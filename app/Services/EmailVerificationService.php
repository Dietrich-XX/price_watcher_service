<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\EmailVerificationException;
use App\Interfaces\Repositories\SubscriberRepositoryInterface;
use App\Interfaces\Services\EmailVerifications\EmailVerificationSenderInterface;
use App\Interfaces\Services\EmailVerifications\EmailVerifierInterface;
use App\Mail\EmailVerificationMail;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

readonly class EmailVerificationService implements EmailVerificationSenderInterface, EmailVerifierInterface
{
    public function __construct(protected SubscriberRepositoryInterface $subscriberRepository)
    {}

    /**
     * Send an email verification link to the given subscriber
     *
     * @param Subscriber $subscriber
     * @return void
     */
    public function sendVerification(Subscriber $subscriber): void
    {
        $verificationToken = $this->generateToken($subscriber);

        Mail::to($subscriber->email)->send(new EmailVerificationMail($verificationToken));
    }

    /**
     * Verify the subscriber's email using the given token
     *
     * @param string $verificationToken
     * @return bool
     * @throws EmailVerificationException
     */
    public function verify(string $verificationToken): bool
    {
        $subscriberId = (int) Cache::get("email_verification:$verificationToken")
            ?? throw EmailVerificationException::tokenNotFound();

        $subscriber = $this->subscriberRepository->findById($subscriberId)
            ?? throw EmailVerificationException::subscriberNotFound();

        $updatedSubscriber = $this->subscriberRepository->updateIsVerified(
            subscriber: $subscriber,
            value: true
        );

        if (!$updatedSubscriber->is_verified) {
            throw EmailVerificationException::verificationFailed();
        }

        Cache::forget("email_verification:$verificationToken");

        return $updatedSubscriber->is_verified;
    }

    /**
     * Generate a unique verification token for the subscriber
     * and store it in cache for 24 hours
     *
     * @param Subscriber $subscriber
     * @return string
     */
    protected function generateToken(Subscriber $subscriber): string
    {
        $verificationToken = Str::uuid()->toString();
        Cache::put("email_verification:$verificationToken", $subscriber->id, now()->addHours(24));

        return $verificationToken;
    }
}
