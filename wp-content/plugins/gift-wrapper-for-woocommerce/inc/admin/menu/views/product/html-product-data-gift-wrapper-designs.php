<?php
/**
 * Panel - Product gift wrapper designs.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ;
}
$rules = get_post_meta( $thepostid , '_gtw_designs' , true ) ;
?>
<table class="gtw-product-gift-wrapper-designs-table">
	<thead>
	<th><?php esc_html_e( 'Sort' , 'gift-wrapper-for-woocommerce' ) ; ?></th>
	<th><?php esc_html_e( 'Name' , 'gift-wrapper-for-woocommerce' ) ; ?></th>
	<th><?php esc_html_e( 'Image' , 'gift-wrapper-for-woocommerce' ) ; ?></th>
	<th><?php esc_html_e( 'Price' , 'gift-wrapper-for-woocommerce' ) ; ?></th>
	<th><?php esc_html_e( 'Remove' , 'gift-wrapper-for-woocommerce' ) ; ?></th>
</thead>

<tbody>
	<?php if ( gtw_check_is_array( $rules ) ) : ?>
		<?php
		foreach ( $rules as $rule_id => $rule ) :
			?>
			<tr>
				<td>
					<p class = "gtw-product-drag-rules-wrapper">
						<span class="dashicons dashicons-menu gtw-product-drag-rules" title="<?php esc_attr_e( 'Sort' , 'gift-wrapper-for-woocommerce' ) ; ?>"></span>
						<input type = "hidden" class = "gtw-product-drag-rule-id" value = "<?php echo esc_attr( $rule_id ) ; ?>" />
					</p> 
				</td>
				<td>
					<input type="text" name="_gtw_designs[<?php echo esc_attr( $rule_id ) ; ?>][name]" required="required" value="<?php echo esc_attr( $rule[ 'name' ] ) ; ?>"/>
				</td>
				<td>
					<p class="gtw-gift-wrapper-image-preview">
						<img src="<?php echo esc_url( gtw_get_product_rule_image_url( $rule[ 'image_id' ] ) ) ; ?>" />
					</p>
					<input type="hidden" name="_gtw_designs[<?php echo esc_attr( $rule_id ) ; ?>][image_id]" value="<?php echo esc_attr( $rule[ 'image_id' ] ) ; ?>"/>
					<input type="button" class="gtw-select-image" value="<?php esc_attr_e( 'Choose Image' , 'gift-wrapper-for-woocommerce' ) ; ?>"/>
				</td>
				<td>
					<input type="text" class="wc_input_price gtw_wrapping_style_price" name="_gtw_designs[<?php echo esc_attr( $rule_id ) ; ?>][price]" min="0" value="<?php echo esc_html( wc_format_localized_price( $rule[ 'price' ] ) ) ; ?>"/>
				</td>
				<td>
					<span class="dashicons dashicons-dismiss gtw-product-remove-rule" title="<?php esc_attr_e( 'Remove Design' , 'gift-wrapper-for-woocommerce' ) ; ?>"></span>
				</td>
			</tr>
		<?php endforeach ; ?>
	<?php endif ; ?>
</tbody>

<tfoot>
<td colspan="5">
	<input type="button" class="gtw-add-product-new-rule" value="<?php esc_attr_e( 'Add Design' , 'gift-wrapper-for-woocommerce' ) ; ?>"/>
</td>
</tfoot>

</table>
<?php
