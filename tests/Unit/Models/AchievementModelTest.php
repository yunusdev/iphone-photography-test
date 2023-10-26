<?php

namespace Tests\Unit\Models;

use App\Events\AchievementUnlocked;
use App\Listeners\AchievementUnlockedListener;
use App\Models\Achievement;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class AchievementModelTest extends TestCase
{

    /**
     * @dataProvider lessonsData
     */
    public function test_lessons_achievements_are_stored_in_the_db($eventName, $lessonsNum): void
    {
        $achievement = Achievement::findByGroupAndNumber(Achievement::LESSON, $lessonsNum);

        $this->assertNotNull($achievement);
        $this->assertEquals($achievement->name, $eventName);
    }

    public function test_find_by_function_to_return_null_for_lesson_when_number_is_wrong(): void
    {
        $achievement = Achievement::findByGroupAndNumber(Achievement::LESSON, 99);

        $this->assertNull($achievement);
    }

    public function test_find_by_function_to_return_null_for_comment_when_number_is_wrong(): void
    {
        $achievement = Achievement::findByGroupAndNumber(Achievement::COMMENT, 99);

        $this->assertNull($achievement);
    }

    /**
     * @dataProvider commentsData
     */
    public function test_comments_achievements_are_stored_in_the_db($eventName, $lessonsNum): void
    {
        $achievement = Achievement::findByGroupAndNumber(Achievement::COMMENT, $lessonsNum);

        $this->assertNotNull($achievement);
        $this->assertEquals($achievement->name, $eventName);
    }

    public function lessonsData() : array {
        return [
            ['First Lesson Watched', 1],
            ['5 Lessons Watched', 5],
            ['10 Lessons Watched', 10],
            ['25 Lessons Watched', 25],
            ['50 Lessons Watched', 50],
        ];
    }

    public function commentsData() : array {
        return [
            ['First Comment Written', 1],
            ['3 Comments Written', 3],
            ['5 Comments Written', 5],
            ['10 Comments Written', 10],
            ['20 Comments Written', 20],
        ];
    }


}
