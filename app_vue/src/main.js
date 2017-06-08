require('./app_config/require')
import Vue from 'vue'
import router from './app_config/router-init'
import store from './store'
import App from './App.vue'

import ElementUI from 'element-ui'
Vue.use(ElementUI)

new Vue({
  router,
  store,
  data: {
    eventHub: new Vue()
  },
    // el: '#app',
  render: h => h(App)
}).$mount('#app')
