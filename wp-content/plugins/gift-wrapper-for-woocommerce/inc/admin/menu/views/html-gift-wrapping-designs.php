<?php
/**
 * Gift Wrapper Meta Box Content.
 * */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}
?>
<table class="gtw-gift-wrapper-designs-table">
	<thead>
	<th><?php esc_html_e( 'Sort' , 'gift-wrapper-for-woocommerce' ) ; ?></th>
	<th><?php esc_html_e( 'Name' , 'gift-wrapper-for-woocommerce' ) ; ?></th>
	<th><?php esc_html_e( 'Image' , 'gift-wrapper-for-woocommerce' ) ; ?></th>
	<th><?php esc_html_e( 'Price' , 'gift-wrapper-for-woocommerce' ) ; ?></th>
	<th><?php esc_html_e( 'Remove' , 'gift-wrapper-for-woocommerce' ) ; ?></th>
</thead>

<tbody>
	<?php if ( gtw_check_is_array( $rule_ids ) ) : ?>
		<?php
		foreach ( $rule_ids as $rule_id ) :
			$rule = gtw_get_rule( $rule_id ) ;
			?>
			<tr>
				<td>
					<p class = "gtw-drag-rules-wrapper">
						<span class="dashicons dashicons-menu gtw-drag-rules" title="<?php esc_attr_e( 'Sort' , 'gift-wrapper-for-woocommerce' ) ; ?>"></span>
						<input type = "hidden" class = "gtw-drag-rule-id" value = "<?php echo esc_attr( $rule_id ) ; ?>" />
					</p> 
				</td>
				<td>
					<input type="text" name="gtw_wrapping_styles[<?php echo esc_attr( $rule_id ) ; ?>][name]" required="required" value="<?php echo esc_attr( $rule->get_name() ) ; ?>"/>
				</td>
				<td>
					<p class="gtw-gift-wrapper-image-preview">
						<img src="<?php echo esc_url( $rule->get_image_url() ) ; ?>" />
					</p>
					<input type="hidden" name="gtw_wrapping_styles[<?php echo esc_attr( $rule_id ) ; ?>][image_id]" value="<?php echo esc_attr( $rule->get_image_id() ) ; ?>"/>
					<input type="button" class="gtw-select-image" value="<?php esc_attr_e( 'Choose Image' , 'gift-wrapper-for-woocommerce' ) ; ?>"/>
				</td>
				<td>
					<input type="text" class="wc_input_price gtw_wrapping_style_price" name="gtw_wrapping_styles[<?php echo esc_attr( $rule_id ) ; ?>][price]" min="0" value="<?php echo esc_html( wc_format_localized_price( $rule->get_price() ) ) ; ?>"/>
				</td>
				<td>
					<span class="dashicons dashicons-dismiss gtw-remove-rule" data-rule-id="<?php echo esc_attr( $rule_id ) ; ?>" title="<?php esc_attr_e( 'Remove Design' , 'gift-wrapper-for-woocommerce' ) ; ?>"></span>
				</td>
			</tr>
		<?php endforeach ; ?>
	<?php endif ; ?>
</tbody>

<tfoot>
<td colspan="5"><input type="button" class="gtw_add_new_rule" value="<?php esc_attr_e( 'Add Design' , 'gift-wrapper-for-woocommerce' ) ; ?>"/></td>
</tfoot>

</table>
<?php
