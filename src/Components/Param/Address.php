<?php
namespace Rolice\Speedy\Components\Param;

use Rolice\Speedy\Components\ComponentInterface;
use Rolice\Speedy\Components\Result\Site;
use Rolice\Speedy\Exceptions\SpeedyException;
use Rolice\Speedy\Traits\Serializable;

class Address implements ComponentInterface
{

    use Serializable;

    /**
     * The ID of the site.
     * @var int
     */
    public $siteId;

    /**
     * The name of the site.
     * @var string
     */
    public $siteName;

    /**
     * The postal code of the address.
     * @var string
     */
    public $postCode;

    /**
     * The name of the street where the address is.
     * @var string
     */
    public $streetName;

    /**
     * The type of the street for this address.
     * @var string
     */
    public $streetType;

    /**
     * The ID of the street in Speedy database, where the address resides.
     * @var int|null
     */
    public $streetId;

    /**
     * The street number for the address.
     * @var string
     */
    public $streetNo;

    /**
     * The name of the neighbourhood.
     * @var string
     */
    public $quarterName;

    /**
     * The type of the neighbourhood.
     * @var string
     */
    public $quarterType;

    /**
     * The ID of the neighbourhood in Speedy database.
     * @var int|null
     */
    public $quarterId;

    /**
     * The number of the building inside the neighbourhood.
     * @var string
     */
    public $blockNo;

    /**
     * The entrance number of the building.
     * @var string
     */
    public $entranceNo;

    /**
     * The floor number inside the building entrance.
     * @var string
     */
    public $floorNo;

    /**
     * The number of the apartment inside the entrance of the building.
     * @var string
     */
    public $apartmentNo;

    /**
     * Additional notes that are available with this address.
     * @var string
     */
    public $addressNote;

    /**
     * The GPS X coordinate for this address.
     * @var float
     */
    public $coordX;

    /**
     * The GPS Y coordinate for this address.
     * @var float
     */
    public $coordY;

    /**
     * Common object ID assigned by Speedy.
     * @var int|null
     */
    public $commonObjectId;

    /**
     * JSON representation of this address.
     * @var string
     */
    public $serializedAddress;

    /**
     * The ID of the country inside Speedy database.
     * @var int
     */
    public $countryId;

    /**
     * Foreign address line 1.
     * @var string
     */
    public $frnAddressLine1;

    /**
     * Foreign address line 2.
     * @var string
     */
    public $frnAddressLine2;

    /**
     * Country state ID inside Speedy database.
     * @var string
     */
    public $stateId;


    public static function createFromRequest(array $data)
    {
        $result = new static;

        if (isset($data['settlement']) && $site = Site::detect($data['settlement'])) {
            $result->siteId = $site->id;
            $result->siteName = $site->name;
        }

        $result->postCode = isset($data['postalCode']) ? $data['postalCode'] : null;
        $result->streetId = isset($data['street']) ? (int)$data['street'] : null;
        $result->streetNo = isset($data['street_num']) ? $data['street_num'] : null;
        $result->entranceNo = isset($data['street_vh']) ? $data['street_vh'] : null;
        $result->floorNo = isset($data['street_et']) ? $data['street_et'] : null;
        $result->apartmentNo = isset($data['street_ap']) ? $data['street_ap'] : null;

        if (!$result->siteId) {
            $result->siteId = null;
        }

        if (!$result->streetId) {
            $result->streetId = null;
        }

        return $result;
    }

    public static function createFromSoapResponse($response)
    {
        $result = new static;

        $result->siteId = isset($response->siteId) ? (int)$response->siteId : null;
        $result->siteName = isset($response->siteName) ? $response->siteName : null;
        $result->postCode = isset($response->postCode) ? $response->postCode : null;
        $result->streetName = isset($response->streetName) ? $response->streetName : null;
        $result->streetType = isset($response->streetType) ? $response->streetType : null;
        $result->streetId = isset($response->streetId) ? (int)$response->streetId : null;
        $result->quarterName = isset($response->quarterName) ? $response->quarterName : null;
        $result->quarterType = isset($response->quarterType) ? $response->quarterType : null;
        $result->quarterId = isset($response->quarterId) ? (int)$response->quarterId : null;
        $result->streetNo = isset($response->streetNo) ? $response->streetNo : null;
        $result->blockNo = isset($response->blockNo) ? $response->blockNo : null;
        $result->entranceNo = isset($response->entranceNo) ? $response->entranceNo : null;
        $result->floorNo = isset($response->floorNo) ? $response->floorNo : null;
        $result->apartmentNo = isset($response->apartmentNo) ? $response->apartmentNo : null;
        $result->addressNote = isset($response->addressNote) ? $response->addressNote : null;
        $result->coordX = isset($response->coordX) ? (float)$response->coordX : null;
        $result->coordY = isset($response->coordY) ? (float)$response->coordY : null;
        $result->commonObjectId = isset($response->commonObjectId) ? (int)$response->commonObjectId : null;
        $result->serializedAddress = isset($response->serializedAddress) ? (int)$response->serializedAddress : null;
        $result->countryId = isset($response->countryId) ? (int)$response->countryId : null;
        $result->frnAddressLine1 = isset($response->frnAddressLine1) ? $response->frnAddressLine1 : null;
        $result->frnAddressLine2 = isset($response->frnAddressLine2) ? $response->frnAddressLine2 : null;
        $result->stateId = isset($response->stateId) ? $response->stateId : null;

        if (!$result->streetId) {
            $result->streetId = null;
        }

        if (!$result->quarterId) {
            $result->quarterId = null;
        }

        if (!$result->commonObjectId) {
            $result->commonObjectId = null;
        }

        if (!$result->siteId || !$response->countryId) {
            throw new SpeedyException('Invalid address detected.');
        }

        return $result;
    }

}