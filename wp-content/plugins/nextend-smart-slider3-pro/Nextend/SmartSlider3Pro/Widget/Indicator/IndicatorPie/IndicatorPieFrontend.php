<?php


namespace Nextend\SmartSlider3Pro\Widget\Indicator\IndicatorPie;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Parser\Color;
use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Widget\AbstractWidgetFrontend;

class IndicatorPieFrontend extends AbstractWidgetFrontend {

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

        Js::addStaticGroup(self::getAssetsPath() . '/dist/w-indicator-pie.min.js', 'w-indicator-pie');

        $displayAttributes = $this->getDisplayAttributes($params, $this->key);

        $track      = Color::colorToSVG($params->get($this->key . 'track'));
        $bar        = Color::colorToSVG($params->get($this->key . 'bar'));
        $parameters = array(
            'backstroke'         => $track[0],
            'backstrokeopacity'  => $track[1],
            'frontstroke'        => $bar[0],
            'frontstrokeopacity' => $bar[1],
            'size'               => intval($params->get($this->key . 'size')),
            'thickness'          => $params->get($this->key . 'thickness') / 100
        );

        $styleClass = $slider->addStyle($params->get($this->key . 'style'), 'heading');

        $slider->features->addInitCallback('new _N2.SmartSliderWidgetIndicatorPie(this, ' . json_encode($parameters) . ');');
        $slider->sliderType->addJSDependency('SmartSliderWidgetIndicatorPie');

        return Html::tag('div', Html::mergeAttributes($attributes, $displayAttributes, array(
            'class' => $styleClass . " nextend-indicator nextend-indicator-pie n2-ow-all"
        )));
    }
}