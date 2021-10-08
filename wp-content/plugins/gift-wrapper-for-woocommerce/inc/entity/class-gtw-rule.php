<?php

/**
 * Rule.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'GTW_Rule' ) ) {

	/**
	 * GTW_Rule Class.
	 */
	class GTW_Rule extends GTW_Post {

		/**
		 * Post Type.
		 */
		protected $post_type = GTW_Register_Post_Types::RULE_POSTTYPE ;

		/**
		 * Post Status.
		 */
		protected $post_status = 'gtw_active' ;

		/**
		 * Name.
		 */
		protected $name ;

		/**
		 * Image ID.
		 */
		protected $gtw_image_id ;

		/**
		 * Price.
		 */
		protected $gtw_price ;

		/**
		 * Meta data keys.
		 */
		protected $meta_data_keys = array(
			'gtw_price'    => '' ,
			'gtw_image_id' => '' ,
				) ;

		/**
		 * Prepare extra post data.
		 */
		protected function load_extra_postdata() {
			$this->name = $this->post->post_title ;
		}

		/**
		 * Get Image.
		 */
		public function get_image( $size = 'woocommerce_thumbnail', $attr = array(), $placeholder = true ) {

			$image = '' ;
			if ( $this->get_image_id() ) {
				$image = wp_get_attachment_image( $this->get_image_id() , $size , false , $attr ) ;
			}

			if ( ! $image && $placeholder ) {
				$image = wc_placeholder_img( $size , $attr ) ;
			}

			return $image ;
		}

		/**
		 * Get Image URL.
		 */
		public function get_image_url() {

			$url = empty( $this->get_image_id() ) ? wc_placeholder_img_src() : wp_get_attachment_url( $this->get_image_id() ) ;

			return $url ;
		}

		/**
		 * Setters and Getters
		 */

		/**
		 * Set Name.
		 */
		public function set_name( $value ) {
			$this->name = $value ;
		}

		/**
		 * Set Image ID.
		 */
		public function set_image_id( $value ) {
			$this->gtw_image_id = $value ;
		}

		/**
		 * Set Price.
		 */
		public function set_price( $value ) {
			$this->gtw_price = $value ;
		}

		/**
		 * Get Name.
		 */
		public function get_name() {

			return $this->name ;
		}

		/**
		 * Get Image ID.
		 */
		public function get_image_id() {

			return $this->gtw_image_id ;
		}

		/**
		 * Get Price.
		 */
		public function get_price() {

			return $this->gtw_price ;
		}

	}

}

