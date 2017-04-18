<template>
  <div class="top_router_view" style="background-color: RGB(61,76,81)">
    <particles></particles>
    <div id="welcombtn" style="z-index: 11;">

      <input type="button" @mouseover="overthis($event)" @mouseleave="levelthis($event)" @click="clickthis($event)" value="SIGN UP FOR EARLY ACCESS"/>
      <div class="line line-start islinelisten becopyline "><div class="line-body"></div></div>



    </div>
    <section class="signup-form" style="display: block; z-index: 10">

        <div class="signup-form-field" style="opacity: 0; ">
          <label for="id_name" style="opacity: 0;">Your Name</label>
          <input type="text" v-model="name" name="name" id="id_name" @focus="focusthis(1,$event)" @focusout="focusoutthis(1,$event)" placeholder="Enter Your Name" autocomplete="off" style="visibility: visible; opacity: 1;">
          <div class="line topline"><div class="line-body"></div></div>
        </div>
        <div class="signup-form-field" style="opacity: 0; ">
          <label for="id_email" style="opacity: 0; ">Email</label>
          <input type="text" v-model="email" name="email" id="id_email" @focus="focusthis(2,$event)" @focusout="focusoutthis(2,$event)" placeholder="Enter Your E-Mail Address" autocomplete="off">
          <div class="line secline"><div class="line-body"></div></div>
        </div>
        <div class="signup-form-field" style="opacity: 0;">
            <label for="id_pass" style="opacity: 0">PassWord</label>
            <input type="text" v-model="pass" name="pass" id="id_pass" @focus="focusthis(3,$event)" @focusout="focusoutthis(3,$event)" placeholder="Your Pass" autocomplete="off">
            <span class="signup-form-success-message" style="visibility: hidden;"></span>
            <div class="line thirdline"><div class="line-body"></div></div>
        </div>
        <div class="signup-form-field" style="opacity: 0;">
          <label for="id_searching" style="opacity: 0">Searching For</label>
          <input type="text" v-model="look_for" name="searching_for" id="id_searching" @focus="focusthis(4,$event)" @focusout="focusoutthis(4,$event)" placeholder="What are you searching for?" autocomplete="off">
          <span class="signup-form-success-message" style="visibility: hidden;"></span>
          <div class="line forthline"><div class="line-body"></div></div>
        </div>


        <div class="signup-form-footer" style="opacity: 1;">
          <div class="signup-form-submit"  v-if="email && pass && name" style="display: inline-block">
            <a href="#" class="signup-form-submit-bttn" @click="submitthis" style="opacity: 1;">submit</a>
            <div class="line line-start line_animation_tip line-submit" ><div class="line-body"></div></div>
          </div>

          <!--<div class="signup-form-share" style="display: none;">-->
            <!--<span class="signup-form-share-message">Share</span>-->
            <!--<div>-->
              <!--<a class="signup-form-share-twitter" href="#"><img src="/assets/img/twitter-stroke.svg"></a>-->
              <!--<a class="signup-form-share-facebook" href="#"><img src="/assets/img/facebook-stroke.svg"></a>-->
              <!--&lt;!&ndash; <a class="signup-form-share-instagram" href="#"><img src="/assets/img/instagram-stroke.svg"></a> &ndash;&gt;-->
            <!--</div>-->
          <!--</div>-->
        </div>

    </section>


  </div>
</template>

<script>
    const ramjet = require('ramjet');
    import particles from './../effect/particles.vue'
  	module.exports = {
  		data: function () {
  			return {
  			    name:'',
  			    email:'',
                pass:'',
                look_for:'',
                linerect:[],
                line_length:0
  			}
  		},
        methods:{
            submitthis(){
                var vm = this
                var params = []
                params['name'] = 'line_form'
                params['params'] = {
                    name:vm.name,
                    pass:vm.pass,
                    email:vm.email,
                    look_for:vm.look_for
                }
                this.$root.eventHub.$emit('rule_submit_form',params)
            },
            focusthis(n,e){
                console.log(n)
                if(n===1){
                    var tmp_top = parseInt($('.signup-form').css('top').replace('px',''))
                    $('.signup-form').css('top',window.innerHeight/2-30*1)
                }
                else if(n===3){
                    let $target = $(e.currentTarget)
                    $target.attr('type','password')
                    $('.signup-form').css('top',window.innerHeight/2-30-102*(n-1))
                }
                else{
                    $('.signup-form').css('top',window.innerHeight/2-30-102*(n-1))

                }

                var $target = $(e.currentTarget)
                $target.prev().css('opacity',1)
            },
            focusoutthis(n,e){
                var $target = $(e.currentTarget)
                $target.prev().css('opacity',0)
            },
            overthis(e){
                var target = $(e.currentTarget)
                target.siblings('.islinelisten').animate(
                    {

                        'width':'5%',

                    }, {
                        duration: 500,
                        complete: function () {
                        }
                    })
            },
            levelthis(e){
                var target = $(e.currentTarget)
                target.siblings('.islinelisten').animate(
                    {

                        'width':'2%',

                    }, {
                        duration: 500,
                        complete: function () {
                        }
                    })
            },
            clickthis(e){
                var vm = this
                var target = $(e.currentTarget)
                target.siblings('.line').removeClass('islinelisten')
                var line_length =  $('.signup-form > .signup-form-field').length
                vm.line_length = line_length
                vm.linerect = []
                for(let i = 0; i < line_length; i++){
                    var tmp_to_line_rect = $('.signup-form > .signup-form-field > .line')[i].getBoundingClientRect()
//                    console.log('to_line_rect->',tmp_to_line_rect)
                    vm.linerect.push(tmp_to_line_rect)

                }

//                var to_line_rect = $('.topline')[0].getBoundingClientRect()
//                var to_secline_rect = $('.secline')[0].getBoundingClientRect()
//                var to_thirdline_rect = $('.thirdline')[0].getBoundingClientRect()
//                var to_forthline_rect = $('.forthline')[0].getBoundingClientRect()

                target.animate({
                    'opacity':0
                },{

                    complete:function () {
                        var $from_line = target.siblings('.line')
                        $from_line
                            .animate(
                                {
                                    'marginLeft':'-30%',
                                    'width':'100%',

                                },{
                                    duration:1000,
                                    complete:function () {
                                        var from_line_rect = $from_line[0].getBoundingClientRect()
                                        var $from_line_copy = $from_line.clone()
                                        $from_line_copy.addClass('iscopyline')

                                        $from_line_copy.css(
                                            {
                                                'top':from_line_rect.top+2,
                                                'opacity':$from_line.css('opacity')
                                            }
                                        )
//                                        $from_line.remove()
                                        $from_line.closest('#welcombtn').css('display','none')
//                                        var $sec_copy_line = $from_line_copy.clone()
//                                        var $third_copy_line = $from_line_copy.clone()
//                                        var $forth_copy_line = $from_line_copy.clone()
                                        target.closest('.top_router_view').append($from_line_copy)
                                        for(let i=1; i < vm.line_length; i++){
                                            var $copy_from_line_copy = $from_line_copy.clone()
                                            target.closest('.top_router_view').append($copy_from_line_copy)
                                        }
//                                        target.closest('.top_router_view').append($from_line_copy)
//                                        target.closest('.top_router_view').append($sec_copy_line)
//                                        target.closest('.top_router_view').append($third_copy_line)
//                                        target.closest('.top_router_view').append($forth_copy_line)
                                        for(let i=0; i < vm.line_length; i++){
                                            $('.iscopyline:eq('+i+')').animate(
                                                {
                                                    'top':vm.linerect[i].top+3,
                                                    'opacity':$('.signup-form > .signup-form-field > .line :eq('+i+')').css('opacity')
                                                },
                                                {
                                                    duration:1000,
                                                    complete:function () {
                                                        var that = $(this)
                                                        $('.signup-form-field:eq('+i+')').animate({
                                                            'opacity':'0.7'
                                                        },{
                                                            duration:300,
                                                            complete:function () {
                                                                that.animate({
                                                                    'opacity':0
                                                                },{
                                                                    duration:100,
                                                                    complete:function () {
                                                                        $(this).remove()
                                                                    }
                                                                })
                                                            }
                                                        })
                                                    }
                                                }

                                            )
                                        }
//                                        $('.iscopyline:eq(0)').animate(
//                                            {
//                                                'top':to_line_rect.top+3,
//                                                'opacity':$('.topline').css('opacity')
//                                            },
//                                            {
//                                                duration:1000,
//                                                complete:function () {
//                                                    var that = $(this)
//                                                    $('.signup-form-field:eq(0)').animate({
//                                                        'opacity':'0.7'
//                                                    },{
//                                                        duration:300,
//                                                        complete:function () {
//                                                            that.animate({
//                                                                'opacity':0
//                                                            },{
//                                                                duration:100,
//                                                                complete:function () {
//                                                                    $(this).remove()
//                                                                }
//                                                            })
//                                                        }
//                                                    })
//                                                }
//                                            }
//
//                                        )
//                                        $('.iscopyline:eq(1)').animate(
//                                            {
//                                                'top':to_secline_rect.top+3,
//                                                'opacity':$('.secline').css('opacity')
//                                            },
//                                            {
//                                                duration:1000,
//                                                complete:function () {
//                                                    var that = $(this)
//                                                    $('.signup-form-field:eq(1)').animate({
//                                                        'opacity':'0.7'
//                                                    },{
//                                                        duration:300,
//                                                        complete:function () {
//                                                            that.animate({
//                                                                'opacity':0
//                                                            },{
//                                                                duration:10,
//                                                                complete:function () {
//                                                                    $(this).remove()
//                                                                }
//                                                            })
//                                                        }
//                                                    })
//                                                }
//                                            }
//
//                                        )
//                                        $('.iscopyline:eq(2)').animate(
//                                            {
//                                                'top':to_thirdline_rect.top+3,
//                                                'opacity':$('.thirdline').css('opacity')
//                                            },
//                                            {
//                                                duration:1000,
//                                                complete:function () {
//                                                    var that = $(this)
//                                                    $('.signup-form-field:eq(2)').animate({
//                                                        'opacity':'0.7'
//                                                    },{
//                                                        duration:300,
//                                                        complete:function () {
//                                                            that.animate({
//                                                                'opacity':0
//                                                            },{
//                                                                duration:10,
//                                                                complete:function () {
//                                                                    $(this).remove()
//                                                                }
//                                                            })
//                                                        }
//                                                    })
//                                                }
//                                            }
//
//                                        )
//                                        $('.iscopyline:eq(3)').animate(
//                                            {
//                                                'top':to_forthline_rect.top+3,
//                                                'opacity':$('.forthline').css('opacity')
//                                            },
//                                            {
//                                                duration:1000,
//                                                complete:function () {
//                                                    var that = $(this)
//                                                    $('.signup-form-field:eq(3)').animate({
//                                                        'opacity':'0.7'
//                                                    },{
//                                                        duration:300,
//                                                        complete:function () {
//                                                            that.animate({
//                                                                'opacity':0
//                                                            },{
//                                                                duration:10,
//                                                                complete:function () {
//                                                                    $(this).remove()
//                                                                }
//                                                            })
//                                                        }
//                                                    })
//                                                }
//                                            }
//
//                                        )
                                    }
                                }
                            )
                    }
                })


            }
        },
        components:{
            particles,

        }
  	}
</script>

<style lang="stylus" rel="stylesheet/stylus">

#welcombtn
    position: absolute;
    top: 70%;
    left: 50%;
    width: 100%;
    line-height: 2em;
    text-align: center;
    cursor: pointer;
    transform: translate(-50%,0);
    /*.line*/
        /*transition all 1.5s*/
    .line-start
        width 2%
    input
        font-family: "Graphik Web", sans-serif !important
        word-spacing 3px
        letter-spacing 3px
        transition all 1.5s
    input:hover
        color rgba(210, 204, 212, 0.33) !important
.signup-form
    position absolute
    top 30%
    left 50%
    width: 100%
    margin-left: -50%;
    text-align center
    transform: translate(0%, 0%) matrix(1, 0, 0, 1, 0, 0);
    transition all 1.5s
    input,label
        font-family: "Graphik Web", sans-serif !important
        word-spacing 3px
        letter-spacing 3px
        transition all 1.5s
    label
        color white
    .signup-form-field
        transform: translate(0%, 0%) matrix(1, 0, 0, 1, 0, 0);
        position: relative;
        height: 102px;
        opacity: .6;
        vertical-align: baseline;
        label
            position: absolute;
            left: 0;
            /*bottom: 0;*/
            width: 20%;
            padding-left: 28px;
            font-size: 1em;
            line-height: 2em;
            text-align left
        input
            left: 35%;
            width: 25%;
            text-align: center;
            text-transform: uppercase;
            background: 0 0;
            font-family: 'Graphik Web',sans-serif;
            font-style: normal;
            font-stretch: normal;
            font-size: 1em;
            color: #00B5AD !important
            font-weight 800
            letter-spacing: 2.9px;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            outline: 0;


    a
        font-size 18px
        font-family: "Graphik Web", sans-serif !important
        color white
        opacity 0.5 !important
        transition  all 1.5s !important
        outline none
    a:hover
        color slategray
</style>
