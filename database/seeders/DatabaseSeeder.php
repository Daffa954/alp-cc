<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Summary;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        User::factory()
            ->count(10)
            ->has(Activity::factory()->count(3))
            ->has(Income::factory()->count(2))
            ->has(Expense::factory()->count(5))
            ->has(Summary::factory()->count(4))
            ->create();

        // or create some direct factories connecting entities
        Activity::factory()->count(20)->create();
        Expense::factory()->count(50)->create();
    }
}
