<?php
	$blog_featured_img_url = '';
	if(!empty($image_thumb))
	{
		$blog_featured_img_url = get_the_post_thumbnail_url($post_ID, 'hoteller-gallery-list');
	}
?>

<!-- Begin each blog post -->
<div <?php post_class(array('blog-posts-'.$settings['layout'], 'blog-tilt')); ?> <?php if(!empty($blog_featured_img_url)) { ?>style="background-image:url(<?php echo esc_url($blog_featured_img_url); ?>);"<?php } ?>>
	
	<div class="bg_overlay"></div>

	<div class="post_wrapper">
		
		<div class="post_content_wrapper text_<?php echo esc_attr($settings['text_align']); ?>">
		    
		    <div class="post_header">
			    <?php
				  	if(!empty($settings['show_categories']))
				  	{
				?>
			    <div class="post_detail single_post">
			    	<span class="post_info_cat">
						<?php
						   //Get Post's Categories
						   $post_categories = wp_get_post_categories($post_ID);
						   
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
							   			echo '&nbsp;.&nbsp;';
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
				
				<div class="post_header_wrapper">
				<?php
			    	switch($settings['text_display'])
			    	{
				    	case 'full_content':
				    		if($settings['strip_html'] == 'yes')
				    		{
					    		echo strip_tags(get_the_content());
				    		}
				    		else
				    		{
				    			echo get_the_content();
				    		}
				    	break;
				    	
				    	case 'excerpt':
				    		if($settings['strip_html'] == 'yes')
				    		{
					    		echo hoteller_limit_get_excerpt(strip_tags(get_the_excerpt()), $settings['excerpt_length']['size'], '...');
					    	}
					    	else
					    	{
				    			echo hoteller_limit_get_excerpt(get_the_excerpt(), $settings['excerpt_length']['size'], '...');
				    		}
				    	break;
			    	}
			    ?>
			    <?php
				  	if(!empty($settings['show_date']))
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

</div>

<!-- End each blog post -->