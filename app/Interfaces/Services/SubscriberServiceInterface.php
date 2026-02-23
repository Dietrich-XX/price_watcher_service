<?php

declare(strict_types=1);

namespace App\Interfaces\Services;

use App\Data\Subscribers\CreateSubscriberResultData;

interface SubscriberServiceInterface
{
    /**
     * @param string $email
     * @return CreateSubscriberResultData
     */
    public function createAndVerify(string $email): CreateSubscriberResultData;
}
