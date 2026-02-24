<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $email
 * @property bool $is_verified
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read Collection $priceSubscriptions
 */
class Subscriber extends Model
{
    protected $fillable = [
        'email',
        'is_verified',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
    ];

    /**
     * @return BelongsToMany
     */
    public function priceSubscriptions(): BelongsToMany
    {
        return $this->belongsToMany(
            PriceSubscription::class,
            'price_subscription_subscriber',
            'subscriber_id',
            'price_subscription_id'
        )->withTimestamps();
    }
}
