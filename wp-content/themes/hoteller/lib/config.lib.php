<?php
//Setup theme constant and default data
$theme_obj = wp_get_theme('hoteller');

define("HOTELLER_THEMENAME", $theme_obj['Name']);
if (!defined('HOTELLER_THEMEDEMO'))
{
	define("HOTELLER_THEMEDEMO", false);
}
define("HOTELLER_THEMEDEMOIG", 'kinfolklifestyle');
define("HOTELLER_SHORTNAME", "pp");
define("HOTELLER_THEMEVERSION", $theme_obj['Version']);
define("HOTELLER_THEMEDEMOURL", $theme_obj['ThemeURI']);
define("HOTELLER_MEGAMENU", true);

define("THEMEGOODS_API", 'http://license.themegoods.com/manager/wp-json/envato');
define("THEMEGOODS_PURCHASE_URL", 'https://1.envato.market/kGaqM');
define('ALLOW_UNFILTERED_UPLOADS', true);

if (!defined('HOTELLER_THEMEDATEFORMAT'))
{
	define("HOTELLER_THEMEDATEFORMAT", get_option('date_format'));
}

if (!defined('HOTELLER_THEMETIMEFORMAT'))
{
	define("HOTELLER_THEMETIMEFORMAT", get_option('time_format'));
}

if ( ! defined( 'ENVATOITEMID' ) ) {
	define("ENVATOITEMID", 22316029);
}

//Get default WP uploads folder
$wp_upload_arr = wp_upload_dir();
define("HOTELLER_THEMEUPLOAD", $wp_upload_arr['basedir']."/".strtolower(sanitize_title(HOTELLER_THEMENAME))."/");
define("HOTELLER_THEMEUPLOADURL", $wp_upload_arr['baseurl']."/".strtolower(sanitize_title(HOTELLER_THEMENAME))."/");

if(!is_dir(HOTELLER_THEMEUPLOAD))
{
	wp_mkdir_p(HOTELLER_THEMEUPLOAD);
}

/**
*  Begin Global variables functions
*/

//Get default WordPress post variable
function hoteller_get_wp_post() {
	global $post;
	return $post;
}

//Get default WordPress file system variable
function hoteller_get_wp_filesystem() {
	require_once(ABSPATH . 'wp-admin/includes/file.php');
	WP_Filesystem();
	global $wp_filesystem;
	return $wp_filesystem;
}

//Get default WordPress wprewrite variable
function hoteller_get_wp_rewrite() {
	global $wp_rewrite;
	return $wp_rewrite;
}

//Get default WordPress wpdb variable
function hoteller_get_wpdb() {
	global $wpdb;
	return $wpdb;
}

//Get default WordPress wp_query variable
function hoteller_get_wp_query() {
	global $wp_query;
	return $wp_query;
}

//Get default WordPress customize variable
function hoteller_get_wp_customize() {
	global $wp_customize;
	return $wp_customize;
}

//Get default WordPress current screen variable
function hoteller_get_current_screen() {
	global $current_screen;
	return $current_screen;
}

//Get default WordPress paged variable
function hoteller_get_paged() {
	global $paged;
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	
	return $paged;
}

//Get default WordPress registered widgets variable
function hoteller_get_registered_widget_controls() {
	global $wp_registered_widget_controls;
	return $wp_registered_widget_controls;
}

//Get default WordPress registered sidebars variable
function hoteller_get_registered_sidebars() {
	global $wp_registered_sidebars;
	return $wp_registered_sidebars;
}

//Get default Woocommerce variable
function hoteller_get_woocommerce() {
	global $woocommerce;
	return $woocommerce;
}

//Get all google font usages in customizer
function hoteller_get_google_fonts() {
	$hoteller_google_fonts = array('tg_body_font', 'tg_header_font', 'tg_menu_font', 'tg_sidemenu_font', 'tg_sidebar_title_font', 'tg_button_font');
	
	global $hoteller_google_fonts;
	return $hoteller_google_fonts;
}

//Get menu transparent variable
function hoteller_get_page_menu_transparent() {
	global $hoteller_page_menu_transparent;
	return $hoteller_page_menu_transparent;
}

//Set menu transparent variable
function hoteller_set_page_menu_transparent($new_value = '') {
	global $hoteller_page_menu_transparent;
	$hoteller_page_menu_transparent = $new_value;
}

//Get no header checker variable
function hoteller_get_is_no_header() {
	global $hoteller_is_no_header;
	return $hoteller_is_no_header;
}

//Get deafult theme screen CSS class
function hoteller_get_screen_class() {
	global $hoteller_screen_class;
	return $hoteller_screen_class;
}

//Set deafult theme screen CSS class
function hoteller_set_screen_class($new_value = '') {
	global $hoteller_screen_class;
	$hoteller_screen_class = $new_value;
}

//Get theme homepage style
function hoteller_get_homepage_style() {
	global $hoteller_homepage_style;
	return $hoteller_homepage_style;
}

//Set theme homepage style
function hoteller_set_homepage_style($new_value = '') {
	global $hoteller_homepage_style;
	$hoteller_homepage_style = $new_value;
}

//Get page gallery ID
function hoteller_get_page_gallery_id() {
	global $hoteller_page_gallery_id;
	return $hoteller_page_gallery_id;
}

//Get default theme options variable
function hoteller_get_options() {
	global $hoteller_options;
	return $hoteller_options;
}

//Set default theme options variable
function hoteller_set_options($new_value = '') {
	global $hoteller_options;
	$hoteller_options = $new_value;
}

//Get top bar setting
function hoteller_get_topbar() {
	global $hoteller_topbar;
	return $hoteller_topbar;
}

//Set top bar setting
function hoteller_set_topbar($new_value = '') {
	global $hoteller_topbar;
	$hoteller_topbar = $new_value;
}

//Get is hide title option
function hoteller_get_hide_title() {
	global $hoteller_hide_title;
	return $hoteller_hide_title;
}

//Set is hide title option
function hoteller_set_hide_title($new_value = '') {
	global $hoteller_hide_title;
	$hoteller_hide_title = $new_value;
}

//Get theme page content CSS class
function hoteller_get_page_content_class() {
	global $hoteller_page_content_class;
	return $hoteller_page_content_class;
}

//Set theme page content CSS class
function hoteller_set_page_content_class($new_value = '') {
	global $hoteller_page_content_class;
	$hoteller_page_content_class = $new_value;
}

//Get Kirki global variable
function hoteller_get_kirki() {
	global $kirki;
	return $kirki;
}

//Get admin theme global variable
function hoteller_get_wp_admin_css_colors() {
	global $_wp_admin_css_colors;
	return $_wp_admin_css_colors;
}

//Get theme plugins
function hoteller_get_plugins() {
	global $hoteller_tgm_plugins;
	return $hoteller_tgm_plugins;
}

//Set theme plugins
function hoteller_set_plugins($new_value = '') {
	global $hoteller_tgm_plugins;
	$hoteller_tgm_plugins = $new_value;
}

$is_imported_elementor_templates_hoteller = false;
$pp_imported_elementor_templates_hoteller = get_option("pp_imported_elementor_templates_hoteller");
if(!empty($pp_imported_elementor_templates_hoteller))
{
	$is_imported_elementor_templates_hoteller = true;
}

$pp_just_imported = get_option('pp_just_imported');

if(!empty($pp_just_imported))
{
	//Auto set permalink to post name
	add_action( 'init', function() {
	    $wp_rewrite = hoteller_get_wp_rewrite();
	    $wp_rewrite->set_permalink_structure( '/%postname%/' );
	    
	    //Refresh rewrite rules
		flush_rewrite_rules();
		
		delete_option('pp_just_imported');
	} );
}
?>