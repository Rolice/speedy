<?php
namespace Rolice\Speedy\Exceptions;

use Exception;

class SpeedyException extends Exception
{

    public function __construct($message = '', $code = '')
    {
        parent::__construct($message ?: $this->message, 0);
        $this->code = $code;
    }

    public function getResponse()
    {
        return response()->json(['error' => $this->getMessage() ?: 'An error with Speedy occurred.']);
    }

}