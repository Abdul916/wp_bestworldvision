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
 
     // check user capabilities
    if (!current_user_can('manage_options') && $this->wprev_canuserseepage('float')==false) {
        return;
    }
	$dbmsg = "";
	$html="";
	$currentfloat= new stdClass();
	$currentfloat->id="";
	$currentfloat->title ="";
	$currentfloat->float_type ="badge";
	$currentfloat->content_id ="";
	$currentfloat->created_time_stamp ="";
	$currentfloat->float_misc ="";
	$currentfloat->float_css ="";
	$currentfloat->enabled='';
	
	//echo $this->_token;  wprevpro_t_read_more_text
	//if token = wp-review-slider-pro then using free version
	
	//db function variables
	global $wpdb;
	$table_name = $wpdb->prefix . 'wpfb_floats';
	
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
				$currentfloat = $wpdb->get_row( "SELECT * FROM ".$table_name." WHERE id = ".$tid );
			}
			//for copying
			if($_GET['taction'] == "copy" && $_GET['tid'] > 0){
				//security
				check_admin_referer( 'tcopy_');
				//get form array
				$currentfloat = $wpdb->get_row( "SELECT * FROM ".$table_name." WHERE id = ".$tid );
				//add new template
				$array = (array) $currentfloat;
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
		$float_type = sanitize_text_field($_POST['wprevpro_float_type']);

		if($float_type=="badge"){
			$content_id = sanitize_text_field($_POST['wprevpro_badge_id']);
		} else if($float_type=="reviews" || $float_type=="pop" ){
			$content_id = sanitize_text_field($_POST['wprevpro_review_t_id']);
		}
		
		//$float_css = sanitize_text_field($_POST['wprevpro_float_css']);
		$float_css = $_POST['wprevpro_float_css'];
				
		//template misc
		$templatemiscarray = array();
		$templatemiscarray['floatlocation']=sanitize_text_field($_POST['wprevpro_float_misc_floatlocation']);
		$templatemiscarray['bgcolor1']=sanitize_text_field($_POST['wprevpro_float_misc_bgcolor1']);
		$templatemiscarray['bordercolor1']=sanitize_text_field($_POST['wprevpro_float_misc_bordercolor1']);
		$templatemiscarray['width']=sanitize_text_field($_POST['wprevpro_float_misc_width']);
		$templatemiscarray['margin-top']=sanitize_text_field($_POST['wprevpro_float_misc_margin-top']);
		$templatemiscarray['margin-right']=sanitize_text_field($_POST['wprevpro_float_misc_margin-right']);
		$templatemiscarray['margin-bottom']=sanitize_text_field($_POST['wprevpro_float_misc_margin-bottom']);
		$templatemiscarray['margin-left']=sanitize_text_field($_POST['wprevpro_float_misc_margin-left']);
		$templatemiscarray['padding-top']=sanitize_text_field($_POST['wprevpro_float_misc_padding-top']);
		$templatemiscarray['padding-right']=sanitize_text_field($_POST['wprevpro_float_misc_padding-right']);
		$templatemiscarray['padding-bottom']=sanitize_text_field($_POST['wprevpro_float_misc_padding-bottom']);
		$templatemiscarray['padding-left']=sanitize_text_field($_POST['wprevpro_float_misc_padding-left']);
		$templatemiscarray['hideonmobile']=sanitize_text_field($_POST['wprevpro_float_misc_hideonmobile']);
		$templatemiscarray['firstvisit']=sanitize_text_field($_POST['wprevpro_float_misc_firstvisit']);
		$templatemiscarray['onclickaction']=sanitize_text_field($_POST['wprevpro_float_misc_onclickaction']);
		$templatemiscarray['onclickurl']=sanitize_text_field($_POST['wprevpro_float_misc_onclickurl']);
		$templatemiscarray['onclickurl_target']=sanitize_text_field($_POST['wprevpro_float_misc_onclickurl_target']);
		$templatemiscarray['sliderevtemplate']=sanitize_text_field($_POST['wprevpro_float_misc_sliderevtemplate']);
		
		$templatemiscarray['slidelocation']=sanitize_text_field($_POST['wprevpro_float_misc_slidelocation']);
		$templatemiscarray['slbgcolor1']=sanitize_text_field($_POST['wprevpro_float_misc_slbgcolor1']);
		$templatemiscarray['slbordercolor1']=sanitize_text_field($_POST['wprevpro_float_misc_slbordercolor1']);
		$templatemiscarray['slborderwidth']=sanitize_text_field($_POST['wprevpro_float_misc_slborderwidth']);
		
		$templatemiscarray['slwidth']=sanitize_text_field($_POST['wprevpro_float_misc_slwidth']);
		$templatemiscarray['slheight']=sanitize_text_field($_POST['wprevpro_float_misc_slheight']);
		$templatemiscarray['slpadding-top']=sanitize_text_field($_POST['wprevpro_float_misc_slpadding-top']);
		$templatemiscarray['slpadding-right']=sanitize_text_field($_POST['wprevpro_float_misc_slpadding-right']);
		$templatemiscarray['slpadding-bottom']=sanitize_text_field($_POST['wprevpro_float_misc_slpadding-bottom']);
		$templatemiscarray['slpadding-left']=sanitize_text_field($_POST['wprevpro_float_misc_slpadding-left']);
		//$templatemiscarray['slideheader']=sanitize_text_field($_POST['wprevpro_float_misc_slideheader']);
		//$templatemiscarray['slidefooter']=sanitize_text_field($_POST['wprevpro_float_misc_slidefooter']);
		$templatemiscarray['slideheader']=wp_kses_post(wpautop($_POST['wprevpro_float_misc_slideheader']));
		$templatemiscarray['slidefooter']=wp_kses_post(wpautop($_POST['wprevpro_float_misc_slidefooter']));

		//for post and page filters
		$templatemiscarray['postfilter']=sanitize_text_field($_POST['wprevpro_t_postfilter']);
		$templatemiscarray['pagefilter']=sanitize_text_field($_POST['wprevpro_t_pagefilter']);
		
		$templatemiscarray['postfilterlist']=sanitize_text_field($_POST['wprevpro_t_postfilterlist']);
		$templatemiscarray['pagefilterlist']=sanitize_text_field($_POST['wprevpro_t_pagefilterlist']);
		$templatemiscarray['catfilterlist']=sanitize_text_field($_POST['wprevpro_t_catfilterlist']);
		
		$templatemiscarray['animate_dir']=sanitize_text_field($_POST['wprevpro_float_misc_animate_dir']);
		if($templatemiscarray['animate_dir'] == "" && $float_type=='pop'){
			$templatemiscarray['animate_dir'] = 'fade';
		}
		$templatemiscarray['animate_delay']=sanitize_text_field($_POST['wprevpro_float_misc_animate_delay']);
		
		$templatemiscarray['autoclose']=sanitize_text_field($_POST['wprevpro_float_misc_autoclose']);
		$templatemiscarray['autoclose_delay']=sanitize_text_field($_POST['wprevpro_float_misc_autoclose_delay']);
		
		
		//tag as enabled or not, can still be added with shortcode if not enabled.
		$isenabled = 0;
		if($templatemiscarray['postfilter']!=''){
			if($templatemiscarray['postfilterlist']!=''){
				$isenabled = 1;
			}
			if($templatemiscarray['catfilterlist']!=''){
				$isenabled = 1;
			}
		}
		if($templatemiscarray['pagefilter']!=''){
			if($templatemiscarray['pagefilterlist']!=''){
				$isenabled = 1;
			}
			if($templatemiscarray['pagefilter']=='all'){
				$isenabled = 1;
			}
		}


		//convert to json, function in class-wp-review-slider-pro-admin-common.php
		require_once WPREV_PLUGIN_DIR . 'admin/class-wp-review-slider-pro-admin-common.php';
		$plugin_admin_common = new Common_Admin_Functions();
		
		$templatemiscarray['postfilterlist'] = $plugin_admin_common->wprev_commastrtojson($templatemiscarray['postfilterlist']);	
		$templatemiscarray['pagefilterlist'] = $plugin_admin_common->wprev_commastrtojson($templatemiscarray['pagefilterlist']);	
		$templatemiscarray['catfilterlist'] = $plugin_admin_common->wprev_commastrtojson($templatemiscarray['catfilterlist']);

		$templatemiscjson = json_encode($templatemiscarray);
		
		$timenow = time();
		
		//+++++++++need to sql escape using prepare+++++++++++++++++++
		//+++++++++++++++++++++++++++++++++++++++++++++++++++++
		//insert or update
			$data = array( 
				'title' => "$title",
				'float_type' => "$float_type",
				'content_id' => "$content_id",
				'created_time_stamp' => "$timenow",
				'float_css' => "$float_css", 
				'float_misc' => "$templatemiscjson",
				'enabled' => "$isenabled",
				);
				//print_r($data);
			$format = array( 
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
			if($updatetempquery>0){
				$dbmsg = '<div id="setting-error-wprevpro_message" class="updated settings-error notice is-dismissible"><p><strong>'.__('Float Updated!', 'wp-review-slider-pro').'</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">'.__('Dismiss this notice.', 'wp-review-slider-pro').'</span></button></div>';
			} else {
				$wpdb->show_errors();
				$wpdb->print_error();
				$dbmsg = '<div id="setting-error-wprevpro_message" class="updated settings-error notice is-dismissible"><p><strong>'.__('Error', 'wp-review-slider-pro').':</strong> '.__('Unable to update. Please contact support.', 'wp-review-slider-pro').'</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">'.__('Dismiss this notice.', 'wp-review-slider-pro').'</span></button></div>';
			}
			
		}
		
	}

	//Get list of all current forms--------------------------
	$currentforms = $wpdb->get_results("SELECT id, title, float_type, created_time_stamp FROM $table_name");
	//-------------------------------------------------------

	//check to see if reviews are in database
	//total number of rows
	$reviews_table_name = $wpdb->prefix . 'wpfb_reviews';
	$reviewtotalcount = $wpdb->get_var( 'SELECT COUNT(*) FROM '.$reviews_table_name );
	if($reviewtotalcount<1){
		$dbmsg = $dbmsg . '<div id="setting-error-wprevpro_message" class="updated settings-error notice is-dismissible">'.__('<p><strong>No reviews found. Please visit the Facebook, Yelp, Google, or TripAdvisor pages or manually add one on the <a href="?page=wp_pro-reviews">Review List</a> page. </strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>', 'wp-review-slider-pro').'</div>';
	}
	
	
?>

<div class="wrap wp_pro-settings" id="">
	<h1><img src="<?php echo plugin_dir_url( __FILE__ ) . 'logo.png'; ?>"></h1>
	
<?php 
include("tabmenu.php");

//query args for export and import
$url_tempdownload = admin_url( 'admin-post.php?action=print_floats.csv' );
if ( wrsp_fs()->can_use_premium_code() ) {
	
?>
<div class="wprevpro_margin10">
	<a id="wprevpro_helpicon_posts" class="wprevpro_btnicononly button dashicons-before dashicons-editor-help"></a>
	<a id="wprevpro_addnewtemplate" class="button dashicons-before dashicons-plus-alt"><?php _e('Add New Float Template', 'wp-review-slider-pro'); ?></a>
	<a href="<?php echo $url_tempdownload;?>" id="wprevpro_exporttemplates" class="button dashicons-before dashicons-download"><?php _e('Export Floats', 'wp-review-slider-pro'); ?></a>
	<a id="wprevpro_importtemplates" class="button dashicons-before dashicons-upload"><?php _e('Import Floats', 'wp-review-slider-pro'); ?></a>
</div>
<div class="wprevpro_margin10" id="importform" style='display:none;'>
	    <form  action="?page=wp_pro-floats" method="post" name="upload_excel" enctype="multipart/form-data">
		<p><b><?php _e('Use this form to import previously exported Floats.', 'wp-review-slider-pro'); ?></b></p>
			<input type="file" name="file" id="file">
			</br>
			<button type="submit" id="submit" name="Import" class="button-primary" data-loading-text="Loading..."><?php _e('Import', 'wp-review-slider-pro'); ?></button>
        </form>
</div>

<?php


} else {
	echo '<div class="wprevpro_margin10">'.__('Floats are a Premium feature. Please upgrade.', 'wp-review-slider-pro').'</div>';
}
//display message
echo $dbmsg;
		$html .= '
		<table class="wp-list-table widefat purplerowbackground striped posts">
			<thead>
				<tr>
					<th scope="col" width="30px" class="manage-column">'.__('ID', 'wp-review-slider-pro').'</th>
					<th scope="col" class="manage-column">'.__('Title', 'wp-review-slider-pro').'</th>
					<th scope="col" width="100px" class="manage-column">'.__('Type', 'wp-review-slider-pro').'</th>
					<th scope="col" width="170px" class="manage-column">'.__('Date Created', 'wp-review-slider-pro').'</th>
					<th scope="col" width="350px" class="manage-column">'.__('Action', 'wp-review-slider-pro').'</th>
				</tr>
				</thead>
			<tbody id="">';
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
		
		//html for is on button.
			
		$html .= '<tr id="'.$currentform->id.'">
				<th scope="col" class=" manage-column">'.$currentform->id.'</th>
				<th scope="col" class=" manage-column"><b>'.$currentform->title.'</b></th>
				<th scope="col" class=" manage-column"><b>'.$currentform->float_type.'</b></th>
				<th scope="col" class=" manage-column">'.date("F j, Y",$currentform->created_time_stamp) .'</th>
				<th scope="col" class="manage-column" templateid="'.$currentform->id.'" templatetype="'.$currentform->float_type.'"><a class="wprevpro_displayshortcode button button-primary dashicons-before dashicons-shortcode">'.__('Shortcode', 'wp-review-slider-pro').'</a> <a href="'.$url_tempeditbtn.'" class="button button-secondary dashicons-before dashicons-admin-generic">'.__('Edit', 'wp-review-slider-pro').'</a> <a href="'.$url_tempdelbtn.'" class="button button-secondary dashicons-before dashicons-trash">'.__('Delete', 'wp-review-slider-pro').'</a> <a href="'.$url_tempcopybtn.'" class="button button-secondary dashicons-before dashicons-admin-page">'.__('Copy', 'wp-fb-reviews').'</a></th>
			</tr>';
	}
	} else {
		$html .= '<tr><td colspan="5">'.__('You can create Sticky Floating badges or reviews that will display at the bottom (or top) of your pages!', 'wp-review-slider-pro').'</td></tr>';
	}
		$html .= '</tbody></table>';
		
			
 echo $html;	

//echo "<div></br></br>Coming Soon! Floating badges and reviews!</br></br></div>"; 
?>

<div class="wprevpro_margin10" id="wprevpro_new_template">
<form name="newtemplateform" id="newtemplateform" action="?page=wp_pro-float" method="post">
	<table class="wprevpro_margin10 form-table ">
		<tbody>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Float Title:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<input id="wprevpro_template_title" data-custom="custom" type="text" name="wprevpro_template_title" placeholder="" value="<?php echo $currentfloat->title; ?>" required>
					<p class="description">
					<?php _e('Enter a title or name for this float.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
			
				<?php
				//echo $currentfloat->float_misc;
				$float_misc_array = json_decode($currentfloat->float_misc, true);
				if(!is_array($float_misc_array)){
					$float_misc_array=array();
					$float_misc_array['floatlocation']="btmrt";
					$float_misc_array['bgcolor1']="";
					$float_misc_array['bordercolor1']="";
					$float_misc_array['margin-top']="";
					$float_misc_array['margin-right']="";
					$float_misc_array['margin-bottom']="";
					$float_misc_array['margin-left']="";
					$float_misc_array['width']="350";
					$float_misc_array['padding-top']="";
					$float_misc_array['padding-right']="";
					$float_misc_array['padding-bottom']="";
					$float_misc_array['padding-left']="";
					$float_misc_array['hideonmobile']="no";
					$float_misc_array['onclickaction']="no";
					$float_misc_array['onclickurl']="";
					$float_misc_array['sliderevtemplate']="";
					//slideout settings
					$float_misc_array['slidelocation']="btmrt";
					$float_misc_array['slbgcolor1']="";
					$float_misc_array['slbordercolor1']="";
					$float_misc_array['slwidth']="400";
					$float_misc_array['slheight']="400";
					$float_misc_array['slpadding-top']="10";
					$float_misc_array['slpadding-right']="10";
					$float_misc_array['slpadding-bottom']="10";
					$float_misc_array['slpadding-left']="10";
					$float_misc_array['slideheader']="";
					$float_misc_array['slidefooter']="";
					//for choosing pages and posts
					$float_misc_array['pagefilter']='';
					$float_misc_array['postfilter']='';
				}
				if(!isset($float_misc_array['pagefilterlist'])){
					$float_misc_array['pagefilterlist']='';
					$pagefilterliststr = "";
				} else {
					//convert to string from json, function in class-wp-review-slider-pro-admin.php
					$pagefilterliststr = $this->wprev_jsontocommastr($float_misc_array['pagefilterlist']);
				}
				if(!isset($float_misc_array['postfilterlist'])){
					$float_misc_array['postfilterlist']='';
					$postfilterliststr = "";
				} else {
					//convert to string from json, function in class-wp-review-slider-pro-admin.php
					$postfilterliststr = $this->wprev_jsontocommastr($float_misc_array['postfilterlist']);
				}
				if(!isset($float_misc_array['catfilterlist'])){
					$float_misc_array['catfilterlist']='';
					$catfilterliststr = "";
				} else {
					//convert to string from json, function in class-wp-review-slider-pro-admin.php
					$catfilterliststr = $this->wprev_jsontocommastr($float_misc_array['catfilterlist']);
				}
				if(!isset($float_misc_array['onclickurl_target'])){
					$float_misc_array['onclickurl_target']='';
				}
				if(!isset($float_misc_array['firstvisit'])){
					$float_misc_array['firstvisit']='';
				}
				if(!isset($float_misc_array['animate_dir'])){
					$float_misc_array['animate_dir']='';
					$float_misc_array['animate_delay']='0';
				}
				if(!isset($float_misc_array['autoclose'])){
					$float_misc_array['autoclose']='';
					$float_misc_array['autoclose_delay']='10';
				}
				if(!isset($float_misc_array['slborderwidth'])){
					$float_misc_array['slborderwidth']='1';
				}
				
				?>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Show on Pages:', 'wp-review-slider-pro'); ?>
				</th>
				<td><div class="">
					<label for="wprevpro_t_pagefilter"></label>
					<select name="wprevpro_t_pagefilter" id="wprevpro_t_pagefilter">
						<option value="" <?php if($float_misc_array['pagefilter']==""){echo "selected";} ?>><?php _e('Select One', 'wp-review-slider-pro'); ?></option>
						<option value="all" <?php if($float_misc_array['pagefilter']=="all"){echo "selected";} ?>><?php _e('All Pages', 'wp-review-slider-pro'); ?></option>
						<option value="allex" <?php if($float_misc_array['pagefilter']=="allex"){echo "selected";} ?>><?php _e('All Pages Except', 'wp-review-slider-pro'); ?></option>
						<option value="choose" <?php if($float_misc_array['pagefilter']=="choose"){echo "selected";} ?>><?php _e('Choose Pages', 'wp-review-slider-pro'); ?></option>
					</select>&nbsp;&nbsp;&nbsp;
					<span class='selectpagesspan' <?php if($float_misc_array['pagefilter']=="" || $float_misc_array['pagefilter']=="all"){echo "style='display:none;'";} ?>>
					<label for="wprevpro_t_pagefilterlist"><?php _e('Pages:', 'wp-review-slider-pro'); ?></label>
					<input class="wprevpro_nr_pageid" id="wprevpro_t_pagefilterlist" type="text" name="wprevpro_t_pagefilterlist" placeholder="" value="<?php echo $pagefilterliststr; ?>" style="width: 8em"><a id="wprevpro_btn_pickpageids" class="button dashicons-before dashicons-yes "><?php _e('Select', 'wp-review-slider-pro'); ?></a>
					</span>
					</div>
					<p class="description">
					<?php _e('Choose which pages to display this float on.', 'wp-review-slider-pro'); ?></p>
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
					<?php _e('Show on Posts:', 'wp-review-slider-pro'); ?>
				</th>
				<td><div class="">
					<label for="wprevpro_t_postfilter"></label>
					<select name="wprevpro_t_postfilter" id="wprevpro_t_postfilter">
						<option value="" <?php if($float_misc_array['postfilter']==""){echo "selected";} ?>><?php _e('Select One', 'wp-review-slider-pro'); ?></option>
						<option value="all" <?php if($float_misc_array['postfilter']=="all"){echo "selected";} ?>><?php _e('All Post Types', 'wp-review-slider-pro'); ?></option>
						<option value="choose" <?php if($float_misc_array['postfilter']=="choose"){echo "selected";} ?>><?php _e('Choose Posts', 'wp-review-slider-pro'); ?></option>
						<option value="cats" <?php if($float_misc_array['postfilter']=="cats"){echo "selected";} ?>><?php _e('Choose Categories', 'wp-review-slider-pro'); ?></option>
					</select>&nbsp;&nbsp;&nbsp;
					<span class='selectpostsspan' <?php if($float_misc_array['postfilter']!="choose"){echo "style='display:none;'";} ?>>
					<label for="wprevpro_t_postfilterlist"><?php _e('Show on these Posts:', 'wp-review-slider-pro'); ?></label>
					<input class="wprevpro_nr_postid" id="wprevpro_t_postfilterlist" type="text" name="wprevpro_t_postfilterlist" placeholder="" value="<?php echo $postfilterliststr; ?>" style="width: 8em"><a id="wprevpro_btn_pickpostids" class="button dashicons-before dashicons-yes "><?php _e('Select', 'wp-review-slider-pro'); ?></a>
					</span>
					<span class='selectpostsspancat' <?php if($float_misc_array['postfilter']!="cats"){echo "style='display:none;'";} ?>>
					<label for="wprevpro_t_catfilterlist"><?php _e('Show on these Categories:', 'wp-review-slider-pro'); ?></label>
					<input class="wprevpro_nr_categories" id="wprevpro_t_catfilterlist" type="text" name="wprevpro_t_catfilterlist" placeholder="" value="<?php echo $catfilterliststr; ?>" style="width: 8em"><a id="wprevpro_btn_pickcats" class="button dashicons-before dashicons-yes "><?php _e('Select', 'wp-review-slider-pro'); ?></a>
					</span>
					</div>
					<p class="description">
					<?php _e('Choose which posts to display this float on. Also includes other post types.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
			
			
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Choose Float Type:', 'wp-review-slider-pro'); ?>
				</th>
				<td><div id="divtemplatestyles">

					<input type="radio" name="wprevpro_float_type" id="wprevpro_float_type1-radio" value="badge" <?php if($currentfloat->float_type== "badge"){echo 'checked';}?> >
					<label for="wprevpro_badge_type1-radio"><?php _e('Badge', 'wp-review-slider-pro'); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

					<input type="radio" name="wprevpro_float_type" id="wprevpro_float_type2-radio" value="reviews" <?php if($currentfloat->float_type== "reviews"){echo 'checked';}?>>
					<label for="wprevpro_float_type2-radio"><?php _e('Review Slider', 'wp-review-slider-pro'); ?></label>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php

?>
					<input type="radio" name="wprevpro_float_type" id="wprevpro_float_type3-radio" value="pop" <?php if($currentfloat->float_type== "pop"){echo 'checked';}?>>
					<label for="wprevpro_float_type3-radio"><?php _e('Review Pop-in/Pop-out', 'wp-review-slider-pro'); ?></label>
<?php

?>					
					</div>
					<p class="description">
					<?php //_e('Do you want to create a floating badge or floating review slider?', 'wp-review-slider-pro'); ?></p>
					<?php _e('Do you want to create a floating badge, floating review slider or reviews that pop in and out?', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
			<tr class="wprevpro_row badgeselectrow" <?php if($currentfloat->float_type=='reviews' || $currentfloat->float_type=='pop'){echo "style='display:none;'";}?>>
				<th scope="row">
					<?php _e('Choose Badge:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
				<?php
					//get list of badges and create select list
					$badge_table_name = $wpdb->prefix . 'wpfb_badges';
					$badgeslist = $wpdb->get_results("SELECT * FROM $badge_table_name");
					//print_r($badgeslist);
					if(count($badgeslist)>0){
						//check for currently selected
						$tempselected ='';
						if($currentfloat->float_type=='badge'){
							$tempselected = $currentfloat->content_id;
						}
				?>
					<select name="wprevpro_badge_id" id="wprevpro_badge_id">
					<option value=""><?php _e('Select One', 'wp-review-slider-pro'); ?></option>
				<?php
						foreach ( $badgeslist as $badgedetails ) 
						{
							$tempseltext ='';
							if($tempselected==$badgedetails->id){
								$tempseltext = 'selected';
							}
							echo '<option value="'.$badgedetails->id.'" '.$tempseltext.'>'.$badgedetails->title.' - '.$badgedetails->badge_type.' - '.$badgedetails->badge_bname.'</option>';
						}
				?>					
					</select>
				<?php
					} else {
						echo __('Please create a badge first by clicking the "Badges" tab above.', 'wp-review-slider-pro');
					}
				?>
				<img src="/wp-admin/images/loading.gif" class="loading-image" style="display:none;">
					<p class="description">
					<?php _e('This is the badge that will be displayed.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
			<tr class="wprevpro_row reviewtemplateselectrow" <?php if($currentfloat->float_type=='badge'){echo "style='display:none;'";}?>>
				<th scope="row">
					<?php _e('Review Template:', 'wp-review-slider-pro'); ?>
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
						if($currentfloat->float_type=='reviews' || $currentfloat->float_type=='pop'){
							$tempselected = $currentfloat->content_id;
						}
				?>
					<select name="wprevpro_review_t_id" id="wprevpro_review_t_id">
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
				<img src="/wp-admin/images/loading.gif" class="loading-image" style="display:none;">
					<p class="description floatslider" <?php if($currentfloat->float_type=='pop'){echo "style='display:none;'";}?>>
					<?php _e('This is the review template that will be displayed. Works best with a template that displays one review at a time.', 'wp-review-slider-pro'); ?></p>
					<p class="description floatpop" <?php if($currentfloat->float_type=='reviews'){echo "style='display:none;'";}?>>
					<?php _e('This is the review template that will be displayed. Only one review will pop-in and out at a time. Works best with template style 10.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>

			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Float Style Settings:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<div class="w3_wprs-row">
						  <div class="w3_wprs-col s12">
							<div class="w3_wprs-col s3">
								<div class="wprevpre_temp_label_row">
								<?php _e('Float Location', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row">
								<?php _e('Background Color:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row">
								<?php _e('Background Border:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row">
								<?php _e('Float Width:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row">
								<?php _e('Float Margin:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row">
								<?php _e(' ', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row">
								<?php _e('Float Padding:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row">
								<?php _e(' ', 'wp-review-slider-pro'); ?>
								</div>
							</div>
							<div class="w3_wprs-col s9">

								<div class="wprevpre_temp_label_row">
									<select name="wprevpro_float_misc_floatlocation" id="wprevpro_float_misc_floatlocation">
									  <option value="btmrt" <?php if($float_misc_array['floatlocation']=='btmrt'){echo "selected";} ?>><?php _e('Bottom Right', 'wp-review-slider-pro'); ?></option>
									  <option value="btmmd" <?php if($float_misc_array['floatlocation']=='btmmd'){echo "selected";} ?>><?php _e('Bottom Middle', 'wp-review-slider-pro'); ?></option>
									  <option value="btmlft" <?php if($float_misc_array['floatlocation']=='btmlft'){echo "selected";} ?>><?php _e('Bottom Left', 'wp-review-slider-pro'); ?></option>
									  <option value="toplft" <?php if($float_misc_array['floatlocation']=='toplft'){echo "selected";} ?>><?php _e('Top Left', 'wp-review-slider-pro'); ?></option>
									  <option value="topmd" <?php if($float_misc_array['floatlocation']=='topmd'){echo "selected";} ?>><?php _e('Top Middle', 'wp-review-slider-pro'); ?></option>
									  <option value="toprt" <?php if($float_misc_array['floatlocation']=='toprt'){echo "selected";} ?>><?php _e('Top Right', 'wp-review-slider-pro'); ?></option>
									</select>
								</div>
								<div class="wprevpre_temp_label_row wprevpre_bgcolor1">
									<input type="text" data-alpha-enabled="true" value="<?php echo $float_misc_array['bgcolor1']; ?>" name="wprevpro_float_misc_bgcolor1" id="wprevpro_float_misc_bgcolor1" class="my-color-field" />
								</div>
								<div class="wprevpre_temp_label_row wprevpre_bgcolor1">
									<input type="text" data-alpha-enabled="true" value="<?php echo $float_misc_array['bordercolor1']; ?>" name="wprevpro_float_misc_bordercolor1" id="wprevpro_float_misc_bordercolor1" class="my-color-field" />
								</div>
								<div class="wprevpre_temp_label_row">
									<input type="number" value="<?php echo $float_misc_array['width']; ?>" name="wprevpro_float_misc_width" id="wprevpro_float_misc_width" class="marginpaddinginput" />
								</div>
								<div class="wprevpre_temp_label_row">
									<div class="wprevpre_temp_label_row">
									<?php _e('Top', 'wp-review-slider-pro'); ?>:<input type="number" value="<?php echo $float_misc_array['margin-top']; ?>" name="wprevpro_float_misc_margin-top" id="wprevpro_float_misc_margin-top" class="marginpaddinginput" /> 
									<?php _e('Right', 'wp-review-slider-pro'); ?>:<input type="number" value="<?php echo $float_misc_array['margin-right']; ?>" name="wprevpro_float_misc_margin-right" id="wprevpro_float_misc_margin-right" class="marginpaddinginput" /> 
									</div>
									<div class="wprevpre_temp_label_row">
									<?php _e('Bottom', 'wp-review-slider-pro'); ?>:<input type="number" value="<?php echo $float_misc_array['margin-bottom']; ?>" name="wprevpro_float_misc_margin-bottom" id="wprevpro_float_misc_margin-bottom" class="marginpaddinginput" /> 
									<?php _e('Left', 'wp-review-slider-pro'); ?>:<input type="number" value="<?php echo $float_misc_array['margin-left']; ?>" name="wprevpro_float_misc_margin-left" id="wprevpro_float_misc_margin-left" class="marginpaddinginput" />
									</div>
								</div>
								<div class="wprevpre_temp_label_row">
									<div class="wprevpre_temp_label_row">
									<?php _e('Top', 'wp-review-slider-pro'); ?>:<input type="number" value="<?php echo $float_misc_array['padding-top']; ?>" name="wprevpro_float_misc_padding-top" id="wprevpro_float_misc_padding-top" class="marginpaddinginput" /> 
									<?php _e('Right', 'wp-review-slider-pro'); ?>:<input type="number" value="<?php echo $float_misc_array['padding-right']; ?>" name="wprevpro_float_misc_padding-right" id="wprevpro_float_misc_padding-right" class="marginpaddinginput" />
									</div>
									<div class="wprevpre_temp_label_row">
									<?php _e('Bottom', 'wp-review-slider-pro'); ?>:<input type="number" value="<?php echo $float_misc_array['padding-bottom']; ?>" name="wprevpro_float_misc_padding-bottom" id="wprevpro_float_misc_padding-bottom" class="marginpaddinginput" /> 
									<?php _e('Left', 'wp-review-slider-pro'); ?>:<input type="number" value="<?php echo $float_misc_array['padding-left']; ?>" name="wprevpro_float_misc_padding-left" id="wprevpro_float_misc_padding-left" class="marginpaddinginput" />
									</div>
								</div>
							</div>
						  </div>
						  
					</div>
					<p class="description">
					<?php _e('The float preview is for visualization purposes only and should work correctly on the front end of your site. ', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Fly-in Animation:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<select name="wprevpro_float_misc_animate_dir" id="wprevpro_float_misc_animate_dir">
										<option value="" <?php if($float_misc_array['animate_dir']==''){echo "selected";} ?>><?php _e('None', 'wp-review-slider-pro'); ?></option>
									  <option value="right" <?php if($float_misc_array['animate_dir']=='right'){echo "selected";} ?>><?php _e('Slide In From Right', 'wp-review-slider-pro'); ?></option>
									  <option value="bottom" <?php if($float_misc_array['animate_dir']=='bottom'){echo "selected";} ?>><?php _e('Slide In From Bottom', 'wp-review-slider-pro'); ?></option>
									  <option value="left" <?php if($float_misc_array['animate_dir']=='left'){echo "selected";} ?>><?php _e('Slide In From Left', 'wp-review-slider-pro'); ?></option>
									  <option value="fade" <?php if($float_misc_array['animate_dir']=='fade'){echo "selected";} ?>><?php _e('Fade In', 'wp-review-slider-pro'); ?></option>
					</select>
					<span class="description">
					<?php _e('  Enter delay in seconds:', 'wp-review-slider-pro'); ?>
					</span>
					
					<input id="wprevpro_float_misc_animate_delay" data-custom="custom" class="marginpaddinginput" type="number" name="wprevpro_float_misc_animate_delay" placeholder="" value="<?php echo $float_misc_array['animate_delay']; ?>">
					<p class="description">
					<?php _e('This allows you to fly in or fade in the Float after a number of seconds. ', 'wp-review-slider-pro'); ?></p>

				</td>
			</tr>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Auto Close:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<select name="wprevpro_float_misc_autoclose" id="wprevpro_float_misc_autoclose">
						 <option value="" <?php if($float_misc_array['autoclose']==''){echo "selected";} ?>><?php _e('No', 'wp-review-slider-pro'); ?></option>
						 <option value="yes" <?php if($float_misc_array['autoclose']=='yes'){echo "selected";} ?>><?php _e('Yes', 'wp-review-slider-pro'); ?></option>
					</select>
					<span class="description">&nbsp;&nbsp;
					<?php _e('Wait this many seconds before closing:', 'wp-review-slider-pro'); ?>
					</span>
					
					<input id="wprevpro_float_misc_autoclose_delay" data-custom="custom" class="marginpaddinginput" type="number" name="wprevpro_float_misc_autoclose_delay" placeholder="" value="<?php echo $float_misc_array['autoclose_delay']; ?>">
					<p class="description">
					<?php _e('This allows you to automatically close the float after a number of seconds. ', 'wp-review-slider-pro'); ?></p>

				</td>
			</tr>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Hide On:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
				
					<input type="radio" name="wprevpro_float_misc_hideonmobile" id="wprevpro_float_misc_hideonmobile1-radio" value="yes" <?php if($float_misc_array['hideonmobile']=='yes'){echo 'checked';}?> >
					<label for="wprevpro_float_misc_hideonmobile1-radio"><?php _e('Mobile', 'wp-review-slider-pro'); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

					<input type="radio" name="wprevpro_float_misc_hideonmobile" id="wprevpro_float_misc_hideonmobile2-radio" value="desktop" <?php if($float_misc_array['hideonmobile']=='desktop'){echo 'checked';}?>>
					<label for="wprevpro_float_misc_hideonmobile2-radio"><?php _e('Desktop', 'wp-review-slider-pro'); ?></label>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					
					<input type="radio" name="wprevpro_float_misc_hideonmobile" id="wprevpro_float_misc_hideonmobile3-radio" value="no" <?php if($float_misc_array['hideonmobile']=='no' || $float_misc_array['hideonmobile']==''){echo 'checked';}?>>
					<label for="wprevpro_float_misc_hideonmobile2-radio"><?php _e('Neither', 'wp-review-slider-pro'); ?></label>

					<p class="description">&nbsp;&nbsp;
					<?php _e('Check Mobile to turn off Float when viewing on small screen, or check Desktop to hide it on large screens.', 'wp-review-slider-pro'); ?>
					</p>
				</td>
			</tr>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('First Page Only:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<select name="wprevpro_float_misc_firstvisit" id="wprevpro_float_misc_firstvisit">
						<option value="no" <?php if($float_misc_array['firstvisit']=='no' || $float_misc_array['firstvisit']==''){echo "selected";} ?>><?php _e('No', 'wp-review-slider-pro'); ?></option>
						<option value="yes" <?php if($float_misc_array['firstvisit']=='yes'){echo "selected";} ?>><?php _e('Yes', 'wp-review-slider-pro'); ?></option>
					</select>
					<span class="description">
					<?php _e('  Set to yes to hide the float on other pages if the visitor has already seen it.', 'wp-review-slider-pro'); ?>
					</span>
				</td>
			</tr>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Custom CSS:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<textarea name="wprevpro_float_css" id="wprevpro_float_css" cols="50" rows="4"><?php echo $currentfloat->float_css; ?></textarea>
					<p class="description">
					<?php _e('Enter custom CSS code to change the look of the template even more when being displayed.</br>Example Style 1:', 'wp-review-slider-pro'); ?> <b>.wprev_pro_float_outerdiv {width: 300px;}</b></p>
				</td>
			</tr>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Float On-Click Action:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<select name="wprevpro_float_misc_onclickaction" id="wprevpro_float_misc_onclickaction">
						<option value="no" <?php if($float_misc_array['onclickaction']=='no'){echo "selected";} ?>><?php _e('Nothing', 'wp-review-slider-pro'); ?></option>
						<option value="url" <?php if($float_misc_array['onclickaction']=='url'){echo "selected";} ?>><?php _e('Link to URL', 'wp-review-slider-pro'); ?></option>
						<option value="slideout" <?php if($float_misc_array['onclickaction']=='slideout'){echo "selected";} ?>><?php _e('Review Slide-out Window', 'wp-review-slider-pro'); ?></option>
						<option value="popup" <?php if($float_misc_array['onclickaction']=='popup'){echo "selected";} ?>><?php _e('Review Pop-up Window', 'wp-review-slider-pro'); ?></option>
					</select>
					<p class="description">
					<?php _e('<b>Link to URL</b>: When someone clicks the Float they will be directed here. <br><b>Review Slide-out Window</b>: A window will slide out from the side displaying a review template that you select.<br><b>Review Pop-up Window</b>: A window will pop-up displaying a review template that you select.', 'wp-review-slider-pro'); ?>
					</p>
				</td>
			</tr>
			<tr class="wprevpro_row linktourl" <?php if($float_misc_array['onclickaction']!='url'){echo "style='display:none;'";} ?>>
				<th scope="row">
					<?php _e('Link to URL:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
				<input id="wprevpro_float_misc_onclickurl" data-custom="custom" type="text" name="wprevpro_float_misc_onclickurl" placeholder="" value="<?php echo $float_misc_array['onclickurl']; ?>">
				<select name="wprevpro_float_misc_onclickurl_target" id="wprevpro_float_misc_onclickurl_target" style="margin-top: -3px;">
						<option value="new" <?php if($float_misc_array['onclickurl_target']=='new' || $float_misc_array['onclickurl_target']==''){echo "selected";} ?>><?php _e('Open in New Window', 'wp-review-slider-pro'); ?></option>
						<option value="same" <?php if($float_misc_array['onclickurl_target']=='same'){echo "selected";} ?>><?php _e('Open in Same Window', 'wp-review-slider-pro'); ?></option>
					</select>
					<p class="description">
					<?php _e('Input the URL where people will be directed when they click the Float. The preview float will open in New Window regardless of this setting. Note: this will override the links in the badge or review slider.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>

			<tr class="wprevpro_row slidouttr" <?php if($float_misc_array['onclickaction']=='no' || $float_misc_array['onclickaction']=='url'){echo "style='display:none;'";} ?>>
				<th scope="row">
					<?php _e('Review Template:', 'wp-review-slider-pro'); ?>
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
						$tempselected = $float_misc_array['sliderevtemplate'];
				?>
					<select name="wprevpro_float_misc_sliderevtemplate" id="wprevpro_float_misc_sliderevtemplate">
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
					<p class="description slideoutlocationsetting" <?php if($float_misc_array['onclickaction']=='popup'){echo "style='display:none;'";} ?> >
					<?php _e('Notes: <br>- One column review templates work best for Right and Left Slides, while one row templates work best for top and bottom slides.<br>- If using a review template with arrows then make sure to increase the left and right padding to at least 35 below to see them.', 'wp-review-slider-pro'); ?></p>
					
				</td>
			</tr>
			
			
			<tr class="wprevpro_row slidouttr" <?php if($float_misc_array['onclickaction']=='no' || $float_misc_array['onclickaction']=='url'){echo "style='display:none;'";} ?>>
				<th scope="row">
					<?php _e('Slide-out/Pop-up Settings:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<div class="w3_wprs-row">
						  <div class="w3_wprs-col s12">
							<div class="w3_wprs-col s3">
								<div <?php if($float_misc_array['onclickaction']=='popup'){echo "style='display:none;'";} ?> class="wprevpre_temp_label_row slideoutlocationsetting">
								<?php _e('Slide-out Location:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row">
								<?php _e('Background Color:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row">
								<?php _e('Background Border:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row slwidthdiv">
								<?php _e('Border Width:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row slwidthdiv" <?php if($float_misc_array['slidelocation']=='top' ||$float_misc_array['slidelocation']=='bottom'){echo "style='display:none;'";} ?> <?php if($float_misc_array['onclickaction']=='popup'){echo "style='display:none;'";} ?>>
								<?php _e('Width:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row slheightdiv" <?php if($float_misc_array['slidelocation']=='left' ||$float_misc_array['slidelocation']=='right'){echo "style='display:none;'";} ?> <?php if($float_misc_array['onclickaction']=='popup'){echo "style='display:none;'";} ?>>
								<?php _e('Height:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row paddingdiv" <?php if($float_misc_array['onclickaction']=='popup'){echo "style='display:none;'";} ?>>
								<?php _e('Padding:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row">
								<?php _e(' ', 'wp-review-slider-pro'); ?>
								</div>
							</div>
							<div class="w3_wprs-col s9">
								<div class="wprevpre_temp_label_row slideoutlocationsetting" <?php if($float_misc_array['onclickaction']=='popup'){echo "style='display:none;'";} ?> id=''>
									<select class="updatesliderinput" name="wprevpro_float_misc_slidelocation" id="wprevpro_float_misc_slidelocation">
									  <option value="right" <?php if($float_misc_array['slidelocation']=='right'){echo "selected";} ?>><?php _e('Right', 'wp-review-slider-pro'); ?></option>
									  <option value="left" <?php if($float_misc_array['slidelocation']=='left'){echo "selected";} ?>><?php _e('Left', 'wp-review-slider-pro'); ?></option>
									  <option value="top" <?php if($float_misc_array['slidelocation']=='top'){echo "selected";} ?>><?php _e('Top', 'wp-review-slider-pro'); ?></option>
									  <option value="bottom" <?php if($float_misc_array['slidelocation']=='bottom'){echo "selected";} ?>><?php _e('Bottom', 'wp-review-slider-pro'); ?></option>
									</select>
								</div>
								<div class="wprevpre_temp_label_row wprevpre_slbgcolor1">
									<input type="text" data-alpha="true" value="<?php echo $float_misc_array['slbgcolor1']; ?>" name="wprevpro_float_misc_slbgcolor1" id="wprevpro_float_misc_slbgcolor1" class="my-color-field updatesliderinput" />
								</div>
								<div class="wprevpre_temp_label_row wprevpre_slbgcolor1">
									<input type="text" data-alpha="true" value="<?php echo $float_misc_array['slbordercolor1']; ?>" name="wprevpro_float_misc_slbordercolor1" id="wprevpro_float_misc_slbordercolor1" class="my-color-field updatesliderinput" />
								</div>
								<div class="wprevpre_temp_label_row slwidthdiv">
									<input type="number" min="0" data-alpha="true" value="<?php echo $float_misc_array['slborderwidth']; ?>" name="wprevpro_float_misc_slborderwidth" id="wprevpro_float_misc_slborderwidth" class="updatesliderinput" style="width: 45px;" />
								</div>
								<div class="wprevpre_temp_label_row slwidthdiv" <?php if($float_misc_array['slidelocation']=='top' ||$float_misc_array['slidelocation']=='bottom'){echo "style='display:none;'";} ?> <?php if($float_misc_array['onclickaction']=='popup'){echo "style='display:none;'";} ?>>
									<input type="number" data-alpha="true" value="<?php echo $float_misc_array['slwidth']; ?>" name="wprevpro_float_misc_slwidth" id="wprevpro_float_misc_slwidth" class="marginpaddinginput updatesliderinput" />
								</div>
								<div class="wprevpre_temp_label_row slheightdiv" <?php if($float_misc_array['slidelocation']=='left' ||$float_misc_array['slidelocation']=='right'){echo "style='display:none;'";} ?> <?php if($float_misc_array['onclickaction']=='popup'){echo "style='display:none;'";} ?>>
									<input type="number" data-alpha="true" value="<?php echo $float_misc_array['slheight']; ?>" name="wprevpro_float_misc_slheight" id="wprevpro_float_misc_slheight" class="marginpaddinginput updatesliderinput" />
								</div>
								<div class="wprevpre_temp_label_row paddingdiv" <?php if($float_misc_array['onclickaction']=='popup'){echo "style='display:none;'";} ?>>
									<div class="wprevpre_temp_label_row">
									<?php _e('Top', 'wp-review-slider-pro'); ?>:<input type="number" data-alpha="true" value="<?php echo $float_misc_array['slpadding-top']; ?>" name="wprevpro_float_misc_slpadding-top" id="wprevpro_float_misc_slpadding-top" class="marginpaddinginput updatesliderinput" /> 
									<?php _e('Right', 'wp-review-slider-pro'); ?>:<input type="number" data-alpha="true" value="<?php echo $float_misc_array['slpadding-right']; ?>" name="wprevpro_float_misc_slpadding-right" id="wprevpro_float_misc_slpadding-right" class="marginpaddinginput updatesliderinput" />
									</div>
									<div class="wprevpre_temp_label_row">
									<?php _e('Bottom', 'wp-review-slider-pro'); ?>:<input type="number" data-alpha="true" value="<?php echo $float_misc_array['slpadding-bottom']; ?>" name="wprevpro_float_misc_slpadding-bottom" id="wprevpro_float_misc_slpadding-bottom" class="marginpaddinginput updatesliderinput" /> 
									<?php _e('Left', 'wp-review-slider-pro'); ?>:<input type="number" data-alpha="true" value="<?php echo $float_misc_array['slpadding-left']; ?>" name="wprevpro_float_misc_slpadding-left" id="wprevpro_float_misc_slpadding-left" class="marginpaddinginput updatesliderinput" />
									</div>
								</div>
							</div>
						  </div>
						  
					</div>
				</td>
			</tr>
			<tr class="wprevpro_row slidouttr" <?php if($float_misc_array['onclickaction']=='no' || $float_misc_array['onclickaction']=='url'){echo "style='display:none;'";} ?>>
				<th scope="row">
					<?php _e('Slide-out/Pop-up Header HTML:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
				<?php
				//render wordpress editor
				$content = stripslashes($float_misc_array['slideheader']);
				$custom_editor_id = "wprevpro_float_misc_slideheader";
				$args = array(
						'media_buttons' => true, // This setting removes the media button.
						'textarea_name' => "wprevpro_float_misc_slideheader", // Set custom name.
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
			<tr class="wprevpro_row slidouttr" <?php if($float_misc_array['onclickaction']=='no' || $float_misc_array['onclickaction']=='url'){echo "style='display:none;'";} ?>>
				<th scope="row">
					<?php _e('Slide-out/Pop-up Footer HTML:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
				<?php
				//render wordpress editor
				$content = stripslashes($float_misc_array['slidefooter']);
				$custom_editor_id = "wprevpro_float_misc_slidefooter";
				$args = array(
						'media_buttons' => true, // This setting removes the media button.
						'textarea_name' => "wprevpro_float_misc_slidefooter", // Set custom name.
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
	<input type="hidden" name="edittid" id="edittid"  value="<?php echo $currentfloat->id; ?>">
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
		<div class="wprevpro_badge_container_style"></div>
		<div class="wprevpro_badge_container" style="z-index: 10;position: fixed;"></div>
		<div class="wprevpro_slideout_container_style"></div>
		<div class="wprevpro_slideout_container" id="theslideoutcontainer" style="display:none;">
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
</br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br>