<?php
/**
 * Created by PhpStorm.
 * User: wfs
 * Date: 24/11/2016
 * Time: 11:24
 */

namespace App\Pojo;


use App\Facades\Ldap;

class Employed
{
    public $NUMEMP;
    public $TIPCOL;
    public $VALKEY;
    public $NUMCAD;
    public $EMACOM;
    public $EMAPAR;
    public $NOMFUN;
    public $DESSIT;
    public $CODCCU;
    public $NOMCCU;
    public $DATADM;
    public $DATAFA;
    public $SITAFA;
    public $NUMCPF;

    public function __construct($NUMEMP = null, $TIPCOL = null, $NUMCAD = null)
    {
        $this->NUMEMP = empty($NUMEMP) ? null : $NUMEMP;
        $this->TIPCOL = empty($TIPCOL) ? null : $TIPCOL;
        $this->NUMCAD = empty($NUMCAD) ? null : $NUMCAD;
        if (!empty($NUMEMP) && !empty($TIPCOL) && !empty($NUMCAD)) {
            $this->get();
        }
        if (empty(trim($this->EMACOM))) {
            $this->MakeMail();
        }


    }
    public function get()
    {
        $Employed = \Illuminate\Support\Facades\DB::connection('vetorh')
            ->table('R034FUN')
            ->select([
                'R034FUN.NUMEMP', 'R034FUN.TIPCOL', 'R998LSF.VALKEY',
                'R034FUN.NUMCAD', 'R034CPL.EMACOM', 'R034CPL.EMAPAR',
                'R034FUN.NOMFUN', 'R010SIT.DESSIT', 'R034FUN.CODCCU',
                'R018CCU.NOMCCU', 'R034FUN.DATADM', 'R034FUN.DATAFA',
                'R034FUN.SITAFA', 'R034FUN.NUMCPF'
            ])
            ->join('R998LSF', function ($inner) {
                $inner->on('R998LSF.KEYNAM', '=', 'R034FUN.TIPCOL')
                    ->where('R998LSF.LSTNAM', '=', 'LTipCol');
            })
            ->join('R018CCU', function ($inner) {
                $inner->on('R018CCU.NUMEMP', '=', 'R034FUN.NUMEMP')
                    ->whereColumn('R018CCU.CODCCU', '=', 'R034FUN.CODCCU');
            })
            ->join('R010SIT', function ($inner) {
                $inner->on('R010SIT.CODSIT', '=', 'R034FUN.SITAFA');
            })
            ->join('R034CPL', function ($inner) {
                $inner->on('R034FUN.NUMEMP', '=', 'R034CPL.NUMEMP')
                    ->whereColumn('R034FUN.TIPCOL', '=', 'R034CPL.TIPCOL')
                    ->whereColumn('R034FUN.NUMCAD', '=', 'R034CPL.NUMCAD');
            })
            ->where('R034CPL.NUMCAD', '=', $this->NUMCAD)
            ->where('R034FUN.NUMEMP', '=', $this->NUMEMP)
            ->where('R034FUN.TIPCOL', '=', $this->TIPCOL);
        if ($Employed->count() > 0) {
            $Employed = $Employed->get();
            $this->NUMEMP = $Employed[0]->NUMEMP;
            $this->TIPCOL = $Employed[0]->TIPCOL;
            $this->VALKEY = iconv('windows-1252', 'utf-8', $Employed[0]->VALKEY);
            $this->NUMCAD = $Employed[0]->NUMCAD;
            $this->EMACOM = $Employed[0]->EMACOM;
            $this->EMAPAR = $Employed[0]->EMAPAR;
            $this->NOMFUN = iconv('windows-1252', 'utf-8', $Employed[0]->NOMFUN);
            $this->DESSIT = $Employed[0]->DESSIT;
            $this->CODCCU = $Employed[0]->CODCCU;
            $this->NOMCCU = iconv('windows-1252', 'utf-8', $Employed[0]->NOMCCU);
            $this->DATADM = $Employed[0]->DATADM;
            $this->DATAFA = $Employed[0]->DATAFA;
            $this->SITAFA = $Employed[0]->SITAFA;
            $this->NUMCPF = $Employed[0]->NUMCPF;
        }
        return $this;
    }
    private function MakeMail()
    {
        $firtName = null;
        $lastName = null;
        $auxName = explode(" ", $this->tirarAcentos($this->NOMFUN));
        $firtName = strtolower($auxName[0]);
        $lastName = strtolower($auxName[count($auxName) - 1]);
        $domain = null;
        if (\App\Facades\Importer::getLCS($this->NOMCCU, 'Facilities') > 55) {
            $domain = 'facilities';
        } else {
            $domain = 'lyonengenharia';
        }
        $ProvEmail = $firtName . "." . $lastName . "@" . $domain . ".com.br";
        $search = Ldap::search($ProvEmail);
        if($search['count']>0){
            if($search[0]['cn'][0]!= $this->NOMFUN){

                $this->EMACOM = "Favor verificar esse e-mail";
            }else{
                $this->EMACOM = null;
            }
        }else{
            $this->EMACOM = $ProvEmail;

        }

    }
    private function tirarAcentos($string){
        return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$string);
    }
}