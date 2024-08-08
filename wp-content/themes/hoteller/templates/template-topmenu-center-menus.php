<?php
//Get page ID
if(is_object($post))
{
    $obj_page = get_page($post->ID);
}
$current_page_id = '';

if(isset($obj_page->ID) && (is_page() OR is_single()))
{
    $current_page_id = $obj_page->ID;
}
elseif(is_home())
{
    $current_page_id = get_option('page_on_front');
}
?>

<div class="header_style_wrapper">
<?php
    //Check if display top bar
    $tg_topbar = get_theme_mod('tg_topbar', false);
    
    if(HOTELLER_THEMEDEMO && isset($_GET['topbar']) && !empty($_GET['topbar']))
	{
	    $tg_topbar = true;
	}
    
    $hoteller_topbar = hoteller_get_topbar();
    hoteller_set_topbar($tg_topbar);
    
    if(!empty($tg_topbar))
    {
?>

<!-- Begin top bar -->
<div class="above_top_bar">
    <div class="page_content_wrapper">
    <div class="top_contact_info">
		<?php
		    if (!function_exists('pll__')) {
		    	$tg_menu_contact_hours = get_theme_mod('tg_menu_contact_hours');
		    }
		    else
		    {
			    $tg_menu_contact_hours = pll__(get_theme_mod('tg_menu_contact_hours'));
		    }
		    
		    if(!empty($tg_menu_contact_hours))
		    {	
		?>
		    <span id="top_contact_hours"><i class="fa fa-clock-o"></i><?php echo esc_html($tg_menu_contact_hours); ?></span>
		<?php
		    }
		?>
		<?php
		    //Display top contact info
		    if (!function_exists('pll__')) {
		    	$tg_menu_contact_number = get_theme_mod('tg_menu_contact_number');
		    }
		    else
		    {
			    $tg_menu_contact_number = pll__(get_theme_mod('tg_menu_contact_number'));
		    }
		    
		    if(!empty($tg_menu_contact_number))
		    {
		?>
		    <span id="top_contact_number"><a href="tel:<?php echo esc_attr($tg_menu_contact_number); ?>"><i class="fa fa-phone"></i><?php echo esc_html($tg_menu_contact_number); ?></a></span>
		<?php
		    }
		?>
    </div>
    
    <?php
		get_template_part("/templates/template-socials");
	?>
    	
    <?php
    	//Display Top Menu
    	if ( has_nav_menu( 'top-menu' ) ) 
		{
		    wp_nav_menu( 
		        	array( 
		        		'menu_id'			=> 'top_menu',
		        		'menu_class'		=> 'top_nav',
		        		'theme_location' 	=> 'top-menu',
		        	) 
		    ); 
		}
    ?>
    <br class="clear"/>
    </div>
</div>
<?php
    }
?>
<!-- End top bar -->

<?php
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
		}
    }
    
   if(!empty($pp_page_bg) && basename($pp_page_bg)=='default.png')
    {
    	$pp_page_bg = '';
    }
	
	//Check if Woocommerce is installed	
	if(class_exists('Woocommerce') && hoteller_is_woocommerce_page())
	{
		$shop_page_id = get_option( 'woocommerce_shop_page_id' );
		$page_menu_transparent = get_post_meta($shop_page_id, 'page_menu_transparent', true);
	}
	
	if(is_search() OR is_404() OR is_archive() OR is_category() OR is_tag())
	{
	    $page_menu_transparent = 0;
	}
	
	$hoteller_homepage_style = hoteller_get_homepage_style();
	if($hoteller_homepage_style == 'fullscreen')
	{
	    $page_menu_transparent = 1;
	}
?>
<div class="top_bar <?php if(!empty($page_menu_transparent)) { ?>hasbg<?php } ?>">
    <div class="standard_wrapper">
    	<!-- Begin logo -->
    	<div id="logo_wrapper">
    	
    	<?php
    	    //get custom logo
    	    $tg_retina_logo = get_theme_mod('tg_retina_logo');

    	    if(!empty($tg_retina_logo))
    	    {	
    	    	//Get image width and height
		    	$image_id = hoteller_get_image_id($tg_retina_logo);
		    	if(!empty($image_id))
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
		    	else
		    	{
			    	$image_width = 0;
			    	$image_height = 0;
		    	}
				
				$logo_img_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
    	?>
    	<div id="logo_normal" class="logo_container">
    		<div class="logo_align">
	    	    <a id="custom_logo" class="logo_wrapper <?php if(!empty($page_menu_transparent)) { ?>hidden<?php } else { ?>default<?php } ?>" href="<?php echo esc_url(home_url('/')); ?>">
	    	    	<?php
						if($image_width > 0 && $image_height > 0)
						{
					?>
					<img src="<?php echo esc_url($tg_retina_logo); ?>" alt="<?php esc_attr(get_bloginfo('name')); ?>" width="<?php echo esc_attr($image_width); ?>" height="<?php echo esc_attr($image_height); ?>"/>
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
    	?>
    	<!-- End logo -->
    	
        <div id="menu_wrapper">
	        <div id="nav_wrapper">
	        	<div class="nav_wrapper_inner">
	        		<div id="menu_border_wrapper">
	        			<?php 	
	        				//Check if has custom menu
	        				if(is_object($post) && $post->post_type == 'page')
	    					{
	    						$page_menu = get_post_meta($current_page_id, 'page_menu', true);
	    					}
	        			
	        				if(empty($page_menu))
	    					{
		    					if(!HOTELLER_THEMEDEMO)
		    					{
		    						if ( has_nav_menu( 'primary-menu' ) ) 
		    						{
		    		    			    wp_nav_menu( 
		    		    			        	array( 
		    		    			        		'menu_id'			=> 'main_menu',
		    		    			        		'menu_class'		=> 'nav',
		    		    			        		'theme_location' 	=> 'primary-menu',
		    		    			        		'walker' => new Hoteller_walker(),
		    		    			        	) 
		    		    			    ); 
		    		    			}
		    		    		}
		    		    		else
		    		    		{
			    		    		if ( has_nav_menu( 'demo-primary-menu' ) ) 
		    						{
		    		    			    wp_nav_menu( 
		    		    			        	array( 
		    		    			        		'menu_id'			=> 'main_menu',
		    		    			        		'menu_class'		=> 'nav',
		    		    			        		'theme_location' 	=> 'demo-primary-menu',
		    		    			        		'walker' => new Hoteller_walker(),
		    		    			        	) 
		    		    			    ); 
		    		    			}
		    		    		}
	    	    			}
	    	    			else
	    				    {
	    				     	if( $page_menu && is_nav_menu( $page_menu ) ) {  
	    						    wp_nav_menu( 
	    						        array(
	    						            'menu' => $page_menu,
	    						            'walker' => new Hoteller_walker(),
	    						            'menu_id'			=> 'main_menu',
	    		    			        	'menu_class'		=> 'nav',
	    						        )
	    						    );
	    						}
	    				    }
	        			?>
	        		</div>
	        		
	        		<!-- Begin right corner buttons -->
			    	<div id="logo_right_button">
					    
						<?php
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
									
								//Get My booking page URL
								$tg_menu_my_booking = get_theme_mod('tg_menu_my_booking');
								if (!function_exists('pll_get_post')) 
								{
									$tg_menu_my_booking_url = get_permalink($tg_menu_my_booking);
								}
								else
								{
									$tg_menu_my_booking_url = get_permalink(pll_get_post($tg_menu_my_booking));
								}
						?>
						<div class="header_client_wrapper">
						    <span class="ti-user"></span>
						    <a href="<?php echo esc_url($user_home_url); ?>">
							    <?php echo esc_html($current_user->display_name); ?></a>
							    
							    (
							    <?php 
									if(!empty($tg_menu_my_booking_url))
									{
								?>
							    <a href="<?php echo esc_url($tg_menu_my_booking_url); ?>" title="<?php esc_html_e('My Booking', 'hoteller' ); ?>"><?php esc_html_e('My Booking', 'hoteller' ); ?></a>&nbsp;|
							    <?php
								    }
								?>
								<a class="client_logout_link" href="<?php echo wp_logout_url( get_permalink() ); ?>" title="<?php esc_html_e('Logout', 'hoteller' ); ?>"><?php esc_html_e('Logout', 'hoteller' ); ?>
						    </a>)
						</div>
						<?php
							}
						?>
						
						<?php
							//Check if display my booking link
							$tg_menu_my_booking = get_theme_mod('tg_menu_my_booking');
							
							if(!empty($tg_menu_my_booking) && is_user_logged_in())
							{
						?>
						<div class="header_client_wrapper">
						    <a href="<?php echo esc_url($tg_menu_my_booking); ?>" title="<?php esc_html_e('My Booking', 'hoteller' ); ?>"><?php esc_html_e('My Booking', 'hoteller' ); ?></a>)
						<?php
							}
						?>
					    
					    <?php
						//Check if display cart icon
					    $tg_menu_show_cart = get_theme_mod('tg_menu_show_cart', true);
					   
						if (class_exists('Woocommerce') && !empty($tg_menu_show_cart)) {
						    //Check if display cart in header
						
						    $woocommerce = hoteller_get_woocommerce();
						    $cart_url = wc_get_cart_url();
						    $cart_count = $woocommerce->cart->cart_contents_count;
						?>
						<div class="header_cart_wrapper">
						    <div class="cart_count"><?php echo esc_html($cart_count); ?></div>
						    <a class="tooltip" href="<?php echo esc_url($cart_url); ?>" title="<?php esc_html_e('View Cart', 'hoteller' ); ?>"><span class="ti-shopping-cart"></span></a>
						</div>
						<?php
						}
						?>
						
						<!-- Begin side menu -->
						<?php
						  	if ( has_nav_menu( 'side-menu' ) ) 
						  	{
						 ?>
					     	<a href="javascript:;" id="mobile_nav_icon"><span class="ti-menu"></span></a>
					     <?php
						  	}
						 ?>
						<!-- End side menu -->
						
			    	</div>
			    	<!-- End right corner buttons -->
	        		
	        		<div id="menu_border_wrapper_right">
	        			<?php 	
	        				if(empty($page_menu))
	    					{
	    						if ( has_nav_menu( 'secondary-menu' ) ) 
	    						{
	    		    			    wp_nav_menu( 
	    		    			        	array( 
	    		    			        		'menu_id'			=> 'main_right_menu',
	    		    			        		'menu_class'		=> 'nav',
	    		    			        		'theme_location' 	=> 'secondary-menu',
	    		    			        		'walker' => new Hoteller_walker(),
	    		    			        	) 
	    		    			    ); 
	    		    			}
	    	    			}
	    	    			else
	    				    {
	    				     	if( $page_menu && is_nav_menu( $page_menu ) ) {  
	    						    wp_nav_menu( 
	    						        array(
	    						            'menu' => $page_menu,
	    						            'walker' => new Hoteller_walker(),
	    						            'menu_id'			=> 'main_right_menu',
	    		    			        	'menu_class'		=> 'nav',
	    						        )
	    						    );
	    						}
	    				    }
	        			?>
	        		</div>
	        	</div>
	        </div>
	        <!-- End main nav -->
        </div>
        
    	</div>
		</div>
    </div>
</div>
