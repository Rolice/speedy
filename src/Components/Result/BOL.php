<?php
namespace Rolice\Speedy\Components\Result;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Rolice\Speedy\Components\ComponentInterface;
use Rolice\Speedy\Traits\Serializable;

class BOL implements ComponentInterface
{
    use Serializable;

    /**
     * List of parcels data
     * @var Collection
     */
    public $generatedParcels;

    /**
     * Amounts
     * @todo Verify what is really coming from Speedy (one Amount - as documented or Collection).
     * @var Amount
     */
    public $amounts;

    /**
     * Deadline for delivery
     * @var Carbon
     */
    public $deadlineDelivery;

}