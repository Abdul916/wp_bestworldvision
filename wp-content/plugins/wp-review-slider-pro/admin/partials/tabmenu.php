<?php
$urltrimmedtab = remove_query_arg( array('page', 'forcerecal','_wpnonce', 'rtype', 'taction', 'tid', 'sortby', 'sortdir', 'opt','settings-updated','revfilter','vfid','rt') );

$urlget['welcome'] = esc_url( add_query_arg( 'page', 'wp_pro-welcome',$urltrimmedtab ) );
$urlget['getrevs'] = esc_url( add_query_arg( 'page', 'wp_pro-getrevs',$urltrimmedtab ) );
$urlget['review_funnel'] = esc_url( add_query_arg( 'page', 'wp_pro-reviewfunnel',$urltrimmedtab ) );
$urlget['googlesettings'] = esc_url( add_query_arg( 'page', 'wp_pro-googlesettings',$urltrimmedtab ) );
$urlget['reviews'] = esc_url( add_query_arg( 'page', 'wp_pro-reviews',$urltrimmedtab ) );
$urlget['templates_posts'] = esc_url( add_query_arg( 'page', 'wp_pro-templates_posts',$urltrimmedtab ) );
$urlget['badges'] = esc_url( add_query_arg( 'page', 'wp_pro-badges',$urltrimmedtab ) );
$urlget['forms'] = esc_url( add_query_arg( 'page', 'wp_pro-forms',$urltrimmedtab ) );
$urlget['float'] = esc_url( add_query_arg( 'page', 'wp_pro-float',$urltrimmedtab ) );
$urlget['analytics'] = esc_url( add_query_arg( 'page', 'wp_pro-analytics',$urltrimmedtab ) );
$urlget['get_yelp'] = esc_url( add_query_arg( 'page', 'wp_pro-get_yelp',$urltrimmedtab ) );
$urlget['get_tripadvisor'] = esc_url( add_query_arg( 'page', 'wp_pro-get_tripadvisor',$urltrimmedtab ) );
$urlget['get_twitter'] = esc_url( add_query_arg( 'page', 'wp_pro-get_twitter',$urltrimmedtab ) );
$urlget['forum'] = esc_url( add_query_arg( 'page', 'wp_pro-forum',$urltrimmedtab ) );
$urlget['notifications'] = esc_url( add_query_arg( 'page', 'wp_pro-notifications',$urltrimmedtab ) );
$urlget['getrevs-contact'] = esc_url( add_query_arg( 'page', 'wp_pro-getrevs-contact',$urltrimmedtab ) );
$urlget['welcome-contact'] = esc_url( add_query_arg( 'page', 'wp_pro-welcome-contact',$urltrimmedtab ) );
//$urlget['get_airbnb'] = esc_url( add_query_arg( 'page', 'wp_pro-get_airbnb',$urltrimmedtab ) );
$urlget['get_vrbo'] = esc_url( add_query_arg( 'page', 'wp_pro-get_vrbo',$urltrimmedtab ) );
$urlget['get_woo'] = esc_url( add_query_arg( 'page', 'wp_pro-get_woo',$urltrimmedtab ) );
$urlget['reviewfunnel'] = esc_url( add_query_arg( 'page', 'wp_pro-reviewfunnel',$urltrimmedtab ) );
$urlget['get_apps'] = esc_url( add_query_arg( array(
    'page' => 'wp_pro-get_apps',
    'rtype' => 'iTunes',
),$urltrimmedtab ) );
$urlget['get_apps_gyg'] = esc_url( add_query_arg( array(
    'page' => 'wp_pro-get_apps',
    'rtype' => 'GetYourGuide',
),$urltrimmedtab ) );
$urlget['get_apps_hw'] = esc_url( add_query_arg( array(
    'page' => 'wp_pro-get_apps',
    'rtype' => 'Hostelworld',
),$urltrimmedtab ) );
$urlget['get_apps_hcp'] = esc_url( add_query_arg( array(
    'page' => 'wp_pro-get_apps',
    'rtype' => 'HousecallPro',
),$urltrimmedtab ) );
$urlget['get_apps_nd'] = esc_url( add_query_arg( array(
    'page' => 'wp_pro-get_apps',
    'rtype' => 'Nextdoor',
),$urltrimmedtab ) );
$urlget['get_apps_fr'] = esc_url( add_query_arg( array(
    'page' => 'wp_pro-get_apps',
    'rtype' => 'Freemius',
),$urltrimmedtab ) );
$urlget['get_apps_qu'] = esc_url( add_query_arg( array(
    'page' => 'wp_pro-get_apps',
    'rtype' => 'Qualitelis',
),$urltrimmedtab ) );
$urlget['get_apps_zillow'] = esc_url( add_query_arg( array(
    'page' => 'wp_pro-get_apps',
    'rtype' => 'Zillow',
),$urltrimmedtab ) );
$urlget['get_apps_angieslist'] = esc_url( add_query_arg( array(
    'page' => 'wp_pro-get_apps',
    'rtype' => 'AngiesList',
),$urltrimmedtab ) );
$urlget['get_apps_feefo'] = esc_url( add_query_arg( array(
    'page' => 'wp_pro-get_apps',
    'rtype' => 'Feefo',
),$urltrimmedtab ) );
$urlget['get_apps_facebook'] = esc_url( add_query_arg( array(
    'page' => 'wp_pro-get_apps',
    'rtype' => 'Facebook',
),$urltrimmedtab ) );
$urlget['get_apps_gcrawl'] = esc_url( add_query_arg( array(
    'page' => 'wp_pro-get_apps',
    'rtype' => 'Google',
),$urltrimmedtab ) );
$urlget['get_apps_feedbackcompany'] = esc_url( add_query_arg( array(
    'page' => 'wp_pro-get_apps',
    'rtype' => 'FeedbackCompany',
),$urltrimmedtab ) );
$urlget['get_apps_truelocal'] = esc_url( add_query_arg( array(
    'page' => 'wp_pro-get_apps',
    'rtype' => 'TrueLocal',
),$urltrimmedtab ) );
$urlget['get_apps_experience'] = esc_url( add_query_arg( array(
    'page' => 'wp_pro-get_apps',
    'rtype' => 'Experience',
),$urltrimmedtab ) );
$urlget['get_apps_styleseat'] = esc_url( add_query_arg( array(
    'page' => 'wp_pro-get_apps',
    'rtype' => 'StyleSeat',
),$urltrimmedtab ) );
$urlget['get_apps_sourceforge'] = esc_url( add_query_arg( array(
    'page' => 'wp_pro-get_apps',
    'rtype' => 'SourceForge',
),$urltrimmedtab ) );
$urlget['get_apps_wordpress'] = esc_url( add_query_arg( array(
    'page' => 'wp_pro-get_apps',
    'rtype' => 'WordPress',
),$urltrimmedtab ) );
$urlget['get_apps_reviewsio'] = esc_url( add_query_arg( array(
    'page' => 'wp_pro-get_apps',
    'rtype' => 'Reviews.io',
),$urltrimmedtab ) );
$urlget['get_apps_guildquality'] = esc_url( add_query_arg( array(
    'page' => 'wp_pro-get_apps',
    'rtype' => 'GuildQuality',
),$urltrimmedtab ) );
$urlget['get_apps_tripadvisor'] = esc_url( add_query_arg( array(
    'page' => 'wp_pro-get_apps',
    'rtype' => 'TripAdvisor',
),$urltrimmedtab ) );
$urlget['get_apps_airbnb'] = esc_url( add_query_arg( array(
    'page' => 'wp_pro-get_apps',
    'rtype' => 'Airbnb',
),$urltrimmedtab ) );
$urlget['get_apps_vrbo'] = esc_url( add_query_arg( array(
    'page' => 'wp_pro-get_apps',
    'rtype' => 'VRBO',
),$urltrimmedtab ) );
$urlget['get_apps_yelp'] = esc_url( add_query_arg( array(
    'page' => 'wp_pro-get_apps',
    'rtype' => 'Yelp',
),$urltrimmedtab ) );
$urlget['get_apps_birdeye'] = esc_url( add_query_arg( array(
    'page' => 'wp_pro-get_apps',
    'rtype' => 'Birdeye',
),$urltrimmedtab ) );
$urlget['get_apps_yotpo'] = esc_url( add_query_arg( array(
    'page' => 'wp_pro-get_apps',
    'rtype' => 'Yotpo',
),$urltrimmedtab ) );
$urlget['get_apps_googleplacesapi'] = esc_url( add_query_arg( array(
    'page' => 'wp_pro-get_apps',
    'rtype' => 'Google-Places-API',
),$urltrimmedtab ) );




if (current_user_can('manage_options')) {

?>
<h2 class="nav-tab-wrapper">

	<a href="<?php echo $urlget['welcome']; ?>" class="nav-tab <?php if($_GET['page']=='wp_pro-welcome'){echo 'nav-tab-active';} ?>"><?php _e('Dashboard', 'wp-review-slider-pro'); ?></a>
	<a href="<?php echo $urlget['getrevs']; ?>" class="getrevshiddenmenu nav-tab <?php if($_GET['page']=='wp_pro-getrevs'){echo 'nav-tab-active';} ?>"><?php _e('Get Reviews', 'wp-review-slider-pro'); ?></a>

	<a href="<?php echo $urlget['review_funnel']; ?>" class="nav-tab <?php if($_GET['page']=='wp_pro-reviewfunnel'){echo 'nav-tab-active';} ?>"><?php _e('Review Funnels', 'wp-review-slider-pro'); ?></a>

	<a href="<?php echo $urlget['reviews']; ?>" class="nav-tab <?php if($_GET['page']=='wp_pro-reviews'){echo 'nav-tab-active';} ?>"><?php _e('Review List', 'wp-review-slider-pro'); ?></a>

	<a href="<?php echo $urlget['templates_posts']; ?>" class="nav-tab <?php if($_GET['page']=='wp_pro-templates_posts'){echo 'nav-tab-active';} ?>"><?php _e('Templates', 'wp-review-slider-pro'); ?></a>
	<a href="<?php echo $urlget['badges']; ?>" class="nav-tab <?php if($_GET['page']=='wp_pro-badges'){echo 'nav-tab-active';} ?>"><?php _e('Badges', 'wp-review-slider-pro'); ?></a>
	<a href="<?php echo $urlget['forms']; ?>" class="nav-tab <?php if($_GET['page']=='wp_pro-forms'){echo 'nav-tab-active';} ?>"><?php _e('Forms', 'wp-review-slider-pro'); ?></a>
	<a href="<?php echo $urlget['float']; ?>" class="nav-tab <?php if($_GET['page']=='wp_pro-float'){echo 'nav-tab-active';} ?>"><?php _e('Floats', 'wp-review-slider-pro'); ?></a>

	<a href="<?php echo $urlget['analytics']; ?>" class="nav-tab <?php if($_GET['page']=='wp_pro-analytics'){echo 'nav-tab-active';} ?>"><?php _e('Analytics', 'wp-review-slider-pro'); ?></a>

	<a href="<?php echo $urlget['notifications']; ?>" class="nav-tab <?php if($_GET['page']=='wp_pro-notifications'){echo 'nav-tab-active';} ?>"><?php _e('Tools', 'wp-review-slider-pro'); ?></a>

	</h2>
<?php
}
?>
	
<div class="getrevshiddenmenu">

</div>