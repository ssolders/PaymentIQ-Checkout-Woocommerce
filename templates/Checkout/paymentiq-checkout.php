<?php
/**
 * PaymentIQ Checkout page
 *
 * Overrides /checkout/form-checkout.php.
 *
 * @package paymentiq-checkout
 */

include_once PIQ_WC_PLUGIN_PATH . '/inc/Utils.php';

wc_print_notices();

do_action( 'piq_co_wc_before_checkout_form' );

if ( ! shouldSetupCheckout ()) {
	return;
}

// If checkout registration is disabled and not logged in, the user cannot checkout.
// if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
// 	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
// 	return;
// }
?>

<form name="checkout" class="checkout woocommerce-checkout">
	<div id="piq-checkout-wrapper">
		<div id="piq-checkout"></div>
		<?php woocommerce_order_review(); ?>
	</div>
  <script>
		// We let the javascript know that it's time to setup the checkout
		// We pass along the configured settings in payload
		window.postMessage({
			eventType: 'setupPIQCheckout',
			payload: {
				merchantId: <?php piqCheckoutMerchantId(); ?>,
				amount: <?php piqCheckoutTotalAmount(); ?>,
				orderId: <?php piqCheckoutOrderId(); ?>
			}
		}, '*')
  </script>
</form>
