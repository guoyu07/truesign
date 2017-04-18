<template>
  <div class="top_router_view" >
    <particles></particles>
    <div id="welcombtn" style="z-index: 11;">

      <!--<input class="trigger_btn" type="button" @mouseover="overthis($event)" @mouseleave="levelthis($event)" @click="clickthis($event)" value="数据加载中"/>-->
      <!--<div class="line line-start islinelisten becopyline"><div class="line-body"></div></div>-->



    </div>
    <section class="signup-form" style="display: block; z-index: 10">

        <div class="signup-form-field" style="opacity: 1; ">
          <label for="id_name" style="opacity: 1;">公司名称</label>
          <input type="text" name="name" id="id_name" @focus="focusthis(1,$event)" @focusout="focusoutthis(1,$event)" :placeholder="aboutus.company" readonly autocomplete="off" style="visibility: visible; opacity: 1;">
          <div class="line topline"><div class="line-body"></div></div>
        </div>
        <div class="signup-form-field" style="opacity: 1; ">
          <label for="id_email" style="opacity: 1; ">公司地址</label>
          <input type="text"  name="email" id="id_email" @focus="focusthis(2,$event)" @focusout="focusoutthis(2,$event)" readonly :placeholder="aboutus.address" autocomplete="off">
          <div class="line secline"><div class="line-body"></div></div>
        </div>
        <div class="signup-form-field" style="opacity: 1;">
            <label for="id_pass" style="opacity: 1">联系电话</label>
            <input type="text"  name="pass" id="id_pass" @focus="focusthis(3,$event)" @focusout="focusoutthis(3,$event)" readonly :placeholder="aboutus.tel" autocomplete="off">
            <span class="signup-form-success-message" style="visibility: hidden;"></span>
            <div class="line thirdline"><div class="line-body"></div></div>
        </div>
        <div class="signup-form-field" style="opacity: 1;">
          <label for="id_searching" style="opacity: 1">网址</label>
          <input type="text" name="searching_for" id="id_searching" @focus="focusthis(4,$event)" @focusout="focusoutthis(4,$event)" readonly :placeholder="aboutus.site" autocomplete="off">
          <span class="signup-form-success-message" style="visibility: hidden;"></span>
          <div class="line forthline"><div class="line-body"></div></div>
        </div>

        <!--<div class="signup-form-footer" style="opacity: 1;">-->
          <!--<div class="signup-form-submit"  v-if="email && pass && name">-->
            <!--<a href="#" class="signup-form-submit-bttn" style="opacity: 1;">submit</a>-->
            <!--<div class="line line-start line_animation_tip line-submit" ><div class="line-body"></div></div>-->
          <!--</div>-->
          <!--&lt;!&ndash;<div class="signup-form-share" style="display: none;">&ndash;&gt;-->
            <!--&lt;!&ndash;<span class="signup-form-share-message">Share</span>&ndash;&gt;-->
            <!--&lt;!&ndash;<div>&ndash;&gt;-->
              <!--&lt;!&ndash;<a class="signup-form-share-twitter" href="#"><img src="/assets/img/twitter-stroke.svg"></a>&ndash;&gt;-->
              <!--&lt;!&ndash;<a class="signup-form-share-facebook" href="#"><img src="/assets/img/facebook-stroke.svg"></a>&ndash;&gt;-->
              <!--&lt;!&ndash;&lt;!&ndash; <a class="signup-form-share-instagram" href="#"><img src="/assets/img/instagram-stroke.svg"></a> &ndash;&gt;&ndash;&gt;-->
            <!--&lt;!&ndash;</div>&ndash;&gt;-->
          <!--&lt;!&ndash;</div>&ndash;&gt;-->
        <!--</div>-->

    </section>


  </div>
</template>

<script>
    const ramjet = require('ramjet');
    import particles from './../../effect/particles.vue'
  	module.exports = {
  		data: function () {
  			return{


            }
  		},
        props:[
            'aboutus'
        ],
        methods:{
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
                var target = $(e.currentTarget)
                target.siblings('.line').removeClass('islinelisten')


                var to_line_rect = $('.topline')[0].getBoundingClientRect()
                var to_secline_rect = $('.secline')[0].getBoundingClientRect()
                var to_thirdline_rect = $('.thirdline')[0].getBoundingClientRect()
                var to_forthline_rect = $('.forthline')[0].getBoundingClientRect()

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
                                        var $sec_copy_line = $from_line_copy.clone()
                                        var $third_copy_line = $from_line_copy.clone()
                                        var $forth_copy_line = $from_line_copy.clone()
                                        target.closest('.top_router_view').append($from_line_copy)
                                        target.closest('.top_router_view').append($sec_copy_line)
                                        target.closest('.top_router_view').append($third_copy_line)
                                        target.closest('.top_router_view').append($forth_copy_line)
                                        $('.iscopyline:eq(0)').animate(
                                            {
                                                'top':to_line_rect.top+3,
                                                'opacity':$('.topline').css('opacity')
                                            },
                                            {
                                                duration:1000,
                                                complete:function () {
                                                    var that = $(this)
                                                    $('.signup-form-field:eq(0)').animate({
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
                                        $('.iscopyline:eq(1)').animate(
                                            {
                                                'top':to_secline_rect.top+3,
                                                'opacity':$('.secline').css('opacity')
                                            },
                                            {
                                                duration:1000,
                                                complete:function () {
                                                    var that = $(this)
                                                    $('.signup-form-field:eq(1)').animate({
                                                        'opacity':'0.7'
                                                    },{
                                                        duration:300,
                                                        complete:function () {
                                                            that.animate({
                                                                'opacity':0
                                                            },{
                                                                duration:10,
                                                                complete:function () {
                                                                    $(this).remove()
                                                                }
                                                            })
                                                        }
                                                    })
                                                }
                                            }

                                        )
                                        $('.iscopyline:eq(2)').animate(
                                            {
                                                'top':to_thirdline_rect.top+3,
                                                'opacity':$('.thirdline').css('opacity')
                                            },
                                            {
                                                duration:1000,
                                                complete:function () {
                                                    var that = $(this)
                                                    $('.signup-form-field:eq(2)').animate({
                                                        'opacity':'0.7'
                                                    },{
                                                        duration:300,
                                                        complete:function () {
                                                            that.animate({
                                                                'opacity':0
                                                            },{
                                                                duration:10,
                                                                complete:function () {
                                                                    $(this).remove()
                                                                }
                                                            })
                                                        }
                                                    })
                                                }
                                            }

                                        )
                                        $('.iscopyline:eq(3)').animate(
                                            {
                                                'top':to_forthline_rect.top+3,
                                                'opacity':$('.forthline').css('opacity')
                                            },
                                            {
                                                duration:1000,
                                                complete:function () {
                                                    var that = $(this)
                                                    $('.signup-form-field:eq(3)').animate({
                                                        'opacity':'0.7'
                                                    },{
                                                        duration:300,
                                                        complete:function () {
                                                            that.animate({
                                                                'opacity':0
                                                            },{
                                                                duration:10,
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
                                }
                            )
                    }
                })


            }
        },
        components:{
            particles,

        },
        mounted(){
//  		    setTimeout(function () {
//                $('.trigger_btn').click()
//            },1000)
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
