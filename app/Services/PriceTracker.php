<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\Services\PriceTrackerInterface;
use App\Interfaces\Strategies\PriceTrackerStrategyInterface;
use App\Models\PriceSubscription;

class PriceTracker implements PriceTrackerInterface
{
    public function __construct(protected PriceTrackerStrategyInterface $priceTrackerStrategy)
    {}

    /**
     * @param PriceSubscription $priceSubscription
     * @return bool
     */
    public function shouldNotifyPriceChanged(PriceSubscription $priceSubscription): bool
    {
        return true;
    }
}
