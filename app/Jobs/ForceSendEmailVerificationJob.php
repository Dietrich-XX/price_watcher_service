<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Mail\EmailVerificationMail;
use App\Models\Subscriber;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ForceSendEmailVerificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected readonly Subscriber $subscriber)
    {}

    /**
     * Execute the job to send an email verification to the subscriber
     *
     * @return void
     */
    public function handle(): void
    {
        Mail::to($this->subscriber->email)->send(new EmailVerificationMail($this->subscriber));
    }
}
