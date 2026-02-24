<?php

declare(strict_types=1);

namespace App\Jobs\TrackPrices;

use App\Interfaces\Repositories\PriceSubscriptionRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Throwable;

class TrackPricesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     *  Retrieves all price subscription IDs from the repository, splits them into chunks of 100, and dispatches
     * a batch of TrackPricesBatchJob jobs for processing
     *
     * @param PriceSubscriptionRepositoryInterface $priceSubscriptionRepository
     * @return void
     * @throws Throwable
     */
    public function handle(PriceSubscriptionRepositoryInterface $priceSubscriptionRepository): void
    {
        $priceSubscriptionIds = $priceSubscriptionRepository->getAllIds();

        $chunks = array_chunk($priceSubscriptionIds->toArray(), 100);

        $jobs = [];

        foreach ($chunks as $chunk) {
            $jobs[] = new TrackPricesBatchJob($chunk);  // Предположим что у нас большое количество страниц за которыми нужно следить, подобная оптимизация никогда не бывает лишней
        }

        Bus::batch($jobs)
            ->name('Price subscription tracking')
            ->dispatch();
    }
}
