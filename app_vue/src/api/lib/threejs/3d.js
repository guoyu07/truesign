const THREE = require('three');
const TweenLite = require('gsap');

var particles3d = {
    className: "nodes-container",
    maxNodes: 200,
    maxEdges: 1e5,
    maxEdgeLength:  Math.pow(45, 2),
    maxLineOpacity: .3,
    depth: 125,
    particlesCenter: {
        x: 0,
        y: 0,
        z: 350
    },
    cameraYRot: 8 * Math.PI / 180,
    cameraXRot: 14 * Math.PI / 180,
    addCube: function (e, t, i) {
        var s = new THREE.BoxGeometry(4, 4, 4),
            r = new THREE.MeshBasicMaterial({
                color: 16776960
            }),
            a = new THREE.Mesh(s, r);
        a.position.x = e, a.position.y = t, a.position.z = i, this.scene.add(a)
    },
    initialize: function () {
        this.isAnimating = !1, this.listenTo(PubSub, "state:change", this.onStateChange), this.renderer = new THREE.WebGLRenderer({
            alpha: !0
        }), this.renderer.setPixelRatio(window.devicePixelRatio || 1), this.renderer.setSize(window.innerWidth, window.innerHeight), this.$el.insertAfter($(".bg-video")), this.$el.append(this.renderer.domElement), TweenLite.set(this.$el, {
            opacity: 0
        }), this.scene = new THREE.Scene, this.lookAt = new THREE.Vector3(0, 0, -1 * this.particlesCenter.z), this.createCamera(), this.createWorld(), this.listenTo(PubSub, "content:visible", function () {
            this.fadeView(1, .5), this.start()
        }.bind(this)), Features.isTouch || this.trackMouse();
        var e = _.debounce(this.onWindowResize.bind(this), 500);
        this.listenTo(PubSub, "window:resize", function (t) {
            this.isResizing = !0, this.stop(), e(t)
        })
    },
    trackMouse: function () {
        this.mouse = new THREE.Vector2;
        var e = _.throttle(this.updateCamera.bind(this), 100);
        window.addEventListener("mousemove", function (t) {
            this.mouse.x = t.clientX, this.mouse.y = t.clientY, e()
        }.bind(this))
    },
    createCamera: function () {
        this.camera = new THREE.PerspectiveCamera(40, window.innerWidth / window.innerHeight, 1, 1e3), this.camera.lookAt(this.lookAt)
    },
    computeBounds: function () {
        this.particlePlane = new THREE.Plane(new THREE.Vector3(0, 0, 1), this.particlesCenter.z), this.BOUNDS = {
            x: [0, 0],
            y: [0, 0],
            z: [-1 * this.particlesCenter.z - this.depth, -1 * this.particlesCenter.z + this.depth]
        };
        var e = Helpers.twoToThree(new THREE.Vector3(-1, 1, 0), this.camera, this.particlePlane),
            t = Helpers.twoToThree(new THREE.Vector3(1, -1, 0), this.camera, this.particlePlane);
        this.BOUNDS.x[0] = e.x, this.BOUNDS.x[1] = t.x, this.BOUNDS.y[0] = t.y, this.BOUNDS.y[1] = e.y
    },
    createWorld: function () {
        this.computeBounds(), this.createParticles(), this.createLines()
    },
    onWindowResize: function (e) {
        TweenLite.killTweensOf(this.camera.position), TweenLite.killTweensOf(this.camera.rotation), this.clean(), this.renderer.setSize(e.width, e.height), this.createCamera(), this.createWorld(), this.start(), this.isResizing = !1
    },
    createParticles: function () {
        this.particles = new THREE.Geometry, this.particlesList = [];
        for (var e = this.maxNodes, t = new THREE.PointCloudMaterial({
            color: 16777215,
            map: THREE.ImageUtils.loadTexture("/assets/img/particle-gl.png"),
            size: 9,
            transparent: !0,
            vertexColors: THREE.VertexColors,
            depthTest: !1,
            opacity: .7
        }), i = 0; e > i; i++) {
            var s = new Particle(this.BOUNDS, i);
            this.particles.vertices.push(s.vertex), this.particles.colors.push(s.color), this.particlesList.push(s)
        }
        this.particleSystem = new THREE.PointCloud(this.particles, t), this.scene.add(this.particleSystem)
    },
    updateParticles: function () {
        for (var e = 0; e < this.particlesList.length; e++) {
            var t = this.particlesList[e];
            t.update()
        }
        this.particleSystem.geometry.verticesNeedUpdate = !0
    },
    createLines: function () {
        var e = require("../shaders/line.vert"),
            t = require("../shaders/line.frag"),
            i = new THREE.ShaderMaterial({
                vertexShader: e(),
                fragmentShader: t(),
                vertexColors: THREE.VertexColors,
                transparent: !0,
                depthWrite: !1
            });
        this.lineGeometry = new THREE.Geometry;
        for (var s = 0, r = !1, a = 0; a < this.particlesList.length; a++) {
            var n = this.particlesList[a];
            if (!(n.age < 1)) {
                var o = 0;
                if (r) break;
                for (var h = a; h < this.particlesList.length; h++) {
                    var c = this.particlesList[h];
                    if (!(c.age < 1)) {
                        var l = n.vertex.distanceToSquared(c.vertex);
                        if (l < this.maxEdgeLength) {
                            if (s++, o++, s > this.maxEdges) {
                                s--, r = !0;
                                break
                            }
                            var d = 1,
                                p = l / this.maxEdgeLength;
                            p > .5 && (d = 2 - 2 * p);
                            var m = this.maxLineOpacity * Math.min(n.opacity, c.opacity),
                                u = new THREE.Color(d, m, d);
                            this.lineGeometry.vertices.push(n.vertex), this.lineGeometry.vertices.push(c.vertex), this.lineGeometry.colors.push(u), this.lineGeometry.colors.push(u)
                        }
                    }
                }
            }
        }
        this.lines = new THREE.Line(this.lineGeometry, i, THREE.LinePieces), this.scene.add(this.lines)
    },
    updateLines: function () {
        for (var e = 0, t = !1, i = 0; i < this.particlesList.length; i++) {
            var s = this.particlesList[i],
                r = 0;
            if (t) break;
            for (var a = i; a < this.particlesList.length; a++) {
                var n = this.particlesList[a],
                    o = s.vertex.distanceToSquared(n.vertex);
                if (o < this.maxEdgeLength) {
                    if (e += 1, r++, r > 4) {
                        e--;
                        break
                    }
                    if (e > this.maxEdges) {
                        e--, t = !0;
                        break
                    }
                    var h = 2 * (e - 1),
                        c = 1 - o / this.maxEdgeLength;
                    this.lineGeometry.vertices[h] = s.vertex, this.lineGeometry.vertices[h + 1] = n.vertex;
                    var l = this.maxLineOpacity;
                    this.lineGeometry.colors[h] ? (this.lineGeometry.colors[h].r = c, this.lineGeometry.colors[h].g = l) : this.lineGeometry.colors[h] = new THREE.Color(c, l, c), this.lineGeometry.colors[h + 1] ? (this.lineGeometry.colors[h + 1].r = c, this.lineGeometry.colors[h + 1].g = l) : this.lineGeometry.colors[h + 1] = new THREE.Color(c, l, c)
                }
            }
        }
        this.lines.geometry.verticesNeedUpdate = !0, this.lines.geometry.colorsNeedUpdate = !0
    },
    updateCamera: function () {
        if (!this.isResizing) {
            var e = this.mouse.x / window.innerWidth * 2 - 1,
                t = 2 * -(this.mouse.y / window.innerHeight) + 1;
            TweenLite.killTweensOf(this.camera.position), TweenLite.killTweensOf(this.camera.rotation);
            var i = this.camera.position.clone(),
                s = this.camera.rotation.clone(),
                r = new THREE.Euler(-50 * e, -30 * t, i.z),
                a = this.lookAt.clone();
            a.x = -5 * e, a.y = -3 * t, this.camera.position.copy(r), this.camera.lookAt(a);
            var n = this.camera.rotation.clone();
            this.camera.position.copy(i), this.camera.rotation.copy(s), TweenLite.to(this.camera.position, 25 / 30, {
                x: r.x,
                y: r.y,
                ease: Quart.easeOut
            }), TweenLite.to(this.camera.rotation, 25 / 30, {
                x: n.x,
                y: n.y,
                z: n.z,
                ease: Quart.easeOut
            })
        }
    },
    start: function () {
        this.isAnimating = !0, this.loop()
    },
    stop: function () {
        this.isAnimating = !1
    },
    loop: function () {
        this.render(), this.isAnimating && requestAnimationFrame(this.loop.bind(this))
    },
    render: function () {
        this.updateParticles(), this.scene.remove(this.lines), this.lineGeometry.dispose(), this.createLines(), this.renderer.render(this.scene, this.camera)
    },
    clean: function () {
        this.scene.remove(this.particleSystem), this.scene.remove(this.lines), this.lineGeometry.dispose(), this.particles.dispose()
    },
    onStateChange: function (e) {
        e == Globals.STATE_INTRO ? this.fadeView(1, 28 / 30) : e == Globals.STATE_FORM && this.fadeView(.6, 28 / 30)
    },
    fadeView: function (e, t) {
        TweenLite.to(this.$el, t, {
            opacity: e,
            ease: Sine.easeInOut
        })
    }
}