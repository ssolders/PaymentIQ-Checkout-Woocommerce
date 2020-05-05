<?php

/**
 * @package PaymentIQ Checkout Plugin for Woocommerce
 * 
 * Using global variabels (defined in BaseController)
 * @plugin_path -> Base path of the plugin (root folder)
 */

namespace Inc\Pages;

use \Inc\Api\SettingsApi;
use \Inc\Base\BaseController;
use \Inc\Api\Callbacks\AdminCallbacks;

class Admin extends BaseController {
  
  public $settings;
  
  public $callbacks;

  public $pages;

  public $subPages;

  public $piqCheckoutSlug = 'PIQCheckoutWoocommerce-plugin';

  public function register() {
    return;
    $this->settings = new SettingsApi();
    
    $this->callbacks = new AdminCallbacks();

    $this->setPages();
    $this->setSubPages();

    $this->setSettings();  
    $this->setSections();  
    $this->setFields();  

    $this->settings->addPages( $this->pages )->withSubPage( 'Settings' )->addSubPages( $this->subPages ) ->register();
  }

  public function setPages () {
    $this->pages = [
      [
        'page_title' => 'PIQCheckout',
        'menu_title' => 'PIQ Checkout',
        'capability' => 'manage_options',
        'menu_slug' => $this->piqCheckoutSlug,
        'callback' => array( $this->callbacks, 'adminSettings' ),
        'icon_url' => 'dashicons-admin-settings',
        'position' => 110
      ]
    ];
  }
  
  public function setSubPages () {
    $this->subPages = [
      [
        'parent_slug' => $this->piqCheckoutSlug,
        'page_title' => 'Other',
        'menu_title' => 'Other',
        'capability' => 'manage_options',
        'menu_slug' => 'PIQCheckout-plugin-settings',
        'callback' => array( $this->callbacks, 'adminOther' )
      ]
      // [
      //   'parent_slug' => $this->piqCheckoutSlug,
      //   'page_title' => 'Other',
      //   'menu_title' => 'Other',
      //   'capability' => 'manage_options',
      //   'menu_slug' => 'PIQCheckout-plugin-other',
      //   'callback' => function () { echo '<h1>Other</h1>'; }
      // ]
    ];
  }

  public function setSettings () {
    $args = array(
      array(
        'option_group' => 'piq_checkout_general_options_group',
        'option_name' => 'merchant_id'
      ),
      array(
        'option_group' => 'piq_checkout_general_options_group',
        'option_name' => 'first_name'
      )
    );

    $this->settings->setSettings( $args );
  }
  
  public function setSections () {
    $args = array(
      array(
        'id' => 'piq_checkout_admin_index',
        'title' => 'Settings',
        'callback' => array( $this->callbacks, 'piqCheckoutAdminSection' ),
        'page' => $this->piqCheckoutSlug
      )
    );

    $this->settings->setSections( $args );
  }
  
  public function setFields () {
    $args = array(
      array(
        'id' => 'merchant_id',
        'title' => 'PaymentIQ Merchant ID',
        'callback' => array( $this->callbacks, 'piqCheckoutMerchantId' ),
        'page' => $this->piqCheckoutSlug,
        'section' => 'piq_checkout_admin_index',
        'args' => array(
          'label_for' => 'merchant_id',
          'class' => 'example-class'
        )
      ),
      array(
        'id' => 'first_name',
        'title' => 'First Name',
        'callback' => array( $this->callbacks, 'piqCheckoutFirstName' ),
        'page' => $this->piqCheckoutSlug,
        'section' => 'piq_checkout_admin_index',
        'args' => array(
          'label_for' => 'first_name',
          'class' => 'example-class'
        )
      )
    );

    $this->settings->setFields( $args );
  }
}