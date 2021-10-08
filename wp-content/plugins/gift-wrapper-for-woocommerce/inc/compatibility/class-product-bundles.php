<?php

/**
 * WooCommerce Product Bundles Compatibility.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'GTW_Product_Bundles_Compatibility' ) ) {

	/**
	 * Class GTW_Product_Bundles_Compatibility.
	 */
	class GTW_Product_Bundles_Compatibility extends GTW_Compatibility {

		/**
		 * Class Constructor.
		 */
		public function __construct() {
			$this->id = 'product_bundles' ;

			parent::__construct() ;
		}

		/**
		 * Is plugin enabled?.
		 * 
		 *  @return bool
		 * */
		public function is_plugin_enabled() {

			return class_exists( 'WC_Bundles' ) ;
		}

		/**
		 * Action
		 */
		public function actions() {

			// Stamp products not allowed to gift wrapper..
			add_filter( 'gtw_validate_gift_wrapper_cart_item_data' , array( $this , 'validate_stamp_products_gift_wrap' ) , 10 , 3 ) ;
		}

		/**
		 * Validate the stamp product to gift wrap.
		 * 
		 * @return bool
		 */
		public function validate_stamp_products_gift_wrap( $bool, $cart_item_data, $product_id ) {

			// Return true, if the products is a bundled product.
			if ( wc_pb_maybe_is_bundled_cart_item( $cart_item_data ) ) {
				return true ;
			}

			return $bool ;
		}

	}

}
