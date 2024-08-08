<?php
	$widget_id = $this->get_id();
	$slides = $this->get_settings('slides');
	
	if(!empty($slides))
	{
		//Get all settings
		$settings = $this->get_settings();
		$count_slide = count($slides);
		
		$slide_titles = array();
		$slide_images = array();
		$slide_button_title = array();
		$slide_button_url = array();
		
		foreach ($slides as $slide) 
		{
			$slide_titles[] = $slide['slide_title'];
			
			$slide_image_id = $slide['slide_image']['id'];
			$slider_image_arr = wp_get_attachment_image_src($slide_image_id, 'medium_large', true);
			
			if(isset($slider_image_arr[0]) && !empty($slider_image_arr[0]))
			{
				$slide_images[] = $slider_image_arr[0];
			}
			else
			{
				$slide_images[] = $slide['slide_image']['url'];
			}
			
			$slide_button_title[] = $slide['slide_button_title'];
			$slide_button_url[] = $slide['slide_button_link']['url'];
		}
		
		//Get pagination class
		$pagination_class = '';
		if($settings['pagination'] != 'yes')
		{
			$pagination_class = 'has-no-pagination';
		}
?>
<div id="tg_synchronized_carousel_slider_<?php echo esc_attr($widget_id); ?>" data-pagination="tg_synchronized_carousel_pagination_<?php echo esc_attr($widget_id); ?>" class="tg_synchronized_carousel_slider_wrapper sliders-container <?php echo esc_attr($pagination_class); ?>" data-countslide="<?php echo esc_attr($count_slide); ?>" data-slidetitles="<?php echo esc_attr(json_encode($slide_titles)); ?>" data-slideimages="<?php echo esc_attr(json_encode($slide_images)); ?>" data-slidebuttontitles="<?php echo esc_attr(json_encode($slide_button_title)); ?>" data-slidebuttonurls="<?php echo esc_attr(json_encode($slide_button_url)); ?>">
	<ul id="tg_synchronized_carousel_pagination_<?php echo esc_attr($widget_id); ?>" class="tg_synchronized_carousel_pagination">
	
<?php
		foreach ($slides as $slide) 
		{
?>
		<li class="pagination__item"><a class="pagination__button"></a></li>
<?php
		}
?>
	</ul>
</div>
<br class="clear"/>
<?php
	}
?>