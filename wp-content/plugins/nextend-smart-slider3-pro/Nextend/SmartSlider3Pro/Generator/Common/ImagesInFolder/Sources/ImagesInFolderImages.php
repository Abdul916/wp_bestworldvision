<?php

namespace Nextend\SmartSlider3Pro\Generator\Common\ImagesInFolder\Sources;

use Nextend\Framework\Filesystem\Filesystem;
use Nextend\Framework\Form\Container\ContainerTable;
use Nextend\Framework\Form\Element\MixedField\GeneratorOrder;
use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Element\Text;
use Nextend\Framework\Form\Element\Text\Folder;
use Nextend\Framework\Notification\Notification;
use Nextend\Framework\Parser\Common;
use Nextend\SmartSlider3\Generator\AbstractGenerator;
use Nextend\SmartSlider3Pro\Generator\Common\ImagesInFolder\GeneratorGroupImagesInFolder;

class ImagesInFolderImages extends AbstractGenerator {

    protected $layout = 'image';

    public function getDescription() {
        return sprintf(n2_('Creates slides from %1$s.'), n2_('Images in folder'));
    }

    public function renderFields($container) {

        $filterGroup = new ContainerTable($container, 'filter-group', n2_('Filter'));

        $filter = $filterGroup->createRow('filter');

        new Folder($filter, 'sourcefolder', n2_('Source folder'), '');

        new OnOff($filter, 'iptc', 'EXIF', 0);

        $excludeGroup = $filterGroup->createRowGroup('exclude-group', n2_('Filename based exclusion'));

        $excludeRow = $excludeGroup->createRow('exclude-row');

        new OnOff($excludeRow, 'remove_resize', 'Exclude resized images', 0, array(
            'tipLabel'       => n2_('Remove resized images'),
            'tipDescription' => n2_('This option removes files that match the "-[number]x[number].[extension]" pattern in the end of their file names. For example, "myimage.jpg" will stay in the generator result, but "myimage-120x120.jpg" will be removed, because it\'s the same image, just in a smaller size.'),
            'tipLink'        => 'https://smartslider.helpscoutdocs.com/article/1901-images-from-folder-generator#exclude-resized-images'
        ));

        new Text($excludeRow, 'includes', n2_('Filename has to contain'), '', array(
            'tipLabel'       => n2_('Filename has to contain'),
            'tipDescription' => n2_('Only those images will be asked down, which have the given texts within their filenames. You can write down multiple texts separated by comma.')
        ));

        new Text($excludeRow, 'excludes', n2_('Filename cannot contain'), '', array(
            'tipLabel'       => n2_('Filename cannot contain'),
            'tipDescription' => n2_('Only those images will be asked down, which don\'t have the given texts within their filenames. You can write down multiple texts separated by comma.')
        ));

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
                Notification::error(n2_('Wrong path. This is the default image folder path, so try to navigate from here:') . '<br>*' . $root);

                return null;
            } else {
                $root = '';
            }
        } else {
            $media_folder = true;
        }
        $folder = Filesystem::realpath($root . GeneratorGroupImagesInFolder::trim($source));
        $files  = Filesystem::files($folder);

        $includes = array_map('trim', explode(',', $this->data->get('includes', '')));
        $excludes = array_map('trim', explode(',', $this->data->get('excludes', '')));

        for ($i = count($files) - 1; $i >= 0; $i--) {
            $ext        = strtolower(pathinfo($files[$i], PATHINFO_EXTENSION));
            $extensions = array(
                'jpg',
                'jpeg',
                'png',
                'svg',
                'gif',
                'webp'
            );
            if (!in_array($ext, $extensions) || GeneratorGroupImagesInFolder::found($includes, $files[$i]) === false || GeneratorGroupImagesInFolder::found($excludes, $files[$i]) === true) {
                array_splice($files, $i, 1);
            }
        }

        $IPTC = $this->data->get('iptc', 0) && function_exists('exif_read_data');

        list($orderBy, $sort) = Common::parse($this->data->get('order', '0|*|asc'));

        $removeResized = $this->data->get('remove_resize', 0);

        if ($orderBy > 0 || $removeResized) {
            $fileCount = 1000; //hardcoded file number limitation
        } else {
            $fileCount = $count;
            $files     = array_slice($files, $startIndex);
        }

        $data = array();
        for ($i = 0; $i < $fileCount && isset($files[$i]); $i++) {
            $image    = GeneratorGroupImagesInFolder::pathToUri($folder . DIRECTORY_SEPARATOR . $files[$i], $media_folder);
            $data[$i] = array(
                'image'     => $image,
                'thumbnail' => $image,
                'title'     => $files[$i],
                'name'      => preg_replace('/\\.[^.\\s]{3,4}$/', '', $files[$i]),
                'created'   => filemtime($folder . DIRECTORY_SEPARATOR . $files[$i])
            );
            if ($IPTC) {
                $properties = @exif_read_data($folder . DIRECTORY_SEPARATOR . $files[$i]);
                if ($properties) {
                    foreach ($properties as $key => $property) {
                        if (!is_array($property) && $property != '' && preg_match('/^[a-zA-Z]+$/', $key)) {
                            preg_match('/([2-9][0-9]*)\/([0-9]+)/', $property, $matches);
                            if (empty($matches)) {
                                $data[$i][$key] = $property;
                            } else {
                                $data[$i][$key] = round($matches[1] / $matches[2], 2);
                            }
                        }
                    }
                }
            }
        }

        if ($removeResized) {
            $new = array();
            for ($i = 0; $i < count($data); $i++) {
                if (!preg_match('/[_-]\d+x\d+(?=\.[a-z]{3,4}$)/', $data[$i]['title'], $match)) {
                    $new[] = $data[$i];
                }
            }
            $data = $new;
        }

        $data = GeneratorGroupImagesInFolder::order($data, $orderBy, $sort);

        if ($orderBy > 0 || $removeResized) {
            $data = array_slice($data, $startIndex, $count);
        }

        return $data;
    }
}