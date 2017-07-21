<template>
    <div class="top_router_view" style="text-align: center;overflow: auto">
        <header>
            <div style="" id="ismenu">
                <p>菜单按钮</p>
                <ol class="common" style="margin: 0;display: none">
                    <div style="margin: 0">
                        123
                    </div>
                    <div style="margin: 0">
                        123
                    </div>
                    <div style="margin: 0">
                        123
                    </div>
                </ol>

            </div>
        </header>

        <div id="isform">

            <div class="isinput">
                <input placeholder="验证码">
                <input class="isbtn " id="iscode" type="button" style="background-image: url('http://cdn.iamsee.com/demo/Selection_003.png'); background-size: 120px 35px;background-repeat:no-repeat;" >
            </div>
            <div class="isinput">
                <input placeholder="手机号">
                <p class="isbtn" >发送</p>
            </div>

        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {}
        },
        created(){

        },
        mounted(){
            $('#ismenu').hover(function () {
                $('ol.common').show()
            })
            $('#ismenu').mouseleave(function () {
                $('ol.common').hide()
            })
            $('#iscode').on('click',function () {
                var img_list = [
                    'http://cdn.iamsee.com/demo/Selection_005.png',
                    'http://cdn.iamsee.com/demo/Selection_006.png',
                    'http://cdn.iamsee.com/demo/Selection_003.png',
                ]
                var img_index = parseInt((Math.random()*10)%3)
                $('#iscode').css("background-image","url("+img_list[img_index]+")");
            })
        },
        methods: {},
        components: {},
        watch: {},

    }
</script>
<style>
    header {
        position: fixed;
        height: 40px;
        width: 100%;
        background-color: gray;
    }

    header div {
        transition: all 1s;
        cursor: pointer;
        width: 150px;
        background-color: #4fc08d;
        border-radius: 3px;
        height: 40px;
        line-height: 40px;
        margin-left: 300px;

    }

    header div:hover {
        background-color: black;
        color: #ffffff;
        box-shadow: 0 0 12px black;
        transition: all 1s;
    }




    #isform {
        margin: 0 auto;
        margin-top: 60px;
        padding-top: 100px;
        width: 600px;
        height: 600px;
        text-align: left;
    }

    #isform .isinput {
        border: 1px rgba(0, 0, 0, 0.21) solid;
        margin: 0 auto;
        margin-top: 12px;
        border-radius: 3px;
        width: 300px;
        height: 25px;
        background-color: #fff;
        box-shadow:  0 0 15px black;
        border: none;
        padding: 0;

    }
    #isform .isinput input{
         border-right: 2px solid black;
            vertical-align: top;
        width: 200px;
        height: 100%;
        border: none;
        padding: 0;
     }
    #isform .isinput .isbtn{
        height: 100%;
        border: none;
        padding: 0;
        display: inline-block;
        text-align: center;
        width: 96px;
        line-height: 25px;
        box-shadow: 0 0 20px black;
        cursor: pointer;
        transition: all 1s;
        background-color: #00b5ad;

    }
    #isform .isinput .isbtn:hover{

        background-color: #00dbd1;

    }
</style>
