<?php

/**
 * Provide a public-facing view for the plugin
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
	$table_name = $wpdb->prefix . 'wpfb_post_templates';
	
 //use the template id to find template in db, echo error if we can't find it or just don't display anything
 	//Get the form--------------------------
	$tid = htmlentities($a['tid']);
	$tid = intval($tid);
	$currentform = $wpdb->get_results("SELECT * FROM $table_name WHERE id = ".$tid);
	
	$totalreviewsnum ='';
	$reviewratingsarray = Array();
	$reviewratingsarrayavg ='';
	
	//check to make sure template found
	if(isset($currentform[0])){
		
		//get all the reviews based on template filters
		$shortcodepageid ='';
		if(isset($a['pageid'])){
			$shortcodepageid = $a['pageid'];
		}
		$shortcodelang='';
		if(isset($a['langcode'])){
			$shortcodelang = $a['langcode'];
		}
		$shortcodetag='';
		if(isset($a['tag'])){
			$shortcodetag = $a['tag'];
		}
		require_once("getreviews_class.php");
		$reviewsclass = new GetReviews_Functions();
		$totaltoget = 1;	//1 actually returns 2 here.
		
		$oldstartoffset = $startoffset;
		$newstartoffset = $startoffset + 1;
		
		$totalreviewsarray = $reviewsclass->wppro_queryreviews($currentform,$oldstartoffset,$totaltoget,$notinstring='',$shortcodepageid,$shortcodelang,'','','','','',$shortcodetag);
		$totalreviews = $totalreviewsarray['reviews'];
		//$totalreviewsarray['totalcount']
		//$totalreviewsarray['totalavg']
	
		$reviewsperpage= $currentform[0]->display_num*$currentform[0]->display_num_rows;
		

	
	$makingslideshow=false;
	$ismakingslideshow = "no";
	if(count($totalreviews)>0){
		
		$iswidget=false;
		//template misc stuff
		$template_misc_array = json_decode($currentform[0]->template_misc, true);
		$gettemplatestylecode = $reviewsclass->wppro_gettemplatestylecode($currentform,$iswidget,$template_misc_array);
		echo $gettemplatestylecode;

		//get total reviews and reviews per a row
		$totalreviewsnum = count($totalreviews);
		$totalreviewschunked = array_chunk($totalreviews, $totalreviewsnum);


		//echo $reviewsperpage;
		//echo "<br>";
		//echo $currentform[0]->createslider;
		//echo "<br>";
		//print_r($totalreviewschunked);
		
		//we drop one since 2 reviews are returned
		unset($totalreviewschunked[0][1]);
		
		//$floatid,$whattofloatid,$whattofloattype
		
		echo '<div class="wprevpro_outerrevdivpop" data-formid="'.$floatid.'" data-wtfloatid="'.$whattofloatid.'" data-startoffset="'.$newstartoffset.'"  id="wprev-pop-'.$floatid.'">';
		
		echo '<div class="wprevpro wprev-no-slider " id="wprev-slider-'.$currentform[0]->id.'">';
		$ajaxsliload = false;
		$currentform[0]->createslider = "no";	//force to no since inside a pop.
		$currentform[0]->display_num = 1;	//force to showing one on a row.
		//loop through each chunk
		foreach ( $totalreviewschunked as $reviewschunked ){
			$totalreviewstemp = $reviewschunked;
			$rowarray[0]=$totalreviewstemp;
			include(plugin_dir_path( __FILE__ ) . 'template_style_'.$currentform[0]->style.'.php');
		}
		
		echo '</div>';
		
		echo '</div>';
	}
	 
}
?>

