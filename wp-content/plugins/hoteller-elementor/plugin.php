<?php
namespace HotellerElementor;

use HotellerElementor\Widgets\Hoteller_Navigation_Menu;
use HotellerElementor\Widgets\Hoteller_Blog_Posts;
use HotellerElementor\Widgets\Hoteller_Gallery_Grid;
use HotellerElementor\Widgets\Hoteller_Gallery_Masonry;
use HotellerElementor\Widgets\Hoteller_Gallery_Justified;
use HotellerElementor\Widgets\Hoteller_Gallery_Horizontal;
use HotellerElementor\Widgets\Hoteller_Album_Grid;
use HotellerElementor\Widgets\Hoteller_Distortion_Grid;
use HotellerElementor\Widgets\Hoteller_Slider_Horizontal;
use HotellerElementor\Widgets\Hoteller_Timeline;
use HotellerElementor\Widgets\Hoteller_Accommodation_Types;
use HotellerElementor\Widgets\Hoteller_Accommodation_Carousel;
use HotellerElementor\Widgets\Hoteller_Slider_Property_Clip;
use HotellerElementor\Widgets\Hoteller_Slider_Zoom;
use HotellerElementor\Widgets\Hoteller_Slider_Parallax;
use HotellerElementor\Widgets\Hoteller_Booking_History;
use HotellerElementor\Widgets\Hoteller_Availability_Search;
use HotellerElementor\Widgets\Hoteller_Availability_Calendar;
use HotellerElementor\Widgets\Hoteller_Mouse_Drive_Vertical_Carousel;
use HotellerElementor\Widgets\Hoteller_Slider_Synchronized_Carousel;
use HotellerElementor\Widgets\Hoteller_Flip_Box;
use HotellerElementor\Widgets\Hoteller_Animated_Text;
use HotellerElementor\Widgets\Hoteller_Animated_Headline;
use HotellerElementor\Widgets\Hoteller_Service_Grid;
use HotellerElementor\Widgets\Hoteller_Service_Carousel;
use HotellerElementor\Widgets\Hoteller_Testimonial_Carousel;
use HotellerElementor\Widgets\Hoteller_Food_Menu;
use HotellerElementor\Widgets\Hoteller_Testimonial_Slider;
use HotellerElementor\Widgets\Hoteller_Portfolio_Timeline;
use HotellerElementor\Widgets\Hoteller_Contact_Form;
use HotellerElementor\Widgets\Hoteller_Search;
use HotellerElementor\Widgets\Hoteller_Background_Menu_Effect;


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}

/**
 * Main Plugin Class
 *
 * Register new elementor widget.
 *
 * @since 1.0.0
 */
class Hoteller_Elementor {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct() {
		$this->add_actions();
		
		add_action( 'init', array( $this, 'init' ), -999 );
	}

	/**
	 * Add Actions
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function add_actions() {
		add_action( 'elementor/init', [ $this, 'on_elementor_init' ] );
		add_action( 'elementor/widgets/register', [ $this, 'on_widgets_registered' ] );

		//Enqueue javascript files
		add_action( 'elementor/frontend/after_register_scripts', function() {
			
			//Check if enable lazy load image
			wp_enqueue_script('masonry');
			wp_enqueue_script('lazy', plugins_url( '/hoteller-elementor/assets/js/jquery.lazy.js' ), array(), false, true );	
			wp_enqueue_script('modulobox', plugins_url( '/hoteller-elementor/assets/js/modulobox.js' ), array(), false, true );
			wp_enqueue_script('parallax-scroll', plugins_url( '/hoteller-elementor/assets/js/jquery.parallax-scroll.js' ), array(), false, true );
			wp_enqueue_script('smoove', plugins_url( '/hoteller-elementor/assets/js/jquery.smoove.js' ), array(), false, true );
			wp_enqueue_script('parallax', plugins_url( '/hoteller-elementor/assets/js/parallax.js' ), array(), false, true );
			wp_enqueue_script('blast', plugins_url( '/hoteller-elementor/assets/js/jquery.blast.js' ), array(), false, true );
			wp_enqueue_script('visible', plugins_url( '/hoteller-elementor/assets/js/jquery.visible.js' ), array(), false, true );
			
			//Add parallax script effect
			wp_enqueue_script('jarallax', plugins_url().'/hoteller-elementor/assets/js/jarallax.js', false, '', true);
			
			//Registered scripts
			wp_register_script('anime', plugins_url( '/hoteller-elementor/assets/js/anime.min.js' ), array(), false, true );
			wp_register_script('hover', plugins_url( '/hoteller-elementor/assets/js/hover.js' ), array(), false, true );
			wp_register_script('tweenmax', plugins_url( '/hoteller-elementor/assets/js/TweenMax.js' ), array(), false, true );
			wp_register_script('three', plugins_url( '/hoteller-elementor/assets/js/three.min.js' ), array(), false, true );
			wp_register_script('flickity', plugins_url( '/hoteller-elementor/assets/js/flickity.pkgd.js' ), array(), false, true );
			wp_register_script('tilt', plugins_url( '/hoteller-elementor/assets/js/tilt.jquery.js' ), array(), false, true );
			wp_register_script('hoteller-album-tilt', plugins_url( '/hoteller-elementor/assets/js/album-tilt.js' ), array(), false, true );
			wp_register_script('justifiedGallery', plugins_url( '/hoteller-elementor/assets/js/justifiedGallery.js' ), array(), false, true );
			wp_register_script('sticky-kit', plugins_url( '/hoteller-elementor/assets/js/jquery.sticky-kit.min.js' ), array(), false, true );
			wp_register_script('momentum-slider', plugins_url( '/hoteller-elementor/assets/js/momentum-slider.js' ), array(), false, true );
			wp_register_script('animatedheadline', plugins_url( '/hoteller-elementor/assets/js/jquery.animatedheadline.js' ), array(), false, true );
			wp_register_script('owl-carousel', plugins_url( '/hoteller-elementor/assets/js/owl.carousel.min.js' ), array(), false, true );
			wp_register_script('hoteller-elementor', plugins_url( '/hoteller-elementor/assets/js/hoteller-elementor.js' ), array('sticky-kit'), false, true );
			
			$params = array(
			  'ajaxurl' => esc_url(admin_url('admin-ajax.php')),
			  'ajax_nonce' => wp_create_nonce('hoteller-post-contact-nonce'),
			);
			
			wp_localize_script("hoteller-elementor", 'tgAjax', $params );
			wp_enqueue_script('hoteller-elementor', plugins_url( '/hoteller-elementor/assets/js/hoteller-elementor.js' ), false, '', true);
		} );
		
		//Enqueue CSS style files
		add_action( 'elementor/frontend/after_enqueue_styles', function() {
			wp_enqueue_style('modulobox', plugins_url( '/hoteller-elementor/assets/css/modulobox.css' ), false, false, 'all' );
			wp_enqueue_style('swiper', plugins_url( '/hoteller-elementor/assets/css/swiper.css' ), false, false, 'all' );
			wp_enqueue_style('animatedheadline', plugins_url( '/hoteller-elementor/assets/css/animatedheadline.css' ), false, false, 'all' );
			wp_enqueue_style('justifiedGallery', plugins_url( '/hoteller-elementor/assets/css/justifiedGallery.css' ), false, false, 'all' );
			wp_enqueue_style('flickity', plugins_url( '/hoteller-elementor/assets/css/flickity.css' ), false, false, 'all' );
			wp_enqueue_style('owl-carousel-theme', plugins_url( '/hoteller-elementor/assets/css/owl.theme.default.min.css' ), false, false, 'all' );
			wp_enqueue_style('hoteller-elementor', plugins_url( '/hoteller-elementor/assets/css/hoteller-elementor.css' ), false, false, 'all' );
			wp_enqueue_style('hoteller-elementor-responsive', plugins_url( '/hoteller-elementor/assets/css/hoteller-elementor-responsive.css' ), false, false, 'all' );
		});
	}
	
	/**
	 * Manually init required modules.
	 *
	 * @return void
	 */
	public function init() {

		hoteller_templates_manager()->init();
		$this->register_extension();

	}
	
	/**
	 * On Elementor Init
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function on_elementor_init() {
		$this->register_category();
	}

	/**
	 * On Widgets Registered
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function on_widgets_registered() {
		$this->includes();
		$this->register_widget();
	}

	/**
	 * Includes
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function includes() {
		require __DIR__ . '/widgets/navigation-menu.php';
		require __DIR__ . '/widgets/blog-posts.php';
		require __DIR__ . '/widgets/gallery-grid.php';
		require __DIR__ . '/widgets/gallery-masonry.php';
		require __DIR__ . '/widgets/gallery-justified.php';
		require __DIR__ . '/widgets/gallery-horizontal.php';
		require __DIR__ . '/widgets/album-grid.php';
		require __DIR__ . '/widgets/distortion-grid.php';
		require __DIR__ . '/widgets/slider-horizontal.php';
		require __DIR__ . '/widgets/timeline.php';
		require __DIR__ . '/widgets/accommodation-types.php';
		require __DIR__ . '/widgets/accommodation-carousel.php';
		require __DIR__ . '/widgets/slider-property-clip.php';
		require __DIR__ . '/widgets/slider-zoom.php';
		require __DIR__ . '/widgets/slider-parallax.php';
		require __DIR__ . '/widgets/booking-history.php';
		require __DIR__ . '/widgets/availability-search.php';
		require __DIR__ . '/widgets/availability-calendar.php';
		require __DIR__ . '/widgets/mouse-driven-vertical-carousel.php';
		require __DIR__ . '/widgets/slider-synchronized-carousel.php';
		require __DIR__ . '/widgets/flip-box.php';
		require __DIR__ . '/widgets/animated-text.php';
		require __DIR__ . '/widgets/animated-headline.php';
		require __DIR__ . '/widgets/service-grid.php';
		require __DIR__ . '/widgets/service-carousel.php';
		require __DIR__ . '/widgets/testimonial-carousel.php';
		require __DIR__ . '/widgets/testimonial-slider.php';
		require __DIR__ . '/widgets/food-menu.php';
		require __DIR__ . '/widgets/portfolio-timeline.php';
		require __DIR__ . '/widgets/contact-form.php';
		require __DIR__ . '/widgets/search.php';
		require __DIR__ . '/widgets/background-menu-effect.php';
	}
	
	/**
	 * Register Category
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function register_category() {
		\Elementor\Plugin::instance()->elements_manager->add_category(
			'hoteller-theme-widgets-category-fullscreen',
			array(
				'title' => 'Theme Fullscreen Elements',
				'icon'  => 'fonts',
			),
			1
		);
		
		\Elementor\Plugin::instance()->elements_manager->add_category(
			'hoteller-theme-widgets-category',
			array(
				'title' => 'Theme General Elements',
				'icon'  => 'fonts',
			),
			2
		);
	}

	/**
	 * Register Widget
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function register_widget() {
		\Elementor\Plugin::instance()->widgets_manager->register( new Hoteller_Navigation_Menu() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Hoteller_Slider_Property_Clip() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Hoteller_Slider_Zoom() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Hoteller_Slider_Parallax() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Hoteller_Slider_Horizontal() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Hoteller_Accommodation_Types() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Hoteller_Accommodation_Carousel() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Hoteller_Booking_History() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Hoteller_Timeline() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Hoteller_Distortion_Grid() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Hoteller_Blog_Posts() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Hoteller_Gallery_Grid() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Hoteller_Gallery_Masonry() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Hoteller_Gallery_Justified() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Hoteller_Gallery_Horizontal() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Hoteller_Album_Grid() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Hoteller_Availability_Search() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Hoteller_Availability_Calendar() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Hoteller_Mouse_Drive_Vertical_Carousel() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Hoteller_Slider_Synchronized_Carousel() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Hoteller_Flip_Box() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Hoteller_Animated_Text() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Hoteller_Animated_Headline() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Hoteller_Service_Grid() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Hoteller_Service_Carousel() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Hoteller_Testimonial_Carousel() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Hoteller_Testimonial_Slider() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Hoteller_Food_Menu() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Hoteller_Portfolio_Timeline() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Hoteller_Contact_Form() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Hoteller_Search() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Hoteller_Background_Menu_Effect() );
	}
	
	/**
	 * Register Extension
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function register_extension() {
		//Custom Elementor extensions
		require __DIR__ . '/extensions.php';
		
		hoteller_ext()->init();
	}
}

new Hoteller_Elementor();
