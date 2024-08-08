<?php
/**
 * The main template file.
 *
 * @package WordPress
 */

get_header();

//Include custom header feature
get_template_part("/templates/template-header");

?>
    
    <div class="inner">

    	<!-- Begin main content -->
    	<div class="inner_wrapper">

    		<div class="sidebar_content">
					
<?php
if (have_posts()) : while (have_posts()) : the_post();
	$image_thumb = '';
								
	if(has_post_thumbnail(get_the_ID(), 'large'))
	{
	    $image_id = get_post_thumbnail_id(get_the_ID());
	    $image_thumb = wp_get_attachment_image_src($image_id, 'large', true);
	}
?>

<!-- Begin each blog post -->
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="post_wrapper">
		
		<?php
		    if(!empty($image_thumb))
		    {
		     $small_image_url = wp_get_attachment_image_src($image_id, 'hoteller-blog', true);
		?>
		    <div class="post_img static">
			    <div class="post_img_hover">
			     	<?php the_post_thumbnail('hoteller-blog'); ?>
			     	<?php echo hoteller_get_post_format_icon(get_the_ID()); ?>
			     	<a href="<?php the_permalink(); ?>"></a>
			    </div>
		    </div>
		<?php
		    }
		?>
	    
	    <div class="post_content_wrapper">
		    
		    <div class="post_header">
			    <?php
					//Get blog categories
					$tg_blog_cat = get_theme_mod('tg_blog_cat');
					
					if(!empty($tg_blog_cat))
					{
				?>
			    <div class="post_detail single_post">
			    	<span class="post_info_cat">
						<?php
						   //Get Post's Categories
						   $post_categories = wp_get_post_categories($post->ID);
						   
						   $count_categories = count($post_categories);
						   $i = 0;
						   
						   if(!empty($post_categories))
						   {
						      	foreach($post_categories as $key => $c)
						      	{
						      		$cat = get_category( $c );
						?>
						      	<a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>"><?php echo esc_html($cat->name); ?></a>
						<?php
							   		if(++$i != $count_categories) 
							   		{
							   			echo '&nbsp;,&nbsp;';
							   		}
						      	}
						   }
						?>
			    	</span>
			 	</div>
			 	<?php
				 	}
				 ?>
				<div class="post_header_title">
				    <h5><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h5>
				</div>
			</div>
	    
			<div class="post_header_wrapper">
				<?php
			    	$tg_blog_display_full = get_theme_mod('tg_blog_display_full', false);
			    	
			    	if(!empty($tg_blog_display_full))
			    	{
			    		the_content();
			    	}
			    	else
			    	{
			    		the_excerpt();
			    	}
			    ?>
			    <?php
					//Get blog date
					$tg_blog_date = get_theme_mod('tg_blog_date', true);
					if(!empty($tg_blog_date))
					{
				?>
			    <div class="post_button_wrapper">
			    	<div class="post_attribute">
					    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php echo date_i18n(HOTELLER_THEMEDATEFORMAT, get_the_time('U')); ?></a>
					</div>
			    </div>
			    <?php
				    }
				?>
			</div>
	    </div>
	    
	</div>

</div>
<br class="clear"/>
<!-- End each blog post -->

<?php endwhile; endif; ?>

	    	<?php
	    	if($wp_query->max_num_pages > 1)
			{
				if (function_exists("hoteller_pagination")) 
				{
				    hoteller_pagination($wp_query->max_num_pages);
				}
				else
				{
				?>
				    <div class="pagination"><p><?php posts_nav_link(' '); ?></p></div>
				<?php
				}
			?>
			<div class="pagination_detail">
		     	<?php
		     		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		     	?>
		     	<?php esc_html_e('Page', 'hoteller' ); ?> <?php echo esc_html($paged); ?> <?php esc_html_e('of', 'hoteller' ); ?> <?php echo esc_html($wp_query->max_num_pages); ?>
		     </div>
		     <?php
		     }
		     ?>
    		
			</div>
    	
    		<div class="sidebar_wrapper">
    		
    			<div class="sidebar_top"></div>
    		
    			<div class="sidebar">
    			
    				<div class="content">

    					<?php 
						if (is_active_sidebar('page-sidebar')) { ?>
		    	    		<ul class="sidebar_widget">
		    	    		<?php dynamic_sidebar('page-sidebar'); ?>
		    	    		</ul>
		    	    	<?php } ?>
    				
    				</div>
    		
    			</div>
    			<br class="clear"/>
    	
    			<div class="sidebar_bottom"></div>
    		</div>
    		
    	</div>
    <!-- End main content -->

</div>
</div>
<?php get_footer(); ?>