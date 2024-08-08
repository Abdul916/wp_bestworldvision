<?php
//Get page ID
if(is_object($post))
{
    $obj_page = get_page($post->ID);
}
$current_page_id = '';

if(isset($obj_page->ID))
{
    $current_page_id = $obj_page->ID;
}
elseif(is_home())
{
    $current_page_id = get_option('page_on_front');
}
?>

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