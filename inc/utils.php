<?php
/**
 * Util function for the plugin
 *
 * @package  PaymentIQ Checkout/utils
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Echoes Checkout iframe setup
 */
function piqCheckoutSetup() {
	$klarna_order = kco_create_or_update_order();
	do_action( 'kco_wc_show_snippet', $klarna_order );
	echo $klarna_order['html_snippet'];
}