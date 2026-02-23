<?php

declare(strict_types=1);

namespace App\Interfaces\Services;

use App\Data\PriceSubscriptions\StorePriceSubscriptionData;
use App\Data\PriceSubscriptions\SubscribeResultData;

interface PriceSubscriptionServiceInterface
{
    /**
     * @param StorePriceSubscriptionData $storePriceSubscriptionData
     * @return SubscribeResultData
     */
    public function subscribe(StorePriceSubscriptionData $storePriceSubscriptionData): SubscribeResultData;
}
