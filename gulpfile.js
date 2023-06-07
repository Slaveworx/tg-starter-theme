//****************************************
// 🆃🅶
// Wᴏʀᴅᴘʀᴇss Sᴛᴀʀᴛᴇʀ Tʜᴇᴍᴇ
// @𝑣𝑒𝑟𝑠𝑖𝑜𝑛 2.0.0
//* This file contains the theme's main automation scripts
//****************************************

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
import concat from "gulp-concat";
import merge from "merge-stream";

dotenv.config();

// Define input and output directories
const dirs = {
  src: {
    loginSass: "./config/sources/assets/scss",
    scss: "./src/scss",
    js: "./src/js",
    components: "./components",
    pages: "./template-pages",
    archives: "./template-archives",
    singles: "./template-singles",
    sections: "./template-parts/sections",
  },
  dest: {
    css: "./static/css",
    js: "./static/js",
    loginSass: "./config/sources/assets/css",
  },
};

// Define JS files to be minified
const jsFiles = {
  components: `${dirs.src.components}/**/*.js`,
  pages: `${dirs.src.pages}/**/*.js`,
  archives: `${dirs.src.archives}/**/*.js`,
  singles: `${dirs.src.singles}/**/*.js`,
  scripts: `${dirs.src.js}/*.js`,
  sections: `${dirs.src.sections}/**/*.js`,
};

// Define functions to handle minification of JS files
function minifyJS(src, dest) {
  return gulp.src(src).pipe(uglify()).pipe(gulp.dest(dest)); // Add this line
}

gulp.task("sass", function () {
  const sass = gulpSass(dartSass);
  return gulp
    .src(`${dirs.src.scss}/main.scss`)
    .pipe(sass({ outputStyle: "compressed" }).on("error", sass.logError))
    .pipe(autoprefixer({ cascade: false }))
    .pipe(gulp.dest(dirs.dest.css))
    .pipe(browserSync.stream());
});

gulp.task("login-sass", function () {
  const sass = gulpSass(dartSass);
  return gulp
    .src(`${dirs.src.loginSass}/login_styles.scss`)
    .pipe(sass({ outputStyle: "compressed" }).on("error", sass.logError))
    .pipe(autoprefixer({ cascade: false }))
    .pipe(gulp.dest(dirs.dest.loginSass))
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

gulp.task("minify-singles-js", function () {
  return minifyJS(jsFiles.singles, `${dirs.dest.js}/template-singles`);
});

gulp.task("scripts", function () {
  // Concatenate section scripts
  const concatSectionsStream = gulp
    .src(jsFiles.sections)
    .pipe(concat("sections.js"));

  // Merge with other scripts
  const allScriptsStream = merge(
    gulp.src(jsFiles.scripts),
    concatSectionsStream
  ).pipe(concat("scripts.js"));

  // Minify and save the final scripts file
  return allScriptsStream
    .pipe(uglify())
    .pipe(gulp.dest(dirs.dest.js))
    .pipe(browserSync.stream());
});

gulp.task("serve", function () {
  browserSync.init({ proxy: process.env.LOCAL_SITE });

  // Watch for changes in files and execute tasks accordingly
  gulp.watch(`${dirs.src.scss}/**/*.scss`, gulp.series("sass"));
  gulp.watch(`${dirs.src.loginSass}/*.scss`, gulp.series("login-sass"));
  gulp.watch(`${dirs.src.components}/**/*.scss`, gulp.series("sass"));
  gulp.watch(`${dirs.src.pages}/**/*.scss`, gulp.series("sass"));
  gulp.watch(`${dirs.src.archives}/**/*.scss`, gulp.series("sass"));
  gulp.watch(`${dirs.src.singles}/**/*.scss`, gulp.series("sass"));
  gulp.watch(`${dirs.src.sections}/**/*.scss`, gulp.series("sass"));
  gulp.watch(jsFiles.components, gulp.series("minify-components-js"));
  gulp.watch(jsFiles.pages, gulp.series("minify-pages-js"));
  gulp.watch(jsFiles.archives, gulp.series("minify-archives-js"));
  gulp.watch(jsFiles.singles, gulp.series("minify-singles-js"));
  gulp.watch(jsFiles.scripts, gulp.series("scripts"));
  gulp.watch(jsFiles.sections, gulp.series("scripts"));
  gulp.watch("**/*").on("change", browserSync.reload);

  //Components Garbage Collector
  chokidar
    .watch("./components/", { ignored: /(^|[\/\\])\../ })
    .on("unlinkDir", function (dirPath) {
      const componentName = dirPath.split("\\").pop();
      console.log("Successfuly Deleted Component: ", componentName);
      deleteSync([
        "static/js/components/" + `${componentName}`,
        "!static/js/components/",
      ]);
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
  //Pages templates garbage collector
  chokidar
    .watch("./template-pages/", { ignored: /(^|[\/\\])\../ })
    .on("unlinkDir", function (dirPath) {
      const templateName = dirPath.split("\\").pop();
      console.log("Successfuly Deleted Page Template: ", templateName);
      deleteSync([
        "static/js/template-pages/" + `${templateName}`,
        "!static/js/template-pages/",
      ]);
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
      deleteSync([
        "static/js/template-archives/" + `${archiveName}`,
        "!static/js/template-archives/",
      ]);
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

  //Singles garbage collector
  chokidar
    .watch("./template-singles/", { ignored: /(^|[\/\\])\../ })
    .on("unlinkDir", function (dirPath) {
      const singleName = dirPath.split("\\").pop();
      console.log("Successfuly Deleted Single Template: ", singleName);
      deleteSync([
        "static/js/template-singles/" + `${singleName}`,
        "!static/js/template-singles/",
      ]);
      gulp
        .src("./src/scss/main.scss")
        .pipe(
          replace(
            //the quotes on the line below must be single quotes, otherwise it will not recognize any text
            `@import './template-singles/${singleName}/single-${singleName}';`,
            ""
          )
        )
        .pipe(gulp.dest("./src/scss/"));
    });
});

//Sections Garbage Collector
chokidar
  .watch("./template-parts/sections/", { ignored: /(^|[\/\\])\../ })
  .on("unlinkDir", function (dirPath) {
    const sectionName = dirPath.split("\\").pop();
    console.log("Successfuly Deleted Section Template: ", sectionName);
    gulp
      .src("./src/scss/main.scss")
      .pipe(
        replace(
          //the quotes on the line below must be single quotes, otherwise it will not recognize any text
          `@import './template-parts/sections/${sectionName}/section-${sectionName}';`,
          ""
        )
      )
      .pipe(gulp.dest("./src/scss/"));
  });

gulp.task(
  "default",
  gulp.series(
    "login-sass",
    "sass",
    "minify-components-js",
    "minify-pages-js",
    "minify-archives-js",
    "minify-singles-js",
    "scripts",
    "serve"
  )
);
