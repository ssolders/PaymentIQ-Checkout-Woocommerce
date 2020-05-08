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

function piq_wc_show_checkout () {
  $currentOrder = piq_create_or_update_order();
}

function piq_create_or_update_order( $order_id = null ) {
	// Need to calculate these here, because WooCommerce hasn't done it yet.
	
	$available_gateways = WC()->payment_gateways->payment_gateways();
	$payment_method     = $available_gateways['paymentiq-checkout'];

	$manualOrder = array(
		'status' => 'pending',
		'payment_method' => $payment_method->id,
		'billing_email' => ''
	);

	$cart = WC()->cart;
	$checkout = WC()->checkout();
	$order_id = $checkout->create_order($manualOrder);
	$order = wc_get_order( $order_id );
	update_post_meta($order_id, '_customer_user', get_current_user_id());
	$totalAmount = $order->calculate_totals();

	$piqClass = PIQ_CHECKOUT_WC();
	PIQ_CHECKOUT_WC()->PIQ_TOTAL_AMOUNT = $totalAmount;
	PIQ_CHECKOUT_WC()->PIQ_ORDER_ID = $order_id;
	PIQ_CHECKOUT_WC()->PIQ_RECEIPT_URL = $order->get_checkout_payment_url();
	$piqClass = PIQ_CHECKOUT_WC();
	
	do_action( 'woocommerce_checkout_create_order', $order, array() );
	do_action( 'woocommerce_checkout_update_order_meta', $order_id, array() );
}

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
	echo PIQ_CHECKOUT_WC()->PIQ_TOTAL_AMOUNT;
}

function getOrderId() {
	echo PIQ_CHECKOUT_WC()->PIQ_ORDER_ID;
}

function updateOrderStatus ( $status ) {
  // call an action hook that main class reacts to
  do_action( 'piq_co_update_order_status', $status );
}