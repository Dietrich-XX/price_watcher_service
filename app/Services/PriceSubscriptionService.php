<?php

declare(strict_types=1);

namespace App\Services;

use App\Data\PriceSubscriptions\StorePriceSubscriptionData;
use App\Data\PriceSubscriptions\SubscribeResultData;
use App\Interfaces\Repositories\PriceSubscriptionRepositoryInterface;
use App\Interfaces\Services\PriceSubscriptionServiceInterface;
use App\Interfaces\Services\SubscriberServiceInterface;
use Illuminate\Support\Facades\DB;
use Throwable;

readonly class PriceSubscriptionService implements PriceSubscriptionServiceInterface
{
    public function __construct(
        protected SubscriberServiceInterface $subscriberService,
        protected PriceSubscriptionRepositoryInterface $priceSubscriptionRepository
    ) {}

    /**
     * Creates or retrieves a subscriber by email, creates or retrieves a price subscription by URL,
     * attaches them without detaching existing relations
     *
     * @param StorePriceSubscriptionData $storePriceSubscriptionData
     * @return SubscribeResultData
     * @throws Throwable
     */
    public function subscribe(StorePriceSubscriptionData $storePriceSubscriptionData): SubscribeResultData
    {
        return DB::transaction(function () use ($storePriceSubscriptionData) {
            $createSubscriberResultData = $this->subscriberService->createAndVerify($storePriceSubscriptionData->email);

            $priceSubscription = $this->priceSubscriptionRepository->findOrCreateByUrl($storePriceSubscriptionData->url);

            $createSubscriberResultData->subscriber->priceSubscriptions()->syncWithoutDetaching([$priceSubscription->id]);

            return new SubscribeResultData(
                createSubscriberResultData: $createSubscriberResultData,
                priceSubscription: $priceSubscription
            );
        });
    }
}
