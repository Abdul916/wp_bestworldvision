<?php
	$widget_id = $this->get_id();
	$images = $this->get_settings('gallery');
	
	if(!empty($images))
	{
		//Get all settings
		$settings = $this->get_settings();
		
		$content_class = 'right';
		$slider_class = 'left';
		if($settings['image_align'] == 'right')
		{
			$content_class = 'left';
			$slider_class = 'right';
		}
?>
<section class="tg_slider_property_clip_wrapper intro">
	<div class="content <?php echo esc_attr($content_class); ?>">
        <div>
	        <?php 
		        if(!empty($settings['subtitle'])) 
		        {
			?>
	    	<span class="subtitle"><?php echo esc_html($settings['subtitle']); ?></span>
	    	<?php
		    	}
		    ?>
		    <?php 
		        if(!empty($settings['title'])) 
		        {
			?>
	    	<h1><?php echo esc_html($settings['title']); ?></h1>
	    	<?php
		    	}
		    ?>
		    <?php 
		        if(!empty($settings['description'])) 
		        {
					echo wp_kses_post($settings['description']);
		    	}
		    ?>
		</div>
    </div>
    <div class="slider <?php echo esc_attr($slider_class); ?>">
        <ul>
	<?php
		foreach ( $images as $image ) 
		{	
			if(isset($image['id']) && !empty($image['id']))
			{
				$image_id = $image['id'];
			}
			else
			{
				$image_id = framed_get_image_id($image['url']);
			}
			
			$image_url = wp_get_attachment_image_src($image_id, $settings['image_size'], true);
	?>
		<li class="tg_horizontal_gallery_cell" style="background-image:url(<?php echo esc_url($image_url[0]); ?>);"></li>
	<?php
		}
	?>
        </ul>
        
        <ul>
	        <nav>
	<?php
		foreach ( $images as $image ) 
		{	
	?>
		<a href="javascript:;"></a>
	<?php
		}
	?>
	        </nav>
        </ul>
    </div>
</section>
<?php
	}
?>