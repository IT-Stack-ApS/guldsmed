<?php

/**
 * Custom Fields Tab
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( class_exists( 'GTW_Custom_Fields_Tab' ) ) {
	return new GTW_Custom_Fields_Tab() ;
}

/**
 * GTW_Custom_Fields_Tab.
 */
class GTW_Custom_Fields_Tab extends GTW_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id           = 'custom_fields' ;
		$this->label        = esc_html__( 'Custom Fields' , 'gift-wrapper-for-woocommerce' ) ;
		$this->show_buttons = false ;

		parent::__construct() ;
	}

	/**
	 * Render the custom fields.
	 * 
	 * @return void
	 */
	public function output_extra_fields() {
		global $current_action ;

		switch ( $current_action ) {
			case 'edit':
				$this->render_edit_custom_field_page() ;
				break ;
			default:
				$this->render_custom_fields_table() ;
				break ;
		}
	}

	/**
	 * Output the custom field edit page.
	 */
	public function render_edit_custom_field_page() {

		global $post_custom_field ;

		if ( ! isset( $_GET[ 'id' ] ) ) { // @codingStandardsIgnoreLine.
			return ;
		}

		$field_id = absint( $_GET[ 'id' ] ) ; // @codingStandardsIgnoreLine.
		$field    = gtw_get_custom_field( $field_id ) ;

		if ( ! $field->exists() ) {
			return ;
		}

		$default_field_data = array(
			'gtw_custom_field_status'      => $field->get_status() ,
			'gtw_custom_field_name'        => $field->get_name() ,
			'gtw_custom_field_description' => $field->get_description() ,
			'gtw_character_count'          => $field->get_character_count() ,
			'gtw_field_type'               => $field->get_field_type()
				) ;

		// may be sanitize post data
		$field_post_data = isset( $post_custom_field ) ? $post_custom_field : array() ;

		$custom_field_data = wp_parse_args( $field_post_data , $default_field_data ) ;

		// Html for edit custom field page.
		include_once( GTW_PLUGIN_PATH . '/inc/admin/menu/views/edit-custom-field.php' ) ;
	}

	/**
	 * Output the custom fields WP List Table.
	 */
	public function render_custom_fields_table() {
		if ( ! class_exists( 'GTW_Custom_Fields_List_Table' ) ) {
			require_once( GTW_PLUGIN_PATH . '/inc/admin/menu/wp-list-table/class-gtw-custom-fields-list-table.php' ) ;
		}

		$post_table = new GTW_Custom_Fields_List_Table() ;
		$post_table->prepare_items() ;

		echo '<div class="gtw_table_wrap">' ;
		echo '<h1 class="wp-heading-inline">' . esc_html__( 'Custom Fields' , 'gift-wrapper-for-woocommerce' ) . '</h1>' ;
		echo '<hr class="wp-header-end">' ;

		if ( isset( $_REQUEST[ 's' ] ) && strlen( wc_clean( wp_unslash( $_REQUEST[ 's' ] ) ) ) ) { // @codingStandardsIgnoreLine.
			/* translators: %s: search keywords */
			echo wp_kses_post( sprintf( '<span class="subtitle">' . esc_html__( 'Search results for &#8220;%s&#8221;' , 'gift-wrapper-for-woocommerce' ) . '</span>' , wc_clean( wp_unslash( $_REQUEST[ 's' ] ) ) ) ) ;
		}

		$post_table->views() ;
		$post_table->display() ;
		echo '</div>' ;
	}

	/**
	 * Save settings.
	 */
	public function save() {
		global $current_action , $post_custom_field ;

		// Show success message.
		if ( isset( $_GET[ 'message' ] ) && 'success' == sanitize_title( $_GET[ 'message' ] ) ) {
			GTW_Settings::add_message( esc_html__( 'New Field has been created successfuly' , 'gift-wrapper-for-woocommerce' ) ) ;
		}

		$post_custom_field = ! empty( $_REQUEST[ 'gtw_custom_field' ] ) ? wc_clean( wp_unslash( ( $_REQUEST[ 'gtw_custom_field' ] ) ) ) : $post_custom_field ;

		if ( ! isset( $_REQUEST[ 'gtw_save' ] ) ) {
			return ;
		}

		switch ( $current_action ) {
			case 'edit':
				$this->update_custom_field() ;
				break ;
		}
	}

	/**
	 * Update the custom field.
	 */
	public function update_custom_field() {

		check_admin_referer( 'gtw_update_custom_field' , '_gtw_nonce' ) ;

		try {
			$field_post_data = ! empty( $_POST[ 'gtw_custom_field' ] ) ? wc_clean( wp_unslash( ( $_POST[ 'gtw_custom_field' ] ) ) ) : array() ;
			$field_id        = ! empty( $_POST[ 'gtw_custom_field_id' ] ) ? absint( $_POST[ 'gtw_custom_field_id' ] ) : 0 ; // @codingStandardsIgnoreLine.
			// Validate custom field name.
			if ( '' == $field_post_data[ 'gtw_custom_field_name' ] ) {
				throw new Exception( esc_html__( 'Field Name is mandatory' , 'gift-wrapper-for-woocommerce' ) ) ;
			}

			$post_args = array(
				'post_status' => $field_post_data[ 'gtw_custom_field_status' ] ,
				'post_title'  => $field_post_data[ 'gtw_custom_field_name' ] ,
					) ;

			// Prepare the post meta data.
			$field_post_data[ 'gtw_description' ]     = isset( $field_post_data[ 'gtw_custom_field_description' ] ) ? $field_post_data[ 'gtw_custom_field_description' ] : '' ;
			$field_post_data[ 'gtw_character_count' ] = isset( $field_post_data[ 'gtw_character_count' ] ) ? $field_post_data[ 'gtw_character_count' ] : '' ;

			// Update Custom field.
			gtw_update_custom_field( $field_id , $field_post_data , $post_args ) ;

			unset( $_POST[ 'gtw_custom_field' ] ) ;

			GTW_Settings::add_message( esc_html__( 'Field has been updated successfully' , 'gift-wrapper-for-woocommerce' ) ) ;
		} catch ( Exception $ex ) {
			GTW_Settings::add_error( $ex->getMessage() ) ;
		}
	}

}

return new GTW_Custom_Fields_Tab() ;
