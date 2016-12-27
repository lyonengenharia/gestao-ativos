<?php
/**
 * Created by PhpStorm.
 * User: wfs
 * Date: 06/12/2016
 * Time: 12:45
 */

namespace App\Pojo;


use Illuminate\Support\Facades\DB;

class Bem
{
    public $CodBem;
    public $CodEmp;
    public $NomEmp;
    public $CodCcu;
    public $DatAqi;
    public $DesBem;
    public $DesCcu;
    /**
     * Bem constructor.
     * @param $codbem
     * @param $codemp
     */
    public function __construct($codbem, $codemp)
    {
        $this->CodBem = $codbem;
        $this->CodEmp = $codemp;
        if (!empty($this->CodBem) && !empty($this->CodEmp)) {
            $this->get();
        }
    }
    public function get()
    {
        $Bem = DB::connection('sapiens')->table("E670BEM")
            ->select([
                'E670BEM.CODBEM',
                'E670BEM.CODEMP',
                'E670BEM.DATAQI',
                'E670DRA.CODCCU',
                'E670BEM.DESBEM',
                'E670BEM.SITPAT',
                'E044CCU.DESCCU',
                'E070EMP.NOMEMP'


            ])
            ->join('E670DRA', function ($join) {
                $join->on('E670DRA.CODEMP', '=', 'E670BEM.CODEMP')
                    ->whereColumn('E670DRA.CODBEM', '=', 'E670BEM.CODBEM');
            })->join('E044CCU', function ($join) {
                $join->on('E044CCU.CODEMP', '=', 'E670DRA.CODEMP')
                    ->whereColumn('E044CCU.CODCCU', '=', 'E670DRA.CODCCU');
            })->join('E070EMP', function ($join) {
                $join->on('E070EMP.CODEMP', '=', 'E670BEM.CODEMP');
            })->where('E670BEM.CODBEM', '=', $this->CodBem)
            ->where('E670BEM.CODEMP', '=', $this->CodEmp)
            ->where('E670DRA.SEQLOC', '=', 1)
            ->where('E670DRA.datloc', '=', function ($query) {
                $query->from("E670DRA")->select(DB::raw('MAX(datloc)'))
                    ->whereColumn('E670DRA.CODBEM', '=', 'E670BEM.CODBEM')
                    ->whereColumn('E670DRA.CODEMP', '=', 'E670BEM.CODEMP');

            });
        if ($Bem->count() > 0) {
            $Bem = $Bem->get()[0];
            $this->CodBem = $Bem->CODBEM;
            $this->CodEmp = $Bem->CODEMP;
            $this->NomEmp = iconv('windows-1252', 'utf-8', $Bem->NOMEMP);
            $this->CodCcu = $Bem->CODCCU;
            $this->DesCcu = iconv('windows-1252', 'utf-8', $Bem->DESCCU);
            $this->DatAqi = $Bem->DATAQI;
            $this->DesBem = iconv('windows-1252', 'utf-8', $Bem->DESBEM);

        }
        return $this;
    }


}