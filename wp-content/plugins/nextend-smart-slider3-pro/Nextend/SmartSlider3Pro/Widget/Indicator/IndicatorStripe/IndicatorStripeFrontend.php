<?php


namespace Nextend\SmartSlider3Pro\Widget\Indicator\IndicatorStripe;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Parser\Color;
use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Widget\AbstractWidgetFrontend;

class IndicatorStripeFrontend extends AbstractWidgetFrontend {

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

        if (!$params->get('autoplay', 0)) {
            return '';
        }

        $slider->addLess(self::getAssetsPath() . '/style.n2less', array(
            "sliderid" => $slider->elementId
        ));

        Js::addStaticGroup(self::getAssetsPath() . '/dist/w-indicator-stripe.min.js', 'w-indicator-stripe');

        $displayAttributes = $this->getDisplayAttributes($params, $this->key);

        $trackRGBA = Color::colorToRGBA($params->get($this->key . 'track'));
        $barRGBA   = Color::colorToRGBA($params->get($this->key . 'bar'));

        $style = '';

        $width = $params->get($this->key . 'width');
        if (is_numeric($width) || substr($width, -1) == '%' || substr($width, -2) == 'px') {
            $style .= 'width:' . $width . ';';
        }

        $height = intval($params->get($this->key . 'height'));

        $parameters = array(
            'area' => intval($params->get($this->key . 'position-area'))
        );

        $slider->features->addInitCallback('new _N2.SmartSliderWidgetIndicatorStripe(this, ' . json_encode($parameters) . ');');
        $slider->sliderType->addJSDependency('SmartSliderWidgetIndicatorStripe');

        return Html::tag('div', Html::mergeAttributes($attributes, $displayAttributes, array(
            'class' => "nextend-indicator nextend-indicator-stripe n2-ow-all",
            'style' => 'background-color:' . $trackRGBA . ';' . $style
        )), Html::tag('div', array(
            'class' => "nextend-indicator-track",
            'style' => 'height: ' . $height . 'px;background-color:' . $barRGBA . ';'
        ), ''));
    }
}