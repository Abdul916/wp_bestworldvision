<?php


namespace Nextend\SmartSlider3Pro\Widget\Arrow\ArrowText;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Widget\AbstractWidgetFrontend;

class ArrowTextFrontend extends AbstractWidgetFrontend {

    protected $rendered = false;

    protected $previousArguments;
    protected $nextArguments;

    public function __construct($sliderWidget, $widget, $params) {

        parent::__construct($sliderWidget, $widget, $params);

        $this->addToPlacement($this->key . 'previous-position-', array(
            $this,
            'renderPrevious'
        ));

        $this->addToPlacement($this->key . 'next-position-', array(
            $this,
            'renderNext'
        ));
    }

    public function renderPrevious($attributes = array()) {

        $this->render();

        if ($this->previousArguments) {

            array_unshift($this->previousArguments, $attributes);

            return call_user_func_array(array(
                $this,
                'getHTML'
            ), $this->previousArguments);
        }

        return '';
    }

    public function renderNext($attributes = array()) {

        $this->render();

        if ($this->nextArguments) {

            array_unshift($this->nextArguments, $attributes);

            return call_user_func_array(array(
                $this,
                'getHTML'
            ), $this->nextArguments);
        }

        return '';
    }

    private function render() {

        if ($this->rendered) return;

        $this->rendered = true;

        $slider = $this->slider;
        $id     = $this->slider->elementId;
        $params = $this->params;
        if ($slider->getSlidesCount() <= 1) {
            return;
        }

        Js::addStaticGroup(self::getAssetsPath() . '/dist/w-arrow-text.min.js', 'w-arrow-text');

        $slider->features->addInitCallback('new _N2.SmartSliderWidgetArrowText(this);');
        $slider->sliderType->addJSDependency('SmartSliderWidgetArrowText');

        $displayAttributes = $this->getDisplayAttributes($params, $this->key);

        $slider->addLess(self::getAssetsPath() . '/style.n2less', array(
            "sliderid" => $slider->elementId
        ));

        $font  = $slider->addFont($params->get($this->key . 'font'), 'hover');
        $style = $slider->addStyle($params->get($this->key . 'style'), 'heading');

        $this->previousArguments = array(
            $id,
            'previous',
            $displayAttributes,
            $font,
            $style
        );
        $this->nextArguments     = array(
            $id,
            'next',
            $displayAttributes,
            $font,
            $style
        );
    }

    private function getHtml($attributes, $id, $side, $displayAttributes, $font, $styleClass) {

        $label = '';
        switch ($side) {
            case 'previous':
                $label = n2_('Previous slide');
                break;
            case 'next':
                $label = n2_('Next slide');
                break;
        }

        $html = Html::openTag("div", Html::mergeAttributes($attributes, $displayAttributes, array(
            'id'         => $id . '-arrow-' . $side,
            "class"      => "nextend-arrow nextend-arrow-{$side} n2-ow-all",
            'tabindex'   => '0',
            'role'       => 'button',
            'aria-label' => $label
        )));


        $html .= Html::tag('div', array(
            "class" => $styleClass . ' ' . $font . ' nextend-arrow-text',
            "style" => 'display:inline-block'
        ), $this->params->get($this->key . $side . '-label'));

        $html .= Html::closeTag("div");

        return $html;
    }
}