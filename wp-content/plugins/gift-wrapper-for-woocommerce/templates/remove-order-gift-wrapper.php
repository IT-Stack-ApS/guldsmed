<?php
/**
 * This template displays the remove order gift wrapper.
 *
 * This template can be overridden by copying it to yourtheme/gift-wrapper-for-woocommerce/remove-order-gift-wrapper.php
 *
 * To maintain compatibility, Gift Wrapper for WooCommerce will update the template files and you have to copy the updated files to your theme
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}
?>
<a href="#" class="gtw-remove-order-gift-wrapper"><?php echo wp_kses_post( get_option( 'gtw_settings_order_gift_wrapper_remove_link_localization' ) ) ; ?></a>
<?php
