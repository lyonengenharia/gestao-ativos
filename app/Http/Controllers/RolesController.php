<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class RolesController extends Controller
{
    public function index(){

        $roles = \App\Role::paginate(15);
        return view('painel.roles',[
            "breadcrumbs" => array("Painel Controle" => "painel","Usuários" => "painel/usuarios","Grupos" => "painel/usuarios/grupos"),
            "page" => "Grupos de acessos",
            "explanation" => " Gestão de grupos de acesso",
            "roles"=>$roles
        ]);
    }
}
