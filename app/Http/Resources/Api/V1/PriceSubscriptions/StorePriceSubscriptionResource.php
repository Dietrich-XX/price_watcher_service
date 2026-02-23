<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1\PriceSubscriptions;

use App\Data\PriceSubscriptions\SubscribeResultData;
use App\Http\Resources\Api\V1\Subscriber\SubscriberResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StorePriceSubscriptionResource extends JsonResource
{
    public function __construct(SubscribeResultData $resource)
    {
        parent::__construct($resource);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        /** @var SubscribeResultData $subscribeResultData */
        $subscribeResultData = $this->resource;
        $subscriber = $subscribeResultData->createSubscriberResultData->subscriber;
        $priceSubscription = $subscribeResultData->priceSubscription;

        return [
            'subscriber' => new SubscriberResource($subscriber),
            'price_subscription' => new PriceSubscriptionResource($priceSubscription),
            'message' => $subscriber->is_verified
                ? "You are successfully tracking price changes for the specified URL: $priceSubscription->url."
                : "You are successfully tracking price changes for the specified URL: $priceSubscription->url,
                    however to receive notifications about changes you need to verify your email.
                    A verification email has been sent to your email address."
        ];
    }
}
