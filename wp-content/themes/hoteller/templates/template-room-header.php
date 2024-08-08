<?php
/**
*	Get Current page object
**/
$page = get_page($post->ID);

/**
*	Get current page id
**/

if(!isset($current_page_id) && isset($page->ID))
{
    $current_page_id = $page->ID;
}

$hoteller_topbar = hoteller_get_topbar();
$hoteller_screen_class = hoteller_get_screen_class();
$hoteller_page_content_class = hoteller_get_page_content_class();

$pp_page_bg = '';

//Get page featured image
if(has_post_thumbnail($current_page_id, 'original'))
{
    $image_id = get_post_thumbnail_id($current_page_id); 
    $image_thumb = wp_get_attachment_image_src($image_id, 'original', true);
    
    if(isset($image_thumb[0]) && !empty($image_thumb[0]))
    {
    	$pp_page_bg = $image_thumb[0];
    }
    
    //Check if add parallax effect
	$tg_page_header_bg_parallax = get_theme_mod('tg_page_header_bg_parallax');
	if(!empty($tg_page_header_bg_parallax))
	{
		wp_enqueue_script("jarallax", get_template_directory_uri()."/js/jarallax.js", false, HOTELLER_THEMEVERSION, true);
		wp_enqueue_script("jarallax-element", get_template_directory_uri()."/js/jarallax-element.js", false, HOTELLER_THEMEVERSION, true);
		
		$custom_jarallax_script = "
		jQuery(function( $ ) {
			var parallaxSpeed = 0.2;
		    if(jQuery(window).width() > 1200)
		    {
			    parallaxSpeed = 0.5;
		    }
		    
	    	jQuery('.parallax').each(function(){
	    		var parallaxObj = jQuery(this);
	    	
			 	jQuery(this).jarallax({
			 		zIndex          : 0,
			 		speed			: parallaxSpeed,
			 		onCoverImage: function() {
				        parallaxObj.css('z-index', 0);
				    }
			 	});
			 });
		});
		";
		
		wp_add_inline_script('jarallax-element', $custom_jarallax_script);
	}
}
?>

<?php
	//Check Elementor page hide title option
	$elementor_page_settings = get_post_meta($current_page_id, '_elementor_page_settings');
	
	if(!isset($elementor_page_settings[0]['hide_title']) OR !hoteller_is_elementor($current_page_id))
	{
?>
<div id="page_caption" class="<?php if(!empty($pp_page_bg)) { ?>hasbg <?php if(!empty($tg_page_header_bg_parallax)) { ?>parallax<?php } ?> <?php } ?> <?php if(!empty($hoteller_topbar)) { ?>withtopbar<?php } ?> <?php if(!empty($hoteller_screen_class)) { echo esc_attr($hoteller_screen_class); } ?> <?php if(!empty($hoteller_page_content_class)) { echo esc_attr($hoteller_page_content_class); } ?>" <?php if(!empty($pp_page_bg)) { ?>style="background-image:url(<?php echo esc_url($pp_page_bg); ?>);"<?php } ?>>
	<?php 
		if(!empty($pp_page_bg)) 
		{
	?>
		<div id="page_caption_overlay"></div>
	<?php
		}
	?>
	<div class="page_title_wrapper">
		<div class="standard_wrapper">
			<div class="page_title_inner">
				<div class="page_title_content">
					<h1 <?php if(!empty($pp_page_bg) && !empty($hoteller_topbar)) { ?>class ="withtopbar"<?php } ?>><?php the_title(); ?></h1>
					<div class="post_attribute">
						<?php echo esc_html(get_the_excerpt()); ?>
				    </div>
				</div>
			</div>
			
			<?php
				$tg_accommodation_content_nav = get_theme_mod('tg_accommodation_content_nav', 1);
				
				if(!empty($tg_accommodation_content_nav))
				{
			?>
			<ul id="page_title_nav" class="page_title_nav">
				<li><a class="room_detail_link" href="#singleroom_detail"><?php esc_html_e('Detail', 'hoteller' ); ?></a></li>
				<li><a class="room_amenties_link" href="#singleroom_amenities"><?php esc_html_e('Amenities & Services', 'hoteller' ); ?></a></li>
				<li><a class="room_gallery_link" href="#singleroom_gallery"><?php esc_html_e('Gallery', 'hoteller' ); ?></a></li>
			</ul>
			<?php
				}
			?>
		</div>
	</div>
</div>
<?php
	} //End hide page title
?>

<!-- Begin content -->
<div id="page_content_wrapper" class="blog_wrapper <?php if(!empty($pp_page_bg)) { ?>hasbg <?php } ?><?php if(!empty($pp_page_bg) && !empty($hoteller_topbar)) { ?>withtopbar <?php } ?><?php if(!empty($hoteller_page_content_class)) { echo esc_attr($hoteller_page_content_class); } ?>">