/* eslint-disable */

const gulp = require('gulp');
const exec = require('child_process').exec;
const postcss = require('gulp-postcss');
const webpack = require('webpack');

const webpackDevConfig = require('./webpack.dev.js');
const webpackProdConfig = require('./webpack.prod.js');

const cssPaths = [
  './css/styles.css'
];

const jsAmdPaths = [
  './amd/src/*.js',
];

const jsUiPaths = [
  './ui/src/**/*.ts',
  './ui/src/**/*.tsx',
  './ui/src/**/*.js'
];

/** CSS. */

var cssBuild = gulp.series(tailwindBuild);

function tailwindBuild(cb) {
  // Build Tailwind. This behaves differently depending on NODE_ENV.
  return gulp
    .src(cssPaths)
    .pipe(postcss())
    .pipe(gulp.dest('.'));
}

/** Moodle. */

function moodleAmd(cb) {
  exec('grunt amd', function (err, stdout, stderr) {
    cb(err);
  });
}

/** Webpack. */

function webpackBuildFromConfig(config) {
  return new Promise((resolve, reject) => {
    webpack(config, (err, stats) => {
      if (err) {
        return reject(err);
      } else if (stats.hasErrors()) {
        return reject(new Error(stats.compilation.errors.map((e) => e.message).join('\n')));
      }
      resolve();
    });
  });
}

function webpackDev(cb) {
  return webpackBuildFromConfig(webpackDevConfig);
}

function webpackProd(cb) {
  return webpackBuildFromConfig(webpackProdConfig);
}

/** Watch. */

function watchAmd(cb) {
  return gulp.watch(jsAmdPaths, gulp.series(moodleAmd));
}

function watchCss(cb) {
  return gulp.watch([].concat(cssPaths, jsUiPaths), cssBuild);
}

const watchJs = gulp.parallel(watchUi, watchAmd);

function watchUi(cb) {
  return gulp.watch(jsUiPaths, gulp.series(webpackDev, moodleAmd));
}

exports['dist'] = gulp.series(cssBuild, webpackProd, moodleAmd);
exports['dist:dev'] = gulp.series(cssBuild, webpackDev, moodleAmd);
exports['watch'] = gulp.parallel(watchJs, watchCss);
exports['watch:css'] = watchCss;
exports['watch:js'] = watchJs;
exports['webpack:dev'] = webpackDev;
exports['webpack:prod'] = webpackProd;
