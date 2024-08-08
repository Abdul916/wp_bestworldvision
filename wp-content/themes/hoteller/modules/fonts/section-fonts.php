<?php

/**
 * Custom Fonts
 */


Kirki::add_field( 'themegoods_customize', array(
    'type' => 'title',
    'settings'  => 'tg_custom_fonts_title',
    'label'    => esc_html__('Uploaded Fonts Settings', 'hoteller' ),
    'section'  => 'general_fonts',
	'priority' => 5,
) );

Kirki::add_field( 'themegoods_customize', array(
    'type' => 'repeater',
    'label' => esc_html__( 'Uploaded Fonts', 'hoteller' ) ,
    'description' => esc_html__( 'Here you can add your custom fonts', 'hoteller' ) ,
    'settings' => 'tg_custom_fonts',
    'priority' => 6,
    'transport' => 'auto',
    'section' => 'general_fonts',
    'row_label' => array(
        'type' => 'text',
        'value' => esc_html__( 'Upload Font', 'hoteller' ) ,
    ),
    'fields' => array(
        'font_name' => array(
            'type' => 'text',
            'label' => esc_html__( 'Name', 'hoteller' ) ,
        ) ,
        'font_url' => array(
            'type' => 'upload',
            'label' => esc_html__( 'Font File (*.woff)', 'hoteller' ) ,
        ) ,
        'font_fallback' => array(
            'type' => 'select',
            'label' => esc_html__( 'Fallback', 'hoteller' ) ,
            'defalut' => esc_html__( 'Helvetica, Arial, sans-serif', 'hoteller' ),
            'choices' => array(
                'sans-serif' => esc_html__( 'Helvetica, Arial, sans-serif', 'hoteller' ) ,
                'serif' => esc_html__( 'Georgia, serif', 'hoteller' ) ,
                'display' => esc_html__( '"Comic Sans MS", cursive, sans-serif', 'hoteller' ) ,
                'handwriting' => esc_html__( '"Comic Sans MS", cursive, sans-serif', 'hoteller' ) ,
                'monospace' => esc_html__( '"Lucida Console", Monaco, monospace', 'hoteller' ) ,
            )
        ) ,
    ) 
) );