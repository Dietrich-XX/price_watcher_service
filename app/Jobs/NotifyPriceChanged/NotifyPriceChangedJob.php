<?php

declare(strict_types=1);

namespace App\Jobs\NotifyPriceChanged;

use App\Models\PriceSubscription;
use App\Repositories\PriceSubscriptionRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Throwable;

class NotifyPriceChangedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(readonly protected PriceSubscription $priceSubscription)
    {}

    /**
     * Dispatch a batch of jobs to notify subscribers about a price change
     *
     * @param PriceSubscriptionRepository $priceSubscriptionRepository
     * @return void
     * @throws Throwable
     */
    public function handle(PriceSubscriptionRepository $priceSubscriptionRepository): void
    {
        $jobs = [];

        $subscriberIds = $priceSubscriptionRepository->getSubscriberIdsByPriceSubscription($this->priceSubscription);

        $chunks = array_chunk($subscriberIds->toArray(), 100);

        foreach ($chunks as $chunk) {
            $jobs[] = new NotifyPriceChangedBatchJob(
                priceSubscription: $this->priceSubscription,
                subscriberIds: $chunk
            );
        }

        Bus::batch($jobs)
            ->name('Notify subscribers about price change')
            ->dispatch();
    }
}
