<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Connect extends Model
{
    protected $fillable = ['id','E670BEM_CODBEM','E070EMP_CODEMP','R034FUN_NUMEMP','R034FUN_TIPCOL','R034FUN_NUMCAD','obs_out','obs_in','data_in','data_out'];

    public function Termos (){
        return $this->belongsToMany('App\Termo');
    }
}
