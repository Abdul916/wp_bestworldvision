<?php

/**
 * Sample menu item metadata
 *
 * This class demonstrate the usage of Menu Item Custom Fields in plugins/themes.
 *
 * @since 0.1.0
 */
class Hoteller_Menu_Item_Custom_Fields {

	/**
	 * Holds our custom fields
	 *
	 * @var    array
	 * @access protected
	 * @since  Menu_Item_Custom_Fields_Example 0.2.0
	 */
	protected static $fields = array();


	/**
	 * Initialize plugin
	 */
	public static function init() {
		add_action( 'wp_nav_menu_item_custom_fields', array( __CLASS__, '_fields' ), 10, 4 );
		add_action( 'wp_update_nav_menu_item', array( __CLASS__, '_save' ), 10, 3 );
		add_filter( 'manage_nav-menus_columns', array( __CLASS__, '_columns' ), 99 );

		self::$fields = array(
			'megamenu' => __( 'Elementor Mega Menu (optional)', 'hoteller-elementor' ),
		);
	}


	/**
	 * Save custom field value
	 *
	 * @wp_hook action wp_update_nav_menu_item
	 *
	 * @param int   $menu_id         Nav menu ID
	 * @param int   $menu_item_db_id Menu item ID
	 * @param array $menu_item_args  Menu item data
	 */
	public static function _save( $menu_id, $menu_item_db_id, $menu_item_args ) {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		check_admin_referer( 'update-nav_menu', 'update-nav-menu-nonce' );

		foreach ( self::$fields as $_key => $label ) {
			$key = sprintf( 'menu-item-%s', $_key );

			// Sanitize
			if ( ! empty( $_POST[ $key ][ $menu_item_db_id ] ) ) {
				// Do some checks here...
				$value = $_POST[ $key ][ $menu_item_db_id ];
			} else {
				$value = null;
			}

			// Update
			if ( ! is_null( $value ) ) {
				update_post_meta( $menu_item_db_id, $key, $value );
			} else {
				delete_post_meta( $menu_item_db_id, $key );
			}
		}
	}


	/**
	 * Print field
	 *
	 * @param object $item  Menu item data object.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args  Menu item args.
	 * @param int    $id    Nav menu ID.
	 *
	 * @return string Form fields
	 */
	public static function _fields( $id, $item, $depth, $args ) {
		//Get all megamenus posts
		$args = array(
			 'post_type'     => 'megamenu',
			 'post_status'   => array( 'publish' ),
			 'numberposts'   => -1,
			 'orderby'       => 'title',
			 'order'         => 'ASC',
			 'suppress_filters'   => false
		);
		$megamenus = get_posts($args);
		$megamenus_select = array();
		$megamenus_select[''] = '';

		if(!empty($megamenus))
		{
			foreach ($megamenus as $megamenu)
			{
				$megamenus_select[$megamenu->ID] = $megamenu->post_title;
			}
		}
		else
		{
			$megamenus_select[''] = __( 'No mega menu found. Please create one first', 'hoteller-elementor' );
		}
	
		foreach ( self::$fields as $_key => $label ) :
			$key   = sprintf( 'menu-item-%s', $_key );
			$id    = sprintf( 'edit-%s-%s', $key, $item->ID );
			$name  = sprintf( '%s[%s]', $key, $item->ID );
			$value = get_post_meta( $item->ID, $key, true );
			$class = sprintf( 'field-%s', $_key );
			?>
				<p class="description description-wide <?php echo esc_attr( $class ) ?>">
					<label for="<?php echo esc_attr($id); ?>">
						<?php echo esc_html($label); ?>
						<select id="<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($name); ?>">
							<?php
								foreach ($megamenus_select as $megamenu_id => $megamenu)
								{
							?>
							<option value="<?php echo esc_attr($megamenu_id); ?>" <?php if($megamenu_id == $value) { ?>selected<?php } ?>><?php echo esc_html($megamenu); ?></option>
							<?php
								}
							?>
						</select>
					<label>
				</p>
			<?php
		endforeach;
	}


	/**
	 * Add our fields to the screen options toggle
	 *
	 * @param array $columns Menu item columns
	 * @return array
	 */
	public static function _columns( $columns ) {
		$columns = array_merge( $columns, self::$fields );

		return $columns;
	}
}
Hoteller_Menu_Item_Custom_Fields::init();