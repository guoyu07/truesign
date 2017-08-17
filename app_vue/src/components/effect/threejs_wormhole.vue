<template>
    <div>
        <canvas id="canvas_config" style=""></canvas>
        <!--<canvas id="canvas_demo" style="margin-left: 50%;"></canvas>-->
        <!--<svg viewBox="0 0 346.4 282.4">-->
            <!--<polygon points="68.5,185.5 1,262.5 270.9,281.9 345.5,212.8 178,155.7 240.3,72.3 153.4,0.6 52.6,53.3 "/>-->
        <!--</svg>-->
    </div>

</template>



<script>
var THREE =require('three')

export default {
    data () {
        return {
            screenWidth:document.body.clientWidth,
            screenHeight:document.body.clientHeight
        }
    },
    created(){

    },
    mounted(){
        var vm = this
        this.$root.eventHub.$on('screenWidth2screenHeight', function (data) {
            var width2height = data.split(",")
            vm.screenWidth = parseInt(width2height[0])
            vm.screenHeight = parseInt(width2height[1])

        })
        this.init_config()




    },
    methods:{
        init_config(){
            var vm = this

            //Create a WebGL renderer
            var renderer = new THREE.WebGLRenderer({
                canvas: document.getElementById("canvas_config")
//                canvas: document.querySelector("canvas")
            });

//Create an empty scene
            var scene = new THREE.Scene();
            scene.fog = new THREE.Fog(0x000000,100,200);
//Create a perpsective camera


//Array of points
            var points = [
                [68.5,185.5],
                [1,262.5],
                [270.9,281.9],
                [345.5,212.8],
                [178,155.7],
                [240.3,72.3],
                [153.4,0.6],
                [52.6,53.3],
                [68.5,185.5]
            ];

//Convert the array of points into vertices
            for (var i = 0; i < points.length; i++) {
                var x = points[i][0];
                //We set a random value for the Y axis
                var y = (Math.random()-0.5)*250;
                var z = points[i][1];
                points[i] = new THREE.Vector3(x, y, z);
            }
//Create a path from the points
            var path = new THREE.CatmullRomCurve3(points);

//Create the tube geometry from the path
            var geometry = new THREE.TubeGeometry( path, 300, 4, 32, true );
//Basic material
            var material = new THREE.MeshLambertMaterial({
                color:0xffffff,
                side : THREE.BackSide
            });
//Create a mesh
            var tube = new THREE.Mesh( geometry, material );
//Add tube into the scene
            scene.add( tube );

            var light = new THREE.PointLight(0xffffff,1, 200);
            scene.add(light);
            var percentage = 0;
            function render(){
                var ww = vm.screenWidth,
                    wh = vm.screenHeight;
                renderer.setSize(ww, wh);
                var camera = new THREE.PerspectiveCamera(85, ww / wh, 0.001, 1000);
                camera.position.z = 400;
                percentage += 0.0002;
                var p1 = path.getPointAt(percentage%1);
                var p2 = path.getPointAt((percentage + 0.01)%1);
                var p3 = path.getPointAt((percentage + 0.07)%1);
                camera.position.set(p1.x,p1.y,p1.z);
                camera.lookAt(p2);
                light.position.set(p3.x, p3.y, p3.z);
                //Render the scene
                renderer.render(scene, camera);

                requestAnimationFrame(render);
            }
            requestAnimationFrame(render);
        },
    }

}
</script>
<style>
    canvas{
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height:100%;
    }
    svg{
        position: absolute;
        top: 0;
        left: 0;
        z-index:0;
        background:white;
        width: 300px;
        opacity:0.5;
    }
</style>