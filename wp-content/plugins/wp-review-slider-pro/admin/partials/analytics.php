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
	if (!current_user_can('edit_pages') && $this->wprev_canuserseepage('analytics')==false) {
        return;
    }

	//check to see if reviews are in database
	//total number of rows
	global $wpdb;
	$reviews_table_name = $wpdb->prefix . 'wpfb_reviews';
	$reviewtotalcount = $wpdb->get_var( 'SELECT COUNT(*) FROM '.$reviews_table_name );
	$dbmsg ='';
	if($reviewtotalcount<1){
		$dbmsg = $dbmsg . '<div id="setting-error-wprevpro_message" class="updated settings-error notice is-dismissible">'.__('<p><strong>No reviews found. Please visit the Get Reviews page or manually add one on the <a href="?page=wp_pro-reviews">Review List</a> page. </strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>', 'wp-review-slider-pro').'</div>';
	}

?>

<div class="wrap wp_pro-settings" id="">
	<h1><img src="<?php echo plugin_dir_url( __FILE__ ) . 'logo.png'; ?>"></h1>
	
<?php 
include("tabmenu.php");
//echo "<div></br>More charts coming soon!</br></div>";
if ( wrsp_fs()->can_use_premium_code() ) {
	
//get all current types used
global $wpdb;
$reviews_table_name = $wpdb->prefix . 'wpfb_reviews';
$tempquery = "select type from ".$reviews_table_name." group by type";
$typearray = $wpdb->get_col($tempquery);

//get current locations
$tempquery = "select * from ".$reviews_table_name." group by pageid";
$fbpagesrows = $wpdb->get_results($tempquery);

?>
<div class="w3-row">
	<div class="w3-col m3 boxouter">
		<select id="location_multiple_select" class="js-example-basic-multiple" name="wprevlocations[]" multiple="multiple" style="width: 100%">
		<?php
		foreach ( $fbpagesrows as $fbpage ) 
		{
			echo '<option value="'.$fbpage->pageid.'" >'.$fbpage->pagename.' ('.$fbpage->type.')</option>';
		}
		?>
		</select>
	</div>
	<div class="w3-col m3 boxouter">
		<select id="type_multiple_select" class="js-example-basic-multiple" name="wprevtypes[]" multiple="multiple" style="width: 100%">
		<?php
		for($x=0;$x<count($typearray);$x++)
		{
			$typelowercase = strtolower($typearray[$x]);
			echo '<option value="'.$typelowercase.'" >'.$typearray[$x].'</option>';
		}
		?>
		</select>
	</div>
	<div class="w3-col m3 boxouter">
		<input id="wprevpro_analytics_filter_string" type="text" name="wprevpro_analytics_filter_string" placeholder="Enter Search Text">
	</div>
	<div class="w3-col m3 boxouter">
		<div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #e5e5e5; width: 100%">
			<i class="fa fa-calendar"></i>&nbsp;
			<span></span> <i class="fa fa-caret-down"></i>
		</div>
	</div>
</div>
<div class="w3-row">
	<div class="w3-col m3 boxouter">
		<div class="boxcontent">
			<div id="avg_rating" class="wppro_smallheader w3-center"><?php _e('Average Rating:', 'wp-review-slider-pro'); ?>  <span id="avg_rating_num"></span> <span class="svgicons svg-wprsp-star w3-text-gold"></span></div>
			<div class="w3-row">
				<div class="w3-col s1">
				&nbsp;
				</div>
				<div class="w3-col s7 starrowdivs">
					<div class="starrow wprevpro_star_imgs"><span class="svgicons svg-wprsp-star"></span><span class="svgicons svg-wprsp-star"></span><span class="svgicons svg-wprsp-star"></span><span class="svgicons svg-wprsp-star"></span><span class="svgicons svg-wprsp-star"></span></div>
					<div class="starrow wprevpro_star_imgs"><span class="svgicons svg-wprsp-star"></span><span class="svgicons svg-wprsp-star"></span><span class="svgicons svg-wprsp-star"></span><span class="svgicons svg-wprsp-star"></span></div>
					<div class="starrow wprevpro_star_imgs"><span class="svgicons svg-wprsp-star"></span><span class="svgicons svg-wprsp-star"></span><span class="svgicons svg-wprsp-star"></span></div>
					<div class="starrow wprevpro_star_imgs"><span class="svgicons svg-wprsp-star"></span><span class="svgicons svg-wprsp-star"></span></div>
					<div class="starrow wprevpro_star_imgs"><span class="svgicons svg-wprsp-star"></span></div>
				</div>
				<div class="w3-col s3 w3-right-align">
					<div id="num_stars_5" class="wppro_smallheader">0</div>
					<div id="num_stars_4" class="wppro_smallheader">0</div>
					<div id="num_stars_3" class="wppro_smallheader">0</div>
					<div id="num_stars_2" class="wppro_smallheader">0</div>
					<div id="num_stars_1" class="wppro_smallheader">0</div>
				</div>
				<div class="w3-col s1">
				&nbsp;
				</div>
			</div>
		</div>
		<div class="boxcontent">
			<div id="revtypebox" class="w3-row wppro_smallheader">
				<div class="w3-col s7" id="temphtml1">
				</div>
				<div class="w3-col s5" id="temphtml2">
				</div>
			</div>
		</div>
	</div>
	<div class="w3-col m9 boxouter w3-center">
		<div class="boxcontent w3-center">
			<div id='overallChartspinner' class="loadingspinner"></div>
			<canvas id="overallChart" width="400" height="200"></canvas>
		</div>
	</div>
</div>


<div class="w3-row">
	<div class="w3-col m6 boxouter">
		<div class="boxcontent wordclouddivouter">
		<div class="wppro_smallheader w3-center"><?php _e('Positive Word Cloud', 'wp-review-slider-pro'); ?></div>
		<div id="positive_word_cloud" class="wordclouddiv"></div>
		</div>
	</div>
	<div class="w3-col m6 boxouter">
		<div class="boxcontent wordclouddivouter">
		<div class="wppro_smallheader w3-center"><?php _e('Negative Word Cloud', 'wp-review-slider-pro'); ?></div>
		<div id="negative_word_cloud" class="wordclouddiv"></div>
		</div>
	</div>
</div>
<div class="w3-row">
	<div class="w3-col m12 boxouter w3-center">
		<div class="boxcontent w3-center">
			<div id='distroChartspinner' class="loadingspinner"></div>
			<canvas id="ratingdistrochart"></canvas>
		</div>
	</div>
</div>
<p><?php _e('More charts coming soon!', 'wp-review-slider-pro'); ?></p>
<?php
} else {
	echo '<div class="wprevpro_margin10">';
	_e('Analytics are a Premium feature. Please upgrade.', 'wp-review-slider-pro');
	echo '</div>';
}
 
?>

	<div id="tb_content_popup" style="display:none;">
		<div id="review_details">
			<div class="wpproslider_t6_DIV_1 w3_wprs-col l12"="">
				<div class="wpproslider_t6_DIV_2 wprev_preview_bg1 wprev_preview_bradius" style="border: 1px solid rgb(238, 238, 238); border-radius: 0px; background: rgb(253, 253, 253);">
						<div class="wpproslider_t6_STRONG_5 wprev_preview_tcolor2">
							<?php _e('Review Source Details', 'wp-review-slider-pro'); ?>
						</div>
					<div class="wpproslider_t6_DIV_4 sourcerevdetails"></div>
				</div>
			</div>
			<div class="wpproslider_t6_DIV_1 w3_wprs-col l12"="">
				<div class="wpproslider_t6_DIV_2 wprev_preview_bg1 wprev_preview_bradius" style="border: 1px solid rgb(238, 238, 238); border-radius: 0px; background: rgb(253, 253, 253);">
				<div class="wpproslider_t6_DIV_2_top" style="line-height:24px;">
					<div class="wpproslider_t6_DIV_3L"><a id="from_url_review" target="_blank">
					<img src="<?php echo plugin_dir_url( __FILE__ ); ?>tripadvisor_mystery_man.png" class="wprev_avatar_opt wpproslider_t6_IMG_2">
					</a>
					</div>
					<div class="wpproslider_t6_DIV_3">
						<div class="wpproslider_t6_STRONG_5 wprev_preview_tcolor2 t6displayname">
							<span id="wprev_showname"><?php _e('John Smith', 'wp-review-slider-pro'); ?></span>
						</div>
						<div class="wpproslider_t6_star_DIV">
							<span id="starloc1" class="wprevpro_star_imgs" style="color: rgb(253, 211, 20);">
								<span class="svgicons svg-wprsp-star"></span><span class="svgicons svg-wprsp-star"></span><span class="svgicons svg-wprsp-star"></span><span class="svgicons svg-wprsp-star"></span><span class="svgicons svg-wprsp-star-o"></span>
							</span>
						</div>
						<div class="wpproslider_t6_SPAN_6 wprev_preview_tcolor2 t6datediv" style="color: rgb(85, 85, 85);">
							<span id="wprev_showdate">1/12/2017</span>
						</div>
					</div>
				</div>
				<div class="wpproslider_t6_DIV_4">
					<p class="wpproslider_t6_P_4 wprev_preview_tcolor1" style="color: rgb(85, 85, 85);"><?php _e('This is a sample review. Hands down the best experience we have had in the southeast! Awesome accommodations, great staff. We will gladly drive four hours for this gem!', 'wp-review-slider-pro'); ?></p>
				</div>
				<div class="wpproslider_t6_DIV_3_logo">
					<a id="from_url" href="" target="_blank"><img src="" alt="" class="wprevpro_t6_site_logo siteicon"></a>
				</div>
				</div>
			</div>
		</div>
		<div id="review_list" style="display:none;">
			<table class="wp-list-table widefat striped posts">
			<thead>
				<tr>
					<th scope="col" width="80px" sortdir="DESC" sorttype="name" class="wprevpro_tablesort manage-column"> <?php _e('Name', 'wp-review-slider-pro'); ?></th>
					<th scope="col" width="70px" sortdir="DESC" sorttype="rating" class="wprevpro_tablesort manage-column"><?php _e('Rating', 'wp-review-slider-pro'); ?></th>
					<th scope="col" class="manage-column"><?php _e('Review Title/Text', 'wp-review-slider-pro'); ?></th>
					<th scope="col" width="75px" sortdir="DESC" sorttype="stime" class="wprevpro_tablesort manage-column"><?php _e('Date', 'wp-review-slider-pro'); ?></th>
					<th scope="col" width="100px" sortdir="DESC" sorttype="stext" class="wprevpro_tablesort manage-column" ><?php _e('Words/Char', 'wp-review-slider-pro'); ?></th>
					<th scope="col" width="100px" sortdir="DESC" sorttype="pagename" class="wprevpro_tablesort manage-column"><?php _e('Social Page', 'wp-review-slider-pro'); ?></th>
				</tr>
			</thead>
			<tbody id="review_list_body">
			
			</tbody>
			</table>
		</div>
	</div>
	
</div>
</br></br></br></br></br></br></br>