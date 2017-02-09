// import Home from './components/Home.vue'
// import Article from './components/Article.vue'
// import Demo from './components/Demo.vue'
// import DemoVuexState from './components/DemoVuexState.vue'

const routes = [
    {
        name: 'Home',
        path: '/',
        meta: {
            title: 'home'
        },
        component: require('../components/Home.vue')
    },
    {
        name: 'Demo',
        path: '/demo',
        meta: {
            title: 'demo'
        },
        component: require('../components/Demo.vue')
    },
    {
        name: 'semantic',
        path: '/semantic',
        meta: {
            title: 'semantic'
        },
        component: require('../components/semantic.vue')
    },
    {
        name: 'awesome',
        path: '/awesome',
        meta: {
            title: 'awesome'
        },
        component: require('../components/awesome.vue')
    },
    {
        name: 'alert',
        path: '/alert',
        meta: {
            title: 'alert'
        },
        component: require('../components/alert.vue')
    },
    {
        name: 'vuex',
        path: '/vuex',
        meta: {
            title: 'vuex'
        },
        component: require('../components/vuex.vue')
    },
    {
        name: 'vuex2',
        path: '/vuex2',
        meta: {
            title: 'vuex2'
        },
        component: require('../components/vuex2.vue')
    },
    {
        name: 'mint-ui',
        path: '/mint-ui',
        meta: {
            title: 'mint-ui'
        },
        component: require('../components/mint-ui.vue')
    },

    {
        name: 'websocket',
        path: '/websocket',
        meta: {
            title: 'websocket'
        },
        component: require('../components/websocket.vue')
    },
    {
        name: 'socket-io',
        path: '/socket-io',
        meta: {
            title: 'socket-io'
        },
        component: require('../components/socket-io.vue')
    },
    {
        name: 'iview',
        path: '/iview',
        meta: {
            title: 'iview'
        },
        component: require('../components/iview.vue')
    },
    {
        name: 'vux',
        path: '/vux',
        meta: {
            title: 'vux'
        },
        component: require('../components/vux.vue')
    },
    {
        name: 'webgl',
        path: '/webgl',
        meta: {
            title: 'webgl'
        },
        component: require('../components/webgl.vue')
    },
    {
        name: 'echat',
        path: '/echat',
        meta: {
            title: 'echat'
        },
        component: require('../components/echat.vue')
    },

]
export default routes
