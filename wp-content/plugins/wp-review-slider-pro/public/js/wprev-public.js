 (function( $ ) {
	'use strict';
	//document ready
	$(function(){

		//only show one review per a slide on mobile
		//get the attribute if it is set and this is in fact a slider
		$(".wprev-slider").each(function(){
			var oneonmobile = $(this).attr( "data-onemobil" );
			if(oneonmobile=='yes'){
				if (/Mobi|Android/i.test(navigator.userAgent) || $(window).width()<600) {
					/* this is a mobile device, continue */
					//get all the slider li elements, each li is a slide
					var li_elements_old = $(this).children('ul');
					//console.log(li_elements_old);
					if(li_elements_old.length>0){
						//get array of all the divs containing the individual slide
						var divrevs = li_elements_old.find('.w3_wprs-col');
						var divrevarray = divrevs.get();
						//get the classes of the 2 divs under the li
						var div1class = divrevs.parent().attr('class');
						var div2class = divrevs.attr('class');
						//only continue if finding the divs
						if(typeof div2class !== "undefined"){
							//remove the l2, l3, l4, l5 , l6
							div2class = div2class.replace(/[a-z]\d\b/g, 'l12');
							//use the divrevarray to make new li elements with one review in each
							var newulhtml = '';
							var i;
							for (i = 0; i < divrevarray.length; i++) { 
								if(i==0){
									newulhtml += '<li class="wprs_unslider-active"><div class="'+div1class+'"><div class="'+div2class+'">'+divrevarray[i].innerHTML + '</div></div></li>';
								} else {
									newulhtml += '<li><div class="'+div1class+'"><div class="'+div2class+'">'+divrevarray[i].innerHTML + '</div></div></li>';
								}
							}
							//add the load more button if found
							if($(this).find('.wprevpro_load_more_div')[0]!== undefined){
								newulhtml += '<li>'+$(this).find('.wprevpro_load_more_div')[0].outerHTML+'</li>';
							}
							newulhtml +='';
							//replace the old li with the new
							li_elements_old.html(newulhtml);
							//re-initialize the slider if we need to
						}
					}
				}
			}
		});
		//}
		//----------------------
		
		//for new readmore text div set initial max-height.
		var wprevinitialmxheight= {};
		var savedheight= {};
		var wprevsliderini_height= {};
		var wprevsliderini_height_widget= {};
		var masonryobj= {};
		
		//setup scroll bar on individual divs if we are using.
		$(".indrevtextscroll").each(function(){
			var maxlines = $(this).attr( "data-lines" );
			var ahover = $(this).attr( "data-ahover" );
			//get line height and multiple by maxlines.
			var lineheight = getLineHeight(this);
			var sliderid = $(this).closest('.wprevpro').attr('id');
			wprevinitialmxheight[sliderid] = parseInt(maxlines) * lineheight;
			//set div maxheight
			$(this).css('max-height',wprevinitialmxheight[sliderid]);
			//bind to mouseover and scroll if turned on.
			if(ahover=='yes'){
				var ele   = $(this);
				var speed = 70, scroll = 1, scrolling;
				var scrheight = ele.prop('scrollHeight');
				
				//console.log('scrheight:'+scrheight);
				$(this).on( "mouseenter", function() {
					//alert('mouseenter');
					scrolling = window.setInterval(function() {
						ele.scrollTop( ele.scrollTop() + scroll );
						//ele.animate({ scrollTop: scrheight }, 1000);
					}, speed);
					} ).on( "mouseleave", function() {
					//alert('mouseout');
					if (scrolling) {
						window.clearInterval(scrolling);
						scrolling = false;
					}
					} );
			}
		});
		
		//setup readmore for no slider, sliders are done after they are created because of height issues.
		$(".wprev-no-slider").each(function(){
			setreadmoreupgo(this);
		});
		
		//check if hidden behind slide-out or pop-up. delay  
		$(".wprev_pro_slideout_outerdiv").each(function(){
			setTimeout (() => {setreadmoreupgo(this);}, 500);
		});
		
		//function setreadmoreup() {
		//	$('.wprevpro').each(function(){
		//		setreadmoreupgo(this);
		//	});
		//}
		
		function setreadmoreupgo(thisrevtemplate) {

			//first make sure this template is even using read more.
			if($(thisrevtemplate).find('.divwprsrdmore').length){
				
				//make sure this is visible on page loop if not.
				var isvis = $(thisrevtemplate).is(":visible");
				if(!isvis){
					//start loop until becomes visible.
					setTimeout(function(){
						//console.log('loop');
						//console.log(thisrevtemplate);
						setreadmoreupgo(thisrevtemplate);
					},1000);
					return false;
				}

				$(thisrevtemplate).find(".readmoretextdiv").each(function(){
					
					if($(this).next('.divwprsrdmore').find('.wprs_rd_less').is(":hidden")) { 

						var maxlines = $(this).attr( "data-lines" );

						//get line height and multiple by maxlines.
						var lineheight = getLineHeight(this);
						var sliderid = $(this).closest('.wprevpro').attr('id');
						wprevinitialmxheight[sliderid] = parseInt(maxlines) * lineheight;
						
						
						//console.log('-------:');
					//console.log('lineheight:'+lineheight);
					//console.log('maxlines:'+maxlines);
					//console.log('wprevinitialmxheight:'+wprevinitialmxheight[sliderid]);
					//console.log('scrollHeight:'+this.scrollHeight);
					//console.log('clientHeight:'+this.clientHeight);
					
					
						if(this.scrollHeight <= (this.clientHeight+4)){
							$(this).next('.divwprsrdmore').hide();
						} else {
							$(this).next('.divwprsrdmore').css("opacity", 1);
							$(this).next('.divwprsrdmore').show();
							
						}

						//set div maxheight
						if(this.clientHeight>wprevinitialmxheight[sliderid]){
							$(this).css('max-height',this.clientHeight);
							wprevinitialmxheight[sliderid] = this.clientHeight;
						} else {
							$(this).css('max-height',wprevinitialmxheight[sliderid]);
						}
					}
						

				});
			}
		}

		function getLineHeight(elem) {
			let computedStyle = window.getComputedStyle(elem);
			let lineHeight = computedStyle.getPropertyValue('line-height');
			let lineheight;
			
			if (lineHeight === 'normal') {
				let fontSize = computedStyle.getPropertyValue('font-size'); // Get Font Size
				lineheight = parseFloat(fontSize) * 1.4; // 'normal' Line Height Equals To 145% Of Font Size In Most Browsers
			} else {
				lineheight = parseFloat(lineHeight); // The Line Height That Is Not 'normal'
			}
			return lineheight;
		}
		function readmorelineclamp(thisclicked){
				var revtextdiv =  $(thisclicked).parent().prev('.readmoretextdiv');
				var finalheight = revtextdiv.prop('scrollHeight');
				var indrevdivheight = $(thisclicked ).closest( '.outerrevdiv' ).height();
				var iniheight = $(thisclicked ).closest( '.wprevpro' ).height();
				var textdifferenceheight = finalheight - revtextdiv.prop('clientHeight');
				var newindrevdivheight = indrevdivheight + textdifferenceheight;
				var newheight = textdifferenceheight + iniheight;
				
				//only do this for slider or grid that is same height, also doing this for Fade transition
				if(!$(thisclicked).closest('.wprevpro').hasClass('revnotsameheight') || $(thisclicked).closest('.wprevpro').hasClass('wprs_unslider-fade') || $(thisclicked).closest('.wprevpro').hasClass('animateheight')){
					
					//save heights to use later
					var sliderid = $(thisclicked).closest('.wprevpro').attr('id');
					var slideid = sliderid+'-'+$(thisclicked).closest('.w3_wprs-col').index();
					savedheight[slideid] =$(thisclicked).closest('.indrevdiv').css("height");
					wprevsliderini_height[slideid] = $(thisclicked ).closest('.wprev-slider').css("height");
					wprevsliderini_height_widget[slideid] = $(thisclicked ).closest('.wprev-slider-widget').css("height");
					

					$(thisclicked).closest('.indrevdiv').css( 'height', 'auto' );
					$(thisclicked).closest('.indrevdiv').parent().css( 'height', 'auto' );
					
					//console.log('-----------------------');
					//console.log('iniheight:'+iniheight);
					//console.log('newindrevdivheight:'+newindrevdivheight);
					//console.log('indrevdivheight:'+indrevdivheight);
					//console.log('textdifferenceheight:'+textdifferenceheight);
					//console.log('finalheight:'+finalheight);
					
					if(iniheight < newindrevdivheight){
						$(thisclicked ).closest('.wprev-slider').animate({
							height: newheight,
						 }, 500 );
						 $(thisclicked ).closest('.wprev-slider-widget').animate({
							height: newheight,
						 }, 500 );
					}
		
				}
				
				
				//console.log("finalheight:"+finalheight);
				revtextdiv.removeClass('indrevlineclamp');
				var checkfinalheight = revtextdiv.prop('scrollHeight');
				revtextdiv.css('max-height',checkfinalheight);
				$(thisclicked).hide( "fast");
				$(thisclicked).next('.wprs_rd_less').show('fast');
				
				
				//if this is in slickslider with adaptiveHeight then we need to update
				var slideprops = $(thisclicked).closest('.wprevgoslick').attr( "data-slickwprev" );
				if(slideprops){
					var slidepropsobj = JSON.parse(slideprops);
					if(slidepropsobj.adaptiveHeight==true){
						if(slidepropsobj.rows>1){
							//console.log('slick1');
							$(thisclicked).closest('.slickwprev-list').css('height', 'auto');
						} else {
							//console.log('slick2');
							var oldslickheight = $(thisclicked).closest('.slickwprev-list').height();
							//console.log('oldslickheight:'+oldslickheight);
							//console.log('newheight:'+newheight);
							//alert(newheighttemp);
							if(oldslickheight<newindrevdivheight){
								//$(thisclicked).closest('.slickwprev-list').css('height', newheighttemp);
								$(thisclicked ).closest('.slickwprev-list').animate({
									height: newheight,
								 }, 500 );
								 prevslickreadmoreheight = oldslickheight;
							}
						}
					}
				}
				
				//update masonry
				var masonryid = $(thisclicked).closest('.wprevpro').attr('id');
				if (typeof masonryobj[masonryid] !== 'undefined') {
					//going to loop this at 30 fps for .5 seconds.
					var time = 1;
					var interval = setInterval(function() { 
						masonryobj[masonryid].layout();
					   if (time <= 20) { 
						  time++;
					   } else { 
						  clearInterval(interval);
					   }
					}, 25);
				}

	
		}
		
		function readlesslineclamp(thisclicked){
			//set everything back
			var revtextdiv =  $(thisclicked).parent().prev('.readmoretextdiv');
			var revid = $(thisclicked).attr( "data-revid" );
			var sliderid = $(thisclicked).closest('.wprevpro').attr('id');
			
			revtextdiv.css('max-height',wprevinitialmxheight[sliderid]);
			$(thisclicked).hide( "fast");
			$(thisclicked).prev('.wprs_rd_more').show('fast');
			
			//only do this for slider or grid that is same height, also doing this for Fade transition
			if(!$(thisclicked).closest('.wprevpro').hasClass('revnotsameheight') || $(thisclicked).closest('.wprevpro').hasClass('wprs_unslider-fade') || $(thisclicked).closest('.wprevpro').hasClass('animateheight')){
				var sliderid = $(thisclicked).closest('.wprevpro').attr('id');
				var slideid = sliderid+'-'+$(thisclicked).closest('.w3_wprs-col').index();
					//$(thisclicked ).closest('.indrevdiv').animate({
					//	height: savedheight[slideid],
					//  }, 10 );
					//console.log("savedheight:");
					//console.log(savedheight[slideid]);
					//$(thisclicked ).closest('.indrevdiv').height('auto');
					setTimeout(function() {$(thisclicked ).closest('.indrevdiv').css('height',savedheight[slideid]);}, 500);
					
					$(thisclicked ).closest('.wprev-slider').animate({
						height: wprevsliderini_height[slideid],
					 }, 500 );
					$(thisclicked ).closest('.wprev-slider-widget').animate({
						height: wprevsliderini_height_widget[slideid],
					  }, 500 );
			}

			setTimeout(function() {
			  revtextdiv.addClass('indrevlineclamp');
			}, 500);
			
			//if this is in slickslider with adaptiveHeight then we need to update
			var slideprops = $(thisclicked).closest('.wprevgoslick').attr( "data-slickwprev" );
				if(slideprops){
					var slidepropsobj = JSON.parse(slideprops);
					if(slidepropsobj.adaptiveHeight==true){
						$(thisclicked ).closest('.slickwprev-list').animate({
									height: prevslickreadmoreheight,
								 }, 500 );
					}
				}
			
			//update masonry
			var masonryid = $(thisclicked).closest('.wprevpro').attr('id');
			if (typeof masonryobj[masonryid] !== 'undefined') {
				//going to loop this at 30 fps for .5 seconds.
				var time = 1;
				var interval = setInterval(function() { 
					masonryobj[masonryid].layout();
				   if (time <= 20) { 
					  time++;
				   } else { 
					  clearInterval(interval);
				   }
				}, 25);
			}
			
		}
		

		//read more click
		$( "body" ).on( "click", ".wprs_rd_more", function(event) {	
				event.preventDefault();
				//readmoreclicked(this);
				readmorelineclamp(this);
		});
		
		//read less click
		$( "body" ).on( "click", ".wprs_rd_less", function(event) {	
			event.preventDefault();
			//console.log('read less clicked');
			//readlessclicked(this);
			readlesslineclamp(this);
		});

		
			//show read more pop-up if set wprevpro_btn_show_rdpop
			$( "body" ).on( "click", ".wprevpro_btn_show_rdpop", function(event) {	
				event.preventDefault();
				//remove in case we've done this before
				$( "#wprevrdmoremodelcontainer" ).remove();
				//move the modal and append to body.
				var modal = $(this).closest('.outerrevdiv').find('.wprevmodal_modal_rdmore');
				var copymodal = modal.clone();
				$( "body" ).append( "<div id='wprevrdmoremodelcontainer'></div>" );
				$( "body" ).find('#wprevrdmoremodelcontainer').append(copymodal);
				copymodal.show();
				
				//$(this).closest('.outerrevdiv').find('.wprevmodal_modal_rdmore').show();
			});
			//close model if not clicked on 
			$( "body" ).on( "click", ".wprevmodal_modal_rdmore", function(event) {	
				if(!$(event.target).closest('.wprevmodal_modal_rdmore-content').length){
					$(this).hide();
					}
			});
			//close modal on x click
			$( "body" ).on( "click", ".wprevmodal_rdmore_close", function(event) {	
					$(this).closest('.wprevmodal_modal_rdmore').hide();
			});
		
		
			
			//start of form javascript
			
			//check to see if we are only allowing one form submission
			// if so then we need to hide the form, show success message, set constant so we don't show form or pop-up below.
			//===================
			checkformonesub('');
			function checkformonesub(formid){
				var showsubonemes=false;
				if(formid==''){
					var theform = $('.wprevpro_form');
				} else {
					var theform = $('#wprevpro_div_form_'+formid);
				}
				var fsubmitone = theform.find('#wprev_Formonesub').val();
				var fformid = theform.find('#wprevpro_fid').val();
				var fpageid = theform.find('#wprev_postid').val();
				
				var unbrid = '';
			
				if(fsubmitone=='nop' || fsubmitone=='nof'){
					//see if the localStorageis set and they have been here before.
					var wprevformses = JSON.parse(localStorage.getItem("wprevformses") || "[]");
					//console.log(wprevformses);
					wprevformses.forEach(function(wprevformse, index) {
						if(wprevformse.submitted=='yes'){
							//form has been submitted before. continue.
							//are we doing by pageid or formid
							if(fsubmitone=='nop'){
								//see if this matches current page_id
								if(wprevformse.pid==fpageid){
									//console.log('hide by page id');
									showsubonemes = true;
									unbrid = wprevformse.unbrid;
								}
							}
							if(fsubmitone=='nof'){
								//see if this matches current form_id
								if(wprevformse.fid==fformid){
									//console.log('hide by form id');
									showsubonemes = true;
									unbrid = wprevformse.unbrid;
								}
							}
						}
					});
				}

				if(showsubonemes){
					var sfbtn = theform.find('.wprevpro_btn_show_form');
					if(sfbtn.length){
						//console.log('butn:');
						//this is behind a button, coded below onclick
					} else {
						//console.log('no butn:');
						//show hide
						theform.find('.wprevpro_form_submitone_msg').show();
						theform.find('.wprevpro_form_inner').hide();
					}
					//final check against db to see if the review was deleted. if so they can submit again.
					var finalchunbrid = checkdbunbrid(unbrid,theform);
				}
				//autopop-up only if we allow multiple submits or they haven't submitted yet.
				if(!showsubonemes){
					//search for form on page and see if it has autopop attr
					if(theform.attr("autopopup")=='yes' || theform.attr("autoclick")=='yes'){
						theform.find('.wprevpro_form_submitone_msg').hide();
						var modal = theform.find('.wprevmodal_modal');
						modal.show();
						//only pop-up if we are allowing more than one submit and they haven't done it yet.
						theform.find(".wprevpro_form_inner").show();
					}
				}
				return showsubonemes;
			}

			//for searching db to see if this persons previous rev was deleted, return true or false
			function checkdbunbrid(unbrid,theform){
				//console.log('check unbrid:'+unbrid);
				var senddata = {
					action: 'wprp_check_unbrid',	//required
					wpfb_nonce: wprevpublicjs_script_vars.wpfb_nonce,
					cache: false,
					processData : false,
					contentType : false,
					unbrid: unbrid,
					};
				//send to ajax to update db
				var jqxhr = jQuery.post(wprevpublicjs_script_vars.wpfb_ajaxurl, senddata, function (data){
					//console.log('done checking:');
					//console.log(data);
					if(data=="notfound"){
						//the review has been deleted. show form.
						theform.find('.wprevpro_form_submitone_msg').hide();
						theform.find('.wprevpro_form_inner').show();
						
					}

					
				});
			}
			
			
			//find IP if set
			checkformIPandtag();
			function checkformIPandtag(){
				//search for form input on page and see if it has ip input set to yes 
				var wprevformip = $('.wprevpro_form').find('#wprev_ipFormInput');
				//console.log('ip:'+wprevformip.val());
				if(wprevformip.val()=='yes'){
					//find IP address and replace the value in the form
					 fetch('https://api.ipify.org?format=json')
					.then((response) => { return response.json() })
					.then((json) => {
						const ip = json.ip;
						//console.log('ip:'+ip);
						wprevformip.val(ip);
					})
					.catch((err) => { console.error('Error getting IP Address: ${err}') })
				}
				
				//add check for tag in url and add to form as a hidden tag.
				let searchParams = new URLSearchParams(window.location.search);
				if(searchParams.has('wrtg')){
					//has the tag, add it to the form.
					let param = searchParams.get('wrtg')
					$('.wprevpro_form').find('#wprev_urltag').val(param);
				}
				
			}
			//for turning form multiselect in to buttons
			document.querySelectorAll(".wprevpro_multiselect").forEach((selectElement) => {
			  new WPRevProCustomSelect(selectElement);
			});
			
			//also doing this for button in banner if set.
			$(".bnrevuspopform").on("click",function(event){
				var formid = $(this).attr("data-formid");
				var showsubonemes = checkformonesub(formid);
				//make sure msgdb is hidden
				$('#wprevpro_div_form_'+formid).find(".wprevpro_form_msg").hide();
				//this is a pop-up
				// Get the modal
				var modal = $("#wprevmodal_myModal_"+formid);
				//modal.show();
				modal.css("visibility", "visible");
				//only if we are allowing more than one submit and they haven't done it yet.
				if(!showsubonemes){
					$("#wprevpro_div_form_inner_"+formid).show();
				} else {
					$('#wprevpro_div_form_'+formid).find('.wprevpro_form_submitone_msg').show();
				}
			});

			//show form on button click
			$(".wprevpro_btn_show_form").on("click",function(event){
				var formid = $(this).attr("formid");
				var showsubonemes = checkformonesub(formid);
				//make sure msgdb is hidden
				$(this).closest('#wprevpro_div_form_'+formid).find(".wprevpro_form_msg").hide();

				//see if this is a pop-up or slide down
				if($(this).attr('ispopup')=='yes'){
					//this is a pop-up
					// Get the modal
					var modal = $("#wprevmodal_myModal_"+formid);
					//modal.show();
					modal.css("visibility", "visible");
					 
					//only if we are allowing more than one submit and they haven't done it yet.
					if(!showsubonemes){
						$("#wprevpro_div_form_inner_"+formid).show();
					} else {
						$(this).closest('#wprevpro_div_form_'+formid).find('.wprevpro_form_submitone_msg').show();
					}
				} else {
					//if this was autopopped then we need to remove modal, we won't be using again
					if($(this).closest('#wprevpro_div_form_'+formid).attr("autopopup")=='yes'){
						//destroy modal
						$(this).closest('#wprevpro_div_form_'+formid).find('.wprevmodal_modal').unbind();
						$(this).closest('#wprevpro_div_form_'+formid).find('.wprevmodal_modal').show();
						$(this).closest('#wprevpro_div_form_'+formid).find('.wprevmodal_modal').removeClass('wprevmodal_modal');
						$(this).closest('#wprevpro_div_form_'+formid).find('.wprevmodal_modal-content').removeClass('wprevmodal_modal-content');
						$(this).closest('#wprevpro_div_form_'+formid).find('.wprevmodal_close').html('');
						
					}
					if(!showsubonemes){
						$(this).next().find(".wprev_review_form").show();
						$(this).next().find(".wprevpro_form_inner").toggle(1000);
					} else {
						$(this).closest('#wprevpro_div_form_'+formid).find('.wprevpro_form_submitone_msg').toggle(1000);
					}
				}
			});
			//close model if not clicked on 
			$(".wprevmodal_modal").on("click",function(event){
				if(!$(event.target).closest('.wprevmodal_modal-content').length){
					//$(this).hide();
					$(this).find(".wprevpro_form_inner").hide();
				  $(this).css("visibility", "hidden");
				  //$(this).find(".wprevpro_form_inner").css("visibility", "hidden");
				}
			});
			//close modal on x click
			$(".wprevmodal_close").on("click",function(event){
					//$(this).closest('.wprevmodal_modal').hide();
					$(this).closest('.wprevmodal_modal').find(".wprevpro_form_inner").hide();
					
					 $(this).closest('.wprevmodal_modal').css("visibility", "hidden");
					//$(this).closest('.wprevmodal_modal').find(".wprevpro_form_inner").css("visibility", "hidden");
			});

			//for saving form submission pageid and form id in browser so we can hide next time if we want.
			function wprev_formsavepidfid(formid,pageid,uniquebrowserid) {
				//if we are hiding form after a person submits it then we save session storage of form id and page id.
				//console.log(wprevpublicjs_script_vars.page_id);
				var pageid = wprevpublicjs_script_vars.page_id;

				//need to grab current settings first
				var wprevformses = JSON.parse(localStorage.getItem("wprevformses") || "[]");
				wprevformses.push({fid: formid, pid: pageid, submitted: "yes", unbrid: uniquebrowserid});
				//save values in local storage. does not expire.
				localStorage.setItem("wprevformses", JSON.stringify(wprevformses));
			}
			
			//check the form on submit  
			$(".wprev_review_form").on("submit",function(event){
				var ratingreq = '';
				ratingreq = $( this ).find('#wprevpro_rating_req').val();
				if(ratingreq=="yes"){
					var checkedvalue = $('input[name=wprevpro_review_rating]:checked').val();
					if ($('input[name=wprevpro_review_rating]:checked').length && checkedvalue!='0') {
					   // at least one of the radio buttons was checked
					   //return true; // allow whatever action would normally happen to continue
					} else {
						   // no radio button was checked
						   alert('Please select a rating.');
						   $( ".wprevpro-rating" ).focus();
						   event.preventDefault(); 
						   return false; // stop whatever action would normally happen
					}
				}
				//if this is non-ajax hide the button
				$(this).find('.btnwprevsubmit').hide();
				$(this).find('.btnwprevsubmit').closest('.wprevform-field').next('.wprev_loader').show();
				
				var formid = $(this).find('#wprevpro_fid').val();
				var pageid = $(this).find('#wprev_postid').val();
				var unbrid = $(this).find('#wprev_unique_id').val();
				//save in session if set.
				wprev_formsavepidfid(formid,pageid,unbrid);
			});
			
			//check if this browser supports ajax file upload FormData
			function wprev_supportFormData() {
				return !! window.FormData;
			}
			
			function hideshowloader(buttondiv,showloader){
				//hide the sumbit button so they don't push twice
				if(showloader==true){
					buttondiv.hide();
					buttondiv.next('.wprev_loader').show();
				} else {
					buttondiv.show();
					buttondiv.next('.wprev_loader').hide();
				}
			}
			
			function resetform(theform){
				$(theform).trigger("reset");
			}
			
			function closeformandscroll(showformbtn){
				//wait a couple of seconds, hide the form after the message is shown. only on hidden form
				if ( showformbtn.length ) {
					setTimeout(function(){
						showformbtn.next().find(".wprevpro_form_inner").toggle(1000);
						//scroll up back to button
						$('html, body').animate({scrollTop: $( ".wprevpro_btn_show_form" ).offset().top-200}, 1000);
					}, 1500);
				}
			}
			
			//hide rating after click, if setting turned on .hasClass( "foo" )
			$(".wprevpro-rating").on("click",function(event){
				if($(this).closest('.wprevpro-rating-wrapper').hasClass('hideafterclick')){
					$(this).closest('.wprevpro-field-review_rating').hide();
				}
			});
			
			//when clicking stars on preview form
			$('.wprevpro-rating-radio-lbl').on("click",function() {
				var clickedstar = $( this ).prev().val();
				var clickedelement = $( this );
				hideshowrestofform(clickedelement,clickedstar);
			});
			//when clicking thumbs up or down
			$('.wprevpro_form').on( "click", "#wppro_fvoteup", function() {
				var clickedstar = 5;
				var clickedelement = $( this );
				//set radio
				$("#wprevpro_review_rating-star5").prop("checked", true);
				//remove and add class to show filled in value, different for smiles
				changthumbonclick('up',clickedelement);
				//find out if we are hiding social links logic
				hideshowrestofform(clickedelement,clickedstar);
			});
			$('.wprevpro_form').on( "click", "#wppro_fvotedown", function() {
				var clickedstar = 2;
				var clickedelement = $( this );
				//set radio
				$("#wprevpro_review_rating-star2").prop("checked", true);
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
				
				var globshowval = $( clickedelement ).closest('form').find('#wprev_globshowval').val();
				var globhiderest = $( clickedelement ).closest('form').find('#wprev_globhiderest').val();
				if(globshowval!=''){
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
			}
			
			//setting hidden pagename variable if we are using it.
			$( '.wprevpro_selpage' ).on("change",function(event) {
				var selectedText = $(this).find("option:selected").text();
				$(this).next('.wprevpro_selpagename').val(selectedText);
			});
			$( '.wprevpro_selpage' ).on("click",function(event) {
				var selectedText = $(this).find("option:selected").text();
				$(this).next('.wprevpro_selpagename').val(selectedText);
			});
			
			
			$( '#wprevpro_submit_ajax' ).on("click",function(event) {
				//ajax form submission
				//find the form id based on this button
				var thisform = $(this).closest('form');
				var thisformcontainer = $(this).closest('.wprevpro_form');
				var thisformdbmsgdiv = thisformcontainer.find('.wprevpro_form_msg');
				var thisformsbmitdiv = thisform.find('.wprevpro_submit');
				var thisshowformbtn = thisformcontainer.find('.wprevpro_btn_show_form');
				var formid = thisform.find('#wprevpro_fid').val();
				var pageid = thisform.find('#wprev_postid').val();
				var unbrid = thisform.find('#wprev_unique_id').val();
				
				//hide the sumbit button so they don't push twice
				hideshowloader(thisformsbmitdiv,true);
				thisformdbmsgdiv.removeClass('wprevpro_submitsuccess');
				thisformdbmsgdiv.removeClass('wprevpro_submiterror');
				
				var fileuploadinput = thisform.find('#wprevpro_review_avatar');
				//default to formdata, but use serialize if not uploading file and browser supports it
				var useserializemethod = false;
				
				//check if we are uploading a file, if so then see if browser supports. if not then use regular submit
				var imgVal = fileuploadinput.val(); 
				var checkformdatasupport = wprev_supportFormData();
				if(imgVal!="" && checkformdatasupport==false){
					//formdata not supported
					return false;
				} else {
					//stop regular form submission continue with ajax
					event.preventDefault();
					if(checkformdatasupport==false){
						useserializemethod = true;
					}
				}

				//if we are not uploading a file use the serialize method
				if(useserializemethod==true){
					var stringofvariables = thisform.serialize();
					//console.log(stringofvariables);
				
					var senddata = {
						action: 'wprp_save_review',	//required
						wpfb_nonce: wprevpublicjs_script_vars.wpfb_nonce,
						cache: false,
						processData : false,
						contentType : false,
						data: stringofvariables,
						};
					//send to ajax to update db
					var jqxhr = jQuery.post(wprevpublicjs_script_vars.wpfb_ajaxurl, senddata, function (data){
						//console.log(data);
						var jsondata = $.parseJSON(data);
						if(jsondata.error=="no"){
								hideshowloader(thisformsbmitdiv,false);
								//display success message
								thisformdbmsgdiv.html(jsondata.successmsg);
								thisformdbmsgdiv.addClass('wprevpro_submitsuccess');
								thisformdbmsgdiv.show('slow');
								resetform(thisform);
								closeformandscroll(thisshowformbtn);
								wprev_formsavepidfid(formid,pageid,unbrid);
						} else {
								//display error message
								hideshowloader(thisformsbmitdiv,false);
								thisformdbmsgdiv.html(jsondata.dbmsg);
								thisformdbmsgdiv.addClass('wprevpro_submiterror');
								thisformdbmsgdiv.show('slow');
						}
						
					});
					jqxhr.fail(function() {
					  //display error message
						hideshowloader(thisformsbmitdiv,false);
						thisformdbmsgdiv.html(jsondata.dbmsg);
						thisformdbmsgdiv.addClass('wprevpro_submiterror');
						thisformdbmsgdiv.show('slow');
						hideshowloader(thisformsbmitdiv,false);
					});
				
				} else {
					//use formdata method
					//now using formdata so we can upload, almost works in all browsers
					var formdata = new FormData(thisform[0]);
					formdata.append('action', 'wprp_save_review');
					formdata.append('wpfb_nonce', wprevpublicjs_script_vars.wpfb_nonce);

					$.ajax({
						url: wprevpublicjs_script_vars.wpfb_ajaxurl,
						action: 'wprp_save_review',	//required
						wpfb_nonce: wprevpublicjs_script_vars.wpfb_nonce,
						type: 'POST',
						data: formdata,
						contentType:false,
						processData:false,
						success: function(data){
							console.log(data);
							var jsondata = $.parseJSON(data);
							//console.log(jsondata);
							if(jsondata.error=="no"){
								hideshowloader(thisformsbmitdiv,false);
								//display success message
								thisformdbmsgdiv.html(jsondata.successmsg);
								thisformdbmsgdiv.addClass('wprevpro_submitsuccess');
								thisformdbmsgdiv.show('slow');
								resetform(thisform);
								closeformandscroll(thisshowformbtn);
								wprev_formsavepidfid(formid,pageid,unbrid);
							} else {
								//display error message
								hideshowloader(thisformsbmitdiv,false);
								thisformdbmsgdiv.html(jsondata.dbmsg);
								thisformdbmsgdiv.addClass('wprevpro_submiterror');
								thisformdbmsgdiv.show('slow');
							}
						  },
						error: function(data){
							console.log(data);
							var jsondata = $.parseJSON(data);
							console.log(jsondata);
							//display error message
								hideshowloader(thisformsbmitdiv,false);
								thisformdbmsgdiv.html(jsondata.dbmsg);
								thisformdbmsgdiv.addClass('wprevpro_submiterror');
								thisformdbmsgdiv.show('slow');
						  },
					});
					
				}

			});

		//for clicking the floating badge or a badge with a slide-out
		$( ".wprevpro_badge_container" ).on("click",function(event) {
				//console.log('here');
				var onclickaction = $(this).attr('data-onc');
				var onclickurl =  $(this).attr('data-oncurl');
				var onclickurltarget =  $(this).attr('data-oncurltarget');
				var badgeid = $(this).attr('data-badgeid');
			
				//first close any open popups or sliders
				$('.wprevpro_slideout_container').each(function(){
					var parentid = $(this).parent().attr('id');
					var slideid = $(this).attr('id');
					slideid = slideid.replace('wprevpro_badge_slide_', '');
					//if this is a different slide then we close it.
					if(Number(slideid)!=Number(badgeid) && $(this).is(":visible") && parentid!='preview_badge_outer'){
						//$( this).hide();
						$( this).css("visibility", "hidden");
					}
				 });

			//only do this if not clicking an arrow  wprs_rd_less  wprev_pro_float_outerdiv-close  
			if(!$(event.target).closest('.wprs_unslider-arrow').length && !$(event.target).closest('.wprs_rd_less').length && !$(event.target).closest('.wprs_rd_more').length && !$(event.target).closest('.wprevpro_btn_show_rdpop').length && !$(event.target).closest('.wprs_unslider-nav').length && !$(event.target).closest('a').length && !$(event.target).closest('.wprevpro_load_more_btn').length  && !$(event.target).closest('.wprev_pro_float_outerdiv-close').length && !$(event.target).hasClass('slickwprev-arrow') && !$(event.target).closest('.slickwprev-dots').length ) {
				//console.log('here2');
				if(onclickaction=='url'){
					if(onclickurl!=""){
						if(onclickurltarget=='same'){
							var win = window.open(onclickurl, '_self');
						} else {
							var win = window.open(onclickurl, '_blank');
						}
						if (win) {
							//Browser has allowed it to be opened
							win.focus();
						} else {
							//Browser has blocked it
							alert('Please allow popups for this website');
						}
					} else {
						alert("Please enter a Link to URL value.");
					}
					return false;
				} else if(onclickaction=='slideout'){
					//slideout the reviews from the side, find the correct one in relation to this click 
					//starting in v11.9.1 we are using visibility hidden instead so read more works 
					if ($("#wprevpro_badge_slide_"+ badgeid).css("visibility") === "visible") {
					  $("#wprevpro_badge_slide_"+ badgeid).css("visibility", "hidden");
					} else {
					  $("#wprevpro_badge_slide_"+ badgeid).css("visibility", "visible");
					}
					
					//$( "#wprevpro_badge_slide_"+ badgeid).toggle(0,function() {
						 //fix height if we need to. if it doesn't have the revnotsameheight class then it is same height
						var wprevprodivvar = $( "#wprevpro_badge_slide_"+ badgeid).find('.wprevpro');
						if(!wprevprodivvar.hasClass('revnotsameheight')){
							var indrevdiv = wprevprodivvar.find('div');
							if(wprevprodivvar.hasClass('wprev-no-slider')){
								//this is grid
								checkfixheightgrid(indrevdiv);
							} else if(wprevprodivvar.hasClass('wprev-slider')){
								//regular slider
								checkfixheightslider(indrevdiv);
							}
						}
						//for slick we are recreating no matter what.
						if(wprevprodivvar.hasClass('wprev-slick-slider')){
							//destroy
							//console.log('badgeid:'+badgeid);
							wprevprodivvar.find('.wprevgoslick').slickwprev('unslickwprev');
							//slick slider, need to recreate slider.
							createaslick(wprevprodivvar.find('.wprevgoslick'));
						}

						//check if we need to masonry this
						turnonmasonry();
					  //});
					  

					return false;
				} else if(onclickaction=='popup'){
					//popup the reviews in to a modal, find the correct one in relation to this click 
					if ($("#wprevpro_badge_pop_"+ badgeid).css("visibility") === "visible") {
					  $("#wprevpro_badge_pop_"+ badgeid).css("visibility", "hidden");
					  //$("#wprevpro_badge_pop_"+ badgeid).css("display", "");
					} else {
					  $("#wprevpro_badge_pop_"+ badgeid).css("visibility", "visible");
					  //$("#wprevpro_badge_pop_"+ badgeid).css("display", "block");
					}

					  $( "#wprevpro_badge_pop_"+ badgeid).find('.wprs_unslider').css('margin-left', '25px');
					  $( "#wprevpro_badge_pop_"+ badgeid).find('.wprs_unslider').css('margin-right', '25px');
					  $( "#wprevpro_badge_pop_"+ badgeid).find('.wprev-slider').css('height', 'unset');
					  $( "#wprevpro_badge_pop_"+ badgeid).find('.wprevgoslick').css('margin-left', '12px');
					  $( "#wprevpro_badge_pop_"+ badgeid).find('.wprevgoslick').css('margin-right', '12px');
					  
					  //fix height if we need to. if it doesn't have the revnotsameheight class then it is same height
						var wprevprodivvar = $( "#wprevpro_badge_pop_"+ badgeid).find('.wprevpro');
						if(!wprevprodivvar.hasClass('revnotsameheight')){
							var indrevdiv = wprevprodivvar.find('div');
							if(wprevprodivvar.hasClass('wprev-no-slider')){
								//this is grid
								//alert('here');
								checkfixheightgrid(indrevdiv);
							} else if(wprevprodivvar.hasClass('wprev-slider')){
								//regular slider
								checkfixheightslider(indrevdiv);
							}
						}
						//for slick we recreate no matter what
						if(wprevprodivvar.hasClass('wprev-slick-slider')){
								//destroy
								wprevprodivvar.find('.wprevgoslick').slickwprev('unslickwprev');
								//slick slider, need to recreate slider.
								createaslick(wprevprodivvar.find('.wprevgoslick'));
						}
						turnonmasonry();

					//if this is a showing a slider we need to unset the height
					//setTimeout(function(){
					//}, 50);
					//check if we need to masonry this
					
					return false;
				}
			}
		});
		//close slideout onclick on everything but it, also using if the slide out was opened from a badge
		$(document).on("click",function(event) { 
			if(!$(event.target).closest('.wprevpro_slideout_container').length && !$(event.target).closest('.updatesliderinput').length  && !$(event.target).closest('.wprevpro_badge').length && !$(event.target).closest('.lity').length) {
				if($('.wprevpro_slideout_container').is(":visible")) {
					//$('.wprevpro_slideout_container').hide();
					$('.wprevpro_slideout_container').css("visibility", "hidden");
				}
			}        
		});
		//close slide-out on x click
		$(".wprevslideout_close").on("click",function(event){
				//$(this).closest('.wprevpro_slideout_container').hide();
				$(this).closest('.wprevpro_slideout_container').css("visibility", "hidden");
		});

		//for admin preview
		$( "#preview_badge_outer" ).on( "click", ".wprevpro_load_more_btn", function(event) {
	
				//need function to load more.
				loadmorerevs(this,'');
		});
		
			
		var lastclickedpagenum = 1;
		var loadedpagehtmls={};
		var loadedpaginationdiv={};
		//need to find first page of reviews if this is a pagination and save to global loadedpagehtmls
		//look for pagination div
		$( ".wppro_pagination" ).each(function( index ) {
			//find templateid
			var templateid = Number($( this ).attr( "data-tid" ));
			var ismasonry = $(this).attr('data-ismasonry');
			if(ismasonry=='yes'){
				var clone = $( this ).closest('div.wprevpro').find('div.wprs_masonry_js').clone();
			} else {
				var clone = $( this ).closest('div.wprevpro').clone();
			}

			clone.find('.wppro_pagination').remove();
			var reviewshtml = clone.html();
			//save in global, different if masonry proptid15p1tnullunsetundefined

			loadedpagehtmls['tid'+templateid+'p'+1+'t']=reviewshtml;
		});
		
		//for searching pagination text
		var txtsearchtimeout = null;
		$('.wprev_search').on('input', function() {
			var myValue = $(this).val();
			var myLength = myValue.length;
			clearTimeout(txtsearchtimeout);
			if(myLength>1 || myLength==0){
				//search here
				// Make a new timeout set to go off in 800ms
				txtsearchtimeout = setTimeout(function () {
					var parentdiv = $(this).closest('.wprev_search_sort_bar').next('.wprevpro').find('.wppro_pagination');
					//fix if searching using Load More button
					if(!parentdiv.length){
						parentdiv = $(this).closest('.wprev_search_sort_bar').next('.wprevpro').find('.wprevpro_load_more_btn');
					}
					//fix if using regular slider
					if(!parentdiv.length){
						parentdiv = $(this).closest('.wprev_search_sort_bar').next('.wprs_unslider').find('.wprevpro_load_more_btn');
					}
			
					$(this).closest('.wprev_search_sort_bar').find('.wprppagination_loading_image_search').show();
					startofgetpagination(1,parentdiv);
				}.bind(this), 600);
			}
		});
		//for using sort drop down on reviews
		$('.wprev_sort').on('change', function() {
			var myValue = $(this).val();
			var myLength = myValue.length;
			if(myLength>0 || myLength==0){
				var parentdiv = $(this).closest('.wprev_search_sort_bar').next('.wprevpro').find('.wppro_pagination');
				//fix if searching using Load More button
					if(!parentdiv.length){
						parentdiv = $(this).closest('.wprev_search_sort_bar').next('.wprevpro').find('.wprevpro_load_more_btn');
					}
					//fix if using regular slider
					if(!parentdiv.length){
						parentdiv = $(this).closest('.wprev_search_sort_bar').next('.wprs_unslider').find('.wprevpro_load_more_btn');
					}
				$(this).closest('.wprev_search_sort_bar').find('.wprppagination_loading_image_search').show();
				startofgetpagination(1,parentdiv);
			}
		});
		//for clicking a quick search tag
		$('.wprevpro_stag').on('click', function() {
			//var showbtn = false;
			
			//if this already has the class current then we unsearch
			if($(this).hasClass('current')){
				$('.wprev_search').val('');
				$(this).removeClass('current');
				
			} else {
				var myValue = $(this).text();
				//remove all other current classes if we picked before
				$('.wprevpro_stag').removeClass('current');
				$(this).addClass('current');
				$('.wprev_search').val(myValue);
			}
			
			var parentdiv = $(this).closest('.wprev_search_sort_bar').next('.wprevpro').find('.wppro_pagination');
			//fix if searching using Load More button
				if(!parentdiv.length){
					parentdiv = $(this).closest('.wprev_search_sort_bar').next('.wprevpro').find('.wprevpro_load_more_btn');
					parentdiv.show();
				}
				//fix if using regular slider
					if(!parentdiv.length){
						parentdiv = $(this).closest('.wprev_search_sort_bar').next('.wprs_unslider').find('.wprevpro_load_more_btn');
					}
			$(this).closest('.wprev_search_sort_bar').find('.wprppagination_loading_image_tag').show();
			
			//since we are clicking tag then clear notinstring value.
			$(parentdiv).attr('data-notinstring','');
			
			startofgetpagination(1,parentdiv);

		});
		//for clicking a quick type filter
		$('.wprevpro_stype_btn').on('click', function() {
			//if this already has the class current then we unsearch
			var myValue = $(this).text();
			var tid = $(this).closest('.wprev_search_sort_bar').attr('data-tid');
			if($(this).hasClass('current')){
				$(this).parent().attr("data-rtype","");
				$(this).removeClass('current');
			} else {
				//remove all currents then add to this one.
				//$(this).parent().find('.wprevpro_stype_btn').removeClass('current');
				if(clearalltypes(tid)){
					$(this).parent().attr("data-rtype",myValue);
					$(this).addClass('current');
				}
			}
			var parentdiv = $(this).closest('.wprev_search_sort_bar').next('.wprevpro').find('.wppro_pagination');
			//fix if searching using Load More button
				if(!parentdiv.length){
					parentdiv = $(this).closest('.wprev_search_sort_bar').next('.wprevpro').find('.wprevpro_load_more_btn');
				}
				//fix if using regular slider
				if(!parentdiv.length){
					parentdiv = $(this).closest('.wprev_search_sort_bar').next('.wprs_unslider').find('.wprevpro_load_more_btn');
				}
			
			$(this).closest('.wprev_search_sort_bar').find('.wprppagination_rtypes_loading_img').show();
			startofgetpagination(1,parentdiv);

		});
		//for pagination click, ajax second page add to html, only doing for grid
		$( ".wppro_pagination" ).on( "click", ".wppro_page_numbers", function(event) {
			event.stopPropagation();
			event.preventDefault();
			var clickedthis = this;
			var parentdiv = $(clickedthis).closest('.wppro_pagination');
			var clickedpagenum = $(clickedthis).text();
			//alert(sliderid);
			if($(clickedthis).hasClass("current")==false){
				$(parentdiv).find( ".wprppagination_loading_image" ).show();
				startofgetpagination(clickedpagenum,parentdiv);
			}
		});
		
		//for clicking a type button in a banner
		$('.wprev_banner_top_source').on('click', function() {
			//if this already has the class current then we unsearch data-stype
			var myValue = $(this).attr('data-stype');
			var tid = $(this).closest('.wprev_banner_outer').attr('data-tid');
			
			//alert(myValue);
			
			if($(this).hasClass('cursel')){
				$(this).parent().attr("data-rtype","");
				$(this).removeClass('cursel');
				//add class to All Reviews.
				$(this).parent().find('.wprev_banner_top_source').first().addClass('cursel');
			} else {
				//remove all currents then add to this one.
				//$(this).parent().find('.wprev_banner_top_source').removeClass('cursel');
				if(clearalltypes(tid)){
					$(this).parent().attr("data-rtype",myValue);
					$(this).addClass('cursel');
				}
			}
			var parentdiv = $(this).closest('.wprev_banner_outer').siblings('.wprevpro').find('.wppro_pagination');
			//fix if searching using Load More button
				if(!parentdiv.length){
					parentdiv = $(this).closest('.wprev_banner_outer').siblings('.wprevpro').find('.wprevpro_load_more_btn');
				}
				//fix if using regular slider
				if(!parentdiv.length){
					parentdiv = $(this).closest('.wprev_banner_outer').siblings('.wprs_unslider').find('.wprevpro_load_more_btn');
				}
				
			var theimagetemp = $(this).find('.wppro_banner_icon');
			flashImage(theimagetemp, 'start');
			startofgetpagination(1,parentdiv);

		});
		//clears all types from banners and buttons, so we can only click one at a time.
		function clearalltypes(tid){
			//console.log('cleartypes');
			$('#wprev_banner_id_'+tid).find('.wprev_banner_top').attr("data-rtype","");
			$('#wprev_banner_id_'+tid).find('.wprev_banner_top_source').removeClass('cursel');
			
			$('#wprev_search_sort_bar_id_'+tid).find('.wprevpro_rtypes_div').attr("data-rtype");
			$('#wprev_search_sort_bar_id_'+tid).find('.wprevpro_stype_btn').removeClass('current');
			return true;
		}
		
		//simple function to slowly flash image to make it look like it is loading.
		var flashimgInterval;
		function flashImage(theimage, startorstop) {
 			if(startorstop == "start"){
				let isHidden = false;
				theimage.addClass('wprevflashingimage');
				flashimgInterval = setInterval(() => {
					if (isHidden) {
						theimage.removeClass('opaci2');
					} else {
						theimage.addClass('opaci2');
					}
					isHidden = !isHidden;
				}, 200); // Change opacity every 500 milliseconds (0.5 seconds)
			} else {
				clearInterval(flashimgInterval);
				setTimeout(function() {
						$('.wprevflashingimage').removeClass('opaci2');
						$('.wprevflashingimage').removeClass('wprevflashingimage');
					}, 100);
			}
        }
		
		//banner leave review button action.
		$('.wprevdropbtn').on('click', function() {
			$(this).next('.wprevdropdown-content').slideToggle();
		});
		
		//function to start get pagination data
		function startofgetpagination(clickedpagenum,parentdiv){
			//console.log('sgp');
			//if this is the slick slider and you clicked a filter then we stop here and rebuild slider
			if(parentdiv.attr('data-slideshow')=='sli'){
				//console.log('slick');
				var thisslick = parentdiv.parent().prev('.wprevgoslick');
				var thebutton = parentdiv;
				//reset call number to 1. data-callnum
				thebutton.attr("data-callnum","0");
				thebutton.attr("data-notinstring","");
				loadmorerevs(thebutton,thisslick,'yes');
				return false;
			}
			//if this is normal slider then we stop here and rebuild
			if(parentdiv.attr('data-slideshow')=='yes'){
				var thebutton = parentdiv;
				//reset call number to 1. data-callnum
				thebutton.attr("data-callnum","0");
				thebutton.attr("data-notinstring","");
				thebutton.attr("hideldbtn","");
				//double check to make sure loadmorebtn div is in the correct spot. we need to move if not.
				if(thebutton.parent().prev().length > 0){
					//loadmorebtn must be in wrong location, move so it is in last li by itself.
					//remove hideldbtn attribute since we are resetting.
					//thebutton.removeAttr('hideldbtn');
					var divtomove = thebutton.parent('.wprevpro_load_more_div');
					var tempul = thebutton.closest('ul');
					divtomove.detach();
					tempul.children('li').last().append(divtomove);
				}

				loadmorerevs(thebutton,'','yes');
				return false;
			}
			
			//console.log(parentdiv);
			//check if arrow clicked
			if(clickedpagenum=='>'){
				clickedpagenum = 1 + Number(lastclickedpagenum);
			} else if(clickedpagenum=='<'){
				clickedpagenum = Number(lastclickedpagenum)-1;
			} else {
				clickedpagenum = Number(clickedpagenum);
			}
			//if nothing clicked then this is first page
			if(clickedpagenum<2){
				clickedpagenum = 1;
			}
			
			
			var numperrow = $(parentdiv).attr('data-perrow');
			var numrows = $(parentdiv).attr('data-nrows');
			var cnum = '';
			var revtemplateid = $(parentdiv).attr('data-tid');
			var notinstr = $(parentdiv).attr('data-notinstring');
			var cpostid = Number($(parentdiv).attr('data-cpostid'));
			var shortcodepageid = $(parentdiv).attr('data-shortcodepageid');
			var shortcodelang = $(parentdiv).attr('data-shortcodelang');
			var shortcodetag = $(parentdiv).attr('data-shortcodetag');
			
			var ismasonry = $(parentdiv).attr('data-ismasonry');
			var revsameheight = $(parentdiv).attr( "data-revsameheight" );
			var lastslidenum = $(parentdiv).attr('data-lastslidenum');
			var totalreviewsindb = $(parentdiv).attr('data-totalreviewsindb');
			
			var spinner = $(parentdiv).find( ".wprppagination_loading_image" );
			//spinner.show();
			//see if we have search text
			var searchtext = $(parentdiv).closest('.wprevpro').prev('.wprev_search_sort_bar').find('.wprev_search').val();
			
			//console.log('searchtext:'+searchtext);

			//override searchtext if we clicked a tag
			if($('.wprevpro_stag.current').text()!=''){
				searchtext = $('.wprevpro_stag.current').text();
				//console.log('searchtexttag:'+searchtext);
			}
			
			//see if we are overriding default sort
			var sorttext = $(parentdiv).closest('.wprevpro').prev('.wprev_search_sort_bar').find('#wprevpro_header_sort').val();
			
			//see if we have a rating specified
			var ratingfilter = $(parentdiv).closest('.wprevpro').prev('.wprev_search_sort_bar').find('#wprevpro_header_rating').val();
			
			//see if we have a language specified
			var langfilter = $(parentdiv).closest('.wprevpro').prev('.wprev_search_sort_bar').find('#wprevpro_header_langcodes').val();
			
			//see if we are specified review type. $(this).parent().attr("data-rtype","");
			var rtype = '';
			if($('#wprev_search_sort_bar_id_'+revtemplateid).find('.wprevpro_rtypes_div').attr("data-rtype")){
			rtype = $('#wprev_search_sort_bar_id_'+revtemplateid).find('.wprevpro_rtypes_div').attr("data-rtype");
			}

			//see if we are clicking on banner type. wprev_banner_top
			if($('#wprev_banner_id_'+revtemplateid).find('.wprev_banner_top').attr("data-rtype")){
				rtype = $('#wprev_banner_id_'+revtemplateid).find('.wprev_banner_top').attr("data-rtype");
			}
			
			//fix for null and undefined.
			if (searchtext == null) {searchtext = '';}
			if (sorttext == null) {sorttext = '';}
			if (ratingfilter == null) {ratingfilter = '';}
			if (langfilter == null) {langfilter = '';}
			if (rtype == null) {rtype = '';}

			//make ajax call
			 var senddata = {
				action: 'wprp_load_more_revs',	//required
				wpfb_nonce: wprevpublicjs_script_vars.wpfb_nonce,
				cache: false,
				processData : false,
				contentType : false,
				perrow: numperrow,
				nrows: numrows,
				callnum: cnum,
				clickedpnum: clickedpagenum,
				notinstring:notinstr,
				revid: revtemplateid,
				onereview: 'no',
				cpostid: cpostid,
				shortcodepageid: shortcodepageid,
				shortcodelang: shortcodelang,
				shortcodetag: shortcodetag,
				textsearch: searchtext,
				textsort: sorttext,
				textrating: ratingfilter,
				textlang: langfilter,
				textrtype: rtype,
				};
			//console.log(senddata);
			//send to ajax to update db
			var paginationhtml = '';
			var havepagesaved = false;
			
			//check to see if this page html has been viewed before, load if so.
			var property = 'tid'+senddata.revid+'p'+senddata.clickedpnum+'t'+senddata.textsearch+senddata.textsort+senddata.textrating+senddata.textlang+senddata.textrtype;
			var notinstring = property+'-notinstr';
			var lcpn = property+'-lcpn';
			var lsn = property+'-lsn';
			var ttrindb = property+'-ttrindb';
			var trevppage = property+'-trevppage';
			
			//console.log('first prop: '+property);
			//console.log(loadedpagehtmls);

			if (typeof loadedpagehtmls[property] !== 'undefined' && (searchtext=="" ||  searchtext==null)){
				havepagesaved = true;
			}
			
			//only using saved date if not using filter
			if(!ratingfilter){ratingfilter='unset';}
			if(!langfilter){langfilter='unset';}
			
			//check to see if this is a button. Button will not have spinner div
				if(!spinner.length){
					//change parentdiv here to div above btn
					parentdiv = parentdiv.parent('.wprevpro_load_more_div');
				}
			//either ajax or load from saved
			if(havepagesaved){
				//console.log('usesaveddata');
				var jsondata = new Object();
				jsondata['innerhtml'] = loadedpagehtmls[property];
				/*
				jsondata['clickedpnum'] =senddata.clickedpnum;
				jsondata['lastslidenum'] = lastslidenum;
				jsondata['totalreviewsindb'] = totalreviewsindb;
				jsondata['reviewsperpage'] = Number(numperrow)*Number(numrows);
				*/
				if(loadedpagehtmls[lcpn]){
					jsondata['clickedpnum'] =loadedpagehtmls[lcpn];
				} else {
					jsondata['clickedpnum'] =senddata.clickedpnum;
				}
				if(loadedpagehtmls[lsn]){
					jsondata['lastslidenum'] =loadedpagehtmls[lsn];
				} else {
					jsondata['lastslidenum'] =lastslidenum;
				}
				if(loadedpagehtmls[ttrindb]){
					jsondata['totalreviewsindb'] =loadedpagehtmls[ttrindb];
				} else {
					jsondata['totalreviewsindb'] =totalreviewsindb;
				}
				if(loadedpagehtmls[trevppage]){
					jsondata['reviewsperpage'] =loadedpagehtmls[trevppage];
				} else {
					jsondata['reviewsperpage'] = Number(numperrow)*Number(numrows);
				}
				
				jsondata['newnotinstring'] = loadedpagehtmls[notinstring];
				loadnextpaginationpage(senddata,spinner,ismasonry,revsameheight,parentdiv,jsondata);
			} else {
				//console.log('callajax');
				ajaxcallforpagination(senddata,spinner,ismasonry,revsameheight,parentdiv);
			}
			
		}
		
		//ajax call for next pagination page clicked
		function ajaxcallforpagination(senddata,spinner,ismasonry,revsameheight,parentdiv){
			//console.log('ajax');
			//console.log(senddata);
			var jsondata = '';
			var jqxhr = jQuery.post(wprevpublicjs_script_vars.wpfb_ajaxurl, senddata, function (data){
					var IS_JSON = true;
					//strip everything outside of {}, workaround when wordpress generates and message
					var lastcurly = data.lastIndexOf("}");
					data = data.substring(0, lastcurly+1);
					try
					   {
							var jsondata = $.parseJSON(data);
					   }
					   catch(err)
					   {
						   console.log('jsonparse error with return html');
						   IS_JSON = false;
							spinner.hide();
					   }
					   
					if(data && data!="" && IS_JSON){
						//update notinstring
						//console.log(jsondata);
						$(parentdiv).attr('data-notinstring',jsondata.newnotinstring);
						loadnextpaginationpage(senddata,spinner,ismasonry,revsameheight,parentdiv,jsondata);
					}
				});
				jqxhr.fail(function() {
					  //display error message
						console.log("fail");
						spinner.hide();
						var theimagetemp = $('.wprevflashingimage');
						flashImage(theimagetemp, 'stop');
						return;
				});
		}
		//actually going to build pagination page here
		function loadnextpaginationpage(senddata,spinner,ismasonry,revsameheight,parentdiv,jsondata){
			//console.log('load from save');
			var paginationhtml = '';
			if(jsondata!=''){
				var innerrevhtml = jsondata.innerhtml;
				var newnotinstring = jsondata.newnotinstring;
				
				//if this is button then also update it
				$(parentdiv).find('.wprevpro_load_more_btn').attr('data-notinstring',newnotinstring);
						
				//console.log(jsondata);

				//save in case we want to quick load, only save if 
				var property = 'tid'+senddata.revid+'p'+senddata.clickedpnum+'t'+senddata.textsearch+senddata.textsort+senddata.textrating+senddata.textlang+senddata.textrtype;
				var notinstring = property+'-notinstr';
				var lcpn = property+'-lcpn';
				var lsn = property+'-lsn';
				var ttrindb = property+'-ttrindb';
				var trevppage = property+'-trevppage';
				
				loadedpagehtmls[property]=innerrevhtml;
				loadedpagehtmls[notinstring]=newnotinstring;
				
				loadedpagehtmls[lcpn]=jsondata.clickedpnum;
				loadedpagehtmls[lsn]=jsondata.lastslidenum;
				loadedpagehtmls[ttrindb]=jsondata.totalreviewsindb;
				loadedpagehtmls[trevppage]=jsondata.reviewsperpage;


				$('.wprppagination_loading_image_search').hide();
				$('.wprppagination_loading_image_tag').hide();
				$('.wprppagination_rtypes_loading_img').hide();
				
				//console.log(loadedpagehtmls);
				var thisrevtempl = $(parentdiv).closest( '.wprevpro' );
				//replace the reviews, different if ismasonry
				if(ismasonry=='yes'){
					
					 $(parentdiv).prevAll( "div.wprs_masonry_js" ).fadeOut(200).promise().done(function(){
						 $(parentdiv).prevAll( "div.wprs_masonry_js" ).html('');
						 $(parentdiv).prevAll( "div.wprs_masonry_js" ).append(innerrevhtml);
						 $(parentdiv).prevAll( "div.wprs_masonry_js" ).fadeIn(200).promise().done(function(){
							 setreadmoreupgo(thisrevtempl);
							if(revsameheight=='yes'){
							checkfixheightgrid(parentdiv);
							}
							turnonmasonry();
						 });

					 });

				} else {
					$(parentdiv).prevAll( "div.wprevprodiv" ).fadeOut(200).promise().done(function(){
						 $(parentdiv).prevAll( "div.wprevprodiv" ).remove();
						 $(parentdiv).closest("div.wprevpro").prepend(innerrevhtml);
						 $(parentdiv).prevAll( "div.wprevprodiv" ).fadeIn(200).promise().done(function(){
							setreadmoreupgo(thisrevtempl);
						 });
						 if(revsameheight=='yes'){
							checkfixheightgrid(parentdiv);
						}
					 });
				 
				}
				
				lastclickedpagenum = Number(jsondata.clickedpnum);
				var lastslidenum = Number(jsondata.lastslidenum);
				var temptotalrevsindb = Number(jsondata.totalreviewsindb);
				var tempreviewsperpage = Number(jsondata.reviewsperpage);
				
				//update the html data so we can pull correct number again.
				//$(parentdiv).attr('data-lastslidenum',lastslidenum);
				//$(parentdiv).attr('data-totalreviewsindb',temptotalrevsindb);
				
				var chtml = '';
				spinner.hide();
				var theimagetemp = $('.wprevflashingimage');
				flashImage(theimagetemp, 'stop');

				
				//scroll to top of reviews on mobile only or if viewport smaller than div height
				if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) || $(parentdiv).closest("div.wprevpro").height()>window.innerHeight) {
					var sliderid = $(parentdiv).closest("div.wprevpro");
					var offset = sliderid.offset();
					$('html, body').animate({
					scrollTop: offset.top-75
					}, 'slow');
				}

				//redraw pagination buttons, only if we don't see all reviews on this page and we are using pagination not load more btn
				if(spinner.length){
					//console.log('redraw pagination');
					//console.log(loadedpaginationdiv);
					if( loadedpaginationdiv[property] ) {
						paginationhtml = loadedpaginationdiv[property];
					} else {
						paginationhtml = '<ul class="wppro_page_numbers_ul">';
						if(temptotalrevsindb > tempreviewsperpage){
							
							if(lastclickedpagenum>1){
								paginationhtml =  paginationhtml +'<li><span class="brnprevclass wppro_page_numbers prev-button"><</span></li>';
							}
							if(lastclickedpagenum==1){
								chtml ='current';
							}
							//always add first page
							paginationhtml =  paginationhtml +'<li><span class="brnprevclass wppro_page_numbers '+chtml+'">1</span></li>';
							//add dots if needed to start
							if(lastclickedpagenum>3){
								paginationhtml =  paginationhtml +'<li><span class="brnprevclass wppro_page_dots">…</span></li>';
							}
							
							for (var i = 2; i < lastslidenum; i++) {
								chtml = '';							
								if(i==lastclickedpagenum){
									chtml ='current';
								}
								if(i-1==lastclickedpagenum || i+1==lastclickedpagenum || i==lastclickedpagenum){
								paginationhtml = paginationhtml +'<li><span class="brnprevclass wppro_page_numbers '+chtml+'">'+i+'</span></li>';
								}
							}
							//add dots if needed to end
							if((lastslidenum-lastclickedpagenum)>2){
								paginationhtml =  paginationhtml +'<li><span class="brnprevclass wppro_page_dots">…</span></li>';
							}
							//always add last page
							chtml ='';
							if(lastslidenum==lastclickedpagenum){
								chtml ='current';
							}
							
							paginationhtml =  paginationhtml +'<li><span class="brnprevclass wppro_page_numbers '+chtml+'">'+lastslidenum+'</span></li>';
							
							if(lastclickedpagenum!=lastslidenum){
								paginationhtml =  paginationhtml +'<li><span class="brnprevclass wppro_page_numbers next-button">></span></li>';
							}
						}
						paginationhtml = paginationhtml +'</ul>'+spinner.get(0).outerHTML;
						
						//save so we can reuse
						loadedpaginationdiv[property]=paginationhtml;
					}
					
					//console.log(spinner);
					
					//need to do a remove add instead of re
					$(parentdiv).html( paginationhtml );
				} else {
					//this must be a button instead of pagination, see if we should hide load more
					if(temptotalrevsindb <= tempreviewsperpage){
						//hide button
						$(parentdiv).find('.wprevpro_load_more_btn').hide();
					} else {
						//show button unless force hide is on. It will have class forceloadmorehide
						if(!$(parentdiv).find('.wprevpro_load_more_btn').hasClass('forceloadmorehide')){
							$(parentdiv).find('.wprevpro_load_more_btn').show();
							//$(parentdiv).find('.wprevpro_load_more_btn').show();
						}
						
					}
				}
	

			} else {
				//console.log(data);
				spinner.hide();
				var theimagetemp = $('.wprevflashingimage');
				flashImage(theimagetemp, 'stop');
			}
			
		}
			

		//for load more btn click, ajax more reviews and add to html
		$( ".wprevpro_load_more_btn" ).on("click",function(event) {
			//trigger read less
			$(this).closest('.wprevpro').find('.wprs_rd_less:visible').trigger('click');
			
			//console.log('load more btn click');
			loadmorerevs(this,'');
		});
		var currentlyloading = false;	//need to check this and wait if this is true so we don't load twice.
		
		function loadmorerevs(thebtn,thisslick,filterbar='no',thisregslider='') {
			if(currentlyloading === true) {
			   setTimeout(loadmorerevs, 1000, thebtn, thisslick,filterbar,thisregslider); /* this checks the flag every 100 milliseconds*/
			} else {
			  /* do something*/
			  loadmorerevscontinue(thebtn,thisslick,filterbar,thisregslider);
			}
		}
		
		//console.log(wprevpublicjs_script_vars.wpfb_ajaxurl);
		function loadmorerevscontinue(thebtn,thisslick,filterbar='no',thisregslider=''){
//console.log('loadmorerevscontinue');
			currentlyloading = true;
			//console.log('here2');
			//console.log(thebtn);
			//get number of review rows and per a row, use this number for offset call to db
			var spinner = $(thebtn).next( ".wprploadmore_loading_image" );
			var theimagetemp = $('.wprevflashingimage');
			var loadbtn = $(thebtn);
			
			//console.log(loadbtn);
			//var templateiddiv = $(thebtn).closest('div');
			var numperrow = $(thebtn).attr('data-perrow');
			var numrows = $(thebtn).attr('data-nrows');
			var cnum = $(thebtn).attr('data-callnum');	//reset this if we are clicking on or searching for something.
			var revtemplateid = $(thebtn).attr('data-tid');
			
			var notinstr = $(thebtn).attr('data-notinstring');
			//console.log('sentnotinstr:'+notinstr);
			
			var cpostid = $(thebtn).attr('data-cpostid');
			var shortcodepageid = $(thebtn).attr('data-shortcodepageid');
			var shortcodelang = $(thebtn).attr('data-shortcodelang');
			var shortcodetag = $(thebtn).attr('data-shortcodetag');
			
			if (/Mobi|Android/i.test(navigator.userAgent) || $(window).width()<600) {
				var oneonmobile = $(thebtn).attr( "data-onemobil" );
			} else {
				var oneonmobile = 'no';
			}
			
			var isslider = $(thebtn).attr('data-slideshow');
			var ismasonry = $(thebtn).attr('data-ismasonry');
			
			//see if we have search text
			var searchtext = $(thebtn).closest(".wprevpro").prev('.wprev_search_sort_bar').find('.wprev_search').val();
			if(isslider=='yes'){
				//for regular slider
				searchtext = $(thebtn).closest('.wprs_unslider').prev('.wprev_search_sort_bar').find('.wprev_search').val();
			}
			//override searchtext if we clicked a tag
			if($('.wprevpro_stag.current').text()!=''){
				searchtext = $('.wprevpro_stag.current').text();
				//console.log('searchtexttag:'+searchtext);
			}
			
			//see if we are overriding default sort
			var sorttext = $(thebtn).closest('.wprevpro').prev('.wprev_search_sort_bar').find('#wprevpro_header_sort').val();
			if(isslider=='yes'){
				sorttext = $(thebtn).closest('.wprs_unslider').prev('.wprev_search_sort_bar').find('#wprevpro_header_sort').val();
			}
			//see if we have a rating specified
			var ratingfilter = $(thebtn).closest('.wprevpro').prev('.wprev_search_sort_bar').find('#wprevpro_header_rating').val();
			if(isslider=='yes'){
				ratingfilter = $(thebtn).closest('.wprs_unslider').prev('.wprev_search_sort_bar').find('#wprevpro_header_rating').val();
			}
			//see if we have a source specified
			var sourcefilter = $(thebtn).closest('.wprevpro').prev('.wprev_search_sort_bar').find('#wprevpro_header_source').val();
			if(isslider=='yes'){
				sourcefilter = $(thebtn).closest('.wprs_unslider').prev('.wprev_search_sort_bar').find('#wprevpro_header_source').val();
			}
			
			//see if we have a language specified
			var langfilter = $(thebtn).closest('.wprevpro').prev('.wprev_search_sort_bar').find('#wprevpro_header_langcodes').val();
			if(isslider=='yes'){
				langfilter = $(thebtn).closest('.wprs_unslider').prev('.wprev_search_sort_bar').find('#wprevpro_header_langcodes').val();
			}
			
			//see if we are specified review type. $(this).parent().attr("data-rtype","");
			//var rtype = $(thebtn).closest('.wprevpro').prev('.wprev_search_sort_bar').find('.wprevpro_rtypes_div').attr("data-rtype");
			//if(isslider=='yes'){
			//	rtype = $(thebtn).closest('.wprs_unslider').prev('.wprev_search_sort_bar').find('.wprevpro_rtypes_div').attr("data-rtype");
			//}
			
			var rtype = '';
			if($('#wprev_search_sort_bar_id_'+revtemplateid).find('.wprevpro_rtypes_div').attr("data-rtype")){
			rtype = $('#wprev_search_sort_bar_id_'+revtemplateid).find('.wprevpro_rtypes_div').attr("data-rtype");
			}

			//see if we are clicking on banner type. wprev_banner_top
			if($('#wprev_banner_id_'+revtemplateid).find('.wprev_banner_top').attr("data-rtype")){
				//console.log('hereclick');
				rtype = $('#wprev_banner_id_'+revtemplateid).find('.wprev_banner_top').attr("data-rtype");
			}

			
			if(thisslick==''){
			spinner.show();
			}
			loadbtn.hide();

			//make ajax call
			 var senddata = {
				action: 'wprp_load_more_revs',	//required
				wpfb_nonce: wprevpublicjs_script_vars.wpfb_nonce,
				cache: false,
				processData : false,
				contentType : false,
				perrow: numperrow,
				nrows: numrows,
				callnum: cnum,
				notinstring:notinstr,
				revid: revtemplateid,
				onereview: oneonmobile,
				cpostid: cpostid,
				shortcodepageid: shortcodepageid,
				shortcodelang: shortcodelang,
				shortcodetag: shortcodetag,
				textsearch: searchtext,
				textsort: sorttext,
				textrating: ratingfilter,
				textsource: sourcefilter,
				textlang: langfilter,
				filterbar: filterbar,
				textrtype: rtype,
				};
				//console.log(senddata);
						
				//send to ajax to update db
				var jqxhr = jQuery.post(wprevpublicjs_script_vars.wpfb_ajaxurl, senddata, function (data){
					var IS_JSON = true;
					//console.log(data);
					//strip everything outside of {}, workaround when wordpress generates and message
					//data = data.substring(0, data.indexOf('}')+1);
					try
					   {
							var jsondata = $.parseJSON(data);
					   }
					   catch(err)
					   {
						   IS_JSON = false;
							spinner.hide();
							flashImage(theimagetemp, 'stop');
					   }  
					if(data && data!="" && IS_JSON){

						//console.log(jsondata);
						var innerrevhtml = jsondata.innerhtml;
						var numreviews = jsondata.totalreviewsnum;
						var hideldbtn = jsondata.hideldbtn;
						var animateheight = jsondata.animateheight;
						
						var newnotinstring = jsondata.newnotinstring;
						//console.log("newnotinstring:"+newnotinstring);
						loadbtn.attr('data-notinstring',newnotinstring);

						//console.log('isslider:'+isslider);
						//console.log('filterbar:'+filterbar);
						//console.log('ismasonry:'+ismasonry);
						flashImage(theimagetemp, 'stop');

						//add to page
						if(isslider=='yes'){
							if(filterbar=='yes'){
								//console.log('filterbar:yes');
								//console.log(innerrevhtml);
								$('.wprppagination_loading_image_search').hide();
								$('.wprppagination_loading_image_tag').hide();
								$('.wprppagination_rtypes_loading_img').hide();
								
								var ulparent = loadbtn.parent('.wprevpro_load_more_div').closest('.wprs_unslider-wrap');
								var divtomove = loadbtn.parent('.wprevpro_load_more_div').closest('li');
								//replace the html in first li and make active
								ulparent.html('<li class="wprs_unslider-active">'+innerrevhtml+'</li>');
								ulparent.append(divtomove);

								savedsliders[revtemplateid].data("wprs_unslider").calculateSlides();
								//redo nav
								$("#wprev-slider-"+revtemplateid).siblings("nav").remove();
								savedsliders[revtemplateid].data("wprs_unslider").initNav();
								$("#wprev-slider-"+revtemplateid).siblings("nav").attr("id","wprs_nav_"+revtemplateid);
								savedsliders[revtemplateid].data("wprs_unslider").animate("first");

							} else {
								//if(innerrevhtml!=''){
									//console.log('filterbar:');
									//console.log(savedsliders);
									$("#wprev-slider-"+revtemplateid).find( "ul" ).append("<li></li>");
									thisregslider.data("wprs_unslider").calculateSlides();
									
									$("#wprev-slider-"+revtemplateid).siblings("nav").remove();
									thisregslider.data("wprs_unslider").initNav();
									$("#wprev-slider-"+revtemplateid).siblings("nav").find( "li" ).last().prev().prev().addClass("wprs_unslider-active");
									
									//add to btn slide
									loadbtn.parent('.wprevpro_load_more_div').before( innerrevhtml );
									
									if(hideldbtn!='yes'){
										//console.log('move btn slide to end:');
										//move btn slide to end
										var tempul = loadbtn.closest('li').next('li');
										var divtomove = loadbtn.parent('.wprevpro_load_more_div');
										divtomove.detach();
										tempul.append(divtomove);
									} else {
										//console.log('remove last li');
										loadbtn.closest('.wprs_unslider').find( "ol li:last").remove();
									}
								//}
								spinner.hide();

							}
							//update slide height here if animateheight is true
							if(animateheight=='yes'){
								var liheight = $(thebtn ).closest('li').prev('li').css("height");
								$(thebtn ).closest( '.wprev-slider' ).animate({height: liheight,}, 750 );
								$(thebtn ).closest( '.wprev-slider-widget' ).animate({height: liheight,}, 750 );
							}
							//check to see if fixheight is set
							var revsameheight = $(thebtn).attr( "data-revsameheight" );
							//console.log('revsameheight:'+revsameheight);
							if(revsameheight=='yes'){
								checkfixheightslider(thebtn);
							}
							
						} else if(isslider=='sli'){
							okaytoloadnextslickslide = true;
							//console.log(innerrevhtml);
							//add innerhtml to the slider using the addslide method
							//if(numreviews>0){
								//destroy and rebuild if this is a filter bar change
								if(filterbar=='yes'){
									$('.wprppagination_loading_image_search').hide();
									$('.wprppagination_loading_image_tag').hide();
									$('.wprppagination_rtypes_loading_img').hide();
									//destroy
									$(thisslick).slickwprev('unslickwprev');
									//replace the html
									$(thisslick).html( innerrevhtml);
									//rebuild slick slider
									createaslick(thisslick);
								} else {
									if(numreviews>0){
									$(thisslick).slickwprev('slickwprevAdd',innerrevhtml);
									var slideprops = $(thisslick).attr( "data-slickwprev" );
									var slidepropsobj = JSON.parse(slideprops);
									//fix transition if this is a fade
									if(slidepropsobj.speed==0){
										//console.log(slidepropsobj);
										$(thisslick).find(".slickwprev-active").css("transition-duration", "0.5s");
									}
									}
								}
								if(numreviews>0){
								//if we are setting same height then we need to do it again.
								var revsameheight = $(thisslick).attr( "data-revsameheight" );		
								if(revsameheight=='yes'){
									setTimeout (() => { 
										fun_revsameheight(thisslick);
										}, 10);
								}
								var wprevmasonry = $(thisslick).attr( "data-wprevmasonry" );
								if(wprevmasonry!='yes'){
									setTimeout (() => { 
											fun_fixheightsliajax(thisslick);
										}, 20);
									}
								}
							//}
							
						} else {
							if(ismasonry=='yes'){
								loadbtn.parent('.wprevpro_load_more_div').prev('.wprs_masonry_js').append( innerrevhtml );
								turnonmasonry();
							} else {
								loadbtn.parent('.wprevpro_load_more_div').before( innerrevhtml );
							}
							spinner.hide();
							if(numreviews>0){
								loadbtn.show();
							}
							if(hideldbtn=='yes'){
								loadbtn.hide();
							}
							var revsameheight = $(thebtn).attr( "data-revsameheight" );
							//console.log('revsameheight:'+revsameheight);
							if(revsameheight=='yes'){
								checkfixheightgrid(thebtn);
							}
							
						}
						//update btn attribute callnum
						var newcallnum = Number(jsondata.callnum) +1;
						loadbtn.attr('data-callnum',newcallnum);
						loadbtn.attr('hideldbtn',hideldbtn);
						
						//fix readmore hideshow.
						var thisrevtempl = loadbtn.closest( '.wprevpro' );
						setTimeout (() => {setreadmoreupgo(thisrevtempl);}, 50);
						

					} else {
						//console.log(data);
						spinner.hide();
					}
					currentlyloading = false;
				});
				jqxhr.fail(function() {
					  //display error message
						console.log("fail");
						spinner.hide();
						loadbtn.show();
						flashImage(theimagetemp, 'stop');
						currentlyloading = false;
				});
			
		}
		
		//check if grid needs same height set, not in pop-up or float
		$( ".wprevpro.wprev-no-slider" ).each(function( index ) {
			if(!$( this ).hasClass('revnotsameheight') && !$(this).closest(".wprevmodal_modal").length && !$(this).closest(".wprevpro_float_outer").length ){
				//set revs same height
				setTimeout(() => { checkfixheightgrid($( this ).find('div')) }, 100);
				//checkfixheightgrid($( this ).find('div'));
			}
		});
						
		//when loading more for grid via button or page then we set height
		function checkfixheightgrid(thebtn){
			//first make sure this is visible.
			if($(thebtn).closest( '.wprevpro' ).is(":visible")){
				var maxheights = $(thebtn).closest( '.wprevpro' ).find(".indrevdiv").map(function (){return $(this).outerHeight();}).get();
				var maxHeightofgrid = Math.max.apply(null, maxheights);
				$(thebtn).closest( '.wprevpro' ).find(".indrevdiv").css( "height", maxHeightofgrid );
				//unset height if indrevdiv is in read more pop-up
				$(thebtn).closest( '.wprevpro' ).find(".wprevmodal_modal_rdmore").find(".indrevdiv").css( "height", '' );
			}
		}
		
		//when loading more, check to see if we are fixing the height, if so then set the height here
		function checkfixheightslider(thebtn){
			//wprs_unslider
			var maxheights = $(thebtn).closest( '.wprs_unslider' ).find(".indrevdiv").map(function (){
				if($(this).find('.wprs_rd_less').is(":visible")) { 
					//console.log("read less visible3");
				} else { 
					//console.log("read less hidden3");
					return $(this).outerHeight();
				}
				}).get();
			var maxHeightofslide = Math.max.apply(null, maxheights);
			//$(thebtn).closest( '.wprs_unslider' ).find(".indrevdiv").css( "height", maxHeightofslide );
			$(thebtn).closest( '.wprs_unslider' ).find(".indrevdiv").each(function(){
				if($(this).find('.wprs_rd_less').is(":hidden")) {
					$(this).css( "height", maxHeightofslide );
				}
			});
			
			//fix if the new height is bigger than overallheight
			//var liheight = $(thebtn ).closest( 'li' ).prevAll( '.wprs_unslider-active' ).outerHeight();
			//find max height of all slides
			//var heights = $(thebtn ).closest('.wprs_unslider').find( "li" ).map(function (){
			//				return $(this).outerHeight();
			//			}).get();
			//var overallheight = Math.max.apply(null, heights);
						
			//if(liheight>overallheight){
				//$(thebtn ).closest( '.wprevpro' ).animate({height: liheight,}, 200 );
			//} else {
				//$(thebtn ).closest( '.wprevpro' ).animate({height: overallheight,}, 200 );
			//}
		}
		
		//for closing float on click
		$( ".wprev_pro_float_outerdiv-close" ).on("click",function(event) {
			$(this).closest('.wprevpro_float_outer').hide('300');
			//add to session storage so we don't show on page reload
			//sessionStorage.setItem('wprevpro_clickedclose', 'yes');
			var floatid = $(this).attr('id');
			
			//need to grab current settings first
			var wprevfloats = JSON.parse(sessionStorage.getItem("wprevfloats") || "[]");
			wprevfloats.push({id: floatid, clickedclose: "yes"});
			
			sessionStorage.setItem("wprevfloats", JSON.stringify(wprevfloats));
			//var clickedclose = sessionStorage.getItem('wprevpro_clickedclose');
		});
		
		//check to see if sessionStorage holds a clicked x then hide if so.
		//var hiddenfloats=[];
		checksession();
		function checksession(){
			//initially show all floats here
			$("div.wprevpro_float_outer").show();
			//check to see if any floats need to be hidden
			var wprevfloats = JSON.parse(sessionStorage.getItem("wprevfloats") || "[]");
			wprevfloats.forEach(function(wprevfloat, index) {
				if(wprevfloat.clickedclose=='yes' || wprevfloat.firstvisithide =='yes'){
					//hide the float here
					$( "#"+wprevfloat.id ).closest('.wprevpro_float_outer').hide();
				}
				//console.log("[" + index + "]: " + wprevfloat.id);
			});
			//update the storage if we are only displaying on first visit here
			$("div.wprevpro_badge_container[data-firstvisit='yes']").each(function( index ) {
				var floatid = $(this).find('.wprev_pro_float_outerdiv-close').attr('id');
				var floatsaved = false;
				//only set if not set before
				var filtered=wprevfloats.filter(function(item){
					return item.firstvisithide=="yes" && item.id == floatid;        
				});
				if (filtered == false) {
					wprevfloats.push({id: floatid, firstvisithide: "yes"});
					sessionStorage.setItem("wprevfloats", JSON.stringify(wprevfloats));
				}
			});
		}
		
		//check to see if we are flying this float in and delay wprevpro_float_outer
		var wprev_popshowtime = 8000; //show review popin for 8 seconds
		var wprev_pophidetime = 6000; //hide review popin for 6 seconds
		var wprev_popnumber = 1;
		var wprev_poptotalpops = 50;	//total numer of pop-ins, force to 10 if we have time set
		var pageisvisible = true;
		var mouseisover = false;
		
		//listening for visibility change
		document.addEventListener("visibilitychange", handleVisibilityChange, false);
		function handleVisibilityChange() {
		  if (document.hidden) {
			  //console.log('hidden');
			pageisvisible = false;
		  } else  {
			  //console.log('shown');
			pageisvisible = true;
		  }
		}
		//listening for mouseover and mouseout
		$('div .wprev_pop_contain').on( "mouseenter",function(){
			 mouseisover = true;
		}).on( "mouseleave",function() {
			 mouseisover = false;
		});
	
		runfloatfunctions();
		function runfloatfunctions(){
			$(".wprevpro_float_outer").each(function() {
				var currentfloatid = $(this).attr('id');
				//get variables to see if we fly in or fade in
				var animatedir = $(this).find('.wprevpro_badge_container').attr('data-animatedir');
				var animatedelay = Number($(this).find(".wprevpro_badge_container").attr('data-animatedelay'))*1000;
				var floatdiv = $(this).find(".wprev_pro_float_outerdiv");
				
				//first we need to move it out
				slideinoutfloat(floatdiv,animatedir,'out',0);
				
				//slide or fade in the float
				if(animatedelay>0){
					setTimeout(function(){ slideinoutfloat(floatdiv,animatedir,'in',1000); }, animatedelay);
				} else {
					slideinoutfloat(floatdiv,animatedir,'in',1000);
				}

				//check to see if we are auto-closing this float
				var autoclose = $(this).find(".wprevpro_badge_container").attr('data-autoclose');
				var autoclosedelay = Number($(this).find(".wprevpro_badge_container").attr('data-autoclosedelay'))*1000 + animatedelay;
				if(autoclose=='yes' && autoclosedelay>0){
					setTimeout(function(){ 
					$(floatdiv).hide(); 
					wprev_popnumber = wprev_poptotalpops;	//end the loop
					}, autoclosedelay);
				}
				
				//check if we have any float review pops on the page. 
				if($(this).find(".wprevpro_outerrevdivpop").length){
					var thispopdiv = $(this).find(".wprevpro_outerrevdivpop");
					var firstdelay = animatedelay + wprev_popshowtime;
					//this will hide it and then loop it
					var myPopVar = setTimeout(function() { hideandload(floatdiv,thispopdiv,animatedir)}, firstdelay);
				}

			});
		}
		//does the actual sliding of the float
		function slideinoutfloat(floatdiv,animatedir,inorout,transtime){
			if(pageisvisible && !mouseisover){
			var startcssval;
			if(inorout=='in'){
				$(floatdiv).show();
			}
			if(animatedir=='right'){
				if(inorout=='in'){
					//fly this in from the right of the page
					$(floatdiv).animate({right: "10px"}, 1000 );
				} else if(inorout=='out'){
					$(floatdiv).animate({right: "-110%"}, 1000 );
				}
			} else if(animatedir=='bottom'){
				if(inorout=='in'){
					$(floatdiv).animate({bottom: "10px"}, 1000 );
				} else if(inorout=='out'){
					//already on page we need to animate off of page.
					$(floatdiv).animate({bottom: "-1000px"}, 1000 );
				}
			} else if(animatedir=='left'){
				if(inorout=='in'){
					//fly this in from the left of the page
					$(floatdiv).animate({left: "10px"}, 1000 );
				} else if(inorout=='out'){
					//already on page we need to animate off of page.
					$(floatdiv).animate({left: "-110%"}, 1000 );
				}
			} else if(animatedir=='fade'){
				if(inorout=='in'){
					//fade this in the page
					$(floatdiv).animate({opacity: 1}, 500 );
				} else if(inorout=='out'){
					//already on page we need to animate off of page.
					$(floatdiv).animate({opacity: "0"}, 500 );
				}
			}
			//wprev_popnumber = 1, then we check to see if we need to fix this advanced slider
			if($(floatdiv).find('.slickwprev-active').length > 0 && $(floatdiv).find('.slickwprev-active').width()==0){
				var thisslick = $(floatdiv).find('.wprevgoslick');
				$(thisslick).slickwprev('slickwprevGoTo',0);
			}
			}
		}

		var startoffset = 1;
		function hideandload(floatdiv,thispopdiv,animatedir) {
			//console.log('wprev_popnumber:'+wprev_popnumber);
			//console.log('wprev_poptotalpops:'+wprev_poptotalpops);
			var missedslideout = false;
			//console.log('hideload2');
			//slide or fade the float out so we can replace html, only if mouse is not overallheight
			if(pageisvisible && !mouseisover){
				slideinoutfloat(floatdiv,animatedir,'out',1000);
				missedslideout = false;
			} else {
				missedslideout = true;
			}
				wprev_popnumber = wprev_popnumber + 1;

				var formid = $(thispopdiv).attr("data-formid");
				var wtfloatid = $(thispopdiv).attr("data-wtfloatid");
				
				//load next slide
					var senddata = {
					action: 'wprp_get_float',	//required
					wpfb_nonce: wprevpublicjs_script_vars.wpfb_nonce,
					fid: formid,
					wtfid: wtfloatid,
					wtftype: 'pop',
					innerdivonly: 'yes',
					startoffset: startoffset
					};
					//console.log(senddata);
				//send to ajax to update db
				var jqxhr = jQuery.post(wprevpublicjs_script_vars.wpfb_ajaxurl, senddata, function (response){
					//console.log(response);
					//console.log(response.length);
					startoffset = startoffset +1;
					if (!$.trim(response) || response.length<100){
						//console.log('unable to find next pop review');
						wprev_popnumber = wprev_poptotalpops;	//end the loop
					} else {
						if(wprev_popnumber < wprev_poptotalpops){
							//remove current pop contents and add new
							if(pageisvisible && !mouseisover && missedslideout==false){
							$(floatdiv).find('.wprev_pop_contain').html('');
							$(floatdiv).find('.wprev_pop_contain').html(response);
							}
							//console.log(wprev_pophidetime);
							//console.log(wprev_popshowtime);
							//now add delay and re-show
							setTimeout(function(){
								slideinoutfloat(floatdiv,animatedir,'in',1000);
							}, wprev_pophidetime);
							var showdelay = wprev_pophidetime + wprev_popshowtime;
							setTimeout(function() {
								hideandload(floatdiv,thispopdiv,animatedir)
							}, showdelay);
							//setreadmore if needed.
							setreadmoreupgo(floatdiv);
						}
						
					}
				});
			
			
		}
		
		//$(".indrevtextscroll").each(function(){
		
		//check to see if we need to add masonry, delay so CSS can setup first
		setTimeout(function(){ 
			turnonmasonry(); 
		}, 200);
		function turnonmasonry(){
			setTimeout(function(){
				
				$(".wprevpro").find(".wprs_masonry_js").each(function( index ) {
					turnonmasonrygo(this); 
				});
				$(".wprevpro").find(".wprs_masonry_js").fadeTo( "fast", 1 );
				
			}, 200);
		}
		
		
		function turnonmasonrygo(thistemplate){
			//make sure this is visible.
			var isvis = $(thistemplate).is(":visible");
			if(!isvis){
				//start loop until becomes visible.
				setTimeout(function(){
					turnonmasonrygo(thistemplate);
				},1000);
				return false;
			}
			
					var numcol = parseInt($(thistemplate).attr( "data-numcol" ));
					var contwidth = parseInt($(thistemplate).closest('.wprevpro').width());
					var blockwidth = parseInt(contwidth/numcol)-30;
					//fix for small screens
					if(blockwidth<200){
						blockwidth = 200;
					}
					var masonryid = $(thistemplate).closest('.wprevpro').attr('id');
					if(numcol>0 && contwidth >0){
						masonryobj[masonryid] = new MiniMasonry({
							container: '#'+masonryid+" .wprs_masonry_js",
							minify: true,
							gutterX: 20,
							gutterY: 0,
							baseWidth: blockwidth
						});
						//if this has readmore setup again and then relayout masonry
						//show readmore
						if($(thistemplate).find('.divwprsrdmore').length){
							var thisrevtempl = $(thistemplate).closest( '.wprevpro' );
							setreadmoreupgo(thisrevtempl);
							masonryobj[masonryid].layout();
						}
						
						//add fix here for scrolling to anchor tag.
						var hash = $(location).attr('hash');
						if(hash){
							//find out if it is visible or not.
							var top_of_element = $(hash).offset().top;
							var bottom_of_element = $(hash).offset().top + $("#element").outerHeight();
							var bottom_of_screen = $(window).scrollTop() + $(window).innerHeight();
							var top_of_screen = $(window).scrollTop();

							if ((bottom_of_screen > top_of_element) && (top_of_screen < bottom_of_element)){
								// the element is visible, do something
							} else {
								// the element is not visible, do something else
								$('html, body').animate({
									scrollTop: $(hash).offset().top
								}, 1000);
							}
						}
					}
			//$(".wprevpro").find(".wprs_masonry_js").fadeTo( "fast", 1 );
			
		}
		
		
		function fun_revsameheight(thisslick){
					var maxheights = $(thisslick).find(".indrevdiv").map(function (){
						//skip if read less is visible.
						if($(this).find('.wprs_rd_less').is(":visible")) { 
							//console.log("read less visible");
						} else { 
							//console.log("read less hidden");
							return $(this).outerHeight();
						} 
						}).get();
					//console.log(maxheights);
					var maxHeightofslide = Math.max.apply(null, maxheights);
					//console.log(maxHeightofslide);
					if(maxHeightofslide>0){
						$(thisslick).find(".indrevdiv").css( "min-height", maxHeightofslide );
					}
		}
		function fun_fixheightsliajax(thisslick){
					var maxheights = $(thisslick).find(".w3_wprs-col").map(function (){
						if($(this).find('.wprs_rd_less').is(":visible")) { 
							//console.log("read less visible2");
						} else { 
							//console.log("read less hidden2");
							return $(this).outerHeight();
						} 
						}).get();
					//console.log(maxheights);
					var maxHeightofslide = Math.max.apply(null, maxheights);
					//console.log(maxHeightofslide);
					if(maxHeightofslide>0){
						$(thisslick).find(".w3_wprs-col").css( "min-height", maxHeightofslide );
					}
		}
		
		//adding creating of reg slide v11.6.2=========
		
		//check to see if we need to create slider;
		var savedsliders={};
		$( ".wprev-slider" ).each(function( index ) {
			createaslider(this,'shortcode');
		});
		$( ".wprev-slider-widget" ).each(function( index ) {
			createaslider(this,'widget');
		});
		function createaslider(thissliderdiv,type){
			var slideprops = $(thissliderdiv).attr( "data-slideprops" );
			var slidepropsobj = JSON.parse(slideprops);
			var sliderid = Number(slidepropsobj.sliderid);
			//console.log(slidepropsobj);

			//unhide other rows.
			$( thissliderdiv ).find('li').show();
			$( thissliderdiv ).show();
			var slider = $( thissliderdiv ).wprs_unslider(
					{
					autoplay:slidepropsobj.autoplay,
					infinite:false,
					delay: slidepropsobj.delay,
					speed: slidepropsobj.sliderspeed,
					animation: slidepropsobj.animation,
					arrows: slidepropsobj.arrows,
					animateHeight: slidepropsobj.animateHeight,
					activeClass: 'wprs_unslider-active',
					}
				);
			savedsliders[sliderid]=slider;
			//close read more on advance
			slider.on('wprs_unslider.change', function(event, index, slide) {
				$(thissliderdiv).find('.wprs_rd_less:visible').trigger('click');
			});

			//add id and class to nav
			$(thissliderdiv).siblings('.wprs_unslider-nav').attr( 'id','wprs_nav_'+sliderid);
			$(thissliderdiv).siblings('.wprs_unslider-arrow').addClass('wprs_nav_arrow_'+sliderid);
			//pause on hove if autoplay is true
			if(slidepropsobj.autoplay==true){
				slider.on('mouseover', function() {slider.data('wprs_unslider').stop();}).on('mouseout', function() {slider.data('wprs_unslider').start();});
			}
			
			//show readmore
			setreadmoreupgo(thissliderdiv);
			
			//force height if set
			if(slidepropsobj.forceheight=='yes'){
				var maxheights = $(thissliderdiv).find(".indrevdiv").map(function (){return $(this).outerHeight();}).get();
				var maxHeightofslide = Math.max.apply(null, maxheights);if(maxHeightofslide>0){$(thissliderdiv).find(".indrevdiv").css( "min-height", maxHeightofslide );}
			}
			//fix height animateHeight
			if(slidepropsobj.animateHeight==true){
				slider.data("wprs_unslider").animate("last");
				setTimeout(function(){slider.data("wprs_unslider").animate(0);}, 100);
			} else {
				//add fix for fade transition
				if(slidepropsobj.animation=='fade'){
					var heights = $("#wprev-slider-"+sliderid).find( "li" ).map(function (){return $(this).outerHeight();}).get(); 
					var maxHeight = Math.max.apply(null, heights);$("#wprev-slider-"+sliderid).height(maxHeight);
				}
			}
			//for making arrows not move
			if(slidepropsobj.sliderarrowheight=="yes"){
				var temparrow=$('#wprev-slider-'+sliderid).siblings('a.next,a.prev');
				var saoffset=temparrow.offset();
				if(saoffset.top>0){temparrow.offset({ top: saoffset.top});}
			}
			//add load more js if needed
			if(slidepropsobj.loadmorersli=="yes"){
				if(slidepropsobj.totalreviewsarray > slidepropsobj.reviewsperpage){
					
					var iswidgethtml = '';
					if(slidepropsobj.iswidget==true){
						iswidgethtml ="_widget";
					}
					
					savedsliders[sliderid].on("wprs_unslider.change", function(event, index, slide) {
						//console.log('slidechange');
						var loopnow = $("#wprev_load_more_btn_"+sliderid).attr("loopnow");
						if(loopnow!="yes"){
							
							var numslides = $("#wprev-slider-"+sliderid+iswidgethtml).find( "li" ).length;
							if(index==-1){index = numslides-1;}
							//console.log('numslides:'+numslides);
							//console.log('index:'+index);
							//console.log('sliderid:'+sliderid);
							//if((numslides-1)==index){addslide(index+1,numslides+1,sliderid);}
							//=======trying to go ahead and load two slides.
							if((numslides-2)==index){addslide(index+1,numslides+1,sliderid);}
						}
					});
					
					function addslide(index,numslides,formid){
						//console.log('ldmorebutn:'+formid);
						var ldmorebutn = $("#wprev_load_more_btn_"+formid);
						var hideldbtn = ldmorebutn.attr("hideldbtn");
						//console.log(ldmorebutn);
						//console.log('hideldbtn:'+hideldbtn);
						if(hideldbtn!="yes"){
							//console.log('loadmorerevs');

							loadmorerevs(ldmorebutn,'','lastslide',slider);
		
							//ldmorebutn.trigger("wprevlastslide");
							//$("#wprev-slider-"+formid+iswidgethtml).find( "ul" ).append("<li></li>");
							//slider.data("wprs_unslider").calculateSlides();
							//$("#wprev-slider-"+formid+iswidgethtml).siblings("nav").remove();
							//slider.data("wprs_unslider").initNav();
							//$("#wprev-slider-"+formid+iswidgethtml).siblings("nav").find( "li" ).last().prev().addClass("wprs_unslider-active");
						} else {
							//console.log('loopnow');
							ldmorebutn.attr("loopnow","yes");
							$("#wprev-slider-"+formid+iswidgethtml).find( "ul li:last").remove();
							slider.data("wprs_unslider").calculateSlides();
							$("#wprev-slider-"+formid+iswidgethtml).siblings("nav").remove();
							slider.data("wprs_unslider").initNav();
							$("#wprev-slider-"+formid+iswidgethtml).siblings("nav").find( "li" ).last().prev().addClass("wprs_unslider-active");
							setTimeout(function(){slider.data("wprs_unslider").animate(0);}, 100);
						}
					}

				}
			}
			//check for float delay
			if(slidepropsobj.checkfloatdelay>0){
				//go back to the first slide .5 seconds before
				setTimeout(function(){slider.data("wprs_unslider").animate(0);}, slidepropsobj.checkfloatdelay);
			}
				
		};
		
		
		//need a global variable to check if mouse is over div. used to make sure slick doesn't autoadvance on read more click.
		let isMouseHover = false
		$( ".wprevgoslick" )
		  .mouseover(function() {
			isMouseHover = true
			if (typeof slickwprev !== "undefined") {
				$(this).slickwprev('slickwprevPause');
			}
		  })
		  .mouseout(function() {
			isMouseHover = false
			//make sure this has autoplay turned on before we start it.
			var slideprops = $(this).attr( "data-slickwprev" );
			var slidepropsobj = JSON.parse(slideprops);
			if(slidepropsobj.autoplay==true){
				if (typeof slickwprev !== "undefined") {
					$(this).slickwprev('slickwprevPlay');
				}
			}
		  });

		//for slick slider, check to make sure there is a slick template on page first
		$( ".wprevgoslick" ).each(function( index ) {
			createaslick(this);
		});
		var okaytoloadnextslickslide = true;
		function createaslick(thisslickdiv){
			//console.log('making slick');
			//find the id of this and use it to create slick
			var thisid = $(thisslickdiv).attr('id');
			//console.log(thisid);
			//var thisslick = $( "#"+thisid );
			//change in version 11.0.9.7 so we can have same shortcode used twice on page.
			var thisslick = thisslickdiv;

			//show since hidden
			$(thisslickdiv).show();
			
			//make sure not in hidden div
			var isvis = $(thisslickdiv).is(":visible");
			if(!isvis){
				//start loop until becomes visible.
				$(thisslickdiv).hide();
				setTimeout(function(){
					createaslick(thisslickdiv);
				},1000);
				return false;
			}
			
			var revsameheight = $(thisslickdiv).attr( "data-revsameheight" );
			if(revsameheight=='yes'){
				setTimeout (() => { 
					fun_revsameheight(thisslick);
				}, 50);
			}

			//if we are doing more than one row and masonry turned off then set min-height of each reviews to largest review.
			var slideprops = $(thisslickdiv).attr( "data-slickwprev" );
			//console.log(slideprops);
			var slidepropsobj = JSON.parse(slideprops);
			//console.log(slidepropsobj);
			var displaymasonry = $(thisslickdiv).attr( "data-wprevmasonry" );
			//is this an avatar nav
			var isavatarnav = $(thisslickdiv).attr( "data-avatarnav" );
			var revsperrow = $(thisslickdiv).attr( "data-revsperrow" );
			
			
			if(slidepropsobj.rows > 1 && displaymasonry=='no'){
				var maxheights = $(thisslickdiv).find(".outerrevdiv").map(function (){return $(this).outerHeight();}).get();
				var maxHeightofrev = Math.max.apply(null, maxheights);
				//console.log(maxHeightofrev);
				if(revsperrow==true && revsperrow=="1"){
					//do nothing since one per a row.
				} else {
					$(thisslickdiv).find(".outerrevdiv").css( "min-height", maxHeightofrev );
				}
			}
			var centerpad = '50px';
			var centerpad2 = '50px';
			if(slidepropsobj.centerPadding && slidepropsobj.centerPadding!=''){
				if(slidepropsobj.centerMode && slidepropsobj.centerMode==true){
				centerpad2 = '100px';
				}
			}
			if(isavatarnav=='no' && slidepropsobj.slidesToShow >1){
				var options = {
			  		responsive: [
						{
						  breakpoint: 992,
						  settings: {
							slidesToShow: 2,
							slidesToScroll: 2
						  }
						},
						{
						  breakpoint: 600,
						  settings: {
							slidesToShow: 1,
							slidesToScroll: 1,
							centerPadding: centerpad
						  }
						}
					  ]
				};
			} else if(isavatarnav=='no' && slidepropsobj.slidesToShow <2){
				var options = {
			  		responsive: [
						{
						  breakpoint: 992,
						  settings: {
							slidesToShow: 1,
							slidesToScroll: 1,
							centerPadding: centerpad2
						  }
						},
						{
						  breakpoint: 600,
						  settings: {
							slidesToShow: 1,
							slidesToScroll: 1,
							centerPadding: centerpad
						  }
						}
					  ]
				};
			} else {

			}

			//console.log(options);
			//if adaptive height set to true and showing more than one review and only scrolling one we hack this
			if(slidepropsobj.adaptiveHeight==true && slidepropsobj.slidesToShow > 1){
				// my slick slider as constant object
				var mySlider = $(thisslick).on('init', function(slickwprev) {
				  // on init run our multi slide adaptive height function
				  wppromultiSlideAdaptiveHeight(thisslick);
				}).on('beforeChange', function(slickwprev, currentSlide, nextSlide) {
				  // on beforeChange run our multi slide adaptive height function
				  wppromultiSlideAdaptiveHeight(thisslick);
				}).slickwprev(options);
				$(thisslick).find('div[aria-hidden="true"]').find('a').attr("tabindex","-1");
			} else {
				$( thisslick ).slickwprev(options);
				$(thisslick).find('div[aria-hidden="true"]').find('a').attr("tabindex","-1");
			}
			
			//console.log('setreadmoreup');
			setreadmoreupgo(thisslickdiv);
		
			//if center mode true add CSS rules
			if(slidepropsobj.centerMode==true){
				$(thisslick).find('.slickwprev-slide').css("opacity", "0.5");
				$(thisslick).find('.slickwprev-center').css("opacity", "1");
				$(thisslick).on('beforeChange', function(slickwprev) {
					$(thisslick).find('.slickwprev-slide').fadeTo( 100, 0.5 );
				});
				$(thisslick).on('setPosition', function(slickwprev) {
					$(thisslick).find('.slickwprev-center').fadeTo( 100, 1 );
				});
			}
			//if this is avatarnav then need to add on click to change slides
			if(isavatarnav=='yes'){
				$(thisslick).find('.avataronlyrevdiv').on("click",function() {
				  var clickedslickindex = $(this).parent().parent().attr( "data-slickwprev-index" );
				  $(thisslick).slickwprev('slickwprevGoTo',clickedslickindex);
				});
			}

			//load another slide on second to last slide
			if($(thisslick).next('.wprevpro_load_more_div').length){
				//console.log('load more div found');
				$(thisslick).on('afterChange', function(slickwprev) {
					//find out total number of slides and if we are on second to last
					var currentslide = $(thisslick).slickwprev('slickwprevCurrentSlide');
					var totalslides = $(thisslick).find('.slickwprev-slide').length;
					var calctemp = currentslide + slidepropsobj.slidesToShow;
					//console.log(currentslide);
					//console.log(totalslides);
					//console.log('calctemp:'+calctemp);
					if(calctemp>=totalslides){
						//console.log('loadmore');
						//get load more button and send to function
						var thebutton = $(thisslick).next('.wprevpro_load_more_div').find('.wprevpro_load_more_btn');
						//turn off transition CSS in case we are fading it
						if(slidepropsobj.speed==0){
							//alert('here');
							$(thisslick).find(".slickwprev-active").css("transition-duration", "0s");
						}
						if(okaytoloadnextslickslide==true){
							okaytoloadnextslickslide = false;
							loadmorerevs(thebutton,thisslick,'lastslide');
						}

					}
				});
			}
			//before we switch slides close the read more
			$(thisslick).on('beforeChange', function(slickwprev) {
				//check to see if any are visible
				$(thisslick).find('.wprs_rd_less:visible').trigger('click');
			});
			//make sure read less is clicked after swipe
			$(thisslick).on('swipe', function(slickwprev) {
				readlesslineclamp($(thisslick).find('.wprs_rd_less:visible'));
			});
			//$(thisslick).on('afterChange', function(slickwprev) {
				//check to see if any are visible
			//	alert("swipe2");
			//	$(thisslick).find('.wprs_rd_less:visible').trigger('click');
			//});
			
			//fix the aria-hidden tab index error for screen readers. Add -1 tabindex to all a on hidden reviews
			$(thisslick).on('afterChange', function(slickwprev) {
				$(thisslick).find('div[aria-hidden="true"]').find('a').attr("tabindex","-1");
			});

		};
		
		// our multi slide adaptive height function passing slider object
		function wppromultiSlideAdaptiveHeight(slider) {
		  // set our vars
		  let activeSlides = [];
		  let tallestSlide = 0;
		  // very short delay in order for us get the correct active slides
		  setTimeout(function() {
			// loop through each active slide for our current slider
			$('.slickwprev-track .slickwprev-active', slider).each(function(item) {
			// add current active slide height to our active slides array
			activeSlides[item] = $(this).outerHeight();
			});
			// for each of the active slides heights
			activeSlides.forEach(function(item) {
			  // if current active slide height is greater than tallest slide height
			  if (item > tallestSlide) {
				// override tallest slide height to current active slide height
				tallestSlide = item + 15;
			  }
			});
			// set the current slider slick list height to current active tallest slide height
			//$('.slickwprev-list', slider).height(tallestSlide);
			$('.slickwprev-list', slider).animate({height: tallestSlide}, 500);
		  }, 15);

		}
		
		//going to search for media added to reviews and load lity if we find them.
		setTimeout(function(){ mediareviewpopup(); }, 500);
		function mediareviewpopup(){
			//search for masonry elements
			//var mediadiv = $(".wprevprodiv").find(".wprev_media_div");
			var mediadiv = $(".wprev_media_div");
			if(mediadiv.length){
				//console.log('mediadiv');
				//load js and css files.
				//console.log(wprevpublicjs_script_vars);
				$('<link/>', {
				   rel: 'stylesheet',
				   type: 'text/css',
				   href: wprevpublicjs_script_vars.wprevpluginsurl+"/public/css/lity.min.css?ver=2.4.10"
				}).appendTo('head');
				$.getScript(wprevpublicjs_script_vars.wprevpluginsurl+"/public/js/lity.min.js", function() {
					//script is loaded and ran on document root.
					//console.log('lity loaded');
				});
			}
		}

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
		
		//added for endless scroll option for grid, only works for one grid on page
		checkforendless();
		var loadingrevs = false;
		function checkforendless(){
			if($( ".wprevpro_load_more_btn[data-endless='yes']" ).first().length){
				//console.log('found endless');
				$( window ).scroll(function(e) {
					//console.log('scroll');
					var ldbtn = $( ".wprevpro_load_more_btn[data-endless='yes']" ).first();
					//find last row of reviews.
					var prediv = ldbtn.parent().prev();
					//find out if the button is in the viewport
					//console.log(prediv);
					var elementTop = prediv.offset().top;
					var elementBottom = elementTop + prediv.outerHeight();
					var viewportTop = $(window).scrollTop();
					var viewportBottom = viewportTop + $(window).height();
					//click the button if scrolled down.
					if(viewportBottom>(elementBottom+50) && loadingrevs==false){
						//close all read less
						//trigger read less first maybe
						$(ldbtn).closest('.wprevpro').find('.wprs_rd_less:visible').trigger('click');
						//console.log('click');
						loadmorerevs(ldbtn,'');
						loadingrevs=true;
						setTimeout(function(){ loadingrevs = false }, 1000);
					}
				});
			}
		}

	});

})( jQuery );



//masonry-------
var MiniMasonry = function(conf) {
    this._sizes             = [];
    this._columns           = [];
    this._container         = null;
    this._count             = null;
    this._width             = 0;
    this._removeListener    = null;
    this._currentGutterX    = null;
    this._currentGutterY    = null;

    this._resizeTimeout = null,

    this.conf = {
        baseWidth: 255,
        gutterX: null,
        gutterY: null,
        gutter: 10,
        container: null,
        minify: true,
        ultimateGutter: 5,
        surroundingGutter: true,
        direction: 'ltr',
        wedge: false
    };

    this.init(conf);

    return this;
}

MiniMasonry.prototype.init = function(conf) {
    for (var i in this.conf) {
        if (conf[i] != undefined) {
            this.conf[i] = conf[i];
        }
    }

    if (this.conf.gutterX == null || this.conf.gutterY == null) {
        this.conf.gutterX = this.conf.gutterY = this.conf.gutter;
    }
    this._currentGutterX = this.conf.gutterX;
    this._currentGutterY = this.conf.gutterY;

    this._container = typeof this.conf.container == 'object' && this.conf.container.nodeName ?
        this.conf.container :
        document.querySelector(this.conf.container);

    if (!this._container) {
        throw new Error('Container not found or missing');
    }

    var onResize = this.resizeThrottler.bind(this)
    window.addEventListener("resize", onResize);
    this._removeListener = function() {
        window.removeEventListener("resize", onResize);
        if (this._resizeTimeout != null) {
            window.clearTimeout(this._resizeTimeout);
            this._resizeTimeout = null;
        }
    }

    this.layout();
};

MiniMasonry.prototype.reset = function() {
    this._sizes   = [];
    this._columns = [];
    this._count   = null;
    this._width   = this._container.clientWidth;
    var minWidth  = this.conf.baseWidth;
    if (this._width < minWidth) {
        this._width = minWidth;
        this._container.style.minWidth = minWidth + 'px';
    }

    if (this.getCount() == 1) {
        // Set ultimate gutter when only one column is displayed
        this._currentGutterX = this.conf.ultimateGutter;
        // As gutters are reduced, two column may fit, forcing to 1
        this._count = 1;
    } else if (this._width < (this.conf.baseWidth + (2 * this._currentGutterX))) {
        // Remove gutter when screen is to low
        this._currentGutterX = 0;
    } else {
        this._currentGutterX = this.conf.gutterX;
    }
};

MiniMasonry.prototype.getCount = function() {
    if (this.conf.surroundingGutter) {
        return Math.floor((this._width - this._currentGutterX) / (this.conf.baseWidth + this._currentGutterX));
    }

    return Math.floor((this._width + this._currentGutterX) / (this.conf.baseWidth + this._currentGutterX));
}

MiniMasonry.prototype.computeWidth = function() {
    var width;
    if (this.conf.surroundingGutter) {
        width = ((this._width - this._currentGutterX) / this._count) - this._currentGutterX;
    } else {
        width = ((this._width + this._currentGutterX) / this._count) - this._currentGutterX;
    }
    width = Number.parseFloat(width.toFixed(2));

    return width;
}

MiniMasonry.prototype.layout =  function() {
    if (!this._container) {
        console.error('Container not found');
        return;
    }
    this.reset();

    //Computing columns count
    if (this._count == null) {
        this._count = this.getCount();
    }
    //Computing columns width
    var colWidth = this.computeWidth();

    for (var i = 0; i < this._count; i++) {
        this._columns[i] = 0;
    }

    //Saving children real heights
    var children = this._container.children;
    for (var k = 0;k< children.length; k++) {
        // Set colWidth before retrieving element height if content is proportional
        children[k].style.width = colWidth + 'px';
        this._sizes[k] = children[k].clientHeight;
    }

    var startX;
    if (this.conf.direction == 'ltr') {
        startX = this.conf.surroundingGutter ? this._currentGutterX : 0;
    } else {
        startX = this._width - (this.conf.surroundingGutter ? this._currentGutterX : 0);
    }
    if (this._count > this._sizes.length) {
        //If more columns than children
        var occupiedSpace = (this._sizes.length * (colWidth + this._currentGutterX)) - this._currentGutterX;
        if (this.conf.wedge === false) {
            if (this.conf.direction == 'ltr') {
                startX = ((this._width - occupiedSpace) / 2);
            } else {
                startX = this._width - ((this._width - occupiedSpace) / 2);
            }
        } else {
            if (this.conf.direction == 'ltr') {
                //
            } else {
                startX = this._width - this._currentGutterX;
            }
        }
    }

    //Computing position of children
    for (var index = 0;index < children.length; index++) {
        var nextColumn = this.conf.minify ? this.getShortest() : this.getNextColumn(index);

        var childrenGutter = 0;
        if (this.conf.surroundingGutter || nextColumn != this._columns.length) {
            childrenGutter = this._currentGutterX;
        }
        var x;
        if (this.conf.direction == 'ltr') {
            x = startX + ((colWidth + childrenGutter) * (nextColumn));
        } else {
            x = startX - ((colWidth + childrenGutter) * (nextColumn)) - colWidth;
        }
        var y = this._columns[nextColumn];


        children[index].style.transform = 'translate3d(' + Math.round(x) + 'px,' + Math.round(y) + 'px,0)';

        this._columns[nextColumn] += this._sizes[index] + (this._count > 1 ? this.conf.gutterY : this.conf.ultimateGutter);//margin-bottom
    }

    this._container.style.height = (this._columns[this.getLongest()] - this._currentGutterY) + 'px';
};

MiniMasonry.prototype.getNextColumn = function(index) {
    return index % this._columns.length;
};

MiniMasonry.prototype.getShortest = function() {
    var shortest = 0;
    for (var i = 0; i < this._count; i++) {
        if (this._columns[i] < this._columns[shortest]) {
            shortest = i;
        }
    }

    return shortest;
};

MiniMasonry.prototype.getLongest = function() {
    var longest = 0;
    for (var i = 0; i < this._count; i++) {
        if (this._columns[i] > this._columns[longest]) {
            longest = i;
        }
    }

    return longest;
};

MiniMasonry.prototype.resizeThrottler = function() {
    // ignore resize events as long as an actualResizeHandler execution is in the queue
    if ( !this._resizeTimeout ) {

        this._resizeTimeout = setTimeout(function() {
            this._resizeTimeout = null;
            //IOS Safari throw random resize event on scroll, call layout only if size has changed
            if (this._container.clientWidth != this._width) {
                this.layout();
            }
           // The actualResizeHandler will execute at a rate of 30fps
        }.bind(this), 33);
    }
}

MiniMasonry.prototype.destroy = function() {
    if (typeof this._removeListener == "function") {
        this._removeListener();
    }

    var children = this._container.children;
    for (var k = 0;k< children.length; k++) {
        children[k].style.removeProperty('width');
        children[k].style.removeProperty('transform');
    }
    this._container.style.removeProperty('height');
    this._container.style.removeProperty('min-width');
}

//for turning multi select in to buttons
class WPRevProCustomSelect {
  constructor(originalSelect) {
    this.originalSelect = originalSelect;
    this.customSelect = document.createElement("div");
    this.customSelect.classList.add("wprev_select");

    this.originalSelect.querySelectorAll("option").forEach((optionElement) => {
      const itemElement = document.createElement("div");

      itemElement.classList.add("wprev_select__item");
      itemElement.textContent = optionElement.textContent;
      this.customSelect.appendChild(itemElement);

      if (optionElement.selected) {
        this._select(itemElement);
      }

      itemElement.addEventListener("click", () => {
        if (
          this.originalSelect.multiple &&
          itemElement.classList.contains("wprev_select__item--selected")
        ) {
          this._deselect(itemElement);
        } else {
          this._select(itemElement);
        }
      });
    });

    this.originalSelect.insertAdjacentElement("afterend", this.customSelect);
    this.originalSelect.style.display = "none";
  }

  _select(itemElement) {
    const index = Array.from(this.customSelect.children).indexOf(itemElement);
    if (!this.originalSelect.multiple) {
      this.customSelect.querySelectorAll(".wprev_select__item").forEach((el) => {
        el.classList.remove("wprev_select__item--selected");
      });
    }
    this.originalSelect.querySelectorAll("option")[index].selected = true;
    itemElement.classList.add("wprev_select__item--selected");
  }

  _deselect(itemElement) {
    const index = Array.from(this.customSelect.children).indexOf(itemElement);
    this.originalSelect.querySelectorAll("option")[index].selected = false;
    itemElement.classList.remove("wprev_select__item--selected");
  }
}

