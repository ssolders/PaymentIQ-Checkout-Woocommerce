<?php

/**
 * @package PaymentIQ Checkout Plugin for Woocommerce
 * 
 * Using global variabels (defined in BaseController)
 * @plugin_path -> Base path of the plugin (root folder)
 */

namespace Inc\Pages;

class Admin extends WC_Payment_Gateway {
  
  public function register() {
    $this->supports = array(
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

    // Load the form fields.!
    $this->initFormFields();

    // Load the settings.!
    $this->init_settings();

    // Initilize PaymentIQ Settings
    $this->initCheckoutSettings();

    // Set description for checkout page!
    $this->setPaymentIQDescriptionForCheckout();
  }

  public function initFormFields() {
    $this->form_fields = array(
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
        'paymentwindowid' => array(
            'title' => 'Payment Window ID',
            'type' => 'text',
            'description' => 'The ID of the payment window to use.',
            'default' => '1'
        ),
        'instantcapture' => array(
            'title' => 'Instant capture',
            'type' => 'checkbox',
            'description' => 'Capture the payments at the same time they are authorized. In some countries, this is only permitted if the consumer receives the products right away Ex. digital products.',
            'label' => 'Enable Instant Capture',
            'default' => 'no'
        ),
        'immediateredirecttoaccept' => array(
            'title' => 'Immediate Redirect',
            'type' => 'checkbox',
            'description' => 'Immediately redirect your customer back to you shop after the payment completed.',
            'label' => 'Enable Immediate redirect',
            'default' => 'no'
        ),
        'addsurchargetoshipment' => array(
            'title' => 'Add Surcharge',
            'type' => 'checkbox',
            'description' => 'Display surcharge amount on the order as an item',
            'label' => 'Enable Surcharge',
            'default' => 'no'
        ),
        'captureonstatuscomplete' => array(
            'title' => 'Capture on status Completed',
            'type' => 'checkbox',
            'description' => 'When this is enabled the full payment will be captured when the order status changes to Completed',
            'default' => 'no'
        )
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

  public function initCheckoutSettings () {
    // Define user set variables!
    $this->enabled = array_key_exists( 'enabled', $this->settings ) ? $this->settings['enabled'] : 'yes';
    $this->title = array_key_exists( 'title', $this->settings ) ? $this->settings['title'] : 'PaymentIQ Checkout';
    $this->description = array_key_exists( 'description', $this->settings ) ? $this->settings['description'] : 'Pay using PaymentIQ Checkout';
    $this->merchant = array_key_exists( 'merchant', $this->settings ) ? $this->settings['merchant'] : '';
    $this->accesstoken = array_key_exists( 'accesstoken', $this->settings ) ? $this->settings['accesstoken'] : '';
    $this->secrettoken = array_key_exists( 'secrettoken', $this->settings ) ? $this->settings['secrettoken'] : '';
    $this->paymentwindowid = array_key_exists( 'paymentwindowid', $this->settings ) ? $this->settings['paymentwindowid'] : 1;
    $this->instantcapture = array_key_exists( 'instantcapture', $this->settings ) ? $this->settings['instantcapture'] :  'no';
    $this->immediateredirecttoaccept = array_key_exists( 'immediateredirecttoaccept', $this->settings ) ? $this->settings['immediateredirecttoaccept'] :  'no';
    $this->addsurchargetoshipment = array_key_exists( 'addsurchargetoshipment', $this->settings ) ? $this->settings['addsurchargetoshipment'] :  'no';
    $this->md5key = array_key_exists( 'md5key', $this->settings ) ? $this->settings['md5key'] : '';
    // $this->roundingmode = array_key_exists( 'roundingmode', $this->settings ) ? $this->settings['roundingmode'] : Bambora_Online_Checkout_Currency::ROUND_DEFAULT;
    $this->captureonstatuscomplete = array_key_exists( 'captureonstatuscomplete', $this->settings ) ? $this->settings['captureonstatuscomplete'] : 'no';
  }

}