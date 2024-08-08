<?php
	//Get all settings
	$settings = $this->get_settings();
	
	$grid_class = 'one_third';
	switch($settings['columns']['size'])
	{
		case 2:
			$grid_class = 'one_half';
		break;
		
		case 4:
			$grid_class = 'one_fourth';
		break;
	}	
?>

<!-- Begin each accommodation type post -->
<?php
	$room_type_id = get_the_id();
	$room_type_title = get_the_title();
	$room_type_content = get_the_excerpt($room_type_id);
	$image_id = get_post_thumbnail_id($room_type_id);
	$image_url = wp_get_attachment_image_src($image_id, 'hoteller-gallery-grid', true);
	$image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
	
	//Get custom pricing
	$custom_pricing = get_post_meta($room_type_id, 'custom_pricing', true);	
	
	switch($settings['layout'])
	{
		case 1:
		default:
?>
	<div class="room_grid_wrapper <?php echo esc_attr($grid_class); ?>">
	<?php
		if(isset($image_url[0]) && !empty($image_url[0]))
		{
	?>
		<div class="post_img_hover">
			<img class="singleroom_other_image" src="<?php echo esc_url($image_url[0]); ?>" alt="<?php echo esc_attr($image_alt); ?>"/>
			<a class="singleroom_other_image_link" title="<?php echo esc_attr($room_type_title); ?>" href="<?php echo esc_url(get_permalink($room_type_id)); ?>"></a>
		</div>
	<?php
		}
	?>
		<h3 class="room_grid_wrapper_header"><a title="<?php echo esc_attr($room_type_title); ?>" href="<?php echo esc_url(get_permalink($room_type_id)); ?>"><?php echo esc_attr($room_type_title); ?></a></h3>
		
		<div class="room_grid_attr_wrapper">
			<?php
				$room_size = get_post_meta($room_type_id, 'mphb_size', true);
				$room_size_unit = get_option('mphb_square_unit');

				if(!empty($room_size))
				{
			?>
			<div class="child_one_third themeborder">
				<div class="room_attr_value"><?php echo esc_html($room_size); ?></div>
				<div class="room_attr_unit"><?php esc_html_e('Size', 'hoteller-elementor' ); ?><br/><?php echo esc_html($room_size_unit); ?></div>
			</div>
			<?php
				}
			?>
			
			<?php
				$room_adults = get_post_meta($room_type_id, 'mphb_adults_capacity', true);
				
				if(!empty($room_adults))
				{
			?>
			<div class="child_one_third themeborder">
				<div class="room_attr_value"><?php echo esc_html($room_adults); ?></div>
				<div class="room_attr_unit"><?php esc_html_e('Max', 'hoteller-elementor' ); ?><br/><?php esc_html_e('Adults', 'hoteller-elementor' ); ?></div>
			</div>
			<?php
				}
			?>
			
			<?php
				$room_children = get_post_meta($room_type_id, 'mphb_children_capacity', true);
				
				if(!empty($room_children))
				{
			?>
			<div class="child_one_third themeborder">
				<div class="room_attr_value"><?php echo esc_html($room_children); ?></div>
				<div class="room_attr_unit"><?php esc_html_e('Max', 'hoteller-elementor' ); ?><br/><?php esc_html_e('Children', 'hoteller-elementor' ); ?></div>
			</div>
			<?php
				}
			?>
		</div>
		<br class="clear"/>
		<div class="room_grid_link_wrapper">
			<a title="<?php echo esc_attr($room_type_title); ?>" href="<?php echo esc_url(get_permalink($room_type_id)); ?>" class="room_grid_book">
				<?php esc_html_e('Book Now From', 'hoteller-elementor' ); ?>&nbsp;
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
			</a>
		</div>
	</div>
<?php
		break;
			
		case 2:
?>
	<div class="room_grid_wrapper <?php echo esc_attr($grid_class); ?>">
	<?php
		if(isset($image_url[0]) && !empty($image_url[0]))
		{
	?>
		<div class="post_img_hover">
			<img class="singleroom_other_image" src="<?php echo esc_url($image_url[0]); ?>" alt="<?php echo esc_attr($image_alt); ?>"/>
			<a class="singleroom_other_image_link" title="<?php echo esc_attr($room_type_title); ?>" href="<?php echo esc_url(get_permalink($room_type_id)); ?>"></a>
		</div>
<?php
		}
?>
		<h3 class="room_grid_wrapper_header"><a title="<?php echo esc_attr($room_type_title); ?>" href="<?php echo esc_url(get_permalink($room_type_id)); ?>"><?php echo esc_attr($room_type_title); ?></a></h3>
		
		<?php
			if(!empty($room_type_content))
			{
		?>
			<div class="room_grid_content_wrapper themeborder">
				<?php echo hoteller_substr(strip_tags(strip_shortcodes($room_type_content)), 150); ?>
			</div>
		<?php
			}
		?>
		<div class="room_grid2_action_wrapper">
			<div class="child_one_half themeborder">
				<div class="room_grid2_price_label"><?php esc_html_e('From', 'hoteller-elementor' ); ?></div>
				<div class="room_grid2_price">
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
			
			<div class="room_grid2_view child_one_half last">
				<a title="<?php echo esc_attr($room_type_title); ?>" href="<?php echo esc_url(get_permalink($room_type_id)); ?>" class="room_grid2_view">
					<?php esc_html_e('View Detail', 'hoteller-elementor' ); ?>
				</a>
			</div>
		</div>
	</div>
<?php
			break;
		}
?>

<!-- End each accommodation type post -->