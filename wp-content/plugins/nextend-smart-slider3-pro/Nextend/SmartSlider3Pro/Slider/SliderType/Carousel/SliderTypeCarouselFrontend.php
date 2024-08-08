<?php


namespace Nextend\SmartSlider3Pro\Slider\SliderType\Carousel;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Data\Data;
use Nextend\Framework\Sanitize;
use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Slider\SliderType\AbstractSliderTypeFrontend;

class SliderTypeCarouselFrontend extends AbstractSliderTypeFrontend {

    public function getDefaults() {
        return array(
            'single-switch'          => 0,
            'slide-width'            => 600,
            'slide-height'           => 400,
            'maximum-pane-width'     => 3000,
            'minimum-slide-gap'      => 10,
            'background-color'       => 'ffffff00',
            'background'             => '',
            'background-size'        => 'cover',
            'background-fixed'       => 0,
            'animation'              => 'horizontal',
            'animation-duration'     => 800,
            'animation-easing'       => 'easeOutQuad',
            'carousel'               => 1,
            'border-width'           => 0,
            'border-color'           => '3E3E3Eff',
            'border-radius'          => 0,
            'slide-background-color' => 'ffffff',
            'slide-border-radius'    => 0
        );
    }

    protected function getSliderClasses() {

        return parent::getSliderClasses() . ' n2-ss-slider-carousel-animation-' . $this->slider->params->get('animation', 'horizontal');
    }

    protected function renderType($css) {
        if ($this->slider->params->get('animation') === 'horizontal' && $this->slider->params->get('single-switch', 0)) {
            $this->renderTypeSingle($css);
        } else {
            $this->renderTypeMulti($css);
        }
    }

    protected function renderTypeMulti($css) {

        $params = $this->slider->params;

        Js::addStaticGroup(SliderTypeCarousel::getAssetsPath() . '/dist/ss-carousel.min.js', 'ss-carousel');

        $this->jsDependency[] = 'ss-carousel';

        $this->initSliderBackground('.n2-ss-slider-2');

        $this->initParticleJS();

        echo wp_kses($this->openSliderElement(), Sanitize::$basicTags);
        ob_start();
        ?>
        <div class="n2-ss-slider-1 n2_ss__touch_element n2-ow">
            <div class="n2-ss-slider-2 n2-ow">
                <?php
                echo wp_kses($this->getBackgroundVideo($params), Sanitize::$videoTags);
                ?>
                <div class="n2-ss-slider-3 n2-ow">
                    <?php
                    $this->displaySizeSVGs($css, true);

                    // PHPCS - Content already escaped
                    echo $this->slider->staticHtml; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    ?>
                    <div class="n2-ss-slider-pane n2-ow">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 <?php echo esc_attr($css->base['slideWidth'] . ' ' . $css->base['slideHeight']); ?>" class="n2-ow n2-ss-preserve-size n2-ss-slide-limiter"></svg>
                        <?php
                        foreach ($this->slider->getSlides() as $i => $slide) {
                            $slide->finalize();

                            // PHPCS - Content already escaped
                            echo Html::tag('div', Html::mergeAttributes($slide->attributes, $slide->linkAttributes, array( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                                                                                                           'class' => 'n2-ss-slide ' . $slide->classes . ' n2-ow',
                                                                                                                           'style' => $slide->style . $params->get('slide-css')
                            )), $slide->background . $slide->getHTML());
                        }
                        ?>
                    </div>
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


        $this->javaScriptProperties['mainanimation'] = array(
            'type'     => $params->get('animation'),
            'duration' => intval($params->get('animation-duration')),
            'ease'     => $params->get('animation-easing')
        );

        $this->slider->addDeviceCSS('all', 'div#' . $this->slider->elementId . ' .n2-ss-slider-3{max-width:' . intval($params->get('maximum-pane-width')) . 'px;}');


        $this->javaScriptProperties['carousel']                      = intval($params->get('carousel'));
        $this->javaScriptProperties['maxPaneWidth']                  = intval($params->get('maximum-pane-width'));
        $this->javaScriptProperties['responsive']['minimumSlideGap'] = intval($params->get('minimum-slide-gap'));

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

        $this->javaScriptProperties['responsive']['border'] = max(0, intval($params->get('border-width', 0)));

        $this->javaScriptProperties['parallax']['enabled'] = 0;

        $this->style .= $css->getCSS();
    }

    protected function renderTypeSingle($css) {

        $params = $this->slider->params;

        Js::addStaticGroup(SliderTypeCarousel::getAssetsPath() . '/dist/ss-carousel-single.min.js', 'ss-carousel-single');

        $this->jsDependency[] = 'ss-carousel-single';

        $sliderCSS = $params->get('slider-css');

        $this->initSliderBackground('.n2-ss-slider-2');

        $this->initParticleJS();

        echo wp_kses($this->openSliderElement(), Sanitize::$basicTags);
        ob_start();
        ?>
        <div class="n2-ss-slider-1 n2_ss__touch_element n2-ow">
            <div class="n2-ss-slider-2 n2-ow" style="<?php echo esc_attr($sliderCSS); ?>">
                <?php
                echo wp_kses($this->getBackgroundVideo($params), Sanitize::$videoTags);
                ?>
                <div class="n2-ss-slider-3 n2-ow">
                    <?php
                    $this->displaySizeSVGs($css, true);

                    // PHPCS - Content already escaped
                    echo $this->slider->staticHtml; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    ?>
                    <div class="n2-ss-slider-pane-single n2-ow">
                        <div class="n2-ss-slider-pipeline n2-ow" style="--slide-width:<?php echo esc_attr($css->base['slideWidth']); ?>px;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 <?php echo esc_attr($css->base['slideWidth'] . ' ' . $css->base['slideHeight']); ?>" class="n2-ow n2-ss-preserve-size n2-ss-slide-limiter"></svg>
                            <?php

                            foreach ($this->slider->getSlides() as $i => $slide) {
                                $slide->finalize();

                                // PHPCS - Content already escaped
                                echo Html::tag('div', Html::mergeAttributes($slide->attributes, $slide->linkAttributes, array( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                                                                                                               'class' => 'n2-ss-slide ' . $slide->classes . ' n2-ow',
                                                                                                                               'style' => $slide->style . $params->get('slide-css')
                                )), $slide->background . $slide->getHTML());
                            }
                            ?></div>
                    </div>
                </div>
                <?php
                $this->renderShapeDividers();
                ?>
            </div>
        </div>
        <?php
        echo $this->widgets->wrapSlider(ob_get_clean()); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo wp_kses($this->closeSliderElement(), Sanitize::$basicTags);

        $this->javaScriptProperties['mainanimation'] = array(
            'duration' => intval($params->get('animation-duration')),
            'ease'     => $params->get('animation-easing')
        );

        $this->slider->addDeviceCSS('all', 'div#' . $this->slider->elementId . ' .n2-ss-slider-3{max-width:' . intval($params->get('maximum-pane-width')) . 'px;}');


        $this->javaScriptProperties['carousel']                      = intval($params->get('carousel'));
        $this->javaScriptProperties['maxPaneWidth']                  = intval($params->get('maximum-pane-width'));
        $this->javaScriptProperties['responsive']['minimumSlideGap'] = intval($params->get('minimum-slide-gap'));
        $this->javaScriptProperties['responsive']['justifySlides']   = intval($params->get('slider-side-spacing', 1));

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

        $this->style .= $css->getCSS();
    }


    public function getScript() {
        if ($this->slider->params->get('animation') === 'horizontal' && $this->slider->params->get('single-switch', 0)) {
            return "_N2.r(" . json_encode(array_unique($this->jsDependency)) . ",function(){new _N2.SmartSliderCarouselSingle('{$this->slider->elementId}', " . $this->encodeJavaScriptProperties() . ");});";
        } else {
            return "_N2.r(" . json_encode(array_unique($this->jsDependency)) . ",function(){new _N2.SmartSliderCarousel('{$this->slider->elementId}', " . $this->encodeJavaScriptProperties() . ");});";
        }
    }

    /**
     * @param $params Data
     */
    public function limitParams($params) {
        $limitParams = array(
            'widget-bar-enabled'        => 0,
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