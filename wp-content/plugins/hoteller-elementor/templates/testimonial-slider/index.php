<?php
	$widget_id = $this->get_id();
	$slides = $this->get_settings('slides');
	
	if(!empty($slides))
	{
		//Get all settings
		$settings = $this->get_settings();
		
		$autoplay = 0;
		if($settings['autoplay'] == 'yes')
		{
			$autoplay = 1;
		}
		
		$timer = intval($settings['timer']['size']*1000);
?>
<div class="testimonials-slider-wrapper">
	<div class="testimonial-carousel owl-carousel" data-autoplay="<?php echo intval($autoplay); ?>" data-timer="<?php echo intval($timer); ?>">
<?php
		$counter = 1;
	
		foreach ($slides as $slide) 
		{
			$testimonial_ID = $slide['_id'];
								
			//Get testimonial meta
			$testimonial_name = $slide['slide_name'];
			$testimonial_position = $slide['slide_desc'];
			
			$has_thumbnail_class = '';
			
			if(is_numeric($slide['slide_image']['id']) && !empty($slide['slide_image']['id']))
			{
				if(is_numeric($slide['slide_image']['id']) && (!isset($_GET['elementor_library']) OR empty($_GET['elementor_library'])))
				{
					$image_url = wp_get_attachment_image_src($slide['slide_image']['id'], 'thumbnail', true);
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
			
			if(!isset($image_url[0]) OR empty($image_url[0]))
			{
				$has_thumbnail_class = 'no-thumbnail';
			}
?>
			<div class="testimonial-block <?php echo esc_attr($has_thumbnail_class); ?>">
				<div class="inner-box">
					<?php
						if(!empty($slide['slide_description']))
						{
					?>
					<div class="text"><?php echo esc_html($slide['slide_description']); ?></div>
					<?php
						}
					?>
					<div class="info-box">
						<?php
							if(isset($image_url[0]) && !empty($image_url[0]))
							{
						?>
						<div class="thumb">
							<img src="<?php echo esc_url($image_url[0]); ?>" alt="<?php echo esc_attr($image_alt);?>" />
						</div>
						<?php
							}
						?>
						<?php
							 if(!empty($testimonial_name))
							 {
						?>
							 <h4 class="name"><?php echo esc_html($testimonial_name); ?></h4>
						<?php
							}
						?>
						<?php
							 if(!empty($testimonial_position))
							 {
								$client_position_html = '<span class="testimonial-client-position">'.$testimonial_position.'</span>';
						?>
							<span class="designation"><?php echo $client_position_html; ?></span>
						<?php
							}
						?>
					</div>
				</div>
			</div>
<?php
			$counter++;
		}
?>
	</div>
</div>
<?php
	}
?>