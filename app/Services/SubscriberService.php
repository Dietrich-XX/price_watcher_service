<?php

declare(strict_types=1);

namespace App\Services;

use App\Data\Subscribers\CreateSubscriberResultData;
use App\Interfaces\Repositories\SubscriberRepositoryInterface;
use App\Interfaces\Services\SubscriberServiceInterface;
use App\Jobs\SendEmailVerificationJob;

readonly class SubscriberService implements SubscriberServiceInterface
{
    public function __construct(protected SubscriberRepositoryInterface $subscriberRepository)
    {}

    /**
     * Retrieves an existing subscriber by email or creates a new one.
     * Dispatches a verification email if the subscriber is not verified
     *
     * @param string $email
     * @return CreateSubscriberResultData
     */
    public function createAndVerify(string $email): CreateSubscriberResultData  // Не самый лучший нейминг для метода, хз как лучше назвать
    {
        $isEmailSent = false;
        $subscriber = $this->subscriberRepository->findOrCreateByEmail($email);

        if ($subscriber->is_verified === false) {
            SendEmailVerificationJob::dispatch($subscriber);
            $isEmailSent = true;
        }

        return new CreateSubscriberResultData(
            subscriber: $subscriber,
            isVerificationEmailSent: $isEmailSent
        );
    }
}
