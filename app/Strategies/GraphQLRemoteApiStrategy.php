<?php

declare(strict_types=1);

namespace App\Strategies;

use App\Exceptions\PriceTrackerException;
use App\Interfaces\Strategies\PriceTrackerStrategyInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Log;

readonly class GraphQLRemoteApiStrategy implements PriceTrackerStrategyInterface
{
    public function __construct(
        protected PendingRequest $OlxGraphqlClient,
        protected string $baseUrl
    ) {}

    /**
     * Tracks the price of an item by querying OLX via GraphQL
     *
     * @param string $url
     * @return float
     * @throws PriceTrackerException
     * @throws ConnectionException
     */
    public function trackPrice(string $url): float // Самое близкое решение которое я придумал, лучше всего работает только если название объявления написанно на английском, в таком случае слаг будет максимально похож на название
    {
        $searchQuery = $this->extractQueryFromUrl($url);

        $graphqlPayload = [
            'query' => '
                query ListingSearchQuery($searchParameters: [SearchParameter!] = []) {
                    clientCompatibleListings(searchParameters: $searchParameters) {
                        ... on ListingSuccess {
                            data {
                                id
                                title
                                params {
                                    key
                                    value {
                                        __typename
                                        ... on PriceParam {
                                            value
                                            currency
                                            label
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            ',
            'variables' => [
                'searchParameters' => [
                    ['key' => 'offset', 'value' => '0'],
                    ['key' => 'limit', 'value' => '1'],
                    ['key' => 'query', 'value' => $searchQuery]
                ],
            ],
        ];

        $response = $this->OlxGraphqlClient->post($this->baseUrl, $graphqlPayload);

        if (!$response->successful()) {
            throw PriceTrackerException::serverResponseError($response->getStatusCode());
        }

        $data = $response->json();

        if (empty($data['data']['clientCompatibleListings']['data'][0]['params'])) {
            throw PriceTrackerException::priceNotFound();
        }

        foreach ($data['data']['clientCompatibleListings']['data'][0]['params'] as $param) {
            if ($param['key'] === 'price' && isset($param['value']['value'])) {
                return (float) $param['value']['value'];
            }
        }

        throw PriceTrackerException::priceNotFound();
    }

    /**
     * Extract search query from OLX URL
     *
     * @param string $url
     * @return string
     */
    protected function extractQueryFromUrl(string $url): string
    {

        $path = parse_url($url, PHP_URL_PATH);
        $segments = explode('/', trim($path, '/'));

        $slug = end($segments);

        $slug = preg_replace('/-ID.*\.html$/', '', $slug); // удаляем -IDXXXX.html

        return str_replace('-', ' ', $slug);
    }
}
