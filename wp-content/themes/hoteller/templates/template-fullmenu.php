<!-- Begin mobile menu -->
<div class="mobile_menu_wrapper">
	<div class="fullmenu_wrapper">
		<div class="fullmenu_content">
		<a id="close_mobile_menu" href="javascript:;"><span class="ti-close"></span></a>
		<?php
			//get custom logo
		    $tg_retina_logo = get_theme_mod('tg_retina_logo');
	
		    if(!empty($tg_retina_logo))
    	    {	
		    	//Get image width and height
	        	$image_id = hoteller_get_image_id($tg_retina_logo);
	        	
	        	if(!empty($image_id) && is_numeric($image_id))
		    	{
		    		$obj_image = wp_get_attachment_image_src($image_id, 'original');
		    		
		    		$image_width = 0;
			    	$image_height = 0;
			    	
			    	if(isset($obj_image[1]))
			    	{
			    		$image_width = intval($obj_image[1]/2);
			    	}
			    	if(isset($obj_image[2]))
			    	{
			    		$image_height = intval($obj_image[2]/2);
			    	}
		    	}
		    	else if(!is_numeric($image_id))
			    {
				    $image_width = 1;
			    	$image_height = 1;
			    }
		    	else
		    	{
			    	$image_width = 0;
			    	$image_height = 0;
		    	}
		?>
		<div id="logo_normal" class="logo_container">
			<div class="logo_align">
	    	    <a id="custom_logo" class="logo_wrapper <?php if(!empty($page_menu_transparent)) { ?>hidden<?php } else { ?>default<?php } ?>" href="<?php echo esc_url(home_url('/')); ?>">
	    	    	<?php
						if($image_width > 1 && $image_height > 1)
						{
					?>
					<img src="<?php echo esc_url($tg_retina_logo); ?>" alt="<?php esc_attr(get_bloginfo('name')); ?>" width="<?php echo esc_attr($image_width); ?>" height="<?php echo esc_attr($image_height); ?>"/>
					<?php
						}
						else if($image_width == 1 && $image_height == 1)
						{
					?>
	    	    	<img src="<?php echo esc_url($tg_retina_logo); ?>" alt="<?php esc_attr(get_bloginfo('name')); ?>" class="custom_logo_no_info"/>
	    	    	<?php 
		    	    	}
						else
						{
					?>
	    	    	<img src="<?php echo esc_url($tg_retina_logo); ?>" alt="<?php esc_attr(get_bloginfo('name')); ?>" width="118" height ="36"/>
	    	    	<?php 
		    	    	}
		    	    ?>
	    	    </a>
			</div>
		</div>
		<?php
		    }
		?>
	
	    <?php
	    	$hoteller_homepage_style = hoteller_get_homepage_style();
	    
	    	//Get main menu layout
			$tg_menu_layout = hoteller_menu_layout(); 
		
	    	//Get page ID
	    	if(is_object($post))
	    	{
	    	    $page = get_page($post->ID);
	    	}
	    	$current_page_id = '';
	    	
	    	if(isset($page->ID))
	    	{
	    	    $current_page_id = $page->ID;
	    	}
	    	elseif(is_home())
	    	{
	    	    $current_page_id = get_option('page_on_front');
	    	}
	    	
	        //If enable menu transparent
	        $page_menu_transparent = get_post_meta($current_page_id, 'page_menu_transparent', true);
	        
	        $pp_page_bg = '';
		    //Get page featured image
		    if(has_post_thumbnail($current_page_id, 'full'))
		    {
		        $image_id = get_post_thumbnail_id($current_page_id); 
		        $image_thumb = wp_get_attachment_image_src($image_id, 'full', true);
		        $pp_page_bg = $image_thumb[0];
		    }
	
	    	//Check if Woocommerce is installed	
	    	if(class_exists('Woocommerce') && hoteller_is_woocommerce_page())
	    	{
	    	    //Check if woocommerce page
	    		$shop_page_id = get_option( 'woocommerce_shop_page_id' );
	    		$page_menu_transparent = get_post_meta($shop_page_id, 'page_menu_transparent', true);
	    	}
	    	
	    	if($hoteller_homepage_style == 'fullscreen')
	        {
	            $page_menu_transparent = 1;
	        }
	        
	        if(is_search() OR is_404() OR is_front_page() OR is_archive() OR is_category() OR is_tag())
			{
			    $page_menu_transparent = 0;
			}
	    ?>
		
	    <?php 
	    	//Check if has custom menu
	    	if(is_object($post) && $post->post_type == 'page')
	    	{
	    	    $page_menu = get_post_meta($post->ID, 'page_menu', true);
	    	}	
	    	
	    	if ( has_nav_menu( 'side-menu' ) ) 
	    	{
	    	    //Get page nav
	    	    wp_nav_menu( 
	    	        array( 
	    	            'menu_id'			=> 'mobile_main_menu',
	                    'menu_class'		=> 'mobile_main_nav',
	    	            'theme_location' 	=> 'side-menu',
	    	        )
	    	    ); 
	    	}
	    ?>
	    
	    <?php
			//Check if open link in new window
			$tg_footer_social_link = get_theme_mod('tg_footer_social_link', true);
		?>
			<div class="social_wrapper">
			    <ul>
			    	<?php
			    		$pp_facebook_url = get_option('pp_facebook_url');
			    		
			    		if(!empty($pp_facebook_url))
			    		{
			    	?>
			    	<li class="facebook"><a <?php if(!empty($tg_footer_social_link)) { ?>target="_blank"<?php } ?> href="<?php echo esc_url($pp_facebook_url); ?>"><i class="fa fa-facebook-official"></i></a></li>
			    	<?php
			    		}
			    	?>
			    	<?php
			    		$pp_twitter_username = get_option('pp_twitter_username');
			    		
			    		if(!empty($pp_twitter_username))
			    		{
			    	?>
			    	<li class="twitter"><a <?php if(!empty($tg_footer_social_link)) { ?>target="_blank"<?php } ?> href="https://twitter.com/<?php echo esc_attr($pp_twitter_username); ?>"><i class="fa fa-twitter"></i></a></li>
			    	<?php
			    		}
			    	?>
			    	<?php
			    		$pp_flickr_username = get_option('pp_flickr_username');
			    		
			    		if(!empty($pp_flickr_username))
			    		{
			    	?>
			    	<li class="flickr"><a <?php if(!empty($tg_footer_social_link)) { ?>target="_blank"<?php } ?> title="Flickr" href="https://flickr.com/people/<?php echo esc_attr($pp_flickr_username); ?>"><i class="fa fa-flickr"></i></a></li>
			    	<?php
			    		}
			    	?>
			    	<?php
			    		$pp_youtube_username = get_option('pp_youtube_username');
			    		
			    		if(!empty($pp_youtube_username))
			    		{
			    	?>
			    	<li class="youtube"><a <?php if(!empty($tg_footer_social_link)) { ?>target="_blank"<?php } ?> title="Youtube" href="https://youtube.com/channel/<?php echo esc_attr($pp_youtube_username); ?>"><i class="fa fa-youtube"></i></a></li>
			    	<?php
			    		}
			    	?>
			    	<?php
			    		$pp_vimeo_username = get_option('pp_vimeo_username');
			    		
			    		if(!empty($pp_vimeo_username))
			    		{
			    	?>
			    	<li class="vimeo"><a <?php if(!empty($tg_footer_social_link)) { ?>target="_blank"<?php } ?> title="Vimeo" href="https://vimeo.com/<?php echo esc_attr($pp_vimeo_username); ?>"><i class="fa fa-vimeo-square"></i></i></a></li>
			    	<?php
			    		}
			    	?>
			    	<?php
			    		$pp_tumblr_username = get_option('pp_tumblr_username');
			    		
			    		if(!empty($pp_tumblr_username))
			    		{
			    	?>
			    	<li class="tumblr"><a <?php if(!empty($tg_footer_social_link)) { ?>target="_blank"<?php } ?> title="Tumblr" href="https://<?php echo esc_attr($pp_tumblr_username); ?>.tumblr.com"><i class="fa fa-tumblr"></i></a></li>
			    	<?php
			    		}
			    	?>
			    	<?php
			    		$pp_google_url = get_option('pp_google_url');
			    		
			    		if(!empty($pp_google_url))
			    		{
			    	?>
			    	<li class="google"><a <?php if(!empty($tg_footer_social_link)) { ?>target="_blank"<?php } ?> title="Google+" href="<?php echo esc_url($pp_google_url); ?>"><i class="fa fa-google-plus"></i></a></li>
			    	<?php
			    		}
			    	?>
			    	<?php
			    		$pp_dribbble_username = get_option('pp_dribbble_username');
			    		
			    		if(!empty($pp_dribbble_username))
			    		{
			    	?>
			    	<li class="dribbble"><a <?php if(!empty($tg_footer_social_link)) { ?>target="_blank"<?php } ?> title="Dribbble" href="https://dribbble.com/<?php echo esc_attr($pp_dribbble_username); ?>"><i class="fa fa-dribbble"></i></a></li>
			    	<?php
			    		}
			    	?>
			    	<?php
			    		$pp_linkedin_username = get_option('pp_linkedin_username');
			    		
			    		if(!empty($pp_linkedin_username))
			    		{
			    	?>
			    	<li class="linkedin"><a <?php if(!empty($tg_footer_social_link)) { ?>target="_blank"<?php } ?> title="Linkedin" href="<?php echo esc_url($pp_linkedin_username); ?>"><i class="fa fa-linkedin"></i></a></li>
			    	<?php
			    		}
			    	?>
			    	<?php
			            $pp_pinterest_username = get_option('pp_pinterest_username');
			            
			            if(!empty($pp_pinterest_username))
			            {
			        ?>
			        <li class="pinterest"><a <?php if(!empty($tg_footer_social_link)) { ?>target="_blank"<?php } ?> title="Pinterest" href="https://pinterest.com/<?php echo esc_attr($pp_pinterest_username); ?>"><i class="fa fa-pinterest"></i></a></li>
			        <?php
			            }
			        ?>
			        <?php
			        	$pp_instagram_username = get_option('pp_instagram_username');
			        	
			        	if(!empty($pp_instagram_username))
			        	{
			        ?>
			        <li class="instagram"><a <?php if(!empty($tg_footer_social_link)) { ?>target="_blank"<?php } ?> title="Instagram" href="https://instagram.com/<?php echo esc_attr($pp_instagram_username); ?>"><i class="fa fa-instagram"></i></a></li>
			        <?php
			        	}
			        ?>
			        <?php
					    $pp_behance_username = get_option('pp_behance_username');
					    
					    if(!empty($pp_behance_username))
					    {
					?>
					<li class="behance"><a <?php if(!empty($pp_topbar_social_link_blank)) { ?>target="_blank"<?php } ?> title="Behance" href="https://behance.net/<?php echo esc_attr($pp_behance_username); ?>"><i class="fa fa-behance-square"></i></a></li>
					<?php
					    }
					?>
					<?php
			    		$pp_500px_url = get_option('pp_500px_url');
			    		
			    		if(!empty($pp_google_url))
			    		{
			    	?>
			    	<li class="500px"><a <?php if(!empty($tg_footer_social_link)) { ?>target="_blank"<?php } ?> title="500px" href="<?php echo esc_url($pp_500px_url); ?>"><i class="fa fa-500px"></i></a></li>
			    	<?php
			    		}
			    	?>
			    </ul>

			</div>
			
			<?php
			 //Display copyright text
			    if (!function_exists('pll__')) {
		        	$tg_footer_copyright_text = get_theme_mod('tg_footer_copyright_text', '© Copyright');
		        }
		        else
		        {
			        $tg_footer_copyright_text = pll__(get_theme_mod('tg_footer_copyright_text', '© Copyright'));
		        }
		
			    if(!empty($tg_footer_copyright_text))
			    {
			    	echo '<div id="copyright">'.wp_kses_post(wp_specialchars_decode($tg_footer_copyright_text)).'</div><br class="clear"/>';
			    }
			?>
	    </div>
	</div>
</div>
<?php
	$hoteller_page_menu_transparent = hoteller_get_page_menu_transparent();
	hoteller_set_page_menu_transparent($page_menu_transparent);
?>
<!-- End mobile menu -->