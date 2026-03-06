<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Setting;

class SettingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Setting::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'whatsapp_link' => fake()->word(),
            'whatsapp_number' => fake()->word(),
            'contact_number' => fake()->word(),
            'account_balance' => fake()->randomFloat(2, 0, 99999999.99),
        ];
    }
}
