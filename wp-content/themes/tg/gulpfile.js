const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const autoprefixer = require('gulp-autoprefixer');
const browserSync = require('browser-sync').create();
const uglify = require('gulp-uglify');
require('dotenv').config();

gulp.task('sass', function() {
  return gulp.src('./src/scss/main.scss')
    .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
    .pipe(autoprefixer({
        overrideBrowserslist: ['last 2 versions'],
      cascade: false
    }))
    .pipe(gulp.dest('./static/css/'))
    .pipe(browserSync.stream());
});

gulp.task('scripts', function() {
  return gulp.src('./src/js/scripts.js')
    .pipe(uglify())
    .pipe(gulp.dest('./static/js/'))
    .pipe(browserSync.stream());
});

gulp.task('serve', function() {
  browserSync.init({
    proxy: process.env.LOCAL_SITE

  });

  gulp.watch('./src/scss/**/*.scss', gulp.series('sass'));
  gulp.watch('./src/js/*.js', gulp.series('scripts'));
  gulp.watch('./*.php').on('change', browserSync.reload);
});

gulp.task('default', gulp.series('sass', 'scripts', 'serve'), function() {
  console.log('Gulp is running...');
});
