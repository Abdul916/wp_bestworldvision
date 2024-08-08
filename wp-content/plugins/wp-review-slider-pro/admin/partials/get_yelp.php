<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    WP_Review_Pro
 * @subpackage WP_Review_Pro/admin/partials
 */
 
     // check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }
	
	    // wordpress will add the "settings-updated" $_GET parameter to the url
		//https://freegolftracker.com/blog/wp-admin/admin.php?settings-updated=true&page=wp_pro-reviews
    if (isset($_GET['settings-updated'])) {
        // add settings saved message with the class of "updated"
        add_settings_error('yelp-radio', 'wprevpro_message', __('Settings Saved', 'wp-review-slider-pro'), 'updated');
    }

	if(isset($this->errormsg)){
		add_settings_error('yelp-radio', 'wprevpro_message', __($this->errormsg, 'wp-review-slider-pro'), 'error');
	}
?>
<div class="wrap wp_pro-settings" id="">
	<h1><img src="<?php echo plugin_dir_url( __FILE__ ) . 'logo.png'; ?>"></h1>
<?php 
include("tabmenu.php");
?>
<div class="w3-row">
<div class="w3-col m2 sidemenucontainer">
<?php
include("getrevs_sidemenu.php");
?>	
</div>
<div class="w3-col m10">

<div class="wprevpro_margin10">
<?php
if ( wrsp_fs()->can_use_premium_code() ) {
?>
	<form action="options.php" method="post">
		<?php
		
		$options = get_option('wprevpro_yelp_settings');
		//print_r($options);
		
		// output security fields for the registered setting "wp_pro-get_yelp"
		settings_fields('wp_pro-get_yelp');
		// output setting sections and their fields
		// (sections are registered for "wp_pro-get_yelp", each field is registered to a specific section)
		do_settings_sections('wp_pro-get_yelp');
		// output save settings button
		//submit_button('Save Settings');
		//<p class="description">Note: It may take a little bit of time to download your Yelp reviews after you hit the Save Settings button.</p>
		?>
		
		
		
		<p class="submit">
		<input name="submit" id="submit" class="button button-primary" value="Save Settings" type="submit">&nbsp;&nbsp;

		<?php
		if( ! empty( $options['yelp_business_url'] )) {
		?>

		<?php } else {?>
<span><i> <?php _e('Please enter the URL above and click Save Settings.', 'wp-review-slider-pro'); ?></i></span>
		<?php } ?>
	</p>
	
	</form>
	<p>
<?php
printf( __( 'Use the %1$sReview Funnels%2$s feature if you need to grab all your reviews.', 'wp-review-slider-pro' ) , '<a href='.$urlget['reviewfunnel'].'>','</a>' );
?>
</p>
	<div id="popup" class="popup-wrapper wprevpro_hide">
	  <div class="popup-content">
		<div class="popup-title">
		  <button type="button" class="popup-close">&times;</button>
		  <h3 id="popup_titletext"></h3>
		</div>
		<div class="popup-body">
		  <div id="popup_bobytext1"></div>
		  <div id="popup_bobytext2"></div>
		</div>
	  </div>
	</div>
	<?php 
// show error/update messages
		settings_errors('yelp-radio');
} else {
?>
<p><strong><?php _e('Upgrade to the Pro Version of this plugin to download and display your Yelp reviews! Get the Pro Version <a href="' . wrsp_fs()->get_upgrade_url() . '">here</a>!', 'wp-review-slider-pro'); ?></strong></p>
<?php
}
?>
</div>

</div>
</div>

	

