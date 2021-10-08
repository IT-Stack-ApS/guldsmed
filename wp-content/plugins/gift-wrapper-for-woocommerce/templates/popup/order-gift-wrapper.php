<?php
/**
 * This template displays the popup order gift wrapper.
 *
 * This template can be overridden by copying it to yourtheme/gift-wrapper-for-woocommerce/popup/popup-order-gift-wrapper.php
 *
 * To maintain compatibility, Gift Wrapper for WooCommerce will update the template files and you have to copy the updated files to your theme
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}
?>
<div class="gtw_hide">
	<div id="gtw-popup-order-gift-wrapper-modal" class="gtw-popup-order-gift-wrapper-modal">
		<div class="gtw-popup-order-gift-wrapper-header">
			<h4><?php echo wp_kses_post( get_option( 'gtw_settings_gift_wrapper_design_localization' ) ) ; ?></h4>
			<p class="gtw-popup-description"><?php echo wp_kses_post( wpautop( wptexturize( get_option( 'gtw_settings_gift_wrapper_popup_description' ) ) ) ) ; ?></p>
		</div>

		<div class="gtw-popup-order-gift-wrapper-content">
			<?php if ( $show_designs ) : ?>
				<p class="gtw-product-gift-wrapper-description">
					<input type="hidden" class="gtw-popup-order-gift-wrapper-current-item" value="<?php echo esc_attr( $current_item ) ; ?>"/>
					<span class="gtw-product-gift-wrapper-name"><?php echo esc_html( $gift_wrapper_name ) ; ?></span>
					<span class="gtw-product-gift-wrapper-price"><strong><?php echo '(' . wp_kses_post( $gift_wrapper_price ) . ')' ; ?></strong></span>
				</p>
				<div class="gtw-popup-order-gift-wrapper-items">
					<?php
					gtw_get_template( 'popup/order-gift-wrapper-items.php' , array(
						'gift_wrapper_ids' => $gift_wrapper_ids ,
						'current_item'     => $current_item ,
					) ) ;
					?>
				</div>

				<?php if ( $pagination[ 'page_count' ] > 1 ) : ?>
					<div class="gtw-popup-order-gift-wrapper-pagination">
						<?php gtw_get_template( 'pagination.php' , $pagination ) ; ?>
					</div>
				<?php endif ; ?> 
			<?php endif ; ?>

			<?php do_action( 'gtw_after_order_gift_wrapper_summary' ) ; ?>
		</div>

		<div class="gtw-popup-order-gift-wrapper-footer">
			<input type="button" class="button gtw-popup-select-order-gift-wrapper" value="<?php echo esc_attr( get_option( 'gtw_settings_select_design_button_localization' ) ) ; ?>"/>
		</div>
	</div>
</div>
<?php
