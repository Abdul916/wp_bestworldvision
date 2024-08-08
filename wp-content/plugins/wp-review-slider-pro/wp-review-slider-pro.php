<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://ljapps.com
 * @since             1.0
 * @package           WP_Review_Slider_Pro
 *
 * @wordpress-plugin
 * Plugin Name: WP Review Slider Pro (Premium)
 * Plugin URI:        https://wpreviewslider.com/
 * Description:       Pro Version - Allows you to easily display your Facebook Page, Yelp, Google, Manually Input, and 80+ other site reviews in your Posts, Pages, and Widget areas.
 * Version:           12.1.5
 * Update URI: https://api.freemius.com
 * Author:            LJ Apps
 * Author URI:        http://ljapps.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-review-slider-pro
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}
//--------------------
//constants for plugin version and token
define( 'WPREVPRO_PLUGIN_VERSION', '12.1.5' );
define( 'WPREVPRO_PLUGIN_TOKEN', 'wp-review-slider-pro' );
//define plugin location constant ex: /home/94285.cloudwaysapps.com/fzamfatyjq/public_html/wp-content/plugins/wp-review-slider-pro-premium/
define( 'WPREV_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WPREV_PLUGIN_URL', plugins_url( '', __FILE__ ) );
//$wprevpro_badge_slidepop = array();
//freemius integration
function wrsp_fs() {
    global $wrsp_fs;
    if ( !isset( $wrsp_fs ) ) {
        // Include Freemius SDK.
        require_once dirname( __FILE__ ) . '/freemius/start.php';
        $wrsp_fs = fs_dynamic_init( array(
            'id'               => '646',
            'premium_slug'     => 'wp-review-slider-pro',
            'slug'             => 'wp-review-slider-pro',
            'type'             => 'plugin',
            'public_key'       => 'pk_118102a96ccea6cd5fab38e72dc0f',
            'is_premium'       => true,
            'premium_suffix'   => '',
            'has_addons'       => false,
            'has_paid_plans'   => true,
            'is_org_compliant' => false,
            'trial'            => array(
                'days'               => 7,
                'is_require_payment' => true,
            ),
            'has_affiliation'  => 'selected',
            'menu'             => array(
                'slug'        => 'wp_pro-welcome',
                'support'     => false,
                'affiliation' => true,
                'contact'     => true,
            ),
            'is_live'          => true,
        ) );
    }
    return $wrsp_fs;
}

// Init Freemius.
//wrsp_fs();
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-review-slider-pro-activator.php
 */
function activate_WP_Review_Pro(  $networkwide  ) {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-review-slider-pro-activator.php';
    WP_Review_Pro_Activator::activate_all( $networkwide );
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-review-slider-pro-deactivator.php
 */
function deactivate_WP_Review_Pro() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-review-slider-pro-deactivator.php';
    WP_Review_Pro_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_WP_Review_Pro' );
register_deactivation_hook( __FILE__, 'deactivate_WP_Review_Pro' );
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-review-slider-pro.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_WP_Review_Pro() {
    //define array of svg icons, so we can slowly transition to svg. If review type is in this then svg exists.
    $svgarray = array(
        "Manual",
        "Submitted",
        "Agoda",
        "Airbnb",
        "AliExpress",
        "AlternativeTo",
        "Amazon",
        "AngiesList",
        "Apartmentratings",
        "Apartments",
        "AppleAppstore",
        "Avvo",
        "Baidu",
        "BBB",
        "Bestbuy",
        "Bilbayt",
        "Birdeye",
        "Bol",
        "Bookatable",
        "Booking",
        "Capterra",
        "CarGurus",
        "Cars",
        "Carvana",
        "Citysearch",
        "ClassPass",
        "ConsumerAffairs",
        "CreativeMarket",
        "CreditKarma",
        "CustomerLobby",
        "Drizly",
        "Facebook",
        "Flipkart",
        "Fresha",
        "Goodreads",
        "Google",
        "Holidaycheck",
        "Kayak",
        "Priceline",
        "Qunar",
        "Reviews.io",
        "Smythstoys",
        "SocialClimb",
        "Steam",
        "Target",
        "Travelocity",
        "Trip",
        "TripAdvisor",
        "Yelp"
    );
    $svgarrayserial = serialize( $svgarray );
    define( 'WPREV_SVG_ARRAY', $svgarrayserial );
    //define type array that we can loop and display through plugin
    $typearray = array(
        "Manual",
        "Submitted",
        "Airbnb",
        "AngiesList",
        "Birdeye",
        "CreativeMarket",
        "Experience",
        "Facebook",
        "FeedbackCompany",
        "Feefo",
        "Freemius",
        "Fresha",
        "GetYourGuide",
        "Google",
        "GuildQuality",
        "Hostelworld",
        "HousecallPro",
        "iTunes",
        "Nextdoor",
        "Qualitelis",
        "Realtor",
        "Reviews.io",
        "SocialClimb",
        "SourceForge",
        "StyleSeat",
        "TripAdvisor",
        "TrueLocal",
        "Twitter",
        "VRBO",
        "WooCommerce",
        "WordPress",
        "Yelp",
        "Yotpo",
        "Zillow"
    );
    $typearrayserial = serialize( $typearray );
    define( 'WPREV_TYPE_ARRAY', $typearrayserial );
    //review funnel type array
    $typearrayrf = array(
        "Agoda",
        "Airbnb",
        "AliExpress",
        "AlternativeTo",
        "Amazon",
        "AngiesList",
        "Apartmentratings",
        "Apartments",
        "AppleAppstore",
        "Avvo",
        "Baidu",
        "Bestbuy",
        "BBB",
        "Bilbayt",
        "Bol",
        "Booking",
        "Capterra",
        "CarGurus",
        "Cars",
        "Carvana",
        "Citysearch",
        "ClassPass",
        "ConsumerAffairs",
        "CreditKarma",
        "CustomerLobby",
        "DealerRater",
        "Deliveroo",
        "Drizly",
        "Ebay",
        "Edmunds",
        "Etsy",
        "Expedia",
        "Facebook",
        "FindLaw",
        "Flipkart",
        "Foursquare",
        "G2Crowd",
        "Gartner",
        "Glassdoor",
        "Goodreads",
        "Google",
        "GooglePlay",
        "GoogleShopping",
        "GreatSchools",
        "Healthgrades",
        "Holidaycheck",
        "HomeAdvisor",
        "HomeAway",
        "Homestars",
        "Hotels",
        "Houzz",
        "HungerStation",
        "Indeed",
        "Influenster",
        "InsiderPages",
        "ITCentralStation",
        "Jet",
        "Kayak",
        "Lawyers",
        "LendingTree",
        "Martindale",
        "Niche",
        "Orbitz",
        "OpenRice",
        "Opentable",
        "Priceline",
        "ProductHunt",
        "ProductReview",
        "Qunar",
        "RateMDs",
        "Realself",
        "Reviews.io",
        "Sitejabber",
        "Smythstoys",
        "SoftwareAdvice",
        "Steam",
        "Talabat",
        "Target",
        "TheFork",
        "TheKnot",
        "Thumbtack",
        "Travelocity",
        "Trip",
        "TripAdvisor",
        "TrustedShops",
        "Trustpilot",
        "TrustRadius",
        "Vitals",
        "VRBO",
        "Walmart",
        "WebMD",
        "WeddingWire",
        "Yell",
        "YellowPages",
        "Yelp",
        "Zillow",
        "ZocDoc",
        "Zomato"
    );
    $typearrayserialrf = serialize( $typearrayrf );
    define( 'WPREV_TYPE_ARRAY_RF', $typearrayserialrf );
    //define array of icon sizes to use in templates and badge.
    $allsourcesarray = array_unique( array_merge( $typearray, $typearrayrf ), SORT_REGULAR );
    foreach ( $allsourcesarray as $sourcetype ) {
        // echo "$sourcetype <br>";
        $sourcetypelower = strtolower( $sourcetype );
        $sizearray[$sourcetypelower] = wppro_get_logo_size( $sourcetype );
    }
    $sizearrayserial = serialize( $sizearray );
    define( 'WPREV_ICONSIZE_ARRAY', $sizearrayserial );
    // Init Freemius.
    $wrspfs = wrsp_fs();
    //echo $wrspfs->get_ajax_action( 'activate_license' );
    //$license = $wrspfs->_get_license();
    $user = $wrspfs->get_user();
    $site = $wrspfs->get_site();
    $license = $wrspfs->_get_license();
    //print_r($site);
    if ( is_admin() ) {
        //for passing to funnel.ljapps.com to check license and number of calls
        //define( 'WPREV_FR_SITEID', $site->license_id );
        //define( 'WPREV_FR_URL', $site->url );
        if ( isset( $site->license_id ) ) {
            update_option( 'wprev_fr_siteid', $site->license_id );
        }
        if ( isset( $site->url ) ) {
            update_option( 'wprev_fr_url', $site->url );
        }
        if ( isset( $site->id ) ) {
            update_option( 'wprev_fr_id', $site->id );
        }
    }
    // Signal that SDK was initiated.
    do_action( 'wrsp_fs_loaded' );
    //register unistall hook for freemius
    // Not like register_uninstall_hook(), you do NOT have to use a static function.
    wrsp_fs()->add_action( 'after_uninstall', 'wrsp_uninstall_cleanup' );
    //custom icon
    wrsp_fs()->add_filter( 'plugin_icon', 'wrsp_fs_custom_icon' );
    $plugin = new WP_Review_Pro();
    $plugin->run();
}

//add link to change log on plugins menu
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wprevpro_action_links' );
function wprevpro_action_links(  $links  ) {
    $links[] = '<a href="https://wpreviewslider.userecho.com/knowledge-bases/2/articles/88-change-log" target="_blank">Change Log</a>';
    return $links;
}

function wrsp_fs_custom_icon() {
    return dirname( __FILE__ ) . '/admin/partials/logo_star.png';
}

//used to remove directories on uninstall
function wpprorev_rmrf(  $dir  ) {
    foreach ( glob( $dir ) as $file ) {
        if ( is_dir( $file ) ) {
            wpprorev_rmrf( "{$file}/*" );
            rmdir( $file );
        } else {
            unlink( $file );
        }
    }
}

function wrsp_uninstall_cleanup() {
    // Leave no trail
    $option1 = 'widget_wprevpro_widget';
    $option2 = 'wp-review-slider-pro_version';
    $option3 = 'wprevpro_options';
    $option4 = 'wprevpro_fb_app_id';
    $option5 = 'wprevpro_hidden_reviews';
    $option6 = 'wprevpro_cookieval';
    $option7 = 'wprevpro_zillowid';
    $option8 = 'wprev_google_crawl_check';
    $option9 = 'wprevpro_birdeyeapikey_val';
    $option10 = 'wprevpro_yotposecretkey_val';
    $option11 = 'wprevpro_yotpousertoken';
    $option12 = 'wprevpro_fb_secret_code';
    $option13 = 'wprevpro_googleplacesapikey_val';
    $option14 = 'wprev_hideondownload';
    $option15 = 'wprev_languagetranslator';
    $option16 = 'wprev_cssposts';
    $option17 = 'wprev_jscssposts';
    $option18 = 'wprev_rolepages';
    $option19 = 'wprev_googleprodratingxml';
    $option20 = 'wprev_loopcron';
    if ( !is_multisite() ) {
        delete_option( $option1 );
        delete_option( $option2 );
        delete_option( $option3 );
        delete_option( $option4 );
        delete_option( $option5 );
        delete_option( $option6 );
        delete_option( $option7 );
        delete_option( $option8 );
        delete_option( $option9 );
        delete_option( $option10 );
        delete_option( $option11 );
        delete_option( $option12 );
        delete_option( $option13 );
        delete_option( $option14 );
        delete_option( $option15 );
        delete_option( $option16 );
        delete_option( $option17 );
        delete_option( $option18 );
        delete_option( $option19 );
        delete_option( $option20 );
        //delete review table in database
        global $wpdb;
        $table_name = $wpdb->prefix . 'wpfb_reviews';
        $wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
        //drop review template table
        $table_name = $wpdb->prefix . 'wpfb_post_templates';
        $wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
        //drop review wpfb_badges table
        $table_name = $wpdb->prefix . 'wpfb_badges';
        $wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
        //drop review wpfb_forms table
        $table_name = $wpdb->prefix . 'wpfb_forms';
        $wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
        //drop review wpfb_floats table
        $table_name = $wpdb->prefix . 'wpfb_floats';
        $wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
        //drop review wpfb_reviewfunnel table
        $table_name = $wpdb->prefix . 'wpfb_reviewfunnel';
        $wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
        //drop review wpfb_getapps_forms table
        $table_name = $wpdb->prefix . 'wpfb_getapps_forms';
        $wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
        //drop review wpfb_nofitifcation_forms table
        $table_name = $wpdb->prefix . 'wpfb_nofitifcation_forms';
        $wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
        //drop review wpfb_gettwitter_forms table
        $table_name = $wpdb->prefix . 'wpfb_gettwitter_forms';
        $wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
        //drop review wpfb_total_averages table
        $table_name = $wpdb->prefix . 'wpfb_total_averages';
        $wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
    } else {
        global $wpdb;
        $blog_ids = $wpdb->get_col( "SELECT blog_id FROM {$wpdb->blogs}" );
        $original_blog_id = get_current_blog_id();
        foreach ( $blog_ids as $blog_id ) {
            switch_to_blog( $blog_id );
            delete_option( $option1 );
            delete_option( $option2 );
            delete_option( $option3 );
            delete_option( $option4 );
            delete_option( $option5 );
            delete_option( $option6 );
            delete_option( $option7 );
            delete_option( $option8 );
            delete_option( $option9 );
            delete_option( $option10 );
            delete_option( $option11 );
            delete_option( $option12 );
            delete_option( $option13 );
            delete_option( $option14 );
            delete_option( $option15 );
            delete_option( $option16 );
            delete_option( $option17 );
            delete_option( $option18 );
            delete_option( $option19 );
            delete_option( $option20 );
            $table_name = $wpdb->prefix . 'wpfb_reviews';
            $wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
            //drop review template table
            $table_name = $wpdb->prefix . 'wpfb_post_templates';
            $wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
            //drop review wpfb_badges table
            $table_name = $wpdb->prefix . 'wpfb_badges';
            $wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
            //drop review wpfb_forms table
            $table_name = $wpdb->prefix . 'wpfb_forms';
            $wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
            //drop review wpfb_floats table
            $table_name = $wpdb->prefix . 'wpfb_floats';
            $wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
            //drop review wpfb_reviewfunnel table
            $table_name = $wpdb->prefix . 'wpfb_reviewfunnel';
            $wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
            //drop review wpfb_getapps_forms table
            $table_name = $wpdb->prefix . 'wpfb_getapps_forms';
            $wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
            //drop review wpfb_nofitifcation_forms table
            $table_name = $wpdb->prefix . 'wpfb_nofitifcation_forms';
            $wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
            //drop review wpfb_gettwitter_forms table
            $table_name = $wpdb->prefix . 'wpfb_gettwitter_forms';
            $wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
            //drop review wpfb_total_averages table
            $table_name = $wpdb->prefix . 'wpfb_total_averages';
            $wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
        }
        switch_to_blog( $original_blog_id );
    }
    //delete avatar and cache directories
    $upload = wp_upload_dir();
    $upload_dir = $upload['basedir'];
    $upload_dir_wprev = $upload_dir . '/wprevslider/';
    wpprorev_rmrf( $upload_dir_wprev );
}

//run single event cron to cache avatars on update-----------------
if ( is_admin() ) {
    add_action( 'admin_init', 'wprevpro_check_cache_avatars_cron' );
}
add_action( 'wprevpro_capic_event', 'wprevpro_check_cache_avatars' );
//used to set a one time event
function wprevpro_check_cache_avatars_cron() {
    //make sure this is an admin
    if ( current_user_can( 'manage_options' ) ) {
        //setup one time cron up if needed
        $current_version = get_option( WPREVPRO_PLUGIN_TOKEN . '_current_ca_pic_version', 0 );
        if ( $current_version != WPREVPRO_PLUGIN_VERSION ) {
            wp_schedule_single_event( time() + 10, 'wprevpro_capic_event' );
        }
    }
}

function wprevpro_check_cache_avatars() {
    //see if this is a update or new install, only run this once
    $current_version = get_option( WPREVPRO_PLUGIN_TOKEN . '_current_ca_pic_version', 0 );
    update_option( WPREVPRO_PLUGIN_TOKEN . '_current_ca_pic_version', WPREVPRO_PLUGIN_VERSION );
    //cache if we made this far
    if ( $current_version != WPREVPRO_PLUGIN_VERSION ) {
        //try to update avatars
        require_once plugin_dir_path( __FILE__ ) . 'admin/class-wp-review-slider-pro-admin_hooks.php';
        $plugin_admin = new WP_Review_Pro_Admin_Hooks('wp-review-slider-pro', WPREVPRO_PLUGIN_VERSION);
        $plugin_admin->wprevpro_download_img_tolocal();
    }
}

//-----------------------------------------------------//
//for running lang_code cron job. Not normally going to be used.
add_action( 'wprevpro_daily_event_lang', 'wprevpro_do_this_daily_lang' );
function wprevpro_do_this_daily_lang() {
    $options = get_option( 'wprevpro_notifications_settings' );
    if ( isset( $options['auto_lang_code'] ) && $options['auto_lang_code'] == 1 && $options['api_key'] != '' ) {
        $apikey = $options['api_key'];
        //auto add is turned on we need to run it.
        //echo "running";
        require_once plugin_dir_path( __FILE__ ) . 'admin/class-wp-review-slider-pro-admin_hooks.php';
        $plugin_admin = new WP_Review_Pro_Admin_Hooks('wp-review-slider-pro', WPREVPRO_PLUGIN_VERSION);
        $plugin_admin->wprevpro_run_language_detect_ajax_go( $apikey, $page = '0', 30 );
    }
    //print_r($options);
}

//for running the cron job
//if(isset($_GET['page']) && $_GET['page']=='wp_pro-welcome'){
//wpprorev_rf_getapps_cron();
//wprevpro_do_this_daily();
//echo "<div>======================testing============================</div>";
//}
add_action( 'wprevpro_daily_event', 'wprevpro_do_this_daily' );
function wprevpro_do_this_daily() {
    require_once plugin_dir_path( __FILE__ ) . 'admin/class-wp-review-slider-pro-admin_hooks.php';
    require_once plugin_dir_path( __FILE__ ) . 'admin/wppro_simple_html_dom.php';
    $plugin_admin = new WP_Review_Pro_Admin_Hooks('wp-review-slider-pro', WPREVPRO_PLUGIN_VERSION);
    $atleastone = false;
    //if they are using the Tools > Google Product Review XML file, then we need to re-create
    if ( get_option( 'wprev_googleprodratingxml' ) ) {
        $googleprodratingxml = get_option( 'wprev_googleprodratingxml' );
        $googleprodratingxmlarray = json_decode( $googleprodratingxml, true );
    } else {
        $googleprodratingxmlarray = array();
    }
    if ( !isset( $googleprodratingxmlarray['createxml'] ) ) {
        $googleprodratingxmlarray['createxml'] = '';
    }
    if ( $googleprodratingxmlarray['createxml'] == 'yes' ) {
        $upload = wp_upload_dir();
        $upload_dir = $upload['basedir'];
        $googleprodfiledir = $upload_dir . "/wprevslider/product_reviews.xml";
        $createxmlfile = $plugin_admin->createGoogleProductXMLFile( $googleprodfiledir );
    }
    //if we have the Tools > Translate reviews setting to run daily.
    if ( get_option( 'wprev_languagetranslator' ) ) {
        $savelanguagetranslatorjson = get_option( 'wprev_languagetranslator' );
        $savelanguagetranslatorarray = json_decode( $savelanguagetranslatorjson, true );
    } else {
        $savelanguagetranslatorarray = array();
    }
    if ( !isset( $savelanguagetranslatorarray['lang_autorun'] ) ) {
        $savelanguagetranslatorarray['lang_autorun'] = '';
    }
    if ( $savelanguagetranslatorarray['lang_autorun'] == 'yes' ) {
        //run the translator for 10 reviews max.
        $limit = 10;
        $apikey = $savelanguagetranslatorarray['lang_api_key'];
        $targlangs = $savelanguagetranslatorarray['lang_targetlang'];
        $lastrevid = 0;
        $runtranslator = $plugin_admin->wprevpro_run_language_translate_ajax_go(
            $apikey,
            $targlangs,
            $limit,
            $lastrevid
        );
        //print_r( $runtranslator );
    }
    //see if we need to download avatars to local
    if ( $atleastone ) {
        $plugin_admin->wprevpro_download_img_tolocal();
    }
}

//=========testing
//if(isset($_GET['page']) && $_GET['page']=='wp_pro-get_apps'){
//echo "here1";
//wpprorev_rf_getapps_cron();
//}
//=========
add_action( 'wprevpro_daily_event_getapps', 'wpprorev_rf_getapps_cron' );
function wpprorev_rf_getapps_cron() {
    //create a one-time cron to run in 10 minutes to make sure everything completed. Repeat if it didn't.
    wp_schedule_single_event( time() + 600, 'wprevpro_rf_getapps_cron_after' );
    //for get apps aka itunes, google crawl, etc..-------------
    global $wpdb;
    $table_name = $wpdb->prefix . 'wpfb_getapps_forms';
    $currentappforms = $wpdb->get_results( "SELECT * FROM {$table_name} where cron!='' ORDER BY last_ran ASC" );
    require_once plugin_dir_path( __FILE__ ) . 'admin/class-wp-review-slider-pro-admin_hooks.php';
    require_once plugin_dir_path( __FILE__ ) . 'admin/wppro_simple_html_dom.php';
    $plugin_admin = new WP_Review_Pro_Admin_Hooks('wp-review-slider-pro', WPREVPRO_PLUGIN_VERSION);
    $count = 0;
    foreach ( $currentappforms as $currentform ) {
        //only going to do 10 at a time so doesn't crash site.
        if ( $count < 10 ) {
            $newappformjob = false;
            /*
            echo "<br>";
            echo " id:".$currentform->id;
            echo " cron:".$currentform->cron;
            echo " last_ran:".$currentform->last_ran;
            echo " difftime:".time() - $currentform->last_ran;
            echo " timetocheck:".$currentform->cron * 60 * 60;
            */
            $difftime = time() - $currentform->last_ran;
            if ( $currentform->cron != '' && $currentform->last_ran != '' ) {
                //echo "run this job once a week";
                //run this if difference in last run time and today is greater than
                //run automatically if this is every day
                if ( $currentform->cron == '24' && $difftime > 86400 ) {
                    //only run if
                    $newappformjob = true;
                    // echo  " newappformjob:yes" ;
                } else {
                    $timetocheck = $currentform->cron * 60 * 60;
                    if ( $difftime > $timetocheck ) {
                        $newappformjob = true;
                        //   echo  " newappformjob:yes" ;
                    }
                }
            }
            if ( $newappformjob == true ) {
                $count = $count + 1;
                //echo "--request ne job fid:".$currentform->id;
                //download reviews
                if ( $currentform->site_type == 'Facebook' ) {
                    //echo "Facebook";
                    $plugin_admin->wprevpro_get_fb_reviews_cron( $currentform->page_id, $currentform->id );
                } else {
                    $newjobresults = $plugin_admin->wprp_getapps_getrevs_ajax_go(
                        $currentform->id,
                        1,
                        100,
                        0,
                        '',
                        'yes'
                    );
                }
            }
        }
    }
}

//=========testing
//if(isset($_GET['page']) && $_GET['page']=='wp_pro-get_apps'){
//echo "here1";
//wpopro_rf_getapps_cron_five();
//}
//=========
//This will run 5 minutes after the review get apps cron, fired by one time event.
function wpopro_rf_getapps_cron_five() {
    //check the get apps forms table to see if any cron jobs are set and did not complete
    global $wpdb;
    $table_name = $wpdb->prefix . 'wpfb_getapps_forms';
    $currentappforms = $wpdb->get_results( "SELECT * FROM {$table_name} where cron!='' ORDER BY last_ran ASC" );
    $missingcron = false;
    foreach ( $currentappforms as $currentform ) {
        if ( $currentform->cron > 0 && $currentform->last_ran > 0 ) {
            //echo "run this job once a week";
            //run this if difference in last run time and today is greater than 7 days.
            $difftime = time() - $currentform->last_ran;
            $timetocheck = $currentform->cron * 60 * 60;
            if ( $difftime > $timetocheck ) {
                $missingcron = true;
            }
        }
    }
    $timesrannum = get_option( 'wprev_loopcron', 0 );
    //echo "timesrannum:".$timesrannum;
    //=========need a one time cron that also runs to save image to local.=========
    if ( $timesrannum < 1 ) {
        //echo "here";
        //save avatars to local here.
        require_once plugin_dir_path( __FILE__ ) . 'admin/class-wp-review-slider-pro-admin_hooks.php';
        $plugin_admin = new WP_Review_Pro_Admin_Hooks('wp-review-slider-pro', WPREVPRO_PLUGIN_VERSION);
        $plugin_admin->wprevpro_download_img_tolocal();
    }
    if ( $missingcron == true && $timesrannum < 20 ) {
        $timesrannum = $timesrannum + 1;
        $timesran = update_option( 'wprev_loopcron', $timesrannum );
        //found a missing cron job, need to rerun.
        wpprorev_rf_getapps_cron();
    } else {
        update_option( 'wprev_loopcron', 0 );
    }
}

add_action(
    'wprevpro_rf_getapps_cron_after',
    'wpopro_rf_getapps_cron_five',
    10,
    2
);
add_action( 'wprevpro_daily_event_funnels', 'wpprorev_rf_funnel_cron' );
//=====testing======
//if ( isset( $_GET['page'] ) && $_GET['page'] == 'wp_pro-reviewfunnel' ) {
//wpprorev_rf_funnel_cron();
//}
function wpprorev_rf_funnel_cron() {
    //for review funnels-------------
    global $wpdb;
    $table_name = $wpdb->prefix . 'wpfb_reviewfunnel';
    $currentfunnels = $wpdb->get_results( "SELECT * FROM {$table_name} where cron!=''" );
    require_once plugin_dir_path( __FILE__ ) . 'admin/class-wp-review-slider-pro-admin_hooks.php';
    $plugin_admin = new WP_Review_Pro_Admin_Hooks('wp-review-slider-pro', WPREVPRO_PLUGIN_VERSION);
    //print_r($currentfunnels);
    foreach ( $currentfunnels as $currentfunnel ) {
        $newfunneljob = false;
        if ( $currentfunnel->cron == '24' ) {
            //echo "run this job once a day";
            $newfunneljob = true;
        } else {
            if ( $currentfunnel->cron == '48' ) {
                //echo "run this job every other a day";
                $today = date( "j" );
                //number 1 - 31
                //if $today is even then run this cron
                if ( $today % 2 == 0 ) {
                    $newfunneljob = true;
                }
            } else {
                if ( $currentfunnel->cron != '' ) {
                    //echo "run this job once a week";
                    //run this if difference in last run time and today is greater than 7 days.
                    $difftime = time() - $currentfunnel->cron_last_ran;
                    $timetocheck = $currentfunnel->cron * 60 * 60;
                    if ( $difftime > $timetocheck ) {
                        $newfunneljob = true;
                    }
                }
            }
        }
        if ( $newfunneljob == true && wrsp_fs()->can_use_premium_code() ) {
            //echo "--request ne job fid:".$currentfunnel->id;
            //request a new scrape job
            $newjobresults = $plugin_admin->wprp_revfunnel_addprofile_ajax_go( $currentfunnel->id, 'usediff', $currentfunnel->cron );
            //print_r($newjobresults);
            if ( isset( $newjobresults['job_id'] ) && $newjobresults['job_id'] != '' ) {
                //update db with cron jobid and cron ran on
                $lji = $newjobresults['job_id'];
                $clr = time();
                $cfid = $currentfunnel->id;
                $cnc = 0;
                $data = array(
                    'cron_last_job_id'      => "{$lji}",
                    'cron_last_ran'         => "{$clr}",
                    'cron_numtimes_checked' => "{$cnc}",
                );
                $format = array('%s', '%s', '%s');
                $updatetempquery = $wpdb->update(
                    $table_name,
                    $data,
                    array(
                        'id' => $cfid,
                    ),
                    $format,
                    array('%d')
                );
            }
        }
    }
    //-------------
}

//=====testing======
//if(isset($_GET['page']) && $_GET['page']=='wp_pro-reviewfunnel'){
//	wpopro_rf_cron_get_reviews();
//}
//----
add_action( 'wprevpro_daily_event_funnels_download', 'wpopro_rf_cron_get_reviews' );
//This will run twice a day looking for any review funnel cron jobs that have ran in the last 24 hours and need to be downloaded. save successful download so we don't check again.
function wpopro_rf_cron_get_reviews() {
    require_once plugin_dir_path( __FILE__ ) . 'admin/class-wp-review-slider-pro-admin_hooks.php';
    $plugin_admin = new WP_Review_Pro_Admin_Hooks('wp-review-slider-pro', WPREVPRO_PLUGIN_VERSION);
    global $wpdb;
    $table_name_funnel = $wpdb->prefix . 'wpfb_reviewfunnel';
    $currentforms = $wpdb->get_results( "SELECT * FROM {$table_name_funnel} where cron!='' ORDER BY cron_last_ran ASC" );
    //print_r($currentforms);
    foreach ( $currentforms as $currentform ) {
        if ( $currentform->cron != '' && $currentform->cron_last_ran != '' ) {
            //run this if difference in last run time and today is greater than last
            if ( $currentform->cron_last_ran > $currentform->cron_last_download ) {
                //we need to try and download this one.
                $job_id = $currentform->cron_last_job_id;
                $fid = $currentform->id;
                $scrapethejob = $plugin_admin->wprp_revfunnel_getrevs_ajax_go( $job_id, $fid );
                //print_r($scrapethejob);
                //die();
                //catch communication error and rerun the job again next time.
                $cfid = $currentform->id;
                if ( $currentform->cron_numtimes_checked < 9 && (!isset( $scrapethejob['crawl_status'] ) || $scrapethejob['crawl_status'] == '') ) {
                    //set cron_numtimes_checked up by one
                    $cnc = $currentform->cron_numtimes_checked + 1;
                    $data = array(
                        'cron_numtimes_checked' => "{$cnc}",
                    );
                    $format = array('%s');
                    $updatetempquery = $wpdb->update(
                        $table_name_funnel,
                        $data,
                        array(
                            'id' => $cfid,
                        ),
                        $format,
                        array('%d')
                    );
                    //echo $wpdb->last_query;
                } else {
                    if ( $scrapethejob['crawl_status'] != 'pending' || $currentform->cron_numtimes_checked > 8 ) {
                        //job is either complete or failed, either way we update so we don't check it again.
                        $cld = time();
                        $data = array(
                            'cron_last_download'    => "{$cld}",
                            'cron_numtimes_checked' => 0,
                        );
                        $format = array('%s', '%d');
                        $updatetempquery = $wpdb->update(
                            $table_name_funnel,
                            $data,
                            array(
                                'id' => $cfid,
                            ),
                            $format,
                            array('%d')
                        );
                    } else {
                        if ( $scrapethejob['crawl_status'] == 'pending' ) {
                            //don't do anything since job is still pending
                        }
                    }
                }
                //update cache
                if ( $scrapethejob['crawl_status'] == 'complete' ) {
                    $plugin_admin->wprevpro_download_img_tolocal();
                }
            }
        }
    }
    //update cache
    $plugin_admin->wprevpro_download_img_tolocal();
}

//=====testing======
//if(isset($_GET['page']) && $_GET['page']=='wp_pro-notifications'){
//	wprevpro_do__autogetrevs_hourly();
//}
//still need to setup cron job for this I think.
//action to use to run the auto setup source page tool on the Tools tab.
add_action( 'wprevpro_autogetrevs_hourly', 'wprevpro_do__autogetrevs_hourly' );
function wprevpro_do__autogetrevs_hourly() {
    require_once plugin_dir_path( __FILE__ ) . 'admin/class-wp-review-slider-pro-admin_hooks.php';
    $plugin_admin = new WP_Review_Pro_Admin_Hooks('wp-review-slider-pro', WPREVPRO_PLUGIN_VERSION);
    //pull values from options.
    $saveautogetrevsjson = get_option( 'wprev_autogetrevs' );
    $saveautogetrevsjsonarray = json_decode( $saveautogetrevsjson, true );
    $resultarray = $plugin_admin->wprevpro_run_autogetrevs_ajax_go(
        $saveautogetrevsjsonarray['autogetrevs_type'],
        $saveautogetrevsjsonarray['autogetrevs_posttype'],
        $saveautogetrevsjsonarray['autogetrevs_cfn'],
        $saveautogetrevsjsonarray['autogetrevs_hourly'],
        $saveautogetrevsjsonarray['autogetrevs_langcode'],
        $saveautogetrevsjsonarray['autogetrevs_which'],
        $saveautogetrevsjsonarray['autogetrevs_cron']
    );
    //use resultarray to communicate back to javascript
    //echo json_encode($resultarray);
}

//=====testing======
//if(isset($_GET['page']) && $_GET['page']=='wp_pro-get_woo'){
//	add_action('init','wprevpro_do__pushtowoo');
//wprevpro_do__pushtowoo();
//}
//start and stop this cron job from the get woo page.
//action to use to run the auto setup source page tool on the Tools tab.
add_action( 'wprevpro_pushtowoo', 'wprevpro_do__pushtowoo' );
function wprevpro_do__pushtowoo() {
    require_once plugin_dir_path( __FILE__ ) . 'admin/class-wp-review-slider-pro-admin_hooks.php';
    $plugin_admin = new WP_Review_Pro_Admin_Hooks('wp-review-slider-pro', WPREVPRO_PLUGIN_VERSION);
    $resultarray = $plugin_admin->wprevpro_cron_push_to_woo();
}

//print_r(wprevpro_gettotalavgs('ChIJC8DB3J5sYogRV8b_lTk20U4'));
//===public admin function so developers can get an array of values from avgtotal table
function wprevpro_gettotalavgs(  $pageid  ) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'wpfb_total_averages';
    $totalavgdata = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_name} where btp_id = %s ", $pageid ), ARRAY_A );
    return $totalavgdata;
}

//===public admin function so developers can get star html from an average.
function wprevpro_getstarhtml(  $finalavg  ) {
    //starhtml setup-------------
    $starhtml = '<span class="wppro_badge1_DIV_stars b4s2">';
    $roundtohalf = '';
    if ( $finalavg > 0 ) {
        $roundtohalf = round( $finalavg * 2 ) / 2;
    }
    for ($x = 1; $x <= $roundtohalf; $x++) {
        //$starhtml = $starhtml.'<span class="wprsp-star-full"></span>';
        $starhtml = $starhtml . '<span class="svgicons svg-wprsp-star-full"></span>';
    }
    if ( $roundtohalf == 1.5 || $roundtohalf == 2.5 || $roundtohalf == 3.5 || $roundtohalf == 4.5 ) {
        //add another half
        //$starhtml = $starhtml.'<span class="wprsp-star-half"></span>';
        $starhtml = $starhtml . '<span class="svgicons svg-wprsp-star-half"></span>';
        $x++;
    }
    //if x is less than 5 need another star or half
    $starleft = 5 - $x;
    for ($x = 0; $x <= $starleft; $x++) {
        //$starhtml = $starhtml.'<span class="wprsp-star-empty"></span>';
        $starhtml = $starhtml . '<span class="svgicons svg-wprsp-star-empty"></span>';
    }
    $starhtml = $starhtml . '</span>';
    return $starhtml;
}

//function for returning icon sizes and setting constant above so we can get sizes in template and badges
function wppro_get_logo_size(  $sourcetype  ) {
    $widthheighticon['x'] = 32;
    $widthheighticon['y'] = 32;
    if ( $sourcetype == "Airbnb" ) {
        $widthheighticon['x'] = 30;
    }
    if ( $sourcetype == "BBB" ) {
        $widthheighticon['x'] = 22;
    }
    if ( $sourcetype == "Bestbuy" ) {
        $widthheighticon['x'] = 55;
    }
    if ( $sourcetype == "Birdeye" ) {
        $widthheighticon['x'] = 33;
    }
    if ( $sourcetype == "Bol" ) {
        $widthheighticon['x'] = 58;
    }
    if ( $sourcetype == "Booking" ) {
        $widthheighticon['x'] = 33;
    }
    if ( $sourcetype == "Bookatable" ) {
        $widthheighticon['x'] = 22;
    }
    if ( $sourcetype == "CarGurus" ) {
        $widthheighticon['x'] = 63;
    }
    if ( $sourcetype == "Cars" ) {
        $widthheighticon['x'] = 76;
    }
    if ( $sourcetype == "CustomerLobby" ) {
        $widthheighticon['x'] = 62;
    }
    if ( $sourcetype == "DealerRater" ) {
        $widthheighticon['x'] = 229;
    }
    if ( $sourcetype == "Drizly" ) {
        $widthheighticon['x'] = 37;
    }
    if ( $sourcetype == "Ebay" ) {
        $widthheighticon['x'] = 58;
    }
    if ( $sourcetype == "Edmunds" ) {
        $widthheighticon['x'] = 47;
    }
    if ( $sourcetype == "Etsy" ) {
        $widthheighticon['x'] = 67;
    }
    if ( $sourcetype == "Experience" ) {
        $widthheighticon['x'] = 26;
    }
    if ( $sourcetype == "Feefo" ) {
        $widthheighticon['x'] = 119;
    }
    if ( $sourcetype == "FindLaw" ) {
        $widthheighticon['x'] = 153;
    }
    if ( $sourcetype == "G2Crowd" ) {
        $widthheighticon['x'] = 33;
    }
    if ( $sourcetype == "Gartner" ) {
        $widthheighticon['x'] = 40;
    }
    if ( $sourcetype == "GetYourGuide" ) {
        $widthheighticon['x'] = 23;
    }
    if ( $sourcetype == "GooglePlay" ) {
        $widthheighticon['x'] = 29;
    }
    if ( $sourcetype == "Healthgrades" ) {
        $widthheighticon['x'] = 39;
    }
    if ( $sourcetype == "Holidaycheck" ) {
        $widthheighticon['x'] = 42;
    }
    if ( $sourcetype == "HomeAdvisor" ) {
        $widthheighticon['x'] = 40;
    }
    if ( $sourcetype == "HomeAway" ) {
        $widthheighticon['x'] = 36;
    }
    if ( $sourcetype == "Homestars" ) {
        $widthheighticon['x'] = 36;
    }
    if ( $sourcetype == "Hotels" ) {
        $widthheighticon['x'] = 30;
    }
    if ( $sourcetype == "HousecallPro" ) {
        $widthheighticon['x'] = 20;
    }
    if ( $sourcetype == "HungerStation" ) {
        $widthheighticon['x'] = 67;
    }
    if ( $sourcetype == "Indeed" ) {
        $widthheighticon['x'] = 22;
    }
    if ( $sourcetype == "ITCentralStation" ) {
        $widthheighticon['x'] = 35;
    }
    if ( $sourcetype == "LendingTree" ) {
        $widthheighticon['x'] = 31;
    }
    if ( $sourcetype == "Newegg" ) {
        $widthheighticon['x'] = 64;
    }
    if ( $sourcetype == "Nextdoor" ) {
        $widthheighticon['x'] = 40;
    }
    if ( $sourcetype == "Niche" ) {
        $widthheighticon['x'] = 46;
    }
    if ( $sourcetype == "Opentable" ) {
        $widthheighticon['x'] = 44;
    }
    if ( $sourcetype == "ProductReview" ) {
        $widthheighticon['x'] = 30;
    }
    if ( $sourcetype == "Realself" ) {
        $widthheighticon['x'] = 59;
    }
    if ( $sourcetype == "Reviews.io" ) {
        $widthheighticon['x'] = 52;
    }
    if ( $sourcetype == "Siftery" ) {
        $widthheighticon['x'] = 33;
    }
    if ( $sourcetype == "Sitejabber" ) {
        $widthheighticon['x'] = 35;
    }
    if ( $sourcetype == "Smythstoys" ) {
        $widthheighticon['x'] = 95;
    }
    if ( $sourcetype == "SoftwareAdvice" ) {
        $widthheighticon['x'] = 38;
    }
    if ( $sourcetype == "SourceForge" ) {
        $widthheighticon['x'] = 36;
    }
    if ( $sourcetype == "StyleSeat" ) {
        $widthheighticon['x'] = 25;
    }
    if ( $sourcetype == "TheFork" ) {
        $widthheighticon['x'] = 28;
    }
    if ( $sourcetype == "TrueLocal" ) {
        $widthheighticon['x'] = 40;
    }
    if ( $sourcetype == "Trulia" ) {
        $widthheighticon['x'] = 17;
    }
    if ( $sourcetype == "TrustRadius" ) {
        $widthheighticon['x'] = 41;
    }
    if ( $sourcetype == "Twitter" ) {
        $widthheighticon['x'] = 39;
    }
    if ( $sourcetype == "Vitals" ) {
        $widthheighticon['x'] = 77;
    }
    if ( $sourcetype == "Walmart" ) {
        $widthheighticon['x'] = 29;
    }
    if ( $sourcetype == "WebMD" ) {
        $widthheighticon['x'] = 144;
    }
    if ( $sourcetype == "WooCommerce" ) {
        $widthheighticon['x'] = 54;
    }
    if ( $sourcetype == "Yell" ) {
        $widthheighticon['x'] = 33;
    }
    if ( $sourcetype == "Yelp" ) {
        $widthheighticon['x'] = 24;
    }
    if ( $sourcetype == "Zillow" ) {
        $widthheighticon['x'] = 39;
    }
    if ( $sourcetype == "ZocDoc" ) {
        $widthheighticon['x'] = 26;
    }
    return $widthheighticon;
}

//====================
//check for whitelable settings
// Get the contents of the JSON file
/*
$wlsettingsfilename = WPREV_PLUGIN_DIR . 'admin/wlsettings.txt';
if (file_exists($wlsettingsfilename)) {
	$wlsettingsContents = file_get_contents($wlsettingsfilename);
	$wlsettingsarray = json_decode($wlsettingsContents, true);
	print_r($wlsettingsarray); // print array
}
*/
//start the plugin-------------
run_WP_Review_Pro();
//======================
//check if any free versions are active, if so then add message to admin-----
if ( in_array( 'wp-google-places-review-slider/wp-google-reviews.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    //plugin is activated
    function wp_rev_freeversion_admin_notice_google() {
        $class = 'notice notice-error is-dismissible';
        $message = __( 'Warning! Please de-activate the free version (WP Google Review Slider) of the WP Review Slider Pro plugin. You can not use the Free version and the Pro version at the same time. If you would like to completely delete the free version, then first de-activate the Pro version.', 'wp-review-slider-pro' );
        printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
    }

    add_action( 'admin_notices', 'wp_rev_freeversion_admin_notice_google' );
}
if ( in_array( 'wp-airbnb-review-slider/wp-airbnb-review-slider.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    //plugin is activated
    function wp_rev_freeversion_admin_notice_airbnb() {
        $class = 'notice notice-error is-dismissible';
        $message = __( 'Warning! Please de-activate the free version (WP Airbnb Review Slider) of the WP Review Slider Pro plugin. You can not use the Free version and the Pro version at the same time. If you would like to completely delete the free version, then first de-activate the Pro version.', 'wp-review-slider-pro' );
        printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
    }

    add_action( 'admin_notices', 'wp_rev_freeversion_admin_notice_airbnb' );
}
if ( in_array( 'wp-facebook-reviews/wp-fb-reviews.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    //plugin is activated
    function wp_rev_freeversion_admin_notice_fb() {
        $class = 'notice notice-error is-dismissible';
        $message = __( 'Warning! Please de-activate the free version (WP Review Slider) of the WP Review Slider Pro plugin. You can not use the Free version and the Pro version at the same time. If you would like to completely delete the free version, then first de-activate the Pro version.', 'wp-review-slider-pro' );
        printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
    }

    add_action( 'admin_notices', 'wp_rev_freeversion_admin_notice_fb' );
}
if ( in_array( 'wp-tripadvisor-review-slider/wp-tripadvisor-review-slider.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    //plugin is activated
    function wp_rev_freeversion_admin_notice_trip() {
        $class = 'notice notice-error is-dismissible';
        $message = __( 'Warning! Please de-activate the free version (WP TripAdvisor Review Slider) of the WP Review Slider Pro plugin. You can not use the Free version and the Pro version at the same time. If you would like to completely delete the free version, then first de-activate the Pro version.', 'wp-review-slider-pro' );
        printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
    }

    add_action( 'admin_notices', 'wp_rev_freeversion_admin_notice_trip' );
}
if ( in_array( 'wp-yelp-review-slider/wp-yelp-review-slider.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    //plugin is activated
    function wp_rev_freeversion_admin_notice_yelp() {
        $class = 'notice notice-error is-dismissible';
        $message = __( 'Warning! Please de-activate the free version (WP Yelp Review Slider) of the WP Review Slider Pro plugin. You can not use the Free version and the Pro version at the same time. If you would like to completely delete the free version, then first de-activate the Pro version.', 'wp-review-slider-pro' );
        printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
    }

    add_action( 'admin_notices', 'wp_rev_freeversion_admin_notice_yelp' );
}
if ( in_array( 'wp-zillow-review-slider/wp-zillow-review-slider.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    //plugin is activated
    function wp_rev_freeversion_admin_notice_zillow() {
        $class = 'notice notice-error is-dismissible';
        $message = __( 'Warning! Please de-activate the free version (WP Zillow Review Slider) of the WP Review Slider Pro plugin. You can not use the Free version and the Pro version at the same time. If you would like to completely delete the free version, then first de-activate the Pro version.', 'wp-review-slider-pro' );
        printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
    }

    add_action( 'admin_notices', 'wp_rev_freeversion_admin_notice_zillow' );
}
if ( in_array( 'wp-thumbtack-review-slider/wp-thumbtack-review-slider.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    //plugin is activated
    function wp_rev_freeversion_admin_notice_thumbtack() {
        $class = 'notice notice-error is-dismissible';
        $message = __( 'Warning! Please de-activate the free version (WP Thumbtack Review Slider) of the WP Review Slider Pro plugin. You can not use the Free version and the Pro version at the same time. If you would like to completely delete the free version, then first de-activate the Pro version.', 'wp-review-slider-pro' );
        printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
    }

    add_action( 'admin_notices', 'wp_rev_freeversion_admin_notice_thumbtack' );
}
/*
add_filter('current_screen','my_current_screen');
function my_current_screen($screen) {
    if( defined('DOING_AJAX') && DOING_AJAX )return$screen;
    print_r($screen);
    return$screen;
}
*/