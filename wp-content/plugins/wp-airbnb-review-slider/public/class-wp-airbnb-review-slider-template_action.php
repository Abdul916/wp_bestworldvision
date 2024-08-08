<?php # -*- coding: utf-8 -*-

add_action( 'init', array ( 'WPrev_Airbnb_Plugin_Action', 'init' ) );

class WPrev_Airbnb_Plugin_Action
{
    /**
     * Creates a new instance.
     *
     * @wp-hook init
     * @see    __construct()
     * @return void
     */
    public static function init()
    {
        new self;
    }

    /**
     * Register the action. May do more magic things.
     */
    public function __construct()
    {
        add_action( 'wprev_airbnb_plugin_action', array ( $this, 'wpairbnb_slider_action_print' ), 10, 1 );
    }

    /**
     * Prints out reviews
     *
     * Usage:
     *    <code>do_action( 'wprev_airbnb_plugin_action', 1 );</code>
     *	
     * @wp-hook wprev_airbnb_plugin_action
     * @param int $templateid
     * @return void
     */
    public function wpairbnb_slider_action_print( $templateid = 0 )
    {
		$a['tid']=$templateid;
		if($templateid>0){
		//ob_start();
		include plugin_dir_path( __FILE__ ) . 'partials/wp-airbnb-review-slider-public-display.php';
		//return ob_get_clean();
		}
    }
}