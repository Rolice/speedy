<?php
namespace Rolice\Speedy\Components\Param;

use Rolice\Speedy\Components\ComponentInterface;
use Rolice\Speedy\Traits\Serializable;

class ReturnServiceRequest implements ComponentInterface
{

    use Serializable;

    /**
     * Service type id
     * @var int
     */
    public $serviceTypeId;

    /**
     * Number of parcels
     * @var int
     */
    public $parcelsCount;

}