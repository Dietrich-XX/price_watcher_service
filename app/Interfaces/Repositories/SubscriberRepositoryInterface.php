<?php

declare(strict_types=1);

namespace App\Interfaces\Repositories;

use App\Models\Subscriber;
use Illuminate\Support\Collection;

interface SubscriberRepositoryInterface
{
    /**
     * @param int $subscriberId
     * @return Subscriber|null
     */
    public function findById(int $subscriberId): ?Subscriber;

    /**
     * @param string $email
     * @return Subscriber
     */
    public function findOrCreateByEmail(string $email): Subscriber;

    /**
     * @param Subscriber $subscriber
     * @param bool $value
     * @return Subscriber
     */
    public function updateIsVerified(Subscriber $subscriber, bool $value): Subscriber;

    /**
     * @param array $subscribers
     * @return Collection
     */
    public function findByIds(array $subscribers): Collection;
}
