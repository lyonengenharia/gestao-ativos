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
    Route::get('/ativos', ['uses'=>'AtivosController@index']);
    Route::get('/ativos/search/', 'AtivosController@search');
    Route::get('/ativos/locations/', 'AtivosController@locations');

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
