// import Home from './components/Home.vue'
// import Article from './components/Article.vue'
// import Demo from './components/Demo.vue'
// import DemoVuexState from './components/DemoVuexState.vue'
import {router_spa} from '../components/project/spa/router-spa'

const home = resolve => require(['../components/Home.vue'], resolve)
const time2hope_index = resolve => require(['../components/time2hope/index.vue'], resolve)
const base_threejs = resolve => require(['../components/time2hope/base_threejs.vue'], resolve)
const routes = [

  {
    name: 'home',
    path: '/',
    meta: {
      title: 'home'
    },
    component: home
  },
  {
    name: 'time2hope_index',
    path: '/time2hope_index',
    meta: {
      title: 'time2hope_index'
    },
    component: time2hope_index,
    children: [
      {
        name: 'base_threejs',
        path: 'base_threejs',
        meta: {
          title: 'base_threejs'
        },
        component: base_threejs
      },
    ]
  },


]
export default routes
