<?php

namespace CAC\Component\ESP\Adapter\Engine;


class EngineApiException extends \Exception
{
    private $engineCode;



    public function setEngineCode($code)
    {
        $this->engineCode = $code;
    }

}
