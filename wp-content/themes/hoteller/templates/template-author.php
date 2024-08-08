<?php
    $tg_blog_display_author = get_theme_mod('tg_blog_display_author', true);
    $author_info = get_the_author_meta('description');
    
    if($tg_blog_display_author && !empty($author_info))
    {
    	$author_name = get_the_author();
?>
<div id="about_the_author">
    <div class="gravatar"><?php echo get_avatar( get_the_author_meta('email'), 200 ); ?></div>
    <div class="author_detail">
     	<div class="author_content">
     		<h4><?php echo esc_html($author_name); ?></h4>
     		<?php echo strip_tags($author_info); ?>
     	</div>
    </div>
    <br class="clear"/>
</div>
<?php
    }
?>