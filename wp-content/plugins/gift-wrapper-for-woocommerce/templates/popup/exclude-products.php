<?php
/**
 * This template displays the exclude products.
 *
 * This template can be overridden by copying it to yourtheme/gift-wrapper-for-woocommerce/popup/exclude-products.php
 *
 * To maintain compatibility, Gift Wrapper for WooCommerce will update the template files and you have to copy the updated files to your theme
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

foreach ( $exclude_products as $exclude_product ) :
	?>
	<tr>
		<?php $_product = wc_get_product( $exclude_product ) ; ?>

		<td data-title="<?php esc_attr_e( 'Product Name' , 'gift-wrapper-for-woocommerce' ) ; ?>"><?php echo wp_kses_post( $_product->get_name() ) ; ?></td>
		<td data-title="<?php esc_attr_e( 'Product Image' , 'gift-wrapper-for-woocommerce' ) ; ?>"><?php echo wp_kses_post( $_product->get_image() ) ; ?></td>
	</tr>
	<?php
endforeach ;

