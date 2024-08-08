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
class Hoteller_Availability_Search extends Widget_Base {

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
		return 'hoteller-availability-search';
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
		return __( 'Availability Search Form', 'hoteller-elementor' );
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
		return 'eicon-search';
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
	
	public function get_accommodation_types() {
		//Get all accommodation types
		$accommodations_arr = get_posts( array(
			'post_type' => array('mphb_room_type'),
			'numberposts' => -1,
		    'orderby' => 'name',
		    'order'   => 'ASC'
		) );
		$tg_accommodations_select = array(
			0 => __( 'Any Accommodation Types', 'hoteller-elementor' )
		);
		
		foreach ($accommodations_arr as $accommodation) {
			$tg_accommodations_select[$accommodation->ID] = $accommodation->post_title;
		}

		return $tg_accommodations_select;
	}
	
	public function get_accommodation_attributes() {
		//Get all accommodation types
		$accommodations_arr = get_posts( array(
			'post_type' => array('mphb_room_attribute'),
			'numberposts' => -1,
			'orderby' => 'name',
			'order'   => 'ASC'
		) );
		$tg_accommodations_select = array();
		
		foreach ($accommodations_arr as $accommodation) {
			$tg_accommodations_select[$accommodation->ID] = $accommodation->post_title;
		}
	
		return $tg_accommodations_select;
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
			'accommodation',
			[
				'label' => __( 'Accommodation Type', 'hoteller-elementor' ),
				'type' => Controls_Manager::SELECT,
			    'options' => $this->get_accommodation_types(),
			    'multiple' => false,
			]
		);
		
		$this->add_control(
			'accommodation_attributes',
			[
				'label' => __( 'Attributes', 'hoteller-elementor' ),
				'type' => Controls_Manager::SELECT2,
				'options' => $this->get_accommodation_attributes(),
				'multiple' => true,
				'condition' => [
					'accommodation' => '',
				],
			]
		);
		
		$this->add_control(
			'accommodation_compact',
			[
				'label' => __( 'Display Compact Style', 'hoteller-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on' => __( 'Yes', 'hoteller-elementor' ),
				'label_off' => __( 'No', 'hoteller-elementor' ),
				'return_value' => 'yes',
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
		    'label_color',
		    [
		        'label' => __( 'Label Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#222222',
		        'selectors' => [
		            '{{WRAPPER}} .availability_search_wrapper label' => 'color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_input_style',
			array(
				'label'      => esc_html__( 'Input', 'hoteller-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);
		
		$this->add_control(
		    'input_font_color',
		    [
		        'label' => __( 'Input Font Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#222222',
		        'selectors' => [
		            '{{WRAPPER}} .availability_search_wrapper input[type=text]' => 'color: {{VALUE}}',
		            '{{WRAPPER}} .availability_search_wrapper select' => 'color: {{VALUE}}',
		            '{{WRAPPER}} .availability_search_wrapper .mphb_sc_search-wrapper .mphb_sc_search-adults:after, {{WRAPPER}} 
.availability_search_wrapper .mphb_sc_search-wrapper .mphb_sc_search-children:after' => 'color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_control(
		    'input_placeholder_font_color',
		    [
		        'label' => __( 'Input Placeholder Font Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#222222',
		        'selectors' => [
		            '{{WRAPPER}} .availability_search_wrapper input[type=text]::placeholder' => 'color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_control(
		    'input_background_color',
		    [
		        'label' => __( 'Input Background Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#ffffff',
		        'selectors' => [
		            '{{WRAPPER}} .availability_search_wrapper input[type=text]' => 'background-color: {{VALUE}}',
		            '{{WRAPPER}} .availability_search_wrapper select' => 'background-color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_control(
		    'input_border_color',
		    [
		        'label' => __( 'Input Border Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#222222',
		        'selectors' => [
		            '{{WRAPPER}} .availability_search_wrapper input[type=text]' => 'border-color: {{VALUE}}',
		            '{{WRAPPER}} .availability_search_wrapper select' => 'border-color: {{VALUE}}',
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
		    'button_font_color',
		    [
		        'label' => __( 'Button Font Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#ffffff',
		        'selectors' => [
		            '{{WRAPPER}} .availability_search_wrapper input[type=submit]' => 'color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_control(
		    'button_background_color',
		    [
		        'label' => __( 'Button Background Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#222222',
		        'selectors' => [
		            '{{WRAPPER}} .availability_search_wrapper input[type=submit]' => 'background-color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_control(
		    'button_border_color',
		    [
		        'label' => __( 'Button Border Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#222222',
		        'selectors' => [
		            '{{WRAPPER}} .availability_search_wrapper input[type=submit]' => 'border-color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_control(
		    'button_font_color_hover',
		    [
		        'label' => __( 'Button Hover Font Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#ffffff',
		        'selectors' => [
		            '{{WRAPPER}} .availability_search_wrapper input[type=submit]:hover' => 'color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_control(
		    'button_background_color_hover',
		    [
		        'label' => __( 'Button Hover Background Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#222222',
		        'selectors' => [
		            '{{WRAPPER}} .availability_search_wrapper input[type=submit]:hover' => 'background-color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_control(
		    'button_border_color_hover',
		    [
		        'label' => __( 'Button Hover Border Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#222222',
		        'selectors' => [
		            '{{WRAPPER}} .availability_search_wrapper input[type=submit]:hover' => 'border-color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_responsive_control(
			'button_width',
			[
				'label' => __( 'Button Width (in %)', 'hoteller-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 5,
					]
				],
				'size_units' => [ '%' ],
				'selectors' => [
					'{{WRAPPER}} .mphb_sc_search-submit-button-wrapper' => 'width: {{SIZE}}%;',
				],
			]
		);
		
		$this->add_control(
			'button_border_radius',
			[
				'label' => __( 'Button Border Radius', 'hoteller-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .availability_search_wrapper input[type=submit]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'button_padding',
			[
				'label' => __( 'Button Padding', 'hoteller-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .availability_search_wrapper input[type=submit]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
		include(HOTELLER_ELEMENTOR_PATH.'templates/availability-search/index.php');
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
