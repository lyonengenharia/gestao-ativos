<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Complement extends Model
{
    public function State(){
        return $this->belongsTo(\App\State::class);
    }
}
