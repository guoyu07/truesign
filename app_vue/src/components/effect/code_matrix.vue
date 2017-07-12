<template>
    <div id="code_matrix">
        <transition-group name="code_matrix_item" tag="div" class="code_matrix_container">

            <div v-for="(item,index) in code_matrix_list" :key="item.id" class="code_matrix_item" :style="{ backgroundColor:item.color}">
                {{ item.code }}
                     </div>
        </transition-group>
    </div>
</template>

<script>
    export default {
        data () {
            return {
            }
        },
        props:['code_matrix_list']
    }
</script>

<style>

</style>
