<?php

/**
 * WPML Multi Currency Compatibility.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'GTW_WPML_Multi_Currency_Compatibility' ) ) {

	/**
	 * Class GTW_WPML_Multi_Currency_Compatibility.
	 */
	class GTW_WPML_Multi_Currency_Compatibility extends GTW_Compatibility {

		/**
		 * Class Constructor.
		 */
		public function __construct() {
			$this->id = 'wpml_multi_currency' ;

			parent::__construct() ;
		}

		/**
		 * Is plugin enabled?.
		 * 
		 *  @return bool
		 * */
		public function is_plugin_enabled() {

			return class_exists( 'WCML_Multi_Currency' ) ;
		}

		/**
		 * Action
		 */
		public function actions() {

			// Convert the price.
			add_filter( 'gtw_convert_price' , array( $this , 'convert_price' ) , 10 , 2 ) ;
		}

		/**
		 * Convert the price.
		 * 
		 * @return string
		 */
		public function convert_price( $price, $currency ) {
			global $woocommerce_wpml ;
			if ( ! is_object( $woocommerce_wpml->multi_currency ) || WCML_MULTI_CURRENCIES_INDEPENDENT != $woocommerce_wpml->settings[ 'enable_multi_currency' ] ) {
				return $price ;
			}

			if ( ! isset( $woocommerce_wpml->multi_currency->prices ) ) {
				return $price ;
			}

			return $woocommerce_wpml->multi_currency->prices->raw_price_filter( $price ) ;
		}

	}

}
