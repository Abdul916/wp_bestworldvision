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

		$('head').append('<style type="text/css">#TB_window {top:250px !important;margin-top: 50px !important;margin-left: -320px !important;width: 630px !important; height: 325px !important; }</style>');
		 
		//role select box.
		$(".roleselect").select2({
			width: '35%',
			placeholder: adminjs_script_vars.Role_Filter
		});
		
		
		//page select box
		$("#wprevpro_source_page").select2({
			width: '50%',
			placeholder: adminjs_script_vars.Location_Filter
		});
		//type select box
		$("#wprevpro_site_type").select2({
			width: '50%',
			placeholder: adminjs_script_vars.Type_Filter
		});

		 
		 
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
		if(checkedittemplate=="edit"){
			jQuery("#wprevpro_new_template").show("slow");
		} else {
			jQuery("#wprevpro_new_template").hide();
		}
		
		$( "#wprevpro_addnewtemplate" ).on( "click",function() {
		  jQuery("#wprevpro_new_template").show("slow");
		});	
		$( "#wprevpro_addnewtemplate_cancel" ).on("click",function() {
		  jQuery("#wprevpro_new_template").hide("slow");
		  //reload page without taction and tid
		  setTimeout(function(){ 
			window.location.href = "?page=wp_pro-notifications"; 
		  }, 500);
		  
		});	
		
		$( "#wprevpro_googleprodratingxml" ).on( "change",function() {
		  if($( "#wprevpro_googleprodratingxml" ).val()=='yes'){
			  $( ".gprfields" ).show('slow');
		  } else {
			  $( ".gprfields" ).hide('slow');
		  }
		});	
		
		
		$( "#autogetrevsplusdownload_btn" ).on("click",function() {
			runautosourcesetup(1);
		});
		
		//for setting up auto download forms based on custom field value.
		$( "#autogetrevs_btn" ).on("click",function() {
			runautosourcesetup(0);
		});
		
		function runautosourcesetup(plusdownload){
			var type = $( "#wprevpro_autogetrevs_type" ).val();
			var posttype = $( "#wprevpro_autogetrevs_posttype" ).val();
			var cfn = $( "#wprevpro_autogetrevs_cfn" ).val();
			var hourly = $( "#wprevpro_autogetrevs_hourly" ).val();
			var langcode = $( "#wprevpro_autogetrevs_langcode" ).val();
			var which = $( "#wprevpro_autogetrevs_which" ).val();
			var cron = $( "#wprevpro_autogetrevs_cron" ).val();
			
			$('#autogetrevs_progress_div').html('');
			$('#autogetrevs_div_error').html('');
			$('.loadingspinner').show();
			
			//open pop-up
			var url = "#TB_inline?width=auto&height=auto&inlineId=tb_content_popup2";
			tb_show("Running Auto Get Review Source Creator...", url);
			$( "#TB_window" ).css({ "width":"600px","margin-left": "-250px" });
			$( "#TB_ajaxContent" ).css({ "width":"auto","height":"auto" });
			$( "#TB_window" ).focus();
			$( "#TB_ajaxContent" ).focus();
			$('#TB_closeWindowButton').blur();
			
			//ajax call to actually do the work.
			var data = {
				action		: 'wppro_run_autogetrevs',
				type	:	type,
				posttype	:	posttype,
				cfn	:	cfn,
				hourly : hourly,
				langcode : langcode,
				which : which,
				cron : cron,
				plusdownload : plusdownload,
				_ajax_nonce		: adminjs_script_vars.wpfb_nonce,
			};
			console.log(data);

			var jqxhr = jQuery.post(ajaxurl, data, function(response) {

				console.log(response);

				var formobject = JSON.parse(response);

				if(typeof formobject =='object'){
				  // It is JSON, safe to continue here
				  console.log(formobject);
				  var resulthtml = "Number Returned: " + formobject.totalpostsfound+"<br><br>";
				  $.each(formobject, function(key,valueObj){
					  if(valueObj.ID){
						//alert(key + "/" + JSON.stringify(valueObj));
						resulthtml = resulthtml  + "Post: " +valueObj.ID + " - " + valueObj.post_title+"<br>";
						resulthtml = resulthtml  + "Field Val: " +valueObj.customfield + "<br>";
						resulthtml = resulthtml  + "<b>New Source Result: " +valueObj.ack + " - "+ valueObj.ackmsg+"</b><br>";
						if(valueObj.dl.ack){
							if(valueObj.dl.ack==='error'){
							resulthtml = resulthtml  + "<b>Review Download Result: Error - " + valueObj.dl.ackmsg+"</b><br>";
							} else {
							resulthtml = resulthtml  + "<b>Review Download Result: Success - " + valueObj.dl.numinserted+" new reviews added to review list.</b><br>";
							}								
						}
						resulthtml = resulthtml  + "<br>";
					  }
					});
					resulthtml = resulthtml  + "Total Reviews Inserted to Review List: " + formobject.totalreviewsinserted+"<br>";

				  $( "#autogetrevs_progress_div" ).html("<br>"+resulthtml);
				  $('.loadingspinner').hide();

				} else {
					$('.loadingspinner').hide();
					$( "#autogetrevs_div_error" ).html("Error: ".response);
				}
				
			});
			
			
		}
		
		

		var timestoloop = 0;
		var numdetectedsuccess = 0;
		var loopnum = 0;
		var timesdied = 0;
		var sentlastrevid = 0;
		
		var totaltranslatorloops = 0;
		var totaltranslatedrevs = 0;
		
		//for translating language of review
		$( "#translaterevs_btn" ).on("click",function() {
			var tempapi = $( "#wprevpro_lang_api_key" ).val();
			var targetlanguages = $( "#wprevpro_lang_targetlang" ).val();
			$('#lang_progress_div').html('');
			$('#lang_progress_div2').html('');
			$('#lang_progress_div3').html('');
			$('#lang_progress_div_error').html('');
			$('.loadingspinner').show();
			//open pop-up
			var url = "#TB_inline?width=auto&height=auto&inlineId=tb_content_popup";
			tb_show("Running Review Language Translator...", url);
			$( "#TB_window" ).css({ "width":"600px","margin-left": "-250px" });
			$( "#TB_ajaxContent" ).css({ "width":"auto","height":"auto" });
			$( "#TB_window" ).focus();
						$( "#TB_ajaxContent" ).focus();
			$('#TB_closeWindowButton').blur();
			
			//reset
			timestoloop = 0;
			numdetectedsuccess = 0;
			loopnum = 0;
			timesdied = 0;
			sentlastrevid = 0;
			runlangtranslator(tempapi,targetlanguages,sentlastrevid);
		});
		//for actually translating
		function runlangtranslator(tempapi,targetlanguages,sentlastrevid){
			//make an ajax call to run detector
			var data = {
				action		: 'wppro_run_language_translate',
				apikey	:	tempapi,
				targlang	:	targetlanguages,
				jslastrevid : sentlastrevid,
				_ajax_nonce		: adminjs_script_vars.wpfb_nonce,
			};
			console.log(data);

			var jqxhr = jQuery.post(ajaxurl, data, function(response) {
				totaltranslatorloops = totaltranslatorloops + 1;
				//console.log(response);
				if (!$.trim(response)){
					if(timesdied<3){
						timesdied = timesdied +1;
						setTimeout(runlangtranslator(tempapi,targetlanguages,sentlastrevid), 2000);
					} else {
						alert(adminjs_script_vars.msg1);
						timesdied = 0;
					}
				} else {
					timesdied = 0;
					var formobject = JSON.parse(response);
					var temptext = '';
					var initialnumtotrans = '';
					var detectobject ='';
					if(typeof formobject =='object'){
					  // It is JSON, safe to continue here
					  console.log(formobject);
					   $('.loadingspinner').hide();
					   $('#lang_progress_div').html("Searching reviews to translate... "+totaltranslatorloops+"</b>");
					  if(Number(formobject.totaltranslateded)>0){
						  totaltranslatedrevs = totaltranslatedrevs + formobject.totaltranslateded;
						$('#lang_progress_div2').html("<br>Total Reviews Translated... <b>"+totaltranslatedrevs+"</b>")
					  }
					  //if we have a last rev id then we loop again if success last time.
					  if(Number(formobject.temp_id)>0 && formobject.ack!='error' && formobject.loop!='stop'){
						  runlangtranslator(tempapi,targetlanguages,Number(formobject.temp_id));
					  } else {
						  $('#lang_progress_div3').append("<br><b>Done.</b>")
					  }
					  //check for a translation error.
					  if(formobject.ack=='error'){
						  $('#lang_progress_div3').append("<br><b>"+formobject.ackmsg+"</b>")
					  }

					} else {
						$('.loadingspinner').hide();
						$( "#lang_progress_div_error" ).html("Error: ".response);
					}
				}
			});
			
		}
		
		
		//for adding language code to reviews
		$( "#lang_detect_btn" ).on("click",function() {
			var tempapi = $(this).prev( "#api_key" ).val();
			console.log(tempapi);
			$('#lang_progress_div').html('');
			$('#lang_progress_div2').html('');
			$('#lang_progress_div3').html('');
			$('#lang_progress_div_error').html('');
			$('.loadingspinner').show();
			//open pop-up
			var url = "#TB_inline?width=auto&height=auto&inlineId=tb_content_popup";
			tb_show("Running Language Detector...", url);
			$( "#TB_window" ).css({ "width":"600px","margin-left": "-250px" });
			$( "#TB_ajaxContent" ).css({ "width":"auto","height":"auto" });
			$( "#TB_window" ).focus();
			
			$( "#TB_ajaxContent" ).focus();
			$('#TB_closeWindowButton').blur();
			
			//reset
			timestoloop = 0;
			numdetectedsuccess = 0;
			loopnum = 0;
			timesdied = 0;
			sentlastrevid = 0;
			
			runlangdetector(tempapi,'0');

		});
		
		function runlangdetector(tempapi,pnum){
			//make an ajax call to run detector
			var data = {
				action		: 'wppro_run_language_detect',
				apikey	:	tempapi,
				dbpage: pnum,
				jslastrevid : sentlastrevid,
				_ajax_nonce		: adminjs_script_vars.wpfb_nonce,
			};
			//console.log(data);

			var jqxhr = jQuery.post(ajaxurl, data, function(response) {
				//console.log(response);
				if (!$.trim(response)){
					if(timesdied<3){
						timesdied = timesdied +1;
						setTimeout(runlangdetector(tempapi,pnum), 2000);
					} else {
						alert(adminjs_script_vars.msg1);
						timesdied = 0;
					}
				} else {
					timesdied = 0;
					var formobject = JSON.parse(response);
					var temptext = '';
					var initialnumtotrans = '';
					var detectobject ='';
					if(typeof formobject =='object'){
					  // It is JSON, safe to continue here
					  console.log(formobject);
					  //send back next revidtofind
					  sentlastrevid = formobject.lastrevid;
					  
					  if(Number(formobject.totalcount)>0){
						  if(loopnum==0){
						  initialnumtotrans = Number(formobject.totalcount);
						  $('#lang_progress_div').append("<br>"+adminjs_script_vars.msg2+" <b>"+initialnumtotrans+"</b> "+adminjs_script_vars.msg3+".");
						  }
						  //$('#lang_progress_div').append("<br>Results....");
						  //loop results and show any errors
						  detectobject = formobject.detect;
						  $.each( detectobject, function( key, value ) {
							  if(value.decoderresult){
								  if(value.decoderresult.code && value.decoderresult.code==200){
									numdetectedsuccess = numdetectedsuccess + 1;
								  } else {
									  
									  //if(value.decoderresult.error.message){
									//	$('#lang_progress_div_error').append("<br><b>"+adminjs_script_vars.Error+": "+value.decoderresult.error.message+"</b>");
									 // }							
									$('#lang_progress_div_error').append("<br>"+adminjs_script_vars.msg4+" <i>"+value.strdetect+"...</i>"); 
								  }
							  }
							});
						  //update on progress
						  $('#lang_progress_div2').html("<br>"+adminjs_script_vars.Updated+" "+numdetectedsuccess+" "+adminjs_script_vars.reviews+".");
						  						  
						  //if the initialnumtotrans is greater than 10 we need to loop. Find total number of times to loop as global.
						  if(initialnumtotrans>1 && loopnum==0){
							  //we need to loop
							  timestoloop = initialnumtotrans/1;
							  timestoloop =Math.ceil(timestoloop);
							  console.log('loop:'+timestoloop);
						  }
						  //loop here if timestoloop is greater than 0
						  if(timestoloop>=loopnum && timestoloop > 0){
							  loopnum = loopnum + 1;
							  //$('#lang_progress_div2').append(" - "+adminjs_script_vars.msg5);
							  setTimeout(runlangdetector(tempapi,loopnum), 1000);
						  } else {
							  $('.loadingspinner').hide();
							  $('#lang_progress_div2').append("<br>"+adminjs_script_vars.msg6);
						  }
						  

					  } else {
						  $('.loadingspinner').hide();
						  $('#lang_progress_div3').append("<br>"+adminjs_script_vars.msg7);

					  }
					} else {
						$('.loadingspinner').hide();
						$( "#lang_progress_div3" ).html(adminjs_script_vars.msg8+" " +response);
					}
				}
			});
			
		}
		
		

		
	 });

})( jQuery );
