<?php
/**
 * This template displays the popup product gift wrapper.
 *
 * This template can be overridden by copying it to yourtheme/gift-wrapper-for-woocommerce/popup/product-gift-wrapper-items.php
 *
 * To maintain compatibility, Gift Wrapper for WooCommerce will update the template files and you have to copy the updated files to your theme
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}
?>
<ul class="gtw-popup-product-gift-wrapper-item-lists">
	<?php
	foreach ( $gift_wrappers as $gift_wrapper ) :
		$gift_wrapper = gtw_format_product_rule( $gift_wrapper ) ;
		?>
		<li class="<?php echo esc_attr( implode( ' ' , gtw_get_popup_product_gift_wrapper_classes( $gift_wrapper[ 'id' ] , $current_item ) ) ) ; ?>" 
			data-rule-id="<?php echo esc_attr( $gift_wrapper[ 'id' ] ) ; ?>">

			<img class="gtw_popup_item_image gtw_popup_order_gift_wrapper_item_image" src="<?php echo esc_url( gtw_get_product_rule_image_url( $gift_wrapper[ 'image_id' ] ) ) ; ?>" />
		</li>
	<?php endforeach ; ?>
</ul>
<?php
