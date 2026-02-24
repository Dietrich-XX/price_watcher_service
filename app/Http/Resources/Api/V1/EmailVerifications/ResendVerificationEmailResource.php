<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1\EmailVerifications;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResendVerificationEmailResource extends JsonResource
{
    public function __construct(Subscriber $subscriber)
    {
        parent::__construct($subscriber);
    }

    /**
     * @param Request $request
     * @return string[]
     */
    public function toArray(Request $request): array
    {
        return [
            'message' => $this->is_verified
                ? 'You has already been verified.'
                : 'A verification email has been sent again to your email address.'
        ];
    }
}
