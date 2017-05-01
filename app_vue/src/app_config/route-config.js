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
const line = resolve => require(['../components/test/line.vue'], resolve)
const vux = resolve => require(['../components/test/vux.vue'], resolve)
const iview = resolve => require(['../components/project/iview.vue'], resolve)
const webgl = resolve => require(['../components/test/webgl.vue'], resolve)
const echat = resolve => require(['../components/test/echat.vue'], resolve)
const element = resolve => require(['../components/project/element.vue'], resolve)
const spa = resolve => require(['../components/project/spa/spa.vue'], resolve)
const canvas = resolve => require(['../components/project/canvas.vue'], resolve)
const test = resolve => require(['../components/test/test.vue'], resolve)
/*
工具组件工作区
 */
const tools = resolve => require(['../components/tools/tools.vue'], resolve)
const VueQuillEditor = resolve => require(['../components/tools/VueQuillEditor.vue'], resolve)
const vue2editor = resolve => require(['../components/tools/vue2-editor.vue'], resolve)
const wangeditor = resolve => require(['../components/tools/wangeditor.vue'], resolve)

const project = resolve => require(['../components/project/project.vue'], resolve)
const gdmap = resolve => require(['../components/project/gdmap.vue'], resolve)
const video = resolve => require(['../components/project/video.vue'], resolve)

const effect = resolve => require(['../components/effect/effect.vue'], resolve)
const particles = resolve => require(['../components/effect/particles.vue'], resolve)
const blackhole = resolve => require(['../components/effect/blackhole.vue'], resolve)
const router_effect_from = resolve => require(['../components/effect/router_effect_from.vue'], resolve)
const router_effect_to = resolve => require(['../components/effect/router_effect_to.vue'], resolve)
const dynamic_effect = resolve => require(['../components/effect/dynamic_effect.vue'], resolve)
const common = resolve => require(['../components/common/common.vue'], resolve)
const slidermenu = resolve => require(['../components/common/slidermenu.vue'], resolve)
const mainpage = resolve => require(['../components/mainpage/mainpage.vue'], resolve)
const fullpage = resolve => require(['../components/mainpage/fullpage.vue'], resolve)
/*
loading 工作区
 */
const loading = resolve => require(['../components/loading/loading.vue'], resolve)
const effect_line = resolve => require(['../components/loading/effect_line.vue'], resolve)
const effect_logo = resolve => require(['../components/loading/effect_logo.vue'], resolve)
const svg = resolve => require(['../components/loading/svg.vue'], resolve)

/*
help 工作区
 */
const help_window_resize = resolve => require(['../components/help/window_resize.vue'], resolve)


/*
数据通讯工作区
 */
const conn = resolve => require(['../components/communicationModule/conn.vue'], resolve)
const initSocket = resolve => require(['../components/communicationModule/initSocket.vue'], resolve)

/*
世纪通泰公司项目
 */
const siteshow_main = resolve => require(['../components/project/siteshow/main.vue'], resolve)
const siteshow_backend = resolve => require(['../components/project/siteshow/backend.vue'], resolve)

/*
truesign-project 工作区
 */
/*
website 网站工作区
 */
const website_main = resolve => require(['../components/project/website_app/main.vue'], resolve)
const website_index = resolve => require(['../components/project/website_app/index.vue'], resolve)
const website_app_square = resolve => require(['../components/project/website_app/app_square.vue'], resolve)

const apps = resolve => require(['../components/project/website_app/apps/apps.vue'], resolve)
const wechat = resolve => require(['../components/project/website_app/apps/wechat.vue'], resolve)
const chat = resolve => require(['../components/project/website_app/apps/chat.vue'], resolve)





const routes = [
    // {
    //     name: '天津世纪通泰科技有限公司',
    //     path: '/',
    //     meta: {
    //         title: '天津世纪通泰科技有限公司'
    //     },
    //     component: siteshow_main
    // },
    // {
    //     name: '世纪通泰科技有限公司后台',
    //     path: '/siteshow_backend',
    //     meta: {
    //         title: '世纪通泰科技有限公司后台'
    //     },
    //     component: siteshow_backend
    // },
    {
        name: 'Home',
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
        children:[
            {
                name: 'VueQuillEditor',
                path: 'VueQuillEditor',
                meta: {
                    title: 'VueQuillEditor'
                },
                component: VueQuillEditor
            },
            {
                name: 'vue2editor',
                path: 'vue2editor',
                meta: {
                    title: 'vue2editor'
                },
                component: vue2editor
            },
            {
                name: 'wangeditor',
                path: 'wangeditor',
                meta: {
                    title: 'wangeditor'
                },
                component: wangeditor
            },

        ]
    },
    {
        name: 'common',
        path: '/common',
        meta: {
            title: 'common'
        },
        component: common,
        children:[
            {
                name: 'slidermenu',
                path: 'slidermenu',
                meta: {
                    title: 'slidermenu'
                },
                component: slidermenu
            },

        ]
    },
    {
        name: 'mainpage',
        path: '/mainpage',
        meta: {
            title: 'mainpage'
        },
        component:mainpage,
        children:[
            {
                name: 'fullpage',
                path: 'fullpage',
                meta: {
                    title: 'fullpage'
                },
                component: fullpage
            },
        ]

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
                name: 'line',
                path: 'line',
                meta: {
                    title: 'line'
                },
                component: line
            },
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
                name: '世纪通泰科技有限公司主页',
                path: 'siteshow_main',
                meta: {
                    title: '世纪通泰科技有限公司主页'
                },
                component: siteshow_main
            },
            {
                name: '世纪通泰科技有限公司后台',
                path: 'siteshow_backend',
                meta: {
                    title: '世纪通泰科技有限公司后台'
                },
                component: siteshow_backend
            },
            {
              name: 'gdmap',
              path: 'gdmap',
              meta: {
                title: 'gdmap'
              },
              component: gdmap
            },
            {
                name: 'video',
                path: 'video',
                meta: {
                    title: 'video'
                },
                component: video
            },

            {
                name: 'mint-ui',
                path: 'mint-ui',
                meta: {
                    title: 'mint-ui'
                },
                component: mint_ui
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
                name: 'website_main',
                path: 'website_main',
                meta: {
                    title: 'website_main'
                },
                component: website_main,
                children:[
                    {
                        name: 'website_index',
                        path: 'website_index',
                        meta: {
                            title: 'website_index'
                        },
                        component: website_index
                    },
                    {
                        name: 'website_app_square',
                        path: 'website_app_square',
                        meta: {
                            title: 'website_app_square'
                        },
                        component: website_app_square,
                    },
                    {
                        name: 'apps',
                        path: 'apps',
                        meta: {
                            title: 'apps'
                        },
                        component: apps,
                        children:[
                            {
                                name: 'wechat',
                                path: 'wechat',
                                meta: {
                                    title: 'wechat'
                                },
                                component: wechat
                            },
                            {
                                name: 'chat',
                                path: 'chat',
                                meta: {
                                    title: 'chat'
                                },
                                component: chat
                            },
                        ]
                    },
                ]
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
            {
                name: 'particles',
                path: 'particles',
                meta: {
                    title: 'particles'
                },
                component: particles
            },
            {
                name: 'router_effect_from',
                path: 'router_effect_from',
                meta: {
                    title: 'router_effect_from'
                },
                component: router_effect_from
            },
            {
                name: 'router_effect_to',
                path: 'router_effect_to',
                meta: {
                    title: 'router_effect_to'
                },
                component: router_effect_to
            },
            {
                name: 'blackhole',
                path: 'blackhole',
                meta: {
                    title: 'blackhole'
                },
                component: blackhole
            },


        ]
    },
    {
        name: 'loading',
        path: '/loading',
        meta: {
            title: 'loading'
        },
        component: loading,
        children:[
            {
                name: 'effect_line',
                path: 'effect_line',
                meta: {
                    title: 'effect_line'
                },
                component: effect_line
            },
            {
                name: 'effect_logo',
                path: 'effect_logo',
                meta: {
                    title: 'effect_logo'
                },
                component: effect_logo
            },
            {
                name: 'svg',
                path: 'svg',
                meta: {
                    title: 'svg'
                },
                component: svg
            },

        ]
    },
    {
        name: 'conn',
        path: '/conn',
        meta: {
            title: 'conn'
        },
        component: conn,
        children:[
            {
                name: 'initSocket',
                path: 'initSocket',
                meta: {
                    title: 'initSocket'
                },
                component: initSocket
            },
        ]
    },
    {
        name: 'help_window_resize',
        path: '/help_window_resize',
        meta: {
            title: 'help_window_resize'
        },
        component: help_window_resize,


    },


]

export default routes
