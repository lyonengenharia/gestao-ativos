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
    mix.scripts(['./node_modules/angular/angular.min.js','./node_modules/angular-messages/angular-messages.min.js'], 'public/assets/js/angular.min.js');
    mix.scripts('painel/termos.js', 'public/assets/js/termos.js');
    // Type Script
    //mix.typescript('','public/assets/js/teste.js');

    // mix.sass('app.scss');
    //
    // mix.typescript('/app.component.ts','public/componente.js');
    // mix.typescript('app.js','public/','/**/*.ts',{
    //     "target": "ES5",
    //     "module": "system",
    //     "moduleResolution": "node",
    //     "sourceMap": true,
    //     "emitDecoratorMetadata": true,
    //     "experimentalDecorators": true,
    //     "removeComments": false,
    //     "noImplicitAny": false,
    // });
    //
    // mix.copy('node_modules/angular2', 'public/angular2');
    // mix.copy('node_modules/rxjs', 'public/rxjs');
    // mix.copy('node_modules/systemjs', 'public/systemjs');
    // mix.copy('node_modules/es6-promise', 'public/es6-promise');
    // mix.copy('node_modules/es6-shim', 'public/es6-shim');
    // mix.copy('node_modules/zone.js', 'public/zone.js');


});


