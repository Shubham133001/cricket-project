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
  pageDetails: {
    bannertitle: '<h1 class="text-h4 text-sm-h3 text-md-h3 text-lg-h2">Digitize your sports venue with<br /><span class="primary--text ">Smarters Booking Management</span></h1>',
    bannerimage: '/images/cricket.png',
    bannerbtntext: '',
    bannerbtntext1: '',
    bannerbtnlink: '',
    bannerbtnlink1: '',
    calltotitle: '',
    calltobutton: 'Contact Sales',
    abouteTitle: '',
    aboutexcerpts: '<p >We are a team of talented professionals who are passionate about</p>',
    whyusimage: '',
    features: '',
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
