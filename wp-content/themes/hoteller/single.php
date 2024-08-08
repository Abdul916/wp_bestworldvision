<?php
/**
 * The main template file for display single post page.
 *
 * @package WordPress
*/

/**
*	Get current page id
**/

$current_page_id = $post->ID;

if(isset($_GET['elementor_library']) && !empty($_GET['elementor_library']))
{
	get_template_part("page");
	exit;
}
else if($post->post_type == 'elementor_library')
{
	get_template_part("page-canvas");

	exit;
}
else if($post->post_type == 'mphb_room_type')
{
	if(hoteller_is_elementor($current_page_id))
	{
		//Remove all filters from MotoPress booking plugin to support Elementor
		remove_all_actions('mphb_render_single_room_type_metas');
		
		get_template_part("page");
	}
	else
	{
		get_template_part("single-room");
	}
	
	exit;
}
else
{
	$post_layout = get_post_meta($post->ID, 'post_layout', true);
	
	switch($post_layout)
	{
		case 'With Right Sidebar':
			get_template_part("single-post-r");
			exit;
		break;
		
		case 'With Left Sidebar':
			get_template_part("single-post-l");
			exit;
		break;
		
		case 'Fullwidth':
		default:
			get_template_part("single-post-f");
			exit;
		break;
	}
}
?>