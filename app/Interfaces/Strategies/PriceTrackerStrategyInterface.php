<?php

declare(strict_types=1);

namespace App\Interfaces\Strategies;

interface PriceTrackerStrategyInterface
{
    public function trackPrice(string $url): void;
}
