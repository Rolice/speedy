<?php
namespace Rolice\Speedy\Components\Param;

use Rolice\Speedy\Components\ComponentInterface;
use Rolice\Speedy\Traits\Serializable;

class OptionsBeforePayment implements ComponentInterface
{

    use Serializable;

    /**
     * Specifies if the client is allowed to open the package before payment.
     * @bool
     */
    public $open;

    /**
     * Specifies if the client is allowed to test the package before payment.
     * @bool
     */
    public $test;

}