<?php
/**
 * Created by PhpStorm.
 * User: wfs
 * Date: 21/11/2016
 * Time: 15:18
 */

namespace App\Pojo;


class Message
{
    private $Title;
    private $SubTitle;
    private $Body;
    private $Attach;

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->Title;
    }

    /**
     * @param mixed $Title
     */
    public function setTitle($Title)
    {
        $this->Title = $Title;
    }

    /**
     * @return mixed
     */
    public function getSubTitle()
    {
        return $this->SubTitle;
    }

    /**
     * @param mixed $SubTitle
     */
    public function setSubTitle($SubTitle)
    {
        $this->SubTitle = $SubTitle;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->Body;
    }

    /**
     * @param mixed $Body
     */
    public function setBody($Body)
    {
        $this->Body = $Body;
    }

    /**
     * @return mixed
     */
    public function getAttach()
    {
        return $this->Attach;
    }

    /**
     * @param mixed $Attach
     */
    public function setAttach($Attach)
    {
        $this->Attach = $Attach;
    }



}