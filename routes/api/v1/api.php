<?php

declare(strict_types=1);

use App\Http\Api\Actions\V1\PriceSubscriptions\StorePriceSubscriptionAction;
use Illuminate\Support\Facades\Route;

Route::post('/price-subscriptions', StorePriceSubscriptionAction::class)->name('subscriptions.store');
