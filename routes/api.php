<?php

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\DB;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get('/produtos/{id}', function (Request $request, $id) {
    $Companies = new \App\Empresa();
    $Companies = $Companies->find($id);
    return ($Companies->AllProductOfCompany);

});

Route::get('/licencas/associadas/{patrimonio}/{empresa}', function (Request $request, $patrimonio, $empresa) {
    $bem = DB::connection('sapiens')
        ->table("E670BEM")
        ->where('E670BEM.CODBEM', '=', $patrimonio)
        ->where('E670BEM.CODEMP', '=', $empresa)->get();
    if (!$bem->count())
        return [];
    $BensKeys = \App\BensKeys::select(["BensKeys.id",
        "BensKeys.key_id", "BensKeys.E670BEM_CODBEM",
        "E070EMP_CODEMP","Keys.id as keyid" ,"Keys.key",
        "Produtos.id as Proid","Produtos.model",
        "Empresas.id as Empid","Empresas.name"
    ])->join('Keys', function ($inner) {
        $inner->on('Keys.id', '=', 'key_id');
    })->join('Produtos', function ($inner) {
        $inner->on('Produtos.id', '=', 'Keys.produto_id');
    })->join('Empresas', function ($inner) {
        $inner->on('Empresas.id', '=', 'Produtos.empresa_id');
    })->where('E670BEM_CODBEM', '=', $patrimonio)->where('E070EMP_CODEMP', '=', $empresa)->get();
    return response()->json($BensKeys);
});