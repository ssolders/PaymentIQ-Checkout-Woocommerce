import { helloWorld } from './../utils'
import _PaymentIQCheckout from 'paymentiq-cashier-bootstrapper'

window.addEventListener('load', function () {
  helloWorld()

});

window.addEventListener('message', function (e) {
  if (e.data && e.data.eventType) {
    const eventType = e.data.eventType
    if (eventType === 'APP_SET_HEIGHT') {
      // document.getElementById('cashierIframe').style.height = e.data.payload.height + 'px'
    }
  }
})

window.addEventListener('setupPIQCheckout', function (e) {
  const lookupConfig = {
    source: 'mock',
    country: 'sweden',
    //identifyFields: ['email', 'zip']
    identifyProvider: 'bankId',
    environment: 'production'
  }
  new _PaymentIQCheckout('#piq-checkout',
  {
    "environment": "development",
    "userId": "PayTestSE",
    "merchantId": '1014',
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
    }
  },
  (api) => {
    api.on({
      cashierInitLoad: () => console.log('Cashier init load'),
      update: data => console.log('The passed in data was set', data),
      success: data => console.log('Transaction was completed successfully', data),
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
});