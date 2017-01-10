<template>
  <div>
    <i class="fa fa-spinner fa-spin"></i> <i class="fa fa-circle-o-notch fa-spin"></i> <i class="fa fa-refresh fa-spin"></i> <i class="fa fa-cog fa-spin"></i>
    123
  </div>
</template>

<script>
  module.exports = {
    props: {
      showRefeshIcon: {
        default: true
      },
      showReturnIcon: {
        default: false
      },
      showWriteIcon: {
        default: true
      },
    },
    methods:{
      writeSome:function () {
        this.$store.commit('isShowAlert', true);
      },
      refresh: function () {
        this.$store.dispatch('getData', {
          progress: this,
          refresh: true
        });
      },
      goBack: function () {
        this.$router.back();
      }
    }
  }
</script>

<style>
  .bar-easy-vue{
    background-color: #1abc9c;
  }
  .bar-easy-vue a, h1{
    color: #fff !important;
  }
</style>
