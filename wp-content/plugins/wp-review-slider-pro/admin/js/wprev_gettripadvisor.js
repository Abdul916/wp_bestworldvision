
function gettripadvisorreviewsfunction(myurlnum) {

	//launch pop-up for progress messages
	openpopup(adminjs_script_vars.popuptitle, adminjs_script_vars.popupmsg+"</br></br>","");
	var myVartimer = setInterval(myTimer, 1000);
	//var myurlnum = '1';

		var data = {
			action		: 'wpfb_tripadvisor_reviews',
			urlnum	:	myurlnum,
			_ajax_nonce		: adminjs_script_vars.wpfb_nonce,
		};

		var myajax = jQuery.post(ajaxurl, data, function(response) {
		//console.log(response);
		clearInterval(myVartimer);
			if( response != '-1' && ! response != '0' )
			{
				jQuery( "#popup_bobytext2").append(response);
				setTimeout(function(){ updateavatars(); }, 2000);

			}
			else
			{
				jQuery( "#popup_bobytext2").html(response);
				setTimeout(function(){ updateavatars(); }, 2000);

			}
		});
		myajax.always(function( data ) {
			clearInterval(myVartimer);
			jQuery( "#popup_bobytext2").append('. done');
			//alert( "Data Loaded1: " + data );
		});

		
		jQuery(window).unload( function() { myajax.abort(); } );
		

}

function myTimer() {
    jQuery( "#popup_bobytext1").append(' - ');
}

function updateavatars(){
				var senddata = {
					action: 'wpfb_update_avatars',	//required
					wpfb_nonce: adminjs_script_vars.wpfb_nonce,
					};
				jQuery.post(ajaxurl, senddata, function (response){console.log(response);});
}

//launch pop-up windows code--------
function openpopup(title, body, body2){

	//set text
	jQuery( "#popup_titletext").html(title);
	jQuery( "#popup_bobytext1").html(body);
	jQuery( "#popup_bobytext2").html(body2);
	
	var popup = jQuery('#popup').popup({
		width: 600,
		height: 400,
		offsetX: -100,
		offsetY: 0,
	});
	
	popup.open();
}
//--------------------------------


(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 * $( document ).ready(function() same as
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
		 

		//hide retrive buttons on settings change
		
		jQuery( "#tripadvisor_scrape_method" ).on("change",function() {
		  jQuery( ".btn_green" ).hide();
		});



		
	 });

})( jQuery );
