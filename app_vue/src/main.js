require('./app_config/require')
import Vue from 'vue'
import router from './app_config/router-init'
import store from './store'
import App from './App.vue'

import ElementUI from 'element-ui'
Vue.use(ElementUI)
Vue.directive('focus', {
    // 当绑定元素插入到 DOM 中。 每次update 调用，由于在table_model中定位v-focus 在一个v-for循环中所以forcus会被执行多次，暂时没想到好的办法解决
    inserted: function (el,binding) {
        // 聚焦元素
        var currect_select = binding.value

        if(currect_select){
            $(el).parent().find('input[name='+currect_select+']').focus()

        }

    }
})
new Vue({
  router,
  store,
  data: {
    eventHub: new Vue()
  },
    // el: '#app',
  render: h => h(App)
}).$mount('#app')
