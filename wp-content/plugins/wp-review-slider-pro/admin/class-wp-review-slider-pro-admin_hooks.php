<?php
/**
 * The admin-specific hooks functionality of the plugin. Specialty hooks.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    WP_Review_Pro
 * @subpackage WP_Review_Pro/admin
 */

/**
 * The admin-specific hooks functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WP_Review_Pro
 * @subpackage WP_Review_Pro/admin
 * @author     Your Name <email@example.com>
 */
class WP_Review_Pro_Admin_Hooks {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugintoken    The ID of this plugin.
	 */
	private $plugintoken;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugintoken       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	 
	/**
	 * The token of the plugin.
	 *
	 * @since    11.6.0
	 * @access   protected
	 * @var      string    $_token   The token of the plugin.
	 */
	private $_token;	//must declare this now in php 8.2
	//private $_default_api_token;
	private $dbversion;
	
	public function __construct( $plugintoken, $version ) {

		$this->_token = $plugintoken;
		$this->version = $version;
		$this->dbversion = $version;
		//$this->_default_api_token = "AIzaSyCMJzaJssj4ugQjJ0YZCAwFfUcagsmxncQ";
		//for testing==============
		$this->version = time();
		//===================
	}
	
	/**
	 * add dashboard widget to wordpress admin
	 * @access  public
	 * @since   11.0.8.2
	 * @return  void
	 */
	public function wprevpro_dashboard_widget() {
		global $wp_meta_boxes;
		//wp_add_dashboard_widget('custom_help_widget', 'Theme Support', 'custom_dashboard_help');
		add_meta_box( 'wprev_pro_recent_reviews', 'WP Review Slider Recent Reviews', array($this,'custom_dashboard_help'), 'dashboard', 'side', 'high' );
	}
	 
	public function custom_dashboard_help() {
		global $wpdb;
		$reviews_table_name = $wpdb->prefix . 'wpfb_reviews';
		$tempquery = "select * from ".$reviews_table_name." ORDER by created_time_stamp Desc limit 5";
		$reviewrows = $wpdb->get_results($tempquery);
		$now = time(); // or your date as well
		
		echo '<style>
			img.wprev_dash_avatar {float: left;margin-right: 8px;border-radius: 20px;}
			.wprev_dash_stars {float: right;}
			p.wprev_dash_text {margin-top: -6px;}
			span.wprev_dash_timeago {font-size: 12px;font-style: italic;}
			</style>';
		if(count($reviewrows)>0){
		echo '<ul>';
		foreach ( $reviewrows as $review ) 
		{
			$timesince = '';
			if(strlen($review->review_text)>130){
				$reviewtext = substr($review->review_text,0,130).'...';
			} else {
				$reviewtext = $review->review_text;
			}
			
			$your_date = $review->created_time_stamp;
			$datediff = $now - $your_date;
			$daysago = round($datediff / (60 * 60 * 24));
			if($daysago==1){
				$daysagohtml = $daysago.' day ago';
			} else {
				$daysagohtml = $daysago.' days ago';
			}
			if($review->rating<1){
				if($review->recommendation_type=='positive'){
					$review->rating=5;
				} else {
					$review->rating=2;
				}
			}
			
			$imgs_url = WPREV_PLUGIN_URL.'/public/partials/imgs/';
			$starfile = 'stars_'.$review->rating.'_yellow.png';
			$starhtml='<img src="'.$imgs_url."".$starfile.'" alt="'.$review->rating.' star rating" class="wprev_dash_stars">';
			
			$avatarhtml = '';
			if(isset($review->userpiclocal) && $review->userpiclocal!=''){
				$avatarhtml = '<img alt="" src="'.$review->userpiclocal.'" class="wprev_dash_avatar" height="40" width="40">';
			} else if(isset($review->userpic) && $review->userpic!=''){
				$avatarhtml = '<img alt="" src="'.$review->userpic.'" class="wprev_dash_avatar" height="40" width="40">';
			}
			
			echo '<li><div class="wprev_dash_revdiv" style="min-height:50px">'.$avatarhtml.'<div class="wprev_dash_stars">'.$starhtml.'</div><h4 class="wprev_dash_name">'.$review->reviewer_name.' - <span class="wprev_dash_timeago">'.$daysagohtml.'</span></h4><p class="wprev_dash_text">'.$reviewtext.'</p></div></li>';
			
		}
		echo '</ul>';
		echo '<div style="margin-bottom: 5px;"><a href="admin.php?page=wp_pro-reviews">All Reviews</a></div>';
		} else {
			echo '<p>';
			_e('Download some reviews and the latest ones will show here.', 'wp-review-slider-pro');
			echo '</p>';
		}

		
	}

	
		//ajax for testing the api key
	public function wpfbr_ajax_testing_api(){
		//echo "here";
		check_ajax_referer('randomnoncestring', 'wpfb_nonce');
		
		$apikey = $_POST['apikey'];
		
		$goodkey = false;
		
		//remote get the autocomplete first
		//https://maps.googleapis.com/maps/api/place/autocomplete/json?input=1600+Amphitheatre&key=<API_KEY>		
		$url = "https://maps.googleapis.com/maps/api/place/autocomplete/json?input=1600+Amphitheatre&key=".$apikey;
		
		//echo $url;
		
		$data = wp_remote_get( $url );

		if ( is_wp_error( $data ) ) 
		{
			$response['error_message'] 	= $data->get_error_message();
			$reponse['status'] 		= $data->get_error_code();
			print_r($response);
		}
		$response = json_decode( $data['body'], true );
		
		if(isset($response['predictions'][0]['place_id'])){
			//autocomplete is working
			echo "- Autocomplete is working.<br>";
			$goodkey = true;
		} else {
			//key not good
			echo "- Something is wrong with this Google API Key. Error from Google...<br><br>";
			print_r($response);
		}
		
		if($goodkey){
				//remote get place if passed outcomplete
				$url = "https://maps.googleapis.com/maps/api/place/details/json?placeid=ChIJC8DB3J5sYogRV8b_lTk20U4&key=".$apikey;
				$data = wp_remote_get( $url );

				if ( is_wp_error( $data ) ) 
				{
					$response['error_message'] 	= $data->get_error_message();
					$reponse['status'] 		= $data->get_error_code();
					print_r($response);
				}
				$response = json_decode( $data['body'], true );
				
				if(isset($response['result']['name'])){
					//place lookup is working
					echo "- Place Look-up is working.<br><br>";
					echo "- This key should be good to go. Make sure to click Save.<br><br>";
				} else {
					echo "- Something is wrong with this Google API Key. Error from Google...<br><br>";
					print_r($response);
				}
		}
		die();
				
	}
	
	//--======================= end GOOGLE =======================--//
	
	
	//=========================Facebook==============================//
	
	/**
	 * Store reviews in table, called from javascript file admin.js for fb reviews
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function wpfb_process_ajax(){
	//ini_set('display_errors',1);  
	//error_reporting(E_ALL);
		
		check_ajax_referer('randomnoncestring', 'wpfb_nonce');
		
		$postreviewarray = $_POST['postreviewarray'];
		$gafid = $_POST['gafid'];
		
		//print_r($_POST);
		//die();
		
		$getresponse = $this->wpfb_process_ajax_go( $postreviewarray,$gafid );
		
		echo $getresponse;
		
		die();
	}
	
	//for last name options of FB page, called from below
	public function formatlastname($tempreviewername,$lastnameoption){
		//what to do with last name
			if(isset($lastnameoption)){
				//make sure php mb extension is loaded
				if (extension_loaded('mbstring')) {
					$words = mb_split("\s", $tempreviewername);
				} else {
					$words = explode(" ", $tempreviewername);
				}
				if($lastnameoption=="nothing"){
					$tempreviewername=$words[0];
				} else if($lastnameoption=="initial"){
					$tempfirst = $words[0];
					if(isset($words[1])){
						$templast = $words[1];
						if (extension_loaded('mbstring')) {
						$templast =mb_substr($templast,0,1);
						} else {
							$templast =substr($templast,0,1);
						}
						$tempreviewername = $tempfirst.' '.$templast.'.';
					} else {
						$tempreviewername = $tempfirst;
					}
				}
			}
		return $tempreviewername;
	}
	
	//for encoding emojis if needed
	public function wprev_maybe_encode_emoji( $string ) {
		global $wpdb;
		$db_charset = $wpdb->charset;
		if ( 'utf8mb4' != $db_charset ) {
			if ( function_exists('wp_encode_emoji') && function_exists( 'mb_convert_encoding' ) ) {
				$string = wp_encode_emoji( $string );
			}
		}
		return $string;
	 }

 
	public function wpfb_process_ajax_go($postreviewarray,$gafid){
		//loop through each one and insert in to db  
		global $wpdb;
		$db_charset = $wpdb->charset;
		$table_name = $wpdb->prefix . 'wpfb_reviews';
		$foundone = 0;
		$stats = array();
		
		$overall_star_rating = '';
		$rating_count='';
		
		$fid = intval($gafid);
		$table_name_form = $wpdb->prefix . 'wpfb_getapps_forms';
		
		//update last ran on
		$clr = time();
		$data = array('last_ran' => "{$clr}");
		$format = array( '%s' );
		$updatetempquery = $wpdb->update($table_name_form,$data,array('id' => $fid),$format,array( '%d' ));


		$reviewformdetails = $wpdb->get_row( "SELECT * FROM $table_name_form WHERE id = $fid" );

		
		
		$tempcats='';
		if(isset($reviewformdetails->categories)){
		$tempcats=$reviewformdetails->categories;
		}
		$tempposts='';
		if(isset($reviewformdetails->posts)){
		$tempposts=$reviewformdetails->posts;
		}
		$lastnameoption = '';
		if(isset($reviewformdetails->last_name)){
		$lastnameoption=$reviewformdetails->last_name;
		}
		//print_r($postreviewarray);
		//die();

		foreach($postreviewarray as $item) { //foreach element in $arr
			$pageid = $item['pageid'];
			$pagename = $item['pagename'];
			//$pagename = str_replace("%27","'",$pagename);
			$created_time = $item['created_time'];
			$created_time_stamp = strtotime($created_time);
			//fix for admin timezone offset in hours
			$timezoneoffset = get_option('gmt_offset')*60*60;
			$created_time_stamp = $created_time_stamp + $timezoneoffset;
			$created_time = date ("Y-m-d H:i:s", $created_time_stamp);
			$reviewer_name = $item['reviewer_name'];
			//get last name option.
			$reviewer_name = $this->formatlastname( $reviewer_name,$lastnameoption );
			
			$reviewer_id = $item['reviewer_id'];
			$reviewer_imgurl = $item['reviewer_imgurl'];
			if(array_key_exists('rating', $item) && $item['rating']){
				$rating = $item['rating'];
			} else {
				$rating =0;
			}
			if(array_key_exists('recommendation_type', $item) && $item['recommendation_type']){
				$recommendation_type = $item['recommendation_type'];
			} else {
				$recommendation_type ="";
			}
			if(array_key_exists('uniqueid', $item) && $item['uniqueid']){
				$uniqueid = $item['uniqueid'];
			} else {
				$uniqueid ="";
			}
			$review_text = $item['review_text'];
			//check if we need to encode emojis
			if ( 'utf8mb4' != $db_charset ) {
			$review_text = $this->wprev_maybe_encode_emoji( $review_text );
			}

			$review_length = substr_count($review_text, ' ');
			if (extension_loaded('mbstring')) {
				$review_length_char = mb_strlen($review_text);
			} else {
				$review_length_char = strlen($review_text);
			}
			if($review_length_char>0 && $review_length<1){
				$review_length = 1;
			}

			$rtype = $item['type'];

			//option for saving positive recommendation_type as 5 start
			$fb_recommendation_to_star = '';
			if(isset($reviewformdetails->rectostar)){
			 $fb_recommendation_to_star=$reviewformdetails->rectostar;
			}

			if($fb_recommendation_to_star =='1'){
				if($rating==0 && $recommendation_type=="positive"){
					$rating=5;
				}
				if($rating==0 && $recommendation_type=="negative"){
					$rating=2;
				}
			}
			
			//check to see if row is in db already
			$checkrow = $wpdb->get_row( "SELECT id FROM ".$table_name." WHERE reviewer_id = '$reviewer_id'" );
			if ( null === $checkrow ) {
				$unixtimestamp = strtotime($created_time);
				
				//$checkrow = $wpdb->get_var( 'SELECT id FROM '.$table_name.' WHERE reviewer_name = "'.$reviewer_name.'" AND type = "'.$rtype.'" AND (review_length_char = "'.$review_length_char.'" OR review_length = "'.$review_length.'" OR created_time_stamp = "'.$unixtimestamp.'")' );
				
				$checkrow = $wpdb->get_var( "SELECT id FROM ".$table_name." WHERE reviewer_name = '".$reviewer_name."' AND type = '".$rtype."' AND (review_length_char = '".$review_length_char."' OR review_length = '".$review_length."' OR created_time_stamp = '".$unixtimestamp."')" );
			}
			
			//see if we are default hide to yes from Tools/Settings page
			$hideondownload = get_option( 'wprev_hideondownload', '' );
			$temphide='';
			if($hideondownload=="yes"){
				$temphide = "yes";
			}
			
			if ( null === $checkrow ) {
				if($reviewer_id!=''){
				$stats[] =array(
						'pageid' => $pageid, 
						'pagename' => $pagename, 
						'created_time' => $created_time,
						'created_time_stamp' => strtotime($created_time),
						'reviewer_name' => $reviewer_name,
						'reviewer_id' => $reviewer_id,
						'rating' => $rating,
						'recommendation_type' => $recommendation_type,
						'review_text' => $review_text,
						'hide' => $temphide,
						'review_length' => $review_length,
						'review_length_char' => $review_length_char,
						'type' => $rtype,
						'userpic' => $reviewer_imgurl,
						'unique_id' => $uniqueid,
						'categories' => trim($tempcats),
						'posts' => trim($tempposts)
					);
				}
			} else {
				//$foundone = 1;
			}
			//get total and avg
			if(isset($item['overall_star_rating']) && $item['overall_star_rating']>0){
			$overall_star_rating = $item['overall_star_rating'];
			}
			if(isset($item['rating_count']) && $item['rating_count']>0){
			$rating_count = $item['rating_count'];
			}
		}
		$i = 0;
		$insertnum = 0;
		
		//print_r($stats);
		
		foreach ( $stats as $stat ){
			$insertnum = $wpdb->insert( $table_name, $stat );
			$i=$i + 1;
		}
			//send $reviews array to function to send email if turned on.
			if(count($stats)>0){
				$this->sendnotificationemail($stats, "facebook");

			}
			//--------------------------------
	
		$insertid = $wpdb->insert_id;
		
		//echo "avg:".$overall_star_rating;
		//echo "total".$rating_count;
		
		//call function to update total reviews and avg based on what we have in db for this.
		//only going to call this once since we have to pull all reviews and get from db
		$updatetotalavgreviews = $this->updatetotalavgreviews('facebook', $pageid, $overall_star_rating, $rating_count,$pagename );
		
		return $insertnum."-".$insertid."-".$i."-".$foundone;
	}
	
/**
	 * Store user options for fb cron job admin.js
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	 /*
	public function wpfb_process_ajax_cron_page(){
	//ini_set('display_errors',1);  
	//error_reporting(E_ALL);
		
		check_ajax_referer('randomnoncestring', 'wpfb_nonce');
		
		$postpageid = $_POST['pageid'];
		$postaddtocron = $_POST['addtocron'];
		//$postauthtoken = $_POST['authtoken'];
		
		//first save authtoken, this is so user doesn't have to hit save button again
		//$wprevpro_options_new = get_option('wprevpro_options' );
		//$wprevpro_options_new['fb_user_token_field_display']=$postauthtoken;
		//update_option( 'wprevpro_options', $wprevpro_options_new);
		
		$option = 'wpfb_cron_pages';
		
		//get existing option array of fb cron pages
		$fbcronpagesarray = get_option( $option );
		if(isset($fbcronpagesarray)){
			$fbcronpagesarray = json_decode($fbcronpagesarray, true);
		} else {
			$fbcronpagesarray = array();
		}
		
		if($postaddtocron=='yes'){
			//add this pageid to option
			$fbcronpagesarray[] = $postpageid;
		} else {
			//remove this page id from option
			$fbcronpagesarray = array_diff($fbcronpagesarray, array($postpageid));
		}
		
		//reset array index
		$fbcronpagesarray = array_values($fbcronpagesarray);
		
		//update option in db
		$new_value = json_encode($fbcronpagesarray, JSON_FORCE_OBJECT);
		update_option( $option, $new_value);
		
		echo $postpageid;
		echo $postaddtocron;
		echo $new_value;

		die();
	}
	*/

    /**
	 * check for new reviews of fb pages with cron job checked. 
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */	
	public function wprevpro_get_fb_reviews_cron($pageid,$gafid) {
      global $pagenow;
	  
	  $accesscode = get_option('wprevpro_fb_secret_code');
	  //must have long lasting page tokens
	  //print_r($fbcronpagesarray);
	  

		//made it this far now try to grab reviews
		$tempurl = "https://fbapp.ljapps.com/ajaxgetpagerevs-cron.php?rlimit=20&q=getrevs&acode=".$accesscode."&afterc=&callback=cron&pid=".$pageid;
		//echo $tempurl."<br>";
		
		//first try wp_remote_get
		//$data = wp_remote_get( $tempurl );
		//if( is_wp_error( $data ) ) {
			if (ini_get('allow_url_fopen') == true) {
				$data=file_get_contents($tempurl);
			} else if (function_exists('curl_init')) {
				$data=$this->file_get_contents_curl($tempurl);
			}
		//}
		// If the response is an array, it's coming from wp_remote_get,
		// so we just want to capture to the body index for json_decode.
		//if( is_array( $data ) ) {
		//	$data = $data['body'];
		//}
		
		//$data = file_get_contents($tempurl);
		//echo($data)."<br>";;
		$data = json_decode($data, true);
		//print_r($data);
		$reviewdata = $data['data'];
		//print_r($reviewdata);
		
			if (is_array($reviewdata)){
			//put data in to another array and pass to function
			$arrlength = count($reviewdata);
			//echo "<br>length:".$arrlength;
			for($x = 0; $x < $arrlength; $x++) {
				$reviewarray[$x]['pageid']=$pageid;
				$reviewarray[$x]['pagename']=$reviewdata[$x]['pagename'];
				$reviewarray[$x]['created_time']=$reviewdata[$x]['created_time'];
				$reviewarray[$x]['reviewer_name']=$reviewdata[$x]['reviewer']['name'];
				$reviewarray[$x]['reviewer_id']=$reviewdata[$x]['reviewer']['id'];
				if(isset($reviewdata[$x]['rating'])){
					$reviewarray[$x]['rating']=$reviewdata[$x]['rating'];
				} else {
					$reviewarray[$x]['rating']='';
				}
				if(isset($reviewdata[$x]['recommendation_type'])){
					$reviewarray[$x]['recommendation_type']=$reviewdata[$x]['recommendation_type'];
				} else {
					$reviewarray[$x]['recommendation_type']='';
				}
				if(isset($reviewdata[$x]['review_text'])){
					$reviewarray[$x]['review_text']=$reviewdata[$x]['review_text'];
				} else {
					$reviewarray[$x]['review_text']='';
				}
				if(isset($reviewdata[$x]['reviewer']['imgurl'])){
					$reviewarray[$x]['reviewer_imgurl']=$reviewdata[$x]['reviewer']['imgurl'];
				} else {
					$reviewarray[$x]['reviewer_imgurl']='';
				}
				
				$reviewarray[$x]['type']="Facebook";
			}
			//save them to db
			//print_r($reviewarray);
			if (isset($reviewarray) && is_array($reviewarray)){
				$savereviews = $this->wpfb_process_ajax_go( $reviewarray,$gafid );
				//unset array
				foreach ($reviewarray as $key => $value) {
					unset($reviewarray[$key]);
				}
			}
		}

	  
	
	}
		
	
	//======================End Facebook========================//
	/**
	 * bulk edit reviews in table, called from javascript file wprevpro_review_list_page.js
	 * @access  public
	 * @since   11.0.9.2
	 * @return  void
	 */
	public function wpfb_bulkedit_ajax(){
	//ini_set('display_errors',1);  
	//error_reporting(E_ALL);
		
		check_ajax_referer('randomnoncestring', 'wpfb_nonce');
		
		$bulktags = $_POST['tags'];
		$bulkpostids = $_POST['postids'];
		$bulkcategories = $_POST['categories'];
		$bulkselopt = $_POST['selopt'];
		$bulkeditwhat = $_POST['editwhat'];
		
		$bulkfiltertext = $_POST['filtertext'];
		$bulkfilterrating = $_POST['filterrating'];
		$bulkfiltertype = $_POST['filtertype'];
		$bulkfiltertag = $_POST['filtertag'];
		$bulkfilterlang = $_POST['filterlang'];
		$bulkfilterpageid = $_POST['filterpageid'];
		$curselrevs='';
		$postpnum='';
		$postsortdir='';
		$postsortby	='';
		$rowsperpage = 50000;		
		
		$reviewsrows = $this->wpfb_getreviews_ajax_main($bulkfiltertext,$bulkfilterrating,$bulkfiltertype,$bulkfilterlang,$bulkfilterpageid,$curselrevs,$postpnum,$postsortdir,$postsortby,$rowsperpage,$bulkfiltertag);
				
		//echo $bulktags.'-'.$bulkpostids.'-'.$bulkcategories.'-'.$bulkselopt.'-'.$bulkfiltertext.'-'.$bulkfilterrating.'-'.$bulkfiltertype.'-'.$bulkfilterlang.'-'.$bulkfilterpageid.'-'.$bulkeditwhat;
		
		//print_r($reviewsrows);
		
		global $wpdb;
		$table_name = $wpdb->prefix . 'wpfb_reviews';
		$totalupdatedreviews = 0;
		foreach ($reviewsrows as $review) {
			if(is_array($review)){
			//loop and make sure there is a name a id
			if($review['id']!='' && $review['reviewer_name']!=''){
				$r_id = $review['id'];
				//now we modify the review here.
				if($bulkeditwhat=='tags'){		//for changing tags=============
					$tagsarray = explode(",", $bulktags);
					//trim whitespaces on each value
					$tagsarray = array_map('trim', $tagsarray); 
					$msg='';
					if($bulkselopt=='replace'){	//replace with new values
						//edit the tags
						$msg='replace tags';
					} else if($bulkselopt=='addto'){	//just add to existing values
						//get existing if there are any
						$msg='addto tags';
						$temptags = $review['tags'];
						if(json_decode($temptags, true)){
							//we have existing so add to them
							$oldtagsarray = json_decode($temptags, true);
						} else {
							$oldtagsarray = array();
						}
						//loop new tagarray and add to existing if not in_array
						foreach($tagsarray as $value){
							if (!in_array($value, $oldtagsarray)){
							  $oldtagsarray[]=$value;
							}
						}
						$tagsarray = $oldtagsarray;

					} else if($bulkselopt=='deleteone'){	//search tags and remove if matched
						//get existing if there are any
						$temptags = $review['tags'];
						if(json_decode($temptags, true)){
							$msg='remove a tag';
							//we have existing so search and remove
							$oldtagsarray = json_decode($temptags, true);
							foreach($tagsarray as $value){
								// To perform a strict type comparison, pass the third parameter as true
								$key = array_search($value, $oldtagsarray, true);
								if ($key !== false) {
									unset($oldtagsarray[$key]);
								}
							}
							$tagsarray = $oldtagsarray;
						}
					} else if($bulkselopt=='delete'){	//delete all values 
						$msg='delete tags';
						$tagsarray = array();
					}
					if($msg!=''){
						if(count($tagsarray)<1){
							$tagsjson='';
						} else {
							$tagsarray = array_values($tagsarray);
							$tagsarray = array_map('trim', $tagsarray);
							$tagsjson = json_encode($tagsarray);
						}
						$data = array('tags' => "$tagsjson");
						$format = array('%s'); 
						$updatetempquery = $wpdb->update($table_name, $data, array( 'id' => $r_id ), $format, array( '%d' ));
						$totalupdatedreviews = $totalupdatedreviews + $updatetempquery;
					}
				} else if($bulkeditwhat=='cats'){		//for changing categories=============
					$catsarray = explode(",", $bulkcategories);
					//trim whitespaces on each value
					$catsarray = array_map('trim', $catsarray); 
					//add dashes
					foreach ($catsarray as &$value) {
						$value = '-' . $value . '-';
					}
					$msg='';
					if($bulkselopt=='replace'){	//replace with new values
						//edit the tags
						$msg='replace cats';
					} else if($bulkselopt=='addto'){	//just add to existing values
						//get existing if there are any
						$msg='addto cats';
						$curcats = $review['categories'];
						if(json_decode($curcats, true)){
							//we have existing so add to them
							$oldcatsarray = json_decode($curcats, true);
						} else {
							$oldcatsarray = array();
						}
						//loop new catarray and add to existing if not in_array
						foreach($catsarray as $testvalue){
							if (!in_array($testvalue, $oldcatsarray)){
							  $oldcatsarray[]=$testvalue;
							}
						}
						$catsarray = $oldcatsarray;
					} else if($bulkselopt=='deleteone'){	//search tags and remove if matched
						//get existing if there are any
						$tempcats = $review['categories'];
						if(json_decode($tempcats, true)){
							$msg='remove a cat';
							//we have existing so search and remove
							$oldcatssarray = json_decode($tempcats, true);
							foreach($catsarray as $value){
								$key = array_search($value, $oldcatssarray, true);
								if ($key !== false) {
									unset($oldcatssarray[$key]);
								}
							}
							$catsarray = $oldcatssarray;
							//print_r($tagsarray);
							//die();
						}
					} else if($bulkselopt=='delete'){	//delete all values
						$msg='delete cats';
						$catsarray = array();
					}
					if($msg!=''){
						//echo $msg;
						if(count($catsarray)<1){
							$catidsarrayjson='';
						} else {
							$catsarray = array_values($catsarray);
							$catsarray = array_map('trim', $catsarray);
							$catidsarrayjson = json_encode($catsarray);
						}
						$data = array('categories' => "$catidsarrayjson");
						$format = array('%s'); 
						$updatetempquery = $wpdb->update($table_name, $data, array( 'id' => $r_id ), $format, array( '%d' ));
						$totalupdatedreviews = $totalupdatedreviews + $updatetempquery;
					}
				} else if($bulkeditwhat=='posts'){		//for changing postids=============
					$postsarray = explode(",", $bulkpostids);
					//trim whitespaces on each value
					$postsarray = array_map('trim', $postsarray); 
					//add dashes
					foreach ($postsarray as &$value) {
						$value = '-' . $value . '-';
					}
					$msg='';
					if($bulkselopt=='replace'){	//replace with new values
						//edit the tags
						$msg='replace posts';
					} else if($bulkselopt=='addto'){	//just add to existing values
						//get existing if there are any
						$msg='addto posts';
						$curposts = $review['posts'];
						if(json_decode($curposts, true)){
							//we have existing so add to them
							$oldpostsarray = json_decode($curposts, true);
						} else {
							$oldpostsarray = array();
						}
						//loop new catarray and add to existing if not in_array
						foreach($postsarray as $posttestvalue){
							if (!in_array($posttestvalue, $oldpostsarray)){
							  $oldpostsarray[]=$posttestvalue;
							}
						}
						$postsarray = $oldpostsarray;
					}  else if($bulkselopt=='deleteone'){	//search tags and remove if matched
						//get existing if there are any
						$curposts = $review['posts'];
						if(json_decode($curposts, true)){
							$msg='remove a post';
							//we have existing so search and remove
							$oldpostssarray = json_decode($curposts, true);
							foreach($postsarray as $value){
								$key = array_search($value, $oldpostssarray, true);
								if ($key !== false) {
									unset($oldpostssarray[$key]);
								}
							}
							$postsarray = $oldpostssarray;
						}
					} else if($bulkselopt=='delete'){	//delete all values
						$msg='delete cats';
						$postsarray = array();
					}
					if($msg!=''){
						if(count($postsarray)<1){
							$postsarrayjson='';
						} else {
							$postsarray = array_values($postsarray);
							$postsarray = array_map('trim', $postsarray);
							$postsarrayjson = json_encode($postsarray);
						}
						$data = array('posts' => "$postsarrayjson");
						$format = array('%s'); 
						$updatetempquery = $wpdb->update($table_name, $data, array( 'id' => $r_id ), $format, array( '%d' ));
						$totalupdatedreviews = $totalupdatedreviews + $updatetempquery;
					}
				}
			}
		}
		}
		
		//$reviewsrows = json_encode($reviewsrows);
		//echo $reviewsrows;
		echo "<b>".$totalupdatedreviews."</b> reviews updated.";

		die();
	}
	
	
	
	/**
	 * Hides or deletes reviews in table, called from javascript file wprevpro_review_list_page.js
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function wpfb_hidereview_ajax(){
	//ini_set('display_errors',1);  
	//error_reporting(E_ALL);
		
		check_ajax_referer('randomnoncestring', 'wpfb_nonce');
		
		$rid = intval($_POST['reviewid']);
		$myaction = $_POST['myaction'];
		$newsw = $_POST['sortweight'];

		global $wpdb;
		$table_name = $wpdb->prefix . 'wpfb_reviews';
		
		//grab review and see if it is hidden or not
		$myreview = $wpdb->get_row( "SELECT * FROM $table_name WHERE id = $rid" );
		
		//check to see if we are deleting or just hiding or showing
		if($myaction=="hideshow"){
			
			//pull array from options table
			$hiddenrevs = get_option( 'wprevpro_hidden_reviews' );
			if(!$hiddenrevs){
				$hiddenrevsarray = array('');
			} else {
				$hiddenrevsarray = json_decode($hiddenrevs,true);
			}
			if(!is_array($hiddenrevsarray)){
				$hiddenrevsarray = array('');
			}
			$this_hide_val = $myreview->reviewer_name."-".$myreview->created_time_stamp."-".$myreview->review_length."-".$myreview->type."-".$myreview->rating;

			if($myreview->hide=="yes"){
				//already hidden need to show
				$newvalue = "";
				
				//remove from $hiddenrevs
				if(($key = array_search($this_hide_val, $hiddenrevsarray)) !== false) {
					unset($hiddenrevsarray[$key]);
				}
				
			} else {
				//shown, need to hide
				$newvalue = "yes";
				
				//need to update hidden ids in options table here array of name,time,count,type
				 array_push($hiddenrevsarray,$this_hide_val);
			}
			//update hidden  reviews option, use this when downloading  reviews so we can re-hide them each download
			$hiddenrevsjson=json_encode($hiddenrevsarray);
			update_option( 'wprevpro_hidden_reviews', $hiddenrevsjson );
			
			//update database review table to hide this one
			$data = array( 
				'hide' => "$newvalue"
				);
			$format = array( 
					'%s'
				); 
			$updatetempquery = $wpdb->update($table_name, $data, array( 'id' => $rid ), $format, array( '%d' ));
			if($updatetempquery>0){
				echo $rid."-".$myaction."-".$newvalue;
			} else {
				echo $rid."-".$myaction."-fail";
			}
			
			//update the total and average review here.
			$this->updatetotalavgreviews('', trim($myreview->pageid), '', '',trim($myreview->pagename));

		}
		if($myaction=="deleterev"){
			$deletereview = $wpdb->delete( $table_name, array( 'id' => $rid ), array( '%d' ) );
			if($deletereview>0){
				echo $rid."-".$myaction."-success";
				//delete this local avatar and cache
				$filename = $myreview->created_time_stamp.'_'.$myreview->id.'.jpg';
				//$localfile = plugin_dir_path(dirname(__FILE__)).'public/partials/avatars/'.$filename;
				$img_locations_option = json_decode(get_option( 'wprev_img_locations' ),true);
				$avatar_dir = $img_locations_option['upload_dir_wprev_avatars'];
				$localfile = $avatar_dir.$filename;
				@unlink($localfile);
				
				//update the total and average review here.
				$this->updatetotalavgreviews('', trim($myreview->pageid), '', '',trim($myreview->pagename));
				
			} else {
				echo $rid."-".$myaction."-fail";
			}
		}
		if($myaction=="updatesw"){
			//update the sortweight
			$data = array( 
				'sort_weight' => "$newsw"
				);
			$format = array( 
					'%d'
				); 
			$updatetempquery = $wpdb->update($table_name, $data, array( 'id' => $rid ), $format, array( '%d' ));
			if($updatetempquery>0){
				echo $rid."-".$myaction."-success";
			} else {
				echo $rid."-".$myaction."-fail";
			}
		}


		die();
	}
	
	/**
	 * Ajax, retrieves reviews from table, called from javascript file wprevpro_templates_posts_page.js
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	 public function wpfb_getreviews_ajax(){
		 
		check_ajax_referer('randomnoncestring', 'wpfb_nonce');
		
		$filtertext = htmlentities($_POST['filtertext']);
		$filtertext = $_POST['filtertext'];
		$filterrating = htmlentities($_POST['filterrating']);
		$filterrating = intval($filterrating);
		$filtertype = htmlentities($_POST['filtertype']);
		$filterlang = htmlentities($_POST['filterlang']);
		$filtertag = htmlentities($_POST['filtertag']);
		$filterpage = htmlentities($_POST['filterpageid']);
		$curselrevs = $_POST['curselrevs'];
		$postpnum = $_POST['pnum'];
		$postsortdir = $_POST['sortdir'];
		$postsortby = $_POST['sortby'];
		 
		 $rowsperpage = 20;
		 
		$reviewsrows = $this->wpfb_getreviews_ajax_main($filtertext,$filterrating,$filtertype,$filterlang,$filterpage,$curselrevs,$postpnum,$postsortdir,$postsortby,$rowsperpage,$filtertag);
		 
	 	$results = json_encode($reviewsrows);
		echo $results;

		die();
	}
	 
	 
	public function wpfb_getreviews_ajax_main($filtertext,$filterrating,$filtertype,$filterlang,$filterpage,$curselrevs,$postpnum,$postsortdir,$postsortby,$rowsperpage,$filtertag="",$posts="",$revlengthchar="",$ishidden=""){
		
	//ini_set('display_errors',1);  
	//error_reporting(E_ALL);
	$hidepagination = false;
	$hidesearch = false;

		//echo
		//perform db search and return results
		global $wpdb;
		$table_name = $wpdb->prefix . 'wpfb_reviews';
		
		//pagenumber
		if(isset($postpnum)){
		$temppagenum = $postpnum;
		} else {
		$temppagenum ="";
		}
		if ( $temppagenum=="") {
			$pagenum = 1;
		} else if(is_numeric($temppagenum)){
			$pagenum = intval($temppagenum);
		}
		
		//sort direction
		if($postsortdir=="ASC" || $postsortdir=="DESC"){
			$sortdir = $postsortdir;
		} else {
			$sortdir = "DESC";
		}

		//make sure sortby is valid
		if(!isset($postsortby)){
			$postsortby = "";
		}
		$allowed_keys = ['created_time_stamp', 'reviewer_name', 'rating', 'recommendation_type', 'review_length', 'pagename', 'type' , 'hide', 'company_name', 'sort_weight'];
		$checkorderby = sanitize_key($postsortby);
	
		if(in_array($checkorderby, $allowed_keys, true) && $postsortby!=""){
			$sorttable = $postsortby. " ";
		} else {
			$sorttable = "created_time_stamp ";
		}
		
		//get reviews from db
		$lowlimit = ($pagenum - 1) * $rowsperpage;
		$tablelimit = $lowlimit.",".$rowsperpage;
		
		if($filterrating>0){
			$filterratingtext = "rating = ".$filterrating;
		} else {
			$filterratingtext = "rating > -1";
		}
		
		//filter by type
		if($filtertype!='all'){
			$filtertypetext = " AND type = '".$filtertype."' ";
		} else {
			$filtertypetext = "";
		}
		
		//filter by tag, comma list of tags.
		if($filtertag!='all'){
			if($filtertag==''){
				$filtertagtext = "";
			} else {
				//filter out end spaces
				$filtertag = htmlentities(trim($filtertag));
				//explode to array
				//$filtertagarray = explode(",", $filtertag);
				//convert array to json
				//$filtertagjson = json_encode($filtertagarray);
				$filtertagtext = " AND tags LIKE '%\"".$filtertag."\"%' ";
			}
		} else {
			$filtertagtext = "";
		}
		
		$postidtext = "";
		if($posts!=''){
			$intpostid = intval($posts);
			$postidtext = " AND posts LIKE '%\"-".$intpostid."-\"%' ";
		}
		
		//echo $filtertagtext;
		
		//filter by language_code
		if($filterlang!='all'){
			if($filterlang=='unset'){
				$filterlangtext = " AND language_code = '' ";
			} else if($filterlang==''){
				$filterlangtext = "";
			} else {
				$filterlangtext = " AND language_code = '".$filterlang."' ";
			}
		} else {
			$filterlangtext = "";
		}
		
		//filter by pageId
		$filterpagetext = "";
		if($filterpage!='all'){
			if($filterpage==''){
				$filterpagetext = "";
			} else {
				$filterpagetext = " AND pageid = '".$filterpage."' ";
			}
		}
		
		//filter by char length
		$revlengthchartext = "";
		if($revlengthchar>0){
			$revlengthchar = intval($revlengthchar);
			$revlengthchartext = " AND review_length_char >= '".$revlengthchar."' ";
		}
		
		//filter is hidden
		$ishiddentext = "";
		if($ishidden=='no'){
			$ishiddentext = " AND hide != 'yes' ";
		}

		//check to see if looking for previously selected only
		if (is_array($curselrevs)){
			$query = "SELECT * FROM ".$table_name." WHERE id IN (";
			//loop array and add to query
			$n=1;
			foreach ($curselrevs as $value) {
				if($value!=""){
					if(count($curselrevs)==$n){
						$query = $query." $value";
					} else {
						$query = $query." $value,";
					}
				}
				$n++;
			}
			$query = $query.")";
			//echo $query ;

			$reviewsrows = $wpdb->get_results($query);
			$hidepagination = true;
			$hidesearch = true;
		} else {

			//if filtertext set then use different query review_title
			if($filtertext!=""){
				$reviewsrows = $wpdb->get_results("SELECT * FROM ".$table_name."
					WHERE (reviewer_name LIKE '%".$filtertext."%' or review_title LIKE '%".$filtertext."%' or review_text LIKE '%".$filtertext."%' or tags LIKE '%".$filtertext."%' or pageid LIKE '%".$filtertext."%') AND ".$filterratingtext.$filtertypetext.$filtertagtext.$postidtext.$filterlangtext.$filterpagetext.$revlengthchartext.$ishiddentext."
					ORDER BY ".$sorttable." ".$sortdir." 
					LIMIT ".$tablelimit." "
				, ARRAY_A);
				$hidepagination = true;
			} else {
				$reviewsrows = $wpdb->get_results(
					$wpdb->prepare("SELECT * FROM ".$table_name."
					WHERE id>%d AND ".$filterratingtext.$filtertypetext.$filtertagtext.$postidtext.$filterlangtext.$filterpagetext.$revlengthchartext.$ishiddentext."
					ORDER BY ".$sorttable." ".$sortdir." 
					LIMIT ".$tablelimit." ", "0")
				, ARRAY_A);
			}
		}
		//print_r($reviewsrows);
		//die();
		// Print last SQL query string
//echo $wpdb->last_query;
//$reviewsrows['query']=$wpdb->last_query;
// Print last SQL query result
//$wpdb->last_result;
// Print last SQL query Error
//$wpdb->last_error;
		//total number of rows
		$reviewtotalcount = $wpdb->get_var( "SELECT COUNT(*) FROM ".$table_name." WHERE id>0 AND ".$filterratingtext.$filtertypetext.$filtertagtext.$filterlangtext.$filterpagetext);
		//total pages
		$totalpages = ceil($reviewtotalcount/$rowsperpage);
		
		$reviewsrows['reviewtotalcount']=$reviewtotalcount;
		$reviewsrows['totalpages']=$totalpages;
		$reviewsrows['pagenum']=$pagenum;
		$reviewsrows['dbquery']="SELECT * FROM ".$table_name."
					WHERE id>%d AND ".$filterratingtext.$filtertypetext.$filtertagtext.$postidtext.$filterlangtext.$filterpagetext.$revlengthchartext.$ishiddentext."
					ORDER BY ".$sorttable." ".$sortdir." 
					LIMIT ".$tablelimit." ";
		
		if($hidepagination){
			$reviewsrows['reviewtotalcount']=count($reviewsrows)-3;
			//$reviewsrows['totalpages']=0;
			//$reviewsrows['pagenum']=0;
		}
		if($hidesearch){
			//$reviewsrows['reviewtotalcount']=0;
			$reviewsrows['totalpages']=0;
			//$reviewsrows['pagenum']=0;
		}
		
		return $reviewsrows;

		die();
	}

	/**
	 * Create XML File for Google Product Ratings program.
	 *
	 * @param string $url - preferably a fully qualified URL
	 * @return boolean - true and msg if success, else false and message.
	 */
	
	public function createGoogleProductXMLFile($googleprodfiledir) {

			//need to creat XML file of products with reviews.
			$dom = new DOMDocument();
			$dom->encoding = 'utf-8';
			$dom->xmlVersion = '1.0';
			$dom->formatOutput = true;

			$root = $dom->createElement('feed');
				$xmlns_vc = new DOMAttr('xmlns:vc', 'http://www.w3.org/2007/XMLSchema-versioning');
				$root->setAttributeNode($xmlns_vc);
				$xmlns_xsi = new DOMAttr('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
				$root->setAttributeNode($xmlns_xsi);
				$noNamespaceSchemaLocation = new DOMAttr('xsi:noNamespaceSchemaLocation', 'http://www.google.com/shopping/reviews/schema/product/2.3/product_reviews.xsd');
				$root->setAttributeNode($noNamespaceSchemaLocation);

			$version_node = $dom->createElement('version','2.3');
			$root->appendChild($version_node);
			
			$aggregator_node = $dom->createElement('aggregator');
			$aggregator_node_name = $dom->createElement('name', 'WP Review Slider Pro');
			$aggregator_node->appendChild($aggregator_node_name);
			$root->appendChild($aggregator_node);
			
			$publisher_node = $dom->createElement('publisher');
			$publisher_node_name = $dom->createElement('name', get_bloginfo('name'));
			$publisher_node->appendChild($publisher_node_name);
			$root->appendChild($publisher_node);
			
			$reviews_node = $dom->createElement('reviews');
			//going to loop here.
			//need list of product ids.
			$allproductidsarray = Array();
			$args = array('post_type' => 'product','posts_per_page' => -1);
			$loop = new WP_Query( $args );
			if ( $loop->have_posts() ): while ( $loop->have_posts() ): $loop->the_post();
				global $product;
				//$price = $product->get_price_html();
				//$sku = $product->get_sku();
				//$stock = $product->get_stock_quantity();
				$allproductidsarray[]= get_the_ID();
			endwhile; endif; wp_reset_postdata();
			
			//print_r($allproductidsarray);
			//loop product IDs.
			foreach ($allproductidsarray as $postid) {
				$posturl = get_permalink($postid);
				$posttitle = get_the_title($postid);
				
				//need to find gtin, mpn, sku, brand if available. Search post meta values and try to match. If more than one match take one with value and has shorter index
				//=================================

				//print_r(get_post_meta($postid));
				$postmetaarray = get_post_meta($postid);
				$found_gtin='';
				$lengthofindex_gtin=1000000;
				$found_mpn='';
				$lengthofindex_mpn=1000000;
				$found_brand='';
				$lengthofindex_brand=1000000;
				$found_sku='';
				if(isset($postmetaarray['_sku'][0]) && $postmetaarray['_sku']!=''){
					$found_sku=$postmetaarray['_sku'][0];
				}
				
				foreach($postmetaarray as $x => $val) {
					//echo "$x = $val[0]<br>";
					//gtin
					$pos_gtin = stripos($x, 'gtin');
					if($pos_gtin !== false && strlen($x)<$lengthofindex_gtin) {
						//found a match get value.
						$found_gtin = $val[0];
						$lengthofindex_gtin = strlen($x);
					}
					//mpn
					$pos_mpn = stripos($x, 'mpn');
					if($pos_mpn !== false && strlen($x)<$lengthofindex_mpn) {
						//found a match get value.
						$found_mpn = $val[0];
						$lengthofindex_mpn = strlen($x);
					}
					//brand
					$pos_brand = stripos($x, 'brand');
					if($pos_brand !== false && strlen($x)<$lengthofindex_brand) {
						//found a match get value.
						$found_brand = $val[0];
						$lengthofindex_brand = strlen($x);
					}
					if($found_brand==''){
						$pos_brand = stripos($x, 'brand name');
						if($pos_brand !== false && strlen($x)<$lengthofindex_brand) {
							//found a match get value.
							$found_brand = $val[0];
							$lengthofindex_brand = strlen($x);
						}
					}
				}
				
				//if brand not found use name of site.
				if($found_brand==''){
					$found_brand = get_bloginfo( 'name' );
				}
				
				//get an array of reviews for this postid and then loop the reviews.
				//order by newest, not hidden.
				$revlengthchar = 25;
				$numofrevs = 20;
				$ishidden = 'no';
				$reviewsrows = $this->wpfb_getreviews_ajax_main('','','all','all','','','','','',$numofrevs,'',$postid,$revlengthchar,$ishidden);

				//print_r($reviewsrows);
				
				foreach ($reviewsrows as $curreview) {
					if(isset($curreview['id'])){
					//print_r($curreview);
					
					$timestamp = intval($curreview['created_time_stamp']);
					$revdatetime = date(DATE_W3C,$timestamp);	//created_time_stamp, 2014-04-21T00:00:00Z
					$reviewer_name = htmlspecialchars($curreview['reviewer_name']);
					$review_text = htmlspecialchars($curreview['review_text']);
					$rating = intval($curreview['rating']);
					$review_id_num = intval($curreview['id']);
					
				$review_node = $dom->createElement('review');
				
					$review_id_temp = $dom->createElement('review_id',$review_id_num);
					$review_node->appendChild($review_id_temp);
				
					$reviewer_node = $dom->createElement('reviewer');
						$reviewer_node_name = $dom->createElement('name',$reviewer_name);
						$reviewer_node->appendChild($reviewer_node_name);
					$review_node->appendChild($reviewer_node);
					
					$review_timestamp_node = $dom->createElement('review_timestamp',$revdatetime);
					$review_node->appendChild($review_timestamp_node);
					
					$content_node = $dom->createElement('content',$review_text);
					$review_node->appendChild($content_node);
					
					$review_url_node = $dom->createElement('review_url',$posturl);
					$review_url_type = new DOMAttr('type', 'group');
					$review_url_node->setAttributeNode($review_url_type);
					$review_node->appendChild($review_url_node);
					
					$ratings_node = $dom->createElement('ratings');
						$ratings_overall_node = $dom->createElement('overall',$rating);
						$rating_min = new DOMAttr('min', '1');
						$ratings_overall_node->setAttributeNode($rating_min);
						$rating_max = new DOMAttr('max', '5');
						$ratings_overall_node->setAttributeNode($rating_max);
						$ratings_node->appendChild($ratings_overall_node);
					$review_node->appendChild($ratings_node);
					
					$products_node = $dom->createElement('products');
						$product_node = $dom->createElement('product');
						
						if($found_gtin!='' || $found_mpn!=''|| $found_sku!=''|| $found_brand!=''){
							$product_ids_node = $dom->createElement('product_ids');
							if($found_gtin!=''){
								$gtins_node = $dom->createElement('gtins');
									$gtin_node = $dom->createElement('gtin',$found_gtin);
									$gtins_node->appendChild($gtin_node);
								$product_ids_node->appendChild($gtins_node);
							}
							if($found_mpn!=''){
								$mpns_node = $dom->createElement('mpns');
									$mpn_node = $dom->createElement('mpn',$found_mpn);
									$mpns_node->appendChild($mpn_node);
								$product_ids_node->appendChild($mpns_node);
							}	
							if($found_sku!=''){
								$skus_node = $dom->createElement('skus');
									$sku_node = $dom->createElement('sku',$found_sku);
									$skus_node->appendChild($sku_node);
								$product_ids_node->appendChild($skus_node);
							}
							if($found_brand!=''){	
								$brands_node = $dom->createElement('brands');
									$brand_node = $dom->createElement('brand',$found_brand);
									$brands_node->appendChild($brand_node);
								$product_ids_node->appendChild($brands_node);
							}
							
							$product_node->appendChild($product_ids_node);
						}
					
							$product_name_node = $dom->createElement('product_name',$posttitle);
							$product_node->appendChild($product_name_node);
							
							$product_url_node = $dom->createElement('product_url',$posturl);
							$product_node->appendChild($product_url_node);
							
						$products_node->appendChild($product_node);	
					$review_node->appendChild($products_node);	
				$reviews_node->appendChild($review_node);
				//end loop here for each review of each product.
				}
				}
			}
				
			$root->appendChild($reviews_node);

			$dom->appendChild($root);

			$savefile = $dom->save($googleprodfiledir);
			
			if(!$savefile){
				return false;
			} else {
				return true;
			}
	
		
	}


	/**
	 * Check if an item exists out there in the "ether".
	 *
	 * @param string $url - preferably a fully qualified URL
	 * @return boolean - true if it is out there somewhere
	 */
	public function webItemExists($url) {
		if (($url == '') || ($url == null)) { return false; }
		$response = wp_remote_head( $url, array( 'timeout' => 5 ) );
		$accepted_status_codes = array( 404);
		echo 'code'.wp_remote_retrieve_response_code( $response );
		if ( in_array( wp_remote_retrieve_response_code( $response ), $accepted_status_codes ) ) {
			return false;
		}
		return true;
	}	
	
	
	/**
	 * replaces insert into post text on media uploader when uploading reviewer avatar
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */	
	public function wprevpro_media_text() {
		global $pagenow;
		if ( 'media-upload.php' == $pagenow || 'async-upload.php' == $pagenow ) {
			// Now we'll replace the 'Insert into Post Button' inside Thickbox
			add_filter( 'gettext', array($this,'replace_thickbox_text') , 1, 3 );
		}
	}
	 
	public function replace_thickbox_text($translated_text, $text, $domain) {
		if ('Insert into Post' == $text) {
			$referer = strpos( wp_get_referer(), 'wp_pro-reviews' );
			if ( $referer != '' ) {
				return __('Use as Reviewer Avatar or Logo', 'wp-review-slider-pro' );
			}
		}
		return $translated_text;
	}
	
	//for using curl instead of fopen
	private function file_get_contents_curl($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);       
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
	
	//--======================= yelp =======================--//
	//===========now doing this in get_apps since version 10.4.9
	/**
	 * download yelp reviews
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */	
	 /*
	//for ajax call to yelp master
	public function wprevpro_ajax_download_yelp_master() {
		
		check_ajax_referer('randomnoncestring', 'wpfb_nonce');
		$thisurlnum = $_POST['urlnum'];
		$getresponse = $this->wprevpro_download_yelp_master($thisurlnum);
		//echo $getresponse;
		//echo "here";
		die();
	}
	 
	 
	public function wprevpro_download_yelp_master($downloadurlnum = 'all') {
			$options = get_option('wprevpro_yelp_settings');
			
			//check to see if only downloading one here, if not that skip and continue
			if($downloadurlnum!='all'){
				if($downloadurlnum==1){
					$numurl='';
				} else {
					$numurl=$downloadurlnum;
				}
				if (filter_var($options['yelp_business_url'.$numurl], FILTER_VALIDATE_URL)) {
					$currenturlmore = $options['yelp_business_url'.$numurl];
					$this->wprevpro_download_yelp_master_perurl($currenturlmore,$numurl);
				} else {
					//$errormsg = 'Please enter a valid URL. If the URL contains international non-ASCII characters (ü) then use the encoded version. You can get it by copying the URL from the address bar and pasting in the URL field.';
					$errormsg = esc_html__('Please enter a valid URL. If the URL contains international non-ASCII characters (ü) then use the encoded version. You can get it by copying the URL from the address bar and pasting in the URL field.', 'wp-review-slider-pro');
					$this->errormsg = $errormsg;
					echo $errormsg;
				}
			} else {
				//make sure you have valid url, if not display message
				if (filter_var($options['yelp_business_url'], FILTER_VALIDATE_URL)) {
					//call for this url, multiple times
					$currenturl = $options['yelp_business_url'];
					$urlnum = '';
					$this->wprevpro_download_yelp_master_perurl($currenturl,$urlnum);
					
				} else {
					//$errormsg = 'Please enter a valid URL. If the URL contains international non-ASCII characters (ü) then use the encoded version. You can get it by copying the URL from the address bar and pasting in the URL field.';
					$errormsg = esc_html__('Please enter a valid URL. If the URL contains international non-ASCII characters (ü) then use the encoded version. You can get it by copying the URL from the address bar and pasting in the URL field.', 'wp-review-slider-pro');
					$this->errormsg = $errormsg;
					echo $errormsg;
				}
				
				$totalmorepages = $options['yelp_business_url_more'];
				for ($x = 2; $x <= $totalmorepages; $x++) {
					sleep(2);
					$numurl = $x;
					if (filter_var($options['yelp_business_url'.$numurl], FILTER_VALIDATE_URL)) {
						$currenturlmore = $options['yelp_business_url'.$numurl];
						if (filter_var($currenturlmore, FILTER_VALIDATE_URL)) {
						$this->wprevpro_download_yelp_master_perurl($currenturlmore,$numurl);
						}
					}
				} 
			}

	}


	public function wprevpro_download_yelp_master_perurl($currenturl,$urlnum) {
		//ini_set('memory_limit','256M');
		
		global $wpdb;
			$table_name = $wpdb->prefix . 'wpfb_reviews';
			$options = get_option('wprevpro_yelp_settings');

				  
				//echo "passed both tests";
				$stripvariableurl = strtok($currenturl, '?');
				$yelpurl[1] = $stripvariableurl.'?sort_by=date_desc';
				$yelpurl[2] = $stripvariableurl.'?start=10&sort_by=date_desc';
				$yelpurl[3] = $stripvariableurl.'?start=20&sort_by=date_desc';
				$yelpurl[4] = $stripvariableurl.'?start=30&sort_by=date_desc';
				$yelpurl[5] = $stripvariableurl.'?start=40&sort_by=date_desc';
				//$yelpurl[6] = $stripvariableurl.'?start=50&sort_by=date_desc';
				//$yelpurl[7] = $stripvariableurl.'?start=60&sort_by=date_desc';
				//$yelpurl[8] = $stripvariableurl.'?start=70&sort_by=date_desc';
				//$yelpurl[9] = $stripvariableurl.'?start=80&sort_by=date_desc';
				
				
				//loop to grab pages
				$reviews = [];
				$n=1;
				
				$avgrating ='';
				$totalreviews ='';

				foreach ($yelpurl as $urlvalue) {
					//echo "-url:".$urlvalue;
					// Create DOM from URL or file
					if (ini_get('allow_url_fopen') == true) {
						$fileurlcontents=file_get_contents($urlvalue);
					} else if (function_exists('curl_init')) {
						$fileurlcontents=$this->file_get_contents_curl($urlvalue);
					} else {
						$fileurlcontents='<html><body>'.esc_html__('fopen is not allowed on this host.', 'wp-review-slider-pro').'</body></html>';
						$errormsg = $errormsg . '<p style="color: #A00;">'.esc_html__('fopen is not allowed on this host and cURL did not work either. Ask your web host to turn fopen on or fix cURL.', 'wp-review-slider-pro').'</p>';
						$this->errormsg = $errormsg;
						echo $errormsg;
						break;
					}
					
					//echo $html;
					//echo($fileurlcontents);
					//die();

					//get the reviews json string
					$startpos = strpos($fileurlcontents, 'reviewFeedQueryProps');
					if($startpos>0){
						$firstsubstring = substr($fileurlcontents,$startpos+22);
						$endpos = strpos($firstsubstring, ', "reviewHighlightsProps":');
						if(!$endpos){
							$endpos = strpos($firstsubstring, ',"reviewHighlightsProps":');
						}
					}
					
					if(!$endpos || !$startpos){
						$pagetype = 'old';
						echo "old";
						//$errormsg = $errormsg . ' Unable to find reviews. Please contact support or use the Review Funnel page.';
						//$this->errormsg = $errormsg;
						//echo $errormsg;
						//break;
					} else {
						$finalstring = substr($firstsubstring,0,$endpos);
						
						$finalstring = substr($firstsubstring,0,$endpos);
						$finalstring = htmlentities($finalstring);
						$finalstring = html_entity_decode($finalstring);
					
						$finalstringjson = json_decode($finalstring,TRUE);
						if(isset($finalstringjson['reviews'][0]['business']['name']) && $finalstringjson['reviews'][0]['business']['name']!=''){
						$pagename =$finalstringjson['reviews'][0]['business']['name'];
						}
						$pagetype = 'new';
					}
				
					//this is different depending on which yelp page type
					//===========================
					$html = wppro_str_get_html($fileurlcontents);
						
					if($pagetype=='old'){
						//echo "here";
						
						if($html->find('a.user-display-name', 0)){
								$pagename = $html->find('h1.css-1x9iesk', 0)->plaintext;
						}
						if($pagename=='' || $pagename==' '){
							echo esc_html__('Error: Can not find page name for this URL. Please contact us or use a Review Funnel to download the reviews.', 'wp-review-slider-pro').'<br>';
							die();
						}
						$pagename = trim($pagename).' '.$urlnum;
						//create pageid for db
						$pageid = str_replace(" ","",$pagename);
						$pageid = str_replace("'","",$pageid);
						$pageid = str_replace('"',"",$pageid);
						$reviewsarray = $this->wpyelp_download_yelp_master_typeold($html,$pagename,$yelpurl,$pageid);
						$reviewstemp = $reviewsarray['reviews'];
						$reviewindb = $reviewsarray['reviewindb'];

						$reviews = array_merge($reviews, $reviewstemp);
					} else if($pagetype=='new'){
						$pagename = trim($pagename).' '.$urlnum;
						//create pageid for db
						$pageid = str_replace(" ","",$pagename);
						$pageid = str_replace("'","",$pageid);
						$pageid = str_replace('"',"",$pageid);
						$reviewsarray = $this->wpyelp_download_yelp_master_typenew($finalstringjson,$pagename,$yelpurl,$pageid);
						if(isset($reviewsarray['pagename']) && $reviewsarray['pagename']!=''){
							$pagename =$reviewsarray['pagename'];
							$pageid = $reviewsarray['pageid'];
						}
						$reviewstemp = $reviewsarray['reviews'];
						$reviewindb = $reviewsarray['reviewindb'];

						if(is_array($reviewstemp) && count($reviewstemp)>0){
						$reviews = array_merge($reviews, $reviewstemp);
						}
						//print_r($reviewstemp);
						//print_r($reviews);
					} else {
						echo "Error: Page title now found. Please contact support".
						die();
					}
					//================================
	
					
					//find total and average number here
					if($totalreviews==''){
						$totalreviews = $this->get_string_between($fileurlcontents, '{"aggregateRating": {"reviewCount": ', ',');
					}
					if($totalreviews==''){
						$totalreviews = $this->get_string_between($fileurlcontents, ',"reviewCount":', '}');
					}
					if($avgrating==''){
					$avgrating = $this->get_string_between($fileurlcontents, '"AggregateRating", "ratingValue": ', '},');
					}
					if($avgrating==''){
					$avgrating = $this->get_string_between($fileurlcontents, ',"ratingValue":', ',"');
					}
					
					
					//break here if found one already in db
					if($reviewindb == 'yes') {
						break;
					}		
							
					//sleep for random 2 seconds
					sleep(rand(1,2));
					$n++;
					
					//var_dump($reviews);
					// clean up memory
					if (!empty($html)) {
						$html->clear();
						unset($html);
					}

				}
				//print_r($reviews);
		
					
					//print_r($reviews);
					//echo "count:";
					//echo count($reviews);
					//die();
					
				//go ahead and delete first, only if we have new ones and turned on.
				if(count($reviews)>0){
					
					if($options['yelp_radio_rule']!='no'){
						$temppagename = trim($pagename);
						//echo "delete reviews".$temppagename;
						$wpdb->delete( $table_name, array( 'type' => 'Yelp', 'pagename' => $temppagename ) );
						$temppagename='';
					}
					//add all new yelp reviews to db
					foreach ( $reviews as $stat ){
						$insertnum = $wpdb->insert( $table_name, $stat );
						//echo $wpdb->last_error;
						//if($wpdb->print_error() !== ''){
						//	$wpdb->print_error();
						//	die();
						//}
					}
					
					//send $reviews array to function to send email if turned on.
					$this->sendnotificationemail($reviews,"yelp");
					
					//reviews added to db
					if(isset($insertnum)){
						$errormsg = ' '.count($reviews).' Yelp reviews downloaded.';
						$this->errormsg = $errormsg;
						
						//update avatars
						$this->wprevpro_download_img_tolocal();
						
					}
				} else {
					$errormsg = esc_html__('No new reviews found. Please note the Plugin can only return Recommended Reviews. You can also use the Get Reviews > Review Funnel page to download Yelp reviews.', 'wp-review-slider-pro');
					$this->errormsg = $errormsg;
				}
				echo $errormsg;
				
				//update total and average
				//echo $pageid."-".$pagename."-".$avgrating."-".$totalreviews;
				$this->updatetotalavgreviews('yelp', trim($pageid), $avgrating, $totalreviews,$pagename );

	}
	
	public function wpyelp_download_yelp_master_typeold($html,$pagename,$yelpurl,$pageid){
					
						// Find 20 reviews
					global $wpdb;
			$table_name = $wpdb->prefix . 'wpfb_reviews';
			$options = get_option('wprevpro_yelp_settings');
					$i = 1;
			
					foreach ($html->find('div.review--with-sidebar') as $review) {
						
							if ($i > 21) {
									break;
							}
							$user_name='';
							$userimage='';
							$rating='';
							$datesubmitted='';
							$rtext='';
							$user_id='';
							// Find user_name
							if($review->find('a.user-display-name', 0)){
								$user_name = $review->find('a.user-display-name', 0)->plaintext;
								$user_id = $review->find('a.user-display-name', 0)->href;
								$user_id = substr($user_id, strpos($user_id, "userid=") + 7);
							}
							if($user_name==''){
								//try again for some international sites
								if($review->find('li.user-name', 0)){
									$user_name = $review->find('li.user-name', 0)->plaintext;
								}
							}
							if($user_id==''){
								$user_id = str_replace(" ","",$user_name);
							}
														
							// Find userimage
							if($review->find('img.photo-box-img', 0)){
								$userimage = $review->find('img.photo-box-img', 0)->src;
							}
							
							// find rating
							if($review->find('div.rating-large', 0)){
								$rating = $review->find('div.rating-large', 0)->title;
								$rating = intval($rating);
							}
							
							// find date
							if($review->find('span.rating-qualifier', 0)){
								$datesubmitted = $review->find('span.rating-qualifier', 0)->plaintext;
								$datesubmitted = str_replace(array("Updated", "review"), "", $datesubmitted);
							}
							
							// find text
							$rtext ='';
							if($review->find('div.review-content', 0)){
								$rtext = $review->find('div.review-content', 0)->find('p', 0)->plaintext;
							}
							//fix for read more tag js-content-toggleable hidden
							if(strlen($rtext)<1){
								if($review->find('div.js-expandable-comment', 0)){
								$rtext = $review->find('div.review-content', 0)->find('div.js-expandable-comment', 0)->find('span.js-content-toggleable', 1)->plaintext;
								}
							}
							
							if($rating>0){
								//$review_length = str_word_count($rtext);
								//if($review_length <2 && $rtext !=""){		//fix for other language error
									$review_length = substr_count($rtext, ' ');
								//}
								$pos = strpos($userimage, 'default_avatars');
								if ($pos === false) {
									$userimage = str_replace("60s.jpg","120s.jpg",$userimage);
								}
								$timestamp = strtotime($datesubmitted);
								$timestamp = date("Y-m-d H:i:s", $timestamp);
								//check option to see if this one has been hidden
								//pull array from options table of yelp hidden
								$yelphidden = get_option( 'wprevpro_hidden_reviews' );
								if(!$yelphidden){
									$yelphiddenarray = array('');
								} else {
									$yelphiddenarray = json_decode($yelphidden,true);
								}
								$this_yelp_val = trim($user_name)."-".strtotime($datesubmitted)."-".$review_length."-Yelp-".$rating;
								if (in_array($this_yelp_val, $yelphiddenarray)){
									$hideme = 'yes';
								} else {
									$hideme = 'no';
								}
								
								//check to see if in database already
								//check to see if row is in db already
								$reviewindb = 'no';
								if($options['yelp_radio_rule']!='no'){
									$reviewindb = 'no';
								} else {
									//$checkrow = $wpdb->get_var( 'SELECT id FROM '.$table_name.' WHERE created_time_stamp = "'.strtotime($datesubmitted).'" AND reviewer_name = "'.trim($user_name).'" ' );
									
									$checkrow = $wpdb->get_var( "SELECT id FROM ".$table_name." WHERE created_time_stamp = '".strtotime($datesubmitted)."' AND reviewer_name = '".trim($user_name)."'" );
									
									if( empty( $checkrow ) )
									{
										$reviewindb = 'no';
									} else {
										$reviewindb = 'yes';
										break;
									}
								}
								$furlrev = "https://www.yelp.com/user_details?userid=".$user_id;
								//find character length
								if (extension_loaded('mbstring')) {
									$review_length_char = mb_strlen($rtext);
								} else {
									$review_length_char = strlen($rtext);
								}
								if($review_length_char>0 && $review_length<1){
									$review_length = 1;
								}

								if( $reviewindb == 'no' )
								{
									$reviews[] = [
											'reviewer_name' => trim($user_name),
											'reviewer_id' => $user_id,
											'pageid' => trim($pageid),
											'pagename' => trim($pagename),
											'userpic' => $userimage,
											'rating' => $rating,
											'created_time' => $timestamp,
											'created_time_stamp' => strtotime($datesubmitted),
											'review_text' => trim($rtext),
											'hide' => $hideme,
											'review_length' => $review_length,
											'review_length_char' => $review_length_char,
											'type' => 'Yelp',
											'from_url' => $yelpurl[1],
											'from_url_review' => $furlrev
									];
								}
								$review_length ='';
								$review_length_char='';
							}
					 
							$i++;
					}
				
				$results['reviews'] = $reviews;
				$results['reviewindb'] =$reviewindb;

					return $results;
		
	}
	
	public function wpyelp_download_yelp_master_typenew($finalstringjson,$pagename,$yelpurl,$pageid){
					// Find 20 reviews
					global $wpdb;
			$table_name = $wpdb->prefix . 'wpfb_reviews';
			$options = get_option('wprevpro_yelp_settings');
					$i = 1;
					//print_r($finalstringjson);
					
						foreach ($finalstringjson['reviews'] as $review) {
							
								if ($i > 21) {
										break;
								}
								$user_name='';
								$userimage='';
								$rating='';
								$datesubmitted='';
								$rtext='';
								$user_id='';
								
								if(!isset($pagename) || $pagename==''){
									$pagename = $review['business']['name'];
									$pagename = trim($pagename).' '.$urlnum;
									$results['pagename'] = $pagename;
								}
								//if(isset($pagename) &&  $pagename!=''){
									//create pageid for db
									$pageid = str_replace(" ","",$pagename);
									$pageid = str_replace("'","",$pageid);
									$pageid = str_replace('"',"",$pageid);
									$results['pageid'] = $pageid;
								//} else {
								//	echo "Can not find pagename.";
								//	die();
								//}
							
								// Find user_name
								$user_name = $review['user']['altText'];
								if($user_id==''){
									$user_id = str_replace(" ","",$user_name);
								}
															
								// Find userimage
								$userimage = $review['user']['src'];
								
								// find rating
								$rating = $review['rating'];
								
								// find date
								$datesubmitted = $review['localizedDate'];
								
								// find text
								$rtext ='';
								$rtext = html_entity_decode($review['comment']['text']);
								$lang = $review['comment']['language'];
								
								if($rating>0){
									//$review_length = str_word_count($rtext);
									//if($review_length <2 && $rtext !=""){		//fix for other language error
										$review_length = substr_count($rtext, ' ');
									//}
									$pos = strpos($userimage, 'default_avatars');
									if ($pos === false) {
										$userimage = str_replace("60s.jpg","120s.jpg",$userimage);
									}
									$timestamp = strtotime($datesubmitted);
									$timestamp = date("Y-m-d H:i:s", $timestamp);
									//check option to see if this one has been hidden
									//pull array from options table of yelp hidden
									$yelphidden = get_option( 'wprevpro_hidden_reviews' );
									if(!$yelphidden){
										$yelphiddenarray = array('');
									} else {
										$yelphiddenarray = json_decode($yelphidden,true);
									}
									$this_yelp_val = trim($user_name)."-".strtotime($datesubmitted)."-".$review_length."-Yelp-".$rating;
									if (in_array($this_yelp_val, $yelphiddenarray)){
										$hideme = 'yes';
									} else {
										$hideme = 'no';
									}
									
									//check to see if in database already
									//check to see if row is in db already
									$reviewindb = 'no';
									if($options['yelp_radio_rule']!='no'){
										$reviewindb = 'no';
									} else {
																				
										//$checkrow = $wpdb->get_var( 'SELECT id FROM '.$table_name.' WHERE created_time_stamp = "'.strtotime($datesubmitted).'" AND reviewer_name = "'.trim($user_name).'" ' );
										
										$checkrow = $wpdb->get_var( "SELECT id FROM ".$table_name." WHERE created_time_stamp = '".strtotime($datesubmitted)."' AND reviewer_name = '".trim($user_name)."'" );
										
										if( empty( $checkrow ) )
										{
											$reviewindb = 'no';
										} else {
											$reviewindb = 'yes';
											break;
										}
									}

									if(isset($review['user']['link']) && $review['user']['link']!=''){
										$furlrev = "https://www.yelp.com/".$review['user']['link'];
									} else {
										$furlrev = $yelpurl[1];
									}
									
									//find character length
									if (extension_loaded('mbstring')) {
										$review_length_char = mb_strlen($rtext);
									} else {
										$review_length_char = strlen($rtext);
									}
									if($review_length_char>0 && $review_length<1){
										$review_length = 1;
									}
									if( $reviewindb == 'no' )
									{
										$reviews[] = [
												'reviewer_name' => trim($user_name),
												'reviewer_id' => $user_id,
												'pageid' => trim($pageid),
												'pagename' => trim($pagename),
												'userpic' => $userimage,
												'rating' => $rating,
												'created_time' => $timestamp,
												'created_time_stamp' => strtotime($datesubmitted),
												'review_text' => trim($rtext),
												'hide' => $hideme,
												'review_length' => $review_length,
												'review_length_char' => $review_length_char,
												'type' => 'Yelp',
												'from_url' => $yelpurl[1],
												'from_url_review' => $furlrev,
												'language_code' => $lang,
										];
									}
									$review_length ='';
									$review_length_char='';
								}
						 
								$i++;
						}
					
					$results['reviews'] = $reviews;
					$results['reviewindb'] =$reviewindb;
					
					//print_r($results['reviews']);

					return $results;
	}
	*/
//--======================= end yelp =======================--//	
	
	
	/**
	 * Ajax, retrieves reviews from table, called from javascript file wprevpro_templates_posts_page.js
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function wpfb_getavatars_ajax(){
		
		check_ajax_referer('randomnoncestring', 'wpfb_nonce');
		
		$this->wprevpro_download_img_tolocal();
		die();
	}
	
	 /**
	 * download a copy of the avatars to local server if checked in template and saved
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */	
	 private function compressimage($source, $destination, $quality) {
		$destination = $source;
		if( @is_file($source)){
		$info = @getimagesize($source);

		
		//print_r($info);
		if($info){
			if ($info['mime'] == 'image/jpeg'){
				$image = @imagecreatefromjpeg($source);
				if(!$image){
					//can't create
				} else {
					if(@imagejpeg($image, $destination, $quality)){
						$destination = $destination;
					} else {
						$destination = $source;
					};
				}
			} else if ($info['mime'] == 'image/gif') {
				$image = @imagecreatefromgif($source);
				if(!$image){
					//can't create
				} else {
					if(@imagejpeg($image, $destination, $quality)){
						$destination = $destination;
					} else {
						$destination = $source;
					};
				}
			} else if ($info['mime'] == 'image/png') {
				$imagetemp = @imagecreatefrompng($source);
				//create new image
				$targetImage = @imagecreatetruecolor( $info[0], $info[1] );   
				@imagealphablending( $targetImage, false );
				@imagesavealpha( $targetImage, true );
				@imagecopyresampled( $targetImage, $imagetemp, 
                    0, 0, 
                    0, 0, 
                    $info[0], $info[1], 
                    $info[0], $info[1] );
				//$image = imagepng(  $targetImage, $destination, 9 );
				if(@imagepng(  $targetImage, $destination, 9 )){
					$destination = $destination;
				} else {
					$destination = $source;
				};
			}
		
		}
		}
		return $destination;
	}
	
	private function wppro_resizeimage($source,$size){
		//add fix for cron job
		if(!function_exists('wp_get_current_user')) {
			include(ABSPATH . "wp-includes/pluggable.php"); 
		}
		$image = wp_get_image_editor( $source );
		if ( ! is_wp_error( $image ) ) {
			$imagesize = $image->get_size();
			if($imagesize['width']>$size){
				$image->resize( $size, $size, true );
				$image->save( $source );
			}
		} else {
			$error_string = $image->get_error_message();
			echo '<div id="message" class="error"><p>' . $error_string . '</p></div>';
		}
	}

	//check this is an image, doesn't work for svg
	public function is_image($path)
	{
		$a = @getimagesize($path);
		if(is_array($a)){
			$image_type = $a[2];
			
			if(in_array($image_type , array(IMAGETYPE_GIF , IMAGETYPE_JPEG ,IMAGETYPE_PNG , IMAGETYPE_BMP)))
			{
				return true;
			}
		}
		return false;
	}

	//used for cache all avatars
	public function wprevpro_download_img_tolocal() {

		//$imagecachedir = plugin_dir_path( __DIR__ ).'/public/partials/cache/';
		$img_locations_option = json_decode(get_option( 'wprev_img_locations' ),true);
		$imagecachedir = $img_locations_option['upload_dir_wprev_cache'];
		
		//get array of all reviews, check to see if there is an image 
		global $wpdb;
		$table_name = $wpdb->prefix . 'wpfb_reviews';
		$currentreviews = $wpdb->get_results("SELECT id, reviewer_id, created_time_stamp, reviewer_name, type, userpic FROM $table_name WHERE type !='Manual' AND type !='Submitted' AND userpic !=''");
		$copyfuncworks = 'no';
		foreach ( $currentreviews as $review ) 
		{
			//echo $review->reviewer_name;
			//echo "<br>";
			$userpic = htmlspecialchars_decode($review->userpic);
			//path extension
			//$path_info = pathinfo($userpic);
			$pathext= '';
			if ($path = parse_url($userpic, PHP_URL_PATH)) { 
			   $pathext= pathinfo($path, PATHINFO_EXTENSION);
			}
			if($pathext!='jpg' && $pathext!='png' && $pathext!='svg' && $pathext!='gif'){
				$pathext='png';
			}
			//make sure this is a found image before saving
			$isimage=true;
			if($pathext!='svg' && $review->type=="Google"){
				if(!$this->is_image($userpic)){
					$isimage=false;
				}
			}
			//$fileExist = is_file($userpic);
			if($isimage && $userpic!=''){
				
				//echo ":isimage:<br>";

				$blob = $review->reviewer_name;
				$blob = preg_replace("/[^a-zA-Z]+/", "", $blob);
				$newfilename = $review->created_time_stamp.'_'.strtolower($blob)."_".$review->id;
				$newfile = $imagecachedir . $newfilename.".".$pathext;
				//check for avatar
				$newfileExist = is_file($newfile);
				//echo $newfileExist."<br>";

				if(!$newfileExist){
					//echo "copy image file<br>";
					//echo $userpic;
					//echo "isimage:<br>";
					//echo is_file($userpic);
					//echo "<br>";

					if($userpic!=''){
						// If the function it's not available, require it.
						if ( ! function_exists( 'download_url' ) ) {
							require_once ABSPATH . 'wp-admin/includes/file.php';
						}
						if ( ! function_exists( 'wp_generate_password' ) ) {
							require_once ABSPATH . 'wp-includes/pluggable.php';
						}

						$tmp_file = download_url( $userpic );
						if(is_string($tmp_file)){
							if ( @copy($tmp_file, $newfile) ) {
								//echo "<br>Copy success!<br>";
								$copyfuncworks = 'yes';
							}
							if ( ! empty( $tmp_file ) ) {
								@unlink( $tmp_file );
							}
						}
					}
					if($copyfuncworks == 'yes' && $pathext!='svg'){
							$this->wppro_resizeimage($newfile,135);
							$d =$this->compressimage($newfile, $newfile, 85);
							$newfileExist = true;
					}
					
					
				}
				//check to make sure there is data in the file
				if(@is_file($newfile)){
					if(@filesize($newfile)<200){
						@unlink($newfile);
						$newfileExist = false;
					}
				}
				
				//now create low quality version
				$newfilelow = $imagecachedir . $newfilename.'_60.'.$pathext;
				$newfilelowExist = is_file($newfilelow);
				if($userpic!=''){
					if(!$newfilelowExist && $newfileExist && @is_file($newfile)){
							if ( @copy($newfile, $newfilelow) && $pathext!='svg') {
								//echo "Copy success!";
								$this->wppro_resizeimage($newfilelow,60);
							}
					}
				}
				//--------------------------
				//now try to save a local copy
				//copy this file to avatar directory and update db, currenlty only doing this for FB, and other things if turned on.
				if($userpic!=''){
					//echo "<br>prepare to copy:".$newfile;
					if($newfileExist){
						//echo "<br>prepare to copy2:";
						if($review->type=="Facebook"){
							//echo "wprevpro_download_avatar_tolocal";
							$this->wprevpro_download_avatar_tolocal($newfile,$review);
						}
					}
				}
			
			}
		}
		
		//set option value 
		update_option( $this->_token . '_copysuccess', $copyfuncworks );

		//die();
	}
	
	//Used to create local copy of avatar to serve 
	public function wprevpro_download_avatar_tolocal($filetocopy,$review) {
		
		//path extension
		//$path_info = pathinfo($filetocopy);
		//$pathext= $path_info['extension']; // "jpg"
		$pathext= '';
		if ($path = parse_url($filetocopy, PHP_URL_PATH)) { 
		   $pathext= pathinfo($path, PATHINFO_EXTENSION);
		   //print_r(pathinfo($path));
		}

		//echo "pathext:".$pathext;
		if($pathext!='jpg' && $pathext!='png' && $pathext!='svg' && $pathext!='gif'){
			$pathext='jpg';
		}
		$isimage=true;
		if($pathext!='svg'){
			if(!$this->is_image($filetocopy)){
				$isimage=false;
			}
		}
		//echo "pathext:".$pathext;
		if($isimage){
			
		//$imagecachedir = plugin_dir_path( __DIR__ ).'public/partials/avatars/';
		$img_locations_option = json_decode(get_option( 'wprev_img_locations' ),true);
		$imageuploadedir =$img_locations_option['upload_dir_wprev_avatars'];
		$filename = $review->created_time_stamp.'_'.$review->id;
		//get array of all reviews, check to see if the image exists
		$newfile = $imageuploadedir . $filename.'.'.$pathext;
		
		$newfileurl = esc_url( $img_locations_option['upload_url_wprev_avatars']). $filename.'.'.$pathext;
			//check for avatar
			if($filetocopy!=''){
				$id= $review->id;
				$revid = $review->reviewer_id;
				global $wpdb;
				$table_name = $wpdb->prefix . 'wpfb_reviews';
				$imgcopyfail = true;
				$newfileExist = is_file($newfile);
				if(!$newfileExist){
					if ( @copy($filetocopy, $newfile) ) {
						//update db with new image location, userpiclocal
						//echo "copy success".$id."-".$revid;
						$imgcopyfail = false;
					} else if (function_exists('curl_init') && is_writable(dirname($newfile))) {
							$curl = curl_init();
							$fh = @fopen($newfile, 'w');
							curl_setopt($curl, CURLOPT_URL, $filetocopy);
							curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
							$result = curl_exec($curl);
							@fwrite($fh, $result);
							fclose($fh);
							curl_close($curl);
												
							if ( is_file($newfile) ) {
								$imgcopyfail = false;
							}
						}
					if($imgcopyfail) {
						//echo "copy failed";
						//unable to copy
						$wpdb->query( $wpdb->prepare("UPDATE $table_name SET userpiclocal = '' WHERE id = %d AND reviewer_id = %s",$id, $revid) );
					} else {
						//image was copied
						//echo "copy success2:".$newfileurl;
						$wpdb->query( $wpdb->prepare("UPDATE $table_name SET userpiclocal = '$newfileurl' WHERE id = %d AND reviewer_id = %s",$id, $revid) );
						//try to resize if too large
						if($pathext!='svg'){
						$this->wppro_resizeimage($newfile,135);
						}
					}
					//check to make sure there is data in the file
					if(@filesize($newfile)<200){
						//echo "no data in file";
						unlink($newfile);
						$wpdb->query( $wpdb->prepare("UPDATE $table_name SET userpiclocal = '' WHERE id = %d AND reviewer_id = %s",$id, $revid) );
					}
					
				} else {
					//echo "image exists:".$newfile;
					//image does exist, just update db with this filename
					$wpdb->query( $wpdb->prepare("UPDATE $table_name SET userpiclocal = '$newfileurl' WHERE id = %d AND reviewer_id = %s",$id, $revid) );
				}
			}
		}
	}
	
	//for exporting CSV file of templates
	public function print_csv()
	{
		if ( ! current_user_can( 'manage_options' ) )
			return;

			      header('Content-Type: text/csv; charset=utf-8');  
				  header('Content-Disposition: attachment; filename=templatedata.csv');  
				  $output = fopen("php://output", "w");  
				  //fputcsv($output, array('ID', 'First Name', 'Last Name', 'Email', 'Joining Date'));  
				  //Get list of all current forms--------------------------
				  global $wpdb;
				  $table_name = $wpdb->prefix . 'wpfb_post_templates';
					$currentformsarray = $wpdb->get_results("SELECT * FROM $table_name",ARRAY_A);
					//print_r($currentformsarray);
					
					//get the column keys and insert them on the first row of the excel file
					$arraykeys = array_keys($currentformsarray[0]);
					//print_r($arraykeys);
					
					fputcsv($output, $arraykeys); 
					
				  //while($row = mysqli_fetch_assoc($result)) 
				foreach ( $currentformsarray as $currentform ) 
				  {  
					   fputcsv($output, $currentform);  
				  }  
				  fclose($output);  

		// output the CSV data
		die();
	}
	
	public function wprev_canuserseepage($pageurl=''){
		require_once WPREV_PLUGIN_DIR . 'admin/class-wp-review-slider-pro-admin-common.php';
		$plugin_admin_common = new Common_Admin_Functions();
		$test = $plugin_admin_common->wprev_canuserseepage($pageurl);
		return $test;
	}
	
	public function printreviews_csv(){
		
	 // check user capabilities
    if (!current_user_can('edit_pages') && $this->wprev_canuserseepage('reviews')==false) {
        return;
    }

			      header('Content-Type: text/csv; charset=utf-8');  
				  header('Content-Disposition: attachment; filename=reviewdata.csv');  
				  $output = fopen("php://output", "w");  
				  //fputcsv($output, array('ID', 'First Name', 'Last Name', 'Email', 'Joining Date'));  
				  //Get list of all current forms--------------------------
				  global $wpdb;
				  $table_name = $wpdb->prefix . 'wpfb_reviews';
					$currentformsarray = $wpdb->get_results("SELECT * FROM $table_name",ARRAY_A);
					//print_r($currentformsarray);
					
					//get the column keys and insert them on the first row of the excel file
					$arraykeys = array_keys($currentformsarray[0]);
					//print_r($arraykeys);
					
					fputcsv($output, $arraykeys); 
					
				  //while($row = mysqli_fetch_assoc($result)) 
				foreach ( $currentformsarray as $currentform ) 
				  {  
					   fputcsv($output, $currentform);  
				  }  
				  fclose($output);  

		// output the CSV data
		die();
	}
		//for exporting CSV file of templates
	public function print_csv_badges()
	{
			 // check user capabilities
		if (!current_user_can('manage_options') && $this->wprev_canuserseepage('reviews')==false) {
			return;
		}

			      header('Content-Type: text/csv; charset=utf-8');  
				  header('Content-Disposition: attachment; filename=badgedata.csv');  
				  $output = fopen("php://output", "w");  
				  //fputcsv($output, array('ID', 'First Name', 'Last Name', 'Email', 'Joining Date'));  
				  //Get list of all current forms--------------------------
				  global $wpdb;
				  $table_name = $wpdb->prefix . 'wpfb_badges';
					$currentformsarray = $wpdb->get_results("SELECT * FROM $table_name",ARRAY_A);
					//print_r($currentformsarray);
					
					//get the column keys and insert them on the first row of the excel file
					$arraykeys = array_keys($currentformsarray[0]);
					//print_r($arraykeys);
					
					fputcsv($output, $arraykeys); 
					
				  //while($row = mysqli_fetch_assoc($result)) 
				foreach ( $currentformsarray as $currentform ) 
				  {  
					   fputcsv($output, $currentform);  
				  }  
				  fclose($output);  

		// output the CSV data
		die();
	}
			//for exporting CSV file of templates
	public function print_csv_forms()
	{
			 // check user capabilities
		if (!current_user_can('manage_options') && $this->wprev_canuserseepage('reviews')==false) {
			return;
		}

			      header('Content-Type: text/csv; charset=utf-8');  
				  header('Content-Disposition: attachment; filename=formdata.csv');  
				  $output = fopen("php://output", "w");  
				  //fputcsv($output, array('ID', 'First Name', 'Last Name', 'Email', 'Joining Date'));  
				  //Get list of all current forms--------------------------
				  global $wpdb;
				  $table_name = $wpdb->prefix . 'wpfb_forms';
					$currentformsarray = $wpdb->get_results("SELECT * FROM $table_name",ARRAY_A);
					//print_r($currentformsarray);
					
					//get the column keys and insert them on the first row of the excel file
					$arraykeys = array_keys($currentformsarray[0]);
					//print_r($arraykeys);
					
					fputcsv($output, $arraykeys); 
					
				  //while($row = mysqli_fetch_assoc($result)) 
				foreach ( $currentformsarray as $currentform ) 
				  {  
					   fputcsv($output, $currentform);  
				  }  
				  fclose($output);  

		// output the CSV data
		die();
	}
	
		
	public function getBetween($content, $start, $end) {
		$n = explode($start, $content);
		$result = Array();
		foreach ($n as $val) {
			$pos = strpos($val, $end);
			if ($pos !== false) {
				$result[] = substr($val, 0, $pos);
			}
		}
		return $result;
	}


	//---for sending notification email=================================================
	public function sendnotificationemail($reviewarray, $type) {
		//new method uses the notification table in the db
		global $wpdb;
		$table_name_notify = $wpdb->prefix . 'wpfb_nofitifcation_forms';
		//first check to see if there are any forms in the db, if we find some, loop and send emails
		$currentforms = $wpdb->get_results("SELECT * FROM $table_name_notify WHERE enable != 'no' ORDER BY id DESC",ARRAY_A);
		//print_r($currentforms);
		$pageid='';
		if(isset($reviewarray[0]['pageid'])){
			$pageid = trim($reviewarray[0]['pageid']);
		}
		
		//print_r($reviewarray);
		if (is_array($currentforms)){
			if (count($currentforms)>0){
				//passed checks we have array of notifications
				$totalforms = count($currentforms);
				for ($x = 0; $x < $totalforms; $x++) {
					//echo "id:".$currentforms[$x]['id']." <br>";
					//run through checks to see if we need to send
					$passedtypecheck = false;
					//use the type and $reviewarray to see if we need to send an email if we need too.
					$dbsitetypearray = json_decode($currentforms[$x]['site_type']);
					if($currentforms[$x]['site_type']==''){
						$passedtypecheck = true;
					}
					if(is_array($dbsitetypearray) && in_array($type, $dbsitetypearray)){
						$passedtypecheck = true;
					}
					$passedpagecheck = false;
					//use the page
					$dbpageidarray = json_decode($currentforms[$x]['source_page']);
					if($currentforms[$x]['source_page']==''){
						$passedpagecheck = true;
					}
					//echo "pageid:".$pageid;
					//print_r($dbpageidarray);
					if(is_array($dbpageidarray)){
						if(in_array($pageid, $dbpageidarray)){
							$passedpagecheck = true;
						}
						//add check for special characters
						$temppageidhtmlentities = htmlspecialchars_decode($pageid);
						if(in_array($temppageidhtmlentities, $dbpageidarray)){
							$passedpagecheck = true;
						}
						//add check for special characters
						$temppageidhtmlentities = htmlentities($pageid);
						if(in_array($temppageidhtmlentities, $dbpageidarray)){
							$passedpagecheck = true;
						}
					}
					
					
					//continue if made it this far and email is not blank
					if($passedpagecheck && $passedtypecheck && $currentforms[$x]['email']!=''){
						
						//loop review array and see if any match the rule in the form, is so then send the mail
						$foundone = false;
						$emailtable ='';
						if(is_array($reviewarray)){
							foreach ( $reviewarray as $review ){
								//is this greater, equal, or less
								$addthisreview = false;
								$ratingnum = $currentforms[$x]['rate_val'];
								if(!isset($review['recommendation_type'])){
									$review['recommendation_type']='';
								}
								//fb fix for recommendations---------
								if($review['rating']<1 && $review['recommendation_type']=='positive'){
									$review['rating']=5;
								} else if($review['rating']<1 && $review['recommendation_type']=='negative'){
									$review['rating']=2;
								}
								//---------------
								if($currentforms[$x]['rate_op']=='>' || $currentforms[$x]['rate_op']=='&gt;'){
									if($review['rating']>$ratingnum && $review['rating']>0){
										$addthisreview=true;
									}
								} else if($currentforms[$x]['rate_op']=='='|| $currentforms[$x]['rate_op']=='&equals;'){
									if($review['rating']==$ratingnum && $review['rating']>0){
										$addthisreview=true;
									}
								} else if($currentforms[$x]['rate_op']=='<' || $currentforms[$x]['rate_op']=='&lt;'){
									if($review['rating']<$ratingnum && $review['rating']>0){
										$addthisreview=true;
									}
								}
								if($addthisreview){
									$foundone = true;
									//add to email string
									$emailtable = $emailtable . '<tr><td style="vertical-align: top;padding: 5px;border: 1px solid #f2f2f2;"><b>'.$review['rating'].'</b></td><td style="vertical-align: top;padding: 5px;border: 1px solid #f2f2f2;">'.date("M j, Y",$review['created_time_stamp']).'</td><td style="vertical-align: top;padding: 5px;border: 1px solid #f2f2f2;">'.$review['reviewer_name'].'</td><td style="vertical-align: top;padding: 5px;border: 1px solid #f2f2f2;">'.$review['review_text'].'</td></tr>';
								}
							}
						}
						//if we found a review then we form email here...
						if($foundone){
							if($currentforms[$x]['email_first_line']==""){
								$currentforms[$x]['email_first_line']= __('WP Review Slider Pro found the following reviews that match your notification settings.', 'wp-review-slider-pro');
							}
							if($type=='facebook'){
								$originurl = "https://www.facebook.com/pg/".$reviewarray[0]['pageid']."/reviews/";
							} else {
								$originurl = $review['from_url'];
								$originurl = urldecode($originurl);
							}
							$tempnameaddress = '<p>'.esc_html__('Social Page Name:', 'wp-review-slider-pro').' <b>'.$reviewarray[0]['pagename'].'</b></p>';
							
							$emailstring = '<div>'.stripslashes(html_entity_decode($currentforms[$x]['email_first_line'])).'</div><p><b>'.esc_html__('Review From:', 'wp-review-slider-pro').' <a href="'.$originurl.'" target="_blank" style="text-decoration: none;">'.ucfirst($type).'</a></b></p><p><b>'.esc_html__('Review URL:', 'wp-review-slider-pro').' <a href="'.$originurl.'" target="_blank" style="text-decoration: none;">'.$originurl.'</a></b></p>'.$tempnameaddress.'<br><table><tr><td  style="width: 50px;"><b>'.esc_html__('Rating', 'wp-review-slider-pro').'</b></td><td style="width: 100px;"><b>'.esc_html__('Date', 'wp-review-slider-pro').' </b></td><td><b>'.esc_html__('Name', 'wp-review-slider-pro').' </b></td><td><b>'.esc_html__('Text', 'wp-review-slider-pro').' </b></td></tr>';
							
							$emailstring = $emailstring . $emailtable . '</table><br><br>';
							
							//finally send the mail here...
							$headers = array('Content-Type: text/html; charset=UTF-8');
							if ( wrsp_fs()->can_use_premium_code() ) {
								//loop through emails and remove admin links if not an admin
								$sendtoemail = $currentforms[$x]['email'];
								$email_data = explode(",",$sendtoemail);
								$subject = $currentforms[$x]['email_subject'];
								if($subject==""){
									$subject=esc_html__('New Reviews Notification - WP Pro Review Slider', 'wp-review-slider-pro');
								}
								 if ( ! empty( $email_data) ) {
									foreach( $email_data as $email) {
										$adminlinks ='';
										$user = get_user_by( 'email', $email);
										
										if ( ! empty( $user ) ) {
											if($user->allcaps['administrator']){
												$siteurl = admin_url();
												$reviewlisturl = $siteurl.'admin.php?page=wp_pro-reviews';
												$loginreviewlisturl = esc_url( wp_login_url( $reviewlisturl ) );
						
												//user is admin, add links
												$adminlinks = '<p><a href="'.$loginreviewlisturl.'" target="_blank" style="text-decoration: none;">'.esc_html__('View in Plugin Admin', 'wp-review-slider-pro').'</a></p><p> '.esc_html__('To turn off or modify these notifications go to the notifications page in the plugin.', 'wp-review-slider-pro').'</p>';
											}
										}
										$emailstringfinal = $emailstring.$adminlinks;
										$adminlinks ='';
										wp_mail( $email, $subject, $emailstringfinal, $headers );
									}
								}
							}
						
						}
					}
					
					//echo "<br>passedtypecheck:".$passedtypecheck;
					//echo "<br>passedpagecheck:".$passedpagecheck."<br>";
					
					
				}
			}
		}
		
		
		
	}
	
	//used to parse out date text.
	function formatdatestring($datesubmitted,$username){
		
		$datesubmitted = str_replace("&nbsp;"," ",$datesubmitted); 
		$datesubmitted = str_replace($username,"",$datesubmitted);
		$datesubmitted = str_replace(" wrote a review ","",$datesubmitted); 
		$datesubmitted = str_replace("Visited ","",$datesubmitted);
		$datesubmitted = str_replace("Visita a ","",$datesubmitted);
		$datesubmitted = str_replace(" ha scritto una recensione a ","",$datesubmitted);
		$datesubmitted = str_replace("Written ","",$datesubmitted);
		$datesubmitted = str_replace("Reviewed ","",$datesubmitted);
		$datesubmitted = str_replace("Scritta in data ","",$datesubmitted); 
		$datesubmitted = str_replace("Date of experience:","",$datesubmitted); 
		$datesubmitted = str_replace("Data dell'esperienza:","",$datesubmitted); 
		$datesubmitted = str_replace("eine Bewertung geschrieben.","",$datesubmitted);
		$datesubmitted = str_replace("hat im","",$datesubmitted);
		$datesubmitted = str_replace("Écrit le ","",$datesubmitted);
		$datesubmitted = str_replace("a écrit un avis","",$datesubmitted);
		$datesubmitted = str_replace("(","",$datesubmitted);
		$datesubmitted = str_replace(")","",$datesubmitted);
		$datesubmitted = str_replace(":","",$datesubmitted);
		$datesubmitted = str_replace("Respondido el ","",$datesubmitted); 
		$datesubmitted = str_replace("Respondido el","",$datesubmitted); 
		$datesubmitted = str_replace("Respondido:","",$datesubmitted); 
		$datesubmitted = str_replace("Responded ","",$datesubmitted); 
		$datesubmitted = str_replace(" г.","",$datesubmitted); 
		$datesubmitted = str_replace("Опубликовано","",$datesubmitted);
		

		$string = htmlentities($datesubmitted, null, 'utf-8');
		$content = str_replace("&nbsp;", " ", $string);
		$datesubmitted = html_entity_decode($content);
		
		$datesubmitted = trim($datesubmitted);

		return $datesubmitted;
	}


	//last name save options================================
	private function changelastname($fullname, $lastnamesaveoption = 'full'){
		//last name display options
		$tempreviewername = stripslashes(strip_tags($fullname));
		$words = explode(" ", $tempreviewername);
		if($lastnamesaveoption!='full'){
			if($lastnamesaveoption=="nothing"){
				$tempreviewername=$words[0];
			} else if($lastnamesaveoption=="initial"){
				$tempfirst = $words[0];
				if(isset($words[1])){
					$templast = $words[1];
					$templast =mb_substr($templast,0,1);
					$tempreviewername = $tempfirst.' '.$templast.'.';
				} else {
					$tempreviewername = $tempfirst;
				}
				
			}
		}
		return $tempreviewername;
	}
	
	//fix stringtotime for other languages
	public function myStrtotime($date_string) { 
		$monthnamearray = array(
		'janvier'=>'jan',
		'février'=>'feb',
		'mars'=>'march',
		'avril'=>'apr',
		'mai'=>'may',
		'juin'=>'jun',
		'juillet'=>'jul',
		'août'=>'aug',
		'septembre'=>'sep',
		'octobre'=>'oct',
		'novembre'=>'nov',
		'décembre'=>'dec',
		'gennaio'=>'jan',
		'febbraio'=>'feb',
		'marzo'=>'march',
		'aprile'=>'apr',
		'maggio'=>'may',
		'giugno'=>'jun',
		'luglio'=>'jul',
		'agosto'=>'aug',
		'settembre'=>'sep',
		'ottobre'=>'oct',
		'novembre'=>'nov',
		'dicembre'=>'dec',
		'janeiro'=>'jan',
		'fevereiro'=>'feb',
		'março'=>'march',
		'abril'=>'apr',
		'maio'=>'may',
		'junho'=>'jun',
		'julho'=>'jul',
		'agosto'=>'aug',
		'setembro'=>'sep',
		'outubro'=>'oct',
		'novembro'=>'nov',
		'dezembro'=>'dec',
		'enero'=>'jan',
		'febrero'=>'feb',
		'marzo'=>'march',
		'abril'=>'apr',
		'mayo'=>'may',
		'junio'=>'jun',
		'julio'=>'jul',
		'agosto'=>'aug',
		'septiembre'=>'sep',
		'octubre'=>'oct',
		'noviembre'=>'nov',
		'diciembre'=>'dec',
		'januari'=>'jan',
		'februari'=>'feb',
		'maart'=>'march',
		'april'=>'apr',
		'mei'=>'may',
		'juni'=>'jun',
		'juli'=>'jul',
		'augustus'=>'aug',
		'september'=>'sep',
		'oktober'=>'oct',
		'november'=>'nov',
		'december'=>'dec',
		' de '=>'',
		'dezember'=>'dec',
		'januar '=>'jan ',
		'stycznia'=>'jan',
		'lutego'=>'feb',
		'februar'=>'feb',
		'marca'=>'march',
		'märz'=>'march',
		'kwietnia'=>'apr',
		'maja'=>'may',
		'czerwca'=>'jun',
		'lipca'=>'jul',
		'sierpnia'=>'aug',
		'września'=>'sep',
		'października'=>'oct',
		'listopada'=>'nov',
		'grudnia'=>'dec',
		'february'=>'feb',
		'января'=>'jan',
		'февраля'=>'feb',
		'марта'=>'march',
		'апреля'=>'apr',
		'мая'=>'may',
		'июня'=>'jun',
		'июля'=>'jul',
		'августа'=>'aug',
		'сентября'=>'sep',
		'октября'=>'oct',
		'ноября'=>'nov',
		'декабря'=>'dec',
		'tháng 1,'=>'jan',
		'tháng 2,'=>'feb',
		'tháng 3,'=>'march',
		'tháng 4,'=>'apr',
		'tháng 5,'=>'may',
		'tháng 6,'=>'jun',
		'tháng 7,'=>'jul',
		'tháng 8,'=>'aug',
		'tháng 9,'=>'sep',
		'tháng 10,'=>'oct',
		'tháng 11,'=>'nov',
		'tháng 12,'=>'dec',
		'augusti'=>'aug',
		'Ιανουαρίου'=>'jan',
		'Φεβρουαρίου'=>'feb',
		'Μαρτίου'=>'march',
		'Απριλίου'=>'apr',
		'Μαΐου'=>'may',
		'Ιουνίου'=>'jun',
		'Ιουλίου'=>'jul',
		'Αυγούστου'=>'aug',
		'Σεπτεμβρίου'=>'sep',
		'Οκτωβρίου'=>'oct',
		'Νοεμβρίου'=>'nov',
		'Δεκεμβρίου'=>'dec',
		'janv.'=>'jan',
		'févr.'=>'feb',
		'mars'=>'march',
		'avril'=>'apr',
		'mai'=>'may',
		'juin'=>'jun',
		'juil.'=>'jul',
		'août'=>'aug',
		'sept.'=>'sep',
		'oct.'=>'oct',
		'nov.'=>'nov',
		'déc.'=>'dec',
		'agustus'=>'aug',
		'genn'=>'jan',
		'gen'=>'jan',
		'febbr'=>'feb',
		'feb'=>'feb',
		'mar'=>'mar',
		'apr'=>'apr',
		'magg'=>'may',
		'mag'=>'may',
		'giugno'=>'jun',
		'giu'=>'jun',
		'luglio'=>'jul',
		'lug'=>'jul',
		'ag'=>'aug',
		'ago'=>'aug',
		'sett'=>'sep',
		'set'=>'sep',
		'ott'=>'oct',
		'nov'=>'nov',
		'dic'=>'dec',
		);
		//genn.	febbr.	mar.	apr.	magg.	giugno	luglio	ag.	sett.	ott.	nov.	dic.
		//echo "::";
		//echo strtolower($date_string);
		//echo "::";
		//echo strtr(strtolower($date_string), $monthnamearray);
		//echo "::";
		return strtotime(strtr(strtolower($date_string), $monthnamearray)); 
	}
	
	//for adding total and averages to new wpfb_total_averages table in database added 11.0.7.4
	//use for 2 cases badge and review template filter, also use for public functions to find avg and total for template, all, or badge, or page id.
	//used to save avg, total or badges, templates, and source pages in database table for easy access.
	public function updateallavgtotalstable(){
		//first clear out table WP_WPFB_TOTAL_AVERAGES for template and badge types
		global $wpdb;
		$table  = $wpdb->prefix . 'wpfb_total_averages';
		$delete = $wpdb->query("DELETE FROM ".$table." WHERE (btp_type='template' OR btp_type='badge')");
		
		//$checkrow = $wpdb->get_var( 'SELECT id FROM '.$table_name.' WHERE reviewer_name = "'.trim($user_name).'" AND type = "Airbnb" AND (review_length_char = "'.$review_length_char.'" OR review_length = "'.$review_length.'" OR created_time_stamp = "'.$unixtimestamp.'")' );
		
		//check to see if we need to delete any old non used pageids totals and averages
		$tempquery = "SELECT btp_id,btp_name FROM ".$table." WHERE btp_id IS NOT NULL GROUP BY btp_id";
		$temppages = $wpdb->get_results($tempquery,ARRAY_A);
		$table_name = $wpdb->prefix . 'wpfb_reviews';
		if(count($temppages)>0){
			foreach ($temppages as &$page) {
				//now see if there is any reviews with the pageid
				$temppageid = $page['btp_id'];
				$checkrow = $wpdb->get_var( "SELECT id FROM ".$table_name." WHERE pageid='".$temppageid ."' " );
				//echo $temppageid;
				if($checkrow){
					//echo "-found";
				}else{
					//echo "-not found, delete";
					$deleterow = $wpdb->query("DELETE FROM ".$table." WHERE btp_id='".$temppageid."'");
				}
		
			}
		}
		
		$this->updateallavgtotalstable_pages();
		$this->updateallavgtotalstable_templates();
		$this->updateallavgtotalstable_badges();
	}
	public function updateallavgtotalstable_pages(){
		global $wpdb;
		//first update all source page totals
		//get an array of all pageids and loop through them recalculating
		$reviews_table_name = $wpdb->prefix . 'wpfb_reviews';
		$tempquery = "SELECT pageid,pagename FROM ".$reviews_table_name." WHERE pageid IS NOT NULL GROUP BY pageid";
		$temppages = $wpdb->get_results($tempquery,ARRAY_A);
		if(count($temppages)>0){
			//loop array and calculate
			//print_r($temppageids);
			foreach ($temppages as &$page) {
				$this->updatetotalavgreviews('', $page['pageid'], '', '',$page['pagename'] );
		
			}
		}
	}
	

	public function updateallavgtotalstable_templates(){
		global $wpdb;
		//now update all template totals and averages
		//select all templates and loop through each updating the total and avg
		$table_name = $wpdb->prefix . 'wpfb_post_templates';
		$currentformsobj = $wpdb->get_results("SELECT * FROM $table_name");
		if(count($currentformsobj)>0){
			require_once WPREV_PLUGIN_DIR . 'public/partials/getreviews_class.php';
			$reviewsclass = new GetReviews_Functions();
			foreach ($currentformsobj as &$singleform) {
				//fix for naming
				$currentform[0]=$singleform;
				//turn on load more so we can totals and avgs
				$currentform[0]->load_more='yes';
				$totalreviewsarray = $reviewsclass->wppro_queryreviews($currentform);

				$valuearray['btp_id'] = "template_".$singleform->id;
				$valuearray['btp_name'] = $singleform->title;
				$valuearray['total_indb']= $totalreviewsarray['totalcount'];
				$valuearray['avg_indb']= $totalreviewsarray['totalavg'];
				$valuearray['numr1']=$totalreviewsarray['numr1'];
				$valuearray['numr2']=$totalreviewsarray['numr2'];
				$valuearray['numr3']=$totalreviewsarray['numr3'];
				$valuearray['numr4']=$totalreviewsarray['numr4'];
				$valuearray['numr5']=$totalreviewsarray['numr5'];
				
				
				$temppageidarray = $totalreviewsarray['reviewspagesarray'];
				//echo "here: <br>";
				//print_r($temppageidarray);
				
				$temptotalarray = Array();
				$tempavgarray = Array();
				$temptypebreakdown = Array();
				foreach ($temppageidarray as &$singlepage) {
					//query the total average table for this single page and return values.
					$table_name_temp = $wpdb->prefix . 'wpfb_total_averages';
					$currentpageval = $wpdb->get_results("SELECT * FROM $table_name_temp WHERE `btp_id` = '".$singlepage."' ",ARRAY_A );
					if(isset($currentpageval[0])){
						//fall back to what was downloaded if this is blank.
						if($currentpageval[0]['total']>0){
							$temptotalarray[]=$currentpageval[0]['total'];
						} else {
							$temptotalarray[]=$currentpageval[0]['total_indb'];
						}
						$tempavgarray[]=$currentpageval[0]['avg'];
						
						$temptype = $currentpageval[0]['pagetype'];
						$temptypebreakdown[$temptype]['total'][] =$currentpageval[0]['total'];
						$temptypebreakdown[$temptype]['avg'][] =$currentpageval[0]['avg'];
						$temptypebreakdown[$temptype]['total_indb'][] =$currentpageval[0]['total_indb'];
						$temptypebreakdown[$temptype]['avg_indb'][] =$currentpageval[0]['avg_indb'];
					}
				}
				//now we need total of totals and avg of averages.
				//get rid of blanks and zeros.
				$tempavgarray = array_filter($tempavgarray);
				if(count($tempavgarray)>0){
					$reviewratingsarrayavg = array_sum($tempavgarray)/count($tempavgarray);
				} else {
					$reviewratingsarrayavg = 0;
				}
				
				$valuearray['avg']= round($reviewratingsarrayavg,1);
				$valuearray['total']= array_sum($temptotalarray);
				
				//need an array of tots and avg for each page type json encode it.
				//print_r($temptypebreakdown);
				//loop and combine if there are more than one per.
				foreach ($temptypebreakdown as $x => $val) {
					$temptypebreakdown[$x]['total'] = array_sum($val['total']);
					$temptypebreakdown[$x]['total_indb'] = array_sum($val['total_indb']);
					
					//now we need weighted averages if count greater than 1
					$avg = array_filter($val['avg']);
					$wavg[]='';
					$wtot[]='';
					if(count($avg)>1){
						//have an array need weighted average.
						$n=0;
						foreach ($avg as $singleavg) {
							$wavg[$n] =  (float)$singleavg*(int)$val['total'][$n];
							$wtot[$n] =  $val['total'][$n];
							$n++;
						}
						if(array_sum($wtot)>0){
							$we_avg = round(array_sum($wavg)/array_sum($wtot),1);
							$temptypebreakdown[$x]['avg'] =$we_avg;
						}
						unset($wavg);
						unset($wtot);
						
					} else if(count($avg)==1){
						//now we need averages.
						$temptypebreakdown[$x]['avg'] = round(array_sum($avg)/count($avg),1);
					} else {
						$temptypebreakdown[$x]['avg'] = '';
					}
					
					//now for indb
					$avg_indb = array_filter($val['avg_indb']);
					if(count($avg_indb)>1){
						//have an array need weighted average.
						$n=0;
						foreach ($avg_indb as $singleavg) {
							$wavg[$n] =  (float)$singleavg*(int)$val['total'][$n];
							$wtot[$n] =  $val['total'][$n];
							$n++;
						}
						if(array_sum($wtot)>0){
							$we_avg = round(array_sum($wavg)/array_sum($wtot),1);
							$temptypebreakdown[$x]['avg_indb'] =$we_avg;
						}
						unset($wavg);
						unset($wtot);
						
					} else if(count($avg_indb)==1){
						//now we need averages.
						$temptypebreakdown[$x]['avg_indb'] = round(array_sum($avg_indb)/count($avg_indb),1);
					} else {
						$temptypebreakdown[$x]['avg_indb'] = '';
					}


				}
				//echo "new";
				//print_r($temptypebreakdown);  number_format(1.0000, 2, '.', ',');

				$valuearray['pagetypedetails']=json_encode($temptypebreakdown); 


				//find the source pages this template uses, then get the average and total for each page and average them and save back.
				$this->updatetotalavgreviewstableinsert('template',$valuearray);
				
			}
		}
	}
	public function updateallavgtotalstable_badges(){
		global $wpdb;
		//now updating all badges
		$table_name = $wpdb->prefix . 'wpfb_badges';
		$currentbadgesobj = $wpdb->get_results("SELECT * FROM $table_name");
		if(count($currentbadgesobj)>0){
			require_once WPREV_PLUGIN_DIR . 'public/partials/badge_class.php';	
			foreach ($currentbadgesobj as &$singlebadge) {
				$badgeid = $singlebadge->id;
				$badgetools = new badgetools($badgeid);
				//fix for naming
				$currentform[0]=$singlebadge;
				$badgetotalavgarray = $badgetools->gettotalsaverages();
				//print_r($badgetotalavgarray);
				$valuearray['btp_id'] = "badge_".$singlebadge->id;
				$valuearray['btp_name'] = $singlebadge->title;
				$valuearray['total_indb']= $badgetotalavgarray['finaltotal'];
				$valuearray['avg_indb']= $badgetotalavgarray['finalavg'];
				$temprating = $badgetotalavgarray['temprating'];
				$valuearray['numr1']=array_sum($temprating[1]);
				$valuearray['numr2']=array_sum($temprating[2]);
				$valuearray['numr3']=array_sum($temprating[3]);
				$valuearray['numr4']=array_sum($temprating[4]);
				$valuearray['numr5']=array_sum($temprating[5]);
				//echo "<br>badgeid:".$badgeid;
				//print_r($badgetotalavgarray);
				$this->updatetotalavgreviewstableinsert('badge',$valuearray);
			}
		}
	}
	
	//used to actually insert the values from function above
	public function updatetotalavgreviewstableinsert($btp_type,$valuearray){
		global $wpdb;
		$table_name_totalavg = $wpdb->prefix . 'wpfb_total_averages';
		$key = $valuearray['btp_id'];
		$name = $valuearray['btp_name'];
		$temp_total_indb=$valuearray['total_indb'];
		$temp_total='';
		if(isset($valuearray['total'])){
			$temp_total=intval($valuearray['total']);
		}
		$temp_avg_indb=$valuearray['avg_indb'];
		$temp_avg='';
		if(isset($valuearray['avg'])){
			$temp_avg=$valuearray['avg'];
		}
		$temp_numr1=$valuearray['numr1'];
		$temp_numr2=$valuearray['numr2'];
		$temp_numr3=$valuearray['numr3'];
		$temp_numr4=$valuearray['numr4'];
		$temp_numr5=$valuearray['numr5'];
		$pagetype = '';
		if(isset($valuearray['pagetype'])){
			$pagetype=$valuearray['pagetype'];
		}
		$pagetypedetails = '';	//json encoded array of total & average for each type (google, fb, etc)
		if(isset($valuearray['pagetypedetails'])){
			$pagetypedetails=$valuearray['pagetypedetails'];
		}
		
		if($temp_avg=='' || $temp_total==''){
			$data = array( 
					'btp_id' => "$key",
					'btp_name' => "$name",
					'btp_type' => "$btp_type",
					'pagetype' => "$pagetype",
					'pagetypedetails' => "$pagetypedetails",
					'total_indb' => "$temp_total_indb",
					'avg_indb' => "$temp_avg_indb",
					'numr1' => "$temp_numr1",
					'numr2' => "$temp_numr2",
					'numr3' => "$temp_numr3",
					'numr4' => "$temp_numr4",
					'numr5' => "$temp_numr5",
					);
				
			$format = array('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s');
		} else {
			$data = array( 
					'btp_id' => "$key",
					'btp_name' => "$name",
					'btp_type' => "$btp_type",
					'pagetype' => "$pagetype",
					'pagetypedetails' => "$pagetypedetails",
					'total_indb' => "$temp_total_indb",
					'total' => "$temp_total",
					'avg_indb' => "$temp_avg_indb",
					'avg' => "$temp_avg",
					'numr1' => "$temp_numr1",
					'numr2' => "$temp_numr2",
					'numr3' => "$temp_numr3",
					'numr4' => "$temp_numr4",
					'numr5' => "$temp_numr5",
					);
				
			$format = array('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s');
		}
		
		$insertrow = $wpdb->replace( $table_name_totalavg, $data, $format );
		
		//print_r($data);
		
		//var_dump( $wpdb->last_query );
			//echo "errors should show here";
			//$wpdb->show_errors();
			//$wpdb->print_error();
			//die();
	}

	
	//-----for updating options for total and avg based on pageid
	public function updatetotalavgreviews($type, $pageid, $avg, $total, $pagename = ''){
		
		//echo "updating total and averages:".$type."-".$pageid;
		
		$ratingsarray= array();
		$pagetype='';
		global $wpdb;
		$table_name = $wpdb->prefix . 'wpfb_reviews';
		//fix for comma in some languages
		$avg = str_replace(",",".",$avg);
		$option = 'wppro_total_avg_reviews';

		//get existing option array of fb cron pages
		$wppro_total_avg_reviews_array = get_option( $option );
		if(isset($wppro_total_avg_reviews_array)){
			$wppro_total_avg_reviews_array = json_decode($wppro_total_avg_reviews_array, true);
		} else {
			$wppro_total_avg_reviews_array = array();
		}
			$field_name = 'rating';
			$prepared_statement = $wpdb->prepare( "SELECT rating, recommendation_type, type FROM {$table_name} WHERE hide != %s AND pageid = %s AND translateparent = ''", 'yes', $pageid);
			$fbreviews = $wpdb->get_results( $prepared_statement );
			
			foreach ( $fbreviews as $fbreview ){
					//echo $fbreview->post_title;
					if($fbreview->rating>0){
						$tempnum=$fbreview->rating;
					} else if($fbreview->recommendation_type=='positive'){
						$tempnum=5;
					} else if($fbreview->recommendation_type=='negative'){
						$tempnum=2;
					}
					if(isset($tempnum)){
					$ratingsarray[]=$tempnum;
					}
					
					$pagetype = $fbreview->type;
				}
			
			//print_r($ratingsarray);
			$ratingsarray = array_filter($ratingsarray);
			if(count($ratingsarray)>0){
				$avgdb = round(array_sum($ratingsarray) / count($ratingsarray), 3);
				$totaldb =  round(count($ratingsarray), 0);
				$wppro_total_avg_reviews_array[$pageid]['total_indb'] = $totaldb;
				if($avg>0){
					$wppro_total_avg_reviews_array[$pageid]['avg'] = round($avg,3);
				} else {
					//$wppro_total_avg_reviews_array[$pageid]['avg'] = $avgdb;
				}
				if($total>0){
					$wppro_total_avg_reviews_array[$pageid]['total'] = $total;
				} else {
					//$wppro_total_avg_reviews_array[$pageid]['total'] = $totaldb;
				}
				$wppro_total_avg_reviews_array[$pageid]['total_indb'] = $totaldb;
				$wppro_total_avg_reviews_array[$pageid]['avg_indb'] = $avgdb;
			}
		//print_r($ratingsarray);
		//ratings for badge 2
		$temprating = $this->wprp_get_temprating($ratingsarray);
		if(isset($temprating)){
			$wppro_total_avg_reviews_array[$pageid]['numr1'] = array_sum($temprating[1]);
			$wppro_total_avg_reviews_array[$pageid]['numr2'] = array_sum($temprating[2]);
			$wppro_total_avg_reviews_array[$pageid]['numr3'] = array_sum($temprating[3]);
			$wppro_total_avg_reviews_array[$pageid]['numr4'] = array_sum($temprating[4]);
			$wppro_total_avg_reviews_array[$pageid]['numr5'] = array_sum($temprating[5]);
		}

		$new_value = json_encode($wppro_total_avg_reviews_array, JSON_FORCE_OBJECT);
		update_option( $option, $new_value);
		
		//added in 10.9.3 now adding this to table wpfb_total_averages---------
		//will enventually replace the options save

			$valuearray['btp_id']=$pageid;
			$valuearray['btp_name'] = $pagename;
			$valuearray['pagetype']= $pagetype;
			$valuearray['total']='';
			if(isset($wppro_total_avg_reviews_array[$pageid]['total'])){
				$valuearray['total']=$wppro_total_avg_reviews_array[$pageid]['total'];
			}
			$valuearray['total_indb']='';
			if(isset($wppro_total_avg_reviews_array[$pageid]['total_indb'])){
			$valuearray['total_indb']=$wppro_total_avg_reviews_array[$pageid]['total_indb'];
			}
			$valuearray['avg']='';
			if(isset($wppro_total_avg_reviews_array[$pageid]['avg'])){
				$valuearray['avg']=$wppro_total_avg_reviews_array[$pageid]['avg'];
			}
			$valuearray['avg_indb']='';
			if(isset($wppro_total_avg_reviews_array[$pageid]['avg_indb'])){
			$valuearray['avg_indb']=$wppro_total_avg_reviews_array[$pageid]['avg_indb'];
			}
			$valuearray['numr1']=$wppro_total_avg_reviews_array[$pageid]['numr1'];
			$valuearray['numr2']=$wppro_total_avg_reviews_array[$pageid]['numr2'];
			$valuearray['numr3']=$wppro_total_avg_reviews_array[$pageid]['numr3'];
			$valuearray['numr4']=$wppro_total_avg_reviews_array[$pageid]['numr4'];
			$valuearray['numr5']=$wppro_total_avg_reviews_array[$pageid]['numr5'];
			
			//print_r($valuearray);

			$this->updatetotalavgreviewstableinsert('page',$valuearray);
		//---------------------------
		//go ahead and update the total and avg for badge here since something has been downloaded.
		$this->updateallavgtotalstable_templates();
		$this->updateallavgtotalstable_badges();
	}
	
	//used to get back number of ratings for each value
	private function wprp_get_temprating($ratingsarray){
		//print_r($ratingsarray);
		//fist set to blank instead of null
		for ($x = 0; $x <= 5; $x++) {
			$temprating[$x][]=0;
		}
		foreach ( $ratingsarray as $tempnum ) 
		{
			//need to round tempnum to int.
			$tempnum = round($tempnum);
			//need to count number of each rating
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
		return $temprating;
	}
	
	
	/**
	 * Ajax, save review from review list page ajax
	 * @access  admin
	 * @since   11.0.7
	 * @return  void
	 */
	public function wprp_savereview_admin_ajax(){
		$formdata = stripslashes($_POST['data']);
		$formarray = json_decode($formdata,true);
		//print_r($formarray);
		
		check_ajax_referer('randomnoncestring', 'wpfb_nonce');
		$dbmsg='';
		
		global $wpdb;
		$table_name = $wpdb->prefix . 'wpfb_reviews';

		//get form submission values and then save or update
		//$t_id = sanitize_text_field($formarray['edittid']);
		
		//get form submission values and then save or update
		$r_id = sanitize_text_field($formarray['editrid']);
		$r_editrtype = sanitize_text_field($formarray['editrtype']);
		if($r_editrtype==""){
			$r_editrtype="Manual";
		}
		$rating ='';
		if(isset($formarray['wprevpro_nr_rating'])){
		$rating = sanitize_text_field($formarray['wprevpro_nr_rating']);
		}
		$title =  sanitize_text_field($formarray['wprevpro_nr_title']);
		//sanitize but keep html wp_kses( $html_message );
		//$arr = array('br' => array(), 'p' => array(), 'strong' => array());
		//$text = wp_kses($formarray['wprevpro_nr_text'],$arr);
		$text = wp_kses_post($formarray['wprevpro_nr_text']);
		//$text = sanitize_textarea_field($formarray['wprevpro_nr_text']);
		
		
		$name = sanitize_text_field($formarray['wprevpro_nr_name']);
		$email = sanitize_text_field($formarray['wprevpro_nr_email']);
		$location = sanitize_text_field($formarray['wprevpro_nr_location']);
		$company_name = sanitize_text_field($formarray['wprevpro_nr_company_name']);
		$company_title = sanitize_text_field($formarray['wprevpro_nr_company_title']);
		$company_url = esc_url_raw($formarray['wprevpro_nr_company_url']);
		$avatar_url = esc_url_raw($formarray['wprevpro_nr_avatar_url']);
		$rdate = sanitize_text_field($formarray['wprevpro_nr_date']);
		$hidestars ='';
		if(isset($formarray['wprevpro_nr_hidestars'])){
		$hidestars = sanitize_text_field($formarray['wprevpro_nr_hidestars']);
		}
		
		$language_code = sanitize_text_field($formarray['wprevpro_nr_lang']);
		$tags = sanitize_text_field($formarray['wprevpro_nr_tags']);
		
		//echo "tags:".$tags;
		
		$tagsarray = explode(",", $tags);
		$tagsarray = array_map('trim', $tagsarray); 	//trim tags
		if(count($tagsarray)>0){
			//$tagsjson = json_encode($tagsarray);
			$tagsjson =json_encode($tagsarray,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
		} else {
			$tagsjson ='';
		}
		//echo "tagsjson:".$tagsjson;
		
		$from='';
		$from_url = '';
		$from_logo ='';
		
		if(isset($formarray['wprevpro_nr_from'])){
			$from = sanitize_text_field($formarray['wprevpro_nr_from']);
		}
		if(isset($formarray['wprevpro_nr_from_url'])){
		$from_url = esc_url_raw($formarray['wprevpro_nr_from_url']);
		}
		if(isset($formarray['wprevpro_nr_logo_url'])){
		$from_logo = esc_url_raw($formarray['wprevpro_nr_logo_url']);
		}
				
		$time = strtotime($rdate);
		$newdateformat = date('Y-m-d H:i:s',$time);

		$review_length = substr_count($text, ' ');
		//fix for one word reviews
		if($review_length==0 && strlen($text)>0){
			$review_length=1;
		}
		
		//if $rating is blank then set recommendation_type as positive
		$recommendation_type='';
		if($rating==""){
			$recommendation_type='positive';
		}

		
		if(!isset($formarray['wprevpro_nr_pageid'])){
			$formarray['wprevpro_nr_pageid']='';
		}
		if(!isset($formarray['wprevpro_nr_pagename'])){
			$formarray['wprevpro_nr_pagename']='';
		}
		$pageid = sanitize_text_field($formarray['wprevpro_nr_pageid']);
		$pagename = sanitize_text_field($formarray['wprevpro_nr_pagename']);

		if($r_editrtype=="Manual"){
			if($r_id=="" && $formarray['wprevpro_nr_pageid']==''){
				$pageid = "manually_added";
				$pagename= "Manually Added";
			} else {
				$pageid = sanitize_text_field($formarray['wprevpro_nr_pageid']);
				$pagename = sanitize_text_field($formarray['wprevpro_nr_pagename']);
				if($pagename==''){
					$pagename= "Manually Added";
				}
			}
		}
		
		//save last input custom logo and wprevpro_nr_logo_url, wprevpro_nr_from_url
		$customlastsave = array($from_logo, $from_url);
		update_option( 'wprevpro_customlastsave', $customlastsave );
		
		//convert to json, function in class-wp-review-slider-pro-admin-common.php
		require_once WPREV_PLUGIN_DIR . 'admin/class-wp-review-slider-pro-admin-common.php';
		$plugin_admin_common = new Common_Admin_Functions();
		
		$catids = sanitize_text_field($formarray['wprevpro_nr_categories']);
		$catidsarrayjson ='';
		if($catids!=''){
		$catidsarrayjson = $plugin_admin_common->wprev_commastrtojson($catids,true);
		}
 
		$postid = sanitize_text_field($formarray['wprevpro_nr_postid']);
		$postidsarrayjson ='';
		if($postid!=''){
		$postidsarrayjson = $plugin_admin_common->wprev_commastrtojson($postid,true);
		}
		
		//find character length
		if (extension_loaded('mbstring')) {
			$review_length_char = mb_strlen($text);
		} else {
			$review_length_char = strlen($text);
		}
		
		//update owner response if needed
		//owner_response {"id":71320417,"name":"Response from the owner","date":"2020-06-05","comment":"Thank You will Matsch "}
		$owner['id'] = sanitize_text_field($formarray['wprevpro_owner_id']);
		$owner['name'] = sanitize_text_field($formarray['wprevpro_owner_name']);
		$owner['comment'] = sanitize_textarea_field($formarray['wprevpro_owner_text']);
		$owner['date'] = sanitize_text_field($formarray['wprevpro_owner_date']);
		if($owner['comment']!=''){
			$owner_response_encode = json_encode($owner);
		} else {
			$owner_response_encode ='';
		}
		
		//media fields
		$mediaurlsarray = ($formarray['wprevpro_media']);
		$mediaurlsthumbarray = ($formarray['wprevpro_mediathumb']);
		
		//loop media array and see if there is a youtube video with no thumbnail, if so then get thumbnail 
		for ($i = 0; $i < count($mediaurlsarray); $i++)  {
			$tempmedia=$mediaurlsarray[$i];
			if (strpos($tempmedia, 'youtu.be') !== false || strpos($tempmedia, 'youtube') !== false) {
				//youtube, see if there is a thumbnail
				if($mediaurlsthumbarray[$i]=="" ){
					preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $tempmedia, $matches);
					//var_dump($matches);
					$youtube_id = $matches[1];
					if(isset($youtube_id) && $youtube_id!=''){
					$mediaurlsthumbarray[$i] = "https://img.youtube.com/vi/".$youtube_id."/sddefault.jpg";
					}
				}
			}
		}
		
		//encode to save in db
		$mediaurlsarrayjson = json_encode($mediaurlsarray);
		$mediathumburlsarrayjson = json_encode($mediaurlsthumbarray);
		
		//insert or update
			$data = array( 
				'pageid' => "$pageid",
				'pagename' => "$pagename",
				'rating' => "$rating",
				'recommendation_type' => "$recommendation_type",
				'review_text' => "$text",
				'reviewer_name' => "$name",
				'reviewer_email' => "$email",
				'location' => "$location",
				'company_name' => "$company_name",
				'created_time' => "$newdateformat",
				'created_time_stamp' => "$time",
				'userpic' => "$avatar_url",
				'review_length' => "$review_length",
				'review_length_char' => "$review_length_char",
				'type' => "$r_editrtype",
				'from_name' => "$from",
				'from_url' => "$from_url",
				'from_logo' => "$from_logo",
				'review_title' => "$title",
				'company_title' => "$company_title",
				'company_url' => "$company_url",
				'categories' => "$catidsarrayjson",
				'posts' => "$postidsarrayjson",
				'hidestars' => "$hidestars",
				'userpiclocal' => "",
				'language_code' => "$language_code",
				'owner_response' => "$owner_response_encode",
				'tags' => "$tagsjson",
				'mediaurlsarrayjson' => "$mediaurlsarrayjson", 
				'mediathumburlsarrayjson' => "$mediathumburlsarrayjson"
				);
			$format = array( 
					'%s',
					'%s',
					'%d',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%d',
					'%s',
					'%d',
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
					'%s',
					'%s'
				); 

		if($r_id==""){
			//insert
			//$wpdb->suppress_errors(false);
			$insertid = $wpdb->insert( $table_name, $data, $format );
			if($insertid>0){
				$dbmsg = $insertid.'-'.__('Review Inserted!', 'wp-review-slider-pro');
			} else {
				$dbmsg = '0-'.__('Oops! Something happened. '.$wpdb->print_error(), 'wp-review-slider-pro');
			}
			//echo "errors should show here";
			//$wpdb->show_errors();
			//$wpdb->print_error();
			//die();
		} else {
			//update
			$updatetempquery = $wpdb->update($table_name, $data, array( 'id' => $r_id ), $format, array( '%d' ));
			//$wpdb->show_errors();
			//$wpdb->print_error();
			//die();
			if($updatetempquery>0){
				$dbmsg = $r_id.'-'.__('Review Updated!', 'wp-review-slider-pro');
				
				//delete cached avatar
				$img_locations_option = json_decode(get_option( 'wprev_img_locations' ),true);
				$imagecachedir =$img_locations_option['upload_dir_wprev_cache'];
				$name = preg_replace("/[^a-zA-Z]+/", "", $name);
				$newfilename = $time.'_'.strtolower($name)."_".$r_id;
				
				$newfile = $imagecachedir . $newfilename.'.jpg';
				$newfile60 = $imagecachedir . $newfilename.'_60.jpg';
				$newfilepng = $imagecachedir . $newfilename.'.png';
				$newfile60png = $imagecachedir . $newfilename.'_60.png';
				
				if(file_exists($newfile)){				
				@unlink($newfile);
				}
				if(file_exists($newfile60)){
				@unlink($newfile60);
				}
				if(file_exists($newfilepng)){
				@unlink($newfilepng);
				}
				if(file_exists($newfile60png)){
				@unlink($newfile60png);
				}
				
				//echo $newfile;
				
				//delete localfile url only if we arent' using it again
				if (strpos($avatar_url, 'uploads/wprevslider/avatars') !== false) {
					//echo 'true';
				} else {
					$imageuploadedir =$img_locations_option['upload_dir_wprev_avatars'];
					$filename = $time.'_'.$r_id;
					$newfile = $imageuploadedir . $filename.'.jpg';
					if(file_exists($newfile)){
					@unlink($newfile);
					}
				}
				
			} else {
				$dbmsg = '0-'.__('Oops! Something happened. '.$wpdb->print_error(), 'wp-review-slider-pro');
			}
		}
		
		//update avg and total for this typ================
		//require_once WPREV_PLUGIN_DIR . 'admin/class-wp-review-slider-pro-admin_hooks.php';
		//$plugin_admin_hooks = new WP_Review_Pro_Admin_Hooks( $this->get_token(), $this->get_version() );
		
		$temptype = $r_editrtype;
		$temptypelower = strtolower($r_editrtype);
		$this->updatetotalavgreviews($temptypelower, $pageid, '', '' );
		
		echo $dbmsg;
		die();
	}


	/**
	 * Ajax, save review template to db
	 * @access  admin
	 * @since   11.0.7
	 * @return  void
	 */
	public function wprp_savetemplate_ajax(){
		$formdata = stripslashes($_POST['data']);
		$formarray = json_decode($formdata,true);
		//print_r($formarray);
		//die();
		
		check_ajax_referer('randomnoncestring', 'wpfb_nonce');
		
		global $wpdb;
		$table_name = $wpdb->prefix . 'wpfb_post_templates';

		//get form submission values and then save or update
		$t_id = sanitize_text_field($formarray['edittid']);
		$title = sanitize_text_field($formarray['wprevpro_template_title']);
		$template_type = sanitize_text_field($formarray['wprevpro_template_type']);
		$style = sanitize_text_field($formarray['wprevpro_template_style']);
		$display_num = sanitize_text_field($formarray['wprevpro_t_display_num']);
		$display_num_rows = sanitize_text_field($formarray['wprevpro_t_display_num_rows']);
		$display_order = sanitize_text_field($formarray['wprevpro_t_display_order']);
		$display_order_second = sanitize_text_field($formarray['wprevpro_t_display_order_second']);
		$display_order_limit = sanitize_text_field($formarray['wprevpro_t_display_order_limit']);
		$hide_no_text = sanitize_text_field($formarray['wprevpro_t_hidenotext']);
		$template_css = sanitize_textarea_field($formarray['wprevpro_template_css']);
		
		$createslider = sanitize_text_field($formarray['wprevpro_t_createslider']);
		$numslides = sanitize_text_field($formarray['wprevpro_t_numslides']);
		
		$load_more = sanitize_text_field($formarray['wprevpro_t_load_more']);
		$load_more_text = sanitize_text_field($formarray['wprevpro_t_load_more_text']);
		
		$read_more = sanitize_text_field($formarray['wprevpro_t_read_more']);
		//$read_more_num = sanitize_text_field($formarray['wprevpro_t_read_more_num']);
		$read_more_text = sanitize_text_field($formarray['wprevpro_t_read_more_text']);
		$read_less_text = sanitize_text_field($formarray['wprevpro_t_read_less_text']);

		$facebook_icon = sanitize_text_field($formarray['wprevpro_t_facebook_icon']);
		$facebook_icon_link = sanitize_text_field($formarray['wprevpro_t_facebook_icon_link']);
		
		$google_snippet_add = sanitize_text_field($formarray['wprevpro_t_google_snippet_add']);
		$google_snippet_type = sanitize_text_field($formarray['wprevpro_t_google_snippet_type']);
		$google_snippet_name = sanitize_text_field($formarray['wprevpro_t_google_snippet_name']);
		$google_snippet_desc = sanitize_text_field($formarray['wprevpro_t_google_snippet_desc']);
		$google_snippet_business_image = esc_url_raw($formarray['wprevpro_t_google_snippet_business_image']);
		
		//added snippet fields for businessrichsnippetfields
		$google_snippet_more_phone = '';
		$google_snippet_more_price = '';
		$google_snippet_more_street = '';
		$google_snippet_more_city = '';
		$google_snippet_more_state = '';
		$google_snippet_more_zip ='';
		$google_snippet_prodbrand = '';
		$google_snippet_prodprice = '';
		$google_snippet_prodpricec = '';
		$google_snippet_prodsku = '';
		$google_snippet_prodginame = '';
		$google_snippet_prodgival = '';
		$google_snippet_produrl = '';
		$google_snippet_prodavailability = '';
		$google_snippet_prodpriceValidUntil = '';
			
		if($google_snippet_type!='Product'){
			$google_snippet_more_phone = sanitize_text_field($formarray['wprevpro_t_google_snippet_more_phone']);
			$google_snippet_more_price = sanitize_text_field($formarray['wprevpro_t_google_snippet_more_price']);
			$google_snippet_more_street = sanitize_text_field($formarray['wprevpro_t_google_snippet_more_street']);
			$google_snippet_more_city = sanitize_text_field($formarray['wprevpro_t_google_snippet_more_city']);
			$google_snippet_more_state = sanitize_text_field($formarray['wprevpro_t_google_snippet_more_state']);
			$google_snippet_more_zip = sanitize_text_field($formarray['wprevpro_t_google_snippet_more_zip']);
		} else {
			$google_snippet_prodbrand = sanitize_text_field($formarray['wprevpro_t_google_snippet_prodbrand']);
			$google_snippet_prodprice = sanitize_text_field($formarray['wprevpro_t_google_snippet_prodprice']);
			$google_snippet_prodpricec = sanitize_text_field($formarray['wprevpro_t_google_snippet_prodpricec']);
			$google_snippet_prodsku = sanitize_text_field($formarray['wprevpro_t_google_snippet_prodsku']);
			$google_snippet_prodginame = sanitize_text_field($formarray['wprevpro_t_google_snippet_prodginame']);
			$google_snippet_prodgival = sanitize_text_field($formarray['wprevpro_t_google_snippet_prodgival']);
			$google_snippet_produrl = sanitize_text_field($formarray['wprevpro_t_google_snippet_produrl']);
			$google_snippet_prodavailability = sanitize_text_field($formarray['wprevpro_t_google_snippet_prodavailability']);
			$google_snippet_prodpriceValidUntil = sanitize_text_field($formarray['wprevpro_t_google_snippet_prodpriceValidUntil']);
		}

		$google_snippet_irm = sanitize_text_field($formarray['wprevpro_t_google_snippet_irm']);
		$google_snippet_irm_type = sanitize_text_field($formarray['wprevpro_t_google_snippet_irm_type']);
		
		$google_snippet_schemaid = sanitize_text_field($formarray['wprevpro_t_google_snippet_more_schemaid']);
		
		$google_snippet_tvr = sanitize_text_field($formarray['wprevpro_t_google_snippet_tvr']);
		
	
		$google_snippet_more_array = array("schemaid"=>"$google_snippet_schemaid","telephone"=>"$google_snippet_more_phone","priceRange"=>"$google_snippet_more_price","streetAddress"=>"$google_snippet_more_street","addressLocality"=>"$google_snippet_more_city","addressRegion"=>"$google_snippet_more_state","postalCode"=>"$google_snippet_more_zip","brand"=>"$google_snippet_prodbrand","price"=>"$google_snippet_prodprice","priceCurrency"=>"$google_snippet_prodpricec","sku"=>"$google_snippet_prodsku","giname"=>"$google_snippet_prodginame","gival"=>"$google_snippet_prodgival","url"=>"$google_snippet_produrl","availability"=>"$google_snippet_prodavailability","priceValidUntil"=>"$google_snippet_prodpriceValidUntil","irm"=>"$google_snippet_irm","irm_type"=>"$google_snippet_irm_type","tvr"=>"$google_snippet_tvr");

		//encode to save in database
		$google_snippet_more_array_encode = json_encode($google_snippet_more_array);
		$cache_settings = sanitize_text_field($formarray['wprevpro_t_cache_settings']);
		
		$add_profile_link = sanitize_text_field($formarray['wprevpro_t_profile_link']);
		
		$display_masonry = sanitize_text_field($formarray['wprevpro_t_display_masonry']);
		
		//pro settings
		$canusepremiumcode = wrsp_fs()->can_use_premium_code();
		if ( $canusepremiumcode ) {
			$sliderautoplay = sanitize_text_field($formarray['wprevpro_sliderautoplay']);
			$sliderdirection = sanitize_text_field($formarray['wprevpro_sliderdirection']);
			$sliderarrows = sanitize_text_field($formarray['wprevpro_sliderarrows']);
			$sliderdots = sanitize_text_field($formarray['wprevpro_sliderdots']);
			$sliderdelay = sanitize_text_field($formarray['wprevpro_t_sliderdelay']);
			$sliderspeed = sanitize_text_field($formarray['wprevpro_t_sliderspeed']);
			$sliderheight = sanitize_text_field($formarray['wprevpro_sliderheight']);
			$slidermobileview = sanitize_text_field($formarray['wprevpro_slidermobileview']);
			$min_rating = sanitize_text_field($formarray['wprevpro_t_min_rating']);
			$min_words = sanitize_text_field($formarray['wprevpro_t_min_words']);
			$max_words = sanitize_text_field($formarray['wprevpro_t_max_words']);
			$word_or_char = sanitize_text_field($formarray['wprevpro_t_word_or_char']);
			$string_sel = sanitize_text_field($formarray['wprevpro_t_string_sel']);
			$string_text = sanitize_text_field($formarray['wprevpro_t_string_text']);
			$string_selnot = sanitize_text_field($formarray['wprevpro_t_string_selnot']);
			$string_textnot = sanitize_text_field($formarray['wprevpro_t_string_textnot']);
			$showreviewsbyid = sanitize_text_field($formarray['wprevpro_t_showreviewsbyid']);
			$review_same_height = sanitize_text_field($formarray['wprevpro_t_review_same_height']);
			$showreviewsbyid_sel= sanitize_text_field($formarray['wprevpro_t_showreviewsbyid_sel']);
		} else {
			$sliderautoplay = "";
			$sliderdirection = "";
			$sliderarrows = "";
			$sliderdots = "";
			$sliderdelay = "";
			$sliderspeed = "";
			$sliderheight = "";
			$slidermobileview = "";
			$min_rating = "";
			$min_words = "";
			$max_words = "";
			$word_or_char = "";
			$showreviewsbyid = "";
			$review_same_height ='';
			$string_sel = '';
			$string_text = '';
			$string_selnot = '';
			$string_textnot = '';
			$showreviewsbyid_sel='';
		}
		
		//turn off masonry if same height set to yes or only 1 per a row
		if($review_same_height=="yes" || $review_same_height=="cur" || $review_same_height=="yea" || $display_num=="1"){
			$display_masonry = "no";
		}
			
		$showreviewsbyidarray = explode("-",$showreviewsbyid);
		$showreviewsbyidjson = json_encode($showreviewsbyidarray);
		
		//template misc
		$templatemiscarray = array();
		$templatemiscarray['showstars']=sanitize_text_field($formarray['wprevpro_template_misc_showstars']);
		
		$templatemiscarray['dateformat']=sanitize_text_field($formarray['wprevpro_template_misc_dateformat']);
		if($templatemiscarray['dateformat']=='hide'){
			$templatemiscarray['showdate']='no';
		}
		$templatemiscarray['bgcolor1']=sanitize_text_field($formarray['wprevpro_template_misc_bgcolor1']);
		$templatemiscarray['bgcolor2']=sanitize_text_field($formarray['wprevpro_template_misc_bgcolor2']);
		$templatemiscarray['tcolor1']=sanitize_text_field($formarray['wprevpro_template_misc_tcolor1']);
		$templatemiscarray['tcolor2']=sanitize_text_field($formarray['wprevpro_template_misc_tcolor2']);
		$templatemiscarray['tcolor3']=sanitize_text_field($formarray['wprevpro_template_misc_tcolor3']);
		$templatemiscarray['tfont1']=sanitize_text_field($formarray['wprevpro_template_misc_tfont1']);
		$templatemiscarray['tfont2']=sanitize_text_field($formarray['wprevpro_template_misc_tfont2']);
		$templatemiscarray['bradius']=sanitize_text_field($formarray['wprevpro_template_misc_bradius']);
		$templatemiscarray['bcolor']=sanitize_text_field($formarray['wprevpro_template_misc_bcolor']);
		$templatemiscarray['lastnameformat']=sanitize_text_field($formarray['wprevpro_template_misc_lastname']);
		$templatemiscarray['firstnameformat']=sanitize_text_field($formarray['wprevpro_template_misc_firstname']);
		$templatemiscarray['showtitle']=sanitize_text_field($formarray['wprevpro_template_misc_showtitle']);
		$templatemiscarray['starcolor']=sanitize_text_field($formarray['wprevpro_template_misc_starcolor']);
		$templatemiscarray['starsize']=sanitize_text_field($formarray['wprevpro_template_misc_starsize']);
		$templatemiscarray['iconsize']=sanitize_text_field($formarray['wprevpro_template_misc_iconsize']);
		$templatemiscarray['stariconfull']=sanitize_text_field($formarray['wprevpro_template_misc_stariconfull']);
		$templatemiscarray['stariconempty']=sanitize_text_field($formarray['wprevpro_template_misc_stariconempty']);
		$templatemiscarray['starlocation']=sanitize_text_field($formarray['wprevpro_template_misc_starlocation']);
		$templatemiscarray['avataropt']=sanitize_text_field($formarray['wprevpro_template_misc_avataropt']);
		$templatemiscarray['avatarsize']=sanitize_text_field($formarray['wprevpro_template_misc_avatarsize']);
		$templatemiscarray['inibgcolor']=sanitize_text_field($formarray['wprevpro_template_misc_inibgcolor']);
		$templatemiscarray['showmedia']=sanitize_text_field($formarray['wprevpro_t_showmedia']);
		$templatemiscarray['ownerres']=sanitize_text_field($formarray['wprevpro_t_ownerres']);
		
		$templatemiscarray['showlocation']=sanitize_text_field($formarray['wprevpro_t_showlocation']);
		$templatemiscarray['showcdetails']=sanitize_text_field($formarray['wprevpro_t_showcdetails']);
		$templatemiscarray['showcdetailslink']=sanitize_text_field($formarray['wprevpro_t_showcdetailslink']);
		$templatemiscarray['cutrevs']=sanitize_text_field($formarray['wprevpro_t_cutrevs']);
		$templatemiscarray['cutrevs_lnum']=sanitize_text_field($formarray['wprevpro_t_cutrevs_lnum']);
		if(isset($formarray['wprevpro_t_scrollbarauto'])){
		$templatemiscarray['scrollbarauto']=sanitize_text_field($formarray['wprevpro_t_scrollbarauto']);
		}
		//$templatemiscarray['length_type']=sanitize_text_field($formarray['wprevpro_t_length_type']);
		$templatemiscarray['load_more_porb']=sanitize_text_field($formarray['wprevpro_t_load_more_porb']);
		$templatemiscarray['choosetypes']=$formarray['wprevpro_choosetypes'];
		$templatemiscarray['readmcolor']=sanitize_text_field($formarray['wprevpro_template_misc_readmcolor']);
		if(isset($formarray['wprevpro_t_rtype_wpmllang'])){
		$templatemiscarray['wpmllang']=sanitize_text_field($formarray['wprevpro_t_rtype_wpmllang']);
		}
		if(isset($formarray['wprevpro_template_misc_sliderarrowcolor'])){
		$templatemiscarray['sliderarrowcolor']=sanitize_text_field($formarray['wprevpro_template_misc_sliderarrowcolor']);
		}
		if(isset($formarray['wprevpro_template_misc_sliderdotcolor'])){
		$templatemiscarray['sliderdotcolor']=sanitize_text_field($formarray['wprevpro_template_misc_sliderdotcolor']);
		}
		if(isset($formarray['wprevpro_t_dropshadow'])){
			$templatemiscarray['dropshadow']=sanitize_text_field($formarray['wprevpro_t_dropshadow']);
		}
		if(isset($formarray['wprevpro_t_raisemouse'])){
			$templatemiscarray['raisemouse']=sanitize_text_field($formarray['wprevpro_t_raisemouse']);
		}
		if(isset($formarray['wprevpro_t_zoommouse'])){
			$templatemiscarray['zoommouse']=sanitize_text_field($formarray['wprevpro_t_zoommouse']);
		}
		
		$templatemiscarray['verified']=sanitize_text_field($formarray['wprevpro_template_misc_verified']);
		$templatemiscarray['screensize']=sanitize_text_field($formarray['wprevpro_screensize']);
		
		$templatemiscarray['showsourcep']=sanitize_text_field($formarray['wprevpro_t_showsourcep']);
		$templatemiscarray['showsourceplink']=sanitize_text_field($formarray['wprevpro_t_showsourceplink']);
		
		$templatemiscarray['header_banner']=sanitize_text_field($formarray['wprevpro_t_header_banner']);
		
		//echo $formarray['wprevpro_choosetypes'];
		
		//$arrallowedtags = array('em' => array(), 'i' => array(), 'strong' => array(), 'b' => array());
		$arrallowedtags = array(
			'a' => array(
				'href' => array(),
				'title' => array()
			),
			'b' => array(),
			'em' => array(),
			'strong' => array(),
			'i' => array(
				'class' => array(),
				'id' => array()
				),
			'span' => array(
				'class' => array(),
				'id' => array()
				),
		);
		//echo $formarray['wprevpro_t_header_text']."<br>";
		$templatemiscarray['header_text']=wp_kses($formarray['wprevpro_t_header_text'],$arrallowedtags);
		//echo $templatemiscarray['header_text']."<br>";
		$templatemiscarray['header_text_tag']=sanitize_text_field($formarray['wprevpro_t_header_text_tag']);
		$templatemiscarray['header_filter_opt']=sanitize_text_field($formarray['wprevpro_t_header_filter_opt']);
		
		$templatemiscarray['header_search']='';
		$templatemiscarray['header_sort']='';
		$templatemiscarray['header_rating']='';
		$templatemiscarray['header_source']='';
		$templatemiscarray['header_langcodes']='';
		$templatemiscarray['header_tag']='';
		$templatemiscarray['header_rtypes']='';
		
		if(isset($formarray['wprevpro_t_header_search'])){
			$templatemiscarray['header_search']=sanitize_text_field($formarray['wprevpro_t_header_search']);
		}
		$templatemiscarray['header_search_place']=sanitize_text_field($formarray['wprevpro_t_header_search_place']);

		if(isset($formarray['wprevpro_t_header_sort'])){
			$templatemiscarray['header_sort']=sanitize_text_field($formarray['wprevpro_t_header_sort']);
		}
		
		$templatemiscarray['header_sort_place']=sanitize_text_field($formarray['wprevpro_t_header_sort_place']);
		if(isset($formarray['wprevpro_t_header_tag'])){
		$templatemiscarray['header_tag']=sanitize_text_field($formarray['wprevpro_t_header_tag']);
		}
		
		$templatemiscarray['header_tags']=sanitize_text_field($formarray['wprevpro_t_header_tags']);
		
		$templatemiscarray['header_tag_search']=sanitize_text_field($formarray['wprevpro_t_header_tag_search']);
		
		$templatemiscarray['header_rating_place']=sanitize_text_field($formarray['wprevpro_t_header_rating_place']);
		
		if(isset($formarray['wprevpro_t_header_rating'])){
			$templatemiscarray['header_rating']=sanitize_text_field($formarray['wprevpro_t_header_rating']);
		}
		
		$templatemiscarray['header_langcodes_list']=sanitize_text_field($formarray['wprevpro_t_header_langcodes_list']);
		$templatemiscarray['header_langcodes_list']= trim($templatemiscarray['header_langcodes_list'], " \t\n\r");
		$templatemiscarray['header_langcodes_place']=sanitize_text_field($formarray['wprevpro_t_header_langcodes_place']);
		if(isset($formarray['wprevpro_t_header_langcodes'])){
		$templatemiscarray['header_langcodes']=sanitize_text_field($formarray['wprevpro_t_header_langcodes']);
		}
		$templatemiscarray['header_source_place']=sanitize_text_field($formarray['wprevpro_t_header_source_place']);
		
		if(isset($formarray['wprevpro_t_header_source'])){
			$templatemiscarray['header_source']=sanitize_text_field($formarray['wprevpro_t_header_source']);
		}
		if(isset($formarray['wprevpro_t_header_rtypes'])){
		$templatemiscarray['header_rtypes']=sanitize_text_field($formarray['wprevpro_t_header_rtypes']);
		}

		
		//for pagination button style
		$templatemiscarray['ps_bw']=sanitize_text_field($formarray['wprevpro_t_ps_bw']);
		$templatemiscarray['ps_br']=sanitize_text_field($formarray['wprevpro_t_ps_br']);
		$templatemiscarray['ps_bcolor']=sanitize_text_field($formarray['wprevpro_t_ps_bcolor']);
		$templatemiscarray['ps_bgcolor']=sanitize_text_field($formarray['wprevpro_t_ps_bgcolor']);
		$templatemiscarray['ps_fontcolor']=sanitize_text_field($formarray['wprevpro_t_ps_fontcolor']);
		$templatemiscarray['ps_fsize']=sanitize_text_field($formarray['wprevpro_t_ps_fsize']);
		$templatemiscarray['ps_paddingt']=sanitize_text_field($formarray['wprevpro_t_ps_paddingt']);
		$templatemiscarray['ps_paddingb']=sanitize_text_field($formarray['wprevpro_t_ps_paddingb']);
		$templatemiscarray['ps_paddingl']=sanitize_text_field($formarray['wprevpro_t_ps_paddingl']);
		$templatemiscarray['ps_paddingr']=sanitize_text_field($formarray['wprevpro_t_ps_paddingr']);
		$templatemiscarray['ps_margint']=sanitize_text_field($formarray['wprevpro_t_ps_margint']);
		$templatemiscarray['ps_marginb']=sanitize_text_field($formarray['wprevpro_t_ps_marginb']);
		$templatemiscarray['ps_marginl']=sanitize_text_field($formarray['wprevpro_t_ps_marginl']);
		$templatemiscarray['ps_marginr']=sanitize_text_field($formarray['wprevpro_t_ps_marginr']);
		

		if(isset($formarray['wprevpro_default_avatar'])){
			$templatemiscarray['default_avatar']=sanitize_text_field($formarray['wprevpro_default_avatar']);
		} else {
			$templatemiscarray['default_avatar']="";
		}
		
		//for post and cat filters
		$templatemiscarray['postfilter']=sanitize_text_field($formarray['wprevpro_t_postfilter']);
		$templatemiscarray['categoryfilter']=sanitize_text_field($formarray['wprevpro_t_categoryfilter']);
		
		$templatemiscarray['postfilterlist']=sanitize_text_field($formarray['wprevpro_t_postfilterlist']);
		$templatemiscarray['categoryfilterlist']=sanitize_text_field($formarray['wprevpro_t_categoryfilterlist']);
		$templatemiscarray['langfilterlist']=sanitize_text_field($formarray['wprevpro_t_langfilterlist']);
		$templatemiscarray['tagfilterlist']=sanitize_text_field($formarray['wprevpro_t_tagfilterlist']);
		$templatemiscarray['tagfilterlist_opt']=sanitize_text_field($formarray['wprevpro_t_tagfilterlist_opt']);

		require_once WPREV_PLUGIN_DIR . 'admin/class-wp-review-slider-pro-admin-common.php';
		$plugin_admin_common = new Common_Admin_Functions();
			
		//convert to json, function in class-wp-review-slider-pro-admin.php
		$templatemiscarray['postfilterlist'] = $plugin_admin_common->wprev_commastrtojson($templatemiscarray['postfilterlist']);	
		$templatemiscarray['categoryfilterlist'] = $plugin_admin_common->wprev_commastrtojson($templatemiscarray['categoryfilterlist']);	
		$templatemiscarray['langfilterlist'] = $plugin_admin_common->wprev_commastrtojson($templatemiscarray['langfilterlist'],'',false);
		//$templatemiscarray['tagfilterlist'] = $plugin_admin_common->wprev_commastrtojson($templatemiscarray['tagfilterlist'],'',false);
		$temptagjson = '[]';
		if($templatemiscarray['tagfilterlist']!=''){
			$temptaglist = explode(',',$templatemiscarray['tagfilterlist']);
			$temptagjson = json_encode($temptaglist);
		}
		$templatemiscarray['tagfilterlist'] = $temptagjson;
		
		//see if we are overriding yelp icon
		if(isset($formarray['wprevpro_t_over_yelp'])){
		$templatemiscarray['icon_over_yelp']=sanitize_text_field($formarray['wprevpro_t_over_yelp']);
		}
		if(isset($formarray['wprevpro_t_over_trip'])){
		$templatemiscarray['icon_over_trip']=sanitize_text_field($formarray['wprevpro_t_over_trip']);
		}
		
		//margins
		$templatemiscarray['template_margin_tb']=sanitize_text_field($formarray['wprevpro_t_template_margin_tb']);
		$templatemiscarray['template_margin_lr']=sanitize_text_field($formarray['wprevpro_t_template_margin_lr']);
		$templatemiscarray['template_margin_tb_m']=sanitize_text_field($formarray['wprevpro_t_template_margin_tb_m']);
		$templatemiscarray['template_margin_lr_m']=sanitize_text_field($formarray['wprevpro_t_template_margin_lr_m']);
		
				//slick slider settings
		if(isset($formarray['wprevpro_sli_infinite'])){
			$templatemiscarray['sli_infinite']=sanitize_text_field($formarray['wprevpro_sli_infinite']);
		}
		if(isset($formarray['wprevpro_sli_slidestoscroll'])){
		$templatemiscarray['sli_slidestoscroll']=sanitize_text_field($formarray['wprevpro_sli_slidestoscroll']);
		}
		if(isset($formarray['wprevpro_sli_avatarnav'])){
		$templatemiscarray['sli_avatarnav']=sanitize_text_field($formarray['wprevpro_sli_avatarnav']);
		}
		if(isset($formarray['wprevpro_sli_centermode'])){
		$templatemiscarray['sli_centermode']=sanitize_text_field($formarray['wprevpro_sli_centermode']); 
		}		
		$templatemiscarray['sli_centermode_padding']=sanitize_text_field($formarray['wprevpro_sli_centermode_padding']);
		
		if(isset($formarray['wprevpro_t_read_more_pop'])){
		$templatemiscarray['readmpop']=sanitize_text_field($formarray['wprevpro_t_read_more_pop']);
		}	
		
		//banner settings.
		$templatemiscarray['bbgcolor']=sanitize_text_field($formarray['wprevpro_t_bbgcolor']);
		$templatemiscarray['btxtcolor']=sanitize_text_field($formarray['wprevpro_t_btxtcolor']);
		$templatemiscarray['bbordercolor']=sanitize_text_field($formarray['wprevpro_t_bbordercolor']);
		$templatemiscarray['bncradius']=sanitize_text_field($formarray['wprevpro_t_bncradius']);
		if(isset($formarray['wprevpro_t_bndropshadow'])){
			$templatemiscarray['bndropshadow']=sanitize_text_field($formarray['wprevpro_t_bndropshadow']);
		}
		if(isset($formarray['wprevpro_t_bnrevusbtn'])){
		$templatemiscarray['bnrevusbtn']=sanitize_text_field($formarray['wprevpro_t_bnrevusbtn']);
		}
		
		$templatemiscarray['bn_filter_opt']=sanitize_text_field($formarray['wprevpro_t_bn_filter_opt']);
		
		if(isset($formarray['wprevpro_t_bnshowsub'])){
		$templatemiscarray['bnshowsub']=sanitize_text_field($formarray['wprevpro_t_bnshowsub']);
		}
		$templatemiscarray['bnshowsubtext']=sanitize_text_field($formarray['wprevpro_t_bnshowsubtext']);
		if(isset($formarray['wprevpro_t_bnshowman'])){
		$templatemiscarray['bnshowman']=sanitize_text_field($formarray['wprevpro_t_bnshowman']);
		}
		$templatemiscarray['bnshowmantext']=sanitize_text_field($formarray['wprevpro_t_bnshowmantext']);
		if(isset($formarray['wprevpro_t_bnhidesource'])){
		$templatemiscarray['bnhidesource']=sanitize_text_field($formarray['wprevpro_t_bnhidesource']);
		}
		
		//banner btn settings
		$templatemiscarray['revus_bgcolor']=sanitize_text_field($formarray['wprevpro_t_revus_bgcolor']);
		$templatemiscarray['revus_fontcolor']=sanitize_text_field($formarray['wprevpro_t_revus_fontcolor']);
		$templatemiscarray['revus_bcolor']=sanitize_text_field($formarray['wprevpro_t_revus_bcolor']);
		$templatemiscarray['revus_txtval']=sanitize_text_field($formarray['wprevpro_t_revus_txtval']);
		$templatemiscarray['revus_btnaction']=sanitize_text_field($formarray['wprevpro_t_revus_btnaction']);
		$templatemiscarray['revus_puform']=sanitize_text_field($formarray['wprevpro_t_revus_puform']);
		$templatemiscarray['revus_btnlink']=sanitize_text_field($formarray['wprevpro_t_revus_btnlink']);
		//multi_links
		for ($x = 1; $x <= 6; $x++) {
			if(isset($formarray['wprevpro_t_revus_btnln'.$x])){
				$templatemiscarray['revus_btnln'][$x]=sanitize_text_field($formarray['wprevpro_t_revus_btnln'.$x]);
			}
			if(isset($formarray['wprevpro_t_revus_btnlu'.$x])){
				$templatemiscarray['revus_btnlu'][$x]=sanitize_text_field($formarray['wprevpro_t_revus_btnlu'.$x]);
			}
		}

		
		
		
		//$templatemiscjson = json_encode($templatemiscarray);
		$templatemiscjson =json_encode($templatemiscarray,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
		
		//$rtype = htmlentities($formarray['wprevpro_t_rtype']);
		$rtypearray=array();
		
		
		//loop type and from fields to check if checked.
		$reviews_table_name = $wpdb->prefix . 'wpfb_reviews';
		$tempquery = "SELECT DISTINCT type,from_name FROM ".$reviews_table_name." WHERE type IS NOT NULL ORDER by type DESC";
		$typerows = $wpdb->get_results($tempquery);
		//print_r($typerows);
		if(count($typerows)>0){
			foreach ( $typerows as $temptype ){
				$typelowercase = strtolower($temptype->type);
				$typelowercasecheck = str_replace(".","",$typelowercase); 
				if(isset($formarray['wprevpro_t_rtype_'.$typelowercasecheck])){
					if(!in_array(sanitize_text_field($formarray['wprevpro_t_rtype_'.$typelowercasecheck]),$rtypearray)){
					array_push($rtypearray, sanitize_text_field($formarray['wprevpro_t_rtype_'.$typelowercasecheck]));
					}
				}
				//now check for manual_from_name 
				$typelowercaseboth = strtolower($temptype->type)."_".$temptype->from_name;
				$typelowercasecheckboth = str_replace(".","",$typelowercaseboth);
				if(isset($formarray['wprevpro_t_rtype_'.$typelowercasecheckboth])){
					if(!in_array(sanitize_text_field($formarray['wprevpro_t_rtype_'.$typelowercasecheckboth]),$rtypearray)){
					array_push($rtypearray, sanitize_text_field($formarray['wprevpro_t_rtype_'.$typelowercasecheckboth]));
					}
				}
			}
		}

//print_r($formarray);

		$rtypearrayjson = json_encode($rtypearray);
		//echo($rtypearrayjson);
		//$rpage = htmlentities($formarray['wprevpro_t_rpage']);
		if(!isset($formarray['wprevpro_t_rpage'])){
			$formarray['wprevpro_t_rpage']="";
		}
			$rpagearray = $formarray['wprevpro_t_rpage'];
			$rpagearrayjson = json_encode($rpagearray);


		
		$timenow = time();
		
		//+++++++++need to sql escape using prepare+++++++++++++++++++
		//+++++++++++++++++++++++++++++++++++++++++++++++++++++
		//insert or update
			$data = array( 
				'title' => "$title",
				'template_type' => "$template_type",
				'style' => "$style",
				'created_time_stamp' => "$timenow",
				'display_num' => "$display_num",
				'display_num_rows' => "$display_num_rows",
				'display_order' => "$display_order",
				'display_order_second' => "$display_order_second",
				'load_more' => "$load_more",
				'load_more_text' => "$load_more_text", 
				'hide_no_text' => "$hide_no_text",
				'template_css' => "$template_css", 
				'min_rating' => "$min_rating", 
				'min_words' => "$min_words",
				'max_words' => "$max_words",
				'word_or_char' => "$word_or_char",
				'rtype' => "$rtypearrayjson", 
				'rpage' => "$rpagearrayjson",
				'createslider' => "$createslider",
				'numslides' => "$numslides",
				'sliderautoplay' => "$sliderautoplay",
				'sliderdirection' => "$sliderdirection",
				'sliderarrows' => "$sliderarrows",
				'sliderdots' => "$sliderdots",
				'sliderdelay' => "$sliderdelay",
				'sliderspeed' => "$sliderspeed",
				'sliderheight' => "$sliderheight",
				'slidermobileview' => "$slidermobileview",
				'showreviewsbyid' => "$showreviewsbyidjson",
				'template_misc' => "$templatemiscjson",
				'read_more' => "$read_more",
				'read_more_text' => "$read_more_text",
				'facebook_icon' => "$facebook_icon",
				'facebook_icon_link' => "$facebook_icon_link",
				'google_snippet_add' => "$google_snippet_add",
				'google_snippet_type' => "$google_snippet_type",
				'google_snippet_name' => "$google_snippet_name",
				'google_snippet_desc' => "$google_snippet_desc",
				'google_snippet_business_image' => "$google_snippet_business_image",
				'google_snippet_more' => "$google_snippet_more_array_encode",
				'cache_settings' => "$cache_settings",
				'review_same_height' => "$review_same_height",
				'add_profile_link' => "$add_profile_link",
				'display_order_limit' => "$display_order_limit",
				'display_masonry' => "$display_masonry",
				'read_less_text' => "$read_less_text",
				'string_sel' => "$string_sel",
				'string_text' => "$string_text",
				'string_selnot' => "$string_selnot",
				'string_textnot' => "$string_textnot",
				'showreviewsbyid_sel' => "$showreviewsbyid_sel"
				);
				//print_r($data);
			$format = array( 
					'%s',
					'%s',
					'%d',
					'%d',
					'%d',
					'%d',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%d',
					'%d',
					'%d',
					'%s',
					'%s',
					'%s',
					'%s',
					'%d',
					'%s',
					'%s',
					'%s',
					'%s',
					'%d',
					'%d',
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
					'%s'
				);
		$returnarray['iu'] =''; 
		$returnarray['ack'] ='';
		$returnarray['ackmessage'] ='';
		$returnarray['t_id']='';
		if($t_id==""){
			//insert
			$returnarray['iu'] ='insert'; 
			$inserttemplate = $wpdb->insert( $table_name, $data, $format );
			$t_id = $wpdb->insert_id;
			if(!$inserttemplate){
				$dbmsg = __('Unable to update. Try refreshing the page. If that does not work then try de-activating and re-activating the plugin.', 'wp-review-slider-pro');
				$returnarray['ack'] ='error';
			} else {
				$dbmsg = __('Template Saved!', 'wp-review-slider-pro');
				$returnarray['ack'] ='success';
			}
		} else {
			//update
			$returnarray['iu'] ='update';
			$updatetempquery = $wpdb->update($table_name, $data, array( 'id' => $t_id ), $format, array( '%d' ));

			if($updatetempquery>0){
				$returnarray['ack'] ='success';
				$dbmsg = __('Template Updated!', 'wp-review-slider-pro');
			} else {
				//$wpdb->show_errors();
				//$wpdb->print_error();
				$returnarray['ack'] ='error';
				$dbmsg = __('Unable to update. Try refreshing the page. If that does not work then try de-activating and re-activating the plugin.', 'wp-review-slider-pro');
			}
		}
		$returnarray['t_id']=$t_id;
		$returnarray['ackmessage'] =$dbmsg;
		$returnjson = json_encode($returnarray);

		//check to see if there are any templates using slider js.
		$foundnormalslider = false;
		$foundslickslider = false;
		$currentforms = $wpdb->get_results("SELECT id, title, template_type, created_time_stamp, style,rtype,createslider FROM $table_name");
		foreach ( $currentforms as $currentform ) 
		{
			if ($currentform->createslider=='yes'){
				$foundnormalslider = true;
			} else if($currentform->createslider=='sli'){
				$foundslickslider = true;
			}

		}
		update_option( 'wprev_slidejsload', '' );
		if($foundnormalslider && !$foundslickslider){
			update_option( 'wprev_slidejsload', 'normal' );
		} else if(!$foundnormalslider && $foundslickslider){
			update_option( 'wprev_slidejsload', 'slick' );
		} else if($foundnormalslider && $foundslickslider){
			 update_option( 'wprev_slidejsload', 'both' );
		}

		echo $returnjson;
		die();
	}
	
	/**
	 * Ajax, return template averages and totals for badge and header
	 * @access  admin
	 * @since   11.8.0
	 * @return  void
	 */
	public function wprp_get_template_totalavgs(){
		$templateid = intval($_POST['cid']);
		$returnarray = Array();
		check_ajax_referer('randomnoncestring', 'wpfb_nonce');
		
		if($templateid>0){
			
			//first we should update in case filters changed on template.
			$this->updateallavgtotalstable_templates();
			
			//query this table and return values for this cid wp_wpfb_total_averages
			global $wpdb;
			$temptemplant = "template_".$templateid;
			$table_name_temp = $wpdb->prefix . 'wpfb_total_averages';
			$currentpageval = $wpdb->get_results("SELECT * FROM $table_name_temp WHERE `btp_id` = '".$temptemplant."' ",ARRAY_A );
			
			//total_indb	total	avg_indb	avg	numr1	numr2	numr3	numr4	numr5
			$returnarray['total_indb']=$currentpageval[0]['total_indb'];
			$returnarray['total']=$currentpageval[0]['total'];
			$returnarray['avg_indb']=$currentpageval[0]['avg_indb'];
			$returnarray['avg']=$currentpageval[0]['avg'];
			$returnarray['numr1']=$currentpageval[0]['numr1'];
			$returnarray['numr2']=$currentpageval[0]['numr2'];
			$returnarray['numr3']=$currentpageval[0]['numr3'];
			$returnarray['numr4']=$currentpageval[0]['numr4'];
			$returnarray['numr5']=$currentpageval[0]['numr5'];
			$returnarray['pagetypedetails']=$currentpageval[0]['pagetypedetails'];
			
		}
		
		$returnjson = json_encode($returnarray);
		
		echo $returnjson;
		
		die();
	}

//-----form functions--------------
	/**
	 * Ajax, save form template to db
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function wprp_saveform_ajax(){
	//ini_set('display_errors',1);  
	//error_reporting(E_ALL);
		
		check_ajax_referer('randomnoncestring', 'wpfb_nonce');
		$formtitle = sanitize_text_field($_POST['title']);
		$formid = sanitize_text_field($_POST['tid']);
		
		$femail =  sanitize_text_field($_POST['email']);
		$fcss = sanitize_textarea_field($_POST['css']);
		$fhtml = wp_kses_post($_POST['fhtml']);
		
		$createdtime = time();
		//$formdata = sanitize_text_field(stripslashes($_POST['data']));
		$formdata = stripslashes($_POST['data']);
		$formarray = json_decode($formdata,true);
		
		//misc settings
		$fmiscdata = sanitize_text_field(stripslashes($_POST['misc']));
		$fmisc = json_decode($fmiscdata,true);	//keep as php object
		$fmiscencode = json_encode($fmisc);

		//================this can not handle 10 or 11
		foreach($formarray as $x_key => $x_value) {
			//get the field number from the string, then use it to setup array
			//$indexnum = substr($x_key, 7, 1);
			//$indexname = substr($x_key, 10, -1);
			//test for zero
			//if( substr($x_key, 8, 1)=='0'){
			//	$indexnum = substr($x_key, 7, 2);
			//	$indexname = substr($x_key, 11, -1);
			//}
			$indexnum = $this->get_string_between($x_key, 'fields[', ']');
			$indexname = $this->get_string_between($x_key, '][', ']');
			$fieldarray[$indexnum][$indexname]=$x_value;
		}
		//reindex here so we can drag and drop to reorder fields. Must do this to avoid json_encode adding 0 to json
		$fieldarray=array_values($fieldarray);

		$fieldarrayjson = json_encode($fieldarray);
		//die();
		//perform db search and return resultsform_fields
		global $wpdb;
		$table_name = $wpdb->prefix . 'wpfb_forms';
		//insert or update
			$data = array( 
				'title' => "$formtitle",
				'created_time_stamp' => "$createdtime",
				'form_fields' => "$fieldarrayjson",
				'notifyemail' => "$femail",
				'form_css' => "$fcss",
				'form_html' => "$fhtml",
				'form_misc' => "$fmiscencode",
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
				); 		
			
		//insert or update if editing
		if($formid>0){
			$where = array( 'id' => "$formid" );
			//$formatwhere = array('%s');
			$insertrow = $wpdb->update( $table_name, $data, array( 'id' => $formid ), $format, array( '%d' ));
			$insertid =$formid;
		} else {
			$insertrow = $wpdb->insert( $table_name, $data, $format );
			$insertid = $wpdb->insert_id;
		}

		echo $insertid;
		die();
	}
	
	//
	/**
	 * Ajax, categories list html, used in Review List page and Templates page to select cat ids and post ids
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function wprp_getcategories_ajax(){
	//ini_set('display_errors',1);  
	//error_reporting(E_ALL);
		
		check_ajax_referer('randomnoncestring', 'wpfb_nonce');
		$idtype = sanitize_text_field($_POST['idtype']);
		$searchterm = sanitize_text_field($_POST['sterm']);
		$filterpagetype = sanitize_text_field($_POST['ttypeonly']);	//if yes then only searching for this type
		$orderby = sanitize_text_field($_POST['orderby']);
		$orderby = strtolower($orderby);
		$order = 'ASC';

		if (is_admin()) {
			//echo $idtype;
			if($idtype=='cat'){
				if($orderby==""){
					$orderby = 'name';
				} else if($orderby=="id"){
					$orderby = 'term_id';
				} else if($orderby=="description"){
					$orderby = 'description';
				} else if($orderby=="slug"){
					$orderby = 'slug';
				} else if($orderby=="count"){
					$orderby = 'count';
					$order='DESC';
				}
				
				//find all terms,then use to find all categories
				$defaulttaxes = ['category','post_tag'];
				$args = array(
				  'public'   => true,
				  '_builtin' => false
				   
				); 
				$customtaxonomies = get_taxonomies($args);
				$alltaxonomies = array_merge($defaulttaxes,array_values($customtaxonomies));
				
				$tablehtml = '<tr idtype="'.$idtype.'"><td>Oops, unable to retrieve list of post categories.</td></tr>';
				$catargs = array(
					'taxonomy'=> $alltaxonomies,
					'orderby' => $orderby,
					'order'   => $order
				);
				print_r($catargs);
				$categories = get_categories($catargs);
				print_r($categories);
				
				if(count($categories)>0){
					$tablehtml = '<thead><tr class="classidtype" idtype="'.$idtype.'">
					<td id="cb" class="manage-column column-cb check-column"></td><th scope="col" id="idnum" class="manage-column column-idnum column-primary sortable desc"><span class="sortspan">'.esc_html__('ID', 'wp-review-slider-pro').'</span><span class="sorting-indicator"></span></th><th scope="col" id="name" class="manage-column column-name column-primary sortable desc"><span class="sortspan">'.esc_html__('Name', 'wp-review-slider-pro').'</span><span class="sorting-indicator"></span></th><th scope="col" id="description" class="manage-column column-description sortable desc"><span  class="sortspan">'.esc_html__('Description', 'wp-review-slider-pro').'</span><span class="sorting-indicator"></span></th><th scope="col" id="slug" class="manage-column column-slug sortable desc"><span class="sortspan">'.esc_html__('Slug', 'wp-review-slider-pro').'</span><span class="sorting-indicator"></span></th><th scope="col" id="posts" class="manage-column column-posts num sortable desc"><span  class="sortspan">'.esc_html__('Taxonomy', 'wp-review-slider-pro').'</span><span class="sorting-indicator"></span></th><th scope="col" id="posts" class="manage-column column-posts num sortable desc"><span  class="sortspan">'.esc_html__('Count', 'wp-review-slider-pro').'</span><span class="sorting-indicator"></span></th></tr></thead>
					<tbody id="the-list" data-wp-lists="list:tag">';
					foreach( $categories as $category ) {
						$category_link = sprintf( 
							'<a href="%1$s" alt="%2$s" target="_blank">%3$s</a>',
							esc_url( get_category_link( $category->term_id ) ),
							esc_attr( sprintf( __( 'View all posts in %s', 'wp-review-slider-pro' ), $category->name ) ),
							esc_html( $category->name )
						);
						 
						$tablehtml = $tablehtml . '<tr idtype="'.$idtype.'" id="tag-'.sprintf( esc_html__($category->term_id )).'"><td scope="row" class="check-column"><label class="screen-reader-text" for="cb-select-'.sprintf( esc_html__($category->term_id )).'">Select another cat</label><input type="checkbox" name="catids[]" value="'.sprintf( esc_html__($category->term_id )).'" id="cb-select-'.sprintf( esc_html__($category->term_id )).'"></td><td class="idnum column-name has-row-actions column-primary" data-colname="ID"><strong>'.sprintf( esc_html__($category->term_id )).'</strong></td><td class="name column-name has-row-actions column-primary" data-colname="Name"><strong>'.sprintf($category_link ).'</strong></td><td class="description column-description" data-colname="Description">'. sprintf( esc_html__($category->description )) .'</td><td class="slug column-slug" data-colname="Slug">'.sprintf( esc_html__($category->slug )).'</td><td class="posts column-posts" data-colname="Taxonomy">' . sprintf( esc_html__($category->taxonomy )) . '</td><td class="posts column-posts" data-colname="Count">' . sprintf( esc_html__($category->count )) . '</td></tr>';
					} 
					$tablehtml = $tablehtml . '</tbody>';
				}
			} else if($idtype=='posts' || $idtype=='pages'){
				if($orderby=="" || $orderby=="name"){
					$orderby = 'post_name';
				} else if($orderby=="id"){
					$orderby = 'ID';
				} else if($orderby=="title"){
					$orderby = 'post_title';
				} else if($orderby=="modified"){
					$orderby = 'post_modified';
				} else if($orderby=="type"){
					$orderby = 'post_type';
				}
				
				$tablehtml = '<tr idtype="'.$idtype.'"><td>'.esc_html__('Oops, unable to retrieve list of post IDs.', 'wp-review-slider-pro').'</td></tr>';
				//get all post types here in an array
				$defaultposttypearray = ['page','post'];
				//print_r($defaultposttypearray);
					$args = array(
					   'public'   => true,
					   '_builtin' => false
					);
				$customposttypearray =get_post_types($args);
				//print_r($customposttypearray);
				$posttypearray = array_merge($defaultposttypearray,array_values($customposttypearray));
				
				if($idtype=='pages'){
					$posttypearray = ['page'];
				}
				if($filterpagetype=="yes"){
					//do not search for page type
					if (($key = array_search('page', $posttypearray)) !== false) {
						unset($posttypearray[$key]);
					}
				}
				$args = array(
					'orderby'    => 'menu_order',
					'numberposts'    => '-1',
					'post_type'   => $posttypearray,	//may need more here, product is for woocommerce
					's' => $searchterm,			//search parameter
					'sort_order' => 'asc'
				);
				//echo $filterpagetype;
				//print_r($args);
				$post_list = get_posts($args);
				if(count($post_list)>0){
					$tablehtml = '<thead><tr class="classidtype" idtype="'.$idtype.'">
					<td id="cb" class="manage-column column-cb check-column"></td><th scope="col" id="idnum" class="manage-column column-idnum column-primary sortable desc"><span class="sortspan">'.esc_html__('ID', 'wp-review-slider-pro').'</span><span class="sorting-indicator"></span></th><th scope="col" id="name" class="manage-column column-name column-primary sortable desc"><span class="sortspan">'.esc_html__('Name', 'wp-review-slider-pro').'</span><span class="sorting-indicator"></span></th><th scope="col" id="post_title" class="manage-column column-title sortable desc"><span  class="sortspan">'.esc_html__('Title', 'wp-review-slider-pro').'</span><span class="sorting-indicator"></span></th><th scope="col" id="post_modified" class="manage-column column-post_modified sortable desc"><span class="sortspan">'.esc_html__('Modified', 'wp-review-slider-pro').'</span><span class="sorting-indicator"></span></th><th scope="col" id="post_type" class="manage-column column-post_type sortable desc"><span class="sortspan">'.esc_html__('Type', 'wp-review-slider-pro').'</span><span class="sorting-indicator"></span></th></tr></thead>
					<tbody id="the-list" data-wp-lists="list:tag">';
					foreach( $post_list as $post ) {
						$category_link = sprintf( 
							'<a href="%1$s" alt="%2$s" target="_blank">%3$s</a>',
							esc_url( get_category_link( $post->ID ) ),
							esc_attr( sprintf( __( 'View all posts in %s', 'wp-review-slider-pro' ), $post->post_name ) ),
							esc_html( $post->post_name )
						);
						 
						$tablehtml = $tablehtml . '<tr idtype="'.$idtype.'" id="tag-'.sprintf( esc_html__($post->ID )).'"><td scope="row" class="check-column"><label class="screen-reader-text" for="cb-select-'.sprintf( esc_html__($post->ID )).'">Select another post</label><input type="checkbox" name="catids[]" value="'.sprintf( esc_html__($post->ID )).'" id="cb-select-'.sprintf( esc_html__($post->ID )).'"></td><td class="idnum column-name has-row-actions column-primary" data-colname="ID"><strong>'.sprintf( esc_html__($post->ID )).'</strong></td><td class="name column-name has-row-actions column-primary" data-colname="Name"><strong>'.sprintf($category_link ).'</strong></td><td class="title column-title" data-colname="Title">'. sprintf( esc_html__($post->post_title )) .'</td><td class="post_modified column-post_modified" data-colname="post_modified">'.sprintf( esc_html__($post->post_modified )).'</td><td class="post_modified column-post_type" data-colname="post_type">'.sprintf( esc_html__($post->post_type )).'</td></tr>';
					} 
					$tablehtml = $tablehtml . '</tbody>';
				}
			}
		}
		
		echo $tablehtml;
		
		die();
	}
	
	public function get_string_between($string, $start, $end, $end2=''){
		$string = ' ' . $string;
		$len='';
		$ini = strpos($string, $start);
		if ($ini == 0) return '';
		$ini += strlen($start);
		$pos2 = strpos($string, $end, $ini);
		if($pos2>0){
			$len = strpos($string, $end, $ini) - $ini;
		}
		$len2 =5000000;
		if($end2!=''){
			$len2 = strpos($string, $end2, $ini) - $ini;
		}
		if($len2<$len){
			$len=$len2;
		}
		if($len>0){
			$result = substr($string, $ini, $len);
		} else {
			$result = substr($string, $ini);
		}
		return $result;
	}
	
	public function get_string_between_reverse($string, $start, $end){
		$string = " ".$string;
		$ini = strrpos($string,$start);
		if ($ini == 0) return "";
		$ini += strlen($start);
		$len = strrpos($string,$end,$ini) - $ini;
		return substr($string,$ini,$len);
	
	}
	
	
	
	
	/**
	 * sync woocommerce reviews when clicking the save button on WooCommerce page
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */	
	public function wprevpro_download_woo() {
      global $pagenow;
      if (isset($_GET['settings-updated']) && $pagenow=='admin.php' && current_user_can('export') && $_GET['page']=='wp_pro-get_woo') {
		$this->wprevpro_download_woo_master();
      }
    }

	/**
	 * download woocommerce reviews
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */	
	public function wprevpro_download_woo_master() {
		$options = get_option('wprevpro_woo_settings');
		//print_r($options);
		//Array([woo_radio_sync] => yes,[woo_sync_all] => all)
		global $wpdb;
		if($options['woo_radio_sync']!='no'){
			//grab all woocommerce reviews depending on settings
			
			
			if($options['woo_radio_sync']=='yes'){		//sync reviews only
				if($options['woo_sync_all']=='all'){
					$args = array(
						'type__in'  => 'review',
						 'parent'      => 0,	//don't get responses yet
						 'status' => 'all',
					);
				} else if($options['woo_sync_all']=='approved'){
					$args = array(
						'type__in'  => 'review',
						 'parent'      => 0,	//don't get responses yet
						 'status' => 'approve',
					);
				}
			} else if($options['woo_radio_sync']=='com'){		//sync reviews only
				if($options['woo_sync_all']=='all'){
					$args = array(
						'type__in'  => 'comment',
						 'parent'      => 0,	//don't get responses yet
						 'status' => 'all',
					);
				} else if($options['woo_sync_all']=='approved'){
					$args = array(
						'type__in'  => 'comment',
						 'parent'      => 0,	//don't get responses yet
						 'status' => 'approve',
					);
				}
			} else if($options['woo_radio_sync']=='rc'){		//sync reviews only
				if($options['woo_sync_all']=='all'){
					$args = array(
						'type__in'  => array('review','comment'),
						 'parent'      => 0,	//don't get responses yet
						 'status' => 'all',
					);
				} else if($options['woo_sync_all']=='approved'){
					$args = array(
						'type__in'  => array('review','comment'),
						 'parent'      => 0,	//don't get responses yet
						 'status' => 'approve',
					);
				}
			}
				
			$comments = get_comments( $args );
			
			//print_r($comments);
			
			//echo get_avatar( 'jgwhite33@hotmail.com', 32 );
			//echo get_avatar_url( 'jgwhite33@hotmail.com', 32 );
			
			//loop through the comments, find the avatar, and the rating, date, product image, product title, cat id, prod id, text, etc...
			foreach ($comments as $comment) {
					// Output comments etc here
									
					$table_name = $wpdb->prefix . 'wpfb_reviews';
					//add check to see if already in db, skip if it is and end loop
					$reviewindb = 'no';
					$unixtimestamp = $this->myStrtotime($comment->comment_date);
					$checkrow = $wpdb->get_var( "SELECT id FROM ".$table_name." WHERE created_time_stamp = '".$unixtimestamp."' " );
					
					$tempreviewarray = $this->wprevpro_returncommentinfoarray($comment,$options['woo_name_options']);
					
					$checkreviewerid = $tempreviewarray['reviewer_id'];
					$checkrevlengthchar = $tempreviewarray['review_length_char'];
					
					$checkrow2 = $wpdb->get_var( "SELECT id FROM ".$table_name." WHERE reviewer_id = '".$checkreviewerid."' AND review_length_char = '".$checkrevlengthchar."' " );
					
					if( empty( $checkrow ) && empty( $checkrow2 ) ){
						$reviewindb = 'no';
						$reviews['add'][] = $tempreviewarray;
					} else {
						$reviewindb = 'yes';
						$reviews['update'][] = $tempreviewarray;
					}
					unset($tempreviewarray);
					
			}
			
			//insert or update array in to reviews table.
			if(isset($reviews['add']) && count($reviews['add'])>0){
				foreach ( $reviews['add'] as $stat ){
					$insertnum = $wpdb->insert( $table_name, $stat );
					//update badge totals
					$this->updatetotalavgreviews('woocommerce', $stat['pageid'], '','',$stat['pagename']);
				}
				$this->errormsg = count($reviews['add']).' '.esc_html__('added to database.', 'wp-review-slider-pro');
				
				//send $reviews array to function to send email if turned on.
				$this->sendnotificationemail($reviews['add'], "woocommerce");
			}
			if(isset($reviews['update']) && count($reviews['update'])>0){
				foreach ( $reviews['update'] as $stat ){
					$tempreviewid = $stat['reviewer_id'];
					$insertnum = $wpdb->update( $table_name, $stat,array( 'reviewer_id' => $tempreviewid ) );
					//update badge totals
					$this->updatetotalavgreviews('woocommerce', $stat['pageid'], '','',$stat['pagename']);
				}
				$this->errormsg = count($reviews['update']).' '.esc_html__('updated in database.', 'wp-review-slider-pro');
			}

			//we also need to hook in to when a new comment is added, deleted, approved, unapproved
			
		}
		
		//check to see if we need to push to woo
		$this->wprevpro_push_to_woo_check();
		
	}
	
	//see if we have push to woo turned on then do it.
	public function wprevpro_push_to_woo_check(){
		$options = get_option('wprevpro_woo_settings');
		//print_r($options);
		//Array([woo_radio_sync] => yes,[woo_sync_all] => all)
		global $wpdb;
		//ability to update total and average back in to Woocommerce
		if($options['woo_push_type']=='yes'){
			//get all products and then loop each one.
			$args = array(
			'post_type'      => 'product',
			'posts_per_page' => 10000,
			);

			$loop = new WP_Query( $args );

			while ( $loop->have_posts() ) : $loop->the_post();
				global $product;
				$productid = get_the_ID();
				//get total from woo, then total from wprev, add together and update. get_the_ID
				//echo "<br />prductrating: ".get_the_title()." - ".$product->get_rating_count()." - ".get_the_ID();
				
				//find all reviews in wprev table that have this product id asociated.
				$table_name = $wpdb->prefix . 'wpfb_reviews';
				$wprevreviews = $wpdb->get_results( "SELECT * FROM ".$table_name." WHERE type != 'WooCommerce' AND posts LIKE '%".$productid."%' ",ARRAY_A );
				
				//print_r($wprevreviews);

				foreach ($wprevreviews as $wprevreview) {
					//loop review and add to wordpress comments table as a review
					//print_r($wprevreview);
					$this->wprevpro_push_to_woo($wprevreview,$productid);
				}

				//this auto updates, _wc_review_count, _wc_rating_count, _wc_average_rating
			
			endwhile;
			
			//start up cron job if not set.
			if (! wp_next_scheduled ( 'wprevpro_pushtowoo' )) {
				$starttime = time()+3000;
				wp_schedule_event($starttime, 'daily', 'wprevpro_pushtowoo');  
			}

				
		} else if($options['woo_push_type']=='no'){
			//we need to undo what we did.
			//delete all comments that have wprev_comment meta set to 1
			$args = array(
				'meta_query' => array(
					array(
						'key' => 'wprev_comment',
						'value' => '1'
					)
				)
			 );
			$comments_query = new WP_Comment_Query;
			$comments = $comments_query->query( $args );
			// Comment Loop
			if ( $comments ) {
				foreach ( $comments as $comment ) {
					//echo '<p>' . $comment->comment_content . '</p>';
					wp_delete_comment($comment->comment_ID,true);
				}
			} else {
				//echo 'No comments found.';
			}
			
			//unschedule cron job.
			$time_next_firing = wp_next_scheduled("wprevpro_pushtowoo");
			//use this function to unschedule it by passing the time and event name
			wp_unschedule_event($time_next_firing, "wprevpro_pushtowoo");
			wp_clear_scheduled_hook('wprevpro_pushtowoo');
		}
	}

	public function wprevpro_cron_push_to_woo(){
		global $wpdb;
		// WP_Comment_Query arguments
		$args = array(
			'status'         => 'approve',
			'type'           => 'review',
			'post_type'      => 'Product',
			'number'         => '20',
			'order'          => 'DESC',
			'orderby'        => 'comment_ID'
		);

		// The Comment Query
		$my_comment_query = new WP_Comment_Query;
		$comments = $my_comment_query->query( $args );

		// Check for comments. 
		if ( $comments ) {
				// Loop over comments. 
				foreach( $comments as $comment ) {
					$productid = $comment->comment_post_ID;
					
					//look for any reviews with this productid.
					$table_name = $wpdb->prefix . 'wpfb_reviews';
					$wprevreviews = $wpdb->get_results( "SELECT * FROM ".$table_name." WHERE type != 'WooCommerce' AND posts LIKE '%".$productid."%' ",ARRAY_A );
					
					foreach ($wprevreviews as $wprevreview) {
						//loop review and add to wordpress comments table as a review
						//print_r($wprevreview);
						$this->wprevpro_push_to_woo($wprevreview,$productid);
					}
					
					$productid = '';
				}
		} else {
			// Display message if no comments are found. 
		}
		
	}

	/**
	 * Used to add or remove wprev reviews to woocommerce review table
	 * @access  public
	 * @since   12.0.6
	 * @return  void
	 */	
	public function wprevpro_push_to_woo($wprevreview,$productid){
		global $wpdb;
		$tempuserid = 0;
		$user = get_user_by( 'email', $wprevreview['reviewer_email'] );
		if ( ! empty( $user ) ) {
			$tempuserid = $user->ID;
		}
		if($wprevreview['rating']=='' || $wprevreview['rating']=='0' || $wprevreview['rating']==0){
			if($wprevreview['recommendation_type']=="positive"){
				$wprevreview['rating']= 5;
			} else if($wprevreview['recommendation_type']=="negative"){
				$wprevreview['rating']= 2;
			}
		}
		
		$wprevreview['review_text'] = apply_filters( 'pre_comment_content', $wprevreview['review_text'] );
		$wprevreview['reviewer_name'] = apply_filters( 'pre_comment_author_name', $wprevreview['reviewer_name'] );
		
		//check to make sure this has not already been added.
		$table_name = $wpdb->prefix . 'comments';
		//add check to see if already in db, skip if it is and end loop
		$checkrowwoo = $wpdb->get_var( "SELECT comment_ID FROM ".$table_name." WHERE comment_author = '".$wprevreview['reviewer_name']."' AND comment_content = '".$wprevreview['review_text']."' " );
		
		if( empty( $checkrowwoo ) ){
			$comment_id = wp_insert_comment( array(
				'comment_post_ID'      => $productid,
				'comment_author'       => $wprevreview['reviewer_name'],
				'comment_author_email' => $wprevreview['reviewer_email'],
				'comment_author_url'   => $wprevreview['from_url_review'],
				'comment_content'      => $wprevreview['review_text'],
				'comment_type'         => 'review',
				'comment_parent'       => 0,
				'user_id'              => $tempuserid,
				'comment_author_IP'    => '',
				'comment_agent'        => '',
				'comment_date'         => $wprevreview['created_time'],
				'comment_approved'     => 1,
				'comment_meta'         => array(
					'wprev_comment' => "1",
					'wprev_type'   => $wprevreview['type'],
					'wprev_title'       => $wprevreview['review_title'],
					'wprev_pageid'       => $wprevreview['pageid'],
					'wprev_pagename'       => $wprevreview['pagename'],
					'wprev_from_url'       => $wprevreview['from_url'],
				)
			) );
			if($comment_id){
				update_comment_meta($comment_id, 'rating', $wprevreview['rating']);
				update_comment_meta($comment_id, 'verified', 1);
			}
		}

	}
	
	/**
	 * ran when a new comment is inserted, deleted (or spam), or updated (approved, unapproved)
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */	
	public function wprevpro_returncommentinfoarray($comment,$nameoption = 'author'){
		//print_r($comment);
		$options = get_option('wprevpro_woo_settings');
		$user_name = $comment->comment_author;
		$results['reviewer_name'] = $user_name;
		$results['reviewer_id'] = 'woo_'.str_replace(' ','',$user_name)."_".$comment->user_id."_".$comment->comment_ID;
			//if we need first or last name find it here
			if($nameoption!='author'){
				$user = get_user_by( 'email', $comment->comment_author_email );
				if ( ! empty( $user ) ) {
				//echo ‘User is ‘ . $user->first_name . ‘ ‘ . $user->last_name;
					if($nameoption=='first' && isset($user->first_name) && $user->first_name!=''){
						$results['reviewer_name'] = $user->first_name;
					} else if($nameoption=='last' && isset($user->last_name) && $user->last_name!=''){
						$results['reviewer_name'] = $user->last_name;
					} else if($nameoption=='firstlast'){
						if((isset($user->last_name) && $user->last_name!='') || (isset($user->first_name) && $user->first_name!='')){
						$results['reviewer_name'] = $user->first_name.' '.$user->last_name;
						}
					}
				}
			}
		
		$pageid = $comment->comment_post_ID;
		$results['pageid'] = $pageid;
		$post = get_post( $pageid ); 
		//print_r($post);
		$results['pagename'] = $post->post_title;	//use for the product title
		$results['from_url'] = get_permalink($pageid);
		$results['userpic'] = get_avatar_url( $comment->comment_author_email, 80 );
		$results['rating'] = get_comment_meta( $comment->comment_ID, 'rating', true );
		//if rating not set then we need to use default value here.
		if(!isset($results['rating'])){
			$results['rating'] = 0;
		}
		if($results['rating']=='' || $results['rating']==0 || $results['rating']==false){
			if(isset($options['woo_rating_options']) && $options['woo_rating_options']!='blank'){
				$results['rating'] = $options['woo_rating_options'];
			}
		}
				
		$unixtimestamp = $this->myStrtotime($comment->comment_date);
		$results['created_time_stamp'] = $unixtimestamp;
		$results['created_time'] = date("Y-m-d H:i:s", $unixtimestamp);
		$results['review_text'] = $comment->comment_content;
		$results['review_length'] = str_word_count($results['review_text']);
		if (extension_loaded('mbstring')) {
			$results['review_length_char'] = mb_strlen($results['review_text']);
		} else {
			$results['review_length_char'] = strlen($results['review_text']);
		}
					
		$hideme = $comment->comment_approved;
		if($hideme==0){
			$results['hide'] = 'yes';
		} else {
			$results['hide'] = 'no';
		}
		//["-107-"],["-18-","-25-"]
		$posts = array();
		$posts[] = "-".intval($comment->comment_post_ID)."-";	//encoding here so we can add more later
		$results['posts'] = json_encode($posts);
		//find cats
		$catidarray = array();
		//woocommerce check 
		$categories = get_the_terms( $pageid, 'product_cat');
		if(is_array($categories)){
			$arrlength = count($categories);
			if($arrlength>0 && $categories){
				for($x = 0; $x < $arrlength; $x++) {
					$catidarray[] = "-".$categories[$x]->term_id."-";	//array containing just the cat_IDs that this post belongs to, dashes added so we can use like search
				}
			}
		}
		$results['categories'] = json_encode($catidarray);
		$results['type'] = 'WooCommerce';
		
		//product image
		$productimage = wp_get_attachment_image_src( get_post_thumbnail_id( $pageid ), 'thumbnail' );
		if(!$productimage){
			$productimage[0] = '';
		}
		
		
		$results['miscpic'] = $productimage[0];
		
		//if this is a wpml site then try to get language of parent post id.
		$wpml_post_language_details = apply_filters( 'wpml_post_language_details', NULL, $pageid  ) ;
		$results['language_code'] ='';
		if(isset($wpml_post_language_details['language_code'])){
			$results['language_code'] = $wpml_post_language_details['language_code'];
		}
					
		//print_r($wpml_post_language_details);
		//die();
		return $results;
	}	
	 
	public function wprevpro_woo_deletecomment($comment){
		global $wpdb;
		$table_name = $wpdb->prefix . 'wpfb_reviews';
		$commentinfoarray = $this->wprevpro_returncommentinfoarray($comment);
		$wpdb->delete( $table_name, array( 'reviewer_id' => $commentinfoarray['reviewer_id'] ) );
		//update badge totals
		$this->updatetotalavgreviews('woocommerce', $commentinfoarray['pageid'], '','',$commentinfoarray['pagename']);
	}
	
	public function wprevpro_woo_changestatus($new_status,$old_status,$comment){
		if($new_status=='spam' || $new_status=='trash'){
			$this->wprevpro_woo_deletecomment($comment);
		} else{
			//comment approved or unapproved via ajax
			$comment_id = $comment->comment_ID;
			$this->wprevpro_woo_iud_comment($comment_id);
		}
	}
	
	
	public function wprevpro_woo_iud_comment($comment_ID,$info=''){
		
		if(get_option('wprevpro_woo_settings')){

		//echo "comment inserted or updated, get info and insert or update reviews table";
		$comment = get_comment( $comment_ID );
		$options = get_option('wprevpro_woo_settings');
		$addreview = false;
		
		if(is_object($comment)){
	
				//need check to see if we are syncing this type.
				
				if($options['woo_radio_sync']=='com' && $comment->comment_type=="comment"){
					$addreview = true;
				}
				if($options['woo_radio_sync']=='yes' && $comment->comment_type=="review"){
					$addreview = true;
				}
				if($options['woo_radio_sync']=='rc' && ($comment->comment_type=="review" || $comment->comment_type=="comment")){
					$addreview = true;
				}
		
			
				if($addreview==true){
					global $wpdb;
					$table_name = $wpdb->prefix . 'wpfb_reviews';
					
					//get comment data and insert or update below.
					$commentinfoarray = $this->wprevpro_returncommentinfoarray($comment);

					//if marked as spam them remove from wpprorev db
					if($comment->comment_approved=='spam' || $comment->comment_approved=='trash'){
						$this->wprevpro_woo_deletecomment($comment);
					}
					
					//if radio option is set to Approved only and this comment is unapproved then do nothing, $options['woo_sync_all']=='approved'
					
					if($options['woo_sync_all']=='approved' && $comment->comment_approved!=1){
						//don't do anything since not syncing unapproved comments
					} else {
						//find out if we need to update or insert
						$checkrow = $wpdb->get_var( "SELECT id FROM ".$table_name." WHERE reviewer_id = '".$commentinfoarray['reviewer_id']."' " );
						if( empty( $checkrow ) ){
							//not in db, insert it
							$insertnum = $wpdb->insert( $table_name, $commentinfoarray );
						} else {
							//is in db, update it.
							$insertnum = $wpdb->update( $table_name, $commentinfoarray,array( 'reviewer_id' => $commentinfoarray['reviewer_id'] ) );
						}
						//update badge totals
						$this->updatetotalavgreviews('woocommerce', $commentinfoarray['pageid'], '','',$commentinfoarray['pagename']);
					}
				}
			}
		}
	}
	public function wprevpro_woo_iud_comment_delete($comment_ID,$comment){
		//comment being deleted, delete from our db as well
		$this->wprevpro_woo_deletecomment($comment);
	}
	//============end woocommerce=========================
	
	//================for review funnels=======================================
	
	/**
	 * used to call review funnel server to download reviews. server will check one more time for valid license
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */	
	
	//used to make call to dataforseo server to find locations for google. used in dropdown list when setting up new job
	public function wprp_rf_dataforseo_glocations_ajax(){
		check_ajax_referer('randomnoncestring', 'wpfb_nonce');
		$serverurl = 'https://funnel.ljapps.com/locationcodes';
		$args = array(
			'timeout'     => 60,
			'sslverify' => false,
			'headers' => array( 
				'Content-Type' => 'application/json'
			) 
		);
		$response = wp_remote_get( $serverurl,$args );
		$body ='';
		if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				$headers = $response['headers']; // array of http header lines
				$body    = $response['body']; // use the content
		}
		echo $body;
		die();
	}
	//used to make call to dataforseo server to find locations for google. used in dropdown list when setting up new job
	public function wprp_rf_dataforseo_glangs_ajax(){
		check_ajax_referer('randomnoncestring', 'wpfb_nonce');
		$serverurl = 'https://api.dataforseo.com/v3/business_data/google/languages';
		$args = array(
			'timeout'     => 60,
			'sslverify' => false,
			'headers' => array( 
				'Content-Type' => 'application/json',
				'Authorization'=> 'Basic am9zaEBsamFwcHMuY29tOjM3ZjFhNmYwZWI0YjJmOGI=' 
			) 
		);
		$response = wp_remote_get( $serverurl,$args );
		$body ='';
		if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				$headers = $response['headers']; // array of http header lines
				$body    = $response['body']; // use the content
				$listarray = json_decode($body,true);
				$langsarray=$listarray['tasks'][0]['result'];
		}
		echo json_encode($langsarray);
		die();
	}	
	
	
	//make a call to get number of current jobid in que, how many jobs in front
	public function wprp_revfunnel_listjobque_ajax(){
		check_ajax_referer('randomnoncestring', 'wpfb_nonce');
		$jobid = sanitize_text_field($_POST['jid']);
		$jobid = intval($jobid );
		$frlicenseid = get_option( 'wprev_fr_siteid' );
		$options = get_option('wprevpro_funnel_options');

		$dbsiteinfo_id = $options['dbsiteinfo_id'];	//this is the id of the site in the db on remote server
		
		//continue here
		$serverurl = 'https://funnel.ljapps.com/jobque?sid='.intval($dbsiteinfo_id).'&frlicenseid='.intval($frlicenseid).'&jobid='.$jobid;
		
		//echo $serverurl;
		//die();
		
		$resultarray['serverurl']=$serverurl;
		
		$args = array(
			'timeout'     => 60,
			'sslverify' => false
		);
		$response = wp_remote_get( $serverurl,$args );
			
		//print_r($response);

		$resultarray['result']='';
		if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				$headers = $response['headers']; // array of http header lines
				$body    = $response['body']; // use the content
				$listjobarray = json_decode($body,true);
				$resultarray['result']=$listjobarray['result'];
		}
		echo json_encode($resultarray);
		die();
	}	
	 
	 
	 //list the funnel jobs
	public function wprp_revfunnel_listjobs_ajax(){
		set_time_limit(60);
		check_ajax_referer('randomnoncestring', 'wpfb_nonce');
		$fid = sanitize_text_field($_POST['fid']);
		$fid =intval($fid );
		$frlicenseid = get_option( 'wprev_fr_siteid' );
		$options = get_option('wprevpro_funnel_options');
		
		global $wpdb;
		$table_name = $wpdb->prefix . 'wpfb_reviewfunnel';
		$reviewfunneldetails = $wpdb->get_row( "SELECT * FROM $table_name WHERE id = $fid" );
		$dbsiteinfo_id = $options['dbsiteinfo_id'];	//this is the id of the site in the db on remote server
		
		//continue here
		$serverurl = 'https://funnel.ljapps.com/listaddprofilejobs?sid='.intval($dbsiteinfo_id).'&frlicenseid='.intval($frlicenseid).'&scrapeurl='.urlencode($reviewfunneldetails->url).'&scrapequery='.urlencode($reviewfunneldetails->query).'&scrapeplaceid='.urlencode($reviewfunneldetails->googleplaceid);
		
		//echo $serverurl;
		
		$resultarray['serverurl']=$serverurl;
		
		$args = array(
				'timeout'     => 60,
				'sslverify' => false
		); 
		$response = wp_remote_get($serverurl,$args);
			
		//$response = wp_remote_get( $serverurl );

			$resultarray['result']='';
 			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				$headers = $response['headers']; // array of http header lines
				$body    = $response['body']; // use the content
				$listjobarray = json_decode($body,true);
				//print_r($listjobarray);
				//die();
				//if we have an error adding job then we display a warning.
				if($listjobarray['ack']=='querydb'){
					$resultarray['ack']=$listjobarray['ack'];
					$addjobresultarray = $listjobarray['result'];
					//catch error from reviewscrape
					if(!is_array($addjobresultarray)){
						$resultarray['ack']='error';
						$resultarray['msg']= esc_html__('Error 01! DB error.', 'wp-review-slider-pro').' '.$addjobresultarray['message'].' '.esc_html__('Contact Support.', 'wp-review-slider-pro');
					} else {
						//list jobs
						$resultarray['result']=$listjobarray['result'];
					}
				} else if($listjobarray['ack']=='error'){
					$resultarray['ack']='error';
					$resultarray['msg']=esc_html__('Error 02!', 'wp-review-slider-pro').' '.$listjobarray['ackmessage'].' '.esc_html__('Contact Support.', 'wp-review-slider-pro');
				} else {
					$resultarray['ack']='error';
					$resultarray['msg']=esc_html__('Error 03! Trouble communicating with server.', 'wp-review-slider-pro').' '.$serverurl." : ".$response ['body'];
				}
			} else {
				$resultarray['ack']='error';
				$resultarray['msg']=esc_html__('Error 04! Trouble communicating with server.', 'wp-review-slider-pro').' '.$serverurl." : ".$response ['body'];
			}
			
		echo json_encode($resultarray);
		die();
		
	}

	/**
	 * used to call review funnel server to add scrape job. called via ajax on review funnel page. server will check one more time for valid license
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	//function to update total credits used and left__373c0__2pnx_
	public function updatecreditsoptions(){
		$frlicenseid = get_option( 'wprev_fr_siteid' );
		$frsiteurl = get_option( 'wprev_fr_url' );
		$wpsiteurl = get_site_url();
		$serverurl = 'https://funnel.ljapps.com/frstats?frlicenseid='.$frlicenseid.'&frsiteurl='.$frsiteurl.'&wpsiteurl='.$wpsiteurl;
		$args = array(
			'timeout'     => 60,
			'sslverify' => false
		);
		$response = wp_remote_get( $serverurl,$args );
		
		//echo 'https://funnel.ljapps.com/frstats?frlicenseid='.$frlicenseid.'&frsiteurl='.$frsiteurl.'&wpsiteurl='.$wpsiteurl;
		 
		if ( is_array( $response ) && ! is_wp_error( $response ) ) {
			$headers = $response['headers']; // array of http header lines
			$body    = $response['body']; // use the content
		}
		$licensecheckarray = json_decode($body,true);

		//error check
		if($licensecheckarray['ack']!="success"){
			//echo '<div class="w3-panel w3-red"><p>'.__( 'Error: Unable to check your review credit balance. Please try again.', 'wp-review-slider-pro' ).$licensecheckarray['ackmessage'].'</p></div> ';
			$resultarray['ack']='error';
			$resultarray['msg']=esc_html__('Error: Unable to check your review credit balance. Please try again.', 'wp-review-slider-pro').' ';
			return $resultarray;
			die();
		}

		//print_r($licensecheckarray);
		$statsarray=$licensecheckarray['stats'];

		//update options in db, so we can check before we make call to server, also do this when using cron job
		$tempoptions['ack']=$licensecheckarray['ack'];
		$tempoptions['totalreviewbank']=$statsarray['totalreviewbank'];
		$tempoptions['totalreviewcreditsused']=$statsarray['totalreviewcreditsused'];
		$tempoptions['dbsiteinfo_id']=$statsarray['id'];
		update_option('wprevpro_funnel_options',$tempoptions);
	}
	
	public function wprp_revfunnel_addprofile_ajax(){
		check_ajax_referer('randomnoncestring', 'wpfb_nonce');
		$fid = sanitize_text_field($_POST['fid']);
		$diffparam = sanitize_text_field($_POST['rv']);	//only get new reviews or not usediff or nodiff, usediff is new only
		$fid =intval($fid );
		$resultarray = $this->wprp_revfunnel_addprofile_ajax_go($fid, $diffparam);
		echo json_encode($resultarray);
		die();
	}
	//calling from above and also calling this from cron job
	public function wprp_revfunnel_addprofile_ajax_go($fid, $diffparam, $cron=""){
		$frlicenseid = get_option( 'wprev_fr_siteid' );
		$frsiteurl = urlencode(get_option( 'wprev_fr_url' ));
		$resultarray['job_id']='';
		$resultarray['scrapeurl']='';
		$savecron = 'no';
		if($cron>0){
			$savecron = intval($cron);
		}
		//run check to update total credits used
		$this->updatecreditsoptions();
		
		//make a call to server, only if we are not out of calls and this site has passed check.
		//$options['ack'], $options['totalreviewbank'], $options['totalreviewcreditsused']
		$options = get_option('wprevpro_funnel_options'); 
		if($options['ack']!="success"){
			//return error message here
			$resultarray['ack']='error';
			$resultarray['msg']=esc_html__('Oops, it looks like this site does not have a valid license.', 'wp-review-slider-pro').' ';

		}else if($options['totalreviewbank']<$options['totalreviewcreditsused']){
			//return error message here
			$resultarray['ack']='error';
			$resultarray['msg']=esc_html__('Oops, it appears that you have used up your review quota.', 'wp-review-slider-pro').' ';
			
		} else {
			$resultarray['ack']='success';
			$resultarray['msg']='passed checks';
			//now find info for this review funnel from WP db
			global $wpdb;
			$table_name = $wpdb->prefix . 'wpfb_reviewfunnel';
			$reviewfunneldetails = $wpdb->get_row( "SELECT * FROM $table_name WHERE id = $fid" );
			$scrapeurl = $reviewfunneldetails->url;
			$scrapequery =  $reviewfunneldetails->query;
			$scrapefromdate = $reviewfunneldetails->from_date;
			$scrapeblocks = $reviewfunneldetails->blocks;
			$scrapeplaceid =  $reviewfunneldetails->googleplaceid;
			//check for google and use query if set
			if($reviewfunneldetails->site_type=="google" || $reviewfunneldetails->site_type=="Google"){
				$scrapefromdate='';
				$scrapeurl='';
				//if($reviewfunneldetails->gplaceorsearch=="placeid"){
				//	$scrapequery='';
				//} else {
				//	$scrapeplaceid='';
				//}
			} else if($reviewfunneldetails->site_type=="facebook" || $reviewfunneldetails->site_type=="Facebook"){
				if($scrapeblocks=="fall"){
					$scrapeblocks='fall';
				} else {
					$scrapefromdate='2001-01-01';
					$scrapeblocks='50';
				}
				
			} else {
				
				//$scrapeblocks='';
				$scrapequery='';
			}
			
			//$resultarray['job_id']='35';
			$resultarray['dbsiteinfo_id']=$options['dbsiteinfo_id'];
			$dbsiteinfo_id = $options['dbsiteinfo_id'];	//this is the id of the site in the db on remote server
			
			//echo  'https://funnel.ljapps.com/addprofile?sid='.intval($dbsiteinfo_id).'&frlicenseid='.intval($frlicenseid).'&frsiteurl='.$frsiteurl.'&scrapeurl='.urlencode($scrapeurl).'&scrapequery='.urlencode($scrapequery).'&scrapefromdate='.$scrapefromdate.'&scrapeblocks='.$scrapeblocks.'&diffparam='.$diffparam;
			
			//continue here
			$wpsiteurl = urlencode(get_site_url());
			
			$resultarray['scrapejoburl'] = 'https://funnel.ljapps.com/addprofile?sid='.intval($dbsiteinfo_id).'&frlicenseid='.intval($frlicenseid).'&frsiteurl='.$frsiteurl.'&scrapeurl='.urlencode($scrapeurl).'&scrapequery='.urlencode($scrapequery).'&scrapefromdate='.$scrapefromdate.'&scrapeblocks='.$scrapeblocks.'&diffparam='.$diffparam.'&wpsiteurl='.$wpsiteurl.'&scrapeplaceid='.urlencode($scrapeplaceid).'&savecron='.$savecron;
			
			//echo $resultarray['scrapejoburl'];
			//die();
			
			 $response = wp_remote_get( $resultarray['scrapejoburl'], array( 'sslverify' => false, 'timeout' => 60 ) );
			//$response = wp_remote_get( $resultarray['scrapejoburl']);
			
 			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				$headers = $response['headers']; // array of http header lines
				$body    = $response['body']; // use the content
				$addscrapeprofilearray = json_decode($body,true);
				//if we have an error adding job then we display a warning.
				if($addscrapeprofilearray['ack']=='curl'){
					$resultarray['ack']=$addscrapeprofilearray['ack'];
					$addjobresultarray = json_decode($addscrapeprofilearray['result'],true);
					//print_r($addjobresultarray);
					//catch error from reviewscrape
					if(!$addjobresultarray['success']){
						$resultarray['ack']='error';
						$resultarray['msg']='Error! '.$addjobresultarray['message'].'. Contact Support.';
					} else {
						//grab the job_id and save to db with this funnel
						$job_id = $addjobresultarray['job_id'];
						$resultarray['job_id']=$job_id;
						//====job_ids are saved on server calls table. we can ping it for updates.
						//if $resultarray['job_id'] is not blank then we have success.
					}
				} else if($addscrapeprofilearray['ack']=='error'){
					$resultarray['ack']='error';
					$resultarray['msg']='Error! '.$addscrapeprofilearray['ackmessage'].' '.esc_html__('Contact Support.', 'wp-review-slider-pro');
				}
			} else {
				$resultarray['ack']='error';
				$resultarray['msg']=esc_html__('Error! Can not wp_remote_get Contact Support.', 'wp-review-slider-pro');
			}
		}
		return $resultarray;
	}
	
	/**
	 * used to call review funnel server to download reviews. server will check one more time for valid license
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */	
	public function wprp_revfunnel_getrevs_ajax(){
		check_ajax_referer('randomnoncestring', 'wpfb_nonce');
		$job_id = sanitize_text_field($_POST['jid']);
		$fid = sanitize_text_field($_POST['fid']);
		$pagenum = sanitize_text_field($_POST['pnum']);
		$perpage = sanitize_text_field($_POST['perp']);
		
		$resultarray = $this->wprp_revfunnel_getrevs_ajax_go($job_id,$fid,$pagenum,$perpage);
		//use resultarray to communicate back to javascript
		echo json_encode($resultarray);
		die();
	}
	public function wprp_revfunnel_getrevs_ajax_go($job_id,$fid,$pagenum=1,$perpage=100){
		
		$frlicenseid = get_option( 'wprev_fr_siteid' );
		$fid =intval($fid );
		$pagenum =intval($pagenum );
		$perpage =intval($perpage );
		
		global $wpdb;
		$table_name_funnel = $wpdb->prefix . 'wpfb_reviewfunnel';
		$reviewfunneldetails = $wpdb->get_row( "SELECT * FROM $table_name_funnel WHERE id = $fid" );
		$pagename = $reviewfunneldetails->title;
		$pageid = str_replace(" ","",$pagename)."_".$reviewfunneldetails->id;
		$pageid = str_replace("'","",$pageid);
		$pageid = str_replace('"',"",$pageid);
		$pageid = preg_replace('/[^A-Za-z0-9\-]/', '', $pageid);
		
		//starting in version 11.4.9 we are now pulling pageid from when it was saved when form was saved.
		$reviewlistpageid = '';
		if(isset($reviewfunneldetails->reviewlistpageid) && $reviewfunneldetails->reviewlistpageid!=''){
			$pageid = $reviewfunneldetails->reviewlistpageid;
		}

		$sitetype = $reviewfunneldetails->site_type;
		if($sitetype=='Google'){
			if(isset($reviewfunneldetails->query) && $reviewfunneldetails->query!=''){
				$listedurl= 'https://www.google.com/search?q='.urlencode($reviewfunneldetails->query);
			}
			if(isset($reviewfunneldetails->gplaceorsearch) && $reviewfunneldetails->gplaceorsearch=='placeid'){
				if(isset($reviewfunneldetails->googleplaceid) && $reviewfunneldetails->googleplaceid!=''){
				$listedurl= 'https://www.google.com/maps/place/?q=place_id:'.$reviewfunneldetails->googleplaceid;
				}
			}
		} else {
			$listedurl= urldecode($reviewfunneldetails->url);
		}
		$tempcats='';
		if(isset($reviewfunneldetails->categories)){
		$tempcats=$reviewfunneldetails->categories;
		}
		$tempposts='';
		if(isset($reviewfunneldetails->posts)){
		$tempposts=$reviewfunneldetails->posts;
		}

		//print_r($reviewfunneldetails);
			
		//make a call to server, only if we are not out of calls and this site has passed check.
		//$options['ack'], $options['totalreviewbank'], $options['totalreviewcreditsused']
		$resultarray['ack']='';
		$resultarray['msg']='';
		$resultarray['dbsiteinfo_id']='';
		$resultarray['numinserted']='';
		$resultarray['numreturned']='';
		$resultarray['scraperesult']='';
		
		$options = get_option('wprevpro_funnel_options'); 
		if($options['ack']!="success"){
			//return error message here
			$resultarray['ack']='error';
			$resultarray['msg']=esc_html__('Oops, it looks like this site does not have a valid license.', 'wp-review-slider-pro').' ';

		} else {
			$resultarray['ack']='success';
			$resultarray['msg']='passed checks';
			$resultarray['dbsiteinfo_id']=$options['dbsiteinfo_id'];
			$dbsiteinfo_id = $options['dbsiteinfo_id'];	//this is the id of the site in the db on remote server
			//continue here
			$callurl = 'https://funnel.ljapps.com/getrevs?jid='.intval($job_id).'&sid='.intval($dbsiteinfo_id).'&frlicenseid='.intval($frlicenseid).'&pnum='.$pagenum.'&perp='.$perpage;
			//echo $callurl;
			//die();
			$resultarray['callurl']=$callurl;
			$args = array(
				'timeout'     => 60,
				'sslverify' => false
			);
			$response = wp_remote_get( $callurl,$args );
 			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				$headers = $response['headers']; // array of http header lines
				$body    = $response['body']; // use the content
			}
			$getrevsarray = json_decode($body,true);
			$resultarray['scraperesult']=$getrevsarray;
			$scraperesultreviewsarray = json_decode($getrevsarray['result'],true);
			
			$resultarray['crawl_status']=$scraperesultreviewsarray['crawl_status'];
			$resultarray['percentage_complete']=$scraperesultreviewsarray['percentage_complete'];
			//print_r($scraperesultreviewsarray);
			$reviewsarray = $scraperesultreviewsarray['reviews'];
			
			
			//print_r($reviewsarray);
			//die();
			
			//insert this in to the review database, return success message and count or error
			//add check to see if already in db, skip if it is and end loop
			$table_name = $wpdb->prefix . 'wpfb_reviews';
			if(is_array($reviewsarray) && count($reviewsarray)>0){
				//go ahead and update review funnel last download time here.--
                $cld = time();
                $cfid = $reviewfunneldetails->id;
                $data = array(
                    'cron_last_download' => "{$cld}"
                );
                $format = array( '%s' );
                $updatetempquery = $wpdb->update(
                    $table_name_funnel,
                    $data,
                    array(
                    'id' => $cfid,
                ),
                    $format,
                    array( '%d' )
                );
				//-------------------------
				
				$resultarray['numreturned'] = count($reviewsarray);
				foreach($reviewsarray as $item) {
					$reviewindb = 'no';

					$reviewer_name = trim($item['name']);
					$reviewer_name =$this->changelastname($reviewer_name, $reviewfunneldetails->last_name);
					$review_text = trim($item['review_text']);
					$review_length = str_word_count($review_text);
					if (extension_loaded('mbstring')) {
						$review_length_char = mb_strlen($review_text);
					} else {
						$review_length_char = strlen($review_text);
					}
					if($review_length_char>0 && $review_length<1){
										$review_length = 1;
									}
					
					$searchname = addslashes($reviewer_name);
					$pagename = trim($pagename);
					$pageid = trim($pageid);
					$timestamp = $this->myStrtotime($item['date']);
					$unixtimestamp = $timestamp;
					$timestamp = date("Y-m-d H:i:s", $timestamp);
						
					$unique_id = trim($item['unique_id']);
						
					//first check non funnels for duplicate
					$checknum = 1;
					$checkrow = $wpdb->get_var( "SELECT id FROM ".$table_name." WHERE reviewer_name = '".$searchname."' AND type = '".$sitetype."' AND reviewfunnel = '' AND (review_length_char = '".$review_length_char."' OR review_length = '".$review_length."')" );
					
					//another check in case the name as been changed, check the unique_id
					if(empty( $checkrow ) && isset($unique_id) && $unique_id!='null' && $unique_id!=''){
						$checkrow = $wpdb->get_var( "SELECT id FROM ".$table_name." WHERE unique_id = '".$unique_id."' AND type = '".$sitetype."' AND pageid = '".$pageid."' AND (review_length_char = '".$review_length_char."' OR review_length = '".$review_length."' OR created_time_stamp = '".$unixtimestamp."')" );
						$checknum = 2;
					}
					
					//now check reviews from this funnel
					if(empty( $checkrow )){
						$checkrow = $wpdb->get_var( "SELECT id FROM ".$table_name." WHERE reviewer_name = '".$searchname."' AND type = '".$sitetype."' AND reviewfunnel = 'yes' AND pagename = '".$pagename."' AND (review_length_char = '".$review_length_char."' OR review_length = '".$review_length."')" );
						$checknum = 3;
					}
					
					//another catch in case name is blank.
					if($searchname=='' && empty( $checkrow )){
						$checkrow = $wpdb->get_var( "SELECT id FROM ".$table_name." WHERE type = '".$sitetype."' AND reviewfunnel = 'yes' AND pagename = '".$pagename."' AND (review_length_char = '".$review_length_char."' OR review_length = '".$review_length."')" );
						$checknum = 4;
					}
					
					//another check looking for first 100 characters.
					if( empty( $checkrow ) && $review_length_char>80){
						$short_review_text= preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $review_text);
						$short_review_text = addslashes(substr($short_review_text,0,80));
						$checkrow = $wpdb->get_var( "SELECT id FROM ".$table_name." WHERE type = '".$sitetype."' AND reviewer_name = '".$searchname."' AND reviewfunnel = 'yes' AND pagename = '".$pagename."' AND review_text LIKE '%".$short_review_text."%' " );
						$checknum = 5;
					}

					$owner_response = '';
					if(isset($item['response']) && is_array($item['response'])){
						$owner_response = json_encode($item['response']);
					}
					
					$addreview = false;
					if( empty( $checkrow )){
						$addreview = true;
					}
					if(!$addreview){
						//echo "checknum:".$checknum."duplicate:".$searchname.":".$review_length_char.":".$review_length;
						
					}
					
					//see if we are default hide to yes from Tools/Settings page
					$hideondownload = get_option( 'wprev_hideondownload', '' );
					$temphide='';
					if($hideondownload=="yes"){
						$temphide = "yes";
					}
					

					if($addreview){
						$reviews[] = [
							'reviewer_name' => $reviewer_name,
							'reviewer_id' => trim($item['id']),
							'pagename' => $pagename,
							'pageid' => $pageid,
							'userpic' => trim($item['profile_picture']),
							'rating' => $item['rating_value'],
							'created_time' => $timestamp,
							'created_time_stamp' => $unixtimestamp,
							'review_text' => $review_text,
							'hide' => $temphide,
							'review_length' => $review_length,
							'review_length_char' => $review_length_char,
							'type' => $sitetype,
							'review_title' => trim($item['review_title']),
							'from_url' => trim($listedurl),
							'from_url_review' => trim($item['url']),
							'company_title' => trim($item['reviewer_title']),
							'location' => trim($item['location']),
							'verified_order' => trim($item['verified_order']),
							'language_code' => trim($item['language_code']),
							'unique_id' => trim($item['unique_id']),
							'meta_data' => trim($item['meta_data']),
							'categories' => trim($tempcats),
							'posts' => trim($tempposts),
							'owner_response' => trim($owner_response),
							'reviewfunnel' => 'yes',
						];
					}
					
				}
				
				//insert or update array in to reviews table.
				$totalreviewsinserted=0;
				//print_r($reviews);
				//die();
				if(isset($reviews) && count($reviews)>0){
					foreach ( $reviews as $stat ){
						$statobj ='';
						$pictocopy='';
						//print_r($stat);
						$insertnum = $wpdb->insert( $table_name, $stat );
						
						$stat['id']=$wpdb->insert_id;
						//echo $wpdb->last_query;
						//echo htmlspecialchars( $wpdb->last_query, ENT_QUOTES );
						//echo "---------";
						//echo $wpdb->last_query;
						//die();
						
						$this->my_print_db_error();
						//die();
						$totalreviewsinserted = $totalreviewsinserted + $insertnum;
						//if inserted and save avatar local turned on, then try to copy here
						if($stat['id']>0 && $reviewfunneldetails->profile_img=="yes" && $stat['userpic']!=''){
							$pictocopy=$stat['userpic'];
							$statobj = (object) $stat;
							$this->wprevpro_download_avatar_tolocal($pictocopy,$statobj);
						}
					}
					
					//send $reviews array to function to send email if turned on.
					$sitetypelower = strtolower($sitetype);
					$this->sendnotificationemail($reviews,$sitetypelower);
				}
				$resultarray['numinserted']=$totalreviewsinserted;
				unset($reviews);
				
				$totalsource = $scraperesultreviewsarray['review_count'];
				$avgsource = $scraperesultreviewsarray['average_rating'];
				
				//search meta_data for ratings_count. 
				//print_r($scraperesultreviewsarray);
				
				if(isset($scraperesultreviewsarray['meta_data'])){
					$metaarray = json_decode($scraperesultreviewsarray['meta_data'],true);
					if(isset($metaarray['ratings_count']) && $metaarray['ratings_count']>0){
						if($sitetype=="Amazon" && $metaarray['ratings_count']>$totalsource){
							$totalsource = intval($metaarray['ratings_count']);
						}
						//echo "rating_count:".$metaarray['ratings_count'];
					}
				}
	
				//update total and avg for badges.
				if(trim($pageid)!=''){
					$temptype = strtolower($sitetype);
					$this->updatetotalavgreviews($temptype, trim($pageid), $avgsource, $totalsource,trim($pagename));
				}
			}
					
		}
		
		return $resultarray;

	}
	
	private function my_print_db_error(){
		global $wpdb;
		if($wpdb->last_error !== '') :
		//print_r($wpdb->last_result);
			$str   =  $wpdb->last_result;
			$query = htmlspecialchars( $wpdb->last_query, ENT_QUOTES );
			$error = htmlspecialchars( $wpdb->last_error, ENT_QUOTES );
			print "<div id='error'>
			<p class='wpdberror'><strong>WordPress database error:</strong> [$error]<br />
			<code>$query</code></p>
			</div>";
		endif;
	}
	

	/**
	 * used to auto setup download forms
	 * @access  public
	 * @since   12.0.4
	 * @return  void
	 */	
	//called from settings js page 

	public function wprevpro_run_autogetrevs_ajax(){
		check_ajax_referer('randomnoncestring', 'wpfb_nonce');
		
		$type = sanitize_text_field($_POST['type']);
		$posttype = sanitize_text_field($_POST['posttype']);
		$customfieldname = sanitize_text_field($_POST['cfn']);
		$hourly = sanitize_text_field($_POST['hourly']);
		$langcode = sanitize_text_field($_POST['langcode']);
		$which = sanitize_text_field($_POST['which']);
		$cron = sanitize_text_field($_POST['cron']);
		$plusdownload = sanitize_text_field($_POST['plusdownload']);

		$resultarray = $this->wprevpro_run_autogetrevs_ajax_go($type, $posttype, $customfieldname, $hourly, $langcode, $which, $cron, $plusdownload);
		
		//use resultarray to communicate back to javascript
		echo json_encode($resultarray);
		die();
	}
	
	public function wprevpro_run_autogetrevs_ajax_go($type, $posttype, $customfieldname, $fhourly, $flangcode, $fwhich, $fcron,$plusdownload=0){
		
		global $wpdb;

		if($type=='google'){
			$rtype='Google-Places-API';
		}
		
		if(!isset($posttype) || $posttype ==''){
			$posttype = "post";
		}
		
		//security===========
		$flangcode = substr($flangcode, 0, 6);
		if($fwhich==''){
			$fwhich='newest';
		} else if($fwhich=='both'){
			$fwhich='both';
		} else if($fwhich=='relevant'){
			$fwhich='relevant';
		}
		if($fcron==''){
			$fcron='';
		} else {
			$fcron = intval($fcron);
		}
		//make sure this is a valid post type.
		$args= array('public' => true);
		$post_types = get_post_types( $args, 'names' );
		if (in_array($posttype, $post_types)){
		  //echo "Match found";
		} else {
		  //echo "Match not found";
		  $posttype = "post";
		}
		//======================

		//get 10 oldest posts that have the meta_key set. working from oldest to newest.
		$table_name_posts = $wpdb->prefix . 'posts';
		$table_name_postmeta = $wpdb->prefix . 'postmeta';
		$startpostid = 0;
		//==================================
		//================ must change $startpostid to use number of rows not last post id. otherwise startposition off
		//=================================================
		if(get_option('wprev_autogetrevs_startpostid')){
			$startpostid = get_option('wprev_autogetrevs_startpostid');
			//get the newest post id. if startpostid is equal or greater then we reset it to zero.
			$latest_cpt = get_posts("post_type=$posttype&numberposts=1");
			//echo $latest_cpt[0]->ID;
			//echo "-";
			if($latest_cpt[0]->ID > 0 && $startpostid >= $latest_cpt[0]->ID){
				$startpostid = 0;
			}
		 }

		$postslist = $wpdb->get_results($wpdb->prepare("SELECT ID,post_title FROM $table_name_posts INNER JOIN $table_name_postmeta ON ".$table_name_postmeta.".post_id = ".$table_name_posts.".ID WHERE ".$table_name_postmeta.".meta_key = %s AND ".$table_name_posts.".post_type = '".$posttype."' AND post_status = 'publish' AND ID > '$startpostid' ORDER BY ID ASC LIMIT 10",$customfieldname));
		
		//echo "SELECT ID,post_title FROM $table_name_posts INNER JOIN $table_name_postmeta ON ".$table_name_postmeta.".post_id = ".$table_name_posts.".ID WHERE ".$table_name_postmeta.".meta_key = $customfieldname AND ".$table_name_posts.".post_type = '".$posttype."' AND post_status = 'publish' AND ID > '$startpostid' ORDER BY ID ASC LIMIT 10";

		$postslist = array_unique($postslist, SORT_REGULAR);
		
		$resultarray['totalpostsfound'] = count($postslist);
		$resultarray['startpostid'] =$startpostid;
		
		$x=0;
		$totalfoundtoinsert = 0;
		$strarraynew = Array();
		$insertidarray = Array();
		$totalreviewsinserted = 0;
		foreach ($postslist as $post) {
			unset($strarraynew);
			$resultarray[$x]['ack'] = '';
			$resultarray[$x]['ackmsg'] = '';
			$resultarray[$x]['ID']= $post->ID;
			$resultarray[$x]['post_title']= $post->post_title;
			
			$resultarray[$x]['customfield']= get_post_meta( $post->ID, $customfieldname, true ); //contains place id or something else in future
			
			update_option('wprev_autogetrevs_startpostid',$post->ID);
			$resultarray['endpostid'] =$post->ID;
			
			//make sure there is actually a placeid
			if($resultarray[$x]['customfield'] && $resultarray[$x]['customfield']!=''){
				
				//now check to see if a review source form WPFB_GETAPPS_FORMS has been setup for this location yet.
				$table_name = $wpdb->prefix . 'wpfb_getapps_forms';
				$checkforform = $wpdb->get_results("SELECT * FROM $table_name where site_type = '".$rtype."' AND page_id = '".$resultarray[$x]['customfield']."' ORDER BY id DESC LIMIT 1");
				
				$strarraynew[] = "-".$post->ID."-";
				$strarrayjson = json_encode($strarraynew);
				
				if(isset($checkforform[0])){
					//form found we can skip.
					$resultarray[$x]['formfound']='yes';
					$resultarray[$x]['ack'] = 'skip';
					$resultarray[$x]['ackmsg'] = 'Already done.';
				} else {
					$totalfoundtoinsert++;
					$resultarray[$x]['formfound']='no';
					$timenow = time();
					//go ahead and add it here, since not found.
					$data = array( 
					'title' => $resultarray[$x]['post_title'],
					'reviewlistpageid' => $resultarray[$x]['customfield'],
					'page_id' => $resultarray[$x]['customfield'],
					'site_type' => "$rtype",
					'created_time_stamp' => "$timenow",
					'url' => $resultarray[$x]['customfield'],
					'cron' => "$fcron",
					'blocks' => "10",
					'last_name' => "full",
					'profile_img' => "no",
					'posts' => "$strarrayjson",
					'sortoption' => "$fwhich",
					'langcode' => "$flangcode",
					'crawlserver' => "local",
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
					); 
					$insertrow = $wpdb->insert( $table_name, $data, $format );
					$resultarray[$x]['dl'] = '';
					if(!$insertrow){
						//error
						$resultarray[$x]['insert_id']='';
						$resultarray[$x]['ack']='error';
						$resultarray[$x]['ackmsg']='Unable to insert in to database.';
					} else {
						$insertid = $wpdb->insert_id;
						$resultarray[$x]['insert_id']=$wpdb->insert_id;
						$resultarray[$x]['ack']='success';
						$resultarray[$x]['ackmsg']='Review Source inserted in to database. Check Get Reviews tab.';
						
						//check to see if we need to download some reviews here if plusdownload is set.
						
						if($plusdownload==1){
							$newjobresults = $this->wprp_getapps_getrevs_ajax_go($insertid,1,100,0,'','no');
							$resultarray[$x]['dl'] = $newjobresults;
							if($newjobresults['ack']=='error'){
								$resultarray[$x]['dl']['ack']='error';
								$resultarray[$x]['dl']['ackmsg']=$newjobresults['ackmsg'];
							} else {
								$totalreviewsinserted = $totalreviewsinserted + $newjobresults['numinserted'];
							}
						}
						
					}
				}
				
			
			}  
		  $x++;
		}

		$resultarray['totalreviewsinserted'] =$totalreviewsinserted;
		
		return $resultarray;
		die();

	}




	/**
	 * used to run language translator from Google api, ran on Settings/Notification/Tools page via ajax
	 * @access  public
	 * @since   11.8.8
	 * @return  void
	 */	
	//called from settings js page 
	public function wprevpro_run_language_translate_ajax(){
		check_ajax_referer('randomnoncestring', 'wpfb_nonce');
		$apikey = sanitize_text_field($_POST['apikey']);
		$targlangs = sanitize_text_field($_POST['targlang']);
		
		$lastrevid = sanitize_text_field($_POST['jslastrevid']);
		$lastrevid = intval($lastrevid);

		$resultarray['key'] = $apikey;

		$resultarray = $this->wprevpro_run_language_translate_ajax_go($apikey, $targlangs, 1, $lastrevid);
		
		//use resultarray to communicate back to javascript
		echo json_encode($resultarray);
		die();
	}
	
	public function wprevpro_run_language_translate_ajax_go($apikey,$targlangs, $limit , $lastrevid){
		global $wpdb;
		$table_name = $wpdb->prefix . 'wpfb_reviews';
		$resultarray['apikey']=$apikey;
		$resultarray['targlangs']=$targlangs;	//this could be comma seperate list. explode to array and then loop.
		$resultarray['loop']='';
		$targetlangarray = explode(",",$targlangs);
		$resultarray['targetlangarray']=$targetlangarray;
		$resultarray['temp_id']='';
		$resultarray['ack']='';

		$savelanguagetranslatorjson = get_option('wprev_languagetranslator');
		$savelanguagetranslatorarray = json_decode($savelanguagetranslatorjson,true);
		$apikey = $savelanguagetranslatorarray['lang_api_key'];
		
		$reviewidstotranslate=Array();
		
		if($apikey!=''){
		
		//doing this for each language.
		$tlnum = 0;
		foreach ($targetlangarray as $targetlang) {

			$resultarray[$tlnum]['targetlang']=$targetlang;
			
			$totaltranslateded = 0;
			
			jumpto:

			if($lastrevid>0){
				//we are going to find the exact
				$query = "SELECT * FROM $table_name WHERE id < $lastrevid AND (translateparent = '') AND (review_text != '' OR review_title != '') ORDER BY id DESC LIMIT $limit";
			} else {
				$query ="SELECT * FROM $table_name WHERE (translateparent = '') AND (review_text != '' OR review_title != '') ORDER BY id DESC LIMIT $limit";
			}
			$totaluntranslatedreviews = $wpdb->get_results( $query ,ARRAY_A);
			
			//now loop these reviews to see if a translated review exists. may need to add break in this loop if we timeout.
			for ($x = 0; $x < count($totaluntranslatedreviews); $x++) {
			//for ($x = 0; $x < 2; $x++) {
				
				//check db to see if translateparent = id and the language code is in the targetlangarray
				$temp_id=intval($totaluntranslatedreviews[$x]['id']);
				$review_text = $totaluntranslatedreviews[$x]['review_text'];
				$review_title = $totaluntranslatedreviews[$x]['review_title'];
				$currentlanguage = $totaluntranslatedreviews[$x]['language_code'];
				$translated_review_title=''; 
				$translated_review_text='';
				$detectedsourcelang_review_text='';	
				$detectedsourcelang_review_title='';
				$detectedsourcelangcode='';
				
				$resultarray['temp_id']=$temp_id;
				
				//echo "SELECT * FROM $table_name WHERE translateparent = $temp_id AND language_code = $targetlang";
				
				$checkfortranslatedversion = $wpdb->get_row( "SELECT * FROM $table_name WHERE translateparent = $temp_id AND language_code = '$targetlang'", ARRAY_A );
				
				if ( null !== $checkfortranslatedversion ) {
				  //has already been translated
				  //we can jump back up and check the next one.
				  $lastrevid = $temp_id;
				  goto jumpto;
				} else if($currentlanguage!=$targetlang) {
				  //needs to be translated.
				  if($review_text!=''){
					  $resultarray[$tlnum][$x]['detect_rtext']['review_text']=$review_text;
					  $url = "https://translation.googleapis.com/language/translate/v2?key=".$apikey."&q=".urlencode($review_text)."&target=".$targetlang;
					  
					  $data = wp_remote_get( $url );
						if ( is_wp_error( $data ) ) 
						{
							$resultarray[$tlnum][$x]['detect_rtext']['error_message'] 	= $data->get_error_message();
							$resultarray[$tlnum][$x]['detect_rtext']['status'] 		= $data->get_error_code();
						}
						$callresultarray = json_decode( $data['body'], true );
						$resultarray[$tlnum][$x]['detect_rtext']['decoderresult']	= $callresultarray;
						//check for Error
						if(isset($callresultarray['error'])){
							$resultarray['ack']='error';
							$resultarray['ackmsg']="Error ".$callresultarray['error']['code']." - ".$callresultarray['error']['errors'][0]['message'];
						}
						//check result
						if(isset($callresultarray['data']['translations'][0]['translatedText'])){
							$translated_review_text = $callresultarray['data']['translations'][0]['translatedText'];
							$detectedsourcelang_review_text = $callresultarray['data']['translations'][0]['detectedSourceLanguage'];
						}
				  }
				  if($review_title!=''){
					  $resultarray[$tlnum][$x]['detect_rtitle']['review_title']=$review_title;
					  $url = "https://translation.googleapis.com/language/translate/v2?key=".$apikey."&q=".urlencode($review_title)."&target=".$targetlang;
					  
					  $data = wp_remote_get( $url );
						if ( is_wp_error( $data ) ) 
						{
							$resultarray[$tlnum][$x]['detect_rtitle']['error_message'] 	= $data->get_error_message();
							$resultarray[$tlnum][$x]['detect_rtitle']['status'] 		= $data->get_error_code();
						}
						$callresultarray = json_decode( $data['body'], true );
						$resultarray[$tlnum][$x]['detect_rtitle']['decoderresult']	= $callresultarray;
						//check for Error
						if(isset($callresultarray['error'])){
							$resultarray['ack']='error';
							$resultarray['ackmsg']="Error ".$callresultarray['error']['code']." - ".$callresultarray['error']['errors'][0]['message'];
						}
						//check result
						if(isset($callresultarray['data']['translations'][0]['translatedText'])){
							$translated_review_title = $callresultarray['data']['translations'][0]['translatedText'];
							$detectedsourcelang_review_title = $callresultarray['data']['translations'][0]['detectedSourceLanguage'];
						}
				  }
				  if($detectedsourcelang_review_text!=''){
					  $detectedsourcelangcode = $detectedsourcelang_review_text;
				  } else if($detectedsourcelang_review_text!=''){
					  $detectedsourcelangcode = $detectedsourcelang_review_title;
				  }
				  //see if we have some results.
				  if(($translated_review_title!='' || $translated_review_text!='') && $targetlang!=$detectedsourcelangcode){
							//add this new review to the db
							$stat = [
								'reviewer_name' => $totaluntranslatedreviews[$x]['reviewer_name'],
								'reviewer_id' => $totaluntranslatedreviews[$x]['reviewer_id'],
								'pagename' => $totaluntranslatedreviews[$x]['pagename'],
								'pageid' => $totaluntranslatedreviews[$x]['pageid'],
								'userpic' => $totaluntranslatedreviews[$x]['userpic'],
								'rating' => $totaluntranslatedreviews[$x]['rating'],
								'recommendation_type' => $totaluntranslatedreviews[$x]['recommendation_type'],
								'created_time' => $totaluntranslatedreviews[$x]['created_time'],
								'created_time_stamp' => $totaluntranslatedreviews[$x]['created_time_stamp'],
								'review_text' => $translated_review_text,
								'hide' => $totaluntranslatedreviews[$x]['hide'],
								'review_length' => $totaluntranslatedreviews[$x]['review_length'],
								'review_length_char' => $totaluntranslatedreviews[$x]['review_length_char'],
								'type' => $totaluntranslatedreviews[$x]['type'],
								'review_title' => $translated_review_title ,
								'from_url' => $totaluntranslatedreviews[$x]['from_url'],
								'from_url_review' => $totaluntranslatedreviews[$x]['from_url_review'],
								'reviewer_email' => $totaluntranslatedreviews[$x]['reviewer_email'],
								'company_title' => $totaluntranslatedreviews[$x]['company_title'],
								'company_url' => $totaluntranslatedreviews[$x]['company_url'],
								'company_name' => $totaluntranslatedreviews[$x]['company_name'],
								'location' => $totaluntranslatedreviews[$x]['location'],
								'verified_order' => $totaluntranslatedreviews[$x]['verified_order'],
								'language_code' => $targetlang,
								'unique_id' => $totaluntranslatedreviews[$x]['unique_id'],
								'meta_data' => $totaluntranslatedreviews[$x]['meta_data'],
								'categories' => $totaluntranslatedreviews[$x]['categories'],
								'posts' => $totaluntranslatedreviews[$x]['posts'],
								'owner_response' => $totaluntranslatedreviews[$x]['owner_response'],
								'tags' => $totaluntranslatedreviews[$x]['tags'],
								'mediaurlsarrayjson' => $totaluntranslatedreviews[$x]['mediaurlsarrayjson'],
								'mediathumburlsarrayjson' => $totaluntranslatedreviews[$x]['mediathumburlsarrayjson'],
								'translateparent' => $temp_id,
							];
							$insertnum = $wpdb->insert( $table_name, $stat );
							$stat['id']=$wpdb->insert_id;
							$this->my_print_db_error();
							$totaltranslateded = $totaltranslateded + 1;
							
							
				  }
				  //check source review for lang code, if it doesn't have it then update it.
					if($totaluntranslatedreviews[$x]['language_code']=='' && $detectedsourcelangcode!=''){
						$data = array('language_code' => "$detectedsourcelangcode");
						$format = array('%s'); 
						$updatetempquery = $wpdb->update($table_name, $data, array( 'id' => $temp_id ), $format, array( '%d' ));
					}
				 
				  
				}
				//usleep(100000);
			}
			$resultarray['totaltranslateded']=$totaltranslateded;
			$tlnum = $tlnum+1;
		}
		}

		return $resultarray;
		die();

	}
	
	
	/**
	 * used to run language detector from Yandex api, ran on Settings/Notification page via ajax
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */	
	//called from settings js page 
	public function wprevpro_run_language_detect_ajax(){
		check_ajax_referer('randomnoncestring', 'wpfb_nonce');
		$apikey = sanitize_text_field($_POST['apikey']);
		$page = sanitize_text_field($_POST['dbpage']);
		$page = intval($page);
		
		$lastrevid = sanitize_text_field($_POST['jslastrevid']);
		$lastrevid = intval($lastrevid);

		$resultarray['key'] = $apikey;

		$resultarray = $this->wprevpro_run_language_detect_ajax_go($apikey, $page, 1, $lastrevid);
		
		//use resultarray to communicate back to javascript
		echo json_encode($resultarray);
		die();
	}
	public function wprevpro_run_language_detect_ajax_go($apikey, $page = '0', $limit=1 , $lastrevid=0){
		
		//search db and find total reviews that do not have the language set.
		global $wpdb;
		$table_name = $wpdb->prefix . 'wpfb_reviews';
		
		$totalunsetreviews = $wpdb->get_results( "SELECT * FROM $table_name WHERE (language_code = '' OR language_code = '--') AND (review_text != '' OR review_title != '')" ,ARRAY_A);
		
		//$offset = "OFFSET ".$page;
		
		if($lastrevid>0){
			//we are going to find the exact
			$query = "SELECT * FROM $table_name WHERE id < $lastrevid AND (language_code = '' OR language_code = '--') AND (review_text != '' OR review_title != '') ORDER BY id DESC LIMIT $limit";
		} else {
			$query = "SELECT * FROM $table_name WHERE (language_code = '' OR language_code = '--') AND (review_text != '' OR review_title != '') ORDER BY id DESC LIMIT $limit";
		}
		
		$reviews = $wpdb->get_results($query,ARRAY_A );
		//$wpdb->last_query();
		//$wpdb->show_errors();
		//$wpdb->print_error();
		//die();
		$resultarray['page']=$page;
		$resultarray['query']=$query;
		$resultarray['totalcount']=count($totalunsetreviews);
		$resultarray['apikey']=$apikey;
		$resultarray['reviews']=$reviews;
		$resultarray['lastrevid']= 0;
		
		//$resultarray['query']=$query;

		//loop through first 20 or less
		//$loopnum=$limit;
		//if($resultarray['totalcount']<=$loopnum){
		//	$loopnum = $resultarray['totalcount'];
		//}
		
		if(count($reviews)>0){
			for ($x = 0; $x < count($reviews); $x++) {
				$resultarray['lastrevid']=intval($reviews[$x]['id']);

				$stringtodetect = '';
				//first try to grab part of review_text
				if(strlen($reviews[$x]['review_text']) > 50){
					$stringtodetect = substr($reviews[$x]['review_text'],0,120);
				} else {
					//description not long enough, grab title if set
					if($reviews[$x]['review_title']!=''){
						$stringtodetect =$reviews[$x]['review_title'];
					} else {
						//use short description as last resort
						$stringtodetect =$reviews[$x]['review_text'];
					}
				}
				$resultarray['detect'][$x]['strdetect']=$stringtodetect;
				
				$resultarray['detect'][$x]['decoderresult']['error']='';
				
				if($stringtodetect!=""){
					$rid='';
					//now call yandex api
					//https://translate.yandex.net/api/v1.5/tr.json/detect?key=<API key>&text=<text>&[hint=<list of probable text languages>]
					$url = "https://translate.yandex.net/api/v1.5/tr.json/detect?key=".$apikey."&text=".urlencode($stringtodetect)."";

					//switching services
					//https://ws.detectlanguage.com/0.2/detect?key=demo&q=buenos+dias+se%C3%B1or
					$url = "https://ws.detectlanguage.com/0.2/detect?key=".$apikey."&q=".urlencode($stringtodetect).""; 

					$data = wp_remote_get( $url );
					if ( is_wp_error( $data ) ) 
					{
						$resultarray['detect'][$x]['error_message'] 	= $data->get_error_message();
						$resultarray['detect'][$x]['status'] 		= $data->get_error_code();
					}
					$resultarray['detect'][$x]['decoderresult']	= json_decode( $data['body'], true );
					
				
					//update the db with the language if we have success code, if not then make a note of it and display to user
					$templang='';
					
					if(isset($resultarray['detect'][$x]['decoderresult']['data']['detections'][0])){
						$resultarray['detect'][$x]['decoderresult']['code']=200;
						$templang=$resultarray['detect'][$x]['decoderresult']['data']['detections'][0]['language'];
						if($templang==''){
							$templang='--';
						}
							$rid = $reviews[$x]['id'];
							$data = array( 
								'language_code' => "$templang"
								);
							$format = array( 
									'%s'
								); 
							$updatetempquery = $wpdb->update($table_name, $data, array( 'id' => $rid ), $format, array( '%d' ));
							if($updatetempquery>0){
								//success
								$resultarray['detect'][$x]['decoderresult']['dbupdated']='yes';
							} else {
								$resultarray['detect'][$x]['decoderresult']['dbupdated']='error';
							}
						
					}
					//print_r($resultarray['detect'][$x]['decoderresult']	);
					//die();
					
					// wait for .5 seconds
					//usleep(1000000);
				}
			}
		}
				
		return $resultarray;

	}
	
	/**
	 * used to get overall chart data via ajax on analytics page 
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */	
	//called from settings js page 
	public function wppro_get_overall_chart_data(){
		check_ajax_referer('randomnoncestring', 'wpfb_nonce');
		$startdate = sanitize_text_field($_POST['startd']);
		$enddate = sanitize_text_field($_POST['endd']);
		
		$rtypearray = sanitize_text_field(stripslashes($_POST['stypes']));
		$rtypearray = json_decode($rtypearray,true);

		$rpagearray = sanitize_text_field(stripslashes($_POST['slocations']));
		$rpagearray = json_decode($rpagearray,true);
		
		$utstartdate=strtotime($startdate);
		$utenddate=strtotime($enddate);
		
		$filtertext = sanitize_text_field($_POST['filtertext']);
		
		$rpagefilter ='';
		$rtypefilter ='';
		//add location pageid search if set
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

				$rpagefilter = $rpagefilter.")";
			}
		}
		
		//add type search if set
		if(is_array($rtypearray)){
			$rtypearray = array_filter($rtypearray);
			$rtypearray = array_values($rtypearray);
			if(count($rtypearray)>0){
				for ($x = 0; $x < count($rtypearray); $x++) {
					if($x==0){
						$rtypefilter = "AND (type = '".$rtypearray[$x]."'";
					} else {
						$rtypefilter = $rtypefilter." OR type = '".$rtypearray[$x]."'";
					}
				}
				$rtypefilter = $rtypefilter.")";
			}
		}
			
		
		//query db and return reviews
		global $wpdb;
		$table_name = $wpdb->prefix . 'wpfb_reviews';
		
		//if filtertext set then use different query
		if($filtertext!=""){
			$querystring = "SELECT * FROM ".$table_name." WHERE (reviewer_name LIKE '%".$filtertext."%' or review_text LIKE '%".$filtertext."%') AND (rating > 0 OR recommendation_type != '') AND (created_time_stamp >= $utstartdate AND created_time_stamp <= $utenddate) ".$rtypefilter." ".$rpagefilter." ORDER BY created_time_stamp ASC";
		} else {
			$querystring = "SELECT * FROM $table_name WHERE (rating > 0 OR recommendation_type != '') AND (created_time_stamp >= $utstartdate AND created_time_stamp <= $utenddate) ".$rtypefilter." ".$rpagefilter." ORDER BY created_time_stamp ASC";
		}

		$totalreviews = $wpdb->get_results($querystring ,ARRAY_A);
		
		$resultarray['querystring']=$querystring;
		
		//$totalreviews = $wpdb->get_results( "SELECT * FROM $table_name WHERE rating > 0 OR recommendation_type != '' ORDER BY created_time_stamp ASC" ,ARRAY_A);
		
		//print_r($totalreviews);
		
		//loop array of all reviews and build results arrays
		$resultarray['ratingvals']=Array();
		$positivewords = '';
		$negativewords = '';
		for ($x = 0; $x < count($totalreviews); $x++) {
			$temptext=$totalreviews[$x]['review_text'];
			if (extension_loaded('mbstring')) {
				$review_length_char = mb_strlen($temptext);
			} else {
				$review_length_char = strlen($temptext);
			}
			if($review_length_char>70){
				if (extension_loaded('mbstring')) {
					$temptext=mb_substr($temptext,0,70).'...';
				} else {
					$temptext=substr($temptext,0,70).'...';
				}
			} else {
				if (extension_loaded('mbstring')) {
					$temptext=mb_substr($temptext,0,70);
				} else {
					$temptext=substr($temptext,0,70);
				}
			}
			$temptext=strip_tags($temptext);
			$temptime = $totalreviews[$x]['created_time_stamp'];
			$tempname = $totalreviews[$x]['reviewer_name'].' - '.date('M j, Y',$temptime);
			if($totalreviews[$x]['pagename']!=''){
				$temppagename =  $totalreviews[$x]['type']." - ".$totalreviews[$x]['pagename'];
			} else {
				$temppagename =  $totalreviews[$x]['type']." - ".$totalreviews[$x]['pageid'];
			}
			
			if($totalreviews[$x]['review_title']!=''){
				$temptitle = $totalreviews[$x]['review_title'];
				if($temptext!=''){
					$temptextarray=[$temppagename,$tempname,$temptitle,$temptext];
				} else {
					$temptextarray=[$temppagename,$tempname,$temptitle];
				}
			} else {
				if($temptext!=''){
					$temptextarray=[$temppagename,$tempname,$temptext];
				} else {
					$temptextarray=[$temppagename,$tempname];
				}
			}
//print_r($temptext);			
			$resultarray['labelvals'][]=$temptextarray;
			//fix for FB
			if($totalreviews[$x]['recommendation_type']=='positive'){
				$totalreviews[$x]['rating']=5;
			} else if($totalreviews[$x]['recommendation_type']=='negative') {
				$totalreviews[$x]['rating']=2;
			}

			//$resultarray['ratingvals'][]= array("x"=>$x, "y"=>intval($totalreviews[$x]['rating']));
			$resultarray['ratingvals'][]=(int)$totalreviews[$x]['rating'];
			//pass review id so we can pull info from db
			
			$resultarray['reviewdata'][]=$totalreviews[$x];
			
			if($totalreviews[$x]['rating']>0){
				$tempnum=(int)$totalreviews[$x]['rating'];
			} else if($totalreviews[$x]['recommendation_type']=='positive'){
				$tempnum=5;
			} else if($totalreviews[$x]['recommendation_type']=='negative'){
				$tempnum=2;
			}
			$temptype = $totalreviews[$x]['type'];
			$typeratingsarray[$temptype][]=$tempnum;
			$ratingsarray[]=$tempnum;
			
			//create positive and negative word string so we can find most common
			if($tempnum>3){
				$positivewords = $positivewords." ".$totalreviews[$x]['review_text'];
			} else if($tempnum<=3){
				$negativewords = $negativewords." ".$totalreviews[$x]['review_text'];
			}
			
		}
		if(isset($typeratingsarray) && is_array($typeratingsarray)){
		$typeratingsarray = array_filter($typeratingsarray);
		$resultarray['ratingtypenumvals'] = $typeratingsarray;
		} else {
			$resultarray['ratingtypenumvals'] = '';
		}
		
		//now we need to find number of each rating
		$temprating = $this->wprp_get_temprating($ratingsarray);
		if(isset($temprating)){
			$tempratingarray['numr1'] = array_sum($temprating[1]);
			$tempratingarray['numr2'] = array_sum($temprating[2]);
			$tempratingarray['numr3'] = array_sum($temprating[3]);
			$tempratingarray['numr4'] = array_sum($temprating[4]);
			$tempratingarray['numr5'] = array_sum($temprating[5]);
		} else {
			$tempratingarray['numr1'] = 0;
			$tempratingarray['numr2'] = 0;
			$tempratingarray['numr3'] = 0;
			$tempratingarray['numr4'] = 0;
			$tempratingarray['numr5'] = 0;
		}
		$resultarray['ratingnumvals'] = $tempratingarray;
		
		//return avg rating
		$resultarray['avgrating'] = '';
		if(isset($ratingsarray) && is_array($ratingsarray)) {
			$ratingsarray = array_filter($ratingsarray);
			$resultarray['avgrating'] = round(array_sum($ratingsarray)/count($ratingsarray),1);
		}
		
		//find arrays of positive and negative words
		$stopwordsarray=["here","very","great","good","their","there","would","which","what","were","when","that", "with", "have", "this", "will", "your", "from", "they", "know", "want", "been", "because", "once"];
		$resultarray['poswordarray'] = $this->mostFrequentWords($positivewords,$stopwordsarray);
		$resultarray['negwordarray'] = $this->mostFrequentWords($negativewords,$stopwordsarray);
		
		//$resultarray['ratingvals'] = [12, 19, 3, 5, 2, 3];
		//$resultarray['labelvals'] = ['January', 'February', 'March', 'April', 'May', 'June'];
		echo json_encode($resultarray);
		die();
	
	}
	
	//function to find most frequent words in a string
	public function mostFrequentWords($string, $stopWords = [], $limit = 15) {
		$string = preg_replace('/\b[A-Za-z0-9]{1,3}\b\s?/i', '', $string);	//remove short words
		$words = array_count_values(array_diff(str_word_count(strtolower($string), 1), $stopWords));
		arsort($words); // Sort based on frequency
		return array_slice($words, 0, $limit);
	}
	
		
	/**
	 * used to download reviews from the wp_pro-get_apps pages.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */	
	public function wprp_getapps_getrevs_ajax(){
		check_ajax_referer('randomnoncestring', 'wpfb_nonce');
		$fid = sanitize_text_field($_POST['fid']);
		$pagenum = sanitize_text_field($_POST['pnum']);
		$perpage = sanitize_text_field($_POST['perp']);
		$revsinsertedsofar = sanitize_text_field($_POST['totalrevsin']);
		$nextpageurl = sanitize_text_field($_POST['npagerul']);
		$resultarrayfinal = $this->wprp_getapps_getrevs_ajax_go($fid,$pagenum,$perpage,$revsinsertedsofar,$nextpageurl,"no");
		//use resultarray to communicate back to javascript
		//echo $resultarrayfinal['numinserted'];
		//echo $resultarrayfinal['ack'];
		//echo $resultarrayfinal['msg'];
		//$resultarrayfinal['ack']='error';
		echo json_encode($resultarrayfinal);
		die();
	}
	public function wprp_getapps_getrevs_ajax_go($fid,$pagenum=1,$perpage=100,$revsinsertedsofar=0,$nextpageurl='',$iscron='no'){
	set_time_limit(120);
	
		global $wpdb;
				
		$frlicenseid = get_option( 'wprev_fr_siteid' );
		$fid =intval($fid );
		$pagenum =intval($pagenum );
		$perpage =intval($perpage );	//currently not being used
		$revsinsertedsofar =intval($revsinsertedsofar );
	
		
		$table_name = $wpdb->prefix . 'wpfb_getapps_forms';
		$reviewformdetails = $wpdb->get_row( "SELECT * FROM $table_name WHERE id = $fid" );
		$pagename = $reviewformdetails->title;
		$pageid = str_replace(" ","",$pagename)."_".$reviewformdetails->id;
		$pageid = str_replace("'","",$pageid);
		$pageid = str_replace('"',"",$pageid);
		$pageid = preg_replace('/[^A-Za-z0-9\-]/', '', $pageid);
		$sitetype = $reviewformdetails->site_type;
		$listedurl= $reviewformdetails->url;
		$tempcats='';
		if(isset($reviewformdetails->categories)){
		$tempcats=$reviewformdetails->categories;
		}
		$tempposts='';
		if(isset($reviewformdetails->posts)){
		$tempposts=$reviewformdetails->posts;
		}
		$blockstoinsert = intval($reviewformdetails->blocks);
		if($blockstoinsert<1){
			if($sitetype=='SocialClimb'){
				$blockstoinsert=5000;
			} else {
				$blockstoinsert=10;
			}
		}
		if($sitetype=='Realtor' ){
			$blockstoinsert=5000;
		}

		//print_r($reviewformdetails);
		//this is currently used for Google api and Experience api to use ids. Not to be confused with pageid in review list table.
		$savedpageid ='';
		if(isset($reviewformdetails->page_id)){
			$savedpageid = $reviewformdetails->page_id;
		}
		
		//starting in version 11.4.9 we are now pulling pageid from when it was saved when form was saved.
		$reviewlistpageid = '';
		//echo 'dbpageid'.$reviewformdetails->reviewlistpageid;
		if(isset($reviewformdetails->reviewlistpageid) && $reviewformdetails->reviewlistpageid!=''){
			$pageid = $reviewformdetails->reviewlistpageid;
		}
		
		//for google sort
		$sortoption ='';
		if(isset($reviewformdetails->sortoption)){
		$sortoption = $reviewformdetails->sortoption;
		}
		
		//for google places lang]
		$langcode ='';
		if(isset($reviewformdetails->langcode)){
		$langcode = $reviewformdetails->langcode;
		}
		//for crawlserver
		$crawlserver ='';
		if(isset($reviewformdetails->crawlserver)){
		$crawlserver = $reviewformdetails->crawlserver;
		}
		
		//for recommendations, save star or not.
		$rectostar = '';
		if(isset($reviewformdetails->rectostar)){
		$rectostar = $reviewformdetails->rectostar;
		}
			
		//make a call to server, only if we are not out of calls and this site has passed check.
		//$options['ack'], $options['totalreviewbank'], $options['totalreviewcreditsused']
		$resultarray['ack']='success';
		$resultarray['msg']='';
		$resultarray['numinserted']='';
		$resultarray['numreturned']='';
		$resultarray['scraperesult']='';
		$resultarray['pagenum']=$pagenum;
		$totalrevsfromsource='';
		$avgrevsfromsource='';
		
		//update last ran on
		$table_name_form = $wpdb->prefix . 'wpfb_getapps_forms';
		$clr = time();
		$cfid = $reviewformdetails->id;
		$data = array('last_ran' => "{$clr}");
		$format = array( '%s' );
		$updatetempquery = $wpdb->update($table_name_form,$data,array('id' => $cfid),$format,array( '%d' ));

		//call function in the class_getapps_revs.php file.
		require_once("class_getapps_revs.php");
		$getappsclass = new GetAppsReviews_Functions($this->_token,$this->version);
		
		if($sitetype=='SourceForge'){
			$getreviewsarray= $getappsclass->wprp_getapps_getrevs_page_sourceforge($sitetype,$listedurl,$pagenum,$perpage,$savedpageid,$sortoption,$fid);
		} else if($sitetype=='WordPress'){
			$getreviewsarray= $getappsclass->wprp_getapps_getrevs_page_wordpress($sitetype,$listedurl,$pagenum,$perpage,$savedpageid,$sortoption,$fid,$blockstoinsert);
		} else if($sitetype=='Reviews.io'){
			$getreviewsarray= $getappsclass->wprp_getapps_getrevs_page_reviewsio($sitetype,$listedurl,$pagenum,$perpage,$savedpageid,$sortoption,$fid,$blockstoinsert,$nextpageurl);
		} else if($sitetype=='TripAdvisor'){
			if($crawlserver=='local'){
				$getreviewsarray= $getappsclass->wprp_getapps_getrevs_page_tripadvisorlocal($sitetype,$listedurl,$pagenum,$perpage,$savedpageid,$sortoption,$fid,$blockstoinsert,$nextpageurl,$iscron,$crawlserver);
			} else {
				$getreviewsarray= $getappsclass->wprp_getapps_getrevs_page_tripadvisor($sitetype,$listedurl,$pagenum,$perpage,$savedpageid,$sortoption,$fid,$blockstoinsert,$nextpageurl,$iscron);
			}
		} else if($sitetype=='Google'){
			//if this is a cron job then limit $blockstoinsert to 10.
			if($iscron=='yes'){$blockstoinsert=10;}
			$getreviewsarray= $getappsclass->wprp_getapps_getrevs_page_google($sitetype,$listedurl,$pagenum,$perpage,$savedpageid,$sortoption,$fid,$blockstoinsert);
		} else if($sitetype=='GuildQuality'){
			$getreviewsarray= $getappsclass->wprp_getapps_getrevs_page_guildquality($sitetype,$listedurl,$pagenum,$perpage,$savedpageid,$sortoption,$fid,$blockstoinsert);
		} else if($sitetype=='Airbnb'){
			$getreviewsarray= $getappsclass->wprp_getapps_getrevs_page_airbnb($sitetype,$listedurl,$pagenum,$perpage,$savedpageid,$sortoption,$fid,$blockstoinsert,$nextpageurl);
		} else if($sitetype=='VRBO'){
			$getreviewsarray= $getappsclass->wprp_getapps_getrevs_page_vrbo($sitetype,$listedurl,$pagenum,$perpage,$savedpageid,$sortoption,$fid,$blockstoinsert,$nextpageurl);
		} else if($sitetype=='Yelp'){
			if($crawlserver=='remote'){
				$getreviewsarray= $getappsclass->wprp_getapps_getrevs_page_yelp($sitetype,$listedurl,$pagenum,$perpage,$savedpageid,$sortoption,$fid,$blockstoinsert,$nextpageurl);
			} else {
				$getreviewsarray= $getappsclass->wprp_getapps_getrevs_page_yelp_local($sitetype,$listedurl,$pagenum,$perpage,$savedpageid,$sortoption,$fid,$blockstoinsert,$nextpageurl);
			}
		} else if($sitetype=='Birdeye'){
			$getreviewsarray= $getappsclass->wprp_getapps_getrevs_page_birdeye($sitetype,$listedurl,$pagenum,$perpage,$savedpageid,$sortoption,$fid,$blockstoinsert,$nextpageurl);
		} else if($sitetype=='SocialClimb'){
			$getreviewsarray= $getappsclass->wprp_getapps_getrevs_page_socialclimb($sitetype,$listedurl,$pagenum,$perpage,$savedpageid,$sortoption,$fid,$blockstoinsert,$nextpageurl);
		}  else if($sitetype=='Nextdoor'){
			$getreviewsarray= $getappsclass->wprp_getapps_getrevs_page_nextdoor($sitetype,$listedurl,$pagenum,$perpage,$savedpageid,$sortoption,$fid,$blockstoinsert,$nextpageurl,$rectostar);
		} else if($sitetype=='Zillow'){
			if($crawlserver=='remote'){
				$getreviewsarray= $getappsclass->wprp_getapps_getrevs_page_multi_remote($sitetype,$listedurl,$pagenum,$perpage,$savedpageid,$sortoption,$fid,$blockstoinsert,$nextpageurl);
			} else {
				$getreviewsarray= $getappsclass->wprp_getapps_getrevs_page_zillow($sitetype,$listedurl,$pagenum,$perpage,$savedpageid,$sortoption,$fid,$blockstoinsert,$nextpageurl);
			}
		} else if($sitetype=='Realtor'){
			if($crawlserver=='remote'){
				$getreviewsarray= $getappsclass->wprp_getapps_getrevs_page_multi_remote($sitetype,$listedurl,$pagenum,$perpage,$savedpageid,$sortoption,$fid,$blockstoinsert,$nextpageurl);
			} else {
				$getreviewsarray= $getappsclass->wprp_getapps_getrevs_page_realtor($sitetype,$listedurl,$pagenum,$perpage,$savedpageid,$sortoption,$fid,$blockstoinsert,$nextpageurl);
			}
		} else if($sitetype=='Yotpo'){
			$getreviewsarray= $getappsclass->wprp_getapps_getrevs_page_yotpo($sitetype,$listedurl,$pagenum,$perpage,$savedpageid,$sortoption,$fid,$blockstoinsert,$nextpageurl);
		}  else if($sitetype=='AngiesList'){
			$getreviewsarray= $getappsclass->wprp_getapps_getrevs_page_angi($sitetype,$listedurl,$pagenum,$perpage,$savedpageid,$sortoption,$fid,$blockstoinsert,$nextpageurl);
		} else if($sitetype=='Google-Places-API'){
			$getreviewsarray= $getappsclass->wprp_getapps_getrevs_page_googleplacesapi($sitetype,$listedurl,$pagenum,$perpage,$savedpageid,$sortoption,$fid,$blockstoinsert,$nextpageurl,$langcode);
		} else if($sitetype=='Fresha'){
			$getreviewsarray= $getappsclass->wprp_getapps_getrevs_page_fresha($sitetype,$listedurl,$pagenum,$perpage,$savedpageid,$sortoption,$fid,$blockstoinsert,$nextpageurl);
		} else if($sitetype=='CreativeMarket'){
			$getreviewsarray= $getappsclass->wprp_getapps_getrevs_page_creativemarket($sitetype,$listedurl,$pagenum,$perpage,$savedpageid,$sortoption,$fid,$blockstoinsert,$nextpageurl);
		} else {
			$getreviewsarray= $this->wprp_getapps_getrevs_page($sitetype,$listedurl,$pagenum,$perpage,$savedpageid,$sortoption,$fid,$rectostar);
		}
		
		//print_r($getreviewsarray);
//die();		
		
		//switch sitetype to just Google if this is Google-Places-API
		$sitetypeorg='';
		if($sitetype=="Google-Places-API"){
			$sitetypeorg="Google-Places-API";
			$sitetype="Google";
		}
		//print_r($getreviewsarray);
		//die();
		//capture next page of reviews url and send back if needed.
		$resultarray['forceloop']='';
		if(isset($getreviewsarray['forceloop'])){
			$resultarray['forceloop']=$getreviewsarray['forceloop'];
		}
		
		$resultarray['nextpageurl']='';
		if(isset($getreviewsarray['nextpageurl'])){
			$resultarray['nextpageurl']=$getreviewsarray['nextpageurl'];
		}
		
		$resultarray['stoploop']='';
		if(isset($getreviewsarray['stoploop'])){
			$resultarray['stoploop']=$getreviewsarray['stoploop'];
		}
		$resultarray['jumpnum']='';
		if(isset($getreviewsarray['jumpnum'])){
			$resultarray['jumpnum']=$getreviewsarray['jumpnum'];
		}
		$resultarray['proxy']='';
		if(isset($getreviewsarray['proxy'])){
			$resultarray['proxy']=$getreviewsarray['proxy'];
		}
				
		if(isset($getreviewsarray['callurl'])){
			$resultarray['callurl']=$getreviewsarray['callurl'];
		}
		if(isset($getreviewsarray['revarray'])){
			$resultarray['revarray']=$getreviewsarray['revarray'];
		}
		$resultarray['ack']='';
		if(isset($getreviewsarray['ack']) && $getreviewsarray['ack']!='success'){
			$resultarray['ack']=$getreviewsarray['ack'];
		}
		$resultarray['ackmsg']='';
		if(isset($getreviewsarray['ackmsg']) && $getreviewsarray['ackmsg']!=''){
			$resultarray['ackmsg']=$getreviewsarray['ackmsg'];
		}
		
		if(isset($getreviewsarray['total']) && $getreviewsarray['total']>0){
			$totalrevsfromsource=$getreviewsarray['total'];
			$resultarray['total']=$totalrevsfromsource;
		}
		if(isset($getreviewsarray['avg']) && $getreviewsarray['avg']>0){
			$avgrevsfromsource=$getreviewsarray['avg'];
			$resultarray['avg']=preg_replace("/[^0-9\.]/", "",$avgrevsfromsource);
		}
		if(isset($getreviewsarray['ackmsg']) && $getreviewsarray['ackmsg']!=''){
			$resultarray['msg']=$getreviewsarray['ackmsg'];
		}
		
		$reviewsarray='';
		if(isset($getreviewsarray['reviews'])){
		$reviewsarray = $getreviewsarray['reviews'];
		}
		
		//print_r($reviewsarray);
		//die();
		
		$resultarray['numinserted'] = 0;
		
		if(is_array($reviewsarray) && count($reviewsarray)>0){
			
			//slice the array if it is bigger than the blocks, number to download
			if(count($reviewsarray)>$blockstoinsert && $blockstoinsert>0){
				$reviewsarray= array_slice($reviewsarray,0,$blockstoinsert);
			}

			//print_r($reviewsarray);
			//die();
			//echo count($reviewsarray);

			//insert this in to the review database, return success message and count or error
			//add check to see if already in db, skip if it is and end loop
			$table_name = $wpdb->prefix . 'wpfb_reviews';
			$reviews= array();
			if(is_array($reviewsarray) && count($reviewsarray)>0){
				
				foreach($reviewsarray as $item) {
					$reviewindb = 'no';
					
					//print_r($item);
					//die();
					
					$reviewer_name = 'Trusted Customer';
					if(isset($item['reviewer_name']) && $item['reviewer_name']!=''){
						$reviewer_name = trim($item['reviewer_name']);
						$reviewer_name =$this->changelastname($reviewer_name, $reviewformdetails->last_name);
					}
					$review_text = trim($item['review_text']);
					$review_length = str_word_count($review_text);
					if (extension_loaded('mbstring')) {
						$review_length_char = mb_strlen($review_text);
					} else {
						$review_length_char = strlen($review_text);
					}
					if($review_length_char>0 && $review_length<1){
						$review_length = 1;
					}
					$searchname = addslashes($reviewer_name);
					$timestamp = $this->myStrtotime($item['updated']);
					$unixtimestamp = $timestamp;
					$timestamp = date("Y-m-d H:i:s", $timestamp);

					if($sitetype=='FeedbackCompany' || $sitetype=='Reviews.io'){
						$checkrow = $wpdb->get_var( "SELECT id FROM ".$table_name." WHERE reviewer_name = '".$searchname."' AND type = '".$sitetype."' AND created_time_stamp = '".$unixtimestamp."' AND review_length_char = '".$review_length_char."' " );
						
					} else if($sitetypeorg=='Google-Places-API'){

						$templanguagecode = $item['language_code'];
						$checkrow = $wpdb->get_var( "SELECT id FROM ".$table_name." WHERE reviewer_name = '".$searchname."' AND language_code = '".$templanguagecode."' AND type = '".$sitetype."' AND review_length = '".$review_length."' AND review_length_char = '".$review_length_char."' AND created_time_stamp = '".$unixtimestamp."'" );
	
					} else {
						$checkrow = $wpdb->get_var( "SELECT id FROM ".$table_name." WHERE reviewer_name = '".$searchname."' AND type = '".$sitetype."' AND (review_length = '".$review_length."' OR review_length_char = '".$review_length_char."' OR created_time_stamp = '".$unixtimestamp."')" );
					}
					//checkrow2 fix for google reviews with different dates and slightly less or more characters
					if($sitetype=='Google' && $sitetypeorg!='Google-Places-API'){
						$temppageid = trim($pageid);
						$templowerchar = $review_length_char - 10;
						$temphigherchar = $review_length_char + 10;
						$checkrow2 = $wpdb->get_var( "SELECT id FROM ".$table_name." WHERE reviewer_name = '".$searchname."' AND type = '".$sitetype."' AND pageid= '".$temppageid."' AND review_length_char BETWEEN ".$templowerchar." AND ".$temphigherchar." " );
					}
					//another check looking for first 100 characters.
					if( empty( $checkrow ) &&  empty( $checkrow2 ) && $review_length_char>100){
						$short_review_text= preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $review_text);
						$short_review_text = addslashes(substr($short_review_text,0,100));
						$checkrow = $wpdb->get_var( "SELECT id FROM ".$table_name." WHERE type = '".$sitetype."' AND reviewer_name = '".$searchname."' AND review_text LIKE '%".$short_review_text."%' " );
					}
					
					//owner response data
					$owner_response = '';
					if(isset($item['owner_response']) && $item['owner_response']!=''){
						if(is_array($item['owner_response'])){
							$owner_response = json_encode($item['owner_response']);
						} else {
							$owner_response = $item['owner_response'];
						}
					}

					$metadata = '';
					if(isset($item['meta_data'])){
						$metadata = $item['meta_data'];
					}
					if(isset($item['from_url'])){
						$listedurl = $item['from_url'];
					}
					//blank if from qualitelis-survey
					if($sitetype=='Qualitelis'){
						$listedurl = '';
					}
					$from_url_review = '';
					if(isset($item['from_url_review'])){
						$from_url_review = $item['from_url_review'];
					}
					$review_id = '';
					if(isset($item['review_id'])){
						$review_id = trim($item['review_id']);
					}
					$tags = '';
					if(isset($item['tags'])){
						$tags = $item['tags'];
					}
					$mediaurlsarrayjson = '';
					if(isset($item['mediaurlsarrayjson'])){
						$mediaurlsarrayjson = $item['mediaurlsarrayjson'];
					}
					//see if we are default hide to yes from Tools/Settings page
					$hideondownload = get_option( 'wprev_hideondownload', '' );
					$temphide='';
					if($hideondownload=="yes"){
						$temphide = "yes";
					}
					if( empty( $checkrow ) && empty($checkrow2 ) ){
							$reviews[] = [
								'reviewer_name' => $reviewer_name,
								'reviewer_id' => $review_id,
								'pagename' => trim($pagename),
								'pageid' => trim($pageid),
								'userpic' => $item['userpic'],
								'rating' => $item['rating'],
								'recommendation_type' => $item['recommendation_type'],
								'created_time' => $timestamp,
								'created_time_stamp' => $unixtimestamp,
								'review_text' => $review_text,
								'hide' => $temphide,
								'review_length' => $review_length,
								'review_length_char' => $review_length_char,
								'type' => $sitetype,
								'review_title' => trim($item['review_title']),
								'from_url' => trim($listedurl),
								'from_url_review' => trim($from_url_review),
								'reviewer_email' => trim($item['reviewer_email']),
								'company_title' => $item['company_title'],
								'company_url' => $item['company_url'],
								'company_name' => $item['company_name'],
								'location' => $item['location'],
								'verified_order' => '',
								'language_code' => $item['language_code'],
								'unique_id' => '',
								'meta_data' => $metadata,
								'categories' => trim($tempcats),
								'posts' => trim($tempposts),
								'owner_response' => trim($owner_response),
								'tags' => $tags,
								'mediaurlsarrayjson' => $mediaurlsarrayjson,
							];
					} else {
						//end loop since we already have these
						//break;
					}
				}
				$resultarray['numreturned'] = count($reviews);
				
				//insert or update array in to reviews table.
				$totalreviewsinserted=0;
				
				//print_r($reviews);
				//die();
				$resultarray['revsinsertedsofar']=$revsinsertedsofar;
				
				
				if(isset($reviews) && count($reviews)>0){
					foreach ( $reviews as $stat ){
						//make sure we don't go over number to insert
						if($blockstoinsert > $revsinsertedsofar){
							$statobj ='';
							$pictocopy='';
							//print_r($stat);
							$insertnum = $wpdb->insert( $table_name, $stat );
							$stat['id']=$wpdb->insert_id;
							$this->my_print_db_error();
							$totalreviewsinserted = $totalreviewsinserted + $insertnum;
							$revsinsertedsofar = $revsinsertedsofar + $insertnum;
							//if inserted and save avatar local turned on, then try to copy here
							if($stat['id']>0 && $reviewformdetails->profile_img=="yes" && $stat['userpic']!=''){
								$pictocopy=$stat['userpic'];
								$statobj = (object) $stat;
								$this->wprevpro_download_avatar_tolocal($pictocopy,$statobj);
							}
							
						}
					}
					
					//send $reviews array to function to send email if turned on.
					if(count($reviews)>0){
						$sitetypelower = strtolower($sitetype);
						$this->sendnotificationemail($reviews, $sitetypelower);
					}
				}
				$resultarray['numinserted']=$totalreviewsinserted;
				unset($reviews);
			
				//update total and avg for badges.
				//echo "total:".$totalrevsfromsource;
				//echo "avg:".$avgrevsfromsource;
				if(trim($pageid)!=''){
					$temptype = strtolower($sitetype);
					$this->updatetotalavgreviews($temptype, trim($pageid), $avgrevsfromsource, $totalrevsfromsource,trim($pagename));
				}

			}
		
		sleep(2);
		}


		return $resultarray;
		die();

	}

	
	public function wprp_getapps_getrevs_page($type,$listedurl,$pagenum,$perpage,$savedpageid,$nhful,$fid,$rectostar){
		$result['ack']='success';
		//$reviewsarray = array();
		
		if($type=='StyleSeat'){
			//call StyleSeat page here.
			//if this is page 1 then try and scrape the total and avg for the reviews.
			
			if($pagenum==1){
				$result['listedurl']=$listedurl;
				$requestdetails = '{url:"'.$listedurl.'",renderType:"html",overseerScript:\'await page.waitForNavigation({waitUntil:"domcontentloaded"});\',}';
				$request = urlencode($requestdetails);
			
				$tempurlvalue = 'https://phantomjscloud.com/api/browser/v2/ak-2cme5-eqftq-dv73x-nr41t-gkbvs/?request='.$request;
				//echo $tempurlvalue;
				$result['callurl'] =$tempurlvalue;
				
					if (ini_get('allow_url_fopen') == true) {
						$fileurlcontents=file_get_contents($tempurlvalue);
					} else if (function_exists('curl_init')) {
						$fileurlcontents=$this->file_get_contents_curl($tempurlvalue);
					} else {
						$fileurlcontents='<html><body>'.esc_html__('fopen is not allowed on this host.', 'wp-review-slider-pro').'</body></html>';
						$errormsg = $errormsg . '<p style="color: #A00;">'.esc_html__('fopen is not allowed on this host and cURL did not work either. Ask your web host to turn fopen on or fix cURL.', 'wp-review-slider-pro').'</p>';
						$this->errormsg = $errormsg;
						//echo $errormsg;
					}
					
					$matchavg = $this->get_string_between($fileurlcontents, '"ratingValue": "', '"');
					$matchtot = $this->get_string_between($fileurlcontents, '"ratingCount": "', '"');
					sleep(2);
			}
			
			
			if(isset($matchavg) && $matchavg>0){
				$result['avg']=$matchavg;
			}
			if(isset($matchtot) && $matchtot>0){
				$result['total']=$matchtot;
			}
			

			//https://www.styleseat.com/api/v2/providers/1002638/ratings?page=1&exclude_star_only=true
			//api url to actuall get the reviews.
			
			$stripvariableurl = stripslashes($savedpageid);
			$callurl = strtok($stripvariableurl, '?');	//remove all parameters
			
			$callurl = $callurl."?page=".$pagenum ."&exclude_star_only=true";
			
			$result['callurl'] =$callurl;
			
			//echo $callurl;

			//echo $callurl;
			$response = wp_remote_get( $callurl );
 			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				$headers = $response['headers']; // array of http header lines
				$body    = $response['body']; // use the content
			}
			$getrevsarray = json_decode($body, TRUE);
			
			$reviewsarraytemp = $getrevsarray['results'];

	
			//loop reviews and build new array of just what we name
			foreach ($reviewsarraytemp as $item) {
				$username = $item['appointment']['client']['user']['first_name']." ".$item['appointment']['client']['user']['last_name'];
				$tags[0] = $item['appointment']['service_name'];
				$tagjson = json_encode($tags);
				
				 $reviewsarray[] = [
				 'reviewer_name' => $username,
				 'reviewer_id' => $item['appointment']['client']['id'],
				 'reviewer_email' => '',
				 'userpic' => '',
				 'rating' => $item['num_stars'],
				 'updated' => $item['creation_time'],
				 'review_text' => $item['review_text'],
				 'review_title' => '',
				 'from_url_review' => '',
				 'language_code' => '',
				 'location' => '',
				 'recommendation_type' => '',
				 'company_title' =>  '',
				 'company_url' => '',
				 'company_name' => '',
				 'tags' => $tagjson,
				 ];
			}
			//print_r($reviewsarray);
			//die();

			$result['reviews'] = $reviewsarray;

		} else if($type=='iTunes'){
			//call itunes page here. This can be moved to another function if we add another source.
			//grab the id out of the Url $listedurl, https://podcasts.apple.com/us/podcast/id1462192400
			$storeitemid = '';
			if (($pos = strpos($listedurl, "/id")) !== FALSE) { 
				$idonword = substr($listedurl, $pos+3);
				$storeitemid = (int) filter_var($idonword, FILTER_SANITIZE_NUMBER_INT);	//filtering out everything but number
			} else {
				//can't find the ID
				$result['ack']=esc_html__('Error: Can not find the ID in the URL you entered.', 'wp-review-slider-pro');
			}
			$urlarray = parse_url($listedurl);
			$pathstr = $urlarray['path'];
			$patharray = (explode("/",$pathstr));
			$patharray = array_filter($patharray);
			$patharray = array_values($patharray);
			$countrycode = $patharray[0];
			if(!isset($countrycode) || $countrycode==''){
				$countrycode = 'us';
			}

			//https://itunes.apple.com/us/rss/customerreviews/page=1/id=1462192400/sortby=mostrecent/xml
			//json https://itunes.apple.com/us/rss/customerreviews/page=1/id=1462192400/sortby=mostrecent/json
			$callurl = "https://itunes.apple.com/".$countrycode."/rss/customerreviews/page=".$pagenum."/id=".$storeitemid."/sortby=mostrecent/xml";
			
			$result['callurl'] =$callurl;

			//echo $callurl;
			$response = wp_remote_get( $callurl );
 			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				$headers = $response['headers']; // array of http header lines
				$body    = $response['body']; // use the content
			}
			//$getrevsarray = json_decode($body, TRUE);
			// do a string replace on im:rating so we can parse it correctly
			$body = str_replace("im:rating","rating_value",$body);
			$xml=simplexml_load_string($body);
			$getrevsarray = json_decode(json_encode((array)$xml), TRUE);

			$reviewsarraytemp = $getrevsarray['entry'];
			
			
			if (strpos($listedurl, 'see-all/reviews') !== false) {
				$fromurlreviews = $listedurl;
			} else {
				$fromurlreviews = $listedurl."#see-all/reviews";
			}
			
			//loop reviews and build new array of just what we name
			foreach ($reviewsarraytemp as $item) {
				 $reviewsarray[] = [
				 'reviewer_name' => $item['author']['name'],
				 'reviewer_id' => $item['id'],
				 'reviewer_email' => '',
				 'userpic' => '',
				 'rating' => $item['rating_value'],
				 'updated' => $item['updated'],
				 'review_text' => $item['content'][0],
				 'review_title' => $item['title'],
				 'from_url_review' => $fromurlreviews,
				 'language_code' => '',
				 'location' => '',
				 'recommendation_type' => '',
				 'company_title' =>  '',
				 'company_url' => '',
				 'company_name' => '',
				 ];
			}

			$result['reviews'] = $reviewsarray;

		}  else if($type=='Feefo'){			
			
			//first find total and avg reviews
			$callurlsummary = "https://api.feefo.com/api/10/reviews/summary/all?merchant_identifier=".$listedurl;
			$response = wp_remote_get( $callurlsummary );
			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				$headers = $response['headers']; // array of http header lines
				$body    = $response['body']; // use the content
			} else {
				$result['ack']=esc_html__('Error: Can not use remote get on this url:', 'wp-review-slider-pro').' '.$callurlsummary;
			}
			$getsummary = json_decode($body, TRUE);	//convert to array
			
			if(isset($getsummary['meta']['count'])){
				$result['total']=$getsummary['meta']['count'];
			}
			if(isset($getsummary['rating']['rating'])){
				$result['avg']=$getsummary['rating']['rating'];
			}
			$from_url='';
			if(isset($getsummary['merchant']['review_url'])){
				$from_url=$getsummary['merchant']['review_url'];
			}
			
			//now find the reviews
			$callurl = "https://api.feefo.com/api/10/reviews/all?merchant_identifier=".$listedurl."&page_size=50&page=".$pagenum;
			$result['callurl'] =$callurl;
			$args = array(
				'timeout'     => 15,
				'sslverify' => false
			); 
			$responsereviews = wp_remote_get($callurl,$args);
 			if ( is_array( $responsereviews ) && ! is_wp_error( $responsereviews ) ) {
				$headers = $responsereviews['headers']; // array of http header lines
				$body    = $responsereviews['body']; // use the content
			} else {
				$result['ack']=esc_html__('Error: The remote URL timed out.', 'wp-review-slider-pro').' '.$callurl;
			}
			$getrevsarray = json_decode($body, TRUE);	//convert to array

			$reviewsarraytemp = $getrevsarray['reviews'];
			
			//loop reviews and build new array of just what we name
			foreach ($reviewsarraytemp as $item) {
				$tempuserpic = '';
				if(isset($item['service']['review'])){
				 $reviewsarray[] = [
				 'reviewer_name' => trim($item['customer']['display_name']),
				 'reviewer_id' => '',
				 'reviewer_email' => '',
				 'userpic' => $tempuserpic,
				 'rating' => $item['service']['rating']['rating'],
				 'updated' => $item['service']['created_at'],
				 'review_text' => $item['service']['review'],
				 'review_title' => $item['service']['title'],
				 'from_url' => $from_url,
				 'from_url_review' => $item['url'],
				 'language_code' => '',
				 'location' => trim($item['customer']['display_location']),
				 'recommendation_type' => '',
				 'company_title' =>  '',
				 'company_url' => '',
				 'company_name' => '',
				 ];
				}
			}
			
			
			$result['reviews'] = $reviewsarray;
			
		}  else if($type=='Experience'){
			
			$apiurl = $savedpageid;
			
			//if this is page 1 then try and scrape the total and avg for the reviews.
			if($pagenum==1){
				
					if (ini_get('allow_url_fopen') == true) {
						$fileurlcontents=file_get_contents($listedurl);
					} else if (function_exists('curl_init')) {
						$fileurlcontents=$this->file_get_contents_curl($listedurl);
					} else {
						$fileurlcontents='<html><body>'.esc_html__('fopen is not allowed on this host.', 'wp-review-slider-pro').'</body></html>';
						$errormsg = $errormsg . '<p style="color: #A00;">'.esc_html__('fopen is not allowed on this host and cURL did not work either. Ask your web host to turn fopen on or fix cURL.', 'wp-review-slider-pro').'</p>';
						$this->errormsg = $errormsg;
						//echo $errormsg;
					}
				
				//echo $fileurlcontents;
				//die();
					
					$matchavg = $this->get_string_between($fileurlcontents, '"AggregateRating","ratingValue":', ',');
					$matchtot = $this->get_string_between($fileurlcontents, ',"reviewCount":', ',');
				
				//echo $matchavg;
				//echo "--";
				//echo $matchtot;
				//die();
				
					sleep(2);
			}
			
			if(isset($matchavg) && $matchavg>0){
				$result['avg']=$matchavg;
			}
			if(isset($matchtot) && $matchtot>0){
				$result['total']=$matchtot;
			}
			
			
			$plainurl =  strtok($listedurl, '?');
			//parse_str( parse_url( $apiurl, PHP_URL_QUERY), $paramarray );
			$slug = substr($plainurl, strrpos($plainurl, '/') + 1);
			
			$tempoffset = 'null';
			$limitnum = 50;
			if($pagenum>1){
			$tempoffset = ($pagenum-1) * $limitnum ;
			}
			
			$callurl ='https://proapi.experience.com/results';

			$result['callurl'] =$callurl;

			$body = '{"operationName": "Query","variables": 
				{"slug": "'.$slug.'",
					"offset": '.$tempoffset.',
					"limit": 50,
					"orderBy": "newest",
					"reviewerName": null,
					"sourceLabel": null,
					"startRating": null,
					"endRating": null,
					"startDate": null,
					"endDate": null
				},
				"query": "query Query($slug: String, $orderBy: String, $offset: Int, $limit: Int, $reviewerName: String, $sourceLabel: String, $startDate: String, $endDate: String, $startRating: Float, $endRating: Float) {\n  reviews(\n    slug: $slug\n    order_by: $orderBy\n    offset: $offset\n    limit: $limit\n    reviewer_name: $reviewerName\n    source_label: $sourceLabel\n    start_date: $startDate\n    end_date: $endDate\n    start_rating: $startRating\n    end_rating: $endRating\n  ) {\n    reviews {\n      id\n      review\n      rating\n      is_pinned\n      city\n      replies {\n        replier\n        reply\n        reply_date\n        __typename\n      }\n      review_date\n      reviewer_first_name\n      reviewer_last_name\n      reviewer_image_url\n      reviewer_square_image\n      reviewer_rectangle_image\n      is_image_resized\n      source_name\n      source_label\n      participant_type\n      state\n      uuid\n      pinned_at\n      is_ranking_score_disabled\n      review_label\n      belongs_to_name\n      belongs_to_slug\n      __typename\n    }\n    count\n    __typename\n  }\n}\n"
			}';

			$options = [
				'body'        => $body,
				'headers'     => [
					'Content-Type' => 'application/json',
				],
				'timeout'     => 60,
				'redirection' => 5,
				'blocking'    => true,
				'httpversion' => '1.0',
				'sslverify'   => false,
				'data_format' => 'body',
			];
			$response=wp_remote_post( $callurl, $options );
			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				$headers = $response['headers']; // array of http header lines
				$fileurlcontents    = $response['body']; // use the content
			} else {
				$result['ack']=esc_html__('Error: Can not use remote post on this url:', 'wp-review-slider-pro').' '.$callurl;
			}
			
			$getrevsarray = json_decode($fileurlcontents, TRUE);	//convert to array
			
			//print_r($getrevsarray);
			//die();

			$reviewsarraytemp = $getrevsarray['data']['reviews']['reviews'];
			
			//loop reviews and build new array of just what we name
			foreach ($reviewsarraytemp as $item) {
				$tempuserpic ='';
				if(isset($item['reviewer_image_url']) && $item['reviewer_image_url']!=''){
				$tempuserpic = $item['reviewer_image_url'];
				}
				$location ='';
				if(isset($item['city']) && $item['city']!=''){
				$location = $item['city'];
				}
				$tempdate = preg_replace("/\([^)]+\)/","",$item['review_date']);
				$tempdate = trim($tempdate);
				$timestamp = $this->myStrtotime($tempdate);
				$timestamp = date("Y-m-d H:i:s", $timestamp);
				$tempname = trim($item['reviewer_first_name'])." ".trim($item['reviewer_last_name']);
				
				 $reviewsarray[] = [
				 'reviewer_name' => $tempname,
				 'reviewer_id' => '',
				 'reviewer_email' => '',
				 'userpic' => $tempuserpic,
				 'rating' => $item['rating'],
				 'updated' => $timestamp,
				 'review_text' => $item['review'],
				 'review_title' => '',
				 'from_url_review' => '' ,
				 'language_code' => '',
				 'location' => $location,
				 'recommendation_type' => '',
				 'company_title' =>  '',
				 'company_url' => '',
				 'company_name' => '',
				 ];
			}
			
			if(isset($reviewsarray)){
			$reviewsarray = array_map("unserialize", array_unique(array_map("serialize", $reviewsarray)));
			$result['reviews'] = $reviewsarray;
			} else {
			$result['reviews'] = '';	
			}
			
			
		} else if($type=='TrueLocal'){
			
			$apiurl = $savedpageid;
			
			//$listedurl = https://api.truelocal.com.au/rest/listings/B80F0EF2-83C6-4D62-A7C9-FE73A4570666/reviews?order=desc&sort=date&offset=0&limit=50&&passToken=V0MxbDBlV2VNUw==
			//start from page 0
			
			$plainurl =  strtok($apiurl, '?');
			parse_str( parse_url( $apiurl, PHP_URL_QUERY), $paramarray );

			$limitnum = 100;
			$tempoffset = ($pagenum-1) * $limitnum ;

			$callurl = $plainurl."?order=desc&sort=date&offset=".$tempoffset."&limit=".$limitnum."&passToken=".$paramarray['passToken'];
			
			$requestdetails = '{url:"'.$callurl.'",renderType:"html",proxy:"anon-us"}';
			$request = urlencode($requestdetails);
			$tempurlvalue = 'https://phantomjscloud.com/api/browser/v2/ak-2cme5-eqftq-dv73x-nr41t-gkbvs/?request='.$request;
			
			$result['callurl'] =$tempurlvalue;
			
			$numberofcalls=1;
			//loop to here if we need to.
			a:

			if (ini_get('allow_url_fopen') == true) {
				$fileurlcontents=file_get_contents($tempurlvalue);
			} else if (function_exists('curl_init')) {
				$fileurlcontents=$this->file_get_contents_curl($tempurlvalue);
			} else {
				$fileurlcontents='<html><body>'.esc_html__('fopen is not allowed on this host.', 'wp-review-slider-pro').'</body></html>';
				$errormsg = $errormsg . '<p style="color: #A00;">'.esc_html__('fopen is not allowed on this host and cURL did not work either. Ask your web host to turn fopen on or fix cURL.', 'wp-review-slider-pro').'</p>';
				$this->errormsg = $errormsg;
				//echo $errormsg;
			}
			$numberofcalls=$numberofcalls+1;
			
			//echo $fileurlcontents;
			//echo "<br><br>";
			$tempjson =  $this->get_string_between($fileurlcontents, ',"data":', '}</pre>');
			
			if($tempjson=='' && $numberofcalls<5){
				//assume we got blocked try again.
				sleep(1);
				//echo looping;
				goto a;
			}
			
			//echo $tempjson;
			$getrevsarray = json_decode($tempjson, TRUE);	//convert to array

			
			if(isset($getrevsarray['totalReviews'])){
				$result['total']=$getrevsarray['totalReviews'];
			}

			$reviewsarraytemp = $getrevsarray['review'];
			
			//loop reviews and build new array of just what we name
			foreach ($reviewsarraytemp as $item) {
				$tempuserpic = $item['user']['avatars']['image']['0']['urls']['thumbnail'];
				$timestamp = date("Y-m-d H:i:s", $item['dateCreated']);
				//https://www.truelocal.com.au/member/anna-littleboy?target=reviews
				$tempusernamedash = trim($item['user']['displayName']);
				$tempusernamedash = str_replace(" ","-",$tempusernamedash);
				
				$fromurlreview = "https://www.truelocal.com.au/member/".$tempusernamedash."?target=reviews";
				
				 $reviewsarray[] = [
				 'reviewer_name' => trim($item['user']['displayName']),
				 'reviewer_id' => trim($item['user']['id']),
				 'reviewer_email' => '',
				 'userpic' => $tempuserpic,
				 'rating' => $item['rating'],
				 'updated' => $timestamp,
				 'review_text' => $item['text'],
				 'review_title' => '',
				 'from_url_review' => $fromurlreview ,
				 'language_code' => '',
				 'location' => '',
				 'recommendation_type' => '',
				 'company_title' =>  '',
				 'company_url' => '',
				 'company_name' => '',
				 ];
			}
			
			
			$result['reviews'] = $reviewsarray;
			
			
		} else if($type=='HousecallPro'){
			//$listedurl = https://client.housecallpro.com/reviews/Wellmann-Plumbing/de5f6b5d-23a0-4467-89fe-f793c431470d/
			//start from page 0

			//https://api.housecallpro.com/alpha/organizations/de5f6b5d-23a0-4467-89fe-f793c431470d/reviews?page=1&count=10
			$tempuniquecode = '';
			$temppieces = array_filter(explode("/", $listedurl));
			$tempuniquecode = end($temppieces);

			$callurl = "https://api.housecallpro.com/alpha/organizations/".$tempuniquecode."/reviews?&page=".$pagenum."&count=20";

			//echo $callurl;
			$result['callurl'] =$callurl;
			$response = wp_remote_get( $callurl );
 			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				$headers = $response['headers']; // array of http header lines
				$body    = $response['body']; // use the content
			} else {
				$result['ack']=esc_html__('Error: Can not use remote get on this url:', 'wp-review-slider-pro').' '.$callurl;
			}
			
			$getrevsarray = json_decode($body, TRUE);	//convert to array
			//print_r($getrevsarray);
			
			if(isset($getrevsarray['total_count'])){
				$result['total']=$getrevsarray['total_count'];
			}
			
			$reviewsarraytemp = $getrevsarray['data'];
			
			//loop reviews and build new array of just what we name
			foreach ($reviewsarraytemp as $item) {
				$tempuserpic = '';
				 $reviewsarray[] = [
				 'reviewer_name' => trim($item['customer_name']),
				 'reviewer_id' => '',
				 'reviewer_email' => '',
				 'userpic' => $tempuserpic,
				 'rating' => $item['rating'],
				 'updated' => $item['created_at'],
				 'review_text' => $item['comments'],
				 'review_title' => '',
				 'from_url_review' => '',
				 'language_code' => '',
				 'location' => '',
				 'recommendation_type' => '',
				 'company_title' =>  '',
				 'company_url' => '',
				 'company_name' => '',
				 ];
			}
			
			
			$result['reviews'] = $reviewsarray;
			
			
		}  else if($type=='Hostelworld'){
			//https://www.hostelworld.com/pwa/hosteldetails.php/Bazpackers-Hostel/Inverness/49057
			//start from page 1
			
			//https://api.m.hostelworld.com/2.2/properties/49057/reviews/?sort=newest&page=1&monthCount=36&application=web&per-page=50
			$listedurl = strtok($listedurl, '?');	//remove all parameters
			$tempuniquecode = '';
			$temppieces = array_filter(explode("/", $listedurl));
			$finalid = end($temppieces);

			$callurl = "https://api.m.hostelworld.com/2.2/properties/".$finalid."/reviews/?sort=newest&page=".$pagenum."&monthCount=36&application=web&per-page=50";

			//echo $callurl;
			$result['callurl'] =$callurl;
			$response = wp_remote_get( $callurl );
 			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				$headers = $response['headers']; // array of http header lines
				$body    = $response['body']; // use the content
			} else {
				$result['ack']=esc_html__('Error: Can not use remote get on this url:', 'wp-review-slider-pro').' '.$callurl;
			}
			
			$getrevsarray = json_decode($body, TRUE);	//convert to array
			
			
			//if this is page 1 then try and scrape the total and avg for the reviews.
			if($pagenum==1){
				
					if (ini_get('allow_url_fopen') == true) {
						$fileurlcontents=file_get_contents($listedurl);
					} else if (function_exists('curl_init')) {
						$fileurlcontents=$this->file_get_contents_curl($listedurl);
					} else {
						$fileurlcontents='<html><body>'.esc_html__('fopen is not allowed on this host.', 'wp-review-slider-pro').'</body></html>';
						$errormsg = $errormsg . '<p style="color: #A00;">'.esc_html__('fopen is not allowed on this host and cURL did not work either. Ask your web host to turn fopen on or fix cURL.', 'wp-review-slider-pro').'</p>';
						$this->errormsg = $errormsg;
						//echo $errormsg;
					}
					
					//echo $html;
					//echo($fileurlcontents);
					//die();

					$matchavg = $this->get_string_between($fileurlcontents, ',total:', ',');
					$matchtot = $this->get_string_between($fileurlcontents, ',totalReviews:', ',');

					//get the total and average by searching for ,total:9.8,totalReviews:1480,color
					//if (preg_match('/,total:(.*?),totalReviews/', $fileurlcontents, $matchavg) == 1) {
					//	$matchavg= $match[1]/2;
					//}
					//if (preg_match('/,totalReviews:(.*?),', $fileurlcontents, $matchtot) == 1) {
					//	$matchtot= $match[1];
					//}
					//echo "avg:".$matchavg;
					//echo "total:".$matchtot;
			}
			
			if(isset($matchavg) && $matchavg>0){
				$result['avg']=$matchavg/2;
			}
			if(isset($matchtot) && $matchtot>0){
				$result['total']=$matchtot;
			}
			
			$reviewsarraytemp = $getrevsarray['reviews'];
			
			//loop reviews and build new array of just what we name
			foreach ($reviewsarraytemp as $item) {
				$tempuserpic = '';
				if($item['user']['image']){
					$tempuserpic = $item['user']['image'];
				}
				$templocation='';
				if($item['user']['nationality']['name']){
					$templocation=$item['user']['nationality']['name'];
				}
				if(isset($item['languageCode']) && $item['languageCode']!=''){
					$templang = $item['languageCode'];
				} else {
					$templang = '';
				}
				$username='Anonymous';
				if($item['user']['nickname']){
					$username = $item['user']['nickname'];
				}
				$temprating = '5';
				if($item['rating']['overall']){
					$temprating =  $item['rating']['overall']/20;
					$temprating = round($temprating);
				}
				
				 $reviewsarray[] = [
				 'reviewer_name' => $username,
				 'reviewer_id' => $item['id'],
				 'reviewer_email' => '',
				 'userpic' => $tempuserpic,
				 'rating' => $temprating,
				 'updated' => $item['date'],
				 'review_text' => $item['notes'],
				 'review_title' => '',
				 'from_url_review' => '',
				 'language_code' => $templang,
				 'location' => $templocation,
				 'recommendation_type' => '',
				 'company_title' =>  '',
				 'company_url' => '',
				 'company_name' => '',
				 ];
			}
			
			$result['reviews'] = $reviewsarray;		
			
		} else if($type=='GetYourGuide'){
			//https://www.getyourguide.com/london-l57/magical-london-harry-potter-guided-walking-tour-t174648/
			//start from page 0
			if($pagenum > 0){
				$pagenum= $pagenum - 1;
			}
			
			//https://travelers-api.getyourguide.com/activities/174648/reviews?limit=3&offset=2
			$tempuniquecode = '';
			$temppieces = array_filter(explode("/", $listedurl));
			$tempuniquecode = end($temppieces);
			$explodedagain = array_filter(explode("-", $tempuniquecode));
			$tempuniquecode = end($explodedagain);
			$finalid = str_replace("t","",$tempuniquecode);
			
			$offset = $pagenum * 50;
			$callurl = "https://travelers-api.getyourguide.com/activities/".$finalid."/reviews?limit=50&offset=".$offset;

			//echo $callurl;
			$result['callurl'] =$callurl;
			$response = wp_remote_get( $callurl );
 			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				$headers = $response['headers']; // array of http header lines
				$body    = $response['body']; // use the content
			} else {
				$result['ack']=esc_html__('Error: Can not use remote get on this url:', 'wp-review-slider-pro').' '.$callurl;
			}
			
			$getrevsarray = json_decode($body, TRUE);	//convert to array
			
			if(isset($getrevsarray['totalCount'])){
				$result['total']=$getrevsarray['totalCount'];
			}
			if(isset($getrevsarray['averageRating'])){
				$result['avg']=$getrevsarray['averageRating'];
			}

			$reviewsarraytemp = $getrevsarray['reviews'];
			
			//loop reviews and build new array of just what we name
			foreach ($reviewsarraytemp as $item) {
				$tempuserpic = '';
				if($item['author']['photo']){
					$tempuserpic = $item['author']['photo'];
				}
				$templocation='';
				if($item['author']['country']){
					$templocation=$item['author']['country'];
				}
				if(isset($item['language']) && $item['language']!=''){
					$templang = $item['language'];
				} else {
					$templang = '';
				}
				
				 $reviewsarray[] = [
				 'reviewer_name' => trim($item['author']['fullName']),
				 'reviewer_id' => $item['id'],
				 'reviewer_email' => '',
				 'userpic' => $tempuserpic,
				 'rating' => $item['rating'],
				 'updated' => $item['created'],
				 'review_text' => $item['message'],
				 'review_title' => $item['title'],
				 'from_url_review' => '',
				 'language_code' => $templang,
				 'location' => $templocation,
				 'recommendation_type' => '',
				 'company_title' =>  '',
				 'company_url' => '',
				 'company_name' => '',
				 ];
			}
			
			$result['reviews'] = $reviewsarray;		
			
		}  else if($type=='Qualitelis'){
				
			//first include the SDK so we can use to grab the reviews
			//echo WPREV_PLUGIN_DIR;
			//explode the $listedurl to find id and tokens
			$tokenarray = explode(",",$listedurl);
			$Token = trim($tokenarray[0]);
			$IdContractor = trim($tokenarray[1]);
			$CycleId = trim($tokenarray[2]);
			$SurveyId = trim($tokenarray[3]);
			$Langue = trim($tokenarray[4]);
			
			//print_r($tokenarray);

			$urlvalue = "http://www.qualitelis-survey.com/api/Comments/GetV2?Token=".$Token."&IdContractor=".$IdContractor."&CycleId=".$CycleId."&SurveyId=".$SurveyId."&Langue=".$Langue."";
			
			$result['callurl'] =$urlvalue;
			
			//echo $urlvalue;
			
			$data = wp_remote_get( $urlvalue );
			if ( is_wp_error( $data ) ) 
			{
				$response['error_message'] 	= $data->get_error_message();
				$reponse['status'] 		= $data->get_error_code();
				print_r($response);
				die();
			}
			if ( is_array( $data ) ) {
			  $header = $data['headers']; // array of http header lines
			  $body = $data['body']; // use the content
			}
				
			$reviewsarraytemp = json_decode( $body, true );
			//print_r($reviewsarraytemp);
			
			if(isset($reviewsarraytemp['nbAnsweredSurveys'])){
				$result['total']=$reviewsarraytemp['nbAnsweredSurveys'];
			}
			if(isset($reviewsarraytemp['satisfactionAverage'])){
				$result['avg']=($reviewsarraytemp['satisfactionAverage']/20);
			}
					
			//loop reviews and build new array of just what we need
			//print_r($reviewsarraytemp);
			//print_r($reviewsarraytemp->reviews);
			foreach ($reviewsarraytemp['comments'] as $item) {
				
				$rating = $item['noteSatisfaction']/20;
				if(!isset($item['commentTitle'])){
					$item['commentTitle']='';
				}
				
				$metadataarray['stayStart'] = $item['stayStart'];
				$metadataarray['stayEnd'] = $item['stayEnd'];
				$metadataarray['note'] = $item['note'];
				$metadataarray['profile1'] = $item['profile1'];
				$metadataarray['profile2'] = $item['profile2'];
				$metadataarray['profile3'] = $item['profile3'];
				$metadataarray['cycleId'] = $item['cycleId'];
				$metadataarray['cycleName'] = $item['cycleName'];
				$metadataarray['surveyId'] = $item['surveyId'];
				$metadataarray['surveyName'] = $item['surveyName'];
				$metadataarray['idSejour'] = $item['idSejour'];
				$metadataarray['replyMail'] = $item['replyMail'];
				$metadata = json_encode($metadataarray);
				
				$ownerresonsearraytemp = array("id"=>"", "name"=>"", "comment"=>"", "date"=>"");
				if(isset($item['replyMail']) && is_array($item['replyMail'])){
					//{"id":"","name":"test owner","comment":"test owner response","date":"2021-03-22"}
					$tempownername = __( 'Owner', 'wp-review-slider-pro' );
					$subject = '';
					if(isset($item['replyMail']['subject'])){
						$subject = $item['replyMail']['subject'];
					}
					$comment = '';
					if(isset($item['replyMail']['mail'])){
						$comment = $item['replyMail']['mail'];
					}
					$cdate ='';
					if(isset($item['replyMail']['mailDate'])){
						$cdate = $item['replyMail']['mailDate'];
						$cdate = str_replace('/', '-', $cdate);
						$cdate = date('Y-m-d', strtotime($cdate));
					}
					
					$ownerresonsearraytemp = array("id"=>"", "name"=>"$tempownername", "comment"=>"$comment", "date"=>"$cdate");
					
				}
				$ownerresponse = $ownerresonsearraytemp;
				//echo "onwerres:";
				//print_r($ownerresponse);
				
				$updated = $item['replyDate'];
				$newdate = DateTime::createFromFormat("d/m/Y" , $updated);
				$updated =  $newdate->format('Y-m-d');
				
				$tempname = $item['firstName']. ' '.$item['lastName'];
				$tempname =strtolower($tempname);

				$reviewsarray[] = [
				 'reviewer_name' => $tempname,
				 'reviewer_id' => '',
				 'reviewer_email' => '',
				 'userpic' => '',
				 'rating' => $rating,
				 'updated' => $updated,
				 'review_text' => $item['comment'],
				 'review_title' => $item['commentTitle'],
				 'from_url_review' => '',
				 'language_code' => '',
				 'location' => '',
				 'recommendation_type' => '',
				 'company_title' =>  '',
				 'company_url' => '',
				 'company_name' => '',
				 'meta_data' => $metadata,
				 'owner_response' => $ownerresponse,
				 ];
			}
			
			//print_r($reviewsarray);

			$result['reviews'] = $reviewsarray;

			
		}  else if($type=='Freemius'){
				
			//first include the SDK so we can use to grab the reviews
			//echo WPREV_PLUGIN_DIR;
			//explode the $listedurl to find id and tokens
			$tokenarray = explode(",",$listedurl);
			$plugin_id = trim($tokenarray[0]);
			$pkey = trim($tokenarray[1]);
			$skey = trim($tokenarray[2]);
			
			//print_r($tokenarray);
			
			require_once WPREV_PLUGIN_DIR.'admin/freemius/FreemiusBase.php';
			require_once WPREV_PLUGIN_DIR.'admin/freemius/Freemius.php';
			
			// Init SDK.
			$api = new Freemius_Api('plugin', $plugin_id, $pkey, $skey);
			
			// Get all products.
			$reviewsarraytemp = $api->Api('reviews.json?enriched=true&count=50');
			//loop reviews and build new array of just what we need
			//print_r($reviewsarraytemp);
			//print_r($reviewsarraytemp->reviews);
			
			foreach ($reviewsarraytemp->reviews as $item) {
				$tempuserpic = get_avatar_url($item->email);
				//echo $item->name;
				//try to pull from gravatar
				//rating based on 100 percent, 60 equals 3 stars
				$rating =  (substr($item->rate,0,-1))/2;
				$company_title = '';
				if($item->job_title){
					$company_title = $item->job_title;
				}
				$company_url = '';
				if($item->company_url){
					$company_url = $item->company_url;
				}
				$company_name = '';
				if($item->company){
					$company_name = $item->company;
				}
				
				$reviewsarray[] = [
				 'reviewer_name' => $item->name,
				 'reviewer_id' => $item->user_id,
				 'reviewer_email' => $item->email,
				 'userpic' => $tempuserpic,
				 'rating' => $rating,
				 'updated' => $item->created,
				 'review_text' => $item->text,
				 'review_title' => $item->title,
				 'from_url_review' => $item->profile_url,
				 'language_code' => '',
				 'location' => '',
				 'recommendation_type' => '',
				 'company_title' =>  $company_title,
				 'company_url' => $company_url,
				 'company_name' => $company_name,
				 ];
			}
			
			//print_r($reviewsarray);

			$result['reviews'] = $reviewsarray;
			
		} else if($type=='FeedbackCompany'){
			$errormsg='';
			$callurl = $listedurl;
			
			if (filter_var($callurl, FILTER_VALIDATE_URL)) {
					
				$stripvariableurl = stripslashes($callurl);
				//find vestiging 
				$vestiging = '';
				if (($pos = strpos($stripvariableurl, "vestiging=")) !== FALSE) { 
					$vestiging = substr($stripvariableurl, $pos+10); 
				}

				$listedurl = strtok($stripvariableurl, '?');	//remove all parameters
				
				//https://www.feedbackcompany.com/nl-nl/reviews/haartsen-letselschade/?starter=0
				//https://www.feedbackcompany.com/nl-nl/reviews/multipage-haartsen-letselschade/?starter=10&vestiging=IMSVESTIGING:6BCE99A0-CF62-4DCD-B099-1FB6F81F533B
			
				$temppage = ($pagenum - 1)*10;
				
				$callurl = $listedurl.'?starter='.$temppage.'&vestiging='.$vestiging;

				//echo $callurl;
				$result['callurl'] =$callurl;
				$response = wp_remote_get( $callurl );
				if ( is_array( $response ) && ! is_wp_error( $response ) ) {
					$headers = $response['headers']; // array of http header lines
					$body    = $response['body']; // use the content
				} else {
					$result['ack']=esc_html__('Error: Can not use remote get on this url:', 'wp-review-slider-pro').' '.$callurl;
				}
				
				$fileurlcontents = $response['body'];
				
				//get the review schema from html string
				$schemastring = $this->get_string_between($fileurlcontents, '<script type="application/ld+json">', '</script>');
				
				//echo $schemastring;
				//return $result;
				
				$pagedata = json_decode( $schemastring, true );
				//print_r($pagedata);
				
				$html = wppro_str_get_html($fileurlcontents);
				
				if($pagenum==1){
					$result['total']='';
					//if(isset($pagedata['aggregateRating']['reviewCount'])){
					//	$result['total']=$pagedata['aggregateRating']['reviewCount'];
					//}
					if($html->find('div[class=label label--small rating__review-count]', 0)){
						$result['total']= $html->find('div[class=label label--small rating__review-count]', 0)->plaintext;
						$result['total']= intval($result['total']);
					}
					
					$result['avg']='';
					//if(isset($pagedata['aggregateRating']['ratingValue'])){
					//	if($pagedata['aggregateRating']['bestRating']<10){
					//		$result['avg']=$pagedata['aggregateRating']['ratingValue'];
					//	} else {
					//		$result['avg']=$pagedata['aggregateRating']['ratingValue']/2;
					//	}
					//}
					if($html->find('div[class=rating__numbers]', 0)){
						$result['avg']= $html->find('div[class=rating__numbers]', 0)->find('span', 0)->plaintext;
					}
				}
				if($pagedata['aggregateRating']['bestRating']<10){
						//$result['avg']=$pagedata['aggregateRating']['ratingValue'];
					} else {
						$result['avg']=$result['avg']/2;
				}
				

				//echo $html;
				$reviewcontainerdiv = Array();
				//get the array of review container class
				if($html->find('article[class=review]', 0)){
					$reviewcontainerdiv = $html->find('article[class=review]');
				}

				foreach ($reviewcontainerdiv as $review) {
						$user_name='';
						$userimage='';
						$rating='';
						$datesubmitted='';
						$rtext='';
						$from_url_review='';
						
					// Find user_name
					
					if($review->find('h3[class=review__header__name]', 0)){
						$user_name= $review->find('h3[class=review__header__name]', 0)->plaintext;
					}

					//from url review
					if($review->find('footer[class=review__footer]', 0)){
						if($review->find('footer[class=review__footer]', 0)->find('a',0)){
							$from_url_review= $review->find('footer[class=review__footer]', 0)->find('a',0)->href;
						}
					}
					
					//find rating review__stars-number label
					if($review->find('div[class=review__stars-number label]', 0)){
							$rating = $review->find('div[class=review__stars-number label]', 0)->plaintext;
							$rating = str_replace("/10", "", $rating);
							$rating = str_replace(" ", "", $rating);
							$rating = $rating/2;
					}
					
					//find date created_at
					if($review->find('section[class=review__timeline]', 0)){
							$datesubmitted= $review->find('section[class=review__timeline]', 0)->plaintext;
					}
					//find text, look for expandable text first $rtext
					if($review->find('span[data-role=expand-text--expanded]', 0)){
						$rtext= $review->find('span[data-role=expand-text--expanded]', 0)->plaintext;
					}
					if($rtext==''){
						//look again if blank header header--long header--black
						if($review->find('h3[class=header header--long header--black]', 0)){
							$rtext= $review->find('h3[class=header header--long header--black]', 0)->plaintext;
						}
					}
					$recommend="";
					$meta_json ="";
					$meta_data = Array();
					if($review->find('span[class=review__recommendation-answer]', 0)){
							$recommend= $review->find('span[class=review__recommendation-answer]', 0)->plaintext;
							$recommend=trim($recommend);
							if($recommend=='Yes' || $recommend=='Ja'){
								$recommendation_type = 'positive';
								$meta_data['recommends'] = "yes";
							} else if($recommend=='No' || $recommend=='Nee'){
								$recommendation_type = 'negative';
								$meta_data['recommends'] = "no";
							}
					}
					if(count($meta_data)>0){
						$meta_json = json_encode($meta_data);
					}
	
					if($rating>0){
						$reviewsarraytemp[] = [
								'reviewer_name' => trim($user_name),
								'rating' => $rating,
								'date' => $datesubmitted,
								'review_text' => trim($rtext),
								'type' => $type,
								'from_url_review' => $from_url_review,
								'recommendation_type' => $recommendation_type,
								'meta_data' => $meta_json
						];
					}
						
				}


				//loop reviews and build new array of just what we need
				foreach ($reviewsarraytemp as $item) {
					 $reviewsarrayfinal[] = [
					 'reviewer_name' => trim($item['reviewer_name']),
					 'reviewer_id' => '',
					 'reviewer_email' => '',
					 'userpic' => '',
					 'rating' => $item['rating'],
					 'updated' => $item['date'],
					 'review_text' => $item['review_text'],
					 'review_title' => '',
					 'from_url' => $listedurl,
					 'from_url_review' => $item['from_url_review'],
					 'language_code' =>'',
					 'location' => '',
					 'recommendation_type' => $item['recommendation_type'],
					 'company_title' =>  '',
					 'company_url' => '',
					 'company_name' => '',
					 'meta_data' => $item['meta_data'],
					 ];
				}
				//print_r($reviewsarrayfinal);
				//die();
				$result['reviews'] = $reviewsarrayfinal;
				
			}
		}
		

		return $result;
	}

	
		//for using curl instead of fopen
	public function file_get_contents_curl_browser($url,$cookieval,$auth='nextdoor.com') {
		$agent= 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:60.0) Gecko/20100101 Firefox/60.0';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_VERBOSE, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, $agent);
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
							'authority: '.$auth.'',
							'pragma: no-cache',
							'cache-control: no-cache',
							'upgrade-insecure-requests: 1',
							'user-agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.100 Safari/537.36',
							'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3', 'accept-encoding: gzip, deflate, br',
							'accept-language: en-US,en;q=0.9',
							'cookie: '.$cookieval.''
					));
		curl_setopt($ch, CURLOPT_URL,$url);
		$result=curl_exec($ch);
		curl_close($ch);
		return $result;
	}
	
	//====================twitter======================
	//for checking twitter keys
	public function wprp_twitter_gettweets_ajax() {
		
		check_ajax_referer('randomnoncestring', 'wpfb_nonce');
		$searchquery = sanitize_text_field($_POST['query']);
		$searchendpoint = sanitize_text_field($_POST['endpoint']);
		$formid = sanitize_text_field($_POST['fid']);
		$resultarray['searchquery'] = $searchquery;
		$resultarray['searchendpoint'] = $searchendpoint;
		
		//update the searchquery for the form id, this is becuase of the input on the pop-up.
		global $wpdb;
		$table_name = $wpdb->prefix . 'wpfb_gettwitter_forms';
		$timenow = time();
		$data = array('query' => "$searchquery",'last_ran' =>"$timenow");
		$format = array('%s','%d');
		$updatetempquery = $wpdb->update($table_name, $data, array( 'id' => $formid ), $format, array( '%d' ));
		
		$wprevpro_twitter_api_key = get_option('wprevpro_twitterapi_key');
		$wprevpro_twitter_api_key_secret = get_option('wprevpro_twitterapi_key_secret');
		$wprevpro_twitter_api_token = get_option('wprevpro_twitterapi_token');
		$wprevpro_twitter_api_token_secret = get_option('wprevpro_twitterapi_token_secret');

		
		
		//------if we are using default keys then force to the standard search, also force in javascript
		if($searchendpoint=="7" || $wprevpro_twitter_api_key=='' || $wprevpro_twitter_api_key_secret=='' || $wprevpro_twitter_api_token=='' || $wprevpro_twitter_api_token_secret==''){
			//====default twitter keys used for standard search/
			$wprevpro_twitter_api_default['key']='O30jlOfBnZdV5Eh8iWO37jsEw';
			$wprevpro_twitter_api_default['secret']='GL4LFyXwfOZTORVmkQjXrhorUzEIy7ycamYXC8icpDWrluKXi2';
			$wprevpro_twitter_api_default['token']='919980007707037697-B8oPwME9yBWt0NQc3L9pdEBvWqzFfzE';
			$wprevpro_twitter_api_default['token_secret']='Gvk3Op3oNyhzzOd1oONPp414yNO6XnFqN5AxSJnMVxkoI';
		
			//use standard search
			$connection = new Abraham\TwitterOAuth\TwitterOAuth($wprevpro_twitter_api_default['key'], $wprevpro_twitter_api_default['secret'], $wprevpro_twitter_api_default['token'], $wprevpro_twitter_api_default['token_secret']);

			
			$resultstemp = (array)$connection->get("search/tweets", ["q" => $searchquery,"count" => '100']);

			//print_r($resultstemp);
			
			$statuses['results']=$resultstemp['statuses'];
			
			//$resultsarr = json_decode($resultstemp,true);
			//print_r($resultsarr);
			//$statusesarr = $resultsarr['statuses'];
			//$statuses = json_encode($statusesarr['statuses']);
			//$statuses need to match what we get from premium search
		} else {

			$connection = new Abraham\TwitterOAuth\TwitterOAuth($wprevpro_twitter_api_key, $wprevpro_twitter_api_key_secret, $wprevpro_twitter_api_token, $wprevpro_twitter_api_token_secret);
			if($searchendpoint=='all'){
				$endhtml = 'fullarchive';
			} else {
				$endhtml = '30day';
			}
			$statuses = $connection->get("tweets/search/".$endhtml."/wprevdev", ["query" => $searchquery,"maxResults" => '100']);
			
		}
		
		//get an array of all tweets in db and pass back so we can know what we already have.
		global $wpdb;
		$table_name = $wpdb->prefix . 'wpfb_reviews';
		$resultarray['savedreviews'] = $wpdb->get_col( "SELECT unique_id FROM ".$table_name." WHERE type = 'Twitter'" );
		
		
		if ($connection->getLastHttpCode() == 200) {
			$resultarray['ack'] = 'success';
			$resultarray['msg'] ='';
			$resultarray['statuses'] =$statuses;
		} else {
			// Handle error case
			$resultarray['ack'] = 'error';
			$temperrormessage = (array)$connection->getLastBody();
			$temperrormessage = json_encode($temperrormessage);
			$resultarray['msg'] = $temperrormessage;
			$resultarray['statuses'] =$statuses;
		}
		
		echo json_encode($resultarray);
		die();
	}
	//for saving or deleting tweets in db
	public function wprp_twitter_savetweet_ajax() {
		check_ajax_referer('randomnoncestring', 'wpfb_nonce');
		
		$saveordel =  sanitize_text_field($_POST['saveordel']);
		
		$review_text = sanitize_text_field($_POST['tw_text']);
		
		$tw_rtc = sanitize_text_field($_POST['tw_rtc']);
		$tw_rc = sanitize_text_field($_POST['tw_rc']);
		$tw_fc = sanitize_text_field($_POST['tw_fc']);
		$tw_time = sanitize_text_field($_POST['tw_time']);
		$tw_id = sanitize_text_field($_POST['tw_id']);
		$tw_sname = sanitize_text_field($_POST['tw_sname']);
		$tw_name = sanitize_text_field($_POST['tw_name']);
		$tw_img = sanitize_text_field($_POST['tw_img']);
		$tw_lang = sanitize_text_field($_POST['tw_lang']);
		
		$fid = sanitize_text_field($_POST['fid']);
		$limage = sanitize_text_field($_POST['limage']);

		$pagename = sanitize_text_field($_POST['title']);
		$pageid = str_replace(" ","",$pagename)."_".$fid;
		$pageid = str_replace("'","",$pageid);
		$pageid = str_replace('"',"",$pageid);
		
		$timestamp = $this->myStrtotime($tw_time);
		$unixtimestamp = $timestamp;
		$timestamp = date("Y-m-d H:i:s", $timestamp);
		
		if (extension_loaded('mbstring')) {
			$review_length = mb_substr_count($review_text, ' ');
			$review_length_char = mb_strlen($review_text);
		} else {
			$review_length = substr_count($review_text, ' ');
			$review_length_char = strlen($review_text);
		}
		if($review_length_char>0 && $review_length<1){
			$review_length = 1;
		}
		
		$from_url = "https://twitter.com/".$tw_sname."/status/".$tw_id;
		
		$cats = sanitize_text_field($_POST['cats']);
		$cats = str_replace("'",'"',$cats);
		$posts = sanitize_text_field($_POST['posts']);
		$posts = str_replace("'",'"',$posts);
		//save likes, retweets, and replies in meta_data
		//===============================================
		$meta_data['user_url'] = "https://twitter.com/".$tw_sname;
		$meta_data['favorite_count'] = $tw_fc;
		$meta_data['retweet_count'] = $tw_rtc;
		$meta_data['reply_count'] = $tw_rc;
		$meta_data['screenname'] = $tw_sname;
		$meta_json = json_encode($meta_data);
		//{"user_url":"https://www.tripadvisor.com/Profile/rhohensee","location":"Houston, Texas","contributions":2,"helpful_votes":3,"date_of_visit":"2019-07-31"}
		//===============================================
		
		global $wpdb;
		$table_name = $wpdb->prefix . 'wpfb_reviews';
		//if saving in db
		if($saveordel=='save'){
			
			//see if we are default hide to yes from Tools/Settings page
			$hideondownload = get_option( 'wprev_hideondownload', '' );
			$temphide='';
			if($hideondownload=="yes"){
				$temphide = "yes";
			}
			
			$stat = [
						'reviewer_name' => $tw_name,
						'reviewer_id' => trim($tw_sname),
						'pagename' => trim($pagename),
						'pageid' => trim($pageid),
						'userpic' => $tw_img,
						'recommendation_type' => 'positive',
						'created_time' => $timestamp,
						'created_time_stamp' => $unixtimestamp,
						'review_text' => $review_text,
						'hide' => $temphide,
						'review_length' => $review_length,
						'review_length_char' => $review_length_char,
						'type' => 'Twitter',
						'from_url' => trim($from_url),
						'from_url_review' => trim($from_url),
						'language_code' => $tw_lang,
						'unique_id' => $tw_id,
						'meta_data' => $meta_json,
						'categories' => trim($cats),
						'posts' => trim($posts),
					];
			
			$insertnum = $wpdb->insert( $table_name, $stat );
			$resultarray['insertnum']=$insertnum;
			
			//try to save local image if turned on
				if($insertnum>0 && $limage=="yes" && $tw_img!=''){
					$resultarray['imgdownload']='yes';
					$stat['id']=$wpdb->insert_id;
					$resultarray['id']=$stat['id'];
					$statobj = (object) $stat;
					$this->wprevpro_download_avatar_tolocal($tw_img,$statobj);
				}
			
		}
		
		echo json_encode($resultarray);
		die();
		
	}
	//to delete tweet via ajax
	public function wprp_twitter_deltweet_ajax() {
		check_ajax_referer('randomnoncestring', 'wpfb_nonce');
		$tw_id = sanitize_text_field($_POST['tw_id']);
		
		global $wpdb;
		$table_name = $wpdb->prefix . 'wpfb_reviews';

		//remove this tweets
		$deletereview = $wpdb->delete( $table_name, array( 'unique_id' => $tw_id ), array( '%s' ) );
		$resultarray['deletenum']=$deletereview;
		
		echo json_encode($resultarray);
		die();
		
	}
	
	
	//ajax for crawling to check placeid.
	public function wprevpro_ajax_crawl_placeid(){
		//echo "here";
		check_ajax_referer('randomnoncestring', 'wpfb_nonce');
		//die();
		$gplaceid = trim(sanitize_text_field($_POST['gplaceid']));
		
		//echo $gplaceid;
		//die();

		if(!isset($gplaceid) || $gplaceid==''){
				$results['ack'] = 'error';
				$results['ackmsg'] = 'Please a enter a Place ID or Search Terms.';
				$results = json_encode($results);
				echo $results;
				die();
		}
		
		
		if(isset($_SERVER['SERVER_ADDR']) && $_SERVER['SERVER_ADDR']!=''){
			$ip_server = $_SERVER['SERVER_ADDR'];
		} else {
			//get url of site.
			$ip_server = urlencode(get_site_url());
		}
		$siteurl = urlencode(get_site_url());
		//strip \' from url 
		$gplaceid = str_replace("\'","",$gplaceid);
		
		$tempurlvalue = 'https://crawl.ljapps.com/crawlrevs?rip='.$ip_server.'&surl='.$siteurl.'&stype=googlecheck&sfp=pro&nobot=1&scrapequery='.urlencode($gplaceid);
		
		//https://crawl.ljapps.com/crawlrevs?rip=127.0.0.1&surl=https%3A%2F%2Fwptest.ljapps.com&stype=googlecheck&sfp=pro&nobot=1&scrapequery=Villa+La+Douce+Provence+Vince+Holiday+home+in+Lorgues%2C+France

		
		$serverresponse='';
		if (ini_get('allow_url_fopen') == true) {
			$serverresponse=file_get_contents($tempurlvalue);
		} else if (function_exists('curl_init')) {
			$serverresponse=$this->file_get_contents_curl($tempurlvalue);
		} else {
			$fileurlcontents='<html><body>'.esc_html__('fopen is not allowed on this host.', 'wp-google-reviews').'</body></html>';
			$errormsg = $errormsg . '<p style="color: #A00;">'.esc_html__('fopen is not allowed on this host and cURL did not work either. Ask your web host to turn fopen on or fix cURL.', 'wp-google-reviews').'</p>';
			$this->errormsg = $errormsg;
			$results['ack'] ='error';
			$results['ackmsg'] =$errormsg;
			$results = json_encode($results);
			echo $results;
			die();
		}
		if($serverresponse==false || $serverresponse==''){
		//try remote_get if we dont' have serverresponse yet
			$args = array(
				'timeout'     => 30,
				'sslverify' => false
			); 
			$response = wp_remote_get( $tempurlvalue, $args );
			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				$headers = $response['headers']; // array of http header lines
				$serverresponse    = $response['body']; // use the content
			} else {
				//must have been an error
				$results['ack'] ='error';
				$results['ackmsg'] ='Error 0001a: trouble contacting crawling server with remote_get. Please try again or contact support.'.$response->get_error_message();
				$results = json_encode($results);
				echo $results;
				die();
			}
		}
		//echo $serverresponse;
		$serverresponsearray = json_decode($serverresponse, true);
		//print_r($serverresponsearray);
		
		if($serverresponse=='' || !is_array($serverresponsearray)){
			$results['ack'] ='error';
			$results['ackmsg'] ='Error 0001: trouble contacting crawling server. Please try again or contact support.';
			$results = json_encode($results);
			echo $results;
			die();
		}
		
		if($serverresponsearray['ack']=='error'){
			$results['ack'] ='error';
			$results['ackmsg'] =$serverresponsearray['ackmessage'];
			$results = json_encode($results);
			echo $results;
			die();
			
		}
		
		$businessdetails = $serverresponsearray['result'];
		if($businessdetails['ack']!='success'){
			$results['ack'] ='error';
			$results['ackmsg'] =$businessdetails['ackmsg'];
			$results = json_encode($results);
			echo $results;
			die();
			
		}

		//$tempcrawlresults = array("$gplaceid" => $results);
		
		//need to add to not overwrite current values.
		$previousoptionsarray = json_decode(get_option('wprev_google_crawl_check'),true);
		$previousoptionsarray["$gplaceid"]=$businessdetails;
		

		$tempcrawlresultsfinal = json_encode($previousoptionsarray);
		update_option('wprev_google_crawl_check',$tempcrawlresultsfinal );
		
		//print_r($previousoptionsarray);
		//print_r(json_decode(get_option('wprev_google_crawl_check'),true));
			//die();
		
		$results = json_encode($businessdetails);
		echo $results;
	
		die();
				
	}
	
	
	

}
?>