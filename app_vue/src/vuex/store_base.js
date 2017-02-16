import Vue  from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        platform: ''
    },
    mutations: {
        SET_APP(state, platform) {
            state.platform = platform;
        }
    },
    actions: {
        setApp({commit}, platform) {
            commit('SET_APP', platform);
        }
    },
    getters: {
        getApp: (state) => state.platform
    }
})
