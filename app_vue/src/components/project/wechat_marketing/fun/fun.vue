<template>
    <div class="top_mobile_view">

    </div>
</template>


<script>
    require('../../../../../static/css/mobile_style.css')

    export default {
        data(){
            return {

            }
        },
        props: {},
        components: {

        },
        computed: {},
        created(){

        },
        mounted(){


        },
        beforeDestroy(){

        },
        methods: {
            onValuesChange(picker, values) {
                if (values[0] > values[1]) {
                    picker.setSlotValue(1, values[0]);
                }
            }
        },

    }
</script>
<style>


</style>
