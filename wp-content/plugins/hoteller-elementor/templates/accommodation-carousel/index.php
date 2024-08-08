<?php
	//Get all settings
	$settings = $this->get_settings();
	
	$autoplay = 0;
	if($settings['autoplay'] == 'yes')
	{
		$autoplay = 1;
	}
	
	$pagination = 0;
	if($settings['pagination'] == 'yes')
	{
		$pagination = 1;
	}
	
	$timer = intval($settings['timer']['size']*1000);
	
	//Get accommodation types data
	$args = array( 
		'post_type' => 'mphb_room_type',
		'posts_per_page' => $settings['posts_per_page']['size'],
		'order' => 'ASC',
	);
	
	if(get_post_type() == 'mphb_room_type')
	{
		$current_post_id = get_the_ID();
		$args['post__not_in'] = array($current_post_id);
	}
	
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
	
	$thumb_image_name = 'hoteller-gallery-grid';
	if(isset($settings['image_dimension']) && !empty($settings['image_dimension']))
	{
		$thumb_image_name = $settings['image_dimension'];
	}

?>
<div class="accommodation-carousel-wrapper">
	<div class="owl-carousel" data-items="<?php echo intval($settings['display_per_page']['size']); ?>" data-pagination="<?php echo intval($pagination); ?>" data-autoplay="<?php echo intval($autoplay); ?>" data-timer="<?php echo intval($timer); ?>">
<?php
		$counter = 1;
	
		if (have_posts()) : while (have_posts()) : the_post();
			$room_type_id = get_the_id();
			$room_type_title = get_the_title();
			$room_type_content = get_the_content();
			$image_id = get_post_thumbnail_id($room_type_id);
			$image_url = wp_get_attachment_image_src($image_id, $thumb_image_name, true);
			$image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
			
			//Get custom pricing
			$custom_pricing = get_post_meta($room_type_id, 'custom_pricing', true);	
?>
			<div class="item">
				<?php
					if(isset($image_url[0]) && !empty($image_url[0]))
					{
				?>
					<div class="accommodation-carousel-image-wrapper">
						<a class="accommodation-carousel-link" title="<?php echo esc_attr($room_type_title); ?>" href="<?php echo esc_url(get_permalink($room_type_id)); ?>">
							<img class="accommodation-carousel-image" src="<?php echo esc_url($image_url[0]); ?>" alt="<?php echo esc_attr($image_alt); ?>"/>
						</a>
					</div>
					
					<div class="accommodation-carousel-content-wrapper">
						<div class="accommodation-carousel-title">
							<h3><a title="<?php echo esc_attr($room_type_title); ?>" href="<?php echo esc_url(get_permalink($room_type_id)); ?>"><?php echo esc_attr($room_type_title); ?></a></h3>
							
							<div class="accommodation-carousel-attr-wrapper">
								<?php
									$room_size = get_post_meta($room_type_id, 'mphb_size', true);
									$room_size_unit = get_option('mphb_square_unit');
					
									if(!empty($room_size))
									{
								?>
									<span class="accommodation-carousel-attr-size">
										<span class="accommodation-carousel-attr-size-value"><?php echo esc_html($room_size); ?></span>
										<span class="accommodation-carousel-attr-size-unit"><?php echo esc_html($room_size_unit); ?></span>
									</span>
								<?php
									}
								?>
								
								<?php
									$room_adults = get_post_meta($room_type_id, 'mphb_adults_capacity', true);
									$room_children = get_post_meta($room_type_id, 'mphb_children_capacity', true);
									
									if((!empty($room_adults) OR !empty($room_children)) && !empty($room_size))
									{
								?>
								<span class="accommodation-carousel-capacity">/&nbsp;
								<?php
									}
										
										if(!empty($room_adults))
										{
								?>
										<span class="accommodation-carousel-capacity-unit"><?php echo esc_html($room_adults); ?> <?php esc_html_e('adults', 'hoteller-elementor' ); ?></span>
								<?php
										}
								?>
									
								<?php
										if(!empty($room_children))
										{
								?>
										<span class="accommodation-carousel-capacity-unit"><?php echo esc_html($room_children); ?> <?php esc_html_e('children', 'hoteller-elementor' ); ?></span>
								<?php
										}
									
									if(!empty($room_adults) OR !empty($room_children))
									{
								?>
								</span>
								<?php
									}
								?>
							</div>
						</div>
						
						<div class="accommodation-carousel-price">
							<span class="accommodation-carousel-price-from"><?php esc_html_e('from', 'hoteller-elementor' ); ?></span>
							<?php 
								if(empty($custom_pricing))
								{
									mphb_tmpl_the_room_type_default_price($room_type_id);
								}
								else
								{
							?>
									<span class="mphb-price"><?php echo esc_html($custom_pricing); ?></span>
							<?php
								}
							?>
						</div>
					</div>
				<?php
					}
				?>
			</div>
<?php
			$counter++;
		endwhile; endif;
?>
	</div>
</div>
<?php
	wp_reset_query();	
?>