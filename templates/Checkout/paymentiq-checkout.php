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

// Setup when we have the necessary things (merchantId, orderId and amount)
if ( ! shouldSetupCheckout ()) {
	return;
}

/*<?php piq_wc_show_checkout(); ?>*/

// If checkout registration is disabled and not logged in, the user cannot checkout.
// if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
// 	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
// 	return;
// }
/* <?php do_action( 'piq_co_update_order_status', 'success' ); ?> */
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
			eventType: '::wooCommerceSetupPIQCheckout',
			payload: {
				merchantId: <?php getPiqMerchantId(); ?>,
				amount: <?php getPiqTotalAmount(); ?>,
				orderId: <?php getOrderId(); ?>
			}
		}, '*')

		window.addEventListener('message', function (e) {
			if (e.data && e.data.eventType) {
				const { eventType, payload } = e.data
				switch (eventType) {
					case '::wooCommercePaymentSuccess':
						console.log('GOT SUCCESS MESSAGE')
						break
					default:
						return
				}	
			}
		})
  </script>
</form>
