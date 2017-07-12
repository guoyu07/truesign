<template>
    <div>
        <bar-top
                :show-refesh-icon="true"
                :show-return-icon="false"
                :show-write-icon="true"></bar-top>
    </div>
</template>

<script>
    var barTop = require('./barTop.vue');

    module.exports = {
        components : {
            'bar-top':barTop,
        },
        methods:{

        }
    }
</script>

<style>

</style>
