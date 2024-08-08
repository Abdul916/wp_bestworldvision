<?php

namespace Nextend\SmartSlider3Pro\Widget\Bar\BarVertical;

use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Widget\AbstractWidgetFrontend;

class BarVerticalFrontend extends AbstractWidgetFrontend {

    public function __construct($sliderWidget, $widget, $params) {

        parent::__construct($sliderWidget, $widget, $params);

        $this->slider->exposeSlideData['description'] = true;

        $this->addToPlacement($this->key . 'position-', array(
            $this,
            'render'
        ));
    }

    public function render($attributes = array()) {

        $slider = $this->slider;
        $id     = $this->slider->elementId;
        $params = $this->params;

        $slider->addLess(self::getAssetsPath() . '/style.n2less', array(
            "sliderid" => $slider->elementId
        ));

        Js::addStaticGroup(self::getAssetsPath() . '/dist/w-bar-vertical.min.js', 'w-bar-vertical');

        $displayAttributes = $this->getDisplayAttributes($params, $this->key, 1);

        $styleClass = $slider->addStyle($params->get($this->key . 'style'), 'simple');

        $fontTitle       = $slider->addFont($params->get($this->key . 'font-title'), 'simple');
        $fontDescription = $slider->addFont($params->get($this->key . 'font-description'), 'simple');


        $style = 'text-align: ' . $params->get($this->key . 'align', 'left') . ';';

        $width = $params->get($this->key . 'width');
        if (is_numeric($width) || substr($width, -1) == '%' || substr($width, -2) == 'px') {
            $style .= 'width:' . $width . ';';
            if (substr($width, -1) == '%') {
                $attributes['data-width-percent'] = substr($width, 0, -1);
            }
        }

        $height = $params->get($this->key . 'height');
        if (is_numeric($height) || substr($height, -1) == '%' || substr($height, -2) == 'px') {
            $style .= 'height:' . $height . ';';
            if (substr($height, -1) == '%') {
                $attributes['data-height-percent'] = substr($height, 0, -1);
            }
        }

        $parameters = array(
            'area'            => intval($params->get($this->key . 'position-area')),
            'animate'         => intval($params->get($this->key . 'animate')),
            'fontTitle'       => $fontTitle,
            'fontDescription' => $fontDescription
        );

        $slider->features->addInitCallback('new _N2.SmartSliderWidgetBarVertical(this, ' . json_encode($parameters) . ');');
        $slider->sliderType->addJSDependency('SmartSliderWidgetBarVertical');

        return Html::tag("div", Html::mergeAttributes($attributes, $displayAttributes, array(
            "class" => "nextend-bar nextend-bar-vertical n2-ss-widget-hidden n2-ow-all",
            "style" => $style
        )), Html::tag("div", array(
            "class" => $styleClass
        ), Html::tag("div", array(), '')));
    }

    protected function translateArea($area) {

        if ($area == 5) {
            return 'left';
        } else if ($area == 8) {
            return 'right';
        }

        return parent::translateArea($area);
    }
}