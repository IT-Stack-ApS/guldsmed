<?php

/**
 * Handles the product Type.
 * */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'GTW_Product_Settings' ) ) {

	/**
	 * Class.
	 * */
	class GTW_Product_Settings {

		/**
		 * Class initialization.
		 * */
		public static function init() {

			// Add the product tyoe tabs for gift wrapper.
			add_filter( 'woocommerce_product_data_tabs' , array( __CLASS__ , 'custom_product_type_tabs' ) ) ;
			// Add the gift wrapper product data panels.
			add_action( 'woocommerce_product_data_panels' , array( __CLASS__ , 'custom_product_data_panels' ) ) ;
			// Save the gift wrapper product type data.
			add_action( 'woocommerce_process_product_meta' , array( __CLASS__ , 'save_custom_product_data_options' ) , 10 , 1 ) ;
		}

		/**
		 * Add the tabs for gift wrapper.
		 * 
		 * @return array
		 * */
		public static function custom_product_type_tabs( $tabs ) {
			//If resource is not array , declare empty array
			if ( ! gtw_check_is_array( $tabs ) ) {
				$tabs = array() ;
			}

			$tabs[ 'gtw_gift_wrapper_tab' ] = array(
				'label'  => esc_html__( 'Gift Wrapper' , 'gift-wrapper-for-woocommerce' ) ,
				'target' => 'gtw_gift_wrapper_tab' ,
					) ;

			return apply_filters( 'gtw_product_type_tabs' , $tabs ) ;
		}

		/**
		 * Add the gift wrapper product data panels.
		 * 
		 * @return void
		 * */
		public static function custom_product_data_panels() {
			global $post , $thepostid , $product_object ;

			$tabs = array(
				'gift-wrapper' ,
					) ;

			foreach ( $tabs as $tab ) {
				include 'menu/views/product/html-product-data-' . $tab . '.php' ;
			}
		}

		/**
		 * Save the gift wrapper product data options.
		 * 
		 * @return void
		 * */
		public static function save_custom_product_data_options( $post_id ) {
			$product = wc_get_product( $post_id ) ;
			if ( ! is_object( $product ) ) {
				return ;
			}

			$design_type = isset( $_REQUEST[ '_gtw_design_type' ] ) ? wc_clean( wp_unslash( $_REQUEST[ '_gtw_design_type' ] ) ) : '' ;

			$meta_data = array(
				'_gtw_design_type' => $design_type ,
				'_gtw_designs'     => self::prepare_designs()
					) ;

			foreach ( $meta_data as $key => $value ) {
				update_post_meta( $post_id , $key , $value ) ;
			}
		}

		/**
		 * Prepare the designs.
		 * 
		 * @return array
		 * */
		public static function prepare_designs() {
			$designs      = array() ;
			$post_designs = isset( $_REQUEST[ '_gtw_designs' ] ) ? wc_clean( wp_unslash( $_REQUEST[ '_gtw_designs' ] ) ) : array() ;
			if ( ! gtw_check_is_array( $post_designs ) ) {
				return $post_designs ;
			}

			foreach ( $post_designs as $key => $value ) {
				$value[ 'id' ]   = $key ;
				$designs[ $key ] = $value ;
			}

			return $designs ;
		}

	}

	GTW_Product_Settings::init() ;
}
	
