<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    WP_Review_Pro
 * @subpackage WP_Review_Pro/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WP_Review_Pro
 * @subpackage WP_Review_Pro/public
 * @author     Your Name <email@example.com>
 */
class WP_Review_Pro_Public {

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
	 * @param      string    $plugintoken       The name of the plugin.
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
	
	public function __construct( $plugintoken, $version ) {

		$this->_token = $plugintoken;
		$this->version = $version;
		//for testing==============
		//$this->version = time();
		//===================
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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
		
		//check if we are only loading this on certain pages. either an array of post ids or ''
		$loadonthispage = true;
		$pagestoload = get_option( 'wprev_cssposts', '');
		if(is_array($pagestoload) && count($pagestoload)>0){
			$tempcurrentpostid = get_the_ID();
			if (in_array($tempcurrentpostid, $pagestoload)){
				$loadonthispage = true;
			} else {
				$loadonthispage = false;
			}
		}
		
		if($loadonthispage){
			wp_register_style( 'wprevpro_w3', plugin_dir_url( __FILE__ ) . 'css/wprevpro_w3_min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'wprevpro_w3' );
			
			//register slider stylesheet, now added to CCS file above wprevpro_w3.css
			//wp_register_style( 'unslider', plugin_dir_url( __FILE__ ) . 'css/wprs_unslider.css', array(), $this->version, 'all' );
			//wp_register_style( 'unslider-dots', plugin_dir_url( __FILE__ ) . 'css/wprs_unslider-dots.css', array(), $this->version, 'all' );

			wp_enqueue_style( 'unslider' );
			//wp_enqueue_style( 'unslider-dots' );
			
			// extra RTL stylesheet
			if ( is_rtl() )
			{
				wp_register_style( 'wp-review-slider-pro-public_template1_rtl', plugin_dir_url( __FILE__ ) . 'css/wprev-public_template1_rtl.css', array(), $this->version, 'all' );
				wp_enqueue_style( 'wp-review-slider-pro-public_template1_rtl' );			
			}
		}

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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
		 
		//check if we are only loading this on certain pages. either an array of post ids or ''
		$loadonthispage = true;
		$pagestoload = get_option( 'wprev_jscssposts', '');
		if(is_array($pagestoload) && count($pagestoload)>0){
			$tempcurrentpostid = get_the_ID();
			if (in_array($tempcurrentpostid, $pagestoload)){
				$loadonthispage = true;
			} else {
				$loadonthispage = false;
			}
		}
		if($loadonthispage){

			$sliderjstoload = get_option( 'wprev_slidejsload', 'both');

		//add slick js if we have a template using it
			if($sliderjstoload=='both' || $sliderjstoload=='slick'){
				wp_enqueue_script( $this->_token."_slick-min", plugin_dir_url( __FILE__ ) . 'js/wprs-slick.min.js', array( 'jquery' ), $this->version, false );
			}
			
			//wp_enqueue_script( $this->_token."_unslider-min", plugin_dir_url( __FILE__ ) . 'js/wprs-unslider.js', array( 'jquery' ), $this->version, true );
			//for mobile sliding
			//wp_enqueue_script( $this->_token."_event-move", plugin_dir_url( __FILE__ ) . 'js/jquery.event.move.js', array( 'jquery' ), $this->version, true );
			//wp_enqueue_script( $this->_token."_event-swipe", plugin_dir_url( __FILE__ ) . 'js/jquery.event.swipe.js', array( 'jquery' ), $this->version, true );
			
			//combined unslider and event move and swipe js files
			if($sliderjstoload=='both' || $sliderjstoload=='normal'){
				wp_enqueue_script( $this->_token."_unslider_comb-min", plugin_dir_url( __FILE__ ) . 'js/wprs-combined.min.js', array( 'jquery' ), $this->version, false );
			}

			wp_enqueue_script( $this->_token."_plublic-min", plugin_dir_url( __FILE__ ) . 'js/wprev-public.min.js', array( 'jquery' ), $this->version, false );
			//used for ajax
			$page_id = get_queried_object_id();
			wp_localize_script($this->_token."_plublic-min", 'wprevpublicjs_script_vars', 
						array(
						'wpfb_nonce'=> wp_create_nonce('randomnoncestring'),
						'wpfb_ajaxurl' => admin_url( 'admin-ajax.php' ),
						'wprevpluginsurl' => WPREV_PLUGIN_URL,
						'page_id' => $page_id 
						)
					);
					
		}
		
	}
	
	//used to return form html from banner so we can launch it using button on banner
	public function getformhtml($formid){
		$wppl = 'bannerlaunch';
		global $wpdb;
		$formarray = $this->wppro_getform_from_db($formid);
		$table_name_form = $wpdb->prefix . 'wpfb_forms';
		
	 //use the template id to find template in db, echo error if we can't find it or just don't display anything
		//Get the form--------------------------
		$currentform = $wpdb->get_results("SELECT * FROM $table_name_form WHERE id = ".$formid);
		if(isset($currentform[0])){
			$templatestylecode = "";
			if($currentform[0]->form_css!=''){
			$templatestylecode = $templatestylecode . "<style>".sanitize_text_field($currentform[0]->form_css)."</style>";
			}
			//remove line breaks and tabs
			$templatestylecode = str_replace(array("\n", "\t", "\r"), '', $templatestylecode);
			echo $templatestylecode;
			if($currentform[0]->style<1){
				$currentform[0]->style = "1";
			}
			$includefile = plugin_dir_path( __FILE__ ) . '/partials/form_style_'.$currentform[0]->style.'.php';
			
			ob_start();
			require($includefile);
			return ob_get_clean();
		
		}

	}

	
	public function wprevpro_woo_iud_comment_public($comment_ID,$info=''){
				//pass comment_ID over to admin function to see if we can add it.
				$plugin_admin_hooks = new WP_Review_Pro_Admin_Hooks( $this->_token, $this->version );
				$plugin_admin_hooks->wprevpro_woo_iud_comment($comment_ID,'');
	}

	/**
	 * Register the Shortcode for returning averages and totals from avg total table
	 *
	 * @since    11.0.9
	 */
	public function shortcode_wprevpro_totalavg() {
	
				add_shortcode( 'wprevpro_totalavg', array($this,'wprevpro_totalavg_func') );
	}	 
	public function wprevpro_totalavg_func( $atts, $content = null ) {
		//get attributes
		    $a = shortcode_atts( array(
				'pid' => '0',
				'attb' => '',
			), $atts );		//$a['pid'] to get id, $a['attb'] = all will return array of all values.
		
		//get form array from db
		$pageid = $a['pid'];
		$attb = $a['attb'];	//if this is blank then return array, if set then return value
		
		$scol = '';
		if($attb=='total_db'){
			$scol = 'total_indb';
		} else if($attb=='total_source'){
			$scol = 'total';
		} else if($attb=='avg_db'){
			$scol = 'avg_indb';
		} else if($attb=='avg_source'){
			$scol = 'avg';
		} else if($attb=='num1'){
			$scol = 'numr1';
		} else if($attb=='num2'){
			$scol = 'numr2';
		} else if($attb=='num3'){
			$scol = 'numr3';
		} else if($attb=='num4'){
			$scol = 'numr4';
		} else if($attb=='num5'){
			$scol = 'numr5';
		}
		
		global $wpdb;
		$table_name = $wpdb->prefix . 'wpfb_total_averages';
		$totalavgdata = $wpdb->get_var($wpdb->prepare("SELECT ".$scol." FROM $table_name where btp_id = %s ",$pageid));

		ob_start();
		echo round($totalavgdata,1);
		return ob_get_clean();
		
	}

	
	/**
	 * Register the Shortcode for the public-facing side of the site to display the form.
	 *
	 * @since    1.0.0
	 */
	public function shortcode_wprevpro_useform() {
	
				add_shortcode( 'wprevpro_useform', array($this,'wprevpro_useform_func') );
	}	 
	public function wprevpro_useform_func( $atts, $content = null ) {
		//get attributes
		    $a = shortcode_atts( array(
				'tid' => '0',
				'wppl' => 'no',
			), $atts );		//$a['tid'] to get id
		
		//get form array from db
		$formid = $a['tid'];
		$wppl = $a['wppl'];	//if this is set to yes, then we are hiding the form on the page and only using the autopopup feature.
		$formarray = $this->wppro_getform_from_db($formid);
				ob_start();
				include plugin_dir_path( __FILE__ ) . '/partials/wp-review-slider-pro-public-display_form.php';
				return ob_get_clean();
	}
	
	/**
	 * Register the Shortcode for the public-facing side of the site to display the badge.
	 *
	 * @since    1.0.0
	 */
	public function shortcode_wprevpro_usebadge() {
	
				add_shortcode( 'wprevpro_usebadge', array($this,'wprevpro_usebadge_func') );
	}	 
	public function wprevpro_usebadge_func( $atts, $content = null ) {
		//get attributes  [wprevpro_usebadge tid="5" orgin="" pageid="" from=""]
			$a = shortcode_atts( array(
				'tid' => '0',
				'orgin' => '',
				'pageid' => '',
				'from' => '',
			), $atts );		//$a['tid'] to get id
			
			$badgeid = intval($a['tid']); 

				ob_start();
				include plugin_dir_path( __FILE__ ) . '/partials/wp-review-slider-pro-public-display_badge.php';
				return ob_get_clean();
		
	}
			 /** Prints out badge
     *
     * Usage:
     *    <code>do_action( 'wprev_pro_plugin_action_badge', 1 );</code>
     *	
     * @wp-hook wprev_pro_plugin_action
     * @param int $templateid
     * @return void
     */
    public function wprevpro_badge_action_print( $badgeid = 0 )
    {
		$a['tid']=$badgeid;
		if($badgeid>0){
		//ob_start();
		include plugin_dir_path( __FILE__ ) . '/partials/wp-review-slider-pro-public-display_badge.php';
		//return ob_get_clean();
		}
    }
	
	/**
	 * Register the Shortcode for the public-facing side of the site to display the template.
	 *
	 * @since    1.0.0
	 */
	public function shortcode_wprevpro_usetemplate() {
	
				add_shortcode( 'wprevpro_usetemplate', array($this,'wprevpro_usetemplate_func') );
	}	 
	public function wprevpro_usetemplate_func( $atts, $content = null ) {
		//get attributes
		    $a = shortcode_atts( array(
				'tid' => '0',
				'pageid' => '',
				'langcode' => '',
				'tag' => '',
				'strhasone' => '',
				'strhasall' => '',
				'strnot' => '',
			), $atts );		//$a['tid'] to get id
			
			//print_r($a);
			
		$inslideout = 'no';
				ob_start();
				include plugin_dir_path( __FILE__ ) . '/partials/wp-review-slider-pro-public-display.php';
				return ob_get_clean();
	}
	
	/** Prints out reviews
     *
     * Usage:
     *    <code>do_action( 'wprev_pro_plugin_action', 1 );</code>
     *	
     * @wp-hook wprev_pro_plugin_action
     * @param int $templateid
     * @return void
     */
    public function wprevpro_slider_action_print( $templateid = 0 )
    {
		$a['tid']=$templateid;
		if($templateid>0){
		//ob_start();
		include plugin_dir_path( __FILE__ ) . 'partials/wp-review-slider-pro-public-display.php';
		//return ob_get_clean();
		}
    }
	
	/**
	 * Echos out pop or slide html after footer if needed for badge
	 * @access  public
	 * @since   10.8.1
	 * @return  void
	 */
	public function wprp_echobadgepopslide(){
		global $wprevpro_badge_slidepop;	//this is set in the public/partials/wp-review-slider-pro-public-display_badge.php file.
		//filter out any empty values before echoing.
		if(is_array($wprevpro_badge_slidepop)){
			foreach ($wprevpro_badge_slidepop as &$value) {
				if(isset($value) && $value!=''){
					echo $value;
				}
			}
		}

	 //echo "pophtml here";
	 //print_r($wprevpro_badge_slidepop);
	}
	
	/**
	 * Register the Shortcode for the public-facing side of the site to display the float.
	 *
	 * @since    1.0.0
	 */
	public function shortcode_wprevpro_usefloat() {
	
				add_shortcode( 'wprevpro_usefloat', array($this,'wprevpro_usefloat_func') );
	}
	
	/**
	 * Echos out Float html after footer if there is one enabled
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function wprp_echofloatfooter(){
	//ini_set('display_errors',1);  
	//error_reporting(E_ALL);
	//echo "printfloat";
	
	$postid = get_the_ID();
	$posttype = get_post_type( $postid );	//is false if this is not a post type
	$currentcatarray = get_the_category();	//only catids
	//$categories = get_the_terms($post->ID, "my-custom-taxonomy");
	$taxonomies=get_taxonomies('','names');
	$categories =wp_get_post_terms($postid, $taxonomies,  array("fields" => "ids"));

	//echo $postid."<br>";
	//echo $posttype."<br>";
	//print_r($categories);
	//echo "<br><br>";
	
	//only continue if a post type
	if($posttype){
	
		//search db to see if any floats are active on this page.
		global $wpdb;
		$table_name = $wpdb->prefix . 'wpfb_floats';
		$floatarray = $wpdb->get_results("SELECT * FROM $table_name WHERE enabled = '1' ", ARRAY_A);
		//print_r($floatarray);
		
		//loop all enabled array and see if any should be displayed on this post/page/categories
		$arrayLength = count($floatarray);
		for ($i = 0; $i < $arrayLength; $i++) {
			$float_misc_array = json_decode($floatarray[$i]['float_misc'], true);
			$pagefilter = $float_misc_array['pagefilter'];
			$pagefilterlist = json_decode($float_misc_array['pagefilterlist'],true);
			$postfilter = $float_misc_array['postfilter'];
			$postfilterlist = json_decode($float_misc_array['postfilterlist'],true);
			$catfilterlist = json_decode($float_misc_array['catfilterlist'],true);
			
			if($posttype=="page"){
				if($pagefilter=="all"){		
					//show on all pages type
					$atts['tid']=$floatarray[$i]['id'];
					$this->wprevpro_usefloat_func( $atts, $content = null );
				} else if($pagefilter=="allex" && is_array($pagefilterlist)){
					//make sure this page id is inarray of pagefilterlist
					if (!in_array($postid, $pagefilterlist)){
						//show on all pages but this page
						$atts['tid']=$floatarray[$i]['id'];
						$this->wprevpro_usefloat_func( $atts, $content = null );
					}
				} else if($pagefilter=="choose" && is_array($pagefilterlist)){
					//make sure this page id is inarray of pagefilterlist
					if (in_array($postid, $pagefilterlist)){
						//show on this page
						$atts['tid']=$floatarray[$i]['id'];
						$this->wprevpro_usefloat_func( $atts, $content = null );
					}
				}
			} else {		
				//using post filters here or cat filter
				if($postfilter=="all"){
					//show on all pages type
					$atts['tid']=$floatarray[$i]['id'];
					$this->wprevpro_usefloat_func( $atts, $content = null );
				} else if($postfilter=="choose"){	
					if (in_array($postid, $postfilterlist)){
						//show on this page
						$atts['tid']=$floatarray[$i]['id'];
						$this->wprevpro_usefloat_func( $atts, $content = null );
					}
				} else if($postfilter=="cats" && is_array($categories)){	
					$resultintersect = array_intersect($catfilterlist, $categories);
					if (is_array($resultintersect) && count($resultintersect)>0){
						//show on this page
						$atts['tid']=$floatarray[$i]['id'];
						$this->wprevpro_usefloat_func( $atts, $content = null );
					}
					
				}
			}
		}
	}
	}
	
	public function wprevpro_usefloat_func( $atts, $content = null ) {
		//get attributes
		$a = shortcode_atts( array(
			'tid' => '0',
			'bar' => 'something',
		), $atts );		//$a['tid'] to get id
		
		//get values from db
		$floatid = intval($a['tid']); 
		global $wpdb;
		$table_name = $wpdb->prefix . 'wpfb_floats';
		$floatarray = $wpdb->get_row("SELECT * FROM $table_name WHERE id = '$floatid' ", ARRAY_A);
		//print_r($floatarray);
		$whattofloatid = intval($floatarray['content_id']);
		$whattofloattype = $floatarray['float_type'];
		$float_misc_array = json_decode($floatarray['float_misc'], true);
		$customcss = sanitize_text_field($floatarray['float_css']);
		
		//create float styles
		$floatstylehtml = '';
		$floatlocation = $float_misc_array['floatlocation'];
		$bgcolor1 = $float_misc_array['bgcolor1'];
		$bordercolor1 = $float_misc_array['bordercolor1'];
		$floatwidth = $float_misc_array['width'];
		$floatmarginarray = [$float_misc_array['margin-top'],$float_misc_array['margin-right'],$float_misc_array['margin-bottom'],$float_misc_array['margin-left']];
		$floatpaddingarray = [$float_misc_array['padding-top'],$float_misc_array['padding-right'],$float_misc_array['padding-bottom'],$float_misc_array['padding-left']];
		
		if($floatwidth>0){
			$middleoffset = $floatwidth/2;
		} else {
			$middleoffset ='';
		}
			//location of float
			$lochtml = '';
			if($floatlocation=="btmrt"){
				$lochtml = 'bottom:10px;right:10px;top:unset;left:unset;';
			} else if($floatlocation=="btmmd"){
				$lochtml = 'bottom: 10px;right: unset;top:unset;left:50%;margin-left:-'.$middleoffset.'px;';
			} else if($floatlocation=="btmlft"){
				$lochtml = 'bottom: 10px;left: 10px;top:unset;right:unset;';
			} else if($floatlocation=="toplft"){
				$lochtml = 'top: 10px;left: 10px;bottom:unset;right:unset;';
			} else if($floatlocation=="topmd"){
				$lochtml = 'top: 10px;right: unset;bottom:unset;left:50%;margin-left:-'.$middleoffset.'px;';
			} else if($floatlocation=="toprt"){
				$lochtml = 'top: 10px;right: 10px;bottom:unset;left:unset;';
			}
			//set colors
			if($bgcolor1!=''){
				$lochtml = $lochtml . 'background: '.$bgcolor1.';';
			}
			if($bordercolor1!=''){
				$lochtml = $lochtml . 'border: 1px solid '.$bordercolor1.';';
			}
			//update width  width: 350px;
			if($floatwidth>0){
				$lochtml = $lochtml . 'width: '.$floatwidth.'px;';
			}
			//update margins
			$arrayLength = count($floatmarginarray);
			$tempstyletext='';
			for ($i = 0; $i < $arrayLength; $i++) {
				if($floatmarginarray[$i]!=''){
					if($i==0){
						$tempstyletext = $tempstyletext . 'margin-top:' . $floatmarginarray[$i] . 'px; ';
					} else if($i==1){
						$tempstyletext = $tempstyletext . 'margin-right:' . $floatmarginarray[$i] . 'px; ';
					} else if($i==2){
						$tempstyletext = $tempstyletext . 'margin-bottom:' . $floatmarginarray[$i] . 'px; ';
					} else if($i==3){
						$tempstyletext = $tempstyletext . 'margin-left:' . $floatmarginarray[$i] . 'px; ';
					}
				}
			}
			//update padding
			$arrayLength = count($floatpaddingarray);
			//fix for padding top messign with close x
			$closexstylefix = '';
			for ($i = 0; $i < $arrayLength; $i++) {
				if($floatpaddingarray[$i]!=''){
					if($i==0){
						$tempstyletext = $tempstyletext . 'padding-top:' . $floatpaddingarray[$i] . 'px; ';
						$closexstylefix = '#wprev_pro_float_'.$floatid.' .wprev_pro_float_outerdiv-close {margin-top: ' . $floatpaddingarray[$i] . 'px;}';
					} else if($i==1){
						$tempstyletext = $tempstyletext . 'padding-right:' . $floatpaddingarray[$i] . 'px; ';
					} else if($i==2){
						$tempstyletext = $tempstyletext . 'padding-bottom:' . $floatpaddingarray[$i] . 'px; ';
					} else if($i==3){
						$tempstyletext = $tempstyletext . 'padding-left:' . $floatpaddingarray[$i] . 'px; ';
					}
				}
			}
			//if on click setting is url add pointer style
			$onclickaction = $float_misc_array['onclickaction'];
			$ochtml='';
			$ochtmlurl ='';
			$ochtmlurltarget='';
			if($onclickaction=='url' || $onclickaction=="slideout" || $onclickaction=="popup"){
				$tempstyletext = $tempstyletext . ' cursor: pointer;';
				$ochtml = "data-onc='".$onclickaction."'";
				$ochtmlurl = "data-oncurl='".$float_misc_array['onclickurl']."'";
				$ochtmlurltarget = "data-oncurltarget='".$float_misc_array['onclickurl_target']."'";
			}
			
			$lochtml = $lochtml . $tempstyletext;
			$locstyle = '#wprev_pro_float_'.$floatid.' {'.$lochtml.'}';
			
			$floatstylehtml = '<style>'.$locstyle.$customcss.$closexstylefix.'</style>';

		//create slideout styles-----------
		$slideoutstylehtml = '';
		if($onclickaction=="slideout"){
			$slidelocation = $float_misc_array['slidelocation'];
			$slideheight = $float_misc_array['slheight'];
			if($slideheight==""){
				$slideheight='auto;';
			} else {
				$slideheight=$slideheight.'px;';
			}
			$slidewidth = $float_misc_array['slwidth'];
			if($slidewidth==""){$slidewidth=350;}
			$slidelochtml='';
			if($slidelocation=="right"){
				$slidelochtml = $slidelochtml . 'bottom: 0px;right: 0px;height: 100%;width: '.$slidewidth.'px;';
				$slidelochtml = $slidelochtml . 'border-right-style:none !important; border-bottom-style:none !important; border-top-style:none !important;';
			} else if($slidelocation=="left"){
				$slidelochtml = $slidelochtml . 'bottom: 0px;left: 0px;height: 100%;width: '.$slidewidth.'px;';
				$slidelochtml = $slidelochtml . 'border-left-style:none !important; border-bottom-style:none !important; border-top-style:none !important;';
			} else if($slidelocation=="top"){
				$slidelochtml = $slidelochtml . 'top: 0px;bottom:unset;width: 100%;height: '.$slideheight;
				$slidelochtml = $slidelochtml . 'border-left-style:none !important; border-right-style:none !important; border-top-style:none !important;';
			} else if($slidelocation=="bottom"){
				$slidelochtml = $slidelochtml . 'top:unset;bottom: 0px;width: 100%;height: '.$slideheight;
				$slidelochtml = $slidelochtml . 'border-left-style:none !important; border-right-style:none !important; border-bottom-style:none !important;';
			}
			
			//border size
			$slbordersize = 1;
			if(isset($float_misc_array['slborderwidth'])){
				$slbordersize = $float_misc_array['slborderwidth'];
			}
			
			//background color
			$slbgcolor1 = $float_misc_array['slbgcolor1'];
			if($slbgcolor1!=''){
				$slidelochtml = $slidelochtml . 'background: '.$slbgcolor1.';';
			}
			$slbordercolor1 = $float_misc_array['slbordercolor1'];
			if($slbordercolor1!=''){
				$slidelochtml = $slidelochtml . 'border: '.$slbordersize.'px solid '.$slbordercolor1.';';
			}
			//slide padding
			$slidepaddingarray = [$float_misc_array['slpadding-top'],$float_misc_array['slpadding-right'],$float_misc_array['slpadding-bottom'],$float_misc_array['slpadding-left']];
			$tempstyletext='';
			$arrayLength = count($slidepaddingarray);
			for ($i = 0; $i < $arrayLength; $i++) {
				if($slidepaddingarray[$i]!=''){
					if($i==0){
						$tempstyletext = $tempstyletext . 'padding-top:' . $slidepaddingarray[$i] . 'px; ';
					} else if($i==1){
						$tempstyletext = $tempstyletext . 'padding-right:' . $slidepaddingarray[$i] . 'px; ';
					} else if($i==2){
						$tempstyletext = $tempstyletext . 'padding-bottom:' . $slidepaddingarray[$i] . 'px; ';
					} else if($i==3){
						$tempstyletext = $tempstyletext . 'padding-left:' . $slidepaddingarray[$i] . 'px; ';
					}
				}
			}
			$bodystyle = '#wprevpro_badge_slide_'.$floatid.' .wprevpro_slideout_container_body {'.$tempstyletext.'}';
			$locstyle = '#wprevpro_badge_slide_'.$floatid.' {'.$slidelochtml.'}';
			$slideoutstylehtml = '<style>'.$locstyle.$bodystyle.'</style>';
			
			//add the header and footer html
			$headerhtml = stripslashes($float_misc_array['slideheader']);
			$footerhtml = stripslashes($float_misc_array['slidefooter']);
			
		} else if($onclickaction=="popup"){
			//background color
			$slidelochtml='';
			$slbgcolor1 = $float_misc_array['slbgcolor1'];
			if($slbgcolor1!=''){
				$slidelochtml = $slidelochtml . 'background: '.$slbgcolor1.';';
			}
			//border size
			$slbordersize = 1;
			if(isset($float_misc_array['slborderwidth'])){
				$slbordersize = $float_misc_array['slborderwidth'];
			}
			$slbordercolor1 = $float_misc_array['slbordercolor1'];
			if($slbordercolor1!=''){
				$slidelochtml = $slidelochtml . 'border: '.$slbordersize.'px solid '.$slbordercolor1.';';
			}
			$locstyle = '#wprevpro_badge_pop_'.$floatid.' .wprevpro_popup_container_inner {'.$slidelochtml.'}';
			$slideoutstylehtml = '<style>'.$locstyle.'</style>';
			
			//add the header and footer html
			$headerhtml = stripslashes($float_misc_array['slideheader']);
			$footerhtml = stripslashes($float_misc_array['slidefooter']);
			
		} else {
			$headerhtml ='';
			$footerhtml = '';
			$slideoutstylehtml ='';
			$slidehtmldata = '';
		}
		//------------------------

		//adding for animation so we can modify in jquery
		$animatedir = '';
		$animatedelay = '';
		if(isset($float_misc_array['animate_dir'])){
			$animatedir = $float_misc_array['animate_dir'];
		}
		if(isset($float_misc_array['animate_delay'])){
			$animatedelay = $float_misc_array['animate_delay'];
		}
		
		//call to get float html
		$floathtml = $this->wppro_getfloat_html($floatid,$whattofloatid,$whattofloattype,$animatedelay);
		
		//get slide html if needed
		if($onclickaction=="slideout" || $onclickaction=="popup" ){
			$revtemplateid = $float_misc_array['sliderevtemplate'];
			$slidehtmldata = $this->wppro_getslideout_html($floatid,$revtemplateid);
		}
		
		//add hide on mobile CSS 
		$hideonmobilehtml = '';
		//print_r($float_misc_array);
		if($float_misc_array['hideonmobile']=='yes'){
			$hideonmobilehtml = '<style>@media screen and (max-width: 768px) {#wprevpro_float_outer_'.$floatid.' {display: none;visibility: hidden;}}</style>';
		} else if($float_misc_array['hideonmobile']=='desktop'){
			$hideonmobilehtml = '<style>@media screen and (min-width: 768px) {#wprevpro_float_outer_'.$floatid.' {display: none;visibility: hidden;}}</style>';
		}
		
		//for hiding after first visit
		$tempfirstvisit = 'no';
		if(isset($float_misc_array['firstvisit']) && $float_misc_array['firstvisit']=='yes'){
			$tempfirstvisit = 'yes';
		}
		

		
		//adding for autoclose so we can modify in jquery
		$autoclose = '';
		$autoclose_delay = '';
		if(isset($float_misc_array['autoclose'])){
			$autoclose = $float_misc_array['autoclose'];
		}
		if(isset($float_misc_array['autoclose_delay'])){
			$autoclose_delay = $float_misc_array['autoclose_delay'];
		}
		
		$tempslidepophtml = '';
		if($onclickaction=="slideout"){
			$tempslidepophtml = '<span class="wprevpro_slideout_container_style">'.$slideoutstylehtml.'</span>
						<div id="wprevpro_badge_slide_'.$floatid.'" class="wprevpro_slideout_container" style="visibility:hidden;">
						    <span class="wprevslideout_close">×</span>
							<div class="wprevpro_slideout_container_header">'.$headerhtml.'</div>
							<div class="wprevpro_slideout_container_body">'.$slidehtmldata.'</div>
							<div class="wprevpro_slideout_container_footer">'.$footerhtml.'</div>
						</div>';
		} else if($onclickaction=="popup"){
			$tempslidepophtml = '<span class="wprevpro_popup_container_style">'.$slideoutstylehtml.'</span>
						<div id="wprevpro_badge_pop_'.$floatid.'" class="wprevmodal_modal wprevpro_popup_container" style="visibility:hidden;">
							<div class="wprevmodal_modal-content wprevpro_popup_container_inner ">
								<span class="wprevmodal_close">×</span>
								<div class="wprevpro_popup_container_header">'.$headerhtml.'</div>
								<div class="wprevpro_popup_container_body">'.$slidehtmldata.'</div>
								<div class="wprevpro_popup_container_footer">'.$footerhtml.'</div>
							</div>
						</div>';
		}

		$divhtml = '<div class="wprevpro_float_outer" style="display:none;" id="wprevpro_float_outer_'.$floatid.'">
						<span class="wprevpro_badge_container_style">'.$hideonmobilehtml.$floatstylehtml.'</span>
						<div data-badgeid="'.$floatid.'" class="wprevpro_badge_container" '.$ochtml.' '.$ochtmlurl.' '.$ochtmlurltarget.' data-firstvisit="'.$tempfirstvisit.'" data-animatedir="'.$animatedir.'" data-animatedelay="'.$animatedelay.'" data-autoclose="'.$autoclose.'" data-autoclosedelay="'.$autoclose_delay.'">'.$floathtml.'</div>
						'.$tempslidepophtml.'
					</div>';
		echo $divhtml;
			
	}



	/**
	 * Ajax, retrieves forms from table, called from javascript file wprevpro_forms_page.js
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function wppro_getform_ajax(){
	//ini_set('display_errors',1);  
	//error_reporting(E_ALL);
		check_ajax_referer('randomnoncestring', 'wpfb_nonce');
		$formid = sanitize_text_field($_POST['fid']);

		$formarray = $this->wppro_getform_from_db($formid);
		
		echo json_encode($formarray);

		die();
	}
	
	//used to find icon image html for form on front end for social links
	public function wppro_returniconhtml($linkurl,$displaytype,$lname){
		$imaghtml='';
		$allrestypearray = unserialize(WPREV_TYPE_ARRAY_RF);
		$alltypearray = unserialize(WPREV_TYPE_ARRAY);
		
		$typemergearray =array_merge($allrestypearray,$alltypearray);
		array_push($typemergearray,"g.page");
		
		//print_r($typemergearray);
		
		$temptype = '';
		
		if($displaytype==''){
				$imaghtml = esc_attr($lname);
		} else {
			foreach ($typemergearray as $value) {
				//echo "$value <br>";
				$temptype = strtolower($value);
				//see if this type is in the url, if so then quit loop and return __('Leave a review on ', 'wp-review-slider-pro');
				//if (strpos($linkurl, $temptype) !== false || $temptype==strtolower($lname)) {
				if ($temptype==strtolower($lname)) {
					//found it
					//echo '<br>true:'.$temptype.':'.$linkurl;
					if($displaytype=='sicon'){
						$imaghtml = '<img src="'.WPREV_PLUGIN_URL.'/public/partials/imgs/'.$temptype.'_small_icon.png" alt="'.$temptype.' Logo" title="'.esc_html__('Leave a review on ', 'wp-review-slider-pro').''.$value.'" class="wprevpro_form_site_logo">';
					  } else if($displaytype=='licon'){
						$imaghtml = '<img src="'.WPREV_PLUGIN_URL.'/public/partials/imgs/branding-'.$temptype.'-badge_50.png" alt="'.$temptype.' Logo" title="'.esc_html__('Leave a review on ', 'wp-review-slider-pro').''.$value.'" class="wprevpro_form_site_logo">';
					  }
					break;
				}
			}
			
			
		}
		
		return $imaghtml;
	
	}

	
	public function wppro_getform_from_db($formid){
	//ini_set('display_errors',1);  
	//error_reporting(E_ALL);

		if($formid=='new'){
			//return default form values 
			
			//=============
			//next rev add ability to add another custom field starornot_icon
			//==========================
			$adminemail = get_option( 'admin_email' );
			$formarray = array(
							"title"=>"",
							"style"=>"",
							"created_time_stamp"=>"",
							"form_css"=>"",
							"form_misc"=>"",
							"notifyemail"=>"$adminemail",
							"form_fields"=>'[{"required":"","label":"Review Rating","show_label":"on","placeholder":"","before":"","after":"How do you rate us?","default_form_value":"","default_display_value":"","input_type":"review_rating","name":"review_rating","starornot":"","star_icon":"","maxrating":"5","afterclick":""},{"required":"","label":"Please review us on...","show_label":"on","placeholder":"","before":"","after":"Or submit the form below.","default_form_value":"","default_display_value":"","input_type":"social_links","name":"social_links","lname1":"","lurl1":"","lname2":"","lurl2":"","lname3":"","lurl3":"","lname4":"","lurl4":"","lname5":"","lurl5":"","displaytype":"","showval":"","hiderest":""},{"required":"","label":"Subject","show_label":"on","placeholder":"","before":"","after":"A subject line for your testimonial.","default_form_value":"","default_display_value":"","input_type":"text","name":"review_title"},{"required":"on","label":"Testimonial","show_label":"on","placeholder":"","before":"","after":"What do you think about us?","default_form_value":"","default_display_value":"","input_type":"textarea","name":"review_text"},{"required":"on","label":"Full Name","show_label":"on","placeholder":"","before":"","after":"What is your full name?","default_form_value":"","default_display_value":"","input_type":"text","name":"reviewer_name"},{"required":"on","label":"Email","show_label":"on","placeholder":"","before":"","after":"What is your email?","default_form_value":"","default_display_value":"","input_type":"email","name":"reviewer_email"},{"required":"","label":"Company Name","show_label":"on","placeholder":"","before":"","after":"What is your company name?","default_form_value":"","default_display_value":"","input_type":"text","name":"company_name"},{"required":"","label":"Company Title","show_label":"on","placeholder":"","before":"","after":"What is your title at the company?","default_form_value":"","default_display_value":"","input_type":"text","name":"company_title"},{"required":"","label":"Company Website","show_label":"on","placeholder":"","before":"","after":"What is your company website?","default_form_value":"","default_display_value":"","input_type":"url","name":"company_website"},{"required":"","label":"Photo","show_label":"on","placeholder":"","before":"","after":"Would you like to include a photo of yourself?","default_form_value":"","default_display_value":"","input_type":"review_avatar","name":"review_avatar"},{"required":"","label":"Video","show_label":"on","placeholder":"","before":"","after":"Upload a Video","default_form_value":"","default_display_value":"","input_type":"review_video","name":"review_video"},{"required":"","label":"Consent","show_label":"on","placeholder":"","before":"","after":"I consent to have the information submitted being stored on your server and displayed on your site as per your privacy policy.","default_form_value":"","default_display_value":"","input_type":"review_consent","name":"review_consent"}]',
							);
		} else {
			//get values from db
			$formid = intval($formid); 
			//"	SELECT * FROM $table_name WHERE id = %d "
			global $wpdb;
			$table_name = $wpdb->prefix . 'wpfb_forms';
			$formarray = $wpdb->get_row("SELECT * FROM $table_name WHERE id = '$formid' ", ARRAY_A);
			//$formarray = $wpdb->get_row("SELECT * FROM $table_name WHERE id = '$formid' ");
		}
		//print_r($formarray);
		
		return $formarray;

	}
	
	
	/**
	 * Ajax, simply checks if thie unique id is in db already for form submission, called from javascript file wprevpro_forms_page.js
	 * @access  public
	 * @since   11.7.7
	 * @return  void
	 */
	public function wprp_check_unbrid_ajax(){
	//ini_set('display_errors',1);  
	//error_reporting(E_ALL);
		check_ajax_referer('randomnoncestring', 'wpfb_nonce');
		
		if(isset($_POST['unbrid'])){
			$unbrid = sanitize_text_field($_POST['unbrid']);
			
			//echo "unbrid php:".$unbrid;
			
			global $wpdb;
			$table_name = $wpdb->prefix . 'wpfb_reviews';
			$unbridcheck = $wpdb->get_row( "SELECT id FROM $table_name WHERE meta_data LIKE '%$unbrid%'", ARRAY_A );
			
			if(is_array($unbridcheck)){	
				echo "found";
			} else {
				echo "notfound";
			}

		} 
		


		die();
	}	
	
	/**
	 * Ajax, saves submitted review to table, called from javascript file wprevpro_forms_page.js
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function wprp_savereview_ajax(){
	//ini_set('display_errors',1);  
	//error_reporting(E_ALL);
		check_ajax_referer('randomnoncestring', 'wpfb_nonce');
		
		if(isset($_POST['data'])){
			//have to do this for serialize method=========
			$postvariablearray = $_POST['data'];
			$params = array();
			parse_str($postvariablearray, $params);
			//=======================
		} else {
			$params = $_POST;
		}
		
		$formsave = $this->wprev_submission_form_action_save($params,$_FILES,true);
		//$formsave['test'] = "ajax working";
		
		echo json_encode($formsave);

		die();
	}	
	
	/**
	 * Checks and submits review submission form on front end, used when doing post submit via page reload
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function wprev_submission_form_action(){
		 $this->wprev_submission_form_action_save($_POST,$_FILES,false);
	}
	
	public function wprev_submission_form_action_save($postvariablearray,$uploadfilesarray,$isajax=false){
		
	//ini_set('display_errors',1);  
	//error_reporting(E_ALL);
	
			
			//var_dump($_REQUEST);
			$mailsent = '';
			//$error_msg ='Oops! Something went wrong, please try again or contact us...<br>';	//used to return message to screen.
			$error_msg = __('Oops! Something went wrong, please try again or contact us...<br>', 'wp-review-slider-pro');
			$success_msg ='';	//used to return message to screen.
			$hasError = false;
			$returnpage = $postvariablearray['_wp_http_referer'];
			$formid = (int)sanitize_text_field($postvariablearray['wprevpro_fid']);	//used to get form settings needed below
			
			if($formid<1){
				exit();
			}
			
			$formarray = $this->wppro_getform_from_db($formid);
			if(!is_array($formarray)){
				exit();
			}
			
			if(isset($formarray['form_misc'])){
			$form_misc_array = json_decode($formarray['form_misc'], true);
			}
			if(isset($formarray['form_fields'])){
			$formfieldsarray= json_decode($formarray['form_fields'], true);
			}
			
			if(!is_array($formfieldsarray)){
				exit();
			}
			
			$formtitle = "";
			if(isset($formarray['title'])){
				$formtitle = $formarray['title'];
			}

			//echo "<br><br>";
			//print_r($postvariablearray);
			//die();
			//echo "<br><br>";
			//print_r($form_misc_array);
			//echo "<br><br>";
			//print_r($formarray);
			
			//first check is honeypot of name variable, do not submit if filled in
			if($postvariablearray['name']!=''){
				//failed honeypot check die with error
				$hasError = true;
				//$error_msg = $error_msg ."Failed honeypot.<br>";
				$error_msg = $error_msg .__('Failed honeypot.<br>', 'wp-review-slider-pro');
			}
			$success_msg = $success_msg ."Passed honeypot.<br>";
		
			//first check wp_nonce_field, only checking if regular post, already checked ajax post
			if ( isset( $postvariablearray['submitted'] ) && isset( $postvariablearray['post_nonce_field'] ) && wp_verify_nonce( $postvariablearray['post_nonce_field'], 'post_nonce' ) ) {
					$success_msg = $success_msg ."Passed nonce.<br>";
			} else {
					//no once doesn't match
					$hasError = true;
					//$error_msg = $error_msg ."Failed nonce.<br>";
					$error_msg = $error_msg .__('Failed nonce.<br>', 'wp-review-slider-pro');
			}

			
			//second check is to recaptcha if using it.
			if($form_misc_array['captchaon']=='v2'){
				if($form_misc_array['captchasecrete']!=''){
					$rscecretekey = trim($form_misc_array['captchasecrete']);		//need to get this from the form if it is set
					$response = wp_remote_get( add_query_arg( array(
						'secret'   => $rscecretekey,
						'response' => isset( $postvariablearray['g-recaptcha-response'] ) ? $postvariablearray['g-recaptcha-response'] : '',
						'remoteip' => isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']
					), 'https://www.google.com/recaptcha/api/siteverify' ) );
					
					if ( is_wp_error( $response ) || empty( $response['body'] ) || ! ( $json = json_decode( $response['body'] ) ) || ! $json->success ) {
						//return new WP_Error( 'wprev-validation-error', '"Are you human" check failed. Please try again.' );
						$hasError = true;
						//$error_msg = $error_msg ."Failed recaptcha.<br>";
						$error_msg = $error_msg .__('Failed recaptcha.<br>', 'wp-review-slider-pro');
						//echo "<br><br>";
						//print_r($json);
					} else {
						$success_msg = $success_msg ."<br>Passed recaptcha.";

					}
				} else {
					$hasError = true;
						//$error_msg = $error_msg ."reCAPTHA not setup correctly for this form. Make sure you input your secrete key.<br>";
						$error_msg = $error_msg .__('reCAPTHA not setup correctly for this form. Make sure you input your secrete key.<br>', 'wp-review-slider-pro');
				}
			}
			
			//print_r($formfieldsarray);
			//print_r($postvariablearray);
			//last check get required fields and make sure they have a values
			//loop through the form to find out what is required if it is and not input then error
			for ($x = 0; $x < count($formfieldsarray); $x++) {
				if($formfieldsarray[$x]['required']=='on'){
					$tempfieldname = 'wprevpro_'.$formfieldsarray[$x]['name'];
					//this field required. did they input anything
					if($postvariablearray[$tempfieldname]==''){
						$hasError = true;
						//$error_msg = $error_msg ."All required fields are not filled out. Nothing input for ".$formfieldsarray[$x]['name'].".<br>";
						$error_msg = $error_msg .__('All required fields are not filled out. Nothing input for ', 'wp-review-slider-pro').$formfieldsarray[$x]['name'].".<br>";
					}
					//fix for review rating field
					if($formfieldsarray[$x]['name']=='review_rating' && $postvariablearray[$tempfieldname]<1){
						$hasError = true;
						//$error_msg = $error_msg ."All required fields are not filled out. Please enter a Review Rating.<br>";
						$error_msg = $error_msg .__('All required fields are not filled out. Please enter a Review Rating.<br>', 'wp-review-slider-pro');
					}
				}
			}
			
			//check if email and url are valid if they are set
			if(isset($postvariablearray['wprevpro_reviewer_email']) && $postvariablearray['wprevpro_reviewer_email']!=''){
				if ( !is_email( $postvariablearray['wprevpro_reviewer_email'] ) ) {
					$hasError = true;
					//$error_msg = $error_msg ."Please enter a valid email address.<br>";
					$error_msg = $error_msg .__('Please enter a valid email address.<br>', 'wp-review-slider-pro');
				}
			}
			if(isset($postvariablearray['wprevpro_company_website']) && $postvariablearray['wprevpro_company_website']!=''){
				if(!$this->validate_url($postvariablearray['wprevpro_company_website'])) {
					$hasError = true;
					//$error_msg = $error_msg ."Please enter a valid website URL.<br>";
					$error_msg = $error_msg .__('Please enter a valid website URL.<br>', 'wp-review-slider-pro');
				}
			}
			
			//if any of these checks fail send back to page with values filled in and show error message.
			
			//made it through checks, now sanitize and validate before inserting in to database
			if($hasError==false){
				$rating = intval($postvariablearray['wprevpro_review_rating']);
				if ( strlen( $rating ) > 1 ) {
				  $rating = substr( $rating, 0, 1 );
				}
				$title = '';
				$text = '';
				$name = '';
				$email = '';
				$company_name = '';
				$company_title = '';
				$company_website = '';
				
				if(isset($postvariablearray['wprevpro_review_title'])){
					$title = sanitize_text_field($postvariablearray['wprevpro_review_title']);
				}
				if(isset($postvariablearray['wprevpro_review_text'])){
					$text = sanitize_textarea_field($postvariablearray['wprevpro_review_text']);
				}
				if(isset($postvariablearray['wprevpro_reviewer_name'])){
					$name = sanitize_text_field($postvariablearray['wprevpro_reviewer_name']);
				}
				if(isset($postvariablearray['wprevpro_reviewer_email'])){
					$email = sanitize_email($postvariablearray['wprevpro_reviewer_email']);
				}
				if(isset($postvariablearray['wprevpro_company_name'])){
					$company_name = sanitize_text_field($postvariablearray['wprevpro_company_name']);
				}
				if(isset($postvariablearray['wprevpro_company_title'])){
					$company_title = sanitize_text_field($postvariablearray['wprevpro_company_title']);
				}
				if(isset($postvariablearray['wprevpro_company_website'])){
					$company_website = sanitize_text_field($postvariablearray['wprevpro_company_website']);
				}
				
				
				
				//------set default submission values, if these are not set
				//---need to loop to see what is set
				$defaultsubmitvalues = array();
				for ($x = 0; $x < count($formfieldsarray); $x++) {
					if($formfieldsarray[$x]['default_display_value']!=''){
						//this field has a values
						$tempfname = $formfieldsarray[$x]['name'];
						$defaultsubmitvalues[$tempfname] = $formfieldsarray[$x]['default_display_value'];
					}
				}
				if($title=="" && isset($defaultsubmitvalues['review_title'])){
					$title=$defaultsubmitvalues['review_title'];
				}
				if($text=="" && isset($defaultsubmitvalues['review_text'])){
					$text=$defaultsubmitvalues['review_text'];
				}
				if($name=="" && isset($defaultsubmitvalues['reviewer_name'])){
					$name=$defaultsubmitvalues['reviewer_name'];
				}
				if($email=="" && isset($defaultsubmitvalues['reviewer_email'])){
					$email=$defaultsubmitvalues['reviewer_email'];
				}
				if($company_name=="" && isset($defaultsubmitvalues['company_name'])){
					$company_name=$defaultsubmitvalues['company_name'];
				}
				if($company_website=="" && isset($defaultsubmitvalues['company_website'])){
					$company_website=$defaultsubmitvalues['company_website'];
				}
				//=--------------------------
				
				if (extension_loaded('mbstring')) {
					$review_length = mb_substr_count($text, ' ');
					$review_length_char = mb_strlen($text);
				} else {
					$review_length = substr_count($text, ' ');
					$review_length_char = strlen($text);
				}
				
				$r_editrtype = 'Submitted';
				$from = '';	//used for displaying logo, custom means not fb, or google etc.
				$from_logo = '';	//for holding custom logo for company
				//set defaults if they are set on the form
				if(isset($form_misc_array['iconimage']) && $form_misc_array['iconimage']!=''){
					$from_logo = esc_url($form_misc_array['iconimage']);
					$from = 'custom';
				}
				if(isset($form_misc_array['iconlink']) && $form_misc_array['iconlink']!=''){
					$from_url = esc_url($form_misc_array['iconlink']);
				} else {
					$from_url = esc_url(sanitize_text_field($postvariablearray['_wp_http_referer']));
				}
				//setup tags if they are set
				$temptags = Array();
				if(isset($form_misc_array['tags']) && $form_misc_array['tags']!=''){
					$temptagsadmin = $form_misc_array['tags'];
					$temptags = explode(",", $temptagsadmin);
				}
				//also get tags from url and add to these.
				if(isset($postvariablearray['wprev_urltag']) && $postvariablearray['wprev_urltag']!=''){
					$urltags = sanitize_text_field($postvariablearray['wprev_urltag']);
					$urltagsarray = explode(",", $urltags);
					$temptags = array_merge($temptags, $urltagsarray);
				}
				
				//$timezoneoffset= get_option('gmt_offset'); 	//2 or -2 in hours
				$time = current_time( 'timestamp' );
				$newdateformat = date('Y-m-d H:i:s',$time);
				
				//save categories and postid with this review.
				$cats = sanitize_text_field($postvariablearray['wprev_catids']);	//encoded this on form page
				$catidjson ="";
				//we need to add dashes to $cats
				if($cats!=""){
					$catsarray = json_decode($cats,true);
					$arrlength = count($catsarray);
					for($x = 0; $x < $arrlength; $x++) {
						$catidarraywithdash[]="-".$catsarray[$x]."-";
					}
				}
				if(isset($catidarraywithdash)){
					$catidjson = json_encode($catidarraywithdash);
				}
				
				$posts[] = "-".intval($postvariablearray['wprev_postid'])."-";	//encoding here so we can add more later
				
				$rconsent ="";
				if(isset($postvariablearray['wprevpro_review_consent'])){
				$rconsent = sanitize_text_field($postvariablearray['wprevpro_review_consent']);
				}
				
				//save pageid in db, so we can filter by page on template page.
				$pageid = intval($postvariablearray['wprev_postid']);
				if($pageid>0){
					$post = get_post( $pageid ); 
					$pagename = $post->post_title;
				} else {
					$pageid = 'submitted';
					$pagename = 'submitted';
				}
				
				//see if this should be autoapproved
				$hidereview = 'yes';
				if($form_misc_array['autoapprove']=='yes'){
					$hidereview = '';
				}
				//for saving ip if set
				$metadata ='';
				$metadataarray = Array();
				$commentauthorIP = '';
				if(isset($postvariablearray['wprev_ipFormInput']) && $postvariablearray['wprev_ipFormInput']!='yes' && $postvariablearray['wprev_ipFormInput']!=''){
					$metadataarray['ip'] = $postvariablearray['wprev_ipFormInput'];
					$commentauthorIP =$metadataarray['ip'];
					
				}
				if(isset($postvariablearray['wprev_unique_id']) && $postvariablearray['wprev_unique_id']!=''){
					$metadataarray['unbrid'] = $postvariablearray['wprev_unique_id'];
					
				}
				if(count($metadataarray)>0){
					$metadata = json_encode($metadataarray);
				}
				
				//checking for custom fields on form and saving them to custom_data
				$customdata ='';
				$customdatastars ='';
				$customdataarray=Array();
				$customstararray=Array();
				$mediaurlsarrayjson='';
				$mediathumburlsarrayjson='';
				$woocreateforprodid = '';
				for ($x = 1; $x < 100; $x++) {
					if(isset($postvariablearray['wprevpro_custom_text_'.$x])){
						$tempidtext = 'custom_text_'.$x;
						if(isset($postvariablearray['wprevpro_custom_text_'.$x.'_label']) && $postvariablearray['wprevpro_custom_text_'.$x.'_label'] !=''){
							//see if the hidden labe is set and use it as the name if so
							$tempidtext = sanitize_text_field($postvariablearray['wprevpro_custom_text_'.$x.'_label']);	
						}
						$customdataarray[$tempidtext] = sanitize_text_field($postvariablearray['wprevpro_custom_text_'.$x]);	
					}
					//wprevpro_custom_select_13
					if(isset($postvariablearray['wprevpro_custom_select_'.$x])){
						$tempidtext = 'custom_select_'.$x;
						if(isset($postvariablearray['wprevpro_custom_select_'.$x.'_label']) && $postvariablearray['wprevpro_custom_select_'.$x.'_label'] !=''){
							//see if the hidden labe is set and use it as the name if so
							$tempidtext = sanitize_text_field($postvariablearray['wprevpro_custom_select_'.$x.'_label']);	
						}
						$customdataarray[$tempidtext] = sanitize_text_field($postvariablearray['wprevpro_custom_select_'.$x]);	
					}
					//wprevpro_custom_textarea_17
					if(isset($postvariablearray['wprevpro_custom_textarea_'.$x])){
						$tempidtext = 'custom_select_'.$x;
						if(isset($postvariablearray['wprevpro_custom_textarea_'.$x.'_label']) && $postvariablearray['wprevpro_custom_textarea_'.$x.'_label'] !=''){
							//see if the hidden labe is set and use it as the name if so
							$tempidtext = sanitize_textarea_field($postvariablearray['wprevpro_custom_textarea_'.$x.'_label']);	
						}
						$customdataarray[$tempidtext] = sanitize_textarea_field($postvariablearray['wprevpro_custom_textarea_'.$x]);	
					}
					//wprevpro_custom_select_page_14, pages need to used to input for the  ["-107-"]
					if(isset($postvariablearray['wprevpro_custom_select_page_'.$x])){
						$tempidtext = 'custom_select_page_'.$x;
						if(isset($postvariablearray['wprevpro_custom_select_page_'.$x.'_label']) && $postvariablearray['wprevpro_custom_select_page_'.$x.'_label'] !=''){
							//see if the hidden label is set and use it as the name if so
							$tempidtext = sanitize_text_field($postvariablearray['wprevpro_custom_select_page_'.$x.'_label']);	
						}
						$temppageid = sanitize_text_field($postvariablearray['wprevpro_custom_select_page_'.$x]);	
						$woocreateforprodid =$temppageid;
						$customdataarray[$tempidtext] = $temppageid;	
						
						$temppagename = '';
						if(isset($postvariablearray['wprevpro_custom_select_page_'.$x.'_pname'])){
							$temppagename = sanitize_text_field($postvariablearray['wprevpro_custom_select_page_'.$x.'_pname']);	
						} else {
							$temppagename = get_the_title( $temppageid );
						}
						
						$customdataarray[$tempidtext."_name"] = $temppagename;
						
						
						//also tag this review to this selected post
						$posts[] = "-".intval($postvariablearray['wprevpro_custom_select_page_'.$x])."-";	
					}
					//wprevpro_custom_select_tag_15
					if(isset($postvariablearray['wprevpro_custom_select_tag_'.$x])){
						$tempidtext = 'custom_select_tag'.$x;
						if(isset($postvariablearray['wprevpro_custom_select_tag_'.$x.'_label']) && $postvariablearray['wprevpro_custom_select_tag_'.$x.'_label'] !=''){
							//see if the hidden labe is set and use it as the name if so
							$tempidtext = sanitize_text_field($postvariablearray['wprevpro_custom_select_tag_'.$x.'_label']);	
						}
						$selectedtagarray = $postvariablearray['wprevpro_custom_select_tag_'.$x];
						
						if(is_array($selectedtagarray)){
							foreach ($selectedtagarray as $aval){
								$temptags[] = sanitize_text_field($aval);
							}
						}
						
						//also save as custom_data input
						$customdataarray[$tempidtext] = json_encode($selectedtagarray);

					}
					//wprevpro_custom_checkbox_12
					if(isset($postvariablearray['wprevpro_custom_checkbox_'.$x])){
						$tempidtext = 'wprevpro_custom_checkbox_'.$x;
						if(isset($postvariablearray['wprevpro_custom_checkbox_'.$x.'_label']) && $postvariablearray['wprevpro_custom_checkbox_'.$x.'_label'] !=''){
							//see if the hidden labe is set and use it as the name if so
							$tempidtext = sanitize_text_field($postvariablearray['wprevpro_custom_checkbox_'.$x.'_label']);	
						}
						$customdataarray[$tempidtext] = sanitize_text_field($postvariablearray['wprevpro_custom_checkbox_'.$x]);	
						
					}
					//custom star ratings wprevpro_custom_starrating_12   $rating = intval($postvariablearray['wprevpro_review_rating']);
					if(isset($postvariablearray['wprevpro_custom_starrating_'.$x])){
						$tempidtext = 'custom_starrating_'.$x;
						if(isset($postvariablearray['wprevpro_custom_starrating_'.$x.'_label']) && $postvariablearray['wprevpro_custom_starrating_'.$x.'_label'] !=''){
							//see if the hidden labe is set and use it as the name if so
							$tempidtext = sanitize_text_field($postvariablearray['wprevpro_custom_starrating_'.$x.'_label']);	
						}
						$customstararray[$tempidtext] = sanitize_text_field($postvariablearray['wprevpro_custom_starrating_'.$x]);	
					}
					//for custom media, going to add this to the regular media so it can be shown and edited with review list.
					$mediaurlsarray = Array();
					$mediaurlsarraythumb = Array();
					if(isset($postvariablearray['wprevpro_custom_media_'.$x])){
						//update mediaurlsarrayjson
						//$mediaurlsarrayjson = json_encode($mediaurlsarray);
						$tempmedia =sanitize_text_field($postvariablearray['wprevpro_custom_media_'.$x]);
						$mediaurlsarray = array($tempmedia, "", "", "", "", "", "", "");
						$mediaurlsarrayjson = json_encode($mediaurlsarray);
						
						//if this is a youtube url then we need to add a generic thumbnail
						//youtu.be youtube
						$youtubeplayurl ='';
						if (strpos($tempmedia, 'youtu.be') !== false || strpos($tempmedia, 'youtube') !== false) {
							//must be a youtube video
							$youtubeplayurl = esc_url( plugins_url( 'partials/imgs/youtube_play_button.png', __FILE__ ) );
							//build url to thumbnail
							preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $tempmedia, $matches);
							//var_dump($matches);
							$youtube_id = $matches[1];
							if(isset($youtube_id) && $youtube_id!=''){
							$youtubeplayurl = "https://img.youtube.com/vi/".$youtube_id."/sddefault.jpg";
							}
							
						}

						//need mediathumburlsarrayjson to stay the same
						$mediaurlsarraythumb = array($youtubeplayurl, "", "", "", "", "", "", "");
						$mediathumburlsarrayjson = json_encode($mediaurlsarraythumb);
					}
				}
				$customdata = json_encode($customdataarray);
				$customdatastars = json_encode($customstararray);
				$posts = json_encode($posts);
				$temptags = json_encode($temptags);
							
				$data = array(
				'pageid' => "$pageid",
				'pagename' => "$pagename",
				'from_url' => "$from_url",
				'rating' => "$rating",
				'review_text' => "$text",
				'hide' => "$hidereview",
				'reviewer_name' => "$name",
				'reviewer_email' => "$email",
				'company_name' => "$company_name",
				'company_title' => "$company_title",
				'created_time' => "$newdateformat",
				'created_time_stamp' => "$time",
				'review_length' => "$review_length",
				'review_length_char' => "$review_length_char",
				'type' => "$r_editrtype",
				'from_name' => "$from",
				'company_url' => "$company_website",
				'from_logo' => "$from_logo",
				'review_title' => "$title",
				'categories' => "$catidjson",
				'posts' => "$posts",
				'consent' => "$rconsent",
				'tags' => "$temptags",
				'meta_data' => "$metadata",
				'custom_data' => "$customdata",
				'custom_stars' => "$customdatastars",
				'mediaurlsarrayjson' => "$mediaurlsarrayjson",
				'mediathumburlsarrayjson' => "$mediathumburlsarrayjson", 
				);
				$format = array( 
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
					'%s'
				); 
				global $wpdb;
				$table_name = $wpdb->prefix . 'wpfb_reviews';
				$insertdata = $wpdb->insert( $table_name, $data, $format );
				$fileurl='';
				$emailfileurlsarray=Array();
				if(!$insertdata){
					$hasError = true;
					//$error_msg = $error_msg ."Could not save review.<br>";
					$error_msg = $error_msg .__('Could not save review.', 'wp-review-slider-pro');
				} else {
					//review inserted, now add avatar
					$insertid = $wpdb->insert_id;
					//print_r($uploadfilesarray);
					if( ! empty( $uploadfilesarray ) ) {
					  foreach( $uploadfilesarray as $key => $file ) {
						 //echo $key;	//wprevpro_review_video, wprevpro_review_avatar
						 //print_r($file);
						if( is_array( $file ) && $file['size']>0 ) {
							//need a check here for custom media upload and 
						  $avatarupload = $this->upload_user_file( $file, $key);
						  $uploadmsg = $avatarupload['msg'];
						  if (strpos($uploadmsg, 'Error') !== false) {
							  $hasError = true;
							  $error_msg = $error_msg . $avatarupload['msg'];
						  } else {
							  $success_msg = $success_msg . $avatarupload['msg'];
							  $fileurl = $avatarupload['file_url'];
							  $emailfileurlsarray[$key]=$fileurl;
							  $tempvidiconurl = WPREV_PLUGIN_URL."/public/partials/imgs/video-icon.svg";
							  $tempvidiconurlpng = WPREV_PLUGIN_URL."/public/partials/imgs/video-icon.png";
							  //$fileurlsmall = $avatarupload['file_url_small'];
							  if($key=='wprevpro_review_avatar'){
								$data = array('userpic' => "$fileurl");
								$format = array('%s');
								$updatetempquery = $wpdb->update($table_name, $data, array( 'id' => $insertid ), $format, array( '%d' ));
							  } else if (strpos($key, 'wprevpro_custom_media') !== false  || $key=='wprevpro_review_video') {
								//this must be media file
								//get the current media array and add this to it.
								if(count($mediaurlsarray)==0){
									$mediaurlsarray[0]=$fileurl;
									if($key=='wprevpro_review_video'){
										$mediaurlsarraythumb[0] = $tempvidiconurl;
										$mediathumburlsarrayjson = json_encode($mediaurlsarraythumb);
									}
								} else {
									for ($i = 0; $i < count($mediaurlsarray); $i++)  {
										if($mediaurlsarray[$i]==''){
											$mediaurlsarray[$i]=$fileurl;
											if($key=='wprevpro_review_video'){
												$mediaurlsarraythumb[$i] = $tempvidiconurl;
												$mediathumburlsarrayjson = json_encode($mediaurlsarraythumb);
											}
											$i=count($mediaurlsarray);
										}
									}
								}
								$mediaurlsarrayjson = json_encode($mediaurlsarray);
								$mediathumburlsarrayjson = json_encode($mediaurlsarraythumb);
								//update the media array.
								$data = array('mediaurlsarrayjson' => "$mediaurlsarrayjson",'mediathumburlsarrayjson' => "$mediathumburlsarrayjson");
								$format = array('%s','%s');
								$updatetempquery = $wpdb->update($table_name, $data, array( 'id' => $insertid ), $format, array( '%d' ));
								
							  }
						  }
							
						} else {
							//check for default avatar values
							if(isset($defaultsubmitvalues['review_avatar'])){
								$review_avatar=$defaultsubmitvalues['review_avatar'];
								$data = array('userpic' => "$review_avatar");
								$format = array('%s');
								$updatetempquery = $wpdb->update($table_name, $data, array( 'id' => $insertid ), $format, array( '%d' ));
							}
						}
					  }
					}
					
					//find out which fields are not hidden, and only email the ones that need to be send
					$hiddenfields = array();
					for ($x = 0; $x < count($formfieldsarray); $x++) {
						if($formfieldsarray[$x]['hide_field']=='on'){
							$tempfieldname = 'wprevpro_'.$formfieldsarray[$x]['name'];
							$hiddenfields[]=$tempfieldname;
						}
					}
					
					//send email if notifyemail is set--
					//print_r($formarray);
					if($formarray['notifyemail']!=''){
						$site_title = get_bloginfo( 'name' );
						$siteurl = admin_url();
						$replytoemail ='';
						if(isset($postvariablearray['wprevpro_reviewer_email'])){
							$replytoemail = sanitize_text_field($postvariablearray['wprevpro_reviewer_email']);
						}
						$headers = array('Content-Type: text/html; charset=UTF-8');
						if($replytoemail!=''){
							$headers = array('Content-Type: text/html; charset=UTF-8','Reply-To: <'.$replytoemail.'>');
						}
						if($hidereview == 'yes'){
							$emailstring = __('<p>Someone just submitted a new review on your site. To display it on your site you need to unhide it on the <a href="'.$siteurl.'admin.php?page=wp_pro-reviews&revfilter=submitted" target="_blank" style="text-decoration: none;">Review List page</a> in the plugin by clicking the "eye" icon.</p><p><b>Details</b></p>', 'wp-review-slider-pro');
						} else {
							$emailstring = __('<p>Someone just submitted a new review on your site. It has automatically been approved (shown). To hide it visit the <a href="'.$siteurl.'admin.php?page=wp_pro-reviews&revfilter=submitted" target="_blank" style="text-decoration: none;">Review List page</a> in the plugin and click the "eye" icon.</p><p><b>Details</b></p>', 'wp-review-slider-pro');
						}
						if (!in_array("wprevpro_review_rating", $hiddenfields)){
							$emailstring = $emailstring . __('<p>Rating: '.sanitize_text_field($postvariablearray['wprevpro_review_rating']).'</p>', 'wp-review-slider-pro');
						}
						if (!in_array("wprevpro_review_title", $hiddenfields)){
							$emailstring = $emailstring . __('<p>Subject: '.sanitize_text_field($postvariablearray['wprevpro_review_title']).'</p>', 'wp-review-slider-pro');
						}
						if (!in_array("wprevpro_review_text", $hiddenfields)){
							$emailstring = $emailstring . __('<p>Text: '.sanitize_text_field($postvariablearray['wprevpro_review_text']).'</p>', 'wp-review-slider-pro');
						}
						if (!in_array("wprevpro_reviewer_name", $hiddenfields)){
							$emailstring = $emailstring . __('<p>Name: '.sanitize_text_field($postvariablearray['wprevpro_reviewer_name']).'</p>', 'wp-review-slider-pro');
						}
						if (!in_array("wprevpro_reviewer_email", $hiddenfields)){
							$emailstring = $emailstring . __('<p>Email: '.sanitize_text_field($postvariablearray['wprevpro_reviewer_email']).'</p>', 'wp-review-slider-pro');
						}
						if (!in_array("wprevpro_company_name", $hiddenfields)){
							$emailstring = $emailstring . __('<p>Company: '.sanitize_text_field($postvariablearray['wprevpro_company_name']).'</p>', 'wp-review-slider-pro');
						}
						if (!in_array("wprevpro_company_title", $hiddenfields)){
							$emailstring = $emailstring . __('<p>Title: '.sanitize_text_field($postvariablearray['wprevpro_company_title']).'</p>', 'wp-review-slider-pro');
						}
						if (!in_array("wprevpro_company_website", $hiddenfields)){
							$emailstring = $emailstring . __('<p>Website: '.sanitize_text_field($postvariablearray['wprevpro_company_website']).'</p>', 'wp-review-slider-pro');
						}
						if (!in_array("wprevpro_review_consent", $hiddenfields) && isset($postvariablearray['wprevpro_review_consent'])){
							$emailstring = $emailstring . __('<p>Display Consent: '.sanitize_text_field($postvariablearray['wprevpro_review_consent']).'</p>', 'wp-review-slider-pro');
						}
						if (!in_array("wprevpro_review_avatar", $hiddenfields) && isset($emailfileurlsarray['wprevpro_review_avatar'])){ 
							$emailstring = $emailstring . __('<p>User Avatar:</p>', 'wp-review-slider-pro');
							if ($fileurl!=''){
								$emailstring = $emailstring . '<p><img src="'.$emailfileurlsarray['wprevpro_review_avatar'].'" width="100px" height="100px"></p>';
							}
						}
						if (!in_array("wprevpro_review_video", $hiddenfields) && isset($emailfileurlsarray['wprevpro_review_video'])){
							$emailstring = $emailstring . __('<p>Video:</p>', 'wp-review-slider-pro');
							if ($fileurl!=''){
								$emailstring = $emailstring . '<p><a href="'.$emailfileurlsarray['wprevpro_review_video'].'" target="_blank" style="text-decoration: none;"><img src="'.$tempvidiconurlpng.'" width="100px" height="100px"></a></p>';
							}
						}
						//add custom data $customdata
						if($customdata!=''){
							$emailstring = $emailstring . __('<p>Custom Data: '.sanitize_text_field($customdata).'</p>', 'wp-review-slider-pro');
						}
						
						
						$reviewlisturl = $siteurl.'admin.php?page=wp_pro-reviews&revfilter=submitted';
						$loginreviewlisturl = esc_url( wp_login_url( $reviewlisturl ) );
						$emailstring = $emailstring . __('<br><p> <a href="'.$loginreviewlisturl.'" target="_blank" style="text-decoration: none;">View in Plugin Admin</a></p><p> To turn off or modify these notifications go to the Forms page in the plugin and remove the Notify Email.</p>', 'wp-review-slider-pro');

						$subject = __('New Review Submission - ', 'wp-review-slider-pro').$site_title." - ".$formtitle;
						
						$sendtoemail = sanitize_text_field($formarray['notifyemail']);
						if ( wrsp_fs()->can_use_premium_code() ) {
							$mailsent = __('Notification sent to admin email. email:', 'wp-review-slider-pro').$sendtoemail.__(',subject:', 'wp-review-slider-pro').$subject;
							$sendmailresult = wp_mail( $sendtoemail, $subject, $emailstring, $headers );
							if(!$sendmailresult){
								$mailsent = __('Error sending Notification to admin email. email:', 'wp-review-slider-pro');
							}
						}
						//------

					}
				}
			}
			
			$randomid = rand(1,5000);	//only used to temporarily store.
			$wprev_form_errors_array = array('');
			$wprev_form_errors_array['mailsent']=$mailsent;
			
			if($hasError==false){
				$error = "no";
				//for testing
				$wprev_form_errors_array[$randomid]=$success_msg;
				//$form_misc_array['successmsg'];
				update_option( 'wprevpro_form_errors', $wprev_form_errors_array );
				$wprev_form_errors_array['randid']=$randomid;
				$wprev_form_errors_array['dbmsg']=$success_msg;
				//update the total and average
				$this->updatetotalavgreviewssubmitted('submitted', $pageid, '', '' );
				
				//see if we need to create woo review here
				if(isset($postvariablearray['wprevpro_create_woo']) && $postvariablearray['wprevpro_create_woo']=="on"){
					//echo "create woo";
					//first make sure woo is active
					include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
					if ( is_plugin_active( 'woocommerce/woocommerce.php') ) {
					  // Do what you want in case woocommerce is installed
					    $product_id = intval($woocreateforprodid); // Replace with the product ID
						$tempuser = get_user_by( 'email', $email );
						$tempuserId = $tempuser->ID;

						$review_data = array(
							'comment_post_ID'  => $product_id, // Replace with the product ID you want to review
							'comment_author'   => $name,
							'comment_author_email' => $email,
							'comment_author_IP' => $commentauthorIP,
							'comment_content'  => $text,
							'comment_approved' => 0, // 1 for approved, 0 for pending
							'user_id'          => $tempuserId, // Replace with the user ID who is leaving the review
							'comment_type'=> 'review',
						);
						$rating      = $rating; // Replace with the desired rating (1 to 5)
						// Insert the review
						$review_id = wp_insert_comment( $review_data );
						update_comment_meta( $review_id, 'rating', $rating );
					  
					}
				}
				
			} else {
				$error = "yes";
				//--save the error messages in the wp options table, use generated ID to grab on frontend and display, then delete after displayed, if query arg is error then display
				$wprev_form_errors_array[$randomid]=$error_msg;
				update_option( 'wprevpro_form_errors', $wprev_form_errors_array );
				$wprev_form_errors_array['randid']=$randomid;
				$wprev_form_errors_array['dbmsg']=$error_msg;
			}

			if($isajax==false){
				
				//are we sending back to same page or redirecting. 
				if(isset($form_misc_array['useajax']) && $form_misc_array['useajax']=='prd' && isset($form_misc_array['redirecturl']) && $form_misc_array['redirecturl']!='' && filter_var($form_misc_array['redirecturl'], FILTER_VALIDATE_URL)){
					//redirecting the user.
					$camefrom = $form_misc_array['redirecturl'];
					wp_redirect($camefrom);
				} else {

					//return to same page that was used to submit, if success then hide form and display message, if not show error message.
					$camefrom = esc_url(sanitize_text_field($postvariablearray['_wp_http_referer']));
					$queryarray = array(
								'wprevfs' => $error,
								'raid' => $randomid,
							);
					$camefrom =  add_query_arg( $queryarray, $camefrom);
					wp_safe_redirect($camefrom);
				}

			} else {
				
				$wprev_form_errors_array['error']=$error;
				$sucmsg = "Thank you for your feedback!";
				if($form_misc_array['successmsg']!=''){
					$sucmsg = $form_misc_array['successmsg'];
				}
				$wprev_form_errors_array['successmsg']=$sucmsg;
				return $wprev_form_errors_array;
			}
			
			exit();
			
	}	
	
//-----for updating options for total and avg based on pageid
	private function updatetotalavgreviewssubmitted($type, $pageid, $avg, $total ){
		
		//option wppro_total_avg_reviews[type][page][total,avg];
		//$option = 'wppro_total_avg_reviews';

		//$wppro_total_avg_reviews_array = get_option( $option );
		//if(isset($wppro_total_avg_reviews_array)){
		//	$wppro_total_avg_reviews_array = json_decode($wppro_total_avg_reviews_array, true);
		//} else {
		//	$wppro_total_avg_reviews_array = array();
		//}
		
		if($type=='submitted'){
			
			$plugin_admin_hooks = new WP_Review_Pro_Admin_Hooks( $this->_token, $this->version );
			$plugin_admin_hooks->updateallavgtotalstable();
				
				
			//query db and calculate new values
			/*
			global $wpdb;
			$table_name = $wpdb->prefix . 'wpfb_reviews';
			$field_name = 'rating';
			$type = 'Submitted';
			$prepared_statement = $wpdb->prepare( "SELECT {$field_name} FROM {$table_name} WHERE  type = %s AND hide != %s", $type, 'yes' );
			$ratingsarray = $wpdb->get_col( $prepared_statement );
			$ratingsarray = array_filter($ratingsarray);
			$avg = 0;
			$total =  round(count($ratingsarray), 0);
			
			if(count($ratingsarray)>0){
				$avg = round(array_sum($ratingsarray) / count($ratingsarray), 1);
			}
			$wppro_total_avg_reviews_array[$pageid]['avg'] = $avg;
			$wppro_total_avg_reviews_array[$pageid]['total'] = $total;
			$wppro_total_avg_reviews_array[$pageid]['total_indb'] = $total;
			$wppro_total_avg_reviews_array[$pageid]['avg_indb'] = $avg;
			
			//ratings for badge 2
			$temprating = $this->wprp_get_temprating($ratingsarray);
			if(isset($temprating)){
				$wppro_total_avg_reviews_array[$pageid]['numr1'] = array_sum($temprating[1]);
				$wppro_total_avg_reviews_array[$pageid]['numr2'] = array_sum($temprating[2]);
				$wppro_total_avg_reviews_array[$pageid]['numr3'] = array_sum($temprating[3]);
				$wppro_total_avg_reviews_array[$pageid]['numr4'] = array_sum($temprating[4]);
				$wppro_total_avg_reviews_array[$pageid]['numr5'] = array_sum($temprating[5]);
			}
			*/
		
		}

		//print_r($wppro_total_avg_reviews_array);
		//$new_value = json_encode($wppro_total_avg_reviews_array, JSON_FORCE_OBJECT);
		//update_option( $option, $new_value);
		
	}
		//used to get back number of ratings for each value
		/*
	private function wprp_get_temprating($ratingsarray){
		//fist set to blank instead of null
		for ($x = 0; $x <= 5; $x++) {
			$temprating[$x][]=0;
		}
		foreach ( $ratingsarray as $tempnum ) 
		{
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
	*/
	
	
	private function validate_url($url) {
		$path = parse_url($url, PHP_URL_PATH);
		$encoded_path = array_map('urlencode', explode('/', $path));
		$url = str_replace($path, implode('/', $encoded_path), $url);

		return filter_var($url, FILTER_VALIDATE_URL) ? true : false;
	}

	//used to upload user submitted files from front end ex: avatar
	private function upload_user_file( $file = array(),$key='' ) {
		$custommedia = false;
		$fileprefix = "avatar";
		if (strpos($key, 'wprevpro_custom_media') !== false) {
				$custommedia = true;
				$fileprefix = "cm";
		}
		$results = Array();
		if ( ! function_exists( 'wp_handle_upload' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
		}
		$file_return = wp_handle_upload( $file, array('test_form' => false ) );

		//file type check
		$allowed_file_types = array('image/jpg','image/jpeg','image/gif','image/png');
		if (strpos($file_return['type'], "image") !== false || strpos($file_return['type'], "video") !== false) {

		//if(in_array($file_return['type'], $allowed_file_types)) {
		
		  if( isset( $file_return['error'] ) || isset( $file_return['upload_error_handler'] ) ) {
				$results['msg']="Error: Upload failed. Please contact us.";
		  } else {
			  $filename = $file_return['file'];
			  $attachment = array(
				  'post_mime_type' => $file_return['type'],
				  'post_title' => $fileprefix."_".preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
				  'post_content' => '',
				  'post_status' => 'inherit',
				  'guid' => $file_return['url']
			  );
			  $attachment_id = wp_insert_attachment( $attachment, $file_return['url'] );
			  require_once(ABSPATH . 'wp-admin/includes/image.php');
			  $attachment_data = wp_generate_attachment_metadata( $attachment_id, $filename );
			  wp_update_attachment_metadata( $attachment_id, $attachment_data );

			  if( 0 < intval( $attachment_id ) ) {
				$results['aid']=$attachment_id;
				//use thumbnail if generated
				if(isset($attachment_data['sizes']['thumbnail']['file']) && $custommedia==false){
					$upload_dir = wp_upload_dir();
					$results['file_url']= $upload_dir['baseurl'].$upload_dir['subdir']."/".$attachment_data['sizes']['thumbnail']['file'];
					if(isset($attachment_data['sizes']['widget-thumbnail'])){
						$results['file_url_small']=$upload_dir['baseurl'].$upload_dir['subdir']."/".$attachment_data['sizes']['widget-thumbnail']['file'];
					} else {
						$results['file_url_small']='';
					}
				} else {
					$results['file_url']=$file_return['url'];
					$results['file_url_small']='';
				}
				$results['msg']="Success uploading file.";
			  }
		  }
		} else {
			$results['msg']="Error: Please select the correct file type.";
		}
		
		return $results;
	
	}
		
	
	/**
	 * Ajax, retrieves float html from table, called from javascript file wprevpro_float_page.js
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function wppro_getfloat_ajax(){
	//ini_set('display_errors',1);  
	//error_reporting(E_ALL);
		check_ajax_referer('randomnoncestring', 'wpfb_nonce');
		$floatid = sanitize_text_field($_POST['fid']);
		$whattofloatid = sanitize_text_field($_POST['wtfid']);
		$whattofloattype = sanitize_text_field($_POST['wtftype']);
		
		if(isset($_POST['innerdivonly']) && $_POST['innerdivonly']=='yes'){
			$innerdivsonly = true;
		} else {
			$innerdivsonly = false;
		}
		$startoffset=0;
		if(isset($_POST['startoffset']) && $_POST['startoffset']>0){
			$startoffset=intval($_POST['startoffset']);
		}
		//echo "innerdivsonly:".$innerdivsonly;
		
		$floathtml = $this->wppro_getfloat_html($floatid,$whattofloatid,$whattofloattype,'',$innerdivsonly,$startoffset);
		
		echo $floathtml;

		die();
	}	

	
	public function wppro_getfloat_html($floatid,$whattofloatid,$whattofloattype,$animatedelay='',$innerdivsonly=false,$startoffset=0){
	//ini_set('display_errors',1);  
	//error_reporting(E_ALL);
	global $wpdb;
	$innerhtml='';
	$displaynone = "style='display:none;'";
	
		if($floatid=='new' || $floatid<0){
			$floatarray = array();
			$displaynone='';
		}
		//floatarray used for styling float and placement only, not html inside
		$styleclosex ='';
		$insidefloat=true;
		//get the badge html or the review html based on the whattofloatid and whattofloattype
		if($whattofloattype=='badge' && $whattofloatid>0){
			$whattofloatid = intval($whattofloatid); 
			$table_name = $wpdb->prefix . 'wpfb_badges';
			$whattofloatarray = $wpdb->get_row("SELECT id,style FROM $table_name WHERE id = '$whattofloatid' ", ARRAY_A);
			//print_r($whattofloatarray);
			//try to call the badge function to get the html, then wrap with floating html
			$a['tid']=$whattofloatarray['id'];
			ob_start();
			include plugin_dir_path( __FILE__ ) . '/partials/wp-review-slider-pro-public-display_badge.php';
			$innerhtml = ob_get_clean();
			
			//style close x
			if($whattofloatarray['style']=='3'){
				$styleclosex = '<style>#wprev_pro_closefloat_'.$floatid.'{top: 0px;}</style>';
			}
			//$styleclosex = '<style>#wprev_pro_closefloat_'.$floatid.'{right: 5px;}</style>';

		} else if($whattofloattype=='reviews'){

			$revtemplateid = intval($whattofloatid); 
			$table_name = $wpdb->prefix . 'wpfb_post_templates';
			$whattoslidearray = $wpdb->get_row("SELECT id,style FROM $table_name WHERE id = '$revtemplateid' ", ARRAY_A);
			//print_r($whattoslidearray);
			//try to call the template function to get the html, then wrap
			$a['tid']=$whattoslidearray['id'];
			//set this to yes to change onpage js when creating the review
			$inslideout="yes";
			ob_start();
			include plugin_dir_path( __FILE__ ) . '/partials/wp-review-slider-pro-public-display.php';
			$innerhtml = ob_get_clean();
			//style close x
			if($whattoslidearray['style']==4 || $whattoslidearray['style']==3){
				$styleclosex = '<style>#wprev_pro_closefloat_'.$floatid.'{right: 13px;}</style>';
			}
			
		} else if($whattofloattype=='pop'){

			$revtemplateid = intval($whattofloatid); 
			$table_name = $wpdb->prefix . 'wpfb_post_templates';
			$whattoslidearray = $wpdb->get_row("SELECT id,style FROM $table_name WHERE id = '$revtemplateid' ", ARRAY_A);
			//print_r($whattoslidearray);
			//try to call the template function to get the html, then wrap
			$a['tid']=$whattoslidearray['id'];
			//set this to yes to change onpage js when creating the review
			$inslideout="yes";
			ob_start();
			include plugin_dir_path( __FILE__ ) . '/partials/wp-review-slider-pro-public-display_pop.php';
			$innerhtml = ob_get_clean();
			$innerhtml = '<div class="wprev_pop_contain">'.$innerhtml.'</div>';
		
		}
	
		//get all html and return
		$allfloathtml='';
		//outer div
		$allfloathtml=$allfloathtml.'<div id="wprev_pro_float_'.$floatid.'" '.$displaynone.' class="wprev_pro_float_outerdiv floattype_'.$whattofloattype.'">'.$styleclosex .'<span class="wprev_pro_float_outerdiv-close" id="wprev_pro_closefloat_'.$floatid.'"></span>';
		//middle badge or review
		$allfloathtml=$allfloathtml.$innerhtml;
		//end outer div
		$allfloathtml=$allfloathtml.'</div>';
		
		if($innerdivsonly){
			$allfloathtml=$innerhtml;
		}
		
		return $allfloathtml;
	}
	
	/**
	 * Ajax, retrieves float html from table, called from javascript file wprevpro_float_page.js
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function wppro_getslideout_ajax(){
	//ini_set('display_errors',1);  
	//error_reporting(E_ALL);
		check_ajax_referer('randomnoncestring', 'wpfb_nonce');
		$floatid = sanitize_text_field($_POST['fid']);
		$revtemplateid = sanitize_text_field($_POST['rtid']);
		$slidehtml = $this->wppro_getslideout_html($floatid,$revtemplateid);
		
		echo $slidehtml;

		die();
	}	

	
	public function wppro_getslideout_html($floatid,$revtemplateid){
	//ini_set('display_errors',1);  
	//error_reporting(E_ALL);
	global $wpdb;
	
		if($floatid='new' || $floatid<0){
			//$floatarray = array();
		} else {
			//get values from db
			//$floatid = intval($floatid); 
			//$table_name = $wpdb->prefix . 'wpfb_floats';
			//$floatarray = $wpdb->get_row("SELECT * FROM $table_name WHERE id = '$floatid' ", ARRAY_A);
		}
		//floatarray used for styling float and placement only, not html inside
		
			
		//get the review template html
		$revtemplateid = intval($revtemplateid); 
		$table_name = $wpdb->prefix . 'wpfb_post_templates';
		$whattoslidearray = $wpdb->get_row("SELECT id FROM $table_name WHERE id = '$revtemplateid' ", ARRAY_A);
		
		$innerhtml = '';
		if(isset($whattoslidearray['id'])){
			//try to call the template function to get the html, then wrap
			$a['tid']=$whattoslidearray['id'];
			$inslideout="yes";
			ob_start();
			include plugin_dir_path( __FILE__ ) . '/partials/wp-review-slider-pro-public-display.php';
			$innerhtml = ob_get_clean();
		}
			
		//get all html and return
		$allfloathtml='';
		//outer div
		$allfloathtml=$allfloathtml.'<div id="wprev_pro_slideout_'.$floatid.'" class="wprev_pro_slideout_outerdiv">';
		//middle badge or review
		$allfloathtml=$allfloathtml.$innerhtml;
		//end outer div
		$allfloathtml=$allfloathtml.'</div>';
		
		
		return $allfloathtml;
	}	
	
	
	//currently only being called from admin review list page. We use to try and do this on Front end as well. Now we try to save FB avatars to db.
	/**
	 * Ajax, tries to update missing image src, facebook expires them.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function wppro_update_profile_pic_ajax(){
	//ini_set('display_errors',1);  
	//error_reporting(E_ALL);
		check_ajax_referer('randomnoncestring', 'wpfb_nonce');
		$revid = sanitize_text_field($_POST['revid']);
		if($revid>0){
		//get review details, if FB then try to update it with call to fbapp.ljapps.com
		global $wpdb;
		$table_name = $wpdb->prefix . 'wpfb_reviews';
		$reviewinfo = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$table_name." WHERE id=%d LIMIT 1", "$revid"), ARRAY_A);

			//check for type and continue if FB
			if($reviewinfo[0]['type']=="Facebook"){
				//set default image
				$newimagesrc['url'] = plugin_dir_url( __FILE__ )."/partials/imgs/fb_mystery_man_big.png";
				//now try to get from fb app.
				$option = get_option('wprevpro_options');
				if(isset($option['fb_app_code'])){
					$accesscode = $option['fb_app_code'];
					$tempurl = "https://fbapp.ljapps.com/ajaxgetprofilepic.php?q=getpic&acode=".$accesscode."&callback=cron&pid=".$reviewinfo[0]['pageid']."&rid=".$reviewinfo[0]['reviewer_id'];
					
					if (ini_get('allow_url_fopen') == true) {
						$data=file_get_contents($tempurl);
					} else if (function_exists('curl_init')) {
						$data=$this->file_get_contents_curl($tempurl);
					}
					//escape and add to db
					$profileimgurl=json_decode($data,true);
					$profileimgurl = $profileimgurl['data'];
					$escapedimgurl = esc_url( $profileimgurl);
					if($escapedimgurl!=''){
						$newimagesrc['url'] = $escapedimgurl;
						$temprevid = $reviewinfo[0]['id'];
						//update the database with this new image url
						$updatereviewsrc = $wpdb->query( $wpdb->prepare("UPDATE ".$table_name." SET userpic = %s WHERE id = %d AND reviewer_id = %s", $escapedimgurl, $temprevid, $reviewinfo[0]['reviewer_id'] ) );
						$temprevid ='';
					}
				}
			}

		}
		exit();
	}
	

	/**
	 * Ajax, when clicking the load more button gets more revs html
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function wppro_loadmore_revs_ajax(){
	//ini_set('display_errors',1);  
	//error_reporting(E_ALL);
	
	global $wpdb;
	$table_name = $wpdb->prefix . 'wpfb_post_templates';
	
		check_ajax_referer('randomnoncestring', 'wpfb_nonce');
		$templateid = intval(sanitize_text_field($_POST['revid']));
		$perrow = intval(sanitize_text_field($_POST['perrow']));
		$nrows = intval(sanitize_text_field($_POST['nrows']));
		$callnum = intval(sanitize_text_field($_POST['callnum']));
		$notinstring = sanitize_text_field($_POST['notinstring']);
		$onereview = sanitize_text_field($_POST['onereview']);
		$shortcodepageid = sanitize_text_field($_POST['shortcodepageid']);
		$shortcodelang = sanitize_text_field($_POST['shortcodelang']);
		$shortcodetag = sanitize_text_field($_POST['shortcodetag']);
		$cpostid = sanitize_text_field($_POST['cpostid']);
		$textsearch ='';
		$textsort ='';
		$textrating ='';
		$textsource ='';
		$textlang ='';
		$textrtype ='';
		$filterbar='';	//will either say lastslide or yes
		if(isset($_POST['filterbar'])){
			$filterbar = sanitize_text_field($_POST['filterbar']);
		}
		if(isset($_POST['textsearch'])){
			$textsearch = sanitize_text_field($_POST['textsearch']);
		}
		if(isset($_POST['textsort'])){
			$textsort = sanitize_text_field($_POST['textsort']);
		}
		if(isset($_POST['textrating'])){
			$textrating = sanitize_text_field($_POST['textrating']);
		}
		if(isset($_POST['textsource'])){
			$textsource = sanitize_text_field($_POST['textsource']);
		}
		if(isset($_POST['textlang'])){
			$textlang = sanitize_text_field($_POST['textlang']);
		}
		if(isset($_POST['textrtype'])){
			$textrtype = sanitize_text_field($_POST['textrtype']);
		}
		
		//for pagination, clickedpnum is either a number or dotshigh or dotshigh
		if(isset($_POST['clickedpnum'])){
			$clickedpnum = sanitize_text_field($_POST['clickedpnum']);
		} else {
			$clickedpnum ='';
		}
		
		$innerhtml ='';

		//echo $templateid.'-'. $perrow.'-'. $nrows.'-'. $callnum.'-';
		$currentform = $wpdb->get_results("SELECT * FROM $table_name WHERE id = ".$templateid);
		$template_misc_array = json_decode($currentform[0]->template_misc, true);
		
		//print_r($currentform);
		
		if($onereview=='yes'){
			$reviewsperpage = 1;
		} else {
			$reviewsperpage = $perrow * $nrows;
		}
		$offeststart = $reviewsperpage*$callnum;
		
		if($currentform[0]->createslider == "yes"){
			if($callnum==1){
				//changing offset for first call depending on number of slides
				$offeststart = $reviewsperpage*$currentform[0]->numslides;
			} else if($callnum>1) {
				$offeststart = ($reviewsperpage*$currentform[0]->numslides)+(($callnum-1)*$reviewsperpage);
			}
		}
		//change offeststart if this is a pagination click
		if($clickedpnum!=''){
			$offeststart = $reviewsperpage*($clickedpnum-1);
		}
		
		$notinarraycount = 0;
		if(substr_count($notinstring, ',')>0){
			$notinarraycount = substr_count($notinstring, ',') + 1;
		}
		if($offeststart>0){
			$offeststart = $offeststart - $notinarraycount;
			if($offeststart<0){
				$offeststart=0;
			}
		} 
		
		if($clickedpnum==1 && $filterbar!='no') {	//and this is a click from filter bar then reset notinstring.
			$notinstring = '';
		}
		
		$tempreviewsperpage = $reviewsperpage;
		
		//if this is a slick slider with header filter set then get all matches 
		$sliusingfilter = false;
			//if($textsearch!='' || $textsort!='' || $textrating!='' || $textsource!='' || $textlang!='' || $textrtype!=''){

				if($currentform[0]->createslider == "sli"){
					if($filterbar=='yes'){	
						$tempreviewsperpage= $currentform[0]->display_num*$currentform[0]->display_num_rows*$currentform[0]->numslides;
						//$notinstring='';
						//$offeststart=0;
						$sliusingfilter = true;
						//$callnum=1;
					} else {
						//$offeststart = $reviewsperpage*($callnum+1);
						$offeststart = ($reviewsperpage*$currentform[0]->numslides)+(($callnum-1)*$reviewsperpage);
					}
				}
			
				//if this is regular slider then we need to fix $offeststart
				if($currentform[0]->createslider == "yes"){
					if($filterbar=='yes'){
						//$offeststart = 0;
						//$callnum=1;
					} else {
						$offeststart = $reviewsperpage*($callnum+1);
					}
					
				}
			//}
			
		//reset not in string.
		if($filterbar=='yes'){
			$notinstring = '';
		}
		$offeststart = 0;
		
		
		//make call to get reviews
		require_once("partials/getreviews_class.php");
		$reviewsclass = new GetReviews_Functions();
		
		$totalreviewsarray = $reviewsclass->wppro_queryreviews($currentform,$offeststart,$tempreviewsperpage,$notinstring,$shortcodepageid,$shortcodelang,$cpostid,$textsearch,$textsort,$textrating,$textlang,$shortcodetag,'','','',$textrtype,$textsource);
		$totalreviews = $totalreviewsarray['reviews'];
		
		$totalreviewsnum = count($totalreviews);
		//test if we keep showing load more
		if($totalreviewsnum > $reviewsperpage){
			//must be more, keep showing load more btn, and pop off array
			array_pop($totalreviews);
			$hideldbtn = "";
		} else {
			//must not be anymore
			$hideldbtn = "yes";
		}
		$totalreviewsnum = count($totalreviews);

		$totalreviewschunked = array_chunk($totalreviews, $reviewsperpage);
		//print_r($totalreviewschunked);
		
		$iswidget=false;
		$thisiswidget="no";
		if(	$currentform[0]->template_type=="widget"){
			$iswidget=true;
			$thisiswidget="yes";
		}
		
		$ajaxsliload = true;
		$looper = 1;
		ob_start();
		foreach ( $totalreviewschunked as $reviewschunked ){
			//echo "loop1".$loop;
			$totalreviewstemp = $reviewschunked;
			//need to break $totalreviewstemp up based on how many rows, create an multi array containing them
			if($currentform[0]->display_num_rows>1 && count($totalreviewstemp)>$currentform[0]->display_num){
				//count of reviews total is greater than display per row then we need to break in to multiple rows
				for ($row = 0; $row < $currentform[0]->display_num_rows; $row++) {
					$n=1;
					foreach ( $totalreviewstemp as $tempreview ){
						//echo "<br>".$tempreview->reviewer_name;
						//echo $n."-".$row."-".$currentform[0]->display_num;
						if($n>($row*$currentform[0]->display_num) && $n<=(($row+1)*$currentform[0]->display_num)){
							$rowarray[$row][$n]=$tempreview;
						}
						$n++;
					}
				}
			} else {
				//everything on one row so just put in multi array
				$rowarray[0]=$totalreviewstemp;
			}
			
			//call the template data to create the html here
			//display_masonry-------------
			if(	$currentform[0]->display_masonry=="yes"){
				if($currentform[0]->createslider == "yes"){
					$masonryclass = "wprs_masonry";
					$masonryclass_item = "wprs_masonry_item";
					//if this is slideshow and masonry is yes
					echo '<div class="'.$masonryclass.'" data-numcol="'.$currentform[0]->display_num.'">';
				} else {
					$masonryclass = "wprs_masonry_js";
					$masonryclass_item = "wprs_masonry_item_js";
				}
			}
			
			//----add template code
			include(plugin_dir_path( __FILE__ ) . '/partials/template_style_'.$currentform[0]->style.'.php');

			if(	$currentform[0]->display_masonry=="yes"){
				if($currentform[0]->createslider == "yes"){
					echo '</div>';
				}
			}

			$looper++;
		}
		$innerhtml = ob_get_clean();

		//update the notinstring, using for everything now.
		$newnotinstring='';

		foreach ( $totalreviews as $tempreview ){
			if(isset($tempreview->id) && $tempreview->id>0){
			$newnotinstringarray[] = $tempreview->id;
			}
		}
		if(isset($newnotinstringarray) && is_array($newnotinstringarray)){
			$strnotinstr = implode(",",$newnotinstringarray);
			if($notinstring!=''){
				$newnotinstring = $notinstring.','.$strnotinstr;
			} else {
				$newnotinstring = $strnotinstr;
			}
		}


		//echo $innerhtml;
		if($innerhtml==''){
			//$innerhtml = '<div class="wprevprodiv wprev_norevsfound">'.__('No more reviews found.', 'wp-review-slider-pro').'</div>';
		}
		//$reviewresultsarray['totalreviews'] = $totalreviews;
		//$reviewresultsarray['totalreviewschunked'] = $totalreviewschunked;

		$reviewresultsarray['sliusingfilter'] = $sliusingfilter;
		//$reviewresultsarray['display_num_rows'] = $currentform[0]->display_num_rows;
		//$reviewresultsarray['count-totalreviewstemp'] = count($totalreviewstemp);
		//$reviewresultsarray['display_num'] = $currentform[0]->display_num;
		//$reviewresultsarray['looper'] = $looper;
		$reviewresultsarray['innerhtml'] = $innerhtml;
		$reviewresultsarray['callnum'] = $callnum;
		$reviewresultsarray['iswidget'] = $thisiswidget;
		$reviewresultsarray['totalreviewsnum'] = $totalreviewsnum;
		$reviewresultsarray['totalreviewsindb'] = $totalreviewsarray['totalcount'];
		$reviewresultsarray['dbcall'] = $totalreviewsarray['dbcall'];
		$reviewresultsarray['hideldbtn'] = $hideldbtn;
		$reviewresultsarray['newnotinstring'] = $newnotinstring;
		$reviewresultsarray['clickedpnum'] = intval($clickedpnum);
		$lastslidenum = ceil($totalreviewsarray['totalcount']/$reviewsperpage);
		$reviewresultsarray['reviewsperpage'] = intval($reviewsperpage);
		$reviewresultsarray['lastslidenum'] = $lastslidenum;
		//check if we need to animate the height of this
		$animateheight = 'no';
		if($currentform[0]->sliderheight!="" && $currentform[0]->sliderheight=='yes'){
			if($currentform[0]->review_same_height=='yes' || $currentform[0]->review_same_height=='cur' || $currentform[0]->review_same_height=='yea'){
				$animateheight = 'no';
			} else {
				$animateheight = 'yes';
			}
		}
		$reviewresultsarray['animateheight'] = $animateheight;

		echo json_encode($reviewresultsarray);
		//use the totalreviews array to loop and call the template.
		exit();
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

	public function get_post_excerpt_by_id( $post_id ) {
		$temppost = get_post( $post_id );
		$content = strip_shortcodes( $temppost->post_content );
		$the_excerpt = wp_trim_words($content);
		$the_excerpt = esc_attr( $the_excerpt );
		return $the_excerpt;
	}
	

}
