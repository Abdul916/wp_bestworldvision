;(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	//document ready
	$(function(){
	 
		$( ".wprs_rd_more" ).click(function() {
			$(this ).hide();
			$(this ).next("span").show();
			
			//change height of wprev-slider-widget
			$(this ).closest( ".wprev-slider-widget" ).css( "height", "auto" );
			
			//change height of wprev-slider
			$(this ).closest( ".wprev-slider" ).css( "height", "auto" );
			
		});
		
			//check to see if we need to create slider;
			$( ".wprev-slider" ).each(function( index ) {
				createaslider(this,'shortcode');
			});
			$( ".wprev-slider-widget" ).each(function( index ) {
				createaslider(this,'widget');
			});
			function createaslider(thissliderdiv,type){
				//unhide other rows.
				var showarrows = true;
				if(type=='widget'){
					showarrows = false;
				}
				$( thissliderdiv ).find('li').show();
				var slider = $( thissliderdiv ).wprs_unslider(
						{
						autoplay:false,
						infinite:false,
						delay: '5000',
						speed: '750',
						animation: 'horizontal',
						arrows: showarrows,
						animateHeight: true,
						activeClass: 'wprs_unslider-active',
						}
					);
				
				setTimeout(function(){
					//height of active slide
					var firstheight = $(thissliderdiv).find('.wprs_unslider-active').height();
					$(thissliderdiv).css( 'height', firstheight );
				}, 500);
				
				slider.on('mouseover', function() {slider.data('wprs_unslider').stop();}).on('mouseout', function() {slider.data('wprs_unslider').start();});
								
			};

		
	});

})( jQuery );