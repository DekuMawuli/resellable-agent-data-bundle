<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\TopUp;
use App\Models\User;

class TopUpFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TopUp::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'code' => fake()->word(),
            'customer_id' => User::factory(),
            'payment_made' => fake()->boolean(),
            'amount' => fake()->randomFloat(2, 0, 999999.99),
            'status' => fake()->randomElement(["pending","completed","cancelled"]),
        ];
    }
}
