<?php
    $tg_blog_display_related = get_theme_mod('tg_blog_display_related', true);
    
    if($tg_blog_display_related)
    {
?>

<?php
//for use in the loop, list 9 post titles related to post's tags on current post
$tags = wp_get_post_tags($post->ID);

if ($tags) {

    $tag_in = array();
  	//Get all tags
  	foreach($tags as $tags)
  	{
      	$tag_in[] = $tags->term_id;
  	}
  	
  	$post_layout = get_post_meta($post->ID, 'post_layout', true);
  	$showposts = 2;
  	$column_tag = 'one_half';
  	if($post_layout == 'Fullwidth')
  	{
	  	$showposts = 3;
	  	$column_tag = 'one_third';
  	}

  	$args=array(
      	  'tag__in' => $tag_in,
      	  'post__not_in' => array($post->ID),
      	  'showposts' => $showposts,
      	  'ignore_sticky_posts' => 1,
      	  'orderby' => 'rand',
      	  'order' => 'DESC'
  	 );
  	$my_query = new WP_Query($args);
  	$i_post = 1;
  	
  	if( $my_query->have_posts() ) {
 ?>
  	<div class="post_related">
	<h3><?php echo esc_html_e('Related Articles', 'hoteller' ); ?></h3><br class="clear"/>
    <?php
       while ($my_query->have_posts()) : $my_query->the_post();
       
       $last_class = '';
       if($i_post%$showposts==0)
       {
	       $last_class = 'last';
       }
       
       $image_thumb = '';
					
		if(has_post_thumbnail(get_the_ID(), 'hoteller-blog'))
		{
		    $image_id = get_post_thumbnail_id(get_the_ID());
		    $image_thumb = wp_get_attachment_image_src($image_id, 'hoteller-blog');
		}
    ?>
       <div class="<?php echo esc_attr($column_tag); ?> <?php echo esc_attr($last_class); ?>">
		   <!-- Begin each blog post -->
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			
				<div class="post_wrapper grid_layout">
					<?php
					    if(!empty($image_thumb))
					    {
					     $small_image_url = wp_get_attachment_image_src($image_id, 'hoteller-blog', true);
					?>
					    <div class="post_img static">
						    <?php
								$tg_enable_lazy_loading = get_theme_mod('tg_enable_lazy_loading', true);
							?>
						    <div class="post_img_hover classic <?php if(!empty($tg_enable_lazy_loading)) { ?>lazy<?php } ?>">
						     	<?php 
							     	$blog_featured_img_url = get_the_post_thumbnail_url($post, 'hoteller-blog'); 
							     	$blog_featured_img_data = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), "hoteller-blog" );
							     	$blog_featured_img_alt = get_post_meta(get_post_thumbnail_id($post->ID), '_wp_attachment_image_alt', true);
							     	$return_attr = hoteller_get_lazy_img_attr();
							     	
							     	if(!empty($blog_featured_img_url))
							     	{
							     ?>
							     <img <?php echo hoteller_get_blank_img_attr(); ?> <?php echo esc_attr($return_attr['source']); ?>="<?php echo esc_url($blog_featured_img_url); ?>" class="<?php echo esc_attr($return_attr['class']); ?>" alt="<?php echo esc_attr($blog_featured_img_alt); ?>"/>
							     <?php
								     }
								?>
						     	<?php echo hoteller_get_post_format_icon(get_the_ID()); ?>
						     	<a href="<?php the_permalink(); ?>"></a>
						    </div>
					    </div>
					<?php
					    }
					?>
				    
				    <div class="post_header_wrapper">
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
						<div class="post_header grid related">
							<h6><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h6>
						</div>
						
						<?php
							//Get blog date
							$tg_blog_date = get_theme_mod('tg_blog_date', true);
							if(!empty($tg_blog_date))
							{
						?>
						<div class="post_header_wrapper">
							<div class="post_button_wrapper">
						    	<div class="post_attribute">
								    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php echo date_i18n(HOTELLER_THEMEDATEFORMAT, get_the_time('U')); ?></a>
								</div>
						    </div>
						</div>
						<?php
							}
						?>
				    </div>
				    
				</div>
			
			</div>
			<!-- End each blog post -->
       </div>
     <?php
     		$i_post++;
	 		endwhile;
	 		
	 		wp_reset_postdata();
     ?>
  	</div>
<?php
  	}
}
    } //end if show related
?>