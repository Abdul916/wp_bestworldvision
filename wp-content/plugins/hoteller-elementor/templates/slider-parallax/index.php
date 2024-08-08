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
		
		$navigation = 0;
		if($settings['navigation'] == 'yes')
		{
			$navigation = 1;
		}
?>
<div class="slider_parallax_wrapper" data-autoplay="<?php echo intval($timer); ?>" data-pagination="<?php echo intval($pagination); ?>" data-navigation="<?php echo intval($navigation); ?>">
	<div class="slider_parallax_inner">
		<div class="slider_parallax_slides">
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
				<div class="slide <?php if($count == 1) { ?>is-active<?php } ?> ">
		          	<div class="slide-content">
		            	<div class="caption">
							<div class="title"><h2><?php echo esc_html($slide['slide_title']); ?></h2></div>
							<div class="text">
		                		<?php echo $slide['slide_description']; ?>
		              		</div> 
			                <?php 
								if(!empty($slide['slide_link']['url']))
								{
									$target = $slide['slide_link']['is_external'] ? 'target="_blank"' : '';
							?>
							<a class="button" href="<?php echo esc_url($slide['slide_link']['url']); ?>" <?php echo esc_attr($target); ?>>
								<?php echo esc_html($slide['slide_link_title']); ?>
							</a>
							<?php
								}
							?>
		            	</div>
		          	</div>
				  	<div class="image-container"> 
		            	<img src="<?php echo esc_url($image_url[0]); ?>" alt="<?php echo esc_attr($image_alt); ?>" class="image" />
		          	</div>
		        </div>
		<?php
					$count++;
				}
		?>
		</div>
		<?php
		     if($settings['pagination'] == 'yes')
		     {
		?>
		<div class="pagination">
			<?php
				foreach ( $slides as $key => $slide ) 
				{
			?>
		        <div class="item <?php if($key == 0) { ?>is-active<?php } ?>"> 
		          <span class="icon"><?php echo intval($key+1); ?></span>
		        </div>
	        <?php
		        }
		    ?>
	    </div>
	    <?php
		    }
		?>
	    <?php
		     if($settings['navigation'] == 'yes')
		     {
		?>
	    <div class="arrows">
	        <div class="arrow prev">
	          <span class="svg svg-arrow-left">
	            <svg version="1.1" id="svg4-Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="14px" height="26px" viewBox="0 0 14 26" enable-background="new 0 0 14 26" xml:space="preserve"> <path d="M13,26c-0.256,0-0.512-0.098-0.707-0.293l-12-12c-0.391-0.391-0.391-1.023,0-1.414l12-12c0.391-0.391,1.023-0.391,1.414,0s0.391,1.023,0,1.414L2.414,13l11.293,11.293c0.391,0.391,0.391,1.023,0,1.414C13.512,25.902,13.256,26,13,26z"/> </svg>
	            <span class="alt sr-only"></span>
	          </span>
	        </div>
	        <div class="arrow next">
	          <span class="svg svg-arrow-right">
	            <svg version="1.1" id="svg5-Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="14px" height="26px" viewBox="0 0 14 26" enable-background="new 0 0 14 26" xml:space="preserve"> <path d="M1,0c0.256,0,0.512,0.098,0.707,0.293l12,12c0.391,0.391,0.391,1.023,0,1.414l-12,12c-0.391,0.391-1.023,0.391-1.414,0s-0.391-1.023,0-1.414L11.586,13L0.293,1.707c-0.391-0.391-0.391-1.023,0-1.414C0.488,0.098,0.744,0,1,0z"/> </svg>
	            <span class="alt sr-only"></span>
	          </span>
	        </div>
	    </div>
	    <?php
		    }
		?>
	</div>
</div>
<?php
	}
?>