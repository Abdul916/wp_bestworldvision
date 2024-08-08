<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://ljapps.com
 * @since      1.0.0
 *
 * @package    WP_Review_Pro
 * @subpackage WP_Review_Pro/admin/partials
 */
 
    // check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }
 
    // add error/update messages
 
    // check if the user have submitted the settings
    // wordpress will add the "settings-updated" $_GET parameter to the url
    if (isset($_GET['settings-updated'])) {
        // add settings saved message with the class of "updated"
        add_settings_error('wprevpro_messages', 'wprevpro_message', __('Settings Saved! If you have previously downloaded FB reviews and would like the settings applied to them, you will need to remove and re-download them.', 'wp-review-slider-pro'), 'updated');
    }
 
    // show error/update messages
    settings_errors('wprevpro_messages');

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
	
	<p><?php _e('The first thing you need to do is grant our Facebook app permission to read your Facebook Page reviews and then copy the access code from our app and paste it in to the field below.', 'wp-review-slider-pro'); ?> </p>
	<p><?php _e('Designers/Developers: If you are setting this up for a client, it is recommended that you delete your Secret Access Code from the plugin after you download the reviews.', 'wp-review-slider-pro'); ?> </p>
	
	<div id="createbtns">
		<button id="fb_get_access_code" type="button" class="button button-secondary"><?php _e('Get Access Code Here', 'wp-review-slider-pro'); ?></button>&nbsp;&nbsp;
		<button id="fb_get_access_code_video" type="button" class="button button-secondary"><?php _e('Video Instructions', 'wp-review-slider-pro'); ?></button>&nbsp;&nbsp;
	</div>
	</br>	
	<form action="options.php" method="post">
		<?php
		// output security fields for the registered setting "wp_pro-settings"
		settings_fields('wp_pro-settings');
		// output setting sections and their fields
		// (sections are registered for "wp_pro-settings", each field is registered to a specific section)
		do_settings_sections('wp_pro-settings');
		// output save settings button
		submit_button('Save Settings');
		
		$fbcronpagesarray = get_option( 'wpfb_cron_pages' );
		?>
		<input id='wpfbcronpagesinput' type='hidden' value='<?php echo $fbcronpagesarray; ?>'>
	</form>
	<div id="pagelist"><h2><?php _e('Download Your Facebook Page Reviews', 'wp-review-slider-pro'); ?></h2><p><?php _e('Click the button below for the page(s) you would like to display reviews for. Afterwords go to the "Reviews List" Page to see all your reviews', 'wp-review-slider-pro'); ?> </p><p><?php _e('Check the Auto Download if you would like the plugin to automatically check for new reviews every 24 hours.', 'wp-review-slider-pro'); ?> </p>
	<table class="wp-list-table widefat fixed striped posts">
		<thead>
			<tr>
				<th scope="col" id="wpfbpr_actions" class="manage-column column-categories"><?php _e('Action', 'wp-review-slider-pro'); ?></th>
				<th scope="col" id="wpfbpr_fbpagename" class="tcenter manage-column"><?php _e('Auto Download', 'wp-review-slider-pro'); ?></th>
				<th scope="col" id="wpfbpr_fbpagename" class="manage-column"><?php _e('Page Name', 'wp-review-slider-pro'); ?></th>
				<th scope="col" id="wpfbpr_fbpageid" class="manage-column"><?php _e('Page ID', 'wp-review-slider-pro'); ?></th>
			</tr>
		</thead>
		<tbody id="page_list">
		<tr>
		<td colspan="4"><div id="pageslisterror">
		<?php _e('No pages to list. Make sure you have pasted your Secret Access Code above and have granted the manage_pages permission to the app and clicked the Save Settings button.', 'wp-review-slider-pro'); ?>
			</div>
		</td>
		</tr>
			</tbody>
	</table>
		<div id="wpfb_page_list_pagination_bar" style="margin-top: 5px;display:none;">
			<span id='btnpageprev' pcode="" class="button"><?php _e('Previous', 'wp-review-slider-pro'); ?></span>
			<span id='btnpagenext' pcode="" class="button"><?php _e('Next', 'wp-review-slider-pro'); ?></span>
		</div>
	</div>

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


