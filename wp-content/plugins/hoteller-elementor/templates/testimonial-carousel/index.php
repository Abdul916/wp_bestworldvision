<?php
	$widget_id = $this->get_id();
	$slides = $this->get_settings('slides');
	
	if(!empty($slides))
	{
		//Get all settings
		$settings = $this->get_settings();
		
		$pagination = 0;
		if($settings['pagination'] == 'yes')
		{
			$pagination = 1;
		}
		
		$autoplay = 0;
		if($settings['autoplay'] == 'yes')
		{
			$autoplay = 1;
		}
		
		$timer = intval($settings['timer']['size']*1000);
?>
<div class="testimonials-carousel-wrapper">
	<div class="owl-carousel" data-pagination="<?php echo intval($pagination); ?>" data-autoplay="<?php echo intval($autoplay); ?>" data-timer="<?php echo intval($timer); ?>">
<?php
		$counter = 1;
	
		foreach ($slides as $slide) 
		{
?>
			<div class="item">
				<div class="shadow-effect">	
					
		          	<?php
			          	if(!empty($slide['slide_description']))
						{
					?>
						<div class="testimonial-info-desc">
							<?php echo esc_html($slide['slide_description']); ?>
						</div>
					<?php
						}
					?>
					
					<?php
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
						
						if(isset($image_url[0]) && !empty($image_url[0]))
						{
					?>
					<div class="testimonial-info-img">
						<img class="img-circle" src="<?php echo esc_url($image_url[0]); ?>" alt="<?php echo esc_attr($image_alt);?>" />
					</div>
					<?php
						}
					?>
					
					<?php
					 	if(!empty($slide['slide_name']))
					 	{
					?>
					 	<div class="testimonial-name"><?php echo esc_html($slide['slide_name']); ?></div>
					<?php
					    }
					?>
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