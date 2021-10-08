<?php

/**
 * Common functions.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

include_once( 'gtw-layout-functions.php' ) ;
include_once( 'gtw-post-functions.php' ) ;
include_once( 'gtw-template-functions.php' ) ;

if ( ! function_exists( 'gtw_check_is_array' ) ) {

	/**
	 * Check if resource is array.
	 *
	 * @return bool
	 */
	function gtw_check_is_array( $data ) {
		return ( is_array( $data ) && ! empty( $data ) ) ;
	}

}

if ( ! function_exists( 'gtw_page_screen_ids' ) ) {

	/**
	 * Get page screen IDs.
	 *
	 * @return array
	 */
	function gtw_page_screen_ids() {
		$wc_screen_id = sanitize_title( esc_html__( 'WooCommerce' , 'woocommerce' ) ) ;

		return apply_filters(
				'gtw_page_screen_ids' , array(
			'product' ,
			$wc_screen_id . '_page_gtw_settings' ,
				)
				) ;
	}

}

if ( ! function_exists( 'gtw_get_allowed_setting_tabs' ) ) {

	/**
	 * Get setting tabs.
	 *
	 * @return array
	 */
	function gtw_get_allowed_setting_tabs() {

		return apply_filters( 'gtw_settings_tabs_array' , array() ) ;
	}

}

if ( ! function_exists( 'gtw_get_settings_page_url' ) ) {

	/**
	 * Get Settings page URL.
	 *
	 * @return URL
	 */
	function gtw_get_settings_page_url( $args = array() ) {

		$url = add_query_arg( array( 'page' => 'gtw_settings' ) , admin_url( 'admin.php' ) ) ;

		if ( gtw_check_is_array( $args ) ) {
			$url = add_query_arg( $args , $url ) ;
		}

		return $url ;
	}

}

if ( ! function_exists( 'gtw_get_field_page_url' ) ) {

	/**
	 * Get the field page URL.
	 *
	 * @return URL
	 */
	function gtw_get_field_page_url( $args = array() ) {

		$url = add_query_arg( array( 'page' => 'gtw_settings' , 'tab' => 'custom_fields' ) , admin_url( 'admin.php' ) ) ;

		if ( gtw_check_is_array( $args ) ) {
			$url = add_query_arg( $args , $url ) ;
		}

		return $url ;
	}

}

if ( ! function_exists( 'gtw_get_wc_categories' ) ) {

	/**
	 * Get WC Categories.
	 *
	 * @return array
	 */
	function gtw_get_wc_categories() {
		$categories    = array() ;
		$wc_categories = get_terms( 'product_cat' ) ;

		if ( ! gtw_check_is_array( $wc_categories ) ) {
			return $categories ;
		}

		foreach ( $wc_categories as $category ) {
			$categories[ $category->term_id ] = $category->name ;
		}

		return $categories ;
	}

}

if ( ! function_exists( 'gtw_get_popup_gift_wrapper_per_page_count' ) ) {

	/**
	 * Get Popup gift wrapper per page count.
	 *
	 * @return string/integer
	 */
	function gtw_get_popup_gift_wrapper_per_page_count() {
		return 8 ;
	}

}

if ( ! function_exists( 'gtw_get_price_to_display' ) ) {

	/**
	 * Returns the price including or excluding tax, based on the 'woocommerce_tax_display_shop' setting.
	 *
	 * @return bool/String
	 */
	function gtw_get_price_to_display( $product, $price, $qty = 1 ) {

		if ( empty( $price ) ) {
			return 0 ;
		}

		$args = array(
			'qty'   => 1 ,
			'price' => $price ,
				) ;

		return wc_get_price_to_display( $product , $args ) ;
	}

}

if ( ! function_exists( 'gtw_get_total_payable_price' ) ) {

	/**
	 * Returns the total payable price.
	 *
	 * @return float
	 */
	function gtw_get_total_payable_price( $product, $price, $rule = false ) {

		$gift_price    = ( float ) gtw_get_price_to_display( $product , $price ) ;
		$product_price = ( float ) wc_get_price_to_display( $product ) ;

		return $gift_price + $product_price ;
	}

}

if ( ! function_exists( 'gtw_get_default_design_price' ) ) {

	/**
	 * Get the default price.
	 *
	 * @return float
	 */
	function gtw_get_default_design_price() {

		return apply_filters( 'gtw_default_design_price' , floatval( get_option( 'gtw_settings_gift_wrapper_design_price' ) ) ) ;
	}

}

if ( ! function_exists( 'gtw_convert_price' ) ) {

	/**
	 * Convert the price.
	 *
	 * @return float
	 */
	function gtw_convert_price( $price, $currency = false ) {

		return apply_filters( 'gtw_convert_price' , $price , $currency ) ;
	}

}

if ( ! function_exists( 'gtw_create_new_product' ) ) {

	/**
	 * Create new a product.
	 * 
	 * @return Integer
	 * */
	function gtw_create_new_product( $product_title ) {
		$args = array(
			'post_author' => get_current_user_id() ,
			'post_status' => 'publish' ,
			'post_title'  => $product_title ,
			'post_type'   => 'product' ,
				) ;

		$product_id = wp_insert_post( $args ) ;

		$terms = array( 'exclude-from-search' , 'exclude-from-catalog' ) ; // For hidden..
		wp_set_post_terms( $product_id , $terms , 'product_visibility' , false ) ;

		$meta_keys = array(
			'_stock_status'      => 'instock' ,
			'total_sales'        => '0' ,
			'_downloadable'      => 'no' ,
			'_virtual'           => 'yes' ,
			'_regular_price'     => '0' ,
			'_price'             => '0' ,
			'_sale_price'        => '' ,
			'_featured'          => '' ,
			'_sold_individually' => 'yes' ,
			'_manage_stock'      => 'no' ,
			'_backorders'        => 'no' ,
			'_stock'             => '' ,
				) ;

		foreach ( $meta_keys as $key => $value ) {
			update_post_meta( $product_id , sanitize_key( $key ) , $value ) ;
		}

		return $product_id ;
	}

}

if ( ! function_exists( 'gtw_get_order_gift_wrapper_product' ) ) {

	/**
	 * Get order gift wrapper product.
	 *
	 * @return string/integer
	 */
	function gtw_get_order_gift_wrapper_product() {

		$selected_product = get_option( 'gtw_settings_order_gift_wrapper_product' ) ;

		if ( ! gtw_check_is_array( $selected_product ) ) {
			return false ;
		}

		return reset( $selected_product ) ;
	}

}

if ( ! function_exists( 'gtw_get_custom_field_translate_string' ) ) {

	/**
	 * Get the custom field translated string.
	 *
	 * @return mixed
	 */
	function gtw_get_custom_field_translate_string( $option_name, $value, $language = null ) {

		return apply_filters( 'gtw_custom_field_translate_string' , $value , $option_name , $language ) ;
	}

}

if ( ! function_exists( 'gtw_get_product_rule_image_url' ) ) {

	/**
	 * Get the product rule image URL.
	 *
	 * @return mixed
	 */
	function gtw_get_product_rule_image_url( $image_id ) {

		return empty( $image_id ) ? wc_placeholder_img_src() : wp_get_attachment_url( $image_id ) ;
	}

}
