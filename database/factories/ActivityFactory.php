<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Activity>
 */
class ActivityFactory extends Factory
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
        'title' => $this->faker->sentence(3),
        'start_longitude' => $this->faker->longitude(),
        'start_latitude' => $this->faker->latitude(),
        'end_longitude' => $this->faker->longitude(),
        'end_latitude' => $this->faker->latitude(),
        'distance_in_km' => $this->faker->randomFloat(2, 0, 100),
        'transportation' => $this->faker->randomElement(['car','motorbike','public_transport','walk']),
        'cost_to_there' => $this->faker->randomFloat(2, 0, 100),
        'activity_location' => $this->faker->city(),
        'date_start' => $this->faker->dateTimeBetween('-1 month', 'now'),
        'date_end' => $this->faker->dateTimeBetween('now', '+1 week'),
    ];
    }
}
