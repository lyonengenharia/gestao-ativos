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
    $BensKeys = \App\BensKeys::select(["benskeys.id",
        "key_id", "benskeys.E670BEM_CODBEM",
        "E070EMP_CODEMP","keys.id as keyid" ,"keys.key",
        "produtos.id as Proid","produtos.model",
        "empresas.id as Empid","empresas.name"
    ])->join('keys', function ($inner) {
        $inner->on('keys.id', '=', 'benskeys.key_id');
    })->join('produtos', function ($inner) {
        $inner->on('produtos.id', '=', 'keys.produto_id');
    })->join('empresas', function ($inner) {
        $inner->on('empresas.id', '=', 'produtos.empresa_id');
    })->where('E670BEM_CODBEM', '=', $patrimonio)->where('E070EMP_CODEMP', '=', $empresa)->get();
    return response()->json($BensKeys);
});
/**
 * Retonar todos os bens associados para id da licença
 */
Route::get('licencas/associadas/{key}',function (Request $request,$key){
    $itens = DB::table('benskeys')
             ->select(['keys.key','keys.quantity','keys.in_use','produtos.model','empresas.name','benskeys.E670BEM_CODBEM'])
             ->join('keys',function ($inner){
                 $inner->on('keys.ID','=','benskeys.key_id');
             })->join('produtos',function ($inner){
                 $inner->on('produtos.id','=','keys.produto_id');
             })->join('empresas',function ($inner){
                 $inner->on('empresas.id','=','produtos.empresa_id');
             })->where('benskeys.key_id','=',$key)->get();
    return $itens;
});


Route::get('colaboradores/{name}/{tipo}/{emp}',function ($name,$tipo,$emp){
    $colaboradores = DB::connection('vetorh')->table('R034FUN')
        ->select(['NUMEMP','TIPCOL','NUMCAD as id','NOMFUN as value','DESSIT','SITAFA'])
        ->join('R010SIT',function ($inner){
            $inner->on('R010SIT.CODSIT','=','R034FUN.SITAFA');
        })
        ->where('NUMEMP','=',$emp)
        ->where('TIPCOL','=',$tipo)
        ->where('NOMFUN','like',"$name%")
        ->limit(10)->get();
    foreach ($colaboradores as $key => $value){
        $colaboradores[$key]->value = iconv('windows-1252','utf-8',$colaboradores[$key]->value);
    }
    return ($colaboradores);
});
Route::get('devolucao/{codbem}/{codbememp}',function ($codbem,$codbememp){
    $VerificaEmprestimo = \App\Emprestimo::where('E670BEM_CODBEM','=',$codbem)
        ->where('E070EMP_CODEMP','=',$codbememp)
        ->where('data_entrada','=',null);
    if($VerificaEmprestimo->count()){
        $VerificaEmprestimo = $VerificaEmprestimo->get();
        $VerificaEmprestimo[0]->data_out= $VerificaEmprestimo[0]->data_saida->format('d/m/Y H:i');
        $Colaborador = DB::connection('vetorh')->table('R034FUN')
            ->select(['NUMEMP','TIPCOL','NUMCAD as id','NOMFUN as value','DESSIT','SITAFA'])
            ->join('R010SIT',function ($inner){
                $inner->on('R010SIT.CODSIT','=','R034FUN.SITAFA');
            })
            ->where('NUMEMP','=',$VerificaEmprestimo[0]->R034FUN_NUMEMP)
            ->where('TIPCOL','=',$VerificaEmprestimo[0]->R034FUN_TIPCOL)
            ->where('NUMCAD','=',$VerificaEmprestimo[0]->R034FUN_NUMCAD);
        if($Colaborador->count()){
            $Colaborador = $Colaborador->get();
            $Colaborador[0]->value = iconv('windows-1252','utf-8',$Colaborador[0]->value);
            $Colaborador[0]->DESSIT = iconv('windows-1252','utf-8',$Colaborador[0]->DESSIT);
            $VerificaEmprestimo[0]->colaborador = $Colaborador[0];
            return $VerificaEmprestimo;

        }

    }
    return $VerificaEmprestimo;
});

