<?php
namespace Rolice\Speedy\Components;

use Carbon\CarbonInterval;
use DateInterval;

class WorkingTime
{


    /**
     * The start time of the regular working time.
     * @var CarbonInterval
     */
    protected $from;

    /**
     * The end time of the regular working time.
     * @var CarbonInterval
     */
    protected $to;

    /**
     * Half-day working time of the this work time.
     * @var static
     */
    protected $half = null;

    public function __construct(DateInterval $from, DateInterval $to, self $half = null)
    {
        $this->from = $from;
        $this->to = $to;
        $this->half = $half;
    }

    /**
     * Returns current "opening" time
     * @return CarbonInterval
     */
    public function form()
    {
        return $this->from;
    }

    /**
     * Returns this "closing" time
     * @return CarbonInterval
     */
    public function to()
    {
        return $this->to;
    }

    /**
     * Retrieves half work day instance.
     * @return static
     */
    public function half()
    {
        return $this->half;
    }

    public static function create($from, $to, $half_from, $half_to)
    {
        $from = CarbonInterval::createFromDateString($from);
        $to = CarbonInterval::createFromDateString($to);

        $half_from = CarbonInterval::createFromDateString($half_from);
        $half_to = CarbonInterval::createFromDateString($half_to);

        $half = null;

        if ($half_from && $half_to) {
            $half = new static($from, $to, null);
        }

        return new static($from, $to, $half);
    }

    public static function createFromSoapResponse($response)
    {
        $from = isset($response->workingTimeFrom) ? $response->workingTimeFrom : null;
        $to = isset($response->workingTimeTo) ? $response->workingTimeTo : null;
        $half_from = isset($response->workingTimeHalfFrom) ? $response->workingTimeHalfFrom : null;
        $half_to = isset($response->workingTimeHalfTo) ? $response->workingTimeHalfTo : null;

        return static::create($from, $to, $half_from, $half_to);
    }

}