<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Pojo\Bem;
use App\Pojo\Employed;
use Carbon\Carbon;
use Faker\Provider\cs_CZ\DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;


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
        $States = \App\State::orderBy('state', 'ASC')->get();
        $tipoTermos = \App\tipotermo::all();
        foreach ($Empresas as $key => $value) {
            $Empresas[$key]->nomemp = iconv('windows-1252', 'utf-8', $Empresas[$key]->nomemp);
            $Empresas[$key]->apeemp = iconv('windows-1252', 'utf-8', $Empresas[$key]->apeemp);
        }
        return view("ativos.ativos", ["breadcrumbs" => array("Ativos" => "ativos"),
            "page" => "Ativos",
            "explanation" => " Busca de ativos",
            "empresas" => $Empresas,
            "states" => $States,
            "tipoTermos" => $tipoTermos

        ]);
    }

    public function search(Request $request)
    {
        $employed = $request->get('employed');
        $pat = $request->get('pat');
        $page = null;
        if ($request->has('qtd')) {
            $page = $request->get('qtd');
        } else {
            $page = 15;
        }
        if ($page < 1) {
            $page = 1;
        }
        if ($page > 50) {
            $page = 50;
        }
        $retorno = [];
        DB::connection('sapiens')->enableQueryLog();
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
                'E670DRA.CODCCU',
                'E044CCU.DESCCU',
                'E670BEM.DESBEM',
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
                $join->on('E670DRA.CODEMP', '=', 'E670BEM.CODEMP')
                    ->whereColumn('E670DRA.CODBEM', '=', 'E670BEM.CODBEM');
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
            ->orderBy('E670BEM.CODEMP')
            ->orderBy('E670DRA.CODCCU')
            ->orderBy('E670BEM.CODBEM');

        //Filter per user
        if (!empty($employed['usr']) && !empty($employed['emp']) && !empty($employed['tip'])) {
            $employed = new \App\Pojo\Employed($employed['emp'], $employed['tip'], $employed['usr']);
            $assoc = \App\Connect::where('data_out', '=', null)
                ->where('R034FUN_NUMEMP', '=', $employed->NUMEMP)
                ->where('R034FUN_TIPCOL', '=', $employed->TIPCOL)
                ->where('R034FUN_NUMCAD', '=', $employed->NUMCAD);
            $assoc->count();
            if ($assoc->count() > 0) {
                $assoc = $assoc->get();
                $bem = [];

                foreach ($assoc as $key => $asso) {
                    array_push($bem, $asso->E670BEM_CODBEM);
                }
                $query->whereIn('E670BEM.CODBEM', $bem);
            }

        } elseif (!empty($pat)) {
            $query->where('E670BEM.CODBEM', 'like', "$pat%");
        }
        if ($request->has('ccu') && !empty($request->get('ccu'))) {
            $query->where('E670DRA.CODCCU', '=', $request->get('ccu'));
        }
        $query = $query->paginate($page);

        foreach ($query as $Key => $row) {
            $assoc = \App\Connect::where('data_out', '=', null)
                ->where('E670BEM_CODBEM', '=', $row->CODBEM)
                ->where('E070EMP_CODEMP', '=', $row->CODEMP);
            $query[$Key]->DESCCU = iconv("ISO-8859-1", "UTF-8", $row->DESCCU);
            $query[$Key]->DESBEM = iconv("ISO-8859-1", "UTF-8", $row->DESBEM);
            $query[$Key]->NOMEMP = iconv("ISO-8859-1", "UTF-8", $row->NOMEMP);
            $query[$Key]->DESESP = iconv("ISO-8859-1", "UTF-8", $row->DESESP);
            $query[$Key]->ABRESP = iconv("ISO-8859-1", "UTF-8", $row->ABRESP);
            $data = new Carbon($row->DATAQI);
            $query[$Key]->DATAQI = $data->format('d/m/Y');
            $query[$Key]->EMPRST = \App\Emprestimo::where('E070EMP_CODEMP', '=', $row->CODEMP)->where('E670BEM_CODBEM', '=', $row->CODBEM)->where('data_entrada', '=', null)->count();
            $Bem = new Bem($row->CODBEM, $row->CODEMP);
            $query[$Key]->HISTEMPRST = $this->loanHistory($Bem);
            $query[$Key]->ASSOC = $assoc->count();
            if ($query[$Key]->ASSOC) {
                $assoc = $assoc->get();
                $query[$Key]->connect = DB::connection('vetorh')->table('R034FUN')
                    ->select(['NUMEMP', 'TIPCOL', 'NUMCAD as id', 'NOMFUN as value', 'DESSIT', 'SITAFA'])
                    ->join('R010SIT', function ($inner) {
                        $inner->on('R010SIT.CODSIT', '=', 'R034FUN.SITAFA');
                    })
                    ->where('NUMEMP', '=', $assoc[0]->R034FUN_NUMEMP)
                    ->where('TIPCOL', '=', $assoc[0]->R034FUN_TIPCOL)
                    ->where('NUMCAD', '=', $assoc[0]->R034FUN_NUMCAD)
                    ->get();
                if ($query[$Key]->connect->count() > 0) {
                    foreach ($query[$Key]->connect as $index => $Value) {
                        $query[$Key]->connect[$index]->DESSIT = iconv("ISO-8859-1", "UTF-8", $Value->DESSIT);
                        $query[$Key]->connect[$index]->value = iconv("ISO-8859-1", "UTF-8", $Value->value);
                    }
                }
            } else {
                $query[$Key]->connect = null;
            }
            $query[$Key]->state = \App\Complement::
            select(['states.id', 'states.description', 'E070EMP_CODEMP', 'E670BEM_CODBEM', 'complements.created_at', 'complements.description as desc', 'states.state'])
                ->join('states', function ($join) {
                    $join->on('state_id', '=', 'states.id');
                })
                ->where('E670BEM_CODBEM', '=', $row->CODBEM)->where('E070EMP_CODEMP', '=', $row->CODEMP)->get();
            $query[$Key]->keys = \App\BensKeys::select(["benskeys.id",
                "key_id", "benskeys.E670BEM_CODBEM",
                "E070EMP_CODEMP", "keys.id as keyid", "keys.key",
                "produtos.id as Proid", "produtos.model",
                "empresas.id as Empid", "empresas.name",
                "keys.quantity", "keys.in_use", "maturity_date"
            ])->join('keys', function ($inner) {
                $inner->on('keys.id', '=', 'benskeys.key_id');
            })->join('produtos', function ($inner) {
                $inner->on('produtos.id', '=', 'keys.produto_id');
            })->join('empresas', function ($inner) {
                $inner->on('empresas.id', '=', 'produtos.empresa_id');
            })->where('E670BEM_CODBEM', '=', $row->CODBEM)
                ->where('E070EMP_CODEMP', '=', $row->CODEMP)->get();
            $query[$Key]->history = $this->history($row->CODBEM, $row->CODEMP);
        }
        return response()->json($query);
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
                , 'E001TNS.DESTNS', 'E001TNS.CODTNS', 'E670MOV.CODCCU'])
            ->join('E001TNS', function ($join) {
                $join->on('E670MOV.CODTNS', '=', 'E001TNS.CODTNS')
                    ->whereColumn('E670MOV.CODEMP', '=', 'E001TNS.CODEMP');
            })
            ->where('E670MOV.CODEMP', '=', 1)
            ->where('E670MOV.CODBEM', '=', $pat)
            ->whereIn('E670MOV.SEQLOC', [1, 2])
            ->whereNotIn('E670MOV.CODTNS', [90815])
            ->orderBy('E670MOV.DATMOV', 'DESC')
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

        $Employed = new \App\Pojo\Employed($request->get("numemp"), $request->get("tipcol"), $request->get("numcad"));
        $To = env('MAIL_DEFAULT_TI', 'informatica@lyonegenharia.com.br');
        if (!empty($Employed->EMACOM)) {
            $To = $Employed->EMACOM;
        } else if (!empty($Employed->EMAPAR)) {
            $To = $Employed->EMAPAR;
        }

        $Data = new \App\Pojo\Message();
        $Data->setTitle("Empréstimo de Equipamento");
        $Data->setSubTitle("Empréstimo realizado dia :" . $request->get('dataempdev'));
        $Data->setBody("Prezado(a) " . $Employed->NOMFUN . " <p>Informamos que o equipamento  com o patrimônio " . $request->get("codbem") .
            " encontra-se em sua responsabilidade.</p><p>Descrição de saída: " . (empty($request->get("obsemp")) ? "Nada consta." : $request->get("obsemp")) . "</p>");
        $message = new \App\Mail\Information($Data);
        $message->subject("Emprestimo de equipamentos");
        $message->to($To);
        $message->from(env('MAIL_DEFAULT_TI', 'informatica@lyonegenharia.com.br'));
        Mail::send($message);
        return response()->json(["erro" => 0, "msg" => "Item emprestado com sucesso."]);
    }

    public function Devolucao(Request $request)
    {
        $VerificaEmprestimo = \App\Emprestimo::where('E670BEM_CODBEM', '=', $request->get("codbem"))
            ->where('E070EMP_CODEMP', '=', $request->get('codbememp'))
            ->where('data_entrada', '=', null);
        if ($VerificaEmprestimo->count()) {
            $VerificaEmprestimo = $VerificaEmprestimo->get();
            $data = \Carbon\Carbon::createFromFormat("d/m/Y", $request->get('data'), "America/Sao_Paulo");
            \App\Emprestimo::where('E670BEM_CODBEM', '=', $request->get("codbem"))
                ->where('E070EMP_CODEMP', '=', $request->get('codbememp'))
                ->where('data_entrada', '=', null)
                ->update(['data_entrada' => $data->toDateTimeString(), 'obs_entrada' => $request->get("obs")]);

            //Envia email

            $Employed = new \App\Pojo\Employed($VerificaEmprestimo[0]->R034FUN_NUMEMP, $VerificaEmprestimo[0]->R034FUN_TIPCOL, $VerificaEmprestimo[0]->R034FUN_NUMCAD);
            $To = env('MAIL_DEFAULT_TI', 'informatica@lyonegenharia.com.br');
            if (!empty($Employed->EMACOM)) {
                $To = $Employed->EMACOM;
            } else if (!empty($Employed->EMAPAR)) {
                $To = $Employed->EMAPAR;
            }
            $Data = new \App\Pojo\Message();
            $Data->setTitle("Devolução de Equipamento");
            $Data->setSubTitle("Devolução realizada dia :" . $request->get('data'));
            $Data->setBody("Prezado(a) " . $Employed->NOMFUN . " <p>Informamos que o equipamento  com o patrimônio "
                . $request->get("codbem") . " foi devolvido. </p><p>Segue as observações: " .
                (empty($request->get("obs")) ? "Nada consta." : $request->get("obs")) . "</p>");
            $message = new \App\Mail\Information($Data);
            $message->subject("Devolução de equipamentos");
            $message->to($To);
            $message->from(env('MAIL_DEFAULT_TI', 'informatica@lyonegenharia.com.br'));
            Mail::send($message);
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
        if ($CheckConect->count()) {
            $CheckConect->update(['data_out' => $data->toDateTimeString(), 'obs_out' => $request->get('obs')]);
            return response()->json(['error' => 0, 'msg' => 'Item desassociado com sucesso!']);
        }
        return response()->json(['error' => 1, 'msg' => 'O item não está associado']);
    }

    /**
     * @return string
     */
    public function State(Request $request)
    {
        $Complement = \App\Complement::where('E670BEM_CODBEM', '=', $request->get('codbem'))->where('E070EMP_CODEMP', '=', $request->get('codbememp'));
        if ($Complement->count()) {
            $Complement->update(['state_id' => $request->get('status'), 'description' => $request->get('obs')]);
            return response()->json(['error' => 0, 'msg' => 'Estado do item foi atualizado!']);
        } else {
            $Complement = new \App\Complement();
            $Complement->E670BEM_CODBEM = $request->get('codbem');
            $Complement->E070EMP_CODEMP = $request->get('codbememp');
            $Complement->state_id = $request->get('status');
            $Complement->description = $request->get('obs');
            $Complement->save();
            return response()->json(['error' => 0, 'msg' => 'Estado do item foi inserido!']);
        }
    }

    public function GetState(Request $request)
    {
        $Complement = \App\Complement::where('E670BEM_CODBEM', '=', $request->get('codbem'))->where('E070EMP_CODEMP', '=', $request->get('codbememp'));
        if ($Complement->count()) {
            $Complement = $Complement->get();
            $Complement[0]->updated = $Complement[0]->updated_at->format('d/m/Y H:i');
            $Complement[0]->create = $Complement[0]->created_at->format('d/m/Y');
            return $Complement;
        }
        return null;
    }

    public function termoNovo(Request $request)
    {

        $bem = new Bem($request->get(1)['coditem'], $request->get(1)['codemp']);
        $employed = new Employed($request->get(2)['numemp'], $request->get(2)['tipcol'], $request->get(2)['numcol']);
        $tipoTermo = \App\tipotermo::find($request->get(0)['tipo']['id']);
        $connect = \App\Connect::where('E670BEM_CODBEM', '=', $bem->CodBem)
            ->where('E070EMP_CODEMP', '=', $bem->CodEmp)
            ->where('R034FUN_NUMEMP', '=', $employed->NUMEMP)
            ->where('R034FUN_TIPCOL', '=', $employed->TIPCOL)
            ->where('R034FUN_NUMCAD', '=', $employed->NUMCAD)
            ->where('data_out', '=', null);
        if ($connect->count() == 0) {
            return (array)(new \App\Pojo\Response(1, null, 'Esse item e o colaborador não estão associados.'));
        }
        if ($connect->get()[0]->Termos()->where('tipotermo_id', '=', $tipoTermo->id)->count() > 0) {
            return (array)(new \App\Pojo\Response(1, null, 'Já existe esse tipo de termo cadastrado.'));
        }
        $termo = new \App\Termo();
        $termo->tipotermo_id = $tipoTermo->id;
        $termo->obs = $request->get(0)['obs'];
        $termo->save();
        $connect->get()[0]->Termos()->attach($termo);
        return (array)(new \App\Pojo\Response(0, null, 'Termo inserido com sucesso!'));
    }

    private function history($ben, $emp)
    {
        $Historyes = DB::table('connects')->where('E670BEM_CODBEM', '=', $ben)->where('E070EMP_CODEMP', '=', $emp)->where('data_out', '<>', null)->get();
        foreach ($Historyes as $Key => $History) {

            $Historyes[$Key]->data_in = new Carbon($Historyes[$Key]->data_in);
            $Historyes[$Key]->data_in = $Historyes[$Key]->data_in->format('d/m/Y H:i');
            $Historyes[$Key]->data_out = new Carbon($Historyes[$Key]->data_out);
            $Historyes[$Key]->data_out = $Historyes[$Key]->data_out->format('d/m/Y H:i');
            $Employed = DB::connection('vetorh')->table('R034FUN')
                ->select(['NUMEMP', 'TIPCOL', 'NUMCAD as id', 'NOMFUN as value', 'DESSIT', 'SITAFA'])
                ->join('R010SIT', function ($inner) {
                    $inner->on('R010SIT.CODSIT', '=', 'R034FUN.SITAFA');
                })
                ->where('NUMEMP', '=', $History->R034FUN_NUMEMP)
                ->where('TIPCOL', '=', $History->R034FUN_TIPCOL)
                ->where('NUMCAD', '=', $History->R034FUN_NUMCAD)
                ->get();
            if (count($Employed) > 0) {
                $Employed[0]->value = iconv('windows-1252', 'utf-8', $Employed[0]->value);
                $Employed[0]->DESSIT = iconv('windows-1252', 'utf-8', $Employed[0]->DESSIT);

                $Employed = $Employed[0];
            } else {
                $Employ = null;
            }
            $Historyes[$Key]->Employed = $Employed;

        }
        return $Historyes;
    }

    private function loanHistory(Bem $Bem)
    {
        $Loans = \App\Emprestimo::where('E070EMP_CODEMP', '=', $Bem->CodEmp)->where('E670BEM_CODBEM', '=', $Bem->CodBem)->orderBy('created_at', 'DESC')->take(10)->get();
        foreach ($Loans as $Key => $Loan) {
            $Loans[$Key]->employed = new Employed($Loan->R034FUN_NUMEMP, $Loan->R034FUN_TIPCOL, $Loan->R034FUN_NUMCAD);
        }
        return $Loans;
    }

    public function termos(Request $request)
    {
        /* $connect = \App\Connect::where('E670BEM_CODBEM','=',$bem->CodBem)
             ->where('E070EMP_CODEMP','=',$bem->CodEmp)
             ->where('R034FUN_NUMEMP','=',$employed->NUMEMP)
             ->where('R034FUN_TIPCOL','=',$employed->TIPCOL)
             ->where('R034FUN_NUMCAD','=',$employed->NUMCAD)
             ->where('data_out','=',null);*/

        return $request->get('bem');
    }
}
