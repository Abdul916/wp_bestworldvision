<div class="food-menu-container">
<?php
	$widget_id = $this->get_id();
	$slides = $this->get_settings('slides');
	$count_slides = count($slides);
	
	if(!empty($slides))
	{
		//Get all settings
		$settings = $this->get_settings();
		
		$column_class = 1;
		$thumb_image_name = 'thumbnail';
?>
<div class="food-menu-content-wrapper food-menu">
<?php		
		$count = 1;
		
		foreach ( $slides as $slide ) 
		{
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
			
			//Calculate slide tags
			$slide_tags = $slide['slide_tag'];
?>
		<div class="food-menu-grid-wrapper food-tooltip food-menu-<?php echo esc_attr($count); ?> <?php if(!empty($slide_tags)) { ?>food-menu-highlight<?php } ?>" <?php if(!empty($slide['slide_nutrition']) && $settings['show_tooltip'] == 'yes') { ?>data-tooltip-content="#tooltip-content-<?php echo esc_attr($widget_id); ?>-<?php echo esc_attr($count); ?>"<?php } ?>>
			
			<?php 
				if(!empty($slide['slide_nutrition']) && $settings['show_tooltip'] == 'yes')
				{
			?>
			<div class="tooltip_templates">
			    <div id="tooltip-content-<?php echo esc_attr($widget_id); ?>-<?php echo esc_attr($count); ?>" class="food-menu-tooltip-content">
				    <h5><?php esc_html_e("Nutrition Information", 'hoteller-elementor' ); ?></h5>
				    <div class="food-menu-tooltip-templates-content">
			        	<?php echo htmlspecialchars_decode($slide['slide_nutrition']); ?>
				    </div>
			    </div>
			</div>
			<?php
				}
			?>
			
			<?php 
				if(!empty($slide_tags)) { 
			?>
				<div class="food-menu-content-highlight-holder">
					<h4><?php echo esc_html($slide_tags); ?></h4>
				</div>
			<?php
				}
			?>
			
			<?php
				if(isset($image_url[0]) && !empty($image_url[0]))
				{
			?>
				<div class="food-menu-img">
					<img src="<?php echo esc_url($image_url[0]); ?>" alt="<?php echo esc_attr($image_alt);?>" />
				</div>
			<?php
				}
			?>

			<div class="food-menu-content <?php if(isset($image_url[0]) && empty($image_url[0])) { ?>no-food-img<?php } ?> <?php if(!empty($slide_tags)) { ?>menu-highlight<?php } ?>">
				
				<div class="food-menu-content-top-holder">
					<div class="food-menu-content-title-holder">
						<h3 class="food-menu-title"><?php echo esc_html($slide['slide_title']); ?></h3>
					</div>
					
					<div class="food-menu-content-title-line"></div>
					
					<?php
						if(isset($slide['slide_sale_price']) OR isset($slide['slide_price']))
						{
					?>
					<div class="food-menu-content-price-holder">
						<?php
							if(isset($slide['slide_sale_price']) && !empty($slide['slide_sale_price']))
							{
						?>
							<span class="food-menu-content-price-sale">
								<?php echo esc_html($slide['slide_sale_price']); ?>
							</span>
						<?php
							}
						?>
						
						<?php
							if(isset($slide['slide_price']) && !empty($slide['slide_price']))
							{
						?>
						<span class="food-menu-content-price-normal">
							<?php echo esc_html($slide['slide_price']); ?>
						</span>
						<?php
							}
						?>
					</div>
					<?php
						}
					?>
				</div>
				
				<div class="food-menu-desc"><?php echo esc_html($slide['slide_description']); ?></div>
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
</div>