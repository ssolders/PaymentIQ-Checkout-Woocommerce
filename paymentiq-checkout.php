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

// add_action( 'plugins_loaded', 'initPIQCheckout', 0 );

function initPIQCheckout () {
  // bow out early if we don't have WooCommerce yet

  if ( ! class_exists( 'WC_Payment_Gateway' ) || class_exists( 'PIQCheckoutWoocommerce' ) ) {
    return;
  }

  /*  Initialize PaymentIQ Checkout and extend it with WC_Payment_Gateway
      After init, call the register function which is turn calls the Init class.
      Check if our Init class exists (/Inc/Init.php)
      If it does -> Start it up
  */

  include_once PIQ_WC_PLUGIN_PATH . '/inc/Utils.php';

  class PIQCheckoutWoocommerce extends WC_Payment_Gateway {

    /**
		 * The instance of this class
		 *
		 * @var $instance
		 */
		protected static $instance;

    public function __construct () {
      $this->id = 'paymentiq-checkout';
      $this->method_title = 'PaymentIQ Checkout';
      $this->method_description = 'PaymentIQ Checkout allows safe and simple online payments in your shop';
      $this->icon = WP_PLUGIN_URL . '/' . plugin_basename( dirname( __FILE__ ) ) . '/piq.png';
      $this->has_fields = false;
      $this->PIQ_MID = $this->get_option( 'merchant' );
      $this->PIQ_TOTAL_AMOUNT = null;
      $this->PIQ_ORDER_ID = null;

      /* Register base stuff - enque scripts and styles */
      $this->register();
      
      $this->supports = Inc\Base\WooCommercePIQCheckoutSetup::registerSupports();

      $this->form_fields = Inc\Base\WooCommercePIQCheckoutSetup::registerFormFields();

      // Load the settings.!
      $this->init_settings();

      // Initilize PaymentIQ Settings
      $this->initCheckoutSettings();

      $this->init();
    }

    public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}
    
    
    public function register () {
      if ( class_exists( 'Inc\\Init' ) ) {
        Inc\Init::registerServices();
      }
    }

    public function init () {
      $this->initHooks();
      add_filter( 'woocommerce_payment_gateways', array( $this, 'addPaymentIQCheckout' ) );
      add_filter( 'wc_get_template', array( $this, 'overrideTemplate' ), 999, 2 );
    }

    public function addPaymentIQCheckout ( $methods ) {
      $methods[] = 'PIQCheckoutWoocommerce'; // name of the main class
        return $methods;
    }

    /*
      WooCommerce hook for when their templates are rendered. Here we can override
      their with ours - so in our case we render our checkout instead of their KYC form.
    
      @param string $template      Template.
	    @param string $template_name Template name.
	    @return string
    */
    public function overrideTemplate( $template, $template_name ) {
      // $piqCheckoutTemplate = require_once( "./templates/Admin/settings.php" );
      switch ($template_name) {
        case 'checkout/payment-method.php':
          return '';
        case 'checkout/form-checkout.php':
          $template = PIQ_WC_PLUGIN_PATH . '/templates/Checkout/paymentiq-checkout.php';
          return $template;
        default:
          return $template;
      }
    }

    public function initCheckoutSettings () {
      // Define user set variables!
      $this->enabled = array_key_exists( 'enabled', $this->settings ) ? $this->settings['enabled'] : 'yes';
      $this->title = array_key_exists( 'title', $this->settings ) ? $this->settings['title'] : 'PaymentIQ Checkout';
      $this->description = array_key_exists( 'description', $this->settings ) ? $this->settings['description'] : 'Pay using PaymentIQ Checkout';
      $this->merchant = array_key_exists( 'merchant', $this->settings ) ? $this->settings['merchant'] : '';
      $this->accesstoken = array_key_exists( 'accesstoken', $this->settings ) ? $this->settings['accesstoken'] : '';
    }

    public function initHooks() {
      // Actions!
      add_action('woocommerce_checkout_fields', array( $this, 'disable_billing_shipping' ) );
      add_action( 'piq_co_wc_before_checkout_form', 'woocommerce_checkout_login_form', 10 );

      add_action('woocommerce_checkout_order_processed', array( $this, 'checkout_order_process_init' ) );
     
      /* When we receive a txStatus from PIQ Checkout */
      add_action('piq_co_handle_transaction_status_update', array( $this, 'handleTransactionStatusUpdate' ) );

      if( is_admin() ) {
        /* Saves changes when editing in PIQ Checkout admin (WooCommerce->Settings->Payments->PaymentIQ Checkout)  */
        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
      }
    }

    function disable_billing_shipping( $fields ){
      $fields[ 'billing' ] = array();
      $fields[ 'shipping' ] = array();
      return $fields;
    }

    public function handleTransactionStatusUpdate ( $args ) {
      ob_clean();
      ob_start();

      $status = $args['status'];
      $order_id = $args['orderId'];

      $payment_methods = WC()->payment_gateways->get_available_payment_gateways();
      $result = $payment_methods[ 'paymentiq-checkout' ]->process_payment( $order_id );
      $resultUrl = $result['redirect'];
      $currentOrder = $order = new WC_Order( $order_id );
    }

    function process_payment( $order_id ) {
      global $woocommerce;
      $order = new WC_Order( $order_id );
      
      $orderUrl = $order->get_checkout_order_received_url();
      $woocommerce->cart->empty_cart();

      return array(
          'result' => 'success',
          'redirect' => $orderUrl
      );
    }
  
  } /* End of class  */

  /* Create instance of our plugin */
  PIQCheckoutWoocommerce::get_instance();

} /* End of initPIQCheckout */

function PIQ_CHECKOUT_WC() { // phpcs:ignore
	return PIQCheckoutWoocommerce::get_instance();
}
