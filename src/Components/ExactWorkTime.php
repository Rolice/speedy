<?php
namespace Rolice\Speedy\Components;

use Carbon\Carbon;
use DateInterval;
use Illuminate\Support\Collection;
use Rolice\Speedy\Exceptions\SpeedyException;
use Rolice\Speedy\Traits\Timer;

class ExactWorkTime
{

    use Timer;

    /**
     * The exact date when this work time applies.
     * @var Carbon
     */
    protected $date;

    /**
     * Points whether the work time is overridden.
     * @var bool
     */
    protected $exception;

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

    public function __construct(Carbon $date, $exception, DateInterval $from = null, DateInterval $to = null)
    {
        $this->date = $date;
        $this->exception = !!$exception;
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * Returns the exact date of this work time.
     * @return Carbon
     */
    public function date()
    {
        return $this->date;
    }

    /**
     * Returns whether this instance is with exceptional work time.
     * @return bool
     */
    public function exception()
    {
        return $this->exception;
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

    public function closed()
    {
        return !$this->from && !$this->to;
    }

    public static function create($date, $exception, $from = null, $to = null)
    {
        $date = static::ParseDate($date);
        $from = static::ParseTime($from);
        $to = static::ParseTime($to);

        if (!$date) {
            throw new SpeedyException('Invalid exact work time detected.');
        }

        return new static($date, !!$exception, $from, $to);
    }

    public static function createFromSoapResponse($response)
    {
        $result = [];

        if (!is_array($response)) {
            $response = [$response];
        }

        foreach ($response as $exact_work_time) {
            $date = isset($exact_work_time->date) ? $exact_work_time->date : null;
            $exception = isset($exact_work_time->workingTimeException) ? !!$exact_work_time->workingTimeException : false;
            $from = isset($exact_work_time->workingTimeFrom) ? $exact_work_time->workingTimeFrom : null;
            $to = isset($exact_work_time->workingTimeTo) ? $exact_work_time->workingTimeTo : null;

            $result[] = static::create($date, $exception, $from, $to);
        }

        return new Collection($result);
    }

}