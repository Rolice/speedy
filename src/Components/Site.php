<?php
namespace Rolice\Speedy\Components;

use Rolice\Speedy\Exceptions\SpeedyException;
use Rolice\Speedy\Speedy;
use Rolice\Speedy\Traits\Serializable;

/**
 * Class Site
 * @property string formatted
 * @package Rolice\Speedy\Components
 */
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

    /**
     * This magic method is used to simulate and support fields like Eloquent Model do. This is done to maintain
     * compatibility of the implementation with Eloquent models, like it is currently in Econt.
     * @param string $name The name of the field that is being accessed and does not have declaration.
     * @return mixed
     */
    public function __get($name)
    {
        if (method_exists($this, 'get' . ucfirst($name) . 'Attribute')) {
            return $this->{'get' . ucfirst($name) . 'Attribute'}();
        }

        if (method_exists($this, "get{$name}Attribute")) {
            return $this->{"get{$name}Attribute"}();
        }

        return null;
    }

    public static function createFromSoapResponse($response)
    {
        $result = new static;
        $result->addrNomen = isset($response->addrNomen) ? $response->addrNomen : null;
        $result->id = isset($response->id) ? $response->id : null;
        $result->municipality = isset($response->municipality) ? $response->municipality : null;
        $result->name = isset($response->name) ? $response->name : null;
        $result->postCode = isset($response->postCode) ? $response->postCode : null;
        $result->region = isset($response->region) ? $response->region : null;
        $result->type = isset($response->type) ? $response->type : null;
        $result->countryId = isset($response->countryId) ? $response->countryId : null;
        $result->servingOfficeId = isset($response->servingOfficeId) ? $response->servingOfficeId : null;
        $result->coordX = isset($response->coordX) ? $response->coordX : null;
        $result->coordY = isset($response->coordY) ? $response->coordY : null;
        $result->coordType = isset($response->coordType) ? $response->coordType : null;
        $result->servingDays = isset($response->servingDays) ? $response->servingDays : null;

        if (!$result->id || !$result->name || !$result->addrNomen) {
            throw new SpeedyException('Invalid site detected.');
        }

        return $result;
    }

    /**
     * Retrieves formatted name of a site that is distinctive and descriptive for an end-user.
     * This method and its name are left to be compatible with possible future models like in Econt implementation.
     * @return string
     */
    public function getFormattedAttribute()
    {
        return "{$this->type} {$this->name} ($this->postCode)";
    }

    public static function getById($id)
    {
        $speedy = app('speedy');
        $site = $speedy->getSiteById($id);

        if (!isset($site->return) || !$site->return) {
            throw new SpeedyException('Invalid site detected by ID.');
        }

        $site = Site::createFromSoapResponse($site->return);

        return $site;
    }

    public static function find($name)
    {
        if (!$name) {
            return null;
        }

        $filter = new FilterSite;
        $filter->searchString = $name;

        /**
         * @var Speedy $speedy
         */
        $speedy = app('speedy');
        $sites = $speedy->listSitesEx($filter, Language::create());

        if (!isset($sites->return)) {
            throw new SpeedyException('Error while searching for Speedy sites.');
        }

        $settlements = SiteEx::createFromSoapResponse($sites->return);

        $site_ex = $settlements->first();

        if (!$site_ex || !$site_ex instanceof SiteEx) {
            return null;
        }

        if (!$site_ex->site instanceof static) {
            return null;
        }

        return $site_ex->site;
    }


}