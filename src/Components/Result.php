<?php
namespace Rolice\Speedy\Components;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Rolice\Speedy\Exceptions\SpeedyException;
use Rolice\Speedy\Traits\Serializable;
use Rolice\Speedy\Traits\Timer;

class Result implements ComponentInterface
{

    use Serializable;
    use Timer;

    /**
     * Shipment's price
     * @var Collection
     */
    public $amounts;

    /**
     * The pick-up date
     * @var Carbon
     */
    public $takingDate;

    /**
     * Deadline for delivery
     * @var Carbon
     */
    public $deadlineDelivery;

    /**
     * Specifies if the discounts are potentially partial (the final discounts might be bigger depending on the other participants' contracts).
     * @var bool
     */
    public $partialDiscount;


    public function __construct(Collection $amounts, Carbon $taking, Carbon $deadline, $partial = false)
    {
        $this->amounts = $amounts;
        $this->takingDate = $taking;
        $this->deadlineDelivery = $deadline;
        $this->partialDiscount = !!$partial;
    }

    public static function createFromSoapResponse($response)
    {
        $amounts = Amount::createFromSoapResponse($response->amounts);
        $takingDate = static::ParseDate($response->takingDate);
        $deadlineDelivery = static::ParseDate($response->deadlineDelivery);
        $partialDiscount = !!$response->partialDiscount;

        if (!$amounts instanceof Collection || !$takingDate || !$deadlineDelivery) {
            throw new SpeedyException('Invalid calculation result data detected.');
        }

        return new static($amounts, $takingDate, $deadlineDelivery, $partialDiscount);
    }

}