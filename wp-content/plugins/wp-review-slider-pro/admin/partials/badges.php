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
 
 
 //=========testing-tool
 //require_once WPREV_PLUGIN_DIR . 'admin/class-wp-review-slider-pro-admin_hooks.php';
	//$plugin_admin_hooks = new WP_Review_Pro_Admin_Hooks( $this->get_token(), $this->get_version() );
	//$plugin_admin_hooks->updateallavgtotalstable();
 //========================
     // check user capabilities
    if (!current_user_can('manage_options') && $this->wprev_canuserseepage('badges')==false) {
        return;
    }
	

	$dbmsg = "";
	$html="";
	$currentbadge= new stdClass();
	$currentbadge->id="";
	$currentbadge->title ="";
	$currentbadge->badge_type ="";
	$currentbadge->badge_bname =__('Your Business Name', 'wp-review-slider-pro');
	$currentbadge->badge_orgin ="custom";
	$currentbadge->rpage ="";
	$currentbadge->style ="1";
	$currentbadge->created_time_stamp ="";
	$currentbadge->badge_misc ="";
	$currentbadge->badge_css ="";
	$currentbadge->google_snippet_add ="";
	$currentbadge->google_snippet_type ="";
	$currentbadge->google_snippet_name ="";
	$currentbadge->google_snippet_desc ="";
	$currentbadge->google_snippet_business_image ="";
	$currentbadge->google_snippet_more="";
	
	//echo $this->_token;  wprevpro_t_read_more_text
	//if token = wp-review-slider-pro then using free version
	
	//db function variables
	global $wpdb;
	$table_name = $wpdb->prefix . 'wpfb_badges';
	$reviews_table_name = $wpdb->prefix . 'wpfb_reviews';
	
	//if badge recalculation clicked
	if(isset($_GET['forcerecal'])){
		if($_GET['forcerecal']=='yes'){
			
			//====need to test this, need to clear out pages that have no reviews in the db.
			//$table_name_totalavg = $wpdb->prefix . 'wpfb_total_averages';
			// $delete = $wpdb->query("TRUNCATE TABLE `".$table_name_totalavg."`");
			
			require_once WPREV_PLUGIN_DIR . 'admin/class-wp-review-slider-pro-admin_hooks.php';
			$plugin_admin_hooks = new WP_Review_Pro_Admin_Hooks( $this->get_token(), $this->get_version() );
			$plugin_admin_hooks->updateallavgtotalstable();
				
			$dbmsg = '<div id="setting-error-wprevpro_message" class="updated settings-error notice is-dismissible"><p><strong>'.__('Badge totals and averages updated!', 'wp-review-slider-pro').'</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">'.__('Dismiss this notice.', 'wp-review-slider-pro').'</span></button></div>';
			
		}
		 remove_query_arg( 'forcerecal' );
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
				$currentbadge = $wpdb->get_row( "SELECT * FROM ".$table_name." WHERE id = ".$tid );
			}
			//for copying
			if($_GET['taction'] == "copy" && $_GET['tid'] > 0){
				//security
				check_admin_referer( 'tcopy_');
				//get form array
				$currentbadge = $wpdb->get_row( "SELECT * FROM ".$table_name." WHERE id = ".$tid );
				//add new template
				$array = (array) $currentbadge;
				$array['title'] = $array['title'].'_copy';
				
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
		$badge_bname = sanitize_text_field($_POST['wprevpro_badge_bname']);
		$badge_type = sanitize_text_field($_POST['wprevpro_badge_type']);
		$badge_orgin = sanitize_text_field($_POST['wprevpro_badge_orgin']);
		if($badge_orgin==''){
			$badge_orgin =='custom';
		}
		$style = sanitize_text_field($_POST['wprevpro_template_style']);
		$badge_css = sanitize_textarea_field($_POST['wprevpro_badge_css']);
		//$badge_css = $_POST['wprevpro_badge_css'];

		$google_snippet_add = sanitize_text_field($_POST['wprevpro_t_google_snippet_add']);
		$google_snippet_type = sanitize_text_field($_POST['wprevpro_t_google_snippet_type']);
		$google_snippet_name = sanitize_text_field($_POST['wprevpro_t_google_snippet_name']);
		$google_snippet_desc = sanitize_text_field($_POST['wprevpro_t_google_snippet_desc']);
		$google_snippet_business_image = sanitize_text_field($_POST['wprevpro_t_google_snippet_business_image']);
		
		//added snippet fields
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
			$google_snippet_more_phone = sanitize_text_field($_POST['wprevpro_t_google_snippet_more_phone']);
			$google_snippet_more_price = sanitize_text_field($_POST['wprevpro_t_google_snippet_more_price']);
			$google_snippet_more_street = sanitize_text_field($_POST['wprevpro_t_google_snippet_more_street']);
			$google_snippet_more_city = sanitize_text_field($_POST['wprevpro_t_google_snippet_more_city']);
			$google_snippet_more_state = sanitize_text_field($_POST['wprevpro_t_google_snippet_more_state']);
			$google_snippet_more_zip = sanitize_text_field($_POST['wprevpro_t_google_snippet_more_zip']);
		} else {
			$google_snippet_prodbrand = sanitize_text_field($_POST['wprevpro_t_google_snippet_prodbrand']);
			$google_snippet_prodprice = sanitize_text_field($_POST['wprevpro_t_google_snippet_prodprice']);
			$google_snippet_prodpricec = sanitize_text_field($_POST['wprevpro_t_google_snippet_prodpricec']);
			$google_snippet_prodsku = sanitize_text_field($_POST['wprevpro_t_google_snippet_prodsku']);
			$google_snippet_prodginame = sanitize_text_field($_POST['wprevpro_t_google_snippet_prodginame']);
			$google_snippet_prodgival = sanitize_text_field($_POST['wprevpro_t_google_snippet_prodgival']);
			$google_snippet_produrl = sanitize_text_field($_POST['wprevpro_t_google_snippet_produrl']);
			$google_snippet_prodavailability = sanitize_text_field($_POST['wprevpro_t_google_snippet_prodavailability']);
			$google_snippet_prodpriceValidUntil = sanitize_text_field($_POST['wprevpro_t_google_snippet_prodpriceValidUntil']);
		}
		
		$google_snippet_schemaid = sanitize_text_field($_POST['wprevpro_t_google_snippet_more_schemaid']);
		$google_snippet_tvr = sanitize_text_field($_POST['wprevpro_t_google_snippet_tvr']);
		
		$google_snippet_more_array = array("schemaid"=>"$google_snippet_schemaid","telephone"=>"$google_snippet_more_phone","priceRange"=>"$google_snippet_more_price","streetAddress"=>"$google_snippet_more_street","addressLocality"=>"$google_snippet_more_city","addressRegion"=>"$google_snippet_more_state","postalCode"=>"$google_snippet_more_zip","brand"=>"$google_snippet_prodbrand","price"=>"$google_snippet_prodprice","priceCurrency"=>"$google_snippet_prodpricec","sku"=>"$google_snippet_prodsku","giname"=>"$google_snippet_prodginame","gival"=>"$google_snippet_prodgival","url"=>"$google_snippet_produrl","availability"=>"$google_snippet_prodavailability","priceValidUntil"=>"$google_snippet_prodpriceValidUntil","irm"=>"","irm_type"=>"","tvr"=>"$google_snippet_tvr");
		//encode to save in database
		$google_snippet_more_array_encode = json_encode($google_snippet_more_array);

		//template misc
		$templatemiscarray = array();
		$templatemiscarray['showstars']=sanitize_text_field($_POST['wprevpro_badge_misc_showstars']);
		$templatemiscarray['bgcolor1']=sanitize_text_field($_POST['wprevpro_badge_misc_bgcolor1']);
		$templatemiscarray['bgcolor2']=sanitize_text_field($_POST['wprevpro_badge_misc_bgcolor2']);
		$templatemiscarray['bgcolor3']=sanitize_text_field($_POST['wprevpro_badge_misc_bgcolor3']);
		$templatemiscarray['starcolor']=sanitize_text_field($_POST['wprevpro_badge_misc_starcolor']);
		$templatemiscarray['tcolor1']=sanitize_text_field($_POST['wprevpro_badge_misc_tcolor1']);
		$templatemiscarray['tcolor2']=sanitize_text_field($_POST['wprevpro_badge_misc_tcolor2']);
		$templatemiscarray['tcolor3']=sanitize_text_field($_POST['wprevpro_badge_misc_tcolor3']);
		$templatemiscarray['bradius']=sanitize_text_field($_POST['wprevpro_badge_misc_bradius']);
		$templatemiscarray['shadow']=sanitize_text_field($_POST['wprevpro_badge_misc_shadow']);
		$templatemiscarray['show_licon']=sanitize_text_field($_POST['wprevpro_badge_misc_show_licon']);
		$templatemiscarray['liconurl']=sanitize_url($_POST['wprevpro_badge_misc_liconurl']);
		$templatemiscarray['liconurllink']=sanitize_text_field($_POST['wprevpro_badge_misc_liconurllink']);
		$templatemiscarray['liconurllink_target']=sanitize_text_field($_POST['wprevpro_badge_misc_liconurllink_target']);
		$templatemiscarray['liconwidth']=sanitize_text_field($_POST['wprevpro_badge_misc_liconwidth']);
		$templatemiscarray['liconheight']=sanitize_text_field($_POST['wprevpro_badge_misc_liconheight']);
		
		$templatemiscarray['bwidth']=sanitize_text_field($_POST['wprevpro_badge_misc_width']);
		$templatemiscarray['bwidtht']=sanitize_text_field($_POST['wprevpro_badge_misc_widtht']);
		
		
		$tempquery = "select type from ".$reviews_table_name." group by pageid";
		$typearray = $wpdb->get_col($tempquery);
		//$typearray = unserialize(WPREV_TYPE_ARRAY);
		 for($x=0;$x<count($typearray);$x++){
			$typelowercase = strtolower($typearray[$x]);
			if($typelowercase!='manual' && $typelowercase!='submitted'){
				if(isset($_POST['wprevpro_badge_misc_si_'.$typelowercase.'_linkurl'])){
				$templatemiscarray['si_'.$typelowercase.'_linkurl']=sanitize_url($_POST['wprevpro_badge_misc_si_'.$typelowercase.'_linkurl']);
				}
			}
		 }
		 //for custom small icon
		 $templatemiscarray['si_custom_linkurl']=sanitize_url($_POST['wprevpro_badge_misc_si_custom_linkurl']);
		 $templatemiscarray['si_custom_imgurl']=sanitize_text_field($_POST['wprevpro_badge_misc_si_custom_imgurl']);
		 $templatemiscarray['si_custom2_linkurl']=sanitize_url($_POST['wprevpro_badge_misc_si_custom2_linkurl']);
		 $templatemiscarray['si_custom2_imgurl']=sanitize_text_field($_POST['wprevpro_badge_misc_si_custom2_imgurl']);
		 $templatemiscarray['si_custom3_linkurl']=sanitize_url($_POST['wprevpro_badge_misc_si_custom3_linkurl']);
		 $templatemiscarray['si_custom3_imgurl']=sanitize_text_field($_POST['wprevpro_badge_misc_si_custom3_imgurl']);
		 
		 
		//$templatemiscarray['si_facebook_linkurl']=sanitize_text_field($_POST['wprevpro_badge_misc_si_facebook_linkurl']);
		//$templatemiscarray['si_google_linkurl']=sanitize_text_field($_POST['wprevpro_badge_misc_si_google_linkurl']);
		//$templatemiscarray['si_yelp_linkurl']=sanitize_text_field($_POST['wprevpro_badge_misc_si_yelp_linkurl']);
		//$templatemiscarray['si_tripadvisor_linkurl']=sanitize_text_field($_POST['wprevpro_badge_misc_si_tripadvisor_linkurl']);
		
		$templatemiscarray['c_text']=sanitize_text_field($_POST['wprevpro_badge_misc_ctext']);
		$templatemiscarray['c_text2']=sanitize_text_field($_POST['wprevpro_badge_misc_ctext2']);
		$templatemiscarray['c_text_b2']=sanitize_text_field($_POST['wprevpro_badge_misc_ctext_b2']);
		$templatemiscarray['c_text_url']=sanitize_url($_POST['wprevpro_badge_misc_ctext_url']);
        $templatemiscarray['c_text_url_title']=sanitize_text_field($_POST['wprevpro_badge_misc_ctext_url_title']);
		$templatemiscarray['ratingsfrom']=sanitize_text_field($_POST['wprevpro_badge_misc_customratingfrom']);
		$templatemiscarray['ratingsavg']=sanitize_text_field($_POST['wprevpro_badge_misc_cratingavg']);
		$templatemiscarray['ratingstot']=sanitize_text_field($_POST['wprevpro_badge_misc_cratingtotal']);
		$templatemiscarray['roundavg']=sanitize_text_field($_POST['wprevpro_badge_misc_roundavg']);
		$templatemiscarray['outof']=sanitize_text_field($_POST['wprevpro_badge_misc_outof']);

		$templatemiscarray['liconurllink_attr']=sanitize_url($_POST['wprevpro_badge_misc_liconurllink_attr']);

		$templatemiscarray['onclickaction']=sanitize_text_field($_POST['wprevpro_badge_misc_onclickaction']);
		$templatemiscarray['onclickurl']=sanitize_url($_POST['wprevpro_badge_misc_onclickurl']);
		$templatemiscarray['onclickurl_target']=sanitize_text_field($_POST['wprevpro_badge_misc_onclickurl_target']);
		$templatemiscarray['sliderevtemplate']=sanitize_text_field($_POST['wprevpro_badge_misc_sliderevtemplate']);
		$templatemiscarray['slidelocation']=sanitize_text_field($_POST['wprevpro_badge_misc_slidelocation']);
		$templatemiscarray['slbgcolor1']=sanitize_text_field($_POST['wprevpro_badge_misc_slbgcolor1']);
		$templatemiscarray['slbordercolor1']=sanitize_text_field($_POST['wprevpro_badge_misc_slbordercolor1']);
		$templatemiscarray['slborderwidth']=sanitize_text_field($_POST['wprevpro_badge_misc_slborderwidth']);
		$templatemiscarray['slwidth']=sanitize_text_field($_POST['wprevpro_badge_misc_slwidth']);
		$templatemiscarray['slheight']=sanitize_text_field($_POST['wprevpro_badge_misc_slheight']);
		$templatemiscarray['slpadding-top']=sanitize_text_field($_POST['wprevpro_badge_misc_slpadding-top']);
		$templatemiscarray['slpadding-right']=sanitize_text_field($_POST['wprevpro_badge_misc_slpadding-right']);
		$templatemiscarray['slpadding-bottom']=sanitize_text_field($_POST['wprevpro_badge_misc_slpadding-bottom']);
		$templatemiscarray['slpadding-left']=sanitize_text_field($_POST['wprevpro_badge_misc_slpadding-left']);
		$templatemiscarray['slideheader']=wp_kses_post(wpautop($_POST['wprevpro_badge_misc_slideheader']));
		$templatemiscarray['slidefooter']=wp_kses_post(wpautop($_POST['wprevpro_badge_misc_slidefooter']));
	
		if(isset($_POST['wprevpro_badge_sicon'])){
			foreach($_POST['wprevpro_badge_sicon'] as $selected){
			$templatemiscarray['sicon'][]=$selected;
			}
		}
		$templatemiscjson = json_encode($templatemiscarray);
		
		if(!isset($_POST['wprevpro_t_rpage'])){
			$_POST['wprevpro_t_rpage']="";
		}
			$rpagearray = $_POST['wprevpro_t_rpage'];
			$rpagearrayjson = json_encode($rpagearray);
		//if this is a post id type badge then we use the pagearray as postids
		if($badge_orgin=='postid'){
			require_once WPREV_PLUGIN_DIR . 'admin/class-wp-review-slider-pro-admin-common.php';
			$plugin_admin_common = new Common_Admin_Functions();
			$postid = sanitize_text_field($_POST['wprevpro_nr_postid']);
			$rpagearrayjson ='';
			if($postid!=''){
				$rpagearrayjson = $plugin_admin_common->wprev_commastrtojson($postid,true);
			}
		}

		$timenow = time();
		
		//+++++++++need to sql escape using prepare+++++++++++++++++++
		//+++++++++++++++++++++++++++++++++++++++++++++++++++++
		//insert or update
			$data = array( 
				'title' => "$title",
				'badge_type' => "$badge_type",
				'badge_bname' => "$badge_bname",
				'badge_orgin' => "$badge_orgin",
				'style' => "$style",
				'created_time_stamp' => "$timenow",
				'badge_css' => "$badge_css", 
				'badge_misc' => "$templatemiscjson",
				'google_snippet_add' => "$google_snippet_add",
				'google_snippet_type' => "$google_snippet_type",
				'google_snippet_name' => "$google_snippet_name",
				'google_snippet_desc' => "$google_snippet_desc",
				'google_snippet_business_image' => "$google_snippet_business_image",
				'google_snippet_more' => "$google_snippet_more_array_encode",
				'rpage' => "$rpagearrayjson",
				);
				//print_r($data);
			$format = array( 
					'%s',
					'%s',
					'%s',
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
					'%s',
					'%s',
				); 

		if($t_id==""){
			//print_r($data);
			//insert
			$wpdb->insert( $table_name, $data, $format );
			//$wpdb->show_errors();
			//$wpdb->print_error();
			//die();
		} else {
			//update
			//print_r($data);
			$updatetempquery = $wpdb->update($table_name, $data, array( 'id' => $t_id ), $format, array( '%d' ));
			//$wpdb->show_errors();
			//$wpdb->print_error();
			//die();
			if($updatetempquery>0){
				$dbmsg = '<div id="setting-error-wprevpro_message" class="updated settings-error notice is-dismissible"><p><strong>'.__('Badge Updated!', 'wp-review-slider-pro').'</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">'.__('Dismiss this notice.', 'wp-review-slider-pro').'</span></button></div>';
			}
		}
		
	}

	//Get list of all current forms--------------------------
	$currentforms = $wpdb->get_results("SELECT id, title, badge_type, created_time_stamp, style FROM $table_name");
	//-------------------------------------------------------

	//check to see if reviews are in database
	//total number of rows
	$reviewtotalcount = $wpdb->get_var( 'SELECT COUNT(*) FROM '.$reviews_table_name );
	if($reviewtotalcount<1){
		$dbmsg = $dbmsg . '<div id="setting-error-wprevpro_message" class="updated settings-error notice is-dismissible">'.__('<p><strong>No reviews found. Please visit the Get Reviews page or manually add one on the <a href="?page=wp_pro-reviews">Review List</a> page. </strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>', 'wp-review-slider-pro').'</div>';
	}
	
	
?>

<div class="wrap wp_pro-settings" id="">
	<h1><img src="<?php echo plugin_dir_url( __FILE__ ) . 'logo.png'; ?>"></h1>
	
<?php 
include("tabmenu.php");

//query args for export and import
$url_tempdownload = admin_url( 'admin-post.php?action=print_badges.csv' );
$url_temprecal = add_query_arg( 'forcerecal', 'yes');
if ( wrsp_fs()->can_use_premium_code() ) {
?>
<div class="wprevpro_margin10">
	<a id="wprevpro_helpicon_posts" class="wprevpro_btnicononly button dashicons-before dashicons-editor-help"></a>
	<a id="wprevpro_addnewtemplate" class="button dashicons-before dashicons-plus-alt"><?php _e('Add New Badges Template', 'wp-review-slider-pro'); ?></a>
	<a href="<?php echo $url_tempdownload;?>" id="wprevpro_exporttemplates" class="button dashicons-before dashicons-download"><?php _e('Export Badges', 'wp-review-slider-pro'); ?></a>
	<a id="wprevpro_importtemplates" class="button dashicons-before dashicons-upload"><?php _e('Import Badges', 'wp-review-slider-pro'); ?></a>
	<a id="wprevpro_recaltotals" class="button dashicons-before dashicons-admin-tools"><?php _e('Badge Data', 'wp-review-slider-pro'); ?></a>
</div>
<div class="wprevpro_margin10 wprev_greybox" id="importform" style='display:none;'>
	    <form  action="?page=wp_pro-badges" method="post" name="upload_excel" enctype="multipart/form-data">
		<p><b>Use this form to import previously exported Badges.</b></p>
			<input type="file" name="file" id="file">
			</br>
			<button type="submit" id="submit" name="Import" class="button-primary" data-loading-text="Loading...">Import</button>
        </form>
</div>
<div class="wprevpro_margin10 wprev_greybox" id="recalform" style='display:none;'>

	<p><b>Badge Totals and Averages are automatically calculated when you download reviews. Use this button to force a recalculation. This is useful if you delete a review for example. This will only work for the downloaded reviews averages and totals. It doesn't affect totals and averages scraped from the source site. You'll need to check for new reviews to update that. </b></p>
	<a href="<?php echo $url_temprecal;?>" id="wprevpro_exporttemplates" class="button dashicons-before dashicons-admin-tools"><?php _e('Force Recalculate', 'wp-review-slider-pro'); ?></a>
	</br></br>
	<b>Current Values:</b>
	<table id="badgedatatable" class="wp-list-table striped posts">
	<tr><th>Source</th><th>Page Name</th><th>Page ID</th><th style="width: 80px;">Source</br>Total</th><th style="width: 80px;">Source</br>Avg</th><th style="width: 80px;">Review List</br>Total</th><th style="width: 80px;">Review List</br>Avg</th><th>Num1</th><th>Num2</th><th>Num3</th><th>Num4</th><th>Num5</th></tr>
	
<?php
$table_name_data = $wpdb->prefix . 'wpfb_total_averages';
$currentdatas= $wpdb->get_results("SELECT * FROM $table_name_data WHERE btp_type='page' order by btp_type ASC");
foreach ( $currentdatas as $currentdata ) 
	{
		echo '<tr id="'.$currentdata->btp_id.'"><td class="pagetype">'.$currentdata->pagetype.'</td><td class="btp_name">'.$currentdata->btp_name.'</td><td class="btp_id">'.$currentdata->btp_id.'</td><td class="centercelltable total">'.$currentdata->total.'</td><td class="centercelltable avg">'.$currentdata->avg.'</td><td class="centercelltable total_indb">'.$currentdata->total_indb.'</td><td class="centercelltable avg_indb">'.$currentdata->avg_indb.'</td><td class="centercelltable numr1">'.$currentdata->numr1.'</td><td class="centercelltable numr2">'.$currentdata->numr2.'</td><td class="centercelltable numr3">'.$currentdata->numr3.'</td><td class="centercelltable numr4">'.$currentdata->numr4.'</td><td class="centercelltable numr5">'.$currentdata->numr5.'</td></tr>';
	}
?>
	</table>
	

</div>

<?php
} else {
	echo '<div class="wprevpro_margin10">'.__('Badges are a Premium feature. Please upgrade.', 'wp-review-slider-pro').'</div>';
}
//display message
echo $dbmsg;
		$html .= '
		<table class="wp-list-table widefat striped posts">
			<thead>
				<tr>
					<th scope="col" width="30px" class="manage-column">'.__('ID', 'wp-review-slider-pro').'</th>
					<th scope="col" class="manage-column">'.__('Title', 'wp-review-slider-pro').'</th>
					<th scope="col" width="100px" class="manage-column">'.__('Style', 'wp-review-slider-pro').'</th>
					<th scope="col" width="170px" class="manage-column">'.__('Last Updated', 'wp-review-slider-pro').'</th>
					<th scope="col" width="350px" class="manage-column">'.__('Action', 'wp-review-slider-pro').'</th>
				</tr>
				</thead>
			<tbody id="">';
	$haswidgettemplate = false;	//for hiding widget type, going to be phasing widget types out.
	if(count($currentforms)>0){
	foreach ( $currentforms as $currentform ) 
	{
	//remove query args we just used
	$urltrimmed = remove_query_arg( array('taction', 'id','forcerecal') );
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
	
		if($currentform->badge_type=='widget'){
			$haswidgettemplate = true;
		}
	
		$html .= '<tr id="'.$currentform->id.'">
				<th scope="col" class=" manage-column">'.$currentform->id.'</th>
				<th scope="col" class=" manage-column"><b>'.$currentform->title.'</b></th>
				<th scope="col" class=" manage-column"><b>'.$currentform->style.'</b></th>
				<th scope="col" class=" manage-column">'.date("F j, Y",$currentform->created_time_stamp) .'</th>
				<th scope="col" class="manage-column" templateid="'.$currentform->id.'" templatetype="'.$currentform->badge_type.'"> <a class="wprevpro_displayshortcode button button-primary dashicons-before dashicons-shortcode">'.__('Shortcode', 'wp-review-slider-pro').'</a> <a href="'.$url_tempeditbtn.'" class="button button-secondary dashicons-before dashicons-admin-generic">'.__('Edit', 'wp-review-slider-pro').'</a> <a href="'.$url_tempdelbtn.'" class="button button-secondary dashicons-before dashicons-trash">'.__('Delete', 'wp-review-slider-pro').'</a> <a href="'.$url_tempcopybtn.'" class="button button-secondary dashicons-before dashicons-admin-page">'.__('Copy', 'wp-fb-reviews').'</a></</th>
			</tr>';
	}
	} else {
		$html .= '<tr><td colspan="5">'.__('You can create a summary badges to proudly show off your review average and total!', 'wp-review-slider-pro').'</td></tr>';
	}
		$html .= '</tbody></table>';
		
			
 echo $html;	

//echo "<div></br></br>Coming Soon! Badges will give you a way to display a cool summary badge of your reviews!</br></br></div>"; 
?>

<div class="wprevpro_margin10" id="wprevpro_new_template">
<form name="newtemplateform" id="newtemplateform" action="?page=wp_pro-badges" method="post">
	<table class="wprevpro_margin10 form-table ">
		<tbody>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Badge Title:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<input id="wprevpro_template_title" data-custom="custom" type="text" name="wprevpro_template_title" placeholder="" value="<?php echo $currentbadge->title; ?>" required>
					<p class="description">
					<?php _e('Enter a title or name for this badge.', 'wp-review-slider-pro'); ?>		</p>
				</td>
			</tr>
			<tr class="wprevpro_row" <?php if($haswidgettemplate==false){echo "style='display:none;'";} ?>>
				<th scope="row">
					<?php _e('Choose Badge Type:', 'wp-review-slider-pro'); ?>
				</th>
				<td><div id="divtemplatestyles">

					<input type="radio" name="wprevpro_badge_type" id="wprevpro_badge_type1-radio" value="post" checked="checked">
					<label for="wprevpro_badge_type1-radio"><?php _e('Post or Page (shortcode)', 'wp-review-slider-pro'); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

					<input type="radio" name="wprevpro_badge_type" id="wprevpro_badge_type2-radio" value="widget" <?php if($currentbadge->badge_type== "widget"){echo 'checked="checked"';}?>>
					<label for="wprevpro_badge_type2-radio"><?php _e('Widget Area', 'wp-review-slider-pro'); ?></label>
					</div>
					<p class="description">
					<?php _e('Are you going to use this on a Page/Post or in a Widget area like your sidebar?', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Badge Business Name:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<input id="wprevpro_badge_bname" data-custom="custom" type="text" name="wprevpro_badge_bname" placeholder="" value="<?php echo stripslashes($currentbadge->badge_bname); ?>" >
					<p class="description">
					<?php _e('The business name on the badge.', 'wp-review-slider-pro'); ?>		</p>
				</td>
			</tr>
					
			
			
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Badge Style Settings:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<div class="w3_wprs-row">
						  <div class="w3_wprs-col s6">
							<div class="w3_wprs-col s6">
								<div class="wprevpre_temp_label_row" style="">
								<?php _e('Badge Style:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row">
								<?php _e('Review Origin:', 'wp-review-slider-pro'); ?>
								
								</div>
								
								<div <?php if($currentbadge->badge_orgin=='manual'){echo 'style="display:none;"';} ?> class="divpickpagesrow wprevpre_temp_label_row">
<?php
//find saved values
$cpostids = '';
if($currentbadge->badge_orgin=='postid'){
	if(isset($currentbadge->rpage) && $currentbadge->rpage!=''){
	$rppostidjsondecode = str_replace("-", "", $currentbadge->rpage);
	$rppostidjsondecode = json_decode($rppostidjsondecode,true);
	$cpostids = implode(",", $rppostidjsondecode);
	}
}
?>
<input style="display:none;" class="wprevpro_nr_postid" id="wprevpro_nr_postid" data-custom="custom" type="text" name="wprevpro_nr_postid" placeholder="" value="<?php echo $cpostids ; ?>" >
<a <?php if($currentbadge->badge_orgin!='postid'){echo "style=display:none;";} ?> id="wprevpro_btn_pickpostids" class="button dashicons-before dashicons-yes ">Select Post IDs</a>
	<div id="tb_content_cat_select" style="display:none;">
		<div class='boxnotice'><?php _e('By default Post ID type badges will reference the reviews that are tagged with the same Post ID that the badge is being displayed on. This could be downloaded reviews that are tagged with a Post ID and/or Submitted reviews. <b>Only check other pages below if you want to create a summary type badge that combines pages.</b>', 'wp-review-slider-pro'); ?>
		</div>
		<div id="tb_content_cat_search"><input id="tb_content_cat_search_input" data-custom="custom" type="text" name="tb_content_cat_search_input" placeholder="Type here to search..." value=""></div>
		<div class="wprev_loader_catlist" style="display:none;"></div>
		<table id="selectcatstable" class="wp-list-table widefat striped posts">
		</table>
	</div>
<a <?php if($currentbadge->badge_orgin=='postid'){echo "style=display:none;";} ?> id="wprevpro_btn_pickpages" class="button dashicons-before dashicons-yes "><?php _e('Select Locations', 'wp-review-slider-pro'); ?></a>
					<?php
					//current selection if editing
					if(isset($currentbadge->rpage)){
						$rpagejsondecode = json_decode($currentbadge->rpage);
						//print_r($rpagejsondecode);
						//stripslashes from pageids
						if(is_array($rpagejsondecode)){
							foreach ($rpagejsondecode as $key=>$value) {
							$rpagejsondecode[$key] = stripslashes($value);
							}
						} else {
							$rpagejsondecode=[''];
						}
					} else {
						$rpagejsondecode=[''];
					}
					if(!$rpagejsondecode){$rpagejsondecode=[''];}
					?>
					<div id="tb_content_page_select" style="display:none;">
					<table class="selectrevstable wp-list-table widefat striped posts">
						<tbody id="">
						<tr id="submittedbadgenotice" <?php if($currentbadge->badge_orgin!="submitted" && $currentbadge->badge_orgin!=""){echo 'style="display:none;"';} ?>><td><?php _e('By default Submitted type badges will reference the reviews for the page that it is being displayed on. Only check other pages if you want to create a summary type badge that combines pages.', 'wp-review-slider-pro'); ?></td></tr>
						<tr id="custombadgenotice" <?php if($currentbadge->badge_orgin!="custom" && $currentbadge->badge_orgin!=""){echo 'style="display:none;"';} ?>><td><?php _e('Leave blank if you wish to use all sources in this list.', 'wp-review-slider-pro'); ?></td></tr>
			
					
					<?php
					//pull distinct page names and page ids from reviews table
					$temptype ='custom';
					$tempquery = "select type from ".$reviews_table_name." group by pageid";
					$typearray = $wpdb->get_col($tempquery);
					$typearray = array_unique($typearray);
					$typearray = array_values($typearray);
					//add submitted to this
					array_push($typearray, "Submitted");
					
					//$typearray = unserialize(WPREV_TYPE_ARRAY);
					for($x=0;$x<count($typearray);$x++){
					$typelowercase = strtolower($typearray[$x]);
						if($currentbadge->badge_orgin==$typelowercase){
							$temptype = $typearray[$x];
						}
					}

					$reviews_table_name = $wpdb->prefix . 'wpfb_reviews';
					//$tempquery = "SELECT DISTINCT pageid,pagename,type,from_url FROM ".$reviews_table_name." WHERE pageid IS NOT NULL";
					$tempquery = "SELECT pageid,pagename,type,from_url FROM ".$reviews_table_name." WHERE pageid IS NOT NULL GROUP BY pageid";
					//$tempquery = "SELECT pageid,pagename,type,from_url FROM ".$reviews_table_name." GROUP BY pageid";

					$fbpagesrows = $wpdb->get_results($tempquery);
					
					//$wpdb->show_errors();
					//$wpdb->print_error();
					
					//die();
					if(count($fbpagesrows)>0){
					foreach ( $fbpagesrows as $fbpage ) 
					{
						if($fbpage->pageid!=""){
							$temppagelink='';
							if($fbpage->type=='Facebook'){
								$temppagelink="https://www.facebook.com/".$fbpage->pageid."/";
							} else if($fbpage->type=='Twitter'){
								$temppagelink="";
							} else{
								//if($fbpage->from_url_review!=''){
								//	$temppagelink=$fbpage->from_url_review;
								//} else {
									$temppagelink=urldecode($fbpage->from_url);
								//}
							}
							if($temptype==$fbpage->type || $temptype=="custom"){
								$temphide="";
							} else {
								$temphide="style='display:none;'";
							}
						?>
								<tr <?php echo $temphide;?> class="bo_<?php echo strtolower($fbpage->type);?>">
								<td>
								<input type="checkbox" data-rtype="<?php echo strtolower($fbpage->type);?>" class="pageselectclass" name="wprevpro_t_rpage[]" id="page_<?php echo $fbpage->pageid; ?>" value="<?php echo $fbpage->pageid; ?>"<?php if(in_array($fbpage->pageid, $rpagejsondecode)){echo 'checked="checked"';}?>><label for="page_<?php echo $fbpage->pageid; ?>"> <?php echo $fbpage->pagename.' ('.$fbpage->type.') - pageid: '.$fbpage->pageid.' - <a target="_blank" href="'.$temppagelink.'">Source Page</a>'; ?></label>
						
						<?php
						//find the totals and averages based on pageid
						//print_r($currentdatas);
						foreach ( $currentdatas as $currentdata ) 
							{
								if($currentdata->btp_id==$fbpage->pageid){
									?>
								<div id="badgedatapopup">
									<div class="badgedatapopupflex-child">
										<div class="bdp_tow"><span class="bdp_label">Source Site Total:</span><span class="bdp_value"><?php echo $currentdata->total; ?></span></div>
										<div class="bdp_tow"><span class="bdp_label">Source Site Avg:</span><span class="bdp_value"><?php echo $currentdata->avg; ?></span></div>
									</div>
									<div class="badgedatapopupflex-child">
										<div class="bdp_tow"><span class="bdp_label">Review List Total:</span><span class="bdp_value"><?php echo $currentdata->total_indb; ?></span></div>
										<div class="bdp_tow"><span class="bdp_label">Review List Avg:</span><span class="bdp_value"><?php echo $currentdata->avg_indb; ?></span></div>
									</div>
								</div>
									<?php
								}
							}
						?>
								</td>
								</tr>
						<?php
						}
					}
					} else {
						?>
						<tr><td><?php _e('Please go click the "Retrieve Reviews" button. Even if you have no new reviews it will update this list.', 'wp-review-slider-pro'); ?></td></tr>
						<?php
					}
					$numselpages = '';;
					if(count(array_filter($rpagejsondecode))>0){
						if(count(array_filter($rpagejsondecode))==1){
							$numselpages = "(".count(array_filter($rpagejsondecode))." ".__('Page Selected', 'wp-review-slider-pro').")";
						} else {
							$numselpages = "(".count(array_filter($rpagejsondecode))." ".__('Pages Selected', 'wp-review-slider-pro').")";
						}
					}
					if($currentbadge->badge_orgin=='postid'){
						$numselpages='';
					}
					?>
						</tbody>
					</table>
					</div>

								</div>
				<?php
				//echo $currentbadge->badge_misc;
				$badge_misc_array = json_decode($currentbadge->badge_misc, true);
				if(!is_array($badge_misc_array)){
					$badge_misc_array=array();
					$badge_misc_array['showstars']="";
					$badge_misc_array['bgcolor1']="";
					$badge_misc_array['bgcolor2']="";
					$badge_misc_array['bgcolor3']="";
					$badge_misc_array['starcolor']="";
					$badge_misc_array['tcolor1']="";
					$badge_misc_array['tcolor2']="";
					$badge_misc_array['tcolor3']="";
					$badge_misc_array['bradius']="0";
					$badge_misc_array['show_licon']="yes";
					$badge_misc_array['liconurl']="";
					$badge_misc_array['liconurllink']="";
					//$typearray = unserialize(WPREV_TYPE_ARRAY);
					for($x=0;$x<count($typearray);$x++){
						$typelowercase = strtolower($typearray[$x]);
						if($typelowercase!='manual' || $typelowercase!='submitted'){
							$badge_misc_array['si_'.$typelowercase.'_linkurl']="";
						}
					}
				}
				if(!isset($badge_misc_array['liconurllink'])){
					$badge_misc_array['liconurllink']='';
				}
				$tempsiconarray =[];
				if(isset($badge_misc_array['sicon'])){
					if(is_array($badge_misc_array['sicon'])){
						$tempsiconarray = $badge_misc_array['sicon'];
					}
				}
				if(!isset($badge_misc_array['c_text'])){
					$badge_misc_array['c_text']=__('Stars - Based on', 'wp-review-slider-pro');
					$badge_misc_array['c_text2']=__('User Reviews', 'wp-review-slider-pro');
				}
				if(!isset($badge_misc_array['c_text_b2'])){
					$badge_misc_array['c_text_b2']=__('reviews', 'wp-review-slider-pro');
				}
				if(!isset($badge_misc_array['ratingsfrom'])){
					$badge_misc_array['ratingsfrom']='download';
					$badge_misc_array['ratingsavg']='';
					$badge_misc_array['ratingstot']='';
				}
				if(!isset($badge_misc_array['liconurllink_target'])){
					$badge_misc_array['liconurllink_target']='';
				}
				if(!isset($badge_misc_array['shadow'])){
					$badge_misc_array['shadow']='';
				}
				if(!isset($badge_misc_array['liconurllink_attr'])){
					$badge_misc_array['liconurllink_attr']='';
				}
				if(!isset($badge_misc_array['c_text_url'])){
					$badge_misc_array['c_text_url']='';
				}
				if(!isset($badge_misc_array['c_text_url_title'])){
					$badge_misc_array['c_text_url_title']='';
				}
				if(!isset($badge_misc_array['slidelocation'])){
						$badge_misc_array['onclickaction']="";
						$badge_misc_array['onclickurl']="";
						$badge_misc_array['sliderevtemplate']="";
						//slideout settings
						$badge_misc_array['slidelocation']="btmrt";
						$badge_misc_array['slbgcolor1']="";
						$badge_misc_array['slbordercolor1']="";
						$badge_misc_array['slwidth']="400";
						$badge_misc_array['slheight']="400";
						$badge_misc_array['slpadding-top']="10";
						$badge_misc_array['slpadding-right']="10";
						$badge_misc_array['slpadding-bottom']="10";
						$badge_misc_array['slpadding-left']="10";
						$badge_misc_array['slideheader']="";
						$badge_misc_array['slidefooter']="";
				}
				if(!isset($badge_misc_array['slborderwidth'])){
					$badge_misc_array['slborderwidth']='1';
				}
				if(!isset($badge_misc_array['onclickurl_target'])){
					$badge_misc_array['onclickurl_target']='';
				}
				if(!isset($badge_misc_array['bwidth'])){
					$badge_misc_array['bwidth']='100';
					$badge_misc_array['bwidtht']='';
				}
				if(!isset($badge_misc_array['roundavg'])){
					$badge_misc_array['roundavg']='1';
				}
				if(!isset($badge_misc_array['si_custom2_imgurl'])){
					$badge_misc_array['si_custom2_imgurl']='';
					$badge_misc_array['si_custom2_linkurl']='';
					$badge_misc_array['si_custom3_imgurl']='';
					$badge_misc_array['si_custom3_linkurl']='';
				}
				if(!isset($badge_misc_array['outof'])){
					$badge_misc_array['outof']='5';
				}
				if(!isset($badge_misc_array['liconheight'])){
					$badge_misc_array['liconheight']='';
					$badge_misc_array['liconwidth']='';
				}

				?>

								<div <?php if($currentbadge->badge_orgin=='postid'){echo "style='display:none;'";} ?> class="wprevpre_temp_label_row t1oneonly ratingsfrom" <?php if($currentbadge->style=='2'){echo "style='display:none;'";} ?>>
								<?php _e('Ratings From:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row customratingsrow" <?php if($currentbadge->style=='2' || $badge_misc_array['ratingsfrom']!='input'){echo "style='display:none;'";} ?>>
								<?php _e('Average : Total', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row" >
								<?php _e('# Average Decimals:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row" >
								<?php _e('Average Out Of:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row t1oneonly" <?php if($currentbadge->style=='2'){echo "style=display:none;";} ?>>
								<?php _e('Show Stars:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row brdiv">
								<?php _e('Border Radius:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row wprevpre_bgcolor2 bsdiv">
								<?php _e('Border Shadow:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row bc1div">
								<?php _e('Border Color 1:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row bc2div wprevpre_bgcolor2">
								<?php _e('Border Color 2:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row bcdiv wprevpre_bgcolor2">
								<?php _e('Background Color:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row rowstarcolor">
								<?php _e('Star Color:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row tc1div">
								<?php _e('Text Color 1:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row wprevpre_tcolor2">
								<?php _e('Text Color 2:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row wprevpre_tcolor3">
								<?php _e('Text Color 3:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row badgewidth">
								<?php _e('Badge Width:', 'wp-review-slider-pro'); ?>
								</div>
							</div>
							<div class="wprevpre_temp_label_row" style="">
									<select name="wprevpro_template_style" id="wprevpro_template_style">
									  <option value="1" <?php if($currentbadge->style=='1' || $currentbadge->style==""){echo "selected";} ?>><?php _e('Style 1', 'wp-review-slider-pro'); ?></option>
									  <?php
									  if ( wrsp_fs()->can_use_premium_code() ) {
									  ?>
									  <option value="2" <?php if($currentbadge->style=='2'){echo "selected";} ?>><?php _e('Style 2', 'wp-review-slider-pro'); ?></option>
									  <option value="3" <?php if($currentbadge->style=='3'){echo "selected";} ?>><?php _e('Style 3', 'wp-review-slider-pro'); ?>
									   <option value="4" <?php if($currentbadge->style=='4'){echo "selected";} ?>><?php _e('Style 4', 'wp-review-slider-pro'); ?></option>
									   <option value="5" <?php if($currentbadge->style=='5'){echo "selected";} ?>><?php _e('Style 5', 'wp-review-slider-pro'); ?></option>
									   <option value="6" <?php if($currentbadge->style=='6'){echo "selected";} ?>><?php _e('Style 6', 'wp-review-slider-pro'); ?></option>
									   <option value="7" <?php if($currentbadge->style=='7'){echo "selected";} ?>><?php _e('Style 7', 'wp-review-slider-pro'); ?></option>
									  <?php
									  }
									  ?>
									</select>
								</div>
							<div class="w3_wprs-col s6">
								<div class="wprevpre_temp_label_row">
								<?php //print_r($typearray); ?>
									<select name="wprevpro_badge_orgin" id="wprevpro_badge_orgin">
									<option value="custom" <?php if($currentbadge->badge_orgin=='custom'){echo "selected";} ?>><?php _e('Custom', 'wp-review-slider-pro'); ?></option>
									<?php
									//loop through all types in db
	
									//$typearray = unserialize(WPREV_TYPE_ARRAY);
									  for($x=0;$x<count($typearray);$x++){
										$typelowercase = strtolower($typearray[$x]);
									?>
										<option value="<?php echo $typelowercase;?>" <?php if($currentbadge->badge_orgin==$typelowercase){echo "selected";} ?>><?php echo $typearray[$x];?></option>
									<?php
									  }
									?>
									  <option value="postid" <?php if($currentbadge->badge_orgin=='postid'){echo "selected";} ?>><?php _e('Post ID', 'wp-review-slider-pro'); ?></option>
									</select>
								</div>
								<div class="wprevpre_temp_label_row divpickpagesrow" <?php if($currentbadge->badge_orgin=='manual'){echo 'style="display:none;"';}; ?>>
								<span id="wprevpro_selectedpagesspan"> <?php echo $numselpages; ?></span>
								</div>
		
								<div class="wprevpre_temp_label_row t1oneonly ratingsfrom" <?php if($currentbadge->style=='2'){echo 'style="display:none;"';} ?>>
									<select name="wprevpro_badge_misc_customratingfrom" id="wprevpro_badge_misc_customratingfrom">
									  <option value="table" <?php if($badge_misc_array['ratingsfrom']=='table'){echo "selected";} ?>><?php _e('Source Site', 'wp-review-slider-pro'); ?></option>
									  <option value="db" <?php if($badge_misc_array['ratingsfrom']=='db'){echo "selected";} ?>><?php _e('Review List', 'wp-review-slider-pro'); ?></option>
									  <option value="input" <?php if($badge_misc_array['ratingsfrom']=='input'){echo "selected";} ?>><?php _e('Input', 'wp-review-slider-pro'); ?></option>
									</select>
								</div>
								<div class="wprevpre_temp_label_row customratingsrow" <?php if($currentbadge->style=='2' || $badge_misc_array['ratingsfrom']!='input'){echo "style='display:none;'";} ?>>
									<input style="width: 3em" id="wprevpro_badge_misc_cratingavg" data-custom="custom" type="text" name="wprevpro_badge_misc_cratingavg" placeholder="4.5" value="<?php echo $badge_misc_array['ratingsavg']; ?>">
									<input style="width: 3em" id="wprevpro_badge_misc_cratingtotal" data-custom="custom" type="text" name="wprevpro_badge_misc_cratingtotal" placeholder="17" value="<?php echo $badge_misc_array['ratingstot']; ?>">
								
								</div>
								<div class="wprevpre_temp_label_row" >
									<select name="wprevpro_badge_misc_roundavg" id="wprevpro_badge_misc_roundavg">
									<option value="0" <?php if($badge_misc_array['roundavg']=='0'){echo "selected";} ?>><?php _e('0', 'wp-review-slider-pro'); ?></option>
									  <option value="1" <?php if($badge_misc_array['roundavg']=='1'){echo "selected";} ?>><?php _e('1', 'wp-review-slider-pro'); ?></option>
									  <option value="2" <?php if($badge_misc_array['roundavg']=='2'){echo "selected";} ?>><?php _e('2', 'wp-review-slider-pro'); ?></option>
									  <option value="3" <?php if($badge_misc_array['roundavg']=='3'){echo "selected";} ?>><?php _e('3', 'wp-review-slider-pro'); ?></option>
									</select>
								</div>
								<div class="wprevpre_temp_label_row" >
									<select name="wprevpro_badge_misc_outof" id="wprevpro_badge_misc_outof">
									  <option value="5" <?php if($badge_misc_array['outof']=='5'){echo "selected";} ?>><?php _e('5', 'wp-review-slider-pro'); ?></option>
									  <option value="10" <?php if($badge_misc_array['outof']=='10'){echo "selected";} ?>><?php _e('10', 'wp-review-slider-pro'); ?></option>
									</select>
								</div>
								<div class="wprevpre_temp_label_row t1oneonly" <?php if($currentbadge->style=='2'){echo "style=display:none;";} ?>>
									<select name="wprevpro_badge_misc_showstars" id="wprevpro_badge_misc_showstars">
									  <option value="yes" <?php if($badge_misc_array['showstars']=='yes'){echo "selected";} ?>><?php _e('Yes', 'wp-review-slider-pro'); ?></option>
									  <option value="no" <?php if($badge_misc_array['showstars']=='no'){echo "selected";} ?>><?php _e('No', 'wp-review-slider-pro'); ?></option>
									</select>
								</div>
								<div class="wprevpre_temp_label_row brdiv">
									<input id="wprevpro_badge_misc_bradius" type="number" min="0" name="wprevpro_badge_misc_bradius" placeholder="" value="<?php echo $badge_misc_array['bradius']; ?>" style="width: 4em">
								</div>
								<div class="wprevpre_temp_label_row bsdiv">
									<select name="wprevpro_badge_misc_shadow" id="wprevpro_badge_misc_shadow">
									  <option value="" <?php if($badge_misc_array['shadow']==''){echo "selected";} ?>><?php _e('Yes', 'wp-review-slider-pro'); ?></option>
									  <option value="no" <?php if($badge_misc_array['shadow']=='no'){echo "selected";} ?>><?php _e('No', 'wp-review-slider-pro'); ?></option>
									</select>
								</div>
								<div class="wprevpre_temp_label_row wprevpre_bgcolor1 bc1div">
									<input type="text" data-alpha-enabled="true" value="<?php echo $badge_misc_array['bgcolor1']; ?>" name="wprevpro_badge_misc_bgcolor1" id="wprevpro_badge_misc_bgcolor1" data-alpha-enabled="true" class="my-color-field" />
								</div>
								<div class="wprevpre_temp_label_row wprevpre_bgcolor2 bc2div">
									<input type="text" data-alpha-enabled="true" value="<?php echo $badge_misc_array['bgcolor3']; ?>" name="wprevpro_badge_misc_bgcolor3" id="wprevpro_badge_misc_bgcolor3" data-alpha-enabled="true" class="my-color-field" />
								</div>
								<div class="wprevpre_temp_label_row wprevpre_bgcolor2 bcdiv">
									<input type="text" data-alpha-enabled="true" value="<?php echo $badge_misc_array['bgcolor2']; ?>" name="wprevpro_badge_misc_bgcolor2" id="wprevpro_badge_misc_bgcolor2" data-alpha-enabled="true" class="my-color-field" />
								</div>
								<div class="wprevpre_temp_label_row wprevpre_starcolor rowstarcolor">
									<input type="text" value="<?php echo $badge_misc_array['starcolor']; ?>" name="wprevpro_badge_misc_starcolor" id="wprevpro_badge_misc_starcolor" data-alpha-enabled="true" class="my-color-field" />
								</div>
								<div class="wprevpre_temp_label_row wprevpre_tcolor1 tc1div">
									<input type="text" value="<?php echo $badge_misc_array['tcolor1']; ?>" name="wprevpro_badge_misc_tcolor1" id="wprevpro_badge_misc_tcolor1" data-alpha-enabled="true" class="my-color-field" />
								</div>
								<div class="wprevpre_temp_label_row wprevpre_tcolor2">
									<input type="text" value="<?php echo $badge_misc_array['tcolor2']; ?>" name="wprevpro_badge_misc_tcolor2" id="wprevpro_badge_misc_tcolor2" data-alpha-enabled="true" class="my-color-field" />
								</div>
								<div class="wprevpre_temp_label_row wprevpre_tcolor3">
									<input type="text" value="<?php echo $badge_misc_array['tcolor3']; ?>" name="wprevpro_badge_misc_tcolor3" id="wprevpro_badge_misc_tcolor3" data-alpha-enabled="true" class="my-color-field" />
								</div>
								<div class="wprevpre_temp_label_row badgewidth">
									<input id="wprevpro_badge_misc_width" type="number" min="0" name="wprevpro_badge_misc_width" placeholder="" value="<?php echo $badge_misc_array['bwidth']; ?>" style="width: 4em">
									<select name="wprevpro_badge_misc_widtht" id="wprevpro_badge_misc_widtht">
									  <option value="" <?php if($badge_misc_array['bwidtht']==''){echo "selected";} ?>><?php _e('%', 'wp-review-slider-pro'); ?></option>
									  <option value="px" <?php if($badge_misc_array['bwidtht']=='px'){echo "selected";} ?>><?php _e('px', 'wp-review-slider-pro'); ?></option>
									</select>
								</div>
								
								
								<a id="wprevpro_pre_resetbtn" class="button"><?php _e('Reset Settings', 'wp-review-slider-pro'); ?></a>
							</div>
						  </div>
						  <div class="w3_wprs-col s6" id="">
						  		<?php 
								//add cursor style if overclick is set
								if($badge_misc_array['onclickaction']!=''){
								echo '<style>.wprevpro_badge{cursor: pointer;}</style>';
								}
								?>
							  <div class="w3_wprs-col s12" id="wprevpro_template_preview">
								<?php 
								//preview will show here
								?>
							  </div>
							  <div class="w3_wprs-col s12 t2onlysource" style="color:red"><?php _e('<b>Note</b>: If you use Ratings From: Source Site for badge style 2, then the plugin will to try calculate the individual totals for each rating. The more reviews you have downloaded, the more accurate it will be.', 'wp-review-slider-pro'); ?></div>
							  <div class="w3_wprs-col s12" id="totalavgwarningmsg">
							  <?php _e('Note: For Review Origin of "Submitted" or "Post Id" the total and average rating above is just an example and does not reflect the actual values that will display on your page.', 'wp-review-slider-pro'); ?>
							  </div>
							  <div class="w3_wprs-col s12" id="wprevpro_gbadge_notice" style="display:none;">
							  <?php _e('Note: Google does not return the total number of reviews in their API. It will default to what has actually been downloaded. Use the "Ratings From" field to input it manually.', 'wp-review-slider-pro'); ?>
							  </div>
							  <div class="w3_wprs-col s12" id="div_icon_options">
							  <div class="t1oneonly lgicondiv">
								<div class="w3_wprs-col s12" style="margin-bottom: 10px;"><b><?php _e('Large Icon Options:', 'wp-review-slider-pro'); ?></b></div>
								<div class="w3_wprs-col s12">
								<?php _e('Show Large Icon:', 'wp-review-slider-pro'); ?>
								<select name="wprevpro_badge_misc_show_licon" id="wprevpro_badge_misc_show_licon">
									  <option value="yes" <?php if($badge_misc_array['show_licon']=='yes'){echo "selected";} ?>><?php _e('Yes', 'wp-review-slider-pro'); ?></option>
									  <option value="no" <?php if($badge_misc_array['show_licon']=='no'){echo "selected";} ?>><?php _e('No', 'wp-review-slider-pro'); ?></option>
									</select>
								</div>
								<div class="w3_wprs-col s12">
								 <?php _e('Icon Image URL:', 'wp-review-slider-pro'); ?>
								<input id="wprevpro_badge_misc_liconurl" data-custom="custom" type="text" name="wprevpro_badge_misc_liconurl" placeholder="" value="<?php echo $badge_misc_array['liconurl']; ?>"><a id="upload_licon_button" class="button"><?php _e('Upload', 'wp-review-slider-pro'); ?></a>
								</div>
								<div class="w3_wprs-col s12">
								 <?php _e('Icon Link URL:', 'wp-review-slider-pro'); ?>
								<input id="wprevpro_badge_misc_liconurllink" data-custom="custom" type="text" name="wprevpro_badge_misc_liconurllink" placeholder="" value="<?php echo $badge_misc_array['liconurllink']; ?>">
								
								<input id="wprevpro_badge_misc_liconwidth" data-custom="custom" type="hidden" name="wprevpro_badge_misc_liconwidth" placeholder="" value="<?php echo $badge_misc_array['liconwidth']; ?>">
								<input id="wprevpro_badge_misc_liconheight" data-custom="custom" type="hidden" name="wprevpro_badge_misc_liconheight" placeholder="" value="<?php echo $badge_misc_array['liconheight']; ?>">
								
								</div>
								</div>
								<div class="w3_wprs-col s12 smallicondiv">
									<div class="w3_wprs-col s12" id='ssilabel'>
										<b><?php _e('Show Small Icons:', 'wp-review-slider-pro'); ?></b>
									</div>
									<div id="divtemplatestyles">
									
									<?php 
									$tempquery = "select type from ".$reviews_table_name." group by type";
									$typearray = $wpdb->get_col($tempquery);
									 for($x=0;$x<count($typearray );$x++){
										$typelowercase = strtolower($typearray [$x]);
										if($typelowercase!='manual' && $typelowercase!='submitted' && $typelowercase!='woocommerce'){
									?>
									<div class='smi_iconchecks'>
										<input type="checkbox" class="wprevpro_badge_sm_ck" name="wprevpro_badge_sicon[]" id="wprevpro_badge_sm_<?php echo $typelowercase;?>" value="<?php echo $typelowercase;?>" <?php if (in_array($typelowercase, $tempsiconarray)){ echo "checked";} ?>><label for="wprevpro_badge_sm_<?php echo $typelowercase;?>"><?php echo $typearray [$x];?></label>
									</div>
									<div <?php if (!in_array($typelowercase, $tempsiconarray)){ echo "style='display:none;'";} ?> class="si_textinput w3_wprs-col s12 div_wprevpro_badge_misc_si_<?php echo $typelowercase;?>_linkurl">
										 <?php _e('Optional Link URL:', 'wp-review-slider-pro'); ?>
										<input id="wprevpro_badge_misc_si_<?php echo $typelowercase;?>_linkurl" data-custom="custom" type="text" name="wprevpro_badge_misc_si_<?php echo $typelowercase;?>_linkurl" placeholder="" value="<?php if(isset($badge_misc_array['si_'.$typelowercase.'_linkurl'])){echo $badge_misc_array['si_'.$typelowercase.'_linkurl'];} ?>">
									</div>
									<?php
										}
									 }
									?>
									<div class="smi_iconchecks">
										<input type="checkbox" class="wprevpro_badge_sm_ck" name="wprevpro_badge_sicon[]" id="wprevpro_badge_sm_custom" value="custom" <?php if (in_array('custom', $tempsiconarray)){ echo "checked";} ?>><label for="wprevpro_badge_sm_custom"><?php _e('Custom 1', 'wp-review-slider-pro'); ?></label>
									</div>
									<div <?php if (!in_array('custom', $tempsiconarray)){ echo "style='display:none;'";} ?>  class="w3_wprs-col s12 si_textinput">
										 <?php _e('Icon Image URL:', 'wp-review-slider-pro'); ?>
										<input id="wprevpro_badge_misc_si_custom_imgurl" data-custom="custom" type="text" name="wprevpro_badge_misc_si_custom_imgurl" placeholder="" value="<?php if(isset($badge_misc_array['si_custom_imgurl'])){echo $badge_misc_array['si_custom_imgurl'];} ?>"><a id="upload_sicon_button" class="button upload_sicustom_btn"><?php _e('Upload', 'wp-review-slider-pro'); ?></a>
									</div>
									<div <?php if (!in_array('custom', $tempsiconarray)){ echo "style='display:none;'";} ?> class="si_textinput w3_wprs-col s12 div_wprevpro_badge_misc_si_custom_linkurl">
										 <?php _e('Optional Link URL:', 'wp-review-slider-pro'); ?>
										<input id="wprevpro_badge_misc_si_custom_linkurl" data-custom="custom" type="text" name="wprevpro_badge_misc_si_custom_linkurl" placeholder="" value="<?php if(isset($badge_misc_array['si_custom_linkurl'])){echo $badge_misc_array['si_custom_linkurl'];} ?>">
									</div>
									
									<div class="smi_iconchecks">
										<input type="checkbox" class="wprevpro_badge_sm_ck" name="wprevpro_badge_sicon[]" id="wprevpro_badge_sm_custom2" value="custom2" <?php if (in_array('custom2', $tempsiconarray)){ echo "checked";} ?>><label for="wprevpro_badge_sm_custom2"><?php _e('Custom 2', 'wp-review-slider-pro'); ?></label>
									</div>
									<div <?php if (!in_array('custom2', $tempsiconarray)){ echo "style='display:none;'";} ?>  class="w3_wprs-col s12 si_textinput">
										 <?php _e('Icon Image URL:', 'wp-review-slider-pro'); ?>
										<input id="wprevpro_badge_misc_si_custom2_imgurl" data-custom="custom" type="text" name="wprevpro_badge_misc_si_custom2_imgurl" placeholder="" value="<?php if(isset($badge_misc_array['si_custom2_imgurl'])){echo $badge_misc_array['si_custom2_imgurl'];} ?>"><a id="upload_sicon_button2" class="button upload_sicustom_btn"><?php _e('Upload', 'wp-review-slider-pro'); ?></a>
									</div>
									<div <?php if (!in_array('custom2', $tempsiconarray)){ echo "style='display:none;'";} ?> class="si_textinput w3_wprs-col s12 div_wprevpro_badge_misc_si_custom2_linkurl">
										 <?php _e('Optional Link URL:', 'wp-review-slider-pro'); ?>
										<input id="wprevpro_badge_misc_si_custom2_linkurl" data-custom="custom" type="text" name="wprevpro_badge_misc_si_custom2_linkurl" placeholder="" value="<?php if(isset($badge_misc_array['si_custom2_linkurl'])){echo $badge_misc_array['si_custom2_linkurl'];} ?>">
									</div>
									
									<div class="smi_iconchecks">
										<input type="checkbox" class="wprevpro_badge_sm_ck" name="wprevpro_badge_sicon[]" id="wprevpro_badge_sm_custom3" value="custom3" <?php if (in_array('custom3', $tempsiconarray)){ echo "checked";} ?>><label for="wprevpro_badge_sm_custom3"><?php _e('Custom 3', 'wp-review-slider-pro'); ?></label>
									</div>
									<div <?php if (!in_array('custom3', $tempsiconarray)){ echo "style='display:none;'";} ?>  class="w3_wprs-col s12 si_textinput">
										 <?php _e('Icon Image URL:', 'wp-review-slider-pro'); ?>
										<input id="wprevpro_badge_misc_si_custom3_imgurl" data-custom="custom" type="text" name="wprevpro_badge_misc_si_custom3_imgurl" placeholder="" value="<?php if(isset($badge_misc_array['si_custom3_imgurl'])){echo $badge_misc_array['si_custom3_imgurl'];} ?>"><a id="upload_sicon_button2" class="button upload_sicustom_btn"><?php _e('Upload', 'wp-review-slider-pro'); ?></a>
									</div>
									<div <?php if (!in_array('custom3', $tempsiconarray)){ echo "style='display:none;'";} ?> class="si_textinput w3_wprs-col s12 div_wprevpro_badge_misc_si_custom3_linkurl">
										 <?php _e('Optional Link URL:', 'wp-review-slider-pro'); ?>
										<input id="wprevpro_badge_misc_si_custom3_linkurl" data-custom="custom" type="text" name="wprevpro_badge_misc_si_custom3_linkurl" placeholder="" value="<?php if(isset($badge_misc_array['si_custom3_linkurl'])){echo $badge_misc_array['si_custom3_linkurl'];} ?>">
									</div>

								</div>
							  </div>
							  
							  <div class="w3_wprs-col s12">
									<div class="w3_wprs-col s12" id='ssilabel'>
										<b><?php _e('Link Attributes:', 'wp-review-slider-pro'); ?></b>
									</div>
									<div>
									<div class="w3_wprs-col s12">
								<?php _e('Link Target:', 'wp-review-slider-pro'); ?>
								<select name="wprevpro_badge_misc_liconurllink_target" id="wprevpro_badge_misc_liconurllink_target">
									  <option value="new" <?php if($badge_misc_array['liconurllink_target']=='new' || $badge_misc_array['liconurllink_target']==''){echo "selected";} ?>><?php _e('New Window', 'wp-review-slider-pro'); ?></option>
									  <option value="same" <?php if($badge_misc_array['liconurllink_target']=='same'){echo "selected";} ?>><?php _e('Same Window', 'wp-review-slider-pro'); ?></option>
									</select>
								</div>

									<div class='smi_iconchecks'>
									<?php _e('Link rel:', 'wp-review-slider-pro'); ?>
										<select name="wprevpro_badge_misc_liconurllink_attr" id="wprevpro_badge_misc_liconurllink_attr">
											<option value="" <?php if($badge_misc_array['liconurllink_attr']==''){echo "selected";} ?>>rel="nofollow"</option>
											<option value="noreferrer" <?php if($badge_misc_array['liconurllink_attr']=='noreferrer'){echo "selected";} ?>>rel="noreferrer"</option>
											<option value="norefnofol" <?php if($badge_misc_array['liconurllink_attr']=='norefnofol'){echo "selected";} ?>>rel="noreferrer nofollow"</option>
											<option value="follow" <?php if($badge_misc_array['liconurllink_attr']=='follow'){echo "selected";} ?>>rel=""</option>
										</select>
									</div>
									
									

								</div>
							  </div>

						  </div>
					</div>
				</td>
			</tr>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Custom Text:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<input class="t1oneonly" id="wprevpro_badge_misc_ctext" data-custom="custom" type="text" name="wprevpro_badge_misc_ctext" placeholder="Stars - Based on" value="<?php echo $badge_misc_array['c_text']; ?>">
					<input class="t1oneonly" id="wprevpro_badge_misc_ctext2" data-custom="custom" type="text" name="wprevpro_badge_misc_ctext2" placeholder="User Reviews" value="<?php echo $badge_misc_array['c_text2']; ?>">
					<input class="t2oneonly" id="wprevpro_badge_misc_ctext_b2" data-custom="custom" type="text" name="wprevpro_badge_misc_ctext_b2" placeholder="reviews" value="<?php echo $badge_misc_array['c_text_b2']; ?>">
					
					<p class="description">
					<?php _e('Change the text used in the badge.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
			<tr class="wprevpro_row t1oneonly">
				<th scope="row">
					<?php _e('Custom Text URL:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<input class="t1oneonly" id="wprevpro_badge_url_ctext" data-custom="custom" type="text" name="wprevpro_badge_misc_ctext_url" placeholder="" value="<?php echo $badge_misc_array['c_text_url']; ?>">
					
					<p class="description">
					<?php _e('Add the URL used at the custom text.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
            <tr class="wprevpro_row t1oneonly">
				<th scope="row">
					<?php _e('Custom Text URL Title:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<input class="t1oneonly" id="wprevpro_badge_title_url_ctext" data-custom="custom" type="text" name="wprevpro_badge_misc_ctext_url_title" placeholder="" value="<?php echo $badge_misc_array['c_text_url_title']; ?>">
					
					<p class="description">
					<?php _e('Add the URL Title used at the custom text.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>  
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Custom CSS:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<textarea name="wprevpro_badge_css" id="wprevpro_badge_css" cols="50" rows="4"><?php echo $currentbadge->badge_css; ?></textarea>
					<p class="description">
					<?php _e('Enter custom CSS code to change the look of the template even more when being displayed.</br>Example Style 1:', 'wp-review-slider-pro'); ?> <b>.wppro_badge1_DIV_1 {width: 300px;}</b></p>
				</td>
			</tr>
			
						<tr class="wprevpro_row">
						<th scope="row">
							<?php _e('Google Rich Snippet:	', 'wp-review-slider-pro'); ?>			
						</th>
						<td>
						<div class="divtemplatestyles">
							<label for="google_snippet_add"><?php _e('Add a Summary Review Google Rich Snippet?', 'wp-review-slider-pro'); ?></label>
							<select name="wprevpro_t_google_snippet_add" id="wprevpro_t_google_snippet_add">
								<option value="no" <?php if($currentbadge->google_snippet_add!="yes"){echo "selected";} ?>><?php _e('No', 'wp-review-slider-pro'); ?></option>
								<option value="yes" <?php if($currentbadge->google_snippet_add=="yes"){echo "selected";} ?>><?php _e('Yes', 'wp-review-slider-pro'); ?></option>
							</select>

							<div id="snippetsettings" class="row snisettingsdivs mt5 mb5" <?php if($currentbadge->google_snippet_add!="yes"){echo "style='display:none;'";} ?>>
							<p class="description"><b><?php _e('Note:', 'wp-review-slider-pro'); ?></b> </br>-<?php _e('Only turn this on for one review or badge template per a page or you will get duplicate rich snippets, which Google may not like.', 'wp-review-slider-pro'); ?></br>-<?php _e('Leave the Name, Description, and Image blank and the plugin will try to pull the info from the Post/Page.', 'wp-review-slider-pro'); ?> </p>
								<div class="w3_wprs-col s4 googlesnippetsettingsdiv"><?php _e('Type:', 'wp-review-slider-pro'); ?></div>
								<div class="w3_wprs-col s8 googlesnippetsettingsdiv">
									<select name="wprevpro_t_google_snippet_type" id="wprevpro_t_google_snippet_type">
									<option value="Product" <?php if($currentbadge->google_snippet_type=='Product'){echo "selected";} ?>>Product</option>
									<option value="LocalBusiness" <?php if($currentbadge->google_snippet_type=='LocalBusiness'){echo "selected";} ?>>Local Business</option>
									<option value="">-------</option>
									<option value="AnimalShelter" <?php if($currentbadge->google_snippet_type=='AnimalShelter'){echo "selected";} ?>>AnimalShelter</option>
									<option value="ArchiveOrganization" <?php if($currentbadge->google_snippet_type=='ArchiveOrganization'){echo "selected";} ?>>ArchiveOrganization</option>
									<option value="AutomotiveBusiness" <?php if($currentbadge->google_snippet_type=='AutomotiveBusiness'){echo "selected";} ?>>AutomotiveBusiness</option>
									<option value="ChildCare" <?php if($currentbadge->google_snippet_type=='ChildCare'){echo "selected";} ?>>ChildCare</option>
									<option value="Dentist" <?php if($currentbadge->google_snippet_type=='Dentist'){echo "selected";} ?>>Dentist</option>
									<option value="DryCleaningOrLaundry" <?php if($currentbadge->google_snippet_type=='DryCleaningOrLaundry'){echo "selected";} ?>>DryCleaningOrLaundry</option>
									<option value="EmergencyService" <?php if($currentbadge->google_snippet_type=='EmergencyService'){echo "selected";} ?>>EmergencyService</option>
									<option value="EmploymentAgency" <?php if($currentbadge->google_snippet_type=='EmploymentAgency'){echo "selected";} ?>>EmploymentAgency</option>
									<option value="EntertainmentBusiness" <?php if($currentbadge->google_snippet_type=='EntertainmentBusiness'){echo "selected";} ?>>EntertainmentBusiness</option>
									<option value="FinancialService" <?php if($currentbadge->google_snippet_type=='FinancialService'){echo "selected";} ?>>FinancialService</option>
									<option value="FoodEstablishment" <?php if($currentbadge->google_snippet_type=='FoodEstablishment'){echo "selected";} ?>>FoodEstablishment</option>
									<option value="GovernmentOffice" <?php if($currentbadge->google_snippet_type=='GovernmentOffice'){echo "selected";} ?>>GovernmentOffice</option>
									<option value="HealthAndBeautyBusiness" <?php if($currentbadge->google_snippet_type=='HealthAndBeautyBusiness'){echo "selected";} ?>>HealthAndBeautyBusiness</option>
									<option value="HomeAndConstructionBusiness" <?php if($currentbadge->google_snippet_type=='HomeAndConstructionBusiness'){echo "selected";} ?>>HomeAndConstructionBusiness</option>
									<option value="HVACBusiness" <?php if($currentbadge->google_snippet_type=='HVACBusiness'){echo "selected";} ?>>HVACBusiness</option>
									<option value="InternetCafe" <?php if($currentbadge->google_snippet_type=='InternetCafe'){echo "selected";} ?>>InternetCafe</option>
									<option value="LegalService" <?php if($currentbadge->google_snippet_type=='LegalService'){echo "selected";} ?>>LegalService</option>
									<option value="Library" <?php if($currentbadge->google_snippet_type=='Library'){echo "selected";} ?>>Library</option>
									<option value="LodgingBusiness" <?php if($currentbadge->google_snippet_type=='LodgingBusiness'){echo "selected";} ?>>LodgingBusiness</option>
									<option value="MedicalBusiness" <?php if($currentbadge->google_snippet_type=='MedicalBusiness'){echo "selected";} ?>>MedicalBusiness</option>
									<option value="ProfessionalService" <?php if($currentbadge->google_snippet_type=='ProfessionalService'){echo "selected";} ?>>ProfessionalService</option>
									<option value="RadioStation" <?php if($currentbadge->google_snippet_type=='RadioStation'){echo "selected";} ?>>RadioStation</option>
									<option value="RealEstateAgent" <?php if($currentbadge->google_snippet_type=='RealEstateAgent'){echo "selected";} ?>>RealEstateAgent</option>
									<option value="RecyclingCenter" <?php if($currentbadge->google_snippet_type=='RecyclingCenter'){echo "selected";} ?>>RecyclingCenter</option>
									<option value="SelfStorage" <?php if($currentbadge->google_snippet_type=='SelfStorage'){echo "selected";} ?>>SelfStorage</option>
									<option value="ShoppingCenter" <?php if($currentbadge->google_snippet_type=='ShoppingCenter'){echo "selected";} ?>>ShoppingCenter</option>
									<option value="SportsActivityLocation" <?php if($currentbadge->google_snippet_type=='SportsActivityLocation'){echo "selected";} ?>>SportsActivityLocation</option>
									<option value="Store" <?php if($currentbadge->google_snippet_type=='Store'){echo "selected";} ?>>Store</option>
									<option value="TelevisionStation" <?php if($currentbadge->google_snippet_type=='TelevisionStation'){echo "selected";} ?>>TelevisionStation</option>
									<option value="TouristInformationCenter" <?php if($currentbadge->google_snippet_type=='TouristInformationCenter'){echo "selected";} ?>>TouristInformationCenter</option>
									<option value="TravelAgency" <?php if($currentbadge->google_snippet_type=='TravelAgency'){echo "selected";} ?>>TravelAgency</option>
									
									
									</select>
								</div>
								<div class="w3_wprs-col s4 googlesnippetsettingsdiv "><?php _e('Business or Product Name:', 'wp-review-slider-pro'); ?></div>
								<div class="w3_wprs-col s8 googlesnippetsettingsdiv ">
									<input id="wprevpro_t_google_snippet_name" type="text" name="wprevpro_t_google_snippet_name" placeholder="" value="<?php if($currentbadge->google_snippet_name!=""){echo stripslashes($currentbadge->google_snippet_name);} ?>" style="width: 10em">
								</div>
								<div class="w3_wprs-col s4 googlesnippetsettingsdiv  "><?php _e('Description:', 'wp-review-slider-pro'); ?></div>
								<div class="w3_wprs-col s8 googlesnippetsettingsdiv  ">
									<input id="wprevpro_t_google_snippet_desc" type="text"  name="wprevpro_t_google_snippet_desc" placeholder="" value="<?php if($currentbadge->google_snippet_desc!=""){echo stripslashes($currentbadge->google_snippet_desc);} ?>" style="width: 20em">
								</div>
								<div class="w3_wprs-col s4 googlesnippetsettingsdiv  "><?php _e('Logo URL:', 'wp-review-slider-pro'); ?></div>
								<div class="w3_wprs-col s8 googlesnippetsettingsdiv  ">
									<input id="wprevpro_t_google_snippet_business_image" type="text"  name="wprevpro_t_google_snippet_business_image" placeholder="" value="<?php if($currentbadge->google_snippet_business_image!=""){echo $currentbadge->google_snippet_business_image;} ?>" style="width: 30em">
								</div>
								<div id="businessrichsnippetfields" <?php if($currentbadge->google_snippet_type=="Product"){echo "style='display:none;'";} ?>>
								<?php
								//get rich snippet more json and convert to array
								//echo $currentbadge->badge_misc;
								if(!isset($currentbadge->google_snippet_more)){
									$currentbadge->google_snippet_more='';
								}
								$google_misc_array = json_decode($currentbadge->google_snippet_more, true);
								if(!is_array($google_misc_array)){
									$google_misc_array=array();
									$google_misc_array['telephone']="";
									$google_misc_array['priceRange']="";
									$google_misc_array['streetAddress']="";
									$google_misc_array['addressLocality']="";
									$google_misc_array['addressRegion']="";
									$google_misc_array['postalCode']="";
								}
								if(!isset($google_misc_array['brand'])){
									$google_misc_array['brand']="";
									$google_misc_array['price']="";
									$google_misc_array['priceCurrency']="";
									$google_misc_array['url']="";
									$google_misc_array['sku']="";
									$google_misc_array['giname']="";
									$google_misc_array['gival']="";
									$google_misc_array['availability']="";
									$google_misc_array['priceValidUntil']="";
								}
								
								if(!isset($google_misc_array['schemaid'])){
								$google_misc_array['schemaid']="";
								}
								
								?>
									<div class="w3_wprs-col s4 googlesnippetsettingsdiv  "><?php _e('Schema @id (optional):', 'wp-review-slider-pro'); ?></div>
									<div class="w3_wprs-col s8 googlesnippetsettingsdiv  ">
										<input id="wprevpro_t_google_snippet_more_schemaid" type="text" name="wprevpro_t_google_snippet_more_schemaid" placeholder="" value="<?php if($google_misc_array['schemaid']!=''){echo $google_misc_array['schemaid'];} ?>" style="width: 10em">
									</div>
									<div class="w3_wprs-col s4 googlesnippetsettingsdiv  "><?php _e('Phone (optional):', 'wp-review-slider-pro'); ?></div>
									<div class="w3_wprs-col s8 googlesnippetsettingsdiv  ">
										<input id="wprevpro_t_google_snippet_more_phone" type="text" name="wprevpro_t_google_snippet_more_phone" placeholder="" value="<?php if($google_misc_array['telephone']!=''){echo $google_misc_array['telephone'];} ?>" style="width: 10em">
									</div>
									<div class="w3_wprs-col s4 googlesnippetsettingsdiv "><?php _e('Price Range (optional): Ex: $$$', 'wp-review-slider-pro'); ?></div>
									<div class="w3_wprs-col s8 googlesnippetsettingsdiv ">
										<input id="wprevpro_t_google_snippet_more_price" type="text" name="wprevpro_t_google_snippet_more_price" placeholder="" value="<?php if($google_misc_array['priceRange']!=''){echo $google_misc_array['priceRange'];} ?>" style="width: 6em">
									</div>
									<div class="w3_wprs-col s4 googlesnippetsettingsdiv  "><?php _e('Address (optional):', 'wp-review-slider-pro'); ?></div>
									<div class="w3_wprs-col s8 googlesnippetsettingsdiv ">
										<input id="wprevpro_t_google_snippet_more_street" type="text" name="wprevpro_t_google_snippet_more_street" placeholder="Street" value="<?php if($google_misc_array['streetAddress']!=''){echo stripslashes($google_misc_array['streetAddress']);} ?>" style="width: 15em">
										<input id="wprevpro_t_google_snippet_more_city" type="text" name="wprevpro_t_google_snippet_more_city" placeholder="City (Locality)" value="<?php if($google_misc_array['addressLocality']!=''){echo stripslashes($google_misc_array['addressLocality']);} ?>" style="width: 15em">
										<input id="wprevpro_t_google_snippet_more_state" type="text" name="wprevpro_t_google_snippet_more_state" placeholder="State (Region)" value="<?php if($google_misc_array['addressRegion']!=''){echo stripslashes($google_misc_array['addressRegion']);} ?>" style="width: 15em">
										<input id="wprevpro_t_google_snippet_more_zip" type="text" name="wprevpro_t_google_snippet_more_zip" placeholder="Zip (Postal Code)" value="<?php if($google_misc_array['postalCode']!=''){echo $google_misc_array['postalCode'];} ?>" style="width: 10em">
									</div>
								
								</div>
								<div id="productrichsnippetfields" <?php if($currentbadge->google_snippet_type!="Product"){echo "style='display:none;'";} ?>>
									<div class="w3_wprs-col s4 googlesnippetsettingsdiv "><?php _e('Brand (optional):', 'wp-review-slider-pro'); ?></div>
									<div class="w3_wprs-col s8 googlesnippetsettingsdiv ">
										<input id="wprevpro_t_google_snippet_prodbrand" type="text" name="wprevpro_t_google_snippet_prodbrand" placeholder="" value="<?php if($google_misc_array['brand']!=''){echo $google_misc_array['brand'];} ?>" style="width: 10em">
									</div>
									<div class="w3_wprs-col s4 googlesnippetsettingsdiv  "><?php _e('Offer (optional):', 'wp-review-slider-pro'); ?></div>
									<div class="w3_wprs-col s8 googlesnippetsettingsdiv ">
										<div class="w3_wprs-col s12 googlesnippetsettingsdiv  ">
											<div class="w3_wprs-col  s4"><?php _e('price (ex. 119.99):', 'wp-review-slider-pro'); ?></div>
											<div class="w3_wprs-col  s8"><input id="wprevpro_t_google_snippet_prodprice" type="text" name="wprevpro_t_google_snippet_prodprice" placeholder="" value="<?php if($google_misc_array['price']!=''){echo stripslashes($google_misc_array['price']);} ?>" style="width: 10em"></div>
										</div>
										<div class="w3_wprs-col s12 googlesnippetsettingsdiv  ">
											<div class="w3_wprs-col  s4"><?php _e('priceCurrency (ex. USD):', 'wp-review-slider-pro'); ?></div>
											<div class="w3_wprs-col  s8"><input id="wprevpro_t_google_snippet_prodpricec" type="text" name="wprevpro_t_google_snippet_prodpricec" placeholder="" value="<?php if($google_misc_array['priceCurrency']!=''){echo stripslashes($google_misc_array['priceCurrency']);} ?>" style="width: 10em"></div>
										</div>
										<div class="w3_wprs-col s12 googlesnippetsettingsdiv  ">
											<div class="w3_wprs-col  s4"><?php _e('Offer URL:', 'wp-review-slider-pro'); ?></div>
											<div class="w3_wprs-col  s8"><input id="wprevpro_t_google_snippet_produrl" type="url" name="wprevpro_t_google_snippet_produrl" placeholder="" value="<?php if($google_misc_array['url']!=''){echo stripslashes($google_misc_array['url']);} ?>" style="width: 10em"></div>
										</div>
										<div class="w3_wprs-col s12 googlesnippetsettingsdiv  ">
											<div class="w3_wprs-col  s4"><?php _e('priceValidUntil (ex. 2021-11-25):', 'wp-review-slider-pro'); ?></div>
											<div class="w3_wprs-col  s8"><input id="wprevpro_t_google_snippet_prodpriceValidUntil" type="text" name="wprevpro_t_google_snippet_prodpriceValidUntil" placeholder="" value="<?php if($google_misc_array['priceValidUntil']!=''){echo stripslashes($google_misc_array['priceValidUntil']);} ?>" style="width: 10em"></div>
										</div>
										<div class="w3_wprs-col s12 googlesnippetsettingsdiv  ">
											<div class="w3_wprs-col  s4"><?php _e('availability (ex. InStock):', 'wp-review-slider-pro'); ?></div>
											<div class="w3_wprs-col  s8"><input id="wprevpro_t_google_snippet_prodavailability" type="text" name="wprevpro_t_google_snippet_prodavailability" placeholder="" value="<?php if($google_misc_array['availability']!=''){echo stripslashes($google_misc_array['availability']);} ?>" style="width: 10em"></div>
										</div>
									</div>
									<div class="w3_wprs-col s4 googlesnippetsettingsdiv "><?php _e('SKU (optional):', 'wp-review-slider-pro'); ?></div>
									<div class="w3_wprs-col s8 googlesnippetsettingsdiv ">
										<input id="wprevpro_t_google_snippet_prodsku" type="text" name="wprevpro_t_google_snippet_prodsku" placeholder="" value="<?php if($google_misc_array['sku']!=''){echo $google_misc_array['sku'];} ?>" style="width: 10em">
									</div>
									<div class="w3_wprs-col s4 googlesnippetsettingsdiv "><?php _e('Global Identifier (optional):', 'wp-review-slider-pro'); ?></div>
									<div class="w3_wprs-col s8 googlesnippetsettingsdiv ">
										<div class="w3_wprs-col s12 googlesnippetsettingsdiv  ">
											<div class="w3_wprs-col  s4"><?php _e('Name (mpn, gtin8):', 'wp-review-slider-pro'); ?></div>
											<div class="w3_wprs-col  s8"><input id="wprevpro_t_google_snippet_prodginame" type="text" name="wprevpro_t_google_snippet_prodginame" placeholder="" value="<?php if($google_misc_array['giname']!=''){echo stripslashes($google_misc_array['giname']);} ?>" style="width: 10em"></div>
										</div>
										<div class="w3_wprs-col s12 googlesnippetsettingsdiv  ">
											<div class="w3_wprs-col  s4"><?php _e('Value:', 'wp-review-slider-pro'); ?></div>
											<div class="w3_wprs-col  s8"><input id="wprevpro_t_google_snippet_prodgival" type="text" name="wprevpro_t_google_snippet_prodgival" placeholder="" value="<?php if($google_misc_array['gival']!=''){echo stripslashes($google_misc_array['gival']);} ?>" style="width: 10em"></div>
										</div>
									</div>
								</div>
								<?php
								if(!isset($google_misc_array['tvr'])){
									$google_misc_array['tvr']='';
								}
								?>
								<div class="w3_wprs-col s4 googlesnippetsettingsdiv "><?php _e('Total Votes or Reviews:', 'wp-review-slider-pro'); ?></div>
								<div class="w3_wprs-col s8 googlesnippetsettingsdiv ">
									<select name="wprevpro_t_google_snippet_tvr" id="wprevpro_t_google_snippet_tvr" class="mt2">
										<option value="votes" <?php if($google_misc_array['tvr']=='votes' || $google_misc_array['tvr']==''){echo "selected";} ?>>Votes</option>
										<option value="reviews" <?php if($google_misc_array['tvr']=='reviews'){echo "selected";} ?>>Reviews</option>
									</select><br>
									<p class="description" id="" >
									<?php _e('Display total reviews as Votes or Reviews on Google search.', 'wp-review-slider-pro'); ?>
									</p>
								</div>
							</div>
						</div>
						<p class="description">
							<?php _e('When Google finds valid reviews or ratings markup, they may show a rich snippet in search results that includes stars and other summary info from the reviews. Once you turn this on and add your template to your site you can test it <a href="https://search.google.com/structured-data/testing-tool" target="_blank">here</a>', 'wp-review-slider-pro'); ?>.</p>
						</td>
			</tr>

			
			
			
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Overall Badge On-Click:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<select name="wprevpro_badge_misc_onclickaction" id="wprevpro_badge_misc_onclickaction">
						<option value="" <?php if($badge_misc_array['onclickaction']==''){echo "selected";} ?>><?php _e('Nothing', 'wp-review-slider-pro'); ?></option>
						<option value="url" <?php if($badge_misc_array['onclickaction']=='url'){echo "selected";} ?>><?php _e('Link to URL', 'wp-review-slider-pro'); ?></option>
						<option value="slideout" <?php if($badge_misc_array['onclickaction']=='slideout'){echo "selected";} ?>><?php _e('Review Slide-out Window', 'wp-review-slider-pro'); ?></option>
						<option value="popup" <?php if($badge_misc_array['onclickaction']=='popup'){echo "selected";} ?>><?php _e('Review Pop-up Window', 'wp-review-slider-pro'); ?></option>
					</select>
					<p class="description">
					<?php _e('Allows you to add an overall badge click action. It will only work when not clicking one of the other badge links set above. <br><b>Link to URL</b>: When someone clicks the Badge they will be directed here. <br><b>Review Slide-out Window</b>: A window will slide out from the side displaying a review template that you select.<br><b>Review Pop-up Window</b>: A window will pop-up displaying a review template that you select.', 'wp-review-slider-pro'); ?>
					</p>
				</td>
			</tr>
			<tr class="wprevpro_row linktourl" <?php if($badge_misc_array['onclickaction']!='url'){echo "style='display:none;'";} ?>>
				<th scope="row">
					<?php _e('Link to URL:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
				<input id="wprevpro_badge_misc_onclickurl" data-custom="custom" type="text" name="wprevpro_badge_misc_onclickurl" placeholder="" value="<?php echo $badge_misc_array['onclickurl']; ?>">
				<select name="wprevpro_badge_misc_onclickurl_target" id="wprevpro_badge_misc_onclickurl_target" style="margin-top: -3px;">
						<option value="new" <?php if($badge_misc_array['onclickurl_target']=='new' || $badge_misc_array['onclickurl_target']==''){echo "selected";} ?>><?php _e('Open in New Window', 'wp-review-slider-pro'); ?></option>
						<option value="same" <?php if($badge_misc_array['onclickurl_target']=='same'){echo "selected";} ?>><?php _e('Open in Same Window', 'wp-review-slider-pro'); ?></option>
					</select>
					<p class="description">
					<?php _e('Input the URL where people will be directed when they click the Float. The preview float will open in New Window regardless of this setting. Note: this will override the links in the badge or review slider.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>

			<tr class="wprevpro_row slidouttr" <?php if($badge_misc_array['onclickaction']=='url' || $badge_misc_array['onclickaction']==''){echo "style='display:none;'";} ?>>
				<th scope="row">
					<?php _e('Slide-out/Pop-up Review Template:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
				<?php
					//get list of badges and create select list
					$rtemplate_table_name = $wpdb->prefix . 'wpfb_post_templates';
					$reviewlist = $wpdb->get_results("SELECT * FROM $rtemplate_table_name");
					//print_r($reviewlist);
					if(count($reviewlist)>0){
						//check for currently selected
						$tempselected ='';
						$tempselected = $badge_misc_array['sliderevtemplate'];
				?>
					<select name="wprevpro_badge_misc_sliderevtemplate" id="wprevpro_badge_misc_sliderevtemplate">
					<option value=""><?php _e('Select One', 'wp-review-slider-pro'); ?></option>
				<?php
						foreach ( $reviewlist as $reviewtdetails ) 
						{
							$tempseltext ='';
							if($tempselected==$reviewtdetails->id){
								$tempseltext = 'selected';
							}
							echo '<option value="'.$reviewtdetails->id.'" '.$tempseltext.'>'.$reviewtdetails->title.' - '.$reviewtdetails->template_type.' ('.$reviewtdetails->display_num.' col, '.$reviewtdetails->display_num_rows.' row)</option>';
						}
				?>					
					</select>
				<?php
					} else {
						echo __('Please create a review template first by clicking the "Templates" tab above.', 'wp-review-slider-pro');
					}
				?>
				<img src="/wp-admin/images/loading.gif" class="loading-image2" style="display:none;">
					<p class="description">
					<?php _e('This is the review template that will be displayed in the Slide-out or Pop-up. Works best with non-slider review templates.', 'wp-review-slider-pro'); ?></p>
					<p class="description">
					<?php _e('<b>Caution:</b> Do not use a review template that is a slider and that is already being used on the page.', 'wp-review-slider-pro'); ?></p>
					<p class="description slideoutlocationsetting" <?php if($badge_misc_array['onclickaction']=='popup'){echo "style='display:none;'";} ?> >
					<?php _e('Notes: <br>- One column review templates work best for Right and Left Slides, while one row templates work best for top and bottom slides.<br>- If using a review template with arrows then make sure to increase the left and right padding to at least 35 below to see them.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
			
			
			<tr class="wprevpro_row slidouttr" <?php if($badge_misc_array['onclickaction']=='url' || $badge_misc_array['onclickaction']==''){echo "style='display:none;'";} ?>>
				<th scope="row">
					<?php _e('Slide-out/Pop-up Settings:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<div class="w3_wprs-row">
						  <div class="w3_wprs-col s12">
							<div class="w3_wprs-col s3">
								<div class="wprevpre_temp_label_row hideforpopup" <?php if($badge_misc_array['onclickaction']=='popup'){echo "style='display:none;'";} ?>>
								<?php _e('Slide-out Location:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row">
								<?php _e('Background Color:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row">
								<?php _e('Background Border:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row">
								<?php _e('Border Width:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row slwidthdiv hideforpopup" <?php if($badge_misc_array['slidelocation']=='top' ||$badge_misc_array['slidelocation']=='bottom'){echo "style='display:none;'";} ?> <?php if($badge_misc_array['onclickaction']=='popup'){echo "style='display:none;'";} ?>>
								<?php _e('Width:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row slheightdiv hideforpopup" <?php if($badge_misc_array['slidelocation']=='left' ||$badge_misc_array['slidelocation']=='right'){echo "style='display:none;'";} ?> <?php if($badge_misc_array['onclickaction']=='popup'){echo "style='display:none;'";} ?>>
								<?php _e('Height:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row hideforpopup" <?php if($badge_misc_array['onclickaction']=='popup'){echo "style='display:none;'";} ?>>
								<?php _e('Padding:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row">
								<?php _e(' ', 'wp-review-slider-pro'); ?>
								</div>
							</div>
							<div class="w3_wprs-col s9">
								<div class="wprevpre_temp_label_row hideforpopup" <?php if($badge_misc_array['onclickaction']=='popup'){echo "style='display:none;'";} ?>>
									<select class="updatesliderinput" name="wprevpro_badge_misc_slidelocation" id="wprevpro_badge_misc_slidelocation">
									  <option value="right" <?php if($badge_misc_array['slidelocation']=='right'){echo "selected";} ?>><?php _e('Right', 'wp-review-slider-pro'); ?></option>
									  <option value="left" <?php if($badge_misc_array['slidelocation']=='left'){echo "selected";} ?>><?php _e('Left', 'wp-review-slider-pro'); ?></option>
									  <option value="top" <?php if($badge_misc_array['slidelocation']=='top'){echo "selected";} ?>><?php _e('Top', 'wp-review-slider-pro'); ?></option>
									  <option value="bottom" <?php if($badge_misc_array['slidelocation']=='bottom'){echo "selected";} ?>><?php _e('Bottom', 'wp-review-slider-pro'); ?></option>
									</select>
								</div>
								<div class="wprevpre_temp_label_row wprevpre_slbgcolor1">
									<input type="text" data-alpha-enabled="true" value="<?php echo $badge_misc_array['slbgcolor1']; ?>" name="wprevpro_badge_misc_slbgcolor1" id="wprevpro_badge_misc_slbgcolor1" class="my-color-field updatesliderinput" />
								</div>
								<div class="wprevpre_temp_label_row wprevpre_slbgcolor1">
									<input type="text" data-alpha-enabled="true" value="<?php echo $badge_misc_array['slbordercolor1']; ?>" name="wprevpro_badge_misc_slbordercolor1" id="wprevpro_badge_misc_slbordercolor1" class="my-color-field updatesliderinput" />
								</div>
								<div class="wprevpre_temp_label_row slwidthdiv">
									<input type="number" data-alpha-enabled="true" min="0" value="<?php echo $badge_misc_array['slborderwidth']; ?>" name="wprevpro_badge_misc_slborderwidth" id="wprevpro_badge_misc_slborderwidth" class="updatesliderinput" style="width: 45px;" />
								</div>
								<div class="wprevpre_temp_label_row slwidthdiv hideforpopup" <?php if($badge_misc_array['slidelocation']=='top' ||$badge_misc_array['slidelocation']=='bottom'){echo "style='display:none;'";} ?> <?php if($badge_misc_array['onclickaction']=='popup'){echo "style='display:none;'";} ?>>
									<input type="number" data-alpha-enabled="true" value="<?php echo $badge_misc_array['slwidth']; ?>" name="wprevpro_badge_misc_slwidth" id="wprevpro_badge_misc_slwidth" class="marginpaddinginput updatesliderinput" />
								</div>
								<div class="wprevpre_temp_label_row slheightdiv hideforpopup" <?php if($badge_misc_array['slidelocation']=='left' ||$badge_misc_array['slidelocation']=='right'){echo "style='display:none;'";} ?> <?php if($badge_misc_array['onclickaction']=='popup'){echo "style='display:none;'";} ?>>
									<input type="number" data-alpha-enabled="true" value="<?php echo $badge_misc_array['slheight']; ?>" name="wprevpro_badge_misc_slheight" id="wprevpro_badge_misc_slheight" class="marginpaddinginput updatesliderinput" />
								</div>
								<div class="wprevpre_temp_label_row hideforpopup" <?php if($badge_misc_array['onclickaction']=='popup'){echo "style='display:none;'";} ?>>
									<div class="wprevpre_temp_label_row">
									<?php _e('Top', 'wp-review-slider-pro'); ?>:<input type="number" data-alpha-enabled="true" value="<?php echo $badge_misc_array['slpadding-top']; ?>" name="wprevpro_badge_misc_slpadding-top" id="wprevpro_badge_misc_slpadding-top" class="marginpaddinginput updatesliderinput" /> 
									<?php _e('Right', 'wp-review-slider-pro'); ?>:<input type="number" data-alpha-enabled="true" value="<?php echo $badge_misc_array['slpadding-right']; ?>" name="wprevpro_badge_misc_slpadding-right" id="wprevpro_badge_misc_slpadding-right" class="marginpaddinginput updatesliderinput" />
									</div>
									<div class="wprevpre_temp_label_row">
									<?php _e('Bottom', 'wp-review-slider-pro'); ?>:<input type="number" data-alpha-enabled="true" value="<?php echo $badge_misc_array['slpadding-bottom']; ?>" name="wprevpro_badge_misc_slpadding-bottom" id="wprevpro_badge_misc_slpadding-bottom" class="marginpaddinginput updatesliderinput" /> 
									<?php _e('Left', 'wp-review-slider-pro'); ?>:<input type="number" data-alpha-enabled="true" value="<?php echo $badge_misc_array['slpadding-left']; ?>" name="wprevpro_badge_misc_slpadding-left" id="wprevpro_badge_misc_slpadding-left" class="marginpaddinginput updatesliderinput" />
									</div>
								</div>
							</div>
						  </div>
						  
					</div>
				</td>
			</tr>
			<tr class="wprevpro_row slidouttr" <?php if($badge_misc_array['onclickaction']=='url' || $badge_misc_array['onclickaction']==''){echo "style='display:none;'";} ?>>
				<th scope="row">
					<?php _e('Slide-out/Pop-up Header HTML:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
				<?php
				//render wordpress editor
				$content = stripslashes($badge_misc_array['slideheader']);
				$custom_editor_id = "wprevpro_badge_misc_slideheader";
				$args = array(
						'media_buttons' => true, // This setting removes the media button.
						'textarea_name' => "wprevpro_badge_misc_slideheader", // Set custom name.
						'textarea_rows' => 4, //Determine the number of rows.
						'quicktags' => true, // Remove view as HTML button.
					);
				wp_editor( $content, $custom_editor_id, $args );
				?>
					
					<p class="description">
					<?php 
					_e('Displays above the review template in the slide-out or pop-up.', 'wp-review-slider-pro'); 
					?>
					</p>
					
				</td>
			</tr>
			<tr class="wprevpro_row slidouttr" <?php if($badge_misc_array['onclickaction']=='url' || $badge_misc_array['onclickaction']==''){echo "style='display:none;'";} ?>>
				<th scope="row">
					<?php _e('Slide-out/Pop-up Footer HTML:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
				<?php
				//render wordpress editor
				$content = stripslashes($badge_misc_array['slidefooter']);
				$custom_editor_id = "wprevpro_badge_misc_slidefooter";
				$args = array(
						'media_buttons' => true, // This setting removes the media button.
						'textarea_name' => "wprevpro_badge_misc_slidefooter", // Set custom name.
						'textarea_rows' => 4, //Determine the number of rows.
						'quicktags' => true, // Remove view as HTML button.
					);
				wp_editor( $content, $custom_editor_id, $args );
				?>
					<p class="description">
					<?php _e('Displays below the review template in the slide-out or pop-up.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
			
			

		</tbody>
	</table>
	<?php 
	//security nonce
	wp_nonce_field( 'wprevpro_save_template');
	?>
	<input type="hidden" name="edittid" id="edittid"  value="<?php echo $currentbadge->id; ?>">
	<input type="submit" name="wprevpro_submittemplatebtn" id="wprevpro_submittemplatebtn" class="button button-primary" value="<?php _e('Save Template', 'wp-review-slider-pro'); ?>">
	<a id="wprevpro_addnewtemplate_cancel" class="button button-secondary"><?php _e('Cancel', 'wp-review-slider-pro'); ?></a>
	</form>
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
	
	<div id="preview_badge_outer">
		<div class="wprevpro_slideout_container_style"></div>
		<div class="wprevpro_slideout_container" style="visibility:hidden;">
			<div class="wprevpro_slideout_container_header"></div>
			<div class="wprevpro_slideout_container_body"></div>
			<div class="wprevpro_slideout_container_footer"></div>
		</div>
		<div class="wprevpro_popup_container_style"></div>
		<div class="wprevmodal_modal wprevpro_popup_container" style="visibility:hidden;">
			<div class="wprevmodal_modal-content wprevpro_popup_container_inner ">
				<span class="wprevmodal_close">×</span>
				<div class="wprevpro_popup_container_header"></div>
				<div class="wprevpro_popup_container_body"></div>
				<div class="wprevpro_popup_container_footer"></div>
			</div>
		</div>
	</div>
	
</div>