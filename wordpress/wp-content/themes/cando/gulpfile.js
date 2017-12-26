var gulp = require('gulp');
var compass = require('gulp-compass');
var sourcemaps = require('gulp-sourcemaps');
var browserSync = require('browser-sync').create();
var plumber = require('gulp-plumber');
var newer = require('gulp-newer');
var rename = require('gulp-rename');
var concat = require('gulp-concat');
var uglifycss = require('gulp-uglifycss');
var uglify = require('gulp-uglify');
var gulpIf = require('gulp-if');
var imagemin = require('gulp-imagemin');
var bower = require('gulp-bower');
var notify = require('gulp-notify');
var autoprefixer = require('gulp-autoprefixer');
var cache = require('gulp-cache');
var del = require('del');
var runSequence = require('run-sequence');

var bowerPath = 'assets/bower_components/';
var nodeModulesPath = 'node_modules/';
var componentsPath = 'assets/components/';

/* Compile Sass
=============== */

gulp.task('compass', function () {

    return gulp.src('assets/scss/**/*.scss')
        .pipe(plumber())
        .pipe(sourcemaps.init())
        .pipe(compass({
            css: 'assets/css',
            sass: 'assets/scss',
            image: 'assets/images'
        }))
        .pipe(sourcemaps.write({
            includeContent: false
        }))
        .pipe(sourcemaps.init({
            loadMaps: true
        }))
        .pipe(autoprefixer({
            browsers: ['last 2 versions'],
            cascade: false
        }))
        .pipe(sourcemaps.write('.'))
        .pipe(plumber.stop())
        .pipe(gulp.dest('assets/css'))
        .pipe(browserSync.reload({
            stream: true
        }))
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(uglifycss({
            maxLineLen: 80
        }))
        .pipe(gulp.dest('assets/css/min'))
        .pipe(browserSync.reload({
            stream: true
        }))
        .pipe(notify({
            message: 'Styles task complete',
            onLast: true
        }));

});

/* Vendor Scripts
================= */

gulp.task('vendorscss', function () {
    return gulp.src([nodeModulesPath + 'bootstrap/dist/css/bootstrap.css', 'assets/css/vendor/*.css'])
        .pipe(concat('lib.css'))
        .pipe(gulp.dest('./assets/css'))
        .pipe(rename({
            basename: "lib",
            suffix: '.min'
        }))
        .pipe(uglifycss())
        .pipe(gulp.dest('./assets/css/min'))
        .pipe(notify({
            message: 'Vendor styles task complete',
            onLast: true
        }));
});


/* Vendor Scripts
================= */

gulp.task('vendorsjs', function () {
    return gulp.src([componentsPath + 'fontawesome-pro-5.0.1/svg-with-js/js/fontawesome-all.js', 'assets/js/vendor/*.js'])
        .pipe(concat('vendors.js'))
        .pipe(gulp.dest('./assets/js'))
        .pipe(rename({
            basename: "vendors",
            suffix: '.min'
        }))
        .pipe(uglify())
        .pipe(gulp.dest('./assets/js/min'))
        .pipe(notify({
            message: 'Vendor scripts task complete',
            onLast: true
        }));
});


/* Custom Scripts
================= */

gulp.task('scriptsjs', function () {
    return gulp.src('assets/js/custom/*.js')
        .pipe(concat('custom.js'))
        .pipe(gulp.dest('./assets/js'))
        .pipe(rename({
            basename: "custom",
            suffix: '.min'
        }))
        .pipe(uglify())
        .pipe(gulp.dest('./assets/js/min'))
        .pipe(notify({
            message: 'Custom scripts task complete',
            onLast: true
        }));
});


/* Run Watch + Browser Sync
=========================== */

gulp.task('browser-sync', function () {

    browserSync.init({
        proxy: 'cando.prentice'
    });

});

gulp.task('watch', ['browser-sync'], function () {

    gulp.watch('assets/scss/**/*.scss', ['compass']);
    gulp.watch('assets/js/**/*.js', ['scriptsjs']);
    // Reloads the browser whenever PHP or JS files change
    gulp.watch('*.php', browserSync.reload);
    gulp.watch('assets/js/**/*.js', browserSync.reload);

});


/* Move Fonts
============= */

gulp.task('fontawesome', function () {
    return gulp.src(bowerPath + 'font-awesome/fonts/**/*')
        .pipe(gulp.dest('assets/css/fonts'));
})

/* Minify Images
================ */


gulp.task('images', function () {
    return gulp.src('assets/images/main/**/*.+(png|jpg|gif|svg)')
        .pipe(imagemin())
        .pipe(gulp.dest('assets/images/min'));
});



/* Clean Dist
============= */

gulp.task('clean:dist', function () {
    return del.sync(['assets/css/styles.css', 'assets/css/styles.css.map', 'assets/js/custom.js']);
});


/* Clear Cache
============== */

gulp.task('cache:clear', function (callback) {
    return cache.clearAll(callback);
});


/* Run Build Sequence
===================== */

gulp.task('build', function (callback) {
    runSequence('clean:dist', ['compass', 'vendorscss', 'vendorsjs', 'scriptsjs', 'images', 'fontawesome'], callback);
});


/* Default Task
=============== */

gulp.task('default', function (callback) {
    runSequence(['compass', 'browser-sync', 'watch', 'scriptsjs'], callback);
});
