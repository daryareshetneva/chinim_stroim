'use strict';

var gulp = require('gulp'),
    watch = require('gulp-watch'),
    uglify = require('gulp-uglify'),
    prefixer = require('gulp-autoprefixer'),
    cleancss = require('gulp-clean-css'),
    less = require('gulp-less'),
    rigger = require('gulp-rigger');
    //browserSync = require("browser-sync"),
    //reload = browserSync.reload;

var path = {
    build: { //Тут мы укажем куда складывать готовые после сборки файлы
        html: 'design/build/',
        js: 'design/build/js/',
        css: 'design/build/css/',
        img: 'design/build/img/'
    },
    src: { //Пути откуда брать исходники
        html: 'design/src/*.html', //Синтаксис src/*.html говорит gulp что мы хотим взять все файлы с расширением .html
        js: 'design/src/js/main.js',//В стилях и скриптах нам понадобятся только main файлы
        css: 'design/src/css/main.less',
        img: 'design/src/img/**/*.*' //Синтаксис img/**/*.* означает - взять все файлы всех расширений из папки и из вложенных каталогов
    },
    watch: { //Тут мы укажем, за изменением каких файлов мы хотим наблюдать
        html: 'design/src/**/*.html',
        js: 'design/src/js/**/*.js',
        css: 'design/src/css/**/*.less',
        img: 'design/src/img/**/*.*'
    },
    clean: './design/build/**/*.*'
};

// переменная с конфигурацией сервера
var config = {
    server: {
        baseDir: "./" // возможно здесь нужно заменить путь
    },
    tunnel: true,
    host: 'localhost',
    port: 9000,
    logPrefix: "darya"
};

// task для сборки html
gulp.task('html:build', function (done) {
    gulp.src(path.src.html) //Выберем файлы по нужному пути
        .pipe(rigger()) //Прогоним через rigger
        .pipe(gulp.dest(path.build.html)); //Выплюнем их в папку build
        //.pipe(reload({stream: true})); //И перезагрузим наш сервер для обновлений
    done();

});

// task для сборки js
gulp.task('js:build', function (done) {
    gulp.src(path.src.js) //Найдем наш main файл
        .pipe(rigger()) //Прогоним через rigger
        .pipe(uglify()) //Сожмем наш js
        .pipe(gulp.dest(path.build.js)); //Выплюнем готовый файл в build
       // .pipe(reload({stream: true})); //И перезагрузим сервер
    done();
});

// task для перевода less файлов в css
gulp.task('less:build', function (done) {
    gulp.src(path.src.css)
        .pipe(less())
        .pipe(prefixer())
        .pipe(cleancss())
        .pipe(gulp.dest(path.build.css)); //Выплюнем готовый файл в build
      //  .pipe(reload({stream: true})); //И перезагрузим сервер
    done();
});

// task для сборки картинок
gulp.task('image:build', function (done) {
    gulp.src(path.src.img) //Выберем наши картинки
        // пока не добавлены пакеты для картинок
        .pipe(gulp.dest(path.build.img)); //И бросим в build
       // .pipe(reload({stream: true}));
    done();
});

// task для запуска нужной задачи при изменении какого-то файла
gulp.task('watch', function(done){
    gulp.watch(path.watch.html, gulp.series('html:build'));
    gulp.watch(path.watch.css, gulp.series('less:build'));
    gulp.watch(path.watch.js, gulp.series('js:build'));
    gulp.watch(path.watch.img, gulp.series('image:build'));
    done();
});

// тестила, выводит ошибку в браузере
//task для работы веб-сервера, обновляющегося онлайн
/*gulp.task('webserver', function (done) {
    browserSync(config);
    done();
});*/

// rimraf отсутствует
// task для очистки папки build
gulp.task('clean', function (cb) {
    rimraf(path.clean, cb);
});

// task по умолчанию, запускающий весь процесс сборки
// в новом gulp так писать нельзя
// поэтому закомментим пока
gulp.task('default', gulp.series('watch'));

/*function defaultTask(cb) {
    // place code for your default task here
    cb();
}

exports.default = defaultTask;*/