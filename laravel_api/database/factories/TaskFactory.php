<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tasks>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'text' => $this->faker->sentence,
            'user_id'=>User::factory(),
            'date' => $this->faker->date(), // nullable date
            'is_complete' => $this->faker->boolean(30), // 30% chance to be true
        ];
    }
}
