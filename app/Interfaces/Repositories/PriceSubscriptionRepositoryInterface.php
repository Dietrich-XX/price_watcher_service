<?php

declare (strict_types = 1);

namespace App\Interfaces\Repositories;

use App\Models\PriceSubscription;

interface PriceSubscriptionRepositoryInterface
{
    /**
     * @param string $url
     * @return PriceSubscription
     */
    public function findOrCreateByUrl(string $url): PriceSubscription;
}
