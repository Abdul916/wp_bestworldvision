<div class="service-grid-container">
<?php
	$widget_id = $this->get_id();
	$slides = $this->get_settings('slides');
	$count_slides = count($slides);
	
	if(!empty($slides))
	{		
		//Get all settings
		$settings = $this->get_settings();
		
		//Get spacing class
		$spacing_class = '';
		if($settings['spacing'] != 'yes')
		{
			$spacing_class = 'has-no-space';
		}
		
		//Get entrance animation option
		$smoove_animation_attr = '';
		switch($settings['entrance_animation'])
		{
			case 'slide-up':
			default:
				$smoove_animation_attr = 'data-move-y="60px"';
				
			break;
			
			case 'popout':
				$smoove_animation_attr = 'data-scale="0"';
				
			break;
			
			case 'fade-in':
				$smoove_animation_attr = 'data-opacity="0"';
				
			break;
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
		   		$column_class = 'tg_one_cols';
		   	break;
		   	
			case 2:
		   		$column_class = 'tg_two_cols';
		   	break;
		   	
		   	case 3:
		   	default:
		   		$column_class = 'tg_three_cols';
		   	break;
		   	
		   	case 4:
		   		$column_class = 'tg_four_cols';
		   	break;
		   	
		   	case 5:
		   		$column_class = 'tg_five_cols';
		   	break;
		}
?>
<div class="service-grid-content-wrapper layout-<?php echo esc_attr($column_class); ?> <?php echo esc_attr($spacing_class); ?>" data-cols="<?php echo esc_attr($settings['columns']['size']); ?>" data-offset="-50%">
<?php		
		$animation_class = '';
		if(isset($settings['disable_animation']))
		{
			$animation_class = 'disable_'.$settings['disable_animation'];
		}
		
		$smoove_min_width = 1;
		switch($settings['disable_animation'])
		{
			case 'none':
				$smoove_min_width = 1;
			break;
			
			case 'tablet':
				$smoove_min_width = 769;
			break;
			
			case 'mobile':
				$smoove_min_width = 415;
			break;
			
			case 'all':
				$smoove_min_width = 5000;
			break;
		}
	
		$last_class = '';
		$count = 1;
		
		foreach ( $slides as $slide ) 
		{
			$last_class = '';
			if($count%$settings['columns']['size'] == 0)
			{
				$last_class = 'last';
			}
			
			//Get featured image
			if(is_numeric($slide['slide_image']['id']) && !empty($slide['slide_image']['id']))
			{
				if(is_numeric($slide['slide_image']['id']) && (!isset($_GET['elementor_library']) OR empty($_GET['elementor_library'])))
				{
					$image_url = wp_get_attachment_image_src($slide['slide_image']['id'], 'large', true);
				}
				else
				{
					$image_url[0] = $slide['slide_image']['url'];
				}
				
				//Get image meta data
				$image_alt = get_post_meta($slide['slide_image']['id'], '_wp_attachment_image_alt', true);
			}
			else
			{
				$image_url[0] = $slide['slide_image']['url'];
				$image_alt = '';
			}
			
			//Calculation for animation queue
			if(!isset($queue))
			{
				$queue = 1;	
			}
			
			if($queue > $settings['columns']['size'])
			{
				$queue = 1;
			}
?>
		<div class="service-grid-wrapper <?php echo esc_attr($column_class); ?> <?php echo esc_attr($last_class); ?>  service-<?php echo esc_attr($count); ?> tile scale-anm all smoove <?php echo esc_attr($animation_class); ?> <?php echo esc_attr($settings['entrance_animation']); ?>" data-delay="<?php echo intval($queue*150); ?>" data-minwidth="<?php echo esc_attr($smoove_min_width); ?>" <?php echo $smoove_animation_attr; ?> style="background-image: url('<?php echo esc_url($image_url[0]); ?>');">
			<?php 
				if(!empty($slide['slide_link']['url']))
				{
					$target = $slide['slide_link']['is_external'] ? 'target="_blank"' : '';
			?>
			<a class="service-grid-link" href="<?php echo esc_url($slide['slide_link']['url']); ?>" <?php echo esc_attr($target); ?>></a>
			<?php
				}
			?>
			<div class="inner-wrap">
				<div class="inner-wrap-border">
					<div class="overflow-inner">
						<div class="header-wrap">
							<?php 
								if(isset($slide['slide_icon']['url']) && !empty($slide['slide_icon']['url']))	
								{
									if(is_numeric($slide['slide_icon']['id']) && !empty($slide['slide_icon']['id']))
									{
										if(is_numeric($slide['slide_icon']['id']) && (!isset($_GET['elementor_library']) OR empty($_GET['elementor_library'])))
										{
											$image_url = wp_get_attachment_image_src($slide['slide_icon']['id'], 'thumbnail', true);
										}
										else
										{
											$image_url[0] = $slide['slide_icon']['url'];
										}
										
										//Get image meta data
										$image_alt = get_post_meta($slide['slide_icon']['id'], '_wp_attachment_image_alt', true);
									}
									else
									{
										$image_url[0] = $slide['slide_icon']['url'];
										$image_alt = '';
									}
							?>
							<img src="<?php echo esc_url($image_url[0]); ?>" alt="<?php echo esc_attr($image_alt); ?>"></i>
							<?php
								}
							?>
							<h2 class="service"><?php echo esc_html($slide['slide_title']); ?></h2>
						</div>
						<div class="hover-content">
							<?php echo htmlspecialchars_decode($slide['slide_description']); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
<?php
			$count++;
			$queue++;
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
</div>