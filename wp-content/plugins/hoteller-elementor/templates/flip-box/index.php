<?php
	$widget_id = $this->get_id();
	$settings = $this->get_settings();
?>
<div class="tg_flip_box_wrapper square-flip">
	<div class="square" data-image="<?php echo esc_url($settings['default_image']['url']); ?>">
		<div class="square-container">
			<?php
				if(!empty($settings['default_title']))
				{
			?>
				<h2><?php echo esc_html($settings['default_title']); ?></h2>
			<?php
				}

				if(!empty($settings['default_description']))
				{
			?>
				<div class="square-desc"><?php echo esc_html($settings['default_description']); ?></div>
			<?php
				}
			?>
		</div>
		<div class="flip-overlay"></div>
	</div>
	<div class="square2" data-image="<?php echo esc_url($settings['flip_image']['url']); ?>">
		<div class="square-container2">
			<div class="align-center"></div>
			<?php
				if(!empty($settings['flip_title']))
				{
			?>
				<h2><?php echo esc_html($settings['flip_title']); ?></h2>
			<?php
				}

				if(!empty($settings['flip_button_title']))
				{
					$target = $settings['flip_button_link']['is_external'] ? 'target="_blank"' : '';
			?>
				<a class="button" href="<?php echo esc_url($settings['flip_button_link']['url']); ?>"  <?php echo esc_attr($target); ?> class="boxshadow button"><?php echo esc_html($settings['flip_button_title']); ?></a>
			<?php
				}
			?>
		</div>
		<div class="flip-overlay"></div>
	</div>
</div>