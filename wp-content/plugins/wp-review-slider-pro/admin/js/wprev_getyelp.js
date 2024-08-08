
function getyelpreviewsfunction(myurlnum) {

	//launch pop-up for progress messages
	openpopup(adminjs_script_vars.popuptitle, adminjs_script_vars.popupmsg+"</br></br>","");
	var myVar = setInterval(myTimer, 1000);

		var data = {
			action		: 'wpfb_yelp_reviews',
			urlnum	:	myurlnum,
			_ajax_nonce		: adminjs_script_vars.wpfb_nonce,
		};

		var myajax = jQuery.post(ajaxurl, data, function(response) {
			console.log(response);
			clearInterval(myVar);
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
		}).fail(function() {
			alert( "error" );
			clearInterval(myVar);
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
				jQuery.post(ajaxurl, senddata, function (response){});
}

//launch pop-up windows code--------
function openpopup(title, body, body2){

	//set text
	jQuery( "#popup_titletext").html(title);
	jQuery( "#popup_bobytext1").html(body);
	jQuery( "#popup_bobytext2").html(body2);
	
	var popup = jQuery('#popup').popup({
		width: 400,
		height: 200,
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
		 
 
		//hide additional location boxes first if not empty
		if(jQuery('#yelp_business_url2').val()==""){
			jQuery('.yelpurl2').hide();
		}
		if(jQuery('#yelp_business_url3').val()==""){
			jQuery('.yelpurl3').hide();
		}
		if(jQuery('#yelp_business_url4').val()==""){
			jQuery('.yelpurl4').hide();
		}


		//add more loc click
		jQuery('#yelpmoreurls').on("click",function(event){
			jQuery('.yelpurl2').show('slow');

	
		});
		jQuery('#yelpmoreurls_yelp_business_url2').on("click",function(event){
			jQuery('.yelpurl3').show('slow');
		});
		jQuery('#yelpmoreurls_yelp_business_url3').on("click",function(event){
			jQuery('.yelpurl4').show('slow');
		});
	



		
	 });

})( jQuery );
