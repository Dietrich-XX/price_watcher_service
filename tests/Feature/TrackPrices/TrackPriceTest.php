<?php

declare(strict_types=1);

namespace Feature\TrackPrices;

use App\Enums\PriceTrackingStrategyEnum;
use App\Jobs\TrackPrices\TrackPricesJob;
use App\Mail\PriceChangedMail;
use App\Models\PriceSubscription;
use App\Models\Subscriber;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class TrackPriceTest extends BaseTestCase
{
    use DatabaseTransactions;

    /**
     * @return void
     */
    public function test_sends_price_change_email_to_verified_subscriber_via_parser_strategy(): void
    {
        Config::set('app.price_tracking_strategy', PriceTrackingStrategyEnum::PARSING);
        Mail::fake();

        [$priceSubscription, $subscriber] = $this->createPriceSubscriptionWithVerifiedSubscriber();

        TrackPricesJob::dispatch();

        Mail::assertSent(PriceChangedMail::class, fn($mail) => $mail->hasTo($subscriber->email));

        $this->assertPriceSubscriptionFieldsUpdated($priceSubscription);
    }

    /**
     * @return void
     */
    public function test_does_not_send_price_change_email_to_unverified_subscriber_via_parser_strategy(): void
    {
        Config::set('app.price_tracking_strategy', PriceTrackingStrategyEnum::PARSING);
        Mail::fake();

        [$priceSubscription, $subscriber] = $this->createPriceSubscriptionWithUnverifiedSubscriber();

        TrackPricesJob::dispatch();

        Mail::assertNotSent(PriceChangedMail::class, fn($mail) => $mail->hasTo($subscriber->email));

        $this->assertPriceSubscriptionFieldsUpdated($priceSubscription);
    }

    /**
     * @return void
     */
    public function test_sends_price_change_email_to_verified_subscriber_via_graphql_remote_api_strategy(): void
    {
        Config::set('app.price_tracking_strategy', PriceTrackingStrategyEnum::GRAPHQL_REMOTE_API);
        Mail::fake();

        [$priceSubscription, $subscriber] = $this->createPriceSubscriptionWithVerifiedSubscriber();

        TrackPricesJob::dispatch();

        Mail::assertSent(PriceChangedMail::class, fn($mail) => $mail->hasTo($subscriber->email));

        $this->assertPriceSubscriptionFieldsUpdated($priceSubscription);
    }

    /**
     * @return void
     */
    public function test_does_not_send_price_change_email_to_unverified_subscriber_via_graphql_remote_api_strategy(): void
    {
        Config::set('app.price_tracking_strategy', PriceTrackingStrategyEnum::GRAPHQL_REMOTE_API);
        Mail::fake();

        [$priceSubscription, $subscriber] = $this->createPriceSubscriptionWithUnverifiedSubscriber();

        TrackPricesJob::dispatch();

        Mail::assertNotSent(PriceChangedMail::class, fn($mail) => $mail->hasTo($subscriber->email));

        $this->assertPriceSubscriptionFieldsUpdated($priceSubscription);
    }

    /**
     * @return array
     */
    protected function createPriceSubscriptionWithVerifiedSubscriber(): array
    {
        // Плохая идея в тесте хардкодить такой url, когда обьявление пропадет тест сломается,
        // но зато мы сейчас можем точно проверить работу стратегий

        /** @var PriceSubscription $priceSubscription */
        $priceSubscription = PriceSubscription::factory()->onlyUrl()->create([
            'url' => 'https://www.olx.ua/d/uk/obyavlenie/audi-a6-c6-2-7-tdi-132kw-2005-rik-IDZWlWJ.html?search_reason=search%7Cpromoted',
        ]);

        /** @var Subscriber $subscriber */
        $subscriber = Subscriber::factory()->verified()->create();
        $priceSubscription->subscribers()->attach($subscriber->id);

        return [$priceSubscription, $subscriber];
    }

    /**
     * @return array
     */
    protected function createPriceSubscriptionWithUnverifiedSubscriber(): array
    {
        // Плохая идея в тесте хардкодить такой url, когда обьявление пропадет тест сломается,
        // но зато мы сейчас можем точно проверить работу стратегий

        /** @var PriceSubscription $priceSubscription */
        $priceSubscription = PriceSubscription::factory()->onlyUrl()->create([
            'url' => 'https://www.olx.ua/d/uk/obyavlenie/audi-a6-c6-2-7-tdi-132kw-2005-rik-IDZWlWJ.html?search_reason=search%7Cpromoted',
        ]);

        /** @var Subscriber $subscriber */
        $subscriber = Subscriber::factory()->create();
        $priceSubscription->subscribers()->attach($subscriber->id);

        return [$priceSubscription, $subscriber];
    }

    /**
     * @param PriceSubscription $priceSubscription
     * @return void
     */
    protected function assertPriceSubscriptionFieldsUpdated(PriceSubscription $priceSubscription): void
    {
        $this->assertDatabaseHas('price_subscriptions', ['id' => $priceSubscription->id]);
        $this->assertNotNull(DB::table('price_subscriptions')->where('id', $priceSubscription->id)->value('current_price'));
        $this->assertNotNull(DB::table('price_subscriptions')->where('id', $priceSubscription->id)->value('last_checked_at'));
    }
}
