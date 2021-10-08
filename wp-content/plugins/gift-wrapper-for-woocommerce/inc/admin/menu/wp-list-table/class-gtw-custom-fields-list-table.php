<?php

/**
 * Custom Fields List Table.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' ) ;
}

if ( ! class_exists( 'GTW_Custom_Fields_List_Table' ) ) {

	/**
	 * GTW_Custom_Fields_List_Table Class.
	 * */
	class GTW_Custom_Fields_List_Table extends WP_List_Table {

		/**
		 * Total Count of Table.
		 * 
		 * @var int
		 * */
		private $total_items ;

		/**
		 * Per page count.
		 * 
		 * @var int
		 * */
		private $perpage ;

		/**
		 * Database.
		 * 
		 * @var object
		 * */
		private $database ;

		/**
		 * Offset.
		 * 
		 * @var int
		 * */
		private $offset ;

		/**
		 * Order BY.
		 * 
		 * @var string
		 * */
		private $orderby = 'ORDER BY menu_order ASC,ID ASC' ;

		/**
		 * Post type.
		 * 
		 * @var string
		 * */
		private $post_type = GTW_Register_Post_Types::CUSTOM_FIELD_POSTTYPE ;

		/**
		 * Base URL.
		 * 
		 * @var string
		 * */
		private $base_url ;

		/**
		 * Current URL.
		 * 
		 * @var string
		 * */
		private $current_url ;

		/**
		 * Prepare the table Data to display table based on pagination.
		 * */
		public function prepare_items() {
			global $wpdb ;
			$this->database = $wpdb ;

			$this->base_url = gtw_get_field_page_url() ;

			$this->prepare_current_url() ;
			$this->process_bulk_action() ;
			$this->get_perpage_count() ;
			$this->get_current_pagenum() ;
			$this->get_current_page_items() ;
			$this->prepare_pagination_args() ;
			$this->prepare_column_headers() ;
		}

		/**
		 * Get per page count
		 * */
		private function get_perpage_count() {

			$this->perpage = 10 ;
		}

		/**
		 * Prepare pagination
		 * */
		private function prepare_pagination_args() {

			$this->set_pagination_args(
					array(
						'total_items' => $this->total_items ,
						'per_page'    => $this->perpage ,
					)
			) ;
		}

		/**
		 * Get current page number
		 * */
		private function get_current_pagenum() {

			$this->offset = 10 * ( $this->get_pagenum() - 1 ) ;
		}

		/**
		 * Prepare header columns
		 * */
		private function prepare_column_headers() {
			$columns               = $this->get_columns() ;
			$hidden                = $this->get_hidden_columns() ;
			$sortable              = $this->get_sortable_columns() ;
			$this->_column_headers = array( $columns , $hidden , $sortable ) ;
		}

		/**
		 * Initialize the columns
		 * */
		public function get_columns() {
			$columns = array(
				'field_name'     => esc_html__( 'Field Name' , 'gift-wrapper-for-woocommerce' ) ,
				'gtw_field_type' => esc_html__( 'Type' , 'gift-wrapper-for-woocommerce' ) ,
				'status'         => esc_html__( 'Status' , 'gift-wrapper-for-woocommerce' ) ,
				'actions'        => esc_html__( 'Actions' , 'gift-wrapper-for-woocommerce' )
					) ;

			if ( ! isset( $_REQUEST[ 'post_status' ] ) && ! isset( $_REQUEST[ 's' ] ) ) {
				$columns[ 'sort' ] = '<span class="dashicons dashicons-menu-alt2" title="' . esc_html__( 'Sort' , 'gift-wrapper-for-woocommerce' ) . '"></span>' ;
			}

			return $columns ;
		}

		/**
		 * Initialize the hidden columns.
		 * */
		public function get_hidden_columns() {
			return array() ;
		}

		/**
		 * Initialize the sortable columns.
		 * */
		public function get_sortable_columns() {
			return array() ;
		}

		/**
		 * Get current url.
		 * */
		private function prepare_current_url() {

			$pagenum         = $this->get_pagenum() ;
			$args[ 'paged' ] = $pagenum ;
			$url             = add_query_arg( $args , $this->base_url ) ;

			$this->current_url = $url ;
		}

		/**
		 * Initialize the bulk actions
		 * */
		protected function get_bulk_actions() {
			$action = array() ;

			return apply_filters( $this->plugin_slug . '_custom_field_bulk_actions' , $action ) ;
		}

		/**
		 * Display the list of views available on this table.
		 * */
		public function get_views() {
			$args        = array() ;
			$status_link = array() ;

			$status_link_array = array(
				'all'          => esc_html__( 'All' , 'gift-wrapper-for-woocommerce' ) ,
				'gtw_active'   => esc_html__( 'Enabled' , 'gift-wrapper-for-woocommerce' ) ,
				'gtw_inactive' => esc_html__( 'Disabled' , 'gift-wrapper-for-woocommerce' ) ,
					) ;

			foreach ( $status_link_array as $status_name => $status_label ) {
				$status_count = $this->get_total_item_for_status( $status_name ) ;

				if ( ! $status_count ) {
					continue ;
				}

				$args[ 'status' ] = $status_name ;

				$label = $status_label . ' (' . $status_count . ')' ;

				$class = array( strtolower( $status_name ) ) ;
				if ( isset( $_GET[ 'status' ] ) && ( sanitize_title( $_GET[ 'status' ] ) == $status_name ) ) { // @codingStandardsIgnoreLine.
					$class[] = 'current' ;
				}

				if ( ! isset( $_GET[ 'status' ] ) && 'all' == $status_name ) { // @codingStandardsIgnoreLine.
					$class[] = 'current' ;
				}

				$status_link[ $status_name ] = $this->get_edit_link( $args , $label , implode( ' ' , $class ) ) ;
			}

			return $status_link ;
		}

		/**
		 * Edit link for status.
		 * */
		private function get_edit_link( $args, $label, $class = '' ) {
			$url        = add_query_arg( $args , $this->base_url ) ;
			$class_html = '' ;
			if ( ! empty( $class ) ) {
				$class_html = sprintf(
						' class="%s"' , esc_attr( $class )
						) ;
			}

			return sprintf(
					'<a href="%s"%s>%s</a>' , esc_url( $url ) , $class_html , $label
					) ;
		}

		/**
		 * Prepare cb column data.
		 * */
		protected function column_cb( $item ) {
			return sprintf(
					'<input type="checkbox" name="id[]" value="%s" />' , $item->get_id()
					) ;
		}

		/**
		 * Bulk action functionality.
		 * */
		public function process_bulk_action() {
			$ids = isset( $_REQUEST[ 'id' ] ) ? wc_clean( wp_unslash( ( $_REQUEST[ 'id' ] ) ) ) : array() ; // @codingStandardsIgnoreLine.
			$ids = ! is_array( $ids ) ? explode( ',' , $ids ) : $ids ;

			if ( ! gtw_check_is_array( $ids ) ) {
				return ;
			}

			if ( ! current_user_can( 'edit_posts' ) ) {
				wp_die( '<p class="error">' . esc_html__( 'Sorry, you are not allowed to edit this item.' , 'gift-wrapper-for-woocommerce' ) . '</p>' ) ;
			}

			$action = $this->current_action() ;

			foreach ( $ids as $id ) {
				if ( 'active' === $action ) {
					gtw_update_custom_field( $id , array() , array( 'post_status' => 'gtw_active' ) ) ;
				} elseif ( 'inactive' === $action ) {
					gtw_update_custom_field( $id , array() , array( 'post_status' => 'gtw_inactive' ) ) ;
				}
			}

			wp_safe_redirect( $this->current_url ) ;
			exit() ;
		}

		/**
		 * Prepare each column data
		 * */
		protected function column_default( $item, $column_name ) {

			switch ( $column_name ) {

				case 'field_name':
					return '<a href="' . esc_url(
									add_query_arg(
											array(
								'action' => 'edit' ,
								'id'     => $item->get_id() ,
											) , $this->base_url
									)
							) . '">' . esc_html( $item->get_name() ) . '</a>' ;
					break ;

				case 'status':
					return gtw_get_status_html( $item->get_status() ) ;

					break ;

				case 'gtw_field_type':
					return gtw_get_field_type_name( $item->get_field_type() ) ;

					break ;

				case 'actions':
					$actions       = array() ;
					$status_action = ( $item->get_status() == 'gtw_inactive' ) ? 'active' : 'inactive' ;

					$actions[ 'edit' ]         = gtw_get_action_link( 'edit' , $item->get_id() , $this->current_url , true ) ;
					$actions[ $status_action ] = gtw_get_action_link( $status_action , $item->get_id() , $this->current_url ) ;

					end( $actions ) ;

					$last_key = key( $actions ) ;
					$views    = '' ;
					foreach ( $actions as $key => $action ) {
						$views .= $action ;

						if ( $last_key == $key ) {
							break ;
						}

						$views .= ' | ' ;
					}

					return $views ;

					break ;

				case 'sort':
					return '<div class = "gtw-post-sort-handle">'
							. '<span class="gtw-custom-field-sortable dashicons dashicons-menu-alt2" data-id = "' . $item->get_id() . '" title="' . esc_html__( 'Sort' , 'gift-wrapper-for-woocommerce' ) . '"></span>'
							. '</div>' ;
					break ;
			}
		}

		/**
		 * Initialize the columns
		 * */
		private function get_current_page_items() {

			$status = ( isset( $_GET[ 'status' ] ) && ( sanitize_title( $_GET[ 'status' ] ) != 'all' ) ) ? ' IN("' . sanitize_title( $_GET[ 'status' ] ) . '")' : ' NOT IN("trash")' ; // @codingStandardsIgnoreLine.

			if ( ! empty( $_REQUEST[ 's' ] ) || ! empty( $_REQUEST[ 'orderby' ] ) ) { // @codingStandardsIgnoreLine.
				$where = ' INNER JOIN ' . $this->database->postmeta . " pm ON ( pm.post_id = p.ID ) where post_type='" . $this->post_type . "' and post_status " . $status ;
			} else {
				$where = " where post_type='" . $this->post_type . "' and post_status" . $status ;
			}

			$where   = apply_filters( $this->table_slug . '_query_where' , $where ) ;
			$limit   = apply_filters( $this->table_slug . '_query_limit' , $this->perpage ) ;
			$offset  = apply_filters( $this->table_slug . '_query_offset' , $this->offset ) ;
			$orderby = apply_filters( $this->table_slug . '_query_orderby' , $this->orderby ) ;

			$count_items       = $this->database->get_results( 'SELECT DISTINCT ID FROM ' . $this->database->posts . " AS p $where $orderby" ) ;
			$this->total_items = count( $count_items ) ;

			$prepare_query = $this->database->prepare( 'SELECT DISTINCT ID FROM ' . $this->database->posts . " AS p $where $orderby LIMIT %d,%d" , $offset , $limit ) ;

			$items = $this->database->get_results( $prepare_query , ARRAY_A ) ;

			$this->prepare_item_object( $items ) ;
		}

		/**
		 * Prepare item Object
		 * */
		private function prepare_item_object( $items ) {
			$prepare_items = array() ;
			if ( gtw_check_is_array( $items ) ) {
				foreach ( $items as $item ) {
					$prepare_items[] = gtw_get_custom_field( $item[ 'ID' ] ) ;
				}
			}

			$this->items = $prepare_items ;
		}

		/**
		 * Get total item from status
		 * */
		private function get_total_item_for_status( $status = '' ) {

			$status = ( 'all' == $status ) ? "NOT IN('trash')" : "IN('" . $status . "')" ;

			$prepare_query = $this->database->prepare( 'SELECT ID FROM ' . $this->database->posts . " WHERE post_type=%s and post_status $status" , $this->post_type ) ;

			$data = $this->database->get_results( $prepare_query , ARRAY_A ) ;

			return count( $data ) ;
		}

	}

}
