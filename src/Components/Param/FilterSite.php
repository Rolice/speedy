<?php
namespace Rolice\Speedy\Components\Param;

use Rolice\Speedy\Components\ComponentInterface;
use Rolice\Speedy\Traits\Serializable;

class FilterSite implements ComponentInterface
{

    use Serializable;

    public $countryId;

    public $postCode;

    public $name;

    public $type;

    public $municipality;

    public $region;

    public $searchString;

}