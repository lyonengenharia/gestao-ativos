<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $fillable=["id","model","description","empresa_id"];
    protected $table = "produtos";
    public function myCompany(){
        return $this->belongsTo('\App\Empresa','empresa_id','id');
    }
}
