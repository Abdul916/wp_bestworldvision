<?php

namespace Nextend\SmartSlider3Pro\Generator\Common\Instagram;

use Nextend\SmartSlider3\Generator\AbstractGeneratorGroup;
use Nextend\SmartSlider3Pro\Generator\Common\Instagram\Sources\InstagramImages;


class GeneratorGroupInstagram extends AbstractGeneratorGroup {

    protected $name = 'instagram';

    protected $needConfiguration = true;

    public function __construct() {

        parent::__construct();
        $this->configuration = new ConfigurationInstagram($this);
    }

    public function getLabel() {
        return 'Instagram';
    }

    public function getDescription() {
        return sprintf(n2_('Creates slides from %1$s.'), 'Instagram');
    }

    protected function loadSources() {
        new InstagramImages($this, 'images', 'Images');

    }
}