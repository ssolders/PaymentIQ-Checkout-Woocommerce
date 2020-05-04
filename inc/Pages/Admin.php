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
  public $pages;

  public function __construct() {
    $this->settings = new SettingsApi();

    // Add another array item to add a new admin page
    $this->pages = [
      [
        'page_title' => 'PIQCheckout',
        'menu_title' => 'PIQ Checkout',
        'capability' => 'manage_options',
        'menu_slug' => 'PIQCheckoutWoocommerce-plugin',
        'callback' => function () { echo '<h1>Test plugin header</h1>'; },
        'icon_url' => 'dashicons-admin-settings',
        'position' => 110
      ]
    ];
  }

  public function register() {
    $this->settings->addPages( $this->pages )->register();
  }
}