<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'code' => fake()->word(),
            'payment_made' => fake()->boolean(),
            'status' => fake()->randomElement(["pending","completed","processing","cancelled"]),
            'customer_id' => User::factory(),
            'product_id' => Product::factory(),
            'total_amount' => fake()->randomFloat(2, 0, 999999.99),
            'phone_number' => fake()->phoneNumber(),
        ];
    }
}
