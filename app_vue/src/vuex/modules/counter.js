const state = {
    count: 0
}

const getters = {
    getCount: state => state.count
}

const mutations = {
    'INCREMENT': state => state.count ++,
    'DECREMENT': state => state.count --
}

const actions = {
    'INCREMENT': store => store.commit('INCREMENT'),
    'DECREMENT': store => store.commit('DECREMENT')
}

export default {
    state,
    getters,
    mutations,
    actions
}
