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
		 
		 
		 
		$( ".popupchoice" ).on("click",function(event) {
			console.log(adminjs_script_vars);
			event.preventDefault();
			//find the type
			//id format: google_choosepopupdiv
			var rtype = $(this).attr('data-type').toLowerCase();
			
			console.log(rtype);
			
			var tbheight = "600";
			if(rtype=='google'){
				tbheight = "500";
			} else if(rtype=='airbnb' || rtype=='angieslist'){
				tbheight = "360";
			} else if(rtype=='facebook'){
				tbheight = "400";
			} else if(rtype=='tripadvisor'){
				tbheight = "395";
			} else if(rtype=='yelp'){
				tbheight = "360";
			} else if(rtype=='zillow'){
				tbheight = "400";
			}

			var url = "#TB_inline?width=930&height="+tbheight+"&inlineId="+rtype+"_choosepopupdiv";
			tb_show(adminjs_script_vars.choosemethod, url);
			$( "#TB_window" ).css({ "height":"auto !important" });
			$( "#TB_ajaxContent" ).css({ "height":"auto !important" });
			$( "#TB_ajaxContent" ).css({ "width":"auto" });
			$( "#TB_ajaxContent" ).focus();
			$('#TB_closeWindowButton').blur();
			
			$('head').append('<style type="text/css">#TB_window {top:100px !important;margin-top: 50px !important;margin-left: -480px !important;width: 960px !important; height: 655px !important; }#TB_ajaxContent {height:auto !important;}</style>');



		});
		
		//help button clicked
		$( "#wprevpro_helpicon_posts" ).on("click",function() {
			console.log(adminjs_script_vars);
			var url = "#TB_inline?width=400&height=200&inlineId=helppopup";
			tb_show("", url);
			$('#TB_closeWindowButton').blur();
			
			$('head').append('<style type="text/css">#TB_window {top:200px !important;margin-top: 50px !important;margin-left: -215px !important;width: 430px !important; height: 230px !important; }</style>');
			
		});
		//add new source
		$( "#wprevpro_addnewtemplate" ).on("click",function() {
			console.log(adminjs_script_vars);
			$( ".chsourcesitediv" ).toggle('slow');
			

		});
		
		//create new datatable, https://datatables.net/
		$('#revsourcetbl').DataTable({
        order: [[0, 'desc']],
		"pageLength": 20,
    });



		
	 });

})( jQuery );
