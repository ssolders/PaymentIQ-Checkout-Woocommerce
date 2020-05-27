<?php

/**
 * @package PaymentIQ Checkout Plugin for Woocommerce
 * 
 * Adds settings input fields to the admin page of PaymentIQ Checkout
 * 
 * Using global variabels:
 * @plugin -> Name reference to our plugin (See BaseController.php)
 */

namespace Inc\Base;

class WooCommercePIQCheckoutSetup {
  static function registerSupports () {
    return array(
        'products',
        'refunds',
        'subscriptions',
        'subscription_cancellation',
        'subscription_suspension',
        'subscription_reactivation',
        'subscription_amount_changes',
        'subscription_date_changes',
        'subscription_payment_method_change_customer',
        'multiple_subscriptions'
      );
  }
  
  static function registerFormFields () {
    return array(
      'enabled' => array(
          'title' => 'Activate module',
          'type' => 'checkbox',
          'label' => 'Enable PaymentIQ Checkout as a payment option.',
          'default' => 'yes'
      ),
      'title' => array(
          'title' => 'Title',
          'type' => 'text',
          'description' => 'The title of the payment method displayed to the customers.',
          'default' => 'PaymentIQ Checkout'
      ),
      'description' => array(
          'title' => 'Description',
          'type' => 'textarea',
          'description' => 'The description of the payment method displayed to the customers.',
          'default' => 'Pay using PaymentIQ Checkout'
      ),
      'merchant' => array(
          'title' => 'Merchant ID',
          'type' => 'text',
          'description' => 'The id identifying your PaymentIQ merchant account.',
          'default' => ''
      ),
      // 'md5key' => array(
      //     'title' => 'MD5 Key',
      //     'type' => 'text',
      //     'description' => 'The MD5 key is used to stamp data sent between WooCommerce and Bambora to prevent it from being tampered with. The MD5 key is optional but if used here, must be the same as in the Bambora administration.',
      //     'default' => ''
      // ),
    //   'paymentwindowid' => array(
    //       'title' => 'Payment Window ID',
    //       'type' => 'text',
    //       'description' => 'The ID of the payment window to use.',
    //       'default' => '1'
    //   ),
    //   'instantcapture' => array(
    //       'title' => 'Instant capture',
    //       'type' => 'checkbox',
    //       'description' => 'Capture the payments at the same time they are authorized. In some countries, this is only permitted if the consumer receives the products right away Ex. digital products.',
    //       'label' => 'Enable Instant Capture',
    //       'default' => 'no'
    //   ),
    //   'addsurchargetoshipment' => array(
    //       'title' => 'Add Surcharge',
    //       'type' => 'checkbox',
    //       'description' => 'Display surcharge amount on the order as an item',
    //       'label' => 'Enable Surcharge',
    //       'default' => 'no'
    //   ),
    //   'captureonstatuscomplete' => array(
    //       'title' => 'Capture on status Completed',
    //       'type' => 'checkbox',
    //       'description' => 'When this is enabled the full payment will be captured when the order status changes to Completed',
    //       'default' => 'no'
    //   )
      // 'roundingmode' => array(
      //     'title' => 'Rounding mode',
      //     'type' => 'select',
      //     'description' => 'Please select how you want the rounding of the amount sendt to the payment system',
      //     'options' => array( Bambora_Online_Checkout_Currency::ROUND_DEFAULT => 'Default', Bambora_Online_Checkout_Currency::ROUND_UP => 'Always up', Bambora_Online_Checkout_Currency::ROUND_DOWN => 'Always down' ),
      //     'label' => 'Rounding mode',
      //     'default' => 'normal',
      // )
    );
  }

}