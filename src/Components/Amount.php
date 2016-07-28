<?php
namespace Rolice\Speedy\Components;

use Illuminate\Support\Collection;
use Rolice\Speedy\Traits\Serializable;

class Amount implements ComponentInterface
{

    use Serializable;


    /**
     * The real value of the shipment
     * @var float
     */
    public $insuranceBase;

    /**
     * The insurance premium (i.e. the price of the "Insurance" complementary service)
     * @var float
     */
    public $insurancePremium;

    /**
     * The net price (of the courier service only; w/o discounts, complementary services, VAT etc.)
     * @var float
     */
    public $net;

    /**
     * (NEGATIVE value) Fixed discount value
     * @var float
     */
    public $discountFixed;

    /**
     * (NEGATIVE value) Discount for shipments delivered to a Speedy office by the sender
     * @var float
     */
    public $discountToOffice;

    /**
     * (NEGATIVE value) Discount for the "To be called" complementary service
     * @var float
     */
    public $discountToBeCalled;

    /**
     * (NEGATIVE value) Additional discount
     * @var float
     */
    public $discountAdditional;

    /**
     * Packings value
     * @var float
     */
    public $packings;

    /**
     * The amount of the "Additional charges for loading/unloading operations" complementary service
     * @var float
     */
    public $tro;

    /**
     * The amount of the "Fixed time for delivery" complementary service
     * @var float
     */
    public $fixedTimeDelivery;

    /**
     * Fuel surcharge
     * @var float
     */
    public $fuelSurcharge;

    /**
     * Island surcharge (international shipments)
     * @var float
     */
    public $islandSurcharge;

    /**
     * The "Cash on delivery" amount to be paid to the sender
     * @var float
     */
    public $codBase;

    /**
     * The price of the "Cash on delivery" complementary service
     * @var float
     */
    public $codPremium;

    /**
     * VAT (Value added tax)
     * @var float
     */
    public $vat;

    /**
     * The total amount
     * @var float
     */
    public $total;

    /**
     * The PERCENTAGE of fixed discount
     * @var float
     */
    public $discPcntFixed;

    /**
     * The PERCENTAGE of the "brought to office" complementary service
     * @var float
     */
    public $discPcntToOffice;

    /**
     * The PERCENTAGE of the "To be called" complementary service
     * @var float
     */
    public $discPcntToBeCalled;

    /**
     * The PERCENTAGE of additional discount
     * @var float
     */
    public $discPcntAdditional;

    /**
     * The PERCENTAGE of fuel surcharge
     * @var float
     */
    public $pcntFuelSurcharge;


    public static function createFromSoapResponse($response)
    {
        $result = [];

        if (!is_array($response)) {
            $response = [$response];
        }

        foreach ($response as $amount) {
            $instance = new static;

            foreach ($instance as $expected => $default) {
                $instance->$expected = isset($amount->$expected) ? (float)$amount->$expected : null;
            }

            $result[] = $instance;
        }

        return new Collection($result);
    }

}