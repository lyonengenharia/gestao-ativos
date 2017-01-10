<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    public function AllProductOfCompany(){
        return $this->hasMany(\App\Produto::class);
    }
}
