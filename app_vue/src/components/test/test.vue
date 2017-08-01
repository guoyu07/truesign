<template>
    <div class="top_router_view" style="text-align: center;overflow: auto">
        <textarea v-model="text" style="width: 600px;height: 300px"></textarea>
        <hr>
        <div>{{ find }}</div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                text:'## DispatcherServlet  \n'+
                '# 321fwaervlet  \n'+
                '# fwaervlet  \n'+
                '# 321321afwaervlet  \n'+
                '# 321321afwaervlet  \n'+
                '>1. DispatcherServlet  的初始化过程中，Spring MVC会在你web应用的 WEB-INF  目录下查找一个名为[servlet-name]-servlet.xml的配置文件，并创建其中所定义的bean。如果在全局上下文中存在相同名字的bean，则它们将被新定义的同名bean覆盖。'+
            '>2. `WebApplicationContext`被绑定在`ServletContext`中.如果需要获取它，你可以通过 RequestContextUtils工具类中的静态方法来拿到这个web应用的上下文`WebApplicationContext',
                res:'',
                find:'',
                regex:/[!#]+((#){1})+((\s){1})+(.+)/
            }
        },
        created(){

        },
        mounted(){


            this.find = this.regex.exec(this.text)

        },
        methods: {
            send(){
                var vm = this
                this.$http.post('http://'+this.url, {}, this.$http_config)
                    .then((res) => {
                        console.log(res)
                        vm.res = res
                    })
            }
        },
        updated(){
        },

        components: {},
        watch: {},

    }
</script>
<style>

</style>
