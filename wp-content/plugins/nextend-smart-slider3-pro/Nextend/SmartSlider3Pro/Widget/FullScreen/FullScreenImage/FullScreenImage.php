<?php


namespace Nextend\SmartSlider3Pro\Widget\FullScreen\FullScreenImage;


use Nextend\Framework\Form\Element\Grouping;
use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Element\Radio\ImageListFromFolder;
use Nextend\Framework\Form\Element\Style;
use Nextend\Framework\Form\Element\Text\Color;
use Nextend\Framework\Form\Element\Text\FieldImage;
use Nextend\Framework\Form\Element\Text\Number;
use Nextend\Framework\Form\Fieldset\FieldsetRow;
use Nextend\SmartSlider3\Form\Element\Group\WidgetPosition;
use Nextend\SmartSlider3\Widget\AbstractWidget;

class FullScreenImage extends AbstractWidget {

    protected $key = 'widget-fullscreen-';

    protected $defaults = array(
        'widget-fullscreen-desktop-image-width' => 16,
        'widget-fullscreen-tablet-image-width'  => 16,
        'widget-fullscreen-mobile-image-width'  => 8,
        'widget-fullscreen-tonormal-image'      => '',
        'widget-fullscreen-tonormal-color'      => 'ffffffcc',
        'widget-fullscreen-tonormal'            => '$ss$/plugins/widgetfullscreen/image/image/tonormal/full1.svg',
        'widget-fullscreen-style'               => '{"data":[{"backgroundcolor":"000000ab","padding":"10|*|10|*|10|*|10|*|px","boxshadow":"0|*|0|*|0|*|0|*|000000ff","border":"0|*|solid|*|000000ff","borderradius":"3","extra":""},{"backgroundcolor":"000000ab"}]}',
        'widget-fullscreen-position-mode'       => 'simple',
        'widget-fullscreen-position-area'       => 4,
        'widget-fullscreen-position-offset'     => 15,
        'widget-fullscreen-mirror'              => 1,
        'widget-fullscreen-tofull-image'        => '',
        'widget-fullscreen-tofull-color'        => 'ffffffcc',
        'widget-fullscreen-tofull'              => '$ss$/plugins/widgetfullscreen/image/image/tofull/full1.svg'
    );

    public function renderFields($container) {

        $rowIcon = new FieldsetRow($container, 'widget-bullet-transition-row-icon');

        $fieldToNormal = new ImageListFromFolder($rowIcon, 'widget-fullscreen-tonormal', n2_('To normal'), '', array(
            'folder' => self::getAssetsPath() . '/tonormal/'
        ));

        new FieldImage($fieldToNormal, 'widget-fullscreen-tonormal-image', n2_('Custom'), '', array(
            'relatedFieldsOff' => array(
                'sliderwidget-fullscreen-tonormal-color'
            )
        ));

        new Color($rowIcon, 'widget-fullscreen-tonormal-color', n2_('Color'), '', array(
            'alpha' => true
        ));


        new OnOff($rowIcon, 'widget-fullscreen-mirror', n2_('Mirror'), '', array(
            'relatedFieldsOff' => array(
                'sliderwidget-fullscreen-tofull',
                'sliderwidget-fullscreen-tofull-color'
            )
        ));

        $fieldToFull = new ImageListFromFolder($rowIcon, 'widget-fullscreen-tofull', n2_('To fullscreen'), '', array(
            'folder' => self::getAssetsPath() . '/tofull/'
        ));

        new FieldImage($fieldToFull, 'widget-fullscreen-tofull-image', n2_('Custom'), '', array(
            'relatedFieldsOff' => array(
                'sliderwidget-fullscreen-tofull-color-grouping'
            )
        ));

        $groupingPauseColor = new Grouping($rowIcon, 'widget-fullscreen-tofull-color-grouping');
        new Color($groupingPauseColor, 'widget-fullscreen-tofull-color', n2_('Color'), '', array(
            'alpha' => true
        ));

        $row3 = new FieldsetRow($container, 'widget-fullscreen-image-row-3');

        new Style($row3, 'widget-fullscreen-style', n2_('Fullscreen'), '', array(
            'mode'    => 'button',
            'preview' => 'SmartSliderAdminWidgetFullScreenImage'
        ));

        new WidgetPosition($row3, 'widget-fullscreen-position', n2_('Position'));


        $row4 = new FieldsetRow($container, 'widget-fullscreen-image-row-4');

        new Number($row4, 'widget-fullscreen-desktop-image-width', n2_('Image width - Desktop'), '', array(
            'wide' => 4,
            'unit' => 'px'
        ));

        new Number($row4, 'widget-fullscreen-tablet-image-width', n2_('Image width - Tablet'), '', array(
            'wide' => 4,
            'unit' => 'px'
        ));

        new Number($row4, 'widget-fullscreen-mobile-image-width', n2_('Image width - Mobile'), '', array(
            'wide' => 4,
            'unit' => 'px'
        ));

    }

    public function prepareExport($export, $params) {
        $export->addImage($params->get($this->key . 'tonormal-image', ''));
        $export->addImage($params->get($this->key . 'tofull-image', ''));

        $export->addVisual($params->get($this->key . 'style'));
    }

    public function prepareImport($import, $params) {

        $params->set($this->key . 'tonormal-image', $import->fixImage($params->get($this->key . 'tonormal-image', '')));
        $params->set($this->key . 'tofull-image', $import->fixImage($params->get($this->key . 'tofull-image', '')));

        $params->set($this->key . 'style', $import->fixSection($params->get($this->key . 'style', '')));
    }
}