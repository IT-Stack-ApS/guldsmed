<?php

/**
 * Handles the Order.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly
}

if ( ! class_exists( 'GTW_Order_Handler' ) ) {

	/**
	 * Class.
	 */
	class GTW_Order_Handler {

		/**
		 * Class Initialization.
		 */
		public static function init() {

			// Update order meta.
			add_action( 'woocommerce_checkout_create_order_line_item' , array( __CLASS__ , 'adjust_order_item' ) , 10 , 4 ) ;
			// Hide order item meta key.
			add_action( 'woocommerce_hidden_order_itemmeta' , array( __CLASS__ , 'hide_order_item_meta_key' ) , 10 , 2 ) ;
		}

		/**
		 * Adjust order item meta
		 */
		public static function adjust_order_item( $item, $cart_item_key, $values, $order ) {
			if ( ! isset( $values[ 'gtw_gift_wrapper' ] ) ) {
				return ;
			}

			$rule         = gtw_get_product_rule_by_id( $values[ 'product_id' ] , $values[ 'gtw_gift_wrapper' ][ 'rule_id' ] ) ;
			$show_designs = gtw_display_gift_wrapper_designs() ;
			if ( $show_designs && ! $rule[ 'id' ] ) {
				return ;
			}

			// Update order item meta.
			$item->add_meta_data( '_gtw_gift_rule_id' , $values[ 'gtw_gift_wrapper' ][ 'rule_id' ] ) ;
			$item->add_meta_data( '_gtw_gift_price' , $values[ 'gtw_gift_wrapper' ][ 'price' ] ) ;
			$item->add_meta_data( '_gtw_gift_mode' , $values[ 'gtw_gift_wrapper' ][ 'mode' ] ) ;

			if ( $show_designs ) {
				$design_value = $rule[ 'name' ] . ' (' . gtw_price( gtw_convert_price( $rule[ 'price' ] ) ) . ')' ;
			} else {
				$design_value = gtw_price( gtw_convert_price( gtw_get_default_design_price() ) ) ;
			}

			$item->add_meta_data( get_option( 'gtw_settings_gift_wrapper_design_localization' ) , $design_value ) ;

			// Update the fields order item. 
			self::fields_order_item( $item , $values[ 'gtw_gift_wrapper' ] ) ;
		}

		/**
		 * Update the fields order item.
		 * 
		 * @return array
		 * */
		public static function fields_order_item( $item, $values ) {
			// Check if the fields post data is exists.
			if ( ! isset( $values[ 'fields' ] ) ) {
				return ;
			}

			$custom_field_ids = GTW_Custom_Fields_Handler::get_custom_fields() ;

			foreach ( $values[ 'fields' ] as $field_key => $field_value ) {

				if ( ! isset( $custom_field_ids[ $field_key ] ) ) {
					continue ;
				}

				if ( empty( $field_value ) ) {
					continue ;
				}

				$item->add_meta_data( $custom_field_ids[ $field_key ]->get_name() , $field_value ) ;
			}

			$item->add_meta_data( '_gtw_gift_fields' , $values[ 'fields' ] ) ;
		}

		/**
		 * Hidden Custom Order item meta.
		 * */
		public static function hide_order_item_meta_key( $hidden_order_itemmeta ) {

			$custom_order_itemmeta = array( '_gtw_gift_rule_id' , '_gtw_gift_price' , '_gtw_gift_message' , '_gtw_gift_mode' , '_gtw_gift_fields' ) ;

			return array_merge( $hidden_order_itemmeta , $custom_order_itemmeta ) ;
		}

	}

	GTW_Order_Handler::init() ;
}
