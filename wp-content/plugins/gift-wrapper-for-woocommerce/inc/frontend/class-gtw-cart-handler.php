<?php

/**
 *  Handles the Cart.
 * */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'GTW_Cart_Handler' ) ) {

	/**
	 * Class.
	 * */
	class GTW_Cart_Handler {

		/**
		 * Class Initialization.
		 * */
		public static function init() {

			// Add item data to the gift wrapper.
			add_action( 'woocommerce_add_cart_item_data' , array( __CLASS__ , 'add_cart_item_data' ) , 20 , 4 ) ;
			// Gets cart item to display in cart.
			add_action( 'woocommerce_get_item_data' , array( __CLASS__ , 'display_custom_item_data' ) , 10 , 2 ) ;
			// Alter cart item thumbnail.
			add_action( 'woocommerce_cart_item_thumbnail' , array( __CLASS__ , 'alter_cart_item_thumbnail' ) , 10 , 3 ) ;
			// Alter product price based on gift wrapper selection.
			add_action( 'woocommerce_get_cart_item_from_session' , array( __CLASS__ , 'set_price' ) , 10 , 3 ) ;
			// set cart quantity in cart page when product is a gift wrapper.
			add_filter( 'woocommerce_cart_item_quantity' , array( __CLASS__ , 'set_cart_item_quantity' ) , 10 , 2 ) ;
			// Remove the gift wrapper based on restriction from cart.
			add_action( 'wp' , array( __CLASS__ , 'remove_gift_wrapper_from_cart' ) ) ;
			// Remove the gift wrapper based on restriction from cart via cart ajax.
			add_action( 'woocommerce_before_cart' , array( __CLASS__ , 'remove_gift_wrapper_from_cart_ajax' ) ) ;
			// Remove the gift wrapper based on restriction from cart via checkout ajax.
			add_action( 'woocommerce_review_order_before_cart_contents' , array( __CLASS__ , 'remove_gift_wrapper_from_cart_ajax' ) ) ;
			// Remove order gift wrapper from the cart when the cart is empty.
			add_action( 'woocommerce_cart_item_removed' , array( __CLASS__ , 'remove_order_gift_wrapper_cart_empty' ) , 10 , 2 ) ;
		}

		/**
		 * Add item data to the gift wrapper.
		 * 
		 * @return array
		 * */
		public static function add_cart_item_data( $cart_item_data, $product_id, $variation_id, $quantity = 1 ) {
			$post_data = $_REQUEST ;

			//Return if the Gift wrapper is not enabled.
			if ( ! isset( $post_data[ 'gtw_enable_gift_wrapper' ] ) || empty( $post_data[ 'gtw_enable_gift_wrapper' ] ) ) {
				return $cart_item_data ;
			}

			// Validate the gift wrapper cart item data. .
			if ( apply_filters( 'gtw_validate_gift_wrapper_cart_item_data' , false , $cart_item_data , $product_id , $variation_id , $quantity ) ) {
				return $cart_item_data ;
			}

			// Check if the designs are enabled.
			if ( gtw_display_gift_wrapper_designs() ) {
				//Return if the rule is not valid.
				$rule_id = isset( $post_data[ 'gtw_gift_wrapper_item' ] ) ? wc_clean( wp_unslash( $post_data[ 'gtw_gift_wrapper_item' ] ) ) : '' ;
				if ( ! $rule_id ) {
					return $cart_item_data ;
				}

				//Return if the rule is not exists.
				$rule = gtw_get_product_rule_by_id( $product_id , $rule_id ) ;
				if ( ! $rule[ 'id' ] ) {
					return $cart_item_data ;
				}

				$rule_id = $rule[ 'id' ] ;
				$price   = $rule[ 'price' ] ;
			} else {
				$rule_id = '' ;
				$price   = get_option( 'gtw_settings_gift_wrapper_design_price' ) ;
			}

			$product_id = ! empty( $variation_id ) ? $variation_id : $product_id ;
			$product    = wc_get_product( $product_id ) ;

			$custom_field_data = array() ;

			// Check if the fields post data is exists.
			if ( isset( $post_data[ 'gtw_wrapper_field' ] ) ) {
				$custom_field_data = GTW_Custom_Fields_Handler::prepare_fields_cart_item_data( $post_data[ 'gtw_wrapper_field' ] ) ;
			}

			// Prepare the gift wrapper item data.
			$cart_item_data[ 'gtw_gift_wrapper' ] = array(
				'rule_id'       => $rule_id ,
				'product_price' => floatval( $product->get_price() ) + floatval( $price ) ,
				'price'         => floatval( $price ) ,
				'fields'        => $custom_field_data ,
				'mode'          => 'product'
					) ;

			return $cart_item_data ;
		}

		/**
		 * Gets cart item to display in the cart.
		 * 
		 * @return array
		 * */
		public static function display_custom_item_data( $item_data, $cart_item ) {

			if ( ! isset( $cart_item[ 'gtw_gift_wrapper' ] ) ) {
				return $item_data ;
			}

			$gift_wrapper = $cart_item[ 'gtw_gift_wrapper' ] ;

			//Return if the rule does not exists.
			$rule         = gtw_get_product_rule_by_id( $cart_item[ 'product_id' ] , $gift_wrapper[ 'rule_id' ] ) ;
			$show_designs = gtw_display_gift_wrapper_designs() ;
			if ( $show_designs && ! $rule[ 'id' ] ) {
				return $item_data ;
			}

			$gift_wrapper_item_data = array() ;

			$product_id = ! empty( $cart_item[ 'variation_id' ] ) ? $cart_item[ 'variation_id' ] : $cart_item[ 'product_id' ] ;
			$product    = wc_get_product( $product_id ) ;

			if ( $show_designs ) {
				$design_value = $rule[ 'name' ] . ' (' . gtw_price( gtw_get_price_to_display( $product , gtw_convert_price( $rule[ 'price' ] ) ) ) . ')' ;
			} else {
				$design_value = gtw_price( gtw_get_price_to_display( $product , gtw_convert_price( gtw_get_default_design_price() ) ) ) ;
			}

			// Gift Wrapper Name.
			$gift_wrapper_item_data[] = array(
				'name'    => get_option( 'gtw_settings_gift_wrapper_design_localization' ) ,
				'value'   => $gift_wrapper[ 'rule_id' ] ,
				'display' => $design_value
					) ;

			// Display the fields value in cart item data. 
			if ( ! empty( $gift_wrapper[ 'fields' ] ) && gtw_check_is_array( $gift_wrapper[ 'fields' ] ) ) {

				$custom_field_ids = GTW_Custom_Fields_Handler::get_custom_fields() ;
				foreach ( $gift_wrapper[ 'fields' ] as $field_key => $field_value ) {
					if ( ! isset( $custom_field_ids[ $field_key ] ) ) {
						continue ;
					}

					if ( empty( $field_value ) ) {
						continue ;
					}

					// Gift Wrapper message.
					$gift_wrapper_item_data[] = array(
						'name'    => $custom_field_ids[ $field_key ]->get_name() ,
						'display' => $field_value
							) ;
				}
			}

			return array_merge( $item_data , $gift_wrapper_item_data ) ;
		}

		/**
		 * Alter cart item thumbnail.
		 * 
		 * @return mixed
		 * */
		public static function alter_cart_item_thumbnail( $thumbnail, $cart_item, $cart_item_key ) {

			if ( ! isset( $cart_item[ 'gtw_gift_wrapper' ][ 'mode' ] ) ) {
				return $thumbnail ;
			}

			if ( 'order' != $cart_item[ 'gtw_gift_wrapper' ][ 'mode' ] ) {
				return $thumbnail ;
			}

			//Return if the rule does not exists.
			$rule = gtw_get_rule( $cart_item[ 'gtw_gift_wrapper' ][ 'rule_id' ] ) ;
			if ( gtw_display_gift_wrapper_designs() && ! $rule->exists() ) {
				return $thumbnail ;
			}

			// Gift order image.
			return $rule->get_image() ;
		}

		/**
		 * Alter product price based on gift wrapper selection.
		 * 
		 * @return void.
		 */
		public static function set_price( $session_data, $values, $key ) {
			// Return if the current product is not a gift wrapper.
			if ( ! isset( $session_data[ 'gtw_gift_wrapper' ] ) ) {
				return $session_data ;
			}

			if ( ! is_object( $session_data[ 'data' ] ) ) {
				return $session_data ;
			}

			$price = apply_filters( 'gtw_gift_wrapper_product_price' , gtw_convert_price( floatval( $session_data[ 'gtw_gift_wrapper' ][ 'price' ] ) ) , $session_data[ 'data' ] ) ;

			if ( 'order' == $session_data[ 'gtw_gift_wrapper' ][ 'mode' ] ) {
				// Add the order gift wrapper quantity.
				$session_data[ 'quantity' ] = gtw_order_gift_wrapper_cart_qty() ;
			} else {
				$price = floatval( $session_data[ 'data' ]->get_price() ) + $price ;
			}

			$session_data[ 'data' ]->set_price( $price ) ;

			return $session_data ;
		}

		/**
		 * Set cart quantity as non editable in the cart.
		 * 
		 * @return int/string
		 */
		public static function set_cart_item_quantity( $quantity, $cart_item_key ) {
			// Return if a cart object is not initialized.
			if ( ! is_object( WC()->cart ) ) {
				return $quantity ;
			}

			$cart_items = WC()->cart->get_cart() ;

			// Check if the product is a gift wrapper product.
			if ( ! isset( $cart_items[ $cart_item_key ][ 'gtw_gift_wrapper' ][ 'mode' ] ) ) {
				return $quantity ;
			}

			if ( 'order' == $cart_items[ $cart_item_key ][ 'gtw_gift_wrapper' ][ 'mode' ] ) {
				$quantity = gtw_order_gift_wrapper_cart_qty() ;
			}

			return $quantity ;
		}

		/**
		 * Remove the gift wrapper based on restriction from the cart.
		 * 
		 * @return void
		 * */
		public static function remove_gift_wrapper_from_cart() {
			if ( isset( $_REQUEST[ 'payment_method' ] ) || isset( $_REQUEST[ 'woocommerce-cart-nonce' ] ) ) {
				return ;
			}

			self::remove_gift_wrapper() ;
		}

		/**
		 * Remove the gift wrapper based on restriction from the cart via ajax.
		 * 
		 * @return void
		 * */
		public static function remove_gift_wrapper_from_cart_ajax() {
			if ( ! isset( $_REQUEST[ 'payment_method' ] ) && ! isset( $_REQUEST[ 'woocommerce-cart-nonce' ] ) ) {
				return ;
			}

			self::remove_gift_wrapper() ;
		}

		/**
		 * Remove the gift wrapper based on restriction.
		 * 
		 * @return void
		 * */
		public static function remove_gift_wrapper() {
			$order_gift_wrapper_cart_key = gtw_order_gift_wrapper_in_cart() ;

			if ( $order_gift_wrapper_cart_key ) {
				self::remove_order_gift_wrapper( $order_gift_wrapper_cart_key ) ;
			} else {
				self::remove_product_gift_wrapper() ;
			}
		}

		/**
		 * Remove the order gift wrapper based on restriction.
		 * 
		 * @return void
		 * */
		public static function remove_order_gift_wrapper( $cart_item_key ) {

			// Return if the cart object is not initialized.
			if ( ! is_object( WC()->cart ) ) {
				return ;
			}

			// Return if the cart contains valid order gift wrapper.
			if ( GTW_Frontend::validate_cart_product_category() ) {
				return ;
			}

			// Remove order gift wrapper.
			WC()->cart->remove_cart_item( $cart_item_key ) ;

			// Error Notice.
			wc_add_notice( get_option( 'gtw_settings_order_gift_wrapper_not_eligible_notice_localization' ) , 'notice' ) ;
		}

		/**
		 * Remove the product gift wrapper based on restriction.
		 * 
		 * @return void
		 * */
		public static function remove_product_gift_wrapper() {
			// Return if the cart object is not initialized.
			if ( ! is_object( WC()->cart ) ) {
				return ;
			}

			$products_removed = false ;

			foreach ( WC()->cart->get_cart() as $key => $value ) {

				if ( ! isset( $value[ 'gtw_gift_wrapper' ] ) ) {
					continue ;
				}

				if ( GTW_Frontend::validate_product_category( $value[ 'product_id' ] , $value[ 'variation_id' ] ) ) {
					continue ;
				}

				// Remove the gift wrapper from the cart.
				WC()->cart->remove_cart_item( $key ) ;

				$products_removed = true ;
			}

			// Error Notice.
			if ( $products_removed ) {
				wc_add_notice( get_option( 'gtw_settings_order_gift_wrapper_not_eligible_notice_localization' ) , 'notice' ) ;
			}
		}

		/**
		 * Remove order gift wrapper from the cart when a cart is empty.
		 * 
		 * @return void
		 * */
		public static function remove_order_gift_wrapper_cart_empty( $removed_cart_item_key, $cart ) {
			// Return if the cart object is not initialized.
			if ( ! is_object( WC()->cart ) ) {
				return ;
			}

			// Return if the cart is empty.
			if ( WC()->cart->get_cart_contents_count() == 0 ) {
				return ;
			}

			$order_gift_wrapper_count = gtw_get_order_gift_wrapper_cart_count() ;
			$cart_items_count         = WC()->cart->get_cart_contents_count() - $order_gift_wrapper_count ;

			// Return if products are exists.
			if ( $cart_items_count ) {
				return ;
			}

			// Remove all gift wrappers from the cart.
			WC()->cart->empty_cart() ;

			// Notice.
			wc_add_notice( get_option( 'gtw_settings_order_gift_wrapper_not_eligible_notice_localization' ) , 'notice' ) ;
		}

	}

	GTW_Cart_Handler::init() ;
}
