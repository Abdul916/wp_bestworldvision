<?php
namespace HotellerElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Search
 *
 * Elementor widget for search field
 *
 * @since 1.0.0
 */
class Hoteller_Search extends Widget_Base {

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
		return 'hoteller-search';
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
		return __( 'Search Bar', 'hoteller-elementor' );
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
			'icon_content_type',
			[
				'label' => __( 'Icon Content Type', 'photography-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'font_icon',
				'options' => [
					 'font_icon' => __( 'Font Icon', 'photography-elementor' ),
					 'icon_image' => __( 'Upload Icon Image', 'photography-elementor' ),
				],
			]
		);
		
		$this->add_control(
			'icon_font',
			[
				'label' => __( 'Font Icon', 'hoteller-elementor' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-search',
					'library' => 'solid',
				],
				'condition' => [
					'icon_content_type' => 'font_icon',
				],
			]
		);
		
		$this->add_control(
			'icon_image',
			  [
				'label' => __( 'Icon Image', 'photography-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [],
				'condition' => [
					'icon_content_type' => 'icon_image',
				],
			]
		);
		
		$this->add_responsive_control(
		    'icon_size',
		    [
		        'label' => __( 'Button Icon Size', 'hoteller-elementor' ),
		        'type' => Controls_Manager::SLIDER,
		        'default' => [
		            'size' => 16,
		            'unit' => 'px',
		        ],
		        'range' => [
		            'px' => [
		                'min' => 1,
		                'max' => 50,
		                'step' => 1,
		            ]
		        ],
		        'size_units' => [ 'px' ],
		        'selectors' => [
		            '{{WRAPPER}} .hoteller-search-icon a' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .hoteller-search-icon a img' => 'max-width: {{SIZE}}{{UNIT}}',
		        ],
		    ]
		);
		
		$this->add_responsive_control(
		    'width',
		    [
		        'label' => __( 'Search Input Width', 'hoteller-elementor' ),
		        'type' => Controls_Manager::SLIDER,
		        'default' => [
		            'size' => 450,
		            'unit' => 'px',
		        ],
		        'range' => [
		            'px' => [
		                'min' => 5,
		                'max' => 1000,
		                'step' => 5,
		            ]
		        ],
		        'size_units' => [ 'px' ],
		        'selectors' => [
		            '{{WRAPPER}} .hoteller-search-wrapper input' => 'width: {{SIZE}}{{UNIT}}',
		        ],
		    ]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'search_typography',
				'label' => __( 'Typography', 'hoteller-elementor' ),
				'selector' => '{{WRAPPER}} .hoteller-search-wrapper .input-group input',
			]
		);
		
		$this->add_control(
			'placeholder',
			[
				'label' => __( 'Placeholder Text', 'hoteller-elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Search for anything', 'hoteller-elementor' ),
			]
		);
		
		$this->add_responsive_control(
		    'search_icon_size',
		    [
		        'label' => __( 'Search Icon Size', 'hoteller-elementor' ),
		        'type' => Controls_Manager::SLIDER,
		        'default' => [
		            'size' => 26,
		            'unit' => 'px',
		        ],
		        'range' => [
		            'px' => [
		                'min' => 1,
		                'max' => 50,
		                'step' => 1,
		            ]
		        ],
		        'size_units' => [ 'px' ],
		        'selectors' => [
		            '{{WRAPPER}} .hoteller-search-wrapper .input-group .input-group-button button i' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .hoteller-search-wrapper .input-group .input-group-button button img' => 'max-width: {{SIZE}}{{UNIT}}',
		        ],
		    ]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_autocomplete',
			[
				'label' => __( 'Auto Complete', 'hoteller-elementor' ),
			]
		);
		
		$this->add_control(
			'autocomplete',
			[
				'label' => __( 'Auto Complete', 'hoteller-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Yes', 'hoteller-elementor' ),
				'label_off' => __( 'No', 'hoteller-elementor' ),
				'return_value' => 'yes',
			]
		);
		
		$this->add_responsive_control(
		    'autocomplete_width',
		    [
		        'label' => __( 'Auto Complete Width', 'hoteller-elementor' ),
		        'type' => Controls_Manager::SLIDER,
		        'default' => [
		            'size' => 500,
		            'unit' => 'px',
		        ],
		        'range' => [
		            'px' => [
		                'min' => 5,
		                'max' => 1000,
		                'step' => 5,
		            ]
		        ],
		        'size_units' => [ 'px' ],
		        'selectors' => [
		            '{{WRAPPER}} .autocomplete' => 'width: {{SIZE}}{{UNIT}}',
		        ],
		    ]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'autocomplete_typography',
				'label' => __( 'Results Typography', 'hoteller-elementor' ),
				'selector' => '{{WRAPPER}} .autocomplete ul li a',
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style',
			array(
				'label'      => esc_html__( 'Styles', 'hoteller-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);
		
		$this->add_control(
		    'button_search_icon_color',
		    [
		        'label' => __( 'Button Icon Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#000000',
		        'selectors' => [
		            '{{WRAPPER}} .hoteller-search-icon a' => 'color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_control(
		    'button_search_icon_hover_color',
		    [
		        'label' => __( 'Button Icon Hover Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#000000',
		        'selectors' => [
		            '{{WRAPPER}} .hoteller-search-icon a:hover' => 'color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_control(
		    'search_bg_color',
		    [
		        'label' => __( 'Search Background Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => 'rgba(0,0,0,0.9)',
		        'selectors' => [
		            '{{WRAPPER}} .hoteller-search-wrapper' => 'background: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_control(
		    'search_font_color',
		    [
		        'label' => __( 'Input Font Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#ffffff',
		        'selectors' => [
		            '{{WRAPPER}} .hoteller-search-wrapper .input-group input' => 'color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_control(
		    'search_placeholder_font_color',
		    [
		        'label' => __( 'Input Placeholder Font Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#ffffff',
		        'selectors' => [
		            '{{WRAPPER}} .hoteller-search-wrapper .input-group input::placeholder' => 'color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_control(
		    'search_border_color',
		    [
		        'label' => __( 'Search Input Border Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '256,256,256,0.1',
		        'selectors' => [
		            '{{WRAPPER}} .hoteller-search-wrapper .input-group' => 'border-color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_control(
		    'search_icon_color',
		    [
		        'label' => __( 'Search Icon Font Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#ffffff',
		        'selectors' => [
		            '{{WRAPPER}} .hoteller-search-wrapper .input-group .input-group-button button' => 'color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_autocomplete_style',
			array(
				'label'      => esc_html__( 'Auto Complete Styles', 'hoteller-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);
		
		$this->add_control(
		    'autocomplete_font_color',
		    [
		        'label' => __( 'Auto Complete Font Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#ffffff',
		        'selectors' => [
		            '{{WRAPPER}} .hoteller-search-wrapper .autocomplete li a' => 'color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_control(
		    'autocomplete_bg_color',
		    [
		        'label' => __( 'Auto Complete Background Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => 'rgba(0,0,0,0)',
		        'selectors' => [
		            '{{WRAPPER}} .autocomplete ul' => 'background-color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_control(
		    'autocomplete_hover_font_color',
		    [
		        'label' => __( 'Auto Complete Hover Font Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#ffffff',
		        'selectors' => [
		            '{{WRAPPER}} .autocomplete li:hover a' => 'color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_control(
		    'autocomplete_hover_bg_color',
		    [
		        'label' => __( 'Auto Complete Hover Background Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => 'rgba(256,256,256,0.1)',
		        'selectors' => [
		            '{{WRAPPER}} .autocomplete li:hover a' => 'background-color: {{VALUE}}',
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
		include(HOTELLER_ELEMENTOR_PATH.'templates/search/index.php');
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
	
	protected function _get_pages() {
		/*
			Get all pages available
		*/
		$pages = get_pages();
		$pages_select = array(
			 '' => __( 'Default Search Page', 'hoteller-elementor' )
		);
		foreach($pages as $each_page)
		{
			$pages_select[$each_page->ID] = $each_page->post_title;
		}
		
		return $pages_select;
	}
}
