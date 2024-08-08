<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://ljapps.com
 * @since      1.0.0
 *
 * @package    WP_Review_Pro
 * @subpackage WP_Review_Pro/admin/partials
 */
 
    // check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }
 
?>
    
<div class="wrap wp_pro-settings" id="">
	<h1><img src="<?php echo plugin_dir_url( __FILE__ ) . 'logo.png'; ?>"></h1>

<?php 
include("tabmenu.php");
?>

<div class="w3-container w3-margin-top w3-margin-bottom">
  <h1>Choose Download Method</h1>
</div>


<div class="w3-row-padding">

<div class="w3-col m4">
<div class="w3-card-4">
<header class="w3-container w3-light-grey">
  <h3>Google Crawl</h3>
</header>
<div class="w3-container">
  <p><b>Pros:</b></p>
  <p>+ Easiest and simplest method.</p>
  <p>+ Will download your latest or most helpful 10 reviews.</p>
  <p>+ No API Key required.</p>
  <p>+ Can also download images.</p>
  <hr>
  <p><b>Cons:</b></p>
  <p>- Limited to your newest or most helpful 10.</p>
  <p>- Limited to 300 locations</p>
</div>
<a href="/wp-admin/admin.php?page=wp_pro-get_apps&rtype=Google" class="w3-button w3-block w3-dark-grey">+ Select</a>
</div>
</div>

<div class="w3-col m4">
<div class="w3-card-4">
<header class="w3-container w3-light-grey">
  <h3>Review Funnel</h3>
</header>
<div class="w3-container">
<p><b>Pros:</b></p>
  <p>+ Can download all of your past Google Reviews.</p>
  <p>+ No API Key required.</p>
  <hr>
  <p><b>Cons:</b></p>
  <p>- Costs Review Credits to use. Each site gets 2,000 free credits a year.</p>
</div>
<a href="/wp-admin/admin.php?page=wp_pro-reviewfunnel&rtype=Google" class="w3-button w3-block w3-dark-grey">+ Select</a>
</div>
</div>

<div class="w3-col m4">
<div class="w3-card-4">
<header class="w3-container w3-light-grey">
  <h3>Google Places API</h3>
</header>
<div class="w3-container">
<p><b>Pros:</b></p>
  <p>+ Uses approved Google API.</p>
  <p>+ Will download your Most Helpful 5 reviews.</p>
  <hr>
  <p><b>Cons:</b></p>
  <p>- Must have a physical address on Google Maps.</p>
  <p>- Requires you to obtain Google Places API Key from Google.</p>
</div>
<a href="/wp-admin/admin.php?page=wp_pro-googlesettings" class="w3-button w3-block w3-dark-grey">+ Select</a>
</div>
</div>

</div>




</br></br></br>
</div>

