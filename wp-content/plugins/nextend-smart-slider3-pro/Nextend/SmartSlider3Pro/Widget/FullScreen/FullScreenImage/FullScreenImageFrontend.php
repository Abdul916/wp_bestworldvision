<?php


namespace Nextend\SmartSlider3Pro\Widget\FullScreen\FullScreenImage;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Cast;
use Nextend\Framework\FastImageSize\FastImageSize;
use Nextend\Framework\Filesystem\Filesystem;
use Nextend\Framework\Misc\Base64;
use Nextend\Framework\Parser\Color;
use Nextend\Framework\ResourceTranslator\ResourceTranslator;
use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Widget\AbstractWidgetFrontend;

class FullScreenImageFrontend extends AbstractWidgetFrontend {

    public function __construct($sliderWidget, $widget, $params) {

        parent::__construct($sliderWidget, $widget, $params);

        $this->addToPlacement($this->key . 'position-', array(
            $this,
            'render'
        ));

    }

    public function render($attributes = array()) {

        $slider = $this->slider;
        $id     = $this->slider->elementId;
        $params = $this->params;

        $html = '';

        $sizeAttributes = array();

        $toNormalImage = $params->get($this->key . 'tonormal-image');
        $toNormalValue = $params->get($this->key . 'tonormal');
        $toNormalColor = $params->get($this->key . 'tonormal-color');

        if (empty($toNormalImage)) {
            if ($toNormalValue == -1) {
                $toNormal = null;
            } else {
                $toNormal = ResourceTranslator::pathToResource(self::getAssetsPath() . '/tonormal/' . basename($toNormalValue));
            }
        } else {
            $toNormal = $toNormalImage;
        }

        if ($params->get($this->key . 'mirror')) {
            $toFullColor = $toNormalColor;
            if (!empty($toNormalImage)) {
                $toFull = $toNormalImage;
            } else {
                $toFull = ResourceTranslator::pathToResource(self::getAssetsPath() . '/tofull/' . basename($toNormalValue));
            }
        } else {
            $toFull      = $params->get($this->key . 'tofull-image');
            $toFullColor = $params->get($this->key . 'tofull-color');
            if (empty($toFull)) {
                $toFullValue = $params->get($this->key . 'tofull');
                if ($toFull == -1) {
                    $toFull = null;
                } else {
                    $toFull = ResourceTranslator::pathToResource(self::getAssetsPath() . '/tofull/' . basename($toFullValue));
                }
            }
        }


        if ($toNormal && $toFull) {

            $desktopWidth = $params->get('widget-fullscreen-desktop-image-width');
            $tabletWidth  = $params->get('widget-fullscreen-tablet-image-width');
            $mobileWidth  = $params->get('widget-fullscreen-mobile-image-width');

            $slider->addDeviceCSS('all', '#' . $id . ' .n2-full-screen-widget img{width: ' . $desktopWidth . 'px}');
            if ($tabletWidth != $desktopWidth) {
                $slider->addDeviceCSS('tabletportrait', 'div#' . $id . ' .n2-full-screen-widget img{width: ' . $tabletWidth . 'px}');
                $slider->addDeviceCSS('tabletlandscape', 'div#' . $id . ' .n2-full-screen-widget img{width: ' . $tabletWidth . 'px}');
            }
            if ($mobileWidth != $desktopWidth) {
                $slider->addDeviceCSS('mobileportrait', 'div#' . $id . ' .n2-full-screen-widget img{width: ' . $mobileWidth . 'px}');
                $slider->addDeviceCSS('mobilelandscape', 'div#' . $id . ' .n2-full-screen-widget img{width: ' . $mobileWidth . 'px}');
            }

            FastImageSize::initAttributes($toNormal, $sizeAttributes);

            $ext = pathinfo($toNormal, PATHINFO_EXTENSION);
            if ($ext == 'svg' && ResourceTranslator::isResource($toNormal)) {
                list($color, $opacity) = Color::colorToSVG($toNormalColor);
                $toNormal = 'data:image/svg+xml;base64,' . Base64::encode(str_replace(array(
                        'fill="#FFF"',
                        'opacity="1"'
                    ), array(
                        'fill="#' . $color . '"',
                        'opacity="' . $opacity . '"'
                    ), Filesystem::readFile(ResourceTranslator::toPath($toNormal))));
            } else {
                $toNormal = ResourceTranslator::toUrl($toNormal);
            }

            $ext = pathinfo($toFull, PATHINFO_EXTENSION);
            if ($ext == 'svg' && ResourceTranslator::isResource($toFull)) {
                list($color, $opacity) = Color::colorToSVG($toFullColor);
                $toFull = 'data:image/svg+xml;base64,' . Base64::encode(str_replace(array(
                        'fill="#FFF"',
                        'opacity="1"'
                    ), array(
                        'fill="#' . $color . '"',
                        'opacity="' . $opacity . '"'
                    ), Filesystem::readFile(ResourceTranslator::toPath($toFull))));
            } else {
                $toFull = ResourceTranslator::toUrl($toFull);
            }

            $slider->addLess(self::getAssetsPath() . '/style.n2less', array(
                "sliderid" => $slider->elementId
            ));

            Js::addStaticGroup(self::getAssetsPath() . '/dist/w-fullscreen.min.js', 'w-fullscreen');

            $displayAttributes = $this->getDisplayAttributes($params, $this->key);

            $styleClass = $slider->addStyle($params->get($this->key . 'style'), 'heading');


            $slider->features->addInitCallback('new _N2.SmartSliderWidgetFullScreenImage(this, ' . Cast::floatToString($params->get($this->key . 'responsive-desktop')) . ', ' . Cast::floatToString($params->get($this->key . 'responsive-tablet')) . ', ' . Cast::floatToString($params->get($this->key . 'responsive-mobile')) . ');');
            $slider->sliderType->addJSDependency('SmartSliderWidgetFullScreenImage');

            $html = Html::tag('div', Html::mergeAttributes($attributes, $displayAttributes, array(
                'class' => $styleClass . 'n2-full-screen-widget n2-ow-all n2-full-screen-widget-image nextend-fullscreen'
            )), Html::image($toNormal, n2_('Exit full screen'), $sizeAttributes + Html::addExcludeLazyLoadAttributes(array(
                        'class'    => 'n2-full-screen-widget-to-normal',
                        'role'     => 'button',
                        'tabindex' => '0'
                    ))) . Html::image($toFull, n2_('Enter Full screen'), $sizeAttributes + Html::addExcludeLazyLoadAttributes(array(
                        'class'    => 'n2-full-screen-widget-to-full',
                        'role'     => 'button',
                        'tabindex' => '0'
                    ))));
        }

        return $html;
    }
}