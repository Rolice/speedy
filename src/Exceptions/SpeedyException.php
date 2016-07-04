<?php
namespace Rolice\Speedy\Exceptions;

use Exception;

class SpeedyException extends Exception
{

    public function __construct($message = '', $code = '')
    {
        parent::__construct($message, 0);
        $this->code = $code;
    }

}