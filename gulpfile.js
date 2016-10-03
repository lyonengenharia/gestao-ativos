const elixir = require('laravel-elixir');

require('laravel-elixir-vue');
require("jquery");


/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */
elixir(function(mix){
    var bootstrapPath = 'node_modules/bootstrap-sass/assets';
    var jqueryPath = './node_modules/jquery/dist/jquery.js';
    mix.sass('app.scss')
     .copy(bootstrapPath + '/fonts', 'public/fonts')
     .copy(bootstrapPath + '/javascripts/bootstrap.min.js', 'public/js');
    //mix.scripts([jqueryPath+"/"]);
    mix.scripts(jqueryPath,'public/js/all.js');
    mix.styles(['all.css'],'public/css/all.css');
});

