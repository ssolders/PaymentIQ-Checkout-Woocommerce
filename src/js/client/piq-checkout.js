import { helloWorld } from './../utils'
import _PaymentIQCheckout from 'paymentiq-cashier-bootstrapper'

window.addEventListener('load', function () {
  helloWorld()

});

window.addEventListener('message', function (e) {
  if (e.data && e.data.eventType) {
    const { eventType, payload } = e.data
    switch (eventType) {
      case '::wooCommerceSetupPIQCheckout':
        return setupCheckout(payload)
      default: 
        return
    }
  }
})

window.addEventListener('setupPIQCheckout', function (e) {
})

function setupCheckout (payload) {
  const orderId = payload.attributes.orderId

  const lookupConfig = {
    source: 'mock',
    country: 'sweden',
    //identifyFields: ['email', 'zip']
    identifyProvider: 'bankId',
    environment: 'development'
  }
  const config = {
    "environment": "development",
    "userId": "PayTestSES",
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
      cashierInitLoad: () => {},
      update: data => {},
      success: data => notifyOrderStatus('success', orderId, data),
      failure: data => {},
      isLoading: data => {},
      doneLoading: data => {},
      newProviderWindow: data => {},
      paymentMethodSelect: data => {},
      paymentMethodPageEntered: data => {},
      navigate: data => {}
    });
  }
)  
}

/* We need to give back control to the script in the php-code
   We do this via a postMessage back (templates/Checkout/paymentiq-checkout.php)
*/
function notifyOrderStatus (status, orderId, data) {
  console.log('notifyOrderStatus')
  let payload = {}
  switch (status) {
    case 'success':
      payload = {
        eventType: '::wooCommercePaymentSuccess',
        payload: {
          orderId,
          ...data
        }
      }
      window.location.href = `/checkout/order-received/${orderId}`
      break
    default:
      return
  }
  window.postMessage(payload, '*')
}
