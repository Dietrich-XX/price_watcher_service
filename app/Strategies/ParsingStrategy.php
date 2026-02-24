<?php

declare(strict_types=1);

namespace App\Strategies;

use App\Exceptions\PriceTrackerException;
use App\Interfaces\Strategies\PriceTrackerStrategyInterface;
use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Symfony\Component\DomCrawler\Crawler;

readonly class ParsingStrategy implements PriceTrackerStrategyInterface
{
    public function __construct(protected PendingRequest $htmlParserClient)
    {}

    public function trackPrice(string $url): string
    {
        $response = $this->htmlParserClient->get($url);

        if (!$response->successful()) {
            throw PriceTrackerException::serverResponseError($response->getStatusCode());
        }

        $crawler = $this->createCrawler($response);

        $priceNode = $crawler
            ->filter('[data-testid="prices-wrapper"] h3')
            ->first();

        if ($priceNode->count() === 0) {
            throw PriceTrackerException::priceNotFound();
        }

        $priceText = $priceNode->text();

        $priceClean = rtrim($priceText, '.');

        if (empty($priceClean)) {
            throw new Exception('Цена некорректна');
        }

        return $priceClean;
    }

    /**
     * Create Crawler for html parsing
     *
     * @param Response $response
     * @return Crawler
     */
    protected function createCrawler(Response $response): Crawler
    {
        return new Crawler($response->body());
    }
}
