<?php

/**
 * Updates.
 * */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'GTW_Updates' ) ) {

	/**
	 * Class.
	 */
	class GTW_Updates {

		/**
		 * DB updates and callbacks that need to be run per version.
		 *
		 * @var array
		 */
		private static $updates = array(
			'update_170' => '1.7.0' ,
				) ;

		/**
		 * Maybe run updates if the versions do not match.
		 */
		public static function maybe_run() {

			// return if it will not run admin.
			if ( ! is_admin() || defined( 'DOING_AJAX' ) || defined( 'DOING_CRON' ) ) {
				return ;
			}

			if ( version_compare( get_option( 'gtw_update_version' ) , GTW_VERSION , '<' ) ) {
				GTW_Install::install() ;
				self::maybe_update_version() ;
			}
		}

		/**
		 * Update GTW DB version to current if unavailable.
		 */
		public static function update_version( $version = null ) {
			update_option( 'gtw_update_version' , ! is_numeric( $version ) ? GTW_VERSION : $version  ) ;
		}

		/**
		 * Check whether we need to show or run db updates during install.
		 */
		private static function maybe_update_version() {

			if ( ! gtw_check_is_array( self::$updates ) ) {
				self::update_version() ;
				return ;
			}

			$needs_db_update = version_compare( get_option( 'gtw_update_version' ) , max( array_values( self::$updates ) ) , '<' ) ;

			if ( ! $needs_db_update ) {
				self::update_version() ;
				return ;
			}

			//Update GTW database
			foreach ( self::$updates as $update => $updating_version ) {
				if ( is_callable( array( 'GTW_Updates' , $update ) ) ) {
					call_user_func_array( array( 'GTW_Updates' , $update ) , array( $updating_version ) ) ;
				}
			}
		}

		/**
		 * Update Version 1.7.0 data
		 */
		public static function update_170( $updating_version ) {

			if ( ! get_option( 'gtw_settings_enable_custom_customer_message' ) ) {
				self::update_version() ;
				return ;
			}

			$fields = GTW_Custom_Fields_Handler::get_custom_fields() ;
			if ( ! gtw_check_is_array( $fields ) ) {
				self::update_version() ;
				return ;
			}

			foreach ( $fields as $field_key => $field ) {
				if ( 'message' != $field_key ) {
					continue ;
				}

				$status = ( 'yes' == get_option( 'gtw_settings_enable_custom_customer_message' ) ) ? 'gtw_active' : 'gtw_inactive' ;

				$post_args = array(
					'post_status' => $status ,
					'post_title'  => get_option( 'gtw_settings_gift_wrapper_message_localization' ) ,
						) ;

				$meta_args = array(
					'gtw_description'     => get_option( 'gtw_settings_gift_wrapper_message_count_localization' ) ,
					'gtw_character_count' => get_option( 'gtw_settings_gift_wrapping_message_count' ) ,
						) ;

				// Update Custom field.
				gtw_update_custom_field( $field->get_id() , $meta_args , $post_args ) ;
			}

			self::update_version() ;
		}

	}

}
