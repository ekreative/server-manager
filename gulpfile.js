var gulp = require('gulp'),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglify'),
    less = require('gulp-less-sourcemap'),
    del = require('del'),
    sourcemaps = require('gulp-sourcemaps'),
    uglifycss = require('gulp-uglifycss'),
    imagemin = require('gulp-imagemin');

gulp.task('default', ['build']);

gulp.task('build', ['fonts', 'styles', 'scripts:bundle', 'scripts:pages', 'images']);

gulp.task('clean', function (cb) {
    del(['web/css/*', 'web/js/*', 'web/fonts/*'], cb);
});

gulp.task('styles', function() {
    return gulp.src(['web-src/less/*'])
        .pipe(less())
        .pipe(uglifycss())
        .pipe(gulp.dest('web/css'));
});

gulp.task('scripts:bundle', function() {
    return gulp.src([
        'node_modules/jquery/dist/jquery.js',
        'node_modules/bootstrap/dist/js/bootstrap.js'
    ])
        .pipe(sourcemaps.init({loadMaps: true}))
        .pipe(concat('bundle.js'))
        .pipe(uglify())
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest('web/js'));
});

gulp.task('scripts:pages', function() {
    return gulp.src(['web-src/js/**/*.js'])
        .pipe(sourcemaps.init({loadMaps: true}))
        .pipe(uglify())
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest('web/js'));
});

gulp.task('fonts', function () {
    return gulp.src(['node_modules/bootstrap/dist/fonts/*'])
        .pipe(gulp.dest('web/fonts'))
});

gulp.task('images', function () {
    return gulp.src('web-src/images/**/*')
        .pipe(imagemin({
            progressive: true,
            interlaced: true
        }))
        .pipe(gulp.dest('web/images'));
});

gulp.task('watch', function () {
    gulp.watch('web-src/less/*.less', ['styles']);
    gulp.watch('web-src/js/**/*.js', ['scripts:pages']);
    gulp.watch('web-src/images/**/*', ['images']);
});
