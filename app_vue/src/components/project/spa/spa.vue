<template>
  <div>
    <router-view   name="header"></router-view>
    <div id="app1"  @click="openFullScreen" v-loading.body="fullscreenLoading">
      123
    </div>
    <div id="app2" @click="openFullScreen">
      456
    </div>

    <router-view  name="navbar"></router-view>
    <router-view  name="sidebar"></router-view>
    <router-view  name="content"></router-view>
    <router-view  name="footer"></router-view>
  </div>
</template>

<style>
  body {
    margin: 0;
  }
</style>

<script>
    import { Loading } from 'element-ui';

    export default {
        data() {
            return {
                is_loading:true,
                loadingInstance : '',
                fullscreenLoading: false
            }
        },
        created(){



        },
        methods: {
            stop_loading(){

                this.is_loading = true
            },
            openFullScreen() {
//                this.fullscreenLoading = false;
//                setTimeout(() => {
//                    this.fullscreenLoading = false;
//                }, 3000);
                var loadingInstance1 = Loading.service({ fullscreen: false,
                    target:"#app1"
                });
                setTimeout(() => {
                    loadingInstance1.close()
                }, 3000);
            }
        },
        mounted(){
//            this.loadingInstance.close()
//          this.is_loading = false;
        }


    };
</script>