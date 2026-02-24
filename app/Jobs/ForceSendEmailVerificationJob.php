<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Interfaces\Services\EmailVerifications\EmailVerificationSenderInterface;
use App\Models\Subscriber;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ForceSendEmailVerificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected readonly Subscriber $subscriber)
    {}

    /**
     * Execute the job to send an email verification to the subscriber
     *
     * @param EmailVerificationSenderInterface $emailVerificationSender
     * @return void
     */
    public function handle(EmailVerificationSenderInterface $emailVerificationSender): void
    {
        $emailVerificationSender->sendVerification($this->subscriber);
    }
}
