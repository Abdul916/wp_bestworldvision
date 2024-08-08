<?php
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

if(!function_exists('hoteller_get_lazy_img_attr'))
{
	function hoteller_get_lazy_img_attr()
	{
		$tg_enable_lazy_loading = get_theme_mod('tg_enable_lazy_loading');
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
		$tg_enable_lazy_loading = get_theme_mod('tg_enable_lazy_loading');
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

if(!function_exists('hoteller_limit_get_excerpt'))
{
	function hoteller_limit_get_excerpt($excerpt = '', $limit = 50, $string = '...')
	{
		$excerpt = strip_tags($excerpt);
		$excerpt = substr($excerpt, 0, $limit);
		$excerpt = substr($excerpt, 0, strripos($excerpt, " "));
		$excerpt = $excerpt.'...';
		
		return '<p>'.$excerpt.'</p>';
	}
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

if(!function_exists('hoteller_substr'))
{
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
 * Retrieve galleries posts
 *
 * @since 1.0.0
 *
 * @access public
 *
 * @return array galleries
 */
function hoteller_get_contact_forms() {
	//Get all galleries
	$args = array(
		'numberposts' => -1,
		'post_type' => array('wpcf7_contact_form'),
		'orderby'   => 'post_title',
		'order'     => 'ASC',
		'suppress_filters'   => false
	);
	
	$contact_forms_arr = get_posts($args);
	$contact_forms_select = array();
	$contact_forms_select[''] = '';
	
	foreach($contact_forms_arr as $contact_form)
	{
		$contact_forms_select[$contact_form->ID] = $contact_form->post_title;
	}

	return $contact_forms_select;
}

/**
* Add custom link URL for image use in theme landing site
*/
 
function themegoods_attachment_field_credit ($form_fields, $post) {
	$form_fields['themegoods-link-url'] = array(
		'label' => 'Custom Link URL',
		'input' => 'text',
		'value' => esc_url(get_post_meta( $post->ID, 'themegoods_link_url', true )),
	);

	return $form_fields;
}

add_filter( 'attachment_fields_to_edit', 'themegoods_attachment_field_credit', 10, 2 );

/**
* Save values of Photographer Name and URL in media uploader
*/

function themegoods_attachment_field_credit_save ($post, $attachment) {
	if( isset( $attachment['themegoods-link-url'] ) )
update_post_meta( $post['ID'], 'themegoods_link_url', esc_url( $attachment['themegoods-link-url'] ) );

	return $post;
}

add_filter( 'attachment_fields_to_save', 'themegoods_attachment_field_credit_save', 10, 2 );
?>