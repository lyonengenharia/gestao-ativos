<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate ;


class AtivosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        /*if(Gate::denies('view')){
            \Auth::logout("Obrigado");
            abort(403,'Acesso negado');
        }*/

    }

    public function index()
    {
        if(Gate::denies('ativos')){
            abort(403);
        }
        return view("ativos.ativos", ["breadcrumbs" => array("Ativos" => "ativos"), "page" => "Ativos", "explanation" => " Busca de ativos"]);


    }

    public function search(Request $request)
    {
        $pat = $request->get('pat');
        $retorno = [];
        $query = DB::connection('sapiens')->table("E670BEM")
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
                'E670BEM.CODEMP'
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
            ->where('E670BEM.CODEMP', '=', 1)
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
            ->where('E670BEM.CODBEM', 'like', "$pat%")
            ->orderBy('E670BEM.CODEMP')
            ->orderBy('E670DRA.CODCCU')
            ->orderBy('E670BEM.CODBEM')
            ->limit(50)
            ->get();
        //dd(DB::connection('sapiens')->getQueryLog());
        foreach ($query as $row) {
            $row->DESCCU = iconv("ISO-8859-1", "UTF-8", $row->DESCCU);
            $row->DESBEM = iconv("ISO-8859-1", "UTF-8", $row->DESBEM);
            array_push($retorno, $row);
        }
        return response()->json($retorno);
        //for ($i=0;$i<)
        //response()->json($query);

    }

    public function locations(Request $request)
    {
        $pat = $request->get('pat');
        $retorno = [];
        $movimentation =[];
        $locations = DB::connection('sapiens')->table("E670LOC")
            ->select(['CODBEM', 'DATLOC', 'E670LOC.CODLOC', 'E674LOR.NOMLOC', 'E674LOR.DESLOC'])
            ->join('E674LOR', function ($join) {
                $join->on('E674LOR.CODLOC', '=', 'E670LOC.CODLOC')
                    ->where('E674LOR.CodEmp ', '=', '1');
            })
            ->where('E670LOC.CodEmp ', '=', '1')
            ->where('E670LOC.seqloc ', '=', '1')
            ->where('E670LOC.CodBem ', '=', $pat)
            ->get();

        foreach ($locations as $location) {
            $location->DESLOC = iconv("ISO-8859-1", "UTF-8", $location->DESLOC);
            $location->NOMLOC = iconv("ISO-8859-1", "UTF-8", $location->NOMLOC);
            array_push($retorno, $location);

        }
        DB::connection('sapiens')->enableQueryLog();
        $movimentationFinancial = DB::connection('sapiens')
            ->table('E670MOV')
            ->select(['E670MOV.CODBEM', 'E670MOV.DATMOV', 'E670MOV.DATLOC'
                , 'E670MOV.SEQMOV', 'E670MOV.SEQLOC', 'E670MOV.NUMMAN'
                , 'E001TNS.DESTNS', 'E001TNS.CODTNS'])
            ->join('E001TNS',function($join){
                $join->on('E670MOV.CODTNS','=','E001TNS.CODTNS')
                ->whereColumn('E670MOV.CODEMP','=','E001TNS.CODEMP');
            })
            ->where('E670MOV.CODEMP','=',1)
            ->where('E670MOV.CODBEM','=',$pat)
            ->whereIn('E670MOV.SEQLOC',[1,2])
            ->whereNotIn('E670MOV.CODTNS',[90815])
            ->get();
        foreach ($movimentationFinancial as $mov){
            $mov->DESTNS = iconv("ISO-8859-1", "UTF-8", $mov->DESTNS);
            array_push($movimentation,$mov);
        }
        return response()->json(["Locations" => $retorno,'MovFinancial'=>$movimentation]);


    }
}
