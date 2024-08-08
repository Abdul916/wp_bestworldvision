<?php
	//Get all settings
	$settings = $this->get_settings();
?>
<div class="accommodation_type_content_wrapper layout_<?php echo esc_attr($settings['layout']); ?>">
<?php
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
		'post_type' => 'mphb_room_type',
		'posts_per_page' => $settings['posts_per_page']['size'],
		'paged' => $paged,
		'order' => 'ASC',
	);
	
	switch($settings['sort_by'])
	{
		case 'menu_order':
		default:
			$args['orderby'] = 'menu_order';
		break;
		
		case 'title':
			$args['orderby'] = 'post_title';
		break;
	}

	if(isset($settings['categories']) && !empty($settings['categories']))
	{
		$args['tax_query'] = array( 
	        array( 
	            'taxonomy' => 'mphb_room_type_category', //or tag or custom taxonomy
	            'field' => 'id', 
	            'terms' => $settings['categories']
	        ) 
	    );
	}
	
	query_posts($args);
	
	$tg_enable_lazy_loading = get_theme_mod('tg_enable_lazy_loading');
	$counter = 0;
	
	if (have_posts()) : while (have_posts()) : the_post();
	
		include(HOTELLER_ELEMENTOR_PATH.'/templates/accommodation-types/grid.php');
	   			    
	endwhile; endif;
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