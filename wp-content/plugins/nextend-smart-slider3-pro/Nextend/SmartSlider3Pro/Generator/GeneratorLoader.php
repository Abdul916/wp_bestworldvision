<?php


namespace Nextend\SmartSlider3Pro\Generator;


use Nextend\Framework\Plugin;
use Nextend\SmartSlider3Pro\Generator;

class GeneratorLoader {

    public function __construct() {

        Plugin::addAction('PluggableFactorySliderGenerator', array(
            $this,
            'sliderGenerator'
        ));
        add_action('rest_api_init', array(
            $this,
            'sliderGeneratorRESTLoader'
        ), 1);
    
    }

    public function sliderGenerator() {
        new Generator\Common\GeneratorCommonLoader();
        new Generator\WordPress\GeneratorWordPressLoader();
    }

    public function sliderGeneratorRESTLoader() {
        new Generator\Common\GeneratorCommonRESTLoader();
    
    }
}