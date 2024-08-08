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

	$thumb_image_name = 'hoteller-gallery-grid';
	if(isset($settings['image_dimension']) && !empty($settings['image_dimension']))
	{
		//If display original dimension and less initial items then display higher resolution image
		if($settings['image_dimension'] == 'medium_large' && $settings['ini_item']['size'] < 3)
		{
			$settings['image_dimension'] = 'large';
		}
		
		$thumb_image_name = $settings['image_dimension'];
	}

	$widget_id = $this->get_id();
	
	//Get all slides contents
	$slides = $this->get_settings('slides');
	$count_slides = count($slides);
	
	if(!empty($slides))
	{
		//Get all settings
		$settings = $this->get_settings();
		$count = 1;
?>
<div class="service-carousel-wrapper">
	<div class="owl-carousel" data-pagination="<?php echo intval($pagination); ?>" data-autoplay="<?php echo intval($autoplay); ?>" data-timer="<?php echo intval($timer); ?>" data-items="<?php echo intval($settings['ini_item']['size']); ?>" data-stage-padding="<?php echo esc_attr($settings['stage_padding']['size']); ?>" data-margin="<?php echo esc_attr($settings['item_margin']['size']); ?>">
<?php
		foreach ( $slides as $slide ) 
		{
			//Get featured image
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
?>
			<div class="item">
				<?php 
				if(!empty($slide['slide_link']['url']))
					{
						$target = $slide['slide_link']['is_external'] ? 'target="_blank"' : '';
				?>
					<a href="<?php echo esc_url($slide['slide_link']['url']); ?>" <?php echo esc_attr($target); ?> ></a>
				<?php
					}
				?>
				
				<?php
					//Display featured image
					if(isset($image_url[0]) && !empty($image_url[0]))
					{
				?>
					<div class="service-carousel-image">
						<div class="service-carousel-image-overflow">
							<img src="<?php echo esc_url($image_url[0]); ?>" alt="<?php echo esc_attr($image_alt);?>" />
						</div>
						
						<?php 
						if(!empty($slide['slide_link']['url']))
							{
								$target = $slide['slide_link']['is_external'] ? 'target="_blank"' : '';
						?>
							<div class="service-carousel-link-button"><div class="service-carousel-link-label"><?php echo esc_html($slide['slide_link_title']); ?></div></div>
						<?php
							}
						?>
					</div>
				<?php
					}
				?>
					
				<?php
					//Display title and excerpt
					if(!empty($slide['slide_title']))
					{
				?>
					<div class="service-carousel-content">
						<div class="overflow-inner">
							<div class="overflow-text">
								<h3 class="service-carousel-title"><?php echo esc_html($slide['slide_title']); ?></h3>
								<div class="service-carousel-desc"><?php echo htmlspecialchars_decode($slide['slide_description']); ?></div>
							</div>
						</div>
					</div>
				<?php
					}
				?>
			</div>
<?php
		}	//End foreach	 
?>
	</div>
</div>
<?php
	}
?>