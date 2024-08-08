<?php

/**
 * Provide a public-facing view for the plugin
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
require_once("template_class.php");
$templateclass = new Template_Functions();
$revcount = 1;
$templatenum = intval($currentform[0]->style);

//loop if more than one row
for ($x = 0; $x < count($rowarray); $x++) {
	if(	$currentform[0]->display_masonry!="yes" && $currentform[0]->createslider != "sli"){
	if(	$currentform[0]->template_type=="widget"){
		$iswidget=true;
		?>
		<div class="wprevpro_t3_outer_div_widget w3_wprs-row-padding-small wprevprodiv">
		<?php
		} else {
			$iswidget=false;
		?>
		<div class="wprevpro_t3_outer_div w3_wprs-row wprevprodiv">
		<?php
	}
	}
	//loop 
	foreach ( $rowarray[$x] as $review ) 
	{
		
					//fix for slickslider more than one row loading on ajax
			$closediv=false;
			if($ajaxsliload == true && $currentform[0]->createslider == "sli" && $sliusingfilter==false){
				if($nrows>1){
					if($revcount==1 || $revcount == $nrows+1 || $revcount == 2*$nrows+1 || $revcount == 3*$nrows+1 || $revcount == 4*$nrows+1){
						echo "<div loop='".$looper."' revcount='".$revcount."' nrows='".$nrows."'>";
					}
					if($revcount == $nrows || $revcount == $nrows*2 || $revcount == $nrows*3 || $revcount == $nrows*4){
						$closediv=true;
					}
				}
				echo "<div class='ajaxsli'><div>";
			}
			
		//add to reviews number array
		$reviewratingsarray[] = $review->rating;
		
		//get userpic, functions in the class-wp-review-slider-pro-public.php file
		//$imagecachedir = plugin_dir_path( __FILE__ ).'cache/';
		$userpic = $templateclass->wprevpro_get_user_pic($review,'142','142',$currentform[0]);
		
		//star number --------------------------------------
		$starfile_burl_logo_array = $templateclass->wprevpro_get_star_logo_burl($review,$imgs_url,$currentform[0],"t3",$template_misc_array);
		$starfile = $starfile_burl_logo_array['starfile'];
		$logo =  $starfile_burl_logo_array['logo'];
		$burl = $starfile_burl_logo_array['burl'];		

		$reviewtext = "";
		//review text --------------------------
		if(!isset($template_misc_array['length_type'])){
			$template_misc_array['length_type']='words';
		}
		$reviewtext = $templateclass->wprevpro_get_reviewtext($review,$currentform[0],$template_misc_array);
		
		
		//per a row
		if($currentform[0]->display_num>0){
			$perrow = 12/$currentform[0]->display_num;
			//fix if less found in database.
			if($totalreviewsnum<$currentform[0]->display_num){
				$perrow = 12/$totalreviewsnum;
			}
		} else {
			$perrow = 4;
		}
	
		//date format MM/DD/YYYY, DD/MM/YY, DD/MM/YYYY, YYYY-MM-DD
		$datestring = $templateclass->wprevpro_get_datestring($review,$template_misc_array);

		
		//========company format   Owner, Dental Practice Website
		$companyhtml = $templateclass->wprevpro_get_companyhtml($review,$template_misc_array,"t3");
		
		//last name display options
		$tempreviewername = $templateclass->wprevpro_get_reviewername($review,$template_misc_array);
		
		//link to author url if turned on in template, use reviewer_id from db and create url for different types
		$profilelink = $templateclass->wprevpro_get_profilelink($review,$currentform[0],$userpic,$tempreviewername,$template_misc_array,$burl);
		
		//userpic html, this could change to nothing if userpic turned off.
		//$userpichtml = $profilelink['userpichtml'];
		//userpic html
		if(!isset($template_misc_array['default_avatar'])){
			$template_misc_array['default_avatar']='none';
		}
		//if the review does not have a image we hide it or show mystery depending on settin
		if($userpic==""){
			//use setting to determine default mystery man if set
			if($template_misc_array['default_avatar']=='fb'){
				$userpic = $imgs_url.'fb_mystery_man_big.png';
			} else if($template_misc_array['default_avatar']=='trip'){
				$userpic = $imgs_url.'trip_myster_man_big.png';
			} else if($template_misc_array['default_avatar']=='google'){
				$userpic = $imgs_url.'google_mystery_man_big.png';
			} else if($template_misc_array['default_avatar']=='yelp'){
				$userpic = $imgs_url.'yelp_mystery_man_big.png';
			} else if($template_misc_array['default_avatar']=='airbnb'){
				$userpic = $imgs_url.'airbnb_mystery_man_small.png';
			} else if($template_misc_array['default_avatar']=='init'){
				$userpic = 'https://avatar.oxro.io/avatar.svg?name='.str_replace(' ', '+', $tempreviewername);
			}
		}
		$tempuserpic ='';
		if($userpic==""){
			$userpichtml = '<div class="wpproslider_t3_avatar_div"></div>';
		} else {
			$altname = htmlspecialchars(stripslashes(strip_tags($review->reviewer_name)));
			$userpichtml = '<div class="wpproslider_t3_avatar_div">'.$profilelink['start'].'<img loading="lazy" src="'.$userpic.'" alt="'.$altname.' Avatar" class="wpproslider_t3_avatar wprevpro_avatarimg" />'.$profilelink['end'].'</div>';
			if(isset($template_misc_array['avataropt'])){
				if($template_misc_array['avataropt']=='hide'){
					$userpichtml = '';
				} else if($template_misc_array['avataropt']=='mystery'){
					if($review->type=="Yelp"){
						$tempuserpic = $imgs_url.'yelp_mystery_man_big.png';
					} else if ($review->type=="TripAdvisor"){
						$tempuserpic = $imgs_url.'trip_myster_man_big.png';
					} else if ($review->type=="Google"){
						$tempuserpic = $imgs_url.'google_mystery_man_big.png';
					} else if ($review->type=="Airbnb"){
						$tempuserpic = $imgs_url.'airbnb_mystery_man_small.png';
					} else {
						$tempuserpic = $imgs_url.'fb_mystery_man_big.png';
						//use setting to determine default mystery man if set
						if($template_misc_array['default_avatar']=='trip'){
							$tempuserpic = $imgs_url.'trip_myster_man_big.png';
						} else if($template_misc_array['default_avatar']=='google'){
							$tempuserpic = $imgs_url.'google_mystery_man_big.png';
						} else if($template_misc_array['default_avatar']=='yelp'){
							$tempuserpic = $imgs_url.'yelp_mystery_man_big.png';
						} else if($template_misc_array['default_avatar']=='airbnb'){
							$tempuserpic = $imgs_url.'airbnb_mystery_man_small.png';
						}
					}
					$userpichtml = '<div class="wpproslider_t3_avatar_div">'.$profilelink['start'].'<img loading="lazy" src="'.$tempuserpic.'" alt="'.$altname.' Avatar" class="wpproslider_t3_avatar wprevpro_avatarimg" />'.$profilelink['end'].'</div>';
				} else if($template_misc_array['avataropt']=='init'){
					$tempuserpic = 'https://avatar.oxro.io/avatar.svg?name='.str_replace(' ', '+', $tempreviewername);
					$userpichtml = '<div class="wpproslider_t3_avatar_div">'.$profilelink['start'].'<img loading="lazy" src="'.$tempuserpic.'" alt="'.$altname.' Avatar" class="wpproslider_t3_avatar wprevpro_avatarimg" />'.$profilelink['end'].'</div>';
				}
			}
		}

		
		$reviewnamehtml = '<div id="wprev_showname">'.$tempreviewername.'</div>';
		
		//title option
		$title = $templateclass->wprevpro_get_title($review,$template_misc_array,$templatenum);
		
		//readmore or scroll different starting in v11.9.0
		$rdmorescroll = $templateclass->wprevpro_get_rdmorescroll($review,$currentform[0],$template_misc_array);
		
		
		//masonry style
		$tempwidget = '';
		if(	$currentform[0]->template_type=="widget"){
			$tempwidget =' marginb10';
		}
		$tempmasonrydiv =' wprevpro_t3_DIV_1'.$tempwidget.' w3_wprs-col l'.$perrow;
		if($ajaxsliload && $currentform[0]->createslider == "sli"  && $sliusingfilter==false){
			$tempmasonrydiv =' wprevpro_t1_DIV_1'.$tempwidget.' w3_wprs-col ';
		}
		
		if(	$currentform[0]->display_masonry=="yes" && $currentform[0]->createslider != "sli"){
			$tempmasonrydiv =$masonryclass_item.' wprevpro_t3_DIV_1'.$tempwidget.' ';
		}		
			
		//verifiedstarhtlm
		$verifiedstarhtmlarray = $templateclass->wprevpro_get_verifiedstarhtml($review,$template_misc_array,$currentform[0]);
		
		//starhtlm
		$starhtmlarray = $templateclass->wprevpro_get_starhtml($review,$template_misc_array,$currentform[0],$starfile);
		$starhtml = $starhtmlarray[0];
		if($starhtml!=''){
			$starhtml = $starhtmlarray[0].$verifiedstarhtmlarray[0];
		}
		$starhtml2 = $starhtmlarray[1];
		if($starhtml2!=''){
			$starhtml2 = $starhtmlarray[1].$verifiedstarhtmlarray[0];
		}
	
	//miscpichtml, for woocommerce
		$miscpichtml = $templateclass->wprevpro_get_miscpichtml($review,$currentform[0],$template_misc_array);
		
		//media
		$media = $templateclass->wprevpro_get_media($review,$template_misc_array);
		
//source pagename or form title
		$sourcepagenamehtml = $templateclass->wprevpro_get_sourcepagename($review,$currentform[0],$template_misc_array);
		
		//readmore pop-up setting, basically adding style 6 in hidden div and then pop it up if set.
		$readmorepopup = $templateclass->wprevpro_get_readmorepop($reviewtext,$template_misc_array,$starhtml,$tempreviewername,$userpic,$tempuserpic,$datestring,$title,$logo,$media);

		//owner response
		$ownerresponse = $templateclass->wprevpro_get_ownerresponse($review,$template_misc_array,$templatenum);

		//load template from child theme if found
		$custometheme= get_stylesheet_directory()."/wprevpro/template".$currentform[0]->style.".php";
		if (file_exists($custometheme)) {
			include($custometheme);
		} else {
	?>
		<div class="<?php echo $tempmasonrydiv; ?> outerrevdiv">
			<div class="indrevdiv wpproslider_t3_DIV_1a<?php if($iswidget){echo "_widget";} ?> wprev_preview_bg2_T<?php echo $currentform[0]->style; ?><?php if($iswidget){echo "_widget";} ?> wprev_preview_bradius_T<?php echo $currentform[0]->style; ?><?php if($iswidget){echo "_widget";} ?>">
				<div class="wpproslider_t3_DIV_2 wprev_preview_bg1_T<?php echo $currentform[0]->style; ?><?php if($iswidget){echo "_widget";} ?> wprev_preview_tcolor2_T<?php echo $currentform[0]->style; ?><?php if($iswidget){echo "_widget";} ?> wprev_preview_tcolor3_T<?php echo $currentform[0]->style; ?><?php if($iswidget){echo "_widget";} ?>">
					<?php echo $userpichtml; ?>
					<?php echo $reviewnamehtml; ?><?php echo $companyhtml;?><?php echo $starhtml2;?>
				</div>
				<div class="indrevtxt wpproslider_t3_P_3 wprev_preview_tcolor1_T<?php echo $currentform[0]->style; ?><?php if($iswidget){echo "_widget";} ?>"><img src="<?php echo $imgs_url."testimonial_quote.png"; ?>" alt="" class="wpproslider_t3_quote"><?php echo $rdmorescroll[0]; ?><span class="wprevpro_star_imgs_T<?php echo $currentform[0]->style; ?><?php if($iswidget){echo "_widget";} ?>"><?php echo $starhtml;?></span>
					<?php echo $title; ?><span class="wppro-reviewtext_T<?php echo $currentform[0]->style; ?>"><?php echo stripslashes($reviewtext); ?></span> <span class="wprev_showdate_T<?php echo $currentform[0]->style; ?><?php if($iswidget){echo "_widget";} ?>"> - <?php echo $datestring; ?><?php echo $verifiedstarhtmlarray[1]; ?></span><?php echo $rdmorescroll[1]; ?><?php echo $ownerresponse; ?>
				</div>
				<?php echo $media; ?>
				<?php echo $miscpichtml; ?>
				<?php echo $sourcepagenamehtml; ?>
				<?php echo $logo; ?>
			</div>
			<?php echo $readmorepopup; ?>
		</div>
	<?php
		}
		//fix for slickslider more than one row loading on ajax
			if($ajaxsliload == true && $currentform[0]->createslider == "sli" && $sliusingfilter==false){
				echo "</div></div>";
				if($nrows>1){
					if($closediv){
						echo "</div>";
					}
				}
				
			}
	$revcount++;
	}
	//end loop
	if(	$currentform[0]->display_masonry!="yes" && $currentform[0]->createslider != "sli"){
	?>
	</div>
<?php
	}
}


?>
