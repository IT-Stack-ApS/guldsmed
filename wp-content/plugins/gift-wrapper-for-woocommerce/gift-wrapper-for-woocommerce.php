<?php

/**
 * Plugin Name: Gift Wrapper for WooCommerce
 * Description: Your users can gift wrap individual products from the product page or gift wrap their entire order. A fee can be charged for gift wrapping.
 * Version: 3.2
 * Author: FantasticPlugins
 * Author URI: http://fantasticplugins.com
 * Text Domain: gift-wrapper-for-woocommerce
 * Domain Path: /languages
 * Woo: 5544168:87cb4050d86f3664c88ec2fd17783c9f
 * Tested up to: 5.8
 * WC tested up to: 5.6
 * WC requires at least: 3.0
 * Copyright: © 2020 FantasticPlugins
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

/* Include once will help to avoid fatal error by load the files when you call init hook */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' ) ;

if ( ! function_exists( 'gtw_maybe_woocommerce_active' ) ) {

	/**
	 * Function to check whether WooCommerce is active or not
	 */
	function gtw_maybe_woocommerce_active() {

		if ( is_multisite() ) {
			// This Condition is for Multi Site WooCommerce Installation
			if ( ! is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) && ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) ) {
				if ( is_admin() ) {
					add_action( 'init' , 'gtw_display_warning_message' ) ;
				}
				return false ;
			}
		} else {
			// This Condition is for Single Site WooCommerce Installation
			if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
				if ( is_admin() ) {
					add_action( 'init' , 'gtw_display_warning_message' ) ;
				}
				return false ;
			}
		}
		return true ;
	}

}

if ( ! function_exists( 'gtw_display_warning_message' ) ) {

	/**
	 * Display Warning message
	 */
	function gtw_display_warning_message() {
		echo "<div class='error'><p> Gift Wrappers for WooCommerce Plugin will not work until WooCommerce Plugin is Activated. Please Activate the WooCommerce Plugin. </p></div>" ;
	}

}

// retrun if WooCommerce is not active
if ( ! gtw_maybe_woocommerce_active() ) {
	return ;
}

// Define constant
if ( ! defined( 'GTW_PLUGIN_FILE' ) ) {
	define( 'GTW_PLUGIN_FILE' , __FILE__ ) ;
}

// Include main class file
if ( ! class_exists( 'FP_Gift_Wrapper' ) ) {
	include_once( 'inc/class-gift-wrapper.php' ) ;
}

// return Gift Wrapper class object
if ( ! function_exists( 'GTW' ) ) {

	function GTW() {
		return FP_Gift_Wrapper::instance() ;
	}

}

// initialize the plugin.
GTW() ;

