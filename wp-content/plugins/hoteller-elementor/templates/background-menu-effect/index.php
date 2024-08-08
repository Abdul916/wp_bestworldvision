<?php
	$widget_id = $this->get_id();
	$slides = $this->get_settings('slides');
	$count_slides = count($slides);
	
	if(!empty($slides))
	{
		//Get all settings
		$settings = $this->get_settings();
?>
<div class="themegoods-background-menu-wrapper">
	<div class="themegoods-background-menu-default"></div>
	<ul class="themegoods-background-menu display-<?php echo esc_attr($settings['title_width']); ?>">
		
		<?php		
			$count = 1;
			
			foreach ( $slides as $slide ) 
			{	
				//Get image URL
				if(is_numeric($slide['slide_image']['id']) && !empty($slide['slide_image']['id']))
				{
					if(is_numeric($slide['slide_image']['id']) && (!isset($_GET['elementor_library']) OR empty($_GET['elementor_library'])))
					{
						$image_url = wp_get_attachment_image_src($slide['slide_image']['id'], 'full', true);
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
				
				$target = $slide['slide_link']['is_external'] ? 'target="_blank"' : '';
		?>
		<li class="themegoods-background-menu__item">
			<a class="themegoods-background-menu__item-link" href="<?php echo esc_url($slide['slide_link']['url']); ?>" <?php echo esc_attr($target); ?>><?php echo esc_html($slide['slide_title']); ?></a>
			
			<span class="themegoods-background-menu__item-image">
				<img class="themegoods-background-menu__item-img" src="<?php echo esc_url($image_url[0]); ?>" alt="<?php echo esc_attr($image_alt); ?>"/>
			<span>
		</li>
		<?php
			}
		?>
		
	</ul>
</div>
<?php
	} //If slide is not empty
?>