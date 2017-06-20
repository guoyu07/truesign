require('./app_config/require')
import Vue from 'vue'
import router from './app_config/router-init'
import store from './store'
import App from './App.vue'
import MintUI from 'mint-ui'
import 'mint-ui/lib/style.css'
Vue.use(MintUI)
import ElementUI from 'element-ui'
Vue.use(ElementUI)
// import VueValidator from 'vue-validator'
// Vue.use(VueValidator)
import VeeValidate, { Validator } from 'vee-validate';
import zh_CN from 'vee-validate/dist/locale/zh_CN';
// import ar from 'vee-validate/dist/locale/ar';
Validator.addLocale(zh_CN);
const config_validate = {
    errorBagName: 'errors', // change if property conflicts.
    fieldsBagName: 'fields',
    delay: 0,
    locale: 'zh_CN',
    dictionary: {

    },
    strict: true,
    enableAutoClasses: false,
    classNames: {
        touched: 'touched', // the control has been blurred
        untouched: 'untouched', // the control hasn't been blurred
        valid: 'valid', // model is valid
        invalid: 'invalid', // model is invalid
        pristine: 'pristine', // control has not been interacted with
        dirty: 'dirty' // control has been interacted with
    },
    events: 'input|blur',
    inject: true
};
Vue.use(VeeValidate,config_validate);
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
// new Vue({
//   router,
//   store,
//   data: {
//     eventHub: new Vue()
//   },
//     // el: '#app',
//   render: h => h(App)
// }).$mount('#app')
var vue_init = new Vue({
      router,
      store,
      data: {
        eventHub: new Vue()
      },
    render: h => h(App)
}).$mount('#app')