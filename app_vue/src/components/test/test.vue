<template>
    <div class="top_router_view" style="text-align: center;overflow: auto">
        <input type="text" style="padding: 5px 15px;width: 30%;background-color: grey;color: white" v-model="url">
        <input type="button" style="padding: 5px 15px;background-color: gray;" value="发送" @click = send>
        <div>{{res}}</div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                url:'',
                res:''
            }
        },
        created(){

        },
        mounted(){
            let list = []

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
        components: {},
        watch: {},

    }
</script>
<style>

</style>
