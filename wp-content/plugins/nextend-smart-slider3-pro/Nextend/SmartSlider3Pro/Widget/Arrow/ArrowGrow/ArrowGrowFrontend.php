<?php


namespace Nextend\SmartSlider3Pro\Widget\Arrow\ArrowGrow;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Filesystem\Filesystem;
use Nextend\Framework\Misc\Base64;
use Nextend\Framework\Parser\Color;
use Nextend\Framework\ResourceTranslator\ResourceTranslator;
use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Widget\AbstractWidgetFrontend;

class ArrowGrowFrontend extends AbstractWidgetFrontend {

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
            return '';
        }

        $slider->addLess(self::getAssetsPath() . '/style.n2less', array(
            "sliderid" => $slider->elementId
        ));

        Js::addStaticGroup(self::getAssetsPath() . '/dist/w-arrow-grow.min.js', 'w-arrow-grow');

        $previousValue = basename($params->get($this->key . 'previous'));
        if ($previousValue == -1) {
            $previous = false;
        } else {
            $previous = ResourceTranslator::pathToResource(self::getAssetsPath() . '/previous/' . $previousValue);
        }
        $previousColor = $params->get($this->key . 'previous-color');
        if ($params->get($this->key . 'mirror')) {
            if ($previousValue == -1) {
                $next = false;
            } else {
                $next = ResourceTranslator::pathToResource(self::getAssetsPath() . '/next/' . $previousValue);
            }
            $nextColor = $previousColor;
        } else {
            $nextValue = basename($params->get($this->key . 'next'));
            if ($nextValue == -1) {
                $next = false;
            } else {
                $next = ResourceTranslator::pathToResource(self::getAssetsPath() . '/next/' . $nextValue);
            }
            $nextColor = $params->get($this->key . 'next-color');
        }

        $fontClass  = $slider->addFont($params->get($this->key . 'font'), 'hover');
        $styleClass = $slider->addStyle($params->get($this->key . 'style'), 'heading');

        if ($previous) {
            $this->previousArguments = array(
                $id,
                'previous',
                $previous,
                $fontClass,
                $styleClass,
                $previousColor
            );
        }
        if ($next) {
            $this->nextArguments = array(
                $id,
                'next',
                $next,
                $fontClass,
                $styleClass,
                $nextColor
            );
        }

        $slider->features->addInitCallback('new _N2.SmartSliderWidgetArrowGrow(this, ' . $params->get($this->key . 'animation-delay') . ');');
        $slider->sliderType->addJSDependency('SmartSliderWidgetArrowGrow');

    }

    /**
     * @param array  $attributes
     * @param        $id
     * @param        $side
     * @param        $image
     * @param        $fontClass
     * @param        $styleClass
     * @param        $color
     *
     * @return string
     */
    protected function getHTML($attributes, $id, $side, $image, $fontClass, $styleClass, $color) {

        $displayAttributes = $this->getDisplayAttributes($this->params, $this->key);

        $ext = pathinfo($image, PATHINFO_EXTENSION);
        if ($ext == 'svg' && ResourceTranslator::isResource($image)) {

            list($color, $opacity) = Color::colorToSVG($color);
            $image = 'data:image/svg+xml;base64,' . Base64::encode(str_replace(array(
                    'fill="#FFF"',
                    'opacity="1"'
                ), array(
                    'fill="#' . $color . '"',
                    'opacity="' . $opacity . '"'
                ), Filesystem::readFile(ResourceTranslator::toPath($image))));
        } else {
            $image = ResourceTranslator::toUrl($image);
        }

        $label = '';
        switch ($side) {
            case 'previous':
                $label = n2_('Previous slide');
                break;
            case 'next':
                $label = n2_('Next slide');
                break;
        }

        return Html::tag('div', Html::mergeAttributes($attributes, $displayAttributes, array(
            'id'         => $id . '-arrow-' . $side,
            'class'      => $styleClass . 'nextend-arrow n2-ow-all nextend-arrow-grow nextend-arrow-' . $side,
            'role'       => 'button',
            'aria-label' => $label,
            'tabindex'   => '0'
        )), Html::tag('div', array(
                'class' => $fontClass . ' nextend-arrow-title'
            ), '') . Html::tag('div', array(
                'class' => 'nextend-arrow-arrow',
                'style' => 'background-image: url(' . $image . ');'
            ), ''));
    }
}