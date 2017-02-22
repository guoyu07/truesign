<template lang="html">
  <div class="pages-counter">
    <button type="button" name="button" @click='increment'>+</button>
    <button type="button" name="button" @click='decrement'>-</button>
    <span v-text='count'></span>
    <span v-text='message'></span>
    <button type="button" name="button" @click='changemessage'>CHANGEMESSAGE</button>
    <span v-text='msg'></span>

  </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex'
    export default {
        mounted () {
            console.log(this.$store)
        },
        data () {
            return {
                'msg':'aaa'
            }
        },
        computed: {
            ...mapGetters({
                count: 'getCount',
                message: 'getMessage'
            })
        },
        methods: {
            ...mapActions({
                increment: 'INCREMENT',
                decrement: 'DECREMENT',
                changemessage: 'CHANGEMESSAGE'
            })
        }
    }
</script>

<style lang="css">
</style>
