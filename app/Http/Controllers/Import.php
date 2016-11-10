<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Storage;


class Import extends Controller
{
    /**
     * @return string
     */
    public function index()
    {
        return view('import.index', [
                "breadcrumbs" => ["Painel Controle" => "painel", "Importação" => "#"],
                "page" => "Importação",
                "explanation" => ""
            ]
        );
    }

    public function dados()
    {
        $Files = \Storage::allFiles('import/data');
        return view('import.import', [
                "breadcrumbs" => ["Painel Controle" => "painel", "Importação" => "/painel/importacao", "Dados" => "#"],
                "page" => "Importação",
                "explanation" => "Dados",
                "files" => $Files
            ]
        );
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

    /**
     * @param Request $request
     * @return string3
     */
    public function Process(Request $request)
    {
        if (Storage::exists('import/data/' . $request->get('data'))) {
            $lines = file(storage_path('app/import/data/') . $request->get('data'));
            $PositionsFile = $this->LoadHeadCVS($this->SliptLine($lines[0]));
            $PatrimonyPosition = null;
            //dd($PositionsFile);
            foreach ($lines as $Keys => $line):
                if ($Keys == 0) {
                    continue;
                }
                $row = $this->SliptLine($line);
                //Verify where is location at cell patrimony
                $PatrimonyPosition = $this->FindCellPosition($PositionsFile,'pat');
                $PatrimonyInformation = $this->CkeckPatrimony($row[$PatrimonyPosition->pos]);
                //echo $this->getLCS("NÃO TEM PATRIMÔNIO","GT");


                if(preg_match('/^([a-zA-Z]+)?.([a-zA-Z]+.)?([0-9]+.?[0-9]+)/',$row[$PatrimonyPosition->pos],$match)){
                    echo $row[$PatrimonyPosition->pos] . " - " .$match[1]."--" . $match[2]."---" . $match[3]."<br>";
                }else{
                    echo $row[$PatrimonyPosition->pos] . " -  Não localizado<br>";
                }
            endforeach;
        }
        return 'não existe';
    }

    public function Delete(Request $request)
    {
        if (Storage::exists('import/data/' . $request->get('data'))) {
            Storage::delete('import/data/' . $request->get('data'));
            return json_encode(["error" => 0, "msg" => "O arquivo apagado."]);
        }
        return json_encode(["error" => 1, "msg" => "O arquivo não existe, ou já foi apagado."]);
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
     * Load the positions of cvs
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
        if ($Result->count() == 1) {
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
    private function CkeckPatrimony($Patrimony){
        $company = "";
        if($this->getLCS($Patrimony,"LYON")>=51 || $this->getLCS($Patrimony,"Lyon")>=51){
            $company = 1;
        }elseif ($this->getLCS($Patrimony,"GT")>=51){
            $company = 2;
        }
        return $company;

    }
    private function FindCellPosition($Positions,$Key){
        foreach ($Positions as $position){
            return $position->whats = $Key?$position:null;
        }
    }
}
