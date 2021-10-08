<?php

/**
 * Gift Wrapper for WooCommerce Main Class.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'FP_Gift_Wrapper' ) ) {

	/**
	 * Main FP_Gift_Wrapper Class.
	 * */
	final class FP_Gift_Wrapper {

		/**
		 * Version.
		 * 
		 * @var string
		 * */
		private $version = '3.2' ;

		/**
		 * The single instance of the class.
		 * */
		protected static $_instance = null ;

		/**
		 * Load FP_Gift_Wrapper Class in Single Instance.
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self() ;
			}

			return self::$_instance ;
		}

		/* Cloning has been forbidden */

		public function __clone() {
			_doing_it_wrong( __FUNCTION__ , 'You are not allowed to perform this action!!!' , '1.0' ) ;
		}

		/**
		 * Unserialize the class data has been forbidden.
		 * */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__ , 'You are not allowed to perform this action!!!' , '1.0' ) ;
		}

		/**
		 * Constructor.
		 * */
		public function __construct() {

			/* Include once will help to avoid fatal error by load the files when you call init hook */
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' ) ;

			$this->header_already_sent_problem() ;
			$this->define_constants() ;
			$this->include_files() ;
			$this->init_hooks() ;
		}

		/**
		 * Function to prevent header error that says you have already sent the header.
		 */
		private function header_already_sent_problem() {
			ob_start() ;
		}

		/**
		 * Load plugin the translate files.
		 * */
		private function load_plugin_textdomain() {
			if ( function_exists( 'determine_locale' ) ) {
				$locale = determine_locale() ;
			} else {
				// @todo Remove when start supporting WP 5.0 or later.
				$locale = is_admin() ? get_user_locale() : get_locale() ;
			}

			$locale = apply_filters( 'plugin_locale' , $locale , 'gift-wrapper-for-woocommerce' ) ;

			unload_textdomain( 'gift-wrapper-for-woocommerce' ) ;
			load_textdomain( 'gift-wrapper-for-woocommerce' , WP_LANG_DIR . '/gift-wrapper-for-woocommerce/gift-wrapper-for-woocommerce-' . $locale . '.mo' ) ;
			load_plugin_textdomain( 'gift-wrapper-for-woocommerce' , false , dirname( plugin_basename( GTW_PLUGIN_FILE ) ) . '/languages' ) ;
		}

		/**
		 * Prepare the constants value array.
		 * */
		private function define_constants() {

			$constant_array = array(
				'GTW_VERSION'        => $this->version ,
				'GTW_LOCALE'         => 'gift-wrapper-for-woocommerce' ,
				'GTW_FOLDER_NAME'    => 'gift-wrapper-for-woocommerce' ,
				'GTW_ABSPATH'        => dirname( GTW_PLUGIN_FILE ) . '/' ,
				'GTW_ADMIN_URL'      => admin_url( 'admin.php' ) ,
				'GTW_ADMIN_AJAX_URL' => admin_url( 'admin-ajax.php' ) ,
				'GTW_PLUGIN_SLUG'    => plugin_basename( GTW_PLUGIN_FILE ) ,
				'GTW_PLUGIN_PATH'    => untrailingslashit( plugin_dir_path( GTW_PLUGIN_FILE ) ) ,
				'GTW_PLUGIN_URL'     => untrailingslashit( plugins_url( '/' , GTW_PLUGIN_FILE ) ) ,
					) ;

			$constant_array = apply_filters( 'gtw_define_constants' , $constant_array ) ;

			if ( is_array( $constant_array ) && ! empty( $constant_array ) ) {
				foreach ( $constant_array as $name => $value ) {
					$this->define_constant( $name , $value ) ;
				}
			}
		}

		/**
		 * Define the Constants value.
		 * */
		private function define_constant( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name , $value ) ;
			}
		}

		/**
		 * Include required files.
		 * */
		private function include_files() {

			// Function.
			include_once( GTW_ABSPATH . 'inc/gtw-common-functions.php' ) ;

			// Abstract classes.
			include_once( GTW_ABSPATH . 'inc/abstracts/class-gtw-post.php' ) ;

			include_once( GTW_ABSPATH . 'inc/class-gtw-register-post-types.php' ) ;
			include_once( GTW_ABSPATH . 'inc/class-gtw-register-post-status.php' ) ;

			include_once( GTW_ABSPATH . 'inc/class-gtw-install.php' ) ;
			include_once( GTW_ABSPATH . 'inc/class-gtw-updates.php' ) ;
			include_once( GTW_ABSPATH . 'inc/privacy/class-gtw-privacy.php' ) ;

			// Entity.
			include_once( GTW_ABSPATH . 'inc/entity/class-gtw-rule.php' ) ;
			include_once( GTW_ABSPATH . 'inc/entity/class-gtw-custom-field.php' ) ;

			include_once( GTW_ABSPATH . 'inc/compatibility/class-gtw-compatibility-instances.php') ;

			include_once( GTW_ABSPATH . 'inc/class-gtw-order-handler.php' ) ;
			include_once( GTW_ABSPATH . 'inc/class-gtw-custom-fields-handler.php' ) ;

			if ( is_admin() ) {
				$this->include_admin_files() ;
			}

			if ( ! is_admin() || defined( 'DOING_AJAX' ) ) {
				$this->include_frontend_files() ;
			}
		}

		/**
		 * Include admin files.
		 * */
		private function include_admin_files() {
			include_once( GTW_ABSPATH . 'inc/admin/class-gtw-admin-assets.php' ) ;
			include_once( GTW_ABSPATH . 'inc/admin/class-gtw-admin-ajax.php' ) ;
						include_once( GTW_ABSPATH . 'inc/admin/class-gtw-product-settings.php' ) ;
			include_once( GTW_ABSPATH . 'inc/admin/menu/class-gtw-menu-management.php' ) ;
		}

		/**
		 * Include frontend files.
		 * */
		private function include_frontend_files() {
			include_once( GTW_ABSPATH . 'inc/frontend/gtw-frontend-functions.php' ) ;
			include_once( GTW_ABSPATH . 'inc/frontend/class-gtw-frontend-assets.php' ) ;
			include_once( GTW_ABSPATH . 'inc/frontend/class-gtw-frontend.php' ) ;
			include_once( GTW_ABSPATH . 'inc/frontend/class-gtw-cart-handler.php' ) ;
		}

		/**
		 * Define the hooks.
		 * */
		private function init_hooks() {

			// Init the plugin.
			add_action( 'init' , array( $this , 'init' ) ) ;

			// After plugins loaded.
			add_action( 'plugins_loaded' , array( $this , 'plugins_loaded' ) ) ;

			// Register the plugin.
			register_activation_hook( GTW_PLUGIN_FILE , array( 'GTW_Install' , 'install' ) ) ;
		}

		/**
		 * After plugins loaded..
		 * */
		public function plugins_loaded() {

			GTW_Compatibility_Instances::instance() ;
		}

		/**
		 * Init.
		 * */
		public function init() {

			$this->load_plugin_textdomain() ;
		}

		/**
		 * Templates.
		 * */
		public function templates() {
			return GTW_PLUGIN_PATH . '/templates/' ;
		}

	}

}

