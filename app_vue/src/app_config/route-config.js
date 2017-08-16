// import Home from './components/Home.vue'
// import Article from './components/Article.vue'
// import Demo from './components/Demo.vue'
// import DemoVuexState from './components/DemoVuexState.vue'
import {router_spa} from '../components/project/spa/router-spa'
const home = resolve => require(['../components/Home.vue'], resolve)

const tools = resolve => require(['../components/tools/tools.vue'], resolve)
const websocket = resolve => require(['../components/tools/websocket.vue'], resolve)

const effect = resolve => require(['../components/effect/effect.vue'], resolve)
const loading_canvas = resolve => require(['../components/effect/loading_canvas.vue'], resolve)
const threejs_dev = resolve => require(['../components/effect/threejs_dev.vue'], resolve)
const threejs_dev_trackball = resolve => require(['../components/effect/threejs_dev_trackball.vue'], resolve)
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
        name: 'tools',
        path: '/tools',
        meta: {
            title: 'tools'
        },
        component: tools,
        children: [
            {
                name: 'websocket',
                path: 'websocket',
                meta: {
                    title: 'websocket'
                },
                component: websocket
            },
        ]
    },
    {
        name: 'effect',
        path: '/effect',
        meta: {
            title: 'effect'
        },
        component: effect,

        children: [
            {
                name: 'loading_canvas',
                path: 'loading_canvas',
                meta: {
                    title: 'loading_canvas'
                },
                component: loading_canvas
            },
            {
                name: 'threejs_dev',
                path: 'threejs_dev',
                meta: {
                    title: 'threejs_dev'
                },
                component: threejs_dev
            },
            {
                name: 'threejs_dev_trackball',
                path: 'threejs_dev_trackball',
                meta: {
                    title: 'threejs_dev_trackball'
                },
                component: threejs_dev_trackball
            },
        ]
    },
]
export default routes
