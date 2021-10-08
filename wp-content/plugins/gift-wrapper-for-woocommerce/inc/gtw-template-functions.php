<?php

/**
 * Template functions.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! function_exists( 'gtw_get_popup_order_gift_wrapper_classes' ) ) {

	/**
	 *  Get order gift wrapper popup classes.
	 *
	 *  @return array
	 */
	function gtw_get_popup_order_gift_wrapper_classes( $gift_wrapper_id, $current_item = false ) {

		$classes = array(
			'gtw-popup-order-gift-wrapper-item' ,
			'gtw-popup-order-gift-wrapper-list' ,
			'gtw-popup-order-gift-wrapper-item-' . $gift_wrapper_id
				) ;

		if ( $gift_wrapper_id == $current_item ) {
			$classes[] = 'gtw_current' ;
		}

		return $classes ;
	}

}

if ( ! function_exists( 'gtw_get_order_gift_wrapper_classes' ) ) {

	/**
	 *  Get the order gift wrapper classes.
	 *
	 *  @return array
	 */
	function gtw_get_order_gift_wrapper_classes( $show_popup ) {

		$classes = array(
			'button' ,
			'gtw-popup-order-gift-wrapper' ,
				) ;

		if ( $show_popup ) {
			$classes[] = 'gtw-popup-gift-wrapper' ;
		} else {
			$classes[] = 'gtw-add-order-gift-wrapper' ;
		}

		return $classes ;
	}

}

if ( ! function_exists( 'gtw_get_popup_product_gift_wrapper_classes' ) ) {

	/**
	 *  Get product gift wrapper popup classes.
	 *
	 *  @return array
	 */
	function gtw_get_popup_product_gift_wrapper_classes( $gift_wrapper_id, $current_item = false ) {

		$classes = array(
			'gtw-popup-product-gift-wrapper-item' ,
			'gtw-popup-product-gift-wrapper-list' ,
			'gtw-popup-product-gift-wrapper-list-' . $gift_wrapper_id
				) ;

		if ( $gift_wrapper_id == $current_item ) {
			$classes[] = 'gtw_current' ;
		}

		return $classes ;
	}

}

if ( ! function_exists( 'gtw_get_product_gift_wrapper_classes' ) ) {

	/**
	 *  Get product gift wrapper classes.
	 *
	 *  @return array
	 */
	function gtw_get_product_gift_wrapper_classes( $gift_wrapper_id, $current_item ) {

		$classes = array(
			'gtw-product-gift-wrapper-item' ,
			'gtw-gift-wrapper-select' ,
			'gtw-product-gift-wrapper-item-' . $gift_wrapper_id
				) ;

		if ( $gift_wrapper_id == $current_item ) {
			$classes[] = 'gtw_current' ;
		}

		return $classes ;
	}

}

if ( ! function_exists( 'gtw_get_field_wrapper_classes' ) ) {

	/**
	 *  Get the field wrapper classes.
	 *
	 *  @return array
	 */
	function gtw_get_field_wrapper_classes( $field_key ) {

		$classes = array(
			'gtw-gift-wrapper-field' ,
			'gtw-product-gift-wrapper-field-' . $field_key
				) ;

		return $classes ;
	}

}

if ( ! function_exists( 'gtw_get_formatted_textarea_field_description' ) ) {

	/**
	 *  Get the textarea field description.
	 *
	 *  @return array
	 */
	function gtw_get_formatted_textarea_field_description( $field ) {

		$description   = gtw_get_custom_field_description( $field ) ;
		$message_count = empty( $field->get_character_count() ) ? 1000 : $field->get_character_count() ;

		$find_array    = array( '{remaining_characters}' , '{max_characters}' ) ;
		$replace_array = array( '<span class="gtw-gift-wrapper-message-count" data-max="' . $message_count . '">' . $message_count . '</span>' , $message_count ) ;

		return str_replace( $find_array , $replace_array , $description ) ;
	}

}

if ( ! function_exists( 'gtw_display_gift_wrapper_designs' ) ) {

	/**
	 * Display the gift wrapper designs.
	 *
	 *  @return array
	 */
	function gtw_display_gift_wrapper_designs() {

		$return = true ;
		if ( '2' == get_option( 'gtw_settings_gift_wrapper_design_type' ) ) {
			$return = false ;
		}

		return apply_filters( 'gtw_display_gift_wrapper_designs' , $return ) ;
	}

}

if ( ! function_exists( 'gtw_get_order_gift_wrapper_button_label' ) ) {

	/**
	 *  Get the order gift wrapper button label.
	 *
	 *  @return array
	 */
	function gtw_get_order_gift_wrapper_button_label( $show_popup, $product ) {

		$label = get_option( 'gtw_settings_order_gift_wrapper_localization' ) ;

		if ( ! $show_popup ) {
			$design_price = floatval( get_option( 'gtw_settings_gift_wrapper_design_price' ) ) ;
			$label        .= '(' . gtw_price( gtw_get_price_to_display( $product , $design_price ) ) . ')' ;
		}

		return apply_filters( 'gtw_order_gift_wrapper_button_label' , $label ) ;
	}

}

if ( ! function_exists( 'gtw_get_product_gift_wrapper_label' ) ) {

	/**
	 *  Get the product gift wrapper label.
	 *
	 *  @return array
	 */
	function gtw_get_product_gift_wrapper_label( $product ) {

		$label = get_option( 'gtw_settings_product_gift_wrapper_localization' ) ;

		if ( ! gtw_display_gift_wrapper_designs() ) {
			$design_price = floatval( get_option( 'gtw_settings_gift_wrapper_design_price' ) ) ;
			$label        .= '(' . gtw_price( gtw_get_price_to_display( $product , $design_price ) ) . ')' ;
		}

		return apply_filters( 'gtw_product_gift_wrapper_label' , $label ) ;
	}

}

if ( ! function_exists( 'gtw_get_custom_field_name' ) ) {

	/**
	 *  Get the custom field name.
	 *
	 *  @return array
	 */
	function gtw_get_custom_field_name( $field ) {

		$name = gtw_get_custom_field_translate_string( 'gtw_custom_field_name_' . $field->get_id() , $field->get_name() ) ;

		return apply_filters( 'gtw_custom_field_name' , $name , $field ) ;
	}

}

if ( ! function_exists( 'gtw_get_custom_field_description' ) ) {

	/**
	 *  Get the custom field description.
	 *
	 *  @return array
	 */
	function gtw_get_custom_field_description( $field ) {

		$description = gtw_get_custom_field_translate_string( 'gtw_custom_field_description_' . $field->get_id() , $field->get_description() ) ;

		return apply_filters( 'gtw_custom_field_description' , $description , $field ) ;
	}

}

if ( ! function_exists( 'gtw_show_product_gift_wrapper_total_payable' ) ) {

	/**
	 * Show the product gift wrapper total payable.
	 * 
	 * @return string.
	 * 
	 * */
	function gtw_show_product_gift_wrapper_total_payable() {

		return apply_filters( 'gtw_show_product_gift_wrapper_total_payable' , 'yes' !== get_option( 'gtw_settings_disable_product_gift_wrapper_total_payable' , 'no' ) ) ;
	}

}
