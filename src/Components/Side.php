<?php
namespace Rolice\Speedy\Components;

use Illuminate\Support\Collection;
use Rolice\Speedy\Components\Param\Address;
use Rolice\Speedy\Traits\Serializable;

class Side implements ComponentInterface
{

    use Serializable;

    /**
     * Client/Partner ID
     * @var int|null
     */
    public $clientId;

    /**
     * Name of the client (company or private person)
     * @var string
     */
    public $partnerName;

    /**
     * Company department/office
     * @var string
     */
    public $objectName;

    /**
     * Address details
     * @var Address
     */
    public $address;

    /**
     * Contact name
     * @var string
     */
    public $contactName;

    /**
     * Phone numbers
     * @var Collection<PhoneNumber>
     */
    public $phones;

    /**
     * Email address
     * @var string
     */
    public $email;

}