<?php
namespace HotellerElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Blog Posts
 *
 * Elementor widget for blog posts
 *
 * @since 1.0.0
 */
class Hoteller_Accommodation_Carousel extends Widget_Base {

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
		return 'hoteller-accommodation-carousel';
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
		return __( 'Accommodation Types Carousel', 'hoteller-elementor' );
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
		return 'eicon-slider-3d';
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
		return [ 'owl-carousel', 'hoteller-elementor' ];
	}
	
	/**
	 * Retrieve accommodation type post categories
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array accommodation type categories
	 */
	public function get_accommodation_categories() {

		$accommodation_cats_arr = get_terms('mphb_room_type_category', 'hide_empty=0&hierarchical=0&parent=0&orderby=name');
		$accommodation_cats_select = array();
		
		foreach ($accommodation_cats_arr as $cat) {
			$accommodation_cats_select[$cat->term_id] = $cat->name;
		}

		return $accommodation_cats_select;
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
			'image_dimension',
			[
				'label'       => esc_html__( 'Image Dimension', 'hoteller-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'hoteller-gallery-grid',
				'options' => [
					 'hoteller-gallery-grid' => __( 'Landscape', 'hoteller-elementor' ),
					 'hoteller-gallery-list' => __( 'Square', 'hoteller-elementor' ),
					 'hoteller-album-grid' => __( 'Portrait', 'hoteller-elementor' ),
					 'medium_large' => __( 'Original', 'hoteller-elementor' ),
				]
			]
		);
		
		$this->add_control(
			'categories',
			[
				'label' => __( 'Categories', 'hoteller-elementor' ),
				'type' => Controls_Manager::SELECT2,
			    'options' => $this->get_accommodation_categories(),
			    'multiple' => true,
			]
		);
		
		$this->add_control(
			'sort_by',
			[
				'label' => __( 'Sort By', 'hoteller-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 1,
			    'options' => [
			     	'menu_order' => __( 'Default WordPress Order', 'hoteller-elementor' ),
				 	'title' => __( 'Title', 'hoteller-elementor' ),
			    ],
			]
		);
		
		$this->add_control(
			'display_per_page',
			[
				'label' => __( 'Display Items', 'hoteller-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 2,
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
			'autoplay',
			[
				'label' => __( 'Auto Play', 'hoteller-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Yes', 'hoteller-elementor' ),
				'label_off' => __( 'No', 'hoteller-elementor' ),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
		    'timer',
		    [
		        'label' => __( 'Timer (in seconds)', 'hoteller-elementor' ),
		        'type' => Controls_Manager::SLIDER,
		        'default' => [
		            'size' => 8,
		        ],
		        'range' => [
		            'px' => [
		                'min' => 1,
		                'max' => 60,
		                'step' => 1,
		            ]
		        ],
		        'size_units' => [ 'px' ]
		    ]
		);
		
		$this->add_control(
			'pagination',
			[
				'label' => __( 'Show Pagination', 'hoteller-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Yes', 'hoteller-elementor' ),
				'label_off' => __( 'No', 'hoteller-elementor' ),
				'return_value' => 'yes',
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_content_style',
			array(
				'label'      => esc_html__( 'Content', 'hoteller-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __( 'Title Typography', 'hoteller-elementor' ),
				'selector' => '{{WRAPPER}} .accommodation-carousel-wrapper .accommodation-carousel-title h3',
			]
		);
		
		$this->add_control(
		    'title_color',
		    [
		        'label' => __( 'Title Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#000000',
		        'selectors' => [
		            '{{WRAPPER}} .accommodation-carousel-wrapper .accommodation-carousel-title h3 a' => 'color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'attr_typography',
				'label' => __( 'Attributes Typography', 'hoteller-elementor' ),
				'selector' => '{{WRAPPER}} .accommodation-carousel-wrapper .accommodation-carousel-title .accommodation-carousel-attr-wrapper',
			]
		);
		
		$this->add_control(
		    'attr_color',
		    [
		        'label' => __( 'Attributes Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#000000',
		        'selectors' => [
		            '{{WRAPPER}} .accommodation-carousel-wrapper .accommodation-carousel-title .accommodation-carousel-attr-wrapper' => 'color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->end_controls_section();

		$this->start_controls_section(
			'section_pricing_style',
			array(
				'label'      => esc_html__( 'Pricing', 'hoteller-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'from_typography',
				'label' => __( 'Pricing From Typography', 'hoteller-elementor' ),
				'selector' => '{{WRAPPER}} .accommodation-carousel-wrapper .accommodation-carousel-price .accommodation-carousel-price-from',
			]
		);
		
		$this->add_control(
		    'from_color',
		    [
		        'label' => __( 'Pricing From Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#000000',
		        'selectors' => [
		            '{{WRAPPER}} .accommodation-carousel-wrapper .accommodation-carousel-price .accommodation-carousel-price-from' => 'color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'pricing_typography',
				'label' => __( 'Pricing Typography', 'hoteller-elementor' ),
				'selector' => '{{WRAPPER}} .accommodation-carousel-wrapper .accommodation-carousel-price .mphb-price',
			]
		);
		
		$this->add_control(
		    'pricing_color',
		    [
		        'label' => __( 'Pricing Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#000000',
		        'selectors' => [
		            '{{WRAPPER}} .accommodation-carousel-wrapper .accommodation-carousel-price .mphb-price' => 'color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_pagination_style',
			array(
				'label'      => esc_html__( 'Pagination', 'hoteller-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);
		
		$this->add_control(
		    'pagination_color',
		    [
		        'label' => __( 'Pagination Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#cccccc',
		        'selectors' => [
		            '{{WRAPPER}} .accommodation-carousel-wrapper .owl-carousel .owl-dots .owl-dot span' => 'background: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_control(
		    'pagination_active_color',
		    [
		        'label' => __( 'Pagination Active Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#000000',
		        'selectors' => [
		            '{{WRAPPER}} .accommodation-carousel-wrapper .owl-carousel .owl-dots .owl-dot.active span' => 'background: {{VALUE}}',
		        ],
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
		include(HOTELLER_ELEMENTOR_PATH.'templates/accommodation-carousel/index.php');
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
