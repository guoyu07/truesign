<template>
  <div>
        <h1 style="color: white">i'm home</h1>
      <div id="example-2">
          <simple-counter></simple-counter>
          <simple-counter></simple-counter>
          <simple-counter></simple-counter>
      </div>
  </div>
</template>

<script>
    import Vue from 'vue'

    var data = { counter: 0 }
    Vue.component('simple-counter', {
        template: '<button v-on:click="counter += 1">{{ counter }}</button>',
        // data 是一个函数，因此 Vue 不会警告，
        // 但是我们为每一个组件返回了同一个对象引用
        data: function () {
            return data
        }
    })

    export default {
        data () {
            return {

            }
        }
    }
</script>

<style>

</style>
