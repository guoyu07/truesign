<template>
  <div>
    <bar-top
            :show-refesh-icon="true"
            :show-return-icon="false"
            :show-write-icon="true"></bar-top>
    <card></card>
    <bar-bottom></bar-bottom>
    <alert></alert>
  </div>
</template>

<script>
    var barTop = require('./components/barTop.vue');
    var alert = require('./components/alert.vue');

    module.exports = {
        components : {
            'bar-top':barTop,
            'alert':alert,
        },
        methods:{

        }
    }
</script>

<style>

</style>
