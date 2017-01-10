<?php
/**
 * Created by PhpStorm.
 * User: wfs
 * Date: 27/12/2016
 * Time: 10:52
 */

namespace App\Pojo;


class Response
{
    /**
     * Response constructor.
     * @param null $error
     * @param null $data
     * @param null $msg
     */
    public function __construct($error,$data,$msg)
    {
        $this->error = $error;
        $this->data = $data;
        $this->msg = $msg;
        return $this;
    }

    public $error;
    public $data;
    public $msg;

}