<?php
namespace Rolice\Speedy\Components\Param;

use Rolice\Speedy\Components\ComponentInterface;
use Rolice\Speedy\Traits\Serializable;

class PhoneNumber implements ComponentInterface
{

    use Serializable;

    /**
     * Phone number (for example: "088 8123 456", "032112233", "+359888123456", "00359888123456", "+359 2 9441234" etc.)
     * Max size is 20 chars.
     * Phone numbers must contain digits only. The "+" sign is also permitted as a leading symbol.
     * Only spaces are allowed as separators.
     * @var string
     */
    public $number;

    /**
     * An extension number
     * Max size is 10 chars.
     * @var string
     */
    public $internal;

}