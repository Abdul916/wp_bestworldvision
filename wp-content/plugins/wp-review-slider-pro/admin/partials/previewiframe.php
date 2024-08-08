<style>
div#adminmenumain {
    display: none !important;
}
div#wpadminbar {
    display: none !important;
}
div#wpfooter {
    display: none !important;
}
div#wpcontent {
	margin-left: 0px;
	padding-left: 0px;
    margin: 0px 35px 0px 30px!important;
    padding: 5px !important;
}
div#wpbody-content {
    padding: 0px!important;
}
div.notice {
    display: none;
}
body {
    background: #ffffff;
}
</style>
<?php
$ptid = $_GET["tid"];
if($ptid>0){
do_action( 'wprev_pro_plugin_action', $ptid);
} else {
	echo "Error, no template found to preview. Please contact support.";
}
?>
