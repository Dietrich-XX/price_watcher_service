<?php

declare(strict_types=1);

namespace App\Interfaces\Services;

use App\Exceptions\PriceTrackerException;
use App\Models\PriceSubscription;

interface PriceTrackerInterface
{
    /**
     * @param PriceSubscription $priceSubscription
     * @return bool
     * @throws PriceTrackerException
     */
    public function shouldNotifyPriceChanged(PriceSubscription $priceSubscription): bool;
}
