<?php
/** Suppress Woo Webhook - Log **/

function woo_log($text)
{
	$logger = wc_get_logger();
	
	$logger->info($text);
}


/*
**
 Suppress Woo Webhook
**
*/

function servicepos_rest_api_suppress_woo_webhook($should_deliver, $webhookObject, $arg) {
	    
    // Is this a REST request?
    if (defined('REST_REQUEST') && REST_REQUEST) {

        woo_log("[woo_is_rest_request]" . $should_deliver );

        //if suppress header exists
        if ( isset($_SERVER['HTTP_HTTP_X_SUPPRESS_HOOKS']) || isset($_SERVER['HTTP_X_SUPPRESS_HOOKS'])) {

            $should_deliver = false;

            woo_log("[woo_suppress_header_exists]" . $should_deliver );

        }
    }
 
    return $should_deliver;
}

add_filter('woocommerce_webhook_should_deliver', 'servicepos_rest_api_suppress_woo_webhook', 10, 3);