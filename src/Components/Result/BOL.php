<?php
namespace Rolice\Speedy\Components\Result;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Rolice\Speedy\Components\ComponentInterface;
use Rolice\Speedy\Exceptions\SpeedyException;
use Rolice\Speedy\Traits\Serializable;
use Rolice\Speedy\Traits\Timer;

class BOL implements ComponentInterface
{
    use Serializable;
    use Timer;

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


    public static function createFromSoapResponse($response)
    {
        $result = new static;

        $result->generatedParcels = ParcelInfo::createFromSoapResponse($response->generatedParcels);
        $result->amounts = Amount::createFromSoapResponse($response->amounts);
        $result->deadlineDelivery = static::ParseDate($result->deadlineDelivery);

        if (!$result->generatedParcels || $result->generatedParcels->isEmpty()) {
            throw new SpeedyException('Invalid bill of lading (BOL/waybill detected) - no parcel info found.');
        }

        if (!$result->amounts || !$result->deadlineDelivery) {
            throw new SpeedyException('Invalid bill of lading (BOL/waybill detected) - no amount or deadline found.');
        }

        return $result;
    }
}