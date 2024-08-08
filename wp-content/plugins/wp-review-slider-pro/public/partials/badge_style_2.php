<?php

/**
 * Provide a public-facing view for the badge style
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    WP_Review_Pro
 * @subpackage WP_Review_Pro/public/partials
 */
 //html code for the template style
$plugin_dir = WP_PLUGIN_DIR;
$imgs_url = esc_url( plugins_url( 'imgs/', __FILE__ ) );

//get the large logo url
$badgeorgin = $currentform[0]->badge_orgin;
$logourl = esc_url($template_misc_array['liconurl']);
$logourllink = esc_url($template_misc_array['liconurllink']);
$logoalt = $badgeorgin." logo".
$businessname = stripslashes(esc_html($currentform[0]->badge_bname));


	require_once('badge_class.php');	
	$badgetools = new badgetools($a['tid']);
	$totalavgarray = $badgetools->gettotalsaverages($template_misc_array,$currentform);
	$finaltotal = $totalavgarray['finaltotal'];
	$finalavg = $totalavgarray['finalavg'];
	$temprating = $totalavgarray['temprating'];



//get the large logo html
$logohtml = '';
$show_licon = esc_html($template_misc_array['show_licon']);
$logourllinktargethtml = 'target="_blank"';
if(isset($template_misc_array['liconurllink_target'])){
	$logourllinktarget = esc_html($template_misc_array['liconurllink_target']);
	if($logourllinktarget=='same'){
		$logourllinktargethtml = 'target="_self"';
	}
}
//follow or no follow, default to nofollow
$followorno = 'rel="nofollow noopener"';
if(isset($template_misc_array['liconurllink_attr'])){
	if(esc_html($template_misc_array['liconurllink_attr'])=='follow'){
		$followorno = 'rel="noopener"';
	}
	if(esc_html($template_misc_array['liconurllink_attr'])=='noreferrer'){
		$followorno = 'rel="noreferrer noopener"';
	}
	if(esc_html($template_misc_array['liconurllink_attr'])=='norefnofol'){
		$followorno = 'rel="nofollow noreferrer noopener"';
	}
}

if($show_licon=="yes"){
	//find icon width and height to use in html
	$logodirloc= dirname(__FILE__).'/imgs/'.basename($logourl);
	$wh_html = $badgetools->getimgheightwidthhtml($logourl);
	
	if($logourllink!=''){
		$logohtml = '<a href="'.$logourllink.'" '.$logourllinktargethtml.' '.$followorno.'><img src='.$logourl.' '.$wh_html.' alt="'.$logoalt.'" class="wppro_badge1_IMG_3"></a>';
	} else {
		$logohtml = '<img src='.$logourl.' '.$wh_html.' alt="'.$logoalt.'" class="wppro_badge1_IMG_3">';
	}
}
//-------------

//div12 text-----
$ctextb2 = 'reviews';
if(isset($template_misc_array['c_text'])){
	if($template_misc_array['c_text']!=''){
		$ctextb2 = esc_html($template_misc_array['c_text_b2']);
	}
}
//small icons setup-------------
$smalliconshtml = $badgetools->getsmalliconshtml($currentform[0]->style,$template_misc_array,$logourllinktargethtml,$followorno,$imgs_url);
if($smalliconshtml==''){
	$smalliconshtml=$finaltotal.' <span>'.$ctextb2.'</span>';
} else {
	$smalliconshtml='<div class="wprevb2avgtot">'.$finaltotal.' <span>'.$ctextb2.'</span></div>'.$smalliconshtml;
}
	

//starhtml setup-------------
$starhtml ='';
$roundtohalf ='';
if($finalavg>0){
$roundtohalf = round($finalavg * 2) / 2;
}

	for ($x = 1; $x <= $roundtohalf; $x++) {
			//$starhtml = $starhtml.'<span class="wprsp-star-full"></span>';
			$starhtml = $starhtml.'<span class="svgicons svg-wprsp-star-full"></span>';
	}
	if($roundtohalf==1.5||$roundtohalf==2.5||$roundtohalf==3.5||$roundtohalf==4.5){
		//add another half
		//$starhtml = $starhtml.'<span class="wprsp-star-half"></span>';
		$starhtml = $starhtml.'<span class="svgicons svg-wprsp-star-half"></span>';
		$x++;
	}
	//if x is less than 5 need another star or half
	$starleft = 5 - $x;
	for ($x = 0; $x <= $starleft; $x++) {
		//$starhtml = $starhtml.'<span class="wprsp-star-empty"></span>';
		$starhtml = $starhtml.'<span class="svgicons svg-wprsp-star-empty"></span>';
	}

//---------------------
		$customebadgetheme= get_stylesheet_directory()."/wprevpro/badge".$currentform[0]->style.".php";
		if (file_exists($customebadgetheme)) {
			include($customebadgetheme);
		} else {
?>

<div class="wprevpro_badge wppro_badge1_DIV_1" id="wprev-badge-<?php echo $currentform[0]->id; ?>">
<div class="wppro_dashboardReviewSummary">
      <div class="wppro_dashboardReviewSummary__left">
        <div class="wppro_dashboardReviewSummary__avgRating"><?php echo number_format($finalavg, 1, '.', ''); ?></div>
		<div class="wppro_b2__rating" data-rating-value="<?php echo $finalavg; ?>">
			<div class="wppro_badge1_DIV_stars bigstar"><?php echo $starhtml; ?>	</div>	
		</div>
        <div class="wppro_dashboardReviewSummary__avgReviews"><?php echo $smalliconshtml; ?></div>
      </div>
      <div class="wppro_dashboardReviewSummary__right">
<?php
for ($x = 5; $x >= 1; $x--) {
	$sizepercent[$x]=0;
	if(is_array($temprating[$x]) && array_sum($temprating[$x])>0 && $finaltotal>0){
		$temparraysum = array_sum($temprating[$x]);
		$sizepercent[$x]=round(($temparraysum/(int)$finaltotal)*100,2);
	}
	if($sizepercent[$x]>100){
		$sizepercent[$x]=100;
	}
?>  

		<div class="wppro_b2__ratingRow">
		  <span><?php echo $x; ?></span><span class="svgicons svg-wprsp-star-full ratingRow__star"></span>
			<div class="wppro_b2__ratingProgress">
			  <div class="wppro_b2__ratingProgress__fill" style="width: <?php echo $sizepercent[$x]; ?>%;"></div>
			</div>
		  <span class="wppro_b2__ratingRow__avg"><?php echo array_sum($temprating[$x]); ?></span>
		</div>
		
<?php
}
?>
      </div>
</div>
</div>
<?php
		}
?>

