<template>
        <div class="phone_model" @mousedown="startdragthis($event)" @mouseup="overdragthis(false,$event)">
                <iframe name="phone_model_iframe" class="demo-page phone_model_iframe" frameborder="0"   :src="currect_mobile_show_uri" @click="iframeclick"></iframe>
                <input @click="iframe_click_back" type="button" class="phone_model_button">
        </div>

</template>


<script>


    export default {
        data(){
            return {
                currect_mobile_show_uri:'http://wap.baidu.com',
                drag_param:{
                    pos_pageY:0,
                    chat_msg_height:0,

                    //
                    currentDisTance : 20,
                    resizeMode : 0,
                    isResize : false,
                    isDrag: false,
                    isStartResize : false,
                    currentDisX:'',
                    currentDisY:'',
                    offsetWidth:'',
                    pull_pointX:'',
                },
            }
        },
        props: {
            mobile_show_uri:{
                type: String,
                default: 'http://wap.baidu.com',
            },

        },
        watch: {
            mobile_show_uri: {
                handler: function (val, oldVal) {
                   this.currect_mobile_show_uri = this.mobile_show_uri

                },
                deep: true
            }
        },
        components: {

        },
        computed: {},
        created(){

        },
        mounted(){


        },
        beforeDestroy(){

        },
        methods: {
            startdragthis(e){
                //console.log('startdragthis')
                var vm =this
                var $target = $(e.currentTarget)
                if($target.attr('data-type') === 'chat-msg-pos-ctrl-bar'){
                    var me = $target
                    var pos_pageY = e.pageY
                    var chat_msg_height = $('#chat-msg').height()
                    this.drag_param.pos_pageY = pos_pageY
                    this.drag_param.chat_msg_height = chat_msg_height
                    $(document).mousemove(function (e){
                        if($target.attr('data-type') === 'chat-msg-pos-ctrl-bar'){
                            if(vm.drag_param.pos_pageY){
                                var lt = e.pageY - vm.drag_param.pos_pageY;
                                //console.log('lt',lt)
                                $('#chat-msg').height(vm.drag_param.chat_msg_height + lt)
                                vm.editor_height = window.innerHeight - $('#chat-msg').height()-100
                            }


                        }
                    });

                }
                else{
                    if(!this.lock){
                        this.drag_param.isDrag = true
                    }
                    if (!this.drag_param.isDrag && !this.drag_param.isResize) return;

                    $target.css("position", "fixed");
                    $target.css("cursor", "move");
                    this.drag_param.currentDisX = e.pageX - $target.offset().left+20;
                    this.drag_param.currentDisY = e.pageY - $target.offset().top;
                    $(document).mousemove(function (e) {
                        if (vm.drag_param.isDrag && !vm.lock) {
                            $target.css("cursor", "move");

                            let cursorX = e.pageX - vm.drag_param.currentDisX; //+$(this).offset().left;
                            let cursorY = e.pageY - vm.drag_param.currentDisY; //-$(this).offset().top;
                            $target.css("top", cursorY + "px").css("left", cursorX + "px");
                        }
                        else{
                            $target.css("cursor", "default");
                        }
                    })

                }



            },
            overdragthis(n,e){
                //console.log('overdragthis')
                var $target = $(e.currentTarget)
                if($target.attr('data-type') === 'chat-msg-pos-ctrl-bar'){
                    this.drag_param.pos_pageY = 0

                }
                else{
                    if(n){

                        $target.css("cursor", "default");
                    }

                    this.drag_param.isDrag = false
                    this.drag_param.pull_pointX = 0
                }


            },
            iframeclick(){
                console.log(1)
            },
            iframe_click_back(){
//                console.log('go back')
//                $('#phone_model_iframe')[0].contentWindow.history.go(1);
//                document.getElementById(phone_model_iframe).contentDocument.location.reload(true);
//                document.getElementById('phone_model_iframe').src = document.getElementById('phone_model_iframe').src
                this.currect_mobile_show_uri = this.currect_mobile_show_uri+' '
                console.log(this.currect_mobile_show_uri)
//                $('.phone_model_iframe').src = this.mobile_show_uri


            }
        },

    }
</script>
<style>
        .phone_model{
                margin:0px 20px 0;
                background-image:url(http://cdn.iamsee.com/phone-model/phone_black.png);
                background-repeat:no-repeat;
                background-size:100% 100%;
                height:620px;
                padding: 0 2px;
                padding-top: 50px;
                box-sizing:border-box;
                width:313px;
                position: absolute;
                left:70%
        }
        .phone_model .demo-page{
                width: 100%;
                height:495px;
                background-color:#fff;
        }
        .phone_model .phone_model_button{
                width: 60px;
                height: 60px;
                background-color: rgba(61, 67, 74, 0.84);
                border-radius: 50px;
                margin-left: 130px;
                border: 2px solid #c4cfda;
                box-shadow: 0 0 15px rgba(51, 55, 60, 0.44);
                transition: all 0.6s;
        }
        .phone_model .phone_model_button:hover{
                box-shadow: 0 0 25px rgba(196, 207, 218, 0.44);
                border: 5px solid rgba(196, 207, 218, 0.84);
                transition: all 0.6s;

        }

</style>
