<?php
	//Get all settings
	$settings = $this->get_settings();	
	$shortcode = '';
?>
<div class="availability_calendar_wrapper">
	<?php 
		$shortcode.= '[mphb_availability_calendar'; 
		
		if(isset($settings['accommodation']) && !empty($settings['accommodation'])) {
			$shortcode.= ' id="'.$settings['accommodation'].'"'; 
		}
		
		$monthstoshow_string = '';
		if(isset($settings['accommodation_monthstoshow_rows']['size']) && !empty($settings['accommodation_monthstoshow_rows']['size']) && isset($settings['accommodation_monthstoshow_columns']['size']) && !empty($settings['accommodation_monthstoshow_columns']['size'])) {
			$monthstoshow_string.= $settings['accommodation_monthstoshow_rows']['size'].','.$settings['accommodation_monthstoshow_columns']['size']; 
			
			$shortcode.= ' monthstoshow="'.$monthstoshow_string.'"'; 
		}
		
		if(isset($settings['show_price']) && $settings['show_price']=='yes') {
			$shortcode.= ' display_price="true"'; 
		}
		
		$shortcode.= ' display_currency="true"'; 
		$shortcode.= ']';
		
		echo do_shortcode($shortcode);
	?>
</div>