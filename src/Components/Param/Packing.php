<?php
namespace Rolice\Speedy\Components\Param;

use Rolice\Speedy\Components\ComponentInterface;
use Rolice\Speedy\Traits\Serializable;

class Packing implements ComponentInterface
{

    use Serializable;

    /**
     * Reserved for internal use.
     * @var int
     */
    public $packingId;

    /**
     * Reserved for internal use.
     * @var int
     */
    public $count;

}