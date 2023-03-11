import gulp from "gulp";
import dartSass from "sass";
import gulpSass from "gulp-sass";
import autoprefixer from "gulp-autoprefixer";
import browserSync from "browser-sync";
import uglify from "gulp-uglify";
import dotenv from "dotenv";
import { deleteSync } from "del";
import replace from "gulp-replace";
import chokidar from "chokidar";

dotenv.config();

/**
 * !TODO: Find a way to add autoprefixer...
 */
gulp.task("sass", function () {
  const sass = gulpSass(dartSass);
  return gulp
    .src("./src/scss/main.scss")
    .pipe(sass({ outputStyle: "compressed" }).on("error", sass.logError))
    .pipe(autoprefixer({
			cascade: false
		}))
    .pipe(gulp.dest("./static/css/"))
    .pipe(browserSync.stream());
});

gulp.task("scripts", function () {
  return gulp
    .src("./src/js/scripts.js")
    .pipe(uglify())
    .pipe(gulp.dest("./static/js/"))
    .pipe(browserSync.stream());
});

gulp.task("minify-components-js", function () {
  return gulp
    .src("./components/**/*.js")
    .pipe(uglify())
    .pipe(gulp.dest("./static/js/components/"))
    .pipe(browserSync.stream());
});

gulp.task("garbage-collector", function () {
  chokidar
    .watch("./components/", { ignored: /(^|[\/\\])\../ })
    .on("unlinkDir", function (dirPath) {
      const componentName = dirPath.split("\\").pop();
      console.log("Successfuly Deleted Component: ", componentName);
      deleteSync(['static/js/components/' + `${componentName}`, '!static/js/components/']);
      gulp
        .src("./src/scss/main.scss")
        .pipe(
          replace(
            //the quotes on the line below must be single quotes, otherwise it will not recognize any text
            `@import './components/${componentName}/${componentName}';`,
            ""
          )
        )
        .pipe(gulp.dest("./src/scss/"));
    });
});

gulp.task("serve", function () {
  browserSync.init({
    proxy: process.env.LOCAL_SITE,
  });

  gulp.watch("./components/**/*.scss", gulp.series("sass"));
  gulp.watch("./components/**/*.js", gulp.series("minify-components-js"));
  gulp.watch("./src/js/*.js", gulp.series("scripts"));
  gulp.watch("./*.php").on("change", browserSync.reload);
  chokidar
    .watch("./components/", { ignored: /(^|[\/\\])\../ })
    .on("unlinkDir", function (dirPath) {
      const componentName = dirPath.split("\\").pop();
      console.log("Successfuly Deleted Component: ", componentName);
      deleteSync(['static/js/components/' + `${componentName}`, '!static/js/components/']);
      gulp
        .src("./src/scss/main.scss")
        .pipe(
          replace(
            //the quotes on the line below must be single quotes, otherwise it will not recognize any text
            `@import './components/${componentName}/${componentName}';`,
            ""
          )
        )
        .pipe(gulp.dest("./src/scss/"));
    });
});

gulp.task(
  "default",
  gulp.series(
    "sass",
    "scripts",
    "minify-components-js",
    "serve",
    function (done) {
      console.log("Gulp is running...");
      done();
    }
  )
);
