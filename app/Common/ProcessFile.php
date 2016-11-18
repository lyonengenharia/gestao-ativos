<?php
/**
 * Created by PhpStorm.
 * User: wfs
 * Date: 18/11/2016
 * Time: 15:20
 */

namespace App\Common;


use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use \App\Facades\Logging;



class ProcessFile
{
    public function Process($File)
    {
        Logging::CreateFile($File);
        Logging::PreEnd($File, "Processando o arquivo" . $File);
        Logging::AppEnd($File, "Iniciando:" . Carbon::now());
        if (Storage::exists('import/data/' . $File)) {
            $lines = file(storage_path('app/import/data/') . $File);
            $PositionsFile = $this->LoadHeadCVS($this->SliptLine($lines[0]));
            $PatrimonyPosition = $this->FindCellPosition($PositionsFile, 'pat');
            $UserPosition = $this->FindCellPosition($PositionsFile, 'usu');
            foreach ($lines as $Keys => $line):

                if ($Keys == 0) {
                    Logging::AppEnd($File, "Cabecalho:" . $line);
                    continue;
                }
                Logging::AppEnd($File, "Iniciando linha: " . $Keys . " " . Carbon::now() . "\n");
                $row = $this->SliptLine($line);
                $PatrimonyInformation = null;
                $UserInformation = $this->CheckUser($row[$UserPosition->pos]);
                /***
                 * Match 1 Company
                 * Match 2 Item
                 * Match 3 Patrimony
                 *
                 */
                if (preg_match('/^([a-zA-Z]+)?.([a-zA-Z]+.)?([0-9]+.?[0-9]+)/', $row[$PatrimonyPosition->pos], $match)) {
                    $PatrimonyInformation = $this->CkeckPatrimony($match[1], $match[2], $match[3]);
                }
                $Log = $this->Connectkeys($row, $PositionsFile, $PatrimonyInformation);
                Logging::AppEnd($File, "Associando Chaves:" . Carbon::now() . " \n" . $Log);
                $Log = $this->ConnectUsers($UserInformation, $PatrimonyInformation);
                Logging::AppEnd($File, "Associando Colaborador:" . Carbon::now() . " \n" . $Log);
                Logging::AppEnd($File, "Finalizando linha: " . $Keys . " " . Carbon::now() . "\n");
            endforeach;
        }
        Logging::AppEnd($File, "Finalizando:" . Carbon::now());
        return 'Arquivo carregado';
    }


    public function Import(Request $request)
    {
        $files = $request->file('file');
        if (!empty($files)) {
            foreach ($files as $file):
                if ($file->extension() == 'csv' || $file->extension() == 'txt') {
                    if (!Storage::exists('import/data' . $file->getClientOriginalName())) {
                        Storage::put('import/data/' . $file->getClientOriginalName(), file_get_contents($file));
                    } else {
                        return json_encode(['error' => 'Arquivo já existe']);
                    }
                } else {
                    return json_encode(['error' => 'Tipo de arquivo não permitido!']);
                }
            endforeach;

            return json_encode('Upload realizado com sucesso');
        }
    }

    public function Delete(Request $request)
    {
        if (Storage::exists('import/data/' . $request->get('data'))) {
            Storage::delete('import/data/' . $request->get('data'));
            return json_encode(["error" => 0, "msg" => "O arquivo apagado."]);
        }
        return json_encode(["error" => 1, "msg" => "O arquivo não existe, ou já foi apagado."]);
    }
    private function ConnectUsers($user, $PatrimonyInformation)
    {
        $Log = "";
        if (!empty($user) && !empty($PatrimonyInformation)) {
            $CheckConnect = \App\Connect::Where('E070EMP_CODEMP','=',$PatrimonyInformation[0]->CODEMP)
                ->where('E670BEM_CODBEM','=',$PatrimonyInformation[0]->CODBEM)
                ->where('R034FUN_NUMCAD','=',$user->id)
                ->where('R034FUN_NUMEMP','=',$user->NUMEMP)
                ->where('R034FUN_TIPCOL','=',$user->TIPCOL)
                ->where('data_out', '=', null);

            if($CheckConnect->count()==0){
                $Connect = new \App\Connect();
                $Connect->E670BEM_CODBEM = $PatrimonyInformation[0]->CODBEM;
                $Connect->E070EMP_CODEMP = $PatrimonyInformation[0]->CODEMP;
                $Connect->R034FUN_NUMEMP = $user->NUMEMP;
                $Connect->R034FUN_TIPCOL = $user->TIPCOL;
                $Connect->R034FUN_NUMCAD = $user->id;
                $Connect->obs_out = 'Inserido automaticamente';
                $Connect->data_in = Carbon::now();
                $Connect->save();
                $Log = "Usuário: ".$user->id. " Emp:".$user->NUMEMP." Tip:".$user->TIPCOL. " <> Item:".$PatrimonyInformation[0]->CODBEM." Emp:".$PatrimonyInformation[0]->CODEMP."\n";
            }else{
                $Log = "(Já Associado) Usuário: ".$user->id. " Emp:".$user->NUMEMP." Tip:".$user->TIPCOL. " <> Item:".$PatrimonyInformation[0]->CODBEM." Emp:".$PatrimonyInformation[0]->CODEMP."\n";
            }

        }
        return $Log;
    }


    private function SliptLine($Line)
    {
        $explodes = explode(';', $Line);
        foreach ($explodes as $key => $value) {
            $explodes[$key] = iconv("ISO-8859-1", "UTF-8", $explodes[$key]);
        }
        return $explodes;
    }

    /**
     * Load the positions of cvs,check if exist company and product
     * @param $FirstLine
     * @return array
     */
    private function LoadHeadCVS($FirstLine)
    {
        $list = [];
        for ($i = 0; $i < count($FirstLine); $i++) {
            $Positions = new \stdClass;
            $Positions->pos = "";
            $Positions->value = "";
            $Positions->whats = "";
            if ($this->getLCS($FirstLine[$i], 'Centro de Custo') >= 50 && empty($Positions->cdc)) {
                $Positions->value = '';
                $Positions->whats = 'cdc';
                $list[$i] = $Positions;
                $Positions->pos = $i;
                continue;
            } else if ($this->getLCS($FirstLine[$i], 'Usuário') >= 50 && empty($Positions->usu)) {
                $Positions->value = '';
                $Positions->whats = 'usu';
                $list[$i] = $Positions;
                $Positions->pos = $i;
                continue;
            } else if ($this->getLCS($FirstLine[$i], 'Patrimônio') >= 50 && empty($Positions->pat)) {
                $Positions->value = '';
                $Positions->whats = 'pat';
                $list[$i] = $Positions;
                $Positions->pos = $i;
                continue;
            }
            $EmpresaProduto = explode("\\", $FirstLine[$i]);
            $Empresa = $this->CheckCompany($EmpresaProduto[0]);
            $Produto = $this->CheckProduct($Empresa, $EmpresaProduto[1]);
            $Positions->value = [$Empresa, $Produto];
            $Positions->whats = $FirstLine[$i];
            $Positions->pos = $i;
            $list[$i] = $Positions;
        }
        return $list;
    }

    /**
     * This method uses the algorithm Longest common substring
     * font:https://en.wikibooks.org/wiki/Algorithm_Implementation/Strings/Longest_common_substring#PHP
     * @param $string_1 texto for comparation
     * @param $string_2 wish text
     * @return float Percentage of equality
     */
    private function getLCS($string_1, $string_2)
    {
        $string_1_length = strlen($string_1);
        $string_2_length = strlen($string_2);
        $return = '';
        if ($string_1_length === 0 || $string_2_length === 0) {
            // No similarities
            return $return;
        }
        $longest_common_subsequence = array();
        // Initialize the CSL array to assume there are no similarities
        $longest_common_subsequence = array_fill(0, $string_1_length, array_fill(0, $string_2_length, 0));
        $largest_size = 0;
        for ($i = 0; $i < $string_1_length; $i++) {
            for ($j = 0; $j < $string_2_length; $j++) {
                // Check every combination of characters
                if ($string_1[$i] === $string_2[$j]) {
                    // These are the same in both strings
                    if ($i === 0 || $j === 0) {
                        // It's the first character, so it's clearly only 1 character long
                        $longest_common_subsequence[$i][$j] = 1;
                    } else {
                        // It's one character longer than the string from the previous character
                        $longest_common_subsequence[$i][$j] = $longest_common_subsequence[$i - 1][$j - 1] + 1;
                    }

                    if ($longest_common_subsequence[$i][$j] > $largest_size) {
                        // Remember this as the largest
                        $largest_size = $longest_common_subsequence[$i][$j];
                        // Wipe any previous results
                        $return = '';
                        // And then fall through to remember this new value
                    }

                    if ($longest_common_subsequence[$i][$j] === $largest_size) {
                        // Remember the largest string(s)
                        $return = substr($string_1, $i - $largest_size + 1, $largest_size);
                    }
                }
                // Else, $CSL should be set to 0, which it was already initialized to
            }
        }
        return strlen($return) * 100 / $string_2_length;
    }

    private function CheckCompany($Company)
    {
        $Result = \App\Empresa::where('name', 'like', $Company . "%");
        if ($Result->count() == 1) {
            return $Result->get()[0];
        } else {
            $Empresa = new \App\Empresa();
            $Empresa->id;
            $Empresa->name = $Company;
            $Empresa->description = $Company;
            $Empresa->save();
            return $Empresa;
        }
    }

    private function CheckProduct($Company, $Product)
    {
        $Result = \App\Produto::where('empresa_id', '=', $Company->id)->where('model', 'like', "$Product%");
        if ($Result->count() >= 1) {
            return $Result->get()[0];
        } else {
            $Produto = new \App\Produto();
            $Produto->id;
            $Produto->model = $Product;
            $Produto->description = $Product;
            $Produto->empresa_id = $Company->id;
            $Produto->save();
            return $Produto;
        }
    }

    private function CkeckPatrimony($Company, $Item = null, $Patrimony)
    {
        $company = null;
        $patrimony = null;
        if (strlen($Patrimony) < 6) {
            $Patrimony = str_pad($Patrimony, 6, "0", STR_PAD_LEFT);
        }
        if ($this->getLCS($Company, "LYON") >= 51 || $this->getLCS($Company, "Lyon") >= 51) {
            $company = 1;
        } elseif ($this->getLCS($Company, "GT") >= 51) {
            $company = 3;
        }
        if (empty($Item)) {

            $patrimony = "COMP-" . $Patrimony;

        } else {
            $patrimony = $Item . "" . $Patrimony;
        }
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
            ->where('E670BEM.CODBEM', 'like', "$patrimony%")
            ->where('E670LOC.CODEMP', '=', $company)
            ->orderBy('E670BEM.CODEMP')
            ->orderBy('E670DRA.CODCCU')
            ->orderBy('E670BEM.CODBEM')
            ->limit(10)
            ->get();
        foreach ($query as $row) {
            $assoc = \App\Connect::where('data_out', '=', null)
                ->where('E670BEM_CODBEM', '=', $row->CODBEM)
                ->where('E070EMP_CODEMP', '=', $row->CODEMP);
            $row->DESCCU = iconv("ISO-8859-1", "UTF-8", $row->DESCCU);
            $row->DESBEM = iconv("ISO-8859-1", "UTF-8", $row->DESBEM);
            $row->NOMEMP = iconv("ISO-8859-1", "UTF-8", $row->NOMEMP);
            $row->DESESP = iconv("ISO-8859-1", "UTF-8", $row->DESESP);
            $row->ABRESP = iconv("ISO-8859-1", "UTF-8", $row->ABRESP);
            $data = new Carbon($row->DATAQI);
            $row->DATAQI = $data->format('d/m/Y');
            $row->EMPRST = \App\Emprestimo::where('E070EMP_CODEMP', '=', $row->CODEMP)->where('E670BEM_CODBEM', '=', $row->CODBEM)->where('data_entrada', '=', null)->count();
            $row->ASSOC = $assoc->count();
            if ($row->ASSOC) {
                $assoc = $assoc->get();
                $row->connect = DB::connection('vetorh')->table('R034FUN')
                    ->select(['NUMEMP', 'TIPCOL', 'NUMCAD as id', 'NOMFUN as value', 'DESSIT', 'SITAFA'])
                    ->join('R010SIT', function ($inner) {
                        $inner->on('R010SIT.CODSIT', '=', 'R034FUN.SITAFA');
                    })
                    ->where('NUMEMP', '=', $assoc[0]->R034FUN_NUMEMP)
                    ->where('TIPCOL', '=', $assoc[0]->R034FUN_TIPCOL)
                    ->where('NUMCAD', '=', $assoc[0]->R034FUN_NUMCAD)
                    ->get();
            } else {
                $row->connect = null;
            }
            $row->state = \App\Complement::
            select(['states.id', 'states.description', 'E070EMP_CODEMP', 'E670BEM_CODBEM', 'complements.created_at', 'complements.description as desc', 'states.state'])
                ->join('states', function ($join) {
                    $join->on('state_id', '=', 'states.id');
                })
                ->where('E670BEM_CODBEM', '=', $row->CODBEM)->where('E070EMP_CODEMP', '=', $row->CODEMP)->get();
            $row->keys = \App\BensKeys::select(["benskeys.id",
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
            array_push($retorno, $row);
        }
        return $retorno;
    }

    private function CheckUser($User)
    {
        $User = preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/",
                "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/",
                "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/")
            , explode(" ", "a A e E i I o O u U n N"), $User);
        $User = trim($User);
        $Result = DB::connection('vetorh')->table('R034FUN')
            ->select(['NUMEMP', 'TIPCOL', 'NUMCAD as id', 'NOMFUN as value', 'DESSIT', 'SITAFA'])
            ->join('R010SIT', function ($inner) {
                $inner->on('R010SIT.CODSIT', '=', 'R034FUN.SITAFA');
            })
            ->where('NOMFUN', 'like', "$User%")
            ->limit(1);
        if ($Result->count()) {
            return $Result->get()[0];
        }
        return null;
    }

    private function Connectkeys($Row, $PositionsFile, $PatrimonyInformation)
    {

        $Log = "";
        foreach ($Row as $key => $row) {
            $row = trim($row);
            if (empty($row) || $this->FindCellPositionForPosition($PositionsFile, $key)->whats == 'pat' || $this->FindCellPositionForPosition($PositionsFile, $key)->whats == 'usu' || $this->FindCellPositionForPosition($PositionsFile, $key)->whats == 'cdc') {
                continue;
            }
            $chave = $this->CheckKey($row, $this->FindCellPositionForPosition($PositionsFile, $key)->value[1], $this->FindCellPositionForPosition($PositionsFile, $key)->value[0]);
            if (!empty($chave) && !empty($PatrimonyInformation)) {
                $CheckBensKey = \App\BensKeys::where('E670BEM_CODBEM', '=', $PatrimonyInformation[0]->CODBEM)
                    ->where('E070EMP_CODEMP', '=', $PatrimonyInformation[0]->CODEMP)
                    ->where('key_id', '=', $chave->id);
                if ($CheckBensKey->count() == 0) {
                    $BensKey = new \App\BensKeys();
                    $BensKey->key_id = $chave->id;
                    $BensKey->E670BEM_CODBEM = $PatrimonyInformation[0]->CODBEM;
                    $BensKey->E070EMP_CODEMP = $PatrimonyInformation[0]->CODEMP;
                    $chave->in_use += 1;
                    $chave->save();
                    $BensKey->save();
                    $Log .= "Chave: " . $row . " item: " . $PatrimonyInformation[0]->CODBEM . "Emp :" . $PatrimonyInformation[0]->CODEMP . " (Associados)\n";
                } else {
                    $Log .= "Chave: " . $row . "  já associado com o item:".$PatrimonyInformation[0]->CODBEM . "Emp :" . $PatrimonyInformation[0]->CODEMP."\n";
                }

            }
        }
        return $Log;


    }

    private function CheckKey($key, $Product, $Company){

        $key = trim($key);
        if (empty($key)) {
            return null;
        }

        $result = \App\Key::select(['keys.key','keys.id'])->where('keys.key', '=', $key)
            ->join('Produtos', function ($inner) use ($Product) {
                $inner->where('Produtos.id', '=', $Product->id);
            })
            ->join('Empresas', function ($inner) {
                $inner->whereColumn('Empresas.id', '=', 'Produtos.empresa_id');
            })
            ->where('Empresas.id', '=', $Company->id);
        $VerifiyKey = \App\Key::where('keys.key', '=', $key);


        if ($result->count() && $VerifiyKey->count()) {
            $result = $result->get();
            $Newkey = \App\Key::find($result[0]->id);
            return $Newkey;
        }
        $Newkey = new \App\Key();
        $Newkey->key = $key;
        $Newkey->description = $key;
        $Newkey->produto_id = $Product->id;
        $Newkey->save();
        return $Newkey;
    }

    private function FindCellPosition($Positions, $Key)
    {
        foreach ($Positions as $position) {
            if ($position->whats == $Key) {
                return $position;
            }
        }
        return null;
    }

    private function FindCellPositionForPosition($Positions, $Position)
    {
        foreach ($Positions as $position) {
            if ($position->pos == $Position) {
                return $position;
            }
        }
        return null;
    }
}