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
	WC()->cart->calculate_fees();
	WC()->cart->calculate_shipping();
	WC()->cart->calculate_totals();
	// if ( WC()->session->get( 'kco_wc_order_id' ) ) { // Check if we have an order id.
	// 	// Try to update the order, if it fails try to create new order.
	// 	$klarna_order = KCO_WC()->api->update_klarna_order( WC()->session->get( 'kco_wc_order_id' ) );
	// 	if ( ! $klarna_order ) {
	// 		// If update order failed try to create new order.
	// 		$klarna_order = KCO_WC()->api->create_klarna_order();
	// 		if ( ! $klarna_order ) {
	// 			// If failed then bail.
	// 			return;
	// 		}
	// 		WC()->session->set( 'kco_wc_order_id', $klarna_order['order_id'] );
	// 		return $klarna_order;
	// 	}
	// 	return $klarna_order;
	// } else {
	// 	// Create new order, since we dont have one.
	// 	$klarna_order = KCO_WC()->api->create_klarna_order();
	// 	if ( ! $klarna_order ) {
	// 		return;
	// 	}
	// 	WC()->session->set( 'kco_wc_order_id', $klarna_order['order_id'] );
	// 	return $klarna_order;
	// }
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
	echo PIQ_TOTAL_AMOUNT;
}

function getOrderId() {
	echo PIQ_ORDER_ID;
}

function updateOrderStatus ( $status ) {
  // call an action hook that main class reacts to
  do_action( 'piq_co_update_order_status', $status );
}