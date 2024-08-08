<?php
	$widget_id = $this->get_id();
	$images = $this->get_settings('gallery');
	
	if(!empty($images))
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
		
		$parallax = 0;
		if($settings['parallax'] == 'yes')
		{
			$parallax = 1;
		}
		
		$fullscreen = 0;
		if($settings['fullscreen'] == 'yes')
		{
			$fullscreen = 1;
		}
?>
<div class="tg_horizontal_gallery_wrapper" data-autoplay="<?php echo intval($timer); ?>" data-loop="<?php echo intval($loop); ?>" data-navigation="<?php echo intval($navigation); ?>" data-pagination="<?php echo intval($pagination); ?>" data-parallax="<?php echo intval($parallax); ?>" data-fullscreen="<?php echo intval($fullscreen); ?>">
<?php
		$counter = 0;
	
		foreach ( $images as $image ) 
		{	
			if(isset($image['id']) && !empty($image['id']))
			{
				$image_id = $image['id'];
			}
			else
			{
				$image_id = hoteller_get_image_id($image['url']);
			}
			
			$image_url = wp_get_attachment_image_src($image_id, $settings['image_size'], true);
			$themegoods_link_url = get_post_meta($image_id, 'themegoods_link_url', true);
			
			//Get image meta data
	        $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
?>
		<div class="tg_horizontal_gallery_cell" style="margin-right:<?php echo intval($settings['spacing']['size']).$settings['spacing']['unit']; ?>">
			<?php
				if(!empty($themegoods_link_url)) 
				{
			?>
			<a href="<?php echo esc_url($themegoods_link_url); ?>" target="_blank">
			<?php
				}
			?>
			<img class="tg_horizontal_gallery_cell_img" data-flickity-lazyload="<?php echo esc_url($image_url[0]); ?>" alt="<?php echo esc_attr($image_alt); ?>" style="height:<?php echo intval($settings['height']['size']).$settings['height']['unit']; ?>;" />
			<?php
				if(!empty($themegoods_link_url)) 
				{
			?>
			</a>
			<?php
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