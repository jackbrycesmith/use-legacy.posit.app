<?php

namespace Database\Factories;

use App\Models\StripeAccount;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StripeAccountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StripeAccount::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->unique()->lexify('acct_????????????????'),
            'country' => $this->faker->randomElement(['AU', 'GB', 'US']),
            'default_currency' => $this->faker->randomElement(['gbp', 'usd', 'eur']),
            'details_submitted' => $this->faker->boolean(75),
            'email' => $this->faker->email,
            'payouts_enabled' => $this->faker->boolean(75),
            'type' => $this->faker->randomElement(['standard', 'express', 'custom']),
        ];
    }
}
