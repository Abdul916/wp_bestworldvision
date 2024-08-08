<?php

/**
 * Typekit
 */

$priority = 1;

Kirki::add_field( 'themegoods_customize', array(
    'type' => 'title',
    'settings'  => 'tg_typekit_title',
    'label'    => esc_html__('Typekit Settings', 'hoteller' ),
    'section'  => 'general_fonts',
	'priority' => 0,
) );


Kirki::add_field( 'themegoods_customize', array(
    'type' => 'switch',
    'settings' => 'tg_enable_typekit',
    'label' => esc_html__( 'Enable Typekit', 'hoteller' ) ,
    'section' => 'general_fonts',
    'default' => 0,
    'priority' => $priority,
    'transport' => 'auto',
    'choices' => array(
        'on'  => esc_html__( 'Enable', 'hoteller' ),
        'off' => esc_html__( 'Disable', 'hoteller' )
    )
) );

Kirki::add_field( 'themegoods_customize', array(
    'type' => 'text',
    'settings' => 'tg_typekit_id',
    'label' => esc_html__( 'Typekit ID', 'hoteller' ) ,
    'section' => 'general_fonts',
    'default' => '',
    'priority' => $priority,
    'transport' => 'auto',
    'required' => array(
        array(
            'setting' => 'tg_enable_typekit',
            'operator' => '==',
            'value' => '1',
        )
    ) ,
) );

Kirki::add_field( 'themegoods_customize', array(
    'type' => 'repeater',
    'label' => esc_html__( 'Typekit Fonts', 'hoteller' ) ,
    'description' => esc_html__( 'Here you can add typekit fonts', 'hoteller' ) ,
    'settings' => 'tg_typekit_fonts',
    'priority' => $priority,
    'transport' => 'auto',
    'section' => 'general_fonts',
    'row_label' => array(
        'type' => 'text',
        'value' => esc_html__( 'Typekit Font', 'hoteller' ) ,
    ),
    'default' => array(
        array(
            'font_name' => 'Europa',
            'font_css_name' => 'europa-web',
            'font_variants' => array( 'regular', 'italic', '700', '700italic' ),
            'font_fallback' => 'sans-serif',
            'font_subsets' => 'latin'
        )
    ),
    'fields' => array(
        'font_name' => array(
            'type' => 'text',
            'label' => esc_html__( 'Name', 'hoteller' ) ,
        ) ,
        'font_css_name' => array(
            'type' => 'text',
            'label' => esc_html__( 'CSS Name', 'hoteller' ) ,
        ) ,
        'font_variants' => array(
            'type' => 'select',
            'label' => esc_html__( 'Variants', 'hoteller' ) ,
            'multiple' => 18,
            'choices' => array(
                '100' => esc_html__( '100', 'hoteller' ) ,
                '100italic' => esc_html__( '100italic', 'hoteller' ) ,
                '200' => esc_html__( '200', 'hoteller' ) ,
                '200italic' => esc_html__( '200italic', 'hoteller' ) ,
                '300' => esc_html__( '300', 'hoteller' ) ,
                '300italic' => esc_html__( '300italic', 'hoteller' ) ,
                'regular' => esc_html__( 'regular', 'hoteller' ) ,
                'italic' => esc_html__( 'italic', 'hoteller' ) ,
                '500' => esc_html__( '500', 'hoteller' ) ,
                '500italic' => esc_html__( '500italic', 'hoteller' ) ,
                '600' => esc_html__( '600', 'hoteller' ) ,
                '600italic' => esc_html__( '600italic', 'hoteller' ) ,
                '700' => esc_html__( '700', 'hoteller' ) ,
                '700italic' => esc_html__( '700italic', 'hoteller' ) ,
                '800' => esc_html__( '800', 'hoteller' ) ,
                '800italic' => esc_html__( '800italic', 'hoteller' ) ,
                '900' => esc_html__( '900', 'hoteller' ) ,
                '900italic' => esc_html__( '900italic', 'hoteller' ) ,
            )
        ),
        'font_fallback' => array(
            'type' => 'select',
            'label' => esc_html__( 'Fallback', 'hoteller' ) ,
            'choices' => array(
                'sans-serif' => esc_html__( 'Helvetica, Arial, sans-serif', 'hoteller' ) ,
                'serif' => esc_html__( 'Georgia, serif', 'hoteller' ) ,
                'display' => esc_html__( '"Comic Sans MS", cursive, sans-serif', 'hoteller' ) ,
                'handwriting' => esc_html__( '"Comic Sans MS", cursive, sans-serif', 'hoteller' ) ,
                'monospace' => esc_html__( '"Lucida Console", Monaco, monospace', 'hoteller' ) ,
            )
        ) ,
        'font_subsets' => array(
            'type' => 'select',
            'label' => esc_html__( 'Subsets', 'hoteller' ) ,
            'multiple' => 7,
            'choices' => array(
                'cyrillic' => esc_html__( 'Cyrillic', 'hoteller' ) ,
                'cyrillic-ext' => esc_html__( 'Cyrillic Extended', 'hoteller' ) ,
                'devanagari' => esc_html__( 'Devanagari', 'hoteller' ) ,
                'greek' => esc_html__( 'Greek', 'hoteller' ) ,
                'greek-ext' => esc_html__( 'Greek Extended', 'hoteller' ) ,
                'khmer' => esc_html__( 'Khmer', 'hoteller' ) ,
                'latin' => esc_html__( 'Latin', 'hoteller' ) ,
            )
        ) ,
    ) ,
    'active_callback' => array(
        array(
            'setting' => 'tg_enable_typekit',
            'operator' => '==',
            'value' => '1'
        )
    )
) );