<?php
namespace Rolice\Speedy\Components\Param;

use Illuminate\Support\Arr;
use Rolice\Speedy\Components\ComponentInterface;
use Rolice\Speedy\Traits\Serializable;

class OptionsBeforePayment implements ComponentInterface
{

    use Serializable;

    /**
     * Specifies if the client is allowed to open the package before payment.
     * @bool
     */
    public $open;

    /**
     * Specifies if the client is allowed to test the package before payment.
     * @bool
     */
    public $test;

    /**
     * OptionsBeforePayment constructor.
     * @param bool $open Whether the receiver can open the shipment before he decide to pay.
     * @param bool $test Whether the receiver can test the shipment before he decide to pay.
     */
    public function __construct($open, $test)
    {
        $this->test = $test;
        $this->open = $test ? false : $open;
    }

    public static function createFromRequest(array $data)
    {
        $open = Arr::has($data, 'shipment.pay_after_accept') ? !!Arr::get($data, 'shipment.pay_after_accept') : false;
        $test = Arr::has($data, 'shipment.pay_after_test') ? !!Arr::get($data, 'shipment.pay_after_test') : false;

        return new static($open, $test);
    }

}