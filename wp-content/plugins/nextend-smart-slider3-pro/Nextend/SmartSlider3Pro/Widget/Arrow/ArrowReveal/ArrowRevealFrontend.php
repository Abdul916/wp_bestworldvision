<?php


namespace Nextend\SmartSlider3Pro\Widget\Arrow\ArrowReveal;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Filesystem\Filesystem;
use Nextend\Framework\Misc\Base64;
use Nextend\Framework\Parser\Color;
use Nextend\Framework\ResourceTranslator\ResourceTranslator;
use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Widget\AbstractWidgetFrontend;

class ArrowRevealFrontend extends AbstractWidgetFrontend {

    protected $rendered = false;

    protected $previousArguments;
    protected $nextArguments;

    public function __construct($sliderWidget, $widget, $params) {

        parent::__construct($sliderWidget, $widget, $params);

        $this->slider->exposeSlideData['thumbnail'] = true;

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

        $RGBA      = Color::colorToRGBA($params->get($this->key . 'background'));
        $titleRGBA = Color::colorToRGBA($params->get($this->key . 'title-background'));


        $slider->addLess(self::getAssetsPath() . '/style.n2less', array(
            "sliderid"            => $slider->elementId,
            "arrowBackgroundRGBA" => $RGBA,
            "titleBackgroundRGBA" => $titleRGBA
        ));

        Js::addStaticGroup(self::getAssetsPath() . '/dist/w-arrow-reveal.min.js', 'w-arrow-reveal');

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

        $fontClass = $slider->addFont($params->get($this->key . 'title-font'), 'simple');

        $animation      = $params->get($this->key . 'animation');
        $animationClass = ' n2-ss-arrow-animation-' . $animation;

        if ($previous) {
            $this->previousArguments = array(
                $id,
                'previous',
                $previous,
                $fontClass,
                $animationClass,
                $previousColor
            );
        }
        if ($next) {
            $this->nextArguments = array(
                $id,
                'next',
                $next,
                $fontClass,
                $animationClass,
                $nextColor
            );
        }

        $slider->features->addInitCallback('new _N2.SmartSliderWidgetArrowReveal(this,"' . $animation . '");');
        $slider->sliderType->addJSDependency('SmartSliderWidgetArrowReveal');

    }

    /**
     * @param array $attributes
     * @param       $id
     * @param       $side
     * @param       $image
     * @param       $fontClass
     * @param       $animationClass
     * @param       $color
     *
     * @return string
     */
    private function getHTML($attributes, $id, $side, $image, $fontClass, $animationClass, $color) {

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
            'class'      => 'nextend-arrow n2-ow nextend-arrow-reveal nextend-arrow-' . $side . $animationClass,
            'role'       => 'button',
            'aria-label' => $label,
            'tabindex'   => '0'
        )), Html::tag('div', array(
                'class' => ' nextend-arrow-image n2-ow'
            ), $this->params->get($this->key . 'title-show') ? Html::tag('div', array(
                'class' => $fontClass . ' nextend-arrow-title n2-ow'
            ), '') : '') . Html::tag('div', array(
                'class' => 'nextend-arrow-arrow n2-ow',
                'style' => 'background-image: url(' . $image . ');'
            ), ''));
    }
}