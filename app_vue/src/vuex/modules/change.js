const state = {
    message: 'hello vuex'
}

const getters = {
    getMessage: state => state.message
}

const mutations = {
    'CHANGEMESSAGE': state => { state.message = 'hellw vue change' }
}

const actions = {
    'CHANGEMESSAGE': store => store.commit('CHANGEMESSAGE')
}

export default {
    state,
    getters,
    mutations,
    actions
}
