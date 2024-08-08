<?php


namespace Nextend\SmartSlider3\Slider\Feature;


use Exception;
use Nextend\Framework\FastImageSize\FastImageSize;
use Nextend\Framework\Image\ImageEdit;
use Nextend\Framework\ResourceTranslator\ResourceTranslator;

class Optimize {

    private $slider;

    private $playWhenVisible = 1;

    private $playWhenVisibleAt = 0.5;

    private $backgroundImageWidthNormal = 1920, $quality = 70, $thumbnailWidth = 100, $thumbnailHeight = 60, $thumbnailQuality = 70;

    public function __construct($slider) {

        $this->slider = $slider;

        $this->playWhenVisible   = intval($slider->params->get('playWhenVisible', 1));
        $this->playWhenVisibleAt = max(0, min(100, intval($slider->params->get('playWhenVisibleAt', 50)))) / 100;

        $this->backgroundImageWidthNormal = intval($slider->params->get('optimize-slide-width-normal', 1920));
        $this->quality                    = intval($slider->params->get('optimize-quality', 70));

        $this->thumbnailWidth   = $slider->params->get('optimizeThumbnailWidth', 100);
        $this->thumbnailHeight  = $slider->params->get('optimizeThumbnailHeight', 60);
        $this->thumbnailQuality = $slider->params->get('optimize-thumbnail-quality', 70);


    }

    public function makeJavaScriptProperties(&$properties) {
        $properties['playWhenVisible']   = $this->playWhenVisible;
        $properties['playWhenVisibleAt'] = $this->playWhenVisibleAt;
    }

    public function optimizeBackground($image, $x = 50, $y = 50) {
        try {
            $imageSize = FastImageSize::getSize($image);
            if ($imageSize) {
                $optimizeScale = $this->slider->params->get('optimize-scale', 0);

                $targetWidth  = $imageSize['width'];
                $targetHeight = $imageSize['height'];
                if ($optimizeScale && $targetWidth > $this->backgroundImageWidthNormal) {
                    $targetHeight = ceil($this->backgroundImageWidthNormal / $targetWidth * $targetHeight);
                    $targetWidth  = $this->backgroundImageWidthNormal;
                }

                return ImageEdit::resizeImage('slider/cache', ResourceTranslator::toPath($image), $targetWidth, $targetHeight, false, 'normal', 'ffffff', true, $this->quality, true, $x, $y);
            }

            return $image;

        } catch (Exception $e) {
            return $image;
        }
    }

    public function optimizeThumbnail($image) {
        if ($this->slider->params->get('optimize-thumbnail-scale', 0)) {
            try {
                return ImageEdit::resizeImage('slider/cache', ResourceTranslator::toPath($image), $this->thumbnailWidth, $this->thumbnailHeight, false, 'normal', 'ffffff', true, $this->thumbnailQuality, true);
            } catch (Exception $e) {

                return ResourceTranslator::toUrl($image);
            }
        }

        return ResourceTranslator::toUrl($image);
    }

    public function adminOptimizeThumbnail($image) {
        if ($this->slider->params->get('optimize-thumbnail-scale', 0)) {
            try {
                return ImageEdit::resizeImage('slider/cache', ResourceTranslator::toPath($image), $this->thumbnailWidth, $this->thumbnailHeight, true, 'normal', 'ffffff', true, $this->thumbnailQuality, true);
            } catch (Exception $e) {

                return ResourceTranslator::toUrl($image);
            }
        }

        return ResourceTranslator::toUrl($image);
    }


    public function optimizeImageWebP($src, $options) {

        $options = array_merge(array(
            'optimize'         => false,
            'quality'          => 70,
            'resize'           => false,
            'defaultWidth'     => 1920,
            'mediumWidth'      => 1200,
            'mediumHeight'     => 0,
            'smallWidth'       => 500,
            'smallHeight'      => 0,
            'focusX'           => 50,
            'focusY'           => 50,
            'compressOriginal' => false
        ), $options);
        $data = array();

        if ($options['optimize'] && function_exists('imagewebp')) {

            $resourceSrc = ResourceTranslator::urlToResource($src);
            $imagePath   = ResourceTranslator::toPath($resourceSrc);

            if (isset($imagePath)) {
                $isRemote = false;
                if ($resourceSrc == $imagePath) {
                    //this is a remote image
                    $isRemote  = true;
                    $imagePath = $src;
                } else {
                    $src = $resourceSrc;
                }

                $extension = pathinfo($imagePath, PATHINFO_EXTENSION);

                $originalImageWidth = FastImageSize::getWidth($src);

                if ($extension && $originalImageWidth) {

                    $normalScale = 1;
                    if ($options['resize']) {
                        if ($originalImageWidth > $options['defaultWidth']) {
                            $normalScale = $options['defaultWidth'] / $originalImageWidth;
                        }
                    }

                    $imageWebpUrl = ImageEdit::scaleImageWebp('slider/cache', $src, array(
                        'mode'    => 'scale',
                        'scale'   => $normalScale,
                        'quality' => $options['quality'],
                        'remote'  => $isRemote
                    ));

                    if ($imageWebpUrl) {

                        $width        = FastImageSize::getWidth($imageWebpUrl);
                        $imageWebpUrl = ResourceTranslator::toUrl($imageWebpUrl);

                        $data['normal'] = array(
                            'src'   => $imageWebpUrl,
                            'width' => $width
                        );

                        if ($options['resize']) {

                            if ($options['compressOriginal'] && $normalScale < 1) {

                                $imageWebpUrlOriginal = ImageEdit::scaleImageWebp('slider/cache', $src, array(
                                    'mode'    => 'scale',
                                    'quality' => $options['quality'],
                                    'remote'  => $isRemote
                                ));

                                if ($imageWebpUrlOriginal) {
                                    $width = FastImageSize::getWidth($imageWebpUrlOriginal);
                                    if ($width) {
                                        $imageWebpUrlOriginal = ResourceTranslator::toUrl($imageWebpUrlOriginal);

                                        $data['original'] = array(
                                            'src'   => $imageWebpUrlOriginal,
                                            'width' => $width
                                        );
                                    }
                                }
                            }

                            if ($originalImageWidth > $options['smallWidth'] && $options['defaultWidth'] > $options['smallWidth'] && $options['mediumWidth'] > $options['smallWidth']) {

                                if ($options['smallHeight'] > 0) {

                                    $imageWebpUrlSmall = ImageEdit::scaleImageWebp('slider/cache', $src, array(
                                        'mode'    => 'resize',
                                        'width'   => $options['smallWidth'],
                                        'height'  => $options['smallHeight'],
                                        'focusX'  => $options['focusX'],
                                        'focusY'  => $options['focusY'],
                                        'quality' => $options['quality'],
                                        'remote'  => $isRemote
                                    ));
                                } else {
                                    $mobileScale = $options['smallWidth'] / $originalImageWidth;

                                    $imageWebpUrlSmall = ImageEdit::scaleImageWebp('slider/cache', $src, array(
                                        'mode'    => 'scale',
                                        'scale'   => $mobileScale,
                                        'quality' => $options['quality'],
                                        'remote'  => $isRemote
                                    ));
                                }

                                if ($imageWebpUrlSmall) {
                                    $width = FastImageSize::getWidth($imageWebpUrlSmall);
                                    if ($width) {
                                        $imageWebpUrlSmall = ResourceTranslator::toUrl($imageWebpUrlSmall);

                                        $data['small'] = array(
                                            'src'   => $imageWebpUrlSmall,
                                            'width' => $width
                                        );
                                    }
                                }
                            }

                            if ($originalImageWidth > $options['mediumWidth'] && $options['defaultWidth'] > $options['mediumWidth']) {

                                if ($options['mediumHeight'] > 0) {

                                    $imageWebpUrlMedium = ImageEdit::scaleImageWebp('slider/cache', $src, array(
                                        'mode'    => 'resize',
                                        'width'   => $options['mediumWidth'],
                                        'height'  => $options['mediumHeight'],
                                        'focusX'  => $options['focusX'],
                                        'focusY'  => $options['focusY'],
                                        'quality' => $options['quality'],
                                        'remote'  => $isRemote
                                    ));
                                } else {
                                    $tabletScale = $options['mediumWidth'] / $originalImageWidth;

                                    $imageWebpUrlMedium = ImageEdit::scaleImageWebp('slider/cache', $src, array(
                                        'mode'    => 'scale',
                                        'scale'   => $tabletScale,
                                        'quality' => $options['quality'],
                                        'remote'  => $isRemote
                                    ));
                                }

                                if ($imageWebpUrlMedium) {
                                    $width = FastImageSize::getWidth($imageWebpUrlMedium);
                                    if ($width) {
                                        $imageWebpUrlMedium = ResourceTranslator::toUrl($imageWebpUrlMedium);

                                        $data['medium'] = array(
                                            'src'   => $imageWebpUrlMedium,
                                            'width' => $width
                                        );
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $data;
    
    }
}