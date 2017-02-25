
require('./app_config/require')
// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
// import promise from 'es6-promise'
import Vue from 'vue'
import router from './app_config/router-init'
import store from './vuex/store'
import App from './App.vue'
import '../theme/index.css'
import ElementUI from 'element-ui'
Vue.use(ElementUI)
// import iView from 'iview';
// import 'iview/dist/styles/iview.css';
//
// Vue.use(iView);

// import VueWebsocket from 'vue-websocket';
// Vue.use(VueWebsocket, 'ws://127.0.0.1:9501', {
//     reconnection: false
// });

new Vue({
  router,
  store,
  // el: '#app',
  render: h => h(App)
}).$mount('#app')



