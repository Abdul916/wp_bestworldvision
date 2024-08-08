<?php
  class GetAppsReviews_Functions extends WP_Review_Pro_Admin_Hooks {
	
	//============================================================
	//functions for scraping reviews from the get-apps page
	//--------------------------
	//
	/**
	 * Called from admin class-wp-review-slider-pro-admin_hooks.php > wprp_getapps_getrevs_ajax_go
	 * @access  public
	 * @since   11.3.7
	 * @return  void
	 
	 */
	//for calling Google Places API
	public function wprp_getapps_getrevs_page_googleplacesapi($sitetype,$listedurl,$pagenum,$perpage,$savedpageid,$sortoption,$fid,$blockstoinsert,$nextpageurl,$langcode){
		$result['ack'] = '';
		$result['ackmsg'] = '';
		$reviewsarray = Array();
		
		//api key
		$google_api_key = get_option('wprevpro_googleplacesapikey_val');
		
		//sortoption, relevant, newest, both
		$temprsort='';
		$jumpnum=1;
		if($sortoption=='relevant'){
			$rsort='most_relevant';
		} else if($sortoption=='newest'){
			$rsort='newest';
		} else if($sortoption=='both'){
			$temprsort='both';
			$rsort='most_relevant';
		}


		//if rsort is both then we need to jump back here and run again with other sort option adding to reviews array.
		a:
		if($temprsort=='both' && $jumpnum == 2){
			$rsort='newest';
		}

		$google_places_url = add_query_arg(
			array(
				'placeid' => trim($savedpageid),
				'key'     => trim($google_api_key),
				'language' => trim($langcode),
				'reviews_sort'=> trim($rsort),
				'reviews_no_translations'=> "true"	
			),
			'https://maps.googleapis.com/maps/api/place/details/json'
		);
		
		//Sanitize the URL
		$url = esc_url_raw( $google_places_url );
		
		//echo $url;
		
		$data = wp_remote_get($url, array(
		  'timeout' => 20,
		  'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:20.0) Gecko/20100101 Firefox/20.0'
		));

		if ( is_wp_error( $data ) ) 
		{
			$result['ack'] ='error';
			$result['ackmsg'] =__('Error gpa_0001: Something happened. Msg: ','wp-review-slider-pro').$data->get_error_message();
			//$result = json_encode($result);
			//echo $result;
			return $result;
			die();
		}
		$response = json_decode( $data['body'], true );
		
		//print_r($response);
		//die();
		//catch non OK status status
		if( !isset( $response['status'])  ||  $response['status']!='OK' ) {
			$result['ack'] ='error';
			$result['ackmsg'] =__('Error gpa_0002: Error from Google: ','wp-review-slider-pro').$response['status'].", Full Response: ".$data['body'];
			//$result = json_encode($result);
			//echo $result;
			return $result;
			die();
		}


		if( ! ( isset( $response['result']['reviews'] ) ||  empty( $response['result']['reviews'] ) ) ){
			$result['ack'] ='error';
			$result['ackmsg'] =__('Error gpa_0003: No Google Reviews Found. Please check your api key and place id. ','wp-review-slider-pro');
			//$result = json_encode($result);
			//echo $result;
			return $result;
			die();
		}
		
		
			//need totals and avg for this place
			$result['total']='';
			$result['avg']='';
			if(isset($response['result']['rating'])){
				$result['avg']=$response['result']['rating'];
			}
			if(isset($response['result']['user_ratings_total'])){
				$result['total']=$response['result']['user_ratings_total'];
			}
			//print_r($response['result']['reviews']);
			foreach( $response['result']['reviews'] as $item){
			
				if(!isset($item['language']) || $item['language']=="NULL"){
							$item['language']='';
				}
				$intreviewer_id = filter_var($item['author_url'], FILTER_SANITIZE_NUMBER_INT);
				
				$updatedtimestring = date( "Y-m-d H:i:s", $item['time'] );
		
				$reviewsarray[] = [
				 'reviewer_name' => $item['author_name'],
				 'reviewer_id' => $intreviewer_id,
				 'reviewer_email' => '',
				 'userpic' => $item['profile_photo_url'],
				 'rating' => $item['rating'],
				 'updated' => $updatedtimestring,
				 'review_text' => $item['text'],
				 'review_title' => '',
				 'from_url' => $response['result']['url'],
				 'from_url_review' => '',
				 'language_code' => $item['language'],
				 'location' => '',
				 'recommendation_type' => '',
				 'company_title' =>  '',
				 'company_url' => '',
				 'company_name' => '',
				 'mediaurlsarrayjson' => '',
				 ];

			}

			//jump back up and run again if using both sort option
			if($temprsort=='both' && $jumpnum ==1){
				$jumpnum = 2;
				goto a;
			}
			
			//print_r($reviewsarray);
			//die();
			
			if($temprsort=='both'){
				//remove possible duplicates
				$reviewsarray = array_map("unserialize", array_unique(array_map("serialize", $reviewsarray)));
			}
			
			$result['reviews'] = $reviewsarray;
			
			
			
			//only calling once since this is google.
			$result['stoploop'] ='stop';
		
		return $result;
	}
	
	
	//for calling remote get and returning array of reviews to insert, calling Crawler now crawl.ljapps.com
	public function wprp_getapps_getrevs_page_google($type,$listedurl,$pagenum,$perpage,$savedpageid,$nhful,$fid,$blockstoinsert){
		
			//Google crawl method to actually download reviews
			if($savedpageid!=''){
				$gplaceid = $savedpageid;
			} else {
				//error no place id.
				$result['ack'] = 'error';
				$result['ackmsg'] =esc_html__('Error 103: Please enter your search terms or place id above and click the Save & Test button.', 'wp-review-slider-pro');
			}
			$checkdetails = json_decode(get_option('wprev_google_crawl_check'),true);
			//print_r($checkdetails);
			//die();
			$tempkey = trim(sanitize_text_field($listedurl));
			//strip \' from url 
			$tempkey = str_replace("\'","",$tempkey);
			
			if($checkdetails[$tempkey]['idorquery'] == 'query' && $checkdetails[$tempkey]['enteredterms']!=''){
				$tempbusinessname=$checkdetails[$tempkey]['enteredterms'];
			} else {
				$tempbusinessname=$checkdetails[$tempkey]['businessname'];
			}

			if(isset($_SERVER['SERVER_ADDR']) && $_SERVER['SERVER_ADDR']!=''){
				$ip_server = $_SERVER['SERVER_ADDR'];
			} else {
				//get url of site.
				$ip_server = urlencode(get_site_url());
			}
			$locationtype = '';
			if($checkdetails[$tempkey]['placetype']=='hotel'){
				$locationtype = 'hotel';
			} else if($checkdetails[$tempkey]['placetype']=='product'){
				$locationtype = 'product';
				
			}
			//print_r($checkdetails);
			$siteurl = urlencode(get_site_url());
			
			$tempurlvalue = 'https://crawl.ljapps.com/crawlrevs?rip='.$ip_server.'&blocks='.$blockstoinsert.'&surl='.$siteurl.'&stype=google&sfp=pro&nobot=1&nobot=1&nhful='.$nhful.'&locationtype='.$locationtype.'&scrapequery='.urlencode($gplaceid).'&tempbusinessname='.urlencode($tempbusinessname);

			//echo $tempurlvalue;
			//die();
			
			$serverresponse='';

			//try remote_get if we dont' have serverresponse yet
			$args = array(
				'timeout'     => 55,
				'sslverify' => false
			); 
			$response = wp_remote_get( $tempurlvalue, $args );
			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				$headers = $response['headers']; // array of http header lines
				$serverresponse    = $response['body']; // use the content
			} else {
				//must have been an error
				$results['ack'] ='error';
				$results['ackmsg'] ='Error 0001ag: trouble contacting crawling server with remote_get. Make sure wp_remote_get is allowed on your server. Please try again or contact support.'.$response->get_error_message();
				$results = json_encode($results);
				echo $results;
				die();
			}

			
			$serverresponsearray = json_decode($serverresponse, true);

			if($serverresponse=='' || !is_array($serverresponsearray)){
				$results['ack'] ='error';
				$results['ackmsg'] ='Error 0001: trouble contacting crawling server. Please contact support.';
				$results = json_encode($results);
				echo $results;
				die();
			}
			//catch limit error
			if($serverresponsearray['ack']=='error'){
				$results['ack'] ='error';
				$results['ackmsg'] ='Error 0002: '.$serverresponsearray['result']['ackmessage']." : ".$serverresponsearray['ackmessage'];
				$results = json_encode($results);
				echo $results;
				die();
			}
			if(!isset($serverresponsearray['result']) || !is_array($serverresponsearray['result'])){
				$results['ack'] ='error';
				$results['ackmsg'] ='Error 0002b: trouble finding reviews. Contact support with this error code and the search terms or place id you are using.';
				$results = json_encode($results);
				echo $results;
				die();
			}
			//catch error
			if($serverresponsearray['result']['ack']=='error'){
				$results['ack'] ='error';
				$results['ackmsg'] ='Error 0003: Please try again. '.$serverresponsearray['result']['ackmessage'];
				$results = json_encode($results);
				echo $results;
				die();
			}

	//print_r($serverresponsearray);
	//die();
			//made it this far assume we have reviews.
			$crawlerresultarray = $serverresponsearray['result'];
			
			if(!isset($crawlerresultarray['reviews']) || !is_array($crawlerresultarray['reviews'])){
				$results['ack'] ='error';
				$results['ackmsg'] ='Error 0004: trouble finding reviews. Contact support with this error code and the search terms or place id you are using. ';
				$results = json_encode($results);
				echo $results;
				die();
			}
			$crawlerreviewsarray = $crawlerresultarray['reviews'];

			//need totals and avg for this place $getreviewsarray['total']
			$result['total']='';
			$result['avg']='';
			if(isset($crawlerresultarray['avg'])){
				$result['avg']=$crawlerresultarray['avg'];
			}
			if(isset($crawlerresultarray['total'])){
				$result['total']=$crawlerresultarray['total'];
			}
			
			//find link to google reviews.
			if (strpos($gplaceid, ' ') !== false) {
				$orgreviewurlvalue = 'https://www.google.com/search?q='.urlencode($gplaceid);
			} else {
				$orgreviewurlvalue = "https://search.google.com/local/reviews?placeid=".urlencode($gplaceid);
			}
			
			$x=0;
			$numreturned = count($crawlerreviewsarray);
			
			//print_r($crawlerreviewsarray);
	
			foreach ($crawlerreviewsarray as $review) {
				// Find user_name
				$results[$x]['user_name']=$review['user_name'];
				
				//created time
				$results[$x]['created_time']=$review['created_time'];
				$results[$x]['created_time_stamp']=$review['created_time_stamp'];
			
						
				//find reviewer_id from maps link		//https://www.google.com/maps/contrib/117800412986895302631?hl=en-US&sa=X&ved=2ahUKEwit9_W6s-PyAhWFTTABHZaUBeIQvvQBegQIARAh
				$results[$x]['user_link']=$review['user_link'];
				$results[$x]['reviewer_id']=$review['reviewer_id'];

				//find review rating span Fam1ne EBe2gf
				$results[$x]['rating']=$review['rating'];
				
				//find review text
				$results[$x]['review_text']=$review['review_text'];
				$results[$x]['reviewlength']=$review['reviewlength'];
				
				//find mediajson
				$results[$x]['mediaurlsarrayjson']=$review['mediaurlsarrayjson'];
				
				//find metadata
				$results[$x]['meta_data']=$review['meta_data'];
				
				//find owner res
				$results[$x]['owner_response']=$review['owner_response'];
								
				//find user image
				$results['userpic'] = $review['userpic'];
				
				if($review['from_url_review']!=''){
					$tempfrom_url_review=$review['from_url_review'];
				} else {
					$tempfrom_url_review=$orgreviewurlvalue;
				}
				
				
					$updatedtimestring = date( "Y-m-d H:i:s", $results[$x]['created_time_stamp']  );
					
					$from_url_val = $checkdetails[$tempkey]['googleurl']; 
					
					$reviewsarray[] = [
					 'reviewer_name' => $results[$x]['user_name'],
					 'reviewer_id' => $results[$x]['reviewer_id'],
					 'reviewer_email' => '',
					 'userpic' => $results['userpic'],
					 'rating' => $results[$x]['rating'],
					 'updated' => $updatedtimestring,
					 'review_text' => $results[$x]['review_text'],
					 'review_title' => '',
					 'from_url' => $orgreviewurlvalue,
					 'from_url_review' => $tempfrom_url_review,
					 'language_code' => '',
					 'location' => '',
					 'recommendation_type' => '',
					 'company_title' =>  '',
					 'company_url' => '',
					 'company_name' => '',
					 'mediaurlsarrayjson' => $results[$x]['mediaurlsarrayjson'],
					 'meta_data' => $results[$x]['meta_data'],
					 'owner_response' => $results[$x]['owner_response']
					 ];
				
				
				$x++;
			}

			$result['reviews'] = $reviewsarray;
			
			
			//only calling once since this is google.
			$result['stoploop'] ='stop';
			
			//print_r($result);
		
		return $result;
	}
	
	public function wprp_browser_curl($url){

		$ch=curl_init($url);
		curl_setopt_array($ch,array(
				CURLOPT_USERAGENT=>'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:60.0) Gecko/20100101 Firefox/60.0',
				CURLOPT_ENCODING=>'gzip, deflate',
				CURLOPT_HTTPHEADER=>array(
						'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
						'Accept-Language: en-US,en;q=0.5',
						'Connection: keep-alive',
						'Upgrade-Insecure-Requests: 1',
				),
		));

		$result=curl_exec($ch);
		
		return $result;
	}
	
	//for downloading TripAdvisor reviews using local server.
	public function wprp_getapps_getrevs_page_tripadvisorlocal($type,$listedurl,$pagenum,$perpage,$savedpageid,$nhful,$fid,$blockstoinsert,$nextpageurl,$iscron,$crawlserver){
		$result['ack']='success';
		set_time_limit(120);
		ini_set('memory_limit','400M');
		$errormsg='';
		$reviewsarraytemp = Array();
		$totalreviews = '';
		$avgrating = '';
		$result['total']='';
		$result['avg']='';
		$currenturl = $listedurl;
		$nexturl = '';
		$vactionrental = false;
		$attractionreviews=false;
		
		if($pagenum>1 && $nextpageurl!=''){
			$currenturl = $nextpageurl;
		}
		
		$parseurl = parse_url($listedurl);
		$baseurl = $parseurl['scheme'].'://'.$parseurl['host'];

		$attractionproductreviews=false;
		$vactionrental = false;
		$showuserreviewpage=false;
		$orgregularpage = false;
		$hotelreview = false;
		$attractionreviews=false;
		$usephantomsimple = false;
		
		if (strpos($currenturl, 'VacationRentalReview') !== false) {
			//this is a vactionrental
			$vactionrental = true;
			$usephantomsimple = true;
		} else if (strpos($currenturl, 'ShowUserReviews') !== false) {
			$showuserreviewpage=true;
		} else if (strpos($currenturl, 'Hotel_Review') !== false) {
			$hotelreview = true;
		} else if (strpos($currenturl, 'Attraction_Review') !== false) {
			$attractionreviews=true;
		} else if (strpos($currenturl, 'AttractionProductReview') !== false) {
			$attractionproductreviews=true;
		} else {
			$orgregularpage = true;
		}
		if (filter_var($listedurl, FILTER_VALIDATE_URL)) {
			
			if($pagenum==1){
				if ($showuserreviewpage === false && $attractionproductreviews === false && $attractionreviews === false ) {
					$urlarray = $this->wprevpro_download_tripadvisor_showuserreviews_url($currenturl);
					$currenturl =$urlarray['page1'];
					$nexturl =$urlarray['page2'];
					$totalreviews = $urlarray['totalreviews'];
					$avgrating = $urlarray['avgrating'];
					sleep(1);
					$showuserreviewpage=true;
					//print_r($urlarray);
				}
			}

			//loop to grab pages
			$reviews = [];
			$reviewobject = [];
			$n=1;
			//foreach ($tripadvisorurl as $urlvalue) {
			$urlvalue = $currenturl;
			$localorphantom = "local";
			$phatomtry = 1;
			phantom:
			
			if($phatomtry > 1){
				$apitoken = 'a-demo-key-with-low-quota-per-ip-address';
				$phantomtempurlvalue = "https://phantomjscloud.com/api/browser/v2/".$apitoken."/?request={url:%22".urlencode($urlvalue)."%22,renderType:%22html%22,requestSettings:{doneWhen:[{event:%22domReady%22}]}}";
			}

				$args = array(
					'timeout'     => 60,
					'sslverify' => false
				); 
				
				if($localorphantom =="local"){
					//echo "local";
						//echo $urlvalue;
					$response = wp_remote_get( $urlvalue,$args );
					$results['urlvalue'] =$urlvalue;
					
					//$fileurlcontents = $this->wprp_browser_curl($urlvalue);
					
					//echo $fileurlcontents;
					//die();
				} else {
					//echo "phatnom";
						//echo $phantomtempurlvalue;
					$response = wp_remote_get( $phantomtempurlvalue,$args );
					$results['urlvalue'] =$phantomtempurlvalue;
				}
				if ( is_array( $response ) ) {
				  $header = $response['headers']; // array of http header lines
				  $fileurlcontents = $response['body']; // use the content
				} else {
						if($phatomtry<2){
							$phatomtry = 2;
							$localorphantom="phantom";
							//goto phantom;
						}
						$results['ack'] ='error';
						$results['ackmsg'] = esc_html__('Error 103a: Unable to read TripAdvisor page. Please contact support, the Review Funnel page, or change the crawl server to Remote.', 'wp-review-slider-pro');
						$results = json_encode($results);
						echo $results;
						die();
				}
				
				
				//fix for lazy load base64 ""
				//$fileurlcontents = str_replace('=="', '', $fileurlcontents);
				
				$html = wppro_str_get_html($fileurlcontents);
				
				//echo $fileurlcontents;
				//die();
					if($attractionproductreviews || $attractionreviews ){
						
						if($html->find('div.LbPSX', 0)){
							//another attraction review
							$reviewcontainerdiv = $html->find('div[data-automation=reviewCard]');
						
						}

						//go ahead and look for next page url.
						$nextlinkgrab = '';
						if($pagenum==1){
							if($html->find("a[class=BrOJk u j z _F wSSLS tIqAi iNBVo]", 0)){
								$nextlinkgrab = $html->find("a[class=BrOJk u j z _F wSSLS tIqAi unMkR]", 0)->href;
							}
						} else {
							if($html->find("a[class=BrOJk u j z _F wSSLS tIqAi iNBVo]", 1)){
								$nextlinkgrab = $html->find("a[class=BrOJk u j z _F wSSLS tIqAi unMkR]", 1)->href;
							}
						}
						
						

						if($nextlinkgrab!=''){
						$nexturl = $parseurl['scheme'].'://'.$parseurl['host'].$nextlinkgrab;
						}
							
					} else {
						if($html->find('div.reviewSelector', 0)){
							$reviewcontainerdiv = $html->find('div.reviewSelector');
							//echo "here1";
						} else if($html->find('div.review-container', 0)){
							$reviewcontainerdiv = $html->find('div.review-container');
							//echo "here2";
						} else if($html->find('div._1c8_1ITO', 0)){
							//print_r($html->find('div._1c8_1ITO', 0));
							$reviewcontainerdiv = $html->find('div._1c8_1ITO', 0)->children;
						} else if($html->find("div[class=Dq9MAugU T870kzTX LnVzGwUB]",0)){
							//echo "here3";
								//another attraction review
								$reviewcontainerdiv = $html->find("div[class=Dq9MAugU T870kzTX LnVzGwUB]");
						} else if($html->find('div[class=eVykL Gi z cPeBe MD cwpFC]',0)){
								//another attraction review
								//eVykL Gi z cPeBe MD cwpFC
								//echo "here4";
								$reviewcontainerdiv = $html->find('div[class=eVykL Gi z cPeBe MD cwpFC]');
						} else if($html->find('div.bPhtn', 0)){
								//another attraction review
								$reviewcontainerdiv = $html->find('div.bPhtn', 0)->children;
							
						} else if($html->find('div.dHjBB', 0)){
								//another attraction review
								$reviewcontainerdiv = $html->find('div.dHjBB', 0)->children;
						} else if($html->find('div.LbPSX', 0)){
								//another attraction review
								$reviewcontainerdiv = $html->find('div.LbPSX', 0)->children;
						}
						
						//if this is page 1 check to see if on first page of tripadvisor.
						//echo $html;
						//die();
						//echo "pagenum:".$pagenum;
						$onfirstpage ='';
						if($pagenum==1){
							//check if we are not on first page
							if($html->find('a[class=pageNum first current]', 0)){
								$onfirstpage = 'yes';
							} else if($html->find('a[class=pageNum first]', 0)){
								$onfirstpage = 'no';
								$rtitlelink = $html->find('a[class=pageNum first]', 0)->href;
								$nexturl = $parseurl['scheme'].'://'.$parseurl['host'].$nextlinkgrab;
							}
						}
						//echo "nexturl:".$nexturl;
						//echo "onfirstpage:".$onfirstpage;
						//die();
						
					}
					
					if(!isset($reviewcontainerdiv) || count($reviewcontainerdiv)<1){
						if($phatomtry<3){
							$phatomtry = 3;
							$localorphantom="phantom";
							goto phantom;
						}
					}
					if(!isset($reviewcontainerdiv) || count($reviewcontainerdiv)<1){
						$results['ack'] ='error';
						$results['ackmsg'] = esc_html__('Error 103b: Unable to read TripAdvisor page. Please change the crawl server to Remote, or use the Review Funnel page. If you still have trouble contact support.', 'wp-review-slider-pro');
						$results = json_encode($results);
						echo $results;
						die();
					}
					
				
				//in case we couldn't get it from first page search string.
				if($pagenum==1){
					if($totalreviews<1 || $avgrating<1){
						$avgtotalarray = $this->tripadvisorgettotalavg($html,"");
						//print_r($avgtotalarray);
						$totalreviews = $avgtotalarray['totalreviews'];
						$avgrating = $avgtotalarray['avgrating'];
					}
					
					//if they aren't find try getting them from the original listed url.
					if($totalreviews<1 || $avgrating<1){
						//echo "<br>getting from listedurl<br>";
						$avgtotalarray = $this->tripadvisorgettotalavg("",$listedurl);
						$totalreviews = $avgtotalarray['totalreviews'];
						$avgrating = $avgtotalarray['avgrating'];
						//print_r($avgtotalarray);
					}
				}
				if($totalreviews>0){
					$result['total']=$totalreviews;
				}
				if($avgrating>0){
					$result['avg']=$avgrating;
				}
				
				//for user avatars
				$startstringpos = stripos("$html","var lazyImgs = [") + 16;
				$choppedstr = substr("$html", $startstringpos);
				$endstringpos = stripos("$choppedstr","]");
				$finalstring = trim(substr("$html", $startstringpos, $endstringpos));
				$finalstring =str_replace(":true",':"true"',$finalstring);
				$finalstring ="[".str_replace(":false",':"false"',$finalstring)."]";
				$jsonlazyimg  = json_decode($finalstring, true);

				// Find 20 reviews
				$i = 1;
				
				
				foreach ($reviewcontainerdiv as $review) {
					
					//echo $review->dump();
				//	die();
					
						if ($i > 21) {
								break;
						}
						$user_name='';
						$userimage='';
						$rating='';
						$datesubmitted='';
						$rtext='';
						// Find user_name
						if($review->find('div.username', 0)){
							$user_name = $review->find('div.username', 0)->plaintext;
						}
						if($user_name==''){
							if($review->find('div.info_text', 0)){
								$user_name = $review->find('div.info_text', 0)->find('div', 0)->plaintext;
							}
						}
						//for attraction
						if($user_name==''){
							if($review->find("span[class=DrjyGw-P _1SRa-qNz NGv7A1lw _2yS548m8 _2cnjB3re _1TAWSgm1 _1Z1zA2gh _2-K8UW3T _2AAjjcx8]", 0)){
								$user_name = $review->find("span[class=DrjyGw-P _1SRa-qNz NGv7A1lw _2yS548m8 _2cnjB3re _1TAWSgm1 _1Z1zA2gh _2-K8UW3T _2AAjjcx8]", 0)->plaintext;
							}
						}
						//for attraction 2
						if($user_name==''){
							if($review->find("a[class=ui_header_link _1r_My98y]", 0)){
								$user_name = $review->find("a[class=ui_header_link _1r_My98y]", 0)->plaintext;
							}
						}
						if($user_name==''){
							if($review->find("a[class=ui_header_link bPvDb]", 0)){
								$user_name = $review->find("a[class=ui_header_link bPvDb]", 0)->plaintext;
							}
						}
						//for attraction 2
						if($user_name==''){
							if($review->find("a[class=iPqaD _F G- ddFHE eKwUx btBEK fUpii]", 0)){
								$user_name = $review->find("a[class=iPqaD _F G- ddFHE eKwUx btBEK fUpii]", 0)->plaintext;
							}
						}
						//for attraction 2 biGQs _P fiohW fOtGX
						if($user_name==''){
							if($review->find("span[class=biGQs _P fiohW fOtGX]", 0)){
								$user_name = $review->find("span[class=biGQs _P fiohW fOtGX]", 0)->plaintext;
							}
						}
				
							
							
							// Find userimage ui_avatar, need to pull from lazy load varible
							if($review->find('div.ui_avatar', 0)){
								if($review->find('div.ui_avatar', 0)->find('img.basicImg', 0)){
									$userimageid = $review->find('div.ui_avatar', 0)->find('img.basicImg', 0)->id;
									//strip id from 
									$userimageid = strrchr ($userimageid , "_" );
									//loop through array and return url
									if (isset($jsonlazyimg) && is_array($jsonlazyimg)){
									for ($x = 0; $x <= count($jsonlazyimg); $x++) {
										//get temp id
										$tempid = $jsonlazyimg[$x]['id'];
										$tempid = strrchr ($tempid , "_" );
										if($userimageid==$tempid){
											$userimage = $jsonlazyimg[$x]['data'];
											$x = count($jsonlazyimg) + 1;
										}
									} 
									}
								}
							}

							//if userimage not found check
							$checkstringpos =  strpos($userimage, 'base64');
							if($userimage =='' || $checkstringpos>0){
								if($review->find('div.ui_avatar', 0)){
									if($review->find('div.ui_avatar', 0)->find('img.basicImg', 0)){
										if($review->find('div.ui_avatar', 0)->find('img.basicImg', 0)->{'data-lazyurl'}){
											$userimage =$review->find('div.ui_avatar', 0)->find('img.basicImg', 0)->{'data-lazyurl'};
										} else {
											$userimage =$review->find('div.ui_avatar', 0)->find('img.basicImg', 0)->src;
										}
									}
								}
								
							}
							//echo "<br>userimage2:".$userimage;
							//another fix
							if($userimage ==''){
								if($review->find('div.avatar', 0)){
									if($review->find('div.avatar', 0)->find('img.avatar', 0)){
										$userimage =$review->find('div.avatar', 0)->find('img.avatar', 0)->{'src'};
									}
								}
							}
							//echo "<br>userimage3:".$userimage;
							//one more try for hotels
							if($userimage =='' && $review->find('div.ui_avatar', 0)){
								if($review->find('div.ui_avatar', 0)->find('img.basicImg', 0)){
										$userimage =$review->find('div.ui_avatar', 0)->find('img.basicImg', 0)->src;
								}
							}
							//echo "<br>userimage4:".$userimage;
							//if userimage not found check
							if($userimage =='' && $review->find('div.ui_avatar', 0)){
								if($review->find('div.ui_avatar', 0)->find('img.basicImg', 0)){
									if($review->find('div.ui_avatar', 0)->find('img.basicImg', 0)->{'data-lazyurl'}){
										$userimage =$review->find('div.ui_avatar', 0)->find('img.basicImg', 0)->{'data-lazyurl'};
									}
								}
								//echo "<br>userimage:".$userimage;
							}
							//echo "<br>userimage5:".$userimage;
							//another check for activities userimage
							if($userimage =='' && $review->find('picture._2f-Th360', 0)){
								if($review->find('picture._2f-Th360', 0)->find('img', 0)){
										$userimage =$review->find('picture._2f-Th360', 0)->find('img', 0)->src;
								}
							}
							//echo "<br>userimage6:".$userimage;
							//another check for activities userimage
							if($userimage =='' && $review->find("a[class=_3x5_awTA ui_social_avatar inline]", 0)){
								if($review->find("a[class=_3x5_awTA ui_social_avatar inline]", 0)->find('img', 0)){
										$userimage =$review->find("a[class=_3x5_awTA ui_social_avatar inline]", 0)->find('img', 0)->src;
								}
							}
							//echo "<br>userimage7:".$userimage;
							//another check for activities userimage
							if($userimage =='' && $review->find("a[class=ui_social_avatar inline]", 0)){
								if($review->find("a[class=ui_social_avatar inline]", 0)->find('img', 0)){
										$userimage =$review->find("a[class=ui_social_avatar inline]", 0)->find('img', 0)->src;
								}
							}
							//echo "<br>userimage8:".$userimage;
							//bugwz I ui_social_avatar inline
							if($userimage =='' && $review->find("a[class=bugwz I ui_social_avatar inline]", 0)){
								if($review->find("a[class=bugwz I ui_social_avatar inline]", 0)->find('img', 0)){
										$userimage =$review->find("a[class=bugwz I ui_social_avatar inline]", 0)->find('img', 0)->src;
								}
							}
							//echo "<br>userimage9:".$userimage;
							//another check for activities userimage
							if($userimage =='' && $review->find("a[class=iPqaD _F G- ddFHE eKwUx]", 0)){
								if($review->find("a[class=iPqaD _F G- ddFHE eKwUx]", 0)->find('img', 0)){
										$userimage =$review->find("a[class=iPqaD _F G- ddFHE eKwUx]", 0)->find('img', 0)->src;
								}
							}
							//echo "<br>userimage10:".$userimage;
							//another check for activities userimage
							if($userimage =='' && $review->find("div[class=FGwzt PaRlG]", 0)){
								if($review->find("div[class=FGwzt PaRlG]", 0)->find('img', 0)){
										$userimage =$review->find("div[class=FGwzt PaRlG]", 0)->find('img', 0)->src;
								}
							}



						// find rating
						if($review->find('span.ui_bubble_rating', 0)){
							$temprating = $review->find('span.ui_bubble_rating', 0)->class;
							$int = filter_var($temprating, FILTER_SANITIZE_NUMBER_INT);
							//echo $int."<br>";
							$rating = str_replace(0,"",$int);
						}
						//rating for activities
						$temprating ='';
						if($rating ==''){
							if($review->find('.zWXXYhVR', 0)){
							$temprating = $review->find('.zWXXYhVR', 0)->title;
							} else if($review->find("svg[class=RWYkj d H0]", 0)){
							$temprating = $review->find("svg[class=RWYkj d H0]", 0)->title;	
							} else if($review->find("svg[class=UctUV d H0]", 0)){
							$temprating = $review->find("svg[class=UctUV d H0]", 0)->getAttribute('aria-label');
							}
							if($temprating==''){
									if($review->find("svg[class=UctUV d H0]", 0)){
										if($review->find("svg[class=UctUV d H0]", 0)->find("title", 0)){
											$temprating = $review->find("svg[class=UctUV d H0]", 0)->find("title", 0)->plaintext;
										}
									}
								}
							$temprating = str_replace('.0 of 5 bubbles',"",$temprating);
							$temprating = str_replace('Punteggio ',"",$temprating);
							$temprating = str_replace(',0 su 5',"",$temprating);
							$temprating = str_replace(',0 sur 5',"",$temprating);
							$rating = filter_var($temprating, FILTER_SANITIZE_NUMBER_INT);
							$rating = $rating[0];
						}
						
						if($rating ==''){
							$temprating = $review->find('span.ui_star_rating', 0)->class;
							$int = filter_var($temprating, FILTER_SANITIZE_NUMBER_INT);
							//echo $int."<br>";
							$rating = str_replace(0,"",$int);
						}
						
						// find date
						if($review->find('span.ratingDate', 0)){
							//if($vactionrental==false){
							//	$datesubmitted = $review->find('span.ratingDate', 0)->title;
							//	$datesubmitted = preg_replace("(<([a-z]+)>.*?</\\1>)is","",$datesubmitted);
							//} else {
								if($review->find('span.ratingDate', 0)->title){
									$datesubmitted = $review->find('span.ratingDate', 0)->title;
									$datesubmitted = preg_replace("(<([a-z]+)>.*?</\\1>)is","",$datesubmitted);
								} else {
								$datesubmitted = $review->find('span.ratingDate', 0)->innertext;
								$datesubmitted = preg_replace("(<([a-z]+)>.*?</\\1>)is","",$datesubmitted);
								$datesubmitted = str_replace("Reviewed","",$datesubmitted);
								$datesubmitted = str_replace("Beoordeeld","",$datesubmitted);
								$datesubmitted = str_replace("op","",$datesubmitted);
								$datesubmitted = str_replace('Recensito il',"",$datesubmitted);
								
								//$datesubmitted = date('d-m-Y H:i:s', strtotime($datesubmitted, 1324189035));
								}
							//}
						}
						//for activities 2 _2fxQ4TOx
						if($datesubmitted ==''){
							if($review->find("div[class=_2fxQ4TOx]", 0)){
								$datesubmitted = $review->find("div[class=_2fxQ4TOx]", 0)->plaintext;
							}
						}
						
						//for activities 2
						if($datesubmitted ==''){
							if($review->find("span[class=_34Xs-BQm]", 0)){
								$datesubmitted = $review->find("span[class=_34Xs-BQm]", 0)->plaintext;
							}
						}
						//for activities 2 DrjyGw-P _26S7gyB4 _1z-B2F-n _1dimhEoy
						if($datesubmitted ==''){
							if($review->find("div[class=DrjyGw-P _26S7gyB4 _1z-B2F-n _1dimhEoy]", 0)){
								$datesubmitted = $review->find("div[class=DrjyGw-P _26S7gyB4 _1z-B2F-n _1dimhEoy]", 0)->plaintext;
							}
						}
						//for activities 2 euPKI _R Me S4 H3
						if($datesubmitted ==''){
							if($review->find("span[class=euPKI _R Me S4 H3]", 0)){
								$datesubmitted = $review->find("span[class=euPKI _R Me S4 H3]", 0)->plaintext;
							}
						}
						//for activities
						if($datesubmitted ==''){
							if($review->find("div[class=WlYyy diXIH cspKb bQCoY]", 0)){
								$datesubmitted = $review->find("div[class=WlYyy diXIH cspKb bQCoY]", 0)->plaintext;
							}
						}
						//for activities italian
						if($datesubmitted ==''){
							if($review->find("div[class=bcaHz]", 0)){
								$datesubmitted = $review->find("div[class=bcaHz]", 0)->plaintext;
							}
						}
						//for activities  biGQs _P pZUbB ncFvv osNWb
						if($datesubmitted ==''){
							if($review->find("div[class=biGQs _P pZUbB ncFvv osNWb]", 0)){
								$datesubmitted = $review->find("div[class=biGQs _P pZUbB ncFvv osNWb]", 0)->plaintext;
							}
						}
						
						//echo 'date:'.$datesubmitted;
						$datesubmitted = $this->formatdatestring($datesubmitted,$user_name);
						//echo 'dateformat:'.$datesubmitted;
						//$timestamp = $this->myStrtotime($datesubmitted);
						//echo "---<br>".$timestamp;
						//die();
						
						// find text
						$rtext='';
						//if($vactionrental==false){
							if($review->find('div.prw_reviews_text_summary_hsx', 0)){
								$rtext = $review->find('div.prw_reviews_text_summary_hsx', 0)->find('p', 0)->plaintext;
							}
							//if this is the first review then handle differently, it is at top of showuserreview page
							if($i==1){
								if($review->find('div.prw_reviews_resp_sur_review_text', 0)){
									//echo 'here1';
									$rtext = $review->find('div.prw_reviews_resp_sur_review_text', 0)->find('p', 0)->plaintext;
								} else if($review->find('div.prw_reviews_resp_sur_review_text_expanded', 0)){
									$rtext = $review->find('div.prw_reviews_resp_sur_review_text_expanded', 0)->find('p', 0)->plaintext;
									//echo 'here2';
									//echo 'rtext-'.$rtext;
									//print_r($review->find('div.prw_reviews_resp_sur_review_text_expanded', 0)->find('p', 0));
								}
								
								
							}
						//} else {
						//	if($review->find('div.entry', 0)){
						//		$rtext = $review->find('div.entry', 0)->find('p', 0)->plaintext;
						//	}
						//}
						//if rtext is blank try one more time, used to get top review on hotels
						if($rtext==''){
							if($review->find('div.entry', 0)){
								$rtext = $review->find('div.entry', 0)->find('p', 0)->plaintext;
							}
						}
						//try again for activities
						if($rtext==''){
							if($review->find("div[class=DrjyGw-P _26S7gyB4 _2nPM5Opx]", 0)){
								$rtext = $review->find("div[class=DrjyGw-P _26S7gyB4 _2nPM5Opx]", 0)->plaintext;
							}
						}
						//try again for activities
						if($rtext==''){
							if($review->find("div[class=cPQsENeY]", 0)){
								$rtext = $review->find("div[class=cPQsENeY]", 0)->plaintext;
							}
						}
						//try again for activities
						if($rtext==''){
							if($review->find("q[class=XllAv H4 _a]", 0)){
								$rtext = $review->find("q[class=XllAv H4 _a]", 0)->plaintext;
								$rtext = mb_substr($rtext, 0, -10);
								$rtext = str_replace("…","",$rtext);
							}
						}
						//try again for activities
						if($rtext==''){
							if($review->find("div[class=WlYyy diXIH dDKKM]", 0)){
								$rtext = $review->find("div[class=WlYyy diXIH dDKKM]", 0)->plaintext;
								$rtext = mb_substr($rtext, 0, -10);
								$rtext = str_replace("…","",$rtext);
							}
						}
						//try again for activities
						if($rtext==''){
							if($review->find("div[class=biGQs _P pZUbB KxBGd]", 0)){
								$rtext = $review->find("div[class=biGQs _P pZUbB KxBGd]", 0)->plaintext;
								//$rtext = mb_substr($rtext, 0, -10);
								$rtext = str_replace("…","",$rtext);
							}
						}
						
						$rtext =str_replace("...More","...",$rtext);
						
						$rtext = trim($rtext);

						//find title if set on options page
						//-may be used in future version--------
							$rtitle = '';
							if($vactionrental==false){
								if($review->find('span.noQuotes', 0)){
									$rtitle = $review->find('span.noQuotes', 0)->plaintext;
								}
							} else {
								if($review->find('div.quote', 0)){
									$rtitle = $review->find('div.quote', 0)->plaintext;
								}									
							}
							//if rtitle
							if($rtitle==''){
								if($review->find('div.quote', 0)){
									$rtitle = $review->find('div.quote', 0)->plaintext;
								}
							}
							//for activity
							if($rtitle==''){
								if($review->find('span._2tsgCuqy', 0)){
									$rtitle = $review->find('span._2tsgCuqy', 0)->plaintext;
								}
							}
							//for activity
							if($rtitle==''){
								if($review->find('div.glasR4aX', 0)){
									$rtitle = $review->find('div.glasR4aX', 0)->plaintext;
								}
							}
							//fpMxB MC _S b S6 H5 _a
							if($rtitle==''){
								if($review->find("div[class=fpMxB MC _S b S6 H5 _a]", 0)){
									$rtitle = $review->find("div[class=fpMxB MC _S b S6 H5 _a]", 0)->plaintext;
								}
							}
							if($rtitle==''){
								if($review->find("div[class=WlYyy cPsXC bLFSo cspKb dTqpp]", 0)){
									$rtitle = $review->find("div[class=WlYyy cPsXC bLFSo cspKb dTqpp]", 0)->plaintext;
								}
							}
							if($rtitle==''){
								if($review->find("div[class=biGQs _P fiohW qWPrE ncFvv fOtGX]", 0)){
									$rtitle = $review->find("div[class=biGQs _P fiohW qWPrE ncFvv fOtGX]", 0)->plaintext;
								}
							}
							
							
						//-----------------------------------
						$fromlink = $listedurl;
						
						if($attractionproductreviews){

							
						} else {
						
							if($review->find('div.quote', 0)){
								if($review->find('div.quote', 0)->find('a',0)){
								$fromlinkgrab = $review->find('div.quote', 0)->find('a',0)->href;
								$parseurl = parse_url($urlvalue);
								$fromlink= $parseurl['scheme'].'://'.$parseurl['host'].$fromlinkgrab;
									if($showuserreviewpage && $pagenum>1){
										$nexturl = $fromlink;
									}
								} else {
								$fromlink = $listedurl;
								}
							} else {
								$fromlink = $listedurl;
							}
						}
						
						if($rating>0 && $rtext!=''){
			
							$review_length = substr_count($rtext, ' ');

							$pos = strpos($userimage, 'default_avatars');
							if ($pos === false) {
								$userimage = str_replace("60s.jpg","120s.jpg",$userimage);
							}

							//echo "<br>".$datesubmitted;
							$timestamp = $this->myStrtotime($datesubmitted);
							//echo "---<br>".$timestamp;
							//die();
							$unixtimestamp = $timestamp;
							$timestamp = date("Y-m-d H:i:s", $timestamp);
							$hideme = 'no';
							
							$review_length = str_word_count($rtext);
							if (extension_loaded('mbstring')) {
								$review_length_char = mb_strlen($rtext);
							} else {
								$review_length_char = strlen($rtext);
							}
							if($review_length_char>0 && $review_length<1){
								$review_length = 1;
							}
							
							//mediaurlsarrayjson, max of 8 images
							//--------------
							$mediaurlsarrayjson='';
							$mediaurlsarray = Array();
							
							if($attractionproductreviews || $attractionreviews){
								if($review->find('button[class=ajoIU _S B-]',0)){
									//have at least one image
									$imagesobject = $review->find('button[class=ajoIU _S B-]');
								} else if($review->find('button[class=UikNM _G B- _S _W _T c G_ wSSLS wnNQG]',0)){
									$imagesobject = $review->find('button[class=UikNM _G B- _S _W _T c G_ wSSLS wnNQG]');
								}
								if($imagesobject){
									//echo $imagesobject[0]->find('img', 0)->src;
									//die();
									foreach ($imagesobject as $imageobj) {
										if($imageobj->find('img', 0)){
											$tempurl= $imageobj->find('img', 0)->src;
											if($tempurl!='' && strpos($tempurl, "base64") == false){
												//get bigger image.
												$imgsizes = array("w=400", "w=300", "w=200", "w=100");
												$mediaurlsarray[]=str_replace($imgsizes,"w=500",$tempurl);
											}
											if(count($mediaurlsarray)>7){
												break;
											}
											$mediaimg='';
											$tempurl='';
										}
									}
									$mediaurlsarrayjson = json_encode($mediaurlsarray);
									unset($mediaurlsarray);
								}
							} else {
							
								if($review->find('div[class=photoContainer]',0)){
									//have at least one image
									$imagesobject = $review->find('div[class=photoContainer]');
									foreach ($imagesobject as $imageobj) {
										if($imageobj->find('img', 1)){
											$tempurl= $imageobj->find('img', 1)->src;
											if($tempurl!='' && strpos($tempurl, "base64") == false){
												//get bigger image.
												$mediaurlsarray[]=str_replace("/photo-l/","/photo-s/",$tempurl);
											}
											if(count($mediaurlsarray)>7){
												break;
											}
											$mediaimg='';
											$tempurl='';
										} else if($imageobj->find('img', 0)){
											$tempurl= $imageobj->find('img', 0)->src;
											if($tempurl!='' && strpos($tempurl, "base64") == false){
												//get bigger image.
												$mediaurlsarray[]=str_replace("/photo-l/","/photo-s/",$tempurl);
											}
											if(count($mediaurlsarray)>7){
												break;
											}
											$mediaimg='';
											$tempurl='';
										}
									}
									$mediaurlsarrayjson = json_encode($mediaurlsarray);
									unset($mediaurlsarray);
								}
							}
							//look for owner response
							//owner_response {"id":71320417,"name":"Response from the owner","date":"2020-06-05","comment":"Thank You will Matsch "}
							$owner_response_encode ='';
							$owner['id'] = '';
							$owner['name'] = '';
							$owner['comment'] = '';
							$owner['date'] = '';
							//mgrRspnInline
							if($attractionproductreviews || $attractionreviews){
								if($review->find('div[class=hjJJO PJ]',0)){
									//must be a response, look for details.
									$responsediv = $review->find('div[class=hjJJO PJ]',0);

									if($responsediv->find('span[class=biGQs _P fiohW fOtGX]', 0)){
										$owner['name']= $responsediv->find('span[class=biGQs _P fiohW fOtGX]', 0)->plaintext;
									} else {
										$owner['name'] = 'Response from the owner';
									}
									
									if($responsediv->find('div[class=biGQs _P pZUbB xUqsL ncFvv osNWb]', 0)){
										//responseDate
										$tempresdate = $responsediv->find('div[class=biGQs _P pZUbB xUqsL ncFvv osNWb]', 0)->plaintext;
										$tempdate = $this->formatdatestring($tempresdate,'');
										$tempdate = $this->myStrtotime($tempdate);
										if($tempdate==''){
											//use review date.
											$tempdate = $unixtimestamp;
										}
										$owner['date'] = date('Y-m-d', $tempdate);
									}
									if($responsediv->find('span[class=JguWG]', 0)){
										$owner['comment']= $responsediv->find('span[class=JguWG]', 0)->plaintext;
									}
								}
							} else {
								if($review->find('div[class=mgrRspnInline]',0)){
									//must be a response, look for details.
									$responsediv = $review->find('div[class=mgrRspnInline]',0);
									if($responsediv->find('div[class=prw_rup prw_reviews_response_header]', 0)){
										$owner['name']= $responsediv->find('div[class=prw_rup prw_reviews_response_header]', 0)->plaintext;
										//need to subtract date.
										if($responsediv->find('span[class=responseDate]', 0)){
											$tempresdate = $responsediv->find('span[class=responseDate]', 0)->plaintext;
											$owner['name'] = str_replace($tempresdate,"",$owner['name']);
										}
									} else {
										$owner['name'] = 'Response from the owner';
									}
									if($responsediv->find('span[class=responseDate]', 0)){
										//responseDate
										$tempresdate = $responsediv->find('span[class=responseDate]', 0)->plaintext;
										$tempdate = $this->formatdatestring($tempresdate,'');
										$tempdate = $this->myStrtotime($tempdate);
										if($tempdate==''){
											//use review date.
											$tempdate = $unixtimestamp;
										}
										$owner['date'] = date('Y-m-d', $tempdate);
									}
									if($responsediv->find('p[class=partial_entry]', 0)){
										$owner['comment']= $responsediv->find('p[class=partial_entry]', 0)->plaintext;
									}
								}
							}
							if($owner['comment']!=''){
								$owner_response_encode = json_encode($owner);
							}
							//end owner response

		

							//check to see if in database already
							$reviewindb = 'no';
							$user_name = trim($user_name);
							$reviews[] = [
									'reviewer_name' => $user_name,
									'pagename' => '',
									'pageid' => '',
									'userpic' => $userimage,
									'rating' => $rating,
									'created_time' => $timestamp,
									'created_time_stamp' => $unixtimestamp,
									'review_text' => trim($rtext),
									'review_title' => trim($rtitle),
									'hide' => $hideme,
									'type' => 'TripAdvisor',
									'from_url' => trim($listedurl),
									'from_url_review' => $fromlink,
									'review_length_char' => $review_length_char,
									'mediaurlsarrayjson' => $mediaurlsarrayjson,
									'owner_response' => $owner_response_encode,
							];

						}
				 
						$i++;
				}
				
				$n++;
				
				//var_dump($reviews);
				// clean up memory

			//}
			 
			// print_r($reviews);
			// die();
			 //echo "<br>";

			$reviews = array_unique($reviews, SORT_REGULAR);
			//$tempArr = array_unique(array_column($reviews, 'value'));
			//$reviews = array_intersect_key($array, $tempArr);

			//remove duplicates
			$reviewernames = [];
			$reviewlength = [];
			$insertreviews = [];
			foreach ( $reviews as $stat ){
				if (!in_array($stat['reviewer_name'], $reviewernames) || !in_array($stat['review_length_char'], $reviewlength)) {
					$insertreviews[] = $stat;
				}
				$reviewernames[] = $stat['reviewer_name'];
				$reviewlength[] = $stat['review_length_char'];
			}
			

			$x=0;
			$reviewsarray= Array();
			$numreturned = count($insertreviews);
			
			foreach ($insertreviews as $review) {
				
				$tempownerres='';
				if(isset($review['owner_response']) && $review['owner_response']!=''){
					$tempownerres = $review['owner_response'];
				}
				$templocation ='';
				if(isset($review['location']) && $review['location']!=''){
					$templocation = $review['location'];
				}	
				$tempmediaurlsarrayjson ='';
				if(isset($review['mediaurlsarrayjson']) && $review['mediaurlsarrayjson']!=''){
					$tempmediaurlsarrayjson = $review['mediaurlsarrayjson'];
				}					
				
				$reviewsarray[] = [
				 'reviewer_name' => $review['reviewer_name'],
				 'reviewer_id' => '',
				 'reviewer_email' => '',
				 'userpic' => $review['userpic'],
				 'rating' => $review['rating'],
				 'updated' => $review['created_time'],
				 'review_text' => $review['review_text'],
				 'review_title' => $review['review_title'],
				 'from_url' => $listedurl,
				 'from_url_review' => $review['from_url_review'],
				 'language_code' => '',
				 'location' => $templocation,
				 'recommendation_type' => '',
				 'company_title' =>  '',
				 'company_url' => '',
				 'company_name' => '',
				 'mediaurlsarrayjson' => $tempmediaurlsarrayjson,
				 'owner_response' => $tempownerres,
				 ];
				
				$x++;
			}

			
			
			//pass back URL used
			if(isset($urlvalue)){
				$result['callurl']=$urlvalue;
			}
			//pass back next URL used
			if(isset($nexturl) && $nexturl!=''){
				$result['nextpageurl']=$nexturl;
			}
			
			if (!empty($html)) {
				$html->clear();
				unset($html);
			}
				
			//pass back stoploop if set
			$result['stoploop']='';
			
			$result['crawlserver'] ='local';
			
			$result['reviews'] = $reviewsarray;
			
		}
			
		return $result;
	}
	
	
	//for calling remote get and returning array of reviews to insert, calling Crawler now crawl.ljapps.com
	public function wprp_getapps_getrevs_page_tripadvisor($type,$listedurl,$pagenum,$perpage,$savedpageid,$nhful,$fid,$blockstoinsert,$nextpageurl,$iscron){
		$result['ack']='success';
		set_time_limit(150);
			$errormsg='';
			$reviewsarraytemp = Array();
			$nhful='new';
			
			if (filter_var($listedurl, FILTER_VALIDATE_URL)) {
					
				$stripvariableurl = stripslashes($listedurl);
				$listedurl = strtok($stripvariableurl, '?');	//remove all parameters
				
				
				if(isset($_SERVER['SERVER_ADDR']) && $_SERVER['SERVER_ADDR']!=''){
					$ip_server = $_SERVER['SERVER_ADDR'];
				} else {
					//get url of site.
					$ip_server = urlencode(get_site_url());
				}
				$siteurl = urlencode(get_site_url());
				
				//scrapeurl
				$tempurlval = 'https://crawl.ljapps.com/crawlrevs?rip='.$ip_server.'&surl='.$siteurl.'&scrapeurl='.$listedurl.'&stype=tripadvisor&sfp=pro&nobot=1&nhful='.$nhful.'&locationtype=&scrapequery=&tempbusinessname=&pagenum='.$pagenum.'&nextpageurl='.$nextpageurl.'&iscron='.$iscron;
				
				//https://crawl.ljapps.com/crawlrevs?rip=127.0.0.1&surl=https%3A%2F%2Fwptest.ljapps.com&scrapeurl=https://www.tripadvisor.com/ShowUserReviews-g187371-d217169-r855068628-Hotel_Lyskirchen_Cologne-Cologne_North_Rhine_Westphalia.html&stype=tripadvisor&sfp=pro&nhful=new&locationtype=&scrapequery=&tempbusinessname=&pagenum=1&nextpageurl=&iscron=no
				
				//echo $tempurlval;
				//die();
				
				$serverresponse='';
				$args = array(
					'timeout'     => 120,
					'sslverify' => false
				); 
				$response = wp_remote_get( $tempurlval, $args );
				if ( is_array( $response ) && ! is_wp_error( $response ) ) {
					$headers = $response['headers']; // array of http header lines
					$serverresponse    = $response['body']; // use the content
				} else {
					//must have been an error
					$results['ack'] ='error';
					$results['ackmsg'] ='Error 0001a: trouble contacting crawling server with remote_get. Please try again or contact support.'.$response->get_error_message();
					$results = json_encode($results);
					echo $results;
					die();
				}
	
				$serverresponsearray = json_decode($serverresponse, true);

				if($serverresponse=='' || !is_array($serverresponsearray)){
					$results['ack'] ='error';
					$results['ackmsg'] ='Error 0001: trouble contacting crawling server. Please try again or contact support.';
					$results = json_encode($results);
					echo $results;
					die();
				}
				//catch limit error
				if($serverresponsearray['ack']=='error'){
					$results['ack'] ='error';
					$results['ackmsg'] ='Error 0002: '.$serverresponsearray['ackmessage'];
					$results = json_encode($results);
					echo $results;
					die();
				}
				if(!isset($serverresponsearray['result']) || !is_array($serverresponsearray['result'])){
					$results['ack'] ='error';
					$results['ackmsg'] ='Error 0002b: trouble finding reviews. Contact support with this error code and the search terms or place id you are using.';
					$results = json_encode($results);
					echo $results;
					die();
				}
				//catch error
				if($serverresponsearray['result']['ack']=='error'){
					$results['ack'] ='error';
					$results['ackmsg'] ='Error 0003: '.$serverresponsearray['ackmessage'].' : '.$serverresponsearray['result']['ackmsg'];
					$results = json_encode($results);
					echo $results;
					die();
				}
				//made it this far assume we have reviews.
				$crawlerresultarray = $serverresponsearray['result'];
				
				//pass back forceloop if set
				$result['forceloop']='';
				if(isset($crawlerresultarray['forceloop'])){
					$result['forceloop']=$crawlerresultarray['forceloop'];
				}
				
				if($result['forceloop']!='yes'){
					if(!isset($crawlerresultarray['reviews']) || !is_array($crawlerresultarray['reviews'])){
						$results['ack'] ='error';
						$results['ackmsg'] ='Error 0004 Trip: trouble finding reviews. Contact support with this error code and the search terms or place id you are using. ';
						$results = json_encode($results);
						echo $results;
						die();
					}
				}
				
				//need totals and avg for this place $getreviewsarray['total']
				$result['total']='';
				$result['avg']='';
				if(isset($crawlerresultarray['avg'])){
					$result['avg']=$crawlerresultarray['avg'];
				}
				if(isset($crawlerresultarray['total'])){
					$result['total']=$crawlerresultarray['total'];
				}
				
				//pass back URL used
				if(isset($crawlerresultarray['callurl'])){
					$result['callurl']=$crawlerresultarray['callurl'];
				}
				//pass back next URL used
				if(isset($crawlerresultarray['nextpageurl'])){
					$result['nextpageurl']=$crawlerresultarray['nextpageurl'];
				}
				//pass back stoploop if set
				if(isset($crawlerresultarray['stoploop'])){
					$result['stoploop']=$crawlerresultarray['stoploop'];
				}
				//pass back jumpnum if set
				$result['jumpnum']='';
				if(isset($crawlerresultarray['jumpnum'])){
					$result['jumpnum']=$crawlerresultarray['jumpnum'];
				}
				//pass back proxy if set
				$result['proxy']='';
				if(isset($crawlerresultarray['proxy'])){
					$result['proxy']=$crawlerresultarray['proxy'];
				}
				
				$x=0;
				$reviewsarray= Array();
				$crawlerreviewsarray = Array();
				if(isset($crawlerresultarray['reviews']) && is_array($crawlerresultarray['reviews'])){
					$crawlerreviewsarray = $crawlerresultarray['reviews'];
				}
				$numreturned = count($crawlerreviewsarray);
				
				foreach ($crawlerreviewsarray as $review) {
					
					$tempownerres='';
					if(isset($review['owner_response']) && $review['owner_response']!=''){
						$tempownerres = $review['owner_response'];
					}
					$templocation ='';
					if(isset($review['location']) && $review['location']!=''){
						$templocation = $review['location'];
					}	
					$tempmediaurlsarrayjson ='';
					if(isset($review['mediaurlsarrayjson']) && $review['mediaurlsarrayjson']!=''){
						$tempmediaurlsarrayjson = $review['mediaurlsarrayjson'];
					}					
					
					$reviewsarray[] = [
					 'reviewer_name' => $review['user_name'],
					 'reviewer_id' => '',
					 'reviewer_email' => '',
					 'userpic' => $review['userpic'],
					 'rating' => $review['rating'],
					 'updated' => $review['created_time'],
					 'review_text' => $review['review_text'],
					 'review_title' => $review['review_title'],
					 'from_url' => $listedurl,
					 'from_url_review' => $review['user_link'],
					 'language_code' => '',
					 'location' => $templocation,
					 'recommendation_type' => '',
					 'company_title' =>  '',
					 'company_url' => '',
					 'company_name' => '',
					 'mediaurlsarrayjson' => $tempmediaurlsarrayjson,
					 'owner_response' => $tempownerres,
					 ];
					
					$x++;
				}

			$result['reviews'] = $reviewsarray;

			}
		return $result;
	}
	
	//for calling remote get and returning array of reviews to insert, calling Crawler now crawl.ljapps.com
	public function wprp_getapps_getrevs_page_yelp_local($type,$listedurl,$pagenum,$perpage,$savedpageid,$nhful,$fid,$blockstoinsert,$nextpageurl){
		$result['ack']='success';
		
		set_time_limit(120);
		
			$errormsg='';
			$reviewsarraytemp = Array();
			$nhful='new';
			$reviewsarray= Array();
			
		if (filter_var($listedurl, FILTER_VALIDATE_URL)) {
			
			$stripvariableurl = strtok($listedurl, '?');
			$urlvalue = $stripvariableurl.'?sort_by=date_desc';
			
			$parseurl = parse_url($stripvariableurl);
			$baseurl = $parseurl['scheme'].'://'.$parseurl['host'];
			
			if($pagenum > 1){
				$pagenumtemp = $pagenum-1;
				$urlvalue = $stripvariableurl.'?start='.$pagenumtemp.'0&sort_by=date_desc';
			}
			
			if (ini_get('allow_url_fopen') == true) {
				$fileurlcontents=file_get_contents($urlvalue);
			} else if (function_exists('curl_init')) {
				$fileurlcontents=$this->file_get_contents_curl($urlvalue);
			} else {
				$fileurlcontents='<html><body>fopen is not allowed on this host.</body></html>';
				$errormsg = $errormsg . ' <p style="color: #A00;">fopen is not allowed on this host and cURL did not work either. Please ask your hosting provided to turn fopen on or fix cURL.</p>';
				$this->errormsg = $errormsg;
				die();
			}
			
			//echo $fileurlcontents;
			//die();
			
			if($pagenum==1){
				$result['total'] = $this->get_string_between($fileurlcontents, '"reviewCount":', '}');
				$result['avg'] = $this->get_string_between($fileurlcontents, '"AggregateRating","ratingValue":', ',');
				if($result['avg'] ==""){
					if($html->find('div[class=five-stars--large__09f24__Waiqf]', 0)){
						$result['avg'] = $html->find('div[class=five-stars--large__09f24__Waiqf]', 0)->{'aria-label'};
						$result['avg'] = preg_replace("/[^0-9\.]/", "",$result['avg']);
					}
				}
				if($result['total'] ==""){
					if($html->find('span[class=css-1x9ee72]', 0)){
						$result['total'] = $html->find('span[class=css-1x9ee72]', 0)->plaintext;
						$result['total'] = preg_replace("/[^0-9\.]/", "",$result['total']);
					}
				}
				$result['avg'] = str_replace('"','',$result['avg']);
				$result['total'] = str_replace('"','',$result['total']);

			}

			$html = wppro_str_get_html($fileurlcontents);
			
			
			$i = 1;
			$reviews = [];
			//print_r($html->find('div.review--with-sidebar'));
			//die();
			//look for review div.
			$reviewdivs = new stdClass();
			if($html->find('div[class=review__09f24__oHr9V]')){
				$reviewdivs = $html->find('div[class=review__09f24__oHr9V]');
			}
			if(count( (array)$reviewdivs) <1){
				if($html->find('li[class=css-1q2nwpv]')){
					$reviewdivs = $html->find('li[class=css-1q2nwpv]');
				}
			}

			
			//another change on 4/26
			if(count( (array)$reviewdivs) <1){
				if($html->find('li[class=yelp-emotion-1jp2syp]')){
					$reviewdivs = $html->find('li[class=yelp-emotion-1jp2syp]');
				}
			}
			
			//another change on 5/17  y-css-1jp2syp
					if(count( (array)$reviewdivs) <1){
						//echo "shere";
						if($html->find('li[class=y-css-1jp2syp]')){
							$reviewdivs = $html->find('li[class=y-css-1jp2syp]');
						}
					}
			
			if(count( (array)$reviewdivs) <1){
				$results['ack'] ='error';
				$results['ackmsg'] ='Error 0001: trouble finding reviews. Please try again, switch to remote crawl, or contact support.';
				$results = json_encode($results);
				echo $results;
				die();
			}
			
			
			foreach ($reviewdivs as $review) {
				if ($i > 21) {
						break;
				}
				$user_name='';
				$userimage='';
				$rating='';
				$datesubmitted='';
				$rtext='';
				$location='';
				// Find user_name
				if($review->find('a.user-display-name', 0)){
					$user_name = $review->find('a.user-display-name', 0)->plaintext;
				}
				if($user_name ==''){
					if($review->find('span[class=fs-block css-ux5mu6]', 0)){
						$user_name = $review->find('span[class=fs-block css-ux5mu6]', 0)->find('a', 0)->plaintext;
					}
				}
				
				
				if($user_name ==''){
					if($review->find('div[class=user-passport-info]', 0)){
						$user_name = $review->find('div[class=user-passport-info]', 0)->find('a', 0)->plaintext;
					}
				}
				
				if($user_name ==''){
					if($review->find('span[class=fs-block yelp-emotion-1m3btbh]', 0)){
						$user_name = $review->find('span[class=fs-block yelp-emotion-1m3btbh]', 0)->find('a', 0)->plaintext;
					}
				}
				
				//find user profile link if we can. this link is for the linking the avatar on the review.
				$from_url_review='';
				if($review->find('a[class=css-1fkqezt]', 0)){
					$from_url_review = $baseurl.$review->find('a[class=css-1fkqezt]', 0)->href;
				}
				if($from_url_review ==''){
					if($review->find('yelp-emotion-idvn5q', 0)){
						$from_url_review = $baseurl.$review->find('yelp-emotion-idvn5q', 0)->href;
					}
				}
				
				$location='';
				if($review->find('div[class=responsive-hidden-small__09f24__qQFtj]', 0)){
					$location = $review->find('div[class=responsive-hidden-small__09f24__qQFtj]', 0)->plaintext;
				}
				if($location ==''){
					if($review->find('div[class=yelp-emotion-12kfwpw]', 0)){
						$location = $review->find('div[class=yelp-emotion-12kfwpw]', 0)->plaintext;
					}
				}
				 
				
				//die();
				
				// Find userimage  y-css-1k4vfmo
				if($review->find('img.photo-box-img', 0)){
					$userimage = $review->find('img.photo-box-img', 0)->src;
				}
				if($userimage ==''){
					if($review->find('img[class=y-css-1k4vfmo]', 0)){
						$userimage = $review->find('img[class=y-css-1k4vfmo]', 0)->src;
					}
				}
				if($userimage ==''){
					if($review->find('img[class=lemon--img__373c0__3GQUb photo-box-img__373c0__O0tbt]', 0)){
						$userimage = $review->find('img[class=lemon--img__373c0__3GQUb photo-box-img__373c0__O0tbt]', 0)->src;
					}
				}
				if($userimage ==''){
					if($review->find('img[class=css-1pz4y59]', 0)){
						$userimage = $review->find('img[class=css-1pz4y59]', 0)->src;
					}
				}
				if($userimage ==''){
					if($review->find('img[class=yelp-emotion-1k4vfmo]', 0)){
						$userimage = $review->find('img[class=yelp-emotion-1k4vfmo]', 0)->src;
					}
				}
				
				if($userimage==""){
					$userimage='"https://s3-media0.fl.yelpcdn.com/assets/srv0/yelp_styleguide/514f6997a318/assets/img/default_avatars/user_60_square.png';
					
				}
				
				// find rating
				if($review->find('div.rating-large', 0)){
					$rating = $review->find('div.rating-large', 0)->title;
					$rating = intval($rating);
				}
				if($rating ==''){
					if($review->find("div[class*=i-stars--regular-]", 0)){
						$rating = $review->find("div[class*=i-stars--regular-]", 0)->{'title'};
						$rating = intval($rating);
					}
				}
				if($rating ==''){
					if($review->find("div[class=five-stars__09f24__mBKym]", 0)){
						$rating = $review->find("div[class=five-stars__09f24__mBKym]", 0)->{'aria-label'};
						$rating = intval($rating);
					}
				}
				if($rating ==''){
					if($review->find("div[class=css-14g69b3]", 0)){
						$rating = $review->find("div[class=css-14g69b3]", 0)->{'aria-label'};
						$rating = intval($rating);
					}
				}
				if($rating ==''){
					if($review->find("div[class=css-14g69b3]", 0)){
						$rating = $review->find("div[class=css-14g69b3]", 0)->{'aria-label'};
						$rating = intval($rating);
					}
				}
				if($rating ==''){
					if($review->find("div[class=yelp-emotion-9tnml4]", 0)){
						$rating = $review->find("div[class=yelp-emotion-9tnml4]", 0)->{'aria-label'};
						$rating = intval($rating);
					}
				}
				if($rating ==''){
					if($review->find("div[class=y-css-9tnml4]", 0)){
						$rating = $review->find("div[class=y-css-9tnml4]", 0)->{'aria-label'};
						$rating = intval($rating);
					}
				}

				// find date
				if($review->find('span.rating-qualifier', 0)){
					$datesubmitted = $review->find('span.rating-qualifier', 0)->plaintext;
					$datesubmitted = str_replace(array("Updated", "review"), "", $datesubmitted);
				}
				if($datesubmitted ==''){
					if($review->find('span[class=lemon--span__373c0__3997G text__373c0__2pB8f text-color--mid__373c0__3G312 text-align--left__373c0__2pnx_]', 0)){
						$datesubmitted = $review->find('span[class=lemon--span__373c0__3997G text__373c0__2pB8f text-color--mid__373c0__3G312 text-align--left__373c0__2pnx_]', 0)->plaintext;
					}
				}
				if($datesubmitted ==''){
					if($review->find('span[class=css-chan6m]', 0)){
						$datesubmitted = $review->find('span[class=css-chan6m]', 0)->plaintext;
					}
				}
				if($datesubmitted ==''){
					if($review->find('span[class=yelp-emotion-v293gj]', 0)){
						$datesubmitted = $review->find('span[class=yelp-emotion-v293gj]', 0)->plaintext;
					}
				}
				if($datesubmitted ==''){
					if($review->find('span[class=y-css-wfbtsu]', 0)){
						$datesubmitted = $review->find('span[class=y-css-wfbtsu]', 0)->plaintext;
					}
				}

				
				// find text
				if($review->find('div.review-content', 0)){
					$rtext = $review->find('div.review-content', 0)->find('p', 0)->plaintext;
				}
				if($rtext ==''){
					if($review->find('p.comment__373c0__3EKjH', 0)){
					$rtext = $review->find('p.comment__373c0__3EKjH', 0)->plaintext;
					}
				}
				if($rtext ==''){
					if($review->find('p[class=comment__09f24__gu0rG]', 0)){
					$rtext = $review->find('p[class=comment__09f24__gu0rG]', 0)->plaintext;
					}
				}
				if($rtext ==''){
					if($review->find('p[class=comment__09f24__D0cxf css-qgunke]', 0)){
					$rtext = $review->find('p[class=comment__09f24__D0cxf css-qgunke]', 0)->plaintext;
					}
				}
				if($rtext ==''){
					if($review->find('span[class=raw__09f24__T4Ezm]', 0)){
					$rtext = $review->find('span[class=raw__09f24__T4Ezm]', 0)->plaintext;
					}
				}
				
				//look for owner comments. block-quote__09f24__qASfJ css-kjl932
				//block-quote__09f24__qASfJ yelp-emotion-cckqp0
				$ownerresponsearrayjson ='';
				if($review->find('div[class=block-quote__09f24__qASfJ]', 0)){
					
					$ownerresponsediv = $review->find('div[class=block-quote__09f24__qASfJ]', 0);
					
					//make sure this isn't a previous review, must not have the stars.
					if($ownerresponsediv->find('div[class=five-stars__09f24__mBKym]', 0)){
						//could be previous review so we skip.
						$ownerresponsearray ='';
					} else {
						$ownerresponsearray = [];
						$ownerresponsearray['id']='';
						
						$ownerresponsearray['name']='';
						if($ownerresponsediv->find('p[class=css-ux5mu6"]', 0)){
							$ownerresponsearray['name']=$ownerresponsediv->find('p[class=css-ux5mu6"]', 0)->plaintext;
						}
						if($ownerresponsediv->find('p[class=css-chan6m"]', 0)){
							$ownerresponsearray['name']=$ownerresponsearray['name'].', '.$ownerresponsediv->find('p[class=css-chan6m"]', 0)->plaintext;
						}
						// yelp-emotion-1m3btbh
						if($ownerresponsearray['name']==''){
							if($ownerresponsediv->find('p[class=yelp-emotion-1m3btbh"]', 0)){
								$ownerresponsearray['name']=$ownerresponsediv->find('p[class=yelp-emotion-1m3btbh"]', 0)->plaintext;
							}
						}
						if($ownerresponsearray['name']==''){
							$ownerresponsearray['name']='Owner';
						}
						$ownerresponsearray['date']='';
						if($ownerresponsediv->find('p[class=css-chan6m"]', 1)){
							$ownerresponsearray['date']=$ownerresponsediv->find('p[class=css-chan6m"]', 1)->plaintext;
						}
						if($ownerresponsearray['date']==''){
							if($ownerresponsediv->find('div[class=yelp-emotion-1j7tr06"]', 0)){
								$ownerresponsearray['date']=$ownerresponsediv->find('div[class=yelp-emotion-1j7tr06"]', 0)->plaintext;
							}
						}

						$ownerresponsearray['comment']='';
						if($ownerresponsediv->find('span[class=raw__09f24__T4Ezm]', 0)){
							$ownerresponsearray['comment']=$ownerresponsediv->find('span[class=raw__09f24__T4Ezm]', 0)->plaintext;
						}

						if($ownerresponsearray['comment']!=''){
						$ownerresponsearrayjson = json_encode($ownerresponsearray);
						}

					}
				}

				
				//----------------
				//add review images here, like google and tripadvisor   ->find('img[class=css-xlzvdl]')
				//mediaurlsarrayjson, max of 8 images,  css-xlzvdl
				//--------------
				$mediaurlsarrayjson='';
				$mediaurlsarray = Array();
				
				if($review->find('div[class=css-1ek5ind]',0)){
					if($review->find('div[class=css-1ek5ind]',0)->find('img[class=css-xlzvdl]')){
						//have at least one image
						$ahrefimagesobject = $review->find('div[class=css-1ek5ind]');
						foreach ($ahrefimagesobject as $imageobj) {
								$mediaimg = $imageobj->find('img[class=css-xlzvdl]',0)->src;
								if($mediaimg!=''){
								$mediaurlsarray[]=$mediaimg;
								}
								if(count($mediaurlsarray)>7){
									break;
								}
							$mediaimg='';
						}
						if(count($mediaurlsarray)>0){
							$mediaurlsarrayjson = json_encode($mediaurlsarray);
						}
						unset($mediaurlsarray);
					}
				}
				
				if($mediaurlsarrayjson=='' || count($mediaurlsarray)<1 ){
					if($review->find('div[class=yelp-emotion-1ax7kgc]',0)) {
					if($review->find('div[class=yelp-emotion-1ax7kgc]',0)->find('img[class=yelp-emotion-dy9j94]',0)){
						$ahrefimagesobject = $review->find('div[class=yelp-emotion-1ax7kgc]',0)->find('img[class=yelp-emotion-dy9j94]');
						foreach ($ahrefimagesobject as $imageobj) {
								$mediaimg = $imageobj->src;
								if($mediaimg!=''){
								$mediaurlsarray[]=$mediaimg;
								}
								if(count($mediaurlsarray)>7){
									break;
								}
							$mediaimg='';
						}
						if(count($mediaurlsarray)>0){
							$mediaurlsarrayjson = json_encode($mediaurlsarray);
						}
						unset($mediaurlsarray);
					}
					}
					
				}
				if($mediaurlsarrayjson=='' || count($mediaurlsarray)<1 ){
					if($review->find('div[class=y-css-1azhvrn]',0)) {
					if($review->find('div[class=y-css-1azhvrn]',0)->find('img',0)){
						$ahrefimagesobject = $review->find('div[class=y-css-1azhvrn]');
						foreach ($ahrefimagesobject as $imageobj) {
								$mediaimg = $imageobj->find('img',0)->src;
								if($mediaimg!=''){
								$mediaurlsarray[]=$mediaimg;
								}
								if(count($mediaurlsarray)>7){
									break;
								}
							$mediaimg='';
						}
						if(count($mediaurlsarray)>0){
							$mediaurlsarrayjson = json_encode($mediaurlsarray);
						}
						unset($mediaurlsarray);
					}
					}
					
				}
				
				
				

				//echo "<br><br>";
				//echo "<br>--".$user_name;
				//echo "<br>--".$userimage;
				//echo "<br>--".$rating;
				//echo "<br>--".$datesubmitted;
				//echo "<br>--".$rtext;
				//die();
				
				if($rating>0 && $user_name!=''){
					$review_length = str_word_count($rtext);
					$pos = strpos($userimage, 'default_avatars');
					if ($pos === false) {
						$userimage = str_replace("60s.jpg","120s.jpg",$userimage);
					}
					$timestamp = strtotime($datesubmitted);
					$timestamp = date("Y-m-d H:i:s", $timestamp);
					//check option to see if this one has been hidden
					//pull array from options table of yelp hidden
					$yelphidden = get_option( 'wpyelp_hidden_reviews' );
					if(!$yelphidden){
						$yelphiddenarray = array('');
					} else {
						$yelphiddenarray = json_decode($yelphidden,true);
					}
					$this_yelp_val = trim($user_name)."-".strtotime($datesubmitted)."-".$review_length."-Yelp-".$rating;
					if (in_array($this_yelp_val, $yelphiddenarray)){
						$hideme = 'yes';
					} else {
						$hideme = 'no';
					}

					$reviewsarray[] = [
					 'reviewer_name' => $user_name,
					 'reviewer_id' => '',
					 'reviewer_email' => '',
					 'userpic' => $userimage,
					 'rating' => $rating,
					 'updated' => $datesubmitted,
					 'review_text' => $rtext,
					 'review_title' => '',
					 'from_url' => $stripvariableurl,
					 'from_url_review' => $from_url_review,
					 'language_code' => '',
					 'location' => $location,
					 'recommendation_type' => '',
					 'company_title' =>  '',
					 'company_url' => '',
					 'company_name' => '',
					 'mediaurlsarrayjson' => $mediaurlsarrayjson,
					 'owner_response' => $ownerresponsearrayjson,
					 ];
					$review_length ='';
				}
		 
				$i++;
			}
				
				
		//if we find less than 10 then do not loop again.
			if(count($reviewsarray)<10){
				$result['stoploop']='stop';
			}

			$result['reviews'] = $reviewsarray;

		}
			if (!empty($html)) {
				$html->clear();
				unset($html);
			}
			
		return $result;
		
	}	
	
	
	//for calling remote get and returning array of reviews to insert, calling Crawler now crawl.ljapps.com
	public function wprp_getapps_getrevs_page_yelp($type,$listedurl,$pagenum,$perpage,$savedpageid,$nhful,$fid,$blockstoinsert,$nextpageurl){
		$result['ack']='success';
		
		set_time_limit(120);
		
			$errormsg='';
			$reviewsarraytemp = Array();
			$nhful='new';
			$reviewsarray= Array();
			
			if (filter_var($listedurl, FILTER_VALIDATE_URL)) {
					
				if(isset($_SERVER['SERVER_ADDR']) && $_SERVER['SERVER_ADDR']!=''){
					$ip_server = $_SERVER['SERVER_ADDR'];
				} else {
					//get url of site.
					$ip_server = urlencode(get_site_url());
				}
				$siteurl = urlencode(get_site_url());
				
				//scrapeurl
				$tempurlval = 'https://crawl.ljapps.com/crawlrevs?rip='.$ip_server.'&surl='.$siteurl.'&scrapeurl='.$listedurl.'&stype=yelp&sfp=pro&nobot=1&nhful='.$nhful.'&locationtype=&scrapequery=&tempbusinessname=&pagenum='.$pagenum.'&nextpageurl='.$nextpageurl;
				
				//echo $tempurlval;
				//die();
				
				$serverresponse='';
				
				$args = array(
					'timeout'     => 120,
					'sslverify' => false
				); 
				$response = wp_remote_get( $tempurlval, $args );
				if ( is_array( $response ) && ! is_wp_error( $response ) ) {
					$headers = $response['headers']; // array of http header lines
					$serverresponse    = $response['body']; // use the content
				} else {
					//must have been an error
					$results['ack'] ='error';
					$results['ackmsg'] ='Error 0001a: trouble contacting crawling server with remote_get. Please try again or contact support. '.$response->get_error_message();
					$results = json_encode($results);
					echo $results;
					die();
				}
					
				$serverresponsearray = json_decode($serverresponse, true);
				
				//print_r($serverresponsearray);

				if($serverresponse=='' || !is_array($serverresponsearray)){
					$results['ack'] ='error';
					$results['ackmsg'] ='Error 0001: trouble contacting crawling server. Please try again or contact support.';
					$results = json_encode($results);
					echo $results;
					die();
				}
				//catch limit error
				if($serverresponsearray['ack']=='error'){
					$results['ack'] ='error';
					$results['ackmsg'] ='Error 0002: '.$serverresponsearray['ackmessage'];
					$results = json_encode($results);
					echo $results;
					die();
				}
				if(!isset($serverresponsearray['result']) || !is_array($serverresponsearray['result'])){
					$results['ack'] ='error';
					$results['ackmsg'] ='Error 0002b: trouble finding reviews. Contact support with this error code and the search terms or place id you are using.';
					$results = json_encode($results);
					echo $results;
					die();
				}
				//catch error
				if($serverresponsearray['result']['ack']=='error'){
					$results['ack'] ='error';
					$results['ackmsg'] ='Error 0003: '.$serverresponsearray['ackmessage'].' : '.$serverresponsearray['result']['ackmsg'];
					$results = json_encode($results);
					echo $results;
					die();
				}
				//made it this far assume we have reviews.
				$crawlerresultarray = $serverresponsearray['result'];
				

				//need totals and avg for this place $getreviewsarray['total']
				$result['total']='';
				$result['avg']='';
				if(isset($crawlerresultarray['avg'])){
					$result['avg']=$crawlerresultarray['avg'];
				}
				if(isset($crawlerresultarray['total'])){
					$result['total']=$crawlerresultarray['total'];
				}
				
				//pass back URL used
				if(isset($crawlerresultarray['callurl'])){
					$result['callurl']=$crawlerresultarray['callurl'];
				}
				//pass back next URL used
				if(isset($crawlerresultarray['nextpageurl'])){
					$result['nextpageurl']=$crawlerresultarray['nextpageurl'];
				}
				//pass back proxy
				if(isset($crawlerresultarray['proxy'])){
					$result['proxy']=$crawlerresultarray['proxy'];
				}
				
				$x=0;
				$crawlerreviewsarray = $crawlerresultarray['reviews'];
				$numreturned = count($crawlerreviewsarray);	
				
				foreach ($crawlerreviewsarray as $review) {
					
					$tempownerres='';
					if(isset($review['owner_response']) && $review['owner_response']!=''){
						$tempownerres = $review['owner_response'];
					}
					$templocation ='';
					if(isset($review['location']) && $review['location']!=''){
						$templocation = $review['location'];
					}	
					$tempmediaurlsarrayjson ='';
					if(isset($review['mediaurlsarrayjson']) && $review['mediaurlsarrayjson']!=''){
						$tempmediaurlsarrayjson = $review['mediaurlsarrayjson'];
					}					
					
					$reviewsarray[] = [
					 'reviewer_name' => $review['user_name'],
					 'reviewer_id' => '',
					 'reviewer_email' => '',
					 'userpic' => $review['userimage'],
					 'rating' => $review['rating'],
					 'updated' => $review['datesubmitted'],
					 'review_text' => $review['rtext'],
					 'review_title' => '',
					 'from_url' => $listedurl,
					 'from_url_review' => $review['from_url_review'],
					 'language_code' => '',
					 'location' => $templocation,
					 'recommendation_type' => '',
					 'company_title' =>  '',
					 'company_url' => '',
					 'company_name' => '',
					 'mediaurlsarrayjson' => $tempmediaurlsarrayjson,
					 'owner_response' => $tempownerres,
					 ];
					
					$x++;
				}
				
				//if we find less than 10 then do not loop again.
					if(count($reviewsarray)<10){
						$result['stoploop']='stop';
					}

			$result['reviews'] = $reviewsarray;

			}
		return $result;
	}
	
	//for calling remote get and returning array of reviews to insert
	public function wprp_getapps_getrevs_page_birdeye($type,$listedurl,$pagenum,$perpage,$savedpageid,$nhful,$fid,$blockstoinsert){
		$result['ack']='success';
		$errormsg='';
		$reviewsarray = Array();
		
		$businessId = $listedurl;
		$sindex = 0;
		$apikey = get_option('wprevpro_birdeyeapikey_val');
		$result['avg']='';
		$result['total']='';
			
		if($businessId=='' || $apikey==''){
			//must have been an error
			$results['ack'] ='error';
			$results['ackmsg'] ='Error 0001: Make sure to enter your API Key and Business ID from Birdeye.';
			$results = json_encode($results);
			echo $results;
			die();
		}

		if($pagenum >1){
			$sindex = $pagenum*100;
		}
		
		//get the total and avg first
		if($pagenum <2){
			//https://api.birdeye.com/resources/v1/review/businessid/businessId/summary?api_key=abcdefgh&statuses=ad
			$callurl = "https://api.birdeye.com/resources/v1/review/businessid/".$businessId."/summary?api_key=".$apikey."&statuses=ad";
			$response = wp_remote_get( $callurl, array( 'timeout' => 10,
					'headers' => array( 'Content-Type' => 'application/json',
                               'Accept'=> 'application/json' ) 
					));
			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				$headers = $response['headers']; // array of http header lines
				$body    = $response['body']; // use the content
			} else {
				$result['ack']=esc_html__('Error: Can not use remote get on this url:', 'wp-review-slider-pro').' '.$callurl;
			}
			$reviewsummary = json_decode($body,true);
			
			if($reviewsummary['sources'] && is_array($reviewsummary['sources'])){
				foreach ($reviewsummary['sources'] as $valuearray) {
					if(isset($valuearray['sourceName'])){
						if($valuearray['sourceName']=='Birdeye'){
							$result['avg']=$valuearray['avgRating'];
							$result['total']=$valuearray['reviewCount'];
						}
					}
				}
			}

		}
		
		//$result = $type.':'.$listedurl.':'.$pagenum.':'.$perpage.':'.$savedpageid.':'.$nhful.':'.$fid.':'.$blockstoinsert;
		//Birdeye:158025200485823:1:100:::324:20
		//https://api.birdeye.com/resources/v1/review/businessId/158025200485823?sindex=0&count=100&api_key=EW36B9hWVECe05pBB96p8WDtNwQktKnx&includeNonAggregatedReviews=false&businessId=158025200485823
			
		$endpoint = 'https://api.birdeye.com/resources/v1/review/businessId/'.$businessId.'?sindex='.$sindex.'&count=100&api_key=EW36B9hWVECe05pBB96p8WDtNwQktKnx&includeNonAggregatedReviews=false&businessId=158025200485823';

		$body = '{
			"sources":["our_website","direct_feedback","birdeye"],
			"allChild":"true",
			"fetchExtraParams":false,
			"needCustomerInfo":false
		}';

		$options = [
			'body'        => $body,
			'headers'     => [
				'Content-Type' => 'application/json',
			],
			'timeout'     => 60,
			'redirection' => 5,
			'blocking'    => true,
			'httpversion' => '1.0',
			'sslverify'   => false,
			'data_format' => 'body',
		];

		$response = wp_remote_post( $endpoint, $options );
		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			$results['ack'] ='error';
			$results['ackmsg'] ='Error 0002: Something went wrong. '.$error_message;
			$results = json_encode($results);
			echo $results;
			die();
		}
		
		$fileurlcontents = $response['body'];	
			
		$reviewcontainerdiv = json_decode($fileurlcontents,true);
		
		foreach ($reviewcontainerdiv as $review) {
				$user_name='';
				$userimage='';
				$rating='';
				$datesubmitted='';
				$rtext='';
				$from_url_review='';
				$company_title='';
				$unique_id='';
				$reviewer_id='';
				$title='';
				$reviewer_email='';
				$userpic='';
				$from_url_review='';
				$from_url='';
				$location='';
			
			// Find unique id
			if($review['reviewId']){
				$unique_id=$review['reviewId'];
			}
			
			// Find user_name
			if($review['reviewer']['nickName']){
				$user_name=$review['reviewer']['nickName'];
			}
			if($review['reviewer']['customerId']){
				$reviewer_id=$review['reviewer']['customerId'];
			}
			if($review['reviewer']['emailId']){
				$reviewer_email=$review['reviewer']['emailId'];
			}
			if($review['reviewer']['thumbnailUrl']){
				$userpic=$review['reviewer']['thumbnailUrl'];
			}

			//find rating
			if($review['rating']){
				$rating=$review['rating'];
			}
			
			//find date created_at
			if($review['reviewDate']){
				$datesubmitted=$review['reviewDate'];
			}
			
			//find title
			if($review['title']){
				$title=$review['title'];
			}
			
			//find text
			if($review['comments']){
				$rtext=$review['comments'];
			}
			
			if($review['reviewUrl']){
				$from_url=$review['reviewUrl'];
			}
			
			if($review['uniqueReviewUrl']){
				$from_url_review=$review['uniqueReviewUrl'];
			}
			$citylocation ='';
			$statelocation='';
			if($review['reviewer']['city']){
				$citylocation = $review['reviewer']['city'];
			}
			if($review['reviewer']['state']){
				$statelocation = $review['reviewer']['state'];
			}
			if($citylocation !='' && $statelocation !=''){
				$location = $citylocation.', '.$statelocation;
			} else if($citylocation !='' && $statelocation ==''){
				$location = $citylocation;
			} else if($citylocation =='' && $statelocation !=''){
				$location = $statelocation;
			}
			
			//owner_response
			$owner_response_encode ='';
			$owner['id'] = '';
			$owner['name'] = '';
			$owner['comment'] = '';
			$owner['date'] = '';
			//mgrRspnInline
			if($review['response']){
				//must be a response
				$owner['name'] = 'Business Response';

				//responseDate
				if(isset($review['responseDate'])){
				 $tempdate = $this->myStrtotime($review['responseDate']);	
				} else {
				 $tempdate = $this->myStrtotime($datesubmitted);
				}
				$owner['date'] = date('Y-m-d', $tempdate);
				$owner['comment'] = $review['response'];
			}
			if($owner['comment']!=''){
				$owner_response_encode = json_encode($owner);
			}

			if($rating>0){
				$reviewsarray[] = [
					 'reviewer_name' => trim($user_name),
					 'reviewer_id' => trim($reviewer_id),
					 'reviewer_email' => $reviewer_email,
					 'userpic' => $userpic,
					 'rating' => $rating,
					 'updated' => $datesubmitted,
					 'review_text' => trim($rtext),
					 'review_title' => trim($title),
					 'from_url' => $from_url,
					 'from_url_review' => $from_url_review,
					 'language_code' => '',
					 'unique_id' => $unique_id,
					 'location' => $location,
					 'recommendation_type' => '',
					 'company_title' =>  '',
					 'company_url' => '',
					 'company_name' => '',
					 'mediaurlsarrayjson' => '',
					 'owner_response' => $owner_response_encode,
					 ];
			}
		}

		$result['reviews'] = $reviewsarray;
				
		//die();

		return $result;
	}
	
	
	//new Nextdoor 4-6-2024
	
	public function wprp_getapps_getrevs_page_nextdoor($type,$listedurl,$pagenum,$perpage,$savedpageid,$nhful,$fid,$blockstoinsert,$rectostar){
			
			
			//try to find page slug. https://nextdoor.com/pages/il-mulino-fort-lauderdale-fl/
			$pageslug = $this->get_string_between($listedurl, '/pages/', '/');

			$endpoint = "https://nextdoor.com/api/gql/OrgPagesRecFeedLogout";
			

			$body = '{"operationName":"OrgPagesRecFeedLogout","variables":{"pagedCommentsMode":"FEED","slug":"'.$pageslug.'","recommendationFeedArgs":{"feedRequestId":"5E07FF34-A2DC-489F-9F6D-B5AC007647E4","nextPage":"eyJwYWdpbmdfdG9rZW4iOiAzLCAic3RhcnRfb2Zmc2V0IjogMywgIm1hdGVyaWFsaXplZF92aWV3X2lkIjogImQ4OGE4NGJkLWE2NDUtNDUyOC04ODU4LTc5OGM0NDAyYTU0YiJ9","pageSize":20},"timeZone":"America/Chicago"},"query":"query OrgPagesRecFeedLogout($slug: String!, $pagedCommentsMode: PagedCommentsMode = FEED, $pinnedCommentId: NextdoorID, $timeZone: String!, $recommendationFeedArgs: OrgPagesRecommendationsFeedArgs!) {\n  orgPagesFeed(\n    orgPagesFeedArgs: {businessSlug: $slug, limitResponsesTo: FeedItemOrgPagesRecommendations}\n  ) {\n    feedItems {\n      feedItemType\n      legacyAnalyticsId\n      trackingId\n      contentId\n      contentType\n      analyticsPayload\n      ... on FeedItemOrgPagesRecommendations {\n        moduleTitleText {\n          ...styled_text_styledTextFragment\n          __typename\n        }\n        recommendationsFeed(feedArgs: $recommendationFeedArgs) {\n          nextPage\n          feedItems {\n            contentId\n            feedItemType\n            trackingId\n            ... on FeedItemRecommendationsItem {\n              feedItemType\n              author {\n                ...logout_businessProfile_AnonymousAuthorFragment\n                __typename\n              }\n              bodyText {\n                ...styled_text_styledTextFragment\n                __typename\n              }\n              secondaryText {\n                ...styled_text_styledTextFragment\n                __typename\n              }\n              sourceId\n              sourceLegacyId\n              sourceType\n              shouldShowDeleteAction\n              sourceContextText {\n                ...styled_text_styledTextFragment\n                __typename\n              }\n              recFeedPost {\n                id\n                author {\n                  ...logout_businessProfile_AnonymousAuthorFragment\n                  __typename\n                }\n                mediaAttachments {\n                  ...logout_businessProfile_MediaAttachmentsFragment\n                  __typename\n                }\n                body\n                comments(mode: $pagedCommentsMode, pinnedCommentId: $pinnedCommentId) {\n                  pagedComments {\n                    ...logout_businessProfile_CommentsFragment\n                    __typename\n                  }\n                  __typename\n                }\n                createdAt {\n                  epochMillis\n                  asDateTime(timeZone: $timeZone) {\n                    relativeTime\n                    __typename\n                  }\n                  __typename\n                }\n                editedAt {\n                  asDateTime(timeZone: $timeZone) {\n                    relativeTime\n                    __typename\n                  }\n                  __typename\n                }\n                feedItemType\n                id\n                imageCropType\n                legacyPostId\n                photos {\n                  url\n                  __typename\n                }\n                postType\n                reactionSummaries {\n                  ...feed_post_reactionSummariesFragment\n                  __typename\n                }\n                markdownBody\n                styledBody {\n                  ...styled_text_styledTextFragment\n                  __typename\n                }\n                subject\n                __typename\n              }\n              __typename\n            }\n            __typename\n          }\n          feedRenderId\n          __typename\n        }\n        __typename\n      }\n      __typename\n    }\n    __typename\n  }\n}\n\nfragment styled_text_styledTextFragment on StyledText {\n  text\n  maxLines\n  styles {\n    start\n    length\n    attributes {\n      standardColor {\n        ...standard_color_standardColorFragment\n        __typename\n      }\n      standardFont\n      isStrikethrough\n      standardIconV2 {\n        ...standard_icon_standardIconV2Fragment\n        __typename\n      }\n      styledTextUrl\n      pillColor {\n        ...standard_color_standardColorFragment\n        __typename\n      }\n      action {\n        ...standard_action_standardActionFragment\n        __typename\n      }\n      __typename\n    }\n    __typename\n  }\n  __typename\n}\n\nfragment standard_color_standardColorFragment on StandardColor {\n  colorName\n  fallbackColorHEX\n  __typename\n}\n\nfragment standard_icon_standardIconV2Fragment on StandardIconV2 {\n  icon\n  tint {\n    ...standard_color_standardColorFragment\n    __typename\n  }\n  __typename\n}\n\nfragment standard_action_standardActionFragment on StandardAction {\n  standardActionType\n  trackingEvent\n  trackingMetadata\n  ... on AppealsAction {\n    nextStep\n    __typename\n  }\n  ... on AppealSuspensionAction {\n    restrictionId\n    restrictionReason\n    __typename\n  }\n  ... on BlockUserAction {\n    block\n    userId\n    userName\n    __typename\n  }\n  ... on LinkAction {\n    url\n    urlPath\n    newWindow\n    linkActionType\n    trackingEvent\n    trackingMetadata\n    adsTrackingMetadata {\n      ...ads_adsTrackingMetadataFragment\n      __typename\n    }\n    __typename\n  }\n  ... on ShowModerationChoicePageActionV1 {\n    contentId\n    __typename\n  }\n  ... on ShowModerationEventsSummaryActionV1 {\n    eventsSummaryType\n    __typename\n  }\n  ... on ShowModerationSummaryActionV1 {\n    viewMode\n    __typename\n  }\n  ... on RecommendBusinessPageActionV1 {\n    businessId\n    isRecommend\n    modalUrlPath\n    __typename\n  }\n  ... on ShareInAppFlowAction {\n    url\n    __typename\n  }\n  ... on ShowUserGroupDetailPostComposerAction {\n    userGroupId\n    userGroupSecureId\n    userGroupName\n    userGroupAbout\n    postSubject\n    promoName\n    __typename\n  }\n  ... on ShareGroupAction {\n    userGroupSecureId\n    userGroupName\n    promoName\n    __typename\n  }\n  ... on UploadCoverPhotoAction {\n    userGroupSecureId\n    promoName\n    __typename\n  }\n  ... on ChangeGroupMembershipAction {\n    userGroupSecureId\n    membershipAction\n    __typename\n  }\n  ... on ClickToCallAction {\n    phoneNumber\n    description\n    __typename\n  }\n  ... on ShareGroupToFeedAction {\n    userGroupSecureId\n    postSubject\n    postMessage\n    promoName\n    __typename\n  }\n  ... on ShowUserGroupCategoriesAction {\n    userGroupSecureId\n    promoName\n    __typename\n  }\n  ... on ContentCreationAction {\n    userGroupSecureId\n    postSubject\n    postType\n    promoName\n    __typename\n  }\n  ... on AcceptConnectionRequestAction {\n    userProfile {\n      id\n      __typename\n    }\n    __typename\n  }\n  ... on SendConnectionRequestAction {\n    userProfile {\n      id\n      __typename\n    }\n    __typename\n  }\n  ... on MessageConnectionAction {\n    userProfile {\n      id\n      legacyUserId\n      canonicalChatId\n      name {\n        displayName\n        __typename\n      }\n      __typename\n    }\n    __typename\n  }\n  ... on MessageAction {\n    nextdoorId\n    displayName\n    legacyId\n    __typename\n  }\n  ... on DeleteNotificationAction {\n    notificationId\n    __typename\n  }\n  ... on UpdateUserMuteStatusAction {\n    userProfile {\n      id\n      name {\n        givenName\n        __typename\n      }\n      __typename\n    }\n    mute\n    __typename\n  }\n  ... on MutePostAction {\n    postId\n    mute\n    __typename\n  }\n  ... on FeedItemToggleNotificationAction {\n    postId\n    postShareId\n    toggle\n    __typename\n  }\n  ... on FeedItemToggleMuteUserAction {\n    toggle\n    userId\n    __typename\n  }\n  ... on WaveAction {\n    userProfile {\n      id\n      name {\n        givenName\n        __typename\n      }\n      __typename\n    }\n    __typename\n  }\n  ... on DiscoverSendUserConnectionRequestAction {\n    userProfile {\n      id\n      name {\n        displayName\n        __typename\n      }\n      __typename\n    }\n    __typename\n  }\n  ... on DiscoverCancelUserConnectionRequestAction {\n    userProfile {\n      id\n      name {\n        displayName\n        __typename\n      }\n      __typename\n    }\n    __typename\n  }\n  ... on DiscoverChangeGroupMembershipAction {\n    userGroupSecureId\n    membershipAction\n    __typename\n  }\n  ... on FeedItemAddReactionAction {\n    contentId\n    reactionName\n    __typename\n  }\n  ... on FeedItemRemoveReactionAction {\n    contentId\n    reactionId\n    __typename\n  }\n  ... on DeleteBookmarkAndRerender {\n    contentId\n    contentType\n    uiComponentId\n    __typename\n  }\n  ... on AddVirtualGiftAction {\n    contentId\n    virtualGiftName\n    __typename\n  }\n  ... on RemoveVirtualGiftAction {\n    contentId\n    virtualGiftId\n    __typename\n  }\n  ... on CopyToClipboardAction {\n    value\n    __typename\n  }\n  ... on OrganizationConnectAction {\n    organizationId\n    actionToTake\n    __typename\n  }\n  ... on SetLocalEventResponseToInterestedAction {\n    eventId\n    userResponse\n    __typename\n  }\n  ... on OpenLocalEventResponsePickerAction {\n    eventId\n    userResponse\n    __typename\n  }\n  ... on SeasonalEventUserResponseAction {\n    pinVariant\n    responseActionType\n    responseText\n    mapType\n    __typename\n  }\n  ... on MapRollupPinAction {\n    pinId\n    sheet {\n      variant\n      __typename\n    }\n    __typename\n  }\n  ... on MapCardsPopupAction {\n    referenceCardId\n    __typename\n  }\n  ... on ReportNodeSelectedAction {\n    node\n    reportContentId: contentId\n    __typename\n  }\n  ... on ReportContentSubmitAction {\n    contentId\n    contentActionReason\n    contentActionType\n    __typename\n  }\n  ... on ReportUserSubmitAction {\n    userId\n    reason\n    __typename\n  }\n  ... on ReportDeleteAction {\n    contentId\n    contentActionType\n    __typename\n  }\n  ... on ReportMuteAuthorAction {\n    contentId\n    mute\n    nextNode\n    __typename\n  }\n  ... on ReportMutePostAction {\n    postId\n    mute\n    nextNode\n    __typename\n  }\n  ... on ReportMuteUserAction {\n    userId\n    mute\n    nextNode\n    __typename\n  }\n  ... on OpenPostShareSheetAction {\n    post {\n      id\n      shareId\n      localEventInfo {\n        localEvent {\n          shareId\n          __typename\n        }\n        __typename\n      }\n      __typename\n    }\n    __typename\n  }\n  ... on MapOpenPostComposerAction {\n    mapType\n    pinPost: post {\n      id\n      __typename\n    }\n    __typename\n  }\n  ... on SocialShareAction {\n    shareMetadata {\n      title\n      body\n      url\n      __typename\n    }\n    __typename\n  }\n  ... on SetLocalEventResponseToGoingAction {\n    eventId\n    userResponse\n    __typename\n  }\n  ... on ShowAppDownloadModalAction {\n    deeplinkUrl\n    modalTitle\n    __typename\n  }\n  ... on OpenComposerAction {\n    composerProperties {\n      initSource\n      redirectOnSuccess\n      subflow\n      __typename\n    }\n    __typename\n  }\n  ... on ClassifiedMessageAction {\n    recipientId\n    recipientDisplayName\n    classifiedId\n    legacyClassifiedId\n    subject\n    __typename\n  }\n  ... on UseAISuggestionAction {\n    variant\n    __typename\n  }\n  ... on GroupPromoLinkAction {\n    groupSecureId\n    url\n    urlPath\n    newWindow\n    promoName\n    linkActionType\n    __typename\n  }\n  ... on MapFilterContentUpdateAction {\n    filterActionType\n    filterProperty\n    mapTypeName\n    __typename\n  }\n  ... on DeactivationFlowNextPageAction {\n    page\n    __typename\n  }\n  ... on UnsuspendUserAction {\n    entryPoint\n    __typename\n  }\n  ... on HideStreamChannelAction {\n    channelId\n    __typename\n  }\n  ... on MuteStreamChannelAction {\n    channelId\n    mute\n    __typename\n  }\n  ... on LeaveLocalCommunityAction {\n    communityShareId\n    __typename\n  }\n  ... on ReportUserAction {\n    userId\n    userSecureId\n    __typename\n  }\n  ... on UserFeedbackResponseAction {\n    responseActionNextStep: nextStep\n    __typename\n  }\n  ... on UserFeedbackSubmitAction {\n    submitActionNextStep: nextStep\n    __typename\n  }\n  ... on LeaveStreamChannelAction {\n    channelId\n    __typename\n  }\n  ... on ViewStreamChannelMembersAction {\n    channelId\n    __typename\n  }\n  ... on EditStreamChannelAction {\n    channelId\n    channelName\n    __typename\n  }\n  ... on DeleteStreamChannelAction {\n    channelId\n    __typename\n  }\n  ... on ShowGeneralizedFlowAction {\n    flowType\n    view\n    __typename\n  }\n  ... on AddUsersToCommunityStreamChatAction {\n    channelCid\n    communityShareId\n    __typename\n  }\n  ... on EditLocalCommunityAction {\n    communityShareId\n    __typename\n  }\n  __typename\n}\n\nfragment ads_adsTrackingMetadataFragment on AdsTrackingMetadata {\n  adUnitID\n  adFormatID\n  productContext\n  metadata {\n    adRequestId\n    trackingId\n    lineItemId\n    creativeId\n    demandSource\n    passthroughKv\n    metadataType\n    ...ads_impressionMetadataFragment\n    ...ads_clickMetadataFragment\n    ...ads_mediaMetadataFragment\n    ...ads_actionMetadataFragment\n    __typename\n  }\n  __typename\n}\n\nfragment ads_impressionMetadataFragment on ImpressionMetadata {\n  adImpressionId\n  adRequestId\n  advertiserID\n  bidResponseEntityId\n  creativeId\n  demandSource\n  extraData\n  impressionType\n  lineItemId\n  metadataType\n  orderId\n  pageId\n  passthroughKv\n  sponsor\n  trackingId\n  __typename\n}\n\nfragment ads_clickMetadataFragment on ClickMetadata {\n  adRequestId\n  advertiserID\n  clickDestinationType\n  clickId\n  creativeId\n  demandSource\n  extraData\n  lineItemId\n  metadataType\n  orderId\n  pageId\n  passthroughKv\n  sponsor\n  trackingId\n  uiElementName\n  __typename\n}\n\nfragment ads_mediaMetadataFragment on MediaMetadata {\n  adImpressionId\n  adMediaId\n  adMediaType\n  adRequestId\n  advertiserID\n  creativeId\n  demandSource\n  extraData\n  lineItemId\n  metadataType\n  orderId\n  passthroughKv\n  trackingId\n  __typename\n}\n\nfragment ads_actionMetadataFragment on ActionMetadata {\n  adActionId\n  adCampaignId\n  adEventType\n  adFunnelId\n  adRequestId\n  advertiserID\n  creativeId\n  demandSource\n  extraData\n  lineItemId\n  metadataType\n  passthroughKv\n  trackingId\n  __typename\n}\n\nfragment feed_post_reactionSummariesFragment on ReactionSummaries {\n  summaries {\n    color\n    count\n    defaultResources {\n      title\n      svgUrl\n      png42Url\n      animationUrl\n      __typename\n    }\n    name\n    popupResources {\n      title\n      svgUrl\n      png42Url\n      animationUrl\n      __typename\n    }\n    selectedResources {\n      title\n      svgUrl\n      png42Url\n      animationUrl\n      __typename\n    }\n    unselectedResources {\n      title\n      svgUrl\n      png42Url\n      animationUrl\n      __typename\n    }\n    userReactionId\n    __typename\n  }\n  __typename\n}\n\nfragment logout_businessProfile_AnonymousAuthorFragment on Author {\n  avatar {\n    url\n    __typename\n  }\n  awardIcon {\n    ...AwardIconFragment\n    __typename\n  }\n  displayName\n  ... on AnonymizedAuthor {\n    locationHint\n    __typename\n  }\n  __typename\n}\n\nfragment AwardIconFragment on AwardIcon {\n  icon {\n    ...standard_icon_standardIconV2Fragment\n    __typename\n  }\n  backgroundColor {\n    ...standard_color_standardColorFragment\n    __typename\n  }\n  borderColor {\n    ...standard_color_standardColorFragment\n    __typename\n  }\n  __typename\n}\n\nfragment logout_businessProfile_CommentsFragment on PagedCommentsConnection {\n  edges {\n    node {\n      comment {\n        ...logout_businessProfile_CommentFragment\n        __typename\n      }\n      replies {\n        edges {\n          node {\n            comment {\n              ...logout_businessProfile_CommentFragment\n              __typename\n            }\n            __typename\n          }\n          __typename\n        }\n        __typename\n      }\n      __typename\n    }\n    __typename\n  }\n  __typename\n}\n\nfragment logout_businessProfile_MediaAttachmentsFragment on MediaAttachment {\n  type\n  url\n  ... on ImageMediaAttachment {\n    imageSize {\n      url\n      widthPx\n      heightPx\n      __typename\n    }\n    image {\n      ...images_imageFragment\n      __typename\n    }\n    resourceId\n    s3BucketKey\n    s3Path\n    order\n    __typename\n  }\n  ... on VideoMediaAttachment {\n    type\n    url\n    resourceId\n    video {\n      videoMetadata {\n        videoDimensions {\n          heightPx\n          widthPx\n          __typename\n        }\n        __typename\n      }\n      videoHlsInfo {\n        url\n        playbackUrlParams {\n          key\n          value\n          __typename\n        }\n        __typename\n      }\n      __typename\n    }\n    previewImage {\n      ...images_imageFragment\n      __typename\n    }\n    order\n    __typename\n  }\n  ... on DocumentMediaAttachment {\n    type\n    url\n    resourceId\n    title\n    __typename\n  }\n  __typename\n}\n\nfragment images_imageFragment on Image {\n  url\n  imageMetadata {\n    altText\n    imageDimensions {\n      widthPx\n      heightPx\n      __typename\n    }\n    imageFocalArea {\n      left\n      top\n      right\n      bottom\n      __typename\n    }\n    __typename\n  }\n  __typename\n}\n\nfragment logout_businessProfile_CommentFragment on Comment {\n  createdAt {\n    epochMillis\n    asDateTime(timeZone: $timeZone) {\n      relativeTime\n      __typename\n    }\n    __typename\n  }\n  id\n  legacyCommentId\n  body\n  author {\n    ...logout_businessProfile_AnonymousAuthorFragment\n    __typename\n  }\n  reactionSummaries {\n    summaries {\n      count\n      __typename\n    }\n    __typename\n  }\n  photos {\n    url\n    __typename\n  }\n  mediaAttachments {\n    ...logout_businessProfile_MediaAttachmentsFragment\n    __typename\n  }\n  __typename\n}\n"}';

			$options = [
				'body'        => $body,
				'headers'     => [
					'Content-Type' => 'application/json',
					'Cookie' => 'csrftoken=kfA8vVrdNaEII9og5mXVT3IpG1rfEVNWt1ayOkd69iLt85T8sMfQP59XFl7lHuq3; WE=412a2e8e-52d2-4a91-bcf0-c2462dd8969a240328; _gcl_au=1.1.1248135096.1711588936; _ga=GA1.1.902398086.1711588936; _ga_L2ES4MTTT0=GS1.1.1711588936.1.1.1711588936.60.0.0; ndp_session_id=7dc7821c-bf27-430a-88fa-aea1d77fb44c; _uetsid=9d424780eca111ee808905f097ea6dba; _uetvid=9d424da0eca111eea9c57f9fca9c43f0; FPID=FPID2.2.bfUma0l6WLAAr0fjLUTYElbMHMll41tannLSI49qpMU%3D.1711588936; FPLC=%2FuDsZyEdsYSQ4ArxbtXzlouYLsugIy3umnI7bjbTFBjnR%2FzCrdu76lJB65f8CFG7zE0BrdFLKdav2teufXtrzDDrTdtGtDi40vQmDsn4kSeiJw2gpfisKCsghqUBlQ%3D%3D; _fbp=fb.1.1711588936858.1156574007; OptanonConsent=isGpcEnabled=0&datestamp=Wed+Mar+27+2024+20%3A22%3A16+GMT-0500+(Central+Daylight+Time)&version=202303.2.0&browserGpcFlag=0&isIABGlobal=false&hosts=&consentId=fd359058-5a27-4feb-8d72-30f74e1448b0&interactionCount=0&landingPath=https%3A%2F%2Fnextdoor.com%2Fpages%2F'.$pageslug.'%2F&groups=C0001%3A1%2CC0003%3A1%2CC0004%3A1%2CC0005%3A1%2CC0002%3A1%2CC0007%3A1; _dd_s=logs=1&id=76a691b8-c975-46aa-9451-dd6e57d6b7bc&created=1711588937062&expire=1711589849865&rum=0',
					'Origin' => 'https://nextdoor.com',
					'Referer' => $listedurl,
					'X-Csrftoken' => 'kfA8vVrdNaEII9og5mXVT3IpG1rfEVNWt1ayOkd69iLt85T8sMfQP59XFl7lHuq3'
				],
				'timeout'     => 60,
				'redirection' => 5,
				'blocking'    => true,
				'httpversion' => '1.0',
				'sslverify'   => false,
				'data_format' => 'body',
			];

			$response = wp_remote_post( $endpoint, $options );

			if ( ! is_wp_error( $response ) ) {
				$body = json_decode( wp_remote_retrieve_body( $response ), true );
				//print_r($body);
			} else {
				$error_message = $response->get_error_message();
				//throw new Exception( $error_message );
				$results['ack'] ='error';
				$results['ackmsg'] ='Error 0002: Something went wrong. '.$error_message;
				$results = json_encode($results);
				echo $results;
				die();
			}
			//recommendationsFeed
			
			if(!isset($body['data']['orgPagesFeed']['feedItems']['0']['recommendationsFeed']['feedItems'])){
				$results['ack'] ='error';
				$results['ackmsg'] ='Error 0003: Something went wrong. Can not find the reviews. Please check your URL and contact support. ';
				$results = json_encode($results);
				echo $results;
				die();
			}
			
			$reviewsarraytemp = $body['data']['orgPagesFeed']['feedItems']['0']['recommendationsFeed']['feedItems'];

			//print_r($reviewsarraytemp);
			//die();
			
			//loop reviews and build new array of just what we need
			foreach ($reviewsarraytemp as $item) {
				
				//check for comments first if not there then grab top level post.
				if(isset($item['recFeedPost']['comments']['pagedComments']['edges'][0]['node']['comment']['id'])){
					$isreplycomment = true;
					$reviewdataarray = $item['recFeedPost']['comments']['pagedComments']['edges'][0]['node']['comment'];
					//echo "<br><br>here<br>";
					//echo $reviewdataarray;
					//print_r($reviewdataarray);
					//die();
				} else {
					$isreplycomment = false;
					$reviewdataarray = $item['recFeedPost'];
					//echo "<br><br>here1<br>";
					//echo $reviewdataarray;
				//	print_r($reviewdataarray);
					//die();
				}

				$tempuserpic = '';
				if($reviewdataarray['author']['avatar']['url']){
					$tempuserpic = $reviewdataarray['author']['avatar']['url'];
				}
				$subject = '';
				if($isreplycomment==false){
					if($reviewdataarray['subject']){
						$subject = $reviewdataarray['subject'];
					}
				}
				
				$updatedtimestring = '';
				if($reviewdataarray['createdAt']['epochMillis']){
					$updatedtimestring = $reviewdataarray['createdAt']['epochMillis'];
				}
				
				$updatedtimestring = date("Y-m-d H:i:s", $updatedtimestring);
				
				
				
				$rating="";
				if(isset($rectostar) && $rectostar =='1'){
						$rating=5;
				}
				
				 $reviewsarray[] = [
				 'reviewer_name' => trim($reviewdataarray['author']['displayName']),
				 'reviewer_id' => '',
				 'reviewer_email' => '',
				 'userpic' => $tempuserpic,
				 'rating' => $rating,
				 'updated' => $updatedtimestring,
				 'review_title' => $subject,
				 'review_text' => $reviewdataarray['body'],
				 'from_url_review' => '',
				 'language_code' => '',
				 'location' => $reviewdataarray['author']['locationHint'],
				 'recommendation_type' => 'positive',
				 'company_title' =>  '',
				 'company_url' => '',
				 'company_name' => '',
				 ];
			}

			$result['reviews'] = $reviewsarray;
				
		//die();
		$result['stoploop']='stop';

		return $result;
	}	
	

		
	//for calling remote get and returning array of reviews to insert
	public function wprp_getapps_getrevs_page_socialclimb($type,$listedurl,$pagenum,$perpage,$savedpageid,$nhful,$fid,$blockstoinsert){
		$result['ack']='success';
		$errormsg='';
		$reviewsarray = Array();
		
		//get apikey andn survey id from input
		$listedurl = str_replace(" ","",$listedurl);
		$idarray = explode(",",$listedurl);
		
		$apikey = $idarray[0];
		$surveyid = $idarray[1];
		$result['avg']='';
		$result['total']='';
			
		if($apikey==''){
			//must have been an error
			$results['ack'] ='error';
			$results['ackmsg'] ='Error 0001: Make sure to enter your API Key and Business ID from Birdeye.';
			$results = json_encode($results);
			echo $results;
			die();
		}

	
		//get the total and avg first===?????anyway to get this?

		$endpoint = 'https://api.socialclimb.com/v1/survey-responses?survey_id='.$surveyid;

		$options = [
			'body'        => '',
			'headers'     => [
				'Content-Type' => 'application/json',
				'api-token' => $apikey,
			],
			'timeout'     => 60,
			'redirection' => 5,
			'blocking'    => true,
			'httpversion' => '1.0',
			'sslverify'   => false,
		];

		$response = wp_remote_get( $endpoint, $options );
		
		//echo "here";
		//print_r($response);
		
		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			$results['ack'] ='error';
			$results['ackmsg'] ='Error 0002: Something went wrong. '.$error_message;
			$results = json_encode($results);
			echo $results;
			die();
		}
		
		$fileurlcontents = json_decode($response['body'],true);	
			
		$reviewcontainerdiv = $fileurlcontents['items'];
		
		//print_r($reviewcontainerdiv);
		
		foreach ($reviewcontainerdiv as $review) {
				$user_name='';
				$userimage='';
				$rating='';
				$datesubmitted='';
				$rtext='';
				$from_url_review='';
				$company_title='';
				$unique_id='';
				$reviewer_id='';
				$title='';
				$reviewer_email='';
				$userpic='';
				$from_url_review='';
				$from_url='';
				$location='';
			
			$user_name=$review['user'];
			
			// Find user_name  reviewer_name
			if($review['user']){
				$name = ucwords(strtolower($review['user']));
			}

			if($review['contact']){
				$reviewer_email=$review['contact'];
			}

			//find rating
			if($review['overall']){
				$rating=$review['overall'];
			}
			
			//find date created_at
			if($review['survey_submit_date']){
				$datesubmitted=$review['survey_submit_date'];
			}
			
		
			//find text
			if($review['comments']){
				$rtext=$review['comments'];
			}
			
			if($review['location']){
				$location=$review['location'];
			}
			
			
			$meta_json ="";
			$meta_data = Array();
			if($review['appointment_date']){
				$meta_data['appointment_date'] = sanitize_text_field($review['appointment_date']);
			}

			if($review['provider']){
				$meta_data['provider'] = sanitize_text_field($review['provider']);
			}
			if($review['delays']){
				$meta_data['delays'] = sanitize_text_field($review['delays']);
			}
			if($review['explanation']){
				$meta_data['explanation'] = sanitize_text_field($review['explanation']);
			}
			if($review['home_care']){
				$meta_data['home_care'] = sanitize_text_field($review['home_care']);
			}
			if($review['information']){
				$meta_data['information'] = sanitize_text_field($review['information']);
			}
			if($review['nurses']){
				$meta_data['nurses'] = sanitize_text_field($review['nurses']);
			}
			if($review['physician']){
				$meta_data['physician'] = sanitize_text_field($review['physician']);
			}
			if($review['recommendation']){
				$meta_data['recommendation'] = sanitize_text_field($review['recommendation']);
			}
			if($review['registration']){
				$meta_data['registration'] = sanitize_text_field($review['registration']);
			}
			if($review['response']){
				$meta_data['response'] = sanitize_text_field($review['response']);
			}
			if(count($meta_data)>0){
				$meta_json = json_encode($meta_data);
			}
			
			

			if($rating>0){
				$reviewsarray[] = [
					 'reviewer_name' => $name,
					 'reviewer_id' => $reviewer_id,
					 'reviewer_email' => $reviewer_email,
					 'userpic' => $userpic,
					 'rating' => $rating,
					 'updated' => $datesubmitted,
					 'review_text' => trim($rtext),
					 'review_title' => trim($title),
					 'from_url' => $from_url,
					 'from_url_review' => $from_url_review,
					 'language_code' => '',
					 'unique_id' => $unique_id,
					 'location' => $location,
					 'recommendation_type' => '',
					 'company_title' =>  '',
					 'company_url' => '',
					 'company_name' => '',
					 'mediaurlsarrayjson' => '',
					 'meta_data' => $meta_json,
					 'owner_response' => '',
					 ];
			}
			//print_r($reviewsarray);
			//die();
		}

		$result['reviews'] = $reviewsarray;
				
		//print_r($result['reviews']);
		//die();

		return $result;
	}

	//for calling remote get and returning array of reviews to insert
	public function wprp_getapps_getrevs_page_reviewsio($type,$listedurl,$pagenum,$perpage,$savedpageid,$nhful,$fid,$blockstoinsert,$nextpageurl){
		$result['ack']='success';
		
			$errormsg='';
			$callurl = $listedurl;
			$reviewsarraytemp = Array();
			
			if (filter_var($callurl, FILTER_VALIDATE_URL)) {
					
				$stripvariableurl = stripslashes($callurl);
				$listedurl = strtok($stripvariableurl, '?');	//remove all parameters
				
				//https://www.reviews.co.uk/company-reviews/store/simplylendingsolutions-co-uk-
				//next page is https://www.reviews.co.uk/company-reviews/store/simplylendingsolutions-co-uk-/1
				//does not work with product reviews
				
				$callurl = $listedurl;
				
				//modify page url if this is $pagenum >1;
				if($pagenum >1){
					if($nextpageurl!=''){
						$callurl =$nextpageurl;
					} else {
						$subtractpage = $pagenum - 1;
						$callurl = $listedurl."/".$subtractpage;
					}
				}

				//echo $callurl;
				$result['callurl'] =$callurl;
				$response = wp_remote_get( $callurl );
				if ( is_array( $response ) && ! is_wp_error( $response ) ) {
					$headers = $response['headers']; // array of http header lines
					$body    = $response['body']; // use the content
				} else {
					$result['ack']=esc_html__('Error: Can not use remote get on this url:', 'wp-review-slider-pro').' '.$callurl;
				}
				
				$fileurlcontents = $response['body'];
				$html = wppro_str_get_html($fileurlcontents);
				
				//actually try to get next page, fall back to using above.
				$nexthref='';
				$result['nextpageurl']='';
				if($pagenum >1){
					if($html->find('ul[class=pagination]', 0)){
						$nexthref = $html->find('ul[class=pagination]', 0) -> lastChild() -> find('a', 0)-> href;
					}
				}
				if($nexthref!=''){
					$result['nextpageurl']= $nexthref;
				}
				
				//echo $html;
				//die();
				
				$jsonschema = $this->get_string_between($fileurlcontents, '<script type="application/ld+json">', '</script>');
				$jsonschemaarray = json_decode($jsonschema,true);
				//use array to find values
				
				//print_r($jsonschemaarray);
				$result['avg']='';
				$result['total']='';
				
				if(!is_array($jsonschemaarray)){
					$result['ack']=esc_html__('Error: Can not find review information. Contact support.', 'wp-review-slider-pro');
					return $result;
					die();
				}
				
				
				//find the average with simplehtmldom
				if($html->find('span[class*=js-reviewsio-avg-rating] strong', 0)){
					$result['avg']= $html->find('span[class*=js-reviewsio-avg-rating] strong', 0)->plaintext;
					$result['avg']=trim($result['avg']);
				}
				//find total from json schema
				if(isset($jsonschemaarray['aggregateRating'])){
					if(isset($jsonschemaarray['aggregateRating']['reviewCount'])){
						$result['total']=$jsonschemaarray['aggregateRating']['reviewCount'];
					}
				}
				
				$reviewcontainerdiv = $jsonschemaarray['review'];
				
				foreach ($reviewcontainerdiv as $review) {
						$user_name='';
						$userimage='';
						$rating='';
						$datesubmitted='';
						$rtext='';
						$from_url_review='';
						$company_title='';
						
					// Find user_name
					if($review['author']['name']){
						$user_name=$review['author']['name'];
					}

					//find rating
					if($review['reviewRating']['ratingValue']){
						$rating=$review['reviewRating']['ratingValue'];
					}
					
					//find date created_at
					if($review['datePublished']){
						$datesubmitted=$review['datePublished'];
					}
					
					//find text
					if($review['reviewBody']){
						$rtext=$review['reviewBody'];
					}

					$recommend="";
					$meta_json ="";
					$meta_data = Array();
					
					if($rating>0){
						$reviewsarraytemp[] = [
								'reviewer_name' => trim($user_name),
								'rating' => $rating,
								'date' => $datesubmitted,
								'review_text' => trim($rtext),
								'type' => $type,
								'company_title' => $company_title
						];
					}
				}
	
					//loop reviews and build new array of just what we need
				$reviewsarrayfinal = Array();
				foreach ($reviewsarraytemp as $item) {
					 $reviewsarrayfinal[] = [
					 'reviewer_name' => trim($item['reviewer_name']),
					 'reviewer_id' => '',
					 'reviewer_email' => '',
					 'userpic' => '',
					 'rating' => $item['rating'],
					 'updated' => $item['date'],
					 'review_text' => $item['review_text'],
					 'review_title' => '',
					 'from_url' => $listedurl,
					 'from_url_review' => '',
					 'language_code' =>'',
					 'location' => '',
					 'recommendation_type' => '',
					 'company_title' =>  '',
					 'company_url' => '',
					 'company_name' => '',
					 'meta_data' => '',
					 ];
				}

				$result['reviews'] = $reviewsarrayfinal;
				
			}
		
		return $result;
	}
    	
	//for calling remote get and returning array of reviews to insert, used for wordpress
	public function wprp_getapps_getrevs_page_wordpress($type,$listedurl,$pagenum,$perpage,$savedpageid,$nhful,$fid,$blockstoinsert){
		$result['ack']='success';
		ini_set('memory_limit','500M');
			$errormsg='';
			$callurl = $listedurl;
			$reviewsarraytemp = Array();
			
			if (filter_var($callurl, FILTER_VALIDATE_URL)) {
					
				$stripvariableurl = stripslashes($callurl);
				$listedurl = strtok($stripvariableurl, '?');	//remove all parameters
				
				//https://wordpress.org/support/plugin/wp-tripadvisor-review-slider/reviews/
				//get totals and averages from first page, then everything else from detail page, url is title
				//next page is https://wordpress.org/support/plugin/wp-tripadvisor-review-slider/reviews/page/2/
				//make sure works for themes as well.
				
				$callurl = $listedurl;
				
				//modify page url if this is $pagenum >1;
				if($pagenum >1){
					$callurl = $listedurl."/page/".$pagenum."/";
				}

				//echo $callurl;
				$result['callurl'] =$callurl;
				$response = wp_remote_get( $callurl );
				if ( is_array( $response ) && ! is_wp_error( $response ) ) {
					$headers = $response['headers']; // array of http header lines
					$body    = $response['body']; // use the content
				} else {
					$result['ack']=esc_html__('Error: Can not use remote get on this url:', 'wp-review-slider-pro').' '.$callurl;
				}
				
				$fileurlcontents = $response['body'];
				$html = wppro_str_get_html($fileurlcontents);
				
				//echo $fileurlcontents;
				//die();
				
				$result['avg']='';
				$result['total']='';
				
				if($html->find('div[class=reviews-total-count]', 0)){
					$result['total']= $html->find('div[class=reviews-total-count]', 0)->plaintext;
					$result['total'] = str_replace(" reviews", "", $result['total']);
					$result['total']=intval($result['total']);
				}
				
				if($html->find('span[class=counter-count]', 0)){
						$numstar[5] = intval($html->find('span[class=counter-count]', 0)->plaintext);
						$numstar[4] = intval($html->find('span[class=counter-count]', 1)->plaintext);
						$numstar[3] = intval($html->find('span[class=counter-count]', 2)->plaintext);
						$numstar[2] = intval($html->find('span[class=counter-count]', 3)->plaintext);
						$numstar[1] = intval($html->find('span[class=counter-count]', 4)->plaintext);
						if($result['total']>0){
							$possiblesumtotal = $result['total']*5;
							$ratedtotal = $numstar[5]*5+$numstar[4]*4+$numstar[3]*3+$numstar[2]*2+$numstar[1]*1;
							$average = ($ratedtotal/$possiblesumtotal)*5;
							$result['avg']=round($average,1);
						}
				}
				
				//need an array of URLs to loop to get actual data
				$reviewurlarray = Array();
				if($html->find('a[class=bbp-topic-permalink]', 0)){
					$reviewurlarraytemp = $html->find('a[class=bbp-topic-permalink]');
					foreach ($reviewurlarraytemp as $reviewurl) {
						$reviewurlarray[]=$reviewurl->href;
					}
				} else {
					//can't find reviews.
					$result['ack']=esc_html__('Error: Can not find reviews for this URL. Contact support.', 'wp-review-slider-pro').' '.$callurl;
				}
				//remove blanks
				$reviewurlarray=array_filter($reviewurlarray);
				$reviewsarraytemp=Array();
				//check count of urls
				if(count($reviewurlarray)>0){
					//if count of reviewurlarray is greater than what we want then slice it to speed things up.array_slice($input, 0, 3);
					if(count($reviewurlarray)>$blockstoinsert){
						$reviewurlarray = array_slice($reviewurlarray, 0, $blockstoinsert);
					}
					//if we find less than 30 then do not loop again.
					if(count($reviewurlarray)<30){
						$result['stoploop']='stop';
					}
					//okay to continue going to each page and getting info.
					foreach ($reviewurlarray as $url) {
						usleep(500000);
						$responsereview = wp_remote_get( $url );
						if ( is_array( $responsereview ) && ! is_wp_error( $responsereview ) ) {
							$headers = $responsereview['headers']; // array of http header lines
							$body    = $responsereview['body']; // use the content
						} else {
							$result['ack']=esc_html__('Error: Can not use remote get on this review url:', 'wp-review-slider-pro').' '.$url;
						}
						$fileurlcontentsreview = $responsereview['body'];
						$htmlreview = wppro_str_get_html($fileurlcontentsreview);
				
						//now should be on individual page. add data to array.
						$user_name='';
						$userimage='';
						$rating='';
						$datesubmitted='';
						$rtext='';
						$from_url_review=$url;
						
						// Find user_name
						if($htmlreview->find('span[class=bbp-author-name]', 0)){
							$user_name= $htmlreview->find('span[class=bbp-author-name]', 0)->plaintext;
						}
						// Find image
						if($htmlreview->find('img[class=avatar avatar-100 photo]', 0)){
							$userpic= $htmlreview->find('img[class=avatar avatar-100 photo]', 0)->src;
							//remove url paramaters and add this... ?s=100&d=retro&r=g
							$userpic = strtok($userpic, '?')."?s=100&d=retro&r=g";
						}
						//find rating
						if($htmlreview->find('div[class=wporg-ratings]', 0)){
								$rating = $htmlreview->find('div[class=wporg-ratings]', 0)->title;
								$rating = str_replace(" out of 5 stars", "", $rating);
								$rating = intval($rating);
						}
						//find date created_at
						if($htmlreview->find('a[class=bbp-topic-permalink]', 0)){
								$datesubmitted= $htmlreview->find('a[class=bbp-topic-permalink]', 0)->title;
								$datesubmitted = substr($datesubmitted, 0, strpos($datesubmitted, "at"));
						}
						//find text
						if($htmlreview->find('div[class=bbp-topic-content]', 0)){
							if($htmlreview->find('div[class=bbp-topic-content]', 0)->find('p',0)){
							$rtext= $htmlreview->find('div[class=bbp-topic-content]', 0)->find('p',0)->plaintext;
							}
						} 
						
						$review_title = '';
						if($htmlreview->find('span[class=bbp-breadcrumb-current]', 0)){
							$review_title= $htmlreview->find('span[class=bbp-breadcrumb-current]', 0)->plaintext;
						} 
						
						$reviewsarraytemp[] = [
								'reviewer_name' => trim($user_name),
								'userpic' => $userpic,
								'rating' => $rating,
								'date' => $datesubmitted,
								'review_title' => trim($review_title),
								'review_text' => trim($rtext),
								'type' => $type,
								'from_url_review' => $from_url_review
						];
						unset($htmlreview);

					}
				}

				//loop reviews and build new array of just what we need
				$reviewsarrayfinal = Array();
				foreach ($reviewsarraytemp as $item) {
					 $reviewsarrayfinal[] = [
					 'reviewer_name' => trim($item['reviewer_name']),
					 'reviewer_id' => '',
					 'reviewer_email' => '',
					 'userpic' => $item['userpic'],
					 'rating' => $item['rating'],
					 'updated' => $item['date'],
					 'review_text' => $item['review_text'],
					 'review_title' => $item['review_title'],
					 'from_url' => $listedurl,
					 'from_url_review' => $item['from_url_review'],
					 'language_code' =>'',
					 'location' => '',
					 'recommendation_type' => '',
					 'company_title' =>  '',
					 'company_url' => '',
					 'company_name' => '',
					 'meta_data' => '',
					 ];
				}
				//print_r($reviewsarrayfinal);
				//die();
				$result['reviews'] = $reviewsarrayfinal;
				
			}
		
		return $result;
	}
	
	//for calling remote get and returning array of reviews to insert
	public function wprp_getapps_getrevs_page_sourceforge($type,$listedurl,$pagenum,$perpage,$savedpageid,$nhful,$fid){
		$result['ack']='success';
		
			$errormsg='';
			$callurl = $listedurl;
			$reviewsarraytemp = Array();
			
			if (filter_var($callurl, FILTER_VALIDATE_URL) && $pagenum=1) {
					
				$stripvariableurl = stripslashes($callurl);
				$listedurl = strtok($stripvariableurl, '?');	//remove all parameters
				
				//https://sourceforge.net/software/product/Visual-Visitor/
				//or
				//https://sourceforge.net/projects/portableapps/reviews/
				//changes depending on software or project.
							
				//$temppage = ($pagenum - 1)*10;
				
				$callurl = $listedurl;

				//echo $callurl;
				$result['callurl'] =$callurl;
				$response = wp_remote_get( $callurl );
				if ( is_array( $response ) && ! is_wp_error( $response ) ) {
					$headers = $response['headers']; // array of http header lines
					$body    = $response['body']; // use the content
				} else {
					$result['ack']=esc_html__('Error: Can not use remote get on this url:', 'wp-review-slider-pro').' '.$callurl;
				}
				
				$fileurlcontents = $response['body'];
				
				
				$html = wppro_str_get_html($fileurlcontents);
				
				$result['avg']='';
				$result['total']='';
				
				if($html->find('div[itemprop=aggregateRating]', 0)){
						$result['avg']= $html->find('div[itemprop=aggregateRating]', 0)->find('span[itemprop=ratingValue]',0)->plaintext;
						$result['total']= $html->find('div[itemprop=aggregateRating]', 0)->find('span[itemprop=reviewCount]',0)->plaintext;
						$result['total']=intval($result['total']);
				}
				//if not set then check for project page
				if($result['avg']=='' && $result['total']==''){
					if($html->find('span[itemprop=ratingValue]', 0)){
						$result['avg']= $html->find('span[itemprop=ratingValue]', 0)->plaintext;
					}
					if($html->find('a[class=count]', 0)){
						$result['total']= $html->find('a[class=count]', 0)->plaintext;
						$result['total']=intval($result['total']);
					}
					
				}

				//print_r($result);
				//echo $html;
				//die();
				
				$reviewcontainerdiv = Array();
				//get the array of review container class
				if($html->find('div[class=m-review]', 0)){
					$reviewcontainerdiv = $html->find('div[class=m-review]');
				}

				foreach ($reviewcontainerdiv as $review) {
						$user_name='';
						$userimage='';
						$rating='';
						$datesubmitted='';
						$rtext='';
						$from_url_review='';
						$company_title='';
						
					// Find user_name
					if($review->find('div[class=ext-review-meta]', 0)){
						$user_name= $review->find('div[class=ext-review-meta]', 0)->find('div',0)->plaintext;
					}
					if($user_name==''){
						//probably on a project page
						if($review->find('span[class=author-name]', 0)){
							$user_name= $review->find('span[class=author-name]', 0)->plaintext;
						}
					}
					//company title ,company_title
					if($review->find('div[class=ext-review-meta]', 0)){
						$company_title= $review->find('div[class=ext-review-meta]', 0)->find('div[class=value-label]',0)->plaintext;
					}

					//find rating
					if($review->find('meta[itemprop=ratingValue]', 0)){
							$rating = $review->find('meta[itemprop=ratingValue]', 0)->content;
							$rating = str_replace(" ", "", $rating);
							$rating = $rating;
					}
					
					//find date created_at
					if($review->find('span[class=created-date]', 0)){
							$datesubmitted= $review->find('span[class=created-date]', 0)->plaintext;
							$datesubmitted = str_replace("Posted", "", $datesubmitted);
							$datesubmitted = str_replace(" ", "", $datesubmitted);
					}
					//find text, look for expandable text first $rtext
					if($review->find('div[class=ext-review-content]', 0)){
						
						$rtext_pros= $review->find('div[class=ext-review-content]', 0)->find('p',0)->innertext;
						$rtext_cons= $review->find('div[class=ext-review-content]', 0)->find('p',1)->innertext;
						$rtext_overall= $review->find('div[class=ext-review-content]', 0)->find('p',2)->innertext;
						$rtext=$rtext_pros."<br><br>".$rtext_cons."<br><br>".$rtext_overall."<br><br>";
						//need to delete all html except for br and b3
						$rtext=strip_tags($rtext,'<b><br>');
					} 
					if($rtext==''){
						//probably on a project page
						if($review->find('div[class=review-txt]', 0)){
							$rtext= $review->find('div[class=review-txt]', 0)->plaintext;
						}
					}

					$recommend="";
					$meta_json ="";
					$meta_data = Array();
					

					if($rating>0){
						$reviewsarraytemp[] = [
								'reviewer_name' => trim($user_name),
								'rating' => $rating,
								'date' => $datesubmitted,
								'review_text' => trim($rtext),
								'type' => $type,
								'company_title' => $company_title
						];
					}
				}
				//print_r($reviewsarraytemp);
				//die();
				
				//loop reviews and build new array of just what we need
				foreach ($reviewsarraytemp as $item) {
					 $reviewsarrayfinal[] = [
					 'reviewer_name' => trim($item['reviewer_name']),
					 'reviewer_id' => '',
					 'reviewer_email' => '',
					 'userpic' => '',
					 'rating' => $item['rating'],
					 'updated' => $item['date'],
					 'review_text' => $item['review_text'],
					 'review_title' => '',
					 'from_url' => $listedurl,
					 'from_url_review' => '',
					 'language_code' =>'',
					 'location' => '',
					 'recommendation_type' => '',
					 'company_title' =>  $item['company_title'],
					 'company_url' => '',
					 'company_name' => '',
					 'meta_data' => '',
					 ];
				}
				//print_r($reviewsarrayfinal);
				//die();
				$result['reviews'] = $reviewsarrayfinal;
				
			}
		
		return $result;
	}
	//for calling remote get and returning array of reviews to insert
	public function wprp_getapps_getrevs_page_guildquality($type,$listedurl,$pagenum,$perpage,$savedpageid,$nhful,$fid){
		$result['ack']='success';
		
			$errormsg='';
			$callurl = $listedurl;
			$reviewsarraytemp = Array();
			
			if (filter_var($callurl, FILTER_VALIDATE_URL) && $pagenum=1) {
					
				$stripvariableurl = stripslashes($callurl);
				$listedurl = strtok($stripvariableurl, '?');	//remove all parameters
				
				//https://www.guildquality.com/pro/model-remodel?tab=reviews
				//only getting first page. 50 reviews max.
				
				$callurl = $listedurl.'?tab=reviews';

				//echo $callurl;
				$result['callurl'] =$callurl;
				$args = array(
					'timeout'     => 15,
					'sslverify' => false
				); 
				$response = wp_remote_get($callurl,$args);
				if ( is_array( $response ) && ! is_wp_error( $response ) ) {
					$headers = $response['headers']; // array of http header lines
					$body    = $response['body']; // use the content
				} else {
					$result['ack']=esc_html__('Error: Can not use remote get on this url:', 'wp-review-slider-pro').' '.$callurl;
				}
				
				$fileurlcontents = $response['body'];
				
				
				$html = wppro_str_get_html($fileurlcontents);
				
				$result['avg']='';
				$result['total']='';
				
				if($html->find('meta[itemprop=reviewCount]', 0)){
						$result['total']= $html->find('meta[itemprop=reviewCount]', 0)->content;
						$result['total']=intval($result['total']);
				}
				if($html->find('meta[itemprop=ratingValue]', 0)){
						$result['avg']= $html->find('meta[itemprop=ratingValue]', 0)->content;
				}
				
				
				$reviewcontainerdiv = Array();
				//get the array of review container class
				if($html->find('div[itemprop=review]', 0)){
					$reviewcontainerdiv = $html->find('div[itemprop=review]');
				}

				foreach ($reviewcontainerdiv as $review) {
						$user_name='';
						$userimage='';
						$rating='';
						$datesubmitted='';
						$rtext='';
						$from_url_review='';
						$location='';
						
					// Find user_name
					if($review->find('span[itemprop=name]', 0)){
						$user_name= $review->find('span[itemprop=name]', 0)->plaintext;
					}

					//location
					if($review->find('div[class=mat-meta]', 0)){
						$location= $review->find('div[class=mat-meta]', 0)->innertext;
						//remove everything inside <>
						$location= preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $location);
						$location = str_replace("&middot;", "", $location);
						$location = str_replace("·", "", $location);
						$location=trim($location);
					}

					//find rating
					if($review->find('meta[itemprop=ratingValue]', 0)){
							$rating = $review->find('meta[itemprop=ratingValue]', 0)->content;
							$rating = str_replace(" ", "", $rating);
							$rating = $rating;
					}
					
					//find date created_at
					if($review->find('span[itemprop=datePublished]', 0)){
							$datesubmitted= $review->find('span[itemprop=datePublished]', 0)->datetime;
					}
					//find text, look for expandable text first $rtext
					if($review->find('p[itemprop=reviewBody]', 0)){
						$rtext= $review->find('p[itemprop=reviewBody]', 0)->plaintext;
					} 
					
					//----------------
					//add review images here
					//mediaurlsarrayjson, max of 8 images
					//--------------
					$mediaurlsarrayjson='';
					$mediaurlsarray = Array();
					if($review->find('a[class=mat-card_review-img]')){
						//have at least one image
						$imagesobject = $review->find('a[class=mat-card_review-img]');
						foreach ($imagesobject as $imageobj) {
							$mediaimg= $imageobj->style;
							$tempurl = $this->get_string_between($mediaimg, "background-image: url('", "');");
							if($tempurl!=''){
							$mediaurlsarray[]=$this->get_string_between($mediaimg, "background-image: url('", "');");
							}
							if(count($mediaurlsarray)>7){
								break;
							}
							$mediaimg='';
							$tempurl='';
						}
						$mediaurlsarrayjson = json_encode($mediaurlsarray);
						unset($mediaurlsarray);
					}
					//========loop to find all media links up to 8.


					$recommend="";
					$meta_json ="";
					$meta_data = Array();
					
					if($rating>0){
						$reviewsarraytemp[] = [
								'reviewer_name' => trim($user_name),
								'rating' => $rating,
								'date' => $datesubmitted,
								'review_text' => trim($rtext),
								'type' => $type,
								'location' => $location,
								'mediaurlsarrayjson' => $mediaurlsarrayjson,
						];
					}
				}
				
				//loop reviews and build new array of just what we need
				foreach ($reviewsarraytemp as $item) {
					 $reviewsarrayfinal[] = [
					 'reviewer_name' => trim($item['reviewer_name']),
					 'reviewer_id' => '',
					 'reviewer_email' => '',
					 'userpic' => '',
					 'rating' => $item['rating'],
					 'updated' => $item['date'],
					 'review_text' => $item['review_text'],
					 'review_title' => '',
					 'from_url' => $listedurl,
					 'from_url_review' => '',
					 'language_code' =>'',
					 'location' => $item['location'],
					 'recommendation_type' => '',
					 'company_title' =>  '',
					 'company_url' => '',
					 'company_name' => '',
					 'meta_data' => '',
					 'mediaurlsarrayjson' => $item['mediaurlsarrayjson'],
					 ];
				}
				//print_r($reviewsarrayfinal);
				//die();
				$result['reviews'] = $reviewsarrayfinal;
				
			}
		
		return $result;
	}
	
	//for calling remote get and returning array of reviews to insert
	public function wprp_getapps_getrevs_page_airbnb($type,$listedurl,$pagenum,$perpage,$savedpageid,$nhful,$fid,$blockstoinsert,$nextpageurl){
		$result['ack']='success';
		
		$parseurl = parse_url($listedurl);
		$baseurl = $parseurl['scheme'].'://'.$parseurl['host'];
		
			$errormsg='';
			$callurl = $listedurl;
			$reviewsarraytemp = Array();
			
			if (filter_var($callurl, FILTER_VALIDATE_URL)) {
					
				$stripvariableurl = stripslashes($callurl);
				$stripvariableurl = strtok($stripvariableurl, '?');	//remove all parameters
				
				$listing_id = (int) filter_var($stripvariableurl, FILTER_SANITIZE_NUMBER_INT);
				
				//find the reviewurl for this URL if this is first call_user_func
				if($pagenum==1){
					if(strpos($listedurl, '/experiences/') !== false){		
						//experiences, get different api key stuff
						$isexperience = true;
						$limit=50;
						if($blockstoinsert<50){
							$limit=$blockstoinsert;
						}
						
						$urldetails = $this->getreviewurl_airbnb($stripvariableurl, $listing_id, 'experience', $limit);
						$callurl = $urldetails['url'];
						
						//print_r($urldetails);
						
					} else {
						$isexperience = false;
						$urldetails = $this->getreviewurl_airbnb($stripvariableurl, $listing_id, 'room');
						
						if($blockstoinsert<100){
							$callurl =$urldetails['url']."&_limit=".$blockstoinsert."&_offset=0";
						} else {
							$callurl =$urldetails['url']."&_limit=100&_offset=0";
						}
						$result['nextpageurl']= $urldetails['url'];
					}

				} else {
					//for pages after first page.
					if(strpos($listedurl, '/experiences/') !== false){		
						//experiences, get different api key stuff
						$isexperience = true;
						$limit=50;
						if($blockstoinsert<50){
							$limit=$blockstoinsert;
						}
						$offset = ($pagenum - 1)*50;
						
						$urldetails = $this->getreviewurl_airbnb($stripvariableurl, $listing_id, 'experience', $limit,$offset);
						$callurl = $urldetails['url'];

					} else {
						//need to set nextpageurl
						$result['nextpageurl']= $nextpageurl;
						
						$offset = ($pagenum - 1)*100;
						$callurl = $nextpageurl."&_limit=100&_offset=".$offset;
					}

				}
				//pass back next URL used
				if(isset($crawlerresultarray['nextpageurl'])){
					$result['nextpageurl']=$crawlerresultarray['nextpageurl'];
				}
			

				$result['callurl'] =$callurl;
				$args = array(
					'timeout'     => 20,
					'sslverify' => false
				); 
				
				//echo $callurl;
				//echo "<br>";
				//echo $urldetails['key'];
				//die();
				if($isexperience){
					$response = wp_remote_get( $callurl ,
						 array( 'timeout' => 30,
						'headers' => array( 'X-Airbnb-API-Key' => $urldetails['key']) 
						 ));
				} else {
					$response = wp_remote_get( $callurl );
				}

				if ( is_array( $response ) && ! is_wp_error( $response ) ) {
					$headers = $response['headers']; // array of http header lines
					$body    = $response['body']; // use the content
				} else {
					$result['ack']=esc_html__('Error: Can not use remote get on this url:', 'wp-review-slider-pro').' '.$callurl;
				}
				
				$pagedata = json_decode( $response['body'], true );
				
				//print_r($pagedata);
				
				if($pagenum==1){
					$result['avg']='';
					$result['total']='';
					if(isset($pagedata['metadata']['reviews_count'])){
						$result['total']= $pagedata['metadata']['reviews_count'];
					}
					if(isset($pagedata['metadata']['reviewsCount'])){
						$result['total']= $pagedata['metadata']['reviewsCount'];
					}
					if($urldetails['avg']){
							$result['avg']= $urldetails['avg'];
					}
				}
				
				if($isexperience){
					$reviewsarray = $pagedata['data']['merlin']['pdpReviews']['reviews'];
				} else {
					$reviewsarray = $pagedata['reviews'];
				}


				foreach ($reviewsarray as $review) {
						$reviewer_id ='';
						$user_name='';
						$userimage='';
						$rating='';
						$datesubmitted='';
						$rtext='';
						$from_url_review='';
						$location='';
						$language_code='';
						
						if($isexperience){
							// Find user_name
							if($review['reviewer']['firstName']){
								$user_name = $review['reviewer']['firstName'];
							}
							
							// Find userimage ui_avatar
							if($review['reviewer']['pictureUrl']){
								$userimage = $review['reviewer']['pictureUrl'];
							}
							
							// find date created_at
							if($review['createdAt']){
								$datesubmitted = $review['createdAt'];
							}

						} else {
							// Find user_name
							if($review['reviewer']['first_name']){
								$user_name = $review['reviewer']['first_name'];
							}
							
							// Find userimage ui_avatar
							if($review['reviewer']['picture_url']){
								$userimage = $review['reviewer']['picture_url'];
							}
							
							// find date created_at
							if($review['created_at']){
								$datesubmitted = $review['created_at'];
							}
						}


					// find rating
					if($review['rating']){
						$rating = $review['rating'];
					}

					
					// find text
					if($review['comments']){
						$rtext = $review['comments'];
					}
					
					//user profile who left review
					if(isset($review['reviewer'])){
						if(isset($review['reviewer']['profile_path'])){
							$from_url_review = $baseurl.$review['reviewer']['profile_path'];
						}
						if(isset($review['reviewer']['profilePath'])){
							$from_url_review = $baseurl.$review['reviewer']['profilePath'];
						}
					}
					if(isset($review['author'])){
						if($review['author']['profile_path']){
							$from_url_review = $baseurl.$review['author']['profile_path'];
						}
					}
					
					if($review['language']){
						$language_code = $review['language'];
					}
					
					//owner_response
					$owner_response_encode ='';
					$owner['id'] = '';
					$owner['name'] = '';
					$owner['comment'] = '';
					$owner['date'] = '';
					//mgrRspnInline
					if($review['response']){
						//must be a response
						if(isset($review['reviewee']['host_name'])){
							$owner['name']= $review['reviewee']['host_name'];
						} if(isset($review['reviewee']['hostName'])){
							$owner['name']= $review['reviewee']['hostName'];
						} else {
							$owner['name'] = 'Response from the owner';
						}
						//responseDate
						if(isset($review['localizedRespondedDate'])){
						 $tempdate = $this->myStrtotime($review['localizedRespondedDate']);	
						} else {
						 $tempdate = $this->myStrtotime($datesubmitted);
						}
						$owner['date'] = date('Y-m-d', $tempdate);
						$owner['comment'] = $review['response'];
					}
					if($owner['comment']!=''){
						$owner_response_encode = json_encode($owner);
					}
					
					if($rating>0){
						$reviewsarraytemp[] = [
								'reviewer_name' => trim($user_name),
								'reviewer_id' => trim($reviewer_id),
								'userpic' => $userimage,
								'rating' => $rating,
								'date' => $datesubmitted,
								'review_text' => trim($rtext),
								'type' => $type,
								'language_code' => $language_code,
								'location' => $location,
								'from_url_review' => $from_url_review,
								'owner_response' => $owner_response_encode,
						];
					}
				}
				
				//loop reviews and build new array of just what we need
				$reviewsarrayfinal = Array();
				foreach ($reviewsarraytemp as $item) {
					 $reviewsarrayfinal[] = [
					 'reviewer_name' => trim($item['reviewer_name']),
					 'reviewer_id' => trim($item['reviewer_id']),
					 'reviewer_email' => '',
					 'userpic' => $item['userpic'],
					 'rating' => $item['rating'],
					 'updated' => $item['date'],
					 'review_text' => $item['review_text'],
					 'review_title' => '',
					 'from_url' => $listedurl,
					 'from_url_review' => $item['from_url_review'],
					 'language_code' =>$item['language_code'],
					 'location' => $item['location'],
					 'recommendation_type' => '',
					 'company_title' =>  '',
					 'company_url' => '',
					 'company_name' => '',
					 'meta_data' => '',
					 'mediaurlsarrayjson' => '',
					 'owner_response' => $item['owner_response'],
					 ];
				}
				//print_r($reviewsarrayfinal);
				//die();
				$result['reviews'] = $reviewsarrayfinal;
				
			}
		
		return $result;
	}
	
	//for calling remote get and returning array of reviews to insert, calling Crawler now crawl.ljapps.com
	public function wprp_getapps_getrevs_page_vrbo($type,$listedurl,$pagenum,$perpage,$savedpageid,$nhful,$fid,$blockstoinsert,$nextpageurl){
		$result['ack']='success';
		
			$errormsg='';
			$reviewsarraytemp = Array();
			$nhful='new';
			$reviewsarray= Array();
			
			if (filter_var($listedurl, FILTER_VALIDATE_URL)) {
					
				if(isset($_SERVER['SERVER_ADDR']) && $_SERVER['SERVER_ADDR']!=''){
					$ip_server = $_SERVER['SERVER_ADDR'];
				} else {
					//get url of site.
					$ip_server = urlencode(get_site_url());
				}
				$siteurl = urlencode(get_site_url());
				
				//scrapeurl
				$tempurlval = 'https://crawl.ljapps.com/crawlrevs?rip='.$ip_server.'&surl='.$siteurl.'&scrapeurl='.$listedurl.'&stype=vrbo&sfp=pro&nobot=1&nhful='.$nhful.'&locationtype=&scrapequery=&tempbusinessname=&pagenum='.$pagenum.'&nextpageurl='.$nextpageurl;
				
				//echo $tempurlval;
				//die();
				
				$serverresponse='';
				
				$args = array(
					'timeout'     => 120,
					'sslverify' => false
				); 
				$response = wp_remote_get( $tempurlval, $args );
				if ( is_array( $response ) && ! is_wp_error( $response ) ) {
					$headers = $response['headers']; // array of http header lines
					$serverresponse    = $response['body']; // use the content
				} else {
					//must have been an error
					$results['ack'] ='error';
					$results['ackmsg'] ='Error 0001a: trouble contacting crawling server with remote_get. Please try again or contact support. '.$response->get_error_message();
					$results = json_encode($results);
					echo $results;
					die();
				}
					
				$serverresponsearray = json_decode($serverresponse, true);

				if($serverresponse=='' || !is_array($serverresponsearray)){
					$results['ack'] ='error';
					$results['ackmsg'] ='Error 0001: trouble contacting crawling server. Please try again or contact support.';
					$results = json_encode($results);
					echo $results;
					die();
				}
				//catch limit error
				if($serverresponsearray['ack']=='error'){
					$results['ack'] ='error';
					$results['ackmsg'] ='Error 0002: '.$serverresponsearray['ackmessage'];
					$results = json_encode($results);
					echo $results;
					die();
				}
				if(!isset($serverresponsearray['result']) || !is_array($serverresponsearray['result'])){
					$results['ack'] ='error';
					$results['ackmsg'] ='Error 0002b: trouble finding reviews. Contact support with this error code and the search terms or place id you are using.';
					$results = json_encode($results);
					echo $results;
					die();
				}
				//catch error
				if($serverresponsearray['result']['ack']=='error'){
					$results['ack'] ='error';
					$results['ackmsg'] ='Error 0003: '.$serverresponsearray['ackmessage'].' : '.$serverresponsearray['result']['ackmsg'];
					$results = json_encode($results);
					echo $results;
					die();
				}
				//made it this far assume we have reviews.
				$crawlerresultarray = $serverresponsearray['result'];
				

				//need totals and avg for this place $getreviewsarray['total']
				$result['total']='';
				$result['avg']='';
				if(isset($crawlerresultarray['avg'])){
					$result['avg']=$crawlerresultarray['avg'];
				}
				if(isset($crawlerresultarray['total'])){
					$result['total']=$crawlerresultarray['total'];
				}
				
				//pass back URL used
				if(isset($crawlerresultarray['callurl'])){
					$result['callurl']=$crawlerresultarray['callurl'];
				}
				//pass back next URL used
				if(isset($crawlerresultarray['nextpageurl'])){
					$result['nextpageurl']=$crawlerresultarray['nextpageurl'];
				}
				
				$x=0;
				$crawlerreviewsarray = $crawlerresultarray['reviews'];
				$numreturned = count($crawlerreviewsarray);	
				
				foreach ($crawlerreviewsarray as $review) {
					
					$tempownerres='';
					if(isset($review['owner_response']) && $review['owner_response']!=''){
						$tempownerres = $review['owner_response'];
					}
					$templocation ='';
					if(isset($review['location']) && $review['location']!=''){
						$templocation = $review['location'];
					}	
					$tempmediaurlsarrayjson ='';
					if(isset($review['mediaurlsarrayjson']) && $review['mediaurlsarrayjson']!=''){
						$tempmediaurlsarrayjson = $review['mediaurlsarrayjson'];
					}					
					
					$reviewsarray[] = [
					 'reviewer_name' => $review['user_name'],
					 'reviewer_id' => '',
					 'reviewer_email' => '',
					 'userpic' => $review['userimage'],
					 'rating' => $review['rating'],
					 'updated' => $review['datesubmitted'],
					 'review_text' => $review['rtext'],
					 'review_title' => '',
					 'from_url' => $listedurl,
					 'from_url_review' => '',
					 'language_code' => '',
					 'location' => $templocation,
					 'recommendation_type' => '',
					 'company_title' =>  '',
					 'company_url' => '',
					 'company_name' => '',
					 'mediaurlsarrayjson' => $tempmediaurlsarrayjson,
					 'owner_response' => $tempownerres,
					 ];
					
					$x++;
				}
				
				//if we find less than 10 then do not loop again.
					if(count($reviewsarray)<10){
						$result['stoploop']='stop';
					}

			$result['reviews'] = $reviewsarray;

			}
		return $result;
	}

	
	
	//for calling remote get and returning array of reviews to insert
	public function wprp_getapps_getrevs_page_vrbo_oldDELETE($type,$listedurl,$pagenum,$perpage,$savedpageid,$nhful,$fid,$blockstoinsert,$nextpageurl){
		$result['ack']='success';
		$stripvariableurl = strtok($listedurl, '?');
		$reviewsarraytemp = Array();

		$callurl = "https://phantomjscloud.com/api/browser/v2/a-demo-key-with-low-quota-per-ip-address/?request=%7Burl:%22".$stripvariableurl."%22,renderType:%22html%22,scripts:%7BdomReady:%5B%22setInterval%28function%28%29%7Bvar%20lastY=window.scrollY;window.scrollBy%280,200%29;if%28lastY===window.scrollY%29%7Bwindow._pjscMeta.forceFinish=true;%7D%7D,50%29;%22%5D%7D%7D";
		
		//echo $callurl;
		//die();
		
		$args = array(
				'timeout'     => 30,
				'sslverify' => false
			); 
		$data = wp_remote_get( $callurl,$args );
		if ( is_wp_error( $data ) ) 
		{
			$response['error_message'] 	= $data->get_error_message();
			$reponse['status'] 		= $data->get_error_code();
			print_r($response);
			die();
		}
		$fileurlcontents = $data['body'];
		
		//check for phantom out of credits.
		//dailySubscriptionBalance
		if (strpos($fileurlcontents, "dailySubscriptionBalance") !== false) {
			$results['ack'] ='error';
			$results['ackmsg'] = esc_html__('Error 1001: Out of credits.', 'wp-review-slider-pro');
			$results = json_encode($results)." - ".$fileurlcontents;
			echo $results;
			die();
		}
					
		
		$html = wppro_str_get_html($fileurlcontents);
		
		if($html->find('div.review__content')){
			$reviewobject = $html->find('div.review__content');
		}
		if(!isset($reviewobject)){
			if($html->find('article[itemprop=review]')){
				$reviewobject = $html->find('article[itemprop=review]');
			}
		}
		if(!isset($reviewobject) || count($reviewobject)<1){
			echo $html;
			$results['ack'] ='error';
			$results['ackmsg'] = esc_html__('Error 103: Unable to read VRBO page. Please contact support or use the Review Funnel page.', 'wp-review-slider-pro');
			$results = json_encode($results);
			echo $results;
			die();
		}
		
		
		foreach ($reviewobject as $review) {
			
			$user_name='';
			$userimage='';
			$rating='';
			$datesubmitted='';
			$rtext='';
			$from_url_review='';
			$location='';
			$language_code='';
			$review_title ='';
			
			// Find user_name
			if($review->find('span[class=review__author-name]', 0)){
				$user_name = $review->find('span[class=review__author-name]', 0)->plaintext;
			}
			if($user_name==''){
				if($review->find('h5[itemprop=author]', 0)){
					$user_name = $review->find('h5[itemprop=author]', 0)->plaintext;
				}
			}
			
			
			// Find location
			if($review->find('span[class=review__author-location]', 0)){
				$location = $review->find('span[class=review__author-location]', 0)->plaintext;
			}


			// find rating rating__score text-muted small
			if($review->find('span[class=rating__score text-muted small]', 0)){
				$rating = $review->find('span[class=rating__score text-muted small]', 0)->plaintext;
				$rating = str_replace("/5","",$rating);
			}
			if($rating==''){
				if($review->find('span[itemprop=ratingValue]', 0)){
					$rating = $review->find('span[itemprop=ratingValue]', 0)->plaintext;
					$rating = str_replace("/5","",$rating);
					$rating = intval($rating);
				}
			}
			
			// find date created_at
			if($review->find('span[class=review__submitted-details]', 0)){
				$datesubmitted = $review->find('span[class=review__submitted-details]', 0)->plaintext;
			}
			if($datesubmitted==''){
				if($review->find('span[itemprop=datePublished]', 0)){
					$datesubmitted = $review->find('span[itemprop=datePublished]', 0)->plaintext;
				}
			}
			
			// find headline
			//review__headline
			if($review->find('h4[class=review__headline]', 0)){
				$review_title = $review->find('h4[class=review__headline]', 0)->plaintext;
			}
			if($review_title==''){
				if($review->find('h5[data-stid=review_section_title]', 0)){
					$review_title = $review->find('h5[data-stid=review_section_title]', 0)->plaintext;
				}
			}
			
			//review--expanded
			if($review->find('div[class=review--expanded]', 0)){
				$rtext = $review->find('div[class=review--expanded]', 0)->plaintext;
			}
			if($rtext==''){
				if($review->find('span[itemprop=description]', 0)){
					$rtext = $review->find('span[itemprop=description]', 0)->plaintext;
				}
			}
			

			if($review->find('div[class=review__response]', 0)){
				$responobj = $review->find('div[class=review__response]', 0);
				if($responobj->find('span[class=review__response-body]', 0)){
					$ownerresponsearray = [];
					$ownerresponsearray['id']='';
					$ownerresponsearray['name']='Owner';
					$ownerresponsearray['date']='';
					$ownerresponsearray['comment']=$responobj->find('span[class=review__response-body]', 0)->plaintext;
					$ownerresponsearray = json_encode($ownerresponsearray);
				} else {
					$ownerresponsearray ='';
				}
				
			} else {
				$ownerresponsearray ='';
			}
			
			if($rating>0){
				$review_length = str_word_count($rtext);
				if (extension_loaded('mbstring')) {
					$review_length_char = mb_strlen($rtext);
				} else {
					$review_length_char = strlen($rtext);
				}
				if($review_length_char>0 && $review_length<1){
								$review_length = 1;
							}
				

				$reviewsarraytemp[] = [
							'reviewer_name' => trim($user_name),
							'reviewer_id' => '',
							'userpic' => $userimage,
							'rating' => $rating,
							'date' => $datesubmitted,
							'review_text' => trim($rtext),
							'review_title' => $review_title,
							'type' => $type,
							'language_code' => $language_code,
							'location' => $location,
							'from_url_review' => $from_url_review,
							'owner_response' => $ownerresponsearray,
				];
	
				
				$review_length ='';
				$review_length_char='';
			}
		}
		
				
				//loop reviews and build new array of just what we need
				$reviewsarrayfinal = Array();
				foreach ($reviewsarraytemp as $item) {
					 $reviewsarrayfinal[] = [
					 'reviewer_name' => trim($item['reviewer_name']),
					 'reviewer_id' => trim($item['reviewer_id']),
					 'reviewer_email' => '',
					 'userpic' => $item['userpic'],
					 'rating' => $item['rating'],
					 'updated' => $item['date'],
					 'review_text' => $item['review_text'],
					 'review_title' => $item['review_title'],
					 'from_url' => $listedurl,
					 'from_url_review' => $item['from_url_review'],
					 'language_code' =>$item['language_code'],
					 'location' => $item['location'],
					 'recommendation_type' => '',
					 'company_title' =>  '',
					 'company_url' => '',
					 'company_name' => '',
					 'meta_data' => '',
					 'mediaurlsarrayjson' => '',
					 'owner_response' => $item['owner_response'],
					 ];
				}
				//print_r($reviewsarrayfinal);
				//die();
				$result['reviews'] = $reviewsarrayfinal;
				//we can currently only get one page of reviews so we send stop command back
				$result['stoploop']='stop';
			
  
 		return $result;
	}
	
	//for calling remote get and returning array of reviews to insert
	public function wprp_getapps_getrevs_page_vrbo_old($type,$listedurl,$pagenum,$perpage,$savedpageid,$nhful,$fid,$blockstoinsert,$nextpageurl){
		$result['ack']='success';
		$stripvariableurl = strtok($listedurl, '?');
		$stripvariableurl = $stripvariableurl."?pwaDialogNested=PropertyDetailsReviewsBreakdownDialog";
		$reviewsarraytemp = Array();
		
		echo $stripvariableurl;
		die();
		
		//https://phantomjscloud.com/api/browser/v2/a-demo-key-with-low-quota-per-ip-address/?request=%7Burl:%22https://www.vrbo.com/741538%22,renderType:%22jpg%22,scripts:%7BdomReady:%5B%22setInterval%28function%28%29%7Bvar%20lastY=window.scrollY;window.scrollBy%280,200%29;if%28lastY===window.scrollY%29%7Bwindow._pjscMeta.forceFinish=true;%7D%7D,50%29;%22%5D%7D%7D
		
		$args = array(
				'timeout'     => 30,
				'sslverify' => false
			); 
		$data = wp_remote_get( $stripvariableurl,$args );
		if ( is_wp_error( $data ) ) 
		{
			$response['error_message'] 	= $data->get_error_message();
			$reponse['status'] 		= $data->get_error_code();
			print_r($response);
			die();
		}
	
		$fileurlcontents = $data['body'];
		
		echo $fileurlcontents;
		die();
		
		$result['total'] = intval($this->get_string_between($fileurlcontents, '"reviewCount": ', '}'));
		$result['avg'] = $this->get_string_between($fileurlcontents, '"ratingValue": ', ',');
		
		//echo 'total:'.$result['total'];
		//echo 'avg:'.$result['avg'];
		
		$parsed = $this->get_string_between($fileurlcontents, '"reviews":[', '],');
		$parsed = "[".$parsed."]";

		$pagedata = json_decode( $parsed, true );
		
		print_r($pagedata);
		die();
		
		foreach ($pagedata as $review) {
			
			$user_name='';
			$userimage='';
			$rating='';
			$datesubmitted='';
			$rtext='';
			$reviewer_id='';
			$from_url_review='';
			$location='';
			$language_code='';
			$review_title ='';
			
			// Find user_name
			if($review['reviewer']['nickname']){
				$user_name = $review['reviewer']['nickname'];
			}
			
			// Find userimage ui_avatar
			if(isset($review['reviewer']['profileImage']) && $review['reviewer']['profileImage']!=''){
				$userimage = $review['reviewer']['profileImage'];
			}
			
			// Find language_code
			if($review['reviewLanguage']){
				$language_code = $review['reviewLanguage'];
			}
			
			// Find user_name
			if($review['reviewer']['location'] && $review['reviewer']['location']!='null'){
				$location = $review['reviewer']['location'];
			}

			// find rating
			if($review['rating']){
				$rating = $review['rating'];
			}

			// find date created_at
			if($review['datePublished']){
				$datesubmitted = $review['datePublished'];
			}
			
			// find headline
			
			if($review['headline']){
				$review_title = $review['headline'];
			}
			if($review['body']){
				$rtext = $review['body'];
			}
			
			//owner response
			//{ "id":12630808, "name":"Response from the owner", "date":"2018-08-24", "comment":"Raul - this is a very bad example of how the trip went. Sorry you feel that way, and hope you have better luck with another charter..." }
			//{"id":16369073,"name":"Response from the owner","date":"2014-05-29","comment":"Thank You - Jennifer.  Your family is always a pleasure to have aboard.  Fish On!!"}
			
			if(isset($review['response']) && $review['response']['body']){
				$ownerresponsearray = [];
				$responsebody = $review['response']['body'];
				$ownerresponsearray['id']='';
				$ownerresponsearray['name']='Owner';
				$ownerresponsearray['date']='';
				$ownerresponsearray['comment']=$responsebody;
				$ownerresponsearray = json_encode($ownerresponsearray);
			} else {
				$ownerresponsearray ='';
			}
			
			
			if($rating>0){
				$review_length = str_word_count($rtext);
				if (extension_loaded('mbstring')) {
					$review_length_char = mb_strlen($rtext);
				} else {
					$review_length_char = strlen($rtext);
				}
				if($review_length_char>0 && $review_length<1){
								$review_length = 1;
							}
				

				$furlrev = 'https://www.vrbo.com/users/show/'.trim($reviewer_id);
				$reviewsarraytemp[] = [
							'reviewer_name' => trim($user_name),
							'reviewer_id' => trim($reviewer_id),
							'userpic' => $userimage,
							'rating' => $rating,
							'date' => $datesubmitted,
							'review_text' => trim($rtext),
							'review_title' => $review_title,
							'type' => $type,
							'language_code' => $language_code,
							'location' => $location,
							'from_url_review' => $from_url_review,
							'owner_response' => $ownerresponsearray,
				];
	
				
				$review_length ='';
				$review_length_char='';
			}
		}
		
				
				//loop reviews and build new array of just what we need
				$reviewsarrayfinal = Array();
				foreach ($reviewsarraytemp as $item) {
					 $reviewsarrayfinal[] = [
					 'reviewer_name' => trim($item['reviewer_name']),
					 'reviewer_id' => trim($item['reviewer_id']),
					 'reviewer_email' => '',
					 'userpic' => $item['userpic'],
					 'rating' => $item['rating'],
					 'updated' => $item['date'],
					 'review_text' => $item['review_text'],
					 'review_title' => $item['review_title'],
					 'from_url' => $listedurl,
					 'from_url_review' => $item['from_url_review'],
					 'language_code' =>$item['language_code'],
					 'location' => $item['location'],
					 'recommendation_type' => '',
					 'company_title' =>  '',
					 'company_url' => '',
					 'company_name' => '',
					 'meta_data' => '',
					 'mediaurlsarrayjson' => '',
					 'owner_response' => $item['owner_response'],
					 ];
				}
				//print_r($reviewsarrayfinal);
				//die();
				$result['reviews'] = $reviewsarrayfinal;
				//we can currently only get one page of reviews so we send stop command back
				$result['stoploop']='stop';
			
  
 		return $result;
	}
	
	//used for Zillow and Realtor remote crawls
	public function wprp_getapps_getrevs_page_multi_remote($sitetype,$listedurl,$pagenum,$perpage,$savedpageid,$sortoption,$fid,$blockstoinsert,$nextpageurl){
		$result['ack']='success';
	
		$errormsg='';
		$reviewsarraytemp = Array();
		$nhful='new';
		$reviewsarray= Array();
		$crawlerreviewsarray= Array();
		
		if (filter_var($listedurl, FILTER_VALIDATE_URL)) {
				
			if(isset($_SERVER['SERVER_ADDR']) && $_SERVER['SERVER_ADDR']!=''){
				$ip_server = $_SERVER['SERVER_ADDR'];
			} else {
				//get url of site.
				$ip_server = urlencode(get_site_url());
			}
			$siteurl = urlencode(get_site_url());
			
			//scrapeurl
			$sitetypelower = strtolower($sitetype);
			
			$tempurlval = 'https://crawl.ljapps.com/crawlrevs?rip='.$ip_server.'&surl='.$siteurl.'&scrapeurl='.$listedurl.'&stype='.$sitetypelower.'&sfp=pro&nobot=1&nhful='.$nhful.'&locationtype=&scrapequery=&tempbusinessname=&pagenum='.$pagenum.'&nextpageurl='.$nextpageurl;
			
			//echo $tempurlval;
			//die();
			
			if(strpos($listedurl, '/lender-profile/') !== false){
				//for lender profile
				$results['ack'] ='error';
				$results['ackmsg'] ='Sorry, this does not currently work for a lender profile url. You can download Lender Reviews with the Review Funnels tab above.';
				$results = json_encode($results);
				echo $results;
				die();
			}
			
			$serverresponse='';
			
			$args = array(
				'timeout'     => 120,
				'sslverify' => false
			); 
			$response = wp_remote_get( $tempurlval, $args );
			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				$headers = $response['headers']; // array of http header lines
				$serverresponse    = $response['body']; // use the content
			} else {
				//must have been an error
				$results['ack'] ='error';
				$results['ackmsg'] ='Error 0001a: trouble contacting crawling server with remote_get. Please try again or contact support.'.$response->get_error_message();
				$results = json_encode($results);
				echo $results;
				die();
			}
				
			$serverresponsearray = json_decode($serverresponse, true);
			
			//print_r($serverresponsearray );
		//	die();

			if($serverresponse=='' || !is_array($serverresponsearray)){
				$results['ack'] ='error';
				$results['ackmsg'] ='Error 0001: trouble contacting crawling server. Please try again or contact support.';
				$results = json_encode($results);
				echo $results;
				die();
			}
			//catch limit error
			if($serverresponsearray['ack']=='error'){
				$results['ack'] ='error';
				$results['ackmsg'] ='Error 0002: '.$serverresponsearray['ackmessage'];
				$results = json_encode($results);
				echo $results;
				die();
			}
			if(!isset($serverresponsearray['result']) || !is_array($serverresponsearray['result'])){
				$results['ack'] ='error';
				$results['ackmsg'] ='Error 0002b: trouble finding reviews. Contact support with this error code and the search terms or place id you are using.';
				$results = json_encode($results);
				echo $results;
				die();
			}
			//catch error
			if($serverresponsearray['result']['ack']=='error'){
				$results['ack'] ='error';
				$results['ackmsg'] ='Error 0003: '.$serverresponsearray['ackmessage'].' : '.$serverresponsearray['result']['ackmsg'];
				$results = json_encode($results);
				echo $results;
				die();
			}
			//made it this far assume we have reviews.
			$crawlerresultarray = $serverresponsearray['result'];
			

			//need totals and avg for this place $getreviewsarray['total']
			$result['total']='';
			$result['avg']='';
			if(isset($crawlerresultarray['avg'])){
				$result['avg']=$crawlerresultarray['avg'];
			}
			if(isset($crawlerresultarray['total'])){
				$result['total']=$crawlerresultarray['total'];
			}
			
			//pass back URL used
			if(isset($crawlerresultarray['callurl'])){
				$result['callurl']=$crawlerresultarray['callurl'];
			}
			//pass back next URL used
			if(isset($crawlerresultarray['nextpageurl'])){
				$result['nextpageurl']=$crawlerresultarray['nextpageurl'];
			}
			
			$x=0;
			if(isset($crawlerresultarray['reviews'])){
			$crawlerreviewsarray = $crawlerresultarray['reviews'];
			}
			
			foreach ($crawlerreviewsarray as $review) {
				
				$tempownerres='';
				if(isset($review['owner_response']) && $review['owner_response']!=''){
					$tempownerres = $review['owner_response'];
				}
				$templocation ='';
				if(isset($review['location']) && $review['location']!=''){
					$templocation = $review['location'];
				}	
				$tempmediaurlsarrayjson ='';
				if(isset($review['mediaurlsarrayjson']) && $review['mediaurlsarrayjson']!=''){
					$tempmediaurlsarrayjson = $review['mediaurlsarrayjson'];
				}					
				
				$reviewsarray[] = [
				 'reviewer_name' => $review['reviewer_name'],
				 'reviewer_id' => $review['reviewer_id'],
				 'reviewer_email' => '',
				 'userpic' => '',
				 'rating' => $review['rating'],
				 'updated' => $review['date'],
				 'review_text' => $review['review_text'],
				 'review_title' => '',
				 'from_url_review' => '',
				 'language_code' => '',
				 'unique_id' => $review['unique_id'],
				 'location' => $templocation,
				 'recommendation_type' => '',
				 'company_title' =>  '',
				 'company_url' => '',
				 'company_name' => '',
				 'mediaurlsarrayjson' => $tempmediaurlsarrayjson,
				 'owner_response' => $tempownerres,
				 'meta_data' => $review['meta_data'],
				 ];
				
				$x++;
			}
			
			//if we find less than 10 then do not loop again.$sitetype
			if(count($reviewsarray)<10 || $sitetypelower=="realtor"){
				$result['stoploop']='stop';
			}
			//just crawling once for zillow
			if($sitetypelower=="zillow"){
				$result['stoploop']='stop';
			}

		$result['reviews'] = $reviewsarray;

		}
		return $result;

	}
	
	
		//for calling remote get and returning array of reviews to insert
	public function wprp_getapps_getrevs_page_zillow($type,$listedurl,$pagenum,$perpage,$savedpageid,$nhful,$fid,$blockstoinsert){
		$result['ack']='success';
		$errormsg='';
		$reviewsarray = Array();
		
		$businessId = $listedurl;
		$sindex = 0;
		//$apikey = get_option('wprevpro_birdeyeapikey_val');
		$result['avg']='';
		$result['total']='';
			

		//https://www.zillow.com/reviews/write/?s=X1-ZUvu3i2bzw4m4p_46au8
		//https://www.zillow.com/profile-page/api/public/v1/reviews?encodedZuid=X1-ZUvu3i2bzw4m4p_46au8&profileTypeIds=1%2C2%2C12%2C16&page=1&size=5&sortType=2&sortOrder=2


		$getreviews = false;
		$errormsg='';
		$callurl = $listedurl;
		$reviewsarraytemp = Array();
		$reviewsarrayfinal = Array();
		if (filter_var($callurl, FILTER_VALIDATE_URL)) {
			$stripvariableurl = strtok($callurl, '?');
			$stripvariableurl = stripslashes($stripvariableurl);
			//check url to find out what kind of review page this is
			if (strpos($stripvariableurl, '/write/') !== false) {
				
				//find the id if this is the first page
				if($pagenum==1){
					$url_components = parse_url($callurl);
					parse_str($url_components['query'], $urldetails);
					
					//print_r($urldetails);
					$id = $urldetails['s'];
					update_option( 'wprevpro_zillowid', $id, false );
				} else {
					$id = get_option('wprevpro_zillowid');
				}
				//$rurl ="https://www.zillow.com/ajax/review/ReviewDisplayJSONGetPage.htm?id=".$id."&size=50&page=".$pagenum."&page_type=received&moderator_actions=0&reviewee_actions=0&reviewer_actions=0&proximal_buttons=1&hasImpersonationPermission=0&service=&sort=1";
				
				
				$rurl ="https://www.zillow.com/profile-page/api/public/v1/reviews?encodedZuid=".$id."&profileTypeIds=1%2C2%2C12%2C16&page=".$pagenum."&size=5&sortType=2&sortOrder=2";
				
				//echo $rurl;
				//die();
				
				$urlvalue = esc_url_raw($rurl);
				
				//if this is a realtor page
				if(isset($id) && $id!=''){
					$getreviews = true;
				} else {
					$errormsg = $errormsg . __(' Unable to find the Zillow reviews URL. Contact support or try using a Review Funnel.','wp-review-slider-pro');
					$this->errormsg = $errormsg;
				}
			
			$result['callurl'] =$urlvalue;
							
			if($getreviews){
				
				//now actually get the reviews
				
				$data = wp_remote_get( $urlvalue );
				if ( is_wp_error( $data ) ) 
				{
					$response['error_message'] 	= $data->get_error_message();
					$reponse['status'] 		= $data->get_error_code();
					print_r($response);
					die();
				}
				//print_r($data);
				//die();
				
				if ( is_array( $data ) ) {
				  $header = $data['headers']; // array of http header lines
				  $body = $data['body']; // use the content
				}
					
				$pagedata = json_decode( $body, true );
				if(!is_array($pagedata) || count($pagedata)<1){
					$fileurlcontents =$this->file_get_contents_curl_browser($urlvalue,'');
					//echo $fileurlcontents;
					$html = wppro_str_get_html($fileurlcontents);
					$pagedata = json_decode( $html, true );
				}
				
				//print_r($pagedata);
				//die();
				
				if(isset($pagedata['filters'][0]['count'])){
						$result['total']=$pagedata['filters'][0]['count'];
				}
				
				if(!isset($pagedata['reviews'])){
					$result['ack'] = __(' Unable to find reviews. Please check the URL. You can also try switching the Crawl Server to Remote.','wp-review-slider-pro');
					return $result;
					die();
				}
				
				// Find reviews
				$reviewsarray = $pagedata['reviews'];
				


				foreach ($reviewsarray as $review) {
						$user_name='';
						$userimage='';
						$rating='';
						$datesubmitted='';
						$rtext='';

						$from_url_review='';
						$company_title='';
						$unique_id='';
						$reviewer_id='';
						$title='';
						$reviewer_email='';
						$userpic='';
						$from_url_review='';
						$from_url='';
						$location='';
						
						// Find user_name
						if($review['reviewer']['screenName']){
							$user_name = $review['reviewer']['screenName'];
						}
						
						// Find userimage ui_avatar
						$userimage = '';

						// find rating
						if($review['rating']){
							$rating = intval($review['rating']);
						}

						// find date created_at
						if($review['createDate']){
							//11/14/2018
							$datesubmitted = $review['createDate'];
						}
						
						// find text
						if($review['reviewComment']){
							$rtext = $review['reviewComment'];
						}
						
						// find reviewer_id
						if($review['reviewer']['encodedZuid']){
							$unique_id = $review['reviewer']['encodedZuid'];
						}
						
						// find text
						if($review['reviewId']){
							$unique_id = $review['reviewId'];
						}
						
						$meta_json ="";
						$meta_data = Array();
						if($review['subRatings']){
							$meta_data['subRatings'] = json_encode($review['subRatings']);
						}
						if($review['workDescription']){
							$meta_data['workDescription'] = $review['workDescription'];
						}
						if(count($meta_data)>0){
							$meta_json = json_encode($meta_data);
						}
					
						if($rating>0){
							$reviewsarraytemp[] = [
									'reviewer_name' => trim($user_name),
									'rating' => $rating,
									'date' => $datesubmitted,
									'review_text' => trim($rtext),
									'reviewer_id' => trim($reviewer_id),
									'unique_id' => trim($unique_id),
									'meta_data' => $meta_json,
									'type' => 'Zillow'
							];
						}
				}
			}
			//print_r($reviewsarraytemp);
			//die();
			//loop reviews and build new array of just what we need
			
			foreach ($reviewsarraytemp as $item) {
				 $reviewsarrayfinal[] = [
				 'reviewer_name' => trim($item['reviewer_name']),
				 'reviewer_id' => $item['reviewer_id'],
				 'reviewer_email' => '',
				 'userpic' => '',
				 'rating' => $item['rating'],
				 'updated' => $item['date'],
				 'review_text' => $item['review_text'],
				 'review_title' => '',
				 'from_url_review' => '',
				 'language_code' => '',
				 'unique_id' => $item['unique_id'],
				 'location' => '',
				 'recommendation_type' => '',
				 'company_title' =>  '',
				 'company_url' => '',
				 'company_name' => '',
				 'meta_data' => $item['meta_data'],
				 ];
			}

			//print_r($reviewsarrayfinal);
			//die();
			
			$result['reviews'] = $reviewsarrayfinal;
			} else if(strpos($stripvariableurl, '/lender-profile/') !== false){

				//for lender profile
				//$stripvariableurl
				$errormsg = $errormsg . __(' Sorry, this does not currently work for a lender profile url. You can download Lender Reviews with the Review Funnels tab above.','wp-review-slider-pro');
				$this->errormsg = $errormsg;
					
					
			}
		} else {
			$errormsg='Please enter a valid URL.';
		}
		
		$result['ack'] =$errormsg;
		if(count($reviewsarrayfinal)<5){
			//no need to loop again.
			$result['stoploop'] = "stop";
		}
		

		return $result;
	}
	
	//for calling remote get and returning array of reviews to insert
	public function wprp_getapps_getrevs_page_yotpo($type,$listedurl,$pagenum,$perpage,$savedpageid,$nhful,$fid,$blockstoinsert){
		$result['ack']='success';
		$errormsg='';
		$reviewsarray = Array();
		
		$client_id = $listedurl;
		$sindex = 0;
		$clientsecret = get_option('wprevpro_yotposecretkey_val');
		$usertoken = get_option('wprevpro_yotpousertoken');
		$result['avg']='';
		$result['total']='';
			
		if($client_id=='' || $clientsecret==''){
			//must have been an error
			$results['ack'] ='error';
			$results['ackmsg'] ='Error 0001: Make sure to enter your API Key and Business ID from Birdeye.';
			$results = json_encode($results);
			echo $results;
			die();
		}

		if($pagenum >1){
			$sindex = $pagenum*100;
		}
		
		//first make call to get user token if on page 1 or if not set yet.
		if($pagenum<2 || $usertoken==''){
			//https://api.yotpo.com/oauth/token?grant_type=client_credentials&client_id=2396MKzUZbAPHI0daFTEmeMfIa8hpLgu7e899k4l&client_secret=3iD4fxhnXZwhgIjztEyVxniBOM25pSQw36AIHfYE
			
			$callurl = "https://api.yotpo.com/oauth/token?grant_type=client_credentials&client_id=".$client_id."&client_secret=".$clientsecret;
			
			$response = wp_remote_post( $callurl, array(
					'method'      => 'POST',
					'timeout'     => 45,
					'redirection' => 5,
					'httpversion' => '1.0',
					'blocking'    => true,
					'headers'     => array(),
					'body'        => array(),
					'cookies'     => array()
					)
				);
			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				$headers = $response['headers']; // array of http header lines
				$body    = $response['body']; // use the content
			} else {
				$result['ack']=esc_html__('Error: Can not use remote post on this url:', 'wp-review-slider-pro').' '.$callurl;
			}
			$usertokenarray = json_decode($body,true);
			
			if(isset($usertokenarray['access_token'])){
				$usertoken = $usertokenarray['access_token'];
				update_option('wprevpro_yotpousertoken',$usertoken);
			}
			
			//also need to find avg and total here since this is first page.
			$callurl = "https://api.yotpo.com/products/".$client_id."/yotpo_site_reviews/bottomline";
			$response = wp_remote_get( $callurl, array( 'timeout' => 10,
					'headers' => array( 'Content-Type' => 'application/json',
                               'Accept'=> 'application/json' ) 
					));
			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				$headers = $response['headers']; // array of http header lines
				$body    = $response['body']; // use the content
			} else {
				$result['ack']=esc_html__('Error: Can not use remote get on this url:', 'wp-review-slider-pro').' '.$callurl;
			}
			$reviewsummary = json_decode($body,true);

			if($reviewsummary['response']['bottomline']){
				$result['avg']=$reviewsummary['response']['bottomline']['average_score'];
				$result['total']=$reviewsummary['response']['bottomline']['total_reviews'];
			}
			
		}
		if ( $usertoken=='') {
			$results['ack'] ='error';
			$results['ackmsg'] ='Error 0001: Can not find user token. ';
			$results = json_encode($results);
			echo $results;
			die();
		}
		
		$endpoint = "https://api.yotpo.com/v1/apps/".$client_id."/reviews?utoken=".$usertoken."&count=50&page=".$pagenum;

		$args = array(
					'timeout'     => 50,
					'sslverify' => false
				); 
		$response = wp_remote_get( $endpoint, $args );
				
		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			$results['ack'] ='error';
			$results['ackmsg'] ='Error 0002: Something went wrong. '.$error_message;
			$results = json_encode($results);
			echo $results;
			die();
		}
		
		$fileurlcontents = $response['body'];	
			
		$reviewcontainerdiv = json_decode($fileurlcontents,true);
		
		foreach ($reviewcontainerdiv['reviews'] as $review) {
				$user_name='';
				$userimage='';
				$rating='';
				$datesubmitted='';
				$rtext='';
				$from_url_review='';
				$company_title='';
				$unique_id='';
				$reviewer_id='';
				$title='';
				$reviewer_email='';
				$userpic='';
				$from_url_review='';
				$from_url='';
				$location='';
			
			// Find unique id
			if($review['id']){
				$unique_id=$review['id'];
			}
			
			// Find user_name
			if($review['name']){
				$user_name=$review['name'];
			}

			if($review['email']){
				$reviewer_email=$review['email'];
			}

			//find rating
			if($review['score']){
				$rating=$review['score'];
			}
			
			//find date created_at
			if($review['created_at']){
				$datesubmitted=$review['created_at'];
			}
			
			//find title
			if($review['title']){
				$title=$review['title'];
			}
			
			//find text
			if($review['content']){
				$rtext=$review['content'];
			}
			
			$meta_json ="";
			$meta_data = Array();
			if($review['votes_up']){
				$meta_data['votes_up'] = json_encode($review['votes_up']);
			}
			if($review['votes_down']){
				$meta_data['votes_down'] = json_encode($review['votes_down']);
			}
			if($review['sentiment']){
				$meta_data['sentiment'] = json_encode($review['sentiment']);
			}
			if($review['sku']){
				$meta_data['sku'] = json_encode($review['sku']);
			}
			if($review['reviewer_type']){
				$meta_data['reviewer_type'] = json_encode($review['reviewer_type']);
			}
			if(count($meta_data)>0){
				$meta_json = json_encode($meta_data);
			}


			if($rating>0){
				$reviewsarray[] = [
					 'reviewer_name' => trim($user_name),
					 'reviewer_id' => trim($reviewer_id),
					 'reviewer_email' => $reviewer_email,
					 'userpic' => $userpic,
					 'rating' => $rating,
					 'updated' => $datesubmitted,
					 'review_text' => trim($rtext),
					 'review_title' => trim($title),
					 'from_url' => $from_url,
					 'from_url_review' => $from_url_review,
					 'language_code' => '',
					 'unique_id' => $unique_id,
					 'location' => $location,
					 'recommendation_type' => '',
					 'company_title' =>  '',
					 'company_url' => '',
					 'company_name' => '',
					 'mediaurlsarrayjson' => '',
					 'owner_response' => '',
					 'meta_data' => $meta_json,
					 ];
			}
		}

		$result['reviews'] = $reviewsarray;
				
		//die();

		return $result;
	}
		//for calling remote get and returning array of reviews to insert
	public function wprp_getapps_getrevs_page_angi($type,$listedurl,$pagenum,$perpage,$savedpageid,$nhful,$fid,$blockstoinsert){
			
				$result['ack']='success';
		
			$errormsg='';
			$reviewsarraytemp = Array();
			$nhful='new';
			$reviewsarray= Array();
			$nextpageurl='';
			
			if (filter_var($listedurl, FILTER_VALIDATE_URL)) {
					
				if(isset($_SERVER['SERVER_ADDR']) && $_SERVER['SERVER_ADDR']!=''){
					$ip_server = $_SERVER['SERVER_ADDR'];
				} else {
					//get url of site.
					$ip_server = urlencode(get_site_url());
				}
				$siteurl = urlencode(get_site_url());
				
				//scrapeurl
				$tempurlval = 'https://crawl.ljapps.com/crawlrevs?rip='.$ip_server.'&surl='.$siteurl.'&scrapeurl='.$listedurl.'&stype=angi&sfp=pro&nobot=1&nhful='.$nhful.'&locationtype=&scrapequery=&tempbusinessname=&pagenum='.$pagenum.'&nextpageurl='.$nextpageurl;
				
				//echo $tempurlval;
				//die();
				
				$serverresponse='';
				
				$args = array(
					'timeout'     => 50,
					'sslverify' => false
				); 
				$response = wp_remote_get( $tempurlval, $args );
				if ( is_array( $response ) && ! is_wp_error( $response ) ) {
					$headers = $response['headers']; // array of http header lines
					$serverresponse    = $response['body']; // use the content
				} else {
					//must have been an error
					$results['ack'] ='error';
					$results['ackmsg'] ='Error 0001a: trouble contacting crawling server with remote_get. Please try again or contact support.'.$response->get_error_message();
					$results = json_encode($results);
					echo $results;
					die();
				}
					
				$serverresponsearray = json_decode($serverresponse, true);
				
				//print_r($serverresponsearray);

				if($serverresponse=='' || !is_array($serverresponsearray)){
					$results['ack'] ='error';
					$results['ackmsg'] ='Error 0001: trouble contacting crawling server. Please try again or contact support.';
					$results = json_encode($results);
					echo $results;
					die();
				}
				//catch limit error
				if($serverresponsearray['ack']=='error'){
					$results['ack'] ='error';
					$results['ackmsg'] ='Error 0002: '.$serverresponsearray['ackmessage'];
					$results = json_encode($results);
					echo $results;
					die();
				}
				if(!isset($serverresponsearray['result']) || !is_array($serverresponsearray['result'])){
					$results['ack'] ='error';
					$results['ackmsg'] ='Error 0002b: trouble finding reviews. Contact support with this error code and the search terms or place id you are using.';
					$results = json_encode($results);
					echo $results;
					die();
				}
				//catch error
				if($serverresponsearray['result']['ack']=='error'){
					$results['ack'] ='error';
					$results['ackmsg'] ='Error 0003: '.$serverresponsearray['ackmessage'].' : '.$serverresponsearray['result']['ackmsg'];
					$results = json_encode($results);
					echo $results;
					die();
				}
				//made it this far assume we have reviews.
				$crawlerresultarray = $serverresponsearray['result'];
				

				//need totals and avg for this place $getreviewsarray['total']
				$result['total']='';
				$result['avg']='';
				if(isset($crawlerresultarray['avg'])){
					$result['avg']=floatval($crawlerresultarray['avg']);
				}
				if(isset($crawlerresultarray['total'])){
					$result['total']=$crawlerresultarray['total'];
				}
				
				//pass back URL used
				if(isset($crawlerresultarray['callurl'])){
					$result['callurl']=$crawlerresultarray['callurl'];
				}
				//pass back next URL used
				if(isset($crawlerresultarray['nextpageurl'])){
					$result['nextpageurl']=$crawlerresultarray['nextpageurl'];
				}
				
				$x=0;
				$crawlerreviewsarray = $crawlerresultarray['reviews'];
				$numreturned = count($crawlerreviewsarray);	
				
				foreach ($crawlerreviewsarray as $review) {
					
					$tempownerres='';
					if(isset($review['owner_response']) && $review['owner_response']!=''){
						$tempownerres = $review['owner_response'];
					}
					$templocation ='';
					if(isset($review['location']) && $review['location']!=''){
						$templocation = $review['location'];
					}	
					$tempmediaurlsarrayjson ='';
					if(isset($review['mediaurlsarrayjson']) && $review['mediaurlsarrayjson']!=''){
						$tempmediaurlsarrayjson = $review['mediaurlsarrayjson'];
					}					
					
					$reviewsarray[] = [
					 'reviewer_name' => $review['user_name'],
					 'reviewer_id' => '',
					 'reviewer_email' => '',
					 'userpic' => $review['userimage'],
					 'rating' => $review['rating'],
					 'updated' => $review['datesubmitted'],
					 'review_text' => $review['rtext'],
					 'review_title' => '',
					 'from_url' => $listedurl,
					 'from_url_review' => $review['from_url_review'],
					 'language_code' => '',
					 'location' => $templocation,
					 'recommendation_type' => '',
					 'company_title' =>  '',
					 'company_url' => '',
					 'company_name' => '',
					 'mediaurlsarrayjson' => $tempmediaurlsarrayjson,
					 'owner_response' => $tempownerres,
					 ];
					
					$x++;
				}
				
				//if we find less than 10 then do not loop again.
					if(count($reviewsarray)<10){
						$result['stoploop']='stop';
					}

			$result['reviews'] = $reviewsarray;

			}
			
			//print_r($result);
			
		return $result;
				
	}
	
		//for calling remote get and returning array of reviews to insert
	public function wprp_getapps_getrevs_page_fresha($type,$listedurl,$pagenum,$perpage,$savedpageid,$nhful,$fid,$blockstoinsert,$nextpageurl){
		$result['ack']='success';
		$errormsg='';
		$reviewsarray = Array();

		$result['avg']='';
		$result['total']='';
			
		if($listedurl=='' || filter_var($listedurl, FILTER_VALIDATE_URL) === FALSE){
			//must have been an error
			$results['ack'] ='error';
			$results['ackmsg'] ='Error 0001: Make sure to enter the correct Fresha page URL.';
			$results = json_encode($results);
			echo $results;
			die();
		}

		
		//get the total and avg first
		if($pagenum <2){
			$response = wp_remote_get( $listedurl, array( 'timeout' => 30,
					'headers' => array( 'Content-Type' => 'application/json',
                               'Accept'=> 'application/json' ) 
					));
			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				$headers = $response['headers']; // array of http header lines
				$body    = $response['body']; // use the content
			} else {
				$result['ack']=esc_html__('Error: Can not use remote get on this url:', 'wp-review-slider-pro').' '.$callurl;
			}
			
			$avg = $this->get_string_between($body, ',"ratingValue":', ',');
			$total = $this->get_string_between($body, '"reviewCount":', ',');
			$avg = (float)$avg;
			if(intval($total)>0){
				$result['total']=$total;
			}
			if($avg>0){
				$result['avg']=$avg;
			}
		}
		
		//print_r($result);
		//die();
		
		//grab the slug from the listedurl
		//https://www.fresha.com/a/morgan-company-hair-beauty-nottingham-unit-2-riverbank-business-park-uk-w3xcsptr
		$slug = $this->get_string_between($listedurl, 'fresha.com/a/', '#');
			
		$endpoint = 'https://b2c-api-gateway.fresha.com/graphql';
		$nextid = "";
		if($nextpageurl!=''){
			$nextid = $nextpageurl;
		}

		$body = '{"query":"\n query locationReviewsModal($slug: String!, $reviews: Int!, $id: ID, $ratings: [RatingValue!], $sortingType: ReviewSortingType) {\n  location(slug: $slug) {\n reviews(\n first: $reviews\n after: $id\n ratings: $ratings\n sortingType: $sortingType\n ) {\n pageInfo {\n hasNextPage\n }\n edges {\n cursor\n node {\n author {\n avatar {\n url(class: THUMB)\n }\n name\n }\n date {\n longDate\n }\n id\n rating\n text\n reply {\n isOnlyVisibleToAuthor\n text\n date {\n longDate\n }\n author {\n avatar {\n url(class: THUMB)\n }\n name\n }\n }\n }\n }\n totalCount\n }\n  }\n}\n",
				   "variables":{
					  "id":"'.$nextid.'",
					  "reviews":5,
					  "ratings":[],
					  "slug":"'.$slug.'",
					  "sortingType":"LATEST"
				   },
				   "operationName":"locationReviewsModal",
				   "extensions":{
					  "platform":"web",
					  "version":"2.8.829"
				   }
				}';

		$options = [
			'body'        => $body,
			'headers'     => [
				'Content-Type' => 'application/json',
			],
			'timeout'     => 60,
			'redirection' => 5,
			'blocking'    => true,
			'httpversion' => '1.0',
			'sslverify'   => false,
			'data_format' => 'body',
		];

		$response = wp_remote_post( $endpoint, $options );
		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			$results['ack'] ='error';
			$results['ackmsg'] ='Error 0002: Something went wrong. '.$error_message;
			$results = json_encode($results);
			echo $results;
			die();
		}
		
		$fileurlcontents = $response['body'];	
			
		$reviewjsonarray = json_decode($fileurlcontents,true);
		
		//print_r($reviewjsonarray);
		
		if(is_array($reviewjsonarray) && isset($reviewjsonarray['data']['location']['reviews']['edges'])){
			$reviewcontainerarray = $reviewjsonarray['data']['location']['reviews']['edges'];
		} else {
			$results['ack'] ='error';
			$results['ackmsg'] ='Error 0003: Unable to find reviews. ';
			$results = json_encode($results);
			echo $results;
			die();
		}
		

		foreach ($reviewcontainerarray as $review) {
				$user_name='';
				$userimage='';
				$rating='';
				$datesubmitted='';
				$rtext='';
				$from_url_review='';
				$company_title='';
				$unique_id='';
				$reviewer_id='';
				$title='';
				$reviewer_email='';
				$userpic='';
				$from_url_review='';
				$from_url='';
				$location='';
				
			//for finding next page of reviews.
			if($review['cursor'] && $review['cursor']!=''){
			$result['nextpageurl']=$review['cursor'];
			}
			
			// Find unique id
			if($review['node']['id']){
				$unique_id=$review['node']['id'];
			}
			
			// Find user_name
			if($review['node']['author']['name']){
				$user_name=$review['node']['author']['name'];
			}


			if($review['node']['author']['avatar']){
				$userpic=$review['node']['author']['avatar']['url'];
			}

			//find rating
			if($review['node']['rating']){
				$rating=$review['node']['rating'];
			}
			
			//find date created_at
			if($review['node']['date']['longDate']){
				$datesubmitted=$review['node']['date']['longDate'];
				$datesubmitted= substr($datesubmitted, 0, strpos($datesubmitted, ","));
			}
			
			//find text
			if($review['node']['text']){
				$rtext=$review['node']['text'];
			}
			
			//owner_response
			$owner_response_encode ='';
			$owner['id'] = '';
			$owner['name'] = '';
			$owner['comment'] = '';
			$owner['date'] = '';
			//mgrRspnInline
			if(isset($review['node']['reply']) && $review['node']['reply']['text']){
				$replyarray=$review['node']['reply'];
				//must be a response
				$owner['name'] = $replyarray['author']['name'];

				//responseDate
				$ownerdate = $replyarray['date']['longDate'];
				$tempdate = $this->myStrtotime($ownerdate);

				$owner['date'] = date('Y-m-d', $tempdate);
				$owner['comment'] = $replyarray['text'];
			}
			if($owner['comment']!=''){
				$owner_response_encode = json_encode($owner);
			}

			if($rating>0){
				$reviewsarray[] = [
					 'reviewer_name' => trim($user_name),
					 'reviewer_id' => trim($reviewer_id),
					 'reviewer_email' => $reviewer_email,
					 'userpic' => $userpic,
					 'rating' => $rating,
					 'updated' => $datesubmitted,
					 'review_text' => trim($rtext),
					 'review_title' => trim($title),
					 'from_url' => $from_url,
					 'from_url_review' => $from_url_review,
					 'language_code' => '',
					 'unique_id' => $unique_id,
					 'location' => $location,
					 'recommendation_type' => '',
					 'company_title' =>  '',
					 'company_url' => '',
					 'company_name' => '',
					 'mediaurlsarrayjson' => '',
					 'owner_response' => $owner_response_encode,
					 ];
			}
		}

		$result['reviews'] = $reviewsarray;
		
		//print_r($reviewsarray);
				
		//die();

		return $result;
	}
	

	//-----------------------------
	//for calling remote get and returning array of reviews to insert
	public function wprp_getapps_getrevs_page_creativemarket($type,$listedurl,$pagenum,$perpage,$savedpageid,$nhful,$fid,$blockstoinsert,$nextpageurl){
		$result['ack']='success';
		$errormsg='';
		$reviewsarray = Array();

		$result['avg']='';
		$result['total']='';
		
		$apikey = "a-demo-key-with-low-quota-per-ip-address";
			
		if($listedurl=='' || filter_var($listedurl, FILTER_VALIDATE_URL) === FALSE){
			//must have been an error
			$results['ack'] ='error';
			$results['ackmsg'] ='Error 0001: Make sure to enter the correct Creative Market page URL.';
			$results = json_encode($results);
			echo $results;
			die();
		}

		$tempphantomurl = 'https://phantomjscloud.com/api/browser/v2/'.$apikey.'/?request={url:%22'.$listedurl.'%22,renderType:%22html%22,requestSettings:{doneWhen:[{event:%22domReady%22}]}}';

		//get the total and avg first
		if($pagenum <2){
			$response = wp_remote_get( $tempphantomurl, array( 'timeout' => 30,
					'headers' => array( 'Content-Type' => 'application/json',
                               'Accept'=> 'application/json' ) 
					));
			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				$headers = $response['headers']; // array of http header lines
				$body    = $response['body']; // use the content
			} else {
				$result['ack']=esc_html__('Error: Can not use remote get on this url:', 'wp-review-slider-pro').' '.$callurl;
			}
			
			//print_r($response);
			//echo "here";
			
			$avg = $this->get_string_between($body, ',"averageReviewRating":', ',');
			$total = $this->get_string_between($body, ',"numReviewRatings":', ',');
			$avg = (float)$avg;
			if(intval($total)>0){
				$result['total']=$total;
			}
			if($avg>0){
				$result['avg']=$avg;
			}
		}
		
		
		//grab the id from the listedurl and then call the api url to get json of reviews.
		//https://creativemarket.com/mila.garret/6817168-Harlow-Serif-Script-Modern-Font-Duo
		$start = "/";
		$end = "-";
		$string = " ".$listedurl;
		$ini = strrpos($string,$start);
		//if ($ini == 0) return "";
		$ini += strlen($start);
		$len = strpos($string,$end,$ini) - $ini;
		$prodid = substr($string,$ini,$len);

		
		$newurl = 'https://creativemarket.com/product/'.$prodid.'/reviews/10/'.$pagenum.'?sort=date&sortBy=desc&contextID='.$prodid.'&limitPage=10&page='.$pagenum.'&sorting=%7B%22id%22:1,%22text%22:%22Most+Recent%22,%22key%22:%22date%22,%22type%22:%22desc%22%7D';
		
		$newurl = urlencode($newurl);
		
		$newurlphantom = 'https://phantomjscloud.com/api/browser/v2/'.$apikey.'/?request={url:%22'.$newurl.'%22,renderType:%22html%22,requestSettings:{doneWhen:[{event:%22domReady%22}]}}';
		
		//echo $newurlphantom;
		//die();
		sleep(3);
		
		$response = wp_remote_get( $newurlphantom, array( 'timeout' => 30,
					'headers' => array( 'Content-Type' => 'application/json',
                               'Accept'=> 'application/json' ) 
					));
		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			$results['ack'] ='error';
			$results['ackmsg'] ='Error 0002: Something went wrong. '.$error_message;
			$results = json_encode($results);
			echo $results;
			die();
		}
		
		$fileurlcontents = trim($response['body']);
		
		$fileurlcontents = $this->get_string_between($fileurlcontents, '<pre style="word-wrap: break-word; white-space: pre-wrap;">', '</pre>');
		

		$reviewjsonarray = json_decode($fileurlcontents,true);

//print_r($reviewjsonarray);
//die();
		
		if(is_array($reviewjsonarray) && isset($reviewjsonarray['reviews'])){
			$reviewcontainerarray = $reviewjsonarray['reviews'];
		} else {
			$results['ack'] ='error';
			$results['ackmsg'] ='Error 0003: Unable to find reviews. ';
			$results = json_encode($results);
			echo $results;
			die();
		}
		

		foreach ($reviewcontainerarray as $review) {
				$user_name='';
				$userimage='';
				$rating='';
				$datesubmitted='';
				$rtext='';
				$from_url_review='';
				$company_title='';
				$unique_id='';
				$reviewer_id='';
				$title='';
				$reviewer_email='';
				$userpic='';
				$from_url_review='';
				$from_url='';
				$location='';

			
			// Find unique id
			if($review['id']){
				$unique_id=$review['id'];
			}
			
			// Find user_name
			if($review['user']['fullName']){
				$user_name=$review['user']['fullName'];
			}


			if($review['user']['avatar']['l']){
				$userpic=$review['user']['avatar']['l'];
			}

			//find rating
			if($review['rating']){
				$rating=$review['rating'];
			}
			
			//find date created_at title=\"
			if($review['timeSince']){
				$temptime = $this->get_string_between($review['timeSince'], '&gt;', '&lt;');
				$datesubmitted=$temptime;
			}

			
			//find text
			if($review['reviewText']){
				$rtext=$review['reviewText'];
			}
			
			//owner_response
			$owner_response_encode ='';
			$owner['id'] = '';
			$owner['name'] = '';
			$owner['comment'] = '';
			$owner['date'] = '';
			//mgrRspnInline
			if(isset($review['reply']) && $review['reply']['replyText']){
				$replyarray=$review['reply'];
				//must be a response
				$owner['name'] = "Owner Response";

				//responseDate
				$ownerdate = $this->get_string_between($review['timeSince'], '&gt;', '&lt;');
				$tempdate = $this->myStrtotime($ownerdate);

				$owner['date'] = date('Y-m-d', $tempdate);
				$owner['comment'] = $replyarray['replyText'];
			}
			if($owner['comment']!=''){
				$owner_response_encode = json_encode($owner);
			}

			if($rating>0){
				$reviewsarray[] = [
					 'reviewer_name' => trim($user_name),
					 'reviewer_id' => trim($reviewer_id),
					 'reviewer_email' => $reviewer_email,
					 'userpic' => $userpic,
					 'rating' => $rating,
					 'updated' => $datesubmitted,
					 'review_text' => trim($rtext),
					 'review_title' => trim($title),
					 'from_url' => $from_url,
					 'from_url_review' => $from_url_review,
					 'language_code' => '',
					 'unique_id' => $unique_id,
					 'location' => $location,
					 'recommendation_type' => '',
					 'company_title' =>  '',
					 'company_url' => '',
					 'company_name' => '',
					 'mediaurlsarrayjson' => '',
					 'owner_response' => $owner_response_encode,
					 ];
			}
		}

		$result['reviews'] = $reviewsarray;
		
		if(count($reviewcontainerarray)<10){
			//no need to loop again.
			$result['stoploop'] = "stop";
		}
				
		//die();

		return $result;
	}
	
	
	//for calling remote get and returning array of reviews to insert
	public function wprp_getapps_getrevs_page_realtor($type,$listedurl,$pagenum,$perpage,$savedpageid,$nhful,$fid,$blockstoinsert,$nextpageurl){
		$result['ack']='success';
		$errormsg='';
		$reviewsarray = Array();

		$result['avg']='';
		$result['total']='';

				//echo $callurl;
				$result['listedurl'] =$listedurl;
				$response = wp_remote_get( $listedurl );
				if ( is_array( $response ) && ! is_wp_error( $response ) ) {
					$headers = $response['headers']; // array of http header lines
					$body    = $response['body']; // use the content
				} else {
					$result['ack']=esc_html__('Error: Can not use remote get on this url:', 'wp-review-slider-pro').' '.$listedurl;
				}
				
				$fileurlcontents = $response['body'];

				//echo $fileurlcontents;
				//die();
				
				//get the review schema from html string
				$schemastring = $this->get_string_between($fileurlcontents, '<script strategy="afterInteractive" type="application/ld+json">', '</script>');
				
				$pagedata = json_decode( $schemastring, true );
				//print_r($pagedata);
				//die();

				$result['total']=$pagedata[0]['aggregateRating']['reviewCount'];
				$result['avg']=$pagedata[0]['aggregateRating']['ratingValue'];
				
				$reviewdata = "[".$this->get_string_between($fileurlcontents, '"feedbackData":[', ']')."]";
				$reviewdataarray = json_decode( $reviewdata, true );
				
				$recommendationdata = "[".$this->get_string_between($fileurlcontents, '"recommendationData":[', ']')."]";

				$recommendationdataarray = json_decode( $recommendationdata, true );

				$allrevsarray = array_merge($reviewdataarray,$recommendationdataarray);
				
				//loop reviews and build new array of just what we need
				foreach ($allrevsarray as $review) {
					
					//print_r($review);
					//die();
					$user_name='';
					$userimage='';
					$rating='';
					$datesubmitted='';
					$rtext='';
					$from_url_review='';
					$location='';
					$meta_json ="";
					$meta_data = Array();

					if(isset($review['display_name'])){
						$user_name=$review['display_name'];
					}
					if(isset($review['comment'])){
						$rtext=$review['comment'];
					}
					if(isset($review['rating'])){
						$rating=$review['rating'];
						$meta_data['rtype']="review";
					}
					if($rating==''){
						//must be recommendation just give it a 5 star
						$rating=5;
						$meta_data['rtype']="recommendation";
					}
					
					if(isset($review['location'])){
						$location=$review['location'];
					}
					if($review['started_timestamp']){
						$datesubmitted=$review['started_timestamp'];
					}
						
					
					if(isset($review['transaction_date'])){
						$meta_data['transaction_date'] = $review['transaction_date'];
					}
					if(isset($review['trixel_url'])){
						$meta_data['trixel_url'] = $review['trixel_url'];
					}
					if(isset($review['describe_yourself'])){
						$meta_data['describe_yourself'] = $review['describe_yourself'];
					}
					if(isset($review['responsiveness'])){
						$meta_data['responsiveness'] = $review['responsiveness'];
					}
					if(isset($review['negotiation_skills'])){
						$meta_data['negotiation_skills'] = $review['negotiation_skills'];
					}
					if(isset($review['professionalism_communication'])){
						$meta_data['professionalism_communication'] = $review['professionalism_communication'];
					}
					if(isset($review['market_expertise'])){
						$meta_data['market_expertise'] = $review['market_expertise'];
					}

					if(isset($review['link'])){
						$meta_data['link'] = $review['link'];
					}
					if(count($meta_data)>0){
						$meta_json = json_encode($meta_data);
					}
					
					$owner_response_encode ='';
					$owner['id'] = '';
					$owner['name'] = '';
					$owner['comment'] = '';
					$owner['date'] = '';
					$reviewer_email = '';
					if(isset($review['reply']) and is_array($review['reply'])){
						$owner['comment'] = $review['reply']['message'];
						$owner['date'] = $review['reply']['reply_date'];
						$owner['name'] = $review['reply']['personName'];
						$reviewer_email = $review['reply']['reviewer_email'];
					}
					if($owner['comment']!=''){
						$owner_response_encode = json_encode($owner);
					}
					
						
					 $reviewsarrayfinal[] = [
						 'reviewer_name' => $user_name,
						 'reviewer_id' => '',
						 'reviewer_email' => $reviewer_email,
						 'userpic' => '',
						 'rating' => $rating,
						 'updated' => $datesubmitted,
						 'review_text' => $rtext,
						 'review_title' => '',
						 'from_url' => $listedurl,
						 'from_url_review' => '',
						 'language_code' =>'',
						 'location' => $location,
						 'recommendation_type' => '',
						 'company_title' =>  '',
						 'company_url' => '',
						 'company_name' => '',
						 'meta_data' => $meta_json ,
						 'owner_response' => $owner_response_encode,
						 ];
				}

				$result['reviews'] = $reviewsarrayfinal;

		return $result;
	}


	
	
	//==========helper functions================
	
	//for returning api URL for Airbnb
	private function getreviewurl_airbnb($urlvalue, $listing_id, $listtype, $limit=50, $offset=0){
		
		$response = wp_remote_get( $urlvalue );
		if ( is_array( $response ) ) {
		  $header = $response['headers']; // array of http header lines
		  $fileurlcontents = $response['body']; // use the content
		} else {
			echo esc_html__('Error finding reviews. Please contact plugin support.', 'wp-review-slider-pro');
			die();
		}
		
		//going to try to pull the API key
		$dom  = new DOMDocument();
		libxml_use_internal_errors( 1 );
		$dom->loadHTML( $fileurlcontents );

		$xpath = new DOMXpath( $dom );

		$totalrevessearch = $xpath->query('//div[contains(@class,"_vy3ibx")]');
		if($totalrevessearch->item(0) !== null){
		$temptotalreviews = intval($totalrevessearch->item(0)->nodeValue);
		//update the badge total and average here
		$reviewurl['totalreviews'] = $temptotalreviews;
		}
		
		$titleNode = $xpath->query('//title');
		
		if($titleNode->item(0)){
			$temptitle = $titleNode->item(0)->nodeValue;
			$pieces = explode("-", $temptitle);
			$reviewurl['pagetitle']=$pieces[0];
		} else {
			$reviewurl['pagetitle']='';
		}
		
		
		$key = $this->get_string_between($fileurlcontents, '","api_config":{"key":"', '","');
		
		
		if($key==""){
			$items = $xpath->query( '//meta/@content' );
			//$items = $dom->getElementsByTagName("meta");
			$key='';
			$findme='"api_config":{';
			if( $items->length < 1 )
			{
				die( __('Error 1: No key found.', 'wp-review-slider-pro') );
			} else {
				//print_r($items);
				foreach ($items as $item) {
					if(strpos($item->nodeValue,$findme)){
						$nodearray = json_decode( $item->nodeValue, true );
						$key = $nodearray['api_config']['key'];
						$locale = $nodearray['locale'];
						//echo $key;
						//end the loop early
						break;
					}
				}
			}
		}
		
		if($key==""){
			//first shorten the stringtotime
			$findme = '","api_config":';
			$pos = strpos($fileurlcontents, $findme);
			//echo "<br>".$pos;
			$shortstring = substr($fileurlcontents,$pos-20,200);
			//echo "<br>".$shortstring;
			//no key found using dom method, try getting with string method
			$findme = 'api","key":"';
			$pos = strpos($shortstring, $findme);
			//echo "<br>".$pos;
			$tempendstring = substr($shortstring,$pos,100);
			//echo "<br>".$tempendstring;
			$end = strpos($tempendstring, '"},');
			//echo "<br>".$end;
			$key = substr($shortstring,$pos+12,$end-12);
			//echo "<br>".$key;
			//now fine locale
			$findme = '"locale":"';
			$firstpos = strpos($shortstring, $findme);
			//echo "<br>".$firstpos;
			$locale = substr($shortstring,$firstpos+10,2);
			//echo "<br>".$locale;
			//die();
		}
//echo $key;
		//die();
		
		
		if($key==""){
			die( __('Error 2: No key found. This could be a temporary error or an incorrectly input URL. Please check your Airbnb URL.', 'wp-review-slider-pro') );
		}
		//print_r($nodearray);
		//die();
		
		//use the key and the listing id to find review data					
		$rurl = "https://www.airbnb.com/api/v2/reviews?key=".$key."&locale=".$locale."&listing_id=".$listing_id."&role=guest&_format=for_p3&_order=recent";
		
		$reviewurl['url'] = esc_url_raw($rurl);
		
		if($listtype=='experience'){
			//$rurl = "https://www.airbnb.com/api/v2/reviews?key=".$key."&locale=".$locale."&reviewable_id=".$listing_id."&reviewable_type=MtTemplate&role=guest&_format=for_experiences_guest_flow&_order=recent";
			//$rurl = "https://www.airbnb.com/api/v2/reviews?key=".$key."&locale=".$locale."&reviewable_id=".$listing_id."&reviewable_type=MtTemplate&role=guest&_order=recent";
			
			$rurl ='https://www.airbnb.com/api/v3/ExperiencesPdpReviews?operationName=ExperiencesPdpReviews&variables={"request":{"fieldSelector":"for_p3_translation_only","entityId":"ExperienceListing:'.$listing_id.'","offset":"'.$offset.'","limit":'.$limit.',"first":'.$limit.',"showingTranslationButton":false}}&extensions={"persistedQuery":{"version":1,"sha256Hash":"c2e483a512971b1e4a3b324039d1706bd8591ea1589f2c4e93534479fdd7c582"}}';
						
			$reviewurl['url'] = $rurl;
		}
		
		$avg = $this->get_string_between($fileurlcontents, 'Rated ', ' out of 5 from');
		if(strlen($avg)>0 && strlen($avg)<8){
			floatval($avg);
		} else {
			$avg ='';
		}
		
		if($avg==''){
			$avg = $this->get_string_between($fileurlcontents, 'Rated ', ' out of 5 stars');
			if(strlen($avg)>0 && strlen($avg)<8){
				floatval($avg);
			} else {
				$avg ='';
			}
		}
		

		$reviewurl['avg'] = $avg;
		$reviewurl['key'] = $key;
		
		//print_r($reviewurl);
		
		return $reviewurl;
		
	}
	
	//used for getting avg and total from TripAdvisor showuserreview page
	public function tripadvisorgettotalavg($html,$listedurl){
		//find total and average number here
			$avgrating ='';
			$totalreviews = '';
			
			if($html=='' && $listedurl!=''){
				$args = array(
					'timeout'     => 15,
					'sslverify' => false
				); 
				$response = wp_remote_get( $listedurl,$args );
				if ( is_array( $response ) ) {
				  $header = $response['headers']; // array of http header lines
				  $fileurlcontents = $response['body']; // use the content
				} else {
					echo esc_html__('Error 01: Error finding reviews. Please contact plugin support.', 'wp-review-slider-pro');
					die();
				}

				$fileurlcontents = str_replace('=="', '', $fileurlcontents);
					
				$html = wppro_str_get_html($fileurlcontents);
				//echo "listedurl:".$listedurl;
			}
			
			
			//works for hotels
			if($html->find('div.ratingContainer', 0)){
				//echo "<br>avg:".$avgrating;
				//echo "<br>totalreviews:".$totalreviews;
				if($html->find('div.ratingContainer', 0)->find('span.ui_bubble_rating', 0)){
				$avgrating = $html->find('div.ratingContainer', 0)->find('span.ui_bubble_rating', 0)->alt;
				$avgrating = str_replace(" of 5 bubbles","",$avgrating);
				$avgrating = str_replace(" de 5 burbujas","",$avgrating);
				$avgrating = str_replace(",",".",$avgrating);
				$avgrating = (float)$avgrating;
				}
				if($html->find('div.ratingContainer', 0)->find('span.reviewCount', 0)){
				$totalreviews = $html->find('div.ratingContainer', 0)->find('span.reviewCount', 0)->plaintext;
				$totalreviews = str_replace(",","",$totalreviews);
				$totalreviews = intval($totalreviews);
				}
			}
			//works for resturants US
			if($html->find('div.userRating', 0)){
				//echo "<br>avg:".$avgrating;
				//echo "<br>totalreviews:".$totalreviews;
				if($html->find('div.userRating', 0)->find('span.ui_bubble_rating', 0)){
				$avgrating = $html->find('div.userRating', 0)->find('span.ui_bubble_rating', 0)->alt;
				$avgrating = str_replace(" of 5 bubbles","",$avgrating);
				$avgrating = str_replace(" de 5 burbujas","",$avgrating);
				$avgrating = str_replace(",",".",$avgrating);
				$avgrating = (float)$avgrating;
				}

				if($html->find('div.userRating', 0)->find('div.rating', 0)){
				$totalreviews = $html->find('div.userRating', 0)->find('div.rating', 0)->plaintext;
				$totalreviews = str_replace(",","",$totalreviews);
				$totalreviews = intval($totalreviews);
				}

			}
			
			//backup method for hotels
			if($avgrating==''){
					if($html->find('span.hotels-hotel-review-about-with-photos-Reviews__overallRating--vElGA', 0)){
					$avgrating = $html->find('span.hotels-hotel-review-about-with-photos-Reviews__overallRating--vElGA', 0)->plaintext;
					$avgrating = str_replace(",",".",$avgrating);
					$avgrating = preg_replace('/[^0-9.]+/', '', $avgrating);
					//$avgrating = $avgrating/10;
					}
					if($html->find('span.hotels-hotel-review-about-with-photos-Reviews__seeAllReviews--3PpLR', 0)){
					$totalreviews = $html->find('span.hotels-hotel-review-about-with-photos-Reviews__seeAllReviews--3PpLR', 0)->plaintext;
					$totalreviews = str_replace(",","",$totalreviews);
					$totalreviews = intval($totalreviews);
					}
			}
			
			//if not found try backup method, currently used for resturants
			if($avgrating==''){
				if($html->find('div.rating_and_popularity', 0)){
					if($html->find('div.rating_and_popularity', 0)->find('span.ui_bubble_rating', 0)){
					$avgrating = $html->find('div.rating_and_popularity', 0)->find('span.ui_bubble_rating', 0)->alt;
					$avgrating = str_replace(" of 5 bubbles","",$avgrating);
					//fix for comma
					$avgrating = str_replace(",",".",$avgrating);
					$avgrating = (float)$avgrating;
					}
					if($html->find('div.rating_and_popularity', 0)->find('div.rating', 0)){
					$totalreviews = $html->find('div.rating_and_popularity', 0)->find('div.rating', 0)->plaintext;
					$totalreviews = str_replace(",","",$totalreviews);
					$totalreviews = intval($totalreviews);
					}
				}
			}
			//finally one more try for vacationrental
			if($avgrating==''){
				if($html->find('div.ratingSystem', 0)){
					if($html->find('div.ratingSystem', 0)->find('span.ui_bubble_rating', 0)){
					$avgrating = $html->find('div.ratingSystem', 0)->find('span.ui_bubble_rating', 0)->class;
					$avgrating = str_replace(",",".",$avgrating);
					$avgrating = preg_replace('/[^0-9.]+/', '', $avgrating);
					$avgrating = $avgrating/10;
					}
					if($html->find('div.ratingSystem', 0)->find('span.based-on-n-reviews', 0)){
					$totalreviews = $html->find('div.ratingSystem', 0)->find('span.based-on-n-reviews', 0)->plaintext;
					$totalreviews = str_replace(",","",$totalreviews);
					$totalreviews = str_replace("-","",$totalreviews);
					$totalreviews = str_replace("based on ","",$totalreviews);
					$totalreviews = preg_replace('/[^0-9.]+/', '', $totalreviews);
					}
				}
			}
			//finally one more try for attraction
			if($avgrating=='' || $totalreviews==''){
				if($html->find('div.ui_poi_review_rating ', 0)){
					if($html->find('div.ui_poi_review_rating ', 0)->find('span.ui_bubble_rating', 0)){
					$avgrating = $html->find('div.ui_poi_review_rating', 0)->find('span.ui_bubble_rating', 0)->class;
					$avgrating = str_replace(",",".",$avgrating);
					$avgrating = preg_replace('/[^0-9.]+/', '', $avgrating);
					$avgrating = $avgrating/10;
					}
					if($avgrating==''){
						if($html->find('div.ui_poi_review_rating ', 0)->find('span.ui_star_rating', 0)){
						$avgrating = $html->find('div.ui_poi_review_rating', 0)->find('span.ui_star_rating', 0)->class;
						$avgrating = str_replace(",",".",$avgrating);
						$avgrating = preg_replace('/[^0-9.]+/', '', $avgrating);
						$avgrating = $avgrating/10;
						}
					}
					
					if($html->find('div.ui_poi_review_rating', 0)->find('span.reviewCount', 0)){
					$totalreviews = $html->find('div.ui_poi_review_rating', 0)->find('span.reviewCount', 0)->plaintext;
					$totalreviews = str_replace(",","",$totalreviews);
					$totalreviews = str_replace("-","",$totalreviews);
					$totalreviews = str_replace("based on ","",$totalreviews);
					$totalreviews = preg_replace('/[^0-9.]+/', '', $totalreviews);
					}
				}
			}
			//another try for attraction
			if($avgrating=='' || $totalreviews==''){
				if($html->find("div[class=zTTYS8QR]", 0)){
					$avgrating = $html->find("div[class=zTTYS8QR]", 0)->{'aria-label'};
					$avgrating = substr($avgrating, 0, 3);
					$avgrating = preg_replace('/[^0-9.]+/', '', $avgrating);
				}
				if($html->find("span[class=DrjyGw-P _26S7gyB4 _14_buatE _2nPM5Opx]", 0)){
					$totalreviews = $html->find("span[class=DrjyGw-P _26S7gyB4 _14_buatE _2nPM5Opx]", 0)->plaintext;
					$totalreviews = str_replace(",","",$totalreviews);
					$totalreviews = preg_replace('/[^0-9.]+/', '', $totalreviews);
				}
			}
			//another try for attraction
			if($avgrating=='' || $totalreviews==''){
				if($html->find("div[class=zTTYS8QR]", 0)){
					$avgrating = $html->find("div[class=zTTYS8QR]", 0)->{'aria-label'};
					$avgrating = substr($avgrating, 0, 3);
					$avgrating = preg_replace('/[^0-9.]+/', '', $avgrating);
				}
				if($html->find("span[class=_1yuvE2vR]", 0)){
					$totalreviews = $html->find("span[class=_1yuvE2vR]", 0)->plaintext;
					$totalreviews = str_replace(",","",$totalreviews);
					$totalreviews = preg_replace('/[^0-9.]+/', '', $totalreviews);
				}
			}
			//another try for attraction
			if($avgrating=='' || $totalreviews==''){
				if($html->find("div[class=WlYyy cPsXC fksET cMKSg]", 0)){
					$avgrating = $html->find("div[class=WlYyy cPsXC fksET cMKSg]", 0)->plaintext;
					$avgrating = substr($avgrating, 0, 3);
					$avgrating = preg_replace('/[^0-9.]+/', '', $avgrating);
				}
				if($html->find("span[class=WlYyy diXIH bGusc dDKKM]", 0)){
					$totalreviews = $html->find("span[class=WlYyy diXIH bGusc dDKKM]", 0)->plaintext;
					$totalreviews = str_replace(",","",$totalreviews);
					$totalreviews = preg_replace('/[^0-9.]+/', '', $totalreviews);
				}
			}
			if($avgrating==''){
				
				
			}
			
		
		
		$response =array("avgrating"=>$avgrating,"totalreviews"=>$totalreviews);
		return $response;
	}
	
	public function wprevpro_download_tripadvisor_showuserreviews_url($currenturl) {
		ini_set('memory_limit','500M');

		if (strpos($currenturl, 'VacationRentalReview') !== false) {
			//this is a vactionrental
			$vactionrental = true;
		} else {
			$vactionrental = false;
		}
					

		$args = array(
			'timeout'     => 15,
			'sslverify' => false
		); 
		$response = wp_remote_get( $currenturl,$args );
		if ( is_array( $response ) ) {
		  $header = $response['headers']; // array of http header lines
		  $fileurlcontents = $response['body']; // use the content
		} else {
			echo esc_html__('Error finding reviews. Please contact plugin support. Error: ', 'wp-tripadvisor-review-slider');
			if( is_wp_error( $response ) ) {
				echo $response->get_error_message();
			}
			die();
		}
		
		
			//fix for lazy load base64 ""
			//$fileurlcontents = str_replace('=="', '', $fileurlcontents);
			
			//echo $fileurlcontents;
			//die();
			
			$html = wppro_str_get_html($fileurlcontents);
			//die();
			//unset($fileurlcontents);
			
			//find total and average number here
			$avgrating ='';
			$totalreviews = '';
			
			$reviewobject5 ="";
			$page2url ="";
			$rtitlelink ="";
			$nextbtnlink="";
			//echo $currenturl;
			//echo "<br><br>";
			

			//check to see if on vacation rental or regular page
			if($vactionrental==true){
				if($html->find('div.reviewSelector')){
					$reviewobject = $html->find('div.reviewSelector',0);
				} else {
					echo esc_html__('Error 102a: Unable to read Vacation Rental TripAdvisor page. Please contact support.', 'wp-tripadvisor-review-slider');
					die();	
				}
			} else {
				if($html->find('div.review-container')){
						$reviewobject = $html->find('div.review-container',0);
						$reviewobject5 = $html->find('div.review-container',5);
				}
			}
			
			//need to get the links
			if($html->find("div[class*=ReviewTitle]", 0)){
				$rtitlelink = $html->find("div[class*=ReviewTitle]", 0)->find('a',0)->href;

				if($html->find("div[class*=ReviewTitle]", 4)){
					$nextbtnlink = $html->find("div[class*=ReviewTitle]", 4)->find('a',0)->href;
				}
				//echo $rtitlelink;
				//echo 'h1';
			
			} else if($html->find("div[data-test-target*=review-title]", 0)){

				$rtitlelink = $html->find("div[data-test-target*=review-title]", 0)->find('a',0)->href;

				if($html->find("div[data-test-target*=review-title]", 4)){
					$nextbtnlink = $html->find("div[data-test-target*=review-title]", 4)->find('a',0)->href;
				}
				//echo $rtitlelink;
				//echo 'h2';
			
			} else if($html->find("div.glasR4aX", 0)){
				$rtitlelink = $html->find("div.glasR4aX", 0)->find('a',0)->href;

				if($html->find("div.glasR4aX", 4)){
					$nextbtnlink = $html->find("div.glasR4aX", 4)->find('a',0)->href;
				}
				//echo $rtitlelink;
				//echo 'h3';
			
			} else {
				//try to find links using string search here, had to add for italian languages
				$urlmatches = $this->getBetween($fileurlcontents, "/ShowUserReviews-", '"');

				//print_r($urlmatches2);
				//echo '<br><br>';
				if(isset($urlmatches[0]) && $urlmatches[0]!=''){
					$rtitlelink = "/ShowUserReviews-".$urlmatches['1'];
					$nextbtnlink = "/ShowUserReviews-".$urlmatches['6'];
					//echo $rtitlelink;
				//echo 'h4';
				} else if(isset($urlmatches[1]) && $urlmatches[1]!=''){
					$rtitlelink = "/ShowUserReviews-".$urlmatches['0'];
					$nextbtnlink = "/ShowUserReviews-".$urlmatches['5'];
					//echo $rtitlelink;
				//echo 'h4';
					
					
				} else {

					echo esc_html__('Error 103a: Unable to read TripAdvisor page. Please contact support.', 'wp-tripadvisor-review-slider');
					//echo $html;
					die();	
				}
			}

			
			//print_r($reviewobject);
			//echo "<br><br>";
			//print_r(reviewobject5);		
			
			if(isset($reviewobject) && $reviewobject!="" && $rtitlelink==''){
				$rtitlelink = $reviewobject->find('div.quote', 0)->find('a',0)->href;
				//echo $rtitlelink;
				//echo 'h5';
			}
			
			if($reviewobject5!="" && $nextbtnlink==''){
				$nextbtnlink = $reviewobject5->find('div.quote', 0)->find('a',0)->href;
			}
			
			$parseurl = parse_url($currenturl);
			$newurl = $parseurl['scheme'].'://'.$parseurl['host'].$rtitlelink; ;
			if($nextbtnlink!=''){
			$page2url = $parseurl['scheme'].'://'.$parseurl['host'].$nextbtnlink;
			}
			
			//$response =array("page1"=>$newurl,"page2"=>$page2url);
			
			$response =array("page1"=>$newurl,"page2"=>$page2url,"totalreviews"=>$totalreviews,"avgrating"=>$avgrating);
			$html->clear(); 
			unset($html);
			
			//print_r($response);
			//die();

			//create new link based on $currenturl and $rtitlelink
		return $response;
	}

	
  }
?>