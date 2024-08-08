<?php
/**
* Custom Sanitize Functions
**/
function hoteller_sanitize_checkbox( $input ) {
	if(is_bool($input))
	{
		return $input;
	}
	else
	{
		return false;
	}

}

function hoteller_sanitize_slider( $input ) {	if(is_numeric($input))
	{
		return $input;
	}
	else
	{
		return 0;

	}
}

function hoteller_sanitize_html( $input ) {
    return wp_kses_post( $input );
}

/*** Configuration to disable default WordPress customizer tabs
**/

add_action( 'customize_register', 'hoteller_customize_register' );
function hoteller_customize_register( $wp_customize ) {
	$wp_customize->remove_section( 'colors' );
	$wp_customize->remove_section( 'background_image' );
}

/**
 * Configuration sample for the Kirki Customizer
 */
function hoteller_demo_configuration_sample() {

    /**
     * If you need to include Kirki in your theme,
     * then you may want to consider adding the translations here
     * using your textdomain.
     * 
     * If you're using Kirki as a plugin then you can remove these.
     */

    $strings = array(
        'background-color' => esc_html__('Background Color', 'hoteller' ),
        'background-image' => esc_html__('Background Image', 'hoteller' ),
        'no-repeat' => esc_html__('No Repeat', 'hoteller' ),
        'repeat-all' => esc_html__('Repeat All', 'hoteller' ),
        'repeat-x' => esc_html__('Repeat Horizontally', 'hoteller' ),
        'repeat-y' => esc_html__('Repeat Vertically', 'hoteller' ),
        'inherit' => esc_html__('Inherit', 'hoteller' ),
        'background-repeat' => esc_html__('Background Repeat', 'hoteller' ),
        'cover' => esc_html__('Cover', 'hoteller' ),
        'contain' => esc_html__('Contain', 'hoteller' ),
        'background-size' => esc_html__('Background Size', 'hoteller' ),
        'fixed' => esc_html__('Fixed', 'hoteller' ),
        'scroll' => esc_html__('Scroll', 'hoteller' ),
        'background-attachment' => esc_html__('Background Attachment', 'hoteller' ),
        'left-top' => esc_html__('Left Top', 'hoteller' ),
        'left-center' => esc_html__('Left Center', 'hoteller' ),
        'left-bottom' => esc_html__('Left Bottom', 'hoteller' ),
        'right-top' => esc_html__('Right Top', 'hoteller' ),
        'right-center' => esc_html__('Right Center', 'hoteller' ),
        'right-bottom' => esc_html__('Right Bottom', 'hoteller' ),
        'center-top' => esc_html__('Center Top', 'hoteller' ),
        'center-center' => esc_html__('Center Center', 'hoteller' ),
        'center-bottom' => esc_html__('Center Bottom', 'hoteller' ),
        'background-position' => esc_html__('Background Position', 'hoteller' ),
        'background-opacity' => esc_html__('Background Opacity', 'hoteller' ),
        'ON' => esc_html__('ON', 'hoteller' ),
        'OFF' => esc_html__('OFF', 'hoteller' ),
        'all' => esc_html__('All', 'hoteller' ),
        'cyrillic' => esc_html__('Cyrillic', 'hoteller' ),
        'cyrillic-ext' => esc_html__('Cyrillic Extended', 'hoteller' ),
        'devanagari' => esc_html__('Devanagari', 'hoteller' ),
        'greek' => esc_html__('Greek', 'hoteller' ),
        'greek-ext' => esc_html__('Greek Extended', 'hoteller' ),
        'khmer' => esc_html__('Khmer', 'hoteller' ),
        'latin' => esc_html__('Latin', 'hoteller' ),
        'latin-ext' => esc_html__('Latin Extended', 'hoteller' ),
        'vietnamese' => esc_html__('Vietnamese', 'hoteller' ),
    );

    $args = array(
        'textdomain'   => 'hoteller',
    );

    return $args;

}
add_filter( 'kirki/config', 'hoteller_demo_configuration_sample' );

/**
 * Create the customizer panels and sections
 */
function hoteller_add_panels_and_sections( $wp_customize ) {

	/**
     * Add panels
     */
    $wp_customize->add_panel( 'general', array(
        'priority'    => 35,
        'title'       => esc_html__('General', 'hoteller' ),
    ) ); 
    
    $wp_customize->add_panel( 'menu', array(
        'priority'    => 35,
        'title'       => esc_html__('Navigation', 'hoteller' ),
    ) );
    
    $wp_customize->add_panel( 'header', array(
        'priority'    => 39,
        'title'       => esc_html__('Header', 'hoteller' ),
    ) );
    
    $wp_customize->add_panel( 'sidebar', array(
        'priority'    => 43,
        'title'       => esc_html__('Sidebar', 'hoteller' ),
    ) );
    
    $wp_customize->add_panel( 'footer', array(
        'priority'    => 44,
        'title'       => esc_html__('Footer', 'hoteller' ),
    ) );
    
    $wp_customize->add_panel( 'gallery', array(
        'priority'    => 45,
        'title'       => esc_html__('Gallery', 'hoteller' ),
    ) );
    
    $wp_customize->add_panel( 'blog', array(
        'priority'    => 46,
        'title'       => esc_html__('Blog', 'hoteller' ),
    ) );
    
    $wp_customize->add_panel( 'accommodation', array(
        'priority'    => 47,
        'title'       => esc_html__('Accommodation', 'hoteller' ),
    ) );
    
    //Check if Woocommerce is installed	
	if(class_exists('Woocommerce'))
	{
		$wp_customize->add_panel( 'shop', array(
	        'priority'    => 48,
	        'title'       => esc_html__('Shop', 'hoteller' ),
	    ) );
	}
    
    $wp_customize->add_panel( 'pages', array(
        'priority'    => 49,
        'title'       => esc_html__('Pages', 'hoteller' ),
    ) );

    /**
     * Add sections
     */
	$wp_customize->add_section( 'logo_favicon', array(
        'title'       => esc_html__('Site Logo', 'hoteller' ),
        'priority'    => 34,

    ) );
    
    $wp_customize->add_section( 'general_image', array(
        'title'       => esc_html__('Image', 'hoteller' ),
        'panel'		  => 'general',
        'priority'    => 46,
    ) );
    
    $wp_customize->add_section( 'general_lightbox', array(
        'title'       => esc_html__('Lightbox', 'hoteller' ),
        'panel'		  => 'general',
        'priority'    => 46,

    ) );
    
    $wp_customize->add_section( 'general_fonts', array(
        'title'       => esc_html__('Fonts', 'hoteller' ),
        'panel'		  => 'general',
        'priority'    => 46,

    ) );
    
    $wp_customize->add_section( 'general_typography', array(
        'title'       => esc_html__('Typography', 'hoteller' ),
        'panel'		  => 'general',
        'priority'    => 47,

    ) );
    
    $wp_customize->add_section( 'general_color', array(
        'title'       => esc_html__('Background & Colors', 'hoteller' ),
        'panel'		  => 'general',
        'priority'    => 48,

    ) );
    
    $wp_customize->add_section( 'general_input', array(
        'title'       => esc_html__('Input and Button Elements', 'hoteller' ),
        'panel'		  => 'general',
        'priority'    => 49,

    ) );
    
    /*$wp_customize->add_section( 'general_frame', array(
        'title'       => esc_html__('Frame', 'hoteller' ),
        'panel'		  => 'general',
        'priority'    => 51,
    ) );*/

    $wp_customize->add_section( 'menu_general', array(
        'title'       => esc_html__('General', 'hoteller' ),
        'panel'		  => 'menu',
        'priority'    => 36,

    ) );
    
    $wp_customize->add_section( 'menu_typography', array(
        'title'       => esc_html__('Typography', 'hoteller' ),
        'panel'		  => 'menu',
        'priority'    => 36,

    ) );
    
    $wp_customize->add_section( 'menu_color', array(
        'title'       => esc_html__('Colors', 'hoteller' ),
        'panel'		  => 'menu',
        'priority'    => 37,

    ) );
    
    $wp_customize->add_section( 'menu_submenu', array(
        'title'       => esc_html__('Sub Menu', 'hoteller' ),
        'panel'		  => 'menu',
        'priority'    => 38,

    ) );
    
    $wp_customize->add_section( 'menu_megamenu', array(
        'title'       => esc_html__('Mega Menu', 'hoteller' ),
        'panel'		  => 'menu',
        'priority'    => 38,

    ) );
    
    $wp_customize->add_section( 'menu_topbar', array(
        'title'       => esc_html__('Top Bar', 'hoteller' ),
        'panel'		  => 'menu',
        'priority'    => 38,

    ) );
    
    $wp_customize->add_section( 'menu_contact', array(
        'title'       => esc_html__('Contact Info', 'hoteller' ),
        'panel'		  => 'menu',
        'priority'    => 39,

    ) );
    
    $wp_customize->add_section( 'menu_sidemenu', array(
        'title'       => esc_html__('Side Menu', 'hoteller' ),
        'panel'		  => 'menu',
        'priority'    => 39,

    ) );
    
    $wp_customize->add_section( 'menu_fullmenu', array(
        'title'       => esc_html__('Fullscreen Menu', 'hoteller' ),
        'panel'		  => 'menu',
        'priority'    => 39,

    ) );
    
    $wp_customize->add_section( 'header_background', array(
        'title'       => esc_html__('Background', 'hoteller' ),
        'panel'		  => 'header',
        'priority'    => 40,

    ) );
    
    $wp_customize->add_section( 'header_title', array(
        'title'       => esc_html__('Page Title', 'hoteller' ),
        'panel'		  => 'header',
        'priority'    => 41,

    ) );
    
    $wp_customize->add_section( 'header_builder_title', array(
        'title'       => esc_html__('Content Builder Header', 'hoteller' ),
        'panel'		  => 'header',
        'priority'    => 41,

    ) );
    
    $wp_customize->add_section( 'header_tagline', array(
        'title'       => esc_html__('Page Tagline & Sub Title', 'hoteller' ),
        'panel'		  => 'header',
        'priority'    => 42,

    ) );
    
    $wp_customize->add_section( 'sidebar_general', array(
        'title'       => esc_html__('General', 'hoteller' ),
        'panel'		  => 'sidebar',
        'priority'    => 42,

    ) );
    
    $wp_customize->add_section( 'sidebar_typography', array(
        'title'       => esc_html__('Typography', 'hoteller' ),
        'panel'		  => 'sidebar',
        'priority'    => 43,

    ) );
    
    $wp_customize->add_section( 'sidebar_color', array(
        'title'       => esc_html__('Colors', 'hoteller' ),
        'panel'		  => 'sidebar',
        'priority'    => 44,

    ) );
    
    $wp_customize->add_section( 'footer_general', array(
        'title'       => esc_html__('General', 'hoteller' ),
        'panel'		  => 'footer',
        'priority'    => 45,

    ) );
    
    $wp_customize->add_section( 'footer_color', array(
        'title'       => esc_html__('Colors', 'hoteller' ),
        'panel'		  => 'footer',
        'priority'    => 46,

    ) );
    
    $wp_customize->add_section( 'footer_copyright', array(
        'title'       => esc_html__('Copyright', 'hoteller' ),
        'panel'		  => 'footer',
        'priority'    => 47,

    ) );
    
    $wp_customize->add_section( 'blog_general', array(
        'title'       => esc_html__('General', 'hoteller' ),
        'panel'		  => 'blog',
        'priority'    => 53,

    ) );
    
    $wp_customize->add_section( 'blog_typography', array(
        'title'       => esc_html__('Typography', 'hoteller' ),
        'panel'		  => 'blog',
        'priority'    => 54,

    ) );
    
    $wp_customize->add_section( 'blog_slider', array(
        'title'       => esc_html__('Slider', 'hoteller' ),
        'panel'		  => 'blog',
        'priority'    => 54,

    ) );
    
    $wp_customize->add_section( 'blog_single', array(
        'title'       => esc_html__('Single Post', 'hoteller' ),
        'panel'		  => 'blog',
        'priority'    => 55,

    ) );
    
    $wp_customize->add_section( 'accommodation_single', array(
        'title'       => esc_html__('Single Accommodation', 'hoteller' ),
        'panel'		  => 'accommodation',
        'priority'    => 56,

    ) );
    
    $wp_customize->add_section( 'accommodation_permalink', array(
        'title'       => esc_html__('Permalinks', 'hoteller' ),
        'panel'		  => 'accommodation',
        'priority'    => 56,
    
    ) );
    
    $wp_customize->add_section( 'accommodation_booking_progress', array(
        'title'       => esc_html__('Booking Progress Bar', 'hoteller' ),
        'panel'		  => 'accommodation',
        'priority'    => 57,
    
    ) );
    
    $wp_customize->add_section( 'accommodation_search_results', array(
        'title'       => esc_html__('Search Results', 'hoteller' ),
        'panel'		  => 'accommodation',
        'priority'    => 57,
    
    ) );
    
    //Check if Woocommerce is installed	
	if(class_exists('Woocommerce'))
	{
		$wp_customize->add_section( 'shop_layout', array(
	        'title'       => esc_html__('Layout', 'hoteller' ),
	        'panel'		  => 'shop',
	        'priority'    => 55,
	
	    ) );
	    
	    $wp_customize->add_section( 'shop_single', array(
	        'title'       => esc_html__('Single Product', 'hoteller' ),
	        'panel'		  => 'shop',
	        'priority'    => 56,
	
	    ) );
	}
    
    $wp_customize->add_section( 'pages_templates', array(
        'title'       => esc_html__('Templates', 'hoteller' ),
        'panel'		  => 'pages',
        'priority'    => 57,
    
    ) );

}
add_action( 'customize_register', 'hoteller_add_panels_and_sections' );

/**
 * Register and setting to header section
 */
function hoteller_header_setting( $wp_customize ) {

	//Register Logo Tab Settings
	$wp_customize->add_setting( 'tg_favicon', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'esc_url_raw',
    ) );
	
    $wp_customize->add_setting( 'tg_retina_logo', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    
    $wp_customize->add_setting( 'tg_retina_transparent_logo', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'esc_url_raw',
    ) );

    //End Logo Tab Settings
    
    //Register General Tab Settings
    $wp_customize->add_setting( 'tg_enable_right_click', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'hoteller_sanitize_checkbox',
    ) );
    
    $wp_customize->add_setting( 'tg_enable_dragging', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'hoteller_sanitize_checkbox',
    ) );
    
    $wp_customize->add_setting( 'tg_body_font', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'esc_html',
    ) );
    
    $wp_customize->add_setting( 'tg_body_font_size', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'hoteller_sanitize_slider',
    ) );
    
	$wp_customize->add_setting( 'tg_header_font', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'esc_html',
    ) );
    
    $wp_customize->add_setting( 'tg_header_font_weight', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'hoteller_sanitize_slider',
    ) );
    
    $wp_customize->add_setting( 'tg_h1_size', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'hoteller_sanitize_slider',
    ) );
    
    $wp_customize->add_setting( 'tg_h2_size', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'hoteller_sanitize_slider',
    ) );
    
    $wp_customize->add_setting( 'tg_h3_size', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'hoteller_sanitize_slider',
    ) );
    
    $wp_customize->add_setting( 'tg_h4_size', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'hoteller_sanitize_slider',
    ) );
    
    $wp_customize->add_setting( 'tg_h5_size', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'hoteller_sanitize_slider',
    ) );
    
    $wp_customize->add_setting( 'tg_h6_size', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'hoteller_sanitize_slider',
    ) );
    
    $wp_customize->add_setting( 'tg_content_bg_color', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    
    $wp_customize->add_setting( 'tg_font_color', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    
    $wp_customize->add_setting( 'tg_link_color', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    
    $wp_customize->add_setting( 'tg_hover_link_color', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    
    $wp_customize->add_setting( 'tg_h1_font_color', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    
    $wp_customize->add_setting( 'tg_hr_color', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    
    $wp_customize->add_setting( 'tg_input_bg_color', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    
    $wp_customize->add_setting( 'tg_input_font_color', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    
    $wp_customize->add_setting( 'tg_input_border_color', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    
    $wp_customize->add_setting( 'tg_input_focus_color', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    
    $wp_customize->add_setting( 'tg_button_font', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'esc_html',
    ) );
    
    $wp_customize->add_setting( 'tg_button_bg_color', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    
    $wp_customize->add_setting( 'tg_button_font_color', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    
    $wp_customize->add_setting( 'tg_button_border_color', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    //End General Tab Settings
    

    //Register Menu Tab Settings
    $wp_customize->add_setting( 'tg_menu_layout', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'esc_html',
    ) );
    
    $wp_customize->add_setting( 'tg_fixed_menu', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'hoteller_sanitize_checkbox',
    ) );
    
    $wp_customize->add_setting( 'tg_menu_font', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'esc_html',
    ) );
    
    $wp_customize->add_setting( 'tg_menu_font_size', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'hoteller_sanitize_slider',
    ) );
    
    $wp_customize->add_setting( 'tg_menu_padding', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'hoteller_sanitize_slider',
    ) );
    
    $wp_customize->add_setting( 'tg_menu_weight', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'hoteller_sanitize_slider',
    ) );
    
    $wp_customize->add_setting( 'tg_menu_font_spacing', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'hoteller_sanitize_slider',
    ) );
    
    $wp_customize->add_setting( 'tg_menu_transform', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'esc_html',
    ) );
    
    $wp_customize->add_setting( 'tg_menu_font_color', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    
    $wp_customize->add_setting( 'tg_menu_hover_font_color', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    
    $wp_customize->add_setting( 'tg_menu_active_font_color', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    
    $wp_customize->add_setting( 'tg_menu_border_color', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    
    $wp_customize->add_setting( 'tg_menu_bg', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'esc_html',
    ) );
    
    $wp_customize->add_setting( 'tg_submenu_font_size', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'hoteller_sanitize_slider',
    ) );
    
    $wp_customize->add_setting( 'tg_submenu_weight', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'hoteller_sanitize_slider',
    ) );
    
    $wp_customize->add_setting( 'tg_submenu_font_spacing', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'hoteller_sanitize_slider',
    ) );
    
    $wp_customize->add_setting( 'tg_submenu_transform', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'esc_html',
    ) );
    
    $wp_customize->add_setting( 'tg_submenu_font_color', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    
    $wp_customize->add_setting( 'tg_submenu_hover_font_color', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    
    $wp_customize->add_setting( 'tg_submenu_hover_bg_color', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    
    $wp_customize->add_setting( 'tg_submenu_bg', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    
    $wp_customize->add_setting( 'tg_submenu_border_color', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    
    $wp_customize->add_setting( 'tg_megamenu_header_color', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    
    $wp_customize->add_setting( 'tg_megamenu_border_color', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    
    $wp_customize->add_setting( 'tg_topbar', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'hoteller_sanitize_checkbox',
    ) );
    
    $wp_customize->add_setting( 'tg_topbar_bg', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    
    $wp_customize->add_setting( 'tg_topbar_font_color', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    
    $wp_customize->add_setting( 'tg_topbar_social_link', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'hoteller_sanitize_checkbox',
    ) );
    
    $wp_customize->add_setting( 'tg_menu_contact_hours', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'esc_html',
    ) );
    
    $wp_customize->add_setting( 'tg_menu_contact_number', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'esc_html',
    ) );
    
    $wp_customize->add_setting( 'tg_menu_search', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'hoteller_sanitize_checkbox',
    ) );
    
    $wp_customize->add_setting( 'tg_menu_search_input_color', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    
    $wp_customize->add_setting( 'tg_menu_search_font_color', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    
    $wp_customize->add_setting( 'tg_sidemenu_bg', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    
    $wp_customize->add_setting( 'tg_sidemenu_font', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'esc_html',
    ) );
    
    $wp_customize->add_setting( 'tg_sidemenu_font_size', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'hoteller_sanitize_slider',
    ) );
    
    $wp_customize->add_setting( 'tg_sidemenu_font_transform', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'esc_html',
    ) );
    
    $wp_customize->add_setting( 'tg_sidemenu_font_spacing', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'hoteller_sanitize_slider',
    ) );
    
    $wp_customize->add_setting( 'tg_sidemenu_font_color', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    
    $wp_customize->add_setting( 'tg_sidemenu_font_hover_color', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    //End Menu Tab Settings
    
    //Register Header Tab Settings
	$wp_customize->add_setting( 'tg_page_header_bg_color', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    
    $wp_customize->add_setting( 'tg_page_header_padding_top', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'hoteller_sanitize_slider',
    ) );
    
    $wp_customize->add_setting( 'tg_page_header_padding_bottom', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'hoteller_sanitize_slider',
    ) );
    
    $wp_customize->add_setting( 'tg_page_title_font_color', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    
    $wp_customize->add_setting( 'tg_page_title_font_size', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'hoteller_sanitize_slider',
    ) );
    
    $wp_customize->add_setting( 'tg_page_title_font_weight', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'hoteller_sanitize_slider',
    ) );
    
    $wp_customize->add_setting( 'tg_page_title_font_spacing', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'hoteller_sanitize_slider',
    ) );
    
    $wp_customize->add_setting( 'tg_page_title_bg_height', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'hoteller_sanitize_slider',
    ) );
    
    $wp_customize->add_setting( 'tg_header_builder_font_size', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'hoteller_sanitize_slider',
    ) );
    
    $wp_customize->add_setting( 'tg_header_builder_font_transform', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'esc_html',
    ) );
    //End Header Tab Settings
    
    //Register Copyright Tab Settings
    
    $wp_customize->add_setting( 'hoteller', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'esc_html',
    ) );
	
	$wp_customize->add_setting( 'tg_footer_social_link', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'hoteller_sanitize_checkbox',
    ) );
    
    $wp_customize->add_setting( 'tg_footer_bg', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'esc_html',
    ) );
    
	$wp_customize->add_setting( 'tg_footer_font_color', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    
    $wp_customize->add_setting( 'tg_footer_link_color', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    
    $wp_customize->add_setting( 'tg_footer_hover_link_color', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    
    $wp_customize->add_setting( 'tg_footer_border_color', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    
    $wp_customize->add_setting( 'tg_footer_social_color', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    
    $wp_customize->add_setting( 'tg_footer_copyright_text', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'hoteller_sanitize_html',
    ) );
    
    $wp_customize->add_setting( 'tg_footer_copyright_right_area', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'esc_html',
    ) );
    
    $wp_customize->add_setting( 'tg_footer_copyright_totop', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'hoteller_sanitize_checkbox',
    ) );
    //End Copyright Tab Settings
    
    
    //Begin Portfolio Tab Settings
    $wp_customize->add_setting( 'tg_portfolio_filterable', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'hoteller_sanitize_checkbox',
    ) );
    
    $wp_customize->add_setting( 'tg_portfolio_filterable_link', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'hoteller_sanitize_checkbox',
    ) );
    
    $wp_customize->add_setting( 'tg_portfolio_filterable_sort', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'esc_html',
    ) );
    
    $wp_customize->add_setting( 'tg_portfolio_items', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'hoteller_sanitize_slider',
    ) );
    
    $wp_customize->add_setting( 'tg_portfolio_next_prev', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'hoteller_sanitize_checkbox',
    ) );
    
    $wp_customize->add_setting( 'tg_portfolio_recent', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'hoteller_sanitize_checkbox',
    ) );
    //End Portfolio Tab Settings
    
    
    //Begin Blog Tab Settings
    $wp_customize->add_setting( 'tg_blog_display_full', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'hoteller_sanitize_checkbox',
    ) );
    
    $wp_customize->add_setting( 'tg_blog_archive_layout', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'esc_html',
    ) );
    
    $wp_customize->add_setting( 'tg_blog_category_layout', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'esc_html',
    ) );
    
    $wp_customize->add_setting( 'tg_blog_tag_layout', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'esc_html',
    ) );
    
    $wp_customize->add_setting( 'tg_blog_header_bg', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'hoteller_sanitize_checkbox',
    ) );
    
    $wp_customize->add_setting( 'tg_blog_feat_content', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'hoteller_sanitize_checkbox',
    ) );
    
    $wp_customize->add_setting( 'tg_blog_display_tags', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'hoteller_sanitize_checkbox',
    ) );
    
    $wp_customize->add_setting( 'tg_blog_display_author', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'hoteller_sanitize_checkbox',
    ) );
    
    $wp_customize->add_setting( 'tg_blog_display_related', array(
        'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'hoteller_sanitize_checkbox',
    ) );
    //End Blog Tab Settings
    
    
    //Check if Woocommerce is installed	
	if(class_exists('Woocommerce'))
	{
		//Begin Shop Tab Settings
		$wp_customize->add_setting( 'tg_shop_layout', array(
	        'type'           => 'theme_mod',
	        'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'esc_html',
	    ) );
	    
	    $wp_customize->add_setting( 'tg_shop_items', array(
	        'type'           => 'theme_mod',
	        'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'hoteller_sanitize_slider',
	    ) );
	    
	    $wp_customize->add_setting( 'tg_shop_price_font_color', array(
	        'type'           => 'theme_mod',
	        'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_hex_color',
	    ) );
	    
	    $wp_customize->add_setting( 'tg_shop_related_products', array(
	        'type'           => 'theme_mod',
	        'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'hoteller_sanitize_checkbox',
	    ) );
		//End Shop Tab Settings
	}
    
    
    //Add Live preview
    if ( $wp_customize->is_preview() && ! is_admin() ) {
	    add_action( 'wp_footer', 'hoteller_customize_preview', 21);
	}
}
add_action( 'customize_register', 'hoteller_header_setting' );

/**
 * Create the setting
 */
function hoteller_custom_setting( $controls ) {

	//Default control choices
	$tg_text_transform = array(
	    'none' => 'None',
	    'capitalize' => 'Capitalize',
	    'uppercase' => 'Uppercase',
	    'lowercase' => 'Lowercase',
	);
	
	$tg_text_alignment = array(
	    'left' => 'Left',
	    'center' => 'Center',
	    'right' => 'Right',
	);
	
	$tg_font_style = array(
	    'normal' => 'Nomal',
	    'italic' => 'Italic',
	    'oblique' => 'oblique',
	);
	
	$tg_copyright_content = array(
	    'social' => 'Social Icons',
	    'menu' => 'Footer Menu',
	);
	
	$tg_copyright_column = array(
	    '' => 'Hide Footer Sidebar',
	    1 => '1 Column',
	    2 => '2 Column',
	    3 => '3 Column',
	    4 => '4 Column',
	);
	
	$tg_footer_content = array(
	    'content' => 'Footer Content',
	    'sidebar' => 'Sidebar',
	    'hide' => 'Hide Footer Content',
	);
	
	$tg_header_content = array(
	    'content' => 'Header Content',
	    'menu' => 'General Menu Layout',
	);
	
	$tg_portfolio_filterable_sort = array(
		'name' => 'By Name',
		'slug' => 'By Slug',
		'id' => 'By ID',
		'count' => 'By Number of Portfolio',
	);
	
	$tg_blog_layout = array(
		'blog-r' => 'Right Sidebar',
		'blog-l' => 'Left Sidebar',
		'blog-f' => 'Fullwidth',
	);
	
	$tg_shop_layout = array(
		'fullwidth' => 'Fullwidth',
		'sidebar' => 'With Sidebar',
	);
	
	$tg_other_room_layout = array(
		1 => 'Style 1',
		2 => 'Style 2',
	);
    
    $tg_search_results_layout = array(
        'classic' => 'Classic',
        'list' => 'List View',
        'grid' => 'Grid View',
    );
	
	$tg_slideshow_trans = array(
	    1 => 'Fade',
	    2 => 'Slide Top',
	    3 => 'Slide Right',
	    4 => 'Slide Bottom',
	    5 => 'Slide Left',
	    6 => 'Carousel Right',
	    7 => 'Carousel Left',
	);
	
	$tg_menu_layout = array(
	    'leftalign' => 'Left Align',
	    'centeralign' => 'Center Align + Menu Below Logo',
	    'centeralign2' => 'Center Align + Top Left Menu',
	    'centeralign3' => 'Center Align + Top Right Menu',
	    'hammenuside' => 'Hamburger Menu + Side Menu',
	    'hammenufull' => 'Hamburger Menu + Fullscreen Menu',
	    'leftmenu' => 'Left Vetical',
	);
	
	$tg_lightbox_skin = array(
		'white' => 'White',
		'black' => 'Black',
	);
	
	$tg_lightbox_thumbnails = array(
		'no_thumbnail' => 'No Thumbnail',
		'thumbnail' => 'Show Thumbnail',
	);
    
    $tg_input_style = array(
        'classic' => 'Classic Style',
        'paper' => 'Paper Style',
    );
	
	//Get all categories
	$categories_arr = get_categories();
	$tg_categories_select = array();
	$tg_categories_select[''] = '';
	
	foreach ($categories_arr as $cat) {
		$tg_categories_select[$cat->cat_ID] = $cat->cat_name;
	}
	
	//Get all gallery categories
	$gallery_categories_arr = get_terms('gallerycat', 'hide_empty=0&hierarchical=0&parent=0&orderby=name');
	$tg_gallery_categories_select = array();
	$tg_gallery_categories_select[''] = '';
	
	if(!empty($gallery_categories_arr) && is_array($gallery_categories_arr))
	{
		foreach ($gallery_categories_arr as $gallery_cat) {
			$tg_gallery_categories_select[$gallery_cat->slug] = $gallery_cat->name;
		}
	}
	
	//Get all footer posts
	$args = array(
		 'post_type'     => 'footer',
		 'post_status'   => array( 'publish' ),
		 'numberposts'   => -1,
		 'orderby'       => 'title',
		 'order'         => 'ASC',
		 'suppress_filters'   => false
	);
	$footers = get_posts($args);
	$tg_footers_select = array();
	$tg_footers_select[''] = '';
	
	if(!empty($footers))
	{
		foreach ($footers as $footer)
		{
			$tg_footers_select[$footer->ID] = $footer->post_title;
		}
	}
	
	//Get all header posts
	$args = array(
		 'post_type'     => 'header',
		 'post_status'   => array( 'publish' ),
		 'numberposts'   => -1,
		 'orderby'       => 'title',
		 'order'         => 'ASC',
		 'suppress_filters'   => false
	);
	$headers = get_posts($args);
	$tg_headers_select = array();
	$tg_headers_select[''] = '';
	
	if(!empty($headers))
	{
		foreach ($headers as $header)
		{
			$tg_headers_select[$header->ID] = $header->post_title;
		}
	}
    
    //Get all fullscreen menus
    $args = array(
        'numberposts' => -1,
        'post_type' => array('fullmenu'),
        'post_status'   => array( 'publish' ),
        'orderby'   => 'post_title',
        'order'     => 'ASC',
        'suppress_filters'   => false
    );
    
    $hoteller_fullmenu_arr = get_posts($args);
    $hoteller_fullmenu_select = array();
    $hoteller_fullmenu_select[''] = '';
    
    foreach($hoteller_fullmenu_arr as $hoteller_fullmenu)
    {
        $hoteller_fullmenu_select[$hoteller_fullmenu->ID] = $hoteller_fullmenu->post_title;
    }
    
    $hoteller_fullmenu_effect_select = array(
        ''              => esc_html__('Scale Up', 'hoteller' ),
        'scale-down'    => esc_html__('Scale Down', 'hoteller' ),
        'move-down'     => esc_html__('Move Down', 'hoteller' ),
        'move-up'       => esc_html__('Move Up', 'hoteller' ),
        'fade'          => esc_html__('Fade In', 'hoteller' ),
    );
	
	//Register Logo Tab Settings
	$controls[] = array(
        'type'     => 'image',
        'settings'  => 'tg_retina_logo',
        'label'    => esc_html__('Retina Logo', 'hoteller' ),
        'description' => esc_html__('Retina Ready Image logo. It should be 2x size of normal logo. For example 200x60px logo will displays at 100x30px', 'hoteller' ),
        'section'  => 'logo_favicon',
	    'default'  => '',
	    'priority' => 2,
    );
    
    $controls[] = array(
        'type'     => 'image',
        'settings'  => 'tg_retina_transparent_logo',
        'label'    => esc_html__('Retina Transparent Logo', 'hoteller' ),
        'description' => esc_html__('Retina Ready Image logo for menu transparent page. It should be 2x size of normal logo. For example 200x60px logo will displays at 100x30px. Recommend logo color is white or bright color', 'hoteller' ),
        'section'  => 'logo_favicon',
	    'default'  => '',
	    'priority' => 3,
    );
    
    $controls[] = array(
        'type'     => 'slider',
        'settings'  => 'tg_logo_margin_top',
        'label'    => esc_html__('Logo Margin Top (in px)', 'hoteller' ),
        'section'  => 'logo_favicon',
        'default'  => 10,
        'choices' => array( 'min' => 0, 'max' => 150, 'step' => 1 ),
        'output' => array(
	        array(
	            'element'  => '.header_style_wrapper #logo_normal .logo_wrapper, .header_style_wrapper #logo_transparent .logo_wrapper',
	            'property' => 'margin-top',
	            'units'    => 'px',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 3,
	    'js_vars'   => array(
			array(
				'element'  => '.header_style_wrapper #logo_normal .logo_wrapper, .header_style_wrapper #logo_transparent .logo_wrapper',
				'function' => 'css',
				'property' => 'margin-top',
				'units'    => 'px',
			),
		)
    );
    
    $controls[] = array(
        'type'     => 'toggle',
        'settings'  => 'tg_retina_logo_for_admin',
        'label'    => esc_html__('Display Retina Logo in Theme Setting', 'hoteller' ),
        'description' => esc_html__('Check this to replace theme setting to your logo. It helps branding your site', 'hoteller' ),
        'section'  => 'logo_favicon',
        'default'  => '',
	    'priority' => 4,
    );
    //End Logo Tab Settings
    
    //Register General Tab Settings
    $controls[] = array(
        'type'     => 'title',
        'settings'  => 'tg_enable_right_click_title',
        'label'    => esc_html__('Right Click Protection Settings', 'hoteller' ),
        'section'  => 'general_image',
	    'priority' => 1,
    );
    
    $controls[] = array(
        'type'     => 'toggle',
        'settings'  => 'tg_enable_right_click',
        'label'    => esc_html__('Enable Right Click Protection', 'hoteller' ),
        'description' => esc_html__('Check this to disable right click.', 'hoteller' ),
        'section'  => 'general_image',
        'default'  => '',
	    'priority' => 2,
    );
    
    $controls[] = array(
        'type'     => 'toggle',
        'settings'  => 'tg_enable_right_click_content',
        'label'    => esc_html__('Enable Right Click Protection Content', 'hoteller' ),
        'description' => esc_html__('Check this to enable fullscreen content when visitor tried to right click', 'hoteller' ),
        'section'  => 'general_image',
        'default'  => '',
	    'priority' => 2,
    );
    
    $controls[] = array(
        'type'     => 'textarea',
        'settings'  => 'tg_enable_right_click_content_text',
        'label'    => esc_html__('Right Click Protection Content', 'hoteller' ),
        'description' => '',
        'section'  => 'general_image',
        'default'  => '',
	    'priority' => 2,
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_enable_right_click_content_bg_color',
        'label'    => esc_html__('Right Click Protection Content Background Color', 'hoteller' ),
        'section'  => 'general_image',
        'default'  => 'rgba(0, 0, 0, 0.5)',
        'output' => array(
	        array(
	            'element'  => '#right_click_content',
	            'property' => 'background',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 2,
	    'js_vars'   => array(
			array(
				'element'  => '#right_click_content',
				'function' => 'css',
				'property' => 'background',
			),
		)
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_enable_right_click_content_font_color',
        'label'    => esc_html__('Right Click Protection Content Font Color', 'hoteller' ),
        'section'  => 'general_image',
        'default'  => '#ffffff',
        'output' => array(
	        array(
	            'element'  => '#right_click_content',
	            'property' => 'color',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 2,
	    'js_vars'   => array(
			array(
				'element'  => '#right_click_content',
				'function' => 'css',
				'property' => 'color',
			),
		)
    );
    
    $controls[] = array(
        'type'     => 'title',
        'settings'  => 'tg_image_other_title',
        'label'    => esc_html__('Other Settings', 'hoteller' ),
        'section'  => 'general_image',
	    'priority' => 3,
    );
    
    $controls[] = array(
        'type'     => 'toggle',
        'settings'  => 'tg_enable_dragging',
        'label'    => esc_html__('Enable Image Dragging Protection', 'hoteller' ),
        'description' => esc_html__('Check this to disable dragging on all images.', 'hoteller' ),
        'section'  => 'general_image',
        'default'  => '',
	    'priority' => 4,
    );
    
    $controls[] = array(
        'type'     => 'toggle',
        'settings'  => 'tg_enable_lazy_loading',
        'label'    => esc_html__('Enable Lazy Loading Image', 'hoteller' ),
        'description' => esc_html__('Check this to enable lazy loading image option to improve site loading speed.', 'hoteller' ),
        'section'  => 'general_image',
        'default'  => 1,
	    'priority' => 4,
    );
    
    $controls[] = array(
        'type'     => 'radio-buttonset',
        'settings'  => 'tg_lightbox_color_scheme',
        'label'    => esc_html__('Select lightbox color scheme', 'hoteller' ),
        'description' => esc_html__('Select which alignment you want to use for lightbox thumbnails', 'hoteller' ),
        'section'  => 'general_lightbox',
        'default'  => 'black',
        'choices'  => $tg_lightbox_skin,
        'priority' => 1,
    );
    
    $controls[] = array(
        'type'     => 'radio-buttonset',
        'settings'  => 'tg_lightbox_thumbnails',
        'label'    => esc_html__('Select lightbox thumbnails alignment', 'hoteller' ),
        'description' => esc_html__('Select which alignment you want to use for lightbox thumbnails', 'hoteller' ),
        'section'  => 'general_lightbox',
        'default'  => 'thumbnail',
        'choices'  => $tg_lightbox_thumbnails,
        'priority' => 1,
    );
    
    $controls[] = array(
        'type'     => 'slider',
        'settings'  => 'tg_lightbox_timer',
        'label'    => esc_html__('Lightbox slideshow timer', 'hoteller' ),
        'description' => esc_html__('Select number of seconds for lightbox slideshow timer', 'hoteller' ),
        'section'  => 'general_lightbox',
        'default'  => 7,
        'choices' => array( 'min' => 1, 'max' => 20, 'step' => 1 ),
        'priority' => 2,
    );
    
    $controls[] = array(
        'type'     => 'title',
        'settings'  => 'tg_body_typography_title',
        'label'    => esc_html__('Body and Content Settings', 'hoteller' ),
        'section'  => 'general_typography',
	    'priority' => 1,
    );
    
    $controls[] = array(
        'type'     => 'typography',
        'settings'  => 'tg_body_typography',
        'label'    => esc_html__('Main Content Typography', 'hoteller' ),
        'section'  => 'general_typography',
        'default'  => array(
            'font-family'    => 'Jost',
            'variant'        => '500',
            'font-size'      => '15px',
            'line-height'    => '1.8',
            'letter-spacing' => '0',
            'text-transform' => 'none',
        ),
        'output' => array(
            array(
                'element'  => 'body, input[type=text], input[type=password], input[type=email], input[type=url], input[type=number], input[type=date], input[type=tel], input.wpcf7-text, .woocommerce table.cart td.actions .coupon .input-text, .woocommerce-page table.cart td.actions .coupon .input-text, .woocommerce #content table.cart td.actions .coupon .input-text, .woocommerce-page #content table.cart td.actions .coupon .input-text, select, input[type=submit], input[type=button], a.button, .button, body .ui-dialog[aria-describedby="ajax-login-register-login-dialog"] .form-wrapper input[type="submit"], body .ui-dialog[aria-describedby="ajax-login-register-dialog"] .form-wrapper input[type="submit"]',
            ),
        ),
        'priority' => 2,
    );
    
    $controls[] = array(
        'type'     => 'title',
        'settings'  => 'tg_header_typography_title',
        'label'    => esc_html__('Header Settings', 'hoteller' ),
        'section'  => 'general_typography',
	    'priority' => 3,
    );
    
    $controls[] = array(
        'type'     => 'typography',
        'settings'  => 'tg_header_typography',
        'label'    => esc_html__('Header Typography', 'hoteller' ),
        'section'  => 'general_typography',
        'default'  => array(
            'font-family'    => 'Oswald',
            'variant'        => '400',
            'line-height'    => '1.3',
            'letter-spacing' => '0',
            'text-transform' => 'none',
        ),
        'output' => array(
            array(
                'element'  => 'h1, h2, h3, h4, h5, h6, h7, .post_quote_title, strong[itemprop="author"], #page_content_wrapper .posts.blog li a, .page_content_wrapper .posts.blog li a, #filter_selected, blockquote, .sidebar_widget li.widget_products, #footer ul.sidebar_widget li ul.posts.blog li a, .woocommerce-page table.cart th, table.shop_table thead tr th, .testimonial_slider_content, .pagination, .pagination_detail, .pagination_detail, .comment-author cite',
            ),
        ),
        'priority' => 4
    );
    
    $controls[] = array(
        'type'     => 'select',
        'settings'  => 'tg_header_font_style',
        'label'    => esc_html__('Header Font Font Style', 'hoteller' ),
        'section'  => 'general_typography',
        'default'  => 'normal',
        'choices'  => $tg_font_style,
        'output' => array(
	        array(
	            'element'  => 'h1, h2, h3, h4, h5, h6, h7, .pagination_detail, .comment-author cite',
	            'property' => 'font-style',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 4,
	    'js_vars'   => array(
			array(
				'element'  => 'h1, h2, h3, h4, h5, h6, h7, .pagination_detail, .comment-author cite',
				'function' => 'css',
				'property' => 'font-style',
			),
		)
    );
    
    $controls[] = array(
        'type'     => 'slider',
        'settings'  => 'tg_h1_size',
        'label'    => esc_html__('H1 Font Size', 'hoteller' ),
        'section'  => 'general_typography',
        'default'  => 34,
        'choices' => array( 'min' => 13, 'max' => 60, 'step' => 1 ),
        'output' => array(
	        array(
	            'element'  => 'h1',
	            'property' => 'font-size',
	            'units'    => 'px',
	        ),
	    ),
	    'priority' => 4,
	    'js_vars'   => array(
			array(
				'element'  => 'h1',
				'function' => 'css',
				'property' => 'font-size',
				'units'    => 'px',
			),
		)
    );
    
    $controls[] = array(
        'type'     => 'slider',
        'settings'  => 'tg_h2_size',
        'label'    => esc_html__('H2 Font Size', 'hoteller' ),
        'section'  => 'general_typography',
        'default'  => 28,
        'choices' => array( 'min' => 13, 'max' => 60, 'step' => 1 ),
        'output' => array(
	        array(
	            'element'  => 'h2',
	            'property' => 'font-size',
	            'units'    => 'px',
	        ),
	    ),
	    'priority' => 4,
	    'js_vars'   => array(
			array(
				'element'  => 'h2',
				'function' => 'css',
				'property' => 'font-size',
				'units'    => 'px',
			),
		)
    );
    
    $controls[] = array(
        'type'     => 'slider',
        'settings'  => 'tg_h3_size',
        'label'    => esc_html__('H3 Font Size', 'hoteller' ),
        'section'  => 'general_typography',
        'default'  => 24,
        'choices' => array( 'min' => 13, 'max' => 60, 'step' => 1 ),
        'output' => array(
	        array(
	            'element'  => 'h3',
	            'property' => 'font-size',
	            'units'    => 'px',
	        ),
	    ),
	    'priority' => 5,
	    'js_vars'   => array(
			array(
				'element'  => 'h3',
				'function' => 'css',
				'property' => 'font-size',
				'units'    => 'px',
			),
		)
    );
    
    $controls[] = array(
        'type'     => 'slider',
        'settings'  => 'tg_h4_size',
        'label'    => esc_html__('H4 Font Size', 'hoteller' ),
        'section'  => 'general_typography',
        'default'  => 20,
        'choices' => array( 'min' => 13, 'max' => 60, 'step' => 1 ),
        'output' => array(
	        array(
	            'element'  => 'h4',
	            'property' => 'font-size',
	            'units'    => 'px',
	        ),
	    ),
	    'priority' => 6,
	    'js_vars'   => array(
			array(
				'element'  => 'h4',
				'function' => 'css',
				'property' => 'font-size',
				'units'    => 'px',
			),
		)
    );
    
    $controls[] = array(
        'type'     => 'slider',
        'settings'  => 'tg_h5_size',
        'label'    => esc_html__('H5 Font Size', 'hoteller' ),
        'section'  => 'general_typography',
        'default'  => 18,
        'choices' => array( 'min' => 13, 'max' => 60, 'step' => 1 ),
        'output' => array(
	        array(
	            'element'  => 'h5',
	            'property' => 'font-size',
	            'units'    => 'px',
	        ),
	    ),
	    'priority' => 7,
	    'js_vars'   => array(
			array(
				'element'  => 'h5',
				'function' => 'css',
				'property' => 'font-size',
				'units'    => 'px',
			),
		)
    );
    
    $controls[] = array(
        'type'     => 'slider',
        'settings'  => 'tg_h6_size',
        'label'    => esc_html__('H6 Font Size', 'hoteller' ),
        'section'  => 'general_typography',
        'default'  => 16,
        'choices' => array( 'min' => 13, 'max' => 60, 'step' => 1 ),
        'output' => array(
	        array(
	            'element'  => 'h6',
	            'property' => 'font-size',
	            'units'    => 'px',
	        ),
	    ),
	    'priority' => 8,
	    'js_vars'   => array(
			array(
				'element'  => 'h6',
				'function' => 'css',
				'property' => 'font-size',
				'units'    => 'px',
			),
		)
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_content_bg_color',
        'label'    => esc_html__('Main Content Background Color', 'hoteller' ),
        'section'  => 'general_color',
        'default'  => '#f9f9f9',
        'output' => array(
	        array(
	            'element'  => 'body, #wrapper, #page_content_wrapper.fixed, #gallery_lightbox h2, .slider_wrapper .gallery_image_caption h2, #body_loading_screen, h3#reply-title span, .overlay_gallery_wrapper, .pagination a, .pagination span, #captcha-wrap .text-box input, .flex-direction-nav a, .blog_promo_title h6, #supersized li, #horizontal_gallery_wrapper .image_caption, body.tg_password_protected #page_content_wrapper .inner .inner_wrapper .sidebar_content, body .ui-dialog[aria-describedby="ajax-login-register-login-dialog"], body .ui-dialog[aria-describedby="ajax-login-register-dialog"]',
	            'property' => 'background-color',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 9,
	    'js_vars'   => array(
			array(
				'element'  => 'body, #wrapper, #page_content_wrapper.fixed, #gallery_lightbox h2, .slider_wrapper .gallery_image_caption h2, #body_loading_screen, h3#reply-title span, .overlay_gallery_wrapper, .pagination a, .pagination span, #captcha-wrap .text-box input, .flex-direction-nav a, .blog_promo_title h6, #supersized li, #horizontal_gallery_wrapper .image_caption, body.tg_password_protected #page_content_wrapper .inner .inner_wrapper .sidebar_content, body .ui-dialog[aria-describedby="ajax-login-register-login-dialog"], body .ui-dialog[aria-describedby="ajax-login-register-dialog"]',
				'function' => 'css',
				'property' => 'background-color',
			),
		)
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_boxed_content_bg_color',
        'label'    => esc_html__('Boxed Content Background Color', 'hoteller' ),
        'section'  => 'general_color',
        'default'  => '#f9f9f9',
        'output' => array(
	        array(
	            'element'  => 'form.mphb_sc_checkout-form .mphb-reserve-rooms-details, #page_content_wrapper .sidebar .content .sidebar_widget li.widget.widget_mphb_search_availability_widget',
	            'property' => 'background-color',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 9,
	    'js_vars'   => array(
			array(
				'element'  => 'form.mphb_sc_checkout-form .mphb-reserve-rooms-details, #page_content_wrapper .sidebar .content .sidebar_widget li.widget.widget_mphb_search_availability_widget',
				'function' => 'css',
				'property' => 'background-color',
			),
		)
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_boxed_content_border_color',
        'label'    => esc_html__('Boxed Content Border Color', 'hoteller' ),
        'section'  => 'general_color',
        'default'  => '#f9f9f9',
        'output' => array(
            array(
                'element'  => 'form.mphb_sc_checkout-form .mphb-reserve-rooms-details, #page_content_wrapper .sidebar .content .sidebar_widget li.widget.widget_mphb_search_availability_widget',
                'property' => 'border-color',
            ),
        ),
        'transport' 	 => 'postMessage',
        'priority' => 9,
        'js_vars'   => array(
            array(
                'element'  => 'form.mphb_sc_checkout-form .mphb-reserve-rooms-details, #page_content_wrapper .sidebar .content .sidebar_widget li.widget.widget_mphb_search_availability_widget',
                'function' => 'css',
                'property' => 'border-color',
            ),
        )
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_font_color',
        'label'    => esc_html__('Page Content Font Color', 'hoteller' ),
        'section'  => 'general_color',
        'default'  => '#222222',
        'output' => array(
	        array(
	            'element'  => 'body, .pagination a, #gallery_lightbox h2, .slider_wrapper .gallery_image_caption h2, .post_info a, #page_content_wrapper.split #copyright, .page_content_wrapper.split #copyright, .ui-state-default a, .ui-state-default a:link, .ui-state-default a:visited, .readmore, .woocommerce-MyAccount-navigation ul a, .mphb-attribute-value a',
	            'property' => 'color',
	        ),
	        array(
	            'element'  => '::selection',
	            'property' => 'background-color',
	        ),
	        array(
	            'element'  => '::-webkit-input-placeholder',
	            'property' => 'color',
	        ),
	        array(
	            'element'  => '::-moz-placeholder',
	            'property' => 'color',
	        ),
	        array(
	            'element'  => ':-ms-input-placeholder',
	            'property' => 'color',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 11,
	    'js_vars'   => array(
			array(
				'element'  => 'body, .pagination a, #gallery_lightbox h2, .slider_wrapper .gallery_image_caption h2, .post_info a, #page_content_wrapper.split #copyright, .page_content_wrapper.split #copyright, .ui-state-default a, .ui-state-default a:link, .ui-state-default a:visited, .readmore, .woocommerce-MyAccount-navigation ul a, .mphb-attribute-value a',
				'function' => 'css',
				'property' => 'color',
			),
			array(
				'element'  => '::-webkit-input-placeholder',
				'function' => 'css',
				'property' => 'background-color',
			),
			array(
				'element'  => '::-moz-placeholder',
				'function' => 'css',
				'property' => 'background-color',
			),
			array(
				'element'  => ':-ms-input-placeholder',
				'function' => 'css',
				'property' => 'background-color',
			),
		)
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_link_color',
        'label'    => esc_html__('Page Content Link Color', 'hoteller' ),
        'section'  => 'general_color',
        'default'  => '#222222',
        'output' => array(
	        array(
	            'element'  => 'a, .gallery_proof_filter ul li a',
	            'property' => 'color',
	        ),
	         array(
	            'element'  => '.flex-control-paging li a.flex-active, .post_attribute a:before, #menu_wrapper .nav ul li a:before, #menu_wrapper div .nav li > a:before, .post_attribute a:before',
	            'property' => 'background-color',
	        ),
	         array(
	            'element'  => '.flex-control-paging li a.flex-active, .image_boxed_wrapper:hover, .gallery_proof_filter ul li a.active, .gallery_proof_filter ul li a:hover',
	            'property' => 'border-color',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 12,
	    'js_vars'   => array(
			array(
	            'element'  => 'a, .gallery_proof_filter ul li a',
	            'property' => 'color',
	        ),
	         array(
	            'element'  => '.flex-control-paging li a.flex-active, .post_attribute a:before, #menu_wrapper .nav ul li a:before, #menu_wrapper div .nav li > a:before, .post_attribute a:before',
	            'property' => 'background-color',
	        ),
	         array(
	            'element'  => '.flex-control-paging li a.flex-active, .image_boxed_wrapper:hover, .gallery_proof_filter ul li a.active, .gallery_proof_filter ul li a:hover',
	            'property' => 'border-color',
	        ),
		)
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_hover_link_color',
        'label'    => esc_html__('Page Content Hover Link Color', 'hoteller' ),
        'section'  => 'general_color',
        'default'  => '#222222',
        'output' => array(
	        array(
	            'element'  => 'a:hover, a:active, .post_info_comment a i, .woocommerce div.product .woocommerce-tabs ul.tabs li a:hover',
	            'property' => 'color',
	        ),
	        array(
	            'element'  => 'input[type=button]:hover, input[type=submit]:hover, a.button:hover, .button:hover, .button.submit, a.button.white:hover, .button.white:hover, a.button.white:active, .button.white:active',
	            'property' => 'background',
	        ),
	        array(
	            'element'  => 'input[type=button]:hover, input[type=submit]:hover, a.button:hover, .button:hover, .button.submit, a.button.white:hover, .button.white:hover, a.button.white:active, .button.white:active',
	            'property' => 'border-color',
	        ),
	    ),
	    'js_vars'   => array(
			array(
	            'element'  => 'a:hover, a:active, .post_info_comment a i, .woocommerce div.product .woocommerce-tabs ul.tabs li a:hover',
	            'property' => 'color',
	        ),
	        array(
	            'element'  => 'input[type=button]:hover, input[type=submit]:hover, a.button:hover, .button:hover, .button.submit, a.button.white:hover, .button.white:hover, a.button.white:active, .button.white:active',
	            'property' => 'background',
	        ),
	        array(
	            'element'  => 'input[type=button]:hover, input[type=submit]:hover, a.button:hover, .button:hover, .button.submit, a.button.white:hover, .button.white:hover, a.button.white:active, .button.white:active',
	            'property' => 'border-color',
	        ),
		),
	    'transport' 	 => 'postMessage',
	    'priority' => 13,
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_h1_font_color',
        'label'    => esc_html__('Header Font Color', 'hoteller' ),
        'section'  => 'general_color',
        'default'  => '#333333',
        'output' => array(
	        array(
	            'element'  => 'h1, h2, h3, h4, h5, h6, h7, pre, code, tt, blockquote, .post_header h5 a, .post_header h3 a, .post_header.grid h6 a, .post_header.fullwidth h4 a, .post_header h5 a, blockquote, .site_loading_logo_item i, .ppb_subtitle, .woocommerce .woocommerce-ordering select, .woocommerce #page_content_wrapper a.button, .woocommerce.columns-4 ul.products li.product a.add_to_cart_button, .woocommerce.columns-4 ul.products li.product a.add_to_cart_button:hover, .ui-accordion .ui-accordion-header a, .tabs .ui-state-active a, body.woocommerce div.product .woocommerce-tabs ul.tabs li.active a, body.woocommerce-page div.product .woocommerce-tabs ul.tabs li.active a, body.woocommerce #content div.product .woocommerce-tabs ul.tabs li.active a, body.woocommerce-page #content div.product .woocommerce-tabs ul.tabs li.active a, .woocommerce div.product .woocommerce-tabs ul.tabs li a, .post_header h5 a, .post_header h6 a, .flex-direction-nav a:before, .social_share_button_wrapper .social_post_view .view_number, .social_share_button_wrapper .social_post_share_count .share_number, .portfolio_post_previous a, .portfolio_post_next a, #filter_selected, #autocomplete li strong, .themelink, body .ui-dialog[aria-describedby="ajax-login-register-login-dialog"] .ui-dialog-titlebar .ui-dialog-title, body .ui-dialog[aria-describedby="ajax-login-register-dialog"] .ui-dialog-titlebar .ui-dialog-title',
	            'property' => 'color',
	        ),
	        array(
	            'element'  => 'body.page.page-template-gallery-archive-split-screen-php #fp-nav li .active span, body.tax-gallerycat #fp-nav li .active span, body.page.page-template-portfolio-fullscreen-split-screen-php #fp-nav li .active span, body.page.tax-portfolioset #fp-nav li .active span, body.page.page-template-gallery-archive-split-screen-php #fp-nav ul li a span, body.tax-gallerycat #fp-nav ul li a span, body.page.page-template-portfolio-fullscreen-split-screen-php #fp-nav ul li a span, body.page.tax-portfolioset #fp-nav ul li a span',
	            'property' => 'background-color',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 14,
	    'js_vars'   => array(
			array(
				'element'  => 'h1, h2, h3, h4, h5, h6, h7, pre, code, tt, blockquote, .post_header h5 a, .post_header h3 a, .post_header.grid h6 a, .post_header.fullwidth h4 a, .post_header h5 a, blockquote, .site_loading_logo_item i, .ppb_subtitle, .woocommerce .woocommerce-ordering select, .woocommerce #page_content_wrapper a.button, .woocommerce.columns-4 ul.products li.product a.add_to_cart_button, .woocommerce.columns-4 ul.products li.product a.add_to_cart_button:hover, .ui-accordion .ui-accordion-header a, .tabs .ui-state-active a, body.woocommerce div.product .woocommerce-tabs ul.tabs li.active a, body.woocommerce-page div.product .woocommerce-tabs ul.tabs li.active a, body.woocommerce #content div.product .woocommerce-tabs ul.tabs li.active a, body.woocommerce-page #content div.product .woocommerce-tabs ul.tabs li.active a, .woocommerce div.product .woocommerce-tabs ul.tabs li a, .post_header h5 a, .post_header h6 a, .flex-direction-nav a:before, .social_share_button_wrapper .social_post_view .view_number, .social_share_button_wrapper .social_post_share_count .share_number, .portfolio_post_previous a, .portfolio_post_next a, #filter_selected, #autocomplete li strong, .themelink, body .ui-dialog[aria-describedby="ajax-login-register-login-dialog"] .ui-dialog-titlebar .ui-dialog-title, body .ui-dialog[aria-describedby="ajax-login-register-dialog"] .ui-dialog-titlebar .ui-dialog-title',
				'function' => 'css',
				'property' => 'color',
			),
			array(
				'element'  => 'body.page.page-template-gallery-archive-split-screen-php #fp-nav li .active span, body.tax-gallerycat #fp-nav li .active span, body.page.page-template-portfolio-fullscreen-split-screen-php #fp-nav li .active span, body.page.tax-portfolioset #fp-nav li .active span, body.page.page-template-gallery-archive-split-screen-php #fp-nav ul li a span, body.tax-gallerycat #fp-nav ul li a span, body.page.page-template-portfolio-fullscreen-split-screen-php #fp-nav ul li a span, body.page.tax-portfolioset #fp-nav ul li a span',
				'function' => 'css',
				'property' => 'background-color',
			),
		)
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_hr_color',
        'label'    => esc_html__('Horizontal Line Color', 'hoteller' ),
        'section'  => 'general_color',
        'default'  => '#222222',
        'output' => array(
	        array(
	            'element'  => '#social_share_wrapper, hr, #social_share_wrapper, .post.type-post, .comment .right, .widget_tag_cloud div a, .meta-tags a, .tag_cloud a, #footer, #post_more_wrapper, #page_content_wrapper .inner .sidebar_content, #page_content_wrapper .inner .sidebar_content.left_sidebar, .ajax_close, .ajax_next, .ajax_prev, .portfolio_next, .portfolio_prev, .portfolio_next_prev_wrapper.video .portfolio_prev, .portfolio_next_prev_wrapper.video .portfolio_next, .separated, .blog_next_prev_wrapper, #post_more_wrapper h5, #ajax_portfolio_wrapper.hidding, #ajax_portfolio_wrapper.visible, .tabs.vertical .ui-tabs-panel, .ui-tabs.vertical.right .ui-tabs-nav li, .woocommerce div.product .woocommerce-tabs ul.tabs li, .woocommerce #content div.product .woocommerce-tabs ul.tabs li, .woocommerce-page div.product .woocommerce-tabs ul.tabs li, .woocommerce-page #content div.product .woocommerce-tabs ul.tabs li, .woocommerce div.product .woocommerce-tabs .panel, .woocommerce-page div.product .woocommerce-tabs .panel, .woocommerce #content div.product .woocommerce-tabs .panel, .woocommerce-page #content div.product .woocommerce-tabs .panel, .woocommerce table.shop_table, .woocommerce-page table.shop_table, .woocommerce .cart-collaterals .cart_totals, .woocommerce-page .cart-collaterals .cart_totals, .woocommerce .cart-collaterals .shipping_calculator, .woocommerce-page .cart-collaterals .shipping_calculator, .woocommerce .cart-collaterals .cart_totals tr td, .woocommerce .cart-collaterals .cart_totals tr th, .woocommerce-page .cart-collaterals .cart_totals tr td, .woocommerce-page .cart-collaterals .cart_totals tr th, table tr th, table tr td, .woocommerce #payment, .woocommerce-page #payment, .woocommerce #payment ul.payment_methods li, .woocommerce-page #payment ul.payment_methods li, .woocommerce #payment div.form-row, .woocommerce-page #payment div.form-row, .ui-tabs li:first-child, .ui-tabs .ui-tabs-nav li, .ui-tabs.vertical .ui-tabs-nav li, .ui-tabs.vertical.right .ui-tabs-nav li.ui-state-active, .ui-tabs.vertical .ui-tabs-nav li:last-child, #page_content_wrapper .inner .sidebar_wrapper ul.sidebar_widget li.widget_nav_menu ul.menu li.current-menu-item a, .page_content_wrapper .inner .sidebar_wrapper ul.sidebar_widget li.widget_nav_menu ul.menu li.current-menu-item a, .ui-accordion .ui-accordion-header, .ui-accordion .ui-accordion-content, #page_content_wrapper .sidebar .content .sidebar_widget li h2.widgettitle:before, h2.widgettitle:before, #autocomplete, .ppb_blog_minimal .one_third_bg, .tabs .ui-tabs-panel, .ui-tabs .ui-tabs-nav li, .ui-tabs li:first-child, .ui-tabs.vertical .ui-tabs-nav li:last-child, .woocommerce .woocommerce-ordering select, .woocommerce div.product .woocommerce-tabs ul.tabs li.active, .woocommerce-page div.product .woocommerce-tabs ul.tabs li.active, .woocommerce #content div.product .woocommerce-tabs ul.tabs li.active, .woocommerce-page #content div.product .woocommerce-tabs ul.tabs li.active, .woocommerce-page table.cart th, table.shop_table thead tr th, hr.title_break, .overlay_gallery_border, #page_content_wrapper.split #copyright, .page_content_wrapper.split #copyright, .post.type-post, .events.type-events, h5.event_title, .post_header h5.event_title, .client_archive_wrapper, #page_content_wrapper .sidebar .content .sidebar_widget li.widget, .page_content_wrapper .sidebar .content .sidebar_widget li.widget, hr.title_break.bold, blockquote, .social_share_button_wrapper, .social_share_button_wrapper, body:not(.single) .post_wrapper, .themeborder, #about_the_author, .related.products, form.mphb_sc_checkout-form .mphb-reserve-rooms-details .mphb-room-details, .room_grid2_action_wrapper .child_one_half.themeborder, .mphb-reserve-room-section, .room_grid_wrapper .room_grid_attr_wrapper .child_one_third',
	            'property' => 'border-color',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 15,
	    'js_vars'   => array(
			array(
				'element'  => '#social_share_wrapper, hr, #social_share_wrapper, .post.type-post, .comment .right, .widget_tag_cloud div a, .meta-tags a, .tag_cloud a, #footer, #post_more_wrapper, #page_content_wrapper .inner .sidebar_content, #page_content_wrapper .inner .sidebar_content.left_sidebar, .ajax_close, .ajax_next, .ajax_prev, .portfolio_next, .portfolio_prev, .portfolio_next_prev_wrapper.video .portfolio_prev, .portfolio_next_prev_wrapper.video .portfolio_next, .separated, .blog_next_prev_wrapper, #post_more_wrapper h5, #ajax_portfolio_wrapper.hidding, #ajax_portfolio_wrapper.visible, .tabs.vertical .ui-tabs-panel, .ui-tabs.vertical.right .ui-tabs-nav li, .woocommerce div.product .woocommerce-tabs ul.tabs li, .woocommerce #content div.product .woocommerce-tabs ul.tabs li, .woocommerce-page div.product .woocommerce-tabs ul.tabs li, .woocommerce-page #content div.product .woocommerce-tabs ul.tabs li, .woocommerce div.product .woocommerce-tabs .panel, .woocommerce-page div.product .woocommerce-tabs .panel, .woocommerce #content div.product .woocommerce-tabs .panel, .woocommerce-page #content div.product .woocommerce-tabs .panel, .woocommerce table.shop_table, .woocommerce-page table.shop_table, .woocommerce .cart-collaterals .cart_totals, .woocommerce-page .cart-collaterals .cart_totals, .woocommerce .cart-collaterals .shipping_calculator, .woocommerce-page .cart-collaterals .shipping_calculator, .woocommerce .cart-collaterals .cart_totals tr td, .woocommerce .cart-collaterals .cart_totals tr th, .woocommerce-page .cart-collaterals .cart_totals tr td, .woocommerce-page .cart-collaterals .cart_totals tr th, table tr th, table tr td, .woocommerce #payment, .woocommerce-page #payment, .woocommerce #payment ul.payment_methods li, .woocommerce-page #payment ul.payment_methods li, .woocommerce #payment div.form-row, .woocommerce-page #payment div.form-row, .ui-tabs li:first-child, .ui-tabs .ui-tabs-nav li, .ui-tabs.vertical .ui-tabs-nav li, .ui-tabs.vertical.right .ui-tabs-nav li.ui-state-active, .ui-tabs.vertical .ui-tabs-nav li:last-child, #page_content_wrapper .inner .sidebar_wrapper ul.sidebar_widget li.widget_nav_menu ul.menu li.current-menu-item a, .page_content_wrapper .inner .sidebar_wrapper ul.sidebar_widget li.widget_nav_menu ul.menu li.current-menu-item a, .ui-accordion .ui-accordion-header, .ui-accordion .ui-accordion-content, #page_content_wrapper .sidebar .content .sidebar_widget li h2.widgettitle:before, h2.widgettitle:before, #autocomplete, .ppb_blog_minimal .one_third_bg, .tabs .ui-tabs-panel, .ui-tabs .ui-tabs-nav li, .ui-tabs li:first-child, .ui-tabs.vertical .ui-tabs-nav li:last-child, .woocommerce .woocommerce-ordering select, .woocommerce div.product .woocommerce-tabs ul.tabs li.active, .woocommerce-page div.product .woocommerce-tabs ul.tabs li.active, .woocommerce #content div.product .woocommerce-tabs ul.tabs li.active, .woocommerce-page #content div.product .woocommerce-tabs ul.tabs li.active, .woocommerce-page table.cart th, table.shop_table thead tr th, hr.title_break, .overlay_gallery_border, #page_content_wrapper.split #copyright, .page_content_wrapper.split #copyright, .post.type-post, .events.type-events, h5.event_title, .post_header h5.event_title, .client_archive_wrapper, #page_content_wrapper .sidebar .content .sidebar_widget li.widget, .page_content_wrapper .sidebar .content .sidebar_widget li.widget, hr.title_break.bold, blockquote, .social_share_button_wrapper, .social_share_button_wrapper, body:not(.single) .post_wrapper, .themeborder, #about_the_author, .related.products, form.mphb_sc_checkout-form .mphb-reserve-rooms-details .mphb-room-details, .mphb-reserve-room-section, .room_grid_wrapper .room_grid_attr_wrapper .child_one_third',
				'function' => 'css',
				'property' => 'border-color',
			),
		)
    );
    
    $controls[] = array(
        'type'     => 'title',
        'settings'  => 'tg_input_title',
        'label'    => esc_html__('Input and Textarea Settings', 'hoteller' ),
        'section'  => 'general_input',
	    'priority' => 15,
    );
    
    $controls[] = array(
        'type'     => 'select',
        'settings'  => 'tg_input_style',
        'label'    => esc_html__('Input and Textarea Style', 'hoteller' ),
        'description' => esc_html__('Select style for input and textarea elements', 'hoteller' ),
        'section'  => 'general_input',
        'default'  => 'paper',
        'choices'  => $tg_input_style,
        'transport' 	 => 'postMessage',
        'priority' => 16,
    );
    
    $controls[] = array(
        'type'     => 'typography',
        'settings'  => 'tg_input_typography',
        'label'    => esc_html__('Input and Textarea Typography', 'hoteller' ),
        'section'  => 'general_input',
        'default'  => array(
            'font-family'    => 'Jost',
            'variant'        => '500',
            'font-size'      => '15px',
            'line-height'    => '1.7',
            'letter-spacing' => '0',
            'text-transform' => 'none',
        ),
        'output' => array(
            array(
                'element'  => 'input[type=text], input[type=password], input[type=email], input[type=url], input[type=tel], input[type=date], textarea, select, input[type=number]',
            ),
        ),
        'priority' => 16,
    );
    
    $controls[] = array(
        'type'     => 'slider',
        'settings'  => 'tg_input_border_radius',
        'label'    => esc_html__('Input and Textarea Border Radius (px)', 'hoteller' ),
        'section'  => 'general_input',
        'default'  => 0,
        'choices' => array( 'min' => 0, 'max' => 25, 'step' => 1 ),
        'output' => array(
            array(
                'element'  => 'input[type=text], input[type=password], input[type=email], input[type=url], input[type=tel], input[type=date], textarea, .widget_search form, select, .woocommerce div.product form.cart .variations select, .woocommerce-checkout .select2-container .select2-selection--single, .woocommerce form.checkout_coupon, .woocommerce form.login, .woocommerce form.register, .mphb_cb_checkout_form .mphb-gateways-list>li, .mphb_sc_checkout-form .mphb-gateways-list>li, input[type=number]',
                'property' => 'border-radius',
                'units'    => 'px',
            ),
        ),
        'transport' 	 => 'postMessage',
        'priority' => 16,
        'js_vars'   => array(
            array(
                'element'  => 'input[type=text], input[type=password], input[type=email], input[type=url], input[type=tel], input[type=date], textarea, select, .woocommerce-checkout .select2-container .select2-selection--single, .woocommerce form.checkout_coupon, .woocommerce form.login, .woocommerce form.register, .mphb_cb_checkout_form .mphb-gateways-list>li, .mphb_sc_checkout-form .mphb-gateways-list>li, input[type=number]',
                'function' => 'css',
                'property' => 'border-radius',
                'units'    => 'px',
            ),
        )
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_input_bg_color',
        'label'    => esc_html__('Input and Textarea Background Color', 'hoteller' ),
        'section'  => 'general_input',
        'default'  => '#f9f9f9',
        'output' => array(
	        array(
	            'element'  => 'input[type=text], input[type=password], input[type=email], input[type=url], input[type=tel], input[type=date], textarea, select, .select2-container--default .select2-selection--single, .select2-dropdown, input[type=number]',
	            'property' => 'background-color',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 16,
	    'js_vars'   => array(
			array(
				'element'  => 'input[type=text], input[type=password], input[type=email], input[type=url], input[type=tel], input[type=date], textarea, select, .select2-container--default .select2-selection--single, .select2-dropdown, input[type=number]',
				'function' => 'css',
				'property' => 'background-color',
			),
		)
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_input_font_color',
        'label'    => esc_html__('Input and Textarea Font Color', 'hoteller' ),
        'section'  => 'general_input',
        'default'  => '#222222',
        'output' => array(
	        array(
	            'element'  => 'input[type=text], input[type=password], input[type=email], input[type=url], input[type=tel], input[type=date], textarea, select, .availability_search_wrapper .mphb_sc_search-wrapper .mphb_sc_search-adults:after, .availability_search_wrapper .mphb_sc_search-wrapper .mphb_sc_search-children:after, .mphb_sc_booking_form-wrapper .mphb-adults-wrapper:after, .mphb_sc_booking_form-wrapper .mphb-children-wrapper:after, .mphb-reserve-room-section .mphb-rooms-quantity-wrapper:after, .mphb-reserve-rooms-details .mphb-adults-chooser:after, .mphb-reserve-rooms-details .mphb-children-chooser:after, .mphb-customer-country:after, .widget_mphb_search_availability_widget .mphb_widget_search-adults:after, .widget_mphb_search_availability_widget .mphb_widget_search-children:after, .select2-container--default .select2-selection--single .select2-selection__rendered, .select2-dropdown, .widget_search form:before, input[type=number]',
	            'property' => 'color',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 17,
	    'js_vars'   => array(
			array(
				'element'  => 'input[type=text], input[type=password], input[type=email], input[type=url], input[type=tel], input[type=date], textarea, select, .availability_search_wrapper .mphb_sc_search-wrapper .mphb_sc_search-adults:after, .availability_search_wrapper .mphb_sc_search-wrapper .mphb_sc_search-children:after, .mphb_sc_booking_form-wrapper .mphb-adults-wrapper:after, .mphb_sc_booking_form-wrapper .mphb-children-wrapper:after, .mphb-reserve-room-section .mphb-rooms-quantity-wrapper:after, .mphb-reserve-rooms-details .mphb-adults-chooser:after, .mphb-reserve-rooms-details .mphb-children-chooser:after, .mphb-customer-country:after, .widget_mphb_search_availability_widget .mphb_widget_search-adults:after, .widget_mphb_search_availability_widget .mphb_widget_search-children:after, .select2-container--default .select2-selection--single .select2-selection__rendered, .select2-dropdown, .widget_search form:before, input[type=number]',
				'function' => 'css',
				'property' => 'color',
			),
		)
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_input_border_color',
        'label'    => esc_html__('Input and Textarea Border Color', 'hoteller' ),
        'section'  => 'general_input',
        'default'  => '#222222',
        'output' => array(
	        array(
	            'element'  => 'input[type=text], input[type=password], input[type=email], input[type=url], input[type=tel], input[type=date], textarea, select, .mphb_cb_checkout_form .mphb-gateways-list>li, .mphb_sc_checkout-form .mphb-gateways-list>li, .woocommerce-checkout .select2-container .select2-selection--single, .woocommerce-checkout .select2-dropdown, .woocommerce form.checkout_coupon, .woocommerce form.login, .woocommerce form.register, .widget_search form, input[type=number]',
	            'property' => 'border-color',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 18,
	    'js_vars'   => array(
			array(
				'element'  => 'input[type=text], input[type=password], input[type=email], input[type=url], input[type=tel], input[type=date], textarea, select, .mphb_cb_checkout_form .mphb-gateways-list>li, .mphb_sc_checkout-form .mphb-gateways-list>li, .woocommerce-checkout .select2-container .select2-selection--single, .woocommerce-checkout .select2-dropdown, .woocommerce form.checkout_coupon, .woocommerce form.login, .woocommerce form.register, .widget_search form, input[type=number]',
				'function' => 'css',
				'property' => 'border-color',
			),
		)
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_input_focus_color',
        'label'    => esc_html__('Input and Textarea Focus State Color', 'hoteller' ),
        'section'  => 'general_input',
        'default'  => '#1C58F6',
        'output' => array(
	        array(
	            'element'  => 'input[type=text]:focus, input[type=password]:focus, input[type=email]:focus, input[type=url]:focus, input[type=date]:focus, textarea:focus, .mphb_cb_checkout_form .mphb-gateways-list>li.active, .mphb_sc_checkout-form .mphb-gateways-list>li.active',
	            'property' => 'border-color',
	        ),
	        array(
	            'element'  => '.input_effect ~ .focus-border',
	            'property' => 'background-color',
	        ),
            array(
                'element'  => '.select2-container--default .select2-results__option[aria-selected=true], .select2-container--default .select2-results__option[data-selected=true]',
                'property' => 'color',
            ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 18,
	    'js_vars'   => array(
			array(
				'element'  => 'input[type=text]:focus, input[type=password]:focus, input[type=email]:focus, input[type=url]:focus, input[type=date]:focus, textarea:focus, .mphb_cb_checkout_form .mphb-gateways-list>li.active, .mphb_sc_checkout-form .mphb-gateways-list>li.active',
				'function' => 'css',
				'property' => 'border-color',
			),
			array(
	            'element'  => '.input_effect ~ .focus-border',
	            'function' => 'css',
	            'property' => 'background-color',
	        ),
            array(
                'element'  => '.select2-container--default .select2-results__option[aria-selected=true], .select2-container--default .select2-results__option[data-selected=true]',
                'property' => 'color',
            ),
		)
    );
    
    $controls[] = array(
        'type'     => 'title',
        'settings'  => 'tg_button_title',
        'label'    => esc_html__('Button Settings', 'hoteller' ),
        'section'  => 'general_input',
	    'priority' => 18,
    );
    
    $controls[] = array(
        'type'     => 'typography',
        'settings'  => 'tg_button_typography',
        'label'    => esc_html__('Button Typography', 'hoteller' ),
        'section'  => 'general_input',
        'default'  => array(
            'font-family'    => 'Oswald',
            'variant'        => '400',
            'font-size'      => '14px',
            'line-height'    => '1.6',
            'letter-spacing' => '1px',
            'text-transform' => 'none',
        ),
        'output' => array(
            array(
                'element'  => 'input[type=submit], input[type=button], a.button, .button, .woocommerce .page_slider a.button, a.button.fullwidth, .woocommerce-page div.product form.cart .button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, body .ui-dialog[aria-describedby="ajax-login-register-login-dialog"] .form-wrapper input[type="submit"], body .ui-dialog[aria-describedby="ajax-login-register-dialog"] .form-wrapper input[type="submit"], .woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button',
            ),
        ),
        'priority' => 19,
    );
    
    $controls[] = array(
        'type'     => 'slider',
        'settings'  => 'tg_button_border_radius',
        'label'    => esc_html__('Button Border Radius (px)', 'hoteller' ),
        'section'  => 'general_input',
        'default'  => 0,
        'choices' => array( 'min' => 0, 'max' => 50, 'step' => 1 ),
        'output' => array(
            array(
                'element'  => 'input[type=submit], input[type=button], a.button, .button, .woocommerce .page_slider a.button, a.button.fullwidth, .woocommerce-page div.product form.cart .button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, body .ui-dialog[aria-describedby="ajax-login-register-login-dialog"] .form-wrapper input[type="submit"], body .ui-dialog[aria-describedby="ajax-login-register-dialog"] .form-wrapper input[type="submit"], .woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce #payment div.payment_box, .woocommerce-page #payment div.payment_box, .woocommerce-message, .woocommerce-error, .woocommerce-info, #toTop',
                'property' => 'border-radius',
                'units'    => 'px',
            ),
        ),
        'transport' 	 => 'postMessage',
        'priority' => 20,
        'js_vars'   => array(
            array(
                'element'  => 'input[type=submit], input[type=button], a.button, .button, .woocommerce .page_slider a.button, a.button.fullwidth, .woocommerce-page div.product form.cart .button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, body .ui-dialog[aria-describedby="ajax-login-register-login-dialog"] .form-wrapper input[type="submit"], body .ui-dialog[aria-describedby="ajax-login-register-dialog"] .form-wrapper input[type="submit"], .woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce #payment div.payment_box, .woocommerce-page #payment div.payment_box, .woocommerce-message, .woocommerce-error, .woocommerce-info, #toTop',
                'function' => 'css',
                'property' => 'border-radius',
                'units'    => 'px',
            ),
        )
    );
    
    $controls[] = array(
        'type'     => 'slider',
        'settings'  => 'tg_button_width',
        'label'    => esc_html__('Button Border Width', 'hoteller' ),
        'section'  => 'general_input',
        'default'  => 2,
        'choices' => array( 'min' => 0, 'max' => 20, 'step' => 1 ),
        'output' => array(
	        array(
	            'element'  => 'input[type=submit], input[type=button], a.button, .button, .woocommerce .page_slider a.button, a.button.fullwidth, .woocommerce-page div.product form.cart .button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, body .ui-dialog[aria-describedby="ajax-login-register-login-dialog"] .form-wrapper input[type="submit"], body .ui-dialog[aria-describedby="ajax-login-register-dialog"] .form-wrapper input[type="submit"], .woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button',
	            'property' => 'border-width',
	            'units'    => 'px',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 20,
	    'js_vars'   => array(
			array(
				'element'  => 'input[type=submit], input[type=button], a.button, .button, .woocommerce .page_slider a.button, a.button.fullwidth, .woocommerce-page div.product form.cart .button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, body .ui-dialog[aria-describedby="ajax-login-register-login-dialog"] .form-wrapper input[type="submit"], body .ui-dialog[aria-describedby="ajax-login-register-dialog"] .form-wrapper input[type="submit"], .woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button',
				'function' => 'css',
				'property' => 'border-width',
				'units'    => 'px',
			),
		)
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_button_bg_color',
        'label'    => esc_html__('Button Background Color', 'hoteller' ),
        'section'  => 'general_input',
        'default'  => '#222222',
        'output' => array(
	        array(
	            'element'  => 'input[type=submit], input[type=button], a.button, .button, .pagination span, .pagination a:hover, .woocommerce .footer_bar .button, .woocommerce .footer_bar .button:hover, .woocommerce-page div.product form.cart .button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .post_type_icon, .filter li a:hover, .filter li a.active, #portfolio_wall_filters li a.active,  #portfolio_wall_filters li a:hover, .comment_box, .one_half.gallery2 .portfolio_type_wrapper, .one_third.gallery3 .portfolio_type_wrapper, .one_fourth.gallery4 .portfolio_type_wrapper, .one_fifth.gallery5 .portfolio_type_wrapper, .portfolio_type_wrapper, .post_share_text, #close_share, .widget_tag_cloud div a:hover, .ui-accordion .ui-accordion-header .ui-icon, .mobile_menu_wrapper #mobile_menu_close.button, .mobile_menu_wrapper #close_mobile_menu, .multi_share_button, body .ui-dialog[aria-describedby="ajax-login-register-login-dialog"] .form-wrapper input[type="submit"], body .ui-dialog[aria-describedby="ajax-login-register-dialog"] .form-wrapper input[type="submit"]',
	            'property' => 'background-color',
	        ),
	        array(
	            'element'  => '.pagination span, .pagination a:hover, .button.ghost, .button.ghost:hover, .button.ghost:active, blockquote:after, .woocommerce-MyAccount-navigation ul li.is-active, body .ui-dialog[aria-describedby="ajax-login-register-login-dialog"] .form-wrapper input[type="submit"], body .ui-dialog[aria-describedby="ajax-login-register-dialog"] .form-wrapper input[type="submit"]',
	            'property' => 'border-color',
	        ),
	        array(
	            'element'  => '.comment_box:before, .comment_box:after',
	            'property' => 'border-top-color',
	        ),
	        array(
	            'element'  => '.button.ghost, .button.ghost:hover, .button.ghost:active, .infinite_load_more, blockquote:before, .woocommerce-MyAccount-navigation ul li.is-active a, body .ui-dialog[aria-describedby="ajax-login-register-login-dialog"] .form-wrapper input[type="submit"], body .ui-dialog[aria-describedby="ajax-login-register-dialog"] .form-wrapper input[type="submit"]',
	            'property' => 'color',
	        ),
	    ),
	    'js_vars'   => array(
			array(
				'element'  => 'input[type=submit], input[type=button], a.button, .button, .pagination span, .pagination a:hover, .woocommerce .footer_bar .button, .woocommerce .footer_bar .button:hover, .woocommerce-page div.product form.cart .button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .post_type_icon, .filter li a:hover, .filter li a.active, #portfolio_wall_filters li a.active,  #portfolio_wall_filters li a:hover, .comment_box, .one_half.gallery2 .portfolio_type_wrapper, .one_third.gallery3 .portfolio_type_wrapper, .one_fourth.gallery4 .portfolio_type_wrapper, .one_fifth.gallery5 .portfolio_type_wrapper, .portfolio_type_wrapper, .post_share_text, #close_share, .widget_tag_cloud div a:hover, .ui-accordion .ui-accordion-header .ui-icon, .mobile_menu_wrapper #mobile_menu_close.button, .mobile_menu_wrapper #close_mobile_menu, .multi_share_button, body .ui-dialog[aria-describedby="ajax-login-register-login-dialog"] .form-wrapper input[type="submit"], body .ui-dialog[aria-describedby="ajax-login-register-dialog"] .form-wrapper input[type="submit"]',
				'function' => 'css',
				'property' => 'background-color',
			),
			array(
				'element'  => '.pagination span, .pagination a:hover, .button.ghost, .button.ghost:hover, .button.ghost:active, blockquote:after, .woocommerce-MyAccount-navigation ul li.is-active, body .ui-dialog[aria-describedby="ajax-login-register-login-dialog"] .form-wrapper input[type="submit"], body .ui-dialog[aria-describedby="ajax-login-register-dialog"] .form-wrapper input[type="submit"]',
				'function' => 'css',
				'property' => 'border-color',
			),
			array(
				'element'  => '.comment_box:before, .comment_box:after',
				'function' => 'css',
				'property' => 'border-top-color',
			),
			array(
				'element'  => '.button.ghost, .button.ghost:hover, .button.ghost:active, .infinite_load_more, blockquote:before, .woocommerce-MyAccount-navigation ul li.is-active a, body .ui-dialog[aria-describedby="ajax-login-register-login-dialog"] .form-wrapper input[type="submit"], body .ui-dialog[aria-describedby="ajax-login-register-dialog"] .form-wrapper input[type="submit"]',
				'function' => 'css',
				'property' => 'color',
			),
		),
	    'transport' 	 => 'postMessage',
	    'priority' => 20,
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_button_font_color',
        'label'    => esc_html__('Button Font Color', 'hoteller' ),
        'section'  => 'general_input',
        'default'  => '#ffffff',
        'output' => array(
	        array(
	            'element'  => 'input[type=submit], input[type=button], a.button, .button, .pagination a:hover, .woocommerce .footer_bar .button , .woocommerce .footer_bar .button:hover, .woocommerce-page div.product form.cart .button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .post_type_icon, .filter li a:hover, .filter li a.active, #portfolio_wall_filters li a.active,  #portfolio_wall_filters li a:hover, .comment_box, .one_half.gallery2 .portfolio_type_wrapper, .one_third.gallery3 .portfolio_type_wrapper, .one_fourth.gallery4 .portfolio_type_wrapper, .one_fifth.gallery5 .portfolio_type_wrapper, .portfolio_type_wrapper, .post_share_text, #close_share, .widget_tag_cloud div a:hover, .ui-accordion .ui-accordion-header .ui-icon, .mobile_menu_wrapper #mobile_menu_close.button, #toTop, .multi_share_button, body .ui-dialog[aria-describedby="ajax-login-register-login-dialog"] .form-wrapper input[type="submit"], body .ui-dialog[aria-describedby="ajax-login-register-dialog"] .form-wrapper input[type="submit"],.pagination span.current, .mobile_menu_wrapper #close_mobile_menu, #footer a.button',
	            'property' => 'color',
	        ),
	    ),
	    'js_vars'   => array(
			array(
				'element'  => 'input[type=submit], input[type=button], a.button, .button, .pagination span, .pagination a:hover, .woocommerce .footer_bar .button, .woocommerce .footer_bar .button:hover, .woocommerce-page div.product form.cart .button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .post_type_icon, .filter li a:hover, .filter li a.active, #portfolio_wall_filters li a.active,  #portfolio_wall_filters li a:hover, .comment_box, .one_half.gallery2 .portfolio_type_wrapper, .one_third.gallery3 .portfolio_type_wrapper, .one_fourth.gallery4 .portfolio_type_wrapper, .one_fifth.gallery5 .portfolio_type_wrapper, .portfolio_type_wrapper, .post_share_text, #close_share, .widget_tag_cloud div a:hover, .ui-accordion .ui-accordion-header .ui-icon, .mobile_menu_wrapper #mobile_menu_close.button, #toTop, .multi_share_button, body .ui-dialog[aria-describedby="ajax-login-register-login-dialog"] .form-wrapper input[type="submit"], body .ui-dialog[aria-describedby="ajax-login-register-dialog"] .form-wrapper input[type="submit"],.pagination span.current, .mobile_menu_wrapper #close_mobile_menu, #footer a.button',
				'function' => 'css',
				'property' => 'color',
			),
		),
	    'transport' 	 => 'postMessage',
	    'priority' => 21,
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_button_border_color',
        'label'    => esc_html__('Button Border Color', 'hoteller' ),
        'section'  => 'general_input',
        'default'  => '#222222',
        'output' => array(
	        array(
	            'element'  => 'input[type=submit], input[type=button], a.button, .button, .pagination a:hover, .woocommerce .footer_bar .button , .woocommerce .footer_bar .button:hover, .woocommerce-page div.product form.cart .button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .infinite_load_more, .post_share_text, #close_share, .widget_tag_cloud div a:hover, .mobile_menu_wrapper #close_mobile_menu, .mobile_menu_wrapper #mobile_menu_close.button, body .ui-dialog[aria-describedby="ajax-login-register-login-dialog"] .form-wrapper input[type="submit"], body .ui-dialog[aria-describedby="ajax-login-register-dialog"] .form-wrapper input[type="submit"]',
	            'property' => 'border-color',
	        ),
	    ),
	    'js_vars'   => array(
			array(
				'element'  => 'input[type=submit], input[type=button], a.button, .button, .pagination span, .pagination a:hover, .woocommerce .footer_bar .button, .woocommerce .footer_bar .button:hover, .woocommerce-page div.product form.cart .button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .post_type_icon, .filter li a:hover, .filter li a.active, #portfolio_wall_filters li a.active,  #portfolio_wall_filters li a:hover, .comment_box, .one_half.gallery2 .portfolio_type_wrapper, .one_third.gallery3 .portfolio_type_wrapper, .one_fourth.gallery4 .portfolio_type_wrapper, .one_fifth.gallery5 .portfolio_type_wrapper, .portfolio_type_wrappe, .post_share_text, #close_share, .widget_tag_cloud div a:hover, .mobile_menu_wrapper #close_mobile_menu, .header_cart_wrapper > a, .ui-accordion .ui-accordion-header .ui-icon, .mobile_menu_wrapper #mobile_menu_close.button, body .ui-dialog[aria-describedby="ajax-login-register-login-dialog"] .form-wrapper input[type="submit"], body .ui-dialog[aria-describedby="ajax-login-register-dialog"] .form-wrapper input[type="submit"]',
				'function' => 'css',
				'property' => 'border-color',
			),
		),
	    'transport' 	 => 'postMessage',
	    'priority' => 22,
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_button_hover_bg_color',
        'label'    => esc_html__('Button Hover Background Color', 'hoteller' ),
        'section'  => 'general_input',
        'default'  => '#1C58F6',
        'output' => array(
	        array(
	            'element'  => 'input[type=button]:hover, input[type=submit]:hover, a.button:hover, .button:hover, .button.submit, a.button.white:hover, .button.white:hover, a.button.white:active, .button.white:active',
	            'property' => 'background-color',
	        ),
	    ),
	    'js_vars'   => array(
			array(
				'element'  => 'input[type=button]:hover, input[type=submit]:hover, a.button:hover, .button:hover, .button.submit, a.button.white:hover, .button.white:hover, a.button.white:active, .button.white:active',
				'function' => 'css',
				'property' => 'background-color',
			),
		),
	    'transport' 	 => 'postMessage',
	    'priority' => 23,
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_button_hover_font_color',
        'label'    => esc_html__('Button Hover Font Color', 'hoteller' ),
        'section'  => 'general_input',
        'default'  => '#ffffff',
        'output' => array(
	        array(
	            'element'  => 'input[type=button]:hover, input[type=submit]:hover, a.button:hover, .button:hover, .button.submit, a.button.white:hover, .button.white:hover, a.button.white:active, .button.white:active',
	            'property' => 'color',
	        ),
	    ),
	    'js_vars'   => array(
			array(
				'element'  => 'input[type=button]:hover, input[type=submit]:hover, a.button:hover, .button:hover, .button.submit, a.button.white:hover, .button.white:hover, a.button.white:active, .button.white:active',
				'function' => 'css',
				'property' => 'color',
			),
		),
	    'transport' 	 => 'postMessage',
	    'priority' => 24,
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_button_hover_border_color',
        'label'    => esc_html__('Button Hover Border Color', 'hoteller' ),
        'section'  => 'general_input',
        'default'  => '#1C58F6',
        'output' => array(
	        array(
	            'element'  => 'input[type=button]:hover, input[type=submit]:hover, a.button:hover, .button:hover, .button.submit, a.button.white:hover, .button.white:hover, a.button.white:active, .button.white:active',
	            'property' => 'border-color',
	        ),
	    ),
	    'js_vars'   => array(
			array(
				'element'  => 'input[type=button]:hover, input[type=submit]:hover, a.button:hover, .button:hover, .button.submit, a.button.white:hover, .button.white:hover, a.button.white:active, .button.white:active',
				'function' => 'css',
				'property' => 'border-color',
			),
		),
	    'transport' 	 => 'postMessage',
	    'priority' => 25,
    );
    
    /*$controls[] = array(
        'type'     => 'toggle',
        'settings'  => 'tg_frame',
        'label'    => esc_html__('Enable Frame', 'hoteller' ),
        'description' => esc_html__('Check this to enable frame for site layout', 'hoteller' ),
        'section'  => 'general_frame',
        'default'  => 0,
	    'priority' => 26,
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_frame_color',
        'label'    => esc_html__('Frame Color', 'hoteller' ),
        'section'  => 'general_frame',
        'default'  => '#222222',
        'output' => array(
	        array(
	            'element'  => '.frame_top, .frame_bottom, .frame_left, .frame_right',
	            'property' => 'background',
	        ),
	    ),
	    'transport'  => 'postMessage',
	    'priority' => 27,
	    'js_vars'   => array(
			array(
				'element'  => '.frame_top, .frame_bottom, .frame_left, .frame_right',
				'function' => 'css',
				'property' => 'background',
			),
		)
    );*/
    
    //End General Tab Settings

	//Register Menu Tab Settings
	
	$controls[] = array(
        'type'     => 'title',
        'settings'  => 'tg_header_content_title',
        'label'    => esc_html__('Header Content Settings', 'hoteller' ),
        'section'  => 'menu_general',
	    'priority' => 1,
    );
	
	$controls[] = array(
        'type'     => 'toggle',
        'settings'  => 'tg_fixed_menu',
        'label'    => esc_html__('Enable Sticky Header', 'hoteller' ),
        'description' => esc_html__('Enable this option to display main menu fixed when scrolling', 'hoteller' ),
        'section'  => 'menu_general',
        'default'  => 1,
	    'priority' => 2,
    );
    
    $controls[] = array(
        'type'     => 'radio',
        'settings'  => 'tg_header_content',
        'label'    => esc_html__('Display Header Content From', 'hoteller' ),
        'description' => esc_html__('Select how theme get main header & navigation content', 'hoteller' ),
        'section'  => 'menu_general',
        'default'  => 'menu',
        'choices'  => $tg_header_content,
	    'priority' => 2,
    );
    
    $controls[] = array(
        'type'     => 'title',
        'settings'  => 'tg_default_header_content_title',
        'label'    => esc_html__('Default Header Content Settings', 'hoteller' ),
        'section'  => 'menu_general',
	    'priority' => 3,
    );
    
    $controls[] = array(
        'type'     => 'select',
        'settings'  => 'tg_header_content_default',
        'label'    => esc_html__('Default Header Content', 'hoteller' ),
        'description' => esc_html__('Select default header content for general pages & posts', 'hoteller' ),
        'section'  => 'menu_general',
        'default'  => '',
        'choices'  => $tg_headers_select,
	    'priority' => 4,
    );
    
    $controls[] = array(
        'type'     => 'select',
        'settings'  => 'tg_sticky_header_content_default',
        'label'    => esc_html__('Default Sticky Header Content', 'hoteller' ),
        'description' => esc_html__('Select default sticky header content for general pages & posts', 'hoteller' ),
        'section'  => 'menu_general',
        'default'  => '',
        'choices'  => $tg_headers_select,
	    'priority' => 4,
    );
    
    $controls[] = array(
        'type'     => 'select',
        'settings'  => 'tg_transparent_header_content_default',
        'label'    => esc_html__('Default Transparent Header Content', 'hoteller' ),
        'description' => esc_html__('Select default transparent header content for general pages & posts', 'hoteller' ),
        'section'  => 'menu_general',
        'default'  => '',
        'choices'  => $tg_headers_select,
	    'priority' => 4,
    );
    
    $controls[] = array(
        'type'     => 'title',
        'settings'  => 'tg_menu_title',
        'label'    => esc_html__('General Menu Settings', 'hoteller' ),
        'section'  => 'menu_general',
	    'priority' => 5,
    );
	
    $controls[] = array(
        'type'     => 'radio',
        'settings'  => 'tg_menu_layout',
        'label'    => esc_html__('Menu Layout', 'hoteller' ),
        'section'  => 'menu_general',
        'default'  => 'leftalign',
        'choices'  => $tg_menu_layout,
	    'priority' => 6,
    );
    
    $controls[] = array(
        'type'     => 'toggle',
        'settings'  => 'tg_menu_show_cart',
        'label'    => esc_html__('Show Cart Icon (Required Woocommerce plugin)', 'hoteller' ),
        'description' => esc_html__('Enable this option to show cart icon which link to Woocommerce cart page along with main menu', 'hoteller' ),
        'section'  => 'menu_general',
        'default'  => 1,
	    'priority' => 6,
    );
    
    $controls[] = array(
        'type'     => 'toggle',
        'settings'  => 'tg_menu_show_client',
        'label'    => esc_html__('Show Client Icon (Required ZM Ajax Login & Register  plugin)', 'hoteller' ),
        'description' => esc_html__('Enable this option to show client icon which link to client login page along with main menu', 'hoteller' ),
        'section'  => 'menu_general',
        'default'  => 1,
	    'priority' => 6,
    );
    
    $controls[] = array(
        'type'     => 'dropdown-pages',
        'settings'  => 'tg_menu_my_booking',
        'label'    => esc_html__('My Booking Page (Optional)', 'hoteller' ),
        'description' => esc_html__('Select my booking page you created and it displays when user is logged in.', 'hoteller' ),
        'section'  => 'menu_general',
        'default'  => '',
	    'priority' => 26,
    );
    
    $controls[] = array(
        'type'     => 'typography',
        'settings'  => 'tg_menu_typography',
        'label'    => esc_html__('Menu Font Typography', 'hoteller' ),
        'section'  => 'menu_typography',
        'default'  => array(
            'font-family'    => 'Jost',
            'variant'        => '600',
            'font-size'      => '12px',
            'line-height'    => '1.6',
            'letter-spacing' => '1px',
            'text-transform' => 'uppercase',
        ),
        'output' => array(
            array(
                'element'  => '#menu_wrapper .nav ul li a, #menu_wrapper div .nav li > a, .header_client_wrapper',
            ),
        ),
        'transport' 	 => 'postMessage',
        'priority' => 4,
    );
    
    $controls[] = array(
        'type'     => 'slider',
        'settings'  => 'tg_menu_padding',
        'label'    => esc_html__('Menu Padding', 'hoteller' ),
        'section'  => 'menu_typography',
        'default'  => 26,
        'choices' => array( 'min' => 0, 'max' => 150, 'step' => 1 ),
        'output' => array(
            array(
                'element'  => '#menu_wrapper .nav ul li, #menu_wrapper div .nav li, html[data-menu=centeralogo] #logo_right_button, html[data-menu=leftalign] #logo_right_wrapper',
                'property' => 'padding-top',
                'units'    => 'px',
            ),
            array(
                'element'  => '#menu_wrapper .nav ul li, #menu_wrapper div .nav li, html[data-menu=centeralogo] #logo_right_button, html[data-menu=leftalign] #logo_right_wrapper',
                'property' => 'padding-bottom',
                'units'    => 'px',
            ),
        ),
        'transport' 	 => 'postMessage',
        'priority' => 4,
        'js_vars'   => array(
            array(
                'element'  => '#menu_wrapper .nav ul li, #menu_wrapper div .nav li, html[data-menu=centeralogo] #logo_right_button, html[data-menu=leftalign] #logo_right_wrapper',
                'function' => 'css',
                'property' => 'padding-top',
                'units'    => 'px',
            ),
            array(
                'element'  => '#menu_wrapper .nav ul li, #menu_wrapper div .nav li, html[data-menu=centeralogo] #logo_right_button, html[data-menu=leftalign] #logo_right_wrapper',
                'function' => 'css',
                'property' => 'padding-bottom',
                'units'    => 'px',
            ),
        )
    );
    
    $controls[] = array(
        'type'     => 'title',
        'settings'  => 'tg_menu_main_colors_title',
        'label'    => esc_html__('Main Menu Colors Settings', 'hoteller' ),
        'section'  => 'menu_color',
	    'priority' => 3,
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_menu_bg',
        'label'    => esc_html__('Menu Background', 'hoteller' ),
        'section'  => 'menu_color',
	    'default'     => '#ffffff',
	    'output' => array(
	        array(
	            'element'  => '.top_bar, html',
	            'property' => 'background-color',
	        ),
	    ),
	    'priority' => 4,
	    'transport' 	 => 'postMessage',
	    'js_vars'   => array(
			array(
				'element'  => '.top_bar, html',
				'function' => 'css',
				'property' => 'background-color',
			),
		),
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_menu_font_color',
        'label'    => esc_html__('Menu Font Color', 'hoteller' ),
        'section'  => 'menu_color',
        'default'  => '#222222',
        'output' => array(
	        array(
	            'element'  => '#menu_wrapper .nav ul li a, #menu_wrapper div .nav li > a, #mobile_nav_icon, #logo_wrapper .social_wrapper ul li a, .header_cart_wrapper a',
	            'property' => 'color',
	        ),
	        array(
	            'element'  => '#mobile_nav_icon',
	            'property' => 'border-color',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 5,
	    'js_vars'   => array(
			array(
				'element'  => '#menu_wrapper .nav ul li a, #menu_wrapper div .nav li > a, #mobile_nav_icon, #logo_wrapper .social_wrapper ul li a, .header_cart_wrapper a',
				'function' => 'css',
				'property' => 'color',
			),
			array(
				'element'  => '#mobile_nav_icon',
				'function' => 'css',
				'property' => 'border-color',
			),
		)
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_menu_hover_font_color',
        'label'    => esc_html__('Menu Hover State Font Color', 'hoteller' ),
        'section'  => 'menu_color',
        'default'  => '#222222',
        'output' => array(
	        array(
	            'element'  => '#menu_wrapper .nav ul li a.hover, #menu_wrapper .nav ul li a:hover, #menu_wrapper div .nav li a.hover, #menu_wrapper div .nav li a:hover, .header_cart_wrapper a:hover, #page_share:hover, #logo_wrapper .social_wrapper ul li a:hover',
	            'property' => 'color',
	        ),
	        array(
	            'element'  => '#menu_wrapper .nav ul li a:before, #menu_wrapper div .nav li > a:before, #wrapper.transparent #menu_wrapper div .nav li.current-menu-item a:before',
	            'property' => 'background-color',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 6,
	    'js_vars'   => array(
			array(
				'element'  => '#menu_wrapper .nav ul li a.hover, #menu_wrapper .nav ul li a:hover, #menu_wrapper div .nav li a.hover, #menu_wrapper div .nav li a:hover, .header_cart_wrapper a:hover, #page_share:hover, #logo_wrapper .social_wrapper ul li a:hover',
				'function' => 'css',
				'property' => 'color',
			),
			array(
	            'element'  => '#menu_wrapper .nav ul li a:before, #menu_wrapper div .nav li > a:before, #wrapper.transparent #menu_wrapper div .nav li.current-menu-item a:before',
	            'property' => 'background-color',
	        ),
		)
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_menu_active_font_color',
        'label'    => esc_html__('Menu Active State Font Color', 'hoteller' ),
        'section'  => 'menu_color',
        'default'  => '#222222',
        'output' => array(
	        array(
	            'element'  => '#menu_wrapper div .nav > li.current-menu-item > a, #menu_wrapper div .nav > li.current-menu-parent > a, #menu_wrapper div .nav > li.current-menu-ancestor > a, #menu_wrapper div .nav li ul li.current-menu-item a, #menu_wrapper div .nav li.current-menu-parent  ul li.current-menu-item a, #logo_wrapper .social_wrapper ul li a:active',
	            'property' => 'color',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 7,
	    'js_vars'   => array(
			array(
				'element'  => '#menu_wrapper div .nav > li.current-menu-item > a, #menu_wrapper div .nav > li.current-menu-parent > a, #menu_wrapper div .nav > li.current-menu-ancestor > a, #menu_wrapper div .nav li ul li.current-menu-item a, #menu_wrapper div .nav li.current-menu-parent  ul li.current-menu-item a, #logo_wrapper .social_wrapper ul li a:active',
				'function' => 'css',
				'property' => 'color',
			),
		)
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_menu_border_color',
        'label'    => esc_html__('Menu Bar Border Color', 'hoteller' ),
        'section'  => 'menu_color',
        'default'  => '#ffffff',
        'output' => array(
	        array(
	            'element'  => '.top_bar, #nav_wrapper',
	            'property' => 'border-color',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 7,
	    'js_vars'   => array(
			array(
				'element'  => '.top_bar, #nav_wrapper',
				'function' => 'css',
				'property' => 'border-color',
			),
		)
    );
    
    $controls[] = array(
        'type'     => 'title',
        'settings'  => 'tg_menu_icon_colors_title',
        'label'    => esc_html__('Icon Colors Settings', 'hoteller' ),
        'section'  => 'menu_color',
	    'priority' => 7,
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_menu_cart_bg',
        'label'    => esc_html__('Cart Counter Background', 'hoteller' ),
        'section'  => 'menu_color',
	    'default'     => '#1C58F6',
	    'output' => array(
	        array(
	            'element'  => '.header_cart_wrapper .cart_count',
	            'property' => 'background-color',
	        ),
	    ),
	    'priority' => 8,
	    'transport' 	 => 'postMessage',
	    'js_vars'   => array(
			array(
				'element'  => '.header_cart_wrapper .cart_count',
				'function' => 'css',
				'property' => 'background-color',
			),
		),
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_menu_cart_font_color',
        'label'    => esc_html__('Cart Counter Font Color', 'hoteller' ),
        'section'  => 'menu_color',
        'default'  => '#ffffff',
        'output' => array(
	        array(
	            'element'  => '.header_cart_wrapper .cart_count',
	            'property' => 'color',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 8,
	    'js_vars'   => array(
			array(
				'element'  => '.header_cart_wrapper .cart_count',
				'function' => 'css',
				'property' => 'color',
			),
		)
    );
    
    $controls[] = array(
        'type'     => 'title',
        'settings'  => 'tg_submenu_font_title',
        'label'    => esc_html__('Typography Settings', 'hoteller' ),
        'section'  => 'menu_submenu',
	    'priority' => 8,
    );
    
    $controls[] = array(
        'type'     => 'typography',
        'settings'  => 'tg_submenu_typography',
        'label'    => esc_html__('Sub Menu Typography', 'hoteller' ),
        'section'  => 'menu_submenu',
        'default'  => array(
            'font-family'    => 'Jost',
            'variant'        => '400',
            'font-size'      => '14px',
            'letter-spacing' => '0px',
            'text-transform' => 'none',
        ),
        'output' => array(
            array(
                'element'  => '#menu_wrapper .nav ul li ul li a, #menu_wrapper div .nav li ul li a, #menu_wrapper div .nav li.current-menu-parent ul li a',
            ),
        ),
        'priority' => 12,
    );
    
    $controls[] = array(
        'type'     => 'title',
        'settings'  => 'tg_submenu_color_title',
        'label'    => esc_html__('Color Settings', 'hoteller' ),
        'section'  => 'menu_submenu',
	    'priority' => 12,
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_submenu_font_color',
        'label'    => esc_html__('Sub Menu Font Color', 'hoteller' ),
        'section'  => 'menu_submenu',
        'default'  => '#ffffff',
        'output' => array(
	        array(
	            'element'  => '#menu_wrapper .nav ul li ul li a, #menu_wrapper div .nav li ul li a, #menu_wrapper div .nav li.current-menu-parent ul li a, #menu_wrapper div .nav li.current-menu-parent ul li.current-menu-item a, #menu_wrapper .nav ul li.megamenu ul li ul li a, #menu_wrapper div .nav li.megamenu ul li ul li a',
	            'property' => 'color',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 13,
	    'js_vars'   => array(
			array(
				'element'  => '#menu_wrapper .nav ul li ul li a, #menu_wrapper div .nav li ul li a, #menu_wrapper div .nav li.current-menu-parent ul li a, #menu_wrapper div .nav li.current-menu-parent ul li.current-menu-item a, #menu_wrapper .nav ul li.megamenu ul li ul li a, #menu_wrapper div .nav li.megamenu ul li ul li a',
				'function' => 'css',
				'property' => 'color',
			),
		)
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_submenu_hover_font_color',
        'label'    => esc_html__('Sub Menu Hover State Font Color', 'hoteller' ),
        'section'  => 'menu_submenu',
        'default'  => '#ffffff',
        'output' => array(
	        array(
	            'element'  => '#menu_wrapper .nav ul li ul li a:hover, #menu_wrapper div .nav li ul li a:hover, #menu_wrapper div .nav li.current-menu-parent ul li a:hover, #menu_wrapper .nav ul li.megamenu ul li ul li a:hover, #menu_wrapper div .nav li.megamenu ul li ul li a:hover, #menu_wrapper .nav ul li.megamenu ul li ul li a:active, #menu_wrapper div .nav li.megamenu ul li ul li a:active, #menu_wrapper div .nav li.current-menu-parent ul li.current-menu-item  a:hover',
	            'property' => 'color',
	        ),
	        array(
	            'element'  => '#menu_wrapper .nav ul li ul li a:before, #menu_wrapper div .nav li ul li > a:before, #wrapper.transparent .top_bar:not(.scroll) #menu_wrapper div .nav ul li ul li a:before',
	            'property' => 'background-color',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 14,
	    'js_vars'   => array(
			array(
				'element'  => '#menu_wrapper .nav ul li ul li a:hover, #menu_wrapper div .nav li ul li a:hover, #menu_wrapper div .nav li.current-menu-parent ul li a:hover, #menu_wrapper .nav ul li.megamenu ul li ul li a:hover, #menu_wrapper div .nav li.megamenu ul li ul li a:hover, #menu_wrapper .nav ul li.megamenu ul li ul li a:active, #menu_wrapper div .nav li.megamenu ul li ul li a:active, #menu_wrapper div .nav li.current-menu-parent ul li.current-menu-item  a:hover',
				'function' => 'css',
				'property' => 'color',
			),
			array(
	            'element'  => '#menu_wrapper .nav ul li ul li a:before, #menu_wrapper div .nav li ul li > a:before, #wrapper.transparent .top_bar:not(.scroll) #menu_wrapper div .nav ul li ul li a:before',
	            'property' => 'background-color',
	        ),
		)
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_submenu_bg_color',
        'label'    => esc_html__('Sub Menu Background Color', 'hoteller' ),
        'section'  => 'menu_submenu',
        'default'  => '#222222',
        'output' => array(
	        array(
	            'element'  => '#menu_wrapper .nav ul li ul, #menu_wrapper div .nav li ul',
	            'property' => 'background',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 16,
	    'js_vars'   => array(
			array(
				'element'  => '#menu_wrapper .nav ul li ul, #menu_wrapper div .nav li ul',
				'function' => 'css',
				'property' => 'background',
			),
		)
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_submenu_border_color',
        'label'    => esc_html__('Sub Menu Border Color', 'hoteller' ),
        'section'  => 'menu_submenu',
        'default'  => '#222222',
        'output' => array(
	        array(
	            'element'  => '#menu_wrapper .nav ul li ul, #menu_wrapper div .nav li ul',
	            'property' => 'border-color',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 17,
	    'js_vars'   => array(
			array(
				'element'  => '#menu_wrapper .nav ul li ul, #menu_wrapper div .nav li ul',
				'function' => 'css',
				'property' => 'border-color',
			),
		)
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_megamenu_header_color',
        'label'    => esc_html__('Mega Menu Header Font Color', 'hoteller' ),
        'section'  => 'menu_megamenu',
        'default'  => '#ffffff',
        'output' => array(
	        array(
	            'element'  => '#menu_wrapper div .nav li.megamenu ul li > a, #menu_wrapper div .nav li.megamenu ul li > a:hover, #menu_wrapper div .nav li.megamenu ul li > a:active, #menu_wrapper div .nav li.megamenu ul li.current-menu-item > a',
	            'property' => 'color',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 18,
	    'js_vars'   => array(
			array(
				'element'  => '#menu_wrapper div .nav li.megamenu ul li > a, #menu_wrapper div .nav li.megamenu ul li > a:hover, #menu_wrapper div .nav li.megamenu ul li > a:active, #menu_wrapper div .nav li.megamenu ul li.current-menu-item > a',
				'function' => 'css',
				'property' => 'color',
			),
		)
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_megamenu_border_color',
        'label'    => esc_html__('Mega Menu Border Color', 'hoteller' ),
        'section'  => 'menu_megamenu',
        'default'  => '#333333',
        'output' => array(
	        array(
	            'element'  => '#menu_wrapper div .nav li.megamenu ul li',
	            'property' => 'border-color',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 20,
	    'js_vars'   => array(
			array(
				'element'  => '#menu_wrapper div .nav li.megamenu ul li',
				'function' => 'css',
				'property' => 'border-color',
			),
		)
    );
    
    $controls[] = array(
        'type'     => 'toggle',
        'settings'  => 'tg_topbar',
        'label'    => esc_html__('Display Top Bar', 'hoteller' ),
        'description' => esc_html__('Enable this option to display top bar above main menu', 'hoteller' ),
        'section'  => 'menu_topbar',
        'default'  => 0,
	    'priority' => 21,
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_topbar_bg_color',
        'label'    => esc_html__('Top Bar Background Color', 'hoteller' ),
        'section'  => 'menu_topbar',
        'default'  => '#ffffff',
        'output' => array(
	        array(
	            'element'  => '.above_top_bar',
	            'property' => 'background',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 22,
	    'js_vars'   => array(
			array(
				'element'  => '.above_top_bar',
				'function' => 'css',
				'property' => 'background',
			),
		)
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_topbar_font_color',
        'label'    => esc_html__('Top Bar Menu Font Color', 'hoteller' ),
        'section'  => 'menu_topbar',
        'default'  => '#222222',
        'output' => array(
	        array(
	            'element'  => '#top_menu li a, .top_contact_info, .top_contact_info i, .top_contact_info a, .top_contact_info a:hover, .top_contact_info a:active',
	            'property' => 'color',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 23,
	    'js_vars'   => array(
			array(
				'element'  => '#top_menu li a, .top_contact_info, .top_contact_info i, .top_contact_info a, .top_contact_info a:hover, .top_contact_info a:active',
				'function' => 'css',
				'property' => 'color',
			),
		)
    );
    
    $controls[] = array(
        'type'     => 'text',
        'settings'  => 'tg_menu_contact_address',
        'label'    => esc_html__('Contact Address (Optional)', 'hoteller' ),
        'description' => esc_html__('Enter your hotel address.', 'hoteller' ),
        'section'  => 'menu_contact',
        'default'  => 'Via Serlas 546, 6700 St. Moritz, Switzerland',
	    'priority' => 26,
    );
    
    $controls[] = array(
        'type'     => 'text',
        'settings'  => 'tg_menu_contact_hours',
        'label'    => esc_html__('Contact Hours (Optional)', 'hoteller' ),
        'description' => esc_html__('Enter your hotel contact hours.', 'hoteller' ),
        'section'  => 'menu_contact',
        'default'  => 'Mon-Fri 09.00 - 17.00',
	    'priority' => 26,
    );
    
    $controls[] = array(
        'type'     => 'text',
        'settings'  => 'tg_menu_contact_number',
        'label'    => esc_html__('Contact Phone Number (Optional)', 'hoteller' ),
        'description' => esc_html__('Enter your hotel contact phone number.', 'hoteller' ),
        'section'  => 'menu_contact',
        'default'  => '+30-85456-6743',
	    'priority' => 27,
    );
    
    $controls[] = array(
        'type'     => 'toggle',
        'settings'  => 'tg_topbar_social_link',
        'label'    => esc_html__('Open Top Bar Social Icons link in new window', 'hoteller' ),
        'description' => esc_html__('Check this to open top bar social icons link in new window', 'hoteller' ),
        'section'  => 'menu_contact',
        'default'  => 1,
	    'priority' => 28,
    );
    
    $controls[] = array(
        'type'     => 'title',
        'settings'  => 'tg_sidemenu_general_title',
        'label'    => esc_html__('General Settings', 'hoteller' ),
        'section'  => 'menu_sidemenu',
        'priority' => 30,
    );
    
    $controls[] = array(
        'type'     => 'toggle',
        'settings'  => 'tg_sidemenu',
        'label'    => esc_html__('Enable Side Menu on Desktop', 'hoteller' ),
        'description' => 'Check this option to enable side menu on desktop',
        'section'  => 'menu_sidemenu',
        'default'  => 0,
	    'priority' => 31,
    );
    
    $controls[] = array(
        'type'     => 'toggle',
        'settings'  => 'tg_sidemenu_close',
        'label'    => esc_html__('Display Close Menu Button', 'hoteller' ),
        'description' => 'Check this option to display close menu button when side menu is opened',
        'section'  => 'menu_sidemenu',
        'default'  => 0,
	    'priority' => 31,
    );
    $controls[] = array(
        'type'     => 'title',
        'settings'  => 'tg_sidemenu_font_title',
        'label'    => esc_html__('Typography Settings', 'hoteller' ),
        'section'  => 'menu_sidemenu',
	    'priority' => 31,
    );
    
    $controls[] = array(
        'type'     => 'typography',
        'settings'  => 'tg_sidemenu_typography',
        'label'    => esc_html__('Side Menu Typography', 'hoteller' ),
        'section'  => 'menu_sidemenu',
        'default'  => array(
            'font-family'    => 'Jost',
            'variant'        => '500',
            'font-size'      => '20px',
            'line-height'    => '2',
            'letter-spacing' => '0px',
            'text-transform' => 'none',
        ),
        'output' => array(
            array(
                'element'  => '.mobile_main_nav li a, #sub_menu li a',
            ),
        ),
        'priority' => 35
    );
    
    $controls[] = array(
        'type'     => 'title',
        'settings'  => 'tg_sidemenu_bg_title',
        'label'    => esc_html__('Color Settings', 'hoteller' ),
        'section'  => 'menu_sidemenu',
	    'priority' => 35,
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_sidemenu_bg',
        'label'    => esc_html__('Side Menu Background', 'hoteller' ),
        'section'  => 'menu_sidemenu',
	    'default'  => '#f9f9f9',
	    'output' => array(
	        array(
	            'element'  => '.mobile_menu_wrapper',
	            'property' => 'background-color',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'js_vars'   => array(
			array(
	            'element'  => '.mobile_menu_wrapper',
	            'property' => 'background-color',
	        ),
		),
	    'priority' => 36,
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_sidemenu_font_color',
        'label'    => esc_html__('Side Menu Font Color', 'hoteller' ),
        'section'  => 'menu_sidemenu',
        'default'  => '#222222',
        'output' => array(
	        array(
	            'element'  => '.mobile_main_nav li a, #sub_menu li a, .mobile_menu_wrapper .sidebar_wrapper a, .mobile_menu_wrapper .sidebar_wrapper, #close_mobile_menu i, .mobile_menu_wrapper .social_wrapper ul li a, .fullmenu_content #copyright, .mobile_menu_wrapper .sidebar_wrapper h2.widgettitle',
	            'property' => 'color',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 37,
	    'js_vars'   => array(
			array(
				'element'  => '.mobile_main_nav li a, #sub_menu li a, .mobile_menu_wrapper .sidebar_wrapper a, .mobile_menu_wrapper .sidebar_wrapper, #close_mobile_menu i, .mobile_menu_wrapper .social_wrapper ul li a, .fullmenu_content #copyright, .mobile_menu_wrapper .sidebar_wrapper h2.widgettitle',
				'function' => 'css',
				'property' => 'color',
			),
		)
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_sidemenu_font_hover_color',
        'label'    => esc_html__('Side Menu Hover State Font Color', 'hoteller' ),
        'section'  => 'menu_sidemenu',
        'default'  => '#222222',
        'output' => array(
	        array(
	            'element'  => '.mobile_main_nav li a:hover, .mobile_main_nav li a:active, #sub_menu li a:hover, #sub_menu li a:active, .mobile_menu_wrapper .social_wrapper ul li a:hover',
	            'property' => 'color',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 38,
	    'js_vars'   => array(
			array(
				'element'  => '.mobile_main_nav li a:hover, .mobile_main_nav li a:active, #sub_menu li a:hover, #sub_menu li a:active, .mobile_menu_wrapper .social_wrapper ul li a:hover',
				'function' => 'css',
				'property' => 'color',
			),
		)
    );
    $controls[] = array(
        'type'     => 'select',
        'settings'  => 'hoteller_fullmenu_default',
        'label'    => esc_html__('Default Fullscreen Menu', 'hoteller' ),
        'description' => esc_html__('Select default fullscreen menu for all pages', 'hoteller' ),
        'section'  => 'menu_fullmenu',
        'default'  => '',
        'choices'  => $hoteller_fullmenu_select,
        'priority' => 1,
    );
    
    $controls[] = array(
        'type'     => 'select',
        'settings'  => 'hoteller_fullmenu_effect',
        'label'    => esc_html__('Fullscreen Menu Effect', 'hoteller' ),
        'description' => esc_html__('Select transition effect for fullscreen menu', 'hoteller' ),
        'section'  => 'menu_fullmenu',
        'default'  => '',
        'choices'  => $hoteller_fullmenu_effect_select,
        'priority' => 2,
    );
    
    //End Menu Tab Settings
    
    //Register Header Tab Settings
    $controls[] = array(
        'type'     => 'title',
        'settings'  => 'tg_page_header_bg_title',
        'label'    => esc_html__('Background Image Settings', 'hoteller' ),
        'section'  => 'header_background',
	    'priority' => 1,
    );
    
    $controls[] = array(
        'type'     => 'slider',
        'settings'  => 'tg_page_title_bg_height',
        'label'    => esc_html__('Page Title Background Image Height (in px)', 'hoteller' ),
        'section'  => 'header_background',
        'default'  => 600,
        'choices' => array( 'min' => 100, 'max' => 1000, 'step' => 5 ),
        'output' => array(
	        array(
	            'element'  => '#page_caption.hasbg',
	            'property' => 'height',
	            'units'    => 'px',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 2,
	    'js_vars'   => array(
			array(
				'element'  => '#page_caption.hasbg',
				'function' => 'css',
				'property' => 'height',
				'units'    => 'px',
			),
		)
    );
    
    $controls[] = array(
        'type'     => 'toggle',
        'settings'  => 'tg_page_header_bg_parallax',
        'label'    => esc_html__('Add Parallax Effect When Scroll', 'hoteller' ),
        'description' => esc_html__('Enable this option to add parallax effect to header background image when scrolling pass it', 'hoteller' ),
        'section'  => 'header_background',
        'default'  => '1',
	    'priority' => 2,
    );
    
    $controls[] = array(
        'type'     => 'title',
        'settings'  => 'tg_page_header_bgcolor_title',
        'label'    => esc_html__('Background Color Settings', 'hoteller' ),
        'section'  => 'header_background',
	    'priority' => 2,
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_page_header_bg_color',
        'label'    => esc_html__('Page Header Background Color', 'hoteller' ),
        'section'  => 'header_background',
        'default'  => '#ffffff',
        'output' => array(
	        array(
	            'element'  => '#page_caption',
	            'property' => 'background-color',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 3,
	    'js_vars'   => array(
			array(
				'element'  => '#page_caption',
				'function' => 'css',
				'property' => 'background-color',
			),
		)
    );
    
    $controls[] = array(
        'type'     => 'title',
        'settings'  => 'tg_page_title_layout_title',
        'label'    => esc_html__('Layout Settings', 'hoteller' ),
        'section'  => 'header_title',
        'priority' => 3,
    );
    
    $controls[] = array(
        'type'     => 'radio',
        'settings'  => 'tg_page_title_font_alignment',
        'label'    => esc_html__('Page Title Alignment', 'hoteller' ),
        'description' => esc_html__('Select alignment for page title', 'hoteller' ),
        'section'  => 'header_title',
        'default'  => 'center',
        'choices'  => $tg_text_alignment,
        'output' => array(
	        array(
	            'element'  => '#page_caption .page_title_wrapper .page_title_inner',
	            'property' => 'text-align',
	        ),
	    ),
	    'js_vars'   => array(
			array(
				'element'  => '#page_caption .page_title_wrapper .page_title_inner',
				'function' => 'css',
				'property' => 'text-align',
			),
		),
	    'transport' 	 => 'postMessage',
	    'priority' => 4,
    );
    
    $controls[] = array(
        'type'     => 'slider',
        'settings'  => 'tg_page_header_padding_top',
        'label'    => esc_html__('Page Header Padding Top', 'hoteller' ),
        'section'  => 'header_title',
        'default'  => 65,
        'choices' => array( 'min' => 0, 'max' => 200, 'step' => 1 ),
        'output' => array(
	        array(
	            'element'  => '#page_caption',
	            'property' => 'padding-top',
	            'units'    => 'px',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 4,
	    'js_vars'   => array(
			array(
				'element'  => '#page_caption',
				'function' => 'css',
				'property' => 'padding-top',
				'units'    => 'px',
			),
		)
    );
    
    $controls[] = array(
        'type'     => 'slider',
        'settings'  => 'tg_page_header_padding_bottom',
        'label'    => esc_html__('Page Header Padding Bottom', 'hoteller' ),
        'section'  => 'header_title',
        'default'  => 65,
        'choices' => array( 'min' => 0, 'max' => 200, 'step' => 1 ),
        'output' => array(
	        array(
	            'element'  => '#page_caption',
	            'property' => 'padding-bottom',
	            'units'    => 'px',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 4,
	    'js_vars'   => array(
			array(
				'element'  => '#page_caption',
				'function' => 'css',
				'property' => 'padding-bottom',
				'units'    => 'px',
			),
		)
    );
    
    $controls[] = array(
        'type'     => 'slider',
        'settings'  => 'tg_page_header_marging_bottom',
        'label'    => esc_html__('Page Header Margin Bottom', 'hoteller' ),
        'section'  => 'header_title',
        'default'  => 70,
        'choices' => array( 'min' => 0, 'max' => 200, 'step' => 1 ),
        'output' => array(
	        array(
	            'element'  => '#page_caption',
	            'property' => 'margin-bottom',
	            'units'    => 'px',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 4,
	    'js_vars'   => array(
			array(
				'element'  => '#page_caption',
				'function' => 'css',
				'property' => 'margin-bottom',
				'units'    => 'px',
			),
		)
    );
    
    $controls[] = array(
        'type'     => 'title',
        'settings'  => 'tg_page_title_typography_title',
        'label'    => esc_html__('Typography Settings', 'hoteller' ),
        'section'  => 'header_title',
        'priority' => 6,
    );
    
    $controls[] = array(
        'type'     => 'typography',
        'settings'  => 'tg_page_title_typography',
        'label'    => esc_html__('Page Title Typography', 'hoteller' ),
        'section'  => 'header_title',
        'default'  => array(
            'font-family'    => 'Oswald',
            'variant'        => '400',
            'font-size'      => '60px',
            'line-height'    => '1.2',
            'letter-spacing' => '-1px',
            'text-transform' => 'none',
        ),
        'output' => array(
            array(
                'element'  => '#page_caption h1, .post_caption h1',
            ),
        ),
        'priority' => 8
    );
    
    $controls[] = array(
        'type'     => 'select',
        'settings'  => 'tg_page_title_font_style',
        'label'    => esc_html__('Page Title Font Style', 'hoteller' ),
        'section'  => 'header_title',
        'default'  => 'normal',
        'choices'  => $tg_font_style,
        'output' => array(
            array(
                'element'  => '#page_caption h1, .post_caption h1',
                'property' => 'font-style',
            ),
        ),
        'transport' 	 => 'postMessage',
        'priority' => 8,
        'js_vars'   => array(
            array(
                'element'  => '#page_caption h1, .post_caption h1',
                'function' => 'css',
                'property' => 'font-style',
            ),
        )
    );
    
    $controls[] = array(
        'type'     => 'title',
        'settings'  => 'tg_page_title_color_title',
        'label'    => esc_html__('Color Settings', 'hoteller' ),
        'section'  => 'header_title',
        'priority' => 8,
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_page_title_font_color',
        'label'    => esc_html__('Page Title Font Color', 'hoteller' ),
        'section'  => 'header_title',
        'default'  => '#333333',
        'output' => array(
	        array(
	            'element'  => '#page_caption h1, .post_caption h1',
	            'property' => 'color',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 9,
	    'js_vars'   => array(
			array(
				'element'  => '#page_caption h1, .post_caption h1',
				'function' => 'css',
				'property' => 'color',
			),
		),
    );
    
    $controls[] = array(
        'type'     => 'title',
        'settings'  => 'tg_page_tagline_typography_title',
        'label'    => esc_html__('Typography Settings', 'hoteller' ),
        'section'  => 'header_tagline',
        'priority' => 9,
    );
    
    $controls[] = array(
        'type'     => 'typography',
        'settings'  => 'tg_page_tagline_typography',
        'label'    => esc_html__('Page Tagline Typography', 'hoteller' ),
        'section'  => 'header_tagline',
        'default'  => array(
            'font-family'    => 'Jost',
            'variant'        => '400',
            'font-size'      => '12px',
            'line-height'    => '1.7',
            'letter-spacing' => '3px',
            'text-transform' => 'none',
        ),
        'output' => array(
            array(
                'element'  => '.page_tagline, .post_detail, .thumb_content span, .portfolio_desc .portfolio_excerpt, .testimonial_customer_position, .testimonial_customer_company',
            ),
        ),
        'priority' => 13
    );
    
    $controls[] = array(
        'type'     => 'title',
        'settings'  => 'tg_page_tagline_color_title',
        'label'    => esc_html__('Color Settings', 'hoteller' ),
        'section'  => 'header_tagline',
        'priority' => 13,
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_page_tagline_font_color',
        'label'    => esc_html__('Page Tagline Font Color', 'hoteller' ),
        'section'  => 'header_tagline',
        'default'  => '#9B9B9B',
        'output' => array(
            array(
                'element'  => '.page_tagline, .thumb_content span, .portfolio_desc .portfolio_excerpt, .testimonial_customer_position, .testimonial_customer_company, .post_detail.single_post',
                'property' => 'color',
            ),
        ),
        'transport' 	 => 'postMessage',
        'priority' => 14,
        'js_vars'   => array(
            array(
                'element'  => '.page_tagline, .thumb_content span, .portfolio_desc .portfolio_excerpt, .testimonial_customer_position, .testimonial_customer_company, .post_detail.single_post',
                'function' => 'css',
                'property' => 'color',
            ),
        ),
    );
    //End Header Tab Settings
    
    $controls[] = array(
        'type'     => 'toggle',
        'settings'  => 'tg_sidebar_sticky',
        'label'    => esc_html__('Enable Sticky Sidebar', 'hoteller' ),
        'description' => esc_html__('Check this to displays sidebar fixed when scrolling.', 'hoteller' ),
        'section'  => 'sidebar_general',
        'default'  => 1,
	    'priority' => 11,
    );
    
    $controls[] = array(
        'type'     => 'typography',
        'settings'  => 'tg_sidebar_title_typography',
        'label'    => esc_html__('Widget Title Typography', 'hoteller' ),
        'section'  => 'sidebar_typography',
        'default'  => array(
            'font-family'    => 'Jost',
            'variant'        => '600',
            'font-size'      => '13px',
            'line-height'    => '1.7',
            'letter-spacing' => '2px',
            'text-transform' => 'uppercase',
        ),
        'output' => array(
            array(
                'element'  => '#page_content_wrapper .sidebar .content .sidebar_widget li h2.widgettitle, h2.widgettitle, h5.widgettitle, form.mphb_sc_checkout-form h3, form.mphb_sc_checkout-form h4, .sidebar_widget li.widget h2',
            ),
        ),
        'priority' => 5
    );
    
    $controls[] = array(
        'type'     => 'select',
        'settings'  => 'tg_sidebar_font_style',
        'label'    => esc_html__('Widget Title Font Style', 'hoteller' ),
        'section'  => 'sidebar_typography',
        'default'  => 'normal',
        'choices'  => $tg_font_style,
        'output' => array(
	        array(
	            'element'  => '#page_content_wrapper .sidebar .content .sidebar_widget li h2.widgettitle, h2.widgettitle, h5.widgettitle, form.mphb_sc_checkout-form h3, form.mphb_sc_checkout-form h4',
	            'property' => 'font-style',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 5,
	    'js_vars'   => array(
			array(
				'element'  => '#page_content_wrapper .sidebar .content .sidebar_widget li h2.widgettitle, h2.widgettitle, h5.widgettitle, form.mphb_sc_checkout-form h3, form.mphb_sc_checkout-form h4',
				'function' => 'css',
				'property' => 'font-style',
			),
		)
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_sidebar_font_color',
        'label'    => esc_html__('Sidebar Font Color', 'hoteller' ),
        'section'  => 'sidebar_color',
        'default'  => '#222222',
        'output' => array(
	        array(
	            'element'  => '#page_content_wrapper .inner .sidebar_wrapper .sidebar .content, .page_content_wrapper .inner .sidebar_wrapper .sidebar .content',
	            'property' => 'color',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 6,
	    'js_vars'   => array(
			array(
				'element'  => '#page_content_wrapper .inner .sidebar_wrapper .sidebar .content, .page_content_wrapper .inner .sidebar_wrapper .sidebar .content',
				'function' => 'css',
				'property' => 'color',
			),
		)
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_sidebar_link_color',
        'label'    => esc_html__('Sidebar Link Color', 'hoteller' ),
        'section'  => 'sidebar_color',
        'default'  => '#333333',
        'output' => array(
	        array(
	            'element'  => '#page_content_wrapper .inner .sidebar_wrapper a:not(.button), .page_content_wrapper .inner .sidebar_wrapper a:not(.button)',
	            'property' => 'color',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 7,
	    'js_vars'   => array(
			array(
				'element'  => '#page_content_wrapper .inner .sidebar_wrapper a:not(.button), .page_content_wrapper .inner .sidebar_wrapper a:not(.button)',
				'function' => 'css',
				'property' => 'color',
			),
		)
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_sidebar_hover_link_color',
        'label'    => esc_html__('Sidebar Hover Link Color', 'hoteller' ),
        'section'  => 'sidebar_color',
        'default'  => '#222222',
        'output' => array(
	        array(
	            'element'  => '#page_content_wrapper .inner .sidebar_wrapper a:hover:not(.button), #page_content_wrapper .inner .sidebar_wrapper a:active:not(.button), .page_content_wrapper .inner .sidebar_wrapper a:hover:not(.button), .page_content_wrapper .inner .sidebar_wrapper a:active:not(.button)',
	            'property' => 'color',
	        ),
	        array(
	            'element'  => '#page_content_wrapper .inner .sidebar_wrapper a:not(.button):before',
	            'property' => 'background-color',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 8,
	    'js_vars'   => array(
			array(
				'element'  => '#page_content_wrapper .inner .sidebar_wrapper a:hover:not(.button), #page_content_wrapper .inner .sidebar_wrapper a:active:not(.button), .page_content_wrapper .inner .sidebar_wrapper a:hover:not(.button), .page_content_wrapper .inner .sidebar_wrapper a:active:not(.button)',
				'function' => 'css',
				'property' => 'color',
			),
			array(
	            'element'  => '#page_content_wrapper .inner .sidebar_wrapper a:not(.button):before',
	            'function' => 'css',
	            'property' => 'background-color',
	        ),
		)
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_sidebar_title_color',
        'label'    => esc_html__('Sidebar Widget Title Font Color', 'hoteller' ),
        'section'  => 'sidebar_color',
        'default'  => '#333333',
        'output' => array(
	        array(
	            'element'  => '#page_content_wrapper .sidebar .content .sidebar_widget li h2.widgettitle, h2.widgettitle, h5.widgettitle, form.mphb_sc_checkout-form h3, form.mphb_sc_checkout-form h4',
	            'property' => 'color',
	        ),
	        array(
	            'element'  => '#page_content_wrapper .sidebar .content .sidebar_widget li h2.widgettitle, h2.widgettitle, h5.widgettitle',
	            'property' => 'border-color',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 9,
	    'js_vars'   => array(
			array(
				'element'  => '#page_content_wrapper .sidebar .content .sidebar_widget li h2.widgettitle, h2.widgettitle, h5.widgettitle, form.mphb_sc_checkout-form h3, form.mphb_sc_checkout-form h4',
				'function' => 'css',
				'property' => 'color',
			),
			array(
				'element'  => '#page_content_wrapper .sidebar .content .sidebar_widget li h2.widgettitle, h2.widgettitle, h5.widgettitle',
				'function' => 'css',
				'property' => 'border-color',
			),
		)
    );
    //End Sidebar Tab Settings
    
    //Register Footer Tab Settings
    $controls[] = array(
        'type'     => 'title',
        'settings'  => 'tg_footer_content_title',
        'label'    => esc_html__('Footer Content Settings', 'hoteller' ),
        'section'  => 'footer_general',
	    'priority' => 0,
    );
    
    $controls[] = array(
        'type'     => 'radio',
        'settings'  => 'tg_footer_content',
        'label'    => esc_html__('Display Footer Content From', 'hoteller' ),
        'description' => esc_html__('Select how theme get main footer content', 'hoteller' ),
        'section'  => 'footer_general',
        'default'  => 'sidebar',
        'choices'  => $tg_footer_content,
	    'priority' => 1,
    );
    
    $controls[] = array(
        'type'     => 'select',
        'settings'  => 'tg_footer_content_default',
        'label'    => esc_html__('Default Footer Content', 'hoteller' ),
        'description' => esc_html__('Select default footer content for general pages & posts', 'hoteller' ),
        'section'  => 'footer_general',
        'default'  => '',
        'choices'  => $tg_footers_select,
	    'priority' => 1,
    );
    
    $controls[] = array(
        'type'     => 'title',
        'settings'  => 'hoteller_title',
        'label'    => esc_html__('Footer Sidebar Settings', 'hoteller' ),
        'section'  => 'footer_general',
	    'priority' => 2,
    );
    
    $controls[] = array(
        'type'     => 'image',
        'settings'  => 'tg_footer_retina_logo',
        'label'    => esc_html__('Footer Retina Logo', 'hoteller' ),
        'description' => esc_html__('Retina Ready Image logo. It should be 2x size of normal logo. For example 200x60px logo will displays at 100x30px', 'hoteller' ),
        'section'  => 'footer_general',
	    'default'  => '',
	    'priority' => 3,
    );
    
    $controls[] = array(
        'type'     => 'radio',
        'settings'  => 'tg_footer_sidebar',
        'label'    => esc_html__('Footer Sidebar Columns', 'hoteller' ),
        'section'  => 'footer_general',
        'default'  => '4',
        'choices'  => $tg_copyright_column,
	    'priority' => 3,
    );
    
    $controls[] = array(
        'type'     => 'toggle',
        'settings'  => 'tg_footer_social_link',
        'label'    => esc_html__('Open Footer Social Icons link in new window', 'hoteller' ),
        'description' => esc_html__('Check this to open footer social icons link in new window', 'hoteller' ),
        'section'  => 'footer_general',
        'default'  => 1,
	    'priority' => 3,
    );
    
    $controls[] = array(
        'type'     => 'title',
        'settings'  => 'tg_footer_typography_title',
        'label'    => esc_html__('Footer Typography Settings', 'hoteller' ),
        'section'  => 'footer_general',
        'priority' => 9,
    );
    
    $controls[] = array(
        'type'     => 'typography',
        'settings'  => 'tg_footer_typography',
        'label'    => esc_html__('Footer Typography', 'hoteller' ),
        'section'  => 'footer_general',
        'default'  => array(
            'font-family'    => 'Jost',
            'variant'        => '500',
            'font-size'      => '17px',
            'line-height'    => '1.7',
            'letter-spacing' => '0px',
            'text-transform' => 'none',
        ),
        'output' => array(
            array(
                'element'  => '#footer',
            ),
        ),
        'priority' => 10
    );
    
    $controls[] = array(
        'type'     => 'slider',
        'settings'  => 'tg_footer_margin_top',
        'label'    => esc_html__('Footer Margin Top (in px)', 'hoteller' ),
        'section'  => 'footer_general',
        'default'  => 0,
        'choices' => array( 'min' => 0, 'max' => 300, 'step' => 5 ),
        'output' => array(
	        array(
	            'element'  => '#footer_wrapper',
	            'property' => 'margin-top',
	            'units'    => 'px',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 10,
	    'js_vars'   => array(
			array(
				'element'  => '#footer_wrapper',
				'function' => 'css',
				'property' => 'margin-top',
				'units'    => 'px',
			),
		)
    );
    
    $controls[] = array(
        'type'     => 'title',
        'settings'  => 'tg_footer_colors_title',
        'label'    => esc_html__('Footer Colors Settings', 'hoteller' ),
        'section'  => 'footer_color',
	    'priority' => 0,
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_footer_bg',
        'label'    => esc_html__('Footer Background', 'hoteller' ),
        'section'  => 'footer_color',
	    'priority' => 1,
	    'default'  => '#ffffff',
	    'output' => array(
	        array(
	            'element'  => '.footer_bar, #footer, #footer input[type=text], #footer input[type=password], #footer input[type=email], #footer input[type=url], #footer input[type=tel], #footer input[type=date], #footer textarea, #footer select, #footer_photostream',
	            'property' => 'background-color',
	        ),
	    ),
	    'js_vars'   => array(
			array(
				'element'  => '.footer_bar, #footer, #footer input[type=text], #footer input[type=password], #footer input[type=email], #footer input[type=url], #footer input[type=tel], #footer input[type=date], #footer textarea, #footer select, #footer_photostream',
				'function' => 'css',
				'property' => 'background-color',
			),
		),
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_footer_font_color',
        'label'    => esc_html__('Footer Font Color', 'hoteller' ),
        'section'  => 'footer_color',
        'default'  => '#222222',
        'output' => array(
	        array(
	            'element'  => '#footer, #copyright, #footer_menu li a, #footer_menu li a:hover, #footer_menu li a:active, #footer input[type=text], #footer input[type=password], #footer input[type=email], #footer input[type=url], #footer input[type=tel], #footer input[type=date], #footer textarea, #footer select, #footer blockquote, #footer input::placeholder',
	            'property' => 'color',
	        ),
	        array(
	            'element'  => '#footer .input_effect ~ .focus-border',
	            'property' => 'background-color',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 10,
	    'js_vars'   => array(
			array(
				'element'  => '#footer, #copyright, #footer_menu li a, #footer_menu li a:hover, #footer_menu li a:active, #footer input[type=text], #footer input[type=password], #footer input[type=email], #footer input[type=url], #footer input[type=tel], #footer input[type=date], #footer textarea, #footer select, #footer blockquote, #footer input::placeholder',
				'function' => 'css',
				'property' => 'color',
			),
			array(
	            'element'  => '#footer .input_effect ~ .focus-border',
	            'property' => 'background-color',
	        ),
		),
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_footer_link_color',
        'label'    => esc_html__('Footer Link Color', 'hoteller' ),
        'section'  => 'footer_color',
        'default'  => '#222222',
        'output' => array(
	        array(
	            'element'  => '#copyright a, #copyright a:active, #footer a:not(.button), #footer a:active:not(.button, .elementor-button), #footer .sidebar_widget li h2.widgettitle, #footer_photostream a',
	            'property' => 'color',
	        ),
	        array(
	            'element'  => '#footer .sidebar_widget li h2.widgettitle',
	            'property' => 'border-color',
	        ),
	    ),
	    'js_vars'   => array(
			array(
				'element'  => '#copyright a, #copyright a:active, #footer a:not(.button), #footer a:active:not(.button, .elementor-button), #footer .sidebar_widget li h2.widgettitle, #footer_photostream a',
				'function' => 'css',
				'property' => 'color',
			),
			array(
	            'element'  => '#footer .sidebar_widget li h2.widgettitle',
	            'function' => 'css',
	            'property' => 'border-color',
	        ),
		),
	    'transport' 	 => 'postMessage',
	    'priority' => 11,
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_footer_hover_link_color',
        'label'    => esc_html__('Footer Hover Link Color', 'hoteller' ),
        'section'  => 'footer_color',
        'default'  => '#222222',
        'output' => array(
	        array(
	            'element'  => '#copyright a:hover, #footer a:hover:not(.button), .social_wrapper ul li a:hover, #footer_wrapper a:hover:not(.button, .elementor-button), #footer_photostream a:hover',
	            'property' => 'color',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 12,
	    'js_vars'   => array(
			array(
				'element'  => '#copyright a:hover, #footer a:hover:not(.button), .social_wrapper ul li a:hover, #footer_wrapper a:hover:not(.button, .elementor-button), #footer_photostream a:hover',
				'function' => 'css',
				'property' => 'color',
			),
		),
    );
    
    $controls[] = array(
        'type'     => 'title',
        'settings'  => 'tg_copyright_colors_title',
        'label'    => esc_html__('Copyright Colors Settings', 'hoteller' ),
        'section'  => 'footer_color',
	    'priority' => 12,
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_copyright_bg',
        'label'    => esc_html__('Copyright Background', 'hoteller' ),
        'section'  => 'footer_color',
	    'priority' => 13,
	    'default'  => '#222222',
	    'output' => array(
	        array(
	            'element'  => '.footer_bar',
	            'property' => 'background-color',
	        ),
	    ),
	    'js_vars'   => array(
			array(
				'element'  => '.footer_bar',
				'function' => 'css',
				'property' => 'background-color',
			),
		),
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_copyright_font_color',
        'label'    => esc_html__('Copyright Font Color', 'hoteller' ),
        'section'  => 'footer_color',
        'default'  => '#ffffff',
        'output' => array(
	        array(
	            'element'  => '.footer_bar, #copyright ',
	            'property' => 'color',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 13,
	    'js_vars'   => array(
			array(
				'element'  => '.footer_bar, #copyright ',
				'function' => 'css',
				'property' => 'color',
			),
		),
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_copyright_link_color',
        'label'    => esc_html__('Copyright Link Color', 'hoteller' ),
        'section'  => 'footer_color',
        'default'  => '#cccccc',
        'output' => array(
	        array(
	            'element'  => '.footer_bar a, #copyright a, #footer_menu li a',
	            'property' => 'color',
	        ),
	    ),
	    'js_vars'   => array(
			array(
				'element'  => '.footer_bar a, #copyright a, #footer_menu li a',
				'function' => 'css',
				'property' => 'color',
			),
		),
	    'transport' 	 => 'postMessage',
	    'priority' => 13,
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_copyright_hover_link_color',
        'label'    => esc_html__('Copyright Hover Link Color', 'hoteller' ),
        'section'  => 'footer_color',
        'default'  => '#ffffff',
        'output' => array(
	        array(
	            'element'  => '.footer_bar a:hover, #copyright a:hover, #footer_menu li a:not(.button):hover',
	            'property' => 'color',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 13,
	    'js_vars'   => array(
			array(
				'element'  => '.footer_bar a:hover, #copyright a:hover, #footer_menu li a:not(.button):hover',
				'function' => 'css',
				'property' => 'color',
			),
		),
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_footer_border_color',
        'label'    => esc_html__('Copyright Border Color', 'hoteller' ),
        'section'  => 'footer_color',
        'default'  => '#222222',
        'output' => array(
	        array(
	            'element'  => '.footer_bar_wrapper, .footer_bar',
	            'property' => 'border-color',
	        ),
	    ),
	    'js_vars'   => array(
			array(
				'element'  => '.footer_bar_wrapper, .footer_bar',
				'function' => 'css',
				'property' => 'border-color',
			),
		),
	    'transport' 	 => 'postMessage',
	    'priority' => 13,
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_footer_social_color',
        'label'    => esc_html__('Copyright Social Icon Color', 'hoteller' ),
        'section'  => 'footer_color',
        'default'  => '#999999',
        'output' => array(
	        array(
	            'element'  => '.footer_bar_wrapper .social_wrapper ul li a',
	            'property' => 'color',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 13,
	    'js_vars'   => array(
			array(
				'element'  => '.footer_bar_wrapper .social_wrapper ul li a',
				'function' => 'css',
				'property' => 'color',
			),
		),
    );
    
    $controls[] = array(
        'type'     => 'title',
        'settings'  => 'tg_footer_copyright_title',
        'label'    => esc_html__('Copyright Content Settings', 'hoteller' ),
        'section'  => 'footer_copyright',
	    'priority' => 4,
    );
    
    $controls[] = array(
        'type'     => 'textarea',
        'settings'  => 'tg_footer_copyright_text',
        'label'    => esc_html__('Copyright Text', 'hoteller' ),
        'description' => esc_html__('Enter your copyright text.', 'hoteller' ),
        'section'  => 'footer_copyright',
        'default'  => '© Copyright Hoteller Theme Demo - Theme by ThemeGoods',
        'transport' 	 => 'postMessage',
	    'priority' => 5,
    );
    
    $controls[] = array(
        'type'     => 'select',
        'settings'  => 'tg_footer_copyright_right_area',
        'label'    => esc_html__('Copyright Right Area Content', 'hoteller' ),
        'section'  => 'footer_copyright',
        'default'  => 'menu',
        'choices'  => $tg_copyright_content,
	    'priority' => 6,
    );
    
    $controls[] = array(
        'type'     => 'title',
        'settings'  => 'tg_footer_copyright_typography_title',
        'label'    => esc_html__('Copyright Typography Settings', 'hoteller' ),
        'section'  => 'footer_copyright',
        'priority' => 6,
    );
    
    $controls[] = array(
        'type'     => 'typography',
        'settings'  => 'tg_copyright_typography',
        'label'    => esc_html__('Copyright Typography', 'hoteller' ),
        'section'  => 'footer_copyright',
        'default'  => array(
            'font-family'    => 'Jost',
            'variant'        => '500',
            'font-size'      => '13px',
            'line-height'    => '1.7',
            'letter-spacing' => '0px',
            'text-transform' => 'none',
        ),
        'output' => array(
            array(
                'element'  => '.footer_bar_wrapper',
            ),
        ),
        'priority' => 7
    );
    
    $controls[] = array(
        'type'     => 'title',
        'settings'  => 'tg_footer_copyright_totop_title',
        'label'    => esc_html__('Go To Top Settings', 'hoteller' ),
        'section'  => 'footer_copyright',
	    'priority' => 8,
    );
    
    $controls[] = array(
        'type'     => 'toggle',
        'settings'  => 'tg_footer_copyright_totop',
        'label'    => esc_html__('Go To Top Button', 'hoteller' ),
        'description' => 'Check this option to enable go to top button at the bottom of page when scrolling',
        'section'  => 'footer_copyright',
        'default'  => 1,
	    'priority' => 9,
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_footer_copyright_totop_bg',
        'label'    => esc_html__('Go To Top Button Background', 'hoteller' ),
        'section'  => 'footer_copyright',
	    'priority' => 13,
	    'default'  => 'rgba(0,0,0,0.1)',
	    'output' => array(
	        array(
	            'element'  => 'a#toTop',
	            'property' => 'background',
	        ),
	    ),
	    'js_vars'   => array(
			array(
				'element'  => 'a#toTop',
				'function' => 'css',
				'property' => 'background',
			),
		),
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_footer_copyright_totop_font_color',
        'label'    => esc_html__('Go To Top Button Font Color', 'hoteller' ),
        'section'  => 'footer_copyright',
        'default'  => '#ffffff',
        'output' => array(
	        array(
	            'element'  => 'a#toTop',
	            'property' => 'color',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 13,
	    'js_vars'   => array(
			array(
				'element'  => 'a#toTop',
				'function' => 'css',
				'property' => 'color',
			),
		),
    );
    //End Footer Tab Settings

    
    //Begin Blog Tab Settings
    $controls[] = array(
        'type'     => 'title',
        'settings'  => 'tg_blog_layout_title',
        'label'    => esc_html__('Layout Settings', 'hoteller' ),
        'section'  => 'blog_general',
        'priority' => 0,
    );
    
    $controls[] = array(
        'type'     => 'toggle',
        'settings'  => 'tg_blog_display_full',
        'label'    => esc_html__('Display Full Blog Post Content', 'hoteller' ),
        'description' => esc_html__('Check this option to display post full content in blog page (excerpt blog grid layout)', 'hoteller' ),
        'section'  => 'blog_general',
        'default'  => 0,
	    'priority' => 1,
    );
    
    $controls[] = array(
        'type'     => 'radio',
        'settings'  => 'tg_blog_archive_layout',
        'label'    => esc_html__('Archive Page Layout', 'hoteller' ),
        'description' => esc_html__('Select page layout for displaying archive page', 'hoteller' ),
        'section'  => 'blog_general',
        'default'  => 'blog-r',
        'choices'  => $tg_blog_layout,
	    'priority' => 2,
    );
    
    $controls[] = array(
        'type'     => 'radio',
        'settings'  => 'tg_blog_category_layout',
        'label'    => esc_html__('Category Page Layout', 'hoteller' ),
        'description' => esc_html__('Select page layout for displaying category page', 'hoteller' ),
        'section'  => 'blog_general',
        'default'  => 'blog-r',
        'choices'  => $tg_blog_layout,
	    'priority' => 2,
    );
    
    $controls[] = array(
        'type'     => 'radio',
        'settings'  => 'tg_blog_tag_layout',
        'label'    => esc_html__('Tag Page Layout', 'hoteller' ),
        'description' => esc_html__('Select page layout for displaying tag page', 'hoteller' ),
        'section'  => 'blog_general',
        'default'  => 'blog-r',
        'choices'  => $tg_blog_layout,
	    'priority' => 3,
    );
    
    $controls[] = array(
        'type'     => 'title',
        'settings'  => 'tg_blog_color_title',
        'label'    => esc_html__('Color Settings', 'hoteller' ),
        'section'  => 'blog_general',
        'priority' => 4,
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_blog_content_bg_color',
        'label'    => esc_html__('Single Post Content Background Color', 'hoteller' ),
        'section'  => 'blog_general',
        'default'  => '#ffffff',
        'output' => array(
	        array(
	            'element'  => '#page_content_wrapper.blog_wrapper, #page_content_wrapper.blog_wrapper input:not([type="submit"]), #page_content_wrapper.blog_wrapper textarea, .post_excerpt.post_tag a:after, .post_excerpt.post_tag a:before, .post_navigation .navigation_post_content',
	            'property' => 'background-color',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 5,
	    'js_vars'   => array(
			array(
				'element'  => '#page_content_wrapper.blog_wrapper, #page_content_wrapper.blog_wrapper input:not([type="submit"]), #page_content_wrapper.blog_wrapper textarea, .post_excerpt.post_tag a:after, .post_excerpt.post_tag a:before, .post_navigation .navigation_post_content',
				'function' => 'css',
				'property' => 'background-color',
			),
		)
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_blog_cat_font_color',
        'label'    => esc_html__('Post Category Link Font Color', 'hoteller' ),
        'section'  => 'blog_general',
        'default'  => '#9B9B9B',
        'output' => array(
	        array(
	            'element'  => '.post_info_cat, .post_info_cat a',
	            'property' => 'color',
	        ),
	        array(
	            'element'  => '.post_info_cat, .post_info_cat a',
	            'property' => 'border-color',
	        ),
	    ),
	    'js_vars'   => array(
			array(
				'element'  => '.post_info_cat, .post_info_cat a',
				'function' => 'css',
				'property' => 'color',
			),
			array(
				'element'  => '.post_info_cat, .post_info_cat a',
				'function' => 'css',
				'property' => 'border-color',
			),
		),
	    'transport' 	 => 'postMessage',
	    'priority' => 5,
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_blog_post_format_bg_color',
        'label'    => esc_html__('Post Format Background Color', 'hoteller' ),
        'section'  => 'blog_general',
        'default'  => '#1C58F6',
        'output' => array(
	        array(
	            'element'  => '.post_img_hover .post_type_icon',
	            'property' => 'background',
	        ),
	    ),
	    'js_vars'   => array(
			array(
				'element'  => '.post_img_hover .post_type_icon',
				'function' => 'css',
				'property' => 'background',
			),
		),
	    'transport' 	 => 'postMessage',
	    'priority' => 5,
    );
    
    $controls[] = array(
        'type'     => 'title',
        'settings'  => 'tg_blog_general_title',
        'label'    => esc_html__('General Post Title Settings', 'hoteller' ),
        'section'  => 'blog_typography',
	    'priority' => 0,
    );
    
    $controls[] = array(
        'type'     => 'typography',
        'settings'  => 'tg_blog_title_typography',
        'label'    => esc_html__('Post Title Typography', 'hoteller' ),
        'section'  => 'blog_typography',
        'default'  => array(
            'font-family'    => 'Oswald',
            'variant'        => '400',
            'letter-spacing' => '0px',
            'text-transform' => 'none',
        ),
        'output' => array(
            array(
                'element'  => '.post_header h5, h6.subtitle, .post_caption h1, #page_content_wrapper .posts.blog li a, .page_content_wrapper .posts.blog li a, #post_featured_slider li .slider_image .slide_post h2, .post_header.grid h6, .blog_minimal_wrapper .content h4, .post_attribute, .comment_date, .post-date',
            ),
        ),
        'priority' => 4
    );
    
    $controls[] = array(
        'type'     => 'select',
        'settings'  => 'tg_blog_title_font_style',
        'label'    => esc_html__('Post Title Font Style', 'hoteller' ),
        'section'  => 'blog_typography',
        'default'  => 'normal',
        'choices'  => $tg_font_style,
        'output' => array(
	        array(
	            'element'  => '.post_header h5, h6.subtitle, .post_caption h1, #page_content_wrapper .posts.blog li a, .page_content_wrapper .posts.blog li a, #post_featured_slider li .slider_image .slide_post h2, .post_header.grid h6, .blog_minimal_wrapper .content h4',
	            'property' => 'font-style',
	        ),
	    ),
	    'js_vars'   => array(
			array(
				'element'  => '.post_header h5, h6.subtitle, .post_caption h1, #page_content_wrapper .posts.blog li a, .page_content_wrapper .posts.blog li a, #post_featured_slider li .slider_image .slide_post h2, .post_header.grid h6, .blog_minimal_wrapper .content h4',
				'function' => 'css',
				'property' => 'font-style',
			),
		),
	    'transport' 	 => 'postMessage',
	    'priority' => 4,
    ); 
    
    $controls[] = array(
        'type'     => 'title',
        'settings'  => 'tg_blog_single_typography_title',
        'label'    => esc_html__('Typography Settings', 'hoteller' ),
        'section'  => 'blog_single',
        'priority' => 0,
    );
    
    $controls[] = array(
        'type'     => 'typography',
        'settings'  => 'tg_blog_single_title_typography',
        'label'    => esc_html__('Single Post Title Typography', 'hoteller' ),
        'section'  => 'blog_single',
        'default'  => array(
            'font-family'    => 'Oswald',
            'variant'        => '400',
            'font-size'      => '60px',
            'line-height'    => '1.2',
            'letter-spacing' => '-1px',
            'text-transform' => 'none',
        ),
        'output' => array(
            array(
                'element'  => 'body.single-post .post_caption h1',
            ),
        ),
        'priority' => 1,
    );
    
    $controls[] = array(
        'type'     => 'typography',
        'settings'  => 'tg_blog_single_detail_typography',
        'label'    => esc_html__('Single Post Detail Typography', 'hoteller' ),
        'section'  => 'blog_single',
        'default'  => array(
            'font-family'    => 'Oswald',
            'variant'        => '400',
            'font-size'      => '16px',
            'line-height'    => '1.5',
            'letter-spacing' => '0px',
            'text-transform' => 'none',
        ),
        'output' => array(
            array(
                'element'  => 'body.single #page_caption.hasbg .post_attribute',
            ),
        ),
        'priority' => 1,
    );
    
    $controls[] = array(
        'type'     => 'typography',
        'settings'  => 'tg_blog_single_category_typography',
        'label'    => esc_html__('Single Post Category Typography', 'hoteller' ),
        'section'  => 'blog_single',
        'default'  => array(
            'font-family'    => 'Jost',
            'variant'        => '400',
            'font-size'      => '12px',
            'line-height'    => '1.5',
            'letter-spacing' => '2px',
            'text-transform' => 'uppercase',
        ),
        'output' => array(
            array(
                'element'  => 'body.single .post_detail.single_post',
            ),
        ),
        'priority' => 1,
    );
    
    $controls[] = array(
        'type'     => 'title',
        'settings'  => 'tg_blog_single_content_title',
        'label'    => esc_html__('Content Settings', 'hoteller' ),
        'section'  => 'blog_single',
        'priority' => 2,
    );
    
    $controls[] = array(
        'type'     => 'slider',
        'settings'  => 'tg_blog_content_marging_top',
        'label'    => esc_html__('Single Post Header Margin Top', 'hoteller' ),
        'section'  => 'blog_single',
        'default'  => -140,
        'choices' => array( 'min' => -200, 'max' => 200, 'step' => 1 ),
        'output' => array(
	        array(
	            'element'  => '#page_content_wrapper.blog_wrapper.hasbg',
	            'property' => 'margin-top',
	            'units'    => 'px',
	        ),
	    ),
	    'transport' 	 => 'postMessage',
	    'priority' => 3,
	    'js_vars'   => array(
			array(
				'element'  => '#page_content_wrapper.blog_wrapper.hasbg',
				'function' => 'css',
				'property' => 'margin-top',
				'units'    => 'px',
			),
		)
    );
    
    $controls[] = array(
        'type'     => 'toggle',
        'settings'  => 'tg_blog_feat_content',
        'label'    => esc_html__('Display Post Featured Content', 'hoteller' ),
        'description' => esc_html__('Check this to display featured header image in single post page', 'hoteller' ),
        'section'  => 'blog_single',
        'default'  => 1,
	    'priority' => 5,
    );
    
    $controls[] = array(
        'type'     => 'toggle',
        'settings'  => 'tg_blog_cat',
        'label'    => esc_html__('Display Post Categories', 'hoteller' ),
        'description' => esc_html__('Check this to display categories in single post page', 'hoteller' ),
        'section'  => 'blog_single',
        'default'  => 1,
	    'priority' => 5,
    );
    
    $controls[] = array(
        'type'     => 'toggle',
        'settings'  => 'tg_blog_date',
        'label'    => esc_html__('Display Post Date', 'hoteller' ),
        'description' => esc_html__('Check this to display date in single post page', 'hoteller' ),
        'section'  => 'blog_single',
        'default'  => 1,
	    'priority' => 5,
    );
    
    $controls[] = array(
        'type'     => 'toggle',
        'settings'  => 'tg_blog_display_tags',
        'label'    => esc_html__('Display Post Tags', 'hoteller' ),
        'description' => esc_html__('Check this option to display post tags on single post page', 'hoteller' ),
        'section'  => 'blog_single',
        'default'  => 1,
	    'priority' => 6,
    );
    
    $controls[] = array(
        'type'     => 'toggle',
        'settings'  => 'tg_blog_display_author',
        'label'    => esc_html__('Display About Author', 'hoteller' ),
        'description' => esc_html__('Check this option to display about author on single post page', 'hoteller' ),
        'section'  => 'blog_single',
        'default'  => 1,
	    'priority' => 7,
    );
    
    $controls[] = array(
        'type'     => 'toggle',
        'settings'  => 'tg_blog_display_related',
        'label'    => esc_html__('Display Related Posts', 'hoteller' ),
        'description' => esc_html__('Check this option to display related posts on single post page', 'hoteller' ),
        'section'  => 'blog_single',
        'default'  => 1,
	    'priority' => 8,
    );
    
    $controls[] = array(
        'type'     => 'toggle',
        'settings'  => 'tg_blog_display_navigation',
        'label'    => esc_html__('Display Previous/Next Navigation', 'hoteller' ),
        'description' => esc_html__('Check this option to display previous/next navigation on single post page', 'hoteller' ),
        'section'  => 'blog_single',
        'default'  => 1,
	    'priority' => 8,
    );
    
    $controls[] = array(
        'type'     => 'title',
        'settings'  => 'tg_blog_single_color_title',
        'label'    => esc_html__('Color Settings', 'hoteller' ),
        'section'  => 'blog_single',
        'priority' => 8,
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_blog_post_tag_bg_color',
        'label'    => esc_html__('Post Tag Background Color', 'hoteller' ),
        'section'  => 'blog_single',
        'default'  => '#f0f0f0',
        'output' => array(
	        array(
	            'element'  => '.post_excerpt.post_tag a',
	            'property' => 'background',
	        ),
	        array(
	            'element'  => '.post_excerpt.post_tag a:after',
	            'property' => 'border-left-color',
	        ),
	    ),
	    'js_vars'   => array(
			array(
				'element'  => '.post_excerpt.post_tag a',
				'function' => 'css',
				'property' => 'background',
			),
			array(
	            'element'  => '.post_excerpt.post_tag a:after',
	            'property' => 'border-left-color',
	        ),
		),
	    'transport' 	 => 'postMessage',
	    'priority' => 9,
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_blog_post_tag_font_color',
        'label'    => esc_html__('Post Tag Font Color', 'hoteller' ),
        'section'  => 'blog_single',
        'default'  => '#888',
        'output' => array(
	        array(
	            'element'  => '.post_excerpt.post_tag a',
	            'property' => 'color',
	        ),
	        array(
	            'element'  => '.post_excerpt.post_tag a',
	            'property' => 'color',
	        ),
	    ),
	    'js_vars'   => array(
			array(
				'element'  => '.post_excerpt.post_tag a',
				'function' => 'css',
				'property' => 'color',
			),
		),
	    'transport' 	 => 'postMessage',
	    'priority' => 9,
    );
    //End Blog Tab Settings
    
    
    //Begin Accommodation Tab Settings
    $controls[] = array(
        'type'     => 'title',
        'settings'  => 'tg_accommodation_content_title',
        'label'    => esc_html__('Content Settings', 'hoteller' ),
        'section'  => 'accommodation_single',
	    'priority' => 0,
    );
    
    $controls[] = array(
        'type'     => 'toggle',
        'settings'  => 'tg_accommodation_content_nav',
        'label'    => esc_html__('Display Room Content Navigation', 'hoteller' ),
        'description' => esc_html__('Check this to display room content navigation in header.', 'hoteller' ),
        'section'  => 'accommodation_single',
        'default'  => 1,
	    'priority' => 1,
    );
    
    $controls[] = array(
        'type'     => 'toggle',
        'settings'  => 'tg_accommodation_amenities',
        'label'    => esc_html__('Display Room Amenities & Services', 'hoteller' ),
        'description' => esc_html__('Check this to display room amenities & services content.', 'hoteller' ),
        'section'  => 'accommodation_single',
        'default'  => 1,
	    'priority' => 1,
    );
    
    $controls[] = array(
        'type'     => 'toggle',
        'settings'  => 'tg_accommodation_gallery',
        'label'    => esc_html__('Display Room Gallery', 'hoteller' ),
        'description' => esc_html__('Check this to display room gallery.', 'hoteller' ),
        'section'  => 'accommodation_single',
        'default'  => 1,
	    'priority' => 1,
    );
    
    $controls[] = array(
        'type'     => 'toggle',
        'settings'  => 'tg_accommodation_other_rooms',
        'label'    => esc_html__('Display Other Rooms', 'hoteller' ),
        'description' => esc_html__('Check this to display other rooms section.', 'hoteller' ),
        'section'  => 'accommodation_single',
        'default'  => 1,
	    'priority' => 1,
    );
    
    $controls[] = array(
	    'type'     => 'radio-buttonset',
	    'settings'  => 'tg_accommodation_other_rooms_layout',
	    'label'    => esc_html__('Other Rooms Grid Layout', 'hoteller' ),
	    'description' => esc_html__('Select grid layout for other rooms content', 'hoteller' ),
	    'section'  => 'accommodation_single',
	    'default'  => '1',
	    'choices'  => $tg_other_room_layout,
	    'priority' => 1,
	);
	
	$controls[] = array(
        'type'     => 'slider',
        'settings'  => 'tg_accommodation_other_rooms_columns',
        'label'    => esc_html__('Other Rooms Columns', 'hoteller' ),
        'section'  => 'accommodation_single',
        'default'  => 3,
        'choices' => array( 'min' => 2, 'max' => 4, 'step' => 1 ),
	    'priority' => 1,
    );
    
    $controls[] = array(
        'type'     => 'slider',
        'settings'  => 'tg_accommodation_other_rooms_items',
        'label'    => esc_html__('Other Rooms Items', 'hoteller' ),
        'section'  => 'accommodation_single',
        'default'  => 3,
        'choices' => array( 'min' => 2, 'max' => 12, 'step' => 1 ),
	    'priority' => 1,
    );
    
    $controls[] = array(
        'type'     => 'title',
        'settings'  => 'tg_accommodation_header_title',
        'label'    => esc_html__('Header Settings', 'hoteller' ),
        'section'  => 'accommodation_single',
        'priority' => 2,
    );
    
    $controls[] = array(
        'type'     => 'toggle',
        'settings'  => 'tg_accommodation_header_transparent',
        'label'    => esc_html__('Make Menu Transparent', 'hoteller' ),
        'description' => esc_html__('Check this to display navigation in transparent.', 'hoteller' ),
        'section'  => 'accommodation_single',
        'default'  => 1,
        'priority' => 3,
    );
    
    $controls[] = array(
        'type'     => 'title',
        'settings'  => 'tg_accommodation_color_title',
        'label'    => esc_html__('Color Settings', 'hoteller' ),
        'section'  => 'accommodation_single',
	    'priority' => 4,
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_accommodation_amenities_bg_color',
        'label'    => esc_html__('Room Amenities & Services Background Color', 'hoteller' ),
        'section'  => 'accommodation_single',
        'default'  => '#0F172B',
        'output' => array(
	        array(
	            'element'  => '.singleroom_amenities',
	            'property' => 'background',
	        ),
	    ),
	    'js_vars'   => array(
			array(
				'element'  => '.singleroom_amenities',
				'function' => 'css',
				'property' => 'background',
			),
		),
	    'transport' 	 => 'postMessage',
	    'priority' => 9,
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_accommodation_amenities_font_color',
        'label'    => esc_html__('Room Amenities & Services Font Color', 'hoteller' ),
        'section'  => 'accommodation_single',
        'default'  => '#ffffff',
        'output' => array(
	        array(
	            'element'  => '.singleroom_amenities',
	            'property' => 'color',
	        ),
	    ),
	    'js_vars'   => array(
			array(
				'element'  => '.singleroom_amenities',
				'function' => 'css',
				'property' => 'color',
			),
		),
	    'transport' 	 => 'postMessage',
	    'priority' => 9,
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_accommodation_review_stars_color',
        'label'    => esc_html__('Review Stars Color', 'hoteller' ),
        'section'  => 'accommodation_single',
        'default'  => '#0F172B',
        'output' => array(
            array(
                'element'  => '.mphbr-star-rating>span',
                'property' => 'color',
            ),
        ),
        'js_vars'   => array(
            array(
                'element'  => '.mphbr-star-rating>span',
                'function' => 'css',
                'property' => 'color',
            ),
        ),
        'transport' 	 => 'postMessage',
        'priority' => 9,
    );
    
    $controls[] = array(
        'type'     => 'title',
        'settings'  => 'tg_accommodation_typography_title',
        'label'    => esc_html__('Typography Settings', 'hoteller' ),
        'section'  => 'accommodation_single',
	    'priority' => 9,
    );
    
    $controls[] = array(
        'type'     => 'typography',
        'settings'  => 'tg_accommodation_price_typography',
        'label'    => esc_html__('Room Pricing Category Typography', 'hoteller' ),
        'section'  => 'accommodation_single',
        'default'  => array(
            'font-family'    => 'Jost',
            'variant'        => '600',
            'font-size'      => '60px',
            'line-height'    => '1.5',
            'letter-spacing' => '0px',
        ),
        'output' => array(
            array(
                'element'  => '.singleroom_price_amount .mphb-price',
            ),
        ),
        'priority' => 10,
    );
    
    $controls[] = array(
        'type'     => 'text',
        'settings'  => 'tg_accommodation_slug',
        'label'    => esc_html__('Accommodation Type Slug', 'hoteller' ),
        'description' => 'Enter custom permalink slug for single accommodation page',
        'section'  => 'accommodation_permalink',
        'default'  => 'accommodation',
        'transport' => 'postMessage',
        'priority' => 1,
    );
    
    $controls[] = array(
        'type'     => 'toggle',
        'settings'  => 'tg_accommodation_booking_progress',
        'label'    => esc_html__('Booking Progress Bar', 'hoteller' ),
        'description' => esc_html__('Check this to display booking progress at the top of pages', 'hoteller' ),
        'section'  => 'accommodation_booking_progress',
        'default'  => 0,
        'priority' => 3,
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_accommodation_booking_progress_default_color',
        'label'    => esc_html__('Booking Progress Bar Default Color', 'hoteller' ),
        'section'  => 'accommodation_booking_progress',
        'default'  => '#efefef',
        'output' => array(
            array(
                'element'  => '#page_caption .page_title_wrapper .page_title_inner .themegoods-step:after, #page_caption .page_title_wrapper .page_title_inner .themegoods-step',
                'property' => 'background-color',
            ),
            array(
                'element'  => '#page_caption .page_title_wrapper .page_title_inner .themegoods-step:after, #page_caption .page_title_wrapper .page_title_inner .themegoods-step',
                'property' => 'border-color',
            ),
        ),
        'js_vars'   => array(
            array(
                'element'  => '#page_caption .page_title_wrapper .page_title_inner .themegoods-step:after, #page_caption .page_title_wrapper .page_title_inner .themegoods-step',
                'function' => 'css',
                'property' => 'background-color',
            ),
            array(
                'element'  => '#page_caption .page_title_wrapper .page_title_inner .themegoods-step:after, #page_caption .page_title_wrapper .page_title_inner .themegoods-step',
                'property' => 'border-color',
            ),
        ),
        'transport' 	 => 'postMessage',
        'priority' => 4,
    );
    
    $controls[] = array(
        'type'     => 'color',
        'settings'  => 'tg_accommodation_booking_progress_active_color',
        'label'    => esc_html__('Booking Progress Bar Active Color', 'hoteller' ),
        'section'  => 'accommodation_booking_progress',
        'default'  => '#f62f5e',
        'output' => array(
            array(
                'element'  => '#page_caption .page_title_wrapper .page_title_inner .themegoods-step.active:after, #page_caption .page_title_wrapper .page_title_inner .themegoods-step.active',
                'property' => 'background-color',
            ),
            array(
                'element'  => '#page_caption .page_title_wrapper .page_title_inner .themegoods-step.active:before',
                'property' => 'color',
            ),
        ),
        'js_vars'   => array(
            array(
                'element'  => '#page_caption .page_title_wrapper .page_title_inner .themegoods-step.active:after, #page_caption .page_title_wrapper .page_title_inner .themegoods-step.active',
                'function' => 'css',
                'property' => 'background-color',
            ),
            array(
                'element'  => '#page_caption .page_title_wrapper .page_title_inner .themegoods-step.active:before',
                'property' => 'color',
            ),
        ),
        'transport' 	 => 'postMessage',
        'priority' => 4,
    );
    
    $controls[] = array(
        'type'     => 'select',
        'settings'  => 'tg_accommodation_search_layout',
        'label'    => esc_html__('Search Results Layout', 'hoteller' ),
        'section'  => 'accommodation_search_results',
        'default'  => 'classic',
        'choices'  => $tg_search_results_layout,
        'priority' => 1,
    );

    //End Accommodation Tab Settings
    
    
    //Check if Woocommerce is installed	
	if(class_exists('Woocommerce'))
	{
		//Begin Shop Tab Settings
		$controls[] = array(
	        'type'     => 'radio-buttonset',
	        'settings'  => 'tg_shop_layout',
	        'label'    => esc_html__('Shop Main Page Layout', 'hoteller' ),
	        'description' => esc_html__('Select page layout for displaying shop\'s products page', 'hoteller' ),
	        'section'  => 'shop_layout',
	        'default'  => 'fullwidth',
	        'choices'  => $tg_shop_layout,
		    'priority' => 1,
	    );
	    
	    $controls[] = array(
	        'type'     => 'slider',
	        'settings'  => 'tg_shop_items',
	        'label'    => esc_html__('Products Page Show At Most', 'hoteller' ),
	        'description' => esc_html__('Select number of product items you want to display per page', 'hoteller' ),
	        'section'  => 'shop_layout',
	        'default'  => 16,
	        'choices' => array( 'min' => 1, 'max' => 100, 'step' => 1 ),
		    'priority' => 2,
	    );
	    
	    $controls[] = array(
	        'type'     => 'toggle',
	        'settings'  => 'tg_shop_filter_sorting',
	        'label'    => esc_html__('Display Shop Sorting Filter Option', 'hoteller' ),
	        'description' => esc_html__('Check this option to display sorting filter option on shop page', 'hoteller' ),
	        'section'  => 'shop_layout',
	        'default'  => 1,
		    'priority' => 3,
	    );
	    
	    $controls[] = array(
	        'type'     => 'color',
	        'settings'  => 'tg_shop_price_font_color',
	        'label'    => esc_html__('Product Price Font Color', 'hoteller' ),
	        'section'  => 'shop_single',
	        'default'  => '#999',
	        'output' => array(
		        array(
		            'element'  => '.woocommerce ul.products li.product .price ins, .woocommerce-page ul.products li.product .price ins, .woocommerce ul.products li.product .price, .woocommerce-page ul.products li.product .price, p.price ins span.amount, .woocommerce #content div.product p.price, .woocommerce #content div.product span.price, .woocommerce div.product p.price, .woocommerce div.product span.price, .woocommerce-page #content div.product p.price, .woocommerce-page #content div.product span.price, .woocommerce-page div.product p.price, .woocommerce-page div.product span.price',
		            'property' => 'color',
		        ),
		    ),
		    'transport' 	 => 'postMessage',
		    'priority' => 4,
		    'js_vars'   => array(
				array(
					'element'  => '.woocommerce ul.products li.product .price ins, .woocommerce-page ul.products li.product .price ins, .woocommerce ul.products li.product .price, .woocommerce-page ul.products li.product .price, p.price ins span.amount, .woocommerce #content div.product p.price, .woocommerce #content div.product span.price, .woocommerce div.product p.price, .woocommerce div.product span.price, .woocommerce-page #content div.product p.price, .woocommerce-page #content div.product span.price, .woocommerce-page div.product p.price, .woocommerce-page div.product span.price',
					'function' => 'css',
					'property' => 'color',
				),
			)
	    );
	    
	    $controls[] = array(
	        'type'     => 'color',
	        'settings'  => 'tg_shop_onsale_bg_color',
	        'label'    => esc_html__('Product On Sale Background Color', 'hoteller' ),
	        'section'  => 'shop_single',
	        'default'  => '#1C58F6',
	        'output' => array(
		        array(
		            'element'  => '.woocommerce .products .onsale, .woocommerce ul.products li.product .onsale, .woocommerce span.onsale',
		            'property' => 'background-color',
		        ),
		    ),
		    'transport' 	 => 'postMessage',
		    'priority' => 5,
		    'js_vars'   => array(
				array(
					'element'  => '.woocommerce .products .onsale, .woocommerce ul.products li.product .onsale, .woocommerce span.onsale',
					'function' => 'css',
					'property' => 'background-color',
				),
			)
	    );
	    
	    $controls[] = array(
	        'type'     => 'toggle',
	        'settings'  => 'tg_shop_related_products',
	        'label'    => esc_html__('Display Related Products', 'hoteller' ),
	        'description' => esc_html__('Check this option to display related products on single product page', 'hoteller' ),
	        'section'  => 'shop_single',
	        'default'  => 1,
		    'priority' => 6,
	    );
		//End Shop Tab Settings
        
        $controls[] = array(
            'type'     => 'select',
            'settings'  => 'tg_pages_template_404',
            'label'    => esc_html__('Default 404 Page', 'hoteller' ),
            'description' => esc_html__('Select a page template for 404 not found page', 'hoteller' ),
            'section'  => 'pages_templates',
            'default'  => '',
            'choices'  => hoteller_get_published_pages(),
            'priority' => 1,
        );
	}

    return $controls;
}
add_filter( 'kirki/controls', 'hoteller_custom_setting' );


function hoteller_customize_preview()
{
?>
    <script type="text/javascript">
        ( function( $ ) {
        	//Register Logo Tab Settings
        	wp.customize('tg_retina_logo',function( value ) {
                value.bind(function(to) {
                    jQuery('#custom_logo img').attr('src', to );
                });
            });
        	//End Logo Tab Settings
        
			//Register General Tab Settings
            wp.customize('tg_body_font',function( value ) {
                value.bind(function(to) {
                	var ppGGFont = 'http://fonts.googleapis.com/css?family='+to;
                	if(jQuery('#google_fonts_'+to).length===0)
                	{
			    		jQuery('head').append('<link rel="stylesheet" id="google_fonts_'+to+'" href="'+ppGGFont+'" type="text/css" media="all">');
			    	}
                    jQuery('body, input[type=text], input[type=password], input[type=email], input[type=url], input[type=date], input[type=tel], input.wpcf7-text, .woocommerce table.cart td.actions .coupon .input-text, .woocommerce-page table.cart td.actions .coupon .input-text, .woocommerce #content table.cart td.actions .coupon .input-text, .woocommerce-page #content table.cart td.actions .coupon .input-text, select, textarea, .ui-widget input, .ui-widget select, .ui-widget textarea, .ui-widget button, .ui-widget label, .ui-widget-header, .zm_alr_ul_container').css('fontFamily', to );
                });
            });
            
            wp.customize('tg_button_font',function( value ) {
                value.bind(function(to) {
                	var ppGGFont = 'http://fonts.googleapis.com/css?family='+to;
                	if(jQuery('#google_fonts_'+to).length===0)
                	{
			    		jQuery('head').append('<link rel="stylesheet" id="google_fonts_'+to+'" href="'+ppGGFont+'" type="text/css" media="all">');
			    	}
                    jQuery('input[type=submit], input[type=button], a.button, .button, .woocommerce .page_slider a.button, a.button.fullwidth, .woocommerce-page div.product form.cart .button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt').css('fontFamily', to );
                });
            });
            //End General Tab Settings
        
        	//Register Menu Tab Settings
        	wp.customize('tg_menu_font',function( value ) {
                value.bind(function(to) {
                	var ppGGFont = 'http://fonts.googleapis.com/css?family='+to;
                	if(jQuery('#google_fonts_'+to).length===0)
                	{
			    		jQuery('head').append('<link rel="stylesheet" id="google_fonts_'+to+'" href="'+ppGGFont+'" type="text/css" media="all">');
			    	}
                    jQuery('#menu_wrapper .nav ul li a, #menu_wrapper div .nav li > a').css('fontFamily', to );
                });
            });
            
            wp.customize('tg_menu_contact_hours',function( value ) {
                value.bind(function(to) {
                    jQuery('#top_contact_hours').html('<i class="fa fa-clock-o"></i>'+to);
                });
            });
            
            wp.customize('tg_menu_contact_number',function( value ) {
                value.bind(function(to) {
                    jQuery('#top_contact_number').html('<i class="fa fa-phone"></i>'+to);
                });
            });
            
            wp.customize('tg_sidemenu_font',function( value ) {
                value.bind(function(to) {
                	var ppGGFont = 'http://fonts.googleapis.com/css?family='+to;
                	if(jQuery('#google_fonts_'+to).length===0)
                	{
			    		jQuery('head').append('<link rel="stylesheet" id="google_fonts_'+to+'" href="'+ppGGFont+'" type="text/css" media="all">');
			    	}
                    jQuery('.mobile_main_nav li a, #sub_menu li a').css('fontFamily', to );
                });
            });
            //End Menu Tab Settings
            
        	
        	//Register Sidebar Tab Settings
            wp.customize('tg_sidebar_title_font',function( value ) {
                value.bind(function(to) {
                	var ppGGFont = 'http://fonts.googleapis.com/css?family='+to;
                	if(jQuery('#google_fonts_'+to).length===0)
                	{
			    		jQuery('head').append('<link rel="stylesheet" id="google_fonts_'+to+'" href="'+ppGGFont+'" type="text/css" media="all">');
			    	}
                    jQuery('#page_content_wrapper .sidebar .content .sidebar_widget li h2.widgettitle, h2.widgettitle, h5.widgettitle').css('fontFamily', to );
                });
            });
            //End Sidebar Tab Settings
            
            
            //Register Footer Tab Settings
            wp.customize('tg_footer_copyright_text',function( value ) {
                value.bind(function(to) {
                    jQuery('#copyright').html( to );
                });
            });
            //End Footer Tab Settings
        } )( jQuery )
    </script>
<?php	
}