<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = \App\User::count();
        $equipamentos = DB::connection('sapiens')->table("E670BEM")
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
            ->where('E044CCU.CODCCU','=','6652')
            ->whereIn('E674ESP.CODESP',[18,3])
            ->count();
        $emprestimo = \App\Emprestimo::where('data_entrada','=',null);
        $devolvidos = \App\Emprestimo::where('data_entrada','!=',null)->orderBy('data_entrada','DESC')->limit(15)->get();
        $datamaior = Carbon::now()->addDays(45);
        $data = Carbon::now();

        $licencas = \App\Key::select(['keys.id','keys.key','keys.quantity','keys.in_use','produtos.model','empresas.name','keys.maturity_date'])
            ->join('produtos',function ($inner){
                $inner->on('produtos.id','=','keys.produto_id');
            })->join('empresas',function ($inner) {
                $inner->on('empresas.id', '=', 'produtos.empresa_id');
            })->where('maturity_date','>',$data)
            ->where('maturity_date','<',$datamaior)->get();

        $vencidas = \App\Key::select(['keys.id','keys.key','keys.quantity','keys.in_use','produtos.model','empresas.name','keys.maturity_date'])
            ->join('produtos',function ($inner){
                $inner->on('produtos.id','=','keys.produto_id');
            })->join('empresas',function ($inner) {
                $inner->on('empresas.id', '=', 'produtos.empresa_id');
            })->where('maturity_date','<',$data)->get();
        return view('dashboard.dashboard',
            ["breadcrumbs" => array("Home" => "home"),
                "page" => "Dashboard",
                "explanation" => " Estatística e visão geral",
                "user"=>$user,
                "informatica"=>$equipamentos,
                "emprestimos"=>$emprestimo->count(),
                "ultimosEmprestimos"=>$emprestimo->orderBy('data_saida', 'desc')->limit(10)->get(),
                "devolvidos"=>$devolvidos,
                "vencimentolicencas"=>$licencas,
                "vencidaslicencas"=>$vencidas
            ]
        );
    }
}
