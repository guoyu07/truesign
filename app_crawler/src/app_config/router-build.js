/**
 * Created by ql-win on 2017/4/2.
 */
import routes from './route-config'

var router_build = []
routes.forEach(function (v,k) {
    var router_item = []
    router_item.push(
        {
            'name':v.name,
            'path':v.path,
        }
    )
    if(v.children){
        v.children.forEach(function (vv,kk) {
            router_item.push(
                {
                    'name':v.name+'/'+vv.name,
                    'path':v.path+'/'+vv.path,
                }
            )
            if(vv.children){
                vv.children.forEach(function (vvv,kkk) {
                    router_item.push(
                        {
                            'name':v.name+'/'+vv.name+'/'+vvv.name,
                            'path':v.path+'/'+vv.path+'/'+vvv.path,
                        }
                    )
                    if(vvv.children){
                        vvv.children.forEach(function (vvvv,kkkk) {
                            router_item.push(
                                {
                                    'name': v.name + '/' + vv.name + '/' + vvv.name + '/' +vvvv.name,
                                    'path': v.path + '/' + vv.path + '/' + vvv.path + '/' +vvvv.path,
                                }
                            )
                        })
                    }
                })
            }
        })
    }
router_build.push(router_item)
})


export  default router_build
