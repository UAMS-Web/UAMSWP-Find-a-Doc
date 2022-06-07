const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const rename = require('gulp-rename');
const concat = require('gulp-concat');
const notify = require('gulp-notify');
const cssnano = require('cssnano');
const changed = require('gulp-changed');
const comments = require('postcss-discard-comments');
const groupmq = require('gulp-group-css-media-queries');

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
        .pipe(sass().on('error', sass.logError))
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