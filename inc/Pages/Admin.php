<?php

/**
 * @package PaymentIQ Checkout Plugin for Woocommerce
 * 
 * Using global variabels:
 * @PLUGIN_PATH -> Base path of the plugin (root folder)
 */

namespace Inc\Pages;

use \Inc\Base\BaseController;
use \Inc\Api\SettingsApi;

class Admin extends BaseController {
  
  public $settings;

  public function register() {
    // for some reason, we can't use __contruct so we create a new SettingsApi in the register() instead
    $this->settings = new SettingsApi();

    $pages = [
      [
        'page_title' => 'PIQCheckout',
        'menu_title' => 'PIQ Checkout',
        'capability' => 'manage_options',
        'menu_slug' => 'PIQCheckoutWoocommerce-plugin',
        'callback' => function () { echo '<h1>Test plugin header</h1>'; },
        'icon_url' => 'dashicons-admin-settings',
        'position' => 110
      ],
      [
        'page_title' => 'PIQCheckout2',
        'menu_title' => 'PIQ Checkout2',
        'capability' => 'manage_options',
        'menu_slug' => 'PIQCheckoutWoocommerce-plugin',
        'callback' => function () { echo '<h1>Test plugin header2</h1>'; },
        'icon_url' => 'dashicons-admin-settings',
        'position' => 110
      ]
    ];
    $this->settings->addPages( $pages )->register();
  }
}