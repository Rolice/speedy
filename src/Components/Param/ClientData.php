<?php
namespace Rolice\Speedy\Components\Param;

use Illuminate\Support\Collection;
use Rolice\Speedy\Components\ComponentInterface;
use Rolice\Speedy\Traits\Serializable;

class ClientData implements ComponentInterface
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

    public static function createFromRequest(array $data)
    {
        $result = new static;

        $result->clientId = isset($data['clientId']) ? (int)$data['clientId'] : 0;
        $result->partnerName = isset($data['name']) ? $data['name'] : null;
        $result->objectName = isset($data['object']) ? (int)$data['object'] : null;
        $result->address = Address::createFromRequest($data);
        $result->contactName = isset($data['contact']) ? $data['contact'] : null;
        $result->phones = PhoneNumber::createFromRequest($data);
        $result->email = isset($data['email']) ? $data['email'] : null;

        return $result;
    }

}