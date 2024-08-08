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
    if (!current_user_can('manage_options')) {
        return;
    }
	
	    // wordpress will add the "settings-updated" $_GET parameter to the url
		//https://freegolftracker.com/blog/wp-admin/admin.php?settings-updated=true&page=wp_pro-reviews
    if (isset($_GET['settings-updated'])) {
        // add settings saved message with the class of "updated"
        add_settings_error('airbnb-radio', 'wppro_message', __('Settings Saved', 'wp-review-slider-pro'), 'updated');
    }

	if(isset($this->errormsg)){
		add_settings_error('airbnb-radio', 'wppro_message', __($this->errormsg, 'wp-review-slider-pro'), 'error');
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

<?php
//$tempoptions = get_option('wprevpro_airbnb_settings');
//print_r($tempoptions);
?>

<div class="wppro_margin10">

	<form action="options.php" method="post">
		<?php
		// output security fields for the registered setting "wp_pro-get_airbnb"
		settings_fields('wp_pro-get_airbnb');
		// output setting sections and their fields
		// (sections are registered for "wp_pro-get_airbnb", each field is registered to a specific section)
		do_settings_sections('wp_pro-get_airbnb');
		// output save settings button
		submit_button('Save Settings');
		?>
	</form>
	<?php 
// show error/update messages
		settings_errors('airbnb-radio');

?>
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
</div>

</div>
</div>
	

