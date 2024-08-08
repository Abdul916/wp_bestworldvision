<?php

namespace Nextend\SmartSlider3Pro\Form\Element\Select;


use Nextend\Framework\Form\Element\Select;
use Nextend\Framework\View\Html;

class ShapeDividerSelect extends Select {

    private static $_options;

    public function __construct($insertAt, $name = '', $label = '', $default = '', array $parameters = array()) {
        if (self::$_options === null) {
            self::$_options = array(
                'simple-Arrow'        => n2_('Arrow'),
                'simple-Curve1'       => sprintf(n2_('Curve %d'), '1'),
                'simple-Curve2'       => sprintf(n2_('Curve %d'), '2'),
                'simple-Curve3'       => sprintf(n2_('Curve %d'), '3'),
                'simple-Curve4'       => sprintf(n2_('Curve %d'), '4'),
                'simple-Curves'       => n2_('Curves'),
                'simple-Fan1'         => sprintf(n2_('Fan %d'), '1'),
                'simple-Fan2'         => sprintf(n2_('Fan %d'), '2'),
                'simple-Fan3'         => sprintf(n2_('Fan %d'), '3'),
                'simple-Fan4'         => sprintf(n2_('Fan %d'), '4'),
                'simple-Hills'        => n2_('Hills'),
                'simple-Incline1'     => sprintf(n2_('Incline %d'), '1'),
                'simple-Incline2'     => sprintf(n2_('Incline %d'), '2'),
                'simple-Incline3'     => sprintf(n2_('Incline %d'), '3'),
                'simple-InverseArrow' => n2_('Inverse arrow'),
                'simple-Rectangle'    => n2_('Rectangle'),
                'simple-Slopes'       => n2_('Slopes'),
                'simple-Tilt1'        => sprintf(n2_('Tilt %d'), '1'),
                'simple-Tilt2'        => sprintf(n2_('Tilt %d'), '2'),
                'simple-Triangle1'    => sprintf(n2_('Triangle %d'), '1'),
                'simple-Triangle2'    => sprintf(n2_('Triangle %d'), '2'),
                'simple-Wave1'        => sprintf(n2_('Wave %d'), '1'),
                'simple-Wave2'        => sprintf(n2_('Wave %d'), '2'),
                'simple-Waves'        => n2_('Waves'),
                'simple-Columns1'     => sprintf(n2_('Columns %d'), '1'),
                'simple-Columns2'     => sprintf(n2_('Columns %d'), '2'),
                'simple-Paper1'       => sprintf(n2_('Paper %d'), '1'),
                'simple-Paper2'       => sprintf(n2_('Paper %d'), '2'),
                'simple-Paper3'       => sprintf(n2_('Paper %d'), '3'),
                'simple-Paper4'       => sprintf(n2_('Paper %d'), '4'),
                'bicolor'             => array(
                    'label'   => n2_('2 Colors'),
                    'options' => array(
                        'bi-Fan'         => n2_('Fan'),
                        'bi-MaskedWaves' => n2_('Masked waves'),
                        'bi-Ribbon'      => n2_('Ribbon'),
                        'bi-Waves'       => n2_('Waves')
                    )
                )
            );
        }

        parent::__construct($insertAt, $name, $label, $default, $parameters);
    }

    protected function renderOptions($options) {

        $html = '<option value="0" ' . $this->isSelected('0') . '>' . n2_('Disabled') . '</option>';

        $html .= $this->renderOptionsRecursive(self::$_options);

        return $html;
    }

    private function renderOptionsRecursive($options) {
        $html = '';

        foreach ($options as $value => $option) {
            if (is_array($option)) {
                $html .= Html::tag('optgroup', array('label' => $option['label']), $this->renderOptionsRecursive($option['options']));
            } else {
                $html .= '<option value="' . $value . '" ' . $this->isSelected($value) . '>' . $option . '</option>';

            }
        }

        return $html;
    }
}