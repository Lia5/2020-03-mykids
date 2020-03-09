var syntax        = 'scss'; // Syntax: sass or scss;

var gulp          = require('gulp'),
		gutil         = require('gulp-util' ),
		sass          = require('gulp-sass'),
		browserSync   = require('browser-sync'),
		concat        = require('gulp-concat'),
		uglify        = require('gulp-uglify'),
		cleancss      = require('gulp-clean-css'),
		rename        = require('gulp-rename'),
		autoprefixer  = require('gulp-autoprefixer'),
		notify        = require("gulp-notify"),
		jade		  = require('gulp-jade');
		// rsync         = require('gulp-rsync');

gulp.task('browser-sync', function() {
	browserSync({
		server: {
			baseDir: 'myapp'
		},
		notify: false,
		// open: false,
		// online: false, // Work Offline Without Internet Connection
		// tunnel: true, tunnel: "projectname", // Demonstration page: http://projectname.localtunnel.me
	})
});

gulp.task('styles', function() {
	return gulp.src('myapp/'+syntax+'/**/*.'+syntax+'')
	.pipe(sass({ outputStyle: 'expanded' }).on("error", notify.onError()))
	.pipe(rename({ suffix: '.min', prefix : '' }))
	.pipe(autoprefixer(['last 15 versions']))
	// .pipe(cleancss( {level: { 1: { specialComments: 0 } } })) // Opt., comment out when debugging
	.pipe(gulp.dest('myapp/css'))
	.pipe(browserSync.stream())
});

gulp.task('js', function() {
	return gulp.src([
		// 'myapp/libs/1.jquery/dist/jquery.min.js',
		'myapp/libs/3.mask/jquery.maskedinput.min.js',
		'myapp/libs/owlcarousel/dist/owlcarousel.js',
		// 'myapp/js/common.js', // Always at the end
		])
	.pipe(concat('scripts.min.js'))
	.pipe(uglify()) // Mifify js (opt.)
	.pipe(gulp.dest('myapp/js'))
	.pipe(browserSync.reload({ stream: true }))
});

gulp.task('jade', function() {
	gulp.src('myapp/jade/pages/**/*.jade')
		.pipe(jade({
			pretty: '  ',
		}))
		.pipe(gulp.dest('myapp/'));
	gulp.src('myapp/jade/**/*.jade')
		.pipe(browserSync.stream())
});

// gulp.task('rsync', function() {
// 	return gulp.src('myapp/**')
// 	.pipe(rsync({
// 		root: 'myapp/',
// 		hostname: 'username@yousite.com',
// 		destination: 'yousite/public_html/',
// 		// include: ['*.htaccess'], // Includes files to deploy
// 		exclude: ['**/Thumbs.db', '**/*.DS_Store'], // Excludes files from deploy
// 		recursive: true,
// 		archive: true,
// 		silent: false,
// 		compress: true
// 	}))
// });

gulp.task('watch', ['styles', 'js', 'jade', 'browser-sync'], function() {
	gulp.watch('myapp/'+syntax+'/**/*.'+syntax+'', ['styles']);
	gulp.watch('myapp/'+syntax+'/**/*.+(scss|sass)', ['styles']);
	gulp.watch(['libs/**/*.js', 'myapp/js/common.js'], ['js']);
	gulp.watch('myapp/jade/**/*.jade', ['jade']);

	gulp.watch('myapp/*.html', browserSync.reload)
});

gulp.task('default', ['watch']);
