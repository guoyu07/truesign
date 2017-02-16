<template>
  <div id="app">
    aaa
    <img src="../assets/images/logo.png">
    <h1>{{ msg }}</h1>
    <mt-button  @click.native="startHacking">Let's do it</mt-button>
    <mt-progress v-bind:value="value" :bar-height="5"></mt-progress>

  </div>
</template>

<script>
    export default {
        name: 'app',
        data () {
            return {
                msg: 'Welcome to Your Vue.js App',
                value: 33,
            }
        },
        methods: {
            startHacking () {
                this.$toast({
                    message: '操作成功',
                })
            },
            selected (){

            }
        }
    }
</script>

<style>
  #app {
    font-family: 'Avenir', Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-align: center;
    color: #2c3e50;
    margin-top: 60px;
  }

  h1, h2 {
    font-weight: normal;
  }

  ul {
    list-style-type: none;
    padding: 0;
  }

  li {
    display: inline-block;
    margin: 0 10px;
  }

  a {
    color: #42b983;
  }
</style>
