<?php

declare(strict_types=1);

namespace App\Data\Subscribers;

use App\Models\Subscriber;
use Spatie\LaravelData\Data;

class CreateSubscriberResultData extends Data
{
    public function __construct(
        public readonly Subscriber $subscriber,
        public readonly bool $isVerificationEmailSent
    ) {}
}
