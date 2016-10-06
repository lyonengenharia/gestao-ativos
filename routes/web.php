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


Route::group(['middleware' => ['auth', 'acess']], function () {

    Route::get('/home', ['uses' => 'HomeController@index']);
    Route::get('/ativos', ['uses' => 'AtivosController@index'])->middleware('can:ativos');
    Route::get('/ativos/search/', 'AtivosController@search');
    Route::get('/ativos/locations/', 'AtivosController@locations');

    //Licences
    Route::get('/licencas', ['uses' => 'LicencasController@index'])->middleware('can:ativos');
    Route::get('/licencas/licenca', ['uses' => 'LicencasController@licenca'])->middleware('can:ativos');
    Route::post('/licencas/licenca/insert', ['uses' => 'LicencasController@licencainsert'])->middleware('can:ativos');
    Route::get('/licencas/licenca/{id}', ['uses' => 'LicencasController@licencaget'])->middleware('can:ativos');
    Route::post('/licencas/licenca/update/', ['uses' => 'LicencasController@licencaupdate'])->middleware('can:ativos');
    Route::post('/licencas/associar/', ['uses' => 'LicencasController@associar'])->middleware('can:ativos');


    Route::get('/licencas/empresa', ['uses' => 'LicencasController@empresa'])->middleware('can:ativos');
    Route::post('/licencas/empresa/insert', ['uses' => 'LicencasController@empresainsert'])->middleware('can:ativos');

    Route::get('/licencas/produto', ['uses' => 'LicencasController@produto'])->middleware('can:ativos');
    Route::post('/licencas/produto/insert', ['uses' => 'LicencasController@produtoinsert'])->middleware('can:ativos');
    Route::delete('/licencas/produto/delete', ['uses' => 'LicencasController@produtodelete'])->middleware('can:ativos');

    //Painel Controle
    Route::get('/painel','PainelController@index')->middleware('can:ativos');
    Route::get('/painel/usuarios','UserController@index')->middleware('can:ativos');
    Route::get('/painel/usuarios/grupos','RolesController@index')->middleware('can:ativos');

    //Usuários
    Route::get('/usuario/{id}','UserController@edit')->middleware('can:ativos');
    Route::post('/usuario/edit','UserController@update')->middleware('can:ativos');

    //Permissões de Segurança
    Route::get('/permissoes','PermissionsController@index')->middleware('can:ativcos');

});

Route::get('/teste', function () {

    echo "<p>" . auth()->user()->name . "</p>";
    echo "<h1>Permissões</h1>";
    foreach (auth()->user()->roles as $role) {
        echo $role->name . " -> ";
        foreach ($role->permissions as $permission) {
            echo $permission->name . ",";
        }
        echo "<hr>";
    }

});

Route::get('/key/{id}', function ($id, \Illuminate\Http\Request $request) {

    $checkKey = DB::table('Keys')->where('id','=',$id)->get();
    $existekey = DB::table('benskeys')
        ->select(
            [
                'produtos.id', 'produtos.model', 'produtos.description',
                'produtos.empresa_id', 'produtos.created_at', 'produtos.updated_at'
            ]
        )->join('keys', function ($inner) {
            $inner->on('keys.ID', '=', 'benskeys.key_id');
        })->join('produtos', function ($inner) {
            $inner->on('produtos.id', '=', 'keys.produto_id');
        })->where('E670BEM_CODBEM', '=', 'COMP-000779.00')
        ->where('E070EMP_CODEMP', '=', 1);
    $licencasassocias = $existekey->get();
    foreach ($checkKey as $row){


    }
    //dd($checkKey->get());
    //dd();
});
