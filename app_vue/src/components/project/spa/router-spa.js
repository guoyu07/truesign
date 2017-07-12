/**
 * Created by ql_os on 2017/2/18.
 */

const spa_header = resolve => require(['../base/header.vue'], resolve)
const spa_navbar = resolve => require(['../base/navbar.vue'], resolve)
const spa_sidebar = resolve => require(['../base/sidebar.vue'], resolve)
const spa_content = resolve => require(['../base/content.vue'], resolve)
const spa_footer = resolve => require(['../base/footer.vue'], resolve)
export const  router_spa =
    {
        name: 'spa',
        path: 'spa',
        meta: {
            title: 'spa'
        },
        components: {
            spa_header:spa_header,
            spa_navbar:spa_navbar,
            spa_sidebar:spa_sidebar,
            spa_content:spa_content,
            spa_footer:spa_footer
        }
    }



