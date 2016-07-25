<?php
namespace Rolice\Speedy\Components;

class AddressSearch implements ComponentInterface
{

    use Serializable;

    /**
     * Site ID
     * @var int
     */
    public $siteId;

    /**
     * Quarter ID
     * @var int|null
     */
    public $quarterId;

    /**
     * Street ID
     * @var int|null
     */
    public $streetId;

    /**
     * Common object ID
     * @var int|null
     */
    public $commonObjectId;

    /**
     * Block No/name
     * @var string
     */
    public $blockNo;

    /**
     * Street No
     * @var string
     */
    public $streetNo;

    /**
     * Entrance
     * @var string
     */
    public $entranceNo;

    /**
     * Return city center coordinates when only siteId is specified
     * @var boolean
     */
    public $returnCityCenterIfNoAddress;

}