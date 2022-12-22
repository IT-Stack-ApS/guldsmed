jQuery( function($){
    // Copy the inputed coupon code to WooCommerce hidden default coupon field
    $('.coupon-form input[name="coupon_code"]').on( 'input change', function(){
        $('form.checkout_coupon input[name="coupon_code"]').val($(this).val());
    });
    
    // On button click, submit WooCommerce hidden default coupon form
    $('.coupon-form button[name="apply_coupon"]').on( 'click', function(){
        $('form.checkout_coupon').submit();
    });
});