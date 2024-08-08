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
class Hoteller_Slider_Property_Clip extends Widget_Base {

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
		return 'hoteller-slider-property-clip';
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
		return __( 'Property Clip Slider', 'hoteller-elementor' );
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
		return 'eicon-post-excerpt';
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
		return [ 'hoteller-elementor' ];
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
				'label' => __( 'Image Size', 'hoteller-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'large',
			    'options' => [
			     	'medium_large' => __( 'Medium (default 768px x 768px max)', 'hoteller-elementor' ),
			     	'large' => __( 'Large (default 1024px x 1024px max)', 'hoteller-elementor' ),
			     	'full' => __( 'Original image resolution', 'hoteller-elementor' ),
			    ],
			]
		);
		
		$this->add_control(
			'image_align',
			[
				'label' => __( 'Image Alignment', 'hoteller-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
			    'options' => [
			     	'left' => __( 'Left', 'hoteller-elementor' ),
			     	'right' => __( 'Right', 'hoteller-elementor' ),
			    ],
			]
		);
		
		$this->add_responsive_control(
		    'width',
		    [
		        'label' => __( 'Width', 'hoteller-elementor' ),
		        'type' => Controls_Manager::SLIDER,
		        'default' => [
		            'size' => 100,
		            'unit' => '%',
		        ],
		        'range' => [
		            'px' => [
		                'min' => 5,
		                'max' => 2000,
		                'step' => 5,
		            ],
				            '%' => [
		                'min' => 0,
		                'max' => 100,
		            ],
		        ],
		        'size_units' => [ 'px', '%' ],
		        'selectors' => [
		            '{{WRAPPER}} .tg_slider_property_clip_wrapper' => 'width: {{SIZE}}{{UNIT}}',
		        ],
		    ]
		);
		
		$this->add_responsive_control(
		    'height',
		    [
		        'label' => __( 'Height', 'hoteller-elementor' ),
		        'type' => Controls_Manager::SLIDER,
		        'default' => [
		            'size' => 500,
		            'unit' => 'px',
		        ],
		        'range' => [
		            'px' => [
		                'min' => 5,
		                'max' => 2000,
		                'step' => 5,
		            ],
				            '%' => [
		                'min' => 0,
		                'max' => 100,
		            ],
		        ],
		        'size_units' => [ 'px', '%' ],
		        'selectors' => [
		            '{{WRAPPER}} .tg_slider_property_clip_wrapper' => 'height: {{SIZE}}{{UNIT}}',
		        ],
		    ]
		);
		
		$this->add_responsive_control(
		    'content_padding',
		    [
		        'label' => __( 'Content Padding', 'hoteller-elementor' ),
		        'type' => Controls_Manager::SLIDER,
		        'default' => [
		            'size' => 80,
		            'unit' => 'px',
		        ],
		        'range' => [
		            'px' => [
		                'min' => 0,
		                'max' => 2000,
		                'step' => 5,
		            ],
				            '%' => [
		                'min' => 0,
		                'max' => 100,
		            ],
		        ],
		        'size_units' => [ 'px', '%' ],
		        'selectors' => [
		            '{{WRAPPER}} .tg_slider_property_clip_wrapper.intro .content' => 'padding: {{SIZE}}{{UNIT}}',
		        ],
		    ]
		);
		
		$this->add_control(
		  'title',
		  [
		     'label'       => __( 'Title', 'hoteller-elementor' ),
		     'type'        => Controls_Manager::TEXT,
		     'placeholder' => __( 'Title', 'hoteller-elementor' ),
		  ]
		);
		
		$this->add_control(
		  'subtitle',
		  [
		     'label'       => __( 'Sub Title', 'hoteller-elementor' ),
		     'type'        => Controls_Manager::TEXT,
		     'placeholder' => __( 'Sub Title', 'hoteller-elementor' ),
		  ]
		);
		
		$this->add_control(
		  'description',
		  [
		     'label'   => __( 'Description', 'hoteller-elementor' ),
		     'type'    => Controls_Manager::WYSIWYG,
		     'default' => __( 'Default description', 'hoteller-elementor' ),
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
		    'content_background',
		    [
		        'label' => __( 'Content Background', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#f0f0f0',
		        'selectors' => [
		            '{{WRAPPER}} .tg_slider_property_clip_wrapper.intro .content' => 'background: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_control(
		    'link_color',
		    [
		        'label' => __( 'Link Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#222222',
		        'selectors' => [
		            '{{WRAPPER}} .tg_slider_property_clip_wrapper.intro .content > div a' => 'color: {{VALUE}}',
		            '{{WRAPPER}} .tg_slider_property_clip_wrapper.intro div.content a' => 'border-color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_control(
		    'pagination_color',
		    [
		        'label' => __( 'Pagination Background Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#ffffff',
		        'selectors' => [
		            '{{WRAPPER}} .tg_slider_property_clip_wrapper.intro nav a' => 'background: {{VALUE}}',
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
		        'default' => '#222222',
		        'selectors' => [
		            '{{WRAPPER}} .tg_slider_property_clip_wrapper.intro .content > div h1' => 'color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __( 'Title Typography', 'hoteller-elementor' ),
				'selector' => '{{WRAPPER}} .tg_slider_property_clip_wrapper .content div h1',
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_subtitle_style',
			array(
				'label'      => esc_html__( 'Sub Title', 'hoteller-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);
		
		$this->add_control(
		    'subtitle_color',
		    [
		        'label' => __( 'Sub Title Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#222222',
		        'selectors' => [
		            '{{WRAPPER}} .tg_slider_property_clip_wrapper.intro .content span' => 'color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'subtitle_typography',
				'label' => __( 'Sub Title Typography', 'hoteller-elementor' ),
				'selector' => '{{WRAPPER}} .tg_slider_property_clip_wrapper .content span.subtitle',
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_description_style',
			array(
				'label'      => esc_html__( 'Description Title', 'hoteller-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);
		
		$this->add_control(
		    'description_color',
		    [
		        'label' => __( 'Description Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#222222',
		        'selectors' => [
		            '{{WRAPPER}} .tg_slider_property_clip_wrapper.intro .content > div p' => 'color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'label' => __( 'Description Typography', 'hoteller-elementor' ),
				'selector' => '{{WRAPPER}} .tg_slider_property_clip_wrapper .content > div p',
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
		include(HOTELLER_ELEMENTOR_PATH.'templates/slider-property-clip/index.php');
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
