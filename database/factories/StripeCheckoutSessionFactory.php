<?php

namespace Database\Factories;

use App\Models\StripeCheckoutSession;
use Illuminate\Database\Eloquent\Factories\Factory;

class StripeCheckoutSessionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StripeCheckoutSession::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->unique()->lexify('cs_test_????????????????'),
        ];
    }
}
