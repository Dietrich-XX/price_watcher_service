<?php

declare(strict_types=1);

namespace App\Jobs\TrackPrices;

use App\Exceptions\PriceTrackerException;
use App\Interfaces\Repositories\PriceSubscriptionRepositoryInterface;
use App\Interfaces\Services\PriceTrackerInterface;
use App\Jobs\NotifyPriceChanged\NotifyPriceChangedJob;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class TrackPricesBatchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public function __construct(readonly protected array $priceSubscriptionIds)
    {}

    /**
     * Process a list of price subscriptions and dispatch notification jobs for those where the price has changed
     *
     * @param PriceSubscriptionRepositoryInterface $priceSubscriptionRepository
     * @param PriceTrackerInterface $priceTracker
     * @return void
     * @throws Throwable
     */
    public function handle(PriceSubscriptionRepositoryInterface $priceSubscriptionRepository, PriceTrackerInterface $priceTracker): void
    {
        try {
            $priceSubscriptions = $priceSubscriptionRepository->findByIds($this->priceSubscriptionIds);

            foreach ($priceSubscriptions as $priceSubscription) {
                try {                                                           // Не фанат везде пихать try catch но тут он нужен что бы в случае если мы не нашли цену например, алгоритм будет продолжать проход по колекции
                    if ($priceTracker->shouldNotifyPriceChanged($priceSubscription)) {
                        NotifyPriceChangedJob::dispatch($priceSubscription);    // При запуске джобы, она получит уже обновленную priceSubscription поэтому тут можно не делать refresh() для сущности
                    }
                } catch (PriceTrackerException $exception) {
                    Log::warning('Price tracking skipped for subscription', [
                        'subscription_id' => $priceSubscription->id,
                        'message' => $exception->getMessage(),
                        'code' => $exception->getCode(),
                    ]);
                }
            }
        } catch (Throwable $exception) {
            Log::error('Price subscription batch job failed', [ // Логируем ошибку с информацией о батче в случае падения
                'exception' => $exception,
                'subscription_ids' => $this->priceSubscriptionIds,
            ]);

            throw $exception;
        }
    }
}
