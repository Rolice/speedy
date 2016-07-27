<?php
namespace Rolice\Speedy\Traits;

use Carbon\Carbon;
use DateInterval;
use Exception;

trait Timer
{

    public static function ParseDate($date)
    {
        $result = null;

        try {
            $result = new Carbon($date);
        } catch (Exception $e) {
        }

        return $result;
    }

    /**
     * @param string $time Speedy formatted time 830 -> 08:30, 1745 -> 17:45.
     * @return DateInterval|null
     */
    public static function ParseTime($time)
    {
        $result = null;

        try {
            $result = new DateInterval(preg_replace('#^(\d{1,2})(\d{2})$#', 'PT$1H$2M', $time));
        } catch (Exception $e) {
        }

        return $result;
    }

}

