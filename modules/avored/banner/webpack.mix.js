let mix = require('laravel-mix')

mix.setPublicPath('dist')
    .js('resources/js/banner.js', 'js/banner.js')
    //.sass('resources/sass/field.scss', 'css')
