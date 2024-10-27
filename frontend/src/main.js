import Vue from 'vue'
import App from './App.vue'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import axios from 'axios'
import router from './router'
import VueToastr from "vue-toastr"

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
Vue.use(VueToastr)

Vue.config.productionTip = false
Vue.prototype.$http = axios

axios.defaults.baseURL = 'http://localhost:8000/api'

new Vue({
  router,
  render: h => h(App),
}).$mount('#app')
