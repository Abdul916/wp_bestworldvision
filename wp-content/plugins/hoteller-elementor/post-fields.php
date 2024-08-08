<?php
add_filter( 'manage_posts_columns', 'rt_add_gravatar_col');
function rt_add_gravatar_col($cols) {
	$cols['thumbnail'] = esc_html__('Thumbnail', 'hoteller-elementor');
	return $cols;
}

add_action( 'manage_posts_custom_column', 'rt_get_author_gravatar');
function rt_get_author_gravatar($column_name ) {
	if ( $column_name  == 'thumbnail'  ) {
		echo get_the_post_thumbnail(get_the_ID(), array(100, 100));
	}
}

/*
	Get post layouts
*/
$post_layout_select = array();
$post_layout_select = array(
	'With Right Sidebar' => 'With Right Sidebar',
	'With Left Sidebar' => 'With Left Sidebar',
	'Fullwidth' => 'Fullwidth',
);

/*
	Begin creating custom fields
*/

global $postmetas;

$postmetas = 
	array (
		'post' => array(
			array("id" => "post_layout", "type" => "select", "title" => esc_html__('Post Layout', 'hoteller-elementor' ), "description" => esc_html__('You can select layout of this single post page.', 'hoteller-elementor' ), "items" => $post_layout_select),
			
			array("id" => "page_menu_transparent", "type" => "checkbox", "title" => esc_html__('Make Menu Transparent', 'hoteller-elementor' ), "description" => esc_html__('Check this option if you want to display main menu in transparent', 'hoteller-elementor' )),
			
			array("id" => "post_video_embed", "type" => "textarea", "title" => esc_html__('Video Embed Code', 'hoteller-elementor' ), "description" => esc_html__('Insert Youtube or Vimeo embed code.', 'hoteller-elementor' )),
		),
		
		'mphb_room_type' => array(	
			array("id" => "custom_booking_url", "type" => "text", "title" => esc_html__('Custom Booking URL (Optional)', 'hoteller-elementor' ), "description" => esc_html__('Enter URL for booking option so when user click on book button. It will opens this entered URL. Recommend if you are using 3rd party booking system.', 'hoteller-elementor' )),
			
			array("id" => "custom_pricing", "type" => "text", "title" => esc_html__('Custom Pricing (Optional)', 'hoteller-elementor' ), "description" => esc_html__('Enter pricing for this accommodation. It will displays this pricing instead of getting from room rates setting.', 'hoteller-elementor' )),
		),
);

function hoteller_create_meta_box() {

	global $postmetas;
	
	if(!isset($_GET['post_type']) OR empty($_GET['post_type']))
	{
		if(isset($_GET['post']) && !empty($_GET['post']))
		{
			$post_obj = get_post($_GET['post']);
			$_GET['post_type'] = $post_obj->post_type;
		}
		else
		{
			$_GET['post_type'] = 'post';
		}
	}
	
	if ( function_exists('add_meta_box') && isset($postmetas) && count($postmetas) > 0 ) {  
		foreach($postmetas as $key => $postmeta)
		{
			if($_GET['post_type']==$key && !empty($postmeta))
			{
				add_meta_box( 'metabox', esc_html__("Options", 'hoteller-elementor'), 'hoteller_new_meta_box', $key, 'normal', 'high' );
			}
		}
	}

}  

function hoteller_new_meta_box() {
	global $post, $postmetas, $gallery_template_urls;
	
	if(!isset($_GET['post_type']) OR empty($_GET['post_type']))
	{
		if(isset($_GET['post']) && !empty($_GET['post']))
		{
			$post_obj = get_post($_GET['post']);
			$_GET['post_type'] = $post_obj->post_type;
		}
		else
		{
			$_GET['post_type'] = 'post';
		}
	}

	echo '<input type="hidden" name="pp_meta_form" id="pp_meta_form" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	
	$meta_section = '';

	foreach ( $postmetas as $key => $postmeta ) {
	
		if($_GET['post_type'] == $key)
		{
		
			foreach ( $postmeta as $postmeta_key => $each_meta ) {
		
				$meta_id = $each_meta['id'];
				$meta_title = $each_meta['title'];
				$meta_description = $each_meta['description'];
				
				if(isset($postmeta['section']))
				{
					$meta_section = $postmeta['section'];
				}
				
				$meta_type = '';
				if(isset($each_meta['type']))
				{
					$meta_type = $each_meta['type'];
				}
				
				echo '<div id="post_option_'.strtolower($each_meta['id']).'" class="pp_meta_option key'.intval($postmeta_key+1).' '.$meta_type.'">';
				echo "<div class=\"meta_title_wrapper\">";
				echo "<strong>".$meta_title."</strong>";
				
				echo "<div class='pp_widget_description'>$meta_description</div>";
				
				echo "</div>";
				echo "<div class=\"meta_title_field\">";
				
				if ($meta_type == 'checkbox') {
					$checked = get_post_meta($post->ID, $meta_id, true) == '1' ? "checked" : "";
					echo "<input type='checkbox' name='$meta_id' id='$meta_id' class='iphone_checkboxes' value='1' $checked />";
				}
				else if ($meta_type == 'select') {
					echo "<select name='$meta_id' id='$meta_id'>";
					
					if(!empty($each_meta['items']))
					{
						foreach ($each_meta['items'] as $key => $item)
						{
							echo '<option value="'.$key.'"';
							
							if($key == get_post_meta($post->ID, $meta_id, true))
							{
								echo ' selected ';
							}
							
							echo '>'.$item.'</option>';
						}
					}
					
					echo "</select>";
				}
				else if ($meta_type == 'template') {
					$current_value = get_post_meta($post->ID, $meta_id, true);
					echo "<input type='hidden' name='$meta_id' id='$meta_id' value='$current_value' />";
					echo "<ul name=\"".$meta_id."_list\" id=\"".$meta_id."_list\" class=\"meta_template_list\">";
					
					if(!empty($each_meta['items']))
					{
						foreach ($each_meta['items'] as $key => $template_name)
						{
							echo '<li data-parent="'.$meta_id.'" data-value="'.esc_attr($key).'" ';
							
							if($key == $current_value)
							{
								echo 'class="checked"';
							}
							
							echo '>';
							
							if(isset($gallery_template_urls[$key]))
							{
								echo '<a href="'.esc_url($gallery_template_urls[$key]).'" target="_blank" title="View Sample" class="tooltipster meta_template_link"><i class="fa fa-external-link"></i></a>';
							}
							echo '<div class="template_title">'.$key.'</div>';
							echo '</li>';
						}
					}
					
					echo "</ul>";
				}
				else if ($meta_type == 'checkboxes') {
					if(!empty($each_meta['items']))
					{
						$checkboxes_post_values = get_post_meta($post->ID, $meta_id, true);
						
						echo '<div class="wp-tab-panel"><ul id="'.$meta_id.'_checklist">';
					
						foreach ($each_meta['items'] as $key => $item)
						{
							echo '<li>';
							echo '<input name="'.$meta_id.'[]" id="'.$meta_id.'[]" type="checkbox"  value="'.$key.'"';
							
							if(is_array($checkboxes_post_values) && !empty($checkboxes_post_values) && in_array($key, $checkboxes_post_values))
							{
								echo ' checked ';
							}
							
							echo '/>'.$item;
							echo '</li>';
						}
						
						echo '</ul></div>';
					}
				}
				else if ($meta_type == 'adding_list') {
					
					echo '<table id="'.$meta_id.'_sortable" class="adding_list_sortable">';
		
					echo '<thead>';
					echo '<tr>';
					
					echo '<th width="5%"></th>';
					echo '<th width="90%">'.esc_html__("Title", 'hoteller-elementor').'</th>';
					echo '<th width="5%"></th>';
					
					echo '</tr>';
					echo '</thead>';
					
					echo '<tbody>';
					
					$adding_list_arr = get_post_meta($post->ID, $meta_id, true);
					
					if(!empty($adding_list_arr) && is_array($adding_list_arr))
					{
						foreach($adding_list_arr as $key => $adding_list_item)
						{
							echo '<tr>';
							echo '<td class="sortable_handle"><span class="dashicons dashicons-menu"></span></td>';
							echo '<td><input type="text" class="widefat" name="'.$meta_id.'[]" value="'.esc_attr($adding_list_item).'"></td>';
							echo '<td><a class="button adding_list_remove_row" href="javascript:;"><span class="dashicons dashicons-no-alt"></span></a></td>';
							echo '</tr>';
						}
					}
					else
					{
						echo '<tr>';
							echo '<td class="sortable_handle"><span class="dashicons dashicons-menu"></span></td>';
							echo '<td><input type="text" class="widefat" name="'.$meta_id.'[]" value=""></td>';
							echo '<td><a class="button adding_list_remove_row" href="javascript:;"><span class="dashicons dashicons-no-alt"></span></a></td>';
							echo '</tr>';
					}
					
					echo '</tbody>';
					echo '</table>';
		
					echo '<a id="'.$meta_id.'_add_row" class="button adding_list_add_row" data-target="'.$meta_id.'_sortable" data-metaid="'.$meta_id.'" href="javascript:;">'.esc_html__("Add another", 'hoteller-elementor').'</a>';
					
					echo '<script>';
					echo '
						jQuery("#'.$meta_id.'_add_row").on( "click", function(){
							var rowHTML = \'\';
							rowHTML+= \'<tr>\';
							rowHTML+= \'<td class="sortable_handle"><span class="dashicons dashicons-menu"></span></td>\';
							rowHTML+= \'<td><input type="text" class="widefat" name="'.$meta_id.'[]"></td>\';
							rowHTML+= \'<td><a class="button adding_list_remove_row" href="javascript:;"><span class="dashicons dashicons-no-alt"></span></a></td>\';
							rowHTML+= \'</tr>\';
							
							jQuery("#'.$meta_id.'_sortable").find("tbody:last").append(rowHTML);
							addingListRemoveEvent();
						});
					';
					echo '</script>';
				}
				else if ($meta_type == 'file') { 
				    echo "<input type='text' name='$meta_id' id='$meta_id' class='' value='".get_post_meta($post->ID, $meta_id, true)."' style='width:calc(100% - 75px)' /><input id='".$meta_id."_button' name='".$meta_id."_button' type='button' value='Upload' class='metabox_upload_btn button' readonly='readonly' rel='".$meta_id."' style='margin:0 0 0 5px' />";
				}
				else if ($meta_type == 'textarea') {
					if(isset($postmeta[$postmeta_key]['sample']))
					{
						echo "<textarea name='$meta_id' id='$meta_id' class=' hint' style='width:100%' rows='7' title='".$postmeta[$postmeta_key]['sample']."'>".get_post_meta($post->ID, $meta_id, true)."</textarea>";
					}
					else
					{
						echo "<textarea name='$meta_id' id='$meta_id' class='' style='width:100%' rows='7'>".get_post_meta($post->ID, $meta_id, true)."</textarea>";
					}
				}			
				else {
					if(isset($postmeta[$postmeta_key]['sample']))
					{
						echo "<input type='text' name='$meta_id' id='$meta_id' class='' title='".$postmeta[$postmeta_key]['sample']."' value='".get_post_meta($post->ID, $meta_id, true)."' style='width:100%' />";
					}
					else
					{
						echo "<input type='text' name='$meta_id' id='$meta_id' class='' value='".get_post_meta($post->ID, $meta_id, true)."' style='width:100%' />";
					}
				}
				
				echo "</div>";
				echo '</div>';
			}
		}
	}

}

function hoteller_save_postdata( $post_id ) {

	global $postmetas;

	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times

	if ( isset($_POST['pp_meta_form']) && !wp_verify_nonce( $_POST['pp_meta_form'], plugin_basename(__FILE__) )) {
		return $post_id;
	}

	// verify if this is an auto save routine. If it is our form has not been submitted, so we dont want to do anything

	if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || (defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']))
        return;

	// Check permissions

	if ( isset($_POST['post_type']) && 'page' == $_POST['post_type'] ) {
		if ( !current_user_can( 'edit_page', $post_id ) )
			return $post_id;
		} else {
		if ( !current_user_can( 'edit_post', $post_id ) )
			return $post_id;
	}

	// OK, we're authenticated

	if ( $parent_id = wp_is_post_revision($post_id) )
	{
		$post_id = $parent_id;
	}
	
	if (isset($_POST['pp_meta_form'])) 
	{
		foreach ( $postmetas as $postmeta ) {
			foreach ( $postmeta as $each_meta ) {
				
				if (isset($_POST[$each_meta['id']]) && $_POST[$each_meta['id']]) {
					hoteller_update_custom_meta($post_id, $_POST[$each_meta['id']], $each_meta['id']);
				}
				
				if (isset($_POST[$each_meta['id']]) && $_POST[$each_meta['id']] == "") {
					delete_post_meta($post_id, $each_meta['id']);
				}
				
				if (!isset($_POST[$each_meta['id']])) {
					delete_post_meta($post_id, $each_meta['id']);
				}
			
			}
		}
	}
}

function hoteller_update_custom_meta($postID, $newvalue, $field_name) {

	if (isset($_POST['pp_meta_form'])) 
	{
		if (!get_post_meta($postID, $field_name)) {
			add_post_meta($postID, $field_name, $newvalue);
		} else {
			update_post_meta($postID, $field_name, $newvalue);
		}
	}

}

//init

add_action('admin_menu', 'hoteller_create_meta_box'); 
add_action('save_post', 'hoteller_save_postdata'); 	
?>