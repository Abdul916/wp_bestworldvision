<?php
	$widget_id = $this->get_id();
	
	//Get all settings
	$settings = $this->get_settings();
?>
<div class="tg_navigation_wrapper menu_<?php echo esc_attr($settings['nav_menu_hover_style']); ?>"><?php 	
//Check if has custom menu
if(isset($post) && is_object($post) && $post->post_type == 'page')
{
	$page_menu = get_post_meta($current_page_id, 'page_menu', true);
}

if(isset($settings['nav_menu']) && !empty($settings['nav_menu']))
{
   $page_menu = $settings['nav_menu'];
}

$wp_rand = wp_rand(1, 40);

if(empty($page_menu))
{
 	if ( has_nav_menu( 'primary-menu' ) ) 
 	{
 	    wp_nav_menu( 
 	        	array( 
 	        		'menu_id'			=> 'nav_menu'.$wp_rand,
 	        		'menu_class'		=> 'nav',
 	        		'theme_location' 	=> 'primary-menu',
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
 	            'menu_id'			=> 'nav_menu'.$wp_rand,
 	        	'menu_class'		=> 'nav',
 	        )
 	    );
 	}
}
?></div>