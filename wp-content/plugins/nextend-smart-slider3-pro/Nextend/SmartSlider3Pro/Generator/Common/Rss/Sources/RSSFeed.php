<?php

namespace Nextend\SmartSlider3Pro\Generator\Common\Rss\Sources;

use Exception;
use Nextend\Framework\Form\Container\ContainerTable;
use Nextend\Framework\Form\Element\Text;
use Nextend\Framework\Form\Element\Textarea;
use Nextend\Framework\Misc\HttpClient;
use Nextend\Framework\Notification\Notification;
use Nextend\SmartSlider3\Generator\AbstractGenerator;
use SimpleXmlElement;

class RSSFeed extends AbstractGenerator {

    protected $layout = 'text';

    public function getDescription() {
        return sprintf(n2_('Creates slides from %1$s.'), 'RSS');
    }

    public function renderFields($container) {
        $filterGroup = new ContainerTable($container, 'filter-group', n2_('Filter'));

        $url = $filterGroup->createRow('url-row');

        new Text($url, 'rssurl', 'RSS url', '', array(
            'style' => 'width:600px;'
        ));

        $date = $filterGroup->createRow('date');

        new Text($date, 'dateformat', n2_('Date format'), 'm-d-Y');
        new Text($date, 'offset', n2_('Offset hours'), '', array(
            'tipLabel'       => n2_('Offset hours'),
            'tipDescription' => n2_('Timezone offset in hours. For example: +2 or -7.')
        ));
        new Textarea($date, 'sourcetranslatedate', n2_('Translate date and time'), 'January->January||February->February||March->March', array(
            'width'  => 300,
            'height' => 100
        ));
    }

    protected function _getData($count, $startIndex) {
        $url             = $this->data->get('rssurl', '');
        $date_format     = $this->data->get('dateformat', 'Y-m-d');
        $sourceTranslate = $this->data->get('sourcetranslatedate', '');
        $translate       = $this->generateTranslationArray($sourceTranslate);

        $content = HttpClient::get($url);

        if (!$content) {
            Notification::error('The file on the given url is either empty or it cannot be accessed.');

            return null;
        }

        try {
            @$xml = new SimpleXmlElement($content);
            $namespaces = $xml->getNamespaces(true);
        } catch (Exception $e) {
            Notification::error(n2_('The data in the given url is not valid XML.'));

            return null;
        }

        $data = array();
        $i    = 0;

        $atom = false;
        if (isset($xml->channel->item)) {
            $entries = $xml->channel->item;
        } else if (isset($xml->entry)) {
            $entries = $xml->entry;
            $atom    = true;
        }

        foreach ($entries as $entry) {
            foreach ($entry as $key => $value) {
                $val = (string)$value;
                foreach ($value as $inner_key => $inner_val) {
                    $data[$i][$key . '_' . $inner_key] = $inner_val;
                }
                if (!empty($val)) {
                    if ($this->checkIsAValidDate($val)) {
                        $offset = $this->data->get('offset', '');
                        if (!empty($offset)) {
                            $offset = intval($offset) * 3600;
                        } else {
                            $offset = 0;
                        }
                        $val = $this->translate(date($date_format, strtotime($val) + $offset), $translate);
                    }
                    $data[$i][$key] = $val;
                }
                $attributes = $entry->$key->attributes();
                if (!empty($attributes)) {
                    foreach ($attributes as $attribute => $attribute_val) {
                        $attribute_val_str = @(string)$attribute_val;
                        if (isset($attribute_val_str)) {
                            $data[$i][$key . '_' . $attribute] = $attribute_val_str;
                        }
                    }
                }

                if (is_array($namespaces)) {
                    foreach ($namespaces as $namespace => $namespacevalue) {
                        $data[$i][$namespace] = $namespacevalue;
                        foreach ($entry->children($namespacevalue) as $k => $v) {
                            $value = @(string)$v;
                            if (!empty($value)) {
                                $data[$i][$namespace . '_' . $k] = trim($value);
                            }
                            $namespace_attributes = @$v->attributes();
                            if (!empty($namespace_attributes)) {
                                foreach ($namespace_attributes as $attr => $attr_val) {
                                    $data[$i][$namespace . '_' . $k . '_' . $attr] = trim((string)$attr_val);
                                }
                            }
                        }
                    }
                }
            }

            $group = $entry->children('http://search.yahoo.com/mrss/')->group;
            foreach ($group as $group_name => $group_data) {
                foreach ($group_data as $group_key => $group_val) {
                    $group_val_str = @(string)$attribute_val;
                    if (isset($group_val_str)) {
                        $data[$i][$group_name . '_' . $group_key] = $group_val_str;
                    }
                    $attributes = $group_data->$group_key->attributes();
                    if (!empty($attributes)) {
                        foreach ($attributes as $attribute => $attribute_val) {
                            $attribute_val_str = @(string)$attribute_val;
                            if (isset($attribute_val_str)) {
                                $data[$i][$group_name . '_' . $group_key . '_' . $attribute] = $attribute_val_str;
                            }
                        }
                    }
                }
            }
            if ($atom) {
                $content = @(string)$entry->content;
            } else {
                $content = @(string)$entry->children('http://purl.org/rss/1.0/modules/content/')->encoded;
            }
            if (!empty($content)) {
                $data[$i]['content'] = $content;
            }
            $i++;
            if ($i == $count + $startIndex) break;
        }
        $data = array_slice($data, $startIndex, $count);

        return $data;
    }

    protected function checkIsAValidDate($dateString) {
        return (bool)strtotime($dateString);
    }

    private function translate($from, $translate) {
        if (!empty($translate) && !empty($from)) {
            foreach ($translate as $key => $value) {
                $from = str_replace($key, $value, $from);
            }
        }

        return $from;
    }

    private function generateTranslationArray($sourceTranslate) {
        $translate      = array();
        $translateValue = explode('||', $sourceTranslate);
        if ($sourceTranslate != 'January->January||February->February||March->March' && !empty($translateValue)) {
            foreach ($translateValue as $tv) {
                $translateArray = explode('->', $tv);
                if (!empty($translateArray) && count($translateArray) == 2) {
                    $translate[$translateArray[0]] = $translateArray[1];
                }
            }
        }

        return $translate;
    }
}
