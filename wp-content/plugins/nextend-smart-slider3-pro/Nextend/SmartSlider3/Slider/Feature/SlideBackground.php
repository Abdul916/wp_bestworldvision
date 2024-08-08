<?php


namespace Nextend\SmartSlider3\Slider\Feature;


use Nextend\Framework\Image\ImageEdit;
use Nextend\Framework\Image\ImageManager;
use Nextend\Framework\Parser\Color;
use Nextend\Framework\ResourceTranslator\ResourceTranslator;
use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Slider\Slide;
use Nextend\SmartSlider3\Slider\Slider;

class SlideBackground {

    /**
     * @var Slider
     */
    private $slider;

    public function __construct($slider) {

        $this->slider = $slider;
    }

    public function makeJavaScriptProperties(&$properties) {
        $enabled = intval($this->slider->params->get('slide-background-parallax', 0));
        if ($enabled) {
            $properties['backgroundParallax'] = array(
                'strength' => intval($this->slider->params->get('slide-background-parallax-strength', 50)) / 100,
                'tablet'   => intval($this->slider->params->get('bg-parallax-tablet', 0)),
                'mobile'   => intval($this->slider->params->get('bg-parallax-mobile', 0))
            );
        }
    }

    /**
     * @param $slide Slide
     *
     * @return string
     */

    public function make($slide) {

        if ($slide->parameters->get('background-type') == '') {
            $slide->parameters->set('background-type', 'color');
            if ($slide->parameters->get('backgroundVideoMp4')) {
                $slide->parameters->set('background-type', 'video');
            } else if ($slide->parameters->get('backgroundImage')) {
                $slide->parameters->set('background-type', 'image');
            }
        }

        return $this->makeBackground($slide);
    }

    private function getBackgroundStyle($slide) {

        $attributes = array();

        $style = '';
        $color = $slide->fill($slide->parameters->get('backgroundColor', ''));
        if (empty($color)) {
            $color = 'ffffff00';
        }
        if (strlen($color) > 0 && $color[0] == '#') {
            $color = substr($color, 1);
            if (strlen($color) == 6) {
                $color .= 'ff';
            }
        }
        $gradient = $slide->parameters->get('backgroundGradient', 'off');

        if ($gradient != 'off') {
            $colorEnd = $slide->fill($slide->parameters->get('backgroundColorEnd', 'ffffff00'));
            if (empty($colorEnd)) {
                $colorEnd = 'ffffff00';
            }

            if ($colorEnd[0] == '#') {
                $colorEnd = substr($colorEnd, 1);
                if (strlen($colorEnd) == 6) {
                    $colorEnd .= 'ff';
                }
            }

            $startColor = Color::colorToRGBA($color);
            $endColor   = Color::colorToRGBA($colorEnd);

            $attributes['data-gradient']    = $gradient;
            $attributes['data-color-start'] = $startColor;
            $attributes['data-color-end']   = $endColor;

            switch ($gradient) {
                case 'horizontal':
                    $style .= 'background:linear-gradient(to right, ' . $startColor . ' 0%,' . $endColor . ' 100%);';
                    break;
                case 'vertical':
                    $style .= 'background:linear-gradient(to bottom, ' . $startColor . ' 0%,' . $endColor . ' 100%);';
                    break;
                case 'diagonal1':
                    $style .= 'background:linear-gradient(45deg, ' . $startColor . ' 0%,' . $endColor . ' 100%);';
                    break;
                case 'diagonal2':
                    $style .= 'background:linear-gradient(135deg, ' . $startColor . ' 0%,' . $endColor . ' 100%);';
                    break;
            }
        } else {
            if (strlen($color) == 8) {

                $colorRGBA = Color::colorToRGBA($color);
                $style     .= "background-color: " . Color::colorToRGBA($color) . ";";

                $attributes['data-color'] = $colorRGBA;


            }
        }

        $attributes['style'] = $style;

        return $attributes;
    }

    private function makeBackground($slide) {

        $backgroundType = $slide->parameters->get('background-type');

        if (empty($backgroundType)) {
            $backgroundVideoMp4 = $slide->parameters->get('backgroundVideoMp4', '');
            $backgroundImage    = $slide->parameters->get('backgroundImage', '');
            if (!empty($backgroundVideoMp4)) {
                $backgroundType = 'video';
            } else if (!empty($backgroundImage)) {
                $backgroundType = 'image';
            } else {
                $backgroundType = 'color';
            }
        }

        $fillMode = $slide->parameters->get('backgroundMode', 'default');

        if ($fillMode == 'default') {
            $fillMode = $this->slider->params->get('backgroundMode', 'fill');

        }

        $backgroundElements = array();

        if ($backgroundType == 'color') {
            $backgroundElements[] = $this->renderColor($slide);

        } else if ($backgroundType == 'video') {


            $backgroundElements[] = $this->renderBackgroundVideo($slide);
            $backgroundElements[] = $this->renderImage($slide, $fillMode);

            $backgroundElements[] = $this->renderColor($slide);

        } else if ($backgroundType == 'image') {


            $backgroundElements[] = $this->renderImage($slide, $fillMode);

            $backgroundElements[] = $this->renderColor($slide);
        }

        $html = implode('', $backgroundElements);

        /* @see https://bugs.chromium.org/p/chromium/issues/detail?id=1181291
         * if (!$slide->getFrontendFirst()) {
         * $html = '<template>' . $html . '</template>';
         * }
         */

        return Html::tag('div', array(
            'class'          => "n2-ss-slide-background",
            'data-public-id' => $slide->publicID,
            'data-mode'      => $fillMode
        ), $html);
    }

    private function renderColor($slide) {
        $backgroundAttributes = $this->getBackgroundStyle($slide);

        if (!empty($backgroundAttributes['style'])) {

            $backgroundAttributes['class'] = 'n2-ss-slide-background-color';

            if ($slide->parameters->get('backgroundColorOverlay', 0)) {
                $backgroundAttributes['data-overlay'] = 1;
            }

            return Html::tag('div', $backgroundAttributes, '');
        }

        return '';
    }

    /**
     * @param $slide Slide
     * @param $fillMode
     *
     * @return string
     */
    private function renderImage($slide, $fillMode) {

        $rawBackgroundImage = $slide->parameters->get('backgroundImage', '');

        if (empty($rawBackgroundImage)) {
            return '';
        }

        $backgroundImageBlur = max(0, $slide->parameters->get('backgroundImageBlur', 0));

        $focusX = max(0, min(100, $slide->fill($slide->parameters->get('backgroundFocusX', 50))));
        $focusY = max(0, min(100, $slide->fill($slide->parameters->get('backgroundFocusY', 50))));

        $backgroundImageMobile        = '';
        $backgroundImageTablet        = '';
        $backgroundImageDesktopRetina = '';
        $backgroundImage              = $slide->fill($rawBackgroundImage);

        if (!$slide->hasGenerator()) {
            $imageData                    = ImageManager::getImageData($backgroundImage);
            $backgroundImageDesktopRetina = $imageData['desktop-retina']['image'];
            $backgroundImageMobile        = $imageData['mobile']['image'];
            $backgroundImageTablet        = $imageData['tablet']['image'];
        }

        $alt   = $slide->fill($slide->parameters->get('backgroundAlt', ''));
        $title = $slide->fill($slide->parameters->get('backgroundTitle', ''));


        $opacity = min(100, max(0, $slide->parameters->get('backgroundImageOpacity', 100)));

        $style = array();
        if ($opacity < 100) {
            $style[] = 'opacity:' . ($opacity / 100);
        }

        if ($focusX != '50') {
            $style[] = '--ss-o-pos-x:' . $focusX . '%';
        }

        if ($focusY != '50') {
            $style[] = '--ss-o-pos-y:' . $focusY . '%';
        }

        $attributes = array(
            "class"        => 'n2-ss-slide-background-image',
            "data-blur"    => $backgroundImageBlur,
            "data-opacity" => $opacity,
            "data-x"       => $focusX,
            "data-y"       => $focusY,
            "data-alt"     => $alt,
            "data-title"   => $title
        );

        if (!empty($style)) {
            $attributes['style'] = implode(';', $style);
        }

        $sources = array();

        if ($this->slider->isAdmin) {
            $src = $backgroundImage;

            $attributes['data-hash']               = md5($src);
            $attributes['data-src-desktop']        = $src;
            $attributes['data-src-desktop-retina'] = $backgroundImageDesktopRetina;
            $attributes['data-src-tablet']         = $backgroundImageTablet;
            $attributes['data-src-mobile']         = $backgroundImageMobile;
        } else {

            if (empty($backgroundImage)) {
                /**
                 * @todo Does it really work as expected?
                 */
                $src = ImageEdit::base64Transparent();
            } else {
                /**
                 * @todo this resize might have a better place
                 */
                $src = $backgroundImage;
                if ($this->slider->params->get('optimize-webp', 0)) {
                    $src = ResourceTranslator::urlToResource($this->slider->features->optimize->optimizeBackground($backgroundImage, $focusX, $focusY));
                }

                $slide->addImage(ResourceTranslator::toUrl($src));
            }

            $hasDeviceSpecificImage = false;

            $mediaQueries = $this->slider->features->responsive->mediaQueries;

            if (!empty($backgroundImageDesktopRetina)) {

                $hasDeviceSpecificImage = true;

                $backgroundImageDesktopRetina = ResourceTranslator::toUrl($backgroundImageDesktopRetina);

                $mediaQueryMinPixelRatio = ' and (-webkit-min-device-pixel-ratio: 1.5)';

                if (!empty($mediaQueries['desktopportrait'])) {
                    $sources[] = HTML::tag('source', Html::addExcludeLazyLoadAttributes(array(
                        'srcset' => $backgroundImageDesktopRetina,
                        'media'  => implode($mediaQueryMinPixelRatio . ',', $mediaQueries['desktopportrait']) . $mediaQueryMinPixelRatio
                    )), false, false);
                }
                if (!empty($mediaQueries['desktoplandscape'])) {
                    $sources[] = HTML::tag('source', Html::addExcludeLazyLoadAttributes(array(
                        'srcset' => $backgroundImageDesktopRetina,
                        'media'  => implode($mediaQueryMinPixelRatio . ',', $mediaQueries['desktoplandscape']) . $mediaQueryMinPixelRatio
                    )), false, false);
                }

            }

            if (!empty($backgroundImageMobile)) {

                $hasDeviceSpecificImage = true;

                $backgroundImageMobileUrl = ResourceTranslator::toUrl($backgroundImageMobile);

                if (!empty($mediaQueries['mobileportrait'])) {
                    $sources[] = HTML::tag('source', Html::addExcludeLazyLoadAttributes(array(
                        'srcset' => $backgroundImageMobileUrl,
                        'media'  => implode(',', $mediaQueries['mobileportrait'])
                    )), false, false);
                }
                if (!empty($mediaQueries['mobilelandscape'])) {
                    $sources[] = HTML::tag('source', Html::addExcludeLazyLoadAttributes(array(
                        'srcset' => $backgroundImageMobileUrl,
                        'media'  => implode(',', $mediaQueries['mobilelandscape'])
                    )), false, false);
                }
            }

            if (!empty($backgroundImageTablet)) {

                $hasDeviceSpecificImage = true;

                $backgroundImageTabletUrl = ResourceTranslator::toUrl($backgroundImageTablet);

                if (!empty($mediaQueries['tabletportrait'])) {
                    $sources[] = HTML::tag('source', Html::addExcludeLazyLoadAttributes(array(
                        'srcset' => $backgroundImageTabletUrl,
                        'media'  => implode(',', $mediaQueries['tabletportrait'])
                    )), false, false);
                }
                if (!empty($mediaQueries['tabletlandscape'])) {
                    $sources[] = HTML::tag('source', Html::addExcludeLazyLoadAttributes(array(
                        'srcset' => $backgroundImageTabletUrl,
                        'media'  => implode(',', $mediaQueries['tabletlandscape'])
                    )), false, false);
                }
            }
            if (!$hasDeviceSpecificImage) {

                $retinaSupport = !!$this->slider->params->get('optimize-slide-width-retina', 0);
                $optimizeScale = $this->slider->params->get('optimize-scale', 0);
                $optimizedData = $this->slider->features->optimize->optimizeImageWebP($backgroundImage, array(
                    'optimize'         => $this->slider->params->get('optimize-webp', 0),
                    'quality'          => intval($this->slider->params->get('optimize-quality', 70)),
                    'resize'           => $optimizeScale,
                    'defaultWidth'     => intval($this->slider->params->get('optimize-slide-width-normal', 1920)),
                    'mediumWidth'      => intval($this->slider->params->get('optimize-slide-width-tablet', 1200)),
                    'mediumHeight'     => intval($this->slider->params->get('optimize-slide-height-tablet', 0)),
                    'smallWidth'       => intval($this->slider->params->get('optimize-slide-width-mobile', 500)),
                    'smallHeight'      => intval($this->slider->params->get('optimize-slide-height-mobile', 0)),
                    'focusX'           => $focusX,
                    'focusY'           => $focusY,
                    'compressOriginal' => $retinaSupport
                ));


                if ($optimizeScale && $retinaSupport) {

                    $webPSrcSet = array();
                    if (isset($optimizedData['small'])) {
                        $slide->addImage($optimizedData['small']['src']);

                        $webPSrcSet[] = $optimizedData['small']['src'] . ' ' . $optimizedData['small']['width'] . 'w';
                    }

                    if (isset($optimizedData['medium'])) {
                        $slide->addImage($optimizedData['medium']['src']);

                        $webPSrcSet[] = $optimizedData['medium']['src'] . ' ' . $optimizedData['medium']['width'] . 'w';
                    }

                    if (isset($optimizedData['normal'])) {
                        $slide->addImage($optimizedData['normal']['src']);
                        $webPSrcSet[] = $optimizedData['normal']['src'] . ' ' . $optimizedData['normal']['width'] . 'w';
                    }

                    if (isset($optimizedData['original'])) {
                        $slide->addImage($optimizedData['original']['src']);
                        $webPSrcSet[] = $optimizedData['original']['src'] . ' ' . $optimizedData['original']['width'] . 'w';
                    }

                    if (!empty($webPSrcSet)) {
                        $sources[] = HTML::tag('source', Html::addExcludeLazyLoadAttributes(array(
                            'srcset' => implode(',', $webPSrcSet),
                            'type'   => 'image/webp'
                        )), false, false);
                    }
                } else {

                    if (isset($optimizedData['small'])) {
                        $slide->addImage($optimizedData['small']['src']);

                        $sources[] = HTML::tag('source', Html::addExcludeLazyLoadAttributes(array(
                            'srcset' => $optimizedData['small']['src'],
                            'type'   => 'image/webp',
                            'media'  => '(max-width: ' . $optimizedData['small']['width'] . 'px)'
                        )), false, false);
                    }

                    if (isset($optimizedData['medium'])) {
                        $slide->addImage($optimizedData['medium']['src']);

                        $sources[] = HTML::tag('source', Html::addExcludeLazyLoadAttributes(array(
                            'srcset' => $optimizedData['medium']['src'],
                            'type'   => 'image/webp',
                            'media'  => '(max-width: ' . $optimizedData['medium']['width'] . 'px)'
                        )), false, false);
                    }

                    if (isset($optimizedData['normal'])) {
                        $slide->addImage($optimizedData['normal']['src']);

                        $sources[] = HTML::tag('source', Html::addExcludeLazyLoadAttributes(array(
                            'srcset' => $optimizedData['normal']['src'],
                            'type'   => 'image/webp'
                        )), false, false);
                    }
                }
            }
        
        }

        $imageAttributes = array(
            'src'     => ResourceTranslator::toUrl($src),
            'alt'     => $alt,
            'title'   => $title,
            'loading' => 'lazy',
            'style'   => ''
        );

        $imageAttributes = Html::addExcludeLazyLoadAttributes($imageAttributes);

        $sources[] = Html::tag('img', $imageAttributes, '', false);

        $picture = HTML::tag('picture', Html::addExcludeLazyLoadAttributes(), implode('', $sources));

        $originalImage = Html::tag('div', $attributes, $picture);

        if ($fillMode === 'blurfit') {
            $slideOption = $slide->parameters->get('backgroundMode', 'default');

            if ($slideOption === 'blurfit') {
                $blurFit = $slide->parameters->get('backgroundBlurFit', 7);
            } else {
                $blurFit                        = $this->slider->params->get('backgroundBlurFit', 7);
                $attributes['data-blurfitmode'] = 'default';
            }
            $picture      = HTML::tag('picture', Html::addExcludeLazyLoadAttributes(array(
                'style' => 'filter:blur(' . $blurFit . 'px)'
            )), implode('', $sources));
            $blurFitStyle = array(
                'margin:-' . ($blurFit * 2) . 'px',
                'padding:' . ($blurFit * 2) . 'px'
            );
            if (!isset($attributes['style'])) {
                $attributes['style'] = '';
            }

            $attributes['data-globalblur'] = $this->slider->params->get('backgroundBlurFit', 7);
            $attributes['data-bgblur']     = $slide->parameters->get('backgroundBlurFit', 7);
            $attributes['style']           = implode(';', $blurFitStyle);
            $ret                           = Html::tag('div', $attributes, $picture) . $originalImage;
        } else {
            $ret = $originalImage;
        }

        return $ret;
    }

    /**
     * @param Slide $slide
     *
     * @return string
     */
    private function renderBackgroundVideo($slide) {
        $mp4 = ResourceTranslator::toUrl($slide->fill($slide->parameters->get('backgroundVideoMp4', '')));

        if (empty($mp4)) {
            return '';
        }

        $sources = '';

        if ($mp4) {
            $sources .= Html::tag("source", array(
                "src"  => $mp4,
                "type" => "video/mp4"
            ), '', false);
        }

        $opacity = min(100, max(0, $slide->parameters->get('backgroundVideoOpacity', 100)));

        $attributes = array(
            'class'              => 'n2-ss-slide-background-video intrinsic-ignore data-tf-not-load',
            'style'              => 'opacity:' . ($opacity / 100) . ';',
            'data-mode'          => $slide->parameters->get('backgroundVideoMode', 'fill'),
            'playsinline'        => 1,
            'webkit-playsinline' => 1,
            'onloadstart'        => 'this.n2LoadStarted=1;',
            'data-keepplaying'   => 1,
            'preload'            => 'none',
            'muted'              => 'muted'
        );

        if ($slide->parameters->get('backgroundVideoLoop', 1)) {
            $attributes['loop'] = 'loop';
        }

        if ($slide->parameters->get('backgroundVideoReset', 1)) {
            $attributes['data-reset-slide-change'] = 1;
        }

        return Html::tag('video', $attributes, $sources);
    

        return '';
    }
}