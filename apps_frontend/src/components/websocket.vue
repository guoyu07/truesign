<template>
  <div>
      <button type="button" class="btn btn-default" @click="socket_init">连接socket</button>
      <button type="button" class="btn btn-default" @click="disconnect">断开socket</button>

      {{ msg }}
      <hr>
      <input type="text" v-model="cmd" />
      <button type="button" class="btn btn-default" @click="emit">emit</button>

  </div>
</template>

<script>
    import chat from '../api/socket_client'

    export default {
        mounted () {

        },
        data () {
            return {
                msg: '',
                socket: '',
                cmd:'cmd'
            }
        },
        computed: {

        },
        methods: {
            socket_init()  {
                chat.init()
            },
            disconnect() {
                chat.logout()
            },
            reconnect() {

            },
            emit(){
                chat.wsSend('msg')
            }


        },

    }
</script>

<style lang="css">
</style>
