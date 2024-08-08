
function getgooglereviewsfunction(pagename) {

	//launch pop-up for progress messages
	openpopup_tb(adminjs_script_vars.popuptitle, adminjs_script_vars.popupmsg+"</br></br>","");

		var data = {
			action		: 'wpfbr_google_reviews',
			_ajax_nonce		: adminjs_script_vars.wpfb_nonce,
		};

		var myajax = jQuery.post(ajaxurl, data, function(response) {
			console.log(response);
			jQuery("#wpcvt_goals_ajax").hide();
			if( response != '-1' && ! response != '0' )
			{
				jQuery( "#popup_bobytext2").append(response);
				//check for new avatars
				setTimeout(function(){ updateavatars(); }, 2000);
				
			}
			else
			{
				jQuery( "#popup_bobytext2").html(response);
				//check for new avatars
				setTimeout(function(){ updateavatars(); }, 2000);
			}
		});
		//jQuery(window).unload( function() { myajax.abort(); } );
		jQuery(window).on( "unload", function() { myajax.abort(); } );
		
		
}

function updateavatars(){
				var senddata = {
					action: 'wpfb_update_avatars',	//required
					wpfb_nonce: adminjs_script_vars.wpfb_nonce,
					};
				jQuery.post(ajaxurl, senddata, function (response){});
}

//launch pop-up windows code--------
function openpopup_tb(title, body, body2){

	//set text
	jQuery( "#popup_titletext").html(title);
	jQuery( "#popup_bobytext1").html(body);
	jQuery( "#popup_bobytext2").html(body2);
var url = "#TB_inline?width=600&height=600&inlineId=popupbody";
	tb_show(title, url);
	jQuery( "#TB_closeWindowButton" ).blur();
	jQuery( "#TB_window" ).css({ "width":"600px","height":"500px","margin-left": "-300px" });
	jQuery( "#TB_ajaxContent" ).css({ "width":"auto","height":"450px","max-height":"450px" });
				
	$('head').append('<style type="text/css">#TB_window {top:250px !important;margin-top: 50px !important;margin-left: -300px !important;width: 600px !important; height: 500px !important; }</style>');			
				
}

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
		 
		 
		var currentloc = 1;
		
		//function wpfbr_testapikey(pagename) {
		jQuery("#wpfbr_testgooglekey").on("click",function(event){
			
			//map
			//var autocomplete = new google.maps.places.Autocomplete(input[index]);
			
			//launch pop-up for progress messages
			openpopup_tb("Testing API Key Against Google Server", "","");
			
			var data = {
			action		: 'wpfbr_testing_api',
			apikey:google_api_key,
			_ajax_nonce		: adminjs_script_vars.wpfb_nonce,
				};

				var myajax = jQuery.post(ajaxurl, data, function(response) {
					console.log(response);
					if( response != '-1' && ! response != '0' )
					{
						jQuery( "#popup_bobytext2").append(response);
					}
					else
					{
						jQuery( "#popup_bobytext2").html(response);
					}
				});
				//jQuery(window).unload( function() { myajax.abort(); } );
				jQuery(window).on( "unload", function() { myajax.abort(); } );

		});
		

		jQuery( "#wpfbr_google_location_set_place_id" ).on("change",function() {
		  jQuery( "#wpfbr_getgooglereviews" ).hide();
		});

		
		//hide location type and search boxes, show on button click
		jQuery('.wpfbr_loctype').hide();
		jQuery('.wpfbr_locsearch').hide();
		 
		 
		//look-up btn clicks
		jQuery('#wpfbr_btn_lookup_google_location_set').on("click",function(event){
			currentloc = 1;
			//show look up form
			jQuery('.wpfbr_loctype').show('slow');
			jQuery('.wpfbr_locsearch').show('slow');
		});
		
		jQuery('.locationlookupbtn').on("click",function(event){
			
			currentloc = $(this).attr( 'currentlocbtn' );
			currentloc = currentloc.replace("google_location_set", "");
			//show look up form
			jQuery('.wpfbr_loctype').show('slow');
			jQuery('.wpfbr_locsearch').show('slow');
			jQuery( "#google_location_txt" ).focus();
		});

		
		//------------ google --------------
		jQuery("#fb_create_google_app").on("click",function(event){
			window.open('https://developers.google.com/places/web-service/');
		});

		var default_google_api_key = "AIzaSyCMJzaJssj4ugQjJ0YZCAwFfUcagsmxncQ";
		
		var google_api_key = jQuery("#google_api_key" ).val();
		
		//for selecting which key to use
		jQuery(".select_google_api").on("change",function(event){
			setapikeyonchange();
		});
		//when modifying api key
		$( "#google_api_key" ).on("change",function() {
		  setapikeyonchange();
		});
		
		
		//hide stuff if api key is blank, so user will now use default key of mine.
		setapikeyonchange();
		
		function setapikeyonchange(){
			if(jQuery("#select_google_api").val()=="default"){
				jQuery(".usedefaultkey").show();
				jQuery(".showapikey").hide();
			} else {
				jQuery(".usedefaultkey").hide();
				jQuery(".showapikey").show();
			}
				if(jQuery("#google_api_key" ).val()=='' || jQuery("#select_google_api").val()=="default"){
					google_api_key = default_google_api_key;
				} else {
					google_api_key = jQuery("#google_api_key" ).val();
				}
		}

		window.gm_authFailure = function() {
			$('#wpfbr_result').after('<div class="notice wpfbr-notice-error error"><p>' + adminjs_script_vars.i18n.google_auth_error + '</p></div>');
		};

		if(google_api_key!=''){
			$(document).ajaxSuccess(function (e, xhr, settings) {
				wpfbr_initialize_autocomplete();
			});
			//$(window).load(function () {
			//	wpfbr_initialize_autocomplete();
			//});
			$(window).on('load',function () {
				wpfbr_initialize_autocomplete();
			});
		}

		/**
		 * Initialize Google Places Autocomplete Field
		 */
		function wpfbr_initialize_autocomplete() {
			var input = $('#google_location_txt');
			var types = $('#google_location_type');

			input.each(function (index, value) {
				var autocomplete = new google.maps.places.Autocomplete(input[index]);

				//Handle type select field
				$(types).on('change', function () {
					//Set type
					var type = $(this).val();
					autocomplete.setTypes([type]);
					$(input[index]).val('');
					$("#wpfbr_location, #wpfbr_place_id").val('');
				});
				add_autocomplete_listener(autocomplete, input[index]);

				//Tame the enter key to not save the widget while using the autocomplete input
				$(input).keydown(function (e) {
					if (e.which == 13) return false;
				});
			});
		}


		/**
		 * Google Maps API Autocomplete Listener
		 *
		 * @param autocomplete
		 * @param input
		 */
		function add_autocomplete_listener(autocomplete, input) {
			google.maps.event.addListener(autocomplete, 'place_changed', function () {
				var place = autocomplete.getPlace();
				console.log(place);
				if (!place.place_id) {
					alert('No place reference found for this location.');
					return false;
				}
				//set location and Place ID hidden input value
				if(currentloc==1){
					$('#wpfbr_google_location_set_location').val(place.name);
					$('#wpfbr_google_location_set_place_id').val(place.place_id);
					$('#wpfbr_google_location_set_place_id_address').val(place.formatted_address);
				} else {
					$('#wpfbr_google_location_set'+currentloc+'_location').val(place.name);
					$('#wpfbr_google_location_set'+currentloc+'_place_id').val(place.place_id);
					$('#wpfbr_google_location_set'+currentloc+'_address').val(place.formatted_address);
					$('#wpfbr_getgooglereviews').hide();
					jQuery('#wpfbr_google_location_set'+currentloc+'_location').focus();
				} 
				
				//hide form
				jQuery('.wpfbr_loctype').hide('slow');
			jQuery('.wpfbr_locsearch').hide('slow');
				
			});
		}



		
	 });

})( jQuery );
