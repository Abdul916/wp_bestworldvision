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

		
		//open pop-up for selecting categories or post ids===============
			$( "#wprevpro_btn_pickcats" ).on("click",function() {
				$( "#selectcatstable" ).hide();
				var url = "#TB_inline?width=600&height=600&inlineId=tb_content_cat_select";
				tb_show(adminjs_script_vars_cta.msg1, url);
				$( "#selectcatstable" ).focus();
				$( "#TB_window" ).css({ "width":"730px","height":"700px","margin-left": "-415px" });
				$( "#TB_ajaxContent" ).css({ "width":"auto","height":"650px","max-height":"650px" });
				//call ajax to get table html
				$( "#tb_content_cat_search" ).hide();
				$( "#tb_content_cat_search_input" ).val('');
				$( "#TB_ajaxContent" ).focus();
				
				$('#TB_closeWindowButton').blur();
							
				$('head').append('<style type="text/css">#TB_window {top:100px !important;margin-top: 50px !important;margin-left: -415px !important;width: 730px !important; height: 700px !important; }</style>');

				getcategoryhtml('cat','name','');
			});
			$( "#wprevpro_btn_pickpostids" ).on("click",function() {
				$( "#selectcatstable" ).hide();
				var url = "#TB_inline?width=600&height=600&inlineId=tb_content_cat_select";
				tb_show(adminjs_script_vars_cta.msg2, url);
				$( "#selectcatstable" ).focus();
				$( "#TB_window" ).css({ "width":"700px","height":"700px","margin-left": "-415px" });
				$( "#TB_ajaxContent" ).css({ "width":"auto","height":"650px","max-height":"650px" });
				
				$('#TB_closeWindowButton').blur();
							
				$('head').append('<style type="text/css">#TB_window {top:100px !important;margin-top: 50px !important;margin-left: -415px !important;width: 730px !important; height: 700px !important; }</style>');

				
				//call ajax to get table html
				$( "#tb_content_cat_search" ).show();
				$( "#tb_content_cat_search_input" ).val('');
				//if this is on float page then filter out page type
				if($("#wprevpro_t_pagefilter"). length){
					getcategoryhtml('posts','name','','yes');
				} else {
				getcategoryhtml('posts','name','');
				}
			});
			$( "#wprevpro_btn_pickpageids" ).on("click",function() {
				$( "#selectcatstable" ).hide();
				var url = "#TB_inline?width=600&height=600&inlineId=tb_content_cat_select";
				tb_show(adminjs_script_vars_cta.msg3, url);
				$( "#selectcatstable" ).focus();
				$( "#TB_window" ).css({ "width":"730px","height":"700px","margin-left": "-415px" });
				$( "#TB_ajaxContent" ).css({ "width":"auto","height":"650px","max-height":"650px" });
				$('#TB_closeWindowButton').blur();
							
				$('head').append('<style type="text/css">#TB_window {top:100px !important;margin-top: 50px !important;margin-left: -415px !important;width: 730px !important; height: 700px !important; }</style>');
				
				//call ajax to get table html
				$( "#tb_content_cat_search" ).show();
				$( "#tb_content_cat_search_input" ).val('');
				getcategoryhtml('pages','name','');
			});
			
			//for search input
			$( "#tb_content_cat_search_input" ).on( "keyup",function() {
				var tempsearchterm = $( "#tb_content_cat_search_input" ).val();
				var temptype = $( this ).attr('idtype');
				if(tempsearchterm.length>1){
					//call getcategoryhtml again with search input.
					console.log( "Handler for .on( keyup) called: "+tempsearchterm );
					getcategoryhtml(temptype,'name',tempsearchterm);
				} else {
					//reset the getcategoryhtml
					getcategoryhtml(temptype);
				}
			});
			
			function getcategoryhtml(idtype,sortbyclick='name',searchterm="",thistypeonly="no"){
				$( ".wprev_loader_catlist" ).show();
				//set type attribute on search input
				$( "#tb_content_cat_search_input" ).attr( 'idtype', idtype )
				//ajax retrieve list of categories and populate table html
				var senddata = {
					action: 'wprp_get_cat_html',	//required
					wpfb_nonce: adminjs_script_vars.wpfb_nonce,
					idtype: idtype,		//either cat or post
					orderby: sortbyclick,
					sterm: searchterm,
					ttypeonly: thistypeonly,
					};
				jQuery.post(ajaxurl, senddata, function (response){
					console.log(response);
					if(response){
						$( "#selectcatstable" ).html(response);
						//find if checked
						findifchecked(idtype);
					} else {
					//must be an error
						var errorhtml = "<tr idtype="+idtype+">"+adminjs_script_vars_cta.msg4+" "+idtype+" .<td>";
						$( "#selectcatstable" ).html(errorhtml);
					}
					$( ".wprev_loader_catlist" ).hide();
					$( "#selectcatstable" ).show();
				});
			}
			
			function findifchecked(idtype){
				var currentvalues='';
				if(idtype=="cat"){
					currentvalues = $('.wprevpro_nr_categories').val();
				} else if(idtype=="posts"){
					currentvalues = $('.wprevpro_nr_postid').val();
				} if(idtype=="pages"){
					currentvalues = $('.wprevpro_nr_pageid').val();
				}

					if(currentvalues!=''){
						var currentvaluesarray = currentvalues.split(",");
					} else {
						var currentvaluesarray = [];
					}
					//check the correct checked boxes, loop through each one and test against arrayindex
					var objectofchks = $( "#selectcatstable" ).find( 'input' );
					console.log(objectofchks);
					$.each( objectofchks, function( key, value ) {
						var tempvalueofinput = objectofchks[key].value;
						if($.inArray(tempvalueofinput, currentvaluesarray) !== -1){
							//alert("found in array"+tempvalueofinput);
							$(this).prop('checked', true);
						}
					});
			}
			//for sorting categories
			$( "#selectcatstable" ).on( "click", "th",function() {
				var sortbyclick = $( this ).text();
				//get cat or post
				var temptype = $( this ).closest('tr').attr( "idtype" );
				getcategoryhtml(temptype,sortbyclick);
			});
			//for clicking the checkbox select on categories and posts
			$( "#selectcatstable" ).on( "click", "input",function() {
				var temptype = $( this ).closest('tr').attr( "idtype" );
				if(temptype=="cat"){
					var currentvalues = $('.wprevpro_nr_categories').val();
				} else if(temptype=="posts"){
					var currentvalues = $('.wprevpro_nr_postid').val();
				}else if(temptype=="pages"){
					var currentvalues = $('.wprevpro_nr_pageid').val();
				}
				
				if(currentvalues!=''){
					var currentvaluesarray = currentvalues.split(",");
				} else {
					var currentvaluesarray = [];
				}
				var tempvalue = $(this).val();
				if($(this).prop('checked')){
					//add to current values
					currentvaluesarray.push(tempvalue);
				} else {
					//remove from current values
					var arrayindex = $.inArray( tempvalue, currentvaluesarray );
					if (arrayindex !== -1) currentvaluesarray.splice(arrayindex, 1);
				}
				//update wprevpro_nr_categories
				console.log(currentvaluesarray);
				currentvaluesarray.toString();
				if(temptype=="cat"){
					$('.wprevpro_nr_categories').val(currentvaluesarray);
				} else if(temptype=="posts"){
					$('.wprevpro_nr_postid').val(currentvaluesarray);
				} else if(temptype=="pages"){
					$('.wprevpro_nr_pageid').val(currentvaluesarray);
				}
								
			});
		
		//======end open pop-up for selecting categories or post ids===============
	
				//simple tooltip for added elements and mobile devices
		$(".wprevpro").on('mouseenter touchstart', '.wprevtooltip', function(e) {
			var titleText = $(this).attr('data-wprevtooltip');
			$(this).data('tiptext', titleText).removeAttr('data-wprevtooltip');
			$('<p class="wprevpro_tooltip"></p>').text(titleText).appendTo('body').css('top', (e.pageY - 15) + 'px').css('left', (e.pageX + 10) + 'px').fadeIn('slow');
		});
		$(".wprevpro").on('mouseleave touchend', '.wprevtooltip', function(e) {
			$(this).attr('data-wprevtooltip', $(this).data('tiptext'));
			$('.wprevpro_tooltip').remove();
		});
		$(".wprevpro").on('mousemove', '.wprevtooltip', function(e) {
			$('.wprevpro_tooltip').css('top', (e.pageY - 15) + 'px').css('left', (e.pageX + 10) + 'px');
		});
		
		
	});

})( jQuery );