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

//loop if more than one row
for ($x = 0; $x < count($rowarray); $x++) {
	//loop 
	foreach ( $rowarray[$x] as $review ) 
	{

		//get userpic, functions in the class-wp-review-slider-pro-public.php file
		//$imagecachedir = plugin_dir_path( __FILE__ ).'cache/';
		$userpic = $templateclass->wprevpro_get_user_pic($review,'142','142',$currentform[0]);

		if(!isset($template_misc_array['default_avatar'])){
			$template_misc_array['default_avatar']='none';
		}
		//if the review does not have a image we hide it or show mystery depending on setting
		if($userpic==""){
			//use setting to determine default mystery man if set
			if($template_misc_array['default_avatar']=='fb'){
				$userpic = $imgs_url.'fb_mystery_man_small.png';
			} else if($template_misc_array['default_avatar']=='trip'){
				$userpic = $imgs_url.'trip_mystery_man_small.png';
			} else if($template_misc_array['default_avatar']=='google'){
				$userpic = $imgs_url.'google_mystery_man_small.png';
			} else if($template_misc_array['default_avatar']=='yelp'){
				$userpic = $imgs_url.'yelp_mystery_man_small.png';
			} else if($template_misc_array['default_avatar']=='airbnb'){
				$userpic = $imgs_url.'airbnb_mystery_man_small.png';
			} else {
				$userpic = $imgs_url.'trip_mystery_man_small.png';
			}
		}
		if($userpic==""){
			$userpichtml = '';
		} else {
			$userpichtml = '<img src="'.$userpic.'" alt="'.$tempreviewername.' Avatar" class="wprevpro_t1_IMG_4 wprevpro_avatarimg avartnavimg" />';
			if(isset($template_misc_array['avataropt'])){
				if($template_misc_array['avataropt']=='hide'){
					$userpichtml = '';
				} else if($template_misc_array['avataropt']=='mystery'){
					if($review->type=="Yelp"){
						$tempuserpic = $imgs_url.'yelp_mystery_man_small.png';
					} else if ($review->type=="TripAdvisor"){
						$tempuserpic = $imgs_url.'trip_mystery_man_small.png';
					} else if ($review->type=="Google"){
						$tempuserpic = $imgs_url.'google_mystery_man_small.png';
					} else if ($review->type=="Airbnb"){
						$tempuserpic = $imgs_url.'airbnb_mystery_man_small.png';
					} else {
						$tempuserpic = $imgs_url.'fb_mystery_man_small.png';
						//use setting to determine default mystery man if set
						if($template_misc_array['default_avatar']=='trip'){
							$tempuserpic = $imgs_url.'trip_mystery_man_small.png';
						} else if($template_misc_array['default_avatar']=='google'){
							$tempuserpic = $imgs_url.'google_mystery_man_small.png';
						} else if($template_misc_array['default_avatar']=='yelp'){
							$tempuserpic = $imgs_url.'yelp_mystery_man_small.png';
						} else if($template_misc_array['default_avatar']=='airbnb'){
							$tempuserpic = $imgs_url.'airbnb_mystery_man_small.png';
						}
					}
					$userpichtml = '<img src="'.$tempuserpic.'" alt="'.$tempreviewername.' Avatar" class="wprevpro_t1_IMG_4 wprevpro_avatarimg avartnavimg" />';
				}
			}
		}

	?>
	<div class="avataronlyrevdiv">
		<div class="avatarnavdiv">
			<?php echo $userpichtml; ?>
		</div>
	</div>
	<?php
	
	}
	//end loop

}

?>
