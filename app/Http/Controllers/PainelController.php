<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

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
}
