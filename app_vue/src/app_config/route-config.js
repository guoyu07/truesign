// import Home from './components/Home.vue'
// import Article from './components/Article.vue'
// import Demo from './components/Demo.vue'
// import DemoVuexState from './components/DemoVuexState.vue'
import {router_spa} from '../components/project/spa/router-spa'

const home = resolve => require(['../components/Home.vue'], resolve)
const time2hope_index = resolve => require(['../components/time2hope/index.vue'], resolve)
const base_threejs = resolve => require(['../components/time2hope/base_threejs.vue'], resolve)
const timeline_threejs = resolve => require(['../components/time2hope/timeline_threejs.vue'], resolve)
const threejs_text = resolve => require(['../components/effect/threejs_text.vue'], resolve)
const note = resolve => require(['../components/project/note/note.vue'], resolve)
// const threejs_dev = resolve => require(['../components/effect/threejs_dev.vue'], resolve)
// const threejs_dat_gui = resolve => require(['../components/effect/threejs_dat_gui.vue'], resolve)
// const threejs_skybox = resolve => require(['../components/effect/threejs_skybox.vue'], resolve)

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
    name: 'note',
    path: '/note',
    meta: {
      title: 'note'
    },
    component: note
  },
  {
    name: 'threejs_text',
    path: '/threejs_text',
    meta: {
      title: 'threejs_text'
    },
    component: threejs_text
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
        name: 'timeline_threejs',
        path: 'timeline_threejs',
        meta: {
          title: 'timeline_threejs'
        },
        component: timeline_threejs
      },

      // {
      //   name: 'threejs_dev',
      //   path: 'threejs_dev',
      //   meta: {
      //     title: 'threejs_dev'
      //   },
      //   component: threejs_dev
      // },
      // {
      //   name: 'threejs_dat_gui',
      //   path: 'threejs_dat_gui',
      //   meta: {
      //     title: 'threejs_dat_gui'
      //   },
      //   component: threejs_dat_gui
      // },
      // {
      //   name: 'threejs_skybox',
      //   path: 'threejs_skybox',
      //   meta: {
      //     title: 'threejs_skybox'
      //   },
      //   component: threejs_skybox
      // },
    ]
  },


]
export default routes
