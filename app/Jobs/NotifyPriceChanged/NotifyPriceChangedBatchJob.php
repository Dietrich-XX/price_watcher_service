<?php

declare(strict_types=1);

namespace App\Jobs\NotifyPriceChanged;

use App\Interfaces\Repositories\SubscriberRepositoryInterface;
use App\Mail\PriceChangedMail;
use App\Models\PriceSubscription;
use App\Models\Subscriber;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class NotifyPriceChangedBatchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public function __construct(
        readonly protected PriceSubscription $priceSubscription,
        readonly protected array $subscriberIds
    ) {}

    /**
     * Send price change notification emails to a batch of subscribers
     *
     * @param SubscriberRepositoryInterface $subscriberRepository
     * @return void
     * @throws Throwable
     */
    public function handle(SubscriberRepositoryInterface $subscriberRepository): void
    {
        try {
            /** @var Collection<Subscriber> $subscribers */
            $subscribers = $subscriberRepository->findByIds($this->subscriberIds);

            foreach ($subscribers as $subscriber) {
                if ($subscriber->is_verified) {
                    Mail::to($subscriber->email)->send(new PriceChangedMail($this->priceSubscription));
                }
            }
        } catch (Throwable $exception) {
            Log::error('Notify price changed batch job failed', [ // Логируем ошибку с информацией о батче в случае падения
                'exception' => $exception,
                'price_subscription_id' => $this->priceSubscription->id,
                'subscription_ids' => $this->subscriberIds
            ]);

            throw $exception;
        }
    }
}
