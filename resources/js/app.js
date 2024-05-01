import './bootstrap'
import Vue from 'vue'
import App from './App.vue'

// VUEX - https://vuex.vuejs.org/
import store from './store'

// VUE-ROUTER - https://router.vuejs.org/
import router from './router'

// PLUGINS
import vuetify from './plugins/vuetify'
import i18n from './plugins/vue-i18n'
import './plugins/vue-google-maps'
import './plugins/vue-shortkey'
import './plugins/vue-head'
import './plugins/vue-gtag'
import './plugins/apexcharts'
import './plugins/echarts'
import './plugins/animate'
import './plugins/clipboard'
import './plugins/moment'


// FILTERS
import './filters/capitalize'
import './filters/lowercase'
import './filters/uppercase'
import './filters/formatCurrency'
import './filters/formatDate'
import './filters/strip'
import './filters/formatTime'

// STYLES
// Main Theme SCSS
import '../sass/theme.scss'

// Animation library - https://animate.style/
import 'animate.css/animate.min.css'

// Set this to false to prevent the production tip on Vue startup.

import Toasted from 'vue-toasted';
Vue.use(Toasted, {
  iconPack: 'fontawesome',
  duration: 10000,
  position: 'top-right',
  theme: 'bubble'
});

Vue.config.productionTip = false

/*
|---------------------------------------------------------------------
| Main Vue Instance
|---------------------------------------------------------------------
|
| Render the vue application on the <div id="app"></div> in index.html
|
| https://vuejs.org/v2/guide/instance.html
|
*/


export default new Vue({
  i18n,
  vuetify,
  router,
  store,
  created() {

    axios.interceptors.request.use(config => {
      const isadminval = this.$router.currentRoute.path.includes('/admin/');
      // console.log(isadminval);
      if (isadminval == true && localStorage.adminData != undefined) {
        // console.log(JSON.parse(atob(localStorage.adminData)));
        config.headers.Authorization = 'Bearer ' + JSON.parse(atob(localStorage.adminData)).access_token;
      }
      else if (localStorage.userdetails != undefined) {
        config.headers.Authorization = 'Bearer ' + localStorage.token;
      }
      return config;
    }, error => {

      return Promise.reject(error);
    });


    axios.interceptors.response.use(response => {
      return response;
    },
      error => {
        console.log("error.response.status", error);
        const isadminval = this.$router.currentRoute.path.includes('/admin/');

        if (error.response.status === 401) {
          if (error.response.status === 401) {
            if (isadminval == true) {
              // console.log("we have to work here to remove admin data");
              localStorage.removeItem('adminData');
              this.$router.replace({ name: 'admin-login' })
            }
            else {
              localStorage.removeItem('userdetails');
              localStorage.removeItem('token');
              this.$router.replace({ name: 'home' })
            }
          }
        }
        return Promise.reject(error)
      }
    );
  },
  render: (h) => h(App)
}).$mount('#app')
