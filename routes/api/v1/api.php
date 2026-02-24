<?php

declare(strict_types=1);

use App\Http\Actions\Api\V1\EmailVerifications\ResendVerificationEmailAction;
use App\Http\Actions\Api\V1\PriceSubscriptions\StorePriceSubscriptionAction;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:60,1'])->group(function () {
    Route::post('/price-subscriptions', StorePriceSubscriptionAction::class)
        ->name('subscriptions.store');

    Route::post('/subscribers/{subscriber}/force-email-verification', ResendVerificationEmailAction::class)
        ->name('subscribers.force-email-verification');
});
