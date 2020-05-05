import { helloWorld } from './../utils'
import _PaymentIQCheckout from 'paymentiq-cashier-bootstrapper'

window.addEventListener('load', function () {
  helloWorld()

});

window.addEventListener('message', function (e) {
  if (e.data && e.data.eventType) {
    console.log(e.data);
    console.log(e.data.payload);
  }
})

window.addEventListener('setupPIQCheckout', function (e) {
  new _PaymentIQCheckout('#piq-checkout',
  {
    merchantId: '1014',
    userId: 'PayTestSE',
    amount: 1500,
    lookupConfig: {
      source: 'mock',
      country: 'sweden',
      identifyFields: ['email', 'zip']
      // identifyProvider: 'bankId'
    },
    showAccounts: 'inline',
    mode: 'ecommerce',
    showListHeaders: true,
    globalSubmit: 'true',
    font: 'custom, santander, santander',
    sessionId: '66',
    environment: 'test', // if not set, defaults to production
    method: 'deposit' // if not set, defaults to deposit
  },
  (api) => {
     console.log('Cashier intialized and ready to take down the empire')
  }
)
});