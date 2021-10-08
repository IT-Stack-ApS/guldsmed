<?php

/**
 * Register Custom Post Status.
 *
 * @package
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'GTW_Register_Post_Status' ) ) {

	/**
	 * Class.
	 */
	class GTW_Register_Post_Status {

		/**
		 * Class initialization.
		 */
		public static function init() {
			add_action( 'init' , array( __CLASS__ , 'register_custom_post_status' ) ) ;
		}

		/**
		 * Register custom post status.
		 * 
		 * @return void
		 */
		public static function register_custom_post_status() {
			$custom_post_statuses = array(
				'gtw_active'   => array( 'GTW_Register_Post_Status' , 'active_post_status_args' ) ,
				'gtw_inactive' => array( 'GTW_Register_Post_Status' , 'inactive_post_status_args' ) ,
					) ;

			$custom_post_statuses = apply_filters( 'gtw_add_custom_post_status' , $custom_post_statuses ) ;

			// Return if no post status have to register.
			if ( ! gtw_check_is_array( $custom_post_statuses ) ) {
				return ;
			}

			foreach ( $custom_post_statuses as $post_status => $args_function ) {

				$args = array() ;
				if ( $args_function ) {
					$args = call_user_func_array( $args_function , array() ) ;
				}

				// Register post status.
				register_post_status( $post_status , $args ) ;
			}
		}

		/**
		 * Active custom post status arguments.
		 * 
		 * @retrun array
		 */
		public static function active_post_status_args() {
			$args = apply_filters(
					'gtw_active_post_status_args' , array(
				'label'                     => esc_html_x( 'Enabled' , 'gift-wrapper-for-woocommerce' ) ,
				'public'                    => true ,
				'exclude_from_search'       => false ,
				'show_in_admin_all_list'    => true ,
				'show_in_admin_status_list' => true ,
				/* translators: %s: number of rules */
				'label_count'               => _n_noop( 'Enabled <span class="count">(%s)</span>' , 'Enabled <span class="count">(%s)</span>' , 'gift-wrapper-for-woocommerce' ) ,
					)
					) ;

			return $args ;
		}

		/**
		 * Inactive custom post status arguments.
		 * 
		 * @return array
		 */
		public static function inactive_post_status_args() {
			$args = apply_filters(
					'gtw_inactive_post_status_args' , array(
				'label'                     => esc_html_x( 'Disabled' , 'gift-wrapper-for-woocommerce' ) ,
				'public'                    => true ,
				'exclude_from_search'       => false ,
				'show_in_admin_all_list'    => true ,
				'show_in_admin_status_list' => true ,
				/* translators: %s: number of rules */
				'label_count'               => _n_noop( 'Disabled <span class="count">(%s)</span>' , 'Disabled <span class="count">(%s)</span>' , 'gift-wrapper-for-woocommerce' ) ,
					)
					) ;

			return $args ;
		}

	}

	GTW_Register_Post_Status::init() ;
}
