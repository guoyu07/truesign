<template>
    <div class="top_router_view" style="overflow: scroll">
        <el-tabs type="border-card" style="background-color: #dcdcdc;box-shadow: none" @tab-click="tabclick"
                 v-model="defaultTab">
            <el-tab-pane v-for="(item,index) in tab_menu_list" :key="item" :label="item.value" :data-name="item.name"
                         :name="item.name">
                <div class="tab_content_custom" style="" v-if="item.name==='平台充值日志'" key="平台充值日志">平台充值日志</div>
                <div class="tab_content_custom" style="" v-if="item.name==='功能数据统计'" key="功能数据统计">功能数据统计</div>
                <div class="tab_content_custom" style="" v-if="item.name==='公众号账户数据统计'" key="公众号账户数据统计">公众号账户数据统计</div>
                <div class="tab_content_custom" style="" v-if="item.name==='公众号发布内容审核、统计'" key="公众号发布内容审核、统计">公众号发布内容审核、统计</div>
                <div class="tab_content_custom" style="" v-if="item.name==='敏感词过滤'" key="敏感词过滤">敏感词过滤</div>

                <div class="tab_content_custom" style="" v-if="item.name==='登录、访问指纹日志'" key="登录、访问指纹日志">
                    <table_model v-loading="tableParams.isloading"
                                 :currect_select="tableParams.table_currect_select"
                                 :element-loading-text="tableParams.loading_text"
                                 :search_sort_by="table_search_sort_by"
                                 :all_data_count="tableParams.all_data_count"
                                 :table_data="tableParams.table_model_data"
                                 :table_field="tableParams.table_model_field"
                                 :info_transfer_action="tableParams.info_transfer_action"
                                 :groupdelable="false"
                                 :show_phone_model="false"
                                 :show_phone_model_key="false"
                    >

                    </table_model>
                </div>
            </el-tab-pane>
        </el-tabs>

    </div>
</template>


<script>
  import table_model from '../../../common/table_model.vue'

  import page_model from '../../../common/page_model.vue'
  import { mapGetters, mapActions } from 'vuex'
  import axios from 'axios'
  import { axios_config } from '../../../../api/axiosApi'
  import { dbResponseAnalysis2WidgetData } from '../../../../api/lib/helper/dataAnalysis'
  export default {
    data(){
      return {
        defaultTab: '平台充值日志',
        tab_menu_list: [
          {
            name: '平台充值日志',
            value: '平台充值日志',
          },
          {
            name: '功能数据统计',
            value: '功能数据统计',
          },
          {
            name: '公众号账户数据统计',
            value: '公众号账户数据统计',
          },
          {
            name: '公众号发布内容审核、统计',
            value: '公众号内容发布审核、统计',
          },
          {
            name: '敏感词过滤',
            value: '敏感词过滤',
          },
          {
            name: '登录、访问指纹日志',
            value: '登录、访问指纹日志',
          },
        ],
        siteinfo: {},

        page_model_data: {},
        table_search_sort_by: {
          page_size: 20,
          page: 1,
          search: {},
          sorter: {},
        },
        tableParams:{
          table_model_data: [],
          table_model_field: {},
          all_data_count: 0,
          show_page_model_ctrl_by_table: false,
          show_adddata_page_model_ctrl: false,
          info_transfer_action: {
            add: 'BusinessCtrl/Descbusinessinfo',
            get: 'BusinessCtrl/getBusinessInfo',
            update: 'BusinessCtrl/UpdateBusinessInfo',
            groupdel: 'BusinessCtrl/GroupDelBusinessInfo'
          },

          table_search_sort_by_status: false,
          isloading: false,
          loading_text: '数据加载中',
          table_currect_select: '',
          email: ''
        }

      }
    },
    props: {},
    watch: {
      table_search_sort_by: {
        handler: function (val, oldVal) {
          this.refresh_table_data(JSON.stringify(this.table_search_sort_by))

        },
        deep: true
      },
      defaultTab: function (val, oldVal) {
        this.refresh_table_data(JSON.stringify(this.table_search_sort_by))
      }
    },
    components: {
      table_model,
      page_model
    },
    computed: {
      ...mapGetters([
        'wechat_marketing_store',
      ])
    },
    created(){
      var vm = this
      this.report_api = this.wechat_marketing_store.apihost + 'ExtendCtrl/'
      this.$root.eventHub.$off('refresh_table')
      this.$root.eventHub.$on('refresh_table', function (data) {
        console.log('on->refresh_table')
        if (data === 'resetselect') {
          vm.reset_search_sort_by()
        }
        else {
          vm.refresh_table_data(data)
        }

      })
    },
    mounted(){

    },
    beforeDestroy(){

    },
    methods: {
      tabclick(e){
        var vm = this
        this.page_model_data = {}
        this.table_model_data = []
        this.table_model_field = {}
        this.all_data_count = 0
        this.reset_search_sort_by()
        this.page_model_data = {}
        vm.defaultTab = e.$el.dataset.name

      },
      reset_search_sort_by(){
        this.table_search_sort_by = {
          page_size: 20,
          page: 1,
          search: {},
          sorter: {},
        }
      },
      refresh_table_data(data){
        if (this.defaultTab === '平台充值日志') {
        }
        else if (this.defaultTab === '功能数据统计') {
        }
        else if (this.defaultTab === '公众号账户数据统计') {
        }
        else if (this.defaultTab === '公众号发布内容审核、统计') {
        }
        else if (this.defaultTab === '敏感词过滤') {
        }
        else if (this.defaultTab === '登录、访问指纹日志') {
          this.getFingerprints(JSON.stringify(this.table_search_sort_by))
        }
      },

      getFingerprints(search_sort_by){
        var vm = this
        var search_param = {}
        if (search_sort_by) {
          search_param.search_sort_by = search_sort_by
        }
        search_param.rules = 1
        this.$http.post(this.report_api + 'getFingerPrints', search_param, this.$http_config)
        //        axios.post(this.report_api + this.info_transfer_action.get, search_param, axios_config)
          .then((res) => {
            if (res.data.code === 0) {
              let analysis_data = dbResponseAnalysis2WidgetData(res.data.response)
              for (let index in analysis_data.searchWidget) {
               /*进行响应式set key*/
                if (!vm.table_search_sort_by.search.hasOwnProperty(analysis_data.searchWidget[index].search_key)) {
                  vm.$set(vm.table_search_sort_by.search, analysis_data.searchWidget[index].search_key, analysis_data.searchWidget[index])
                }
              }
              for (let index in analysis_data.sorterWidget) {
                /* 进行响应式set key*/
                if (!vm.table_search_sort_by.sorter.hasOwnProperty(analysis_data.sorterWidget[index].key)) {
                  vm.$set(vm.table_search_sort_by.sorter, analysis_data.sorterWidget[index].key, analysis_data.sorterWidget[index].way)
                }
              }
              vm.tableParams.info_transfer_action = {
                add: false,
                get: 'ExtendCtrl/getFingerPrints',
                update: 'ExtendCtrl/UpdateNote',
                groupdel: false
              }
              vm.tableParams.table_model_field = analysis_data.rules
              vm.tableParams.table_model_data = analysis_data.data
              vm.tableParams.all_data_count = analysis_data.count
              console.log('tableParams->',vm.tableParams)

//                            console.log(vm.table_search_sort_by)
            }
            else {
              vm.$notify.error({
                title: '失败',
                message: res.data.code + ' ' + res.data.desc,
                offset: 100,
                duration: '2000'
              });
            }

          })

      }
    },

  }
</script>
<style>
    .el-tabs__header {
        box-shadow: 0 0 15px rgba(93, 100, 124, 0.38) !important;

    }
    .tab_content_custom{
        width: 100%;height: auto;min-height: 600px;text-align: left;
    }

</style>
