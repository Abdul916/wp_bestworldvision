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
	

?>
<div class="wrap wp_pro-settings" id="">
	<h1><img src="<?php echo plugin_dir_url( __FILE__ ) . 'logo.png'; ?>"></h1>
<?php 
include("tabmenu.php");
?>
<div class="wprevpro_margin10">
<?php
if ( wrsp_fs()->is_plan('pro') ) {
	if (wrsp_fs()->is_trial() || wrsp_fs()->has_active_license() ) {
// Include support forum
?>

<iframe src="https://wpreviewslider.userecho.com" height="1500" width="100%"></iframe>

<?php
	}
} else {
?>
<p><strong><?php _e('Upgrade to the Pro Version of this plugin to access our support forum. Get the Pro Version <a href="' . wrsp_fs()->get_upgrade_url() . '">here</a>!', 'wp-fb-reviews'); ?></strong></p>
<?php
}
?>
</div>

</div>

	

