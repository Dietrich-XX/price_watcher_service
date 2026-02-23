<?php

declare(strict_types=1);

namespace App\Data\PriceSubscriptions;

use App\Data\Subscribers\CreateSubscriberResultData;
use App\Models\PriceSubscription;
use Spatie\LaravelData\Data;

class SubscribeResultData extends Data
{
    public function __construct(
        public readonly CreateSubscriberResultData $createSubscriberResultData,
        public readonly PriceSubscription $priceSubscription
    ) {}
}
