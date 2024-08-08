<?php


namespace Nextend\SmartSlider3Pro\Slider\SliderType\Showcase;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Data\Data;
use Nextend\Framework\Parser\Common;
use Nextend\Framework\Sanitize;
use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Slider\SliderType\AbstractSliderTypeFrontend;

class SliderTypeShowcaseFrontend extends AbstractSliderTypeFrontend {

    private $direction = 'horizontal';

    public function getDefaults() {
        return array(
            'slide-width'         => 600,
            'slide-height'        => 400,
            'background'          => '',
            'background-size'     => 'cover',
            'background-fixed'    => 0,
            'border-width'        => 0,
            'border-color'        => '3E3E3Eff',
            'border-radius'       => 0,
            'slider-css'          => '',
            'slide-css'           => '',
            'animation-duration'  => 800,
            'animation-easing'    => 'easeOutQuad',
            'animation-direction' => 'horizontal',
            'slide-distance'      => 60,
            'perspective'         => 1000,
            'carousel'            => 1,
            'carousel-slides'     => 3,
            'opacity'             => '0|*|100|*|100|*|100',
            'scale'               => '0|*|100|*|100|*|100',
            'translate-x'         => '0|*|0|*|0|*|0',
            'translate-y'         => '0|*|0|*|0|*|0',
            'translate-z'         => '0|*|0|*|0|*|0',
            'rotate-x'            => '0|*|0|*|0|*|0',
            'rotate-y'            => '0|*|0|*|0|*|0',
            'rotate-z'            => '0|*|0|*|0|*|0'
        );
    }

    protected function renderType($css) {

        $params = $this->slider->params;

        Js::addStaticGroup(SliderTypeShowcase::getAssetsPath() . '/dist/ss-showcase.min.js', 'ss-showcase');

        $this->jsDependency[] = 'ss-showcase';

        $sliderCSS = $params->get('slider-css');

        $this->initSliderBackground('.n2-ss-slider-2');

        $this->initParticleJS();

        echo wp_kses($this->openSliderElement(), Sanitize::$basicTags);
        ob_start();

        $overlay = $params->get('slide-overlay', 1);
        ?>
        <div class="n2-ss-slider-1 n2_ss__touch_element n2-ow">
            <div class="n2-ss-slider-2 n2-ow"<?php echo empty($sliderCSS) ? '' : ' style="' . esc_attr($sliderCSS) . '"'; ?>>
                <?php
                echo wp_kses($this->getBackgroundVideo($params), Sanitize::$videoTags);
                ?>
                <div class="n2-ss-slider-3 n2-ow">
                    <?php
                    $this->displaySizeSVGs($css, true);

                    // PHPCS - Content already escaped
                    echo $this->slider->staticHtml; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    ?>
                    <div class="n2-ss-showcase-slides n2-ow">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 <?php echo esc_attr($css->base['slideWidth'] . ' ' . $css->base['slideHeight']); ?>" class="n2-ow n2-ss-preserve-size n2-ss-slide-limiter"></svg>
                        <?php
                        foreach ($this->slider->getSlides() as $i => $slide) {
                            $slide->finalize();


                            // PHPCS - Content already escaped
                            echo Html::tag('div', Html::mergeAttributes($slide->attributes, array( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                                                                                   'class' => 'n2-ss-slide ' . $slide->classes . ' n2-ow',
                                                                                                   'style' => $slide->style . $params->get('slide-css')
                            )), $slide->background . Html::tag('div', array('class' => 'n2-ss-slide-inner') + $slide->linkAttributes, $slide->getHTML()) . ($overlay ? Html::tag('div', array('class' => 'n2-ss-showcase-overlay n2-ow')) : ''));
                        }
                        ?></div>
                </div>
                <?php
                $this->renderShapeDividers();
                ?>
            </div>
        </div>
        <?php

        // PHPCS - Content already escaped
        echo $this->widgets->wrapSlider(ob_get_clean()); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo wp_kses($this->closeSliderElement(), Sanitize::$basicTags);

        $this->javaScriptProperties['carousel']           = intval($params->get('carousel'));
        $this->javaScriptProperties['carouselSideSlides'] = intval((max(intval($params->get('carousel-slides')), 1) - 1) / 2);

        $this->javaScriptProperties['showcase'] = array(
            'duration' => intval($params->get('animation-duration')),
            'ease'     => $params->get('animation-easing')
        );


        $sideSpacing = array();

        if ($params->get('side-spacing-desktop-enable', 0)) {
            $sideSpacing['desktop'] = array_pad(array_map('intval', explode('|*|', $params->get('side-spacing-desktop'))), 4, 0);
        } else {
            $sideSpacing['desktop'] = array(
                0,
                0,
                0,
                0
            );
        }

        if ($params->get('side-spacing-tablet-enable', 0)) {
            $sideSpacing['tablet'] = array_pad(array_map('intval', explode('|*|', $params->get('side-spacing-tablet'))), 4, 0);
        } else {
            $sideSpacing['tablet'] = $sideSpacing['desktop'];
        }

        if ($params->get('side-spacing-mobile-enable', 0)) {
            $sideSpacing['mobile'] = array_pad(array_map('intval', explode('|*|', $params->get('side-spacing-mobile'))), 4, 0);
        } else {
            $sideSpacing['mobile'] = $sideSpacing['tablet'];
        }

        $desktop = implode('px ', $sideSpacing['desktop']) . 'px';
        $this->slider->addDeviceCSS('all', 'div#' . $this->slider->elementId . ' .n2-ss-slider-3{padding:' . $desktop . '}');

        $tablet = implode('px ', $sideSpacing['tablet']) . 'px';
        if ($tablet !== $desktop) {
            $this->slider->addDeviceCSS('tabletportrait', 'div#' . $this->slider->elementId . ' .n2-ss-slider-3{padding:' . $tablet . '}');
            $this->slider->addDeviceCSS('tabletlandscape', 'div#' . $this->slider->elementId . ' .n2-ss-slider-3{padding:' . $tablet . '}');

        }
        $mobile = implode('px ', $sideSpacing['mobile']) . 'px';
        if ($mobile !== $desktop) {
            $this->slider->addDeviceCSS('mobileportrait', 'div#' . $this->slider->elementId . ' .n2-ss-slider-3{padding:' . $mobile . '}');
            $this->slider->addDeviceCSS('mobilelandscape', 'div#' . $this->slider->elementId . ' .n2-ss-slider-3{padding:' . $mobile . '}');

        }

        $this->initAnimationProperties();

        $this->style .= $css->getCSS();
    }

    public function getScript() {
        return "_N2.r(" . json_encode(array_unique($this->jsDependency)) . ",function(){new _N2.SmartSliderShowcase('{$this->slider->elementId}', " . $this->encodeJavaScriptProperties() . ");});";
    }

    protected function getSliderClasses() {
        switch ($this->slider->params->get('animation-direction', 'horizontal')) {
            case 'vertical':
                $this->direction = 'vertical';

                return parent::getSliderClasses() . ' n2-ss-showcase-vertical';
                break;
            default:
                $this->direction = 'horizontal';

                return parent::getSliderClasses() . ' n2-ss-showcase-horizontal';
        }
    }

    private function initAnimationProperties() {
        $params = $this->slider->params;

        $slideDistance = intval($params->get('slide-distance'));

        $this->javaScriptProperties['showcase'] += array(
            'direction' => $this->direction,
            'distance'  => $slideDistance,
            'animate'   => array(
                'opacity'   => self::animationPropertyState($params, 'opacity', 100),
                'scale'     => self::animationPropertyState($params, 'scale', 100),
                'x'         => self::animationPropertyState($params, 'translate-x'),
                'y'         => self::animationPropertyState($params, 'translate-y'),
                'z'         => self::animationPropertyState($params, 'translate-z'),
                'rotationX' => self::animationPropertyState($params, 'rotate-x'),
                'rotationY' => self::animationPropertyState($params, 'rotate-y'),
                'rotationZ' => self::animationPropertyState($params, 'rotate-z'),
            ),
            'overlay'   => $params->get('slide-overlay', 1)
        );
    }

    private static function animationPropertyState($params, $prop, $normalize = 1) {
        $propValue = Common::parse($params->get($prop));
        if ($propValue[0] != 1) {
            return null;
        }

        return array(
            'before' => intval($propValue[1]) / $normalize,
            'active' => intval($propValue[2]) / $normalize,
            'after'  => intval($propValue[3]) / $normalize
        );
    }

    /**
     * @param $params Data
     */
    public function limitParams($params) {
        $limitParams = array(
            'widget-fullscreen-enabled' => 0,
            'responsiveLimitSlideWidth' => 0,
            'imageloadNeighborSlides'   => 0,
            'slider-size-override'      => 0
        );

        if ($params->get('responsive-mode') === 'fullpage') {
            $limitParams['responsive-mode'] = 'auto';
        }

        $params->loadArray($limitParams);
    }
}