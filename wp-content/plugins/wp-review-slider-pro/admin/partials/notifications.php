<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    WP_Review_Pro
 * @subpackage WP_Review_Pro/admin/partials
 */
 
     // check user capabilities
if (!current_user_can('manage_options') && $this->wprev_canuserseepage('notifications')==false) {
        return;
    }

	    // wordpress will add the "settings-updated" $_GET parameter to the url
    if (isset($_GET['settings-updated'])) {
        // add settings saved message with the class of "updated"
        add_settings_error('tripadvisor-radio', 'wprevpro_message', __('Settings Saved', 'wp-review-slider-pro'), 'updated');
    }

	if(isset($this->errormsg)){
		add_settings_error('tripadvisor-radio', 'wprevpro_message', __($this->errormsg, 'wp-review-slider-pro'), 'error');
	}
	
	//default values for new notifications
	$dbmsg = "";
	$html="";
	$currenttemplate= new stdClass();
	$currenttemplate->id="";
	$currenttemplate->title="";
	$currenttemplate->source_page="";
	$currenttemplate->site_type="";
	$currenttemplate->rate_op="<";
	$currenttemplate->rate_val="3";
	$currenttemplate->email=get_option('admin_email');
	$currenttemplate->email_subject=__('New Reviews Notification - WP Review Slider Pro', 'wp-review-slider-pro');
	$currenttemplate->email_first_line=__('<b>WP Review Slider Pro</b> found the following reviews that match your notification settings.', 'wp-review-slider-pro');
	$currenttemplate->enable="";

	global $wpdb;
	$table_name_notify = $wpdb->prefix . 'wpfb_nofitifcation_forms';
	
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
				$wpdb->delete( $table_name_notify, array( 'id' => $tid ), array( '%d' ) );
			}
			//for updating
			if($_GET['taction'] == "edit" && $_GET['tid'] > 0){
				//security
				check_admin_referer( 'tedit_');
				//get form array
				$currenttemplate = $wpdb->get_row( "SELECT * FROM ".$table_name_notify." WHERE id = ".$tid );
			}
			//for copying
			if($_GET['taction'] == "copy" && $_GET['tid'] > 0){
				//security
				check_admin_referer( 'tcopy_');
				//get form array
				$currenttemplate = $wpdb->get_row( "SELECT * FROM ".$table_name_notify." WHERE id = ".$tid );
				//add new template
				$array = (array) $currenttemplate;
				$array['title'] = $array['title'].'_copy';
				
				unset($array['id']);
				//print_r($array);
				//remove the id so it can be generated.
				$wpdb->insert( $table_name_notify, $array );
				//$wpdb->show_errors();
				//$wpdb->print_error();
			}
		}
		
	}
	
	//----updating the post ids for loading js and css
	if (isset($_POST['wprevpro_submitpostids'])){
		if (isset($_POST['wprevpro_jscsspages'])){
			
			if($_POST['wprevpro_jscsspages']==''){
				update_option( 'wprev_jscssposts', '' );
			} else {
				$tempids = sanitize_text_field($_POST['wprevpro_jscsspages']);
				$tempids = trim($tempids);
				$tempids = str_replace(" ","",$tempids);
				$tempidsarray = explode(",",$tempids);
				
				//$tempidsjson = json_encode($tempidsarray);
				update_option( 'wprev_jscssposts', $tempidsarray );
			}
		}
		if (isset($_POST['wprevpro_csspages'])){
			if($_POST['wprevpro_csspages']==''){
				update_option( 'wprev_cssposts', '' );
			} else {
				$tempidscss = sanitize_text_field($_POST['wprevpro_csspages']);
				$tempidscss = trim($tempidscss);
				$tempidscss = str_replace(" ","",$tempidscss);
				$tempidsarraycss = explode(",",$tempidscss);
				
				//$tempidsjson = json_encode($tempidsarray);
				update_option( 'wprev_cssposts', $tempidsarraycss );
			}
		}
		$dbmsg = '<div id="setting-error-wprevpro_message" class="updated settings-error notice is-dismissible"><p><strong>'.__('Specify JS and CSS Pages Setting Updated!', 'wp-review-slider-pro').'</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">'.__('Dismiss this notice.', 'wp-review-slider-pro').'</span></button></div>';
		
	}
	
	//for language translator
	//save values if we need to..
	if (isset($_POST['wprevpro_submitranslatorform'])){

		check_admin_referer( 'wprevpro_save_languagetranslatorform');
		$ltavarray['lang_api_key'] = sanitize_text_field($_POST['wprevpro_lang_api_key']);
		$ltavarray['lang_targetlang'] = sanitize_text_field($_POST['wprevpro_lang_targetlang']);
		$ltavarray['lang_autorun'] = sanitize_text_field($_POST['wprevpro_lang_autorun']);

		$ltsavejson = json_encode($ltavarray);
		update_option( 'wprev_languagetranslator', $ltsavejson );
		$dbmsg = '<div id="setting-error-wprevpro_message" class="updated settings-error notice is-dismissible"><p><strong>'.__('Settings Updated!', 'wp-review-slider-pro').'</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">'.__('Dismiss this notice.', 'wp-review-slider-pro').'</span></button></div>';
	}
	
	//save autorevs get settings.
	
	if (isset($_POST['wprevpro_submitautogetrevsform'])){

		check_admin_referer( 'wprevpro_save_autogetrevsform');
		$agrfarray['autogetrevs_type'] = sanitize_text_field($_POST['wprevpro_autogetrevs_type']);
		$agrfarray['autogetrevs_posttype'] = sanitize_text_field($_POST['wprevpro_autogetrevs_posttype']);
		$agrfarray['autogetrevs_cfn'] = sanitize_text_field($_POST['wprevpro_autogetrevs_cfn']);
		$agrfarray['autogetrevs_hourly'] = sanitize_text_field($_POST['wprevpro_autogetrevs_hourly']);
		//============================
		//=======if $agrfarray['autogetrevs_hourly'] is set then we need to setup a cron job here if not set. unset if set to no.
		if($agrfarray['autogetrevs_hourly']=='yes'){
			if (! wp_next_scheduled ( 'wprevpro_autogetrevs_hourly' )) {
				wp_schedule_event(time(), 'hourly', 'wprevpro_autogetrevs_hourly');  
			}
		} else {
			$timestamp = wp_next_scheduled( 'wprevpro_autogetrevs_hourly' );
			wp_unschedule_event( $timestamp, 'wprevpro_autogetrevs_hourly' );
		}
		//=============================
		$agrfarray['autogetrevs_langcode'] = sanitize_text_field($_POST['wprevpro_autogetrevs_langcode']);
		$agrfarray['autogetrevs_which'] = sanitize_text_field($_POST['wprevpro_autogetrevs_which']);
		$agrfarray['autogetrevs_cron'] = sanitize_text_field($_POST['wprevpro_autogetrevs_cron']);
		$agrfsavejson = json_encode($agrfarray);
		update_option( 'wprev_autogetrevs', $agrfsavejson );
		$dbmsg = '<div id="setting-error-wprevpro_message" class="updated settings-error notice is-dismissible"><p><strong>'.__('Settings Updated!', 'wp-review-slider-pro').'</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">'.__('Dismiss this notice.', 'wp-review-slider-pro').'</span></button></div>';
	}
	
	
	
	//for updating hide revs on downloaded
	if (isset($_POST['wprevpro_submithiderevondown'])){
		if (isset($_POST['wprevpro_hiderevondownload'])){
			if($_POST['wprevpro_hiderevondownload']==''){
				update_option( 'wprev_hideondownload', '' );
			} else {
				$tempval = sanitize_text_field($_POST['wprevpro_hiderevondownload']);
				update_option( 'wprev_hideondownload', $tempval );
			}
			$dbmsg = '<div id="setting-error-wprevpro_message" class="updated settings-error notice is-dismissible"><p><strong>'.__('Settings Updated!', 'wp-review-slider-pro').'</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">'.__('Dismiss this notice.', 'wp-review-slider-pro').'</span></button></div>';
		}
	}

	//----updating the admin pages visibility based on role or current user can
	$pagedisplaynamearray = [0 => "Welcome",1 => "Get Reviews",2 => "Review Funnels",3 => "Review List",4 => "Templates",5 => "Badges",6 => "Forms",7 => "Floats",8 => "Analytics",9 => "Tools",10 => "Forum",];
	$pagenamearray = [0 => "welcome",1 => "getrevs",2 => "reviewfunnel",3 => "reviews",4 => "templates_posts",5 => "badges",6 => "forms",7 => "float",8 => "analytics",9 => "notifications",10 => "forum",];
	
	//echo $pagenamearray[5];
	//print_r($pagenamearray);
	//echo "<br><br>";
	if (isset($_POST['wprevpro_adminpages'])){
		$rolesadminjson = '';
		$wprevrolesarray=Array();
		$wprevroles_json ='';
		
		for ($x = 0; $x <= 10; $x++) {
			if(isset($_POST['wprevroles'.$x])){
				$wprevrolesarray[$pagenamearray[$x]] = $_POST['wprevroles'.$x];
			}
		}
		if(count($wprevrolesarray)>0){
			//print_r($wprevrolesarray);
			$wprevroles_json = json_encode($wprevrolesarray);
			//update the option in wordpress
			update_option( 'wprev_rolepages', $wprevroles_json );
		} else {
			update_option( 'wprev_rolepages', '' );
		}
	}
	
	//for saving google rating xml settings.
	$upload = wp_upload_dir();
$upload_dir = $upload['baseurl'];
$googleprodfileurlval = $upload_dir."/wprevslider/product_reviews.xml";
$upload_dir = $upload['basedir'];
$googleprodfiledir = $upload_dir."/wprevslider/product_reviews.xml";
	if (isset($_POST['wprevpro_submitgoogleprodrating'])){
		
		//verify nonce wp_nonce_field( 'wprevpro_save_googleprodrating');
		check_admin_referer( 'wprevpro_save_googleprodrating');
		$gprsavarray['createxml'] = sanitize_text_field($_POST['wprevpro_googleprodratingxml']);
		//$gprsavarray['gpr_gtin'] = sanitize_text_field($_POST['wprevpro_gpr_gtin']);
		//$gprsavarray['gpr_mpn'] = sanitize_text_field($_POST['wprevpro_gpr_mpn']);
		//$gprsavarray['gpr_brand'] = sanitize_text_field($_POST['wprevpro_gpr_brand']);
		$gprsavejson = json_encode($gprsavarray);
		update_option( 'wprev_googleprodratingxml', $gprsavejson );
		
		if($gprsavarray['createxml']=='yes'){
		
		require_once WPREV_PLUGIN_DIR . 'admin/class-wp-review-slider-pro-admin_hooks.php';
		$plugin_admin_hooks = new WP_Review_Pro_Admin_Hooks( $this->get_token(), $this->get_version() );
		$createxmlfile = $plugin_admin_hooks->createGoogleProductXMLFile($googleprodfiledir);
		

			if(!$createxmlfile){
				$dbmsg = '<div id="setting-error-wprevpro_message" class="updated settings-error notice is-dismissible"><p><strong>'.__('Error Creating XML File', 'wp-review-slider-pro').':</strong> '.__('Please contact support.', 'wp-review-slider-pro').'</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">'.__('Dismiss this notice.', 'wp-review-slider-pro').'</span></button></div>';
			} else {
				//$msgprodrevxmlsave = "$googleprodfiledir has been successfully created";
				$dbmsg = '<div id="setting-error-wprevpro_message" class="updated settings-error notice is-dismissible"><p><strong>'.__('XML File Created!', 'wp-review-slider-pro').'</strong> <a href="'.$googleprodfileurlval.'" target="_blank"> '.$googleprodfileurlval.'</a></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">'.__('Dismiss this notice.', 'wp-review-slider-pro').'</span></button></div>';
			}
		}

	}
	
	
	//echo get_option('wprev_rolepages');
	
	

	//form posting here--------------------------------
	//check to see if form has been posted.
	//if template id present then update database if not then insert as new.
	//db function variables
	if (isset($_POST['wprevpro_submittemplatebtn'])){
		//verify nonce wp_nonce_field( 'wprevpro_save_template');
		check_admin_referer( 'wprevpro_save_template');
		//get form submission values and then save or update
		$t_id = sanitize_text_field($_POST['edittid']);
		$title = sanitize_text_field($_POST['wprevpro_template_title']);
		$source_page = array();
		$souce_page_json ='';
		if(isset($_POST['source_pages'])){
			$source_page = $_POST['source_pages'];
			$souce_page_json = json_encode($source_page);
		}
		$site_type = array();
		$site_type_json ='';
		if(isset($_POST['site_types'])){
			$site_type = $_POST['site_types'];
			$site_type_json = json_encode($site_type);
		}
		$rate_op = $_POST['wprevpro_rate_op'];
		$rate_val = sanitize_text_field($_POST['wprevpro_rate_val']);
		$rate_val = intval($rate_val);
		$email = sanitize_text_field($_POST['wprevpro_email']);
		$email_subject = sanitize_text_field($_POST['wprevpro_email_subject']);
		$email_first_line = htmlentities($_POST['wprevpro_email_first_line']);
		$enable = sanitize_text_field($_POST['wprevpro_enable']);

		$timenow = time();
		
		//+++++++++need to sql escape using prepare+++++++++++++++++++
		//+++++++++++++++++++++++++++++++++++++++++++++++++++++
		//insert or update
			$data = array( 
				'title' => "$title",
				'source_page' => "$souce_page_json",
				'site_type' => "$site_type_json",
				'created_time_stamp' => "$timenow",
				'rate_op' => "$rate_op",
				'rate_val' => "$rate_val",
				'email' => "$email",
				'email_subject' => "$email_subject",
				'email_first_line' => "$email_first_line",
				'enable' => "$enable",
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
				); 

		if($t_id==""){
			//print_r($data);
			//insert
			$insertrow = $wpdb->insert( $table_name_notify, $data, $format );
			if(!$insertrow){
			//$wpdb->show_errors();
			//$wpdb->print_error();
			$dbmsg = $dbmsg.'<div id="setting-error-wprevpro_message" class="error settings-error notice is-dismissible">'.__('<p><strong>Oops! This form could not be inserted in to the database.</br> -'.$wpdb->show_errors().' -'.$wpdb->print_error().' </strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>', 'wp-review-slider-pro').'</div>';
			}
			//die();
		} else {
			//update
			//print_r($data);
			$updatetempquery = $wpdb->update($table_name_notify, $data, array( 'id' => $t_id ), $format, array( '%d' ));
			//$wpdb->show_errors();
			//$wpdb->print_error();
			if($updatetempquery>0){
				$dbmsg = '<div id="setting-error-wprevpro_message" class="updated settings-error notice is-dismissible"><p><strong>'.__('Settings Updated!', 'wp-review-slider-pro').'</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">'.__('Dismiss this notice.', 'wp-review-slider-pro').'</span></button></div>';
			} else {
				$wpdb->show_errors();
				$wpdb->print_error();
				$dbmsg = '<div id="setting-error-wprevpro_message" class="updated settings-error notice is-dismissible"><p><strong>'.__('Error', 'wp-review-slider-pro').':</strong> '.__('Unable to update. Please contact support.', 'wp-review-slider-pro').'</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">'.__('Dismiss this notice.', 'wp-review-slider-pro').'</span></button></div>';
			}
		}
		
		
	}
	
	//update admin url here
	//
	//if (isset($_POST['wprevpro_submitadmbinurlbtn'])){
	//	$newadminurl = sanitize_text_field($_POST['wprevpro_admin_url']);
	//	update_option( 'wprevpro_admin_url',$newadminurl);
	//}
	
	
	//Get list of all current forms--------------------------
	$currentforms = $wpdb->get_results("SELECT * FROM $table_name_notify ORDER BY id DESC");
	//-------------------------------------------------------	
	

	
	
?>
<div class="wrap wp_pro-settings" id="">
	<h1><img src="<?php echo plugin_dir_url( __FILE__ ) . 'logo.png'; ?>"></h1>
<?php 
include("tabmenu.php");
?>
<div class="wprevpro_margin10">


<?php
if ( wrsp_fs()->can_use_premium_code() ) {
?>

<div class="notifications_sections">

	<h3><?php _e('Review Notifications', 'wp-review-slider-pro'); ?></h3>
	<p><?php _e('Allows you to get email notifications of new reviews based on certain rules. This is only for downloaded reviews. For submitted reviews use the setting on the review Form page.', 'wp-review-slider-pro'); ?></p>

	<div class="wprevpro_margin10">
		<a id="wprevpro_addnewtemplate" class="button dashicons-before dashicons-plus-alt"><?php _e('Add New Notification', 'wp-review-slider-pro'); ?></a>
	</div>

	  <div class="wprevpro_margin10" id="wprevpro_new_template">
	<form name="newtemplateform" id="newtemplateform" action="?page=wp_pro-notifications" method="post">
		<table class="wprevpro_margin10 form-table ">
			<tbody>
				<tr class="wprevpro_row">
					<th scope="row">
						<?php _e('Title:', 'wp-review-slider-pro'); ?>
					</th>
					<td>
						<input id="wprevpro_template_title" data-custom="custom" type="text" name="wprevpro_template_title" placeholder="" value="<?php echo $currenttemplate->title; ?>" required>
						<p class="description">
						<?php _e('Enter a unique name for this notification.', 'wp-review-slider-pro'); ?>		</p>
					</td>
				</tr>
				<tr class="wprevpro_row">
					<th scope="row">
						<?php _e('Filter by Source Page(s):', 'wp-review-slider-pro'); ?>
					</th>
					<td><div id="divsitetype">
					<select id="wprevpro_source_page" class="js-example-basic-multiple" name="source_pages[]" multiple="multiple" style="width: 100%">
					<?php
					//if editing then we need to add selected attribute if there is a match.
					$pageidarray = array();
					if($currenttemplate->source_page!=''){
						$pageidarray = json_decode($currenttemplate->source_page);
					}
					//var_dump($pageidarray);
					//get current locations
					$reviews_table_name = $wpdb->prefix . 'wpfb_reviews';
					$tempquery = "select * from ".$reviews_table_name." group by pageid";
					$fbpagesrows = $wpdb->get_results($tempquery);
					//var_dump($fbpagesrows);
					foreach ( $fbpagesrows as $fbpage ) 
					{
						//check for previous values
						$isselectedtext = '';
						$temppageid = $fbpage->pageid;
						$temppageidhtmlentities = htmlspecialchars_decode($fbpage->pageid);
						//echo $temppageidhtmlentities;
						if(in_array($temppageid, $pageidarray) || in_array($temppageidhtmlentities, $pageidarray)){
							$isselectedtext = 'selected="selected"';
						}
						echo '<option value="'.$fbpage->pageid.'" '.$isselectedtext.'>'.$fbpage->pagename.' ('.$fbpage->type.')</option>';
					}
					?>
					</select>

						</div>
						<p class="description">
						<?php _e('The original location of the reviews. Leave blank for all reviews.', 'wp-review-slider-pro'); ?></p>
					</td>
				</tr>
				<tr class="wprevpro_row">
					<th scope="row">
						<?php _e('Filter by Review Type:', 'wp-review-slider-pro'); ?>
					</th>
					<td><div id="divsitetype">

						<select id="wprevpro_site_type" class="js-example-basic-multiple" name="site_types[]" multiple="multiple" style="width: 100%">
						<?php
						//if editing then we need to add selected attribute if there is a match.
						$savedtypearray = array();
						if($currenttemplate->site_type!=''){
							$savedtypearray = json_decode($currenttemplate->site_type);
						}
						$tempquery = "select type from ".$reviews_table_name." group by type";
						$typearray = $wpdb->get_col($tempquery);
						
						for($x=0;$x<count($typearray);$x++)
						{
							$typelowercase = strtolower($typearray[$x]);
							//check for previous values
							$isselectedtext = '';
							if(in_array($typelowercase, $savedtypearray)){
								$isselectedtext = 'selected="selected"';
							}
							echo '<option value="'.$typelowercase.'" '.$isselectedtext.'>'.__($typearray[$x], 'wp-review-slider-pro').'</option>';
						}
						?>
						</select>

						</div>
						<p class="description">
						<?php _e('The type of review. Leave blank for all reviews.', 'wp-review-slider-pro'); ?></p>
					</td>
				</tr>
				<tr class="wprevpro_row">
					<th scope="row">
						<?php _e('Rating Rule:', 'wp-review-slider-pro'); ?>
					</th>
					<td><div id="divsitetype">
						<?php _e('If Review Rating is', 'wp-review-slider-pro'); ?>
						<select name="wprevpro_rate_op" id="wprevpro_rate_op">
						  <option value=">" <?php if($currenttemplate->rate_op=='>'){echo "selected";} ?>><?php _e('>', 'wp-review-slider-pro'); ?></option>
						  <option value="=" <?php if($currenttemplate->rate_op=='='){echo "selected";} ?>><?php _e('=', 'wp-review-slider-pro'); ?></option>
						  <option value="<" <?php if($currenttemplate->rate_op=='<'){echo "selected";} ?>><?php _e('<', 'wp-review-slider-pro'); ?></option>
						</select>
						<select name="wprevpro_rate_val" id="wprevpro_rate_val">
						<option value="0" <?php if($currenttemplate->rate_val=='0'){echo "selected";} ?>><?php _e('0', 'wp-review-slider-pro'); ?></option>
						  <option value="1" <?php if($currenttemplate->rate_val=='1'){echo "selected";} ?>><?php _e('1', 'wp-review-slider-pro'); ?></option>
						  <option value="2" <?php if($currenttemplate->rate_val=='2'){echo "selected";} ?>><?php _e('2', 'wp-review-slider-pro'); ?></option>
						  <option value="3" <?php if($currenttemplate->rate_val=='3'){echo "selected";} ?>><?php _e('3', 'wp-review-slider-pro'); ?></option>
						  <option value="4" <?php if($currenttemplate->rate_val=='4'){echo "selected";} ?>><?php _e('4', 'wp-review-slider-pro'); ?></option>
						  <option value="5" <?php if($currenttemplate->rate_val=='5'){echo "selected";} ?>><?php _e('5', 'wp-review-slider-pro'); ?></option>
						  <option value="6" <?php if($currenttemplate->rate_val=='6'){echo "selected";} ?>><?php _e('6', 'wp-review-slider-pro'); ?></option>
						</select>
						.
						</div>
						<p class="description">
						<?php _e('If the rating is greater, equal, or less than this value, send the notification.', 'wp-review-slider-pro'); ?></p>
					</td>
				</tr>
				<tr class="wprevpro_row">
					<th scope="row">
						<?php _e('Email Address:', 'wp-review-slider-pro'); ?>
					</th>
					<td>
						<input class="wprevpro_not_input" id="wprevpro_email" data-custom="custom" type="text" name="wprevpro_email" placeholder="" value="<?php echo $currenttemplate->email; ?>">
						<p class="description">
						<?php _e('Email address of where you would like the notifications sent. This can also be a comma separated list of email addresses.', 'wp-review-slider-pro'); ?>		</p>
					</td>
				</tr>
				<tr class="wprevpro_row">
					<th scope="row">
						<?php _e('Email Subject Title:', 'wp-review-slider-pro'); ?>
					</th>
					<td>
						<input class="wprevpro_not_input" id="wprevpro_email_subject" data-custom="custom" type="text" name="wprevpro_email_subject" placeholder="" value="<?php echo $currenttemplate->email_subject; ?>">
						<p class="description">
						<?php _e('Customize the email subject.', 'wp-review-slider-pro'); ?>		</p>
					</td>
				</tr>
				<tr class="wprevpro_row">
					<th scope="row">
						<?php _e('Email Text Before Reviews:', 'wp-review-slider-pro'); ?>
					</th>
					<td>
						<textarea class="wprevpro_not_input" name="wprevpro_email_first_line" id="wprevpro_email_first_line" cols="60" rows="4" spellcheck="false"><?php echo stripslashes(html_entity_decode($currenttemplate->email_first_line)); ?></textarea>
						<p class="description">
						<?php _e('Customize the text in the email that appears before the list of reviews. It can be plain text or HTML.', 'wp-review-slider-pro'); ?></p>
					</td>
				</tr>
				<tr class="wprevpro_row" >
					<th scope="row">
						<?php _e('Turn On/Off:', 'wp-review-slider-pro'); ?>
					</th>
					<td>
						<input type="radio" name="wprevpro_enable" value="yes" <?php if($currenttemplate->enable=='yes' || $currenttemplate->enable==''){echo "checked";} ?>><?php _e('On', 'wp-review-slider-pro'); ?>&nbsp;&nbsp;&nbsp;
						<input type="radio" name="wprevpro_enable" value="no" <?php if($currenttemplate->enable=='no' ){echo "checked";} ?>><?php _e('Off', 'wp-review-slider-pro'); ?>&nbsp;&nbsp;&nbsp;
						<p class="description">
						<?php _e('Turn this notification on or off. Allows you to pause a notification without deleting it.', 'wp-review-slider-pro'); ?></p>
					</td>
				</tr>
				


			</tbody>
		</table>
		<?php 
		//security nonce
		wp_nonce_field( 'wprevpro_save_template');
		?>
		<input type="hidden" name="edittid" id="edittid"  value="<?php echo $currenttemplate->id; ?>">
		<input type="submit" name="wprevpro_submittemplatebtn" id="wprevpro_submittemplatebtn" class="button button-primary" value="<?php _e('Save', 'wp-review-slider-pro'); ?>">
		<a id="wprevpro_addnewtemplate_cancel" class="button button-secondary"><?php _e('Cancel', 'wp-review-slider-pro'); ?></a>
		</form>
	</div>




	<?php

	//display message
	echo $dbmsg;
			$html .= '
			<table class="wp-list-table widefat striped posts">
				<thead>
					<tr>
						<th scope="col" width="40px" class="manage-column">'.__('ID', 'wp-review-slider-pro').'</th>
						<th scope="col" class="manage-column">'.__('Title', 'wp-review-slider-pro').'</th>
						<th scope="col" width="" class="manage-column">'.__('Email', 'wp-review-slider-pro').'</th>
						<th scope="col" width="" class="manage-column">'.__('Updated', 'wp-review-slider-pro').'</th>
						<th scope="col" width="" class="manage-column">'.__('Enabled', 'wp-review-slider-pro').'</th>
						<th scope="col" width="" class="manage-column"></th>
					</tr>
					</thead>
				<tbody id="appformstable">';
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
			
			$enabledhtml = '';
			if($currentform->enable!='yes'){
				$enabledhtml = "<span style='color:red;'>".$currentform->enable."</span>";
			} else {
				$enabledhtml = "<span style='color:green;'>".$currentform->enable."</span>";
			}
				
			$lastupdated = '';
			if($currentform->created_time_stamp>0){$lastupdated = date("M j, Y",$currentform->created_time_stamp);}
				
			$html .= '<tr id="'.$currentform->id.'">
					<th scope="col" class=" manage-column">'.$currentform->id.'</th>
					<th scope="col" class=" manage-column" style="min-width: 150px;"><b><span class="titlespan">'.$currentform->title.'</span></b></th>
					<th scope="col" class=" manage-column">'.$currentform->email.'</th>
					<th scope="col" class=" manage-column">'.$lastupdated.'</th>
					<th scope="col" class=" manage-column"><b>'.$enabledhtml.'</b></th>
					<th scope="col" class="manage-column" templateid="'.$currentform->id.'" templatetype="'.$currentform->site_type.'"><a href="'.$url_tempeditbtn.'" class="rfbtn button button-secondary dashicons-before dashicons-admin-generic">'.__('Edit', 'wp-review-slider-pro').'</a> <a href="'.$url_tempdelbtn.'" class="rfbtn button button-secondary dashicons-before dashicons-trash">'.__('Delete', 'wp-review-slider-pro').'</a> <a href="'.$url_tempcopybtn.'" class="rfbtn button button-secondary dashicons-before dashicons-admin-page">'.__('Copy', 'wp-fb-reviews').'</a></th>
				</tr>';
		}
		} else {
			$html .= '<tr><td colspan="8">'.esc_html__('You can create a Notification rule so that you get an email when the plugin downloads a new review. Click the "Add New Notification" button above. It helps if you already have reviews on the Review List page.', 'wp-review-slider-pro').' </td></tr>';
		}
			$html .= '</tbody></table>';
	echo $html;


	?>
		<p>The plugin uses the wp_mail() function to send the emails. If they don't come through then try one of the SMTP email plugins.</p>
</div>

<div class="notifications_sections">

<?php
$pagestoloadjs = get_option( 'wprev_jscssposts', '');
if(is_array($pagestoloadjs)){
	$pagestoloadjs = implode(",",$pagestoloadjs);
}

$pagestoloadcss = get_option( 'wprev_cssposts', '');
if(is_array($pagestoloadcss)){
	$pagestoloadcss = implode(",",$pagestoloadcss);
}
?>
	<h3><?php _e('Specify Pages for JS and CSS', 'wp-review-slider-pro'); ?></h3>
	<p><?php _e('Optional: Allows you to specify the Post/Page IDs where you would like the JS and CSS files for this plugin to get added. By default they are added to all pages so you can use the plugin on any page of your site. ', 'wp-review-slider-pro'); ?></p>

<form name="specifycssjspages" id="specifycssjspages" action="?page=wp_pro-notifications" method="post">
	<div class="wprevpro_margin10">
		
		<b>JS Pages:</b> <input id="wprevpro_jscsspages" data-custom="custom" type="text" name="wprevpro_jscsspages" value="<?php echo $pagestoloadjs; ?>"><span class="description "> <?php _e('Comma separated list of Post or Page IDs.', 'wp-review-slider-pro'); ?>
		</span>
		<br><br>
		<b>CSS Pages:</b> <input id="wprevpro_csspages" data-custom="custom" type="text" name="wprevpro_csspages" value="<?php echo $pagestoloadcss; ?>">
		<span class="description"> <?php _e('Comma separated list of Post or Page IDs.', 'wp-review-slider-pro'); ?>
		</span>
			<p class="description ">
			<?php _e('<b>Warning:</b> If you enter a Post/Page ID and then try to use the plugin on a different page it will not work. Recommend that this is left blank.', 'wp-review-slider-pro'); ?>
			
		</p>
		</div>
		<p class="submit">
		<input type="submit" name="wprevpro_submitpostids" id="wprevpro_submitpostids" class="button button-primary" value="<?php _e('Save', 'wp-review-slider-pro'); ?>">
		</p>
		
	
	</form>
</div>



<div class="sections">
	<form action="options.php" method="post">

		<?php
		
		$options = get_option('wprevpro_notifications_settings');
		//print_r($options);
		
		// output security fields for the registered setting "wp_pro-notifications"
		settings_fields('wp_pro-notifications');
		// output setting sections and their fields
		// (sections are registered for "wp_pro-notifications", each field is registered to a specific section)
		do_settings_sections('wp_pro-notifications');
		// output save settings button
		?><?php 
		submit_button('Save');
		?>
	</form>
</div>
	

<div class="notifications_sections">
<?php

 //get saved values
 if(get_option('wprev_languagetranslator')){
	$savelanguagetranslatorjson = get_option('wprev_languagetranslator');
	$savelanguagetranslatorarray = json_decode($savelanguagetranslatorjson,true);
 } else {
	$savelanguagetranslatorarray = Array(); 
 }
	 
?>
	<h3><?php _e('Language Translator', 'wp-review-slider-pro'); ?></h3>
	<p><?php 
	echo sprintf( __( 'Allows you to automatically create translated copies of reviews in another language. Requires <a href="%s" target="_blank">Google Translate</a> API Key.', 'wp-review-slider-pro' ), 
			'https://console.cloud.google.com/apis/library/translate.googleapis.com'
		);
	?>
	</p>
	<p class="description">
		<b><?php _e('Warning:', 'wp-review-slider-pro'); ?></b> <?php
		echo sprintf( __( 'Google provides a certain amount of translations for <a href="%s" target="_blank">free</a> each month. Highly recommend that you set your daily <a href="%s" target="_blank">Quota</a> so that you do not get charged.', 'wp-review-slider-pro' ), 
			'https://cloud.google.com/translate/pricing',
			'https://console.cloud.google.com/apis/api/translate.googleapis.com/quotas'
		);?>
		</p>

	<form name="languagetranslatorform" id="languagetranslatorform" action="?page=wp_pro-notifications" method="post">
		<div class="wprevpro_margin10">
		
		<table class="form-table" role="presentation"><tbody>
		<tr class="wprevpro_row">
		<th scope="row">
		<label for="api_key"><?php _e('Google Translate API Key', 'wp-review-slider-pro'); ?></label></th><td>
		<input class="regular-text" id="wprevpro_lang_api_key" data-custom="custom" type="text" name="wprevpro_lang_api_key" placeholder="" value="<?php echo $savelanguagetranslatorarray['lang_api_key'];?>">
		<p class="description">
		<?php _e('Enter your Google API key.', 'wp-review-slider-pro'); ?> <a href="https://detectlanguage.com/" target="_blank"><?php _e('Instructions', 'wp-review-slider-pro'); ?></a>
		</p>
		
	   </td></tr></tbody>
	   </table>
	   
	   <table id="transtable" class="form-table" role="presentation"><tbody>
		<tr class="wprevpro_row">
		<th scope="row">
		<label><?php _e('Setup Translations', 'wp-review-slider-pro'); ?></label>
		</th><td>
		<table class="w3-table w3-striped w3-border" style="background-color:#fff;color:#000">
		<tbody>
		<tr>
		  <th><?php _e('Target Language(s)', 'wp-review-slider-pro'); ?></th>
		  <th><?php _e('Auto Run Daily', 'wp-review-slider-pro'); ?></th>
		  <th></th>
		</tr>
		<tr>
		  <td>
		  <input id="wprevpro_lang_targetlang" data-custom="custom" type="text" name="wprevpro_lang_targetlang" value="<?php echo $savelanguagetranslatorarray['lang_targetlang'];?>">
		  </td>
		   <td>
		  <select id="wprevpro_lang_autorun" name="wprevpro_lang_autorun">
			<option value="" <?php if($savelanguagetranslatorarray['lang_autorun']!='yes'){echo 'selected="selected"';} ?>>No</option>
			<option value="yes" <?php if($savelanguagetranslatorarray['lang_autorun']=='yes'){echo 'selected="selected"';} ?>>Yes</option>
			</select>
			</td>
		  <td><button id="translaterevs_btn" type="button" class="btn_green"><?php _e('Translate Reviews', 'wp-review-slider-pro'); ?></button></td>
		</tr>
		</tbody></table>
		<p class="description">
		<?php 
		echo sprintf( __( 'Enter single Language Code or comma seperated list of <a href="%s" target="_blank"> Language Codes</a>.', 'wp-review-slider-pro' ), 
			'https://cloud.google.com/translate/docs/languages'
		);
		?>
		</p>


		</td></tr></tbody>
	   </table>

		</div>
		<p class="submit">
		<input type="submit" name="wprevpro_submitranslatorform" id="wprevpro_submitranslatorform" class="button button-primary" value="<?php _e('Save', 'wp-review-slider-pro'); ?>">
		</p>
			<?php 
		//security nonce
		wp_nonce_field( 'wprevpro_save_languagetranslatorform');
		?>
	</form>
</div>	


<div class="notifications_sections">
<?php
$hideondownload = get_option( 'wprev_hideondownload', '');
?>
	<h3><?php _e('Hide Reviews on Download', 'wp-review-slider-pro'); ?></h3>
	<p><?php _e('Optional: Allows you to automatically hide reviews when they are downloaded. You will then need to use the "eye" icon on the Review List page to show the review in a template. ', 'wp-review-slider-pro'); ?></p>

	<form name="hiderevondownload" id="hiderevondownload" action="?page=wp_pro-notifications" method="post">
		<div class="wprevpro_margin10">
			<select id="wprevpro_hiderevondownload" name="wprevpro_hiderevondownload">
			<option value="" <?php if($hideondownload!='yes'){echo 'selected="selected"';} ?>>No</option>
			<option value="yes" <?php if($hideondownload=='yes'){echo 'selected="selected"';} ?>>Yes</option>
			</select>
			
			<p class="description">
				Recommend that this is set to no and then use the Filter on the review template to only show what you want.
			</p>
		</div>
		<p class="submit">
		<input type="submit" name="wprevpro_submithiderevondown" id="wprevpro_submithiderevondown" class="button button-primary" value="<?php _e('Save', 'wp-review-slider-pro'); ?>">
		</p>
	</form>
</div>	


<div class="notifications_sections">

<?php
	 $rolenamesarray = $this->wprev_get_role_names();
	 
	 //get saved values
	 if(get_option('wprev_rolepages')){
		$savedrolesjson = get_option('wprev_rolepages');
		$savedrolesarray = json_decode($savedrolesjson,true);
	 } else {
		$savedrolesarray = Array(); 
	 }
	// print_r($savedrolesarray);
?>
	<h3><?php _e('Show Admin Pages Based on Role', 'wp-review-slider-pro'); ?></h3>
	<p><?php _e('Optional: The Admin role has access to all pages of the plugin and the Editor role has access to the Review List and Analytics pages. If you would like to open the plugin up to more roles, choose them below.', 'wp-review-slider-pro'); ?></p>

	<form name="pageroles" id="pageroles" action="?page=wp_pro-notifications" method="post">
		<div class="wprevpro_margin10">


<?php
	
for ($x = 0; $x <= 10; $x++) {
?>
<div class="pagerow">
<label class="rollabel" for="wprevpro_rolepages<?php echo $x;?>"><?php echo $pagedisplaynamearray[$x]; ?></label>
	<select class="roleselect js-example-basic-multiple" id="wprevpro_rolepages<?php echo $x;?>" name="wprevroles<?php echo $x;?>[]" multiple="multiple">
	<?php
	foreach($rolenamesarray as $label => $val) {
	  if($label!='administrator' && $label!='subscriber'){
		 if (isset($savedrolesarray[$pagenamearray[$x]]) && in_array($label, $savedrolesarray[$pagenamearray[$x]])) {
			 echo "<option selected='selected' value='$label'>$val</option>";
		 } else {
			echo "<option value='$label'>$val</option>"; 
		 }
	  }
	}
	?>
	</select>
</div>	

<?php
}	
?>

		</div>
		<p class="submit">
		<input type="submit" name="wprevpro_adminpages" id="wprevpro_adminpages" class="button button-primary" value="<?php _e('Save', 'wp-review-slider-pro'); ?>">
		</p>
	</form>
</div>
	
	
<div class="notifications_sections">
<?php
	 if(get_option('wprev_googleprodratingxml')){
		$googleprodratingxml = get_option('wprev_googleprodratingxml');
		$googleprodratingxmlarray = json_decode($googleprodratingxml,true);
	 } else {
		$googleprodratingxmlarray = Array(); 
	 }
	 
if(!isset($googleprodratingxmlarray['createxml'])){
	$googleprodratingxmlarray['createxml']='';
}
$hideadfields = '';
if($googleprodratingxmlarray['createxml']!='yes'){
	$hideadfields = "style='display:none;";
}

?>
	<h3><?php _e('Google Product Ratings XML File', 'wp-review-slider-pro'); ?></h3>
	<p><?php _e('If you are a part of the Google Merchant Program, the Product Ratings program allows you to display aggregated reviews for your products to customers shopping on Google. The program requires you to link to a review XML file. This will create the file for you.', 'wp-review-slider-pro'); ?>
	<a href="https://support.google.com/merchants/answer/6059553?hl=en" target="_blank"><?php _e('More Info', 'wp-review-slider-pro'); ?></a>
	<?php
	/*
	var_dump(get_post_meta('1249'));
	echo "<br>";
	
	$allproductmetaarray = Array();
	
	$args = array(
        'post_type' => 'product',
        'posts_per_page' => -1
    );
    $loop = new WP_Query( $args );
    if ( $loop->have_posts() ): while ( $loop->have_posts() ): $loop->the_post();

        global $product;

        $price = $product->get_price_html();
        $sku = $product->get_sku();
        $stock = $product->get_stock_quantity();
		echo "<br>";
		echo the_ID();
		
		//var_dump($product);

    endwhile; endif; wp_reset_postdata();
	*/
	
	?>
	</p>

	<form name="googleprodratingxml" id="googleprodratingxml" action="?page=wp_pro-notifications" method="post">
		<div class="wprevpro_margin10">
		
		<table class="form-table" role="presentation">
		<tbody>
		<tr class="wprevpro_row apikey">
			<th scope="row"><label for="wprevpro_googleprodratingxml">Create Ratings XML File:</label></th>
			<td>		
			<select id="wprevpro_googleprodratingxml" name="wprevpro_googleprodratingxml">
			<option value="" <?php if($googleprodratingxmlarray['createxml']!='yes'){echo 'selected="selected"';} ?>>No</option>
			<option value="yes" <?php if($googleprodratingxmlarray['createxml']=='yes'){echo 'selected="selected"';} ?>>Yes</option>
			</select>
			<p class="description">
			<?php _e('Only use this if you have been approved to use Product Ratings in the Google Merchant Center.', 'wp-review-slider-pro'); ?>
			<a href="https://support.google.com/merchants/troubleshooter/10994881?visit_id=638338600588086086-3983038699&rd=1" target="_blank"><?php _e('More Info', 'wp-review-slider-pro'); ?></a>
			</p>
		   </td>
	   </tr>
<?php
//may need to add this later.
/*
?>
		<tr class="wprevpro_row apikey gprfields" <?php echo $hideadfields;?> >
			<th scope="row"><label for="wprevpro_gpr_gtin">GTIN Field</label></th>
			<td><input class="regular-text" id="wprevpro_gpr_gtin" data-custom="custom" type="text" name="wprevpro_gpr_gtin" placeholder="" value="">
			<p class="description">
				<?php _e('Optional: Enter the Custom Field (or WooCommerce Product Field) for the product that will be used for the GTIN. If you do not have a GTIN, MPN, or Brand Google will try and match the product by the URL.', 'wp-review-slider-pro'); ?>
				<a href="https://support.google.com/merchants/answer/160161" target="_blank"><?php _e('More Info', 'wp-review-slider-pro'); ?></a>
			</p>
		   </td>
	    </tr>
		<tr class="wprevpro_row apikey gprfields" <?php echo $hideadfields;?>>
			<th scope="row"><label for="wprevpro_gpr_mpn">MPN Field</label></th>
			<td><input class="regular-text" id="wprevpro_gpr_mpn" data-custom="custom" type="text" name="wprevpro_gpr_mpn" placeholder="" value="">
			<p class="description">
				<?php _e('Optional: Enter the Custom Field (or WooCommerce Product Field) for the product that will be used for the MPN.', 'wp-review-slider-pro'); ?>
			</p>
		   </td>
	    </tr>
		<tr class="wprevpro_row apikey gprfields" <?php echo $hideadfields;?>>
			<th scope="row"><label for="wprevpro_gpr_brand">Brand Field</label></th>
			<td><input class="regular-text" id="wprevpro_gpr_brand" data-custom="custom" type="text" name="wprevpro_gpr_brand" placeholder="" value="">
			<p class="description">
				<?php _e('Optional: Enter the Custom Field (or WooCommerce Product Field) the product that will be used for the Brand.', 'wp-review-slider-pro'); ?>
			</p>
		   </td>
	    </tr>
<?php
*/
?>
		<tr class="wprevpro_row apikey gprfields" <?php echo $hideadfields;?>>
			<th scope="row"><label for="wprevpro_gpr_fileurl">Ratings XML File URL</label></th>
			<td><input class="regular-text" id="wprevpro_gpr_fileurl" data-custom="custom" type="text" name="wprevpro_gpr_fileurl" placeholder="" value="<?php echo $googleprodfileurlval; ?>" disabled>
			<p class="description">
				<?php _e('This is the URL that you will submit to Google Merchant Center.', 'wp-review-slider-pro'); ?>
				<a href="https://support.google.com/merchants/answer/7075701?sjid=12594025614581765696-NA#mode_type" target="_blank"><?php _e('More Info', 'wp-review-slider-pro'); ?></a>
				
			</p>
		   </td>
	    </tr>
		
		<tr class="wprevpro_row apikey gprfields" <?php echo $hideadfields;?>>
			<th scope="row"><label for="">Things to Note:</label></th>
			<td>
			<p class="description">
				<?php _e('1) The plugin will search the Product meta and custom fields for GTIN, MPN, SKU, and Brand or each product. Please check the xml file to make sure it is finding the correct values. ', 'wp-review-slider-pro'); ?>
				<a href="https://developers.google.com/product-review-feeds/schema#product_ids" target="_blank"><?php _e('More Info', 'wp-review-slider-pro'); ?></a>
			</p>
			<p class="description">
				<?php _e('2) It will use the most recent 20 reviews for each product that have at least 25 characters.', 'wp-review-slider-pro'); ?>
			</p>
		   </td>
	    </tr>
 
		</tbody>
		</table>
		

		</div>
		<?php 
		//security nonce
		wp_nonce_field( 'wprevpro_save_googleprodrating');
		?>
		<p class="submit">
		<input type="submit" name="wprevpro_submitgoogleprodrating" id="wprevpro_submitgoogleprodrating" class="button button-primary" value="<?php _e('Save', 'wp-review-slider-pro'); ?>">
		</p>
	</form>
</div>	


<div class="notifications_sections">
<?php
//section for automatically creating get review forms for Google Places API, will be adding other apis here as long as they don't cost me server resources. TripAdvisor API would good as well. Maybe Yelp.
 //get saved values
 if(get_option('wprev_autogetrevs')){
	$saveautogetrevsjson = get_option('wprev_autogetrevs');
	$saveautogetrevsjsonarray = json_decode($saveautogetrevsjson,true);
 } else {
	$saveautogetrevsjsonarray = Array(); 
 }
	 
?>
	<h3><?php _e('Automatic Add Review Sources', 'wp-review-slider-pro'); ?></h3>
	<p><?php 
	echo sprintf( __( 'Allows you to automatically add review sources based on a custom field value in a Post or Page. Currently only works for Google Places API.', 'wp-review-slider-pro' ), 
			'https://console.cloud.google.com/apis/library/translate.googleapis.com'
		);
	?>
	</p>
	<p class="description">
		<b><?php _e('Instructions:', 'wp-review-slider-pro'); ?></b><br> 
		<?php
		echo sprintf( __( '- Make sure you have your Google Places API entered and working on this <a href="%s" target="_blank"> page</a>.', 'wp-review-slider-pro' ),'admin.php?page=wp_pro-get_apps&rtype=Google-Places-API');?><br>
		<?php
		echo sprintf( __( '- Use the "Add a New Source Page" button after you enter your API Key and go ahead and download some reviews. Make sure everything is working.', 'wp-review-slider-pro' ));?><br>
		<?php
		echo sprintf( __( '- IMPORTANT: You must have a custom field on your Posts or Pages that contains the Google Places API.', 'wp-review-slider-pro' ));?><br>
		</p>

	<form name="autogetrevsform" id="autogetrevsform" action="?page=wp_pro-notifications" method="post">
		<div class="wprevpro_margin10">
	   
	   <table id="autogetrevstable" class="form-table" role="presentation"><tbody>
		<tr class="wprevpro_row">
		<td>
		<table class="w3-table w3-striped w3-border" style="background-color:#fff;color:#000">
		<tbody>
		<tr>
		  <th><?php _e('Source Type', 'wp-review-slider-pro'); ?></th>
		  <th><?php _e('Post Type', 'wp-review-slider-pro'); ?></th>
		  <th><?php _e('Custom Field Name', 'wp-review-slider-pro'); ?></th>
		  <th><?php _e('Run Hourly', 'wp-review-slider-pro'); ?></th>
		  <th><?php _e('Form: Language Code ', 'wp-review-slider-pro'); ?></th>
		  <th><?php _e('Form: Which reviews?  ', 'wp-review-slider-pro'); ?></th>
		  <th><?php _e('Form: Auto Download  ', 'wp-review-slider-pro'); ?></th>
		</tr>
		<tr>
			<td>
				<select id="wprevpro_autogetrevs_type" name="wprevpro_autogetrevs_type">
				<option value="google" <?php if($saveautogetrevsjsonarray['autogetrevs_type']=='google'){echo 'selected="selected"';} ?>><?php _e('Google API', 'wp-review-slider-pro'); ?></option>
				</select>
			</td>
			<td>
				<?php
				// Get post types
				$args= array('public' => true);
				$post_types = get_post_types( $args, 'objects' );
				?>
				<select id="wprevpro_autogetrevs_posttype" name="wprevpro_autogetrevs_posttype">
				<?php foreach ( $post_types as $post_type_obj ):
				if($post_type_obj->name!='attachment'){
					$labels = get_post_type_labels( $post_type_obj );
				?>
					<option <?php if($saveautogetrevsjsonarray['autogetrevs_posttype']==esc_attr( $post_type_obj->name )){echo 'selected="selected"';} ?> value="<?php echo esc_attr( $post_type_obj->name ); ?>"><?php echo esc_html( $labels->name ); ?></option>
				<?php 
				}
				endforeach; 
				?>
				</select>
			</td>
			<td>
				<input id="wprevpro_autogetrevs_cfn" data-custom="custom" type="text" maxlength="50" name="wprevpro_autogetrevs_cfn" value="<?php echo $saveautogetrevsjsonarray['autogetrevs_cfn'];?>">
			</td>
			<td>
				<select id="wprevpro_autogetrevs_hourly" name="wprevpro_autogetrevs_hourly">
				<option value="no" <?php if($saveautogetrevsjsonarray['autogetrevs_hourly']!='yes'){echo 'selected="selected"';} ?>><?php _e('No', 'wp-review-slider-pro'); ?></option>
				<option value="yes" <?php if($saveautogetrevsjsonarray['autogetrevs_hourly']=='yes'){echo 'selected="selected"';} ?>><?php _e('Yes', 'wp-review-slider-pro'); ?></option>
				</select>
			</td>
			<td>
				<input id="wprevpro_autogetrevs_langcode" data-custom="custom" type="text" maxlength="10" name="wprevpro_autogetrevs_langcode" value="<?php echo $saveautogetrevsjsonarray['autogetrevs_langcode'];?>">
			</td>
			<td>
				<select id="wprevpro_autogetrevs_which" name="wprevpro_autogetrevs_which">
				<option value="" <?php if($saveautogetrevsjsonarray['autogetrevs_which']!='newest'){echo 'selected="selected"';} ?>><?php _e('Newest', 'wp-review-slider-pro'); ?></option>
				<option value="relevant" <?php if($saveautogetrevsjsonarray['autogetrevs_which']=='relevant'){echo 'selected="selected"';} ?>><?php _e('Most Relevant', 'wp-review-slider-pro'); ?></option>
				<option value="both" <?php if($saveautogetrevsjsonarray['autogetrevs_which']=='both'){echo 'selected="selected"';} ?>><?php _e('Both', 'wp-review-slider-pro'); ?></option>
				</select>
			</td>
			<td>
				<select id="wprevpro_autogetrevs_cron" name="wprevpro_autogetrevs_cron">
				<option value="" <?php if($saveautogetrevsjsonarray['autogetrevs_cron']!='No'){echo 'selected="selected"';} ?>><?php _e('No', 'wp-review-slider-pro'); ?></option>
				<option value="672" <?php if($saveautogetrevsjsonarray['autogetrevs_cron']=='672'){echo 'selected="selected"';} ?>><?php _e('Once a Month', 'wp-review-slider-pro'); ?></option>
				<option value="336" <?php if($saveautogetrevsjsonarray['autogetrevs_cron']=='336'){echo 'selected="selected"';} ?>><?php _e('Every 14 Days', 'wp-review-slider-pro'); ?></option>
				<option value="168" <?php if($saveautogetrevsjsonarray['autogetrevs_cron']=='168'){echo 'selected="selected"';} ?>><?php _e('Every 7 Days', 'wp-review-slider-pro'); ?></option>
				<option value="48" <?php if($saveautogetrevsjsonarray['autogetrevs_cron']=='48'){echo 'selected="selected"';} ?>><?php _e('Every Other Day', 'wp-review-slider-pro'); ?></option>
				<option value="24" <?php if($saveautogetrevsjsonarray['autogetrevs_cron']=='24'){echo 'selected="selected"';} ?>><?php _e('Once a Day', 'wp-review-slider-pro'); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="6"><button id="autogetrevs_btn" type="button" class="btn_green" ><?php _e('Run Once', 'wp-review-slider-pro'); ?></button>&nbsp;&nbsp;
			<button id="autogetrevsplusdownload_btn" type="button" class="btn_green" ><?php _e('Run Once + Download Reviews', 'wp-review-slider-pro'); ?></button>
			</td>

		</tr>
		</tbody></table>
		<p class="description">
		<?php 
		echo sprintf( __( 'Notes:<br>-This will set up ten Review Source Pages at a Time. <br>-"Custom Field Name" is the custom field on the Post/Page that contains the Place ID. <br>-"Run Hourly" will do 10 every hour. <br>- Recommend that you check the sources on the Get Reviews tab after the first run. <br>- The reviews will be downloaded the next time the cron job runs which is once a day.<br>- "The Form:" settings are used to setup the automatically generated download forms.<br>- "Run Once" will setup the review sources on the Get Reviews tab. "Run Once + Download Reviews" will do the same but also go ahead and download the reviews.', 'wp-review-slider-pro' ));
		?>
		</p>
		</td></tr></tbody>
	   </table>

		</div>
		<p class="submit">
		<input type="submit" name="wprevpro_submitautogetrevsform" id="wprevpro_submitautogetrevsform" class="button button-primary" value="<?php _e('Save', 'wp-review-slider-pro'); ?>">
		</p>
			<?php 
		//security nonce
		wp_nonce_field( 'wprevpro_save_autogetrevsform');
		?>
	</form>
</div>	




	
	</div>
	<div id="popup" class="popup-wrapper wprevpro_hide">
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
	<div id="tb_content_popup" style="display:none;">
		<div id="lang_progress_div_container">
				<div id="lang_progress_div">
				</div>
				<div id="lang_progress_div2">
				</div>
				<div id="lang_progress_div3">
				</div>
				<div id="lang_progress_div_error">
				</div>
				<div class="loadingspinner"></div>
			</div>
	</div>
	<div id="tb_content_popup2" style="display:none;">
		<div id="autogetrevs_div_container">
				<div id="autogetrevs_progress_div"><?php _e('Working...', 'wp-review-slider-pro'); ?>
				</div>
				<div id="autogetrevs_div_error">
				</div>
				<div class="loadingspinner"></div>
				<div class=""></div>
			</div>
	</div>
				
	<?php 
// show error/update messages
		settings_errors('tripadvisor-radio');
} else {
?>
<p><strong><?php _e('Upgrade to the Pro Version of this plugin to get notifications! Get the Pro Version <a href="' . wrsp_fs()->get_upgrade_url() . '">here</a>!', 'wp-fb-reviews'); ?></strong></p>
<?php
}
?>
</div>

</div>

	

