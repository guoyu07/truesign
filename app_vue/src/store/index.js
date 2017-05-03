import Vue from 'vue'
import Vuex from 'vuex'
import mutations from './mutations'
import actions from './actions'
import getters from './getters'
import LocalVoucher from '../api/localVoucherTools.js'
LocalVoucher.checkStorageMode()
LocalVoucher.initEngine()
Vue.use(Vuex);

const state = {
    EventFactory:{
        init_socket_send_factory:[],
        socket_send_factory:[]
    },
    WebSite: {
        unique_auth_code: LocalVoucher.getValue('WebSite.unique_auth_code'),
        encryption_key:LocalVoucher.getValue('WebSite.encryption_key'),
        website_encryption_key:LocalVoucher.getValue('WebSite.website_encryption_key'),
        conn_status:0,
        socket_id:0,
        apprules:[],
        access_user:(LocalVoucher.getValue('WebSite.access_user'))?(JSON.parse(LocalVoucher.getValue('WebSite.access_user'))):'',
        isbindapps:(LocalVoucher.getValue('WebSite.isbindapps'))?(JSON.parse(LocalVoucher.getValue('WebSite.isbindapps'))):'',
        website_user:(LocalVoucher.getValue('WebSite.website_user'))?(JSON.parse(LocalVoucher.getValue('WebSite.website_user'))):'',
        website_level:1,
    },
    AppRules:[],
    SysInfo:{
        ip:'0.0.0.0'
    },

}

export default new Vuex.Store({
    state,
    mutations,
    getters,
    actions
})
