'use strict';

import browserSync from 'browser-sync';
import gulp from 'gulp';
import gulpLoadPlugins from 'gulp-load-plugins';
import svgo from 'imagemin-svgo';
import routes from './config/routes';
import fn from './config/functions';

const reload = browserSync.reload;
const $ = gulpLoadPlugins({ camelize: true });
const SVGO_ARGS = {
    plugins: [
        { cleanupIDs: true },
        { collapseGroups: true },
        { convertColors: true },
        { convertShapeToPath: true },
        { removeComments: true },
        { removeDescription: true },
        { removeDoctype: true },
        { removeEmptyAttrs: true },
        { removeMetadata: true },
        { removeStyleElement: true },
        { removeTitle: true },
        { removeUselessStrokeAndFill: true },
        { removeViewBox: false }
    ]
};
const IMAGES_FILES = [
    routes.src.img + '/**/*',
    '!' + routes.src.sprites + '/*'
];
const SPRITES_FILES = [routes.src.sprites + '/*.svg'];

gulp.task('images', ['images:compress', 'images:sprites']);

gulp.task('images:compress', () => {
    fn.consoleLog('Start: Compressing Images', 'start');
    return gulp.src(IMAGES_FILES)
        .pipe($.cache(
            $.imagemin({
                optimizationLevel: 5,
                progressive: true,
                interlaced: true
            })
        ))
        .pipe(svgo(SVGO_ARGS)())
        .pipe(gulp.dest(routes.dist.img));
});

gulp.task('images:sprites', () => {
    fn.consoleLog('Start: Spriting', 'start');
    return gulp.src(SPRITES_FILES)
        .pipe($.rename({ prefix: 'icon-' }))
        .pipe(svgo(SVGO_ARGS)())
        .pipe($.cheerio({
            run: ($) => {
                $('[fill]').removeAttr('fill');
            },
            parserOptions: { xmlMode: true }
        }))
        .pipe($.svgstore())
        .pipe(gulp.dest(routes.dist.sprites));
});

gulp.task('images:watch', () => {
    gulp.watch([IMAGES_FILES], ['images:compress', reload]);
    gulp.watch([SPRITES_FILES], ['images:sprites', reload]);
});