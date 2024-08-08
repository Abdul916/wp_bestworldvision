<?php
/**
 * The main template file for display category page.
 *
 * @package WordPress
*/
	
//Get category page layout setting
$tg_blog_category_layout = get_theme_mod('tg_blog_category_layout', 'blog-r');
get_template_part($tg_blog_category_layout);
?>