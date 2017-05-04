<template>

    <div class="appcard" style="color: white;font-size: 22px;font-weight: 600;" :data-index="dataIndex" @mouseover="overthis($event)" @mouseout="outthis($event)">
        <div  v-if="level<itemData.applevel" class="levellimit"
              style="position: absolute;width: 320px;height: 180px;z-index:15;background-color: rgba(255,255,255,0.44);line-height: 180px;text-align: center;color: black">
            权限低于限定标准
        </div>


        <div   v-if="level>=itemData.applevel" class="enter-app" @click="enterapp($event)" :data-index="dataIndex" :data-id="itemData.document_id"
              style="">
            进入应用
        </div>
        <div style="position: absolute;" >
            <img :src="itemData.appimg" width="320px" height="180px;" style="">

        </div>
        <div class="ctrlbar" v-if="parseInt(level)>=3 && ctrl" style="position: absolute;z-index: 30">
            <input type="file" class="file" style="display: none"  :data-index="dataIndex" :data-id="itemData.document_id" @change="onFileChange($event)">
            <input class="input-btn" type="button" value="修改图片" v-on:click.stop.prevent="changeimg($event)" :data-index="dataIndex" :data-id="itemData.document_id" style="padding-bottom: 5px">
            <input v-if="itemData.document_id" type="button" value="保存修改"  v-on:click.stop.prevent="updateCard($event)" :data-index="dataIndex" :data-id="itemData.document_id">
            <input v-else="itemData.document_id" type="button" value="邢增修改"  v-on:click.stop.prevent="newaddCard($event)" :data-index="dataIndex" :data-id="itemData.document_id">
            <input v-if="itemData.document_id" type="button" value="删除app"  v-on:click.stop.prevent="delCard($event)" :data-index="dataIndex" :data-id="itemData.document_id">
        </div>
        <div class="card-show" style=""  >
            <div v-if="parseInt(level)>=3 && ctrl" style="position: absolute;top:30px;left:0;width: 100%;height: 70%;">
                <label style="font-size: 16px" for="CardName">name:</label>
                <input id="CardName" class="CardCol" v-model="itemData.appname" style="display: inline-block">
                <label style="font-size: 16px" for="CardType">type:</label>
                <input id="CardType" class="CardCol" v-model="itemData.apptype" style="display: inline-block">
                <label style="font-size: 16px" for="CardLevel">level:</label>
                <input  id="CardLevel" class=" CardCol"  v-model="itemData.applevel">
                <label style="font-size: 16px" for="CardTable">table:</label>
                <input id="CardTable" class=" CardCol" v-model="itemData.apptable">
            </div>

            <div v-if="parseInt(level)>=3 && ctrl" class="card-content">
                    <!-- quill-editor -->
                <vue-editor :id="'editor_'+dataIndex" v-model="itemData.apptitle" :editorToolbar="customToolbar"

                ></vue-editor>
            </div>
            <div v-else="parseInt(level)>=3 && ctrl" class="card-content">
                <span style="position: absolute;right:5px ;width: 30px;height: 30px;color: whitesmoke;border-radius: 15px;background-color: rgba(137,161,192,0.42);line-height: 30px">
                    {{itemData.applevel}}
                </span>
                <div  v-html="itemData.apptitle"></div>
            </div>

        </div>


    </div>

</template>



<script>
    import { VueEditor } from 'vue2-editor'

//    import LocalVoucher from '../../api/LocalVoucherTools'



    export default {
        data() {
            return {
                customToolbar: [
                    [{ 'header': 1 }, { 'header': 2 }],               // custom button values
                    [{ 'color': [] }],          // dropdown with defaults from theme
                    [{ 'align': [] }],
                ],
                imgfile:'',
                oss_lock:false,
            }
        },
        props:[
            'itemData',
            'dataIndex',
            'level',
            'ctrl'
        ],
        created(){


        },
        methods: {

            overthis(e){
                var target = $(e.currentTarget)
                target.find('.enter-app').css('visibility','visible')
            },
            outthis(e){
                var target = $(e.currentTarget)

                target.find('.enter-app').css('visibility','hidden')
            },
            enterapp(e){
                var vm = this
                var target = $(e.currentTarget)
                var target_index = target.attr('data-index')
                var target_id = target.attr('data-id')
                var target_name = this.itemData.appname
                this.$router.push('apps/'+target_name)
//                this.$router.push('apps/shadowsocks')
            },
            delCard(e){
                var vm = this
                var target = $(e.currentTarget)
                var target_index = target.attr('data-index')
                var target_id = target.attr('data-id')
                var payload_data =vm.itemData
                var params = {
                    to:null,
                    payload_type:'delAppRule',
                    payload_data:payload_data,
                    yaf:{
                        module:'index',
                        controller:'apps',
                        action:'delAppRule'
                    }
                }
                console.log('delAppRule->param:',params)
                vm.$root.eventHub.$emit('socket_send',params)
            },
            updateCard(e){
                var vm = this
                var target = $(e.currentTarget)
                var target_index = target.attr('data-index')
                var target_id = target.attr('data-id')
                var payload_data = vm.itemData
                var params = {
                    to:null,
                    payload_type:'updateAppRule',
                    payload_data:payload_data,
                    yaf:{
                        module:'index',
                        controller:'apps',
                        action:'updateAppRule'
                    }
                }
                console.log('updateAppRule->param:',params)
                vm.$root.eventHub.$emit('socket_send',params)

            },
            newaddCard(e){
                var vm = this
                var target = $(e.currentTarget)
                var target_index = target.attr('data-index')
                var payload_data = vm.itemData
                var params = {
                    to:null,
                    payload_type:'addAppRule',
                    payload_data:payload_data,
                    yaf:{
                        module:'index',
                        controller:'apps',
                        action:'addAppRule'
                    }
                }
                console.log('addAppRule->param:',params)
                vm.$root.eventHub.$emit('socket_send',params)
            },
            changeimg(e){
                var target = $(e.currentTarget)
                var target_index = target.attr('data-index')
                $('.file[data-index='+target_index+']').click()

            },
            onFileChange(e){
                var vm = this
                var target = $(e.currentTarget)
                var target_index = target.attr('data-index')
                var target_id = target.attr('data-id')
                var files = e.target.files || e.dataTransfer.files;
                if (!files.length){
                    return;
                }
                this.imgfile = files
                var imgfilename = files[0].name
                var pre_change_params = {
                    filename:files[0].name,
                    imgfile:files,
                    target_index:target_index,
                    target_id:target_id,
                    unique_auth_code:vm.unique_auth_code,
                    website_encryption_key:vm.website_encryption_key,
                    type:'app_img_logo',
                }
                this.$root.eventHub.$emit('app_img_change_event',pre_change_params)

            }
        },


        mounted(){


        },
        watch:{

        },

        computed:{

        },
        components:{
            VueEditor

        },

    }
</script>
<style lang="stylus" rel="stylesheet/stylus">
.enter-app
    position: absolute;
    width: 120px;
    height: 30px;
    margin-left:100px;
    margin-top:75px;
    z-index:15;
    background-color: rgba(101,101,101,0.42);
    line-height: 30px;
    font-size:18px;
    text-align: center;
    color: white;
    box-shadow: 0 0 10px #fffefd
    cursor pointer
    visibility hidden
label
    display:inline-block;
    width:70px;
.CardCol

    font-size 16px
    border-bottom  1px solid whitesmoke
    width 70%
    right 0
    color black !important
    background-color rgba(245, 245, 245, 0.42) !important
.CardCol:hover
    color black !important
.input-btn:hover
    background-color rgba(220, 220, 220, 0.34) !important
.ctrlbar
    position absolute
    font-size 12px
    border 1px solid whitesmoke
.appcard
    width: 320px
    height: 180px;
    background-color: rgba(62, 160, 163, 0.2);
    display: inline-block;
    margin 8px 1%
    /*border: 2px solid white;*/
    box-shadow: 0 0 10px #57DCDF
    overflow: hidden !important

    .card-show
        width: 320px;
        height 180px
        position: absolute;
        /*cursor pointer*/
        box-sizing:border-box;
        text-align center
        overflow hidden
        transition all 1.5s
        .card-content
            background-color: rgba(20,40,41,0.56);
            height 100%
            transform translateY(80%)
            transition all 1s
            padding-top 5px
            cursor pointer
        .card-content:hover
            transform translateY(0%)


</style>
