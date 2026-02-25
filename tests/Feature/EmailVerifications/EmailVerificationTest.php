<?php

declare(strict_types=1);

namespace Feature\EmailVerifications;

use App\Jobs\EmailVerifications\ForceSendEmailVerificationJob;
use App\Mail\EmailVerificationMail;
use App\Models\Subscriber;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;

class EmailVerificationTest extends BaseTestCase
{
    use DatabaseTransactions;

    /**
     * @return void
     */
    public function test_subscription_sends_verification_email_and_can_be_verified(): void
    {
        $url = 'https://m.olx.ua/d/uk/obyavlenie/example';
        $email = 'test@example.com';

        Mail::fake();
        Cache::flush();

        $response = $this->postJson(route('api.v1.subscriptions.store'), [
            'url' => $url,
            'email' => $email,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('subscribers', [
            'email' => $email,
            'is_verified' => false
        ]);

        Mail::assertSent(EmailVerificationMail::class, function ($mail) use ($email, &$token) {
            $token = $mail->verificationToken;
            return $mail->hasTo($email);
        });

        $verifyResponse = $this->getJson(route('web.subscribers.email-verification', ['token' => $token]));
        $verifyResponse->assertOk();

        $this->assertDatabaseHas('subscribers', [
            'email' => $email,
            'is_verified' => true
        ]);
    }

    /**
     * @return void
     */
    public function test_force_send_verification_email_dispatches_job_for_unverified_subscriber(): void
    {
        Queue::fake();

        /** @var Subscriber $subscriber */
        $subscriber = Subscriber::factory()->create();

        $response = $this->postJson(route('api.v1.subscribers.force-email-verification', [
            'subscriber' => $subscriber->id,
        ]));

        $response->assertOk();

        Queue::assertPushed(ForceSendEmailVerificationJob::class);
    }

    /**
     * @return void
     */
    public function test_force_send_verification_email_does_not_dispatch_job_for_verified_subscriber(): void
    {
        Queue::fake();

        /** @var Subscriber $subscriber */
        $subscriber = Subscriber::factory()->verified()->create();

        $response = $this->postJson(route('api.v1.subscribers.force-email-verification', [
            'subscriber' => $subscriber->id
        ]));

        $response->assertOk();

        Queue::assertNothingPushed();
    }

    /**
     * @return void
     */
    public function test_force_send_verification_email_dispatches_more_than_once(): void
    {
        Queue::fake();

        /** @var Subscriber $subscriber */
        $subscriber = Subscriber::factory()->create();

        $this->postJson(route('api.v1.subscribers.force-email-verification', [
            'subscriber' => $subscriber->id,
        ]))->assertOk();

        $this->postJson(route('api.v1.subscribers.force-email-verification', [
            'subscriber' => $subscriber->id,
        ]))->assertOk();

        Queue::assertPushed(ForceSendEmailVerificationJob::class, 2);
    }

    /**
     * @return void
     */
    public function test_force_send_verification_email_returns_404_for_non_existing_subscriber(): void
    {
        Queue::fake();

        $nonExistingId = 999999;

        $response = $this->postJson(route('api.v1.subscribers.force-email-verification', [
            'subscriber' => $nonExistingId,
        ]));

        $response->assertStatus(404);

        Queue::assertNothingPushed();
    }
}
