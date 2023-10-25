<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BadgesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $badges = [
            ['name' => 'Beginner', 'number' => 0],
            ['name' => 'Intermediate', 'number' => 4],
            ['name' => 'Advanced', 'number' => 8],
            ['name' => 'Master', 'number' => 10],
        ];
        foreach ($badges as $badge){
            Badge::query()->updateOrCreate(['name' => $badge['name']], $badge);
        }
    }
}
