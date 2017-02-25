// import Home from './components/Home.vue'
// import Article from './components/Article.vue'
// import Demo from './components/Demo.vue'
// import DemoVuexState from './components/DemoVuexState.vue'
import {router_spa} from "../components/project/spa/router-spa"
const home = resolve => require(['../components/Home.vue'], resolve)
const demo = resolve => require(['../components/test/Demo.vue'], resolve)

const semantic = resolve => require(['../components/test/semantic.vue'], resolve)
const awesome = resolve => require(['../components/test/awesome.vue'], resolve)
const alert = resolve => require(['../components/test/alert.vue'], resolve)
const vuex = resolve => require(['../components/test/vuex.vue'], resolve)
const vuex2 = resolve => require(['../components/test/vuex2.vue'], resolve)
const mint_ui = resolve => require(['../components/project/mint-ui.vue'], resolve)
const websocket = resolve => require(['../components/test/websocket.vue'], resolve)
const socket_io = resolve => require(['../components/test/socket-io.vue'], resolve)
const vux = resolve => require(['../components/test/vux.vue'], resolve)
const iview = resolve => require(['../components/project/iview.vue'], resolve)
const webgl = resolve => require(['../components/test/webgl.vue'], resolve)
const echat = resolve => require(['../components/test/echat.vue'], resolve)
const element = resolve => require(['../components/project/element.vue'], resolve)
const spa = resolve => require(['../components/project/spa/spa.vue'], resolve)
const test = resolve => require(['../components/test/test.vue'], resolve)
const project = resolve => require(['../components/project/project.vue'], resolve)

const effect = resolve => require(['../components/effect/effect.vue'], resolve)
const dynamic_effect = resolve => require(['../components/effect/dynamic_effect.vue'], resolve)



const routes = [
    {
        name: 'Home',
        path: '/',
        meta: {
            title: 'home'
        },
        component: home
    },
    {
        name: 'Test',
        path: '/test',
        meta: {
            title: 'test'
        },
        component:test,
        children:[

            {
                name: 'Demo',
                path: 'demo',
                meta: {
                    title: 'demo'
                },
                component: demo
            },
            {
                name: 'semantic',
                path: 'semantic',
                meta: {
                    title: 'semantic'
                },
                component: semantic
            },
            {
                name: 'awesome',
                path: 'awesome',
                meta: {
                    title: 'awesome'
                },
                component: awesome
            },
            {
                name: 'alert',
                path: 'alert',
                meta: {
                    title: 'alert'
                },
                component: alert
            },
            {
                name: 'vuex',
                path: 'vuex',
                meta: {
                    title: 'vuex'
                },
                component: vuex
            },
            {
                name: 'vuex2',
                path: 'vuex2',
                meta: {
                    title: 'vuex2'
                },
                component: vuex2
            },


            {
                name: 'websocket',
                path: 'websocket',
                meta: {
                    title: 'websocket'
                },
                component: websocket
            },
            {
                name: 'socket-io',
                path: 'socket-io',
                meta: {
                    title: 'socket-io'
                },
                component: socket_io
            },

            {
                name: 'vux',
                path: 'vux',
                meta: {
                    title: 'vux'
                },
                component: vux
            },
            {
                name: 'webgl',
                path: 'webgl',
                meta: {
                    title: 'webgl'
                },
                component: webgl
            },
            {
                name: 'echat',
                path: 'echat',
                meta: {
                    title: 'echat'
                },
                component: echat
            },


        ]

    },
    {
        name: 'project',
        path: '/project',
        meta: {
            title: 'project'
        },
        component: project,
        children:[
            {
                name: 'element',
                path: 'element',
                meta: {
                    title: 'element'
                },
                component: element
            },
            {
                name: 'iview',
                path: 'iview',
                meta: {
                    title: 'iview'
                },
                component: iview
            },
            {
                name: 'mint-ui',
                path: 'mint-ui',
                meta: {
                    title: 'mint-ui'
                },
                component: mint_ui
            },

            router_spa
        ]
    },
    {
        name: 'effect',
        path: '/effect',
        meta: {
            title: 'effect'
        },
        component: effect,
        children:[
            {
                name: 'dynamic_effect',
                path: 'dynamic_effect',
                meta: {
                    title: 'dynamic_effect'
                },
                component: dynamic_effect
            },

        ]
    },







]
console.log(routes)

export default routes
