const elixir = require('laravel-elixir');
//require('laravel-elixir-vue-2');
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
elixir(function (mix) {
    var bootstrapPath = './node_modules/bootstrap-sass/assets';
    // var jqueryPath = './node_modules/jquery/dist/jquery.js';
    mix.sass('app.scss', 'public/assets/css')
        .webpack([
                bootstrapPath + '/javascripts/bootstrap.min.js'
            ],'public/assets/js/');
    mix.copy(bootstrapPath + '/fonts', 'public/assets/fonts');
    //View - Template
    mix.scripts([
        './node_modules/sweetalert2/dist/sweetalert2.min.js',
        './node_modules/jquery/dist/jquery.js',
        'jquery-ui/jquery-ui.js'
     ],'public/assets/js/app.js');
    mix.styles([
        'all.css',
        'jquery-ui/jquery-ui.theme.css',
        'jquery-ui/jquery-ui.css',
        'sbadmin/sb-admin.css',
        'sbadmin/css/font-awesome.css',
        './node_modules/sweetalert2/dist/sweetalert2.min.css',
    ], 'public/assets/css/all.css');
    mix.styles(['404.css'], 'public/assets/css/404.css');
    mix.copy('./resources/assets/css/sbadmin/fonts', 'public/assets/fonts');
    //View - Css
    mix.styles('fileupload/fileinput.min.css', 'public/assets/css/fileupload/fileinput.min.css');
    //View - JavaScript
    mix.scripts('licencas/licencas.js', 'public/assets/js/licencas.js');
    mix.scripts('ativos/ativos.js', 'public/assets/js/ativos.js');
    mix.scripts('fileupload/fileinput.js', 'public/assets/js/fileinput/fileinput.js');
    mix.scripts('fileupload/locales/pt-BR.js', 'public/assets/js/fileinput/locales/pt-BR.js');
    // //Teste VUE
    //
    // mix.scripts('main.js','public/assets/js/main.js');


});


