<?php

declare(strict_types=1);

namespace App\Interfaces\Services\EmailVerifications;

use App\Models\Subscriber;

interface EmailVerificationSenderInterface
{
    /**
     * @param Subscriber $subscriber
     * @return void
     */
    public function sendVerification(Subscriber $subscriber): void;
}
