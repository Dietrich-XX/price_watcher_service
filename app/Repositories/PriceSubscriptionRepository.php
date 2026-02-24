<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\Repositories\PriceSubscriptionRepositoryInterface;
use App\Models\PriceSubscription;
use Illuminate\Support\Collection;

class PriceSubscriptionRepository extends AbstractEntityRepository implements PriceSubscriptionRepositoryInterface
{
    public function __construct(protected PriceSubscription $priceSubscription)
    {
        parent::__construct($priceSubscription);
    }

    /**
     * Find price subscription by url or create a new one if not exists
     *
     * @param string $url
     * @return PriceSubscription
     */
    public function findOrCreateByUrl(string $url): PriceSubscription
    {
        return $this->entity()->firstOrCreate(
            ['url' => $url]
        );
    }

    /**
     * Retrieve IDs of all price subscriptions
     *
     * @return Collection
     */
    public function getAllIds(): Collection
    {
        return $this->entity()->pluck('id');
    }

    /**
     * Find price subscription by IDs
     *
     * @param array $priceSubscriptionIds
     * @return Collection
     */
    public function findByIds(array $priceSubscriptionIds): Collection
    {
        return $this->entity()->whereIn('id', $priceSubscriptionIds)->get();
    }

    /**
     * Get all subscriber IDs for a given price subscription
     *
     * @param PriceSubscription $priceSubscription
     * @return Collection
     */
    public function getSubscriberIdsByPriceSubscription(PriceSubscription $priceSubscription): Collection
    {
        return $priceSubscription->subscribers()->get()->pluck('id');
    }

    /**
     * Update the current price and last checked timestamp for a subscription
     *
     * @param PriceSubscription $priceSubscription
     * @param float $newPrice
     * @return PriceSubscription
     */
    public function updateCurrentPrice(PriceSubscription $priceSubscription, float $newPrice): PriceSubscription
    {
        return tap($priceSubscription, function (PriceSubscription $priceSubscription) use ($newPrice) {
            $priceSubscription->update([
                'current_price' => $newPrice,
                'last_checked_at' => now()
            ]);
        });
    }
}
