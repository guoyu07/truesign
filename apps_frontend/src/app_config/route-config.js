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
    }
]
export default routes
