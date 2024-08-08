
		
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
		var formid = 'new';
		
		var setlargiconurl = $('#wprevpro_badge_misc_liconurl').val();
		if(setlargiconurl==''){
			var customicon = false;
		} else {
			if(setlargiconurl.includes("-badge_50.png")){
				var customicon = false;
			} else {
				var customicon = true;
			}
		}
		var prestyle = "";
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
				//alert('here');
			},
			// a callback to fire when the input is emptied or an invalid color
			clear: function() {}
		};
		 
		$('.my-color-field').wpColorPicker(myOptions);
		
		//show hide custom ratings rows 
		if($( "#wprevpro_badge_misc_customratingfrom" ).val()!=='input' || $( "#wprevpro_template_style" ).val()=='2'){
			$('.customratingsrow').hide('slow');
			//change value back to 4.5 and 17
			$('.wppro_badge1_SPAN_13').html("4.5");
			$('.wppro_badge1_SPAN_15').html("17");
		} else {
			$('.customratingsrow').show('slow');
			var tempavg = $("#wprevpro_badge_misc_cratingavg").val();
			$('.wppro_badge1_SPAN_13').html(tempavg);
			var temptotal = $( "#wprevpro_badge_misc_cratingtotal").val();
			$('.wppro_badge1_SPAN_15').html(temptotal);
		}
		
		$( "#wprevpro_badge_misc_customratingfrom" ).on("change",function() {
			if($( "#wprevpro_badge_misc_customratingfrom" ).val()!=='input' || $( "#wprevpro_template_style" ).val()=='2'){
				$('.customratingsrow').hide('slow');
				//change value back to 4.5 and 17
				$('.wppro_badge1_SPAN_13').html("4.5");
				$('.wppro_badge1_SPAN_15').html("17");
				//check to see if this is a Google only snippet and warn about total  
				//if($( "#wprevpro_badge_orgin" ).val()=="google" && $( "#wprevpro_badge_misc_customratingfrom" ).val()=="table"){
				//	if($( "#wprevpro_t_google_snippet_add" ).val()=='yes'){
				//	$( "#googlewarning" ).show();
				//	}
				//} else {
					$( "#googlewarning" ).hide();
				//}
			} else {
				$('.customratingsrow').show('slow');
				var tempavg = $( "#wprevpro_badge_misc_cratingavg" ).val();
				$('.wppro_badge1_SPAN_13').html(tempavg);
				var temptotal = $( "#wprevpro_badge_misc_cratingtotal" ).val();
				$('.wppro_badge1_SPAN_15').html(temptotal);
				$( "#googlewarning" ).hide();
			}
		});
		//if manual ratings then update preview
		$( "#wprevpro_badge_misc_cratingavg" ).on("change",function() {
			var tempavg = $( this ).val();
			$('.wppro_badge1_SPAN_13').html(tempavg);
		});
		$( "#wprevpro_badge_misc_cratingtotal" ).on("change",function() {
			var temptotal = $( this ).val();
			$('.wppro_badge1_SPAN_15').html(temptotal);
		});
		
		
		changepreviewhtml();
		stylenumchanged();
		//resetcolors();
		
		//reset colors to default
		$( "#wprevpro_pre_resetbtn" ).on("click",function() {
			resetcolors();
		});

		//for hiding and showing file upload form
		$( "#wprevpro_importtemplates" ).on("click",function() {
			$("#importform").slideToggle();
		});
		$( "#wprevpro_recaltotals" ).on("click",function() {
			$("#recalform").slideToggle();
		});
	
		//on template num change
		$( "#wprevpro_template_style" ).on("change",function() {
			stylenumchanged();
		});
		function stylenumchanged(){
				//reset colors if not editing, otherwise leave alone
				if($( "#edittid" ).val()==""){
				resetcolors();
				}
				//hide the text color one if this val is 4 since we don't need
				if($("#wprevpro_template_style").val()=='4'){
					$('.tc1div').hide();
					$('.brdiv').hide();
					$('.bsdiv').hide();
					$('.bc1div').hide();
					$('.bc2div').hide();
					$('.bcdiv').hide();
				} else {
					$('.tc1div').show();
					$('.brdiv').show();
					$('.bsdiv').show();
					$('.bc1div').show();
					$('.bc2div').show();
					$('.bcdiv').show();
				}
				changepreviewhtml();
		}
		
		//on review orgin change
		$( "#wprevpro_badge_orgin" ).on("change",function() {
			var typearray = JSON.parse(adminjs_script_vars.globalwprevtypearray);
				resetcolors();
				changepreviewhtml();
				var curval = $( "#wprevpro_badge_orgin" ).val();

					//hide or show the correct types of rows
					if(curval=='custom'){

						for(var i=0; i<typearray.length; i++){
							var tempopt = '.bo_'+typearray[i].toLowerCase();
							$( tempopt ).show();
						}

					} else {
						for(var i=0; i<typearray.length; i++){
							var tempopt = '.bo_'+typearray[i].toLowerCase();
							$( tempopt ).hide();
						}
						
						$( '.bo_'+curval ).show();
						$( "#googlewarning" ).hide();
					}
				//unselect all current pages
				$('.pageselectclass').removeAttr('checked');
				$('#wprevpro_selectedpagesspan').html('');
				
				//show/hide google notice
				//if(curval=='google'){
				//	$( "#wprevpro_gbadge_notice" ).show();
				//} else {
					$( "#wprevpro_gbadge_notice" ).hide();
				//}
				
				//show/hide submitted notice
				if(curval=='submitted'){
					$( "#submittedbadgenotice" ).show();
				} else {
					$( "#submittedbadgenotice" ).hide();
				}
				if(curval=='custom'){
					$( "#custombadgenotice" ).show();
				} else {
					$( "#custombadgenotice" ).hide();
				}
				if(curval=='postid'){
					$( "#postidbadgenotice" ).show();
				} else {
					$( "#postidbadgenotice" ).hide();
				}
				
		});
				
		$( "#wprevpro_badge_bname" ).on("change",function() {
				changepreviewhtml();
		});
		$( "#wprevpro_badge_misc_showstars" ).on("change",function() {
				changepreviewhtml();
		});

		$( "#wprevpro_badge_misc_bradius" ).on("change",function() {
				changepreviewhtml();
		});
		$( "#wprevpro_badge_misc_bgcolor1" ).on("change",function() {
				changepreviewhtml();
		});
		$( "#wprevpro_badge_misc_bgcolor2" ).on("change",function() {
				changepreviewhtml();
		});
		$( "#wprevpro_badge_misc_bgcolor3" ).on("change",function() {
				changepreviewhtml();
		});
		$( "#wprevpro_badge_misc_bgcolor3" ).on("change",function() {
				changepreviewhtml();
		});
		
		$( "#wprevpro_badge_misc_starcolor" ).on("change",function() {
				changepreviewhtml();
		});
		$( "#wprevpro_badge_misc_tcolor1" ).on("change",function() {
				changepreviewhtml();
		});
		$( "#wprevpro_badge_misc_tcolor2" ).on("change",function() {
				changepreviewhtml();
		});
		$( "#wprevpro_badge_misc_tcolor3" ).on("change",function() {
				changepreviewhtml();
		});
		$( "#wprevpro_badge_misc_shadow" ).on("change",function() {
				changepreviewhtml();
		});
		$( "#wprevpro_badge_misc_customratingfrom" ).on("change",function() {
				changepreviewhtml();
		});
		

		
		//for small images
		$('.wprevpro_badge_sm_ck').on("change",function() {
			changepreviewhtml();
			//show link url
			if ($(this).is(":checked")){
				$(this).parent().nextUntil('.smi_iconchecks').show();
			} else {
				$(this).parent().nextUntil('.smi_iconchecks').hide();
			}
		});

		
		$('#wprevpro_badge_misc_show_licon').on("change",function() {
			changepreviewhtml();
		});
				
		//custom css change preview
		var lastValue = '';
		$("#wprevpro_badge_css").on('change keyup paste mouseup', function() {
			if ($(this).val() != lastValue) {
				lastValue = $(this).val();
				changepreviewhtml();
			}
		});
		
		$('#wprevpro_badge_misc_width').on("change",function() {
			changepreviewhtml();
		});
		$('#wprevpro_badge_misc_widtht').on("change",function() {
			changepreviewhtml();
		});
		$('#wprevpro_badge_misc_roundavg').on("change",function() {
			changepreviewhtml();
		});
		$('#wprevpro_badge_misc_outof').on("change",function() {
			changepreviewhtml();
		});
		$('#wprevpro_badge_misc_liconurl').on("change",function() {
			changepreviewhtml();
		});
		
	
		
		//upload large icon button----------------------------------
		$('#upload_licon_button').on("click",function() {
			tb_show('Upload Icon', 'media-upload.php?referer=wp_pro-badges&type=image&TB_iframe=true&post_id=0', false);
			//store old send to editor function
			window.restore_send_to_editor = window.send_to_editor;
			
			window.send_to_editor = function(html) {
				var image_url = jQuery("<div>" + html + "</div>").find('img').attr('src');
				$('#wprevpro_badge_misc_liconurl').val(image_url);
				$(".wppro_badge1_IMG_3").attr("src",image_url);
				$(".wppro_badge1_IMG_3").show();
				customicon = true;
				tb_remove();
				//restore old send to editor function
				 window.send_to_editor = window.restore_send_to_editor;
				//update preview.
				changepreviewhtml();
			}
		
			return false;
		});
		//upload small icon button----------------------------------
		/*
		$('#upload_sicon_button').on("click",function() {
			tb_show('Upload Icon', 'media-upload.php?referer=wp_pro-badges&type=image&TB_iframe=true&post_id=0', false);
			//store old send to editor function
			window.restore_send_to_editor = window.send_to_editor;
			
			window.send_to_editor = function(html) {
				var image_url = jQuery("<div>" + html + "</div>").find('img').attr('src');
				$('#wprevpro_badge_misc_si_custom_imgurl').val(image_url);
				tb_remove();
				//restore old send to editor function
				 window.send_to_editor = window.restore_send_to_editor;
				 changepreviewhtml();
			}
		
			return false;
		});
		*/
		$('.upload_sicustom_btn').on("click",function() {
			tb_show('Upload Icon', 'media-upload.php?referer=wp_pro-badges&type=image&TB_iframe=true&post_id=0', false);
			var clickedbtn = $(this);
			//store old send to editor function
			window.restore_send_to_editor = window.send_to_editor;
			
			window.send_to_editor = function(html) {
				var image_url = jQuery("<div>" + html + "</div>").find('img').attr('src');
				clickedbtn.prev('input').val(image_url);
				//$('#wprevpro_badge_misc_si_custom_imgurl').val(image_url);
				tb_remove();
				//restore old send to editor function
				 window.send_to_editor = window.restore_send_to_editor;
				 changepreviewhtml();
			}
		
			return false;
		});
		
		//---------
		
		function changepreviewhtml(){
			var templatenum = $( "#wprevpro_template_style" ).val();
			var bname = $( "#wprevpro_badge_bname" ).val();
			
			var borderradius = $( "#wprevpro_badge_misc_bradius" ).val();
			var bcolor1 = $( "#wprevpro_badge_misc_bgcolor1" ).val();
			var bcolor2 = $( "#wprevpro_badge_misc_bgcolor2" ).val();
			var bcolor3 = $( "#wprevpro_badge_misc_bgcolor3" ).val();
			var starcolor = $( "#wprevpro_badge_misc_starcolor" ).val();
			var tcolor1 = $( "#wprevpro_badge_misc_tcolor1" ).val();
			var tcolor2 = $( "#wprevpro_badge_misc_tcolor2" ).val();
			var tcolor3 = $( "#wprevpro_badge_misc_tcolor3" ).val();
			var showlargicon = $('#wprevpro_badge_misc_show_licon').val();
			var largiconurl = $('#wprevpro_badge_misc_liconurl').val();
			var badgeorgin = $( "#wprevpro_badge_orgin" ).val();
			var shadow = $( "#wprevpro_badge_misc_shadow" ).val();
			
			var bwidth = $( "#wprevpro_badge_misc_width" ).val();
			var bwidtht = $( "#wprevpro_badge_misc_widtht" ).val();
			
			var avgdecimals = $( "#wprevpro_badge_misc_roundavg" ).val();
			//out of 5 or 10. 
			var outof = $( "#wprevpro_badge_misc_outof" ).val();
			
			
			if(bwidth=='' || bwidth==0){
				bwidth = '100';
			}
			if(bwidtht==''){
				bwidtht = '%';
			}
			
			//hide star color if yelp or tripadvisor
			if(badgeorgin=='yelp' || badgeorgin=='tripadvisor'){
				$( "#rowstarcolor" ).hide();
			} else {
				$( "#rowstarcolor" ).hide();
			}
			
			var prestyle = '<style>.wppro_badge1_DIV_1 {border-radius: '+borderradius+'px;}.wppro_badge1_DIV_1 {border-top-color: '+bcolor1+';background-color: '+bcolor2+';border-bottom-color: '+bcolor3+';}.wppro_badge1_DIV_stars {color: '+starcolor+';}.ratingRow__star{color: '+starcolor+';}.wppro_b2__ratingProgress__fill{color: '+starcolor+';}span.wppro_badge1_SPAN_4 {color: '+tcolor1+';}.wppro_dashboardReviewSummary__avgRating{color: '+tcolor1+';}span.wppro_badge1_SPAN_13 {color: '+tcolor2+';}			.wppro_dashboardReviewSummary__avgReviews{color: '+tcolor2+';}.wppro_badge1_DIV_12 {color: '+tcolor3+';}.wppro_dashboardReviewSummary__right{color: '+tcolor3+';}.wppro_badge1_DIV_1 {width: '+bwidth+bwidtht+';}.svgicons{background:'+starcolor+' !important}</style>';
			
			if(templatenum=='5'){
				var prestyle = '<style>.wprev_badge_5_outer {border-radius: '+borderradius+'px;}.wprev_badge_5_outer {border-color: '+bcolor1+';background-color: '+bcolor2+';border-bottom-color: '+bcolor1+';}.wppro_badge1_DIV_stars {color: '+starcolor+';}.ratingRow__star{color: '+starcolor+';}.wppro_b2__ratingProgress__fill{color: '+starcolor+';}.wppro_badge5_name {color: '+tcolor1+';}.wppro_dashboardReviewSummary__avgRating{color: '+tcolor1+';}span.wppro_badge1_SPAN_13 {color: '+tcolor2+';}			.wppro_dashboardReviewSummary__avgReviews{color: '+tcolor2+';}.wppro_badge5_total {color: '+tcolor3+';}.wppro_dashboardReviewSummary__right{color: '+tcolor3+';}.wprev_badge_5_outer {width: '+bwidth+bwidtht+';}.svgicons{background:'+starcolor+' !important}</style>';
			}
			
			if(templatenum=='6'){
				var prestyle = '<style>.wppro_badge1_DIV_1 {border-radius: '+borderradius+'px;}.wppro_badge1_DIV_1.b6s1 {border-top-color: '+bcolor1+';background-color: '+bcolor2+';border-bottom-color: '+bcolor3+';}.wppro_badge1_DIV_stars {color: '+starcolor+';}.ratingRow__star{color: '+starcolor+';}.wppro_b2__ratingProgress__fill{color: '+starcolor+';}span.wppro_badge1_SPAN_4 {color: '+tcolor1+';}.wppro_avg_b6s6a{color: '+tcolor1+';}span.wppro_badge1_SPAN_13 {color: '+tcolor2+';}			.wppro_dashboardReviewSummary__avgReviews{color: '+tcolor2+';}.wppro_badge1_DIV_12 {color: '+tcolor3+';}.wppro_dashboardReviewSummary__right{color: '+tcolor3+';}.wppro_badge1_DIV_1 {width: '+bwidth+bwidtht+';}.svgicons{background:'+starcolor+' !important}</style>';
			}
			
			
			var svgarray = JSON.parse(adminjs_script_vars.globalwprevsvgarray); 
			
			//big icon-------------
			if(showlargicon=="yes"){
				var brfile = 'branding-'+badgeorgin+'-badge_50.png';
				if(svgarray.findIndex(item => badgeorgin.toUpperCase() === item.toUpperCase()) !== -1){
					brfile = badgeorgin+'_small_icon.svg';
				}
				
				//only change if customicon = false;
				if(customicon==false){
					var sourceiconurl = adminjs_script_vars.pluginsUrl + '/public/partials/imgs/'+brfile;
					$( "#wprevpro_badge_misc_liconurl" ).val(sourceiconurl);
				} else {
					var sourceiconurl = $( "#wprevpro_badge_misc_liconurl" ).val();
				}
				var sourceicon = '<img src="'+sourceiconurl+'" alt="'+badgeorgin+' logo" class="wppro_badge1_IMG_3" />';
				if(templatenum=='3'){
					sourceicon = '<img src="'+sourceiconurl+'" alt="'+badgeorgin+' logo" class="wppro_badge1_IMG_3 b3i" />';
				}
				if(templatenum=='5'){
					sourceicon = '<img src="'+sourceiconurl+'" alt="'+badgeorgin+' logo" class="wppro_badge5_IMG" />';
				}
				//check image size and save with badge so we can add it to html instead of using php.
				const img = new Image();
				img.onload = function() {
				  //console.log(this.width + 'x' + this.height);
				  if(this.width>0){
					  $( "#wprevpro_badge_misc_liconwidth" ).val(this.width);
				  }
				  if(this.height>0){
					  $( "#wprevpro_badge_misc_liconheight" ).val(this.height);
				  }
				}
				img.src = sourceiconurl;
				
			} else {
				var sourceiconurl = '';
				$( "#wprevpro_badge_misc_liconurl" ).val('');
				var sourceicon = '';
				customicon = false;
			}
			
			//for small icons------
			var smallhtmlicon = '';
			var typearray = JSON.parse(adminjs_script_vars.globalwprevtypearray);
			var imgclass = 'wppro_badge1_IMG_4';
			if(templatenum=='2'){
				imgclass = 'wppro_badge2_IMG_4';
			}
			if(templatenum=='6'){
				imgclass = 'wppro_badge6_IMG_4';
			}
			
			
			
			//console.log('svg:');
			//console.log(svgarray);
			
			for(var i=0; i<typearray.length; i++){
				if(typearray[i]){
				//check for svg icon.
				var fileext = 'png';
				if(jQuery.inArray(typearray[i], svgarray) !== -1){
					fileext = 'svg';
				}
				var temptype = typearray[i].toLowerCase();
				var stypelogo='';
				console.log('checked1:'+temptype);	//[id="x1-2800026.1"]
				if ($('[id="wprevpro_badge_sm_'+temptype+'"]').is(":checked")){
					// it is checked
					console.log('checked:'+temptype);
					stypelogo = adminjs_script_vars.pluginsUrl + '/public/partials/imgs/'+temptype+'_small_icon.'+fileext;
					smallhtmlicon = smallhtmlicon + '<img src="'+stypelogo+'" alt="'+temptype+' logo" class="'+imgclass+'" />';
					//yelp warning
					if(temptype=='yelp'){
						var syelp = stypelogo;
					}
				}
				}
			}
			//check for custom small icon
			if($('#wprevpro_badge_misc_si_custom_imgurl').val()!=""){
				//check if checkbox checked wprevpro_badge_sm_custom
				if($('#wprevpro_badge_sm_custom').is(":checked")){
					smallhtmlicon = smallhtmlicon +'<img src="'+$('#wprevpro_badge_misc_si_custom_imgurl').val()+'" alt="logo" class="'+imgclass+'" />';
				}
			}
			//check for custom small icon 2
			if($('#wprevpro_badge_misc_si_custom2_imgurl').val()!=""){
				//check if checkbox checked wprevpro_badge_sm_custom
				if($('#wprevpro_badge_sm_custom2').is(":checked")){
					smallhtmlicon = smallhtmlicon +'<img src="'+$('#wprevpro_badge_misc_si_custom2_imgurl').val()+'" alt="logo" class="'+imgclass+'" />';
				}
			}
			//check for custom small icon 3
			if($('#wprevpro_badge_misc_si_custom3_imgurl').val()!=""){
				//check if checkbox checked wprevpro_badge_sm_custom
				if($('#wprevpro_badge_sm_custom3').is(":checked")){
					smallhtmlicon = smallhtmlicon +'<img src="'+$('#wprevpro_badge_misc_si_custom3_imgurl').val()+'" alt="logo" class="'+imgclass+'" />';
				}
			}
			
			if(smallhtmlicon!=""){
				if(templatenum=='6'){
					smallhtmlicon = '<div class="wppro_badge1_DIV_13 b6si">'+smallhtmlicon+'</div>';
				} else {
					smallhtmlicon = '<div class="wppro_badge1_DIV_13">'+smallhtmlicon+'</div>';
				}
			}
			
			//----------------
			
			// change stars to images if yelp or tripadvisor------------
			//var starhtmldiv = '<span class="wprsp-star-full"></span><span class="wprsp-star-full"></span><span class="wprsp-star-full"></span><span class="wprsp-star-full"></span><span class="wprsp-star-half"></span></span>';
			var starhtmldiv = '<span class="svgicons svg-wprsp-star-full"></span><span class="svgicons svg-wprsp-star-full"></span><span class="svgicons svg-wprsp-star-full"></span><span class="svgicons svg-wprsp-star-full"></span><span class="svgicons svg-wprsp-star-half"></span></span>';
			
			if(badgeorgin=='yelp'){
				 starhtmldiv = adminjs_script_vars.pluginsUrl + '/public/partials/imgs/yelp_stars_4.5.png';
				 starhtmldiv = '<img src="'+starhtmldiv+'" alt="yelp logo" class="wppro_badge1_IMG_5" />';
			}
			if(badgeorgin=='tripadvisor'){
				 starhtmldiv = adminjs_script_vars.pluginsUrl + '/public/partials/imgs/tripadvisor_stars_4.5.png';
				 starhtmldiv = '<img src="'+starhtmldiv+'" alt="tripadvisor logo" class="wppro_badge1_IMG_5" />';
			}
			//--------------

			//---custom text
			var tempvalctext = $( "#wprevpro_badge_misc_ctext" ).val();
			if(tempvalctext==''){
				//tempvalctext = adminjs_script_vars.msg1;
			}
			var tempvalctext2 = $( "#wprevpro_badge_misc_ctext2" ).val();
			if(tempvalctext2==''){
				//tempvalctext2 = adminjs_script_vars.User_Reviews;
			}
			var tempvalctextb2 = $( "#wprevpro_badge_misc_ctext_b2" ).val();
			if(tempvalctextb2==''){
				tempvalctextb2 =  adminjs_script_vars.reviews;
			}
			
			//remove total for google style
			var temptotalrevs = "17";
			var tempavgrevs = "4.5";
			if(outof==10){
				var tempavgrevs = "9.0";
			}
			//alert(avgdecimals);
			if(avgdecimals==2){
				tempavgrevs = "4.53";
				if(outof==10){
				tempavgrevs = "9.06";	
				}
			} else if(avgdecimals==3){
				tempavgrevs = "4.531";
				if(outof==10){
					tempavgrevs = "9.062";
				}
			} else if(avgdecimals==0){
				tempavgrevs = "5";
				if(outof==10){
					tempavgrevs = "9";
				}
			}
			//get average and total based on pages selected and type. Pulling from Badge Data table
			if(badgeorgin!='submitted' && badgeorgin!='postid'){
				$( "#totalavgwarningmsg" ).hide('slow');
				//then we get total from selected pages.
				//if none selected and custom then total and average of all pages.
				//if non selected and some other type then we match types.
				
				//find if anything selected first.
				var checkedpages = $('input.pageselectclass:checked');
				//console.log('ts:'+checkedpages.length);
				var allpages = $('input.pageselectclass');
				//console.log('as:'+allpages.length);
				
				//find source site or table
				var sourceordb = 'total';
				var sourceordbavg = 'avg';
				var sourcecount = 0;
				if($( "#wprevpro_badge_misc_customratingfrom" ).val()=="table"){
					sourceordb = 'total';
					sourceordbavg = 'avg';
				} else if($( "#wprevpro_badge_misc_customratingfrom" ).val()=="db"){
					sourceordb = 'total_indb';
					sourceordbavg = 'avg_indb';
				}
				//console.log('sourceordb:'+sourceordb);
				//console.log('badgeorgin:'+badgeorgin);
				//$('#' + $.escapeSelector(my_id_with_special_chars))
				var rtot=0;
				var ravg=0;
				if ( $.isFunction($.escapeSelector) ) {
					if(checkedpages.length==0){
						if(badgeorgin=='custom'){
							//get all pages
							$.each(allpages, function() {
								var $this = $(this);
								var pageid = $this.val();
								rtot = Number(rtot) + Number($('#'+$.escapeSelector(pageid)).find('.'+sourceordb).text());
								if(Number($('#'+$.escapeSelector(pageid)).find('.'+sourceordbavg).text())>0){
									ravg = Number(ravg) + Number($('#'+$.escapeSelector(pageid)).find('.'+sourceordbavg).text());
									sourcecount = sourcecount + 1;
								}
							}); 
							//ravg = (ravg/sourcecount);
						
						} else if(badgeorgin!='submitted' && badgeorgin!='postid' ){
							//get all pages of certain type that is selected.
							$.each(allpages, function() {
								var $this = $(this);
								//console.log($this.val());
								var pageid = $this.val();
								var rtype =  $this.attr( "data-rtype" );
								//console.log('rtype:'+rtype);
								if(badgeorgin==rtype){
									//console.log('num:'+Number($('#'+pageid).find('.'+sourceordb).text()));
									rtot = Number(rtot) + Number($('#'+$.escapeSelector(pageid)).find('.'+sourceordb).text());
									if(Number($('#'+$.escapeSelector(pageid)).find('.'+sourceordbavg).text())>0){
										ravg = Number(ravg) + Number($('#'+$.escapeSelector(pageid)).find('.'+sourceordbavg).text());
										sourcecount = sourcecount + 1;
									}
								}
							});
							//ravg = (ravg/sourcecount);
						}
					} else {
						//must have a page selected, get values based on selected pages.
						$.each(checkedpages, function() {
							var $this = $(this);
							var pageid = $this.val();
							rtot = Number(rtot) + Number($('#'+$.escapeSelector(pageid)).find('.'+sourceordb).text());
							if(Number($('#'+$.escapeSelector(pageid)).find('.'+sourceordbavg).text())>0){
								ravg = Number(ravg) + Number($('#'+$.escapeSelector(pageid)).find('.'+sourceordbavg).text());
								sourcecount = sourcecount + 1;
							}
						}); 
						//ravg = (ravg/sourcecount);
					}
					if(sourcecount>0 && ravg>0){
						ravg = (ravg/sourcecount);
					} else {
						ravg = 0;
					}
				}
				console.log('sourcecount:'+sourcecount);
				console.log('rtot:'+rtot);
				console.log('ravg:'+ravg);
				temptotalrevs = rtot;
				if(ravg>0){
					tempavgrevs = ravg.toFixed(1);
					if(outof==10){
						tempavgrevs = ravg*2;
						tempavgrevs = tempavgrevs.toFixed(1);						
					}
					if(avgdecimals==2){
						tempavgrevs = ravg.toFixed(2);
						if(outof==10){
							tempavgrevs = ravg*2;
							tempavgrevs = tempavgrevs.toFixed(2);						
						}
					} else if(avgdecimals==3){
						tempavgrevs = ravg.toFixed(3);
						if(outof==10){
							tempavgrevs = ravg*2;
							tempavgrevs = tempavgrevs.toFixed(3);						
						}
					} else if(avgdecimals==0){
						tempavgrevs = ravg.toFixed(0);
						if(outof==10){
							tempavgrevs = ravg*2;
							tempavgrevs = tempavgrevs.toFixed(0);						
						}
					}
				} else {
					tempavgrevs = 0;
				}
			} else {
				$( "#totalavgwarningmsg" ).show('slow');
			}

			
			

			if($( "#wprevpro_badge_misc_customratingfrom" ).val()=="input"){
				temptotalrevs = $( "#wprevpro_badge_misc_cratingtotal" ).val();
				tempavgrevs = $( "#wprevpro_badge_misc_cratingavg" ).val();
			}
			if(tempvalctext==''){
				tempavgrevs='';
			}
			if(tempvalctext2==''){
				temptotalrevs='';
			}
			
			var style1html ='<div class="wprevpro_badge wppro_badge1_DIV_1">	\
		<div class="wppro_badge1_DIV_2">	\
			'+sourceicon+'<span class="wppro_badge1_SPAN_4">'+bname+'</span>	\
			<div class="wppro_badge1_DIV_5">	\
				<div class="wppro_badge1_DIV_stars">'+starhtmldiv+'	\
				</div>	\
				<div class="wppro_badge1_DIV_12">	\
					<span class="wppro_badge1_SPAN_13">'+tempavgrevs+'</span> '+tempvalctext+' <a href="#" title="'+tempvalctext2+'" class="wppro_badge1_A_14"><span class="wppro_badge1_SPAN_15">'+temptotalrevs+'</span> '+tempvalctext2+'</a>	\
				</div>'+smallhtmlicon+'</div></div></div>';
				
			var style3html ='<div class="wprevpro_badge wppro_badge1_DIV_1 b3s1">	\
		<div class="wppro_badge1_DIV_2 b3s2">	\
			'+sourceicon+'<span class="wppro_badge1_SPAN_4 b3s4">'+bname+'</span>	\
			<div class="wppro_badge1_DIV_5 b3s5">	\
				<div class="wppro_badge1_DIV_stars b3s6">'+starhtmldiv+'	\
				</div>	\
				<div class="wppro_badge1_DIV_12 b3s12">	\
					<span class="wppro_badge1_SPAN_13 b3s13">'+tempavgrevs+'</span> '+tempvalctext+' <a href="#" title="'+tempvalctext2+'" class="wppro_badge1_A_14"><span class="wppro_badge1_SPAN_15">'+temptotalrevs+'</span> '+tempvalctext2+'</a>	\
				</div>'+smallhtmlicon+'</div></div></div>';
				
			var style4html ='<div class="wprevpro_badge wppro_badge4_DIV_1 b4s1">	\
				<span class="wppro_badge1_DIV_stars b4s2">'+starhtmldiv+'	\
				</span>	\
				<span class="wppro_badge1_DIV_12 b3s12">	\
					<span class="wppro_badge1_SPAN_13 b3s13">'+tempavgrevs+'</span> '+tempvalctext+' <a href="#" title="'+tempvalctext2+'" class="wppro_badge1_A_14"><span class="wppro_badge1_SPAN_15">'+temptotalrevs+'</span> '+tempvalctext2+'</a>	\
				</span></div>';
				
			var style5html ='<div class="wppro_badge1_DIV_1 wprevpro_badge wprev_badge_5_outer" id="">	\
			  '+sourceicon+'	\
			  <div class="wppro_badge5_name">'+bname+'</div>	\
			  <div class="wppro_badge1_DIV_stars wppro_badge5_stars">'+starhtmldiv+'</div>	\
			  <div class="wppro_badge5_total"><span class="wppro_badge1_SPAN_13 b5">'+tempavgrevs+'</span> '+tempvalctext+' <a href="#" title="'+tempvalctext2+'" class="wppro_badge1_A_14"><span class="wppro_badge1_SPAN_15">'+temptotalrevs+'</span> '+tempvalctext2+'</a></div>	\
			  <div class="wppro_badge5_icons">'+smallhtmlicon+'</div>	\
			</div>';
			
			var style6html ='<div class="wprevpro_badge wppro_badge1_DIV_1 b6s1">	\
				<div class="wppro_badge1_DIV_2 b6s2">	\
				'+smallhtmlicon+'	\
				<div class="wppro_badge1_DIV_5 b6s5">	\
				<div class="wppro_badge1_DIV_stars b6s6"><span class="wppro_avg_b6s6a">'+tempavgrevs+'</span>'+starhtmldiv+'	\
				</div>	\
				<div class="wppro_badge1_DIV_12 b6s12">	\
					 <a href="#" title="'+tempvalctext2+'" class="wppro_badge1_A_14 b6s13"><span class="wppro_badge1_SPAN_15">'+temptotalrevs+'</span> '+tempvalctext2+'</a>	\
				</div></div></div></div>';
				
			var style7html ='<div class="wppro_badge1_DIV_1 wprevpro_badge wprev_badge_5_outer" id="">	\
			  <div class="wppro_badge7_avgrating">'+tempavgrevs+'</div>	\
			  <div class="wppro_badge1_DIV_stars wppro_badge5_stars">'+starhtmldiv+'</div>	\
			  <div class="wppro_badge5_name">'+bname+'</div>	\
			  <div class="wppro_badge5_total"><span class="wppro_badge1_SPAN_13 b5">'+tempavgrevs+'</span> '+tempvalctext+' <a href="#" title="'+tempvalctext2+'" class="wppro_badge1_A_14"><span class="wppro_badge1_SPAN_15">'+temptotalrevs+'</span> '+tempvalctext2+'</a></div>	\
			  <div class="wppro_badge5_icons">'+smallhtmlicon+'</div>	\
			</div>';	
				
						
			var smallicont2 = smallhtmlicon;
			if(smallicont2==''){
				smallicont2 = ''+temptotalrevs+' <span>'+tempvalctextb2+'</span>';
			}

			var style2html = '<div class="wprevpro_badge wppro_badge1_DIV_1" id="wprev-badge-">\
<div class="wppro_dashboardReviewSummary">\
      <div class="wppro_dashboardReviewSummary__left">\
        <div class="wppro_dashboardReviewSummary__avgRating">'+tempavgrevs+'</div>\
		<div class="wppro_b2__rating" data-rating-value="'+tempavgrevs+'">\
			<div class="wppro_badge1_DIV_stars bigstar" style="width:max-content;">'+starhtmldiv+'	</div>	\
		</div>\
        <div class="wppro_dashboardReviewSummary__avgReviews">'+smallicont2+'</div>\
      </div>\
      <div class="wppro_dashboardReviewSummary__right">\
		<div class="wppro_b2__ratingRow">\
		  <span>5</span><span class="wprevicon-star-full ratingRow__star"></span>\
			<div class="wppro_b2__ratingProgress">\
			  <div class="wppro_b2__ratingProgress__fill" style="width: 75.00%;"></div>\
			</div>\
		  <span class="wppro_b2__ratingRow__avg">15</span>\
		</div>\
		<div class="wppro_b2__ratingRow">\
		  <span>4</span><span class="wprevicon-star-full ratingRow__star"></span>\
			<div class="wppro_b2__ratingProgress">\
			  <div class="wppro_b2__ratingProgress__fill" style="width: 15.00%;"></div>\
			</div>\
		  <span class="wppro_b2__ratingRow__avg">3</span>\
		</div>\
		<div class="wppro_b2__ratingRow">\
		  <span>3</span><span class="wprevicon-star-full ratingRow__star"></span>\
			<div class="wppro_b2__ratingProgress">\
			  <div class="wppro_b2__ratingProgress__fill" style="width: 10.00%;"></div>\
			</div>\
		  <span class="wppro_b2__ratingRow__avg">2</span>\
		</div>\
		<div class="wppro_b2__ratingRow">\
		  <span>2</span><span class="wprevicon-star-full ratingRow__star"></span>\
			<div class="wppro_b2__ratingProgress">\
			  <div class="wppro_b2__ratingProgress__fill" style="width: 0.00%;"></div>\
			</div>\
		  <span class="wppro_b2__ratingRow__avg">0</span>\
		</div>\
		<div class="wppro_b2__ratingRow">\
		  <span>1</span><span class="wprevicon-star-full ratingRow__star"></span>\
			<div class="wppro_b2__ratingProgress">\
			  <div class="wppro_b2__ratingProgress__fill" style="width: 0.00%;"></div>\
			</div>\
		  <span class="wppro_b2__ratingRow__avg">0</span>\
		</div>\
      </div>\
</div>\
</div>';

			//for showing or hiding stars_5_yellow
			if($( "#wprevpro_badge_misc_showstars" ).val()=="no"){
				var starcss = '<style>.wppro_badge1_DIV_stars{display: none;}.wppro_badge1_DIV_12 {display: inline-block;}</style>';
				prestyle =  prestyle + starcss;
			}
			//for border shadow
			if(shadow=="no"){
				prestyle =  prestyle + '<style>.wppro_badge1_DIV_1 {box-shadow: unset;}</style>';
			}
						
			if($( "#wprevpro_badge_css" ).val()!=""){
				var customcss = '<style>'+$( "#wprevpro_badge_css" ).val()+'</style>';
				prestyle =  prestyle + customcss;
			}
			
			var temphtml;
			$( ".lgicondiv" ).show();
			$( ".smallicondiv" ).show();
			$( ".wprevpre_tcolor2" ).show();
			$( ".badgewidth" ).show();
			$( "#wprevpro_badge_misc_ctext" ).show();
			$( ".t2onlysource" ).hide();
			
			if(templatenum=='1'){
				$( "#wprevpro_template_preview" ).html(prestyle+style1html);
				$( ".t1oneonly" ).show();
				$( ".t2oneonly" ).hide();
			} else if(templatenum=='2'){
				$( "#wprevpro_template_preview" ).html(prestyle+style2html);
				//hide stuff if this is template 2
				$( ".t1oneonly" ).hide();
				$( ".t2oneonly" ).show();
				if($( "#wprevpro_badge_misc_customratingfrom" ).val()=="table"){
					$( ".t2onlysource" ).show();
				}
				
			} else if(templatenum=='3'){
				$( "#wprevpro_template_preview" ).html(prestyle+style3html);
				$( ".t1oneonly" ).show();
				$( ".t2oneonly" ).hide();
			} else if(templatenum=='4'){
				$( "#wprevpro_template_preview" ).html(prestyle+style4html);
				$( ".t1oneonly" ).show();
				$( ".t2oneonly" ).hide();
				//hide large an small icon divs
				$( ".lgicondiv" ).hide();
				$( ".smallicondiv" ).hide();
			} else if(templatenum=='5'){
				$( "#wprevpro_template_preview" ).html(prestyle+style5html);
				$( ".t1oneonly" ).show();
				$( ".t2oneonly" ).hide();
				$( ".bc2div" ).hide();
			} else if(templatenum=='6'){
				$( "#wprevpro_template_preview" ).html(prestyle+style6html);
				$( ".t1oneonly" ).show();
				$( ".t2oneonly" ).hide();
				$( ".wprevpre_tcolor2" ).hide();
				$( ".badgewidth" ).hide();
				$( "#wprevpro_badge_misc_ctext" ).hide();
				$( ".lgicondiv" ).hide();
			}  else if(templatenum=='7'){
				$( "#wprevpro_template_preview" ).html(prestyle+style7html);
				$( ".t1oneonly" ).show();
				$( ".t2oneonly" ).hide();
				$( ".bc2div" ).hide();
			}
			
			//hide and show based on badge orgin wprevpro_btn_pickpages
				var curval = $( "#wprevpro_badge_orgin" ).val();
				if(curval=='manual'){
					//hide the select button and div
					$( ".divpickpagesrow" ).hide('slow');
					$( "#wprevpro_btn_pickpostids" ).hide('slow');
					$( ".ratingsfrom" ).show('slow');
					$( "#wprevpro_btn_pickpages" ).show('slow');
					$( "#wprevpro_btn_pickpages" ).hide('slow');
					$( ".ratingsfrom" ).hide('slow');
					
				} else if(curval=='postid'){
					//hide everything not using.
					$( "#wprevpro_btn_pickpostids" ).show('slow');
					$( "#wprevpro_btn_pickpages" ).hide('slow');
					$( ".ratingsfrom" ).hide('slow');
				} else {
					$( "#wprevpro_btn_pickpostids" ).hide('slow');
					$( ".divpickpagesrow" ).show('slow');
					$( ".ratingsfrom" ).show('slow');
					$( "#wprevpro_btn_pickpages" ).show('slow');
				}
				if($( "#wprevpro_badge_misc_customratingfrom" ).val()=='input'){	//wprevpro_badge_misc_customratingfrom
					$( ".customratingsrow" ).show('slow');
				} else {
					$( ".customratingsrow" ).hide('slow');
				}
				

		}
		
		function resetcolors(){
				var templatenum = $( "#wprevpro_template_style" ).val();
				var orgin = $( "#wprevpro_badge_orgin" ).val();
				//reset colors to default
				if(templatenum=='1' && orgin=='facebook'){
					$( "#wprevpro_badge_misc_bradius" ).val('0');
					$( "#wprevpro_badge_misc_bgcolor1" ).val('#6988FE');
					$( "#wprevpro_badge_misc_bgcolor2" ).val('#ffffff');
					$( "#wprevpro_badge_misc_bgcolor3" ).val('#ffffff');
					$( "#wprevpro_badge_misc_starcolor" ).val('#6988FE');
					$( "#wprevpro_badge_misc_tcolor1" ).val('#666666');
					$( "#wprevpro_badge_misc_tcolor2" ).val('#6988FE');
					$( "#wprevpro_badge_misc_tcolor3" ).val('#666666');
					prestyle="";
					//reset color picker
					$('#wprevpro_badge_misc_bgcolor1').iris('color', '#6988FE');
					$('#wprevpro_badge_misc_bgcolor2').iris('color', '#ffffff');
					$('#wprevpro_badge_misc_bgcolor3').iris('color', '#ffffff');
					$('#wprevpro_badge_misc_starcolor').iris('color', '#6988FE');
					$( "#wprevpro_badge_misc_tcolor1" ).iris('color','#666666');
					$( "#wprevpro_badge_misc_tcolor2" ).iris('color','#6988FE');
					$( "#wprevpro_badge_misc_tcolor3" ).iris('color','#666666');
				}
				if(templatenum=='1' && orgin=='google'){
					$( "#wprevpro_badge_misc_bradius" ).val('0');
					$( "#wprevpro_badge_misc_bgcolor1" ).val('#2EA756');
					$( "#wprevpro_badge_misc_bgcolor2" ).val('#ffffff');
					$( "#wprevpro_badge_misc_bgcolor3" ).val('#ffffff');
					$( "#wprevpro_badge_misc_starcolor" ).val('#F9BC11');
					$( "#wprevpro_badge_misc_tcolor1" ).val('#666666');
					$( "#wprevpro_badge_misc_tcolor2" ).val('#4F81F3');
					$( "#wprevpro_badge_misc_tcolor3" ).val('#666666');
					prestyle="";
					//reset color picker
					$('#wprevpro_badge_misc_bgcolor1').iris('color', '#2EA756');
					$('#wprevpro_badge_misc_bgcolor2').iris('color', '#ffffff');
					$('#wprevpro_badge_misc_bgcolor3').iris('color', '#ffffff');
					$('#wprevpro_badge_misc_starcolor').iris('color', '#F9BC11');
					$( "#wprevpro_badge_misc_tcolor1" ).iris('color','#666666');
					$( "#wprevpro_badge_misc_tcolor2" ).iris('color','#4F81F3');
					$( "#wprevpro_badge_misc_tcolor3" ).iris('color','#666666');
				}
				if(templatenum=='1'){
					if(orgin=='yelp' || orgin=='airbnb'){
					$( "#wprevpro_badge_misc_bradius" ).val('0');
					$( "#wprevpro_badge_misc_bgcolor1" ).val('#ffffff');
					$( "#wprevpro_badge_misc_bgcolor2" ).val('#ffffff');
					$( "#wprevpro_badge_misc_bgcolor3" ).val('#ffffff');
					$( "#wprevpro_badge_misc_starcolor" ).val('#F9BC11');
					$( "#wprevpro_badge_misc_tcolor1" ).val('#666666');
					$( "#wprevpro_badge_misc_tcolor2" ).val('#666666');
					$( "#wprevpro_badge_misc_tcolor3" ).val('#666666');
					prestyle="";
					//reset color picker
					$('#wprevpro_badge_misc_bgcolor1').iris('color', '#ffffff');
					$('#wprevpro_badge_misc_bgcolor2').iris('color', '#ffffff');
					$('#wprevpro_badge_misc_bgcolor3').iris('color', '#ffffff');
					$('#wprevpro_badge_misc_starcolor').iris('color', '#F9BC11');
					$( "#wprevpro_badge_misc_tcolor1" ).iris('color','#666666');
					$( "#wprevpro_badge_misc_tcolor2" ).iris('color','#666666');
					$( "#wprevpro_badge_misc_tcolor3" ).iris('color','#666666');
					}
				}
				if(templatenum=='1' && orgin=='tripadvisor'){
					$( "#wprevpro_badge_misc_bradius" ).val('0');
					$( "#wprevpro_badge_misc_bgcolor1" ).val('#30a57e');
					$( "#wprevpro_badge_misc_bgcolor2" ).val('#ffffff');
					$( "#wprevpro_badge_misc_bgcolor3" ).val('#ffffff');
					$( "#wprevpro_badge_misc_starcolor" ).val('#F9BC11');
					$( "#wprevpro_badge_misc_tcolor1" ).val('#666666');
					$( "#wprevpro_badge_misc_tcolor2" ).val('#666666');
					$( "#wprevpro_badge_misc_tcolor3" ).val('#666666');
					prestyle="";
					//reset color picker
					$('#wprevpro_badge_misc_bgcolor1').iris('color', '#30a57e');
					$('#wprevpro_badge_misc_bgcolor2').iris('color', '#ffffff');
					$('#wprevpro_badge_misc_bgcolor3').iris('color', '#ffffff');
					$('#wprevpro_badge_misc_starcolor').iris('color', '#F9BC11');
					$( "#wprevpro_badge_misc_tcolor1" ).iris('color','#666666');
					$( "#wprevpro_badge_misc_tcolor2" ).iris('color','#666666');
					$( "#wprevpro_badge_misc_tcolor3" ).iris('color','#666666');
				}
				if(templatenum=='1'){
					if(orgin=='manual' || orgin=='submitted'){
					$( "#wprevpro_badge_misc_bradius" ).val('0');
					$( "#wprevpro_badge_misc_bgcolor1" ).val('#ffffff');
					$( "#wprevpro_badge_misc_bgcolor2" ).val('#ffffff');
					$( "#wprevpro_badge_misc_bgcolor3" ).val('#ffffff');
					$( "#wprevpro_badge_misc_starcolor" ).val('#F9BC11');
					$( "#wprevpro_badge_misc_tcolor1" ).val('#666666');
					$( "#wprevpro_badge_misc_tcolor2" ).val('#666666');
					$( "#wprevpro_badge_misc_tcolor3" ).val('#666666');
					prestyle="";
					//reset color picker
					$('#wprevpro_badge_misc_bgcolor1').iris('color', '#ffffff');
					$('#wprevpro_badge_misc_bgcolor2').iris('color', '#ffffff');
					$('#wprevpro_badge_misc_bgcolor3').iris('color', '#ffffff');
					$('#wprevpro_badge_misc_starcolor').iris('color', '#F9BC11');
					$( "#wprevpro_badge_misc_tcolor1" ).iris('color','#666666');
					$( "#wprevpro_badge_misc_tcolor2" ).iris('color','#666666');
					$( "#wprevpro_badge_misc_tcolor3" ).iris('color','#666666');
					}
				}
				if(templatenum=='2'){
					$( "#wprevpro_badge_misc_bradius" ).val('0');
					$( "#wprevpro_badge_misc_bgcolor1" ).val('#ffffff');
					$( "#wprevpro_badge_misc_bgcolor2" ).val('#ffffff');
					$( "#wprevpro_badge_misc_bgcolor3" ).val('#ffffff');
					$( "#wprevpro_badge_misc_starcolor" ).val('#F9BC11');
					$( "#wprevpro_badge_misc_tcolor1" ).val('#666666');
					$( "#wprevpro_badge_misc_tcolor2" ).val('#666666');
					$( "#wprevpro_badge_misc_tcolor3" ).val('#666666');
					prestyle="";
					//reset color picker
					$('#wprevpro_badge_misc_bgcolor1').iris('color', '#ffffff');
					$('#wprevpro_badge_misc_bgcolor2').iris('color', '#ffffff');
					$('#wprevpro_badge_misc_bgcolor3').iris('color', '#ffffff');
					$('#wprevpro_badge_misc_starcolor').iris('color', '#F9BC11');
					$( "#wprevpro_badge_misc_tcolor1" ).iris('color','#666666');
					$( "#wprevpro_badge_misc_tcolor2" ).iris('color','#666666');
					$( "#wprevpro_badge_misc_tcolor3" ).iris('color','#666666');
				}
				if(templatenum=='6'){
					$( "#wprevpro_badge_misc_bradius" ).val('0');
					$( "#wprevpro_badge_misc_bgcolor1" ).val('#ffffff');
					$( "#wprevpro_badge_misc_bgcolor2" ).val('#ffffff');
					$( "#wprevpro_badge_misc_bgcolor3" ).val('#ffffff');
					$( "#wprevpro_badge_misc_starcolor" ).val('#F9BC11');
					$( "#wprevpro_badge_misc_tcolor1" ).val('#666666');
					$( "#wprevpro_badge_misc_tcolor2" ).val('#666666');
					$( "#wprevpro_badge_misc_tcolor3" ).val('#666666');
					prestyle="";
					//reset color picker
					$('#wprevpro_badge_misc_bgcolor1').iris('color', '#ffffff');
					$('#wprevpro_badge_misc_bgcolor2').iris('color', '#ffffff');
					$('#wprevpro_badge_misc_bgcolor3').iris('color', '#ffffff');
					$('#wprevpro_badge_misc_starcolor').iris('color', '#F9BC11');
					$( "#wprevpro_badge_misc_tcolor1" ).iris('color','#666666');
					$( "#wprevpro_badge_misc_tcolor2" ).iris('color','#666666');
					$( "#wprevpro_badge_misc_tcolor3" ).iris('color','#666666');
				}
		}

		//help button clicked
		$( "#wprevpro_helpicon_posts" ).on("click",function() {
		  openpopup(adminjs_script_vars.popuptitle, '<p>'+adminjs_script_vars.popupmsg+'</p>', "");
		});
		//display shortcode button click 
		$( ".wprevpro_displayshortcode" ).on("click",function() {
			//get id and badge type
			var tid = $( this ).parent().attr( "templateid" );
			var ttype = $( this ).parent().attr( "templatetype" );
			
		  if(ttype=="widget"){
			openpopup(adminjs_script_vars.popuptitle1, '<p>'+adminjs_script_vars.popupmsg1+'</p>', '');
		  } else {
			openpopup(adminjs_script_vars.popuptitle2, '<p>'+adminjs_script_vars.popupmsg2+' </br></br>[wprevpro_usebadge tid="'+tid+'"] </br></br>'+adminjs_script_vars.popupmsg6+' <a href="https://wpreviewslider.userecho.com/en/knowledge-bases/2/articles/1644-parameters-in-shortcodes-for-badges" target="_blank">'+adminjs_script_vars.popupmsg5+'</a>.</br></br>'+adminjs_script_vars.popupmsg3+'</br></br> echo do_shortcode( \'[wprevpro_usebadge tid="'+tid+'"]\' ); </br></br> do_action( \'wprev_pro_plugin_action_badge\', '+tid+' ); </br></br>'+adminjs_script_vars.popupmsg4+' <a href="https://wpreviewslider.userecho.com/knowledge-bases/2/articles/1334-access-total-and-averages-with-shortcode-or-function" target="_blank">'+adminjs_script_vars.popupmsg5+'</a>. </p>', '');
		  }
		  
		});
		
		
		//launch pop-up windows code--------
		function openpopup(title, body, body2){

			//set text
			jQuery( "#popup_titletext").html(title);
			jQuery( "#popup_bobytext1").html(body);
			jQuery( "#popup_bobytext2").html(body2);
			
			var popup = jQuery('#popup_review_list').popup({
				width: 500,
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
			window.location.href = "?page=wp_pro-badges"; 
		  }, 500);
		  
		});	
		
		//-------------------------------
		//form validation 
		$("#wprevpro_submittemplatebtn").on("click",function(){
			if(jQuery( "#wprevpro_template_title").val()==""){
				alert(adminjs_script_vars.msg2);
				$( "#wprevpro_template_title" ).focus();
				return false;
			}
			if(jQuery('.selectrevstable input[type=checkbox]:checked').length<1) {
				if($( "#wprevpro_badge_misc_customratingfrom" ).val()!="input"){
					if($( "#wprevpro_badge_orgin" ).val()!="manual" && $( "#wprevpro_badge_orgin" ).val()!="submitted" && $( "#wprevpro_badge_orgin" ).val()!="woocommerce" && $( "#wprevpro_badge_orgin" ).val()!="custom" && $( "#wprevpro_badge_orgin" ).val()!="postid"){
						//alert(adminjs_script_vars.msg3);
						//return false;
					}
				}
			}
			return true;
		});
		

		
		//hide or show rich snippet settings---------------
		$( "#wprevpro_t_google_snippet_add" ).on("change",function() {
			//if no then hide
			var tempval = $( "#wprevpro_t_google_snippet_add" ).val();
			if(tempval!="yes"){
				$('#snippetsettings').hide('slow');
			} else {
				$('#snippetsettings').show('slow');
				//check to see if this is a Google only snippet and warn about total  
				//if($( "#wprevpro_badge_orgin" ).val()=="google" && $( "#wprevpro_badge_misc_customratingfrom" ).val()=="table"){
				//	if($( "#wprevpro_t_google_snippet_add" ).val()=='yes'){
				//	$( "#googlewarning" ).show();
				//	}
				//} else {
					$( "#googlewarning" ).hide();
				//}
				
			}
			var tempval2 = $( "#wprevpro_t_google_snippet_type" ).val();
			if(tempval2=="Product"){
				$('#businessrichsnippetfields').hide();
				$('#productrichsnippetfields').show('slow');
				
			} else {
				$('#productrichsnippetfields').hide();
				$('#businessrichsnippetfields').show('slow');
			}
		});
		
		
		//hide or show local business settings---------------
		$( "#wprevpro_t_google_snippet_type" ).on("change",function() {
			//if no then hide
			var tempval = $( "#wprevpro_t_google_snippet_type" ).val();
			if(tempval=="Product"){
				$('#businessrichsnippetfields').hide();
				$('#productrichsnippetfields').show('slow');
				
			} else {
				$('#productrichsnippetfields').hide();
				$('#businessrichsnippetfields').show('slow');
			}
		});
		
		//wprevpro_btn_pickpages open thickbox----------------
		$( "#wprevpro_btn_pickpages" ).on("click",function() {
			var url = "#TB_inline?width=600&height=700&inlineId=tb_content_page_select";
			tb_show(adminjs_script_vars.msg4, url);
			$( "#selectrevstable" ).focus();
			$( "#TB_window" ).css({ "width":"650px","height":"700px","margin-left": "-350px" });
			$( "#TB_ajaxContent" ).css({ "width":"auto","height":"650px" });
			$( "#TB_ajaxContent" ).focus();
			$('#TB_closeWindowButton').blur();
			$('head').append('<style type="text/css">#TB_window {top:100px !important;margin-top: 50px !important;margin-left: -480px !important;width: 960px !important; height: 655px !important; }</style>');
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
			changepreviewhtml();
		});
		
		//when changing custom text  wppro_badge1_DIV_12
		$( "#wprevpro_badge_misc_ctext" ).on("change",function() {
			changepreviewhtml();
		});
		$( "#wprevpro_badge_misc_ctext2" ).on( "change",function() {
			changepreviewhtml();
		});
		
		//when selecting overrall badge click options
		$( "#wprevpro_badge_misc_onclickaction" ).on( "change",function() {
			var clickstyle = '<style>.wprevpro_badge{cursor: pointer;}</style>';
			if($(this).val()=="url"){
				$( ".linktourl" ).show('slow');
				$( ".slidouttr" ).hide();
			} else if($(this).val()=="slideout"){
				$( ".slidouttr" ).show('slow');
				$( ".linktourl" ).hide();
				$( ".hideforpopup" ).show();
				getslideoutdata();
			} else if($(this).val()=="popup"){
				$( ".slidouttr" ).show('slow');
				$( ".linktourl" ).hide();
				$( ".hideforpopup" ).hide();
				getslideoutdata();
			} else {
				$( ".slidouttr" ).hide('slow');
				$( ".linktourl" ).hide('slow');
				$( ".hideforpopup" ).show();
				clickstyle = '';
			}
			//add style css for cursor if we are adding click event to overall
			$( "#wprevpro_template_preview" ).append(clickstyle);
			
		});
		//get slide out data and add to preview div----------------
		if($('.wprevpro_slideout_container_body').html()==''){
			if($( "#wprevpro_badge_misc_sliderevtemplate" ).val()>0){
				getslideoutdata();
			}
		}
		//get popup data if needed
		if($('.wprevpro_popup_container_body').html()=='' && $( "#wprevpro_badge_misc_onclickaction" ).val()=='popup'){
			if($( "#wprevpro_badge_misc_sliderevtemplate" ).val()>0){
				getslideoutdata();
			}
		}
		$( "#wprevpro_badge_misc_sliderevtemplate" ).on( "change",function() {
			getslideoutdata();
		});
		function getslideoutdata(){
			$( ".loading-image2" ).show();
			var revtemplateid='';
			revtemplateid = $( "#wprevpro_badge_misc_sliderevtemplate" ).val();

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
					if($( "#wprevpro_badge_misc_onclickaction" ).val()=='slideout'){
						$( ".wprevpro_slideout_container_body" ).html(response);
					} else if($( "#wprevpro_badge_misc_onclickaction" ).val()=='popup'){
						$( ".wprevpro_popup_container_body" ).html(response);
					}
				}
				// on success refresh from preview
			});
			jqxhr.fail(function() {
			  alert( adminjs_script_vars.msg5 );
			});
		}
		
		//update slide style when changing
		 changeslideoutstyle();
		$( "#wprevpro_badge_misc_slidelocation" ).on( "change",function() {
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
		$('#wprevpro_badge_misc_slideheader').on('input selectionchange propertychange', function() {
		  changeslideoutstyle();
		});
		$('#wprevpro_badge_misc_slidefooter').on('input selectionchange propertychange', function() {
		  changeslideoutstyle();
		});

		function changeslideoutstyle(){
			
			//is this a popup or slideout
			var onclickaction = $( "#wprevpro_badge_misc_onclickaction" ).val();
			// onclickaction will equal no, url, slideout, or popup
			
			var bname = $( "#wprevpro_float_bname" ).val();
			var slidelocation = $( "#wprevpro_badge_misc_slidelocation" ).val();
			
			var slideheight = $( "#wprevpro_badge_misc_slheight" ).val();
			if(slideheight==""){
				slideheight='auto;';
			} else {
				slideheight=slideheight+'px;';
			}
			var slidewidth = $( "#wprevpro_badge_misc_slwidth" ).val();
			if(slidewidth==""){slidewidth=350;}
			
			//background color
			var lochtml='';
			var bgcolor1 = $( "#wprevpro_badge_misc_slbgcolor1" ).val();
			if(bgcolor1!=''){
				lochtml = lochtml + 'background: '+bgcolor1+';';
			}
			var bgbordercolor1 = $( "#wprevpro_badge_misc_slbordercolor1" ).val();
			if(bgbordercolor1!=''){
				lochtml = lochtml + 'border: 1px solid '+bgbordercolor1+';';
			}

			if(onclickaction=='popup'){
				//var bodystyle = '.wprevpro_popup_container_body {'+tempstyletext+'}';
				
				//lochtml = 'width: '+slidewidth+'px;height: '+slideheight;
				
				var locstyle = '.wprevpro_popup_container_inner {'+lochtml+'}';
				var formstyle = '<style>'+locstyle+'</style>';
				$( ".wprevpro_popup_container_style" ).html(formstyle);
				
				//add the header and footer html
				var headerhtml = $( "#wprevpro_badge_misc_slideheader" ).val();
				$( ".wprevpro_popup_container_header" ).html(headerhtml);
				var footerhtml = $( "#wprevpro_badge_misc_slidefooter" ).val();
				$( ".wprevpro_popup_container_footer" ).html(footerhtml);
			} else {
				
				if(slidelocation=="right"){
					lochtml = 'bottom: 0px;right: 0px;height: 100%;width: '+slidewidth+'px;';
				} else if(slidelocation=="left"){
					lochtml = 'bottom: 0px;left: 0px;height: 100%;width: '+slidewidth+'px;';
				} else if(slidelocation=="top"){
					lochtml = 'top: 0px;bottom:unset;width: 100%;height: '+slideheight;
				} else if(slidelocation=="bottom"){
					lochtml = 'top:unset;bottom: 0px;width: 100%;height: '+slideheight;
				}
			
				//slide padding
				var slidepaddingarray = [$( "#wprevpro_badge_misc_slpadding-top" ).val(), $( "#wprevpro_badge_misc_slpadding-right" ).val(), $( "#wprevpro_badge_misc_slpadding-bottom" ).val(),$( "#wprevpro_badge_misc_slpadding-left" ).val()];
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
				//var headerhtml = $( "#wprevpro_badge_misc_slideheader" ).val();
				var headerhtml = tinymce.get("wprevpro_badge_misc_slideheader").getContent();
				$( ".wprevpro_slideout_container_header" ).html(headerhtml);
				//var footerhtml = $( "#wprevpro_badge_misc_slidefooter" ).val();
				var footerhtml = tinymce.get("wprevpro_badge_misc_slidefooter").getContent();
				$( ".wprevpro_slideout_container_footer" ).html(footerhtml);
			}
			
			
		}		
		
		//if we are using the overall click then we demo it here
		$( "#wprevpro_template_preview" ).on("click",function() {
			var tempclickval = $( "#wprevpro_badge_misc_onclickaction" ).val();
			if(tempclickval=='url'){
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
			} else if(tempclickval=='slideout'){
				//we need to do something here
				changeslideoutstyle();
				//$('.wprevpro_slideout_container').toggle();
				if ($('.wprevpro_slideout_container').css("visibility") === "visible") {
				  $('.wprevpro_slideout_container').css("visibility", "hidden");
				} else {
				  $('.wprevpro_slideout_container').css("visibility", "visible");
				}
				
				
			} else if (tempclickval=='popup'){
				changeslideoutstyle();
				//$('.wprevpro_popup_container').show();
				if ($('.wprevpro_popup_container').css("visibility") === "visible") {
				  $('.wprevpro_popup_container').css("visibility", "hidden");
				} else {
				  $('.wprevpro_popup_container').css("visibility", "visible");
				}
			}
		});

		//for updating preview when text editor is changed
        setTimeout(function () {
            for (var i = 0; i < tinymce.editors.length; i++) {
                tinymce.editors[i].on("change",function (ed, e) {
                    changeslideoutstyle();
                });
            }
        }, 1000);

		//close slideout onclick on everything but it
		$(document).on("click",function(event) {
			changeslideoutstyle();			
			if(!$(event.target).closest('.wprevpro_slideout_container').length && !$(event.target).closest('.updatesliderinput').length && !$(event.target).closest('#wprevpro_template_preview').length) {
				if($('.wprevpro_slideout_container').is(":visible")) {
					//$('.wprevpro_slideout_container').hide();
					$('.wprevpro_slideout_container').css("visibility", "hidden");
				}
			}        
		});
		
		
	});

})( jQuery );

