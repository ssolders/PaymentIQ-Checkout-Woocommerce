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

function piqCheckoutMerchantId() {
	echo PIQ_MID;
}

function piqCheckoutTotalAmount() {
	echo PIQ_TOTAL_AMOUNT;
}

function piqCheckoutOrderId() {
	echo PIQ_ORDER_ID;
}