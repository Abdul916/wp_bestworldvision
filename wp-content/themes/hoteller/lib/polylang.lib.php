<?php
if ( function_exists( 'pll_register_string' ) ) :
	function hoteller_pll_register_string() {
		pll_register_string( 'tg_menu_contact_address', get_theme_mod( 'tg_menu_contact_address' ), 'hoteller', true );
		pll_register_string( 'tg_menu_contact_hours', get_theme_mod( 'tg_menu_contact_hours' ), 'hoteller', true );
		pll_register_string( 'tg_menu_contact_number', get_theme_mod( 'tg_menu_contact_number' ), 'hoteller', true );
		pll_register_string( 'tg_footer_copyright_text', get_theme_mod( 'tg_footer_copyright_text' ), 'hoteller', true );
		pll_register_string( 'tg_enable_right_click_content_text', get_theme_mod( 'tg_enable_right_click_content_text' ), 'hoteller', true );
    }
    
    add_action( 'after_setup_theme', 'hoteller_pll_register_string' );
endif;
	
?>