<?php
/**
 * PaymentIQ Checkout page
 *
 * Overrides /checkout/form-checkout.php.
 *
 * @package paymentiq-checkout
 */

wc_print_notices();

// do_action( 'kco_wc_before_checkout_form' );

// If checkout registration is disabled and not logged in, the user cannot checkout.
// if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
// 	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
// 	return;
// }
?>

<!-- <div id="piq-order-review">
	<?php woocommerce_order_review(); ?>
</div> -->

<form name="checkout" class="checkout woocommerce-checkout">
	<div id="piq-checkout-wrapper">
		<div id="piq-checkout"></div>
	</div>
  <script>
		var event = new Event('setupPIQCheckout');
		window.dispatchEvent(event)
  </script>
</form>
