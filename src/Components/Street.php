<?php
namespace Rolice\Speedy\Components;

use Illuminate\Support\Collection;
use Rolice\Speedy\Exceptions\SpeedyException;

class Street implements ComponentInterface
{

    use Serializable;

    /**
     * The site ID which contains the street on its area.
     * @var int
     */
    public $id;

    /**
     * The type of the street
     * @var string
     */
    public $type;

    /**
     * Name of the street. It could be the old one, in case it was renamed.
     * @var string
     */
    public $name;

    /**
     * The actual name of the street if it has a new name. It is not known from the DOCs if relying on one of the name
     * fields is enough, for example if actualName is always with the new or current name. That is why functionality
     * here cannot assume any lesser implementation with single field.
     * @var string
     */
    public $actualName;

    public function __construct($id, $type, $name, $actualName = null)
    {
        $this->id = $id;
        $this->type = $type;
        $this->name = $name;
        $this->actualName = $actualName;
    }

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


    /**
     * Retrieves formatted name of a street that is distinctive and descriptive for an end-user.
     * This method and its name are left to be compatible with possible future models like in Econt implementation.
     * @return string
     */
    public function getFormattedAttribute()
    {
        return "{$this->type} {$this->name}";
    }

    public static function createFromSoapResponse($response)
    {
        $result = [];

        if (is_object($response)) {
            $response = [$response];
        }

        if (is_array($response)) {
            foreach ($response as $street) {
                $id = isset($street->id) ? $street->id : null;
                $type = isset($street->type) ? $street->type : null;
                $name = isset($street->name) ? $street->name : null;
                $actualName = isset($street->actualName) ? $street->actualName : null;

                if (!$id || !$type || !$name) {
                    throw new SpeedyException('Invalid street detected.');
                }

                $result[] = new static($id, $type, $name, $actualName);

            }
        }

        return new Collection($result);
    }

}