<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Termo extends Model
{
    public function getTipoTermo(){
        return $this->belongsTo('App\tipotermo','tipotermo_id');
    }
    public function getConnect(){
        return $this->belongsToMany('App\Connect');
    }
    public function getNotification(){
        return $this->hasMany('App\NotificationTermos');
    }
}
