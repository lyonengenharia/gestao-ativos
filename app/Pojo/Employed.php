<?php
/**
 * Created by PhpStorm.
 * User: wfs
 * Date: 24/11/2016
 * Time: 11:24
 */

namespace App\Pojo;


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
    public function __construct($NUMEMP = null,$TIPCOL=null,$NUMCAD=null)
    {
        $this->NUMEMP = empty($NUMEMP)?null:$NUMEMP;
        $this->TIPCOL = empty($TIPCOL)?null:$TIPCOL;
        $this->NUMCAD = empty($NUMCAD)?null:$NUMCAD;
        if(!empty($NUMEMP) && !empty($TIPCOL) && !empty($NUMCAD)){
            $this->get();
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
                'R034FUN.SITAFA'
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
            if($Employed->count()>0){
                $Employed = $Employed->get();
                $this->NUMEMP = $Employed[0]->NUMEMP;
                $this->TIPCOL = $Employed[0]->TIPCOL;
                $this->VALKEY = iconv('windows-1252','utf-8',$Employed[0]->VALKEY);
                $this->NUMCAD = $Employed[0]->NUMCAD;
                $this->EMACOM = $Employed[0]->EMACOM;
                $this->EMAPAR = $Employed[0]->EMAPAR;
                $this->NOMFUN = iconv('windows-1252','utf-8',$Employed[0]->NOMFUN);
                $this->DESSIT = $Employed[0]->DESSIT;
                $this->CODCCU = $Employed[0]->CODCCU;
                $this->NOMCCU = iconv('windows-1252','utf-8',$Employed[0]->NOMCCU);
                $this->DATADM = $Employed[0]->DATADM;
                $this->DATAFA = $Employed[0]->DATAFA;
                $this->SITAFA = $Employed[0]->SITAFA;
            }
            return $this;

    }
}