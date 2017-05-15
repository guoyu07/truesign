<template>
  <div class="top_router_view "  >
        <div id="logo_scope" :class="{'logo_effect_center_out':on_logo_pos === 'center' || on_logo_pos==='relative_center'}"
             :style="{
            zIndex:'100',
            borderRadius:center_logo.borderRadius+'px',width:center_logo.width+15+'px',height:center_logo.height+15+'px',top:center_logo.top+'px',bottom:center_logo.bottom+'px',left:center_logo.left+'px',right:center_logo.right+'px'}">



        </div>

  </div>
</template>

<script>

//    module.exports = {
    export default {
  		data () {
  			return {

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
                default: '100', //left_top
                required: false,

            },



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

<style>

#logo_scope {

    transition: all 1s linear;
    position: absolute;
    /*margin: auto;*/
    z-index: 11;
    box-shadow: 0 0 20px #57DCDF;
    background-color: rgba(245, 245, 245, 0.33) ;
    border-radius: 100px;
    /*animation:reverse_rotatealways 5s   infinite*/
    /*animation:reverse_rotatealways 3s linear  infinite normal*/
}
    #center_logo{
        /*animation:rotatealways 5s linear  infinite normal*/
        transition: all 0.5s linear;
        position: absolute;
        width: 100px;
        height: 100px;
        border-radius:100px;
        margin: auto;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        background-color: rgba(133, 133, 133, 0.44);
        z-index:12;
        box-shadow: 0 0 20px #ffffff inset;
        border: 20px solid rgba(248, 253, 255, 0.35);
        border-left: 20px ridge rgba(248, 253, 255, 0.35);
        border-right: 20px groove rgba(248, 253, 255, 0.35);
    }

        .i_bar
        {
            transition: all 0.5s linear

        }

            .i_bar_content
            {
                position:absolute;width: 15%;height: 80%;left:0;right:0;margin:auto;top:0;bottom:0;background-color: rgba(87,220,223,0.42);border-radius: 0
            }
            .i_bar_dot1{
                position:absolute;width: 15%;height: 15%;margin:auto;top:-15%;left:0;right:0;background-color: rgba(87,220,223,0.42);border-radius: 100px

            }
            .i_bar_dot2{
                position:absolute;width: 15%;height: 15%;margin:auto;bottom:-15%;left:0;right:0;background-color: rgba(87,220,223,0.42);border-radius: 100px

            }
.logo_effect_center_out{
    animation:reverse_rotatealways 5s   infinite

}
.logo_effect_center_in{
    animation:rotatealways 3s   infinite normal

}
.logo_effect_other{
    animation:logo_effect_other_always 3s   infinite

}

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
