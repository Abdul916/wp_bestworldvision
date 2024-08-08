<?php


namespace Nextend\SmartSlider3Pro\Renderable\Item\Html;


use Nextend\Framework\Form\Element\Message\Warning;
use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Element\Radio\TextAlign;
use Nextend\Framework\Form\Element\Textarea;
use Nextend\Framework\Form\Fieldset;
use Nextend\SmartSlider3\Renderable\Item\AbstractItem;

class ItemHtml extends AbstractItem {

    protected $ordering = 102;

    protected $layerProperties = array("desktopportraitwidth" => 200);

    protected function isBuiltIn() {
        return true;
    }

    public function getType() {
        return 'html';
    }

    public function getTitle() {
        return n2_('HTML');
    }

    public function getIcon() {
        return 'ssi_32 ssi_32--html';
    }

    public function getGroup() {
        return n2_x('Advanced', 'Layer group');
    }

    public function createFrontend($id, $itemData, $layer) {
        return new ItemHtmlFrontend($this, $id, $itemData, $layer);
    }

    public function getValues() {
        return parent::getValues() + array(
                'html'      => '<div>' . n2_('Empty element') . '</div>',
                'css'       => ".selector{\n\n}",
                'textalign' => 'inherit'
            );
    }


    public function getFilled($slide, $data) {
        $data = parent::getFilled($slide, $data);

        $data->set('html', $slide->fill($data->get('html', '')));

        return $data;
    }

    public function renderFields($container) {
        $settings = new Fieldset\LayerWindow\FieldsetLayerWindow($container, 'item-html', n2_('General'));
        new Warning($settings, 'item-html-notice', sprintf(n2_('Please note that %1$swe do not support%2$s the HTML layer and the 3rd party codes loaded by it. We only suggest using this layer if you are a developer. %3$sAlso, make sure your HTML code is valid! Invalid HTML codes can mess up the entire slide and the only way resolving this problem is deleting the slide.'), '<b>', '</b>', '<br>'));

        new Textarea($settings, 'html', 'HTML', '', array(
            'height' => 130,
            'width'  => 314
        ));
        new TextAlign($settings, 'textalign', n2_('Text align'), 'inherit');

        if (class_exists('tidy', false)) {
            new OnOff($settings, 'tidy', n2_('Repair HTML errors'), 1);
        }

        new Textarea($settings, 'css', 'CSS', '', array(
            'height' => 130,
            'width'  => 314
        ));
    }
}