<template>
    <div class="top_router_view" style="background-color: transparent;text-align: center;overflow: auto;">
        <p class="top_router_tip" style="opacity: 0.2">wechat_marketing</p>
        <navmenu :logo="logo" :menulist="menulist"></navmenu>
        <div style="min-width: 1200px;width: 100%;background-color: transparent;height: 100%;margin: 0 auto;marigin-top: 70px;">
            <transition name="el-zoom-in-top">
                <router-view></router-view>
            </transition>
        </div>

    </div>
</template>


<script>
  import navmenu from '../../common/navmenu.vue'
  import { wechat_marketing_apihost } from '../../../app_config/base_config'
  import { mapGetters, mapActions } from 'vuex'
  export default {
    data(){
      return {
        logo: 'https://res.wx.qq.com/mpres/htmledition/images/bg/bg_logo318e8e.png',
        menulist: {
          main_page: '首页',
          marketing_product: '产品',
          form: '登陆∞注册',
        },

      }
    },

    components: {
      navmenu,

    },
    computed: {
      ...mapGetters([
        'wechat_marketing_store',
      ]),

    },
    watch: {
//      wechat_marketing_store: {
//        handler: function (val, oldVal) {
//
//         console.log('wechat_marketing_store->change')
//        },
//        deep: true
//      },
    },
    created(){
      this.updateWechat_marketing_store({
        apihost: wechat_marketing_apihost
      })
      if (this.$route.path === '/wechat_marketing') {
        this.$router.push('/wechat_marketing/main_page')
      }
    },
    mounted(){

      var vm = this
      this.$root.eventHub.$on('changeNavMenu', function (data) {
        switch (data) {
          case 0:
            console.log(data)
            break;
          case 1:
            console.log(data)

            break;
          case 2:
            console.log(data)

            break;
          case 3:
            console.log(data)

            break;
          default:
            console.log(data)

        }
      })

    },
    beforeUpdate(){
        console.log('updated')
        console.log(this.$store.getters.wechat_marketing_store)
        console.log(this.wechat_marketing_store)
    },

    beforeDestroy(){

    },
    methods: {

      ...mapActions([
        'updateWechat_marketing_store',
      ]),
    },

  }
</script>
<style>

    .code_matrix_container {
        display: flex;
        flex-wrap: wrap;
        width: 238px;
        margin-top: 10px;
    }

    .code_matrix_item {
        display: flex;
        justify-content: space-around;
        align-items: center;
        width: 25px;
        height: 25px;
        border: 1px solid #aaa;
        margin-right: -1px;
        margin-bottom: -1px;
    }

    .code_matrix_item:nth-child(3n) {
        margin-right: 0;
    }

    .code_matrix_item:nth-child(27n) {
        margin-bottom: 0;
    }

    .code_matrix_item-move {
        transition: transform 1s;
    }

    #loding_log_show p {
        width: 50%;
        text-align: right;
    }

    .fade-show-enter-active {
        transition: all 1s;
        opacity: 1;

    }

    .fade-show-enter {
        transition: all 1s;

        opacity: 0;

    }

    .fade-show-leave {
        transition: all 1s;

        opacity: 1;
    }

    .fade-show-leave-active {
        transition: all 1s;

        opacity: 0;

    }
</style>
