<?php

/**
 * Frontend functions.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! function_exists( 'gtw_get_order_gift_wrapper_cart_count' ) ) {

	/**
	 * Get order gift wrapper count in cart.
	 *
	 * @return bool/String
	 */
	function gtw_get_order_gift_wrapper_cart_count() {
		$count = 0 ;
		// Return if a cart object is not initialized.
		if ( ! is_object( WC()->cart ) ) {
			return $count ;
		}

		// Return 0 if the cart is empty.
		$cart_contents = WC()->cart->get_cart() ;
		if ( ! gtw_check_is_array( $cart_contents ) ) {
			return $count ;
		}

		foreach ( $cart_contents as $key => $value ) {
			if ( ! isset( $value[ 'gtw_gift_wrapper' ][ 'mode' ] ) ) {
				continue ;
			}

			if ( 'order' != $value[ 'gtw_gift_wrapper' ][ 'mode' ] ) {
				continue ;
			}

			$count ++ ;
		}

		return $count ;
	}

}

if ( ! function_exists( 'gtw_product_gift_wrapper_in_cart' ) ) {

	/**
	 * Get product gift wrapper in cart.
	 *
	 * @return bool/String
	 */
	function gtw_product_gift_wrapper_in_cart() {
		// Return if a cart object is not initialized.
		if ( ! is_object( WC()->cart ) ) {
			return false ;
		}

		// Return false if the cart is empty.
		$cart_contents = WC()->cart->get_cart() ;
		if ( ! gtw_check_is_array( $cart_contents ) ) {
			return false ;
		}

		foreach ( $cart_contents as $key => $value ) {
			if ( ! isset( $value[ 'gtw_gift_wrapper' ][ 'mode' ] ) ) {
				continue ;
			}

			if ( 'product' != $value[ 'gtw_gift_wrapper' ][ 'mode' ] ) {
				continue ;
			}

			return $key ;
		}

		return false ;
	}

}

if ( ! function_exists( 'gtw_order_gift_wrapper_in_cart' ) ) {

	/**
	 * Get order gift wrapper in cart.
	 *
	 * @return bool/String
	 */
	function gtw_order_gift_wrapper_in_cart() {
		// Return if a cart object is not initialized.
		if ( ! is_object( WC()->cart ) ) {
			return false ;
		}

		// Return false if the cart is empty.
		$cart_contents = WC()->cart->get_cart() ;
		if ( ! gtw_check_is_array( $cart_contents ) ) {
			return false ;
		}

		foreach ( $cart_contents as $key => $value ) {
			if ( ! isset( $value[ 'gtw_gift_wrapper' ][ 'mode' ] ) ) {
				continue ;
			}

			if ( 'order' != $value[ 'gtw_gift_wrapper' ][ 'mode' ] ) {
				continue ;
			}

			return $key ;
		}

		return false ;
	}

}

if ( ! function_exists( 'gtw_get_order_gift_wrapper_exclude_products' ) ) {

	/**
	 * Get the order gift wrapper exclude products.
	 * 
	 * @return bool
	 */
	function gtw_get_order_gift_wrapper_exclude_products() {

		$order_exclude_products = array() ;
		$product_restriction    = get_option( 'gtw_settings_gift_wrapping_product_restriction' ) ;
		$exclude_type           = get_option( 'gtw_settings_exclude_order_gift_wrapper_message_type' ) ;

		// Return true if the option is all products.
		if ( '1' == $product_restriction || '1' == $exclude_type ) {
			return $order_exclude_products ;
		}

		// Return if a cart object is not initialized.
		if ( ! is_object( WC()->cart ) ) {
			return $order_exclude_products ;
		}

		// Return empty array if the cart is empty.
		$cart_contents = WC()->cart->get_cart() ;
		if ( ! gtw_check_is_array( $cart_contents ) ) {
			return $order_exclude_products ;
		}

		$include_products   = get_option( 'gtw_settings_gift_wrapping_include_product' ) ;
		$exclude_products   = get_option( 'gtw_settings_gift_wrapping_exclude_product' ) ;
		$include_categories = get_option( 'gtw_settings_gift_wrapping_include_categories' ) ;
		$exclude_categories = get_option( 'gtw_settings_gift_wrapping_exclude_categories' ) ;

		foreach ( $cart_contents as $cart_content ) {

			// Don't consider if the product is a gift wrapper.
			if ( isset( $cart_content[ 'gtw_gift_wrapper' ] ) ) {
				continue ;
			}

			$product_id = ! empty( $cart_content[ 'variation_id' ] ) ? $cart_content[ 'variation_id' ] : $cart_content[ 'product_id' ] ;

			switch ( $product_restriction ) {
				case '2':
					// Includeed products.
					if ( ! in_array( $cart_content[ 'product_id' ] , $include_products ) && ! in_array( $cart_content[ 'variation_id' ] , $include_products ) ) {
						$order_exclude_products[] = $product_id ;
					}
					break ;
				case '3':
					// Excluded products.
					if ( in_array( $cart_content[ 'product_id' ] , $exclude_products ) || in_array( $cart_content[ 'variation_id' ] , $exclude_products ) ) {
						$order_exclude_products[] = $product_id ;
					}
					break ;
				case '4':
					// All Categories.
					$product_categories = get_the_terms( $cart_content[ 'product_id' ] , 'product_cat' ) ;
					if ( ! gtw_check_is_array( $product_categories ) ) {
						$order_exclude_products[] = $product_id ;
					}
					break ;
				case '5':
					$return             = false ;
					// Included categories.
					$product_categories = get_the_terms( $cart_content[ 'product_id' ] , 'product_cat' ) ;
					if ( gtw_check_is_array( $product_categories ) ) {
						foreach ( $product_categories as $product_category ) {
							if ( ! in_array( $product_category->term_id , $include_categories ) ) {
								$return         = true ;
								$category_ids[] = $product_category->term_id ;
							}
						}
					}

					// If true.
					if ( $return ) {
						$order_exclude_products[] = $product_id ;
					}

					break ;
				case '6':
					// Excluded categories.
					$product_categories = get_the_terms( $cart_content[ 'product_id' ] , 'product_cat' ) ;
					if ( gtw_check_is_array( $product_categories ) ) {
						foreach ( $product_categories as $product_category ) {
							if ( in_array( $product_category->term_id , $exclude_categories ) ) {
								$order_exclude_products[] = $product_id ;
							}
						}
					}
			}
		}

		return $order_exclude_products ;
	}

}

if ( ! function_exists( 'gtw_order_gift_wrapper_cart_qty' ) ) {

	/**
	 * Order gift wrapper cart quantity.
	 *
	 * @return string
	 */
	function gtw_order_gift_wrapper_cart_qty() {
		if ( '2' != get_option( 'gtw_settings_multiply_gift_wrapper_price' ) ) {
			return 1 ;
		}

		$cart_qty = gtw_get_valid_order_gift_wrapper_cart_qty() ;

		return apply_filters( 'gtw_order_gift_wrapper_cart_qty' , $cart_qty ) ;
	}

}

if ( ! function_exists( 'gtw_get_valid_order_gift_wrapper_cart_qty' ) ) {

	/**
	 * Get valid order gift wrapper cart quantity.
	 *
	 * @return string
	 */
	function gtw_get_valid_order_gift_wrapper_cart_qty() {
		$cart_qty = 0 ;
		// Return if a cart object is not initialized.
		if ( ! is_object( WC()->cart ) ) {
			return $cart_qty ;
		}

		// Return false if the cart is empty.
		$cart_contents = WC()->cart->get_cart() ;
		if ( ! gtw_check_is_array( $cart_contents ) ) {
			return $cart_qty ;
		}

		$exclude_products = gtw_get_order_gift_wrapper_exclude_products() ;

		foreach ( $cart_contents as $cart_content ) {

			// Don't consider if the product is a gift wrapper.
			if ( isset( $cart_content[ 'gtw_gift_wrapper' ] ) ) {
				continue ;
			}

			$product_id = ! empty( $cart_content[ 'variation_id' ] ) ? $cart_content[ 'variation_id' ] : $cart_content[ 'product_id' ] ;

			// Don't consider if the product is excluded.
			if ( in_array( $product_id , $exclude_products ) ) {
				continue ;
			}

			$cart_qty += $cart_content[ 'quantity' ] ;
		}

		return apply_filters( 'gtw_get_valid_order_gift_wrapper_cart_qty' , $cart_qty , $exclude_products ) ;
	}

}
	
