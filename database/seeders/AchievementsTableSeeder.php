<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;

class AchievementsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $achievements = [
            ['name' => 'First Lesson Watched', 'group' => Achievement::LESSON, 'number' => 1],
            ['name' => '5 Lessons Watched', 'group' => Achievement::LESSON, 'number' => 5],
            ['name' => '10 Lessons Watched', 'group' => Achievement::LESSON, 'number' => 10],
            ['name' => '25 Lessons Watched', 'group' => Achievement::LESSON, 'number' => 25],
            ['name' => '50 Lessons Watched', 'group' => Achievement::LESSON, 'number' => 50],

            ['name' => 'First Comment Written', 'group' => Achievement::COMMENT, 'number' => 1],
            ['name' => '3 Comments Written', 'group' => Achievement::COMMENT, 'number' => 3],
            ['name' => '5 Comments Written', 'group' => Achievement::COMMENT, 'number' => 5],
            ['name' => '10 Comment Written', 'group' => Achievement::COMMENT, 'number' => 10],
            ['name' => '20 Comment Written', 'group' => Achievement::COMMENT, 'number' => 20],
        ];
        foreach ($achievements as $achievement){
            Achievement::query()->updateOrCreate(['name' => $achievement['name']], $achievement);
        }
    }
}
