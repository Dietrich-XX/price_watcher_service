<?php

declare(strict_types=1);

namespace App\Interfaces\Strategies;

use App\Exceptions\PriceTrackerException;

interface PriceTrackerStrategyInterface
{
    /**
     * @param string $url
     * @return string
     * @throws PriceTrackerException
     */
    public function trackPrice(string $url): string;
}
