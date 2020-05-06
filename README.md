# PaymentIQ-Checkout-Woocommerce

Plugin built using the docker [plugin-development-docker](https://github.com/Yoast/plugin-development-docker)

Clone the plugin [plugin-development-docker](https://github.com/Yoast/plugin-development-docker) and follow the setup guide:

1) cd to directory
2) run: `bash make.sh`
3) run: `bash start.sh woocommerce-wordpress`

If the console repeats `Restarting docker now to fix out-of-sync hardware clock!`

Restart docker and the run the setup (1,2,3) again.

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
7) npm install
) npm start // starts hot-reload watch on the JS-files
7) Reload http://woocommerce.wordpress.test/admin
8) Login using admin+admin. Go to plugins
9) In the list of plugins you should now see PaymentIQ Checkout Woocommerce
10) Click WooCommerce -> Settings -> Payments -> PaymentIQ Checkout
11) Set your merchantId and click save
12) Go to the the site and add an item to your cart -> checkout
13) PaymentIQ Checkout should now show

Test credentials for Checkout lookup:

NationalId: 0000000000
