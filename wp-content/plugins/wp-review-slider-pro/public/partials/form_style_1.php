<?php

/**
 * Provide a public-facing view for the form style
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    WP_Review_Pro
 * @subpackage WP_Review_Pro/public/partials
 */
 //html code for the template style
$plugin_dir = WP_PLUGIN_DIR;
$imgs_url = esc_url( plugins_url( 'imgs/', __FILE__ ) );
//all values are in $formarray

//loop form fields and build output.

//$formfieldsarray = $formarray['form_fields'];
$formfieldsarray= json_decode($formarray['form_fields'], true);

$formfieldshtml = '';
$ratingrequired = '';

$form_misc_array = json_decode($formarray['form_misc'], true);

$btnwprevdefault = '';
//add btn style if set to default .btnwprevdefault
if(isset($form_misc_array['btnstyle'])){
	if($form_misc_array['btnstyle']=='default'){
		$btnwprevdefault = 'btnwprevdefault';
	} else if($form_misc_array['btnstyle']=='btn2'){
		$btnwprevdefault = 'btnwprevstyle2';
	}
}

//find post id and the current categories
$currentpostid = get_the_ID();
$catidarray = array();
$categories = get_the_category();
if(is_array($categories)){
	$arrlength = count($categories);
} else {
	$arrlength=0;
}
//check if this is a custom taxonomy like woocommerce
if($arrlength<1){
	//woocommerce check 
	if(taxonomy_exists('product_cat')){
	$categories = get_the_terms( $currentpostid, 'product_cat' );
		if(is_array($categories)){
		$arrlength = count($categories);
		}
	}
}
if($arrlength>0){
	for($x = 0; $x < $arrlength; $x++) {
		if(isset($categories[$x]->term_id)){
		$catidarray[] = $categories[$x]->term_id;	//array containing just the cat_IDs that this post belongs to, dashes added so we can use like search
		}
	}
}
//print_r($catidarray);

$jsoncatidarray = json_encode($catidarray);

//first find out what global logic values are if they are set
$globhiderest = '';
$globshowval = '';
$hideformhtml = '';
$defaultname="";
$defaultemail="";
if ( is_user_logged_in() ) {
	$current_user = wp_get_current_user();
	$defaultname= $current_user->display_name;
	$defaultemail=$current_user->user_email;
}

for ($x = 0; $x < count($formfieldsarray); $x++) {
	if($formfieldsarray[$x]['input_type']=='social_links'){
		if($formfieldsarray[$x]['hiderest']=='hide'){
			//$globhiderest = 'hide';
		}
		if($formfieldsarray[$x]['showval']!=''){
			$globshowval = $formfieldsarray[$x]['showval'];
		}
	}
}

for ($x = 0; $x < count($formfieldsarray); $x++) {
	//only for non hidden fields
	if($formfieldsarray[$x]['hide_field']==''){
		//add required symbol
		$reqhtml="";
		$reqinput="";
		$hidesslinkshtml = '';
		$hideroformhtml ='';
		$checkreqinput='';
		
		if($formfieldsarray[$x]['input_type']=='social_links'){
			if($formfieldsarray[$x]['hiderest']=='hide'){
				$globhiderest = 'hide';
			}
		}
		
		if($formfieldsarray[$x]['input_type']=='review_rating' || $formfieldsarray[$x]['input_type']=='social_links'){
			$restclass = '';
		} else {
			if($globhiderest == 'hide'){
				//hide entire form and then show what we need to with javascript
				$hideroformhtml = 'style="display:none;"';
				$restclass = 'rofform';
			} else {
				$restclass = '';
			}
		}
		
		$btnstyleclass = '';
		$iconhtml = '';
		if($formfieldsarray[$x]['input_type']=='review_avatar' || $formfieldsarray[$x]['input_type']=='review_video'){
			if(isset($formfieldsarray[$x]['picstyle']) && $formfieldsarray[$x]['picstyle']){
				$btnstyleclass = esc_attr($formfieldsarray[$x]['picstyle']);
			}
			if(isset($formfieldsarray[$x]['vidstyle']) && $formfieldsarray[$x]['vidstyle']){
				$btnstyleclass = esc_attr($formfieldsarray[$x]['vidstyle']);
			}
			if($btnstyleclass=='btn1' || $btnstyleclass=='btn2'){
				if($formfieldsarray[$x]['input_type']=='review_avatar'){
					$iconhtml = '<span class="svgicons svg-wprsp-camera"></span>&nbsp;';
				} else if($formfieldsarray[$x]['input_type']=='review_video'){	
					$iconhtml = '<span class="svgicons svg-wprsp-video-camera"></span>&nbsp;';
				}
			}
		}
		
		if($formfieldsarray[$x]['required']=='on'){
			$reqhtml='<span class="required symbol"></span>';
			$checkreqinput="required";
			//$reqinput="required oninvalid=\"this.setCustomValidity('".__('Please fill out this field.', 'wp-review-slider-pro')."')\" oninput=\"setCustomValidity('')\"";
			$reqinput="required";
		}
		if($globshowval!='' && $formfieldsarray[$x]['input_type']=='social_links'){
			//hide the social links and then show via javascript
			$hidesslinkshtml = 'style="display:none;"';
		}
		$formfieldshtml = $formfieldshtml . '<div '.$hidesslinkshtml.' '.$hideroformhtml.' class="wprevform-field wprevpro-field-'.esc_attr($formfieldsarray[$x]['name']).' '.$restclass.' '.$btnstyleclass.'">';
		
		if($formfieldsarray[$x]['show_label']=='on' || $btnstyleclass=='btn1' || $btnstyleclass=='btn2'){
			$formfieldshtml = $formfieldshtml . '<label id="lbl_'.esc_attr($formfieldsarray[$x]['name']).'" for="wprevpro_'.esc_attr($formfieldsarray[$x]['name']).'">'.$iconhtml.''.esc_html($formfieldsarray[$x]['label']).'</label>';
		}
		$formfieldshtml = $formfieldshtml . $reqhtml.'<span class="before">'.$formfieldsarray[$x]['before'].'</span>';
					
		//find type and change based on type
		if($formfieldsarray[$x]['input_type']=='review_rating' || $formfieldsarray[$x]['input_type']=='starrating'){
			if($checkreqinput=="required"){
				$ratingrequired = 'yes';
			}
			$ratetype = '';
			$tempid = $formfieldsarray[$x]['input_type'];
			$iscustom = '';
			if($tempid=="starrating"){
				$tempid = $formfieldsarray[$x]['name'];
				$iscustom = 'iscustom';
			}
			if(isset($formfieldsarray[$x]['starornot']) && $formfieldsarray[$x]['starornot'] && $formfieldsarray[$x]['starornot']=='updown'){
				$ratetype = $formfieldsarray[$x]['starornot'];
			}
			
			$afterclick='';
			if(isset($formfieldsarray[$x]['afterclick']) && $formfieldsarray[$x]['afterclick']!='' ){
				$afterclick='hideafterclick';
			}
						
			$formfieldshtml = $formfieldshtml . '<div class="wprevpro-rating-wrapper field-wrap in-form '.$afterclick.'">
							<fieldset contenteditable="false" id="wprevpro_'.$tempid.'" name="'.$tempid.'" class="wprevpro-rating" data-field-type="rating" tabindex="0">';
							
			//check rating type and add thumbs up or stars
			$hidestars = '';
			if($ratetype=='updown'){
				$hidestars = 'style="display:none !important;"';
				$rateiconup = 'svg-wprsp-thumbs-o-up';
				$rateicondown = 'svg-wprsp-thumbs-o-down';
				if($formfieldsarray[$x]['star_icon'] && $formfieldsarray[$x]['star_icon']!=''){
					if($formfieldsarray[$x]['star_icon']=='smileys'){
						$rateiconup = 'svg-wprsp-smile-o';
						$rateicondown = 'svg-wprsp-frown-o';
					}
				}
				//updown html here
				$formfieldshtml = $formfieldshtml . '<span id="wppro_fvoteup" class="svgicons '.$rateiconup.' wppro_updown '.$iscustom.'"></span><span id="wppro_fvotedown" class="svgicons '.$rateicondown.' wppro_updown '.$iscustom.'"></span>';
			}
			//add stars, hiding if we are doing a thumbs up
			$maxrating = 5;
			if(isset($formfieldsarray[$x]['maxrating']) && $formfieldsarray[$x]['maxrating']>0){
				$maxrating = $formfieldsarray[$x]['maxrating'];
			}
			if($formfieldsarray[$x]['default_form_value']==''){
				$formfieldsarray[$x]['default_form_value']=0;
			}
			for ($k = 0; $k <= $maxrating; $k++) {
				$tempchecked='';
				if($formfieldsarray[$x]['default_form_value']==$k){
					$tempchecked='checked="checked"';
				}
				$formfieldshtml = $formfieldshtml . '<input '.$hidestars.' type="radio" id="wprevpro_'.$tempid.'-star'.$k.'" name="wprevpro_'.$tempid.'" value="'.$k.'" '.$tempchecked.'><label '.$hidestars.' for="wprevpro_'.$tempid.'-star'.$k.'" title="'.$k.' stars" class="wprevpro-rating-radio-lbl"></label>';
			}
			
			$formfieldshtml = $formfieldshtml . '</fieldset></div>';		
		
		} else if($formfieldsarray[$x]['input_type']=='text'){
			$tempautocomplete = "";
			$defaulformvalue = esc_attr($formfieldsarray[$x]['default_form_value']);
			if($formfieldsarray[$x]['name']=='reviewer_name'){
				$defaulformvalue = $defaultname;
				$tempautocomplete = 'autocomplete="name"';
			} else if($formfieldsarray[$x]['name']=='company_name'){
				$tempautocomplete = 'autocomplete="organization"';
			}
			$formfieldshtml = $formfieldshtml . '<input id="wprevpro_'.esc_attr($formfieldsarray[$x]['name']).'" maxlength="250" type="text" class="text" name="wprevpro_'.esc_attr($formfieldsarray[$x]['name']).'" value="'.$defaulformvalue.'" tabindex="0" placeholder="'.esc_attr($formfieldsarray[$x]['placeholder']).'" '.$reqinput.'>';
		
		} else if($formfieldsarray[$x]['input_type']=='textarea'){
			$formfieldshtml = $formfieldshtml . '<textarea id="wprevpro_'.esc_attr($formfieldsarray[$x]['name']).'" maxlength="3500" name="wprevpro_'.esc_attr($formfieldsarray[$x]['name']).'" class="" '.$reqinput.' tabindex="0" placeholder="">'.esc_textarea($formfieldsarray[$x]['default_form_value']).'</textarea>';
		
		} else if($formfieldsarray[$x]['input_type']=='email'){
			if($defaultemail!=''){
				$tempuseremail = $defaultemail;
			} else {
				$tempuseremail = esc_attr($formfieldsarray[$x]['default_form_value']);
			}
			$formfieldshtml = $formfieldshtml . '<input id="wprevpro_'.esc_attr($formfieldsarray[$x]['name']).'" maxlength="250" type="email" class="text email" name="wprevpro_'.esc_attr($formfieldsarray[$x]['name']).'" value="'.$tempuseremail.'" '.$reqinput.' tabindex="0" placeholder="'.esc_attr($formfieldsarray[$x]['placeholder']).'" autocomplete="email">';
		
		} else if($formfieldsarray[$x]['input_type']=='url'){
			$formfieldshtml = $formfieldshtml . '<input id="wprevpro_'.esc_attr($formfieldsarray[$x]['name']).'" maxlength="250" type="url" class="text url" name="wprevpro_'.esc_attr($formfieldsarray[$x]['name']).'" value="'.esc_attr($formfieldsarray[$x]['default_form_value']).'" tabindex="0" placeholder="'.esc_attr($formfieldsarray[$x]['placeholder']).'" '.$reqinput.'>';
		
		} else if($formfieldsarray[$x]['input_type']=='review_avatar'){
			$styleclass = 
			$formfieldshtml = $formfieldshtml . '<input name="wprevpro_'.esc_attr($formfieldsarray[$x]['name']).'" id="wprevpro_'.esc_attr($formfieldsarray[$x]['name']).'" type="file" accept="image/x-png,image/gif,image/jpeg" '.$reqinput.' tabindex="0">';
		
		}  else if($formfieldsarray[$x]['input_type']=='review_video'){
			$formfieldshtml = $formfieldshtml . '<input name="wprevpro_'.esc_attr($formfieldsarray[$x]['name']).'" id="wprevpro_'.esc_attr($formfieldsarray[$x]['name']).'" type="file" accept="video/mp4,video/x-m4v,video/*" '.$reqinput.' tabindex="0">';
		
		} else if($formfieldsarray[$x]['input_type']=='review_consent'){
			$formfieldshtml = $formfieldshtml . '<input name="wprevpro_'.esc_attr($formfieldsarray[$x]['name']).'" id="wprevpro_'.esc_attr($formfieldsarray[$x]['name']).'" type="checkbox" value="yes" '.$reqinput.'>';
			
		}  else if($formfieldsarray[$x]['input_type']=='social_links'){
			
			$btnclass = $btnwprevdefault.' button';
			if(!isset($formfieldsarray[$x]['displaytype'])){
				$formfieldsarray[$x]['displaytype']='';
			}
			if($formfieldsarray[$x]['displaytype']=='sicon'){
				$btnclass = 'btnwprevdefault_sicon';
			} else if($formfieldsarray[$x]['displaytype']=='licon'){
				$btnclass = 'btnwprevdefault_licon';
			} 
			for ($k = 1; $k < 51; $k++) {
				if(isset($formfieldsarray[$x]['lurl'.$k]) && isset($formfieldsarray[$x]['lname'.$k]) && !isset($formfieldsarray[$x]['limgurl'.$k])){
										
					$formfieldshtml = $formfieldshtml . '<a href="'.esc_attr($formfieldsarray[$x]['lurl'.$k]).'" target="_blank" class="'.$btnclass.'">';
					$lname = $formfieldsarray[$x]['lname'.$k];
					//echo "lname:".$lname;
					$linkurl = $formfieldsarray[$x]['lurl'.$k];
					//check to see if displaying icon, if so then need to search and return img html
					$formfieldshtml = $formfieldshtml . $this->wppro_returniconhtml($linkurl,$formfieldsarray[$x]['displaytype'],$lname);
					
					
					$formfieldshtml = $formfieldshtml .'</a>';
				} else if(isset($formfieldsarray[$x]['lurl'.$k]) && isset($formfieldsarray[$x]['lname'.$k]) && isset($formfieldsarray[$x]['limgurl'.$k]) && $formfieldsarray[$x]['limgurl'.$k] !=''){
					//for custom icons
					$lname = $formfieldsarray[$x]['lname'.$k];
					$linkurl = $formfieldsarray[$x]['lurl'.$k];
					$linkimgurl = $formfieldsarray[$x]['limgurl'.$k];
					if($btnclass == $btnwprevdefault.' button'){
						$formfieldshtml = $formfieldshtml . '<a href="'.esc_attr($linkurl).'" target="_blank" class="'.$btnclass.'">'.$lname.'</a>';
					} else {
						$formfieldshtml = $formfieldshtml . '<a href="'.esc_attr($linkurl).'" target="_blank" class="'.$btnclass.'"><img src="'.esc_attr($linkimgurl).'" alt="'.$lname.' Logo" title="'.esc_html__('Leave a review on ', 'wp-review-slider-pro').' '.$lname.'" class="wprevpro_form_site_logo"></a>';
					}
				}
			}
		} else if($formfieldsarray[$x]['input_type']=='checkbox'){
			$formfieldshtml = $formfieldshtml . '<input name="wprevpro_'.esc_attr($formfieldsarray[$x]['name']).'" id="wprevpro_'.esc_attr($formfieldsarray[$x]['name']).'" type="checkbox" value="yes" '.$reqinput.'>';
			
		} else if($formfieldsarray[$x]['input_type']=='media'){
			
			$formfieldshtml = $formfieldshtml . '<input id="wprevpro_'.esc_attr($formfieldsarray[$x]['name']).'" type="text" class="text" name="wprevpro_'.esc_attr($formfieldsarray[$x]['name']).'" value="'.esc_attr($formfieldsarray[$x]['default_form_value']).'" tabindex="0" placeholder="'.esc_attr($formfieldsarray[$x]['placeholder']).'" '.$reqinput.'>';
			
			$formfieldshtml = $formfieldshtml . '<input name="wprevpro_'.esc_attr($formfieldsarray[$x]['name']).'_upload" id="wprevpro_'.esc_attr($formfieldsarray[$x]['name']).'_upload" type="file" accept="image/x-png,image/gif,image/jpeg" '.$reqinput.' tabindex="0">';
			
			
		} else if($formfieldsarray[$x]['input_type']=='select'){
			if(isset($formfieldsarray[$x]['selopts'])){
				//get comman string of options. opt1, opt2, opt 3,
				$opts = $formfieldsarray[$x]['selopts'];
				$opts = str_replace(", ", ",", $opts);
				$opts = str_replace(" , ", ",", $opts);
				$opts = str_replace(" ,", ",", $opts);
				$optsarray = explode(",", $opts);
				$formfieldshtml = $formfieldshtml .'	<select class="" name="wprevpro_'.$formfieldsarray[$x]['name'].'" id="wprevpro_'.$formfieldsarray[$x]['name'].'">';
					foreach ($optsarray as $value) {
					  $formfieldshtml = $formfieldshtml .'<option value="'.$value.'">'.$value.'</option>';
					}
				$formfieldshtml = $formfieldshtml .'</select>';
			}
		} else if($formfieldsarray[$x]['input_type']=='select_tag'){
			if(isset($formfieldsarray[$x]['selopts'])){
				//get comman string of options. opt1, opt2, opt 3,
				$opts = $formfieldsarray[$x]['selopts'];
				$opts = str_replace(", ", ",", $opts);
				$opts = str_replace(" , ", ",", $opts);
				$opts = str_replace(" ,", ",", $opts);
				$optsarray = explode(",", $opts);
				//for creating multiselect
				$multiselect = '';
				$classmultiselect = '';
				$classmultiselectarray = '';
				if($formfieldsarray[$x]['input_type']=='select_tag'){
					$multiselect = 'multiple';
					$classmultiselect = 'wprevpro_multiselect';
					$classmultiselectarray = '[]';
				}
				$formfieldshtml = $formfieldshtml .'	<select class="'.$classmultiselect.'" name="wprevpro_'.$formfieldsarray[$x]['name'].$classmultiselectarray.'" id="wprevpro_'.$formfieldsarray[$x]['name'].'" '.$multiselect.'>';
					foreach ($optsarray as $value) {
					  $formfieldshtml = $formfieldshtml .'<option value="'.$value.'">'.$value.'</option>';
					}
				$formfieldshtml = $formfieldshtml .'</select>';
			}
		} else if($formfieldsarray[$x]['input_type']=='select_page' && $formfieldsarray[$x]['selopts']!=''){	
			//for select page select_page
			//get comman string of options. opt1, opt2, opt 3,
			$totalloop = 1;
			$optsarray = array();
			$optnamessarray = array();
			
				$opts = $formfieldsarray[$x]['selopts'];
				$opts = str_replace(", ", ",", $opts);
				$opts = str_replace(" , ", ",", $opts);
				$opts = str_replace(" ,", ",", $opts);
				$optsarray = explode(",", $opts);
				if(count($optsarray)>0){
					$totalloop = count($optsarray);
				}
				if($formfieldsarray[$x]['seloptsname']!=''){
				$optnames = $formfieldsarray[$x]['seloptsname'];
				$optnames = str_replace(", ", ",", $optnames);
				$optnames = str_replace(" , ", ",", $optnames);
				$optnames = str_replace(" ,", ",", $optnames);
				$optnamessarray = explode(",", $optnames);
				}
				
				$formfieldshtml = $formfieldshtml .'	<select class="wprevpro_selpage" name="wprevpro_'.$formfieldsarray[$x]['name'].'" id="wprevpro_'.$formfieldsarray[$x]['name'].'">';
				
				for ($jx = 0; $jx < $totalloop; $jx++) {
					$tempname = $optsarray[$jx];
					if(isset($optnamessarray[$jx]) && $optnamessarray[$jx] !=''){
						$tempname = $optnamessarray[$jx];
					}
					$formfieldshtml = $formfieldshtml .'<option value="'.$optsarray[$jx].'">'.$tempname.'</option>';
				}

				$formfieldshtml = $formfieldshtml .'</select>';
				
				//add a hidden field for the selected page name, so we can save it as well. Will set with javascript on select change
				$formfieldshtml = $formfieldshtml . '<input class="wprevpro_selpagename" id="wprevpro_'.esc_attr($formfieldsarray[$x]['name']).'_pname" type="hidden" name="wprevpro_'.esc_attr($formfieldsarray[$x]['name']).'_pname" value="">';
				
				//also need a hidden field for whether or not we are creating a woo review from this.
				$formfieldshtml = $formfieldshtml . '<input class="wprevpro_selpagename" id="wprevpro_create_woo" type="hidden" name="wprevpro_create_woo" value="'.esc_attr($formfieldsarray[$x]['create_woo']).'">';
		}
		
		//add a hidden input for the labelif this is a custom field
		if( strpos( $formfieldsarray[$x]['name'], 'custom_' ) !== false) {
			$formfieldshtml = $formfieldshtml . '<input id="wprevpro_'.esc_attr($formfieldsarray[$x]['name']).'_label" type="hidden" name="wprevpro_'.esc_attr($formfieldsarray[$x]['name']).'_label" value="'.esc_html($formfieldsarray[$x]['label']).'">';
		}
		
		//after span changes for consent
		if($formfieldsarray[$x]['input_type']=='review_consent' || $formfieldsarray[$x]['input_type']=='checkbox'){
			$formfieldshtml = $formfieldshtml . '<span class="wprev_consent">'.$formfieldsarray[$x]['after'].'</span> </div>';
		} else {
			$formfieldshtml = $formfieldshtml . '<span class="after">'.$formfieldsarray[$x]['after'].'</span> </div>';
		}

		
	}
}

//captcha settings
$formcaptchahtml = '';
if($form_misc_array['captchaon']=='v2' && $form_misc_array['captchasite']!='' && $form_misc_array['captchasecrete']!=''){
	$formcaptchahtml = '<script src="https://www.google.com/recaptcha/api.js"></script>
	<div class="g-recaptcha wprevform-field rofform" '.$hideroformhtml.' data-sitekey="'.esc_html($form_misc_array['captchasite']).'"></div>';
}

//required note at top of form
$requiredtext = "Required field";
if($form_misc_array['requiredlabelshow']!='hide'){
	if($form_misc_array['requiredlabeltext']!=""){
		$requiredtext = esc_html( $form_misc_array['requiredlabeltext'] );
	}
	$formrequiredtext = '<p class="wprevpro_required_notice"><span class="required symbol"></span>'.$requiredtext.'</p>';
} else {
	$formrequiredtext ='';
}

//button text
$btntext = "Submit Review";
if($form_misc_array['btntext']!=''){
	$btntext = esc_html($form_misc_array['btntext']);
}
$btnclass = "";
if($form_misc_array['btnclass']!=''){
	$btnclass = esc_html($form_misc_array['btnclass']);
}

//hide form behind button
$formhtml = "";
$showonclick = "no";
$showonclicktext = "Leave a Review";
$formhidestyle = '';
$showbtnhtml='';
$hidebtn ='';
if(!isset($form_misc_array['autopopup'])){
	$form_misc_array['autopopup']='no';
}

if($form_misc_array['showonclick']=='yes' || $form_misc_array['showonclick']=='popup'){
	$formhidestyle = 'style="display: none;"';
	$popupbtn = '';
	if($form_misc_array['showonclick']=='popup'){
		$popupbtn = 'ispopup="yes"';
	}
	if($form_misc_array['showonclicktext']!=''){
		$showonclicktext = esc_html($form_misc_array['showonclicktext']);
	}
	if($form_misc_array['autopopup']=='yeshide'){
		$hidebtn = 'style="display: none;"';
	}
	$showbtnhtml = '<button '.$hidebtn.' '.$popupbtn.' formid="'.esc_html($formarray['id']).'" class="button wprevpro_btn_show_form '.$btnclass.' '.$btnwprevdefault.'">'.$showonclicktext.'</button>';
}


//doing ajax or regular default submit
$submitbuttonhtml = '<input type="hidden" name="action" value="wprev_submission_form">
					<input type="submit" id="wprevpro_submit_review" name="wprevpro_submit_review" value="'.$btntext.'" class="button btnwprevsubmit '.$btnwprevdefault.' '.$btnclass.'" tabindex="0">';
$ajaxmsgdiv ='';
if(isset($form_misc_array['useajax'])){
	if($form_misc_array['useajax']=='yes'){
		$submitbuttonhtml = '<input type="submit" id="wprevpro_submit_ajax" name="wprevpro_submit_ajax" value="'.$btntext.'" class="button btnwprevsubmit '.$btnwprevdefault.' '.$btnclass.'" tabindex="0">';
		$ajaxmsgdiv = '<div id="wprevpro_div_form_'.esc_html($formarray['id']).'_ajaxmsg" class="wprevpro_form_msg" style="display: none;"></div>';
	}
}

//track form submissions
if(!isset($form_misc_array['onesub'])){
	$form_misc_array['onesub']='';
}
if(!isset($form_misc_array['onesub_msg'])){
	$form_misc_array['onesub_msg']='';
}
$previoussubmitsuccessmessage ='';
if($form_misc_array['onesub']!=''){
	if($form_misc_array['onesub_msg']==''){
		$sucmsg = "You have already submitted this form, we appreciate your feedback!";
	} else {
		$sucmsg = $form_misc_array['onesub_msg'];
	}
	if($form_misc_array['successmsg']!=''){
		$sucmsg = esc_html($form_misc_array['successmsg']);
	}
	$previoussubmitsuccessmessage = '<div id="wprevpro_div_form_'.esc_html($formarray['id']).'_subonemsg" class="wprevpro_form wprevpro_form_submitone_msg wprevpro_submitsuccess" style="display: none;">'.$sucmsg.'</div>';
}

//add headerhtml if set
$headerhtmlval = '';
if(isset($currentform[0]->form_html) && $currentform[0]->form_html!=''){
	$headerhtmlval ='<div class="wprevform-headerhtml">'.stripslashes($currentform[0]->form_html).'</div>';
}

//if we are using auto-popup
$popupvar = '';
$popuppadding = '';
$popupmodalstart = '<div>';
$popupmodalend = '</div>';

//also hiding if $wppl is equal to yes and then the URL contains the wppl variable set to yes
$hideformonpagestart = '';
$hideformonpageend = '';
$hideformonpagejs = '';
	//now see if the URL has it set
	if (isset($_GET['wppl'])) {
	  $wppl_url = $_GET['wppl'];
	} else if(isset($_GET['review'])){
		if($_GET['review']=='1'){
			$wppl_url = 'yes';
		} else if($_GET['review']=='2'){
			$wppl_url = 'auto';
		}
	} else {
	  //Handle the case where there is no parameter
	  $wppl_url = 'no';
	}
	
if($wppl=='yes'){
	if($wppl == $wppl_url){
		//looking for variable and found it, so show form and auto-popup
	} else {
		//looking for vairable, did not find it, hide form on page
		$hideformonpagestart = '<div style="display: none;">';
		$hideformonpageend ='</div>';
		$hideformonpagejs = 'hideformonpagejs';
	}
}

//check wppl for autoclick value and add so js can autoclick the button and open form
$popupvar='';
if($wppl=='auto'){
	if($wppl == $wppl_url){
	$popupvar = 'autoclick="yes"';
	}
} else {
	$popupvar = 'autoclick=""';
}

if($form_misc_array['showonclick']=='popup' || $form_misc_array['autopopup']!='no'){
	if($form_misc_array['autopopup']=='yesshow' || $form_misc_array['autopopup']=='yeshide'){
		$popupvar = $popupvar.' autopopup="yes"';
	} else {
		$popupvar = $popupvar.' autopopup=""';
	}

	$formhidestyle = 'style="display: none;"';
	if($showbtnhtml==''){
		//not showing button so get rid of form padding
		$popuppadding='padding: 0px;';
	}
	$popupmodalstart ='<div id="wprevmodal_myModal_'.esc_html($formarray['id']).'" class="wprevmodal_modal">
				  <div class="wprevmodal_modal-content">
					<span class="wprevmodal_close">&times;</span>';
	$popupmodalend = '</div></div>';
}

//if we are lauching this form from a banner then force hidden form with no button.
if($wppl=='bannerlaunch'){
	$showbtnhtml='';
	$formhidestyle = 'style="display: none;"';
	$popuppadding='padding: 0px;';
	$popupmodalstart ='<div id="wprevmodal_myModal_'.esc_html($formarray['id']).'" class="wprevmodal_modal">
				  <div class="wprevmodal_modal-content">
					<span class="wprevmodal_close">&times;</span>';
	$popupmodalend = '</div></div>';
}


//track ip address
if(!isset($form_misc_array['ip'])){
	$form_misc_array['ip']='';
}
//random string, gets saved in browser cache.
$unbrid = substr(md5(time()), 0, 7);


$formhtml = $formhtml . '<style>'.esc_html( $formarray['form_css'] ).'</style>
				'.$hideformonpagestart.'<div id="wprevpro_div_form_'.esc_html($formarray['id']).'" class="wprevpro_form" '.$popupvar.' style="display: block;'.$popuppadding.'">
				'.$showbtnhtml.$popupmodalstart.'
					<div id="wprevpro_div_form_inner_'.esc_html($formarray['id']).'" class="wprevpro_form_inner" '.$formhidestyle.'>
						<form class="wprev_review_form" name="wprevpro_form_'.esc_html($formarray['id']).'" id="wprevpro_form_'.esc_html($formarray['id']).'" enctype="multipart/form-data" action="'.esc_url( admin_url('admin-post.php') ).'" method="post" autocomplete="off">
						'.$headerhtmlval.$formrequiredtext.'
							'.$formfieldshtml.'
							'.$formcaptchahtml.'
							<input id="wprevpro_fid" name="wprevpro_fid" value="'.esc_attr($formarray['id']).'" type="hidden">
							<div class="wpreveprohme"><input type="text" id="name" name="name" value=""></div>
							<div class="wprevform-field wprevpro_submit rofform" '.$hideroformhtml.'>
								<label>
								'.wp_nonce_field( 'post_nonce', 'post_nonce_field' ).'
								'.$submitbuttonhtml.'
								<input id="wprevpro_rating_req" name="wprevpro_rating_req" value="'.esc_html($ratingrequired).'" type="hidden">
								<input type="hidden" id="wprev_catids" name="wprev_catids" value="'.$jsoncatidarray.'">
								<input type="hidden" id="wprev_postid" name="wprev_postid" value="'.$currentpostid.'">
								<input type="hidden" id="wprev_urltag" name="wprev_urltag" value="">
								<input type="hidden" id="wprev_globhiderest" name="wprev_globhiderest" value="'.$globhiderest.'">
								<input type="hidden" id="wprev_globshowval" name="wprev_globshowval" value="'.$globshowval.'">
								<input type="hidden" id="wprev_ipFormInput" name="wprev_ipFormInput" value="'.esc_html($form_misc_array['ip']).'">
								<input type="hidden" id="wprev_Formonesub" name="wprev_Formonesub" value="'.esc_html($form_misc_array['onesub']).'">
								<input type="hidden" id="wprev_unique_id" name="wprev_unique_id" value="'.$unbrid.'">
								<input type="hidden" name="submitted" id="submitted" value="true" />
								</label>
							</div>
							<div class="wprev_loader" style="display:none;"></div>
						</form>
					</div>
					'.$ajaxmsgdiv.$previoussubmitsuccessmessage.$popupmodalend.'
				</div>'.$hideformonpageend;

//check if this form was just submitted by testing url. Display message if it was.$globhiderest = '';$globshowval = '';
if(!isset($_GET["wprevfs"])){
	$_GET["wprevfs"]='';
}
if(!isset($_GET["raid"])){
	$_GET["raid"]='';
}
if($_GET["wprevfs"]=="no"){
	//success message
	$sucmsg = "Thank you for your feedback!";
	if($form_misc_array['successmsg']!=''){
		$sucmsg = esc_html($form_misc_array['successmsg']);
	}
	echo '<div id="wprevpro_div_form_'.esc_html($formarray['id']).'" class="wprevpro_form wprevpro_submitsuccess" style="display: block;">
				'.$sucmsg.'
				</div>';
} else if($_GET["wprevfs"]=="yes"){
	//display errors, must get from option in db
	$erroroptions = get_option('wprevpro_form_errors');
	$raid = $_GET["raid"];
	$sucmsg = $erroroptions[$raid];
	echo '<div id="wprevpro_div_form_'.esc_html($formarray['id']).'" class="wprevpro_form wprevpro_submiterror" style="display: block;">
				'.$sucmsg.'
				</div>';
} else {
	//display form

	echo $formhtml;
}

?>


<?php
//print_r($formarray);
?>