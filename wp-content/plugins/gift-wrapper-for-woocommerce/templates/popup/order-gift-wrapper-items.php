<?php
/**
 * This template displays the order gift wrapper items.
 *
 * This template can be overridden by copying it to yourtheme/gift-wrapper-for-woocommerce/popup/order-gift-wrapper-items.php
 *
 * To maintain compatibility, Gift Wrapper for WooCommerce will update the template files and you have to copy the updated files to your theme
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}
?>
<ul class="gtw-popup-order-gift-wrapper-item-lists">
	<?php
	foreach ( $gift_wrapper_ids as $gift_wrapper_id ) :
		$gift_wrapper = gtw_get_rule( $gift_wrapper_id ) ;
		?>
		<li class="<?php echo esc_attr( implode( ' ' , gtw_get_popup_order_gift_wrapper_classes( $gift_wrapper_id , $current_item ) ) ) ; ?>" 
			data-rule-id="<?php echo esc_attr( $gift_wrapper->get_id() ) ; ?>">

			<img class="gtw_popup_item_image gtw_popup_product_gift_wrapper_item_image" src="<?php echo esc_url( $gift_wrapper->get_image_url() ) ; ?>" />
		</li>
	<?php endforeach ; ?>
</ul>
<?php
