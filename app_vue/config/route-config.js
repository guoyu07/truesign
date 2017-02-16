import articles from './articlesRoutes.js'
const routes = [
  {
    name: 'Hello',
    path: '/',
    meta: {
      title: 'hello'
    },
    component: require('../components/Hello.vue')
  },
  {
    name: 'Home',
    path: '/home',
    meta: {
      title: 'home'
    },
    component: require('../components/Home.vue')
  },
  {
    name: 'Article',
    path: '/article',
    meta: {
      title: 'article'
    },
    component: require('../components/Article.vue'),
    children: articles
  },
  {
    name: 'Demo',
    path: '/demo',
    meta: {
      title: 'demo'
    },
    component: require('../components/Demo.vue'),
    children: [
      {
        name: 'DemoVuexState',
        path: 'vuex_state',
        meta: {
          title: 'vuex演示'
        },
        component: require('../components/demo/DemoVuexState.vue')
      },
      {
        name: 'FlexGrid',
        path: 'flexgrid',
        meta: {
          title: 'flexGrid'
        },
        component: require('../components/demo/FlexGrid.vue')
      }
    ]
  }
]

export default routes
