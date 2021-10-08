<?php

/**
 * Initialize the Plugin.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'GTW_Install' ) ) {

	/**
	 * Class.
	 */
	class GTW_Install {

		/**
		 *  Class initialization.
		 */
		public static function init() {
			// Update
			add_action( 'init' , array( 'GTW_Updates' , 'maybe_run' ) , 1 ) ;
			// Plugin actin link.
			add_filter( 'plugin_action_links_' . GTW_PLUGIN_SLUG , array( __CLASS__ , 'settings_link' ) ) ;
		}

		/**
		 * Install.
		 */
		public static function install() {
			self::set_default_values() ; // default values.
			self::create_default_fields() ; // Create default fields.
			self::update_version() ;
		}

		/**
		 * Update current version.
		 */
		private static function update_version() {
			update_option( 'gtw_version' , GTW_VERSION ) ;
		}

		/**
		 *  Settings link.
		 */
		public static function settings_link( $links ) {
			$setting_page_link = '<a href="' . gtw_get_settings_page_url() . '">' . esc_html__( 'Settings' , 'gift-wrapper-for-woocommerce' ) . '</a>' ;

			array_unshift( $links , $setting_page_link ) ;

			return $links ;
		}

		/**
		 *  Create the default fields.
		 */
		public static function create_default_fields() {

			if ( 'yes' == get_option( 'gtw_default_fields_created' ) ) {
				return ;
			}

			// Check if already fields are created.
			$fields = gtw_get_custom_field_ids() ;
			if ( gtw_check_is_array( $fields ) ) {
				return ;
			}

			// Create default fields.
			GTW_Custom_Fields_Handler::create_default_fields() ;

			update_option( 'gtw_default_fields_created' , 'yes' ) ;
		}

		/**
		 *  Set settings default values.
		 */
		public static function set_default_values() {

			if ( ! class_exists( 'GTW_Settings' ) ) {
				include_once( GTW_PLUGIN_PATH . '/inc/admin/menu/class-gtw-settings.php' ) ;
			}

			// Get the settings.
			$settings = GTW_Settings::get_settings_pages() ;

			foreach ( $settings as $setting ) {
				$sections = $setting->get_sections() ;
				if ( ! gtw_check_is_array( $sections ) ) {
					continue ;
				}

				foreach ( $sections as $section_key => $section ) {
					$settings_array = $setting->get_settings( $section_key ) ;
					foreach ( $settings_array as $value ) {
						if ( isset( $value[ 'default' ] ) && isset( $value[ 'id' ] ) ) {
							if ( get_option( $value[ 'id' ] ) === false ) {
								add_option( $value[ 'id' ] , $value[ 'default' ] ) ;
							}
						}
					}
				}
			}
		}

	}

	GTW_Install::init() ;
}
