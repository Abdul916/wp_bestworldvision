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
class Hoteller_Contact_Form extends Widget_Base {

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
		return 'hoteller-contact-form';
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
		return __( 'Contact Form 7', 'hoteller-elementor' );
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
		return 'eicon-form-horizontal';
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
			'form_id',
			[
				'label' => __( 'Contact Form', 'hoteller-elementor' ),
				'type' => Controls_Manager::SELECT2,
				'options' => hoteller_get_contact_forms(),
				'multiple' => false,
			]
		);
		
		$this->add_control(
			'form_layout',
			[
				'label' => __( 'Layout', 'grandrestaurant-elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'fullwidth',
				'options' => [
					'fullwidth'  => __( 'Fullwidth', 'grandrestaurant-elementor' ),
					'two_cols' => __( '2 Columns', 'grandrestaurant-elementor' ),
					'three_cols' => __( '3 Columns', 'grandrestaurant-elementor' ),
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_label_style',
			array(
				'label'      => esc_html__( 'Label', 'hoteller-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);
		
		$this->add_control(
			'label_font_color',
			[
				'label' => __( 'Label Font Color', 'hoteller-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .hoteller-contact-form-content-wrapper label' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'label_typography',
				'label' => __( 'Label Typography', 'hoteller-elementor' ),
				'selector' => '{{WRAPPER}} .hoteller-contact-form-content-wrapper label',
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_field_style',
			array(
				'label'      => esc_html__( 'Fields', 'hoteller-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);
		
		$this->add_control(
			'field_background',
			[
				'label' => __( 'Fields Background', 'hoteller-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#f9f9f9',
				'selectors' => [
					'{{WRAPPER}} .hoteller-contact-form-content-wrapper input, {{WRAPPER}} .hoteller-contact-form-content-wrapper textarea' => 'background: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'field_font_color',
			[
				'label' => __( 'Fields Font Color', 'hoteller-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .hoteller-contact-form-content-wrapper input, {{WRAPPER}} .hoteller-contact-form-content-wrapper textarea, {{WRAPPER}} .hoteller-contact-form-content-wrapper p' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'field_focus_color',
			[
				'label' => __( 'Fields Focus Color', 'hoteller-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#1C58F6',
				'selectors' => [
					'{{WRAPPER}} .hoteller-contact-form-content-wrapper input:focus, {{WRAPPER}} .hoteller-contact-form-content-wrapper textarea:focus' => 'border-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'field_typography',
				'label' => __( 'Title Typography', 'hoteller-elementor' ),
				'selector' => '{{WRAPPER}} .hoteller-contact-form-content-wrapper input, {{WRAPPER}} .hoteller-contact-form-content-wrapper textarea, {{WRAPPER}} .hoteller-contact-form-content-wrapper select',
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
			'button_background',
			[
				'label' => __( 'Button Background', 'hoteller-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .hoteller-contact-form-content-wrapper input[type=submit]' => 'background: {{VALUE}}',
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
					'{{WRAPPER}} .hoteller-contact-form-content-wrapper input[type=submit]' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'button_border_color',
			[
				'label' => __( 'Button Border Color', 'hoteller-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .hoteller-contact-form-content-wrapper input[type=submit]' => 'border-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'button_hover_background',
			[
				'label' => __( 'Button Hover Background', 'hoteller-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .hoteller-contact-form-content-wrapper input[type=submit]:hover' => 'background: {{VALUE}}',
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
					'{{WRAPPER}} .hoteller-contact-form-content-wrapper input[type=submit]:hover' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'button_hover_border_color',
			[
				'label' => __( 'Button Hover Border Color', 'hoteller-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .hoteller-contact-form-content-wrapper input[type=submit]:hover' => 'border-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'label' => __( 'Button Typography', 'hoteller-elementor' ),
				'selector' => '{{WRAPPER}} .hoteller-contact-form-content-wrapper input[type=submit]',
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
		include(HOTELLER_ELEMENTOR_PATH.'templates/contact-form/index.php');
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
