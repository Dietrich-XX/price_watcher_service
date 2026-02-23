<?php

declare(strict_types=1);

namespace App\Http\Api\Actions\V1\PriceSubscriptions;

use App\Data\PriceSubscriptions\StorePriceSubscriptionData;
use App\Http\Requests\Api\V1\PriceSubscriptions\StorePriceSubscriptionRequest;
use App\Http\Resources\Api\V1\PriceSubscriptions\StorePriceSubscriptionResource;
use App\Interfaces\Services\PriceSubscriptionServiceInterface;

readonly class StorePriceSubscriptionAction
{
    public function __construct(protected PriceSubscriptionServiceInterface $priceSubscriptionService)
    {}

    /**
     * @param StorePriceSubscriptionRequest $storePriceSubscriptionRequest
     * @return StorePriceSubscriptionResource
     */
    public function __invoke(StorePriceSubscriptionRequest $storePriceSubscriptionRequest): StorePriceSubscriptionResource
    {
        /** @var StorePriceSubscriptionData $storePriceSubscriptionData */
        $storePriceSubscriptionData = $storePriceSubscriptionRequest->getDto();

        $subscribeResultData = $this->priceSubscriptionService->subscribe($storePriceSubscriptionData);

        return new StorePriceSubscriptionResource($subscribeResultData);
    }
}
