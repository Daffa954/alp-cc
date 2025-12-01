<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Summary>
 */
class SummaryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('-2 months','now');
        $end = (clone $start)->modify('+6 days');
        return [
            'user_id' => User::factory(),
            'week_start' => $start->format('Y-m-d'),
            'week_end' => $end->format('Y-m-d'),
            'total_expense_this_week' => $this->faker->randomFloat(2, 0, 200),
            'average_expense' => $this->faker->randomFloat(2, 0, 200),
        ];
    }
}
