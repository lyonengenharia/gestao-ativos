<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Carbon\Carbon;
use Faker\Provider\cs_CZ\DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;


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
        $Empresas = DB::connection('vetorh')->table('R030EMP')->select(['numemp', 'nomemp', 'apeemp'])->get();
        $States = \App\State::orderBy('state','ASC')->get();
        foreach ($Empresas as $key => $value) {
            $Empresas[$key]->nomemp = iconv('windows-1252', 'utf-8', $Empresas[$key]->nomemp);
            $Empresas[$key]->apeemp = iconv('windows-1252', 'utf-8', $Empresas[$key]->apeemp);
        }
        return view("ativos.ativos", ["breadcrumbs" => array("Ativos" => "ativos"),
            "page" => "Ativos",
            "explanation" => " Busca de ativos",
            "empresas" => $Empresas,
            "states"=>$States

        ]);
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
            ->where('E670BEM.CODBEM', 'like', "$pat%")
            ->orderBy('E670BEM.CODEMP')
            ->orderBy('E670DRA.CODCCU')
            ->orderBy('E670BEM.CODBEM')
            ->limit(10);
            if($request->has('emp')){
                $query->where('E670LOC.CODEMP','=',$request->get('emp'));
            }
        $query = $query->get();
        //dd(DB::connection('sapiens')->getQueryLog());
        foreach ($query as $row) {
            $row->DESCCU = iconv("ISO-8859-1", "UTF-8", $row->DESCCU);
            $row->DESBEM = iconv("ISO-8859-1", "UTF-8", $row->DESBEM);
            $row->NOMEMP = iconv("ISO-8859-1", "UTF-8", $row->NOMEMP);
            $row->DESESP = iconv("ISO-8859-1", "UTF-8", $row->DESESP);
            $row->ABRESP = iconv("ISO-8859-1", "UTF-8", $row->ABRESP);
            $data = new Carbon($row->DATAQI);
            $row->DATAQI = $data->format('d/m/Y');
            $row->EMPRST = \App\Emprestimo::where('E070EMP_CODEMP', '=', $row->CODEMP)->where('E670BEM_CODBEM', '=', $row->CODBEM)->where('data_entrada', '=', null)->count();
            $row->ASSOC = \App\Connect::where('data_out', '=', null)
                ->where('E670BEM_CODBEM', '=', $row->CODBEM)
                ->where('E070EMP_CODEMP', '=', $row->CODEMP)
                ->count();
            $row->state = \App\Complement::
            join('states',function ($join){
                $join->on('state_id','=','states.id');
            })
            ->where('E670BEM_CODBEM','=',$row->CODBEM)->where('E070EMP_CODEMP','=',$row->CODEMP)->get();
            $row->keys = \App\BensKeys::select(["benskeys.id",
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
            })->where('E670BEM_CODBEM', '=', $row->CODBEM)
              ->where('E070EMP_CODEMP', '=', $row->CODEMP)->get();
            array_push($retorno, $row);
        }
        return response()->json($retorno);
    }

    public function locations(Request $request)
    {
        $pat = $request->get('pat');
        $retorno = [];
        $movimentation = [];
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
            $dataLoc = new Carbon($location->DATLOC);
            $location->DATLOC = $dataLoc->format('d/m/Y');

            array_push($retorno, $location);

        }
        $movimentationFinancial = DB::connection('sapiens')
            ->table('E670MOV')
            ->select(['E670MOV.CODBEM', 'E670MOV.DATMOV', 'E670MOV.DATLOC'
                , 'E670MOV.SEQMOV', 'E670MOV.SEQLOC', 'E670MOV.NUMMAN'
                , 'E001TNS.DESTNS', 'E001TNS.CODTNS','E670MOV.CODCCU'])
            ->join('E001TNS', function ($join) {
                $join->on('E670MOV.CODTNS', '=', 'E001TNS.CODTNS')
                    ->whereColumn('E670MOV.CODEMP', '=', 'E001TNS.CODEMP');
            })
            ->where('E670MOV.CODEMP', '=', 1)
            ->where('E670MOV.CODBEM', '=', $pat)
            ->whereIn('E670MOV.SEQLOC', [1, 2])
            ->whereNotIn('E670MOV.CODTNS', [90815])
            ->orderBy('E670MOV.DATMOV','DESC')
            ->get();
        foreach ($movimentationFinancial as $mov) {
            $mov->DESTNS = iconv("ISO-8859-1", "UTF-8", $mov->DESTNS);
            $dataMov = new Carbon($mov->DATMOV);
            $mov->DATMOV = $dataMov->format('d/m/Y');
            array_push($movimentation, $mov);

        }
        return response()->json(["Locations" => $retorno, 'MovFinancial' => $movimentation]);
    }

    public function Emprestimo(Request $request)
    {
        $data = \Carbon\Carbon::createFromFormat("d/m/Y", $request->get('dataempdev'), "America/Sao_Paulo");
        if (!$request->has('dataempdev')) {
            return response()->json(["erro" => 1, "msg" => "Favor preencher a data de empréstimo"]);
        }
        $VerificarEmprestimo = \App\Emprestimo::where('E670BEM_CODBEM', '=', $request->get("codbem"))
            ->where('E070EMP_CODEMP', '=', $request->get('codbememp'))
            ->where('data_entrada', '=', null)->count();

        $CheckConect = \App\Connect::where('data_out', '=', null)
            ->where('E670BEM_CODBEM', '=', $request->get('codbem'))
            ->where('E070EMP_CODEMP', '=', $request->get('codbememp'))->count();
        if ($VerificarEmprestimo) {
            return response()->json(["erro" => 1, "msg" => "O item se encontra emprestado!"]);
        }
        if ($CheckConect) {
            return response()->json(["erro" => 1, "msg" => "O item se encontra associado a um colaborador!"]);
        }
        $Emprestimo = new \App\Emprestimo();
        $Emprestimo->E670BEM_CODBEM = $request->get("codbem");
        $Emprestimo->E070EMP_CODEMP = $request->get("codbememp");
        $Emprestimo->data_saida = $data->toDateTimeString();
        $Emprestimo->R034FUN_NUMEMP = $request->get("numemp");
        $Emprestimo->R034FUN_TIPCOL = $request->get("tipcol");
        $Emprestimo->R034FUN_NUMCAD = $request->get("numcad");
        $Emprestimo->obs_saida = $request->get("obsemp");
        $Emprestimo->save();
        return response()->json(["erro" => 0, "msg" => "O item foi emprestado com sucesso!"]);
    }

    public function Devolucao(Request $request)
    {
        $VerificaEmprestimo = \App\Emprestimo::where('E670BEM_CODBEM', '=', $request->get("codbem"))
            ->where('E070EMP_CODEMP', '=', $request->get('codbememp'))
            ->where('data_entrada', '=', null)->count();
        if ($VerificaEmprestimo) {
            $data = \Carbon\Carbon::createFromFormat("d/m/Y", $request->get('data'), "America/Sao_Paulo");
            $Emprestimo = \App\Emprestimo::where('E670BEM_CODBEM', '=', $request->get("codbem"))
                ->where('E070EMP_CODEMP', '=', $request->get('codbememp'))
                ->where('data_entrada', '=', null)
                ->update(['data_entrada' => $data->toDateTimeString(), 'obs_entrada' => $request->get("obs_entrada")]);
        } else {
            return response()->json(["error" => 1, "msg" => "Esse item não pode ser devolvido! Ele não consta como emprestado."]);
        }
        return response()->json(["error" => 0, "msg" => "O item foi devolvido com sucesso."]);
    }

    /**
     * @return string
     */
    public function connect(Request $request)
    {

        $dataassoc = \Carbon\Carbon::createFromFormat("d/m/Y", $request->get('dataempdev'), "America/Sao_Paulo");
        $CheckConect = \App\Connect::where('data_out', '=', null)
            ->where('E670BEM_CODBEM', '=', $request->get('codbem'))
            ->where('E070EMP_CODEMP', '=', $request->get('codbememp'));
        $Connect = new \App\Connect();
        if ($CheckConect->count()) {
            return response()->json(['error' => 1, 'msg' => 'Esse item já está associado']);
        } else {

            $Connect->E670BEM_CODBEM = $request->get('codbem');
            $Connect->E070EMP_CODEMP = $request->get('codbememp');
            $Connect->R034FUN_NUMEMP = $request->get('numemp');
            $Connect->R034FUN_TIPCOL = $request->get('tipcol');
            $Connect->R034FUN_NUMCAD = $request->get('numcad');
            $Connect->obs_out = $request->get('obsemp');
            $Connect->data_in = $dataassoc->toDateTimeString();
            $Connect->save();
            if ($request->get('gerarTermo')) {
                $Termo = new \App\Termo();
                $Termo->tipotermo_id = 1;
                $Termo->maketermo = true;
                $Termo->pathtermo = "teste";
            }
        }

        return response()->json(['error' => 0, 'msg' => 'Item Associado com sucesso']);
    }

    public function Disconnect(Request $request)
    {
        $data = \Carbon\Carbon::createFromFormat("d/m/Y", $request->get('data'), "America/Sao_Paulo");
        $CheckConect = \App\Connect::where('data_out', '=', null)
            ->where('E670BEM_CODBEM', '=', $request->get('codbem'))
            ->where('E070EMP_CODEMP', '=', $request->get('codbememp'));
        if($CheckConect->count()){
            $CheckConect->update(['data_out'=>$data->toDateTimeString(),'obs_out'=>$request->get('obs')]);
            return response()->json(['error'=>0,'msg'=>'Item desassociado com sucesso!']);
        }
        return response()->json(['error'=>1,'msg'=>'O item não está associado']);
    }

    /**
     * @return string
     */
    public function State(Request $request)
    {
        $Complement = \App\Complement::where('E670BEM_CODBEM','=',$request->get('codbem'))->where('E070EMP_CODEMP','=',$request->get('codbememp'));
        if($Complement->count()){
            $Complement->update(['state_id'=>$request->get('status'),'description'=>$request->get('obs')]);
            return response()->json(['error'=>0,'msg'=>'Estado do item foi atualizado!']);
        }else{
            $Complement = new \App\Complement();
            $Complement->E670BEM_CODBEM = $request->get('codbem');
            $Complement->E070EMP_CODEMP = $request->get('codbememp');
            $Complement->state_id = $request->get('status');
            $Complement->description = $request->get('obs');
            $Complement->save();
            return response()->json(['error'=>0,'msg'=>'Estado do item foi inserido!']);
        }
    }
    public function GetState(Request $request){

        $Complement = \App\Complement::where('E670BEM_CODBEM','=',$request->get('codbem'))->where('E070EMP_CODEMP','=',$request->get('codbememp'));
        if($Complement->count()){
            $Complement = $Complement->get();
            $Complement[0]->updated = $Complement[0]->updated_at->format('d/m/Y H:i');
            $Complement[0]->create = $Complement[0]->created_at->format('d/m/Y');
        }
       return $Complement;
    }

}
