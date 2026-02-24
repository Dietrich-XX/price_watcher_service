<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\PriceTrackerException;
use App\Interfaces\Repositories\PriceSubscriptionRepositoryInterface;
use App\Interfaces\Services\PriceTrackerInterface;
use App\Interfaces\Strategies\PriceTrackerStrategyInterface;
use App\Models\PriceSubscription;

class PriceTracker implements PriceTrackerInterface
{
    public function __construct(
        protected PriceTrackerStrategyInterface $priceTrackerStrategy,
        protected PriceSubscriptionRepositoryInterface $priceSubscriptionRepository
    ) {}

    /**
     * @param PriceSubscription $priceSubscription
     * @return bool
     * @throws PriceTrackerException
     */
    public function shouldNotifyPriceChanged(PriceSubscription $priceSubscription): bool
    {
        $trackedPrice = $this->priceTrackerStrategy->trackPrice($priceSubscription->url);

        if ($trackedPrice !== $priceSubscription->current_price) {
            $this->priceSubscriptionRepository->updateCurrentPrice($priceSubscription, $trackedPrice);
            return true;
        }

        return false;
    }
}
