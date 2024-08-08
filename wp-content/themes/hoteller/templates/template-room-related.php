<?php
$tg_accommodation_other_rooms_layout = get_theme_mod('tg_accommodation_other_rooms_layout', 1);
$tg_accommodation_other_rooms = get_theme_mod('tg_accommodation_other_rooms', 1);
$tg_accommodation_other_rooms_items = get_theme_mod('tg_accommodation_other_rooms_items', 3);
$tg_accommodation_other_rooms_columns = get_theme_mod('tg_accommodation_other_rooms_columns', 3);
				
if(!empty($tg_accommodation_other_rooms))
{
	$grid_class = 'one_third';
	switch($tg_accommodation_other_rooms_columns)
	{
		case 2:
			$grid_class = 'one_half';
		break;
		
		case 4:
			$grid_class = 'one_fourth';
		break;
	}
	
	//Get other rooms section
	$args = array(
	    'numberposts' => $tg_accommodation_other_rooms_items,
	    'post_type' => array('mphb_room_type'),
	    'suppress_filters' => false,
	    'exclude' => array($post->ID),
	    'orderby' => 'menu_order',
		'order' => 'ASC',
	);
	
	$room_type_arr = get_posts($args);
	//var_dump($room_type_arr);
	
	if(!empty($room_type_arr) && is_array($room_type_arr))
	{
?>
<div class="singleroom_other_wrapper">
	<div class="page_content_wrapper">
		<div class="singleroom_other_header">
			<h2><?php esc_html_e('Other Rooms', 'hoteller' ); ?></h2>
			<div class="post_attribute"><?php esc_html_e('Could also be interest for you', 'hoteller' ); ?></div>
		</div>
		
		<div class="singleroom_other_rooms">
			<?php
				foreach ( $room_type_arr as $room_type ) 
				{
					//Get custom pricing
					$custom_pricing = get_post_meta($room_type->ID, 'custom_pricing', true);
					
					switch($tg_accommodation_other_rooms_layout)
					{
						case 1:
						default:
			?>
				<div class="room_grid_wrapper <?php echo esc_attr($grid_class); ?>">
				<?php
					$image_id = get_post_thumbnail_id($room_type->ID);
					$image_url = wp_get_attachment_image_src($image_id, 'hoteller-gallery-grid', true);
					$image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);	
					
					if(isset($image_url[0]) && !empty($image_url[0]))
					{
				?>
					<div class="post_img_hover">
						<img class="singleroom_other_image" src="<?php echo esc_url($image_url[0]); ?>" alt="<?php echo esc_attr($image_alt); ?>"/>
						<a class="singleroom_other_image_link" title="<?php echo esc_attr($room_type->post_title); ?>" href="<?php echo esc_url(get_permalink($room_type->ID)); ?>"></a>
					</div>
			<?php
					}
			?>
					<h3 class="room_grid_wrapper_header"><?php echo esc_attr($room_type->post_title); ?></h3>
					
					<div class="room_grid_attr_wrapper">
						<?php
							$room_size = get_post_meta($room_type->ID, 'mphb_size', true);
							$room_size_unit = get_option('mphb_square_unit');
						?>
						<div class="child_one_third themeborder">
							<div class="room_attr_value"><?php echo esc_html($room_size); ?></div>
							<div class="room_attr_unit"><?php esc_html_e('Size', 'hoteller' ); ?><br/><?php echo esc_html($room_size_unit); ?></div>
						</div>
						
						<?php
							$room_adults = get_post_meta($room_type->ID, 'mphb_adults_capacity', true);
						?>
						<div class="child_one_third themeborder">
							<div class="room_attr_value"><?php echo esc_html($room_adults); ?></div>
							<div class="room_attr_unit"><?php esc_html_e('Max', 'hoteller' ); ?><br/><?php esc_html_e('Adults', 'hoteller' ); ?></div>
						</div>
						
						<?php
							$room_children = get_post_meta($room_type->ID, 'mphb_children_capacity', true);
						?>
						<div class="child_one_third themeborder">
							<div class="room_attr_value"><?php echo esc_html($room_children); ?></div>
							<div class="room_attr_unit"><?php esc_html_e('Max', 'hoteller' ); ?><br/><?php esc_html_e('Children', 'hoteller' ); ?></div>
						</div>
					</div>
					<br class="clear"/>
					<div class="room_grid_link_wrapper">
						<a title="<?php echo esc_attr($room_type->post_title); ?>" href="<?php echo esc_url(get_permalink($room_type->ID)); ?>" class="room_grid_book">
							<?php esc_html_e('Book Now From', 'hoteller' ); ?>&nbsp;
							<?php 
								if(empty($custom_pricing))
								{
									mphb_tmpl_the_room_type_default_price($room_type->ID);
								}
								else
								{
							?>
									<span class="mphb-price"><?php echo esc_html($custom_pricing); ?></span>
							<?php
								}
							?>
						</a>
					</div>
				</div>
			<?php
						break;
						
						case 2:
			?>
				<div class="room_grid_wrapper <?php echo esc_attr($grid_class); ?>">
				<?php
					$image_id = get_post_thumbnail_id($room_type->ID);
					$image_url = wp_get_attachment_image_src($image_id, 'hoteller-gallery-grid', true);
					$image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
					
					if(isset($image_url[0]) && !empty($image_url[0]))
					{
				?>
					<div class="post_img_hover">
						<img class="singleroom_other_image" src="<?php echo esc_url($image_url[0]); ?>" alt="<?php echo esc_attr($image_alt); ?>"/>
						<a class="singleroom_other_image_link" title="<?php echo esc_attr($room_type->post_title); ?>" href="<?php echo esc_url(get_permalink($room_type->ID)); ?>"></a>
					</div>
			<?php
					}
			?>
					<h3 class="room_grid_wrapper_header"><?php echo esc_attr($room_type->post_title); ?></h3>
					
					<?php
						if(!empty($room_type->post_excerpt))
						{
					?>
						<div class="room_grid_content_wrapper themeborder">
							<?php echo hoteller_substr(strip_tags(strip_shortcodes($room_type->post_excerpt)), 150); ?>
						</div>
					<?php
						}
					?>
					<div class="room_grid2_action_wrapper">
						<div class="child_one_half themeborder">
							<div class="room_grid2_price_label"><?php esc_html_e('From', 'hoteller' ); ?></div>
							<div class="room_grid2_price">
								<?php 
									if(empty($custom_pricing))
									{
										mphb_tmpl_the_room_type_default_price($room_type->ID);
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
						
						<div class="room_grid2_view child_one_half last">
							<a title="<?php echo esc_attr($room_type->post_title); ?>" href="<?php echo esc_url(get_permalink($room_type->ID)); ?>" class="room_grid2_view">
								<?php esc_html_e('View Detail', 'hoteller' ); ?>
							</a>
						</div>
					</div>
				</div>
			<?php
						break;
					}
				}
			?>
		</div>
	</div>
</div>
<?php
	}
}
?>