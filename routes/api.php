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
             ->select(['keys.key','keys.quantity','keys.in_use','produtos.model','empresas.name','benskeys.E670BEM_CODBEM','benskeys.E070EMP_CODEMP'])
             ->join('keys',function ($inner){
                 $inner->on('keys.ID','=','benskeys.key_id');
             })->join('produtos',function ($inner){
                 $inner->on('produtos.id','=','keys.produto_id');
             })->join('empresas',function ($inner){
                 $inner->on('empresas.id','=','produtos.empresa_id');
             })->where('benskeys.key_id','=',$key)->get();

    foreach ($itens as $key => $iten){

        $comp = DB::connection('sapiens')->table("E670BEM")
            ->select([
                'E670BEM.CODBEM',
                'E670LOC.CODEMP',
                'E670LOC.CODBEM',
                'E670LOC.DATLOC',
                'E670LOC.SEQLOC',
                'E670LOC.CTARED',
                'E670BEM.DATAQI',
                'E044CCU.CODEMP',
                'E044CCU.CODCCU',
                'E044CCU.DESCCU',
                'E670BEM.DESBEM',
                'E670LOC.CODCCU',
                'E670BEM.SITPAT',
                'E670BEM.CODEMP',
                'E070EMP.NOMEMP',
                'E674ESP.DESESP',
                'E674ESP.ABRESP'
            ])
            ->join('E670LOC', function ($join) {
                $join->on('E670LOC.CODEMP', '=', 'E670BEM.CODEMP');

            })
            ->Join('E670DRA', function ($join) {
                $join->on('E670DRA.CODEMP', '=', 'E670LOC.CODEMP');
            })
            ->join('E044CCU', function ($join) {
                $join->on('E044CCU.CODEMP', '=', 'E670DRA.CODEMP');
            })
            ->join('E070EMP', function ($join) {
                $join->on('E070EMP.CODEMP', '=', 'E670BEM.CODEMP');
            })
            ->join('E674ESP', function ($join) {
                $join->on('E674ESP.CODESP', '=', 'E670BEM.CODESP')
                    ->whereColumn('E674ESP.CODEMP', '=', 'E670BEM.CODEMP');
            })
            //->where('E670BEM.CODEMP', '=', 1)
            ->whereColumn('E670LOC.CODEMP', '=', 'E670BEM.CODEMP')
            ->whereColumn('E670LOC.CODBEM', '=', 'E670BEM.CODBEM')
            ->whereColumn('E670DRA.CODEMP', '=', 'E670LOC.CODEMP')
            ->whereColumn('E670DRA.CODBEM', '=', 'E670LOC.CODBEM')
            ->whereColumn('E670DRA.DATLOC', '=', 'E670LOC.DATLOC')
            ->whereColumn('E670DRA.SEQLOC', '=', 'E670LOC.SEQLOC')
            ->whereColumn('E044CCU.CODEMP', '=', 'E670DRA.CODEMP')
            ->whereColumn('E044CCU.CODCCU', '=', 'E670DRA.CODCCU')
            ->where('E670LOC.ULTREG', '=', 'S')
            ->where('E670LOC.SITLOC', '=', 'A')
            ->where('E670BEM.CODBEM', '=', $iten->E670BEM_CODBEM)
            ->where('E670BEM.CODEMP', '=', $iten->E070EMP_CODEMP)
            ->get();

        $comp[0]->DESCCU = iconv('windows-1252','utf-8', $comp[0]->DESCCU);
        $comp[0]->DESBEM = iconv('windows-1252','utf-8', $comp[0]->DESBEM);
        $comp[0]->DESESP = iconv('windows-1252','utf-8', $comp[0]->DESESP);
        $comp[0]->NOMEMP = iconv('windows-1252','utf-8', $comp[0]->NOMEMP);
        $comp[0]->ABRESP = iconv('windows-1252','utf-8', $comp[0]->ABRESP);
        $itens[$key]->iten = $comp;
    }
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

Route::get('costscenters/{search}',function($search){
   $CostsCenters = DB::connection('sapiens')->table('E044CCU')->where('CODCCU','like',"$search%")->get();
   foreach ($CostsCenters as $Key => $CostCenter){
       $CostsCenters[$Key]->DesCcu = iconv('windows-1252','utf8',$CostCenter->DesCcu);
       $CostsCenters[$Key]->AbrCcu = iconv('windows-1252','utf8',$CostCenter->AbrCcu);
       $CostsCenters[$Key]->id = $CostsCenters[$Key]->CodCcu;
       $CostsCenters[$Key]->value = $CostsCenters[$Key]->CodCcu . " - " . $CostsCenters[$Key]->DesCcu ;
   }
   return $CostsCenters;
});

