<?php

namespace Database\Factories;

use App\Models\Summary;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recommendation>
 */
class RecommendationFactory extends Factory
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
        'summary_id' => Summary::factory(),
        'week_start' => $this->faker->date(),
        'message' => $this->faker->sentence(),
    ];
    }
}
