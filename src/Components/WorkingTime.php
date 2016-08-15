<?php
namespace Rolice\Speedy\Components;

use DateInterval;
use Rolice\Speedy\Exceptions\SpeedyException;
use Rolice\Speedy\Traits\Timer;

class WorkingTime
{

    use Timer;

    /**
     * The start time of the regular working time.
     * @var DateInterval
     */
    protected $from;

    /**
     * The end time of the regular working time.
     * @var DateInterval
     */
    protected $to;

    /**
     * Half-day working time of the this work time.
     * @var static
     */
    private $half = null;

    public function __construct(DateInterval $from, DateInterval $to, self $half = null)
    {
        $this->from = $from;
        $this->to = $to;
        $this->half = $half;
    }

    /**
     * Returns current "opening" time
     * @return DateInterval
     */
    public function form()
    {
        return $this->from;
    }

    /**
     * Returns this "closing" time
     * @return DateInterval
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
        $from = static::ParseTime($from);
        $to = static::ParseTime($to);

        if (!$from || !$to) {
            return null;
//            throw new SpeedyException('Invalid work time detected.');
        }

        $half = null;

        if ($half_from && $half_to) {
            $half_from = static::ParseTime($half_from);
            $half_to = static::ParseTime($half_to);
            $half = new static($half_from, $half_to, null);
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