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
    if (!current_user_can('edit_pages') && $this->wprev_canuserseepage('reviews')==false) {
        return;
    }

//db function variables
global $wpdb;
$table_name = $wpdb->prefix . 'wpfb_reviews';


	//template importing from CSV file--------------------
	$importmes="";
	 if(isset($_POST["Import"])){
		 //security
		$nonce = $_REQUEST['_wpnonce'];
		if ( ! wp_verify_nonce( $nonce, 'my-nonce' ) ) {
			// This nonce is not valid.
			die( __( 'Failed security check.', 'wp-review-slider-pro' ) ); 
		}
		 
		//print_r($_FILES);
		$filename=$_FILES["file"]["tmp_name"];		
		 if($_FILES["file"]["size"] > 0)
		 {
		  	$file = fopen($filename, "r");
			$c = 0; //use line one for column names
			$skippedlines = 0;
			$reviewsimported = 0;
	        while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
	         {
				$c++;
				if($c == 1){
				//print_r($getData);
					$colarray = $getData;
				} else {
					$insertdata = array_combine($colarray, $getData);
					
					//addslashes to '
					//$insertdata['review_text'] = addslashes($insertdata['review_text']);
					
					//fix created_time 
					if(isset($insertdata['created_time_stamp']) && $insertdata['created_time_stamp']!=''){
				
						$timestamp = $insertdata['created_time_stamp'];
					} else {
						$timestamp = strtotime($insertdata['created_time']);
					}
					$timestampday = date("Y-m-d H:i:s", $timestamp);
					$insertdata['created_time']=$timestampday;
					$insertdata['created_time_stamp']=$timestamp;
					
					//fix pageid and reviewerid, maybe with a warning message
					if($insertdata['pageid']==''){
						$insertdata['pageid']='manually_added';
					}
					if($insertdata['pagename']==''){
						$insertdata['pagename']='Manually Added';
					}
					if($insertdata['type']==''){
						$insertdata['type']='Manual';
					}
					//fix review length if not set
					if($insertdata['review_length']==''){
						$review_text = $insertdata['review_text'];
						$review_length = str_word_count($review_text);
						if (extension_loaded('mbstring')) {
							$review_length_char = mb_strlen($review_text);
						} else {
							$review_length_char = strlen($review_text);
						}
						if($review_length_char>0 && $review_length<1){
							$review_length = 1;
						}
						$insertdata['review_length']=$review_length;
						$insertdata['review_length_char']=$review_length_char;
					}
					
					
					//remove id so it will assign another on insert
					unset($insertdata['id']);
					
					//print_r($insertdata);
					//die();
					
					//check for duplicate data here and insert if not in db
					$unixtimestamp = $insertdata['created_time_stamp'];
					$user_name = $insertdata['reviewer_name'];
					if($user_name!=""){
					$checkrow = $wpdb->get_var( 'SELECT id FROM '.$table_name.' WHERE created_time_stamp = "'.$unixtimestamp.'" AND reviewer_name = "'.trim($user_name).'" ' );
								if( empty( $checkrow ) ){
										$reviewindb = 'no';
										//insert to db here
										$numinsert = $wpdb->insert( $table_name, $insertdata );
										$reviewsimported=$reviewsimported+$numinsert;
										//$wpdb->show_errors();
										//$wpdb->print_error();
								} else {
									$skippedlines++;
								}
					}			
				}
	         }
	         fclose($file);	
			 $importmes = "<div>".$reviewsimported." ".__('reviews imported', 'wp-review-slider-pro').", ".$skippedlines." ".__('reviews skipped because of duplicates found.', 'wp-review-slider-pro')."</div>";
			 
			 //update totals and averages.
			 require_once WPREV_PLUGIN_DIR . 'admin/class-wp-review-slider-pro-admin_hooks.php';
			$plugin_admin_hooks = new WP_Review_Pro_Admin_Hooks( $this->get_token(), $this->get_version() );
			$plugin_admin_hooks->updateallavgtotalstable();
			 
		 }
		 
	}
	//---------------------------------------------------------------
	
	
	$dbmsg = "";
	$html="";
	$currentreview= new stdClass();
	$currentreview->id="";
	$currentreview->rating="";
	$currentreview->review_title="";
	$currentreview->review_text="";
	$currentreview->reviewer_name="";
	$currentreview->reviewer_email="";
	$currentreview->reviewer_id="";
	$currentreview->company_name="";
	$currentreview->created_time="";
	$currentreview->created_time_stamp="";
	$currentreview->userpic="";
	$currentreview->review_length="";
	$currentreview->review_length_char="";
	$currentreview->type="";
	$currentreview->from_name="";
	$currentreview->from_url="";
	$currentreview->from_logo="";
	$currentreview->consent="";
	$currentreview->hidestars="";
	$currentreview->language_code="";
	$currentreview->tags="";
	


$rowsperpage = 20;

	//form updating here---------------------------
	if(isset($_GET['taction'])){
		$rid = htmlentities($_GET['rid']);
		$rid = intval($rid);
		//for updating
		if($_GET['taction'] == "edit" && $_GET['rid'] > 0){
			//security
			check_admin_referer( 'tedit_');
			//get form array
			$currentreview = $wpdb->get_row( "SELECT * FROM ".$table_name." WHERE id = ".$rid );
		}
		
	}
	//------------------------------------------
	

?>
<div class="wrap wp_pro-settings" id="">
	<h1><img src="<?php echo plugin_dir_url( __FILE__ ) . 'logo.png'; ?>"></h1>
<?php 
include("tabmenu.php");
$nonce = wp_create_nonce( 'my-nonce' );		
//query args for export and import
$url_tempdownbtn = admin_url( 'admin-post.php?action=printreviews.csv' );

?>
<div class="wprevpro_margin10">
	<a id="wprevpro_helpicon" class="wprevpro_btnicononly button dashicons-before dashicons-editor-help"></a>
	<a id="wprevpro_removeallbtn"  data-sec="<?php echo esc_attr( $nonce ); ?>" class="button dashicons-before dashicons-no"><?php _e('Remove Reviews', 'wp-review-slider-pro'); ?></a>
	<a id="wprevpro_addnewreviewbtn" class="button dashicons-before dashicons-plus-alt"><?php _e('Manually Add Review', 'wp-review-slider-pro'); ?></a>
	<a href="<?php echo $url_tempdownbtn;?>" class="button dashicons-before dashicons-download"><?php _e('Download CSV File of Reviews', 'wp-review-slider-pro'); ?></a>
	<a id="wprevpro_importtemplates" class="button dashicons-before dashicons-upload"><?php _e('Import Reviews', 'wp-review-slider-pro'); ?></a>
	<span id='rl_totalrevcount'></span>
	<?php echo $importmes; ?>
</div>

<div class="wprevpro_margin10" id="importform" style='display:none;'>
	    <form  action="?page=wp_pro-reviews&_wpnonce=<?php echo $nonce; ?>" method="post" name="upload_excel" enctype="multipart/form-data">
		<p><b>Use this form to import previously exported Reviews. Please make sure that your spreadsheet program has not shortened numbers by adding a E+. In Excel you need to change the format to Number with no decimal places.</b></p>
		<p><b>Follow these steps if importing from a different plugin/app. These steps are not necessary if the reviews are from this plugin:</b><br>1) Use the "Manually 
		Add Review" to add one of the reviews.<br>2) Now use the "Download CSV File" button to save it to your computer.<br>3) Use that CSV file as a template to paste your reviews in to. <br>4) Now you can upload the CSV.</p>
			<input type="file" name="file" id="file">
			</br>
			<button type="submit" id="submit" name="Import" class="button-primary" data-loading-text="Loading...">Import</button>
        </form>
</div>
<div class="wpfb_review_list_pagination_bar"></div>
<div id="new_review_overlay" style="display:none;"></div>
<div class="wprevpro_margin10" id="wprevpro_new_review">
<form name="newreviewform" id="newreviewform" action="?page=wp_pro-reviews" method="post" onsubmit="return validateForm()">
<a id="wprevpro_btn_copyprevrev" class="button"><?php _e('copy last entered', 'wp-review-slider-pro'); ?></a>
	<table class="form-table ">
		<tbody>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Review Rating (1 - 5):', 'wp-review-slider-pro'); ?>
				</th>
				<td><div id="divtemplatestyles">
				<?php 
				//if this is a not a manual review or new one then disable this
				if($currentreview->type=='Manual' || $currentreview->type==''){
					$tempdisable = '';
				} else {
					$tempdisable = 'disabled';
				}
				?>

					<input type="radio" name="wprevpro_nr_rating" id="wprevpro_nr_rating0-radio" value="" <?php if($currentreview->rating==""){echo "checked";} else {echo $tempdisable;}?> >
					<label for="wprevpro_template_type2-radio"><?php _e('blank', 'wp-review-slider-pro'); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="radio" name="wprevpro_nr_rating" id="wprevpro_nr_rating1-radio" value="1" <?php if($currentreview->rating=="1"){echo "checked";} else {echo $tempdisable;}?> <?php echo $tempdisable; ?>>
					<label for="wprevpro_template_type1-radio"><?php _e('1', 'wp-review-slider-pro'); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="radio" name="wprevpro_nr_rating" id="wprevpro_nr_rating2-radio" value="2" <?php if($currentreview->rating=="2"){echo "checked";} else {echo $tempdisable;}?> <?php echo $tempdisable; ?>>
					<label for="wprevpro_template_type2-radio"><?php _e('2', 'wp-review-slider-pro'); ?></label>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="radio" name="wprevpro_nr_rating" id="wprevpro_nr_rating3-radio" value="3" <?php if($currentreview->rating=="3"){echo "checked";} else {echo $tempdisable;}?> <?php echo $tempdisable; ?>>
					<label for="wprevpro_template_type2-radio"><?php _e('3', 'wp-review-slider-pro'); ?></label>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="radio" name="wprevpro_nr_rating" id="wprevpro_nr_rating4-radio" value="4" <?php if($currentreview->rating=="4"){echo "checked";} else {echo $tempdisable;}?> <?php echo $tempdisable; ?>>
					<label for="wprevpro_template_type2-radio"><?php _e('4', 'wp-review-slider-pro'); ?></label>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="radio" name="wprevpro_nr_rating" id="wprevpro_nr_rating5-radio" value="5" <?php if($currentreview->rating=="5" || $currentreview->rating==""){echo "checked";} else {echo $tempdisable;}?> >
					<label for="wprevpro_template_type2-radio"><?php _e('5', 'wp-review-slider-pro'); ?></label>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					
					</div>

				</td>
			</tr>
			
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Star Display:', 'wp-review-slider-pro'); ?>
				</th>
				<td><div>
					<input type="radio" name="wprevpro_nr_hidestars" id="wprevpro_nr_hidestars0-radio" value=""  <?php if($currentreview->hidestars==""){echo "checked";} ?>>
					<label for="wprevpro_template_type2-radio"><?php _e('Show Stars', 'wp-review-slider-pro'); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="radio" name="wprevpro_nr_hidestars" id="wprevpro_nr_hidestars1-radio" value="yes">
					<label for="wprevpro_template_type1-radio"><?php _e('Hide Stars', 'wp-review-slider-pro'); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</div>
				</td>
			</tr>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Review Title:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<input id="wprevpro_nr_title" data-custom="custom" type="text" name="wprevpro_nr_title" placeholder="" value="<?php echo $currentreview->review_title; ?>" >
				</td>
			</tr>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Review Text:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
				<?php 
				//if this is a not a manual review or new one then disable this
				if($currentreview->type=='Manual' || $currentreview->type=='Submitted' || $currentreview->type==''){
					$tempdisable = '';
				} else {
					$tempdisable = 'readonly';
				}
				?>
					<textarea name="wprevpro_nr_text" id="wprevpro_nr_text" cols="60" rows="5" <?php echo $tempdisable; ?>><?php echo $currentreview->review_text; ?></textarea></br>
					<span class="description"><a id="wprevpro_btn_ownerresponse"><?php _e('owner response', 'wp-review-slider-pro'); ?></a></span>
					<div id="tb_content_ownerresponse" style="display:none;">
					
					<div><?php _e('Response Owner Name:', 'wp-review-slider-pro'); ?></div>
						<div><input id="wprevpro_owner_name" data-custom="custom" type="text" name="wprevpro_owner_name" placeholder="" value=""><input id="wprevpro_owner_id" data-custom="custom" type="hidden" name="wprevpro_owner_id" placeholder="" value=""></div>

						<div><?php _e('Response Text:', 'wp-review-slider-pro'); ?></div>
						<div><textarea name="wprevpro_owner_text" id="wprevpro_owner_text" cols="60" rows="5"></textarea></div>

						<div><?php _e('Response Date:', 'wp-review-slider-pro'); ?></div>
						<div><input id="wprevpro_owner_date" data-custom="custom" type="date" name="wprevpro_owner_date" placeholder="" value=""></div>
						<span class="description">
					<?php _e('This does not change the owner response on the original review source site. You can display the owner response in a review template if you use a child theme.', 'wp-review-slider-pro'); ?> <a href="https://wpreviewslider.userecho.com/knowledge-bases/2/articles/553-use-child-theme-to-customize-the-review-template-more" target="_blank"><?php _e('more info', 'wp-review-slider-pro'); ?></a>		</span>

					</div>
				</td>
			</tr>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Reviewer Info:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<?php _e('Name:', 'wp-review-slider-pro'); ?> <input id="wprevpro_nr_name" data-custom="custom" type="text" name="wprevpro_nr_name" placeholder="" value="<?php echo $currentreview->reviewer_name; ?>" required <?php echo $tempdisable; ?>>
					 <?php _e('Email:', 'wp-review-slider-pro'); ?> <input id="wprevpro_nr_email" data-custom="custom" type="text" name="wprevpro_nr_email" placeholder="" value="<?php echo $currentreview->reviewer_email; ?>">
					 <?php _e('Location:', 'wp-review-slider-pro'); ?> <input id="wprevpro_nr_location" data-custom="custom" type="text" name="wprevpro_nr_location" placeholder="" value="<?php echo $currentreview->reviewer_email; ?>">
					<br><span class="description">
					<?php _e('Enter the name of the person who wrote this review. Email & Location are optional.', 'wp-review-slider-pro'); ?>		</span>
				</td>
			</tr>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Company Info:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					Company Name: <input id="wprevpro_nr_company_name" data-custom="custom" type="text" name="wprevpro_nr_company_name" placeholder="" value="<?php echo $currentreview->company_name; ?>" >
					Title: <input id="wprevpro_nr_company_title" data-custom="custom" type="text" name="wprevpro_nr_company_title" placeholder="" value="" >
					URL: <input id="wprevpro_nr_company_url" data-custom="custom" type="url" name="wprevpro_nr_company_url" placeholder="" value="" >
					<br><span class="description">
					<?php _e('Optional: Enter the reviewer\'s location or company details. Will show up under their name in the review.', 'wp-review-slider-pro'); ?>		</span>
				</td>
			</tr>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Reviewer Pic URL:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
				<?php
				if($currentreview->type=="Facebook" && $currentreview->userpic==""){
					$currentreview->userpic = "https://graph.facebook.com/".$currentreview->reviewer_id."/picture?type=square";
				}
				?>
					<input id="wprevpro_nr_avatar_url" data-custom="custom" type="text" name="wprevpro_nr_avatar_url" placeholder="" value="<?php if($currentreview->userpic!=""){echo $currentreview->userpic; } else {echo plugin_dir_url( __FILE__ ) . 'fb_profile.jpg';} ?>"> <a id="upload_avatar_button" class="button"><?php _e('Upload', 'wp-review-slider-pro'); ?></a>
					<br><span class="description">
					<?php _e('Avatar for the person who wrote the review. Click one of the following images to insert generic avatar URL.', 'wp-review-slider-pro'); ?>
					</span>
					<div class="avatar_images_list">
					<img src="<?php echo plugin_dir_url( __FILE__ ); ?>tripadvisor_mystery_man.png" alt="thumb" class="rlimg default_avatar_img">&nbsp;&nbsp;&nbsp;
					<img src="<?php echo plugin_dir_url( __FILE__ ); ?>google_mystery_man.png" alt="thumb" class="rlimg default_avatar_img">&nbsp;&nbsp;&nbsp;
					<img src="<?php echo plugin_dir_url( __FILE__ ); ?>facebook_mystery_man.png" alt="thumb" class="rlimg default_avatar_img">&nbsp;&nbsp;&nbsp;
					<img src="<?php echo plugin_dir_url( __FILE__ ); ?>yelp_mystery_man.png" alt="thumb" class="rlimg default_avatar_img">
					&nbsp;&nbsp;&nbsp;
					<img src="<?php echo plugin_dir_url( __FILE__ ); ?>airbnb_mystery_man.png" alt="thumb" class="rlimg default_avatar_img">
					&nbsp;&nbsp;&nbsp;
					<img src="<?php echo plugin_dir_url( __FILE__ ); ?>gravatar_mystery_man.png" alt="thumb" class="rlimg default_avatar_img">
					&nbsp;&nbsp;&nbsp;
					<img src="<?php echo 'https://avatar.oxro.io/avatar.svg?name='.str_replace(' ', '+', $currentreview->reviewer_name) ?>" alt="thumb" class="rlimg default_avatar_img avatarini">
					</div>
					</br>
					<img class="" height="100px" id="avatar_preview" src="<?php if($currentreview->userpic!=""){echo $currentreview->userpic; } else {echo plugin_dir_url( __FILE__ ) . 'fb_profile.jpg';} ?>">
				</td>
			</tr>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Review Date:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<input id="wprevpro_nr_date" data-custom="custom" type="text" name="wprevpro_nr_date" placeholder="" value="<?php if($currentreview->created_time!=""){echo $currentreview->created_time; } else {echo date("Y-m-d H:i:s",current_time( 'timestamp' ));} ?>" required>
				</td>
			</tr>
			<tr class="wprevpro_row socialreviewfield">
				<th scope="row">
					<?php _e('Review Icon:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<select name="wprevpro_nr_from" id="wprevpro_nr_from">
					  <option value="" <?php if($currentreview->from_name == ""){echo "selected";} ?>></option>
					  <option value="custom" <?php if($currentreview->from_name == "custom"){echo "selected";} ?>>Custom</option>
<?php
				$typearray = unserialize(WPREV_TYPE_ARRAY);
				$typearrayrf = unserialize(WPREV_TYPE_ARRAY_RF);
				$typearray = array_merge($typearray,$typearrayrf);
				for($x=0;$x<count($typearray);$x++)
				{
					if($typearray[$x]!="Manual" || $typearray[$x]!="Submitted"){
					$typelowercase = strtolower($typearray[$x]);
?>
					<option value="<?php echo $typelowercase;?>" <?php if($currentreview->from_name == $typelowercase){echo "selected";} ?>><?php echo $typearray[$x]; ?></option>
<?php
					}
				}
?>

					</select>
					<span class="description">
					<?php _e('Optional: If you would like to display the logo of the site where the review came from. Not normally used for submitted reviews.', 'wp-review-slider-pro'); ?></span>
					<div id='div_customlogoupload' <?php if($currentreview->from_name!="custom"){echo "style='display:none;'";} ?>>
						<input id="wprevpro_nr_logo_url" data-custom="custom" type="text" name="wprevpro_nr_logo_url" placeholder="" value="<?php if($currentreview->from_logo!=""){echo $currentreview->from_logo; } ?>"> <a id="upload_logo_button" class="button"><?php _e('Upload', 'wp-review-slider-pro'); ?></a> <a id="btn_copy_last_urls" class="button"><?php _e('Copy Last Used URLs', 'wp-review-slider-pro'); ?></a>
						<div class="description"><span class="description">
						<?php _e('This will appear in the bottom right of the review. Height of 32px works best.', 'wp-review-slider-pro'); ?>:
						</span></div></br>
						<img class="wprevpro_margin10" height="32px" id="from_logo_preview" src="">
					
					</div>
				</td>
			</tr>
			<tr class="wprevpro_row socialreviewfield" >
				<th scope="row">
					<?php _e('Review Link URL:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<input id="wprevpro_nr_from_url" data-custom="custom" type="text" name="wprevpro_nr_from_url" placeholder="" value="<?php if($currentreview->from_url!=""){echo $currentreview->from_url; } ?>">
					<span class="description">
					<?php _e('Optional: Input the URL location of the original review. Not normally used for submitted reviews.', 'wp-review-slider-pro'); ?></span>
				</td>
			</tr>
			<tr class="wprevpro_row submittedreviewfield">
				<th scope="row">
					<?php _e('Reviewer Consent:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<input id="wprevpro_nr_consent" data-custom="custom" type="text" name="wprevpro_nr_consent" placeholder="" value="<?php echo $currentreview->consent; ?>" readonly>
					<span class="description">
					<?php _e('Did the person check the consent checkbox when submitting the review. Not used for downloaded reviews.', 'wp-review-slider-pro'); ?>		</span>
				</td>
			</tr>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Review Categories:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<input class="wprevpro_nr_categories" id="wprevpro_nr_categories" data-custom="custom" type="text" name="wprevpro_nr_categories" placeholder="" value="">
					<span class="description"><a id="wprevpro_btn_pickcats" class="button dashicons-before dashicons-yes ">Select Categories</a>
					<?php _e('Optional: Single or comma separated list of post category IDs. Allows you to associate the review with post categories. Submitted reviews are automatically associated with the categories of the post that the form is located on. ex: 1,3,5', 'wp-review-slider-pro'); ?>		</span>
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
					<?php _e('Review Post IDs:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<input class="wprevpro_nr_postid" id="wprevpro_nr_postid" data-custom="custom" type="text" name="wprevpro_nr_postid" placeholder="" value="" >
					<span class="description"><a id="wprevpro_btn_pickpostids" class="button dashicons-before dashicons-yes ">Select Post IDs</a>
					<?php _e('Optional: Single or comma separated list of post IDs. Allows you to associate the review with multiple posts or page IDs. Submitted reviews are automatically associated with the post ID that the form is located on. ex: 11', 'wp-review-slider-pro'); ?>		</span>
				</td>
			</tr>
			
			<?php
			//pull distinct page names and page ids from reviews table
			if(!isset($rpagejsondecode)){
				$rpagejsondecode=[''];
			}
			$reviews_table_name = $wpdb->prefix . 'wpfb_reviews';
			//$tempquery = 	"SELECT DISTINCT pageid,pagename,type,from_url FROM ".$reviews_table_name." WHERE pageid IS NOT NULL";
			$tempquery = 	"select * from ".$reviews_table_name." group by pageid ORDER BY pagename ASC";
			
			//$tempquery = "SELECT * FROM (SELECT * FROM ".$reviews_table_name." ORDER BY pagename ASC) AS sub GROUP BY pageid";
			
			$fbpagesrows = $wpdb->get_results($tempquery);

			if(count($fbpagesrows)>0){
			?>
			<tr class="wprevpro_row fbhide revselectedhide">
				<th scope="row">
					<?php _e('Source Location:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
				<input class="" id="wprevpro_nr_pagename" data-custom="custom" type="text" name="wprevpro_nr_pagename" placeholder="" value="" >
				<input class="" id="wprevpro_nr_pageid" data-custom="custom" type="hidden" name="wprevpro_nr_pageid" placeholder="" value="" >
				<a id="wprevpro_btn_pickpages" class="button dashicons-before dashicons-yes">Select Location</a>
					<div id="tb_content_page_select" style="display:none;">
					<table class="selectrevstable wp-list-table widefat striped posts">
						<tbody id="">
					<?php
					foreach ( $fbpagesrows as $fbpage ) 
					{
					if($fbpage->pageid!=""){
						$temppagelink='';
						if($fbpage->type=='Facebook'){
							$temppagelink="https://www.facebook.com/".$fbpage->pageid."/";
						} else {
							//if($fbpage->from_url_review!=''){
							//		$temppagelink=$fbpage->from_url_review;
							//	} else {
									$temppagelink=$fbpage->from_url;
							//	}
						}
					?>
							<tr>
							<td>
							<input type="radio" pagename="<?php echo $fbpage->pagename;?>" class="pageselectclass" name="wprevpro_t_rpage" id="page_<?php echo $fbpage->pageid; ?>" value="<?php echo $fbpage->pageid; ?>"<?php if(in_array($fbpage->pageid, $rpagejsondecode)){echo 'checked="checked"';}?>><label for="page_<?php echo $fbpage->pageid; ?>"> <?php echo $fbpage->pagename.' ('.$fbpage->type.')'; ?></label>
							</td>
							</tr>
					<?php
					}
					}
					$numselpages = '';;
					if(count(array_filter($rpagejsondecode))>0){
						if(count(array_filter($rpagejsondecode))==1){
							$numselpages = "(".count(array_filter($rpagejsondecode))." Page Selected)";
						} else {
							$numselpages = "(".count(array_filter($rpagejsondecode))." Pages Selected)";
						}
					}
					?>
						</tbody>
					</table>
				</div><span id="wprevpro_selectedpagesspan"> <?php echo $numselpages; ?></span>
					<span class="description">
					<?php _e('Optional: This lets you tag this review to a certain Source Page location. Then it can be displayed by location when creating a template.', 'wp-review-slider-pro'); ?></span>
				</td>
			</tr>
			
			<?php
			}
			?>
			
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Language Code:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<input id="wprevpro_nr_lang" data-custom="custom" type="text" name="wprevpro_nr_lang" maxlength="2" placeholder="" value="<?php if($currentreview->language_code!=""){echo $currentreview->language_code; } ?>" style="width: 35px;">
					<span class="description">
					<?php _e('Optional: Two character language code of this reviews. Allows you to filter by language on the Template page. ex: en', 'wp-review-slider-pro'); ?>		</span>
				</td>
			</tr>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Tags:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<input id="wprevpro_nr_tags" data-custom="custom" type="text" name="wprevpro_nr_tags" placeholder="" value="<?php if($currentreview->tags!=""){echo $currentreview->tags; } ?>" >
					<span class="description">
					<?php _e('Optional: Comma seperate list of tags. Allows you to filter by tag in the review template settings.', 'wp-review-slider-pro'); ?>		</span>
				</td>
			</tr>
			
			<tr class="wprevpro_row">
						<th scope="row">
							<?php _e('Review Media:', 'wp-review-slider-pro'); ?>				</th>
						<td>
							<a id="wprevpro_addmedia" class="button"><?php _e('Enter Image or Video URLs', 'wp-review-slider-pro'); ?>	</a>
							<span class="description">
							<?php _e('Optional: Add images or YouTube videos under the review text.', 'wp-review-slider-pro'); ?>	 </span>
						</td>
					</tr>
					<tr class="wprevpro_row" id="mediainputs" style="display:none;">
						<th scope="row"></th>
						<td>
							<table>
								<tr>
									<td>
									<div class='wprev_media_input'><?php _e('Media URLs', 'wp-review-slider-pro'); ?></div><br>
							<input class="wprev_input_val" id="wprevpro_media0" data-custom="custom" type="url" name="wprevpro_media[]" placeholder="" value=""><br>
							<input class="wprev_input_val" id="wprevpro_media1" data-custom="custom" type="url" name="wprevpro_media[]" placeholder="" value=""><br>
							<input class="wprev_input_val" id="wprevpro_media2" data-custom="custom" type="url" name="wprevpro_media[]" placeholder="" value=""><br>
							<input class="wprev_input_val" id="wprevpro_media3" data-custom="custom" type="url" name="wprevpro_media[]" placeholder="" value=""><br>
							<input class="wprev_input_val" id="wprevpro_media4" data-custom="custom" type="url" name="wprevpro_media[]" placeholder="" value=""><br>
							<input class="wprev_input_val" id="wprevpro_media5" data-custom="custom" type="url" name="wprevpro_media[]" placeholder="" value=""><br>
							<input class="wprev_input_val" id="wprevpro_media6" data-custom="custom" type="url" name="wprevpro_media[]" placeholder="" value=""><br>
							<input class="wprev_input_val" id="wprevpro_media7" data-custom="custom" type="url" name="wprevpro_media[]" placeholder="" value="">
									</td>
									<td>
									<div class='wprev_media_input'><?php _e('Optional Thumbnail URLs', 'wp-review-slider-pro'); ?></div>
									<br>
							<input class="wprev_input_val" id="wprevpro_mediathumb0" data-custom="custom" type="url" name="wprevpro_mediathumb[]" placeholder="" value=""><br>
							<input class="wprev_input_val" id="wprevpro_mediathumb1" data-custom="custom" type="url" name="wprevpro_mediathumb[]" placeholder="" value=""><br>
							<input class="wprev_input_val" id="wprevpro_mediathumb2" data-custom="custom" type="url" name="wprevpro_mediathumb[]" placeholder="" value=""><br>
							<input class="wprev_input_val" id="wprevpro_mediathumb3" data-custom="custom" type="url" name="wprevpro_mediathumb[]" placeholder="" value=""><br>
							<input class="wprev_input_val" id="wprevpro_mediathumb4" data-custom="custom" type="url" name="wprevpro_mediathumb[]" placeholder="" value=""><br>
							<input class="wprev_input_val" id="wprevpro_mediathumb5" data-custom="custom" type="url" name="wprevpro_mediathumb[]" placeholder="" value=""><br>
							<input class="wprev_input_val" id="wprevpro_mediathumb6" data-custom="custom" type="url" name="wprevpro_mediathumb[]" placeholder="" value=""><br>
							<input class="wprev_input_val" id="wprevpro_mediathumb7" data-custom="custom" type="url" name="wprevpro_mediathumb[]" placeholder="" value="">
									</td>
								</tr>
							</table>
						<span class="description">
							<?php _e('Add the image or YouTube URL in the left input, then add the Thumbnail for the image or video on the right.', 'wp-review-slider-pro'); ?>	 </span>
		
						</td>
					</tr>
					
					
			
			<tr id="showmorefields" class="wprevpro_row" style="display:none;">
				<th scope="row">
				</th>
				<td>
					show all fields
				</td>
			</tr>
		
		</tbody>
	</table>
	<?php 
	//security nonce
	wp_nonce_field( 'wprevpro_save_review');
	$customlastsaveoption = get_option('wprevpro_customlastsave');
	?>
	<input type="hidden" name="from_logo_last" id="from_logo_last"  value="<?php echo $customlastsaveoption[0]; ?>">
	<input type="hidden" name="from_url_last" id="from_url_last"  value="<?php echo $customlastsaveoption[1]; ?>">
	<input type="hidden" name="editrid" id="editrid"  value="<?php echo $currentreview->id; ?>">
	<input type="hidden" name="editrtype" id="editrtype"  value="<?php echo $currentreview->type; ?>">
	<?php
	/*
	?>
	<input type="submit" name="wprevpro_submitreviewbtn" id="wprevpro_submitreviewbtn" class="button button-primary" value="<?php _e('Save Review', 'wp-review-slider-pro'); ?>">
	<?php
	*/
	?>
	<a id="wprevpro_savereviewbtn_ajax" class="button button-primary"><?php _e('Save & Close', 'wp-review-slider-pro'); ?></a>
	<a id="wprevpro_updatereviewbtn_ajax" class="button button-primary"><?php _e('Update', 'wp-review-slider-pro'); ?></a>
	<a id="wprevpro_addnewreview_cancel" class="button button-secondary"><?php _e('Close', 'wp-review-slider-pro'); ?></a>
	<div id="update_form_msg_div"><img src="<?php echo WPREV_PLUGIN_URL; ?>/public/partials/imgs/loading_ripple.gif" id="savingformimg" class="wprptemplate_update_loading_image" style="display:none;"><span id="update_form_msg"></span></div>
</form>
</div>

<?php 

	//for removing reviews in bulk all, first make sure they want to remove
	if(isset($_GET['opt'])){
		//security
		$nonce = $_REQUEST['_wpnonce'];
		if ( ! wp_verify_nonce( $nonce, 'my-nonce' ) ) {
			// This nonce is not valid.
			die( __( 'Failed security check.', 'wp-review-slider-pro' ) ); 
		}
		if($_GET['opt']=="delall"){
			$delete = $wpdb->query("TRUNCATE TABLE `".$table_name."`");
			//remove total and averages
			update_option('wppro_total_avg_reviews','' );
			$table_name_avgdata = $wpdb->prefix . 'wpfb_total_averages';
			$delete = $wpdb->query("TRUNCATE TABLE `".$table_name_avgdata."`");
			
		} else if($_GET['opt_type']=="type") {
			$typearray = unserialize(WPREV_TYPE_ARRAY);
			$typearray2 = unserialize(WPREV_TYPE_ARRAY_RF);
			$typearray = array_merge($typearray,$typearray2);
			//print_r($typearray);
			for($x=0;$x<count($typearray);$x++)
				{
					$typelowercase = strtolower($typearray[$x]);
					if($_GET['opt']=="del_".$typelowercase){
						$deltype=$typearray[$x];
						//echo $deltype;
						$delete = $wpdb->query("DELETE FROM `".$table_name."` WHERE `type` = '".$deltype."'");
					}
				}
		} else if($_GET['opt_type']=="page") {
			$delpagename = $_GET['opt'];
			//replace & or %26 with &amp;
			$delpagename2 = str_replace("&", "&amp;", $delpagename);
			
			//make sure this is in the db for security
			$reviews_table_name = $wpdb->prefix . 'wpfb_reviews';
			$tempquery = "select pagename from ".$reviews_table_name." group by pagename";
			$pagenamearray = $wpdb->get_col($tempquery);

			if (in_array($delpagename2, $pagenamearray)){
				$delete = $wpdb->query("DELETE FROM `".$table_name."` WHERE `pagename` = '".$delpagename2."'");
			}
			if (in_array($delpagename, $pagenamearray)){
				$delete = $wpdb->query("DELETE FROM `".$table_name."` WHERE `pagename` = '".$delpagename."'");
			}
		}
		
		//delete all cached avatars, avatars are recreated on public side also
		$img_locations_option = json_decode(get_option( 'wprev_img_locations' ),true);
		
		if($_GET['opt']=="delall"){
			
			//delete all local avatars, used for FB images
			$avatar_dir = $img_locations_option['upload_dir_wprev_avatars'];
			$localfiles = glob($avatar_dir.'*');
			foreach($localfiles as $file){ // iterate files
			  if(is_file($file))
				unlink($file); // delete file
			}
			$cachedir = $img_locations_option['upload_dir_wprev_cache'];
			$files = glob($cachedir.'*');
			foreach($files as $file){ // iterate files
			  if(is_file($file))
				unlink($file); // delete file
			}
		}
	}
	
	//pagenumber
	if(isset($_GET['pnum'])){
	$temppagenum = $_GET['pnum'];
	} else {
	$temppagenum ="";
	}
	if ( $temppagenum=="") {
		$pagenum = 1;
	} else if(is_numeric($temppagenum)){
		$pagenum = intval($temppagenum);
	}
	
	if(!isset($_GET['sortdir'])){
		$_GET['sortdir'] = "";
	}
	if ( $_GET['sortdir']=="" || $_GET['sortdir']=="DESC") {
		$sortdirection = "&sortdir=ASC";
	} else {
		$sortdirection = "&sortdir=DESC";
	}
	$currenturl = remove_query_arg( 'sortdir' );
	
	//make sure sortby is valid
	if(!isset($_GET['sortby'])){
		$_GET['sortby'] = "";
	}
	$allowed_keys = ['created_time_stamp', 'reviewer_name', 'rating', 'review_length', 'pagename', 'type' , 'hide', 'sort_weight'];
	$checkorderby = sanitize_key($_GET['sortby']);
	
		if(in_array($checkorderby, $allowed_keys, true) && $_GET['sortby']!=""){
			$sorttable = $_GET['sortby']. " ";
		} else {
			$sorttable = "created_time_stamp ";
		}
		if($_GET['sortdir']=="ASC" || $_GET['sortdir']=="DESC"){
			$sortdir = $_GET['sortdir'];
		} else {
			$sortdir = "DESC";
		}
		unset($sorticoncolor);
		for ($x = 0; $x <= 10; $x++) {
			$sorticoncolor[$x]="";
		} 
		if($sorttable=="hide "){
			$sorticoncolor[0]="text_green";
		} else if($sorttable=="reviewer_name "){
			$sorticoncolor[1]="text_green";
		} else if($sorttable=="rating "){
			$sorticoncolor[2]="text_green";
		} else if($sorttable=="created_time_stamp "){
			$sorticoncolor[3]="text_green";
		} else if($sorttable=="review_length "){
			$sorticoncolor[4]="text_green";
		} else if($sorttable=="pagename "){
			$sorticoncolor[5]="text_green";
		} else if($sorttable=="type "){
			$sorticoncolor[6]="text_green";	
		} else if($sorttable=="rating_type "){
			$sorticoncolor[7]="text_green";	
		} else if($sorttable=="sort_weight "){
			$sorticoncolor[8]="text_green";	
		}
	
		$html .= '
		<table class="wp-list-table widefat striped posts">
			<thead>
				<tr>
					<th scope="col" width="20px" sortdir="DESC" sorttype="hide" class="wprevpro_tablesort manage-column">'.__('Hide', 'wp-review-slider-pro').'<br><i class="dashicons dashicons-sort '.$sorticoncolor[0].'" aria-hidden="true"></i></th>
					<th scope="col" width="70px" sortdir="DESC" sorttype="name" class="wprevpro_tablesort manage-column">'.__('Name', 'wp-review-slider-pro').'<br><i class="dashicons dashicons-sort '.$sorticoncolor[1].'" aria-hidden="true"></i></th>
					<th scope="col" width="40px" sortdir="DESC" sorttype="rating" class="wprevpro_tablesort manage-column">'.__('Rating', 'wp-review-slider-pro').'<br><i class="dashicons dashicons-sort '.$sorticoncolor[2].'" aria-hidden="true"></i></th>
					<th scope="col" class="manage-column">'.__('Review Title/Text', 'wp-review-slider-pro').'</th>
					<th scope="col" width="75px" sortdir="DESC" sorttype="stime" class="wprevpro_tablesort manage-column">'.__('Date', 'wp-review-slider-pro').'<br><i class="dashicons dashicons-sort '.$sorticoncolor[3].'" aria-hidden="true"></i></th>
					<th scope="col" width="80px" sortdir="DESC" sorttype="stext" class="wprevpro_tablesort manage-column" >'.__('Words/Char', 'wp-review-slider-pro').'<br><i class="dashicons dashicons-sort '.$sorticoncolor[4].'" aria-hidden="true"></i></th>
					<th scope="col" width="100px" sortdir="DESC" sorttype="pagename" class="wprevpro_tablesort manage-column">'.__('Social Page', 'wp-review-slider-pro').'<br><i class="dashicons dashicons-sort '.$sorticoncolor[5].'" aria-hidden="true"></i></th>
					<th scope="col" width="80px" sortdir="DESC" sorttype="type" class="wprevpro_tablesort manage-column">'.__('Type', 'wp-review-slider-pro').'<br><i class="dashicons dashicons-sort '.$sorticoncolor[6].'" aria-hidden="true"></i></th>
					<th scope="col" width="50px" sortdir="DESC" sorttype="sort_weight" class="wprevpro_tablesort manage-column">'.__('Weight', 'wp-review-slider-pro').'<br><i class="dashicons dashicons-sort '.$sorticoncolor[8].'" aria-hidden="true"></i></th>
				</tr>
				</thead>
				<thead>
				<tr id="wprevpro_searchbar">
					<th scope="col" class="manage-column" colspan="10"><span class="dashicons dashicons-search" style="font-size: 30px;"></span>
					<input id="wprevpro_filter_table_name" type="text" name="wprevpro_filter_table_name" placeholder="Search..." >
					<select name="wprevpro_filter_table_min_rating" id="wprevpro_filter_table_min_rating">
					<option value="0" >'.__('All Stars', 'wp-review-slider-pro').'</option>
					  <option value="1" >'.__('1 Star', 'wp-review-slider-pro').'</option>
					  <option value="2" >'.__('2 Star', 'wp-review-slider-pro').'</option>
					  <option value="3" >'.__('3 Star', 'wp-review-slider-pro').'</option>
					  <option value="4" >'.__('4 Star', 'wp-review-slider-pro').'</option>
					  <option value="5" >'.__('5 Star', 'wp-review-slider-pro').'</option>
					</select>
					<select name="wprevpro_filter_table_type" id="wprevpro_filter_table_type">
					<option value="all" >'.__('All Types', 'wp-review-slider-pro').'</option>';
					
				$reviews_table_name = $wpdb->prefix . 'wpfb_reviews';
				$tempquery = "select type from ".$reviews_table_name." group by type";
				$typearray = $wpdb->get_col($tempquery);
					//$typearray = unserialize(WPREV_TYPE_ARRAY);
					for($x=0;$x<count($typearray);$x++)
					{
						$typelowercase = strtolower($typearray[$x]);
						$html .= '<option value="'.$typelowercase.'" >'.__($typearray[$x], 'wp-review-slider-pro').'</option>';
					}

$html .='			</select>
					<select name="wprevpro_filter_table_tag" id="wprevpro_filter_table_tag">
					<option value="all" >'.__('All Tags', 'wp-review-slider-pro').'</option>';
				$tempquery2 = "select tags from ".$reviews_table_name." group by tags";
				$tagarray = $wpdb->get_col($tempquery2);
				//print_r($tagarray);
				$finaltagarray = Array();
					for($x=0;$x<count($tagarray);$x++)
					{
						$temptags = "";
						if($tagarray[$x]!=''){
							$temptagsarray = json_decode($tagarray[$x],true);
							//print_r($temptagsarray);
							if(is_array($temptagsarray)){
							foreach ( $temptagsarray as $temptag ){
								$finaltagarray[]= $temptag;
							}
							
							
								
							//$temptags = implode(",", $temptagsarray);
							}
						}
						
						//if($temptags!=''){
						//$html .= '<option value="'.$temptags.'" >'.__($temptags, 'wp-review-slider-pro').'</option>';
						//}
					}
					$finaltagarray = array_unique($finaltagarray);
					$finaltagarray = array_filter($finaltagarray);
					
					foreach ( $finaltagarray as $finaltag ){
						$html .= '<option value="'.$finaltag.'" >'.__($finaltag, 'wp-review-slider-pro').'</option>';
					}
					
					
$html .='			</select>
					<select name="wprevpro_filter_table_lang" id="wprevpro_filter_table_lang">
					<option value="all" >'.__('All Languages', 'wp-review-slider-pro').'</option>';
				$tempquery2 = "select language_code from ".$reviews_table_name." group by language_code";
				$langarray = $wpdb->get_col($tempquery2);
					//$typearray = unserialize(WPREV_TYPE_ARRAY);
					for($x=0;$x<count($langarray);$x++)
					{
						if($langarray[$x]!=''){
						$html .= '<option value="'.$langarray[$x].'" >'.__($langarray[$x], 'wp-review-slider-pro').'</option>';
						}
					}
					
$html .='			<option value="unset" >'.__('unset', 'wp-review-slider-pro').'</option></select>
				<select name="wprevpro_filter_table_pageid" id="wprevpro_filter_table_pageid">
					<option value="all" >'.__('All Source Pages', 'wp-review-slider-pro').'</option>';

					foreach ( $fbpagesrows as $fbpage ) 
					{
						if($fbpage->pageid!=''){
						$html .= '<option value="'.$fbpage->pageid.'" >'.__(stripslashes($fbpage->pagename), 'wp-review-slider-pro').'</option>';
						}
					}
$html .='			</select><a id="wprevpro_bulkedit" class="button dashicons-before dashicons-edit">Bulk Edit</a></th>
				</tr>
			</thead>';
$html .= '<tbody id="review_list">';		
			
		//get reviews from db
		$lowlimit = ($pagenum - 1) * $rowsperpage;
		$tablelimit = $lowlimit.",".$rowsperpage;
		$reviewsrows = $wpdb->get_results(
			$wpdb->prepare("SELECT * FROM ".$table_name."
			WHERE id>%d
			ORDER BY ".$sorttable." ".$sortdir." 
			LIMIT ".$tablelimit." ", "0")
		);
		//total number of rows
		$reviewtotalcount = $wpdb->get_var( 'SELECT COUNT(*) FROM '.$table_name );
		//total pages
		$totalpages = ceil($reviewtotalcount/$rowsperpage);
		
		if($reviewtotalcount>0){

		} else {
				$html .= '<tr>
						<th colspan="10" scope="col" class="manage-column">'.__('No reviews found. Please visit the <a href="?page=wp_pro-settings">Get FB Reviews</a> page to retrieve reviews from Facebook, or manually add one.', 'wp-review-slider-pro').'</th>
					</tr>';
		}					
				
				
		$html .= '</tbody>
		</table>';
		
		//pagination bar
		
		$html .= '<div id="wpfb_review_list_pagination_bar" class="wpfb_review_list_pagination_bar">';
		/*
		$currenturl = remove_query_arg( 'pnum' );
		for ($x = 1; $x <= $totalpages; $x++) {
			if($x==$pagenum){$blue_grey = "blue_grey";} else {$blue_grey ="";}
			$html .= '<a href="'.esc_url( add_query_arg( 'pnum', $x,$currenturl ) ).'" class="button '.$blue_grey.'">'.$x.'</a>';
		}
		*/
		$html .= '</div>';
		
				
		$html .= '<div id="reviewlistspinner" class="loadingspinner" style="display: none;"></div></div>';		
 
 echo $html;
?>
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
	
	<div id="tb_content_bulkedit" style="display:none;">
		<div><b><?php _e('Caution: ', 'wp-review-slider-pro'); ?></b><?php _e('This will bulk edit all reviews currently filtered. If no filter is used then it will change all reviews in your database. If you need to edit just one review then use the Pencil Icon to the left of the review.', 'wp-review-slider-pro'); ?>
		</br></br>
		<b><?php _e('"Replace": ', 'wp-review-slider-pro'); ?></b><?php _e('Deletes the current values for all the reviews and adds the values you input. ', 'wp-review-slider-pro'); ?></br>
		<b><?php _e('"Add To": ', 'wp-review-slider-pro'); ?></b><?php _e('Keeps the current values and adds the values in the input field.', 'wp-review-slider-pro'); ?>
		</br>
		<b><?php _e('"Remove": ', 'wp-review-slider-pro'); ?></b><?php _e('Searches the reviews and deletes the value in the input field.', 'wp-review-slider-pro'); ?></br>
		<b><?php _e('"Remove All": ', 'wp-review-slider-pro'); ?></b><?php _e('Deletes all current values.', 'wp-review-slider-pro'); ?>
		</div>
		</br>

			<div><span class="wprevpro_nr_bulk_name"><?php _e('Categories:', 'wp-review-slider-pro'); ?></span>
			<select name="wprevpro_nr_categories_bulk_sel" id="wprevpro_nr_categories_bulk_sel" class="wprevpro_nr_bulk_sel">
					<option value="replace"><?php _e('Replace', 'wp-review-slider-pro'); ?></option>
					<option value="addto"><?php _e('Add To', 'wp-review-slider-pro'); ?></option>
					<option value="deleteone"><?php _e('Remove', 'wp-review-slider-pro'); ?></option>
					<option value="delete"><?php _e('Remove All', 'wp-review-slider-pro'); ?></option>
			</select>
			<input class="wprevpro_nr_bulk_edit" id="wprevpro_nr_categories_bulk" data-custom="custom" type="text" name="wprevpro_nr_categories_bulk" placeholder="" value="">
			<span name="wprevpro_nr_categories_bulk_submit" id="wprevpro_nr_categories_bulk_submit" class="button button-primary">Update Categories</span>
			<img src="<?php echo WPREV_PLUGIN_URL; ?>/public/partials/imgs/loading_ripple.gif" id="savingformimg_cats" class="wprptemplate_update_loading_image" style="display:none;"><span class="bulk_update_msg" id="update_form_msg_cats"></span>
			</div>
			<p class='description'>
			Single or comma separated list of post category IDs. Allows you to associate the review with post categories. Submitted reviews are automatically associated with the categories of the post that the form is located on. ex: 1,3,5</p>
			</br>
			<div><span class="wprevpro_nr_bulk_name"><?php _e('Post IDs:', 'wp-review-slider-pro'); ?></span>
			<select name="wprevpro_nr_postid_bulk_sel" id="wprevpro_nr_postid_bulk_sel" class="wprevpro_nr_bulk_sel">
					<option value="replace"><?php _e('Replace', 'wp-review-slider-pro'); ?></option>
					<option value="addto"><?php _e('Add To', 'wp-review-slider-pro'); ?></option>
					<option value="deleteone"><?php _e('Remove', 'wp-review-slider-pro'); ?></option>
					<option value="delete"><?php _e('Remove All', 'wp-review-slider-pro'); ?></option>
			</select>
			<input class="wprevpro_nr_bulk_edit" id="wprevpro_nr_postid_bulk" data-custom="custom" type="text" name="wprevpro_nr_postid_bulk" placeholder="" value="">
			<span name="wprevpro_nr_postid_bulk_submit" id="wprevpro_nr_postid_bulk_submit" class="button button-primary">Update Post IDs</span>
			<img src="<?php echo WPREV_PLUGIN_URL; ?>/public/partials/imgs/loading_ripple.gif" id="savingformimg_posts" class="wprptemplate_update_loading_image" style="display:none;"><span class="bulk_update_msg" id="update_form_msg_posts"></span>
			</div>
			<p class='description'>
			Single or comma separated list of post IDs. Allows you to associate the review with multiple posts or page IDs. Submitted reviews are automatically associated with the post ID that the form is located on. ex: 11</p>
			</br>
			<div><span class="wprevpro_nr_bulk_name"><?php _e('Tags:', 'wp-review-slider-pro'); ?></span>
			<select name="wprevpro_nr_tags_bulk_sel" id="wprevpro_nr_tags_bulk_sel" class="wprevpro_nr_bulk_sel">
					<option value="replace"><?php _e('Replace', 'wp-review-slider-pro'); ?></option>
					<option value="addto"><?php _e('Add To', 'wp-review-slider-pro'); ?></option>
					<option value="deleteone"><?php _e('Remove', 'wp-review-slider-pro'); ?></option>
					<option value="delete"><?php _e('Remove All', 'wp-review-slider-pro'); ?></option>
			</select>
			<input class="wprevpro_nr_bulk_edit" id="wprevpro_nr_tags_bulk" data-custom="custom" type="text" name="wprevpro_nr_tags_bulk" placeholder="" value="">
			<span name="wprevpro_nr_tags_bulk_submit" id="wprevpro_nr_tags_bulk_submit" class="button button-primary">Update Tags</span>
			<img src="<?php echo WPREV_PLUGIN_URL; ?>/public/partials/imgs/loading_ripple.gif" id="savingformimg_tags" class="wprptemplate_update_loading_image" style="display:none;"><span class="bulk_update_msg" id="update_form_msg_tags"></span>
			</div>
			<p class='description'>
			 Assign tags to the reviews. Comma seperate list of tags. Allows you to filter by tag in the review template settings.</p>
			 


	</div>
	
<?php
//===================================
//get an arrow of all currently saved tweets and create a js array that we can search and modify
//====================================


?>