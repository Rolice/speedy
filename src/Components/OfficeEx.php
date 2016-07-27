<?php
namespace Rolice\Speedy\Components;

use Illuminate\Support\Collection;
use Rolice\Speedy\Exceptions\SpeedyException;

class OfficeEx implements ComponentInterface
{

    use Serializable;

    /**
     * The ID of Speedy office
     * @var int
     */
    public $id;

    /**
     * The name of the office.
     * @var string
     */
    public $name;

    /**
     * The ID of the site which contains the office.
     * @var int
     */
    public $siteId;

    /**
     * An address reference for the office.
     * @var object
     */
    public $address;

    /**
     * The work time of the office.
     * @var WorkingTime
     */
    public $workingTime;

    /**
     * The maximum dimensions of a shipping that the office can handle.
     * @var object
     */
    public $maxParcelDimensions;

    /**
     * The maximum weight of a shipping that this office can handle.
     * @var float
     */
    public $maxParcelWeight;

    /**
     * Working time schedule for office.
     * @var object
     */
    public $workingTimeSchedule;

    public static function createFromSoapResponse($response)
    {
        $result = [];

        if (is_object($response)) {
            $response = [$response];
        }

        if (is_array($response)) {
            foreach ($response as $office) {
                $instance = new static;

                $instance->id = isset($office->id) ? $office->id : null;
                $instance->name = isset($office->name) ? $office->name : null;
                $instance->siteId = isset($office->siteId) ? $office->siteId : null;
                $instance->address = AddressEx::createFromSoapResponse($office->address);
                $instance->workingTime = WorkingTime::createFromSoapResponse($office);
                $instance->maxParcelDimensions = Size::createFromSoapResponse($office->maxParcelDimensions);
                $instance->maxParcelWeight = isset($office->maxParcelWeight) ? (float)$office->maxParcelWeight : null;
                $instance->workingTimeSchedule = ExactWorkTime::createFromSoapResponse($office->workingTimeSchedule);

                if (!$instance->id || !$instance->name) {
                    throw new SpeedyException('Invalid office detected.');
                }

                $result[] = $instance;
            }
        }

        return new Collection($result);
    }

}