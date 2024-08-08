<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    WP_Airbnb_Review
 * @subpackage WP_Airbnb_Review/admin/partials
 */
 
     // check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }
	
	    // wordpress will add the "settings-updated" $_GET parameter to the url
		//https://freegolftracker.com/blog/wp-admin/admin.php?settings-updated=true&page=wp_airbnb-reviews
    if (isset($_GET['settings-updated'])) {
        // add settings saved message with the class of "updated"
        add_settings_error('airbnb-radio', 'wpairbnb_message', __('Settings Saved', 'wp-airbnb-review-slider'), 'updated');
    }

	if(isset($this->errormsg)){
		add_settings_error('airbnb-radio', 'wpairbnb_message', __($this->errormsg, 'wp-airbnb-review-slider'), 'error');
	}
?>
<div class="wrap wp_airbnb-settings" id="">
	<h1><img src="<?php echo plugin_dir_url( __FILE__ ) . 'logo.png'; ?>"></h1>
<?php 
include("tabmenu.php");
?>
<div class="wpairbnb_margin10">

	<form action="options.php" method="post">
		<?php
		// output security fields for the registered setting "wp_airbnb-get_airbnb"
		settings_fields('wp_airbnb-get_airbnb');
		// output setting sections and their fields
		// (sections are registered for "wp_airbnb-get_airbnb", each field is registered to a specific section)
		do_settings_sections('wp_airbnb-get_airbnb');
		// output save settings button
		submit_button('Save Settings & Download');
		?>
		<p><i>Note: It may take a little time after you hit the Save button to download your reviews.</i></p>
	</form>
	<?php 
// show error/update messages
		settings_errors('airbnb-radio');

?>

</div>

</div>

	

