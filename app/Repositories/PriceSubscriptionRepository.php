<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\Repositories\PriceSubscriptionRepositoryInterface;
use App\Models\PriceSubscription;

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
}
