<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\App;

class PainelController extends Controller
{
    public function index(){
        $users = \App\User::count();
        return view('painel.painel',[
            "breadcrumbs" => array("Painel Controle" => "painel"),
            "page" => "Painel controle",
            "explanation" => " Configurações globais do sistema",
            "users"=>$users
            ]);
    }
    public function termos(){
        $termos = \App\tipotermo::all();
        return view('painel.termos',[
            "breadcrumbs" => array("Painel Controle" => "painel","Termos"=>""),
            "page" => "Termos",
            "explanation" => " Listagem de todos os termos disponíveis",
            "termos"=>$termos
        ]);
    }
    public function termosNovo(Request $request){
        $rules = [
            'name' => 'required|unique:tipotermos|max:200',
            'description' => 'required|max:500'
        ];
        $messages = [
            'name.required' => "O nome do termo é requerido",
            'name.unique' => "O nome do termo já está cadastrado",
            'name.max' => "O tamanho máximo para esse campo é de 200 caracteres ",
            'description.max' => "O tamanho máximo para descrição é de 500 caracteres ",
            'description.required' => "A descrição é requerida"

        ];
        $validator = \Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(['error'=>1,"msg"=>"","data"=>$validator->errors()]);
        }
        $tipoTermo = new \App\tipotermo();
        $tipoTermo->name = $request->get('name');
        $tipoTermo->description = $request->get('description');
        $tipoTermo->save();
        return response()->json(['error'=>0,"msg"=>"O termo foi adicionado.","data"=>""]);
    }
    public function termosAtualizacao(Request $request){
        $rules = [
            'name' => 'required|max:200',
            'description' => 'required|max:500'
        ];
        $messages = [
            'name.required' => "O nome do termo é requerido",
            'name.max' => "O tamanho máximo para esse campo é de 200 caracteres ",
            'description.max' => "O tamanho máximo para descrição é de 500 caracteres ",
            'description.required' => "A descrição é requerida"

        ];
        $validator = \Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(['error'=>1,"msg"=>"","data"=>$validator->errors()]);
        }
         $tipoTermo = \App\tipotermo::find($request->get('id'));
        $tipoTermo->name = $request->get("name");
        $tipoTermo->description = $request->get("description");
        $tipoTermo->update();
        return response()->json(['error'=>0,"msg"=>"O termo foi atualizado ","data"=>""]);
    }
    public function termosDelete($id){
        $tipoTermo = \App\tipotermo::find($id);
        $tipoTermo->delete();
        return response()->json(['error'=>0,"msg"=>"O termo foi apagado ","data"=>""]);
    }
}
