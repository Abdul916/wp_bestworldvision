<div id="menu_address_wrapper">
	<?php
	 	if ( has_nav_menu( 'side-menu' ) ) 
	 	{
	?>
	   <a href="javascript:;" id="mobile_nav_icon"><span class="ti-menu"></span></a>
	<?php
	 	}
	?>
	
	<?php
	    //Display contact address
	    if (!function_exists('pll__')) {
		    $tg_menu_contact_address = get_theme_mod('tg_menu_contact_address');
	    }
	    else
	    {
	    	$tg_menu_contact_address = pll__(get_theme_mod('tg_menu_contact_address'));
	    }
	    
	    if(!empty($tg_menu_contact_address))
	    {
	?>
		<div class="menu_address_content">
			<div class="menu_address_label"><?php esc_html_e('address', 'hoteller' ); ?></div>
		    <div class="menu_address">
			    <?php echo esc_html($tg_menu_contact_address); ?>
		    </div>
		</div>
	<?php
	    }
	?>
	
	<?php
	    //Display contact tel
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
	    <div class="menu_tel_content">
		    <div class="menu_tel_label"><?php esc_html_e('tel', 'hoteller' ); ?></div>
		    <div class="menu_tel_number">
		    	<a class="tooltip" href="tel:<?php echo esc_html($tg_menu_contact_number); ?>" title="<?php esc_html_e('Call Us', 'hoteller' ); ?>"><?php echo esc_html($tg_menu_contact_number); ?></a>
		    </div>
		</div>
	<?php
	    }
	?>
</div>