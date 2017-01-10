<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class PermissionAcess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->ajax()){
            if(Gate::denies('acesso')){
                Auth::logout("Obrigado");
                return response()->json(['erro'=>1,'msg'=>'Acesso negado']);
            }
        }
        if(Gate::denies('acesso')){
            Auth::logout("Obrigado");
            abort(403,'Acesso negado');
        }
        return $next($request);
        /*$actions = $request->route()->getAction();
        $roles = isset($actions['roles']) ? $actions['roles'] : null;
        dd($request->user()->hasAnyRoles($roles));

        foreach ($request->user()->roles as $role) {
            //Administrator of system
            if ($role->name == 'admin') {
                return $next($request);
            }
            foreach ($role->permissions as $permission) {
                foreach ($roles as $row) {
                    echo $row . "=" . $permission->name . "<br>";
                    if ($row == $permission->name) {
                        //
                    }
                }

            }

        }
        abort(403, 'Acesso negado');
*/

    }
}
