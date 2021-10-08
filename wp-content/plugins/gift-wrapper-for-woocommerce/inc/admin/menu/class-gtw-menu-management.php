<?php

/**
 * Menu Management
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'GTW_Menu_Management' ) ) {

	include_once( 'class-gtw-settings.php' ) ;

	/**
	 * GTW_Menu_Management Class.
	 */
	class GTW_Menu_Management {

		/**
		 * Plugin slug.
		 */
		protected static $plugin_slug = 'gtw' ;

		/**
		 * Menu slug.
		 */
		protected static $menu_slug = 'woocommerce' ;

		/**
		 * Settings slug.
		 */
		protected static $settings_slug = 'gtw_settings' ;

		/**
		 * Class initialization.
		 */
		public static function init() {
			add_action( 'admin_menu' , array( __CLASS__ , 'add_menu_pages' ) ) ;
			add_filter( 'woocommerce_screen_ids' , array( __CLASS__ , 'add_custom_wc_screen_ids' ) , 9 , 1 ) ;
			//Sanitize the settings value. 
			add_filter( 'woocommerce_admin_settings_sanitize_option' , array( __CLASS__ , 'save_custom_fields' ) , 10 , 3 ) ;
		}

		/**
		 * Add Custom Screen IDs in WooCommerce
		 */
		public static function add_custom_wc_screen_ids( $wc_screen_ids ) {
			$screen_ids = gtw_page_screen_ids() ;

			$newscreenids = get_current_screen() ;
			$screenid     = str_replace( 'edit-' , '' , $newscreenids->id ) ;

			// return if current page is not free products page
			if ( ! in_array( $screenid , $screen_ids ) ) {
				return $wc_screen_ids ;
			}

			$wc_screen_ids[] = $screenid ;

			return $wc_screen_ids ;
		}

		/**
		 * Add menu pages
		 */
		public static function add_menu_pages() {
			// Settings Submenu
			$settings_page = add_submenu_page( self::$menu_slug , esc_html__( 'Gift Wrapper' , 'gift-wrapper-for-woocommerce' ) , esc_html__( 'Gift Wrapper' , 'gift-wrapper-for-woocommerce' ) , 'manage_woocommerce' , self::$settings_slug , array( __CLASS__ , 'settings_page' ) ) ;

			add_action( 'load-' . $settings_page , array( __CLASS__ , 'settings_page_init' ) ) ;
		}

		/**
		 * Settings page init
		 */
		public static function settings_page_init() {
			global $current_tab , $current_section , $current_sub_section , $current_action ;

			// Include settings pages.
			$settings = GTW_Settings::get_settings_pages() ;

			$tabs = gtw_get_allowed_setting_tabs() ;

			// Get current tab/section.
			$current_tab = key( $tabs ) ;
			if ( ! empty( $_GET[ 'tab' ] ) ) {
				$sanitize_current_tab = sanitize_title( wp_unslash( $_GET[ 'tab' ] ) ) ; // @codingStandardsIgnoreLine.
				if ( array_key_exists( $sanitize_current_tab , $tabs ) ) {
					$current_tab = $sanitize_current_tab ;
				}
			}

			$section = isset( $settings[ $current_tab ] ) ? $settings[ $current_tab ]->get_sections() : array() ;

			$current_section     = empty( $_REQUEST[ 'section' ] ) ? key( $section ) : sanitize_title( wp_unslash( $_REQUEST[ 'section' ] ) ) ; // @codingStandardsIgnoreLine.
			$current_section     = empty( $current_section ) ? $current_tab : $current_section ;
			$current_sub_section = empty( $_REQUEST[ 'subsection' ] ) ? '' : sanitize_title( wp_unslash( $_REQUEST[ 'subsection' ] ) ) ; // @codingStandardsIgnoreLine.
			$current_action      = empty( $_REQUEST[ 'action' ] ) ? '' : sanitize_title( wp_unslash( $_REQUEST[ 'action' ] ) ) ; // @codingStandardsIgnoreLine.

			do_action( sanitize_key( self::$plugin_slug . '_settings_save_' . $current_tab ) , $current_section ) ;
			do_action( sanitize_key( self::$plugin_slug . '_settings_reset_' . $current_tab ) , $current_section ) ;

			add_action( 'woocommerce_admin_field_gtw_custom_fields' , array( __CLASS__ , 'custom_fields_output' ) ) ;
		}

		/**
		 * Settings page output
		 */
		public static function settings_page() {
			GTW_Settings::output() ;
		}

		/**
		 * Output the custom field settings.
		 * 
		 * @return void
		 */
		public static function custom_fields_output( $options ) {

			GTW_Settings::output_fields( $options ) ;
		}

		/**
		 * Save the custom field settings.
		 * 
		 * @return mixed
		 */
		public static function save_custom_fields( $value, $option, $raw_value ) {

			return GTW_Settings::save_fields( $value , $option , $raw_value ) ;
		}

	}

	GTW_Menu_Management::init() ;
}
