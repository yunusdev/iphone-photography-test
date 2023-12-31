<?php

namespace Database\Seeders;

use App\Models\Lesson;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Lesson::factory()
            ->count(20)
            ->create();

        $this->call(AchievementsTableSeeder::class);
        $this->call(BadgesTableSeeder::class);

        User::factory()->create();

    }
}
