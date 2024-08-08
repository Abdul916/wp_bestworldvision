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
class Hoteller_Availability_Calendar extends Widget_Base {

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
		return 'hoteller-availability-calendar';
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
		return __( 'Availability Calendar', 'hoteller-elementor' );
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
		return 'eicon-calendar';
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
			0 => __( 'Select Accommodation Type', 'hoteller-elementor' )
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
			'accommodation_monthstoshow_rows',
			[
				'label' => __( 'Calendar Rows', 'hoteller-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 2,
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 10,
						'step' => 1,
					]
				],
			]
		);
		
		$this->add_control(
			'accommodation_monthstoshow_columns',
			[
				'label' => __( 'Calendar Columns', 'hoteller-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 3,
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 7,
						'step' => 1,
					]
				],
			]
		);
		
		$this->add_control(
			'show_price',
			[
				'label' => __( 'Show Room Price', 'hoteller-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Yes', 'hoteller-elementor' ),
				'label_off' => __( 'No', 'hoteller-elementor' ),
				'return_value' => 'yes',
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
		include(HOTELLER_ELEMENTOR_PATH.'templates/availability-calendar/index.php');
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
