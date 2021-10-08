<?php

/**
 * Custom Field.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'GTW_Custom_Field' ) ) {

	/**
	 * GTW_Custom_Field Class.
	 */
	class GTW_Custom_Field extends GTW_Post {

		/**
		 * Post Type.
		 * 
		 * @var string
		 */
		protected $post_type = GTW_Register_Post_Types::CUSTOM_FIELD_POSTTYPE ;

		/**
		 * Post Status.
		 * 
		 * @var string
		 */
		protected $post_status = 'gtw_active' ;

		/**
		 * Name.
		 * 
		 * @var string
		 */
		protected $name ;

		/**
		 * Custom Field Type.
		 * 
		 * @var string
		 */
		protected $gtw_field_type ;

		/**
		 * Custom Field key.
		 * 
		 * @var string
		 */
		protected $gtw_field_key ;

		/**
		 * Description.
		 * 
		 * @var string
		 */
		protected $gtw_description ;

		/**
		 * Character Count.
		 * 
		 * @var float/string
		 */
		protected $gtw_character_count ;

		/**
		 * Meta data keys.
		 */
		protected $meta_data_keys = array(
			'gtw_field_type'      => '' ,
			'gtw_field_key'       => '' ,
			'gtw_description'     => '' ,
			'gtw_character_count' => ''
				) ;

		/**
		 * Prepare extra post data.
		 */
		protected function load_extra_postdata() {
			$this->name = $this->post->post_title ;
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
		 * Set Description.
		 */
		public function set_description( $value ) {
			$this->gtw_description = $value ;
		}

		/**
		 * Set Field Type.
		 */
		public function set_field_type( $value ) {
			$this->gtw_field_type = $value ;
		}

		/**
		 * Set Field key.
		 */
		public function set_field_key( $value ) {
			$this->gtw_field_key = $value ;
		}

		/**
		 * Set Character Count.
		 */
		public function set_character_count( $value ) {
			$this->gtw_character_count = $value ;
		}

		/**
		 * Get Name.
		 */
		public function get_name() {

			return $this->name ;
		}

		/**
		 * Get Description.
		 */
		public function get_description() {

			return $this->gtw_description ;
		}

		/**
		 * Get Field Type.
		 */
		public function get_field_type() {
			return $this->gtw_field_type ;
		}

		/**
		 * Get Field key.
		 */
		public function get_field_key() {
			return $this->gtw_field_key ;
		}

		/**
		 * Get Character Count.
		 */
		public function get_character_count() {

			return $this->gtw_character_count ;
		}

	}

}

