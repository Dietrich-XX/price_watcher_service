<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Subscriber;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Subscriber>
 */
class SubscriberFactory extends Factory
{
    protected $model = Subscriber::class;

    /**
     * @return array|mixed[]
     */
    public function definition(): array
    {
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'is_verified' => false,
        ];
    }

    /**
     * @return $this
     */
    public function verified(): static
    {
        return $this->state(fn () => [
            'is_verified' => true,
        ]);
    }
}
