<?php
/**
 * Created by PhpStorm.
 * User: wfs
 * Date: 18/11/2016
 * Time: 15:24
 */

namespace App\Facades;


use Illuminate\Support\Facades\Facade;

class Importer extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'processfile';
    }

}