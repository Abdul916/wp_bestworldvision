<?php

namespace Nextend\SmartSlider3\Slider\SliderType\Simple;

use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Form\Container\ContainerRowGroup;
use Nextend\Framework\Form\Container\ContainerTable;
use Nextend\Framework\Form\Element\Grouping;
use Nextend\Framework\Form\Element\Hidden;
use Nextend\Framework\Form\Element\MarginPadding;
use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Element\Radio;
use Nextend\Framework\Form\Element\Select;
use Nextend\Framework\Form\Element\Select\Easing;
use Nextend\Framework\Form\Element\Select\Skin;
use Nextend\Framework\Form\Element\Text\Color;
use Nextend\Framework\Form\Element\Text\Number;
use Nextend\Framework\Form\Element\Text\NumberAutoComplete;
use Nextend\Framework\Form\Element\Textarea;
use Nextend\Framework\Form\Fieldset\FieldsetRow;
use Nextend\Framework\Form\Fieldset\LayerWindow\FieldsetLayerWindow;
use Nextend\Framework\Form\Insert\InsertAfter;
use Nextend\Framework\Form\Insert\InsertBefore;
use Nextend\SmartSlider3\BackgroundAnimation\BackgroundAnimationManager;
use Nextend\SmartSlider3\Form\Element\BackgroundAnimation;
use Nextend\SmartSlider3\Slider\SliderType\AbstractSliderTypeAdmin;
use Nextend\SmartSlider3Pro\Form\Element\PostBackgroundAnimation;
use Nextend\SmartSlider3Pro\PostBackgroundAnimation\PostBackgroundAnimationManager;

class SliderTypeSimpleAdmin extends AbstractSliderTypeAdmin {

    protected $ordering = 1;

    public function getLabel() {
        return n2_('Simple');
    }

    public function getLabelFull() {
        return n2_x('Simple slider', 'Slider type');
    }

    public function getIcon() {
        return 'ssi_64 ssi_64--slider';
    }

    public function prepareForm($form) {

        $tableMainAnimation = new ContainerTable(new InsertBefore($form->getElement('/animations/effects')), 'slider-type-simple-main-animation', n2_('Main animation'));

        $rowMainAnimation = new FieldsetRow($tableMainAnimation, 'slider-type-simple-main-animation-1');

        new Select($rowMainAnimation, 'animation', n2_('Main animation'), 'horizontal', array(
            'options'            => array(
                'no'                  => n2_('No animation'),
                'fade'                => n2_('Fade'),
                'crossfade'           => n2_('Crossfade'),
                'horizontal'          => n2_('Horizontal'),
                'vertical'            => n2_('Vertical'),
                'horizontal-reversed' => n2_('Horizontal - reversed'),
                'vertical-reversed'   => n2_('Vertical - reversed')
            ),
            'relatedValueFields' => array(
                array(
                    'values' => array(
                        'fade',
                        'crossfade',
                        'horizontal',
                        'vertical',
                        'horizontal-reversed',
                        'vertical-reversed'
                    ),
                    'field'  => array(
                        'slideranimation-duration',
                        'slideranimation-delay',
                        'slideranimation-easing'
                    )
                )
            )
        ));


        new NumberAutoComplete($rowMainAnimation, 'animation-duration', n2_('Duration'), 800, array(
            'min'    => 0,
            'values' => array(
                800,
                1500,
                2000
            ),
            'unit'   => 'ms',
            'wide'   => 5
        ));
        new Number($rowMainAnimation, 'animation-delay', n2_('Delay'), 0, array(
            'min'  => 0,
            'unit' => 'ms',
            'wide' => 5
        ));
        new Easing($rowMainAnimation, 'animation-easing', n2_('Easing'), 'easeOutQuad');

        new OnOff($rowMainAnimation, 'carousel', n2_x('Carousel', 'Feature'), 1, array(
            'tipLabel'         => n2_x('Carousel', 'Feature'),
            'tipDescription'   => n2_('If you turn off this option, you can\'t switch to the first slide from the last one.'),
            'tipLink'          => 'https://smartslider.helpscoutdocs.com/article/1780-simple-slider-type#carousel',
            'relatedFieldsOn'  => array(
                'slidercontrolsBlockCarouselInteraction'
            ),
            'relatedFieldsOff' => array(
                'sliderdisabled-carousel-notice'
            )
        ));
    

        $tableBackground = new ContainerTable(new InsertBefore($form->getElement('/animations/effects')), 'slider-type-simple-background', n2_('Background animation'));

        $rowBackgroundAnimation = new FieldsetRow($tableBackground, 'slider-type-simple-background-animation');

        new BackgroundAnimation($rowBackgroundAnimation, 'background-animation', n2_('Background animation'), '', array(
            'relatedFields'  => array(
                'sliderbackground-animation-speed',
                'slideranimation-shifted-background-animation'
            ),
            'tipLabel'       => n2_('Background animation'),
            'tipDescription' => n2_('Background animations only work on the slide background images, which have Fill selected at their Fill mode. They don\'t affect any images if the background parallax is enabled.'),
            'tipLink'        => 'https://smartslider.helpscoutdocs.com/article/1780-simple-slider-type#background-animation',
        ));
        new Hidden($rowBackgroundAnimation, 'background-animation-color', '333333ff');

        new Select($rowBackgroundAnimation, 'background-animation-speed', n2_('Speed'), 'normal', array(
            'options' => array(
                'superSlow10' => n2_('Super slow') . ' 10x',
                'superSlow'   => n2_('Super slow') . ' 3x',
                'slow'        => n2_('Slow') . ' 1.5x',
                'normal'      => n2_('Normal') . ' 1x',
                'fast'        => n2_('Fast') . ' 0.75x',
                'superFast'   => n2_('Super fast') . ' 0.5x'
            )
        ));
        new Radio($rowBackgroundAnimation, 'animation-shifted-background-animation', n2_('Shifted'), 'auto', array(
            'tipLabel'       => n2_('Shifted'),
            'tipDescription' => n2_('The background and the main animation plays simultaneously or shifted.'),
            'tipLink'        => 'https://smartslider.helpscoutdocs.com/article/1780-simple-slider-type#shifted',
            'options'        => array(
                'auto' => n2_('Auto'),
                '0'    => n2_('Off'),
                '1'    => n2_('On')
            )
        ));
    
        $rowKenBurns = new FieldsetRow($tableBackground, 'slider-type-simple-kenburns-animation');

        new PostBackgroundAnimation($rowKenBurns, 'kenburns-animation', n2_('Ken Burns effect'), '50|*|50|*|', array(
            'relatedFields' => array(
                'sliderkenburns-animation-speed',
                'sliderkenburns-animation-strength'
            )
        ));

        new Select($rowKenBurns, 'kenburns-animation-speed', n2_('Speed'), 'default', array(
            'options' => array(
                'default'   => n2_('Default'),
                'superSlow' => n2_('Super slow') . ' 0.25x',
                'slow'      => n2_('Slow') . ' 0.5x',
                'normal'    => n2_('Normal') . ' 1x',
                'fast'      => n2_('Fast') . ' 2x',
                'superFast' => n2_('Super fast') . ' 4x'
            )
        ));

        new Select($rowKenBurns, 'kenburns-animation-strength', n2_('Strength'), 'default', array(
            'options' => array(
                'default'     => n2_('Default'),
                'superSoft'   => n2_('Super soft') . ' 0.3x',
                'soft'        => n2_('Soft') . ' 0.6x',
                'normal'      => n2_('Normal') . ' 1x',
                'strong'      => n2_('Strong') . ' 1.5x',
                'superStrong' => n2_('Super strong') . ' 2x'
            )
        ));

    

        $margin = new MarginPadding(new InsertAfter($form->getElement('/general/design/design-1/margin')), 'padding', n2_('Padding'), '0|*|0|*|0|*|0', array(
            'unit' => 'px'
        ));

        for ($i = 1; $i < 5; $i++) {
            new Number($margin, 'padding-' . $i, false, '', array(
                'wide' => 3
            ));
        }

        $rowGroup = new ContainerRowGroup(new InsertAfter($form->getElement('/general/design/design-1')), 'slider-type-simple-group-general', false);

        $rowStyle = new FieldsetRow($rowGroup, 'slider-type-simple-settings-style');

        new Number($rowStyle, 'border-width', n2_('Border width'), 0, array(
            'unit'          => 'px',
            'wide'          => 3,
            'relatedFields' => array('sliderborder-color')
        ));
        new Color($rowStyle, 'border-color', n2_('Border color'), '3E3E3Eff', array(
            'alpha' => true
        ));
        new Number($rowStyle, 'border-radius', n2_('Border radius'), 0, array(
            'unit' => 'px',
            'wide' => 3
        ));

        $rowSliderCSS = new FieldsetRow($rowGroup, 'slider-type-simple-settings-slidercss');

        new Skin($rowSliderCSS, 'slider-preset', n2_('Slider CSS Preset'), '', array(
            'post'    => 'break',
            'options' => array(
                'shadow'       => array(
                    'label'    => n2_('Light shadow'),
                    'settings' => array(
                        'slider-css' => 'box-shadow: 1px 0 5px RGBA(0, 0, 0, 0.2), -1px 0 5px RGBA(0, 0, 0, 0.2);'
                    )
                ),
                'shadow2'      => array(
                    'label'    => n2_('Dark shadow'),
                    'settings' => array(
                        'slider-css' => 'box-shadow: 0 2px 4px 1px rgba(0, 0, 0, 0.6);'
                    )
                ),
                'photo'        => array(
                    'label'    => n2_('Photo'),
                    'settings' => array(
                        'slider-css'   => 'box-shadow: 1px 0 5px RGBA(0, 0, 0, 0.2), -1px 0 5px RGBA(0, 0, 0, 0.2);',
                        'border-width' => '8',
                        'border-color' => 'FFFFFFFF'
                    )
                ),
                'roundedphoto' => array(
                    'label'    => n2_('Photo rounded'),
                    'settings' => array(
                        'slider-css'    => 'box-shadow: 1px 0 5px RGBA(0, 0, 0, 0.2), -1px 0 5px RGBA(0, 0, 0, 0.2);',
                        'border-width'  => '5',
                        'border-color'  => 'FFFFFFFF',
                        'border-radius' => '12'
                    )
                )
            )
        ));

        new Textarea($rowSliderCSS, 'slider-css', n2_('Slider') . ' CSS', '', array(
            'height' => 26
        ));

        $rowSlideCSS = new FieldsetRow(new InsertAfter($form->getElement('/slides/slides-design/slides-design-1')), 'slider-type-simple-settings-slidecss');

        new Textarea($rowSlideCSS, 'slide-css', n2_('Slide') . ' CSS', '', array(
            'height' => 26
        ));
    }

    public function renderSlideFields($container) {

        $dataToFields = array();

        $tableAnimation = new FieldsetLayerWindow($container, 'fields-slide-animation', n2_('Animation'));

        // Background animations are required for simple type. We need to load the lightbox, because it is not working over AJAX slider type change.
        BackgroundAnimationManager::enqueue($container->getForm());

        $rowBackgroundAnimation = new Grouping($tableAnimation, 'slide-settings-animation-background-animation');

        new BackgroundAnimation($rowBackgroundAnimation, 'slide-background-animation', n2_('Background animation'), '', array(
            'relatedFields' => array(
                'layerslide-background-animation-speed'
            )
        ));
        $dataToFields[] = [
            'name' => 'background-animation',
            'id'   => 'layerslide-background-animation',
            'def'  => ''
        ];

        new Hidden($rowBackgroundAnimation, 'slide-background-animation-color', '');
        $dataToFields[] = [
            'name' => 'background-animation-color',
            'id'   => 'layerslide-background-animation-color',
            'def'  => '333333ff'
        ];

        new Select($rowBackgroundAnimation, 'slide-background-animation-speed', n2_('Speed'), '', array(
            'options' => array(
                'default'     => n2_('Default'),
                'superSlow10' => n2_('Super slow') . ' 10x',
                'superSlow'   => n2_('Super slow') . ' 3x',
                'slow'        => n2_('Slow') . ' 1.5x',
                'normal'      => n2_('Normal') . ' 1x',
                'fast'        => n2_('Fast') . ' 0.75x',
                'superFast'   => n2_('Super fast') . ' 0.5x'
            )
        ));
        $dataToFields[] = [
            'name' => 'background-animation-speed',
            'id'   => 'layerslide-background-animation-speed',
            'def'  => 'default'
        ];

        PostBackgroundAnimationManager::enqueue($container->getForm());

        $rowKenBurns = new Grouping($tableAnimation, 'slide-settings-animation-ken-burns');

        new PostBackgroundAnimation($rowKenBurns, 'slide-kenburns-animation', n2_('Ken Burns effect'), '', array(
            'relatedFields' => array(
                'layerslide-kenburns-animation-speed',
                'layerslide-kenburns-animation-strength'
            )
        ));
        $dataToFields[] = [
            'name' => 'kenburns-animation',
            'id'   => 'layerslide-kenburns-animation',
            'def'  => '50|*|50|*'
        ];

        new Select($rowKenBurns, 'slide-kenburns-animation-speed', n2_('Speed'), '', array(
            'options' => array(
                'default'   => n2_('Default'),
                'superSlow' => n2_('Super slow') . ' 0.25x',
                'slow'      => n2_('Slow') . ' 0.5x',
                'normal'    => n2_('Normal') . ' 1x',
                'fast'      => n2_('Fast') . ' 2x',
                'superFast' => n2_('Super fast' . ' 4x')
            )
        ));
        $dataToFields[] = [
            'name' => 'kenburns-animation-speed',
            'id'   => 'layerslide-kenburns-animation-speed',
            'def'  => 'default'
        ];

        new Select($rowKenBurns, 'slide-kenburns-animation-strength', n2_('Strength'), '', array(
            'options' => array(
                'default'     => n2_('Default'),
                'superSoft'   => n2_('Super soft') . ' 0.3x',
                'soft'        => n2_('Soft') . ' 0.6x',
                'normal'      => n2_('Normal') . ' 1x',
                'strong'      => n2_('Strong') . ' 1.5x',
                'superStrong' => n2_('Super strong') . ' 2x'
            )
        ));
        $dataToFields[] = [
            'name' => 'kenburns-animation-strength',
            'id'   => 'layerslide-kenburns-animation-strength',
            'def'  => 'default'
        ];
    

        Js::addInline("_N2.r('SectionSlide', function(){ _N2.SectionSlide.addExternalDataToField(" . json_encode($dataToFields) . ");});");
    }

    public function registerSlideAdminProperties($component) {

        $component->createProperty('background-animation', '');
        $component->createProperty('background-animation-color', '333333ff');
        $component->createProperty('background-animation-speed', 'default');
        $component->createProperty('kenburns-animation', '50|*|50|*|');
        $component->createProperty('kenburns-animation-speed', 'default');
        $component->createProperty('kenburns-animation-strength', 'default');
    
    }
}