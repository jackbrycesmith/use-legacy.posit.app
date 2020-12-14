<?php

namespace Database\Factories;

use App\Models\StripeAccount;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StripeEventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StripeEvent::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->unique()->lexify('evt_????????????????'),
            'api_version' => $this->faker->date(),
            'created' => $this->faker->dateTimeBetween('-1 hour', 'now'),
            'livemode' => $this->faker->boolean,
            'pending_webhooks' => $this->faker->numberBetween(0, 100),
            'type' => $this->faker->randomElement([
                'charge.failed',
                'payment_intent.succeeded',
            ]),
        ];
    }

    /**
     * Indicate that the user is suspended.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function connectAccount()
    {
        return $this->state(function (array $attributes) {
            return [
                'account_id' => StripeAccount::factory()->create()->getKey(),
            ];
        });
    }
}
