<?php

namespace Nextend\SmartSlider3Pro\Generator\WordPress\TheEventsCalendar\Elements;

use Nextend\Framework\Form\Element\Select;


class TheEventsCalendarCategories extends Select {

    public function __construct($insertAt, $name = '', $label = '', $default = '', $parameters = array()) {
        parent::__construct($insertAt, $name, $label, $default, $parameters);

        $categories = get_categories(array(
            'type'         => 'tribe_events',
            'child_of'     => 0,
            'parent'       => '',
            'orderby'      => 'name',
            'order'        => 'ASC',
            'hide_empty'   => 0,
            'hierarchical' => 1,
            'exclude'      => '',
            'include'      => '',
            'number'       => '',
            'taxonomy'     => 'tribe_events_cat',
            'pad_counts'   => false
        ));
        $new        = array();
        foreach ($categories as $a) {
            $new[$a->category_parent][] = $a;
        }
        $options = array();
        $this->createTree($options, $new, 0);

        $this->options['0'] = n2_('All');
        if (count($options)) {
            foreach ($options as $option) {
                $this->options[$option->cat_ID] = ' - ' . $option->treename;
            }
        }

    }
}
