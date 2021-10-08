<?php

/**
 * Frontend Assets.
 * */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'GTW_Fronend_Assets' ) ) {

	/**
	 * Class.
	 */
	class GTW_Fronend_Assets {

		/**
		 * Suffix.
		 * 
		 * @var string
		 */
		private static $suffix ;

		/**
		 * In Footer.
		 * 
		 * @var bool
		 */
		private static $in_footer = false ;

		/**
		 * Class Initialization.
		 */
		public static function init() {

			self::$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' ;

			// Enqueue script in footer.
			if ( '2' == get_option( 'gtw_settings_frontend_enqueue_scripts_type' ) ) {
				self::$in_footer = true ;
			}

			add_action( 'wp_enqueue_scripts' , array( __CLASS__ , 'external_js_files' ) , 999 ) ;
			add_action( 'wp_enqueue_scripts' , array( __CLASS__ , 'external_css_files' ) , 999 ) ;
		}

		/**
		 * Enqueue external JS files.
		 */
		public static function external_js_files() {

			// Lightcase.
			wp_register_script( 'lightcase' , GTW_PLUGIN_URL . '/assets/js/lightcase' . self::$suffix . '.js' , array( 'jquery' ) , GTW_VERSION , self::$in_footer ) ;

			// Enhanced lightcase.
			wp_enqueue_script( 'gtw-lightcase' , GTW_PLUGIN_URL . '/assets/js/gtw-lightcase-enhanced.js' , array( 'jquery' , 'jquery-blockui' , 'lightcase' ) , GTW_VERSION , self::$in_footer ) ;

			// Frontend.
			wp_enqueue_script( 'gtw-frontend' , GTW_PLUGIN_URL . '/assets/js/frontend.js' , array( 'jquery' , 'jquery-blockui' , 'lightcase' ) , GTW_VERSION , self::$in_footer ) ;
			wp_localize_script(
					'gtw-frontend' , 'gtw_frontend_params' , array(
				'gift_wrapper_nonce'            => wp_create_nonce( 'gtw-gift-wrapper' ) ,
				'popup_gift_wrapper_nonce'      => wp_create_nonce( 'gtw-popup-gift-wrapper' ) ,
				'remove_order_gift_wrapper_msg' => esc_html__( 'Are you sure you want to remove order gift wrapper?' , 'gift-wrapper-for-woocommerce' ) ,
				'ajaxurl'                       => GTW_ADMIN_AJAX_URL ,
					)
			) ;
		}

		public static function external_css_files() {
			wp_register_style( 'gtw-inline-style' , false , array() , GTW_VERSION ) ; // phpcs:ignore
			wp_enqueue_style( 'gtw-inline-style' ) ;

			//Add inline style.
			self::add_inline_style() ;

			// Lightcase.
			wp_enqueue_style( 'lightcase' , GTW_PLUGIN_URL . '/assets/css/lightcase' . self::$suffix . '.css' , array() , GTW_VERSION ) ;

			// Frontend.
			wp_enqueue_style( 'gtw-frontend' , GTW_PLUGIN_URL . '/assets/css/frontend.css' , array() , GTW_VERSION ) ;
		}

		/**
		 * Add Inline style.
		 */
		public static function add_inline_style() {
			$contents = get_option( 'gtw_settings_custom_css' , '' ) ;

			if ( ! $contents ) {
				return ;
			}

			//Add custom css as inline style.
			wp_add_inline_style( 'gtw-inline-style' , $contents ) ;
		}

	}

	GTW_Fronend_Assets::init() ;
}
