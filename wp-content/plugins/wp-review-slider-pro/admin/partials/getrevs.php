<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://ljapps.com
 * @since      1.0.0
 *
 * @package    WP_Review_Pro
 * @subpackage WP_Review_Pro/admin/partials
 */
 
    // check user capabilities
	if (!current_user_can('manage_options') && $this->wprev_canuserseepage('getrevs')==false) {
        return;
    }
 
?>
    
<div class="wrap wp_pro-settings" id="">
	<h1><img src="<?php echo plugin_dir_url( __FILE__ ) . 'logo.png'; ?>"></h1>

<?php 
include("tabmenu.php");
?>
<div class="w3-row">

<div class="wprevpro_margin10">
	<a id="wprevpro_helpicon_posts" class="wprevpro_btnicononly button dashicons-before dashicons-editor-help"></a>
	<a id="wprevpro_addnewtemplate" class="button dashicons-before dashicons-plus-alt"> <?php _e('Add Review Source Site', 'wp-review-slider-pro'); ?></a>
</div>

<div class="w3-col m12 welcomediv wprevpro_hide chsourcesitediv w3-margin-bottom">

	<div class="w3-col m12">
		<div class="w3-border">
			<header class="w3-container w3-light-grey">
			  <h3 class="welcomecardheader">Choose Source Site</h3>
			</header>
			<div class="w3-container btnsourcecontainer">
<div id="sourcebtndiv">
<?php
//print_r(unserialize(WPREV_TYPE_ARRAY));
//print_r(unserialize(WPREV_TYPE_ARRAY_RF));

$typearray = unserialize(WPREV_TYPE_ARRAY);
$typearrayrf = unserialize(WPREV_TYPE_ARRAY_RF);

//create a total array with source name and also link to page.
$totalarray = Array();
$x=0;
foreach ($typearray as $value) {
	if($value!="Submitted" && $value!="Manual"){
		$totalarray[$x]['name']=$value;
		if($value=="Twitter"){
			$totalarray[$x]['link']=site_url()."/wp-admin/admin.php?page=wp_pro-get_twitter";
		} else if($value=="WooCommerce"){
			$totalarray[$x]['link']=site_url()."/wp-admin/admin.php?page=wp_pro-get_woo";
		} else {
			$totalarray[$x]['link']=site_url()."/wp-admin/admin.php?page=wp_pro-get_apps&rtype=".$value;
		}
		$totalarray[$x]['choice']='no';
		$x++;
	}
}
//print_r($totalarray);
//now for review funnels
foreach ($typearrayrf as $value) {
	//make sure this type is not in array yet.
	$key = array_search($value, array_column($totalarray, 'name'));
	//echo "<br>key.".$key;
	//echo "value.".$value;
	if($key==false && $key!==0){
		$totalarray[$x]['name']=$value;
		$totalarray[$x]['link']=site_url()."/wp-admin/admin.php?page=wp_pro-reviewfunnel&rt=".$value;
		$totalarray[$x]['choice']='no';
		$x++;
	} else {
		//search array and modify choice to yes
		for ($y = 0; $y <= count($totalarray); $y+=1) {
		  if(isset($totalarray[$y]) && $totalarray[$y]['name']==$value){
			  $totalarray[$y]['choice']='yes';
		  }
		}
		
	}
}

//sort the final array by name
$keys = array_column($totalarray, 'name');
array_multisort($keys, SORT_ASC, SORT_NATURAL|SORT_FLAG_CASE, $totalarray);
//var_dump($new);

//print_r($totalarray);

foreach ($totalarray as $value) {
	$tempname = $value['name'];
	$templink = 'href="'.$value['link'].'"';
	if($tempname=="DealerRater" || $tempname=="FindLaw" || $tempname=="Feefo" || $tempname=="Etsy"){
		$tempname ="";
	}
	$popupchoice = "";
	if($value['choice']=="yes"){
		$popupchoice = "popupchoice";
		$templink ="";
	}
	$tempasterick = "";
	if(strpos($templink, "page=wp_pro-reviewfunnel") !== false){
		$tempasterick = "*";
	}
	
	$fileext = "png";
	//check for svg. 
	$svgarray = unserialize(WPREV_SVG_ARRAY);
	if (in_array($value['name'], $svgarray)) {
		$fileext = "svg";
	}

	echo '<a '.$templink.' data-type="'.$value['name'].'" class="w3-btn w3-white w3-border w3-round sourceiconbtn '.$popupchoice.'"><img src="'.WPREV_PLUGIN_URL.'/public/partials/imgs/'.strtolower($value['name']).'_small_icon.'.$fileext.'?id='.time().'" alt="'.$value['name'].' icon" class="siconimg"> '.$tempname.' '.$tempasterick.'</a>';
	
}
?>


</div>
			</div>
		</div>
	</div>
<span id="astnote">* Requires use of Review Funnel</span> 

</div>

<?php
$currentformstotal = Array();
//Get list of all current forms--------------------------
	global $wpdb;
	$table_name = $wpdb->prefix . 'wpfb_getapps_forms';
	$currentforms = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_time_stamp DESC",ARRAY_A);
	
	foreach ( $currentforms as $currentform ) 
	{
		
		$currentform['dm']="Built In";
		$currentformstotal[]=$currentform;
	}
	
	//add twitter forms--------------------------
	$table_name = $wpdb->prefix . 'wpfb_gettwitter_forms';
	$currenttwitterforms = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_time_stamp DESC",ARRAY_A);
	
	foreach ( $currenttwitterforms as $currentform ) 
	{
		$currentform['dm']="Built In";
		$currentformstotal[]=$currentform;
	}
	
	//add woocommerce settings
	$woooptions = get_option('wprevpro_woo_settings');
	if(isset($woooptions['woo_radio_sync'])){
		if($woooptions['woo_radio_sync']!='no' && $woooptions['woo_radio_sync']!=''){
			//must have something set
			$currentwooform['site_type']='WooCommerce';
			$currentwooform['title']='WooCommerce: '.$woooptions['woo_radio_sync'];
			$currentwooform['url']=$woooptions['woo_sync_all'].", ".$woooptions['woo_name_options'].", ".$woooptions['woo_rating_options'];
			$currentwooform['dm']="Built In";
			$currentwooform['cron']=$woooptions['woo_radio_sync'];
			
			$currentformstotal[]=$currentwooform;
		}
	}
	
	//now we need RF
	$table_name = $wpdb->prefix . 'wpfb_reviewfunnel';
	$currentrfforms = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_time_stamp DESC",ARRAY_A);
	foreach ( $currentrfforms as $currentform ) 
	{
		$currentform['dm']="Review Funnel";
		$currentformstotal[]=$currentform;
	}
?>
	<div class="w3-col m12 ">
		<div class="w3-border">
			<header class="w3-container w3-light-grey">
			  <h3 class="welcomecardheader">Current Review Sources</h3>
			</header>
<?php
if(count($currentformstotal)<1){
echo '<div class="w3-container norevsyet">Nothing to see here yet. Click the "Add Review Source Site" button above to get started.</div>';
}
?>
			
</div></div>
<?php
$html ='';
//display message
if(count($currentformstotal)>0){
		$html .= '
		<table id="revsourcetbl" class="reviewsourcetbl wp-list-table widefat striped posts">
			<thead>
				<tr>
					<th scope="col" width="" class="cid manage-column"></th>
					<th scope="col" class="manage-column">'.__('Title <br>URL or Query', 'wp-review-slider-pro').'</th>
					<th scope="col" width="50px" class="manage-column">'.__('Type', 'wp-review-slider-pro').'</th>
					<th scope="col" width="125px" class="manage-column">'.__('Download Method', 'wp-review-slider-pro').'</th>
					<th scope="col" width="125px" class="manage-column">'.__('Auto Update<br>(days)', 'wp-review-slider-pro').'</th>
					<th scope="col" width="115px" class="manage-column">'.__('Last Ran', 'wp-review-slider-pro').'</th>
					<th scope="col" width="60px" class="manage-column">'.__('Created', 'wp-review-slider-pro').'</th>
				</tr>
				</thead>
			<tbody>';

	foreach ( $currentformstotal as $currentform ) 
	{
		//print_r($currentform);

		$tempblocks = '';
		if(isset($currentform['blocks']) && $currentform['blocks']>0){
			$tempblocks = ($currentform['blocks']);
		}
			
		$tempurlhtml = '';
		if(isset($currentform['url']) && $currentform['url']!=''){
			$tempurlhtml = substr(urldecode($currentform['url']),0,290);
			if(strlen(urldecode($currentform['url']))>300){
				$tempurlhtml = $tempurlhtml ."...";
			}
		}
		if($tempurlhtml=="" && isset($currentform['query'])){
			$tempurlhtml = $currentform['query'];
		}
		if($tempurlhtml=="" && isset($currentform['googleplaceid'])){
			$tempurlhtml = $currentform['googleplaceid'];
		}
		
		$lastranon = '';
		if(isset($currentform['last_ran']) && $currentform['last_ran']>0){
			//$lastranon = date("M j, Y",$currentform['last_ran']);
			$lastranon = date("m/d/Y",$currentform['last_ran']);
		}
		if($lastranon==""){
			//for review funnels
			if(isset($currentform['cron_last_ran']) && $currentform['cron_last_ran']>0){
				$lastranon = date("m/d/Y",$currentform['cron_last_ran']);
			}
		}
		
		$tempiconsite = $currentform['site_type'];
		if($tempiconsite=='Google-Places-API'){
			$tempiconsite = 'Google';
		}
		$fileext = "png";
		//check for svg. 
		$svgarray = unserialize(WPREV_SVG_ARRAY);
		if (in_array($tempiconsite, $svgarray)) {
			$fileext = "svg";
		}
		$tempicon = WPREV_PLUGIN_URL.'/public/partials/imgs/'.strtolower($tempiconsite).'_small_icon.'.$fileext;
		
		//edit button url.
		if($currentform['dm']!="Review Funnel"){
			if($currentform['site_type']=="Twitter"){
				$url_tempeditbtn = site_url()."/wp-admin/admin.php?page=wp_pro-get_twitter&vfid=".$currentform['id'];
			} else if($currentform['site_type']=="WooCommerce"){
				$url_tempeditbtn = site_url()."/wp-admin/admin.php?page=wp_pro-get_woo";
			} else {
				$url_tempeditbtn = site_url()."/wp-admin/admin.php?page=wp_pro-get_apps&rtype=".$currentform['site_type']."&vfid=".$currentform['id'];
			}
		} else if($currentform['dm']=="Review Funnel"){
			$url_tempeditbtn = site_url()."/wp-admin/admin.php?page=wp_pro-reviewfunnel&vfid=".$currentform['id'];
		}
		
		$autoupdate ="";
		if(isset($currentform['cron']) && $currentform['cron']>0){
			$autoupdate = intval($currentform['cron'])/24;
		}
		if($autoupdate==0){
			$autoupdate ="";
		}
		//changing built-in depending on local or remote.
		if($currentform['dm']=="Built In"){
			if(isset($currentform['crawlserver']) && $currentform['crawlserver']=='remote'){
				$currentform['dm']= 'Crawl Remote';
			} else {
				$currentform['dm']= 'Crawl Local';
			}
			if($currentform['site_type']=='Google'){
				$currentform['dm']= 'Crawl Remote';
			}

			if($currentform['site_type']=='Birdeye' || $currentform['site_type']=='Freemius' || $currentform['site_type']=='Qualitelis' || $currentform['site_type']=='StyleSeat' || $currentform['site_type']=='Facebook' || $currentform['site_type']=='TrueLocal' || $currentform['site_type']=='Twitter' || $currentform['site_type']=='Yotpo' || $currentform['site_type']=='Google-Places-API'){
				$currentform['dm']= 'API';
			}
			
			if($currentform['site_type']=='WooCommerce' || $currentform['site_type']=='WordPress'){
				$currentform['dm']= 'Built In';
			}

			if($currentform['site_type']=='AngiesList'){
				$currentform['dm']= 'Crawl Remote';
			}
			if($currentform['site_type']=='CreativeMarket'){
				$currentform['dm']= 'Crawl Remote';
			}
			if($currentform['site_type']=='TripAdvisor'){
				$currentform['dm']= 'Crawl Remote';
				if($currentform['crawlserver']=='local'){
					$currentform['dm']= 'Crawl Local';
				}
			}
			if($currentform['site_type']=='Zillow'){
				$currentform['dm']= 'Crawl Local';
				if($currentform['crawlserver']=='remote'){
					$currentform['dm']= 'Crawl Remote';
				}
			}

		}
		
		$cid='';
		if(isset($currentform['id'])){
			$cid=$currentform['id'];
		}
		
		if(!isset($currentform['created_time_stamp'])){
			$currentform['created_time_stamp']='';
		}
	
		//for review funnels
		$created ='';
		if(isset($currentform['created_time_stamp']) && $currentform['created_time_stamp']>0){
			$created = date("m/d/Y",$currentform['created_time_stamp'])."<br>".date("h:i:s A",$currentform['created_time_stamp']);
		}


		$html .= '<tr id="'.$cid.'" class="locationrow" data-blocks="'.$tempblocks.'">
				<td scope="col" class="cid manage-column">'.$currentform['created_time_stamp'].'</td>
				<td scope="col" class=" manage-column" style="min-width: 250px;"><a href="'.$url_tempeditbtn.'" class="viewgarfbtn rfbtn dashicons-before dashicons-search"><b><span class="titlespan">'.stripslashes($currentform['title']).'</span></b></a><br><span style="font-size:10px;">'.stripslashes($tempurlhtml).'</span></td>
				<td scope="col" class=" manage-column"><img src="'.$tempicon.'" alt="'.$currentform['site_type'].' icon" class="sisiconimg"><span class="wprevpro_hide">'.$currentform['site_type'].'</span></td>
				<td scope="col" class=" manage-column">'.$currentform['dm'].'</td>
				<td scope="col" class=" manage-column"><b>'.$autoupdate.'</b></td>
				<td scope="col" class=" manage-column">'.$lastranon.'</td>
				<td scope="col" class="manage-column">'.$created.'</td>
				
			</tr>';

	}
	
		$html .= '</tbody></table>';
echo $html;
//echo "<div></br>Coming Soon! Review Funnels will give you a way to download reviews from more than 40 different sites!</br></br></div>"; 
}
?>


<?php 
//require_once('getrevs_sidemenu.php');
?>

</div>
</div>

<div id="helppopup" class="wprevpro_hide">
<div class="w3-row-padding choicecontainer">
	<div class="w3-col m12">
	<p class="description">
			<?php _e('Use the <b>"Add New Review Source Site"</b> button to start downloading reviews. This page will also show you a list of currently set up source sites.', 'wp-review-slider-pro'); ?>
	</p>
	</div>
	</div>
</div>

<div id="airbnb_choosepopupdiv" class="wprevpro_hide">
<div class="w3-row-padding choicecontainer">
	
	<div class="w3-col m6">
	<div class="w3-card-4">
	<header class="w3-container w3-light-grey">
	  <h3 class="htitle"><img src="https://wptest.ljapps.com/wp-content/plugins/wp-review-slider-pro/public/partials/imgs/airbnb_small_icon.png" class="popsiconimg"> Airbnb Crawl</h3>
	</header>
	<div class="w3-container">
	  <p><b>Pros:</b></p>
	  <p>+ Easiest and simplest.</p>
	  <p>+ Uses built-in crawl method.</p>
	</div>
	<a href="<?php echo $urlget['get_apps_airbnb']; ?>" class="w3-button w3-block w3-dark-grey">+ Select</a>
	</div>
	</div>
	<div class="w3-col m6">
	<div class="w3-card-4">
	<header class="w3-container w3-light-grey">
	  <h3 class="htitle"><img src="https://wptest.ljapps.com/wp-content/plugins/wp-review-slider-pro/public/partials/imgs/airbnb_small_icon.png" class="popsiconimg"> Airbnb Review Funnel</h3>
	</header>
	<div class="w3-container">
	<p><b>Pros:</b></p>
	  <p>+ Uses third-party scraping service.</p>
	  <p>+ No API Key required.</p>
	  <hr>
	  <p><b>Cons:</b></p>
	  <p>- Costs Review Credits. Each site gets 2,000 free credits a year.</p>
	</div>
	<a href="<?php echo $urlget['review_funnel']; ?>&rt=Airbnb" class="w3-button w3-block w3-dark-grey">+ Select</a>
	</div>
	</div>
</div>
</div>

<div id="angieslist_choosepopupdiv" class="wprevpro_hide">
<div class="w3-row-padding choicecontainer">
	
	<div class="w3-col m6">
	<div class="w3-card-4">
	<header class="w3-container w3-light-grey">
	  <h3 class="htitle"><img src="https://wptest.ljapps.com/wp-content/plugins/wp-review-slider-pro/public/partials/imgs/airbnb_small_icon.png" class="popsiconimg"> AngiesList Crawl</h3>
	</header>
	<div class="w3-container">
	  <p><b>Pros:</b></p>
	  <p>+ Easiest and simplest.</p>
	  <p>+ Uses built-in crawl method.</p>
	</div>
	<a href="<?php echo $urlget['get_apps_angieslist']; ?>" class="w3-button w3-block w3-dark-grey">+ Select</a>
	</div>
	</div>
	<div class="w3-col m6">
	<div class="w3-card-4">
	<header class="w3-container w3-light-grey">
	  <h3 class="htitle"><img src="https://wptest.ljapps.com/wp-content/plugins/wp-review-slider-pro/public/partials/imgs/airbnb_small_icon.png" class="popsiconimg"> AngiesList Review Funnel</h3>
	</header>
	<div class="w3-container">
	<p><b>Pros:</b></p>
	  <p>+ Uses third-party scraping service.</p>
	  <p>+ No API Key required.</p>
	  <hr>
	  <p><b>Cons:</b></p>
	  <p>- Costs Review Credits. Each site gets 2,000 free credits a year.</p>
	</div>
	<a href="<?php echo $urlget['review_funnel']; ?>&rt=AngiesList" class="w3-button w3-block w3-dark-grey">+ Select</a>
	</div>
	</div>
</div>
</div>


<div id="facebook_choosepopupdiv" class="wprevpro_hide">
<div class="w3-row-padding choicecontainer">
	<div class="w3-col m6">
	<div class="w3-card-4">
	<header class="w3-container w3-light-grey">
	  <h3 class="htitle"><img src="https://wptest.ljapps.com/wp-content/plugins/wp-review-slider-pro/public/partials/imgs/facebook_small_icon.png" class="popsiconimg"> Facebook API</h3>
	</header>
	<div class="w3-container">
	  <p><b>Pros:</b></p>
	  <p>+ Uses the official Facebook API.</p>
	  <p>+ Most reliable method.</p>
	  <hr>
	  <p><b>Cons:</b></p>
	  <p>- Requires you have Admin access to Facebook page.</p>
	  <p>- Does not return overall review total and avg.</p>
	</div>
	<a href="<?php echo $urlget['get_apps_facebook']; ?>" class="w3-button w3-block w3-dark-grey">+ Select</a>
	</div>
	</div>
	<div class="w3-col m6">
	<div class="w3-card-4">
	<header class="w3-container w3-light-grey">
	  <h3 class="htitle"><img src="https://wptest.ljapps.com/wp-content/plugins/wp-review-slider-pro/public/partials/imgs/facebook_small_icon.png" class="popsiconimg"> Review Funnel</h3>
	</header>
	<div class="w3-container">
	<p><b>Pros:</b></p>
	  <p>+ Uses third-party scraping service.</p>
	  <p>+ No Facebook page Admin access required..</p>
	  <hr>
	  <p><b>Cons:</b></p>
	  <p>- Facebook review page must be publicly viewable.</p>
	  <p>- Costs Review Credits. Each site gets 2,000 free credits a year.</p>
	</div>
	<a href="<?php echo $urlget['review_funnel']; ?>&rt=Facebook" class="w3-button w3-block w3-dark-grey">+ Select</a>
	</div>
	</div>
</div>
</div>

<div id="google_choosepopupdiv" class="wprevpro_hide">
<div class="w3-row-padding choicecontainer">
	<div class="w3-col m4">
	<div class="w3-card-4">
	<header class="w3-container w3-light-grey">
	  <h3 class="htitle"><img src="https://wptest.ljapps.com/wp-content/plugins/wp-review-slider-pro/public/partials/imgs/google_small_icon.png" class="popsiconimg"> Google Crawl</h3>
	</header>
	<div class="w3-container">
	  <p><b>Pros:</b></p>
	  <p>+ Easiest and simplest method.</p>
	  <p>+ Downloads your latest or most helpful 40 reviews.</p>
	  <p>+ Will also download images.</p>
	  <p>+ No API Key required.</p>
	  <hr>
	  <p><b>Cons:</b></p>
	  <p>- Limited to 100 locations</p>
	</div>
	<a href="<?php echo $urlget['get_apps_gcrawl']; ?>" class="w3-button w3-block w3-dark-grey">+ Select</a>
	</div>
	</div>
	<div class="w3-col m4">
	<div class="w3-card-4">
	<header class="w3-container w3-light-grey">
	  <h3 class="htitle"><img src="https://wptest.ljapps.com/wp-content/plugins/wp-review-slider-pro/public/partials/imgs/google_small_icon.png" class="popsiconimg"> Review Funnel</h3>
	</header>
	<div class="w3-container">
	<p><b>Pros:</b></p>
	  <p>+ Can download all of your past Google Reviews.</p>
	  <p>+ No API Key required.</p>
	  <hr>
	  <p><b>Cons:</b></p>
	  <p>- Costs Review Credits. Each site gets 2,000 free credits a year.</p>
	</div>
	<a href="<?php echo $urlget['review_funnel']; ?>&rt=Google" class="w3-button w3-block w3-dark-grey">+ Select</a>
	</div>
	</div>
	<div class="w3-col m4">
	<div class="w3-card-4">
	<header class="w3-container w3-light-grey">
	  <h3 class="htitle"><img src="https://wptest.ljapps.com/wp-content/plugins/wp-review-slider-pro/public/partials/imgs/google_small_icon.png" class="popsiconimg"> Google Places API</h3>
	</header>
	<div class="w3-container">
	<p><b>Pros:</b></p>
	  <p>+ Uses approved Google Places API.</p>
	  <p>+ Will download your Most Helpful or Newest 5 reviews.</p>
	  <p>+ Best for directory style sites.</p>
	  <hr>
	  <p><b>Cons:</b></p>
	  <p>- Must have a physical address on Google Maps.</p>
	  <p>- Requires you to obtain Google Places API Key from Google.</p>
	</div>
	<a href="<?php echo $urlget['get_apps_googleplacesapi']; ?>" class="w3-button w3-block w3-dark-grey">+ Select</a>
	</div>
	</div>
</div>
</div>


<div id="tripadvisor_choosepopupdiv" class="wprevpro_hide">
<div class="w3-row-padding choicecontainer">
	<div class="w3-col m4">
	<div class="w3-card-4">
	<header class="w3-container w3-light-grey">
	  <h3 class="htitle"><img src="https://wptest.ljapps.com/wp-content/plugins/wp-review-slider-pro/public/partials/imgs/tripadvisor_small_icon.png" class="popsiconimg"> TripAdvisor Crawl</h3>
	</header>
	<div class="w3-container">
	  <p><b>Pros:</b></p>
	  <p>+ Can also download images.</p>
	  <p>+ No API Key required.</p>
	  <hr>
	  <p><b>Cons:</b></p>
	  <p>- Limited to 100 locations and 50 reviews each.</p>
	  <p>- Not the most reliable.</p>
	</div>
	<a href="<?php echo $urlget['get_apps_tripadvisor']; ?>" class="w3-button w3-block w3-dark-grey">+ Select</a>
	</div>
	</div>
	<div class="w3-col m4">
	<div class="w3-card-4">
	<header class="w3-container w3-light-grey">
	  <h3 class="htitle"><img src="https://wptest.ljapps.com/wp-content/plugins/wp-review-slider-pro/public/partials/imgs/tripadvisor_small_icon.png" class="popsiconimg"> Review Funnel</h3>
	</header>
	<div class="w3-container">
	<p><b>Pros:</b></p>
	  <p>+ Can download all of your past TripAdvisor Reviews.</p>
	  <p>+ No API Key required.</p>
	  <hr>
	  <p><b>Cons:</b></p>
	  <p>- Costs Review Credits. Each site gets 2,000 free credits a year.</p>
	</div>
	<a href="<?php echo $urlget['review_funnel']; ?>&rt=TripAdvisor" class="w3-button w3-block w3-dark-grey">+ Select</a>
	</div>
	</div>
	<div class="w3-col m4">
	<div class="w3-card-4">
	<header class="w3-container w3-light-grey">
	  <h3 class="htitle"><img src="https://wptest.ljapps.com/wp-content/plugins/wp-review-slider-pro/public/partials/imgs/tripadvisor_small_icon.png" class="popsiconimg"> TripAdvisor API</h3>
	</header>
	<div class="w3-container">
	<p><b>Coming Soon!</b></p>
	 
	</div>
	</div>
</div>
</div>

<div id="yelp_choosepopupdiv" class="wprevpro_hide">
<div class="w3-row-padding choicecontainer">
	<div class="w3-col m6">
	<div class="w3-card-4">
	<header class="w3-container w3-light-grey">
	  <h3 class="htitle"><img src="https://wptest.ljapps.com/wp-content/plugins/wp-review-slider-pro/public/partials/imgs/yelp_small_icon.png" class="popsiconimg"> Yelp Crawl</h3>
	</header>
	<div class="w3-container">
	  <p><b>Pros:</b></p>
	  <p>+ Easiest and simplest.</p>
	  <p>+ Uses built-in crawl method.</p>
	  <p>+ Can also download images.</p>
	</div>
	<a href="<?php echo $urlget['get_apps_yelp']; ?>" class="w3-button w3-block w3-dark-grey">+ Select</a>
	</div>
	</div>
	<div class="w3-col m6">
	<div class="w3-card-4">
	<header class="w3-container w3-light-grey">
	  <h3 class="htitle"><img src="https://wptest.ljapps.com/wp-content/plugins/wp-review-slider-pro/public/partials/imgs/yelp_small_icon.png" class="popsiconimg"> Review Funnel</h3>
	</header>
	<div class="w3-container">
	<p><b>Pros:</b></p>
	  <p>+ Uses third-party scraping service.</p>
	  <p>+ No API Key required.</p>
	  <hr>
	  <p><b>Cons:</b></p>
	  <p>- Costs Review Credits. Each site gets 2,000 free credits a year.</p>
	</div>
	<a href="<?php echo $urlget['review_funnel']; ?>&rt=Yelp" class="w3-button w3-block w3-dark-grey">+ Select</a>
	</div>
	</div>
</div>
</div>

<div id="zillow_choosepopupdiv" class="wprevpro_hide">
<div class="w3-row-padding choicecontainer">
	<div class="w3-col m6">
	<div class="w3-card-4">
	<header class="w3-container w3-light-grey">
	  <h3 class="htitle"><img src="https://wptest.ljapps.com/wp-content/plugins/wp-review-slider-pro/public/partials/imgs/zillow_small_icon.png" class="popsiconimg"> Zillow Crawl</h3>
	</header>
	<div class="w3-container">
	  <p><b>Pros:</b></p>
	  <p>+ Easiest and simplest.</p>
	  <p>+ Uses built-in crawl method.</p>
	  <hr>
	  <p><b>Cons:</b></p>
	  <p>- May not work for some people.</p>
	  <p>- Can not download Lender Reviews.</p>
	</div>
	<a href="<?php echo $urlget['get_apps_zillow']; ?>" class="w3-button w3-block w3-dark-grey">+ Select</a>
	</div>
	</div>
	<div class="w3-col m6">
	<div class="w3-card-4">
	<header class="w3-container w3-light-grey">
	  <h3 class="htitle"><img src="https://wptest.ljapps.com/wp-content/plugins/wp-review-slider-pro/public/partials/imgs/zillow_small_icon.png" class="popsiconimg"> Review Funnel</h3>
	</header>
	<div class="w3-container">
	<p><b>Pros:</b></p>
	  <p>+ Uses third-party scraping service.</p>
	  <p>+ No API Key required.</p>
	  <p>+ Can download Lender Reviews</p>
	  <hr>
	  <p><b>Cons:</b></p>
	  <p>- Costs Review Credits. Each site gets 2,000 free credits a year.</p>
	</div>
	<a href="<?php echo $urlget['review_funnel']; ?>&rt=Zillow" class="w3-button w3-block w3-dark-grey">+ Select</a>
	</div>
	</div>
</div>
</div>

<div id="vrbo_choosepopupdiv" class="wprevpro_hide">
<div class="w3-row-padding choicecontainer">
	<div class="w3-col m6">
	<div class="w3-card-4">
	<header class="w3-container w3-light-grey">
	  <h3 class="htitle"><img src="https://wptest.ljapps.com/wp-content/plugins/wp-review-slider-pro/public/partials/imgs/vrbo_small_icon.png" class="popsiconimg"> VRBO Crawl</h3>
	</header>
	<div class="w3-container">
	  <p><b>Pros:</b></p>
	  <p>+ Easiest and simplest.</p>
	  <p>+ Uses built-in crawl method.</p>
	  <hr>
	  <p><b>Cons:</b></p>
	  <p>- Can only grab your most recent reviews.</p>
	</div>
	<a href="<?php echo $urlget['get_apps_vrbo']; ?>" class="w3-button w3-block w3-dark-grey">+ Select</a>
	</div>
	</div>
	<div class="w3-col m6">
	<div class="w3-card-4">
	<header class="w3-container w3-light-grey">
	  <h3 class="htitle"><img src="https://wptest.ljapps.com/wp-content/plugins/wp-review-slider-pro/public/partials/imgs/vrbo_small_icon.png" class="popsiconimg"> Review Funnel</h3>
	</header>
	<div class="w3-container">
	<p><b>Pros:</b></p>
	  <p>+ Uses third-party scraping service.</p>
	  <p>+ No API Key required.</p>
	  <hr>
	  <p><b>Cons:</b></p>
	  <p>- Costs Review Credits. Each site gets 2,000 free credits a year.</p>
	</div>
	<a href="<?php echo $urlget['review_funnel']; ?>&rt=VRBO" class="w3-button w3-block w3-dark-grey">+ Select</a>
	</div>
	</div>
</div>
</div>







