<?php
namespace HotellerElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Blog Posts
 *
 * Elementor widget for blog posts
 *
 * @since 1.0.0
 */
class Hoteller_Blog_Posts extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'hoteller-blog-posts';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Blog Posts', 'hoteller-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-post-list';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'hoteller-theme-widgets-category' ];
	}

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [ 'tilt', 'sticky-kit', 'masonry', 'hoteller-elementor' ];
	}
	
	/**
	 * Retrieve blog post categories
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Blog categories
	 */
	public function get_blog_categories() {
		//Get all categories
		$categories_arr = get_categories( array(
			'orderby' => 'name',
			'order'   => 'ASC'
		) );
		$tg_categories_select = array();
		
		foreach ($categories_arr as $cat) {
			$tg_categories_select[$cat->term_id] = $cat->name;
		}

		return $tg_categories_select;
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'hoteller-elementor' ),
			]
		);

		$this->add_control(
			'layout',
			[
				'label' => __( 'Layout', 'hoteller-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'classic',
				'options' => [
					 'classic'  			=> __( 'Classic', 'hoteller-elementor' ),
					 'grid' 				=> __( 'Grid', 'hoteller-elementor' ),
					 'grid_no_space' 	=> __( 'Grid No Space', 'hoteller-elementor' ),
					 'masonry' 			=> __( 'Masonry', 'hoteller-elementor' ),
					 'list'   			=> __( 'List', 'hoteller-elementor' ),
					 'list_circle'   	=> __( 'List Circle', 'hoteller-elementor' ),
					 'metro'   			=> __( 'Metro', 'hoteller-elementor' ),
					 'metro_no_space'   	=> __( 'Metro No Space', 'hoteller-elementor' ),
				],
			]
		);
		
		$this->add_control(
			'posts_per_page',
			[
				'label' => __( 'Posts Per Page', 'hoteller-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 6,
				],
				'range' => [
					'px' => [
						'min' => -1,
						'max' => 100,
						'step' => 1,
					]
				],
			]
		);
		
		$this->add_control(
			'categories',
			[
				'label' => __( 'Categories', 'hoteller-elementor' ),
				'type' => Controls_Manager::SELECT2,
				'options' => $this->get_blog_categories(),
				'multiple' => true,
			]
		);
		
		$this->add_control(
			'show_categories',
			[
				'label' => __( 'Show Post Categories', 'hoteller-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Yes', 'hoteller-elementor' ),
				'label_off' => __( 'No', 'hoteller-elementor' ),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'show_date',
			[
				'label' => __( 'Show Post Date', 'hoteller-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Yes', 'hoteller-elementor' ),
				'label_off' => __( 'No', 'hoteller-elementor' ),
				'return_value' => 'yes',
			]
		);
		
		$this->add_control(
			'show_pagination',
			[
				'label' => __( 'Show Pagination', 'hoteller-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Yes', 'hoteller-elementor' ),
				'label_off' => __( 'No', 'hoteller-elementor' ),
				'return_value' => 'yes',
			]
		);
		
		$this->add_control(
			'text_display',
			[
				'label' => __( 'Text Display', 'hoteller-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'excerpt',
				'options' => [
					 'excerpt' => __( 'Excerpt', 'hoteller-elementor' ),
					 'full_content' => __( 'Full Content', 'hoteller-elementor' ),
					 'no_text' => __( 'No text', 'hoteller-elementor' ),
				],
			]
		);
		
		
		$this->add_control(
			'text_align',
			[
				'label' => __( 'Text Alignment', 'hoteller-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => __( 'Left', 'hoteller-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'hoteller-elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'hoteller-elementor' ),
						'icon' => 'fa fa-align-right',
					],
				],
			]
		);
		
		$this->add_control(
			'excerpt_length',
			[
				'label' => __( 'Excerpt Length', 'hoteller-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 100,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
						'step' => 1,
					]
				],
			]
		);
		
		$this->add_control(
			'strip_html',
			[
				'label' => __( 'Strip HTML from Post Content', 'hoteller-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Yes', 'hoteller-elementor' ),
				'label_off' => __( 'No', 'hoteller-elementor' ),
				'return_value' => 'yes',
			]
		);
		
		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
		include(HOTELLER_ELEMENTOR_PATH.'templates/blog-posts/index.php');
	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function content_template() {
		return '';
	}
}
