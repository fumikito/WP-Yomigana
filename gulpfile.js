const gulp          = require('gulp');
const $             = require('gulp-load-plugins')();
const pngquant      = require('imagemin-pngquant');
const eventStream   = require('event-stream');
const webpack       = require('webpack-stream');
const webpackBundle = require('webpack');
const named         = require('vinyl-named');


// Sassのタスク
gulp.task('sass', () => {
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
gulp.task('js', () => {
  return gulp.src(['./assets/js/src/**/*.js'])
    .pipe($.plumber({
      errorHandler: $.notify.onError('<%= error.message %>')
    }))
    .pipe($.sourcemaps.init({
      loadMaps: true
    }))
    .pipe($.babel({
      presets: ['@babel/env']
    }))
    .pipe($.uglify({
      output:{
        comments: /^!/
      }
    }))
    .pipe($.sourcemaps.write('./map'))
    .pipe(gulp.dest('./assets/js/dist/'));
});

// Compile babel
gulp.task('jsx', () => {
  return gulp.src([
    './assets/js/src/**/*.jsx',
    '!./assets/js/src/**/_*.jsx'
  ])
    .pipe($.plumber({
      errorHandler: $.notify.onError('<%= error.message %>')
    }))
    .pipe(named())
    .pipe(webpack({
      mode: 'production',
      devtool: 'source-map',
      resolve: {
        extensions:  ['.js', '.jsx']
      },
      module: {
        rules: [
          {
            test: /\.jsx$/,
            exclude: /(node_modules|bower_components)/,
            use: {
              loader: 'babel-loader',
              options: {
                presets: ['@babel/preset-env'],
                plugins: ['@babel/plugin-transform-react-jsx']
              }
            }
          }
        ]
      }
    }, webpackBundle))
    .pipe(gulp.dest('./assets/js/dist/'));
});

// JS Hint
gulp.task('jshint', () => {
  return gulp.src(['./assets/js/src/**/*.js'])
    .pipe($.jshint('./assets/.jshintrc'))
    .pipe($.jshint.reporter('jshint-stylish'));
});


// images
gulp.task('images', () => gulp.src('./assets/img/src/**/*.{gif,png,jpg}')
    .pipe($.plumber({
      errorHandler: $.notify.onError('<%= error.message %>')
    }))
    .pipe($.imagemin({
      optimizationLevel: 7,
      progressive      : true,
      svgoPlugins      : [{removeViewBox: false}],
      use              : [pngquant()]
    }))
    .pipe(gulp.dest('./assets/img/dist')
));

// Copy library
gulp.task('copyLib', () => eventStream.merge(
    gulp.src('./node_modules/jquery-ui-mp6/src/css/**/*')
      .pipe(gulp.dest('./assets/css')),
    gulp.src('./node_modules/jquery-ui-mp6/src/images/**/*')
      .pipe(gulp.dest('./assets/images'))
));

// watch
gulp.task('watch', function () {
  gulp.watch('assets/scss/**/*.scss', ['sass']);
  gulp.watch('assets/js/src/**/*.js', ['js', 'jshint']);
  gulp.watch('assets/js/src/**/*.jsx', ['jsx']);
  gulp.watch('assets/img/src/**/*.{gif,png,jpg}', ['images']);
});

// Build
gulp.task('build', ['jshint', 'copyLib', 'js', 'jsx', 'sass', 'images']);

// Default Tasks
gulp.task('default', ['watch']);
