<?php

declare (strict_types = 1);

namespace App\Interfaces\Repositories;

use App\Models\PriceSubscription;
use Illuminate\Support\Collection;

interface PriceSubscriptionRepositoryInterface
{
    /**
     * @param string $url
     * @return PriceSubscription
     */
    public function findOrCreateByUrl(string $url): PriceSubscription;

    /**
     * @return Collection
     */
    public function getAllIds(): Collection;

    /**
     * @param array $priceSubscriptionIds
     * @return Collection
     */
    public function findByIds(array $priceSubscriptionIds): Collection;

    /**
     * @param PriceSubscription $priceSubscription
     * @return Collection
     */
    public function getSubscriberIdsByPriceSubscription(PriceSubscription $priceSubscription): Collection;
}
