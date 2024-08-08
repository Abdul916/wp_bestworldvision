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
	$frlicenseid = get_option( 'wprev_fr_siteid' );
	$frsiteurl = get_option( 'wprev_fr_url' );
	$frsiteid = get_option( 'wprev_fr_id' );
	$wpsiteurl = get_site_url();

     // check user capabilities
    if (!current_user_can('manage_options') && $this->wprev_canuserseepage('reviewfunnel')==false) {
        return;
    }
	
	$dbmsg = "";
	$html="";
	$currentreviewfunnel= new stdClass();
	$currentreviewfunnel->id="";
	$currentreviewfunnel->title="";
	$currentreviewfunnel->site_type="";
	$currentreviewfunnel->url="";
	$currentreviewfunnel->cron="";
	$currentreviewfunnel->from_date="";
	$currentreviewfunnel->query="";
	$currentreviewfunnel->blocks="25";
	$currentreviewfunnel->last_name="full";
	$currentreviewfunnel->profile_img="";
	$currentreviewfunnel->categories="";
	$currentreviewfunnel->posts="";
	$currentreviewfunnel->googleplaceid="";
	$currentreviewfunnel->gplaceorsearch="";
	$currentreviewfunnel->pluginversion="";
	$currentreviewfunnel->reviewlistpageid="";	

	
	//db function variables
	global $wpdb;
	$table_name = $wpdb->prefix . 'wpfb_reviewfunnel';
	
	//--get rt from url if set and use for type
	$rt='';
	if(isset($_GET['rt'])){
		$rt=$_GET['rt'];
	}
	
	//form deleting and updating here---------------------------
	if(isset($_GET['taction'])){
		if(isset($_GET['tid'])){
			$tid = htmlentities($_GET['tid']);
			$tid = intval($tid);
			//for deleting
			if($_GET['taction'] == "del" && $_GET['tid'] > 0){
				//security
				check_admin_referer( 'tdel_');
				//delete
				$wpdb->delete( $table_name, array( 'id' => $tid ), array( '%d' ) );
			}
			//for updating
			if($_GET['taction'] == "edit" && $_GET['tid'] > 0){
				//security
				check_admin_referer( 'tedit_');
				//get form array
				$currentreviewfunnel = $wpdb->get_row( "SELECT * FROM ".$table_name." WHERE id = ".$tid );
			}
			//for copying
			if($_GET['taction'] == "copy" && $_GET['tid'] > 0){
				//security
				check_admin_referer( 'tcopy_');
				//get form array
				$currentreviewfunnel = $wpdb->get_row( "SELECT * FROM ".$table_name." WHERE id = ".$tid );
				//add new template
				$array = (array) $currentreviewfunnel;
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
	//check to see if form has been posted.
	//if template id present then update database if not then insert as new.

	if (isset($_POST['wprevpro_submittemplatebtn'])){
		//verify nonce wp_nonce_field( 'wprevpro_save_template');
		check_admin_referer( 'wprevpro_save_template');
		//get form submission values and then save or update
		$t_id = sanitize_text_field($_POST['edittid']);
		$title = sanitize_text_field($_POST['wprevpro_template_title']);
		$site_type = sanitize_text_field($_POST['wprevpro_site_type']);
		$url = urlencode($_POST['wprevpro_url']);
		$cron = sanitize_text_field($_POST['wprevpro_cron_setting']);
		$from_date = sanitize_text_field($_POST['wprevpro_from_date']);
		$blocks = sanitize_text_field($_POST['wprevpro_blocks']);
		$blocks = intval($blocks);
		
		//if this is facebook change blocks and from date if we want all.
		$fb_num = "";
		if(isset($_POST['wprevpro_fb_num']) && ($site_type=="facebook" || $site_type=="Facebook")){
			$fb_num = sanitize_text_field($_POST['wprevpro_fb_num']);
		}
		if($fb_num=="fall"){
			$blocks = 'fall';
		} else if($fb_num=="10"){
			$blocks = '10';
			$from_date ="2001-01-01";
		}
		
		$last_name = sanitize_text_field($_POST['wprevpro_last_name']);
		$profile_img = sanitize_text_field($_POST['wprevpro_profile_img']);
		
		$googleplaceid ='';
		$query='';
		$gplaceorsearch ='';
		if($site_type=="google" || $site_type=="Google"){
			$from_date='';
			$url='';
			$gplaceorsearch = sanitize_text_field($_POST['wprevpro_placeidorterms']);
			//are we using placeid or search terms.
			if($_POST['wprevpro_placeidorterms']=='placeid'){
				$googleplaceid = sanitize_text_field($_POST['wprevpro_googleplaceid']);
			} else {
				$query = sanitize_text_field($_POST['wprevpro_query']);
			}
		} else {
			$query='';
			//$blocks='';
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
		$reviewlistpageid = '';
		
		//+++++++++need to sql escape using prepare+++++++++++++++++++
		//+++++++++++++++++++++++++++++++++++++++++++++++++++++
		//insert or update
		
		//now saving place_id with form data
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
			$reviewlistpageid = $pregtitle."_rf".$site_type;
			

			
		if($t_id==""){
			//must be inserting.
			//first check to make sure there is not another one using the same $reviewlistpageid
			$checkformforpageid = $wpdb->get_results("SELECT * FROM $table_name WHERE reviewlistpageid = '$reviewlistpageid' LIMIT 1");
			if(count($checkformforpageid)>0){
				$dbmsg = $dbmsg.'<div id="setting-error-wprevpro_message" class="error settings-error notice is-dismissible"><p><strong>'.__('Oops! The Review Funnel could not be inserted in to the database. Try to use a more unique Funnel Name or add a number to the end.', 'wp-review-slider-pro').'</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">'.__('Dismiss this notice.', 'wp-review-slider-pro').'</span></button></div>';
			} else {

			$data = array( 
				'title' => "$title",
				'reviewlistpageid' => "$reviewlistpageid",
				'site_type' => "$site_type",
				'created_time_stamp' => "$timenow",
				'url' => "$url",
				'cron' => "$cron",
				'from_date' => "$from_date",
				'query' => "$query",
				'blocks' => "$blocks",
				'last_name' => "$last_name",
				'profile_img' => "$profile_img",
				'categories' => "$catidsarrayjson",
				'posts' => "$postidsarrayjson",
				'googleplaceid' => "$googleplaceid",
				'gplaceorsearch' => "$gplaceorsearch",
				'pluginversion' => "$this->intpluginversion",
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
					'%s',
				); 
			//print_r($data);
			//insert
			$insertrow = $wpdb->insert( $table_name, $data, $format );
			if(!$insertrow){
			//$wpdb->show_errors();
			//$wpdb->print_error();
			$dbmsg = $dbmsg.'<div id="setting-error-wprevpro_message" class="error settings-error notice is-dismissible"><p><strong>'.__('Oops! The Review Funnel could not be inserted in to the database. Make sure you are only using the main part of the URL.', 'wp-review-slider-pro').'</br> -'.$wpdb->show_errors().' -'.$wpdb->print_error().' </strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">'.__('Dismiss this notice.', 'wp-review-slider-pro').'</span></button></div>';
			}
			}
			//die();
		} else {

				$data = array( 
				'title' => "$title",
				'reviewlistpageid' => "$reviewlistpageid",
				'site_type' => "$site_type",
				'created_time_stamp' => "$timenow",
				'url' => "$url",
				'cron' => "$cron",
				'from_date' => "$from_date",
				'query' => "$query",
				'blocks' => "$blocks",
				'last_name' => "$last_name",
				'profile_img' => "$profile_img",
				'categories' => "$catidsarrayjson",
				'posts' => "$postidsarrayjson",
				'googleplaceid' => "$googleplaceid",
				'gplaceorsearch' => "$gplaceorsearch",
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
			//print_r($data);
			$updatetempquery = $wpdb->update($table_name, $data, array( 'id' => $t_id ), $format, array( '%d' ));
			//$wpdb->show_errors();
			//$wpdb->print_error();
			if($updatetempquery>0){
				$dbmsg = '<div id="setting-error-wprevpro_message" class="updated settings-error notice is-dismissible"><p><strong>'.__('Review Funnel Updated!', 'wp-review-slider-pro').'</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">'.__('Dismiss this notice.', 'wp-review-slider-pro').'</span></button></div>';
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
				$dbmsg = '<div id="setting-error-wprevpro_message" class="updated settings-error notice is-dismissible"><p><strong>'.__('Error', 'wp-review-slider-pro').':</strong> '.__('Oops! The Review Funnel could not be updated in the database. Make sure you are only using the main part of the URL.', 'wp-review-slider-pro').'</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">'.__('Dismiss this notice.', 'wp-review-slider-pro').'</span></button></div>';
			}
			
		}
		
		
	}

	//Get list of all current forms--------------------------
	$currentforms = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id DESC");
	//-------------------------------------------------------

	
?>

<div class="wrap wp_pro-settings" style="min-height: 900px;">
	<h1><img src="<?php echo plugin_dir_url( __FILE__ ) . 'logo.png'; ?>"></h1>
	
<?php 
include("tabmenu.php");

//query args for export and import
$url_tempdownload = admin_url( 'admin-post.php?action=print_reviewfunnel.csv' );
if ( wrsp_fs()->can_use_premium_code() ) {
	
// make a call to http://funnel.ljapps.com/frstats with the variables to get stats, or insert if new
//echo $frsiteid."<br>" ;
//echo $frlicenseid."<br>" ;
//echo $frsiteurl."<br>" ;
//echo $wpsiteurl."<br>" ;

 $response = wp_remote_get( 'https://funnel.ljapps.com/frstats?frlicenseid='.$frlicenseid.'&frsiteurl='.$frsiteurl.'&wpsiteurl='.$wpsiteurl.'&frsiteid='.$frsiteid, array( 'sslverify' => false, 'timeout' => 60 ) );

//$response = wp_remote_get( 'https://funnel.ljapps.com/frstats?frlicenseid='.$frlicenseid.'&frsiteurl='.$frsiteurl.'&wpsiteurl='.$wpsiteurl.'&frsiteid='.$frsiteid );

//echo 'https://funnel.ljapps.com/frstats?frlicenseid='.$frlicenseid.'&frsiteurl='.$frsiteurl.'&wpsiteurl='.$wpsiteurl.'&frsiteid='.$frsiteid;
 
 
 //print_r($response);
 
 
if ( is_array( $response ) && ! is_wp_error( $response ) ) {
    $headers = $response['headers']; // array of http header lines
    $body    = $response['body']; // use the content
}
$licensecheckarray = json_decode($body,true);

//print_r($licensecheckarray);

//error check
if($licensecheckarray['ack']!="success"){
	echo '<div class="w3-panel w3-red"><p>'.__( 'Error: Unable to check your review credit balance. Please try again. ', 'wp-review-slider-pro' ).$licensecheckarray['ackmessage'].'</p></div> ';
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

$revcreditsleft = $statsarray['totalreviewbank']-$statsarray['totalreviewcreditsused'];
if($revcreditsleft<0){
	$revcreditsleft=0;
}
	
	
?>

<div class="w3-col m12">
<div class="wprevpro_margin10">
	<a id="wprevpro_helpicon_posts" class="wprevpro_btnicononly button dashicons-before dashicons-editor-help"></a>
	<a id="wprevpro_addnewtemplate" class="button dashicons-before dashicons-plus-alt"><?php _e('Add New Review Funnel', 'wp-review-slider-pro'); ?></a>
	<a href="https://funnel.ljapps.com/buycredits/<?php echo $statsarray['id']; ?>" target="_blank" class="button dashicons-before dashicons-cart"><?php _e('Buy More Review Credits', 'wp-review-slider-pro'); ?></a>
<?php
/*
	<a href="<?php echo $url_tempdownload;?>" id="wprevpro_exporttemplates" class="button dashicons-before dashicons-download"><?php _e('Export Review Funnels', 'wp-review-slider-pro'); ?></a>
	<a id="wprevpro_importtemplates" class="button dashicons-before dashicons-upload"><?php _e('Import Review Funnels', 'wp-review-slider-pro'); ?></a>
	*/
?>
</div>
<div class="wprevpro_margin10" id="importform" style='display:none;'>
	    <form  action="?page=wp_pro-reviewfunnel" method="post" name="upload_excel" enctype="multipart/form-data">
		<p><b><?php _e('Use this form to import previously exported Review Funnels.', 'wp-review-slider-pro'); ?></b></p>
			<input type="file" name="file" id="file">
			</br>
			<button type="submit" id="submit" name="Import" class="button-primary" data-loading-text="Loading..."><?php _e('Import', 'wp-review-slider-pro'); ?></button>
        </form>
</div>

<?php

} else {
	echo '<div class="wprevpro_margin10">';
	_e('Review Funnels are a Premium feature. Please upgrade.', 'wp-review-slider-pro');
	echo '</div>';
}



?>
<div id="moreinfoaccountpopup" class="wprevpro_hide">
<div>
<p><?php _e('Your website automatically gets 2,000 review credits every year it has an active license.', 'wp-review-slider-pro'); ?></p>
<p><?php _e('When you make a request to scrape a site it will cost (10 review credits + the # of reviews scraped) against your Review Credits, even if you don\'t download them. Take care if you are Automatically checking for new reviews not to set it to often.', 'wp-review-slider-pro'); ?></p>
<p><a href="https://funnel.ljapps.com/buycredits/<?php echo $statsarray['id']; ?>" target="_blank"><?php _e('Buy more credits here.', 'wp-review-slider-pro'); ?></a></p>
<?php
foreach ($statsarray as $k => $v) {
    echo "$k => $v\n <br>";
}
?>
</div>
</div>


<div class="w3-row-padding w3-margin-bottom" style="margin-right: -30px;">
    <div class="w3-third" style="margin-left: -15px;">
      <div class="w3-container w3-green w3-padding-16">
        <div class="w3-left"><span class="dashicons dashicons-download"></span> <?php _e('Review Credits Remaining', 'wp-review-slider-pro'); ?> <span id="accountinfospan"><?php _e('(more info)', 'wp-review-slider-pro'); ?></span></div>
        <div class="w3-right"></div>
        <h1 id="reviewcredits" class="w3-text-white" style="padding-top: 30px;"><?php echo $statsarray['totalreviewbank']-$statsarray['totalreviewcreditsused']; ?></h1>
      </div>
    </div>
	<div class="w3-third">
      <div class="w3-container w3-blue w3-padding-16">
        <div class="w3-left"><span class="dashicons dashicons-plus-alt"></span> <?php _e('Total Scrape Jobs Added', 'wp-review-slider-pro'); ?></div>
        <div class="w3-right"></div>
        <h1 class="w3-text-white" style="padding-top: 30px;"><?php echo $statsarray['totaljobsadded']; ?></h1>
      </div>
    </div>
		<div class="w3-third">
      <div class="w3-container w3-orange w3-padding-16">
        <div class="w3-left w3-text-white"><span class="dashicons dashicons-calendar-alt"></span> <?php _e('Last Scrape Date', 'wp-review-slider-pro'); ?></div>
        <div class="w3-right"></div>
        <h1 class="w3-text-white" style="padding-top: 30px;"><?php 
		if($statsarray['lastjobdatetime']>0){
		echo date_i18n( get_option( 'date_format' ), $statsarray['lastjobdatetime'] ); 
		} else {
			echo "-";
		}
		?></h1>
      </div>
    </div>
 </div>
 
   <?php
  
  if(count($currentforms)<1 && $rt!=''){
	  echo '<div class="" id="wprevpro_newinfomsg">
		<div class="w3-border w3-margin-top">
			<header class="w3-container w3-light-grey">
			  <h3 class="welcomecardheader">About Review Funnels</h3>
			</header>
			<div class="w3-container">
			  <p>';
	  echo sprintf(__( 'Review Funnels use a third party service to download reviews. They allow you to get reviews from many more sites above the built-in download method.<br> Fill out the form below to create a Review Funnel to get reviews from <b>%1$s</b>! <br><br><b>Note:</b> Since Review Funnels use a paid third-party service, this site will get 2,000 free review credits every year as long as the license is active.', 'wp_fb-reviews' ),$rt);
	  echo '</p></div></div></div>';
  }
  
  ?>

	
  <div class="wprevpro_margin10" id="wprevpro_new_template">
<form name="newtemplateform" id="newtemplateform" action="?page=wp_pro-reviewfunnel" method="post">
	<table class="wprevpro_margin10 form-table ">
		<tbody>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Funnel Name', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<input id="wprevpro_template_title" data-custom="custom" type="text" name="wprevpro_template_title" placeholder="" value="<?php echo stripslashes($currentreviewfunnel->title); ?>" required>
					<p class="description">
					<?php _e('Enter a unique name for this Review Funnel. This is usually the name of the business or product.', 'wp-review-slider-pro'); ?>		</p>
				</td>
			</tr>
			<tr class="wprevpro_row choosesitetr">
				<th scope="row">
					<?php _e('Choose Review Site', 'wp-review-slider-pro'); ?>
				</th>
				<td><div id="divsitetype">
						<select name="wprevpro_site_type" id="wprevpro_site_type">
						<?php
						$typearray = unserialize(WPREV_TYPE_ARRAY_RF);
						  for($x=0;$x<count($typearray);$x++){
						?>
							<option value="<?php echo $typearray[$x];?>" <?php if($currentreviewfunnel->site_type==$typearray[$x]){echo "selected";} ?>><?php echo $typearray[$x];?></option>
						<?php
						  }
						?>
						</select>
					</div>
					<p class="description">
					<?php _e('This is the site you are downloading the reviews from.', 'wp-review-slider-pro'); ?>
					<div id="sitetypenote" class="description"></div>
					</p>
				</td>
			</tr>
			<tr class="wprevpro_row notforgoogle">
				<th scope="row">
					<?php _e('Review Source URL', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<input class="yelp_business_url" id="wprevpro_url" data-custom="custom" type="url" name="wprevpro_url" placeholder="" value="<?php echo urldecode($currentreviewfunnel->url); ?>" required>
					<p class="description">
					<?php _e('The URL of the page where the reviews are located. Needs to be publicly visible with a web browser.', 'wp-review-slider-pro'); ?>
					<div id="urlnote" class="description"></div>
					</p>
					
				</td>
			</tr>
			<tr class="wprevpro_row fromdaterow">
				<th scope="row">
					<?php _e('From Date', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<input class="" id="wprevpro_from_date" data-custom="custom" type="date" name="wprevpro_from_date" placeholder="" value="<?php echo $currentreviewfunnel->from_date; ?>" required>
					<p class="description">
					<?php _e('Only scrape reviews from a specific past date. Set this older than the oldest review you want to download. Allows you to limit the number of Review Credits used. If you don\'t have a datepicker then use the format yyyy-mm-dd. EX: 2019-01-01', 'wp-review-slider-pro'); ?>		</p>
				</td>
			</tr>
			<tr class="wprevpro_row forgoogle">
				<th scope="row">
					<?php _e('Google Info', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<select name="wprevpro_placeidorterms" id="wprevpro_placeidorterms">
						<option value="placeid" <?php if($currentreviewfunnel->gplaceorsearch=='' || $currentreviewfunnel->gplaceorsearch=='placeid'){echo "selected";} ?>><?php _e('Place ID', 'wp-review-slider-pro'); ?></option>
						<option value="terms" <?php if($currentreviewfunnel->gplaceorsearch=='terms'){echo "selected";} ?>><?php _e('Search Terms', 'wp-review-slider-pro'); ?></option>
						
					</select>
					
					<input class="yelp_business_url gsearch" id="wprevpro_query" data-custom="custom" type="text" name="wprevpro_query" placeholder="<?php _e('Enter Google search terms.', 'wp-review-slider-pro'); ?>" value="<?php echo stripslashes($currentreviewfunnel->query); ?>">
					<p id="googledesc1" class="description gsearch">
					<?php _e('These are the search terms you enter in Google to get your business to show up on the right hand side of the Google results. Generate the query with the full name of the business and then the city and state for best results. Sometimes you need the full address. ', 'wp-review-slider-pro'); ?><a href="https://ljapps.com/how-to-download-all-your-google-business-reviews-using-wp-review-slider-pro-review-funnel/" target="_blank"><?php _e('More Info', 'wp-review-slider-pro'); ?></a></p>
					
					<input class="yelp_business_url gplaceid" id="wprevpro_googleplaceid" data-custom="custom" type="text" name="wprevpro_googleplaceid" placeholder="<?php _e('Enter Google Place ID', 'wp-review-slider-pro'); ?>" value="<?php echo stripslashes($currentreviewfunnel->googleplaceid); ?>">

					<p id="googledesc2" class="description gplaceid">
					<?php _e('This is the Google Place ID for the location. ', 'wp-review-slider-pro'); ?><a href="https://ljapps.com/two-methods-to-find-your-google-place-id/" target="_blank"><?php _e('How to find Place ID.', 'wp-review-slider-pro'); ?></a></p>
					
				
					
				</td>
			</tr>
			<tr class="wprevpro_row maxnumrow">
				<th scope="row">
					<?php _e('Max Number of Reviews to Return', 'wp-review-slider-pro'); ?>
				</th>
				<td><div id="divsitetype">
						<input class="" style="width: 70px;" id="wprevpro_blocks" data-custom="custom" type="number" name="wprevpro_blocks" placeholder="" max="<?php if($currentreviewfunnel->id==''){echo $revcreditsleft;} ?>" value="<?php echo $currentreviewfunnel->blocks; ?>" >
					</div>
					<p class="description">
					<?php _e('The maximum number of reviews you wish to download. Set this to a really large number if you need to grab all your reviews. Set to a small number to save review credits.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
			
			
			<tr class="wprevpro_row facebooknumrow">
				<th scope="row">
					<?php _e('Number of Reviews', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<select name="wprevpro_fb_num" id="wprevpro_fb_num">
					<option value="10" <?php if($currentreviewfunnel->fb_num=='10'){echo "selected";} ?>><?php _e('10', 'wp-review-slider-pro'); ?></option>
					<option value="fall" <?php if($currentreviewfunnel->fb_num=='fall'){echo "selected";} ?>><?php _e('all', 'wp-review-slider-pro'); ?></option>
					</select>
					<p class="description">
					<?php _e('For Facebook, we can currently try to grab 10 most helpful reviews or All of the reviews.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
			
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Last Name Save Option', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<input type="radio" name="wprevpro_last_name" value="full" <?php if($currentreviewfunnel->last_name=='full' || $currentreviewfunnel->last_name==''){echo "checked";} ?>>Full Last Name&nbsp;&nbsp;&nbsp;
					<input type="radio" name="wprevpro_last_name" value="initial" <?php if($currentreviewfunnel->last_name=='initial' ){echo "checked";} ?>>Initial Only&nbsp;&nbsp;&nbsp;
					<input type="radio" name="wprevpro_last_name" value="nothing" <?php if($currentreviewfunnel->last_name=='nothing' ){echo "checked";} ?>>Nothing
					<p class="description">
					<?php _e('Set this to change the way the last name is saved in your database. You can also hide the last name when creating a review template.', 'wp-review-slider-pro'); ?>		</p>
				</td>
			</tr>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Local Profile Images', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<input type="radio" name="wprevpro_profile_img" value="no" <?php if($currentreviewfunnel->profile_img=='no' || $currentreviewfunnel->profile_img==''){echo "checked";} ?>>No&nbsp;&nbsp;&nbsp;
					<input type="radio" name="wprevpro_profile_img" value="yes" <?php if($currentreviewfunnel->profile_img=='yes' ){echo "checked";} ?>>Yes&nbsp;&nbsp;&nbsp;
					<p class="description">
					<?php _e('By default, avatar images are referenced from the original review site. Set this to yes if you would like the plugin to try and save the profile images locally. This may not always work as the remote site might block the download.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>

			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Auto Download Reviews', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<select name="wprevpro_cron_setting" id="wprevpro_cron_setting">
					<option value="" <?php if($currentreviewfunnel->cron==''){echo "selected";} ?>><?php _e('No', 'wp-review-slider-pro'); ?></option>
					<option value="672" <?php if($currentreviewfunnel->cron=='672'){echo "selected";} ?>><?php _e('Once a Month', 'wp-review-slider-pro'); ?></option>
					<option value="336" <?php if($currentreviewfunnel->cron=='336'){echo "selected";} ?>><?php _e('Every 14 Days', 'wp-review-slider-pro'); ?></option>
					<option value="168" <?php if($currentreviewfunnel->cron=='168'){echo "selected";} ?>><?php _e('Every 7 Days', 'wp-review-slider-pro'); ?></option>
					<option value="48" <?php if($currentreviewfunnel->cron=='48'){echo "selected";} ?>><?php _e('Every Other Day', 'wp-review-slider-pro'); ?></option>
					<option value="24" <?php if($currentreviewfunnel->cron=='24'){echo "selected";} ?>><?php _e('Once a Day', 'wp-review-slider-pro'); ?></option>
					</select>
					<p class="description">
					<?php _e('Automatically request a new scrape job and download the reviews. <b>Warning:</b> It will cost <b>10 review credits</b> every time this runs, even if no new reviews are found.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Post Categories', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<input class="wprevpro_nr_categories" id="wprevpro_nr_categories" data-custom="custom" type="text" name="wprevpro_nr_categories" placeholder="" value="<?php echo $this->wprev_jsontocommastr($currentreviewfunnel->categories); ?>">
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
					<?php _e('Post IDs', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<input class="wprevpro_nr_postid" id="wprevpro_nr_postid" data-custom="custom" type="text" name="wprevpro_nr_postid" placeholder="" value="<?php echo $this->wprev_jsontocommastr($currentreviewfunnel->posts); ?>" >
					<span class="description"><a id="wprevpro_btn_pickpostids" class="button dashicons-before dashicons-yes "><?php _e('Select Post IDs', 'wp-review-slider-pro'); ?></a>
					<?php _e('Optional: Single or comma separated list of post IDs. Allows you to associate the reviews with multiple posts or page IDs when they are downloaded. You can then use the Post filter for the template. ex: 11', 'wp-review-slider-pro'); ?>		</span>
				</td>
			</tr>

		</tbody>
	</table>
	<?php 
	//security nonce
	wp_nonce_field( 'wprevpro_save_template');
	?>
	<input type="hidden" name="edittid" id="edittid"  value="<?php echo $currentreviewfunnel->id; ?>">
	<input type="hidden" name="reviewlistpageid" id="reviewlistpageid"  value="<?php echo $currentreviewfunnel->reviewlistpageid; ?>">
	<input type="hidden" name="editpluginversion" id="editpluginversion"  value="<?php echo $currentreviewfunnel->pluginversion; ?>">
	<input type="submit" name="wprevpro_submittemplatebtn" id="wprevpro_submittemplatebtn" class="button button-primary" value="<?php _e('Save Review Funnel', 'wp-review-slider-pro'); ?>">
	<a id="wprevpro_addnewtemplate_cancel" class="button button-secondary"><?php _e('Cancel', 'wp-review-slider-pro'); ?></a>
	</form>
</div>


<?php

//display message
echo $dbmsg;
if($rt=='' || count($currentforms)>0){
		$html .= '
		<table class="wp-list-table widefat striped posts">
			<thead>
				<tr>
					<th scope="col" width="50px" class="manage-column">'.__('ID', 'wp-review-slider-pro').'</th>
					<th scope="col" class="manage-column">'.__('Title <br>URL or Query', 'wp-review-slider-pro').'</th>
					<th scope="col" width="115px" class="manage-column">'.__('From Date<br> or Number', 'wp-review-slider-pro').'</th>
					<th scope="col" width="125px" class="manage-column">'.__('Type', 'wp-review-slider-pro').'</th>
					<th scope="col" width="80px" class="manage-column">'.__('Cron', 'wp-review-slider-pro').'</th>
					<th scope="col" width="390px" class="manage-column">'.__('Action', 'wp-review-slider-pro').'</th>
				</tr>
				</thead>
			<tbody id="reviewfunnelstable">';
	if(count($currentforms)>0){
	foreach ( $currentforms as $currentform ) 
	{
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
			$tempurlhtml = substr(urldecode($currentform->url),0,90);
			if(strlen(urldecode($currentform->url))>100){
				$tempurlhtml = $tempurlhtml ."...";
			}
		}
		$tempplaceidorquery = '';
		if($currentform->gplaceorsearch=='placeid'){
			$tempplaceidorquery = $currentform->googleplaceid;
		} else {
			$tempplaceidorquery = stripslashes($currentform->query);
		}
			
		$html .= '<tr id="'.$currentform->id.'" class="locationrow">
				<th scope="col" class=" manage-column" style="min-width: 50px;">'.$currentform->id.'</th>
				<th scope="col" class=" manage-column" style="min-width: 150px;"><b><span class="titlespan">'.stripslashes($currentform->title).'</span></b><br><span style="font-size:10px;">'.$tempurlhtml.''.$tempplaceidorquery.'</span></th>
				<th scope="col" class=" manage-column"><b>'.$currentform->from_date.':'.$tempblocks.'</b></th>
				<th scope="col" class=" manage-column"><b>'.$currentform->site_type.'</b></th>
				<th scope="col" class=" manage-column"><b>'.$currentform->cron.'</b></th>
				<th scope="col" class="manage-column" templateid="'.$currentform->id.'" templatetype="'.$currentform->site_type.'"><a href="'.$url_tempeditbtn.'" class="rfbtn button button-secondary dashicons-before dashicons-admin-generic">'.__('Edit', 'wp-review-slider-pro').'</a> <a href="'.$url_tempdelbtn.'" class="rfbtn button button-secondary dashicons-before dashicons-trash">'.__('Delete', 'wp-review-slider-pro').'</a> <a href="'.$url_tempcopybtn.'" class="rfbtn button button-secondary dashicons-before dashicons-admin-page">'.__('Copy', 'wp-fb-reviews').'</a> <span class="rfbtn button button-primary dashicons-before dashicons-star-filled retreviewsbtn"> '.__('Get Reviews', 'wp-fb-reviews').'</span></th>
			</tr>';
	}
	} else {
		if($rt==''){
		$html .= __('<tr><td colspan="7">You can create a Review Funnel to download reviews from even more sites! Click the "Add New Review Funnel" button above to get started.<br><br><b>Note:</b> Review Funnels use a third party service to download reviews, so you are limited to 2,000 free review credits per a year per a site. You must also have an active license for this plugin.</td></tr>', 'wp-review-slider-pro');
		}
	}
		$html .= '</tbody></table>';
echo $html;
}
//echo "<div></br>Coming Soon! Review Funnels will give you a way to download reviews from more than 40 different sites!</br></br></div>"; 

?>

<div id="retreivewspopupdiv" class="wprevpro_hide">

<div id="scrapeinstructionsdiv" style="display:none;">
<?php _e('<p>Use the button below to request our remote server to scrape a URL that contains your reviews. Once the job is done, it will show up in the table below so that you can download your reviews.</p><p>"Only Scrape New Reviews" can only be used after you have at least one complete scrape job. It will only download reviews from your last scrape job.</p> <p><b>Note:</b> When you make a request to scrape a webpage it will cost (<b>10 review credits + the # of reviews scraped</b>) against your Review Credits, even if you don\'t download them. Choose the "Only Scrape New Reviews" option after your first scrape to save review credits.', 'wp-review-slider-pro'); ?>

</p></div>

<table id="popupjobtable" class="joblisttable w3-table w3-bordered w3-striped w3-border">
<tbody>
<tr class="trrfrow">
	<td colspan="4" style="min-width: 420px;">
	<input type="radio" id="lastscrape" name="scrapedatechoice" value="usediff" checked><label for="lastscrape"><?php _e('Only Scrape New Reviews', 'wp-review-slider-pro'); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  <input type="radio" id="fromdate" name="scrapedatechoice" value="nodiff"><label for="fromdate"><?php _e('Use From Date or Max Number', 'wp-review-slider-pro'); ?></label>
	</td>
	<td colspan="2"><span class="requestscrapebtn button button-secondary"><?php _e('Request New Scrape', 'wp-review-slider-pro'); ?></span><div id="btnspinner" style="display:none;" class="loadingspinner"></div><span id="btnclickmes" class="greenfont" style="display:none;"></span>
	</td>
</tr>
<tr class="trrfrow joblisttoprow">
   <th><?php _e('Job&nbsp;ID', 'wp-review-slider-pro'); ?> <span class="btnrefreshjoblist dashicons dashicons-update" style="cursor:pointer;" title="refresh job list" alt="refresh job list"></span></th>
   <th><?php _e('Ran&nbsp;On', 'wp-review-slider-pro'); ?></th>
  <th><?php _e('#&nbsp;of<br>Reviews', 'wp-review-slider-pro'); ?></th>
   <th><?php _e('Avg<br>Rating', 'wp-review-slider-pro'); ?></th>
  <th><?php _e('Status<br>%&nbsp;Done', 'wp-review-slider-pro'); ?></th>
  <th></th>
</tr>
<tr class="trrfrowloading">
	<td colspan="6">
<div class="loadingspinner"></div>
	</td>
</tr>
</tbody>
</table>
</div>

	<div id="popup_review_list" class="popup-wrapper wprevpro_hide">
	  <div class="popup-content">
		<div class="popup-title">
		  <button type="button" class="popup-close">&times;</button>
		  <h3 id="popup_titletext"></h3>
		</div>
		<div class="popup-body">
		  <div id="popup_bobytext1"></div>
		  <div id="popup_bobytext2"></div>
		</div>
	  </div>
	</div>
	

</div>
<?php
//echo "<br><br><br>";
//print_r($licensecheckarray);
?>
</div>
