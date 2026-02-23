<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1\Subscriber;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriberResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'email' => $this->resource->email,
            'is_verified' => $this->resource->is_verified
        ];
    }
}
