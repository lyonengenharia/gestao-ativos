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
    var jqueryui = './node_modules/jquery-ui/';
    mix.sass('app.scss')
     .copy(bootstrapPath + '/fonts', 'public/fonts')
     .copy(bootstrapPath + '/javascripts/bootstrap.min.js', 'public/js');
    //mix.scripts([jqueryPath+"/"]);

    //View - Template

    mix.scripts(jqueryPath,'public/js/all.js');

    //Jquery-ui
    mix.scripts('jquery-ui/jquery-ui.js','public/js/jquery-ui');


    mix.styles(['all.css','jquery-ui/jquery-ui.theme.css','jquery-ui/jquery-ui.css'],'public/css/all.css');
    //View - Css

    mix.styles('fileupload/fileinput.min.css','public/css/fileupload/fileinput.min.css');


    //View - JavaScript
    mix.scripts('licencas/licencas.js','public/js/licencas.js');
    mix.scripts('ativos/ativos.js','public/js/ativos.js');
    mix.scripts('fileupload/fileinput.js','public/js/fileinput/fileinput.js');
    mix.scripts('fileupload/locales/pt-BR.js','public/js/fileinput/locales/pt-BR.js');


});


