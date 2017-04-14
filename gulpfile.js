var gulp        = require('gulp');
var browserSync = require('browser-sync').create();


gulp.task('watch', function () {    // 这里的watch，是自定义的，写成live或者别的也行

    // app/**/*.*的意思是 app文件夹下的 任何文件夹 的 任何文件
    gulp.watch('Apps/**/*.*').on('change',browserSync.reload);
    gulp.watch('html5/**/*.*').on('change',browserSync.reload);

});

// 代理
gulp.task('browser-sync',['watch'], function() {
    browserSync.init({
        proxy: "http://localhost:5001"
    });
});
gulp.task('default',['browser-sync']);
