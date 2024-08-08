<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    WP_Airbnb_Review
 * @subpackage WP_Airbnb_Review/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WP_Airbnb_Review
 * @subpackage WP_Airbnb_Review/public
 * @author     Your Name <email@example.com>
 */
class WP_Airbnb_Review_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugintoken    The ID of this plugin.
	 */
	private $plugintoken;
	private $_token;

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
	public function __construct( $plugintoken, $version ) {

		$this->_token = $plugintoken;
		$this->version = $version;
	//$this->version = time();
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
		 * defined in WP_Airbnb_Review_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WP_Airbnb_Review_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		
		//-----only enqueue styles for templates actually used.----
		global $wpdb;
		$table_name = $wpdb->prefix . 'wpairbnb_post_templates';
		$templatearray = $wpdb->get_results("SELECT style FROM $table_name WHERE id > 0");
		foreach ( $templatearray as $template ){
			if(isset($template->style)){
				if($template->style=="1" || $template->style=="2" || $template->style=="3" || $template->style=="4" || $template->style=="5" || $template->style=="6" || $template->style=="7" || $template->style=="8" || $template->style=="9" || $template->style=="10"){
					wp_register_style( 'wp-airbnb-review-slider-public_template'.$template->style, plugin_dir_url( __FILE__ ) . 'css/wprev-public_template'.$template->style.'.css', array(), $this->version, 'all' );
					wp_enqueue_style( 'wp-airbnb-review-slider-public_template'.$template->style );
				}
			}
		}

		wp_register_style( 'wpairbnb_w3', plugin_dir_url( __FILE__ ) . 'css/wpairbnb_w3.css', array(), $this->version, 'all' );
		
		//register slider stylesheet
		wp_register_style( 'unslider', plugin_dir_url( __FILE__ ) . 'css/wprs_unslider.css', array(), $this->version, 'all' );
		wp_register_style( 'unslider-dots', plugin_dir_url( __FILE__ ) . 'css/wprs_unslider-dots.css', array(), $this->version, 'all' );

		wp_enqueue_style( 'wpairbnb_w3' );
		wp_enqueue_style( 'unslider' );
		wp_enqueue_style( 'unslider-dots' );
		
		//add inline styles from saved templates.
		//$color = get_theme_mod( 'custom-color', '#FE001A' );
		//$custom_css = ".has-background-color{background-color: $color;}";

		// Loads inline styling, but only after 'custom-style' is enqueued.
		//wp_add_inline_style( 'custom-style', $custom_css );
		

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
		 * defined in WP_Airbnb_Review_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WP_Airbnb_Review_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		
		//wp_enqueue_script( $this->_token."_unslider-min", plugin_dir_url( __FILE__ ) . 'js/wprs-unslider-min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->_token."_unslider-min", plugin_dir_url( __FILE__ ) . 'js/wprs-unslider-swipe.js', array( 'jquery' ), $this->version, false );
		
		wp_enqueue_script( $this->_token."_plublic", plugin_dir_url( __FILE__ ) . 'js/wprev-public.js', array( 'jquery' ), $this->version, false );

	}
	
	/**
	 * Register the Shortcode for the public-facing side of the site to display the template.
	 *
	 * @since    1.0.0
	 */
	public function shortcode_wpairbnb_usetemplate() {
	
				add_shortcode( 'wpairbnb_usetemplate', array($this,'wpairbnb_usetemplate_func') );
	}	 
	public function wpairbnb_usetemplate_func( $atts, $content = null ) {
		//get attributes
		    $a = shortcode_atts( array(
				'tid' => '0',
				'bar' => 'something',
			), $atts );		//$a['tid'] to get id
	
				ob_start();
				include plugin_dir_path( __FILE__ ) . '/partials/wp-airbnb-review-slider-public-display.php';
				return ob_get_clean();
	}
}
