<?php
namespace HotellerElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Blog Posts
 *
 * Elementor widget
 *
 * @since 1.0.0
 */
class Hoteller_Slider_Parallax extends Widget_Base {

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
		return 'hoteller-slider-parallax';
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
		return __( 'Parallax Slider', 'hoteller-elementor' );
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
		return 'eicon-post-slider';
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
		return [ 'tweenmax', 'hoteller-elementor' ];
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
		
		/**
		*
		*	Begin slides repeat list
		*
		**/
		
		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'slide_image', [
				'label' => __( 'Image', 'hoteller-elementor' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'slide_title', [
				'label' => __( 'Title', 'hoteller-elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
			]
		);
		
		$repeater->add_control(
			'slide_description', [
				'label' => __( 'Description', 'hoteller-elementor' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'label_block' => true,
			]
		);
		
		$repeater->add_control(
			'slide_link_title', [
				'label' => __( 'Link Title', 'hoteller-elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
			]
		);
		
		$repeater->add_control(
			'slide_link', [
				'label' => __( 'Link URL', 'hoteller-elementor' ),
				'type' => \Elementor\Controls_Manager::URL,
				'show_external' => true,
			]
		);
		
		$this->add_control(
			'slides',
			[
				'label' => __( 'Slides', 'hoteller-elementor' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ slide_title }}}',
			]
		);
		
		/**
		*
		*	End slides repeat list
		*
		**/
		
		$this->add_responsive_control(
		    'height',
		    [
		        'label' => __( 'Height', 'hoteller-elementor' ),
		        'type' => Controls_Manager::SLIDER,
		        'default' => [
		            'size' => 900,
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
		        'size_units' => [ 'px', 'vh' ],
		        'selectors' => [
		            '{{WRAPPER}} .slider_parallax_wrapper' => 'height: {{SIZE}}{{UNIT}}',
		        ],
		    ]
		);
		
		$this->add_control(
			'autoplay',
			[
				'label' => __( 'Auto Play', 'hoteller-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
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
			'navigation',
			[
				'label' => __( 'Show Navigation', 'hoteller-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Yes', 'hoteller-elementor' ),
				'label_off' => __( 'No', 'hoteller-elementor' ),
				'return_value' => 'yes',
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
		        'default' => 'rgba(0,0,0,0.3)',
		        'selectors' => [
		            '{{WRAPPER}} .slider_parallax_wrapper .slide .image-container::before' => 'background: {{VALUE}}',
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
		            '{{WRAPPER}} .slider_parallax_wrapper .pagination .item::before' => 'background: {{VALUE}}',
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
		
		$this->add_responsive_control(
		    'title_width',
		    [
		        'label' => __( 'Title Width', 'hoteller-elementor' ),
		        'type' => Controls_Manager::SLIDER,
		        'default' => [
		            'size' => 50,
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
		            '{{WRAPPER}} .slider_parallax_wrapper .slide .caption' => 'width: {{SIZE}}{{UNIT}};',
		        ],
		    ]
		);
		
		$this->add_control(
		    'title_color',
		    [
		        'label' => __( 'Title Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#ffffff',
		        'selectors' => [
		            '{{WRAPPER}} .slider_parallax_wrapper .slide .title h2' => 'color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __( 'Title Typography', 'hoteller-elementor' ),
				'selector' => '{{WRAPPER}} .slider_parallax_wrapper .slider_parallax_inner .slider_parallax_slides .slide .caption .title h2',
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
		        'default' => '#ffffff',
		        'selectors' => [
		            '{{WRAPPER}} .slider_parallax_wrapper .slide .text' => 'color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'label' => __( 'Description Typography', 'hoteller-elementor' ),
				'selector' => '{{WRAPPER}} .slider_parallax_wrapper .slide div.caption div.text',
			]
		);
		
		$this->add_responsive_control(
		    'description_margin_top',
		    [
		        'label' => __( 'Description Margin Top', 'hoteller-elementor' ),
		        'type' => Controls_Manager::SLIDER,
		        'default' => [
		            'size' => 10,
		        ],
		        'range' => [
		            'px' => [
		                'min' => 0,
		                'max' => 300,
		                'step' => 5,
		            ],
		        ],
		        'size_units' => [ 'px' ],
		        'selectors' => [
		            '{{WRAPPER}} .slider_parallax_wrapper .slide .text' => 'margin-top: {{SIZE}}{{UNIT}};',
		        ],
		    ]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_button_style',
			array(
				'label'      => esc_html__( 'Button', 'hoteller-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);
		
		$this->add_control(
		    'button_bg_color',
		    [
		        'label' => __( 'Button Background Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#222222',
		        'selectors' => [
		            '{{WRAPPER}} .slider_parallax_wrapper .slide .button' => 'border-color: {{VALUE}}',
		            '{{WRAPPER}} .slider_parallax_wrapper .slide a.button' => 'background-color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_control(
		    'button_font_color',
		    [
		        'label' => __( 'Button Font Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#ffffff',
		        'selectors' => [
		            '{{WRAPPER}} .slider_parallax_wrapper div.slide a.button' => 'color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_control(
		    'button_hover_bg_color',
		    [
		        'label' => __( 'Button Hover Background Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#555555',
		        'selectors' => [
		            '{{WRAPPER}} .slider_parallax_wrapper .slide .button:hover' => 'border-color: {{VALUE}}',
		            '{{WRAPPER}} .slider_parallax_wrapper .slide a.button:hover' => 'background-color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_control(
		    'button_hover_font_color',
		    [
		        'label' => __( 'Button Hover Font Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#ffffff',
		        'selectors' => [
		            '{{WRAPPER}} .slider_parallax_wrapper div.slide a.button:hover' => 'color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'label' => __( 'Button Typography', 'hoteller-elementor' ),
				'selector' => '{{WRAPPER}} .slider_parallax_wrapper div.slide a.button',
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
		include(HOTELLER_ELEMENTOR_PATH.'templates/slider-parallax/index.php');
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
