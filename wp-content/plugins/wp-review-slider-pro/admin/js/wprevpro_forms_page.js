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
		
		//global
		var templateid = '';


		//when clicking the edit button
		$( ".editformbtn" ).on("click",function() {
			//get the form id
			templateid = $(this).attr('templateid');
			//=================
			//save this as a hidden variable so we can access it
			$('#edittid').val(templateid);
			//==============================
			
			//change title
			$('#fpc_edit_title').html(adminjs_script_vars.msg3);

			//pass to ajax to get the data
			getformdata(templateid);
			jQuery("#wprevpro_new_template").show("slow");
		});	
		
		//add new template
		$( "#wprevpro_addnewtemplate" ).on( "click",function() {
			getformdata();
		  jQuery("#wprevpro_new_template").show("slow");
		  //change title
			$('#fpc_edit_title').html(adminjs_script_vars.msg2);
		});	
		$( "#wprevpro_addnewtemplate_cancel" ).on("click",function() {
		  jQuery("#wprevpro_new_template").hide("slow");
		  //reload page 
		  setTimeout(function(){ 
			window.location.href = "?page=wp_pro-forms"; 
		  }, 500);
		});	
		
		//for hiding and showing file upload form
		$( "#wprevpro_importtemplates" ).on("click",function() {
			$("#importform").slideToggle();
		});
		
		//help button clicked
		$( "#wprevpro_helpicon_posts" ).on("click",function() {
		  openpopup(adminjs_script_vars.popuptitle, '<p>'+adminjs_script_vars.popupmsg+'</p>', "");
		});
		//display shortcode button click 
		$( ".wprevpro_displayshortcode" ).on("click",function() {
			//get id and badge type
			var fid = $( this ).parent().attr( "fid" );
			var ttype = "";
			
		  if(ttype=="widget"){
			openpopup(adminjs_script_vars.popuptitle1, '<p>'+adminjs_script_vars.popupmsg1+'</p>', '');
		  } else {
			openpopup(adminjs_script_vars.popuptitle2, '<p>'+adminjs_script_vars.popupmsg2a+' </br></br><b>[wprevpro_useform tid="'+fid+'"]</b></br></br><b>'+adminjs_script_vars.popupmsg2b+'</b> </br></br>[wprevpro_useform tid="'+fid+'" wppl="yes"]</br></br>'+adminjs_script_vars.popupmsg2c+'</br> http://mywebsite.com/?wppl=yes</br>http://mywebsite.com/?review=1</br>'+adminjs_script_vars.popupmsg2d+'</br></br><b>'+adminjs_script_vars.popupmsg2b1+'</b> </br></br>[wprevpro_useform tid="'+fid+'" wppl="auto"]</br></br>'+adminjs_script_vars.popupmsg2c1+'</br> http://mywebsite.com/?wppl=auto</br>http://mywebsite.com/?review=2</br></br>'+adminjs_script_vars.popupmsg2d1+'</br></br><b>'+adminjs_script_vars.popupmsg3d1+'</b></br></br>'+adminjs_script_vars.popupmsg3d2+'</br>http://mywebsite.com/?wrtg=tag1</br>'+adminjs_script_vars.popupmsg3d3+'</br>http://mywebsite.com/?wrtg=tag1,tag2</p>', '');
		  }
		  
		});
		//launch pop-up windows code--------
		function openpopup(title, body, body2){

			//set text
			jQuery( "#popup_titletext").html(title);
			jQuery( "#popup_bobytext1").html(body);
			jQuery( "#popup_bobytext2").html(body2);
			
			var popup = jQuery('#popup_review_list').popup({
				width: 650,
				offsetX: -150,
				offsetY: -150,
			});
			
			popup.open();
			//set height
			var bodyheight = Number(jQuery( ".popup-content").height()) + 10;
			jQuery( "#popup_review_list").height(bodyheight);

		}
		//--------------------------------	

		//upload custom logo url button----------------------------------
		$('#upload_logo_button').on("click",function() {
			tb_show(adminjs_script_vars.msg1, 'media-upload.php?referer=wp_pro-reviews&type=image&TB_iframe=true&post_id=0', false);
			
			//store old send to editor function
			window.restore_send_to_editor = window.send_to_editor;
			//overwrite send to editor function
			window.send_to_editor = function(html) {
				 var logo_image_url = jQuery("<div>" + html + "</div>").find('img').attr('src');
				 $('#wprevpro_form_icon_image_url').val(logo_image_url);
				 
				 tb_remove();
				 //restore old send to editor function
				 window.send_to_editor = window.restore_send_to_editor;
			}
			
			
			return false;
		});		
		
		//make fields sortable-handle
		$( ".ui-sortable" ).sortable();
		//$( ".ui-sortable" ).disableSelection();
		$( ".ui-sortable" ).on( "sortupdate", function( event, ui ) {
			//alert('here');
			updatetheformindb();
		} );
		
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

		
		//hide or show field 
		$( "#custom_fields_list" ).on( "click", "li div.custom-field-header", function() {
			if($(this).parent().hasClass( "open" )){
				$(this).parent().removeClass('open');
				$(this).next().hide('slow');
			} else {
				$(this).parent().addClass('open');
				$(this).next().show('slow');
			}
		});
		
		
		
		//get form data if editing or creating new one----------------
		function getformdata(formid='new',updateff='yes',updatefprev='yes'){
			var senddata = {
				action: 'wprp_get_form',	//required
				wpfb_nonce: adminjs_script_vars.wpfb_nonce,
				fid: formid,
				};
			//send to ajax to update db
			var jqxhr = jQuery.post(ajaxurl, senddata, function (response){
				//console.log(response);
				if (!$.trim(response)){
					alert(adminjs_script_vars.msg4);
				} else {
					
					var formobject = JSON.parse(response);
					//console.log(formobject);
					//update the form field values.
					if(formid=='new' || updateff=='yes'){
						updateformfields(formobject);
					}
					if(updatefprev=='yes'){
						updateformpreview(formobject);
					}
				}

				// on success refresh from preview
			});
			jqxhr.fail(function() {
			  alert( adminjs_script_vars.msg5 );
			});
			
		}
		//global var for clicking stars and hiding or showing logic
		var globhiderest;
		var globshowval;
		//update the form preview display-----------------------------------
		function updateformpreview(formobject){
			var rftypearray = adminjs_script_vars.allrevstypearray;
			var ffields = formobject.form_fields;
			var ffields_array = JSON.parse(ffields);
			var arrayLength = ffields_array.length;
			var fmisc = formobject.form_misc;
			var fmisc_obj = {};
			if(fmisc!=''){
				fmisc_obj = JSON.parse(fmisc);
			}
			//console.log(ffields_array);
			
			var inlinestyle = '<style>'+formobject.form_css+'</style>';
			
			//add custom html if set
			var headerhtmlval = '';
			if(formobject.form_html && formobject.form_html!=''){
				headerhtmlval ='<div class="wprevform-headerhtml">'+formobject.form_html+'</div>';
				headerhtmlval = headerhtmlval.replace(/\\(.)/mg, "$1");
			}
			
			//for required label at top typeof myVar != 'undefined'
			var showrequiredhtml = '';
			var showreqtext = 'Required field';
			if(fmisc_obj.requiredlabeltext!='' && typeof fmisc_obj.requiredlabeltext!='undefined'){
				showreqtext = fmisc_obj.requiredlabeltext;
			}
			if(fmisc_obj.requiredlabelshow!="hide"){
				showrequiredhtml = '<p class="wprevpro_required_notice"><span class="required symbol"></span>'+showreqtext+'</p>';
			}

			var formhtml = inlinestyle+headerhtmlval+showrequiredhtml+'	\
			<form enctype="multipart/form-data" autocomplete="off">';
			
			var hiderest = '';
			//first loop to see if we need to hide rest of form when using logic
			for (var i = 0; i < arrayLength; i++) {
				var temptype = ffields_array[i].input_type;
				if(temptype=="social_links"){
					//see  if we need to hide the rest of the form
					if(ffields_array[i].hiderest=='hide'){
						hiderest = 'wprevhideme';
						globhiderest = 'hide';
					}
				}
			}
			var hidemeclass = '';
			//console.log('hidemeclass:'+hidemeclass);
			for (var i = 0; i < arrayLength; i++) {
				//console.log('hidemeclass2:'+hidemeclass);
				var rofform = 'rofform';
				var iconinlabel ='';
				var btnstyleclass = '';
				
				//only do this if hidden checkbox not checked
				if(ffields_array[i].hide_field!="on"){
					
					var tempvalue = ffields_array[i].default_form_value;
					
					//for required checkbox
					var temprequired ='';
					var temprequiredhtml ='';
					if(ffields_array[i].required=='on'){
						temprequired = 'required';
						temprequiredhtml = '<span class="required symbol"></span>';
					}
					
					//change html a little based on field type
					var temptype = ffields_array[i].input_type;
					//console.log('temptype:'+temptype);
					var temptypehtml = '';
					if(temptype=="text"){
						temptypehtml ='<input id="wprevpro_'+ffields_array[i].name+'" type="text" class="text" name="post_title" value="'+tempvalue+'" tabindex="0" '+temprequired+' placeholder="'+ffields_array[i].placeholder+'">';
					} else if(temptype=="textarea"){
						temptypehtml ='<textarea id="wprevpro_'+ffields_array[i].name+'" name="wprevpro_'+ffields_array[i].name+'" class="" '+temprequired+' tabindex="0" placeholder="'+ffields_array[i].placeholder+'">'+tempvalue+'</textarea>';
					} else if(temptype=="email"){
						temptypehtml ='<input id="wprevpro_'+ffields_array[i].name+'" type="email" class="text email" name="wprevpro_'+ffields_array[i].name+'" value="'+tempvalue+'" '+temprequired+' tabindex="0" placeholder="'+ffields_array[i].placeholder+'">';
					} else if(temptype=="url"){
						temptypehtml ='<input id="wprevpro_'+ffields_array[i].name+'" type="url" class="text url" name="wprevpro_'+ffields_array[i].name+'" value="'+tempvalue+'" tabindex="0" '+temprequired+' placeholder="'+ffields_array[i].placeholder+'">';
					} else if(temptype=="review_rating" || temptype=="starrating"){
						rofform ='';
						//starrating is for adding a custom rating field
						//get default value and add here
						//if else depending on stars or updown vote
						var ratetype = '';
						var tempid = temptype;
						var iscustom = '';
						if(temptype=="starrating"){
							tempid = ffields_array[i].name;
							iscustom = 'iscustom';
						}
						if(ffields_array[i].starornot && ffields_array[i].starornot=='updown'){
							ratetype = ffields_array[i].starornot;
						}
						var afterclick = '';
						if(ffields_array[i].afterclick && ffields_array[i].afterclick!=''){
							afterclick = 'hideafterclick';
						}
						
						temptypehtml ='<div class="wprevpro-rating-wrapper field-wrap in-form '+afterclick+'"><fieldset contenteditable="false" id="wprevpro_'+tempid+'" name="'+tempid+'" class="wprevpro-rating" data-field-type="rating" tabindex="0">';
						if(ratetype!='updown'){
							for (var k = 0; k <= 5; k++) {
								var starchecked = '';
								if(tempvalue==k){
									starchecked = ' checked="checked"';
								}
								temptypehtml = temptypehtml + '<input type="radio" class="wprevpro-rating-radio" id="'+tempid+'-star'+k+'" name="'+tempid+'-star'+k+'" value="'+k+'" '+starchecked+'><label for="'+tempid+'-star'+k+'" title="'+k+' stars" class="wprevpro-rating-radio-lbl"></label>';
							}
						} else {
							var rateiconup = 'wprsp-thumbs-o-up';
							var rateicondown = 'wprsp-thumbs-o-down';
							if(ffields_array[i].star_icon && ffields_array[i].star_icon!=''){
								if(ffields_array[i].star_icon=='smileys'){
									rateiconup = 'wprsp-smile-o';
									rateicondown = 'wprsp-frown-o';
								}
							}
							//updown html here
							temptypehtml = temptypehtml + '<span id="wppro_fvoteup" class="svgicons svg-'+rateiconup+' wppro_updown '+iscustom+'"></span><span id="wppro_fvotedown" class="svgicons svg-'+rateicondown+' wppro_updown '+iscustom+'"></span>';
						}
						
						temptypehtml = temptypehtml + '</fieldset></div>';
					} else if(temptype=="review_avatar" || temptype=="review_video"){
						temptypehtml ='<input name="wprevpro_'+ffields_array[i].name+'" type="file" tabindex="0">';
						
						if(ffields_array[i].picstyle){
							btnstyleclass = ffields_array[i].picstyle;
						}
						if(ffields_array[i].vidstyle){
							btnstyleclass = ffields_array[i].vidstyle;
						}
						if(btnstyleclass=='btn1' || btnstyleclass=='btn2'){
							if(temptype=='review_avatar'){
								iconinlabel = '<span class="svgicons svg-wprsp-camera"></span>&nbsp;';
							} else if(temptype=='review_video'){	
								iconinlabel = '<span class="svgicons svg-wprsp-video-camera"></span>&nbsp;';
							}
						}
						
					} else if(temptype=="review_consent"){
						temptypehtml ='<input type="checkbox" name="wprevpro_'+ffields_array[i].name+'" value="yes" tabindex="0">';
					} else if(temptype=="social_links"){
						rofform ='';
						if(Number(ffields_array[i].showval)>0){
							//hide the buttons till star clicked
							hidemeclass = 'wprevhideme';
							globshowval = ffields_array[i].showval;
						}
						
						//change link display type based on saved value
						var btnclass = 'btnwprevdefault';
						var iconhtml = '';
						if(ffields_array[i].displaytype=='sicon'){
							btnclass = 'btnwprevdefault_sicon';
						} else if(ffields_array[i].displaytype=='licon'){
							btnclass = 'btnwprevdefault_licon';
						} 
						
						//loop here and build the rest
						for (let k = 1; k < 51; k++) {
							var tempname = 'lname'+k;
							var tempurl = 'lurl'+k;
							var tempimgurl = 'limgurl'+k;
							if(ffields_array[i][tempname] && ffields_array[i][tempurl] && !ffields_array[i][tempimgurl] ){
								temptypehtml +='<a href="'+ffields_array[i][tempurl]+'" target="_blank" class="'+btnclass+'">';
								iconhtml = returniconhtml(ffields_array[i][tempurl],ffields_array[i].displaytype,ffields_array[i][tempname]);
								temptypehtml +=iconhtml;
								temptypehtml +='</a>';
							}  else if(ffields_array[i][tempname] && ffields_array[i][tempurl] && ffields_array[i][tempimgurl]){
								if(btnclass == 'btnwprevdefault'){
									temptypehtml +='<a href="'+ffields_array[i][tempurl]+'" target="_blank" class="'+btnclass+'">'+ffields_array[i][tempname]+'</a>';
								} else {
									temptypehtml +='<a href="'+ffields_array[i][tempurl]+'" target="_blank" class="'+btnclass+'"><img src="'+ffields_array[i][tempimgurl]+'" alt="'+ffields_array[i][tempname]+' Logo" class="wprevpro_form_site_logo"></a>';
								}
							}
						}
						
					} else if(temptype=="checkbox"){
						temptypehtml ='<input type="checkbox" name="wprevpro_'+ffields_array[i].name+'" value="yes" tabindex="0">';
					} else if(temptype=="media"){
						temptypehtml ='<input id="wprevpro_'+ffields_array[i].name+'" type="text" class="text" name="mediaurl" value="'+tempvalue+'" tabindex="0" '+temprequired+' placeholder="'+ffields_array[i].placeholder+'">';

						temptypehtml +='<input id="wprevpro_'+ffields_array[i].name+'_upload" name="wprevpro_'+ffields_array[i].name+'_upload" type="file" tabindex="0">';
						
						
					} else if(temptype=="select"){
						if(ffields_array[i].selopts){
							//get comman string of options. opt1, opt2, opt3,
							var opts = ffields_array[i].selopts;
							opts = opts.replace(/\s+/g, '');
							var optsArr = opts.split(",");
							temptypehtml ='	<select name="wprevpro_'+ffields_array[i].name+'" id="wprevpro_'+ffields_array[i].name+'">';
							for (let i = 0; i < optsArr.length; i++) {
							  temptypehtml += '<option value="'+optsArr[i]+'">'+optsArr[i]+'</option>';
							}
							temptypehtml += '</select>';
						}
					} else if(temptype=="select_page"){
						if(ffields_array[i].selopts && ffields_array[i].seloptsname){
							//get comman string of options. opt1, opt2, opt3,
							var spopts = ffields_array[i].selopts;
							spopts = spopts.replace(/\s+/g, '');
							var spoptsArr = spopts.split(",");
							//get opt lables for pages
							var spoptsnames = ffields_array[i].seloptsname;
							spoptsnames = spoptsnames.replaceAll(' ,', ', ');
							spoptsnames = spoptsnames.replaceAll(' , ', ', ');
							spoptsnames = spoptsnames.replaceAll(', ', ',');
							var spoptsnamesArr = spoptsnames.split(",");
							//need to find page name
							temptypehtml ='	<select name="wprevpro_'+ffields_array[i].name+'" id="wprevpro_'+ffields_array[i].name+'">';
							for (let i = 0; i < spoptsArr.length; i++) {
							  temptypehtml += '<option value="'+spoptsArr[i]+'">'+spoptsnamesArr[i]+'</option>';
							}
							temptypehtml += '</select>';
						}
					} else if(temptype=="select_tag"){
						//get comman string of options. opt1, opt2, opt3,
						if(ffields_array[i].selopts){
							var opts = ffields_array[i].selopts;
							opts = opts.replaceAll(' ,', ', ');
							opts = opts.replaceAll(' , ', ', ');
							opts = opts.replaceAll(', ', ',');
							var optsArr = opts.split(",");
							temptypehtml ='	<select class="wprevpro_multiselect" name="wprevpro_'+ffields_array[i].name+'" id="wprevpro_'+ffields_array[i].name+'" multiple style="display: none;">';
							for (let i = 0; i < optsArr.length; i++) {
							  temptypehtml += '<option value="'+optsArr[i]+'">'+optsArr[i]+'</option>';
							}
							temptypehtml += '</select>';
							
							temptypehtml += '<div class="wprev_select">';
							for (let i = 0; i < optsArr.length; i++) {
							  temptypehtml += '<div class="wprev_select__item">'+optsArr[i]+'</div>';
							}
							temptypehtml += '</div>';
						}
					}
					
					if(temptype=="social_links" ||temptype=="review_rating"){
						var hiderestfinal = '';
					} else {
						var hiderestfinal = hidemeclass;
						if(hidemeclass=='wprevhideme'){
							rofform = 'rofform';
						} else {
							rofform ='';
						}
					}
					
					//hide if show label not set to on
					var tempshowlabel ='';
					var tempshowlabelhtml ='';
					if(ffields_array[i].show_label=='on' || btnstyleclass=='btn1' || btnstyleclass=='btn2'){
						tempshowlabelhtml = '<label for="wprevpro_'+ffields_array[i].name+'">'+iconinlabel+ffields_array[i].label+'</label>';
					}
					
					//span after changes for consent box
					var spanafterhtml = '<span class="after">'+ffields_array[i].after+'</span>';
					if(temptype=="review_consent" || temptype=="checkbox"){
						spanafterhtml = '<span class="wprev_consent">'+ffields_array[i].after+'</span>';
					}
					
					//console.log('hidemeclass3:'+hidemeclass);

					formhtml = formhtml + '<div class="wprevform-field wprevpro-field-'+ffields_array[i].name+' '+hidemeclass+' '+rofform+' '+hiderestfinal+' '+btnstyleclass+'">'+tempshowlabelhtml+'	\
					'+temprequiredhtml+'	\
					<span class="before">'+ffields_array[i].before+'</span>	\
					'+temptypehtml+'	\
					'+spanafterhtml+'	\
					</div>';
				
				}
			}

			
			//for recaptcha
			formhtml = formhtml + '<div id="div_recaptcha" class="wprevform-field '+rofform+' '+hiderestfinal+'"></div>';
			//for custom button text
			var showbtntext = 'Submit Review';
			if(fmisc_obj.btntext!='' && typeof fmisc_obj.btntext!='undefined'){
				showbtntext = fmisc_obj.btntext;
			}
			//for custom button class wprevpro_template_btn_class
			var showbtnclass = '';
			if(fmisc_obj.btnclass!='' && typeof fmisc_obj.btnclass!='undefined'){
				showbtnclass = fmisc_obj.btnclass;
			}
			//btn style. fmisc_obj.btnstyle
			var defaultbtnclass = '';
			if(fmisc_obj.btnstyle!='' && typeof fmisc_obj.btnstyle!='undefined'){
				if(fmisc_obj.btnstyle=='default'){
					defaultbtnclass = "btnwprevdefault";
				} else if(fmisc_obj.btnstyle=='btn2'){
					defaultbtnclass = "btnwprevstyle2";
				}
			}
			
			formhtml = formhtml + '<div class="wprevform-field wprevpro_submit '+rofform+' '+hiderestfinal+'">	\
						<label><input type="button" id="wprevpro_submit_review" name="wprevpro_submit_review" value="'+showbtntext+'" class="'+showbtnclass+' '+defaultbtnclass+'" tabindex="0"></label>	\
					</div>	\
				</form>';
			
			$('.wprevpro_form_inner').html(formhtml);	
					
			//hide and show slow to reveal
			$('#wprevpro_form').hide();
			
			//loaded recaptcha with enque scripts should be loaded by now
			if(fmisc_obj.captchaon=="v2" && fmisc_obj.captchasite !=''){
				grecaptcha.render('div_recaptcha', {
				  'sitekey' : fmisc_obj.captchasite
				});
			}
			
			$('#wprevpro_form').fadeIn('slow');

		}
		
		//used to find icon based on link url in the above function
		//alert(adminjs_script_vars.pluginsUrl);
		//https://wordpress-110055-405301.cloudwaysapps.com/wp-content/plugins/wp-review-slider-pro-premium
		//console.log(adminjs_script_vars.allrevstypearray);
		//console.log(returniconhtml('https://search.google.com/local/writereview?placeid=ChIJOUW7JL0RYogRgDxol-LP_sU','sicon'));
		
		function returniconhtml(linkurl,displaytype,lname){
			var allrestypearray = eval(adminjs_script_vars.allrevstypearray);
			var temptype ='';
			var imaghtml = '';
			allrestypearray.push("g.page");
			//console.log('lname:'+lname);
			//console.log(allrestypearray);
			//google fix for g.page leave review pop-up
			//console.log(allrestypearray);
			//console.log(lname);
			//default to returning just the name
			if(displaytype==''){
				imaghtml = lname;
			} else {
			//if the linkurl contains text that matches in the array allrevstypearray then we return that image
				for (var i = 0; i < allrestypearray.length; i++) {
					temptype =allrestypearray[i].toLowerCase();
					//console.log('match:'+temptype+':'+linkurl+':'+lname);
					if (linkurl.indexOf(temptype)>-1 || temptype==lname.toLowerCase()) {
					  if(displaytype=='sicon'){
						imaghtml = '<img src="'+adminjs_script_vars.pluginsUrl+'/public/partials/imgs/'+temptype+'_small_icon.png" alt="'+temptype+' Logo" class="wprevpro_form_site_logo">';
					  } else if(displaytype=='licon'){
						imaghtml = '<img src="'+adminjs_script_vars.pluginsUrl+'/public/partials/imgs/branding-'+temptype+'-badge_50.png" alt="'+temptype+' Logo" class="wprevpro_form_site_logo">';
					  }
					  break;
					}
				  temptype ='';
				}
			}
			
			return imaghtml;
		}
		
		//hide rating after click, if setting turned on .hasClass( "hideafterclick" )
		$('.wprevpro_form_inner').on( "click", ".wprevpro-rating", function() {
			if($(this).closest('.wprevpro-rating-wrapper').hasClass('hideafterclick')){
				$(this).closest('.wprevpro-field-review_rating').hide();
			}
		});
			
		//when clicking stars on preview form
		$('.wprevpro_form_inner').on( "click", ".wprevpro-rating-radio-lbl", function() {
			var clickedstar = $( this ).prev().val();
			var clickedelement = $( this );
			//find out if we are hiding social links logic
			hideshowrestofform(clickedelement,clickedstar);
		});
		//when clicking thumbs up or down
		$('.wprevpro_form_inner').on( "click", "#wppro_fvoteup", function() {
			var clickedstar = 5;
			var clickedelement = $( this );
			changthumbonclick('up',clickedelement);
			//find out if we are hiding social links logic
			hideshowrestofform(clickedelement,clickedstar);
		});
		$('.wprevpro_form_inner').on( "click", "#wppro_fvotedown", function() {
			var clickedstar = 2;
			var clickedelement = $( this );
			changthumbonclick('down',clickedelement);
			//find out if we are hiding social links logic
			hideshowrestofform(clickedelement,clickedstar);
		});
		
		//for changing thumbs icons on click wppro_updown_yellobg
			function changthumbonclick(voteupdown,clickedelement){
				var voteupbtn = clickedelement.closest('.wprevpro-rating').find('#wppro_fvoteup');
				var votedownbtn = clickedelement.closest('.wprevpro-rating').find('#wppro_fvotedown');
				if(voteupdown=='up'){
					if(voteupbtn.hasClass( "svg-wprsp-thumbs-o-up" )){
						voteupbtn.removeClass( "svg-wprsp-thumbs-o-up" );
						voteupbtn.addClass( "svg-wprsp-thumbs-up" );
						votedownbtn.removeClass( "svg-wprsp-thumbs-down" );
						votedownbtn.addClass( "svg-wprsp-thumbs-o-down" );
					} else if(voteupbtn.hasClass( "svg-wprsp-smile-o" ) || voteupbtn.hasClass( " svg-smileselect" )){
						voteupbtn.addClass( "svg-smileselect" );
						//voteupbtn.removeClass( "svg-wprsp-smile-o" );
						votedownbtn.removeClass( "svg-smileselect" );
						//votedownbtn.addClass( "svg-wprsp-frown-o" );
					}
				} else if(voteupdown=='down'){
					if(voteupbtn.hasClass( "svg-wprsp-thumbs-up" ) || voteupbtn.hasClass( "svg-wprsp-thumbs-o-up" )){
						voteupbtn.addClass( "svg-wprsp-thumbs-o-up" );
						voteupbtn.removeClass( "svg-wprsp-thumbs-up" );
						votedownbtn.addClass( "svg-wprsp-thumbs-down" );
						votedownbtn.removeClass( "svg-wprsp-thumbs-o-down" );
					} else if(votedownbtn.hasClass( "svg-wprsp-frown-o" ) || votedownbtn.hasClass( " svg-smileselect" )){
						votedownbtn.addClass( "svg-smileselect" );
						//votedownbtn.removeClass( "svg-wprsp-frown-o" );
						voteupbtn.removeClass( "svg-smileselect" );
						//voteupbtn.addClass( "svg-wprsp-smile-o" );
					}
				}
			}
		
		//hiding or showing rest of form logic
		function hideshowrestofform(clickedelement,clickedstar){
			if(clickedstar>globshowval){
				//show social links
				$( clickedelement ).closest('form').find('.wprevpro-field-social_links').removeClass('wprevhideme');
				$( clickedelement ).closest('form').find('.wprevpro-field-social_links').hide();
				$( clickedelement ).closest('form').find('.wprevpro-field-social_links').show('2000');
				//what to do with rest of form
				if(globhiderest=='hide'){
					$( clickedelement ).closest('form').find('.rofform').hide();
				}
			} else {
				$( clickedelement ).closest('form').find('.wprevpro-field-social_links').hide('2000');
				//what to do with rest of form
				if(globhiderest=='hide'){
					$( clickedelement ).closest('form').find('.rofform').show('2000');
				}
			}
		}
		
		
		
		//update the form field values, called when editing or starting new form.---------------------------
		function updateformfields(formobject){
			//remove test html
			$('#custom_fields_list').html('');
			
			//update title
			var ftitle = formobject.title;
			$( "#wprevpro_template_title" ).val(ftitle);
			
			//update required field wprevpro_form_show_required_text
			var frequiredshow = formobject.title;
			var frequiredtext = formobject.title;
			$( "#wprevpro_form_show_required_text" ).val(ftitle);
			
			//update css field
			var fcss = formobject.form_css;
			$( "#wprevpro_form_css" ).val(fcss);
			
			//update custom html
			var fhtml = formobject.form_html;
			if(fhtml){
				fhtml = fhtml.replace(/\\(.)/mg, "$1");
			} else {
				fhtml ='';
			}
			$( "#wprevpro_form_headerhtml" ).val(fhtml);
			
			//update notify email field
			var femail = formobject.notifyemail;
			$( "#wprevpro_template_notify_email" ).val(femail);
			
			//captcha settings, pull from form_misc
			if(formobject.form_misc!=''){
				var fmisc = formobject.form_misc;
				var fmisc_obj = JSON.parse(fmisc);
				//console.log(fmisc_obj);
				$( "#wprevpro_form_showcaptcha" ).val(fmisc_obj.captchaon);
				$( "#wprevpro_form_cap_sitekey" ).val(fmisc_obj.captchasite);
				$( "#wprevpro_form_cap_secretekey" ).val(fmisc_obj.captchasecrete);
				//required text
				$( "#wprevpro_form_show_required_text" ).val(fmisc_obj.requiredlabelshow);
				$( "#wprevpro_form_required_text" ).val(fmisc_obj.requiredlabeltext);
				
				//update button text field if set
				var btntext = fmisc_obj.btntext;
				$( "#wprevpro_template_btn_text" ).val(btntext);
				
				//update button style
				var btnstyle = fmisc_obj.btnstyle;
				$("#wprevpro_template_btn_style").val(btnstyle);
				
				//update button class field
				var btnclass = fmisc_obj.btnclass;
				$("#wprevpro_template_btn_class").val(btnclass);
				
				//update successmesage
				var successmsg = fmisc_obj.successmsg;
				$( "#wprevpro_template_success_msg" ).val(successmsg);
				
				
				//update show on click values
				var showonclick = fmisc_obj.showonclick;
				var showonclicktext = fmisc_obj.showonclicktext;
				$( "#wprevpro_form_showonclick" ).val(showonclick);
				$( "#wprevpro_form_showonclick_txt" ).val(showonclicktext);
				
				//update autopopup
				var autopopval = '';
				if(fmisc_obj.autopopup){
					autopopval = fmisc_obj.autopopup;
					$( "#wprevpro_form_autopopup" ).val(autopopval);
				}
				
				//update ajax
				var useajax = fmisc_obj.useajax;
				$( "#wprevpro_form_useajax" ).val(useajax);
				
				if(fmisc_obj.redirecturl){
					$( "#wprevpro_form_redirecturl" ).val(fmisc_obj.redirecturl);
				}

				
				$('.formsubmittype').hide();
				$('#wprevpro_form_redirecturl').hide();
				if($('#wprevpro_form_useajax').val()=="prd"){
					$('#wprevpro_form_redirecturl').show();
					//change description
					$('.pageredirect').show();
					
				} else if($('#wprevpro_form_useajax').val()=="no"){
					$('.pagereload').show();
				} else if($('#wprevpro_form_useajax').val()=="yes"){
					$('.pageajax').show();
				}
			
				
				//update autoapprove
				var autoapprove = fmisc_obj.autoapprove;
				if(autoapprove=='' || typeof autoapprove=='undefined'){
					autoapprove = 'no';
				}
				$( "#wprevpro_form_autoapprove" ).val(autoapprove);
				
				//update icon fields
				var iconimage = '';
				if(fmisc_obj.iconimage){
					iconimage = fmisc_obj.iconimage;
					if(iconimage=='' || typeof iconimage=='undefined'){
						iconimage = '';
					}
				}
				$( "#wprevpro_form_icon_image_url" ).val(iconimage);
				
				var iconimagelink = '';
				if(fmisc_obj.iconlink){
					iconimagelink = fmisc_obj.iconlink;
					if(iconimagelink=='' || typeof iconimagelink=='undefined'){
						iconimagelink = '';
					}
				}
				$( "#wprevpro_form_icon_link_url" ).val(iconimagelink);
				
				//tags
				var tagsinput = '';
				if(fmisc_obj.tags){
					tagsinput = fmisc_obj.tags;
					if(tagsinput=='' || typeof tagsinput=='undefined'){
						tagsinput = '';
					}
				}
				$( "#wprevpro_form_tags" ).val(tagsinput);
				//ip address
				var ipinput = '';
				if(fmisc_obj.ip){
					ipinput = fmisc_obj.ip;
					if(ipinput=='' || typeof ipinput=='undefined'){
						ipinput = '';
					}
				}
				$( "#wprevpro_form_ip" ).val(ipinput);
				
				//one sub
				var onesub = '';
				if(fmisc_obj.onesub){
					onesub = fmisc_obj.onesub;
					if(onesub=='' || typeof ipinput=='undefined'){
						onesub = '';
					}
				}
				$( "#wprevpro_form_onesub" ).val(onesub);
				
				//one sub text
				var onesub_msg = '';
				if(fmisc_obj.onesub_msg){
					onesub_msg = fmisc_obj.onesub_msg;
					if(onesub_msg=='' || typeof ipinput=='undefined'){
						onesub_msg = '';
					}
				}
				$( "#wprevpro_form_onesub_msg" ).val(onesub_msg);

			}
			if($( "#wprevpro_form_showcaptcha" ).val()=="none"){
				$('#divrecap_fields').hide('slow');
			} else {
				$('#divrecap_fields').show('slow');
			}
			if($( "#wprevpro_form_show_required_text" ).val()=="" || $( "#wprevpro_form_show_required_text" ).val()==null){
				$( "#wprevpro_form_show_required_text" ).val('show');
			}
	
			//
			
			//loop through the form fields and add them to the page
			var ffields = formobject.form_fields;
			//console.log(JSON.parse(ffields));
			var ffields_array = JSON.parse(ffields);
			var arrayLength = ffields_array.length;
			//alert(arrayLength);
			//console.log(ffields_array);
			var tempfiledhtml = '';
			for (var i = 0; i < arrayLength; i++) {
				//alert(ffields_array[i].label);
				var tempreqhtml='';
				if(ffields_array[i].required=="on"){
					tempreqhtml= 'checked="checked"';
				}
				var tempslhtml='';
				if(ffields_array[i].show_label=="on"){
					tempslhtml= 'checked="checked"';
				}
				var temphidefieldhtml='';
				var temphidencss='';
				if(ffields_array[i].hide_field=="on"){
					temphidefieldhtml= 'checked="checked"';
					//hiding this field so add css class to li header
					temphidencss = 'hiddenfield';
				}
				
				
				var hidethis_rating = "";
				var tempformvaluehtml='<input type="text" name="fields['+i+'][default_form_value]" value="'+ffields_array[i].default_form_value+'" class="formfield">';
				var tempdisplayvaluehtml='<input type="text" name="fields['+i+'][default_display_value]" value="'+ffields_array[i].default_display_value+'" class="formfield">';

				//change inputs for review_rating--------------
				var tempformrrhtml = '';
				var hidethis_rrthumb = '';
				if(ffields_array[i].input_type=="review_rating" || ffields_array[i].input_type=="starrating"){
					var temptypechdt1='';
					var temptypechdt2='';
					var temphideicontr='';
					if(ffields_array[i].starornot==''){
						temptypechdt1='selected';
						temphideicontr='wprevhideme';
					} else if(ffields_array[i].starornot=='updown'){
						temptypechdt2='selected';
						hidethis_rrthumb = "wprevhideme";
					}
					tempformrrhtml = '<tr class="field-secondary"><th>Display</th><td>	\
											<select name="fields['+i+'][starornot]" class="field-label formfield">	\
											  <option value="" '+temptypechdt1+'>'+adminjs_script_vars.msg6+'</option>	\
											  <option value="updown" '+temptypechdt2+'>Up or Down</option>	\
											  </select> <span id="starornotmsg" style="display:none;">'+adminjs_script_vars.msg7+'</span></td></tr>';	
					var tempicon1='';
					var tempicon3='';
					if(ffields_array[i].star_icon==''){
						tempicon1='selected';
					} else if(ffields_array[i].star_icon=='smileys'){
						tempicon3='selected';
					}						  
					tempformrrhtml = tempformrrhtml + '<tr class="field-secondary tr_star_icon '+temphideicontr+'"><th>Icon</th><td>	\
											<select name="fields['+i+'][star_icon]" class="field-label formfield">	\
											  <option value="" '+tempicon1+'>Thumbs</option>	\
											  <option value="smileys" '+tempicon3+'>Smileys</option>	\
											  </select></td></tr>';	
					//add max rating value
					tempformrrhtml = tempformrrhtml + '<tr class="field-secondary trmaxrating '+hidethis_rrthumb+'"><th>Max Rating</th><td>	\
											<input type="number" name="fields['+i+'][maxrating]" value="'+ffields_array[i].maxrating+'" class="formfield" min="1" max="10" size="2"></td></tr>';	
					
					tempformvaluehtml='<input type="number" name="fields['+i+'][default_form_value]" value="'+ffields_array[i].default_form_value+'" class="formfield" min="0" max="10" size="2"> '+adminjs_script_vars.msg8+'';
					tempdisplayvaluehtml='<input type="number" name="fields['+i+'][default_display_value]" value="'+ffields_array[i].default_display_value+'" class="formfield" min="0" max="10" size="2"> '+adminjs_script_vars.msg8+'';
					hidethis_rating = "wprevhideme";
					
					//add hide or show afterclick 
					var tempselectafterclick1='selected';
					var tempselectafterclick2='';
					if(ffields_array[i].afterclick=='hide'){
						tempselectafterclick1='';
						tempselectafterclick2='selected';
					}
					tempformrrhtml = tempformrrhtml + '<tr class="field-secondary "><th>After Click</th><td>	\
											<select name="fields['+i+'][afterclick]" class="field-label formfield">	\
											  <option value="" '+tempselectafterclick1+'>Show</option>	\
											  <option value="hide" '+tempselectafterclick2+'>Hide</option>	\
											  </select></td></tr>';	
				}
				//change inputs for social links
				var hidethis_ss = "";
				var tempformsshtml="";
				var temphiderest='';
				if(ffields_array[i].hiderest=="on"){
					temphiderest= 'checked="checked"';
				}
				
				if(ffields_array[i].input_type=="social_links"){
					hidethis_ss = "wprevhideme";
					tempformsshtml = '<tr id="link1" class="field-secondary"><th>'+adminjs_script_vars.msg9+' 1<a href="https://ljapps.com/?p=1045&preview=true" target="_blank" class="wprev_quest update-plugins count-1"><span class="plugin-count">?</span></a></th><td><input type="text" placeholder="Site Name" name="fields['+i+'][lname1]" value="'+ffields_array[i].lname1+'" class="formfield lname">	\
					<input placeholder="URL" type="text" name="fields['+i+'][lurl1]" value="'+ffields_array[i].lurl1+'" class="formfield lurl"></td></tr>';
									
					//loop here and build the rest
					for (let k = 2; k < 51; k++) {
						var tempname = 'lname'+k;
						var tempurl = 'lurl'+k;
						var tempimgurl = 'limgurl'+k;
						if(ffields_array[i][tempname] && ffields_array[i][tempurl] && !ffields_array[i][tempimgurl]){
							tempformsshtml = tempformsshtml + '<tr id="link'+k+'" class="field-secondary"><th>'+adminjs_script_vars.msg9+' '+k+'</th><td><input type="text" name="fields['+i+'][lname'+k+']" value="'+ffields_array[i][tempname]+'" class="formfield lname">	<input type="text" name="fields['+i+'][lurl'+k+']" value="'+ffields_array[i][tempurl]+'" class="formfield lurl"></td></tr>';
						} else if(ffields_array[i][tempname] && ffields_array[i][tempurl] && ffields_array[i][tempimgurl]){
							//for custom links
							tempformsshtml = tempformsshtml + '<tr id="link'+k+'" class="field-secondary"><td colspan="2"><input type="text" name="fields['+i+'][lname'+k+']" value="'+ffields_array[i][tempname]+'" class="formfield clurl">	<input type="text" name="fields['+i+'][lurl'+k+']" value="'+ffields_array[i][tempurl]+'" class="formfield clurl">	<input type="text" name="fields['+i+'][limgurl'+k+']" value="'+ffields_array[i][tempimgurl]+'" class="formfield clurl"></td></tr>';
						}
					}

					//for adding another link
					tempformsshtml = tempformsshtml	+ '<tr id="addlinkrow" class="field-secondary"><th></th><td>\
										<span id="aal" class="linkaddspan">Add Another Link</span> <span id="aacl" class="linkaddspan">Add Custom Link</span></td></tr>';	
									
					//for social link display type
					var temptypechck='';
					var temptypechcksi='';
					var temptypechckli='';
					if(ffields_array[i].displaytype=='sicon'){
						temptypechcksi='selected';
					} else if(ffields_array[i].displaytype=='licon'){
						temptypechckli='selected';
					}
					tempformsshtml = tempformsshtml	+ '<tr class="field-secondary"><th>Display</th><td>\
										<select name="fields['+i+'][displaytype]" class="field-label formfield">	\
														  <option value="">'+adminjs_script_vars.msg10+'</option>	\
														  <option value="sicon" '+temptypechcksi+'>'+adminjs_script_vars.msg11+'</option>	\
														  <option value="licon" '+temptypechckli+'>'+adminjs_script_vars.msg12+'</option>	\
														  </select></td></tr>';			
					
					var tempselected=[];
					for (var j = 0; j < 5; j++) {
						if(Number(ffields_array[i].showval)==j && ffields_array[i].showval!=''){
							tempselected[j]='selected';
						} else {
							tempselected[j]='';
						}
					}
					var temphideresthere = '';
					if(ffields_array[i].hiderest=='hide'){
						temphideresthere = 'selected';
					}
					tempformsshtml = tempformsshtml	+ '<tr class="field-secondary"><th>Logic</th><td>Only show links when Rating is \
					<select name="fields['+i+'][showval]" class="field-label formfield">	\
									  <option value=""></option>	\
									  <option value="4" '+tempselected[4]+'>> 4</option>	\
									  <option value="3" '+tempselected[3]+'>> 3</option>	\
									  <option value="2" '+tempselected[2]+'>> 2</option>	\
									  <option value="1" '+tempselected[1]+'>> 1</option>	\
									  <option value="0" '+tempselected[0]+'>> 0</option>	\
						</select>	\
						</br><label><select name="fields['+i+'][hiderest]" class="field-label formfield">	\
									  <option value="">'+adminjs_script_vars.Show+'</option>	\
									  <option value="hide" '+temphideresthere+'>'+adminjs_script_vars.Hide+'</option>	\
									  </select><span class="help inline">'+adminjs_script_vars.msg13+'</span></label></td></tr>';	
				}
				
				
				//hide inputs for avatar field, add wprevhideme class, also add style options
				var hidethis_avatar = "";
				var tempformpicvidhtml="";
				if(ffields_array[i].input_type=="review_avatar" || ffields_array[i].input_type=="review_video"){
					hidethis_avatar = "wprevhideme";
					
					var picorvid = '';
					if(ffields_array[i].input_type=="review_avatar"){
						picorvid = 'pic';
					} else {
						picorvid = 'vid';
					}
					
					var temptypepvdt1='';
					var temptypepvdt2='';
					var temptypepvdt3='';
					
					var tempobjkey = picorvid+'style';
					
					if(ffields_array[i][tempobjkey]==''){
						temptypepvdt1='selected';
					} else if(ffields_array[i][tempobjkey]=='btn1'){
						temptypepvdt2='selected';
					} else if(ffields_array[i][tempobjkey]=='btn2'){
						temptypepvdt3='selected';
					}
					
					tempformpicvidhtml = '<tr class="field-secondary"><th>Style</th><td>	\
											<select name="fields['+i+']['+picorvid+'style]" class="field-label formfield">	\
											  <option value="" '+temptypepvdt1+'>'+adminjs_script_vars.msg25+'</option>	\
											  <option value="btn1" '+temptypepvdt2+'>'+adminjs_script_vars.msg26+'</option>	\
											  <option value="btn2" '+temptypepvdt3+'>'+adminjs_script_vars.msg27+'</option>	\
											  </select></td></tr>';	
				}
				
				//change for consent field 
				var hidethis_consent = "";
				if(ffields_array[i].input_type=="review_consent"){
					hidethis_consent = "wprevhideme";
				}
				var deleteinputhtml="";
				if(ffields_array[i].name.includes("custom_")){
					deleteinputhtml='<span class="deletecustominput">delete input</span>';
				}
				
				//change for custom select field.
				if(ffields_array[i].name.includes("custom_select_") ){
					var templabelopt = 'Comma Seperated Options';
					var tempplaceopt = 'option1, option2, option3';
					if(!ffields_array[i].selopts){
						ffields_array[i].selopts='';
					}
					if(!ffields_array[i].seloptsname){
						ffields_array[i].seloptsname='';
					}
					if(ffields_array[i].name.includes("custom_select_page_") ){
						templabelopt = 'Comma Seperated Page IDs';
						tempplaceopt = '11, 15, 18';
						tempformsshtml = tempformsshtml	+  '<tr class="field-secondary"><th>'+templabelopt+'</th><td><input type="text" placeholder="'+tempplaceopt+'" name="fields['+i+'][selopts]" value="'+ffields_array[i].selopts+'" class="formfield">	\
						</td></tr>';
						tempformsshtml = tempformsshtml	+  '<tr class="field-secondary"><th>Comma Seperated Page Labels</th><td><input type="text" placeholder="Page 11 label, page 15 label, page 18 label" name="fields['+i+'][seloptsname]" value="'+ffields_array[i].seloptsname+'" class="formfield">	\
						</td></tr>';
						//we need to add option for creating woo review.
						var create_woohtml='';
						if(ffields_array[i].create_woo=="on"){
							create_woohtml= 'checked="checked"';
						}
				
						tempformsshtml = tempformsshtml	+ '<tr class="field-label-row"><th>Create WooCommerce Review</th><td><label><input type="checkbox" name="fields['+i+'][create_woo]" '+create_woohtml+' class="formfield"><span class="help inline">Copy review to the WooCommerce database.</span></label></td></tr>';
						
						
					} else if(ffields_array[i].name.includes("custom_select_tag_") ){
						templabelopt = 'Comma Seperated Tags';
						tempplaceopt = 'tag1, tag2, tag3';
						tempformsshtml = tempformsshtml	+  '<tr class="field-secondary"><th>'+templabelopt+'</th><td><input type="text" placeholder="'+tempplaceopt+'" name="fields['+i+'][selopts]" value="'+ffields_array[i].selopts+'" class="formfield">	\
						</td></tr>';
					} else {
						tempformsshtml = tempformsshtml	+  '<tr class="field-secondary"><th>'+templabelopt+'</th><td><input type="text" placeholder="'+tempplaceopt+'" name="fields['+i+'][selopts]" value="'+ffields_array[i].selopts+'" class="formfield">	\
						</td></tr>';
					}
					
					hidethis_rrthumb = "wprevhideme";	//hiding default values
				}
				
				tempfiledhtml = '<li id="field-'+i+'" class="'+temphidencss+'" style="">	\
				<div class="custom-field-header"><span class="link open" title="'+adminjs_script_vars.msg14+'">	\
						<a class="field" href="#">'+ffields_array[i].label+'</a>	\
						<span class="handle ui-sortable-handle" title="'+adminjs_script_vars.msg15+'"></span>	\
						<span class="toggle"></span>	\
					</span></div>	\
				<div class="custom-field" style="display: none;"><table class="field-table"><tbody>	\
					<tr class="wprevhideme"><th>Type</th><td>'+ffields_array[i].input_type+'</td></tr>	\
					<tr class="field-secondary '+hidethis_ss+'"><th>'+adminjs_script_vars.Required+'</th><td>	\
					<input type="checkbox" name="fields['+i+'][required]" '+tempreqhtml+' class="formfield">	\
					<span class="help inline">'+adminjs_script_vars.msg16+'</span></td></tr>	\
					<tr class="field-label-row"><th>Label</th><td><input class="field-label" type="text" name="fields['+i+'][label]" value="'+ffields_array[i].label+'"><label><input type="checkbox" name="fields['+i+'][show_label]" '+tempslhtml+' class="formfield"><span class="help inline">'+adminjs_script_vars.msg17+'</span></label></td></tr> 	\
					<tr class="field-secondary '+hidethis_ss+hidethis_avatar+hidethis_rating+hidethis_consent+'"><th>'+adminjs_script_vars.Placeholder+'</th><td><input type="text" name="fields['+i+'][placeholder]" value="'+ffields_array[i].placeholder+'" class="formfield"></td></tr>	\
					<tr class="field-secondary"><th>'+adminjs_script_vars.Before+'</th><td><input type="text" name="fields['+i+'][before]" value="'+ffields_array[i].before+'" class="formfield"></td></tr>'+tempformsshtml+'	\
					<tr class="field-secondary"><th>'+adminjs_script_vars.After+'</th><td><input type="text" name="fields['+i+'][after]" value="'+ffields_array[i].after+'" class="formfield"></td></tr>'+tempformrrhtml+'	\
					<tr class="field-secondary tr_defaultformvalue '+hidethis_ss+hidethis_avatar+hidethis_consent+hidethis_rrthumb+'"><th>'+adminjs_script_vars.msg18+'</th><td>	\
					'+tempformvaluehtml+'	\
					<span class="help">'+adminjs_script_vars.msg19+'</span></td></tr>	\
					<tr class="field-secondary tr_defaultsubmitvalue '+hidethis_ss+hidethis_consent+hidethis_rrthumb+'"><th>'+adminjs_script_vars.msg20+'</th><td>	\
					'+tempdisplayvaluehtml+'	\
					<span class="help">'+adminjs_script_vars.msg21+'</span></td></tr>'+tempformpicvidhtml+'	\
					<tr class="field-secondary trhidefield"><td colspan="2">	\
					<input type="checkbox" name="fields['+i+'][hide_field]" '+temphidefieldhtml+' class="formfield">	\
					<span class="help inline">'+adminjs_script_vars.msg22+'</span>'+deleteinputhtml+'</td></tr>	\
					</tbody></table>	\
				<input type="hidden" name="fields['+i+'][input_type]" value="'+ffields_array[i].input_type+'" class="formfield">	\
				<input type="hidden" name="fields['+i+'][name]" value="'+ffields_array[i].name+'" class="formfield">	\
				</div></li>';
				$('#custom_fields_list').append(tempfiledhtml);
			}
			//add buttons for adding more fields adminjs_script_vars.pluginsUrl
			var newfieldhtml = '<div id="outerdivaddinput"><span id="addforminputbtn" class="linkaddspan">Add Form Input</span>	\
								<div id="addinputtype" style="display:none;">	\
								<select name="newinputtypesel" id="newinputtypesel">	\
									  <option value="">Choose Type of Input</option>	\
									  <option value="text">Text</option>	\
									  <option value="textarea">Text Area</option>	\
									  <option value="checkbox">Check Box</option>	\
									  <option value="starrating">Star Rating</option>	\
									  <option value="select">Select</option>	\
									  <option value="select_page">Page/Post/Product Select</option>	\
									  <option value="select_tag">Tag Multi-Select</option>	\
									  <option value="media">Image</option>	\
								</select>	\
								<span id="addforminputbtnsubmit" class="button button-secondary">Add Input</span>	\
								<div id="update_form_msg_div"><img src="'+adminjs_script_vars.pluginsUrl+'/public/partials/imgs/loading_ripple.gif" id="savingformimg" class="wprptemplate_update_loading_image" style="display:none;"><span id="update_form_msg"></span></div>	\
								</div>	\
								</div>';
			if ($('#newformform').find('#outerdivaddinput').length < 1){
				//make sure it isn't already on the page
			  $('#custom_fields_list').after(newfieldhtml);
			}
			//hide custom input loading image
			//show loading image
			$('#newformform').find('#savingformimg').hide();
			
		}
		
		//for showing add form input on click
		$('#newformform').on( "click", "#addforminputbtn", function() {
			$(this).next('#addinputtype').toggle('slow');
		});
		//for clicking the actual button to add the new input
		$('#newformform').on( "click", "#addforminputbtnsubmit", function() {
			//show loading image
			$(this).next().find('#savingformimg').show();
			var inputtype = $(this).prev('#newinputtypesel').val();
			//get the last li number
			var lastid = $(this).closest('#outerdivaddinput').prev('#custom_fields_list').find('li').last().attr('id');
			lastid = lastid.replace(/\D/g,'');
			lastid = parseInt(lastid) + 1;
			//add the input
			var newinputhtml = '';
			var labeltext = inputtype.substr(0,1).toUpperCase()+inputtype.substr(1);
			if(inputtype=='checkbox'){
				//we need to hide certain things
				var display_placeholder = 'wprevhideme';
				var display_dfv = 'wprevhideme';
				var display_dsv = 'wprevhideme';
			}
			var custombefore = '';
			if(inputtype=='media'){
				custombefore = 'Enter the image or YouTube url. You can also upload an image, but not a video.';
			}
			newinputhtml = '<li id="field-'+lastid+'" class="custominputli" style="display:none;"><div class="custom-field-header"><span class="link open" title="click to open or close"><a class="field" href="#">Custom '+labeltext+'</a><span class="handle ui-sortable-handle" title="drag and drop to reorder"></span><span class="toggle"></span></span></div><div class="custom-field" style="display: none;"><table class="field-table"><tbody><tr class="wprevhideme"><th>Type</th><td>text</td></tr>	\
			<tr class="field-secondary "><th>Required</th><td><input type="checkbox" name="fields['+lastid+'][required]" class="formfield"><span class="help inline">Require this field for form submission.</span></td></tr>	\
			<tr class="field-label-row"><th>Label</th><td><input class="field-label" type="text" name="fields['+lastid+'][label]" value="Custom '+labeltext+'"><label><input type="checkbox" name="fields['+lastid+'][show_label]" checked="checked" class="formfield"><span class="help inline">Show this label on the form.</span></label></td></tr>	\
			<tr class="field-secondary '+display_placeholder+'"><th>Placeholder</th><td><input type="text" name="fields['+lastid+'][placeholder]" value="" class="formfield"></td></tr>	\
			<tr class="field-secondary"><th>Before</th><td><input type="text" name="fields['+lastid+'][before]" value="'+custombefore+'" class="formfield"></td></tr>	\
			<tr class="field-secondary"><th>After</th><td><input type="text" name="fields['+lastid+'][after]" value="" class="formfield"></td></tr>	\
			<tr class="field-secondary tr_defaultformvalue '+display_dfv+'"><th>Default Form Value</th><td><input type="text" name="fields['+lastid+'][default_form_value]" value="" class="formfield"><span class="help">Populate the field with this value.</span></td></tr>	\
			<tr class="field-secondary tr_defaultsubmitvalue '+display_dsv+'"><th>Default Submit Value</th><td><input type="text" name="fields['+lastid+'][default_display_value]" value="" class="formfield"><span class="help">Save this value with the testimonial if one is not input.</span></td></tr>	\
			<tr class="field-secondary trhidefield"><td colspan="'+lastid+'"><input type="checkbox" name="fields['+lastid+'][hide_field]" class="formfield"><span class="help inline">'+adminjs_script_vars.msg22+'</span><span class="deletecustominput">delete input</span></td></tr></tbody></table>	\
			<input type="hidden" name="fields['+lastid+'][input_type]" value="'+inputtype+'" class="formfield">	\
			<input type="hidden" name="fields['+lastid+'][name]" value="custom_'+inputtype+'_'+lastid+'" class="formfield"></div></li>';
			
			$('#custom_fields_list').append(newinputhtml);
			updatetheformindb('no','yes','yes');
		});
		
		//for deleting custom input
		$('#newformform').on( "click", ".deletecustominput", function() {
			$(this).closest('li').remove();
			updatetheformindb('no','no','yes');
		});

		
		//used for adding custom link form field
		$('#newformform').on( "click", "#aacl", function() {
			//add a custom link row
			var addlinkrow = $(this).closest('#addlinkrow');
			var linknum = addlinkrow.prev().attr("id");
			linknum = linknum.replace(/\D/g,'');
			linknum = parseInt(linknum)+1;
			//find what fields this is in case they have moved them. custom-field
			var li_id = $(this).closest('li').attr('id').match(/\d+/);
			//build html
			var newrowhtml = '<tr id="link'+linknum+'" class="field-secondary"><td colspan="2"><input type="text" name="fields['+li_id+'][lname'+linknum+']" value="" class="formfield clurl" placeholder="Site Name">	<input type="text" name="fields['+li_id+'][lurl'+linknum+']" value="" class="formfield clurl" placeholder="Site URL"> <input type="text" name="fields['+li_id+'][limgurl'+linknum+']" value="" class="formfield clurl" placeholder="Site Image URL"></td></tr>';
			//alert(linknum);
			addlinkrow.before(newrowhtml);
		});
		
		//used for adding another link form field
		$('#newformform').on( "click", "#aal", function() {
			//get link number from row above. Use it to add fields to form. placeholder="Name"
			var addlinkrow = $(this).closest('#addlinkrow');
			var linknum = addlinkrow.prev().attr("id");
			linknum = linknum.replace(/\D/g,'');
			linknum = parseInt(linknum)+1;
			//find what fields this is in case they have moved them. custom-field
			var li_id = $(this).closest('li').attr('id').match(/\d+/);
			//build html
			var newrowhtml = '<tr id="link'+linknum+'" class="field-secondary"><th>Link '+linknum+'</th><td><input type="text" name="fields['+li_id+'][lname'+linknum+']" value="" class="formfield lname" placeholder="Site Name">	<input type="text" name="fields['+li_id+'][lurl'+linknum+']" value="" class="formfield lurl" placeholder="Site URL"></td></tr>';
			//alert(linknum);
			addlinkrow.before(newrowhtml);
		});
		
		//---set hidden form values on change of any of the inputs---------------------------
		$("#newformform").on('change', 'input', function(){
			//console.log($(this).attr('name'));
			//console.log($(this).attr('type'));
			//console.log("here");
			
			var inputname = $(this).attr('name');
			var inputtype = $(this).attr('type');
			var inputval = $(this).val();
			var checkedornot = $(this).attr('checked');

			//add or remove checked value if this is a checkbox
			if(inputtype=='checkbox'){
				if ($(this).is(':checked')){
					$(this).prop('checked', true).attr('checked', 'checked');
					//if this is the hide this field button then update css of header div
					if(inputname.includes("hide_field")){
						$( this ).closest('li').addClass('hiddenfield');
					}
				}
				else {
					$(this).prop('checked', false).removeAttr('checked');
					if(inputname.includes("hide_field")){
						$( this ).closest('li').removeClass('hiddenfield');
					}
				}
			}
			
			//something on the form changed
			if(inputname=='wprevpro_template_title'){
				//updatetheformindb('no','no','no');	//turned off because we were getting duplicate forms
			} else {
				updatetheformindb('no','no','yes');
			}

		})
		
		
		//--on css change, captcha change
		$( "#wprevpro_form_css" ).on("change",function() {
			updatetheformindb('no','no','yes');
		});
		$( "#wprevpro_form_headerhtml" ).on("change",function() {
			updatetheformindb('no','no','yes');
		});
		
		//--on show hide required change
		$( "#wprevpro_form_show_required_text" ).on("change",function() {
			updatetheformindb('no','no','yes');
		});
		//--on captcha change
		$('#wprevpro_form_showcaptcha').on('change', function() {
		  	if($(this).val()=="none"){
				$('#divrecap_fields').hide('slow');
			} else {
				$('#divrecap_fields').show('slow');
			}
			updatetheformindb('no','no','yes');
		})
		//--on select change
		$("#newformform").on('change', 'select', function(){
			console.log($(this).attr('name'));
			console.log($(this).attr('type'));
			console.log($(this).val());
			var inputname = $(this).attr('name');
			var inputval = $(this).val();
			
			//check to see if changing star type
			if(inputname.includes("starornot")){
				if(inputval=='updown'){
					//hide the default form values
					$(this).closest('tbody').find('.tr_defaultformvalue').hide('slow');
					$(this).closest('tbody').find('.tr_defaultsubmitvalue').hide('slow');
					$(this).closest('tbody').find('.tr_star_icon').show('slow');
					$(this).closest('tbody').find('.trmaxrating ').hide('slow');
					$(this).closest('tbody').find('#starornotmsg ').show('slow');
					
				} else {
					$(this).closest('tbody').find('.tr_defaultformvalue').show('slow');
					$(this).closest('tbody').find('.tr_defaultsubmitvalue').show('slow');
					$(this).closest('tbody').find('.tr_star_icon').hide('slow');
					$(this).closest('tbody').find('.trmaxrating ').show('slow');
					$(this).closest('tbody').find('#starornotmsg ').hide('slow');
				}
			}
			//show hide page redirect settings
			$('.formsubmittype').hide();
			$('#wprevpro_form_redirecturl').hide();
			if($('#wprevpro_form_useajax').val()=="prd"){
				$('#wprevpro_form_redirecturl').show();
				//change description
				$('.pageredirect').show();
			} else if($('#wprevpro_form_useajax').val()=="no"){
				$('.pagereload').show();
			} else if($('#wprevpro_form_useajax').val()=="yes"){
				$('.pageajax').show();
			}
			
			
			updatetheformindb('no','no','yes');
		});
		
		//---------update the form----------------------
		//on submit button click
		$( "#wprevpro_addnewform_submit" ).on("click",function(event) {
			event.preventDefault();
			//make sure there is a title
			if($('#wprevpro_template_title').val()==''){
				alert("Please enter a title.");
				$( "#wprevpro_template_title" ).focus();
			} else if($('#wprevpro_form_useajax').val()=='prd' && $('#wprevpro_form_redirecturl').val()==''){
				alert("Please enter a redirect URL.");
				$( "#wprevpro_form_redirecturl" ).focus();
			} else {
				updatetheformindb('yes','no','no');
			}
		});

		
		function updatetheformindb(reloadpage='no',updateff='no', updatefprev='yes'){
			console.log('updating form..');
			
			
			//get all form values
			var ftitle = $( "#wprevpro_template_title" ).val();
			var ftid = $('#edittid').val();
			
			var femail = $('#wprevpro_template_notify_email').val();
			var fcss =$('#wprevpro_form_css').val();
			var fhtml =$('#wprevpro_form_headerhtml').val();
			
			var fmiscobj ={};
			fmiscobj.captchaon = $('#wprevpro_form_showcaptcha').val();
			fmiscobj.captchasite = $('#wprevpro_form_cap_sitekey').val();
			fmiscobj.captchasecrete = $('#wprevpro_form_cap_secretekey').val();
			fmiscobj.requiredlabelshow = $('#wprevpro_form_show_required_text').val();
			fmiscobj.requiredlabeltext = $('#wprevpro_form_required_text').val();
			fmiscobj.btntext = $('#wprevpro_template_btn_text').val();
			fmiscobj.btnstyle = $('#wprevpro_template_btn_style').val();
			fmiscobj.btnclass = $('#wprevpro_template_btn_class').val();
			fmiscobj.successmsg = $('#wprevpro_template_success_msg').val();
			fmiscobj.showonclick = $('#wprevpro_form_showonclick').val();
			fmiscobj.showonclicktext = $('#wprevpro_form_showonclick_txt').val();
			fmiscobj.autopopup = $('#wprevpro_form_autopopup').val();
			fmiscobj.useajax = $('#wprevpro_form_useajax').val();
			fmiscobj.redirecturl = $('#wprevpro_form_redirecturl').val();
			fmiscobj.autoapprove = $('#wprevpro_form_autoapprove').val();
			fmiscobj.iconimage = $('#wprevpro_form_icon_image_url').val();
			fmiscobj.iconlink = $('#wprevpro_form_icon_link_url').val();
			fmiscobj.tags = $('#wprevpro_form_tags').val();
			fmiscobj.ip = $('#wprevpro_form_ip').val();
			fmiscobj.onesub = $('#wprevpro_form_onesub').val();
			fmiscobj.onesub_msg = $('#wprevpro_form_onesub_msg').val();
			
			var fmiscobjstring = JSON.stringify(fmiscobj);
			//alert(fmiscobjstring);
			
			var optionsobj = {};
			var tempkey='';
			$("[name*='fields']").each(function(){
				//console.log(this);
				var inputtype = $(this).attr('type');
				tempkey = $(this).attr('name');
				if(inputtype=='checkbox'){
					if ($(this).is(':checked')){
						optionsobj[tempkey] = "on";
					} else {
						optionsobj[tempkey] = "";
					}
				} else {
					optionsobj[tempkey] = $(this).val();
				}
			});
			console.log(optionsobj)
			var jsonfields = JSON.stringify(optionsobj);

			//only submit if there is a title
			if(ftitle!=''){
				var senddata = {
					action: 'wprp_save_form',	//required
					wpfb_nonce: adminjs_script_vars.wpfb_nonce,
					title: ftitle,
					tid: ftid,
					email: femail,
					css: fcss,
					fhtml: fhtml,
					misc: fmiscobjstring,
					data: jsonfields,
					};
				//send to ajax to update db
				var jqxhr = jQuery.post(ajaxurl, senddata, function (response){
					//console.log(response);
					if (!$.trim(response)){
						alert(adminjs_script_vars.msg23);
					} else {
						//success, refresh page.
						//need the insert id for the form so we can update the preview
						templateid = Number(response);
						$('#edittid').val(templateid);
						if(reloadpage=='yes'){
							location.reload();
						} else {
							getformdata(templateid,updateff,updatefprev);
							
						}
					}
					
				});
				jqxhr.fail(function() {
				  alert(  adminjs_script_vars.msg24 );
				});
				
				//hide close button since we have already saved in db
				$( "#wprevpro_addnewtemplate_cancel" ).hide();
				$( "#wprevpro_addnewform_submit" ).show();
			}
		}
		
		
		
	});

})( jQuery );


