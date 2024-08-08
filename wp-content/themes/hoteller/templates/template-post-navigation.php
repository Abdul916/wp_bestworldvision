<?php
//display post navigation
$tg_blog_display_navigation = get_theme_mod('tg_blog_display_navigation', true);
$tg_enable_lazy_loading = get_theme_mod('tg_enable_lazy_loading', true);

if(!empty($tg_blog_display_navigation))
{
	$prev_post = get_previous_post();
	if(!empty($prev_post))
	{
?>
<div class="post_navigation previous">
	<a href="<?php echo esc_url(get_permalink($prev_post->ID)); ?>">
		<div class="navigation_post_content">
			<?php
				if(has_post_thumbnail($prev_post->ID, 'thumbnail'))
				{
			?>
			<div class="post_img static">
			    <div class="post_img_hover <?php if(!empty($tg_enable_lazy_loading)) { ?>lazy<?php } ?>">
			     	<?php 
				     	$blog_featured_img_url = get_the_post_thumbnail_url($prev_post, 'hoteller-gallery-list'); 
				     	$blog_featured_img_data = wp_get_attachment_image_src(get_post_thumbnail_id($prev_post->ID), "hoteller-gallery-list" );
				     	$blog_featured_img_alt = get_post_meta(get_post_thumbnail_id($prev_post->ID), '_wp_attachment_image_alt', true);
				     	$return_attr = hoteller_get_lazy_img_attr();
				     	
				     	if(!empty($blog_featured_img_url))
				     	{
				     ?>
				     <img <?php echo hoteller_get_blank_img_attr(); ?> <?php echo esc_attr($return_attr['source']); ?>="<?php echo esc_url($blog_featured_img_url); ?>" class="<?php echo esc_attr($return_attr['class']); ?>" alt="<?php echo esc_attr($blog_featured_img_alt); ?>"/>
				     <?php
					     }
					?>
			    </div>
		    </div>
			<?php
				}
			?>
			<h7><?php echo esc_html($prev_post->post_title); ?></h7>
		</div>
		<div class="navigation_anchor">
			<?php esc_html_e('Previous Article', 'hoteller' ); ?>
		</div>
	</a>
</div>
<?php
	}
	
	$next_post = get_next_post();
	if(!empty($next_post))
	{
?>
<div class="post_navigation next">
	<a href="<?php echo esc_url(get_permalink($next_post->ID)); ?>">
		<div class="navigation_post_content">
			<?php
				if(has_post_thumbnail($next_post->ID, 'thumbnail'))
				{
			?>
			<div class="post_img static">
			    <div class="post_img_hover <?php if(!empty($tg_enable_lazy_loading)) { ?>lazy<?php } ?>">
			     	<?php 
				     	$blog_featured_img_url = get_the_post_thumbnail_url($next_post, 'hoteller-gallery-list'); 
				     	$blog_featured_img_data = wp_get_attachment_image_src(get_post_thumbnail_id($next_post->ID), "hoteller-gallery-list" );
				     	$blog_featured_img_alt = get_post_meta(get_post_thumbnail_id($next_post->ID), '_wp_attachment_image_alt', true);
				     	$return_attr = hoteller_get_lazy_img_attr();
				     	
				     	if(!empty($blog_featured_img_url))
				     	{
				     ?>
				     <img <?php echo hoteller_get_blank_img_attr(); ?> <?php echo esc_attr($return_attr['source']); ?>="<?php echo esc_url($blog_featured_img_url); ?>" class="<?php echo esc_attr($return_attr['class']); ?>" alt="<?php echo esc_attr($blog_featured_img_alt); ?>"/>
				     <?php
					     }
					?>
			    </div>
		    </div>
			<?php
				}
			?>
			<h7><?php echo esc_html($next_post->post_title); ?></h7>
		</div>
		<div class="navigation_anchor">
			<?php esc_html_e('Next Article', 'hoteller' ); ?>
		</div>
	</a>
</div>
<?php	
	}
}
?>