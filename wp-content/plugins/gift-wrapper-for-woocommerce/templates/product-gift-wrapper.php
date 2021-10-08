<?php
/**
 * This template displays the product gift wrapper.
 *
 * This template can be overridden by copying it to yourtheme/gift-wrapper-for-woocommerce/product-gift-wrapper.php
 *
 * To maintain compatibility, Gift Wrapper for WooCommerce will update the template files and you have to copy the updated files to your theme
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}
?>
<div class="gtw-product-gift-wrapper">
	<div class="gtw-product-gift-wrapper-content">

		<div class="gtw-product-gift-wrapper-enable-field">
			<label><?php echo wp_kses_post( gtw_get_product_gift_wrapper_label( $product ) ) ; ?></label>
			<input type="checkbox" class="gtw-product-gift-wrapper-enable" name="gtw_enable_gift_wrapper"/>
		</div>

		<?php if ( gtw_display_gift_wrapper_designs() ) : ?>
			<div class="gtw-product-gift-wrapper-items-content gtw-gift-wrapper">
				<h4><?php echo wp_kses_post( get_option( 'gtw_settings_gift_wrapper_design_localization' ) ) ; ?></h4>
				<p class="gtw-product-gift-wrapper-description">
					<span class="gtw-product-gift-wrapper-name"><?php echo esc_html( $gift_wrapper_name ) ; ?></span>
					<span class="gtw-product-gift-wrapper-price"><strong><?php echo '(' . wp_kses_post( $gift_wrapper_price ) . ')' ; ?></strong></span>
				</p>

				<ul class="gtw-product-gift-wrapper-items">
					<?php
					foreach ( $gift_wrappers as $gift_wrapper ) :
						$gift_wrapper = gtw_format_product_rule( $gift_wrapper ) ;
						?>
						<li class="<?php echo esc_attr( implode( ' ' , gtw_get_product_gift_wrapper_classes( $gift_wrapper[ 'id' ] , $current_item ) ) ) ; ?>" 
							data-rule-id="<?php echo esc_attr( $gift_wrapper[ 'id' ] ) ; ?>">

							<img src="<?php echo esc_attr( gtw_get_product_rule_image_url( $gift_wrapper[ 'image_id' ] ) ) ; ?>" />
						</li>
					<?php endforeach ; ?>

					<?php if ( $see_more ) : ?>
						<li class="gtw-product-gift-wrapper-item gtw-popup-extra-gift-wrapper gtw-popup-gift-wrapper" data-popup="#gtw-popup-product-gift-wrapper-modal">
							<p><?php echo wp_kses_post( get_option( 'gtw_settings_gift_wrapper_see_more_localization' ) ) ; ?></p>
						</li>
					<?php endif ; ?>
				</ul>
				<input type="hidden" class="gtw-product-gift-wrapper-product-id" value="<?php echo esc_attr( $product->get_id() ) ; ?>"/>
				<input type="hidden" class="gtw-product-gift-wrapper-current-item" name="gtw_gift_wrapper_item" value="<?php echo esc_attr( $current_item ) ; ?>"/>
			</div>
		<?php endif ; ?>

		<?php do_action( 'gtw_after_product_gift_wrapper_summary' ) ; ?>

		<?php if ( gtw_show_product_gift_wrapper_total_payable() ) : ?>
			<div class="gtw-product-gift-wrapper-total-payable-content gtw-gift-wrapper">
				<label><?php echo wp_kses_post( get_option( 'gtw_settings_gift_wrapper_total_payable_localization' ) ) ; ?></label>
				<span class="gtw-product-gift-wrapper-total-payable"><strong><?php echo wp_kses_post( $total_payable ) ; ?></strong></span>
			</div>
		<?php endif ; ?>
	</div>
</div>
<?php
