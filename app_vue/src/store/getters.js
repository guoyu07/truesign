/**
 * Created by ql-win on 2017/4/22.
 */

const getters = {
    apprules: state => {
        return state.AppRules
    },
    website: state => {
        return state.WebSite
    },
    sysinfo: state => {
        return state.SysInfo
    },
    eventfactory : state => {
        return state.EventFactory
    },
    appshow: state => {
        return state.AppShow
    },
    wechat_marketing_store: state => {
        return state.wechat_marketing_store
    },
    socket_server_store: state => {
        return state.socket_server_store
    },
    socketinfo:state => {
        return state.ScoketInfo
    }
}

export default getters


