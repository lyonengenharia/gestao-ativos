<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Emprestimo extends Model
{
    protected $dates =['create_at','data_saida','data_entrada','update_at'];
}
