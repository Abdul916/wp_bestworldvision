<?php


namespace Nextend\SmartSlider3\Slider\SliderType;


use Nextend\Framework\Asset\Builder\BuilderJs;
use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Data\Data;
use Nextend\Framework\Filesystem\Filesystem;
use Nextend\Framework\Parser\Color;
use Nextend\Framework\ResourceTranslator\ResourceTranslator;
use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Application\Frontend\ApplicationTypeFrontend;
use Nextend\SmartSlider3\Slider\Slider;
use Nextend\SmartSlider3\Widget\SliderWidget;

abstract class AbstractSliderTypeFrontend {

    /**
     * @var Slider
     */
    protected $slider;

    protected $jsDependency = array(
        'documentReady',
        'smartslider-frontend'
    );

    protected $javaScriptProperties;

    /** @var  SliderWidget */
    protected $widgets;

    protected $shapeDividerAdded = false;

    protected $style = '';

    public function __construct($slider) {
        $this->slider = $slider;

        $this->enqueueAssets();
    }

    public function addJSDependency($dependency) {
        $this->jsDependency[] = $dependency;
    }

    protected $classes = array();

    public function addClass($className) {
        $this->classes[] = $className;
    }

    /**
     * @param AbstractSliderTypeCss $css
     *
     * @return string
     */
    public function render($css) {

        $this->javaScriptProperties = $this->slider->features->generateJSProperties();

        $this->widgets = new SliderWidget($this->slider);

        ob_start();
        $this->renderType($css);

        return ob_get_clean();
    }

    /**
     * @param AbstractSliderTypeCss $css
     *
     * @return string
     */
    protected abstract function renderType($css);

    protected function getSliderClasses() {

        return $this->slider->getAlias() . ' ' . implode(' ', $this->classes);
    }

    protected function openSliderElement() {

        $attributes = array(
            'id'              => $this->slider->elementId,
            'data-creator'    => 'Smart Slider 3',
            'data-responsive' => $this->slider->features->responsive->type,
            'class'           => 'n2-ss-slider n2-ow n2-has-hover n2notransition ' . $this->getSliderClasses(),
        );

        if ($this->slider->isLegacyFontScale()) {
            $attributes['data-ss-legacy-font-scale'] = 1;
        }

        return Html::openTag('div', $attributes);
    }

    protected function closeSliderElement() {

        return '</div>';
    }

    public function getDefaults() {
        return array();
    }

    /**
     * @param $params Data
     */
    public function limitParams($params) {

    }

    protected function encodeJavaScriptProperties() {

        $initCallback = implode($this->javaScriptProperties['initCallbacks']);
        unset($this->javaScriptProperties['initCallbacks']);

        $encoded = array();
        foreach ($this->javaScriptProperties as $k => $v) {
            $encoded[] = '"' . $k . '":' . json_encode($v);
        }
        $encoded[] = '"initCallbacks":function(){' . $initCallback . '}';

        return '{' . implode(',', $encoded) . '}';
    }


    protected function initParticleJS() {
        $particle = $this->slider->params->get('particle');
        if ($this->slider->isAdmin || empty($particle)) {
            return;
        }
        $particle = new Data($particle, true);
        $preset   = $particle->get('preset', '0');
        if ($preset != '0') {

            Js::addStaticGroup(ResourceTranslator::toPath('$ss3-pro-frontend$/dist/particle.min.js'), 'particles');

            $custom = $particle->get('custom', '');
            if ($preset == 'custom' && is_array($custom)) {
                $jsProp = $custom;
            } else {
                $jsProp = json_decode(Filesystem::readFile(ResourceTranslator::toPath('$ss3-pro-frontend$/js/particle/presets/' . $particle->get('preset') . '.json')), true);

                $color                                   = Color::colorToSVG($particle->get('color'));
                $jsProp['particles']["color"]["value"]   = '#' . $color[0];
                $jsProp['particles']["opacity"]["value"] = $color[1];

                $lineColor                                     = Color::colorToSVG($particle->get('line-color'));
                $jsProp['particles']["line_linked"]["color"]   = '#' . $lineColor[0];
                $jsProp['particles']["line_linked"]["opacity"] = $lineColor[1];

                $hover = $particle->get('hover', 0);
                if ($hover == '0') {
                    $jsProp['interactivity']["events"]["onhover"]['enable'] = 0;
                } else {
                    $jsProp['interactivity']["events"]["onhover"]['enable'] = 1;
                    $jsProp['interactivity']["events"]["onhover"]['mode']   = $hover;
                }

                $click = $particle->get('click', 0);
                if ($click == '0') {
                    $jsProp['interactivity']["events"]["onclick"]['enable'] = 0;
                } else {
                    $jsProp['interactivity']["events"]["onclick"]['enable'] = 1;
                    $jsProp['interactivity']["events"]["onclick"]['mode']   = $click;
                }

                $jsProp['particles']["number"]["value"] = max(10, min(200, $particle->get('number')));

                $jsProp['particles']["move"]["speed"] = max(1, min(60, $particle->get('speed')));
            }

            $jsProp['mobile'] = intval($particle->get('mobile', 0));

            $this->javaScriptProperties['particlejs'] = $jsProp;
        }
    
    }

    protected function renderShapeDividers() {
        $shapeDividers = $this->slider->params->get('shape-divider');
        if (!empty($shapeDividers)) {
            $shapeDividers = json_decode($shapeDividers, true);
            if ($shapeDividers) {
                $this->renderShapeDivider('top', $shapeDividers['top']);
                $this->renderShapeDivider('bottom', $shapeDividers['bottom']);
            }
        }
    
    }

    private function renderShapeDivider($side, $params) {
        $data = new Data($params);
        $type = $data->get('type', "0");
        if ($type != "0") {
            preg_match('/([a-z]+)\-(.*)/', $type, $matches);

            $type = $matches[2];
            switch ($matches[1]) {
                case 'bi':
                    $type = 'bicolor/' . $type;
                    break;
            }

            $file = ResourceTranslator::toPath('$ss3-pro-frontend$/shapedivider/' . $type . '.svg');
            if (Filesystem::existsFile($file)) {

                $animate = $data->get('animate') == '1';

                $id       = $this->slider->elementId . '-shape-divider-' . $side;
                $selector = 'div#' . $id;

                $height = max(0, $data->get('desktopportraitheight'));

                if ($height > 0) {
                    $this->slider->addDeviceCSS('all', $selector . '{' . 'height:' . $height . 'px}');
                } else {
                    $this->slider->addDeviceCSS('all', $selector . '{' . 'display:none}');
                }

                $width = $data->get('desktopportraitwidth');
                if ($width != 100) {
                    $this->slider->addDeviceCSS('all', $selector . ' .n2-ss-shape-divider-inner{' . 'width:' . $width . '%;margin: 0 ' . (($width - 100) / -2) . '%;}');

                }

                foreach ($this->slider->features->responsive->sizes as $device => $size) {
                    if ($device === 'desktopPortrait') continue;

                    $device = strtolower($device);

                    $deviceHeight = max(0, $data->get($device . 'height'));

                    if ($height != $deviceHeight) {
                        if ($height > 0) {
                            $this->slider->addDeviceCSS($device, $selector . '{' . 'height:' . $deviceHeight . 'px}');
                        } else {
                            $this->slider->addDeviceCSS($device, $selector . '{' . 'display:none}');
                        }
                    }

                    $deviceWidth = $data->get($device . 'width');
                    if ($width != $deviceWidth) {
                        $this->slider->addDeviceCSS($device, $selector . ' .n2-ss-shape-divider-inner{' . 'width:' . $deviceWidth . '%;margin: 0 ' . (($deviceWidth - 100) / -2) . '%;}');
                    }
                }

                $scroll = $data->get('scroll', null);

                $outer = array(
                    'id'                 => $id,
                    'class'              => 'n2-ss-shape-divider n2-ss-shape-divider-' . $side,
                    'data-ss-sd-animate' => ($animate ? 1 : 0),
                    'data-ss-sd-scroll'  => $scroll,
                    'data-ss-sd-speed'   => $data->get('speed', 100),
                    'data-ss-sd-side'    => $side
                );

                $inner = array(
                    'class' => 'n2-ss-shape-divider-inner',
                );
                switch ($scroll) {
                    case 'shrink':
                    case 'grow':
                        $inner['style'] = 'transform:scaleY(0)';
                        break;
                }

                $svg = Filesystem::readFile($file);

                if (!$animate) {
                    /**
                     * when not animated, we do not need the start state of the svg
                     */
                    $svg = preg_replace_callback('/((to-)?d)(=".*?")/', function ($match) {
                        if ($match[1] === 'd') {
                            return '';
                        }

                        return 'd' . $match[3];
                    }, $svg);
                }

                /**
                 * compress svg
                 */
                $svg = preg_replace('/\s\s+/', ' ', preg_replace('/[\n\r\t]/', '', $svg));

                if ($side == 'bottom') {
                    if ($data->get('flip') == '1') {
                        $svg = SVGFlip::mirror($svg, true, true);
                    } else {
                        $svg = SVGFlip::mirror($svg, false, true);
                    }
                } else {
                    if ($data->get('flip') == '1') {
                        $svg = SVGFlip::mirror($svg, true, false);
                    }
                }

                $output = Html::tag('div', $outer, Html::tag('div', $inner, str_replace(array(
                    '#000000',
                    '#000010'
                ), array(
                    Color::colorToRGBA($data->get('color')),
                    Color::colorToRGBA($data->get('color2'))
                ), $svg)));
                // PHPCS - Content already escaped
                echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

                if (!$this->shapeDividerAdded) {

                    $path = ResourceTranslator::toPath('$ss3-pro-frontend$/dist/shapedivider.min.js');
                    if (file_exists($path)) {
                        $this->javaScriptProperties['initCallbacks'][] = file_get_contents($path);
                    } else {
                    }

                    $this->shapeDividerAdded = true;
                }
            }
        }
    
    }

    /**
     * @return string
     */
    public function getScript() {
        return '';
    }

    public function getStyle() {
        return $this->style;
    }

    public function setJavaScriptProperty($key, $value) {
        $this->javaScriptProperties[$key] = $value;
    }

    public function enqueueAssets() {

        Js::addStaticGroup(ApplicationTypeFrontend::getAssetsPath() . '/dist/smartslider-frontend.min.js', 'smartslider-frontend');
    }

    public function handleSliderMinHeight($minHeight) {

        $this->slider->addDeviceCSS('all', 'div#' . $this->slider->elementId . ' .n2-ss-slider-1{min-height:' . $minHeight . 'px;}');
    }

    public function displaySizeSVGs($css, $hasMaxWidth = false) {
        if (!$this->slider->isAdmin && $this->slider->features->responsive->type == 'fullpage' && !intval($this->slider->params->get('responsiveConstrainRatio', 0))) {

            /**
             * Full page responsive type with constrain ratio off does not use the initial slider size to prevent too large heights.
             */
            return;
        }
    

        $attrs = array(
            'xmlns'               => "http://www.w3.org/2000/svg",
            'viewBox'             => '0 0 ' . $css->base['sliderWidth'] . ' ' . $css->base['sliderHeight'],
            'data-related-device' => "desktopPortrait",
            'class'               => "n2-ow n2-ss-preserve-size n2-ss-preserve-size--slider n2-ss-slide-limiter"
        );
        if ($hasMaxWidth) {
            $attrs['style'] = 'max-width:' . $css->base['sliderWidth'] . 'px';
        }

        $svgs = array(
            Html::tag('svg', $attrs, '')
        );

        foreach ($this->slider->features->responsive->sizes as $device => $size) {
            if ($device === 'desktopPortrait') continue;

            if ($size['customHeight'] && $size['width'] > 0 && $size['height'] > 0) {

                $attrs['viewBox']             = '0 0 ' . $size['width'] . ' ' . $size['height'];
                $attrs['data-related-device'] = $device;
                if ($hasMaxWidth) {
                    $attrs['style'] = 'max-width:' . $size['width'] . 'px';
                }

                $svgs[] = Html::tag('svg', $attrs, '');

                $styles = array(
                    'div#' . $this->slider->elementId . ' .n2-ss-preserve-size[data-related-device="desktopPortrait"] {display:none}',
                    'div#' . $this->slider->elementId . ' .n2-ss-preserve-size[data-related-device="' . $device . '"] {display:block}'
                );
                $this->slider->addDeviceCSS(strtolower($device), implode('', $styles));
            }

        }

        // PHPCS - Content already escaped
        echo implode('', $svgs);  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }

    protected function initSliderBackground($selector) {

        $params = $this->slider->params;

        $backgroundImage = $params->get('background');
        $backgroundColor = $params->get('background-color', '');

        $sliderCSS2 = '';

        if (!empty($backgroundImage)) {
            $sliderCSS2 .= 'background-image: url(' . ResourceTranslator::toUrl($backgroundImage) . ');';
        }
        if (!empty($backgroundColor)) {
            $rgba = Color::hex2rgba($backgroundColor);
            if ($rgba[3] != 0) {
                $sliderCSS2 .= 'background-color:RGBA(' . $rgba[0] . ',' . $rgba[1] . ',' . $rgba[2] . ',' . round($rgba[3] / 127, 2) . ');';
            }
        }

        if (!empty($sliderCSS2)) {

            $this->slider->addCSS('div#' . $this->slider->elementId . ' ' . $selector . '{' . $sliderCSS2 . '}');
            if (!empty($backgroundImage)) {

                $optimizedData = $this->slider->features->optimize->optimizeImageWebP($backgroundImage, array(
                    'optimize'     => $this->slider->params->get('optimize-slider-webp', 0),
                    'quality'      => intval($this->slider->params->get('optimize-slider-quality', 70)),
                    'resize'       => $this->slider->params->get('optimize-slider-scale', 0),
                    'defaultWidth' => intval($this->slider->params->get('optimize-slider-width-normal', 1920)),
                    'mediumWidth'  => intval($this->slider->params->get('optimize-slider-width-tablet', 1200)),
                    'mediumHeight' => intval($this->slider->params->get('optimize-slider-height-tablet', 0)),
                    'smallWidth'   => intval($this->slider->params->get('optimize-slider-width-mobile', 500)),
                    'smallHeight'  => intval($this->slider->params->get('optimize-slider-height-mobile', 0)),
                ));


                if (isset($optimizedData['normal'])) {
                    $this->slider->addImage($optimizedData['normal']['src']);

                    $this->slider->addCSS('.n2webp div#' . $this->slider->elementId . ' ' . $selector . '{background-image: url(' . $optimizedData['normal']['src'] . ')}');
                }

                if (isset($optimizedData['medium'])) {
                    $this->slider->addImage($optimizedData['medium']['src']);

                    $this->slider->addCSS('@media (max-width: ' . $optimizedData['medium']['width'] . 'px) {.n2webp div#' . $this->slider->elementId . ' ' . $selector . '{background-image: url(' . $optimizedData['medium']['src'] . ')}}');
                }

                if (isset($optimizedData['small'])) {
                    $this->slider->addImage($optimizedData['small']['src']);

                    $this->slider->addCSS('@media (max-width: ' . $optimizedData['small']['width'] . 'px) {.n2webp div#' . $this->slider->elementId . ' ' . $selector . '{background-image: url(' . $optimizedData['small']['src'] . ')}}');

                }

            }
        
        }
    }

    protected function getBackgroundVideo($params) {
        $mp4 = ResourceTranslator::toUrl($params->get('backgroundVideoMp4', ''));

        if (empty($mp4)) {
            return '';
        }

        $attributes = array();

        if ($params->get('backgroundVideoLoop', 1)) {
            $attributes['loop'] = 'loop';
        }

        $objectFit = 'cover';
        if ($params->get('backgroundVideoMode', 'fill') == 'fit') {

            $objectFit = 'contain';
        }

        return Html::tag('video', $attributes + array(
                'class'              => 'n2-ss-slider-background-video n2-ow n2-' . $objectFit,
                'playsinline'        => 1,
                'webkit-playsinline' => 1,
                'data-keepplaying'   => 1,
                'preload'            => 'none',
                'muted'              => 'muted'
            ), Html::tag("source", array(
            "src"  => $mp4,
            "type" => "video/mp4"
        ), '', false));
    }
}