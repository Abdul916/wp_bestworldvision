<?php
/**
 * The main template file for display single post page.
 *
 * @package WordPress
*/

get_header(); 

$hoteller_topbar = hoteller_get_topbar();

/**
*	Get current page id
**/

$current_page_id = $post->ID;

//Include custom header feature
get_template_part("/templates/template-room-header");
?>
    
    <div class="inner">

    	<!-- Begin main content -->
    	<div class="inner_wrapper">

    		<div id="singleroom_detail" class="sidebar_content full_width blog_f">
					
<?php
if (have_posts()) : while (have_posts()) : the_post();
?>
						
<!-- Begin each blog post -->
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="post_wrapper">

		<div class="singleroom_two_third themeborder">
		    <?php echo do_shortcode(wpautop($post->post_content)); ?>
		</div>
		
		<div class="singleroom_one_third themeborder">
			<div class="singleroom_price_wrapper">
				<div class="singleroom_price_label"><?php esc_html_e('From', 'hoteller' ); ?></div>
				<?php
					//Get custom pricing
					$custom_pricing = get_post_meta($post->ID, 'custom_pricing', true);	
				?>
				<div class="singleroom_price_amount">
					<?php 
						if(empty($custom_pricing))
						{
							mphb_tmpl_the_room_type_default_price($post->ID); 
						}
						else
						{
					?>
						<span class="mphb-price"><?php echo esc_html($custom_pricing); ?></span>
					<?php		
						}
					?>
				</div>
				<?php
					//Check if has custom booking URL
					$custom_booking_url = get_post_meta($post->ID, 'custom_booking_url', true);
					
					if(empty($custom_booking_url))
					{
						//Check if booking is disabled
						$mphb_booking_disabled = get_option('mphb_booking_disabled');
						
						if(empty($mphb_booking_disabled))
						{
				?>
				<a id="singleroom_book" data-formid="<?php echo esc_attr($post->ID); ?>" href="javascript:;" class="singleroom_book button"><?php esc_html_e('Book Now', 'hoteller' ); ?></a>
				<div id="singleroom_book_form<?php echo esc_attr($post->ID); ?>" class="singleroom_book_form"><?php echo do_shortcode('[mphb_availability id="'.$post->ID.'"]'); ?></div>
				<?php
						}
					}
					else
					{
				?>
				<a href="<?php echo esc_url($custom_booking_url); ?>" class="singleroom_book button" target="_blank"><?php esc_html_e('Book Now', 'hoteller' ); ?></a>
				<?php
					}
				?>
			</div>
			<br class="clear"/>
			<div class="singleroom_attributes_wrapper">
				<?php
					$bed_type = mphb_tmpl_get_room_type_bed_type();
					if(!empty($bed_type))
					{
				?>
				<div class="singleroom_attribute">
					<div class="singleroom_bed_icon room_icon"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/images/icon_bed.png" alt="<?php esc_html_e('Bed Type', 'hoteller' ); ?>"/></div>
					<div class="singleroom_bed room_attribute"><?php echo esc_html($bed_type); ?></div>
				</div>
				<?php
					}
				?>
				
				<?php
					$adults = mphb_tmpl_get_room_type_adults_capacity();
					$children = mphb_tmpl_get_room_type_children_capacity();
					if(!empty($adults) OR !empty($children))
					{
				?>
				<div class="singleroom_attribute">
					<div class="singleroom_adults_children_icon room_icon"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/images/icon_people.png" alt="<?php esc_html_e('Adults & Children', 'hoteller' ); ?>"/></div>
					<div class="singleroom_adults_children room_attribute">
						<?php
							//Check if displays adult & children or using guests
							$mphb_guest_management = get_option('mphb_guest_management');
							$adult_string = esc_html__('Adults', 'hoteller' );
							if($mphb_guest_management == 'disable-children')
							{
								$adult_string = esc_html__('Guests', 'hoteller' );
							}
							
							if(!empty($adults))
							{
						?>
							<?php echo esc_html($adults); ?>&nbsp;<?php echo $adult_string; ?>&nbsp;
						<?php
							}
						?>
						<?php
							if(!empty($children))
							{
						?>
							<?php echo esc_html($children); ?>&nbsp;<?php esc_html_e('Children', 'hoteller' ); ?>&nbsp;
						<?php
							}
						?>
					</div>
				</div>
				<?php
					}
				?>
				
				<?php
					$size = mphb_tmpl_get_room_type_size();
					
					if(!empty($size))
					{
				?>
				<div class="singleroom_attribute">
					<div class="singleroom_size_icon room_icon"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/images/icon_size.png" alt="<?php esc_html_e('Size', 'hoteller' ); ?>"/></div>
					<div class="singleroom_size room_attribute"><?php echo esc_html($size); ?></div>
				</div>
				<?php
					}
				?>
				
				<?php
					$view = mphb_tmpl_get_room_type_view();
					if(!empty($view))
					{
				?>
				<div class="singleroom_attribute">
					<div class="singleroom_view_icon room_icon"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/images/icon_view.png" alt="<?php esc_html_e('View', 'hoteller' ); ?>"/></div>
					<div class="singleroom_view room_attribute"><?php echo esc_html($view); ?></div>
				</div>
				<?php
					}
				?>
				
				<?php
					global $mphbAttributes;

					if(!empty($mphbAttributes) && is_array($mphbAttributes))
					{
						foreach ($mphbAttributes as $attribute_slug => $attribute) {
							$attribute_id = get_page_by_path($attribute_slug, OBJECT, 'mphb_room_attribute');
							$image_id = get_post_thumbnail_id($attribute_id); 
							$image_thumb = wp_get_attachment_image_src($image_id, 'original', true);
							
							$room_terms = wp_get_post_terms( $current_page_id, $attribute['taxonomyName'] );
				?>
				<div id="singleroom_attribute_<?php echo esc_attr($attribute_id->ID); ?>" class="singleroom_attribute singleroom_custom_attributes">
					<div class="singleroom_view_icon room_icon"><img src="<?php echo esc_url($image_thumb[0]); ?>" alt="<?php echo esc_attr(get_the_title($attribute_id)); ?>"/></div>
					<div class="singleroom_view room_attribute">
						<?php 
							if(is_array($room_terms) && !empty($room_terms))
							{
								foreach($room_terms as $room_terms)
								{
									echo $room_terms->name.'&nbsp;';
								}
							}
						?>
					</div>
				</div>
				<?php
						} 
					}
				?>
				
				<div class="sidebar">
					<div class="content">
						<?php 
			 			if (is_active_sidebar('single-room-sidebar')) { ?>
			    	    	<ul class="sidebar_widget">
			    	    	<?php dynamic_sidebar('single-room-sidebar'); ?>
			    	    	</ul>
			    	    <?php } ?>
					</div>
				</div>
			</div>
		</div>
	    
	</div>

</div>
<!-- End each blog post -->

<?php endwhile; endif; ?>
						
    	</div>
    
    </div>
    <!-- End main content -->
</div>

<br class="clear"/>
</div>
<?php

$tg_accommodation_amenities = get_theme_mod('tg_accommodation_amenities', 1);
				
if(!empty($tg_accommodation_amenities))
{
	$amenities_arr = wp_get_object_terms($post->ID, 'mphb_room_type_facility');
	$services_id_arr = get_post_meta($post->ID, 'mphb_services', true);
	
	if(!empty($amenities_arr) OR !empty($services_id_arr))
	{
?>
<div id="singleroom_amenities" class="singleroom_amenities">
	<div class="page_content_wrapper">
	<?php
		//Display Amenties
		
		if(!empty($amenities_arr) && is_array($amenities_arr))
		{
	?>
	<div class="singleroom_amenities_wrapper">
		<div class="singleroom_amenities_content">
			<div class="singleroom_amenities_label">
				<img src="<?php echo esc_url(get_template_directory_uri()); ?>/images/icon_amenities.png" alt="<?php esc_html_e('Amenities', 'hoteller' ); ?>"/><?php esc_html_e('Amenities', 'hoteller' ); ?>
			</div>
			<div class="singleroom_amenities_list_wrapper">
				<ul class="singleroom_amenities_list">
				<?php
					foreach($amenities_arr as $amenty)
					{
				?>
					<li><?php echo esc_html($amenty->name); ?></li>
				<?php
					}
				?>
				</ul>
			</div>
		</div>
		<br class="clear"/>
	</div>
	<?php
		}
	?>
	
	<?php
		//Display Services
	
		if(!empty($services_id_arr) && is_array($services_id_arr))
		{
			//Get all available services
			$services = MPHB()->getServiceRepository()->findAll();
	?>
	<div class="singleroom_amenities_wrapper">
		<div class="singleroom_amenities_content">
			<div class="singleroom_amenities_label">
				<img src="<?php echo esc_url(get_template_directory_uri()); ?>/images/icon_services.png" alt="<?php esc_html_e('Services', 'hoteller' ); ?>"/><?php esc_html_e('Services', 'hoteller' ); ?>
			</div>
			<div class="singleroom_amenities_list_wrapper">
				<ul class="singleroom_amenities_list">
				<?php
					foreach($services as $service)
					{
						if(in_array($service->getOriginalId(), $services_id_arr))
						{
				?>
					<li>
						<?php echo esc_html($service->getTitle()); ?>&nbsp;(<?php echo wp_kses_post($service->getPriceWithConditions( true )); ?>
					)</li>
				<?php
						}
					}
				?>
				</ul>
			</div>
		</div>
		<br class="clear"/>
	</div>
	<?php
		}
	?>
	</div>
</div>
<?php
	}
}
?>

<?php
$tg_accommodation_gallery = get_theme_mod('tg_accommodation_gallery', 1);
				
if(!empty($tg_accommodation_gallery))
{
	//Display Gallery
	$gallery_image_id_arr = get_post_meta($post->ID, 'mphb_gallery', true);
	$gallery_image_id_arr = explode(',', $gallery_image_id_arr);
	
	if(!empty($gallery_image_id_arr) && is_array($gallery_image_id_arr))
	{
		wp_enqueue_style('flickity', esc_url(get_template_directory_uri()).'/css/flickity.css', false, false, 'all' );
		wp_enqueue_script("flickity", esc_url(get_template_directory_uri())."/js/flickity.pkgd.js", false, HOTELLER_THEMEVERSION, true);
		wp_enqueue_script("custom-singleroom", esc_url(get_template_directory_uri())."/js/core/custom_singleroom.js", false, HOTELLER_THEMEVERSION, true);
?>
<br class="clear"/>
<div id="singleroom_gallery" class="tg_horizontal_gallery_wrapper">
<?php
		foreach ( $gallery_image_id_arr as $image_id ) 
		{	
			$image_url = wp_get_attachment_image_src($image_id, 'original', true);
			$image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
?>
		<div class="tg_horizontal_gallery_cell" style="margin-right:10px;">
			<img class="tg_horizontal_gallery_cell_img" data-flickity-lazyload="<?php echo esc_url($image_url[0]); ?>" alt="<?php echo esc_attr($image_alt); ?>" style="height:600px;" />
		</div>
<?php
		}
?>
</div>
<?php
	}
}
?>

<?php
	//Get other rooms
	get_template_part("/templates/template-room-related");
?>

<?php
if (comments_open($post->ID) OR hoteller_post_has('pings', $post->ID)) 
{
?>
<div class="page_content_wrapper">
	<div class="fullwidth_comment_wrapper sidebar">
		<?php comments_template( '', true ); ?>
	</div>
</div>
<?php
}
?>

<?php get_footer(); ?>