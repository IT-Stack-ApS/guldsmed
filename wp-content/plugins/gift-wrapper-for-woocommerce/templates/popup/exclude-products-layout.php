<?php
/**
 * This template displays the exclude products layout.
 *
 * This template can be overridden by copying it to yourtheme/gift-wrapper-for-woocommerce/popup/exclude-products-layout.php
 *
 * To maintain compatibility, Gift Wrapper for WooCommerce will update the template files and you have to copy the updated files to your theme
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

$_columns = array(
	'product_name'  => esc_html__( 'Product Name' , 'gift-wrapper-for-woocommerce' ) ,
	'product_image' => esc_html__( 'Product Image' , 'gift-wrapper-for-woocommerce' ) ,
		) ;
?>
<div id="gtw-popup-order-exclude-items-modal" class="gtw-popup-order-exclude-items-modal gtw_hide">

	<table class="shop_table shop_table_responsive gtw-popup-order-exclude-items-table">

		<thead>
			<tr>
				<?php foreach ( $_columns as $column_name ) : ?>
					<th><?php echo esc_html( $column_name ) ; ?></th>
				<?php endforeach ; ?>
			</tr>
		</thead>

		<tbody>
			<?php
			gtw_get_template(
					'popup/exclude-products.php' , array(
				'exclude_products' => $exclude_products
					)
			) ;
			?>
		</tbody>

		<?php if ( $pagination[ 'page_count' ] > 1 ) : ?>
			<tfoot>
				<tr>
					<td colspan="<?php echo esc_attr( count( $_columns ) ) ; ?>">
						<?php if ( $pagination[ 'page_count' ] > 1 ) : ?>
							<div class="gtw-popup-order-exculde-gift-wrapper-pagination">
								<?php gtw_get_template( 'pagination.php' , $pagination ) ; ?>
							</div>
						</td>
					<?php endif ; ?>   
				</tr>
			</tfoot>
		<?php endif ; ?>

	</table>
</div>
<?php
