<?php
/**
 * The Header for the template.
 *
 * @package WordPress
 */

if ( ! isset( $content_width ) ) $content_width = 960;

if(session_id() == '') {
	session_start();
}
 
$hoteller_homepage_style = hoteller_get_homepage_style();

$tg_menu_layout = hoteller_menu_layout();
?><!DOCTYPE html>
<html <?php language_attributes(); ?> <?php if(isset($hoteller_homepage_style) && !empty($hoteller_homepage_style)) { echo 'data-style="'.esc_attr($hoteller_homepage_style).'"'; } ?> data-menu="<?php echo esc_attr($tg_menu_layout); ?>">
<head>

<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<?php
	//Fallback compatibility for favicon
	if(!function_exists( 'has_site_icon' ) || ! has_site_icon() ) 
	{
		/**
		*	Get favicon URL
		**/
		$tg_favicon = get_theme_mod('tg_favicon');
		
		if(!empty($tg_favicon))
		{
?>
			<link rel="shortcut icon" href="<?php echo esc_url($tg_favicon); ?>" />
<?php
		}
	}
?> 

<?php
	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
?>
</head>

<body <?php body_class(); ?>>
	<?php
		$post = hoteller_get_wp_post();
		$custom_bg_style = '';
		
		//if password protected
		if(post_password_required())
		{
			$image_thumb = '';				
			if(has_post_thumbnail(get_the_ID(), 'full'))
			{
			    $image_id = get_post_thumbnail_id(get_the_ID());
			    $image_thumb = wp_get_attachment_image_src($image_id, 'full', true);
			}
			
			if(isset($image_thumb[0]) && !empty($image_thumb[0]))
			{
				$custom_bg_style.='background-image:url('.esc_url($image_thumb[0]).');';
			}
		}	
	?>
	<div id="perspective" <?php if(!empty($custom_bg_style)) { ?>style="<?php echo esc_attr($custom_bg_style); ?>"<?php } ?>>
	<?php
		//Check if disable right click
		$tg_enable_right_click = get_theme_mod('tg_enable_right_click', false);
		
		//Check if disable image dragging
		$tg_enable_dragging = get_theme_mod('tg_enable_dragging', false);
		
		//Check if sticky menu
		$tg_fixed_menu = get_theme_mod('tg_fixed_menu', true);
		
		//Check if sticky sidebar
		$tg_sidebar_sticky = get_theme_mod('tg_sidebar_sticky', true);
		
		//Check if display top bar
		$tg_topbar = get_theme_mod('tg_topbar', false);
		if(HOTELLER_THEMEDEMO && isset($_GET['topbar']) && !empty($_GET['topbar']))
		{
			$tg_topbar = true;
		}
		
		//Get lightbox thumbnails alignment
		$tg_lightbox_thumbnails = get_theme_mod('tg_lightbox_thumbnails', 'thumbnail');

		$tg_lightbox_thumbnails_display = true;
		if(empty($tg_lightbox_thumbnails))
		{
			$tg_lightbox_thumbnails_display = false;
		}
		
		$tg_lightbox_timer = get_theme_mod('tg_lightbox_timer', 7);
		$tg_header_content = get_theme_mod('tg_header_content', 'menu');
	?>
	<input type="hidden" id="pp_menu_layout" name="pp_menu_layout" value="<?php echo esc_attr($tg_menu_layout); ?>"/>
	<input type="hidden" id="pp_enable_right_click" name="pp_enable_right_click" value="<?php echo esc_attr($tg_enable_right_click); ?>"/>
	<input type="hidden" id="pp_enable_dragging" name="pp_enable_dragging" value="<?php echo esc_attr($tg_enable_dragging); ?>"/>
	<input type="hidden" id="pp_image_path" name="pp_image_path" value="<?php echo esc_url(get_template_directory_uri()); ?>/images/"/>
	<input type="hidden" id="pp_homepage_url" name="pp_homepage_url" value="<?php echo esc_url(home_url('/')); ?>"/>
	<input type="hidden" id="pp_fixed_menu" name="pp_fixed_menu" value="<?php echo esc_attr($tg_fixed_menu); ?>"/>
	<input type="hidden" id="tg_sidebar_sticky" name="tg_sidebar_sticky" value="<?php echo esc_attr($tg_sidebar_sticky); ?>"/>
	<input type="hidden" id="pp_topbar" name="pp_topbar" value="<?php echo esc_attr($tg_topbar); ?>"/>
	<input type="hidden" id="post_client_column" name="post_client_column" value="4"/>
	<input type="hidden" id="pp_back" name="pp_back" value="<?php esc_html_e('Back', 'hoteller' ); ?>"/>
	<input type="hidden" id="tg_lightbox_thumbnails" name="tg_lightbox_thumbnails" value="<?php echo esc_attr($tg_lightbox_thumbnails); ?>"/>
	<input type="hidden" id="tg_lightbox_thumbnails_display" name="tg_lightbox_thumbnails_display" value="<?php echo esc_attr($tg_lightbox_thumbnails_display); ?>"/>
	<input type="hidden" id="tg_lightbox_timer" name="tg_lightbox_timer" value="<?php echo intval($tg_lightbox_timer*1000); ?>"/>
	<input type="hidden" id="tg_header_content" name="tg_header_content" value="<?php echo esc_attr($tg_header_content); ?>"/>
	
	<?php
		if(class_exists('Woocommerce'))
		{
			$woocommerce = hoteller_get_woocommerce();
			$cart_url = wc_get_cart_url();
	?>
	<input type="hidden" id="tg_cart_url" name="tg_cart_url" value="<?php echo esc_url($cart_url); ?>"/>
	<?php
		}
	?>
	
	<?php
		$tg_live_builder = 0;
		if(isset($_GET['ppb_live']))
		{
			$tg_live_builder = 1;
		}
	?>
	<input type="hidden" id="tg_live_builder" name="tg_live_builder" value="<?php echo esc_attr($tg_live_builder); ?>"/>
	
	<?php
		//Check footer sidebar columns
		$tg_footer_sidebar = get_theme_mod('tg_footer_sidebar', 4);
	?>
	<input type="hidden" id="pp_footer_style" name="pp_footer_style" value="<?php echo esc_attr($tg_footer_sidebar); ?>"/>
	
	<?php
		switch($tg_menu_layout)
		{
			case 'centeralign':
			case 'centeralign2':
			case 'centeralign3':
			case 'hammenuside':
			case 'leftalign':
			case 'leftmenu':
			default:
				get_template_part("/templates/template-sidemenu");
			break;
			
			case 'hammenufull':
				get_template_part("/templates/template-fullmenu");
			break;
		}
	?>

	<!-- Begin template wrapper -->
	<?php
		
		$hoteller_page_menu_transparent = hoteller_get_page_menu_transparent();

		if(isset($post->ID) && (is_page() OR is_single()))
		{
			$current_page_id = $post->ID;
		}
		else
		{
			$current_page_id = '';
		}
		
		//Get Page Menu Transparent Option
		$page_menu_transparent = get_post_meta($current_page_id, 'page_menu_transparent', true);

	    $pp_page_bg = '';
	    //Get page featured image
	    if(has_post_thumbnail($current_page_id, 'full'))
	    {
	        $image_id = get_post_thumbnail_id($current_page_id); 
	        $image_thumb = wp_get_attachment_image_src($image_id, 'full', true);
	        $pp_page_bg = $image_thumb[0];
	        
	        if(is_single() && $post->post_type == 'mphb_room_type')
			{
				$page_menu_transparent = 1;
				$hoteller_page_menu_transparent = 1;
			}
	    }
	    
	   if(!empty($pp_page_bg) && basename($pp_page_bg)=='default.png')
	    {
	    	$pp_page_bg = '';
	    }
		
		//Check if Woocommerce is installed	
		if(class_exists('Woocommerce') && hoteller_is_woocommerce_page())
		{
			$shop_page_id = get_option('woocommerce_shop_page_id');
			$page_menu_transparent = get_post_meta($shop_page_id, 'page_menu_transparent', true);
		}
		
		if(is_search() OR is_404() OR is_archive() OR is_category() OR is_tag())
		{
		    $page_menu_transparent = 0;
		}
	?>
	<div id="wrapper" class="<?php if(!empty($hoteller_page_menu_transparent)) { ?>hasbg<?php } ?> <?php if(!empty($page_menu_transparent)) { ?>transparent<?php } ?>">
	
	<?php
		$tg_header_content = get_theme_mod('tg_header_content', 'menu');
		
		if($tg_header_content == 'content')
		{
			get_template_part("/templates/template-elementor-header");
		}
		else
		{
			//Get current page template
			$tg_current_page_template = basename(get_page_template(),'.php');
		
			if($tg_current_page_template != 'maintenance')
			{
				//Get main menu layout
				$tg_menu_layout = hoteller_menu_layout();
				
				switch($tg_menu_layout)
				{
					case 'centeralign':
					case 'centeralign2':
					case 'hammenuside':
					case 'hammenufull':
					default:
						get_template_part("/templates/template-topmenu");
					break;
					
					case 'leftalign':
						get_template_part("/templates/template-topmenu-left");
					break;
					
					case 'centeralogo':
						get_template_part("/templates/template-topmenu-center-menus");
					break;
				}
			}
		}
	?>