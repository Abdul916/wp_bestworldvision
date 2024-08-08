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

class ImagesInFolderSubfolders extends AbstractGenerator {

    protected $layout = 'image';

    public function getDescription() {
        return sprintf(n2_('Creates slides from %1$s.'), n2_('Images in folder and subfolders'));
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

    function getSubFolders($folders = array(), $ready = array()) {
        $subFolders = array();
        foreach ($folders as $folder) {
            $ready[]          = $folder;
            $subFoldersHelper = Filesystem::folders($folder);
            foreach ($subFoldersHelper as $helper) {
                $subFolders[] = $folder . DIRECTORY_SEPARATOR . $helper;
            }
        }
        if (!empty($subFolders)) {
            return $this->getSubFolders($subFolders, $ready);
        } else {
            return $ready;
        }
    }

    protected function _getData($count, $startIndex) {
        $root   = GeneratorGroupImagesInFolder::fixSeparators(Filesystem::getImagesFolder());
        $source = GeneratorGroupImagesInFolder::fixSeparators($this->data->get('sourcefolder', ''));

        $search = (strpos($source, "%%") !== false);
        if (substr($source, 0, 1) != DIRECTORY_SEPARATOR && substr($source, 0, 1) != '*') {
            $source = DIRECTORY_SEPARATOR . $source;
        }
        if ($search) {
            $parts          = preg_split("/[\s\/]+/", $source);
            $originalSource = $source;
            $source         = '';
            foreach ($parts as $part) {
                if (strpos($part, "%%") !== false) {
                    $source .= $part . DIRECTORY_SEPARATOR;
                } else {
                    if (substr($source, -2, 2) == '//') {
                        $source = substr($source, 0, -1);
                    }
                    break;
                }
            }
            $base = $root;
        }

        if (substr($source, 0, 1) == '*') {
            $media_folder = false;
            $source       = substr($source, 1);
            if (!Filesystem::existsFolder($source)) {
                Notification::error(n2_('Wrong path. This is the default image folder path, so try to navigate from here:') . '<br>*' . $root);

                return array();
            } else {
                $root = '';
            }
        } else {
            $media_folder = true;
        }

        $baseFolder = Filesystem::realpath($root . GeneratorGroupImagesInFolder::trim($source));

        if (empty($baseFolder)) {
            Notification::error(n2_('Folder not found.'));

            return array();
        }
        $folders = $this->getSubFolders(array($baseFolder));

        if ($search) {
            if (substr($originalSource, 0, 1) == '*') {
                $originalSource = substr($originalSource, 1);
            } else {
                $originalSource = $base . $originalSource;
            }
            $from           = array(
                '%%',
                '/'
            );
            $to             = array(
                '([^.]+)',
                '\/'
            );
            $pattern        = str_replace($from, $to, $originalSource);
            $pattern        = '#' . $pattern . '#';
            $matchedFolders = array();
            foreach ($folders as $folder) {
                if (preg_match($pattern, $folder . DIRECTORY_SEPARATOR)) {
                    $matchedFolders[] = $folder;
                }
            }
            $folders = $matchedFolders;
        }

        $allFiles = array();
        foreach ($folders as $f) {
            $allFiles[$f] = Filesystem::files($f);
        }

        $includes = array_map('trim', explode(',', $this->data->get('includes', '')));
        $excludes = array_map('trim', explode(',', $this->data->get('excludes', '')));

        $return = array();

        list($orderBy, $sort) = Common::parse($this->data->get('order', '0|*|asc'));

        $removeResized = $this->data->get('remove_resize', 0);

        $IPTC = $this->data->get('iptc', 0) && function_exists('exif_read_data');

        if ($orderBy > 0 || $removeResized) {
            $fileCount = 1000; //hardcoded file number limitation
        } else {
            $fileCount = $count;
        }

        foreach ($allFiles as $folder => $files) {
            if (count($return) < $fileCount) {

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

                $data = array();
                for ($i = 0; $i < $fileCount && isset($files[$i]); $i++) {
                    $image    = GeneratorGroupImagesInFolder::pathToUri($folder . DIRECTORY_SEPARATOR . $files[$i], $media_folder);
                    $data[$i] = array(
                        'image'      => $image,
                        'thumbnail'  => $image,
                        'title'      => $files[$i],
                        'name'       => preg_replace('/\\.[^.\\s]{3,4}$/', '', $files[$i]),
                        'folder'     => $folder,
                        'foldername' => basename($folder),
                        'created'    => filemtime($folder . DIRECTORY_SEPARATOR . $files[$i])
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

                $return = array_merge($return, $data);
            }
        }

        if ($removeResized) {
            $new = array();
            for ($i = 0; $i < count($return); $i++) {
                if (!preg_match('/[_-]\d+x\d+(?=\.[a-z]{3,4}$)/', $return[$i]['title'], $match)) {
                    $new[] = $return[$i];
                }
            }
            $return = $new;
        }

        $return = GeneratorGroupImagesInFolder::order($return, $orderBy, $sort);

        $return = array_slice($return, $startIndex, $count);

        return $return;
    }
}