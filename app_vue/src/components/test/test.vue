<template>
    <div class="testest" style="text-align: center;overflow: auto">
        123

        <input   style="border: 2px solid black;"  value="123" v-on:input="test">

    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                ctrl:8,
                flag:false
            }
        },
        created(){

        },
        mounted(){
           
        },
        methods: {
            test(){
                console.log(1)
            }
        },
        updated(){
        },

        components: {},
        watch: {},

    }
</script>
<style>

</style>
