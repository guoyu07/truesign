// import Home from './components/Home.vue'
// import Article from './components/Article.vue'
// import Demo from './components/Demo.vue'
// import DemoVuexState from './components/DemoVuexState.vue'
import {router_spa} from '../components/project/spa/router-spa'

const home = resolve => require(['../components/Home.vue'], resolve)
const images = resolve => require(['../components/project/Techies/images.vue'], resolve)
const pay_logs = resolve => require(['../components/project/Techies/pay_logs.vue'], resolve)
const sysconfig = resolve => require(['../components/project/Techies/sysconfig.vue'], resolve)
const user = resolve => require(['../components/project/Techies/user.vue'], resolve)
const test = resolve => require(['../components/test/test.vue'], resolve)
// const time2hope_index = resolve => require(['../components/time2hope/index.vue'], resolve)
// const base_threejs = resolve => require(['../components/time2hope/base_threejs.vue'], resolve)
// const timeline_threejs = resolve => require(['../components/time2hope/timeline_threejs.vue'], resolve)
// const note = resolve => require(['../components/project/note/note.vue'], resolve)
// const threejs_dev = resolve => require(['../components/effect/threejs_dev.vue'], resolve)
// const threejs_dat_gui = resolve => require(['../components/effect/threejs_dat_gui.vue'], resolve)
// const threejs_skybox = resolve => require(['../components/effect/threejs_skybox.vue'], resolve)

const routes = [

  {
    name: 'test',
    path: '/',
    meta: {
      title: 'test'
    },
    component: test
  },
    {
        name: 'images',
        path: '/images',
        meta: {
            title: 'images'
        },
        component: images
    },
    {
        name: 'pay_logs',
        path: '/pay_logs',
        meta: {
            title: 'pay_logs'
        },
        component: pay_logs
    },
    {
        name: 'sysconfig',
        path: '/sysconfig',
        meta: {
            title: 'sysconfig'
        },
        component: sysconfig
    },
    {
        name: 'user',
        path: '/user',
        meta: {
            title: 'user'
        },
        component: user
    }



]
export default routes
