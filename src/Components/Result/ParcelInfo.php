<?php
namespace Rolice\Speedy\Components\Result;

use Illuminate\Support\Collection;
use Rolice\Speedy\Components\ComponentInterface;
use Rolice\Speedy\Exceptions\SpeedyException;
use Rolice\Speedy\Traits\Serializable;

class ParcelInfo implements ComponentInterface
{

    use Serializable;

    /**
     * Parcel's serial number (1, 2, 3, ...)
     * @var int
     */
    public $seqNo;

    /**
     * Parcel ID. First parcel's ID is always the same as the BOL number.
     * @var int
     */
    public $parcelId;

    public function __construct($seqNo, $parcelId)
    {
        $this->seqNo = (int)$seqNo;
        $this->parcelId = (float)$parcelId;
    }

    public static function createFromSoapResponse($response)
    {
        $result = [];

        if (!is_array($response)) {
            $response = [$response];
        }

        foreach ($response as $parcelInfo) {
            $seqNo = isset($parcelInfo->seqNo) ? (int)$parcelInfo->seqNo : 0;
            $parcelId = isset($parcelInfo->parcelId) ? (float)$parcelInfo->parcelId : 0;

            if (!$seqNo || !$parcelId) {
                throw new SpeedyException('Invalid parcel info detected.');
            }

            $result[] = new static($seqNo, $parcelId);
        }

        return new Collection($result);
    }

}