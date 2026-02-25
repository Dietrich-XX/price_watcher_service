<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\PriceSubscription;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PriceSubscription>
 */
class PriceSubscriptionFactory extends Factory
{
    protected $model = PriceSubscription::class;

    /**
     * @return array|mixed[]
     */
    public function definition(): array
    {
        $amount = $this->faker->randomFloat(2, 10, 1000);
        $currency = $this->faker->randomElement(['$', 'грн']);

        $adId = $this->faker->bothify('ID?????');
        $titleSlug = $this->faker->slug(5, '-');
        $url = "https://www.olx.ua/d/uk/obyavlenie/{$titleSlug}-{$adId}.html";

        return [
            'url' => $url,
            'current_price' => number_format($amount, 2, '.', '') . " $currency",
            'last_checked_at' => $this->faker->dateTimeBetween('-1 week')
        ];
    }

    /**
     * @return $this
     */
    public function onlyUrl(): static
    {
        return $this->state(fn () => [
            'current_price' => null,
            'last_checked_at' => null
        ]);
    }
}
