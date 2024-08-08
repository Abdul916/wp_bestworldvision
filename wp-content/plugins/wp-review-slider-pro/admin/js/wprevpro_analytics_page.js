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
		
		//style the pop-up size
		$('head').append('<style type="text/css">#TB_window {top:100px !important;margin-top: 50px !important;margin-left: -480px !important;width: 960px !important; height: 655px !important; }</style>');
		
		//globals
		//alert(adminjs_script_vars.globalwprevtypearray);
		//datepicker for page
		var start = moment().subtract(59, 'days');
		var end = moment();
		var startdate;
		var enddate;
		var seltypesarray = new Array();
		var sellocationsarray = new Array();
		var poswords;	//used for word clouds
		var negwords;	//used for word clouds
		//returned reviews
		var returneddataarray=[];
		var tempdataset={};
		var reviewids=[];
		
		//page select box
		$("#location_multiple_select").select2({
			width: 'resolve',
			placeholder: adminjs_script_vars.msg1
		});
		$('#location_multiple_select').on('change', function (e) {
			var dataobj = $(this).select2('data');
			//console.log(dataobj);
			//loop object and create array of selected types
			sellocationsarray.length = 0;
			$.each( dataobj, function( key, value ) {
			  //alert( key + ": " + value.id );
			  sellocationsarray.push(value.id);
			});
			//console.log(sellocationsarray);
			//update chart with type filter
			getdatasetoverchart();
		});
		
		
		//type select box
		$("#type_multiple_select").select2({
			width: 'resolve',
			placeholder: adminjs_script_vars.msg2
		});
		$('#type_multiple_select').on('change', function (e) {
			var dataobj = $(this).select2('data');
			//console.log(dataobj);
			//loop object and create array of selected types
			seltypesarray.length = 0;
			$.each( dataobj, function( key, value ) {
			  //alert( key + ": " + value.id );
			  seltypesarray.push(value.id);
			});
			//console.log(seltypesarray);
			//update chart with type filter
			getdatasetoverchart();
		});

		function cb(start, end) {
			$('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
			startdate = start.format('YYYY-MM-DD');
			enddate = end.format('YYYY-MM-DD');
			getdatasetoverchart();
			//alert(startdate);
		}

		$('#reportrange').daterangepicker({
			startDate: start,
			endDate: end,
			ranges: {
			   'Today': [moment(), moment()],
			   'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			   'Last 7 Days': [moment().subtract(6, 'days'), moment()],
			   'Last 30 Days': [moment().subtract(29, 'days'), moment()],
			   'Last 60 Days': [moment().subtract(59, 'days'), moment()],
			   'Last 90 Days': [moment().subtract(89, 'days'), moment()],
			   'This Month': [moment().startOf('month'), moment().endOf('month')],
			   'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
			   'This Year': [moment().startOf('year'), moment().endOf('year')],
			   'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
			   'All Time': [moment().subtract(50, 'year').startOf('year'), moment().endOf('year')]
			}
		}, cb);
		
		cb(start, end);
		// Disable automatic style injection
		//Chart.platform.disableCSSInjection = true;
		
		//for search box------------------------------
		var mysearchtimer;
		$('#wprevpro_analytics_filter_string').on('input', function() {
			clearTimeout(mysearchtimer);
			var myValue = $("#wprevpro_analytics_filter_string").val();
			var myLength = myValue.length;
			if(myLength>1 || myLength==0){
			//search here, use timer for delay in typing
				mysearchtimer = setTimeout(getdatasetoverchart, 500);
				//getdatasetoverchart();
			}
		});

		function getdatasetoverchart(){
			$("#overallChartspinner").show();
			var stringseltypes = JSON.stringify(seltypesarray);
			var stringsellocation = JSON.stringify(sellocationsarray);
			var filterbytext = $("#wprevpro_analytics_filter_string").val();
			//console.log(stringsellocation);
			
			//use ajax to return data array or maybe even the whole dataset.
			var data = {
				action : 'wppro_get_overall_chart_data',
				startd : startdate,
				endd : enddate,
				stypes : stringseltypes,
				slocations : stringsellocation,
				filtertext : filterbytext,
				_ajax_nonce : adminjs_script_vars.wpfb_nonce,
			};
			var jqxhr = jQuery.post(ajaxurl, data, function(response) {
				//console.log(response);
				if (!$.trim(response)){
					alert(adminjs_script_vars.msg3);
				} else {
					var formobject = JSON.parse(response);
					if(typeof formobject =='object'){
					  // It is JSON, safe to continue here
					  console.log(formobject);
					  returneddataarray = formobject.ratingvals;
					  //only insert chart if we have values
					  if(returneddataarray.length>0){
						tempdataset.data = {
									label: adminjs_script_vars.msg4,
									borderColor: "#3e95cd",
									pointBackgroundColor:'gold',
									pointBorderColor:'black',
									backgroundColor:'#f9f9f9',
									data: returneddataarray,
									borderWidth: 1,
									fill: false,
									trendlineLinear: {
										style: "#3e95cd",
										lineStyle: "line",
										width: 1
									}
								};
						tempdataset.labels = formobject.labelvals;
						tempdataset.reviews = formobject.reviewdata;
					  } else {
						  tempdataset.data = {
									data: returneddataarray
								};
					  }
					  insertoverallchart(tempdataset);

					  //insert distro chart
					  insertdistrochart();
						//setup rating num values and avg, clear first
						$("#avg_rating_num").html('0');
						$("#num_stars_5").html('0');
						$("#num_stars_4").html('0');
						$("#num_stars_3").html('0');
						$("#num_stars_2").html('0');
						$("#num_stars_1").html('0');
						if(formobject.avgrating){
							$("#avg_rating_num").html(formobject.avgrating);
						}
						if(formobject.ratingnumvals){
							if(formobject.ratingnumvals.numr5){
							$("#num_stars_5").html(formobject.ratingnumvals.numr5);
							}
							if(formobject.ratingnumvals.numr4){
							$("#num_stars_4").html(formobject.ratingnumvals.numr4);
							}
							if(formobject.ratingnumvals.numr3){
							$("#num_stars_3").html(formobject.ratingnumvals.numr3);
							}
							if(formobject.ratingnumvals.numr2){
							$("#num_stars_2").html(formobject.ratingnumvals.numr2);
							}
							if(formobject.ratingnumvals.numr1){
							$("#num_stars_1").html(formobject.ratingnumvals.numr1);
							}
						}
						//setup type average box revtypebox
						if(formobject.ratingtypenumvals){
							if(Object.keys(formobject.ratingtypenumvals).length>0){
								var tempobj = formobject.ratingtypenumvals;
								var temphtml1 = '';
								var temphtml2 = '';
								$.each( tempobj, function( key, value ) {
									var total = 0;
									var numratings= 0;
									var typelower = key.toLowerCase();
									numratings = value.length
									
									for(var i = 0; i < numratings; i++) {
										total += Number(value[i]);
									}
									var avg = total / numratings;
									avg = Math.round(avg * 10) / 10;
								  //console.log( key + " : " +numratings +" : " + avg +" : "+ value );
								  //build html to add to page
								   if(typelower!="manual" && typelower!="submitted"){
									temphtml1 = temphtml1 + '<div class="lh36"><img src="'+adminjs_script_vars.pluginsUrl+'/public/partials/imgs/'+typelower+'_small_icon.png" alt="'+typelower+' logo" class="charttypelogo">'+key+' </div>';
								  } else {
									temphtml1 = temphtml1 + '<div class="lh36">'+key+' </div>';
								  }
								  temphtml2 = temphtml2 + '<div class="lh36">'+numratings+' ('+avg+' <span class="svgicons svg-wprsp-star w3-text-gold"></span>)</div>';
								});
								//now add html
								$("#temphtml1").html(temphtml1);
								$("#temphtml2").html(temphtml2);
							}
						}
						//for word clouds get the data
						if(formobject.poswordarray){
							if(Object.keys(formobject.poswordarray).length>0){
								poswords = formobject.poswordarray;
								$("#positive_word_cloud").show();
								buildwordclouds('pos');
							} else {
								$("#positive_word_cloud").hide();
							}
						}
						if(formobject.negwordarray){
							if(Object.keys(formobject.negwordarray).length>0){
								negwords = formobject.negwordarray;
								$("#negative_word_cloud").show();
								buildwordclouds('neg');
							} else {
								$("#negative_word_cloud").hide();
							}
						}
						
					} else {
						console.log(adminjs_script_vars.msg5+" " +response);
					}
				}
			});
		}
		var myChartOverall;
		function insertoverallchart(tempdataset){
			//destroy old chart first in case we are updating
			if(myChartOverall){
				myChartOverall.destroy();
			}
			var overallChart = $('#overallChart');
			myChartOverall = new Chart(overallChart, {
				type: 'line',
				data: {
					labels: tempdataset.labels,
					datasets: [tempdataset.data]
				},
				options: {
					scales: {
						xAxes: [{
							ticks: {
								display: false
							}
						}],
						yAxes: [{
							ticks: {
								beginAtZero: true,
								max:6
							}
						}]
					},
					title: {
						display: true,
						text: adminjs_script_vars.msg6,
						legend: {
							display: false,
							labels: {
								fontColor: 'rgb(255, 99, 132)'
							}
						}
					},
					tooltips: {
						callbacks: {
							title: function(tooltipItem, data) {
							  return data['labels'][tooltipItem[0]['index']];
							},
							label: function(tooltipItem, data) {
							  return data['datasets'][0]['data'][tooltipItem['index']];
							}
						  }
					},
					legend: {
					 onHover: function(e) {
						e.target.style.cursor = 'pointer';
					 },
					 display:false
					},
				  hover: {
						 onHover: function(e) {
							var point = this.getElementAtEvent(e);
							if (point.length) e.target.style.cursor = 'pointer';
							else e.target.style.cursor = 'default';
						 }
					}
				}
			});
			
			//for clicking on overall chart, open pop-up with review info
			$('#overallChart').click(function (e)
				{
					var activePoints = myChartOverall.getElementsAtEvent(event);
					//var activeDataSet = myChartOverall.getDatasetAtEvent(event); //only use if we have more than one dataset
					console.log(activePoints);

					if (activePoints.length > 0)
					{
						 var clickedElementIndex = activePoints[0]._index;
						//use index to show pop-up and the correct review
			
						//open pop-up
						var url = "#TB_inline?width=auto&height=auto&inlineId=tb_content_popup";
						tb_show("Review Details", url);
						$( "#TB_window" ).css({ "width":"600px","margin-left": "-300px" });
						$( "#TB_ajaxContent" ).css({ "width":"auto","height":"auto" });
						$( "#TB_window" ).focus();
						$( "#review_details" ).show();
						$( "#review_list" ).hide();
						
						//get dbid.
						//var dbid = activePoints[0]._chart.tooltip._data.labels[clickedElementIndex][0];

						//find review in tempdataset.reviews and update pop-up
						$("#wprev_showname").html(tempdataset.reviews[clickedElementIndex].reviewer_name);
						if(tempdataset.reviews[clickedElementIndex].from_url_review!=''){
							$("#from_url_review").attr("href", tempdataset.reviews[clickedElementIndex].from_url_review);
						} else {
							$("#from_url_review").attr("href", tempdataset.reviews[clickedElementIndex].from_url);
						}
						
						$("#wprev_showdate").html(tempdataset.reviews[clickedElementIndex].created_time);
						
						var reviewtexthtml = '';
						if(tempdataset.reviews[clickedElementIndex].review_title!=''){
							reviewtexthtml="<b>"+tempdataset.reviews[clickedElementIndex].review_title+"</b><br>"+tempdataset.reviews[clickedElementIndex].review_text;
						} else {
							reviewtexthtml=tempdataset.reviews[clickedElementIndex].review_text;
						}
						//add owner_response
						if(tempdataset.reviews[clickedElementIndex].owner_response!=''){
							var ownerres=JSON.parse(tempdataset.reviews[clickedElementIndex].owner_response);
								//console.log(object[index].owner_response);
								//console.log(ownerres);
							reviewtexthtml = reviewtexthtml + '<div class="wppro_owners_res_div"><div class="wppro_revres_title">'+adminjs_script_vars.msg7+'</div><div>'+ownerres.name+' - '+ownerres.date+'</div><div>'+ownerres.comment+'</div></div>'
						}
						$(".wpproslider_t6_P_4").html(reviewtexthtml);
						
						if(tempdataset.reviews[clickedElementIndex].userpiclocal!=''){
							$(".wprev_avatar_opt").attr("src",tempdataset.reviews[clickedElementIndex].userpiclocal);
						} else if(tempdataset.reviews[clickedElementIndex].userpic!=''){
							$(".wprev_avatar_opt").attr("src",tempdataset.reviews[clickedElementIndex].userpic);
						}
						//site icon and link
						//need to find correct small icon based on review type
						var revtype = tempdataset.reviews[clickedElementIndex].type;
						var siconurl = adminjs_script_vars.pluginsUrl + '/public/partials/imgs/'+revtype.toLowerCase()+'_small_icon.png';
						var fromurllink = tempdataset.reviews[clickedElementIndex].from_url;
						var fromurlrevlink = tempdataset.reviews[clickedElementIndex].from_url_review;
						//facebook fix
						if(tempdataset.reviews[clickedElementIndex].type=='Facebook' && fromurllink==''){
							fromurllink = "https://www.facebook.com/pg/"+tempdataset.reviews[clickedElementIndex].pageid+"/reviews/";
						}
						if(tempdataset.reviews[clickedElementIndex].type=='Facebook' && fromurlrevlink==''){
							fromurlrevlink = 'https://www.facebook.com/search/top/?q='+encodeURI(tempdataset.reviews[clickedElementIndex].reviewer_name);
						}
						if(revtype!='Manual' && revtype!='Submitted'){
							$(".siteicon").attr("src",siconurl);
						}
						$("#from_url").attr("href",fromurllink);
						
						//insert correct star images
						var tempstarhtml = '';
						var temprating = 0;
						if(tempdataset.reviews[clickedElementIndex].rating!=''){
							temprating = Number(tempdataset.reviews[clickedElementIndex].rating);
						}
						var i;
						for (i = 1; i <= 5; i++) { 
							if(i<=temprating){
								tempstarhtml += '<span class="svgicons svg-wprsp-star"></span>';
							} else {
								tempstarhtml += '<span class="svgicons svg-wprsp-star-o"></span>';
							}
						}
						$("#starloc1").html(tempstarhtml);
						
						//add review source details...
						var sourcerevdetailshtml = adminjs_script_vars.msg8+': '+revtype+'</br>'+adminjs_script_vars.msg9+': '+tempdataset.reviews[clickedElementIndex].pagename+' ('+tempdataset.reviews[clickedElementIndex].pageid+')</br><a href="'+fromurllink+'" target="_blank">'+adminjs_script_vars.msg10+'</a> </br><a href="'+fromurlrevlink+'" target="_blank">'+adminjs_script_vars.msg11+'</a></br>';
						$(".sourcerevdetails").html(sourcerevdetailshtml);
					}
				});
			//hide spinner
			$("#overallChartspinner").hide();
		}
		
		function buildwordclouds(type){
			//word clouds
			var tempid;
			var tempglobal;
			var classpat;
			if(type=="pos"){
				tempglobal = poswords;
				tempid = '#positive_word_cloud';
				classpat = 'w{n}';
			} else if(type=="neg"){
				tempglobal = negwords;
				tempid = '#negative_word_cloud';
				classpat = 'v{n}';
			}
			//destroy so we can rebuild on update
			$(tempid).jQCloud('destroy');
			
			//loop through poswords if they exists
			var wordsarray = new Array();
			$.each( tempglobal, function( key, value ) {
				//console.log(key+":"+value);
				var tempwordobj = {text: key, weight: value, handlers: {click: function() {showclickedwordreviews(this);}}};
				wordsarray.push(tempwordobj);
			});

			console.log(wordsarray);
			console.log(tempglobal);

			$(tempid).jQCloud(wordsarray, {
			  classPattern: classpat,
			  removeOverflowing: false
			});
			$(tempid).jQCloud('update', wordsarray);
		}
		
		//display list of reviews that match clicked word in cloud
		function showclickedwordreviews(clickedword){
			console.log(clickedword);
			var clickedwordtext = $(clickedword).text();
			var clickedspanid = $(clickedword).attr('id');
			var posornegrating = '';
			if(clickedspanid.includes('positive')){
				posornegrating = 'pos';
			} else {
				posornegrating = 'neg';
			}
			//all reviews contained in tempdataset.reviews array of object. We need to loop and list them.
			//console.log(tempdataset.reviews);
			//show pop-up and build list
			//open pop-up
			var url = "#TB_inline?width=auto&height=auto&inlineId=tb_content_popup";
			tb_show(adminjs_script_vars.msg12, url);
			$( "#TB_window" ).css({ "width":"80%","margin-left": "-40%","margin-top": "-400px"  });
			$( "#TB_ajaxContent" ).css({ "width":"auto","height":"auto","max-height":"700px" });
			$( "#TB_window" ).focus();
			$( "#review_details" ).hide();
			$( "#review_list" ).show();
			
			var reviewshtml = '';

			$.each( tempdataset.reviews, function( key, value ) {
				//console.log(key+":"+value.review_text);
				var lowercasereviewtext = value.review_text.toLowerCase();
				if(value.review_text!='' && lowercasereviewtext.includes(clickedwordtext)){
					if((posornegrating=='pos' && Number(value.rating)>3) || (posornegrating=='neg' && Number(value.rating)<3) || (posornegrating=='pos' && value.recommendation_type=="positive") || (posornegrating=='neg' && value.recommendation_type=="negative")){
					
					//function to build the revlist html
					reviewshtml = reviewshtml + getreviewshtml(value,clickedwordtext);
					
					}
					
				}
			});
			
			$( "#review_list_body" ).html(reviewshtml);
		}
		
		function getreviewshtml(value, searchword=''){
			//this reviews contains the word so add it to html
			//str.replace("Microsoft", "W3Schools");
					var reviewshtml=''
					var userpic="";
					if(value.userpiclocal!=""){
						userpic = '<img wprevid="'+value.id+'" class="imgprofilepic" style="-webkit-user-select: none;width: 50px;" src="'+value.userpiclocal+'">';
					} else {
						userpic = '<img wprevid="'+value.id+'" class="imgprofilepic" style="-webkit-user-select: none;width: 50px;" src="'+value.userpic+'">';
					}
					
					var fromname = '';
					if(value.from_name){
						fromname ='-'+value.from_name;
					}
					var fromurllink = wprev_prourldecode(value.from_url);
					var fromurlrevlink = wprev_prourldecode(value.from_url_review);
					if(fromurlrevlink==''){
						fromurlrevlink = fromurllink;
					}
					//facebook fix
					if(value.type=='Facebook' && fromurllink==''){
						fromurllink = "https://www.facebook.com/pg/"+value.pageid+"/reviews/";
					}
					if(value.type=='Facebook' && fromurlrevlink==''){
						fromurlrevlink = 'https://www.facebook.com/search/top/?q='+encodeURI(value.reviewer_name);
					}
					var reviewtext = value.review_text;
					if(searchword!=''){
						var boldsearchword = "<b>"+searchword+"</b>";
						var replace = searchword;
						var re = new RegExp(replace,"gi");
						reviewtext = reviewtext.replace(re, boldsearchword);
					}
					//add owner_response
						if(value.owner_response!=''){
							var ownerres=JSON.parse(value.owner_response);
								//console.log(object[index].owner_response);
								//console.log(ownerres);
							reviewtext = reviewtext + '<div class="wppro_owners_res_div"><div class="wppro_revres_title">'+adminjs_script_vars.msg7+'</div><div>'+ownerres.name+' - '+ownerres.date+'</div><div>'+ownerres.comment+'</div></div>'
						}
						
					reviewshtml = reviewshtml + '<tr id="'+value.id+'" rtype="'+value.type+'">	\
							<th scope="col" class="wprev_row_userpic wprev_row_reviewer_name manage-column"><a href="'+fromurlrevlink+'" target="_blank">'+userpic+'<br>'+value.reviewer_name+'</a></th>	\
							<th scope="col" class="wprev_row_rating manage-column"><b>'+value.rating+'</br>'+value.recommendation_type+'</b></th>	\
							<th scope="col" rtitle="'+value.reviewer_name+'" class="wprev_row_review_text manage-column"><span class="wprev_row_review_title_span">'+value.review_title+'</span><span class="wprev_row_review_text_span">'+reviewtext+'</span></th>	\
							<th scope="col" class="wprev_row_created_time manage-column">'+value.created_time+'</th>	\
							<th scope="col" class="manage-column">'+value.review_length+'<br>'+value.review_length_char+'<br>'+value.language_code+'</th>	\
							<th scope="col" class="manage-column">'+value.pagename+'</br><a href="'+fromurllink+'" target="_blank">'+value.type+fromname+'</a></th></tr>';
							
				return reviewshtml;
		}
		
		//insert distro chart
		var mydistroChart;
		function insertdistrochart(){
			
			//first need data arrays for each star number
			var stardataarray=new Array();
			var i;
			for (i = 1; i < 6; i++) {
				stardataarray[i]=new Array();
			}
			var labelsarray = new Array();
			var currentday;
			var tempratingcount = 1;
			//loop through reviews build arrays and array of labels
			console.log('date:');
			
			$.each( tempdataset.reviews, function( key, value ) {
				currentday = moment(value.created_time).format("MMM D");
				//push values in to arrays, first check last value in labelsearray if equal to currentday then add 1 to stardataarray
				//get last value in labelsarray
				if(labelsarray[labelsarray.length-1] == currentday){
				  //still on same day, add a 1 to that last value of stardataarray
					for (i = 1; i < 6; i++) {
						if(Number(value.rating)==i){
							stardataarray[i][stardataarray[i].length-1] = stardataarray[i][stardataarray[i].length-1] + 1;
						}
					}
				}else{
				 //new day so just add it here
				 	for (i = 1; i < 6; i++) {
						if(Number(value.rating)==i){
							stardataarray[i].push(tempratingcount);
						} else {
							stardataarray[i].push(0);
						}
					}
					labelsarray.push(currentday);
				}
			});
			console.log(labelsarray);
			console.log(stardataarray);
			//div id=ratingdistrochart
			$('#distroChartspinner').hide();
			var barChartData = {
			labels: labelsarray,
			datasets: [{
				label: '5 Star',
				backgroundColor: '#0b6fbf',
				stack: 'Stack 0',
				data: stardataarray[5]
			}, {
				label: '4 Star',
				backgroundColor: '#00ccff',
				stack: 'Stack 0',
				data: stardataarray[4]
			}, {
				label: '3 Star',
				backgroundColor: '#ffd700',
				stack: 'Stack 0',
				data: stardataarray[3]
			}, {
				label: '2 Star',
				backgroundColor: '#ff9800',
				stack: 'Stack 0',
				data: stardataarray[2]
			}, {
				label: '1 Star',
				backgroundColor: '#ea0000',
				stack: 'Stack 0',
				data: stardataarray[1]
			}]
			};
			var distroChart = $('#ratingdistrochart');
			if(mydistroChart){
				mydistroChart.destroy();
			}
			mydistroChart = new Chart(distroChart, {
				type: 'bar',
				data: barChartData,
				options: {
					title: {
						display: true,
						text: 'Rating Distribution'
					},
					tooltips: {
						mode: 'index',
						intersect: false
					},
					responsive: true,
					scales: {
						xAxes: [{
							stacked: true,
						}],
						yAxes: [{
							stacked: true
						}]
					},
					legend: {
					 onHover: function(e) {
						e.target.style.cursor = 'pointer';
						}
					},
					hover: {
						 onHover: function(e) {
							var point = this.getElementAtEvent(e);
							if (point.length) e.target.style.cursor = 'pointer';
							else e.target.style.cursor = 'default';
						 }
					}
				}
			});
			
			//for clicking on overall chart, open pop-up with review info
			$('#ratingdistrochart').click(function (e){
					var activePoints = mydistroChart.getElementsAtEvent(event);
					//var activeDataSet = mydistroChart.getDatasetAtEvent(event); //only use if we have more than one dataset
					console.log(activePoints);

					if (activePoints.length > 0)
					{
						var clickedElementIndex = activePoints[0]._index;
						console.log(clickedElementIndex);
						//get date at that index
						console.log(labelsarray[clickedElementIndex]);
						var clickeddatestring = labelsarray[clickedElementIndex];
						
						//loop review array and add to display html if dates match
						var url = "#TB_inline?width=auto&height=auto&inlineId=tb_content_popup";
						tb_show(adminjs_script_vars.msg12, url);
						$( "#TB_window" ).css({ "width":"80%","margin-left": "-40%","margin-top": "-400px"  });
						$( "#TB_ajaxContent" ).css({ "width":"auto","height":"auto","max-height":"700px" });
						$( "#TB_window" ).focus();
						$( "#review_details" ).hide();
						$( "#review_list" ).show();
						
						var reviewshtml = '';
						var userpic;
						var fromname;
						var temprevdate;
						$.each( tempdataset.reviews, function( key, value ) {
							//get date of this review and compare
							temprevdate = moment(value.created_time).format("MMM D");
							if(temprevdate==clickeddatestring){
																
								//function to build the revlist html
								reviewshtml = reviewshtml + getreviewshtml(value);
								
							}
						});
						$( "#review_list_body" ).html(reviewshtml);
						
					}
					
			});
		
		
		}
		
		//javascript equivelant to urldecode
		function wprev_prourldecode(url) {
		  return decodeURIComponent(url.replace(/\+/g, ' '));
		}
		
	});

})( jQuery );
