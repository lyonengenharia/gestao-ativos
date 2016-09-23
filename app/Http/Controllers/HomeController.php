<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate ;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //if(Gate::denies('acesso'))
         //   abort(403,'Acesso negado');
        return view('dashboard.dashboard', ["breadcrumbs" => array("Home" => "home"), "page" => "Dashboard", "explanation" => " Estatística e visão geral"]);
    }
}
