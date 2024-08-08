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
	//$currentform = $wpdb->get_results("SELECT * FROM $table_name WHERE id = ".$tid);
	
	$currentform = $wpdb->get_results($wpdb->prepare(
		"SELECT * FROM $table_name WHERE id =  %s",
		$tid
	));
	
	$totalreviewsnum ='';
	$reviewratingsarray = Array();
	$reviewratingsarrayavg ='';
	
	//check to make sure template found
	if(isset($currentform[0])){
		$formid = intval($currentform[0]->id);
		
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
		$sc_strhasone='';
		if(isset($a['strhasone'])){
			$sc_strhasone = $a['strhasone'];
		}
		$sc_strhasall='';
		if(isset($a['strhasall'])){
			$sc_strhasall = $a['strhasall'];
		}
		$sc_strnot='';
		if(isset($a['strnot'])){
			$sc_strnot = $a['strnot'];
		}
		
		require_once("getreviews_class.php");
		$reviewsclass = new GetReviews_Functions();
		$totalreviewsarray = $reviewsclass->wppro_queryreviews($currentform,$startoffset=0,$totaltoget=0,$notinstring='',$shortcodepageid,$shortcodelang,'','','','','',$shortcodetag,$sc_strhasone,$sc_strhasall,$sc_strnot);
		$totalreviews = $totalreviewsarray['reviews'];
		//$totalreviewsarray['totalcount']
		//$totalreviewsarray['totalavg']

		
		//print_r($totalreviewsarray);
	
		$reviewsperpage= $currentform[0]->display_num*$currentform[0]->display_num_rows;
		
		//template misc stuff
		$template_misc_array = json_decode($currentform[0]->template_misc, true);
			
			
	//only continue if some reviews found
	$makingslideshow=false;
	$ismakingslideshow = "no";
	if(count($totalreviews)>0){

	//------------add style
		$iswidget = '';
		$gettemplatestylecode = $reviewsclass->wppro_gettemplatestylecode($currentform,$iswidget,$template_misc_array);
		echo "<div>".$gettemplatestylecode."</div>";
		//---------end add style-----------------------
		
		$loading_img_url = esc_url( plugins_url( 'imgs/', __FILE__ ) ).'loading_ripple.gif';
		
		//add banner
		if(!isset($template_misc_array['header_banner'])){
			$template_misc_array['header_banner']='';
		}
		
		if($template_misc_array['header_banner']=='txt' || $template_misc_array['header_banner']=='b1'){
			//need to get totals and averages from table.
			$pagetypedetailsarray = Array();
			$temptemplant = "template_".$formid;
			$table_name_temp = $wpdb->prefix . 'wpfb_total_averages';
			$templatepagevals = $wpdb->get_row("SELECT * FROM $table_name_temp WHERE `btp_id` = '".$temptemplant."' ",ARRAY_A );
			
			if(isset($templatepagevals['pagetypedetails']) && $templatepagevals['pagetypedetails']!=''){
				$pagetypedetailsarray = json_decode($templatepagevals['pagetypedetails'],true);
			}
			//print_r($templatepagevals);
			//print_r($pagetypedetailsarray);
		}
			
			
		//add header text if set
		if(isset($template_misc_array['header_text']) && $template_misc_array['header_text']!='' && $template_misc_array['header_banner']!='no' && $template_misc_array['header_banner']!='b1'){
			
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
			$tempheadertext= '<div id="wprev_header_txt_id_'.$formid.'" class="wprev_header_txt"><'.wp_kses($template_misc_array['header_text_tag'],$arrallowedtags).'>'.wp_kses($template_misc_array['header_text'],$arrallowedtags).'</'.wp_kses($template_misc_array['header_text_tag'],$arrallowedtags).'></div>';
			
			//grab values from badge if set and badge exists.
			$badgeavg='';
			$badgetotal='';
			if(isset($template_misc_array['header_filter_opt']) && $template_misc_array['header_filter_opt']>0){
				/*
				$badgeid = intval($template_misc_array['header_filter_opt']);
				//try to find values
				require_once('badge_class.php');	
				$badgetools = new badgetools($badgeid);
				$totalavgarray = $badgetools->gettotalsaverages();
				$badgetotal = $totalavgarray['finaltotal'];
				$badgeavg = $totalavgarray['finalavg'];
				*/
				$badgeavg = $templatepagevals['avg'];
				$badgetotal = $templatepagevals['total'];
				$badgeavg = number_format((float)$badgeavg, 1, '.', '');
				
			}
			if($badgeavg!='' && $badgetotal!=''){
				$tempheadertext=str_replace("{avgrating}",$badgeavg,$tempheadertext);
				$tempheadertext=str_replace("{totalratings}",$badgetotal,$tempheadertext);
			} else {
				$tempheadertext=str_replace("{avgrating}",$totalreviewsarray['totalavg'],$tempheadertext);
				$tempheadertext=str_replace("{totalratings}",$totalreviewsarray['totalcount'],$tempheadertext);
			}
			
			//===================================
			//getting rid of badge totals. Replacing with this source totals and averages.
			//===========================
			
			echo $tempheadertext;
		}
		
		//banner style 1, will possibly use this for other banners styles and just change html.
		if($template_misc_array['header_banner']=='b1'){

			$tempavg = $templatepagevals['avg_indb']; //default to use review list values
			$temptotal = $templatepagevals['total_indb'];

			if($template_misc_array['bn_filter_opt']=='source'){
				//try to use source var
				$tempavg = $templatepagevals['avg'];
				$temptotal = $templatepagevals['total'];
			}
			$tempavg = number_format((float)$tempavg, 1, '.', '');
			
			//create upper html.
			if(count($pagetypedetailsarray)>0){
				$upperhtml = '';
				foreach($pagetypedetailsarray as $page => $value) {
					$addicon = true;
					$lowertype = strtolower($page);
					$fileext = "png";
					//check for svg. 
					$svgarray = unserialize(WPREV_SVG_ARRAY);
					if (in_array($page, $svgarray)) {
						//found svg.
						$fileext = "svg";
					}
					
					$temptypeavg = $value['avg_indb'];
					$temptotalind = $value['total_indb'];
					if($template_misc_array['bn_filter_opt']=='source'){
						$temptypeavg = $value['avg'];
						$temptotalind = $value['total'];
					}
					if($temptypeavg<1){
						//in case we have zero from source site
						$temptypeavg = $value['avg_indb'];
					}
					$temptypeavg = number_format((float)$temptypeavg, 1, '.', '');
					
					//check for changes in submitted text and if we should display it.
					$subtext = $page;
					if($page=="Submitted"){
							$subtext = sanitize_text_field($template_misc_array['bnshowsubtext']);
							if($template_misc_array['bnshowsub']!='yes') {
								$addicon = false;
							}
					}
					if($page=="Manual"){
							$subtext = sanitize_text_field($template_misc_array['bnshowmantext']);
							if($template_misc_array['bnshowman']!='yes') {
								$addicon = false;
							}
					}
					
					if($addicon){
					$upperhtml = $upperhtml . '<span class="wprev_banner_top_source" data-stype="'.$lowertype.'"><img src="'.WPREV_PLUGIN_URL. '/public/partials/imgs/'.$lowertype.'_small_icon.'.$fileext.'?time=7" alt="'.$lowertype.' logo" class="wppro_banner_icon">'.$subtext.' '.$temptypeavg.'</span>';
					}
					
				};
			}

			
			//starhtml setup-------------
			$starhtml ='';
			$roundtohalf ='';
			if($tempavg>0){
			$roundtohalf = round($tempavg * 2) / 2;
			}
			for ($x = 1; $x <= $roundtohalf; $x++) {
				$starhtml = $starhtml.'<span class="svgicons svg-wprsp-star-full"></span>';
			}
			if($roundtohalf==1.5||$roundtohalf==2.5||$roundtohalf==3.5||$roundtohalf==4.5){
				//add another half
				//$starhtml = $starhtml.'<span class="wprsp-star-half"></span>';
				$starhtml = $starhtml.'<span class="svgicons svg-wprsp-star-half"></span>';
				$x++;
			}
			//if x is less than 5 need another star or half
			$starleft = 5 - $x;
			for ($x = 0; $x <= $starleft; $x++) {
				//$starhtml = $starhtml.'<span class="wprsp-star-empty"></span>';
				$starhtml = $starhtml.'<span class="svgicons svg-wprsp-star-empty"></span>';
			}
			
			//review us btn setup.----------
			if(!isset($template_misc_array['revus_btnaction'])){
				$template_misc_array['revus_btnaction'] = '';
			}
			if(!isset($template_misc_array['revus_btnlink'])){
				$template_misc_array['revus_btnlink'] = '';
			}
			
			
			$revus_txtval = 'Review Us';
			$revusbtnhtml = "";
			$revuslinkstart = "";
			$revuslinkend = "";
			$revuslinkhref = "";
			if(isset($template_misc_array['bnrevusbtn']) && $template_misc_array['bnrevusbtn']=='yes'){
				
				if(isset($template_misc_array['revus_txtval']) && $template_misc_array['revus_txtval']!=''){
					$revus_txtval =sanitize_text_field($template_misc_array['revus_txtval']);
				}
				$revusbtnhtml ='<div class="wprevpro_bnrevus_btn">'.$revus_txtval.'</div>';
				

				if($template_misc_array['revus_btnaction']=="link"){
					$revuslinkstart = '<a href="'.sanitize_text_field($template_misc_array['revus_btnlink']).'" target="_blank">';
					$revuslinkend = "</a>";
					$revusbtnhtml = $revuslinkstart.$revusbtnhtml.$revuslinkend;
					
				} else if($template_misc_array['revus_btnaction']=="ddlinks"){
					//multiple links, we need to build hidden drop down list that we show when clicked or pushed
					$revusbtnhtml = '<div class="wprevpro_bnrevus_btn wprevdropbtn">'.$revus_txtval.'</div>
					<div class="wprevdropdown-content">';
					if(!isset($template_misc_array['revus_btnln'])){$template_misc_array['revus_btnln']=Array();}
					if(!isset($template_misc_array['revus_btnlu'])){$template_misc_array['revus_btnlu']=Array();}
					for ($x = 1; $x <= 6; $x++) {
						if(isset($template_misc_array['revus_btnlu'][$x]) && isset($template_misc_array['revus_btnln'][$x])){
							if($template_misc_array['revus_btnlu'][$x]!='' && $template_misc_array['revus_btnln'][$x]!=''){
						$revusbtnhtml = $revusbtnhtml. '<a target="_blank" href="'.sanitize_text_field($template_misc_array['revus_btnlu'][$x]).'">'.sanitize_text_field($template_misc_array['revus_btnln'][$x]).'</a>';
							}
						}
					}
					$revusbtnhtml = $revusbtnhtml. '</div>';
				} else if($template_misc_array['revus_btnaction']=="form"){
					//need to add form to page and pop-up when pushing the button.
					//$template_misc_array['revus_puform'] is form id.
					$revusbtnhtml ='<div class="wprevpro_bnrevus_btn bnrevuspopform" data-formid="'.$template_misc_array['revus_puform'].'">'.$revus_txtval.'</div>';
					
					//get form array from db
					$reviewformid = intval($template_misc_array['revus_puform']);
	
					$formhtml=$this->getformhtml($reviewformid);
					echo $formhtml;
					
				}
			}
			
			$tempheadertext='<div id="wprev_banner_id_'.$formid.'" data-tid="'.$formid.'" class="wprev_banner_outer wprevb1"><div class="wprev_banner_top"><span class="wprev_banner_top_source cursel">'.__('All Reviews', 'wp-review-slider-pro').' '.$tempavg.'</span>'.$upperhtml.'</div><div class="wprev_banner_bottom"><div class="wprev_banner_bottom_t">'.__('Overall Rating', 'wp-review-slider-pro').'</div><div class="wprev_banner_bottom_b"><span class="wprev_avgrevs">'.$tempavg.'</span> <span class="starloc1 wprevpro_star_imgs wprevpro_star_imgsloc1">'.$starhtml.'</span> <span class="wprev_totrevs">'.$temptotal.' '.__('reviews', 'wp-review-slider-pro').'</span></div><div class="wprevpro_bnrevus_div">'.$revusbtnhtml.'</div></div></div>';
			
			
			echo $tempheadertext;
		}
		
		//add search bar if turned on
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
		if(!isset($template_misc_array['header_rtypes'])){
			$template_misc_array['header_rtypes']='';
		}
		if(!isset($template_misc_array['header_source'])){
			$template_misc_array['header_source']='';
		}
		
		//need to track if we are forcing load more so we can hide button later if needed.
		$forceloadmore = false;

		//if any header search is turned on or banner then we need to force on load more
		if($template_misc_array['header_search']=='yes' || $template_misc_array['header_sort']=='yes' || $template_misc_array['header_tag']=='yes' || $template_misc_array['header_rating']=='yes' || $template_misc_array['header_langcodes']=='yes' || $template_misc_array['header_rtypes']=='yes' || $template_misc_array['header_source']=='yes' || $template_misc_array['header_banner']=='b1'){
			if(!isset($currentform[0]->load_more) || $currentform[0]->load_more!="yes"){
				$currentform[0]->load_more='yes';
				$forceloadmore = true;
			}

		}
		if( $currentform[0]->load_more=='yes' && ($template_misc_array['header_search']=='yes' || $template_misc_array['header_sort']=='yes' || $template_misc_array['header_tag']=='yes' || $template_misc_array['header_rating']=='yes' || $template_misc_array['header_langcodes']=='yes' || $template_misc_array['header_source']=='yes' || $template_misc_array['header_rtypes']=='yes')){
			
			echo "<div id='wprev_search_sort_bar_id_".$formid."' data-tid='".$formid."' class='wprev_search_sort_bar'>";
			
			if($template_misc_array['header_search']=='yes'){
				echo '<input class="wprev_searchsort wprev_search" id="wprevpro_header_search_input" type="text" name="wprevpro_header_search_input" placeholder="'.esc_html($template_misc_array['header_search_place']).'" value="">';
			}
			if($template_misc_array['header_sort']=='yes'){
				echo '<select class="wprev_searchsort wprev_sort" name="wprevpro_header_sort" id="wprevpro_header_sort">
						<option value="" disabled selected>'.esc_html($template_misc_array['header_sort_place']).'</option>
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
			if($template_misc_array['header_source']=='yes'){
				//get list of source pages and ids
				//pull distinct page names and page ids from reviews table
				$reviews_table_name = $wpdb->prefix . 'wpfb_reviews';
				//$tempquery = 	"SELECT DISTINCT pageid,pagename,type,from_url FROM ".$reviews_table_name." WHERE pageid IS NOT NULL";
				$tempquery = "select * from ".$reviews_table_name." group by pageid";
				$fbpagesrows = $wpdb->get_results($tempquery);

				echo '<select class="wprev_searchsort wprev_sort" name="wprevpro_header_source" id="wprevpro_header_source">
						<option value="unset" selected>'.esc_html($template_misc_array['header_source_place']).'</option>';
					foreach ( $fbpagesrows as $fbpage ){
						if($fbpage->pageid!=""){
							echo '<option value="'.$fbpage->pageid.'">'.$fbpage->pagename.'</option>';
						}
					}
				echo '</select>';
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
			//add loading image if needed
			if($template_misc_array['header_sort']=='yes' || $template_misc_array['header_search']=='yes' || $template_misc_array['header_rating']=='yes' || $template_misc_array['header_langcodes']=='yes'){
				echo '<img loading="lazy" src="'.$loading_img_url.'" alt="loading image" class="wprppagination_loading_image_search" style="display:none;">';
			}
			
			if($template_misc_array['header_tag']=='yes' & $template_misc_array['header_tags']!=''){
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
			
			//adding header rtypes buttons if set.
			if(isset($template_misc_array['header_rtypes']) && $template_misc_array['header_rtypes']=='yes'){
				echo '<div class="wprevpro_rtypes_div">';
				//find types listed in this template. coming from get_reviewsclass
				foreach($totalreviewsarray['reviewtypesarray'] as $temptype){
					if($temptype!='' && $temptype!='Manual'){
						
						$img = WPREV_PLUGIN_URL."/public/partials/imgs/".strtolower($temptype)."_small_icon.png";
						$imgdir = WPREV_PLUGIN_DIR."public/partials/imgs/".strtolower($temptype)."_small_icon.png";
						//echo $img;
						if(file_exists($imgdir)){
							echo '<span class="wprevpro_stype_btn"><img class="wprevrtypebtn" src="'.$img.'" alt="'.$temptype.' filter" height="32">'.esc_html($temptype).'</span>';
						} else {
							echo '<span class="wprevpro_stype_btn">'.esc_html($temptype).'</span>';
						}
					}
				}
				// create html for each type.
				echo '<img loading="lazy" src="'.$loading_img_url.'" class="wprppagination_rtypes_loading_img" style="display:none;">';
				echo '</div>';
			}
			
			echo '</div>';
			
		}


		//find out if making same height and addclass so we can ues in js
		if($currentform[0]->review_same_height=='yes' || $currentform[0]->review_same_height=='cur' || $currentform[0]->review_same_height=='yea'){
			$notsameheight="";
		} else {
			//only using for non-slider
			$notsameheight="revnotsameheight";
		}

		//get total reviews and reviews per a row
		$totalreviewsnum = count($totalreviews);
		
		//check if total number of reviews is less than or equal to reviewsonslide and masonry turned on then do not create slider
		if(	$currentform[0]->display_masonry=="yes" && $totalreviewsnum<=$reviewsperpage && $currentform[0]->createslider != "no"){
			$currentform[0]->createslider ="no";
		}
		
		//need to pass this to javascript file
		$revsameheight = 'no';
		if($currentform[0]->review_same_height!=""){
			if($currentform[0]->review_same_height=='yes' || $currentform[0]->review_same_height=='cur' || $currentform[0]->review_same_height=='yea'){
				$revsameheight = 'yes';
			}
		}
		
		//if making slide show then add it here
		if($currentform[0]->createslider == "yes"){
			//make sure we have enough to create a show here
			if($totalreviewsnum>$reviewsperpage){
				$makingslideshow = true;
				$ismakingslideshow = "yes";
				$rtltag = '';
				if ( is_rtl() ) {
					$rtltag = 'dir="rtl"';
				}
				$mobileoneperslide = "";
				$animateHeightslider = '';
				if(isset($currentform[0]->slidermobileview) && $currentform[0]->slidermobileview == "one"){
					$mobileoneperslide = 'data-onemobil="yes"';
				}
				if($currentform[0]->sliderheight!="" && $currentform[0]->sliderheight=='yes'){
					$animateHeightslider = 'animateheight';
				}
				
				//attributes passing to js.----------
				if($currentform[0]->sliderautoplay!="" && $currentform[0]->sliderautoplay=='yes'){
					$autoplay = 'true';
					$autoplayjson ='"autoplay": true';
				} else {
					$autoplay = 'false';
					$autoplayjson ='"autoplay": false';
				}
				if($currentform[0]->sliderspeed!="" && intval($currentform[0]->sliderspeed)>0){
					$sliderspeed = intval($currentform[0]->sliderspeed);
					$sliderspeedjson =',"sliderspeed": '.$sliderspeed;
				} else {
					$sliderspeed = "750";
					$sliderspeedjson =',"sliderspeed": '.$sliderspeed;
				}
				
				if($currentform[0]->sliderdelay!="" && intval($currentform[0]->sliderdelay)>0){
					$delay = intval($currentform[0]->sliderdelay)*1000 + $sliderspeed;;
					$delayjson =',"delay": '.$delay;
				} else {
					$delay = 3000 + $sliderspeed;
					$delayjson =',"delay": '.$delay;
				}
				if($currentform[0]->sliderdirection=='vertical' || $currentform[0]->sliderdirection=='horizontal' || $currentform[0]->sliderdirection=='fade'){
					$animation = $currentform[0]->sliderdirection;
					$animationjson =',"animation": "'.$animation.'"';
				} else {
					$animation = 'horizontal';
					$animationjson =',"animation": "'.$animation.'"';
				}

				if($currentform[0]->sliderarrows!="" && $currentform[0]->sliderarrows=='no'){
					$arrows = 'false';
					$arrowsjson =',"arrows": '.$arrows;
				} else {
					$arrows = 'true';
					$arrowsjson =',"arrows": '.$arrows;
				}
				$forceheight ='';
				$forceheightjson =',"forceheight": "no"';
				if($currentform[0]->review_same_height!=""){
					if($currentform[0]->review_same_height=='yes' || $currentform[0]->review_same_height=='cur' || $currentform[0]->review_same_height=='yea'){
						$forceheightjson =',"forceheight": "yes"';
					}
				}
				if($currentform[0]->sliderheight!="" && $currentform[0]->sliderheight=='yes' && $forceheight==''){
					$animateHeight = 'true';
					$animateHeightjson =',"animateHeight": '.$animateHeight;
				} else {
					$animateHeight = 'false';
					$animateHeightjson =',"animateHeight": '.$animateHeight;
				}
				//for making the arrows not move
				$sliderarrowheightjson =',"sliderarrowheight": "no"';
				if(!isset($inslideout)){
					$inslideout='';
				}
				if($arrows == 'true' && $inslideout!="yes"){
					$sliderarrowheightjson =',"sliderarrowheight": "yes"';
				}
				//for adding load more js if needed.
				$loadmorerslijson = ',"loadmorersli": "no"';
				if(isset($currentform[0]->load_more) && $currentform[0]->load_more=="yes"){
					$loadmorerslijson = ',"loadmorersli": "yes"';
				}
				$totalreviewsarrayjson = ',"totalreviewsarray": 0';
				if($totalreviewsarray['totalcount']>0){
				$totalreviewsarrayjson = ',"totalreviewsarray": '.$totalreviewsarray['totalcount'];
				}
				$reviewsperpagejson = ',"reviewsperpage": '.$reviewsperpage;
				
				$checkfloatdelayjson = ',"checkfloatdelay": 0';
				if(isset($insidefloat) && $animatedelay>0){
					//go back to the first slide .5 seconds before
					$slideanimatedelay = $animatedelay*1000-50;
					$checkfloatdelayjson = ',"checkfloatdelay": '.$slideanimatedelay;
				}
				$thissliderid = ',"sliderid":'.$currentform[0]->id;
				$iswidgetjson = ',"iswidget":false';
				
				echo '<div style="display:none;" class="wprevpro wprev-slider '.$notsameheight.' '.$animateHeightslider.'" '.$mobileoneperslide.' '.$rtltag.' id="wprev-slider-'.$currentform[0]->id.'" data-revsameheight="'.$revsameheight.'" data-slideprops=\'{'.$autoplayjson.''.$delayjson.''.$animationjson.''.$sliderspeedjson .''.$arrowsjson.''.$animateHeightjson.''.$forceheightjson.''.$sliderarrowheightjson.''.$loadmorerslijson.''.$totalreviewsarrayjson.''.$reviewsperpagejson.''.$checkfloatdelayjson.''.$thissliderid.''.$iswidgetjson.'}\'>';
				
			} else {
				echo '<div class="wprevpro wprev-no-slider '.$notsameheight.'" id="wprev-slider-'.$currentform[0]->id.'">';
			}
		} else if($currentform[0]->createslider == "sli"){
			echo '<div class="wprevpro wprev-slick-slider '.$notsameheight.'" id="wprev-slider-'.$currentform[0]->id.'">';
		} else {
			//not making slideshow
			echo '<div class="wprevpro wprev-no-slider '.$notsameheight.'" id="wprev-slider-'.$currentform[0]->id.'">';

		}		
		

		if($currentform[0]->createslider == "yes" && $makingslideshow){
					echo '<ul>';
			$totalreviewschunked = array_chunk($totalreviews, $reviewsperpage);
		} else if($currentform[0]->createslider == "sli"){
			$totalreviewschunked = array_chunk($totalreviews, $reviewsperpage);
		} else {
			$totalreviewschunked = array_chunk($totalreviews, $totalreviewsnum);
		}
		
		//================================
		//if making slick slider------------
		//==================================
		$slickrtl = '';
		$slickrtlhtml ='';
		$slickavatarnav = false;
		if($currentform[0]->createslider == "sli"){
				
			//avatar navigation
			$dataavatartemplate = 'data-avatartemplate="0"';
			$sli_asnavfor = '';
			if(isset($template_misc_array['sli_avatarnav']) && $template_misc_array['sli_avatarnav']=='yes'){
				$slickavatarnav = true;
				//force slidestoscroll to 1.
				$sli_slidestoscroll =',"slidesToScroll": 1';
				//change template setting to avatar only.
				$dataavatartemplate = 'data-avatartemplate="'.esc_attr($currentform[0]->style).'"';
				//change the $totalreviewschunked to force one slide at a time and then will use previous value to add another slider with just faces
				$totalreviewschunkedoriginal = $totalreviewschunked;
				$reviewsperpagetemp = 1;
				$totalreviewschunked = array_chunk($totalreviews, $reviewsperpagetemp);
				$org_display_num_rows = intval($currentform[0]->display_num_rows);
				$org_display_num = intval($currentform[0]->display_num);
				$currentform[0]->display_num_rows = 1;
				$currentform[0]->display_num = 1;
				$currentform[0]->sliderarrows = 'no';
				$currentform[0]->sliderdots='no';

				$sli_asnavfor =', "asNavFor": "#wprevgoslickidnav_'.intval($currentform[0]->id).'"';
			}
			
			
			if ( is_rtl() ) {
					$slickrtl = ', "rtl":true';
					$slickrtlhtml = 'dir="rtl"';
				}
			$sli_rows = ', "rows":'.intval($currentform[0]->display_num_rows).'';		//used to create a grid mode with slidesPerRow
			$slidesetup ='"slidesToShow": '.intval($currentform[0]->display_num);
			$sli_slidestoscroll =',"slidesToScroll": '.intval($currentform[0]->display_num);
			//fix if only a few reviews returned.
			if($totalreviewsnum < $currentform[0]->display_num){
				$slidesetup ='"slidesToShow": '.intval($totalreviewsnum);
				$sli_slidestoscroll =',"slidesToScroll": '.intval($totalreviewsnum);
				$sli_rows = ', "rows":1';
			}
			$sli_autoplay = '';
			$sli_autoplaySpeed = '';
			$sli_autoplaySpeedval = 3000;
			
			$sli_infinite =',"infinite": false';
			$sli_Speedval = 750;
			$sli_Speed = ',"speed": '.$sli_Speedval.'';
			$sli_dots = ',"dots": true';
			$sli_arrows = ',"arrows": true';
			$sli_adaptiveheight = ',"adaptiveHeight": false';
			$sli_fade = ',"fade":false';
			$sli_dataloadmore = 'data-loadmore="no"';
			
			//if we are loading more at end
			if(isset($currentform[0]->load_more) && $currentform[0]->load_more=="yes"){
				$sli_dataloadmore = 'data-loadmore="yes"';
			}

			//slides to scroll one at a time
			if(isset($template_misc_array['sli_slidestoscroll']) && $template_misc_array['sli_slidestoscroll']=='yes'){
				$sli_slidestoscroll =',"slidesToScroll": 1';
			}
			
			if($currentform[0]->sliderautoplay!="" && $currentform[0]->sliderautoplay=='yes'){
				$sli_autoplay = ',"autoplay": true';
			}
			if($currentform[0]->sliderdelay!="" && $sli_autoplay!=''){
				$sli_autoplaySpeedval = intval($currentform[0]->sliderdelay)*1000;
				$sli_autoplaySpeed = ',"autoplaySpeed": '.$sli_autoplaySpeedval.'';
				//change to linear easing if 0 delay.
				if($sli_autoplaySpeedval==0){
					$sli_autoplaySpeed = $sli_autoplaySpeed . ',"cssEase": "linear"';
				}
			}
			//echo $currentform[0]->sliderspeed;
			if($currentform[0]->sliderspeed!="" && intval($currentform[0]->sliderspeed)>0){
				$sli_Speedval = intval($currentform[0]->sliderspeed);
				$sli_Speed = ',"speed": '.$sli_Speedval.'';
			}
			if($currentform[0]->sliderdirection=='fade'){
				if($currentform[0]->display_num>1){
					//have to force slidesToScroll to slidesToShow
					$sli_slidestoscroll =',"slidesToScroll": '.$currentform[0]->display_num;
					$sli_Speed = ',"speed": 0,"cssEase": "linear"';
					//have to hack a solution if we are showing more than one review and fade is true
					$sli_transition = $sli_Speedval/1000;
					//add some CSS
					echo '<style>#wprev-slider-'.$currentform[0]->id.' .slickwprev-slide:not(.slickwprev-current):not(.slickwprev-active) {opacity: 0;transition: opacity '.$sli_transition.'s linear;}
					#wprev-slider-'.$currentform[0]->id.' .slickwprev-active{opacity: 1;transition: opacity '.$sli_transition.'s linear;}</style>';
				} else {
					$sli_fade = ',"fade":true';
				}
			}
			//hiding or showing dots
			if($currentform[0]->sliderdots=='no'){
				$sli_dots = ',"dots": false';
			}
			//hide or showing arrows
			if($currentform[0]->sliderarrows=='no'){
				$sli_arrows = ',"arrows": false';
			}
			//adaptive height
			if($currentform[0]->sliderheight=='yes'){
				$sli_adaptiveheight = ',"adaptiveHeight": true';
				//we have to hack this if it is more than one review per a slide, code is js file.
				echo '<style>#wprev-slider-'.$currentform[0]->id.' .slick-list {transition: all .5s ease;}</style>';
			}
			//infinite slide
			if(isset($template_misc_array['sli_infinite']) && $template_misc_array['sli_infinite']=='yes'){
				$sli_infinite =',"infinite": true';
			}
			//center mode
			if(isset($template_misc_array['sli_centermode']) && $template_misc_array['sli_centermode']=='yes'){
				$sli_centermode =',"centerMode": true';
				//force to show and scroll one slide since this is center mode
				$slidesetup ='"slidesToShow": 1';
				$sli_slidestoscroll =',"slidesToScroll": 1';
			} else {
				$sli_centermode =',"centerMode": false';
			}
			//center mode padding
			$sli_centermode_padding = ',"centerPadding": "40px"';
			if(isset($template_misc_array['sli_centermode_padding']) && $template_misc_array['sli_centermode_padding']>0){
				$sli_centermode_padding =',"centerPadding": "'.$template_misc_array['sli_centermode_padding'].'px"';
			}
			
			//if we are doing continous slide then we need to force scroll one at a time.
			if($sli_autoplaySpeedval==0){
			$sli_slidestoscroll =',"slidesToScroll": 1';
			}


			echo '<div id="wprevgoslickid_'.esc_attr($currentform[0]->id).'" '.$slickrtlhtml.' style="display:none;" class="wprevgoslick w3_wprs-row" '.$dataavatartemplate.' data-totalreviewsnum="'.$totalreviewsnum.'" data-revsperrow="'.intval($currentform[0]->display_num).'" data-wprevmasonry="'.esc_attr($currentform[0]->display_masonry).'" '.$sli_dataloadmore.' data-avatarnav="no" data-revsameheight="'.$revsameheight.'" data-slickwprev=\'{'.$slidesetup.''.$sli_slidestoscroll.''.$sli_dots.''.$sli_arrows.''.$sli_infinite.''.$sli_Speed.''.$sli_adaptiveheight.$sli_centermode.$sli_fade.$sli_centermode_padding.$slickrtl.$sli_rows.$sli_autoplay.$sli_autoplaySpeed.$sli_asnavfor.'}\'>';
		}
		//---------end slick slider-----------------------
		
		//only using this variable for using filter on sli ajax load
		$sliusingfilter=false;

		//loop through each chunk
		foreach ( $totalreviewschunked as $reviewschunked ){
			//echo "loop1";
			$totalreviewstemp = $reviewschunked;
			//print_r($totalreviewstemp);
			//need to break $totalreviewstemp up based on how many rows, create an multi array containing them
			if($currentform[0]->display_num_rows>1 && count($totalreviewstemp)>$currentform[0]->display_num){
				//count of reviews total is greater than display per row then we need to break in to multiple rows
				for ($row = 0; $row < $currentform[0]->display_num_rows; $row++) {
					$n=1;
					foreach ( $totalreviewstemp as $tempreview ){
						//echo "<br>".$tempreview->reviewer_name;
						if($n>($row*$currentform[0]->display_num) && $n<=(($row+1)*$currentform[0]->display_num)){
							//echo $row."-".$n."-".$tempreview->reviewer_name."<br>";
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
			if($currentform[0]->style=="1" || $currentform[0]->style=="2" || $currentform[0]->style=="3" || $currentform[0]->style=="4" || $currentform[0]->style=="5" || $currentform[0]->style=="6" || $currentform[0]->style=="7" || $currentform[0]->style=="8" || $currentform[0]->style=="9" || $currentform[0]->style=="10" || $currentform[0]->style=="11" || $currentform[0]->style=="12" || $currentform[0]->style=="13"){
				$iswidget=false;
				//display_masonry-------------
				//print_r($currentform[0]);
				if(	$currentform[0]->display_masonry=="yes" && $currentform[0]->createslider != "sli"){
					if($makingslideshow){
						$masonryclass = "wprs_masonry";
						$masonryclass_item = "wprs_masonry_item";
					} else {
						$masonryclass = "wprs_masonry_js";
						$masonryclass_item = "wprs_masonry_item_js";
					}
					echo '<div class="'.$masonryclass.'" data-numcol="'.$currentform[0]->display_num.'">';
				}	//display_masonry-------------

				$iswidget=false;
				$ajaxsliload = false;
				
				
				if($currentform[0]->style=='1'){
					include(plugin_dir_path( __FILE__ ) . 'template_style_'.$currentform[0]->style.'.php');
				} else {
					if ( wrsp_fs()->can_use_premium_code() ) {
						include(plugin_dir_path( __FILE__ ) . 'template_style_'.$currentform[0]->style.'.php');
					}
				}
				
				
				//display_masonry------------
				if(	$currentform[0]->display_masonry=="yes" && $currentform[0]->createslider != "sli"){
					echo '</div>';
				}
				//display_masonry------------
			}
			

			//if making slide show then end loop here
			if($makingslideshow){
					echo '</li>';
			}
			
			unset($rowarray);
		
		}	//end loop chunks
		
		//end slick div here.
		if($currentform[0]->createslider == "sli"){
			echo '</div>';
		}
		//if we are creating avatar navigation then we create another simple slider here with just avatars that will control slider above
		//=============================
		if($slickavatarnav){
			$totalreviewschunked = $totalreviewschunkedoriginal;
			$totalreviewschunked = array_chunk($totalreviews, $reviewsperpage);
			//$currentform[0]->display_num_rows = $org_display_num_rows;
			$currentform[0]->display_num_rows = 1;
			$currentform[0]->display_num = $org_display_num;
			if ( is_rtl() ) {
				$slickrtl = ', rtl:true';
			}
			$sli_rows = ', "rows":'.intval($currentform[0]->display_num_rows).'';		//used to create a grid mode with slidesPerRow
			$slidesetup ='"slidesToShow": '.intval($currentform[0]->display_num);
			$sli_autoplay = '';
			$sli_autoplaySpeed = '';
			$sli_slidestoscroll =',"slidesToScroll": 1';
			$sli_arrows = ',"arrows": true';
			$sli_fade = ',"fade":false';
			$sli_adaptiveheight = ',"adaptiveHeight": false';
			echo '<div class="wprevgoslicknavcontainer"><div id="wprevgoslickidnav_'.intval($currentform[0]->id).'" style="display:none;" class="wprevgoslick w3_wprs-row-padding" '.$dataavatartemplate.' data-avatarnav="yes" '.$sli_dataloadmore.' data-wprevmasonry="'.esc_attr($currentform[0]->display_masonry).'" data-slickwprev=\'{'.$slidesetup.''.$sli_slidestoscroll.''.$sli_dots.''.$sli_arrows.''.$sli_infinite.''.$sli_Speed.''.$sli_adaptiveheight.', "asNavFor": "#wprevgoslickid_'.intval($currentform[0]->id).'", "centerMode": true'.$sli_fade.', "centerPadding": "0px"'.$slickrtl.$sli_rows.$sli_autoplay.$sli_autoplaySpeed.'}\'>';
			
			//loop through each chunk
		foreach ( $totalreviewschunked as $reviewschunked ){

			$totalreviewstemp = $reviewschunked;
			//everything on one row so just put in multi array
			$rowarray[0]=$totalreviewstemp;
				
				include(plugin_dir_path( __FILE__ ) . 'avatarnav.php');
				unset($rowarray);
		}
		echo '</div></div>';

		}//===============================================
		
		

		//---add load more button if turned on
		$jslastslide ='';
		if(isset($currentform[0]->load_more) && $currentform[0]->load_more=="yes"){
			//if sort is random or picking reviews then we need to add the ids to button so we can test and do a NOT IN call 
			$notinstring='';
			//if($currentform[0]->display_order=="random" && $currentform[0]->showreviewsbyid!=""){
				$alreadygrabbedreviews=Array();
				for ($x = 0; $x < count($totalreviews); $x++) {
					if(isset($totalreviews[$x]->id)){
						$alreadygrabbedreviews[] = $totalreviews[$x]->id;
					}
				}
				$notinstring= implode(",",$alreadygrabbedreviews);
			//}
			//($currentform,$iswidget,$makingslideshow, $notinstring='',$shortcodepageid='',$shortcodelang='',$cpostid='' )
			$cpostid = get_the_ID();
			$getloadmorebtnhtml = $reviewsclass->wppro_getloadmorebtnhtml($currentform,$iswidget,$makingslideshow,$notinstring,$shortcodepageid,$shortcodelang,$cpostid,$totalreviewsarray['totalcount'],$shortcodetag,$forceloadmore);
			
			//echo out html in hidden div for load more functionality, jslastslide is now loaded via js file since v11.6.3
			echo $getloadmorebtnhtml['echothis'];
			
			//only add if we need it.
			if($totalreviewsarray['totalcount']>$reviewsperpage){
				$jslastslide = $getloadmorebtnhtml['jslastslide'];
			}
		}
		
			
		//if making slide show add this stuff. 
		if($makingslideshow){
			echo '</ul></div>';
				
		} else {
			echo '</div>';
		}

		//google snippet html
		$tempsnippethtml = $reviewsclass->wppro_getgooglesnippet($currentform,$totalreviewsarray['totalcount'],$totalreviewsarray['totalavg'],$totalreviews);
		echo $tempsnippethtml;
	 
	}
}
?>

