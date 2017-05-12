<template>
  <div class="top_router_view "  :style="{position: 'absolute','height': screenHeight+'px',overflow: 'hidden'}">
      <!--<div-->
              <!--style="border-bottom: 1000px solid red;-->
                    <!--border-left: 1000px solid transparent;-->
                    <!--width: 0;height: 0;-->
                    <!--position: absolute;-->
                    <!--margin-left: 100px;-->
                    <!--margin-top: 10000px;-->
<!--">-->

      <!--</div>-->
      <div class="triangle-bottomright animation_line"
           :style="{
                position: 'absolute',
                zIndex :2,
                borderBottom:borderStyle.borderBottom*effect_line_rate +'px solid #3A4552',
                borderLeft:borderStyle.borderLeft*effect_line_rate +'px solid transparent',
                marginLeft:-(margin.x-parseInt(trim))+'px',
                marginTop:-(margin.y-parseInt(trim))+'px',
                transform:'rotate('+effect_rotate_deg+'deg)',
                }"
      >
      </div>
      <div class="triangle-topleft animation_line"
           :style="{
                position: 'absolute',
                zIndex :2,
                borderTop:borderStyle.borderTop*effect_line_rate+'px solid #3A4552',
                borderRight:borderStyle.borderRight*effect_line_rate+'px solid transparent',
                marginLeft:-(margin.x+parseInt(trim))+'px',
                marginTop:-(margin.y+parseInt(trim))+'px',
                transform:'rotate('+effect_rotate_deg+'deg)'
               }">

      </div>
      <div id="effect_line_color" v-if="effect_line_top>0"
           :style="{
                width: '100%',
                height: '100%',
                backgroundColor: '#57DCDF',
                position: 'absolute',
                zIndex: 1,
                top:(100-effect_line_top)+'%'
        //            top:'100%'
                }" >
      </div>

  </div>
</template>

<script>
//    import effectlogo from './effect_logo.vue'
    export default  {
  		data () {
  			return {
//                screenWidth: window.innerWidth,   // 这里是给到了一个默认值 （这个很重要）
//                screenHeight:window.innerHeight,  // 这里是给到了一个默认值 （这个很重要）
                borderStyle:{
                    borderBottom:100,
                    borderRight:100,
                    borderLeft:100,
                    borderTop:100
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
                center_logo:{
                    width:100,
                    height:100,
                    borderRadius:50
                },
                logo_pos:'center',
                screenWidth:document.body.clientWidth,
                screenHeight:document.body.clientHeight
  			}
  		},
        created(){
            var vm = this
            this.$root.eventHub.$on('screenWidth2screenHeight',function (data) {
                var width2height = data.split(",")
                vm.screenWidth = parseInt(width2height[0])
                vm.screenHeight = parseInt(width2height[1])

            })
        },
        props:{
            effect_line_top:{
                type: String,
                default: '0',
                required: false,
                validator: function (value) {
                    return value >= 0
                }
            },

        },
        methods:{
            resize_effect_line(){
                var vm = this
                vm.borderStyle.borderBottom = vm.screenHeight
                vm.borderStyle.borderLeft = vm.screenWidth
                vm.borderStyle.borderTop = vm.screenHeight
                vm.borderStyle.borderRight = vm.screenWidth
                vm.effect_line_length = Math.sqrt(Math.pow(vm.screenWidth,2)+Math.pow(vm.screenHeight,2))
                vm.effect_line_angle.a = 180/(Math.PI/(Math.asin(vm.screenWidth/vm.effect_line_length)))
                vm.effect_line_angle.b = 180/(Math.PI/(Math.asin(vm.screenHeight/vm.effect_line_length)))
                vm.logo_angle = vm.effect_line_angle.a
                vm.center.x = vm.screenWidth/2
                vm.center.y = vm.screenHeight/2
                vm.margin.x = parseInt(vm.screenWidth*vm.effect_line_rate/2 - vm.center.x)
                vm.margin.y = parseInt(vm.screenHeight*vm.effect_line_rate/2 - vm.center.y)

            },
            resize_effect_over(){
                var vm = this
                this.effect_rotate_deg = -this.effect_line_angle.a
                this.logo_angle = 0
                setTimeout(function () {
                    $('#effect_line_color').hide()
                    vm.trim = 2000
                },1500)
                setTimeout(function () {
                    vm.logo_pos = 'left_top'
                },1500)
                setTimeout(function () {
                    vm.$root.eventHub.$emit('laoding','over')
                },3000)



            },
            resize_effect_start(){

                var vm = this

                vm.trim = 2

                setTimeout(function () {
                    $('#effect_line_color').show()
                    vm.logo_angle = vm.effect_line_angle.a

                    vm.effect_rotate_deg = 0

                },1500)
                setTimeout(function () {
                    vm.logo_pos = 'center'
                },1500)


            },

//            动画函数
            beforeEnter(){
                console.log('beforeEnter↓')
            },
            enter(){
                console.log('enter↓')

            },
            afterEnter(){
                console.log('afterEnter↓')

            },
            enterCancelled(){
                console.log('enterCancelled↓')

            },
            beforeLeave(){
                console.log('beforeLeave↓')

            },
            leave(){
                console.log('leave↓')

            },
            afterLeave(){
                console.log('afterLeave↓')

            },
            leaveCancelled(){
                console.log('leaveCancelled↓')

            }
        },
        mounted(){

  		    var vm = this
            this.$root.eventHub.$on('effect_line_top',function (data) {
                vm.effect_line_top = parseInt(data)

            })
            this.resize_effect_line()
            this.$root.eventHub.$on('screenWidth2screenHeight',function (data) {
//                console.log('screenWidth2screenHeight')
//                console.log(data)
                var width2height = data.split(",")
//                console.log(width2height)
                vm.screenWidth = parseInt(width2height[0])
                vm.screenHeight = parseInt(width2height[1])
                vm.resize_effect_line()

            })


            setTimeout(function () {
                vm.effect_line_per = 100
//                $('#effect_line_color').animate({
//                    'top':(100-vm.effect_line_per)+'%'
//                },{
//                    duration:5000,
//                    complete:function () {
//                    }
//                })
//                vm.resize_effect_over()
            },2000)

        },
        updated(){
//            console.log('updated')
//            console.log(this.screenHeight)
        },
        components:{
//            effectlogo

        },
        watch:{
            effect_line_top(val){
                var vm = this
//                console.log('effect_line_top->',val)
                this.effect_line_per =parseInt(this.effect_line_top)
                if(this.effect_line_per === 100){

                    setTimeout(function () {
                        vm.resize_effect_over()

                    },800)
                }
                else if(this.effect_line_per <= 0){
                    setTimeout(function () {
                        vm.resize_effect_start()

                    },800)
                }
            }
        }
  	}
</script>

<style lang="stylus" rel="stylesheet/stylus">
#effect_line_color
    transition: all 0.8s linear
.animation_line
    transition: all 0.8s linear

</style>
