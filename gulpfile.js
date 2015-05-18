var gulp = require('gulp'),
    $ = require('gulp-load-plugins')(),
    pngquant = require('imagemin-pngquant');


// Sassのタスク
gulp.task('sass',function(){
    return gulp.src(['./assets/scss/**/*.scss'])
        .pipe($.plumber())
        .pipe($.sourcemaps.init())
        .pipe($.sass({
            errLogToConsole: true,
            outputStyle: 'compressed',
            sourceComments: 'normal',
            sourcemap: true,
            includePaths: [
                './assets/scss'
            ]
        }))
        .pipe($.sourcemaps.write('./map'))
        .pipe(gulp.dest('./assets/css'));
});


// Minify
gulp.task('js', function(){
    return gulp.src(['./assets/js/src/**/*.js'])
        .pipe($.plumber())
        .pipe($.sourcemaps.init({
            loadMaps: true
        }))
        .pipe($.uglify())
        .pipe($.sourcemaps.write('./map'))
        .pipe(gulp.dest('./assets/js/dist/'));
});

// JS Hint
gulp.task('jshint', function(){
    return gulp.src(['./assets/js/src/**/*.js'])
        .pipe($.jshint('./assets/.jshintrc'))
        .pipe($.jshint.reporter('jshint-stylish'));
});


// images
gulp.task('images', function () {
    gulp.src('./assets/img/src/**/*.{gif,png,jpg}')
        .pipe($.plumber())
        .pipe($.imagemin({
            optimizationLevel: 7,
            progressive: true,
            svgoPlugins: [{removeViewBox: false}],
            use: [pngquant()]
        }))
        .pipe(gulp.dest('./assets/img/dist'));
});


// watch
gulp.task('watch',function(){
    gulp.watch('./assets/sass/**/*.scss',['sass']);
    gulp.watch('./assets/js/src/**/*.js',['js', 'jshint']);
    gulp.watch('./assets/img/src/**/*.{gif,png,jpg}',['images']);
});

// Build
gulp.task('build', ['jshint', 'js', 'sass', 'images']);

// Default Tasks
gulp.task('default', ['watch']);
