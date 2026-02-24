<?php

declare(strict_types=1);

namespace App\Providers;

use App\Enums\PriceTrackingStrategyEnum;
use App\Interfaces\Repositories\PriceSubscriptionRepositoryInterface;
use App\Interfaces\Repositories\SubscriberRepositoryInterface;
use App\Interfaces\Services\EmailVerifications\EmailVerificationSenderInterface;
use App\Interfaces\Services\EmailVerifications\EmailVerifierInterface;
use App\Interfaces\Services\PriceSubscriptionServiceInterface;
use App\Interfaces\Services\PriceTrackerInterface;
use App\Interfaces\Services\SubscriberServiceInterface;
use App\Interfaces\Strategies\PriceTrackerStrategyInterface;
use App\Repositories\PriceSubscriptionRepository;
use App\Repositories\SubscriberRepository;
use App\Services\EmailVerificationService;
use App\Services\PriceSubscriptionService;
use App\Services\PriceTracker;
use App\Services\SubscriberService;
use App\Strategies\GraphQLRemoteApiStrategy;
use App\Strategies\ParsingStrategy;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;
use RuntimeException;

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

        $this->app->bind(PriceTrackerInterface::class, PriceTracker::class);

        $this->app->singleton(PriceTrackerStrategyInterface::class, function ($app) {
            return match (config('app.price_tracking_strategy')) {
                PriceTrackingStrategyEnum::GRAPHQL_REMOTE_API => $app->make(GraphQLRemoteApiStrategy::class),
                PriceTrackingStrategyEnum::PARSING => $app->make(ParsingStrategy::class),
                default => throw new RuntimeException('Unknown price tracking strategy: ' . config('app.price_tracking_strategy'))
            };
        });

        $this->app->bind(GraphQLRemoteApiStrategy::class, function () {
            $client = Http::withHeaders([
                'Content-Type'    => 'application/json',
                'Accept'          => 'application/json',
                'Accept-Encoding' => 'gzip, deflate, br',
                'Connection'      => 'keep-alive',
                'Host'            => 'www.olx.ua',
                'Origin'          => 'https://m.olx.ua',
                'Referer'         => 'https://m.olx.ua/',
                'User-Agent'      => 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1',
                'x-client'        => 'MWEB',
            ])->timeout(10);

            return new GraphQLRemoteApiStrategy(
                olxGraphqlClient: $client,
                baseUrl: config('app.graphql_remote_api_base_url', 'https://m.olx.ua/apigateway/graphql'),
            );
        });

        $this->app->bind(ParsingStrategy::class, function () {
            $client = Http::withHeaders([])->timeout(10);
            return new ParsingStrategy($client);
        });

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
