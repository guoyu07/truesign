<template>
  <div class="top_router_view" style="text-align: center">
     <input type="button" @click="send" value="发送">
    <div v-for="item,index in msglist">
        {{index}} : {{item}}
    </div>

  </div>
</template>

<script>
  	export default {
  		data: function () {
  			return {
  			    msglist:[]
            }
  		},
        created(){

        },
        methods:{
          send(){
              
                  let uri = 'http://rate.taobao.com/feedRateList.htm?userNumId=2133903213&auctionNumId=548983653978&siteId=4&spuId=0&currentPageNum=1&pageSize=50&rateType=1'

                  $.ajax({
                      url:uri,       //跨域到http://www.wp.com，另，http://test.com也算跨域
                      type:'GET',                                //jsonp 类型下只能使用GET,不能用POST,这里不写默认为GET
                      dataType:'jsonp',                          //指定为jsonp类型
                      data:{"name":"Zjmainstay"},                //数据参数
                                         //服务器端获取回调函数名的key，对应后台有$_GET['callback']='getName';callback是默认值
                     
                      success:function(result){                  //成功执行处理，对应后台返回的getName(data)方法。
                          console.log(result)
                      },
                      error:function(msg){
                          console.log('error->')
                          console.log(msg)
                      }
                  }); 

              

          }
        },
        components: {
        },
        watch:{

        },

  	}
</script>

<style>
  .outer {
    position: absolute;
    width: 100%;
    height: 100%;
    background: url(http://cdn.iamsee.com/loading/icon-spin-s.png) no-repeat;
    animation: spin 800ms infinite linear;
  }
  @keyframes spin {
    0%   { transform: rotate(360deg); }
    100% { transform: rotate(0deg); }
  }
  @keyframes second-half-hide {
    0%        { opacity: 1; }
    50%, 100% { opacity: 0; }
  }
  @keyframes second-half-show {
    0%        { opacity: 0; }
    50%, 100% { opacity: 1; }
  }
</style>
