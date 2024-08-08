<?php
/**
 * The main template file for display tag page.
 *
 * @package WordPress
*/
	
//Get tag page layout setting
$tg_blog_tag_layout = get_theme_mod('tg_blog_tag_layout', 'blog-r');
get_template_part($tg_blog_tag_layout);
?>