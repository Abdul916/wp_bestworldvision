<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    WP_Review_Pro
 * @subpackage WP_Review_Pro/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    WP_Review_Pro
 * @subpackage WP_Review_Pro/includes
 * @author     Your Name <email@example.com>
 */
class WP_Review_Pro_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	 
	public static function activate_all($networkwide) {
		global $wpdb;
		 
		if (function_exists('is_multisite') && is_multisite()) {
			// check if it is a network activation - if so, run the activation function for each blog id
			if ($networkwide) {
						$old_blog = $wpdb->blogid;
				// Get all blog ids
				$blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
				foreach ($blogids as $blog_id) {
					switch_to_blog($blog_id);
					self::activate();
				}
				switch_to_blog($old_blog);
				return;
			}   
		} 
		self::activate();   
	}
	 
	public static function activate() {
		//============================
		//need to make this multisite compatible
		//=============================
		
		//add option for optionally loading css and js files on certain post/pages
		add_option( 'wprev_jscssposts', '' );

		//add option for checking if we should load slick js, regular js, or both.
		add_option( 'wprev_slidejsload', 'both' );
		
		//setup cron
		if (! wp_next_scheduled ( 'wprevpro_daily_event' )) {
			wp_schedule_event(time(), 'daily', 'wprevpro_daily_event');  
		}
		//for auto language_code
		if (! wp_next_scheduled ( 'wprevpro_daily_event_lang' )) {
			$starttime = time()+500;
			wp_schedule_event($starttime, 'daily', 'wprevpro_daily_event_lang');  
		}
	
		//create table in database
		global $wpdb;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$table_name = $wpdb->prefix . 'wpfb_reviews';
		
			$charset_collate = $wpdb->get_charset_collate();
			$bloglang = get_bloginfo("language"); //don't change if chinese (zh). blob will mess with chinese characters
			
			//echo "charset_collate:".$charset_collate;
			//look for mb4 collation. if not found set text to blob. this is for smileys to be able to save
			$pos = strpos($charset_collate, "mb4");
			$checklang = strpos($bloglang, "zh");
			$rtextcol = "text";
			$rtitlecol = "varchar(500)";
			
			if ($pos !== false) {
				$rtextcol = "text";
				$rtitlecol = "varchar(500)";
			} else if ($checklang === false){
				$rtextcol = "blob";
				$rtitlecol = "blob";
			}

			$sql = "CREATE TABLE $table_name (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				pageid varchar(150) DEFAULT '' NOT NULL,
				pagename tinytext NOT NULL,
				created_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				created_time_stamp int(12) NOT NULL,
				reviewer_name tinytext NOT NULL,
				reviewer_email tinytext NOT NULL,
				company_name varchar(100) DEFAULT '' NOT NULL,
				company_title varchar(100) DEFAULT '' NOT NULL,
				company_url varchar(100) DEFAULT '' NOT NULL,
				reviewer_id varchar(50) DEFAULT '' NOT NULL,
				rating varchar(3) NOT NULL,
				recommendation_type varchar(12) DEFAULT '' NOT NULL,
				review_text ".$rtextcol." NOT NULL,
				hide varchar(3) DEFAULT '' NOT NULL,
				review_length int(5) NOT NULL,
				review_length_char int(5) NOT NULL,
				type varchar(20) DEFAULT '' NOT NULL,
				userpic varchar(500) DEFAULT '' NOT NULL,
				userpic_small varchar(500) DEFAULT '' NOT NULL,
				from_name varchar(20) DEFAULT '' NOT NULL,
				from_url varchar(800) DEFAULT '' NOT NULL,
				from_logo varchar(500) DEFAULT '' NOT NULL,
				from_url_review varchar(800) DEFAULT '' NOT NULL,
				review_title ".$rtitlecol." DEFAULT '' NOT NULL,
				categories text NOT NULL,
				posts text NOT NULL,
				consent varchar(3) DEFAULT '' NOT NULL,
				userpiclocal varchar(500) DEFAULT '' NOT NULL,
				hidestars varchar(3) DEFAULT '' NOT NULL,
				miscpic varchar(500) DEFAULT '' NOT NULL,
				location varchar(500) DEFAULT '' NOT NULL,
				verified_order varchar(10) DEFAULT '' NOT NULL,
				language_code varchar(10) DEFAULT '' NOT NULL,
				unique_id tinytext DEFAULT '' NOT NULL,
				meta_data text DEFAULT '' NOT NULL,
				custom_data text DEFAULT '' NOT NULL,
				custom_stars text DEFAULT '' NOT NULL,
				owner_response text NOT NULL,
				sort_weight int(5) NOT NULL,
				tags text NOT NULL,
				mediaurlsarrayjson text NOT NULL,
				mediathumburlsarrayjson text NOT NULL,
				reviewfunnel varchar(3) DEFAULT '' NOT NULL,
				translateparent varchar(10) DEFAULT '' NOT NULL,
				last_modified DATETIME NOT NULL ON UPDATE CURRENT_TIMESTAMP,
				UNIQUE KEY id (id),
				PRIMARY KEY (id)
			) $charset_collate;";
			dbDelta( $sql );
			
			$table_name = $wpdb->prefix . 'wpfb_post_templates';
			$sql_template = "CREATE TABLE $table_name (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				title varchar(200) DEFAULT '' NOT NULL,
				template_type varchar(7) DEFAULT '' NOT NULL,
				style int(2) NOT NULL,
				created_time_stamp int(12) NOT NULL,
				display_num int(2) NOT NULL,
				display_num_rows int(3) NOT NULL,
				load_more varchar(3) DEFAULT '' NOT NULL,
				load_more_text varchar(50) DEFAULT '' NOT NULL,
				display_order varchar(10) DEFAULT '' NOT NULL,
				display_order_second varchar(10) DEFAULT '' NOT NULL,
				hide_no_text varchar(3) DEFAULT '' NOT NULL,
				template_css text NOT NULL,
				min_rating int(2) NOT NULL,
				min_words int(4) NOT NULL,
				max_words int(4) NOT NULL,
				word_or_char varchar(5) DEFAULT '' NOT NULL,
				rtype varchar(200) DEFAULT '' NOT NULL,
				rpage varchar(1000) DEFAULT '' NOT NULL,
				createslider varchar(3) DEFAULT '' NOT NULL,
				numslides int(2) NOT NULL,
				sliderautoplay varchar(3) DEFAULT '' NOT NULL,
				sliderdirection varchar(12) DEFAULT '' NOT NULL,
				sliderarrows varchar(3) DEFAULT '' NOT NULL,
				sliderdots varchar(3) DEFAULT '' NOT NULL,
				sliderdelay int(2) NOT NULL,
				sliderspeed int(5) NOT NULL,
				sliderheight varchar(3) DEFAULT '' NOT NULL,
				slidermobileview varchar(5) DEFAULT '' NOT NULL,
				showreviewsbyid varchar(600) DEFAULT '' NOT NULL,
				template_misc text DEFAULT '' NOT NULL,
				read_more varchar(3) DEFAULT '' NOT NULL,
				read_more_num int(4) NOT NULL,
				read_more_text varchar(20) DEFAULT '' NOT NULL,
				facebook_icon varchar(3) DEFAULT '' NOT NULL,
				facebook_icon_link varchar(3) DEFAULT '' NOT NULL,
				google_snippet_add varchar(3) DEFAULT '' NOT NULL,
				google_snippet_type varchar(50) DEFAULT '' NOT NULL,
				google_snippet_name varchar(500) DEFAULT '' NOT NULL,
				google_snippet_desc varchar(1000) DEFAULT '' NOT NULL,
				google_snippet_business_image varchar(500) DEFAULT '' NOT NULL,
				google_snippet_more text DEFAULT '' NOT NULL,
				cache_settings varchar(5) DEFAULT '' NOT NULL,
				review_same_height varchar(3) DEFAULT '' NOT NULL,
				add_profile_link varchar(3) DEFAULT '' NOT NULL,
				display_order_limit varchar(3) DEFAULT '' NOT NULL,
				display_masonry varchar(3) DEFAULT '' NOT NULL,
				read_less_text varchar(20) DEFAULT '' NOT NULL,
				string_sel varchar(3) DEFAULT '' NOT NULL,
				string_selnot varchar(3) DEFAULT '' NOT NULL,
				string_text varchar(300) DEFAULT '' NOT NULL,
				string_textnot varchar(300) DEFAULT '' NOT NULL,
				showreviewsbyid_sel varchar(9) DEFAULT '' NOT NULL,
				UNIQUE KEY id (id),
				PRIMARY KEY (id)
			) $charset_collate;";
			dbDelta( $sql_template );
		
			$table_name_badge = $wpdb->prefix . 'wpfb_badges';
			$sql_badge = "CREATE TABLE $table_name_badge (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				title varchar(200) DEFAULT '' NOT NULL,
				badge_type varchar(7) DEFAULT '' NOT NULL,
				badge_bname varchar(100) DEFAULT '' NOT NULL,
				badge_orgin varchar(20) DEFAULT '' NOT NULL,
				style varchar(10) NOT NULL,
				created_time_stamp int(12) NOT NULL,
				badge_css text NOT NULL,
				badge_misc text DEFAULT '' NOT NULL,
				rpage text DEFAULT '' NOT NULL,
				google_snippet_add varchar(3) DEFAULT '' NOT NULL,
				google_snippet_type varchar(50) DEFAULT '' NOT NULL,
				google_snippet_name varchar(50) DEFAULT '' NOT NULL,
				google_snippet_desc varchar(300) DEFAULT '' NOT NULL,
				google_snippet_business_image varchar(400) DEFAULT '' NOT NULL,
				google_snippet_more text DEFAULT '' NOT NULL,
				UNIQUE KEY id (id),
				PRIMARY KEY (id)
			) $charset_collate;";
			dbDelta( $sql_badge );
			
			$table_name_form = $wpdb->prefix . 'wpfb_forms';
			$sql_form = "CREATE TABLE $table_name_form (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				title varchar(200) DEFAULT '' NOT NULL,
				style varchar(10) NOT NULL,
				created_time_stamp int(12) NOT NULL,
				form_css text NOT NULL,
				form_html text NOT NULL,
				form_fields text NOT NULL,
				form_misc text DEFAULT '' NOT NULL,
				notifyemail varchar(200) DEFAULT '' NOT NULL,
				UNIQUE KEY id (id),
				PRIMARY KEY (id)
			) $charset_collate;";
			dbDelta( $sql_form );
			
			$table_name_form = $wpdb->prefix . 'wpfb_floats';
			$sql_form = "CREATE TABLE $table_name_form (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				title varchar(200) DEFAULT '' NOT NULL,
				float_type varchar(200) DEFAULT '' NOT NULL,
				content_id varchar(200) DEFAULT '' NOT NULL,
				style varchar(10) NOT NULL,
				created_time_stamp int(12) NOT NULL,
				float_css text NOT NULL,
				float_misc text DEFAULT '' NOT NULL,
				enabled int(2) NOT NULL,
				UNIQUE KEY id (id),
				PRIMARY KEY (id)
			) $charset_collate;";
			dbDelta( $sql_form );
			
			$table_name_reviewfunnel = $wpdb->prefix . 'wpfb_reviewfunnel';
			$sql_reviewfunnel = "CREATE TABLE $table_name_reviewfunnel (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				title varchar(140) DEFAULT '' NOT NULL,
				reviewlistpageid varchar(300) DEFAULT '' NOT NULL,
				site_type varchar(20) DEFAULT '' NOT NULL,
				url varchar(700) DEFAULT '' NOT NULL,
				cron varchar(3) DEFAULT '' NOT NULL,
				cron_last_job_id int(12) NOT NULL,
				cron_last_ran int(12) NOT NULL,
				cron_last_download int(12) NOT NULL,
				cron_numtimes_checked int(12) NOT NULL,
				created_time_stamp int(12) NOT NULL,
				from_date varchar(10) DEFAULT '' NOT NULL,
				query varchar(300) DEFAULT '' NOT NULL,
				blocks varchar(4) DEFAULT '' NOT NULL,
				job_ids text DEFAULT '' NOT NULL,
				last_name varchar(7) DEFAULT '' NOT NULL,
				profile_img varchar(7) DEFAULT '' NOT NULL,
				categories text NOT NULL,
				posts text NOT NULL,
				googleplaceid varchar(200) DEFAULT '' NOT NULL,
				gplaceorsearch varchar(10) DEFAULT '' NOT NULL,
				pluginversion varchar(10) DEFAULT '' NOT NULL,
				UNIQUE KEY id (id),
				PRIMARY KEY (id)
			) $charset_collate;";
			dbDelta( $sql_reviewfunnel );
			
			$table_name_getapps = $wpdb->prefix . 'wpfb_getapps_forms';
			$sql_reviewfunnel = "CREATE TABLE $table_name_getapps (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				title varchar(140) DEFAULT '' NOT NULL,
				reviewlistpageid varchar(300) DEFAULT '' NOT NULL,
				page_id varchar(200) DEFAULT '' NOT NULL,
				site_type varchar(20) DEFAULT '' NOT NULL,
				url varchar(700) DEFAULT '' NOT NULL,
				cron varchar(3) DEFAULT '' NOT NULL,
				last_ran int(12) NOT NULL,
				created_time_stamp int(12) NOT NULL,
				blocks varchar(4) DEFAULT '' NOT NULL,
				last_name varchar(7) DEFAULT '' NOT NULL,
				sortoption varchar(10) DEFAULT '' NOT NULL,
				profile_img varchar(7) DEFAULT '' NOT NULL,
				langcode varchar(7) DEFAULT '' NOT NULL,
				rectostar varchar(3) DEFAULT '' NOT NULL,
				categories text NOT NULL,
				posts text NOT NULL,
				crawlserver varchar(7) DEFAULT '' NOT NULL,
				UNIQUE KEY id (id),
				PRIMARY KEY (id)
			) $charset_collate;";
			dbDelta( $sql_reviewfunnel );
			
			$table_name_notiform = $wpdb->prefix . 'wpfb_nofitifcation_forms';
			$sql_reviewfunnel = "CREATE TABLE $table_name_notiform (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				title varchar(200) DEFAULT '' NOT NULL,
				source_page text NOT NULL,
				site_type text NOT NULL,
				created_time_stamp int(12) NOT NULL,
				rate_op varchar(10) DEFAULT '' NOT NULL,
				rate_val varchar(1) DEFAULT '' NOT NULL,
				email text NOT NULL,
				email_subject text NOT NULL,
				email_first_line text NOT NULL,
				enable varchar(3) DEFAULT '' NOT NULL,
				UNIQUE KEY id (id),
				PRIMARY KEY (id)
			) $charset_collate;";
			dbDelta( $sql_reviewfunnel );
			
			$table_name_getapps = $wpdb->prefix . 'wpfb_gettwitter_forms';
			$sql_reviewfunnel = "CREATE TABLE $table_name_getapps (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				title varchar(140) DEFAULT '' NOT NULL,
				site_type varchar(20) DEFAULT '' NOT NULL,
				query text DEFAULT '' NOT NULL,
				endpoint varchar(3) DEFAULT '' NOT NULL,
				last_ran int(12) NOT NULL,
				created_time_stamp int(12) NOT NULL,
				blocks varchar(4) DEFAULT '' NOT NULL,
				profile_img varchar(7) DEFAULT '' NOT NULL,
				categories text NOT NULL,
				posts text NOT NULL,
				UNIQUE KEY id (id),
				PRIMARY KEY (id)
			) $charset_collate;";
			dbDelta( $sql_reviewfunnel );
			
			//moving option wppro_total_avg_reviews to a table so we can access easier
			$table_name_totalavg = $wpdb->prefix . 'wpfb_total_averages';
			$sql_totalavg = "CREATE TABLE $table_name_totalavg (
				btp_id varchar(150) DEFAULT '' NOT NULL,
				btp_name varchar(150) DEFAULT '' NOT NULL,
				btp_type varchar(10) DEFAULT '' NOT NULL,
				pagetype varchar(100) DEFAULT '' NOT NULL,
				pagetypedetails text NOT NULL,
				total_indb varchar(10) DEFAULT '' NOT NULL,
				total varchar(10) DEFAULT '' NOT NULL,
				avg_indb varchar(10) DEFAULT '' NOT NULL,
				avg varchar(10) DEFAULT '' NOT NULL,
				numr1 varchar(10) DEFAULT '' NOT NULL,
				numr2 varchar(10) DEFAULT '' NOT NULL,
				numr3 varchar(10) DEFAULT '' NOT NULL,
				numr4 varchar(10) DEFAULT '' NOT NULL,
				numr5 varchar(10) DEFAULT '' NOT NULL,
				last_modified DATETIME NOT NULL ON UPDATE CURRENT_TIMESTAMP,
				UNIQUE KEY id (btp_id),
				PRIMARY KEY (btp_id)
			) $charset_collate;";
			dbDelta( $sql_totalavg );
			
			//create directories in uploads folder for avatar and cache_settings
			$upload = wp_upload_dir();
			$upload_dir = $upload['basedir'];
			$upload_dir_wprev = $upload_dir . '/wprevslider';
			//check folder permissions, delete if false
			if (is_dir($upload_dir_wprev)) {
				$dir_writable = substr(sprintf('%o', fileperms($upload_dir_wprev)), -4) == "0775" ? true : false;
				if($dir_writable==false){
					//delete the directory and sub directories
					self::wpprorev_rmrf($upload_dir_wprev);
				}
			}
			if (! is_dir($upload_dir_wprev)) {
			   @mkdir( $upload_dir_wprev, 0775 );
			   //chmod($upload_dir_wprev, 0775);
			}
			$upload_dir_wprev_avatars = $upload_dir . '/wprevslider/avatars';
			if (! is_dir($upload_dir_wprev_avatars)) {
			   @mkdir( $upload_dir_wprev_avatars, 0775 );
			   //chmod($upload_dir_wprev_avatars, 0775);
			}
			$upload_dir_wprev_cache = $upload_dir . '/wprevslider/cache';
			if (! is_dir($upload_dir_wprev_cache)) {
			   @mkdir( $upload_dir_wprev_cache, 0775 );
			   //chmod($upload_dir_wprev_cache, 0775);
			}
	

		//check for fb app id from free plugin and save it 
		
		$paidoptions = get_option( 'wprevpro_options' );
		$freeoptions = get_option( 'wpfbr_options' );
		if(!$paidoptions && $freeoptions){
			update_option( 'wprevpro_options', $freeoptions );
		}
		

	}
	
		//used to remove directories on uninstall
	public static function wpprorev_rmrf( $dir )
	{
		foreach ( glob( $dir ) as $file ) {
			
			if ( is_dir( $file ) ) {
				self::wpprorev_rmrf( "{$file}/*" );
				rmdir( $file );
			} else {
				unlink( $file );
			}
		
		}
	}


}
