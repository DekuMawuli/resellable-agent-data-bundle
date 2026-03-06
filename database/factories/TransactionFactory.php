<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\User;

class TransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'code' => fake()->word(),
            'amount' => fake()->randomFloat(2, 0, 999999.99),
            'type' => fake()->randomElement(["credit","debit"]),
            'description' => fake()->text(),
            'customer_id' => User::factory(),
            'order_id' => Order::factory(),
        ];
    }
}
