<?php
namespace HotellerElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Animated Headline
 *
 * Elementor widget for animated headline
 *
 * @since 1.0.0
 */
class Hoteller_Animated_Headline extends Widget_Base {

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
		return 'hoteller-animated-headline';
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
		return __( 'Animated Headline', 'hoteller-elementor' );
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
		return [ 'animatedheadline', 'hoteller-elementor' ];
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
			'section_headline',
			[
				'label' => __( 'Headline', 'hoteller-elementor' ),
			]
		);
		
		$this->add_control(
			'headline_animation',
			[
				'label' => __( 'Animation', 'hoteller-elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'type',
				'options' => [
					'rotate-1'  => __( 'Rotate', 'hoteller-elementor' ),
					'rotate-2'  => __( 'Rotate 2', 'hoteller-elementor' ),
					'rotate-3'  => __( 'Rotate 3', 'hoteller-elementor' ),
					'type'  => __( 'Type', 'hoteller-elementor' ),
					'loading-bar'  => __( 'Loading Bar', 'hoteller-elementor' ),
					'slide'  => __( 'Slide', 'hoteller-elementor' ),
					'clip'  => __( 'Clip', 'hoteller-elementor' ),
					'zoom'  => __( 'Zoom', 'hoteller-elementor' ),
					'scale'  => __( 'Scale', 'hoteller-elementor' ),
					'push'  => __( 'Push', 'hoteller-elementor' ),
				],
			]
		);
		
		$this->add_control(
			'headline_before',
			[
				'label' => __( 'Before Animated Text', 'hoteller-elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
			]
		);
		
		/**
		*
		*	Begin animated text repeat list
		*
		**/
		
		$repeater = new \Elementor\Repeater();
		
		$repeater->add_control(
			'headline_animated_text', [
				'label' => __( 'Text', 'artale-elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
			]
		);
		
		$this->add_control(
			'headline_animated',
			[
				'label' => __( 'Animated Text', 'artale-elementor' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ headline_animated_text }}}',
			]
		);
		
		/**
		*
		*	End animated text repeat list
		*
		**/
		
		$this->add_control(
			'headline_after',
			[
				'label' => __( 'After Animated Text', 'hoteller-elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'condition' => [
					'headline_animation' => array('type' , 'clip'),
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
		            '{{WRAPPER}} .themegoods-animated-headline' => 'text-align: {{VALUE}}',
		        ],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_headline_style',
			array(
				'label'      => esc_html__( 'Headline', 'hoteller-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);
		
		$this->add_control(
		    'headline_font_color',
		    [
		        'label' => __( 'Text Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#000000',
		        'selectors' => [
		            '{{WRAPPER}} .themegoods-animated-headline h1' => 'color: {{VALUE}}',
		            '{{WRAPPER}} .themegoods-animated-headline h2' => 'color: {{VALUE}}',
		            '{{WRAPPER}} .themegoods-animated-headline h3' => 'color: {{VALUE}}',
		            '{{WRAPPER}} .themegoods-animated-headline h4' => 'color: {{VALUE}}',
		            '{{WRAPPER}} .themegoods-animated-headline h5' => 'color: {{VALUE}}',
		            '{{WRAPPER}} .themegoods-animated-headline h6' => 'color: {{VALUE}}',
		            '{{WRAPPER}} .themegoods-animated-headline div' => 'color: {{VALUE}}',
		            '{{WRAPPER}} .themegoods-animated-headline span' => 'color: {{VALUE}}',
		            '{{WRAPPER}} .themegoods-animated-headline p' => 'color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'headline_typography',
				'label' => __( 'Typography', 'hoteller-elementor' ),
				'selector' => '{{WRAPPER}} .themegoods-animated-headline h1, {{WRAPPER}} .themegoods-animated-headline h2, {{WRAPPER}} .themegoods-animated-headline h3, {{WRAPPER}} .themegoods-animated-headline h4, {{WRAPPER}} .themegoods-animated-headline h5, {{WRAPPER}} .themegoods-animated-headline h6, {{WRAPPER}} .themegoods-animated-headline div, {{WRAPPER}} .themegoods-animated-headline span, {{WRAPPER}} .themegoods-animated-headline p',
			]
		);
		
		$this->add_control(
		    'headline_animated_font_color',
		    [
		        'label' => __( 'Animated Text Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#000000',
		        'selectors' => [
		            '{{WRAPPER}} .themegoods-animated-headline span.ah-words-wrapper' => 'color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'headline_animated_typography',
				'label' => __( 'Animated Text Typography', 'hoteller-elementor' ),
				'selector' => '{{WRAPPER}} .themegoods-animated-headline span.ah-words-wrapper b',
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
		include(HOTELLER_ELEMENTOR_PATH.'templates/animated-headline/index.php');
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
