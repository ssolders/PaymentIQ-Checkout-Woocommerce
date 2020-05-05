<?php

/**
 * @package PaymentIQ Checkout Plugin for Woocommerce
 * 
 * Adds a custom link(s) to the plugin in the Admin view of wordpress
 * 
 * Using global variabels:
 * @plugin -> Name reference to our plugin (See BaseController.php)
 */

namespace Inc\Base;

use \Inc\Base\BaseController;

class SettingsLinks extends BaseController {
  public function register () {
    add_filter( "plugin_action_links_" . $this->plugin, array( $this, 'settingsLink' ) );   
  }

  public function settingsLink ( $links ) {
    $settingsLink = "<a href='admin.php?page=wc-settings&tab=checkout&section=paymentiq-checkout'>Settings</a>";
    array_push( $links, $settingsLink );
    return $links;
  }
}