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

gulp.task("minify-pages-js", function () {
  return gulp
    .src("./template-pages/**/*.js")
    .pipe(uglify())
    .pipe(gulp.dest("./static/js/template-pages/"))
    .pipe(browserSync.stream());
});

gulp.task("minify-archives-js", function () {
  return gulp
    .src("./template-archives/**/*.js")
    .pipe(uglify())
    .pipe(gulp.dest("./static/js/template-archives/"))
    .pipe(browserSync.stream());
});

gulp.task("serve", function () {
  browserSync.init({
    proxy: process.env.LOCAL_SITE,
  });

  gulp.watch("./components/**/*.scss", gulp.series("sass"));
  gulp.watch("./src/scss/**/*.scss", gulp.series("sass"));
  gulp.watch("./components/**/*.js", gulp.series("minify-components-js"));
  gulp.watch("./template-pages/**/*.js", gulp.series("minify-pages-js"));
  gulp.watch("./template-archives/**/*.js", gulp.series("minify-archives-js"));
  gulp.watch("./src/js/*.js", gulp.series("scripts"));
  gulp.watch("**/*").on("change", browserSync.reload);
  //Components Garbage Collector
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
    //Page templates garbage collector
    chokidar
    .watch("./template-pages/", { ignored: /(^|[\/\\])\../ })
    .on("unlinkDir", function (dirPath) {
      const templateName = dirPath.split("\\").pop();
      console.log("Successfuly Deleted Page Template: ", templateName);
      deleteSync(['static/js/template-pages/' + `${templateName}`, '!static/js/template-pages/']);
      gulp
        .src("./src/scss/main.scss")
        .pipe(
          replace(
            //the quotes on the line below must be single quotes, otherwise it will not recognize any text
            `@import './template-pages/${templateName}/page-${templateName}';`,
            ""
          )
        )
        .pipe(gulp.dest("./src/scss/"));
    });
    //Archives garbage collector
    chokidar
    .watch("./template-archives/", { ignored: /(^|[\/\\])\../ })
    .on("unlinkDir", function (dirPath) {
      const archiveName = dirPath.split("\\").pop();
      console.log("Successfuly Deleted Archive Template: ", archiveName);
      deleteSync(['static/js/template-archives/' + `${archiveName}`, '!static/js/template-archives/']);
      gulp
        .src("./src/scss/main.scss")
        .pipe(
          replace(
            //the quotes on the line below must be single quotes, otherwise it will not recognize any text
            `@import './template-archives/${archiveName}/archive-${archiveName}';`,
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
    "minify-pages-js",
    "minify-archives-js",
    "serve",
    function (done) {
      console.log("Gulp is running...");
      done();
    }
  )
);
