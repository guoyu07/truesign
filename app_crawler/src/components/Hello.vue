<template>
  <div class="hello">
    <el-tabs v-model="defaultTab" type="card" @tab-click="handleClick">
      <el-tab-pane v-for="item,index in infolist" :label="''+index" :name="''+index" :key="item">

          <div style="text-align: center">
            <img :src="item.img">
            <div
                    style="position: absolute;top:0;
                    background-color:rgba(75,75,75,0.79);
                    width: 100%;color: #ffffff;font-size: 15px;padding:  100px 0;border-radius: 5px">
              {{item.msg}}
            </div>
          </div>
      </el-tab-pane>
  </el-tabs>
  </div>
</template>

<script>
export default {
  data () {
    return {
      infolist:[
        {
          msg:123,
          img:require('../../static/imgs/1.png')
        },
        {
          msg:456,
          img:require('../../static/imgs/1.png')
        }
      ],
      currectinfo:{},
      defaultTab:'0',
      activeName2: 'first'
    }
  },
  mounted(){
    this.currectinfo = this.infolist[0]
  },
  methods: {
    handleClick(tab, event) {
      this.currectinfo = this.infolist[parseInt(tab.name)]
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
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
