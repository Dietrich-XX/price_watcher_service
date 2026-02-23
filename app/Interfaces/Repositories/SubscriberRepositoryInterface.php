<?php

declare(strict_types=1);

namespace App\Interfaces\Repositories;

use App\Models\Subscriber;

interface SubscriberRepositoryInterface
{
    /**
     * @param string $email
     * @return Subscriber
     */
    public function findOrCreateByEmail(string $email): Subscriber;
}
