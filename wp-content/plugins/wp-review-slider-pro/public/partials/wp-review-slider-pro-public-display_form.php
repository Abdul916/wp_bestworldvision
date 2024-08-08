<?php

/**
 * Provide a public-facing view for the plugin to display a badge
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    WP_Review_Pro
 * @subpackage WP_Review_Pro/public/partials
 */

 	//db function variables
	global $wpdb;
	$table_name = $wpdb->prefix . 'wpfb_forms';
	
 //use the template id to find template in db, echo error if we can't find it or just don't display anything
 	//Get the form--------------------------
	$tid = htmlentities($a['tid']);
	$tid = intval($tid);
	
	$currentform = $wpdb->get_results("SELECT * FROM $table_name WHERE id = ".$tid);


	//check to make sure template found
	if(isset($currentform[0])){
		
		//print_r($currentform[0]);
		
		//=========
		//add styles from template misc here====not using for now, since we can't change anything yet
		//===========
			
			//print out user style added
			//echo "<style>".$currentform[0]->template_css."</style>";
			$templatestylecode = "";
			if($currentform[0]->form_css!=''){
			$templatestylecode = $templatestylecode . "<style>".sanitize_text_field($currentform[0]->form_css)."</style>";
			}
			
			//remove line breaks and tabs
			$templatestylecode = str_replace(array("\n", "\t", "\r"), '', $templatestylecode);
			echo $templatestylecode;
		
		//include the correct form_style__1.php based on the style of the form, currently only 1
		if ( wrsp_fs()->can_use_premium_code() ) {
			if($currentform[0]->style<1){
				$currentform[0]->style = "1";
			}
			include(plugin_dir_path( __FILE__ ) . 'form_style_'.$currentform[0]->style.'.php');
		}

	 
	}

?>

