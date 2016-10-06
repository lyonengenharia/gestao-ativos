<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class PermissionsController extends Controller
{
    public function index(){

        $Permissions = \App\Permission::paginate(15);
        return view('painel.permissions',[
            "breadcrumbs" => array("Painel Controle" => "painel","Usuários" => "painel/usuarios","Grupos" => "painel/usuarios/grupos","Permissões"=>"/permissoes"),
            "page" => "Grupos de acessos",
            "explanation" => " permissões",
            "Permissions"=>$Permissions
        ]);
    }
}
