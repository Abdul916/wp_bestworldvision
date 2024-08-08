<?php

namespace Nextend\SmartSlider3Pro\Renderable\Item\BeforeAfter;


use Nextend\Framework\Filesystem\Filesystem;
use Nextend\Framework\Form\Container\ContainerTable;
use Nextend\Framework\Form\Element\Font;
use Nextend\Framework\Form\Element\Hidden\HiddenFont;
use Nextend\Framework\Form\Element\Hidden\HiddenStyle;
use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Element\Select;
use Nextend\Framework\Form\Element\Style;
use Nextend\Framework\Form\Element\Text;
use Nextend\Framework\Form\Element\Text\Color;
use Nextend\Framework\Form\Element\Text\FieldImage;
use Nextend\Framework\Form\Element\Text\Number;
use Nextend\Framework\Form\Element\Text\NumberSlider;
use Nextend\Framework\Form\Fieldset;
use Nextend\Framework\Parser\Common;
use Nextend\Framework\ResourceTranslator\ResourceTranslator;
use Nextend\SmartSlider3\Renderable\Item\AbstractItem;

class ItemBeforeAfter extends AbstractItem {


    protected $fonts = array(
        'fontlabel'   => array(
            'defaultName' => 'item-beforeafter-fontlabel',
            'value'       => '{"data":[{"extra":"","color":"000000FF","size":"14||px","tshadow":"0|*|0|*|0|*|000000ff","lineheight":"1.2","bold":0,"italic":0,"underline":0,"align":"inherit","letterspacing":"normal","wordspacing":"normal","texttransform":"none"},{},{}]}'
        ),
        'fontcaption' => array(
            'defaultName' => 'item-beforeafter-fontcaption',
            'value'       => '{"data":[{"extra":"","color":"000000FF","size":"14||px","tshadow":"0|*|0|*|0|*|000000ff","lineheight":"1.2","bold":0,"italic":0,"underline":0,"align":"inherit","letterspacing":"normal","wordspacing":"normal","texttransform":"none"},{},{}]}'
        )
    );


    protected function isBuiltIn() {
        return true;
    }

    public function getType() {
        return 'beforeafter';
    }

    public function getTitle() {
        return n2_('Before After');
    }

    public function getIcon() {
        return 'ssi_32 ssi_32--beforeafter';
    }

    public function getGroup() {
        return n2_x('Special', 'Layer group');
    }


    public function createFrontend($id, $itemData, $layer) {
        return new ItemBeforeAfterFrontend($this, $id, $itemData, $layer);
    }


    public function loadResources($renderable) {
        parent::loadResources($renderable);
        $renderable->addLess(self::getAssetsPath() . "/beforeAfter.n2less", array(
            "sliderid" => $renderable->elementId
        ));


        $renderable->addScript('_N2.ItemBeforeafter.svgUrlList=' . json_encode($this->getArrowUrlList()) . ';');
        $renderable->addScript('_N2.ItemBeforeafter.svgTypeList=' . json_encode($this->getArrowTypeList()) . ';');


    }


    public function getValues() {
        return parent::getValues() + array(
                'imagebefore'       => '$ss3-frontend$/images/placeholder/image.png',
                'imageafter'        => '$ss3-frontend$/images/placeholder/video.png',
                'textbefore'        => n2_('Before'),
                'textafter'         => n2_('After'),
                'labelposition'     => 'center',
                'labelbackground'   => 'FFFFFFFF',
                'showlabel'         => 1,
                'labeltype'         => 'normal',
                'showcaption'       => 0,
                'captiontext'       => n2_('Caption'),
                'captiontype'       => 'normal',
                'captionposition'   => '3-2',
                'captionbackground' => 'FFFFFFFF',
                'direction'         => 'horizontal',
                'interaction'       => 'drag',
                'dividerstyle'      => 'arrow',
                'dividercolor'      => 'FFFFFFFF',
                'dividerwidth'      => '4',
                'width'             => '4',
                'startposition'     => '50',
                'image-optimize'  => 1
            );
    }


    public function getFilled($slide, $data) {
        $data = parent::getFilled($slide, $data);

        $data->set('imagebefore', $slide->fill($data->get('imagebefore', '')));
        $data->set('imageafter', $slide->fill($data->get('imageafter', '')));
        $data->set('textbefore', $slide->fill($data->get('textbefore', '')));
        $data->set('textafter', $slide->fill($data->get('textafter', '')));
        $data->set('captiontext', $slide->fill($data->get('captiontext', '')));
        $data->set('altbefore', $slide->fill($data->get('altbefore', '')));
        $data->set('altafter', $slide->fill($data->get('altafter', '')));

        return $data;

    }

    public function prepareExport($export, $data) {
        parent::prepareExport($export, $data);

        $export->addVisual($data->get('fontlabel'));
        $export->addVisual($data->get('fontcaption'));

        $export->addImage($data->get('imagebefore', ''));
        $export->addImage($data->get('imageafter', ''));
    }

    public function prepareImport($import, $data) {
        $data = parent::prepareImport($import, $data);

        $data->set('fontlabel', $import->fixSection($data->get('fontlabel')));
        $data->set('fontcaption', $import->fixSection($data->get('fontcaption')));

        $data->set('imagebefore', $import->fixImage($data->get('imagebefore')));
        $data->set('imageafter', $import->fixImage($data->get('imageafter')));



        return $data;
    }

    public function prepareSample($data) {
        $data->set('imagebefore', ResourceTranslator::toUrl($data->get('imagebefore', '')));
        $data->set('imageafter', ResourceTranslator::toUrl($data->get('imageafter', '')));

        return $data;
    }



    private function getArrowTypeList() {
        return array(
            'arrow' => 'default',
            'circle'    => 'custom',
            'rectangle' => 'custom'
        );
    }

    private function getArrowUrlList() {
        static $types = null;
        if ($types === null) {
            $types     = array();
            $extension = 'svg';
            $folder    = self::getAssetsPath() . '/svg/';
            $files     = Filesystem::files($folder);
            foreach ($files as $file) {
                $pathInfo = pathinfo($file);
                if (isset($pathInfo['extension']) && $pathInfo['extension'] == $extension) {
                    $types[$pathInfo['filename']] = Filesystem::readFile($folder . $file);;
                }
            }
        }

        return $types;
    }

    public function getArrowSvg($type) {
        $arrowList = $this->getArrowUrlList();

        if (isset($this->getArrowTypeList()[$type])) {
            $arrow = $arrowList["arrow_" . $this->getArrowTypeList()[$type]];
            return $arrow;
        }

        return false;
    }

    private function getCaptionTypes() {
        return array(
            '1-1' => n2_('Left top'),
            '1-2' => n2_('Center top'),
            '1-3' => n2_('Right top'),
            '2-1' => n2_('Left center'),
            '2-2' => n2_('Center'),
            '2-3' => n2_('Right Center'),
            '3-1' => n2_('Left bottom'),
            '3-2' => n2_('Center bottom'),
            '3-3' => n2_('Right bottom')
        );
    }




    public function globalDefaultItemFontAndStyle($container) {
        $table = new ContainerTable($container, $this->getType(), $this->getTitle());
        $row1  = $table->createRow($this->getType() . '-1');

        new Font($row1, 'item-beforeafter-fontlabel', false, $this->fonts['fontlabel']['value'], array(
            'mode' => 'simple'
        ));
        new Font($row1, 'item-beforeafter-captionlabel', false, $this->fonts['fontcaption']['value'], array(
            'mode' => 'simple'
        ));

    }

    public function renderFields($container) {

        $general = new Fieldset\LayerWindow\FieldsetLayerWindow($container, 'item-beforeafter', n2_('General'));
        new FieldImage($general, 'imagebefore', n2_('Before Image'), '', array(
            'width'         => 220,
            'relatedFields' => array(
                'item_beforeafteraltbefore'
            )
        ));
        new FieldImage($general, 'imageafter', n2_('After Image'), '', array(
            'width'         => 220,
            'relatedFields' => array(
                'item_beforeafteraltafter'
            )
        ));

        $label = new Fieldset\LayerWindow\FieldsetLayerWindow($container, 'item-beforeafter-label', n2_('Label'));
        new OnOff($label, 'showlabel', n2_('Labels'), 1, array(
            'relatedFieldsOn' => array(
                'item_beforeaftertextbefore',
                'item_beforeaftertextafter',
                'item_beforeafterlabelposition',
                'item_beforeafterlabeltype',
                'item_beforeafterlabelbackground',
            )
        ));
        new Select($label, 'labeltype', n2_('Show Label'), '', array(
            'options' => array(
                'normal' => n2_('Normal'),
                'hover'  => n2_('Hover'),
                'always' => n2_('Always')
            )
        ));
        new Select($label, 'labelposition', n2_('Position'), '', array(
            'options' => array(
                'start'  => n2_('Start'),
                'center' => n2_('Center'),
                'end'    => n2_('End')
            )
        ));
        new Text($label, 'textbefore', n2_('Before label'), '', array(
            'style' => 'width:132px;',
        ));
        new Text($label, 'textafter', n2_('After label'), '', array(
            'style' => 'width:132px;',
        ));


        new Color($label, 'labelbackground', n2_('Background'), 'FFFFFFFF', array(
            'alpha' => true,
            'style' => "width:75px"

        ));

        new HiddenFont($label, 'fontlabel', n2_('Font') . ' - ' . n2_('Label'), '', array(
            'mode' => 'simple'
        ));

        $behavior = new Fieldset\LayerWindow\FieldsetLayerWindow($container, 'item-beforeafter-behavior', n2_('Behavior'));
        new Select($behavior, 'direction', n2_('Direction'), '', array(
            'options' => array(
                'horizontal' => n2_('Horizontal'),
                'vertical'   => n2_('Vertical'),
            )
        ));

        new Select($behavior, 'interaction', n2_('Interaction'), 'drag', array(
            'options' => array(
                'drag'  => n2_('Drag'),
                'hover' => n2_('Hover'),
            )
        ));

        $divider = new Fieldset\LayerWindow\FieldsetLayerWindow($container, 'item-beforeafter-divider', n2_('Divider'));

        new Select($divider, 'dividerstyle', n2_('Type'), 'arrow', array(
            'options' => array(
                'line'      => n2_('Line'),
                'arrow'     => n2_('Arrow'),
                'circle'    => n2_('Circle'),
                'rectangle' => n2_('Rectangle'),
            )
        ));

        new Color($divider, 'dividercolor', n2_('Color'), 'FFFFFFFF', array(
            'alpha' => true,
            'style' => "width:65px"

        ));

        $caption = new Fieldset\LayerWindow\FieldsetLayerWindow($container, 'item-beforeafter-caption', n2_('Caption'));
        new OnOff($caption, 'showcaption', n2_('Caption'), 0, array(
            'relatedFieldsOn' => array(
                'item_beforeaftercaptiontype',
                'item_beforeaftercaptionposition',
                'item_beforeaftercaptiontext',
                'item_beforeaftercaptionbackground',
            )
        ));

        new Select($caption, 'captiontype', n2_('Show Caption'), 'normal', array(
            'options' => array(
                'normal' => n2_('Normal'),
                'hover'  => n2_('Hover'),
                'always' => n2_('Always')
            )
        ));

        new Select($caption, 'captionposition', n2_('Position'), '', array(
            'options' => $this->getCaptionTypes()
        ));

        new Text($caption, 'captiontext', n2_('Caption text'), 'Caption', array(
            'style' => 'width:132px;',
        ));

        new Color($caption, 'captionbackground', n2_('Background'), 'FFFFFFFF', array(
            'alpha' => true,
            'style' => "width:75px"

        ));

        new HiddenFont($caption, 'fontcaption', n2_('Font') . ' - ' . n2_('Caption'), '', array(
            'mode' => 'simple'
        ));


        new NumberSlider($divider, 'startposition', n2_('Position'), 50, array(
            'min'       => 0,
            'max'       => 100,
            'unit'      => '%',
            'style'     => "width:15px;",
            'sliderMax' => 100,
            'step'      => 5,
            'wide'      => 4
        ));

        new Select($divider, 'dividerwidth', n2_('Width'), 4, array(
            'options' => array(
                '2'  => '2',
                '4'  => '4',
                '6'  => '6',
                '8'  => '8',
                '10' => '10'
            )
        ));



        $seo = new Fieldset\LayerWindow\FieldsetLayerWindow($container, 'item-beforeafter-seo', n2_('SEO'));
        new Text($seo, 'altbefore', n2_('Before image alt tag'), '', array(
            'style' => 'width:132px;'
        ));
        new Text($seo, 'altafter', n2_('After image alt tag'), '', array(
            'style' => 'width:132px;'
        ));

        $optimize = new Fieldset\LayerWindow\FieldsetLayerWindow($container, 'item-caption-optimize', n2_('Optimize'));
        new OnOff($optimize, 'image-optimize', n2_('Optimize images'), 1, array(
            'tipLabel'       => n2_('Optimize image'),
            'tipDescription' => n2_('You can turn off the Layer image optimization for this image, to resize it for tablet and mobile.'),
            'tipLink'        => 'https://smartslider.helpscoutdocs.com/article/1839-caption-layer#optimize'
        ));

    }
}