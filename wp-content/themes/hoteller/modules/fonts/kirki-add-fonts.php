<?php

/*
 * Add Typekit Fonts
 * */

class kirkiAddFonts {
    public $custom_fonts;
    public function __construct() {

        $theme_info = wp_get_theme();
        $this->theme_version = $theme_info[ 'Version' ];

        $this->custom_fonts = get_theme_mod( 'tg_custom_fonts' );

        if ( ! empty( $this->custom_fonts ) ){
            add_action( 'wp_enqueue_scripts', array( $this, 'load_custom_fonts' ) );
        }
        add_filter( 'kirki/fonts/google_fonts', array( $this, 'add_custom_fonts' ) );
    }
    
    public function load_custom_fonts() {
        $custom_css = '';
        
        if ( ! empty( $this->custom_fonts ) ) 
        {
            foreach( $this->custom_fonts as $key=>$custom_font ){
	            if (filter_var($custom_font[ 'font_url' ], FILTER_VALIDATE_URL) === FALSE) {
				    $custom_font[ 'font_url' ] = wp_get_attachment_url($custom_font[ 'font_url' ]);
				}
	            
	            $custom_css.='
                	@font-face {
	                	font-family: "'.$custom_font[ 'font_name' ].'";
	                	src: url('.esc_url($custom_font[ 'font_url' ]).') format("woff");
	                }
                ';
            }
        }

        wp_add_inline_style( 'hoteller-screen', $custom_css );
    }

    public function add_custom_fonts( $google_fonts ){
        $my_custom_fonts = array();
        if ( ! empty( $this->custom_fonts ) ) {
            foreach( $this->custom_fonts as $key=>$custom_font )
            {
                $my_custom_fonts[ $custom_font[ 'font_name' ] ] = array(
                    'label' => $custom_font[ 'font_name' ],
                    'category' => 'sans-serif'
                );
            }
        }
        //var_dump($my_custom_fonts); die;
        return array_merge_recursive( $my_custom_fonts, $google_fonts );
    }
}

new kirkiAddFonts;

function tmu_custom_fonts( $standard_fonts ){
    $tg_custom_fonts = get_theme_mod( 'tg_custom_fonts' );
    $my_custom_fonts = array();
    
    if ( ! empty( $tg_custom_fonts ) ){
        foreach( $tg_custom_fonts as $key=>$custom_font ){
            $font_key = sanitize_title($custom_font[ 'font_name' ]);
            
            $my_custom_fonts['font2'] = array(
                'label' => $custom_font[ 'font_name' ],
                'variants' => array('regular','italic','700','700italic'),
                'stack' => $custom_font[ 'font_name' ].', sans-serif',
            );
        }
    }
    else
    {
        $my_custom_fonts = array();
    }
    
    return array_merge_recursive( $my_custom_fonts, $standard_fonts );
}
add_filter( 'kirki/fonts/standard_fonts', 'tmu_custom_fonts', 20 );