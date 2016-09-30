<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/


Route::get('/', function () {
    return view('welcome');
});


Route::get('/home', 'HomeController@index');

Route::post('/testeldap', "UserController@index");
Auth::routes();


Route::group(['middleware'=>['auth','acess']],function(){

    Route::get('/home', ['uses'=>'HomeController@index']);
    Route::get('/ativos', ['uses'=>'AtivosController@index'])->middleware('can:ativos');
    Route::get('/ativos/search/', 'AtivosController@search');
    Route::get('/ativos/locations/', 'AtivosController@locations');

    //Licences
    Route::get('/licencas', ['uses'=>'LicencasController@index'])->middleware('can:ativos');
    Route::get('/licencas/licenca', ['uses'=>'LicencasController@licenca'])->middleware('can:ativos');
    Route::post('/licencas/licenca/insert', ['uses'=>'LicencasController@licencainsert'])->middleware('can:ativos');
    Route::get('/licencas/licenca/{id}', ['uses'=>'LicencasController@licencaget'])->middleware('can:ativos');
    Route::post('/licencas/licenca/update/', ['uses'=>'LicencasController@licencaupdate'])->middleware('can:ativos');
    Route::post('/licencas/associar/', ['uses'=>'LicencasController@associar'])->middleware('can:ativos');


    Route::get('/licencas/empresa', ['uses'=>'LicencasController@empresa'])->middleware('can:ativos');
    Route::post('/licencas/empresa/insert', ['uses'=>'LicencasController@empresainsert'])->middleware('can:ativos');

    Route::get('/licencas/produto', ['uses'=>'LicencasController@produto'])->middleware('can:ativos');
    Route::post('/licencas/produto/insert', ['uses'=>'LicencasController@produtoinsert'])->middleware('can:ativos');

});

Route::get('/teste', function () {

    echo "<p>".auth()->user()->name."</p>";
    echo "<h1>Permissões</h1>";
    foreach (auth()->user()->roles as $role){
        echo $role->name." -> ";
        foreach ($role->permissions as $permission){
            echo $permission->name.",";
        }
        echo "<hr>";
    }

});

Route::get('/loguser/{id}',function ($id){

        Log::info('Showing user profile for user: '.$id);

        return Log::getMonolog();

});
