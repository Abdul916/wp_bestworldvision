<?php
/**
 * The main template file for display single post page.
 *
 * @package WordPress
*/

get_header(); 

$hoteller_topbar = hoteller_get_topbar();

/**
*	Get current page id
**/

$current_page_id = $post->ID;

//Include custom header feature
get_template_part("/templates/template-post-header");
?>
    
    <div class="inner">

    	<!-- Begin main content -->
    	<div class="inner_wrapper">

    		<div class="sidebar_content full_width blog_f">
					
<?php
if (have_posts()) : while (have_posts()) : the_post();
?>
						
<!-- Begin each blog post -->
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="post_wrapper">

	    <?php
		    //Get video embed code
		    $post_video_embed = get_post_meta($post->ID, 'post_video_embed', true);
		    if(!empty($post_video_embed))
		    {
		?>
				<div class="video_wrapper"><?php echo trim($post_video_embed); ?></div>
		<?php
		    }
		    
		    the_content();
		?>
		<br class="clear"/>
		<?php
		    wp_link_pages();

			//Get share button
			get_template_part("/templates/template-post-tags");
		?>
	    
	</div>
	
	<?php
		//Get post author
		get_template_part("/templates/template-author");
				
	    //Get post related
		get_template_part("/templates/template-post-related");
	?>

</div>
<!-- End each blog post -->

<?php
if (comments_open($post->ID) OR hoteller_post_has('pings', $post->ID)) 
{
?>
<div class="fullwidth_comment_wrapper sidebar">
	<?php comments_template( '', true ); ?>
</div>
<?php
}
?>

<?php
//Get post navigation
get_template_part("/templates/template-post-navigation");
?>

<?php endwhile; endif; ?>
						
    	</div>
    
    </div>
    <!-- End main content -->
</div>

<br class="clear"/>
</div>
<?php get_footer(); ?>