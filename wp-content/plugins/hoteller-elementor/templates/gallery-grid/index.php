<?php
	$widget_id = $this->get_id();
	$images = $this->get_settings('gallery');
	
	if(!empty($images))
	{
		//Get all settings
		$settings = $this->get_settings();
		
		//Get spacing class
		$spacing_class = '';
		if($settings['spacing'] != 'yes')
		{
			$spacing_class = 'has_no_space';
		}
		
		//Get lightbox link
		$is_lighbox = false;
		if($settings['lightbox'] == 'yes')
		{
			$is_lighbox = true;
		}
		
		//Get hover effect
		$hover_class = '';
		if($settings['hover_effect'] == 'yes')
		{
			$hover_class = 'gallery-grid-tilt';
		}
		
		$column_class = 1;
		$thumb_image_name = 'hoteller-gallery-grid';
		if(isset($settings['image_dimension']) && !empty($settings['image_dimension']))
		{
			$thumb_image_name = $settings['image_dimension'];
		}
		
		//Start displaying gallery columns
		switch($settings['columns']['size'])
		{
		   	case 1:
		   	default:
		   		$column_class = 'tg_one_cols';
		   		$thumb_image_name = 'original';
		   	break;
		   	
		   	case 2:
		   		$column_class = 'tg_two_cols';
		   	break;
		   	
		   	case 3:
		   		$column_class = 'tg_three_cols';
		   	break;
		   	
		   	case 4:
		   		$column_class = 'tg_four_cols';
		   	break;
		   	
		   	case 5:
		   		$column_class = 'tg_five_cols';
		   	break;
		   	
		   	case 6:
		   		$column_class = 'tg_six_cols';
		   	break;
		}
		
		$tg_enable_lazy_loading = get_theme_mod('tg_enable_lazy_loading');
?>
<div class="gallery_grid_content_wrapper layout_<?php echo esc_attr($column_class); ?> <?php echo esc_attr($spacing_class); ?>">
<?php		
		$last_class = '';
		$count = 1;
		
		foreach ( $images as $image ) 
		{
			$last_class = '';
			if($count%$settings['columns']['size'] == 0)
			{
				$last_class = 'last';
			}
			
			if(isset($image['id']) && !empty($image['id']))
			{
				$image_id = $image['id'];
			}
			else
			{
				$image_id = hoteller_get_image_id($image['url']);
			}

			if(is_numeric($image_id) && (!isset($_GET['elementor_library']) OR empty($_GET['elementor_library'])))
			{
				$thumb_image_url = wp_get_attachment_image_src($image_id, $thumb_image_name, true);
				$lightbox_thumb_image_url = wp_get_attachment_image_src($image_id, 'medium', true);
				
				//Get image meta data
		        $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
		        
		        //Get lightbox content
		        $image_title = '';
		        $image_desc = '';
		        switch($settings['lightbox_content'])
				{
					case 'title':
						$image_title = get_the_title($image_id);
					break;
					
					case 'title_caption':
						$image_title = get_the_title($image_id);
						$image_desc = get_post_field('post_excerpt', $image_id);
					break;
				}
			}
			else
			{
				$thumb_image_url[0] = $image['url'];
				$lightbox_thumb_image_url[0] = $image['url'];
				$image_alt = '';
				$image_title = '';
		        $image_desc = '';
			}
			
			$return_attr = hoteller_get_lazy_img_attr();
?>
		<div class="gallery_grid_item <?php echo esc_attr($column_class); ?> <?php echo esc_attr($last_class); ?> <?php echo esc_attr($hover_class); ?> <?php if(!empty($tg_enable_lazy_loading)) { ?>lazy<?php } ?>">
			<?php
				if($is_lighbox)	
				{
			?>
				<a class="tg_gallery_lightbox" href="<?php echo esc_url($image['url']); ?>" data-thumb="<?php echo esc_url($lightbox_thumb_image_url[0]); ?>" data-rel="tg_gallery<?php echo esc_attr($widget_id); ?>" <?php if(!empty($image_title)) { ?>data-title="<?php echo esc_attr($image_title); ?>"<?php } ?> <?php if(!empty($image_desc)) { ?>data-desc="<?php echo esc_attr($image_desc); ?>"<?php } ?>>
			<?php
				}
			?>
				<img <?php echo hoteller_get_blank_img_attr(); ?> <?php echo esc_attr($return_attr['source']); ?>="<?php echo esc_url($thumb_image_url[0]); ?>" class="<?php echo esc_attr($return_attr['class']); ?>" alt="<?php echo esc_attr($image_alt); ?>" />
			<?php
				if($settings['show_title'] == 'yes')
				{
					if(empty($image_title))
					{
						$image_title = get_the_title($image_id);
					}
			?>		
				<div class="bg_overlay"></div>
				<div class="tg_gallery_grid_title"><?php echo esc_html($image_title); ?></div>
			<?php
				}
				
				if($is_lighbox)	
				{
			?>
				</a>
			<?php
				}
			?>
		</div>
<?php
			$count++;
		}
?>
<?php
	if($settings['spacing'] == 'yes')
	{
?>
<br class="clear"/>
<?php
	}
?>
</div>
<?php
	}
?>