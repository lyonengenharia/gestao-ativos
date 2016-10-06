<?php

namespace App\Http\Controllers;

use Adldap\Laravel\Facades\Adldap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;

class UserController extends Controller
{
    /**
     * @var Adldap
     */
    protected $adldap;

    /**
     * Constructor.
     *
     * @param AdldapInterface $adldap
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Displays the all LDAP users.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $users = \App\User::paginate(15);
        return view('painel.usuarios', [
            "breadcrumbs" => array("Painel Controle" => "painel", "Usuários" => 'painel/usuarios'),
            "page" => "Gestão de usuários",
            "explanation" => " Gestão de Usuários",
            "users" => $users
        ]);
    }

    public function edit($id)
    {
        $user = \App\User::find($id);
        $roles = \App\Role::all();
        return view('users.edit', [
            "breadcrumbs" => array("Painel Controle" => "painel", "Usuários" => "painel/usuarios", "Editar" => "#"),
            "page" => "Gestão de usuários",
            "explanation" => " edição de usuário",
            "user" => $user,
            "roles" => $roles
        ]);
    }

    public function update(Request $request)
    {
        $user = \App\User::find($request->get('usuario'));
        DB::table('role_user')->where('user_id', '=', $request->get('usuario'))->delete();
        if (count($request->get('permissoes')) > 0) {
            foreach ($request->get('permissoes') as $permission) {
                $RoleUser = new \App\RoleUser();
                $RoleUser->user_id = $request->get('usuario');
                $RoleUser->role_id = $permission;
                $RoleUser->save();
            }
        }
        return response()->json(['erro' => 0, 'msg' => 'Permissões atualizadas com sucesso!!']);

    }
}
