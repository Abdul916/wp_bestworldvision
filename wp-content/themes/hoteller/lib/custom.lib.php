<?php

//Get page custom fields values
if(!function_exists('hoteller_get_page_postmetas'))
{
	function hoteller_get_page_postmetas() {
		//Get all sidebars
		$theme_sidebar = array(
			'' => '',
			'Page Sidebar' => 'Page Sidebar', 
			'Contact Sidebar' => 'Contact Sidebar', 
			'Blog Sidebar' => 'Blog Sidebar',
		);
		
		$dynamic_sidebar = get_option('pp_sidebar');
		
		if(!empty($dynamic_sidebar))
		{
			foreach($dynamic_sidebar as $sidebar)
			{
				$theme_sidebar[$sidebar] = $sidebar;
			}
		}
		
		/*
			Get gallery list
		*/
		$args = array(
			'numberposts' => -1,
			'post_type' => array('galleries'),
		);
		
		$galleries_arr = get_posts($args);
		$galleries_select = array();
		$galleries_select[0] = '';
		
		foreach($galleries_arr as $gallery)
		{
			$galleries_select[$gallery->ID] = $gallery->post_title;
		}
		
		/*
			Get page templates list
		*/
		if(function_exists('get_page_templates'))
		{
			$page_templates = get_page_templates();
			$page_templates_select = array();
			$page_key = 1;
			
			foreach ($page_templates as $template_name => $template_filename) 
			{
				$page_templates_select[$template_name] = get_template_directory_uri()."/functions/images/page/".basename($template_filename, '.php').".png";
				$page_key++;
			}
		}
		else
		{
			$page_templates_select = array();
		}
		
		/*
			Get all menus available
		*/
		$menus = get_terms('nav_menu');
		$menus_select = array(
			 '' => 'Default Menu'
		);
		foreach($menus as $each_menu)
		{
			$menus_select[$each_menu->slug] = $each_menu->name;
		}
		
		/*
			Get all menus available
		*/
		$menus = get_terms('nav_menu');
		$menus_select = array(
			 '' => 'Default Menu'
		);
		foreach($menus as $each_menu)
		{
			$menus_select[$each_menu->slug] = $each_menu->name;
		}
		
		//Get all footer posts
		$args = array(
			 'post_type'     => 'footer',
			 'post_status'   => array( 'publish' ),
			 'numberposts'   => -1,
			 'orderby'       => 'title',
			 'order'         => 'ASC',
			 'suppress_filters'   => false
		);
		$footers = get_posts($args);
		$tg_footers_select = array();
		$tg_footers_select[''] = '';
		
		if(!empty($footers))
		{
			foreach ($footers as $footer)
			{
				$tg_footers_select[$footer->ID] = $footer->post_title;
			}
		}
		
		//Get all header posts
		$args = array(
			 'post_type'     => 'header',
			 'post_status'   => array( 'publish' ),
			 'numberposts'   => -1,
			 'orderby'       => 'title',
			 'order'         => 'ASC',
			 'suppress_filters'   => false
		);
		$headers = get_posts($args);
		$tg_headers_select = array();
		$tg_headers_select[''] = '';
		
		if(!empty($headers))
		{
			foreach ($headers as $header)
			{
				$tg_headers_select[$header->ID] = $header->post_title;
			}
		}
		
		$hoteller_page_postmetas = array();
		
		$hoteller_page_postmetas_extended = 
			array (
				/*
					Begin Page custom fields
				*/
				array("section" => esc_html__('Page Title', 'hoteller'), "id" => "page_menu_transparent", "type" => "checkbox", "title" => esc_html__('Make Menu Transparent', 'hoteller' ), "description" => esc_html__('Check this option if you want to display main menu in transparent', 'hoteller' )),
				
				array("section" => esc_html__('Page Title', 'hoteller' ), "id" => "page_show_title", "type" => "checkbox", "title" => esc_html__('Hide Default Page Header', 'hoteller' ), "description" => esc_html__('Check this option if you want to hide default page header', 'hoteller' )),
				
				array("section" => esc_html__('Page Tagline', 'hoteller' ), "id" => "page_tagline", "type" => "textarea", "title" => esc_html__('Page Tagline (Optional)', 'hoteller' ), "description" => esc_html__('Enter page tagline. It will displays under page title (*Note: HTML code also support)', 'hoteller' )),
				
				array("section" => esc_html__('Layout', 'hoteller'), "id" => "page_boxed_layout", "type" => "checkbox", "title" => esc_html__('Boxed Layout', 'hoteller' ), "description" => esc_html__('Check this option if you want to enable boxed layout', 'hoteller' )),
				
				array("section" => esc_html__('Select Header (Optional)', 'hoteller' ), "id" => "page_header", "type" => "select", "title" => esc_html__('Page Header (Optional)', 'hoteller' ), "description" => esc_html__('Select this page header content if you want to display header content other than default one', 'hoteller' ), "items" => $tg_headers_select),
				
				array("section" => esc_html__('Select Sticky Header (Optional)', 'hoteller' ), "id" => "page_sticky_header", "type" => "select", "title" => esc_html__('Page Sticky Header (Optional)', 'hoteller' ), "description" => esc_html__('Select this page sticky header content if you want to display header content other than default one', 'hoteller' ), "items" => $tg_headers_select),
				
				array("section" => esc_html__('Select Transparent Header (Optional)', 'hoteller' ), "id" => "page_transparent_header", "type" => "select", "title" => esc_html__('Page Transparent Header (Optional)', 'hoteller' ), "description" => esc_html__('Select this page transparent header content if you want to display transparent header content other than default one', 'hoteller' ), "items" => $tg_headers_select),
				
				array("section" => esc_html__('Select Sidebar (Optional)', 'hoteller' ), "id" => "page_sidebar", "type" => "select", "title" => esc_html__('Page Sidebar (Optional)', 'hoteller' ), "description" => esc_html__('Select this page sidebar to display. To use this option, you have to select page template end with "Sidebar" only', 'hoteller' ), "items" => $theme_sidebar),
				
				array("section" => esc_html__('Select Footer (Optional)', 'hoteller' ), "id" => "page_footer", "type" => "select", "title" => esc_html__('Page Footer (Optional)', 'hoteller' ), "description" => esc_html__('Select this page footer content if you want to display footer content other than default one', 'hoteller' ), "items" => $tg_footers_select),
				
				array("section" => esc_html__('Footer', 'hoteller' ), "id" => "page_hide_footer", "type" => "checkbox", "title" => esc_html__('Hide Footer', 'hoteller' ), "description" => esc_html__('Check this option if you want to hide default page footer', 'hoteller' )),
				
				/*array("section" => esc_html__('Select Menu', 'hoteller' ), "id" => "page_menu", "type" => "select", "title" => esc_html__('Page Menu (Optional)', 'hoteller' ), "description" => esc_html__('Select this page menu if you want to display main menu other than default one', 'hoteller' ), "items" => $menus_select),
				
				array("section" => esc_html__('Footer', 'hoteller' ), "id" => "page_show_footer_sidebar", "type" => "checkbox", "title" => esc_html__('Hide Page Footer Sidebar', 'hoteller' ), "description" => esc_html__('Check this option if you want to hide page footer sidebar', 'hoteller' )),
				
				array("section" => esc_html__('Footer', 'hoteller' ), "id" => "page_show_footer_photostream", "type" => "checkbox", "title" => esc_html__('Hide Page Footer Photostream', 'hoteller' ), "description" => esc_html__('Check this option if you want to hide page footer photostream', 'hoteller' )),
				
				array("section" => esc_html__('Footer', 'hoteller' ), "id" => "page_show_copyright", "type" => "checkbox", "title" => esc_html__('Hide Page Copyright', 'hoteller' ), "description" => esc_html__('Check this option if you want to hide page copyright', 'hoteller' )),*/
			);
		
		
		$hoteller_page_postmetas = $hoteller_page_postmetas + $hoteller_page_postmetas_extended;
			
		return $hoteller_page_postmetas;
	}
}

function hoteller_get_site_domain()
{
	$site_url = site_url();
	$parse = parse_url($site_url);
	
	if(isset($parse['host']) && !empty($parse['host'])) {
		return $parse['host'];
	}
	else {
		return false;
	}
}

/**
* Setup blog pagination function
**/
function hoteller_pagination($pages = '', $range = 4, $class = '')
{
	 $showitems = ($range * 2)+1;
	 
	 $paged = hoteller_get_paged();
	 if(empty($paged)) $paged = 1;
	 
	 if($pages == '')
	 {
	 $wp_query = hoteller_get_wp_query();
	 $pages = $wp_query->max_num_pages;
	 if(!$pages)
	 {
	 $pages = 1;
	 }
	 }
	 
	 if(1 != $pages)
	 {
	 echo "<div class=\"pagination ".esc_attr($class)."\">";
	 if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo; First</a>";
	 if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo; Previous</a>";
	 
	 for ($i=1; $i <= $pages; $i++)
	 {
		 if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
		 {
			 if($paged == $i)
			 {
				 echo "<span class=\"current\">".$i."</span>";
			 }
			 else
			 {
				 echo "<a href='".get_pagenum_link($i)."' class=\"inactive\">".$i."</a>";
			 }
		 }
	 }
	 
	 if ($paged < $pages && $showitems < $pages) echo "<a href=\"".get_pagenum_link($paged + 1)."\">Next &rsaquo;</a>";
	 if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>Last &raquo;</a>";
	 echo "</div>";
	 }
}

function hoteller_get_review($post_id = '', $rating_field = '')
{
	$rating_arr = array();
	if(!empty($post_id) && !empty($rating_field))
	{
		$args = array(
			'status' => 'approve',
			'post_id' => $post_id, // use post_id, not post_ID
		);
		$comments = get_comments($args);
		$count_comments = count($comments);
		$rating_avg = 0;
		$rating_points = 0;
		
		if(!empty($comments) && is_array($comments))
		{
			foreach($comments as $comment)
			{
				$rating = get_comment_meta( $comment->comment_ID, $rating_field, true );
				$rating_points += $rating;
			}
			
			$rating_avg = $rating_points/$count_comments;
		}
		
		$rating_arr = array(
			'average'	=> $rating_avg,
			'points'	=> $rating_points,
			'count'		=> $count_comments,
		);
		
		return $rating_arr;
	}
	else
	{
		return $rating_arr = array(
			'average'	=> 0,
			'points'	=> 0,
			'count'		=> 0,
		);
	}
}
    
/**
*	Setup comment style
**/
function hoteller_comment($comment, $args, $depth) 
{
	$GLOBALS['comment'] = $comment; 
?>
   
	<div class="comment" id="comment-<?php comment_ID() ?>">
		<?php
		$has_avatar = get_avatar($comment,$size='60',$default='' );
		
		if($has_avatar != '')
		{
		?>
		<div class="gravatar">
         	<?php echo get_avatar($comment,$size='60',$default='' ); ?>
      	</div>
      	<?php
      	}
      	?>
      
      	<div class="right <?php if($has_avatar == '') { ?>fullwidth<?php } ?>">
			<?php if ($comment->comment_approved == '0') : ?>
         		<em><?php echo esc_html_e('(Your comment is awaiting moderation.)', 'hoteller') ?></em>
         		<br />
      		<?php endif; ?>
			
			<?php
				if(!empty($comment->comment_author_url))
				{
			?>
					<a href="<?php echo esc_url($comment->comment_author_url); ?>"><h7><?php echo esc_html($comment->comment_author); ?></h7></a>
			<?php
				}
				else
				{
			?>
					<h7><?php echo esc_html($comment->comment_author); ?></h7>
			<?php
				}
			?>
			
			<div class="comment_date"><?php echo date_i18n(HOTELLER_THEMEDATEFORMAT, strtotime($comment->comment_date)); ?> <?php echo esc_html_e('at', 'hoteller') ?> <?php echo date_i18n(HOTELLER_THEMETIMEFORMAT, strtotime($comment->comment_date)); ?></div>
			<?php 
      			if($depth < 3)
      			{
      		?>
      			<?php comment_reply_link(array_merge( $args, array('depth' => $depth,
'reply_text' =>  __('Reply', 'hoteller'), 'login_text' => __('Login to Reply', 'hoteller'), 'max_depth' => $args['max_depth']))) ?>
			<?php
				}
			?>
			<div class="comment_text"/>
      			<?php ' '.comment_text() ?>
	  		</div>
      	</div>
    </div>
    <?php 
        if($depth == 1)
        {
    ?>
    <br class="clear"/>
    <?php
    	}
    ?>
<?php
}


if(!function_exists('hoteller_substr'))
{
	// Substring without losing word meaning and
	// tiny words (length 3 by default) are included on the result.
	// "..." is added if result do not reach original string length
	
	function hoteller_substr($str, $length, $minword = 3)
	{
	    $sub = '';
	    $len = 0;
	    
	    foreach (explode(' ', $str) as $word)
	    {
	        $part = (($sub != '') ? ' ' : '') . $word;
	        $sub .= $part;
	        $len += strlen($part);
	        
	        if (strlen($word) > $minword && strlen($sub) >= $length)
	        {
	            break;
	        }
	    }
	    
	    return $sub . (($len < strlen($str)) ? '...' : '');
	}
}


/**
*	Setup recent posts widget
**/
function hoteller_posts($sort = 'recent', $items = 3, $echo = TRUE, $show_thumb = TRUE) 
{
	$return_html = '';
	
	if($sort == 'recent')
	{
		$args = array(
			'numberposts' => $items,
			'order' => 'DESC',
			'orderby' => 'date',
			'post_type' => 'post',
			'post_status' => 'publish',
			'suppress_filters' => 0,
		);
		$posts = get_posts($args);
		$title = esc_html__('Recent Posts', 'hoteller');
	}
	
	if(!empty($posts))
	{
		$return_html.= '<h2 class="widgettitle"><span>'.$title.'</span></h2>';
		$return_html.= '<ul class="posts blog ';
		
		if($show_thumb)
		{
			$return_html.= 'withthumb ';
		}
		
		$return_html.= '">';
		
		$count_post = count($posts);

			foreach($posts as $post)
			{
				$image_thumb = get_post_meta($post->ID, 'blog_thumb_image_url', true);
				$return_html.= '<li>';
				
				if(!empty($show_thumb) && has_post_thumbnail($post->ID, 'thumbnail'))
				{
					$image_id = get_post_thumbnail_id($post->ID);
					$image_url = wp_get_attachment_image_src($image_id, 'thumbnail', true);
					
					$return_html.= '<div class="post_circle_thumb"><a href="'.get_permalink($post->ID).'"><img src="'.$image_url[0].'" alt="'.esc_attr($post->post_title).'" /></a></div>';
				}
				
				$return_html.= '<a href="'.get_permalink($post->ID).'">'.$post->post_title.'</a><div class="post_attribute">'.date_i18n(HOTELLER_THEMEDATEFORMAT, get_the_time('U', $post->ID)).'</div>';
				$return_html.= '</li>';

			}	

		$return_html.= '</ul>';

	}
	
	if($echo)
	{
		echo stripslashes($return_html);
	}
	else
	{
		return $return_html;
	}
}

function hoteller_cat_posts($cat_id = '', $items = 5, $echo = TRUE, $show_thumb = TRUE) 
{
	$return_html = '';
	$posts = get_posts('numberposts='.$items.'&order=DESC&orderby=date&category='.$cat_id);
	$title = get_cat_name($cat_id);
	$category_link = get_category_link($cat_id);
	$count_post = count($posts);
	
	if(!empty($posts))
	{

		$return_html.= '<h2 class="widgettitle"><span>'.$title.'</span></h2>';
		$return_html.= '<ul class="posts blog ';
		
		if($show_thumb)
		{
			$return_html.= 'withthumb ';
		}
		
		$return_html.= '">';

			foreach($posts as $key => $post)
			{
				$return_html.= '<li>';
			
				if(!empty($show_thumb) && has_post_thumbnail($post->ID, 'thumbnail'))
				{
					$image_id = get_post_thumbnail_id($post->ID);
					$image_url = wp_get_attachment_image_src($image_id, 'thumbnail', true);
					
					$return_html.= '<div class="post_circle_thumb"><a href="'.get_permalink($post->ID).'"><img class="alignleft frame post_thumb" src="'.$image_url[0].'" alt="'.esc_attr($post->post_title).'" /></a></div>';
				}
				
				$return_html.= '<a href="'.get_permalink($post->ID).'">'.hoteller_substr($post->post_title, 50).'</a><div class="post_attribute">'.date_i18n(HOTELLER_THEMEDATEFORMAT, get_the_time('U', $post->ID)).'</div>';
				$return_html.= '</li>';
			}	

		$return_html.= '</ul>';

	}
	
	if($echo)
	{
		echo stripslashes($return_html);
	}
	else
	{
		return $return_html;
	}
}

function hoteller_image_from_description($data) {
    preg_match_all('/<img src="([^"]*)"([^>]*)>/i', $data, $matches);
    return $matches[1][0];
}


function hoteller_select_image($img, $size) {
    $img = explode('/', $img);
    $filename = array_pop($img);

    // The sizes listed here are the ones Flickr provides by default.  Pass the array index in the

    // 0 for square, 1 for thumb, 2 for small, etc.
    $s = array(
        '_s.', // square
        '_q.', // thumb
        '_m.', // small
        '.',   // medium
        '_b.'  // large
    );

    $img[] = preg_replace('/(_(s|t|m|b))?\./i', $s[$size], $filename);
    return implode('/', $img);
}

function hoteller_get_flickr($settings) 
{
	if (!function_exists('MagpieRSS')) {
	    // Check if another plugin is using RSS, may not work
	    require_once ABSPATH . WPINC . '/class-simplepie.php';
	}
	
	if(!isset($settings['items']) || empty($settings['items']))
	{
		$settings['items'] = 9;
	}
	
	// get the feeds
	if ($settings['type'] == "user") { $rss_url = 'https://api.flickr.com/services/feeds/photos_public.gne?id=' . $settings['id'] . '&per_page='.$settings['items'].'&format=rss_200'; }
	elseif ($settings['type'] == "favorite") { $rss_url = 'https://api.flickr.com/services/feeds/photos_faves.gne?id=' . $settings['id'] . '&format=rss_200'; }
	elseif ($settings['type'] == "set") { $rss_url = 'https://api.flickr.com/services/feeds/photoset.gne?set=' . $settings['set'] . '&nsid=' . $settings['id'] . '&format=rss_200'; }
	elseif ($settings['type'] == "group") { $rss_url = 'https://api.flickr.com/services/feeds/groups_pool.gne?id=' . $settings['id'] . '&format=rss_200'; }
	elseif ($settings['type'] == "public" || $settings['type'] == "community") { $rss_url = 'https://api.flickr.com/services/feeds/photos_public.gne?tags=' . $settings['tags'] . '&format=rss_200'; }
	else {
	    print '<strong>No "type" parameter has been setup. Check your settings, or provide the parameter as an argument.</strong>';
	    die();
	}
	
	$flickr_cache_path = HOTELLER_THEMEUPLOAD.'/flickr_'.$settings['id'].'_'.$settings['items'].'.cache';
		
	if(file_exists($flickr_cache_path))
	{
	    $flickr_cache_timer = intval((time()-filemtime($flickr_cache_path))/60);
	}
	else
	{
	    $flickr_cache_timer = 0;
	}
	
	$photos_arr = array();
	
	if(!file_exists($flickr_cache_path) OR $flickr_cache_timer > 15)
	{
		# get rss file
		$feed = new SimplePie();
		$feed->set_feed_url($rss_url);
		$feed->enable_cache(FALSE);
		$feed->init();
		$feed->handle_content_type();
		
		foreach ($feed->get_items() as $key => $item)
		{
			$enclosure = $item->get_enclosure();
			$img = hoteller_image_from_description($item->get_description()); 
			$thumb_url = hoteller_select_image($img, 1);
			$large_url = hoteller_select_image($img, 4);
			
			$photos_arr[] = array(
				'title' => $enclosure->get_title(),
				'thumb_url' => $thumb_url,
				'url' => $large_url,
				'link' => $item->get_link(),
			);
			
			$current = intval($key+1);
			
			if($current == $settings['items'])
			{
				break;
			}
		} 
		
		if(!empty($photos_arr))
		{
			if(file_exists($flickr_cache_path))
			{
			    unlink($flickr_cache_path);
			}
			
			//Writing cache file
			$wp_filesystem = hoteller_get_wp_filesystem();
			$wp_filesystem->put_contents(
			  $flickr_cache_path,
			  serialize($photos_arr),
			  FS_CHMOD_FILE
			);
		}
	}
	else
	{
		$wp_filesystem = hoteller_get_wp_filesystem();
		$file = $wp_filesystem->get_contents($flickr_cache_path);
					
		if(!empty($file))
		{
		    $photos_arr = unserialize($file);			
		}
	}

	return $photos_arr;
}

function hoteller_get_instagram($username, $access_token, $items = 8)
{   
	$wp_filesystem = hoteller_get_wp_filesystem();
	
	$photos_arr = array();

    if(!empty($username) && !empty($access_token))
    {
	    $instagram_cache_path = HOTELLER_THEMEUPLOAD.'/instagram_'.$username.'_'.$items.'.cache';
		
		if(file_exists($instagram_cache_path))
		{
		    $instagram_cache_timer = intval((time()-filemtime($instagram_cache_path))/60);
		}
		else
		{
		    $instagram_cache_timer = 0;
		}
		
		$photos_arr = array();
		
		if(!file_exists($instagram_cache_path) OR $instagram_cache_timer > 15)
		{
			require_once get_template_directory() . "/modules/instagram/instagram.php";
    
		    $isg = new instagramPhp($username, $access_token); 
		    if(!HOTELLER_THEMEDEMO)
		    {
		    	$shots = $isg->getUserMedia(array('count'=> $items)); 
		    }
		    else
		    {
		    	$shots = $isg->getTagMedia(array('count'=> $items), HOTELLER_THEMEDEMOIG); 
		    }
		
			if(is_array($shots->data))
			{
				foreach ($shots->data as $key => $item)
				{
					$small_thumb_url = $item->images->low_resolution->url;
					$thumb_url = $item->images->thumbnail->url;
					$large_url = $item->images->standard_resolution->url;
					
					$photos_arr[] = array(
						'thumb_url' => $thumb_url,
						'url' => $large_url,
						'link' => $item->link,
						'title' => '',
					);
				} 
			}
			
			if(!empty($photos_arr))
			{
				if(file_exists($instagram_cache_path))
				{
				    unlink($instagram_cache_path);
				}
				
				//Writing cache file
				$wp_filesystem->put_contents(
				  $instagram_cache_path,
				  serialize($photos_arr),
				  FS_CHMOD_FILE
				);
			}
			else
			{
				$file = $wp_filesystem->get_contents($instagram_cache_path);
						
				if(!empty($file))
				{
				    $photos_arr = unserialize($file);			
				}
			}
		}
		else
		{
			$file = $wp_filesystem->get_contents($instagram_cache_path);
						
			if(!empty($file))
			{
			    $photos_arr = unserialize($file);			
			}
		}
    } 
    else 
    {
    	echo 'Invalid username and access token';
    }
    
    return $photos_arr;
}

function hoteller_apply_content($pp_content) {
	$pp_content = apply_filters('the_content', $pp_content);
    $pp_content = str_replace(']]>', ']]>', $pp_content);
    
    return $pp_content;
}

function hoteller_get_excerpt_by_id($post_id)
{
	$the_post = get_post($post_id); //Gets post ID
	if(!empty($the_post->post_excerpt))
	{
		$the_excerpt = $the_post->post_excerpt;
	}
	else
	{
		$the_excerpt = $the_post->post_content;
	}
	
	$excerpt_length = 35; //Sets excerpt length by word count
	$the_excerpt = strip_tags(strip_shortcodes($the_excerpt)); //Strips tags and images
	$words = explode(' ', $the_excerpt, $excerpt_length + 1);
	if(count($words) > $excerpt_length) :
	array_pop($words);
	array_push($words, '…');
	$the_excerpt = implode(' ', $words);
	endif;
	$the_excerpt = '<p>' . $the_excerpt . '</p>';
	return $the_excerpt;
}

if(!function_exists('hoteller_get_image_id'))
{
	function hoteller_get_image_id($url) 
	{
		$attachment_id = attachment_url_to_postid($url);
		
		if(!empty($attachment_id))
		{
			return $attachment_id;
		}
		else
		{
			return $url;
		}
	}
}

function hoteller_aasort(&$array, $key) 
{
    $sorter=array();
    $ret=array();
    reset($array);
    foreach ($array as $ii => $va) {
        $sorter[$ii]=$va[$key];
    }
    asort($sorter);
    foreach ($sorter as $ii => $va) {
        $ret[$ii]=$array[$ii];
    }
    $array=$ret;
}

if(!function_exists('get_dynamic_sidebar'))
{
	function get_dynamic_sidebar($index = 1)
	{
		$sidebar_contents = "";
		ob_start();
		dynamic_sidebar($index);
		$sidebar_contents = ob_get_clean();
		return $sidebar_contents;
	}
}

function hoteller_update_urls($options,$oldurl,$newurl)
{	
	$wpdb = hoteller_get_wpdb();
	$results = array();
	$queries = array(
	'content' =>		array("UPDATE $wpdb->posts SET post_content = replace(post_content, %s, %s)",  esc_html__('Content Items (Posts, Pages, Custom Post Types, Revisions)','hoteller') ),
	'excerpts' =>		array("UPDATE $wpdb->posts SET post_excerpt = replace(post_excerpt, %s, %s)", esc_html__('Excerpts','hoteller') ),
	'attachments' =>	array("UPDATE $wpdb->posts SET guid = replace(guid, %s, %s) WHERE post_type = 'attachment'",  esc_html__('Attachments','hoteller') ),
	'links' =>			array("UPDATE $wpdb->links SET link_url = replace(link_url, %s, %s)", esc_html__('Links','hoteller') ),
	'custom' =>			array("UPDATE $wpdb->postmeta SET meta_value = replace(meta_value, %s, %s)",  esc_html__('Custom Fields','hoteller') ),
	'guids' =>			array("UPDATE $wpdb->posts SET guid = replace(guid, %s, %s)",  esc_html__('GUIDs','hoteller') )
	);

	foreach($options as $option){
	    if( $option == 'custom' ){
	    	$n = 0;
	    	$row_count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->postmeta" );
	    	$page_size = 10000;
	    	$pages = ceil( $row_count / $page_size );
	    	
	    	for( $page = 0; $page < $pages; $page++ ) {
	    		$current_row = 0;
	    		$start = $page * $page_size;
	    		$end = $start + $page_size;
	    		$pmquery = "SELECT * FROM $wpdb->postmeta WHERE meta_value <> ''";
	    		$items = $wpdb->get_results( $pmquery );
	    		foreach( $items as $item ){
	    		$value = $item->meta_value;
	    		if( trim($value) == '' )
	    			continue;
	    		
	    			$edited = hoteller_unserialize_replace( $oldurl, $newurl, $value );
	    		
	    			if( $edited != $value ){
	    				$fix = $wpdb->query( $wpdb->prepare( "UPDATE $wpdb->postmeta SET meta_value = %s WHERE meta_id = %s", $edited, $item->meta_id) );
	    				if( $fix )
	    					$n++;
	    			}
	    		}
	    	}
	    	$results[$option] = array($n, $queries[$option][1]);
	    }
	    else{
	    	$result = $wpdb->query( $wpdb->prepare( $queries[$option][0], $oldurl, $newurl) );
	    	$results[$option] = array($result, $queries[$option][1]);
	    }
	}
	
	return $results;			
}


function hoteller_elementor_replace_url($oldurl,$newurl) {

	$from = $oldurl;
	$to = $newurl;

	$wpdb = hoteller_get_wpdb();

	// @codingStandardsIgnoreStart cannot use `$wpdb->prepare` because it remove's the backslashes
	$rows_affected = $wpdb->query(
		"UPDATE {$wpdb->postmeta} " .
		"SET `meta_value` = REPLACE(`meta_value`, '" . str_replace( '/', '\\\/', $from ) . "', '" . str_replace( '/', '\\\/', $to ) . "') " .
		"WHERE `meta_key` = '_elementor_data' AND `meta_value` LIKE '[%' ;" ); // meta_value LIKE '[%' are json formatted
	// @codingStandardsIgnoreEnd
	
	//var_dump($rows_affected);
}


function hoteller_unserialize_replace( $from = '', $to = '', $data = '', $serialised = false ) 
{
    try {
    	if ( is_string( $data ) && ( $unserialized = @unserialize( $data ) ) !== false ) {
    		$data = hoteller_unserialize_replace( $from, $to, $unserialized, true );
    	}
    	elseif ( is_array( $data ) ) {
    		$_tmp = array( );
    		foreach ( $data as $key => $value ) {
    			$_tmp[ $key ] = hoteller_unserialize_replace( $from, $to, $value, false );
    		}
    		$data = $_tmp;
    		unset( $_tmp );
    	}
    	else {
    		if ( is_string( $data ) )
    			$data = str_replace( $from, $to, $data );
    	}
    	if ( $serialised )
    		return serialize( $data );
    } catch( Exception $error ) {
    }
    return $data;
}

function hoteller_get_first_title_word($title) {
	return $title;
}

function hoteller_menu_layout() {
	$tg_menu_layout = get_theme_mod('tg_menu_layout', 'leftalign');

	if(HOTELLER_THEMEDEMO && isset($_GET['menulayout']) && !empty($_GET['menulayout']))
	{
		$tg_menu_layout = $_GET['menulayout'];
	}
	
	return $tg_menu_layout;
}

/**
* hoteller_is_woocommerce_page - Returns true if on a page which uses WooCommerce templates (cart and checkout are standard pages with shortcodes and which are also included)
*
* @access public
* @return bool
*/
function hoteller_is_woocommerce_page() 
{
	if(  function_exists ( "is_woocommerce" ) && is_woocommerce()){
	        return true;
	}
	$woocommerce_keys   =   array ( "woocommerce_shop_page_id") ;
	foreach ( $woocommerce_keys as $wc_page_id ) {
	        if ( get_the_ID () == get_option ( $wc_page_id , 0 ) ) {
	                return true ;
	        }
	}
	return false;
}

function hoteller_check_system()
{
	$has_error = 0;
	$return_html = '<div class="tg_system_status_wrapper">';
	
	$return_html.= '<h4>System Status</h4><br/>';

	//Get max_execution_time
	$max_execution_time = ini_get('max_execution_time');
	$max_execution_time_class = '';
	$max_execution_time_text = '';
	if($max_execution_time < 180)
	{
		$max_execution_time_class = 'tg_error';
		$has_error = 1;
		$max_execution_time_text = '*RECOMMENDED 180';
	}
	$return_html.= '<div class="'.$max_execution_time_class.'">max_execution_time: '.$max_execution_time.' '.$max_execution_time_text.'</div>';
	
	//Get memory_limit
	$memory_limit = ini_get('memory_limit');
	$memory_limit_class = '';
	$memory_limit_text = '';
	if(intval($memory_limit) < 128)
	{
		$memory_limit_class = 'tg_error';
		$has_error = 1;
		$memory_limit_text = '*RECOMMENDED 128M';
	}
	$return_html.= '<div class="'.$memory_limit_class.'">memory_limit: '.$memory_limit.' '.$memory_limit_text.'</div>';
	
	//Get post_max_size
	$post_max_size = ini_get('post_max_size');
	$post_max_size_class = '';
	$post_max_size_text = '';
	if(intval($post_max_size) < 32)
	{
		$post_max_size_class = 'tg_error';
		$has_error = 1;
		$post_max_size_text = '*RECOMMENDED 32M';
	}
	$return_html.= '<div class="'.$post_max_size_class.'">post_max_size: '.$post_max_size.' '.$post_max_size_text.'</div>';
	
	//Get upload_max_filesize
	$upload_max_filesize = ini_get('upload_max_filesize');
	$upload_max_filesize_class = '';
	$upload_max_filesize_text = '';
	if(intval($upload_max_filesize) < 32)
	{
		$upload_max_filesize_class = 'tg_error';
		$has_error = 1;
		$upload_max_filesize_text = '*RECOMMENDED 32M';
	}
	$return_html.= '<div class="'.$upload_max_filesize_class.'">upload_max_filesize: '.$upload_max_filesize.' '.$upload_max_filesize_text.'</div>';
	
	//Get max_input_vars
	$max_input_vars = ini_get('max_input_vars');
	$max_input_vars_class = '';
	$max_input_vars_text = '';
	if(intval($max_input_vars) < 2000)
	{
		$max_input_vars_class = 'tg_error';
		$has_error = 1;
		$max_input_vars_text = '*RECOMMENDED 2000';
	}
	$return_html.= '<div class="'.$max_input_vars_class.'">max_input_vars: '.$max_input_vars.' '.$max_input_vars_text.'</div>';
	
	if(!empty($has_error))
	{
		$return_html.= '<br/><hr/>We are sorry, the demo data could not import properly. It most likely due to PHP configurations on your server. Please fix configuration in System Status which are reported in <span class="tg_error">RED</span>';
	}
	
	$return_html.= '</div>' ;
	
	return $return_html;
}

function hoteller_get_dynamic_sidebar($index = 1)
{
    $sidebar_contents = "";
    ob_start();
    dynamic_sidebar($index);
    $sidebar_contents = ob_get_clean();
    return $sidebar_contents;
}

function hoteller_hex_to_rgb($hex) 
{
	$hex = str_replace("#", "", $hex);
	$color = array();
	
	if(strlen($hex) == 3) {
	    $color['r'] = hexdec(substr($hex, 0, 1) . $r);
	    $color['g'] = hexdec(substr($hex, 1, 1) . $g);
	    $color['b'] = hexdec(substr($hex, 2, 1) . $b);
	}
	else if(strlen($hex) == 6) {
	    $color['r'] = hexdec(substr($hex, 0, 2));
	    $color['g'] = hexdec(substr($hex, 2, 2));
	    $color['b'] = hexdec(substr($hex, 4, 2));
	}
	
	return $color;
}

function hoteller_available_widgets() 
{
	$wp_registered_widget_controls = hoteller_get_registered_widget_controls();

	$widget_controls = $wp_registered_widget_controls;

	$available_widgets = array();

	foreach ( $widget_controls as $widget ) {

		if ( ! empty( $widget['id_base'] ) && ! isset( $available_widgets[$widget['id_base']] ) ) { // no dupes

			$available_widgets[$widget['id_base']]['id_base'] = $widget['id_base'];
			$available_widgets[$widget['id_base']]['name'] = $widget['name'];

		}

	}

	return $available_widgets;
}

function hoteller_import_data( $data ) 
{
	$wp_registered_sidebars = hoteller_get_registered_sidebars();

	// Have valid data?
	// If no data or could not decode
	if ( empty( $data ) || ! is_object( $data ) ) {
		wp_die(
			esc_html__('Import data could not be read. Please try a different file.', 'hoteller' ),
			'',
			array( 'back_link' => true )
		);
	}

	// Get all available widgets site supports
	$available_widgets = hoteller_available_widgets();

	// Get all existing widget instances
	$widget_instances = array();
	foreach ( $available_widgets as $widget_data ) {
		$widget_instances[$widget_data['id_base']] = get_option( 'widget_' . $widget_data['id_base'] );
	}

	// Begin results
	$results = array();

	// Loop import data's sidebars
	foreach ( $data as $sidebar_id => $widgets ) {

		// Skip inactive widgets
		// (should not be in export file)
		if ( 'wp_inactive_widgets' == $sidebar_id ) {
			continue;
		}

		// Check if sidebar is available on this site
		// Otherwise add widgets to inactive, and say so
		if ( isset( $wp_registered_sidebars[$sidebar_id] ) ) {
			$sidebar_available = true;
			$use_sidebar_id = $sidebar_id;
			$sidebar_message_type = 'success';
			$sidebar_message = '';
		} else {
			$sidebar_available = false;
			$use_sidebar_id = 'wp_inactive_widgets'; // add to inactive if sidebar does not exist in theme
			$sidebar_message_type = 'error';
			$sidebar_message = esc_html__('Sidebar does not exist in theme (using Inactive)', 'hoteller' );
		}

		// Result for sidebar
		$results[$sidebar_id]['name'] = ! empty( $wp_registered_sidebars[$sidebar_id]['name'] ) ? $wp_registered_sidebars[$sidebar_id]['name'] : $sidebar_id; // sidebar name if theme supports it; otherwise ID
		$results[$sidebar_id]['message_type'] = $sidebar_message_type;
		$results[$sidebar_id]['message'] = $sidebar_message;
		$results[$sidebar_id]['widgets'] = array();

		// Loop widgets
		foreach ( $widgets as $widget_instance_id => $widget ) {

			$fail = false;

			// Get id_base (remove -# from end) and instance ID number
			$id_base = preg_replace( '/-[0-9]+$/', '', $widget_instance_id );
			$instance_id_number = str_replace( $id_base . '-', '', $widget_instance_id );

			// Does site support this widget?
			if ( ! $fail && ! isset( $available_widgets[$id_base] ) ) {
				$fail = true;
				$widget_message_type = 'error';
				$widget_message = esc_html__('Site does not support widget', 'hoteller' ); // explain why widget not imported
			}

			// Filter to modify settings object before conversion to array and import
			// Leave this filter here for backwards compatibility with manipulating objects (before conversion to array below)
			// Ideally the newer wie_widget_settings_array below will be used instead of this
			$widget = apply_filters( 'wie_widget_settings', $widget ); // object

			// Convert multidimensional objects to multidimensional arrays
			// Some plugins like Jetpack Widget Visibility store settings as multidimensional arrays
			// Without this, they are imported as objects and cause fatal error on Widgets page
			// If this creates problems for plugins that do actually intend settings in objects then may need to consider other approach: https://wordpress.org/support/topic/problem-with-array-of-arrays
			// It is probably much more likely that arrays are used than objects, however
			$widget = json_decode( json_encode( $widget ), true );

			// Does widget with identical settings already exist in same sidebar?
			if ( ! $fail && isset( $widget_instances[$id_base] ) ) {

				// Get existing widgets in this sidebar
				$sidebars_widgets = get_option( 'sidebars_widgets' );
				$sidebar_widgets = isset( $sidebars_widgets[$use_sidebar_id] ) ? $sidebars_widgets[$use_sidebar_id] : array(); // check Inactive if that's where will go

				// Loop widgets with ID base
				$single_widget_instances = ! empty( $widget_instances[$id_base] ) ? $widget_instances[$id_base] : array();
				foreach ( $single_widget_instances as $check_id => $check_widget ) {

					// Is widget in same sidebar and has identical settings?
					if ( in_array( "$id_base-$check_id", $sidebar_widgets ) && (array) $widget == $check_widget ) {

						$fail = true;
						$widget_message_type = 'warning';
						$widget_message = esc_html__('Widget already exists', 'hoteller' ); // explain why widget not imported

						break;

					}

				}

			}

			// No failure
			if ( ! $fail ) {

				// Add widget instance
				$single_widget_instances = get_option( 'widget_' . $id_base ); // all instances for that widget ID base, get fresh every time
				$single_widget_instances = ! empty( $single_widget_instances ) ? $single_widget_instances : array( '_multiwidget' => 1 ); // start fresh if have to
				$single_widget_instances[] = $widget; // add it

					// Get the key it was given
					end( $single_widget_instances );
					$new_instance_id_number = key( $single_widget_instances );

					// If key is 0, make it 1
					// When 0, an issue can occur where adding a widget causes data from other widget to load, and the widget doesn't stick (reload wipes it)
					if ( '0' === strval( $new_instance_id_number ) ) {
						$new_instance_id_number = 1;
						$single_widget_instances[$new_instance_id_number] = $single_widget_instances[0];
						unset( $single_widget_instances[0] );
					}

					// Move _multiwidget to end of array for uniformity
					if ( isset( $single_widget_instances['_multiwidget'] ) ) {
						$multiwidget = $single_widget_instances['_multiwidget'];
						unset( $single_widget_instances['_multiwidget'] );
						$single_widget_instances['_multiwidget'] = $multiwidget;
					}

					// Update option with new widget
					update_option( 'widget_' . $id_base, $single_widget_instances );

				// Assign widget instance to sidebar
				$sidebars_widgets = get_option( 'sidebars_widgets' ); // which sidebars have which widgets, get fresh every time
				$new_instance_id = $id_base . '-' . $new_instance_id_number; // use ID number from new widget instance
				$sidebars_widgets[$use_sidebar_id][] = $new_instance_id; // add new instance to sidebar
				update_option( 'sidebars_widgets', $sidebars_widgets ); // save the amended data

				// Success message
				if ( $sidebar_available ) {
					$widget_message_type = 'success';
					$widget_message = esc_html__('Imported', 'hoteller' );
				} else {
					$widget_message_type = 'warning';
					$widget_message = esc_html__('Imported to Inactive', 'hoteller' );
				}

			}

			// Result for widget instance
			$results[$sidebar_id]['widgets'][$widget_instance_id]['name'] = isset( $available_widgets[$id_base]['name'] ) ? $available_widgets[$id_base]['name'] : $id_base; // widget name or ID if name not available (not supported by site)
			$results[$sidebar_id]['widgets'][$widget_instance_id]['title'] = ! empty( $widget['title'] ) ? $widget['title'] : esc_html__('No Title', 'hoteller' ); // show "No Title" if widget instance is untitled
			$results[$sidebar_id]['widgets'][$widget_instance_id]['message_type'] = $widget_message_type;
			$results[$sidebar_id]['widgets'][$widget_instance_id]['message'] = $widget_message;

		}

	}

	// Return results
	return $results;
}

function hoteller_count_client_images($client_id = '')
{
	if(!empty($client_id))
	{
		$images_counter = 0;
		$client_galleries = get_post_meta($client_id, 'client_galleries', true);
		
		if(is_array($client_galleries) && !empty($client_galleries))
		{
			foreach($client_galleries as $gallery_id)
			{
				$all_photo_arr = get_post_meta($gallery_id, 'wpsimplegallery_gallery', true);
				$images_counter += intval(count($all_photo_arr));
			}
		}
		
		return intval($images_counter);
	}
	else
	{
		return 0;
	}
}

function hoteller_set_map_api()
{
	//Get Google Map API Key
	$pp_googlemap_api_key = get_option('pp_googlemap_api_key');
	
	if(empty($pp_googlemap_api_key))
	{
		wp_enqueue_script("google_maps", "https://maps.googleapis.com/maps/api/js", false, HOTELLER_THEMEVERSION, true);
	}
	else
	{
		wp_enqueue_script("google_maps", "https://maps.googleapis.com/maps/api/js?key=".$pp_googlemap_api_key, false, HOTELLER_THEMEVERSION, true);
	}
}

function hoteller_live_builder_begin_wrapper($item_id = '', $item_type = '')
{
	//Check if live content builder mode
	if(isset($_GET['ppb_live']) && !empty($item_id))
	{
		$return_html = '<div id="live_'.esc_attr($item_id).'" class="ppb_live_edit_wrapper '.esc_attr($item_type).'">';
		$return_html.= '<div class="ppb_live_action">';
		
		if(empty($item_type) OR $item_type != 'ppb_divider')
		{
			$return_html.= '<a href="javascript:;" class="ppb_add_after" data-builder-id="'.esc_attr($item_id).'" title="'.esc_html__('Add Content After This', 'hoteller' ).'"><i class="fa fa-plus"></i></a>';
		
			$return_html.= '<a href="javascript:;" class="ppb_edit" data-builder-id="'.esc_attr($item_id).'" title="'.esc_html__('Edit', 'hoteller' ).'"><i class="fa fa-edit"></i></a>';
			
			$return_html.= '<a href="javascript:;" class="ppb_duplicate" data-builder-id="'.esc_attr($item_id).'" title="'.esc_html__('Duplicate', 'hoteller' ).'"><i class="fa fa-copy"></i></a>';
		}
		
		$return_html.= '<a href="javascript:;" class="ppb_remove" data-builder-id="'.esc_attr($item_id).'" title="'.esc_html__('Remove', 'hoteller' ).'"><i class="fa fa-trash"></i></a>';
		
		$return_html.= '</div>';
		
	    return $return_html;
	}
	else
	{
		return '';
	}
}

function hoteller_live_builder_end_wrapper($item_id = '')
{
	//Check if live content builder mode
	if(isset($_GET['ppb_live']) && !empty($item_id))
	{
	    return '</div>';
	}
	else
	{
		return '';
	}
}

function hoteller_get_post_view($post_id = '', $raw = false)
{
	if(!empty($post_id) && function_exists('pvc_get_post_views'))
	{
		if($raw)
		{
			if(HOTELLER_THEMEDEMO)
			{
				$number_view_format = 544;
			}
			else
			{
				$number_view_format = pvc_get_post_views($post_id);
			}
			
			return $number_view_format;
		}
		else
		{
			if(HOTELLER_THEMEDEMO)
			{
				$number_view_format = '3K';
			}
			else
			{
				$number_view = pvc_get_post_views($post_id);
				
				$precision = 1;
				if ($number_view >= 1000 && $number_view < 1000000) 
				{
				    $number_view_format = number_format($number_view/1000,$precision).'K';
				} 
				else if ($number_view >= 1000000) 
				{
				    $number_view_format = number_format($number_view/1000000,$precision).'M';
				}
				else
				{
				    $number_view_format = $number_view;
				}
			}
		}
		
		return $number_view_format;
	}
	else
	{
		return 0;
	}
}

function hoteller_get_post_share($post_id = '', $raw = false)
{
	if($raw)
	{
		if(function_exists('pssc_all'))
		{
		    if(HOTELLER_THEMEDEMO)
		    {
		    	$number_share_format = 1200;
		    }
		    else
		    {
		    	$number_share_format = pssc_all($post_id);
		    }
		}
		else
		{
			$number_share_format = 0;
		}
	    
	    return $number_share_format;
	}
	else
	{
		if(HOTELLER_THEMEDEMO)
		{
			$number_share = 1200;
			$precision = 1;
			
			if ($number_share >= 1000 && $number_share < 1000000) 
			{
			    $number_share_format = number_format($number_share/1000,$precision).'K';
			} 
			else if ($number_share >= 1000000) 
			{
			    $number_share_format = number_format($number_share/1000000,$precision).'M';
			}
			return $number_share_format;
		}
		else
		{	
			if(!empty($post_id) && function_exists('pssc_all'))
			{
				$number_share = pssc_all($post_id);
				$precision = 1;
				
				if ($number_share >= 1000 && $number_share < 1000000) 
				{
			    	$number_share_format = number_format($number_share/1000,$precision).'K';
			    } 
			    else if ($number_share >= 1000000) 
			    {
			    	$number_share_format = number_format($number_share/1000000,$precision).'M';
			    }
			    else
			    {
				    $number_share_format = $number_share;
			    }
			    return $number_share_format;
			}
			else
			{
				return 0;
			}
		}
	}
}

function hoteller_get_demo_logo($logo_name = 'tg_retina_logo')
{
	$logo_url = '';
	
	return $logo_url;
}

function hoteller_get_demo_url($domain = 'hoteller', $path = '')
{
	$demo_url = 'https://'.$domain.'.themegoods.com/'.$path;
	return $demo_url;
}

function hoteller_themegoods_action() 
{
	if(defined('THEMEGOODS') && THEMEGOODS)
	{
		update_option("pp_verified_envato_hoteller", true);
		update_option("pp_envato_personal_token", '[ThemeGoods Activation]');
	}
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

function hoteller_register_theme($purchase_code = '', $registered_domain = '') {
	if(!empty($purchase_code)) {
		$update_result = update_option("envato_purchase_code_".ENVATOITEMID, $purchase_code);
		
		//If register successfully
		if($update_result) {
			$admin_email = get_option('admin_email');
			$to = $admin_email;
			$subject = '[ThemeGoods] your purchase code '.$purchase_code.' is registered';
			$message = 'Purchase Code: '.$purchase_code.'<br/>';
			
			if(!empty($registered_domain)) {
				$message.= 'Domain: '.$registered_domain.'<br/><br/>';
				$message.= 'In case you want to remove/change registered domain. Please register your account here https://license.themegoods.com/manager/
				<br/><br/>
				Then you will be able to manage/remove your purchase code registration\'s domain from there.';
			}
			
			$headers = array('Content-Type: text/html; charset=UTF-8');
			$mail_result = wp_mail($to, $subject, $message, $headers);
			
			delete_option("envato_purchase_code_".ENVATOITEMID."_removed", true);
		}
		
		return $update_result;
	}
	else {
		return false;
	}
}

function hoteller_unregister_theme() {
	$result = delete_option("envato_purchase_code_".ENVATOITEMID);
	
	//Flag option to display unregister notice message
	update_option("envato_purchase_code_".ENVATOITEMID."_removed", true);
	
	return $result;
}

function hoteller_get_elementor_content($tg_content_default = '')
{
	if (class_exists("\\Elementor\\Plugin")) {
        $pluginElementor = \Elementor\Plugin::instance(); 
        $contentElementor = $pluginElementor->frontend->get_builder_content($tg_content_default);
        return $contentElementor;
    }
    else
    {
	    return '';
    }
}

function hoteller_get_published_pages()
{
	// Define the arguments for the query
	$args = array(
		'post_type' => 'page',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'sort_order' => 'asc',
		'sort_column' => 'post_title',
	);
	
	// Run the query to get all published pages
	$pages = get_pages($args);
	
	// Create an empty array to store the pages
	$pages_array = array(
		''	=> esc_html__('No Template', 'hoteller')
	);
	
	// Loop through each page and add it to the array
	if (!empty($pages)) {
		foreach($pages as $page){
			$page_ID = $page->ID;
			$page_title = $page->post_title;
			$pages_array[$page_ID] = $page_title;
		}
	}

	return $pages_array;
}

function hoteller_starts_with($haystack, $needle) 
{
    // search backwards starting from haystack length characters from the end
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
}

function hoteller_post_has( $type, $post_id ) 
{
	$comments = get_comments('status=approve&type=' . $type . '&post_id=' . $post_id );
	$comments = separate_comments( $comments );
	 
	return 0 < count( $comments[ $type ] );
}

function hoteller_is_elementor()
{
	$elementor_cpt_support = get_option('elementor_cpt_support');
	if(!is_array($elementor_cpt_support))
	{
		$elementor_cpt_support = array();
	}
	
	if (class_exists("\\Elementor\\Plugin") && in_array('mphb_room_type', $elementor_cpt_support)) {
	  $post = hoteller_get_wp_post();
	  return \Elementor\Plugin::$instance->db->is_built_with_elementor($post->ID);
	}
	else
	{
		return false;
	}
}

if(!function_exists('hoteller_get_lazy_img_attr'))
{
	function hoteller_get_lazy_img_attr()
	{
		$tg_enable_lazy_loading = get_theme_mod('tg_enable_lazy_loading', true);
		$return_attr = array('class' => '','source' => 'src');
		
		if(!empty($tg_enable_lazy_loading))
		{
			$return_attr = array('class' => 'lazy','source' => 'data-src');
		}
		
		return $return_attr;
	}
}
	
if(!function_exists('hoteller_get_blank_img_attr'))
{
	function hoteller_get_blank_img_attr()
	{
		$tg_enable_lazy_loading = get_theme_mod('tg_enable_lazy_loading', true);
		$return_attr = '';
		
		if(!empty($tg_enable_lazy_loading))
		{
			$return_attr = 'src=""';
		}
		
		return $return_attr;
	}
}

if(!function_exists('hoteller_get_post_format_icon'))
{
	function hoteller_get_post_format_icon($post_id = '')
	{
		$return_html = '';
		
		if(!empty($post_id))
		{
			$post_format = get_post_format($post_id);
			
			if($post_format == 'video')
			{
				$return_html = '<div class="post_type_icon"><i class="fa fa-play"></i></div>';	
			}
		}
		
		return $return_html;
	}
}

if(!function_exists('hoteller_write_log'))
{
	function hoteller_write_log($log)  
	{
	    if (true === WP_DEBUG) 
	    {
	        if ( is_array($log) || is_object($log) ) 
	        {
	            error_log(print_r($log, true));
	        } else 
	        {
	            error_log($log);
	        }
	    }
	}
}

if(!function_exists('hoteller_get_service_price'))
{
	function hoteller_get_service_price($post_id = '')
	{
		$return_html = '';
		
		if(!empty($post_id))
		{
			$mphb_price = get_post_meta($post_id, 'mphb_price', true);
			
			if(empty($mphb_price))
			{
				$return_html.= '('.esc_html__('Free', 'hoteller' ).')';
			}
			else
			{
				$mphb_currency_symbol = get_option('mphb_currency_symbol');
				$return_html.= '('.$mphb_price.')';
			}
		}
		
		return $return_html;
	}
}

function hoteller_is_elementor_page()
{
  	global $post;
  	return \Elementor\Plugin::$instance->db->is_built_with_elementor($post->ID);
}

function hoteller_get_attachment_url_by_slug( $slug ) {
	  $args = array(
		'post_type' => 'attachment',
		'name' => sanitize_title($slug),
		'posts_per_page' => 1,
		'post_status' => 'inherit',
	  );
	  $_header = get_posts( $args );
	  $header = $_header ? array_pop($_header) : null;
	  return $header ? wp_get_attachment_url($header->ID) : '';
}
?>