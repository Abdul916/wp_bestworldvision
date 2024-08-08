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
class Hoteller_Flip_Box extends Widget_Base {

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
		return 'hoteller-flip-box';
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
		return __( 'Flip Box', 'hoteller-elementor' );
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
		return 'eicon-flip-box';
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
		
		$this->add_control(
		    'default_image',
		    [
		        'label' => __( 'Default Background Image', 'hoteller-elementor' ),
		        'type' => Controls_Manager::MEDIA,
		    ]
		);
		
		$this->add_control(
		    'default_title',
		    [
		        'label' => __( 'Default Title', 'hoteller-elementor' ),
		        'type' => Controls_Manager::TEXT,
		    ]
		);
		
		$this->add_control(
		    'default_description',
		    [
		        'label' => __( 'Default Description', 'hoteller-elementor' ),
		        'type' => Controls_Manager::TEXTAREA,
		    ]
		);
		
		$this->add_control(
		    'flip_image',
		    [
		        'label' => __( 'Flip Background Image', 'hoteller-elementor' ),
		        'type' => Controls_Manager::MEDIA,
		    ]
		);
		
		$this->add_control(
		    'flip_title',
		    [
		        'label' => __( 'Flip Title', 'hoteller-elementor' ),
		        'type' => Controls_Manager::TEXT,
		    ]
		);
		
		$this->add_control(
		    'flip_button_title',
		    [
		        'label' => __( 'Flip Button Title', 'hoteller-elementor' ),
		        'type' => Controls_Manager::TEXT,
		    ]
		);
		
		$this->add_control(
		    'flip_button_link',
		    [
		        'label' => __( 'Flip Button Link', 'hoteller-elementor' ),
		        'type' => Controls_Manager::URL,
		        'default' => [
				    'url' => '',
				    'is_external' => '',
				 ],
				'show_external' => true,
		    ]
		);
		
		$this->add_responsive_control(
		    'height',
		    [
		        'label' => __( 'Height (in px)', 'hoteller-elementor' ),
		        'type' => Controls_Manager::SLIDER,
		        'default' => [
		            'size' => 400,
		        ],
		        'range' => [
		            'px' => [
		                'min' => 5,
		                'max' => 1500,
		                'step' => 5,
		            ]
		        ],
		        'size_units' => [ 'px' ],
		        'selectors' => [
		            '{{WRAPPER}} .tg_flip_box_wrapper.square-flip' => 'height: {{SIZE}}{{UNIT}}',
		        ],
		    ]
		);
		
		$this->add_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'hoteller-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tg_flip_box_wrapper.square-flip .square' => 'border-radius: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tg_flip_box_wrapper.square-flip .square2' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
		    'content_bg_color',
		    [
		        'label' => __( 'Overlay Background Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => 'rgba(0,0,0,0.2);',
		        'selectors' => [
		            '{{WRAPPER}} .tg_flip_box_wrapper.square-flip .flip-overlay' => 'background: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_default_content_style',
			array(
				'label'      => esc_html__( 'Default Content', 'hoteller-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);
		
		$this->add_control(
		    'default_title_color',
		    [
		        'label' => __( 'Default Title Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#ffffff',
		        'selectors' => [
		            '{{WRAPPER}} .tg_flip_box_wrapper.square-flip .square .square-container h2' => 'color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'default_title_typography',
				'label' => __( 'Default Title Typography', 'hoteller-elementor' ),
				'selector' => '{{WRAPPER}} .tg_flip_box_wrapper.square-flip .square .square-container h2',
			]
		);
		
		$this->add_control(
		    'default_description_color',
		    [
		        'label' => __( 'Default Description Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#ffffff',
		        'selectors' => [
		            '{{WRAPPER}} .tg_flip_box_wrapper.square-flip .square .square-container .square-desc' => 'color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'default_description_typography',
				'label' => __( 'Default Description Typography', 'hoteller-elementor' ),
				'selector' => '{{WRAPPER}} .tg_flip_box_wrapper.square-flip .square .square-container .square-desc',
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_flip_content_style',
			array(
				'label'      => esc_html__( 'Flip Content', 'hoteller-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);
		
		$this->add_control(
		    'flip_title_color',
		    [
		        'label' => __( 'Flip Title Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#ffffff',
		        'selectors' => [
		            '{{WRAPPER}} .tg_flip_box_wrapper.square-flip .square2 .square-container2 h2' => 'color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'flip_title_typography',
				'label' => __( 'Flip Title Typography', 'hoteller-elementor' ),
				'selector' => '{{WRAPPER}} .tg_flip_box_wrapper.square-flip .square2 .square-container2 h2',
			]
		);
		
		$this->add_control(
		    'button_bg_color',
		    [
		        'label' => __( 'Flip Button Background Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#0067DA',
		        'selectors' => [
		            '{{WRAPPER}} .tg_flip_box_wrapper.square-flip .square2 .square-container2 .button' => 'border-color: {{VALUE}}',
		            '{{WRAPPER}} .tg_flip_box_wrapper.square-flip .square2 .square-container2 a.button' => 'background-color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_control(
		    'button_font_color',
		    [
		        'label' => __( 'Flip Button Font Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#ffffff',
		        'selectors' => [
		            '{{WRAPPER}} .tg_flip_box_wrapper.square-flip .square2 .square-container2 .button' => 'color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_control(
		    'button_hover_bg_color',
		    [
		        'label' => __( 'Flip Button Hover Background Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#ffffff',
		        'selectors' => [
		            '{{WRAPPER}} .tg_flip_box_wrapper.square-flip .square2 .square-container2 .button:hover' => 'border-color: {{VALUE}}',
		            '{{WRAPPER}} .tg_flip_box_wrapper.square-flip .square2 .square-container2 a.button:hover' => 'background-color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_control(
		    'button_hover_font_color',
		    [
		        'label' => __( 'Flip Button Hover Font Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#222222',
		        'selectors' => [
		            '{{WRAPPER}} .tg_flip_box_wrapper.square-flip .square2 .square-container2 .button:hover' => 'color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'label' => __( 'Button Typography', 'hoteller-elementor' ),
				'selector' => '{{WRAPPER}} .tg_flip_box_wrapper.square-flip .square-container2 .button',
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
		include(HOTELLER_ELEMENTOR_PATH.'templates/flip-box/index.php');
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
