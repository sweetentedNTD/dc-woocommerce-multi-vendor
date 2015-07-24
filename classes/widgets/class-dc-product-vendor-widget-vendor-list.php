<?php
/**
 * Poduct Vendor Widget
 *
 * @author    Dualcube
 * @category  Widgets
 * @package   product_vendor/Widgets
 * @version   1.6.4
 * @extends   WP_Widget
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class DC_Widget_Vendor_List extends WP_Widget {
	
	/**
   * constructor
   *
   * @access public
   * @return void
   */
  function DC_Widget_Vendor_List() {
  	global $DC_Product_Vendor;
  	// Widget variable settings
		$this->widget_cssclass = 'widget_product_vendor_list';
		$this->widget_description = __( 'Display a list of vendors.', $DC_Product_Vendor->text_domain );
		$this->widget_idbase = 'dc_product_vendors_list';
		$this->widget_title = __( 'WooCommerce Product Vendors List', $DC_Product_Vendor->text_domain );

		// Widget settings
		$widget_ops = array( 'classname' => $this->widget_cssclass, 'description' => $this->widget_description );

		// Widget control settings
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => $this->widget_idbase );

		// Create the widget
		$this->WP_Widget( $this->widget_idbase, $this->widget_title, $widget_ops, $control_ops );
  }
	
  
  /**
   * widget function.
   *
   * @see WP_Widget
   * @access public
   * @param array $args
   * @param array $instance
   * @return void
   */
  function widget( $args, $instance ) {
  	global $DC_Product_Vendor, $woocommerce;
  	extract( $args, EXTR_SKIP );

		$vendor_id = false;
		$vendor = false;

		$show_widget = true;
				
		if( $show_widget ) {
			
			$vendors = get_dc_vendors();
			
			if( $vendors ) {
				// Set up widget title
				if( $instance['title'] ) {
					$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
				} else {
					$title = false;
				}
				// Before widget (defined by themes)
				echo $before_widget;
				// Display the widget title if one was input (before and after defined by themes).
				if ( $title ) { echo $before_title . $title . $after_title; }
				// Widget content
				// Action for plugins/themes to hook onto
				do_action( $this->widget_cssclass . '_top' );
				
				$DC_Product_Vendor->template->get_template( 'widget/vendor-list.php', array('vendors' => $vendors));

				// Action for plugins/themes to hook onto
				do_action( $this->widget_cssclass . '_bottom' );

				// After widget (defined by themes).
				echo $after_widget;
			}
		}
  }
  
  /**
   * update function.
   *
   * @see WP_Widget->update
   * @access public
   * @param array $new_instance
   * @param array $old_instance
   * @return array
  */
  function update( $new_instance, $old_instance ) {
  	$instance = $old_instance;
  	// Sanitise inputs
		$instance['title'] = strip_tags( $new_instance['title'] );
		return $instance;
  }
  
  /**
	 * The form on the widget control in the widget administration area
	 * @since  1.0.0
	 * @param  array $instance The settings for this instance.
	 * @return void
	*/
	public function form( $instance ) {
		global $DC_Product_Vendor;
		// Set up the default widget settings
		$defaults = array(
						'title' => '',
					);
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title (optional):', $DC_Product_Vendor->text_domain ); ?></label>
			<input type="text" name="<?php echo $this->get_field_name( 'title' ); ?>"  value="<?php echo $instance['title']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" />
		</p>
		<span class="description"><?php _e('This widget show a list of shop vendors..', $DC_Product_Vendor->text_domain) ?> </span>
		<?php
	}
}