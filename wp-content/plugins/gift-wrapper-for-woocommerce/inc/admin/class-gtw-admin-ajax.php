<?php

/**
 * Admin Ajax.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'GTW_Admin_Ajax' ) ) {

	/**
	 * Class.
	 */
	class GTW_Admin_Ajax {

		/**
		 *  Class initialization.
		 */
		public static function init() {

			$actions = array(
				'json_search_products_and_variations'    => false ,
				'json_search_products'                   => false ,
				'json_search_customers'                  => false ,
				'add_new_rule'                           => false ,
				'add_product_new_rule'                   => false ,
				'remove_rule'                            => false ,
				'drag_rules'                             => false ,
				'drag_fields'                            => false ,
				'create_order_gift_wrapper_product'      => false ,
				'select_gift_wrapper'                    => true ,
				'select_popup_product_gift_wrapper'      => true ,
				'select_popup_order_gift_wrapper'        => true ,
				'remove_order_gift_wrapper'              => true ,
				'popup_product_gift_wrapper_pagination'  => true ,
				'popup_order_gift_wrapper_pagination'    => true ,
				'select_popup_product_gift_wrapper_item' => true ,
				'select_popup_order_gift_wrapper_item'   => true ,
				'add_order_gift_wrapper_item'            => true ,
					) ;

			foreach ( $actions as $action => $nopriv ) {
				add_action( 'wp_ajax_gtw_' . $action , array( __CLASS__ , $action ) ) ;

				if ( $nopriv ) {
					add_action( 'wp_ajax_nopriv_gtw_' . $action , array( __CLASS__ , $action ) ) ;
				}
			}
		}

		/**
		 * Search for products.
		 * 
		 * @return void
		 */
		public static function json_search_products( $term = '', $include_variations = false ) {
			check_ajax_referer( 'search-products' , 'gtw_security' ) ;

			try {

				if ( empty( $term ) && isset( $_GET[ 'term' ] ) ) {
					$term = isset( $_GET[ 'term' ] ) ? wc_clean( wp_unslash( $_GET[ 'term' ] ) ) : '' ;
				}

				if ( empty( $term ) ) {
					throw new exception( esc_html__( 'No Products found' , 'gift-wrapper-for-woocommerce' ) ) ;
				}

				if ( ! empty( $_GET[ 'limit' ] ) ) {
					$limit = absint( $_GET[ 'limit' ] ) ;
				} else {
					$limit = absint( apply_filters( 'woocommerce_json_search_limit' , 30 ) ) ;
				}

				$data_store = WC_Data_Store::load( 'product' ) ;
				$ids        = $data_store->search_products( $term , '' , ( bool ) $include_variations , false , $limit ) ;

				$product_objects = array_filter( array_map( 'wc_get_product' , $ids ) , 'wc_products_array_filter_readable' ) ;
				$products        = array() ;

				$exclude_global_variable = isset( $_GET[ 'exclude_global_variable' ] ) ? wc_clean( wp_unslash( $_GET[ 'exclude_global_variable' ] ) ) : 'no' ; // @codingStandardsIgnoreLine.
				foreach ( $product_objects as $product_object ) {
					if ( 'yes' == $exclude_global_variable && $product_object->is_type( 'variable' ) ) {
						continue ;
					}

					$products[ $product_object->get_id() ] = rawurldecode( $product_object->get_formatted_name() ) ;
				}

				wp_send_json( apply_filters( 'woocommerce_json_search_found_products' , $products ) ) ;
			} catch ( Exception $ex ) {
				wp_die() ;
			}
		}

		/**
		 * Search for product variations.
		 * 
		 * @return void
		 */
		public static function json_search_products_and_variations( $term = '', $include_variations = false ) {
			self::json_search_products( '' , true ) ;
		}

		/**
		 * Customers search.
		 * 
		 * @return void
		 */
		public static function json_search_customers() {
			check_ajax_referer( 'gtw-search-nonce' , 'gtw_security' ) ;

			try {
				$term = isset( $_GET[ 'term' ] ) ? wc_clean( wp_unslash( $_GET[ 'term' ] ) ) : '' ; // @codingStandardsIgnoreLine.

				if ( empty( $term ) ) {
					throw new exception( esc_html__( 'No Customer found' , 'gift-wrapper-for-woocommerce' ) ) ;
				}

				$exclude = isset( $_GET[ 'exclude' ] ) ? wc_clean( wp_unslash( $_GET[ 'exclude' ] ) ) : '' ; // @codingStandardsIgnoreLine.
				$exclude = ! empty( $exclude ) ? array_map( 'intval' , explode( ',' , $exclude ) ) : array() ;

				$found_customers = array() ;
				$customers_query = new WP_User_Query(
						array(
					'fields'         => 'all' ,
					'orderby'        => 'display_name' ,
					'search'         => '*' . $term . '*' ,
					'search_columns' => array( 'ID' , 'user_login' , 'user_email' , 'user_nicename' ) ,
						)
						) ;
				$customers       = $customers_query->get_results() ;

				if ( gtw_check_is_array( $customers ) ) {
					foreach ( $customers as $customer ) {
						if ( ! in_array( $customer->ID , $exclude ) ) {
							$found_customers[ $customer->ID ] = $customer->display_name . ' (#' . $customer->ID . ' &ndash; ' . sanitize_email( $customer->user_email ) . ')' ;
						}
					}
				}

				wp_send_json( $found_customers ) ;
			} catch ( Exception $ex ) {
				wp_die() ;
			}
		}

		/**
		 * Order Gift Wrapper product creation.
		 * 
		 * @return void
		 * */
		public static function create_order_gift_wrapper_product() {
			check_ajax_referer( 'gtw-product-nonce' , 'gtw_security' ) ;

			try {
				if ( ! isset( $_POST ) ) {
					throw new exception( esc_html__( 'Invalid Request' , 'gift-wrapper-for-woocommerce' ) ) ;
				}

				// Return if the current user does not have permission.
				if ( ! current_user_can( 'edit_posts' ) ) {
					throw new exception( esc_html__( "You don't have permission to do this action" , 'gift-wrapper-for-woocommerce' ) ) ;
				}

				$product_name = isset( $_POST[ 'product_name' ] ) ? wc_clean( wp_unslash( $_POST[ 'product_name' ] ) ) : '' ;

				$product_id = gtw_create_new_product( $product_name ) ;

				update_option( 'gtw_settings_order_gift_wrapper_product_type' , '2' ) ;
				update_option( 'gtw_settings_order_gift_wrapper_product' , array( $product_id ) ) ;
				update_post_meta( $product_id , 'gtw_order_gift_wrapper_product' , 'yes' ) ;

				$product_name = $product_name . ' (#' . $product_id . ')' ;

				wp_send_json_success( array( 'product_id' => absint( $product_id ) , 'product_name' => wp_kses_post( $product_name ) ) ) ;
			} catch ( Exception $ex ) {
				wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
			}
		}

		/**
		 * Add a product new rule.
		 * 
		 * @return void
		 */
		public static function add_product_new_rule() {
			check_ajax_referer( 'gtw-rules-nonce' , 'gtw_security' ) ;

			try {
				// Return if the current user does not have permission.
				if ( ! current_user_can( 'edit_posts' ) ) {
					throw new exception( esc_html__( "You don't have permission to do this action" , 'gift-wrapper-for-woocommerce' ) ) ;
				}

				ob_start() ;
				$rule_id  = uniqid() ;
				include_once (GTW_PLUGIN_PATH . '/inc/admin/menu/views/product/html-product-data-gift-wrapper-design.php') ;
				$contents = ob_get_contents() ;
				ob_end_clean() ;

				wp_send_json_success( array( 'html' => $contents ) ) ;
			} catch ( Exception $ex ) {
				wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
			}
		}

		/**
		 * Add new rule.
		 * 
		 * @return void
		 */
		public static function add_new_rule() {
			check_ajax_referer( 'gtw-rules-nonce' , 'gtw_security' ) ;

			try {
				$key = isset( $_REQUEST[ 'key' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'key' ] ) ) : '' ; // @codingStandardsIgnoreLine.

				if ( empty( $key ) ) {
					throw new exception( esc_html__( 'Invalid Key' , 'gift-wrapper-for-woocommerce' ) ) ;
				}

				// Return if the current user does not have permission.
				if ( ! current_user_can( 'edit_posts' ) ) {
					throw new exception( esc_html__( "You don't have permission to do this action" , 'gift-wrapper-for-woocommerce' ) ) ;
				}

				ob_start() ;
				include_once (GTW_PLUGIN_PATH . '/inc/admin/menu/views/html-new-rule.php') ;
				$contents = ob_get_contents() ;
				ob_end_clean() ;

				wp_send_json_success( array( 'html' => $contents ) ) ;
			} catch ( Exception $ex ) {
				wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
			}
		}

		/**
		 * Remove rule.
		 * 
		 * @retrun void
		 */
		public static function remove_rule() {
			check_ajax_referer( 'gtw-rules-nonce' , 'gtw_security' ) ;

			try {
				$rule_id = isset( $_REQUEST[ 'rule_id' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'rule_id' ] ) ) : '' ; // @codingStandardsIgnoreLine.

				if ( empty( $rule_id ) ) {
					throw new exception( esc_html__( 'Invalid Rule ID' , 'gift-wrapper-for-woocommerce' ) ) ;
				}

				// Return if the current user does not have permission.
				if ( ! current_user_can( 'edit_posts' ) ) {
					throw new exception( esc_html__( "You don't have permission to do this action" , 'gift-wrapper-for-woocommerce' ) ) ;
				}

				$rule = gtw_get_rule( $rule_id ) ;
				if ( ! $rule->exists() ) {
					throw new exception( esc_html__( 'Invalid Rule ID' , 'gift-wrapper-for-woocommerce' ) ) ;
				}

				// Delete the rule.
				gtw_delete_rule( $rule_id ) ;

				wp_send_json_success() ;
			} catch ( Exception $ex ) {
				wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
			}
		}

		/**
		 * Drag Rules.
		 * 
		 * @return void
		 */
		public static function drag_rules() {
			check_ajax_referer( 'gtw-rules-nonce' , 'gtw_security' ) ;

			try {
				if ( ! isset( $_POST ) || ! isset( $_POST[ 'sort_order' ] ) ) { // @codingStandardsIgnoreLine.
					throw new exception( esc_html__( 'Invalid Request' , 'gift-wrapper-for-woocommerce' ) ) ;
				}

				// Return if the current user does not have permission.
				if ( ! current_user_can( 'edit_posts' ) ) {
					throw new exception( esc_html__( "You don't have permission to do this action" , 'gift-wrapper-for-woocommerce' ) ) ;
				}

				$sort_ids = array() ;
				// Sanitize post values.
				$sort_ids = ! empty( $_POST[ 'sort_order' ] ) ? wc_clean( wp_unslash( ( $_POST[ 'sort_order' ] ) ) ) : array() ; // @codingStandardsIgnoreLine.
				// Update the rules based on sort.
				foreach ( $sort_ids as $menu_order => $post_id ) {
					if ( 'new' == $post_id ) {
						continue ;
					}

					wp_update_post(
							array(
								'ID'         => $post_id ,
								'menu_order' => $menu_order + 1 ,
							)
					) ;
				}

				wp_send_json_success() ;
			} catch ( Exception $ex ) {
				wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
			}
		}

		/**
		 * Drag the fields.
		 * 
		 * @return void
		 */
		public static function drag_fields() {
			check_ajax_referer( 'gtw-rules-nonce' , 'gtw_security' ) ;

			try {
				if ( ! isset( $_POST ) || ! isset( $_POST[ 'sort_order' ] ) ) { // @codingStandardsIgnoreLine.
					throw new exception( esc_html__( 'Invalid Request' , 'gift-wrapper-for-woocommerce' ) ) ;
				}

				// Return if the current user does not have permission.
				if ( ! current_user_can( 'edit_posts' ) ) {
					throw new exception( esc_html__( "You don't have permission to do this action" , 'gift-wrapper-for-woocommerce' ) ) ;
				}

				$sort_ids = array() ;
				// Sanitize post values.
				$sort_ids = ! empty( $_POST[ 'sort_order' ] ) ? wc_clean( wp_unslash( $_POST[ 'sort_order' ] ) ) : array() ; // @codingStandardsIgnoreLine.
				// Update the fields based on sort.
				foreach ( $sort_ids as $menu_order => $post_id ) {

					wp_update_post(
							array(
								'ID'         => $post_id ,
								'menu_order' => $menu_order + 1 ,
							)
					) ;
				}

				wp_send_json_success() ;
			} catch ( Exception $ex ) {
				wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
			}
		}

		/**
		 * Select Gift Wrapper.
		 * 
		 * @return void
		 */
		public static function select_gift_wrapper() {
			check_ajax_referer( 'gtw-gift-wrapper' , 'gtw_security' ) ;

			try {
				$rule_id    = isset( $_REQUEST[ 'rule_id' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'rule_id' ] ) ) : '' ; // @codingStandardsIgnoreLine.
				$product_id = isset( $_REQUEST[ 'product_id' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'product_id' ] ) ) : '' ; // @codingStandardsIgnoreLine.

				if ( empty( $rule_id ) || empty( $product_id ) ) {
					throw new exception( esc_html__( 'Invalid Rule ID' , 'gift-wrapper-for-woocommerce' ) ) ;
				}

				$rule = gtw_get_product_rule_by_id( $product_id , $rule_id ) ;
				if ( ! $rule[ 'id' ] ) {
					throw new exception( esc_html__( 'Invalid Rule ID' , 'gift-wrapper-for-woocommerce' ) ) ;
				}

				$product = wc_get_product( $product_id ) ;
				if ( ! $product->exists() ) {
					throw new exception( esc_html__( 'Invalid Product ID' , 'gift-wrapper-for-woocommerce' ) ) ;
				}

				$rule_price    = gtw_convert_price( $rule[ 'price' ] ) ;
				$total_payable = gtw_get_total_payable_price( $product , $rule_price ) ;

				$response = array(
					'name'  => $rule[ 'name' ] ,
					'price' => '(' . gtw_price( gtw_get_price_to_display( $product , $rule_price ) ) . ')' ,
					'total' => gtw_price( $total_payable )
						) ;

				wp_send_json_success( $response ) ;
			} catch ( Exception $ex ) {
				wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
			}
		}

		/**
		 * Select popup product gift wrapper.
		 * 
		 * @return void
		 */
		public static function select_popup_product_gift_wrapper() {
			check_ajax_referer( 'gtw-gift-wrapper' , 'gtw_security' ) ;

			try {
				$rule_id    = isset( $_REQUEST[ 'rule_id' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'rule_id' ] ) ) : '' ; // @codingStandardsIgnoreLine.
				$product_id = isset( $_REQUEST[ 'product_id' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'product_id' ] ) ) : '' ; // @codingStandardsIgnoreLine.

				if ( empty( $rule_id ) ) {
					throw new exception( esc_html__( 'Invalid Rule ID' , 'gift-wrapper-for-woocommerce' ) ) ;
				}

				if ( empty( $product_id ) ) {
					throw new exception( esc_html__( 'Invalid Product ID' , 'gift-wrapper-for-woocommerce' ) ) ;
				}

				$rule = gtw_get_product_rule_by_id( $product_id , $rule_id ) ;
				if ( ! $rule[ 'id' ] ) {
					throw new exception( esc_html__( 'Invalid Rule ID' , 'gift-wrapper-for-woocommerce' ) ) ;
				}

				$product = wc_get_product( $product_id ) ;

				$response = array(
					'name'  => $rule[ 'name' ] ,
					'price' => '(' . gtw_price( gtw_get_price_to_display( $product , gtw_convert_price( $rule[ 'price' ] ) ) ) . ')' ,
						) ;

				wp_send_json_success( $response ) ;
			} catch ( Exception $ex ) {
				wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
			}
		}

		/**
		 * Select popup order gift wrapper.
		 * 
		 * @return void
		 */
		public static function select_popup_order_gift_wrapper() {
			check_ajax_referer( 'gtw-gift-wrapper' , 'gtw_security' ) ;

			try {
				$rule_id = isset( $_REQUEST[ 'rule_id' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'rule_id' ] ) ) : '' ; // @codingStandardsIgnoreLine.

				if ( empty( $rule_id ) ) {
					throw new exception( esc_html__( 'Invalid Rule ID' , 'gift-wrapper-for-woocommerce' ) ) ;
				}

				$rule = gtw_get_rule( $rule_id ) ;
				if ( ! $rule->exists() ) {
					throw new exception( esc_html__( 'Invalid Rule ID' , 'gift-wrapper-for-woocommerce' ) ) ;
				}

				$product_id = gtw_get_order_gift_wrapper_product() ;
				if ( empty( $product_id ) ) {
					throw new exception( esc_html__( 'Product not configured for order gift wrapper' , 'gift-wrapper-for-woocommerce' ) ) ;
				}

				$product = wc_get_product( $product_id ) ;

				$response = array(
					'name'  => $rule->get_name() ,
					'price' => '(' . gtw_price( gtw_get_price_to_display( $product , gtw_convert_price( $rule->get_price() ) ) ) . ')' ,
						) ;

				wp_send_json_success( $response ) ;
			} catch ( Exception $ex ) {
				wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
			}
		}

		/**
		 * Remove order gift wrapper.
		 * 
		 * @return void
		 */
		public static function remove_order_gift_wrapper() {
			check_ajax_referer( 'gtw-gift-wrapper' , 'gtw_security' ) ;

			try {

				$cart_item_key = gtw_order_gift_wrapper_in_cart() ;

				if ( empty( $cart_item_key ) ) {
					throw new exception( esc_html__( 'Invalid Request' , 'gift-wrapper-for-woocommerce' ) ) ;
				}

				// Remove order gift wrapper.
				WC()->cart->remove_cart_item( $cart_item_key ) ;

				// Success Notice.
				wc_add_notice( get_option( 'gtw_settings_order_gift_wrapper_removed_notice_localization' ) ) ;

				wp_send_json_success() ;
			} catch ( Exception $ex ) {
				wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
			}
		}

		/**
		 * Display product Gift Wrapper based on pagination.
		 * 
		 * @return void
		 */
		public static function popup_product_gift_wrapper_pagination() {
			check_ajax_referer( 'gtw-popup-gift-wrapper' , 'gtw_security' ) ;

			try {
				if ( ! isset( $_POST ) || ! isset( $_POST[ 'page_number' ] ) ) { // @codingStandardsIgnoreLine.
					throw new exception( esc_html__( 'Invalid Request' , 'gift-wrapper-for-woocommerce' ) ) ;
				}

				// Sanitize post values.
				$current_page = ! empty( $_POST[ 'page_number' ] ) ? absint( $_POST[ 'page_number' ] ) : 0 ; // @codingStandardsIgnoreLine.
				$product_id   = ! empty( $_POST[ 'product_id' ] ) ? absint( $_POST[ 'product_id' ] ) : 0 ;
				$product      = wc_get_product( $product_id ) ;
				if ( ! $product ) {
					throw new exception( esc_html__( 'Invalid Request' , 'gift-wrapper-for-woocommerce' ) ) ;
				}

				$per_page = gtw_get_popup_gift_wrapper_per_page_count() ;
				$offset   = ( $current_page - 1 ) * $per_page ;

				// Get the gift products based on per page count.
				$design_type      = get_post_meta( $product_id , '_gtw_design_type' , true ) ;
				$gift_wrappers    = ( '2' == $design_type ) ? array_filter( ( array ) get_post_meta( $product_id , '_gtw_designs' , true ) ) : gtw_get_active_rule_ids() ;
				$gift_wrapper_ids = array_slice( $gift_wrappers , $offset , $per_page ) ;

				// Get the gift products table body content.
				$html = gtw_get_template_html( 'popup/product-gift-wrapper-items.php' , array(
					'gift_wrappers' => $gift_wrappers
						) ) ;

				wp_send_json_success( array( 'html' => $html ) ) ;
			} catch ( Exception $ex ) {
				wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
			}
		}

		/**
		 * Select Popup Gift Wrapper.
		 * 
		 * @return void
		 */
		public static function select_popup_product_gift_wrapper_item() {
			check_ajax_referer( 'gtw-popup-gift-wrapper' , 'gtw_security' ) ;

			try {
				$rule_id    = isset( $_REQUEST[ 'rule_id' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'rule_id' ] ) ) : '' ; // @codingStandardsIgnoreLine.
				$product_id = isset( $_REQUEST[ 'product_id' ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'product_id' ] ) ) : '' ; // @codingStandardsIgnoreLine.

				if ( empty( $rule_id ) || empty( $product_id ) ) {
					throw new exception( esc_html__( 'Invalid Rule ID' , 'gift-wrapper-for-woocommerce' ) ) ;
				}

				$rule = gtw_get_product_rule_by_id( $product_id , $rule_id ) ;
				if ( ! $rule[ 'id' ] ) {
					throw new exception( esc_html__( 'Invalid Rule ID' , 'gift-wrapper-for-woocommerce' ) ) ;
				}

				$product = wc_get_product( $product_id ) ;
				if ( ! $product->exists() ) {
					throw new exception( esc_html__( 'Invalid Product ID' , 'gift-wrapper-for-woocommerce' ) ) ;
				}

				$item = '<li class="' . implode( ' ' , gtw_get_product_gift_wrapper_classes( $rule_id , $rule_id ) ) . '" data-rule-id="' . esc_attr( $rule_id ) . '">
			<img src="' . esc_url( gtw_get_product_rule_image_url( $rule[ 'image_id' ] ) ) . ' " />
		</li>' ;

				$rule_price    = gtw_convert_price( $rule[ 'price' ] ) ;
				$total_payable = gtw_get_total_payable_price( $product , $rule_price ) ;
				$response      = array(
					'name'  => $rule[ 'name' ] ,
					'price' => '(' . gtw_price( gtw_get_price_to_display( $product , $rule_price ) ) . ')' ,
					'item'  => $item ,
					'total' => gtw_price( $total_payable )
						) ;

				wp_send_json_success( $response ) ;
			} catch ( Exception $ex ) {
				wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
			}
		}

		/**
		 * Display order Gift Wrapper based on pagination.
		 * 
		 * @return void
		 */
		public static function popup_order_gift_wrapper_pagination() {
			check_ajax_referer( 'gtw-popup-gift-wrapper' , 'gtw_security' ) ;

			try {
				if ( ! isset( $_POST ) || ! isset( $_POST[ 'page_number' ] ) ) { // @codingStandardsIgnoreLine.
					throw new exception( esc_html__( 'Invalid Request' , 'gift-wrapper-for-woocommerce' ) ) ;
				}

				// Sanitize post values.
				$current_page = ! empty( $_POST[ 'page_number' ] ) ? absint( $_POST[ 'page_number' ] ) : 0 ; // @codingStandardsIgnoreLine.

				$per_page = gtw_get_popup_gift_wrapper_per_page_count() ;
				$offset   = ( $current_page - 1 ) * $per_page ;

				// Get gift products based on per page count.
				$gift_wrapper_ids = gtw_get_active_rule_ids() ;
				$gift_wrapper_ids = array_slice( $gift_wrapper_ids , $offset , $per_page ) ;

				// Get gift products table body content.
				$html = gtw_get_template_html( 'popup/order-gift-wrapper-items.php' , array(
					'gift_wrapper_ids' => $gift_wrapper_ids
						) ) ;

				wp_send_json_success( array( 'html' => $html ) ) ;
			} catch ( Exception $ex ) {
				wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
			}
		}

		/**
		 * Select order Gift Wrapper.
		 * 
		 * @return void
		 */
		public static function select_popup_order_gift_wrapper_item() {
			check_ajax_referer( 'gtw-popup-gift-wrapper' , 'gtw_security' ) ;

			try {

				$post_data = $_REQUEST ;

				// Check if the designs are enabled.
				if ( gtw_display_gift_wrapper_designs() ) {
					$rule_id = isset( $post_data[ 'rule_id' ] ) ? wc_clean( wp_unslash( $post_data[ 'rule_id' ] ) ) : '' ; // @codingStandardsIgnoreLine.

					if ( empty( $rule_id ) ) {
						throw new exception( esc_html__( 'Invalid Rule ID' , 'gift-wrapper-for-woocommerce' ) ) ;
					}

					$rule = gtw_get_rule( $rule_id ) ;
					if ( ! $rule->exists() ) {
						throw new exception( esc_html__( 'Invalid Rule ID' , 'gift-wrapper-for-woocommerce' ) ) ;
					}
					$rule_id = $rule->get_id() ;
					$price   = $rule->get_price() ;
				} else {
					$rule_id = '' ;
					$price   = get_option( 'gtw_settings_gift_wrapper_design_price' ) ;
				}

				$product_id = gtw_get_order_gift_wrapper_product() ;
				if ( empty( $product_id ) ) {
					throw new exception( esc_html__( 'Product not configured for order gift wrapper' , 'gift-wrapper-for-woocommerce' ) ) ;
				}

				$custom_field_data = array() ;
				if ( isset( $post_data[ 'fields' ] ) ) {
					parse_str( $post_data[ 'fields' ] , $custom_field_data ) ;

					$custom_field_data = GTW_Custom_Fields_Handler::prepare_fields_cart_item_data( $custom_field_data ) ;
				}

				$cart_item_data = array(
					'gtw_gift_wrapper' => array(
						'rule_id' => $rule_id ,
						'price'   => ( float ) $price ,
						'fields'  => $custom_field_data ,
						'mode'    => 'order'
					) ,
						) ;

				// Add to order Gift Wrapper in cart.
				WC()->cart->add_to_cart( $product_id , '1' , 0 , array() , $cart_item_data ) ;

				// Success Notice.
				wc_add_notice( get_option( 'gtw_settings_order_gift_wrapper_add_notice_localization' ) ) ;

				wp_send_json_success( array() ) ;
			} catch ( Exception $ex ) {
				wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
			}
		}

		/**
		 * Add the order Gift Wrapper to cart.
		 * 
		 * @return void
		 */
		public static function add_order_gift_wrapper_item() {
			check_ajax_referer( 'gtw-popup-gift-wrapper' , 'gtw_security' ) ;

			try {

				$product_id = gtw_get_order_gift_wrapper_product() ;
				if ( empty( $product_id ) ) {
					throw new exception( esc_html__( 'Product not configured for order gift wrapper' , 'gift-wrapper-for-woocommerce' ) ) ;
				}

				$cart_item_data = array(
					'gtw_gift_wrapper' => array(
						'rule_id' => '' ,
						'price'   => floatval( get_option( 'gtw_settings_gift_wrapper_design_price' ) ) ,
						'fields'  => array() ,
						'mode'    => 'order'
					) ,
						) ;

				// Add to order Gift Wrapper in cart.
				WC()->cart->add_to_cart( $product_id , '1' , 0 , array() , $cart_item_data ) ;

				// Success Notice.
				wc_add_notice( get_option( 'gtw_settings_order_gift_wrapper_add_notice_localization' ) ) ;

				wp_send_json_success( array() ) ;
			} catch ( Exception $ex ) {
				wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
			}
		}

	}

	GTW_Admin_Ajax::init() ;
}
