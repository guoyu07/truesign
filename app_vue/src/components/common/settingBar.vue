<template>
    <div id="settingbar" :style="{right: settingbar_marginRight+'px'}">

        <div id="mini-setting-bar">
            <i class="settingbar-trigger" @mouseover="overthis" @click="outthis"  style="position: absolute;">身份资料</i>
            <div class="min-info-bar" style="width: 100%;height: 100px;">
                <div style="width: 100px;height: 100%;">
                    <img :src="website.website_user.headpic" width="100%" height="100%">
                </div>
                <div style="width: 200px;height: 100%;">
                    <ol>
                        <li>{{website.website_user.username}}</li>
                        <li style="font-size: 18px"><label>权限</label>{{website.website_user.website_level }}</li>
                    </ol>
                </div>
            </div>
            <div class="info-ctrl-bar" style="width: 100%;height: 100px;">
                <div style="width: 100px;height: 100%;">
                    <ol >
                        <li style="">设定中心</li>
                        <li>消息中心</li>
                    </ol>
                </div>
                <div style="width: 180px;height: 100%;position: relative">
                    <effectlogo logo_pos="relative_center"  logo_width="50"></effectlogo>

                </div>
            </div>
        </div>
    </div>
</template>



<script>
    import { mapGetters,mapActions } from 'vuex'
    import effectlogo from '../loading/effect_logo.vue'


    export default {
        data() {
            return{
                settingbar_marginRight:-335
            }
        },
        props:[

        ],
        mounted(){



        },
        methods:{
            ...mapActions([
                'updateWebSite',
                'updateSysInfo',
                'updateAppRules',
            ]),
            overthis(){
                this.settingbar_marginRight = 0
            },
            outthis(){
                this.settingbar_marginRight = -335
            }
        },
        computed:{
            ...mapGetters([
                'apprules',
                'website',
                'sysinfo'
            ]),
        },
        components:{
            effectlogo,

        },

    }
</script>
<style lang="stylus" rel="stylesheet/stylus">
.settingbar-trigger
    position absolute
    color white
    display block
    width 80px;
    height 20px
    background-color rgba(73, 73, 73, 0.63)
    text-align left
    margin-left -80px
    border-radius 5px
    box-shadow: 0 0 10px #fffefd
    line-height 20px
    cursor pointer
    border-right 15px solid orange

#settingbar
    position absolute
    background-color rgba(35, 35, 35, 0.95)
    box-shadow: 0 0 10px #fffefd
    border 15px solid rgba(35, 35, 35, 0.15)
    border-radius 8px
    transition all 1.5s


    #mini-setting-bar
        width 300px
        height 200px
        display flex
        flex-direction: column;
        /*overflow hidden*/
        .min-info-bar
            display flex
        .min-info-bar li
            width: 100%;
            text-transform: uppercase;
            background: 0 0;
            font-family: 'Graphik Web',sans-serif;
            font-style: normal;
            font-stretch: normal;
            font-size: 1em;
            color: #00B5AD !important
            font-weight 800
            letter-spacing: 1.6px;
            font-size 24px;
            text-align center
            line-height 50px
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            outline: 0;
        .info-ctrl-bar
            display flex
        .info-ctrl-bar li
            margin-top 5px
            padding 5px 10px
            cursor:pointer;
            width: 100px;
            text-align: center;
            color: rgba(82,203,214,0.65);
            line-height: 30px;
            font-size: 14px;
            letter-spacing: 2px;
            font-family: 'Graphik Web',sans-serif;
        .info-ctrl-bar li:hover
            background-color: rgba(203, 203, 203, 0.54)
            border-radius 5px
        .info-ctrl-bar .top_router_view
            width auto
            height auto


/*flex-direction: row*/
        /*justify-content:center*/
        /*align-items:center*/
        /*align-content:center*/

</style>
