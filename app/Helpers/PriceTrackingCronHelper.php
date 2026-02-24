<?php

declare(strict_types=1);

namespace App\Helpers;

class PriceTrackingCronHelper
{
    /**
     * Generate a valid cron expression from a given interval in minutes
     *
     * @param int $minutes
     * @return string
     */
    public static function fromMinutes(int $minutes): string
    {
        if ($minutes <= 60) {
            return "*/{$minutes} * * * *";
        }

        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;

        if ($remainingMinutes === 0) {
            return "0 */{$hours} * * *";
        }

        return "{$remainingMinutes} */{$hours} * * *";
    }
}
