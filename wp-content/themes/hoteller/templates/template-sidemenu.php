<!-- Begin mobile menu -->
<a id="close_mobile_menu" href="javascript:;"></a>

<div class="mobile_menu_wrapper">
	<?php
		$tg_sidemenu_close = get_theme_mod('tg_sidemenu_close');
		if(!empty($tg_sidemenu_close))
		{
	?>
		<a id="mobile_menu_close" href="javascript:;" class="button"><span class="ti-close"></span></a>
	<?php
		}
	?>

	<div class="mobile_menu_content">
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
        $page_menu_transparent = 0;
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
        
        if(is_search() OR is_404() OR is_archive() OR is_category() OR is_tag())
		{
		    $page_menu_transparent = 0;
		}
    ?>
	
	<?php
		//If left menu then display logo
		if($tg_menu_layout == 'leftmenu')
	    {
	    	$page_menu_transparent = 0;
    	    
    	    if($hoteller_homepage_style == 'fullscreen')
    	    {
    	        $page_menu_transparent = 1;
    	    }
	?>
	
	<?php
	if(empty($page_menu_transparent))
	{
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
	<div class="logo_container">
		<div class="logo_align">
    	    <a class="logo_wrapper <?php if(!empty($page_menu_transparent)) { ?>hidden<?php } else { ?>default<?php } ?>" href="<?php echo esc_url(home_url('/')); ?>">
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
	<!-- End logo -->
	<?php
	}
	else
	{
        //get custom logo transparent
        $tg_retina_transparent_logo = get_theme_mod('tg_retina_transparent_logo');

        if(!empty($tg_retina_transparent_logo))
        {
        	//Get image width and height
	    	$image_id = hoteller_get_image_id($tg_retina_transparent_logo);
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
    ?>
    <div id="logo_transparent" class="logo_container">
        <div class="logo_align">
	        <a id="custom_logo_transparent" class="logo_wrapper <?php if(empty($page_menu_transparent)) { ?>hidden<?php } else { ?>default<?php } ?>" href="<?php echo esc_url(home_url('/')); ?>">
	        	<?php
	    			if($image_width > 0 && $image_height > 0)
	    			{
	    		?>
	    		<img src="<?php echo esc_url($tg_retina_transparent_logo); ?>" alt="<?php esc_attr(get_bloginfo('name')); ?>" width="<?php echo esc_attr($image_width); ?>" height="<?php echo esc_attr($image_height); ?>"/>
	    		<?php
	    			}
	    			else
	    			{
	    		?>
	        	<img src="<?php echo esc_url($tg_retina_transparent_logo); ?>" alt="<?php esc_attr(get_bloginfo('name')); ?>" width="118" height ="36"/>
	        	<?php 
	    	    	}
	    	    ?>
	        </a>
        </div>
    </div>
    <?php
        }
	}
	?>
	
	<?php
	} //End if left menu
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
	    	if($tg_menu_layout == 'leftmenu')
			{
				//Check if display client icon
				$tg_menu_show_client = get_theme_mod('tg_menu_show_client', true);
				
				//Check if login module is activated
				$zm_ajax_login_register_activated = function_exists('zm_alr_init');
				
				if($zm_ajax_login_register_activated && !empty($tg_menu_show_client) && !is_user_logged_in())
				{
			?>
			<div class="header_client_wrapper">
			    <a class="client_login_link" href="javascript:;" title="<?php esc_html_e('Login', 'hoteller' ); ?>"><span class="ti-lock"></span><?php esc_html_e('Login', 'hoteller' ); ?></a>
			</div>
			<?php
				}
				else if(is_user_logged_in() && !empty($tg_menu_show_client))
				{
					$current_user = wp_get_current_user();
					$user_homepage = get_the_author_meta('user_homepage', $current_user->ID);
					
					if(!empty($user_homepage))
					{
						$user_home_url = get_permalink($user_homepage);
					}
					else
					{
						$user_home_url = home_url();
					}
			?>
			<div class="header_client_wrapper">
			    <span class="ti-user"></span><a href="<?php echo esc_url($user_home_url); ?>"><?php echo esc_html($current_user->display_name); ?></a>(<a class="client_logout_link" href="<?php echo wp_logout_url( get_permalink() ); ?>" title="<?php esc_html_e('Logout', 'hoteller' ); ?>"><?php esc_html_e('Logout', 'hoteller' ); ?></a>)
			</div>
	<?php
				}
			}
	?>
    </div>
</div>
<?php
	$hoteller_page_menu_transparent = hoteller_get_page_menu_transparent();
	hoteller_set_page_menu_transparent($page_menu_transparent);
?>
<!-- End mobile menu -->