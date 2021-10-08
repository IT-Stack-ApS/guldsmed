<?php

/**
 * Compatibility Instances Class.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'GTW_Compatibility_Instances' ) ) {

	/**
	 * Class GTW_Compatibility_Instances
	 */
	class GTW_Compatibility_Instances {

		/**
		 * Compatibilities.
		 * 
		 * @var array
		 * */
		private static $compatibilities ;

		/**
		 * Get Compatibilities.
		 * 
		 * @var array
		 */
		public static function instance() {
			if ( is_null( self::$compatibilities ) ) {
				self::$compatibilities = self::load_compatibilities() ;
			}

			return self::$compatibilities ;
		}

		/**
		 * Load all Compatibilities.
		 */
		public static function load_compatibilities() {

			if ( ! class_exists( 'GTW_Compatibility' ) ) {
				include GTW_PLUGIN_PATH . '/inc/abstracts/abstract-gtw-compatibility.php' ;
			}

			$default_compatibility_classes = array(
				'wpml'                => 'GTW_WPML_Compatibility' ,
				'wpml-multi-currency' => 'GTW_WPML_Multi_Currency_Compatibility' ,
				'product-bundles'     => 'GTW_Product_Bundles_Compatibility' ,
					) ;

			foreach ( $default_compatibility_classes as $file_name => $compatibility_class ) {

				// Include file.
				include 'class-' . $file_name . '.php' ;

				// Add compatibility.
				self::add_compatibility( new $compatibility_class() ) ;
			}
		}

		/**
		 * Add a Compatibility.
		 */
		public static function add_compatibility( $compatibility ) {

			self::$compatibilities[ $compatibility->get_id() ] = $compatibility ;

			return new self() ;
		}

		/**
		 * Get compatibility by id.
		 * 
		 * @var Object
		 */
		public static function get_compatibility_by_id( $module_id ) {
			$compatibilities = self::instance() ;

			return isset( $compatibilities[ $compatibility_id ] ) ? $compatibilities[ $compatibility_id ] : false ;
		}

	}

}
