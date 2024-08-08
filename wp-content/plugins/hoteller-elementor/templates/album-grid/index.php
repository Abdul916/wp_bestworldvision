<?php
	$widget_id = $this->get_id();
	$slides = $this->get_settings('slides');
	$count_slides = count($slides);
	
	if(!empty($slides))
	{
		//Get all settings
		$settings = $this->get_settings();
		
		//Get spacing class
		$spacing_class = '';
		if($settings['spacing'] != 'yes')
		{
			$spacing_class = 'has_no_space';
		}
		
		$column_class = 1;
		$thumb_image_name = 'hoteller-album-grid';
		
		//Start displaying gallery columns
		switch($settings['columns']['size'])
		{
			case 2:
		   		$column_class = 'tg_two_cols';
		   	break;
		   	
		   	case 3:
		   	default:
		   		$column_class = 'tg_three_cols';
		   	break;
		   	
		   	case 4:
		   		$column_class = 'tg_four_cols';
		   	break;
		   	
		   	case 5:
		   		$column_class = 'tg_five_cols';
		   	break;
		   	
		   	case 6:
		   		$column_class = 'tg_six_cols';
		   	break;
		}
?>
<div class="gallery_grid_content_wrapper album_grid layout_<?php echo esc_attr($column_class); ?> <?php echo esc_attr($spacing_class); ?>">
<?php		
		$last_class = '';
		$count = 1;
		
		foreach ( $slides as $slide ) 
		{
			$last_class = '';
			if($count%$settings['columns']['size'] == 0)
			{
				$last_class = 'last';
			}
			
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
			
			$target = $slide['slide_link']['is_external'] ? 'target="_blank"' : '';
?>
		<a href="<?php echo esc_url($slide['slide_link']['url']); ?>" <?php echo esc_attr($target); ?> class="gallery_grid_item <?php echo esc_attr($column_class); ?> <?php echo esc_attr($last_class); ?> tilter tilter--<?php echo esc_attr($settings['layout']); ?>">
			<figure class="tilter__figure">
				<img class="tilter__image" src="<?php echo esc_url($image_url[0]); ?>" alt="<?php echo esc_attr($image_alt);?>" />
				<?php
					if($settings['glare'] == 'yes')
					{
				?>
				<div class="tilter__deco tilter__deco--shine"><div></div></div>
				<?php 
					}

					if($settings['overlay'] == 'yes')
					{
				?>
				<div class="tilter__deco tilter__deco--overlay"></div>
				<?php 
					}
				?>	
				<figcaption class="tilter__caption">
					<h3 class="tilter__title"><?php echo esc_html($slide['slide_title']); ?></h3>
					<p class="tilter__description"><?php echo esc_html($slide['slide_subtitle']); ?></p>
				</figcaption>
				
				<?php
					if($settings['layout'] != 3)
					{
				?>
				<svg class="tilter__deco tilter__deco--lines" viewBox="0 0 300 415">
					<path d="M20.5,20.5h260v375h-260V20.5z" />
				</svg>
				<?php
					}
				?>
			</figure>
		</a>
<?php
			$count++;
		}
?>
<?php
	if($settings['spacing'] == 'yes')
	{
?>
<br class="clear"/>
<?php
	}
?>
</div>
<?php
	}
?>