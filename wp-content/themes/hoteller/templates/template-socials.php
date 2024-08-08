<?php
    //Display top social icons
    //Check if open link in new window
    $tg_topbar_social_link = get_theme_mod('tg_topbar_social_link', true);
?>
<div class="social_wrapper">
    <ul>
	 <?php
	 	$pp_facebook_url = get_option('pp_facebook_url');
	 	
	 	if(!empty($pp_facebook_url))
	 	{
	 ?>
	 <li class="facebook"><a <?php if(!empty($tg_topbar_social_link)) { ?>target="_blank"<?php } ?> href="<?php echo esc_url($pp_facebook_url); ?>"><i class="fab fa-facebook"></i></a></li>
	 <?php
	 	}
	 ?>
	 <?php
	 	$pp_twitter_username = get_option('pp_twitter_username');
	 	
	 	if(!empty($pp_twitter_username))
	 	{
	 ?>
	 <li class="twitter"><a <?php if(!empty($tg_topbar_social_link)) { ?>target="_blank"<?php } ?> href="<?php echo esc_url('http://twitter.com/'.$pp_twitter_username); ?>"><i class="fab fa-twitter"></i></a></li>
	 <?php
	 	}
	 ?>
	 <?php
	 	$pp_flickr_username = get_option('pp_flickr_username');
	 	
	 	if(!empty($pp_flickr_username))
	 	{
	 ?>
	 <li class="flickr"><a <?php if(!empty($tg_topbar_social_link)) { ?>target="_blank"<?php } ?> title="Flickr" href="<?php echo esc_url('http://flickr.com/people/'.$pp_flickr_username); ?>"><i class="fab fa-flickr"></i></a></li>
	 <?php
	 	}
	 ?>
	 <?php
	 	$pp_youtube_url = get_option('pp_youtube_url');
	 	
	 	if(!empty($pp_youtube_url))
	 	{
	 ?>
	 <li class="youtube"><a <?php if(!empty($tg_topbar_social_link)) { ?>target="_blank"<?php } ?> title="Youtube" href="<?php echo esc_url($pp_youtube_url); ?>"><i class="fab fa-youtube"></i></a></li>
	 <?php
	 	}
	 ?>
	 <?php
	 	$pp_vimeo_username = get_option('pp_vimeo_username');
	 	
	 	if(!empty($pp_vimeo_username))
	 	{
	 ?>
	 <li class="vimeo"><a <?php if(!empty($tg_topbar_social_link)) { ?>target="_blank"<?php } ?> title="Vimeo" href="<?php echo esc_url('http://vimeo.com/'.$pp_vimeo_username); ?>"><i class="fab fa-vimeo-square"></i></a></li>
	 <?php
	 	}
	 ?>
	 <?php
	 	$pp_tumblr_username = get_option('pp_tumblr_username');
	 	
	 	if(!empty($pp_tumblr_username))
	 	{
	 ?>
	 <li class="tumblr"><a <?php if(!empty($tg_topbar_social_link)) { ?>target="_blank"<?php } ?> title="Tumblr" href="<?php echo esc_url('http://'.$pp_tumblr_username.'.tumblr.com'); ?>"><i class="fab fa-tumblr"></i></a></li>
	 <?php
	 	}
	 ?>
	 <?php
	 	$pp_google_url = get_option('pp_google_url');
	 	
	 	if(!empty($pp_google_url))
	 	{
	 ?>
	 <li class="google"><a <?php if(!empty($tg_topbar_social_link)) { ?>target="_blank"<?php } ?> title="Google+" href="<?php echo esc_url($pp_google_url); ?>"><i class="fab fa-google-plus"></i></a></li>
	 <?php
	 	}
	 ?>
	 <?php
	 	$pp_dribbble_username = get_option('pp_dribbble_username');
	 	
	 	if(!empty($pp_dribbble_username))
	 	{
	 ?>
	 <li class="dribbble"><a <?php if(!empty($tg_topbar_social_link)) { ?>target="_blank"<?php } ?> title="Dribbble" href="<?php echo esc_url('http://dribbble.com/'.$pp_dribbble_username); ?>"><i class="fab fa-dribbble"></i></a></li>
	 <?php
	 	}
	 ?>
	 <?php
	 	$pp_linkedin_url = get_option('pp_linkedin_url');
	 	
	 	if(!empty($pp_linkedin_url))
	 	{
	 ?>
	 <li class="linkedin"><a <?php if(!empty($tg_topbar_social_link)) { ?>target="_blank"<?php } ?> title="Linkedin" href="<?php echo esc_url($pp_linkedin_url); ?>"><i class="fab fa-linkedin"></i></a></li>
	 <?php
	 	}
	 ?>
	 <?php
	        $pp_pinterest_username = get_option('pp_pinterest_username');
	        
	        if(!empty($pp_pinterest_username))
	        {
	    ?>
	    <li class="pinterest"><a <?php if(!empty($tg_topbar_social_link)) { ?>target="_blank"<?php } ?> title="Pinterest" href="<?php echo esc_url('http://pinterest.com/'.$pp_pinterest_username); ?>"><i class="fab fa-pinterest"></i></a></li>
	    <?php
	        }
	    ?>
	    <?php
	    	$pp_instagram_username = get_option('pp_instagram_username');
	    	
	    	if(!empty($pp_instagram_username))
	    	{
	    ?>
	    <li class="instagram"><a <?php if(!empty($tg_topbar_social_link)) { ?>target="_blank"<?php } ?> title="Instagram" href="<?php echo esc_url('http://instagram.com/'.$pp_instagram_username); ?>"><i class="fab fa-instagram"></i></a></li>
	    <?php
	    	}
	    ?>
	    <?php
	    	$pp_behance_username = get_option('pp_behance_username');
	    	
	    	if(!empty($pp_behance_username))
	    	{
	    ?>
	    <li class="behance"><a <?php if(!empty($tg_topbar_social_link)) { ?>target="_blank"<?php } ?> title="Behance" href="<?php echo esc_url('http://behance.net/'.$pp_behance_username); ?>"><i class="fab fa-behance-square"></i></a></li>
	    <?php
	    	}
	    ?>
	    <?php
		     $pp_500px_url = get_option('pp_500px_url');
		     
		     if(!empty($pp_500px_url))
		     {
		 ?>
		 <li class="500px"><a <?php if(!empty($tg_topbar_social_link)) { ?>target="_blank"<?php } ?> title="500px" href="<?php echo esc_url($pp_500px_url); ?>"><i class="fab fa-500px"></i></a></li>
		 <?php
		     }
		 ?>
		 <?php
		     $pp_snapchat_url = get_option('pp_snapchat_url');
		     
		     if(!empty($pp_snapchat_url))
		     {
		 ?>
		 <li class="snapchat"><a <?php if(!empty($pp_snapchat_url)) { ?>target="_blank"<?php } ?> title="Snapchat" href="<?php echo esc_url($pp_snapchat_url); ?>"><i class="fab fa-snapchat-ghost"></i></a></li>
		 <?php
		     }
		 ?>
		 <?php
		    $pp_tripadvisor_url = get_option('pp_tripadvisor_url');
		    
		    if(!empty($pp_tripadvisor_url))
		    {
		?>
		<li class="tripadvisor"><a <?php if(!empty($tg_footer_social_link)) { ?>target="_blank"<?php } ?> title="Tripadvisor" href="<?php echo esc_url($pp_tripadvisor_url); ?>"><i class="fab fa-tripadvisor"></i></a></li>
		<?php
		    }
		?>
		<?php
		    $pp_yelp_url = get_option('pp_yelp_url');
		    
		    if(!empty($pp_yelp_url))
		    {
		?>
		<li class="yelp"><a <?php if(!empty($tg_footer_social_link)) { ?>target="_blank"<?php } ?> title="Yelp" href="<?php echo esc_url($pp_yelp_url); ?>"><i class="fab fa-yelp"></i></a></li>
		<?php
		    }
		?>
	</ul>
</div>