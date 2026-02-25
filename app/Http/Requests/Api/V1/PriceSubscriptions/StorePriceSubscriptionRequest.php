<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\PriceSubscriptions;

use App\Data\PriceSubscriptions\StorePriceSubscriptionData;
use App\Http\Requests\Api\AbstractApiFormRequest;
use App\Rules\OlxUrlRule;

class StorePriceSubscriptionRequest extends AbstractApiFormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'url'   => ['required', 'url', new OlxUrlRule()],
            'email' => ['required', 'email', 'max:255']
        ];
    }

    /**
     * @return string
     */
    protected function dtoClass(): string
    {
        return StorePriceSubscriptionData::class;
    }
}
