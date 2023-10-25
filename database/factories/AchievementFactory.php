<?php

namespace Database\Factories;

use App\Models\Achievement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Achievement>
 */
class AchievementFactory extends Factory
{

    protected $model = Achievement::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->text(),
            'group' => $this->faker->randomElement([Achievement::COMMENT, Achievement::LESSON]),
            'number' => $this->faker->numberBetween(1, 50)
        ];
    }

    public function forLesson(): self
    {
        return $this->state(['group' => Achievement::LESSON]);
    }

    public function forComment(): self
    {
        return $this->state(['group' => Achievement::COMMENT]);
    }

}
