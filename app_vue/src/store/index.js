import Vue from 'vue'
import Vuex from 'vuex'
import mutations from './mutations'
import actions from './actions'
import getters from './getters'
import LocalVoucher from '../api/localVoucherTools.js'
LocalVoucher.checkStorageMode()
LocalVoucher.initEngine()
import isJson from 'is-json'
import isEmpty from 'lodash.isempty'
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
        login_status:0,
        socket_id:0,
        apprules:[],
        appstatus:[],
        access_user:(LocalVoucher.getValue('WebSite.access_user'))?(JSON.parse(LocalVoucher.getValue('WebSite.access_user'))):'',
        isbindapps:(LocalVoucher.getValue('WebSite.isbindapps'))?(JSON.parse(LocalVoucher.getValue('WebSite.isbindapps'))):'',
        website_user:(LocalVoucher.getValue('WebSite.website_user'))?(JSON.parse(LocalVoucher.getValue('WebSite.website_user'))):'',
        website_level:1,
    },
    AppRules:[],
    SysInfo:{
        ip:'0.0.0.0',
        screenWidth:0,
        screenHeight:0,
        os:'',
        os_description:''

    },
    ScoketInfo:{
        unique_auth_code:LocalVoucher.getValue('socketinfo.unique_auth_code'),
        cid:'',
    },
    AppShow:{
        chat:false,
        music:false
    },
    wechat_marketing_store:{
        apihost:'',
        token:isEmpty(LocalVoucher.getValue('wechat_marketing_store.token'))?'':(LocalVoucher.getValue('wechat_marketing_store.token')),
        userinfo:isJson(LocalVoucher.getValue('wechat_marketing_store.userinfo'))?(JSON.parse(LocalVoucher.getValue('wechat_marketing_store.userinfo'))):'',
        last_response:{},
        page_model:false
    },
    socket_server_store:{
        apihost:'',
        token:isEmpty(LocalVoucher.getValue('socket_server_store.token'))?'':(LocalVoucher.getValue('socket_server_store.token')),
        userinfo:isJson(LocalVoucher.getValue('socket_server_store.userinfo'))?(JSON.parse(LocalVoucher.getValue('socket_server_store.userinfo'))):'',
        last_response:{},
        page_model:false
    }

}

export default new Vuex.Store({
    // strict:true,
    state,
    mutations,
    getters,
    actions
})
