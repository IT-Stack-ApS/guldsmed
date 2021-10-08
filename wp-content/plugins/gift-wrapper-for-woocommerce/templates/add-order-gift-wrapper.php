<?php
/**
 * This template displays the add order gift wrapper.
 *
 * This template can be overridden by copying it to yourtheme/gift-wrapper-for-woocommerce/add-order-gift-wrapper.php
 *
 * To maintain compatibility, Gift Wrapper for WooCommerce will update the template files and you have to copy the updated files to your theme
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}
?>
<p class="gtw-add-order-gift-wrapper-content">
	<button type="button" class="<?php echo esc_attr( implode( ' ' , gtw_get_order_gift_wrapper_classes( $show_popup ) ) ) ; ?>" data-popup="#gtw-popup-order-gift-wrapper-modal">
		<?php echo wp_kses_post( gtw_get_order_gift_wrapper_button_label( $show_popup , $product ) ) ; ?>
	</button>
</p>
<?php
