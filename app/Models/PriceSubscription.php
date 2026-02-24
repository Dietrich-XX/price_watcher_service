<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $url
 * @property float|null $current_price
 * @property Carbon|null $last_checked_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read Collection $subscribers
 */
class PriceSubscription extends Model
{
    protected $fillable = [
        'url',
        'current_price',
        'last_checked_at'
    ];

    protected $casts = [
        'current_price' => 'float',
        'last_checked_at' => 'datetime'
    ];

    /**
     * @return BelongsToMany
     */
    public function subscribers(): BelongsToMany
    {
        return $this->belongsToMany(
            Subscriber::class,
            'price_subscription_subscriber',
            'price_subscription_id',
            'subscriber_id'
        )->withTimestamps();
    }
}
