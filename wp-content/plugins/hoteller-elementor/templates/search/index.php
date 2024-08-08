<?php
	$widget_id = $this->get_id();
	
	//Get all settings
	$settings = $this->get_settings();
	
	$redirect_to_page = get_site_url();
	if(!empty($settings['redirect_page']))
	{
		$redirect_to_page = get_the_permalink($settings['redirect_page']);
	}
	
	$keyword = '';
	if(isset($_GET['s']))
	{
		$keyword = $_GET['s'];
	}
?>
<div class="hoteller-search-icon">
	<a data-open="tg_search_<?php echo esc_attr($widget_id); ?>" href="javascript:;">
		<?php 
			if($settings['icon_content_type'] == 'font_icon') {
				\Elementor\Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] ); 
			}
			else if(isset($settings['icon_image']['url'])) {
		?>
			<img src="<?php echo esc_url($settings['icon_image']['url']); ?>" alt="<?php esc_html_e('Search', 'hoteller-elementor' ); ?>"/>
		<?php
			}
		?>
	</a>
</div>

<div id="tg_search_<?php echo esc_attr($widget_id); ?>" class="hoteller-search-wrapper">
	<div class="hoteller-search-inner">
		<form id="tg_search_form_<?php echo esc_attr($widget_id); ?>" class="tg_search_form <?php if($settings['autocomplete'] == 'yes') { ?>autocomplete_form<?php } ?>" method="get" action="<?php echo esc_url($redirect_to_page); ?>" data-result="autocomplete_<?php echo esc_attr($widget_id); ?>" data-open="tg_search_<?php echo esc_attr($widget_id); ?>">
			<div class="input-group">
				<input id="s" name="s" placeholder="<?php echo esc_attr($settings['placeholder']); ?>" autocomplete="off" value="<?php echo esc_attr($keyword); ?>"/>
				<?php
				    if (function_exists('icl_object_id')) {
				?>
				    <input id="lang" name="lang" type="hidden" value="<?php echo esc_attr(ICL_LANGUAGE_CODE); ?>"/>
				<?php
					}
				?>
				<span class="input-group-button">
					<button aria-label="<?php echo esc_attr($settings['placeholder']); ?>" type="submit">
						<?php 
							if($settings['icon_content_type'] == 'font_icon') {
								\Elementor\Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] ); 
							}
							else if(isset($settings['icon_image']['url'])) {
						?>
							<img src="<?php echo esc_url($settings['icon_image']['url']); ?>" alt="<?php esc_html_e('Search', 'hoteller-elementor' ); ?>"/>
						<?php
							}
						?>
					</button>
				</span>
			</div>
			
			<?php
				if($settings['autocomplete'] == 'yes')
				{
			?>
				<br class="clear"/>
				<div id="autocomplete_<?php echo esc_attr($widget_id); ?>" class="autocomplete" data-mousedown="false"></div>
			<?php
			    }
			?>
		</form>
	</div>
</div>