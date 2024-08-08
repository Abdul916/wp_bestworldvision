<?php
	$widget_id = $this->get_id();
	$slides = $this->get_settings('slides');

	if(!empty($slides))
	{
		//Get all settings
		$settings = $this->get_settings();
?>
<div class="tg_portfolio_timeline_wrapper cd-horizontal-timeline" data-min-distance="<?php echo esc_attr($settings['min_distance']); ?>">
	<div class="timeline">
		<div class="events-wrapper">
			<div class="events">
				<ol>
					<?php
						$counter = 0;
						
						foreach ($slides as $slide)
						{
							$slide_date = $slide['slide_date'];
							$slide_date_format = $slide['slide_date_format'];
					?>
					<li><a href="#0" data-date="<?php echo esc_attr(date("d/m/Y", strtotime($slide_date))); ?>" <?php if($counter == 0){ ?>class="selected"<?php } ?>><?php echo esc_attr(date($slide_date_format, strtotime($slide_date))); ?></a></li>
					<?php
							$counter++;
						}
					?>
				</ol>

				<span class="filling-line" aria-hidden="true"></span>
			</div> <!-- .events -->
		</div> <!-- .events-wrapper -->
			
		<ul class="cd-timeline-navigation">
			<li><a href="#0" class="prev inactive"></a></li>
			<li><a href="#0" class="next"></a></li>
		</ul> <!-- .cd-timeline-navigation -->
	</div> <!-- .timeline -->
	
	<div class="events-content">
		<ol>
<?php
		$counter = 0;
	
		foreach ($slides as $slide)
		{	
			$slide_date = $slide['slide_date'];
			$slide_date_format = $slide['slide_date_format'];
			$slide_title = $slide['slide_title'];
			$slide_subtitle = $slide['slide_subtitle'];
			$slide_description = $slide['slide_description'];
			
			if(is_numeric($slide['slide_image']['id']) && !empty($slide['slide_image']['id']))
			{
				if(is_numeric($slide['slide_image']['id']) && (!isset($_GET['elementor_library']) OR empty($_GET['elementor_library'])))
				{
					$image_url = wp_get_attachment_image_src($slide['slide_image']['id'], 'large', true);
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
			<li <?php if($counter == 0){ ?>class="selected"<?php } ?> data-date="<?php echo esc_attr(date("d/m/Y", strtotime($slide_date))); ?>">
				<div class="portfolio_timeline_content_wrapper">
					<div class="portfolio_timeline_content <?php echo esc_attr($content_class); ?>">
						<?php
							if(!empty($slide_title))
							{
						?>
							<h2><?php echo esc_html($slide_title); ?></h2>
						<?php
						 	}
						?>
						<?php
							if(!empty($slide_subtitle))
							{
						?>
							<em><?php echo esc_html($slide_subtitle); ?></em>
						<?php
						 	}
						?>
						<?php
							if(!empty($slide_description))
							{
						?>
							<div class="events-content-desc"><?php echo wp_kses_post($slide_description); ?></div>
						<?php
						 	}
						?>
						<?php
							if(isset($slide['slide_link']['url']) && !empty($slide['slide_link']['url'])) 
							{
						?>
							<a rel="noopener noreferrer" class="portfolio_timeline_link" href="<?php echo esc_url($slide['slide_link']['url']); ?>" <?php echo esc_attr($target); ?> ><span class="ti-arrow-right"></span></a>
						<?php 
							}
						?>
					</div>
					<?php 
						$content_class = "one";
						if(isset($image_url[0]) && !empty($image_url[0]))
						{
							$content_class = "one_third";
					?>
						<div class="portfolio_timeline_img">
							<img src="<?php echo esc_url($image_url[0]); ?>" alt="<?php echo esc_attr($image_alt);?>" />
						</div>
					<?php
						}
					?>
			</div>
			</li>
<?php
			$counter++;
		}
?>
		</ol>
	</div>
</div>
<?php
	}
?>