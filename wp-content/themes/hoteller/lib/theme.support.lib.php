<?php
if ( function_exists( 'add_theme_support' ) ) {
	// Setup thumbnail support
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'custom-background' );
}

if ( function_exists( 'add_image_size' ) ) { 
	//Setup image grid dimensions
	$pp_gallery_grid_image_width = get_option('pp_gallery_grid_image_width');
	if(empty($pp_gallery_grid_image_width))
	{
		$pp_gallery_grid_image_width = 700;
	}
	$pp_gallery_grid_image_height = get_option('pp_gallery_grid_image_height');
	if(empty($pp_gallery_grid_image_height))
	{
		$pp_gallery_grid_image_height = 466;
	}
	$image_crop = true;
	if($pp_gallery_grid_image_height == 9999)
	{
		$image_crop = false;
	}
	add_image_size( 'hoteller-gallery-grid', intval($pp_gallery_grid_image_width), intval($pp_gallery_grid_image_height), $image_crop );
	
	
	//Setup image masonry dimensions
	$pp_gallery_masonry_image_width = get_option('pp_gallery_masonry_image_width');
	if(empty($pp_gallery_masonry_image_width))
	{
		$pp_gallery_masonry_image_width = 440;
	}
	$pp_gallery_masonry_image_height = get_option('pp_gallery_masonry_image_height');
	if(empty($pp_gallery_masonry_image_height))
	{
		$pp_gallery_masonry_image_height = 9999;
	}
	$image_crop = true;
	if($pp_gallery_masonry_image_height == 9999)
	{
		$image_crop = false;
	}
	add_image_size( 'hoteller-gallery-masonry', intval($pp_gallery_masonry_image_width), intval($pp_gallery_masonry_image_height), $image_crop );

	
	//Setup image grid list dimensions
	$pp_gallery_list_image_width = get_option('pp_gallery_list_image_width');
	if(empty($pp_gallery_list_image_width))
	{
		$pp_gallery_list_image_width = 610;
	}
	$pp_gallery_list_image_height = get_option('pp_gallery_list_image_height');
	if(empty($pp_gallery_list_image_height))
	{
		$pp_gallery_list_image_height = 610;
	}
	$image_crop = true;
	if($pp_gallery_list_image_height == 9999)
	{
		$image_crop = false;
	}
	add_image_size( 'hoteller-gallery-list', intval($pp_gallery_list_image_width), intval($pp_gallery_list_image_height), $image_crop );

	add_image_size( 'hoteller-album-grid', 660, 913, true );
	
	
	//Setup image blog dimensions
	$pp_blog_image_width = get_option('pp_blog_image_width');
	if(empty($pp_blog_image_width))
	{
		$pp_blog_image_width = 960;
	}
	$pp_blog_image_height = get_option('pp_blog_image_height');
	if(empty($pp_blog_image_height))
	{
		$pp_blog_image_height = 604;
	}
	$image_crop = true;
	if($pp_blog_image_height == 9999)
	{
		$image_crop = false;
	}
	add_image_size( 'hoteller-blog', intval($pp_blog_image_width), intval($pp_blog_image_height), $image_crop );
}

add_action( 'after_setup_theme', 'hoteller_woocommerce_support' );

function hoteller_woocommerce_support() {
    add_theme_support( 'woocommerce' );
}

add_theme_support( 'post-formats', array('video') );

/* Flush rewrite rules for custom post types. */
add_action( 'after_switch_theme', 'flush_rewrite_rules' );
?>