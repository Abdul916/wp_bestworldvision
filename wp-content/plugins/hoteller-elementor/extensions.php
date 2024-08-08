<?php

/**
 * Class description
 *
 * @package   package_name
 * @author    ThemeG
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Hoteller_Ext' ) ) {

	/**
	 * Define Hoteller_Ext class
	 */
	class Hoteller_Ext {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    object
		 */
		private static $instance = null;

		/**
		 * Init Handler
		 */
		public function init() {
			add_action( 'elementor/element/column/section_advanced/after_section_end', [ $this, 'widget_tab_advanced_add_section' ], 10, 2 );
			add_action( 'elementor/element/common/_section_style/after_section_end', array( $this, 'widget_tab_advanced_add_section' ), 10, 2 );
			
			add_action( 'elementor/element/section/section_background/after_section_end', [ $this, 'widget_tab_styled_add_section' ], 10, 2 );
			
			//Add support for container
			add_action( 'elementor/element/container/section_background/after_section_end', [ $this, 'widget_tab_styled_add_section' ], 10, 2 );
			add_action( 'elementor/element/container/section_layout/after_section_end', [ $this, 'widget_tab_advanced_add_section' ], 10, 2 );
			
			add_action( 'elementor/element/image/section_style_image/after_section_end', [ $this, 'widget_image_tab_styled_add_section' ], 10, 2 );
		}
		
		public function widget_image_tab_styled_add_section( $element, $args ) {
			$element->start_controls_section(
				'hoteller_image_animation_section',
				[
					'label' => esc_html__( 'Image Animation', 'hoteller-elementor' ),
					'tab'   => Elementor\Controls_Manager::TAB_STYLE,
				]
			);
			
			$element->add_control(
				'hoteller_image_is_animation',
				[
					'label'        => esc_html__( 'Animation Effect', 'hoteller-elementor' ),
					'description'  => esc_html__( 'Add animation effect to image when scrolling', 'hoteller-elementor' ),
					'type'         => Elementor\Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'hoteller-elementor' ),
					'label_off'    => esc_html__( 'No', 'hoteller-elementor' ),
					'return_value' => 'true',
					'default'      => 'false',
					'frontend_available' => true,
				]
			);
			
			$element->add_control(
				'hoteller_image_animation_effect',
				[
					'label'       => esc_html__( 'Effect', 'hoteller-elementor' ),
					'type' => Elementor\Controls_Manager::SELECT,
					'default' => 'slide_down',
				    'options' => [
				     	'slide_down' => __( 'Slide Down', 'hoteller-elementor' ),
				     	'slide_up' => __( 'Slide Up', 'hoteller-elementor' ),
				     	'slide_left' => __( 'Slide Left', 'hoteller-elementor' ),
				     	'slide_right' => __( 'Slide Right', 'hoteller-elementor' ),
				     	'zoom_in' => __( 'Zoom In', 'hoteller-elementor' ),
				     	'zoom_out' => __( 'Zoom Out', 'hoteller-elementor' ),
				    ],
					'condition' => [
						'hoteller_image_is_animation' => 'true',
					],
					'frontend_available' => true,
				]
			);
			
			$element->add_control(
		    'hoteller_image_animation_overlay_color',
		    [
		        'label' => __( 'Overlay Color', 'hoteller-elementor' ),
		        'type' => Elementor\Controls_Manager::COLOR,
		        'condition' => [
					'hoteller_image_is_animation' => 'true',
				],
		        'default' => '#ffffff',
		        'selectors' => [
		            '{{WRAPPER}}:after' => 'background: {{VALUE}} !important',
		            '{{WRAPPER}}.elementor-element:after' => 'border-color: {{VALUE}} !important',
		        ],
		    ]
		);
			
			$element->end_controls_section();
		}
		
		public function widget_tab_styled_add_section( $element, $args ) {
			$element->start_controls_section(
				'hoteller_ext_parallax_section',
				[
					'label' => esc_html__( 'Background Parallax', 'hoteller-elementor' ),
					'tab'   => Elementor\Controls_Manager::TAB_STYLE,
				]
			);
			
			$element->add_control(
				'hoteller_ext_is_background_parallax',
				[
					'label'        => esc_html__( 'Background Parallax', 'hoteller-elementor' ),
					'description'  => esc_html__( 'Add parallax scrolling effect to background image', 'hoteller-elementor' ),
					'type'         => Elementor\Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'hoteller-elementor' ),
					'label_off'    => esc_html__( 'No', 'hoteller-elementor' ),
					'return_value' => 'true',
					'default'      => 'false',
					'frontend_available' => true,
				]
			);
			
			$element->add_control(
			    'hoteller_ext_is_background_parallax_speed',
			    [
			        'label' => __( 'Scroll Speed', 'hoteller-elementor' ),
			        'description' => __( 'factor that control speed of scroll animation', 'hoteller-elementor' ),
			        'type' => Elementor\Controls_Manager::SLIDER,
			        'default' => [
			            'size' => 0.1,
			        ],
			        'range' => [
			            'px' => [
			                'min' => -1,
			                'max' => 1,
			                'step' => 0.1,
			            ]
			        ],
			        'size_units' => [ 'px' ],
			        'condition' => [
						'hoteller_ext_is_background_parallax' => 'true',
					],
					'frontend_available' => true,
			    ]
			);
			
			$element->end_controls_section();
			
			$element->start_controls_section(
				'hoteller_ext_background_on_scroll_section',
				[
					'label' => esc_html__( 'Background On Scroll', 'hoteller-elementor' ),
					'tab'   => Elementor\Controls_Manager::TAB_STYLE,
				]
			);
			
			$element->add_control(
				'hoteller_ext_is_background_on_scroll',
				[
					'label'        => esc_html__( 'Background On Scroll', 'hoteller-elementor' ),
					'description'  => esc_html__( 'Add background color change on scroll', 'hoteller-elementor' ),
					'type'         => Elementor\Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'hoteller-elementor' ),
					'label_off'    => esc_html__( 'No', 'hoteller-elementor' ),
					'return_value' => 'true',
					'default'      => 'false',
					'frontend_available' => true,
				]
			);
			
			$element->add_control(
			    'hoteller_ext_background_on_scroll_color',
			    [
			        'label' => __( 'Background Color', 'hoteller-elementor' ),
			        'type' => Elementor\Controls_Manager::COLOR,
			        'condition' => [
						'hoteller_ext_is_background_on_scroll' => 'true',
					],
			        'default' => '#000000',
			        'frontend_available' => true,
			    ]
			);
			
			$element->end_controls_section();
		}

		/**
		 * [widget_tab_advanced_add_section description]
		 * @param  [type] $element [description]
		 * @param  [type] $args    [description]
		 * @return [type]          [description]
		 */
		public function widget_tab_advanced_add_section( $element, $args ) {
			
			$element->start_controls_section(
				'hoteller_ext_link_section',
				[
					'label' => esc_html__( 'Link Options', 'hoteller-elementor' ),
					'tab'   => Elementor\Controls_Manager::TAB_ADVANCED,
				]
			);
			
			$element->add_control(
				'hoteller_ext_link_sidemenu',
				[
					'label'        => esc_html__( 'Link to side menu', 'hoteller-elementor' ),
					'description'  => esc_html__( 'Add link to element to open side menu when clicking', 'hoteller-elementor' ),
					'type'         => Elementor\Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'hoteller-elementor' ),
					'label_off'    => esc_html__( 'No', 'hoteller-elementor' ),
					'return_value' => 'true',
					'default'      => 'false',
					'frontend_available' => true,
				]
			);
			
			$element->add_control(
				'hoteller_ext_link_fullmenu',
				[
					'label'        => esc_html__( 'Link to fullscreen menu', 'hoteller-elementor' ),
					'description'  => esc_html__( 'Add link to element to open fullscreen menu when clicking', 'hoteller-elementor' ),
					'type'         => Elementor\Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'hoteller-elementor' ),
					'label_off'    => esc_html__( 'No', 'hoteller-elementor' ),
					'return_value' => 'true',
					'default'      => 'false',
					'frontend_available' => true,
				]
			);
			
			$element->add_control(
				'hoteller_ext_link_closed_fullmenu',
				[
					'label'        => esc_html__( 'Link to closed fullscreen menu', 'hoteller-elementor' ),
					'description'  => esc_html__( 'Add link to element to close fullscreen menu when clicking', 'hoteller-elementor' ),
					'type'         => Elementor\Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'hoteller-elementor' ),
					'label_off'    => esc_html__( 'No', 'hoteller-elementor' ),
					'return_value' => 'true',
					'default'      => 'false',
					'frontend_available' => true,
				]
			);
			
			/*$element->add_control(
				'hoteller_ext_link_lightbox',
				[
					'label'        => esc_html__( 'Link to lightbox', 'hoteller-elementor' ),
					'description'  => esc_html__( 'Add link to element to open in lightbox when clicking', 'hoteller-elementor' ),
					'type'         => Elementor\Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'hoteller-elementor' ),
					'label_off'    => esc_html__( 'No', 'hoteller-elementor' ),
					'return_value' => 'true',
					'default'      => 'false',
					'frontend_available' => true,
				]
			);*/
			
			$element->end_controls_section();

			$element->start_controls_section(
				'hoteller_ext_animation_section',
				[
					'label' => esc_html__( 'Custom Animation', 'hoteller-elementor' ),
					'tab'   => Elementor\Controls_Manager::TAB_ADVANCED,
				]
			);

			$element->add_control(
				'hoteller_ext_is_scrollme',
				[
					'label'        => esc_html__( 'Scroll Animation', 'hoteller-elementor' ),
					'description'  => esc_html__( 'Add animation to element when scrolling through page contents', 'hoteller-elementor' ),
					'type'         => Elementor\Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'hoteller-elementor' ),
					'label_off'    => esc_html__( 'No', 'hoteller-elementor' ),
					'return_value' => 'true',
					'default'      => 'false',
					'frontend_available' => true,
				]
			);

			$element->add_control(
				'hoteller_ext_scrollme_disable',
				[
					'label'       => esc_html__( 'Disable for', 'hoteller-elementor' ),
					'type' => Elementor\Controls_Manager::SELECT,
					'default' => 'mobile',
				    'options' => [
				     	'none' => __( 'None', 'hoteller-elementor' ),
				     	'tablet' => __( 'Mobile and Tablet', 'hoteller-elementor' ),
				     	'mobile' => __( 'Mobile', 'hoteller-elementor' ),
				    ],
					'condition' => [
						'hoteller_ext_is_scrollme' => 'true',
					],
					'frontend_available' => true,
				]
			);
			
			$element->add_control(
			    'hoteller_ext_scrollme_smoothness',
			    [
			        'label' => __( 'Smoothness', 'hoteller-elementor' ),
			        'description' => __( 'factor that slowdown the animation, the more the smoothier', 'hoteller-elementor' ),
			        'type' => Elementor\Controls_Manager::SLIDER,
			        'default' => [
			            'size' => 30,
			        ],
			        'range' => [
			            'px' => [
			                'min' => 0,
			                'max' => 100,
			                'step' => 5,
			            ]
			        ],
			        'size_units' => [ 'px' ],
			        'condition' => [
						'hoteller_ext_is_scrollme' => 'true',
					],
					'frontend_available' => true,
			    ]
			);
			
			$element->add_control(
			    'hoteller_ext_scrollme_scalex',
			    [
			        'label' => __( 'Scale X', 'hoteller-elementor' ),
			        'type' => Elementor\Controls_Manager::SLIDER,
			        'default' => [
			            'size' => 1,
			        ],
			        'range' => [
			            'px' => [
			                'min' => 0.1,
			                'max' => 2,
			                'step' => 0.1,
			            ]
			        ],
			        'size_units' => [ 'px' ],
			        'condition' => [
						'hoteller_ext_is_scrollme' => 'true',
					],
					'frontend_available' => true,
			    ]
			);
			
			$element->add_control(
			    'hoteller_ext_scrollme_scaley',
			    [
			        'label' => __( 'Scale Y', 'hoteller-elementor' ),
			        'type' => Elementor\Controls_Manager::SLIDER,
			        'default' => [
			            'size' => 1,
			        ],
			        'range' => [
			            'px' => [
			                'min' => 0.1,
			                'max' => 2,
			                'step' => 0.1,
			            ]
			        ],
			        'size_units' => [ 'px' ],
			        'condition' => [
						'hoteller_ext_is_scrollme' => 'true',
					],
					'frontend_available' => true,
			    ]
			);
			
			$element->add_control(
			    'hoteller_ext_scrollme_scalez',
			    [
			        'label' => __( 'Scale Z', 'hoteller-elementor' ),
			        'type' => Elementor\Controls_Manager::SLIDER,
			        'default' => [
			            'size' => 1,
			        ],
			        'range' => [
			            'px' => [
			                'min' => 0.1,
			                'max' => 2,
			                'step' => 0.1,
			            ]
			        ],
			        'size_units' => [ 'px' ],
			        'condition' => [
						'hoteller_ext_is_scrollme' => 'true',
					],
					'frontend_available' => true,
			    ]
			);
		
			$element->add_control(
			    'hoteller_ext_scrollme_rotatex',
			    [
			        'label' => __( 'Rotate X', 'hoteller-elementor' ),
			        'type' => Elementor\Controls_Manager::SLIDER,
			        'default' => [
			            'size' => 0,
			        ],
			        'range' => [
			            'px' => [
			                'min' => -360,
			                'max' => 360,
			                'step' => 1,
			            ]
			        ],
			        'size_units' => [ 'px' ],
			        'condition' => [
						'hoteller_ext_is_scrollme' => 'true',
					],
					'frontend_available' => true,
			    ]
			);
			
			$element->add_control(
			    'hoteller_ext_scrollme_rotatey',
			    [
			        'label' => __( 'Rotate Y', 'hoteller-elementor' ),
			        'type' => Elementor\Controls_Manager::SLIDER,
			        'default' => [
			            'size' => 0,
			        ],
			        'range' => [
			            'px' => [
			                'min' => -360,
			                'max' => 360,
			                'step' => 1,
			            ]
			        ],
			        'size_units' => [ 'px' ],
			        'condition' => [
						'hoteller_ext_is_scrollme' => 'true',
					],
					'frontend_available' => true,
			    ]
			);
			
			$element->add_control(
			    'hoteller_ext_scrollme_rotatez',
			    [
			        'label' => __( 'Rotate Z', 'hoteller-elementor' ),
			        'type' => Elementor\Controls_Manager::SLIDER,
			        'default' => [
			            'size' => 0,
			        ],
			        'range' => [
			            'px' => [
			                'min' => -360,
			                'max' => 360,
			                'step' => 1,
			            ]
			        ],
			        'size_units' => [ 'px' ],
			        'condition' => [
						'hoteller_ext_is_scrollme' => 'true',
					],
					'frontend_available' => true,
			    ]
			);
			
			$element->add_control(
			    'hoteller_ext_scrollme_translatex',
			    [
			        'label' => __( 'Translate X', 'hoteller-elementor' ),
			        'type' => Elementor\Controls_Manager::SLIDER,
			        'default' => [
			            'size' => 0,
			        ],
			        'range' => [
			            'px' => [
			                'min' => -1000,
			                'max' => 1000,
			                'step' => 1,
			            ]
			        ],
			        'size_units' => [ 'px' ],
			        'condition' => [
						'hoteller_ext_is_scrollme' => 'true',
					],
					'frontend_available' => true,
			    ]
			);
			
			$element->add_control(
			    'hoteller_ext_scrollme_translatey',
			    [
			        'label' => __( 'Translate Y', 'hoteller-elementor' ),
			        'type' => Elementor\Controls_Manager::SLIDER,
			        'default' => [
			            'size' => 0,
			        ],
			        'range' => [
			            'px' => [
			                'min' => -1000,
			                'max' => 1000,
			                'step' => 1,
			            ]
			        ],
			        'size_units' => [ 'px' ],
			        'condition' => [
						'hoteller_ext_is_scrollme' => 'true',
					],
					'frontend_available' => true,
			    ]
			);
			
			$element->add_control(
			    'hoteller_ext_scrollme_translatez',
			    [
			        'label' => __( 'Translate Z', 'hoteller-elementor' ),
			        'type' => Elementor\Controls_Manager::SLIDER,
			        'default' => [
			            'size' => 0,
			        ],
			        'range' => [
			            'px' => [
			                'min' => -1000,
			                'max' => 1000,
			                'step' => 1,
			            ]
			        ],
			        'size_units' => [ 'px' ],
			        'condition' => [
						'hoteller_ext_is_scrollme' => 'true',
					],
					'frontend_available' => true,
			    ]
			);
			
			$element->add_control(
				'hoteller_ext_is_smoove',
				[
					'label'        => esc_html__( 'Entrance Animation', 'hoteller-elementor' ),
					'description'  => esc_html__( 'Add custom entrance animation to element', 'hoteller-elementor' ),
					'type'         => Elementor\Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'hoteller-elementor' ),
					'label_off'    => esc_html__( 'No', 'hoteller-elementor' ),
					'return_value' => 'true',
					'default'      => 'false',
					'frontend_available' => true,
				]
			);

			$element->add_control(
				'hoteller_ext_smoove_disable',
				[
					'label'       => esc_html__( 'Disable for', 'hoteller-elementor' ),
					'type' => Elementor\Controls_Manager::SELECT,
					'default' => 1,
				    'options' => [
				     	1 => __( 'None', 'hoteller-elementor' ),
				     	769 => __( 'Mobile and Tablet', 'hoteller-elementor' ),
				     	415 => __( 'Mobile', 'hoteller-elementor' ),
				    ],
					'condition' => [
						'hoteller_ext_is_smoove' => 'true',
					],
					'frontend_available' => true,
				]
			);
			
			$element->add_control(
				'hoteller_ext_smoove_easing',
				[
					'label'       => esc_html__( 'Easing', 'hoteller-elementor' ),
					'type' => Elementor\Controls_Manager::SELECT,
					'default' => '0.250, 0.250, 0.750, 0.750',
				    'options' => [
					    '0.250, 0.250, 0.750, 0.750' => __( 'linear', 'hoteller-elementor' ),
				     	'0.250, 0.100, 0.250, 1.000' => __( 'ease', 'hoteller-elementor' ),
				     	'0.420, 0.000, 1.000, 1.000' => __( 'ease-in', 'hoteller-elementor' ),
				     	'0.000, 0.000, 0.580, 1.000' => __( 'ease-out', 'hoteller-elementor' ),
				     	'0.420, 0.000, 0.580, 1.000' => __( 'ease-in-out', 'hoteller-elementor' ),
				     	'0.550, 0.085, 0.680, 0.530' => __( 'easeInQuad', 'hoteller-elementor' ),
				     	'0.550, 0.055, 0.675, 0.190' => __( 'easeInCubic', 'hoteller-elementor' ),
				     	'0.895, 0.030, 0.685, 0.220' => __( 'easeInQuart', 'hoteller-elementor' ),
				     	'0.755, 0.050, 0.855, 0.060' => __( 'easeInQuint', 'hoteller-elementor' ),
				     	'0.470, 0.000, 0.745, 0.715' => __( 'easeInSine', 'hoteller-elementor' ),
				     	'0.950, 0.050, 0.795, 0.035' => __( 'easeInExpo', 'hoteller-elementor' ),
				     	'0.600, 0.040, 0.980, 0.335' => __( 'easeInCirc', 'hoteller-elementor' ),
				     	'0.600, -0.280, 0.735, 0.045' => __( 'easeInBack', 'hoteller-elementor' ),
				     	'0.250, 0.460, 0.450, 0.940' => __( 'easeOutQuad', 'hoteller-elementor' ),
				     	'0.215, 0.610, 0.355, 1.000' => __( 'easeOutCubic', 'hoteller-elementor' ),
				     	'0.165, 0.840, 0.440, 1.000' => __( 'easeOutQuart', 'hoteller-elementor' ),
				     	'0.230, 1.000, 0.320, 1.000' => __( 'easeOutQuint', 'hoteller-elementor' ),
				     	'0.390, 0.575, 0.565, 1.000' => __( 'easeOutSine', 'hoteller-elementor' ),
				     	'0.190, 1.000, 0.220, 1.000' => __( 'easeOutExpo', 'hoteller-elementor' ),
				     	'0.075, 0.820, 0.165, 1.000' => __( 'easeOutCirc', 'hoteller-elementor' ),
				     	'0.175, 0.885, 0.320, 1.275' => __( 'easeOutBack', 'hoteller-elementor' ),
				     	'0.455, 0.030, 0.515, 0.955' => __( 'easeInOutQuad', 'hoteller-elementor' ),
				     	'0.645, 0.045, 0.355, 1.000' => __( 'easeInOutCubic', 'hoteller-elementor' ),
				     	'0.770, 0.000, 0.175, 1.000' => __( 'easeInOutQuart', 'hoteller-elementor' ),
				     	'0.860, 0.000, 0.070, 1.000' => __( 'easeInOutQuint', 'hoteller-elementor' ),
				     	'0.445, 0.050, 0.550, 0.950' => __( 'easeInOutSine', 'hoteller-elementor' ),
				     	'1.000, 0.000, 0.000, 1.000' => __( 'easeInOutExpo', 'hoteller-elementor' ),
				     	'0.785, 0.135, 0.150, 0.860' => __( 'easeInOutCirc', 'hoteller-elementor' ),
				     	'0.680, -0.550, 0.265, 1.550' => __( 'easeInOutBack', 'hoteller-elementor' ),
				    ],
					'condition' => [
						'hoteller_ext_is_smoove' => 'true',
					],
					'frontend_available' => false,
					'selectors' => [
			            '.elementor-element.elementor-element-{{ID}}' => 'transition-timing-function: cubic-bezier({{VALUE}}) !important',
			        ],
				]
			);
			
			$element->add_control(
				'hoteller_ext_smoove_delay',
				[
					'label' => __( 'Animation Delay (ms)', 'hoteller-elementor' ),
					'type' => \Elementor\Controls_Manager::NUMBER,
					'min' => 0,
					'max' => 5000,
					'step' => 5,
					'default' => 0,
					'condition' => [
						'hoteller_ext_is_smoove' => 'true',
					],
					'frontend_available' => false,
					'selectors' => [
			            '.elementor-element.elementor-element-{{ID}}' => 'transition-delay: {{VALUE}}ms !important',
			        ],
				]
			);
			
			$element->add_control(
				'hoteller_ext_smoove_duration',
				[
					'label' => __( 'Animation Duration (ms)', 'hoteller-elementor' ),
					'type' => \Elementor\Controls_Manager::NUMBER,
					'min' => 5,
					'max' => 5000,
					'step' => 5,
					'default' => 400,
					'condition' => [
						'hoteller_ext_is_smoove' => 'true',
					],
					'frontend_available' => true,
					/*'selectors' => [
			            '.elementor-widget.elementor-element-{{ID}}' => 'transition-duration: {{VALUE}}ms !important',
			        ],*/
				]
			);
			
			$element->add_control(
			    'hoteller_ext_smoove_opacity',
			    [
			        'label' => __( 'Opacity', 'hoteller-elementor' ),
			        'type' => Elementor\Controls_Manager::SLIDER,
			        'default' => [
			            'size' => 0,
			        ],
			        'range' => [
			            'px' => [
			                'min' => 0,
			                'max' => 1,
			                'step' => 0.1,
			            ]
			        ],
			        'size_units' => [ 'px' ],
			        'condition' => [
						'hoteller_ext_is_smoove' => 'true',
					],
					'frontend_available' => false,
					'selectors' => [
			            '.elementor-widget.elementor-element-{{ID}}' => 'opacity: {{SIZE}}',
			        ],
			    ]
			);
			
			$element->add_control(
			    'hoteller_ext_smoove_scalex',
			    [
			        'label' => __( 'Scale X', 'hoteller-elementor' ),
			        'type' => Elementor\Controls_Manager::SLIDER,
			        'default' => [
			            'size' => 1,
			        ],
			        'range' => [
			            'px' => [
			                'min' => 0.1,
			                'max' => 2,
			                'step' => 0.1,
			            ]
			        ],
			        'size_units' => [ 'px' ],
			        'condition' => [
						'hoteller_ext_is_smoove' => 'true',
					],
					'frontend_available' => true,
			    ]
			);
			
			$element->add_control(
			    'hoteller_ext_smoove_scaley',
			    [
			        'label' => __( 'Scale Y', 'hoteller-elementor' ),
			        'type' => Elementor\Controls_Manager::SLIDER,
			        'default' => [
			            'size' => 1,
			        ],
			        'range' => [
			            'px' => [
			                'min' => 0.1,
			                'max' => 2,
			                'step' => 0.1,
			            ]
			        ],
			        'size_units' => [ 'px' ],
			        'condition' => [
						'hoteller_ext_is_smoove' => 'true',
					],
					'frontend_available' => true,
			    ]
			);
			
			$element->add_control(
			    'hoteller_ext_smoove_rotatex',
			    [
			        'label' => __( 'Rotate X', 'hoteller-elementor' ),
			        'type' => Elementor\Controls_Manager::SLIDER,
			        'default' => [
			            'size' => 0,
			        ],
			        'range' => [
			            'px' => [
			                'min' => -360,
			                'max' => 360,
			                'step' => 1,
			            ]
			        ],
			        'size_units' => [ 'px' ],
			        'condition' => [
						'hoteller_ext_is_smoove' => 'true',
					],
					'frontend_available' => true,
			    ]
			);
			
			$element->add_control(
			    'hoteller_ext_smoove_rotatey',
			    [
			        'label' => __( 'Rotate Y', 'hoteller-elementor' ),
			        'type' => Elementor\Controls_Manager::SLIDER,
			        'default' => [
			            'size' => 0,
			        ],
			        'range' => [
			            'px' => [
			                'min' => -360,
			                'max' => 360,
			                'step' => 1,
			            ]
			        ],
			        'size_units' => [ 'px' ],
			        'condition' => [
						'hoteller_ext_is_smoove' => 'true',
					],
					'frontend_available' => true,
			    ]
			);
			
			$element->add_control(
			    'hoteller_ext_smoove_rotatez',
			    [
			        'label' => __( 'Rotate Z', 'hoteller-elementor' ),
			        'type' => Elementor\Controls_Manager::SLIDER,
			        'default' => [
			            'size' => 0,
			        ],
			        'range' => [
			            'px' => [
			                'min' => -360,
			                'max' => 360,
			                'step' => 1,
			            ]
			        ],
			        'size_units' => [ 'px' ],
			        'condition' => [
						'hoteller_ext_is_smoove' => 'true',
					],
					'frontend_available' => true,
			    ]
			);
			
			$element->add_control(
			    'hoteller_ext_smoove_translatex',
			    [
			        'label' => __( 'Translate X', 'hoteller-elementor' ),
			        'type' => Elementor\Controls_Manager::SLIDER,
			        'default' => [
			            'size' => 0,
			        ],
			        'range' => [
			            'px' => [
			                'min' => -1000,
			                'max' => 1000,
			                'step' => 1,
			            ]
			        ],
			        'size_units' => [ 'px' ],
			        'condition' => [
						'hoteller_ext_is_smoove' => 'true',
					],
					'frontend_available' => true,
			    ]
			);
			
			$element->add_control(
			    'hoteller_ext_smoove_translatey',
			    [
			        'label' => __( 'Translate Y', 'hoteller-elementor' ),
			        'type' => Elementor\Controls_Manager::SLIDER,
			        'default' => [
			            'size' => 0,
			        ],
			        'range' => [
			            'px' => [
			                'min' => -1000,
			                'max' => 1000,
			                'step' => 1,
			            ]
			        ],
			        'size_units' => [ 'px' ],
			        'condition' => [
						'hoteller_ext_is_smoove' => 'true',
					],
					'frontend_available' => true,
			    ]
			);
			
			$element->add_control(
			    'hoteller_ext_smoove_translatez',
			    [
			        'label' => __( 'Translate Z', 'hoteller-elementor' ),
			        'type' => Elementor\Controls_Manager::SLIDER,
			        'default' => [
			            'size' => 0,
			        ],
			        'range' => [
			            'px' => [
			                'min' => -1000,
			                'max' => 1000,
			                'step' => 1,
			            ]
			        ],
			        'size_units' => [ 'px' ],
			        'condition' => [
						'hoteller_ext_is_smoove' => 'true',
					],
					'frontend_available' => true,
			    ]
			);
			
			$element->add_control(
			    'hoteller_ext_smoove_skewx',
			    [
			        'label' => __( 'Skew X', 'hoteller-elementor' ),
			        'type' => Elementor\Controls_Manager::SLIDER,
			        'default' => [
			            'size' => 0,
			        ],
			        'range' => [
			            'px' => [
			                'min' => 0,
			                'max' => 360,
			                'step' => 1,
			            ]
			        ],
			        'size_units' => [ 'px' ],
			        'condition' => [
						'hoteller_ext_is_smoove' => 'true',
					],
					'frontend_available' => true,
			    ]
			);
			
			$element->add_control(
			    'hoteller_ext_smoove_skewy',
			    [
			        'label' => __( 'Skew Y', 'hoteller-elementor' ),
			        'type' => Elementor\Controls_Manager::SLIDER,
			        'default' => [
			            'size' => 0,
			        ],
			        'range' => [
			            'px' => [
			                'min' => 0,
			                'max' => 360,
			                'step' => 1,
			            ]
			        ],
			        'size_units' => [ 'px' ],
			        'condition' => [
						'hoteller_ext_is_smoove' => 'true',
					],
					'frontend_available' => true,
			    ]
			);
			
			$element->add_control(
			    'hoteller_ext_smoove_perspective',
			    [
			        'label' => __( 'Perspective', 'hoteller-elementor' ),
			        'type' => Elementor\Controls_Manager::SLIDER,
			        'default' => [
			            'size' => 1000,
			        ],
			        'range' => [
			            'px' => [
			                'min' => 5,
			                'max' => 4000,
			                'step' => 5,
			            ]
			        ],
			        'size_units' => [ 'px' ],
			        'condition' => [
						'hoteller_ext_is_smoove' => 'true',
					],
					'frontend_available' => true,
			    ]
			);
			
			$element->add_control(
				'hoteller_ext_is_parallax_mouse',
				[
					'label'        => esc_html__( 'Mouse Parallax', 'hoteller-elementor' ),
					'description'  => esc_html__( 'Add parallax to element when moving mouse position', 'hoteller-elementor' ),
					'type'         => Elementor\Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'hoteller-elementor' ),
					'label_off'    => esc_html__( 'No', 'hoteller-elementor' ),
					'return_value' => 'true',
					'default'      => 'false',
					'frontend_available' => true,
				]
			);
			
			$element->add_control(
			    'hoteller_ext_is_parallax_mouse_depth',
			    [
			        'label' => __( 'Depth', 'hoteller-elementor' ),
			        'type' => Elementor\Controls_Manager::SLIDER,
			        'default' => [
			            'size' => 0.2,
			        ],
			        'range' => [
			            'px' => [
			                'min' => 0.1,
			                'max' => 2,
			                'step' => 0.05,
			            ]
			        ],
			        'size_units' => [ 'px' ],
			        'condition' => [
						'hoteller_ext_is_parallax_mouse' => 'true',
					],
					'frontend_available' => true,
			    ]
			);
			
			$element->add_control(
				'hoteller_ext_is_infinite',
				[
					'label'        => esc_html__( 'Infinite Animation', 'hoteller-elementor' ),
					'description'  => esc_html__( 'Add custom infinite animation to element', 'hoteller-elementor' ),
					'type'         => Elementor\Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'hoteller-elementor' ),
					'label_off'    => esc_html__( 'No', 'hoteller-elementor' ),
					'return_value' => 'true',
					'default'      => 'false',
					'frontend_available' => true,
				]
			);
			
			$element->add_control(
				'hoteller_ext_infinite_animation',
				[
					'label'       => esc_html__( 'Easing', 'hoteller-elementor' ),
					'type' => Elementor\Controls_Manager::SELECT,
					'default' => 'if_bounce',
				    'options' => [
					    'if_swing1' => __( 'Swing 1', 'hoteller-elementor' ),
					    'if_swing2' => __( 'Swing 2', 'hoteller-elementor' ),
				     	'if_wave' 	=> __( 'Wave', 'hoteller-elementor' ),
				     	'if_tilt' 	=> __( 'Tilt', 'hoteller-elementor' ),
				     	'if_bounce' => __( 'Bounce', 'hoteller-elementor' ),
				     	'if_scale' 	=> __( 'Scale', 'hoteller-elementor' ),
				     	'if_spin' 	=> __( 'Spin', 'hoteller-elementor' ),
				    ],
					'condition' => [
						'hoteller_ext_is_infinite' => 'true',
					],
					'frontend_available' => true,
				]
			);
			
			$element->add_control(
				'hoteller_ext_infinite_easing',
				[
					'label'       => esc_html__( 'Easing', 'hoteller-elementor' ),
					'type' => Elementor\Controls_Manager::SELECT,
					'default' => '0.250, 0.250, 0.750, 0.750',
				    'options' => [
					    '0.250, 0.250, 0.750, 0.750' => __( 'linear', 'hoteller-elementor' ),
				     	'0.250, 0.100, 0.250, 1.000' => __( 'ease', 'hoteller-elementor' ),
				     	'0.420, 0.000, 1.000, 1.000' => __( 'ease-in', 'hoteller-elementor' ),
				     	'0.000, 0.000, 0.580, 1.000' => __( 'ease-out', 'hoteller-elementor' ),
				     	'0.420, 0.000, 0.580, 1.000' => __( 'ease-in-out', 'hoteller-elementor' ),
				     	'0.550, 0.085, 0.680, 0.530' => __( 'easeInQuad', 'hoteller-elementor' ),
				     	'0.550, 0.055, 0.675, 0.190' => __( 'easeInCubic', 'hoteller-elementor' ),
				     	'0.895, 0.030, 0.685, 0.220' => __( 'easeInQuart', 'hoteller-elementor' ),
				     	'0.755, 0.050, 0.855, 0.060' => __( 'easeInQuint', 'hoteller-elementor' ),
				     	'0.470, 0.000, 0.745, 0.715' => __( 'easeInSine', 'hoteller-elementor' ),
				     	'0.950, 0.050, 0.795, 0.035' => __( 'easeInExpo', 'hoteller-elementor' ),
				     	'0.600, 0.040, 0.980, 0.335' => __( 'easeInCirc', 'hoteller-elementor' ),
				     	'0.600, -0.280, 0.735, 0.045' => __( 'easeInBack', 'hoteller-elementor' ),
				     	'0.250, 0.460, 0.450, 0.940' => __( 'easeOutQuad', 'hoteller-elementor' ),
				     	'0.215, 0.610, 0.355, 1.000' => __( 'easeOutCubic', 'hoteller-elementor' ),
				     	'0.165, 0.840, 0.440, 1.000' => __( 'easeOutQuart', 'hoteller-elementor' ),
				     	'0.230, 1.000, 0.320, 1.000' => __( 'easeOutQuint', 'hoteller-elementor' ),
				     	'0.390, 0.575, 0.565, 1.000' => __( 'easeOutSine', 'hoteller-elementor' ),
				     	'0.190, 1.000, 0.220, 1.000' => __( 'easeOutExpo', 'hoteller-elementor' ),
				     	'0.075, 0.820, 0.165, 1.000' => __( 'easeOutCirc', 'hoteller-elementor' ),
				     	'0.175, 0.885, 0.320, 1.275' => __( 'easeOutBack', 'hoteller-elementor' ),
				     	'0.455, 0.030, 0.515, 0.955' => __( 'easeInOutQuad', 'hoteller-elementor' ),
				     	'0.645, 0.045, 0.355, 1.000' => __( 'easeInOutCubic', 'hoteller-elementor' ),
				     	'0.770, 0.000, 0.175, 1.000' => __( 'easeInOutQuart', 'hoteller-elementor' ),
				     	'0.860, 0.000, 0.070, 1.000' => __( 'easeInOutQuint', 'hoteller-elementor' ),
				     	'0.445, 0.050, 0.550, 0.950' => __( 'easeInOutSine', 'hoteller-elementor' ),
				     	'1.000, 0.000, 0.000, 1.000' => __( 'easeInOutExpo', 'hoteller-elementor' ),
				     	'0.785, 0.135, 0.150, 0.860' => __( 'easeInOutCirc', 'hoteller-elementor' ),
				     	'0.680, -0.550, 0.265, 1.550' => __( 'easeInOutBack', 'hoteller-elementor' ),
				    ],
					'condition' => [
						'hoteller_ext_is_infinite' => 'true',
					],
					'frontend_available' => true
				]
			);
			
			$element->add_control(
				'hoteller_ext_infinite_duration',
				[
					'label' => __( 'Animation Duration (s)', 'hoteller-elementor' ),
					'type' => \Elementor\Controls_Manager::NUMBER,
					'min' => 1,
					'max' => 15,
					'step' => 1,
					'default' => 4,
					'condition' => [
						'hoteller_ext_is_infinite' => 'true',
					],
					'frontend_available' => true
				]
			);
			
			$element->add_control(
				'hoteller_ext_is_fadeout_animation',
				[
					'label'        => esc_html__( 'FadeOut Animation', 'hoteller-elementor' ),
					'description'  => esc_html__( 'Add fadeout animation  to element when scrolling', 'hoteller-elementor' ),
					'type'         => Elementor\Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'hoteller-elementor' ),
					'label_off'    => esc_html__( 'No', 'hoteller-elementor' ),
					'return_value' => 'true',
					'default'      => 'false',
					'frontend_available' => true,
				]
			);
			
			$element->add_control(
			    'hoteller_ext_is_fadeout_animation_velocity',
			    [
			        'label' => __( 'Velocity', 'hoteller-elementor' ),
			        'type' => Elementor\Controls_Manager::SLIDER,
			        'default' => [
			            'size' => 0.7,
			        ],
			        'range' => [
			            'px' => [
			                'min' => 0.1,
			                'max' => 1,
			                'step' => 0.1,
			            ]
			        ],
			        'size_units' => [ 'px' ],
			        'condition' => [
						'hoteller_ext_is_fadeout_animation' => 'true',
					],
					'frontend_available' => true,
			    ]
			);
			
			$element->add_control(
				'hoteller_ext_is_fadeout_animation_direction',
				[
					'label'       => esc_html__( 'FadeOut Direction', 'hoteller-elementor' ),
					'type' => Elementor\Controls_Manager::SELECT,
					'default' => 'up',
				    'options' => [
					    'up' 		=> __( 'Up', 'hoteller-elementor' ),
					    'down' 		=> __( 'Down', 'hoteller-elementor' ),
				     	'still' 	=> __( 'Still', 'hoteller-elementor' ),
				    ],
					'condition' => [
						'hoteller_ext_is_fadeout_animation' => 'true',
					],
					'frontend_available' => true,
				]
			);
			
			$element->add_control(
				'hoteller_ext_mobile_static',
				[
					'label'        => esc_html__( 'Display Static Position on Mobile', 'hoteller-elementor' ),
					'description'  => esc_html__( 'Enbale this option to make the element display static position on mobile devices', 'hoteller-elementor' ),
					'type'         => Elementor\Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'hoteller-elementor' ),
					'label_off'    => esc_html__( 'No', 'hoteller-elementor' ),
					'return_value' => 'true',
					'default'      => 'false',
					'frontend_available' => true,
				]
			);

			$element->end_controls_section();
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return object
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
	}
}

/**
 * Returns instance of Hoteller_Ext
 *
 * @return object
 */
function hoteller_ext() {
	return Hoteller_Ext::get_instance();
}
