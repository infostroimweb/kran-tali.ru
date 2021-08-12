const gulp = require('gulp');
const autoprefixer = require('gulp-autoprefixer');
const cssmin = require('gulp-cssmin');
const minify = require('gulp-minify');
const rename = require('gulp-rename');
const cssUseref = require('gulp-css-useref');
const del = require('del');
const sequence = require('gulp-sequence');
const debug = require('gulp-debug');
const sass = require('gulp-sass');
const jsonTransform = require('gulp-json-transform');
const concat = require('gulp-concat');

function filePathTransformCorrection(file, basepath)
{
    var file = String(file);
    var correctedFile = file.replace('..\\','');
    var filePathCorrected =  basepath + '/' + correctedFile;

    return filePathCorrected;
}

const files = {
    // internal libraries
    guideplugin: {
        name: 'guideplugin',
        js: ['assets/guideplugin/js/guideplugin.js'],
        css: ['assets/guideplugin/css/guideplugin.css']
    },
    guidefont: {
        name: 'guidefont',
        css: ['node_modules/guidefont/style.css']
    },
    font_awesome: {
        name: 'font-awesome',
        css: ['node_modules/@fortawesome/fontawesome-free/css/all.css'],
        /*js: ['node_modules/@fortawesome/fontawesome-pro/js/all.js']*/
    },
    ion_slider: {
        name: 'ion-slider',
        css: ['node_modules/ion-rangeslider/css/ion.rangeSlider.css'],
        js: ['node_modules/ion-rangeslider/js/ion.rangeSlider.js'],
    },
    slick_carousel: {
        name: 'slick-carousel',
        css: ['node_modules/slick-carousel/slick/slick.css'],
        js: ['node_modules/slick-carousel/slick/slick.js'],
    },
    canvas_confetti: {
        name: 'canvas-confetti',
        js: ['node_modules/canvas-confetti/dist/confetti.browser.js'],
    },
    guideplugin_modal: {
        name: 'guideplugin-modal',
        css: ['assets/guideplugin-modal/css/guideplugin-modal.css'],
        js: ['assets/guideplugin-modal/js/guideplugin-modal.js'],
    }, 
    popper: {
        name: 'popper',
        js: ['node_modules/@popperjs/core/dist/umd/popper.js'],
    }, 
    tippy: {
        name: 'tippy',
        css: ['node_modules/tippy.js/dist/tippy.css', 'node_modules/tippy.js/animations/scale.css'],
        js: ['node_modules/tippy.js/dist/tippy.umd.js'],
    }, 
}

const acfFiles = {
    guide: 'acf-json/group_5dfb8ace86651.json',
    facet: 'acf-json/group_5dbc5da7e067b.json',
    filter: 'acf-json/group_5dc9466f08606.json',
    settings: 'acf-json/group_5e2193ef05ca5.json',
    logic_rules: 'acf-json/group_5e29f139d8cbc.json',
    design: 'acf-json/group_5e88b630a8f12.json',
    design_post: 'acf-json/group_5e8b2e13af264.json',
    template: 'acf-json/group_5e9862d14f82b.json',
    gutenberg_block: 'acf-json/group_5e996f1908565.json',
    template_fields: 'acf-json/group_5e99950d5b69a.json'
}



// Task for cleaning the dist
gulp.task('clean', function() {
    return del([ 'dist', 'acf-php' ]);
});


// Task for sass convert
gulp.task('sass', function () {
    Object.keys(files).forEach(function(key) {
        if (files[key]['sass'] != undefined) {
            gulp.src(files[key]['sass'])
                .pipe(sass().on('error', sass.logError))
                .pipe(debug({title: 'minify sass:'}))
                .pipe(cssmin())
                .pipe(rename({
                    suffix: '.min',
                    basename: files[key]['name']
                }))
                .pipe(cssUseref({
                    pathTransform: function(newAssetFile, cssFilePathRel, urlMatch, options)
                    {
                        // console.log(newAssetFile);
                        return filePathTransformCorrection(newAssetFile,basepath);

                        // instead of
                        // return newAssetFile;
                    },
                }))
                .pipe(gulp.dest('./dist/css'))
        }
    });
});


// Task for css minification
gulp.task('css-minify', function(cb) {
    Object.keys(files).forEach(function(key) {
        if (files[key]['css'] != undefined) {
            var basepath = String('../assets/' + files[key]['name']);
            console.log(basepath);
            gulp.src(files[key]['css'])
                .pipe(debug({title: 'minify:'}))
                .pipe(autoprefixer({
                    cascade: false
                }))
                .pipe(concat('all.css'))
                .pipe(cssmin())
                .pipe(rename({
                    suffix: '.min',
                    basename: files[key]['name']
                }))
                .pipe(cssUseref({
                    pathTransform: function(newAssetFile, cssFilePathRel, urlMatch, options)
                    {
                        // console.log(newAssetFile);
                        return filePathTransformCorrection(newAssetFile,basepath);

                        // instead of
                        // return newAssetFile;
                    },
                }))
                .pipe(gulp.dest('./dist/css'))
        }
    });
});


// Task for js minification
gulp.task('js-minify', function(cb) {
    Object.keys(files).forEach(function(key) {
        if (files[key]['js'] != undefined) {
            gulp.src(files[key]['js'])
                .pipe(minify({
                    noSource: true,
                }))
                .pipe(concat('all.js'))
                .pipe(rename({
                    suffix: '.min',
                    basename: files[key]['name']
                }))
                .pipe(gulp.dest('./dist/js'))
        }
    });
});


// Task for json movement
gulp.task('copy-json', function(cb) {
    Object.keys(files).forEach(function(key) {
        if (files[key]['json'] != undefined) {
            gulp.src(files[key]['json'])
                .pipe(gulp.dest('./dist/assets/' + files[key]['name'] + '/json'))
        }
    });
});


// Process acf fieldgroups
gulp.task('acf-fieldgroups', function(cb) {
    Object.keys(acfFiles).forEach(function(key) {
        if (acfFiles[key] != undefined) {
            gulp.src(acfFiles[key])
            .pipe(jsonTransform(function(data, file) {
                let content = '<?php return array(';
                content += acfFieldgroupArray(data);
                content += ');?>'
                return content;
            }))
            .pipe(rename({
                basename: key,
                extname: '.php'
            }))
            .pipe(gulp.dest('./dist/acf-php/'));
        }
    });
});


let acfFieldgroupArray = function(jsonObject) {
    let output = '';
    for(let [key, value] of Object.entries(jsonObject)){
        if (typeof value == 'object' && isNaN(key)) {
            output += "'" + key + "' => array(" + acfFieldgroupArray(value) + "),";
        } else if (typeof value == 'object' && !isNaN(key)) {
            output += "array(" + acfFieldgroupArray(value) + "),";
        } else {
            if (key == 'label' || (key == 'button_label' && value != '') || (key == 'instructions' && value != '')) {
                output += "'" + key + "' => __('" + escapeHtml(value) + "', 'guideplugin'),\n";
            } else {
                output += "'" + key + "' => '" + escapeHtml(value) + "',\n";
            }
        }//end if
    }//end for
    return output;
}

let escapeHtml = function(unsafe) {
    return String(unsafe)
        /*.replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")*/
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}


// Gulp default task
gulp.task('default', sequence('clean', ['sass', 'css-minify', 'js-minify', 'copy-json', 'acf-fieldgroups']));


