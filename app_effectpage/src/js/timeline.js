function timeLine(applyId) {

    this.screenWidth = document.body.clientWidth   // 这里是给到了一个默认值 （这个很重要）
    this.screenHeight = document.body.clientHeight  // 这里是给到了一个默认值 （这个很重要）
    this.applyId = applyId
    this.showhelper = false
    this.threejs_dev = {
        stats: '',
        gui: '',
        container: '',
        scene: '',
        camera: '',
        renderer: '',
        controls: '',
        objects: '',
        meshs: [],
        tween: {
            obj: {},
            opts: {
                range: 800,
                duration: 2500,
                delay: 200,
                easing: 'Elastic.EaseInOut',
                position: {
                    x: 0,
                    y: 0,
                    z: 0,
                },
                scale: {
                    x: 1,
                    y: 1,
                    z: 1
                }
            }
        }
    }
    this.start = function () {
        this.initStats()
        this.init_renderer()
        this.init_container()
        this.init_scene()
        this.init_camera()
        this.init_helper()
        this.init_objects()
        this.init_controls()
        this.do_render()
        // this.do_animate()
    }
    this.box = {
        r : this.screenWidth
    }
    this.test = function (a, b) {
        console.log(a, b)
    }
    this.initGui = function () {
        var Options = function () {
            this.color0 = "#ffae23"; // CSS string
            this.color1 = [0, 128, 255]; // RGB array
            this.color2 = [0, 128, 255, 0.3]; // RGB with alpha
            this.color3 = {h: 350, s: 0.9, v: 0.3}; // Hue, saturation, value
        };
        var options = new Options();
        var gui = new DAT.GUI();
        gui.addColor(options, 'color0');
        gui.addColor(options, 'color1');
        gui.addColor(options, 'color2');
        gui.addColor(options, 'color3');
    }
    this.initStats = function () {
        var old_stats = document.getElementById('threejs_stats')
        if (old_stats !== null) {
            document.body.removeChild(old_stats);
        }
        this.threejs_dev.stats = new Stats()
        this.threejs_dev.stats.setMode(0);
        this.threejs_dev.stats.domElement.id = 'threejs_stats'
        this.threejs_dev.stats.domElement.style.position = 'fixed';
        this.threejs_dev.stats.domElement.style.left = 100 + 'px';
        this.threejs_dev.stats.domElement.style.top = 30 + 'px';
//                this.threejs_dev.stats.domElement.style.width = '300px';
//                this.threejs_dev.stats.domElement.style.height = '100px';
        document.body.appendChild(this.threejs_dev.stats.domElement);
    }
    this.initTween = function () {
        var vm = this
        var update = function () {
            vm.threejs_dev.meshs['move_cube'].position.x = current.x;
        }
        var current = {x: -vm.threejs_dev.tween.opts.range};
        TWEEN.removeAll();
        var easing = TWEEN.Easing[vm.threejs_dev.tween.opts.easing.split('.')[0]][vm.threejs_dev.tween.opts.easing.split('.')[1]];
        var tweenHead = new TWEEN.Tween(current)
            .to({x: +vm.threejs_dev.tween.opts.range}, vm.threejs_dev.tween.opts.duration)
            .delay(vm.threejs_dev.tween.opts.delay)
            .easing(easing)
            .onUpdate(update);
        var tweenBack = new TWEEN.Tween(current)
            .to({x: -vm.threejs_dev.tween.opts.range}, vm.threejs_dev.tween.opts.duration)
            .delay(vm.threejs_dev.tween.opts.delay)
            .easing(easing)
            .onUpdate(update);
        tweenHead.chain(tweenBack);
        tweenBack.chain(tweenHead);
        tweenHead.start();
    }
    this.do_change_render = function () {
//                this.threejs_dev.renderer.setSize(500,500);


    }
    this.init_renderer = function () {
        this.threejs_dev.renderer = new THREE.WebGLRenderer();
        this.threejs_dev.renderer.setClearColor(0x000000, 1);
        this.threejs_dev.renderer.setPixelRatio(window.devicePixelRatio);
        this.threejs_dev.renderer.setSize(this.screenWidth, this.screenHeight);
        this.threejs_dev.renderer.shadowMap.enabled = true
        this.threejs_dev.renderer.shadowMap.type = THREE.PCFSoftShadowMap;
        this.threejs_dev.renderer.gammaInput = true;
        this.threejs_dev.renderer.gammaOutput = true;

    }
    this.init_container = function () {
        this.threejs_dev.container = document.createElement('div');
        document.getElementById(this.applyId).appendChild(this.threejs_dev.container)
        this.threejs_dev.container.id = 'container_div'
        this.threejs_dev.container.style.fontSize = 0
        this.threejs_dev.container.appendChild(this.threejs_dev.renderer.domElement);
    }
    this.init_scene = function () {
        this.threejs_dev.scene = new THREE.Scene();
        this.threejs_dev.scene.fog = new THREE.FogExp2(0xcccccc, 0.002);
    }
    this.init_camera = function () {
        this.threejs_dev.camera = new THREE.PerspectiveCamera(55, this.screenWidth / this.screenHeight, 0.1, 100000);
        this.threejs_dev.camera.position.set(0, 0,12);
        this.threejs_dev.camera.lookAt(new THREE.Vector3(0, 0, 0))
    }
    this.init_controls = function () {
        this.threejs_dev.controls = new THREE.TrackballControls(this.threejs_dev.camera, this.threejs_dev.renderer.domElement);

        this.threejs_dev.controls.rotateSpeed = 0.25;
        this.threejs_dev.controls.zoomSpeed = 0.4;
        this.threejs_dev.controls.panSpeed = 0.1;
        this.threejs_dev.controls.noZoom = false;
        this.threejs_dev.controls.noPan = false;
        this.threejs_dev.controls.staticMoving = false;
        this.threejs_dev.controls.dynamicDampingFactor = 0.1;
        this.threejs_dev.controls.keys = [65, 83, 68];
        // this.threejs_dev.controls.target = new THREE.Vector3(0, 0, -15);
//                this.threejs_dev.controls.addEventListener('mousemove', this.do_render);


    }

    this.init_helper = function () {
        var vm = this
        /*环境光*/
        var ambient = new THREE.AmbientLight(0xe8e2e2, 0.4);
        this.threejs_dev.scene.add(ambient);

        // var floor_material = new THREE.MeshPhongMaterial({color: 0x666666, dithering: true});
        var floor_material = new THREE.MeshPhongMaterial({color: 0x666666, dithering: true});
        var floor_geometry = new THREE.BoxGeometry(this.screenWidth, 1, this.screenWidth);
        var floor_mesh = new THREE.Mesh(floor_geometry, floor_material);
        floor_mesh.position.set(0, -this.screenWidth/2, 0);
        floor_mesh.receiveShadow = true;
        this.threejs_dev.scene.add(floor_mesh);


        var spotLight = new THREE.SpotLight(0xffffff, 1);
        spotLight.position.set(50, 50, 0);
        spotLight.angle = Math.PI / 6;
        spotLight.penumbra = 0.05;
        spotLight.decay = 2;
        spotLight.distance = 200;
        spotLight.castShadow = true;
        spotLight.shadow.mapSize.width = 1024;
        spotLight.shadow.mapSize.height = 1024;
        spotLight.shadow.camera.near = 10;
        spotLight.shadow.camera.far = 200;
        this.threejs_dev.scene.add(spotLight);
        if(this.showhelper){
             var lightHelper = new THREE.SpotLightHelper(spotLight);
            this.threejs_dev.scene.add(lightHelper);
             var shadowCameraHelper = new THREE.CameraHelper(spotLight.shadow.camera);
        this.threejs_dev.scene.add(shadowCameraHelper);
        }
       
       
        this.threejs_dev.scene.add(new THREE.AxisHelper(10));

  if(this.showhelper){
        var size = this.box.r ;
        var step = 30;
        var gridHelper = new THREE.GridHelper( size, step );
        this.threejs_dev.scene.add( gridHelper );

}
        var sphere = new THREE.SphereGeometry();
        var object = new THREE.Mesh( sphere, new THREE.MeshBasicMaterial(0xff0000) );
        var box = new THREE.BoxHelper( object );
        this.threejs_dev.scene.add( box );

        /*平行光*/
        var directionalLight = new THREE.DirectionalLight(0xffffff, 8);
        directionalLight.position.set(0, 0, 30);

        this.threejs_dev.scene.add(directionalLight);
  if(this.showhelper){
        var directionalLightHelper = new THREE.DirectionalLightHelper(directionalLight);
        this.threejs_dev.scene.add(directionalLightHelper);
        var shadowCameraHelper_directionalLight = new THREE.CameraHelper(directionalLight.shadow.camera);
        this.threejs_dev.scene.add(shadowCameraHelper_directionalLight);
}
        /*半球/自然光*/
        var hemisphereLight = new THREE.HemisphereLight(0xfffff0, 0x101020, 0.2)
        hemisphereLight.position.set(0, 0, 6)
        // this.threejs_dev.scene.add(hemisphereLight)
         if(this.showhelper){ 
        var hemisphereLightHelper = new THREE.HemisphereLightHelper(hemisphereLight);
        this.threejs_dev.scene.add(hemisphereLightHelper);
}
        /*点光源/精灵*/

        var sprite_textureUrl = '../api/lib/threejs/light/blue_particle.jpg'
        var sprite_texture = new THREE.TextureLoader().load(sprite_textureUrl)
        var sprite_material = new THREE.SpriteMaterial({
            map: sprite_texture,
            blending: THREE.AdditiveBlending,
        })
        var sprite = new THREE.Sprite(sprite_material)
        sprite.scale.x = 5
        sprite.scale.y = 5;
        sprite.position.set(0, 0, 0.1)
        this.threejs_dev.scene.add(sprite);





    }
    this.init_objects = function () {
        var vm = this
//
//         var floorcenter_material = new THREE.MeshPhongMaterial({color: 0x4080ff, dithering: false});
//         var floorcenter_geometry = new THREE.BoxGeometry(3, 1, 2);
//         var floorcenter_mesh = new THREE.Mesh(floorcenter_geometry, floorcenter_material);
//         floorcenter_mesh.position.set(10, 10, 0);
//         floorcenter_mesh.castShadow = true;
// //        this.threejs_dev.scene.add( floorcenter_mesh );

        var line_material = new THREE.MeshPhongMaterial({

            blending: THREE.AdditiveBlending,
//          color:0x1aec1a,
            side: THREE.DoubleSide,
//          depthWrite	: false,
            transparent: false
        })
        var line_geometry = new THREE.CylinderBufferGeometry(0.3, 0.3, this.screenWidth / 2, 64);
        var line_mesh = new THREE.Mesh(line_geometry, line_material)
        line_mesh.rotation.x = 1 / 2 * Math.PI
        line_mesh.position.set(0, 0, -this.screenWidth / 4);
        line_mesh.castShadow = true;
        this.threejs_dev.scene.add(line_mesh)
        this.addMeshToScene('line_mesh', line_mesh)

        var o_mesh_objs = []
        for (var i = 1; i <= 100; i++) {
            var o_geometry = new THREE.TorusBufferGeometry(0.3, 0.1, 32, 300);

            var o_material = new THREE.MeshLambertMaterial(
                {
                    color: 0x1aec1a,
                    emissive: 0x53f953,
                    side:
                    THREE.DoubleSide
                })
            var o_mesh = new THREE.Mesh(o_geometry, o_material);
            o_mesh.position.set(0, 0, 0 - (i * vm.screenWidth / 200))
            o_mesh.castShadow = true;
            this.threejs_dev.scene.add(o_mesh);

            o_mesh_objs.push(o_mesh)
        }

        this.addMeshToScene('o_mesh_objs', o_mesh_objs)
    }
    this.init_resize = function () {
        var vm = this

        this.threejs_dev.camera.aspect = this.screenWidth / this.screenHeight
        this.threejs_dev.camera.updateProjectionMatrix();

        this.threejs_dev.renderer.setSize(this.screenWidth, this.screenHeight);
    }
    this.init_mouse_move = function (event) {
        this.mouse_move.mouseX = ( event.clientX - this.screenWidth / 2 );
        this.mouse_move.mouseY = ( event.clientY - this.screenHeight / 2 ) * 1;
    }
    this.do_render = function () {
        var vm = this
        var delta = 10
//        console.log("this.threejs_dev.meshs['laserBeam'].object3d",this.threejs_dev.meshs['laserBeam'].object3d)
//        this.threejs_dev.meshs['line_object3d'].rotation.x += 0.4 * delta;
//        this.threejs_dev.meshs['line_object3d'].rotation.y += 0.5 * delta;
//        var o_mesh_speed = Math.abs(this.threejs_dev.meshs.o_mesh.position.z)/vm.screenWidth/2 * 18
//        if(o_mesh_speed < 1.5){
//          o_mesh_speed = 1.5
//        }
        /*光圈移动*/
        var o_mesh_len = vm.screenWidth / 100
        vm.threejs_dev.meshs.o_mesh_objs.forEach(function (v, k) {
            if (vm.threejs_dev.meshs.o_mesh_objs[k].position.z >= 0) {
                vm.threejs_dev.meshs.o_mesh_objs[k].position.z = -vm.screenWidth / 2
            }
            vm.threejs_dev.meshs.o_mesh_objs[k].position.z += o_mesh_len / 60
        })
        /*点光源移动*/
        var point_light_speed = 2
        for (var k in vm.threejs_dev.meshs.point_light_move) {
            if (vm.threejs_dev.meshs.point_light_move[k].position.z >= 0) {
                vm.threejs_dev.meshs.point_light_move[k].position.z = -vm.screenWidth / 2
            }
            vm.threejs_dev.meshs.point_light_move[k].position.z += point_light_speed
        }

        this.threejs_dev.renderer.render(this.threejs_dev.scene, this.threejs_dev.camera);
        // this.camera_animation()

    }
    this.do_animate = function () {
        // requestAnimationFrame(this.do_animate);
        // this.threejs_dev.controls.update();
        // this.threejs_dev.stats.update()
        // this.do_render();
    }
    this.do_resize = function () {
        this.screenWidth = document.body.clientWidth   // 这里是给到了一个默认值 （这个很重要）
        this.screenHeight = document.body.clientHeight  // 这里是给到了一个默认值 （这个很重要）
        this.initStats()
        this.threejs_dev.renderer.setSize(this.screenWidth, this.screenHeight);
        this.init_camera()
        this.init_controls()
    }
    this.group_init_particle = function () {
        var particlesData = [];
        var maxParticleCount = 2;
        var r = this.box.r;
        var rHalf = r / 2;
        this.addMeshToScene('rHalf', rHalf)
        this.addMeshToScene('maxParticleCount', maxParticleCount)

        var effectController = {
            showDots: true,
            showLines: true,
            minDistance: 800,
            limitConnections: false,
            maxConnections: 20,
        };
        this.addMeshToScene('effectController', effectController)
        var helper = new THREE.BoxHelper(new THREE.Mesh(new THREE.BoxGeometry(r, r, r)));
        helper.material.color.setHex(0x080808);
        helper.material.blending = THREE.AdditiveBlending;
        helper.material.transparent = true;
        this.threejs_dev.scene.add(helper)


        this.addMeshToScene('group_particle_helper', helper)



        var particlePositions = new Float32Array(maxParticleCount * 3);
        console.log('maxParticleCount', maxParticleCount)

        for (var i = 0; i < maxParticleCount; i++) {
            var x = Math.random() * r - r / 2;
            var y = Math.random() * r - r / 2;
            var z = Math.random() * r - r / 2;
            particlePositions[i * 3] = x;
            particlePositions[i * 3 + 1] = y;
            particlePositions[i * 3 + 2] = z;

            // add it to the geometry
            // particlesData.push( {
            // velocity: new THREE.Vector3( -1 + Math.random() * 2, -1 + Math.random() * 2,  -1 + Math.random() * 2 ),
            // numConnections: 0
            // } );
        }
        this.addMeshToScene('particlePositions', particlePositions)
        // this.addMeshToScene('particlesData',particlesData)

        // particles.setDrawRange( 0, maxParticleCount );
        var particles = new THREE.BufferGeometry();

        var segments = maxParticleCount * maxParticleCount;
        this.addMeshToScene('segments', segments)
        var positions = new Float32Array(segments * 3);
        var colors = new Float32Array(segments * 3);
        this.addMeshToScene('positions', positions)
        this.addMeshToScene('colors', colors)

        particles.addAttribute('position', new THREE.BufferAttribute(particlePositions, 3).setDynamic(true));
        var pMaterial = new THREE.PointsMaterial({
            color: 0xFFFFFF,
            size: 13,
            blending: THREE.AdditiveBlending,
            transparent: true,
            sizeAttenuation: false
        });
        pointCloud = new THREE.Points(particles, pMaterial);
        this.threejs_dev.scene.add(pointCloud)
        this.addMeshToScene('pointCloud', pointCloud)
        positions = new Float32Array([
            -1.0, -1.0, 1.0,
            1.0, -1.0, 1.0,
            1.0, 1.0, 1.0,

        ]);
        var geometry = new THREE.BufferGeometry();
        geometry.addAttribute( 'position', new THREE.BufferAttribute( positions, 3 ).setDynamic( true ) );
        geometry.addAttribute( 'color', new THREE.BufferAttribute( colors, 3 ).setDynamic( true ) );
        geometry.computeBoundingSphere();
        geometry.setDrawRange( 0, 0 );
        var material = new THREE.LineBasicMaterial( {
            vertexColors: THREE.VertexColors,
            blending: THREE.AdditiveBlending,
            transparent: false
        } );
        var linesMesh = new THREE.LineSegments( geometry, material );
        this.threejs_dev.scene.add(linesMesh)
        this.addMeshToScene('group_init_particle_linesMesh',linesMesh)
        var geometry_test = new THREE.BufferGeometry();
// create a simple square shape. We duplicate the top left and bottom right
// vertices because each vertex needs to appear once per triangle.
        var vertices = new Float32Array([
            -1.0, -1.0, 1.0,
            1.0, -1.0, 1.0,
            1.0, 1.0, 1.0,

        ]);


// itemSize = 3 because there are 3 values (components) per vertex
        console.log('geometry',geometry)
        geometry_test.addAttribute('position', new THREE.BufferAttribute(vertices, 3));
        console.log('geometry_test',geometry_test)
        var material = new THREE.MeshBasicMaterial({color: 0xffffff});
        var mesh = new THREE.Mesh(geometry_test, material);
        // this.threejs_dev.scene.add(mesh)

    }

    this.group_init_particle_animate = function () {

        // var vertexpos = 0;
        // var colorpos = 0;
        // var numConnected = 0;
        // for ( var i = 0; i <  this.threejs_dev.meshsmaxParticleCount; i++ )
        //     this.threejs_dev.meshs.particlesData[ i ].numConnections = 0;
        // for ( var i = 0; i < this.threejs_dev.meshsmaxParticleCount; i++ ) {
        //     // get the particle
        //     var particleData = this.threejs_dev.meshs.particlesData[i];
        //     this.threejs_dev.meshs.particlePositions[ i * 3     ] += particleData.velocity.x;
        //     this.threejs_dev.meshs.particlePositions[ i * 3 + 1 ] += particleData.velocity.y;
        //     this.threejs_dev.meshs.particlePositions[ i * 3 + 2 ] += particleData.velocity.z;
        //     if ( this.threejs_dev.meshs.particlePositions[ i * 3 + 1 ] < -this.threejs_dev.meshs.rHalf || this.threejs_dev.meshs.particlePositions[ i * 3 + 1 ] > this.threejs_dev.meshs.rHalf )
        //         particleData.velocity.y = -particleData.velocity.y;
        //     if ( this.threejs_dev.meshs.particlePositions[ i * 3 ] < -this.threejs_dev.meshs.rHalf || this.threejs_dev.meshs.particlePositions[ i * 3 ] > this.threejs_dev.meshs.rHalf )
        //         particleData.velocity.x = -particleData.velocity.x;
        //     if ( this.threejs_dev.meshs.particlePositions[ i * 3 + 2 ] < -this.threejs_dev.meshs.rHalf || this.threejs_dev.meshs.particlePositions[ i * 3 + 2 ] > this.threejs_dev.meshs.rHalf )
        //         particleData.velocity.z = -particleData.velocity.z;
        //     if ( this.threejs_dev.meshs.effectController.limitConnections && particleData.numConnections >= this.threejs_dev.meshs.effectController.maxConnections )
        //         continue;
        //     // Check collision
        //     for ( var j = i + 1; j < this.threejs_dev.meshsmaxParticleCount; j++ ) {
        //         var particleDataB = this.threejs_dev.meshs.particlesData[ j ];
        //         if ( this.threejs_dev.meshs.effectController.limitConnections && particleDataB.numConnections >= this.threejs_dev.meshs.effectController.maxConnections )
        //             continue;
        //         var dx = this.threejs_dev.meshs.particlePositions[ i * 3     ] - this.threejs_dev.meshs.particlePositions[ j * 3     ];
        //         var dy = this.threejs_dev.meshs.particlePositions[ i * 3 + 1 ] - this.threejs_dev.meshs.particlePositions[ j * 3 + 1 ];
        //         var dz = this.threejs_dev.meshs.particlePositions[ i * 3 + 2 ] - this.threejs_dev.meshs.particlePositions[ j * 3 + 2 ];
        //         var dist = Math.sqrt( dx * dx + dy * dy + dz * dz );
        //         if ( dist < this.threejs_dev.meshs.effectController.minDistance ) {
        //             particleData.numConnections++;
        //             particleDataB.numConnections++;
        //             var alpha = 1.0 - dist / this.threejs_dev.meshs.effectController.minDistance;
        //             this.threejs_dev.meshs.positions[ vertexpos++ ] = this.threejs_dev.meshs.particlePositions[ i * 3     ];
        //             this.threejs_dev.meshs.positions[ vertexpos++ ] = this.threejs_dev.meshs.particlePositions[ i * 3 + 1 ];
        //             this.threejs_dev.meshs.positions[ vertexpos++ ] = this.threejs_dev.meshs.particlePositions[ i * 3 + 2 ];
        //             this.threejs_dev.meshs.positions[ vertexpos++ ] = this.threejs_dev.meshs.particlePositions[ j * 3     ];
        //             this.threejs_dev.meshs.positions[ vertexpos++ ] = this.threejs_dev.meshs.particlePositions[ j * 3 + 1 ];
        //             this.threejs_dev.meshs.positions[ vertexpos++ ] = this.threejs_dev.meshs.particlePositions[ j * 3 + 2 ];
        //             this.threejs_dev.meshs.colors[ colorpos++ ] = alpha;
        //             this.threejs_dev.meshs.colors[ colorpos++ ] = alpha;
        //             this.threejs_dev.meshs.colors[ colorpos++ ] = alpha;
        //             this.threejs_dev.meshs.colors[ colorpos++ ] = alpha;
        //             this.threejs_dev.meshs.colors[ colorpos++ ] = alpha;
        //             this.threejs_dev.meshs.colors[ colorpos++ ] = alpha;
        //             numConnected++;
        //         }
        //     }
        // }
        // // this.threejs_dev.meshs.group_init_particle_linesMesh.geometry.setDrawRange( 0, numConnected * 2 );
        // // this.threejs_dev.meshs.group_init_particle_linesMesh.geometry.attributes.position.needsUpdate = true;
        // // this.threejs_dev.meshs.group_init_particle_linesMesh.geometry.attributes.color.needsUpdate = true;
        // this.threejs_dev.meshs.pointCloud.geometry.attributes.position.needsUpdate = true;
    }
    this.group_init_line = function () {
        var segments = 1000;
        var geometry = new THREE.BufferGeometry();
        var material = new THREE.LineBasicMaterial({ vertexColors: THREE.VertexColors });
        var positions = new Float32Array( segments * 3 );
        var colors = new Float32Array( segments * 3 );
        var r = 50;
        for ( var i = 0; i < segments; i ++ ) {
            var x = Math.random() * r - r / 2;
            var y = Math.random() * r - r / 2;
            var z = Math.random() * r - r / 2;
            // positions
            positions[ i * 3 ] = x;
            positions[ i * 3 + 1 ] = y;
            positions[ i * 3 + 2 ] = z;
            // colors
            colors[ i * 3 ] = ( x / r ) + 0.5;
            colors[ i * 3 + 1 ] = ( y / r ) + 0.5;
            colors[ i * 3 + 2 ] = ( z / r ) + 0.5;
        }
        geometry.addAttribute( 'position', new THREE.BufferAttribute( positions, 3 ) );
        geometry.addAttribute( 'color', new THREE.BufferAttribute( colors, 3 ) );
        geometry.computeBoundingSphere();
        mesh = new THREE.LineSegments( geometry, material );
        this.threejs_dev.scene.add( mesh );
    }
    /*###### 扩展方法  #############*/
    this.addMeshToScene = function (name, mesh) {
        this.threejs_dev.meshs[name] = mesh
    }
    this.generateLaserBodyCanvas = function () {
        // init canvas
        var canvas = document.createElement('canvas');
        var context = canvas.getContext('2d');
        canvas.width = 1;
        canvas.height = 1;
        // set gradient
        var gradient = context.createLinearGradient(0, 0, canvas.width, canvas.height);
//        gradient.addColorStop(0, 'rgba(  0,  0,  0,0.1)');
//        gradient.addColorStop(0.1, 'rgba(160,160,160,0.3)');
//        gradient.addColorStop(0.5, 'rgba(255,255,255,0.5)');
//        gradient.addColorStop(0.9, 'rgba(160,160,160,0.3)');
//        gradient.addColorStop(1.0, 'rgba(  0,  0,  0,0.1)');
//        gradient.addColorStop(0, 'rgba(  255,  255,  255,0.1)');
//        gradient.addColorStop(0.1, 'rgba(255,255,255,0.3)');
//        gradient.addColorStop(0.5, 'rgba(255,255,255,0.5)');
//        gradient.addColorStop(0.9, 'rgba(255,255,255,0.3)');
        gradient.addColorStop(0, 'rgba(  0,  0, 0,1)');
        gradient.addColorStop(1.0, 'rgba(  255,  255, 255,1)');
        // fill the rectangle
        context.fillStyle = gradient;
        context.fillRect(0, 0, canvas.width, canvas.height);
        // return the just built canvas
        return canvas;
    }
    this.createCanvasMaterial = function (color, size) {
        var matCanvas = document.createElement('canvas');
        matCanvas.width = matCanvas.height = size;
        var matContext = matCanvas.getContext('2d');
        // create exture object from canvas.
        var texture = new THREE.Texture(matCanvas);
        // Draw a circle
        var center = size / 2;
        matContext.beginPath();
        matContext.arc(center, center, size / 2, 0, 2 * Math.PI, false);
        matContext.closePath();
        matContext.fillStyle = color;
        matContext.fill();
        // need to set needsUpdate
        texture.needsUpdate = true;
        // return a texture made from the canvas
        return texture;
    }
    this.getRandomAround = function (len) {
        return Math.random() > 0.5 ? 1 : -1 * Math.random() * len
    }

    this.createMesh = function (originalGeometry, scene, scale, x, y, z, color, dynamic) {
        var i, c;
        var vertices = originalGeometry.vertices;
        var vl = vertices.length;
        var geometry = new THREE.Geometry();
        var vertices_tmp = [];
        for (i = 0; i < vl; i++) {
            p = vertices[i];
            geometry.vertices[i] = p.clone();
            vertices_tmp[i] = [p.x, p.y, p.z, 0, 0];
        }
        var clones = [
            [6000, 0, -4000],
            [5000, 0, 0],
            [1000, 0, 5000],
            [1000, 0, -5000],
            [4000, 0, 2000],
            [-4000, 0, 1000],
            [-5000, 0, -5000],
            [0, 0, 0]
        ];
        if (dynamic) {
            for (i = 0; i < clones.length; i++) {
                c = ( i < clones.length - 1 ) ? 0x252525 : color;
                mesh = new THREE.Points(geometry, new THREE.PointsMaterial({size: 30, color: c}));
                mesh.scale.x = mesh.scale.y = mesh.scale.z = scale;
                mesh.position.x = x + clones[i][0];
                mesh.position.y = y + clones[i][1];
                mesh.position.z = z + clones[i][2];
                parent.add(mesh);
                clonemeshes.push(
                    {
                        mesh: mesh,
                        speed: 0.5 + Math.random()
                    });
            }
        } else {
            mesh = new THREE.Points(geometry, new THREE.PointsMaterial(
                {
                    size: 30,
                    color: color
                }
            ));
            mesh.scale.x = mesh.scale.y = mesh.scale.z = scale;
            mesh.position.x = x;
            mesh.position.y = y;
            mesh.position.z = z;
            parent.add(mesh);
        }
        meshes.push({
            mesh: mesh,
            vertices: geometry.vertices,
            vertices_tmp: vertices_tmp,
            vl: vl,
            down: 0,
            up: 0,
            direction: 0,
            speed: 35,
            delay: Math.floor(200 + 200 * Math.random()),
            started: false,
            start: Math.floor(100 + 200 * Math.random()),
            dynamic: dynamic
        });
    }
    this.camera_animation = function () {
        this.threejs_dev.camera.position.set(0, 0, 20);
    }
}


