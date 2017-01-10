<template>
  <div>
    <bar-top
            :show-refesh-icon="true"
            :show-return-icon="false"
            :show-write-icon="true"></bar-top>
    <alert></alert>
  </div>
</template>

<script>
    var barTop = require('./barTop.vue');
    var alert = require('./alert.vue');

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
