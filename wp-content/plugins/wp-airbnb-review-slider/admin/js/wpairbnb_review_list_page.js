;(function( $ ) {
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
		
		//help button clicked
		$( "#wpairbnb_helpicon" ).click(function() {
		  openpopup("Tips", '<p>- If you\'re using the pro version you can hide certain reviews by clicking the <i class="dashicons dashicons-visibility text_green" aria-hidden="true"></i> in the table below. There are also ways to hide certain types of reviews under the Templates page.</p>	\
		  <p><b>- Remove All Reviews:</b> Allows you to delete all reviews in your Wordpress database and start over. It Does NOT affect your reviews on Airbnb.</p> \
		  ', "");
		});
		
		//remove all button
		$( "#wpairbnb_removeallbtn" ).click(function() {
			var sec = $(this).attr('data-sec');
		  openpopup("Are you sure?", '<p>This will delete all reviews in your Wordpress database including the ones you manually entered. It Does NOT affect your reviews on Airbnb.</p>', '<a class="button dashicons-before dashicons-no" href="?page=wp_airbnb-reviews&opt=delall&_wpnonce='+sec+'">Remove</a>');
		});	

		//upgrade to pro
		$( ".wpairbnb_upgrade_needed" ).click(function() {
		  openpopup("Upgrade Needed", '<p>Please upgrade to the Pro Version of this Plugin to access this feature.</p>', '<a class="button dashicons-before  dashicons-cart" href="https://wpreviewslider.com/">Upgrade Here</a>');
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
		//hide or show new review form ----------
		$( "#wpairbnb_addnewreviewbtn" ).click(function() {
		  jQuery("#wpairbnb_new_review").show("slow");
		});	
		$( "#wpairbnb_addnewreview_cancel" ).click(function() {
		  jQuery("#wpairbnb_new_review").hide("slow");
		  //reload page without taction and tid
		  setTimeout(function(){ 
			window.location.href = "?page=wp_airbnb-reviews"; 
		  }, 500);
		  
		});
		//show form if rid hidden field has a value
		if(jQuery("#editrid").val()!=""){
			jQuery("#wpairbnb_new_review").show("slow");
		}
		
		//upload avatar button----------------------------------
		$('#upload_avatar_button').click(function() {
			tb_show('Upload Reviewer Avatar', 'media-upload.php?referer=wp_airbnb-reviews&type=image&TB_iframe=true&post_id=0', false);
			return false;
		});
		
		window.send_to_editor = function(html) {
			var image_url = jQuery("<div>" + html + "</div>").find('img').attr('src');
			//var image_url = $('img',html).attr('src');
			$('#wpairbnb_nr_avatar_url').val(image_url);
			$("#avatar_preview").attr("src",image_url);
			tb_remove();
			
		}
		
		//form validation
		$("#newreviewform").submit(function(){ 

			  if ($('input[name=wpairbnb_nr_rating]:checked').length) {
				   // at least one of the radio buttons was checked
				   //return true; // allow whatever action would normally happen to continue
				   
			  } else {
				   // no radio button was checked
				   alert("Please select review value.");
				   return false; // stop whatever action would normally happen
			  }
		
			if(jQuery( "#wpairbnb_nr_name").val()==""){
				alert("Please enter a name.");
				$( "#wpairbnb_nr_name" ).focus();
				return false;
			} else {
				return true;
			}

		});
		
		//ajax for hide or delete btn clicked for a review
		$("#review_list").on("click", ".revdelbtn", function (event) {
			//grab the id for this review
			var rid = $(this).closest('tr').prop("id");
			var rowobject = $(this).closest('tr');
				//post to server
			sendtoajax(rid,"deleterev",rowobject);
		});
		
		$("#review_list").on("click", ".hiderevbtn", function (event) {
			//grab the id for this review
			var rid = $(this).closest('tr').prop("id");
			var rowobject = $(this).closest('tr');
				//post to server
			sendtoajax(rid,"hideshow",rowobject);
		
		});
		
		//for edit review btn click
		$("#review_list").on("click", ".reveditbtn", function (event) {
			//grab the id for this review
			
			var rid = $(this).closest('tr').prop("id");
			var rowobject = $(this).closest('tr');
			var name = rowobject.find('.wprev_row_reviewer_name').html();
			var wprev_row_userpic = rowobject.find('.wprev_row_userpic').find('img').attr('src');
			var wprev_row_rating = rowobject.find('.wprev_row_rating').html();
			var wprev_row_review_text = rowobject.find('.wprev_row_review_text').html();
			var wprev_row_created_time = rowobject.find('.wprev_row_created_time').html();

			//show edit form and focus
			$("#wpairbnb_new_review").show("slow");
			//find values from rowobject and fill in edit form wpairbnb_nr_rating, wpairbnb_nr_text, wpairbnb_nr_name, wpairbnb_nr_avatar_url, wpairbnb_nr_date
			$("#editrid").val(rid);
			$("#wpairbnb_nr_name").val(name);
			$("#wpairbnb_nr_avatar_url").val(wprev_row_userpic);
			//for radio
			$("#wpairbnb_nr_rating").val(wprev_row_rating);
			$("#wpairbnb_nr_date").val(wprev_row_created_time);
			
			$("#wpairbnb_nr_text").val(wprev_row_review_text);
			
			//var ratingnum = rowobject.
			

		
		});
		//ajax for hiding and deleting
		function sendtoajax(rid,whattodo,rowobject){
			var senddata = {
					action: 'wpairbnb_hide_review',	//required
					wpairbnb_nonce: adminjs_script_vars.wpairbnb_nonce,
					reviewid: rid,
					myaction: whattodo
					};

				jQuery.post(ajaxurl, senddata, function (response){
				//console.log(response);
					var res = response.split("-");
					if(res[1]=="hideshow"){
						//change icon if hiding or showing
						if(res[2]=="yes"){
							//hiding this one
							rowobject.find('.hiderevbtn').removeClass('dashicons-visibility');
							rowobject.find('.hiderevbtn').removeClass('text_green');
							rowobject.find('.hiderevbtn').addClass('dashicons-hidden');
						} else {
							rowobject.find('.hiderevbtn').removeClass('dashicons-hidden');
							rowobject.find('.hiderevbtn').addClass('dashicons-visibility');
							rowobject.find('.hiderevbtn').addClass('text_green');
						}
						if(res[2]=="fail"){
							alert("Oops! Unable to hide this review. Please contact support.");
						}
					}
					if(res[1]=="deleterev"){
						if(res[2]=="success"){
							//hide the row
							jQuery("#"+rid).hide("slow");
						} else {
							alert("Oops! Unable to delete this review. Please contact support.");
						}
						
					}
				
				});
		}
		
		//--------for searching--------------------------------------
		//for search box------------------------------
		$('#wpairbnb_filter_table_name').on('input', function() {
			var myValue = $("#wpairbnb_filter_table_name").val();
			var myLength = myValue.length;
			if(myLength>1 || myLength==0){
			//search here
				sendtoajaxreview('','','',"");
			}
		});
		//for sorting table--------------wpairbnb_sortname, wpairbnb_sorttext, wpairbnb_sortdate
		$( ".wpairbnb_tablesort" ).click(function() {
			//remove all green classes
			$(this).parent().find('i').removeClass("text_green");

			//add back on this one
			$(this).children( "i" ).addClass("text_green");
			
			var sortdir = $(this).attr("sortdir");
			var sorttype = $(this).attr("sorttype");
			if(sortdir=="DESC"){
				$(this).attr("sortdir","ASC");
			} else {
				$(this).attr("sortdir","DESC");
			}
			if(sorttype=="name"){
				sorttype="reviewer_name";
			} else if(sorttype=="rating") {
				sorttype="rating";
			} else if(sorttype=="stext") {
				sorttype="review_length";
			} else if(sorttype=="stime") {
				sorttype="created_time_stamp";
			}
		  sendtoajaxreview('1',sorttype,sortdir,"");
		});
		
		//for search select box------------------------------
		$( "#wpairbnb_filter_table_min_rating" ).change(function() {
				sendtoajaxreview('','','',"");
		});
		//for pagination bar-----------------------------------
		$("#wpairbnb_review_list_pagination_bar").on("click", "span", function (event) {
			var pageclicked = $(this).text();
			sendtoajaxreview(pageclicked,'','',"");
		});
		function sendtoajaxreview(pageclicked,sortbyval,sortd,selrevs){
			var filterbytext = $("#wpairbnb_filter_table_name").val();
			var filterbyrating = $("#wpairbnb_filter_table_min_rating").val();
			//clear list and pagination bar
			$( "#review_list" ).html("");
			$( "#wpairbnb_review_list_pagination_bar" ).html("");
			var senddata = {
					action: 'wpairbnb_find_reviews',	//required
					wpairbnb_nonce: adminjs_script_vars.wpairbnb_nonce,
					sortby: sortbyval,
					sortdir: sortd,
					filtertext: filterbytext,
					filterrating: filterbyrating,
					pnum:pageclicked,
					curselrevs:selrevs
					};

				jQuery.post(ajaxurl, senddata, function (response){
					//console.log(response);
					var object = JSON.parse(response);
				//console.log(object);

				var htmltext;
				var userpic;
				var editdellink;
				var hideicon;
				var url_tempeditbtn;
				var reviewtext = '';
				
					$.each(object, function(index) {
						if(object[index]){
						if(object[index].reviewer_name){
							
							//userpic
							userpic="";
							if(object[index].type=="Facebook"){
								userpic = '<a href="http://facebook.com/'+object[index].reviewer_id+'" target=_blank><img style="-webkit-user-select: none;width: 50px;" src="https://graph.facebook.com/'+object[index].reviewer_id+'/picture?type=square"></a>';
								editdellink ='';
							} else if(object[index].type=="Airbnb"){
								userpic = '<img style="-webkit-user-select: none;width: 50px;" src="'+object[index].userpic+'">';
								editdellink ='';
							} else {
								userpic = '<img style="-webkit-user-select: none;width: 50px;" src="'+object[index].userpic+'">';
								editdellink = '<span class="reveditbtn dashicons dashicons-edit"></span><span title="Delete" class="revdelbtn text_red dashicons dashicons-trash"></span>';
							}
							//hide link
							if(object[index].hide!="yes"){
								hideicon = '<i title="Shown" class="hiderevbtn dashicons dashicons-visibility text_green" aria-hidden="true"></i>';
							} else {
								hideicon = '<i title="Hidden" class="hiderevbtn dashicons dashicons-hidden" aria-hidden="true"></i>';
							}
							//stripslashes
							reviewtext = String(object[index].review_text);
							reviewtext = reviewtext.replace(/\\'/g,'\'').replace(/\"/g,'"').replace(/\\\\/g,'\\').replace(/\\0/g,'\0');
							
							htmltext = htmltext + '<tr id="'+object[index].id+'">	\
								<th scope="col" class="manage-column">'+hideicon+' '+editdellink+'</th>	\
								<th scope="col" class="wprev_row_userpic">'+userpic+'</th>	\
								<th scope="col" class="wprev_row_reviewer_name manage-column">'+object[index].reviewer_name+'</th>	\
								<th scope="col" class="wprev_row_rating manage-column"><b>'+object[index].rating+'</b></th>	\
								<th scope="col" class="wprev_row_review_text manage-column">'+reviewtext+'</th>	\
								<th scope="col" class="wprev_row_created_time manage-column">'+object[index].created_time+'</th>	\
								<th scope="col" class="manage-column">'+object[index].review_length+'</th>	\
								<th scope="col" class="manage-column">'+object[index].pagename+'</th>	\
								<th scope="col" class="manage-column">'+object[index].type+'</th>	\
							</tr>';
							reviewtext ='';
						}
						}
					});
					
					$( "#review_list" ).html(htmltext);
					
					//pagination bar------------------
					var numpages = Number(object['totalpages']);
					var reviewtotalcount = Number(object['reviewtotalcount']);
					if(numpages>1){
						var pagebarhtml="";
						var blue_grey;
						var i;
						var numpages = Number(object['totalpages']);
						var curpage = Number(object['pagenum']);
						for (i = 1; i <= numpages; i++) {
							if(i==curpage){blue_grey = " blue_grey";} else {blue_grey ="";}
							pagebarhtml = pagebarhtml + '<span class="button'+blue_grey+'">'+i+'</span>';
						}
					}
						$( "#wpairbnb_review_list_pagination_bar" ).html(pagebarhtml);

					if(reviewtotalcount==0){
						$("#wpairbnb_review_list_pagination_bar").hide();
					} else {

						$("#wpairbnb_review_list_pagination_bar").show();
					}
					
				});
		}
		
		
		
	});

})( jQuery );