<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\Repositories\SubscriberRepositoryInterface;
use App\Models\Subscriber;
use Illuminate\Support\Collection;

class SubscriberRepository extends AbstractEntityRepository implements SubscriberRepositoryInterface
{
    public function __construct(Subscriber $subscriber)
    {
        parent::__construct($subscriber);
    }

    /**
     * Find subscriber by ID
     *
     * @param int $subscriberId
     * @return Subscriber|null
     */
    public function findById(int $subscriberId): ?Subscriber
    {
        /** @var Subscriber|null */
        return $this->entity()->find($subscriberId);
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
     * Update the 'is_verified' status of a subscriber
     *
     * @param Subscriber $subscriber
     * @param bool $value
     * @return Subscriber
     */
    public function updateIsVerified(Subscriber $subscriber, bool $value): Subscriber
    {
        return tap($subscriber, function (Subscriber $subscriber) use ($value) {
            $subscriber->update([
                'is_verified' => $value
            ]);
        });
    }

    /**
     * Find subscribers by IDs
     *
     * @param array $subscribers
     * @return Collection
     */
    public function findByIds(array $subscribers): Collection
    {
        return $this->entity()->whereIn('id', $subscribers)->get();
    }
}
