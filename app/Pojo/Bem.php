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
    private $CodBem;
    private $CodEmp;
    private $NomEmp;
    private $CodCcu;
    private $DatAqi;
    private $DesBem;
    private $DesCcu;

    /**
     * Bem constructor.
     * @param $codbem
     * @param $codemp
     */
    public function __construct($codbem,$codemp)
    {
        $this->CodBem = $codbem;
        $this->CodEmp = $codemp;
        if(!empty($this->CodBem) && !empty($this->CodEmp)){
            $this->get();
        }
    }

    /**
     * @return mixed
     */
    public function getNomEmp()
    {
        return $this->NomEmp;
    }

    /**
     * @param mixed $NomEmp
     */
    public function setNomEmp($NomEmp)
    {
        $this->NomEmp = $NomEmp;
    }


    /**
     * @return mixed
     */
    public function getCod()
    {
        return $this->Cod;
    }

    /**
     * @param mixed $Cod
     */
    public function setCod($Cod)
    {
        $this->Cod = $Cod;
    }

    /**
     * @return mixed
     */
    public function getEmp()
    {
        return $this->Emp;
    }

    /**
     * @param mixed $Emp
     */
    public function setEmp($Emp)
    {
        $this->Emp = $Emp;
    }

    /**
     * @return mixed
     */
    public function getCodBem()
    {
        return $this->CodBem;
    }

    /**
     * @param mixed $CodBem
     */
    public function setCodBem($CodBem)
    {
        $this->CodBem = $CodBem;
    }

    /**
     * @return mixed
     */
    public function getCodEmp()
    {
        return $this->CodEmp;
    }

    /**
     * @param mixed $CodEmp
     */
    public function setCodEmp($CodEmp)
    {
        $this->CodEmp = $CodEmp;
    }

    /**
     * @return mixed
     */
    public function getCodCcu()
    {
        return $this->CodCcu;
    }

    /**
     * @param mixed $CodCcu
     */
    public function setCodCcu($CodCcu)
    {
        $this->CodCcu = $CodCcu;
    }

    /**
     * @return mixed
     */
    public function getDatAqi()
    {
        return $this->DatAqi;
    }

    /**
     * @param mixed $DatAqi
     */
    public function setDatAqi($DatAqi)
    {
        $this->DatAqi = $DatAqi;
    }

    /**
     * @return mixed
     */
    public function getDesBem()
    {
        return $this->DesBem;
    }

    /**
     * @param mixed $DesBem
     */
    public function setDesBem($DesBem)
    {
        $this->DesBem = $DesBem;
    }

    /**
     * @return mixed
     */
    public function getDesCcu()
    {
        return $this->DesCcu;
    }

    /**
     * @param mixed $DesCcu
     */
    public function setDesCcu($DesCcu)
    {
        $this->DesCcu = $DesCcu;
    }
    public function get(){
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
                    ->whereColumn('E670DRA.CODBEM','=','E670BEM.CODBEM');
            })->join('E044CCU', function ($join) {
               $join->on('E044CCU.CODEMP', '=', 'E670DRA.CODEMP')
                   ->whereColumn('E044CCU.CODCCU', '=', 'E670DRA.CODCCU');
           })->join('E070EMP', function ($join) {
               $join->on('E070EMP.CODEMP', '=', 'E670BEM.CODEMP');
           })->where('E670BEM.CODBEM','=',$this->CodBem)
            ->where('E670BEM.CODEMP','=',$this->CodEmp)
           ->where('E670DRA.SEQLOC','=',1)
           ->where('E670DRA.datloc','=',function ($query){
              $query->from("E670DRA")->select(DB::raw('MAX(datloc)'))
                  ->whereColumn('E670DRA.CODBEM','=','E670BEM.CODBEM')
                  ->whereColumn('E670DRA.CODEMP', '=', 'E670BEM.CODEMP');

       });
       if($Bem->count()>0){
           $Bem = $Bem->get()[0];
           $this->CodBem = $Bem->CODBEM;
           $this->CodEmp = $Bem->CODEMP;
           $this->NomEmp = $Bem->NOMEMP;
           $this->CodCcu = $Bem->CODCCU;
           $this->DesCcu = $Bem->DESCCU;
           $this->DatAqi = $Bem->DATAQI;
           $this->DesBem = $Bem->DESBEM;

       }
       return $this;
    }



}