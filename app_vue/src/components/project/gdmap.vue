<template>
  <div>
    <div id="map-container"></div>


  </div>
</template>



<script>



  $('h1').css('display','none')

  export default{
    data(){
        return{
            map:null,
            location:[],
        }
    },
    mounted(){
      this.initMap()
      this.initLocation()
      this.setZoomAndCenter()



    },
    methods:{
        initMap:function () {
          const map = new AMap.Map('map-container', {
            resizeEnable: true,
//          zoom: 8,
//          center:
          })
          this.map = map
          map.setMapStyle("blue_night");

          map.plugin(["AMap.ToolBar",'AMap.Autocomplete','AMap.PlaceSearch'], function() {
            map.addControl(new AMap.ToolBar());
          })
          var features = ['bg','road','building','point'];
          map.setFeatures(features);
        },
        initLocation:function () {
          var map = this.map
          var that = this
          map.plugin('AMap.Geolocation', function() {
            var geolocation = new AMap.Geolocation({
              enableHighAccuracy: true,//是否使用高精度定位，默认:true
//          timeout: 10000,          //超过10秒后停止定位，默认：无穷大
              buttonOffset: new AMap.Pixel(10, 20),//定位按钮与设置的停靠位置的偏移量，默认：Pixel(10, 20)
              zoomToAccuracy: true,      //定位成功后调整地图视野范围使定位位置及精度范围视野内可见，默认：false
              buttonPosition:'RB'
            });
            map.addControl(geolocation);
            geolocation.getCurrentPosition();
            AMap.event.addListener(geolocation, 'complete', onComplete);//返回定位信息
            AMap.event.addListener(geolocation, 'error', onError);      //返回定位出错信息
          });
          //解析定位结果
          function onComplete(data) {

            var str=['定位成功'];
            str.push('经度：' + data.position.getLng());
            str.push('纬度：' + data.position.getLat());
            this.location = [data.position.getLng(),data.position.getLat()]
            if(data.accuracy){
              str.push('精度：' + data.accuracy + ' 米');
            }//如为IP精确定位结果则没有精度信息
            str.push('是否经过偏移：' + (data.isConverted ? '是' : '否'));
            setTimeout(function () {
              map.setZoom(11)
            },2000)

          }
          //解析定位错误信息
          function onError(data) {


          }
        },
        setZoomAndCenter(){



        }

    }
  }

</script>
<style>
  #map-container {height:100%; position: absolute;  width:100%;}
  .amap-logo {
    right: 0 !important;
    left: auto !important;
    display: none;
  }



  .amap-copyright {
    right: -170px !important;
    left: auto !important;
  }
</style>
