<?php
 $tg_blog_display_tags = get_theme_mod('tg_blog_display_tags', true);

    if(has_tag() && !empty($tg_blog_display_tags))
    {
?>
    <div class="post_excerpt post_tag">
    	<?php
	    	if( $tags = get_the_tags() ) {
			    foreach( $tags as $tag ) {
			        echo '<a href="' . get_term_link( $tag, $tag->taxonomy ) . '">' . $tag->name . '</a>';
			    }
			}	
	   	?>
    </div><br class="clear"/>
<?php
    }
?>