<?php

/**
* @package PaymentIQ Checkout Plugin for Woocommerce
*/

namespace Inc\Api\Callbacks;

use Inc\Base\BaseController;

class AdminCallbacks extends BaseController {

  public function adminSettings () {
    return require_once( "$this->plugin_path/templates/Admin/settings.php" );
  }

  public function adminOther () {
    return require_once( "$this->plugin_path/templates/Admin/other.php" );
  }
  
  public function piqCheckoutOptionsGroup ( $input ) {
    // If we wanna handle input value before saving
  }
  
  public function piqCheckoutAdminSection ( ) {
    // echo 'Admin section callback';
  }
  
  public function piqCheckoutMerchantId () {
    $value = esc_attr( get_option( 'merchant_id' ) );
    echo '<input type="text" class="regular-text" name="merchant_id" value="' . $value . '" placeholder="PaymentIQ Merchant Id" >';
  }
  
  public function piqCheckoutFirstName () {
    $value = esc_attr( get_option( 'first_name' ) );
    echo '<input type="text" class="regular-text" name="first_name" value="' . $value . '" placeholder="Write your first name" >';
  }
}