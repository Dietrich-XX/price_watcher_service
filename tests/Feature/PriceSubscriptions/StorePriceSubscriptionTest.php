<?php

declare(strict_types=1);

namespace Feature\PriceSubscriptions;

use App\Jobs\EmailVerifications\SendEmailVerificationJob;
use App\Models\Subscriber;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

class StorePriceSubscriptionTest extends BaseTestCase
{
    use DatabaseTransactions;

    /**
     * @return void
     */
    public function test_create_subscription_and_sends_verification_email(): void
    {
        $url = 'https://m.olx.ua/d/uk/obyavlenie/example';
        $email = 'test@example.com';

        Queue::fake();

        $response = $this->postJson(route('api.v1.subscriptions.store'),[
            'url' => $url,
            'email' => $email
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('subscribers', [
            'email' => $email,
            'is_verified' => false,
        ]);

        $this->assertDatabaseHas('price_subscriptions', [
            'url' => $url,
            'current_price' => null,
            'last_checked_at' => null
        ]);

        $subscriberId = $response->json('data.subscriber.id');
        $subscriptionId = $response->json('data.price_subscription.id');

        $this->assertDatabaseHas('price_subscription_subscriber', [
            'subscriber_id' => $subscriberId,
            'price_subscription_id' => $subscriptionId,
        ]);

        Queue::assertPushed(SendEmailVerificationJob::class);
    }

    /**
     * @return void
     */
    public function test_verification_email_is_sent_only_once_for_same_unverified_subscriber(): void
    {
        $url = 'https://m.olx.ua/d/uk/obyavlenie/example1';
        $email = 'test@example.com';

        Queue::fake();

        $this->postJson(route('api.v1.subscriptions.store'), [
            'url' => $url,
            'email' => $email,
        ])->assertOk();

        $url = 'https://m.olx.ua/d/uk/obyavlenie/example2';

        $this->postJson(route('api.v1.subscriptions.store'), [
            'url' => $url,
            'email' => $email,
        ])->assertOk();

        $this->assertDatabaseCount('subscribers', 1);

        $this->assertDatabaseCount('price_subscriptions', 2);
        $this->assertDatabaseCount('price_subscription_subscriber', 2);

        Queue::assertPushed(SendEmailVerificationJob::class, 1);
    }

    /**
     * @return void
     */
    public function test_store_subscription_returns_validation_errors_when_data_is_invalid(): void
    {
        Queue::fake();

        $response = $this->postJson(route('api.v1.subscriptions.store'), [
            'url' => null,
            'email' => 'testexample.com',
        ]);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors([
            'url',
            'email',
        ]);

        $this->assertDatabaseCount('subscribers', 0);
        $this->assertDatabaseCount('price_subscriptions', 0);

        Queue::assertNothingPushed();
    }

    /**
     * @return void
     */
    public function test_store_subscription_returns_validation_errors_when_not_olx_url(): void
    {
        Queue::fake();

        $response = $this->postJson(route('api.v1.subscriptions.store'), [
            'url' => 'https://www.google.com',
            'email' => 'testexample.com',
        ]);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors([
            'url' => 'The url must be an olx.ua URL.',
            'email'
        ]);

        $this->assertDatabaseCount('subscribers', 0);
        $this->assertDatabaseCount('price_subscriptions', 0);

        Queue::assertNothingPushed();
    }

    /**
     * @return void
     */
    public function test_store_subscription_returns_validation_errors_when_olx_url_is_not_trackable(): void
    {
        Queue::fake();

        $response = $this->postJson(route('api.v1.subscriptions.store'), [
            'url' => 'https://m.olx.ua/d/uk/categories',
            'email' => 'testexample.com',
        ]);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors([
            'url' => 'The url must be a valid OLX tracking URL.',
            'email'
        ]);

        $this->assertDatabaseCount('subscribers', 0);
        $this->assertDatabaseCount('price_subscriptions', 0);

        Queue::assertNothingPushed();
    }

    /**
     * @return void
     */
    public function test_store_subscription_does_not_send_verification_email_when_subscriber_already_verified(): void
    {
        $url = 'https://m.olx.ua/d/uk/obyavlenie/example';

        Queue::fake();

        /** @var Subscriber $subscriber */
        $subscriber = Subscriber::factory()->verified()->create();

        $response = $this->postJson(route('api.v1.subscriptions.store'), [
            'url' => $url,
            'email' => $subscriber->email
        ]);

        $response->assertOk();

        $response->assertJson([
            'data' => [
                'subscriber' => [
                    'id' => $subscriber->id,
                    'email' => $subscriber->email,
                    'is_verified' => true,
                ],
                'price_subscription' => [
                    'url' => $url,
                ],
                'message' => "You are successfully tracking price changes for the specified URL: $url."
            ]
        ]);

        $this->assertDatabaseHas('price_subscriptions', [
            'url' => $url
        ]);

        $priceSubscriptionId = $response->json('data.price_subscription.id');

        $this->assertDatabaseHas('price_subscription_subscriber', [
            'subscriber_id' => $subscriber->id,
            'price_subscription_id' => $priceSubscriptionId
        ]);

        Queue::assertNothingPushed();
    }
}
