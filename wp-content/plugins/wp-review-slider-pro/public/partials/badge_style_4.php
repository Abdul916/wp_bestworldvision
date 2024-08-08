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
$finaltotalhtml ='';


//get badge totals and averages
	require_once('badge_class.php');	
	$badgetools = new badgetools($a['tid']);
	$totalavgarray = $badgetools->gettotalsaverages($template_misc_array,$currentform);
	$finaltotal = $totalavgarray['finaltotal'];
	$finalavg = $totalavgarray['finalavg'];
	$finaltotalnospace = $totalavgarray['finaltotal'];
	
	

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

//-------------

//div12 text-----
$ctext = '';
if(isset($template_misc_array['c_text'])){
	if($template_misc_array['c_text']!=''){
		$ctext = esc_html($template_misc_array['c_text']);
	}
}
$ctext2 = '';
if(isset($template_misc_array['c_text2'])){
	if($template_misc_array['c_text2']!=''){
		$ctext2 = esc_html($template_misc_array['c_text2']);
	}
}

$ctext_url = '';
if(isset($template_misc_array['c_text_url'])){
	if($template_misc_array['c_text_url']!=''){
		$ctext_url = esc_html($template_misc_array['c_text_url']);
	}
}

$ctext_url_title = '';
if(isset($template_misc_array['c_text_url_title'])){
	if($template_misc_array['c_text_url_title']!=''){
		$ctext_url_title = esc_html($template_misc_array['c_text_url_title']);
	}
}

$ctext_url_start='';
$ctext_url_end='';
if($ctext_url!=''){
	$ctext_url_start='<a href="'.$ctext_url.'" title="'.$ctext_url_title.'" '.$followorno.' class="wppro_badge1_A_14" '.$logourllinktargethtml.'>';
	$ctext_url_end='</a>';
} else {
	$ctext_url_start='<span class="wppro_badge1_A_14_span">';
	$ctext_url_end='</span>';
}

				
if($ctext=='' && $ctext2==''){
	$div12html ='';
} else if($ctext=='' && $ctext2!=''){
	
	$div12html = '<span class="wppro_badge1_DIV_12 b4s12">'.$ctext_url_start.'<span class="wppro_badge1_SPAN_15">'.$finaltotalnospace.'</span>&nbsp;'.$ctext2.$ctext_url_end.'</span>';
	
} else if($ctext!='' && $ctext2==''){
	
	$div12html = '<span class="wppro_badge1_DIV_12 b4s12"><span class="wppro_badge1_SPAN_13 b3s13">'.$ctext_url_start.$finalavg.'</span>&nbsp;'.$ctext.$ctext_url_end.'</span>';
	
	
} else if($ctext!='' && $ctext2!=''){
	$div12html = '<span class="wppro_badge1_DIV_12 b4s12"><span class="wppro_badge1_SPAN_13 b3s13">'.$finalavg.'</span>&nbsp;'.$ctext.'&nbsp;'.$ctext_url_start.'<span class="wppro_badge1_SPAN_15">'.$finaltotalnospace.'</span>&nbsp;'.$ctext2.$ctext_url_end.'</span>';
}


//starhtml setup-------------
$starhtml ='';
$roundtohalf ='';
if($finalavg>0){
$roundtohalf = round($finalavg * 2) / 2;
}
if($badgeorgin=='yelp' || $badgeorgin=='tripadvisor'){
		$starhtml = $starhtml.'<img src="'.$imgs_url.$badgeorgin.'_stars_'.$roundtohalf.'.png" alt="'.$finalavg.' '.$badgeorgin.' stars" class="wppro_badge_stars">';
} else {

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

}
//---------------------
//load template from child theme if found
		$customebadgetheme= get_stylesheet_directory()."/wprevpro/badge".$currentform[0]->style.".php";
		if (file_exists($customebadgetheme)) {
			include($customebadgetheme);
		} else {
?>

<div class="wprevpro_badge wppro_badge4_DIV_1 b4s1" id="wprev-badge-<?php echo $currentform[0]->id; ?>">
			<span class="wppro_badge1_DIV_stars b4s2">
			<?php echo $starhtml; ?>
			</span>
			<?php echo $div12html; ?>
</div>
<?php
		}
?>
