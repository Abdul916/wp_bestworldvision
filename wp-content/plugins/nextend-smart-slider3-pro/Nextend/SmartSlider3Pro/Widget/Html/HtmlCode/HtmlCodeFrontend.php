<?php

namespace Nextend\SmartSlider3Pro\Widget\Html\HtmlCode;

use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Widget\AbstractWidgetFrontend;

class HtmlCodeFrontend extends AbstractWidgetFrontend {

    public function __construct($sliderWidget, $widget, $params) {

        parent::__construct($sliderWidget, $widget, $params);

        $this->addToPlacement($this->key . 'position-', array(
            $this,
            'render'
        ));

    }

    public function render($attributes = array()) {

        $slider = $this->slider;
        $params = $this->params;

        $slider->features->addInitCallback("new _N2.SmartSliderWidget(this, 'html', '.n2-widget-html');");
        $slider->sliderType->addJSDependency('SmartSliderWidget');

        $displayAttributes = $this->getDisplayAttributes($params, $this->key, 1);

        return Html::tag('div', Html::mergeAttributes($attributes, $displayAttributes, array(
            "class" => "n2-widget-html"
        )), $params->get($this->key . 'code'));

    }
}