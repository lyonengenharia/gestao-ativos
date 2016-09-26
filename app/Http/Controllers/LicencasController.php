<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;

use App\Http\Requests;

class LicencasController extends Controller
{
    public function index(){
        return view("licencas.licencas", ["breadcrumbs" => array("Licenças" => "licencas"),
            "page" => "Licenças",
            "explanation" => " Listagem de todas as licenças de software"]);
    }
    public function empresa(){
        return view("licencas.empresa", ["breadcrumbs" => array("Licenças" => "licencas","Empresa" => "empresa"),
            "page" => "Empresa",
            "explanation" => " Cadastro de empresa desenvolvedora de software"]);
    }
    public function empresainsert(Request $request){
        $rules = [
            'name' => 'required|unique:empresas|max:200',
            'description' => 'max:500',
        ];
        $messages = [
            'name.required' => "O nome da empresa é requerido",
            'name.unique' => "O nome da empresa já está cadastrado",
            'name.max' => "O tamanho máximo para esse campo é de 200 caracteres ",
            'description.max'=>"O tamanho máximo para esse campo é de 500 caracteres "

        ];
        $validator = \Validator::make($request->all(),$rules,$messages);
        if($validator->fails()){
            return redirect('licencas/empresa')
                ->withErrors($validator)
                ->withInput();
        }
        $empresa = new \App\Empresa();
        $empresa->name = $request->get('name');
        $empresa->description = $request->get('description');
        $empresa->save();
        return redirect('licencas/empresa')->with('status','Empresa '.$request->get('name').' incluida com sucesso!');
    }
    public function produto(){
        $empresas = \App\Empresa::get();
        return view("licencas.produto", ["breadcrumbs" => array("Licenças" => "licencas","Produto" => "licencas/produto"),
            "page" => "Produto",
            "explanation" => " Cadastro de softwares",
            "empresas"=>$empresas]);

    }
    public function produtoinsert(Request $request){
        $rules = [
            'model' => 'required|unique:produtos|max:200',
            'description' => 'max:500',
            'empresa'=>'required',
        ];
        $messages = [
            'model.required' => "O nome da empresa é requerido",
            'model.unique' => "O nome da empresa já está cadastrado",
            'model.max' => "O tamanho máximo para esse campo é de 200 caracteres ",
            'description.max'=>"O tamanho máximo para esse campo é de 500 caracteres ",
            'empresa.required'=>"A empresa é requerida."
        ];
        $validator = \Validator::make($request->all(),$rules,$messages);
        if($validator->fails()){
            return redirect('licencas/empresa')
                ->withErrors($validator)
                ->withInput();
        }
        $produto =  new \App\Produto();
        $produto->model = $request->get('model');
        $produto->description = $request->get('description');
        $produto->empresa_id = $request->get('empresa');
        $produto->save();
        return redirect('licencas/produto')->with('status','Produto '.$request->get('model').' incluido com sucesso!');

    }
    public function licenca(){
        $empresas = \App\Empresa::get();
        return view("licencas.licenca", ["breadcrumbs" => array("Licenças" => "licencas","Novo" => "licencas/licenca"),
            "page" => "Nova licença",
            "explanation" => " Cadastro de licenças softwares",
            "empresas"=>$empresas]);


    }
}
