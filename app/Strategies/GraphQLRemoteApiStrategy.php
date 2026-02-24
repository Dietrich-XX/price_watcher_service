<?php

declare(strict_types=1);

namespace App\Strategies;

use App\Interfaces\Strategies\PriceTrackerStrategyInterface;

class GraphQLRemoteApiStrategy implements PriceTrackerStrategyInterface
{
    public function trackPrice(string $url): void
    {
        // TODO: Implement trackPrice() method.
    }
}
