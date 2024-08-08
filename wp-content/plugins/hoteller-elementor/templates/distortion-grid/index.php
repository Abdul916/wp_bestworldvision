<?php
	$widget_id = $this->get_id();
	$slides = $this->get_settings('slides');
	$theme = $this->get_settings('theme');
	$count_slides = count($slides);
	
	$additional_attr = '';
	switch($theme)
	{
		case 1:
		default:
			$theme_code = 2;
			$displacement = '8.jpg';
			$intensity = -0.65;
			$speed_in = 'data-speedIn="1.2"';
			$speed_out = 'data-speedOut="1.2"';
		break;
		
		case 2:
			$theme_code = 3;
			$displacement = '4.png';
			$intensity = 0.2;
			$speed_in = 'data-speedIn="1.6"';
			$speed_out = 'data-speedOut="1.6"';
		break;
		
		case 3:
			$theme_code = 6;
			$displacement = '1.jpg';
			$intensity = -0.4;
			$speed_in = 'data-speedIn="0.7"';
			$speed_out = 'data-speedOut="0.3"';
			$additional_attr = 'data-easing="Sine.easeOut"';
		break;
		
		case 4:
			$theme_code = 7;
			$displacement = '7.jpg';
			$intensity = 0.9;
			$speed_in = 'data-speedIn="0.8"';
			$speed_out = 'data-speedOut="0.4"';
			$additional_attr = 'data-easing="Circ.easeOut"';
		break;
		
		case 5:
			$theme_code = 10;
			$displacement = '10.jpg';
			$intensity = 0.7;
			$speed_in = 'data-speedIn="1"';
			$speed_out = 'data-speedOut="0.5"';
			$additional_attr = 'data-easing="Power2.easeOut"';
		break;
		
		case 6:
			$theme_code = 11;
			$displacement = '6.jpg';
			$intensity = 0.6;
			$speed_in = 'data-speedIn="1.2"';
			$speed_out = 'data-speedOut="0.5"';
		break;
		
		case 7:
			$theme_code = 14;
			$displacement = '11.jpg';
			$intensity = 0.4;
			$speed_in = 'data-speedIn="1"';
			$speed_out = 'data-speedOut="1"';
		break;
		
		case 8:
			$theme_code = 15;
			$displacement = '2.jpg';
			$intensity = 0.6;
			$speed_in = 'data-speedIn="1"';
			$speed_out = 'data-speedOut="1"';
		break;
		
		case 9:
			$theme_code = 15;
			$displacement = '15.jpg';
			$intensity = -0.1;
			$speed_in = 'data-speedIn="0.4"';
			$speed_out = 'data-speedOut="0.4"';
			$additional_attr = 'data-easing="power2.easeInOut"';
		break;
		
		case 10:
			$theme_code = 19;
			$displacement = '13.jpg';
			$intensity = 0.2;
			$speed_in = '';
			$speed_out = '';
		break;
	}
	
	if(!empty($slides))
	{
		//Get all settings
		$settings = $this->get_settings();
		
		$thumb_image_name = 'hoteller-gallery-list';
?>
<div class="distortion_grid_wrapper">
<?php		
		$count = 1;
		
		foreach ( $slides as $slide ) 
		{	
			//Get first image URL
			if(is_numeric($slide['slide_image']['id']) && !empty($slide['slide_image']['id']))
			{
				if(is_numeric($slide['slide_image']['id']) && (!isset($_GET['elementor_library']) OR empty($_GET['elementor_library'])))
				{
					$image_url = wp_get_attachment_image_src($slide['slide_image']['id'], $thumb_image_name, true);
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
			
			//Get second image URL
			if(is_numeric($slide['slide_image_hover']['id']) && !empty($slide['slide_image_hover']['id']))
			{
				if(is_numeric($slide['slide_image_hover']['id']) && (!isset($_GET['elementor_library']) OR empty($_GET['elementor_library'])))
				{
					$image_hover_url = wp_get_attachment_image_src($slide['slide_image_hover']['id'], $thumb_image_name, true);
				}
				else
				{
					$image_hover_url[0] = $slide['slide_image_hover']['url'];
				}
				
				//Get image meta data
				$image_hover_alt = get_post_meta($slide['slide_image_hover']['id'], '_wp_attachment_image_alt', true);
			}
			else
			{
				$image_hover_url[0] = $slide['slide_image_hover']['url'];
				$image_hover_alt = '';
			}
			
			$target = $slide['slide_link']['is_external'] ? 'target="_blank"' : '';
?>
		<div class="distortion_grid_item distortion_grid_item--bg theme-<?php echo esc_attr($theme_code); ?>">
			<div class="distortion_grid_item-img" data-displacement="<?php echo plugins_url( '/hoteller-elementor/assets/images/distortion-grid/' ); ?><?php echo esc_attr($displacement); ?>" data-intensity="<?php echo esc_attr($intensity); ?>" <?php echo $speed_in; ?> <?php echo $speed_out; ?> <?php echo $additional_attr; ?>>
				<img src="<?php echo esc_url($image_url[0]); ?>" alt="<?php echo esc_attr($image_alt); ?>"/>
				<img src="<?php echo esc_url($image_hover_url[0]); ?>" alt="<?php echo esc_attr($image_hover_alt); ?>"/>
			</div>
			<div class="distortion_grid_item-content">
				<span class="distortion_grid_item-meta"><?php echo esc_html($slide['slide_subtitle']); ?></span>
				<h2 class="distortion_grid_item-title"><?php echo esc_html($slide['slide_title']); ?></h2>
				<div class="distortion_grid_item-subtitle">
					<span><?php echo esc_html($slide['slide_excerpt']); ?></span>
					<a class="distortion_grid_item-link" href="<?php echo esc_url($slide['slide_link']['url']); ?>" <?php echo esc_attr($target); ?>><?php echo esc_html($slide['slide_link_title']); ?></a>
				</div>
			</div>
		</div>
<?php
			$count++;
		}
?>
</div>
<?php
	}
?>