const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js').vue()    
mix.js('resources/js/print.min.js', 'public/js')

mix.styles(
    ['resources/css/app.css',
    'resources/css/responsive.css',
    'resources/css/main.css',
    'resources/css/common.css',
    'resources/css/print.min.css',
    'resources/css/responsive.css']
, 'public/css/all.css'); 
