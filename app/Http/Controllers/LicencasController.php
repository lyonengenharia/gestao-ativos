<?php

namespace App\Http\Controllers;

use App\Produto;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Http\Requests;

class LicencasController extends Controller
{
    public function index()
    {
        $licencas = DB::table('keys')
            ->select(['keys.id as keyid', 'key', 'quantity', 'in_use', 'keys.description', 'produtos.id', 'produtos.model', 'empresas.name'])
            ->join('produtos', function ($inner) {
                $inner->on('produtos.id', '=', 'keys.produto_id');
            })
            ->join('empresas', function ($inner) {
                $inner->on('empresas.id', '=', 'produtos.empresa_id');
            })
            ->paginate(15);
        return view("licencas.licencas", ["breadcrumbs" => array("Licenças" => "licencas"),
            "page" => "Licenças",
            "explanation" => " Listagem de todas as licenças de software",
            "licencas" => $licencas]);
    }

    public function empresa()
    {
        return view("licencas.empresa", ["breadcrumbs" => array("Licenças" => "licencas", "Empresa" => "empresa"),
            "page" => "Empresa",
            "explanation" => " Cadastro de empresa desenvolvedora de software"]);
    }

    public function empresainsert(Request $request)
    {
        $rules = [
            'name' => 'required|unique:empresas|max:200',
            'description' => 'max:500',
        ];
        $messages = [
            'name.required' => "O nome da empresa é requerido",
            'name.unique' => "O nome da empresa já está cadastrado",
            'name.max' => "O tamanho máximo para esse campo é de 200 caracteres ",
            'description.max' => "O tamanho máximo para esse campo é de 500 caracteres "

        ];
        $validator = \Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect('licencas/empresa')
                ->withErrors($validator)
                ->withInput();
        }
        $empresa = new \App\Empresa();
        $empresa->name = $request->get('name');
        $empresa->description = $request->get('description');
        $empresa->save();
        return redirect('licencas/empresa')->with('status', 'Empresa ' . $request->get('name') . ' incluida com sucesso!');
    }

    public function produto()
    {
        $empresas = \App\Empresa::get();
        return view("licencas.produto", ["breadcrumbs" => array("Licenças" => "licencas", "Produto" => "licencas/produto"),
            "page" => "Produto",
            "explanation" => " Cadastro de softwares",
            "empresas" => $empresas]);

    }

    public function produtoinsert(Request $request)
    {
        $rules = [
            'model' => 'required|unique:produtos|max:200',
            'description' => 'max:500',
            'empresa' => 'required',
        ];
        $messages = [
            'model.required' => "O nome da empresa é requerido",
            'model.unique' => "O nome da empresa já está cadastrado",
            'model.max' => "O tamanho máximo para esse campo é de 200 caracteres ",
            'description.max' => "O tamanho máximo para esse campo é de 500 caracteres ",
            'empresa.required' => "A empresa é requerida."
        ];
        $validator = \Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect('licencas/empresa')
                ->withErrors($validator)
                ->withInput();
        }
        $produto = new \App\Produto();
        $produto->model = $request->get('model');
        $produto->description = $request->get('description');
        $produto->empresa_id = $request->get('empresa');
        $produto->save();
        return redirect('licencas/produto')->with('status', 'Produto ' . $request->get('model') . ' incluido com sucesso!');

    }

    public function licenca()
    {
        $empresas = \App\Empresa::get();
        return view("licencas.licenca", ["breadcrumbs" => array("Licenças" => "licencas", "Novo" => "licencas/licenca"),
            "page" => "Nova licença",
            "explanation" => " Cadastro de licenças softwares",
            "empresas" => $empresas
        ]);

    }

    public function licencainsert(Request $request)
    {
        $rules = [
            'produto_id' => 'required|exists:produtos,id',
            'key' => 'required|unique:keys',
            'quantity' => 'required|min:1',
        ];
        $messages = [
            'produto_id.required' => "O campo é obrigatório",
            'produto_id.exists' => "O produto não existe no banco de dados",
            'key.required' => "O campo é obrigatório",
            'key.unique' => "A chave já está cadastrada.",
            'quantity.required' => "O campo é obrigatório.",
            'quantity.min' => "O valor mínimo é 1.",
        ];
        $validator = \Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect('licencas/licenca')
                ->withErrors($validator)
                ->withInput();
        }
        $data = null;
        if ($request->has('datavencimento')) {
            $data = \Carbon\Carbon::createFromFormat("d/m/Y", $request->get('datavencimento'), "America/Sao_Paulo");
        }
        $Key = new \App\Key();
        $Key->key = $request->get('key');
        $Key->description = $request->get('description');
        $Key->quantity = $request->get('quantity');
        $Key->produto_id = $request->get('produto_id');
        $Key->maturity_date= $data->toDateTimeString();
        $Key->save();
        return redirect('licencas/licenca')->with('status', 'Licença incluida com sucesso!');

    }

    public function licencaget($id)
    {
        $key = \App\Key::find($id);
        $produtos = \App\Produto::find($key->produto_id);
        $empresas = \App\Empresa::all();
        return view("licencas.licencaget", ["breadcrumbs" => array("Licenças" => "licencas", "Edição" => "licencas/licenca"),
            "page" => "Edição de licença",
            "explanation" => "Edição de licenças softwares",
            "key" => $key,
            "empresas" => $empresas,
            "produtos" => $produtos
        ]);
    }

    public function licencaupdate(Request $request)
    {
        $rules = [
            'produto_id' => 'required|exists:produtos,id',
            'keyid' => 'required|exists:keys,id',
            'quantity' => 'required|min:1',
        ];
        $messages = [
            'produto_id.required' => "O campo é obrigatório",
            'produto_id.exists' => "O produto não existe no banco de dados",
            'key.required' => "O campo é obrigatório",
            'key.exists' => "O registro da chave não existe no banco de dados",
            'quantity.required' => "O campo é obrigatório.",
            'quantity.min' => "O valor mínimo é 1.",
        ];
        $validator = \Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect('licencas/licenca/update')
                ->withErrors($validator)
                ->withInput();
        }
        $key = \App\Key::find($request->get('keyid'));
        $key->key = $request->get('key');
        $key->description = $request->get('description');
        $key->quantity = $request->get('quantity');
        $key->produto_id = $request->get('produto_id');
        $key->save();
        return redirect('licencas')->with('status', 'Chave atualizada');

    }

    public function associar(Request $request)
    {
        $rules = [
            'pat' => 'required',
            'emp' => 'required',
            'key' => 'required',
        ];
        $messages = [
            'pat.required' => "O campo é obrigatório",
            'emp.required' => "O campo é obrigatório",

        ];
        $validator = \Validator::make($request->all(), $rules, $messages);
        if ($request->ajax()) {
            if ($validator->fails()) {
                return response()->json(['erro' => 0]);
            }
            //Verificar se existe um chave identica associada.

            if (!$request->has('conf')) {
                $checkKey = DB::table('keys')->where('id', '=', $request->get('key'))->get();
                $existekey = DB::table('benskeys')
                    ->select(
                        [
                            'produtos.id', 'produtos.model', 'produtos.description',
                            'produtos.empresa_id', 'produtos.created_at', 'produtos.updated_at'
                        ]
                    )->join('keys', function ($inner) {
                        $inner->on('keys.ID', '=', 'benskeys.key_id');
                    })->join('produtos', function ($inner) {
                        $inner->on('produtos.id', '=', 'keys.produto_id');
                    })->where('E670BEM_CODBEM', '=', $request->get('pat'))
                    ->where('E070EMP_CODEMP', '=', $request->get('emp'));
                foreach ($existekey->get() as $keys) {
                    if ($keys->id == $checkKey[0]->produto_id) {
                        return response()->json(['erro' => 2, 'msg' => 'Já existe uma licença desse tipo de software associado, desse continuar assim mesmo?']);
                    }
                }

            }

            //Associate the key
            $key = \App\Key::find($request->get('key'));
            $key->in_use += 1;
            $key->save();
            $BensKey = new \App\BensKeys();
            $BensKey->key_id = $request->get('key');
            $BensKey->E670BEM_CODBEM = $request->get('pat');
            $BensKey->E070EMP_CODEMP = $request->get('emp');
            $BensKey->save();
            return response()->json(['erro' => 1, 'msg' => 'Chave associada!']);
        }

    }

    public function produtodelete(Request $request)
    {
        $table = DB::table('benskeys')->where('key_id', '=', $request->get('key'))->where('E670BEM_CODBEM', '=', $request->get('pat'))->delete();
        $key = \App\Key::find($request->get('key'));
        $key->in_use =  $key->in_use - $table;
        $key->save();
        return $table;
    }

}
