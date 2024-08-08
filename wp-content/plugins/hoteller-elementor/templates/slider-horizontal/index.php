<?php
	$widget_id = $this->get_id();
	$slides = $this->get_settings('slides');

	if(!empty($slides))
	{
		//Get all settings
		$settings = $this->get_settings();
		$timer_arr = $this->get_settings('timer');
		$timer = intval($timer_arr['size']) * 1000;
		
		if($settings['autoplay'] != 'yes')
		{
			$timer = 0;
		}
		
		$loop = 0;
		if($settings['loop'] == 'yes')
		{
			$loop = 1;
		}
		
		$navigation = 0;
		if($settings['navigation'] == 'yes')
		{
			$navigation = 1;
		}
		
		$pagination = 0;
		if($settings['pagination'] == 'yes')
		{
			$pagination = 1;
		}
		
		$fullscreen = 0;
		if($settings['fullscreen'] == 'yes')
		{
			$fullscreen = 1;
		}
		
		$content_width = intval($settings['content_width']['size']);
		$gallery_width = intval(100 - $content_width);
?>
<div class="tg_horizontal_slider_wrapper" data-autoplay="<?php echo intval($timer); ?>" data-loop="<?php echo intval($loop); ?>" data-navigation="<?php echo intval($navigation); ?>" data-pagination="<?php echo intval($pagination); ?>" data-fullscreen="<?php echo intval($fullscreen); ?>">
<?php
		$counter = 0;
	
		foreach ($slides as $slide)
		{	
			//Get slide images
			$count_slide_img = count($slide['slide_image']);
			
?>
		<div class="tg_horizontal_slider_cell  title_align_<?php echo esc_attr($slide['slide_title_align']); ?>" style="height:<?php echo intval($settings['height']['size']).$settings['height']['unit']; ?>;">
			<div class="tg_horizontal_slider_content" style="padding:<?php echo intval($settings['spacing']['size']).$settings['spacing']['unit']; ?>;">
				<div class="tg_horizontal_slider_content_wrap">
					<div class="tg_horizontal_slider_content_cell">
						<?php
							if(!empty($slide['slide_title']))
							{
						?>
				     		<div class="tg_horizontal_slide_content_title"><h2><?php echo esc_html($slide['slide_title']); ?></h2></div>
				     	<?php
					     	}
					     	
					     	if(!empty($slide['slide_description']))
							{
						?>
							<div class="tg_horizontal_slide_content_desc"><?php echo $slide['slide_description']; ?></div>
						<?php 
							}
							if(!empty($slide['slide_link']['url']))
							{
								$target = $slide['slide_link']['is_external'] ? 'target="_blank"' : '';
						?>
						<a class="tg_horizontal_slide_content_link" href="<?php echo esc_url($slide['slide_link']['url']); ?>" <?php echo esc_attr($target); ?>><?php echo esc_html($slide['slide_link_title']); ?></a>
						<?php
							}
						?>
					</div>
				</div>
			</div>
			
			<?php
				switch($count_slide_img)
				{
					case 1:
						$first_image_url = wp_get_attachment_url($slide['slide_image'][0]['id']);
			?>
			<div class="tg_horizontal_slider_bg">
				<div class="tg_horizontal_slider_bg_one_cols" style="background-image:url(<?php echo esc_url($first_image_url); ?>);"></div>
			</div>
			<?php
					break;
					
					case 2:
						$first_image_url = wp_get_attachment_url($slide['slide_image'][0]['id']);
						$second_image_url = wp_get_attachment_url($slide['slide_image'][1]['id']);
			?>
			<div class="tg_horizontal_slider_bg">
				<div class="tg_horizontal_slider_bg_two_cols" style="background-image:url(<?php echo esc_url($first_image_url); ?>);"></div>
				
				<div class="tg_horizontal_slider_bg_two_cols last" style="background-image:url(<?php echo esc_url($second_image_url); ?>);"></div>
			</div>
			<?php
					break;
					
					case 3:
						$first_image_url = wp_get_attachment_url($slide['slide_image'][0]['id']);
						$second_image_url = wp_get_attachment_url($slide['slide_image'][1]['id']);
						$third_image_url = wp_get_attachment_url($slide['slide_image'][2]['id']);
			?>
			<div class="tg_horizontal_slider_bg">
				<div class="tg_horizontal_slider_bg_two_cols" style="background-image:url(<?php echo esc_url($first_image_url); ?>);"></div>
				
				<div class="tg_horizontal_slider_bg_two_cols last">
					<div class="tg_horizontal_slider_bg_two_rows" style="background-image:url(<?php echo esc_url($second_image_url); ?>);"></div>
				
					<div class="tg_horizontal_slider_bg_two_rows last" style="background-image:url(<?php echo esc_url($third_image_url); ?>);"></div>
				</div>
			</div>
			<?php
					break;
					
					case 4:
						$first_image_url = wp_get_attachment_url($slide['slide_image'][0]['id']);
						$second_image_url = wp_get_attachment_url($slide['slide_image'][1]['id']);
						$third_image_url = wp_get_attachment_url($slide['slide_image'][2]['id']);
						$fourth_image_url = wp_get_attachment_url($slide['slide_image'][3]['id']);
			?>
			<div class="tg_horizontal_slider_bg">
				<div class="tg_horizontal_slider_bg_two_cols">
					<div class="tg_horizontal_slider_bg_two_rows" style="background-image:url(<?php echo esc_url($first_image_url); ?>);"></div>
				
					<div class="tg_horizontal_slider_bg_two_rows last" style="background-image:url(<?php echo esc_url($second_image_url); ?>);"></div>
				</div>
				
				<div class="tg_horizontal_slider_bg_two_cols last">
					<div class="tg_horizontal_slider_bg_two_rows" style="background-image:url(<?php echo esc_url($third_image_url); ?>);"></div>
				
					<div class="tg_horizontal_slider_bg_two_rows last" style="background-image:url(<?php echo esc_url($fourth_image_url); ?>);"></div>
				</div>
			</div>
			<?php
					break;
				}
			?>
			
		</div>
<?php
			$counter++;
		}
?>
</div>
<?php
	}
?>