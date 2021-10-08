<?php

/**
 * Handles the custom fields.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly
}

if ( ! class_exists( 'GTW_Custom_Fields_Handler' ) ) {

	/**
	 * Class.
	 */
	class GTW_Custom_Fields_Handler {

		/**
		 * Custom Fields.
		 * 
		 * @var array
		 * */
		private static $custom_fields ;

		/**
		 * Create the default fields.
		 */
		public static function create_default_fields() {
			$default_field_data = array(
				array(
					'field_type' => 'text' ,
					'field_key'  => 'first_name' ,
					'name'       => 'First Name' ,
					'status'     => 'gtw_inactive'
				) ,
				array(
					'field_type' => 'text' ,
					'field_key'  => 'last_name' ,
					'name'       => 'Last Name' ,
					'status'     => 'gtw_inactive'
				) ,
				array(
					'field_type'      => 'textarea' ,
					'field_key'       => 'message' ,
					'name'            => 'Gift Wrap Message' ,
					'status'          => 'gtw_inactive' ,
					'character_count' => '100' ,
					'description'     => 'Remaining/Maximum Characters: {remaining_characters}/{max_characters}'
				)
					) ;

			if ( ! gtw_check_is_array( $default_field_data ) ) {
				return ;
			}

			foreach ( $default_field_data as $default_field ) {

				$post_args = array(
					'post_status' => $default_field[ 'status' ] ,
					'post_title'  => $default_field[ 'name' ] ,
					'menu_order'  => 99999
						) ;

				$meta_args = array(
					'gtw_description'     => isset( $default_field[ 'description' ] ) ? $default_field[ 'description' ] : '' ,
					'gtw_character_count' => isset( $default_field[ 'character_count' ] ) ? $default_field[ 'character_count' ] : '' ,
					'gtw_field_type'      => $default_field[ 'field_type' ] ,
					'gtw_field_key'       => $default_field[ 'field_key' ]
						) ;

				// Create the new custom field.
				gtw_create_new_custom_field( $meta_args , $post_args ) ;
			}
		}

		/**
		 * Gets the custom fields.
		 * 
		 * @return array
		 * */
		public static function get_custom_fields() {

			if ( isset( self::$custom_fields ) ) {
				return self::$custom_fields ;
			}

			self::$custom_fields = array() ;

			// If no one fields are not exists/active.
			$custom_field_ids = gtw_get_active_custom_field_ids() ;
			if ( ! gtw_check_is_array( $custom_field_ids ) ) {
				return self::$custom_fields ;
			}

			foreach ( $custom_field_ids as $custom_field_id ) {
				$field = gtw_get_custom_field( $custom_field_id ) ;

				self::$custom_fields[ $field->get_field_key() ] = $field ;
			}

			return self::$custom_fields ;
		}

		/**
		 * Gets the fields cart item to display in the cart.
		 * 
		 * @return array
		 * */
		public static function prepare_fields_cart_item_data( $post_data ) {
			$fields = array() ;

			// Check if the fields post data is exists.
			if ( ! gtw_check_is_array( $post_data ) ) {
				return $fields ;
			}

			$custom_field_ids = self::get_custom_fields() ;

			foreach ( $custom_field_ids as $field_key => $field ) {

				if ( ! isset( $post_data[ $field_key ] ) ) {
					continue ;
				}

				switch ( $field->get_field_type() ) {
					case 'textarea':
						$value = isset( $post_data[ $field_key ] ) ? wc_sanitize_textarea( wp_unslash( $post_data[ $field_key ] ) ) : '' ; // WPCS: input var ok, CSRF ok.
						break ;
					default:
						$value = isset( $post_data[ $field_key ] ) ? wc_clean( wp_unslash( $post_data[ $field_key ] ) ) : '' ; // WPCS: input var ok, CSRF ok.
						break ;
				}

				$fields[ $field_key ] = $value ;
			}

			return apply_filters( 'gtw_custom_fields_posted_data' , $fields ) ;
		}

	}

}
