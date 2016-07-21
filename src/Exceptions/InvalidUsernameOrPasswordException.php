<?php
namespace Rolice\Speedy\Exceptions;

class InvalidUsernameOrPasswordException extends SpeedyException
{
    protected $message = 'Invalid username or password supplied for Speedy authorization.';
}