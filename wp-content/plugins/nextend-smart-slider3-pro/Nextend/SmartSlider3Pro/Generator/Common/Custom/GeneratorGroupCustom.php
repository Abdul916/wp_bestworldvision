<?php

namespace Nextend\SmartSlider3Pro\Generator\Common\Custom;

use Nextend\SmartSlider3\Generator\AbstractGeneratorGroup;
use Nextend\SmartSlider3Pro\Generator\Common\Custom\Sources\CustomCustom;

class GeneratorGroupCustom extends AbstractGeneratorGroup {

    protected $name = 'custom', $error = '';

    public function getLabel() {
        return 'Custom';
    }

    public function getDescription() {
        return n2_('Creates slides by your custom settings.');
    }

    public function getDocsLink() {
        return 'https://smartslider.helpscoutdocs.com/article/1957-creating-a-custom-generator';
    }

    public function getError() {
        return $this->error;
    }

    protected function loadSources() {
        $customGenerators = array();
        $customGenerators = apply_filters('smartslider3_custom_generator', $customGenerators);

        foreach ($customGenerators as $customGenerator) {
            new CustomCustom($this, $customGenerator);
        }

        if (empty($customGenerators)) {
            $this->error = sprintf(n2_('You don\'t have custom generators yet. %1$s Check the documentation %2$s to learn how to create your own generator.'), '<a href="https://smartslider.helpscoutdocs.com/article/1957-creating-a-custom-generator" target="_blank">', '</a>');
        }
    }
}
