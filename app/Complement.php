<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Complement extends Model
{
    protected $dates = ['updated_at','created_at'];
    public function State(){
        return $this->belongsTo(\App\State::class);
    }
}
