<template>
    <div class="top_router_view" id="accountInfo" style="overflow: auto">
        账户信息管理


    </div>
</template>


<script>

  import page_model from '../../../common/page_model.vue'
  import table_model from '../../../common/table_model.vue'
  import Vue from 'vue'
  import { mapGetters, mapActions } from 'vuex'
  import axios from 'axios'
  import { axios_config } from '../../../../api/axiosApi'
  import { dbResponseAnalysis2WidgetData } from '../../../../api/lib/helper/dataAnalysis'
  import phone_model from '../../../common/phone_model.vue'
  export default {
    data(){
      return {}
    },
    watch: {

    },
    props: {},
    components: {
      page_model,
      table_model,
      phone_model
    },
    computed: {
      ...mapGetters([
        'wechat_marketing_store',
      ])
    },
    created(){

    },
    mounted(){

    },
    beforeDestroy(){

    },
    destroyed(){
    },
    methods: {}
  }
</script>

<style lang="stylus" rel="stylesheet/stylus">
    .el-tabs__header {
        box-shadow: 0 0 15px rgba(93, 100, 124, 0.38) !important;

    }

    .tab_content {
        width: 100%;
        height: auto;
        min-height: 600px;
        text-align: left;
        overflow-y scroll
    }

</style>
