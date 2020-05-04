<?php

/**
* @package PaymentIQ Checkout Plugin for Woocommerce
*/

/*  Enqueue means register asset files (e.g css, js) to be imported in the browser
    Wordpress splits these into the admin and the actual plugin assets.
    Basically, if you want to add a new javascript or css file, you need to add it here in order
    for it to show up as a loaded asset.
*/

 namespace Inc\Base;

 use \Inc\Base\BaseController;

 class Enqueue extends BaseController {
  function __construct() {}

  public function register() {
    add_action( 'admin_enqueue_scripts', array( $this, 'enqueueAdminAssets') );
    add_action( 'wp_enqueue_scripts', array( $this, 'enqueueAssets') );    
  }

  public function enqueueAdminAssets () {
    wp_enqueue_style( 'piqCheckoutAdminStyle', $this->plugin_url . '/wp-content/plugins/paymentiq-checkout/assets/styles/piq-checkout-admin-styles.css' );
    wp_enqueue_script( 'piqCheckoutAdminScript', $this->plugin_url . '/wp-content/plugins/paymentiq-checkout/assets/scripts/piq-checkout-admin.js' );
  }
  
  function enqueueAssets () {
    wp_enqueue_style( 'piqCheckoutScript', $this->plugin_url . '/wp-content/plugins/paymentiq-checkout/assets/styles/piq-checkout-styles.css' );
    wp_enqueue_script( 'piqCheckoutScript', $this->plugin_url . '/wp-content/plugins/paymentiq-checkout/assets/scripts/piq-checkout-scripts.js' );
  }
 }