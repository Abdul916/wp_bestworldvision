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

		var prestyle = "";
		var formid = 'new';
		//color picker
		var myOptions = {
			// a callback to fire whenever the color changes to a valid color
			change: function(event, ui){
				//var color = ui.color.toString();
				var color = ui.color.toCSS( 'rgb' );
				var element = event.target;
				var curid = $(element).attr('id');
				$( element ).val(color)
				changepreviewhtml();
				changeslideoutstyle();
				//alert('here');
			},
			// a callback to fire when the input is emptied or an invalid color
			clear: function() {}
		};
		 
		$('.my-color-field').wpColorPicker(myOptions);
		
		//hide or show select badge or template rows, wprevpro_float_type
		$('input[type=radio][name=wprevpro_float_type]').on("change",function() {
			if (this.value == 'badge') {
				//display the badge select, hide the review select
				$('.reviewtemplateselectrow').hide();
				$('.badgeselectrow').show('3000');
				$( ".wprevpro_badge_container" ).html('');
			} else if (this.value == 'reviews') {
				$('.badgeselectrow').hide();
				$('.reviewtemplateselectrow').show('3000');
				$('.floatslider').show();
				$('.floatpop').hide();
				$( ".wprevpro_badge_container" ).html('');
			} else if (this.value == 'pop') {
				$('.badgeselectrow').hide();
				$('.reviewtemplateselectrow').show('3000');
				$('.floatslider').hide();
				$('.floatpop').show();
				$( ".wprevpro_badge_container" ).html('');
			}
			changepreviewhtml();
			getfloatdata(formid);
		});
		
		

		//$( "#wprevpro_float_misc_bgcolor1" ).on("change",function() {
		//		changepreviewhtml();
		//});
		//$( "#wprevpro_float_misc_bordercolor1" ).on("change",function() {
		//		changepreviewhtml();
		//});
		$( "#wprevpro_badge_id" ).on("change",function() {
			if($(this).val()>0){
				changepreviewhtml();
				getfloatdata(formid);
			}
		});
		$( "#wprevpro_review_t_id" ).on("change",function() {
			if($(this).val()>0){
				changepreviewhtml();
				getfloatdata(formid);
			}
		});
		
		//for displaying and hiding page select button
		$( "#wprevpro_t_pagefilter" ).on("change",function() {
			if($(this).val()=='choose' || $(this).val()=='allex'){
				$( ".selectpagesspan" ).show();
			} else {
				$( ".selectpagesspan" ).hide();
			}
		});
		$( "#wprevpro_t_postfilter" ).on("change",function() {
			if($(this).val()=='choose'){
				$( ".selectpostsspan" ).show();
			} else {
				$( ".selectpostsspan" ).hide();
			}
			if($(this).val()=='cats'){
				$( ".selectpostsspancat" ).show();
			} else {
				$( ".selectpostsspancat" ).hide();
			}
		});
		
		floatlocationflyincheck();
		function floatlocationflyincheck(){
			var floatlocation = $( "#wprevpro_float_misc_floatlocation" ).val();
			$("select#wprevpro_float_misc_animate_dir option[value='left']").attr('disabled',false); 
			$("select#wprevpro_float_misc_animate_dir option[value='right']").attr('disabled',false); 
			//set for different locations
			if(floatlocation=='btmrt'){
				//disable leftflying
				$("select#wprevpro_float_misc_animate_dir option[value='left']").attr('disabled',"disabled"); 
			} else if(floatlocation=='btmmd'){
				$("select#wprevpro_float_misc_animate_dir option[value='left']").attr('disabled',"disabled"); 
				$("select#wprevpro_float_misc_animate_dir option[value='right']").attr('disabled',"disabled"); 
			} else if(floatlocation=='btmlft'){
				$("select#wprevpro_float_misc_animate_dir option[value='right']").attr('disabled',"disabled"); 
			} else if(floatlocation=='toplft'){
				$("select#wprevpro_float_misc_animate_dir option[value='right']").attr('disabled',"disabled"); 
			} else if(floatlocation=='topmd'){
				$("select#wprevpro_float_misc_animate_dir option[value='left']").attr('disabled',"disabled"); 
				$("select#wprevpro_float_misc_animate_dir option[value='right']").attr('disabled',"disabled"); 
			} else if(floatlocation=='toprt'){
				$("select#wprevpro_float_misc_animate_dir option[value='left']").attr('disabled',"disabled"); 
			}
		}
		$( "#wprevpro_float_misc_animate_dir" ).on("change",function() {
			floatlocationflyincheck();
		});
				
		//custom css change preview
		var lastValue = '';
		$("#wprevpro_float_css").on('change keyup paste mouseup', function() {
			if ($(this).val() != lastValue) {
				lastValue = $(this).val();
				changepreviewhtml();
			}
		});
		//change float location
		$( "#wprevpro_float_misc_floatlocation" ).on("change",function() {
			floatlocationflyincheck();
			changepreviewhtml();
		});
		//for margin and padding changes
		$( ".marginpaddinginput" ).on("change",function() {
			changepreviewhtml();
		});
		//for badge clicking to url
		$( "#wprevpro_float_misc_onclickaction" ).on("change",function() {
			changepreviewhtml();
		});
		$( ".wprevpro_badge_container" ).on("click",function() {
			var onclickaction = $( "#wprevpro_float_misc_onclickaction" ).val();
			var onclickurl = $( "#wprevpro_float_misc_onclickurl" ).val();
			if(!$(event.target).closest('.wprs_unslider-arrow').length && !$(event.target).closest('.wprs_rd_less').length && !$(event.target).closest('.wprs_rd_more').length && !$(event.target).closest('.wprs_unslider-nav').length) {
				if(onclickaction=='url'){
					if(onclickurl!=""){
						var win = window.open(onclickurl, '_blank');
						if (win) {
							//Browser has allowed it to be opened
							win.focus();
						} else {
							//Browser has blocked it
							alert(adminjs_script_vars.msg1);
						}
					} else {
						alert(adminjs_script_vars.msg2);
					}
					return false;
				} else if(onclickaction=='slideout'){
					//slideout the reviews from the side 
					$('.wprevpro_slideout_container').show();
					return false;
				} else if(onclickaction=='popup'){
					//slideout the reviews from the side 
					changeslideoutstyle()
					$('.wprevpro_popup_container').show();
					return false;
				}
			}

		});
		
		//---------
		
		function changepreviewhtml(){
			
			var bname = $( "#wprevpro_float_bname" ).val();
			var floatlocation = $( "#wprevpro_float_misc_floatlocation" ).val();
			var bgcolor1 = $( "#wprevpro_float_misc_bgcolor1" ).val();
			var bordercolor1 = $( "#wprevpro_float_misc_bordercolor1" ).val();
			var floatwidth = Number($( "#wprevpro_float_misc_width" ).val());
			var customcss = $( "#wprevpro_float_css" ).val();
			var floatmarginarray = [$( "#wprevpro_float_misc_margin-top" ).val(), $( "#wprevpro_float_misc_margin-right" ).val(), $( "#wprevpro_float_misc_margin-bottom" ).val(),$( "#wprevpro_float_misc_margin-left" ).val()];
			var floatpaddingarray = [$( "#wprevpro_float_misc_padding-top" ).val(), $( "#wprevpro_float_misc_padding-right" ).val(), $( "#wprevpro_float_misc_padding-bottom" ).val(),$( "#wprevpro_float_misc_padding-left" ).val()];

			//console.log(floatmarginarray);
			
			//set class wprev_pro_float_outerdiv by inserting style in to div id wprevpro_badge_container_style 
			var lochtml = '';
			var middleoffset = floatwidth/2;
			if(floatlocation=="btmrt"){
				lochtml = 'bottom:10px;right:10px;top:unset;left:unset;';
			} else if(floatlocation=="btmmd"){
				lochtml = 'bottom: 10px;right: unset;top:unset;left:50%;margin-left:-'+middleoffset+'px;';
			} else if(floatlocation=="btmlft"){
				lochtml = 'bottom: 10px;left: 10px;top:unset;right:unset;';
			} else if(floatlocation=="toplft"){
				lochtml = 'top: 10px;left: 10px;bottom:unset;right:unset;';
			} else if(floatlocation=="topmd"){
				lochtml = 'top: 10px;right: unset;bottom:unset;left:50%;margin-left:-'+middleoffset+'px;';
			} else if(floatlocation=="toprt"){
				lochtml = 'top: 10px;right: 10px;bottom:unset;left:unset;';
			}
			
			//set colors
			if(bgcolor1!=''){
				lochtml = lochtml + 'background: '+bgcolor1+';';
			}
			if(bordercolor1!=''){
				lochtml = lochtml + 'border: 1px solid '+bordercolor1+';';
			}
			//update width  width: 350px;
			if(floatwidth>0){
				lochtml = lochtml + 'width: '+floatwidth+'px;';
			}
			//update margins
			var arrayLength = floatmarginarray.length;
			var tempstyletext='';
			for (var i = 0; i < arrayLength; i++) {
				if(floatmarginarray[i]!=''){
					if(i==0){
						tempstyletext = tempstyletext + 'margin-top:' + floatmarginarray[i] + 'px; ';
					} else if(i==1){
						tempstyletext = tempstyletext + 'margin-right:' + floatmarginarray[i] + 'px; ';
					} else if(i==2){
						tempstyletext = tempstyletext + 'margin-bottom:' + floatmarginarray[i] + 'px; ';
					} else if(i==3){
						tempstyletext = tempstyletext + 'margin-left:' + floatmarginarray[i] + 'px; ';
					}
				}
				//Do something
			}
			lochtml = lochtml + tempstyletext;
			
			//update paddings
			var arrayLength = floatpaddingarray.length;
			var tempstyletext='';
			var closexfix='';
			for (var i = 0; i < arrayLength; i++) {
				if(floatpaddingarray[i]!=''){
					if(i==0){
						tempstyletext = tempstyletext + 'padding-top:' + floatpaddingarray[i] + 'px; ';
						closexfix='#wprev_pro_closefloat_new {margin-top: ' + floatpaddingarray[i] + 'px;}';
					} else if(i==1){
						tempstyletext = tempstyletext + 'padding-right:' + floatpaddingarray[i] + 'px; ';
					} else if(i==2){
						tempstyletext = tempstyletext + 'padding-bottom:' + floatpaddingarray[i] + 'px; ';
					} else if(i==3){
						tempstyletext = tempstyletext + 'padding-left:' + floatpaddingarray[i] + 'px; ';
					}
				}
			}
			
			//if on click setting is url add pointer style
			var onclickaction = $( "#wprevpro_float_misc_onclickaction" ).val();
			var onclickurl = $( "#wprevpro_float_misc_onclickurl" ).val();
			if(onclickaction=='url' || onclickaction=="slideout"){
				tempstyletext = tempstyletext + ' cursor: pointer;';
			}
			
			lochtml = lochtml + tempstyletext;
			var locstyle = '.wprev_pro_float_outerdiv {'+lochtml+'}';
			var formstyle = '<style>'+locstyle+customcss+closexfix+'</style>';
			$( ".wprevpro_badge_container_style" ).html(formstyle);

		}
		
		//check to see if anything being shown call if not
		
		if($( ".wprevpro_badge_container" ).html()==''){
			getfloatdata();
		}
		//get float data and add to preview div----------------
		function getfloatdata(){
			$( ".loading-image" ).show();
			var wtfloatid='';
			var wtfloattype='';
			wtfloattype=$('input[type=radio][name=wprevpro_float_type]:checked').val();
			if(wtfloattype == 'badge'){
				var wtfloatid = $( "#wprevpro_badge_id" ).val();
			} else if(wtfloattype == 'reviews' || wtfloattype == 'pop'){
				var wtfloatid = $( "#wprevpro_review_t_id" ).val();
			}
			var senddata = {
				action: 'wprp_get_float',	//required
				wpfb_nonce: adminjs_script_vars.wpfb_nonce,
				fid: formid,
				wtfid: wtfloatid,
				wtftype: wtfloattype,
				};
				console.log(senddata);
			//send to ajax to update db
			var jqxhr = jQuery.post(ajaxurl, senddata, function (response){
				$( ".loading-image" ).hide();
				//console.log(response);
				

				if (!$.trim(response)){
					console.log(adminjs_script_vars.msg3);
				} else {
					//add to preview div
					$( ".wprevpro_badge_container" ).html(response);
					
						//create slick slider if we need to.
						var thisslick = $( ".wprevpro_badge_container" ).find( "#wprevgoslickid_"+wtfloatid );
						if($(thisslick).length){
							$(thisslick).show();
							var options = {};
							$( thisslick ).slickwprev(options);
						}
						
						//create pop-in/out if we need to
						//var thispop = $( ".wprevpro_badge_container" ).find( ".wprevpro_outerrevdivpop" );
						//if($(thispop).length){
						//	$(thispop).show();
						//}
						
				}
				// on success refresh from preview
			});
			jqxhr.fail(function() {
			  console.log( adminjs_script_vars.msg4 );
			});
		}
		
		function setupslickpreview(){
			alert('heresetupslik');
			//var thisslick = $( "#"+thisid );
			//createaslick(thisslick);
			
		}
		
		
		//get slide out data and add to preview div----------------
		if($('.wprevpro_slideout_container_body').html()=='' && $( "#wprevpro_float_misc_onclickaction" ).val()=='slideout'){
			if($( "#wprevpro_float_misc_sliderevtemplate" ).val()>0){
				getslideoutdata();
			}
		}
		//get popup data if needed
		if($('.wprevpro_popup_container_body').html()=='' && $( "#wprevpro_float_misc_onclickaction" ).val()=='popup'){
			if($( "#wprevpro_float_misc_sliderevtemplate" ).val()>0){
				getslideoutdata();
			}
		}
		$( "#wprevpro_float_misc_sliderevtemplate" ).on("change",function() {
			getslideoutdata();
		});
		function getslideoutdata(){
			$( ".loading-image2" ).show();
			var revtemplateid='';
			revtemplateid = $( "#wprevpro_float_misc_sliderevtemplate" ).val();

			var senddata = {
				action: 'wprp_get_slideout_revs',	//required
				wpfb_nonce: adminjs_script_vars.wpfb_nonce,
				fid: formid,
				rtid: revtemplateid,
				};
				//send to ajax to update db
			var jqxhr = jQuery.post(ajaxurl, senddata, function (response){
				$( ".loading-image2" ).hide();
				//console.log(response);

				if (!$.trim(response)){
					alert(adminjs_script_vars.msg5);
				} else {
					//add to preview div
					if($( "#wprevpro_float_misc_onclickaction" ).val()=='slideout'){
						$( ".wprevpro_slideout_container_body" ).html(response);
					} else if($( "#wprevpro_float_misc_onclickaction" ).val()=='popup'){
						$( ".wprevpro_popup_container_body" ).html(response);
					}
				}
				// on success refresh from preview
				changeslideoutstyle();
			});
			jqxhr.fail(function() {
			  alert( adminjs_script_vars.msg5 );
			});
		}
		
		
		//update slide style when changing
		$( "#wprevpro_float_misc_slidelocation" ).on("change",function() {
			changeslideoutstyle();
			//hide inputs based on value
			
			if($(this).val()=="top" || $(this).val()=="bottom"){
				$( ".slwidthdiv" ).hide();
				$( ".slheightdiv" ).show();
			} else {
				$( ".slheightdiv" ).hide();
				$( ".slwidthdiv" ).show();
			}
			
		});
		$( ".updatesliderinput" ).on("change",function() {
			changeslideoutstyle();
		});
		$('#wprevpro_float_misc_slideheader').on('input selectionchange propertychange', function() {
		  changeslideoutstyle();
		});
		$('#wprevpro_float_misc_slidefooter').on('input selectionchange propertychange', function() {
		  changeslideoutstyle();
		});
		
		function changeslideoutstyle(){
			
			//is this a popup or slideout
			var onclickaction = $( "#wprevpro_float_misc_onclickaction" ).val();
			// onclickaction will equal no, url, slideout, or popup
			
			var bname = $( "#wprevpro_float_bname" ).val();
			var slidelocation = $( "#wprevpro_float_misc_slidelocation" ).val();
			
			var slideheight = $( "#wprevpro_float_misc_slheight" ).val();
			if(slideheight==""){
				slideheight='auto;';
			} else {
				slideheight=slideheight+'px;';
			}
			var slidewidth = $( "#wprevpro_float_misc_slwidth" ).val();
			if(slidewidth==""){slidewidth=350;}
			
			//background color
			var lochtml='';
			var bgcolor1 = $( "#wprevpro_float_misc_slbgcolor1" ).val();
			if(bgcolor1!=''){
				lochtml = lochtml + 'background: '+bgcolor1+';';
			}
			var bgborderwidth = Number($( "#wprevpro_float_misc_slborderwidth" ).val());
			if(bgborderwidth==''){
				bgborderwidth = 1;
			} 
			var bgbordercolor1 = $( "#wprevpro_float_misc_slbordercolor1" ).val();
			if(bgbordercolor1!=''){
				lochtml = lochtml + 'border: '+bgborderwidth+'px solid '+bgbordercolor1+';';
			}
			

			if(onclickaction=='popup'){
				//var bodystyle = '.wprevpro_popup_container_body {'+tempstyletext+'}';
				
				//lochtml = 'width: '+slidewidth+'px;height: '+slideheight;
				
				var locstyle = '.wprevpro_popup_container_inner {'+lochtml+'}';
				var formstyle = '<style>'+locstyle+'</style>';
				$( ".wprevpro_popup_container_style" ).html(formstyle);
				
				//add the header and footer html
				var headerhtml = $( "#wprevpro_float_misc_slideheader" ).val();
				$( ".wprevpro_popup_container_header" ).html(headerhtml);
				var footerhtml = $( "#wprevpro_float_misc_slidefooter" ).val();
				$( ".wprevpro_popup_container_footer" ).html(footerhtml);
			} else {
				
				if(slidelocation=="right"){
					lochtml = lochtml + 'border-right-width:0px; border-bottom-width:0px;';
					lochtml = lochtml + 'bottom: 0px;right: 0px;height: 100%;width: '+slidewidth+'px;';
				} else if(slidelocation=="left"){
					lochtml = lochtml + 'border-left-width:0px; border-bottom-width:0px;';
					lochtml = lochtml + 'bottom: 0px;left: 0px;height: 100%;width: '+slidewidth+'px;';
				} else if(slidelocation=="top"){
					lochtml = lochtml + 'border-left-width:0px; border-right-width:0px;border-top-width:0px;';
					lochtml = lochtml + 'top: 0px;bottom:unset;width: 100%;height: '+slideheight;
				} else if(slidelocation=="bottom"){
					lochtml = lochtml + 'border-left-width:0px; border-right-width:0px;border-bottom-width:0px;';
					lochtml = lochtml + 'top:unset;bottom: 0px;width: 100%;height: '+slideheight;
				}
			
				//slide padding
				var slidepaddingarray = [$( "#wprevpro_float_misc_slpadding-top" ).val(), $( "#wprevpro_float_misc_slpadding-right" ).val(), $( "#wprevpro_float_misc_slpadding-bottom" ).val(),$( "#wprevpro_float_misc_slpadding-left" ).val()];
				var arrayLength = slidepaddingarray.length;
				var tempstyletext='';
				for (var i = 0; i < arrayLength; i++) {
					if(slidepaddingarray[i]!=''){
						if(i==0){
							tempstyletext = tempstyletext + 'padding-top:' + slidepaddingarray[i] + 'px; ';
						} else if(i==1){
							tempstyletext = tempstyletext + 'padding-right:' + slidepaddingarray[i] + 'px; ';
						} else if(i==2){
							tempstyletext = tempstyletext + 'padding-bottom:' + slidepaddingarray[i] + 'px; ';
						} else if(i==3){
							tempstyletext = tempstyletext + 'padding-left:' + slidepaddingarray[i] + 'px; ';
						}
					}
				}
			
				var bodystyle = '.wprevpro_slideout_container_body {'+tempstyletext+'}';
				var locstyle = '.wprevpro_slideout_container {'+lochtml+'}';
				var formstyle = '<style>'+locstyle+bodystyle+'</style>';
				$( ".wprevpro_slideout_container_style" ).html(formstyle);
				
				//add the header and footer html 
				//var headerhtml = $( "#wprevpro_float_misc_slideheader" ).val();
				var headerhtml = tinymce.get("wprevpro_float_misc_slideheader").getContent();
				console.log(headerhtml);
				$( ".wprevpro_slideout_container_header" ).html(headerhtml);
				//var footerhtml = $( "#wprevpro_float_misc_slidefooter" ).val();
				var footerhtml = tinymce.get("wprevpro_float_misc_slidefooter").getContent();
				$( ".wprevpro_slideout_container_footer" ).html(footerhtml);
			}

		}
		
		//for updating preview when text editor is changed
        setTimeout(function () {
            for (var i = 0; i < tinymce.editors.length; i++) {
                tinymce.editors[i].on("change",function (ed, e) {
					//console.log(this.getContent());

                    changeslideoutstyle();
                });
            }
        }, 1000);
		
		
		//close slideout onclick on everything but it
		$(document).on("click",function(event) { 
		changeslideoutstyle();
			if(!$(event.target).closest('.wprevpro_slideout_container').length && !$(event.target).closest('.updatesliderinput').length) {
				if($('.wprevpro_slideout_container').is(":visible")) {
					$('.wprevpro_slideout_container').hide();
				}
			}        
		});
		
		//float click setting
		//for margin and padding changes
		$( "#wprevpro_float_misc_onclickaction" ).on("change",function() {
			if($(this).val()=="url"){
				$( ".linktourl" ).show('slow');
				$( ".slidouttr" ).hide();
			} else if($(this).val()=="slideout"){
				$( ".slidouttr" ).show('slow');
				$( ".linktourl" ).hide();
				$( ".slideoutlocationsetting" ).show();
				$( ".slwidthdiv" ).show();
				$( ".slheightdiv" ).show();
				$( ".paddingdiv" ).show();
			} else if($(this).val()=="popup"){
				$( ".slidouttr" ).show('slow');
				$( ".linktourl" ).hide();
				$( ".slideoutlocationsetting" ).hide();
				$( ".slwidthdiv" ).hide();
				$( ".slheightdiv" ).hide();
				$( ".paddingdiv" ).hide();
				
			} else {
				$( ".slidouttr" ).hide('slow');
				$( ".linktourl" ).hide('slow');
			}

		});
		
		//now hide/show width or heigt if needed
		$( "#wprevpro_float_misc_slidelocation" ).on("change",function() {	
			if($('#wprevpro_float_misc_slidelocation').val()=="top" || $('#wprevpro_float_misc_slidelocation').val()=="bottom"){
				$( ".slwidthdiv" ).hide();
				$( ".slheightdiv" ).show();
			} else {
				$( ".slheightdiv" ).hide();
				$( ".slwidthdiv" ).show();
			}
		});

		//help button clicked
		$( "#wprevpro_helpicon_posts" ).on("click",function() {
		  openpopup(adminjs_script_vars.popuptitle, '<p>'+adminjs_script_vars.popupmsg+'</p>', "");
		});
		//display shortcode button click 
		$( ".wprevpro_displayshortcode" ).on("click",function() {
			//get id and float type
			var tid = $( this ).parent().attr( "templateid" );
			var ttype = $( this ).parent().attr( "templatetype" );

			openpopup(adminjs_script_vars.popuptitle1, ''+adminjs_script_vars.popupmsg1+' [wprevpro_usefloat tid="'+tid+'"]', '');
		  
		});
		
		
		//launch pop-up windows code--------
		function openpopup(title, body, body2){

			//set text
			jQuery( "#popup_titletext").html(title);
			jQuery( "#popup_bobytext1").html(body);
			jQuery( "#popup_bobytext2").html(body2);
			
			var popup = jQuery('#popup_review_list').popup({
				width: 400,
				offsetX: -100,
				offsetY: 0,
			});
			
			popup.open();
			//set height
			var bodyheight = Number(jQuery( ".popup-content").height()) + 10;
			jQuery( "#popup_review_list").height(bodyheight);

		}
		//--------------------------------
		//get the url parameter-----------
		function getParameterByName(name, url) {
			if (!url) {
			  url = window.location.href;
			}
			name = name.replace(/[\[\]]/g, "\\$&");
			var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
				results = regex.exec(url);
			if (!results) return null;
			if (!results[2]) return '';
			return decodeURIComponent(results[2].replace(/\+/g, " "));
		}
		//---------------------------------
		
		//hide or show new template form ----------
		var checkedittemplate = getParameterByName('taction'); // "lorem"
		if(checkedittemplate=="edit"){
			jQuery("#wprevpro_new_template").show("slow");
		} else {
			jQuery("#wprevpro_new_template").hide();
		}
		
		$( "#wprevpro_addnewtemplate" ).on("click",function() {
		  jQuery("#wprevpro_new_template").show("slow");
		});	
		$( "#wprevpro_addnewtemplate_cancel" ).on("click",function() {
		  jQuery("#wprevpro_new_template").hide("slow");
		  //reload page without taction and tid
		  setTimeout(function(){ 
			window.location.href = "?page=wp_pro-float"; 
		  }, 500);
		  
		});	
		
		//-------------------------------
		//form validation 
		$("#wprevpro_submittemplatebtn").on("click",function(){
			if(jQuery( "#wprevpro_template_title").val()==""){
				alert("Please enter a title.");
				$( "#wprevpro_template_title" ).focus();
				return false;
			}
			return true;
		});
		
		
		//wprevpro_btn_pickpages open thickbox----------------
		/*
		$( "#wprevpro_btn_pickpages" ).on("click",function() {
			var url = "#TB_inline?width=600&height=600&inlineId=tb_content_page_select";
			tb_show("Choose the review orgin page or leave blank to select all of this type.", url);
			$( "#selectrevstable" ).focus();
			$( "#TB_window" ).css({ "width":"830px","margin-left": "-415px" });
			$( "#TB_ajaxContent" ).css({ "width":"800px" });
		});
		//when checking a page check box. update number selected
		$( ".pageselectclass" ).on("click",function() {
			var totalselected = $('input.pageselectclass:checked').length;
			if(Number(totalselected)<2){
				var newhtml = " ("+totalselected+" Page Selected)";
			} else {
				var newhtml = " ("+totalselected+" Pages Selected)";
			}
			$('#wprevpro_selectedpagesspan').html(newhtml);
		});
		*/
		

		//readmore click
			var savedheight;
		var wprevsliderini_height;
		var wprevsliderini_height_widget;
			//$( '.wprs_rd_more' ).on("click",function(event) {

			$( "#preview_badge_outer" ).on( "click", ".wprs_rd_more", function(event) {
				event.preventDefault();
				var oldheight = $(this ).parent().parent().height();	//height of individual review
				var oldouterheight = $(this ).parent().parent().outerHeight();
				
				savedheight = $(this ).parent().parent().css("height");

				$(this ).prev( '.wprs_rd_more_dots' ).hide();
				$(this ).parent().parent().css( 'height', 'auto' );		//set individual review height to auto

				$(this ).parent().parent().parent().css( 'height', 'auto' );
				//also set wprev-slider div to auto if readmore has been clicked before, doesn't work for fade transition
				wprevsliderini_height = $(this ).closest('.wprev-slider').css("height");
				wprevsliderini_height_widget = $(this ).closest('.wprev-slider-widget').css("height");
				
				//$(this ).closest('.wprev-slider').css( 'height', 'auto' );

				$(this ).hide();
				$( this ).next('span').show(0, function() {
					// Animation complete.
					$( this ).next('.wprs_rd_less').show();
					});
					
				//var readmoretag = $( this ).prev('.wprs_rd_more');
				//show the read less tag
				var newheight = $(this ).parent().parent().height();
			
				//find height of .wprs_unslider-active then set .wprev-slider, only change if bigger
				var liheight = $(this ).closest( '.wprs_unslider-active' ).outerHeight();
				var dotheight = $(this ).closest('.wprs_unslider').siblings('.wprs_unslider-nav').height();
				//find max height of all slides
				var heights = $(this ).closest('.wprs_unslider').find( "li" ).map(function ()
							{
								return $(this).outerHeight();
							}).get(),
					overallheight = Math.max.apply(null, heights);

				if(liheight>overallheight){
					$(this ).closest( '.wprev-slider' ).animate({height: liheight,}, 200 );
					//$(this ).closest( '.wprev-slider' ).css( 'height', liheight );
				} else {
					$(this ).closest( '.wprev-slider' ).animate({height: overallheight,}, 200 );
					//$(this ).closest( '.wprev-slider' ).css( 'height', overallheight );
				}

				//for the widgets-------
				var widgetdivheight = $(this ).closest( '.wprs_unslider-active' ).height();
				var wheights = $(this ).closest('.wprs_unslider').find( "li" ).map(function ()
							{
								return $(this).outerHeight();
							}).get(),
							widgetoverallheight = Math.max.apply(null, wheights);
				if(widgetdivheight>widgetoverallheight){
					$(this ).closest( '.wprev-slider-widget' ).animate({height: widgetdivheight,}, 200 );
					//$(this ).closest( '.wprev-slider-widget').css( 'height', widgetdivheight );
				} else {
					$(this ).closest( '.wprev-slider-widget' ).animate({height: widgetoverallheight,}, 200 );
					//$(this ).closest( '.wprev-slider-widget').css( 'height', widgetoverallheight );
				}
				//---------------------
						
				//fix if we made smaller then set back to what it was.
				if(newheight<oldheight){
					if(oldouterheight>oldheight){
						$(this ).parent().parent().css( 'height', oldouterheight );
					}else {
						$(this ).parent().parent().css( 'height', oldheight );
					}
				}
			});
			//$( '.wprs_rd_less' ).on("click",function(event) {
			$( "#preview_badge_outer" ).on( "click", ".wprs_rd_less", function(event) {
				event.preventDefault();
				$(this ).hide();
				$( this ).prev('span').hide( 0, function() {
					$(this ).prevAll('.wprs_rd_more').show();
				});
				$(this ).parent().parent().animate({
					height: savedheight,
				  }, 0 );
				$(this ).closest('.wprev-slider').animate({
					height: wprevsliderini_height,
				  }, 100 );
				  $(this ).closest('.wprev-slider-widget').animate({
					height: wprevsliderini_height_widget,
				  }, 100 );

			});
	
		
	});

})( jQuery );

