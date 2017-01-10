var Vue = require('vue'); // get vue
var Vuex = require('vuex'); // get vuex

Vue.use(Vuex);

var state = {
  cardData: [],
  isloadingComplete: false,
  busy: false,
  isShow: false,
};

var getters = {

}

var mutations = {
  updateLoadingState(state, data){
    state.isloadingComplete = data;
  },
  updateBusyState(state, data){
    state.busy = data;
  },
  addData(state, data){
    state.cardData = state.cardData.concat(data);
  },
  refreshData(state, data){
    state.cardData = data;
  },
  isShowAlert(state, data){
    state.isShow = data;
  }
};

var actions = {
};

var moduleCard = {
  state: state,
  getters: getters,
  mutations: mutations,
  actions: actions
};

var store = new Vuex.Store({
  state: state,
  getters: getters,
  mutations: mutations,
  actions: actions
});

module.exports = store;

// export default new Vuex.Store({
//   modules: {
//     moduleCard: moduleCard
//   }
// });
