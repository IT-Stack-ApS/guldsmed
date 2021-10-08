<?php
/**
 * This template displays the product gift wrapper fields.
 *
 * This template can be overridden by copying it to yourtheme/gift-wrapper-for-woocommerce/popup/product-gift-wrapper-fields.php
 *
 * To maintain compatibility, Gift Wrapper for WooCommerce will update the template files and you have to copy the updated files to your theme
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}
?>
<div class="gtw-popup-product-gift-wrapper-fields gtw-gift-wrapper">
	<div class="gtw-popup-product-gift-wrapper-fields-content">

		<?php
		/**
		 * Hook: gtw_before_product_gift_wrapper_fields hook.
		 *
		 * */
		do_action( 'gtw_before_product_gift_wrapper_fields' ) ;
		?>

		<?php
		foreach ( $fields as $field ) :

			if ( ! is_object( $field ) ) {
				continue ;
			}

			switch ( $field->get_field_type() ) {
				case 'text':
					?>
					<div class="<?php echo esc_attr( implode( ' ' , gtw_get_field_wrapper_classes( $field->get_field_key() ) ) ) ; ?>">
						<label><?php echo wp_kses_post( gtw_get_custom_field_name( $field ) ) ; ?></label>
						<input type="text" class="gtw-gift-wrapper-fields" name="<?php echo esc_attr( $field->get_field_key() ) ; ?>" />
						<span class="gtw-gift-wrapper-description"><?php echo wp_kses_post( gtw_get_custom_field_description( $field ) ) ; ?></span>
					</div>
					<?php
					break ;

				case 'textarea':
					?>
					<div class="<?php echo esc_attr( implode( ' ' , gtw_get_field_wrapper_classes( $field->get_field_key() ) ) ) ; ?>">
						<label><?php echo wp_kses_post( gtw_get_custom_field_name( $field ) ) ; ?></label>
						<textarea class="gtw-gift-wrapper-fields gtw-gift-wrapper-message" name="<?php echo esc_attr( $field->get_field_key() ) ; ?>" maxlength="<?php echo esc_attr( $field->get_character_count() ) ; ?>"></textarea>
						<span class="gtw-gift-wrapper-description gtw-gift-wrapper-validate-message"><?php echo wp_kses_post( gtw_get_formatted_textarea_field_description( $field ) ) ; ?></span>
					</div>
					<?php
					break ;
			}
		endforeach ;

		/**
		 * Hook: gtw_after_product_gift_wrapper_fields hook.
		 *
		 * */
		do_action( 'gtw_after_product_gift_wrapper_fields' ) ;
		?>
	</div>
</div>
<?php
