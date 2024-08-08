<?php
/**
 * The main template file for display page.
 *
 * @package WordPress
*/

//Check if single attachment page
if($post->post_type == 'attachment')
{
	get_template_part("single-attachment");
	die;
}

/**
*	Get Current page object
**/
if(!is_null($post))
{
	$page_obj = get_page($post->ID);
}

$current_page_id = '';

/**
*	Get current page id
**/

if(!is_null($post) && isset($page_obj->ID))
{
    $current_page_id = $page_obj->ID;
}

get_header(); 
?>

<?php
    //Include custom header feature
	get_template_part("/templates/template-header");
?>
    <div class="inner">
    	<!-- Begin main content -->
    	<div class="inner_wrapper">
    		<div class="sidebar_content full_width">
    		<?php 
    			if ( have_posts() ) {
    		    while ( have_posts() ) : the_post(); ?>		
    	
    		    <?php 
	    		    the_content(); 
	    		    break;  
	    		?>

    		<?php endwhile; 
	    		
	    		wp_link_pages(
	    			array(
						'before'           => '<br class="clear"/><p>' . esc_html__( 'Pages:', 'hoteller' ),
						'after'            => '</p>',
					)
				);
    		}

			if (comments_open($post->ID) OR hoteller_post_has('pings', $post->ID)) 
			{
			?>
			<div class="fullwidth_comment_wrapper">
				<?php comments_template( '', true ); ?>
			</div>
			<?php
			}
			?>
    		</div>
    	</div>
    	<!-- End main content -->
    </div>
    <br class="clear"/>
</div>
<?php get_footer(); ?>