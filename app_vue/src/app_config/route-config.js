// import Home from './components/Home.vue'
// import Article from './components/Article.vue'
// import Demo from './components/Demo.vue'
// import DemoVuexState from './components/DemoVuexState.vue'
import {router_spa} from "../components/project/spa/router-spa"
const home = resolve => require(['../components/Home.vue'], resolve)
// const demo = resolve => require(['../components/test/Demo.vue'], resolve)
//
// const semantic = resolve => require(['../components/test/semantic.vue'], resolve)
// const awesome = resolve => require(['../components/test/awesome.vue'], resolve)
// const alert = resolve => require(['../components/test/alert.vue'], resolve)
// const vuex = resolve => require(['../components/test/vuex.vue'], resolve)
// const vuex2 = resolve => require(['../components/test/vuex2.vue'], resolve)
// const mint_ui = resolve => require(['../components/project/mint-ui.vue'], resolve)
// const websocket = resolve => require(['../components/test/websocket.vue'], resolve)
// const socket_io = resolve => require(['../components/test/socket-io.vue'], resolve)
// const line = resolve => require(['../components/test/line.vue'], resolve)
// const iview = resolve => require(['../components/project/iview.vue'], resolve)
// const webgl = resolve => require(['../components/test/webgl.vue'], resolve)
// const echart = resolve => require(['../components/common/echart.vue'], resolve)
// const element = resolve => require(['../components/project/element.vue'], resolve)
// const spa = resolve => require(['../components/project/spa/spa.vue'], resolve)
const test = resolve => require(['../components/test/test.vue'], resolve)
// const test_child1 = resolve => require(['../components/test/test_child1.vue'], resolve)
// const test_child2 = resolve => require(['../components/test/test_child2.vue'], resolve)



// /*
// 工具组件工作区
//  */
const tools = resolve => require(['../components/tools/tools.vue'], resolve)
// const VueQuillEditor = resolve => require(['../components/tools/VueQuillEditor.vue'], resolve)
// const vue2editor = resolve => require(['../components/tools/vue2-editor.vue'], resolve)
// const wangeditor = resolve => require(['../components/tools/wangeditor.vue'], resolve)
const wangeditor_new = resolve => require(['../components/tools/wangeditor_new.vue'], resolve)
const table_model = resolve => require(['../components/common/table_model.vue'], resolve)
const table_model_dev = resolve => require(['../components/common/table_model_dev.vue'], resolve)

const project = resolve => require(['../components/project/project.vue'], resolve)
// const gdmap = resolve => require(['../components/project/gdmap.vue'], resolve)
// const video = resolve => require(['../components/project/video.vue'], resolve)
//
const effect = resolve => require(['../components/effect/effect.vue'], resolve)
const canvas = resolve => require(['../components/effect/canvas.vue'], resolve)
const loading_canvas = resolve => require(['../components/effect/loading_canvas.vue'], resolve)
const loading_canvas_backup = resolve => require(['../components/effect/loading_canvas_backup.vue'], resolve)

const threejs_wormhole = resolve => require(['../components/effect/threejs_wormhole.vue'], resolve)
const css3d_periodictable = resolve => require(['../components/effect/css3d_periodictable.vue'], resolve)
const canvas_materials_video = resolve => require(['../components/effect/canvas_materials_video.vue'], resolve)
const threejs_dev = resolve => require(['../components/effect/threejs_dev.vue'], resolve)
const threejs_dev_trackball = resolve => require(['../components/effect/threejs_dev_trackball.vue'], resolve)
const particles = resolve => require(['../components/effect/particles.vue'], resolve)
// const blackhole = resolve => require(['../components/effect/blackhole.vue'], resolve)
const router_effect_from = resolve => require(['../components/effect/router_effect_from.vue'], resolve)
const router_effect_to = resolve => require(['../components/effect/router_effect_to.vue'], resolve)
const dynamic_effect = resolve => require(['../components/effect/dynamic_effect.vue'], resolve)
const common = resolve => require(['../components/common/common.vue'], resolve)
const slidermenu = resolve => require(['../components/common/slidermenu.vue'], resolve)
const mainpage = resolve => require(['../components/mainpage/mainpage.vue'], resolve)
const fullpage = resolve => require(['../components/mainpage/fullpage.vue'], resolve)

// loading 工作区
//  */
// const loading = resolve => require(['../components/loading/loading.vue'], resolve)
// const effect_line = resolve => require(['../components/loading/effect_line.vue'], resolve)
// const effect_logo = resolve => require(['../components/loading/effect_logo.vue'], resolve)
// const svg = resolve => require(['../components/loading/svg.vue'], resolve)
//
// /*
// help 工作区
//  */
// const help_window_resize = resolve => require(['../components/help/window_resize.vue'], resolve)
//
//
// /*
// 数据通讯工作区
//  */
// const conn = resolve => require(['../components/communicationModule/conn.vue'], resolve)
// const initSocket = resolve => require(['../components/communicationModule/initSocket.vue'], resolve)
//
// /*
// 世纪通泰公司项目
//  */
// const siteshow_main = resolve => require(['../components/project/siteshow/main.vue'], resolve)
// const siteshow_backend = resolve => require(['../components/project/siteshow/backend.vue'], resolve)

// /*
// truesign-project 工作区
//  */
// /*
// website 网站工作区

const website_main = resolve => require(['../components/project/website_app/main.vue'], resolve)
const website_index = resolve => require(['../components/project/website_app/index.vue'], resolve)
const website_ab0utme = resolve => require(['../components/project/website_app/ab0utme.vue'], resolve)
const website_app_square = resolve => require(['../components/project/website_app/app_square.vue'], resolve)

//website手机端
const m_website_main = resolve => require(['../components/project/m_website_app/main.vue'], resolve)
// const m_website_index = resolve => require(['../components/project/m_website_app/index.vue'], resolve)
// const m_website_app_square = resolve => require(['../components/project/m_website_app/app_square.vue'], resolve)
//
// const apps = resolve => require(['../components/project/website_app/apps/apps.vue'], resolve)
const shadowsocks = resolve => require(['../components/project/website_app/apps/shadowsocks.vue'], resolve)
// const wechat = resolve => require(['../components/project/website_app/apps/wechat.vue'], resolve)
// const chat = resolve => require(['../components/project/website_app/apps/chat.vue'], resolve)


const phone_model = resolve => require(['../components/common/phone_model.vue'], resolve)
const page_model = resolve => require(['../components/common/page_model.vue'], resolve)
const wechat_marketing = resolve => require(['../components/project/wechat_marketing/index.vue'], resolve)
const form = resolve => require(['../components/project/wechat_marketing/form.vue'], resolve)
const main_page = resolve => require(['../components/project/wechat_marketing/main_page.vue'], resolve)
const marketing_product = resolve => require(['../components/project/wechat_marketing/product.vue'], resolve)
const wechat_marketing_backend = resolve => require(['../components/project/wechat_marketing/pagebackend/index.vue'], resolve)
const w_m_b_site_ctrl = resolve => require(['../components/project/wechat_marketing/pagebackend/site_ctrl.vue'], resolve)
const w_m_b_business_client_ctrl = resolve => require(['../components/project/wechat_marketing/pagebackend/business_client_ctrl.vue'], resolve)
const w_m_b_agent_ctrl = resolve => require(['../components/project/wechat_marketing/pagebackend/agent_ctrl.vue'], resolve)
const w_m_b_extend_ctrl = resolve => require(['../components/project/wechat_marketing/pagebackend/extend_ctrl.vue'], resolve)
const w_m_b_fun_ctrl = resolve => require(['../components/project/wechat_marketing/pagebackend/fun_ctrl.vue'], resolve)
// const w_m_b_wechat_content_ctrl = resolve => require(['../components/project/wechat_marketing/pagebackend/wechat_content_ctrl.vue'], resolve)
const w_m_b_weimob_ctrl = resolve => require(['../components/project/wechat_marketing/pagebackend/weimob_ctrl.vue'], resolve)


const wechat_marketing_fun = resolve => require(['../components/project/wechat_marketing/fun/fun.vue'], resolve)
const wechat_marketing_fun_aboutus = resolve => require(['../components/project/wechat_marketing/fun/aboutus.vue'], resolve)
const wechat_marketing_template = resolve => require(['../components/project/wechat_marketing/template/template.vue'], resolve)


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
            // {
            //     name: 'VueQuillEditor',
            //     path: 'VueQuillEditor',
            //     meta: {
            //         title: 'VueQuillEditor'
            //     },
            //     component: VueQuillEditor
            // },
            // {
            //     name: 'vue2editor',
            //     path: 'vue2editor',
            //     meta: {
            //         title: 'vue2editor'
            //     },
            //     component: vue2editor
            // },
            // {
            //     name: 'wangeditor',
            //     path: 'wangeditor',
            //     meta: {
            //         title: 'wangeditor'
            //     },
            //     component: wangeditor
            //
            // },
            {
                name: 'wangeditor_new',
                path: 'wangeditor_new',
                meta: {
                    title: 'wangeditor_new'
                },
                component: wangeditor_new

            },
            {
                name: 'table_model',
                path: 'table_model',
                meta: {
                    title: 'table_model'
                },
                component: table_model

            },
            {
                name: 'table_model_dev',
                path: 'table_model_dev',
                meta: {
                    title: 'table_model_dev'
                },
                component: table_model_dev

            }

        ]
    },
    // {
    //     name: 'common',
    //     path: '/common',
    //     meta: {
    //         title: 'common'
    //     },
    //     component: common,
    //     children:[
    //         {
    //             name: 'echart',
    //             path: 'echart',
    //             meta: {
    //                 title: 'echart'
    //             },
    //             component: echart
    //         },
    //
    //     ]
    // },
    // {
    //     name: 'mainpage',
    //     path: '/mainpage',
    //     meta: {
    //         title: 'mainpage'
    //     },
    //     component:mainpage,
    //     children:[
    //         {
    //             name: 'fullpage',
    //             path: 'fullpage',
    //             meta: {
    //                 title: 'fullpage'
    //             },
    //             component: fullpage
    //         },
    //     ]
    //
    // },
    {
        name: 'Test',
        path: '/test',
        meta: {
            title: 'test'
        },
        component: test
    },
    //     children:[
    //         {
    //             name: 'test_child1',
    //             path: 'test_child1',
    //             meta: {
    //                 title: 'test_child1'
    //             },
    //             component: test_child1
    //         },
    //         {
    //             name: 'test_child2',
    //             path: 'test_child2',
    //             meta: {
    //                 title: 'test_child2'
    //             },
    //             component: test_child2
    //         },
    //         {
    //             name: 'line',
    //             path: 'line',
    //             meta: {
    //                 title: 'line'
    //             },
    //             component: line
    //         },
    //         {
    //             name: 'Demo',
    //             path: 'demo',
    //             meta: {
    //                 title: 'demo'
    //             },
    //             component: demo
    //         },
    //
    //         {
    //             name: 'semantic',
    //             path: 'semantic',
    //             meta: {
    //                 title: 'semantic'
    //             },
    //             component: semantic
    //         },
    //         {
    //             name: 'awesome',
    //             path: 'awesome',
    //             meta: {
    //                 title: 'awesome'
    //             },
    //             component: awesome
    //         },
            // {
            //     name: 'alert',
            //     path: 'alert',
            //     meta: {
            //         title: 'alert'
            //     },
            //     component: alert
            // },
            // {
            //     name: 'vuex',
            //     path: 'vuex',
            //     meta: {
            //         title: 'vuex'
            //     },
            //     component: vuex
            // },
            // {
            //     name: 'vuex2',
            //     path: 'vuex2',
            //     meta: {
            //         title: 'vuex2'
            //     },
            //     component: vuex2
            // },
            //
            //
            // {
            //     name: 'websocket',
            //     path: 'websocket',
            //     meta: {
            //         title: 'websocket'
            //     },
            //     component: websocket
            // },
            // {
            //     name: 'socket-io',
            //     path: 'socket-io',
            //     meta: {
            //         title: 'socket-io'
            //     },
            //     component: socket_io
            // },
            //
            // {
            //     name: 'vux',
            //     path: 'vux',
            //     meta: {
            //         title: 'vux'
            //     },
            //     component: vux
            // },
            // {
            //     name: 'webgl',
            //     path: 'webgl',
            //     meta: {
            //         title: 'webgl'
            //     },
            //     component: webgl
            // },
            // {
            //     name: 'echat',
            //     path: 'echat',
            //     meta: {
            //         title: 'echat'
            //     },
            //     component: echat
            // },


        // ]

    // },
    {
        name: 'project',
        path: '/project',
        meta: {
            title: 'project'
        },
        component: project,
        children:[
            // {
            //     name: 'element',
            //     path: 'element',
            //     meta: {
            //         title: 'element'
            //     },
            //     component: element
            // },
            // {
            //     name: 'siteshow_main',
            //     path: 'siteshow_main',
            //     meta: {
            //         title: '世纪通泰科技有限公司主页'
            //     },
            //     component: siteshow_main
            // },
            // {
            //     name: 'siteshow_backend',
            //     path: 'siteshow_backend',
            //     meta: {
            //         title: '世纪通泰科技有限公司后台'
            //     },
            //     component: siteshow_backend
            // },
            // {
            //   name: 'gdmap',
            //   path: 'gdmap',
            //   meta: {
            //     title: 'gdmap'
            //   },
            //   component: gdmap
            // },
            // {
            //     name: 'video',
            //     path: 'video',
            //     meta: {
            //         title: 'video'
            //     },
            //     component: video
            // },
            //
            // {
            //     name: 'mint-ui',
            //     path: 'mint-ui',
            //     meta: {
            //         title: 'mint-ui'
            //     },
            //     component: mint_ui
            // },
            // {
            //   name: 'canvas',
            //   path: 'canvas',
            //   meta: {
            //     title: 'canvas'
            //   },
            //   component: canvas
            // },
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
                    // {
                    //     name: 'wechat',
                    //     path: 'wechat',
                    //     meta: {
                    //         title: 'wechat'
                    //     },
                    //     component: wechat
                    // },
                    // {
                    //     name: 'apps/chat',
                    //     path: 'apps/chat',
                    //     meta: {
                    //         title: 'apps/chat'
                    //     },
                    //     component: chat
                    // },
                    {
                        name: 'apps/shadowsocks',
                        path: 'apps/shadowsocks',
                        meta: {
                            title: 'apps/shadowsocks'
                        },
                        component: shadowsocks
                    },
                ]
            },
            {
                name: 'website_ab0utme',
                path: 'website_ab0utme',
                meta: {
                    title: 'website_ab0utme'
                },
                component: website_ab0utme,

            },
            {
                name: 'm_website_main',
                path: 'm_website_main',
                meta: {
                    title: 'm_website_main'
                },
                component: m_website_main,
                // children:[
                //     {
                //         name: 'm_website_index',
                //         path: 'm_website_index',
                //         meta: {
                //             title: 'm_website_index'
                //         },
                //         component: m_website_index
                //     },
                //     {
                //         name: 'm_website_app_square',
                //         path: 'm_website_app_square',
                //         meta: {
                //             title: 'm_website_app_square'
                //         },
                //         component: m_website_app_square,
                //     },
                // ]
            },


            // router_spa
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
                name: 'loading_canvas',
                path: 'loading_canvas',
                meta: {
                    title: 'loading_canvas'
                },
                component: loading_canvas
            },
            {
                name: 'loading_canvas_backup',
                path: 'loading_canvas_backup',
                meta: {
                    title: 'loading_canvas_backup'
                },
                component: loading_canvas_backup
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
                    name: 'threejs_wormhole',
                    path: 'threejs_wormhole',
                    meta: {
                        title: 'threejs_wormhole'
                    },
                    component: threejs_wormhole
                },
                {
                    name: 'css3d_periodictable',
                    path: 'css3d_periodictable',
                    meta: {
                        title: 'css3d_periodictable'
                    },
                    component: css3d_periodictable
                },
                {
                    name: 'canvas_materials_video',
                    path: 'canvas_materials_video',
                    meta: {
                        title: 'canvas_materials_video'
                    },
                    component: canvas_materials_video
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
    // {
    //     name: 'loading',
    //     path: '/loading',
    //     meta: {
    //         title: 'loading'
    //     },
    //     component: loading,
    //     children:[
    //         {
    //             name: 'effect_line',
    //             path: 'effect_line',
    //             meta: {
    //                 title: 'effect_line'
    //             },
    //             component: effect_line
    //         },
    //         {
    //             name: 'effect_logo',
    //             path: 'effect_logo',
    //             meta: {
    //                 title: 'effect_logo'
    //             },
    //             component: effect_logo
    //         },
    //         {
    //             name: 'svg',
    //             path: 'svg',
    //             meta: {
    //                 title: 'svg'
    //             },
    //             component: svg
    //         },
    //
    //     ]
    // },
    // {
    //     name: 'conn',
    //     path: '/conn',
    //     meta: {
    //         title: 'conn'
    //     },
    //     component: conn,
    //     children:[
    //         {
    //             name: 'initSocket',
    //             path: 'initSocket',
    //             meta: {
    //                 title: 'initSocket'
    //             },
    //             component: initSocket
    //         },
    //     ]
    // },
    // {
    //     name: 'help_window_resize',
    //     path: '/help_window_resize',
    //     meta: {
    //         title: 'help_window_resize'
    //     },
    //     component: help_window_resize,
    //
    //
    // },

    {
            name: 'wechat_marketing',
            path: '/wechat_marketing',
            meta: {
                title: 'wechat_marketing'
            },
            component: wechat_marketing,
            children:[
                {
                    name: 'form',
                    path: 'form',
                    meta: {
                        title: 'form'
                    },
                    component: form
                },
                {
                    name: 'main_page',
                    path: 'main_page',
                    meta: {
                        title: 'main_page'
                    },
                    component: main_page
                },
                {
                    name: 'marketing_product',
                    path: 'marketing_product',
                    meta: {
                        title: 'marketing_product'
                    },
                    component: marketing_product
                },

            ]
        },
    {
        name: 'wechat_marketing_backend',
        path: '/wechat_marketing_backend',
        meta: {
            title: 'wechat_marketing_backend'
        },
        component: wechat_marketing_backend,
        children:[
            {
                name: 'w_m_b_site_ctrl',
                path: 'w_m_b_site_ctrl',
                meta: {
                    title: 'w_m_b_site_ctrl'
                },
                component: w_m_b_site_ctrl
            },
            {
                name: 'w_m_b_business_client_ctrl',
                path: 'w_m_b_business_client_ctrl',
                meta: {
                    title: 'w_m_b_business_client_ctrl'
                },
                component: w_m_b_business_client_ctrl
            },
            {
                name: 'w_m_b_weimob_ctrl',
                path: 'w_m_b_weimob_ctrl',
                meta: {
                    title: 'w_m_b_weimob_ctrl'
                },
                component: w_m_b_weimob_ctrl
            },
            // {
            //     name: 'w_m_b_wechat_content_ctrl',
            //     path: 'w_m_b_wechat_content_ctrl',
            //     meta: {
            //         title: 'w_m_b_wechat_content_ctrl'
            //     },
            //     component: w_m_b_wechat_content_ctrl
            // },
            {
                name: 'w_m_b_fun_ctrl',
                path: 'w_m_b_fun_ctrl',
                meta: {
                    title: 'w_m_b_fun_ctrl'
                },
                component: w_m_b_fun_ctrl
            },
            {
                name: 'w_m_b_agent_ctrl',
                path: 'w_m_b_agent_ctrl',
                meta: {
                    title: 'w_m_b_agent_ctrl'
                },
                component: w_m_b_agent_ctrl
            },
            {
                name: 'w_m_b_extend_ctrl',
                path: 'w_m_b_extend_ctrl',
                meta: {
                    title: 'w_m_b_extend_ctrl'
                },
                component: w_m_b_extend_ctrl
            },

        ]
    },
    {
        name: 'wechat_marketing_fun',
        path: '/wechat_marketing_fun',
        meta: {
            title: 'wechat_marketing_fun'
        },
        component: wechat_marketing_fun,
        children:[
            {
                name: 'wechat_marketing_fun_aboutus',
                path: 'wechat_marketing_fun_aboutus',
                meta: {
                    title: 'wechat_marketing_fun_aboutus'
                },
                component: wechat_marketing_fun_aboutus
            },
        ]
    },
    {
        name: 'wechat_marketing_template',
        path: '/wechat_marketing_template',
        meta: {
            title: 'wechat_marketing_template'
        },
        component: wechat_marketing_template
    },
    {
        name: 'phone_model',
        path: '/phone_model',
        meta: {
            title: 'phone_model'
        },
        component: phone_model,
    },
    {
        name: 'page_model',
        path: '/page_model',
        meta: {
            title: 'page_model'
        },
        component: page_model,
    },



]

export default routes
