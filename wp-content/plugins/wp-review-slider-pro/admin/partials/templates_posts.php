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
	if (!current_user_can('manage_options') && $this->wprev_canuserseepage('templates_posts')==false) {
        return;
    }
	
	//check to see if they can use premium code
	$canusepremiumcode = wrsp_fs()->can_use_premium_code();
	//testing-tool
	//$canusepremiumcode = false;
	
	$dbmsg = "";
	$html="";
	$currenttemplate= new stdClass();
	$currenttemplate->id="";
	$currenttemplate->title ="";
	$currenttemplate->template_type ="";
	$currenttemplate->style ="";
	$currenttemplate->created_time_stamp ="";
	$currenttemplate->display_num ="";
	$currenttemplate->display_num_rows ="";
	$currenttemplate->load_more ="no";
	$currenttemplate->load_more_text ="Load More";
	$currenttemplate->display_masonry ="";
	$currenttemplate->display_order ="newest";
	$currenttemplate->display_order_second ="newest";
	$currenttemplate->display_order_limit ="all";
	$currenttemplate->hide_no_text ="";
	$currenttemplate->template_css ="";
	$currenttemplate->min_rating ="";
	$currenttemplate->min_words ="";
	$currenttemplate->max_words ="";
	$currenttemplate->word_or_char ="";
	$currenttemplate->rtype ="";
	$currenttemplate->rpage ="";
	$currenttemplate->showreviewsbyid ="";
	$currenttemplate->createslider ="sli";
	$currenttemplate->numslides ="3";
	$currenttemplate->sliderautoplay ="";
	$currenttemplate->sliderdirection ="";
	$currenttemplate->sliderarrows ="";
	$currenttemplate->sliderdots ="";
	$currenttemplate->sliderdelay ="";
	$currenttemplate->sliderspeed ="750";
	$currenttemplate->sliderheight ="";
	$currenttemplate->slidermobileview ="";
	$currenttemplate->template_misc ="";
	$currenttemplate->read_more ="";
	//$currenttemplate->read_more_num ="";
	$currenttemplate->read_more_text ="read more";
	$currenttemplate->read_less_text ="read less";
	$currenttemplate->review_same_height ="";
	$currenttemplate->google_snippet_add ="";
	$currenttemplate->google_snippet_type ="";
	$currenttemplate->google_snippet_name ="";
	$currenttemplate->google_snippet_desc ="";
	$currenttemplate->google_snippet_business_image ="";
	
	$currenttemplate->google_snippet_more="";
	
	$currenttemplate->facebook_icon ="yes";
	$currenttemplate->facebook_icon_link ="";
	$currenttemplate->cache_settings ="";
	$currenttemplate->add_profile_link ="";
	
	$currenttemplate->string_sel="";
	$currenttemplate->string_text="";
	$currenttemplate->string_selnot="";
	$currenttemplate->string_textnot="";
	
	$currenttemplate->showreviewsbyid_sel="";
	
	//$currenttemplate->length_type="words";
	
	//echo $this->_token;  wprevpro_t_read_more_text
	//if token = wp-review-slider-pro then using free version
	
	//db function variables
	global $wpdb;
	$table_name = $wpdb->prefix . 'wpfb_post_templates';
	
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
				$currenttemplate = $wpdb->get_row( "SELECT * FROM ".$table_name." WHERE id = ".$tid );
			}
			//for copying
			if($_GET['taction'] == "copy" && $_GET['tid'] > 0){
				//security
				check_admin_referer( 'tcopy_');
				//get form array
				$currenttemplate = $wpdb->get_row( "SELECT * FROM ".$table_name." WHERE id = ".$tid );
				//add new template
				$array = (array) $currenttemplate;
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

	if (isset($_POST['wprevpro_submittemplatebtn']) ){
		//verify nonce wp_nonce_field( 'wprevpro_save_template');
		check_admin_referer( 'wprevpro_save_template');

		//get form submission values and then save or update
		$t_id = sanitize_text_field($_POST['edittid']);
		$title = sanitize_text_field($_POST['wprevpro_template_title']);
		$template_type = sanitize_text_field($_POST['wprevpro_template_type']);
		$style = sanitize_text_field($_POST['wprevpro_template_style']);
		$display_num = sanitize_text_field($_POST['wprevpro_t_display_num']);
		$display_num_rows = sanitize_text_field($_POST['wprevpro_t_display_num_rows']);
		$display_order = sanitize_text_field($_POST['wprevpro_t_display_order']);
		$display_order_second = sanitize_text_field($_POST['wprevpro_t_display_order_second']);
		$display_order_limit = sanitize_text_field($_POST['wprevpro_t_display_order_limit']);
		$hide_no_text = sanitize_text_field($_POST['wprevpro_t_hidenotext']);
		$template_css = sanitize_textarea_field($_POST['wprevpro_template_css']);
		//$template_css = $_POST['wprevpro_template_css'];
		
		$createslider = sanitize_text_field($_POST['wprevpro_t_createslider']);
		$numslides = sanitize_text_field($_POST['wprevpro_t_numslides']);
		
		$load_more = sanitize_text_field($_POST['wprevpro_t_load_more']);
		$load_more_text = sanitize_text_field($_POST['wprevpro_t_load_more_text']);
		
		$read_more = sanitize_text_field($_POST['wprevpro_t_read_more']);
		//$read_more_num = sanitize_text_field($_POST['wprevpro_t_read_more_num']);
		$read_more_text = sanitize_text_field($_POST['wprevpro_t_read_more_text']);
		$read_less_text = sanitize_text_field($_POST['wprevpro_t_read_less_text']);
		

		
		$facebook_icon = sanitize_text_field($_POST['wprevpro_t_facebook_icon']);
		$facebook_icon_link = sanitize_text_field($_POST['wprevpro_t_facebook_icon_link']);
		
		$google_snippet_add = sanitize_text_field($_POST['wprevpro_t_google_snippet_add']);
		$google_snippet_type = sanitize_text_field($_POST['wprevpro_t_google_snippet_type']);
		$google_snippet_name = sanitize_text_field($_POST['wprevpro_t_google_snippet_name']);
		$google_snippet_desc = sanitize_text_field($_POST['wprevpro_t_google_snippet_desc']);
		$google_snippet_business_image = esc_url_raw($_POST['wprevpro_t_google_snippet_business_image']);
		
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

		$google_snippet_irm = sanitize_text_field($_POST['wprevpro_t_google_snippet_irm']);
		$google_snippet_irm_type = sanitize_text_field($_POST['wprevpro_t_google_snippet_irm_type']);
		
		$google_snippet_schemaid = sanitize_text_field($_POST['wprevpro_t_google_snippet_more_schemaid']);
		
		$google_snippet_tvr = sanitize_text_field($_POST['wprevpro_t_google_snippet_tvr']);
		
		$google_snippet_more_array = array("schemaid"=>"$google_snippet_schemaid","telephone"=>"$google_snippet_more_phone","priceRange"=>"$google_snippet_more_price","streetAddress"=>"$google_snippet_more_street","addressLocality"=>"$google_snippet_more_city","addressRegion"=>"$google_snippet_more_state","postalCode"=>"$google_snippet_more_zip","brand"=>"$google_snippet_prodbrand","price"=>"$google_snippet_prodprice","priceCurrency"=>"$google_snippet_prodpricec","sku"=>"$google_snippet_prodsku","giname"=>"$google_snippet_prodginame","gival"=>"$google_snippet_prodgival","url"=>"$google_snippet_produrl","availability"=>"$google_snippet_prodavailability","priceValidUntil"=>"$google_snippet_prodpriceValidUntil","irm"=>"$google_snippet_irm","irm_type"=>"$google_snippet_irm_type","tvr"=>"$google_snippet_tvr");

		//encode to save in database
		$google_snippet_more_array_encode = json_encode($google_snippet_more_array);
		$cache_settings = sanitize_text_field($_POST['wprevpro_t_cache_settings']);
		
		$add_profile_link = sanitize_text_field($_POST['wprevpro_t_profile_link']);
		
		$display_masonry = sanitize_text_field($_POST['wprevpro_t_display_masonry']);
		
		//pro settings
		if ( $canusepremiumcode ) {
			$sliderautoplay = sanitize_text_field($_POST['wprevpro_sliderautoplay']);
			$sliderdirection = sanitize_text_field($_POST['wprevpro_sliderdirection']);
			$sliderarrows = sanitize_text_field($_POST['wprevpro_sliderarrows']);
			$sliderdots = sanitize_text_field($_POST['wprevpro_sliderdots']);
			$sliderdelay = sanitize_text_field($_POST['wprevpro_t_sliderdelay']);
			$sliderspeed = sanitize_text_field($_POST['wprevpro_t_sliderspeed']);
			$sliderheight = sanitize_text_field($_POST['wprevpro_sliderheight']);
			$slidermobileview = sanitize_text_field($_POST['wprevpro_slidermobileview']);
			$min_rating = sanitize_text_field($_POST['wprevpro_t_min_rating']);
			$min_words = sanitize_text_field($_POST['wprevpro_t_min_words']);
			$max_words = sanitize_text_field($_POST['wprevpro_t_max_words']);
			$word_or_char = sanitize_text_field($_POST['wprevpro_t_word_or_char']);
			$string_sel = sanitize_text_field($_POST['wprevpro_t_string_sel']);
			$string_text = sanitize_text_field($_POST['wprevpro_t_string_text']);
			$string_selnot = sanitize_text_field($_POST['wprevpro_t_string_selnot']);
			$string_textnot = sanitize_text_field($_POST['wprevpro_t_string_textnot']);
			$showreviewsbyid = sanitize_text_field($_POST['wprevpro_t_showreviewsbyid']);
			$review_same_height = sanitize_text_field($_POST['wprevpro_t_review_same_height']);
			$showreviewsbyid_sel= sanitize_text_field($_POST['wprevpro_t_showreviewsbyid_sel']);
		} else {
			$sliderautoplay = "";
			$sliderdirection = "";
			$screensize ="";
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
		
		//turn off masonry if same height set to yes
		if($review_same_height=="yes" || $review_same_height=="cur" || $review_same_height=="yea" || $display_num=="1"){
			$display_masonry = "no";
		}
			
		$showreviewsbyidarray = explode("-",$showreviewsbyid);
		$showreviewsbyidjson = json_encode($showreviewsbyidarray);
		
		//template misc
		$templatemiscarray = array();
		$templatemiscarray['showstars']=sanitize_text_field($_POST['wprevpro_template_misc_showstars']);
		
		$templatemiscarray['dateformat']=sanitize_text_field($_POST['wprevpro_template_misc_dateformat']);
		if($templatemiscarray['dateformat']=='hide'){
			$templatemiscarray['showdate']='no';
		}
		
		$templatemiscarray['bgcolor1']=sanitize_text_field($_POST['wprevpro_template_misc_bgcolor1']);
		$templatemiscarray['bgcolor2']=sanitize_text_field($_POST['wprevpro_template_misc_bgcolor2']);
		$templatemiscarray['tcolor1']=sanitize_text_field($_POST['wprevpro_template_misc_tcolor1']);
		$templatemiscarray['tcolor2']=sanitize_text_field($_POST['wprevpro_template_misc_tcolor2']);
		$templatemiscarray['tcolor3']=sanitize_text_field($_POST['wprevpro_template_misc_tcolor3']);
		$templatemiscarray['tfont1']=sanitize_text_field($_POST['wprevpro_template_misc_tfont1']);
		$templatemiscarray['tfont2']=sanitize_text_field($_POST['wprevpro_template_misc_tfont2']);
		$templatemiscarray['bradius']=sanitize_text_field($_POST['wprevpro_template_misc_bradius']);
		$templatemiscarray['bcolor']=sanitize_text_field($_POST['wprevpro_template_misc_bcolor']);
		$templatemiscarray['lastnameformat']=sanitize_text_field($_POST['wprevpro_template_misc_lastname']);
		$templatemiscarray['firstnameformat']=sanitize_text_field($_POST['wprevpro_template_misc_firstname']);
		$templatemiscarray['showtitle']=sanitize_text_field($_POST['wprevpro_template_misc_showtitle']);
		$templatemiscarray['starcolor']=sanitize_text_field($_POST['wprevpro_template_misc_starcolor']);
		$templatemiscarray['starsize']=sanitize_text_field($_POST['wprevpro_template_misc_starsize']);
		$templatemiscarray['iconsize']=sanitize_text_field($_POST['wprevpro_template_misc_iconsize']);
		$templatemiscarray['stariconfull']=sanitize_text_field($_POST['wprevpro_template_misc_stariconfull']);
		$templatemiscarray['stariconempty']=sanitize_text_field($_POST['wprevpro_template_misc_stariconempty']);
		$templatemiscarray['starlocation']=sanitize_text_field($_POST['wprevpro_template_misc_starlocation']);
		$templatemiscarray['avataropt']=sanitize_text_field($_POST['wprevpro_template_misc_avataropt']);
		$templatemiscarray['avatarsize']=sanitize_text_field($_POST['wprevpro_template_misc_avatarsize']);
		$templatemiscarray['inibgcolor']=sanitize_text_field($_POST['wprevpro_template_misc_inibgcolor']);
		$templatemiscarray['showmedia']=sanitize_text_field($_POST['wprevpro_t_showmedia']);
		$templatemiscarray['ownerres']=sanitize_text_field($_POST['wprevpro_t_ownerres']);
		
		$templatemiscarray['showlocation']=sanitize_text_field($_POST['wprevpro_t_showlocation']);
		$templatemiscarray['showcdetails']=sanitize_text_field($_POST['wprevpro_t_showcdetails']);
		$templatemiscarray['showcdetailslink']=sanitize_text_field($_POST['wprevpro_t_showcdetailslink']);
		//$templatemiscarray['length_type']=sanitize_text_field($_POST['wprevpro_t_length_type']);
		$templatemiscarray['load_more_porb']=sanitize_text_field($_POST['wprevpro_t_load_more_porb']);
		$templatemiscarray['verified']=sanitize_text_field($_POST['wprevpro_template_misc_verified']);
		$templatemiscarray['screensize']=sanitize_text_field($_POST['wprevpro_screensize']);

		$templatemiscarray['showsourcep']=sanitize_text_field($_POST['wprevpro_t_showsourcep']);
		$templatemiscarray['showsourceplink']=sanitize_text_field($_POST['wprevpro_t_showsourceplink']);
		
		if(isset($_POST['wprevpro_choosetypes'])){
			$templatemiscarray['choosetypes']=$_POST['wprevpro_choosetypes'];
		} else {
			$templatemiscarray['choosetypes']=[];
		}
		$templatemiscarray['readmcolor']=sanitize_text_field($_POST['wprevpro_template_misc_readmcolor']);
		if(isset($_POST['wprevpro_t_rtype_wpmllang'])){
		$templatemiscarray['wpmllang']=sanitize_text_field($_POST['wprevpro_t_rtype_wpmllang']);
		}
		if(isset($_POST['wprevpro_template_misc_sliderarrowcolor'])){
		$templatemiscarray['sliderarrowcolor']=sanitize_text_field($_POST['wprevpro_template_misc_sliderarrowcolor']);
		}
		if(isset($_POST['wprevpro_template_misc_sliderdotcolor'])){
			$templatemiscarray['sliderdotcolor']=sanitize_text_field($_POST['wprevpro_template_misc_sliderdotcolor']);
		}
		if(isset($_POST['wprevpro_t_dropshadow'])){
			$templatemiscarray['dropshadow']=sanitize_text_field($_POST['wprevpro_t_dropshadow']);
		}
		if(isset($_POST['wprevpro_t_raisemouse'])){
			$templatemiscarray['raisemouse']=sanitize_text_field($_POST['wprevpro_t_raisemouse']);
		}
		if(isset($_POST['wprevpro_t_zoommouse'])){
			$templatemiscarray['zoommouse']=sanitize_text_field($_POST['wprevpro_t_zoommouse']);
		}
		
		$templatemiscarray['header_banner']=sanitize_text_field($_POST['wprevpro_t_header_banner']);

		
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
		//echo $_POST['wprevpro_t_header_text']."<br>";
		$templatemiscarray['header_rating']='';
		$templatemiscarray['header_search']='';
		$templatemiscarray['header_sort']='';
		$templatemiscarray['header_source']='';
		$templatemiscarray['header_langcodes']='';
		$templatemiscarray['header_tag']='';
		$templatemiscarray['header_rtypes']='';
		
		$templatemiscarray['header_text']=wp_kses($_POST['wprevpro_t_header_text'],$arrallowedtags);
		//echo $templatemiscarray['header_text']."<br>";
		$templatemiscarray['header_text_tag']=sanitize_text_field($_POST['wprevpro_t_header_text_tag']);
		$templatemiscarray['header_filter_opt']=sanitize_text_field($_POST['wprevpro_t_header_filter_opt']);
		if(isset($_POST['wprevpro_t_header_search'])){
		$templatemiscarray['header_search']=sanitize_text_field($_POST['wprevpro_t_header_search']);
		}
		$templatemiscarray['header_search_place']=sanitize_text_field($_POST['wprevpro_t_header_search_place']);
		if(isset($_POST['wprevpro_t_header_sort'])){
		$templatemiscarray['header_sort']=sanitize_text_field($_POST['wprevpro_t_header_sort']);
		}
		$templatemiscarray['header_sort_place']=sanitize_text_field($_POST['wprevpro_t_header_sort_place']);
		if(isset($_POST['wprevpro_t_header_tag'])){
		$templatemiscarray['header_tag']=sanitize_text_field($_POST['wprevpro_t_header_tag']);
		}
		
		$templatemiscarray['header_tags']=sanitize_text_field($_POST['wprevpro_t_header_tags']);
		$templatemiscarray['header_tag_search']=sanitize_text_field($_POST['wprevpro_t_header_tag_search']);
		$templatemiscarray['header_rating_place']=sanitize_text_field($_POST['wprevpro_t_header_rating_place']);
		if(isset($_POST['wprevpro_t_header_rating'])){
		$templatemiscarray['header_rating']=sanitize_text_field($_POST['wprevpro_t_header_rating']);
		}
		$templatemiscarray['header_langcodes_list']=sanitize_text_field($_POST['wprevpro_t_header_langcodes_list']);
		$templatemiscarray['header_langcodes_list']= trim($templatemiscarray['header_langcodes_list'], " \t\n\r");
		$templatemiscarray['header_langcodes_place']=sanitize_text_field($_POST['wprevpro_t_header_langcodes_place']);
		if(isset($_POST['wprevpro_t_header_langcodes'])){
		$templatemiscarray['header_langcodes']=sanitize_text_field($_POST['wprevpro_t_header_langcodes']);
		}
		
		$templatemiscarray['header_source_place']=sanitize_text_field($_POST['wprevpro_t_header_source_place']);
		if(isset($_POST['wprevpro_t_header_source'])){
		$templatemiscarray['header_source']=sanitize_text_field($_POST['wprevpro_t_header_source']);
		}
		if(isset($_POST['wprevpro_t_header_rtypes'])){
		$templatemiscarray['header_rtypes']=sanitize_text_field($_POST['wprevpro_t_header_rtypes']);
		}
	
		//for pagination button style
		$templatemiscarray['ps_bw']=sanitize_text_field($_POST['wprevpro_t_ps_bw']);
		$templatemiscarray['ps_br']=sanitize_text_field($_POST['wprevpro_t_ps_br']);
		$templatemiscarray['ps_bcolor']=sanitize_text_field($_POST['wprevpro_t_ps_bcolor']);
		$templatemiscarray['ps_bgcolor']=sanitize_text_field($_POST['wprevpro_t_ps_bgcolor']);
		$templatemiscarray['ps_fontcolor']=sanitize_text_field($_POST['wprevpro_t_ps_fontcolor']);
		$templatemiscarray['ps_fsize']=sanitize_text_field($_POST['wprevpro_t_ps_fsize']);
		$templatemiscarray['ps_paddingt']=sanitize_text_field($_POST['wprevpro_t_ps_paddingt']);
		$templatemiscarray['ps_paddingb']=sanitize_text_field($_POST['wprevpro_t_ps_paddingb']);
		$templatemiscarray['ps_paddingl']=sanitize_text_field($_POST['wprevpro_t_ps_paddingl']);
		$templatemiscarray['ps_paddingr']=sanitize_text_field($_POST['wprevpro_t_ps_paddingr']);
		$templatemiscarray['ps_margint']=sanitize_text_field($_POST['wprevpro_t_ps_margint']);
		$templatemiscarray['ps_marginb']=sanitize_text_field($_POST['wprevpro_t_ps_marginb']);
		$templatemiscarray['ps_marginl']=sanitize_text_field($_POST['wprevpro_t_ps_marginl']);
		$templatemiscarray['ps_marginr']=sanitize_text_field($_POST['wprevpro_t_ps_marginr']);

				
		if(isset($_POST['wprevpro_default_avatar'])){
			$templatemiscarray['default_avatar']=sanitize_text_field($_POST['wprevpro_default_avatar']);
		} else {
			$templatemiscarray['default_avatar']="";
		}
		
		//for post and cat filters
		$templatemiscarray['postfilter']=sanitize_text_field($_POST['wprevpro_t_postfilter']);
		$templatemiscarray['categoryfilter']=sanitize_text_field($_POST['wprevpro_t_categoryfilter']);
		
		$templatemiscarray['postfilterlist']=sanitize_text_field($_POST['wprevpro_t_postfilterlist']);
		$templatemiscarray['categoryfilterlist']=sanitize_text_field($_POST['wprevpro_t_categoryfilterlist']);
		$templatemiscarray['langfilterlist']=sanitize_text_field($_POST['wprevpro_t_langfilterlist']);
		$templatemiscarray['tagfilterlist']=sanitize_text_field($_POST['wprevpro_t_tagfilterlist']);
		
		$templatemiscarray['tagfilterlist_opt']=sanitize_text_field($_POST['wprevpro_t_tagfilterlist_opt']);
		

		//convert to json, function in class-wp-review-slider-pro-admin-common.php
		require_once WPREV_PLUGIN_DIR . 'admin/class-wp-review-slider-pro-admin-common.php';
		$plugin_admin_common = new Common_Admin_Functions();
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
		if(isset($_POST['wprevpro_t_over_yelp'])){
		$templatemiscarray['icon_over_yelp']=sanitize_text_field($_POST['wprevpro_t_over_yelp']);
		}
		if(isset($_POST['wprevpro_t_over_trip'])){
		$templatemiscarray['icon_over_trip']=sanitize_text_field($_POST['wprevpro_t_over_trip']);
		}
		
		//margins
		$templatemiscarray['template_margin_tb']=sanitize_text_field($_POST['wprevpro_t_template_margin_tb']);
		$templatemiscarray['template_margin_lr']=sanitize_text_field($_POST['wprevpro_t_template_margin_lr']);
		$templatemiscarray['template_margin_tb_m']=sanitize_text_field($_POST['wprevpro_t_template_margin_tb_m']);
		$templatemiscarray['template_margin_lr_m']=sanitize_text_field($_POST['wprevpro_t_template_margin_lr_m']);
		
		//slick slider settings
		if(isset($_POST['wprevpro_sli_infinite'])){
			$templatemiscarray['sli_infinite']=sanitize_text_field($_POST['wprevpro_sli_infinite']);
		}
		if(isset($_POST['wprevpro_sli_slidestoscroll'])){
		$templatemiscarray['sli_slidestoscroll']=sanitize_text_field($_POST['wprevpro_sli_slidestoscroll']);
		}
		if(isset($_POST['wprevpro_sli_avatarnav'])){
		$templatemiscarray['sli_avatarnav']=sanitize_text_field($_POST['wprevpro_sli_avatarnav']);
		}
		if(isset($_POST['wprevpro_sli_centermode'])){
		$templatemiscarray['sli_centermode']=sanitize_text_field($_POST['wprevpro_sli_centermode']);
		}		
		$templatemiscarray['sli_centermode_padding']=sanitize_text_field($_POST['wprevpro_sli_centermode_padding']);
		
		if(isset($_POST['wprevpro_t_read_more_pop'])){
		$templatemiscarray['readmpop']=sanitize_text_field($_POST['wprevpro_t_read_more_pop']);
		}
		
		//cut revs settings
		if(isset($_POST['wprevpro_t_cutrevs'])){
		$templatemiscarray['cutrevs']=sanitize_text_field($_POST['wprevpro_t_cutrevs']);
		}
		if(isset($_POST['wprevpro_t_cutrevs_lnum'])){
		$templatemiscarray['cutrevs_lnum']=sanitize_text_field($_POST['wprevpro_t_cutrevs_lnum']);
		}
		if(isset($_POST['wprevpro_t_scrollbarauto'])){
		$templatemiscarray['scrollbarauto']=sanitize_text_field($_POST['wprevpro_t_scrollbarauto']);
		}
		
		
		
		//banner settings.
		$templatemiscarray['bbgcolor']=sanitize_text_field($_POST['wprevpro_t_bbgcolor']);
		$templatemiscarray['btxtcolor']=sanitize_text_field($_POST['wprevpro_t_btxtcolor']);
		$templatemiscarray['bbordercolor']=sanitize_text_field($_POST['wprevpro_t_bbordercolor']);
		$templatemiscarray['bncradius']=sanitize_text_field($_POST['wprevpro_t_bncradius']);
		if(isset($_POST['wprevpro_t_bndropshadow'])){
		$templatemiscarray['bndropshadow']=sanitize_text_field($_POST['wprevpro_t_bndropshadow']);
		}
		if(isset($_POST['wprevpro_t_bnrevusbtn'])){
		$templatemiscarray['bnrevusbtn']=sanitize_text_field($_POST['wprevpro_t_bnrevusbtn']);
		}
		
		$templatemiscarray['bn_filter_opt']=sanitize_text_field($_POST['wprevpro_t_bn_filter_opt']);
		$templatemiscarray['bnshowsub']=sanitize_text_field($_POST['wprevpro_t_bnshowsub']);
		$templatemiscarray['bnshowsubtext']=sanitize_text_field($_POST['wprevpro_t_bnshowsubtext']);
		$templatemiscarray['bnshowman']=sanitize_text_field($_POST['wprevpro_t_bnshowman']);
		$templatemiscarray['bnshowmantext']=sanitize_text_field($_POST['wprevpro_t_bnshowmantext']);
		$templatemiscarray['bnhidesource']=sanitize_text_field($_POST['wprevpro_t_bnhidesource']);										
		
		//banner btn settings
		$templatemiscarray['revus_bgcolor']=sanitize_text_field($_POST['wprevpro_t_revus_bgcolor']);
		$templatemiscarray['revus_fontcolor']=sanitize_text_field($_POST['wprevpro_t_revus_fontcolor']);
		$templatemiscarray['revus_bcolor']=sanitize_text_field($_POST['wprevpro_t_revus_bcolor']);
		$templatemiscarray['revus_txtval']=sanitize_text_field($_POST['wprevpro_t_revus_txtval']);
		$templatemiscarray['revus_btnaction']=sanitize_text_field($_POST['wprevpro_t_revus_btnaction']);
		$templatemiscarray['revus_puform']=sanitize_text_field($_POST['wprevpro_t_revus_puform']);
		$templatemiscarray['revus_btnlink']=sanitize_text_field($_POST['wprevpro_t_revus_btnlink']);
		//multi_links
		for ($x = 1; $x <= 6; $x++) {
			if(isset($_POST['wprevpro_t_revus_btnln'.$x])){
				$templatemiscarray['revus_btnln'][$x]=sanitize_text_field($_POST['wprevpro_t_revus_btnln'.$x]);
			}
			if(isset($_POST['wprevpro_t_revus_btnlu'.$x])){
				$templatemiscarray['revus_btnlu'][$x]=sanitize_text_field($_POST['wprevpro_t_revus_btnlu'.$x]);
			}
		}


		//$templatemiscjson = json_encode($templatemiscarray);
		$templatemiscjson =json_encode($templatemiscarray,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
		
		//echo $templatemiscjson;
		
		//$rtype = htmlentities($_POST['wprevpro_t_rtype']);
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
				//echo strtolower($temptype->type).":";
				if(isset($_POST['wprevpro_t_rtype_'.$typelowercasecheck])){
					//echo $typelowercase."-";
					if(!in_array(sanitize_text_field($_POST['wprevpro_t_rtype_'.$typelowercasecheck]),$rtypearray)){
					array_push($rtypearray, sanitize_text_field($_POST['wprevpro_t_rtype_'.$typelowercasecheck]));
					}
				}
				//now check for manual_from_name 
				$typelowercaseboth = strtolower($temptype->type)."_".$temptype->from_name;
				$typelowercasebothcheck = str_replace(".","",$typelowercaseboth);
				if(isset($_POST['wprevpro_t_rtype_'.$typelowercasebothcheck])){
					if(!in_array(sanitize_text_field($_POST['wprevpro_t_rtype_'.$typelowercasebothcheck]),$rtypearray)){
					array_push($rtypearray, sanitize_text_field($_POST['wprevpro_t_rtype_'.$typelowercasebothcheck]));
					}
				}
			}
		}

		$rtypearrayjson = json_encode($rtypearray);
		//echo($rtypearrayjson);
		//$rpage = htmlentities($_POST['wprevpro_t_rpage']);
		if(!isset($_POST['wprevpro_t_rpage'])){
			$_POST['wprevpro_t_rpage']="";
		}
			$rpagearray = $_POST['wprevpro_t_rpage'];
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
		if($t_id==""){
			//insert
			$inserttemplate = $wpdb->insert( $table_name, $data, $format );
			if(!$inserttemplate){
				$dbmsg = '<div id="setting-error-wprevpro_message" class="updated settings-error notice is-dismissible"><p><strong>'.__('Error', 'wp-review-slider-pro').':</strong> '.__('Unable to save template. Try de-activating and re-activating the plugin on the Plugins page. That will force the database table to update.', 'wp-review-slider-pro').'</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">'.__('Dismiss this notice.', 'wp-review-slider-pro').'</span></button></div>';
			}
		} else {
			//update
			
			$updatetempquery = $wpdb->update($table_name, $data, array( 'id' => $t_id ), $format, array( '%d' ));

			if($updatetempquery>0){
				$dbmsg = '<div id="setting-error-wprevpro_message" class="updated settings-error notice is-dismissible"><p><strong>'.__('Template Updated!', 'wp-review-slider-pro').'</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">'.__('Dismiss this notice.', 'wp-review-slider-pro').'</span></button></div>';
			} else {
				$wpdb->show_errors();
				$wpdb->print_error();
				$dbmsg = '<div id="setting-error-wprevpro_message" class="updated settings-error notice is-dismissible"><p><strong>'.__('Error', 'wp-review-slider-pro').':</strong> '.__('Unable to update. Try de-activating and re-activating the plugin. That will force the database table to update.', 'wp-review-slider-pro').'</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">'.__('Dismiss this notice.', 'wp-review-slider-pro').'</span></button></div>';
			}
		}
		//$wpdb->show_errors();
		//$wpdb->print_error();
		//die();
		
	}
	
	//echo WPREV_PLUGIN_DIR;

	//Get list of all current forms--------------------------
	$currentforms = $wpdb->get_results("SELECT id, title, template_type, created_time_stamp, style,rtype,createslider FROM $table_name");
	//-------------------------------------------------------
	
	//check to see if reviews are in database
	//total number of rows
	$reviews_table_name = $wpdb->prefix . 'wpfb_reviews';
	$reviewtotalcount = $wpdb->get_var( 'SELECT COUNT(*) FROM '.$reviews_table_name );
	if($reviewtotalcount<1){
		$dbmsg = $dbmsg . '<div id="setting-error-wprevpro_message" class="updated settings-error notice is-dismissible">'.__('<p><strong>No reviews found. Please visit the Get Reviews page or manually add one on the <a href="?page=wp_pro-reviews">Review List</a> page. </strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>', 'wp-review-slider-pro').'</div>';
	}
	
	
?>

<div class="wrap wp_pro-settings" id="wp_pro_template_settings">
	<h1><img src="<?php echo plugin_dir_url( __FILE__ ) . 'logo.png?ti'.time(); ?>"></h1>
<?php 
include("tabmenu.php");

//query args for export and import
$url_tempdownload = admin_url( 'admin-post.php?action=print.csv' );



?>
<div class="wprevpro_margin10">
	<a id="wprevpro_helpicon_posts" class="wprevpro_btnicononly button dashicons-before dashicons-editor-help"></a>
	<a id="wprevpro_addnewtemplate" class="button dashicons-before dashicons-plus-alt"><?php _e('Add New Reviews Template', 'wp-review-slider-pro'); ?></a>
	<a href="<?php echo $url_tempdownload;?>" id="wprevpro_exporttemplates" class="button dashicons-before dashicons-download"><?php _e('Export Templates', 'wp-review-slider-pro'); ?></a>
	<a id="wprevpro_importtemplates" class="button dashicons-before dashicons-upload"><?php _e('Import Templates', 'wp-review-slider-pro'); ?></a>
</div>
<div class="wprevpro_margin10" id="importform" style='display:none;'>
	    <form  action="?page=wp_pro-templates_posts" method="post" name="upload_excel" enctype="multipart/form-data">
		<p><b><?php _e('Use this form to import previously exported Templates.', 'wp-review-slider-pro'); ?></b></p>
			<input type="file" name="file" id="file">
			</br>
			<button type="submit" id="submit" name="Import" class="button-primary" data-loading-text="Loading..."><?php _e('Import', 'wp-review-slider-pro'); ?></button>
        </form>
</div>

<?php
//display message
echo $dbmsg;
		$html .= '
		<table class="wp-list-table widefat bluerowbackground striped posts">
			<thead>
				<tr>
					<th scope="col" width="30px" class="manage-column">'.__('ID', 'wp-review-slider-pro').'</th>
					<th scope="col" class="manage-column">'.__('Title', 'wp-review-slider-pro').'</th>
					<th scope="col" width="150px" class="manage-column">'.__('Slider or Grid', 'wp-review-slider-pro').'</th>
					<th scope="col" width="170px" class="manage-column">'.__('Date Created', 'wp-review-slider-pro').'</th>
					<th scope="col" width="450px" class="manage-column">'.__('Action', 'wp-review-slider-pro').'</th>
				</tr>
				</thead>
			<tbody>';
			
	$haswidgettemplate = false;	//for hiding widget type, going to be phasing widget types out.
	$foundnormalslider = false;
	$foundslickslider = false;
	$atleastone = false;
	foreach ( $currentforms as $currentform ) 
	{
		$atleastone = true;
	//remove query args we just used,
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
		$sliderhtml = '';
		if($currentform->createslider=='no'){
			$sliderhtml = 'Grid';
		} else if ($currentform->createslider=='yes'){
			$foundnormalslider = true;
			$sliderhtml = 'Slider - Normal';
		} else {
			$sliderhtml = 'Slider - Advanced';
			$foundslickslider = true;
		}
		
		if($currentform->template_type=='widget'){
			$haswidgettemplate = true;
		}
			
		$html .= '<tr id="'.$currentform->id.'">
				<th scope="col" class=" manage-column">'.$currentform->id.'</th>
				<th scope="col" class=" manage-column"><b>'.$currentform->title.'</b></th>
				<th scope="col" class=" manage-column"><b>'.$sliderhtml.'</b></th>
				<th scope="col" class=" manage-column">'.date("F j, Y",$currentform->created_time_stamp) .'</th>
				<th scope="col" class="manage-column" templateid="'.$currentform->id.'" templatetype="'.$currentform->template_type.'"><a class="wprevpro_displayshortcode button button-primary dashicons-before dashicons-shortcode">'.__('Shortcode', 'wp-review-slider-pro').'</a> <a href="'.$url_tempeditbtn.'" class="button button-secondary dashicons-before dashicons-admin-generic">'.__('Edit', 'wp-review-slider-pro').'</a> <a href="'.$url_tempdelbtn.'" class="button button-secondary dashicons-before dashicons-trash">'.__('Delete', 'wp-review-slider-pro').'</a> <a href="'.$url_tempcopybtn.'" class="button button-secondary dashicons-before dashicons-admin-page">'.__('Copy', 'wp-fb-reviews').'</a> <a data-fid="'.$currentform->id.'" class="wprevpro_displaypreview button button-secondary dashicons-before dashicons-visibility">'.__('Preview', 'wp-review-slider-pro').'</a></</th>
			</tr>';
	}
	if(!$atleastone){
		$html .= '
				<tr>
					<th scope="col" width="30px" colspan="5" class="manage-column">'.__('Use the "Add New Reviews Template" button to create your first review template that you can use to display reviews on your site.', 'wp-review-slider-pro').'</th>
				</tr>';
	}
		$html .= '</tbody></table>';
			
 echo $html;	
 
 //update option to make sure we load js if we are using a slider
 update_option( 'wprev_slidejsload', '' );
 if($foundnormalslider && !$foundslickslider){
 	update_option( 'wprev_slidejsload', 'normal' );
 } else if(!$foundnormalslider && $foundslickslider){
	update_option( 'wprev_slidejsload', 'slick' );
 } else if($foundnormalslider && $foundslickslider){
	 update_option( 'wprev_slidejsload', 'both' );
 }


$numsemsg="";
$clearallmsg="";
$ctselhidemestyle="";
$ctseleted="";

//phasing out all new templates are post/page.
if($currenttemplate->template_type!= "widget"){
	$haswidgettemplate = false;
}

?>
<div class="wprevpro_margin10" id="wprevpro_new_template">
<form name="newtemplateform" id="newtemplateform" action="?page=wp_pro-templates_posts" method="post" onsubmit="return validateForm()">
	<table class="wprevpro_margin10 form-table ">
		<tbody>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Template Name:', 'wp-review-slider-pro'); ?>
				</th>
				<td>
					<input id="wprevpro_template_title" data-custom="custom" type="text" name="wprevpro_template_title" placeholder="" value="<?php echo $currenttemplate->title; ?>" required>
					<p class="description">
					<?php _e('Enter a name for this template.', 'wp-review-slider-pro'); ?>		</p>
				</td>
			</tr>
			<tr <?php if($haswidgettemplate==false){echo "style='display:none;'";} ?> class="wprevpro_row">
				<th scope="row">
					<?php _e('Choose Template Type:', 'wp-review-slider-pro'); ?>
				</th>
				<td><div id="divtemplatestyles">

					<input type="radio" name="wprevpro_template_type" id="wprevpro_template_type1-radio" value="post" checked="checked">
					<label for="wprevpro_template_type1-radio"><?php _e('Post or Page (Shortcode)', 'wp-review-slider-pro'); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

					<input type="radio" name="wprevpro_template_type" id="wprevpro_template_type2-radio" value="widget" <?php if($currenttemplate->template_type== "widget"){echo 'checked="checked"';}?>>
					<label for="wprevpro_template_type2-radio"><?php _e('Widget Area', 'wp-review-slider-pro'); ?></label>
					</div>
					<p class="description">
					<?php _e('Are you going to use this on a Page/Post or in a Widget area like your sidebar? Widget type can only display one column. <br>If you need more than one column use the Post type and then insert the shortcode in a text widget.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
			
			
			</tbody>
		</table>
<h2 class="nav-tab-wrapper">
	<span id="settingtab0" class="settingtab nav-tab nav-tab-active cursorpointer gotopage0">Template Style</span>
	<span id="settingtab1" class="settingtab nav-tab cursorpointer gotopage1">General Settings</span>
	<span id="settingtab2" class="settingtab nav-tab cursorpointer gotopage2" >Filter Settings</span>
	<span id="settingtab3" class="settingtab nav-tab cursorpointer gotopage3" >Header Options</span>
	<span id="settingtab4" class="settingtab nav-tab cursorpointer gotopage4" >More Settings</span>
</h2>
	<table id="settingtable0" class="wprevpro_margin10 form-table settingstable templatesettingstable">
		<tbody>
		
<tr class="wprevpro_row">
				<td>
					<div class="w3_wprs-row">
						  <div class="w3_wprs-col s6">
							<div class="w3_wprs-col s6">
								<div class="wprevpre_temp_label_row">
								<?php _e('Template Style:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row">
								<?php _e('Show Stars:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row">
								<?php _e('Star Color & Size:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row">
								<?php _e('Show Verified:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row starlocationdiv" <?php if($currenttemplate->style!='3'){echo "style='display:none;'";} ?>>
								<?php _e('Star Location:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row">
								<?php _e('Date:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row displayavatar">
								<?php _e('Display Avatar:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row">
								<?php _e('First Last Name:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row reviewtitle" <?php if($currenttemplate->style=='10'){echo "style='display:none;'";} ?>>
								<?php _e('Review Title:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row">
								<?php _e('Site Icon:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row divsiteiconchoose" <?php if($currenttemplate->facebook_icon!='cho'){echo "style=display:none;";} ?>>
								
								</div>
								<div class="wprevpre_temp_label_row">
								<?php _e('Corner Radius:', 'wp-review-slider-pro'); ?>
								</div>
								
								<div class="wprevpre_temp_label_row">
								<?php _e('Background Color 1:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row wprevpre_bgcolor2">
								<?php _e('Background Color 2:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row">
								<?php _e('Text Color 1:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row">
								<?php _e('Text Color 2:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row wprevpre_tcolor3">
								<?php _e('Text Color 3:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row">
								<?php _e('Border Color:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row">
								<?php _e('Font Size 1:', 'wp-review-slider-pro'); ?>
								</div>
								<div class="wprevpre_temp_label_row">
								<?php _e('Font Size 2:', 'wp-review-slider-pro'); ?>
								</div>
								
							</div>
							<div class="w3_wprs-col s6">
								<div class="wprevpre_temp_label_row">
									<select name="wprevpro_template_style" id="wprevpro_template_style">
									  <option value="1" <?php if($currenttemplate->style=='1' || $currenttemplate->style==""){echo "selected";} ?>><?php _e('Style', 'wp-review-slider-pro'); ?> 1</option>
									  <?php
									  if ($canusepremiumcode ) {
									  ?>
									  <option value="2" <?php if($currenttemplate->style=='2'){echo "selected";} ?>><?php _e('Style', 'wp-review-slider-pro'); ?> 2</option>
									  <option value="3" <?php if($currenttemplate->style=='3'){echo "selected";} ?>><?php _e('Style', 'wp-review-slider-pro'); ?> 3</option>
									  <option value="4" <?php if($currenttemplate->style=='4'){echo "selected";} ?>><?php _e('Style', 'wp-review-slider-pro'); ?> 4</option>
									  <option value="5" <?php if($currenttemplate->style=='5'){echo "selected";} ?>><?php _e('Style', 'wp-review-slider-pro'); ?> 5</option>
									  <option value="6" <?php if($currenttemplate->style=='6'){echo "selected";} ?>><?php _e('Style', 'wp-review-slider-pro'); ?> 6</option>
									  <option value="7" <?php if($currenttemplate->style=='7'){echo "selected";} ?>><?php _e('Style', 'wp-review-slider-pro'); ?> 7</option>
									  <option value="8" <?php if($currenttemplate->style=='8'){echo "selected";} ?>><?php _e('Style', 'wp-review-slider-pro'); ?> 8</option>
									  <option value="9" <?php if($currenttemplate->style=='9'){echo "selected";} ?>><?php _e('Style', 'wp-review-slider-pro'); ?> 9</option>
									  <option value="10" <?php if($currenttemplate->style=='10'){echo "selected";} ?>><?php _e('Style', 'wp-review-slider-pro'); ?> 10</option>
									  <option value="11" <?php if($currenttemplate->style=='11'){echo "selected";} ?>><?php _e('Style', 'wp-review-slider-pro'); ?> 11</option>
									  <option value="12" <?php if($currenttemplate->style=='12'){echo "selected";} ?>><?php _e('Style', 'wp-review-slider-pro'); ?> 12</option>
									  <option value="13" <?php if($currenttemplate->style=='13'){echo "selected";} ?>><?php _e('Style', 'wp-review-slider-pro'); ?> 13</option>
									  <?php
									  }
									  ?>
									</select>
									<?php
									  if ($canusepremiumcode ) {
									  ?>
									<a id="wprevpro_pre_choosestyle" class="button">Choose Style</a>
									<?php
									  }
									  ?>
									
								</div>
				<?php
				//echo $currenttemplate->template_misc;
				$template_misc_array = json_decode($currenttemplate->template_misc, true);
				if(!isset($template_misc_array['avataropt'])){
					$template_misc_array['avataropt']='yes';
				}
				if(!is_array($template_misc_array)){
					$template_misc_array=array();
				}
				if(!isset($template_misc_array['lastnameformat'])){
					$template_misc_array['lastnameformat']='';
				}
				if(!isset($template_misc_array['bradius'])){
					$template_misc_array['bradius']='0';
				}
				if(!isset($template_misc_array['bcolor'])){
					$template_misc_array['bcolor']='';
				}
				if(!isset($template_misc_array['bgcolor1'])){
					$template_misc_array['bgcolor1']='';
				}
				if(!isset($template_misc_array['bgcolor2'])){
					$template_misc_array['bgcolor2']='';
				}
				if(!isset($template_misc_array['tcolor1'])){
					$template_misc_array['tcolor1']='';
				}
				if(!isset($template_misc_array['tcolor2'])){
					$template_misc_array['tcolor2']='';
				}
				if(!isset($template_misc_array['tcolor3'])){
					$template_misc_array['tcolor3']='';
				}
				if(!isset($template_misc_array['dateformat'])){
					$template_misc_array['dateformat']='MM/DD/YYYY';
				}
				if(!isset($template_misc_array['lastnameformat'])){
					$template_misc_array['lastnameformat']='show';
				}
				if(!isset($template_misc_array['showtitle'])){
					$template_misc_array['showtitle']='show';
				}
				if(!isset($template_misc_array['starcolor'])){
					$template_misc_array['starcolor']='#FDD314';
				}
				if(!isset($template_misc_array['stariconfull'])){
					$template_misc_array['stariconfull']='wprsp-star';
					$template_misc_array['stariconempty']='wprsp-star-o';
				}
				if(!isset($template_misc_array['starlocation'])){
					$template_misc_array['starlocation']='1';
				}
				if(!isset($template_misc_array['default_avatar'])){
					$template_misc_array['default_avatar']='facebook';
				}
				//if(!isset($template_misc_array['length_type'])){
				//	$template_misc_array['length_type']="words";
				//}
				if(!isset($template_misc_array['icon_over_yelp'])){
					$template_misc_array['icon_over_yelp']="";
				}
				if(!isset($template_misc_array['icon_over_trip'])){
					$template_misc_array['icon_over_trip']="";
				}
				if(!isset($template_misc_array['load_more_porb'])){
					$template_misc_array['load_more_porb']="";
				}
				if(!isset($template_misc_array['header_text'])){
					$template_misc_array['header_text']="";
					$template_misc_array['header_text_tag']="h2";
				}
				if(!isset($template_misc_array['header_search'])){
					$template_misc_array['header_search']="";
					$template_misc_array['header_search_place']=__('Search Reviews', 'wp-review-slider-pro');
					$template_misc_array['header_sort']="";
					$template_misc_array['header_sort_place']=__('Sort By...', 'wp-review-slider-pro');
				}
				if(!isset($template_misc_array['header_tag'])){
					$template_misc_array['header_tag']="";
					$template_misc_array['header_tags']="";
				}
				if(!isset($template_misc_array['header_tag_search'])){
					$template_misc_array['header_tag_search']="";
				}
				if(!isset($template_misc_array['header_langcodes'])){
					$template_misc_array['header_langcodes']="";
					$template_misc_array['header_langcodes_place']=__('Filter By Language', 'wp-review-slider-pro');
					$template_misc_array['header_langcodes_list']="";
					$template_misc_array['header_rating']="";
					$template_misc_array['header_rating_place']=__('Filter By Rating', 'wp-review-slider-pro');
				}
				if(!isset($template_misc_array['header_filter_opt'])){
					$template_misc_array['header_filter_opt']="";
				}
				if(!isset($template_misc_array['tfont1'])){
					$template_misc_array['tfont1']="";
				}
				if(!isset($template_misc_array['tfont2'])){
					$template_misc_array['tfont2']="";
				}
				if(!isset($template_misc_array['choosetypes'])){
					$template_misc_array['choosetypes']=[];
				}
				if(!isset($template_misc_array['showstars'])){
					$template_misc_array['showstars']="";
				}
				if(!isset($template_misc_array['showdate'])){
					$template_misc_array['showdate']="";
				}
				if(!isset($template_misc_array['firstnameformat'])){
					$template_misc_array['firstnameformat']="";
				}
				if(!isset($template_misc_array['avatarsize'])){
					$template_misc_array['avatarsize']="";
				}
				if(!isset($template_misc_array['verified'])){
					$template_misc_array['verified']="";
				}
				if(!isset($template_misc_array['starsize'])){
					$template_misc_array['starsize']="18";
				}
				if(!isset($template_misc_array['iconsize'])){
					$template_misc_array['iconsize']="32";
				}
				if(!isset($template_misc_array['header_source'])){
					$template_misc_array['header_source']="";
					$template_misc_array['header_source_place']=__('Filter By Source', 'wp-review-slider-pro');
					$template_misc_array['header_rtypes'] = "";
				}
			if(!isset($template_misc_array['sli_infinite'])){
					$template_misc_array['sli_infinite']='';
			}
			if(!isset($template_misc_array['sli_slidestoscroll'])){
					$template_misc_array['sli_slidestoscroll']='';
			}
			if(!isset($template_misc_array['sli_avatarnav'])){
					$template_misc_array['sli_avatarnav']='';
			}
			if(!isset($template_misc_array['sli_centermode'])){
					$template_misc_array['sli_centermode']='';
			}
			if(!isset($template_misc_array['sli_centermode_padding'])){
					$template_misc_array['sli_centermode_padding']='60';
			}
			if(!isset($template_misc_array['template_margin_tb'])){
					$template_misc_array['template_margin_tb']='0';
					$template_misc_array['template_margin_lr']='0';
			}
			if(!isset($template_misc_array['template_margin_tb_m'])){
					$template_misc_array['template_margin_tb_m']='0';
					$template_misc_array['template_margin_lr_m']='0';
			}
			if(!isset($template_misc_array['screensize'])){
				$template_misc_array['screensize']='both';
			}
			if(!isset($template_misc_array['sliderarrowcolor'])){
				$template_misc_array['sliderarrowcolor']='';
				$template_misc_array['sliderdotcolor']='';
			}
			if(!isset($template_misc_array['inibgcolor'])){
				$template_misc_array['inibgcolor']='';
			}

				?>
								<div class="wprevpre_temp_label_row">
									<select name="wprevpro_template_misc_showstars" id="wprevpro_template_misc_showstars">
									  <option value="yes" <?php if($template_misc_array['showstars']=='yes'){echo "selected";} ?>><?php _e('Style1', 'wp-review-slider-pro'); ?></option>
									  <option value="yes2" <?php if($template_misc_array['showstars']=='yes2'){echo "selected";} ?>><?php _e('Style2', 'wp-review-slider-pro'); ?></option>
									  <option value="no" <?php if($template_misc_array['showstars']=='no'){echo "selected";} ?>><?php _e('No', 'wp-review-slider-pro'); ?></option>
									</select>
									<div class="rowstaricons">
										<span class="btnstaricon button " id="fullstaricon"><span class="svgicons svg-<?php echo $template_misc_array['stariconfull']; ?>"></span></span> <span class="btnstaricon button " id="emptystaricon"><span class="svgicons svg-<?php echo $template_misc_array['stariconempty']; ?>"></span></span>
										<input type="hidden" value="<?php echo $template_misc_array['stariconfull']; ?>" name="wprevpro_template_misc_stariconfull" id="wprevpro_template_misc_stariconfull" />
										<input type="hidden" value="<?php echo $template_misc_array['stariconempty']; ?>" name="wprevpro_template_misc_stariconempty" id="wprevpro_template_misc_stariconempty" />
									</div>
								
								</div>
								<div class="wprevpre_temp_label_row">
									<input type="text" value="<?php echo $template_misc_array['starcolor']; ?>" name="wprevpro_template_misc_starcolor" id="wprevpro_template_misc_starcolor" data-alpha-enabled="true" class="my-color-field" />
									<input id="wprevpro_template_misc_starsize" type="number" min="0" name="wprevpro_template_misc_starsize" placeholder="" value="<?php echo $template_misc_array['starsize']; ?>" style="width: 4em;min-width: 4em;vertical-align: top;height: 30px;margin-left: -5px;">
								</div>
								<div class="wprevpre_temp_label_row">
									<select name="wprevpro_template_misc_verified" id="wprevpro_template_misc_verified">
										<option value="no" <?php if($template_misc_array['verified']=='no' || $template_misc_array['verified']==''){echo "selected";} ?>><?php _e('No', 'wp-review-slider-pro'); ?></option>
										<option value="yes1" <?php if($template_misc_array['verified']=='yes1'){echo "selected";} ?>><?php _e('Location 1', 'wp-review-slider-pro'); ?></option>
										<option value="yes2" <?php if($template_misc_array['verified']=='yes2'){echo "selected";} ?>><?php _e('Location 2', 'wp-review-slider-pro'); ?></option>
									  
									</select>
								</div>
								<div class="wprevpre_temp_label_row starlocationdiv"  <?php if($currenttemplate->style!='3'){echo "style='display:none;'";} ?>>
									<select name="wprevpro_template_misc_starlocation" id="wprevpro_template_misc_starlocation">
									  <option value="1" <?php if($template_misc_array['starlocation']=='1'){echo "selected";} ?>><?php _e('Default', 'wp-review-slider-pro'); ?></option>
									  <option value="2" <?php if($template_misc_array['starlocation']=='2'){echo "selected";} ?>>Loc 1</option>
									</select>
								</div>
								<div class="wprevpre_temp_label_row">
									<select name="wprevpro_template_misc_dateformat" id="wprevpro_template_misc_dateformat">
									  <option value="MM/DD/YYYY" <?php if($template_misc_array['dateformat']=='MM/DD/YYYY'){echo "selected";} ?>>MM/DD/YYYY</option>
									  <option value="DD/MM/YY" <?php if($template_misc_array['dateformat']=='DD/MM/YY'){echo "selected";} ?>>DD/MM/YY</option>
									  <option value="DD/MM/YYYY" <?php if($template_misc_array['dateformat']=='DD/MM/YYYY'){echo "selected";} ?>>DD/MM/YYYY</option>
									  <option value="DD-MM-YYYY" <?php if($template_misc_array['dateformat']=='DD-MM-YYYY'){echo "selected";} ?>>DD-MM-YYYY</option>
									  <option value="YYYY-MM-DD" <?php if($template_misc_array['dateformat']=='YYYY-MM-DD'){echo "selected";} ?>>YYYY-MM-DD</option>
									  <option value="d M Y" <?php if($template_misc_array['dateformat']=='d M Y'){echo "selected";} ?>>DD Mmm YYYY</option>
									  <option value="M Y" <?php if($template_misc_array['dateformat']=='M Y'){echo "selected";} ?>>Mmm YYYY</option>
									  <option value="wpadmin" <?php if($template_misc_array['dateformat']=='wpadmin'){echo "selected";} ?>><?php _e('WP Admin', 'wp-review-slider-pro'); ?></option>
									  <option value="timesince" <?php if($template_misc_array['dateformat']=='timesince'){echo "selected";} ?>><?php _e('Time Since', 'wp-review-slider-pro'); ?></option>
									  <option value="hide" <?php if($template_misc_array['dateformat']=='hide'){echo "selected";} ?>><?php _e('Hide Date', 'wp-review-slider-pro'); ?></option>
									</select>
								</div>
								<div class="wprevpre_temp_label_row displayavatar">
									<select name="wprevpro_template_misc_avataropt" id="wprevpro_template_misc_avataropt">
									  <option value="show" <?php if($template_misc_array['avataropt']=='show'){echo "selected";} ?>><?php _e('Yes', 'wp-review-slider-pro'); ?></option>
									  <option value="hide" <?php if($template_misc_array['avataropt']=='hide'){echo "selected";} ?>><?php _e('No', 'wp-review-slider-pro'); ?></option>
									  <option value="mystery" <?php if($template_misc_array['avataropt']=='mystery'){echo "selected";} ?>><?php _e('Mystery', 'wp-review-slider-pro'); ?></option>
									  <option value="init" <?php if($template_misc_array['avataropt']=='init'){echo "selected";} ?>><?php _e('Initials', 'wp-review-slider-pro'); ?></option>
									</select>
									<input id="wprevpro_template_misc_avatarsize" type="number" min="0" name="wprevpro_template_misc_avatarsize" placeholder="" value="<?php echo $template_misc_array['avatarsize']; ?>" style="width: 4em;min-width: 4em;">
									<span id="spaninibgcolor" <?php if($template_misc_array['avataropt']!='init'){echo 'style="display:none;"';} ?> >
									<input type="text" value="<?php echo $template_misc_array['inibgcolor']; ?>" name="wprevpro_template_misc_inibgcolor" id="wprevpro_template_misc_inibgcolor" data-alpha-enabled="false" class="my-color-field" />
									</span>
									
								</div>

								<div class="wprevpre_temp_label_row firstlastnamerow">
									
									<select name="wprevpro_template_misc_firstname" id="wprevpro_template_misc_firstname">
									  <option value="show" <?php if($template_misc_array['firstnameformat']=='show' || $template_misc_array['firstnameformat']==''){echo "selected";} ?>><?php _e('Show', 'wp-review-slider-pro'); ?></option>
									  <option value="hide" <?php if($template_misc_array['firstnameformat']=='hide'){echo "selected";} ?>><?php _e('Hide', 'wp-review-slider-pro'); ?></option>
									  <option value="initial" <?php if($template_misc_array['firstnameformat']=='initial'){echo "selected";} ?>><?php _e('Initial', 'wp-review-slider-pro'); ?></option>
									</select>
									<select name="wprevpro_template_misc_lastname" id="wprevpro_template_misc_lastname">
									  <option value="show" <?php if($template_misc_array['lastnameformat']=='show'){echo "selected";} ?>><?php _e('Show', 'wp-review-slider-pro'); ?></option>
									  <option value="hide" <?php if($template_misc_array['lastnameformat']=='hide'){echo "selected";} ?>><?php _e('Hide', 'wp-review-slider-pro'); ?></option>
									  <option value="initial" <?php if($template_misc_array['lastnameformat']=='initial'){echo "selected";} ?>><?php _e('Initial', 'wp-review-slider-pro'); ?></option>
									</select>
								</div>
								<div class="wprevpre_temp_label_row reviewtitle" <?php if($currenttemplate->style=='10'){echo "style='display:none;'";} ?>>
									<select name="wprevpro_template_misc_showtitle" id="wprevpro_template_misc_showtitle">
									  <option value="show" <?php if($template_misc_array['showtitle']=='show'){echo "selected";} ?>><?php _e('Show', 'wp-review-slider-pro'); ?></option>
									  <option value="hide" <?php if($template_misc_array['showtitle']=='hide'){echo "selected";} ?>><?php _e('Hide', 'wp-review-slider-pro'); ?></option>
									</select>
								</div>
								<div class="wprevpre_temp_label_row ">
									<select name="wprevpro_t_facebook_icon" id="wprevpro_t_facebook_icon">
										<option value="no" <?php if($currenttemplate->facebook_icon=='no'){echo "selected";} ?>><?php _e('No', 'wp-review-slider-pro'); ?></option>
										<option value="yes" <?php if($currenttemplate->facebook_icon=='yes'){echo "selected";} ?>><?php _e('Yes', 'wp-review-slider-pro'); ?></option>
										<option value="cho" <?php if($currenttemplate->facebook_icon=='cho'){echo "selected";} ?>><?php _e('Choose', 'wp-review-slider-pro'); ?></option>
									</select>
									<input id="wprevpro_template_misc_iconsize" type="number" min="0" name="wprevpro_template_misc_iconsize" placeholder="" value="<?php echo $template_misc_array['iconsize']; ?>" style="width: 4em;min-width: 4em;">
								</div>
								<div class="wprevpre_temp_label_row divsiteiconchoose" <?php if($currenttemplate->facebook_icon!='cho'){echo "style=display:none;";} ?>>
								
									<select id="type_multiple_select" class="js-example-basic-multiple" name="wprevpro_choosetypes[]" multiple="multiple" style="width: 100%">
										<?php
										$reviews_table_type = $wpdb->prefix . 'wpfb_reviews';
										$tempquery = "select type from ".$reviews_table_type." group by type";
										$typearray = $wpdb->get_col($tempquery);
										for($x=0;$x<count($typearray);$x++)
										{
											$typelowercase = strtolower($typearray[$x]);
											//check if this is already selected
											$tempselected = '';
											if (in_array($typelowercase, $template_misc_array['choosetypes'])) {
												$tempselected = 'selected="selected"';
											}
											
											echo '<option value="'.$typelowercase.'" '.$tempselected.' >'.$typearray[$x].'</option>';
										}
										?>
									</select>
								</div>
								<div class="wprevpre_temp_label_row">

									<input id="wprevpro_template_misc_bradius" type="number" min="0" name="wprevpro_template_misc_bradius" placeholder="" value="<?php echo $template_misc_array['bradius']; ?>" style="width: 4em;min-width: 4em;">
	
								</div>

								<div class="wprevpre_temp_label_row">
									<input type="text" value="<?php echo $template_misc_array['bgcolor1']; ?>" name="wprevpro_template_misc_bgcolor1" id="wprevpro_template_misc_bgcolor1" data-alpha-enabled="true" class="my-color-field" />
								</div>
								<div class="wprevpre_temp_label_row wprevpre_bgcolor2">
									<input type="text" value="<?php echo $template_misc_array['bgcolor2']; ?>" name="wprevpro_template_misc_bgcolor2" id="wprevpro_template_misc_bgcolor2" data-alpha-enabled="true" class="my-color-field" />
								</div>
								<div class="wprevpre_temp_label_row">
									<input type="text" value="<?php echo $template_misc_array['tcolor1']; ?>" name="wprevpro_template_misc_tcolor1" id="wprevpro_template_misc_tcolor1" data-alpha-enabled="true" class="my-color-field" />
								</div>
								<div class="wprevpre_temp_label_row">
									<input type="text" value="<?php echo $template_misc_array['tcolor2']; ?>" name="wprevpro_template_misc_tcolor2" id="wprevpro_template_misc_tcolor2" data-alpha-enabled="true" class="my-color-field" />
								</div>
								<div class="wprevpre_temp_label_row wprevpre_tcolor3">
									<input type="text" value="<?php echo $template_misc_array['tcolor3']; ?>" name="wprevpro_template_misc_tcolor3" id="wprevpro_template_misc_tcolor3" data-alpha-enabled="true" class="my-color-field" />
								</div>
								<div class="wprevpre_temp_label_row">
									<input type="text" value="<?php echo $template_misc_array['bcolor']; ?>" name="wprevpro_template_misc_bcolor" id="wprevpro_template_misc_bcolor" data-alpha-enabled="true" class="my-color-field" />
								</div>
								<div class="wprevpre_temp_label_row">
									<input type="number" value="<?php echo $template_misc_array['tfont1']; ?>" style="width: 4em;min-width: 4em;" min="0" name="wprevpro_template_misc_tfont1" id="wprevpro_template_misc_tfont1" />px
								</div>
								<div class="wprevpre_temp_label_row">
									<input type="number" value="<?php echo $template_misc_array['tfont2']; ?>" style="width: 4em;min-width: 4em;" min="0" name="wprevpro_template_misc_tfont2" id="wprevpro_template_misc_tfont2" />px
								</div>
								
								
								<a id="wprevpro_pre_resetbtn" class="button"><?php _e('Reset Colors', 'wp-review-slider-pro'); ?></a>
													
							</div>
						  </div>
						  <div class="w3_wprs-col s6 wprevpro">
							<div class="w3_wprs-col" id="wprevpro_template_preview">
							</div>
							<?php 
							//preview willshow here
							?>
							<div class="rtsettings">
							<?php 
							if(!isset($template_misc_array['dropshadow'])){
								$template_misc_array['dropshadow']='';
							}
							if(!isset($template_misc_array['raisemouse'])){
								$template_misc_array['raisemouse']='';
							}
							if(!isset($template_misc_array['zoommouse'])){
								$template_misc_array['zoommouse']='';
							}
							//print_r($template_misc_array);
							?>
								<div class="wprevpre_temp_label_row_checkboxes">
								<input type="checkbox" name="wprevpro_t_dropshadow" id="wprevpro_t_dropshadow" value="yes" <?php if($template_misc_array['dropshadow']=='yes'){echo "checked";} ?>>
								<label for="wprevpro_t_dropshadow"> Drop Shadow</label>
								</div>
								
								<div class="wprevpre_temp_label_row_checkboxes">
								<input type="checkbox" name="wprevpro_t_raisemouse" id="wprevpro_t_raisemouse" value="yes" <?php if($template_misc_array['raisemouse']=='yes'){echo "checked";} ?>>
								<label for="wprevpro_t_raisemouse"> Raise on Mouse-Over</label>
								</div>
								
								<div class="wprevpre_temp_label_row_checkboxes">
								<input type="checkbox" name="wprevpro_t_zoommouse" id="wprevpro_t_zoommouse" value="yes" <?php if($template_misc_array['zoommouse']=='yes'){echo "checked";} ?>>
								<label for="wprevpro_t_zoommouse"> Zoom on Mouse-Over</label>
								</div>


								<div class="" id="wprevpro_custom_css">
									<div>
									<?php _e('Custom CSS:', 'wp-review-slider-pro'); ?>
									</div>

									<textarea name="wprevpro_template_css" id="wprevpro_template_css" cols="55" rows="6"><?php echo stripslashes($currenttemplate->template_css); ?></textarea>
									<p class="description">
									<?php _e('Enter custom CSS code to change the look even more.', 'wp-review-slider-pro');
									?><br><?php
									_e('<a href="https://wpreviewslider.com/wp-content/uploads/2022/04/CSS_example.mp4" target="_blank">Video Example</a>', 'wp-review-slider-pro'); ?>
									</p>

								</div>
							</div>
							
						  </div>
						 
					</div>


				</td>
			</tr>
		<tr class="wprevpro_row">
				<th scope="row" colspan="2">
				<span  class="button button-secondary dashicons-before dashicons-arrow-right-after gotopage1">Next</span>
				</th>
			</tr>

		</tbody>
	</table>
		
	<table id="settingtable1" class="wprevpro_margin10 form-table settingstable templatesettingstable" style="display:none;">
		<tbody>
		<tr class="wprevpro_row nopaddingtd">
				<th scope="row">
				</th>
				<td>&nbsp;
				</td>
			</tr>
						<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Number of Reviews', 'wp-review-slider-pro'); ?><a class="wprevpro_helpicon_p wprevpro_btnicononlyhelp dashicons-before dashicons-editor-help"></a>
				</th>
				<td><div class="divtemplatestyles">
					<label for="wprevpro_t_display_num"><?php _e('How many per a row?', 'wp-review-slider-pro'); ?></label>
					<select name="wprevpro_t_display_num" id="wprevpro_t_display_num">
					  <option value="1" <?php if($currenttemplate->display_num==1){echo "selected";} ?>>1</option>
					  <option value="2" <?php if($currenttemplate->display_num==2){echo "selected";} ?>>2</option>
					  <option value="3" <?php if($currenttemplate->display_num==3 || $currenttemplate->display_num==""){echo "selected";} ?>>3</option>
					  <option value="4" <?php if($currenttemplate->display_num==4){echo "selected";} ?>>4</option>
					  <option value="5" <?php if($currenttemplate->display_num==5){echo "selected";} ?>>5</option>
					  <option value="6" <?php if($currenttemplate->display_num==6){echo "selected";} ?>>6</option>
				</select>
					
					<label for="wprevpro_t_display_num_rows"><?php _e('How many total rows?', 'wp-review-slider-pro'); ?></label>
					<input id="wprevpro_t_display_num_rows" type="number" min="1" name="wprevpro_t_display_num_rows" placeholder="" value="<?php if($currenttemplate->display_num_rows>0){echo $currenttemplate->display_num_rows;} else {echo "1";}?>" style="width: 4em;min-width: 4em;">
					&nbsp;&nbsp;&nbsp;&nbsp;
					<label for="wprevpro_t_display_masonry"><?php _e('Masonry style?', 'wp-review-slider-pro'); ?></label>
					<select name="wprevpro_t_display_masonry" id="wprevpro_t_display_masonry">
					  <option value="no" <?php if($currenttemplate->display_masonry=='no'  || $currenttemplate->display_masonry==""){echo "selected";} ?>>No</option>
					  <option value="yes" <?php if($currenttemplate->display_masonry=='yes'){echo "selected";} ?>>Yes</option>
					</select>
					
					</div>
					<p class="description">
					<?php _e('How many reviews to display on the page at a time. Masonry Style allows you to create a Pinterest style view.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
			
<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Slider Or Grid', 'wp-review-slider-pro'); ?><a class="wprevpro_helpicon_p wprevpro_btnicononlyhelp dashicons-before dashicons-editor-help"></a>
				</th>
				<td>
					<div class="divtemplatestyles">
						<label for="wprevpro_t_createslider"></label>
						<select name="wprevpro_t_createslider" id="wprevpro_t_createslider">
							<option value="no" <?php if($currenttemplate->createslider=="no"){echo "selected";} ?>><?php _e('Grid', 'wp-review-slider-pro'); ?></option>
							<option value="yes" <?php if($currenttemplate->createslider=="yes"){echo "selected";} ?>><?php _e('Slider - Normal', 'wp-review-slider-pro'); ?></option>
							<?php
							
							?>
							<option value="sli" <?php if($currenttemplate->createslider=="sli"){echo "selected";} ?>><?php _e('Slider - Advanced', 'wp-review-slider-pro'); ?></option>
							<?php
							
							?>
						</select>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label id="wprevpro_t_numslides_label" for="wprevpro_t_display_num_rows" <?php if($currenttemplate->createslider=="no" || $currenttemplate->createslider==""){echo "style='display:none;'";} ?>><?php _e('How many total slides?', 'wp-review-slider-pro'); ?></label>
						<select name="wprevpro_t_numslides" id="wprevpro_t_numslides" <?php if($currenttemplate->createslider=="no" || $currenttemplate->createslider==""){echo "style='display:none;'";} ?>>
							<option value="2" <?php if($currenttemplate->numslides=="2"){echo "selected";} ?>>2</option>
							<option value="3" <?php if($currenttemplate->numslides=="3"){echo "selected";} ?>>3</option>
							<option value="4" <?php if($currenttemplate->numslides=="4"){echo "selected";} ?>>4</option>
							<option value="5" <?php if($currenttemplate->numslides=="5"){echo "selected";} ?>>5</option>
							<option value="6" <?php if($currenttemplate->numslides=="6"){echo "selected";} ?>>6</option>
							<option value="7" <?php if($currenttemplate->numslides=="7"){echo "selected";} ?>>7</option>
							<option value="8" <?php if($currenttemplate->numslides=="8"){echo "selected";} ?>>8</option>
							<option value="9" <?php if($currenttemplate->numslides=="9"){echo "selected";} ?>>9</option>
							<option value="10" <?php if($currenttemplate->numslides=="10"){echo "selected";} ?>>10</option>
							<option value="15" <?php if($currenttemplate->numslides=="15"){echo "selected";} ?>>15</option>
							<option value="20" <?php if($currenttemplate->numslides=="20"){echo "selected";} ?>>20</option>
							<option value="25" <?php if($currenttemplate->numslides=="25"){echo "selected";} ?>>25</option>
							<option value="30" <?php if($currenttemplate->numslides=="30"){echo "selected";} ?>>30</option>
							<option value="40" <?php if($currenttemplate->numslides=="40"){echo "selected";} ?>>40</option>
							<option value="50" <?php if($currenttemplate->numslides=="50"){echo "selected";} ?>>50</option>
							<option value="75" <?php if($currenttemplate->numslides=="75"){echo "selected";} ?>>75</option>
							<option value="100" <?php if($currenttemplate->numslides=="100"){echo "selected";} ?>>100</option>
							<option value="150" <?php if($currenttemplate->numslides=="150"){echo "selected";} ?>>150</option>
							<option value="200" <?php if($currenttemplate->numslides=="200"){echo "selected";} ?>>200</option>
						</select>
					
					</div>
					<p class="description">
					<?php _e('Allows you to create a slide show with your reviews. One slide equals one page of reviews.', 'wp-review-slider-pro'); ?></p>
					<div class="wprevpro_messagebox slickdivpluswidget" style='display:none;'><p>The advanced slider only works on Post/Page template type. Please change it above.</p></div>
				</td>
			</tr>

			<tr class="wprevpro_row"  id="slidersettingsrow" <?php if($currenttemplate->createslider=="no"){echo "style='display:none;'";} ?>>
				<th scope="row">
					<?php 
					_e('Slider Settings:', 'wp-review-slider-pro'); 
					?>
				</th>
				<td>
					<div class="w3_wprs-row slidersettingsdivs">
					<div class="w3_wprs-col s12">
						  <div class="w3_wprs-col s4 slidersettingsdivtoprow"><?php _e('Autoplay Slides:', 'wp-review-slider-pro'); ?></div>
						  <div class="w3_wprs-col s8 ">
							<input type="radio" name="wprevpro_sliderautoplay" id="wprevpro_sliderautoplay1-radio" value="no" checked="checked">
							<label for="wprevpro_sliderautoplay1-radio"><?php _e('No', 'wp-review-slider-pro'); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" name="wprevpro_sliderautoplay" id="wprevpro_sliderautoplay2-radio" value="yes" <?php if($currenttemplate->sliderautoplay== "yes"){echo 'checked="checked"';}?>>
							<label for="wprevpro_sliderautoplay2-radio"><?php _e('Yes', 'wp-review-slider-pro'); ?></label>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<label class="timedelay <?php if($currenttemplate->sliderautoplay!="yes"){echo "wprevhiddenClass";} ?>" for="wprevpro_t_createslider"><?php _e('Time delay between slides:', 'wp-review-slider-pro'); ?></label>
							<select class="timedelay <?php if($currenttemplate->sliderautoplay!="yes"){echo "wprevhiddenClass";} ?>" name="wprevpro_t_sliderdelay" id="wprevpro_t_sliderdelay">
							<option class="slickdiv" <?php if($currenttemplate->createslider=="yes"){echo 'style="display: none;"';} ?> value="0" <?php if($currenttemplate->sliderdelay=="0"){echo "selected";} ?>><?php _e('0 sec', 'wp-review-slider-pro'); ?></option>
								<option value="1" <?php if($currenttemplate->sliderdelay=="1"){echo "selected";} ?>><?php _e('1 sec', 'wp-review-slider-pro'); ?></option>
								<option value="3" <?php if($currenttemplate->sliderdelay=="3" || $currenttemplate->sliderdelay==""){echo "selected";} ?>><?php _e('3 sec', 'wp-review-slider-pro'); ?></option>
								<option value="5" <?php if($currenttemplate->sliderdelay=="5"){echo "selected";} ?>><?php _e('5 sec', 'wp-review-slider-pro'); ?></option>
								<option value="7" <?php if($currenttemplate->sliderdelay=="7"){echo "selected";} ?>><?php _e('7 sec', 'wp-review-slider-pro'); ?></option>
								<option value="9" <?php if($currenttemplate->sliderdelay=="9"){echo "selected";} ?>><?php _e('9 sec', 'wp-review-slider-pro'); ?></option>
								<option value="11" <?php if($currenttemplate->sliderdelay=="11"){echo "selected";} ?>><?php _e('11 sec', 'wp-review-slider-pro'); ?></option>
								<option value="13" <?php if($currenttemplate->sliderdelay=="13"){echo "selected";} ?>><?php _e('13 sec', 'wp-review-slider-pro'); ?></option>
								<option value="15" <?php if($currenttemplate->sliderdelay=="15"){echo "selected";} ?>><?php _e('15 sec', 'wp-review-slider-pro'); ?></option>
								<option value="20" <?php if($currenttemplate->sliderdelay=="20"){echo "selected";} ?>><?php _e('20 sec', 'wp-review-slider-pro'); ?></option>
								<option value="25" <?php if($currenttemplate->sliderdelay=="25"){echo "selected";} ?>><?php _e('25 sec', 'wp-review-slider-pro'); ?></option>
							</select>
						</div>
						</div>
						<div class="w3_wprs-col s12">
						<div class="w3_wprs-col s4 slidersettingsdiv"><?php _e('Slide Animation:', 'wp-review-slider-pro'); ?></div>
						<div class="w3_wprs-col s8 slidersettingsdivrt">
							<input type="radio" name="wprevpro_sliderdirection" id="wprevpro_sliderdirection1-radio" value="horizontal" checked="checked">
							<label for="wprevpro_sliderdirection1-radio"><?php _e('Horizontal', 'wp-review-slider-pro'); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" name="wprevpro_sliderdirection" id="wprevpro_sliderdirection3-radio" value="fade" <?php if($currenttemplate->sliderdirection== "fade"){echo 'checked="checked"';}?>>
							<label for="wprevpro_sliderdirection3-radio"><?php _e('Fade', 'wp-review-slider-pro'); ?></label>
						</div>
						</div>
						<div class="w3_wprs-col s12">
						<div class="w3_wprs-col s4 slidersettingsdiv"><?php _e('Show Navigation Arrows:', 'wp-review-slider-pro'); ?></div>
						<div class="w3_wprs-col s8 slidersettingsdivrt">
							<input type="radio" name="wprevpro_sliderarrows" id="wprevpro_sliderarrows1-radio" value="yes" checked="checked">
							<label for="wprevpro_sliderarrows1-radio"><?php _e('Yes', 'wp-review-slider-pro'); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" name="wprevpro_sliderarrows" id="wprevpro_sliderarrows3-radio" value="des" <?php if($currenttemplate->sliderarrows== "des"){echo 'checked="checked"';}?>>
							<label for="wprevpro_sliderarrows3-radio"><?php _e('Desktop Only', 'wp-review-slider-pro'); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" name="wprevpro_sliderarrows" id="wprevpro_sliderarrows2-radio" value="no" <?php if($currenttemplate->sliderarrows== "no"){echo 'checked="checked"';}?>>
							<label for="wprevpro_sliderarrows2-radio"><?php _e('No', 'wp-review-slider-pro'); ?></label>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" data-alpha="true" value="<?php echo $template_misc_array['sliderarrowcolor']; ?>" name="wprevpro_template_misc_sliderarrowcolor" id="wprevpro_template_misc_sliderarrowcolor" data-alpha-enabled="true" class="my-color-field" />
						</div>
						</div>
						<div class="w3_wprs-col s12">
						<div class="w3_wprs-col s4 slidersettingsdiv"><?php _e('Show Navigation Dots:', 'wp-review-slider-pro'); ?></div>
						<div class="w3_wprs-col s8 slidersettingsdivrt">
							<input type="radio" name="wprevpro_sliderdots" id="wprevpro_sliderdots1-radio" value="yes" checked="checked">
							<label for="wprevpro_sliderdots1-radio"><?php _e('Yes', 'wp-review-slider-pro'); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							
							<input type="radio" name="wprevpro_sliderdots" id="wprevpro_sliderdots3-radio" value="des" <?php if($currenttemplate->sliderdots== "des"){echo 'checked="checked"';}?>>
							<label for="wprevpro_sliderdots3-radio"><?php _e('Desktop Only', 'wp-review-slider-pro'); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							
							<input type="radio" name="wprevpro_sliderdots" id="wprevpro_sliderdots2-radio" value="no" <?php if($currenttemplate->sliderdots== "no"){echo 'checked="checked"';}?>>
							<label for="wprevpro_sliderdots2-radio"><?php _e('No', 'wp-review-slider-pro'); ?></label>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" data-alpha="true" value="<?php echo $template_misc_array['sliderdotcolor']; ?>" name="wprevpro_template_misc_sliderdotcolor" id="wprevpro_template_misc_sliderarrowcolor" data-alpha-enabled="true" class="my-color-field" />
						</div>
						</div>
						<div class="w3_wprs-col s12">
						<div class="w3_wprs-col s4 slidersettingsdiv"><?php _e('Change Height of Each Slide:', 'wp-review-slider-pro'); ?></div>
						<div class="w3_wprs-col s8 slidersettingsdivrt">
							<input type="radio" name="wprevpro_sliderheight" id="wprevpro_sliderheight2-radio" value="no" checked="checked">
							<label for="wprevpro_sliderheight2-radio"><?php _e('No', 'wp-review-slider-pro'); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" name="wprevpro_sliderheight" id="wprevpro_sliderheight1-radio" value="yes" <?php if($currenttemplate->sliderheight== "yes"){echo 'checked="checked"';}?>>
							<label for="wprevpro_sliderheight1-radio"><?php _e('Yes', 'wp-review-slider-pro'); ?></label>
						</div>
						</div>
						<div class="w3_wprs-col s12">
						<div class="w3_wprs-col s4 slidersettingsdiv"><?php _e('Animation Speed:', 'wp-review-slider-pro'); ?></div>
						<div class="w3_wprs-col s8 slidersettingsdivrt">
							<input type="text" name="wprevpro_t_sliderspeed" id="wprevpro_t_sliderspeed" style="width: 4em;min-width: 4em;" value="<?php if($currenttemplate->sliderspeed>0){echo $currenttemplate->sliderspeed;} else { echo '750';} ?>">
							<label for="wprevpro_t_sliderspeed"><?php _e('How long (in milliseconds) the slider should animate between slides.', 'wp-review-slider-pro'); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						</div>
						</div>
						<div class="w3_wprs-col s12 onepermobilerow" <?php if($currenttemplate->createslider=="sli"){echo 'style="display: none;"';} ?>>
							<div class="w3_wprs-col s4 slidersettingsdiv"><?php _e('Mobile Slide View:', 'wp-review-slider-pro'); ?></div>
							<div class="w3_wprs-col s8 slidersettingsdivrt">
								<input type="radio" name="wprevpro_slidermobileview" id="wprevpro_slidermobile2-radio" value="stack" checked="checked">
								<label for="wprevpro_slidermobile2-radio"><?php _e('Stack Reviews', 'wp-review-slider-pro'); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="radio" name="wprevpro_slidermobileview" id="wprevpro_slidermobile1-radio" value="one" <?php if($currenttemplate->slidermobileview== "one"){echo 'checked="checked"';}?>>
								<label for="wprevpro_slidermobile1-radio"><?php _e('One Review per Slide', 'wp-review-slider-pro'); ?></label>
							</div>
						</div>
						
			<?php

			?>						
						<div class="w3_wprs-col s12 slickdiv" <?php if($currenttemplate->createslider=="yes"){echo 'style="display: none;"';} ?>>
							<div class="w3_wprs-col s4 slidersettingsdiv"><?php _e('Infinite Scroll:', 'wp-review-slider-pro'); ?></div>
							<div class="w3_wprs-col s8 slidersettingsdivrt">
								<input type="checkbox" id="wprevpro_sli_infinite" name="wprevpro_sli_infinite" value="yes" <?php if($template_misc_array['sli_infinite']=="yes"){echo 'checked';} ?>>
								<label for="wprevpro_slidermobile2-radio"></label>
								<a id="wprevpro_helpicon_sli_infinite" class="wprevpro_btnicononlyhelp dashicons-before dashicons-editor-help"></a>
							</div>
						</div>
						<div class="w3_wprs-col s12 slickdiv" <?php if($currenttemplate->createslider=="yes"){echo 'style="display: none;"';} ?>>
							<div class="w3_wprs-col s4 slidersettingsdiv"><?php _e('Scroll One Review:', 'wp-review-slider-pro'); ?></div>
							<div class="w3_wprs-col s8 slidersettingsdivrt">
								<input type="checkbox" id="wprevpro_sli_slidestoscroll" name="wprevpro_sli_slidestoscroll" value="yes" <?php if($template_misc_array['sli_slidestoscroll']=="yes"){echo 'checked';} ?>>
								<label for="wprevpro_slidermobile2-radio"></label>
								<a id="wprevpro_helpicon_sli_onereview" class="wprevpro_btnicononlyhelp dashicons-before dashicons-editor-help"></a>
							</div>
						</div>
						<div class="w3_wprs-col s12 slickdiv" <?php if($currenttemplate->createslider=="yes"){echo 'style="display: none;"';} ?>>
							<div class="w3_wprs-col s4 slidersettingsdiv"><?php _e('Center Mode:', 'wp-review-slider-pro'); ?></div>
							<div class="w3_wprs-col s8 slidersettingsdivrt">
								<input type="checkbox" id="wprevpro_sli_centermode" name="wprevpro_sli_centermode" value="yes" <?php if($template_misc_array['sli_centermode']=="yes"){echo 'checked';} ?>>
								<label for="wprevpro_slidermobile2-radio"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<label for="wprevpro_slidermobile1-radio"><?php _e('Center Padding:', 'wp-review-slider-pro'); ?></label>
								<input type="number" style="width: 4em;min-width: 4em;" name="wprevpro_sli_centermode_padding" id="wprevpro_sli_centermode_padding-radio" value="<?php echo $template_misc_array['sli_centermode_padding']; ?>">
								<a id="wprevpro_helpicon_sli_centermode" class="wprevpro_btnicononlyhelp dashicons-before dashicons-editor-help"></a>
							</div>
						</div>
						<div class="w3_wprs-col s12 slickdiv" <?php if($currenttemplate->createslider=="yes"){echo 'style="display: none;"';} ?>>
							<div class="w3_wprs-col s4 slidersettingsdiv"><?php _e('Avatar Navigation:', 'wp-review-slider-pro'); ?></div>
							<div class="w3_wprs-col s8 slidersettingsdivrt">
								<input type="checkbox" id="wprevpro_sli_avatarnav" name="wprevpro_sli_avatarnav" value="yes" <?php if($template_misc_array['sli_avatarnav']=="yes"){echo 'checked';} ?>>
								<label for="wprevpro_slidermobile2-radio"></label>
								<a id="wprevpro_helpicon_sli_avatar" class="wprevpro_btnicononlyhelp dashicons-before dashicons-editor-help"></a>
							</div>
						</div>

					</div>
					
				</td>
			</tr>			
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Load More', 'wp-review-slider-pro'); ?><a class="wprevpro_helpicon_p wprevpro_btnicononlyhelp dashicons-before dashicons-editor-help"></a>
				</th>
				<td><div class="">
					<label for="wprevpro_t_load_more"></label>
					<select name="wprevpro_t_load_more" id="wprevpro_t_load_more" class="mt2">
						<option value="no" <?php if($currenttemplate->load_more=='no'){echo "selected";} ?>><?php _e('No', 'wp-review-slider-pro'); ?></option>
						<option value="yes" <?php if($currenttemplate->load_more=='yes'){echo "selected";} ?>><?php _e('Yes', 'wp-review-slider-pro'); ?></option>
					</select>&nbsp;&nbsp;
					<span id='paginationgrid' <?php if($currenttemplate->createslider!="no"){echo "style='display:none;'";} ?>>
					<label for="wprevpro_t_load_more_porb"><?php _e('Display As:', 'wp-review-slider-pro'); ?></label>
					<select name="wprevpro_t_load_more_porb" id="wprevpro_t_load_more_porb" class="mt2">
						<option value="" <?php if($template_misc_array['load_more_porb']==''){echo "selected";} ?>><?php _e('Button', 'wp-review-slider-pro'); ?></option>
						<option value="pagenums" <?php if($template_misc_array['load_more_porb']=='pagenums'){echo "selected";} ?>><?php _e('Page Numbers', 'wp-review-slider-pro'); ?></option>
						<option value="scroll" <?php if($template_misc_array['load_more_porb']=='scroll'){echo "selected";} ?>><?php _e('Endless Scroll', 'wp-review-slider-pro'); ?></option>
					</select>
					<a id="wprevpro_btn_paginationstyle" <?php if($template_misc_array['load_more_porb']=='scroll'){echo "style='display:none;'";}  ?> class="button">Modify Style</a>
					<label <?php if($template_misc_array['load_more_porb']=='pagenums' || $template_misc_array['load_more_porb']=='scroll'){echo "style='display:none;'";}  ?> for="wprevpro_t_load_more_text" class='lmt'>&nbsp;&nbsp;<?php _e('Load More Text:', 'wp-review-slider-pro'); ?></label>
					<input <?php if($template_misc_array['load_more_porb']=='pagenums' || $template_misc_array['load_more_porb']=='scroll'){echo "style='display:none;'";}  ?> class='lmt' id="wprevpro_t_load_more_text" type="text" name="wprevpro_t_load_more_text" placeholder="Load More" value="<?php if($currenttemplate->load_more_text!=''){echo $currenttemplate->load_more_text;} else {_e('Load More', 'wp-review-slider-pro');}?>" style="width: 6em">
					</span>

					</div>
					
					<p id="desc_grid" class="description" <?php if($currenttemplate->createslider!="no"){echo "style='display:none;'";}  ?> >
					<?php _e('Adds a button or pagination bar  below the reviews that will load more when clicked. "Endless Scroll" will auto load on page scroll.', 'wp-review-slider-pro'); ?></p>
					<p id="desc_slide" class="description" <?php if($currenttemplate->createslider=="no"){echo "style='display:none;'";}  ?>>
					<?php _e('More reviews are loaded automatically when the last slide appears.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>			
			
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Display Order', 'wp-review-slider-pro'); ?><a class="wprevpro_helpicon_p wprevpro_btnicononlyhelp dashicons-before dashicons-editor-help"></a>
				</th>
				<td>
					<select name="wprevpro_t_display_order" id="wprevpro_t_display_order">
						<option value="random" <?php if($currenttemplate->display_order=="random"){echo "selected";} ?>><?php _e('Random', 'wp-review-slider-pro'); ?></option>
						<option value="newest" <?php if($currenttemplate->display_order=="newest"){echo "selected";} ?>><?php _e('Newest', 'wp-review-slider-pro'); ?></option>
						<option value="oldest" <?php if($currenttemplate->display_order=="oldest"){echo "selected";} ?>><?php _e('Oldest', 'wp-review-slider-pro'); ?></option>
						<option value="highest" <?php if($currenttemplate->display_order=="highest"){echo "selected";} ?>><?php _e('Highest Rated', 'wp-review-slider-pro'); ?></option>
						<option value="lowest" <?php if($currenttemplate->display_order=="lowest"){echo "selected";} ?>><?php _e('Lowest Rated', 'wp-review-slider-pro'); ?></option>
						<option value="longest" <?php if($currenttemplate->display_order=="longest"){echo "selected";} ?>><?php _e('Longest', 'wp-review-slider-pro'); ?></option>
						<option value="shortest" <?php if($currenttemplate->display_order=="shortest"){echo "selected";} ?>><?php _e('Shortest', 'wp-review-slider-pro'); ?></option>
						<option value="sortweight" <?php if($currenttemplate->display_order=="sortweight"){echo "selected";} ?>><?php _e('Sort Weight', 'wp-review-slider-pro'); ?></option>
					</select>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<?php
					if(!isset($currenttemplate->display_order_second)){
						$currenttemplate->display_order_second='newest';
					}
					?>
					<span id='span_display_order_second' <?php if($currenttemplate->display_order=="random"){echo "style='display:none;'";} ?>>
					<label for="wprevpro_t_display_order_second"><?php _e('Secondary Sort Order:', 'wp-review-slider-pro'); ?></label>
						<select name="wprevpro_t_display_order_second" id="wprevpro_t_display_order_second">
							<option value="random" <?php if($currenttemplate->display_order_second=="random"){echo "selected";} ?>><?php _e('Random', 'wp-review-slider-pro'); ?></option>
							<option value="newest" <?php if($currenttemplate->display_order_second=="newest"){echo "selected";} ?>><?php _e('Newest', 'wp-review-slider-pro'); ?></option>
							<option value="oldest" <?php if($currenttemplate->display_order_second=="oldest"){echo "selected";} ?>><?php _e('Oldest', 'wp-review-slider-pro'); ?></option>
							<option value="highest" <?php if($currenttemplate->display_order_second=="highest"){echo "selected";} ?>><?php _e('Highest Rated', 'wp-review-slider-pro'); ?></option>
							<option value="lowest" <?php if($currenttemplate->display_order_second=="lowest"){echo "selected";} ?>><?php _e('Lowest Rated', 'wp-review-slider-pro'); ?></option>
							<option value="longest" <?php if($currenttemplate->display_order_second=="longest"){echo "selected";} ?>><?php _e('Longest', 'wp-review-slider-pro'); ?></option>
							<option value="shortest" <?php if($currenttemplate->display_order_second=="shortest"){echo "selected";} ?>><?php _e('Shortest', 'wp-review-slider-pro'); ?></option>
							<option value="sortweight" <?php if($currenttemplate->display_order_second=="sortweight"){echo "selected";} ?>><?php _e('Sort Weight', 'wp-review-slider-pro'); ?></option>
						</select>
					</span>
					<span id='span_display_order_limit' <?php if($currenttemplate->display_order!="random"){echo "style='display:none;'";} ?>>
					<label for="wprevpro_t_display_order_limit"><?php _e('Limit them to the past:', 'wp-review-slider-pro'); ?></label>
						<select name="wprevpro_t_display_order_limit" id="wprevpro_t_display_order_limit">
							<option value="1" <?php if($currenttemplate->display_order_limit=="1"){echo "selected";} ?>>1 <?php _e('month', 'wp-review-slider-pro'); ?></option>
							<option value="2" <?php if($currenttemplate->display_order_limit=="2"){echo "selected";} ?>>2 <?php _e('months', 'wp-review-slider-pro'); ?></option>
							<option value="3" <?php if($currenttemplate->display_order_limit=="3"){echo "selected";} ?>>3 <?php _e('months', 'wp-review-slider-pro'); ?></option>
							<option value="4" <?php if($currenttemplate->display_order_limit=="4"){echo "selected";} ?>>4 <?php _e('months', 'wp-review-slider-pro'); ?></option>
							<option value="5" <?php if($currenttemplate->display_order_limit=="5"){echo "selected";} ?>>5 <?php _e('months', 'wp-review-slider-pro'); ?></option>
							<option value="6" <?php if($currenttemplate->display_order_limit=="6"){echo "selected";} ?>>6 <?php _e('months', 'wp-review-slider-pro'); ?></option>
							<option value="7" <?php if($currenttemplate->display_order_limit=="7"){echo "selected";} ?>>7 <?php _e('months', 'wp-review-slider-pro'); ?></option>
							<option value="8" <?php if($currenttemplate->display_order_limit=="8"){echo "selected";} ?>>8 <?php _e('months', 'wp-review-slider-pro'); ?></option>
							<option value="9" <?php if($currenttemplate->display_order_limit=="9"){echo "selected";} ?>>9 <?php _e('months', 'wp-review-slider-pro'); ?></option>
							<option value="10" <?php if($currenttemplate->display_order_limit=="10"){echo "selected";} ?>>10 <?php _e('months', 'wp-review-slider-pro'); ?></option>
							<option value="11" <?php if($currenttemplate->display_order_limit=="11"){echo "selected";} ?>>11 <?php _e('months', 'wp-review-slider-pro'); ?></option>
							<option value="12" <?php if($currenttemplate->display_order_limit=="12"){echo "selected";} ?>>12 <?php _e('months', 'wp-review-slider-pro'); ?></option>
							<option value="18" <?php if($currenttemplate->display_order_limit=="18"){echo "selected";} ?>>18 <?php _e('months', 'wp-review-slider-pro'); ?></option>
							<option value="24" <?php if($currenttemplate->display_order_limit=="24"){echo "selected";} ?>>24 <?php _e('months', 'wp-review-slider-pro'); ?></option>
							<option value="36" <?php if($currenttemplate->display_order_limit=="36"){echo "selected";} ?>>36 <?php _e('months', 'wp-review-slider-pro'); ?></option>
							<option value="48" <?php if($currenttemplate->display_order_limit=="48"){echo "selected";} ?>>48 <?php _e('months', 'wp-review-slider-pro'); ?></option>
							<option value="all" <?php if($currenttemplate->display_order_limit=="all" || $currenttemplate->display_order_limit==""){echo "selected";} ?>><?php _e('all months', 'wp-review-slider-pro'); ?></option>
						</select>
					</span>
					
					<p class="description">
					<?php _e('The order in which the reviews are displayed. The Secondary Sort Order is only used if two reviews are equal.', 'wp-review-slider-pro'); ?></p>
					<p class="description" id="sortweightdescription" style="display:none;"><b>
					<?php _e('You can change the individual Sort Weight for each review on the Review List page. Very right column. Reviews with the largest Sort Weight are shown first.', 'wp-review-slider-pro'); ?></b></p>
				</td>
			</tr>


			<?php
			if(!isset($template_misc_array['readmcolor'])){
				$template_misc_array['readmcolor'] = '#0000ee';
			}
			if(!isset($template_misc_array['readmpop'])){
				$template_misc_array['readmpop'] = '';
			}
			
			//make this backword compitable for templates that used read more.
			if($currenttemplate->read_more=='yes'){
				if(!isset($template_misc_array['cutrevs'])){
					$template_misc_array['cutrevs'] = 'yes';
				}
			}
			if(!isset($template_misc_array['cutrevs'])){
				$template_misc_array['cutrevs'] = 'no';
			}
			if(!isset($template_misc_array['cutrevs_lnum'])){
				$template_misc_array['cutrevs_lnum'] = '3';
			}
			if(!isset($template_misc_array['scrollbarauto'])){
				$template_misc_array['scrollbarauto'] = '';
			}

			?>
					
					
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Trim Long Reviews', 'wp-review-slider-pro'); ?><a class="wprevpro_helpicon_p wprevpro_btnicononlyhelp dashicons-before dashicons-editor-help"></a>
				</th>
				<td>
				<div>
					<select name="wprevpro_t_cutrevs" id="wprevpro_t_cutrevs" class="">
						<option value="no" <?php if($template_misc_array['cutrevs']=='no'){echo "selected";} ?>><?php _e('No', 'wp-review-slider-pro'); ?></option>
						<option value="yes" <?php if($template_misc_array['cutrevs']=='yes'){echo "selected";} ?>><?php _e('Yes', 'wp-review-slider-pro'); ?></option>
					</select><label for="wprevpro_t_rtype_manual"></label>
					<div class="wprevboxoutline" id="longreveiwsettings" <?php if($template_misc_array['cutrevs']=='no'){echo "style='display:none;'";} ?>>
					<?php _e('Max lines of text to display:', 'wp-review-slider-pro'); ?>
					<input id="wprevpro_t_cutrevs_lnum" type="number" min="1" name="wprevpro_t_cutrevs_lnum" placeholder="" value="<?php echo $template_misc_array['cutrevs_lnum']; ?>" style="width: 4em"><br>
					<?php _e('Use:', 'wp-review-slider-pro'); ?>
					<select name="wprevpro_t_read_more" id="wprevpro_t_read_more" class="">
						<option value="yes" <?php if($currenttemplate->read_more=='yes'){echo "selected";} ?>><?php _e('Read More', 'wp-review-slider-pro'); ?></option>
						<option value="no" <?php if($currenttemplate->read_more=='no'){echo "selected";} ?>><?php _e('Scroll Bar', 'wp-review-slider-pro'); ?></option>
					</select>
					<div id="readmoresettings" <?php if($currenttemplate->read_more=='no'){echo "style='display:none;'";} ?>>
						Color:&nbsp;<input type="text" data-alpha="true" value="<?php echo $template_misc_array['readmcolor']; ?>" name="wprevpro_template_misc_readmcolor" id="wprevpro_template_misc_readmcolor" class="my-color-field" />
						
						<label for="wprevpro_t_read_more_text">&nbsp;&nbsp;<?php _e('Read More Text:', 'wp-review-slider-pro'); ?></label>
						<input id="wprevpro_t_read_more_text" type="text" name="wprevpro_t_read_more_text" placeholder="read more" value="<?php if($currenttemplate->read_more_text!=''){echo $currenttemplate->read_more_text;} else {_e('read more', 'wp-review-slider-pro');}?>" style="width: 6em">
						<label for="wprevpro_t_read_less_text">&nbsp;&nbsp;<?php _e('Read Less Text:', 'wp-review-slider-pro'); ?></label>
						<input id="wprevpro_t_read_less_text" type="text" name="wprevpro_t_read_less_text" placeholder="read less" value="<?php if($currenttemplate->read_less_text!=''){echo $currenttemplate->read_less_text;} else {_e('read less', 'wp-review-slider-pro');}?>" style="width: 6em">
						</br>
						<?php
						/*
						?>
						<select name="wprevpro_t_length_type" id="wprevpro_t_length_type" class="">
						  <option value="words" <?php if($template_misc_array['length_type']=='words'){echo "selected";} ?>><?php _e('# words', 'wp-review-slider-pro'); ?></option>
						  <option value="char" <?php if($template_misc_array['length_type']=='char'){echo "selected";} ?>><?php _e('# characters', 'wp-review-slider-pro'); ?></option>
						</select><?php _e(' to show before link:', 'wp-review-slider-pro'); ?>
						<input id="wprevpro_t_read_more_num" type="number" min="1" name="wprevpro_t_read_more_num" placeholder="" value="<?php if($currenttemplate->read_more_num>0){echo $currenttemplate->read_more_num;} else {echo "20";}?>" style="width: 4.5em">
						<?php
						*/
						?>
						<div id="readmpopset"><input type="checkbox" name="wprevpro_t_read_more_pop" id="wprevpro_t_read_more_pop" value="yes" <?php if($template_misc_array['readmpop']=="yes"){echo 'checked="checked"';}?>><?php _e('Pop-up review in lightbox when clicking read more.', 'wp-review-slider-pro'); ?></div>
					</div>
					<div id="scrollsettings" <?php if($currenttemplate->read_more=='yes' || $currenttemplate->read_more==''){echo "style='display:none;'";} ?>>
						<input type="checkbox" name="wprevpro_t_scrollbarauto" id="wprevpro_t_scrollbarauto" value="yes" <?php if($template_misc_array['scrollbarauto']=='yes'){echo "checked";} ?>><label for="wprevpro_t_rtype_manual"> <?php _e('Auto-scroll on hover.', 'wp-review-slider-pro'); ?></label>
					</div>
					</div>
				</div>
					<p class="description">
					<?php _e('Shorten long reviews and add a "Read More" or a Scroll Bar. ', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>




<?php
/*
?>

			
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Add Read More Link', 'wp-review-slider-pro'); ?><a class="wprevpro_helpicon_p wprevpro_btnicononlyhelp dashicons-before dashicons-editor-help"></a>
				</th>
				<td><div class="divtemplatestyles">
					<label for="wprevpro_t_read_more"></label>
					<select name="wprevpro_t_read_more" id="wprevpro_t_read_more" class="">
						<option value="no" <?php if($currenttemplate->read_more=='no'){echo "selected";} ?>>No</option>
						<option value="yes" <?php if($currenttemplate->read_more=='yes'){echo "selected";} ?>>Yes</option>
					</select>

					<div id="readmoresettings" <?php if($currenttemplate->read_more=='no'){echo "style='display:none;'";} ?>>
					Color:&nbsp;<input type="text" data-alpha="true" value="<?php echo $template_misc_array['readmcolor']; ?>" name="wprevpro_template_misc_readmcolor" id="wprevpro_template_misc_readmcolor" class="my-color-field" />
					
					<label for="wprevpro_t_read_more_text">&nbsp;&nbsp;<?php _e('Read More Text:', 'wp-review-slider-pro'); ?></label>
					<input id="wprevpro_t_read_more_text" type="text" name="wprevpro_t_read_more_text" placeholder="read more" value="<?php if($currenttemplate->read_more_text!=''){echo $currenttemplate->read_more_text;} else {_e('read more', 'wp-review-slider-pro');}?>" style="width: 6em">
					<label for="wprevpro_t_read_less_text">&nbsp;&nbsp;<?php _e('Read Less Text:', 'wp-review-slider-pro'); ?></label>
					<input id="wprevpro_t_read_less_text" type="text" name="wprevpro_t_read_less_text" placeholder="read less" value="<?php if($currenttemplate->read_less_text!=''){echo $currenttemplate->read_less_text;} else {_e('read less', 'wp-review-slider-pro');}?>" style="width: 6em">
					</br>
					<select name="wprevpro_t_length_type" id="wprevpro_t_length_type" class="">
					  <option value="words" <?php if($template_misc_array['length_type']=='words'){echo "selected";} ?>><?php _e('# words', 'wp-review-slider-pro'); ?></option>
					  <option value="char" <?php if($template_misc_array['length_type']=='char'){echo "selected";} ?>><?php _e('# characters', 'wp-review-slider-pro'); ?></option>
					</select><?php _e(' to show before link:', 'wp-review-slider-pro'); ?>
					<input id="wprevpro_t_read_more_num" type="number" min="1" name="wprevpro_t_read_more_num" placeholder="" value="<?php if($currenttemplate->read_more_num>0){echo $currenttemplate->read_more_num;} else {echo "20";}?>" style="width: 4.5em">
					<div id="readmpopset"><input type="checkbox" name="wprevpro_t_read_more_pop" id="wprevpro_t_read_more_pop" value="yes" <?php if($template_misc_array['readmpop']=="yes"){echo 'checked="checked"';}?>>Pop-up review in lightbox when clicking read more.</div>
					</div>
					</div>
					<p class="description">
					<?php _e('Allows you to cut off long reviews and add a read more link that will show the rest of the review when clicked.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
<?php
*/
?>			

			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Reviews Same Height', 'wp-review-slider-pro'); ?><a class="wprevpro_helpicon_p wprevpro_btnicononlyhelp dashicons-before dashicons-editor-help"></a>
				</th>
				<td><div class="divtemplatestyles">
					<label for="wprevpro_t_review_same_height"><?php _e('Force the reviews to be the same height regardless of text length?', 'wp-review-slider-pro'); ?></label>
					<select name="wprevpro_t_review_same_height" id="wprevpro_t_review_same_height">
								<option value="no" <?php if($currenttemplate->review_same_height=="no" || $currenttemplate->review_same_height==""){echo "selected";} ?>><?php _e('No', 'wp-review-slider-pro'); ?></option>
								<option value="nod" <?php if($currenttemplate->review_same_height=="nod"){echo "selected";} ?>><?php _e('No -double line breaks', 'wp-review-slider-pro'); ?></option>
								<option value="noa" <?php if($currenttemplate->review_same_height=="noa"){echo "selected";} ?>><?php _e('No -all line breaks', 'wp-review-slider-pro'); ?></option>
								<option value="yes" <?php if($currenttemplate->review_same_height=="yes"){echo "selected";} ?>><?php _e('Yes', 'wp-review-slider-pro'); ?></option>
								<option value="cur" <?php if($currenttemplate->review_same_height=="cur"){echo "selected";} ?>><?php _e('Yes -double line breaks', 'wp-review-slider-pro'); ?></option>
								<option value="yea" <?php if($currenttemplate->review_same_height=="yea"){echo "selected";} ?>><?php _e('Yes -all line breaks', 'wp-review-slider-pro'); ?></option>
							</select>
					</div>
					<p class="description">
					<?php _e('The individual review boxes will all be equal to the biggest one in all slides. The -double line breaks will get rid of the double line breaks in the review text. The -all line breaks will remove all line breaks. ', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
			
			
			<?php

			?>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Template Margins', 'wp-review-slider-pro'); ?><a class="wprevpro_helpicon_p wprevpro_btnicononlyhelp dashicons-before dashicons-editor-help"></a>
				</th>
				<td>
				<div style="margin-bottom: 5px;"><b>
				<?php _e('Desktop - ', 'wp-review-slider-pro'); ?></b>
				<?php _e('Top/Bottom:', 'wp-review-slider-pro'); ?>
				<input id="wprevpro_t_template_margin_tb" type="number" name="wprevpro_t_template_margin_tb" value="<?php echo $template_misc_array['template_margin_tb'];?>" style="padding-top: 0px;width: 3.8em;height: 28px;">px
				&nbsp;&nbsp;
				<?php _e('Left/Right:', 'wp-review-slider-pro'); ?>
				<input id="wprevpro_t_template_margin_lr" type="number" name="wprevpro_t_template_margin_lr" value="<?php echo $template_misc_array['template_margin_lr'];?>" style="padding-top: 0px;width: 3.8em;height: 28px;">px
				</div>
				<div><b>
				<?php _e('Mobile - ', 'wp-review-slider-pro'); ?></b>
				<?php _e('Top/Bottom:', 'wp-review-slider-pro'); ?>
				<input id="wprevpro_t_template_margin_tb_m" type="number" name="wprevpro_t_template_margin_tb_m" value="<?php echo $template_misc_array['template_margin_tb_m'];?>" style="padding-top: 0px;width: 3.8em;height: 28px;">px
				&nbsp;&nbsp;
				<?php _e('Left/Right:', 'wp-review-slider-pro'); ?>
				<input id="wprevpro_t_template_margin_lr_m" type="number" name="wprevpro_t_template_margin_lr_m" value="<?php echo $template_misc_array['template_margin_lr_m'];?>" style="padding-top: 0px;width: 3.8em;height: 28px;">px
				</div>
					<p class="description">
					<?php _e('Add some margins around the review template. This can be helpful if the slider arrows are overlapping another element.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Default Avatar Style', 'wp-review-slider-pro'); ?><a class="wprevpro_helpicon_p wprevpro_btnicononlyhelp dashicons-before dashicons-editor-help"></a>
				</th>
				<td><div class="">
					<input type="radio" name="wprevpro_default_avatar" id="wprevpro_default_avatar1-radio" value="trip" <?php if($template_misc_array['default_avatar']=='trip' ){echo 'checked="checked"';} ?>>
					<label for="wprevpro_default_avatar1-radio">
					<img src="<?php echo plugin_dir_url( __FILE__ ); ?>tripadvisor_mystery_man.png" alt="thumb" class="default_avatar_img">
					</label>&nbsp;&nbsp;&nbsp;
					<input type="radio" name="wprevpro_default_avatar" id="wprevpro_default_avatar2-radio" value="google" <?php if($template_misc_array['default_avatar']=='google'){echo 'checked="checked"';} ?>>
					<label for="wprevpro_default_avatar2-radio"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>google_mystery_man.png" alt="thumb" class="default_avatar_img"></label>&nbsp;&nbsp;&nbsp;
					<input type="radio" name="wprevpro_default_avatar" id="wprevpro_default_avatar3-radio" value="fb" <?php if($template_misc_array['default_avatar']=='fb'){echo 'checked="checked"';} ?>>
					<label for="wprevpro_default_avatar3-radio"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>facebook_mystery_man.png" alt="thumb" class="default_avatar_img"></label>&nbsp;&nbsp;&nbsp;
					<input type="radio" name="wprevpro_default_avatar" id="wprevpro_default_avatar4-radio" value="yelp" <?php if($template_misc_array['default_avatar']=='yelp'){echo 'checked="checked"';} ?>>
					<label for="wprevpro_default_avatar4-radio"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>yelp_mystery_man.png" alt="thumb" class="default_avatar_img"></label>&nbsp;&nbsp;&nbsp;
					<input type="radio" name="wprevpro_default_avatar" id="wprevpro_default_avatar6-radio" value="airbnb" <?php if($template_misc_array['default_avatar']=='airbnb'){echo 'checked="checked"';} ?>>
					<label for="wprevpro_default_avatar6-radio"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>airbnb_mystery_man.png" alt="thumb" class="default_avatar_img"></label>&nbsp;&nbsp;&nbsp;
					
					<input type="radio" name="wprevpro_default_avatar" id="wprevpro_default_avatar7-radio" value="init" <?php if($template_misc_array['default_avatar']=='init'){echo 'checked="checked"';} ?>>
					<label for="wprevpro_default_avatar7-radio"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>initials_mystery_man.png" alt="thumb" class="default_avatar_img"></label>&nbsp;&nbsp;&nbsp;
					
					
					<input type="radio" name="wprevpro_default_avatar" id="wprevpro_default_avatar5-radio" value="none" <?php if($template_misc_array['default_avatar']=='none'){echo 'checked="checked"';} ?>>
					<label for="wprevpro_default_avatar5-radio">None</label>
					</div>
					<p class="description">
					<?php _e('If the review is missing the avatar then this will be used. Does not affect downloaded avatars that have a default avatar on their original site.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
			
			<?php

			?>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Show on Device', 'wp-review-slider-pro'); ?><a class="wprevpro_helpicon_p wprevpro_btnicononlyhelp dashicons-before dashicons-editor-help"></a>
				</th>
				<td><div class="">
					<input type="radio" name="wprevpro_screensize" id="wprevpro_screensize1-radio" value="both" <?php if($template_misc_array['screensize']=='' ||  $template_misc_array['screensize']=='both'){echo 'checked="checked"';} ?>>
					<label for="wprevpro_screensize1-radio">Desktop and Mobile
					</label>&nbsp;&nbsp;&nbsp;
					<input type="radio" name="wprevpro_screensize" id="wprevpro_screensize2-radio" value="desk" <?php if($template_misc_array['screensize']=='desk'){echo 'checked="checked"';} ?>>
					<label for="wprevpro_screensize2-radio">Desktop Only
					</label>&nbsp;&nbsp;&nbsp;
					<input type="radio" name="wprevpro_screensize" id="wprevpro_screensize3-radio" value="mobile" <?php if($template_misc_array['screensize']=='mobile'){echo 'checked="checked"';} ?>>
					<label for="wprevpro_screensize3-radio">Mobile Only
					</label>&nbsp;&nbsp;&nbsp;
					</div>
					<p class="description">
					<?php _e('Allows you to hide these reviews on mobile or desktop if you like. Useful if you want to use different templates based on screen size.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
			
			<tr class="wprevpro_row">
				<th scope="row" colspan="2">
				<span  class="button button-secondary dashicons-before dashicons-arrow-left gotopage0">Previous</span>
				<span  class="button button-secondary dashicons-before dashicons-arrow-right-after gotopage2">Next</span>
				</th>
			</tr>
			</tbody>
		</table>
		
	
		
	<table id="settingtable2" class="wprevpro_margin10 form-table settingstable templatesettingstable" style="display:none;">
		<tbody>
		<tr class="wprevpro_row" style="background: #f4f4f4;">
				<th scope="row" colspan="2">
				<?php _e('By default this review template will show all the reviews on the Review List page. Use the filters below to hide the ones you do not want to show.', 'wp-review-slider-pro'); ?>
				</th>
			</tr>
			<tr class="wprevpro_row revselectedhide">
				<th scope="row">
					<?php _e('Filter Reviews Without Text', 'wp-review-slider-pro'); ?><a class="wprevpro_helpicon_p wprevpro_btnicononlyhelp dashicons-before dashicons-editor-help"></a>
				</th>
				<td>
					<select name="wprevpro_t_hidenotext" id="wprevpro_t_hidenotext">
						<option value="yes" <?php if($currenttemplate->hide_no_text=="yes"){echo "selected";} ?>><?php _e('Yes', 'wp-review-slider-pro'); ?></option>
						<option value="no" <?php if($currenttemplate->hide_no_text=="no"){echo "selected";} ?>><?php _e('No', 'wp-review-slider-pro'); ?></option>
					</select>
					<p class="description">
					<?php _e('Set to Yes and only display reviews that have text included.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
			
			<tr class="wprevpro_row revselectedhide" <?php echo $ctselhidemestyle; ?>>
				<th scope="row">
					<?php _e('Filter By Rating', 'wp-review-slider-pro'); ?><a class="wprevpro_helpicon_p wprevpro_btnicononlyhelp dashicons-before dashicons-editor-help"></a>
				</th>
				<td>
					<select name="wprevpro_t_min_rating" id="wprevpro_t_min_rating">
					  <option value="1" <?php if($currenttemplate->min_rating==1){echo "selected";} ?>><?php _e('Show All', 'wp-review-slider-pro'); ?></option>
					  <option value="2" <?php if($currenttemplate->min_rating==2){echo "selected";} ?>><?php _e('2 & Higher', 'wp-review-slider-pro'); ?></option>
					  <option value="3" <?php if($currenttemplate->min_rating==3){echo "selected";} ?>><?php _e('3 & Higher', 'wp-review-slider-pro'); ?></option>
					  <option value="4" <?php if($currenttemplate->min_rating==4){echo "selected";} ?>><?php _e('4 & Higher', 'wp-review-slider-pro'); ?></option>
					  <option value="5" <?php if($currenttemplate->min_rating==5){echo "selected";} ?>><?php _e('Only 5 Star', 'wp-review-slider-pro'); ?></option>
					</select>
					<p class="description">
					<?php _e('Show only reviews with at least this value rating. Allows you to hide low reviews.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
			<tr class="wprevpro_row revselectedhide"<?php echo $ctselhidemestyle; ?>>
				<th scope="row">
					<?php _e('Filter By Length', 'wp-review-slider-pro'); ?><a class="wprevpro_helpicon_p wprevpro_btnicononlyhelp dashicons-before dashicons-editor-help"></a>
				</th>
				<td><?php _e('Only show reviews between', 'wp-review-slider-pro'); ?> <input id="wprevpro_t_min_words" type="number" name="wprevpro_t_min_words" placeholder="" value="<?php echo $currenttemplate->min_words; ?>" style="width: 4em;min-width: 4em;"> <?php _e('minimum and', 'wp-review-slider-pro'); ?> <input id="wprevpro_t_max_words" type="number" name="wprevpro_t_max_words" placeholder="" value="<?php echo $currenttemplate->max_words; ?>" style="width: 4em;min-width: 4em;"> <?php _e('maximum ', 'wp-review-slider-pro'); ?>
				<select name="wprevpro_t_word_or_char" id="wprevpro_t_word_or_char">
					  <option value="" <?php if($currenttemplate->word_or_char==''){echo "selected";} ?>><?php _e('words', 'wp-review-slider-pro'); ?></option>
					  <option value="char" <?php if($currenttemplate->word_or_char=='char'){echo "selected";} ?>><?php _e('characters', 'wp-review-slider-pro'); ?></option>
					</select>
				.
					<p class="description">
					<?php _e('Leave blank to show all reviews. Allows you to filter out the reviews based on word or character count.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
			
			<tr class="wprevpro_row revselectedhide"<?php echo $ctselhidemestyle; ?>>
				<th scope="row">
					<?php _e('Filter By Text String', 'wp-review-slider-pro'); ?><a class="wprevpro_helpicon_p wprevpro_btnicononlyhelp dashicons-before dashicons-editor-help"></a>
				</th>
				<td>
					<select name="wprevpro_t_string_sel" id="wprevpro_t_string_sel">
					  <option value="no" <?php if($currenttemplate->string_sel=='no'){echo "selected";} ?>><?php _e('Turned Off', 'wp-review-slider-pro'); ?></option>
					  <option value="any" <?php if($currenttemplate->string_sel=='any'){echo "selected";} ?>><?php _e('Contains at Least One of These', 'wp-review-slider-pro'); ?></option>
					  <option value="all" <?php if($currenttemplate->string_sel=='all'){echo "selected";} ?>><?php _e('Must Contain All These Words', 'wp-review-slider-pro'); ?></option>
					  <option value="not" <?php if($currenttemplate->string_sel=='not'){echo "selected";} ?>><?php _e('Does NOT Contain These Words', 'wp-review-slider-pro'); ?></option>
					</select>
				<input id="wprevpro_t_string_text" type="text" name="wprevpro_t_string_text" value="<?php echo $currenttemplate->string_text; ?>" placeholder="enter search string" style="padding-top: 0px;width: 10em;height: 28px;"></br>
				<select name="wprevpro_t_string_selnot" id="wprevpro_t_string_selnot">
					  <option value="no" <?php if($currenttemplate->string_selnot=='no' || $currenttemplate->string_selnot==''){echo "selected";} ?>><?php _e('Turned Off', 'wp-review-slider-pro'); ?></option>
					  <option value="not" <?php if($currenttemplate->string_selnot=='not'){echo "selected";} ?>><?php _e('Does NOT Contain These Words', 'wp-review-slider-pro'); ?></option>
					</select>
				<input id="wprevpro_t_string_textnot" type="text" name="wprevpro_t_string_textnot" value="<?php echo $currenttemplate->string_textnot; ?>" placeholder="enter search string" style="padding-top: 0px;width: 10em;height: 28px;">
				
					<p class="description">
					<?php _e('Only show reviews containing some or all or these words. Separate multiple words by a comma unless searching for multiple words next to each other like "brown dog".', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
			
			<tr class="wprevpro_row revselectedhide"<?php echo $ctselhidemestyle; ?>>
				<th scope="row">
					<?php _e('Filter By Review Type', 'wp-review-slider-pro'); ?><a class="wprevpro_helpicon_p wprevpro_btnicononlyhelp dashicons-before dashicons-editor-help"></a>
				</th>
				<td>
				<?php
				$rtypejsondecode = json_decode($currenttemplate->rtype);
				if(!is_array($rtypejsondecode)){
					$rtypejsondecode=array();
				}
				$tempquery = "SELECT DISTINCT type,from_name FROM ".$reviews_table_name." WHERE type IS NOT NULL ORDER by type DESC";
				//$tempquery = 	"select * from ".$reviews_table_name." group by pageid";
				$typerows = $wpdb->get_results($tempquery);
				//print_r($typerows);
				if(count($typerows)>0){
					//check if any are manual and add manual all 
					$foundmanual = false;
				foreach ( $typerows as $temptype ){
						$temptype->type = 
						$typelowercase = strtolower($temptype->type);
						$typelowercasecheck = str_replace(".","",$typelowercase); 
						$tempid = "wprevpro_t_rtype_".$typelowercasecheck;
						$tempval = $typelowercase;
						$tempname = $temptype->type;
						if($temptype->from_name!=''){
							$tempid = "wprevpro_t_rtype_".$typelowercase."_".$temptype->from_name;
							$tempval = $typelowercase."_".$temptype->from_name;
							$tempname = $temptype->type."_".$temptype->from_name;
							if($typelowercase=='manual'){
								$foundmanual = true;
							}
						}
						if($typelowercase=='manual'){
							$foundmanual = false;
						}
						
					?>
					<input type="checkbox" name="<?php echo $tempid;?>" id="<?php echo $tempid;?>" value="<?php echo $tempval;?>" <?php if(in_array($tempval, $rtypejsondecode)){echo 'checked="checked"';}?>><label for="<?php echo $tempid;?>"> <?php _e($tempname, 'wp-review-slider-pro'); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<?php
					}
					//add manual all here if we found one
					if($foundmanual==true){
						?>
						<input type="checkbox" name="wprevpro_t_rtype_manual" id="wprevpro_t_rtype_manual" value="manual" <?php if(in_array('manual', $rtypejsondecode)){echo 'checked="checked"';}?>>
						<label for="wprevpro_t_rtype_manual"> <?php _e('Manual All', 'wp-review-slider-pro'); ?></label>
						<?php
					}
				}
				?>
					<p class="description">
					<?php _e('Only show reviews of this type. Caution: Yelp only likes their reviews to be displayed with other Yelp reviews. Use at your own risk.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
			<?php
				if(!isset($template_misc_array['postfilter'])){
					$template_misc_array['postfilter']='no';
				}
				if(!isset($template_misc_array['postfilterlist'])){
					$template_misc_array['postfilterlist']='';
					$postfilterliststr = "";
				} else {
					//convert to string from json, function in class-wp-review-slider-pro-admin.php
					$postfilterliststr = $this->wprev_jsontocommastr($template_misc_array['postfilterlist']);
				}
			?>
			<tr class="wprevpro_row revselectedhide"<?php echo $ctselhidemestyle; ?>>
				<th scope="row">
					<?php _e('Filter By Post/Product', 'wp-review-slider-pro'); ?><a class="wprevpro_helpicon_p wprevpro_btnicononlyhelp dashicons-before dashicons-editor-help"></a>
				</th>
				<td><div class="">
					<label for="wprevpro_t_postfilter"><?php _e('Turn on Post ID filter:', 'wp-review-slider-pro'); ?></label>
					<select name="wprevpro_t_postfilter" id="wprevpro_t_postfilter">
					  <option value="yes" <?php if($template_misc_array['postfilter']=="yes"){echo "selected";} ?>><?php _e('Yes', 'wp-review-slider-pro'); ?></option>
					  <option value="no" <?php if($template_misc_array['postfilter']=="no"){echo "selected";} ?>><?php _e('No', 'wp-review-slider-pro'); ?></option>
					</select>&nbsp;&nbsp;&nbsp;
					<label for="wprevpro_t_postfilterlist"><?php _e('Also show reviews from these Post/Page/Product IDs:', 'wp-review-slider-pro'); ?></label>&nbsp;
					<input class="wprevpro_nr_postid" id="wprevpro_t_postfilterlist" type="text" name="wprevpro_t_postfilterlist" placeholder="" value="<?php echo $postfilterliststr; ?>" style="width: 8em">&nbsp;<a id="wprevpro_btn_pickpostids" class="button dashicons-before dashicons-yes ">Select Post IDs</a>
					</div>
					<p class="description">
					<?php _e('Allows you to only show reviews that are linked to the same Post or Product the review slider is being displayed on. You can also add a comma separated list of Post IDs. Submitted reviews are automatically linked to the Post where the form is located. Normally only used for submitted reviews.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
			<?php
				if(!isset($template_misc_array['categoryfilter'])){
					$template_misc_array['categoryfilter']='no';
				}
				if(!isset($template_misc_array['categoryfilterlist'])){
					$template_misc_array['categoryfilterlist']='';
					$catfilterliststr = "";
				} else {
					$catfilterliststr = $this->wprev_jsontocommastr($template_misc_array['categoryfilterlist']);
				}
			?>
			<tr class="wprevpro_row revselectedhide"<?php echo $ctselhidemestyle; ?>>
				<th scope="row">
					<?php _e('Filter By Category', 'wp-review-slider-pro'); ?><a class="wprevpro_helpicon_p wprevpro_btnicononlyhelp dashicons-before dashicons-editor-help"></a>
				</th>
				<td><div class="">
					<label for="wprevpro_t_categoryfilter"><?php _e('Turn on Post Category ID filter:', 'wp-review-slider-pro'); ?></label>
					<select name="wprevpro_t_categoryfilter" id="wprevpro_t_categoryfilter">
					  <option value="yes" <?php if($template_misc_array['categoryfilter']=="yes"){echo "selected";} ?>><?php _e('Yes', 'wp-review-slider-pro'); ?></option>
					  <option value="no" <?php if($template_misc_array['categoryfilter']=="no"){echo "selected";} ?>><?php _e('No', 'wp-review-slider-pro'); ?></option>
					</select>&nbsp;&nbsp;&nbsp;
					<label for="wprevpro_t_categoryfilterlist"><?php _e('Also show reviews from these category IDs:', 'wp-review-slider-pro'); ?></label>&nbsp;
					<input class="wprevpro_nr_categories" id="wprevpro_t_categoryfilterlist" type="text" name="wprevpro_t_categoryfilterlist" placeholder="" value="<?php echo $catfilterliststr; ?>" style="width: 8em">&nbsp;<a id="wprevpro_btn_pickcats" class="button dashicons-before dashicons-yes "><?php _e('Select Categories', 'wp-review-slider-pro'); ?></a>
					</div>
					<p class="description">
					<?php _e('Allows you to only show reviews that are linked to the same category as the post the review slider is being displayed on. You can also add a comma separated list of category IDs. Submitted reviews are automatically linked to the category of the post where the form is located. Normally only used for submitted reviews.', 'wp-review-slider-pro'); ?></p>
					<div id="tb_content_cat_select" style="display:none;">
						<div id="tb_content_cat_search"><input id="tb_content_cat_search_input" data-custom="custom" type="text" name="tb_content_cat_search_input" placeholder="><?php _e('Type here to search...', 'wp-review-slider-pro'); ?>" value=""></div>
						<div class="wprev_loader_catlist" style="display:none;"></div>
						<table id="selectcatstable" class="wp-list-table widefat striped posts">
						</table>
					</div>
				</td>
			</tr>

			
			
			<?php
			//pull distinct page names and page ids from reviews table
			$reviews_table_name = $wpdb->prefix . 'wpfb_reviews';
			//$tempquery = 	"SELECT DISTINCT pageid,pagename,type,from_url FROM ".$reviews_table_name." WHERE pageid IS NOT NULL";
			$tempquery = "select * from ".$reviews_table_name." group by pageid";
			$fbpagesrows = $wpdb->get_results($tempquery);
			if(count($fbpagesrows)>0){
			?>
			<tr class="wprevpro_row fbhide revselectedhide"<?php echo $ctselhidemestyle; ?>>
				<th scope="row">
					<?php _e('Filter By Source Location', 'wp-review-slider-pro'); ?><a class="wprevpro_helpicon_p wprevpro_btnicononlyhelp dashicons-before dashicons-editor-help"></a>
				</th>
				<td><a id="wprevpro_btn_pickpages" class="button dashicons-before dashicons-yes"><?php _e('Select Locations', 'wp-review-slider-pro'); ?></a>
					<?php
					//current selection if editing
					$rpagejsondecode = json_decode($currenttemplate->rpage);
					//print_r($rpagejsondecode);
					if(!$rpagejsondecode){$rpagejsondecode=[''];}
					?>
					<div id="tb_content_page_select" style="display:none;">
					<table class="selectrevstable wp-list-table widefat striped posts">
						<tbody id="">
					<?php
						foreach ( $fbpagesrows as $fbpage ) 
					{
					if($fbpage->pageid!=""){
						$temppagelink='';
						$temppagedb_id=$fbpage->pageid;
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
							<input type="checkbox" class="pageselectclass" name="wprevpro_t_rpage[]" id="page_<?php echo $fbpage->pageid; ?>" value="<?php echo $temppagedb_id; ?>"<?php if(in_array($temppagedb_id, $rpagejsondecode)){echo 'checked="checked"';}?>><label for="page_<?php echo $temppagedb_id; ?>"> <?php echo $fbpage->pagename.' ('.$fbpage->type.') - pageid: '.$temppagedb_id.'</label>'; ?>
							</td>
							</tr>
					<?php
					}
					}
					$numselpages = '';
					if(count(array_filter($rpagejsondecode))>0){
						if(count(array_filter($rpagejsondecode))==1){
							$numselpages = "(".count(array_filter($rpagejsondecode))." ".__('Page Selected', 'wp-review-slider-pro').")";
						} else {
							$numselpages = "(".count(array_filter($rpagejsondecode))." ".__('Pages Selected', 'wp-review-slider-pro').")";
						}
					}
					?>
						</tbody>
					</table>
				</div><span id="wprevpro_selectedpagesspan"> <?php echo $numselpages; ?></span>
					<p class="description">
					<?php _e('Only show reviews from these Source Locations. Leave blank to show all reviews in your database. Normally only used for downloaded social reviews.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
			<?php
			}
			?>
			
			<?php
				if(!isset($template_misc_array['langfilterlist'])){
					$template_misc_array['langfilterlist']='';
					$langfilterliststr = "";
				} else {
					$langfilterliststr = $this->wprev_jsontocommastr($template_misc_array['langfilterlist']);
				}
				if(!isset($template_misc_array['wpmllang'])){
					$template_misc_array['wpmllang']='';
				}
			?>
			<tr class="wprevpro_row revselectedhide"<?php echo $ctselhidemestyle; ?>>
				<th scope="row">
					<?php _e('Filter By Language', 'wp-review-slider-pro'); ?><a class="wprevpro_helpicon_p wprevpro_btnicononlyhelp dashicons-before dashicons-editor-help"></a>
				</th>
				<td><div class="">
					<input class="wprevpro_nr_language" id="wprevpro_t_langfilterlist" type="text" name="wprevpro_t_langfilterlist" placeholder="" value="<?php echo $langfilterliststr; ?>" style="width: 10em">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<span <?php if ( !function_exists('icl_object_id')){ echo 'style="display: none;"'; }?>>
					<input type="checkbox" name="wprevpro_t_rtype_wpmllang" id="wprevpro_t_rtype_wpmllang" value="yes" <?php if($template_misc_array['wpmllang']=='yes'){echo 'checked';} ?>><label for="wprevpro_t_rtype_wpmllang"> Use Current WPML Language </label>
					</span>
	
					
					</div>
					<p class="description">
					<?php _e('Single or comma separated list of language codes. Allows you to only show reviews that are tagged with these language codes. Visit the Tools tab above to add language codes to your reviews.', 'wp-review-slider-pro'); ?></p>

				</td>
			</tr>
			<?php
				if(!isset($template_misc_array['tagfilterlist'])){
					$template_misc_array['tagfilterlist']='';
					$tagfilterliststr = "";
				} else {
					//$tagfilterliststr = $this->wprev_jsontocommastr($template_misc_array['tagfilterlist']);
					$tagfilterliststrjson = json_decode($template_misc_array['tagfilterlist'],true);
					$tagfilterliststr = implode(",",$tagfilterliststrjson);
					
				}
				if(!isset($template_misc_array['tagfilterlist_opt'])){
					$template_misc_array['tagfilterlist_opt']='';
				}
				
			?>
			<tr class="wprevpro_row revselectedhide"<?php echo $ctselhidemestyle; ?>>
				<th scope="row">
					<?php _e('Filter By Tag', 'wp-review-slider-pro'); ?><a class="wprevpro_helpicon_p wprevpro_btnicononlyhelp dashicons-before dashicons-editor-help"></a>
				</th>
				<td><div class="">
					<input class="wprevpro_nr_tags" id="wprevpro_t_tagfilterlist" type="text" name="wprevpro_t_tagfilterlist" placeholder="" value="<?php echo $tagfilterliststr; ?>" style="width: 10em">
					<select name="wprevpro_t_tagfilterlist_opt" id="wprevpro_t_tagfilterlist_opt">
						<option value="these" <?php if($template_misc_array['tagfilterlist_opt']=='these' || $template_misc_array['tagfilterlist_opt']==''){echo "selected";} ?>>Must Have</option>
						<option value="notthese" <?php if($template_misc_array['tagfilterlist_opt']=='notthese'){echo "selected";} ?>>Must Not Have</option>
					</select>
					</div>
					<p class="description">
					<?php _e('Single or comma separated list of tags. Allows you to only show reviews that are tagged with these words. Tags can be added on the Review List page by editing a review.', 'wp-review-slider-pro'); ?></p>

				</td>
			</tr>
<?php
//these filter settings are hidden if selected reviews are being used.

				if($currenttemplate->showreviewsbyid){
					$ctselarray = json_decode($currenttemplate->showreviewsbyid);
					$countsel = count($ctselarray);
					$ctseleted = implode("-",json_decode($currenttemplate->showreviewsbyid));
					if($countsel>0 && $ctselarray[0]!=""){
						$numsemsg = '<b>'.$countsel.'</b> '.__('Reviews Selected', 'wp-review-slider-pro').' (<span class="dashicons dashicons-search" style="font-size: 16px;vertical-align: middle;"></span>'.__('show', 'wp-review-slider-pro').')';
						$clearallmsg = '(<span class="dashicons dashicons-trash" style="font-size: 16px;vertical-align: middle;"></span>'.__('clear all', 'wp-review-slider-pro').')';
						if($currenttemplate->showreviewsbyid_sel!="theseplus"){
						$ctselhidemestyle = ' style="background:rgb(173, 173, 173); "';
						}
					}
					
				} else {
					$ctseleted ="";
				}
?>	
			
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Select Reviews To Show', 'wp-review-slider-pro'); ?><a class="wprevpro_helpicon_p wprevpro_btnicononlyhelp dashicons-before dashicons-editor-help"></a>
				</th>
				<td>
				<input id="wprevpro_t_showreviewsbyid" data-custom="custom" type="hidden" name="wprevpro_t_showreviewsbyid" placeholder="" value="<?php echo $ctseleted; ?>">
				
					<a id="wprevpro_btn_pickreviews" class="button dashicons-before dashicons-yes"><?php _e('Select Reviews', 'wp-review-slider-pro'); ?></a>&nbsp;&nbsp;<span id="wprevpro_selectedrevsdiv"><?php echo $numsemsg; ?></span> <span id="wprevpro_clearselectedrevsbtn"> <?php echo $clearallmsg; ?></span>
					<select name="wprevpro_t_showreviewsbyid_sel" id="wprevpro_t_showreviewsbyid_sel">
						<option value="these" <?php if($currenttemplate->showreviewsbyid_sel=='these' || $currenttemplate->showreviewsbyid_sel==''){echo "selected";} ?>>Show these reviews only.</option>
						<option value="theseplus" <?php if($currenttemplate->showreviewsbyid_sel=='theseplus'){echo "selected";} ?>>Show these reviews plus others.</option>
					</select>
					<p class="description">
					<?php _e('Allows you to individually pick up to 100 reviews to display in this template. "Show these reviews only" will override all other filter settings. "Show these reviews plus others" will allow you to always include the selected reviews with other reviews from the filters above. It is normally better to use the "hide" icon on the Review List page to hide certain reviews.', 'wp-review-slider-pro'); ?></p>
	
				</td>
			</tr>
			<tr class="wprevpro_row">
				<th scope="row" colspan="2">
				<span  class="button button-secondary dashicons-before dashicons-arrow-left gotopage1">Previous</span>
				<span  class="button button-secondary dashicons-before dashicons-arrow-right-after gotopage3">Next</span>
				</th>
			</tr>
			</tbody>
		</table>
		
		<table id="settingtable3" class="wprevpro_margin10 form-table settingstable templatesettingstable" style="display:none;">
		<tbody>
		
		
			
<?php
//====================pro settings======================================
if ( $canusepremiumcode ) {
?>
			<tr class="wprevpro_row" style="background: #f4f4f4;">
				<th scope="row" colspan="2">
				<?php _e('Use this page to add a Header, Banner, and Filter options above the reviews.', 'wp-review-slider-pro'); ?>
				</th>
			</tr>

<?php
//this is coming in next version. will be a radio select showing hiding different options for banners.
//catch for old templates
if(!isset($template_misc_array['header_banner'])){
	if($template_misc_array['header_text']!=''){
		$template_misc_array['header_banner'] = 'txt';
	} else {
		$template_misc_array['header_banner'] = 'no';
	}
}
?>			
			<tr class="wprevpro_row add_banner" >
				<th scope="row">
					<?php _e('Banner', 'wp-review-slider-pro'); ?><a class="wprevpro_helpicon_p wprevpro_btnicononlyhelp dashicons-before dashicons-editor-help"></a>
				</th>
				<td>
				<input type="radio" name="wprevpro_t_header_banner" id="wprevpro_t_header_banner1-radio" value="no" <?php if($template_misc_array['header_banner']=="no"){echo "checked";} ?>>
				<label for="wprevpro_t_header_banner1-radio"><?php _e('None', 'wp-review-slider-pro'); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
				
				<input type="radio" name="wprevpro_t_header_banner" id="wprevpro_t_header_banner2-radio" value="txt" <?php if($template_misc_array['header_banner']=="txt"){echo "checked";} ?>>
				<label for="wprevpro_t_header_banner2-radio"><?php _e('Avg/Total Text Header', 'wp-review-slider-pro'); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
				
				<input type="radio" name="wprevpro_t_header_banner" id="wprevpro_t_header_banner3-radio" value="b1" <?php if($template_misc_array['header_banner']=="b1"){echo "checked";} ?>>
				<label for="wprevpro_t_header_banner3-radio"><?php _e('Banner Style 1', 'wp-review-slider-pro'); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<p class="description">
					<?php _e('Select a Banner to display above the reviews.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
<?php
if(!isset($template_misc_array['bbgcolor'])){
	$template_misc_array['bbgcolor']='';
}
if(!isset($template_misc_array['btxtcolor'])){
	$template_misc_array['btxtcolor']='';
}
if(!isset($template_misc_array['bncradius'])){
	$template_misc_array['bncradius']='2';
}
if(!isset($template_misc_array['bndropshadow'])){
	$template_misc_array['bndropshadow']='';
}
if(!isset($template_misc_array['bn_filter_opt'])){
	$template_misc_array['bn_filter_opt']='';
}
if(!isset($template_misc_array['bnrevusbtn'])){
	$template_misc_array['bnrevusbtn']='';
}
if(!isset($template_misc_array['bbordercolor'])){
	$template_misc_array['bbordercolor']='';
}

if(!isset($template_misc_array['bnshowsub'])){
	$template_misc_array['bnshowsub']='yes';
	$template_misc_array['bnshowsubtext'] = 'Submitted';
}
if(!isset($template_misc_array['bnshowman'])){
	$template_misc_array['bnshowman']='yes';
	$template_misc_array['bnshowmantext'] = 'Manual';
}
if(!isset($template_misc_array['bnhidesource'])){
	$template_misc_array['bnhidesource']='';
}

?>
			<tr class="wprevpro_row bannerprev">
				<td scope="row" colspan="2">
				<div id="bannerprevdiv"></div>
				</td>
			</tr>
			
			<tr class="wprevpro_row b1header bsettings">
				<td colspan="2">
				<div class="bnsettingstital">
				<?php _e('Banner Settings', 'wp-review-slider-pro'); ?><a class="wprevpro_helpicon_p wprevpro_btnicononlyhelp dashicons-before dashicons-editor-help"></a>
				</div>
				<div class="w3_wprs-row">
				<div class="w3_wprs-col s4">
					<div class="bncol">
					<span class="bnlabcol"><?php _e('Background:', 'wp-review-slider-pro'); ?></span>
					<input type="text" value="<?php echo $template_misc_array['bbgcolor']; ?>" name="wprevpro_t_bbgcolor" id="wprevpro_t_bbgcolor" data-alpha-enabled="true" class="my-color-field" /></div>
					<div class="bncol">
					<span class="bnlabcol"><?php _e('Text:', 'wp-review-slider-pro'); ?></span>
					<input type="text" value="<?php echo $template_misc_array['btxtcolor']; ?>" name="wprevpro_t_btxtcolor" id="wprevpro_t_btxtcolor" data-alpha-enabled="true" class="my-color-field" />
					</div>
					<div class="bncol">
					<span class="bnlabcol"><?php _e('Border:', 'wp-review-slider-pro'); ?></span>
					<input type="text" value="<?php echo $template_misc_array['bbordercolor']; ?>" name="wprevpro_t_bbordercolor" id="wprevpro_t_bbordercolor" data-alpha-enabled="true" class="my-color-field" />
					</div>
				</div>
				<div class="w3_wprs-col s4">
					<div class="bncol">
					<span class="bnlabcol"><?php _e('Corner Radius:', 'wp-review-slider-pro'); ?></span>
					<input id="wprevpro_t_bncradius" type="number" min="0" name="wprevpro_t_bncradius" placeholder="" value="<?php echo $template_misc_array['bncradius']; ?>" style="width: 4em;min-width: 4em;">
					</div>
					<div class="bncol">
					<span class="bnlabcol"><label for="wprevpro_t_bndropshadow"><?php _e('Drop Shadow:', 'wp-review-slider-pro'); ?></label></span>
					<input type="checkbox" name="wprevpro_t_bndropshadow" id="wprevpro_t_bndropshadow" value="yes" <?php if($template_misc_array['bndropshadow']=='yes'){echo "checked";} ?>>
					</div>
					<div class="bncol">
					<span class="bnlabcol"><label for="wprevpro_t_bnrevusbtn"><?php _e('Review Us Btn:', 'wp-review-slider-pro'); ?></label></span>
					<input type="checkbox" name="wprevpro_t_bnrevusbtn" id="wprevpro_t_bnrevusbtn" value="yes" <?php if($template_misc_array['bnrevusbtn']=='yes'){echo "checked";} ?>>
					<span class="button button-secondary bnbtnoptions"><?php _e('Btn Settings', 'wp-review-slider-pro'); ?></span>
					</div>
				</div>
				<div class="w3_wprs-col s4">
					<div class="bncol">
					<span class="bnlabcol"><label for="wprevpro_t_bn_filter_opt"><?php _e('Review Totals:', 'wp-review-slider-pro'); ?></label></span>
					<select name="wprevpro_t_bn_filter_opt" id="wprevpro_t_bn_filter_opt">
						<option value="" <?php if($template_misc_array['bn_filter_opt']==""){echo "selected";} ?>><?php _e('Downloaded & Filtered', 'wp-review-slider-pro'); ?></option>
						<option value="source" <?php if($template_misc_array['bn_filter_opt']=="source"){echo "selected";} ?>><?php _e('Source Site', 'wp-review-slider-pro'); ?></option>
					</select>
					</div>
					<div class="bncol bshowsubmitteddiv">
					<span class="bnlabcol"><label for="wprevpro_t_bnshowsub"><?php _e('Submitted:', 'wp-review-slider-pro'); ?></label></span>
					<input type="checkbox" name="wprevpro_t_bnshowsub" id="wprevpro_t_bnshowsub" value="yes" <?php if($template_misc_array['bnshowsub']=='yes'){echo "checked";} ?>>
					<input id="wprevpro_t_bnshowsubtext" type="text" name="wprevpro_t_bnshowsubtext" value="<?php echo $template_misc_array['bnshowsubtext']; ?>" style="width: 10em;min-width: 10em;">
					</div>
					
					<div class="bncol bshowmanualdiv">
					<span class="bnlabcol"><label for="wprevpro_t_bnshowman"><?php _e('Manual:', 'wp-review-slider-pro'); ?></label></span>
					<input type="checkbox" name="wprevpro_t_bnshowman" id="wprevpro_t_bnshowman" value="yes" <?php if($template_misc_array['bnshowman']=='yes'){echo "checked";} ?>>
					<input id="wprevpro_t_bnshowmantext" type="text" name="wprevpro_t_bnshowmantext" value="<?php echo $template_misc_array['bnshowmantext']; ?>" style="width: 10em;min-width: 10em;">
					</div>

					
					<div class="bncol bshowsourcediv">
					<span class="bnlabcol"><label for="wprevpro_t_bnhidesource"><?php _e('Hide Source:', 'wp-review-slider-pro'); ?></label></span>
					<input type="checkbox" name="wprevpro_t_bnhidesource" id="wprevpro_t_bnhidesource" value="yes" <?php if($template_misc_array['bnhidesource']=='yes'){echo "checked";} ?>>
				</div>
				</div>
				
				
					<p class="description">
					<?php _e('Use these settings to modify the Banner. The "Review Totals" setting allows you to use total and average calculated from downloaded reviews using the Filters for this template or from the total/avgerage on the Source Site.', 'wp-review-slider-pro'); ?><br>
					</p>
				</td>
			</tr>

			<tr class="wprevpro_row txtheader bsettings">
				<th scope="row">
					<?php _e('Avg/Total Header Settings', 'wp-review-slider-pro'); ?><a class="wprevpro_helpicon_p wprevpro_btnicononlyhelp dashicons-before dashicons-editor-help"></a>
				</th>
				<td>
				<?php _e('HTML Tag:', 'wp-review-slider-pro'); ?>
				<select name="wprevpro_t_header_text_tag" id="wprevpro_t_header_text_tag">
						<option value="h1" <?php if($template_misc_array['header_text_tag']=="h1"){echo "selected";} ?>><?php _e('h1', 'wp-review-slider-pro'); ?></option>
						<option value="h2" <?php if($template_misc_array['header_text_tag']=="h2"){echo "selected";} ?>><?php _e('h2', 'wp-review-slider-pro'); ?></option>
						<option value="h3" <?php if($template_misc_array['header_text_tag']=="h3"){echo "selected";} ?>><?php _e('h3', 'wp-review-slider-pro'); ?></option>
						<option value="h4" <?php if($template_misc_array['header_text_tag']=="h4"){echo "selected";} ?>><?php _e('h4', 'wp-review-slider-pro'); ?></option>
						<option value="h5" <?php if($template_misc_array['header_text_tag']=="h5"){echo "selected";} ?>><?php _e('h5', 'wp-review-slider-pro'); ?></option>
						<option value="p" <?php if($template_misc_array['header_text_tag']=="p"){echo "selected";} ?>><?php _e('p', 'wp-review-slider-pro'); ?></option>
						<option value="div" <?php if($template_misc_array['header_text_tag']=="div"){echo "selected";} ?>><?php _e('div', 'wp-review-slider-pro'); ?></option>
				</select>
				&nbsp;&nbsp;
				<?php _e('Text:', 'wp-review-slider-pro'); ?>
				<input id="wprevpro_t_header_text" type="text" name="wprevpro_t_header_text" value='<?php echo str_replace("'",'"',$template_misc_array['header_text']);?>' placeholder="" style="padding-top: 0px;width: 25em;height: 28px;">

				<select name="wprevpro_t_header_filter_opt" id="wprevpro_t_header_filter_opt">
						<option value="" <?php if($template_misc_array['header_filter_opt']==""){echo "selected";} ?>><?php _e('Downloaded & Filtered', 'wp-review-slider-pro'); ?></option>
						<option value="source" <?php if($template_misc_array['header_filter_opt']=="source"){echo "selected";} ?>><?php _e('Source Site', 'wp-review-slider-pro'); ?></option>
<?php
//find all badge Id's and names.
/*
$table_name_badge = $wpdb->prefix . 'wpfb_badges';
$currentforms = $wpdb->get_results("SELECT id, title FROM $table_name_badge");
if(count($currentforms)>0){
	foreach ( $currentforms as $currentform ){
		//$currentform->id
		$issel = '';
		if($template_misc_array['header_filter_opt']==$currentform->id){
			$issel = 'selected';
		}
		echo '<option value="'.$currentform->id.'" '.$issel.'> '.__('Badge:', 'wp-review-slider-pro').' '.$currentform->title.'</option>';
	}
} else {
	echo '<option value="" > '.__('No Badges Created', 'wp-review-slider-pro').'</option>';
}
*/
?>
				</select>
					<p class="description">
					<?php _e('Use the {avgrating} and {totalratings} tags to display the average and total ratings.', 'wp-review-slider-pro'); ?><br>
					<?php _e('"This Template Values" selection allows you to use total and average calculated from downloaded reviews or from the total/avgerage on the Source Site.', 'wp-review-slider-pro'); ?><br>
					<?php _e('Allowed html tags:', 'wp-review-slider-pro'); ?> &lt;span&gt;&lt;/span&gt;&lt;i&gt;&lt;/i&gt;&lt;em&gt;&lt;/em&gt;&lt;strong&gt;&lt;/strong&gt;&lt;b&gt;&lt;/b&gt;&lt;a&gt;&lt;/a&gt;.<br>
					<?php _e('You can use class names just make sure to use " instead of \'.', 'wp-review-slider-pro'); ?><br>
					<?php echo sprintf(__('ex: Rated &lt;b&gt;%1$s out of 5 stars&lt;/b&gt; based on %2$s customer reviews.', 'wp-review-slider-pro'),'{avgrating}','{totalratings}'); ?></p>
				</td>
			</tr>
			
			

<?php
//catch for old templates, if any of this is set to yes then we need to set the radio to yes.

?>			
			<tr class="wprevpro_row add_banner" >
				<th scope="row">
					<?php _e('Search/Sort/Filter', 'wp-review-slider-pro'); ?><a class="wprevpro_helpicon_p wprevpro_btnicononlyhelp dashicons-before dashicons-editor-help"></a>
				</th>
				<td>
				<span class="button button-secondary ssfsettings">Settings</span>

					<p class="description">
					<?php _e('Allow you to add search, filter, and sort options above the reviews.', 'wp-review-slider-pro'); ?>
					<img class="ssfexample" src="<?php echo WPREV_PLUGIN_URL; ?>/admin/partials/ssfex.png"></p>
					
				</td>
			</tr>
			
			<tr class="wprevpro_row searchsorttr" >
				<th scope="row">
					<?php _e('Add Search Bar', 'wp-review-slider-pro'); ?><a class="wprevpro_helpicon_p wprevpro_btnicononlyhelp dashicons-before dashicons-editor-help"></a>
				</th>
				<td>
				<input type="checkbox" name="wprevpro_t_header_search" id="wprevpro_t_header_search" value="yes" <?php if($template_misc_array['header_search']=="yes"){echo "checked";} ?>>

				&nbsp;&nbsp;
				<?php _e('Placeholder:', 'wp-review-slider-pro'); ?>
				<input id="wprevpro_t_header_search_place" type="text" name="wprevpro_t_header_search_place" value="<?php echo $template_misc_array['header_search_place'];?>" style="padding-top: 0px;width: 12em;height: 28px;">
					<p class="description">
					<?php _e('Display a search bar above the reviews so visitors can search reviews by text.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
			
			<tr class="wprevpro_row searchsorttr" >
				<th scope="row">
					<?php _e('Add Sort Option', 'wp-review-slider-pro'); ?><a class="wprevpro_helpicon_p wprevpro_btnicononlyhelp dashicons-before dashicons-editor-help"></a>
				</th>
				<td>
				<input type="checkbox" name="wprevpro_t_header_sort" id="wprevpro_t_header_sort" value="yes" <?php if($template_misc_array['header_sort']=="yes"){echo "checked";} ?>>

				&nbsp;&nbsp;
				<?php _e('Placeholder:', 'wp-review-slider-pro'); ?>
				<input id="wprevpro_t_header_sort_place" type="text" name="wprevpro_t_header_sort_place" value="<?php echo $template_misc_array['header_sort_place'];?>" style="padding-top: 0px;width: 12em;height: 28px;">
					<p class="description">
					<?php _e('Display a sort reviews field so users can sort the reviews.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
			
			<tr class="wprevpro_row searchsorttr">
				<th scope="row">
					<?php _e('Add Rating Option', 'wp-review-slider-pro'); ?><a class="wprevpro_helpicon_p wprevpro_btnicononlyhelp dashicons-before dashicons-editor-help"></a>
				</th>
				<td>
				<input type="checkbox" name="wprevpro_t_header_rating" id="wprevpro_t_header_rating" value="yes" <?php if($template_misc_array['header_rating']=="yes"){echo "checked";} ?>>

				&nbsp;&nbsp;
				<?php _e('Placeholder:', 'wp-review-slider-pro'); ?>
				<input id="wprevpro_t_header_rating_place" type="text" name="wprevpro_t_header_rating_place" value="<?php echo $template_misc_array['header_rating_place'];?>" style="padding-top: 0px;width: 12em;height: 28px;">
					<p class="description">
					<?php _e('Display a filter by rating field so users can show only reviews with a specific rating.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
			
			<tr class="wprevpro_row searchsorttr" >
				<th scope="row">
					<?php _e('Add Source Option', 'wp-review-slider-pro'); ?><a class="wprevpro_helpicon_p wprevpro_btnicononlyhelp dashicons-before dashicons-editor-help"></a>
				</th>
				<td>
				<input type="checkbox" name="wprevpro_t_header_source" id="wprevpro_t_header_source" value="yes" <?php if($template_misc_array['header_source']=="yes"){echo "checked";} ?>>
				
				&nbsp;&nbsp;
				<?php _e('Placeholder:', 'wp-review-slider-pro'); ?>
				<input id="wprevpro_t_header_source_place" type="text" name="wprevpro_t_header_source_place" value="<?php echo $template_misc_array['header_source_place'];?>" style="padding-top: 0px;width: 12em;height: 28px;">
					<p class="description">
					<?php _e('Display a filter by source dropdown field so users can show only reviews with a specific source.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
			
			<tr class="wprevpro_row searchsorttr" >
				<th scope="row">
					<?php _e('Add Language Option', 'wp-review-slider-pro'); ?><a class="wprevpro_helpicon_p wprevpro_btnicononlyhelp dashicons-before dashicons-editor-help"></a>
				</th>
				<td>
				<input type="checkbox" name="wprevpro_t_header_langcodes" id="wprevpro_t_header_langcodes" value="yes" <?php if($template_misc_array['header_langcodes']=="yes"){echo "checked";} ?>>
				
				&nbsp;&nbsp;
				<?php _e('Placeholder:', 'wp-review-slider-pro'); ?>
				<input id="wprevpro_t_header_langcodes_place" type="text" name="wprevpro_t_header_langcodes_place" value="<?php echo $template_misc_array['header_langcodes_place'];?>" style="padding-top: 0px;width: 12em;height: 28px;">
				&nbsp;&nbsp;
				<?php _e('Comma Seperated List of Codes:', 'wp-review-slider-pro'); ?>
				<input id="wprevpro_t_header_langcodes_list" type="text" placeholder="en, it" name="wprevpro_t_header_langcodes_list" value="<?php echo $template_misc_array['header_langcodes_list'];?>" style="padding-top: 0px;width: 12em;height: 28px;">
					<p class="description">
					<?php _e('Display a select language field so users can filter by language. Enter a comma separated list of language codes. You can see the language codes on the Review List page in the Count Column. If not, then go to the settings tab and run the language detector.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
			
			<tr class="wprevpro_row searchsorttr" >
				<th scope="row">
					<?php _e('Add Search Tags', 'wp-review-slider-pro'); ?><a class="wprevpro_helpicon_p wprevpro_btnicononlyhelp dashicons-before dashicons-editor-help"></a>
				</th>
				<td>
				<input type="checkbox" name="wprevpro_t_header_tag" id="wprevpro_t_header_tag" value="yes" <?php if($template_misc_array['header_tag']=="yes"){echo "checked";} ?>>

				&nbsp;&nbsp;
				<?php _e('Tags:', 'wp-review-slider-pro'); ?>
				<input id="wprevpro_t_header_tags" type="text" placeholder="price, menu, service" name="wprevpro_t_header_tags" value="<?php echo $template_misc_array['header_tags'];?>" style="padding-top: 0px;width: 20em;height: 28px;">
				<select name="wprevpro_t_header_tag_search" id="wprevpro_t_header_tag_search">
						<option value="" <?php if($template_misc_array['header_tag_search']=="" ){echo "selected";} ?>><?php _e('search review text', 'wp-review-slider-pro'); ?></option>
						<option value="tags" <?php if($template_misc_array['header_tag_search']=="tags"){echo "selected";} ?>><?php _e('search review tags', 'wp-review-slider-pro'); ?></option>
						<option value="both" <?php if($template_misc_array['header_tag_search']=="both"){echo "selected";} ?>><?php _e('search both', 'wp-review-slider-pro'); ?></option>
				</select>
					<p class="description">
					<?php _e('Display tags that users can click to easily filter the reviews. Enter tags seperated by a comma.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
			
			<tr class="wprevpro_row searchsorttr" >
				<th scope="row">
					<?php _e('Add Review Type Buttons', 'wp-review-slider-pro'); ?><a class="wprevpro_helpicon_p wprevpro_btnicononlyhelp dashicons-before dashicons-editor-help"></a>
				</th>
				<td>
				<input type="checkbox" name="wprevpro_t_header_rtypes" id="wprevpro_t_header_rtypes" value="yes" <?php if($template_misc_array['header_rtypes']=="yes"){echo "checked";} ?>>

					<p class="description">
					<?php _e('Display buttons for the review type (Facebook, Google, etc..) that users can click to easily filter the reviews.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
			

			<tr class="wprevpro_row add_banner">
				<th scope="row" colspan="2">
				<span  class="button button-secondary dashicons-before dashicons-arrow-left gotopage2">Previous</span>
				<span  class="button button-secondary dashicons-before dashicons-arrow-right-after gotopage4">Next</span>
				</th>
			</tr>
			</tbody>
		</table>
		
	<table id="settingtable4" class="wprevpro_margin10 form-table settingstable templatesettingstable" style="display:none;">
		<tbody>
			<tr class="wprevpro_row nopaddingtd">
				<th scope="row">
				</th>
				<td>&nbsp;
				</td>
			</tr>

			<tr class="wprevpro_row fbhide">
				<th scope="row">
					<?php _e('Review Icon Link', 'wp-review-slider-pro'); ?><a class="wprevpro_helpicon_p wprevpro_btnicononlyhelp dashicons-before dashicons-editor-help"></a>
				</th>
				<td><div class="divtemplatestyles">
					<label for="wprevpro_t_facebook_icon_link"><?php _e('Create a link to the Social Site?', 'wp-review-slider-pro'); ?></label>
					<select name="wprevpro_t_facebook_icon_link" id="wprevpro_t_facebook_icon_link">
						<option value="yes" <?php if($currenttemplate->facebook_icon_link=='yes'){echo "selected";} ?>><?php _e('Yes (nofollow)', 'wp-review-slider-pro'); ?></option>
						<option value="fol" <?php if($currenttemplate->facebook_icon_link=='fol'){echo "selected";} ?>><?php _e('Yes', 'wp-review-slider-pro'); ?></option>
						<option value="no" <?php if($currenttemplate->facebook_icon_link=='no'){echo "selected";} ?>><?php _e('No', 'wp-review-slider-pro'); ?></option>
					</select>
					</div>
					<p class="description">
					<?php _e('Links the site icon to the original review page. Turn on and off the site icon on the Template Style tab. Yelp reviews must display the icon.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
			<tr class="wprevpro_row ">
				<th scope="row">
					<?php _e('Review Avatar Link', 'wp-review-slider-pro'); ?><a class="wprevpro_helpicon_p wprevpro_btnicononlyhelp dashicons-before dashicons-editor-help"></a>
				</th>
				<td><div class="divtemplatestyles">
					<label for="wprevpro_t_profile_link"><?php _e('Link to reviewer profile page?', 'wp-review-slider-pro'); ?></label>
					<select name="wprevpro_t_profile_link" id="wprevpro_t_profile_link">
						<option value="no" <?php if($currenttemplate->add_profile_link=='no'){echo "selected";} ?>><?php _e('No', 'wp-review-slider-pro'); ?></option>
						<option value="yes" <?php if($currenttemplate->add_profile_link=='yes'){echo "selected";} ?>><?php _e('Yes (nofollow)', 'wp-review-slider-pro'); ?></option>
						<option value="fol" <?php if($currenttemplate->add_profile_link=='fol'){echo "selected";} ?>><?php _e('Yes', 'wp-review-slider-pro'); ?></option>
					</select>
					</div>
					<p class="description">
					<?php _e('When turned on, this allows you to click the reviewer avatar and go to their social profile page. This may not work for every type.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
			<?php
			if(!isset($template_misc_array['showmedia'])){
					$template_misc_array['showmedia']='yes';
			}
			?>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Review Media', 'wp-review-slider-pro'); ?><a class="wprevpro_helpicon_p wprevpro_btnicononlyhelp dashicons-before dashicons-editor-help"></a>
				</th>
				<td><div class="">
					<label for="wprevpro_t_showmedia"><?php _e('Display Review Media:', 'wp-review-slider-pro'); ?></label>
					<select name="wprevpro_t_showmedia" id="wprevpro_t_showmedia">
						<option value="no" <?php if($template_misc_array['showmedia']=='no'){echo "selected";} ?>>No</option>
						<option value="yes" <?php if($template_misc_array['showmedia']=='yes'){echo "selected";} ?>>Yes</option>
					</select>
					</div>
					<p class="description">
					<?php _e('Images can be added to a review on the Review List. They will also be downloaded for some review types.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
			
			<?php
			if(!isset($template_misc_array['ownerres'])){
					$template_misc_array['ownerres']='';
			}
			?>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Owner Response', 'wp-review-slider-pro'); ?><a class="wprevpro_helpicon_p wprevpro_btnicononlyhelp dashicons-before dashicons-editor-help"></a>
				</th>
				<td><div class="">
					<label for="wprevpro_t_ownerres"><?php _e('Display Owner Response:', 'wp-review-slider-pro'); ?></label>
					<select name="wprevpro_t_ownerres" id="wprevpro_t_ownerres">
						<option value="no" <?php if($template_misc_array['ownerres']=='no'){echo "selected";} ?>>No</option>
						<option value="yes" <?php if($template_misc_array['ownerres']=='yes'){echo "selected";} ?>>Yes</option>
					</select>
					</div>
					<p class="description">
					<?php _e('Show the Owner Response after the review text.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
			
			
			<?php
			if(!isset($template_misc_array['showlocation'])){
					$template_misc_array['showlocation']='no';
			}
			?>
			
			<?php
			if(!isset($template_misc_array['showsourcep'])){
					$template_misc_array['showsourcep']='no';
			}
			if(!isset($template_misc_array['showsourceplink'])){
					$template_misc_array['showsourceplink']='no';
			}
			?>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Source Name/Title', 'wp-review-slider-pro'); ?><a class="wprevpro_helpicon_p wprevpro_btnicononlyhelp dashicons-before dashicons-editor-help"></a>
				</th>
				<td><div class="">
					<label for="wprevpro_t_showsourcep"><?php _e('Display Review Source Page Name/Title:', 'wp-review-slider-pro'); ?></label>
					<select name="wprevpro_t_showsourcep" id="wprevpro_t_showsourcep">
						<option value="no" <?php if($template_misc_array['showsourcep']=='no'){echo "selected";} ?>>No</option>
						<option value="yes" <?php if($template_misc_array['showsourcep']=='yes'){echo "selected";} ?>>Yes</option>
					</select>
					<label for="wprevpro_t_showsourceplink"><?php _e('Link to Page:', 'wp-review-slider-pro'); ?></label>
					<select name="wprevpro_t_showsourceplink" id="wprevpro_t_showsourceplink">
						<option value="no" <?php if($template_misc_array['showsourceplink']=='no'){echo "selected";} ?>>No</option>
						<option value="yes" <?php if($template_misc_array['showsourceplink']=='yes'){echo "selected";} ?>>Yes (nofollow)</option>
						<option value="yesf" <?php if($template_misc_array['showsourceplink']=='yesf'){echo "selected";} ?>>Yes</option>
					</select>
					</div>
					<p class="description">
					<?php _e('Whether or not to add the source page name or download form title to the review and link to the source page. Shows up below the review.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
			
			
			
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Customer Location', 'wp-review-slider-pro'); ?><a class="wprevpro_helpicon_p wprevpro_btnicononlyhelp dashicons-before dashicons-editor-help"></a>
				</th>
				<td><div class="">
					<label for="wprevpro_t_showlocation"><?php _e('Display Customer Location:', 'wp-review-slider-pro'); ?></label>
					<select name="wprevpro_t_showlocation" id="wprevpro_t_showlocation">
						<option value="no" <?php if($template_misc_array['showlocation']=='no'){echo "selected";} ?>>No</option>
						<option value="yes" <?php if($template_misc_array['showlocation']=='yes'){echo "selected";} ?>>Yes</option>
					</select>
					</div>
					<p class="description">
					<?php _e('Whether or not to add the customer location to the review. Location may be blank for certain types of reviews. ', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
			
			<?php
			if(!isset($template_misc_array['showcdetails'])){
					$template_misc_array['showcdetails']='yes';
			}
			if(!isset($template_misc_array['showcdetailslink'])){
					$template_misc_array['showcdetailslink']='yes';
			}
			?>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Company Details', 'wp-review-slider-pro'); ?><a class="wprevpro_helpicon_p wprevpro_btnicononlyhelp dashicons-before dashicons-editor-help"></a>
				</th>
				<td><div class="">
					<label for="wprevpro_t_showcdetails"><?php _e('Display Company Name/Title:', 'wp-review-slider-pro'); ?></label>
					<select name="wprevpro_t_showcdetails" id="wprevpro_t_showcdetails">
						<option value="no" <?php if($template_misc_array['showcdetails']=='no'){echo "selected";} ?>>No</option>
						<option value="yes" <?php if($template_misc_array['showcdetails']=='yes'){echo "selected";} ?>>Yes</option>
					</select>
					<label for="wprevpro_t_showcdetailslink"><?php _e('Link to Company:', 'wp-review-slider-pro'); ?></label>
					<select name="wprevpro_t_showcdetailslink" id="wprevpro_t_showcdetailslink">
						<option value="no" <?php if($template_misc_array['showcdetailslink']=='no'){echo "selected";} ?>>No</option>
						<option value="yes" <?php if($template_misc_array['showcdetailslink']=='yes'){echo "selected";} ?>>Yes (nofollow)</option>
						<option value="yesf" <?php if($template_misc_array['showcdetailslink']=='yesf'){echo "selected";} ?>>Yes</option>
					</select>
					</div>
					<p class="description">
					<?php _e('Whether or not to add the company details to the review and link to the company website. Not normally used for social reviews.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
			
			<tr class="wprevpro_row">
						<th scope="row">
							<?php _e('Google Rich Snippet	', 'wp-review-slider-pro'); ?>	<a class="wprevpro_helpicon_p wprevpro_btnicononlyhelp dashicons-before dashicons-editor-help"></a>		
						</th>
						<td>
						<div class="divtemplatestyles">
							<label for="google_snippet_add"><?php _e('Add a Summary Review Google Rich Snippet?', 'wp-review-slider-pro'); ?></label>
							<select name="wprevpro_t_google_snippet_add" id="wprevpro_t_google_snippet_add">
								<option value="no" <?php if($currenttemplate->google_snippet_add!="yes"){echo "selected";} ?>><?php _e('No', 'wp-review-slider-pro'); ?></option>
								<option value="yes" <?php if($currenttemplate->google_snippet_add=="yes"){echo "selected";} ?>><?php _e('Yes', 'wp-review-slider-pro'); ?></option>
							</select>
							<div id="snippetsettings" class="row snisettingsdivs mt5 mb5" <?php if($currenttemplate->google_snippet_add!="yes"){echo "style='display:none;'";} ?>>
							<p id="snippetsettingsdesc" class="description" style="display: block;font-style: italic;"><?php _e('<b>Notes:</b></br>-Only turn this on for one review or badge template per a page or you will get duplicate rich snippets, which Google may not like.', 'wp-review-slider-pro'); ?>
							<?php _e('</br>-Leave the Name, Description, and Image blank and the plugin will try to pull the info from the Page.', 'wp-review-slider-pro'); ?>							</p>
								<div class="w3_wprs-col s4 googlesnippetsettingsdiv"><?php _e('Type:', 'wp-review-slider-pro'); ?></div>
								<div class="w3_wprs-col s8 googlesnippetsettingsdiv">
									<select name="wprevpro_t_google_snippet_type" id="wprevpro_t_google_snippet_type">
									<option value="Product" <?php if($currenttemplate->google_snippet_type=='Product'){echo "selected";} ?>>Product</option>
									<option value="LocalBusiness" <?php if($currenttemplate->google_snippet_type=='LocalBusiness'){echo "selected";} ?>>Local Business</option>
									<option value="">-------</option>
									<option value="AnimalShelter" <?php if($currenttemplate->google_snippet_type=='AnimalShelter'){echo "selected";} ?>>AnimalShelter</option>
									<option value="ArchiveOrganization" <?php if($currenttemplate->google_snippet_type=='ArchiveOrganization'){echo "selected";} ?>>ArchiveOrganization</option>
									<option value="AutomotiveBusiness" <?php if($currenttemplate->google_snippet_type=='AutomotiveBusiness'){echo "selected";} ?>>AutomotiveBusiness</option>
									<option value="ChildCare" <?php if($currenttemplate->google_snippet_type=='ChildCare'){echo "selected";} ?>>ChildCare</option>
									<option value="Dentist" <?php if($currenttemplate->google_snippet_type=='Dentist'){echo "selected";} ?>>Dentist</option>
									<option value="DryCleaningOrLaundry" <?php if($currenttemplate->google_snippet_type=='DryCleaningOrLaundry'){echo "selected";} ?>>DryCleaningOrLaundry</option>
									<option value="EmergencyService" <?php if($currenttemplate->google_snippet_type=='EmergencyService'){echo "selected";} ?>>EmergencyService</option>
									<option value="EmploymentAgency" <?php if($currenttemplate->google_snippet_type=='EmploymentAgency'){echo "selected";} ?>>EmploymentAgency</option>
									<option value="EntertainmentBusiness" <?php if($currenttemplate->google_snippet_type=='EntertainmentBusiness'){echo "selected";} ?>>EntertainmentBusiness</option>
									<option value="FinancialService" <?php if($currenttemplate->google_snippet_type=='FinancialService'){echo "selected";} ?>>FinancialService</option>
									<option value="FoodEstablishment" <?php if($currenttemplate->google_snippet_type=='FoodEstablishment'){echo "selected";} ?>>FoodEstablishment</option>
									<option value="GovernmentOffice" <?php if($currenttemplate->google_snippet_type=='GovernmentOffice'){echo "selected";} ?>>GovernmentOffice</option>
									<option value="HealthAndBeautyBusiness" <?php if($currenttemplate->google_snippet_type=='HealthAndBeautyBusiness'){echo "selected";} ?>>HealthAndBeautyBusiness</option>
									<option value="HomeAndConstructionBusiness" <?php if($currenttemplate->google_snippet_type=='HomeAndConstructionBusiness'){echo "selected";} ?>>HomeAndConstructionBusiness</option>
									<option value="HVACBusiness" <?php if($currenttemplate->google_snippet_type=='HVACBusiness'){echo "selected";} ?>>HVACBusiness</option>
									<option value="InternetCafe" <?php if($currenttemplate->google_snippet_type=='InternetCafe'){echo "selected";} ?>>InternetCafe</option>
									<option value="LegalService" <?php if($currenttemplate->google_snippet_type=='LegalService'){echo "selected";} ?>>LegalService</option>
									<option value="Library" <?php if($currenttemplate->google_snippet_type=='Library'){echo "selected";} ?>>Library</option>
									<option value="LodgingBusiness" <?php if($currenttemplate->google_snippet_type=='LodgingBusiness'){echo "selected";} ?>>LodgingBusiness</option>
									<option value="MedicalBusiness" <?php if($currenttemplate->google_snippet_type=='MedicalBusiness'){echo "selected";} ?>>MedicalBusiness</option>
									<option value="ProfessionalService" <?php if($currenttemplate->google_snippet_type=='ProfessionalService'){echo "selected";} ?>>ProfessionalService</option>
									<option value="RadioStation" <?php if($currenttemplate->google_snippet_type=='RadioStation'){echo "selected";} ?>>RadioStation</option>
									<option value="RealEstateAgent" <?php if($currenttemplate->google_snippet_type=='RealEstateAgent'){echo "selected";} ?>>RealEstateAgent</option>
									<option value="RecyclingCenter" <?php if($currenttemplate->google_snippet_type=='RecyclingCenter'){echo "selected";} ?>>RecyclingCenter</option>
									<option value="SelfStorage" <?php if($currenttemplate->google_snippet_type=='SelfStorage'){echo "selected";} ?>>SelfStorage</option>
									<option value="ShoppingCenter" <?php if($currenttemplate->google_snippet_type=='ShoppingCenter'){echo "selected";} ?>>ShoppingCenter</option>
									<option value="SportsActivityLocation" <?php if($currenttemplate->google_snippet_type=='SportsActivityLocation'){echo "selected";} ?>>SportsActivityLocation</option>
									<option value="Store" <?php if($currenttemplate->google_snippet_type=='Store'){echo "selected";} ?>>Store</option>
									<option value="TelevisionStation" <?php if($currenttemplate->google_snippet_type=='TelevisionStation'){echo "selected";} ?>>TelevisionStation</option>
									<option value="TouristInformationCenter" <?php if($currenttemplate->google_snippet_type=='TouristInformationCenter'){echo "selected";} ?>>TouristInformationCenter</option>
									<option value="TravelAgency" <?php if($currenttemplate->google_snippet_type=='TravelAgency'){echo "selected";} ?>>TravelAgency</option>
									</select>
								</div>
								<div class="w3_wprs-col s4 googlesnippetsettingsdiv "><?php _e('Business or Product Name:', 'wp-review-slider-pro'); ?></div>
								<div class="w3_wprs-col s8 googlesnippetsettingsdiv ">
									<input id="wprevpro_t_google_snippet_name" type="text" name="wprevpro_t_google_snippet_name" placeholder="" value="<?php if($currenttemplate->google_snippet_name!=""){echo stripslashes($currenttemplate->google_snippet_name);} ?>" style="width: 10em">
								</div>
								<div class="w3_wprs-col s4 googlesnippetsettingsdiv  "><?php _e('Description:', 'wp-review-slider-pro'); ?></div>
								<div class="w3_wprs-col s8 googlesnippetsettingsdiv  ">
									<input id="wprevpro_t_google_snippet_desc" type="text"  name="wprevpro_t_google_snippet_desc" placeholder="" value="<?php if($currenttemplate->google_snippet_desc!=""){echo stripslashes($currenttemplate->google_snippet_desc);} ?>" style="width: 20em">
								</div>
								<div class="w3_wprs-col s4 googlesnippetsettingsdiv  "><?php _e('Image URL:', 'wp-review-slider-pro'); ?></div>
								<div class="w3_wprs-col s8 googlesnippetsettingsdiv  ">
									<input id="wprevpro_t_google_snippet_business_image" type="text"  name="wprevpro_t_google_snippet_business_image" placeholder="" value="<?php if($currenttemplate->google_snippet_business_image!=""){echo $currenttemplate->google_snippet_business_image;} ?>" style="width: 30em">
								</div>
								
								<div id="businessrichsnippetfields" <?php if($currenttemplate->google_snippet_type=="Product"){echo "style='display:none;'";} ?>>
								<?php
								//get rich snippet more json and convert to array
								//echo $currenttemplate->template_misc;
								if(!isset($currenttemplate->google_snippet_more)){
									$currenttemplate->google_snippet_more='';
								}
								$google_misc_array = json_decode($currenttemplate->google_snippet_more, true);
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
										<input id="wprevpro_t_google_snippet_more_zip" type="text" name="wprevpro_t_google_snippet_more_zip" placeholder="Zip (Postal Code)" value="<?php if($google_misc_array['postalCode']!=''){echo stripslashes($google_misc_array['postalCode']);} ?>" style="width: 10em">
									</div>
								
								</div>
								<div id="productrichsnippetfields" <?php if($currenttemplate->google_snippet_type!="Product"){echo "style='display:none;'";} ?>>
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
								if(!isset($google_misc_array['irm'])){
									$google_misc_array['irm']='';
								}
								if(!isset($google_misc_array['irm_type'])){
									$google_misc_array['irm_type']='';
								}
								?>
								<div class="w3_wprs-col s4 googlesnippetsettingsdiv "><?php _e('Individual Review Markup:', 'wp-review-slider-pro'); ?></div>
								<div class="w3_wprs-col s8 googlesnippetsettingsdiv ">
									<label for="wprevpro_t_google_snippet_irm"></label>
									<select name="wprevpro_t_google_snippet_irm" id="wprevpro_t_google_snippet_irm" class="mt2">
										<option value="no" <?php if($google_misc_array['irm']=='no' || $google_misc_array['irm']==''){echo "selected";} ?>>No</option>
										<option value="yes" <?php if($google_misc_array['irm']=='yes'){echo "selected";} ?>>Yes</option>
									</select>
									<label for="wprevpro_t_google_snippet_irm_type">&nbsp;&nbsp;<?php _e('Only Use This Type:', 'wp-review-slider-pro'); ?></label>
									<select name="wprevpro_t_google_snippet_irm_type" id="wprevpro_t_google_snippet_irm_type" class="mt2">
										<option value="Manual" <?php if($google_misc_array['irm_type']=='manual'){echo "selected";} ?>>Manual</option>
										<option value="Submitted" <?php if($google_misc_array['irm_type']=='Submitted'){echo "selected";} ?>>Submitted</option>
										<option value="ManualSubmitted" <?php if($google_misc_array['irm_type']=='ManualSubmitted'){echo "selected";} ?>>Manual & Submitted</option>
										<option value="all" <?php if($google_misc_array['irm_type']=='all'){echo "selected";} ?>>All</option>
									</select><br>
									<p class="description" id="irmwarning" <?php if($google_misc_array['irm']!='yes'){echo "style='display:none;'";} ?>>
									<?php _e('Warning: Google only likes this added for reviews that originate from your site. Not third party sites. In other words, I would only use Manually added or Submitted reviews.', 'wp-review-slider-pro'); ?>
									</p>
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
									<p class="description" id="rsvoteorrevs" style="display: block;font-style: italic;" >
									<?php _e('Display total reviews as Votes or Reviews on Google search.', 'wp-review-slider-pro'); ?>
									</p>
								</div>
								
								
							</div>
						</div>
						<p class="description">
							<?php _e('When Google finds valid reviews or ratings markup, they may show a rich snippet in search results that includes stars and other summary info from the reviews. Once you turn this on and add your template to your site you can test it <a href="https://search.google.com/test/rich-results" target="_blank">here</a>.', 'wp-review-slider-pro'); ?></p>
						</td>
			</tr>
		
			
			<?php
			//check if copy exists
	$function_name = "copy";
    if ( function_exists($function_name) ) {
        $cmsg= "";
		$disableme='';
    } else {
        $cmsg="<span style='color:red;'>".__('PHP Copy function is not enabled', 'wp-review-slider-pro')."</span>";
		$disableme = "disabled";
    }
			
			?>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Cache Images', 'wp-review-slider-pro');?><a class="wprevpro_helpicon_p wprevpro_btnicononlyhelp dashicons-before dashicons-editor-help"></a>
					
				</th>
				<td>
				<label for="wprevpro_t_cache_settings"><?php _e('Cache review avatars?', 'wp-review-slider-pro'); ?></label>
					<select name="wprevpro_t_cache_settings" id="wprevpro_t_cache_settings" <?php echo $disableme; ?>>
					  <option value="" <?php if($currenttemplate->cache_settings==''){echo "selected";} ?>><?php _e('No', 'wp-review-slider-pro'); ?></option>
					  <option value="image" <?php if($currenttemplate->cache_settings=='image'){echo "selected";} ?>><?php _e('Yes', 'wp-review-slider-pro'); ?></option>
					</select>
					<?php echo $cmsg;?>
					<p class="description">
					<?php _e('When displaying a lot of reviews, this may improve performance. Avatar images are downloaded and compressed instead of being referenced from Facebook, Yelp, Google, etc.. servers. Note: The PHP “copy” function must be enabled on your server.', 'wp-review-slider-pro'); ?></p>
				</td>
			</tr>
					
			<tr id="selectrevsrow" class="wprevpro_row">
			<td colspan="2">
				<div id="tb_content" style="display:none;">
					<table class="selectrevstable wp-list-table widefat striped posts">
						<thead>
							<tr>
								<th scope="col" style="min-width: 20px;" class="manage-column"></th>
								<th scope="col" style="min-width: 50px;" class="manage-column"><?php _e('Pic', 'wp-review-slider-pro'); ?></th>
								<th scope="col" style="min-width: 70px;" sortdir="DESC" sorttype="name" class="wprevpro_tablesort manage-column"><i class="dashicons dashicons-sort"></i> <?php _e('Name', 'wp-review-slider-pro'); ?></th>
								<th scope="col" style="min-width: 70px;" sortdir="DESC" sorttype="rating" class="wprevpro_tablesort manage-column"><i class="dashicons dashicons-sort"></i> <?php _e('Rating', 'wp-review-slider-pro'); ?></th>
								<th scope="col" style="width: 80%;" sortdir="DESC" sorttype="stext" class="wprevpro_tablesort manage-column"><i class="dashicons dashicons-sort"></i> <?php _e('Text', 'wp-review-slider-pro'); ?></th>
								<th scope="col" style="min-width: 75px;" sortdir="DESC" sorttype="stime" class="wprevpro_tablesort manage-column"><i class="dashicons dashicons-sort text_green"></i> <?php _e('Date', 'wp-review-slider-pro'); ?></th>
								<th scope="col" style="min-width: 75px;" sortdir="DESC" sorttype="type" class="wprevpro_tablesort manage-column"><i class="dashicons dashicons-sort text_green"></i> <?php _e('Type', 'wp-review-slider-pro'); ?></th>
							</tr>
						</thead>
						<thead>
							<tr id="wprevpro_searchbar">
								<th scope="col" class="manage-column" colspan="7"><span class="dashicons dashicons-search" style="font-size: 30px;"></span>
								<input id="wprevpro_filter_table_name" type="text" name="wprevpro_filter_table_name" placeholder="Enter Search Text" >
								<select name="wprevpro_filter_table_min_rating" id="wprevpro_filter_table_min_rating">
								<option value="0" ><?php _e('All', 'wp-review-slider-pro'); ?></option>
								  <option value="1" ><?php _e('1 Star', 'wp-review-slider-pro'); ?></option>
								  <option value="2" ><?php _e('2 Star', 'wp-review-slider-pro'); ?></option>
								  <option value="3" ><?php _e('3 Star', 'wp-review-slider-pro'); ?></option>
								  <option value="4" ><?php _e('4 Star', 'wp-review-slider-pro'); ?></option>
								  <option value="5" ><?php _e('5 Star', 'wp-review-slider-pro'); ?></option>
								</select>
								<select name="wprevpro_filter_table_type" id="wprevpro_filter_table_type">
								<option value="all" ><?php _e('All', 'wp-review-slider-pro'); ?></option>
								<?php
								//$typearray = unserialize(WPREV_TYPE_ARRAY);
								$reviews_table_name = $wpdb->prefix . 'wpfb_reviews';
								$tempquery = "select type from ".$reviews_table_name." group by type";
								$typearray = $wpdb->get_col($tempquery);
								for($x=0;$x<count($typearray);$x++)
								{
									$temptype = $typearray[$x];
									$typelowercase = strtolower($typearray[$x]);
									echo '<option value="'.$typelowercase.'" >'.$temptype.'</option>';
								}
								?>
								</select>
								</th>
							</tr>
						</thead>
						<tbody id="review_list_select">
						</tbody>
					</table>
					<div id="wprevpro_list_pagination_bar">
						&nbsp;
					</div>
				</div>
			</td>
			</tr>

			<tr id="selectstariconsrow" style="display:none;" class="wprevpro_row">
			<td colspan="2">
				<div id="tb_content_sicons" style="display:none;">
				<?php
				//this is a pop-up for the select star icons
				//create array that holds the class names
				$iconclasses = array(
							"wprsp-plus",
							"wprsp-minus",
							"wprsp-glass",
							"wprsp-music",
							"wprsp-heart",
							"wprsp-star",
							"wprsp-star-o",
							"wprsp-flag",
							"wprsp-camera",
							"wprsp-gift",
							"wprsp-leaf",
							"wprsp-fire",
							"wprsp-thumbs-o-up",
							"wprsp-thumbs-o-down",
							"wprsp-heart-o",
							"wprsp-trophy",
							"wprsp-lightbulb-o",
							"wprsp-circle",
							"wprsp-smile-o",
							"wprsp-frown-o",
							"wprsp-meh-o",
							"wprsp-thumbs-up",
							"wprsp-thumbs-down",
							"wprsp-gittip",
							"wprsp-sun-o",
							"wprsp-moon-o",
							"wprsp-paw",
							"wprsp-tripadvisor",
							"wprsp-yelp",
							"wprsp-google-plus",
							"wprsp-facebook",
							"empty"
							);
				$arrlength = count($iconclasses);
				for($x = 0; $x < $arrlength; $x++) {
					echo "<span class='button stariconlist' id='".$iconclasses[$x]."'><span class='svgicons svg-".$iconclasses[$x]."'></span></span> ";
				}
				
				?>
				<div class="iconoverdiv">
					<div>
						<input type="checkbox" name="wprevpro_t_over_yelp" id="wprevpro_t_over_yelp" value="yes" <?php if($template_misc_array['icon_over_yelp']=="yes"){echo 'checked="checked"';}?>>
						<label for="wprevpro_t_over_yelp"> <?php _e('Override Yelp Icon', 'wp-review-slider-pro'); ?></label>
					</div>
					<div>
						<input type="checkbox" name="wprevpro_t_over_trip" id="wprevpro_t_over_trip" value="yes" <?php if($template_misc_array['icon_over_trip']=="yes"){echo 'checked="checked"';}?>>
						<label for="wprevpro_t_over_yelp"> <?php _e('Override TripAdvisor Icon', 'wp-review-slider-pro'); ?></label>
					</div>
				</div>
				</div>
			</td>
			</tr>
			<tr class="wprevpro_row">
				<th scope="row" colspan="2">
				<span  class="button button-secondary dashicons-before dashicons-arrow-left gotopage3">Previous</span>
				</th>
			</tr>
			
			
<?php
//end premium logic--------------------------------------
}
    if ( !$canusepremiumcode ) {

?>
			<tr>
				<td class="notice updated " colspan="2" style="border-left: 4px solid #d6d6d6;">
					<p><strong><?php _e('Upgrade to the Pro Version of this plugin to access more super cool settings! Get the Pro Version <a href="' . wrsp_fs()->get_upgrade_url() . '">here</a>!', 'wp-fb-reviews'); ?></strong></p>
				</td>
			</tr>
<?php
    }

?>
		</tbody>
	</table>
<?php
//in case this is an old template
if(!isset($template_misc_array['ps_bw'])){
		$template_misc_array['ps_bw']='';
		$template_misc_array['ps_br']='';
		$template_misc_array['ps_bcolor']='';
		$template_misc_array['ps_bgcolor']='';
		$template_misc_array['ps_fontcolor']='';
		$template_misc_array['ps_fsize']='';
		$template_misc_array['ps_paddingt']='';
		$template_misc_array['ps_paddingb']='';
		$template_misc_array['ps_paddingl']='';
		$template_misc_array['ps_paddingr']='';
		$template_misc_array['ps_margint']='';
		$template_misc_array['ps_marginb']='';
		$template_misc_array['ps_marginl']='';
		$template_misc_array['ps_marginr']='';
}
?>
<div id="tb_content_paginationstyle" style="display:none;">
	<div class="w3-row-padding row">
		<div class="w3_wprs-col s6 paginationstyleblock">
			<h2 class="w3_wprs-col s12 styletitle">Settings:</h2>
			<div class="w3_wprs-col s5 googlesnippetsettingsdiv">Border Width:</div>
			<div class="w3_wprs-col s7 googlesnippetsettingsdiv">
			<input id="wprevpro_t_ps_bw" class="updatebtnstyle" type="number" min="0" name="wprevpro_t_ps_bw" placeholder="" value="<?php echo $template_misc_array['ps_bw']; ?>" style="width: 6em">
			</div>
			<div class="w3_wprs-col s5 googlesnippetsettingsdiv">Border Radius:</div>
			<div class="w3_wprs-col s7 googlesnippetsettingsdiv">
			<input id="wprevpro_t_ps_br" class="updatebtnstyle" type="number" min="0" name="wprevpro_t_ps_br" placeholder="" value="<?php echo $template_misc_array['ps_br']; ?>" style="width: 6em">
			</div>
			
			<div class="w3_wprs-col s5 googlesnippetsettingsdiv">Border Color:</div>
			<div class="w3_wprs-col s7 googlesnippetsettingsdiv">
			<input type="text" data-alpha="true" value="<?php echo $template_misc_array['ps_bcolor']; ?>" name="wprevpro_t_ps_bcolor" id="wprevpro_t_ps_bcolor" class="updatebtnstyle my-color-field" />
			</div>
			
			<div class="w3_wprs-col s5 googlesnippetsettingsdiv">Background Color:</div>
			<div class="w3_wprs-col s7 googlesnippetsettingsdiv">
			<input type="text" data-alpha="true" value="<?php echo $template_misc_array['ps_bgcolor']; ?>" name="wprevpro_t_ps_bgcolor" id="wprevpro_t_ps_bgcolor" class="updatebtnstyle my-color-field" />
			</div>
			
			<div class="w3_wprs-col s5 googlesnippetsettingsdiv">Font Color:</div>
			<div class="w3_wprs-col s7 googlesnippetsettingsdiv">
			<input type="text" data-alpha="true" value="<?php echo $template_misc_array['ps_fontcolor']; ?>" name="wprevpro_t_ps_fontcolor" id="wprevpro_t_ps_fontcolor" class="updatebtnstyle my-color-field" />
			</div>
			
			<div class="w3_wprs-col s5 googlesnippetsettingsdiv">Font Size:</div>
			<div class="w3_wprs-col s7 googlesnippetsettingsdiv">
			<input id="wprevpro_t_ps_fsize" class="updatebtnstyle" type="number" min="0" name="wprevpro_t_ps_fsize" placeholder="" value="<?php echo $template_misc_array['ps_fsize']; ?>" style="width: 6em">
			</div>
			
			
			<div class="w3_wprs-col s12 googlesnippetsettingsdiv">Padding:</div>
			<div class="w3_wprs-col s12">
				<div class="w3_wprs-col s6 googlesnippetsettingsdiv">
				Top:<input id="wprevpro_t_ps_paddingt" class="updatebtnstyle" type="number" min="0" name="wprevpro_t_ps_paddingt" placeholder="" value="<?php echo $template_misc_array['ps_paddingt']; ?>" style="width: 4em;min-width: 4em;">
				</div>
				<div class="w3_wprs-col s6 googlesnippetsettingsdiv">
				Bottom:<input id="wprevpro_t_ps_paddingb" class="updatebtnstyle" type="number" min="0" name="wprevpro_t_ps_paddingb" placeholder="" value="<?php echo $template_misc_array['ps_paddingb']; ?>" style="width: 4em;min-width: 4em;">
				</div>
				<div class="w3_wprs-col s6 googlesnippetsettingsdiv">
				Left:<input id="wprevpro_t_ps_paddingl" class="updatebtnstyle" type="number" min="0" name="wprevpro_t_ps_paddingl" placeholder="" value="<?php echo $template_misc_array['ps_paddingl']; ?>" style="width: 4em;min-width: 4em;">
				</div>
				<div class="w3_wprs-col s6 googlesnippetsettingsdiv">
				Right:<input id="wprevpro_t_ps_paddingr" class="updatebtnstyle" type="number" min="0" name="wprevpro_t_ps_paddingr" placeholder="" value="<?php echo $template_misc_array['ps_paddingr']; ?>" style="width: 4em;min-width: 4em;">
				</div>
			</div>
			<div class="w3_wprs-col s12 googlesnippetsettingsdiv">Margin:</div>
			<div class="w3_wprs-col s12">
				<div class="w3_wprs-col s6 googlesnippetsettingsdiv">
				Top:<input id="wprevpro_t_ps_margint" class="updatebtnstyle" type="number" name="wprevpro_t_ps_margint" placeholder="" value="<?php echo $template_misc_array['ps_margint']; ?>" style="width: 4em;min-width: 4em;">
				</div>
				<div class="w3_wprs-col s6 googlesnippetsettingsdiv">
				Bottom:<input id="wprevpro_t_ps_marginb" class="updatebtnstyle" type="number" name="wprevpro_t_ps_marginb" placeholder="" value="<?php echo $template_misc_array['ps_marginb']; ?>" style="width: 4em;min-width: 4em;">
				</div>
				<div class="w3_wprs-col s6 googlesnippetsettingsdiv">
				Left:<input id="wprevpro_t_ps_marginl" class="updatebtnstyle" type="number" name="wprevpro_t_ps_marginl" placeholder="" value="<?php echo $template_misc_array['ps_marginl']; ?>" style="width: 4em;min-width: 4em;">
				</div>
				<div class="w3_wprs-col s6 googlesnippetsettingsdiv">
				Right:<input id="wprevpro_t_ps_marginr" class="updatebtnstyle" type="number" name="wprevpro_t_ps_marginr" placeholder="" value="<?php echo $template_misc_array['ps_marginr']; ?>" style="width: 4em;min-width: 4em;">
				</div>
			</div>

		</div>
		<div class="w3_wprs-col s6 paginationstyleblock">
			<h2 class="w3_wprs-col s12 styletitle">Preview:</h2>
			<div id="paginationstylepreviewdiv" class="w3_wprs-col s12 googlesnippetsettingsdiv ">

			</div>
		</div>
	</div>
</div>	

<?php
if(!isset($template_misc_array['revus_bcolor'])){
	$template_misc_array['revus_bcolor']='';
	$template_misc_array['revus_bgcolor']='';
	$template_misc_array['revus_fontcolor']='';
	$template_misc_array['revus_btntext']='Review Us';
	$template_misc_array['revus_btnlink']='';
	
}
if(!isset($template_misc_array['revus_btnaction'])){
		$template_misc_array['revus_btnaction'] = 'link';
}
if(!isset($template_misc_array['revus_puform'])){
		$template_misc_array['revus_puform'] = '';
}
if(!isset($template_misc_array['revus_txtval'])){
		$template_misc_array['revus_txtval'] = 'Review Us';
}


?>
<div id="tb_content_revusbtnoptions" style="display:none;">
	<div class="w3-row-padding row">
		<div class="w3_wprs-col s12 paginationstyleblock">
			<h2 class="w3_wprs-col s12 styletitle"><?php _e('Settings:', 'wp-review-slider-pro'); ?></h2>
			
			<div class="w3_wprs-col s4 "><?php _e('Background Color:', 'wp-review-slider-pro'); ?></div>
			<div class="w3_wprs-col s8 ">

			<input type="text" data-alpha-enabled="true" value="<?php echo $template_misc_array['revus_bgcolor']; ?>" name="wprevpro_t_revus_bgcolor" id="wprevpro_t_revus_bgcolor" class="updaterevusbtnstyle my-color-field" />
			</div>
			
			<div class="w3_wprs-col s4 "><?php _e('Font Color:', 'wp-review-slider-pro'); ?></div>
			<div class="w3_wprs-col s8 ">
			<input type="text" data-alpha-enabled="true" value="<?php echo $template_misc_array['revus_fontcolor']; ?>" name="wprevpro_t_revus_fontcolor" id="wprevpro_t_revus_fontcolor" class="updaterevusbtnstyle my-color-field" />
			</div>
			
			<div class="w3_wprs-col s4 "><?php _e('Border Color:', 'wp-review-slider-pro'); ?></div>
			<div class="w3_wprs-col s8 ">
			<input type="text" data-alpha-enabled="true" value="<?php echo $template_misc_array['revus_bcolor']; ?>" name="wprevpro_t_revus_bcolor" id="wprevpro_t_revus_bcolor" class="updaterevusbtnstyle my-color-field" />
			</div>
			
			
			<div class="w3_wprs-col s4 "><?php _e('Button Text:', 'wp-review-slider-pro'); ?></div>
			<div class="w3_wprs-col s8 ">
			<input type="text" value="<?php echo $template_misc_array['revus_txtval']; ?>" name="wprevpro_t_revus_txtval" id="wprevpro_t_revus_txtval" class="updaterevusbtnstyle" />
			</div>
			<div class="w3_wprs-col s12">&nbsp;</div>
			<div class="w3_wprs-col s4 "><?php _e('Button Action:', 'wp-review-slider-pro'); ?></div>
			<div class="w3_wprs-col s8 ">
			

			<select name="wprevpro_t_revus_btnaction" id="wprevpro_t_revus_btnaction">
				<option value="link" <?php if($template_misc_array['revus_btnaction']=="link"){echo "selected";} ?>><?php _e('Link', 'wp-review-slider-pro'); ?></option>
				<option value="ddlinks" <?php if($template_misc_array['revus_btnaction']=="ddlinks"){echo "selected";} ?>><?php _e('Links Drop Down', 'wp-review-slider-pro'); ?></option>
				<option value="form" <?php if($template_misc_array['revus_btnaction']=="form"){echo "selected";} ?>><?php _e('Pop-up Form', 'wp-review-slider-pro'); ?></option>

			</select>
			
			</div>
			<div class="w3_wprs-col s12">&nbsp;</div>
			
			<div class="w3_wprs-col s12 linksettingsdiv">
				<div class="w3_wprs-col s4 ">Enter Link URL:</div>
				<div class="w3_wprs-col s8 ">
				<input type="text" value="<?php echo $template_misc_array['revus_btnlink']; ?>" name="wprevpro_t_revus_btnlink" id="wprevpro_t_revus_btnlink" class="updaterevusbtnstyle" />
				</div>
			</div>
			
			<div class="w3_wprs-col s12 ddlinkssettingsdiv">
				<div class="w3_wprs-col s4 ">Link Names:</div>
				<div class="w3_wprs-col s1 ">&nbsp;</div>
				<div class="w3_wprs-col s7 ">Link URLs:</div>
				<?php
				if(!isset($template_misc_array['revus_btnln'])){
					$template_misc_array['revus_btnln']=Array();
				}
				if(!isset($template_misc_array['revus_btnlu'])){
					$template_misc_array['revus_btnlu']=Array();
				}
				for ($x = 1; $x <= 6; $x++) {
					if(!isset($template_misc_array['revus_btnln'][$x])){
						$template_misc_array['revus_btnln'][$x]='';
					}
					if(!isset($template_misc_array['revus_btnlu'][$x])){
						$template_misc_array['revus_btnlu'][$x]='';
					}
				?>
				<div class="w3_wprs-col s4 "><input type="text" placeholder="Link <?php echo $x;?> Name" value="<?php echo $template_misc_array['revus_btnln'][$x]; ?>" name="wprevpro_t_revus_btnln<?php echo $x;?>" id="wprevpro_t_revus_btnln<?php echo $x;?>" class="updaterevusbtnstyle" />
				</div>
				<div class="w3_wprs-col s1 ">&nbsp;</div>
				<div class="w3_wprs-col s7 ">
				<input type="text" placeholder="Link <?php echo $x;?> Url" value="<?php echo $template_misc_array['revus_btnlu'][$x]; ?>" name="wprevpro_t_revus_btnlu<?php echo $x;?>" id="wprevpro_t_revus_btnlu<?php echo $x;?>" class="updaterevusbtnstyle" />
				</div>
				<?php
				}
				?>
			</div>
			
			<div class="w3_wprs-col s12 formsettingsdiv">
				Select Form to Pop-up:&nbsp;
				<select name="wprevpro_t_revus_puform" id="wprevpro_t_revus_puform" class="updaterevusbtnstyle">
				<?php
				echo '<option value=""></option>';
				//get list of form names and ids.
				$forms_table_name = $wpdb->prefix . 'wpfb_forms';
				$currentforms= $wpdb->get_results( "SELECT id,title FROM ".$forms_table_name );
				print_r($currentforms);
				
				if(count($currentforms)>0){
					foreach ($currentforms as $currentform){
						if($currentform->id == $template_misc_array['revus_puform']){
							echo '<option value="'.$currentform->id.'" selected>'.$currentform->title.'</option>';
						} else {
							echo '<option value="'.$currentform->id.'">'.$currentform->title.'</option>';
						}
					}
				} else {
					echo '<option value="">No Forms Found.</option>';
				}
				?>
				</select>
			
			<p class="description">
					<?php _e('Create a Form on the Forms page of the plugin.', 'wp-review-slider-pro'); ?></p>
			</div>

		</div>

	</div>
</div>	

	
	<?php 
	//security nonce
	wp_nonce_field( 'wprevpro_save_template');
	?>
	<input type="hidden" name="edittid" id="edittid"  value="<?php echo $currenttemplate->id; ?>">
	<input type="submit" name="wprevpro_submittemplatebtn" id="wprevpro_submittemplatebtn" class="wpsubmitbutton button button-primary" value="<?php _e('Save & Close', 'wp-review-slider-pro'); ?>" >
	<a id="wprevpro_addnewtemplate_update" class="button button-primary"><?php if($currenttemplate->id>0){_e('Update', 'wp-review-slider-pro');} else {_e('Save', 'wp-review-slider-pro');} ?></a>
	<a id="wprevpro_addnewtemplate_cancel" class="button button-secondary"><?php _e('Cancel', 'wp-review-slider-pro'); ?></a>
	<a id="wprevpro_addnewtemplate_preview" class="button button-secondary"><?php _e('Preview', 'wp-review-slider-pro'); ?></a>
	<div id="update_form_msg_div"><img src="<?php echo WPREV_PLUGIN_URL; ?>/public/partials/imgs/loading_ripple.gif" id="savingformimg" class="wprptemplate_update_loading_image" style="display:none;"><span id="update_form_msg"></span></div>
		
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
	
	<div id="tb_content_style_select" style="display:none;">
	<div class="style_cont">
<?php
for ($x = 1; $x <= 13; $x=$x+2) {
	$y = $x +1;
?>
		<div class="style_sel_cont w3_wprs-row">
			<div id="style_<?php echo $x; ?>" data-selid="<?php echo $x; ?>" class="w3_wprs-col s6 style_sel_cind ">
				<div class="selimg_div">
				<img src="<?php echo plugin_dir_url( __FILE__ ); ?>t<?php echo $x; ?>preview.png" class="img_style_sel">
				</div>
			</div>
			<?php
			if($y!=14){
			?>
		  <div id="style_<?php echo $y; ?>" data-selid="<?php echo $y; ?>" class="w3_wprs-col s6 style_sel_cind ">
		  <div class="selimg_div">
				<img src="<?php echo plugin_dir_url( __FILE__ ); ?>t<?php echo $y; ?>preview.png" class="img_style_sel">
				</div>
			</div>
			<?php
			}
			?>
		</div>
<?php
}
?>
	</div>
	</div>

	
	
</div>

<?php
//for displaying preview
$iframefile = "/wp-admin/admin.php?page=wp_pro-get_preview";
?>
<div id='iframediv' class='' style='height:300px; margin-left: -15px; margin-right: 5px; display:none;position: relative;'>
<div id='overlayloadingdiv' class='previewloader'></div>
<iframe id="previframe" src="" width="100%" scrolling="auto" frameborder="0"></iframe>
</div>

<br><br><br><br><br><br><br>
<style>
div#wpfooter {
    display: none !important;
}
<style>
