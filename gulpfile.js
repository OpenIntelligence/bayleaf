var gulp           = require('gulp'),
	plumber        = require('gulp-plumber'),
	browserSync    = require('browser-sync').create(),
	sass           = require('gulp-sass'),
	postcss        = require('gulp-postcss'),
	autoprefixer   = require('gulp-autoprefixer'),
	combineMq      = require("css-mqpacker"),
	cssComb        = require('postcss-csscomb'),
	eslint         = require('gulp-eslint'),
	clean          = require('gulp-clean'),
	webpack        = require('webpack'),
	rtlCSS         = require('gulp-rtlcss'),
	rename         = require('gulp-rename'),
	customComb     = {
		"block-indent": "\t",
		"eof-newline": true,
		"leading-zero": true,
		"space-before-opening-brace": " ",
		"space-after-opening-brace": "\n",
		"space-after-selector-delimiter": "\n",
		"space-before-selector-delimiter": "",
		"space-before-closing-brace": "\n",
		"strip-spaces": true,
		"unitless-zero": true,
		"vendor-prefix-align": true
	},
	jsPartials     = './assets/public/js/modules/**/*.js',
	cssPartials    = './assets/public/css/modules/**/*.scss',
	mainSCSS       = './assets/public/css/style.scss';


// Compile Sass files to generate main css file.
gulp.task('compileSass', function() {
	return gulp.src(mainSCSS)
		.pipe(plumber()) // Prevent termination on error
		.pipe(sass({
			indentType: 'tab',
			indentWidth: 1,
			outputStyle: 'expanded', // Expanded so that our CSS is readable
	  	})).on('error', sass.logError)
	  	.pipe(autoprefixer({
			browsers: ['>1%', 'last 2 versions', 'IE 11'],
			cascade: false
	  	}))
	  	.pipe(postcss([
			cssComb('csscomb'),
			cssComb(customComb),
			combineMq({
				sort: true,
			}),
		]))
		.pipe(gulp.dest('./')) // Output compiled files in the same dir as Sass sources
		.pipe(browserSync.stream()) // Stream to browserSync
		.pipe(rtlCSS())
		.pipe(rename({suffix: '-rtl'}))
		.pipe(gulp.dest('./'));
});

// Initialize gulp for plugin development.
gulp.task( 'watch', function( done ) {

	browserSync.init( {
		notify: false,
		proxy: 'http://premiumwp.local/',
		ghostMode: false,
		browser: 'firefox',
		online: false
	} );

	// Watch php files and perform appropriate action.
	gulp.watch( './**/*.php', function( done ) {
		browserSync.reload();
		done();
	} );

	// Watch front-end css partials for changes and preprare main stylesheet.
	gulp.watch(cssPartials, gulp.series('compileSass'));

	// Watch front-end js partials for changes and preprare main script file.
	gulp.watch( jsPartials, gulp.series(
		function( done ) {
			webpack(require('./webpack.config.js'), function() {
				done();
			});
		},
		function( done ) {
			browserSync.reload();
			done();
		}
	) );
	done();
} );

gulp.task('build', gulp.series(
	function() {
		return gulp.src('bayleaf', {read: false, allowEmpty: true})
			.pipe(clean());
	},
	function() {
		return gulp.src([
			'./**',
			'!./bayleaf/**/*',
			'!./node_modules/**/*',
			'!./gulpfile.js',
			'!./package-lock.json',
			'!./package.json',
			'!./webpack.config.js',
			'!./assets/**/*.scss',
			'!./assets/public/css',
		])
			.pipe(gulp.dest('./bayleaf'));
	},
	function() {
		return gulp.src([
			'./bayleaf/node_modules',
			'./bayleaf/assets/public/css',
		])
			.pipe(clean());
	}
));
