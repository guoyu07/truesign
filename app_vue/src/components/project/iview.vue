<template>
    <Checkbox :checked.sync="single">Checkbox</Checkbox>
</template>
<script>
    export default {
        data () {
            return {
                single: false
            }
        }
    }
</script>
