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

// Define input and output directories
const dirs = {
  src: {
    scss: "./src/scss",
    js: "./src/js",
    components: "./components",
    pages: "./template-pages",
    archives: "./template-archives",
  },
  dest: {
    css: "./static/css",
    js: "./static/js",
  },
};

// Define JS files to be minified
const jsFiles = {
  components: `${dirs.src.components}/**/*.js`,
  pages: `${dirs.src.pages}/**/*.js`,
  archives: `${dirs.src.archives}/**/*.js`,
  scripts: `${dirs.src.js}/*.js`,
};

// Define functions to handle minification of JS files
function minifyJS(src, dest) {
  return gulp.src(src)
    .pipe(uglify())
    .pipe(gulp.dest(dest))
    .pipe(browserSync.stream());
}

gulp.task("sass", function () {
  const sass = gulpSass(dartSass);
  return gulp.src(`${dirs.src.scss}/main.scss`)
    .pipe(sass({ outputStyle: "compressed" }).on("error", sass.logError))
    .pipe(autoprefixer({ cascade: false }))
    .pipe(gulp.dest(dirs.dest.css))
    .pipe(browserSync.stream());
});

gulp.task("minify-components-js", function () {
  return minifyJS(jsFiles.components, `${dirs.dest.js}/components`);
});

gulp.task("minify-pages-js", function () {
  return minifyJS(jsFiles.pages, `${dirs.dest.js}/template-pages`);
});

gulp.task("minify-archives-js", function () {
  return minifyJS(jsFiles.archives, `${dirs.dest.js}/template-archives`);
});

gulp.task("scripts", function () {
  return minifyJS(jsFiles.scripts, dirs.dest.js);
});

gulp.task("serve", function () {
  browserSync.init({ proxy: process.env.LOCAL_SITE });

  // Watch for changes in files and execute tasks accordingly
  gulp.watch(`${dirs.src.scss}/**/*.scss`, gulp.series("sass"));
  gulp.watch(jsFiles.components, gulp.series("minify-components-js"));
  gulp.watch(jsFiles.pages, gulp.series("minify-pages-js"));
  gulp.watch(jsFiles.archives, gulp.series("minify-archives-js"));
  gulp.watch(jsFiles.scripts, gulp.series("scripts"));
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

gulp.task("default", gulp.series("sass", "minify-components-js", "minify-pages-js", "minify-archives-js", "scripts", "serve"));
