<?php

/**
 * @package PaymentIQ Checkout Plugin for Woocommerce
 * 
 * Using global variabels:
 * @PLUGIN_PATH -> Base path of the plugin (root folder)
 */

 namespace Inc\Pages;

 class Admin {
  function __construct() {}

  public function register() {
    add_action( 'admin_menu', array( $this, 'addAdminPage' ) );
  }

  public function addAdminPage () {
    add_menu_page( 'PIQCheckout',  'PIQ Checkout', 'manage_options', 'PIQCheckoutWoocommerce-plugin', array( $this, 'renderSettingsPage' ), 'dashicons-admin-settings', 110 );
  }

  public function renderSettingsPage () {
    require_once PLUGIN_PATH . 'templates/admin/settings.php';
  }
 }