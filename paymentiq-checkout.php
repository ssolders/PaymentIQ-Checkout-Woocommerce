<?php

/**
 * @package PaymentIQ Checkout Plugin for Woocommerce
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
use Inc\Base\Activate;
use Inc\Base\Deactivate;
function activatePIQCheckout () {
  Activate::activate(); // inc/Base/Activate.php
}
function deactivatePIQCheckout () {
  Deactivate::deactivate(); // inc/Base/Deactivate.php
}

register_activation_hook( __FILE__, 'activatePIQCheckout' );
register_deactivation_hook( __FILE__, 'deactivatePIQCheckout' );

/* Hook for when plugins have loaded -> Our way of knowing when to kick things of
*/
add_action( 'plugins_loaded', 'initPIQCheckout', 0 );

function initPIQCheckout () {
  /*  REGISTER GLOBAL VARIABLES
  */
  /*  Global variable for keeping track of the root folder when importing classes */
  define ( 'PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
  /*  Global variable for keeping track of the baseUrl of the plugin when importing assets */
  define ( 'PLUGIN_URL', plugin_dir_url( __FILE__ ) );
  /*  Global variable for keeping track of the plugin name (wordpress plugins' name is unique and is used for referencing this unique plugin ) */
  define ( 'PLUGIN', plugin_basename( __FILE__ ) );


  /*  Initialize PaymentIQ Checkout and extend it with WC_Payment_Gateway
      After init, call the register function which is turn calls the Init class.
      Check if our Init class exists (/Inc/Init.php)
      If it does -> Start it up
  */

  class PIQCheckoutWoocommerce extends WC_Payment_Gateway {
    function register () {
      if ( class_exists( 'Inc\\Init' ) ) {
        Inc\Init::registerServices();
      }
    }
  }

  /* Create a new instance of PIQCheckoutWoocommerce and then trigger its register function  */
  if( class_exists( 'PIQCheckoutWoocommerce' ) ) {
    $piqCheckoutWoocommerce = new PIQCheckoutWoocommerce();
    $piqCheckoutWoocommerce->register();
  }
}






