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
	if(count($currentform)>0){

		
		//template misc stuff
		$template_misc_array = json_decode($currentform[0]->template_misc, true);

		$reviewsperpage= $currentform[0]->display_num*$currentform[0]->display_num_rows;
		
		require_once("getreviews_class.php");
		$reviewsclass = new GetReviews_Functions();
		$totalreviewsarray = $reviewsclass->wppro_queryreviews($currentform);
		$totalreviews = $totalreviewsarray['reviews'];

		
	//only continue if some reviews found
	$makingslideshow=false;
	if(count($totalreviews)>0){
	$totalreviewsnum = count($totalreviews);

		//if creating a slider than we need to split into chunks for each slider
		$totalreviewschunked = array_chunk($totalreviews, $reviewsperpage);

//add styles from template misc here
		$iswidget = '_widget';
		$gettemplatestylecode = $reviewsclass->wppro_gettemplatestylecode($currentform,$iswidget,$template_misc_array);
		echo $gettemplatestylecode;
		
		
		//add header text if set
		if(isset($template_misc_array['header_text']) && $template_misc_array['header_text']!=''){
			//$arrallowedtags = array('em' => array(), 'i' => array(), 'strong' => array(), 'b' => array());
			$arrallowedtags = array(
				'a' => array(
					'href' => array(),
					'title' => array()
				),
				'b' => array('class' => array(),
					'id' => array()),
				'em' => array('class' => array(),
					'id' => array()),
				'strong' => array('class' => array(),
					'id' => array()),
				'i' => array(
					'class' => array(),
					'id' => array()
					),
				'span' => array(
					'class' => array(),
					'id' => array()
					),
			);
			$tempheadertext= '<div class="wprev_header_txt"><'.wp_kses($template_misc_array['header_text_tag'],$arrallowedtags).'>'.wp_kses($template_misc_array['header_text'],$arrallowedtags).'</'.wp_kses($template_misc_array['header_text_tag'],$arrallowedtags).'></div>';
			$tempheadertext=str_replace("{avgrating}",$totalreviewsarray['totalavg'],$tempheadertext);
			$tempheadertext=str_replace("{totalratings}",$totalreviewsarray['totalcount'],$tempheadertext);
			
			echo $tempheadertext;
		}	

		//add search bar if turned on and not slider
		if(!isset($template_misc_array['header_search'])){
			$template_misc_array['header_search']='';
		}
		if(!isset($template_misc_array['header_sort'])){
			$template_misc_array['header_sort']='';
		}
		//add quick search tags if turned on
		if(!isset($template_misc_array['header_tag'])){
			$template_misc_array['header_tag']='';
		}
		if(!isset($template_misc_array['header_tags'])){
			$template_misc_array['header_tags']='';
		}
		if(!isset($template_misc_array['header_rating'])){
			$template_misc_array['header_rating']='';
			$template_misc_array['header_langcodes']='';
		}
		
		if($currentform[0]->createslider!='yes' && $currentform[0]->load_more=='yes' && ($template_misc_array['header_search']=='yes' || $template_misc_array['header_sort']=='yes' || $template_misc_array['header_tag']=='yes' || $template_misc_array['header_rating']=='yes' || $template_misc_array['header_langcodes']=='yes')){
			
			echo "<div class='wprev_search_sort_bar'>";
			
			if($template_misc_array['header_search']=='yes'){
				echo '<input class="wprev_searchsort wprev_search" id="wprevpro_header_search_input" type="text" name="wprevpro_header_search_input" placeholder="'.$template_misc_array['header_search_place'].'" value="">';
			}
			if($template_misc_array['header_sort']=='yes'){
				echo '<select class="wprev_searchsort wprev_sort" name="wprevpro_header_sort" id="wprevpro_header_sort">
						<option value="" disabled selected>'.$template_misc_array['header_sort_place'].'</option>
						<option value="newest">'.__('Newest', 'wp-review-slider-pro').'</option>
						<option value="oldest">'.__('Oldest', 'wp-review-slider-pro').'</option>
						<option value="highest">'.__('Highest', 'wp-review-slider-pro').'</option>
						<option value="lowest">'.__('Lowest', 'wp-review-slider-pro').'</option>
						<option value="longest">'.__('Longest', 'wp-review-slider-pro').'</option>
						<option value="shortest">'.__('Shortest', 'wp-review-slider-pro').'</option>
						<option value="random">'.__('Random', 'wp-review-slider-pro').'</option>
				</select>';
			}
			if($template_misc_array['header_rating']=='yes'){
				echo '<select class="wprev_searchsort wprev_sort" name="wprevpro_header_rating" id="wprevpro_header_rating">
						<option value="unset" selected>'.esc_html($template_misc_array['header_rating_place']).'</option>
						<option value="1">'.__('1', 'wp-review-slider-pro').'</option>
						<option value="2">'.__('2', 'wp-review-slider-pro').'</option>
						<option value="3">'.__('3', 'wp-review-slider-pro').'</option>
						<option value="4">'.__('4', 'wp-review-slider-pro').'</option>
						<option value="5">'.__('5', 'wp-review-slider-pro').'</option>
				</select>';
			}
			if($template_misc_array['header_langcodes']=='yes'){
				$langcodestring = $template_misc_array['header_langcodes_list'];
				$langcodestring = preg_replace('/\s/', '', $langcodestring);	//remove whitespaces
				$langcodearray = array_filter(explode(",",$langcodestring));
				echo '<select class="wprev_searchsort wprev_sort" name="wprevpro_header_langcodes" id="wprevpro_header_langcodes">
						<option value="unset" selected>'.esc_html($template_misc_array['header_langcodes_place']).'</option>';
				foreach ($langcodearray as $value){ 
					echo '<option value="'.$value.'">'.$value.'</option>';
				} 
				echo '</select>';
			}
			
			$loading_img_url = esc_url( plugins_url( 'imgs/', __FILE__ ) ).'loading_ripple.gif';
			
			if($currentform[0]->createslider!='yes' && ($template_misc_array['header_tag']=='yes' & $template_misc_array['header_tags']!='')){
				//get array of header tags
				$str = esc_html( $template_misc_array['header_tags']);
				$tagarray = explode(",",$str);
				if(is_array($tagarray) && count($tagarray)>0){
					echo '<div class="wprevpro_searchtags_div">';
					$arrlength = count($tagarray);
					for($x = 0; $x < $arrlength; $x++) {
						echo '<span class="wprevpro_stag">'.trim($tagarray[$x]).'</span>';
					}
					echo '<img loading="lazy" src="'.$loading_img_url.'" class="wprppagination_loading_image_tag" style="display:none;">';
					echo '</div>';
				}
				//print_r($tagarray);
			}
			
			
			echo '<img loading="lazy" src="'.$loading_img_url.'" alt="loading image" class="wprppagination_loading_image_search" style="display:none;"></div>';
		}
		
		//jsecho
		//$jstemplatestylecode = '$("head").append("'.$gettemplatestylecode.'");';
		
		//if making slide show then add it here
		if($currentform[0]->createslider == "yes"){
			//make sure we have enough to create a show here
			if($totalreviews>$reviewsperpage){
				$makingslideshow = true;
				$rtltag = '';
				if ( is_rtl() ) {
				$rtltag = 'dir="rtl"';
				}
				echo '<div class="wprevpro wprev-slider-widget" '.$rtltag.' id="wprev-slider-'.$currentform[0]->id.'_widget"><ul>';
			}
		}else {
			echo '<div class="wprevpro wprev-no-slider-widget" id="wprev-slider-'.$currentform[0]->id.'_widget">';
		}
		
		foreach ( $totalreviewschunked as $reviewschunked ){
			//echo "loop1";
			$totalreviewstemp = $reviewschunked;
			
			//need to break $totalreviewstemp up based on how many rows, create an multi array containing them
			if($currentform[0]->display_num_rows>1 && count($totalreviewstemp)>$currentform[0]->display_num){
				//count of reviews total is greater than display per row then we need to break in to multiple rows
				for ($row = 0; $row < $currentform[0]->display_num_rows; $row++) {
					$n=1;
					foreach ( $totalreviewstemp as $tempreview ){
						//echo "<br>".$tempreview->reviewer_name;
						//echo $n."-".$row."-".$currentform[0]->display_num;
						if($n>($row*$currentform[0]->display_num) && $n<=(($row+1)*$currentform[0]->display_num)){
							$rowarray[$row][$n]=$tempreview;
						}
						$n++;
					}
				}
			} else {
				//everything on one row so just put in multi array
				$rowarray[0]=$totalreviewstemp;
			}
			

			//if making slide show
			if($makingslideshow){
					echo '<li>';
			}
		 
				//include the correct tid here
				if($currentform[0]->style=="1" || $currentform[0]->style=="2" || $currentform[0]->style=="3" || $currentform[0]->style=="4" || $currentform[0]->style=="5" || $currentform[0]->style=="6" || $currentform[0]->style=="7" || $currentform[0]->style=="8" || $currentform[0]->style=="9" || $currentform[0]->style=="10" || $currentform[0]->style=="11" || $currentform[0]->style=="12"){
					$iswidget=true;
					
				$ajaxsliload = false;
				$templatenum = intval($currentform[0]->style);
				
					if($currentform[0]->style=='1'){
						include(plugin_dir_path( __FILE__ ) . 'template_style_'.$currentform[0]->style.'.php');
					} else {
						if ( wrsp_fs()->can_use_premium_code() ) {
							include(plugin_dir_path( __FILE__ ) . 'template_style_'.$currentform[0]->style.'.php');
						}
					}
	
				}
			
			//if making slide show then end loop here
			if($makingslideshow){
					echo '</li>';
			}
		
		}	//end loop chunks
		
		//---add load more button if turned on
		//$jslastslide ='';
		//$getloadmorebtnhtml = $reviewsclass->wppro_getloadmorebtnhtml($currentform,$iswidget,$makingslideshow);
		//$jslastslide = $getloadmorebtnhtml['jslastslide'];
		//echo $getloadmorebtnhtml['echothis'];
		
		//---add load more button if turned on
		$jslastslide ='';
		if($currentform[0]->load_more=="yes"){
			//if sort is random then we need to add the ids to button so we can test and do a NOT IN call 
			$notinstring='';
			//if($currentform[0]->display_order=="random"){
				$alreadygrabbedreviews=Array();
				for ($x = 0; $x < count($totalreviews); $x++) {
					if(isset($totalreviews[$x]->id)){
						$alreadygrabbedreviews[] = $totalreviews[$x]->id;
					}
				}
				$notinstring= implode(",",$alreadygrabbedreviews);
			//}
			//$getloadmorebtnhtml = $reviewsclass->wppro_getloadmorebtnhtml($currentform,$iswidget,$makingslideshow,$notinstring);
			$cpostid = get_the_ID();
			$getloadmorebtnhtml = $reviewsclass->wppro_getloadmorebtnhtml($currentform,$iswidget,$makingslideshow,$notinstring,'','',$cpostid,$totalreviewsarray['totalcount']);
			
			echo $getloadmorebtnhtml['echothis'];
			
			if($totalreviewsarray['totalcount']>$reviewsperpage){
				$jslastslide = $getloadmorebtnhtml['jslastslide'];
				
			}
		}
		
		//for forcing same height, on both slider and nonslider
			$forceheight ='';
			if($currentform[0]->review_same_height!=""){
				if($currentform[0]->review_same_height=='yes' || $currentform[0]->review_same_height=='cur' || $currentform[0]->review_same_height=='yea'){
					$forceheight='var maxheights = $("#wprev-slider-'.$currentform[0]->id.'_widget").find(".w3_wprs-col").find("p").parent().map(function (){return $(this).outerHeight();}).get();var maxHeightofslide = Math.max.apply(null, maxheights);$("#wprev-slider-'.$currentform[0]->id.'_widget").find(".w3_wprs-col").find("p").parent().css( "min-height", maxHeightofslide );';
				}
			}
			
		//if making slide show then end it
		$avatarimgexists ='';
		if($makingslideshow){
			//grab db values
			if($currentform[0]->sliderautoplay!="" && $currentform[0]->sliderautoplay=='yes'){
				$autoplay = 'true';
			} else {
				$autoplay = 'false';
			}
			if($currentform[0]->sliderdirection=='vertical' || $currentform[0]->sliderdirection=='horizontal' || $currentform[0]->sliderdirection=='fade'){
				$animation = $currentform[0]->sliderdirection;
			} else {
				$animation = 'horizontal';
			}
			if($currentform[0]->sliderarrows=='yes'){
				$arrows = 'true';
			} else {
				$arrows = 'false';
			}
			if($currentform[0]->sliderdots!="" && $currentform[0]->sliderdots=='no'){
				$slidedots = '$("#wprev-slider-'.$currentform[0]->id.'_widget").siblings(".wprs_unslider-nav").hide();';
			} else {
				$slidedots = '$("#wprev-slider-'.$currentform[0]->id.'_widget").siblings(".wprs_unslider-nav").show();';
			}
			if($currentform[0]->sliderdelay!="" && intval($currentform[0]->sliderdelay)>0){
				$delay = intval($currentform[0]->sliderdelay)*1000;
			} else {
				$delay = "3000";
			}
			if($currentform[0]->sliderspeed!="" && intval($currentform[0]->sliderspeed)>0){
				$sliderspeed = intval($currentform[0]->sliderspeed);
			} else {
				$sliderspeed = "750";
			}
			$fixheight = '';
			if($currentform[0]->sliderheight=='no'){
				$animateHeight = 'false';
				//add fix for fade transition
				if($animation=='fade'){
					$fixheight='var heights = $("#wprev-slider-'.$currentform[0]->id.'_widget").find( "li" ).map(function ()
							{return $(this).height();}).get(),maxHeight = Math.max.apply(null, heights);
							$("#wprev-slider-'.$currentform[0]->id.'_widget").height(maxHeight);';
				}
			} else {
				$animateHeight = 'true';
			}
			//javascript that checks if avatar images exists, hides if not found
			$avatarimgexists = "$('.wprevpro_avatarimg').each(function() {var tempsrc = $(this).attr('src');var newimage = new Image();newimage.src = tempsrc;if(newimage.width==0){jQuery(this).remove();}});";
			
			$swipe='';
			/*
			if($animation=='horizontal'){
				if ( is_rtl() ) {
				$swipe="$('#wprev-slider-".$currentform[0]->id."_widget').on('swipeleft', function(e) {slider.data('wprs_unslider').prev();}).on('swiperight', function(e) {slider.data('wprs_unslider').next();});";
				} else {
				$swipe="$('#wprev-slider-".$currentform[0]->id."_widget').on('swipeleft', function(e) {slider.data('wprs_unslider').next();}).on('swiperight', function(e) {slider.data('wprs_unslider').prev();});";
				}
			}
			*/
		
				echo '</ul></div>';
				echo "<script type='text/javascript'>
						function wprs_defer_widget(methodwidget) {
							document.getElementById('wprev-slider-".$currentform[0]->id."_widget').style.display='none';
							if (window.jQuery) {
								if(jQuery.fn.wprs_unslider){
									methodwidget();
								} else {
									setTimeout(function() { wprs_defer_widget(methodwidget) }, 500);
									console.log('waiting to load rev_slider js...');
								}
							} else {
								setTimeout(function() { wprs_defer_widget(methodwidget) }, 100);
								console.log('waiting to load jquery...');
							}
						}
						wprs_defer_widget(function () {
							jQuery(document).ready(function($) {
								document.getElementById('wprev-slider-".$currentform[0]->id."_widget').style.display='block';
								var slider = $('#wprev-slider-".$currentform[0]->id."_widget').wprs_unslider(
									{
									autoplay:".$autoplay.",
									delay: '".$delay."',
									animation: '".$animation."',
									speed: ".$sliderspeed.",
									arrows: ".$arrows.",
									animateHeight: ".$animateHeight.",
									activeClass: 'wprs_unslider-active',
									infinite: false,
									}
								);
								slider.on('wprs_unslider.change', function(event, index, slide) {
									$('#wprev-slider-".$currentform[0]->id."_widget').find('.wprs_rd_less:visible').trigger('click');
								})
								$('#wprev-slider-".$currentform[0]->id."_widget').siblings('.wprs_unslider-nav').attr( 'id','wprs_widget_nav_".$currentform[0]->id."');
								$('#wprev-slider-".$currentform[0]->id."_widget').siblings('.wprs_unslider-arrow').addClass('wprs_widget_nav_arrow_".$currentform[0]->id."');
								".$slidedots."
								slider.on('mouseover', function() {slider.data('wprs_unslider').stop();}).on('mouseout', function() {slider.data('wprs_unslider').start();});
								".$forceheight."".$fixheight."".$swipe."".$jslastslide."".$avatarimgexists."});
						});
						</script>";
		} else {
			echo '</div>';
		}
		
		
		
		//google snippet html
		$tempsnippethtml = $reviewsclass->wppro_getgooglesnippet($currentform,$totalreviewsarray['totalcount'],$totalreviewsarray['totalavg'],$totalreviews);
		echo $tempsnippethtml;


	 
	}
}
?>

