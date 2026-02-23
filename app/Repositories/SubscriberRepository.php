<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\Repositories\SubscriberRepositoryInterface;
use App\Models\Subscriber;

class SubscriberRepository extends AbstractEntityRepository implements SubscriberRepositoryInterface
{
    public function __construct(Subscriber $subscriber)
    {
        parent::__construct($subscriber);
    }

    /**
     * @param string $email
     * @return Subscriber|null
     */
    public function findByEmail(string $email): ?Subscriber
    {
        return $this->entity()
            ->where('email', $email)
            ->first();
    }

    /**
     * Find subscriber by email or create a new one if not exists
     *
     * @param string $email
     * @return Subscriber
     */
    public function findOrCreateByEmail(string $email): Subscriber
    {
        return $this->entity()->firstOrCreate(
            ['email' => $email],
            ['is_verified' => false]
        );
    }

    /**
     * @param Subscriber $subscriber
     * @return Subscriber
     */
    public function confirm(Subscriber $subscriber): Subscriber
    {
        return tap($subscriber, function (Subscriber $subscriber) {
            $subscriber->update([
                'is_verified' => true
            ]);
        });
    }
}
