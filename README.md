# PaymentIQ-Checkout-Woocommerce

Plugin built using the docker [plugin-development-docker](https://github.com/Yoast/plugin-development-docker)

Clone the plugin [plugin-development-docker](https://github.com/Yoast/plugin-development-docker) and follow the setup guide:

1) cd to directory
2) run: `bash make.sh`
3) run: `bash start.sh woocommerce-wordpress`

This should trigger a wordpress page with the url http://woocommerce.wordpress.test/ to automatically open.

To in admin mode: http://woocommerce.wordpress.test/admin

* username: admin
* password: admin

## Add PaymentIQ-Checkout-Woocommerce

1) Clone this repo
2) Copy all files in PaymentIQ-Checkout-Woocommerce
3) cd to plugin-development-docker/plugin
4) mkdir paymentiq-checkout
5) cd paymentiq-checkout
6) paste all files
7) Reload http://woocommerce.wordpress.test/admin
8) Go to plugins
9) In the list of plugins you should now see PaymentIQ Checkout Woocommerce
