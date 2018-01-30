let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('themes/mage2/default/assets/js/app.js', 'public/vendor/mage2-default/js')
   .sass('themes/mage2/default/assets/sass/app.scss', 'public/vendor/mage2-default/css');



mix.scripts(['modules/mage2/ecommerce/resources/assets/js/jquery-3.2.1.min.js',
        'modules/mage2/ecommerce/resources/assets/js/popper.min.js',
        'modules/mage2/ecommerce/resources/assets/js/bootstrap.min.js',
        'modules/mage2/ecommerce/resources/assets/js/fontawesome-all.min.js',
        'modules/mage2/ecommerce/resources/assets/js/Chart.min.js',
        'modules/mage2/ecommerce/resources/assets/js/select2.min.js',
        'modules/mage2/ecommerce/resources/assets/js/flatpickr.js'
        ]
            , 'public/vendor/mage2-admin/js/app.js');


    mix.sass('modules/mage2/ecommerce/resources/assets/sass/app.scss', 'public/vendor/mage2-admin/css/sass.css')
    .styles(['public/vendor/mage2-admin/css/bootstrap.min.css',
            'public/vendor/mage2-admin/css/select2.min.css',
            'public/vendor/mage2-admin/css/flatpickr.min.css',
            'public/vendor/mage2-admin/css/styles.css',
            'public/vendor/mage2-admin/css/sass.css'
            ]
            ,'public/vendor/mage2-admin/css/app.css'
        );