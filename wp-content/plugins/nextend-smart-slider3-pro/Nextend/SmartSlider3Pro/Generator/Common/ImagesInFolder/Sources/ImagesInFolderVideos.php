<?php

namespace Nextend\SmartSlider3Pro\Generator\Common\ImagesInFolder\Sources;

use Nextend\Framework\Filesystem\Filesystem;
use Nextend\Framework\Form\Container\ContainerTable;
use Nextend\Framework\Form\Element\MixedField\GeneratorOrder;
use Nextend\Framework\Form\Element\Text\Folder;
use Nextend\Framework\Notification\Notification;
use Nextend\Framework\Parser\Common;
use Nextend\SmartSlider3\Generator\AbstractGenerator;
use Nextend\SmartSlider3Pro\Generator\Common\ImagesInFolder\GeneratorGroupImagesInFolder;

class ImagesInFolderVideos extends AbstractGenerator {

    protected $layout = 'video_mp4';

    public function getDescription() {
        return sprintf(n2_('Creates slides from %1$s.'), n2_('Videos in folder'));
    }

    public function renderFields($container) {

        $filterGroup = new ContainerTable($container, 'filter-group', n2_('Filter'));

        $filter = $filterGroup->createRow('filter');

        new Folder($filter, 'sourcefolder', n2_('Source folder'), '');

        $orderGroup = new ContainerTable($container, 'order-group', n2_('Order'));
        $order      = $orderGroup->createRow('order-row');
        new GeneratorOrder($order, 'order', '0|*|asc', array(
            'options' => array(
                '0' => n2_('None'),
                '1' => n2_('Filename'),
                '2' => n2_('Creation date')
            )
        ));
    }

    protected function _getData($count, $startIndex) {
        $root   = GeneratorGroupImagesInFolder::fixSeparators(Filesystem::getImagesFolder());
        $source = GeneratorGroupImagesInFolder::fixSeparators($this->data->get('sourcefolder', ''));
        if (substr($source, 0, 1) == '*') {
            $media_folder = false;
            $source       = substr($source, 1);
            if (!Filesystem::existsFolder($source)) {
                Notification::error(n2_('Wrong path. This is the default upload/media folder path, so try to navigate from here:') . '<br>*' . $root);

                return null;
            } else {
                $root = '';
            }
        } else {
            $media_folder = true;
        }

        $folder = Filesystem::realpath($root . GeneratorGroupImagesInFolder::trim($source));
        $files  = Filesystem::files($folder);

        for ($i = count($files) - 1; $i >= 0; $i--) {
            $ext = strtolower(pathinfo($files[$i], PATHINFO_EXTENSION));
            if ($ext != 'mp4') {
                array_splice($files, $i, 1);
            }
        }

        $files = array_slice($files, $startIndex);

        list($orderBy, $sort) = Common::parse($this->data->get('order', '0|*|asc'));

        if ($orderBy > 0) {
            $fileCount = 1000; //hardcoded file number limitation
        } else {
            $fileCount = $count;
            $files     = array_slice($files, $startIndex);
        }

        $data = array();
        for ($i = 0; $i < $fileCount && isset($files[$i]); $i++) {
            $video    = GeneratorGroupImagesInFolder::pathToUri($folder . DIRECTORY_SEPARATOR . $files[$i], $media_folder);
            $data[$i] = array(
                'video'   => $video,
                'title'   => $files[$i],
                'name'    => preg_replace('/\\.[^.\\s]{3,4}$/', '', $files[$i]),
                'created' => filemtime($folder . DIRECTORY_SEPARATOR . $files[$i])
            );
        }

        if ($orderBy > 0) {
            $data = GeneratorGroupImagesInFolder::order($data, $orderBy, $sort);

            $data = array_slice($data, $startIndex, $count);
        }

        return $data;
    }
}