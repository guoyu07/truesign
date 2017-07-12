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
              for(let i=0; i<=100; i++){
                  let uri = 'http://www.kuaidi100.com/query?postid=221180904443&type=shentong';
                  $.get(uri,function (data) {
                      msglist.push(data)
                  })
              }

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
