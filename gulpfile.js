var gulp = require('gulp');
    sass = require('gulp-sass');
    postcss = require('gulp-postcss');
    autoprefixer = require('autoprefixer'),
    rename = require('gulp-rename'),
    concat = require('gulp-concat'),
    notify = require('gulp-notify'),
    cssnano = require('cssnano'),
    changed = require('gulp-changed'),
    comments = require('postcss-discard-comments'),
    groupmq = require('gulp-group-css-media-queries');

var plugins = [
    autoprefixer,
    cssnano,
    // cmq,
    comments({
        removeAllButFirst: true
    })
]

var paths = {
    styles: {
        src: 'assets/scss/app.scss',
        dest: 'assets/css'
    }
}

function style() {
    return gulp.src(paths.styles.src)
        .pipe(changed(paths.styles.dest))
        .pipe(sass.sync().on('error', sass.logError))
        .pipe(concat('app.scss'))
        .pipe(postcss(plugins))
        .pipe(groupmq()) // Group media queries!
        .pipe(rename('app.css'))
        .pipe(gulp.dest(paths.styles.dest))
        .pipe(notify({ message: 'Styles task complete' }));
}
    
    /**
 * Compile Sass files
 */
// gulp.task('compile:sass', ['lint:sass'], () =>
//   gulp.src(SASS_SOURCES, { base: './' })
//     .pipe(plumber()) // Prevent termination on error
//     .pipe(sass({
//       indentType: 'tab',
//       indentWidth: 1,
//       outputStyle: 'expanded', // Expanded so that our CSS is readable
//     })).on('error', sass.logError)
//     .pipe(postcss([
//       autoprefixer({
//         browsers: ['last 2 versions'],
//         cascade: false,
//       })
//     ]))
//     .pipe(groupmq()) // Group media queries!
//     .pipe(gulp.dest('.')) // Output compiled files in the same dir as Sass sources
//     .pipe(bs.stream())); // Stream to browserSync

/**
 * Default task executed by running `gulp`
 */
// gulp.task('default', ['watch:sass']);
gulp.task('default', gulp.parallel(style));