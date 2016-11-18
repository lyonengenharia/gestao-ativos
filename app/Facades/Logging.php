<?php
/**
 * Created by PhpStorm.
 * User: wfs
 * Date: 17/11/2016
 * Time: 11:25
 */

namespace App\Facades;


use Illuminate\Support\Facades\Facade;

class Logging extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'logcustom';
    }
}