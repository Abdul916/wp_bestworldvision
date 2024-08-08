<?php
	function hoteller_typedjs_shortcode( $atts , $content = null ) {
		wp_enqueue_style('typedjs-style', plugins_url( '/hoteller-elementor/assets/css/typedjs.min.css' ), false, false, 'all' );
		wp_enqueue_script('typedjs-script', plugins_url( '/hoteller-elementor/assets/js/typed.min.js' ), array(), false, true );

        //Loop
        $exp = explode(",", $content);
        $sentence = "";

        foreach($exp AS $sentence_raw) {

            $sentence .= "<p>$sentence_raw</p>";

        }

        $return_html = "<div class=\"type-wrap\" style=\"display:none;\">
        <div id=\"typed-strings\">$sentence</div>
        <span id=\"typed\" style=\"white-space:pre;\"></span>
        </div>";
        
        $custom_typed_script = '
		jQuery(function( $ ) {
			jQuery(".type-wrap").show();
		    jQuery("#typed").typed({
		        stringsElement: jQuery("#typed-strings"),
		        typeSpeed: 65,
		        backDelay: 2500,
		        loop: true,
		        loopCount: Infinity,
		        contentType: "html", // or text
		        loopCount: true
		    });
		});
		';
		
		wp_add_inline_script( 'typedjs-script', $custom_typed_script );

        return $return_html;

    }
    add_shortcode( 'typedjs', 'hoteller_typedjs_shortcode' );
?>