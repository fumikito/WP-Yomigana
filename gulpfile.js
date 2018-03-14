var gulp     = require('gulp'),
    $        = require('gulp-load-plugins')(),
    pngquant = require('imagemin-pngquant'),
    eventStream = require('event-stream');


// Sassのタスク
gulp.task('sass', function () {
  return gulp.src(['./assets/scss/**/*.scss'])
    .pipe($.plumber({
      errorHandler: $.notify.onError('<%= error.message %>')
    }))
    .pipe($.sourcemaps.init())
    .pipe($.sass({
      errLogToConsole: true,
      outputStyle    : 'compressed',
      sourceComments : false,
      sourcemap      : true,
      includePaths   : [
        './assets/scss'
      ]
    }))
    .pipe($.autoprefixer({
      browsers: ['last 2 versions'],
      cascade: false
    }))
    .pipe($.sourcemaps.write('./map'))
    .pipe(gulp.dest('./assets/css'));
});


// Minify
gulp.task('js', function () {
  return gulp.src(['./assets/js/src/**/*.js'])
    .pipe($.plumber({
      errorHandler: $.notify.onError('<%= error.message %>')
    }))
    .pipe($.sourcemaps.init({
      loadMaps: true
    }))
    .pipe($.babel({
      presets: ['env']
    }))
    .pipe($.uglify({
      output:{
        comments: /^!/
      }
    }))
    .pipe($.sourcemaps.write('./map'))
    .pipe(gulp.dest('./assets/js/dist/'));
});

// JS Hint
gulp.task('jshint', function () {
  return gulp.src(['./assets/js/src/**/*.js'])
    .pipe($.jshint('./assets/.jshintrc'))
    .pipe($.jshint.reporter('jshint-stylish'));
});


// images
gulp.task('images', function () {
  gulp.src('./assets/img/src/**/*.{gif,png,jpg}')
    .pipe($.plumber({
      errorHandler: $.notify.onError('<%= error.message %>')
    }))
    .pipe($.imagemin({
      optimizationLevel: 7,
      progressive      : true,
      svgoPlugins      : [{removeViewBox: false}],
      use              : [pngquant()]
    }))
    .pipe(gulp.dest('./assets/img/dist'));
});

// Copy library
gulp.task('copyLib', function(){
  return eventStream.merge(
    gulp.src('./node_modules/jquery-ui-mp6/src/css/**/*')
      .pipe(gulp.dest('./assets/css')),
    gulp.src('./node_modules/jquery-ui-mp6/src/images/**/*')
      .pipe(gulp.dest('./assets/images'))
  );
});

// watch
gulp.task('watch', function () {
  gulp.watch('assets/scss/**/*.scss', ['sass']);
  gulp.watch('assets/js/src/**/*.js', ['js', 'jshint']);
  gulp.watch('assets/img/src/**/*.{gif,png,jpg}', ['images']);
});

// Build
gulp.task('build', ['jshint', 'copyLib', 'js', 'sass', 'images']);

// Default Tasks
gulp.task('default', ['watch']);
