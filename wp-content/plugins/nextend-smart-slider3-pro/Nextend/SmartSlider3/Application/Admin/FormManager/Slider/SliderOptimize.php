<?php


namespace Nextend\SmartSlider3\Application\Admin\FormManager\Slider;


use Nextend\Framework\Form\Container\ContainerTable;
use Nextend\Framework\Form\Element\Grouping;
use Nextend\Framework\Form\Element\Message\Notice;
use Nextend\Framework\Form\Element\Message\Warning;
use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Element\Select;
use Nextend\Framework\Form\Element\Text\Number;
use Nextend\Framework\Form\FormTabbed;

class SliderOptimize extends AbstractSliderTab {

    /**
     * SliderOptimize constructor.
     *
     * @param FormTabbed $form
     */
    public function __construct($form) {
        parent::__construct($form);

        $this->loading();

        $this->optimizeSlide();
        $this->optimizeLayer();
    
        $this->optimizeSliderBackgroundImage();
    

        $this->other();
    }

    /**
     * @return string
     */
    protected function getName() {
        return 'optimize';
    }

    /**
     * @return string
     */
    protected function getLabel() {
        return n2_('Optimize');
    }

    protected function loading() {

        $table = new ContainerTable($this->tab, 'loading', n2_('Loading'));

        $row1 = $table->createRow('loading-1');

        new Select($row1, 'loading-type', n2_('Loading type'), '', array(
            'options'            => array(
                ''            => n2_('Instant'),
                'afterOnLoad' => n2_('After page loaded'),
                'afterDelay'  => n2_('After delay')
            ),
            'relatedValueFields' => array(
                array(
                    'values' => array(
                        'afterDelay'
                    ),
                    'field'  => array(
                        'sliderdelay'
                    )
                )
            ),
            'tipLabel'           => n2_('Loading type'),
            'tipDescription'     => n2_('If your slider is above the fold, you can load it immediately. Otherwise, you can load it only after the page has loaded.'),
            'tipLink'            => 'https://smartslider.helpscoutdocs.com/article/1801-slider-settings-optimize#loading-type'
        ));

        new Number($row1, 'delay', n2_('Load delay'), 0, array(
            'wide' => 5,
            'unit' => 'ms'
        ));

        new OnOff($row1, 'playWhenVisible', n2_('Play when visible'), 1, array(
            'relatedFieldsOn' => array(
                'sliderplayWhenVisibleAt'
            ),
            'tipLabel'        => n2_('Play when visible'),
            'tipDescription'  => n2_('Makes sure that the autoplay and layer animations only start when your slider is visible.')
        ));
        new Number($row1, 'playWhenVisibleAt', n2_('At'), 50, array(
            'unit' => '%',
            'wide' => 3
        ));
    }

    protected function optimizeSlide() {

        $table = new ContainerTable($this->tab, 'optimize-slide', n2_('Slide background images'));

        /**
         * Used for field injection: /optimize/optimize-slide/optimize-slide-loading-mode
         */
        $row1 = $table->createRow('optimize-slide-loading-mode');

        new Select($row1, 'imageload', n2_('Loading mode'), '0', array(
            'options'            => array(
                '0' => n2_('Normal'),
                '2' => n2_('Delayed loading'),
                '1' => n2_('Lazy loading')
            ),
            'relatedValueFields' => array(
                array(
                    'values' => array(
                        '1'
                    ),
                    'field'  => array(
                        'sliderimageloadNeighborSlides'
                    )
                )
            ),
            'tipLabel'           => n2_('Loading mode'),
            'tipDescription'     => n2_('You can speed up your site\'s loading by delaying the slide background images.'),
            'tipLink'            => 'https://smartslider.helpscoutdocs.com/article/1801-slider-settings-optimize#lazy-load'
        ));

        /**
         * Used for field injection: /optimize/optimize-slide/optimize-slide-loading-mode/imageloadNeighborSlides
         */
        new Number($row1, 'imageloadNeighborSlides', n2_('Load neighbor'), 0, array(
            'unit' => n2_x('slides', 'Unit'),
            'wide' => 3
        ));
    

        $row2 = $table->createRow('optimize-slide-2');

        $memoryLimitText = '';
        if (function_exists('ini_get')) {
            $memory_limit = ini_get('memory_limit');
            if (!empty($memory_limit)) {
                $memoryLimitText = ' (' . $memory_limit . ')';
            }
        }

        new Warning($row2, 'optimize-notice', sprintf(n2_('Convert to WebP and image resizing require a lot of memory. Lift the memory limit%s if you get a blank page.'), $memoryLimitText));

        $row3 = $table->createRow('optimize-slide-3');

        new OnOff($row3, 'optimize-webp', n2_('Convert to WebP'), '0', array(
            'relatedFieldsOn' => array(
                'slideroptimize-slide-webp',
                'slideroptimize-quality',
                'slideroptimize-slide-webp-2'
            )
        ));
    

        $optimizeWebp = new Grouping($row3, 'optimize-slide-webp');

        new Number($optimizeWebp, 'optimize-quality', n2_('Quality'), 70, array(
            'min'  => 0,
            'max'  => 100,
            'unit' => '%',
            'wide' => 3,
            'post' => 'break'
        ));
        new OnOff($optimizeWebp, 'optimize-scale', n2_('Resize'), '0', array(
            'relatedFieldsOn' => array(
                'slideroptimize-slide-width-normal',
                'slideroptimize-slide-width-tablet',
                'slideroptimize-slide-height-tablet',
                'slideroptimize-slide-width-mobile',
                'slideroptimize-slide-height-mobile',
                'slideroptimize-slide-width-retina',
                'slideroptimize-slide-scale-notice'
            )
        ));
    

        new Number($optimizeWebp, 'optimize-slide-width-normal', n2_('Default width'), 1920, array(
            'min'  => 0,
            'unit' => 'px',
            'wide' => 4
        ));
        new Number($optimizeWebp, 'optimize-slide-width-tablet', n2_('Medium width'), 1200, array(
            'min'  => 0,
            'unit' => 'px',
            'wide' => 4
        ));
        new Number($optimizeWebp, 'optimize-slide-height-tablet', n2_('Medium height'), 0, array(
            'min'  => 0,
            'unit' => 'px',
            'wide' => 4
        ));
        new Number($optimizeWebp, 'optimize-slide-width-mobile', n2_('Small width'), 500, array(
            'min'  => 0,
            'unit' => 'px',
            'wide' => 4
        ));
        new Number($optimizeWebp, 'optimize-slide-height-mobile', n2_('Small height'), 0, array(
            'min'  => 0,
            'unit' => 'px',
            'wide' => 4
        ));
        new OnOff($optimizeWebp, 'optimize-slide-width-retina', n2_('Retina'), 0);

        $resizeWarning = new Grouping($row3, 'optimize-slide-webp-2');
        new Notice($resizeWarning, 'optimize-slide-scale-notice', n2_('Instruction'), n2_('If your images look blurry on small screens, use the available height option to match the aspect ratio of the slider and image on that device.'));

    

        $row4 = $table->createRow('optimize-slide-4');

        new OnOff($row4, 'optimize-thumbnail-scale', n2_('Resize Thumbnail'), '0', array(
            'relatedFieldsOn' => array(
                'slideroptimize-thumbnail-quality',
                'slideroptimizeThumbnailWidth',
                'slideroptimizeThumbnailHeight'
            )
        ));

        new Number($row4, 'optimize-thumbnail-quality', n2_('Thumbnail Quality'), 70, array(
            'min'  => 0,
            'max'  => 100,
            'unit' => '%',
            'wide' => 3,
            'post' => 'break'
        ));

        new Number($row4, 'optimizeThumbnailWidth', n2_('Thumbnail width'), 100, array(
            'min'  => 0,
            'unit' => 'px',
            'wide' => 4
        ));
        new Number($row4, 'optimizeThumbnailHeight', n2_('Thumbnail height'), 60, array(
            'min'  => 0,
            'unit' => 'px',
            'wide' => 4
        ));

    }

    protected function optimizeLayer() {
        $table = new ContainerTable($this->tab, 'optimize-layer', n2_('Layer images'));

        $row1 = $table->createRow('optimize-layer-1');
        new OnOff($row1, 'layer-image-webp', n2_('Convert to WebP'), '0', array(
            'relatedFieldsOn' => array(
                'sliderlayer-image-optimize-webp'
            )
        ));

        $optimizeWebp = new Grouping($row1, 'layer-image-optimize-webp');

        new Number($optimizeWebp, 'layer-image-optimize-quality', n2_('Quality'), 70, array(
            'min'  => 0,
            'max'  => 100,
            'unit' => '%',
            'wide' => 3,
            'post' => 'break'
        ));

        new OnOff($optimizeWebp, 'layer-image-optimize', n2_('Resize'), '0', array(
            'relatedFieldsOn' => array(
                'sliderlayer-image-width-normal',
                'sliderlayer-image-width-tablet',
                'sliderlayer-image-width-mobile',
                'sliderlayer-image-width-retina'
            )
        ));

        new Number($optimizeWebp, 'layer-image-width-normal', n2_('Default width'), 1400, array(
            'unit' => 'px',
            'wide' => 4
        ));
        new Number($optimizeWebp, 'layer-image-width-tablet', n2_('Medium width'), 800, array(
            'unit' => 'px',
            'wide' => 4
        ));
        new Number($optimizeWebp, 'layer-image-width-mobile', n2_('Small width'), 425, array(
            'unit' => 'px',
            'wide' => 4
        ));
        new OnOff($optimizeWebp, 'layer-image-width-retina', n2_('Retina'), 0);

        $row2 = $table->createRow('optimize-layer-2');
        new OnOff($row2, 'layer-image-base64', n2_('Base64 embed'), '0', array(
            'relatedFieldsOn' => array(
                'sliderlayer-image-base64-size'
            ),
            'tipLabel'        => n2_('Base64 embed'),
            'tipDescription'  => n2_('Embeds the layer images to the page source, reducing the requests.')
        ));
        new Number($row2, 'layer-image-base64-size', n2_('Max file size'), 50, array(
            'min'  => 0,
            'unit' => 'kb',
            'wide' => 5
        ));
    
    }

    protected function optimizeSliderBackgroundImage() {
        $table = new ContainerTable($this->tab, 'optimize-slider', n2_('Slider background image'));

        $row1 = $table->createRow('optimize-slider-1');


        new OnOff($row1, 'optimize-slider-webp', n2_('Convert to WebP'), '0', array(
            'relatedFieldsOn' => array(
                'slideroptimize-slider-webp-group',
                'slideroptimize-slider-quality'
            )
        ));

        $optimizeWebp = new Grouping($row1, 'optimize-slider-webp-group');

        new Number($optimizeWebp, 'optimize-slider-quality', n2_('Quality'), 70, array(
            'min'  => 0,
            'max'  => 100,
            'unit' => '%',
            'wide' => 3,
            'post' => 'break'
        ));


        new OnOff($optimizeWebp, 'optimize-slider-scale', n2_('Resize'), '0', array(
            'relatedFieldsOn' => array(
                'slideroptimize-slider-width-normal',
                'slideroptimize-slider-width-tablet',
                'slideroptimize-slider-height-tablet',
                'slideroptimize-slider-width-mobile',
                'slideroptimize-slider-height-mobile',
            )
        ));

        new Number($optimizeWebp, 'optimize-slider-width-normal', n2_('Default width'), 1920, array(
            'min'  => 0,
            'unit' => 'px',
            'wide' => 4
        ));

        new Number($optimizeWebp, 'optimize-slider-width-tablet', n2_('Medium width'), 1200, array(
            'min'  => 0,
            'unit' => 'px',
            'wide' => 4
        ));
        new Number($optimizeWebp, 'optimize-slider-height-tablet', n2_('Medium height'), 0, array(
            'min'  => 0,
            'unit' => 'px',
            'wide' => 4
        ));
        new Number($optimizeWebp, 'optimize-slider-width-mobile', n2_('Small width'), 500, array(
            'min'  => 0,
            'unit' => 'px',
            'wide' => 4
        ));
        new Number($optimizeWebp, 'optimize-slider-height-mobile', n2_('Small height'), 0, array(
            'min'  => 0,
            'unit' => 'px',
            'wide' => 4
        ));
    
    }

    protected function other() {
        $table = new ContainerTable($this->tab, 'optimize-other', n2_('Other'));

        $row1 = $table->createRow('optimize-other-1');

        new OnOff($row1, 'slides-background-video-mobile', n2_('Background video on mobile'), 1);
    
    }
}