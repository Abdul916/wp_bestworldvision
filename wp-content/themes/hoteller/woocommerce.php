<?php
/**
 * The main template file for display page.
 *
 * @package WordPress
*/

/**
*	Get current page id
**/
$current_page_id = get_option( 'woocommerce_shop_page_id' );

get_header();

//Get Shop Sidebar
$page_sidebar = '';

//Get Shop Sidebar Display Settting
$tg_shop_layout = get_theme_mod('tg_shop_layout', 'fullwidth');

if(HOTELLER_THEMEDEMO && isset($_GET['sidebar']))
{
	$tg_shop_layout = 'sidebar';
}

if($tg_shop_layout == 'sidebar')
{
	$page_sidebar = 'Shop Sidebar';
}

//Check if woocommerce page
$shop_page_id = get_option( 'woocommerce_shop_page_id' );

//Get Page Menu Transparent Option
$page_menu_transparent = get_post_meta($shop_page_id, 'page_menu_transparent', true);

$page_show_title = get_post_meta($shop_page_id, 'page_show_title', true);

//If single product page then hide page header
if(is_product())
{
	$page_show_title = 1;
}

if(empty($page_show_title))
{
	$query_term = get_query_var('term');
	
	if(is_archive() && !empty($query_term))
	{
		$ob_term = get_term_by('slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
	    $page_taxonomy = get_taxonomy($ob_term->taxonomy);
	    $page_title = $ob_term->name;
	}
	else
	{
		$page_title = get_the_title($shop_page_id);
	}
	
	//Get current page tagline
	$page_tagline = get_post_meta($current_page_id, 'page_tagline', true);

	$pp_page_bg = '';
	//Get page featured image
	if(has_post_thumbnail($current_page_id, 'full'))
    {
        $image_id = get_post_thumbnail_id($current_page_id); 
        $image_thumb = wp_get_attachment_image_src($image_id, 'full', true);
        
        if(isset($image_thumb[0]) && !empty($image_thumb[0]))
        {
        	$pp_page_bg = $image_thumb[0];
        }
    }
    
    //Check if add parallax effect
	$tg_page_header_bg_parallax = get_theme_mod('tg_page_header_bg_parallax');
	
	$hoteller_topbar = hoteller_get_topbar();
	$page_header_type = '';
	
	//Get header featured content
	$page_header_type = get_post_meta(get_the_ID(), 'page_header_type', true);
	
	$video_url = '';
				
	if($page_header_type == 'Youtube Video' OR $page_header_type == 'Vimeo Video')
	{
		//Add jarallax video script
		wp_enqueue_script("jarallax-video", get_template_directory_uri()."/js/jarallax-video.js", false, HOTELLER_THEMEVERSION, true);
		
		if($page_header_type == 'Youtube Video')
		{
			$page_header_youtube = get_post_meta(get_the_ID(), 'page_header_youtube', true);
			$video_url = 'https://www.youtube.com/watch?v='.$page_header_youtube;
		}
		else
		{
			$page_header_vimeo = get_post_meta(get_the_ID(), 'page_header_vimeo', true);
			$video_url = 'https://vimeo.com/'.$page_header_vimeo;
		}
	}
?>
<div id="page_caption" class="<?php if(!empty($pp_page_bg)) { ?>hasbg <?php if(!empty($tg_page_header_bg_parallax)) { ?>parallax<?php } ?> <?php } ?> <?php if(!empty($hoteller_topbar)) { ?>withtopbar<?php } ?> <?php if(!empty($hoteller_screen_class)) { echo esc_attr($hoteller_screen_class); } ?> <?php if(!empty($hoteller_page_content_class)) { echo esc_attr($hoteller_page_content_class); } ?>" <?php if(!empty($pp_page_bg)) { ?>style="background-image:url(<?php echo esc_url($pp_page_bg); ?>);"<?php } ?> <?php if($page_header_type == 'Youtube Video' OR $page_header_type == 'Vimeo Video') { ?>data-jarallax-video="<?php echo esc_url($video_url); ?>"<?php } ?>>
	
	<?php 
		if(!empty($pp_page_bg) OR $page_header_type == 'Youtube Video' OR $page_header_type == 'Vimeo Video') 
		{
	?>
		<div id="page_caption_overlay"></div>
	<?php
		}
	?>

	<?php
		if(empty($page_show_title))
		{
	?>
	<div class="page_title_wrapper">
		<div class="standard_wrapper">
			<div class="page_title_inner">
				<div class="page_title_content">
					<h1 <?php if(!empty($pp_page_bg) && !empty($hoteller_topbar)) { ?>class ="withtopbar"<?php } ?>><?php echo esc_html($page_title); ?></h1>
					<?php
				    	if(!empty($page_tagline))
				    	{
				    ?>
				    	<div class="page_tagline">
				    		<?php echo nl2br($page_tagline); ?>
				    	</div>
				    <?php
				    	}
				    ?>
				</div>
			</div>
		</div>
	</div>
	<?php
		}
	?>
</div>
<?php
	}
?>

<!-- Begin content -->
<div id="page_content_wrapper" <?php if(!empty($pp_page_bg)) { ?>class="hasbg"<?php } ?>>
    <div class="inner ">
    	<!-- Begin main content -->
    	<div class="inner_wrapper">
    		<div class="sidebar_content <?php if(empty($page_sidebar)) { ?>full_width<?php } else { ?>left_sidebar<?php } ?>">
				
				<?php woocommerce_content();  ?>
				
    		</div>
    		<?php if(!empty($page_sidebar)) { ?>
    		<div class="sidebar_wrapper left_sidebar">
	            <div class="sidebar">
	            
	            	<div class="content">
	            
	            		<?php 
						$page_sidebar = sanitize_title($page_sidebar);
						
						if (is_active_sidebar($page_sidebar)) { ?>
		    	    		<ul class="sidebar_widget">
		    	    		<?php dynamic_sidebar($page_sidebar); ?>
		    	    		</ul>
		    	    	<?php } ?>
	            	
	            	</div>
	        
	            </div>
            <br class="clear"/>
        
            <div class="sidebar_bottom"></div>
			</div>
    		<?php } ?>
    	</div>
    	<!-- End main content -->
    </div>
</div>
<!-- End content -->
<br class="clear"/><br/>
<?php get_footer(); ?>