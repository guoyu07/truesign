import * as types from './mutation-types'
import LocalVoucher from '../api/localVoucherTools.js'
var _ = require('lodash');

LocalVoucher.checkStorageMode()
LocalVoucher.initEngine()
// 对于vuex的用法，其实理解了流程就行
// vuex -> actions -> 提交（commit）mutations ->state -> view -> dispatch 触发 actions ->...
// actions 其实是mutations的升级版，它实现了mutations只能同步改变状态不能异步改变
// actions 就是可以异步操作mutation的提交
// 具体可以看下我的blog中的总结 http://selvinpro.com/2017/03/17/vuex-about/#more
export const mutations = {
  // 这里的data指提交时：
  // 从/api/login传回的user对象，其中包含name,messeage等信息
    [types.WEBSITE](state, data) {
    if(data.unique_auth_code){
        state.WebSite.unique_auth_code = data.unique_auth_code;

        LocalVoucher.setKeyValue('WebSite.unique_auth_code',state.WebSite.unique_auth_code)
    }
    if(data.encryption_key){
        state.WebSite.encryption_key = data.encryption_key;

        LocalVoucher.setKeyValue('WebSite.encryption_key',state.WebSite.encryption_key)
    }
    if(data.website_encryption_key){
        state.WebSite.website_encryption_key = data.website_encryption_key;

        LocalVoucher.setKeyValue('WebSite.website_encryption_key',state.WebSite.website_encryption_key)
    }
    if(data.conn_status){
        state.WebSite.conn_status = data.conn_status;

        LocalVoucher.setKeyValue('WebSite.conn_status',parseInt(state.WebSite.conn_status))
    }
    else if(data.conn_status === 0){
        state.WebSite.conn_status = data.conn_status;
        LocalVoucher.setKeyValue('WebSite.conn_status',state.WebSite.conn_status+'')

    }
    if(data.login_status){
      state.WebSite.login_status = data.login_status;
    }
    else if(data.login_status === 0){
      state.WebSite.login_status = data.login_status;
    }

    if(data.socket_id){
        state.WebSite.socket_id = data.socket_id;
    }
    if(data.access_user){
        state.WebSite.access_user = data.access_user;
        LocalVoucher.setKeyValue('WebSite.access_user',JSON.stringify(state.WebSite.access_user))
    }
    else if(data.access_user === null){
        state.WebSite.access_user = '';
    }
    if(data.isbindapps){
        state.WebSite.isbindapps = data.isbindapps;
        LocalVoucher.setKeyValue('WebSite.isbindapps',JSON.stringify(state.WebSite.isbindapps))
    }
    if(data.website_user){
        state.WebSite.website_user = data.website_user;
        LocalVoucher.setKeyValue('WebSite.website_user',JSON.stringify(state.WebSite.website_user))

    }
    if(data.website_level){
        state.WebSite.website_level = data.website_level;
    }
    if(data.apprules){
        state.WebSite.apprules = data.apprules
    }
    /*
    处理app状态
     */
    if(data.appstatus){
        if(data.type==='add'){
            state.WebSite.appstatus.push(data.appstatus)
            state.WebSite.appstatus = _.uniq(state.WebSite.appstatus)
        }
        else if(data.type === 'rm'){
            state.WebSite.appstatus = _.remove(state.WebSite.appstatus,function (v) {
                return v === data.appstatus
            })
        }
    }



    // vuex的本质作用是管理组件之间复杂的状态的（如购物车逻辑等等...）
    // 所以当刷新浏览器时，这些状态也会一并被清空
    // 所以还是需要有一个长期在浏览器中保存如登录/登出状态的机制
    // 因此这里采用了localStorage
    // 一定要明白vuex这类库的本质作用，它极大的增加了前端逻辑处理的可能性

    // localStorage.setItem('session', data.session.user)
    },
    [types.APPRULES](state,data) {
      if(data.apprules){
          state.AppRules = data.apprules
      }
    },
    [types.SYSINFO](state,data) {
      if(data.ip){
          state.SysInfo.ip = data.ip
      }
      if(data.screenHeight){
          state.SysInfo.screenHeight = data.screenHeight
      }
      if(data.screenWidth){
          state.SysInfo.screenWidth = data.screenWidth
      }
    },
    [types.EVENTFACTORY](state,data) {
    if(data.type === 'init_socket_send_factory'){
        state.EventFactory.init_socket_send_factory.push(data.event)
    }
    else if(data.type === 'socket_send_factory'){
        state.EventFactory.socket_send_factory.push(data.event)
        state.EventFactory.socket_send_factory = _.uniq(state.EventFactory.socket_send_factory)

    }
    else if(data.type === 'unshift_socket_send_factory'){
        state.EventFactory.socket_send_factory.unshift(data.event)

    }
    // else if(data.type === 'shift_init_socket_send_factory'){
    //     state.EventFactory.init_socket_send_factory.shift()
    // }
    // else if(data.type === 'shift_socket_send_factory'){
    //     state.EventFactory.socket_send_factory.shift()
    // }
    },
    [types.APPSHOW](state,data){
        if(data.chat){
            state.AppShow.chat = data.chat
        }
        else if(data.chat === false){
            state.AppShow.chat = data.chat
        }
        if(data.music){
            state.AppShow.music = data.music
        }
        else if(data.music === false){
            state.AppShow.music = data.music
        }
    }

}

export default mutations
