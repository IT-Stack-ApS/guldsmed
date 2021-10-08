<?php

/**
 * Custom Post Type.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'GTW_Register_Post_Types' ) ) {

	/**
	 * GTW_Register_Post_Types Class.
	 */
	class GTW_Register_Post_Types {

		/**
		 * Rules Post Type.
		 */
		const RULE_POSTTYPE = 'gtw_rules' ;

		/**
		 * Custom Field Post Type.
		 */
		const CUSTOM_FIELD_POSTTYPE = 'gtw_custom_fields' ;

		/**
		 * GTW_Register_Post_Types Class initialization.
		 */
		public static function init() {

			add_action( 'init' , array( __CLASS__ , 'register_custom_post_types' ) ) ;
		}

		/**
		 * Register Custom Post types.
		 * 
		 * @return void
		 */
		public static function register_custom_post_types() {
			if ( ! is_blog_installed() ) {
				return ;
			}

			$custom_post_types = array(
				self::RULE_POSTTYPE         => array( 'GTW_Register_Post_Types' , 'rules_post_type_args' ) ,
				self::CUSTOM_FIELD_POSTTYPE => array( 'GTW_Register_Post_Types' , 'custom_fields_post_type_args' )
					) ;

			$custom_post_types = apply_filters( 'gtw_add_custom_post_types' , $custom_post_types ) ;

			// Return if no post type to register.
			if ( ! gtw_check_is_array( $custom_post_types ) ) {
				return ;
			}

			foreach ( $custom_post_types as $post_type => $args_function ) {

				$args = array() ;
				if ( $args_function ) {
					$args = call_user_func_array( $args_function , $args ) ;
				}

				// Register custom post type.
				register_post_type( $post_type , $args ) ;
			}
		}

		/**
		 * Prepare rules post type arguments.
		 * 
		 * @return array
		 */
		public static function rules_post_type_args() {

			return apply_filters(
					'gtw_rules_post_type_args' , array(
				'label'           => esc_html__( 'Rules' , 'gift-wrapper-for-woocommerce' ) ,
				'public'          => false ,
				'hierarchical'    => false ,
				'supports'        => false ,
				'capability_type' => 'post' ,
				'rewrite'         => false ,
					)
					) ;
		}

		/**
		 * Prepare custom fields post type arguments.
		 * 
		 * @return array
		 */
		public static function custom_fields_post_type_args() {

			return apply_filters(
					'gtw_custom_fields_post_type_args' , array(
				'label'           => esc_html__( 'Custom Fields' , 'gift-wrapper-for-woocommerce' ) ,
				'public'          => false ,
				'hierarchical'    => false ,
				'supports'        => false ,
				'capability_type' => 'post' ,
				'rewrite'         => false ,
					)
					) ;
		}

	}

	GTW_Register_Post_Types::init() ;
}
