<?php
	//Get all settings
	$settings = $this->get_settings();
	$shortcode = '';
?>
<div class="hoteller-contact-form-content-wrapper <?php echo esc_attr($settings['form_layout']); ?>">
	<?php
		if(isset($settings['form_id']) && !empty($settings['form_id'])) {
			echo do_shortcode('[contact-form-7 id="'.esc_attr($settings['form_id']).'" title="Contact form"]');
		}
	?>
</div>