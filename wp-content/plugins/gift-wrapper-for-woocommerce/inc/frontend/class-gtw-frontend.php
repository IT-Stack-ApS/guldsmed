<?php

/**
 *  Handles the frontend.
 * */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'GTW_Frontend' ) ) {

	/**
	 * Class.
	 * */
	class GTW_Frontend {

		/**
		 * Class Initialization.
		 * */
		public static function init() {

			// Define the order gift wrapper hooks.
			add_action( 'wp_head' , array( __CLASS__ , 'define_order_gift_wrapper_hooks' ) ) ;
			// Render the gift wrapper popup for the single product page.
			add_action( 'woocommerce_before_add_to_cart_button' , array( __CLASS__ , 'render_popup_product_gift_wrapper' ) ) ;
			// Render the gift wrapper for the simple product.
			add_action( 'woocommerce_before_add_to_cart_button' , array( __CLASS__ , 'render_gift_wrapper_simple_product' ) ) ;
			// Alter the array of data for a variation. Used in the add to cart form.
			add_action( 'woocommerce_available_variation' , array( __CLASS__ , 'render_gift_wrapper_variable_product' ) , 10 , 3 ) ;
			// Render product gift wrapper fields.
			add_action( 'gtw_after_product_gift_wrapper_summary' , array( __CLASS__ , 'render_product_gift_wrapper_custom_fields' ) ) ;
			// Render order gift wrapper fields.
			add_action( 'gtw_after_order_gift_wrapper_summary' , array( __CLASS__ , 'render_order_gift_wrapper_custom_fields' ) ) ;
			// Remove order gift wrapper notice for the page.
			add_action( 'wp_head' , array( __CLASS__ , 'add_remove_order_gift_wrapper_notice' ) ) ;
			// Order gift wrapper exclude products notice for the page.
			add_action( 'wp_head' , array( __CLASS__ , 'order_gift_wrapper_exclude_products_notice' ) ) ;
		}

		/**
		 * Define the order gift wrapper hook.
		 * 
		 * @return void
		 * */
		public static function define_order_gift_wrapper_hooks() {
			// Hook for the cart order gift wrapper.
			$cart_location = self::get_cart_current_location() ;
			if ( gtw_check_is_array( $cart_location ) ) {
				add_action( $cart_location[ 'hook' ] , array( __CLASS__ , 'render_popup_cart_order_gift_wrapper' ) , $cart_location[ 'priority' ] ) ;
			}

			// Hook for the checkout order gift wrapper.
			$checkout_location = self::get_checkout_current_location() ;
			if ( gtw_check_is_array( $checkout_location ) ) {
				add_action( $checkout_location[ 'hook' ] , array( __CLASS__ , 'render_popup_checkout_order_gift_wrapper' ) , $checkout_location[ 'priority' ] ) ;
			}
		}

		/**
		 * Get the cart current location.
		 *
		 * @return array.
		 */
		public static function get_cart_current_location() {

			$cart_location = get_option( 'gtw_settings_cart_order_gift_wrapper_location' ) ;

			$location_details = apply_filters( 'gtw_order_gift_wrapper_cart_locations' , array(
				'1' => array(
					'hook'     => 'woocommerce_before_cart_table' ,
					'priority' => 9
				) ,
				'2' => array(
					'hook'     => 'woocommerce_after_cart_table' ,
					'priority' => 10
				)
					)
					) ;

			$location_detail = isset( $location_details[ $cart_location ] ) ? $location_details[ $cart_location ] : reset( $location_details ) ;

			return $location_detail ;
		}

		/**
		 * Get the checkout current location.
		 *
		 * @return array.
		 */
		public static function get_checkout_current_location() {

			$checkout_location = get_option( 'gtw_settings_checkout_order_gift_wrapper_location' ) ;

			$location_details = apply_filters( 'gtw_order_gift_wrapper_checkout_locations' , array(
				'1' => array(
					'hook'     => 'woocommerce_before_checkout_form' ,
					'priority' => 20
				) ,
				'2' => array(
					'hook'     => 'woocommerce_checkout_before_order_review_heading' ,
					'priority' => 10
				)
					)
					) ;

			$location_detail = isset( $location_details[ $checkout_location ] ) ? $location_details[ $checkout_location ] : reset( $location_details ) ;

			return $location_detail ;
		}

		/**
		 * Render gift wrapper popup for the single product page.
		 * 
		 * @return void
		 * */
		public static function render_popup_product_gift_wrapper() {
			// Return if the product gift wrapper is not enabled.
			if ( 'yes' != get_option( 'gtw_settings_enable_gift_wrapper_product_page' ) ) {
				return ;
			}

			// Return if the designs is not enabled.
			if ( ! gtw_display_gift_wrapper_designs() ) {
				return ;
			}

			// Return if the order gift wrapper is in cart.
			if ( gtw_order_gift_wrapper_in_cart() ) {
				return ;
			}

			global $product ;

			$design_type   = get_post_meta( $product->get_id() , '_gtw_design_type' , true ) ;
			$gift_wrappers = ( '2' == $design_type ) ? array_filter( ( array ) get_post_meta( $product->get_id() , '_gtw_designs' , true ) ) : gtw_get_active_rule_ids() ;
			// Return if the rules are not configured.
			if ( ! gtw_check_is_array( $gift_wrappers ) ) {
				return ;
			}

			$current_page         = 1 ;
			$current_item         = ( '2' == $design_type ) ? key( $gift_wrappers ) : reset( $gift_wrappers ) ;
			$current_gift_wrapper = reset( $gift_wrappers ) ;
			$per_page             = gtw_get_popup_gift_wrapper_per_page_count() ;

			/* Calculate Page Count */
			$default_args[ 'posts_per_page' ] = $per_page ;
			$default_args[ 'offset' ]         = ( $current_page - 1 ) * $per_page ;
			$page_count                       = ceil( count( $gift_wrappers ) / $per_page ) ;

			$current_rule = gtw_format_product_rule( $current_gift_wrapper ) ;

			$data_args = array(
				'product_id'         => $product->get_id() ,
				'gift_wrappers'      => array_slice( $gift_wrappers , $default_args[ 'offset' ] , $per_page ) ,
				'current_item'       => $current_item ,
				'gift_wrapper_name'  => $current_rule[ 'name' ] ,
				'gift_wrapper_price' => gtw_price( gtw_get_price_to_display( $product , gtw_convert_price( $current_rule[ 'price' ] ) ) ) ,
				'pagination'         => array(
					'page_count'   => $page_count ,
					'current_page' => $current_page
				) ,
					) ;

			// Display product gift wrapper popup.
			gtw_get_template( 'popup/product-gift-wrapper.php' , $data_args ) ;
		}

		/**
		 * Render gift wrapper Popup for the order in the cart.
		 * 
		 * @return void
		 * */
		public static function render_popup_cart_order_gift_wrapper() {
			// Return if the order gift wrapper is not enabled.
			if ( 'yes' != get_option( 'gtw_settings_enable_cart_order_gift_wrapper' ) ) {
				return ;
			}

			// Return if the cart order gift wrapper is not valid.
			if ( ! apply_filters( 'gtw_is_valid_cart_order_gift_wrapper' , true ) ) {
				return ;
			}

			self::render_popup_order_gift_wrapper( 'cart' ) ;
		}

		/**
		 * Render gift wrapper Popup for the order in the checkout.
		 * 
		 * @return void
		 * */
		public static function render_popup_checkout_order_gift_wrapper() {
			// Return if the order gift wrapper is not enabled.
			if ( 'yes' != get_option( 'gtw_settings_enable_checkout_order_gift_wrapper' ) ) {
				return ;
			}

			// Return if the checkout order gift wrapper is not valid.
			if ( ! apply_filters( 'gtw_is_valid_checkout_order_gift_wrapper' , true ) ) {
				return ;
			}

			self::render_popup_order_gift_wrapper( 'checkout' ) ;
		}

		/**
		 * Render gift wrapper Popup for the order.
		 * 
		 * @return void
		 * */
		public static function render_popup_order_gift_wrapper( $page ) {

			//Return if the order gift wrapper product is not configured.
			$product_id = gtw_get_order_gift_wrapper_product() ;
			if ( empty( $product_id ) ) {
				return ;
			}

			// Return if the gift wrapper is in cart.
			if ( gtw_product_gift_wrapper_in_cart() || gtw_order_gift_wrapper_in_cart() ) {
				return ;
			}

			// Return if the order gift wrapper is not valid.
			if ( ! apply_filters( 'gtw_is_valid_order_gift_wrapper' , true ) ) {
				return ;
			}

			// Validate the cart if eligible for order gift wrapper.
			if ( ! self::validate_cart_product_category() ) {
				return ;
			}

			$show_popup       = false ;
			$custom_field_ids = GTW_Custom_Fields_Handler::get_custom_fields() ;
			$product          = wc_get_product( $product_id ) ;
			if ( gtw_display_gift_wrapper_designs() ) {

				// Return if the rules are not configured.
				$gift_wrapper_ids = gtw_get_active_rule_ids() ;
				if ( ! gtw_check_is_array( $gift_wrapper_ids ) ) {
					return ;
				}

				$per_page     = gtw_get_popup_gift_wrapper_per_page_count() ;
				$current_page = 1 ;

				/* Calculate Page Count */
				$default_args[ 'posts_per_page' ] = $per_page ;
				$default_args[ 'offset' ]         = ( $current_page - 1 ) * $per_page ;
				$page_count                       = ceil( count( $gift_wrapper_ids ) / $per_page ) ;

				$current_item = reset( $gift_wrapper_ids ) ;
				$curent_rule  = gtw_get_rule( $current_item ) ;

				$data_args = array(
					'show_designs'       => true ,
					'gift_wrapper_ids'   => array_slice( $gift_wrapper_ids , $default_args[ 'offset' ] , $per_page ) ,
					'current_item'       => $current_item ,
					'gift_wrapper_name'  => $curent_rule->get_name() ,
					'gift_wrapper_price' => gtw_price( gtw_get_price_to_display( $product , gtw_convert_price( $curent_rule->get_price() ) ) ) ,
					'pagination'         => array(
						'page_count'   => $page_count ,
						'current_page' => $current_page ,
					) ,
						) ;

				$show_popup = true ;
			} elseif ( gtw_check_is_array( $custom_field_ids ) ) {
				$data_args = array(
					'show_designs' => false ,
						) ;

				$show_popup = true ;
			}

			// Display order gift wrapper popup.
			if ( $show_popup ) {
				$popup_file_name = apply_filters( 'gtw_popup_order_gift_wrapper_file_name_' . $page , 'popup/order-gift-wrapper.php' , $data_args ) ;
				gtw_get_template( $popup_file_name , $data_args ) ;
			}

			// Display add order gift wrapper.
			$button_file_name = apply_filters( 'gtw_order_gift_wrapper_button_file_name_' . $page , 'add-order-gift-wrapper.php' ) ;
			gtw_get_template( $button_file_name , array( 'show_popup' => $show_popup , 'product' => $product ) ) ;
		}

		/**
		 * Order gift wrapper remove notice for the page.
		 * 
		 * @return void
		 * */
		public static function add_remove_order_gift_wrapper_notice() {
			// Return if the order gift wrapper remove notice is disabled.  
			if ( '2' == get_option( 'gtw_settings_show_order_gift_wrapper_remove_notice' ) ) {
				return ;
			}

			// Return if the page is not cart/checkout.
			if ( '2' == get_option( 'gtw_settings_order_gw_remove_notice_display_type' ) && ! is_cart() && ! is_checkout() ) {
				return ;
			}

			// Return if the order gift wrapper is not in cart.
			if ( ! gtw_order_gift_wrapper_in_cart() ) {
				return ;
			}

			// Get remove order Gift wrapper html.
			$remove_gift_wrapper_html = gtw_get_template_html( 'remove-order-gift-wrapper.php' ) ;

			$remove_wrapper_notice = str_replace( '{remove_link}' , $remove_gift_wrapper_html , get_option( 'gtw_settings_order_gift_wrapper_remove_notice_localization' ) ) ;

			// Add remove order gift wrapper notice.
			wc_add_notice( $remove_wrapper_notice , 'notice' ) ;
		}

		/**
		 * Order gift wrapper exclude products notice for the page.
		 * 
		 * @return void
		 * */
		public static function order_gift_wrapper_exclude_products_notice() {

			// Return If the current page is not checkout/cart. 
			if ( ! is_cart() && ! is_checkout() ) {
				return ;
			}

			// Return if the product gift wrapper is in cart.
			if ( gtw_product_gift_wrapper_in_cart() ) {
				return ;
			}

			$exclude_products = gtw_get_order_gift_wrapper_exclude_products() ;
			// Return if the exclude products are exists.
			if ( ! gtw_check_is_array( $exclude_products ) ) {
				return ;
			}

			$per_page     = 5 ;
			$current_page = 1 ;

			/* Calculate Page Count */
			$default_args[ 'posts_per_page' ] = $per_page ;
			$default_args[ 'offset' ]         = ( $current_page - 1 ) * $per_page ;
			$page_count                       = ceil( count( $exclude_products ) / $per_page ) ;

			$data_args = array(
				'exclude_products' => array_slice( $exclude_products , $default_args[ 'offset' ] , $per_page ) ,
				'pagination'       => array(
					'page_count'   => $page_count ,
					'current_page' => $current_page
				) ,
					) ;

			// Get html exclude products layout content.
			$html = gtw_get_template_html( 'popup/exclude-products-layout.php' , $data_args ) ;

			$non_applicable_products = $html . "<a href='#' class='gtw-popup-order-exclude-products gtw-popup-gift-wrapper' data-popup='#gtw-popup-order-exclude-items-modal'>" . get_option( 'gtw_settings_exclude_order_gift_wrapper_click_here_label' ) . '</a>' ;

			$exclude_products_notice = str_replace( '{non_applicable_products}' , $non_applicable_products , get_option( 'gtw_settings_exclude_order_gift_wrapper_message' ) ) ;

			// Order gift wrapper exclude products notice.
			wc_add_notice( $exclude_products_notice , 'notice' ) ;
		}

		/**
		 * Render gift wrapper for the simple product.
		 * 
		 * @return void
		 * */
		public static function render_gift_wrapper_simple_product() {
			global $product ;

			// Return null, if the product variable is not object. 
			if ( ! is_object( $product ) ) {
				return null ;
			}

			// Return If the product is a variable product type.
			if ( $product->is_type( 'variable' ) ) {
				return ;
			}

			// Return if the order gift wrapper is in cart.
			if ( gtw_order_gift_wrapper_in_cart() ) {
				return ;
			}

			// Render gift wrapper.
			self::render_gift_wrapper( $product , $product->get_id() ) ;
		}

		/**
		 * Render gift wrapper for the variable product.
		 * 
		 * @return array
		 * */
		public static function render_gift_wrapper_variable_product( $array, $class, $variation ) {
			// Return if the array is empty.
			if ( ! gtw_check_is_array( $array ) ) {
				return $array ;
			}

			// Return null, if the variation variable is not object. 
			if ( ! is_object( $variation ) ) {
				return $array ;
			}

			// Return if the order gift wrapper is in cart.
			if ( gtw_order_gift_wrapper_in_cart() ) {
				return $array ;
			}

			// Get gift wrapper html.
			$gift_wrapper_html = self::render_gift_wrapper( $variation , $variation->get_parent_id() , $variation->get_id() , false ) ;

			if ( ! $gift_wrapper_html ) {
				return $array ;
			}

			$array[ 'gtw_gift_wrapper' ] = $gift_wrapper_html ;

			return $array ;
		}

		/**
		 * Render Gift Wrapper.
		 * 
		 * @return mixed
		 * */
		public static function render_gift_wrapper( $product, $product_id, $variation_id = false, $echo = true ) {

			// Return null, if the product variable is not object. 
			if ( ! is_object( $product ) ) {
				return null ;
			}

			// Return if the product page gift wrapper is not enabled.
			if ( 'yes' != get_option( 'gtw_settings_enable_gift_wrapper_product_page' ) ) {
				return null ;
			}

			// Return if the product gift wrapper is not valid.
			if ( ! apply_filters( 'gtw_is_valid_product_gift_wrapper' , true , $product_id , $variation_id ) ) {
				return null ;
			}

			// Return if the current product is not valid.
			if ( ! self::validate_product_category( $product_id , $variation_id ) ) {
				return null ;
			}

			if ( gtw_display_gift_wrapper_designs() ) {
				$design_type   = get_post_meta( $product_id , '_gtw_design_type' , true ) ;
				$gift_wrappers = ( '2' == $design_type ) ? array_filter( ( array ) get_post_meta( $product_id , '_gtw_designs' , true ) ) : gtw_get_active_rule_ids() ;

				// Return if the rules are not configured.
				if ( ! gtw_check_is_array( $gift_wrappers ) ) {
					return null ;
				}

				$see_more = false ;
				if ( count( $gift_wrappers ) > 4 ) {
					$gift_wrappers = array_slice( $gift_wrappers , 0 , 3 ) ;

					$see_more = true ;
				}

				$current_item         = ( '2' == $design_type ) ? key( $gift_wrappers ) : reset( $gift_wrappers ) ;
				$current_gift_wrapper = reset( $gift_wrappers ) ;
				$current_rule         = gtw_format_product_rule( $current_gift_wrapper ) ;
				$rule_price           = gtw_convert_price( $current_rule[ 'price' ] ) ;
				$total_payable        = gtw_get_total_payable_price( $product , $rule_price ) ;

				$data_args = array(
					'product'            => $product ,
					'gift_wrappers'      => $gift_wrappers ,
					'see_more'           => $see_more ,
					'current_item'       => $current_item ,
					'total_payable'      => gtw_price( $total_payable ) ,
					'gift_wrapper_name'  => $current_rule[ 'name' ] ,
					'gift_wrapper_price' => gtw_price( gtw_get_price_to_display( $product , $rule_price ) ) ,
						) ;
			} else {
				$total_payable = gtw_get_total_payable_price( $product , gtw_convert_price( gtw_get_default_design_price() ) ) ;

				$data_args = array(
					'product'       => $product ,
					'total_payable' => gtw_price( $total_payable )
						) ;
			}

			if ( $echo ) {
				// Display gift wrapper.
				gtw_get_template( 'product-gift-wrapper.php' , $data_args ) ;
			} else {
				// Get gift wrapper html.
				return gtw_get_template_html( 'product-gift-wrapper.php' , $data_args ) ;
			}
		}

		/**
		 * Render product gift wrapper custom fields.
		 * 
		 * @return array
		 * */
		public static function render_product_gift_wrapper_custom_fields() {
			// If no one fields are not exists/active.
			$custom_field_ids = GTW_Custom_Fields_Handler::get_custom_fields() ;
			if ( ! gtw_check_is_array( $custom_field_ids ) ) {
				return ;
			}

			// Display gift wrapper fields.
			gtw_get_template( 'product-gift-wrapper-fields.php' , array( 'fields' => $custom_field_ids ) ) ;
		}

		/**
		 * Render popup product gift wrapper custom fields.
		 * 
		 * @return array
		 * */
		public static function render_order_gift_wrapper_custom_fields() {
			// If no one fields are not exists/active.
			$custom_field_ids = GTW_Custom_Fields_Handler::get_custom_fields() ;
			if ( ! gtw_check_is_array( $custom_field_ids ) ) {
				return ;
			}

			// Display gift wrapper fields.
			gtw_get_template( 'popup/product-gift-wrapper-fields.php' , array( 'fields' => $custom_field_ids ) ) ;
		}

		/**
		 * Validate the cart products/categories.
		 * 
		 * @return bool
		 */
		public static function validate_cart_product_category() {
			$product_restriction = get_option( 'gtw_settings_gift_wrapping_product_restriction' ) ;
			// Return true if the option is all products.
			if ( '1' == $product_restriction ) {
				return true ;
			}

			// Return if a cart object is not initialized.
			if ( ! is_object( WC()->cart ) ) {
				return true ;
			}

			// Return false if the cart is empty.
			$cart_contents = WC()->cart->get_cart() ;
			if ( ! gtw_check_is_array( $cart_contents ) ) {
				return false ;
			}

			$return                 = false ;
			$product_ids            = array() ;
			$category_ids           = array() ;
			$order_include_products = false ;
			$include_products       = get_option( 'gtw_settings_gift_wrapping_include_product' ) ;
			$exclude_products       = get_option( 'gtw_settings_gift_wrapping_exclude_product' ) ;
			$include_categories     = get_option( 'gtw_settings_gift_wrapping_include_categories' ) ;
			$exclude_categories     = get_option( 'gtw_settings_gift_wrapping_exclude_categories' ) ;
			$exclude_type           = get_option( 'gtw_settings_exclude_order_gift_wrapper_message_type' ) ;

			foreach ( $cart_contents as $cart_content ) {

				// Don't consider if the product is a gift wrapper.
				if ( isset( $cart_content[ 'gtw_gift_wrapper' ] ) ) {
					continue ;
				}

				$product_ids[] = $cart_content[ 'product_id' ] ;
				if ( ! empty( $cart_content[ 'variation_id' ] ) ) {
					$product_ids[] = $cart_content[ 'variation_id' ] ;
				}

				switch ( $product_restriction ) {
					case '3':
						$return = true ;
						// Excluded products.
						if ( in_array( $cart_content[ 'product_id' ] , $exclude_products ) || in_array( $cart_content[ 'variation_id' ] , $exclude_products ) ) {
							if ( '3' != $exclude_type ) {
								return false ;
							}
						} else {
							$order_include_products = true ;
						}
						break ;
					case '4':
						// All Categories.
						$product_categories = get_the_terms( $cart_content[ 'product_id' ] , 'product_cat' ) ;
						if ( gtw_check_is_array( $product_categories ) || '3' == $exclude_type ) {
							return true ;
						}
						break ;
					case '5':
						// Included categories.
						$product_categories = get_the_terms( $cart_content[ 'product_id' ] , 'product_cat' ) ;
						if ( gtw_check_is_array( $product_categories ) ) {
							foreach ( $product_categories as $product_category ) {
								$category_ids[] = $product_category->term_id ;
							}
						}
						break ;
					case '6':
						// Excluded categories.
						$return             = true ;
						$product_categories = get_the_terms( $cart_content[ 'product_id' ] , 'product_cat' ) ;
						if ( gtw_check_is_array( $product_categories ) ) {
							foreach ( $product_categories as $product_category ) {
								if ( in_array( $product_category->term_id , $exclude_categories ) ) {
									if ( '3' != $exclude_type ) {
										return false ;
									}
								} else {
									$order_include_products = true ;
								}
							}
						}
				}
			}

			switch ( $product_restriction ) {
				case '2':
					// Return if only the selected products in the cart.
					if ( empty( array_diff( $product_ids , $include_products ) ) ) {
						$return = true ;
					} elseif ( '3' == $exclude_type && ! empty( array_intersect( $include_products , $product_ids ) ) ) {
						$return = true ;
					}
					break ;
				case '5':
					// Return if only the selected categories in the cart.
					if ( ( empty( array_diff( $category_ids , $include_categories ) ) && empty( array_diff( $include_categories , $category_ids ) ) ) || '3' == $exclude_type ) {
						$return = true ;
					}
					break ;
				case '3':
				case '6':
					if ( '3' == $exclude_type && ! $order_include_products ) {
						$return = false ;
					}
					break ;
			}

			return $return ;
		}

		/**
		 * Validate Products/Categories.
		 * 
		 * @return bool
		 */
		public static function validate_product_category( $product_id, $variation_id = false ) {
			$return              = true ;
			$product_restriction = get_option( 'gtw_settings_gift_wrapping_product_restriction' ) ;

			switch ( $product_restriction ) {
				case '2':
					$include_products = get_option( 'gtw_settings_gift_wrapping_include_product' ) ;
					// Return false if a current product is not in selected products.
					if ( gtw_check_is_array( $include_products ) && ! in_array( $product_id , $include_products ) && ! in_array( $variation_id , $include_products ) ) {
						$return = false ;
					}

					break ;
				case '3':
					$exclude_products = get_option( 'gtw_settings_gift_wrapping_exclude_product' ) ;
					// Return false if a current product is in selected products.
					if ( gtw_check_is_array( $exclude_products ) && ( in_array( $product_id , $exclude_products ) || in_array( $variation_id , $exclude_products ) ) ) {
						$return = false ;
					}
					break ;
				case '4':
					// All Categories.
					$product_categories = get_the_terms( $product_id , 'product_cat' ) ;
					if ( ! gtw_check_is_array( $product_categories ) ) {
						$return = false ;
					}

					break ;
				case '5':
					// Included categories.
					$product_categories = get_the_terms( $product_id , 'product_cat' ) ;
					$include_categories = get_option( 'gtw_settings_gift_wrapping_include_categories' ) ;

					if ( gtw_check_is_array( $product_categories ) ) {
						$return = false ;

						foreach ( $product_categories as $product_category ) {
							// Return true if any selected categories are having the current product.
							if ( in_array( $product_category->term_id , $include_categories ) ) {
								$return = true ;
							}
						}
					}

					break ;
				case '6':
					// Excluded categories.
					$product_categories = get_the_terms( $product_id , 'product_cat' ) ;
					$exclude_categories = get_option( 'gtw_settings_gift_wrapping_exclude_categories' ) ;

					if ( gtw_check_is_array( $product_categories ) ) {
						foreach ( $product_categories as $product_category ) {
							if ( in_array( $product_category->term_id , $exclude_categories ) ) {
								$return = false ;
							}
						}
					}

					break ;
			}

			return $return ;
		}

	}

	GTW_Frontend::init() ;
}
