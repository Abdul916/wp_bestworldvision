<?php
/**
 * The main template file for display error page.
 *
 * @package WordPress
*/


get_header(); 

//Check if has custom 404 not found page
$tg_pages_template_404 = get_theme_mod('tg_pages_template_404');
if(!empty($tg_pages_template_404)) {
	//Add Polylang plugin support
	if (function_exists('pll_get_post')) {
		$tg_pages_template_404 = pll_get_post($tg_pages_template_404);
	}
	
	//Add WPML plugin support
	if (function_exists('icl_object_id')) {
		$tg_pages_template_404 = icl_object_id($tg_pages_template_404, 'page', false, ICL_LANGUAGE_CODE);
	}
	
	if (class_exists("\\Elementor\\Plugin")) {
		echo hoteller_get_elementor_content($tg_pages_template_404);
	}
}
//Display default 404 page template
else 
{
?>

<!-- Begin content -->
<div id="page_caption">
	<div class="page_title_wrapper">
		<div class="standard_wrapper">
			<div class="page_title_inner">
			    <h1><?php esc_html_e('404 Not Found!', 'hoteller' ); ?></h1>
			</div>
		</div>
	</div>
</div>

<div id="page_content_wrapper">

    <div class="inner">
    
    	<!-- Begin main content -->
    	<div class="inner_wrapper">
    	
	    	<div class="search_form_wrapper">
		    	<?php esc_html_e( "We're sorry, the page you have looked for does not exist in our content! Perhaps you would like to go to our homepage or try searching below.", 'hoteller' ); ?>
		    	<br/><br/>
		    	
	    		<form class="searchform" method="get" action="<?php echo esc_url(home_url('/')); ?>">
		    		<p class="input_wrapper">
			    		<input type="text" class="input_effect field searchform-s" name="s" value="<?php the_search_query(); ?>" placeholder="<?php esc_attr_e('Type to search...', 'hoteller' ); ?>">
			    	<span class="focus-border"></span>
		    		</p>
			    	<br/>
			    	<input type="submit" value="<?php esc_attr_e('Search', 'hoteller' ); ?>"/>
			    </form>
    		</div>
	    	
	    	<br/>
	    	
    		</div>
    	</div>
    	
</div>
<br class="clear"/>
<?php 
} //End display default 404 not found templates
get_footer(); 
?>