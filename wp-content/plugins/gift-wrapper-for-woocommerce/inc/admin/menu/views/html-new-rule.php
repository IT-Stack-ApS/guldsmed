<?php
/**
 * Gift Wrapper Meta Box Content.
 * */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}
?>
<tr>
	<td>
		<p class = "gtw-drag-rules-wrapper">
			<span class="dashicons dashicons-menu gtw-drag-rules" title="<?php esc_attr_e( 'Sort' , 'gift-wrapper-for-woocommerce' ) ; ?>"></span>
			<input type = "hidden" class = "gtw-drag-rule-id" value = "new" />
		</p>
	</td>
	<td>
		<input type="text" name="gtw_wrapping_styles[new][<?php echo esc_attr( $key ) ; ?>][name]" required="required" value=""/>
	</td>
	<td>
		<p class="gtw-gift-wrapper-image-preview">
			<img src="<?php echo esc_url( wc_placeholder_img_src() ) ; ?>" />
		</p>
		<input type="hidden" name="gtw_wrapping_styles[new][<?php echo esc_attr( $key ) ; ?>][image_id]" value=""/>
		<input type="button" class="gtw-select-image" value="<?php esc_attr_e( 'Choose Image' , 'gift-wrapper-for-woocommerce' ) ; ?>"/>

	</td>
	<td>
		<input type="text" class="wc_input_price gtw_wrapping_style_price" name="gtw_wrapping_styles[new][<?php echo esc_attr( $key ) ; ?>][price]" min="0" value=""/>
	</td>
	<td>
		<input type="hidden" class="gtw-rule-count" value="<?php echo esc_attr( $key ) ; ?>"/>
		<span class="dashicons dashicons-dismiss gtw-remove-rule" data-rule-id="" title="<?php esc_attr_e( 'Remove Design' , 'gift-wrapper-for-woocommerce' ) ; ?>"></span>
	</td>
</tr>
<?php
