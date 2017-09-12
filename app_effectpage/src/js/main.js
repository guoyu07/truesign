$(document).ready(function () {
    $('#time-line').css('width',document.body.clientWidth)
    $('#time-line').css('height',document.body.clientHeight)
    var timeline = new timeLine('time-line')
    timeline.start()
    $(window).resize(function () {
        timeline.do_resize()
    })
    timeline.group_init_particle()
    timeline.group_init_line()
    // timeline.init_dynamically_line()
    function do_animate(){
        requestAnimationFrame(do_animate)
        timeline.threejs_dev.controls.update();
        timeline.threejs_dev.stats.update()
        timeline.do_render();
        timeline.group_init_particle_animate();
        timeline.move_point_light_animate();
        timeline.point_light_animate();
        // timeline.init_dynamically_line_animate();
    }
    do_animate()

})