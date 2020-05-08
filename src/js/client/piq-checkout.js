import { helloWorld } from './../utils'
import _PaymentIQCheckout from 'paymentiq-cashier-bootstrapper'

window.addEventListener('load', function () {
  helloWorld()

});

window.addEventListener('message', function (e) {
  console.log(e.data)
  if (e.data && e.data.eventType) {
    const { eventType, payload } = e.data
    switch (eventType) {
      case '::wooCommerceSetupPIQCheckout':
        console.log(payload)
        return setupCheckout(payload)
      default: 
        return
    }
  }
})

window.addEventListener('setupPIQCheckout', function (e) {
})

function setupCheckout (payload) {
  const lookupConfig = {
    source: 'mock',
    country: 'sweden',
    //identifyFields: ['email', 'zip']
    identifyProvider: 'bankId',
    environment: 'production'
  }
  const config = {
    "environment": "test",
    "userId": "PayTestSE",
    "amount": "499",
    "showAccounts": "inline",
    "globalSubmit": true,
    "showListHeaders": true,
    "showAccounts": "false",
    "mode": "ecommerce",
    "locale": "sv_SE",
    "font": 'custom,santander,santander',
    "containerHeight": 'auto',
    lookupConfig: {
      ...lookupConfig
    },
    "theme": {
      "buttons": {
        "color": "#eb0000"
      }
    },
    ...payload
  }
  new _PaymentIQCheckout('#piq-checkout', config,
  (api) => {
    api.on({
      cashierInitLoad: () => console.log('Cashier init load'),
      update: data => console.log('The passed in data was set', data),
      success: data => notifyOrderStatus('success', data),
      failure: data => console.log('Transaction failed', data),
      isLoading: data => console.log('Data is loading', data),
      doneLoading: data => console.log('Data has been successfully downloaded', data),
      newProviderWindow: data => console.log('A new window / iframe has opened', data),
      paymentMethodSelect: data => console.log('Payment method was selected', data),
      paymentMethodPageEntered: data => console.log('New payment method page was opened', data),
      navigate: data => console.log('Path navigation triggered', data)
    });
  }
)  
}

function notifyOrderStatus (status, data) {
  let payload = {}
  switch (status) {
    case 'success':
      payload = {
        eventType: '::wooCommercePaymentSuccess'
      }
      break
    default:
      return
  }
  // window.postMessage(payload, '*')
}
