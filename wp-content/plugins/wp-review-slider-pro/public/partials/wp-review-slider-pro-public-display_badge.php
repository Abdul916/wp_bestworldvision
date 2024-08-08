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
	$table_name = $wpdb->prefix . 'wpfb_badges';
	
 //use the template id to find template in db, echo error if we can't find it or just don't display anything
 	//Get the form--------------------------
	$tid = htmlentities($a['tid']);
	$tid = intval($tid);
	$currentform = $wpdb->get_results("SELECT * FROM $table_name WHERE id = ".$tid);

	
	//print_r($a);
	//override currentform values with shortcodes if set
	if(isset($a['orgin']) && $a['orgin']!=''){
		$currentform[0]->badge_orgin = strtolower($a['orgin']);
	}
	if(isset($a['pageid']) && $a['pageid']!=''){
		//explode in to array and then json_encode
		$temppageids = str_replace(" ","",$a['pageid']);
		$temppieces = explode(",", $a['pageid']);
		$currentform[0]->rpage = json_encode($temppieces);
	}
	if(isset($a['from']) && $a['from']!=''){
		if($a['from']=='table' || $a['from']=='db' || $a['from']=='source'){
		//explode in to array and then json_encode
			if($a['from']=='source'){
				$a['from']='table';
			}
			$currentform[0]->ratingfromoverride = $a['from'];
		}
	}
	

//print_r($currentform);

	$google_snippet_add ='';
	$google_snippet_type ='';
	$google_snippet_name ='';
	$google_snippet_desc ='';
	$google_snippet_business_image ='';
	$google_snippet_more_array_encode ='';
	
	//check to make sure template found
	if(isset($currentform[0])){
		
		//print_r($currentform[0]);
		
		
		//add styles from template misc here
		
			$templatestylecode = '';
			$misc_style= '';
			$template_misc_array = json_decode($currentform[0]->badge_misc, true);
			if(is_array($template_misc_array)){
				//hide stars and/or date
				if($template_misc_array['showstars']=="no"){
					$misc_style = $misc_style . '#wprev-badge-'.$currentform[0]->id.' .wppro_badge1_DIV_stars {display: none;}';
				}
				//hide or show large icon
				if($template_misc_array['show_licon']=="no"){
					$misc_style = $misc_style . '#wprev-badge-'.$currentform[0]->id.' .wppro_badge1_IMG_3 {display: none;}';
				}
				$misc_style = $misc_style . '#wprev-badge-'.$currentform[0]->id.'{border-radius: '.$template_misc_array['bradius'].'px;}';
				if($template_misc_array['bgcolor1']!='' && $currentform[0]->style!='4'){
					if($currentform[0]->style=='3'){
						$misc_style = $misc_style . '#wprev-badge-'.$currentform[0]->id.'{border-top: 3px solid '.$template_misc_array['bgcolor1'].';}';
					} else if($currentform[0]->style=='5'){
						$misc_style = $misc_style . '#wprev-badge-'.$currentform[0]->id.'{border: 1px solid '.$template_misc_array['bgcolor1'].';}';
					} else {
						$misc_style = $misc_style . '#wprev-badge-'.$currentform[0]->id.'{border-top: 5px solid '.$template_misc_array['bgcolor1'].';}';
					}
				}
				if($template_misc_array['bgcolor2']!='' && $currentform[0]->style!='4'){
				$misc_style = $misc_style . '#wprev-badge-'.$currentform[0]->id.'{background: '.$template_misc_array['bgcolor2'].';}';
				}
				if($template_misc_array['bgcolor3']!='' && $currentform[0]->style!='4'){
					if($currentform[0]->style=='3'){
						$misc_style = $misc_style . '#wprev-badge-'.$currentform[0]->id.'{border-bottom: 3px solid '.$template_misc_array['bgcolor3'].';}';
					} else {
						$misc_style = $misc_style . '#wprev-badge-'.$currentform[0]->id.'{border-bottom: 5px solid '.$template_misc_array['bgcolor3'].';}';
					}
				}
				if($template_misc_array['starcolor']!=''){
				//$misc_style = $misc_style . '#wprev-badge-'.$currentform[0]->id.' .wppro_badge1_DIV_stars {color: '.$template_misc_array['starcolor'].';}';
				
				$misc_style = $misc_style . '#wprev-badge-'.$currentform[0]->id.' .svgicons {background: '.$template_misc_array['starcolor'].' }';
				
					if($currentform[0]->style=='2'){
						//$misc_style = $misc_style . '#wprev-badge-'.$currentform[0]->id.' .ratingRow__star {color: '.$template_misc_array['starcolor'].';}';
						
						
					}
				}
				if($template_misc_array['tcolor1']!=''){
					if($currentform[0]->style!='2'){
				$misc_style = $misc_style . '#wprev-badge-'.$currentform[0]->id.' .wppro_badge1_SPAN_4 {color: '.$template_misc_array['tcolor1'].';}';
					} else if($currentform[0]->style=='2') {
						//for template 2
						$misc_style = $misc_style . '#wprev-badge-'.$currentform[0]->id.' .wppro_dashboardReviewSummary__avgRating {color: '.$template_misc_array['tcolor1'].';}';
					}
				}
				if($template_misc_array['tcolor2']!=''){
					if($currentform[0]->style!='2'){
				$misc_style = $misc_style . '#wprev-badge-'.$currentform[0]->id.' .wppro_badge1_SPAN_13 {color: '.$template_misc_array['tcolor2'].';}';
					} else if($currentform[0]->style=='2') {
						//for template 2
						$misc_style = $misc_style . '#wprev-badge-'.$currentform[0]->id.' .wppro_dashboardReviewSummary__avgReviews {color: '.$template_misc_array['tcolor2'].';}';
					}
				}
				if($template_misc_array['tcolor3']!=''){
					if($currentform[0]->style!='2'){
				$misc_style = $misc_style . '#wprev-badge-'.$currentform[0]->id.' .wppro_badge1_DIV_12 {color: '.$template_misc_array['tcolor3'].';}';
					} else if($currentform[0]->style=='2') {
						//for template 2
						$misc_style = $misc_style . '#wprev-badge-'.$currentform[0]->id.' .wppro_b2__ratingRow {color: '.$template_misc_array['tcolor3'].';}';
					}
				}
				//for border shadow
				if(isset($template_misc_array['shadow']) && $template_misc_array['shadow']=='no'){
					$misc_style = $misc_style . '#wprev-badge-'.$currentform[0]->id.' {box-shadow: unset;}';
				}
				
				//for badge width settings
				if(isset($template_misc_array['bwidth']) && $template_misc_array['bwidth']!='' && $template_misc_array['bwidth']>0){
					$tempwidthnum = intval($template_misc_array['bwidth']);
					$tempwidthper = '%';
					if($template_misc_array['bwidtht']=='px'){
						$tempwidthper = 'px';
					}
					//check for auto
					if($tempwidthper=='%' && $tempwidthnum>99){
						$tempwidthnum = 'auto';
						$tempwidthper='';
					}
					$misc_style = $misc_style . '#wprev-badge-'.$currentform[0]->id.' {width: '.$tempwidthnum.$tempwidthper.';}';
					
				}
				
				//------------------------
				//echo "<style>".$misc_style."</style>";
				$templatestylecode = "<style>".$misc_style."</style>";
			}
			
			//print out user style added
			//echo "<style>".$currentform[0]->template_css."</style>";
			if($currentform[0]->badge_css!=''){
			$templatestylecode = $templatestylecode . "<style>".sanitize_text_field($currentform[0]->badge_css)."</style>";
			}
			
			//remove line breaks and tabs
			$templatestylecode = str_replace(array("\n", "\t", "\r"), '', $templatestylecode);
			echo $templatestylecode;
			
			//adding outer div if we are going to add a slide-out to this
			$onclickaction ='';
			if(isset($template_misc_array['onclickaction'])){
				$onclickaction = $template_misc_array['onclickaction'];
			}
			$ochtml='';
			$ochtmlurl ='';
			$ochtmlurltarget='';
			// $badgeid only set if this is not a float.
			if(isset($badgeid) && ($onclickaction=='url' || $onclickaction=="slideout" || $onclickaction=="popup")){
				//add style for cursor
				echo '<style>.wprevpro_badge{cursor:pointer;}</style>';
				$ochtml = "data-onc='".$onclickaction."'";
				$ochtmlurl = "data-oncurl='".$template_misc_array['onclickurl']."'";
				$ochtmlurltarget = "data-oncurltarget='".$template_misc_array['onclickurl_target']."'";
			}
			//adding for animation so we can modify in jquery
			$animatedir = '';
			$animatedelay = '';
			if(isset($template_misc_array['animate_dir'])){
				$animatedir = $template_misc_array['animate_dir'];
			}
			if(isset($template_misc_array['animate_delay'])){
				$animatedelay = $template_misc_array['animate_delay'];
			}

			if(isset($badgeid) && ($onclickaction=='url' || $onclickaction=="slideout"  || $onclickaction=="popup")){
				echo '<div data-badgeid="'.$badgeid.'" class="wprevpro_badge_container" '.$ochtml.' '.$ochtmlurl.' '.$ochtmlurltarget.' data-animatedir="'.$animatedir.'" data-animatedelay="'.$animatedelay.'" >';
			}
			
		//include the correct badge_style_1.php based on the style of the badge
		if ( wrsp_fs()->can_use_premium_code() ) {
			include(plugin_dir_path( __FILE__ ) . 'badge_style_'.$currentform[0]->style.'.php');
		}
		
		if(isset($badgeid) && ($onclickaction=='url' || $onclickaction=="slideout"  || $onclickaction=="popup")){
			echo '</div>';
		}
		
		

		//add hidden slideout html here if it is set on badge settings===============
		
		
		//create slideout styles-----------
		//if on click setting is url add pointer style
		//check if this is inside a Float or not. If it is then $badgeid will not be set. We do not want to allow duplicate popups or slideouts, so only create this if not inside a Float.

		$slideoutstylehtml = '';
		$revtemplateid = $template_misc_array['sliderevtemplate'];
		//add the header and footer html
		if(!isset($template_misc_array['slideheader'])){
			$template_misc_array['slideheader'] = '';
			$template_misc_array['slidefooter'] = '';
		}
		$headerhtml = stripslashes($template_misc_array['slideheader']);
		$footerhtml = stripslashes($template_misc_array['slidefooter']);
		
		//border size
		$slbordersize = 1;
		if(isset($template_misc_array['slborderwidth'])){
			$slbordersize = $template_misc_array['slborderwidth'];
		}
		
		//get the global array of slideouts so we can add to it
		if(!isset($GLOBALS['wprevpro_badge_slidepop'])){
			$GLOBALS['wprevpro_badge_slidepop'] = array();
		}
		$tempglobalslideouts = $GLOBALS['wprevpro_badge_slidepop'];
		
		if($onclickaction=="slideout" && isset($badgeid)){
			$slidelocation = $template_misc_array['slidelocation'];
			$slideheight = $template_misc_array['slheight'];
			if($slideheight==""){
				$slideheight='auto;';
			} else {
				$slideheight=$slideheight.'px;';
			}
			$slidewidth = $template_misc_array['slwidth'];
			if($slidewidth==""){$slidewidth=350;}
			$slidelochtml='';
			if($slidelocation=="right"){
				$slidelochtml = $slidelochtml . 'bottom: 0px;right: 0px;height: 100%;width: '.$slidewidth.'px;';
				$slidelochtml = $slidelochtml . 'border-right-style:none !important; border-bottom-style:none !important; border-top-style:none !important;';
			} else if($slidelocation=="left"){
				$slidelochtml = $slidelochtml . 'bottom: 0px;left: 0px;height: 100%;width: '.$slidewidth.'px;';
				$slidelochtml = $slidelochtml . 'border-left-style:none !important; border-bottom-style:none !important; border-top-style:none !important;';
			} else if($slidelocation=="top"){
				$slidelochtml = $slidelochtml . 'top: 0px;bottom:unset;width: 100%;height: '.$slideheight;
				$slidelochtml = $slidelochtml . 'border-left-style:none !important; border-right-style:none !important; border-top-style:none !important;';
			} else if($slidelocation=="bottom"){
				$slidelochtml = $slidelochtml . 'top:unset;bottom: 0px;width: 100%;height: '.$slideheight;
				$slidelochtml = $slidelochtml . 'border-left-style:none !important; border-right-style:none !important; border-bottom-style:none !important;';
			}
			
			//background color
			$slbgcolor1 = $template_misc_array['slbgcolor1'];
			if($slbgcolor1!=''){
				$slidelochtml = $slidelochtml . 'background: '.$slbgcolor1.';';
			}
			$slbordercolor1 = $template_misc_array['slbordercolor1'];
			if($slbordercolor1!=''){
				$slidelochtml = $slidelochtml . 'border: '.$slbordersize.'px solid '.$slbordercolor1.';';
			}
			//slide padding
			$slidepaddingarray = [$template_misc_array['slpadding-top'],$template_misc_array['slpadding-right'],$template_misc_array['slpadding-bottom'],$template_misc_array['slpadding-left']];
			$tempstyletext='';
			$arrayLength = count($slidepaddingarray);
			for ($i = 0; $i < $arrayLength; $i++) {
				if($slidepaddingarray[$i]!=''){
					if($i==0){
						$tempstyletext = $tempstyletext . 'padding-top:' . $slidepaddingarray[$i] . 'px; ';
					} else if($i==1){
						$tempstyletext = $tempstyletext . 'padding-right:' . $slidepaddingarray[$i] . 'px; ';
					} else if($i==2){
						$tempstyletext = $tempstyletext . 'padding-bottom:' . $slidepaddingarray[$i] . 'px; ';
					} else if($i==3){
						$tempstyletext = $tempstyletext . 'padding-left:' . $slidepaddingarray[$i] . 'px; ';
					}
				}
			}
			$bodystyle = '#wprevpro_badge_slide_'.$badgeid.' .wprevpro_slideout_container_body {'.$tempstyletext.'}';
			$locstyle = '#wprevpro_badge_slide_'.$badgeid.' {'.$slidelochtml.'}';
			$slideoutstylehtml = '<style>'.$locstyle.$bodystyle.'</style>';
			
			//echo "check method";
			//var_dump(method_exists($this,'wppro_getslideout_html'));
			//echo "done";
			if(method_exists($this,'wppro_getslideout_html')){
				$slidehtmldata = $this->wppro_getslideout_html($badgeid,$revtemplateid);
			} else {
				require_once(WPREV_PLUGIN_DIR . 'public/class-wp-review-slider-pro-public.php');
				$plugin_public_function = new WP_Review_Pro_Public( WPREVPRO_PLUGIN_TOKEN, WPREVPRO_PLUGIN_VERSION );
				$slidehtmldata = $plugin_public_function->wppro_getslideout_html($badgeid,$revtemplateid);
			}
			
			$divhtml = '<span class="wprevpro_slideout_container_style">'.$slideoutstylehtml.'</span>
				<div class="wprevpro_slideout_container" id="wprevpro_badge_slide_'.$badgeid.'" style="visibility: hidden;">
				<span class="wprevslideout_close">×</span>
					<div class="wprevpro_slideout_container_header">'.$headerhtml.'</div>
					<div class="wprevpro_slideout_container_body">'.$slidehtmldata.'</div>
					<div class="wprevpro_slideout_container_footer">'.$footerhtml.'</div>
				</div>';
			//echo $divhtml;
			$tempglobalslideouts[$badgeid]=$divhtml;
			$GLOBALS['wprevpro_badge_slidepop'] = $tempglobalslideouts;
		} 
		
		if($onclickaction=="popup" && isset($badgeid)){
			//background color
			$slidelochtml='';
			$slbgcolor1 = $template_misc_array['slbgcolor1'];
			if($slbgcolor1!=''){
				$slidelochtml = $slidelochtml . 'background: '.$slbgcolor1.';';
			}
			$slbordercolor1 = $template_misc_array['slbordercolor1'];
			if($slbordercolor1!=''){
				$slidelochtml = $slidelochtml . 'border: '.$slbordersize.'px solid '.$slbordercolor1.';';
			}
			
			$locstyle = '#wprevpro_badge_pop_'.$badgeid.' .wprevpro_popup_container_inner {'.$slidelochtml.'}';
			$slideoutstylehtml = '<style>'.$locstyle.'</style>';
			$slidehtmldata = $this->wppro_getslideout_html($badgeid,$revtemplateid);
			
			$divhtml = '<span class="wprevpro_popup_container_style">'.$slideoutstylehtml.'</span>
						<div class="wprevmodal_modal wprevpro_popup_container" id="wprevpro_badge_pop_'.$badgeid.'" style="visibility:hidden;">
							<div class="wprevmodal_modal-content wprevpro_popup_container_inner ">
								<span class="wprevmodal_close">×</span>
								<div class="wprevpro_popup_container_header">'.$headerhtml.'</div>
								<div class="wprevpro_popup_container_body">'.$slidehtmldata.'</div>
								<div class="wprevpro_popup_container_footer">'.$footerhtml.'</div>
							</div>
						</div>';
			//echo $divhtml;
			$tempglobalslideouts[$badgeid]=$divhtml;
			$GLOBALS['wprevpro_badge_slidepop'] = $tempglobalslideouts;
		}
		

		
		//====================================================	
		//snippet
		$google_snippet_add =$currentform[0]->google_snippet_add;
		$google_snippet_type =$currentform[0]->google_snippet_type;
		$google_snippet_name =$currentform[0]->google_snippet_name;
		$google_snippet_desc =$currentform[0]->google_snippet_desc;
		$google_snippet_business_image =$currentform[0]->google_snippet_business_image;
		$google_snippet_more_array_encode =$currentform[0]->google_snippet_more;

	

		//turn on google snippet if set to yes
		if($google_snippet_add=="yes" && $finalavg>0){
			
			//default name to post/page title
			if($google_snippet_name==''){
				$google_snippet_name = esc_html( get_the_title() );
			}
			$google_snippet_name = stripslashes($google_snippet_name);
			if($google_snippet_desc==''){
				if(get_the_ID()){
					$post_id = get_the_ID();
					$google_snippet_desc = $this->get_post_excerpt_by_id( $post_id );
				}
			}
			$google_snippet_desc = stripslashes($google_snippet_desc);
			if($google_snippet_business_image==''){
				$google_snippet_business_image = get_the_post_thumbnail_url( get_the_ID(), 'medium' );
			}

			$google_misc_array = json_decode($google_snippet_more_array_encode, true);
			if(!is_array($google_misc_array)){
				$google_misc_array=array();
				$google_misc_array['telephone']="";
				$google_misc_array['priceRange']="";
				$google_misc_array['streetAddress']="";
				$google_misc_array['addressLocality']="";
				$google_misc_array['addressRegion']="";
				$google_misc_array['postalCode']="";
			}
			if($google_misc_array['streetAddress']!='' || $google_misc_array['addressLocality']!='' || $google_misc_array['addressRegion']!='' || $google_misc_array['postalCode']!=''){
				$gsaddress = ', "address": {"@type": "PostalAddress","addressLocality": "'.$google_misc_array['addressLocality'].'","addressRegion": "'.$google_misc_array['addressRegion'].'","postalCode": "'.$google_misc_array['postalCode'].'","streetAddress": "'.$google_misc_array['streetAddress'].'"}';
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
			
			$ratingcounthtml = "ratingCount";
			if(isset($google_misc_array['tvr']) && $google_misc_array['tvr']!=''){
				if($google_misc_array['tvr']=='reviews'){
					$ratingcounthtml = "reviewCount";
				}
			}
			

			$tempsnippethtml = '<script type="application/ld+json">{'.$schemaidtext.'"@context": "http://schema.org/","@type": "'.$google_snippet_type.'","name": "'.$google_snippet_name.'","description": "'.$google_snippet_desc.'","aggregateRating": {"@type": "AggregateRating","ratingValue": "'.$finalavg.'","'.$ratingcounthtml.'": "'.$finaltotal.'","bestRating": "5","worstRating": "1"},"image": "'.$google_snippet_business_image.'"'.$gsaddress.$gsphone.$gsprice.$prodmoretxt.'}</script>';
			echo $tempsnippethtml;
		}
	 
	}

?>

