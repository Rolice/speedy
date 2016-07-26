<?php
namespace Rolice\Speedy\Exceptions;

class InvalidSessionException extends SpeedyException
{
    protected $message = 'Invalid or expired session supplied. Please log in again in Speedy service.';
}