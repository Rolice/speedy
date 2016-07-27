<?php
namespace Rolice\Speedy\Components;

use Rolice\Speedy\Exceptions\SpeedyException;

class AddressEx implements ComponentInterface
{

    use Serializable;

    /**
     * The Speedy site of the address
     * @var Site
     */
    public $site;

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
     * Speedy-specific type of the GPS coordinates.
     * @var int
     */
    public $coordTypeId;

    /**
     * Common object ID assigned by Speedy.
     * @var int|null
     */
    public $commonObjectId;

    /**
     * Common object name assigned by Speedy.
     * @var string
     */
    public $commonObjectName;

    /**
     * Concatenated full address.
     * @var string
     */
    public $fullAddressString;

    /**
     * Site-specific address.
     * @var string
     */
    public $siteAddressString;

    /**
     * Concatenated local address.
     * @var string
     */
    public $localAddressString;

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


    public static function createFromSoapResponse($response)
    {
        $result = new static;

        $result->site = isset($response->resultSite) && is_object($response->resultSite) ? Site::createFromSoapResponse($response->resultSite) : null;
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
        $result->coordTypeId = isset($response->coordTypeId) ? (int)$response->coordTypeId : null;
        $result->commonObjectId = isset($response->commonObjectId) ? (int)$response->commonObjectId : null;
        $result->commonObjectName = isset($response->commonObjectName) ? $response->commonObjectName : null;
        $result->fullAddressString = isset($response->fullAddressString) ? $response->fullAddressString : null;
        $result->siteAddressString = isset($response->siteAddressString) ? $response->siteAddressString : null;
        $result->localAddressString = isset($response->localAddressString) ? $response->localAddressString : null;
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

        if (!$result->site || !$response->countryId) {
            throw new SpeedyException('Invalid address ex detected.');
        }

        return $result;
    }

}