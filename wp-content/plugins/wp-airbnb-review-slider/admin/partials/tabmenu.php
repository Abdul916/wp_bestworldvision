<?php
$urltrimmedtab = remove_query_arg( array('page', '_wpnonce', 'taction', 'tid', 'sortby', 'sortdir', 'opt','settings-updated') );

$urlreviewlist = esc_url( add_query_arg( 'page', 'wp_airbnb-reviews',$urltrimmedtab ) );
$urltemplateposts = esc_url( add_query_arg( 'page', 'wp_airbnb-templates_posts',$urltrimmedtab ) );
$urlgetpro = esc_url( add_query_arg( 'page', 'wp_airbnb-get_airbnb',$urltrimmedtab ) );
$urlforum = esc_url( add_query_arg( 'page', 'wp_airbnb-get_pro',$urltrimmedtab ) );
$urlwelcome = esc_url( add_query_arg( 'page', 'wp_airbnb-welcome',$urltrimmedtab ) );
?>	
	<h2 class="nav-tab-wrapper">
	<a href="<?php echo $urlgetpro; ?>" class="nav-tab <?php if($_GET['page']=='wp_airbnb-welcome'){echo 'nav-tab-active';} ?>"><?php _e('Welcome', 'wp-airbnb-review-slider'); ?></a>
	<a href="<?php echo $urlgetpro; ?>" class="nav-tab <?php if($_GET['page']=='wp_airbnb-get_airbnb'){echo 'nav-tab-active';} ?>"><?php _e('Get Airbnb Reviews', 'wp-airbnb-review-slider'); ?></a>
	<a href="<?php echo $urlreviewlist; ?>" class="nav-tab <?php if($_GET['page']=='wp_airbnb-reviews'){echo 'nav-tab-active';} ?>"><?php _e('Review List', 'wp-airbnb-review-slider'); ?></a>
	<a href="<?php echo $urltemplateposts; ?>" class="nav-tab <?php if($_GET['page']=='wp_airbnb-templates_posts'){echo 'nav-tab-active';} ?>"><?php _e('Templates', 'wp-airbnb-review-slider'); ?></a>
	<a href="https://wpreviewslider.com/" target="_blank" class="nav-tab <?php if($_GET['page']=='wp_airbnb-get_pro'){echo 'nav-tab-active';} ?>"><?php _e('Get Pro Version', 'wp_airbnb-get_pro'); ?></a>

	</h2>