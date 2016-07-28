<?php
namespace Rolice\Speedy\Components;

use Rolice\Speedy\Traits\Serializable;

class ParcelInfo implements ComponentInterface
{

    use Serializable;

    /**
     * Parcel's sequential number (1, 2, 3, ...). First parcel (seqNo = 1) could be omitted for non-pallet shipments.
     * In this case it will be auto-generated.
     * @var int
     */
    public $seqNo;

    /**
     * Special value of -1 could be used to request server side automatic parcel number (barcode) generation.
     * Otherwise valid parcel number (barcode) is required. For the first parcel (the one with seqNo equal to 1)
     * automatic barcode generation is required. Actually, the first parcel will take the auto-generated
     * BOL number as its parcel number.
     * @var int
     */
    public $parcelId;

    /**
     * Packing ID
     * @var int|null
     */
    public $packId;

    /**
     * Pallet or Parcel size.
     * @var Size
     */
    public $size;

    /**
     * Pallet or Parcel weight.
     * @var float
     */
    public $weight;

    /**
     * Foreign parcel number associated with this parcel
     * @var string
     */
    public $foreignParcelNumber;

}