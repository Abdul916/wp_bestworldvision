<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://ljapps.com
 * @since      1.0.0
 *
 * @package    WP_Review_Pro
 * @subpackage WP_Review_Pro/admin/partials
 */
 
 //freemius license function https://freemius.com/help/documentation/wordpress-sdk/software-licensing/
 
 //add thickbox
 add_thickbox();
 
 //global variables for using freemius api
	//$frlicenseid = get_option( 'wprev_fr_siteid' );
	//$frsiteurl = get_option( 'wprev_fr_url' );
 
     // check user capabilities
    if (!current_user_can('manage_options') && $this->wprev_canuserseepage('get_apps')==false) {
        return;
    }
	
	$dbmsg = "";
	$html="";
	$currentgetappform= new stdClass();
	$currentgetappform->id="";
	$currentgetappform->title="";
	$currentgetappform->page_id="";
	$currentgetappform->site_type="";
	$currentgetappform->url="";
	$currentgetappform->cron="";
	$currentgetappform->blocks="100";
	$currentgetappform->last_name="full";
	$currentgetappform->sortoption="";
	$currentgetappform->profile_img="";
	$currentgetappform->categories="";
	$currentgetappform->posts="";
	$currentgetappform->rectostar="";
	$currentgetappform->langcode="";
	$currentgetappform->crawlserver="";
	$currentgetappform->reviewlistpageid="";
	$maxnumcandownload = "";
	$hidelastnameoption = false;
	$hideimageoption = false;
	$hidenumtodownload = false;
	
	$rtype='';
	$exdesc=__('The URL of the page where the reviews or recommendations are located.', 'wp-review-slider-pro');
	$exurls ='';
	$exurlsplaceholder='';
	$allwithrf ='';
	$showcrawlserveroption = false;
	
	//find this review source type, iTunes, GetYourGuide
		$rtype=$_GET['rtype'];
		if($rtype=='iTunes'){
			$hideimageoption = true;
			$exurls = '<br>'.__('Please make sure the URL is visible by a browser. In other words, it doesn\'t open in iTunes.', 'wp-review-slider-pro').'<br><i>https://podcasts.apple.com/us/podcast/id1462192400<br>https://books.apple.com/us/audiobook/where-the-crawdads-sing-unabridged/id1428290134<br>https://apps.apple.com/us/app/pok%C3%A9mon-go/id1094591345</i>';
		} else if($rtype=='GetYourGuide'){
			$hideimageoption = true;
			$exurls = ' Ex:<br><i>https://www.getyourguide.com/new-york-city-l59/new-york-one-world-observatory-entrance-ticket-t52132/</i>';
		} else if($rtype=='SourceForge'){
			$hideimageoption = true;
			$hidelastnameoption = true;
			$hidenumtodownload = true;
			$exurls = ' Ex:<br><i>https://sourceforge.net/software/product/Visual-Visitor/</i><br><i>https://sourceforge.net/projects/portableapps/reviews/</i>';
			$currentgetappform->blocks="50";
		} else if($rtype=='WordPress'){
			$hideimageoption = false;
			$hidelastnameoption = true;
			$hidenumtodownload = false;
			$exurls = ' Ex:<br><i>https://wordpress.org/support/plugin/wp-tripadvisor-review-slider/reviews/</i><br><i>https://wordpress.org/support/theme/twentytwentyone/reviews/</i>';
			$currentgetappform->blocks="20";
			$maxnumcandownload = "";
		} else if($rtype=='TrueLocal'){
			$hideimageoption = false;
			$currentgetappform->blocks="50";
			$maxnumcandownload = "";
			$hidelastnameoption = false;
			$hidenumtodownload = false;
			$exurlsplaceholder='';
			$exdesc=__('The URL to the business page. ', 'wp-review-slider-pro');
			$exurls = 'Ex:<br><i>https://www.truelocal.com.au/business/all-car-express/brookvale</i><br>';
		} else if($rtype=='Experience'){
			$hideimageoption = false;
			$currentgetappform->blocks="50";
			$maxnumcandownload = "";
			$hidelastnameoption = false;
			$hidenumtodownload = false;
			$exurlsplaceholder='';
			$exdesc=__('The URL to the business page. ', 'wp-review-slider-pro');
			$exurls = 'Ex:<br><i>https://pro.experience.com/pages/david-talbott</i><br>';
		}  else if($rtype=='Hostelworld'){
			$hideimageoption = true;
			$exurls = ' Ex:<br><i>https://www.hostelworld.com/pwa/hosteldetails.php/Bazpackers-Hostel/Inverness/49057</i>';
		}  else if($rtype=='HousecallPro'){
			$hideimageoption = true;
			$exurls = ' Ex:<br><i>https://client.housecallpro.com/reviews/Wellmann-Plumbing/de5f6b5d-23a0-4467-89fe-f793c431470d/</i>';
		} else if($rtype=='Nextdoor'){
			$hidenumtodownload = true;
			$exurls = ' Ex:<br><i>https://nextdoor.com/pages/tortoras-owens-cross-roads-al/</i>';
		} else if($rtype=='Zillow'){
			$currentgetappform->blocks="20";
			$hideimageoption = true;
			$exdesc='';
			$showcrawlserveroption = true;
			$currentgetappform->crawlserver="local";
			$exurls = ' Ex: <i>https://www.zillow.com/reviews/write/?s=X1-ZUvu3i2bzw4m4p_46au8</i><br>'.__('This is the Write Review link address.', 'wp-review-slider-pro').'  <a href="https://wpreviewslider.com/wp-content/uploads/2023/02/zillow_instr.mp4" target="_blank" style="text-decoration: none;">'.__('Video Instructions', 'wp-review-slider-pro').'</a> <br>'.__('<b>Use the Get Reviews > Review Funnel to download Lender Reviews.</b>', 'wp-review-slider-pro');
		}  else if($rtype=='AngiesList'){
			$exurls = ' Ex:<br><i>https://www.angi.com/companylist/us/wi/verona/accurate-tree-services-reviews-5539193.htm</i>'.'<br>'.__('<b>AngiesList download is limited to 100 reviews.</b>', 'wp-review-slider-pro');
			$currentgetappform->blocks="25";
			$maxnumcandownload = "100";
			$hidelastnameoption = true;
			$hideimageoption = true;
			$hidenumtodownload = false;
		} else if($rtype=='Qualitelis'){			//Qualitelis
			$hideimageoption = true;
			$hidenumtodownload = true;
		} else if($rtype=='Freemius'){
			$hidenumtodownload = true;
		}  else if($rtype=='Feefo'){
			$hideimageoption = true;
		}  else if($rtype=='Realtor'){
			$hideimageoption = true;
			$hidenumtodownload = true;
			$showcrawlserveroption = true;
			$currentgetappform->crawlserver="local";
		}  else if($rtype=='Google'){
			$hideimageoption = false;
			$hidenumtodownload = false;
			$maxnumcandownload = "50";
			$currentgetappform->blocks="50";
			$allwithrf = "Max of 10 reviews for Hotels anbd 20 for Products. If you need all your reviews please use a review funnel.";
			$exurlsplaceholder='e.g.: ChIJOUW7JL0RYogRgDxol-LP_sU';
			$exdesc='';
			$exurls = ' '.__('Need help finding your', 'wp-review-slider-pro').'<a href="https://ljapps.com/wp-content/uploads/2021/08/google_search_terms.mp4" target="_blank" style="text-decoration: none;">
			'.__('Google Search Terms', 'wp-review-slider-pro').'</a> '.__('or', 'wp-review-slider-pro').' <a href="https://ljapps.com/two-methods-to-find-your-google-place-id/" target="_blank" style="text-decoration: none;">
			'.__('Place ID?', 'wp-review-slider-pro').'</a> ';
		} else if($rtype=='FeedbackCompany'){
			$hideimageoption = true;
			$hidenumtodownload = false;
			$maxnumcandownload = "1000";
			$currentgetappform->blocks="10";
			$exurls = '  <a href="https://ljapps.com/wp-content/uploads/2021/11/feedbackcompany.mp4" target="_blank" style="text-decoration: none;">'.__('Video Instructions', 'wp-review-slider-pro').'</a> ';
		} else if($rtype=='StyleSeat'){
			$hideimageoption = true;
			$hidenumtodownload = false;
			$maxnumcandownload = "";
			$currentgetappform->blocks="10";
			$exdesc= __('The URL of the page where the reviews or recommendations are located.', 'wp-review-slider-pro');
			$exurls = ' Ex:<br><i>https://www.styleseat.com/m/v/madlashinc</i>';
		} else if($rtype=='Reviews.io'){
			$hideimageoption = true;
			$hidenumtodownload = false;
			$maxnumcandownload = "";
			$currentgetappform->blocks="20";
			$exdesc= __('The URL of the page where the reviews or recommendations are located. Does not currently work for Product reviews.', 'wp-review-slider-pro');
			$exurls = ' Ex:<br><i>https://www.reviews.co.uk/company-reviews/store/simplylendingsolutions-co-uk-</i>';
		} else if($rtype=='TripAdvisor'){
			$hideimageoption = false;
			$hidenumtodownload = false;
			$maxnumcandownload = "50";
			$currentgetappform->blocks="20";
			$allwithrf = "";
			$exdesc= __('The URL of the page where the reviews or recommendations are located.', 'wp-review-slider-pro');
			$exurls = ' Ex:<br><i>https://www.tripadvisor.com/Restaurant_Review-g30755-d1817350-Reviews-Tortora_s-Owens_Cross_Roads_Alabama.html</i>';
			$currentgetappform->crawlserver="local";
			$showcrawlserveroption = true;
		}  else if($rtype=='Airbnb'){
			$hideimageoption = false;
			$hidenumtodownload = false;
			$maxnumcandownload = "";
			$currentgetappform->blocks="20";
			$allwithrf = "";
			$exdesc= __('The URL of the page where the reviews or recommendations are located.', 'wp-review-slider-pro');
			$exurls = ' Ex:<br><i>https://www.airbnb.com/rooms/5337141</i><br><i>https://www.airbnb.com/experiences/3062007</i>';
		}  else if($rtype=='GuildQuality'){
			$hideimageoption = true;
			$hidenumtodownload = false;
			$maxnumcandownload = "50";
			$currentgetappform->blocks="50";
			$allwithrf = "";
			$exdesc= __('The URL of the page where the reviews or recommendations are located.', 'wp-review-slider-pro');
			$exurls = ' Ex:<br><i>https://www.guildquality.com/pro/model-remodel?tab=reviews</i>';
		} else if($rtype=='VRBO'){
			$hideimageoption = true;
			$hidenumtodownload = false;
			$maxnumcandownload = "5";
			$currentgetappform->blocks="5";
			$allwithrf = "";
			$exdesc= __('The URL of the page where the reviews or recommendations are located.', 'wp-review-slider-pro');
			$exurls = ' Ex:<br><i>https://www.vrbo.com/4169183ha</i>';
		} else if($rtype=='Yelp'){
			$hideimageoption = false;
			$hidenumtodownload = false;
			$maxnumcandownload = "";
			$currentgetappform->blocks="20";
			$currentgetappform->crawlserver="local";
			$showcrawlserveroption = true;
			$allwithrf = "";
			$exdesc= __('The URL of the page where the reviews or recommendations are located.', 'wp-review-slider-pro');
			$exurls = ' Ex:<br><i>https://www.yelp.com/biz/earth-and-stone-wood-fired-pizza-huntsville-2</i>';
		} else if($rtype=='Fresha'){
			$hideimageoption = false;
			$hidenumtodownload = false;
			$maxnumcandownload = "";
			$currentgetappform->blocks="20";
			$allwithrf = "";
			$exdesc= __('The URL of the page where the reviews or recommendations are located.', 'wp-review-slider-pro');
			$exurls = ' Ex:<br><i>https://www.fresha.com/a/morgan-company-hair-beauty-nottingham-unit-2-riverbank-business-park-uk-w3xcsptr</i>';
		} else if($rtype=='Facebook'){
			$hideimageoption = true;
			$hidenumtodownload = true;
			$maxnumcandownload = "";
			$currentgetappform->blocks="20";
			$allwithrf = "";
			$exdesc= __('Select Facebook Source Page', 'wp-review-slider-pro');
			$exurls = '';
		} else if($rtype=='Birdeye'){
			$hideimageoption = false;
			$hidenumtodownload = false;
			$maxnumcandownload = "";
			$currentgetappform->blocks="20";
			$allwithrf = "";
			$exdesc= __('Enter your Birdeye business ID. Example: 12345678', 'wp-review-slider-pro');
			$exurls = '';
		} else if($rtype=='Yotpo'){
			$hideimageoption = false;
			$hidenumtodownload = false;
			$maxnumcandownload = "";
			$currentgetappform->blocks="50";
			$allwithrf = "";
			$exdesc= __('Enter Yotpo App Key or Store ID.', 'wp-review-slider-pro').' <a href="https://support.yotpo.com/en/article/finding-your-yotpo-app-key-and-secret-key" target="_blank" id="instr" name="instr">'.__('More Info', 'wp-review-slider-pro').'</a>';
			$exurls = '';
		}  else if($rtype=='SocialClimb'){
			$hideimageoption = false;
			$hidenumtodownload = false;
			$maxnumcandownload = "";
			$currentgetappform->blocks="";
			$allwithrf = "";
			$exdesc= __('Enter your Api Key and Survey ID seperated by a comma. You can find the API Key by logging in > Settings > Preferences > dropdown Account API Key > SHOW API KEY. Survey ID can be found in the SocialClimb app on the Reviews Iframe page.', 'wp-review-slider-pro').' ';
			$exurls = '';
		} else if($rtype=='Google-Places-API'){
			$hideimageoption = false;
			$hidenumtodownload = true;
			$maxnumcandownload = "";
			$currentgetappform->blocks="10";
			$allwithrf = "If you need all your reviews please use a Review Funnel. The Google Crawl method can download 40.";
			$exdesc= __('Enter your Google Places ID. Example: ChIJ4ZQVjptsYogRuCFSf--uNBQ', 'wp-review-slider-pro');
			$exurls = '';
		} else if($rtype=='CreativeMarket'){
			$hideimageoption = false;
			$hidenumtodownload = false;
			$maxnumcandownload = "";
			$currentgetappform->blocks="20";
			$allwithrf = "";
			$exdesc= __('The URL of the page where the reviews or recommendations are located. This would be a product page.', 'wp-review-slider-pro');
			$exurls = ' Ex:<br><i>https://creativemarket.com/TheArtifexForge/3756429-Incredible-Impressionism-Brushes</i>';
		}
	
	
	//db function variables
	global $wpdb;
	$table_name = $wpdb->prefix . 'wpfb_getapps_forms';
	
//delete_option('wprev_google_crawl_check');
	//$previouscheckdelete = json_decode(get_option('wprev_google_crawl_check'),true);
	//print_r($previouscheckdelete);
	

	//form deleting and updating here---------------------------
	if(isset($_GET['taction'])){
		if(isset($_GET['tid'])){
			$tid = sanitize_text_field($_GET['tid']);
			$tid = intval($tid);
			//for deleting
			if($_GET['taction'] == "del" && $_GET['tid'] > 0){
				//security
				check_admin_referer( 'tdel_');
				//delete
				$wpdb->delete( $table_name, array( 'id' => $tid ), array( '%d' ) );
				//if this is google then need to delete out of option
				if($rtype=='Google'){
				$previouscheckdelete = json_decode(get_option('wprev_google_crawl_check'),true);
				$gettformurl  = $wpdb->get_var( "SELECT url FROM $table_name where id='".$tid."' " );
				unset($previouscheckdelete['gettformurl']);
				}
				
			}
			//for updating
			if($_GET['taction'] == "edit" && $_GET['tid'] > 0){
				//security
				check_admin_referer( 'tedit_');
				//get form array
				$currentgetappform = $wpdb->get_row( "SELECT * FROM ".$table_name." WHERE id = ".$tid );
			}
			//for copying
			if($_GET['taction'] == "copy" && $_GET['tid'] > 0){
				//security
				check_admin_referer( 'tcopy_');
				//get form array
				$currentgetappform = $wpdb->get_row( "SELECT * FROM ".$table_name." WHERE id = ".$tid );
				//add new template
				$array = (array) $currentgetappform;
				$array['title'] = $array['title'].'_copy';
				$array['reviewlistpageid'] = $array['reviewlistpageid'].'_copy';
				
				unset($array['id']);
				//print_r($array);
				//remove the id so it can be generated.
				$wpdb->insert( $table_name, $array );
				//$wpdb->show_errors();
				//$wpdb->print_error();
			}
		}
		
	}
	//------------------------------------------
	
	//template importing from CSV file--------------------
	 if(isset($_POST["Import"])){
		//print_r($_FILES);
		$filename=$_FILES["file"]["tmp_name"];		
		 if($_FILES["file"]["size"] > 0)
		 {
		  	$file = fopen($filename, "r");
			$c = 0; //use line one for column names
	        while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
	         {
				$c++;
				if($c == 1){
				//print_r($getData);
					$colarray = $getData;
				} else {
					$insertdata = array_combine($colarray, $getData);
					//remove id so it will assign another on insert
					unset($insertdata['id']);
					//insert to db here
					$wpdb->insert( $table_name, $insertdata );
				}
	         }
	         fclose($file);	
		 }
	}
	//---------------------------------------------------------------
	
	

	//form posting here--------------------------------
	//for nextdoor save cookie
	if (isset($_POST['wprevpro_savecookie']) && $rtype=="Nextdoor"){
		//verify nonce wp_nonce_field( 'wprevpro_save_template');
		check_admin_referer( 'wprevpro_save_cookie');
		$cookieval = sanitize_textarea_field($_POST['wprevpro_cookie']);
		update_option( 'wprevpro_cookieval', $cookieval );
	}
	
	//birdeye save api key
	if (isset($_POST['wprevpro_birdeyeapikey']) && $rtype=="Birdeye"){
		//verify nonce wp_nonce_field( 'wprevpro_save_template');
		check_admin_referer( 'wprevpro_birdeye_api_key');
		$apival = sanitize_textarea_field($_POST['wprevpro_birdeyeapikey']);
		update_option( 'wprevpro_birdeyeapikey_val', $apival );
		//echo "here";
		//echo get_option('wprevpro_birdeyeapikey_val');
	}
	
	//Google Places save api key
	if (isset($_POST['wprevpro_googleplacesapikey']) && $rtype=="Google-Places-API"){
		//verify nonce wp_nonce_field( 'wprevpro_save_template');
		check_admin_referer( 'wprevpro_googleplaces_api_key');
		$apival = sanitize_textarea_field($_POST['wprevpro_googleplacesapikey']);
		update_option( 'wprevpro_googleplacesapikey_val', $apival );
		//echo "here";
		//echo get_option('wprevpro_googleplacesapikey_val');
	}
	
	//yotpo save secret key yotposecretkey
	if (isset($_POST['wprevpro_yotposecretkey']) && $rtype=="Yotpo"){
		//verify nonce wp_nonce_field( 'wprevpro_save_template');
		check_admin_referer( 'wprevpro_yotpo_api_key');
		$apival = sanitize_textarea_field($_POST['wprevpro_yotposecretkey']);
		update_option( 'wprevpro_yotposecretkey_val', $apival );
		//echo "here";
		//echo get_option('wprevpro_yotposecretkey_val');
	}
	
	//saving FB access code
	if (isset($_POST['wprevpro_fb_secret_code']) && $rtype=="Facebook"){
		//verify nonce wp_nonce_field( 'wprevpro_save_template');
		check_admin_referer( 'wprevpro_save_fb_secret_code');
		$fb_secret_code = sanitize_text_field($_POST['fb_secret_code']);
		update_option( 'wprevpro_fb_secret_code', $fb_secret_code );
	}
	

	//check to see if form has been posted.
	//if template id present then update database if not then insert as new.

	if (isset($_POST['wprevpro_submittemplatebtn'])){
		//verify nonce wp_nonce_field( 'wprevpro_save_template');
		check_admin_referer( 'wprevpro_save_template');
		//get form submission values and then save or update
		$t_id = sanitize_text_field($_POST['edittid']);
		if(isset($_POST['wprevpro_template_title'])){
			$title = sanitize_text_field($_POST['wprevpro_template_title']);
		}
		if($rtype=='Google-Places-API'){
			$title = sanitize_text_field($_POST['wprevpro_template_title_gpa']);
		}
		$site_type = sanitize_text_field($_POST['wprevpro_site_type']);
		
		//saving this here now instead of creating on review download
		//$reviewlistpageid = str_replace(" ","",$title);
		//$reviewlistpageid = str_replace("'","",$reviewlistpageid);
		//$reviewlistpageid = str_replace('"',"",$reviewlistpageid);
		//$reviewlistpageid = preg_replace('/[^A-Za-z0-9\-]/', '', $reviewlistpageid)."_".$site_type;
		
		$reviewlistpageid = str_replace(" ","",$title);
		$reviewlistpageid = str_replace("'","",$reviewlistpageid);
		$reviewlistpageid = str_replace('"',"",$reviewlistpageid);
		$pattern = '/[^A-Za-z0-9\-]/';
		$pregtitle = preg_replace($pattern, '', $reviewlistpageid);
		if (function_exists('mb_strlen') && function_exists('mb_ereg_replace')) {
			if(mb_strlen($pregtitle)<3){
				$pattern = '/[^A-Za-z0-9\-\p{L}]/';
				$pregtitle = mb_ereg_replace($pattern, '', $reviewlistpageid);
			}
		}
		$reviewlistpageid = $pregtitle."_".$site_type;
			
		
		$page_id='';
		if(isset($_POST['wprevpro_template_page_id'])){
			if($rtype=='Nextdoor' || $rtype=='TrueLocal'|| $rtype=='Experience'|| $rtype=='StyleSeat'|| $rtype=='Reviews.io'){
			$page_id= sanitize_text_field($_POST['wprevpro_template_page_id']);
			}
		}
		if(isset($_POST['wprevpro_google_page_id']) && $rtype=='Google'){
			$page_id= sanitize_text_field($_POST['wprevpro_google_page_id']);
		}
		if(isset($_POST['wprevpro_url']) && $rtype=='Google-Places-API'){
			$page_id= sanitize_text_field($_POST['wprevpro_url']);
			$reviewlistpageid =$page_id;
		}
		$langcode = '';
		if(isset($_POST['wprevpro_langcode']) && $rtype=='Google-Places-API'){
			$langcode = sanitize_text_field($_POST['wprevpro_langcode']);
		}

		$rectostar ='';
		if($rtype=='Facebook'){
			//135558838733_The Ridge Adventure & Off Road Riding Park
			$page_info= sanitize_text_field($_POST['wprevpro_fb_page']);
			$page_id = strtok($page_info, '_');
			$reviewlistpageid=$page_id;
			$title = substr($page_info, strpos($page_info, "_") + 1); 
			$rectostar= sanitize_text_field($_POST['wprevpro_template_fbrecommendations']);
		}
		if($rtype=='Nextdoor'){
			$rectostar= sanitize_text_field($_POST['wprevpro_template_fbrecommendations']);
		}
		
		
		if($rtype=='Freemius' || $rtype=='Qualitelis' || $rtype=='Feefo' || $rtype=='Google' || $rtype=='Birdeye' || $rtype=='SocialClimb' || $rtype=='Yotpo' || $rtype=='Google-Places-API'){
		$url = sanitize_text_field($_POST['wprevpro_url']);
		} else {
		$url = sanitize_url($_POST['wprevpro_url']);
		//remove #REVIEWS in case this is TripAdvisor
		$url = preg_replace('/#REVIEWS$/', '', $url);
		}
		//$url=urlencode($url);
		
		$cron = sanitize_text_field($_POST['wprevpro_cron_setting']);
		$blocks = sanitize_text_field($_POST['wprevpro_blocks']);
		$blocks = intval($blocks);
		
		$last_name = sanitize_text_field($_POST['wprevpro_last_name']);
		$profile_img = sanitize_text_field($_POST['wprevpro_profile_img']);
		
		$sortoption = '';
		if(isset($_POST['wprevpro_sortoption'])){
			$sortoption = sanitize_text_field($_POST['wprevpro_sortoption']);
		}

		$timenow = time();
		
		//convert to json, function in class-wp-review-slider-pro-admin-common.php
		require_once WPREV_PLUGIN_DIR . 'admin/class-wp-review-slider-pro-admin-common.php';
		$plugin_admin_common = new Common_Admin_Functions();
		
		$catids = sanitize_text_field($_POST['wprevpro_nr_categories']);
		$catidsarrayjson ='';
		if($catids!=''){
		$catidsarrayjson = $plugin_admin_common->wprev_commastrtojson($catids,true);
		}
 
		$postid = sanitize_text_field($_POST['wprevpro_nr_postid']);
		$postidsarrayjson ='';
		if($postid!=''){
		$postidsarrayjson = $plugin_admin_common->wprev_commastrtojson($postid,true);
		}
		
		$crawlserver='';
		if(isset($_POST['wprevpro_crawl_server'])){
			$crawlserver = sanitize_text_field($_POST['wprevpro_crawl_server']);
		}
		
		//+++++++++need to sql escape using prepare+++++++++++++++++++
		//+++++++++++++++++++++++++++++++++++++++++++++++++++++
		//insert or update
			$data = array( 
				'title' => "$title",
				'reviewlistpageid' => "$reviewlistpageid",
				'page_id' => "$page_id",
				'site_type' => "$site_type",
				'created_time_stamp' => "$timenow",
				'url' => "$url",
				'cron' => "$cron",
				'blocks' => "$blocks",
				'last_name' => "$last_name",
				'profile_img' => "$profile_img",
				'categories' => "$catidsarrayjson",
				'posts' => "$postidsarrayjson",
				'rectostar' => "$rectostar",
				'sortoption' => "$sortoption",
				'langcode' => "$langcode",
				'crawlserver' => "$crawlserver",
				);
				//print_r($data);
			$format = array( 
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
				); 

		if($t_id==""){
			//print_r($data);
			//insert
			$insertrow = $wpdb->insert( $table_name, $data, $format );
			if(!$insertrow){
			//$wpdb->show_errors();
			//$wpdb->print_error();
			$dbmsg = $dbmsg.'<div id="setting-error-wprevpro_message" class="error settings-error notice is-dismissible">'.__('<p><strong>Oops! This form could not be inserted in to the database. Please try de-activating and re-activating the plugin to force the database tables to update.</br> -'.$wpdb->show_errors().' -'.$wpdb->print_error().' </strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>', 'wp-review-slider-pro').'</div>';
			}
			//die();
		} else {
			//update
			//print_r($data);
			$updatetempquery = $wpdb->update($table_name, $data, array( 'id' => $t_id ), $format, array( '%d' ));
			//$wpdb->show_errors();
			//$wpdb->print_error();
			if($updatetempquery>0){
				$dbmsg = '<div id="setting-error-wprevpro_message" class="updated settings-error notice is-dismissible"><p><strong>'.__('Form Updated!', 'wp-review-slider-pro').'</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">'.__('Dismiss this notice.', 'wp-review-slider-pro').'</span></button></div>';
				
				//see if we need to update the reviewlistpageid and pagename for any reviews that have been downloaded, based on old reviewlistpageid.
				$oldreviewlistpageid = sanitize_text_field($_POST['reviewlistpageid']);
				if($oldreviewlistpageid!=''){
					//update reviews in reviewlist table with 
					$table_name_rl = $wpdb->prefix . 'wpfb_reviews';
					$datarl = array('pageid' => "$reviewlistpageid",'pagename' => "$title");
					$formatrl = array('%s','%s');
					$updatetempqueryrl = $wpdb->update($table_name_rl, $datarl, array( 'pageid' => $oldreviewlistpageid ), $formatrl, array( '%s' ));
				}
				
			} else {
				$wpdb->show_errors();
				$wpdb->print_error();
				$dbmsg = '<div id="setting-error-wprevpro_message" class="updated settings-error notice is-dismissible"><p><strong>'.__('Error', 'wp-review-slider-pro').':</strong> '.__('Unable to update. Please contact support.', 'wp-review-slider-pro').'</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">'.__('Dismiss this notice.', 'wp-review-slider-pro').'</span></button></div>';
			}
		}
		
		
	}

	//Get list of all current forms--------------------------
	$currentforms = $wpdb->get_results("SELECT * FROM $table_name where site_type = '".$rtype."' ORDER BY id DESC");
	//-------------------------------------------------------

	
?>

<div class="wrap wp_pro-settings" style="min-height: 900px;">
	<h1><img src="<?php echo plugin_dir_url( __FILE__ ) . 'logo.png'; ?>"></h1>
	
<?php 
include("tabmenu.php");

//query args for export and import
$url_tempdownload = admin_url( 'admin-post.php?action=print_reviewfunnel.csv' );
if ( wrsp_fs()->can_use_premium_code() ) {


//header name
$headertitle = $rtype;
$headerimg = $rtype;
if($rtype=='Google-Places-API'){
	$headertitle = "Google Places API";
	$headerimg = "Google";
}
	$fileext = "png";
	//check for svg. 
	$svgarray = unserialize(WPREV_SVG_ARRAY);
	if (in_array($headerimg, $svgarray)) {
		$fileext = "svg";
	}
?>

<div class="w3-col m12">
<div class="headertype wprevpro_margin10">
<img id="reviewtypelogo" src="<?php echo WPREV_PLUGIN_URL . '/public/partials/imgs/'.strtolower($headerimg).'_small_icon.'.$fileext.'?temp='; ?>">
<span id="headertypetext"><?php echo $headertitle; ?> Reviews</span>
</div>
<div class="wprevpro_margin10">
	<a id="wprevpro_helpicon_posts" class="wprevpro_btnicononly button dashicons-before dashicons-editor-help"></a>
	<a id="wprevpro_addnewtemplate" class="button button-primary dashicons-before dashicons-plus-alt"> <?php _e('Add New Source Page', 'wp-review-slider-pro'); ?> - <?php echo $headerimg; ?></a>
<?php
if($rtype=='Facebook'){
?>
	<a id="wprevpro_addfbcode" class="button dashicons-before dashicons-plus-alt"><?php _e('Enter/Modify Access Code', 'wp-review-slider-pro'); ?></a>
<?php
}	
?>

<?php
	//$previouscheck = json_decode(get_option('wprev_google_crawl_check'),true);
	//print_r($previouscheck);
?>
</div>
<?php
if($rtype=='Facebook'){
	$wprevpro_fb_secret_code = get_option('wprevpro_fb_secret_code');
	if(isset($wprevpro_fb_secret_code) && $wprevpro_fb_secret_code!=''){
		$acesscode = $wprevpro_fb_secret_code;
	} else {
		$acesscode ='';
	}
?>
<div class="wprevpro_margin10 bordered_form" id="fb_secret_code_div" <?php if($acesscode!=''){echo "style='display:none;'";} ?>>
	    <form  action="?page=wp_pro-get_apps&rtype=Facebook" method="post" name="fbsecretcode" enctype="multipart/form-data">
		<b>Secret Access Code:</b>
		<table class="wprevpro_margin10 ">
		<tbody>
			<tr class="wprevpro_row">
			<td scope="row" style="">
			<p class="description">
			<?php _e('The first thing you need to do is grant our Facebook app permission to read your Facebook Page reviews and then copy the access code from our app and paste it in to the field below.', 'wp-review-slider-pro'); ?></p>
			<p class="description">
			<?php _e('Designers/Developers: If you are setting this up for a client, it is recommended that you delete your Secret Access Code from the plugin after you download the reviews.', 'wp-review-slider-pro'); ?></p>
			<a href="https://fbapp.ljapps.com/login.php?ut=pd" target="_blank" id="instr" name="instr" class="button-secondary "><?php _e('Get Access Code Here', 'wp-review-slider-pro'); ?></a>
&nbsp;&nbsp;<a href="https://wpreviewslider.com/wp-content/uploads/2022/08/fbapiinstructions.mp4" target="_blank" id="instr" name="instr" class="button-secondary "><?php _e('Video Instructions', 'wp-review-slider-pro'); ?></a>
			</td>
			</tr>
			<tr class="wprevpro_row">
				<td scope="row">
				<br>
				<b>Enter Access Code:</b> <input name="fb_secret_code" id="fb_secret_code" spellcheck="false" value="<?php echo $acesscode; ?>">
				</td>
			</tr>
			</tbody>
			</table>
				<?php 
	//security nonce
	wp_nonce_field( 'wprevpro_save_fb_secret_code');
	?>
			<input type="submit" name="wprevpro_fb_secret_code" id="wprevpro_fb_secret_code" class="button button-primary" value="<?php _e('Save', 'wp-review-slider-pro'); ?>">
			&nbsp;&nbsp;
        </form>
</div>
<?php
}
?>

<?php
if($rtype=='Nextdoor'){
	/*
?>
<div class="wprevpro_margin10 bordered_form" id="login_cookie">
	    <form  action="?page=wp_pro-get_apps&rtype=Nextdoor" method="post" name="logincookie" enctype="multipart/form-data">
		<b>Nextdoor Cookie:</b>
		<table class="wprevpro_margin10 ">
		<tbody>
			<tr class="wprevpro_row">
				<td scope="row">
				<textarea name="wprevpro_cookie" id="wprevpro_cookie" cols="50" rows="4" spellcheck="false"><?php echo get_option('wprevpro_cookieval'); ?></textarea>
				</td>
				<td scope="row" style="padding-left:10px;">
			<p class="description">
			<?php _e('Nextdoor requires you to be logged in to see recommendations. The plugin will use this cookie value to identify your account with Nextdoor. Follow the steps in the video instructions to obtain the cookie value.', 'wp-review-slider-pro'); ?></p>
			<p class="description">
			<?php _e('<b>Note:</b> This cookie may expire. If it does, then you\'ll need to enter a new one.', 'wp-review-slider-pro'); ?></p>
			</td>
			</tr>
			</tbody>
			</table>
				<?php 
	//security nonce
	wp_nonce_field( 'wprevpro_save_cookie');
	?>
			<input type="submit" name="wprevpro_savecookie" id="wprevpro_savecookie" class="button button-primary" value="<?php _e('Save', 'wp-review-slider-pro'); ?>">
&nbsp;&nbsp;<a href="https://wpreviewslider.com/wp-content/uploads/2022/12/Nextdoor_cookie_12-2-2022.mp4" target="_blank" id="instr" name="instr" class="button-secondary "><?php _e('Video Instructions', 'wp-review-slider-pro'); ?></a>
        </form>
</div>
<?php
*/
}
?>

<?php
if($rtype=='Birdeye'){
?>
<div class="wprevpro_margin10 bordered_form" id="login_cookie">
	    <form  action="?page=wp_pro-get_apps&rtype=Birdeye" method="post" name="logincookie" enctype="multipart/form-data">
		<b>Birdeye API Key:</b>
		<table class="wprevpro_margin10 ">
		<tbody>
			<tr class="wprevpro_row">
				<td scope="row">
				<input name="wprevpro_birdeyeapikey" id="wprevpro_birdeyeapikey" value="<?php echo get_option('wprevpro_birdeyeapikey_val'); ?>" type="text">
				</td>
				<td scope="row" style="padding-left:10px;">
			<p class="description">
			<?php _e('Get your API Key from Birdeye and enter it here to use their API.', 'wp-review-slider-pro'); ?> <a href="https://support.birdeye.com/setup-locations/1205154-where-can-i-find-my-account-s-unique-business-id" target="_blank" id="instr" name="instr"><?php _e('API info on Birdeye', 'wp-review-slider-pro'); ?></a></p>
			</td>
			</tr>
			</tbody>
			</table>
				<?php 
	//security nonce
	wp_nonce_field( 'wprevpro_birdeye_api_key');
	?>
<input type="submit" name="wprevpro_savecookie" id="wprevpro_savecookie" class="button button-primary" value="<?php _e('Save', 'wp-review-slider-pro'); ?>">
        </form>
</div>
<?php
}
?>
<?php
if($rtype=='Google-Places-API'){
?>
<div class="wprevpro_margin10 bordered_form" id="login_cookie">
	    <form  action="?page=wp_pro-get_apps&rtype=Google-Places-API" method="post" name="logincookie" enctype="multipart/form-data">
		<b>Google Places API Key:</b>
		<table class="wprevpro_margin10 ">
		<tbody>
			<tr class="wprevpro_row">
				<td scope="row">
				<input name="wprevpro_googleplacesapikey" id="wprevpro_googleplacesapikey" value="<?php echo get_option('wprevpro_googleplacesapikey_val'); ?>" type="text"><button id="wpfbr_testgoogleplaceskey" type="button" class="button">Test API Key</button>
				</td>
				<td scope="row" style="padding-left:10px;">
			<p class="description">
			<?php _e('Get your API Key from Google and enter it here to use their API.', 'wp-review-slider-pro'); ?> <a href="https://wpreviewslider.com/google-places-api-key/" target="_blank" id="instr" name="instr"><?php _e('Instructions', 'wp-review-slider-pro'); ?></a></p>
			</td>
			</tr>
			</tbody>
			</table>
				<?php 
	//security nonce
	wp_nonce_field( 'wprevpro_googleplaces_api_key');
	?>
<input type="submit" name="wprevpro_savecookie" id="wprevpro_savecookie" class="button button-primary" value="<?php _e('Save', 'wp-review-slider-pro'); ?>">
        </form>
</div>
<?php
}
?>
<?php
if($rtype=='Yotpo'){
?>
<div class="wprevpro_margin10 bordered_form" id="login_cookie">
	    <form  action="?page=wp_pro-get_apps&rtype=Yotpo" method="post" name="logincookie" enctype="multipart/form-data">
		<b>Yotpo Secret Key:</b>
		<table class="wprevpro_margin10 ">
		<tbody>
			<tr class="wprevpro_row">
				<td scope="row">
				<input name="wprevpro_yotposecretkey" id="wprevpro_yotposecretkey" value="<?php echo get_option('wprevpro_yotposecretkey_val'); ?>" type="text">
				</td>
				<td scope="row" style="padding-left:10px;">
			<p class="description">
			<?php _e('Get your Secret Key from Yotpo and enter it here to use their API.', 'wp-review-slider-pro'); ?> <a href="https://support.yotpo.com/en/article/finding-your-yotpo-app-key-and-secret-key#retrieving-your-secret-key" target="_blank" id="instr" name="instr"><?php _e('How to find it.', 'wp-review-slider-pro'); ?></a></p>
			</td>
			</tr>
			</tbody>
			</table>
				<?php 
	//security nonce
	wp_nonce_field( 'wprevpro_yotpo_api_key');
	?>
<input type="submit" name="wprevpro_savecookie" id="wprevpro_savecookie" class="button button-primary" value="<?php _e('Save', 'wp-review-slider-pro'); ?>">
        </form>
</div>
<?php
}
?>

<?php

} else {
	echo '<div class="wprevpro_margin10"> ';
	printf( __( '%s reviews are a Premium feature. Please upgrade.', 'wp-review-slider-pro' ) , $rtype );
	echo '</div>';
}


//$previouscheck = json_decode(get_option('wprev_google_crawl_check'),true);
//print("<pre>".print_r($previouscheck,true)."</pre>");
//delete_option('wprev_google_crawl_check');
?>

  <div class="wprevpro_margin10" id="wprevpro_new_template">
<form name="newtemplateform" id="newtemplateform" action="?page=wp_pro-get_apps&rtype=<?php echo $rtype; ?>" method="post">
	<table class="wprevpro_margin10 form-table ">
		<tbody>
			<?php
			if($rtype=='Facebook'){
			?>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php 
						_e('Select Facebook Page:', 'wp-review-slider-pro'); 
					?>
				</th>
				<td>
				<?php
				//print_r($currentgetappform);
				$temppageval = '';
				if($currentgetappform->page_id!=''){
					$temppageval = $currentgetappform->page_id."_".$currentgetappform->title;
				}
				?>
					<select name="wprevpro_fb_page" id="wprevpro_fb_page">
					<option value="<?php echo $temppageval; ?>"><?php echo $currentgetappform->title; ?></option>
					</select>
					<p class="description">
					<?php
					printf( __( 'The Facebook page to download reviews from.', 'wp-review-slider-pro' ), $rtype );
					?>
					</p>
					<div id="pageslisterror"></div>
				</td>
			</tr>
			<?php
			}
			if($rtype!='Facebook' && $rtype!='Google-Places-API'){
			?>
			<tr class="wprevpro_row">
				<th scope="row">
				
					<?php 
					if($rtype=='Freemius'){
						_e('Plugin or Theme Name:', 'wp-review-slider-pro');
					} else {
						_e('Place or Location Name:', 'wp-review-slider-pro'); 
					}
					?>
				</th>
				<td>
					<input id="wprevpro_template_title" data-custom="custom" type="text" name="wprevpro_template_title" placeholder="" value="<?php echo stripslashes($currentgetappform->title); ?>" required style="width: 350px;" class="<?php if($rtype=='Google-Places-API'){echo 'golocsearch';} ?>"><span id="titleerrmsg"></span>
					<p class="description">
					<?php
					printf( __( 'Enter a unique name for these %s reviews. This would normally be the name of what the reviews are talking about.', 'wp-review-slider-pro' ), $rtype );
					?>		
					</p>
				</td>
			</tr>
			<?php
			}
			if($rtype=='Google-Places-API'){
			?>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php 
					_e('Type to Find Location:', 'wp-review-slider-pro');
					?>
				</th>
				<td>
					<input id="wprevpro_template_title_gpa" data-custom="custom" type="text" name="wprevpro_template_title_gpa" placeholder="" value="<?php echo stripslashes($currentgetappform->title); ?>" required style="width: 350px;" class="golocsearch"><span id="titleerrmsg"></span>
					<p class="description">
					<?php
						echo '<input id="wprevpro_google_location_name" data-custom="custom" type="hidden" name="wprevpro_google_location_name" placeholder="" value="" class="">';
						echo sprintf(__('If you have problems searching for your business then manually input the name and then enter the Place Id below. Look them up and copy them from the map on this %spage%s.', 'wp-review-slider-pro'),'<a href="https://developers.google.com/maps/documentation/javascript/examples/places-placeid-finder" target="_blank">','</a>');
					?>		
					</p>
				</td>
			</tr>
			<?php
			}
			if($rtype=='Nextdoor'){
				/*
			?>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Nextdoor Page_ID:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<input id="wprevpro_template_page_id" data-custom="custom" type="text" name="wprevpro_template_page_id" placeholder="" value="<?php echo $currentgetappform->page_id; ?>" required>&nbsp;&nbsp;<a href="https://wpreviewslider.com/wp-content/uploads/2022/12/Nextdoor_business_ID_12-2-2022.mp4" target="_blank" id="instr" name="instr" class="button-secondary "><?php _e('Video Instructions', 'wp-review-slider-pro'); ?></a>
					<p class="description">
					<?php
					printf( __( 'Follow the video instructions to find the Page_ID.', 'wp-review-slider-pro' ), $rtype );
					?>		</p>
				</td>
			</tr>
			<?php
			*/
			}
			?>
			<tr class="wprevpro_row" style='display:none;'>
				<th scope="row">
					<?php _e('Choose Review Site:', 'wp-review-slider-pro'); ?>
				</th>
				<td><div id="divsitetype">
						<select name="wprevpro_site_type" id="wprevpro_site_type">
						<option value="<?php echo $rtype;?>" <?php if($currentgetappform->site_type==$rtype){echo "selected";} ?>><?php echo $rtype;?></option>
						</select>
					</div>
					<p class="description">
					<?php _e('This is the app store you are downloading the reviews from.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
			<tr class="wprevpro_row" <?php if($rtype=='Facebook'){echo "style='display:none;'"; } ?> >
				<th scope="row">
					<?php 
					if($rtype=='Freemius'){
						_e('Plugin ID, Public Key, Secret Key', 'wp-review-slider-pro');
					} else if($rtype=='Qualitelis'){
						_e('Token, IdContractor, CycleId, SurveyId, Langue', 'wp-review-slider-pro');
					} else if($rtype=='Feefo'){
						_e('Merchant Identifier', 'wp-review-slider-pro');
					} else if($rtype=='Google'){
						_e('Place ID or Search Terms', 'wp-review-slider-pro');
					} else if($rtype=='Birdeye'){
						_e('Business ID', 'wp-review-slider-pro');
					} else if($rtype=='Google-Places-API'){
						_e('Google Place ID', 'wp-review-slider-pro');
					} else if($rtype=='Yotpo'){
						_e('App Key or Store ID', 'wp-review-slider-pro');
					} else if($rtype=='SocialClimb'){
						_e('Api Key, Survey ID', 'wp-review-slider-pro');
					} else {
						_e('Review URL', 'wp-review-slider-pro'); 
					}
					?>
				</th>
				<td><?php
					if($rtype=='Birdeye' || $rtype=='Yotpo' || $rtype=='SocialClimb'){
					?>
						<input class="yelp_business_url" id="wprevpro_url" data-custom="custom" type="text" name="wprevpro_url" placeholder="" value="<?php echo $currentgetappform->url; ?>" required>
						<p class="description">
						<?php
						_e($exdesc.$exurls, 'wp-review-slider-pro'); 
						?>
					<?php
					} else if($rtype=='Google-Places-API'){
					?>
						<input style="width: 350px;" class="yelp_business_url" id="wprevpro_url" data-custom="custom" type="text" name="wprevpro_url" placeholder="" value="<?php echo $currentgetappform->url; ?>" required>
						<p class="description">
					
					<?php
						//_e('Enter your Birdeye business ID. Example: 12345678', 'wp-review-slider-pro');
						_e($exdesc.$exurls, 'wp-review-slider-pro'); 
						
					} else if($rtype=='Freemius'){
					?>
						<input class="yelp_business_url" id="wprevpro_url" data-custom="custom" type="text" name="wprevpro_url" placeholder="" value="<?php echo $currentgetappform->url; ?>" required>
						<p class="description">
					
					<?php
						_e('Enter a comma separated list of your Plugin ID, Public Key, Secret Key in that order. They can be found by logging in to Freemius, click the plugin, go to Settings > Keys.', 'wp-review-slider-pro');
						
					} else if($rtype=='Qualitelis'){
					?>
						<input class="yelp_business_url" id="wprevpro_url" data-custom="custom" type="text" name="wprevpro_url" placeholder="" value="<?php echo $currentgetappform->url; ?>" required>
						<p class="description">
					
					<?php
						_e('Enter a comma separated list of your Token, IdContractor, CycleId, SurveyId, Langue in that order. They can be found by logging in to https://www.qualitelis-survey.com/. The CycleID, SurveyId, and Langue are optional. If you leave them out do so like this: xxxxtokenxxxx, xxxxIdContractorxxxx,,,xxxLanguexxx', 'wp-review-slider-pro');


					} else if($rtype=='Feefo'){
					?>
						<input class="yelp_business_url" id="wprevpro_url" data-custom="custom" type="text" name="wprevpro_url" placeholder="" value="<?php echo $currentgetappform->url; ?>" required>
						<p class="description">
					<?php	
						echo sprintf(__( 'Enter your merchant_identifier. Go %1$shere%2$s for more info.', 'wp_fb-reviews' ), 
						'<a href="https://support.feefo.com/support/solutions/articles/8000041003-reviews-api-parameter-merchant-identifier" target="_blank">', 
						'</a>'
						);
					} else if($rtype=='Google'){
					?>
						<input class="yelp_business_url" id="wprevpro_url" data-custom="custom" type="text" name="wprevpro_url" placeholder="<?php echo $exurlsplaceholder;?>" value="<?php echo stripslashes($currentgetappform->url); ?>" required>
						<input style="display:none;" class="yelp_business_url" id="wprevpro_google_page_id" data-custom="custom" type="text" name="wprevpro_google_page_id" placeholder="" value="<?php echo $currentgetappform->page_id; ?>">
						<button id="savetest" type="button" class="button " style="width:120px">Save &amp; Test &nbsp; ❯</button>
						<div id="buttonloader" style="display:none;" class="wprevloader"></div>
						<p class="description">
					<?php	
						_e($exdesc.$exurls, 'wp-review-slider-pro'); 
					} else if($rtype!='Facebook'){
					?>
						<input class="yelp_business_url" id="wprevpro_url" data-custom="custom" type="url" name="wprevpro_url" placeholder="<?php echo $exurlsplaceholder;?>" value="<?php echo $currentgetappform->url; ?>" required>
						<p class="description">
					<?php
						_e($exdesc.$exurls, 'wp-review-slider-pro'); 
					}
					?>
					</p>
					<?php
					if($rtype=='Google'){
						//delete_option('wprev_google_crawl_check');
						$previouscheck = json_decode(get_option('wprev_google_crawl_check'),true);
						//print_r($previouscheck);
						$tempplace = $currentgetappform->url;
						if(!isset($previouscheck[$tempplace])){
							$previouscheck[$tempplace]['foundplaceid']='';
							$previouscheck[$tempplace]['img']='';
							$previouscheck[$tempplace]['businessname']='';
							$previouscheck[$tempplace]['website']='';
							$previouscheck[$tempplace]['rating']='';
							$previouscheck[$tempplace]['totalreviews']='';
							$previouscheck[$tempplace]['googleurl']='';
							
						}
					?>
					<div id='divgoogletestresults' <?php if(!isset($previouscheck[$tempplace]['foundplaceid']) || $previouscheck[$tempplace]['foundplaceid']==''){echo 'style="display:none;"';} ?> class="w3-row">
					  <div class="mt10">
						<div id='googletestresults' <?php if($previouscheck[$tempplace]['foundplaceid']==''){echo 'style="display:none;"';} ?>>
							<div class="">
								<div class="w3-container">
									<div class="w3-row" style="margin-bottom: 10px;">
									  <div class="w3-col" style="width:85px;text-align: center;padding-right: 10px;"><img id='businessimg' src="<?php if($previouscheck[$tempplace]['img']!=''){echo $previouscheck[$tempplace]['img'];} else {echo WPREV_PLUGIN_URL."/admin/partials/branding-google-badge_50.png";} ?>" alt="location logo" class="w3-circle"></div>
									  <div class="w3-rest"><p><strong id='businessname'><?php if($previouscheck[$tempplace]['businessname']!=''){echo $previouscheck[$tempplace]['businessname'];} ?></strong><br>
										  <span id='website'><?php if($previouscheck[$tempplace]['website']!=''){echo $previouscheck[$tempplace]['website'];} ?></span><br>
										  <span id='reviewtext'><?php if($previouscheck[$tempplace]['rating']!=''){echo 'Rated <b>'.$previouscheck[$tempplace]['rating'].'</b> out of <b>'.$previouscheck[$tempplace]['totalreviews'].'</b>';} ?></span><br>
										  <a id='googleurl' href='<?php if($previouscheck[$tempplace]['googleurl']!=''){echo $previouscheck[$tempplace]['googleurl'];} ?>' target="_blank"><?php if($previouscheck[$tempplace]['googleurl']!=''){echo $previouscheck[$tempplace]['googleurl'];} ?></a>
										</p>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div id='googletestresultserror' style="display:none;" class="w3-panel w3-pale-red w3-display-container w3-border">
							  <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">×</span>
							  <p id='googletestresultserrortext'></p>
						</div> 
					  </div>
					</div>
					<?php
					}
					?>
				</td>
			</tr>
			<?php
			if($rtype=='Facebook' || $rtype=='Nextdoor'){
			?>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Recommendations:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
				<input type="checkbox" id="wprevpro_template_fbrecommendations" name="wprevpro_template_fbrecommendations" value="1" <?php if($currentgetappform->rectostar=="1"){echo "checked"; }?>>
				&nbsp;<?php _e('Save Positive Recommendations as 5 Star and Negative as 2 Star.', 'wp-review-slider-pro'); ?>
				<p class="description">
					<?php _e('This will allow you to display the stars with the review.', 'wp-review-slider-pro'); ?>
				</p>
				</td>
			</tr>
			<?php
			}
			?>
			<?php
			if($rtype=='StyleSeat'){
			?>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('StyleSeat API URL:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<input id="wprevpro_template_page_id" data-custom="custom" type="text" name="wprevpro_template_page_id" placeholder="" value="<?php echo $currentgetappform->page_id; ?>" required>&nbsp;&nbsp;<a href="https://ljapps.com/wp-content/uploads/2021/11/styleseat.mp4" target="_blank" id="instr" name="instr" class="button-secondary "><?php _e('Video Instructions Using Chrome', 'wp-review-slider-pro'); ?></a>
					<p class="description">
					<?php
					$tempmsg = 'Ex: <i>https://www.styleseat.com/api/v2/providers/1002638/ratings?page=1&exclude_star_only=true</i><br>';
					echo $tempmsg;
					?>
					</p>
				</td>
			</tr>
			<?php
			}
			?>
			<?php
			if($rtype=='TrueLocal'){
			?>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('TrueLocal API URL:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<input id="wprevpro_template_page_id" data-custom="custom" type="text" name="wprevpro_template_page_id" placeholder="" value="<?php echo $currentgetappform->page_id; ?>" required>&nbsp;&nbsp;<a href="https://ljapps.com/wp-content/uploads/2021/10/truelocal_api_url.mp4" target="_blank" id="instr" name="instr" class="button-secondary "><?php _e('Video Instructions Using Chrome', 'wp-review-slider-pro'); ?></a>
					<p class="description">
					<?php
					$tempmsg = 'Ex: <i>https://api.truelocal.com.au/rest/listings/B80F0EF2-83C6-4D62-A7C9-FE73A4570666/reviews?order=desc&sort=date&offset=0&limit=50&&passToken=V0MxbDBlV2VNUw==</i><br>';
					echo $tempmsg;
					?>
					</p>
				</td>
			</tr>
			<?php
			}
			if($rtype=='Google-Places-API'){
			?>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Language Code', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<input id="wprevpro_langcode" data-custom="custom" type="text" name="wprevpro_langcode" placeholder="" value="<?php echo $currentgetappform->langcode; ?>">
					<p class="description">
					<?php _e('Optional: Indicate in which language the results should be returned, if possible.', 'wp-review-slider-pro'); ?>
					<a href="https://developers.google.com/maps/faq#languagesupport" target="_blank"><?php _e('Language Codes', 'wp-review-slider-pro'); ?></a>
					</p>
				</td>
			</tr>
			<?php
			}
			?>
			<?php
			if($rtype=='Google' || $rtype=='Google-Places-API'){
			?>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Which reviews?', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<input type="radio" name="wprevpro_sortoption" value="relevant" <?php if($currentgetappform->sortoption=='relevant' || $currentgetappform->sortoption==''){echo "checked";} ?>><?php _e('Most Relevant', 'wp-review-slider-pro'); ?>&nbsp;&nbsp;&nbsp;
					<input type="radio" name="wprevpro_sortoption" value="newest" <?php if($currentgetappform->sortoption=='newest'){echo "checked";} ?>><?php _e('Newest', 'wp-review-slider-pro'); ?>&nbsp;&nbsp;&nbsp;
					<?php
					if($rtype=='Google-Places-API'){
					?>
					<input type="radio" name="wprevpro_sortoption" value="both" <?php if($currentgetappform->sortoption=='both'){echo "checked";} ?>><?php _e('Both', 'wp-review-slider-pro'); ?>
					<?php
					}
					?>
					<p class="description">
					<?php _e('Which reviews would you like to download?', 'wp-review-slider-pro'); ?> 
					<?php if($rtype=='Google'){ _e('Newest may not always work, it will fallback to Most Relevant.', 'wp-review-slider-pro');} ?>
					<?php if($rtype=='Google-Places-API'){ _e('This will download your Newest 5 or Most Helpful 5. "Both" will download 10 reviews. If you need more use the Google Crawl or Review Funnel.', 'wp-review-slider-pro');} ?></p>
				</td>
			</tr>
			<?php
			}
			?>
			<?php
			if($rtype!='SocialClimb'){
			?>
			<tr class="wprevpro_row" <?php if($hidenumtodownload){echo "style='display:none;'";}?>>
				<th scope="row">
					<?php _e('Number of Reviews', 'wp-review-slider-pro'); ?>
				</th>
				<td><div id="divsitetype">
						<input class="" style="width: 70px;" id="wprevpro_blocks" data-custom="custom" type="number" name="wprevpro_blocks" placeholder="" max="<?php echo $maxnumcandownload; ?>" value="<?php echo $currentgetappform->blocks; ?>" >
					</div>
					<p class="description">
					<?php _e('The number of reviews you wish to download.', 'wp-review-slider-pro'); 
					if($maxnumcandownload>1){
						_e(' Max of ', 'wp-review-slider-pro'); 
						echo '<b>'.$maxnumcandownload.' reviews</b>. '.$allwithrf;
					}
					?></p>
				</td>
			</tr>
			<?php
			}
			?>
			<tr class="wprevpro_row" <?php if($hidelastnameoption){echo 'style="display:none;"';} ?>>
				<th scope="row">
					<?php _e('Last Name Save Option', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<input type="radio" name="wprevpro_last_name" value="full" <?php if($currentgetappform->last_name=='full' || $currentgetappform->last_name==''){echo "checked";} ?>><?php _e('Full Last Name', 'wp-review-slider-pro'); ?>&nbsp;&nbsp;&nbsp;
					<input type="radio" name="wprevpro_last_name" value="initial" <?php if($currentgetappform->last_name=='initial' ){echo "checked";} ?>><?php _e('Initial Only', 'wp-review-slider-pro'); ?>&nbsp;&nbsp;&nbsp;
					<input type="radio" name="wprevpro_last_name" value="nothing" <?php if($currentgetappform->last_name=='nothing' ){echo "checked";} ?>><?php _e('Nothing', 'wp-review-slider-pro'); ?>
					<p class="description">
					<?php _e('Set this to change the way the last name is saved in your database. You can also hide the last name when creating a review template.', 'wp-review-slider-pro'); ?>		</p>
				</td>
			</tr>
			<tr class="wprevpro_row" <?php if($hideimageoption){echo "style='display:none;'";}?>>
				<th scope="row">
					<?php _e('Local Profile Images', 'wp-review-slider-pro'); ?>
				</th>
				<td>
				<?php
				//set default local download
				$checklocaldownloadno = "";
				$checklocaldownloadyes = "";
				if($currentgetappform->profile_img==''){
					if($rtype=='Google'){
						$checklocaldownloadyes = "checked";
					} else {
						$checklocaldownloadno = "checked";
					}
				} else if($currentgetappform->profile_img=='no'){
					$checklocaldownloadno = "checked";
				} else if($currentgetappform->profile_img=='yes'){
					$checklocaldownloadyes = "checked";
				}
				?>
					<input type="radio" name="wprevpro_profile_img" value="no" <?php echo $checklocaldownloadno; ?>><?php _e('No', 'wp-review-slider-pro'); ?>&nbsp;&nbsp;&nbsp;
					<input type="radio" name="wprevpro_profile_img" value="yes" <?php echo $checklocaldownloadyes; ?>><?php _e('Yes', 'wp-review-slider-pro'); ?>&nbsp;&nbsp;&nbsp;
					<p class="description">
					<?php 
					_e('By default, avatar images are referenced from the original review site. Set this to yes if you would like the plugin to try and save the profile images locally. This may not always work as the remote site might block the download. ', 'wp-review-slider-pro'); 
					if($rtype=='Google'){
						_e('Recommend you turn this on for Google as profile images can expire.', 'wp-review-slider-pro'); 
					}
					?></p>
				</td>
			</tr>

			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Auto Download Reviews', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<select name="wprevpro_cron_setting" id="wprevpro_cron_setting">
					<option value="" <?php if($currentgetappform->cron==''){echo "selected";} ?>><?php _e('No', 'wp-review-slider-pro'); ?></option>
					<option value="672" <?php if($currentgetappform->cron=='672'){echo "selected";} ?>><?php _e('Once a Month', 'wp-review-slider-pro'); ?></option>
					<option value="336" <?php if($currentgetappform->cron=='336'){echo "selected";} ?>><?php _e('Every 14 Days', 'wp-review-slider-pro'); ?></option>
					<option value="168" <?php if($currentgetappform->cron=='168'){echo "selected";} ?>><?php _e('Every 7 Days', 'wp-review-slider-pro'); ?></option>
					<option value="48" <?php if($currentgetappform->cron=='48'){echo "selected";} ?>><?php _e('Every Other Day', 'wp-review-slider-pro'); ?></option>
					<option value="24" <?php if($currentgetappform->cron=='24'){echo "selected";} ?>><?php _e('Once a Day', 'wp-review-slider-pro'); ?></option>
					</select>
					<p class="description">
					<?php _e('Automatically request a new scrape job and download the reviews.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Post Categories:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<input class="wprevpro_nr_categories" id="wprevpro_nr_categories" data-custom="custom" type="text" name="wprevpro_nr_categories" placeholder="" value="<?php echo $this->wprev_jsontocommastr($currentgetappform->categories); ?>">
					<span class="description"><a id="wprevpro_btn_pickcats" class="button dashicons-before dashicons-yes "><?php _e('Select Categories', 'wp-review-slider-pro'); ?></a>
					<?php _e('Optional: Single or comma separated list of post category IDs. Allows you to associate the reviews with post categories as they are downloaded. You can then use the Category filter for the template. ex: 1,3,5', 'wp-review-slider-pro'); ?>		</span>
					<div id="tb_content_cat_select" style="display:none;">
						<div id="tb_content_cat_search"><input id="tb_content_cat_search_input" data-custom="custom" type="text" name="tb_content_cat_search_input" placeholder="Type here to search..." value=""></div>
						<div class="wprev_loader_catlist" style="display:none;"></div>
						<table id="selectcatstable" class="wp-list-table widefat striped posts">
						</table>
					</div>
				</td>
			</tr>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Post IDs:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<input class="wprevpro_nr_postid" id="wprevpro_nr_postid" data-custom="custom" type="text" name="wprevpro_nr_postid" placeholder="" value="<?php echo $this->wprev_jsontocommastr($currentgetappform->posts); ?>" >
					<span class="description"><a id="wprevpro_btn_pickpostids" class="button dashicons-before dashicons-yes "><?php _e('Select Post IDs', 'wp-review-slider-pro'); ?></a>
					<?php _e('Optional: Single or comma separated list of post IDs. Allows you to associate the reviews with multiple posts or page IDs when they are downloaded. You can then use the Post filter for the template. ex: 11', 'wp-review-slider-pro'); ?>		</span>
				</td>
			</tr>
			<?php
			if($showcrawlserveroption){
			?>
			<tr class="wprevpro_row" <?php if(!$showcrawlserveroption){echo 'style="display:none;"';} ?>>
				<th scope="row">
					<?php 
					_e('Crawl Server', 'wp-review-slider-pro'); 
					//for old forms.
					if ($currentgetappform->id > 0 && $currentgetappform->crawlserver==''){
						if($rtype=="TripAdvisor" || $rtype=="Yelp" ){
							$currentgetappform->crawlserver='remote';
						} else if($rtype=="Zillow"){
							$currentgetappform->crawlserver='local';
						}
					}
					?>
				</th>
				<td>
					<select name="wprevpro_crawl_server" id="wprevpro_crawl_server">
					<option value="local" <?php if($currentgetappform->crawlserver=='local'){echo "selected";} ?>><?php _e('Local', 'wp-review-slider-pro'); ?></option>
					<option value="remote" <?php if($currentgetappform->crawlserver=='remote'){echo "selected";} ?>><?php _e('Remote', 'wp-review-slider-pro'); ?></option>
					</select>
					<p class="description">
					<?php _e('Use your local WordPress server or our remote server to crawl for the reviews. If one method has trouble then try the other. Try local first.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
			<?php
			}
			?>

		</tbody>
	</table>
	<?php 
	//security nonce
	wp_nonce_field( 'wprevpro_save_template');
	?>
	<input type="hidden" name="edittid" id="edittid"  value="<?php echo $currentgetappform->id; ?>">
	<input type="hidden" name="reviewlistpageid" id="reviewlistpageid"  value="<?php echo $currentgetappform->reviewlistpageid; ?>">
	<input type="submit" name="wprevpro_submittemplatebtn" id="wprevpro_submittemplatebtn" class="button button-primary" value="<?php _e('Save', 'wp-review-slider-pro'); ?>">
	<a id="wprevpro_addnewtemplate_cancel" class="button button-secondary"><?php _e('Cancel', 'wp-review-slider-pro'); ?></a>
	</form>
</div>
  

<?php

//display message
echo $dbmsg;

$templangcodeheader = "";
if($rtype=='Google-Places-API'){
	$templangcodeheader = '<th scope="col" width="80px" class="manage-column">'.__('Language Code', 'wp-review-slider-pro').'</th>';
}

		$html .= '
		<table class="wp-list-table widefat striped posts">
			<thead>
				<tr>
					<th scope="col" width="40px" class="manage-column">'.__('ID', 'wp-review-slider-pro').'</th>
					<th scope="col" class="manage-column">'.__('Title <br>URL or Query', 'wp-review-slider-pro').'</th>
					'.$templangcodeheader.'
					<th scope="col" width="80px" class="manage-column">'.__('Auto Download', 'wp-review-slider-pro').'</th>
					<th scope="col" width="80px" class="manage-column">'.__('Download Method', 'wp-review-slider-pro').'</th>
					<th scope="col" width="115px" class="manage-column">'.__('Last Ran', 'wp-review-slider-pro').'</th>
					<th scope="col" width="390px" class="manage-column">'.__('Action', 'wp-review-slider-pro').'</th>
				</tr>
				</thead>
			<tbody id="appformstable">';
	if(count($currentforms)>0){
	foreach ( $currentforms as $currentform ) 
	{
		//print_r($currentform);
	//remove query args we just used
	$urltrimmed = remove_query_arg( array('taction', 'id') );
		$tempeditbtn =  add_query_arg(  array(
			'taction' => 'edit',
			'tid' => "$currentform->id",
			),$urltrimmed);
			
		$url_tempeditbtn = wp_nonce_url( $tempeditbtn, 'tedit_');
			
		$tempdelbtn = add_query_arg(  array(
			'taction' => 'del',
			'tid' => "$currentform->id",
			),$urltrimmed) ;
			
		$url_tempdelbtn = wp_nonce_url( $tempdelbtn, 'tdel_');
		
						//for copying
		$tempcopybtn = add_query_arg(  array(
			'taction' => 'copy',
			'tid' => "$currentform->id",
			),$urltrimmed) ;
		$url_tempcopybtn = wp_nonce_url( $tempcopybtn, 'tcopy_');
		$tempblocks = '';
		if($currentform->blocks>0){
			$tempblocks = ($currentform->blocks);
		}
			
		$tempurlhtml = '';
		if($currentform->url!=''){
			$tempurlhtml = substr(urldecode($currentform->url),0,190);
			if(strlen(urldecode($currentform->url))>200){
				$tempurlhtml = $tempurlhtml ."...";
			}
		}
		$lastranon = '';
		if($currentform->last_ran>0){$lastranon = date("M j, Y",$currentform->last_ran);}
		
		$getreviewsbtn = '<span class="rfbtn button button-primary dashicons-before dashicons-star-filled retreviewsbtn"> '.__('Get Reviews', 'wp-review-slider-pro').'</span>';
		
		if($rtype=='Facebook'){
			$getreviewsbtn = '<span data-pageid="'.$currentform->page_id .'" data-pagename="'.$currentform->title .'" id="getreviews_'.$currentform->page_id .'" type="button" class="getfbreviews button button-primary dashicons-before dashicons-star-filled"> '.__('Get Reviews', 'wp-review-slider-pro').'</span>';
		}
		
		$autoupdate = intval($currentform->cron)/24;
		if($autoupdate==0){
			$autoupdate ="";
		}
		$downloadmethod = "";
		if(!isset($currentform->crawlserver)){
			$currentform->crawlserver = '';
		}
		if($currentform->crawlserver!=''){
			$downloadmethod = "Crawl ".ucfirst($currentform->crawlserver);
		} else if($currentform->site_type=='TripAdvisor'){
			$downloadmethod= 'Crawl Remote';
		} else if($currentform->site_type=='Zillow'){
			$downloadmethod= 'Crawl Local';
		}
		if($currentform->site_type=='Birdeye' || $currentform->site_type=='Freemius' || $currentform->site_type=='Qualitelis' || $currentform->site_type=='StyleSeat' || $currentform->site_type=='Facebook' || $currentform->site_type=='TrueLocal' || $currentform->site_type=='Twitter' || $currentform->site_type=='Yotpo' || $currentform->site_type=='Google-Places-API'){
				$downloadmethod= 'API';
		}
		if($currentform->site_type=='AngiesList' || $currentform->site_type=='Google' || $currentform->site_type=='CreativeMarket'){
				$downloadmethod= 'Crawl Remote';
		}

		$templangcodecol="";
		if($rtype=='Google-Places-API'){
			$templangcodecol= '<th scope="col" class=" manage-column">'.$currentform->langcode.'</th>';
		}
			
		$html .= '<tr id="'.esc_attr($currentform->id).'" class="locationrow" data-blocks="'.$tempblocks.'">
				<th scope="col" class=" manage-column">'.$currentform->id.'</th>
				<th scope="col" class=" manage-column" style="min-width: 200px;"><b><span class="titlespan">'.esc_html($currentform->title).'</span></b><br><span style="font-size:10px;">'.stripslashes($tempurlhtml).'</span></th>
				'.$templangcodecol.'
				<th scope="col" class=" manage-column"><b>'.$autoupdate.'</b></th>
				<th scope="col" class=" manage-column">'.$downloadmethod.'</th>
				<th scope="col" class=" manage-column">'.$lastranon.'</th>
				<th scope="col" class="manage-column" templateid="'.esc_attr($currentform->id).'" templatetype="'.esc_attr($currentform->site_type).'"><a href="'.$url_tempeditbtn.'" class="rfbtn button button-secondary dashicons-before dashicons-admin-generic">'.__('Edit', 'wp-review-slider-pro').'</a> <a href="'.$url_tempdelbtn.'" class="rfbtn button button-secondary dashicons-before dashicons-trash">'.__('Delete', 'wp-review-slider-pro').'</a> <a href="'.$url_tempcopybtn.'" class="rfbtn button button-secondary dashicons-before dashicons-admin-page">'.__('Copy', 'wp-review-slider-pro').'</a> '.$getreviewsbtn.'</th>
			</tr>';
	}
	} else {

		$html .= '<tr><td colspan="7" class="newformtext">';
		$html .= sprintf(__('You can create a Review Form to download reviews from %s! Once downloaded, they will show up on the Review List page of the plugin and you can display them on your website with a Review Template. Click the <b>"Add New Source Page"</b> button above to get started.', 'wp-review-slider-pro'),$rtype);
		if($rtype=="Zillow"){
			$html .= "<br>".sprintf(__('This does not work with Lender reviews.', 'wp-review-slider-pro'),$rtype);
		}
		if($rtype=="Google"){
			$html .= "<br>".sprintf(__('This will only download your Newest reviews. Use the Review Funnels if you need to download all your old '.$rtype.' Reviews.', 'wp-review-slider-pro'),$rtype);
			
		}
		if($rtype=="Google" || $rtype=="Yelp" ||$rtype=="AngiesList" ||$rtype=="TripAdvisor"){
			$html .= sprintf(__('<b> %s reviews are limited to a max of 100 locations.</b>', 'wp-review-slider-pro'),$rtype);
		}
						
		
		$html .= '</td></tr>';
	}
		$html .= '</tbody></table>';
echo $html;
//echo "<div></br>Coming Soon! Review Funnels will give you a way to download reviews from more than 40 different sites!</br></br></div>"; 

?>

<div id="retreivewspopupdiv" class="wprevpro_hide">
<div class="ajaxmessagediv"></div>
<div class="loadingspinner downloadrevsbtnspinner"></div>
</div>

	<div id="popup_review_list" class="popup-wrapper wprevpro_hide">
	  <div class="popup-content">
		<div class="popup-title">
		  <button type="button" class="popup-close">&times;</button>
		  <h3 id="popup_titletext"></h3>
		</div>
		<div class="popup-body">
		  <div id="popup_bobytext1">
		  <?php
		  if($rtype=="Google"){
		   echo sprintf(__('This is just one of 3 ways to download Google business reviews. You can also use the Get Reviews > Google Places API or the Review Funnel method. Each one has it\'s pros and cons. Read more %shere%s. This method is limited to 100 locations and 50 reviews.', 'wp-review-slider-pro'),'<a href="https://wpreviewslider.userecho.com/en/knowledge-bases/2/articles/1711-three-different-ways-to-download-google-reviews-pros-cons" target="_blank">','</a>');
		  } else if($rtype=="AngiesList"){
		   echo sprintf(__('This page will let you download reviews from multiple %s source pages. %s reviews are limited to a max of 100 locations.', 'wp-review-slider-pro'),$rtype,$rtype);
		  } else if($rtype=="Yelp"){
		   echo sprintf(__('This page will let you download reviews from multiple %s source pages. %s reviews are limited to a max of 100 locations.', 'wp-review-slider-pro'),$rtype,$rtype);
		  }  else if($rtype=="TripAdvisor"){
		   echo sprintf(__('This page will let you download reviews from multiple %s source pages. %s reviews are limited to a max of 100 locations.', 'wp-review-slider-pro'),$rtype,$rtype);
		  } else {
			 echo sprintf(__('This page will let you download reviews from multiple %s source pages.', 'wp-review-slider-pro'),$rtype);
 
		  }
		  ?>
		  </div>
		  <div id="popup_bobytext2">
		  
		  </div>
		</div>
	  </div>
	</div>
	
		<div id="popupmsg" class="popup-wrapper wprevpro_hide">
	  <div class="popup-content">
		<div class="popup-title">
		  <button type="button" class="popup-close">&times;</button>
		  <h3 id="popupmsg_titletext"></h3>
		</div>
		<div id="popupmsgbody" class="popup-body">
		  <div id="popupmsg_bobytext1"></div>
		  <div id="popupmsg_bobytext2"></div>
		</div>
	  </div>
	</div>
	

</div>
<?php
//echo "<br><br><br>";
//print_r($licensecheckarray);
?>
</div>
