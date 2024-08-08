<?php
	//Get all settings
	$settings = $this->get_settings();	
?>

<!-- Begin each booking post -->
<?php
	$booking_id = get_the_id();
	
	$booking_rooms = MPHB()->getReservedRoomRepository()->findAllByBooking($booking_id);
	if(is_array($booking_rooms) && !empty($booking_rooms))
	{
		foreach($booking_rooms as $key => $booking_room)
		{
			$room_id = $booking_rooms[$key]->getRoomId();
			$room_type_id = get_post_meta($room_id, 'mphb_room_type_id', true);
			
			$image_id = get_post_thumbnail_id($room_type_id);
			$image_url = wp_get_attachment_image_src($image_id, 'medium', true);
			$image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
		}
	}
?>
<div class="booking_history_row_wrapper">
	<div class="booking_history_title"><h4><?php esc_html_e('Booking ID', 'hoteller-elementor' ); ?> #<?php echo esc_html($booking_id); ?></h4></div>
	<?php
		if(isset($image_url[0]) && !empty($image_url[0]))
		{
	?>
		<div class="booking_history_img">
			<img src="<?php echo esc_url($image_url[0]); ?>" alt="<?php echo esc_attr($image_alt); ?>"/>
		</div>
	<?php
		}
	?>
	
	<div class="booking_history_content">
		<div class="booking_history_content_row">
			<?php
				$booking_status = get_post_status($booking_id);
			?>
			<div class="booking_history_label">
				<?php esc_html_e('Status', 'hoteller-elementor' ); ?>
			</div>
			<div class="booking_history_attr">
				<div class="booking_history_status <?php echo esc_attr($booking_status); ?>">
				<?php
					switch($booking_status)
					{
						case 'confirmed':
				?>
					<i class="fa fa-check-circle marginright"></i><?php echo ucfirst(esc_html_e('Confirmed', 'hoteller-elementor' )); ?>
				<?php
						break;
						
						case 'pending-user':
						case 'pending-payment':
						case 'pending':
				?>
					<i class="fa fa-info-circle marginright"></i><?php echo ucfirst(esc_html_e('Pending', 'hoteller-elementor' )); ?>
				<?php
						break;
						
						case 'abandoned':
						case 'cancelled':
				?>
					<i class="fa fa-times-circle marginright"></i><?php echo ucfirst(esc_html_e('Cancelled', 'hoteller-elementor' )); ?>
				<?php
						break;
					}
				?>
				</div>
			</div>
		</div>
		
		<div class="booking_history_content_row">
			<?php
				$check_in_date = get_post_meta($booking_id, 'mphb_check_in_date', true);
			?>
			<div class="booking_history_label">
				<?php esc_html_e('Check-in', 'hoteller-elementor' ); ?>
			</div>
			<div class="booking_history_attr">
				<?php echo date_i18n(get_option('date_format'), strtotime($check_in_date)); ?>
			</div>
			
			<?php
				$check_out_date = get_post_meta($booking_id, 'mphb_check_out_date', true);
			?>
			<div class="booking_history_label">
				<?php esc_html_e('Check-out', 'hoteller-elementor' ); ?>
			</div>
			<div class="booking_history_attr">
				<?php echo date_i18n(get_option('date_format'), strtotime($check_out_date)); ?>
			</div>
			
			<?php
				$date1 = new DateTime($check_in_date);
				$date2 = new DateTime($check_out_date);
				$number_of_nights = $date2->diff($date1)->format("%a"); 
			?>
			<div class="booking_history_label"></div>
			<div class="booking_history_attr">
				<?php echo esc_html($number_of_nights); ?>&nbsp;<?php esc_html_e('night(s)', 'hoteller-elementor' ); ?>
			</div>
		</div>
		
		<div class="booking_history_content_row">
			<?php
				$booking_email = get_post_meta($booking_id, 'mphb_email', true);
			?>
			<div class="booking_history_label">
				<?php esc_html_e('Contact details', 'hoteller-elementor' ); ?>
			</div>
			<div class="booking_history_attr">
				<?php echo esc_html($booking_email); ?>
			</div>
			
			<?php
				$booking_phone = get_post_meta($booking_id, 'mphb_phone', true);
			?>
			<div class="booking_history_label"></div>
			<div class="booking_history_attr">
				<?php echo esc_html($booking_phone); ?>
			</div>
		</div>
		
		<div class="booking_history_content_row">
			<?php
				$booking_first_name = get_post_meta($booking_id, 'mphb_first_name', true);
				$booking_last_name = get_post_meta($booking_id, 'mphb_last_name', true);
			?>
			<div class="booking_history_label">
				<?php esc_html_e('Guest name', 'hoteller-elementor' ); ?>
			</div>
			<div class="booking_history_attr">
				<?php echo esc_html($booking_first_name); ?>&nbsp;<?php echo esc_html($booking_last_name); ?>
			</div>
		</div>
		
		<?php
			$booking_price_breakdown = get_post_meta($booking_id, '_mphb_booking_price_breakdown', true);
			$booking_price_breakdown_arr = json_decode($booking_price_breakdown);
		?>
		<div class="booking_history_label">
			<?php esc_html_e('Details', 'hoteller-elementor' ); ?>
		</div>
		<div class="booking_history_attr">
			<?php
				if(isset($booking_price_breakdown_arr->rooms) && is_array($booking_price_breakdown_arr->rooms) && !empty($booking_price_breakdown_arr->rooms))				
				{
					foreach($booking_price_breakdown_arr->rooms as $booking_detail)
					{
?>
					<div class="booking_history_sub_label"><a href="<?php esc_url(get_permalink($booking_detail->room->id)); ?>"><?php echo esc_html($booking_detail->room->type); ?></a></div>
					<div class="booking_history_sub_attr"><?php echo mphb_format_price($booking_detail->discount_total); ?></div>
<?php
					}
				}
			?>
			
			<div class="booking_history_sub_label"><?php esc_html_e('Total', 'hoteller-elementor' ); ?></div>
			<?php
				if(isset($booking_price_breakdown_arr->total))
				{
			?>
			<div class="booking_history_sub_attr"><strong style="font-size:20px;"><?php echo mphb_format_price($booking_price_breakdown_arr->total); ?></strong></div>
			<?php 
				}
			?>
		</div>
	</div>
</div>
<!-- End each booking post -->