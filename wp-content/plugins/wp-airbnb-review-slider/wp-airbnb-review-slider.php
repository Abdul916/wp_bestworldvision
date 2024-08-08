<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wpreviewslider.com/
 * @since             1.0
 * @package           WP_Airbnb_Review_Slider
 *
 * @wordpress-plugin
 * Plugin Name: 	  WP Airbnb Review Slider
 * Plugin URI:        https://wpreviewslider.com/
 * Description:       Allows you to easily display your Airbnb Business Page reviews in your Posts, Pages, and Widget areas.
 * Version:           3.9
 * Author:            LJ Apps
 * Author URI:        http://ljapps.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-airbnb-review-slider
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-airbnb-review-slider-activator.php
 */
function activate_WP_Airbnb_Review( $networkwide )
{
	//save time activated
	$newtime=time();
	update_option( 'wprev_activated_time_airbnb', $newtime );
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-airbnb-review-slider-activator.php';
    WP_Airbnb_Review_Activator::activate_all( $networkwide );
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-airbnb-review-slider-deactivator.php
 */
function deactivate_WP_Airbnb_Review()
{
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-airbnb-review-slider-deactivator.php';
    WP_Airbnb_Review_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_WP_Airbnb_Review' );
register_deactivation_hook( __FILE__, 'deactivate_WP_Airbnb_Review' );
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-airbnb-review-slider.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_WP_Airbnb_Review()
{
    //define plugin location constant
    define( 'wprev_airbnb_plugin_dir', plugin_dir_path( __FILE__ ) );
    define( 'wprev_airbnb_plugin_url', plugins_url( '', __FILE__ ) );


    $plugin = new WP_Airbnb_Review();
    $plugin->run();
}

//for running the cron job
add_action('wpairbnb_daily_event', 'wpairbnb_do_this_daily');

function wpairbnb_do_this_daily() {

		
	require_once plugin_dir_path( __FILE__ ) . 'admin/class-wp-airbnb-review-slider-admin.php';
	$plugin_admin = new WP_Airbnb_Review_Admin( 'wp-airbnb-review-slider', '3.9' );
	$plugin_admin->wpairbnb_download_airbnb_master();
	
}
//add link to change log on plugins menu
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wprevair_action_links' );
function wprevair_action_links( $links )
{
    $links[] = '<a href="https://wpreviewslider.com/" target="_blank"><strong style="color: #009040; display: inline;">Go Pro!</strong></a>';
    return $links;
}

//start the plugin-------------
run_WP_Airbnb_Review();