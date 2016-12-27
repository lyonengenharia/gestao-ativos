<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Termo extends Model
{
    public function getTipoTermo(){
        return $this->belongsTo('App\tipotermo','tipotermo_id');
    }
}
