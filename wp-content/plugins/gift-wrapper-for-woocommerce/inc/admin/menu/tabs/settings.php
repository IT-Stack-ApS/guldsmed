<?php

/**
 * Settings Tab
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( class_exists( 'GTW_Settings_Tab' ) ) {
	return new GTW_Settings_Tab() ;
}

/**
 * GTW_Settings_Tab.
 */
class GTW_Settings_Tab extends GTW_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'settings' ;
		$this->label = esc_html__( 'Settings' , 'gift-wrapper-for-woocommerce' ) ;

		parent::__construct() ;
	}

	/**
	 * Get sections.
	 */
	public function get_sections() {
		$sections = array(
			'general'       => esc_html__( 'General' , 'gift-wrapper-for-woocommerce' ) ,
			'localizations' => esc_html__( 'Localization' , 'gift-wrapper-for-woocommerce' )
				) ;

		return apply_filters( $this->plugin_slug . '_get_sections_' . $this->id , $sections ) ;
	}

	/**
	 * Get settings for general section array.
	 */
	public function general_section_array() {
		$section_fields = array() ;
		$wc_categories  = gtw_get_wc_categories() ;

		// General Section Start
		$section_fields[] = array(
			'type'  => 'title' ,
			'title' => esc_html__( 'General Settings' , 'gift-wrapper-for-woocommerce' ) ,
			'id'    => 'gtw_general_options' ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Gift Wrapper Mode' , 'gift-wrapper-for-woocommerce' ) ,
			'type'    => 'select' ,
			'default' => '1' ,
			'id'      => $this->get_option_key( 'gift_wrapper_design_type' ) ,
			'options' => array(
				'1' => esc_html__( 'With Designs' , 'gift-wrapper-for-woocommerce' ) ,
				'2' => esc_html__( 'Without Designs' , 'gift-wrapper-for-woocommerce' ) ,
			) ,
				) ;
		$section_fields[] = array(
			'title'     => esc_html__( 'Gift Wrapper Price' , 'gift-wrapper-for-woocommerce' ) ,
			'type'      => 'gtw_custom_fields' ,
			'gtw_field' => 'price' ,
			'default'   => '' ,
			'id'        => $this->get_option_key( 'gift_wrapper_design_price' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Display Gift Wrapper option on Single Product Page' , 'gift-wrapper-for-woocommerce' ) ,
			'type'    => 'checkbox' ,
			'default' => 'no' ,
			'class'   => 'gtw-enabled-product-gift-wrapper' ,
			'id'      => $this->get_option_key( 'enable_gift_wrapper_product_page' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Display Gift Wrapper option for the Entire Order in Cart Page' , 'gift-wrapper-for-woocommerce' ) ,
			'type'    => 'checkbox' ,
			'default' => 'no' ,
			'class'   => 'gtw_enabled_order_gift_wrapper' ,
			'id'      => $this->get_option_key( 'enable_cart_order_gift_wrapper' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Position to Display Entire Order Gift Wrapper in Cart Page' , 'gift-wrapper-for-woocommerce' ) ,
			'id'      => $this->get_option_key( 'cart_order_gift_wrapper_location' ) ,
			'type'    => 'select' ,
			'default' => '1' ,
			'class'   => 'gtw-order-gift-wrapper-field gtw-order-gift-wrapper-cart-field' ,
			'options' => array(
				'1' => esc_html__( 'Before Cart Table' , 'gift-wrapper-for-woocommerce' ) ,
				'2' => esc_html__( 'After Cart Table' , 'gift-wrapper-for-woocommerce' ) ,
			) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Disable the Total Payable' , 'gift-wrapper-for-woocommerce' ) ,
			'type'    => 'checkbox' ,
			'default' => 'no' ,
			'class'   => 'gtw-product-gift-wrapper-field' ,
			'id'      => $this->get_option_key( 'disable_product_gift_wrapper_total_payable' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Display Gift Wrapper option for the Entire Order in Checkout Page' , 'gift-wrapper-for-woocommerce' ) ,
			'type'    => 'checkbox' ,
			'default' => 'no' ,
			'class'   => 'gtw_enabled_order_gift_wrapper' ,
			'id'      => $this->get_option_key( 'enable_checkout_order_gift_wrapper' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Position to Display Entire Order Gift Wrapper in Checkout Page' , 'gift-wrapper-for-woocommerce' ) ,
			'id'      => $this->get_option_key( 'checkout_order_gift_wrapper_location' ) ,
			'type'    => 'select' ,
			'default' => '1' ,
			'class'   => 'gtw-order-gift-wrapper-field gtw-order-gift-wrapper-checkout-field' ,
			'options' => array(
				'1' => esc_html__( 'Before Checkout Form' , 'gift-wrapper-for-woocommerce' ) ,
				'2' => esc_html__( 'Before Order Review Heading' , 'gift-wrapper-for-woocommerce' ) ,
			) ,
				) ;
		$section_fields[] = array(
			'title'    => esc_html__( 'Product Type for the Entire Order Gift Wrapper Product' , 'gift-wrapper-for-woocommerce' ) ,
			'class'    => 'gtw-order-gift-wrapper-field' ,
			'type'     => 'select' ,
			'default'  => '1' ,
			'id'       => $this->get_option_key( 'order_gift_wrapper_product_type' ) ,
			'options'  => array(
				'1' => esc_html__( 'New Product' , 'gift-wrapper-for-woocommerce' ) ,
				'2' => esc_html__( 'Existing Product' , 'gift-wrapper-for-woocommerce' ) ,
			) ,
			'desc_tip' => true ,
			'desc'     => esc_html__( 'Whole order gift wrapper requires a product through which the wrapper will be added to cart. If "New Product" is selected, a new product will be created. If "Existing Product" is selected, an existing product has to be selected.' , 'gift-wrapper-for-woocommerce' ) ,
				) ;
		$section_fields[] = array(
			'title'                   => esc_html__( 'Product Selection' , 'gift-wrapper-for-woocommerce' ) ,
			'id'                      => $this->get_option_key( 'order_gift_wrapper_product' ) ,
			'class'                   => 'gtw-order-gift-wrapper-field' ,
			'action'                  => 'gtw_json_search_products_and_variations' ,
			'type'                    => 'gtw_custom_fields' ,
			'exclude_global_variable' => 'yes' ,
			'list_type'               => 'products' ,
			'gtw_field'               => 'ajaxmultiselect' ,
			'placeholder'             => esc_html__( 'Select a Product' , 'gift-wrapper-for-woocommerce' ) ,
			'multiple'                => false ,
			'allow_clear'             => true ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Product Name' , 'gift-wrapper-for-woocommerce' ) ,
			'type'    => 'text' ,
			'default' => 'Gift Wrapper' ,
			'id'      => $this->get_option_key( 'order_gift_wrapper_product_name' ) ,
			'class'   => 'gtw-order-gift-wrapper-field gtw-product-selection' ,
				) ;
		$section_fields[] = array(
			'default'   => esc_html__( 'Create Product' , 'gift-wrapper-for-woocommerce' ) ,
			'id'        => $this->get_option_key( 'create_order_gift_wrapper_product' ) ,
			'class'     => 'button-primary gtw-order-gift-wrapper-field' ,
			'type'      => 'gtw_custom_fields' ,
			'gtw_field' => 'button' ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Multiply Price Based on Quantity for Entire Order Gift Wrapper' , 'gift-wrapper-for-woocommerce' ) ,
			'id'      => $this->get_option_key( 'multiply_gift_wrapper_price' ) ,
			'type'    => 'select' ,
			'default' => '1' ,
			'class'   => 'gtw-order-gift-wrapper-field' ,
			'options' => array(
				'1' => esc_html__( 'No' , 'gift-wrapper-for-woocommerce' ) ,
				'2' => esc_html__( 'Yes' , 'gift-wrapper-for-woocommerce' ) ,
			) ,
				) ;
		$section_fields[] = array(
			'title'    => esc_html__( 'Product(s) to Display the Gift Wrapper option' , 'gift-wrapper-for-woocommerce' ) ,
			'id'       => $this->get_option_key( 'gift_wrapping_product_restriction' ) ,
			'type'     => 'select' ,
			'default'  => '1' ,
			'options'  => array(
				'1' => esc_html__( 'All Product(s)' , 'gift-wrapper-for-woocommerce' ) ,
				'2' => esc_html__( 'Only for Selected Product(s)' , 'gift-wrapper-for-woocommerce' ) ,
				'3' => esc_html__( 'Excluding Selected Product(s)' , 'gift-wrapper-for-woocommerce' ) ,
				'4' => esc_html__( 'All Categories' , 'gift-wrapper-for-woocommerce' ) ,
				'5' => esc_html__( 'Only for Products under Selected Categories' , 'gift-wrapper-for-woocommerce' ) ,
				'6' => esc_html__( 'Excluding Products under Selected Categories' , 'gift-wrapper-for-woocommerce' ) ,
			) ,
			'desc_tip' => true ,
			'desc'     => esc_html__( 'Gift wrapping option for whole order in cart/checkout page will not be displayed if any of the non-applicable products are in cart.' , 'gift-wrapper-for-woocommerce' )
				) ;
		$section_fields[] = array(
			'title'       => esc_html__( 'Select Product(s)' , 'gift-wrapper-for-woocommerce' ) ,
			'id'          => $this->get_option_key( 'gift_wrapping_include_product' ) ,
			'action'      => 'gtw_json_search_products' ,
			'class'       => 'gtw_product_restriction' ,
			'type'        => 'gtw_custom_fields' ,
			'list_type'   => 'products' ,
			'gtw_field'   => 'ajaxmultiselect' ,
			'default'     => array() ,
			'placeholder' => esc_html__( 'Select a Product' , 'gift-wrapper-for-woocommerce' ) ,
				) ;
		$section_fields[] = array(
			'title'       => esc_html__( 'Select Product(s)' , 'gift-wrapper-for-woocommerce' ) ,
			'id'          => $this->get_option_key( 'gift_wrapping_exclude_product' ) ,
			'action'      => 'gtw_json_search_products' ,
			'class'       => 'gtw_product_restriction' ,
			'type'        => 'gtw_custom_fields' ,
			'list_type'   => 'products' ,
			'gtw_field'   => 'ajaxmultiselect' ,
			'default'     => array() ,
			'placeholder' => esc_html__( 'Select a Product' , 'gift-wrapper-for-woocommerce' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Select Categories' , 'gift-wrapper-for-woocommerce' ) ,
			'id'      => $this->get_option_key( 'gift_wrapping_include_categories' ) ,
			'class'   => 'gtw_product_restriction gtw_select2' ,
			'type'    => 'multiselect' ,
			'default' => array() ,
			'options' => $wc_categories ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Select Categories' , 'gift-wrapper-for-woocommerce' ) ,
			'id'      => $this->get_option_key( 'gift_wrapping_exclude_categories' ) ,
			'class'   => 'gtw_product_restriction gtw_select2' ,
			'type'    => 'multiselect' ,
			'default' => array() ,
			'options' => $wc_categories ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Behavior for Entire Order Gift Wrapping when Non-Applicable Product(s) for Gift Wrapping are in Cart' , 'gift-wrapper-for-woocommerce' ) ,
			'id'      => $this->get_option_key( 'exclude_order_gift_wrapper_message_type' ) ,
			'type'    => 'select' ,
			'default' => '1' ,
			'class'   => 'gtw-order-gift-wrapper-field' ,
			'options' => array(
				'1' => esc_html__( 'Hide Gift Wrapper option' , 'gift-wrapper-for-woocommerce' ) ,
				'2' => esc_html__( 'Hide Gift Wrapper option and show a Notice' , 'gift-wrapper-for-woocommerce' ) ,
				'3' => esc_html__( 'Show Gift Wrapper option and show a Notice' , 'gift-wrapper-for-woocommerce' ) ,
			) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Show/Hide Gift Wrapping Enabled for Cart Notice' , 'gift-wrapper-for-woocommerce' ) ,
			'id'      => $this->get_option_key( 'show_order_gift_wrapper_remove_notice' ) ,
			'type'    => 'select' ,
			'default' => '1' ,
			'class'   => 'gtw-order-gift-wrapper-field' ,
			'options' => array(
				'1' => esc_html__( 'Show' , 'gift-wrapper-for-woocommerce' ) ,
				'2' => esc_html__( 'Hide' , 'gift-wrapper-for-woocommerce' ) ,
			) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Page to Display Gift Wrapping Enabled for Cart Notice' , 'gift-wrapper-for-woocommerce' ) ,
			'id'      => $this->get_option_key( 'order_gw_remove_notice_display_type' ) ,
			'type'    => 'select' ,
			'default' => '1' ,
			'class'   => 'gtw-order-gift-wrapper-field gtw-order-gift-wrapper-notice-display-type' ,
			'options' => array(
				'1' => esc_html__( 'All Pages' , 'gift-wrapper-for-woocommerce' ) ,
				'2' => esc_html__( 'Cart & Checkout Page' , 'gift-wrapper-for-woocommerce' ) ,
			) ,
				) ;
		$section_fields[] = array(
			'type' => 'sectionend' ,
			'id'   => 'gtw_general_options' ,
				) ;
		// General Section End
		// Troubleshoot Section Start.
		$section_fields[] = array(
			'type'  => 'title' ,
			'title' => esc_html__( 'Troubleshoot' , 'gift-wrapper-for-woocommerce' ) ,
			'id'    => 'gtw_troubleshoot_options' ,
				) ;
		$section_fields[] = array(
			'title'    => esc_html__( 'Frontend Scripts Enqueued on' , 'gift-wrapper-for-woocommerce' ) ,
			'id'       => $this->get_option_key( 'frontend_enqueue_scripts_type' ) ,
			'type'     => 'select' ,
			'default'  => '1' ,
			'options'  => array(
				'1' => esc_html__( 'Header' , 'gift-wrapper-for-woocommerce' ) ,
				'2' => esc_html__( 'Footer' , 'gift-wrapper-for-woocommerce' ) ,
			) ,
			'desc_tip' => true ,
			'desc'     => esc_html__( 'Choose whether the frontend scripts has to be loaded on Header/Footer' , 'gift-wrapper-for-woocommerce' ) ,
				) ;
		$section_fields[] = array(
			'type' => 'sectionend' ,
			'id'   => 'gtw_troubleshoot_options' ,
				) ;
		// Troubleshoot Section End.
		// Custom CSS Section Start.
		$section_fields[] = array(
			'type'  => 'title' ,
			'title' => esc_html__( 'Custom CSS' , 'gift-wrapper-for-woocommerce' ) ,
			'id'    => 'gtw_custom_css_options' ,
				) ;
		$section_fields[] = array(
			'title'             => esc_html__( 'Custom CSS' , 'gift-wrapper-for-woocommerce' ) ,
			'type'              => 'textarea' ,
			'default'           => '' ,
			'custom_attributes' => array( 'rows' => 10 ) ,
			'id'                => $this->get_option_key( 'custom_css' ) ,
				) ;
		$section_fields[] = array(
			'type' => 'sectionend' ,
			'id'   => 'gtw_custom_css_options' ,
				) ;
		// Custom CSS Section End.

		return $section_fields ;
	}

	/**
	 * Get settings for localization section array.
	 */
	public function localizations_section_array() {
		$section_fields = array() ;

		// Label Customization Section Start.
		$section_fields[] = array(
			'type'  => 'title' ,
			'title' => esc_html__( 'Label Customization' , 'gift-wrapper-for-woocommerce' ) ,
			'id'    => 'gtw_label_customization_options' ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Gift Wrapping Option in Single Product Page Label' , 'gift-wrapper-for-woocommerce' ) ,
			'type'    => 'text' ,
			'default' => 'Gift Wrap this Product' ,
			'id'      => $this->get_option_key( 'product_gift_wrapper_localization' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Gift Wrapping Option in Cart and Checkout Page Label' , 'gift-wrapper-for-woocommerce' ) ,
			'type'    => 'text' ,
			'default' => 'Gift Wrap this Order' ,
			'id'      => $this->get_option_key( 'order_gift_wrapper_localization' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Gift Wrap Design Label' , 'gift-wrapper-for-woocommerce' ) ,
			'type'    => 'text' ,
			'default' => 'Gift Wrap Design' ,
			'id'      => $this->get_option_key( 'gift_wrapper_design_localization' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'See More Label' , 'gift-wrapper-for-woocommerce' ) ,
			'type'    => 'text' ,
			'default' => 'See More' ,
			'id'      => $this->get_option_key( 'gift_wrapper_see_more_localization' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Select Design Button Label' , 'gift-wrapper-for-woocommerce' ) ,
			'type'    => 'text' ,
			'default' => 'Select Design' ,
			'id'      => $this->get_option_key( 'select_design_button_localization' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Total Payable Label' , 'gift-wrapper-for-woocommerce' ) ,
			'type'    => 'text' ,
			'default' => 'Total Payable: ' ,
			'id'      => $this->get_option_key( 'gift_wrapper_total_payable_localization' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Description to be Displayed in Popup' , 'gift-wrapper-for-woocommerce' ) ,
			'type'    => 'textarea' ,
			'default' => '' ,
			'id'      => $this->get_option_key( 'gift_wrapper_popup_description' ) ,
				) ;
		$section_fields[] = array(
			'type' => 'sectionend' ,
			'id'   => 'gtw_label_customization_options' ,
				) ;
		// Label customization Section End
		// Notice customization Section Start.
		$section_fields[] = array(
			'type'  => 'title' ,
			'title' => esc_html__( 'Notice Customization' , 'gift-wrapper-for-woocommerce' ) ,
			'id'    => 'gtw_notice_customization_options' ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Remove Order Gift Wrapper Notice' , 'gift-wrapper-for-woocommerce' ) ,
			'type'    => 'text' ,
			'default' => 'Gift Wrapping is enabled for your cart. To remove the Gift Wrapping use {remove_link}' ,
			'id'      => $this->get_option_key( 'order_gift_wrapper_remove_notice_localization' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Label to Append in {remove_link} Shortcode' , 'gift-wrapper-for-woocommerce' ) ,
			'type'    => 'text' ,
			'default' => 'Remove' ,
			'id'      => $this->get_option_key( 'order_gift_wrapper_remove_link_localization' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Order Gift Wrapper Added Notice' , 'gift-wrapper-for-woocommerce' ) ,
			'type'    => 'text' ,
			'default' => 'Gift Wrapper added successfully.' ,
			'id'      => $this->get_option_key( 'order_gift_wrapper_add_notice_localization' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Order Gift Wrapper Removed Notice' , 'gift-wrapper-for-woocommerce' ) ,
			'type'    => 'text' ,
			'default' => 'Gift Wrapper removed successfully.' ,
			'id'      => $this->get_option_key( 'order_gift_wrapper_removed_notice_localization' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Order Gift Wrapper Removed Notice when No Valid Products are in the Cart' , 'gift-wrapper-for-woocommerce' ) ,
			'type'    => 'text' ,
			'default' => 'Gift Wrapper removed from the cart as there are no valid products in the cart.' ,
			'id'      => $this->get_option_key( 'order_gift_wrapper_not_eligible_notice_localization' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Order Gift Wrapper Notice when Non-Applicable Product(s) for Gift Wrapping are in the Cart' , 'gift-wrapper-for-woocommerce' ) ,
			'type'    => 'text' ,
			'default' => 'One or more product(s) in your cart are not applicable for gift wrapping. {non_applicable_products} to see the list of non-applicable products for gift wrapping' ,
			'id'      => $this->get_option_key( 'exclude_order_gift_wrapper_message' ) ,
				) ;
		$section_fields[] = array(
			'title'   => esc_html__( 'Label to Append in {non_applicable_products} Shortcode' , 'gift-wrapper-for-woocommerce' ) ,
			'type'    => 'text' ,
			'default' => 'Click here' ,
			'id'      => $this->get_option_key( 'exclude_order_gift_wrapper_click_here_label' ) ,
				) ;
		$section_fields[] = array(
			'type' => 'sectionend' ,
			'id'   => 'gtw_notice_customization_options' ,
				) ;
		// Notice customization Section End.

		return $section_fields ;
	}

	/**
	 * May be display the warning notices.
	 * 
	 * @return void
	 */
	public function notices() {

		// Return if the order gift wrapper is not enabled.
		if ( 'yes' != get_option( 'gtw_settings_enable_cart_order_gift_wrapper' ) && 'yes' != get_option( 'gtw_settings_enable_checkout_order_gift_wrapper' ) ) {
			return ;
		}

		$order_gift_wrapper_product = gtw_get_order_gift_wrapper_product() ;

		if ( ! $order_gift_wrapper_product ) {
			GTW_Settings::error_message( esc_html__( 'Please create a new product or select an existing product to show Entire Order Gift Wrapper option in cart/checkout page.' , 'gift-wrapper-for-woocommerce' ) ) ;
		} else {
			$product = wc_get_product( $order_gift_wrapper_product ) ;

			if ( ! is_object( $product ) || 'publish' !== $product->get_status() || ! $product->is_purchasable() ) {
				GTW_Settings::error_message( esc_html__( 'Entire Order Gift Wrapper Product which you have selected is not available for purchase and hence Entire Order Gift Wrapper option will not be displayed for the user. Some of the reasons are Product price is set as empty, the Product is in Trash, the Product is not Published, etc.' , 'gift-wrapper-for-woocommerce' ) ) ;
			}
		}
	}

}

return new GTW_Settings_Tab() ;
