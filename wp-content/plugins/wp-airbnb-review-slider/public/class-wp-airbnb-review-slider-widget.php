<?php
/**
 * Description:   Adds a widget that displays the reviews template
 */

class wpairbnb_Widget extends WP_Widget {

  // Set up the widget name and description.
  public function __construct() {
    $widget_options = array( 'classname' => 'wpairbnb_widget', 'description' => 'Widget for WP Airbnb Review Slider Plugin. Allows you to display your reviews in your widget areas.' );
    parent::__construct( 'wpairbnb_widget', 'WP Airbnb Review Slider', $widget_options );
  }


  // Create the widget output.
  public function widget( $args, $instance ) {
    $templateid = apply_filters( 'widget_title', $instance[ 'template' ] );
	$templateid = strip_tags($templateid);
	$title = apply_filters( 'widget_title', $instance[ 'title' ] );
		//db function variables
	global $wpdb;
	$table_name = $wpdb->prefix . 'wpairbnb_post_templates';
	//$templatedetails = $wpdb->get_row("SELECT * FROM $table_name WHERE id='".$templateid."'");
	$templatedetails = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id=%d",$templateid));
	//print_r($templatedetails);

    echo $args['before_widget'] . $args['before_title'] . $title . $args['after_title']; 
	
	if($templatedetails){
		//echo out widget template here, can include from a file based on which template chosen
		$a['tid']=$templatedetails->id;
		include plugin_dir_path( __FILE__ ) . 'partials/wp-airbnb-review-slider-public-display-widget.php';

	}
	echo $args['after_widget'];
  }

  
  // Create the admin area widget settings form.
  public function form( $instance ) {
    $template = ! empty( $instance['template'] ) ? $instance['template'] : ''; 
	$title = ! empty( $instance['title'] ) ? $instance['title'] : ''; 
	
	//echo esc_attr( $template );
	//create select box of widget templates, pull from db
		//db function variables
	global $wpdb;
	$table_name = $wpdb->prefix . 'wpairbnb_post_templates';
	$widgettemplates = $wpdb->get_results("SELECT id, title, template_type FROM $table_name WHERE template_type='widget'");
	//print_r($widgettemplates);

	
?>
    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>">Widget Title:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'template' ); ?>">Select Review Template:</label>
	  <select id="<?php echo $this->get_field_id( 'template' ); ?>" name="<?php echo $this->get_field_name( 'template' ); ?>">
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
	
<?php
  }


  // Apply settings to the widget instance.
  public function update( $new_instance, $old_instance ) {
    $instance = $old_instance;
    $instance[ 'template' ] = strip_tags( $new_instance[ 'template' ] );
	$instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
    return $instance;
  }

}

// Register the widget.
function wpairbnb_register_widget() { 
  register_widget( 'wpairbnb_Widget' );
}
global $wpdb;
//register a widget if there is a review template or badge that is type widget.
$table_name = $wpdb->prefix . 'wpairbnb_post_templates';
$airbnbtemplatewidgetrowcount = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE template_type='widget'");
if($airbnbtemplatewidgetrowcount>0){
add_action( 'widgets_init', 'wpairbnb_register_widget' );
}
?>