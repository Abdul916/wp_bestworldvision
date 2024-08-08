<?php
/**
 * The main template file for display archive page.
 *
 * @package WordPress
*/

//Get archive page layout setting
$tg_blog_archive_layout = get_theme_mod('tg_blog_archive_layout', 'blog-r');
get_template_part($tg_blog_archive_layout);
?>