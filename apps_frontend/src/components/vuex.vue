<template>
  <div>
    <p style="display: inline-block;">i'm vuex  on=> </p><p style="display: inline-block;" id="show_app"></p>
      <hr>
      <a class="btn btn-default btn-sm" href="#" @click="doLogin">触发登录</a>
      <p id="show_login_cb">{{ name }} {{ token }}</p>
  </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex'
    export default  {
  		data: function () {
  			return {
  			    'name':'',
  			    'token':'',
  			}
  		},
        mounted () {
            let u = navigator.userAgent;
            if ( u.indexOf('Android') > -1 || u.indexOf('Adr') > -1 ) {
                this.$store.dispatch('setApp', 'android');
            }
            else if (!!u.match(/\(i[^;]+;( U;) ? CPU.+Mac OS X/)) {
                this.$store.dispatch('setApp', 'ios');
            }
            else {
                this.$store.dispatch('setApp', u);
            }
            $('#show_app').html(this.$store.getters.getApp)
        },
        methods: {
            doLogin(){
                this.$store.dispatch('login','')
//                    .then(
//                    response => {
//                       this.name = this.$store.getters.getName
//                       this.token = this.$store.getters.getToken
//                })
            }
        },
        computed: {
            ...mapGetters({
                name: 'getName',
                token: 'getToken',
            })
        },




  	}
</script>

<style>

</style>
