<?php
/**
 * Created by PhpStorm.
 * User: wfs
 * Date: 17/11/2016
 * Time: 11:16
 */

namespace App\Common;

use Illuminate\Support\Facades\Storage;



class LogCustom
{
    public function CreateFile($File){
        Storage::disk('public')->put($File.'.log', "");
    }
    public function PreEnd($File,$Message){
        Storage::disk('public')->prepend($File.'.log', $Message);
    }
    public function AppEnd($File,$Message){
        Storage::disk('public')->append($File.'.log', $Message);
    }
    public function Create($File){
        Storage::disk('public')->put($File, "");
    }
    public function AppEndFile($File,$Message){
        Storage::disk('public')->append($File, $Message);
    }
}