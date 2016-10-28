<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Key extends Model
{
    protected $table = "keys";
    protected $timestamp = true;
    protected $fillable = ['id','key','description','quantity','in_use','produto_id','created_at','updated_at'];
    protected $dates = ['maturity_date'];

    protected function produto(){
        return $this->belongsTo('App\Produto');
    }
}
