<?php
class GetReviews_Functions {
	
	//============================================================
	//functions for querying database for correct reviews to display, called by wp-review-slider-pro-public-display
	//--------------------------
	//
	/**
	 * Called from public partials wp-review-slider-pro-public-display, to return totalreivews
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function wppro_queryreviews($currentform,$startoffset=0,$totaltoget=0,$notinstring='',$shortcodepageid='',$shortcodelang='',$cpostid='', $textsearch='', $textsort='', $textrating='', $textlang='',$shortcodetag='',$sc_strhasone='',$sc_strhasall='',$sc_strnot='',$textrtype='', $textsource=''){
		global $wpdb;
				
		//table limit changes if we are calling this from load more button click
		$notinsearchstring ='';
		if($totaltoget>0){
			//this must be load more click
			$totaltoget = $totaltoget + 1;	//testing to see if we need to show the load more btn again.
			$tablelimit = $totaltoget;
			//add a not in statement after $sortdir if we are rand search ex: AND book_price NOT IN (100,200)
			if($notinstring!=''){
				//explode implode for safety
				$tempnotinarray = explode(",",$notinstring);
				if(is_array($tempnotinarray)){
					$notinstring = implode(",",$tempnotinarray);
					$notinsearchstring = " AND id NOT IN (".$notinstring.") ";
				}
			}
			$tablelimit = $startoffset.",".$totaltoget;


		} else {
			$reviewsperpage= $currentform[0]->display_num*$currentform[0]->display_num_rows;
			$tablelimit = $reviewsperpage;
			//change limit for slider
			if($currentform[0]->createslider == "yes" || $currentform[0]->createslider == "sli"){
				$tablelimit = $tablelimit*$currentform[0]->numslides;
			}
		}

		
		//template misc stuff
		$template_misc_array = json_decode($currentform[0]->template_misc, true);
		
		//add text search if we are using pagination and the search box
		$textsearchquery = '';
		$tagsearch='';
		if(!isset($template_misc_array['header_tag_search'])){
			$template_misc_array['header_tag_search']='';
		}
		if($textsearch!=''){
			if($template_misc_array['header_tag_search']=='tags'){
				$textsearchquery = "AND (tags LIKE '%%".sanitize_text_field($textsearch)."%%')";
			} else if($template_misc_array['header_tag_search']=='both'){
				$textsearchquery = "AND (reviewer_name LIKE '%%".sanitize_text_field($textsearch)."%%' or review_text LIKE '%%".sanitize_text_field($textsearch)."%%' or review_title LIKE '%%".sanitize_text_field($textsearch)."%%' or tags LIKE '%%".sanitize_text_field($textsearch)."%%' or type LIKE '%%".sanitize_text_field($textsearch)."%%')";
			} else {
			$textsearchquery = "AND (reviewer_name LIKE '%%".sanitize_text_field($textsearch)."%%' or review_text LIKE '%%".sanitize_text_field($textsearch)."%%' or review_title LIKE '%%".sanitize_text_field($textsearch)."%%' or type LIKE '%%".sanitize_text_field($textsearch)."%%')";
			}
		}
		//echo $textsearchquery;
		
		//filter by tag, template setting
		$tagfilter='';
		if(isset($template_misc_array['tagfilterlist']) && $template_misc_array['tagfilterlist']!=''){
			$tagcodearray = json_decode($template_misc_array['tagfilterlist'],true);
			$tagfilterlist_opt = '';
			if(isset($template_misc_array['tagfilterlist_opt']) && $template_misc_array['tagfilterlist_opt']!=''){
				$tagfilterlist_opt = $template_misc_array['tagfilterlist_opt'];
			}
			if(is_array($tagcodearray)){
				$tagcodearray = array_filter($tagcodearray);
				$tagcodearray = array_values($tagcodearray);
				if(count($tagcodearray)>0){
					for ($x = 0; $x < count($tagcodearray); $x++) {
						if($tagfilterlist_opt=='notthese'){
							if($x==0){
								$tagfilter = "AND (tags NOT LIKE '%\"".$tagcodearray[$x]."\"%'";
							} else {
								$tagfilter = $tagfilter." OR tags NOT LIKE '%\"".$tagcodearray[$x]."\"%'";
							}
						} else {
							if($x==0){
								$tagfilter = "AND (tags LIKE '%\"".$tagcodearray[$x]."\"%'";
							} else {
								$tagfilter = $tagfilter." OR tags LIKE '%\"".$tagcodearray[$x]."\"%'";
							}
						}
					}
					$tagfilter = $tagfilter.")";
				}
			}
		}
		
		//echo $tagfilter;
		//die();
		
		
		//use values from currentform to get reviews from db
		$table_name = $wpdb->prefix . 'wpfb_reviews';
		
		if($textsort!=''){
			if($textsort=='newest'){
				$sorttable = "created_time_stamp ";
				$sortdir = "DESC";
			} else if($textsort=='oldest'){
				$sorttable = "created_time_stamp ";
				$sortdir = "ASC";
			} else if($textsort=='highest'){
				$sorttable = "rating";
				$sortdir = "DESC, recommendation_type DESC";
			} else if($textsort=='lowest'){
				$sorttable = "rating ";
				$sortdir = "ASC, recommendation_type ASC";
			} else if($textsort=='longest'){
				$sorttable = "review_length_char ";
				$sortdir = "DESC";
			} else if($textsort=='shortest'){
				$sorttable = "review_length_char ";
				$sortdir = "ASC";
			} else if($textsort=='random'){
				$sorttable = "RAND() ";
				$sortdir = "";
			}
		} else {
			$sorttable = "created_time_stamp ";
			$sortdir = "DESC";
			if($currentform[0]->display_order=="random"){
				$sorttable = "RAND() ";
				$sortdir = "";
			} else if($currentform[0]->display_order=="oldest"){
				$sorttable = "created_time_stamp ";
				$sortdir = "ASC";
			} else if($currentform[0]->display_order=="newest"){
				$sorttable = "created_time_stamp ";
				$sortdir = "DESC";
			} else if($currentform[0]->display_order=='highest'){
				$sorttable = "rating";
				$sortdir = "DESC, recommendation_type DESC";
			} else if($currentform[0]->display_order=='lowest'){
				$sorttable = "rating ";
				$sortdir = "ASC, recommendation_type ASC";
			} else if($currentform[0]->display_order=='longest'){
				$sorttable = "review_length_char ";
				$sortdir = "DESC";
			} else if($currentform[0]->display_order=='shortest'){
				$sorttable = "review_length_char ";
				$sortdir = "ASC";
			} else if($currentform[0]->display_order=='sortweight'){
				$sorttable = "sort_weight ";
				$sortdir = "DESC";
			}
			//add second sort_weight
			if(isset($currentform[0]->display_order_second) && $currentform[0]->display_order_second!=''){
				if($currentform[0]->display_order_second=="random"){
					$sortdir = $sortdir . ",RAND()";
				} else if($currentform[0]->display_order_second=="oldest"){
					$sortdir = $sortdir . ", created_time_stamp ASC";
				} else if($currentform[0]->display_order_second=="newest"){
					$sortdir = $sortdir . ", created_time_stamp DESC";
				} else if($currentform[0]->display_order_second=='highest'){
					$sortdir = $sortdir . ", rating DESC";
				} else if($currentform[0]->display_order_second=='lowest'){
					$sortdir = $sortdir . ", rating ASC";
				} else if($currentform[0]->display_order_second=='longest'){
					$sortdir = $sortdir . ", review_length_char DESC";
				} else if($currentform[0]->display_order_second=='shortest'){
					$sortdir = $sortdir . ", review_length_char ASC";
				} else if($currentform[0]->display_order_second=='sortweight'){
					$sortdir = $sortdir . ", sort_weight DESC";
				}
			}
		}
		
		if($currentform[0]->hide_no_text=="yes"){
			$min_words = 1;
			$max_words = 5000;
		} else {
			$min_words = 0;
			$max_words = 5000;
		}
		
		//-----------------------------
		//======pro filter settings 	min_words, max_words, min_rating, rtype, rpage, filterbytext showreviewsbyid========
		if(isset($currentform[0]->min_words) && $currentform[0]->min_words>0){
			$min_words = intval($currentform[0]->min_words);
		}
		if(isset($currentform[0]->max_words) &&  $currentform[0]->max_words>0){
			$max_words = intval($currentform[0]->max_words);
		}
		//filter length based on word count or char count
		$lengthquery = "review_length >= %d AND review_length <= %d";
		if(isset($currentform[0]->word_or_char) && $currentform[0]->word_or_char=='char'){
			$lengthquery = "review_length_char >= %d AND review_length_char <= %d";
		}
		
		//min_rating filter----
		if($currentform[0]->min_rating>0){
			$min_rating = intval($currentform[0]->min_rating);
			//grab positive recommendations as well
			if($min_rating==1){
				$min_rating=0;
			}
			if($min_rating==0){
				//fix to show non rated woocommerce comments also
				$ratingquery = " AND (rating >= '".$min_rating."' OR rating = '' OR recommendation_type = 'positive' OR recommendation_type = 'negative' ) ";
			} else if($min_rating<3){
				//show positive and negative
				//$ratingquery = " AND rating >= '".$min_rating."' ";
				$ratingquery = " AND (rating >= '".$min_rating."' OR recommendation_type = 'positive' OR recommendation_type = 'negative' ) ";
			} else {
				//only show positive
				$ratingquery = " AND (rating >= '".$min_rating."' OR recommendation_type = 'positive' ) ";
			}
		} else {
			$min_rating ="";
			$ratingquery ="";
		}
		
		//customer input rating filter
		$ratingquerypublic ="";
		if($textrating>0 && $textrating!='unset'){
			$textrating = intval($textrating);
			$ratingquerypublic = " AND rating = '".$textrating."' ";
		}
		
		//display random limit by month
		$randlimitfilter = "";
		if($currentform[0]->display_order=="random" &&  $currentform[0]->display_order_limit!="all" &&  $currentform[0]->display_order_limit!=""){
			$current_time=time();
			$howmanyago = "-".$currentform[0]->display_order_limit." month";
			$pasttime = strtotime($howmanyago, $current_time);
			$randlimitfilter = " AND created_time_stamp > '".$pasttime."' ";
		}
		
		//rtype filter-----
		$rtypefilter = "";
		if($currentform[0]->rtype!=""){
			$rtypearray = json_decode($currentform[0]->rtype);
			if(is_array($rtypearray)){
			$rtypearray = array_filter($rtypearray);
			$rtypearray = array_values($rtypearray);
			if(count($rtypearray)>0){
				for ($x = 0; $x < count($rtypearray); $x++) {
					//check if any manual_custom is set
					if (strpos($rtypearray[$x], '_') !== false) {
						//add from_name search
						$tempsearcharray = explode("_",$rtypearray[$x]);
						if($x==0){
							$rtypefilter = "AND ((type = '".$tempsearcharray[0]."' AND from_name = '".$tempsearcharray[1]."')";
						} else {
							$rtypefilter = $rtypefilter." OR (type = '".$tempsearcharray[0]."' AND from_name = '".$tempsearcharray[1]."')";
						}
					} else {
						if($x==0){
							$rtypefilter = "AND (type = '".$rtypearray[$x]."'";
						} else {
							$rtypefilter = $rtypefilter." OR type = '".$rtypearray[$x]."'";
						}
					}
				}
				$rtypefilter = $rtypefilter.")";
			}
			}
		}
		//rtype filter on front end header bar.
		$publicrtypefilter='';
		if($textrtype!='' && $textrtype!='unset'){
			$publicrtypefilter = "AND type = '".sanitize_text_field($textrtype)."'";
		}
		
		
		//rpage filter-----
		$rpagefilter = "";
		$shortcodepageidarray = explode(",",$shortcodepageid);
		//print_r($shortcodepageidarray);
		if($currentform[0]->rpage!=""){
			$rpagearray = json_decode($currentform[0]->rpage);
			//need to also check for &amp; in pageid, this is because of it getting saved wrong.
			if(isset($rpagearray) && is_array($rpagearray)){
				foreach ($rpagearray as &$value) {
					if(strpos($value, '&')){
						$newpageid = str_replace("&", "&amp;", $value);
						$rpagearraynew[] = $newpageid;
					}
				}
			}
			if(isset($rpagearraynew) && is_array($rpagearraynew)){
				$rpagearray = array_merge($rpagearray, $rpagearraynew);
			}
			
			if(is_array($rpagearray)){
			$rpagearray = array_filter($rpagearray);
			$rpagearray = array_values($rpagearray);
			if(count($rpagearray)>0){
				for ($x = 0; $x < count($rpagearray); $x++) {
					if($x==0){
						$rpagefilter = "AND (pageid = '".$rpagearray[$x]."'";
					} else {
						$rpagefilter = $rpagefilter." OR pageid = '".$rpagearray[$x]."'";
					}
				}
				//add shortcode pageid
				for ($k = 0; $k < count($shortcodepageidarray); $k++) {
					if($shortcodepageidarray[$k]!=''){
						$rpagefilter = $rpagefilter." OR pageid = '".trim($shortcodepageidarray[$k])."'";
					}
				}
				$rpagefilter = $rpagefilter.")";
			}
			}
		}
		//rpage filter in shortcodepageid
		if($shortcodepageid!='' && $rpagefilter==""){
				for ($x = 0; $x < count($shortcodepageidarray); $x++) {
					if($x==0){
						$rpagefilter = "AND (pageid = '".trim($shortcodepageidarray[$x])."'";
					} else {
						$rpagefilter = $rpagefilter." OR pageid = '".trim($shortcodepageidarray[$x])."'";
					}
				}
				$rpagefilter = $rpagefilter.")";
		}
		
		//echo $rpagefilter;
		
		//rpostid filter-----
		$rpostidfilter = "";
		//echo "pfilter:".$template_misc_array['postfilter'];
		//echo "<br>";
		if(!isset($template_misc_array['postfilter'])){
			$template_misc_array['postfilter']='no';
		}
		if($template_misc_array['postfilter']=='yes'){
			//first grab current post id from this page, doesn't work on load more, need to pass through ajax.
			//check if passed first
			if($cpostid!=''){
				$rpostidarray[] = $cpostid;
			} else { 
				$rpostidarray[] = get_the_ID();
			}
			//now add additional post id
			$rpostidarraymore = json_decode($template_misc_array['postfilterlist'],true);
			if(is_array($rpostidarraymore)){
				$rpostidarray = array_merge($rpostidarray, $rpostidarraymore);
			}
			if(is_array($rpostidarray)){
				$rpostidarray = array_filter($rpostidarray);
				$rpostidarray = array_values($rpostidarray);
				if(count($rpostidarray)>0){
					for ($x = 0; $x < count($rpostidarray); $x++) {
						if($x==0){
							$rpostidfilter = "AND (posts LIKE '%-".$rpostidarray[$x]."-%'";
						} else {
							$rpostidfilter = $rpostidfilter." OR posts LIKE '%-".$rpostidarray[$x]."-%'";
						}
					}
					$rpostidfilter = $rpostidfilter.")";
				}
			}
		}
		//echo "rpostidfilter:".$rpostidfilter;
		//echo "<br>";
		
		//rcatid filter-----
		$rcatidfilter = "";
		if(!isset($template_misc_array['categoryfilter'])){
			$template_misc_array['categoryfilter']='no';
		}
		if($template_misc_array['categoryfilter']=='yes'){
			//first grab current category id from this page
			//$categories = get_the_category();
			$taxonomies=get_taxonomies('','names');
			if($cpostid!=''){
				$postid = $cpostid;
			} else { 
				$postid = get_the_ID();
			}
			$categories =wp_get_post_terms($postid, $taxonomies,  array("fields" => "ids"));
			$arrlength = count($categories);
			if($arrlength>0){
				$rcategoryidarray = $categories;
			//for($x = 0; $x < $arrlength; $x++) {
			//	$rcategoryidarray[] = $categories[$x]->cat_ID;	//array containing just the cat_IDs that this post belongs to
			//}
			} else {
				$rcategoryidarray[]='';
			}
			//now add additional category id
			$rcategoryidarraymore = json_decode($template_misc_array['categoryfilterlist'],true);
			if(is_array($rcategoryidarraymore) && is_array($rcategoryidarray)){
				$rcategoryidarray = array_merge($rcategoryidarray, $rcategoryidarraymore);
			}
			if(is_array($rcategoryidarray)){
				$rcategoryidarray = array_filter($rcategoryidarray);
				$rcategoryidarray = array_values($rcategoryidarray);
				if(count($rcategoryidarray)>0){
					for ($x = 0; $x < count($rcategoryidarray); $x++) {
						if($x==0){
							$rcatidfilter = "AND (categories LIKE '%-".$rcategoryidarray[$x]."-%'";
						} else {
							$rcatidfilter = $rcatidfilter." OR categories LIKE '%-".$rcategoryidarray[$x]."-%'";
						}
					}
					$rcatidfilter = $rcatidfilter.")";
				}
			}
		}

		//filter by language code
		$langfilter='';
		if(isset($template_misc_array['langfilterlist']) && $template_misc_array['langfilterlist']!=''){
			$langcodearray = json_decode($template_misc_array['langfilterlist'],true);
			
			if(is_array($langcodearray)){
				$langcodearray = array_filter($langcodearray);
				$langcodearray = array_values($langcodearray);
				if(count($langcodearray)>0){
					for ($x = 0; $x < count($langcodearray); $x++) {
						if($x==0){
							$langfilter = "AND (language_code = '".$langcodearray[$x]."'";
						} else {
							$langfilter = $langfilter." OR language_code = '".$langcodearray[$x]."'";
						}
					}
					$langfilter = $langfilter.")";
				}
			}
		}
		//filter by language if this is wpml site and the checkbox is checked on the template settings
		//ICL_LANGUAGE_CODE
		//$templatemiscarray['wpmllang']
		if(isset($template_misc_array['wpmllang']) && $template_misc_array['wpmllang']=='yes'){
			if (defined('ICL_LANGUAGE_CODE')) {
				if(ICL_LANGUAGE_CODE!=''){
					$langfilter = $langfilter . " AND language_code = '".ICL_LANGUAGE_CODE."'";
				}
			}
		}
		
		//tag filter in shortcode //	'%\"tag\"%'
		$shortcodetagfilter='';
		if($shortcodetag!=''){
			$shortcodetagarray = explode(",",$shortcodetag);
				for ($x = 0; $x < count($shortcodetagarray); $x++) {
					if($x==0){
							$shortcodetagfilter = " AND (tags LIKE '%\"".sanitize_text_field($shortcodetagarray[$x])."\"%'";
						} else {
							$shortcodetagfilter = $shortcodetagfilter." OR tags LIKE '%\"".sanitize_text_field($shortcodetagarray[$x])."\"%'";
						}
					}
					$shortcodetagfilter = $shortcodetagfilter.")";
		}
		
		//language code filter in shortcode
		$shortlangfilter='';
		if($shortcodelang!=''){
			$shortcodelangarray = explode(",",$shortcodelang);
				for ($x = 0; $x < count($shortcodelangarray); $x++) {
					if($x==0){
							$shortlangfilter = " AND (language_code = '".sanitize_text_field($shortcodelangarray[$x])."'";
						} else {
							$shortlangfilter = $shortlangfilter." OR language_code = '".sanitize_text_field($shortcodelangarray[$x])."'";
						}
					}
					$shortlangfilter = $shortlangfilter.")";
		}
		//language code filter on front end
		$publiclangfilter='';
		if($textlang!='' && $textlang!='unset'){
			$publiclangfilter = " AND language_code = '".sanitize_text_field($textlang)."'";
		}
		//source code filter on front end
		$publicsourcefilter='';
		if($textsource!='' && $textsource!='unset'){
			$publicsourcefilter = " AND pageid = '".sanitize_text_field($textsource)."'";
		}
		
		//filter by keyword in template setting 
		$rstringfilter = "";
		if(isset($currentform[0]->string_sel) && $currentform[0]->string_sel!='no' && $currentform[0]->string_sel!=''){
		if(isset($currentform[0]->string_text) && $currentform[0]->string_text!=""){
			$strarray = explode(',',$currentform[0]->string_text);
			$strarray = array_filter($strarray);
			if(count($strarray)>0){
					for ($x = 0; $x < count($strarray); $x++) {
						$tempstring = trim($strarray[$x]);
						if($currentform[0]->string_sel=='all'){
							if($x==0){
								$rstringfilter = "AND (review_text LIKE '%%".$tempstring."%%'";
							} else {
								$rstringfilter = $rstringfilter." AND review_text LIKE '%%".$tempstring."%%'";
							}
					
						} else if ($currentform[0]->string_sel=='any'){
							if($x==0){
								$rstringfilter = "AND (review_text LIKE '%%".$tempstring."%%'";
							} else {
								$rstringfilter = $rstringfilter." OR review_text LIKE '%%".$tempstring."%%'";
							}
						} else if ($currentform[0]->string_sel=='not'){
							if($x==0){
								$rstringfilter = "AND (review_text NOT LIKE '%%".$tempstring."%%'";
							} else {
								$rstringfilter = $rstringfilter." AND review_text NOT LIKE '%%".$tempstring."%%'";
							}
						}
					}
					$rstringfilter = $rstringfilter.")";
			}
		}
		}
		//and with shortcode has one word in review text
		//$sc_strhasone=''
		$tempstring ='';
		if($sc_strhasone!=''){
			$sc_strhasonearray = explode(',',$sc_strhasone);
			$sc_strhasonearray = array_filter($sc_strhasonearray);
			if(count($sc_strhasonearray)>0){
					for ($x = 0; $x < count($sc_strhasonearray); $x++) {
						$tempstring = trim($sc_strhasonearray[$x]);
						if($x==0){
							$rstringfilter = $rstringfilter."AND (review_text LIKE '%%".sanitize_text_field($tempstring)."%%'";
						} else {
							$rstringfilter = $rstringfilter." OR review_text LIKE '%%".sanitize_text_field($tempstring)."%%'";
						}
					}
					$rstringfilter = $rstringfilter.")";
			}
		}
		//and with shortcode has all words in review text
		//$sc_strhasall=''
		$tempstring ='';
		if($sc_strhasall!=''){
			$sc_strhasallarray = explode(',',$sc_strhasall);
			$sc_strhasallarray = array_filter($sc_strhasallarray);
			if(count($sc_strhasallarray)>0){
					for ($x = 0; $x < count($sc_strhasallarray); $x++) {
						$tempstring = trim($sc_strhasallarray[$x]);
						if($x==0){
							$rstringfilter = "AND (review_text LIKE '%%".sanitize_text_field($tempstring)."%%'";
						} else {
							$rstringfilter = $rstringfilter." AND review_text LIKE '%%".sanitize_text_field($tempstring)."%%'";
						}
					}
					$rstringfilter = $rstringfilter.")";
			}
		}
		//and with shortcode not in review text
		//$sc_strnot=''
		$tempstring ='';
		if($sc_strnot!=''){
			$sc_strnotarray = explode(',',$sc_strnot);
			$sc_strnotarray = array_filter($sc_strnotarray);
			if(count($sc_strnotarray)>0){
					for ($x = 0; $x < count($sc_strnotarray); $x++) {
						$tempstring = trim($sc_strnotarray[$x]);
						if($x==0){
							$rstringfilter = "AND (review_text NOT LIKE '%%".sanitize_text_field($tempstring)."%%'";
						} else {
							$rstringfilter = $rstringfilter." AND review_text NOT LIKE '%%".sanitize_text_field($tempstring)."%%'";
						}
					}
					$rstringfilter = $rstringfilter.")";
			}
		}
		
		
		//filter by keyword NOT IN
		$rstringfilternot = "";
		if(isset($currentform[0]->string_selnot) && $currentform[0]->string_selnot!='no' && $currentform[0]->string_selnot!=''){
		if(isset($currentform[0]->string_textnot) && $currentform[0]->string_textnot!=""){
			$strarraynot = explode(',',$currentform[0]->string_textnot);
			$strarraynot = array_filter($strarraynot);
			if(count($strarraynot)>0){
					for ($x = 0; $x < count($strarraynot); $x++) {
						$tempstringnot = trim($strarraynot[$x]);
						if ($currentform[0]->string_selnot=='not'){
							if($x==0){
								$rstringfilternot = "AND (review_text NOT LIKE '%%".$tempstringnot."%%'";
							} else {
								$rstringfilternot = $rstringfilternot." AND review_text NOT LIKE '%%".$tempstringnot."%%'";
							}
						}
					}
					$rstringfilternot = $rstringfilternot.")";
			}
		}
		}
		
		//showreviewsbyid filter---------replaces all other filters
		$onlyselected = false;
		$selectedfilter = "";
		$selectedreviews= array();
		//print_r($currentform);
		//die();
		if(!isset($currentform[0]->showreviewsbyid_sel)){
			$currentform[0]->showreviewsbyid_sel='';
		}
		if($currentform[0]->showreviewsbyid!=""){
			$showreviewsbyidarray = json_decode($currentform[0]->showreviewsbyid);
			$showreviewsbyidarray = array_filter($showreviewsbyidarray);
			$showreviewsbyidarray = array_values($showreviewsbyidarray);
			if(count($showreviewsbyidarray)>0){
				if( $currentform[0]->showreviewsbyid_sel!='theseplus'){
					$onlyselected = true;
				} else {
					//showing these plus other reviews from filter, build filter here
					$selectedfilter = "AND id IN (". implode(",",$showreviewsbyidarray).")";
					$selectedreviews = $wpdb->get_results(
						$wpdb->prepare("SELECT * FROM ".$table_name." WHERE id>%d ".$selectedfilter." ORDER BY ".$sorttable." ".$sortdir."","0")
					);
					//echo $wpdb->last_query ;
					//print_r($selectedreviews);
				}
			}
		}
		$nolimitreviews='';
		if(!isset($template_misc_array['header_text'])){
			$template_misc_array['header_text']='';
		}
		if(!isset($currentform[0]->load_more)){
			$currentform[0]->load_more='';
		}
		if(!isset($template_misc_array['header_rtypes'])){
			$template_misc_array['header_rtypes']='';
		}
		if(!isset($template_misc_array['header_banner'])){
			$template_misc_array['header_banner']='';
		}
		
		//if we are filtering by selected reviews only.
		if($onlyselected){
			$query = "SELECT * FROM ".$table_name." WHERE id IN (";
			//loop array and add to query
			$n=1;
			foreach ($showreviewsbyidarray as $value) {
				if($value!=""){
					if(count($showreviewsbyidarray)==$n){
						$query = $query." $value";
					} else {
						$query = $query." $value,";
					}
				}
				$n++;
			}
			$querylimit = $query.") ".$notinsearchstring."".$textsearchquery."".$ratingquerypublic."".$publiclangfilter."".$publicsourcefilter."".$publicrtypefilter." ORDER BY ".$sorttable." ".$sortdir." LIMIT ".$tablelimit." ";
			$querynolimit = $query.") ".$textsearchquery."".$ratingquerypublic."".$publiclangfilter."".$publicsourcefilter."".$publicrtypefilter." ORDER BY ".$sorttable." ".$sortdir."";
			
			$totalreviews = $wpdb->get_results($querylimit);
			$totalreviewsarray['dbcall'] = $wpdb->last_query;
			//run another query if we need total and average.
			if($currentform[0]->google_snippet_add=='yes' || $currentform[0]->load_more=='yes' ||  $template_misc_array['header_text']!='' || $template_misc_array['header_rtypes']=='yes' || $template_misc_array['header_banner']!=''){
				//find total and average in db
				$nolimitreviews = $wpdb->get_results($querynolimit,ARRAY_A);
			}
		} else {
			$totalreviews = $wpdb->get_results(
				$wpdb->prepare("SELECT * FROM ".$table_name."
				WHERE id>%d AND ".$lengthquery." AND hide != %s ".$rtypefilter." ".$rpagefilter." ".$rpostidfilter." ".$rstringfilter." ".$rstringfilternot." ".$rcatidfilter." ".$tagfilter." ".$langfilter." ".$shortlangfilter." ".$shortcodetagfilter." ".$randlimitfilter."".$ratingquery."".$notinsearchstring."".$textsearchquery."".$ratingquerypublic."".$publiclangfilter."".$publicsourcefilter."".$publicrtypefilter."
				ORDER BY ".$sorttable." ".$sortdir." LIMIT ".$tablelimit." ", "0","$min_words","$max_words","yes")
			);
			$totalreviewsarray['dbcall'] = $wpdb->last_query;
			//run another query if we need total and average.
			if($currentform[0]->google_snippet_add=='yes' || $currentform[0]->load_more=='yes' ||  $template_misc_array['header_text']!='' || $template_misc_array['header_rtypes']=='yes' || $template_misc_array['header_banner']!=''){
				$notinsearchstring ='';
				$nolimitreviews = $wpdb->get_results(
				$wpdb->prepare("SELECT * FROM ".$table_name."
				WHERE id>%d AND ".$lengthquery." AND hide != %s ".$rtypefilter." ".$rpagefilter." ".$rpostidfilter." ".$rstringfilter." ".$rstringfilternot." ".$rcatidfilter." ".$tagfilter." ".$langfilter." ".$shortlangfilter." ".$shortcodetagfilter." ".$randlimitfilter."".$ratingquery."".$notinsearchstring."".$textsearchquery."".$ratingquerypublic."".$publiclangfilter."".$publicsourcefilter."".$publicrtypefilter."
				ORDER BY ".$sorttable." ".$sortdir." ", "0","$min_words","$max_words","yes"),ARRAY_A);
			}
		}

//print_r($totalreviews);

		//if we also must show selected reviews combine here, only if not on Load More
		if( $currentform[0]->showreviewsbyid_sel=='theseplus' && $totaltoget<1){
			//print_r($selectedreviews);
			//add to $totalreviews if not in there.
			//echo $tablelimit;
			//echo "here".count($totalreviews);
			$flipselectedreviews = array_reverse($selectedreviews);		//so we keep order
			foreach ($flipselectedreviews as $current) {
				if ( ! in_array($current, $totalreviews)) {
					array_unshift($totalreviews,$current);	//adds item to beginning
					if($tablelimit<=count($totalreviews)){
						array_pop($totalreviews);	//removes an item at the end
					}
					//try to sort based on sortdir
					if($textsort=="oldest" || $currentform[0]->display_order=="oldest"){
						array_multisort( array_column( $totalreviews, 'created_time_stamp' ), SORT_ASC, SORT_NUMERIC, $totalreviews );
					}  else if($textsort=="highest" || $currentform[0]->display_order=="highest"){
						array_multisort( array_column( $totalreviews, 'rating' ), SORT_DESC, SORT_NUMERIC, $totalreviews );
					} else if($textsort=="lowest" || $currentform[0]->display_order=="lowest"){
						array_multisort( array_column( $totalreviews, 'rating' ), SORT_ASC, SORT_NUMERIC, $totalreviews );
					} else if($textsort=="longest" || $currentform[0]->display_order=="longest"){
						array_multisort( array_column( $totalreviews, 'review_length_char' ), SORT_DESC, SORT_NUMERIC, $totalreviews );
					} else if($textsort=="shortest" || $currentform[0]->display_order=="shortest"){
						array_multisort( array_column( $totalreviews, 'review_length_char' ), SORT_ASC, SORT_NUMERIC, $totalreviews );
					} else if($textsort=="random" || $currentform[0]->display_order=="random"){
						shuffle($totalreviews);
					} else if($textsort=="sortweight" || $currentform[0]->display_order=="sortweight"){
						array_multisort( array_column( $totalreviews, 'sort_weight' ), SORT_DESC, SORT_NUMERIC, $totalreviews );
					} else {
						//default to newest
						array_multisort( array_column( $totalreviews, 'created_time_stamp' ), SORT_DESC, SORT_NUMERIC, $totalreviews );
					}

				}
			}
		}
		//print_r($totalreviews);
		
		//we need both the reviews and the total in db if we are using load more or rich snippet
		$totalreviewsarray['reviews']=$totalreviews;
		$totalreviewsarray['totalcount']='';
		$totalreviewsarray['totalavg']='';
		$tempnum ='';
		//print_r($nolimitreviews);
		$reviewtypesarray = Array();
		$reviewspagesarray = Array();
		
		if(is_array($nolimitreviews)){
			$reviewratingsarray = Array();
			//loop allrevs to find total number of reviews and average of all of them.
			foreach ($nolimitreviews as $review) {
				$reviewtypesarray[]= $review['type'];
				$reviewspagesarray[]= $review['pageid'];
				
				if($review['rating']>0){
					$reviewratingsarray[] = intval($review['rating']);
					$tempnum = intval($review['rating']);
				}
				//also count positive and negative recommendations
				if($review['rating']<1 && $review['recommendation_type']=='positive'){
					$reviewratingsarray[] = 5;
					$tempnum = 5;
				}
				if($review['rating']<1 && $review['recommendation_type']=='negative'){
					$reviewratingsarray[] = 2;
					$tempnum = 2;
				}
				//find number of each star
				if($tempnum==1){
					$temprating[1][]=1;
				} else if($tempnum==2){
					$temprating[2][]=1;
				} else if($tempnum==3){
					$temprating[3][]=1;
				} else if($tempnum==4){
					$temprating[4][]=1;
				} else if($tempnum==5){
					$temprating[5][]=1;
				}
			}
			
			
			if(isset($temprating[1])){
				$totalreviewsarray['numr1'] = array_sum($temprating[1]);
			} else {
				$totalreviewsarray['numr1'] =0;
			}
			if(isset($temprating[2])){
				$totalreviewsarray['numr2'] = array_sum($temprating[2]);
			} else {
				$totalreviewsarray['numr2'] =0;
			}
			if(isset($temprating[3])){
				$totalreviewsarray['numr3'] = array_sum($temprating[3]);
			} else {
				$totalreviewsarray['numr3'] =0;
			}
			if(isset($temprating[4])){
				$totalreviewsarray['numr4'] = array_sum($temprating[4]);
			} else {
				$totalreviewsarray['numr4'] =0;
			}
			if(isset($temprating[5])){
				$totalreviewsarray['numr5'] = array_sum($temprating[5]);
			} else {
				$totalreviewsarray['numr5'] =0;
			}

			
			//remove empties
			$reviewratingsarray = array_filter($reviewratingsarray);
			$totalreviewsarray['totalcount']=count($reviewratingsarray);
			if(count($reviewratingsarray)>0){
				$reviewratingsarrayavg = array_sum($reviewratingsarray)/count($reviewratingsarray);
			} else {
				$reviewratingsarrayavg = 0;
			}
			$totalreviewsarray['totalavg'] = round($reviewratingsarrayavg,1);
		}
		$totalreviewsarray['reviewtypesarray'] = array_unique($reviewtypesarray);
		$totalreviewsarray['reviewspagesarray'] = array_unique($reviewspagesarray);
		
		//print_r(array_unique($reviewtypesarray));
		//print_r($totalreviewsarray);
		
			//echo "<br><br>";
		return $totalreviewsarray;

	}	
	
	public function wppro_getloadmorebtnhtml($currentform,$iswidget,$makingslideshow, $notinstring='',$shortcodepageid='',$shortcodelang='',$cpostid='',$totalcount='',$shortcodetag='',$forceloadmore=false ){
		$formid = intval($currentform[0]->id);
		$resultsecho='';
		$jslastslide ='';
		$ismakingslideshow = "";
		$iswidgethtml ='';
		$imageclassslideshow ='';
		$endlessscroll='';
		$template_misc_array = json_decode($currentform[0]->template_misc, true);
		$loading_img_url = esc_url( plugins_url( 'imgs/', __FILE__ ) ).'loading_ripple.gif';
		//number of reviews per a page
		$reviewsperpage= $currentform[0]->display_num*$currentform[0]->display_num_rows;
		$totalcount = intval($totalcount);

		$lastslidenumb = ceil($totalcount/$reviewsperpage);
		//echo "here:".$lastslidenumb;
		$hidepagination ='';
		if($lastslidenumb<2){	//need to hide pagination because only one page	
			$hidepagination = "style=display:none;";
		}
		
		//check for load more pagination setting, default to button
		if(isset($template_misc_array['load_more_porb']) && $template_misc_array['load_more_porb']=="pagenums"){
			$btnorpagenums = 'pagenums';
		} else {
			$btnorpagenums = 'btn';
		}
		
		if($iswidget==true){
			$iswidgethtml ="_widget";
		}
		$hidebtnhtml = '';
		if($currentform[0]->createslider == "sli"){
			$hidebtnhtml = "style=display:none;";
			$ismakingslideshow = "sli";
			$btnorpagenums='btn';
		}
		
		//if we are doing endless scroll also hide pagination button so we can click it. only for grid
		if(!$makingslideshow && isset($template_misc_array['load_more_porb']) && $template_misc_array['load_more_porb']=="scroll"){
			$hidepagination = "style=display:none;";
			$endlessscroll='data-endless="yes"';
		}
		
		if($currentform[0]->load_more=="yes" ){
			
			//if we are turning this load more on because of header then we should at least hide it if it has not been turned on.
			if($forceloadmore==true){
				$ldmorehtml = 'forceloadmorehide';
			} else {
				$ldmorehtml = '';
			}
			
			if($makingslideshow){
				$ismakingslideshow = "yes";
				$imageclassslideshow = "isinslideshowloadingimg";
				//hide button
				$hidebtnhtml = "style=display:none;";
				//different flor slideshow maybe
				$resultsecho = $resultsecho. '<li>';
				$jslastslide = 'slider.on("wprs_unslider.change", function(event, index, slide) {
					var loopnow = $("#wprev_load_more_btn_'.$formid.'").attr("loopnow");
					if(loopnow!="yes"){
					var numslides = $("#wprev-slider-'.$formid.$iswidgethtml.'").find( "li" ).length;
					if(index==-1){index = numslides-1;}
					if((numslides-1)==index){addslide(index+1,numslides+1);}
					}
					});
				function addslide(index,numslides){
					var hideldbtn = $("#wprev_load_more_btn_'.$formid.'").attr("hideldbtn");
					if(hideldbtn!="yes"){
					$("#wprev_load_more_btn_'.$formid.'").trigger("wprevlastslide");
					$("#wprev-slider-'.$formid.$iswidgethtml.'").find( "ul" ).append("<li></li>");
					slider.data("wprs_unslider").calculateSlides();
					$("#wprev-slider-'.$formid.$iswidgethtml.'").siblings("nav").remove();
					slider.data("wprs_unslider").initNav();
					$("#wprev-slider-'.$formid.$iswidgethtml.'").siblings("nav").find( "li" ).last().prev().addClass("wprs_unslider-active");
					} else {
					$("#wprev_load_more_btn_'.$formid.'").attr("loopnow","yes");
					$("#wprev-slider-'.$formid.$iswidgethtml.'").find( "ul li:last").remove();
					slider.data("wprs_unslider").calculateSlides();
					$("#wprev-slider-'.$formid.$iswidgethtml.'").siblings("nav").remove();
					slider.data("wprs_unslider").initNav();
					$("#wprev-slider-'.$formid.$iswidgethtml.'").siblings("nav").find( "li" ).last().prev().addClass("wprs_unslider-active");
					setTimeout(function(){slider.data("wprs_unslider").animate(0);}, 100);
					}}';
			}
			
			$reviewssameheight = "";
			if($currentform[0]->review_same_height=='yes' || $currentform[0]->review_same_height=='cur' || $currentform[0]->review_same_height=='yea'){
				$reviewssameheight = 'data-revsameheight="yes"';
			} else {
				$reviewssameheight = 'data-revsameheight="no"';
			}
					
			if($makingslideshow || $btnorpagenums=='btn'){
				$mobileoneperslide = "";
					if($currentform[0]->slidermobileview == "one"){
						$mobileoneperslide = 'data-onemobil="yes"';
					} else {
						$mobileoneperslide = 'data-onemobil="no"';
					}
				
				$loadmoretext = esc_html( $currentform[0]->load_more_text );
				if($loadmoretext == 'Load More'){
					$loadmoretext = __('load more', 'wp-review-slider-pro');
				}
					
				$resultsecho = $resultsecho. '<div '.$hidepagination.' class="wprevpro_load_more_div" ><button data-notinstring="'.$notinstring.'" '.$mobileoneperslide.' '.$reviewssameheight.' '.$endlessscroll.' data-callnum="1" data-ismasonry="'.$currentform[0]->display_masonry.'" data-slideshow="'.$ismakingslideshow.'" data-tid="'.$formid.'" data-nrows="'.intval($currentform[0]->display_num_rows).'" data-perrow="'.intval($currentform[0]->display_num).'" data-cpostid="'.$cpostid.'" data-shortcodepageid="'.$shortcodepageid.'" data-shortcodelang="'.$shortcodelang.'" data-shortcodetag="'.$shortcodetag.'" class="wprevpro_load_more_btn brnprevclass '.$ldmorehtml.'" id="wprev_load_more_btn_'.$formid.'" '.$hidebtnhtml.'>'.$loadmoretext.'</button><img loading="lazy" src="'.$loading_img_url.'" class="wprploadmore_loading_image '.$imageclassslideshow.'" alt="Button to load more customer reviews" style="display:none;"></div>';
			}
			if($makingslideshow){
				//different for slideshow maybe
				$resultsecho = $resultsecho. '</li>';
			}
			//add pagination div if not makingslideshow and set to pagenmus
			if(!$makingslideshow && $btnorpagenums=='pagenums'){
				
				//find the number of last slide and create correct html

					//add first number
					$resultsecho = $resultsecho. '
						<div id="wppro_review_pagination'.$formid.'" '.$hidepagination.' class="wppro_pagination" '.$reviewssameheight.' data-notinstring="'.$notinstring.'" data-nrows="'.$currentform[0]->display_num_rows.'" data-ismasonry="'.$currentform[0]->display_masonry.'" data-perrow="'.$currentform[0]->display_num.'" data-cpostid="'.$cpostid.'" data-shortcodepageid="'.$shortcodepageid.'" data-shortcodelang="'.$shortcodelang.'" data-shortcodetag="'.$shortcodetag.'" data-tid="'.$formid.'" data-lastslidenum="'.$lastslidenumb.'" data-totalreviewsindb="'.$totalcount.'">
						<ul class="wppro_page_numbers_ul">
							<li><span class="brnprevclass wppro_page_numbers current">1</span></li>';
					if($lastslidenumb<4){
						for ($x = 2; $x <= $lastslidenumb; $x++) {
							$resultsecho = $resultsecho. '<li><span class="brnprevclass wppro_page_numbers">'.$x.'</span></li>';
						} 
					} else if ($lastslidenumb>3){
						$resultsecho = $resultsecho. '
							<li><span class="brnprevclass wppro_page_numbers">2</span></li>
							<li><span class="brnprevclass wppro_page_dots">…</span></li>
							<li><span class="brnprevclass wppro_page_numbers">'.$lastslidenumb.'</span></li>
							<li><span class="brnprevclass wppro_page_numbers next-button">></span></li>
						';
					}
					$resultsecho = $resultsecho. '</ul><img src="'.$loading_img_url.'" class="wprppagination_loading_image '.$imageclassslideshow.'" style="display:none;"></div>';
				
			}
		}
		$results['jslastslide']=$jslastslide;
		$results['echothis']=$resultsecho;
		
		return $results;
	}
	
	public function get_post_excerpt_by_id_template( $post_id ) {
		$temppost = get_post( $post_id );
		$content = strip_shortcodes( $temppost->post_content );
		$the_excerpt = wp_trim_words($content);
		$the_excerpt = esc_attr( $the_excerpt );
		return $the_excerpt;
	}
	
	public function wppro_getgooglesnippet($currentform,$totalcount,$totalavg,$totalreviews=''){
		$google_snippet_add ='';
		$google_snippet_type ='';
		$google_snippet_name ='';
		$google_snippet_desc ='';
		$google_snippet_business_image ='';
		$google_snippet_more_array_encode ='';
		$tempsnippethtml ='';
		
		//snippet
		$google_snippet_add =$currentform[0]->google_snippet_add;
		$google_snippet_type =$currentform[0]->google_snippet_type;
		$google_snippet_name =stripslashes($currentform[0]->google_snippet_name);
		$google_snippet_desc =stripslashes($currentform[0]->google_snippet_desc);
		$google_snippet_business_image =$currentform[0]->google_snippet_business_image;
		$google_snippet_more_array_encode =$currentform[0]->google_snippet_more;
		
		//turn on google snippet if set to yes
		if($google_snippet_add=="yes" && $totalavg>0){
				
			//default name to post/page title
			if($google_snippet_name==''){
				$google_snippet_name = esc_html( get_the_title() );
			}
			
			if($google_snippet_desc==''){
				if(get_the_ID()){
					$post_id = get_the_ID();
					$google_snippet_desc = $this->get_post_excerpt_by_id_template( $post_id );
				}
			}

			if($google_snippet_business_image==''){
				$google_snippet_business_image = get_the_post_thumbnail_url( get_the_ID(), 'medium' );
			}

			
			$google_misc_array = json_decode($google_snippet_more_array_encode, true);
			if(!is_array($google_misc_array) && $google_snippet_type!='Product'){
				$google_misc_array=array();
				$google_misc_array['telephone']="";
				$google_misc_array['priceRange']="";
				$google_misc_array['streetAddress']="";
				$google_misc_array['addressLocality']="";
				$google_misc_array['addressRegion']="";
				$google_misc_array['postalCode']="";
			}
			if($google_misc_array['streetAddress']!='' || $google_misc_array['addressLocality']!='' || $google_misc_array['addressRegion']!='' || $google_misc_array['postalCode']!=''){
				$gsaddress = ', "address": {"@type": "PostalAddress","addressLocality": "'.$google_misc_array['addressLocality'].'","addressRegion": "'.stripslashes($google_misc_array['addressRegion']).'","postalCode": "'.$google_misc_array['postalCode'].'","streetAddress": "'.stripslashes($google_misc_array['streetAddress']).'"}';
			} else {
				$gsaddress = '';
			}
			if($google_misc_array['telephone']!=''){
				$gsphone = ', "telephone": "'.$google_misc_array['telephone'].'"';
			} else {
				$gsphone = '';
			}
			if($google_misc_array['priceRange']!=''){
				$gsprice = ', "priceRange": "'.$google_misc_array['priceRange'].'"';
			} else {
				$gsprice = '';
			}
			if(isset($google_misc_array['schemaid']) && $google_misc_array['schemaid']!=''){
				$schemaid = $google_misc_array['schemaid'];
				$schemaidtext = '"@id": "'.$schemaid .'",';
			} else {
				$schemaidtext = '';
			}
			
			//add product stuff here if set
			$prodmoretxt='';
			if($google_snippet_type=='Product'){
				if(!isset($google_misc_array['brand'])){
					$google_misc_array['brand']="";
					$google_misc_array['price']="";
					$google_misc_array['priceCurrency']="";
					$google_misc_array['sku']="";
					$google_misc_array['giname']="";
					$google_misc_array['gival']="";
					$google_misc_array['url']="";
					$google_misc_array['availability']="";
					$google_misc_array['priceValidUntil']="";
				}
				if($google_misc_array['brand']!=''){
					$prodmoretxt=', "brand": {"@type": "Brand","name": "'.$google_misc_array['brand'].'"}';
				}
				if($google_misc_array['price']!='' || $google_misc_array['priceCurrency']!=''){
					$prodmoretxt=$prodmoretxt.', 
					  "offers": {
						"@type": "Offer",
						"url": "'.$google_misc_array['url'].'",
						"priceCurrency": "'.$google_misc_array['priceCurrency'].'",
						"price": "'.$google_misc_array['price'].'",
						"availability": "'.$google_misc_array['availability'].'",
						"priceValidUntil": "'.$google_misc_array['priceValidUntil'].'"
						}';
				}
				if($google_misc_array['sku']!=''){
					$prodmoretxt=$prodmoretxt.', "sku": "'.$google_misc_array['sku'].'"';
				}
				if($google_misc_array['giname']!='' && $google_misc_array['gival']!=''){
					$prodmoretxt=$prodmoretxt.', "'.$google_misc_array['giname'].'": "'.$google_misc_array['gival'].'"';
				}

			}
			
			//add the individual review markup if set
			$irmtext = '';
			$reviewmarkuparray = Array();
			if(isset($google_misc_array['irm']) && $google_misc_array['irm']=='yes' && $totalreviews!=''){
				$filtertype = $google_misc_array['irm_type'];
				for ($x = 0; $x < count($totalreviews); $x++) {
					if(isset($totalreviews[$x]->type)){
						if($filtertype=='Manual' && $totalreviews[$x]->type=='Manual'){
							$reviewmarkuparray[$x] = $totalreviews[$x];
						}
						if($filtertype=='Submitted' && $totalreviews[$x]->type=='Submitted'){
							$reviewmarkuparray[$x] = $totalreviews[$x];
						}
						if($filtertype=='ManualSubmitted' && ($totalreviews[$x]->type=='Submitted' || $totalreviews[$x]->type=='Manual')){
							$reviewmarkuparray[$x] = $totalreviews[$x];
						}
						if($filtertype=='all'){
							$reviewmarkuparray[$x] = $totalreviews[$x];
						}
					}
				}
			}
			if(count($reviewmarkuparray)>0){
				$reviewmarkuparray = array_values($reviewmarkuparray);
				$irmtext = ', "review": [';
				for ($x = 0; $x < count($reviewmarkuparray); $x++) {
					$ratingvalue = 5;
					if($reviewmarkuparray[$x]->rating < 1){
						if($reviewmarkuparray[$x]->recommendation_type=='negative'){
							$ratingvalue = 2;
						}
					} else {
						$ratingvalue = $reviewmarkuparray[$x]->rating;
					}
					
					if($x >0){
						$irmtext = $irmtext .',';
					}
					$reviewtext = stripslashes($reviewmarkuparray[$x]->review_text);
					$reviewtext = str_replace( '"', '\"', $reviewtext);
					$reviewtext = str_replace(array("\r", "\n", "<br>", "</br>"), '', $reviewtext);
					$irmtext = $irmtext . '{
							"@type": "Review",
							"reviewRating": {
							  "@type": "Rating",
							  "ratingValue": "'.$ratingvalue.'"
							},
							"author": {
							  "@type": "Person",
							  "name": "'.$reviewmarkuparray[$x]->reviewer_name.'"
							},
							"reviewBody": "'.$reviewtext.'"
						  }';
				}
				$irmtext = $irmtext .']';
			}
			
			$ratingcounthtml = "ratingCount";
			if(isset($google_misc_array['tvr']) && $google_misc_array['tvr']!=''){
				if($google_misc_array['tvr']=='reviews'){
					$ratingcounthtml = "reviewCount";
				}
			}
			
			$tempsnippethtml = '<script type="application/ld+json">{'.$schemaidtext.'"@context": "http://schema.org/","@type": "'.$google_snippet_type.'","name": "'.stripslashes(sanitize_text_field($google_snippet_name)).'","description": "'.sanitize_text_field($google_snippet_desc).'","aggregateRating": {"@type": "AggregateRating","ratingValue": "'.$totalavg.'","'.$ratingcounthtml.'": "'.$totalcount.'","bestRating": "5","worstRating": "1"},"image": "'.esc_url($google_snippet_business_image).'"'.$gsaddress.$gsphone.$gsprice.$prodmoretxt.$irmtext.'}</script>';

		}
		
		return $tempsnippethtml;
	}
	
	//get style code html
	public function wppro_gettemplatestylecode($currentform,$iswidget,$template_misc_array){
		
		//add styles from template misc here
		$templatestylecode = '';
		$formid = intval($currentform[0]->id);
		
		if(!isset($template_misc_array['header_banner'])){
			$template_misc_array['header_banner']='';
		}
		if(!isset($template_misc_array['bnrevusbtn'])){
			$template_misc_array['bnrevusbtn']='';
		}
		
		//print_r($template_misc_array);
		
		//add banner style if needed. .wprev_banner_outer
		$prestyle ="";
		if($template_misc_array['header_banner']=='b1'){
			
			if(isset($template_misc_array['bbgcolor']) && $template_misc_array['bbgcolor']!=''){
				$bgcolor = sanitize_text_field($template_misc_array['bbgcolor']);
				$prestyle = $prestyle .".wprev_banner_outer {background: ".$bgcolor.";}";
			}
			if(isset($template_misc_array['btxtcolor']) && $template_misc_array['btxtcolor']!=''){
				$btxtcolor = sanitize_text_field($template_misc_array['btxtcolor']);
				$prestyle = $prestyle . ".wprev_banner_outer {color: ".$btxtcolor.";}";
			}
			if(isset($template_misc_array['bbordercolor']) && $template_misc_array['bbordercolor']!=''){
				$bbordercolor = sanitize_text_field($template_misc_array['bbordercolor']);
				$prestyle = $prestyle . ".wprev_banner_outer {border: 1px solid ".$bbordercolor.";}";
			}
			if(isset($template_misc_array['bncradius']) && $template_misc_array['bncradius']!=''){
				$bncradius = sanitize_text_field($template_misc_array['bncradius']);
				$prestyle = $prestyle . ".wprev_banner_outer {border-radius: ".$bncradius."px;}";
				//also do button.
				$prestyle = $prestyle . ".wprevb1 .wprevpro_bnrevus_btn {border-radius: ".$bncradius."px;}";
			}
			if(isset($template_misc_array['bndropshadow']) && $template_misc_array['bndropshadow']=='yes'){
				$prestyle = $prestyle . ".wprev_banner_outer {box-shadow: 0 0 10px 2px rgb(0 0 0 / 14%);}";
			}
			if(isset($template_misc_array['bnhidesource']) && $template_misc_array['bnhidesource']=='yes'){
				$prestyle = $prestyle . ".wprev_banner_top {display: none;}";
			}
		}
		//add review us button style, banner independant. currently only have one banner
		if($template_misc_array['header_banner']=='b1' && $template_misc_array['bnrevusbtn']=="yes"){
			if(isset($template_misc_array['revus_bcolor']) && $template_misc_array['revus_bcolor']!=''){
				$revus_bcolor = sanitize_text_field($template_misc_array['revus_bcolor']);
				$prestyle = $prestyle . ".wprevpro_bnrevus_btn {border-color: ".$revus_bcolor.";}";
			}
			if(isset($template_misc_array['revus_bgcolor']) && $template_misc_array['revus_bgcolor']!=''){
				$revus_bgcolor = sanitize_text_field($template_misc_array['revus_bgcolor']);
				$prestyle = $prestyle . ".wprevpro_bnrevus_btn {background-color: ".$revus_bgcolor.";}";
			}
			if(isset($template_misc_array['revus_fontcolor']) && $template_misc_array['revus_fontcolor']!=''){
				$revus_fontcolor = sanitize_text_field($template_misc_array['revus_fontcolor']);
				$prestyle = $prestyle . ".wprevpro_bnrevus_btn {color: ".$revus_fontcolor.";}";
			}

		}
		if($prestyle!=''){
			$templatestylecode = $templatestylecode . "<style>".$prestyle."</style>";
		}
		
		
		if(is_array($template_misc_array)){
			$misc_style ="";
			
			//hide or show based on screen size if set.
			if(isset($template_misc_array['screensize'])){
				if($template_misc_array['screensize']=='desk'){	//show desktop only
					$misc_style = $misc_style . '@media only screen and (max-width: 600px) {
						div#wprev-slider-'.$formid.', #wprs_nav_'.$formid.', #wprev_header_txt_id_'.$formid.', #wprev_search_sort_bar_id_'.$formid.' {display: none !important;}
						}';
				}
				if($template_misc_array['screensize']=='mobile'){	//mobile only
					$misc_style = $misc_style . '@media only screen and (min-width: 600px) {
						div#wprev-slider-'.$formid.', #wprs_nav_'.$formid.', #wprev_header_txt_id_'.$formid.', #wprev_search_sort_bar_id_'.$formid.' {display: none !important;}
						}';
					
				}
			}
			//if we are endless scroll then hide the no more reviews text. $template_misc_array['load_more_porb']=='scroll'
			if(isset($template_misc_array['load_more_porb']) && $template_misc_array['load_more_porb']=='scroll'){
					$misc_style = $misc_style . 'div#wprev-slider-'.$formid.' .wprev_norevsfound {display: none;}';
			}
			
			//template margins desktop
			if(isset($template_misc_array['template_margin_tb'])){
				if($template_misc_array['template_margin_tb']>0){
					$misc_style = $misc_style .'@media screen and (min-width: 600px) {';
					$misc_style = $misc_style .'div#wprev-slider-'.$formid.'{margin-top: '.intval($template_misc_array['template_margin_tb']).'px;}';
					$misc_style = $misc_style .'div#wprev-slider-'.$formid.'{margin-bottom: '.intval($template_misc_array['template_margin_tb']).'px;}';
					$misc_style = $misc_style .'}';
				}
			}
			if(isset($template_misc_array['template_margin_lr'])){
				if($template_misc_array['template_margin_lr']!=0){
					$tempssbm = intval($template_misc_array['template_margin_lr']);
					
					$misc_style = $misc_style .'@media screen and (min-width: 600px) {';
					
					//for unslider
					if($currentform[0]->createslider == "yes"){
					$misc_style = $misc_style .'div.wprs_unslider {margin-left: '.$tempssbm.'px;}';
					$misc_style = $misc_style .'div.wprs_unslider {margin-right: '.$tempssbm.'px;}';
					}
					
					//for slick slider
					if($currentform[0]->createslider == "sli"){
					$misc_style = $misc_style .'div#wprev-slider-'.$formid.' {margin-left: '.$tempssbm.'px;}';
					$misc_style = $misc_style .'div#wprev-slider-'.$formid.' {margin-right: '.$tempssbm.'px;}';
					}
					
					if($currentform[0]->createslider == "no"){
					$misc_style = $misc_style .'#wprev-slider-'.$formid.' {margin-left: '.$tempssbm.'px;margin-right: '.intval($template_misc_array['template_margin_lr']).'px;}';
					}
					
					$tempssbmlft = $tempssbm+15;
					
					//for search bar
					$misc_style = $misc_style .'div#wprev_search_sort_bar_id_'.$formid.' {margin-left: '.$tempssbmlft.'px;margin-right: '.$tempssbm.'px;}';
					$tempssbnnlft = $tempssbm+21;
					$misc_style = $misc_style .'div#wprev_banner_id_'.$formid.' {margin-left: '.$tempssbnnlft.'px;margin-right: '.$tempssbnnlft.'px;}';							   
					$misc_style = $misc_style .'}';
				}
			}
			//template margins mobile
			if(isset($template_misc_array['template_margin_tb_m'])){
				if($template_misc_array['template_margin_tb_m']>0){
					$misc_style = $misc_style .'@media only screen and (max-width: 600px) {';
					$misc_style = $misc_style .'div#wprev-slider-'.$formid.'{margin-top: '.intval($template_misc_array['template_margin_tb_m']).'px;}';
					$misc_style = $misc_style .'div#wprev-slider-'.$formid.'{margin-bottom: '.intval($template_misc_array['template_margin_tb_m']).'px;}';
					$misc_style = $misc_style .'}';
				}
			}
			if(isset($template_misc_array['template_margin_lr_m'])){
				if($template_misc_array['template_margin_lr_m']>0){
					$misc_style = $misc_style .'@media only screen and (max-width: 600px) {';
					if($currentform[0]->createslider == "yes"){
						$misc_style = $misc_style .'div.wprs_unslider {margin-left: '.intval($template_misc_array['template_margin_lr_m']).'px;}';
						$misc_style = $misc_style .'div.wprs_unslider {margin-right: '.intval($template_misc_array['template_margin_lr_m']).'px;}';
					}
					if($currentform[0]->createslider == "sli"){
					$misc_style = $misc_style .'div#wprev-slider-'.$formid.'.wprev-slick-slider {margin-left: '.intval($template_misc_array['template_margin_lr_m']).'px;}';
					$misc_style = $misc_style .'div#wprev-slider-'.$formid.'.wprev-slick-slider {margin-right: '.intval($template_misc_array['template_margin_lr_m']).'px;}';
					}
					if($currentform[0]->createslider == "no"){
					$misc_style = $misc_style .'#wprev-slider-'.$formid.' {margin-left: '.intval($template_misc_array['template_margin_lr_m']).'px;margin-right: '.intval($template_misc_array['template_margin_lr_m']).'px;}';
					}

					$misc_style = $misc_style .'}';
				}
			}
			
			//hide stars and/or date
			if(isset($template_misc_array['showstars']) && $template_misc_array['showstars']=="no"){
				$misc_style = $misc_style . '#wprev-slider-'.$formid.$iswidget.' .wprevpro_star_imgs_T'.$currentform[0]->style.$iswidget.' {display: none;}';
			}
			//if(isset($template_misc_array['showdate']) && $template_misc_array['showdate']=="no"){
			//	$misc_style = $misc_style . '#wprev-slider-'.$formid.$iswidget.' .wprev_showdate_T'.$currentform[0]->style.$iswidget.' {display: none;}';
			//}
			//in case not set
			if(!isset($template_misc_array['starcolor'])){
				$template_misc_array['starcolor']='#FDD314';
			}
			
			$misc_style = $misc_style . '#wprev-slider-'.$formid.$iswidget.' .wprevpro_star_imgs{color: '.$template_misc_array['starcolor'].';}';
			$misc_style = $misc_style . '#wprev-slider-'.$formid.$iswidget.' .wprsp-star{color: '.$template_misc_array['starcolor'].';}';
			//color for svgs
			$misc_style = $misc_style . '#wprev-slider-'.$formid.$iswidget.' .wprevpro_star_imgs span.svgicons {background: '.$template_misc_array['starcolor'].';}';
			
			if(isset($template_misc_array['bradius'])){
				$misc_style = $misc_style . '#wprev-slider-'.$formid.$iswidget.' .wprev_preview_bradius_T'.$currentform[0]->style.$iswidget.' {border-radius: '.$template_misc_array['bradius'].'px;}';
			}
			
			if(isset($template_misc_array['bgcolor1']) && $template_misc_array['bgcolor1']!=''){
			$misc_style = $misc_style . '#wprev-slider-'.$formid.$iswidget.' .wprev_preview_bg1_T'.$currentform[0]->style.$iswidget.' {background:'.$template_misc_array['bgcolor1'].';}';
			}
			if(isset($template_misc_array['bgcolor2']) && $template_misc_array['bgcolor2']!=''){
			$misc_style = $misc_style . '#wprev-slider-'.$formid.$iswidget.' .wprev_preview_bg2_T'.$currentform[0]->style.$iswidget.' {background:'.$template_misc_array['bgcolor2'].';}';
			}
			if(isset($template_misc_array['tcolor1']) && $template_misc_array['tcolor1']!=''){
			$misc_style = $misc_style . '#wprev-slider-'.$formid.$iswidget.' .wprev_preview_tcolor1_T'.$currentform[0]->style.$iswidget.' {color:'.$template_misc_array['tcolor1'].';}';
			}
			if(isset($template_misc_array['tcolor2']) && $template_misc_array['tcolor2']!=''){
			$misc_style = $misc_style . '#wprev-slider-'.$formid.$iswidget.' .wprev_preview_tcolor2_T'.$currentform[0]->style.$iswidget.' {color:'.$template_misc_array['tcolor2'].';}';
			}
			
			if(isset($template_misc_array['bcolor']) && $template_misc_array['bcolor']!=''){
			$misc_style = $misc_style . '#wprev-slider-'.$formid.$iswidget.' .wprev_preview_bradius_T'.$currentform[0]->style.$iswidget.' {border-color:'.$template_misc_array['bcolor'].';}';
			}
			
			if(isset($template_misc_array['tfont1']) && $template_misc_array['tfont1']>0){
				$misc_style = $misc_style . '#wprev-slider-'.$formid.$iswidget.' .wprev_preview_tcolor1_T'.$currentform[0]->style.$iswidget.' {font-size:'.$template_misc_array['tfont1'].'px;line-height: normal;}';
			}
			if(isset($template_misc_array['tfont2']) && $template_misc_array['tfont2']>0){
				$misc_style = $misc_style . '#wprev-slider-'.$formid.$iswidget.' .wprev_preview_tcolor2_T'.$currentform[0]->style.$iswidget.' {font-size:'.$template_misc_array['tfont2'].'px;line-height: normal;}';
			}

			//is dropshadow checked.
			if(isset($template_misc_array['dropshadow'])){
				// something when checked
				$misc_style = $misc_style . '#wprev-slider-'.$formid.$iswidget.' .wprev_preview_bradius_T'.$currentform[0]->style.$iswidget.' {box-shadow:0 0 10px 2px rgb(0 0 0 / 14%);}';
			}
			//is raise on mouseover checked.
			if(isset($template_misc_array['raisemouse'])){
				// something when checked
				$misc_style = $misc_style . '#wprev-slider-'.$formid.$iswidget.' .outerrevdiv {transition: transform ease 400ms;} #wprev-slider-'.$formid.$iswidget.' .outerrevdiv:hover{transform: translate(0, -4px);} ';
			}
			//is zoom on mouseover checked.
			if(isset($template_misc_array['zoommouse'])){
				// something when checked
				$misc_style = $misc_style . '#wprev-slider-'.$formid.$iswidget.' .outerrevdiv {transition: transform ease 400ms;transform-origin: center;} #wprev-slider-'.$formid.$iswidget.' .outerrevdiv:hover{transform: scale(1.1);} ';
			}
			
			//style specific mods 	div > p
			if($currentform[0]->style=="1"){
				if(isset($template_misc_array['bgcolor1'])){
				$misc_style = $misc_style . '#wprev-slider-'.$formid.$iswidget.' .wprev_preview_bg1_T'.$currentform[0]->style.$iswidget.'::after{ border-top: 30px solid '.$template_misc_array['bgcolor1'].'; }';
				}
				if(isset($template_misc_array['bcolor']) && $template_misc_array['bcolor']!=''){
					$misc_style = $misc_style . '#wprev-slider-'.$formid.$iswidget.' .wprevpro_t1_DIV_2:after {filter: drop-shadow(1px 1px 0px '.$template_misc_array['bcolor'].');}';
				}
			}
			if($currentform[0]->style=="2"){
				if(isset($template_misc_array['bgcolor2'])){
				$misc_style = $misc_style . '#wprev-slider-'.$formid.$iswidget.' .wprev_preview_bg1_T'.$currentform[0]->style.$iswidget.' {border-bottom:3px solid '.$template_misc_array['bgcolor2'].'}';
				}
				if(isset($template_misc_array['bcolor']) && $template_misc_array['bcolor']!='' && strpos($template_misc_array['bcolor'], ',0)') == false ){
				$misc_style = $misc_style . '#wprev-slider-'.$formid.$iswidget.' .wprev_preview_bg1_T'.$currentform[0]->style.$iswidget.' {border:1px solid '.$template_misc_array['bcolor'].'}';
				}
				
			}
			if($currentform[0]->style=="3"){
				if(isset($template_misc_array['tcolor3'])){
				$misc_style = $misc_style . '#wprev-slider-'.$formid.$iswidget.' .wprev_preview_tcolor3_T'.$currentform[0]->style.$iswidget.' {text-shadow:'.$template_misc_array['tcolor3'].' 1px 1px 0px;}';
				}
				if(isset($template_misc_array['tfont2']) && $template_misc_array['tfont2']>0){
				$misc_style = $misc_style . '#wprev-slider-'.$formid.$iswidget.' .wprev_preview_tcolor3_T'.$currentform[0]->style.$iswidget.' {font-size:'.$template_misc_array['tfont2'].'px;line-height: normal;}';
				}
			}
			if($currentform[0]->style=="5"){
				if(isset($template_misc_array['bgcolor2'])){
				$misc_style = $misc_style . '#wprev-slider-'.$formid.$iswidget.' .wprev_preview_bg1_T'.$currentform[0]->style.$iswidget.' {border-bottom:3px solid '.$template_misc_array['bgcolor2'].'}';
				}
				if(isset($template_misc_array['bcolor']) && $template_misc_array['bcolor']!='' && strpos($template_misc_array['bcolor'], ',0)') == false ){
				$misc_style = $misc_style . '#wprev-slider-'.$formid.$iswidget.' .wprev_preview_bg1_T'.$currentform[0]->style.$iswidget.' {border:1px solid '.$template_misc_array['bcolor'].'}';
				}
			}

			if($currentform[0]->style=="4" || $currentform[0]->style=="12"){
				if(isset($template_misc_array['tcolor3'])){
				$misc_style = $misc_style . '#wprev-slider-'.$formid.$iswidget.' .wprev_preview_tcolor3_T'.$currentform[0]->style.$iswidget.' {color:'.$template_misc_array['tcolor3'].';}';
				}
				if(isset($template_misc_array['bcolor']) && $template_misc_array['bcolor']!='' && strpos($template_misc_array['bcolor'], ',0)') == false ){
				$misc_style = $misc_style . '#wprev-slider-'.$formid.$iswidget.' .wprev_preview_bg1_T'.$currentform[0]->style.$iswidget.' {border:1px solid '.$template_misc_array['bcolor'].'}';
				}
			}
			/*
			if($currentform[0]->style=="6"){
				if(isset($template_misc_array['bgcolor2'])){
				$misc_style = $misc_style . '#wprev-slider-'.$formid.$iswidget.' .wprev_preview_bg1_T'.$currentform[0]->style.$iswidget.' {border:1px solid '.$template_misc_array['bgcolor2'].'}';
				}
			}
			
			if($currentform[0]->style=="7" || $currentform[0]->style=="8" || $currentform[0]->style=="11"){
				if(isset($template_misc_array['bgcolor2'])){
				$misc_style = $misc_style . '#wprev-slider-'.$formid.$iswidget.' .wpproslider_t'.$currentform[0]->style.$iswidget.'_DIV_2 {border:1px solid '.$template_misc_array['bgcolor2'].'}';
				}
			}
			*/
			
			if(isset($template_misc_array['readmcolor']) && $template_misc_array['readmcolor']!=''){
			$misc_style = $misc_style . '#wprev-slider-'.$formid.$iswidget.' .wprs_rd_more,.wprs_rd_less,.wprevpro_btn_show_rdpop{color:'.$template_misc_array['readmcolor'].';}';
			}
			if(isset($template_misc_array['avatarsize']) && $template_misc_array['avatarsize']!=''){
				$misc_style = $misc_style . '#wprev-slider-'.$formid.$iswidget.' img.wprevpro_avatarimg {width: '.$template_misc_array['avatarsize'].'px; height: '.$template_misc_array['avatarsize'].'px;}';
			}
			if(isset($template_misc_array['starsize']) && $template_misc_array['starsize']>0 ){
				$misc_style = $misc_style . '#wprev-slider-'.$formid.$iswidget.' .wprevpro_star_imgs span.svgicons {width: '.$template_misc_array['starsize'].'px; height: '.$template_misc_array['starsize'].'px;}';
			}
			if(isset($template_misc_array['iconsize']) && $template_misc_array['iconsize']>0){
				$misc_style = $misc_style . '#wprev-slider-'.$formid.$iswidget.' .wprevsiteicon {height: '.$template_misc_array['iconsize'].'px;}';
			}
			//add arrow color if set.
			if(isset($template_misc_array['sliderarrowcolor']) && $template_misc_array['sliderarrowcolor']!=""){
				if($currentform[0]->createslider == "sli"){
					$misc_style = $misc_style . '#wprev-slider-'.$formid.$iswidget.' .slickwprev-next:before, #wprev-slider-'.$formid.$iswidget.' .slickwprev-prev:before {color: '.htmlentities($template_misc_array['sliderarrowcolor']).';}';
				} else if($currentform[0]->createslider == "yes"){
					$misc_style = $misc_style . '.wprs_nav_arrow_'.$formid.'{background-color: '.htmlentities($template_misc_array['sliderarrowcolor']).';opacity: 1;}';
					
				}
			}
			//add dot color if set.
			if(isset($template_misc_array['sliderdotcolor']) && $template_misc_array['sliderdotcolor']!=""){
				if($currentform[0]->createslider == "sli"){
					$misc_style = $misc_style . '#wprev-slider-'.$formid.$iswidget.' .slickwprev-dots li.slickwprev-active button:before {color: '.htmlentities($template_misc_array['sliderdotcolor']).';} #wprev-slider-'.$formid.$iswidget.' .slickwprev-dots li button:before {color: '.htmlentities($template_misc_array['sliderdotcolor']).';}';
				} else if($currentform[0]->createslider == "yes"){
					$misc_style = $misc_style . '#wprs_nav_'.$formid.' ol li.wprs_unslider-active {background: '.htmlentities($template_misc_array['sliderdotcolor']).';} #wprs_nav_'.$formid.' ol li {border-color: '.htmlentities($template_misc_array['sliderdotcolor']).';}';
					
				}
			}
			
			
			//------------------------
			//echo "<style>".$misc_style."</style>";
			$templatestylecode = $templatestylecode . "<style>".$misc_style."</style>";
		}
		
		//check if we should be hiding navigation dots, fix for load more reading them
		if($currentform[0]->createslider == "yes"){
			if($currentform[0]->sliderdots!="" && $currentform[0]->sliderdots=='no'){
				$sliderdotscss = '#wprs_nav_'.$formid.$iswidget.' {display:none;}';
				$templatestylecode = $templatestylecode . "<style>".$sliderdotscss."</style>";
			} else if($currentform[0]->sliderdots!="" && $currentform[0]->sliderdots=='des'){
				$sliderdotscss = '@media only screen and (max-width: 600px) {#wprs_nav_'.$formid.' {display:none;}}';
				$templatestylecode = $templatestylecode . "<style>".$sliderdotscss."</style>";
			}
		}
		
		//check if we are hiding navigation dots for slick slider on mobile only
		if($currentform[0]->createslider == "sli"){
			if($currentform[0]->sliderdots!="" && $currentform[0]->sliderdots=='des'){
				$slisliderdotscss = '@media only screen and (max-width: 600px) {#wprev-slider-'.$formid.' .slickwprev-dots {display:none!important;}}';
				$templatestylecode = $templatestylecode . "<style>".$slisliderdotscss."</style>";
			}			
		}
		
		//check if we are hiding arrows on mobile only
		if($currentform[0]->sliderarrows=='des'){
			if($currentform[0]->createslider == "sli"){
				$arrowhidecss = '@media only screen and (max-width: 600px) { 
				#wprev-slider-'.$formid.' .slickwprev-arrow, #wprev-slider-'.$formid.'_widget .slickwprev-arrow {display:none !important;}}';
			} else if($currentform[0]->createslider == "yes"){
				$arrowhidecss = '@media only screen and (max-width: 600px) { .wprs_nav_arrow_'.$formid.$iswidget.' {display:none;}}';
			}
			if(isset($arrowhidecss)){
			$templatestylecode = $templatestylecode . "<style>".$arrowhidecss."</style>";
			}
		}
		
		//print out user style added
		//echo "<style>".$currentform[0]->template_css."</style>";
		if($currentform[0]->template_css!=''){
			$templatestylecode = $templatestylecode . "<style>".stripslashes(sanitize_text_field($currentform[0]->template_css))."</style>";
		}
		
		//add pagination style if set.
		$paginationstyle = '';
		if(isset($template_misc_array['ps_bw']) && $template_misc_array['ps_bw']!=''){
			$paginationstyle = $paginationstyle . ".brnprevclass{border-width:".intval($template_misc_array['ps_bw'])."px !important}";
		}
		if(isset($template_misc_array['ps_br']) && $template_misc_array['ps_br']!=''){
			$paginationstyle = $paginationstyle . ".brnprevclass{border-radius:".intval($template_misc_array['ps_br'])."px !important}";
		}
		if(isset($template_misc_array['ps_bcolor']) && $template_misc_array['ps_bcolor']!=''){
			$paginationstyle = $paginationstyle . ".brnprevclass{border-color:".$template_misc_array['ps_bcolor']." !important}";
		}
		if(isset($template_misc_array['ps_bgcolor']) && $template_misc_array['ps_bgcolor']!=''){
			$paginationstyle = $paginationstyle . ".brnprevclass{background-color:".$template_misc_array['ps_bgcolor']." !important}";
			$paginationstyle = $paginationstyle . ".brnprevclass:hover{background-color:#00000066 !important}";
			$paginationstyle = $paginationstyle . ".brnprevclass.current{background-color:#00000066 !important}";
			
		}
		if(isset($template_misc_array['ps_fontcolor']) && $template_misc_array['ps_fontcolor']!=''){
			$paginationstyle = $paginationstyle . ".brnprevclass{color:".$template_misc_array['ps_fontcolor']." !important}";
		}
		if(isset($template_misc_array['ps_fsize']) && $template_misc_array['ps_fsize']!=''){
			$paginationstyle = $paginationstyle . ".brnprevclass{font-size:".intval($template_misc_array['ps_fsize'])."px !important}";
		}
		if(isset($template_misc_array['ps_paddingt']) && $template_misc_array['ps_paddingt']!=''){
			$paginationstyle = $paginationstyle . ".brnprevclass{padding-top:".intval($template_misc_array['ps_paddingt'])."px !important}";
		}
		if(isset($template_misc_array['ps_paddingb']) && $template_misc_array['ps_paddingb']!=''){
			$paginationstyle = $paginationstyle . ".brnprevclass{padding-bottom:".intval($template_misc_array['ps_paddingb'])."px !important}";
		}
		if(isset($template_misc_array['ps_paddingl']) && $template_misc_array['ps_paddingl']!=''){
			$paginationstyle = $paginationstyle . ".brnprevclass{padding-left:".intval($template_misc_array['ps_paddingl'])."px !important}";
		}
		if(isset($template_misc_array['ps_paddingr']) && $template_misc_array['ps_paddingr']!=''){
			$paginationstyle = $paginationstyle . ".brnprevclass{padding-right:".intval($template_misc_array['ps_paddingr'])."px !important}";
		}
		if(isset($template_misc_array['ps_margint']) && $template_misc_array['ps_margint']!=''){
			$paginationstyle = $paginationstyle . ".brnprevclass{margin-top:".intval($template_misc_array['ps_margint'])."px !important}";
		}
		if(isset($template_misc_array['ps_marginb']) && $template_misc_array['ps_marginb']!=''){
			$paginationstyle = $paginationstyle . ".brnprevclass{margin-bottom:".intval($template_misc_array['ps_marginb'])."px !important}";
		}
		if(isset($template_misc_array['ps_marginl']) && $template_misc_array['ps_marginl']!=''){
			$paginationstyle = $paginationstyle . ".brnprevclass{margin-left:".intval($template_misc_array['ps_marginl'])."px !important}";
		}
		if(isset($template_misc_array['ps_marginr']) && $template_misc_array['ps_marginr']!=''){
			$paginationstyle = $paginationstyle . ".brnprevclass{margin-right:".intval($template_misc_array['ps_marginr'])."px !important}";
		}
		if($paginationstyle!=''){
			$paginationstyle = "<style>".$paginationstyle."</style>";
			$templatestylecode = $templatestylecode . $paginationstyle;
		}
		
		//remove line breaks and tabs
		$templatestylecode = str_replace(array("\n", "\t", "\r"), '', $templatestylecode);
		//echo $templatestylecode;
		
		//add masonry style css if this is a slideshow only, doing this with js if we are in grid
		$masonrystyle = '';
		//if(	$currentform[0]->display_masonry=="yes" && $currentform[0]->createslider == "yes"){
		if(	$currentform[0]->display_masonry=="yes" && $currentform[0]->createslider == "yes"){
					$tempdisplaynum['xs']= $currentform[0]->display_num-3;
					if($tempdisplaynum['xs']<1){
						$tempdisplaynum['xs']=1;
					}
					$tempdisplaynum['s']= $currentform[0]->display_num-2;
					if($tempdisplaynum['s']<1){
						$tempdisplaynum['s']=1;
					}
					$tempdisplaynum['m']= $currentform[0]->display_num-1;
					if($tempdisplaynum['m']<1){
						$tempdisplaynum['m']=1;
					}
					$tempdisplaynum['l']= $currentform[0]->display_num;
					if($tempdisplaynum['l']<1){
						$tempdisplaynum['l']=1;
					}
			$misc_masonry_style = '.wprs_masonry {margin: 0 5px 0 5px;padding: 0;}.wprs_masonry_item {display: grid;width: 100%;padding-top: 5px;margin-bottom: 10px;margin-top: 0px;break-inside: avoid;-webkit-column-break-inside: avoid;page-break-inside: avoid;}@media only screen and (min-width: 400px) {.wprs_masonry {-moz-column-count: '.$tempdisplaynum['xs'].';-webkit-column-count: '.$tempdisplaynum['xs'].';	column-count: '.$tempdisplaynum['xs'].';}}@media only screen and (min-width: 700px) {.wprs_masonry {-moz-column-count: '.$tempdisplaynum['s'].';-webkit-column-count: '.$tempdisplaynum['s'].';column-count: '.$tempdisplaynum['s'].';}}@media only screen and (min-width: 900px) {.wprs_masonry {-moz-column-count: '.$tempdisplaynum['m'].';-webkit-column-count: '.$tempdisplaynum['m'].';column-count: '.$tempdisplaynum['m'].';}}@media only screen and (min-width: 1100px) {.wprs_masonry {-moz-column-count: '.$tempdisplaynum['l'].';-webkit-column-count: '.$tempdisplaynum['l'].';column-count: '.$tempdisplaynum['l'].';}}';
			$masonrystyle = "<style>".$misc_masonry_style."</style>";
			echo $masonrystyle;
		}
		$templatestylecode = $templatestylecode . $masonrystyle;
		
		//added in v11.9.1, new read more CSS for scroll or read_more expand.
		$readmorestyle = '';
		if(!isset($template_misc_array['cutrevs'])){
			//must be a pre 11.9.1 template, so then we check read more.
			if(	$currentform[0]->read_more=="yes"){
				//using read_more so set this to yes.
				$template_misc_array['cutrevs'] = 'yes';
			} else {
				$template_misc_array['cutrevs'] = 'no';
			}
		}
		if(!isset($template_misc_array['cutrevs_lnum'])){
			$template_misc_array['cutrevs_lnum'] = '3';
		}
		//are we cutting long reviews?
		if($template_misc_array['cutrevs']=='yes'){
			//if read more is turned on
			if($currentform[0]->read_more=="yes"){
				$readmorestyle = "<style>#wprev-slider-".$formid." .indrevlineclamp{display:-webkit-box;-webkit-line-clamp: ".intval($template_misc_array['cutrevs_lnum']).";-webkit-box-orient: vertical;overflow:hidden;hyphens: auto;word-break: auto-phrase;}</style>";

			}
		}
		$templatestylecode = $templatestylecode . $readmorestyle;
		
		
		
		
		return $templatestylecode;
	}
	

	
}

?>