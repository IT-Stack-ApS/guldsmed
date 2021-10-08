<?php

/**
 * WPML Compatibility.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'GTW_WPML_Compatibility' ) ) {

	/**
	 * Class GTW_WPML_Compatibility.
	 */
	class GTW_WPML_Compatibility extends GTW_Compatibility {

		/**
		 * Context
		 * 
		 * @var string
		 */
		private $context = 'gift-wrapper-for-woocommerce' ;

		/**
		 * Class Constructor.
		 */
		public function __construct() {
			$this->id = 'wpml' ;

			parent::__construct() ;
		}

		/**
		 * Is plugin enabled?.
		 * 
		 *  @return bool
		 * */
		public function is_plugin_enabled() {

			return function_exists( 'icl_register_string' ) ;
		}

		/**
		 * Admin Action.
		 */
		public function admin_action() {

			// Register the string.
			add_filter( 'admin_init' , array( $this , 'register_string' ) , 10 , 3 ) ;
		}

		/**
		 * Action
		 */
		public function actions() {

			// Get the string.
			add_filter( 'gtw_custom_field_translate_string' , array( $this , 'translate_string' ) , 10 , 3 ) ;
		}

		/**
		 * Register the string in WPML.
		 * 
		 * @return bool
		 */
		public function register_string() {

			$custom_field_ids = gtw_get_custom_field_ids() ;
			// Return if the custom field ids not exists.
			if ( ! gtw_check_is_array( $custom_field_ids ) ) {
				return ;
			}

			foreach ( $custom_field_ids as $custom_field_id ) {
				$custom_field = gtw_get_custom_field( $custom_field_id ) ;

				$register_strings = array(
					'gtw_custom_field_name_' . $custom_field_id        => $custom_field->get_name() ,
					'gtw_custom_field_description_' . $custom_field_id => $custom_field->get_description() ,
						) ;

				foreach ( $register_strings as $name => $value ) {

					// Registering the custom field string.
					icl_register_string( $this->context , $name , $value ) ;
				}
			}
		}

		/**
		 * Get the string in WPML.
		 * 
		 * @return string
		 */
		public function translate_string( $value, $option_name, $language ) {
			$has_translation = null ;

			return icl_translate( $this->context , $option_name , $value , false , $has_translation , $language ) ;
		}

	}

}
