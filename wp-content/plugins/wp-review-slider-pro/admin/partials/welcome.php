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
	if (!current_user_can('manage_options') && $this->wprev_canuserseepage('welcome')==false) {
        return;
    }
 
?>
    
<div class="wrap wp_pro-settings" id="">
	<h1><img src="<?php echo plugin_dir_url( __FILE__ ) . 'logo.png'; ?>"></h1>

<?php 
include("tabmenu.php");
?>
<div class="w3-row welcomediv">

<div class="w3-col m6  ">

	<div class="w3-col m12">

		<div class="w3-border w3-margin-top wprevmr8">

			<header class="w3-container w3-light-grey">
			  <h3 class="welcomecardheader">Welcome!</h3>
			</header>

			<div class="w3-container">
			  <p>
				<?php _e('Thank you for being an awesome WP Review Slider Pro customer! If you have trouble, or need a feature added, please don\'t hesitate to contact me. I\'m always looking for ways to improve the plugin. -Josh', 'wp-review-slider-pro'); ?>
			  </p>

			</div>

		</div>
	</div>
	<div class="w3-col m12">

		<div class="w3-border w3-margin-top wprevmr8">

			<header class="w3-container w3-light-grey">
			  <h3 class="welcomecardheader">Getting Started</h3>
			</header>

			<div class="w3-container">
		<p><?php 
	echo sprintf(__( '1) Use the "%4$sGet Reviews%2$s" page to download your reviews from different sites and save them to your database. The "%1$sReview Funnels%2$s" page allows you to scrape all your reviews from many more sites along with the built-in methods. Manual reviews can be inserted on the "%3$sReview List%2$s" page.', 'wp_fb-reviews' ), 
							'<a href="'.$urlget['reviewfunnel'].'">', 
							'</a>', 
							'<a href="'.$urlget['reviews'].'">',
							'<a href="'.$urlget['getrevs'].'">'
							);
		?> </p>
		<p>	
		<?php 
	echo sprintf(__( '2) Once downloaded, all the reviews should show up on the "%1$sReview List%2$s" page of the plugin.', 'wp_fb-reviews' ), 
							'<a href="'.$urlget['reviews'].'">', 
							'</a>'
							);
		?>
		</p>
		<p>	
			<?php 
	echo sprintf(__( '3) Create a Review Slider or Grid for your site on the "%1$sTemplates%2$s" page.', 'wp_fb-reviews' ), 
							'<a href="'.$urlget['templates_posts'].'">', 
							'</a>'
							);
		?>
		</p>
		<p>	
		<?php 
	echo sprintf(__( '4) You can also create badges on the "%1$sBadges%2$s" page and even a review submission form on the "%3$sForms%2$s" page.', 'wp_fb-reviews' ), 
							'<a href="'.$urlget['badges'].'">', 
							'</a>', 
							'<a href="'.$urlget['forms'].'">'
							);
		?>
		</p>
		<p>
		<?php 
	echo sprintf(__( '5) Finally, you can use the "%1$sFloats%2$s" page to make a badge or review template float on your site. ', 'wp_fb-reviews' ), 
							'<a href="'.$urlget['float'].'">', 
							'</a>'
							);
		?>
		</p>
		<p>
		<?php 
		echo sprintf(__( 'If you have any trouble please check the %1$sSupport Forum%2$s first. If you want to contact me privately you can either enter your question in the forum as a ticket or use this %3$sform%2$s. I\'m always happy to help!', 'wp_fb-reviews' ), 
							'<a href="'.$urlget['forum'].'">', 
							'</a>', 
							'<a href="'.$urlget['welcome-contact'].'">'
							);

		?>
		</p>

			</div>

		</div>
	</div>


</div>

<div class="w3-col m6  ">
	<div class="w3-col m12">
		<div class="w3-border w3-margin-top wprevml8">
			<header class="w3-container w3-light-grey">
			  <h3 class="welcomecardheader">Useful Links</h3>
			</header>
			<div class="w3-container">
			<p>

<a href="/wp-admin/admin.php?page=wp_pro-forum" class="w3-btn w3-white w3-border w3-round dashicons-before dashicons-list-view">Support Forum</a>
<a href="/wp-admin/admin.php?page=wp_pro-welcome-contact" class="w3-btn w3-white w3-border w3-round dashicons-before dashicons-email">Contact</a>
<a href="https://users.freemius.com/store/263" target="_blank" class="w3-btn w3-white w3-border w3-round dashicons-before dashicons-external">License Dashboard</a>
</p>
			</div>
		</div>
	</div>
	<div class="w3-col m12">
		<div class="w3-border w3-margin-top wprevml8">
			<header class="w3-container w3-light-grey">
			  <h3 class="welcomecardheader">Recent Reviews</h3>
			</header>
			<div class="w3-container">
			  <?php
			  require_once WPREV_PLUGIN_DIR . 'admin/class-wp-review-slider-pro-admin_hooks.php';
			  $plugin_admin_hooks = new WP_Review_Pro_Admin_Hooks(WPREVPRO_PLUGIN_TOKEN, WPREVPRO_PLUGIN_VERSION);
			  $plugin_admin_hooks->custom_dashboard_help();
			  ?>
			</div>
		</div>
	</div>



</div>




</div>




