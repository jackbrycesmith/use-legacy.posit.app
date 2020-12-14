<?php

namespace Database\Factories;

use App\Models\StripeCustomer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StripeCustomerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StripeCustomer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->unique()->lexify('cus_????????????????'),
        ];
    }
}
