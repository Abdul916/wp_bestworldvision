<?php

namespace Nextend\SmartSlider3Pro\Generator\WordPress\NextgenGallery\Sources;

use Nextend\Framework\Form\Container\ContainerTable;
use Nextend\SmartSlider3\Generator\AbstractGenerator;
use Nextend\SmartSlider3Pro\Generator\WordPress\NextgenGallery\Elements\NextgenGalleries;
use Imagely\NGG\DataStorage\Manager;

class NextgenGalleryGallery extends AbstractGenerator {

    protected $layout = 'image';

    public function getDescription() {
        return sprintf(n2_('Creates slides from %1$s galleries.'), 'NextGEN Gallery');
    }

    public function renderFields($container) {
        $filterGroup = new ContainerTable($container, 'filter-group', n2_('Filter'));
        $filter      = $filterGroup->createRow('filter');
        new NextgenGalleries($filter, 'gallery', n2_('Source gallery'), '');
    }

    protected function _getData($count, $startIndex) {
        $manager = Manager::get_instance();

        global $wpdb;
        $images = $wpdb->get_results("SELECT * FROM " . $wpdb->base_prefix . "ngg_pictures WHERE galleryid = '" . intval($this->data->get('gallery', 0)) . "' ORDER BY sortorder LIMIT " . $startIndex . ", " . $count);

        $data = array();
        $i    = 0;
        foreach ($images as $image) {
            $data[$i]['image']       = $manager->get_image_url($image->pid);
            $data[$i]['thumbnail']   = $manager->get_image_url($image->pid, 'thumbnail');
            $data[$i]['title']       = $image->alttext;
            $data[$i]['description'] = $image->description;
            $data[$i]['id']          = $image->pid;

            $i++;
        }

        return $data;
    }
}