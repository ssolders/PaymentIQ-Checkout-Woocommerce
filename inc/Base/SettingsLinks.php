<?php

/**
 * @package PaymentIQ Checkout Plugin for Woocommerce
 * 
 * Adds a custom link(s) to the plugin in the Admin view of wordpress
 * 
 * Using global variabels:
 * @PLUGIN -> Name reference to our plugin
 */



 namespace Inc\Base;

 class SettingsLinks {
    public function register () {
      add_filter( "plugin_action_links_" . PLUGIN, array( $this, 'settingsLink' ) );   
    }

    public function settingsLink ( $links ) {
      $settingsLink = '<a href="admin.php?page=PIQCheckoutWoocommerce-plugin">Settings</a>';
      array_push( $links, $settingsLink );
      return $links;
    }
 }