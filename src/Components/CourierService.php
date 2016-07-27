<?php
namespace Rolice\Speedy\Components;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Rolice\Speedy\Exceptions\SpeedyException;

class CourierService implements ComponentInterface
{

    use Serializable;

    /**
     * The default service ID.
     * @todo Experimental.
     */
    const DefaultServiceId = 113;

    /**
     * Courier service type ID
     * @var int
     */
    public $typeId;

    /**
     * Courier service name
     * @var string
     */
    public $name;

    /**
     * Specifies if the complementary service "Fixed time for delivery" is banned, allowed or required.
     * @var ComplementaryServiceAllowance
     */
    public $allowanceFixedTimeDelivery;

    /**
     * Specifies if the complementary service "COD" is banned, allowed or required.
     * @var ComplementaryServiceAllowance
     */
    public $allowanceCashOnDelivery;

    /**
     * Specifies if the complementary service "Insurance" is banned, allowed or required.
     * @var ComplementaryServiceAllowance
     */
    public $allowanceInsurance;

    /**
     * Specifies if the complementary service "Return documents" is banned, allowed or required.
     * @var ComplementaryServiceAllowance
     */
    public $allowanceBackDocumentsRequest;

    /**
     * Specifies if the complementary service "Return receipt" is banned, allowed or required.
     * @var ComplementaryServiceAllowance
     */
    public $allowanceBackReceiptRequest;

    /**
     * Specifies if the complementary service "To be called" is banned, allowed or required.
     * @var ComplementaryServiceAllowance
     */
    public $allowanceToBeCalled;

    /**
     * The deadline for shipment delivery
     * @var Carbon|null
     */
    public $deliveryDeadline = null;

    /**
     * Cargo type (1 - CARGO_TYPE_PARCEL, 2 - CARGO_TYPE_PALLET)
     * @var int
     */
    public $cargoType;


    public static function createFromSoapResponse($response)
    {
        $result = [];

        if (!is_array($response)) {
            $response = [$response];
        }

        foreach ($response as $service) {
            $instance = new static;
            $instance->typeId = isset($service->typeId) ? (int)$service->typeId : null;
            $instance->name = isset($service->name) ? $service->name : null;
            $instance->allowanceFixedTimeDelivery = isset($service->allowanceFixedTimeDelivery) ? $service->allowanceFixedTimeDelivery : null;
            $instance->allowanceCashOnDelivery = isset($service->allowanceCashOnDelivery) ? $service->allowanceCashOnDelivery : null;
            $instance->allowanceInsurance = isset($service->allowanceInsurance) ? $service->allowanceInsurance : null;
            $instance->allowanceBackDocumentsRequest = isset($service->allowanceBackDocumentsRequest) ? $service->allowanceBackDocumentsRequest : null;
            $instance->allowanceBackReceiptRequest = isset($service->allowanceBackReceiptRequest) ? $service->allowanceBackReceiptRequest : null;
            $instance->allowanceToBeCalled = isset($service->allowanceToBeCalled) ? $service->allowanceToBeCalled : null;
            $instance->deliveryDeadline = isset($service->deliveryDeadline) ? $service->deliveryDeadline : null;
            $instance->cargoType = isset($service->cargoType) ? (int)$service->cargoType : null;

            if (!$instance->typeId || !$instance->name || !$instance->cargoType) {
                throw new SpeedyException('Invalid Speedy service detected.');
            }

            if ($instance->deliveryDeadline && !$instance->deliveryDeadline instanceof Carbon) {
                throw new SpeedyException('Invalid Speedy service date detected.');
            }


            $result[] = $instance;
        }

        return new Collection($result);
    }

}