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
            $lines = file(storage_path('app/import/data/').$request->get('data'));
            echo "<pre>";
            $this->LoadHeadCVS($this->SliptLine($lines[0]));
            foreach ($lines as $Keys=>$line):
                if($Keys==0){
                    continue;
                }
                print_r($this->SliptLine($line));
                exit();
            endforeach;
        }
        return 'não existe';
    }
    public function Delete(Request $request){
        if(Storage::exists('import/data/' . $request->get('data'))) {
            Storage::delete('import/data/' . $request->get('data'));
            return json_encode(["error"=>0,"msg"=>"O arquivo apagado."]);
        }
        return json_encode(["error"=>1,"msg"=>"O arquivo não existe, ou já foi apagado."]);
    }
    private function SliptLine($Line){
        $explodes = explode(';',$Line);
        foreach ($explodes as $key=>$value){
            $explodes[$key] = iconv("ISO-8859-1", "UTF-8", $explodes[$key]);
        }
        return $explodes;
    }
    private function LoadHeadCVS($FirstLine){
        print_r($FirstLine);
        $Posicoes =new \stdClass;
        for ($i=0;$i<count($FirstLine);$i++){
            if($this->getLCS($FirstLine[$i],'Centro de Custo') >=50 && empty($Posicoes->cdc)){
                  ($Posicoes->cdc = ["pos"=>$i,'value'=>''] );
                    continue;

            }else if($this->getLCS($FirstLine[$i],'Usuário') >=50 && empty($Posicoes->usu)){
                ($Posicoes->usu = ["pos"=>$i,'value'=>'']);
                continue;

            }else if($this->getLCS($FirstLine[$i],'Patrimônio') >=50 && empty($Posicoes->pat)){
                ($Posicoes->pat = ["pos"=>$i,'value'=>'']);
                continue;
            }
            $Produdo = explode("\\",$FirstLine[$i]);
            //Vefificar se empresa existe
            $Empresa = \App\Empresa::where('name','like',$Produdo[0]."%")->count();
            echo ($Empresa==1?'Econtrada':'não encontrada')."<br>";

        }
        echo "<br>";
        print_r($Posicoes);
        exit();



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
        $return          = '';
        if ($string_1_length === 0 || $string_2_length === 0)
        {
            // No similarities
            return $return;
        }
        $longest_common_subsequence = array();
        // Initialize the CSL array to assume there are no similarities
        $longest_common_subsequence = array_fill(0, $string_1_length, array_fill(0, $string_2_length, 0));
        $largest_size = 0;
        for ($i = 0; $i < $string_1_length; $i++)
        {
            for ($j = 0; $j < $string_2_length; $j++)
            {
                // Check every combination of characters
                if ($string_1[$i] === $string_2[$j])
                {
                    // These are the same in both strings
                    if ($i === 0 || $j === 0)
                    {
                        // It's the first character, so it's clearly only 1 character long
                        $longest_common_subsequence[$i][$j] = 1;
                    }
                    else
                    {
                        // It's one character longer than the string from the previous character
                        $longest_common_subsequence[$i][$j] = $longest_common_subsequence[$i - 1][$j - 1] + 1;
                    }

                    if ($longest_common_subsequence[$i][$j] > $largest_size)
                    {
                        // Remember this as the largest
                        $largest_size = $longest_common_subsequence[$i][$j];
                        // Wipe any previous results
                        $return       = '';
                        // And then fall through to remember this new value
                    }

                    if ($longest_common_subsequence[$i][$j] === $largest_size)
                    {
                        // Remember the largest string(s)
                        $return = substr($string_1, $i - $largest_size + 1, $largest_size);
                    }
                }
                // Else, $CSL should be set to 0, which it was already initialized to
            }
        }
        return strlen($return)*100/$string_2_length;
    }
}
