<?php

/**
 * Admin Assets
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'GTW_Admin_Assets' ) ) {

	/**
	 * Class.
	 */
	class GTW_Admin_Assets {

		/**
		 * Suffix
		 */
		private static $suffix ;

		/**
		 * Class Initialization.
		 */
		public static function init() {
			self::$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' ;

			add_action( 'admin_enqueue_scripts' , array( __CLASS__ , 'external_js_files' ) ) ;
			add_action( 'admin_enqueue_scripts' , array( __CLASS__ , 'external_css_files' ) ) ;
		}

		/**
		 * Enqueue external JS files
		 */
		public static function external_css_files() {
			$screen_ids   = gtw_page_screen_ids() ;
			$newscreenids = get_current_screen() ;
			$screenid     = str_replace( 'edit-' , '' , $newscreenids->id ) ;

			if ( ! in_array( $screenid , $screen_ids ) ) {
				return ;
			}

			wp_enqueue_style( 'gtw-admin' , GTW_PLUGIN_URL . '/assets/css/admin.css' , array() , GTW_VERSION ) ;
		}

		/**
		 * Enqueue external JS files
		 */
		public static function external_js_files() {
			$screen_ids   = gtw_page_screen_ids() ;
			$newscreenids = get_current_screen() ;
			$screenid     = str_replace( 'edit-' , '' , $newscreenids->id ) ;

			$enqueue_array = array(
				'gtw-admin'   => array(
					'callable' => array( 'GTW_Admin_Assets' , 'admin' ) ,
					'restrict' => in_array( $screenid , $screen_ids ) ,
				) ,
				'gtw-select2' => array(
					'callable' => array( 'GTW_Admin_Assets' , 'select2' ) ,
					'restrict' => in_array( $screenid , $screen_ids ) ,
				) ,
					) ;

			$enqueue_array = apply_filters( 'gtw_admin_assets' , $enqueue_array ) ;
			if ( ! gtw_check_is_array( $enqueue_array ) ) {
				return ;
			}

			foreach ( $enqueue_array as $key => $enqueue ) {
				if ( ! gtw_check_is_array( $enqueue ) ) {
					continue ;
				}

				if ( $enqueue[ 'restrict' ] ) {
					call_user_func_array( $enqueue[ 'callable' ] , array() ) ;
				}
			}
		}

		/**
		 * Enqueue Admin end required JS files
		 */
		public static function admin() {
			// Media
			wp_enqueue_media() ;

			// admin
			wp_enqueue_script( 'gtw-admin' , GTW_PLUGIN_URL . '/assets/js/admin.js' , array( 'jquery' , 'jquery-blockui' ) , GTW_VERSION ) ;
			wp_localize_script(
					'gtw-admin' , 'gtw_admin_params' , array(
				'product_nonce'                    => wp_create_nonce( 'gtw-product-nonce' ) ,
				'rules_nonce'                      => wp_create_nonce( 'gtw-rules-nonce' ) ,
				'product_creation_error_message'   => esc_html__( 'Please create a new product or select an existing product to show Entire Order Gift Wrapper option in cart/checkout page' , 'gift-wrapper-for-woocommerce' ) ,
				'empty_product_name_message'       => esc_html__( 'Please enter product name' , 'gift-wrapper-for-woocommerce' ) ,
				'confirm_product_creation_message' => esc_html__( 'Are you sure you want to create a product?' , 'gift-wrapper-for-woocommerce' ) ,
				'remove_rule_alert_message'        => esc_html__( 'Are you sure you want to delete?' , 'gift-wrapper-for-woocommerce' )
					)
			) ;
		}

		/**
		 * Enqueue select2 scripts and CSS
		 */
		public static function select2() {

			wp_enqueue_script( 'gtw-enhanced' , GTW_PLUGIN_URL . '/assets/js/gtw-enhanced.js' , array( 'jquery' , 'select2' ) , GTW_VERSION ) ;
			wp_localize_script(
					'gtw-enhanced' , 'gtw_enhanced_select_params' , array(
				'i18n_no_matches'           => esc_html__( 'No matches found' , 'gift-wrapper-for-woocommerce' ) ,
				'i18n_input_too_short_1'    => esc_html__( 'Please enter 1 or more characters' , 'gift-wrapper-for-woocommerce' ) ,
				'i18n_input_too_short_n'    => esc_html__( 'Please enter %qty% or more characters' , 'gift-wrapper-for-woocommerce' ) ,
				'i18n_input_too_long_1'     => esc_html__( 'Please delete 1 character' , 'gift-wrapper-for-woocommerce' ) ,
				'i18n_input_too_long_n'     => esc_html__( 'Please delete %qty% characters' , 'gift-wrapper-for-woocommerce' ) ,
				'i18n_selection_too_long_1' => esc_html__( 'You can only select 1 item' , 'gift-wrapper-for-woocommerce' ) ,
				'i18n_selection_too_long_n' => esc_html__( 'You can only select %qty% items' , 'gift-wrapper-for-woocommerce' ) ,
				'i18n_load_more'            => esc_html__( 'Loading more results&hellip;' , 'gift-wrapper-for-woocommerce' ) ,
				'i18n_searching'            => esc_html__( 'Searching&hellip;' , 'gift-wrapper-for-woocommerce' ) ,
				'search_nonce'              => wp_create_nonce( 'gtw-search-nonce' ) ,
				'ajaxurl'                   => GTW_ADMIN_AJAX_URL ,
					)
			) ;
		}

	}

	GTW_Admin_Assets::init() ;
}
