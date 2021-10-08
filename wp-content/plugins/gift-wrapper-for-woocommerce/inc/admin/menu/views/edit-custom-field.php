<?php
/**
 * Edit Rule Page.
 * */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}
?>
<div class="woocommerce gtw-custom-field-wrapper gtw-edit-custom-field">

	<h2><?php esc_html_e( 'Edit Custom Field' , 'gift-wrapper-for-woocommerce' ) ; ?></h2>

	<table class="form-table">
		<tbody>

			<tr>
				<th scope='row'>
					<label><?php esc_html_e( 'Field Type' , 'gift-wrapper-for-woocommerce' ) ; ?></label>
				</th>
				<td>
					<span><?php echo esc_html( gtw_get_field_type_name( $field->get_field_type() ) ) ; ?></span>
				</td>
			</tr>

			<tr>
				<th scope='row'>
					<label><?php esc_html_e( 'Field Status' , 'gift-wrapper-for-woocommerce' ) ; ?></label>
				</th>
				<td>
					<select name="gtw_custom_field[gtw_custom_field_status]">
						<option value="gtw_active" <?php selected( $custom_field_data[ 'gtw_custom_field_status' ] , 'gtw_active' ) ; ?>><?php esc_html_e( 'Enabled' , 'gift-wrapper-for-woocommerce' ) ; ?></option>
						<option value="gtw_inactive" <?php selected( $custom_field_data[ 'gtw_custom_field_status' ] , 'gtw_inactive' ) ; ?>><?php esc_html_e( 'Disabled' , 'gift-wrapper-for-woocommerce' ) ; ?></option>
					</select>
				</td>
			</tr>

			<tr>
				<th scope='row'>
					<label><?php esc_html_e( 'Field Name' , 'gift-wrapper-for-woocommerce' ) ; ?><span class="required">*</span></label>
				</th>
				<td>
					<input type="text" name="gtw_custom_field[gtw_custom_field_name]" value="<?php echo esc_attr( $custom_field_data[ 'gtw_custom_field_name' ] ) ; ?>"/>
				</td>
			</tr>

			<tr>
				<th scope='row'>
					<label><?php esc_html_e( 'Description' , 'gift-wrapper-for-woocommerce' ) ; ?></label>
				</th>
				<td>
					<textarea name="gtw_custom_field[gtw_custom_field_description]"><?php echo esc_html( $custom_field_data[ 'gtw_custom_field_description' ] ) ; ?></textarea>
				</td>
			</tr>

			<?php if ( 'textarea' == $custom_field_data[ 'gtw_field_type' ] ) : ?>
				<tr>
					<th scope='row'>
						<label><?php esc_html_e( 'Character Count' , 'gift-wrapper-for-woocommerce' ) ; ?></label>
					</th>
					<td>
						<input type="number" class="gtw-custom-field" name="gtw_custom_field[gtw_character_count]" min="1" value="<?php echo esc_attr( $custom_field_data[ 'gtw_character_count' ] ) ; ?>"/>
					</td>				
				</tr>
			<?php endif ; ?>
		</tbody>
	</table>

	<p class="submit">
		<input name='gtw_custom_field_id' type='hidden' value="<?php echo esc_attr( $field->get_id() ) ; ?>" />
		<input name='gtw_save' class='button-primary gtw-save-btn' type='submit' value="<?php esc_attr_e( 'Update Custom Field' , 'gift-wrapper-for-woocommerce' ) ; ?>" />
		<?php wp_nonce_field( 'gtw_update_custom_field' , '_gtw_nonce' , false , true ) ; ?>
	</p>
</div>
<?php
