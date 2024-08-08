<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    wp_pro_Review
 * @subpackage wp_pro_Review/admin/partials
 */
 
     // check user capabilities
    if (!current_user_can('manage_options') && $this->wprev_canuserseepage('get_woo')==false) {
        return;
    }
	
	    // wordpress will add the "settings-updated" $_GET parameter to the url
		//https://freegolftracker.com/blog/wp-admin/admin.php?settings-updated=true&page=wp_pro-reviews
    if (isset($_GET['settings-updated'])) {
		$options = get_option('wprevpro_woo_settings');
		if($options['woo_radio_sync']!='no'){
			add_settings_error('woo-radio', 'wppro_message', __('Settings Saved. Your WooCommerce reviews/comments should now show up on the Review List page below. ', 'wp-review-slider-pro'), 'updated');
		} else {
        // add settings saved message with the class of "updated"
        add_settings_error('woo-radio', 'wppro_message', __('Settings Saved.', 'wp-review-slider-pro'), 'updated');
		}
    }

?>
<div class="wrap wp_pro-settings" id="">
	<h1><img src="<?php echo plugin_dir_url( __FILE__ ) . 'logo.png'; ?>"></h1>
<?php 
include("tabmenu.php");
//print_r(get_option('wprevpro_woo_settings'));
$headertitle = '';
?>
<div class="w3-row">
<div class="w3-col m12">
<div class="headertype wprevpro_margin10">
<img id="reviewtypelogo" src="<?php echo WPREV_PLUGIN_URL . '/public/partials/imgs/woocommerce_small_icon.png?temp='.time(); ?>">
<span id="headertypetext"><?php echo $headertitle; ?> Reviews</span>
</div>
<div class="wppro_margin10">

	<form action="options.php" method="post">
		<?php
		// output security fields for the registered setting "wp_pro-get_woo"
		settings_fields('wp_pro-get_woo');
		// output setting sections and their fields
		// (sections are registered for "wp_pro-get_woo", each field is registered to a specific section)
		do_settings_sections('wp_pro-get_woo');
		// output save settings button
		submit_button('Save Settings');
		?>
	</form>
	<?php 
// show error/update messages
		settings_errors('woo-radio');

?>
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
</div>

</div>
</div>
	

