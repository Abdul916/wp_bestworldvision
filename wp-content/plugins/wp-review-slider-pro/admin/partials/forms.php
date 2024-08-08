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
    if (!current_user_can('manage_options') && $this->wprev_canuserseepage('forms')==false) {
        return;
    }
	$dbmsg = "";
	$html="";
	$currentform= new stdClass();
	$currentform->id="";
	$currentform->title ="";
	$currentform->style ="";
	$currentform->created_time_stamp ="";
	$currentform->form_misc ="";
	$currentform->form_css ="";
	$currentform->form_fields ="";
	$currentform->notifyemail =get_option( 'admin_email' );
	
	
	//db function variables
	global $wpdb;
	$table_name = $wpdb->prefix . 'wpfb_forms';
	
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
				$currentform = $wpdb->get_row( "SELECT * FROM ".$table_name." WHERE id = ".$tid );
			}
			//for copying
			if($_GET['taction'] == "copy" && $_GET['tid'] > 0){
				//security
				check_admin_referer( 'tcopy_');
				//get form array
				$currentform = $wpdb->get_row( "SELECT * FROM ".$table_name." WHERE id = ".$tid );
				//add new template
				$array = (array) $currentform;
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
/*
	if (isset($_POST['wprevpro_submittemplatebtn'])){
		//verify nonce wp_nonce_field( 'wprevpro_save_form');
		check_admin_referer( 'wprevpro_save_form');

		//get form submission values and then save or update
		$t_id = sanitize_text_field($_POST['edittid']);
		$title = sanitize_text_field($_POST['wprevpro_template_title']);
		//$style = sanitize_text_field($_POST['wprevpro_template_style']);
		$style = $_POST['wprevpro_template_style'];
		$form_css = sanitize_textarea_field($_POST['wprevpro_form_css']);
		$notifyemail= sanitize_text_field($_POST['wprevpro_template_notify_email']);

		//form fields 
		$formfieldsarray = array();
		
		//form misc
		$formmiscarray = array();
		$formmiscarray['showstars']=sanitize_text_field($_POST['wprevpro_form_misc_showstars']);
		$formmiscarray['showtitle']=sanitize_text_field($_POST['wprevpro_form_misc_showtitle']);
		$formmiscjson = json_encode($formmiscarray);

		$timenow = time();
		
		//+++++++++need to sql escape using prepare+++++++++++++++++++
		//+++++++++++++++++++++++++++++++++++++++++++++++++++++
		//insert or update
			$data = array( 
				'title' => "$title",
				'style' => "$style",
				'created_time_stamp' => "$timenow",
				'form_css' => "$form_css", 
				'form_misc' => "$formmiscjson",
				'notifyemail' => "$notifyemail",
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
			//insert
			$wpdb->insert( $table_name, $data, $format );
		} else {
			//update
			//print_r($data);
			$updatetempquery = $wpdb->update($table_name, $data, array( 'id' => $t_id ), $format, array( '%d' ));
			//$wpdb->show_errors();
			//$wpdb->print_error();
			if($updatetempquery>0){
				$dbmsg = '<div id="setting-error-wprevpro_message" class="updated settings-error notice is-dismissible">'.__('<p><strong>Form Updated!</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>', 'wp-review-slider-pro').'</div>';
			}
		}
		
	}
*/
	//Get list of all current forms--------------------------
	$currentforms = $wpdb->get_results("SELECT id, title, created_time_stamp, style FROM $table_name");
	//-------------------------------------------------------

	
	
?>
<script src='https://www.google.com/recaptcha/api.js'></script>
<div class="wrap wp_pro-settings" id="">
	<h1><img src="<?php echo plugin_dir_url( __FILE__ ) . 'logo.png'; ?>"></h1>
	
<?php 
include("tabmenu.php");

//query args for export and import
$url_tempdownload = admin_url( 'admin-post.php?action=print_forms.csv' );



?>
<div class="wprevpro_margin10">
	<a id="wprevpro_helpicon_posts" class="wprevpro_btnicononly button dashicons-before dashicons-editor-help"></a>
	<a id="wprevpro_addnewtemplate" class="button dashicons-before dashicons-plus-alt"><?php _e('Add New Forms Template', 'wp-review-slider-pro'); ?></a>
	<a href="<?php echo $url_tempdownload;?>" id="wprevpro_exporttemplates" class="button dashicons-before dashicons-download"><?php _e('Export Forms', 'wp-review-slider-pro'); ?></a>
	<a id="wprevpro_importtemplates" class="button dashicons-before dashicons-upload"><?php _e('Import Forms', 'wp-review-slider-pro'); ?></a>
</div>
<div class="wprevpro_margin10" id="importform" style='display:none;'>
	    <form  action="?page=wp_pro-forms" method="post" name="upload_excel" enctype="multipart/form-data">
		<p><b><?php _e('Use this form to import previously exported Forms.', 'wp-review-slider-pro'); ?></b></p>
			<input type="file" name="file" id="file">
			</br>
			<button type="submit" id="submit" name="Import" class="button-primary" data-loading-text="Loading..."><?php _e('Import', 'wp-review-slider-pro'); ?></button>
        </form>
</div>

<?php

//display message
echo $dbmsg;
		$html .= '
		<table class="wp-list-table widefat greenrowbackground striped posts">
			<thead>
				<tr>
					<th scope="col" width="30px" class="manage-column">'.__('ID', 'wp-review-slider-pro').'</th>
					<th scope="col" class="manage-column">'.__('Form Title', 'wp-review-slider-pro').'</th>
					<th scope="col" width="170px" class="manage-column">'.__('Date Created', 'wp-review-slider-pro').'</th>
					<th scope="col" width="350px" class="manage-column">'.__('Action', 'wp-review-slider-pro').'</th>
				</tr>
				</thead>
			<tbody id="">';
	foreach ( $currentforms as $tempcurrentform ) 
	{
	//remove query args we just used
	$urltrimmed = remove_query_arg( array('taction', 'id') );
		$tempeditbtn =  add_query_arg(  array(
			'taction' => 'edit',
			'tid' => "$tempcurrentform->id",
			),$urltrimmed);
			
		$url_tempeditbtn = wp_nonce_url( $tempeditbtn, 'tedit_');
			
		$tempdelbtn = add_query_arg(  array(
			'taction' => 'del',
			'tid' => "$tempcurrentform->id",
			),$urltrimmed) ;
			
		$url_tempdelbtn = wp_nonce_url( $tempdelbtn, 'tdel_');
		
						//for copying
		$tempcopybtn = add_query_arg(  array(
			'taction' => 'copy',
			'tid' => "$tempcurrentform->id",
			),$urltrimmed) ;
		$url_tempcopybtn = wp_nonce_url( $tempcopybtn, 'tcopy_');
			
		$html .= '<tr id="'.$tempcurrentform->id.'">
				<th scope="col" class=" manage-column">'.$tempcurrentform->id.'</th>
				<th scope="col" class=" manage-column"><b>'.$tempcurrentform->title.'</b></th>
				<th scope="col" class=" manage-column">'.date("F j, Y",$tempcurrentform->created_time_stamp) .'</th>
				<th scope="col" fid="'.$tempcurrentform->id.'" class="manage-column"><a class="wprevpro_displayshortcode button button-primary dashicons-before dashicons-shortcode">'.__('Shortcode', 'wp-review-slider-pro').'</a> <button templateid="'.$tempcurrentform->id.'" class="editformbtn button button-secondary dashicons-before dashicons-admin-generic">'.__('Edit', 'wp-review-slider-pro').'</button> <a href="'.$url_tempdelbtn.'" class="button button-secondary dashicons-before dashicons-trash">'.__('Delete', 'wp-review-slider-pro').'</a> <a href="'.$url_tempcopybtn.'" class="button button-secondary dashicons-before dashicons-admin-page">'.__('Copy', 'wp-fb-reviews').'</a></</th>
			</tr>';
	}	
		$html .= '</tbody></table>';
		
			
 echo $html;			
?>
<div class="wprevpro_margin10 w3_wprs-container" id="wprevpro_new_template">
<h3 id='fpc_edit_title'><?php _e('Create New Submission Form:', 'wp-review-slider-pro'); ?></h3>
	<div class="w3_wprs-col m6">
	<div id='edit_fields'>
	<form name="newformform" id="newformform" action="?page=wp_pro-forms" method="post" onsubmit="return validateForm()">
		<table class="wprevpro_margin10 form-table forminputtable">
			<tbody>
				<tr class="wprevpro_row">
					<th scope="row">
						<?php _e('Form Title:', 'wp-review-slider-pro'); ?>
					</th>
					<td>
						<input class='fullwidth' id="wprevpro_template_title" data-custom="custom" type="text" name="wprevpro_template_title" placeholder="" value="<?php echo $currentform->title; ?>" required>
						<p class="description">
						<?php _e('Enter a title or name for this submission form.', 'wp-review-slider-pro'); ?>		</p>
					</td>
				</tr>
			</tbody>
		</table>
		<div style="font-weight: 600;font-size: 14px;"><?php _e('Edit Form Input Fields:', 'wp-review-slider-pro'); ?></div>
		<div style="font-weight: normal;font-size: 12px;"><?php _e('(click to open, drag to reorder)', 'wp-review-slider-pro'); ?></div>
		
		<ul id="custom_fields_list" class='ui-sortable'>
		
	</ul>
			<table class="wprevpro_margin10 form-table forminputtable">
			<tbody>
			<tr class="wprevpro_row">
					<th scope="row">
						<?php _e('Required Label:', 'wp-review-slider-pro'); ?>
					</th>
					<td>
						<select name="wprevpro_form_show_required_text" id="wprevpro_form_show_required_text">
									  <option value="show" selected><?php _e('Show', 'wp-review-slider-pro'); ?></option>
									  <option value="hide"><?php _e('Hide', 'wp-review-slider-pro'); ?></option>
						</select>
						<input class='' id="wprevpro_form_required_text" data-custom="custom" type="text" name="wprevpro_form_required_text" placeholder="Required field" value="Required field">
						<p class="description">
						<?php _e('The Required Label at the top of the form.', 'wp-review-slider-pro'); ?></p>
					</td>
			</tr>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Header HTML:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<textarea name="wprevpro_form_headerhtml" id="wprevpro_form_headerhtml" cols="50" rows="3"></textarea>
					<p class="description">
					<?php _e('Add custom HTML to the very top of the Form.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Custom CSS:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<textarea name="wprevpro_form_css" id="wprevpro_form_css" cols="50" rows="3"></textarea>
					<p class="description">
					<?php _e('Enter custom CSS code to change the look of the form template even more when being displayed.</br>Example:', 'wp-review-slider-pro'); ?> <b>.wprevform-field>label {font-style: italic;}</b></p>
				</td>
			</tr>
				<tr class="wprevpro_row">
					<th scope="row">
						<?php _e('Notify Email:', 'wp-review-slider-pro'); ?>
					</th>
					<td>
						<input class='fullwidth' id="wprevpro_template_notify_email" data-custom="custom" type="text" name="wprevpro_template_notify_email" placeholder="" autocomplete='email' value="">
						<p class="description">
						<?php _e('Notify me at this email when someone submits a review. This can also be a comma separated list of email addresses.', 'wp-review-slider-pro'); ?>		</p>
					</td>
				</tr>
				<tr class="wprevpro_row">
					<th scope="row">
						<?php _e('reCAPTCHA', 'wp-review-slider-pro'); ?>
					</th>
					<td>
						<select name="wprevpro_form_showcaptcha" id="wprevpro_form_showcaptcha">
									  <option value="none"><?php _e('None', 'wp-review-slider-pro'); ?></option>
									  <option value="v2"><?php _e('reCAPTCHA v2', 'wp-review-slider-pro'); ?></option>
						</select>
						<div id="divrecap_fields">
							<input class='fullwidth' id="wprevpro_form_cap_sitekey" data-custom="custom" type="text" name="wprevpro_form_cap_sitekey" placeholder="Enter Your Site Key" value="">
							<input class='fullwidth' id="wprevpro_form_cap_secretekey" data-custom="custom" type="text" name="wprevpro_form_cap_secretekey" placeholder="Enter Your Secrete Key" value="">
						</div>
						<p class="description">
						<?php _e('Turn on reCAPTCHA to help block spam submissions. By default the form will use a hidden input to block most robots. Turning this on will block even more. Learn more ', 'wp-review-slider-pro'); ?><a href="https://www.google.com/recaptcha" target="_blank"><?php _e('here.', 'wp-review-slider-pro'); ?></a>		</p>
					</td>
				</tr>
				<tr class="wprevpro_row">
					<th scope="row">
						<?php _e('Button Text', 'wp-review-slider-pro'); ?>
					</th>
					<td>
						<input class='fullwidth' id="wprevpro_template_btn_text" data-custom="custom" type="text" name="wprevpro_template_btn_text" placeholder="Submit Review" value="" >
						<p class="description">
						<?php _e('Text for the Submit button.', 'wp-review-slider-pro'); ?>		</p>
					</td>
				</tr>
				<tr class="wprevpro_row">
					<th scope="row">
						<?php _e('Button Style', 'wp-review-slider-pro'); ?>
					</th>
					<td>
						<select name="wprevpro_template_btn_style" id="wprevpro_template_btn_style">
									<option value="default"><?php _e('Button 1', 'wp-review-slider-pro'); ?></option>
									<option value="btn2"><?php _e('Button 2', 'wp-review-slider-pro'); ?></option>
									<option value=""><?php _e('None', 'wp-review-slider-pro'); ?></option>
						</select>
						<p class="description">
						<?php _e('Change the look of the buttons on the form.', 'wp-review-slider-pro'); ?>		</p>
					</td>
				</tr>
				<tr class="wprevpro_row">
					<th scope="row">
						<?php _e('Button Class', 'wp-review-slider-pro'); ?>
					</th>
					<td>
						<input class='fullwidth' id="wprevpro_template_btn_class" data-custom="custom" type="text" name="wprevpro_template_btn_class" placeholder="" value="" >
						<p class="description">
						<?php _e('Add a CSS Class name to the form buttons.', 'wp-review-slider-pro'); ?>		</p>
					</td>
				</tr>
				<tr class="wprevpro_row">
					<th scope="row">
						<?php _e('Success Message', 'wp-review-slider-pro'); ?>
					</th>
					<td>
						<input class='fullwidth' id="wprevpro_template_success_msg" data-custom="custom" type="text" name="wprevpro_template_success_msg" placeholder="Thank you for your feedback!" value="" >
						<p class="description">
						<?php _e('Displayed after a review submission.', 'wp-review-slider-pro'); ?>		</p>
					</td>
				</tr>
				<tr class="wprevpro_row">
					<th scope="row">
						<?php _e('Show On Click', 'wp-review-slider-pro'); ?>
					</th>
					<td>
						<select name="wprevpro_form_showonclick" id="wprevpro_form_showonclick">
									  <option value="no" selected><?php _e('No', 'wp-review-slider-pro'); ?></option>
									  <option value="yes"><?php _e('Yes : Slide-down', 'wp-review-slider-pro'); ?></option>
									  <option value="popup"><?php _e('Yes : Pop-up', 'wp-review-slider-pro'); ?></option>
						</select>
						  <?php _e('Button Text:', 'wp-review-slider-pro'); ?> <input class='' id="wprevpro_form_showonclick_txt" data-custom="custom" type="text" name="wprevpro_form_showonclick_txt" placeholder="Leave a Review" value="">
						<p class="description">
						<?php _e('Hide the review form behind a button. Form will display when user clicks a button.', 'wp-review-slider-pro'); ?></p>
					</td>
				</tr>
				<tr class="wprevpro_row">
					<th scope="row">
						<?php _e('Auto Pop-up', 'wp-review-slider-pro'); ?>
					</th>
					<td>
						<select name="wprevpro_form_autopopup" id="wprevpro_form_autopopup">
									  <option value="no" selected><?php _e('No', 'wp-review-slider-pro'); ?></option>
									  <option value="yesshow"><?php _e('Yes: Leave button on page.', 'wp-review-slider-pro'); ?></option>
									  <option value="yeshide"><?php _e('Yes: Hide button on page.', 'wp-review-slider-pro'); ?></option>
						</select>
						<p class="description">
						<?php _e('A Pop-up including the form will automatically appear when the page is loaded. Choose what to do with the "Show on Click" button. If you aren\'t using the button then this will hide the entire form on the page. ', 'wp-review-slider-pro'); ?></p>
					</td>
				</tr>
				<tr class="wprevpro_row">
					<th scope="row">
						<?php _e('Submission Type', 'wp-review-slider-pro'); ?>
					</th>
					<td>
						<select name="wprevpro_form_useajax" id="wprevpro_form_useajax">
									  <option value="no" selected><?php _e('Page Reload', 'wp-review-slider-pro'); ?></option>
									  <option value="yes"><?php _e('Ajax Submission', 'wp-review-slider-pro'); ?></option>
									  <option value="prd"><?php _e('Page Redirect', 'wp-review-slider-pro'); ?></option>
						</select>
						<input style="display:none;" class='fullwidth' id="wprevpro_form_redirecturl" data-custom="custom" type="url" name="wprevpro_form_redirecturl" placeholder="Enter URL for redirect." value="">
						<p class="description formsubmittype pagereload">
						<?php _e('Reloads the page with a success message.', 'wp-review-slider-pro'); ?></p>
						<p style="display:none;" class="description formsubmittype pageajax">
						<?php _e('Use Ajax to submit the form. Make sure you are not caching the page more than an hour or this will not work.', 'wp-review-slider-pro'); ?></p>
						<p style="display:none;" class="description formsubmittype pageredirect">
						<?php _e('Redirects the user to the URL of your choice after form submission.', 'wp-review-slider-pro'); ?></p>
					</td>
				</tr>
				<tr class="wprevpro_row">
					<th scope="row">
						<?php _e('Auto Approve Submission', 'wp-review-slider-pro'); ?>
					</th>
					<td>
						<select name="wprevpro_form_autoapprove" id="wprevpro_form_autoapprove">
									  <option value="no" selected><?php _e('No', 'wp-review-slider-pro'); ?></option>
									  <option value="yes"><?php _e('Yes', 'wp-review-slider-pro'); ?></option>
						</select>
						<p class="description">
						<?php _e('Approve (show) reviews submitted through this form automatically. Use with caution.', 'wp-review-slider-pro'); ?></p>
					</td>
				</tr>
				<tr class="wprevpro_row">
					<th scope="row">
						<?php _e('Review Icon Image Url:', 'wp-review-slider-pro'); ?>
					</th>
					<td>
						<input class='fullwidth' id="wprevpro_form_icon_image_url" data-custom="custom" type="url" name="wprevpro_form_icon_image_url" placeholder="" value=""><a id="upload_logo_button" class="button"><?php _e('Upload', 'wp-review-slider-pro'); ?></a>
						<p class="description">
						<?php _e('Allows you to set a review icon to display with the submitted review text. Input the URL to the image file. Size of 32px height works best.', 'wp-review-slider-pro'); ?></p>
					</td>
				</tr>
				<tr class="wprevpro_row">
					<th scope="row">
						<?php _e('Review Icon Link Url:', 'wp-review-slider-pro'); ?>
					</th>
					<td>
						<input class='fullwidth' id="wprevpro_form_icon_link_url" data-custom="custom" type="url" name="wprevpro_form_icon_link_url" placeholder="" value="">
						<p class="description">
						<?php _e('Leave this blank to link the icon back to the page this form is on. Enter a url if you want to link the icon to a different page.', 'wp-review-slider-pro'); ?></p>
					</td>
				</tr>
				<tr class="wprevpro_row">
					<th scope="row">
						<?php _e('Tags:', 'wp-review-slider-pro'); ?>
					</th>
					<td>
						<input class='fullwidth' id="wprevpro_form_tags" data-custom="custom" type="url" name="wprevpro_form_tags" placeholder="" value="">
						<p class="description">
						<?php _e('Enter a tag or comma separated tags if you would like to tag reviews left with this form. Tags can be used to filter reviews in a review template.', 'wp-review-slider-pro'); ?></p>
					</td>
				</tr>
				<tr class="wprevpro_row">
					<th scope="row">
						<?php _e('Track IP', 'wp-review-slider-pro'); ?>
					</th>
					<td>
						<select name="wprevpro_form_ip" id="wprevpro_form_ip">
									<option value=""><?php _e('No', 'wp-review-slider-pro'); ?></option>
									<option value="yes"><?php _e('Yes', 'wp-review-slider-pro'); ?></option>
						</select>
						<p class="description">
						<?php _e('Record IP address of person submitting form.', 'wp-review-slider-pro'); ?>		</p>
					</td>
				</tr>
				<tr class="wprevpro_row">
					<th scope="row">
						<?php _e('Multiple Submissions', 'wp-review-slider-pro'); ?>
					</th>
					<td>
						<select name="wprevpro_form_onesub" id="wprevpro_form_onesub">
									<option value=""><?php _e('Yes', 'wp-review-slider-pro'); ?></option>
									<option value="nop"><?php _e('No - Page ID', 'wp-review-slider-pro'); ?></option>
									<option value="nof"><?php _e('No - Form ID', 'wp-review-slider-pro'); ?></option>
						</select>
						&nbsp&nbspDisplay Text: <input class='' id="wprevpro_form_onesub_msg" data-custom="custom" type="text" name="wprevpro_form_onesub_msg" placeholder="You have already submitted this form, we appreciate your feedback!" value="" >
						<p class="description">
						<?php _e('Allow a person to submit this form more than once. If you use the same form on multiple pages, check "No - Form ID" to hide the form everywhere or "No - Page ID" to hide it depending on the page.', 'wp-review-slider-pro'); ?>		</p>
					</td>
				</tr>
			</tbody>
		</table>
		<?php 
		//security nonce
		wp_nonce_field( 'wprevpro_save_form');
		?>
		<input type="hidden" name="edittid" id="edittid"  value="<?php echo $currentform->id; ?>">
		<button id="wprevpro_addnewform_submit" style="" class="button button-secondary"><?php _e('Done Editing Form', 'wp-review-slider-pro'); ?></button>
		<a id="wprevpro_addnewtemplate_cancel" class="button button-secondary"><?php _e('Cancel', 'wp-review-slider-pro'); ?></a>
		</form>
	</div>
	</div>
	<div id="form_prev_container" class="w3_wprs-col m6">
		<div id="form_preview">
		<h3 id='fpc_title'><?php _e('Preview:', 'wp-review-slider-pro'); ?></h3>
		<div id="wprevpro_form" class="wprevpro_form">
			<div class="wprevpro_form_inner">
				<p class="required-notice"><span class="required symbol"></span><?php _e('Required field', 'wp-review-slider-pro'); ?></p>
				<form enctype="multipart/form-data" autocomplete="off">
					<div class="form-field field-post_title"><label for="wpmtst_post_title"><?php _e('Heading', 'wp-review-slider-pro'); ?></label><span class="before"><?php _e('Before Text', 'wp-review-slider-pro'); ?></span><input id="wpmtst_post_title" type="text" class="text" name="post_title" value="" tabindex="0"><span class="after"><?php _e('A headline for your testimonial.', 'wp-review-slider-pro'); ?></span>
					</div>
					<div class="form-field wprevpro_submit">
						<label><input type="button" id="wprevpro_submit_review" name="wprevpro_submit_review" value="Add Testimonial" class="button" tabindex="0"></label>
					</div>
					
				</form>
			</div>
		</div>
		</div>
	</div>
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