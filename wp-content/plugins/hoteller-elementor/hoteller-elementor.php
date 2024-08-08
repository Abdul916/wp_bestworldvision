<?php
/**
 * Plugin Name: Hoteller Theme Elements for Elementor
 * Description: Custom elements for Elementor using Hoteller theme
 * Plugin URI:  https://themegoods.com/
 * Version:     3.9
 * Author:      ThemGoods
 * Author URI:  https://themegoods.com/
 * Elementor tested up to: 3.16.4
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}

//Theme Envato item ID
if (!defined('ENVATOITEMID')) {
	define("ENVATOITEMID", 22316029);
}

if(!function_exists('hoteller_is_registered'))
{
	function hoteller_is_registered() {
		$hoteller_is_registered = get_option("envato_purchase_code_".ENVATOITEMID);
		
		if(!empty($hoteller_is_registered)) {
			return $hoteller_is_registered;
		}
		else {
			return false;
		}
	}
}

define( 'HOTELLER_ELEMENTOR_PATH', plugin_dir_path( __FILE__ ));

if (!defined('HOTELLER_THEMEDATEFORMAT'))
{
	define("HOTELLER_THEMEDATEFORMAT", get_option('date_format'));
}

if (!defined('HOTELLER_THEMETIMEFORMAT'))
{
	define("HOTELLER_THEMETIMEFORMAT", get_option('time_format'));
}

$is_verified_envato_purchase_code = false;

//Check if verified
$is_verified_envato_purchase_code = hoteller_is_registered();

if($is_verified_envato_purchase_code) {
	/**
	 * Load the plugin after Elementor (and other plugins) are loaded.
	 *
	 * @since 1.0.0
	 */
	function hoteller_elementor_load() {
		load_plugin_textdomain( 'hoteller-elementor', FALSE, dirname( plugin_basename(__FILE__) ) . '/languages/' );
		
		// Require the main plugin file
		require(HOTELLER_ELEMENTOR_PATH.'/tools.php');
		require(HOTELLER_ELEMENTOR_PATH.'/actions.php');
		require(HOTELLER_ELEMENTOR_PATH.'/templates.php' );
		require(HOTELLER_ELEMENTOR_PATH.'/plugin.php' );
		require(HOTELLER_ELEMENTOR_PATH.'/shortcode.php');
		require(HOTELLER_ELEMENTOR_PATH.'/page-fields.php' );
		require(HOTELLER_ELEMENTOR_PATH.'/post-fields.php' );
		require(HOTELLER_ELEMENTOR_PATH.'/megamenu.php');
	}
	add_action( 'plugins_loaded', 'hoteller_elementor_load' );
	
	//Add featured image support for room attribute
	add_post_type_support( 'mphb_room_attribute', 'thumbnail' );
	
	function hoteller_post_type_header() {
		$labels = array(
			'name' => _x('Headers', 'post type general name', 'hoteller-elementor'),
			'singular_name' => _x('Header', 'post type singular name', 'hoteller-elementor'),
			'add_new' => _x('Add New Header', 'hoteller-elementor'),
			'add_new_item' => __('Add New Header', 'hoteller-elementor'),
			'edit_item' => __('Edit Header', 'hoteller-elementor'),
			'new_item' => __('New Header', 'hoteller-elementor'),
			'view_item' => __('View Header', 'hoteller-elementor'),
			'search_items' => __('Search Header', 'hoteller-elementor'),
			'not_found' =>  __('No Header found', 'hoteller-elementor'),
			'not_found_in_trash' => __('No Header found in Trash', 'hoteller-elementor'), 
			'parent_item_colon' => ''
		);		
		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true, 
			'query_var' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'show_in_nav_menus' => false,
			'show_in_admin_bar' => true,
			'menu_position' => 20,
			'exclude_from_search' => true,
			'supports' => array('title', 'content'),
			'menu_icon' => 'dashicons-editor-insertmore'
		); 		
	
		register_post_type( 'header', $args );
	} 
									  
	add_action('init', 'hoteller_post_type_header');
	
	
	function hoteller_post_type_footer() {
		$labels = array(
			'name' => _x('Footers', 'post type general name', 'hoteller-elementor'),
			'singular_name' => _x('Footer', 'post type singular name', 'hoteller-elementor'),
			'add_new' => _x('Add New Footer', 'hoteller-elementor'),
			'add_new_item' => __('Add New Footer', 'hoteller-elementor'),
			'edit_item' => __('Edit Footer', 'hoteller-elementor'),
			'new_item' => __('New Footer', 'hoteller-elementor'),
			'view_item' => __('View Footer', 'hoteller-elementor'),
			'search_items' => __('Search Footer', 'hoteller-elementor'),
			'not_found' =>  __('No Footer found', 'hoteller-elementor'),
			'not_found_in_trash' => __('No Footer found in Trash', 'hoteller-elementor'), 
			'parent_item_colon' => ''
		);		
		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true, 
			'query_var' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'show_in_nav_menus' => false,
			'show_in_admin_bar' => true,
			'menu_position' => 20,
			'exclude_from_search' => true,
			'supports' => array('title', 'content'),
			'menu_icon' => 'dashicons-editor-insertmore'
		); 		
	
		register_post_type( 'footer', $args );
	} 
									  
	add_action('init', 'hoteller_post_type_footer');
	
	function hoteller_post_type_megamenu() {
		$labels = array(
			'name' => _x('Mega Menus', 'post type general name', 'hoteller-elementor'),
			'singular_name' => _x('Mega Menu', 'post type singular name', 'hoteller-elementor'),
			'add_new' => _x('Add New Mega Menu', 'hoteller-elementor'),
			'add_new_item' => __('Add New Mega Menu', 'hoteller-elementor'),
			'edit_item' => __('Edit Mega Menu', 'hoteller-elementor'),
			'new_item' => __('New Mega Menu', 'hoteller-elementor'),
			'view_item' => __('View Mega Menu', 'hoteller-elementor'),
			'search_items' => __('Search Mega Menu', 'hoteller-elementor'),
			'not_found' =>  __('No Mega Menu found', 'hoteller-elementor'),
			'not_found_in_trash' => __('No Mega Menu found in Trash', 'hoteller-elementor'), 
			'parent_item_colon' => ''
		);		
		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true, 
			'query_var' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'show_in_nav_menus' => false,
			'show_in_admin_bar' => true,
			'menu_position' => 20,
			'exclude_from_search' => true,
			'supports' => array('title', 'content'),
			'menu_icon' => 'dashicons-welcome-widgets-menus'
		); 		
	
		register_post_type( 'megamenu', $args );
	} 
									  
	add_action('init', 'hoteller_post_type_megamenu');
	
	function hoteller_post_type_fullscreen_menu() {
		$labels = array(
			'name' => _x('Fullscreen Menus', 'post type general name', 'hoteller-elementor'),
			'singular_name' => _x('Fullscreen Menu', 'post type singular name', 'hoteller-elementor'),
			'add_new' => _x('Add New Fullscreen Menu', 'hoteller-elementor'),
			'add_new_item' => __('Add New Fullscreen Menu', 'hoteller-elementor'),
			'edit_item' => __('Edit Fullscreen Menu', 'hoteller-elementor'),
			'new_item' => __('New Fullscreen Menu', 'hoteller-elementor'),
			'view_item' => __('View Fullscreen Menu', 'hoteller-elementor'),
			'search_items' => __('Search Fullscreen Menu', 'hoteller-elementor'),
			'not_found' =>  __('No Fullscreen Menu found', 'hoteller-elementor'),
			'not_found_in_trash' => __('No Mega Menu found in Trash', 'hoteller-elementor'), 
			'parent_item_colon' => ''
		);		
		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true, 
			'query_var' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'show_in_nav_menus' => false,
			'show_in_admin_bar' => true,
			'menu_position' => 20,
			'exclude_from_search' => true,
			'supports' => array('title', 'content'),
			'menu_icon' => 'dashicons-format-aside'
		); 		
	
		register_post_type( 'fullmenu', $args );
	} 
									  
	add_action('init', 'hoteller_post_type_fullscreen_menu');
	
	/**
	*	Begin Recent Posts Custom Widgets
	**/
	
	class hoteller_Recent_Posts extends WP_Widget {
		function __construct() {
			$widget_ops = array('classname' => 'hoteller_Recent_Posts', 'description' => 'The recent posts with thumbnails' );
			parent::__construct('hoteller_Recent_Posts', 'Custom Recent Posts', $widget_ops);
		}
	
		function widget($args, $instance) {
			extract($args, EXTR_SKIP);
	
			echo stripslashes($before_widget);
			$items = empty($instance['items']) ? ' ' : apply_filters('widget_title', $instance['items']);
			$items = absint($items);
			
			$show_thumb = empty($instance['show_thumb']) ? ' ' : apply_filters('widget_title', $instance['show_thumb']);
			
			if(!is_numeric($items))
			{
				$items = 3;
			}
			
			if(!empty($items))
			{
				hoteller_posts('recent', $items, TRUE, trim($show_thumb));
			}
			
			echo stripslashes($after_widget);
		}
	
		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			$instance['items'] = strip_tags($new_instance['items']);
			$instance['show_thumb'] = strip_tags($new_instance['show_thumb']);
	
			return $instance;
		}
	
		function form($instance) {
			$instance = wp_parse_args( (array) $instance, array( 'items' => '', 'show_thumb' => '') );
			$items = strip_tags($instance['items']);
			$show_thumb = strip_tags($instance['show_thumb']);
	
	?>
				<p><label for="<?php echo esc_attr($this->get_field_id('items')); ?>"><?php esc_html_e('Items (default 3)', 'hoteller' ); ?>: <input class="widefat" id="<?php echo esc_attr($this->get_field_id('items')); ?>" name="<?php echo esc_attr($this->get_field_name('items')); ?>" type="text" value="<?php echo esc_attr($items); ?>" /></label></p>
				
				<p><label for="<?php echo esc_attr($this->get_field_id('show_thumb')); ?>"><?php esc_html_e('Display Thumbnails', 'hoteller' ); ?>: <input class="widefat" id="<?php echo esc_attr($this->get_field_id('show_thumb')); ?>" name="<?php echo esc_attr($this->get_field_name('show_thumb')); ?>" type="checkbox" value="1" <?php if(!empty($show_thumb)) { ?>checked<?php } ?> /></label></p>
	<?php
		}
	}
	
	add_action('widgets_init', 'hoteller_recent_posts_widget');
	function hoteller_recent_posts_widget() {
		register_widget('hoteller_Recent_Posts');
	}
	
	/**
	*	End Recent Posts Custom Widgets
	**/
	
	
	/**
	*	Begin Flickr Feed Custom Widgets
	**/
	
	class hoteller_Flickr extends WP_Widget {
		function __construct() {
			$widget_ops = array('classname' => 'hoteller_Flickr', 'description' => 'Display your recent Flickr photos' );
			parent::__construct('hoteller_Flickr', 'Custom Flickr', $widget_ops);
		}
	
		function widget($args, $instance) {
			extract($args, EXTR_SKIP);
	
			echo stripslashes($before_widget);
			$flickr_id = empty($instance['flickr_id']) ? ' ' : apply_filters('widget_title', $instance['flickr_id']);
			$title = $instance['title'];
			$items = $instance['items'];
			$items = absint($items);
			
			if(!is_numeric($items))
			{
				$items = 9;
			}
			
			if(!empty($items) && !empty($flickr_id))
			{
				$photos_arr = hoteller_get_flickr(array('type' => 'user', 'id' => $flickr_id, 'items' => $items));
	
				if(!empty($photos_arr))
				{
					echo stripslashes($before_title);
					echo esc_html($title);
					echo stripslashes($after_title);
					
					echo '<ul class="flickr">';
					
					foreach($photos_arr as $photo)
					{
						echo '<li>';
						echo '<a class="no_effect" target="_blank" href="'.esc_url($photo['link']).'"><img src="'.esc_url($photo['thumb_url']).'" alt="'.esc_attr($photo['title']).'" width="75" height="75" /></a>';
						echo '</li>';
					}
					
					echo '</ul><br class="clear"/>';
				}
			}
			
			echo stripslashes($after_widget);
		}
	
		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			$instance['items'] = absint($new_instance['items']);
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['flickr_id'] = strip_tags($new_instance['flickr_id']);
	
			return $instance;
		}
	
		function form($instance) {
			$instance = wp_parse_args( (array) $instance, array( 'items' => '', 'flickr_id' => '', 'title' => '') );
			$items = strip_tags($instance['items']);
			$flickr_id = strip_tags($instance['flickr_id']);
			$title = strip_tags($instance['title']);
	
	?>
				<p><label for="<?php echo esc_attr($this->get_field_id('flickr_id')); ?>"><?php esc_html_e('Flickr ID', 'hoteller' ); ?> <a href="http://idgettr.com/"><?php esc_html_e('Find your Flickr ID here', 'hoteller' ); ?></a>: <input class="widefat" id="<?php echo esc_attr($this->get_field_id('flickr_id')); ?>" name="<?php echo esc_attr($this->get_field_name('flickr_id')); ?>" type="text" value="<?php echo esc_attr($flickr_id); ?>" /></label></p>
				
				<p><label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title', 'hoteller' ); ?>: <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
	
				<p><label for="<?php echo esc_attr($this->get_field_id('items')); ?>"><?php esc_html_e('Items (default 9)', 'hoteller' ); ?>: <input class="widefat" id="<?php echo esc_attr($this->get_field_id('items')); ?>" name="<?php echo esc_attr($this->get_field_name('items')); ?>" type="text" value="<?php echo esc_attr($items); ?>" /></label></p>
	<?php
		}
	}
	
	add_action('widgets_init', 'hoteller_flickr_widget');
	function hoteller_flickr_widget() {
		register_widget('hoteller_Flickr');
	}
	
	/**
	*	End Flickr Feed Custom Widgets
	**/
	
	
	/**
	*	Begin Instagram Feed Custom Widgets
	**/
	
	class hoteller_Instagram extends WP_Widget {
		function __construct() {
			$widget_ops = array('classname' => 'hoteller_Instagram', 'description' => 'Display your recent Instagram photos' );
			parent::__construct('hoteller_Instagram', 'Custom Instagram', $widget_ops);
		}
	
		function widget($args, $instance) {
			extract($args, EXTR_SKIP);
	
			echo stripslashes($before_widget);
			$title = $instance['title'];
			$items = $instance['items'];
			$items = absint($items);
			
			//Get Instagram Access Data
			$pp_instagram_username = get_option('pp_instagram_username');
			$pp_instagram_access_token = get_option('pp_instagram_access_token');
			
			if(!is_numeric($items))
			{
				$items = 9;
			}
			
			if(!empty($items) && !empty($pp_instagram_username) && !empty($pp_instagram_access_token))
			{
				$photos_arr = hoteller_get_instagram($pp_instagram_username, $pp_instagram_access_token, $items);
	
				if(!empty($photos_arr))
				{
					echo stripslashes($before_title);
					echo esc_html($title);
					echo stripslashes($after_title);
					
					echo '<ul class="flickr">';
					
					foreach($photos_arr as $photo)
					{
						echo '<li>';
						echo '<a class="no_effect" target="_blank" href="'.esc_url($photo['link']).'"><img src="'.esc_url($photo['thumb_url']).'" width="75" height="75" alt="'.esc_attr($photo['title']).'" /></a>';
						echo '</li>';
					}
					
					echo '</ul><br class="clear"/>';
				}
			}
			else
			{
				echo  esc_html__('Error: Please check if you enter Instagram username and Access Token in Theme Setting > Social Profiles', 'hoteller' );
			}
			
			echo stripslashes($after_widget);
		}
	
		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			$instance['items'] = absint($new_instance['items']);
			$instance['title'] = strip_tags($new_instance['title']);
	
			return $instance;
		}
	
		function form($instance) {
			$instance = wp_parse_args( (array) $instance, array( 'items' => '', 'title' => '') );
			$items = strip_tags($instance['items']);
			$title = strip_tags($instance['title']);
	
	?>
				<p><label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title', 'hoteller' ); ?>: <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
	
				<p><label for="<?php echo esc_attr($this->get_field_id('items')); ?>"><?php esc_html_e('Items (default 9)', 'hoteller' ); ?>: <input class="widefat" id="<?php echo esc_attr($this->get_field_id('items')); ?>" name="<?php echo esc_attr($this->get_field_name('items')); ?>" type="text" value="<?php echo esc_attr($items); ?>" /></label></p>
	<?php
		}
	}
	
	add_action('widgets_init', 'hoteller_instagram_widget');
	function hoteller_instagram_widget() {
		register_widget('hoteller_Instagram');
	}
	
	/**
	*	End Instagram Feed Custom Widgets
	**/
	
	/**
	*	Begin Category Posts Custom Widgets
	**/
	
	class hoteller_Cat_Posts extends WP_Widget {
		function __construct() {
			$widget_ops = array('classname' => 'hoteller_Cat_Posts', 'description' => 'Display category\'s post' );
			parent::__construct('hoteller_Cat_Posts', 'Custom Category Posts', $widget_ops);
		}
	
		function widget($args, $instance) {
			extract($args, EXTR_SKIP);
	
			echo stripslashes($before_widget);
			$cat_id = empty($instance['cat_id']) ? 0 : $instance['cat_id'];
			$items = empty($instance['items']) ? 0 : $instance['items'];
			$items = absint($items);
			
			$show_thumb = empty($instance['show_thumb']) ? ' ' : apply_filters('widget_title', $instance['show_thumb']);
			
			if(empty($items))
			{
				$items = 5;
			}
			
			if(!empty($cat_id))
			{
				hoteller_cat_posts($cat_id, $items, TRUE, trim($show_thumb));
			}
	
			echo stripslashes($after_widget);
		}
	
		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			$instance['cat_id'] = strip_tags($new_instance['cat_id']);
			$instance['items'] = strip_tags($new_instance['items']);
			$instance['show_thumb'] = strip_tags($new_instance['show_thumb']);
	
			return $instance;
		}
	
		function form($instance) {
			$instance = wp_parse_args( (array) $instance, array( 'cat_id' => '', 'items' => '', 'show_thumb' => '') );
			$cat_id = strip_tags($instance['cat_id']);
			$items = strip_tags($instance['items']);
			$show_thumb = strip_tags($instance['show_thumb']);
			
			$categories = get_categories('hide_empty=0&orderby=name');
			$wp_cats = array(
				0		=> "Choose a category"
			);
			foreach ($categories as $category_list ) {
				$wp_cats[$category_list->cat_ID] = $category_list->cat_name;
			}
	
	?>
				
				<p><label for="<?php echo esc_attr($this->get_field_id('cat_id')); ?>"><?php esc_html_e('Category', 'hoteller' ); ?>: 
					<select  id="<?php echo esc_attr($this->get_field_id('cat_id')); ?>" name="<?php echo esc_attr($this->get_field_name('cat_id')); ?>">
					<?php
						foreach($wp_cats as $wp_cat_id => $wp_cat)
						{
					?>
							<option value="<?php echo esc_attr($wp_cat_id); ?>" <?php if(esc_attr($cat_id) == $wp_cat_id) { echo 'selected="selected"'; } ?>><?php echo esc_html($wp_cat); ?></option>
					<?php
						}
					?>
					</select>
				</label></p>
				
				<p><label for="<?php echo esc_attr($this->get_field_id('items')); ?>"><?php esc_html_e('Items (default 5)', 'hoteller' ); ?>: <input class="widefat" id="<?php echo esc_attr($this->get_field_id('items')); ?>" name="<?php echo esc_attr($this->get_field_name('items')); ?>" type="text" value="<?php echo esc_attr($items); ?>" /></label></p>
				
				<p><label for="<?php echo esc_attr($this->get_field_id('show_thumb')); ?>"><?php esc_html_e('Display Thumbnails', 'hoteller' ); ?>: <input class="widefat" id="<?php echo esc_attr($this->get_field_id('show_thumb')); ?>" name="<?php echo esc_attr($this->get_field_name('show_thumb')); ?>" type="checkbox" value="1" <?php if(!empty($show_thumb)) { ?>checked<?php } ?> /></label></p>
	<?php
		}
	}
	
	add_action('widgets_init', 'hoteller_cat_posts_widget');
	function hoteller_cat_posts_widget() {
		register_widget('hoteller_Cat_Posts');
	}
	
	/**
	*	End Category Posts Custom Widgets
	**/
	
	/**
	*	Begin Social Profiles Custom Widgets
	**/
	
	class hoteller_Social_Profiles_Posts extends WP_Widget {
		function __construct() {
			$widget_ops = array('classname' => 'hoteller_Social_Profiles_Posts', 'description' => 'Display social profiles' );
			parent::__construct('hoteller_Social_Profiles_Posts', 'Custom Social Profiles', $widget_ops);
		}
	
		function widget($args, $instance) {
			extract($args, EXTR_SKIP);
			$title = $instance['title'];
	
			echo stripslashes($before_widget);
			
			if(!empty($title) && strlen($title) > 0)
			{
				echo stripslashes($before_title);
				echo esc_html($title);
				echo stripslashes($after_title);
			}
			
			$return_html = '<div class="social_wrapper shortcode light small"><ul>';
		
			$pp_facebook_url = get_option('pp_facebook_url');
			if(!empty($pp_facebook_url))
			{
				$return_html.='<li class="facebook"><a target="_blank" title="Facebook" href="'.esc_url($pp_facebook_url).'"><i class="fab fa-facebook"></i></a></li>';
			}
			
			$pp_twitter_username = get_option('pp_twitter_username');
			if(!empty($pp_twitter_username))
			{
				$return_html.='<li class="twitter"><a target="_blank" title="Twitter" href="https://twitter.com/'.$pp_twitter_username.'"><i class="fab fa-twitter"></i></a></li>';
			}
			
			$pp_flickr_username = get_option('pp_flickr_username');
							
			if(!empty($pp_flickr_username))
			{
				$return_html.='<li class="flickr"><a target="_blank" title="Flickr" href="https://flickr.com/people/'.esc_attr($pp_flickr_username).'"><i class="fab fa-flickr"></i></a></li>';
			}
							
			$pp_youtube_url = get_option('pp_youtube_url');
			if(!empty($pp_youtube_url))
			{
				$return_html.='<li class="youtube"><a target="_blank" title="Youtube" href="'.esc_url($pp_youtube_url).'"><i class="fab fa-youtube"></i></a></li>';
			}
		
			$pp_vimeo_username = get_option('pp_vimeo_username');
			if(!empty($pp_vimeo_username))
			{
				$return_html.='<li class="vimeo"><a target="_blank" title="Vimeo" href="https://vimeo.com/'.$pp_vimeo_username.'"><i class="fab fa-vimeo-square"></i></a></li>';
			}
		
			$pp_tumblr_username = get_option('pp_tumblr_username');
			if(!empty($pp_tumblr_username))
			{
				$return_html.='<li class="tumblr"><a target="_blank" title="Tumblr" href="https://'.$pp_tumblr_username.'.tumblr.com"><i class="fab fa-tumblr"></i></a></li>';
			}
							
			$pp_dribbble_username = get_option('pp_dribbble_username');
			if(!empty($pp_dribbble_username))
			{
				$return_html.='<li class="dribbble"><a target="_blank" title="Dribbble" href="https://dribbble.com/'.$pp_dribbble_username.'"><i class="fab fa-dribbble"></i></a></li>';
			}
			
			$pp_linkedin_url = get_option('pp_linkedin_url');
			if(!empty($pp_linkedin_url))
			{
				$return_html.='<li class="linkedin"><a target="_blank" title="Linkedin" href="'.$pp_linkedin_url.'"><i class="fab fa-linkedin"></i></a></li>';
			}
							
			$pp_pinterest_username = get_option('pp_pinterest_username');
			if(!empty($pp_pinterest_username))
			{
				$return_html.='<li class="pinterest"><a target="_blank" title="Pinterest" href="https://pinterest.com/'.$pp_pinterest_username.'"><i class="fab fa-pinterest"></i></a></li>';
			}
							
			$pp_instagram_username = get_option('pp_instagram_username');
			if(!empty($pp_instagram_username))
			{
				$return_html.='<li class="instagram"><a target="_blank" title="Instagram" href="https://instagram.com/'.strtolower($pp_instagram_username).'"><i class="fab fa-instagram"></i></a></li>';
			}
			
			$pp_behance_username = get_option('pp_behance_username');
			if(!empty($pp_behance_username))
			{
				$return_html.='<li class="behance"><a target="_blank" title="Behance" href="https://behance.net/'.$pp_behance_username.'"><i class="fab fa-behance-square"></i></a></li>';
			}
			
			$pp_500px_url = get_option('pp_500px_url');
								
			if(!empty($pp_500px_url))
			{
				$return_html.='<li class="500px"><a target="_blank" title="500px" href="'.$pp_500px_url.'"><i class="fab fa-500px"></i></a></li>';
			}
			
			$pp_tripadvisor_url = get_option('pp_tripadvisor_url');
							
			if(!empty($pp_tripadvisor_url))
			{
				$return_html.='<li class="tripadvisor"><a target="_blank" title="Tripadvisor" href="'.$pp_tripadvisor_url.'"><i class="fab fa-tripadvisor"></i></a></li>';
			}
			
			$return_html.= '</ul></div>';
			
			echo $return_html;
	
			echo stripslashes($after_widget);
		}
	
		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);
	
			return $instance;
		}
	
		function form($instance) {
			$instance = wp_parse_args( (array) $instance, array( 'items' => '', 'title' => '') );
			$title = strip_tags($instance['title']);
	
	?>
			<p><label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title', 'hoteller' ); ?>: <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
	<?php
		}
	}
	
	add_action('widgets_init', 'hoteller_social_profiles_posts_widget');
	function hoteller_social_profiles_posts_widget() {
		register_widget('hoteller_Social_Profiles_Posts');
	}
	
	/**
	*	End Social Profiles Widgets
	**/
	
	
	add_action('add_meta_boxes', function () {
		global $post;
	
		// Check if its a correct post type/types to apply template
		if ( ! in_array( $post->post_type, [ 'header', 'footer', 'megamenu' ] ) ) {
			return;
		}
	
		// Check that a template is not set already
		if ( '' !== $post->page_template ) {
			return;
		}
	
		//Finally set the page template
		$post->page_template = 'elementor_canvas';
		update_post_meta($post->ID, '_wp_page_template', 'elementor_canvas');
	}, 5 );
}