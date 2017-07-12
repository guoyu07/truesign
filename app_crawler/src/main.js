import Vue from 'vue'
import App from './App'
import store from './store'
require('semantic-ui/dist/semantic.css')
require('semantic-ui/dist/semantic.js')
Vue.config.productionTip = false
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-default/index.css'
Vue.use(ElementUI)
/* eslint-disable no-new */
new Vue({
  store,
  data: {
    eventHub: new Vue()
  },
  el: '#app',
  template: '<App/>',
  components: { App }
})
