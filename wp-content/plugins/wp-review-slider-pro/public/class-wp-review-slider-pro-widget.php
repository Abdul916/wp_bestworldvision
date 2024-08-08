<?php
/**
 * Description:   Adds a widget that displays the reviews template
 */

class wprevpro_Widget extends WP_Widget {

  // Set up the widget name and description.
  public function __construct() {
    $widget_options = array( 'classname' => 'wprevpro_widget', 'description' => 'Widget for WP Review Slider Pro Plugin. Allows you to display your reviews in your widget areas.' );
    parent::__construct( 'wprevpro_widget', 'WP Review Slider Pro Widget', $widget_options );
  }


  // Create the widget output.
  public function widget( $args, $instance ) {
	
	if(isset($instance[ 'template' ])){
    $templateid = apply_filters( 'widget_title', $instance[ 'template' ] );
	$templateid = strip_tags($templateid);
	} else {
		$templateid ='';
	}
	 $badgeid = '';
	  if(isset($instance[ 'badge' ])){
	$badgeid = apply_filters( 'widget_title', $instance[ 'badge' ] );
	$badgeid = strip_tags($badgeid);
	  }
	  if(isset($instance[ 'title' ])){
	$title = apply_filters( 'widget_title', $instance[ 'title' ] );
	  } else {
		  $title ='';
	  }
	if($title==''){
		$beforetitle = '';
		$aftertitle = '';
	} else {
		$beforetitle = $args['before_title'];
		$aftertitle = $args['after_title'];
	}
	if(isset($instance[ 'customhtml' ])){
	$customhtml = $instance[ 'customhtml' ];
	} else {
		$customhtml ='';
	}
	
	echo $args['before_widget'] . $beforetitle . $title . $aftertitle; 
		//db function variables
	global $wpdb;
	//------for displaying badge
	if($badgeid>0){
		$table_name = $wpdb->prefix . 'wpfb_badges';
		$badgedetails = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id=%d",$badgeid));
		if($badgedetails){
			//echo out widget template here, can include from a file based on which template chosen
			$a['tid']=$badgedetails->id;
			include plugin_dir_path( __FILE__ ) . 'partials/wp-review-slider-pro-public-display_badge.php';

		}
	}


	
	//------for displaying review template
	if($templateid>0){
		$table_name = $wpdb->prefix . 'wpfb_post_templates';
		$templatedetails = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id=%d",$templateid));
		if($templatedetails){
			//echo out widget template here, can include from a file based on which template chosen
			$a['tid']=$templatedetails->id;
			include plugin_dir_path( __FILE__ ) . 'partials/wp-review-slider-pro-public-display-widget.php';

		}
	}
	echo "<div class='wprev_after_widget_div'>".$customhtml."</div>";
	echo $args['after_widget'];
  }

  
  // Create the admin area widget settings form.
  public function form( $instance ) {
	  $badge = ! empty( $instance['badge'] ) ? $instance['badge'] : '';
    $template = ! empty( $instance['template'] ) ? $instance['template'] : ''; 
	$title = ! empty( $instance['title'] ) ? $instance['title'] : ''; 
	$customhtml = ! empty( $instance['customhtml'] ) ? $instance['customhtml'] : ''; 
	
	//echo esc_attr( $template );
	//create select box of widget templates, pull from db
		//db function variables
	global $wpdb;
	$table_name = $wpdb->prefix . 'wpfb_post_templates';
	$widgettemplates = $wpdb->get_results("SELECT id, title, template_type FROM $table_name WHERE template_type='widget'");
	//print_r($widgettemplates);
	$table_name_badge = $wpdb->prefix . 'wpfb_badges';
	$widgetbadges  = $wpdb->get_results("SELECT id, title, badge_type FROM $table_name_badge WHERE badge_type='widget'");

	
?>
    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>">Widget Title:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'badge' ); ?>">Select Badge Template:</label>
	  <select id="<?php echo $this->get_field_id( 'badge' ); ?>" name="<?php echo $this->get_field_name( 'badge' ); ?>">
	  <option value="none" <?php if(esc_attr( $badge )=='none'){echo "selected";}?> >None</option>';
<?php
	foreach ( $widgetbadges as $widgetbadge ) 
	{
?>
	<option value="<?php echo $widgetbadge->id;?>" <?php if(esc_attr( $badge )==$widgetbadge->id){echo "selected";}?> ><?php echo $widgetbadge->title; ?></option>';
<?php
	}
?>
	</select>

    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'template' ); ?>">Select Review Template:</label>
	  <select id="<?php echo $this->get_field_id( 'template' ); ?>" name="<?php echo $this->get_field_name( 'template' ); ?>">
	  <option value="none" <?php if(esc_attr( $template )=='none'){echo "selected";}?> >None</option>';
<?php
	foreach ( $widgettemplates as $widgettemplate ) 
	{
?>
	<option value="<?php echo $widgettemplate->id;?>" <?php if(esc_attr( $template )==$widgettemplate->id){echo "selected";}?> ><?php echo $widgettemplate->title; ?></option>';
<?php
	}
?>
	</select>

    </p>
	<p>
      <label for="<?php echo $this->get_field_id( 'customhtml' ); ?>">Custom HTML After Reviews:</label><div>
      <textarea rows="4" style="width: 100%;" id="<?php echo $this->get_field_id( 'customhtml' ); ?>" name="<?php echo $this->get_field_name( 'customhtml' ); ?>"><?php echo esc_attr( $customhtml ); ?></textarea></div>
    </p>
	
<?php
  }


  // Apply settings to the widget instance.
  public function update( $new_instance, $old_instance ) {
    $instance = $old_instance;
	$instance[ 'badge' ] = strip_tags( $new_instance[ 'badge' ] );
    $instance[ 'template' ] = strip_tags( $new_instance[ 'template' ] );
	$instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
	$instance[ 'customhtml' ] = $new_instance[ 'customhtml' ] ;
	
    return $instance;
  }
  

}

// Register the widget.
function wprevpro_register_widget() { 
  register_widget( 'wprevpro_Widget' );
}

global $wpdb;
//register a widget if there is a review template or badge that is type widget.
$table_name = $wpdb->prefix . 'wpfb_post_templates';
$templatewidgetrowcount = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE template_type='widget'");
$table_name_badge = $wpdb->prefix . 'wpfb_badges';
$badgewidgetrowcount = $wpdb->get_var("SELECT COUNT(*) FROM $table_name_badge WHERE badge_type='widget'");

if($templatewidgetrowcount>0 || $badgewidgetrowcount>0){
add_action( 'widgets_init', 'wprevpro_register_widget' );
}

?>