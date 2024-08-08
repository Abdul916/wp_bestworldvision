<?php
namespace HotellerElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Animated Text
 *
 * Elementor widget for animated text
 *
 * @since 1.0.0
 */
class Hoteller_Animated_Text extends Widget_Base {

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
		return 'hoteller-animated-text';
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
		return __( 'Animated Text', 'hoteller-elementor' );
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
		return 'eicon-t-letter';
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
			'section_text',
			[
				'label' => __( 'Text', 'hoteller-elementor' ),
			]
		);
		
		$this->add_control(
			'title_content',
			[
				'label' => __( 'Title', 'hoteller-elementor' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 5,
				'default' => '',
				'placeholder' => __( 'Type your title here', 'hoteller-elementor' ),
			]
		);
		
		$this->add_control(
			'title_link',
			[
				'label' => __( 'Link', 'hoteller-elementor' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'Paste URL or type', 'hoteller-elementor' ),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
				],
			]
		);
		
		$this->add_control(
			'title_html_tag',
			[
				'label' => __( 'HTML Tag', 'hoteller-elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'h1',
				'options' => [
					'h1'  => __( 'H1', 'hoteller-elementor' ),
					'h2'  => __( 'H2', 'hoteller-elementor' ),
					'h3'  => __( 'H3', 'hoteller-elementor' ),
					'h4'  => __( 'H4', 'hoteller-elementor' ),
					'h5'  => __( 'H5', 'hoteller-elementor' ),
					'h6'  => __( 'H6', 'hoteller-elementor' ),
					'div'  => __( 'div', 'hoteller-elementor' ),
					'span'  => __( 'span', 'hoteller-elementor' ),
					'p'  => __( 'p', 'hoteller-elementor' ),
				],
			]
		);
		
		$this->add_control(
			'title_delimiter_type',
			[
				'label' => __( 'Delimiter Type', 'hoteller-elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'word',
				'options' => [
					'character'  => __( 'Character', 'hoteller-elementor' ),
					'word'  => __( 'Word', 'hoteller-elementor' ),
					'sentence'  => __( 'Sentence', 'hoteller-elementor' ),
				],
			]
		);
		
		$this->add_responsive_control(
			'title_alignment',
			[
				'label' => __( 'Alignment', 'hoteller-elementor' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'hoteller-elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'hoteller-elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'hoteller-elementor' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'left',
				'toggle' => true,
		        'selectors' => [
		            '{{WRAPPER}} .themegoods-animated-text' => 'text-align: {{VALUE}}',
		        ],
			]
		);
		
		$this->add_control(
		    'title_transition_speed',
		    [
		        'label' => __( 'Transition Speed (in milli-seconds)', 'hoteller-elementor' ),
		        'type' => Controls_Manager::SLIDER,
		        'default' => [
		            'size' => 100,
		        ],
		        'range' => [
		            'px' => [
		                'min' => 100,
		                'max' => 2000,
		                'step' => 100,
		            ]
		        ],
		        'size_units' => [ 'px' ]
		    ]
		);
		
		$this->add_control(
		    'title_transition_duration',
		    [
		        'label' => __( 'Transition Duration (in milli-seconds)', 'hoteller-elementor' ),
		        'type' => Controls_Manager::SLIDER,
		        'default' => [
		            'size' => 800,
		        ],
		        'range' => [
		            'px' => [
		                'min' => 100,
		                'max' => 10000,
		                'step' => 100,
		            ]
		        ],
		        'size_units' => [ 'px' ]
		    ]
		);
		
		$this->add_control(
			'title_transition_from',
			[
				'label' => __( 'Transition from', 'hoteller-elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'right',
				'options' => [
					'left'  => __( 'Left', 'hoteller-elementor' ),
					'right'  => __( 'Right', 'hoteller-elementor' ),
					'top'  => __( 'Top', 'hoteller-elementor' ),
					'bottom'  => __( 'Bottom', 'hoteller-elementor' ),
					'zoomin'  => __( 'Zoom In', 'hoteller-elementor' ),
					'zoomout'  => __( 'Zoom Out', 'hoteller-elementor' ),
				],
			]
		);
		
		$this->add_control(
			'title_transition_overflow',
			[
				'label' => __( 'Transition Overflow', 'hoteller-elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'visible',
				'options' => [
					'visible'  => __( 'Visible', 'hoteller-elementor' ),
					'hidden'  => __( 'Hidden', 'hoteller-elementor' ),
				],
			]
		);
		
		$this->add_control(
			'title_transition_delay',
			[
				'label' => __( 'Animation Delay (ms)', 'hoteller-elementor' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 5000,
				'step' => 5,
				'default' => 0,
				'frontend_available' => false,
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
		    'title_font_color',
		    [
		        'label' => __( 'Text Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#000000',
		        'selectors' => [
		            '{{WRAPPER}} .themegoods-animated-text h1' => 'color: {{VALUE}}',
		            '{{WRAPPER}} .themegoods-animated-text h2' => 'color: {{VALUE}}',
		            '{{WRAPPER}} .themegoods-animated-text h3' => 'color: {{VALUE}}',
		            '{{WRAPPER}} .themegoods-animated-text h4' => 'color: {{VALUE}}',
		            '{{WRAPPER}} .themegoods-animated-text h5' => 'color: {{VALUE}}',
		            '{{WRAPPER}} .themegoods-animated-text h6' => 'color: {{VALUE}}',
		            '{{WRAPPER}} .themegoods-animated-text div' => 'color: {{VALUE}}',
		            '{{WRAPPER}} .themegoods-animated-text span' => 'color: {{VALUE}}',
		            '{{WRAPPER}} .themegoods-animated-text p' => 'color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __( 'Typography', 'hoteller-elementor' ),
				'selector' => '{{WRAPPER}} .themegoods-animated-text h1, {{WRAPPER}} .themegoods-animated-text h2, {{WRAPPER}} .themegoods-animated-text h3, {{WRAPPER}} .themegoods-animated-text h4, {{WRAPPER}} .themegoods-animated-text h5, {{WRAPPER}} .themegoods-animated-text h6, {{WRAPPER}} .themegoods-animated-text div, {{WRAPPER}} .themegoods-animated-text span, {{WRAPPER}} .themegoods-animated-text p',
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
		include(HOTELLER_ELEMENTOR_PATH.'templates/animated-text/index.php');
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
