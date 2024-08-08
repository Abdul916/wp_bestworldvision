<?php


namespace Nextend\SmartSlider3Pro\Renderable\Item\Countdown;


use Nextend\Framework\Asset\Js\Js;
use Nextend\Framework\Form\Container\ContainerTable;
use Nextend\Framework\Form\Element\Font;
use Nextend\Framework\Form\Element\Gap;
use Nextend\Framework\Form\Element\Grouping;
use Nextend\Framework\Form\Element\Hidden\HiddenFont;
use Nextend\Framework\Form\Element\Hidden\HiddenStyle;
use Nextend\Framework\Form\Element\Message\Notice;
use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Element\Select;
use Nextend\Framework\Form\Element\Style;
use Nextend\Framework\Form\Element\Text;
use Nextend\Framework\Form\Element\Text\NumberAutoComplete;
use Nextend\Framework\Form\Fieldset;
use Nextend\Framework\Platform\Platform;
use Nextend\SmartSlider3\Form\Element\DatePicker;
use Nextend\SmartSlider3\Renderable\Item\AbstractItem;

class ItemCountdown extends AbstractItem {

    protected $ordering = 11;

    protected $fonts = array(
        'font'      => array(
            'defaultName' => 'item-countdown-font',
            'value'       => '{"data":[{"extra":"","color":"ffffffff","size":"40||px","tshadow":"0|*|0|*|0|*|000000ff","lineheight":"1","bold":0,"italic":0,"underline":0,"align":"inherit","letterspacing":"normal","wordspacing":"normal","texttransform":"none"}]}'
        ),
        'fontlabel' => array(
            'defaultName' => 'item-countdown-fontlabel',
            'value'       => '{"data":[{"extra":"","color":"ffffffff","size":"16||px","tshadow":"0|*|0|*|0|*|000000ff","lineheight":"1","bold":0,"italic":0,"underline":0,"align":"inherit","letterspacing":"normal","wordspacing":"normal","texttransform":"capitalize"}]}'
        )
    );

    protected $styles = array(
        'style' => array(
            'defaultName' => 'item-countdown-style',
            'value'       => ''
        )
    );

    protected function isBuiltIn() {
        return true;
    }

    public function getType() {
        return 'countdown';
    }

    public function getTitle() {
        return n2_('Countdown');
    }

    public function getIcon() {
        return 'ssi_32 ssi_32--countdown';
    }

    public function getGroup() {
        return n2_x('Special', 'Layer group');
    }

    public function createFrontend($id, $itemData, $layer) {
        return new ItemCountdownFrontend($this, $id, $itemData, $layer);
    }

    public function getValues() {

        return parent::getValues() + array(
                'slide-schedule' => 0,
                'date'           => date('Y-m-d H:i:s'),

                'gap'            => '10|*|10',
                'columns'        => 4,
                'label'          => 1,
                'tablet-style'   => 0,
                'tablet-gap'     => '10|*|10',
                'tablet-columns' => 4,

                'mobile-style'   => 0,
                'mobile-gap'     => '10|*|10',
                'mobile-columns' => 1,

                'action'       => '',
                'redirect-url' => ''
            );
    }

    public function prepareExport($export, $data) {
        parent::prepareExport($export, $data);

        $export->addVisual($data->get('font'));
        $export->addVisual($data->get('style'));
        $export->addVisual($data->get('fontlabel'));
    }

    public function prepareImport($import, $data) {
        $data = parent::prepareImport($import, $data);

        $data->set('font', $import->fixSection($data->get('font')));
        $data->set('style', $import->fixSection($data->get('style')));
        $data->set('fontlabel', $import->fixSection($data->get('fontlabel')));

        return $data;
    }

    public function globalDefaultItemFontAndStyle($container) {
        $table = new ContainerTable($container, $this->getType(), $this->getTitle());
        $row1  = $table->createRow($this->getType() . '-1');

        new Font($row1, 'item-countdown-font', n2_('Countdown'), $this->fonts['font']['value'], array(
            'mode' => 'simple'
        ));

        new Font($row1, 'item-countdown-fontlabel', n2_('Label'), $this->fonts['fontlabel']['value'], array(
            'mode' => 'simple'
        ));

        new Style($row1, 'item-countdown-style', n2_('Countdown'), $this->styles['style']['value'], array(
            'mode' => 'heading'
        ));
    }

    public function loadResources($renderable) {
        parent::loadResources($renderable);

        $renderable->addLess(self::getAssetsPath() . '/countdown.n2less', array(
            'sliderid' => $renderable->elementId
        ));
    }

    public function renderFields($container) {

        $offset  = (Platform::getTimestamp() - gmdate('U', time() - date('Z'))) / 60;
        $minutes = $offset % 60;
        $hours   = (int)($offset / 60);

        Js::addGlobalInline('window.ssTimezoneOffset=' . json_encode(sprintf('%+03d:%02d', $hours, $minutes)) . ';');

        $dateFieldset = new Fieldset\LayerWindow\FieldsetLayerWindow($container, 'item-countdown', n2_('Date'));

        new OnOff($dateFieldset, 'slide-schedule', n2_('Use slide schedule'), 0, array(
            'relatedFieldsOff' => array(
                'item_countdowndate'
            ),
            'relatedFieldsOn'  => array(
                'item_countdownschedule-notice'
            ),
            'tipLabel'         => n2_('Use slide schedule'),
            'tipDescription'   => n2_('You can use the "Unpublished on" date of the slide itself.'),
            'tipLink'          => 'https://smartslider.helpscoutdocs.com/article/2047-countdown-layer#use-slide-schedule'
        ));

        new Notice($dateFieldset, 'schedule-notice', n2_('Use Slide Schedule'), n2_('Go to Slide → Content tab and set the Unpublish on date for the slide.'));


        $dateGroup = new Grouping($dateFieldset, 'date-group');

        new DatePicker($dateGroup, 'date', 'Date', date('Y-m-d H:i:s'), array(
            'onOff' => false
        ));

        new HiddenFont($dateFieldset, 'font', n2_('Font') . ' - ' . n2_('Countdown'), '', array(
            'mode' => 'simple'
        ));

        new HiddenFont($dateFieldset, 'fontlabel', n2_('Font') . ' - ' . n2_('Label'), '', array(
            'mode' => 'simple'
        ));

        new HiddenStyle($dateFieldset, 'style', false, '', array(
            'mode' => 'heading'
        ));

        $style = new Fieldset\LayerWindow\FieldsetLayerWindow($container, 'item-countdown-style', n2_('Style'));

        new OnOff($style, 'label', n2_('Show label'), 1, array(
            'tipLabel'       => n2_('Show label'),
            'tipDescription' => n2_('Displays the days, hours, minutes and seconds texts under the counter numbers. To display the labels in your own language, translate the texts in the language files.'),
            'tipLink'        => 'https://smartslider.helpscoutdocs.com/article/1938-translation#translation'
        ));

        $gap = new Gap($style, 'gap', n2_('Gap'), '10|*|10', array(
            'tipLabel'       => n2_('Gap'),
            'tipDescription' => n2_('Creates vertical and horizontal distance between the counter elements.')
        ));
        $gap->setUnit('px');

        for ($i = 1; $i < 3; $i++) {
            new NumberAutoComplete($gap, 'gap-' . $i, false, '', array(
                'values' => array(
                    0,
                    5,
                    10,
                    20,
                    30
                ),
                'wide'   => 3
            ));
        }

        new Select($style, 'columns', n2_('Columns'), '4', array(
            'options' => array(
                '1' => '1',
                '2' => '2',
                '4' => '4'
            )
        ));

        $tabletStyleGroup = new Grouping($style, 'tablet-style-group');

        new OnOff($tabletStyleGroup, 'tablet-style', n2_('Tablet style'), 0, array(
            'relatedFieldsOn' => array(
                'item_countdowntablet-gap',
                'item_countdowntablet-columns'
            ),
            'tipLabel'        => n2_('Tablet style'),
            'tipDescription'  => n2_('Set custom Gap and Column for tablet.')
        ));

        $gap = new Gap($tabletStyleGroup, 'tablet-gap', n2_('Gap'), '10|*|10');
        $gap->setUnit('px');

        for ($i = 1; $i < 3; $i++) {
            new NumberAutoComplete($gap, 'tablet-gap-' . $i, false, '', array(
                'values' => array(
                    0,
                    5,
                    10,
                    20,
                    30
                ),
                'wide'   => 3
            ));
        }

        new Select($tabletStyleGroup, 'tablet-columns', n2_('Columns'), '4', array(
            'options' => array(
                '1' => '1',
                '2' => '2',
                '4' => '4'
            )
        ));

        $mobileStyleGroup = new Grouping($style, 'mobile-style-group');
        new OnOff($mobileStyleGroup, 'mobile-style', n2_('Mobile style'), 0, array(
            'relatedFieldsOn' => array(
                'item_countdownmobile-gap',
                'item_countdownmobile-columns'
            ),
            'tipLabel'        => n2_('Mobile style'),
            'tipDescription'  => n2_('Set custom Gap and Column for mobile.')
        ));

        $gap = new Gap($mobileStyleGroup, 'mobile-gap', n2_('Gap'), '10|*|10');
        $gap->setUnit('px');

        for ($i = 1; $i < 3; $i++) {
            new NumberAutoComplete($gap, 'mobile-gap-' . $i, false, '', array(
                'values' => array(
                    0,
                    5,
                    10,
                    20,
                    30
                ),
                'wide'   => 3
            ));
        }

        new Select($mobileStyleGroup, 'mobile-columns', n2_('Columns'), '4', array(
            'options' => array(
                '1' => '1',
                '2' => '2',
                '4' => '4'
            )
        ));

        $general = new Fieldset\LayerWindow\FieldsetLayerWindow($container, 'item-countdown-general', n2_('General'));

        new Select($general, 'action', n2_('Action when ends'), '4', array(
            'options'            => array(
                ''          => n2_('No action'),
                'hideLayer' => n2_('Hide layer'),
                'redirect'  => n2_('Redirect to URL'),
            ),
            'relatedValueFields' => array(
                array(
                    'values' => array(
                        'redirect',
                    ),
                    'field'  => array(
                        'item_countdownredirect-url'
                    )
                )
            ),
            'tipLabel'           => n2_('Action when ends'),
            'tipDescription'     => n2_('Choose what happens after the counter reached zero.'),
            'tipLink'            => 'https://smartslider.helpscoutdocs.com/article/2047-countdown-layer#action-when-ends'
        ));

        new Text($general, 'redirect-url', n2_('Redirect to URL'), '', array(
            'style' => 'width: 140px;'
        ));
    }

}