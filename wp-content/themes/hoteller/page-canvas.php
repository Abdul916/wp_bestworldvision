<?php 
	get_header(); 

	if ( have_posts() ) {
	while ( have_posts() ) : the_post(); ?>		

	<?php the_content(); break;  ?>

<?php endwhile; 
}

if (comments_open($post->ID)) 
{
?>
<div class="fullwidth_comment_wrapper">
	<?php comments_template( '', true ); ?>
</div>
<?php
}

get_footer();
?>