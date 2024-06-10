import configs from '../../configs'
import actions from './actions'
import mutations from './mutations'

const { product, time, theme, currencies } = configs

const { globalTheme, menuTheme, toolbarTheme, isToolbarDetached, isContentBoxed, isRTL, storeDetails, smtpDetails, rzkey, smsDetails, isUserlogin } = theme
const { currency, availableCurrencies } = currencies

// state initial values
const state = {
  product,

  time,


  // currency
  currency,
  availableCurrencies,
  isUserlogin: false,
  // themes and layout configurations
  globalTheme,
  menuTheme,
  toolbarTheme,
  isToolbarDetached,
  isContentBoxed,
  isRTL,
  storeDetails: {
    name: '',
    address: '',
    phone: '',
    email: '',
    logo: '',
  },
  
  smtpDetails: {
    host: '',
    port: '',
    username: '',
    password: '',
    encryption: '',
  },
  smsDetails: {
    url: "",
    apikey: "",
    senderid: "",
    route: "1",
    country: "91",
  },

  rzkey: 'rzp_test_7si6oRAVVuk2vM',

  // App.vue main toast
  toast: {
    show: false,
    color: 'black',
    message: '',
    timeout: 3000
  }
}

export default {
  namespaced: true,
  state,
  actions,
  mutations,
}
