<?php
	$widget_id = $this->get_id();
	$slides = $this->get_settings('slides');
	$count_slides = count($slides);
	
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
		
		$pagination = 0;
		if($settings['pagination'] == 'yes')
		{
			$pagination = 1;
		}
?>
<div class="slider_zoom_wrapper" data-autoplay="<?php echo intval($timer); ?>" data-pagination="<?php echo intval($pagination); ?>">
<?php		
		$count = 1;
		
		foreach ( $slides as $slide ) 
		{	
			//Get image URL
			if(is_numeric($slide['slide_image']['id']) && !empty($slide['slide_image']['id']))
			{
				if(is_numeric($slide['slide_image']['id']) && (!isset($_GET['elementor_library']) OR empty($_GET['elementor_library'])))
				{
					$image_url = wp_get_attachment_image_src($slide['slide_image']['id'], 'original', true);
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
?>
		<div class="slideshow__slide js-slider-home-slide" data-slide="<?php echo esc_attr($count); ?>">
			<div class="slideshow__slide-background-parallax background-absolute js-parallax" data-speed="-1" data-position="top">
				<div class="slideshow__slide-background-load-wrap background-absolute">
					<div class="slideshow__slide-background-load background-absolute">
						<div class="slideshow__slide-background-wrap background-absolute">
							<div class="slideshow__slide-background background-absolute">
								<div class="slideshow__slide-image-wrap background-absolute">
									<div class="slideshow__slide-image background-absolute" style="background-image: url(<?php echo esc_url($image_url[0]); ?>);"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="slideshow__slide-caption">
				<div class="slideshow__slide-caption-text">
					<div class="container js-parallax title_align_<?php echo esc_attr($slide['slide_title_align']); ?>" data-speed="2" data-position="top">
						<h2 class="slideshow__slide-caption-title"><?php echo esc_html($slide['slide_title']); ?></h2>
						<div class="slideshow__slide-desc"><?php echo esc_html($slide['slide_description']); ?></div>
						<?php 
							if(!empty($slide['slide_link']['url']))
							{
								$target = $slide['slide_link']['is_external'] ? 'target="_blank"' : '';
						?>
						<a class="slideshow__slide-caption-subtitle -load o-hsub -link" href="<?php echo esc_url($slide['slide_link']['url']); ?>" <?php echo esc_attr($target); ?>>
							<span class="slideshow__slide-caption-subtitle-label"><?php echo esc_html($slide['slide_link_title']); ?></span>
						</a>
						<?php
							}
						?>
					</div>
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