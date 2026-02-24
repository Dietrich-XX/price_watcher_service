<?php

declare(strict_types=1);

namespace App\Jobs\EmailVerifications;

use App\Interfaces\Services\EmailVerifications\EmailVerificationSenderInterface;
use App\Models\Subscriber;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * This job is unique for each subscriber for 5 minutes. If the same job is dispatched again
 * within 5 minutes, it will not be pushed to the queue
 */
class SendEmailVerificationJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of seconds the job should remain unique
     *
     * @var int
     */
    public int $uniqueFor = 300;

    public function __construct(protected readonly Subscriber $subscriber)
    {}

    /**
     * The subscriber ID is used to guarantee uniqueness for job
     *
     * @return string
     */
    public function uniqueId(): string
    {
        return (string) $this->subscriber->id;
    }

    /**
     * Execute the job to send verification to the subscriber
     *
     * @param EmailVerificationSenderInterface $emailVerificationSender
     * @return void
     */
    public function handle(EmailVerificationSenderInterface $emailVerificationSender): void
    {
        $emailVerificationSender->sendVerification($this->subscriber);
    }
}
