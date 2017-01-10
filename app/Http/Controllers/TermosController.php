<?php

namespace App\Http\Controllers;

use App\Pojo\Message;
use App\Pojo\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class termosController extends Controller
{
    /**
     * @param $id id of termo
     * @return \Illuminate\Http\RedirectResponse
     */
    public function direction($id){
        $termo = \App\Termo::find($id);
        if(empty($termo)){
            abort(412,'Termo não encontrado');
        }
        if(empty($termo->getConnect()->count())){
            abort(412,'Termo não está associado');
        }
        if(empty($termo->getTipoTermo()->get()[0]->controller)){
            abort(412,'Tipo de termo sem controller, favor notificar a TI.');
        }
        return redirect()->action($termo->getTipoTermo()->get()[0]->controller, ['id'=>$id]);

    }
    public function supply($id){
        $termo = \App\Termo::find($id);
        if(empty($termo)){
            abort(412,'Termo não encontrado');
        }
        if(empty($termo->getConnect()->count())){
            abort(412,'Termo não está associado');
        }
        /*if($termo->tipotermo_id !=1){
            abort(412,'Tipo de termo incorreto, o termo solicitado é de "'.$termo->getTipoTermo()->get()[0]->name.'"');
        }*/
        $employed = new \App\Pojo\Employed($termo->getConnect()->get()[0]->R034FUN_NUMEMP,$termo->getConnect()->get()[0]->R034FUN_TIPCOL,$termo->getConnect()->get()[0]->R034FUN_NUMCAD);
        $bem = new \App\Pojo\Bem($termo->getConnect()->get()[0]->E670BEM_CODBEM,$termo->getConnect()->get()[0]->E070EMP_CODEMP);
        return View('termos.supply',[
            "termo"=>$termo,
            "employed"=>$employed,
            "bem"=>$bem
        ]);
    }
    public function devolution($id){
        $termo = \App\Termo::find($id);
        if(empty($termo)){
            abort(412,'Termo não encontrado');
        }
        if(empty($termo->getConnect()->count())){
            abort(412,'Termo não está associado');
        }
        /*if($termo->tipotermo_id !=2){
            abort(412,'Tipo de termo incorreto, o termo solicitado é de "'.$termo->getTipoTermo()->get()[0]->name.'"');
        }*/
        $employed = new \App\Pojo\Employed($termo->getConnect()->get()[0]->R034FUN_NUMEMP,$termo->getConnect()->get()[0]->R034FUN_TIPCOL,$termo->getConnect()->get()[0]->R034FUN_NUMCAD);
        $bem = new \App\Pojo\Bem($termo->getConnect()->get()[0]->E670BEM_CODBEM,$termo->getConnect()->get()[0]->E070EMP_CODEMP);
        return View('termos.devolution',[
            "termo"=>$termo,
            "employed"=>$employed,
            "bem"=>$bem
        ]);
    }

    public function download($id){
        if (Storage::exists('public/termos/' . $id . '.pdf')) {
            return response()->file('../storage/app/public/termos/'. $id . '.pdf');
        }
        abort(412,'Termo não encontrado');

    }
    public function import(Request $request){
        if(!$request->has('id-termo')){
            return (array) new Response(1,null,'Requisitos não atendidos para essa requisição');
        }
        $termo = \App\Termo::find($request->get('id-termo'));
        if(empty($termo)){
            return (array) new Response(1,null,'Termo não encontrado');
        }
        //return $termo;
        $file = $request->file('file');
        //return $files;
       if (!empty($file)) {
                if ($file->extension() == 'pdf') {
                    $termo->signtermo = 1;
                    $termo->pathsigntermo = $termo->id."-".$termo->tipotermo_id.".pdf";
                    $termo->save();
                    if (!Storage::exists('public/termos/' . $termo->id."-".$termo->tipotermo_id.".pdf")) {
                        Storage::put('public/termos/'. $termo->id."-".$termo->tipotermo_id.".pdf", file_get_contents($file));
                    } else {
                        Storage::put('public/termos/'. $termo->id."-".$termo->tipotermo_id.".pdf", file_get_contents($file));
                        return (array) new Response(1,null,'Arquivo foi substituido');
                    }

                } else {
                    return (array) new Response(1,null,'Tipo de arquivo não permitido!');
                }
           return (array) new Response(0,null,'Upload realizado com sucesso');
        }
        return (array) new Response(1,null,'Problemas para fazer o upload, tente novamente mais tarde.');
    }
}
