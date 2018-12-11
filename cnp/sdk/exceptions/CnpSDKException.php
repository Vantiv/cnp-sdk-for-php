<?php
/**
 * Created by PhpStorm.
 * User: araju
 * Date: 12/11/18
 * Time: 9:42 AM
 */

namespace cnp\sdk\exceptions;

class cnpSDKException extends \Exception
{
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
    public function __toString()
    {
        return "cnpSDKException : [{$this->code}]: {$this->message}\n";
    }
}