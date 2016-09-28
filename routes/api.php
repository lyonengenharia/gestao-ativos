<?php

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Auth;

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

Route::get('/user', function (Request $request) {
    var_dump($request->user());
});

Route::get('/produtos/{id}', function (Request $request, $id) {
    $Companies = new \App\Empresa();
    $Companies = $Companies->find($id);
    return ($Companies->AllProductOfCompany);

});
