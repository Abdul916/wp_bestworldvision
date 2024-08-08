<?php
	$widget_id = $this->get_id();
	
	//Get all settings
	$settings = $this->get_settings();
?>
<div class="themegoods-animated-text text-alignment-<?php echo esc_attr($settings['title_alignment']); ?> transition-<?php echo esc_attr($settings['title_transition_from']); ?> overflow-<?php echo esc_attr($settings['title_transition_overflow']); ?>" data-delimiter="<?php echo esc_attr($settings['title_delimiter_type']); ?>" data-transition="<?php echo esc_attr($settings['title_transition_speed']['size']); ?>" data-transition-delay="<?php echo esc_attr($settings['title_transition_delay']); ?>" data-transition-duration="<?php echo esc_attr($settings['title_transition_duration']['size']); ?>">
	<?php
		if(!empty($settings['title_link']['url']))
		{
			$target = $settings['title_link']['is_external'] ? 'target="_blank"' : '';
	?>
	<a href="<?php echo esc_url($settings['title_link']['url']); ?>" <?php echo esc_attr($target); ?>>
	<?php
		}
	?>
	<<?php echo esc_attr($settings['title_html_tag']); ?>>
		<?php echo ($settings['title_content']); ?>
	</<?php echo esc_attr($settings['title_html_tag']); ?>>
	<?php
		if(!empty($settings['title_link']['url']))
		{
			$target = $settings['title_link']['is_external'] ? 'target="_blank"' : '';
	?>
	</a>
	<?php
		}
	?>
</div>