<?php

declare(strict_types=1);

namespace App\Http\Actions\Api\V1\EmailVerifications;

use App\Http\Resources\Api\V1\EmailVerifications\ResendVerificationEmailResource;
use App\Jobs\EmailVerifications\ForceSendEmailVerificationJob;
use App\Models\Subscriber;

readonly class ResendVerificationEmailAction
{
    /**
     * @param Subscriber $subscriber
     * @return ResendVerificationEmailResource
     */
    public function __invoke(Subscriber $subscriber): ResendVerificationEmailResource
    {
        if (!$subscriber->is_verified) {
            ForceSendEmailVerificationJob::dispatch($subscriber);
        }

        return new ResendVerificationEmailResource($subscriber);
    }
}
