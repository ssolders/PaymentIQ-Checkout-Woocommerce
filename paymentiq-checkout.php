<?php

/**
 * @package paymentiq-checkout
 */
 /* 
 Plugin Name: PaymentIQ Checkout Woocommerce
 Plugin URI: https://docs.paymentiq.io/
 Description: PaymentIQ Checkout for Woocommerce
 Version: 1.0.0
 Author: Simon Solders
 Author URI: https://github.com/ssolders
 License: GPLv2 or later
 Text Domain: PIQ Checkout

 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

// If not inside wordpress - die right away
defined( 'ABSPATH' ) or die( 'Hey you can\t access this file, you silly human');

/* Using composer - autoload for simpler importing of classes */
if ( file_exists(  dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
  require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

/* Activate and Deactivation Hook handlers 
   A Wordpress plugin has an activation & deactivation hook - we can call functions/classes to be triggered
   Need to set these up to start with
*/
function activatePIQCheckout () {
  Inc\Base\Activate::activate(); // inc/Base/Activate.php
}
register_activation_hook( __FILE__, 'activatePIQCheckout' );

function deactivatePIQCheckout () {
  Inc\Base\Deactivate::deactivate(); // inc/Base/Deactivate.php
}
register_deactivation_hook( __FILE__, 'deactivatePIQCheckout' );

define( 'PIQ_WC_PLUGIN_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );

/* Hook for when plugins have loaded -> Our way of knowing when to kick things of
*/
/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
  add_action( 'plugins_loaded', 'initPIQCheckout', 0 );
}

function initPIQCheckout () {
  /*  Initialize PaymentIQ Checkout and extend it with WC_Payment_Gateway
      After init, call the register function which is turn calls the Init class.
      Check if our Init class exists (/Inc/Init.php)
      If it does -> Start it up
  */

  class PIQCheckoutWoocommerce extends WC_Payment_Gateway {

    public function __construct () {
      $this->id = 'paymentiq-checkout';
      $this->method_title = 'PaymentIQ Checkout';
      $this->method_description = 'PaymentIQ Checkout allows safe and simple online payments in your shop';
      $this->icon = WP_PLUGIN_URL . '/' . plugin_basename( dirname( __FILE__ ) ) . '/piq.png';
      $this->has_fields = true;
      
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
    
    
    function register () {
      if ( class_exists( 'Inc\\Init' ) ) {
        Inc\Init::registerServices();

        add_filter( 'wc_get_template', array( $this, 'overrideTemplate' ), 999, 2 );
      }
    }

    public function overrideTemplate( $template, $template_name ) {
      // $piqCheckoutTemplate = require_once( "./templates/Admin/settings.php" );
      if ( 'checkout/form-billing.php' === $template_name ) {
        $mid = $this->get_option( 'merchant' );
        $PIQ_MERCHANT_ID = $mid;

        $template = PIQ_WC_PLUGIN_PATH . '/templates/Checkout/paymentiq-checkout.php';
      }
      return $template;
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

    public function initHooks() {
      // Actions!
      // add_action( 'woocommerce_api_' . strtolower( get_class() ), array( $this, 'bambora_online_checkout_callback' ) );

      if( is_admin() ) {
          // add_action( 'add_meta_boxes', array( $this, 'bambora_online_checkout_meta_boxes' ) );
          add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
          // add_action( 'wp_before_admin_bar_render', array( $this, 'bambora_online_checkout_actions' ) );
          // add_action( 'admin_notices', array( $this, 'bambora_online_checkout_admin_notices' ) );
      //     if($this->captureonstatuscomplete === 'yes') {
      //         add_action( 'woocommerce_order_status_completed', array( $this, 'bambora_online_checkout_order_status_completed' ) );
      //     }
      }

      // //Subscriptions
      // add_action('woocommerce_scheduled_subscription_payment_' . $this->id, array($this, 'scheduled_subscription_payment'), 10, 2);
      // add_action('woocommerce_subscription_cancelled_' . $this->id, array($this, 'subscription_cancellation'));

      // // Register styles!
      // add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_wc_bambora_online_checkout_admin_styles_and_scripts' ) );
      // add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_wc_bambora_online_checkout_front_styles' ) );
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

    /**
    * Set the WC Payment Gateway description for the checkout page
    */
    public function setPaymentIQDescriptionForCheckout() {
      global $woocommerce;
      $description = '';
      $this->description .= $description;
    }
  }

  /* Create a new instance of PIQCheckoutWoocommerce and then trigger its register function  */
  if( class_exists( 'PIQCheckoutWoocommerce' ) ) {
    $piqCheckoutWoocommerce = new PIQCheckoutWoocommerce();
    $piqCheckoutWoocommerce->register();
    
    $PIQ_MERCHANTID = 

    add_filter( 'woocommerce_payment_gateways', 'addPaymentIQCheckout' );
    $piqCheckoutWoocommerce->initHooks();
    function addPaymentIQCheckout ( $methods ) {
      $methods[] = 'PIQCheckoutWoocommerce'; // name of the main class
        return $methods;
    }
  }
}






