<?php

namespace Nextend\SmartSlider3Pro\Generator\Common\Instagram\Sources;

use Nextend\Framework\Form\Container\ContainerTable;
use Nextend\Framework\Form\Element\MixedField\GeneratorOrder;
use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Element\Select;
use Nextend\Framework\Form\Element\Text;
use Nextend\Framework\Form\Element\Textarea;
use Nextend\Framework\Parser\Common;
use Nextend\Framework\Platform\Platform;
use Nextend\SmartSlider3\Generator\AbstractGenerator;
use Nextend\Framework\Browse\BulletProof\Exception;
use Nextend\Framework\Notification\Notification;

class InstagramImages extends AbstractGenerator {

    protected $layout = 'image';

    public function getDescription() {
        return sprintf(n2_('Creates slides from %1$s.'), 'Instagram media');
    }

    public function renderFields($container) {
        $this->group->getConfiguration()
                    ->checkExpire($container);

        $filterGroup = new ContainerTable($container, 'filter-group', n2_('Filter'));
        $filterImage = $filterGroup->createRow('filter-image');
        new OnOff($filterImage, 'allow_images', "Allow Single images", 1, array());

        $filteralbum = $filterGroup->createRow('filter-video');

        new OnOff($filteralbum, 'allow_album', "Allow albums", 0, array(
            'relatedFieldsOn' => array(
                "generatoralbum_type",

            )
        ));

        new Select($filteralbum, 'album_type', n2_('Album loading method'), '0', array(
            'options' => array(
                0 => n2_('Load first images only'),
                1 => n2_('Load album images separately'),
                2 => n2_('Load Album images as record data')
            )
        ));


        $filtervideo = $filterGroup->createRow('filter-video');
        new OnOff($filtervideo, 'allow_video', "Allow videos", 0);

        $date = $filterGroup->createRow('date-row');
        new Text($date, 'instagramdate', n2_('Date format'), 'm-d-Y');
        new Text($date, 'instagramtime', n2_('Time format'), 'G:i');
        new Textarea($date, 'instagramtranslatedate', n2_('Translate date and time'), 'January->January||February->February||March->March', array(
            'width'  => 300,
            'height' => 100
        ));


        $orderGroup = new ContainerTable($container, 'order-group', n2_('Order'));
        $order      = $orderGroup->createRow('order-row');
        new GeneratorOrder($order, 'order', '0|*|asc', array(
            'options' => array(
                '0' => n2_('None'),
                '1' => n2_('Caption'),
                '2' => n2_('Creation date')
            )
        ));


    }


    protected function _getData($count, $startIndex) {
        $api  = $this->group->getConfiguration()
                            ->getApi();
        $data = array();

        $api->setMediaFields('timestamp,media_url,media_type,permalink,thumbnail_url,caption,username');
        $api->setMediaChildrenFields('timestamp,media_url,media_type,permalink,username');
        try {
            $images                    = $api->getUserMedia();
            $dateOptions               = array();
            $dateOptions['dateFormat'] = $this->data->get('instagramdate', 'm-d-Y');
            $dateOptions['timeFormat'] = $this->data->get('instagramtime', 'G:i');

            $translateDate            = $this->data->get('instagramtranslatedate', '');
            $translateValue           = explode('||', $translateDate);
            $dateOptions['translate'] = array();
            if ($translateDate != 'January->January||February->February||March->March' && !empty($translateValue)) {
                foreach ($translateValue as $tv) {
                    $translateArray = explode('->', $tv);
                    if (!empty($translateArray) && count($translateArray) == 2) {
                        $dateOptions['translate'][$translateArray[0]] = $translateArray[1];
                    }
                }
            }


            if (is_object($images) && isset($images->data)) {
                $idx = 0;
                $this->iterateImages($images->data, $dateOptions, $data, $idx, $api);
            }

            $this->shortData($data);
            $data = array_slice($data, $startIndex, $count);
        } catch (Exception $e) {
            Notification::error($e->getMessage());
            return null;
        }

        return $data;
    }

    protected function shortData(&$data) {
        list($orderBy, $sort) = Common::parse($this->data->get('order', '0|*|asc'));

        switch ($orderBy) {
            case 1:
                usort($data, array(
                    $this,
                    $sort
                ));
                break;
            case 2:
                usort($data, array(
                    $this,
                    'orderByDate_' . $sort
                ));
                break;
            default:
                break;
        }

    }

    private function inAvailableMediaTypes($img, $force = false) {
        $mediaTypes = [
            'IMAGE'          => ($force) ? $force : intval($this->data->get('allow_images', 1)),
            'CAROUSEL_ALBUM' => intval($this->data->get('allow_album', 0)),
            'VIDEO'          => intval($this->data->get('allow_video', 0)),
        ];

        if (isset($mediaTypes[$img->media_type])) {
            return $mediaTypes[$img->media_type];
        }

        return false;
    }

    private function transformData($img, $dateOptions, $parent = null, $withImage = true) {
        $data = array();
        if ($parent) {
            $data['caption'] = isset($parent->caption) ? $parent->caption : '';
        } else {
            $data['caption'] = isset($img->caption) ? $img->caption : '';
        }

        if ($img->media_type === 'VIDEO') {
            $data['image'] = $img->thumbnail_url;
            $data['video'] = $img->media_url;
        }

        if ($withImage && ($img->media_type === 'IMAGE' || $img->media_type === 'CAROUSEL_ALBUM')) {
            $data['image'] = $img->media_url;
        }

        $data['link']      = $img->permalink;
        $data['date']      = $this->translate($this->formatDate($img->timestamp, $dateOptions['dateFormat']), $dateOptions['translate']);
        $data['time']      = $this->translate($this->formatDate($img->timestamp, $dateOptions['timeFormat']), $dateOptions['translate']);
        $data['username']  = $img->username;
        $data['timestamp'] = strtotime($img->timestamp);

        return $data;
    }


    private function iterateImages($images, $dateOptions, &$data, &$idx, $api, &$childrenIdx = null, $parent = null) {

        foreach ($images as $img) {

            //force needs because children's images should be shown
            $force = false;


            if (isset($childrenIdx) || $parent) {
                //if it's children image, get children media, what has different data
                $img   = $api->getMedia($img->id, true);
                $force = true;
            }

            if ($this->inAvailableMediaTypes($img, $force)) {
                //if carousel album,check if need to load separately images or not
                if ($img->media_type === 'CAROUSEL_ALBUM' && $this->data->get('allow_album', 0)) {
                    $albumType = $this->data->get('album_type', 0);
                    if ($albumType) {

                        if ($albumType == 1) {
                            //children images don't have caption, so need parent's caption
                            $parent = $img;
                        } else {
                            $childrenIdx = 1;
                            /*@TODO should we add main image if we use images as record data?*/
                            $data[$idx] = $this->transformData($img, $dateOptions, $parent);
                        }
                        //iterate trough children
                        $children = $api->getMediaChildren($img->id);
                        $this->iterateImages($children->children->data, $dateOptions, $data, $idx, $api, $childrenIdx, $parent);
                        $parent      = null;
                        $childrenIdx = null;
                        $idx++;
                    } else {
                        $data[$idx] = $this->transformData($img, $dateOptions);
                        $idx++;
                    }
                } else {
                    if (isset($childrenIdx)) {
                        $data[$idx]['album_' . strtolower($img->media_type) . '_' . $childrenIdx] = $img->media_url;
                        $childrenIdx++;
                    } else {
                        $data[$idx] = $this->transformData($img, $dateOptions, $parent);
                        $idx++;
                    }
                }

            }

        }

    }

    private function translate($from, $translate) {
        if (!empty($translate) && !empty($from)) {
            foreach ($translate as $key => $value) {
                $from = str_replace($key, $value, $from);
            }
        }

        return $from;
    }

    private function formatDate($datetime, $format = 'Y-m-d', $strtotime = true) {
        if ($datetime != '0000-00-00 00:00:00') {
            if ($strtotime) {
                $datetime = strtotime($datetime);
            }

            return date($format, $datetime);
        } else {
            return '';
        }
    }

    private function asc($a, $b) {
        return (strtolower($b['caption']) < strtolower($a['caption']) ? 1 : -1);
    }

    private function desc($a, $b) {
        return (strtolower($a['caption']) < strtolower($b['caption']) ? 1 : -1);
    }

    private function orderByDate_asc($a, $b) {
        return ($b['timestamp'] < $a['timestamp'] ? 1 : -1);
    }

    private function orderByDate_desc($a, $b) {
        return ($a['timestamp'] < $b['timestamp'] ? 1 : -1);
    }

}
