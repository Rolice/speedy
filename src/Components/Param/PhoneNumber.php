<?php
namespace Rolice\Speedy\Components\Param;

use Illuminate\Support\Collection;
use Rolice\Speedy\Components\ComponentInterface;
use Rolice\Speedy\Traits\Serializable;

class PhoneNumber implements ComponentInterface
{

    use Serializable;

    /**
     * Phone number (for example: "088 8123 456", "032112233", "+359888123456", "00359888123456", "+359 2 9441234" etc.)
     * Max size is 20 chars.
     * Phone numbers must contain digits only. The "+" sign is also permitted as a leading symbol.
     * Only spaces are allowed as separators.
     * @var string
     */
    public $number;

    /**
     * An extension number
     * Max size is 10 chars.
     * @var string
     */
    public $internal;

    public function __construct($number, $internal = null)
    {
        $this->number = $number;
        $this->internal = $internal;
    }

    public static function createFromRequest(array $data)
    {
        $result = [];

        if (isset($data['phone'])) {
            $result[] = new static($data['phone']);
        }

        if (isset($data['phones']) && is_array($data['phones'])) {
            foreach ($data['phones'] as $phone) {
                if (!is_scalar($phone)) {
                    continue;
                }

                $result[] = new static($phone);
            }
        }

        return new Collection($result);
    }
}