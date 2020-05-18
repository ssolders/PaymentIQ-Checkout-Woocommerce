<?php
/**
 * PaymentIQ Checkout page
 *
 * Overrides /checkout/form-checkout.php.
 *
 * @package paymentiq-checkout
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

include_once PIQ_WC_PLUGIN_PATH . '/inc/Utils.php';

wc_print_notices();

do_action( 'piq_co_wc_before_checkout_form' );

// Setup when we have the necessary things (merchantId, orderId and amount)
// if ( ! shouldSetupCheckout ()) {
// 	return;
// }

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
		<?php piq_wc_show_checkout(); ?>
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
				orderId: <?php getOrderId(); ?>,
				attributes: {
					orderId: <?php getOrderId(); ?>
				}
			}
		}, '*')

		/* We need to create an action hook to get back to the php code (out of our template/script file)
			 When we receive the status update of the transaction via postMessage - we make an ajax request,
			 wordpress' way of triggering an action hook via request.			 
		*/

		window.addEventListener('message', function (e) {
			if (e.data && e.data.eventType) {
				const { eventType, payload } = e.data
				switch (eventType) {
					case '::wooCommercePaymentSuccess':
						try {
							var data = {
								action: 'ACTION_NAME',
								_ajax_nonce: '<?php echo wp_create_nonce( 'piq_tx_status_update_nonce' ); ?>', /* wordpress way of determining validity of ajax-request from plugin */
								status: 'success',
								orderId: payload.orderId,
								...payload.data
							};
						
							/* Specific url required in order to trigger an action hooks:
								wp_ajax_{ACTION_NAME}
								wp_ajax_nopriv_{ACTION_NAME}
								in /Inc/Utils.php
							*/
							var url = '<?php echo admin_url('admin-ajax.php'); ?>';
							jQuery.post(url, data, function(response) {
									console.log('jquery callback')
							});
						} catch (err) {
							console.error(err)
							console.log('PIQCheckout: Unable to trigger ajax action hook')
						}
						break
					default:
						return
				}	
			}
		})
  </script>
</form>
