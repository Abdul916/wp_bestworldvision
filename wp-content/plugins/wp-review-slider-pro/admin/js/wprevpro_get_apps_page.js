
		
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
		
		var getrevformtempid='';

		//help button clicked
		$( "#wprevpro_helpicon_posts" ).on("click",function() {
		  openpopup(adminjs_script_vars.popuptitle, '<p>'+adminjs_script_vars.popupmsg+' </p>', "");
		});
		
		
		//find if we are setting type in a url para
		var getUrlParameter = function getUrlParameter(sParam) {
			var sPageURL = window.location.search.substring(1),
				sURLVariables = sPageURL.split('&'),
				sParameterName,
				i;
			for (i = 0; i < sURLVariables.length; i++) {
				sParameterName = sURLVariables[i].split('=');
				if (sParameterName[0] === sParam) {
					return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
				}
			}
			return false;
		};
		//now check if we are highlighting a previous funnel
		var gervfid = getUrlParameter('vfid');
		if(gervfid){
			//only higlight if greater than 1 row.
			var numrows = $( ".locationrow" ).length;
			if(numrows!=1){
				$( "#"+gervfid ).css('background-color', '#ffff6f');
				//remove highlight after 8 seconds.
				setTimeout(function(){ $( "#"+gervfid ).css('background-color', ''); }, 5000);
			}
		}
		
		
		//launch pop-up windows code--------
		function openpopup(title, body, body2){
			//set text
			jQuery( "#popup_titletext").html(title);
			//jQuery( "#popup_bobytext1").html(body);
			//jQuery( "#popup_bobytext2").html(body2);
			
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
		
		//===========for pop-up==========
		//retrieve reviews button clicked
		var totaltoinsert = 0;
		$( ".retreviewsbtn" ).on("click",function(event) {
			event.preventDefault();
			//check to see if the text area wprevpro_cookie is on page, and has value.
			if($('#wprevpro_cookie').length){
				if(!$('#wprevpro_cookie').val().length){
					alert(adminjs_script_vars.msg1);
					return;
				}
			}
			
			//get revs
			getrevformtempid = $( this ).parent().attr( "templateid" );
			totaltoinsert = $( this ).closest('.locationrow').attr( "data-blocks" );
			var url = "#TB_inline?inlineId=retreivewspopupdiv";
			tb_show(adminjs_script_vars.Downloading_Reviews, url);
			$( "#TB_window" ).css({ "height":"auto !important" });
			$( "#TB_ajaxContent" ).css({ "max-height":"300px" });
			$( "#TB_ajaxContent" ).css({ "width":"auto" });
			$( "#TB_ajaxContent" ).css({ "height":"300px" });
			
			$( "#TB_ajaxContent" ).focus();
			$('#TB_closeWindowButton').blur();
			
			$('head').append('<style type="text/css">#TB_window {top:250px !important;margin-top: 50px !important;margin-left: -320px !important;width: 630px !important; height: 325px !important; }</style>');
			
			$( "#getrevsbtnpopup" ).attr("tabindex",-1).focus();
			$( "#TB_ajaxContent" ).focus();
			$('#TB_closeWindowButton').blur();
			//call ajax to scrape the reviews.
			$( ".ajaxmessagediv" ).html('');
			ajaxgetrevform(1,100);
		});

		
		//for actually downloading the revs, use ajax probably
		var howmanyloopstomake = 0;
		var nextpnum = 0;
		var totrevsreturned = 0;
		var totalrevsinserted = 0;
		var nextpageurl ="";

		function ajaxgetrevform(pnum,perp){
			var temptimer;
			var spinnerdiv = $( ".downloadrevsbtnspinner" );
			spinnerdiv.addClass('loadingspinner');
			//make ajax call here to pull reviews from server
			//pass funnel id
			// use funnel id to get funnel details from db
			//use funnel details, plus license info, to pull reviews from server and display results
			var senddata = {
				action: 'wprp_getapps_getrevs',	//required
				wpfb_nonce: adminjs_script_vars.wpfb_nonce,
				fid: getrevformtempid,
				pnum:pnum,
				perp:perp,
				totalrevsin:totalrevsinserted,
				npagerul:nextpageurl
				};
			//send to ajax to update db
			console.log(senddata);
			var jqxhr = jQuery.post(ajaxurl, senddata, function (response){
				spinnerdiv.removeClass('loadingspinner');
				console.log(response);
				//$( ".ajaxmessagediv" ).html(response);
				if (!$.trim(response)){
					//alert("Error returning reviews for this url, please contact support.");
					$( ".ajaxmessagediv" ).html(adminjs_script_vars.msg2+response);
				} else {
					try {
						var formobject = JSON.parse(response);
					} catch(e) {
						//alert(e); // error in the above string (in this case, yes)!
						$( ".ajaxmessagediv" ).html(adminjs_script_vars.msg2+response);
					}
					var msghtml='';
					if(typeof formobject =='object')
					{
					  // It is JSON, safe to continue here
						if(formobject.forceloop=='yes'){
								//this means that first was just to get url. tripadvisor_attraction msg25
								nextpageurl = formobject.nextpageurl;
								console.log('loop, nextpage:'+nextpageurl);
								$( ".ajaxmessagediv" ).html(adminjs_script_vars.msg25);
								temptimer = setTimeout(ajaxgetrevform(1,100), 2000);
								
						} else if(formobject.numreturned>0){
							totrevsreturned = totrevsreturned + formobject.numreturned;
							totalrevsinserted = totalrevsinserted + formobject.numinserted;
							//console.log(formobject.numinserted+'-'+totaltoinsert+'-'+totalrevsinserted);
							msghtml =  msghtml + "<br><b>"+String(formobject.numreturned) + "</b> "+adminjs_script_vars.msg3+" ";
							msghtml =  msghtml + "<br><b>"+String(totalrevsinserted) + "</b> "+adminjs_script_vars.msg4+". ";
							//if the numinserted is greater than 0 then we need to loop
							if(formobject.numinserted>0 && totaltoinsert > totalrevsinserted && formobject.stoploop!='stop'){
								console.log('loop');
								//loop here, need a break if we loop too many times
								nextpnum = pnum + 1;
								//set next page url in case we are using.
								nextpageurl = formobject.nextpageurl;
								temptimer = setTimeout(ajaxgetrevform(nextpnum,perp), 2000);
							} else {
								//console.log('finished');
								//must be finished
								if(totalrevsinserted<1){
									msghtml =  msghtml + "<br>"+adminjs_script_vars.Done+". ";
								} else {
									msghtml =  msghtml + "<br>"+adminjs_script_vars.msg5+" ";
								}
								updateavatars();
								totalrevsinserted = 0;
							}
							$( ".ajaxmessagediv" ).html(msghtml);
						} else if(formobject.numreturned<1 && totalrevsinserted>0){
							$( ".ajaxmessagediv" ).append("<br>"+adminjs_script_vars.Done+"! ");
							totalrevsinserted = 0;
							//console.log('finished2');
							updateavatars();
						} else if(formobject.ack!='success' && formobject.ack!=''){
							$( ".ajaxmessagediv" ).append("<br>"+formobject.ack+" ");
							if(formobject.msg && formobject.msg!=''){
								$( ".ajaxmessagediv" ).append("<br>"+formobject.msg+" ");
							}
							if(formobject.ackmsg && formobject.ackmsg!=''){
								$( ".ajaxmessagediv" ).append("<br>"+formobject.ackmsg+" ");
							}
						} else if(formobject.numreturned==0 && formobject.numinserted==0){
							$( ".ajaxmessagediv" ).append("<br>"+adminjs_script_vars.msg6+" ");
						} else {
							msghtml =  msghtml + " "+adminjs_script_vars.msg7+" ";
							console.log(response);
							$( ".ajaxmessagediv" ).append(msghtml);
							//$( ".ajaxmessagediv" ).append('<br>');
							//$( ".ajaxmessagediv" ).append(response);
						}
						

					}
					else
					{
						$( ".ajaxmessagediv" ).html(adminjs_script_vars.msg8+" " +response);
						console.log(response);
					}
					//testing
					//console.log('finished3');
					//updateavatars();
				}
			});
			jqxhr.fail(function() {
			  alert( adminjs_script_vars.msg9 );
			  $( ".ajaxmessagediv" ).append(adminjs_script_vars.msg9);
			  spinnerdiv.removeClass('loadingspinner');
			});
			
		}
		//=========================
		//update the cache avatars
		function updateavatars(){
			console.log('updating avatars');
				var senddata = {
					action: 'wpfb_update_avatars',	//required
					wpfb_nonce: adminjs_script_vars.wpfb_nonce,
					};
				jQuery.post(ajaxurl, senddata, function (response){console.log(response);});
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

		
		//hide or show edit template form ----------
		var checkedittemplate = getParameterByName('taction'); // "lorem"
		var currentrtype = getParameterByName('rtype');
		var totallocations = $('.locationrow').length;
		if(checkedittemplate=="edit"){
			jQuery("#wprevpro_new_template").show("slow");
		} else {
			jQuery("#wprevpro_new_template").hide();
		}
		
		//for hide show FB access code
		$( "#wprevpro_addfbcode" ).on("click",function() {
			jQuery("#fb_secret_code_div").toggle("slow");
		});
		
		//TripAdvisor
		$( "#wprevpro_addnewtemplate" ).on("click",function() {
			//if this is Google then count total locations first to limit them. appformstable 
			if(currentrtype=='Google' && totallocations>100){
				alert(adminjs_script_vars.msg23);
			} else if(currentrtype=='AngiesList' && totallocations>100){
				alert(adminjs_script_vars.msg23);
			} else if(currentrtype=='TripAdvisor' && totallocations>100){
				alert(adminjs_script_vars.msg23);
			} else if(currentrtype=='Yelp' && totallocations>100){
				alert(adminjs_script_vars.msg23);
			} else if(currentrtype=='Google-Places-API' &&  $( "#wprevpro_googleplacesapikey" ).val()==""){
				alert(adminjs_script_vars.msg24);
			} else {
				jQuery("#wprevpro_new_template").show("slow");
			}
		});	
		$( "#wprevpro_addnewtemplate_cancel" ).on("click",function() {
		  jQuery("#wprevpro_new_template").hide("slow");
		  //reload page without taction and tid
		  setTimeout(function(){ 
			window.location.href = "?page=wp_pro-get_apps&rtype="+currentrtype; 
		  }, 500);
		  
		});	
		

		 var placeid = '';
		//function wpfbr_testapikey(pagename) {
		$("#savetest").on("click",function(event){
			
			//hide button
			$( this ).hide();
			//add class wprevloader to parent div
			$( '#buttonloader' ).show();
			$( '#googletestresults' ).hide();
			$( '#googletestresultstext2' ).html('');
			$( '#googletestresultserrortext2' ).html('');
			$( '#googletestresults2' ).hide();

			placeid = $("#wprevpro_url").val();
			if(placeid==''){
				//alert("Please enter your Place ID.");
				//return false;
			}
			var data = {
			action	:	'wprevpro_crawl_placeid',
			gplaceid	:	placeid,
			_ajax_nonce		: adminjs_script_vars.wpfb_nonce,
				};
			var myajax = jQuery.post(ajaxurl, data, function(response) {
					console.log(response);
					    try {
							const objresponse = JSON.parse(response);
							$( '#divgoogletestresults' ).show();
							console.log(objresponse);
							if(objresponse.ack!='success'){
								//show error
								$( '#divdownloadreviews' ).hide();
								$( '#googletestresults' ).hide();
								$( '#googletestresultserror' ).show();
								$( '#googletestresultserrortext' ).html('<p>'+objresponse.ackmsg+'</p>');
							} else {
								$( '#divdownloadreviews' ).show();
								$( '#googletestresults' ).show();
								$( '#googletestresultserror' ).hide();
								if(objresponse.img!=''){
								$( '#businessimg' ).attr("src", objresponse.img);
								}
								$( '#businessname' ).html(objresponse.businessname);
								$( '#website' ).html(objresponse.website);
								$( '#googleurl' ).html(objresponse.googleurl);
								$( '#googleurl' ).attr("href", objresponse.googleurl);
								$( '#wprevpro_google_page_id' ).val(objresponse.foundplaceid);
								$( '#reviewtext' ).html('Rated <b>'+objresponse.rating+'</b> out of <b>'+objresponse.totalreviews+'</b>');
								$( '#downloadreviews' ).show();
							}
						} catch (e) {
							$( '#googletestresults' ).hide();
							$( '#googletestresultserror' ).show();
							$( '#googletestresultserror' ).html('<p>Error crawling Google. Please contact support.</p>');
							return false;
						}
					//show button
					$("#savetest").show();
					//add class wprevloader to parent div
					$( '#buttonloader' ).hide();
					
			});
			jQuery(window).unload( function() { myajax.abort(); } );

		});
		
		//======for Google Places API===========
		//testing google places api key
		$( "#wpfbr_testgoogleplaceskey" ).on("click",function() {
			//launch pop-up for progress messages
			openpopup_tb("Testing API Key Against Google Server", "","");
			var google_api_key = $( "#wprevpro_googleplacesapikey" ).val();
			
			var data = {
			action		: 'wpfbr_testing_api',
			apikey:google_api_key,
			_ajax_nonce		: adminjs_script_vars.wpfb_nonce,
				};

			var myajax = jQuery.post(ajaxurl, data, function(response) {
				console.log(response);
				if( response != '-1' && ! response != '0' ){
					jQuery( "#popupmsg_bobytext2").append(response);
				} else {
					jQuery( "#popupmsg_bobytext2").html(response);
				}
			});
			jQuery(window).on( "unload", function() { myajax.abort(); } );
		});
		
		//for searching for location.
		$( "#wprevpro_template_title_gpa" ).on("change",function() {
			 var val = $(this).val().trim();
			 val = val.replace(/\s+/g, '');
			 if(val.length % 3 == 0) { //for checking 3 characters
				   //your logic here
				   
			 }
		});
		var google_api_key = jQuery("#wprevpro_googleplacesapikey" ).val();
		if(google_api_key!=''){
			// Create the script tag, set the appropriate attributes
			//https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=
			var script = document.createElement('script');
			script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key='+google_api_key+'&callback=initMap';
			script.async = true;
			// Attach your callback function to the `window` object
			window.initMap = function() {
			  // JS API is loaded and available
			  wpfbr_initialize_autocomplete();
			  
			};
			// Append the 'script' element to 'head'
			document.head.appendChild(script);
			

			//$(document).ajaxSuccess(function (e, xhr, settings) {
			//	wpfbr_initialize_autocomplete();
			//});
			//$(window).on('load',function () {
			//	wpfbr_initialize_autocomplete();
			//});
		}
		window.gm_authFailure = function() {
			$('#titleerrmsg').html('Error searching for location. Test your API Key above and click Save.');
		};
		
		/**
		 * Initialize Google Places Autocomplete Field
		 */
		function wpfbr_initialize_autocomplete() {
			var input = $('#wprevpro_template_title_gpa');
			var types = "establishment";

			input.each(function (index, value) {
				var autocomplete = new google.maps.places.Autocomplete(input[index]);

				//Handle type select field
				$(types).on('change', function () {
					//Set type
					var type = $(this).val();
					autocomplete.setTypes([type]);
					$(input[index]).val('');
					$("#wprevpro_url").val('');
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
				$('#wprevpro_google_location_name').val(place.name);
				$('#wprevpro_url').val(place.place_id);
				//$('#wpfbr_google_location_set_place_id_address').val(place.formatted_address);

				//hide form
				jQuery('.wpfbr_loctype').hide('slow');
				jQuery('.wpfbr_locsearch').hide('slow');
				
			});
		}
		
		
		//launch pop-up windows code--------
		function openpopup_tb(title, body, body2){
			//set text
			jQuery( "#popupmsg_titletext").html(title);
			jQuery( "#popupmsg_bobytext1").html(body);
			jQuery( "#popupmsg_bobytext2").html(body2);
		var url = "#TB_inline?width=600&height=600&inlineId=popupmsgbody";
			tb_show(title, url);
			$( "#TB_window" ).css({ "height":"auto !important" });
			$( "#TB_ajaxContent" ).css({ "max-height":"300px" });
			$( "#TB_ajaxContent" ).css({ "width":"auto" });
			$( "#TB_ajaxContent" ).css({ "height":"300px" });
			
			$( "#TB_ajaxContent" ).focus();
			$('#TB_closeWindowButton').blur();
			
			$('head').append('<style type="text/css">#TB_window {top:250px !important;margin-top: 50px !important;margin-left: -320px !important;width: 630px !important; height: 325px !important; }</style>');

		}

		
		//======for facebook========
		$( ".getfbreviews" ).on("click",function() {
			var pageid = $(this).attr( "data-pageid" );
			var pagename = $(this).attr( "data-pagename" );
			var getrevformtempid = $( this ).parent().attr( "templateid" );
			jQuery( ".downloadrevsbtnspinner").show();
			console.log(pageid);
			console.log(pagename);
			console.log(getrevformtempid);
			getfbreviewsfunction(pageid,pagename,getrevformtempid)
		});
		
		function getfbreviewsfunction(pageid,pagename,getrevformtempid) {
			event.preventDefault();
			var url = "#TB_inline?inlineId=retreivewspopupdiv";
			tb_show(adminjs_script_vars.Downloading_Reviews, url);
			$( "#TB_window" ).css({ "height":"auto !important" });
			$( "#TB_ajaxContent" ).css({ "max-height":"300px" });
			$( "#TB_ajaxContent" ).css({ "width":"auto" });
			$( "#TB_ajaxContent" ).css({ "height":"300px" });
			
			$( "#TB_ajaxContent" ).focus();
			$('#TB_closeWindowButton').blur();
			
			$('head').append('<style type="text/css">#TB_window {top:250px !important;margin-top: 50px !important;margin-left: -320px !important;width: 630px !important; height: 325px !important; }</style>');
			
			
			$( "#getrevsbtnpopup" ).attr("tabindex",-1).focus();
			//call ajax to scrape the reviews.
			$( ".ajaxmessagediv" ).html('');

			var reviewarray = new Array();
			var totalinserted = 0;
			var numtodownload = 5;
			var msg = "";
			for ( var i = 0; i < numtodownload; i++ ) {
				reviewarray[i] = []; 
			}
			var aftercode = "";

			getandsavefbreviews(pageid,pagename,reviewarray,totalinserted,numtodownload,aftercode,getrevformtempid);
		}
		
		//for downloading FB reviews.
		function getandsavefbreviews(pageid,pagename,reviewarray,totalinserted,numtodownload,aftercode,getrevformtempid){	
			//start a loop here that loops on success and stops on error or no more entries, try every 25, update progress bar
			var pagingdata = "";
			var accesscode = jQuery("#fb_secret_code" ).val();
			var i,senddata,msg;
			
			//make ajax call to server using secrete code, fbuserid, and pageid
			//make a jsonp call here to find pages that have been checked on fbapp.ljapps.com
			jQuery.ajax({
				url: "https://fbapp.ljapps.com/ajaxgetpagerevs-click.php",
				jsonp: "callback",
				dataType: "jsonp",
				data: {
					q: "getrevs",
					acode: accesscode,
					pid: pageid,
					afterc:aftercode,
					format: "json"
				},
			 
				// Work with the response
				success: function( response ) {
					console.log( response ); // server response
					if(response.ack!="success" && typeof(response.ack) != "undefined"){
						msg =	"</br></br>"+response.ack;
						jQuery( ".ajaxmessagediv").append(msg);
						jQuery( ".downloadrevsbtnspinner").hide();
					} else {
						pagingdata = response.paging;
						if(response.data.length > 0){
							var fbreviewarray = response.data;
							for (i = 0; i < fbreviewarray.length; i++) {
								if(fbreviewarray[i].reviewer){
								reviewarray[i] = {};
								reviewarray[i]['pageid']=pageid;
								reviewarray[i]['pagename']=pagename;
								reviewarray[i]['overall_star_rating']=fbreviewarray[i].overall_star_rating;
								reviewarray[i]['rating_count']=fbreviewarray[i].rating_count;
								reviewarray[i]['created_time']=fbreviewarray[i].created_time;
								reviewarray[i]['reviewer_name']=fbreviewarray[i].reviewer.name;
								reviewarray[i]['reviewer_id']=fbreviewarray[i].reviewer.id;
								reviewarray[i]['rating']=fbreviewarray[i].rating;
								if(fbreviewarray[i].recommendation_type){
									reviewarray[i]['recommendation_type']=fbreviewarray[i].recommendation_type;
								} else {
									reviewarray[i]['recommendation_type']="";
								}
								if(fbreviewarray[i].review_text){
									reviewarray[i]['review_text']=fbreviewarray[i].review_text;
								} else {
									reviewarray[i]['review_text']="";
								}
								if(fbreviewarray[i].reviewer.imgurl){
									reviewarray[i]['reviewer_imgurl']=fbreviewarray[i].reviewer.imgurl;
								} else {
									reviewarray[i]['reviewer_imgurl']="";
								}
								if(fbreviewarray[i].open_graph_story && fbreviewarray[i].open_graph_story.id){
									reviewarray[i]['uniqueid']=fbreviewarray[i].open_graph_story.id;
								} else {
									reviewarray[i]['uniqueid']="";
								}
								reviewarray[i]['type']="Facebook";
								}
							}
					// take response and format array based on what we need only
					//send array via ajax to php function to insert to db.
					// use nonce to make sure this is not hijacked
							//post to server
							var stringifyreviews = JSON.stringify(reviewarray);
							senddata = {
								action: 'wpfb_get_results',	//required
								wpfb_nonce: adminjs_script_vars.wpfb_nonce,
								postreviewarray: reviewarray,
								gafid: getrevformtempid
								};
							//console.log(stringifyreviews);

							jQuery.post(ajaxurl, senddata, function (response){
								console.log(response);
								var res = response.split("-");
								var thisinserted = Number(res[2]);
								totalinserted = Number(totalinserted) + Number(res[2]);
								if(totalinserted>0){
									jQuery( ".ajaxmessagediv").html(adminjs_script_vars.fbmsg+" " + totalinserted);
								}
								if(thisinserted==0 && totalinserted<1){
									jQuery( ".ajaxmessagediv").html(adminjs_script_vars.fbmsg2);
									jQuery( ".downloadrevsbtnspinner").hide();
								} else if(thisinserted==0 && totalinserted>0){
									jQuery( ".ajaxmessagediv").append(adminjs_script_vars.fbmsg5);
									jQuery( ".downloadrevsbtnspinner").hide();
									updateavatars();
								} else {
									if(pagingdata){
										if(!pagingdata.next){
											jQuery( ".ajaxmessagediv").append("</br></br>"+adminjs_script_vars.fbmsg1);
											jQuery( ".downloadrevsbtnspinner").hide();
											//finished call ajax for downloading avatars
											updateavatars();
										}
										
										//loop here if paging data next is available
										if(pagingdata.next && Number(res[3])!=1 ){
											aftercode = pagingdata.cursors.after;
											jQuery( ".downloadrevsbtnspinner").hide();
											getandsavefbreviews(pageid,pagename,reviewarray,totalinserted,numtodownload,aftercode,getrevformtempid);
										} else {
											jQuery( ".ajaxmessagediv").append(adminjs_script_vars.fbmsg5);
											jQuery( ".downloadrevsbtnspinner").hide();
										}
									} else {
										jQuery( ".ajaxmessagediv").append("</br></br>"+adminjs_script_vars.fbmsg1);
										jQuery( ".downloadrevsbtnspinner").hide();
										//finished call ajax for downloading avatars
										updateavatars();
									}
								}
								
							});

						} else {
							msg = "";
							console.log(pagingdata);
							if(!pagingdata){
								msg = " Oops, no reviews returned from Facebook for that page. If the page does in fact have reviews on Facebook, please try again or contact us for help.";
							} else {
								if(!pagingdata.next){
									msg = "</br></br>"+msg+adminjs_script_vars.fbmsg1;
								} else {
									aftercode = pagingdata.cursors.after;
									getandsavefbreviews(pageid,pagename,reviewarray,totalinserted,numtodownload,aftercode,getrevformtempid);
								}
							}
							jQuery( ".ajaxmessagediv").append(msg);
							updateavatars();
							jQuery( ".downloadrevsbtnspinner").hide();
						}
					}
				}
			});
		}


		function updateavatars(){
			var senddata = {
				action: 'wpfb_update_avatars',	//required
				wpfb_nonce: adminjs_script_vars.wpfb_nonce,
				};
			jQuery.post(ajaxurl, senddata, function (response){console.log(response);});
		}		
		
		//Facebook list pages
		//ran on page load to list pages. Only should do this if we are on Facebook type.
		let searchParams = new URLSearchParams(window.location.search)
		let param = searchParams.get('rtype')
		if(param=='Facebook'){
			var tempcodeid = jQuery("#fb_secret_code" ).val();
		   //hide stuff if app id is not set
			if(tempcodeid==''){
				//jQuery("#wprevpro_addnewtemplate").hide();
				jQuery("#fb_secret_code_div").show();
				jQuery("#pageslisterror").append(adminjs_script_vars.fbmsg3);
			} else {
				//jQuery("#wprevpro_addnewtemplate").show();
				jQuery("#fb_secret_code_div").hide();
				listpages(tempcodeid);
			}
		}
		//--------------------------
		function listpages(accesscode){
			
			//make a jsonp call here to find pages that have been checked on fbapp.ljapps.com
			$.ajax({
				url: "https://fbapp.ljapps.com/ajaxlistpages.php",
				jsonp: "callback",
				dataType: "jsonp",
				data: {
					q: "listpages",
					acode: accesscode,
					format: "json"
				},
			 
				// Work with the response
				success: function( response ) {
					console.log(response);
					if(response.ack!="success"){
						//alert(response.ack);
						jQuery("#pageslisterror").html(response.ack);
						jQuery("#pageslisterror").append(adminjs_script_vars.fbmsg3);
						return false;
					}
					//loop through page ids and save and display them in the table.
					if(response.data[0].fbpageid){
					console.log(response.data);
						var fbpagearray = response.data;
						var tablerows = "";
						var selectrows = "";
						var i = 0;
						var temppagename = "";
						var tempcheckedcron = '';
						for (i = 0; i < fbpagearray.length; i++) { 
						//build select options for wprevpro_fb_page here.
							temppagename = fbpagearray[i].fbpagename.replace(/'/g, "%27");
							temppagename = temppagename.replace(/"/g, "");
						
							selectrows = selectrows + '<option value="'+ fbpagearray[i].fbpageid +'_'+ fbpagearray[i].fbpagename +'">' + fbpagearray[i].fbpagename + '</option>';
							
						}
						//jQuery("#page_list" ).append( tablerows );
						console.log(selectrows);
						jQuery("#wprevpro_fb_page" ).append( selectrows );
	
					} else {
						alert(adminjs_script_vars.Oops);
					}
				}
			});
			//call the graph api to get a page access token and put it in the text field
		}
		
		
		//-------------------------------
		//form validation 
		$("#wprevpro_submittemplatebtn").on("click",function(){
			if(jQuery( "#wprevpro_template_title").val()=="" || jQuery( "#wprevpro_template_title_gpa").val()==""){
				alert(adminjs_script_vars.msg20);
				//$( "#wprevpro_template_title" ).focus();
				return false;
			}
			//loop through title to see if it's been used yet. only if not editing
			var uniquename=true;
			if($("#edittid").val()==''){
				$( ".titlespan" ).each(function() {
				  var temptitle = $( this ).text();
				  if(jQuery( "#wprevpro_template_title").val()==temptitle){
					  uniquename=false;
				  }
				});
				if(uniquename==false){
					alert(adminjs_script_vars.msg20);
					return false;
				}
			}
			
			if($("#wprevpro_site_type").val()!='google' && $("#wprevpro_site_type").val()!='Google' && $("#wprevpro_site_type").val()!='Facebook'){
				if(jQuery( "#wprevpro_url").val()==""){
					alert(adminjs_script_vars.msg21);
					//$( "#wprevpro_url" ).focus();
					return false;
				}
			} else if($("#wprevpro_site_type").val()=='google' && $("#wprevpro_site_type").val()=='Google') {
				//make sure they tested and got the place id.
				//if #googletestresults is shown then we assume it is set correctly.
				if($("#googletestresults").is(":visible")){
					
				} else {
					alert(adminjs_script_vars.msg22);
					return false;
				}
				if(currentrtype=='Google' && totallocations>300){
					alert(adminjs_script_vars.msg23);
					return false;
				}
				
			} else if($("#wprevpro_site_type").val()=='Facebook') {
				if(jQuery( "#wprevpro_fb_page").val()==""){
					alert(adminjs_script_vars.fbmsg4);
					return false;
				}
			}
			
			return true;
		});
		
		function ValidURL(str) {
            var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
            '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.?)+[a-z]{2,}|'+ // domain name
            '((\\d{1,3}\\.){3}\\d{1,3}))'+ // ip (v4) address
            '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ //port
            '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
            '(\\#[-a-z\\d_]*)?$','i');
		  if(!pattern.test(str)) {
			return false;
		  } else {
			return true;
		  }
		}
		
		function timeConverter(UNIX_timestamp){
		  var a = new Date(UNIX_timestamp * 1000);
		  var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
		  var year = a.getFullYear();
		  var month = months[a.getMonth()];
		  var date = a.getDate();
		  var hour = a.getHours();
		  var min = a.getMinutes();
		  //var sec = a.getSeconds();
		  var time = date + ' ' + month + ' ' + year + ' ' + hour + ':' + min ;
		  return time;
		}
		
	});

})( jQuery );

