<?php

/**
 * Post functions.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! function_exists( 'gtw_create_new_rule' ) ) {

	/**
	 * Create New Rule.
	 *
	 * @return integer/string
	 */
	function gtw_create_new_rule( $meta_args, $post_args = array() ) {

		$object = new GTW_RULE() ;
		$id     = $object->create( $meta_args , $post_args ) ;

		return $id ;
	}

}

if ( ! function_exists( 'gtw_get_rule' ) ) {

	/**
	 * Get Rule object.
	 *
	 * @return object
	 */
	function gtw_get_rule( $id ) {

		$object = new GTW_RULE( $id ) ;

		return $object ;
	}

}

if ( ! function_exists( 'gtw_update_rule' ) ) {

	/**
	 * Update Rule.
	 *
	 * @return object
	 */
	function gtw_update_rule( $id, $meta_args, $post_args = array() ) {

		$object = new GTW_RULE( $id ) ;
		$object->update( $meta_args , $post_args ) ;

		return $object ;
	}

}

if ( ! function_exists( 'gtw_delete_rule' ) ) {

	/**
	 * Delete Rule.
	 *
	 * @return bool
	 */
	function gtw_delete_rule( $id, $force = true ) {

		wp_delete_post( $id , $force ) ;

		return true ;
	}

}

if ( ! function_exists( 'gtw_get_rule_statuses' ) ) {

	/**
	 * Get Rule statuses.
	 *
	 * @return array
	 */
	function gtw_get_rule_statuses() {
		return apply_filters( 'gtw_rule_statuses' , array( 'gtw_active' , 'gtw_inactive' ) ) ;
	}

}

if ( ! function_exists( 'gtw_create_new_custom_field' ) ) {

	/**
	 * Create New custom field.
	 *
	 * @return integer/string
	 */
	function gtw_create_new_custom_field( $meta_args, $post_args = array() ) {

		$object = new GTW_Custom_Field() ;
		$id     = $object->create( $meta_args , $post_args ) ;

		return $id ;
	}

}

if ( ! function_exists( 'gtw_get_custom_field' ) ) {

	/**
	 * Get the custom field object.
	 *
	 * @return object
	 */
	function gtw_get_custom_field( $id ) {

		$object = new GTW_Custom_Field( $id ) ;

		return $object ;
	}

}

if ( ! function_exists( 'gtw_update_custom_field' ) ) {

	/**
	 * Update the custom field.
	 *
	 * @return object
	 */
	function gtw_update_custom_field( $id, $meta_args, $post_args = array() ) {

		$object = new GTW_Custom_Field( $id ) ;
		$object->update( $meta_args , $post_args ) ;

		return $object ;
	}

}

if ( ! function_exists( 'gtw_delete_custom_field' ) ) {

	/**
	 * Delete the custom field.
	 *
	 * @return bool
	 */
	function gtw_delete_custom_field( $id, $force = true ) {

		wp_delete_post( $id , $force ) ;

		return true ;
	}

}

if ( ! function_exists( 'gtw_get_custom_field_statuses' ) ) {

	/**
	 * Get the custom field statuses.
	 *
	 * @return array
	 */
	function gtw_get_custom_field_statuses() {
		return apply_filters( 'gtw_custom_field_statuses' , array( 'gtw_active' , 'gtw_inactive' ) ) ;
	}

}

if ( ! function_exists( 'gtw_get_active_rule_ids' ) ) {

	/**
	 * Get active rule IDs.
	 *
	 * @return array
	 */
	function gtw_get_active_rule_ids() {

		return gtw_get_rule_ids( 'gtw_active' ) ;
	}

}

if ( ! function_exists( 'gtw_get_rule_ids' ) ) {

	/**
	 * Get rule IDs.
	 *
	 * @return array
	 */
	function gtw_get_rule_ids( $post_status = 'all' ) {
		if ( 'all' == $post_status ) {
			$post_status = gtw_get_rule_statuses() ;
		}

		$args = array(
			'post_type'      => GTW_Register_Post_Types::RULE_POSTTYPE ,
			'post_status'    => $post_status ,
			'posts_per_page' => '-1' ,
			'fields'         => 'ids' ,
			'orderby'        => 'menu_order ID' ,
			'order'          => 'ASC' ,
				) ;

		return get_posts( $args ) ;
	}

}

if ( ! function_exists( 'gtw_get_active_custom_field_ids' ) ) {

	/**
	 * Get the active custom field ids.
	 *
	 * @return array
	 */
	function gtw_get_active_custom_field_ids() {

		return gtw_get_custom_field_ids( 'gtw_active' ) ;
	}

}

if ( ! function_exists( 'gtw_get_custom_field_ids' ) ) {

	/**
	 * Get the custom field ids.
	 *
	 * @return array
	 */
	function gtw_get_custom_field_ids( $post_status = 'all' ) {
		if ( 'all' == $post_status ) {
			$post_status = gtw_get_custom_field_statuses() ;
		}

		$args = array(
			'post_type'      => GTW_Register_Post_Types::CUSTOM_FIELD_POSTTYPE ,
			'post_status'    => $post_status ,
			'posts_per_page' => '-1' ,
			'fields'         => 'ids' ,
			'orderby'        => 'menu_order ID' ,
			'order'          => 'ASC' ,
				) ;

		return get_posts( $args ) ;
	}

}

if ( ! function_exists( 'gtw_format_product_rule' ) ) {

	/**
	 * Format the product rule.
	 *
	 * @return array
	 */
	function gtw_format_product_rule( $rule ) {
		$default_args = array(
			'name'     => '' ,
			'id'       => '' ,
			'price'    => '' ,
			'image_id' => ''
				) ;

		if ( is_array( $rule ) ) {
			return wp_parse_args( $rule , $default_args ) ;
		}

		$gift_wrapper = gtw_get_rule( $rule ) ;
		if ( ! $gift_wrapper->exists() ) {
			return $default_args ;
		}

		$args = array(
			'name'     => $gift_wrapper->get_name() ,
			'id'       => $gift_wrapper->get_id() ,
			'price'    => $gift_wrapper->get_price() ,
			'image_id' => $gift_wrapper->get_image_id()
				) ;

		return $args ;
	}

}

if ( ! function_exists( 'gtw_get_product_rule_by_id' ) ) {

	/**
	 * Get the product rule by ID.
	 *
	 * @return array
	 */
	function gtw_get_product_rule_by_id( $product_id, $rule_id ) {
		$design_type = get_post_meta( $product_id , '_gtw_design_type' , true ) ;

		if ( '2' == $design_type ) {
			$rules   = array_filter( ( array ) get_post_meta( $product_id , '_gtw_designs' , true ) ) ;
			$rule_id = ( array_key_exists( $rule_id , $rules ) ) ? $rules[ $rule_id ] : array() ;
		}

		return gtw_format_product_rule( $rule_id ) ;
	}

}
