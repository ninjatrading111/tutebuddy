<?php

namespace App\Helpers\General;

use Carbon\Carbon;

/**
 * Class Timezone.
 */
class Timezone
{
    /**
     * @param Carbon $date
     * @param string $format
     *
     * @return Carbon
     */
    public function convertToLocal(Carbon $date, $format = 'D M j G:i:s T Y') : string
    {
        return $date->setTimezone(auth()->user()->timezone ?? config('app.timezone'))->format($format);
    }

    /**
     * @param $date
     *
     * @return Carbon
     */
    public function convertFromLocal($date) : Carbon
    {
        return Carbon::parse($date, auth()->user()->timezone)->setTimezone('UTC');
    }

    /**
     * @param $date
     * @param string $timezone
     * 
     * @return Carbon
     */
    public function convertFromTimezone($date, $timezone = 'UTC', $format = 'D M j G:i:s T Y') : string
    {
        return Carbon::parse($date, $timezone)->setTimezone(auth()->user()->timezone ?? config('app.timezone'))->format($format);
    }
}
