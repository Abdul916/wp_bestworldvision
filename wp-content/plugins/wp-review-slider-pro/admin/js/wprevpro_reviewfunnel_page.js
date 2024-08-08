
		
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

	$('head').append('<style type="text/css">#TB_window {top:150px !important;margin-top: 50px !important;margin-left: -350px !important;width: 700px !important; height: auto !important; }</style>');
		
		var getrevfunneltempid='';
		//check quota.
		var reviewcredits = parseInt($( "#reviewcredits" ).text());
		
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
		var gerrevtype = getUrlParameter('rt');
		//doing some stuff if this is set
		if(gerrevtype){
			$( "#wprevpro_site_type" ).val(gerrevtype);
			setTimeout(function() {
				//$(".choosesitetr").hide();
				$("#wprevpro_addnewtemplate").trigger("click");
			}, 500);
		}
		//now check if we are highlighting a previous funnel
		var gervfid = getUrlParameter('vfid');
		if(gervfid){
			var numrows = $( ".locationrow" ).length;
			if(numrows!=1){
			$( "#"+gervfid ).css('background-color', '#ffff6f');
			}
		}

		//help button clicked
		$( "#wprevpro_helpicon_posts" ).on("click",function() {
		  openpopup(adminjs_script_vars.popuptitle, '<p>'+adminjs_script_vars.popupmsg+'</p>', "");
		});
		
		
		//account info pop-up
		$( "#accountinfospan" ).on("click",function(event) {
			event.preventDefault();
			var url = "#TB_inline?inlineId=moreinfoaccountpopup";
			tb_show("Account Info", url);
			$( "#TB_window" ).css({ "height":"auto !important","width":"700px" });
			$( "#TB_ajaxContent" ).css({ "max-height":"600px" });
			$( "#TB_ajaxContent" ).css({ "width":"auto" });
			$( "#TB_ajaxContent" ).css({ "height":"auto" });
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
		
		//hide show based on type
		$( "#wprevpro_site_type" ).on("change",function() {
				hideshowrows();
		});
		
		//hide show google based on selection
		$( "#wprevpro_placeidorterms" ).on("change",function() {
				if($("#wprevpro_placeidorterms").val()=="terms"){
					$( ".gplaceid" ).hide();
					$( ".gsearch" ).show();
				} else {
					$( ".gsearch" ).hide();
					$( ".gplaceid" ).show();
				}
		});
		
		//hide show rows based on type here
		function hideshowrows(){
			
			if($( "#wprevpro_site_type" ).val()=="google" || $( "#wprevpro_site_type" ).val()=="Google"){
				//remove required attributes
				$( "#wprevpro_url" ).val('');
				$( "#wprevpro_url" ).removeAttr('required');
				$( "#wprevpro_from_date" ).removeAttr('required');
				$( ".notforgoogle" ).hide();
				$( ".forgoogle" ).show();
				$( ".fromdaterow" ).hide();
				$( ".facebooknumrow" ).hide();
				if($("#wprevpro_placeidorterms").val()=="terms"){
					$( ".gplaceid" ).hide();
					$( ".gsearch" ).show();
				} else {
					$( ".gsearch" ).hide();
					$( ".gplaceid" ).show();
				}
				//call ajax to get list of locations for dataforseo
				ajaxgetdataforseogooglelocations();
				ajaxgetdataforseogooglelangs();
				
			} else {
				$( "#wprevpro_query" ).val('');
				$( "#wprevpro_url" ).attr('required');
				$( "#wprevpro_from_date" ).attr('required');
				$( ".forgoogle" ).hide();
				$( ".notforgoogle" ).show();
				$( ".fromdaterow" ).show();
				$( ".facebooknumrow" ).hide();
			}
			//if this is Facebook then we hide the from_date and max num options. Facebook can either get 4 or all reviews.
			if($( "#wprevpro_site_type" ).val()=="facebook" || $( "#wprevpro_site_type" ).val()=="Facebook"){
				$( ".maxnumrow" ).hide();
				$( ".fromdaterow" ).hide();
				$( "#wprevpro_from_date" ).removeAttr('required');
				$( ".facebooknumrow" ).show();
			}
			
			//special notes for the different types
			$( "#urlnote" ).html("");
			var sitetype = $( "#wprevpro_site_type" ).val();
			var tempstr = "note_"+sitetype.toLowerCase();
			var tempstrval = adminjs_script_vars[tempstr];
			if( tempstrval ) {
				$( "#urlnote" ).html(tempstrval);
			}
			
			//special note for sitetype
			$( "#sitetypenote" ).html("");
			var sitetype = $( "#wprevpro_site_type" ).val();
			var tempstr1 = "sitetypenote_"+sitetype.toLowerCase();
			var tempstrval1 = adminjs_script_vars[tempstr1];
			if( tempstrval1 ) {
				$( "#sitetypenote" ).html(tempstrval1);
			}
		}
		
		
		//get list of locations for Google - dataforseo
		function ajaxgetdataforseogooglelocations(){
			//console.log("get locations");
			var senddata = {
				action: 'wprp_rf_dataforseo_glocations',	//required
				wpfb_nonce: adminjs_script_vars.wpfb_nonce
				};
			//send to ajax to update db
			var jqxhr = jQuery.post(ajaxurl, senddata, function (response){
				//console.log(response);
				if (!$.trim(response)){
					alert('Error D001: Unable to find locations. Contact support.');
				} else {
					var jobqueresponse = JSON.parse(response);
					if(typeof jobqueresponse =='object' && jobqueresponse!=null){
						//console.log(jobqueresponse);
						//set the options for the wprevpro_gdataforseolocation select dropdown
						var selecthmtl='<option value="" >Select Location</option>';
						$.each(jobqueresponse, function(key,valueObj){
							console.log(key + "/" + this.location_name );
							selecthmtl = selecthmtl + '<option value="'+this.location_name+'" >'+this.location_name+'</option>';
						});
						$( "#wprevpro_gdataforseolocation" ).html(selecthmtl);
						

					} else {
						alert(adminjs_script_vars.msg6+" " +response);
						console.log(response);
					}
				}
			});
			jqxhr.fail(function() {
			  alert('Error D002: Unable to find locations. Contact support.');
			});
		};
		//get list of languages for Google - dataforseo
		function ajaxgetdataforseogooglelangs(){
			//console.log("get locations");
			var senddata = {
				action: 'wprp_rf_dataforseo_glangs',	//required
				wpfb_nonce: adminjs_script_vars.wpfb_nonce
				};
			//send to ajax to update db
			var jqxhr = jQuery.post(ajaxurl, senddata, function (response){
				//console.log(response);
				if (!$.trim(response)){
					alert('Error D001: Unable to find locations. Contact support.');
				} else {
					var jobqueresponse = JSON.parse(response);
					if(typeof jobqueresponse =='object' && jobqueresponse!=null){
						console.log(jobqueresponse);
						//set the options for the wprevpro_gdataforseolang select dropdown
						var selecthmtl='<option value="" >Select Language</option>';
						$.each(jobqueresponse, function(key,valueObj){
							console.log(key + "/" + this.language_name );
							selecthmtl = selecthmtl + '<option value="'+this.language_code+'" >'+this.language_name+'</option>';
						});
						$( "#wprevpro_gdataforseolang" ).html(selecthmtl);
						

					} else {
						alert(adminjs_script_vars.msg6+" " +response);
						console.log(response);
					}
				}
			});
			jqxhr.fail(function() {
			  alert('Error D002: Unable to find locations. Contact support.');
			});
		};
		
		//===========for pop-up==========

		//retrieve reviews button clicked
		$( ".retreviewsbtn" ).on("click",function(event) {
			event.preventDefault();
			//get id and badge type
			getrevfunneltempid = $( this ).parent().attr( "templateid" );
			var url = "#TB_inline?inlineId=retreivewspopupdiv";
			tb_show('Download Reviews For This Review Funnel <span id="showinstrrf" class="wprevpro_btnicononly dashicons-before dashicons-editor-help"></span>', url);
			$( "#TB_window" ).css({ "height":"auto !important","width":"700px" });
			$( "#TB_ajaxContent" ).css({ "max-height":"600px" });
			$( "#TB_ajaxContent" ).css({ "width":"auto" });
			$( "#TB_ajaxContent" ).css({ "height":"auto" });
			$( "#getrevsbtnpopup" ).attr("tabindex",-1).focus();
			//show button in case still hidden
			$( ".requestscrapebtn" ).show();
			$( "#btnclickmes" ).hide();
			//list previous jobs in table
			ajaxlistrevfunneljobs();
			
		});
		$( "body" ).on( "click", "#showinstrrf", function() {
			$( "#scrapeinstructionsdiv" ).toggle('slow');
		});

		
		$( ".btnrefreshjoblist" ).on("click",function() {
			//console.log('refresh');
			//show button in case still hidden
			$( ".requestscrapebtn" ).show();
			$( "#btnclickmes" ).hide();
			//list previous jobs in table
			ajaxlistrevfunneljobs();
		});
		
		//ajax for listing job que
		function ajaxlistjobque(tempjobid){
			//console.log("get in line num");
			//var tempjobid = '241629196';
			var numberinline = '';
			//console.log(tempjobid);
			var senddata = {
				action: 'wprp_revfunnel_listjobque',	//required
				wpfb_nonce: adminjs_script_vars.wpfb_nonce,
				jid: tempjobid
				};
			//send to ajax to update db
			var jqxhr = jQuery.post(ajaxurl, senddata, function (response){
				console.log(response);
				if (!$.trim(response)){
					alert(adminjs_script_vars.msg1);
				} else {
					var jobqueresponse = JSON.parse(response);
					if(typeof jobqueresponse =='object' && jobqueresponse!=null){
						console.log(jobqueresponse);
						numberinline = parseInt(jobqueresponse.result);
						//if numberinline higher than 0 then update the message in the table.
						if(numberinline>0){
						$( "#"+tempjobid ).html('still working...<br>number <b>'+numberinline+'</b> in queue');
						}
					} else {
						alert(adminjs_script_vars.msg6+" " +response);
						console.log(response);
					}
				}
			});
			jqxhr.fail(function() {
			  //alert( adminjs_script_vars.msg7);
			});
		};	

		//ajax for listing previous jobs for this review funnel
		var previousjobcomplete = false;
		var lastcrawldaysdiff=0;
		var jobsfound = false;
		function ajaxlistrevfunneljobs(){
			//console.log(getrevfunneltempid);
			$( ".trrfrowloading" ).show();
			//remove previous results
			$( ".trrfhiddendatarow" ).remove();
			$( ".trrfdatarow" ).remove();
			var senddata = {
				action: 'wprp_revfunnel_listjobs',	//required
				wpfb_nonce: adminjs_script_vars.wpfb_nonce,
				fid: getrevfunneltempid
				};
			//send to ajax to update db
			var jqxhr = jQuery.post(ajaxurl, senddata, function (response){
				console.log(response);
				$( ".trrfrowloading" ).hide();
				if (!$.trim(response)){
					alert(adminjs_script_vars.msg1);
				} else {
					var reviewresults = JSON.parse(response);
					if(typeof reviewresults =='object' && reviewresults!=null)
					{
						//catch error
						if(reviewresults.ack=='error'){
							alert(adminjs_script_vars.msg2+" " +response);
							console.log(response);
						} else {
						//console.log(reviewresults);
						var jobobjarray = reviewresults.result;
						//console.log(jobobjarray);
						//loop array and add to table
						var arrayLength = jobobjarray.length;
						var tablehtml = '';
						var tempranondate = '';
						var downloadbtnhtml = '';
						var numberinline;
						var pastjobrowclass='';
						if(arrayLength>0){
							for (var i = 0; i < arrayLength; i++) {
								jobsfound = true;
								//console.log(jobobjarray[i]);
								downloadbtnhtml ='';
								tempranondate = timeConverter(jobobjarray[i].calltimestamp) ;
								if(jobobjarray[i].result_count>0){
									if(i == 0){
										previousjobcomplete = true;
									}
									downloadbtnhtml = '<span class="getrevsbtnpopup rfbtn button button-primary" jobid="'+jobobjarray[i].job_id+'" tabindex="-1"> '+adminjs_script_vars.Download_Reviews+'</span>';
									//hide if greater than 30 days.
									var curtime = Math.floor(Date.now() / 1000);
									//console.log(curtime);
									//console.log(jobobjarray[i].calltimestamp);
									var timedif = parseInt(curtime)-parseInt(jobobjarray[i].calltimestamp);
									if(i == 0){
										lastcrawldaysdiff = timedif/86400;
									}
									
									if(timedif>2592000){
										downloadbtnhtml ="";
										pastjobrowclass = "oldjobrow";
									}
								} else {
									if(i == 0){
										previousjobcomplete = false;
									}
									if(jobobjarray[i].crawl_status=='' || jobobjarray[i].crawl_status=='pending'){
										downloadbtnhtml = adminjs_script_vars.still_working;
										//get number in line
										//===================================
										//---fix this with a cache file on server next time we have backup
										numberinline = ajaxlistjobque(jobobjarray[i].job_id);
									} else {
										previousjobcomplete = true;
									}
									if(jobobjarray[i].percentage_complete==100 && jobobjarray[i].crawl_status!='pending'){
										if(jobobjarray[i].diff_job_id>1){
											downloadbtnhtml = adminjs_script_vars.msg3;
										} else {
											downloadbtnhtml = adminjs_script_vars.msg4;
										}
									}
								}
								tablehtml = tablehtml + '<tr class="trrfdatarow '+pastjobrowclass+'"><td class="jobidtr">'+jobobjarray[i].job_id+' <span class="iconjobmoreinfo dashicons dashicons-info"></span></td> <td>'+tempranondate+'</td> <td>'+jobobjarray[i].result_count+'/'+jobobjarray[i].review_count+'</td><td>'+jobobjarray[i].average_rating+'</td> <td>'+jobobjarray[i].crawl_status+'<br>'+jobobjarray[i].percentage_complete+'%</td> <td id="'+jobobjarray[i].job_id+'" class="statusmsg">'+downloadbtnhtml+'<div style="display:none;" class="loadingspinner downloadrevsbtnspinner"></div><div class="downloadrevsbtntext"></div></td> </tr>';
								//add hidden row with all info for this job_id
								var output = '';
								if(pastjobrowclass == "oldjobrow"){
										output += "Jobs older than 30 days can not be downloaded. ";
									}
								for (var property in jobobjarray[i]) {
									
								  output += property + ': ' + jobobjarray[i][property]+';  ';
								}
								tablehtml = tablehtml + '<tr class="trrfhiddendatarow" style="display:none;"><td class="jobidtrhidden" colspan="6">'+output+'</td></tr>';
								//Do something
								$('input:radio[name=scrapedatechoice]').filter('[value=usediff]').prop('checked', true);
							}
						} else {
							tablehtml = '<tr class="trrfdatarow"><td class="jobidtrhidden" colspan="6">'+adminjs_script_vars.msg5+'</td></tr>';
							//set the radio button
							$('input:radio[name=scrapedatechoice]').filter('[value=nodiff]').prop('checked', true);
							//
						}
						//remove previous data and re-add
						$( ".trrfdatarow" ).remove();
						$( ".joblisttable" ).append($(tablehtml));
						
						}
					}
					else
					{
						alert(adminjs_script_vars.msg6+" " +response);
						console.log(response);
					}
				}
			});
			jqxhr.fail(function() {
			  alert( adminjs_script_vars.msg7);
			});
			
		};	
		//when clicking the info icon next to the job_id, hide show the hidden row
		$( "#popupjobtable" ).on( "click", ".iconjobmoreinfo", function() {
		  $( this ).closest(".trrfdatarow").next(".trrfhiddendatarow").toggle("slow");
		});
		
		//when clicking the Request Scrape btn
		$( ".requestscrapebtn" ).on("click",function() {

			//make sure previous job is complete if we are using Only Scrape New Reviews
			var radioValue = $("input[name='scrapedatechoice']:checked").val();
			
			
			if(radioValue=='usediff' && jobsfound==false){
				alert("You can not use the 'Only Scrape New Reviews' setting if you do not have a completed previous job. Please select the 'Use From Date or Max Number' setting.");
				return false;
			}
			
			if(radioValue=='usediff' && previousjobcomplete==false){
				alert("You can not use the 'Only Scrape New Reviews' setting if the previous job did not complete. Please wait for it to complete or select the 'Use From Date or Max Number' setting.");
				return false;
			}
			if(radioValue=='usediff' && lastcrawldaysdiff>30){
				alert("You can not use the 'Only Scrape New Reviews' setting if the previous job is older than 30 days. Please select the 'Use From Date or Max Number' setting.");
				return false;
			}
			//check quota
			var reviewcredits = parseInt($( "#reviewcredits" ).text());
			if(reviewcredits<1){
				alert("It appears that you have ran out of Review Credits. Review Credits are replenished automatically every year or you can purchase more with the button on this page.");
				return false;
			}
			//alert(lastcrawldaysdiff);
			//hide this button and show spinner
			$( "#btnspinner" ).show();
			$( this ).hide();

			ajaxprofilerevfunnel();
		});
		
		//ajax for adding profile and getting job_id
		function ajaxprofilerevfunnel(){
			//console.log("fid"+getrevfunneltempid);
			//find the scrapedatechoice radio button and pass all the way to server
			var radioValue = $("input[name='scrapedatechoice']:checked").val();
			//make ajax call here to pull reviews from server
			//use funnel details, plus license info, to pull reviews from server and display results
			var senddata = {
				action: 'wprp_revfunnel_addprofile',	//required
				wpfb_nonce: adminjs_script_vars.wpfb_nonce,
				fid: getrevfunneltempid,
				rv: radioValue
				};
			//send to ajax to update db
			var jqxhr = jQuery.post(ajaxurl, senddata, function (response){
				console.log(response);
				$( "#btnspinner" ).hide();
				if (!$.trim(response)){
					alert(adminjs_script_vars.msg8);
				} else {
					var formobject = JSON.parse(response);
					if(typeof formobject =='object')
					{
					  // It is JSON, safe to continue here
					  //$( ".requestscrapebtn" ).show();
					  //console.log(formobject);
					  if(formobject.job_id){
						$( "#btnclickmes" ).html(adminjs_script_vars.msg9);
						$( "#btnclickmes" ).show();
						$( "#btnclickmes" ).addClass('greenfont');
						$( "#btnclickmes" ).removeClass('redfont');
						//call refresh the job list
						setTimeout(ajaxlistrevfunneljobs, 3000);
					  } else {
						  $( "#btnclickmes" ).html(adminjs_script_vars.msg10+" " +response);
						  $( "#btnclickmes" ).show();
						  $( "#btnclickmes" ).addClass('redfont');
						$( "#btnclickmes" ).removeClass('greenfont');
							console.log(response);
					  }
					}
					else
					{
						$( "#btnclickmes" ).html(adminjs_script_vars.msg11+" " +response);
						$( "#btnclickmes" ).addClass('redfont');
						$( "#btnclickmes" ).removeClass('greenfont');
						$( "#btnclickmes" ).show();
						console.log(response);
					}
				}
			});
			jqxhr.fail(function() {
			  alert( adminjs_script_vars.msg12 );
			});
			
		}
		
		
		//for actually downloading the revs, use ajax probably
		var howmanyloopstomake = 0;
		var nextpnum = 0;
		var totrevsreturned = 0;
		var totalrevsinserted = 0;
		
		$( "#popupjobtable" ).on( "click", ".getrevsbtnpopup", function() {
			totrevsreturned = 0;
			totalrevsinserted = 0;
			var tempjid = $( this ).attr( "jobid" );
			//alert(tempjid);
			var thisbutton = $( this );
			$( this ).next( ".downloadrevsbtnspinner" ).show();
			$( this ).hide();
			ajaxgetrevfunnel(tempjid,thisbutton,1,100);
		});
		function ajaxgetrevfunnel(tempjid,thisbutton,pnum,perp){
			var spinnerdiv = thisbutton.next( ".downloadrevsbtnspinner" );
			var textdiv = spinnerdiv.next( ".downloadrevsbtntext" );
			spinnerdiv.addClass('loadingspinner');
			//make ajax call here to pull reviews from server
			//pass funnel id
			// use funnel id to get funnel details from db
			//use funnel details, plus license info, to pull reviews from server and display results
			var senddata = {
				action: 'wprp_revfunnel_getrevs',	//required
				wpfb_nonce: adminjs_script_vars.wpfb_nonce,
				jid: tempjid,
				fid: getrevfunneltempid,
				pnum:pnum,
				perp:perp
				};
			//send to ajax to update db
			var jqxhr = jQuery.post(ajaxurl, senddata, function (response){
				spinnerdiv.removeClass('loadingspinner');
				console.log(response);
				if (!$.trim(response)){
					//alert("Error returning reviews for this url, please contact support.");
					textdiv.html(adminjs_script_vars.msg13+response);
				} else {
					var formobject = JSON.parse(response);
					var msghtml='';
					if(typeof formobject =='object')
					{
					  // It is JSON, safe to continue here
						if(formobject.numreturned>0){
							totrevsreturned = totrevsreturned + formobject.numreturned;
							totalrevsinserted = totalrevsinserted + formobject.numinserted;
							msghtml =  msghtml + "<b>"+String(totrevsreturned) + "</b> "+adminjs_script_vars.msg14;
							msghtml =  msghtml + " <b>"+String(totalrevsinserted) + "</b> "+adminjs_script_vars.msg15;
							if(formobject.numinserted>0){
								msghtml =  msghtml + " " +adminjs_script_vars.msg16+" ";
							}
							//if the result_count is > 100 we need to loop
							var scraperesult = JSON.parse(formobject.scraperesult.result);
							var result_count = scraperesult.result_count;
							//console.log(result_count);
							if(parseInt(result_count)>parseInt(perp)){
								//find how many times to loop
								howmanyloopstomake = parseInt(result_count)/parseInt(perp);
								howmanyloopstomake = Math.ceil(howmanyloopstomake);
								//console.log(howmanyloopstomake);
								//check to make sure we aren't done
								if(howmanyloopstomake>pnum){
									spinnerdiv.addClass('loadingspinner');
									//loop here, need a break if we loop too many times
									nextpnum = pnum + 1;
									//console.log('nextpnum:'+nextpnum);
									ajaxgetrevfunnel(tempjid,thisbutton,nextpnum,perp);
								} else {
									//must be done, update the avatars
									console.log('update cache avatars');
									spinnerdiv.removeClass('loadingspinner');
									setTimeout(function(){ updateavatars(); }, 1000);
								}
							} else {
								//must be done, update the avatars
									console.log('update cache avatars');
									spinnerdiv.removeClass('loadingspinner');
									setTimeout(function(){ updateavatars(); }, 1000);
							}
						} else {
							msghtml =  msghtml + "  "+adminjs_script_vars.msg17;
							console.log(response);
						}
						textdiv.html(msghtml);
					  
					}
					else
					{
						textdiv.html(adminjs_script_vars.msg18+" " +response);
						console.log(response);
					}
				}
			});
			jqxhr.fail(function() {
			  alert( adminjs_script_vars.msg19 );
			});
			
		}
		//=========================
		//update the cache avatars
		function updateavatars(){
				var senddata = {
					action: 'wpfb_update_avatars',	//required
					wpfb_nonce: adminjs_script_vars.wpfb_nonce,
					};
				jQuery.post(ajaxurl, senddata, function (response){});
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
		if(checkedittemplate=="edit"){
			hideshowrows();
			jQuery("#wprevpro_new_template").show("slow");
		} else {
			jQuery("#wprevpro_new_template").hide();
		}
		
		$( "#wprevpro_addnewtemplate" ).on("click",function() {
			if(reviewcredits<1){
				alert("It appears that you have ran out of Review Credits. Review Credits are replenished automatically every year or you can purchase more with the button on this page.");
				return false;
			}
			
		  hideshowrows();
		  jQuery("#wprevpro_new_template").show("slow");
		});	
		$( "#wprevpro_addnewtemplate_cancel" ).on("click",function() {
		  jQuery("#wprevpro_new_template").hide("slow");
		  //reload page without taction and tid
		  setTimeout(function(){ 
			window.location.href = "?page=wp_pro-reviewfunnel"; 
		  }, 500);
		  
		});	
		
		//-------------------------------
		//form validation 
		$("#wprevpro_submittemplatebtn").on("click",function(){
			if(jQuery( "#wprevpro_template_title").val()==""){
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
			
			if($("#wprevpro_site_type").val()!='Google' && $("#wprevpro_site_type").val()!='G2Crowd' && $("#wprevpro_site_type").val()!='AppleAppstore' && $("#wprevpro_site_type").val()!='GooglePlay' && $("#wprevpro_site_type").val()!='GoogleShopping'){
				if(jQuery( "#wprevpro_url").val()==""){
					alert(adminjs_script_vars.msg21);
					//$( "#wprevpro_url" ).focus();
					return false;
				}
				//check to see if site_type matches the url entered
				var tempurl = jQuery( "#wprevpro_url").val();
				var tempsite = $("#wprevpro_site_type").val();
				tempsite = tempsite.toLowerCase();
				tempurl = tempurl.toLowerCase();
				console.log(tempsite);
				console.log(tempurl);
				if(tempurl.search(tempsite)<0){
					//alert(adminjs_script_vars.msg22);
					//return false;
				}
			} else if($("#wprevpro_site_type").val()=='Google'){
				var strquery = jQuery( "#wprevpro_query").val();
				if(strquery=="" && 	$("#wprevpro_placeidorterms").val()=="terms"){
					alert(adminjs_script_vars.msg23);
					//$( "#wprevpro_query" ).focus();
					return false;
				}
				if(jQuery( "#wprevpro_blocks").val()<1){
					alert(adminjs_script_vars.msg24);
					return false;
				}
				
				//if(strquery.trim().indexOf(' ') == -1){
				//	alert(adminjs_script_vars.msg23);
				//	return false;
				//}
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

