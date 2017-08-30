// import Home from './components/Home.vue'
// import Article from './components/Article.vue'
// import Demo from './components/Demo.vue'
// import DemoVuexState from './components/DemoVuexState.vue'
import {router_spa} from '../components/project/spa/router-spa'
const home = resolve => require(['../components/Home.vue'], resolve)

const tools = resolve => require(['../components/tools/tools.vue'], resolve)
const websocket = resolve => require(['../components/tools/websocket.vue'], resolve)

const socket_server_manager = resolve => require(['../components/project/socket_server_manager/index.vue'], resolve)
const ssm_user = resolve => require(['../components/project/socket_server_manager/user.vue'], resolve)
const ssm_app = resolve => require(['../components/project/socket_server_manager/app.vue'], resolve)
const ssm_authlog = resolve => require(['../components/project/socket_server_manager/authlog.vue'], resolve)
const ssm_msglog = resolve => require(['../components/project/socket_server_manager/msglog.vue'], resolve)


const effect = resolve => require(['../components/effect/effect.vue'], resolve)
const loading_canvas = resolve => require(['../components/effect/loading_canvas.vue'], resolve)
const threejs_dev = resolve => require(['../components/effect/threejs_dev.vue'], resolve)
const threejs_dev_trackball = resolve => require(['../components/effect/threejs_dev_trackball.vue'], resolve)
const cache_canvas = resolve => require(['../components/effect/cache_canvas.vue'], resolve)
const word_canvas = resolve => require(['../components/effect/word_canvas.vue'], resolve)
const countdown_canvas = resolve => require(['../components/effect/countdown_canvas.vue'], resolve)
const draw_canvas = resolve => require(['../components/effect/draw_canvas.vue'], resolve)
const canvas = resolve => require(['../components/effect/canvas.vue'], resolve)
const ball_canvas = resolve => require(['../components/effect/ball_canvas.vue'], resolve)
const ball_canvas_onedraw = resolve => require(['../components/effect/ball_canvas_onedraw.vue'], resolve)
const ball_canvas_init = resolve => require(['../components/effect/ball_canvas_init.vue'], resolve)
const canvas_materials_video = resolve => require(['../components/effect/canvas_materials_video.vue'], resolve)


const project = resolve => require(['../components/project/project.vue'], resolve)
const jktruesign_doc = resolve => require(['../components/project/jktruesign_app/doc.vue'], resolve)
const jktruesign_doctype = resolve => require(['../components/project/jktruesign_app/doc_type.vue'], resolve)
const jktruesign_doc_handle_log = resolve => require(['../components/project/jktruesign_app/doc_handle_log.vue'], resolve)

const test = resolve => require(['../components/test/test.vue'], resolve)
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
        name: 'test',
        path: '/test',
        meta: {
            title: 'test'
        },
        component: test
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
        name: 'socket_server_manager',
        path: '/socket_server_manager',
        meta: {
            title: 'socket_server_manager'
        },
        component: socket_server_manager,
        children: [
            {
                name: 'ssm_user',
                path: 'ssm_user',
                meta: {
                    title: 'ssm_user'
                },
                component: ssm_user
            },
            {
                name: 'ssm_app',
                path: 'ssm_app',
                meta: {
                    title: 'ssm_app'
                },
                component: ssm_app
            },
            {
                name: 'ssm_authlog',
                path: 'ssm_authlog',
                meta: {
                    title: 'ssm_authlog'
                },
                component: ssm_authlog
            },
            {
                name: 'ssm_msglog',
                path: 'ssm_msglog',
                meta: {
                    title: 'ssm_msglog'
                },
                component: ssm_msglog
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
        children: [
            {
                name: 'jktruesign_doc',
                path: 'jktruesign_doc',
                meta: {
                    title: 'jktruesign_doc'
                },
                component: jktruesign_doc
            },
            {
                name: 'jktruesign_doctype',
                path: 'jktruesign_doctype',
                meta: {
                    title: 'jktruesign_doctype'
                },
                component: jktruesign_doctype
            },
            {
                name: 'jktruesign_doc_handle_log',
                path: 'jktruesign_doc_handle_log',
                meta: {
                    title: 'jktruesign_doc_handle_log'
                },
                component: jktruesign_doc_handle_log
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
                name: 'canvas_materials_video',
                path: 'canvas_materials_video',
                meta: {
                    title: 'canvas_materials_video'
                },
                component: canvas_materials_video
            },
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
            {
                name: 'draw_canvas',
                path: 'draw_canvas',
                meta: {
                    title: 'draw_canvas'
                },
                component: draw_canvas
            },
            {
                name: 'canvas',
                path: 'canvas',
                meta: {
                    title: 'canvas'
                },
                component: canvas
            },
            {
                name: 'word_canvas',
                path: 'word_canvas',
                meta: {
                    title: 'word_canvas'
                },
                component: word_canvas
            },
            {
                name: 'countdown_canvas',
                path: 'countdown_canvas',
                meta: {
                    title: 'countdown_canvas'
                },
                component: countdown_canvas
            },
            {
                name: 'cache_canvas',
                path: 'cache_canvas',
                meta: {
                    title: 'cache_canvas'
                },
                component: cache_canvas
            },
            {
                name: 'ball_canvas',
                path: 'ball_canvas',
                meta: {
                    title: 'ball_canvas'
                },
                component: ball_canvas
            },
            {
                name: 'ball_canvas_onedraw',
                path: 'ball_canvas_onedraw',
                meta: {
                    title: 'ball_canvas_onedraw'
                },
                component: ball_canvas_onedraw
            },
            {
                name: 'ball_canvas_init',
                path: 'ball_canvas_init',
                meta: {
                    title: 'ball_canvas_init'
                },
                component: ball_canvas_init
            },
          
        ]
    },
]
export default routes
