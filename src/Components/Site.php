<?php
namespace Rolice\Speedy\Components;

class Site implements ComponentInterface
{

    use Serializable;

    /**
     * Site ID
     * @var int
     */
    public $id;

    /**
     * Site type
     * @var string
     */
    public $type;

    /**
     * Site name
     * @var string
     */
    public $name;

    /**
     * Municipality name
     * @var string
     */
    public $municipality;

    /**
     * Region name
     * @var string
     */
    public $region;

    /**
     * Post code
     * @var string
     */
    public $postCode;

    /**
     * Specifies if Speedy have (or have not) address nomenclature (streets, quarters etc.) for this site
     * @var AddrNomen
     */
    public $addrNomen;

    /**
     * Site country id
     * @var int
     */
    public $countryId;

    /**
     * Serving office id
     * @var int
     */
    public $servingOfficeId;

    /**
     * GPS X coordinate
     * @var float
     */
    public $coordX;

    /**
     * GPS Y coordinate
     * @var float
     */
    public $coordY;

    /**
     * GPS coordinates type id
     * @var int
     */
    public $coordType;

    /**
     * ServingDays - Serving days for this site. Format: 7 serial digits (0 or 1) where each digit corresponds to a day
     * in week (the first digit corresponds to Monday, the second to Tuesday and so on). Value of '0' (zero) means that
     * the site is not served by Speedy on this day while '1' (one) means that it is served.
     *
     * (Example: the text "0100100" means that the site is served on Tuesday and Friday only.)
     *
     * In short: BitField stored in string...
     * -- Lyubo
     *
     * @var string
     */
    public $servingDays;

}