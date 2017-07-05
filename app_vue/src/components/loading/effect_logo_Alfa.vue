<template>
  <div class="" style=""  >
        <!--<input v-model="on_logo_pos" style="position: absolute;z-index:100000;background-color: white;color: black!important;margin-left: 200px">-->
        <div id="logo_scope" :class="{'logo_effect_center_out':on_logo_pos === 'center' || on_logo_pos==='relative_center'}"
             :style="{
            zIndex:'100',
            borderRadius:center_logo.borderRadius+'px',
            width:center_logo.width*1.2+'px',
            height:center_logo.height*1.2+'px',
            top:center_logo.top+'px',
            bottom:center_logo.bottom+'px',
            left:center_logo.left+'px',
            right:center_logo.right+'px',
            boxShadow:logo_box_shadow
            }">
            <div id="center_logo" :style="{transform:'rotate('+logo_angle+'deg)',width:center_logo.width+'px',height:center_logo.height+'px',
            borderRadius:center_logo.borderRadius+'px',
            borderWidth:(borderStyle.borderTop+'px ' + borderStyle.borderRight+'px ' + borderStyle.borderBottom+'px ' + borderStyle.borderLeft+'px')}">
                <div :class="{'i_bar':always_class,'logo_effect_center_in':on_logo_pos === 'center' || on_logo_pos==='relative_center','logo_effect_other':on_logo_pos === 'left_top'}"
                     style="position: absolute;width: 100%;height: 100%;">
                    <div class="i_bar_content" :style="{backgroundColor:colorCtrl.i_bar_content,borderRadius:'20px',height:'60%'}"></div>
                    <div class="i_bar_dot1" :style="{backgroundColor:colorCtrl.i_bar_dot1,top:'-12%'}"></div>
                    <div class="i_bar_dot2" :style="{backgroundColor:colorCtrl.i_bar_dot2,bottom:'-12%'}"></div>
                </div>


            </div>


        </div>

  </div>
</template>

<script>

//    module.exports = {
    export default {
  		data () {
  			return {
                screenWidth: document.body.clientWidth,   // 这里是给到了一个默认值 （这个很重要）
                screenHeight: document.body.clientHeight,  // 这里是给到了一个默认值 （这个很重要）
                borderStyle:{
                    borderBottom:20,
                    borderRight:20,
                    borderLeft:20,
                    borderTop:20
                },
                center:{
                    x:0,
                    y:0,
                },
                margin:{
                    x:0,
                    y:0
                },
                effect_line_angle:{
                    a:0,
                    b:0
                },
                logo_angle:0,
                effect_line_length:0,
                effect_line_rate:5,
                effect_line_per:0,
                effect_rotate_deg:0,
                trim:2,

                colorCtrl:{
                    i_bar_content:'#57DCDF',
                    i_bar_dot1:"#57DCDF",
                    i_bar_dot2:"#57DCDF",
                },
                always_class:true,
                center_logo:{
                    width:100,
                    height:100,
                    borderRadius:100,
                    top:0,
                    right:0,
                    left:0,
                    bottom:0

                },
                on_logo_pos:'',
                logo_box_shadow:'0 0 20px #57DCDF'
  			}
  		},
        props:{
  		   
            logo_pos:{
                type: String,
                default: 'center', //left_top
                required: false,

            },
            logo_width:{
                type: String,
                default: '150', //left_top
                required: false,

            },
            custom_margin_left:{
                type: String,
                default: '0', //left_top
                required: false,
            },
            custom_margin_top:{
                type: String,
                default: '0', //left_top
                required: false,
            }





        },
        methods:{
            resize_effect_logo(){
                var vm = this
                vm.center.x = vm.screenWidth/2
                vm.center.y = vm.screenHeight/2

                if(this.on_logo_pos === 'left_top'){
                    this.center_logo.left = 100
                    this.center_logo.top = 10
                    this.center_logo.right = vm.screenWidth - this.center_logo.left-30
                    this.center_logo.bottom = vm.screenHeight - this.center_logo.top-30

                    this.center_logo.borderRadius = 3
                    this.center_logo.width = 100
                    this.center_logo.height = 30

//                    this.borderStyle.borderLeft = 10
//                    this.borderStyle.borderRight = 10
                    this.borderStyle.borderTop = 10
                    this.borderStyle.borderBottom = 10
                }
                else if(this.on_logo_pos === 'center'){


                    this.center_logo.right = 0
                    this.center_logo.bottom = 0
                    this.center_logo.left = vm.center.x-vm.logo_width*1.2/2 - parseInt(this.custom_margin_left)

                    this.center_logo.top = vm.center.y-115 -parseInt(this.custom_margin_top)
                    this.center_logo.borderRadius = parseInt(vm.logo_width)
//                    this.center_logo.width = parseInt(vm.logo_width)
//                    this.center_logo.height = parseInt(vm.logo_width)
                    this.center_logo.width = parseInt(vm.logo_width)
                    this.center_logo.height = parseInt(vm.logo_width)

                    this.borderStyle.borderLeft = 20
                    this.borderStyle.borderRight = 20
                    this.borderStyle.borderTop = 20
                    this.borderStyle.borderBottom = 20
                }
                else if(this.on_logo_pos === 'relative_center'){
                    this.center_logo.right = 0
                    this.center_logo.bottom = 0
                    this.center_logo.left = 0
                    this.center_logo.top = 0
                    this.center_logo.borderRadius = 100
                    this.center_logo.width = parseInt(vm.logo_width)
                    this.center_logo.height = parseInt(vm.logo_width)

                    this.borderStyle.borderLeft = 20
                    this.borderStyle.borderRight = 20
                    this.borderStyle.borderTop = 20
                    this.borderStyle.borderBottom = 20
//                    $('#logo_scope').css('margin','15px 75px')
                }

            },

        },
        mounted(){
            this.on_logo_pos = this.logo_pos
            this.resize_effect_logo()
  		    var vm = this


            this.$root.eventHub.$on('screenWidth2screenHeight',function (data) {
//                console.log('screenWidth2screenHeight')
//                console.log(data)
                var width2height = data.split(",")
//                console.log(width2height)
                vm.screenWidth = parseInt(width2height[0])
                vm.screenHeight = parseInt(width2height[1])
                vm.resize_effect_logo()

            })

            this.$root.eventHub.$on('change_logo_box_shadow',function (data) {
                vm.logo_box_shadow = data
            })
        },
        updated(){

        },
        components:{


        },
        watch:{
            logo_pos(val){
              this.on_logo_pos = this.logo_pos
            },
            on_logo_pos(val){
                this.resize_effect_logo()

            }
        }
  	}
</script>

<style lang="stylus" rel="stylesheet/stylus">

#logo_scope
    transition all 1s linear
    position: absolute;
    /*margin: auto;*/
    z-index:11;
    box-shadow: 0 0 20px #57DCDF
    background-color: rgba(245, 245, 245, 0.33)
    border-radius 100px
    /*animation:reverse_rotatealways 5s   infinite*/
    /*animation:reverse_rotatealways 3s linear  infinite normal*/
    #center_logo
        /*animation:rotatealways 5s linear  infinite normal*/
        transition all 0.5s linear
        position: absolute;
        /*width: 100px;
        height: 100px;*/
        border-radius:100px;
        margin: auto;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        background-color: rgba(133, 133, 133, 0.44);
        z-index:12;
        box-shadow: 0 0 20px #ffffff inset
        border 20px solid rgba(248, 253, 255, 0.35)
        border-left: 20px ridge rgba(248, 253, 255, 0.35);
        border-right: 20px groove rgba(248, 253, 255, 0.35);
        .i_bar
            transition all 0.5s linear
            /*animation:rotatealways 3s linear  infinite normal*/
            .i_bar_content

                position:absolute;width: 15%;height: 80%;left:0;right:0;margin:auto;top:0;bottom:0;background-color: rgba(87,220,223,0.42);border-radius: 0
            .i_bar_dot1
                position:absolute;width: 15%;height: 15%;margin:auto;top:-15%;left:0;right:0;background-color: rgba(87,220,223,0.42);border-radius: 100px
            .i_bar_dot2
                position:absolute;width: 15%;height: 15%;margin:auto;bottom:-15%;left:0;right:0;background-color: rgba(87,220,223,0.42);border-radius: 100px
.logo_effect_center_out
    animation:reverse_rotatealways 5s   infinite
.logo_effect_center_in
    animation:rotatealways 3s   infinite normal
.logo_effect_other
    animation:logo_effect_other_always 3s   infinite

@keyframes logo_effect_other_always{
    0%{
        transform:translateX(0%)

        }
    25%{
        transform:translateX(50%)


    }
    50%{
        transform:translateX(0%)


    }
    75%{
        transform:translateX(-50%)


    }
    100%{
        transform:translateX(0%)

    }
}
@keyframes rotatealways{
    0%{
        transform:rotate(0deg)

    }
    100%{
        transform:rotate(360deg)
    }
}
@keyframes reverse_rotatealways{
0%{
        transform:rotate(0deg)

    }
100%{
        transform:rotate(-360deg)



    }
}
</style>
