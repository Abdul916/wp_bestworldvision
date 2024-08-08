<?php
	$widget_id = $this->get_id();
	$slides = $this->get_settings('slides');
	$count_slides = count($slides);
	
	if(!empty($slides))
	{
		//Get all settings
		$settings = $this->get_settings();
?>
<div class="timeline_wrapper">
<?php		
		$count = 1;
		
		foreach ( $slides as $slide ) 
		{	
			//Get image URL
			if(is_numeric($slide['slide_image']['id']) && !empty($slide['slide_image']['id']))
			{
				if(is_numeric($slide['slide_image']['id']) && (!isset($_GET['elementor_library']) OR empty($_GET['elementor_library'])))
				{
					$image_url = wp_get_attachment_image_src($slide['slide_image']['id'], $settings['image_size'], true);
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
		<div class="timeline_entry <?php if(isset($image_url[0]) && !empty($image_url[0]) && $settings['parallax'] == 'yes') { ?>stellar<?php } ?>">
			<div class="timeline_title">
				<?php 
					if(isset($image_url[0]) && !empty($image_url[0]))
					{
				?>
					<div class="timeline_image"><img src="<?php echo esc_url($image_url[0]); ?>" alt="<?php echo esc_attr($image_alt); ?>"/></div>
				<?php
					}
				?>
				<h3><?php echo esc_html($slide['slide_title']); ?></h3>
			</div>
			<div class="timeline_body"><p><?php echo esc_html($slide['slide_description']); ?></p></div>
		</div>
<?php
			$count++;
		}
?>
</div><br class="clear"/>
<?php
	}
?>