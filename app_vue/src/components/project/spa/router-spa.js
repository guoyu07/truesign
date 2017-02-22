/**
 * Created by ql_os on 2017/2/18.
 */

const header = resolve => require(['../base/header.vue'], resolve)
const navbar = resolve => require(['../base/navbar.vue'], resolve)
const sidebar = resolve => require(['../base/sidebar.vue'], resolve)
const content = resolve => require(['../base/content.vue'], resolve)
const footer = resolve => require(['../base/footer.vue'], resolve)
export const  router_spa =
    {
        name: 'spa',
        path: 'spa',
        meta: {
            title: 'spa'
        },
        components: {
            header:header,
            navbar:navbar,
            sidebar:sidebar,
            content:content,
            footer:footer
        }
    }



