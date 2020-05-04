<div class='wrap'>
  <h1>PaymentIQ Checkout Settings</h1>

  <?php settings_errors(); ?>

  <form method="post" action="options.php">
    <?php
      settings_fields( 'piq_checkout_general_options_group' );
      do_settings_sections( 'PIQCheckoutWoocommerce-plugin' );
      submit_button();
    ?>
  </form>
</div>

