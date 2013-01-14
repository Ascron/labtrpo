<?php
class PageError extends Exception
{
    private $resultCode;

    function __construct($message, $resultCode)
    {
        $this->setResultCode($resultCode);
        parent::__construct($message);
    }

    public function setResultCode($resultCode)
    {
        $this->resultCode = $resultCode;
    }

    public function getResultCode()
    {
        return $this->resultCode;
    }


}
