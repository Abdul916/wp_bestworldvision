<?php

/**
 * The admin-specific functionality of the plugin. Builds the pages and saves settings
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    WP_Review_Pro
 * @subpackage WP_Review_Pro/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WP_Review_Pro
 * @subpackage WP_Review_Pro/admin
 * @author     Your Name <email@example.com>
 */
class WP_Review_Pro_Admin {

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
	 * The token of the plugin.
	 *
	 * @since    11.6.0
	 * @access   protected
	 * @var      string    $_token   The token of the plugin.
	 */
	private $_token;	//must declare this now in php 8.2
	private $intpluginversion;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugintoken       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugintoken, $version ) {

		$this->_token = $plugintoken;
		$this->version = $version;
		$this->intpluginversion = str_replace(".","",$version);
		//for testing==============
		$this->version = time();
		//===================
	}
		/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_token() {
		return $this->_token;
	}
	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
	

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WP_Review_Pro_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WP_Review_Pro_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		//only load for this plugin
		if(isset($_GET['page'])){
			if($_GET['page']=="wp_pro-getrevs" || $_GET['page']=="wp_pro-getrevschoice" || $_GET['page']=="wp_pro-reviews" || $_GET['page']=="wp_pro-templates_posts" || $_GET['page']=="wp_pro-reviewfunnel" || $_GET['page']=="wp_pro-get_apps" || $_GET['page']=="wp_pro-get_twitter" || $_GET['page']=="wp_pro-get_woo" ||$_GET['page']=="wp_pro-forum" || $_GET['page']=="wp_pro-settings-account" || $_GET['page']=="wp_pro-settings-contact" || $_GET['page']=="wp_pro-settings-pricing" || $_GET['page']=="wp_pro-notifications" || $_GET['page']=="wp_pro-badges" || $_GET['page']=="wp_pro-forms" || $_GET['page']=="wp_pro-float" || $_GET['page']=="wp_pro-analytics" || $_GET['page']=="wp_pro-welcome"){
				wp_enqueue_style( $this->_token, plugin_dir_url( __FILE__ ) . 'css/wprevpro_admin.css', array(), $this->version, 'all' );
				wp_enqueue_style( $this->_token."_wprevpro_w3", plugin_dir_url(dirname(__FILE__)) . 'public/css/wprevpro_w3.css', array(), $this->version, 'all' );
			}
			//load template styles for wp_pro-templates_posts page
			if($_GET['page']=="wp_pro-templates_posts"){
				//enque template styles for preview
			
				wp_register_style( 'wprevpro_w3', plugin_dir_url(dirname(__FILE__)). 'public/css/wprevpro_w3.css', array(), $this->version, 'all' );
				wp_enqueue_style( 'wprevpro_w3' );
				//register slider stylesheet
				//wp_register_style( 'unslider', plugin_dir_url(dirname(__FILE__)). 'public/css/wprs_unslider.css', array(), $this->version, 'all' );
				//wp_enqueue_style( 'unslider' );
				wp_enqueue_style( $this->_token."_select2", plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), $this->version, 'all' );
				
			}

			if($_GET['page']=="wp_pro-badges" || $_GET['page']=="wp_pro-float"){
				//style for float page only
				wp_register_style( 'wprevpro_w3', plugin_dir_url(dirname(__FILE__)). 'public/css/wprevpro_w3.css', array(), $this->version, 'all' );
				wp_enqueue_style( 'wprevpro_w3' );
				
				//register slider stylesheet
				wp_register_style( 'unslider', plugin_dir_url(dirname(__FILE__)) . 'public/css/wprs_unslider.css', array(), $this->version, 'all' );
				wp_register_style( 'unslider-dots', plugin_dir_url(dirname(__FILE__)) . 'public/css/wprs_unslider-dots.css', array(), $this->version, 'all' );

				wp_enqueue_style( 'unslider' );
				wp_enqueue_style( 'unslider-dots' );
			}
			
			//load template styles for get revs pages
			if($_GET['page']=="wp_pro-getrevs" || $_GET['page']=="wp_pro-getrevschoice" || $_GET['page']=="wp_pro-get_woo" || $_GET['page']=="wp_pro-reviewfunnel" || $_GET['page']=="wp_pro-get_apps"|| $_GET['page']=="wp_pro-get_twitter" || $_GET['page']=="wp_pro-analytics" || $_GET['page']=="wp_pro-welcome" || $_GET['page']=="wp_pro-notifications"){
				//enque styles for w3 css
				wp_enqueue_style( $this->_token."_w3", plugin_dir_url( __FILE__ ) . 'css/w3.css', array(), $this->version, 'all' );
			}
			//welcome only
			if($_GET['page']=="wp_pro-welcome"){
				wp_register_style( 'chart-min', plugin_dir_url( __FILE__ ) . 'css/Chart.min.css', array(), $this->version, 'all' );
				wp_enqueue_style( 'chart-min' );
			}
			//analytics only page
			if($_GET['page']=="wp_pro-analytics"){
				wp_register_style( 'chart-min', plugin_dir_url( __FILE__ ) . 'css/Chart.min.css', array(), $this->version, 'all' );
				wp_enqueue_style( 'chart-min' );
				//enque styles for w3 css
				wp_enqueue_style( $this->_token."_daterangepicker", plugin_dir_url( __FILE__ ) . 'css/daterangepicker.css', array(), $this->version, 'all' );
				wp_enqueue_style( $this->_token."_select2", plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), $this->version, 'all' );
				wp_enqueue_style( $this->_token."_jqcloud", plugin_dir_url( __FILE__ ) . 'css/jqcloud.css', array(), $this->version, 'all' );
			}
			//notifications only
			if($_GET['page']=="wp_pro-notifications"){
				wp_enqueue_style( $this->_token."_select2", plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), $this->version, 'all' );
			}
			
			//review list
			if($_GET['page']=="wp_pro-reviews"){
				wp_enqueue_style( $this->_token."lity_min", plugin_dir_url( __FILE__ ) . 'css/lity.min.css', array(), $this->version, 'all' );
			}
			
			//get reviews
			//for fancy tables
			if($_GET['page']=="wp_pro-getrevs"){
				wp_enqueue_style( "dataTables", 'https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css', array(), '1.13.4', 'all' );
			}
			
		
		
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WP_Review_Pro_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WP_Review_Pro_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		

		//scripts for all pages in this plugin
		if(isset($_GET['page'])){
			if($_GET['page']=="wp_pro-getrevs" || $_GET['page']=="wp_pro-getrevschoice" || $_GET['page']=="wp_pro-reviews" || $_GET['page']=="wp_pro-templates_posts" || $_GET['page']=="wp_pro-get_woo" || $_GET['page']=="wp_pro-notifications" || $_GET['page']=="wp_pro-badges" || $_GET['page']=="wp_pro-reviewfunnel" || $_GET['page']=="wp_pro-get_apps" || $_GET['page']=="wp_pro-get_twitter" || $_GET['page']=="wp_pro-forms" || $_GET['page']=="wp_pro-float" || $_GET['page']=="wp_pro-analytics" || $_GET['page']=="wp_pro-welcome"){
				//pop-up script
				wp_register_script( 'simple-popup-js',  plugin_dir_url( __FILE__ ) . 'js/wprevpro_simple-popup.min.js' , '', $this->version, false );
				wp_enqueue_script( 'simple-popup-js' );
				
			}
		}
		
		/*
		//scripts for get google reviews page
		if(isset($_GET['page'])){
			if($_GET['page']=="wp_pro-googlesettings" ){
				$options = get_option('wpfbr_google_options');
				$default_key = "AIzaSyCMJzaJssj4ugQjJ0YZCAwFfUcagsmxncQ";
				
				if(empty( $options['google_api_key'] )){
					$google_api_key = $default_key;
				} 
				if (isset($options['select_google_api']) && $options['select_google_api']=="mine"){
					$google_api_key =$options['google_api_key'];
				} else {
					$google_api_key = $default_key;
				}

				if( ! empty($google_api_key ) )
				{
				wp_register_script( 'wpfbr_google_places_gmaps', 'https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=' . esc_attr( $google_api_key ), array( 'jquery' ) );
				wp_enqueue_script( 'wpfbr_google_places_gmaps' );
				}

				//admin js
				wp_enqueue_script( $this->_token, plugin_dir_url( __FILE__ ) . 'js/wprev_getgoogle.js', array( 'jquery' ), $this->version, false );
				//used for ajax
				wp_localize_script($this->_token, 'adminjs_script_vars', 
					array(
						'wpfb_nonce'=> wp_create_nonce('randomnoncestring'),
						'ajax_url' => admin_url( 'admin-ajax.php' ),
						'popuptitle' => esc_html__('Downloading Reviews', 'wp-review-slider-pro'),
						'popupmsg' => sprintf(__('Retrieving Google Business reviews and saving them to your Wordpress database. This can take a few seconds for each location. This Method will only return your 5 Most Helpful reviews. Use the Get Reviews > Review Funnel page to download all reviews. %1$smore info%2$s', 'wp-review-slider-pro'),'<a href="https:\/\/wpreviewslider.userecho.com/knowledge-bases/2/articles/323-google-only-downloads-5-reviews" target="_blank">','</a>'),
						'i18n'     => array( 'google_auth_error' => 
						sprintf( 
						__( '%1$sGoogle API Error:%2$s Invalid API Key. Please inspect the console for more details. Right-click on the page > inspect, look for the console window. %3$sHow To Create an API Key%4$s', 'wp-review-slider-pro' ), 
						'<strong>', 
						'</strong>', 
						'<br><a href="https://ljapps.com/google-places-api-key/" target="_blank" class="new-window">', 
						'</a>' 
						) )
					)
				);
				//thickbox
				wp_enqueue_script('thickbox');
				wp_enqueue_style('thickbox');
					
			}
		}
		*/
		
		//common localized variables arra
		//======delete this after move to get_apps
		/*
		$script_vars_array = array(
					'wpfb_nonce'=> wp_create_nonce('randomnoncestring'),
					'popuptitle' => esc_html__('Downloading Reviews', 'wp-review-slider-pro'),
					'popupmsg' => esc_html__('Retrieving reviews and saving them to your Wordpress database.', 'wp-review-slider-pro'),
					'popupmsg1' => esc_html__('This should take less than 10 seconds per a url.', 'wp-review-slider-pro'),
					'popupmsg2' => esc_html__('Communicating with server...', 'wp-review-slider-pro'),
					'msg' => esc_html__('Downloaded so far:', 'wp-review-slider-pro'),
					'msg1' => esc_html__('Finished! The reviews should now show up on the Review List page of the plugin.', 'wp-review-slider-pro'),
					'msg2' => esc_html__('No new reviews found.', 'wp-review-slider-pro'),
					'Retrieve_Reviews' => esc_html__('Retrieve Reviews', 'wp-review-slider-pro'),
					'Yes' => esc_html__('Yes', 'wp-review-slider-pro'),
					'Oops' => esc_html__('Oops, no Facebook pages found. Please try again or contact us for help.', 'wp-review-slider-pro'),
					);
		
		//scripts for get fb reviews page
		
		if(isset($_GET['page'])){
			if($_GET['page']=="wp_pro-settings"){
				//admin js
				wp_enqueue_script( $this->_token, plugin_dir_url( __FILE__ ) . 'js/wprevpro_admin.js', array( 'jquery' ), $this->version, false );
				//used for ajax
				wp_localize_script($this->_token, 'adminjs_script_vars',$script_vars_array);
			}
		}
		*/
		
		//=================
		
		//scripts for get Yelp reviews page
/*
		if(isset($_GET['page'])){
			if($_GET['page']=="wp_pro-get_yelp"){
				//admin js
				wp_enqueue_script( $this->_token, plugin_dir_url( __FILE__ ) . 'js/wprev_getyelp.js', array( 'jquery' ), $this->version, false );
				//used for ajax
				wp_localize_script($this->_token, 'adminjs_script_vars', $script_vars_array);
			}
		}
		*/
		
		
		//scripts for get Tripadvisor reviews page
		/*
		if(isset($_GET['page'])){
			if($_GET['page']=="wp_pro-get_tripadvisor"){
				//admin js
				wp_enqueue_script( $this->_token, plugin_dir_url( __FILE__ ) . 'js/wprev_gettripadvisor.js', array( 'jquery' ), $this->version, false );
				//used for ajax
				wp_localize_script($this->_token, 'adminjs_script_vars', $script_vars_array);
			}
		}
		*/
		
		//scripts for get Airbnb reviews page
		/*
		if(isset($_GET['page'])){
			if($_GET['page']=="wp_pro-get_airbnb"){
				//admin js
				wp_enqueue_script( $this->_token, plugin_dir_url( __FILE__ ) . 'js/wprev_getairbnb.js', array( 'jquery' ), $this->version, false );
				//used for ajax
				wp_localize_script($this->_token, 'adminjs_script_vars',$script_vars_array);
			}
		}
		*/
		
		//scripts for get Vrbo reviews page
		/*
		if(isset($_GET['page'])){
			if($_GET['page']=="wp_pro-get_vrbo"){
				//admin js
				wp_enqueue_script( $this->_token, plugin_dir_url( __FILE__ ) . 'js/wprev_getvrbo.js', array( 'jquery' ), $this->version, false );
				//used for ajax
				wp_localize_script($this->_token, 'adminjs_script_vars',$script_vars_array);
			}
		}
		*/

		//scripts for get WooCommerce reviews page
		if(isset($_GET['page'])){
			if($_GET['page']=="wp_pro-get_woo"){
				//admin js
				wp_enqueue_script( $this->_token, plugin_dir_url( __FILE__ ) . 'js/wprev_getwoo.js', array( 'jquery' ), $this->version, false );
				//used for ajax
				wp_localize_script($this->_token, 'adminjs_script_vars', 
					array(
					'wpfb_nonce'=> wp_create_nonce('randomnoncestring'),
					'popuptitle' => esc_html__('Syncing Reviews', 'wp-review-slider-pro'),
					'popupmsg' => esc_html__('Copying WooCommerce reviews in to this plugin database.', 'wp-review-slider-pro'),
					)
				);
			}
		}
		
		
		//scripts for review list page
		if(isset($_GET['page'])){
			if($_GET['page']=="wp_pro-badges" || $_GET['page']=="wp_pro-reviews" || $_GET['page']=="wp_pro-reviewfunnel" || $_GET['page']=="wp_pro-analytics"){
				global $wpdb;
				$reviews_table_name = $wpdb->prefix . 'wpfb_reviews';
				$tempquery = "select type from ".$reviews_table_name." group by type";
				$typearray = $wpdb->get_col($tempquery);
			}
			if($_GET['page']=="wp_pro-reviews"){
				$tempquery = "select pagename from ".$reviews_table_name." group by pagename";
				$pagenamearray = $wpdb->get_col($tempquery);
				//$typearray = unserialize(WPREV_TYPE_ARRAY);
				//admin js
				wp_enqueue_script('wprevpro_review_list_page-js', plugin_dir_url( __FILE__ ) . 'js/wprevpro_review_list_page.js', array( 'jquery','media-upload','thickbox' ), $this->version, false );
				//used for ajax
				wp_localize_script('wprevpro_review_list_page-js', 'adminjs_script_vars', 
					array(
					'wpfb_nonce'=> wp_create_nonce('randomnoncestring'),
					'wpfb_ajaxurl' => admin_url( 'admin-ajax.php' ),
					'wprevpluginsurl' => WPREV_PLUGIN_URL,
					'globalwprevtypearray' => json_encode($typearray),
					'pagenamearray' => json_encode($pagenamearray),
					'popuptitle' => esc_html__('Tips', 'wp-review-slider-pro'),
					'popupmsg' => __('<p>- If you\'re using the pro version you can hide certain reviews by clicking the <i class="dashicons dashicons-visibility text_green" aria-hidden="true"></i> in the table below. There are also ways to hide certain types of reviews under the Templates page.</p><p><b>- Remove Reviews:</b> Allows you to delete reviews in your Wordpress database and start over. It Does NOT affect your reviews on the source site like Facebook.</p><p><b>- Manually Add Review:</b> Allows you to manaully insert a review in to your Wordpress database.</p><p><b>- Download a CSV File:</b> Save a CSV file to your computer containing all the reviews in this table.</p>', 'wp-review-slider-pro'),
					'popuptitle1' => esc_html__('Are you sure?', 'wp-review-slider-pro'),
					'popupmsg1' => __('<p>This will delete reviews in your Wordpress database including the ones you manually entered. It Does NOT affect your reviews on the original Social Pages.</p><p>If you are using a template where you individually selected the reviews, then you will need to redo it.</p><p><b>Remove by type...</b>', 'wp-review-slider-pro'),
					'popupmsg3' => __('<b>Remove by page...</b>', 'wp-review-slider-pro'),
					'all_reviews' => esc_html__('All Reviews', 'wp-review-slider-pro'),
					'popuptitle2' => esc_html__('Upgrade Needed', 'wp-review-slider-pro'),
					'popupmsg2' => __('Please upgrade to the Pro Version of this Plugin to access this feature.', 'wp-review-slider-pro'),
					'upgrade_here' => esc_html__('Upgrade Here', 'wp-review-slider-pro'),
					'msg1' => esc_html__('Oops! Unable to hide this review. Please contact support.', 'wp-review-slider-pro'),
					'msg2' => esc_html__('Oops! Unable to delete this review. Please contact support.', 'wp-review-slider-pro'),
					'Delete' => esc_html__('Delete', 'wp-review-slider-pro'),
					'Edit' => esc_html__('Edit', 'wp-review-slider-pro'),
					'Copy' => esc_html__('Copy', 'wp-review-slider-pro'),
					'Review_Response' => esc_html__('Review Response', 'wp-review-slider-pro'),
					'msg3' => sprintf(__( 'No reviews found. Please visit the %1$sGet Reviews%2$s page to retrieve reviews. If you\'ve already downloaded reviews and they are not showing, then go de-activate and re-activate this plugin. That will force the database tables to update. ', 'wp-review-slider-pro' ),'<a href="?page=wp_pro-getrevs">','</a>'),
					'Total_Reviews' => esc_html__('Total Reviews', 'wp-review-slider-pro'),
					'msg4' => esc_html__('Oops! Unable to update the sort weight. Please contact support.', 'wp-review-slider-pro'),
					)
				);

				
				//currently has select cat id script
				wp_register_script( 'common-to-admin',  plugin_dir_url( __FILE__ ) . 'js/wprevpro_commontoadmin.js' , '', $this->version, false );
				wp_enqueue_script( 'common-to-admin' );
				wp_localize_script('common-to-admin', 'adminjs_script_vars_cta', 
						array(
						'msg1' => esc_html__('Pick the Categories', 'wp-review-slider-pro'),
						'msg2' => esc_html__('Pick the Post IDs', 'wp-review-slider-pro'),
						'msg3' => esc_html__('Pick the Pages', 'wp-review-slider-pro'),
						'msg4' => esc_html__('Oops, please contact support. Error getting', 'wp-review-slider-pro'),
						)
					);
				
				//for media popup
				wp_register_script( 'wprev_lity',  plugin_dir_url( __FILE__ ) . 'js/lity.js' , '', $this->version, false );
				wp_enqueue_script( 'wprev_lity' );
				
				
 				wp_enqueue_script('thickbox');
				wp_enqueue_style('thickbox');
		 
				wp_enqueue_script('media-upload');
				wp_enqueue_script('wptuts-upload');

			}
			
			//script for notifications/settings page
			if($_GET['page']=="wp_pro-notifications"){
					wp_enqueue_script('wprevpro_notifications_page-js', plugin_dir_url( __FILE__ ) . 'js/wprevpro_notifications_page.js', array( 'jquery'), $this->version, false );
					//used for ajax
					wp_localize_script('wprevpro_notifications_page-js', 'adminjs_script_vars', 
						array(
						'wpfb_nonce'=> wp_create_nonce('randomnoncestring'),
						'wpfb_ajaxurl' => admin_url( 'admin-ajax.php' ),
						'wprevpluginsurl' => WPREV_PLUGIN_URL,
						'Location_Filter' => esc_html__('Location Filter', 'wp-review-slider-pro'),
						'Type_Filter' => esc_html__('Type Filter', 'wp-review-slider-pro'),
						'msg1' => esc_html__('Error accessing language function via ajax. Please refresh page and try again or contact support.', 'wp-review-slider-pro'),
						'msg2' => esc_html__('Detecting the language of', 'wp-review-slider-pro'),
						'msg3' => esc_html__('total reviews', 'wp-review-slider-pro'),
						'msg4' => esc_html__('Can\'t determine language of this review:', 'wp-review-slider-pro'),
						'msg5' => esc_html__('Checking next reviews...', 'wp-review-slider-pro'),
						'msg6' => esc_html__('Finished checking all reviews.', 'wp-review-slider-pro'),
						'msg7' => esc_html__('Done. No reviews found that do not already have language specified. The language code should be displayed on the Review List page under the Word Count column.', 'wp-review-slider-pro'),
						'msg8' => esc_html__('Error returning json object. Please try again or contact us and copy and send us the following:', 'wp-review-slider-pro'),
						'Error' => esc_html__('Error', 'wp-review-slider-pro'),
						'Updated' => esc_html__('Updated', 'wp-review-slider-pro'),
						'reviews' => esc_html__('reviews', 'wp-review-slider-pro'),
						'Role_Filter' => esc_html__('Select Roles', 'wp-review-slider-pro'),
						)
					);
					
					//pretty select
				wp_register_script( $this->_token.'_select2',  plugin_dir_url( __FILE__ ) . 'js/select2.min.js' , '', $this->version, false );
				wp_enqueue_script( $this->_token.'_select2' );
					
				wp_enqueue_script('thickbox');
				wp_enqueue_style('thickbox');
				
			}
			
			//scripts for templates posts page
			if($_GET['page']=="wp_pro-templates_posts"){
				$svgarray = unserialize(WPREV_SVG_ARRAY);
				//admin js
				wp_enqueue_script('wprevpro_templates_posts_page-js', plugin_dir_url( __FILE__ ) . 'js/wprevpro_templates_posts_page.js', array( 'jquery','wp-color-picker','thickbox' ), $this->version, false );
				//used for ajax
				wp_localize_script('wprevpro_templates_posts_page-js', 'adminjs_script_vars', 
					array(
					'wpfb_nonce'=> wp_create_nonce('randomnoncestring'),
					'pluginsUrl' => WPREV_PLUGIN_URL,
					'globalwprevsvgarray' => json_encode($svgarray),
					'popuptitle' => esc_html__('Tips', 'wp-review-slider-pro'),
					'popupmsg' => __('This page will let you create multiple Reviews Templates that you can then add to your Posts or Pages via a shortcode or template function.', 'wp-review-slider-pro'),
					'popuptitle1' => esc_html__('Widget Instructions', 'wp-review-slider-pro'),
					'popupmsg1' => __('To display this in your Sidebar or other Widget areas, add the WP Reviews widget under Appearance > Widgets, and then select this template in the drop down.', 'wp-review-slider-pro'),
					'popuptitle2' => esc_html__('How to Display', 'wp-review-slider-pro'),
					'popupmsg2a' => __('Enter this shortcode on a post/page or text widget:', 'wp-review-slider-pro'),
					'popupmsg2b' => __('Or you can use one of the following php codes in your template:', 'wp-review-slider-pro'),
					'or' => esc_html__('or', 'wp-review-slider-pro'),
					'more_info' => esc_html__('more info', 'wp-review-slider-pro'),
					'Review_Selected' => esc_html__('Review Selected', 'wp-review-slider-pro'),
					'Reviews_Selected' => esc_html__('Reviews Selected', 'wp-review-slider-pro'),
					'Show' => esc_html__('Show', 'wp-review-slider-pro'),
					'choose_site_types' => esc_html__('icons to show...', 'wp-review-slider-pro'),	
					
					)
				);
				//currently has select cat id script
				wp_register_script( 'common-to-admin',  plugin_dir_url( __FILE__ ) . 'js/wprevpro_commontoadmin.js' , '', $this->version, false );
				wp_enqueue_script( 'common-to-admin' );
				wp_localize_script('common-to-admin', 'adminjs_script_vars_cta', 
						array(
						'msg1' => esc_html__('Pick the Categories', 'wp-review-slider-pro'),
						'msg2' => esc_html__('Pick the Post IDs', 'wp-review-slider-pro'),
						'msg3' => esc_html__('Pick the Pages', 'wp-review-slider-pro'),
						'msg4' => esc_html__('Oops, please contact support. Error getting', 'wp-review-slider-pro'),
						)
					);
				
 				wp_enqueue_script('thickbox');
				wp_enqueue_style('thickbox');
				
				//add color picker here
				wp_enqueue_style( 'wp-color-picker' );
				//enque alpha color add-on wprevpro-wp-color-picker-alpha.js
				wp_register_script( 'wp-color-picker-alpha3', plugin_dir_url( __FILE__ ) . 'js/wprevpro-wp-color-picker-alpha.js', array( 'wp-color-picker' ), $this->version, false );
				wp_enqueue_script( 'wp-color-picker-alpha3' );

				
				//pretty select
				wp_register_script( $this->_token.'_select2',  plugin_dir_url( __FILE__ ) . 'js/select2.min.js' , '', $this->version, false );
				wp_enqueue_script( $this->_token.'_select2' );
				
			}
			
			//scripts for create badges
			if($_GET['page']=="wp_pro-badges"){
			//$typearray = unserialize(WPREV_TYPE_ARRAY);
			$svgarray = unserialize(WPREV_SVG_ARRAY);
						
				//admin js
				wp_enqueue_script('wprevpro_badges_page-js', plugin_dir_url( __FILE__ ) . 'js/wprevpro_badges_page.js', array( 'jquery','wp-color-picker' ), $this->version, false );
				//used for ajax
				wp_localize_script('wprevpro_badges_page-js', 'adminjs_script_vars', 
					array(
					'wpfb_nonce'=> wp_create_nonce('randomnoncestring'),
					'pluginsUrl' => WPREV_PLUGIN_URL,
					'globalwprevtypearray' => json_encode($typearray),
					'globalwprevsvgarray' => json_encode($svgarray),
					'popuptitle' => esc_html__('Tips', 'wp-review-slider-pro'),
					'popupmsg' => __('This page will let you create multiple Badges that you can then add to your site via a shortcode, or widget. A Badge is a way to display a quick summary of your social reviews.', 'wp-review-slider-pro'),
					'popuptitle1' => esc_html__('Widget Instructions', 'wp-review-slider-pro'),
					'popupmsg1' => __('To display this in your Sidebar or other Widget areas, add the WP Reviews Badges widget under Appearance > Widgets, and then select this badge in the drop down.', 'wp-review-slider-pro'),
					'popuptitle2' => esc_html__('How to Display', 'wp-review-slider-pro'),
					'popupmsg2' => __('Enter this shortcode on a post/page or a text widget:', 'wp-review-slider-pro'),
					'popupmsg3' => __('Or you can add one of the following php codes to your theme:', 'wp-review-slider-pro'),
					'popupmsg4' => __('If you would like to output just the total or average click', 'wp-review-slider-pro'),
					'popupmsg5' => __('here', 'wp-review-slider-pro'),
					'popupmsg6' => __('You can now use parameters in the shortcode, more info ', 'wp-review-slider-pro'),
					'msg1' => esc_html__('Stars - Based on', 'wp-review-slider-pro'),
					'User_Reviews' => esc_html__('User Reviews', 'wp-review-slider-pro'),
					'reviews' => esc_html__('reviews', 'wp-review-slider-pro'),
					'msg2' => esc_html__('Please enter a title.', 'wp-review-slider-pro'),
					'msg3' => esc_html__('Please select at least one location.', 'wp-review-slider-pro'),
					'msg4' => esc_html__('Choose the review orgin page or leave blank to select all of this type.', 'wp-review-slider-pro'),
					
					)
				);
				
								//currently has select cat id script
				wp_register_script( 'common-to-admin',  plugin_dir_url( __FILE__ ) . 'js/wprevpro_commontoadmin.js' , '', $this->version, false );
				wp_enqueue_script( 'common-to-admin' );
				wp_localize_script('common-to-admin', 'adminjs_script_vars_cta', 
						array(
						'msg1' => esc_html__('Pick the Categories', 'wp-review-slider-pro'),
						'msg2' => esc_html__('Pick the Post IDs', 'wp-review-slider-pro'),
						'msg3' => esc_html__('Pick the Pages', 'wp-review-slider-pro'),
						'msg4' => esc_html__('Oops, please contact support. Error getting', 'wp-review-slider-pro'),
						)
					);
					
 				wp_enqueue_script('thickbox');
				wp_enqueue_style('thickbox');
		 
				wp_enqueue_script('media-upload');
				wp_enqueue_script('wptuts-upload');
				
				//add color picker here
				wp_enqueue_style( 'wp-color-picker' );
				//enque alpha color add-on wprevpro-wp-color-picker-alpha.js
				wp_register_script( 'wp-color-picker-alpha3', plugin_dir_url( __FILE__ ) . 'js/wprevpro-wp-color-picker-alpha.js', array( 'wp-color-picker' ), $this->version, false );
				wp_enqueue_script( 'wp-color-picker-alpha3' );
				
				//add some public scripts for the preview
				wp_enqueue_script( $this->_token."_unslider-min", plugin_dir_url(dirname(__FILE__)) . 'public/js/wprs-unslider.min.js', array( 'jquery' ), $this->version, false );
				wp_enqueue_script( $this->_token."_plublic-min",plugin_dir_url(dirname(__FILE__)) . 'public/js/wprev-public.js', array( 'jquery' ), $this->version, false );
				//used for ajax
				wp_localize_script($this->_token."_plublic-min", 'wprevpublicjs_script_vars', 
					array(
					'wpfb_nonce'=> wp_create_nonce('randomnoncestring'),
					'wpfb_ajaxurl' => admin_url( 'admin-ajax.php' ),
					'wprevpluginsurl' => WPREV_PLUGIN_URL,
					)
				);

			}
			//scripts for reviewfunnel
			if($_GET['page']=="wp_pro-reviewfunnel"){
			//$typearray = unserialize(WPREV_TYPE_ARRAY);
				//admin js
				wp_enqueue_script('wprevpro_reviewfunnel_page-js', plugin_dir_url( __FILE__ ) . 'js/wprevpro_reviewfunnel_page.js', array( 'jquery' ), $this->version, false );
				//used for ajax
				wp_localize_script('wprevpro_reviewfunnel_page-js', 'adminjs_script_vars', 
					array(
					'wpfb_nonce'=> wp_create_nonce('randomnoncestring'),
					'pluginsUrl' => WPREV_PLUGIN_URL,
					'globalwprevtypearray' => json_encode($typearray),
					'popuptitle' => esc_html__('Tips', 'wp-review-slider-pro'),
					'popupmsg' => __('This page will let you create multiple Review Funnels that will allow you to scrape reviews from multiple sites. <br><br><b>Note:</b> Review Funnels use a third party service to download reviews, so you are limited to 2,000 reviews per a year per a site. You must also have an active license for this plugin.', 'wp-review-slider-pro'),
					'Download_Reviews' => esc_html__('Download Reviews', 'wp-review-slider-pro'),
					'still_working' => esc_html__('still working...', 'wp-review-slider-pro'),
					'msg1' => esc_html__('Error returning reviews for this url, please try again or contact support.', 'wp-review-slider-pro'),
					'msg2' => esc_html__('Please try again. Check console for more details. Error returning json object:', 'wp-review-slider-pro'),
					'msg3' => esc_html__('No new reviews scraped.', 'wp-review-slider-pro'),
					'msg4' => esc_html__('No reviews scraped. Check the review funnel settings.', 'wp-review-slider-pro'),
					'msg5' => esc_html__('No previous scrape jobs found. Use the "Request New Scrape" button above to request one.', 'wp-review-slider-pro'),
					'msg6' => esc_html__('Please try again. Error returning json object:', 'wp-review-slider-pro'),
					'msg7' => esc_html__('Error using ajax to return scrape jobs, please try again or contact support.', 'wp-review-slider-pro'),
					'msg8' => esc_html__('Error returning reviews for this url, please try again or contact support.', 'wp-review-slider-pro'),
					'msg9' => sprintf(__( 'Success! Please check back in a few minutes or use the %1$s below. The job should show up in the list below.', 'wp-review-slider-pro' ),'<span class="dashicons dashicons-update"></span>'),
					'msg10' => esc_html__('Error adding this scrape job. Please try again or contact us and copy and send us the following:', 'wp-review-slider-pro'),
					'msg11' => esc_html__('Error returning json object. Please try again or contact us and copy and send us the following:', 'wp-review-slider-pro'),
					'msg12' => esc_html__('Error using ajax to return reviews, please try again or contact support.', 'wp-review-slider-pro'),
					'msg13' => esc_html__('Error returning reviews for this url, please try again or contact support.', 'wp-review-slider-pro'),
					'msg14' => esc_html__('Reviews returned.', 'wp-review-slider-pro'),
					'msg15' => esc_html__('New Reviews inserted in to your database.', 'wp-review-slider-pro'),
					'msg16' => esc_html__('They should now show up on the Review List page.', 'wp-review-slider-pro'),
					'msg17' => esc_html__('Error: No Reviews returned from this scrape job. Please see javascript console for more details. Right-click > inspect, look for console window.', 'wp-review-slider-pro'),
					'msg18' => esc_html__('Error returning json object:', 'wp-review-slider-pro'),
					'msg19' => esc_html__('Error using ajax to return reviews, please try again or contact support.', 'wp-review-slider-pro'),
					'msg20' => esc_html__('Please enter a unique Place Name.', 'wp-review-slider-pro'),
					'msg21' => esc_html__('Please enter the URL.', 'wp-review-slider-pro'),
					'msg22' => esc_html__('The Review Site does not match the Review URL.', 'wp-review-slider-pro'),
					'msg23' => esc_html__('Please enter at least 2 Google Search Terms.', 'wp-review-slider-pro'),
					'msg24' => esc_html__('Please enter the Number of Reviews to Return.', 'wp-review-slider-pro'),
					'note_appleappstore' => esc_html__('Note: This currently only works for App reviews.', 'wp-review-slider-pro'),
					'note_etsy' => esc_html__('Note: Should be shop URL for Etsy.', 'wp-review-slider-pro'),
					'note_bbb' => esc_html__('Note: BBB should not have /customer-reviews at the end of URL.', 'wp-review-slider-pro'),
					'note_healthgrades' => esc_html__('Note: Healthgrades requires single doctor/physician url, it can not use the group reviews page url.', 'wp-review-slider-pro'),
					'note_airbnb' => esc_html__('Note: Airbnb - Currently only rooms are supported. To download reviews for experiences then you would need to use the Get Reviews > Airbnb.', 'wp-review-slider-pro'),
					'note_googleshopping' => esc_html__('Example URL: https://www.google.com/shopping/ratings/account/metrics?q=outbax.com.au&c=AU&v=17', 'wp-review-slider-pro'),
					'sitetypenote_trustpilot' => sprintf(__( '<b>Warning:</b> Trustpilot does not legally allow you to display their reviews unless you use one of their widgets. They have forced me to remove Trustpilot icons from this plugin. Use at your own risk. More info %1$s. ', 'wp-review-slider-pro' ),'<a href="https://legal.trustpilot.com/for-businesses/legal-brand-guidelines" target="_blank">here</a>'),
					)
				);

				
				
				//currently has select cat id script
				wp_register_script( 'common-to-admin',  plugin_dir_url( __FILE__ ) . 'js/wprevpro_commontoadmin.js' , '', $this->version, false );
				wp_enqueue_script( 'common-to-admin' );
				wp_localize_script('common-to-admin', 'adminjs_script_vars_cta', 
						array(
						'msg1' => esc_html__('Pick the Categories', 'wp-review-slider-pro'),
						'msg2' => esc_html__('Pick the Post IDs', 'wp-review-slider-pro'),
						'msg3' => esc_html__('Pick the Pages', 'wp-review-slider-pro'),
						'msg4' => esc_html__('Oops, please contact support. Error getting', 'wp-review-slider-pro'),
						)
					);
				
			}
			//scripts for getapps page itunes
			if($_GET['page']=="wp_pro-get_apps"){
			//$typearray = unserialize(WPREV_TYPE_ARRAY);
				//admin js
				wp_enqueue_script('wprevpro_get_apps_page-js', plugin_dir_url( __FILE__ ) . 'js/wprevpro_get_apps_page.js', array( 'jquery' ), $this->version, false );
				//used for ajax
				wp_localize_script('wprevpro_get_apps_page-js', 'adminjs_script_vars', 
					array(
					'wpfb_nonce'=> wp_create_nonce('randomnoncestring'),
					'pluginsUrl' => WPREV_PLUGIN_URL,
					'popuptitle' => esc_html__('Tips', 'wp-review-slider-pro'),
					'popupmsg' => __('This page will let you download reviews from multiple source pages.', 'wp-review-slider-pro'),
					'Downloading_Reviews' => esc_html__('Downloading Reviews', 'wp-review-slider-pro'),
					'msg1' => esc_html__('Please enter your Nextdoor cookie value.', 'wp-review-slider-pro'),
					'msg2' => esc_html__('Error returning reviews for this url, please try again or contact support.', 'wp-review-slider-pro'),
					'msg3' => esc_html__('Reviews returned.', 'wp-review-slider-pro'),
					'msg4' => esc_html__('New Reviews inserted in to your database.', 'wp-review-slider-pro'),
					'msg5' => esc_html__('They should now show up on the Review List page.', 'wp-review-slider-pro'),
					'msg6' => esc_html__('No new reviews found. Check the Review List for your reviews.', 'wp-review-slider-pro'),
					'msg7' => esc_html__('Error: No Reviews returned. Please see javascript console for more details. Right-click > inspect, look for console window.', 'wp-review-slider-pro'),
					'msg8' => esc_html__('Error returning json object:', 'wp-review-slider-pro'),
					'msg9' => esc_html__('Error using ajax to return some reviews. This could be a temporary issue. First check the Review List to see if they were downloaded. If they were not, please try again or use a Review Funnel. Contact support with questions.', 'wp-review-slider-pro'),
					'msg20' => esc_html__('Please enter a unique Place Name.', 'wp-review-slider-pro'),
					'msg21' => esc_html__('Please enter the URL.', 'wp-review-slider-pro'),
					'msg22' => esc_html__('Please click the "Save & Test" button above', 'wp-review-slider-pro'),
					'msg23' => esc_html__('There is a limit of 100 locations with this download method.', 'wp-review-slider-pro'),
					'msg24' => esc_html__('Please enter and save API Key.', 'wp-review-slider-pro'),
					'msg25' => esc_html__('Found review page, now looking for reviews...', 'wp-review-slider-pro'),
					'Done' => esc_html__('Done', 'wp-review-slider-pro'),

					'fbmsg' => esc_html__('Downloaded so far:', 'wp-review-slider-pro'),
					'fbmsg1' => esc_html__('Finished! The reviews should now show up on the Review List page of the plugin.', 'wp-review-slider-pro'),
					'fbmsg2' => esc_html__('No new reviews found.', 'wp-review-slider-pro'),
					'fbmsg3' => esc_html__(' Please use the button above to enter or modify your access code.', 'wp-review-slider-pro'),
					'fbmsg4' => esc_html__(' Please select a Facebook page.', 'wp-review-slider-pro'),
					'fbmsg5' => esc_html__('<br>Finished searching for new reviews.', 'wp-review-slider-pro'),
					'fbRetrieve_Reviews' => esc_html__('Retrieve Reviews', 'wp-review-slider-pro'),
					'fbYes' => esc_html__('Yes', 'wp-review-slider-pro'),
					'fbOops' => esc_html__('Oops, no Facebook pages found. Please try again or contact us for help.', 'wp-review-slider-pro'),
					
					
					)
				);
				
				//currently has select cat id script
				wp_register_script( 'common-to-admin',  plugin_dir_url( __FILE__ ) . 'js/wprevpro_commontoadmin.js' , '', $this->version, false );
				wp_enqueue_script( 'common-to-admin' );
				wp_localize_script('common-to-admin', 'adminjs_script_vars_cta', 
						array(
						'msg1' => esc_html__('Pick the Categories', 'wp-review-slider-pro'),
						'msg2' => esc_html__('Pick the Post IDs', 'wp-review-slider-pro'),
						'msg3' => esc_html__('Pick the Pages', 'wp-review-slider-pro'),
						'msg4' => esc_html__('Oops, please contact support. Error getting', 'wp-review-slider-pro'),
						)
					);
				
			}
			
			//scripts for download reviews page
			if($_GET['page']=="wp_pro-getrevs"){
			//$typearray = unserialize(WPREV_TYPE_ARRAY);
				//admin js
				wp_enqueue_script('wprevpro_getrevs_page-js', plugin_dir_url( __FILE__ ) . 'js/wprevpro_getrevs_page.js', array( 'jquery' ), $this->version, false );
				//used for ajax
				wp_localize_script('wprevpro_getrevs_page-js', 'adminjs_script_vars', 
					array(
					'wpfb_nonce'=> wp_create_nonce('randomnoncestring'),
					'pluginsUrl' => WPREV_PLUGIN_URL,
					'popuptitle' => esc_html__('Tips', 'wp-review-slider-pro'),
					'popupmsg' => __('Choose the type of reviews you want to download.', 'wp-review-slider-pro'),
					'choosemethod' => esc_html__('Choose Download Method', 'wp-review-slider-pro'),
					)
				);
				
				wp_enqueue_script('thickbox');
				wp_enqueue_style('thickbox');
				
				//for fancy tables
				wp_enqueue_script( 'dataTables', 'https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js', array('jquery'), '1.13.4', false );
			}
				
				
			//scripts for get_twitter page itunes
			if($_GET['page']=="wp_pro-get_twitter"){
			//$typearray = unserialize(WPREV_TYPE_ARRAY);
				//admin js
				wp_enqueue_script('wprevpro_get_twitter_page-js', plugin_dir_url( __FILE__ ) . 'js/wprevpro_get_twitter_page.js', array( 'jquery' ), $this->version, false );
				//used for ajax
				wp_localize_script('wprevpro_get_twitter_page-js', 'adminjs_script_vars', 
					array(
					'wpfb_nonce'=> wp_create_nonce('randomnoncestring'),
					'pluginsUrl' => WPREV_PLUGIN_URL,
					'popuptitle' => esc_html__('Tips', 'wp-review-slider-pro'),
					'popupmsg' => __('This page will let you search for and download twitter posts.', 'wp-review-slider-pro'),
					'msg1' => esc_html__('Error using ajax to save tweet, please try again or contact support.', 'wp-review-slider-pro'),
					'msg2' => esc_html__('Error using ajax to delete tweet, please try again or contact support.', 'wp-review-slider-pro'),
					'msg3' => esc_html__('Download Tweets', 'wp-review-slider-pro'),
					'msg4' => esc_html__('Error returning tweets for this query, please try again or contact support.', 'wp-review-slider-pro'),
					'msg5' => esc_html__('download tweet', 'wp-review-slider-pro'),
					'msg6' => esc_html__('remove tweet from Plugin', 'wp-review-slider-pro'),
					'msg7' => esc_html__('Error returning json object:', 'wp-review-slider-pro'),
					'msg8' => esc_html__('Error using ajax to return tweets, please try again or contact support.', 'wp-review-slider-pro'),
					'msg9' => esc_html__('Please enter valide API Keys first.', 'wp-review-slider-pro'),
					'msg10' => esc_html__('Please enter a unique Form Title.', 'wp-review-slider-pro'),
					'Likes' => esc_html__('Likes', 'wp-review-slider-pro'),
					'Replies' => esc_html__('Replies', 'wp-review-slider-pro'),
					'Retweets' => esc_html__('Retweets', 'wp-review-slider-pro'),
					)
				);
				
				//currently has select cat id script
				wp_register_script( 'common-to-admin',  plugin_dir_url( __FILE__ ) . 'js/wprevpro_commontoadmin.js' , '', $this->version, false );
				wp_enqueue_script( 'common-to-admin' );
				wp_localize_script('common-to-admin', 'adminjs_script_vars_cta', 
						array(
						'msg1' => esc_html__('Pick the Categories', 'wp-review-slider-pro'),
						'msg2' => esc_html__('Pick the Post IDs', 'wp-review-slider-pro'),
						'msg3' => esc_html__('Pick the Pages', 'wp-review-slider-pro'),
						'msg4' => esc_html__('Oops, please contact support. Error getting', 'wp-review-slider-pro'),
						)
					);
				
			}
			
			
			//scripts for create forms
			if($_GET['page']=="wp_pro-forms"){
			
				//admin js
				wp_enqueue_script('wprevpro_forms_page-js', plugin_dir_url( __FILE__ ) . 'js/wprevpro_forms_page.js', array( 'jquery' ), $this->version, false );
				//used for ajax
				$wprtypearrayrf = unserialize(WPREV_TYPE_ARRAY_RF);
				$wprtypearray = unserialize(WPREV_TYPE_ARRAY);
				$wprtypearraytotal = array_merge($wprtypearrayrf, $wprtypearray);
				//print_r($wprtypearray);
				//print_r($wprtypearraytotal);
				
				wp_localize_script('wprevpro_forms_page-js', 'adminjs_script_vars', 
					array(
					'wpfb_nonce'=> wp_create_nonce('randomnoncestring'),
					'pluginsUrl' => WPREV_PLUGIN_URL,
					'allrevstypearray'=>json_encode($wprtypearraytotal),
					'popuptitle' => esc_html__('Tips', 'wp-review-slider-pro'),
					'popupmsg' => __('This page will let you create multiple front end submission forms that you can then add to your site via a shortcode, or widget. You can use the form to collect reviews or testimonials for your site.', 'wp-review-slider-pro'),
					'popuptitle1' => esc_html__('Widget Instructions', 'wp-review-slider-pro'),
					'popupmsg1' => __('To display this in your Sidebar or other Widget areas, add the WP Reviews Badges widget under Appearance > Widgets, and then select this badge in the drop down.', 'wp-review-slider-pro'),
					'popuptitle2' => esc_html__('How to Display', 'wp-review-slider-pro'),
					'popupmsg2a' => __('Enter this shortcode on a post/page or text widget:', 'wp-review-slider-pro'),
					'popupmsg2b' => __('To create a special pop-up only link, use this shortcode:', 'wp-review-slider-pro'),
					'popupmsg2b1' => __('To make a form auto-popup by url variable, use this shortcode:', 'wp-review-slider-pro'),
					'popupmsg2c' => __('Then the form will only appear if the URL has the wppl=yes or review=1. For example:', 'wp-review-slider-pro'),
					'popupmsg2c1' => __('The form will only auto-popup if the URL has the wppl=auto or review=2. For example:', 'wp-review-slider-pro'),
					'popupmsg2d' => __('This is useful for creating an auto-popup form that you only want to show certain people.', 'wp-review-slider-pro'),
					'popupmsg2d1' => __('The difference between wppl=auto and wppl=yes is that wppl=auto will leave the form button on the page.', 'wp-review-slider-pro'),
					'popupmsg3d1' => __('To create tags based on URL you can use the wrtg variable in the URL.', 'wp-review-slider-pro'),
					'popupmsg3d2' => __('For example if I have a form on this page...', 'wp-review-slider-pro'),
					'popupmsg3d3' => __('That will save "tag1" as a tag with the form submission. You can also do multiple tags...', 'wp-review-slider-pro'),
					'msg1' => esc_html__('Upload Review Logo', 'wp-review-slider-pro'),
					'msg2' => esc_html__('Create New Submission Form:', 'wp-review-slider-pro'),
					'msg3' => esc_html__('Edit Submission Form:', 'wp-review-slider-pro'),
					'msg4' => esc_html__('Error returning form values, please contact support.', 'wp-review-slider-pro'),
					'msg5' => esc_html__('Error using ajax to return form data, please contact support.', 'wp-review-slider-pro'),
					'msg6' => esc_html__('Stars', 'wp-review-slider-pro'),
					'msg7' => esc_html__('Up is 5 stars. Down is 2 stars.', 'wp-review-slider-pro'),
					'msg8' => esc_html__('star rating', 'wp-review-slider-pro'),
					'msg9' => esc_html__('Link', 'wp-review-slider-pro'),
					'msg10' => esc_html__('Button', 'wp-review-slider-pro'),
					'msg11' => esc_html__('Small Icon', 'wp-review-slider-pro'),
					'msg12' => esc_html__('Large Icon', 'wp-review-slider-pro'),
					'msg13' => esc_html__('rest of form when showing links.', 'wp-review-slider-pro'),
					'msg14' => esc_html__('click to open or close', 'wp-review-slider-pro'),
					'msg15' => esc_html__('drag and drop to reorder', 'wp-review-slider-pro'),
					'msg16' => esc_html__('Require this field for form submission.', 'wp-review-slider-pro'),
					'msg17' => esc_html__('Show this label on the form.', 'wp-review-slider-pro'),
					'msg18' => esc_html__('Default Form Value', 'wp-review-slider-pro'),
					'msg19' => esc_html__('Populate the field with this value.', 'wp-review-slider-pro'),
					'msg20' => esc_html__('Default Submit Value', 'wp-review-slider-pro'),
					'msg21' => esc_html__('Save this value with the testimonial if one is not input.', 'wp-review-slider-pro'),
					'msg22' => esc_html__('Hide this field from the form.', 'wp-review-slider-pro'),
					'Show' => esc_html__('Show', 'wp-review-slider-pro'),
					'Hide' => esc_html__('Hide', 'wp-review-slider-pro'),
					'Required' => esc_html__('Required', 'wp-review-slider-pro'),
					'Placeholder' => esc_html__('Placeholder', 'wp-review-slider-pro'),
					'Before' => esc_html__('Before', 'wp-review-slider-pro'),
					'After' => esc_html__('After', 'wp-review-slider-pro'),
					'msg23' => esc_html__('Error saving form values, please contact support.', 'wp-review-slider-pro'),
					'msg24' => esc_html__('Error using ajax to save form data, please contact support.', 'wp-review-slider-pro'),
					'msg25' => esc_html__('Default', 'wp-review-slider-pro'),
					'msg26' => esc_html__('Button 1', 'wp-review-slider-pro'),
					'msg27' => esc_html__('Button 2', 'wp-review-slider-pro')
					)   //  adminjs_script_vars.msg24
				);
 				wp_enqueue_script('jquery-ui-sortable');
				//wp_enqueue_style('thickbox');
				
				//add color picker here
				wp_enqueue_style( 'wp-color-picker' );
				//enque alpha color add-on wprevpro-wp-color-picker-alpha.js
				wp_register_script( 'wp-color-picker-alpha3', plugin_dir_url( __FILE__ ) . 'js/wprevpro-wp-color-picker-alpha.js', array( 'wp-color-picker' ), $this->version, false );
				wp_enqueue_script( 'wp-color-picker-alpha3' );
				
				//google recaptcha on admin page https://www.google.com/recaptcha/api.js?render=explicit
				wp_enqueue_script ( 'wp-recaptcha', 'https://www.google.com/recaptcha/api.js?render=explicit' );
				wp_enqueue_script('thickbox');
				wp_enqueue_style('thickbox');
				wp_enqueue_script('media-upload');
			}
			
			
			//scripts for floating 
			if($_GET['page']=="wp_pro-float"){
	
				//admin js
				wp_enqueue_script('wprevpro_float_page-js', plugin_dir_url( __FILE__ ) . 'js/wprevpro_float_page.js', array( 'jquery','wp-color-picker' ), $this->version, false );
				//used for ajax
				wp_localize_script('wprevpro_float_page-js', 'adminjs_script_vars', 
					array(
					'wpfb_nonce'=> wp_create_nonce('randomnoncestring'),
					'pluginsUrl' => WPREV_PLUGIN_URL,
					'popuptitle' => esc_html__('Tips', 'wp-review-slider-pro'),
					'popupmsg' => __('This page will let you create a Floating Badge, Review Slider, or Review Pop-up! You can stick it to the bottom or top of your pages.', 'wp-review-slider-pro'),
					'popuptitle1' => esc_html__('How to Display', 'wp-review-slider-pro'),
					'popupmsg1' => esc_html__('There are two ways to display a float. <p>1) Use the Show on Pages: and Show on Posts: setting when creating the Float.</p><p>2) Enter this shortcode on a post or page:</p>', 'wp-review-slider-pro'),
					'msg1' => esc_html__('Please allow popups for this website', 'wp-review-slider-pro'),
					'msg2' => esc_html__('Please enter a Link to URL value.', 'wp-review-slider-pro'),
					'msg3' => esc_html__('Error returning float values, please contact support.', 'wp-review-slider-pro'),
					'msg4' => esc_html__('Error using ajax to return float data, please contact support.', 'wp-review-slider-pro'),
					'msg5' => esc_html__('Error using ajax to return slideout data, please contact support.', 'wp-review-slider-pro'),
					)
				);
				
				wp_register_script( 'common-to-admin',  plugin_dir_url( __FILE__ ) . 'js/wprevpro_commontoadmin.js' , '', $this->version, false );
				wp_enqueue_script( 'common-to-admin' );
				wp_localize_script('common-to-admin', 'adminjs_script_vars_cta', 
						array(
						'msg1' => esc_html__('Pick the Categories', 'wp-review-slider-pro'),
						'msg2' => esc_html__('Pick the Post IDs', 'wp-review-slider-pro'),
						'msg3' => esc_html__('Pick the Pages', 'wp-review-slider-pro'),
						'msg4' => esc_html__('Oops, please contact support. Error getting', 'wp-review-slider-pro'),
						)
					);
				
 				wp_enqueue_script('thickbox');
				wp_enqueue_style('thickbox');
				
				//add color picker here
				wp_enqueue_style( 'wp-color-picker' );
				//enque alpha color add-on wprevpro-wp-color-picker-alpha.js
				wp_register_script( 'wp-color-picker-alpha3', plugin_dir_url( __FILE__ ) . 'js/wprevpro-wp-color-picker-alpha.js', array( 'wp-color-picker' ), $this->version, false );
				wp_enqueue_script( 'wp-color-picker-alpha3' );
				
				//for previewing slider
				//wp_enqueue_script( $this->_token."_plublic-min", plugin_dir_url(dirname(__FILE__)) . 'public/js/wprev-public.js', array( 'jquery' ), $this->version, false );
				
				//add some public scripts for the preview
				
				wp_enqueue_script( $this->_token."_slick-min", plugin_dir_url( dirname(__FILE__) ) . 'public/js/wprs-slick.min.js', array( 'jquery' ), $this->version, true );
				
				wp_enqueue_script( $this->_token."_unslider-min", plugin_dir_url(dirname(__FILE__)) . 'public/js/wprs-unslider.min.js', array( 'jquery' ), $this->version, false );
				wp_enqueue_script( $this->_token."_plublic-min",plugin_dir_url(dirname(__FILE__)) . 'public/js/wprev-public.js', array( 'jquery' ), $this->version, false );
				//used for ajax
				wp_localize_script($this->_token."_plublic-min", 'wprevpublicjs_script_vars', 
					array(
					'wpfb_nonce'=> wp_create_nonce('randomnoncestring'),
					'wpfb_ajaxurl' => admin_url( 'admin-ajax.php' ),
					'wprevpluginsurl' => WPREV_PLUGIN_URL,
					)
				);
				

			}
			
			//scripts for welcome 
			if($_GET['page']=="wp_pro-welcome"){
			
				//admin js
				//wp_enqueue_script('wprevpro_welcome_page-js', plugin_dir_url( __FILE__ ) . 'js/wprevpro_welcome_page.js', array( 'jquery' ), $this->version, false );
				//used for ajax
				//wp_localize_script('wprevpro_welcome_page-js', 'adminjs_script_vars', 
				//	array(
				//	'wpfb_nonce'=> wp_create_nonce('randomnoncestring'),
				//	'pluginsUrl' => WPREV_PLUGIN_URL,
				//	)	//	adminjs_script_vars.msg12
				//);
				
			
				wp_register_script( $this->_token.'chart-js',  plugin_dir_url( __FILE__ ) . 'js/Chart.bundle.min.js' , '', $this->version, false );
				wp_enqueue_script( $this->_token.'chart-js' );
				
				wp_register_script( $this->_token.'chart-js-trendline',  plugin_dir_url( __FILE__ ) . 'js/chartjs-plugin-trendline.js' , '', $this->version, false );
				wp_enqueue_script( $this->_token.'chart-js-trendline' );
				
 				wp_enqueue_script('thickbox');
				wp_enqueue_style('thickbox');

			}
			
			//scripts for analytics 
			if($_GET['page']=="wp_pro-analytics"){
			
				//admin js
				wp_enqueue_script('wprevpro_analytics_page-js', plugin_dir_url( __FILE__ ) . 'js/wprevpro_analytics_page.js', array( 'jquery' ), $this->version, false );
				//used for ajax
				wp_localize_script('wprevpro_analytics_page-js', 'adminjs_script_vars', 
					array(
					'wpfb_nonce'=> wp_create_nonce('randomnoncestring'),
					'pluginsUrl' => WPREV_PLUGIN_URL,
					'globalwprevtypearray' => json_encode($typearray),
					'msg1' => esc_html__('Location Filter', 'wp-review-slider-pro'),
					'msg2' => esc_html__('Type Filter', 'wp-review-slider-pro'),
					'd1' => esc_html__('Today', 'wp-review-slider-pro'),
					'd2' => esc_html__('Yesterday', 'wp-review-slider-pro'),
					'd3' => esc_html__('Last 7 Days', 'wp-review-slider-pro'),
					'd4' => esc_html__('Last 30 Days', 'wp-review-slider-pro'),
					'd5' => esc_html__('Last 60 Days', 'wp-review-slider-pro'),
					'd6' => esc_html__('Last 90 Days', 'wp-review-slider-pro'),
					'd7' => esc_html__('This Month', 'wp-review-slider-pro'),
					'd8' => esc_html__('Last Month', 'wp-review-slider-pro'),
					'd9' => esc_html__('This Year', 'wp-review-slider-pro'),
					'd10' => esc_html__('Last Year', 'wp-review-slider-pro'),
					'd11' => esc_html__('All Time', 'wp-review-slider-pro'),
					'msg3' => esc_html__('Error accessing language function via ajax.', 'wp-review-slider-pro'),
					'msg4' => esc_html__('Ratings', 'wp-review-slider-pro'),
					'msg5' => esc_html__('Error returning json object. Please try again or contact us and copy and send us the following:', 'wp-review-slider-pro'),
					'msg6' => esc_html__('Overall Ratings (Old >> New)', 'wp-review-slider-pro'),
					'msg7' => esc_html__('Review Response:', 'wp-review-slider-pro'),
					'msg8' => esc_html__('Type', 'wp-review-slider-pro'),
					'msg9' => esc_html__('Page', 'wp-review-slider-pro'),
					'msg10' => esc_html__('Source URL', 'wp-review-slider-pro'),
					'msg11' => esc_html__('Reviewer URL', 'wp-review-slider-pro'),
					'msg12' => esc_html__('Review Details', 'wp-review-slider-pro'),
					)	//	adminjs_script_vars.msg12
				);
				
			
				wp_register_script( $this->_token.'chart-js',  plugin_dir_url( __FILE__ ) . 'js/Chart.bundle.min.js' , '', $this->version, false );
				wp_enqueue_script( $this->_token.'chart-js' );
				
				wp_register_script( $this->_token.'chart-js-trendline',  plugin_dir_url( __FILE__ ) . 'js/chartjs-plugin-trendline.js' , '', $this->version, false );
				wp_enqueue_script( $this->_token.'chart-js-trendline' );
				
				//for datepicker
				wp_register_script( $this->_token.'_moment',  plugin_dir_url( __FILE__ ) . 'js/moment.min.js' , '', $this->version, false );
				wp_enqueue_script( $this->_token.'_moment' );
				
				wp_register_script( $this->_token.'_daterangepicker',  plugin_dir_url( __FILE__ ) . 'js/daterangepicker.js' , '', $this->version, false );
				wp_enqueue_script( $this->_token.'_daterangepicker' );
				
				//pretty select
				wp_register_script( $this->_token.'_select2',  plugin_dir_url( __FILE__ ) . 'js/select2.min.js' , '', $this->version, false );
				wp_enqueue_script( $this->_token.'_select2' );
				
				//word cloud
				wp_register_script( $this->_token.'_jqcloud',  plugin_dir_url( __FILE__ ) . 'js/jqcloud.min.js' , '', $this->version, false );
				wp_enqueue_script( $this->_token.'_jqcloud' );
				
				
 				wp_enqueue_script('thickbox');
				wp_enqueue_style('thickbox');

			}
			
			//for preview template in iframe on templates page
			if($_GET['page']=="wp_pro-get_preview"){
				
				//enque template styles for preview
				wp_register_style( 'wprevpro_w3', plugin_dir_url(dirname(__FILE__)). 'public/css/wprevpro_w3.css', array(), $this->version, 'all' );
				wp_enqueue_style( 'wprevpro_w3' );
				//register slider stylesheet
				//wp_register_style( 'unslider', plugin_dir_url(dirname(__FILE__)). 'public/css/wprs_unslider.css', array(), $this->version, 'all' );
				//wp_enqueue_style( 'unslider' );
				wp_enqueue_style( $this->_token."_select2", plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), $this->version, 'all' );
				
				
				//for the preview window....plugin_dir_url(dirname(__FILE__)). 'public/
				wp_enqueue_script( $this->_token."_slick-min", plugin_dir_url(dirname(__FILE__)) . 'public/js/wprs-slick.min.js', array( 'jquery' ), $this->version, true );
				wp_enqueue_script( $this->_token."_unslider_comb-min", plugin_dir_url(dirname(__FILE__)). 'public/js/wprs-combined.min.js', array( 'jquery' ), $this->version, true );
				wp_enqueue_script( $this->_token."_plublic-min", plugin_dir_url(dirname(__FILE__)). 'public/js/wprev-public.js', array( 'jquery' ), $this->version, true );
				//used for ajax
				wp_localize_script($this->_token."_plublic-min", 'wprevpublicjs_script_vars', 
					array(
					'wpfb_nonce'=> wp_create_nonce('randomnoncestring'),
					'wpfb_ajaxurl' => admin_url( 'admin-ajax.php' ),
					'wprevpluginsurl' => WPREV_PLUGIN_URL
					)
				);
				
			
			}
		}
		
	}
	
	public function add_menu_pages() {

		/**
		 * adds the menu pages to wordpress
		 */

		$page_title = 'WP Reviews Pro : Welcome';
		$menu_title = 'WP Reviews Pro';
		$capability = 'manage_options';
		$capabilityeditor = 'edit_pages';
		$menu_slug = 'wp_pro-welcome';
		
		// Check Tools page to see if any other roles can also see this.
		$defaultcap = $capability;
		$getreviewsallcap = $capability;
		$checkcapuser = $this->wprev_canuserseepage('welcome');
		if($checkcapuser['canview']==true && $checkcapuser['cap']!=''){
			$defaultcap = $checkcapuser['cap'];
			$getreviewsallcap = $defaultcap;
		}
		add_menu_page($page_title, $menu_title, $defaultcap, $menu_slug, array($this,'wp_fb_welcome'),'dashicons-star-half');
		$sub_menu_title = 'Welcome';
		add_submenu_page($menu_slug, $page_title, $sub_menu_title, $defaultcap, $menu_slug, array($this,'wp_fb_welcome'));
		
		//sub menu page for get reviews
		$defaultcap = $capability;
		$checkcapuser = $this->wprev_canuserseepage('getrevs');
		if($checkcapuser['canview']==true && $checkcapuser['cap']!=''){
			$defaultcap = $checkcapuser['cap'];
		}
		$submenu_page_title = 'WP Reviews Pro : Download Reviews';
		$submenu_title = 'Get Reviews';
		$submenu_slug = 'wp_pro-getrevs';
		add_submenu_page($menu_slug, $submenu_page_title, $submenu_title, $defaultcap, $submenu_slug, array($this,'wp_fb_getrevs'));
		
		
		// Now add a hidden page for iframing the template preview
		$submenu_page_title = 'WP Reviews Pro : Template Preview';
		$submenu_title = 'Preview';
		$submenu_slug = 'wp_pro-get_preview';
		add_submenu_page('', $submenu_page_title, $submenu_title, $getreviewsallcap, $submenu_slug, array($this,'wp_fb_getpreview'));
		
		// Now add a non-menu page for choosing download method
		$submenu_page_title = 'WP Reviews Pro : Choose Method';
		$submenu_title = 'Choose';
		$submenu_slug = 'wp_pro-getrevschoice';
		add_submenu_page('', $submenu_page_title, $submenu_title, $getreviewsallcap, $submenu_slug, array($this,'wp_fb_getrevschoice'));
		
		// Now add the submenu page for FB
		/*
		$submenu_page_title = 'WP Reviews Pro : Get Facebook Reviews';
		$submenu_title = 'Facebook';
		$submenu_slug = 'wp_pro-settings';
		add_submenu_page(null, $submenu_page_title, $submenu_title, $getreviewsallcap, $submenu_slug, array($this,'wp_fb_settings'));
		*/
		
		// Now add the submenu page for google page
		/*
		$submenu_page_title = 'WP Google Places Reviews: Google Places API';
		$submenu_title = 'Google';
		$submenu_slug = 'wp_pro-googlesettings';
		add_submenu_page('', $submenu_page_title, $submenu_title, $getreviewsallcap, $submenu_slug, array($this,'wp_fb_googlesettings'));
		*/
		
	
		// Now add the hidden submenu page for app stores/itunes
		$submenu_page_title = 'WP Reviews Pro : iTunes';
		$submenu_title = 'iTunes';
		$submenu_slug = 'wp_pro-get_apps';
		add_submenu_page('', $submenu_page_title, $submenu_title, $getreviewsallcap, $submenu_slug, array($this,'wp_fb_getapps'));
		
		// Now add the hidden submenu page for twitter
		$submenu_page_title = 'WP Reviews Pro : Twitter';
		$submenu_title = 'Twitter';
		$submenu_slug = 'wp_pro-get_twitter';
		add_submenu_page('', $submenu_page_title, $submenu_title, $getreviewsallcap, $submenu_slug, array($this,'wp_fb_gettwitter'));
		
		// Now add the submenu page for woocommerce
		$submenu_page_title = 'WP Reviews Pro : WooCommerce';
		$submenu_title = 'WooCommerce';
		$submenu_slug = 'wp_pro-get_woo';
		add_submenu_page('', $submenu_page_title, $submenu_title, $getreviewsallcap, $submenu_slug, array($this,'wp_fb_getwoo'));
				
		// Now add the hidden submenu page for reviewscraper
		// Check Tools page to see if any other roles can also see this.
		$defaultcap = $capability;
		$checkcapuser = $this->wprev_canuserseepage('reviewfunnel');
		if($checkcapuser['canview']==true && $checkcapuser['cap']!=''){
			$defaultcap = $checkcapuser['cap'];
		}
		$submenu_page_title = 'WP Reviews Pro : Review Funnel';
		$submenu_title = 'Review Funnels';
		$submenu_slug = 'wp_pro-reviewfunnel';
		add_submenu_page($menu_slug, $submenu_page_title, $submenu_title, $defaultcap, $submenu_slug, array($this,'wp_fb_reviewfunnel'));
		
		// Now add the submenu page for the actual reviews list
		// Check Tools page to see if any other roles can also see this.
		$defaultcap = $capabilityeditor;
		$checkcapuser = $this->wprev_canuserseepage('reviews');
		if($checkcapuser['canview']==true && $checkcapuser['cap']!=''){
			$defaultcap = $checkcapuser['cap'];
		}
		$submenu_page_title = 'WP Reviews Pro : Review List';
		$submenu_title = 'Review List';
		$submenu_slug = 'wp_pro-reviews';
		add_submenu_page($menu_slug, $submenu_page_title, $submenu_title, $defaultcap, $submenu_slug, array($this,'wp_fb_reviews'));
		
		// Now add the submenu page for the reviews templates
		// Check Tools page to see if any other roles can also see this.
		$defaultcap = $capability;
		$checkcapuser = $this->wprev_canuserseepage('templates_posts');
		if($checkcapuser['canview']==true && $checkcapuser['cap']!=''){
			$defaultcap = $checkcapuser['cap'];
		}
		$submenu_page_title = 'WP Reviews Pro : Templates';
		$submenu_title = 'Templates';
		$submenu_slug = 'wp_pro-templates_posts';
		add_submenu_page($menu_slug, $submenu_page_title, $submenu_title, $defaultcap, $submenu_slug, array($this,'wp_fb_templates_posts'));
		
		// Now add the submenu page for the badges
		// Check Tools page to see if any other roles can also see this.
		$defaultcap = $capability;
		$checkcapuser = $this->wprev_canuserseepage('badges');
		if($checkcapuser['canview']==true && $checkcapuser['cap']!=''){
			$defaultcap = $checkcapuser['cap'];
		}
		$submenu_page_title = 'WP Reviews Pro : Badges';
		$submenu_title = 'Badges';
		$submenu_slug = 'wp_pro-badges';
		add_submenu_page($menu_slug, $submenu_page_title, $submenu_title, $defaultcap, $submenu_slug, array($this,'wp_fb_badges'));
		
		// Now add the submenu page for the forms
		// Check Tools page to see if any other roles can also see this.
		$defaultcap = $capability;
		$checkcapuser = $this->wprev_canuserseepage('forms');
		if($checkcapuser['canview']==true && $checkcapuser['cap']!=''){
			$defaultcap = $checkcapuser['cap'];
		}
		$submenu_page_title = 'WP Reviews Pro : Forms';
		$submenu_title = 'Forms';
		$submenu_slug = 'wp_pro-forms';
		add_submenu_page($menu_slug, $submenu_page_title, $submenu_title, $defaultcap, $submenu_slug, array($this,'wp_fb_forms'));
		
		// Now add the submenu page for the Float
		// Check Tools page to see if any other roles can also see this.
		$defaultcap = $capability;
		$checkcapuser = $this->wprev_canuserseepage('float');
		if($checkcapuser['canview']==true && $checkcapuser['cap']!=''){
			$defaultcap = $checkcapuser['cap'];
		}
		$submenu_page_title = 'WP Reviews Pro : Float';
		$submenu_title = 'Floats';
		$submenu_slug = 'wp_pro-float';
		add_submenu_page($menu_slug, $submenu_page_title, $submenu_title, $defaultcap, $submenu_slug, array($this,'wp_fb_float'));
		
		// Now add the submenu page for the analytics
		// Check Tools page to see if any other roles can also see this.
		$defaultcap = $capabilityeditor;
		$checkcapuser = $this->wprev_canuserseepage('analytics');
		if($checkcapuser['canview']==true && $checkcapuser['cap']!=''){
			$defaultcap = $checkcapuser['cap'];
		}
		$submenu_page_title = 'WP Reviews Pro : Analytics';
		$submenu_title = 'Analytics';
		$submenu_slug = 'wp_pro-analytics';
		add_submenu_page($menu_slug, $submenu_page_title, $submenu_title, $defaultcap, $submenu_slug, array($this,'wp_fb_analytics'));
		
		// Now add the submenu page for the notifications
		// Check Tools page to see if any other roles can also see this.
		$defaultcap = $capability;
		$checkcapuser = $this->wprev_canuserseepage('notifications');
		if($checkcapuser['canview']==true && $checkcapuser['cap']!=''){
			$defaultcap = $checkcapuser['cap'];
		}
		$submenu_page_title = 'WP Reviews Pro : Tools';
		$submenu_title = 'Tools';
		$submenu_slug = 'wp_pro-notifications';
		add_submenu_page($menu_slug, $submenu_page_title, $submenu_title, $defaultcap, $submenu_slug, array($this,'wp_fb_notifications'));
		
		// Now add the submenu page for the forum
		// Check Tools page to see if any other roles can also see this.
		$defaultcap = $capability;
		$checkcapuser = $this->wprev_canuserseepage('forum');
		if($checkcapuser['canview']==true && $checkcapuser['cap']!=''){
			$defaultcap = $checkcapuser['cap'];
		}
		$submenu_page_title = 'WP Reviews Pro : Forum';
		$submenu_title = 'Help Forum';
		$submenu_slug = 'wp_pro-forum';
		add_submenu_page($menu_slug, $submenu_page_title, $submenu_title, $defaultcap, $submenu_slug, array($this,'wp_fb_forum'));
		

	}
	
	public function wp_fb_welcome() {
		require_once plugin_dir_path( __FILE__ ) . 'partials/welcome.php';
	}
	public function wp_fb_getrevs() {
		require_once plugin_dir_path( __FILE__ ) . 'partials/getrevs.php';
	}
	
	public function wp_fb_getpreview() {
		require_once plugin_dir_path( __FILE__ ) . 'partials/previewiframe.php';
	}
	public function wp_fb_getrevschoice() {
		require_once plugin_dir_path( __FILE__ ) . 'partials/getrevschoice.php';
	}
	
	//public function wp_fb_getairbnb() {
	//	require_once plugin_dir_path( __FILE__ ) . 'partials/get_airbnb.php';
	//}
	//public function wp_fb_getvrbo() {
	//	require_once plugin_dir_path( __FILE__ ) . 'partials/get_vrbo.php';
	//}
	public function wp_fb_getwoo() {
		require_once plugin_dir_path( __FILE__ ) . 'partials/get_woo.php';
	}
	
	//public function wp_fb_settings() {
	//	require_once plugin_dir_path( __FILE__ ) . 'partials/settings.php';
	//}
	public function wp_fb_reviews() {
		require_once plugin_dir_path( __FILE__ ) . 'partials/review_list.php';
	}
	//public function wp_fb_googlesettings() {
	//	require_once plugin_dir_path( __FILE__ ) . 'partials/googlesettings.php';
	//}

	public function wp_fb_templates_posts() {
		require_once plugin_dir_path( __FILE__ ) . 'partials/templates_posts.php';
	}
	public function wp_fb_badges() {
		require_once plugin_dir_path( __FILE__ ) . '/partials/badges.php';
	}
	public function wp_fb_forms() {
		require_once plugin_dir_path( __FILE__ ) . 'partials/forms.php';
	}
	public function wp_fb_float() {
		require_once plugin_dir_path( __FILE__ ) . 'partials/float.php';
	}
	public function wp_fb_analytics() {
		require_once plugin_dir_path( __FILE__ ) . 'partials/analytics.php';
	}
	/*
	public function wp_fb_getyelp() {
		require_once plugin_dir_path( __FILE__ ) . 'partials/get_yelp.php';
	}
	*/
	public function wp_fb_reviewfunnel() {
		require_once plugin_dir_path( __FILE__ ) . '/partials/reviewfunnel.php';
	}
	public function wp_fb_getapps() {
		require_once plugin_dir_path( __FILE__ ) . '/partials/get_apps.php';
	}
	//public function wp_fb_gettripadvisor() {
	//	require_once plugin_dir_path( __FILE__ ) . 'partials/get_tripadvisor.php';
	//}
	public function wp_fb_gettwitter() {
		require_once plugin_dir_path( __FILE__ ) . '/partials/get_twitter.php';
	}
	public function wp_fb_forum() {
		require_once plugin_dir_path( __FILE__ ) . 'partials/forum.php';
	}
	public function wp_fb_notifications() {
		require_once plugin_dir_path( __FILE__ ) . 'partials/notifications.php';
	}

	
	
	/**
	 * custom option and settings on woo page
	 */
	 //===========start woo page settings===========================================================
	public function wppro_woo_settings_init()
	{
	
		// register a new setting for "wp_pro-get_woo" page
		register_setting('wp_pro-get_woo', 'wprevpro_woo_settings');
		
		// register a new section in the "wp_pro-get_woo" page
		add_settings_section(
			'wprevpro_woo_section_developers',
			'',
			array($this,'wppro_woo_section_developers_cb'),
			'wp_pro-get_woo'
		);
		

		//Turn on woo Reviews cron
		add_settings_field("woo_radio_sync", __( 'Pull WooCommerce:', 'wp-review-slider-pro' ), array($this,'woo_radio_display_sync'), "wp_pro-get_woo", "wprevpro_woo_section_developers",
			[
				'label_for'         => 'woo_radio_sync',
				'class'             => 'wprevpro_row',
				'wprevpro_custom_data' => 'custom',
			]); 
			
		//add sync type select
		add_settings_field(
			'woo_sync_all', 
			__( 'Pull Type:', 'wp-review-slider-pro' ),
			array($this,'wprevpro_field_woo_sync'),
			'wp_pro-get_woo',
			'wprevpro_woo_section_developers',
			array(
				'label_for'         => 'woo_sync_all',
				'class'             => 'wprevpro_row wooburlmore'
			)
		);
		//add name option
		add_settings_field(
			'woo_name_options', 
			__( 'Name Options:', 'wp-review-slider-pro' ),
			array($this,'wprevpro_field_woo_name'),
			'wp_pro-get_woo',
			'wprevpro_woo_section_developers',
			array(
				'label_for'         => 'woo_name_options',
				'class'             => 'wprevpro_row'
			)
		);
		//add rating option
		add_settings_field(
			'woo_rating_options', 
			__( 'Default Rating:', 'wp-review-slider-pro' ),
			array($this,'wprevpro_field_woo_rating'),
			'wp_pro-get_woo',
			'wprevpro_woo_section_developers',
			array(
				'label_for'         => 'woo_rating_options',
				'class'             => 'wprevpro_row'
			)
		);
		//add sync type select
		add_settings_field(
			'woo_push_type', 
			__( 'Push WooCommerce:', 'wp-review-slider-pro' ),
			array($this,'wprevpro_field_woo_push'),
			'wp_pro-get_woo',
			'wprevpro_woo_section_developers',
			array(
				'label_for'         => 'woo_push_type',
				'class'             => 'wprevpro_row wooburlmore'
			)
		);

	}
	//==== developers section cb ====
		public function wprevpro_field_woo_push($args)
	{
		$options = get_option('wprevpro_woo_settings');
		if(!isset($options[$args['label_for']])){
			$options[$args['label_for']]='all';
		}
		
		?>
		<select id="<?php echo  esc_attr($args['label_for']); ?>" name="wprevpro_woo_settings[<?php echo  esc_attr($args['label_for']); ?>]">
			<option value="no" <?php selected( $options[$args['label_for']], 'no' ); ?>><?php echo esc_attr__( 'No', 'wp-review-slider-pro' ); ?></option>
			</option>
			<option value="yes" <?php selected( $options[$args['label_for']], 'yes' ); ?>><?php echo esc_attr__( 'Yes', 'wp-review-slider-pro' ); ?></option>
			
		</select>		
		<p class="description">
			<?php echo  esc_html__('Use this to push reviews that are input or downloaded by this plugin back in to the WooCommerce review system. This only works when you assign a Post Id to the review when you download or when editing it on the Review List. This can be helpful if you are still using the WooCommerce review system to display stars or reviews. ', 'wp-review-slider-pro'); ?>
		</p>
		<?php
		
	}
	//==== developers section cb ====
		public function wprevpro_field_woo_rating($args)
	{
		$options = get_option('wprevpro_woo_settings');
		if(!isset($options[$args['label_for']])){
			$options[$args['label_for']]='all';
		}
		
		?>
		<select id="<?php echo  esc_attr($args['label_for']); ?>" name="wprevpro_woo_settings[<?php echo  esc_attr($args['label_for']); ?>]">
			<option value="blank" <?php selected( $options[$args['label_for']], 'blank' ); ?>><?php echo esc_attr__( 'Leave Blank', 'wp-review-slider-pro' ); ?></option>
			</option>
			<option value="5" <?php selected( $options[$args['label_for']], '5' ); ?>><?php echo esc_attr__( '5', 'wp-review-slider-pro' ); ?></option>
			<option value="4" <?php selected( $options[$args['label_for']], '4' ); ?>><?php echo esc_attr__( '4', 'wp-review-slider-pro' ); ?></option>
			<option value="3" <?php selected( $options[$args['label_for']], '3' ); ?>><?php echo esc_attr__( '3', 'wp-review-slider-pro' ); ?></option>
			<option value="2" <?php selected( $options[$args['label_for']], '2' ); ?>><?php echo esc_attr__( '2', 'wp-review-slider-pro' ); ?></option>
			<option value="1" <?php selected( $options[$args['label_for']], '1' ); ?>><?php echo esc_attr__( '1', 'wp-review-slider-pro' ); ?></option>
		</select>		
		<p class="description">
			<?php echo  esc_html__('Use this to assign a rating value if the review/comment does not have one.', 'wp-review-slider-pro'); ?>
		</p>
		<?php
		
	}
	
	public function wppro_woo_section_developers_cb($args)
	{
		echo "<div id='wprevwooinstr'>";
		echo "<p>".__('Use this page to sync your WooCommerce product reviews with this plugin and add them to the Review List page.', 'wp-review-slider-pro')."</p><p>".__('To create a summary of WooCommerce reviews simply go to the Templates tab and create a new template.<br>If you want to replace the default WooCommerce review system it is more involved. Instructions can be found <a href="https://wpreviewslider.com/how-to-use-wp-review-slider-pro-for-woocommerce-product-reviews/" target="_blank">here</a>.', 'wp-review-slider-pro')."</p>";
		echo "</div>";
	}


	public function woo_radio_display_sync($args)
		{
		$options = get_option('wprevpro_woo_settings');
		if(!isset($options['woo_radio_sync'])){
			$options['woo_radio_sync']='no';
		}

		   ?>
				<input type="radio" name="wprevpro_woo_settings[<?= esc_attr($args['label_for']); ?>]" value="yes" <?php checked('yes', $options[$args['label_for']], true); ?>><?php echo  esc_html__('Reviews', 'wp-review-slider-pro'); ?>&nbsp;&nbsp;&nbsp;
				<input type="radio" name="wprevpro_woo_settings[<?= esc_attr($args['label_for']); ?>]" value="com" <?php checked('com', $options[$args['label_for']], true); ?>><?php echo  esc_html__('Comments', 'wp-review-slider-pro'); ?>&nbsp;&nbsp;&nbsp;
				<input type="radio" name="wprevpro_woo_settings[<?= esc_attr($args['label_for']); ?>]" value="rc" <?php checked('rc', $options[$args['label_for']], true); ?>><?php echo  esc_html__('Reviews & Comments', 'wp-review-slider-pro'); ?>&nbsp;&nbsp;&nbsp;
				<input type="radio" name="wprevpro_woo_settings[<?= esc_attr($args['label_for']); ?>]" value="no" <?php checked('no', $options[$args['label_for']], true); ?>><?php echo  esc_html__('None', 'wp-review-slider-pro'); ?>
				<p class="description">
				<?php
				echo __('Tells the plugin what type of comment to pull in to this plugin. ', 'wp-review-slider-pro');
				echo "<p>".__('Notes:', 'wp-review-slider-pro')."<br>".__('- When you get a new WooCommerce review, it will automatically be pulled in to this plugin in the hidden state. <br>- If you approve it, then it will change to the displayed state in this plugin. <br>- If you delete it then it will be removed from this plugin. <br>- This does not work in reverse, e.g., if you delete a review from this plugin, it does not delete the original WooCommerce review.', 'wp-review-slider-pro')."</p>"
				?>
				</p>
		   <?php
		}
		
	public function wprevpro_field_woo_sync($args)
	{
		$options = get_option('wprevpro_woo_settings');
		if(!isset($options[$args['label_for']])){
			$options[$args['label_for']]='all';
		}
		
		?>
		<select id="<?php echo  esc_attr($args['label_for']); ?>" name="wprevpro_woo_settings[<?php echo  esc_attr($args['label_for']); ?>]">
			<option value="approved" <?php selected( $options[$args['label_for']], 'approved' ); ?>><?php echo esc_attr__( 'Approved Only', 'wp-review-slider-pro' ); ?></option>
			</option>
			<option value="all" <?php selected( $options[$args['label_for']], 'all' ); ?>><?php echo esc_attr__( 'All Comments', 'wp-review-slider-pro' ); ?></option>
		</select>		
		<p class="description">
			<?php echo  esc_html__('Use this if you want to leave Unapproved WooCommerce reviews out of this plugin.', 'wp-review-slider-pro'); ?>
		</p>
		<?php
		
	}
	public function wprevpro_field_woo_name($args)
	{
		// get the value of the setting we've registered with register_setting()
		$options = get_option('wprevpro_woo_settings');
		if(!isset($options[$args['label_for']])){
			$options[$args['label_for']]='author';
		}
		//print_r($options);
		// output the field
		?>
				<input type="radio" name="wprevpro_woo_settings[<?= esc_attr($args['label_for']); ?>]" value="author" <?php checked('author', $options[$args['label_for']], true); ?>>Username&nbsp;&nbsp;&nbsp;
				<input type="radio" name="wprevpro_woo_settings[<?= esc_attr($args['label_for']); ?>]" value="first" <?php checked('first', $options[$args['label_for']], true); ?>>First Name&nbsp;&nbsp;&nbsp;
				<input type="radio" name="wprevpro_woo_settings[<?= esc_attr($args['label_for']); ?>]" value="last" <?php checked('last', $options[$args['label_for']], true); ?>>Last Name&nbsp;&nbsp;&nbsp;
				<input type="radio" name="wprevpro_woo_settings[<?= esc_attr($args['label_for']); ?>]" value="firstlast" <?php checked('firstlast', $options[$args['label_for']], true); ?>>First & Last Name&nbsp;&nbsp;&nbsp;
				<p class="description">
			<?php echo  esc_html__('Set this to change the way the name is saved in your database. If the first or last name can\'t be found, then the username will be used.', 'wp-review-slider-pro'); ?>
		</p>
		<?php
	}
	//=======end woo page settings========================================================

	
	
	/**
	 * custom option and settings on airbnb page
	 */
	 //===========start airbnb page settings===========================================================
	 /*
	public function wppro_airbnb_settings_init()
	{
	
		// register a new setting for "wp_pro-get_airbnb" page
		register_setting('wp_pro-get_airbnb', 'wprevpro_airbnb_settings');
		
		// register a new section in the "wp_pro-get_airbnb" page
		add_settings_section(
			'wprevpro_airbnb_section_developers',
			'',
			array($this,'wppro_airbnb_section_developers_cb'),
			'wp_pro-get_airbnb'
		);
		
		//register airbnb business url input field
		add_settings_field(
			'airbnb_business_url', // as of WP 4.6 this value is used only internally
			__( 'Airbnb Listing URL', 'wp-review-slider-pro' ),
			array($this,'wprevpro_field_airbnb_business_id_cb'),
			'wp_pro-get_airbnb',
			'wprevpro_airbnb_section_developers',
			[
				'label_for'         => 'airbnb_business_url',
				'class'             => 'wppro_row',
				'wppro_custom_data' => 'custom',
			]
		);

		//--------------
		//looping here based on how many we need
		$tempoptions = [];

		if(get_option('wprevpro_airbnb_settings')){
			$tempoptions = get_option('wprevpro_airbnb_settings');
		}
		if(!isset($tempoptions['airbnb_business_url_more'])){
			$tempoptions['airbnb_business_url_more']=1;
		}
		if($tempoptions['airbnb_business_url_more']>1){
			$loopnum = $tempoptions['airbnb_business_url_more'];
			for ($x = 2; $x <= $loopnum; $x++) {
				//register airbnb business url input field
				add_settings_field(
					"airbnb_business_url$x", // as of WP 4.6 this value is used only internally
					sprintf( __( 'Airbnb Business URL %s', 'wp-review-slider-pro' ) , $x ),
					array($this,'wprevpro_field_airbnb_business_id_cb_more'),
					'wp_pro-get_airbnb',
					'wprevpro_airbnb_section_developers',
					[
						'label_for'         => "airbnb_business_url$x",
						'class'             => 'wprevpro_row airbnburladditional',
						'wprevpro_custom_data' => 'custom',
					]
				);
			}
		}	
		//add more url fields
		add_settings_field(
			'airbnb_business_url_more', 
			__( 'Total URLs', 'wp-review-slider-pro' ),
			array($this,'wprevpro_field_airbnb_more_urls'),
			'wp_pro-get_airbnb',
			'wprevpro_airbnb_section_developers',
			array(
				'label_for'         => 'airbnb_business_url_more',
				'class'             => 'wprevpro_row airbnburlmore'
			)
		);

		//Turn on airbnb Reviews cron
		add_settings_field("airbnb_radio_cron", __( 'Check Reviews 24 Hours', 'wp-review-slider-pro' ), array($this,'airbnb_radio_display_cron'), "wp_pro-get_airbnb", "wprevpro_airbnb_section_developers",
			[
				'label_for'         => 'airbnb_radio_cron',
				'class'             => 'wprevpro_row',
				'wprevpro_custom_data' => 'custom',
			]); 

	}
	//==== developers section cb ====
	public function wppro_airbnb_section_developers_cb($args)
	{
		
		echo  "<p>".esc_html__('Use this page to download your newest Airbnb business reviews and save them in your Wordpress database. They will show up on the Review List page once downloaded. Currently you can download listing reviews, and experiences reviews.', 'wp-review-slider-pro')."</p>";
	}
	
	//==== field cb =====
	public function wprevpro_field_airbnb_business_id_cb($args)
	{
		// get the value of the setting we've registered with register_setting()
		$options = get_option('wprevpro_airbnb_settings');
		$nameval = $args['label_for']."_name";
		if(!isset($options[$args['label_for']])){
			$options[$args['label_for']]='';
		}
		if(!isset($options[$nameval])){
			$options[$nameval]='';
		}
		
		$nameval =$options[$nameval];
		// output the field
		?>
		<input class='' id="<?= esc_attr($args['label_for']); ?>_name" data-custom="" type="text" name="wprevpro_airbnb_settings[<?= esc_attr($args['label_for']); ?>_name]" placeholder="<?= esc_html__('Unique Location Name', 'wp-review-slider-pro'); ?>" value="<?php echo $nameval; ?>">
		
		<input class='airbnb_business_url' id="<?= esc_attr($args['label_for']); ?>" data-custom="" type="text" name="wprevpro_airbnb_settings[<?= esc_attr($args['label_for']); ?>]" placeholder="<?= esc_html__('Location Airbnb URL', 'wp-review-slider-pro'); ?>" value="<?php echo $options[$args['label_for']]; ?>">
		<?php
		if( ! empty( $options[$args['label_for']] )) {
		?>
		<button onclick="getairbnbreviewsfunction('1')" id="wpfbr_getairbnbreviews" type="button" class="btn_green"><?= esc_html__('Retrieve Reviews', 'wp-review-slider-pro'); ?></button><br/>
		<?php
		}
		?>
		
		<p class="description">
			<?= esc_html__('Enter a name for this location and the Airbnb URL and click Save Settings.', 'wp-review-slider-pro'); ?>
		</p>
		<?php
	}
	
	public function wprevpro_field_airbnb_business_id_cb_more($args)
	{
		// get the value of the setting we've registered with register_setting()
		$options = get_option('wprevpro_airbnb_settings');
		$temparg = esc_attr($args['label_for']);
		$nameval = $args['label_for']."_name";
		if(!isset($options[$temparg])){
			$options[$temparg]='';
		}
		if(!isset($options[$nameval])){
			$options[$nameval]='';
		}
		//find url num from id
		$urlnum = esc_attr($args['label_for']);
		$urlnum = str_replace("airbnb_business_url","",$urlnum);
		// output the field
		
		$nameval =$options[$nameval];
		?>
		<input class='' id="<?= esc_attr($args['label_for']); ?>_name" data-custom="" type="text" name="wprevpro_airbnb_settings[<?= esc_attr($args['label_for']); ?>_name]" placeholder="<?= esc_html__('Unique Location Name', 'wp-review-slider-pro'); ?>" value="<?php echo $nameval; ?>">
		
		<input class='airbnb_business_url' id="<?= esc_attr($args['label_for']); ?>" data-custom="" type="text" name="wprevpro_airbnb_settings[<?= esc_attr($args['label_for']); ?>]" placeholder="<?= esc_html__('Location URL', 'wp-review-slider-pro'); ?>" value="<?php echo $options[$args['label_for']]; ?>">
		<?php
		if( ! empty( $options[$args['label_for']] )) {
		?>
		<button onclick="getairbnbreviewsfunction('<?php echo $urlnum; ?>')" id="wpfbr_getairbnbreviews" type="button" class="btn_green"><?= esc_html__('Retrieve Reviews', 'wp-review-slider-pro'); ?></button><br/>
		<?php
		}
		?>
		
		<p class="description">
			<?= esc_html__('Optional: If you have more than one Airbnb page.', 'wp-review-slider-pro'); ?>
		</p>
		<?php
	}
	
	public function wprevpro_field_airbnb_more_urls($args)
	{
		$options = get_option('wprevpro_airbnb_settings');
		if(!isset($options[$args['label_for']])){
			$options[$args['label_for']]='';
		}
		
		?>
		<select id="<?php echo  esc_attr($args['label_for']); ?>" name="wprevpro_airbnb_settings[<?php echo  esc_attr($args['label_for']); ?>]">
			<?php foreach( range( 1, 50 ) as $minr ) { ?>
			<option value="<?php echo esc_attr( $minr ); ?>" <?php selected( $options[$args['label_for']], $minr ); ?>><?php echo esc_attr__( $minr, 'wp-review-slider-pro' ); ?></option>
			<?php } ?>
		</select>		
		<p class="description">
			<?php echo  esc_html__('Optional: Use this if you need to add more than one URL, set this and click Save Settings to get another URL field.', 'wp-review-slider-pro'); ?>
		</p>
		<?php
		
	}

	public function airbnb_radio_display_cron($args)
		{
		$options = get_option('wprevpro_airbnb_settings');
		if(!isset($options['airbnb_radio_cron'])){
			$options['airbnb_radio_cron']='no';
		}

		   ?>
				<input type="radio" name="wprevpro_airbnb_settings[<?= esc_attr($args['label_for']); ?>]" value="yes" <?php checked('yes', $options[$args['label_for']], true); ?>><?php echo  esc_html__('Yes', 'wp-review-slider-pro'); ?>&nbsp;&nbsp;&nbsp;
				<input type="radio" name="wprevpro_airbnb_settings[<?= esc_attr($args['label_for']); ?>]" value="no" <?php checked('no', $options[$args['label_for']], true); ?>><?php echo  esc_html__('No', 'wp-review-slider-pro'); ?>
				<p class="description">
			<?php echo  esc_html__('Checks for new reviews every 24 hours.', 'wp-review-slider-pro'); ?>
				</p>
		   <?php
		}
		*/
	//=======end airbnb page settings========================================================
	
/**
	 * custom option and settings on vrbo page
	 */
	 //===========start vrbo page settings===========================================================
	 /*
	public function wppro_vrbo_settings_init()
	{
	
		// register a new setting for "wp_pro-get_airbnb" page
		register_setting('wp_pro-get_vrbo', 'wprevpro_vrbo_settings');
		
		// register a new section in the "wp_pro-get_vrbo" page
		add_settings_section(
			'wprevpro_vrbo_section_developers',
			'',
			array($this,'wppro_vrbo_section_developers_cb'),
			'wp_pro-get_vrbo'
		);

		//register vrbo business url input field
		add_settings_field(
			'vrbo_business_url', // as of WP 4.6 this value is used only internally
			__( 'VRBO Listing URL', 'wp-review-slider-pro' ),
			array($this,'wprevpro_field_vrbo_business_id_cb'),
			'wp_pro-get_vrbo',
			'wprevpro_vrbo_section_developers',
			[
				'label_for'         => 'vrbo_business_url',
				'class'             => 'wppro_row',
				'wprevpro_custom_data' => 'custom',
			]
		);

		//--------------
		//looping here based on how many we need
		$tempoptions = [];
		if(get_option('wprevpro_vrbo_settings')){
		$tempoptions = get_option('wprevpro_vrbo_settings');
		}
		if(!isset($tempoptions['vrbo_business_url_more'])){
			$tempoptions['vrbo_business_url_more']=1;
		}
		if($tempoptions['vrbo_business_url_more']>1){
			$loopnum = $tempoptions['vrbo_business_url_more'];
			for ($x = 2; $x <= $loopnum; $x++) {
				//register vrbo business url input field
				add_settings_field(
					"vrbo_business_url$x", // as of WP 4.6 this value is used only internally
					sprintf( __( 'VRBO Business URL %s', 'wp-review-slider-pro' ) , $x ),
					array($this,'wprevpro_field_vrbo_business_id_cb_more'),
					'wp_pro-get_vrbo',
					'wprevpro_vrbo_section_developers',
					[
						'label_for'         => "vrbo_business_url$x",
						'class'             => 'wprevpro_row vrbourladditional',
						'wprevpro_custom_data' => 'custom',
					]
				);
			}
		}	
		//add more url fields
		add_settings_field(
			'vrbo_business_url_more', 
			__( 'Total URLs', 'wp-review-slider-pro' ),
			array($this,'wprevpro_field_vrbo_more_urls'),
			'wp_pro-get_vrbo',
			'wprevpro_vrbo_section_developers',
			array(
				'label_for'         => 'vrbo_business_url_more',
				'class'             => 'wprevpro_row vrbourlmore'
			)
		);

		//Turn on vrbo Reviews cron
		add_settings_field("vrbo_radio_cron", __( 'Check Reviews 24 Hours', 'wp-review-slider-pro' ), array($this,'vrbo_radio_display_cron'), "wp_pro-get_vrbo", "wprevpro_vrbo_section_developers",
			[
				'label_for'         => 'vrbo_radio_cron',
				'class'             => 'wprevpro_row',
				'wprevpro_custom_data' => 'custom',
			]); 

	}
	//==== developers section cb ====
	public function wppro_vrbo_section_developers_cb($args)
	{
		//echos out at top of section
		echo "<p>".esc_html__('Use this page to download your newest VRBO business reviews and save them in your Wordpress database. They will show up on the Review List page once downloaded.', 'wp-review-slider-pro')."</p>"; 
	}
	
	//==== field cb =====
	public function wprevpro_field_vrbo_business_id_cb($args)
	{
		// get the value of the setting we've registered with register_setting()
		$options = get_option('wprevpro_vrbo_settings');
		$nameval = $args['label_for']."_name";
		if(!isset($options[$args['label_for']])){
			$options[$args['label_for']]='';
		}
		if(!isset($options[$nameval])){
			$options[$nameval]='';
		}
		$nameval =$options[$nameval];
		
		// output the field
		?>
		<input class='vrbo_business_url_name' id="<?= esc_attr($args['label_for']); ?>_name" data-custom="<?= esc_attr($args['wprevpro_custom_data']); ?>" type="text" name="wprevpro_vrbo_settings[<?= esc_attr($args['label_for']); ?>_name]" placeholder="<?= esc_html__('Unique Location Name', 'wp-review-slider-pro'); ?>" value="<?php echo $nameval; ?>">
		
		<input class='vrbo_business_url' id="<?= esc_attr($args['label_for']); ?>" data-custom="<?= esc_attr($args['wprevpro_custom_data']); ?>" type="text" name="wprevpro_vrbo_settings[<?= esc_attr($args['label_for']); ?>]" placeholder="<?= esc_html__('Location URL', 'wp-review-slider-pro'); ?>" value="<?php echo $options[$args['label_for']]; ?>">
		<?php
		if( ! empty( $options[$args['label_for']] )) {
		?>
		<button onclick="getvrboreviewsfunction('1')" id="wpfbr_getvrboreviews" type="button" class="btn_green"><?= esc_html__('Retrieve Reviews', 'wp-review-slider-pro'); ?></button><br/>
		<?php
		}
		?>
		
		<p class="description">
			<?= esc_html__('Enter a name for your location and the VRBO URL then click Save Settings. Example:', 'wp-review-slider-pro'); ?>
			</br>
			https://www.vrbo.com/471229
			
		</p>
		<?php
	}
	
	public function wprevpro_field_vrbo_business_id_cb_more($args)
	{
		// get the value of the setting we've registered with register_setting()
		$options = get_option('wprevpro_vrbo_settings');
		$temparg = esc_attr($args['label_for']);
		$nameval = $args['label_for']."_name";
		if(!isset($options[$temparg])){
			$options[$temparg]='';
		}
		if(!isset($options[$nameval])){
			$options[$nameval]='';
		}
		//find url num from id
		$urlnum = esc_attr($args['label_for']);
		$urlnum = str_replace("vrbo_business_url","",$urlnum);
		// output the field

		$nameval =$options[$nameval];
		?>
		<input class='vrbo_business_url_name' id="<?= esc_attr($args['label_for']); ?>_name" data-custom="<?= esc_attr($args['wprevpro_custom_data']); ?>" type="text" name="wprevpro_vrbo_settings[<?= esc_attr($args['label_for']); ?>_name]" placeholder="<?= esc_html__('Unique Location Name', 'wp-review-slider-pro'); ?>" value="<?php echo $nameval; ?>">
		
		<input class='vrbo_business_url' id="<?= esc_attr($args['label_for']); ?>" data-custom="<?= esc_attr($args['wprevpro_custom_data']); ?>" type="text" name="wprevpro_vrbo_settings[<?= esc_attr($args['label_for']); ?>]" placeholder="<?= esc_html__('Location URL', 'wp-review-slider-pro'); ?>" value="<?php echo $options[$args['label_for']]; ?>">
		<?php
		if( ! empty( $options[$args['label_for']] )) {
		?>
		<button onclick="getvrboreviewsfunction('<?php echo $urlnum; ?>')" id="wpfbr_getvrboreviews" type="button" class="btn_green"><?= esc_html__('Retrieve Reviews', 'wp-review-slider-pro'); ?></button><br/>
		<?php
		}
		?>
		
		<p class="description">
			<?= esc_html__('Optional: If you have more than one VRBO page.', 'wp-review-slider-pro'); ?>
		</p>
		<?php
	}
	
	public function wprevpro_field_vrbo_more_urls($args)
	{
		$options = get_option('wprevpro_vrbo_settings');
		if(!isset($options[$args['label_for']])){
			$options[$args['label_for']]='';
		}
		
		?>
		<select id="<?php echo  esc_attr($args['label_for']); ?>" name="wprevpro_vrbo_settings[<?php echo  esc_attr($args['label_for']); ?>]">
			<?php foreach( range( 1, 50 ) as $minr ) { ?>
			<option value="<?php echo esc_attr( $minr ); ?>" <?php selected( $options[$args['label_for']], $minr ); ?>><?php echo esc_attr__( $minr, 'wp-review-slider-pro' ); ?></option>
			<?php } ?>
		</select>		
		<p class="description">
			<?php echo  esc_html__('Optional: Use this if you need to add more than one URL, set this and click Save Settings to get another URL field.', 'wp-review-slider-pro'); ?>
		</p>
		<?php
		
	}

	public function vrbo_radio_display_cron($args)
		{
		$options = get_option('wprevpro_vrbo_settings');
		if(!isset($options['vrbo_radio_cron'])){
			$options['vrbo_radio_cron']='no';
		}

		   ?>
				<input type="radio" name="wprevpro_vrbo_settings[<?= esc_attr($args['label_for']); ?>]" value="yes" <?php checked('yes', $options[$args['label_for']], true); ?>><?php echo  esc_html__('Yes', 'wp-review-slider-pro'); ?>&nbsp;&nbsp;&nbsp;
				<input type="radio" name="wprevpro_vrbo_settings[<?= esc_attr($args['label_for']); ?>]" value="no" <?php checked('no', $options[$args['label_for']], true); ?>><?php echo  esc_html__('No', 'wp-review-slider-pro'); ?>
				<p class="description">
			<?php echo  esc_html__('Checks for new reviews every 24 hours. There is currently no way to get old reviews. You will need to manually add them.', 'wp-review-slider-pro'); ?>
				</p>
		   <?php
		}
	//=======end vrbo page settings========================================================
	*/

	// custom option and settings on notifications page wprevpro_notifications_settings_init
	//===============start setttings / notifications page=====================================================

	//for notification fields
	public function wprevpro_notifications_settings_init()
	{
		// register a new setting for "wp_pro-notifications" page
		register_setting('wp_pro-notifications', 'wprevpro_notifications_settings');
		
		// register a new section in the "wp_pro-notifications" page or language settings
		
		add_settings_section(
			'wprevpro_language_section_developers',
			'',
			array($this,'wprevpro_language_section_developers_cb'),
			'wp_pro-notifications'
		);
		
		//yandex key
		add_settings_field(
			"api_key", 
			__( 'API Key', 'wp-review-slider-pro' ),
			array($this,'wprevpro_yandex_api_key'), 
			"wp_pro-notifications", 
			"wprevpro_language_section_developers",
			[
				'label_for'         => 'api_key',
				'class'             => 'wprevpro_row apikey',
				'wprevpro_custom_data' => 'custom',
			]);
				
		//turn on langsupport
		add_settings_field(
			"auto_lang_code", 
			__( 'Auto Add Language Code', 'wp-review-slider-pro' ),
			array($this,'wprevpro_multilang_support'), 
			"wp_pro-notifications", 
			"wprevpro_language_section_developers",
			[
				'label_for'         => 'auto_lang_code',
				'class'             => 'wprevpro_row langsupport',
				'wprevpro_custom_data' => 'custom',
			]);

			
	}	
	
		//==== developers section cb ====
	public function wprevpro_language_section_developers_cb($args)
	{
		//echos out at top of section
	
		echo "<div class='notifications_sections'><h3>".esc_html__('Multi-Language Detector', 'wp-review-slider-pro')."</h3><p>".esc_html__('Allows you to check and set the language of each review. You can then filter by language in a review template.', 'wp-review-slider-pro')."</p>";
	}
	
	
	public function wprevpro_multilang_support($args)
	{
		$options = get_option('wprevpro_notifications_settings');
		$tempkey = $args['label_for'];
		
		if(!isset($options[$tempkey]) || $options[$tempkey]==''){
			$options[$tempkey]='';
		}
		 ?>
			<select id="<?php echo  esc_attr($args['label_for']); ?>" name="wprevpro_notifications_settings[<?php echo esc_attr($args['label_for']); ?>]">
			<option value="" <?php selected( $options[$args['label_for']], "" ); ?>><?= esc_html__('No', 'wp-review-slider-pro'); ?></option>
			<option value="1" <?php selected( $options[$args['label_for']], 1); ?>><?= esc_html__('Yes', 'wp-review-slider-pro'); ?></option>
			</select>	
		<p class="description">
			<?= esc_html__('Automatically check reviews nightly and add language code.', 'wp-review-slider-pro'); ?>
			
		</p>
		<?php

	}

	public function wprevpro_yandex_api_key($args)
	{
	$tempkey = $args['label_for'];
	$options = get_option('wprevpro_notifications_settings');

	if(!isset($options[$tempkey]) || $options[$tempkey]==''){
		$options[$tempkey]='';
	}
	   ?>
		<input class="regular-text" id="<?= esc_attr($args['label_for']); ?>" data-custom="<?= esc_attr($args['wprevpro_custom_data']); ?>" type="text" name="wprevpro_notifications_settings[<?= esc_attr($args['label_for']); ?>]" placeholder="" value="<?php echo $options[$tempkey]; ?>">
		<?php
		if($options[$tempkey]!=''){
			echo '<button id="lang_detect_btn" type="button" class="btn_green">'.esc_html__('Run Language Detector', 'wp-review-slider-pro').'</button>';
			}
		?>
	
	<p class="description">
		<?= esc_html__('Enter your API key. Get a free key ', 'wp-review-slider-pro'); ?><a href="https://detectlanguage.com/" target="_blank"><?= esc_html__('here.', 'wp-review-slider-pro'); ?></a>
	</p>
	   <?php
	}
		
	//=======end notifications page settings========================================================
	
	
	
	
/**
	 * custom option and settings on tripadvisor page
	 */
	 //===========start tripadvisor page settings===========================================================
	 /*
	public function wprevpro_tripadvisor_settings_init()
	{
	
		// register a new setting for "wp_pro-get_tripadvisor" page
		register_setting('wp_pro-get_tripadvisor', 'wprevpro_tripadvisor_settings');
		
		// register a new section in the "wp_pro-get_tripadvisor" page
		add_settings_section(
			'wprevpro_tripadvisor_section_developers',
			'',
			array($this,'wprevpro_tripadvisor_section_developers_cb'),
			'wp_pro-get_tripadvisor'
		);
		
		//register tripadvisor business url input field
		add_settings_field(
			'tripadvisor_business_url', // as of WP 4.6 this value is used only internally
			__( 'TripAdvisor Business URL', 'wp-review-slider-pro' ),
			array($this,'wprevpro_field_tripadvisor_business_id_cb'),
			'wp_pro-get_tripadvisor',
			'wprevpro_tripadvisor_section_developers',
			[
				'label_for'         => 'tripadvisor_business_url',
				'class'             => 'wprevpro_row tripadvisorurl1',
				'wprevpro_custom_data' => 'custom',
			]
		);
		
		//--------------
		//looping here based on how many we need
		$tempoptions = get_option('wprevpro_tripadvisor_settings');
		if(!isset($tempoptions['tripadvisor_business_url_more'])){
			$tempoptions['tripadvisor_business_url_more']=1;
		}
		if($tempoptions['tripadvisor_business_url_more']>1){
			$loopnum = $tempoptions['tripadvisor_business_url_more'];
			for ($x = 2; $x <= $loopnum; $x++) {
				//register tripadvisor business url input field
				add_settings_field(
					"tripadvisor_business_url$x", // as of WP 4.6 this value is used only internally
					sprintf( __( 'TripAdvisor Business URL %s', 'wp-review-slider-pro' ) , $x ),
					array($this,'wprevpro_field_tripadvisor_business_id_cb_more'),
					'wp_pro-get_tripadvisor',
					'wprevpro_tripadvisor_section_developers',
					[
						'label_for'         => "tripadvisor_business_url$x",
						'class'             => 'wprevpro_row tripadvisorurladditional',
						'wprevpro_custom_data' => 'custom',
					]
				);
			}
		
		}
		//------------------------------
		//add more url fields
		add_settings_field(
			'tripadvisor_business_url_more', 
			__( 'Total URLs', 'wp-review-slider-pro' ),
			array($this,'wprevpro_field_tripadvisor_more_urls'),
			'wp_pro-get_tripadvisor',
			'wprevpro_tripadvisor_section_developers',
			array(
				'label_for'         => 'tripadvisor_business_url_more',
				'class'             => 'wprevpro_row tripadvisorurlmore'
			)
		);

		//Turn on TripAdvisor Reviews cron
		add_settings_field("tripadvisor_radio_cron", __( 'Check Reviews 24 Hours', 'wp-review-slider-pro' ), array($this,'tripadvisor_radio_display_cron'), "wp_pro-get_tripadvisor", "wprevpro_tripadvisor_section_developers",
			[
				'label_for'         => 'tripadvisor_radio_cron',
				'class'             => 'wprevpro_row',
				'wprevpro_custom_data' => 'custom',
			]); 
		
		//Turn on TripAdvisor Reviews method
		add_settings_field("tripadvisor_scrape_method", __( 'Retrieval Method', 'wp-review-slider-pro' ), array($this,'tripadvisor_scrape_display_method'), "wp_pro-get_tripadvisor", "wprevpro_tripadvisor_section_developers",
			[
				'label_for'         => 'tripadvisor_scrape_method',
				'class'             => 'wprevpro_row',
				'wprevpro_custom_data' => 'custom',
			]); 		

	}
	//==== developers section cb ====
	public function wprevpro_tripadvisor_section_developers_cb($args)
	{
		//echos out at top of section
		//echo __('<p>Note: You can use this page to download TripAdvisor reviews, but it is more reliable to use the Get Reviews > Review Funnel method.</p><p>Your reviews will show up on the Review List page once downloaded. <br><br><b>Note:</b> <br>- Hotel Reviews may or may not work. You can always use a Review Funnel to grab them. <br>- If you need to download old TripAdvisor reviews check this <a href="https://wpreviewslider.userecho.com/knowledge-bases/2/articles/356-how-to-download-old-tripadvisor-reviews" target="_blank">link</a>.</p>', 'wp-review-slider-pro');
	}
	
	//==== field cb =====
	public function wprevpro_field_tripadvisor_business_id_cb($args)
	{
		// get the value of the setting we've registered with register_setting()
		$options = get_option('wprevpro_tripadvisor_settings');
		$nameval = $args['label_for']."_name";
		if(!isset($options[$args['label_for']])){
			$options[$args['label_for']]='';
		}
		if(!isset($options[$nameval])){
			$options[$nameval]='';
		}
		
		$nameval =$options[$nameval];
		// output the field
		?>
		<input class='' id="<?= esc_attr($args['label_for']); ?>_name" data-custom="" type="text" name="wprevpro_tripadvisor_settings[<?= esc_attr($args['label_for']); ?>_name]" placeholder="<?= esc_html__('Unique Location Name', 'wp-review-slider-pro'); ?>" value="<?php echo $nameval; ?>">
		<input class='tripadvisor_business_url' id="<?= esc_attr($args['label_for']); ?>" data-custom="<?= esc_attr($args['wprevpro_custom_data']); ?>" type="text" placeholder="<?= esc_html__('Location URL', 'wp-review-slider-pro'); ?>" name="wprevpro_tripadvisor_settings[<?= esc_attr($args['label_for']); ?>]" placeholder="" value="<?php echo $options[$args['label_for']]; ?>">
		<?php
		if( ! empty( $options[$args['label_for']] )) {
		?>
		<button onclick="gettripadvisorreviewsfunction('1')" id="wpfbr_gettripadvisorreviews" type="button" class="btn_green"><?= esc_html__('Retrieve Reviews', 'wp-review-slider-pro'); ?></button><br/>
		<?php
		}
		?>
		
		<p class="description">
			<?= esc_html__('Enter a name for this location and the TripAdvisor URL and then click Save Settings. Example:', 'wp-review-slider-pro'); ?>
			</br>
https://www.tripadvisor.com/Restaurant_Review-g34346-d804726-Reviews-Kaiyo_Grill_Sushi-Islamorada_Florida_Keys_Florida.html
		</p>
		<?php
	}
	
	public function wprevpro_field_tripadvisor_business_id_cb_more($args)
	{
		// get the value of the setting we've registered with register_setting()
		$options = get_option('wprevpro_tripadvisor_settings');
		$nameval = $args['label_for']."_name";
		
		$temparg = esc_attr($args['label_for']);
		if(!isset($options[$temparg])){
			$options[$temparg]='';
		}
		//find url num from id
		$urlnum = esc_attr($args['label_for']);
		$urlnum = str_replace("tripadvisor_business_url","",$urlnum);
		
		if(!isset($options[$nameval])){
			$options[$nameval]='';
		}
		$nameval =$options[$nameval];
		
		// output the field
		
		?>
		<input class='' id="<?= esc_attr($args['label_for']); ?>_name" data-custom="" type="text" name="wprevpro_tripadvisor_settings[<?= esc_attr($args['label_for']); ?>_name]" placeholder="<?= esc_html__('Unique Location Name', 'wp-review-slider-pro'); ?>" value="<?php echo $nameval; ?>">
		<input class='tripadvisor_business_url' placeholder="<?= esc_html__('Location URL', 'wp-review-slider-pro'); ?>" id="<?= esc_attr($args['label_for']); ?>" data-custom="<?= esc_attr($args['wprevpro_custom_data']); ?>" type="text" name="wprevpro_tripadvisor_settings[<?= esc_attr($args['label_for']); ?>]" placeholder="" value="<?php echo $options[$args['label_for']]; ?>">
		<?php
		if( ! empty( $options[$args['label_for']] )) {
		?>
		<button onclick="gettripadvisorreviewsfunction('<?php echo $urlnum; ?>')" id="wpfbr_gettripadvisorreviews" type="button" class="btn_green"><?= esc_html__('Retrieve Reviews', 'wp-review-slider-pro'); ?></button><br/>
		<?php
		}
		?>
		
		<p class="description">
			<?= esc_html__('Optional: If you have more than one TripAdvisor page.', 'wp-review-slider-pro'); ?>
		</p>
		<?php
	}
	
	public function wprevpro_field_tripadvisor_more_urls($args)
	{
		$options = get_option('wprevpro_tripadvisor_settings');
		if(!isset($options[$args['label_for']])){
			$options[$args['label_for']]=1;
		}
		?>
		<select id="<?php echo  esc_attr($args['label_for']); ?>" name="wprevpro_tripadvisor_settings[<?php echo  esc_attr($args['label_for']); ?>]">
			<?php foreach( range( 1, 15 ) as $minr ) { ?>
			<option value="<?php echo esc_attr( $minr ); ?>" <?php selected( $options[$args['label_for']], $minr ); ?>><?php echo esc_attr( $minr ); ?></option>
			<?php } ?>
		</select>		
		<p class="description">
			<?php echo  esc_html__('Optional: Use this if you need to add more than one URL, set this and click Save Settings to get another URL field.', 'wp-review-slider-pro'); ?>
		</p>
		<?php
		
	}

	public function tripadvisor_radio_display_cron($args)
		{
		$options = get_option('wprevpro_tripadvisor_settings');
		if(!isset($options['tripadvisor_radio_cron'])){
			$options['tripadvisor_radio_cron']='no';
		}

		   ?>
				<input type="radio" name="wprevpro_tripadvisor_settings[<?= esc_attr($args['label_for']); ?>]" value="yes" <?php checked('yes', $options[$args['label_for']], true); ?>><?php echo  esc_html__('Yes', 'wp-review-slider-pro'); ?>&nbsp;&nbsp;&nbsp;
				<input type="radio" name="wprevpro_tripadvisor_settings[<?= esc_attr($args['label_for']); ?>]" value="no" <?php checked('no', $options[$args['label_for']], true); ?>><?php echo  esc_html__('No', 'wp-review-slider-pro'); ?>
				<p class="description">
			<?php echo  esc_html__('Checks for new reviews every 24 hours. There is currently no way to get old reviews. You will need to manually add them.', 'wp-review-slider-pro'); ?>
				</p>
		   <?php
		}
		
		public function tripadvisor_scrape_display_method($args)
		{
		$options = get_option('wprevpro_tripadvisor_settings');
		if(!isset($options['tripadvisor_scrape_method'])){
			$options['tripadvisor_scrape_method']='default';
		}

		   ?>
		<select id="<?php echo  esc_attr($args['label_for']); ?>" name="wprevpro_tripadvisor_settings[<?php echo  esc_attr($args['label_for']); ?>]">
			<option value="default" <?php selected( $options[$args['label_for']], 'default' ); ?>><?php echo  esc_html__('default', 'wp-review-slider-pro'); ?></option>
			<option value="phantom" <?php selected( $options[$args['label_for']], 'phantom' ); ?>><?php echo  esc_html__('alternative', 'wp-review-slider-pro'); ?></option>
		</select>		
		<p class="description">
			<?php echo  esc_html__('Default will work in most cases. If you have trouble retrieving reviews then try the alternative method. For instance, if the reviews avatars are missing, then delete the reviews and try the other method.', 'wp-review-slider-pro'); ?>
		</p>
		   <?php
		}

*/
	//=======end tripadvisor page settings========================================================
	



	/**
	 * custom option and settings on yelp page
	 */
	 //===========start yelp page settings===========================================================
	 /*
	public function wprevpro_yelp_settings_init()
	{
	
		// register a new setting for "wp_pro-get_yelp" page
		register_setting('wp_pro-get_yelp', 'wprevpro_yelp_settings');
		
		// register a new section in the "wp_pro-get_yelp" page
		add_settings_section(
			'wprevpro_yelp_section_developers',
			'',
			array($this,'wprevpro_yelp_section_developers_cb'),
			'wp_pro-get_yelp'
		);
		
		//register yelp business url input field
		add_settings_field(
			'yelp_business_url', // as of WP 4.6 this value is used only internally
			__( 'Yelp Business URL', 'wp-review-slider-pro'),
			array($this,'wprevpro_field_yelp_business_id_cb'),
			'wp_pro-get_yelp',
			'wprevpro_yelp_section_developers',
			[
				'label_for'         => 'yelp_business_url',
				'class'             => 'wprevpro_row yelpurl1',
				'wprevpro_custom_data' => 'custom',
			]
		);
		//looping here based on how many we need
		$tempoptions = get_option('wprevpro_yelp_settings');
		if(!isset($tempoptions['yelp_business_url_more'])){
			$tempoptions['yelp_business_url_more']=1;
			update_option('wprevpro_yelp_settings',$tempoptions);
		}
		if($tempoptions['yelp_business_url_more']>1){
			$loopnum = $tempoptions['yelp_business_url_more'];
			for ($x = 2; $x <= $loopnum; $x++) {
				//register yelp business url input field
				add_settings_field(
					"yelp_business_url$x", // as of WP 4.6 this value is used only internally
					sprintf( __( 'Yelp Business URL %s', 'wp-review-slider-pro') , $x ),
					array($this,'wprevpro_field_yelp_business_id_cb_more'),
					'wp_pro-get_yelp',
					'wprevpro_yelp_section_developers',
					[
						'label_for'         => "yelp_business_url$x",
						'class'             => 'wprevpro_row yelpurladditional',
						'wprevpro_custom_data' => 'custom',
					]
				);
			}
		
		}
		
		//add more url fields
		add_settings_field(
			'yelp_business_url_more', 
			__( 'Total URLs', 'wp-review-slider-pro'),
			array($this,'wprevpro_field_yelp_more_urls'),
			'wp_pro-get_yelp',
			'wprevpro_yelp_section_developers',
			array(
				'label_for'         => 'yelp_business_url_more',
				'class'             => 'wprevpro_row yelpurlmore'
			)
		);
		
		//Turn on Yelp Reviews Downloader
		add_settings_field("yelp_radio", __( 'Get Reviews Every 24 Hours', 'wp-review-slider-pro'), array($this,'yelp_radio_display'), "wp_pro-get_yelp", "wprevpro_yelp_section_developers",
			[
				'label_for'         => 'yelp_radio',
				'class'             => 'wprevpro_row',
				'wprevpro_custom_data' => 'custom',
			]); 
		add_settings_field("yelp_radio_rule", __( 'Insert Rule', 'wp-review-slider-pro'), array($this,'yelp_radio_display_rule'), "wp_pro-get_yelp", "wprevpro_yelp_section_developers",
			[
				'label_for'         => 'yelp_radio_rule',
				'class'             => 'wprevpro_row',
				'wprevpro_custom_data' => 'custom',
			]); 
	
	}
	//==== developers section cb ====
	public function wprevpro_yelp_section_developers_cb($args)
	{
		//echos out at top of section
		echo __('<p>Use this page to download your Yelp business reviews and save them in your Wordpress database. They will show up on the Review List page once downloaded. A couple of things to note.</p>
		<ul>
			<li> - This will try to download your most recent 80 reviews. To get all of them go to Get Reviews > Review Funnels. </li>
			<li> - Yelp reviews should only be cached for 24 hours. </li>
			<li> - They must contain a link to your Yelp business page and display the Yelp logo and review stars. We do handle this in our templates.</li>
		</ul>', 'wp-review-slider-pro');
		
	}
	
	//==== field cb =====
	public function wprevpro_field_yelp_business_id_cb($args)
	{
		// get the value of the setting we've registered with register_setting()
		$options = get_option('wprevpro_yelp_settings');
		if(!isset($options[$args['label_for']])){
			$options[$args['label_for']]='';
		}
		// output the field
		?>
		<input class='yelp_business_url' id="<?= esc_attr($args['label_for']); ?>" data-custom="<?= esc_attr($args['wprevpro_custom_data']); ?>" type="text" name="wprevpro_yelp_settings[<?= esc_attr($args['label_for']); ?>]" placeholder="" value="<?php echo $options[$args['label_for']]; ?>">
		<?php
		if( ! empty( $options[$args['label_for']] )) {
		?>
		<button onclick="getyelpreviewsfunction('1')" id="wpfbr_getyelpreviews" type="button" class="btn_green"><?= esc_html__('Retrieve Reviews', 'wp-review-slider-pro'); ?></button><br/>
		<?php
		}
		?>
		<p class="description">
			<?= esc_html__('Enter the Yelp URL for your business and click Save Settings. Example:', 'wp-review-slider-pro'); ?>
			</br>
			https://www.yelp.com/biz/earth-and-stone-wood-fired-pizza-huntsville-2

		</p>
		<?php
	}
	public function wprevpro_field_yelp_business_id_cb_more($args)
	{
		// get the value of the setting we've registered with register_setting()
		$options = get_option('wprevpro_yelp_settings');
		if(!isset($options[$args['label_for']])){
			$options[$args['label_for']]='';
		}
		// output the field
		//find url num from id
		$urlnum = esc_attr($args['label_for']);
		$urlnum = str_replace("yelp_business_url","",$urlnum);
		?>
		<input class='yelp_business_url' id="<?= esc_attr($args['label_for']); ?>" data-custom="<?= esc_attr($args['wprevpro_custom_data']); ?>" type="text" name="wprevpro_yelp_settings[<?= esc_attr($args['label_for']); ?>]" placeholder="" value="<?php echo $options[$args['label_for']]; ?>">
				<?php
		if( ! empty( $options[$args['label_for']] )) {
		?>
		<button onclick="getyelpreviewsfunction('<?php echo $urlnum; ?>')" id="wpfbr_getyelpreviews" type="button" class="btn_green"><?= esc_html__('Retrieve Reviews', 'wp-review-slider-pro'); ?></button><br/>
		<?php
		}
		?>
		<p class="description">
			<?= esc_html__('Optional: If you have more than one Yelp page.', 'wp-review-slider-pro'); ?>
		</p>
		<?php
	}
	
	public function wprevpro_field_yelp_more_urls($args)
	{
		$options = get_option('wprevpro_yelp_settings');
		
		?>
		<select id="<?php echo  esc_attr($args['label_for']); ?>" name="wprevpro_yelp_settings[<?php echo  esc_attr($args['label_for']); ?>]">
			<?php foreach( range( 1, 15 ) as $minr ) { ?>
			<option value="<?php echo esc_attr( $minr ); ?>" <?php selected( $options[$args['label_for']], $minr ); ?>><?php echo esc_attr( $minr ); ?></option>
			<?php } ?>
		</select>		
		<p class="description">
			<?php echo  esc_html__('Optional: Use this if you need to add more than one URL, set this and click Save Settings to get another URL field.', 'wp-review-slider-pro'); ?>
		</p>
		<?php
		
	}
	
	public function yelp_radio_display_rule($args)
		{
		$options = get_option('wprevpro_yelp_settings');
		if(!isset($options[$args['label_for']])){
			$options[$args['label_for']]='no';
		}
		
		   ?>
				<input type="radio" name="wprevpro_yelp_settings[<?= esc_attr($args['label_for']); ?>]" value="yes" <?php checked('yes', $options[$args['label_for']], true); ?>><?php echo  esc_html__('Refresh Reviews', 'wp-review-slider-pro'); ?>&nbsp;&nbsp;&nbsp;
				<input type="radio" name="wprevpro_yelp_settings[<?= esc_attr($args['label_for']); ?>]" value="no" <?php checked('no', $options[$args['label_for']], true); ?>><?php echo  esc_html__('Only Get New Reviews', 'wp-review-slider-pro'); ?>
				<p class="description">
			<?php echo  esc_html__('Set this to "Only Get New Reviews" in order to reduce your server load.', 'wp-review-slider-pro'); ?>
		</p>
		   <?php
		} //yelp_radio_download
		
	public function yelp_radio_display($args)
		{
		$options = get_option('wprevpro_yelp_settings');
		if(!isset($options[$args['label_for']])){
			$options[$args['label_for']]='';
		}
		   ?>
				<input type="radio" name="wprevpro_yelp_settings[<?= esc_attr($args['label_for']); ?>]" value="yes" <?php checked('yes', $options[$args['label_for']], true); ?>><?php echo  esc_html__('Yes', 'wp-review-slider-pro'); ?>&nbsp;&nbsp;&nbsp;
				<input type="radio" name="wprevpro_yelp_settings[<?= esc_attr($args['label_for']); ?>]" value="no" <?php checked('no', $options[$args['label_for']], true); ?>><?php echo  esc_html__('No', 'wp-review-slider-pro'); ?>
		   <?php
		} 
		//yelp_radio_download
	//=======end yelp page settings========================================================
*/
//--======================= GOOGLE =======================--//
/*
	public function wprevpro_google_settings_init()
	{

		// register a new setting for "wp_fb-google_settings" page
		register_setting('wp_fb-google_settings', 'wpfbr_google_options');

		// register a new section in the "wp_fb-google_settings" page
		add_settings_section(
			'wpfbr_section_developers_google',
			'',
			array($this,'wpfbr_section_developers_google_cb'),
			'wp_fb-google_settings'
		);
	 
		//register Google API key input field
		add_settings_field(
			'google_api_key', 
			__( 'Google API Key', 'wp-review-slider-pro'),
			array($this,'wpfbr_field_google_api_key_cb'),
			'wp_fb-google_settings',
			'wpfbr_section_developers_google',
			array(
				'label_for'         => 'google_api_key',
				'class'             => 'wpfbr_row'
			)
		);
		
		//register location type
		add_settings_field(
			'google_location_type', 
			__( 'Location Type', 'wp-review-slider-pro'),
			array($this,'wpfbr_location_type_cb'),
			'wp_fb-google_settings',
			'wpfbr_section_developers_google',
			array(
				'label_for'         => 'google_location_type',
				'class'             => 'wpfbr_loctype wpfbr_row wpfbr_hide2'
			)
		);
		//register location search field
		add_settings_field(
			'google_location_txt', 
			__( 'Location Search', 'wp-review-slider-pro'),
			array($this,'wpfbr_location_cb'),
			'wp_fb-google_settings',
			'wpfbr_section_developers_google',
			array(
				'label_for'         => 'google_location_txt',
				'class'             => 'wpfbr_locsearch wpfbr_row wpfbr_hide2'
			)
		);

		//register location field after autocomplete
		add_settings_field(
			'google_location_set', 
			__( 'Add Location', 'wp-review-slider-pro'),
			array($this,'wpfbr_location_set_cb'),
			'wp_fb-google_settings',
			'wpfbr_section_developers_google',
			array(
				'label_for'         => 'google_location_set', 
				'class'             => 'wpfbr_row wpfbr_hide2',
				'tarray'			=> array( 'google_location_set' => array( 'location'=>'', 'place_id'=>'', 'address'=>'' ) ),
			)
		);
		
		//--------------
		//looping here based on how many we need
		$tempoptions = get_option('wpfbr_google_options');
		//print_r($tempoptions);
		if(isset($tempoptions) && !isset($tempoptions['google_business_location_total'])){
			//first time seeing this find total of locations currently being used and set to that. if only using one then set to zero
			$numinuse = 1;
			for ($x = 2; $x <= 4; $x++) {
				if(isset($tempoptions["google_location_set$x"]["place_id"])){
					if($tempoptions["google_location_set$x"]["place_id"]!=''){
						$numinuse = $x;
					}
				}
			}
			$tempoptions['google_business_location_total']=$numinuse;
			update_option('wpfbr_google_options',$tempoptions);
		}
		if($tempoptions['google_business_location_total']>1){
			$loopnum = $tempoptions['google_business_location_total'];
			for ($x = 2; $x <= $loopnum; $x++) {
				$temptarray = "google_location_set".$x;
				add_settings_field(
					"google_location_set$x", 
					__( 'Location', 'wp-review-slider-pro')." $x",
					array($this,'wpfbr_location_set_cb'),
					'wp_fb-google_settings',
					'wpfbr_section_developers_google',
					array(
						'label_for'         => "google_location_set".$x, 
						'class'             => "wpfbr_row wpfbr_hide".$x." wpfbr_loc".$x."",
						'tarray'			=> array( $temptarray => array( 'location'=>'', 'place_id'=>'', 'address'=>'' ) ),
					)
				);
				
			}
		}
		
	
	
		//add more url fields
		add_settings_field(
			'google_business_location_total', 
			__( 'Total Locations', 'wp-review-slider-pro'),
			array($this,'wpfbr_google_locations_total'),
			'wp_fb-google_settings',
			'wpfbr_section_developers_google',
			array(
				'label_for'         => 'google_business_location_total',
				'class'             => 'wpfbr_row wpfbr_hide2'
			)
		);

		//fetch google reviews with a minimum of X rating
		add_settings_field(
			'google_location_minrating', 
			__( 'Minimum Rating', 'wp-review-slider-pro'),
			array($this,'wpfbr_location_minrating_cb'),
			'wp_fb-google_settings',
			'wpfbr_section_developers_google',
			array(
				'label_for'         => 'google_location_minrating',
				'class'             => 'wpfbr_row wpfbr_hide2'
			)
		);
		//run cron everyday to get 5 more reviews; google places API only gives 5 reviews;
		add_settings_field(
			'google_review_cron',
			__( 'Auto Fetch New Reviews', 'wp-review-slider-pro'),			
			array($this,'wpfbr_location_review_cron_cb'),
			'wp_fb-google_settings',
			'wpfbr_section_developers_google',
			array(
				'label_for'         => 'google_review_cron',
				'class'             => 'wpfbr_row wpfbr_hide2'
			)
		);
		
		//register last name save option
		add_settings_field(
			'google_last_name_option', // as of WP 4.6 this value is used only internally
			__( 'Last Name Save Option', 'wp-review-slider-pro'),	
			array($this,'wpfbr_location_google_last_name'),
			'wp_fb-google_settings',
			'wpfbr_section_developers_google',
			[
				'label_for'         => 'google_last_name_option',
				'class'             => ''
			]
		);
		
		//register language field
		add_settings_field(
			'google_language_option', // as of WP 4.6 this value is used only internally
			__( 'Language Code', 'wp-review-slider-pro'),
			array($this,'wpfbr_location_language'),
			'wp_fb-google_settings',
			'wpfbr_section_developers_google',
			[
				'label_for'         => 'google_language_option',
				'class'             => ''
			]
		);

		
		
	}	
	*/
/*
	// the values are defined at the add_settings_section() function.
	public function wpfbr_section_developers_google_cb($args)
	{
		//echos out at top of section
	}
*/
	//--======================= GOOGLE =======================--//
/*	
	public function wpfbr_field_google_api_key_cb($args)
	{
		// get the value of the setting we've registered with register_setting(), google_api_key
		$options = get_option('wpfbr_google_options');
		if(!isset($options[$args['label_for']])){
			$options[$args['label_for']]='';
		}
		if(!isset($options['select_google_api'])){
			$options['select_google_api']="default";
		}
			
		// output the field
		?>
		<select class="select_google_api" id="select_google_api" name="wpfbr_google_options[select_google_api]">
			<option value="default" <?php if(esc_attr($options['select_google_api'])=='default'){echo 'selected="selected"';} ?>><?php _e('Use Default API Key', 'wp-review-slider-pro'); ?></option>
			<option value="mine" <?php if(esc_attr($options['select_google_api'])=='mine'){echo 'selected="selected"';} ?>><?php _e('Use My API Key', 'wp-review-slider-pro'); ?></option>
		</select><button id="wpfbr_testgooglekey" type="button" class="button"><?php _e('Test API Key', 'wp-review-slider-pro'); ?></button>
		<p class="usedefaultkey"><?php _e('You can either use the default community API Key or create your own. It is more reliable to create and use your own key since the default key may exceed the daily call limit.', 'wp-review-slider-pro'); ?></p>
		<div class="showapikey">
			<input class="regular-text showapikey" id="<?php echo esc_attr($args['label_for']); ?>" type="text" name="wpfbr_google_options[<?php echo esc_attr($args['label_for']); ?>]" placeholder="Google API Key" value="<?php echo esc_attr( $options[$args['label_for']] ); ?>">
			<p class="showapikey"><?php _e('Once you have your Google API Key paste it in the box and click the "Save Settings" button.', 'wp-review-slider-pro'); ?> </p>
			<div class="showapikey" id="createbtns">
				<a href="http://ljapps.com/google-places-api-key/" target="_blank" id="fb_create_google_app_help" type="" class=""><?php _e('How To Get API Key', 'wp-review-slider-pro'); ?></a>
			</div>
		</div>
		<div id="wpfbr_result"></div>
		<?php
	}
	public function wpfbr_location_cb($args)
	{
		$options = get_option('wpfbr_google_options');
		if(!isset($options[$args['label_for']])){
			$options[$args['label_for']]='';
		}
		?>
		<input class="regular-text" id="<?php echo esc_attr($args['label_for']); ?>" type="text" name="wpfbr_google_options[<?php echo esc_attr($args['label_for']); ?>]" 
			placeholder="Enter a location" autocomplete="off" value="<?php echo esc_attr( $options[$args['label_for']] ); ?>">
		<p class="description">
			<?php echo  esc_html__('Search for your Location. Try &#34;City name, business name&#34; if you have problems searching for your business.', 'wp-review-slider-pro'); ?>
		</p>
		<p class="description">
			<?php echo  esc_html__('If you can\'t find your location with the search box then manually input the values below. Look them up and copy them from the map on this ', 'wp-review-slider-pro'); ?>
			<a href="https://developers.google.com/maps/documentation/javascript/examples/places-placeid-finder"  target="_blank"><?php echo  esc_html__('page', 'wp-review-slider-pro'); ?></a>.
		</p>
		<?php
		
	}
	public function wpfbr_location_type_cb($args)
	{
		// get the value of the setting we've registered with register_setting()
		$options = get_option('wpfbr_google_options');

		$location_type = array( "all" => __('All','wp-review-slider-pro'), "address"=>__('Address','wp-review-slider-pro'), "establishment" => __('Establishment','wp-review-slider-pro'), "(regions)" => __('Regions','wp-review-slider-pro') );
		// output the field

		if(!isset($options[$args['label_for']])){
			$options[$args['label_for']]='';
		}
		
		?>
		<select id="<?php echo  esc_attr($args['label_for']); ?>" name="wpfbr_google_options[<?php echo  esc_attr($args['label_for']); ?>]">
			<?php foreach( $location_type as $location_i => $location_v ) { ?>
			<option value="<?php echo esc_attr( $location_i ); ?>" <?php selected( $options[$args['label_for']], $location_i ); ?>><?php echo esc_attr( $location_v ); ?></option>
			<?php } ?>
		</select>		
		<p class="description">
			<?php echo  esc_html__('Enter Location Type.', 'wp-review-slider-pro'); ?>
		</p>
		<?php

	}

	public function wpfbr_location_set_cb( $args )
	{
		$options = get_option('wpfbr_google_options');
		?>
		<button id="wpfbr_btn_lookup_<?php echo $args['label_for'];?>" type="button" currentlocbtn="<?php echo $args['label_for'];?>" class="btn_small_lookup locationlookupbtn button-secondary"><?php echo  esc_html__('Click to Find Location', 'wp-review-slider-pro'); ?></button></br>
		<?php
		foreach( $args['tarray'] as $label=>$val ){
			foreach( $val as $labeli=>$valv ){
			if(!isset($options[$label][$labeli])){
				$options[$label][$labeli]="";
			}
		?>
		
		<label>
		<input class="regular-text" id="wpfbr_<?php echo esc_attr($label)."_".esc_attr($labeli); ?>" type="text"  
				name="wpfbr_google_options[<?php echo esc_attr($label); ?>][<?php echo esc_attr($labeli); ?>]" 
				placeholder="<?php _e('No Location set','wp-review-slider-pro');?>" value="<?php echo esc_attr($options[$label][$labeli]); ?>"> <?php echo ucfirst( $labeli ); ?>
		</label><br/>
		<?php
		}}
		
	}
	
	public function wpfbr_google_locations_total($args)
	{
		$options = get_option('wpfbr_google_options');
		//print_r($options);
		if(!isset($options[$args['label_for']])){
			$options[$args['label_for']]=1;
		}
		?>
		<select id="<?php echo  esc_attr($args['label_for']); ?>" name="wpfbr_google_options[<?php echo  esc_attr($args['label_for']); ?>]">
			<?php foreach( range( 1, 350 ) as $minr ) { ?>
			<option value="<?php echo esc_attr( $minr ); ?>" <?php selected( $options[$args['label_for']], $minr ); ?>><?php echo esc_attr( $minr ); ?></option>
			<?php } ?>
		</select>		
		
			<?php echo  esc_html__('Optional: Use this if you need to add more than one Location, set this and click Save Settings to get another field.', 'wp-review-slider-pro'); ?>
		
		<?php
		
	}
	
	public function wpfbr_location_minrating_cb($args)
	{
		$options = get_option('wpfbr_google_options');
		if(!isset($options[$args['label_for']])){
			$options[$args['label_for']]=1;
		}
		?>
		<select id="<?php echo  esc_attr($args['label_for']); ?>" name="wpfbr_google_options[<?php echo  esc_attr($args['label_for']); ?>]">
			<?php foreach( range( 1, 5 ) as $minr ) { ?>
			<option value="<?php echo esc_attr( $minr ); ?>" <?php selected( $options[$args['label_for']], $minr ); ?>><?php echo esc_attr( $minr ); ?></option>
			<?php } ?>
		</select>		
		
			<?php echo  esc_html__('Only import reviews with a minimum rating of X.', 'wp-review-slider-pro'); ?>
		
		<?php
		
	}

	public function wpfbr_location_review_cron_cb($args)
	{
		$options = get_option('wpfbr_google_options');
		if(!isset($options[$args['label_for']])){
			$options[$args['label_for']]='';
		}
		?>
		<input type="checkbox" id="<?php echo  esc_attr($args['label_for']); ?>" name="wpfbr_google_options[<?php echo esc_attr($args['label_for']); ?>]" value="1" <?php checked( $options[$args['label_for']], "1" ); ?>/>
		
		<?php echo  esc_html__('Run a wp-cron to check for new reviews and add them. Google only returns 5 reviews in their API, this option allows you to check and see if they added a new one automatically. Use the Get Reviews > Review Funnel page to download all your reviews.', 'wp-review-slider-pro'); ?>
		
		<?php
		
	}
		public function wpfbr_location_google_last_name($args)
	{
		// get the value of the setting we've registered with register_setting()
		$options = get_option('wpfbr_google_options');
		if(!isset($options[$args['label_for']])){
			$options[$args['label_for']]='full';
		}
		//print_r($options);
		// output the field
		?>
				<input type="radio" name="wpfbr_google_options[<?= esc_attr($args['label_for']); ?>]" value="full" <?php checked('full', $options[$args['label_for']], true); ?>>Full Last Name&nbsp;&nbsp;&nbsp;
				<input type="radio" name="wpfbr_google_options[<?= esc_attr($args['label_for']); ?>]" value="initial" <?php checked('initial', $options[$args['label_for']], true); ?>>Initial Only&nbsp;&nbsp;&nbsp;
				<input type="radio" name="wpfbr_google_options[<?= esc_attr($args['label_for']); ?>]" value="nothing" <?php checked('nothing', $options[$args['label_for']], true); ?>>Nothing
				<p class="description">
			<?php echo  esc_html__('Set this to change the way the last name is saved in your database.', 'wp-review-slider-pro'); ?>
		</p>
		<?php
	}
		public function wpfbr_location_language($args)
	{
		// get the value of the setting we've registered with register_setting()
		$options = get_option('wpfbr_google_options');
		if(!isset($options[$args['label_for']])){
			$options[$args['label_for']]='';
		}
		//print_r($options);
		// output the field
		?>
		<input class="regular-text" id="<?php echo esc_attr($args['label_for']); ?>" type="text" name="wpfbr_google_options[<?php echo esc_attr($args['label_for']); ?>]" value="<?php echo esc_attr( $options[$args['label_for']] ); ?>">
				<p class="description">
			<?php echo  esc_html__('Optional: The language code, indicating in which language the results should be returned, if possible. Click ', 'wp-review-slider-pro'); ?><a href="https://developers.google.com/maps/faq#languagesupport" target='_blank'><?php echo  esc_html__('here', 'wp-review-slider-pro'); ?></a>
			<?php echo  esc_html__(' for the language codes.', 'wp-review-slider-pro'); ?>
		</p>
		<?php
	}
	

		//--======================= GOOGLE =======================--//
	*/	
		
		//--======================= Facebook =======================--//
	/**
	 * custom option and settings on Facebook settings page
	 */
	 /*
	public function wprevpro_settings_init()
	{
		// register a new setting for "wp_pro-settings" page
		register_setting('wp_pro-settings', 'wprevpro_options');
	 
		// register a new section in the "wp_pro-settings" page
		add_settings_section(
			'wprevpro_section_developers',
			'',
			array($this,'wprevpro_section_developers_cb'),
			'wp_pro-settings'
		);
		//register fb code from fbapp.ljapps.com input field
		add_settings_field(
			'fb_app_code', // as of WP 4.6 this value is used only internally
			__( 'Secret Access Code', 'wp-review-slider-pro'),
			array($this,'wprevpro_field_fb_code_cb'),
			'wp_pro-settings',
			'wprevpro_section_developers',
			[
				'label_for'         => 'fb_app_code',
				'class'             => 'wprevpro_row',
				'wpfbr_custom_data' => 'custom',
			]
		);
		//register last name save option
		add_settings_field(
			'fb_last_name_option', // as of WP 4.6 this value is used only internally
			__( 'Last Name Save Option', 'wp-review-slider-pro'),
			array($this,'wprevpro_field_fb_last_name'),
			'wp_pro-settings',
			'wprevpro_section_developers',
			[
				'label_for'         => 'fb_last_name_option',
				'class'             => ''
			]
		);
		//recommendations as star value
		add_settings_field(
			'fb_recommendation_to_star', // as of WP 4.6 this value is used only internally
			__( 'Recommendations', 'wp-review-slider-pro'),
			array($this,'wprevpro_field_fb_recommendation_to_star'),
			'wp_pro-settings',
			'wprevpro_section_developers',
			[
				'label_for'         => 'fb_recommendation_to_star',
				'class'             => ''
			]
		);

		
	}
	*/
	
	/**
	 * custom option and settings:
	 * callback functions
	 */
	 
	//==== developers section cb ====
	// section callbacks can accept an $args parameter, which is an array.
	// $args have the following keys defined: title, id, callback.
	// the values are defined at the add_settings_section() function.
	//public function wprevpro_section_developers_cb($args)
	//{
		//echos out at top of section
	//}

	
	//==== field cb =====
	// field callbacks can accept an $args parameter, which is an array.
	// $args is defined at the add_settings_field() function.
	// wordpress has magic interaction with the following keys: label_for, class.
	// the "label_for" key value is used for the "for" attribute of the <label>.
	// the "class" key value is used for the "class" attribute of the <tr> containing the field.
	// you can add custom key value pairs to be used inside your callbacks.
	/*
	public function wprevpro_field_fb_code_cb($args)
	{
		// get the value of the setting we've registered with register_setting()
		$options = get_option('wprevpro_options');
		if(!isset($options[$args['label_for']])){
			$options[$args['label_for']] = "";
		}
		// output the field
		?>
		<input id="<?= esc_attr($args['label_for']); ?>" data-custom="<?= esc_attr($args['wpfbr_custom_data']); ?>" type="text" name="wprevpro_options[<?= esc_attr($args['label_for']); ?>]" placeholder="" value="<?php echo $options[$args['label_for']]; ?>">
		
		<p class="description">
			<?= esc_html__('Enter the Access Code that you copied from the link above. Do not share this code.', 'wp-review-slider-pro'); ?>
		</p>
		<?php
	}
		
	public function wprevpro_field_fb_last_name($args)
	{
		// get the value of the setting we've registered with register_setting()
		$options = get_option('wprevpro_options');
		if(!isset($options[$args['label_for']])){
			$options[$args['label_for']]='full';
		}
		// output the field
		?>
				<input type="radio" name="wprevpro_options[<?= esc_attr($args['label_for']); ?>]" value="full" <?php checked('full', $options[$args['label_for']], true); ?>><?php echo  esc_html__('Full Last Name', 'wp-review-slider-pro'); ?>&nbsp;&nbsp;&nbsp;
				<input type="radio" name="wprevpro_options[<?= esc_attr($args['label_for']); ?>]" value="initial" <?php checked('initial', $options[$args['label_for']], true); ?>><?php echo  esc_html__('Initial Only', 'wp-review-slider-pro'); ?>&nbsp;&nbsp;&nbsp;
				<input type="radio" name="wprevpro_options[<?= esc_attr($args['label_for']); ?>]" value="nothing" <?php checked('nothing', $options[$args['label_for']], true); ?>><?php echo  esc_html__('Nothing', 'wp-review-slider-pro'); ?>
				<p class="description">
			<?php echo  esc_html__('Set this to change the way the last name is saved in your database.', 'wp-review-slider-pro'); ?>
		</p>
		<?php
	}	
	
	public function wprevpro_field_fb_recommendation_to_star($args)
	{
		// get the value of the setting we've registered with register_setting()
		$options = get_option('wprevpro_options');
		if(!isset($options[$args['label_for']])){
			$options[$args['label_for']]='';
		}
		// output the field
		?>
		<input type="checkbox" id="<?php echo  esc_attr($args['label_for']); ?>" name="wprevpro_options[<?php echo esc_attr($args['label_for']); ?>]" value="1" <?php checked( $options[$args['label_for']], "1" ); ?>/>
		
		<?php echo  esc_html__(' Save Positive Recommendations as 5 Star and Negative as 2 Star.', 'wp-review-slider-pro'); ?>
		<p class="description">
			<?php echo  esc_html__('This will allow you to display the stars with the review.', 'wp-review-slider-pro'); ?>
		</p>
		<?php
	}
	
	*/
	public function wprev_jsontocommastr($jsonstring){
			if($jsonstring!=''){
				$strarrayjson = json_decode($jsonstring,true);
				$str = implode(",",$strarrayjson);
				$str = str_replace(" ","",$str);
				$str = str_replace("-","",$str);
				return $str;
			}
	}
	
	//for returning list of site Roles, we can call this admin/partials.
	public function wprev_get_role_names() {
		global $wp_roles;
		if ( ! isset( $wp_roles ) )
			$wp_roles = new WP_Roles();
		return $wp_roles->get_names();
	}
	
	public function wprev_canuserseepage($pageurl=''){
		require_once WPREV_PLUGIN_DIR . 'admin/class-wp-review-slider-pro-admin-common.php';
		$plugin_admin_common = new Common_Admin_Functions();
		$test = $plugin_admin_common->wprev_canuserseepage($pageurl);
		return $test;
	}

	

}
