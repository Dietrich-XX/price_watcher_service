<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1\PriceSubscriptions;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PriceSubscriptionResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'url' => $this->resource->url,
            'current_price' => $this->resource->current_price,
            'last_checked_at' => $this->resource->last_checked_at
        ];
    }
}
