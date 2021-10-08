<?php
/**
 * Panel - Product gift wrapper design.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ;
}
?>
<tr>
	<td>
		<p class = "gtw-product-drag-rules-wrapper">
			<span class="dashicons dashicons-menu gtw-product-drag-rules" title="<?php esc_attr_e( 'Sort' , 'gift-wrapper-for-woocommerce' ) ; ?>"></span>
			<input type = "hidden" class = "gtw-product-drag-rule-id" value = "<?php echo esc_attr( $rule_id ) ; ?>" />
		</p> 
	</td>
	<td>
		<input type="text" name="_gtw_designs[<?php echo esc_attr( $rule_id ) ; ?>][name]" required="required" value=""/>
	</td>
	<td>
		<p class="gtw-gift-wrapper-image-preview">
			<img src="" />
		</p>
		<input type="hidden" name="_gtw_designs[<?php echo esc_attr( $rule_id ) ; ?>][image_id]" value=""/>
		<input type="button" class="gtw-select-image" value="<?php esc_attr_e( 'Choose Image' , 'gift-wrapper-for-woocommerce' ) ; ?>"/>
	</td>
	<td>
		<input type="text" class="wc_input_price gtw_wrapping_style_price" name="_gtw_designs[<?php echo esc_attr( $rule_id ) ; ?>][price]" min="0" value=""/>
	</td>
	<td>
		<span class="dashicons dashicons-dismiss gtw-product-remove-rule" title="<?php esc_attr_e( 'Remove Design' , 'gift-wrapper-for-woocommerce' ) ; ?>"></span>
	</td>
</tr>
<?php
	


