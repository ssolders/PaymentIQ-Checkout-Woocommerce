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

	// $session = WC()->session;

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


	$order->set_billing_first_name( 'Jane' );
	$order->set_billing_last_name( 'Doe' );
	$order->set_billing_country( 'Sweden' );
	$order->set_billing_address_1( 'Vasagatan 14' );
	$order->set_billing_address_2( '' );
	$order->set_billing_city( 'Stockholm' );
	$order->set_billing_state( 'Stockholm' );
	$order->set_billing_postcode( '11750' );
	$order->set_billing_phone( '0700000000' );
	$order->set_billing_email( 'test@example.com' );

	$order->set_shipping_first_name( 'Jane' );
	$order->set_shipping_last_name( 'Doe' );
	$order->set_shipping_country( 'Sweden' );
	$order->set_shipping_address_1( 'Vasagatan 14' );
	$order->set_shipping_address_2( '' );
	$order->set_shipping_city( 'Stockholm' );
	$order->set_shipping_state( 'Stockholm' );
	$order->set_shipping_postcode( '11750' );

	$totalAmount = $order->calculate_totals();

	$piqClass = PIQ_CHECKOUT_WC();
	PIQ_CHECKOUT_WC()->PIQ_TOTAL_AMOUNT = $totalAmount;
	PIQ_CHECKOUT_WC()->PIQ_ORDER_ID = $order_id;
	PIQ_CHECKOUT_WC()->PIQ_RECEIPT_URL = $order->get_checkout_payment_url();
	$piqClass = PIQ_CHECKOUT_WC();
	
	do_action( 'woocommerce_checkout_create_order', $order, array() );

	$order->save();

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
	$instance = PIQ_CHECKOUT_WC();
	echo PIQ_CHECKOUT_WC()->merchant;
	// echo PIQ_MID;
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

add_action('wp_ajax_ACTION_NAME', 'handlePiqCheckoutTxStatusNotification');
add_action( 'wp_ajax_nopriv_ACTION_NAME', 'handlePiqCheckoutTxStatusNotification' );
function handlePiqCheckoutTxStatusNotification() {
	check_ajax_referer('piq_tx_status_update_nonce');
	$status	= isset($_POST['status'])?trim($_POST['status']):"";
	$orderId	= isset($_POST['orderId'])?trim($_POST['orderId']):"";

	$args = array (
    'status' => $status,
    'orderId' => $orderId, // max posts
	);
	
	do_action('piq_co_handle_transaction_status_update', $args);

	$response	= array();
	$response['message']	= "Successfull Request";
	echo json_encode($response);
}