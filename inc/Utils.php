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

function shouldSetupCheckout() {
	if ( null !== PIQ_MID && null !== PIQ_TOTAL_AMOUNT && null !== PIQ_TOTAL_AMOUNT ) {
		return true;
	} else {
		return false;	
	}
}

/* Getter functions for variables defined in paymentiq-checkout.php plugin main class  */
function getPiqMerchantId() {
	echo PIQ_MID;
}

function getPiqTotalAmount() {
	echo PIQ_TOTAL_AMOUNT;
}

function getOrderId() {
	echo PIQ_ORDER_ID;
}

function updateOrderStatus ( $status ) {
  // call an action hook that main class reacts to
  do_action( 'piq_co_update_order_status', $status );
}