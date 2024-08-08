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
class Hoteller_Gallery_Justified extends Widget_Base {

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
		return 'hoteller-gallery-justified';
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
		return __( 'Justified Gallery', 'hoteller-elementor' );
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
		return 'eicon-gallery-justified';
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
		return [ 'tilt', 'justifiedGallery', 'hoteller-elementor' ];
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
			'gallery',
			  [
			    'label' => __( 'Add Images', 'hoteller-elementor' ),
				'type' => Controls_Manager::GALLERY,
				'default' => [],
			]
		);
		
		$this->add_control(
			'image_size',
			[
				'label' => __( 'Image Size', 'starto-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'medium_large',
			    'options' => [
			     	'medium' => __( 'Small (default 300px x 300px max)', 'starto-elementor' ),
			     	'medium_large' => __( 'Medium (default 768px x 768px max)', 'starto-elementor' ),
			     	'large' => __( 'Large (default 1024px x 1024px max)', 'starto-elementor' ),
			     	'full' => __( 'Original image resolution', 'starto-elementor' ),
			    ],
			]
		);

		$this->add_control(
		    'row_height',
		    [
		        'label' => __( 'Row Height', 'hoteller-elementor' ),
		        'type' => Controls_Manager::SLIDER,
		        'default' => [
		            'size' => 150,
		        ],
		        'range' => [
		            'px' => [
		                'min' => 20,
		                'max' => 300,
		                'step' => 5,
		            ]
		        ],
		        'size_units' => [ 'px' ]
		    ]
		);
		
		$this->add_control(
		    'margin',
		    [
		        'label' => __( 'Margin', 'hoteller-elementor' ),
		        'type' => Controls_Manager::SLIDER,
		        'default' => [
		            'size' => 5,
		        ],
		        'range' => [
		            'px' => [
		                'min' => 0,
		                'max' => 80,
		                'step' => 1,
		            ]
		        ],
		        'size_units' => [ 'px' ]
		    ]
		);
		
		$this->add_control(
			'justify_last_row',
			[
				'label' => __( 'Justify Last Row', 'hoteller-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Yes', 'hoteller-elementor' ),
				'label_off' => __( 'No', 'hoteller-elementor' ),
				'return_value' => 'yes',
			]
		);
		
		$this->add_control(
			'hover_effect',
			[
				'label' => __( 'Hover Effect', 'hoteller-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Yes', 'hoteller-elementor' ),
				'label_off' => __( 'No', 'hoteller-elementor' ),
				'return_value' => 'yes',
			]
		);
		
		$this->add_control(
			'show_title',
			[
				'label' => __( 'Show Title on Hover', 'hoteller-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Yes', 'hoteller-elementor' ),
				'label_off' => __( 'No', 'hoteller-elementor' ),
				'return_value' => 'yes',
			]
		);
		
		$this->add_control(
			'lightbox',
			[
				'label' => __( 'Image Lightbox', 'hoteller-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Yes', 'hoteller-elementor' ),
				'label_off' => __( 'No', 'hoteller-elementor' ),
				'return_value' => 'yes',
			]
		);
		
		$this->add_control(
			'lightbox_content',
			[
				'label' => __( 'Lightbox Content', 'hoteller-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'title',
			    'options' => [
			     	'title' => __( 'Title', 'hoteller-elementor' ),
			     	'title_caption' => __( 'Title and Caption', 'hoteller-elementor' ),
			     	'none' 	=> __( 'None', 'hoteller-elementor' ),
			    ],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_general_style',
			array(
				'label'      => esc_html__( 'General', 'hoteller-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);
		
		$this->add_control(
		    'background_overlay',
		    [
		        'label' => __( 'Background Overlay', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => 'rgba(0,0,0,0.2)',
		        'selectors' => [
		            '{{WRAPPER}} .gallery_grid_content_wrapper .gallery_grid_item:hover .bg_overlay' => 'background: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_title_style',
			array(
				'label'      => esc_html__( 'Title', 'hoteller-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);
		
		$this->add_control(
		    'title_color',
		    [
		        'label' => __( 'Title Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#ffffff',
		        'selectors' => [
		            '{{WRAPPER}} .gallery_grid_content_wrapper .gallery_grid_item:hover .tg_gallery_grid_title' => 'color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __( 'Title Typography', 'hoteller-elementor' ),
				'selector' => '{{WRAPPER}} .gallery_grid_content_wrapper .gallery_grid_item a.tg_gallery_lightbox .tg_gallery_grid_title',
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
		include(HOTELLER_ELEMENTOR_PATH.'templates/gallery-justified/index.php');
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
