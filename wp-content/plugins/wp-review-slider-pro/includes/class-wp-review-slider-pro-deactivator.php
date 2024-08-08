<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    WP_Review_Pro
 * @subpackage WP_Review_Pro/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    WP_Review_Pro
 * @subpackage WP_Review_Pro/includes
 * @author     Your Name <email@example.com>
 */
class WP_Review_Pro_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

	//unschdule Yelp review download
	//first find the next schedule callback time
    $time_next_firing = wp_next_scheduled("wprevpro_daily_event");
    //use this function to unschedule it by passing the time and event name
    wp_unschedule_event($time_next_firing, "wprevpro_daily_event");
	wp_clear_scheduled_hook('wprevpro_daily_event');
	
	wp_clear_scheduled_hook( 'wpfbr_cron_google_review' );
	
	//first find the next schedule callback time
    $time_next_firing = wp_next_scheduled("wprevpro_daily_event_lang");
    wp_unschedule_event($time_next_firing, "wprevpro_daily_event_lang");
	wp_clear_scheduled_hook('wprevpro_daily_event_lang');
	
		//first find the next schedule callback time
    $time_next_firing = wp_next_scheduled("wprevpro_daily_event_getapps");
    wp_unschedule_event($time_next_firing, "wprevpro_daily_event_getapps");
	wp_clear_scheduled_hook('wprevpro_daily_event_getapps');
	
		//first find the next schedule callback time
    $time_next_firing = wp_next_scheduled("wprevpro_daily_event_funnels");
    wp_unschedule_event($time_next_firing, "wprevpro_daily_event_funnels");
	wp_clear_scheduled_hook('wprevpro_daily_event_funnels');
	
		//first find the next schedule callback time
    $time_next_firing = wp_next_scheduled("wprevpro_daily_event_funnels_download");
    wp_unschedule_event($time_next_firing, "wprevpro_daily_event_funnels_download");
	wp_clear_scheduled_hook('wprevpro_daily_event_funnels_download');
	
	
	}

}
