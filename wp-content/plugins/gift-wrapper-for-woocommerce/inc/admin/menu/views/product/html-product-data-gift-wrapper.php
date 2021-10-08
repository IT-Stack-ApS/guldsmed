<?php
/**
 * Panel - Product gift wrapper.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ;
}
?>
<div id="gtw_gift_wrapper_tab" class="panel gtw-gift-wrapper-options woocommerce_options_panel">
	<div class="options_group">
		<?php
		woocommerce_wp_select(
				array(
					'id'      => '_gtw_design_type' ,
					'label'   => __( 'Gift Wrapper Design' , 'gift-wrapper-for-woocommerce' ) ,
					'options' => array(
						'1' => __( 'Use Globally Created Designs' , 'gift-wrapper-for-woocommerce' ) ,
						'2' => __( 'Use Product Level Designs' , 'gift-wrapper-for-woocommerce' )
					) ,
					'default' => '1' ,
				)
		) ;
		?>
	</div>

	<div class="options_group gtw-product-designs">
		<h3><?php esc_html_e( 'List of Designs' , 'gift-wrapper-for-woocommerce' ) ; ?></h3>
		<?php
		// Include the file of designs.
		include_once 'html-product-data-gift-wrapper-designs.php' ;
		?>
	</div>
</div>
<?php
