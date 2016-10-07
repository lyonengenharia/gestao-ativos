<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class PermissionsController extends Controller
{
    public function index()
    {

        $Permissions = \App\Permission::paginate(15);
        return view('painel.permissions', [
            "breadcrumbs" => array("Painel Controle" => "painel", "Usuários" => "painel/usuarios", "Grupos" => "painel/usuarios/grupos", "Permissões" => "/permissoes"),
            "page" => "Grupos de acessos",
            "explanation" => " permissões",
            "Permissions" => $Permissions
        ]);
    }

    public function permission(Request $request, $id = null)
    {

        if (empty($id)) {
            return view('painel.permission', [
                "breadcrumbs" => array("Painel Controle" => "painel",
                    "Usuários" => "painel/usuarios",
                    "Grupos" => "painel/usuarios/grupos",
                    "Permissões" => "/permissoes",
                    "Novo" => "permission"
                ),
                "page" => "Grupos de acessos",
                "explanation" => " Nova permissão",
                "action" => "permission/insert"
            ]);
        } else {
            $Permission = \App\Permission::find($id);
            return view('painel.permission', [
                "breadcrumbs" => array("Painel Controle" => "painel",
                    "Usuários" => "painel/usuarios",
                    "Grupos" => "painel/usuarios/grupos",
                    "Permissões" => "/permissoes",
                    "Editar" => "#"
                ),
                "page" => "Grupos de acessos",
                "explanation" => " Edição de permissão",
                "action" => "update",
                "permission" => $Permission
            ]);
        }

    }

    public function permissionInsert(Request $request)
    {
        $rules = [
            'name' => 'unique:permissions,name|required',
            'description' => 'max:500',
        ];
        $messages = [
            'name.unique' => "Essa permissão já existe",
            'name.required' => "O nome é obrigatório",
            'description.max' => "O limite de 500 caracteres foi superado.",
        ];
        $validator = \Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect('permission')
                ->withErrors($validator)
                ->withInput();
        }
        $Permission = new \App\Permission();
        $Permission->name = $request->get('name');
        $Permission->label = $request->get('description');
        $Permission->save();
        return redirect('permission')->with('status', 'Permissão inserida com sucesso');
    }

    public function permissionUpdate(Request $request)
    {

        $rules = [
            'idpermission' => "exists:permissions,id",
            'name' => 'required',
            'description' => 'max:500',
        ];
        $messages = [
            'idpermission.exists' => "Essa permissão não existe no banco de dados!",
            'name.required' => "O nome é obrigatório",
            'description.max' => "O limite de 500 caracteres foi superado.",
        ];
        $validator = \Validator::make($request->all(), $rules, $messages);
        $Permission = \App\Permission::find($request->get('idpermission'));
        if ($Permission->name != $request->get('name')) {
            $validator->after(function ($validator) use ($request) {
                $checkName = \App\Permission::where('name', '=', $request->get('name'))->count();
                if ($checkName) {
                    $validator->errors()->add('name', 'O nome dessa permissão já existe!');
                }
            });
        }
        if ($validator->fails()) {
            return redirect('permission' . "/" . $request->get('idpermission'))
                ->withErrors($validator)
                ->withInput();
        }
        $Permission->name = $request->get('name');
        $Permission->label = $request->get('description');
        $Permission->save();
        return redirect('permissoes')->with('status', 'Permissão atualizada com sucesso!');
    }
}
