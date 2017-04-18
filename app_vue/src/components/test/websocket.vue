<template>
  <div>
      <button type="button" class="btn btn-default" @click="socket_init">连接socket</button>
      <button type="button" class="btn btn-default" @click="disconnect">断开socket</button>
      <button type="button" class="btn btn-default" @click="check_status">开始计时</button>
      <button type="button" class="btn btn-default" @click="stop_check_status">停止计时</button>

      <span style="margin-left: 20px;"></span>
      <div style="display: inline-block" v-if="me">
          <button type="button" class="btn btn-default" id="me_id" >{{me.me_id}}</button>
          <button type="button" class="btn btn-default" id="me_nickname" >{{me.me_nickname}}</button>
          <div style="display: inline-block;margin-top: -2px" >
            <span style="color: green" v-if="me.me_status" class="glyphicon glyphicon-ok-circle btn btn-default"></span>
            <span style="color: red" v-else="me.me_status" class="glyphicon glyphicon-remove-circle btn btn-default"></span>

          </div>




      </div>
      <div class="progress" v-if="show_process">
          <div  class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" :aria-valuenow="process" aria-valuemin="0" aria-valuemax="100" v-bind:style="{ width: process + '%'} " style="float: right" >
              {{  process/10 }}
          </div>
      </div>




      <hr>
      <div class="col-lg-12">
          <div class="col-lg-1" style="padding: 0;">
              <div class="input-group">
                  <span class="input-group-addon">TO</span>
                  <input type="text" class="form-control col-sm-3" v-model="to">
              </div><!-- /input-group -->
          </div>
      </div><!-- /.col-lg-6 -->
      <div class="col-lg-12">
          <div class="col-lg-3" style="padding: 0">
              <div class="input-group">
                  <span class="input-group-addon">module</span>
                  <input type="text" class="form-control" v-model="module">
              </div><!-- /input-group -->
          </div><!-- /.col-lg-6 -->
          <div class="col-lg-3" style="padding: 0">
              <div class="input-group">
                  <span class="input-group-addon">controller</span>
                  <input type="text" class="form-control" v-model="controller">
              </div><!-- /input-group -->
          </div><!-- /.col-lg-6 -->
          <div class="col-lg-3" style="padding: 0">
              <div class="input-group">
                  <span class="input-group-addon">action</span>
                  <input type="text" class="form-control" v-model="action">
              </div><!-- /input-group -->
          </div><!-- /.col-lg-6 -->
      </div>
      <div class="col-lg-12">
          <div class="col-lg-3" style="padding: 0">
              <div class="input-group">
                  <span class="input-group-addon">payload_type</span>
                  <input type="text" class="form-control" v-model="payload_type">
              </div><!-- /input-group -->
          </div><!-- /.col-lg-6 -->
          <div class="col-lg-3" style="padding: 0">
              <div class="input-group">
                  <span class="input-group-addon">payload_data</span>
                  <input type="text" class="form-control" v-model="payload_data">
              </div><!-- /input-group -->
          </div><!-- /.col-lg-6 -->

      </div>

      <div class="col-lg-12">
          <div class="col-lg-6" style="padding: 0">
              <div class="input-group">
                  <input type="text" class="form-control " v-model="cmd">
                  <span class="input-group-btn">
                            <button class="btn btn-default" style="width: 200px" type="button" @click="send">Go!</button>
                    </span>

              </div><!-- /input-group -->
          </div>
      </div><!-- /.col-lg-6 -->
      <hr style="padding: 20px;">

      <form role="form" style="padding: 15px;">
          <div style="position: absolute; left: 0">
              <textarea class="form-control" style="height: 250px; width: 800px;" v-model="payload"></textarea>
              <textarea class="form-control" style="height: 250px;width: 800px;" rows="2" cols="4" id="response" v-model="response"></textarea>
          </div>
          <div style="position: absolute; left: 820px">
              <textarea class="form-control" style="height: 500px; width: 400px;" id="user_list" v-model="user_list"></textarea>

          </div>
      </form>


  </div>
</template>

<script>
    import SOCKET_CLIENT_DEMO from '../../api/SOCKET_CLIENT_DEMO'
    SOCKET_CLIENT_DEMO.data.this_vue = this
    export default {
        mounted () {

        },
        data () {
            return {
                msg: '',
                socket: '',
                cmd:'cmd',
                to:'',
                payload_type:'search',
                payload_data:'{id:3}',
                response_data:'response_data',
                module:'index',
                controller:'index',
                action:'index',
                payload:null,
                response:null,
                user_list:null,
                me:null,
                process:100,
                show_process:false,
                init_check:null,

            }
        },
        computed: {

        },
        methods: {

            check_status(){

                let that = this
                this.int_check = setInterval(function () {
                     that.process -= 10
//                    console.log(that.process)
                    if(that.process <= 0){
                        that.process = 100
//                        console.log('SOCKET_CLIENT_DEMO.data.wSock=>')
//                        console.log(SOCKET_CLIENT_DEMO.data.wSock)
                        if(SOCKET_CLIENT_DEMO.data.wSock){

                        }else{
                            that.me.me_status = false
                        }
                    }
                 },1000)
            },
            stop_check_status(){
                window.clearInterval(this.int_check)
            },
            socket_init()  {
                SOCKET_CLIENT_DEMO.data.this_vue = this

                SOCKET_CLIENT_DEMO.init()
            },
            disconnect() {
                SOCKET_CLIENT_DEMO.data.this_vue = this

                SOCKET_CLIENT_DEMO.wsClose()
            },
            reconnect() {

            },
            send(){

                this.payload = {
                    to:parseInt(this.to),
                    payload_type:this.payload_type,
                    payload_data:this.payload_data,
                    yaf:{
                        module:this.module,
                        controller:this.controller,
                        action:this.action,
                    }
                }
                this.payload['cmd'] = this.cmd
                SOCKET_CLIENT_DEMO.data.payload = this.payload
                SOCKET_CLIENT_DEMO.data.this_vue = this
                let response = SOCKET_CLIENT_DEMO.wsSend()

            }


        },

    }
</script>

<style lang="css">
    div{
        margin: 0;
        padding: 0;
    }
    .col-lg-3{
        padding: 0;
    }
</style>
