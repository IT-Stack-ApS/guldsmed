<?php

/**
 * Designs Tab
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( class_exists( 'GTW_Designs_Tab' ) ) {
	return new GTW_Designs_Tab() ;
}

/**
 * GTW_Designs_Tab.
 */
class GTW_Designs_Tab extends GTW_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id                = 'designs' ;
		$this->show_reset_button = false ;
		$this->label             = esc_html__( 'Designs' , 'gift-wrapper-for-woocommerce' ) ;

		add_action( sanitize_key( $this->plugin_slug . '_settings_' . $this->id ) , array( $this , 'render_wrapping_designs' ) , 20 ) ;

		parent::__construct() ;
	}

	/**
	 * Render Wrapping designs.
	 * 
	 * @return void
	 */
	public function render_wrapping_designs() {
		$rule_ids = gtw_get_rule_ids() ;

		include_once( GTW_ABSPATH . 'inc/admin/menu/views/html-gift-wrapping-designs.php') ;
	}

	/**
	 * Save settings.
	 * 
	 * @return void
	 */
	public function save() {
		if ( ! isset( $_REQUEST[ 'gtw_save' ] ) ) {
			return ;
		}

		// Save wrapping designs.
		$this->save_wrapping_designs() ;

		parent::save() ;
	}

	/**
	 * Save Wrapping designs.
	 * 
	 * @return void
	 */
	public function save_wrapping_designs() {
		if ( ! isset( $_REQUEST[ 'gtw_wrapping_styles' ] ) ) {
			return ;
		}

		$rules = wc_clean( wp_unslash( $_REQUEST[ 'gtw_wrapping_styles' ] ) ) ;

		// Return if rules is not set.
		if ( ! gtw_check_is_array( $rules ) ) {
			return ;
		}

		foreach ( $rules as $rule_id => $rule ) {

			if ( 'new' == $rule_id ) {
				foreach ( $rule as $new_rule ) {
					$meta_args = array(
						'gtw_image_id' => $new_rule[ 'image_id' ] ,
						'gtw_price'    => wc_format_decimal( $new_rule[ 'price' ] )
							) ;

					//New Rule.
					gtw_create_new_rule( $meta_args , array( 'post_title' => $new_rule[ 'name' ] , 'menu_order' => 99999 ) ) ;
				}
			} else {

				$meta_args = array(
					'gtw_image_id' => $rule[ 'image_id' ] ,
					'gtw_price'    => wc_format_decimal( $rule[ 'price' ] )
						) ;

				//Update Rule.
				gtw_update_rule( $rule_id , $meta_args , array( 'post_title' => $rule[ 'name' ] ) ) ;
			}
		}
	}

	/**
	 * May be display the warning notices.
	 * 
	 * @return void
	 */
	public function notices() {
		// Return if the gift wrapper is without designs.
		if ( '2' == get_option( 'gtw_settings_gift_wrapper_design_type' ) ) {
			return ;
		}

		$rule_ids = gtw_get_active_rule_ids() ;
		// Return if the designs are configured.
		if ( gtw_check_is_array( $rule_ids ) ) {
			return ;
		}

		GTW_Settings::error_message( esc_html__( 'No Gift Wrapper Designs are available. You have to create at least one Design to show Gift Wrapper option in Frontend. You can create designs in "WooCommerce > Gift Wrapper > Designs" or else you can create designs for each product in the edit product page.' , 'gift-wrapper-for-woocommerce' ) ) ;
	}

}

return new GTW_Designs_Tab() ;
