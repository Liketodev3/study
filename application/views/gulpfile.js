const { src, dest, watch, series, parallel } = require('gulp');
const sass = require('gulp-sass');
      sass.compiler = require('node-sass');
const babel = require('gulp-babel');
const sourcemaps = require('gulp-sourcemaps');

const autoprefixer = require('gulp-autoprefixer');
const svgSprite = require('gulp-svg-sprite');
const concat = require('gulp-concat');

//
// SVG Sprite Config
//
const config = {
  shape: {
    dimension: { // Set maximum dimensions
      maxWidth: 32,
      maxHeight: 32,
      precision: 2,
      attributes: false, 
    },
    // spacing: { // Add padding
    //   padding: 10,
    //   box: 'padding'
    // },
  },
  mode: {
    symbol: {
      dest: './',
      sprite: 'sprite.yo-coach.svg'
    }
  },
  dest: './'
};

//
// Tasks
//
//
function css(){
  return src('./scss/*.scss')
      .pipe(sourcemaps.init({loadMaps: true}))
			.pipe(sass())
      .pipe(autoprefixer())
      .pipe(sourcemaps.write('.'))
			.pipe(dest('./common-css'));
}

/* function js(){
  return src('./js/*.js')
      .pipe(sourcemaps.init({loadMaps: true}))
			.pipe(babel({
        presets: ['@babel/env']
      }))
      .pipe(concat('main.js'))
      .pipe(sourcemaps.write('.'))
			.pipe(dest('./js'));
} */

function svg(){
  return src('./images/sprite/*.svg')
      .pipe(svgSprite(config))
      .pipe(dest('./images'));
}

// Watch files
function watchFiles() {
	// Watch SCSS changes
  watch(['scss'], parallel(css));
}

exports.default = parallel(css, svg);
exports.watch = watchFiles;
