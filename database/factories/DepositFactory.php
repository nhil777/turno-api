<?php

namespace Database\Factories;

use App\Enums\DepositStatusEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Deposit>
 */
class DepositFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'amount' => fake()->numberBetween(0, 10000),
            'image' => fake()->imageUrl(),
            'status' => fake()->randomElement(DepositStatusEnum::values()),
        ];
    }
}
