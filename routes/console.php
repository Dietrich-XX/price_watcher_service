<?php

use App\Helpers\PriceTrackingCronHelper;
use App\Jobs\TrackPrices\TrackPricesJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

//Schedule::job(new TrackPricesJob())
//    ->cron(PriceTrackingCronHelper::fromMinutes((int) config('app.interval_minutes', 60)));

Schedule::job(new TrackPricesJob())->everyMinute();
