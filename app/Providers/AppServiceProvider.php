<?php

declare(strict_types=1);

namespace App\Providers;

use App\Interfaces\Repositories\PriceSubscriptionRepositoryInterface;
use App\Interfaces\Repositories\SubscriberRepositoryInterface;
use App\Interfaces\Services\EmailVerifications\EmailVerificationSenderInterface;
use App\Interfaces\Services\EmailVerifications\EmailVerifierInterface;
use App\Interfaces\Services\PriceSubscriptionServiceInterface;
use App\Interfaces\Services\SubscriberServiceInterface;
use App\Repositories\PriceSubscriptionRepository;
use App\Repositories\SubscriberRepository;
use App\Services\EmailVerificationService;
use App\Services\PriceSubscriptionService;
use App\Services\SubscriberService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(PriceSubscriptionServiceInterface::class, PriceSubscriptionService::class);
        $this->app->bind(SubscriberServiceInterface::class, SubscriberService::class);
        $this->app->bind(EmailVerificationSenderInterface::class, EmailVerificationService::class);
        $this->app->bind(EmailVerifierInterface::class, EmailVerificationService::class);

        $this->app->bind(PriceSubscriptionRepositoryInterface::class, PriceSubscriptionRepository::class);
        $this->app->bind(SubscriberRepositoryInterface::class, SubscriberRepository::class);
    }

    /**
     * @return void
     */
    public function boot(): void
    {
        //
    }
}
