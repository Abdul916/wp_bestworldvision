<?php
	//Get all settings
	$settings = $this->get_settings();
?>
<div class="booking_history_wrapper">
<?php
$is_logged_in = is_user_logged_in();

//If theme demo then show booking history
if (defined('HOTELLER_THEMEDEMO') && HOTELLER_THEMEDEMO)
{
	$is_logged_in = true;
}

if($is_logged_in)
{
	//For pagination
	if(is_front_page())
	{
	    $paged = (get_query_var('page')) ? get_query_var('page') : 1;
	}
	else
	{
	    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	}

	$args = array( 
		'post_type' => 'mphb_booking',
		'posts_per_page' => $settings['posts_per_page']['size'],
		'paged' => $paged,
		'orderby' => 'ID',
		'order' => 'DESC',
	);
	
	if($_SERVER['SERVER_NAME'] == 'themes.themegoods.com')
	{
		$args['author'] = 1;
	}
	else
	{
		$current_user = wp_get_current_user();
		
		$args['meta_query'][] = array(
			'key' => 'mphb_email',
			'value' => $current_user->user_email,
			'compare' => '='
		);
	}
	
	query_posts($args);
	
	if (have_posts()) : while (have_posts()) : the_post();
	
		include(HOTELLER_ELEMENTOR_PATH.'/templates/booking-history/grid.php');
		   			    
	endwhile; else:
?>
<div class="booking_notice">
	<?php esc_html_e("You don't have any booking history", "hoteller-elementor" ); ?>
</div>
<?php	
	endif;
	
} //if logged in
else 
{
?>
<div class="booking_notice">
	<?php esc_html_e('Please log in to see your booking history', 'hoteller-elementor' ); ?>
</div>
<?php
}
?>
</div>
<?php
	if($settings['show_pagination'] == 'yes')
	{
		global $wp_query;
		if($wp_query->max_num_pages > 1)
	    {
	    	if (function_exists("hoteller_pagination")) 
	    	{
	    	    hoteller_pagination($wp_query->max_num_pages, 4, 'blog-posts-'.$settings['layout'] );
	    	}
	    	else
	    	{
?>
	    		<div class="pagination accommodation-types-<?php echo esc_attr($settings['layout']); ?>"><p><?php posts_nav_link(''); ?></p></div>
<?php
	    	}
?>
			<div class="pagination_detail accommodation-types-<?php echo esc_attr($settings['layout']); ?>">
		    	<?php esc_html_e('Page', 'hoteller-elementor' ); ?> <?php echo esc_html($paged); ?> <?php esc_html_e('of', 'hoteller-elementor' ); ?> <?php echo esc_html($wp_query->max_num_pages); ?>
		    </div>
<?php
	    }
	}
	
	wp_reset_query();	
?>
<br class="clear"/>