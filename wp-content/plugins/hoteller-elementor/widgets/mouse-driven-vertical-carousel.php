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
class Hoteller_Mouse_Drive_Vertical_Carousel extends Widget_Base {

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
		return 'hoteller-mouse-driven-vertical-carousel';
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
		return __( 'Mouse Driven Vertical Carousel', 'hoteller-elementor' );
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
		return 'eicon-post-list';
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
		    'header',
		    [
		        'label' => __( 'Header', 'hoteller-elementor' ),
		        'type' => Controls_Manager::TEXT,
		    ]
		);
		
		$this->add_control(
		    'sub_header',
		    [
		        'label' => __( 'Sub Header', 'hoteller-elementor' ),
		        'type' => Controls_Manager::TEXT,
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
			'slide_sub_title', [
				'label' => __( 'Sub Title', 'hoteller-elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
			]
		);
		
		$repeater->add_control(
			'slide_button_link', [
				'label' => __( 'Button Link URL', 'hoteller-elementor' ),
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
		        'label' => __( 'Height (in px)', 'hoteller-elementor' ),
		        'type' => Controls_Manager::SLIDER,
		        'default' => [
		            'size' => 700,
		        ],
		        'range' => [
		            'px' => [
		                'min' => 5,
		                'max' => 2000,
		                'step' => 5,
		            ]
		        ],
		        'size_units' => [ 'px' ],
		        'selectors' => [
		            '{{WRAPPER}} .tg_mouse_driven_vertical_carousel_wrapper' => 'height: {{SIZE}}{{UNIT}}',
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
		            '{{WRAPPER}} .tg_mouse_driven_vertical_carousel_wrapper .c-gradient-overlay' => 'background: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_header_style',
			array(
				'label'      => esc_html__( 'Header & Sub Header', 'hoteller-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);
		
		$this->add_control(
		    'header_color',
		    [
		        'label' => __( 'Header Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#ffffff',
		        'selectors' => [
		            '{{WRAPPER}} .tg_mouse_driven_vertical_carousel_wrapper .u-media-wrapper .carousel__header h2' => 'color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'header_typography',
				'label' => __( 'Header Typography', 'hoteller-elementor' ),
				'selector' => '{{WRAPPER}} .tg_mouse_driven_vertical_carousel_wrapper .u-media-wrapper .carousel__header h2',
			]
		);
		
		$this->add_control(
		    'sub_header_color',
		    [
		        'label' => __( 'Sub Header Color', 'hoteller-elementor' ),
		        'type' => Controls_Manager::COLOR,
		        'default' => '#ffffff',
		        'selectors' => [
		            '{{WRAPPER}} .tg_mouse_driven_vertical_carousel_wrapper .u-media-wrapper .carousel__header .carousel__sub_header' => 'color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'sub_header_typography',
				'label' => __( 'Sub Header Typography', 'hoteller-elementor' ),
				'selector' => '{{WRAPPER}} .tg_mouse_driven_vertical_carousel_wrapper .u-media-wrapper .carousel__header .carousel__sub_header',
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
		            '{{WRAPPER}} .tg_mouse_driven_vertical_carousel_wrapper .u-media-wrapper .c-mouse-vertical-carousel__title' => 'color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __( 'Title Typography', 'hoteller-elementor' ),
				'selector' => '{{WRAPPER}} .tg_mouse_driven_vertical_carousel_wrapper .u-media-wrapper .c-mouse-vertical-carousel__title',
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
		        'default' => '#ffffff',
		        'selectors' => [
		            '{{WRAPPER}} .tg_mouse_driven_vertical_carousel_wrapper .u-media-wrapper .c-mouse-vertical-carousel__eyebrow' => 'color: {{VALUE}}',
		        ],
		    ]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'subtitle_typography',
				'label' => __( 'Sub Title Typography', 'hoteller-elementor' ),
				'selector' => '{{WRAPPER}} .tg_mouse_driven_vertical_carousel_wrapper .u-media-wrapper .c-mouse-vertical-carousel__eyebrow',
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
		include(HOTELLER_ELEMENTOR_PATH.'templates/mouse-driven-vertical-carousel/index.php');
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
