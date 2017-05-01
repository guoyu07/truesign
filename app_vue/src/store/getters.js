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
    }
}

export default getters


