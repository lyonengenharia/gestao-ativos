<?php

namespace App\Http\Controllers;



use App\Http\Requests;
use App\Facades\Importer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


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
        return Importer::Import($request);
    }
    public function Process(Request $request)
    {
        dispatch(new \App\Jobs\ImportData($request->get('data'),Auth::user()));

        //Importer::Process($request->get('data'),Auth::user());
        return response()->json(['error'=>0,'msg'=>'O Arquivo será processado em breve.']);
    }

    public function Delete(Request $request)
    {
        return Importer::Delete($request);
    }


}
