<?php

namespace Tests\Unit\Models;

use App\Events\AchievementUnlocked;
use App\Listeners\AchievementUnlockedListener;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class UserModelTest extends TestCase
{

    /**
     * @dataProvider lessonsWatchedData
     */
    public function test_watched_count_after_watching_lessons($lessonsWatched): void
    {
        $user = User::factory()->create();
        $this->watchLessons($user, $lessonsWatched);

        $this->assertEquals($lessonsWatched, $user->watchedCount());
    }

    public function test_lesson_watched_count_is_zero_when_no_lessons_is_watched(): void
    {
        $user = User::factory()->create();
        $this->watchLessons($user, 20, false);

        $this->assertEquals(0, $user->watchedCount());
    }

    /**
     * @dataProvider commentsWrittenData
     */
    public function test_comments_count_after_creating_comments($commentNum): void
    {

        $user = User::factory()->create();
        $this->createComments($user, $commentNum);

        $this->assertEquals($commentNum, $user->commentsCount());
    }

    public function test_comments_count_is_zero_when_no_comments_is_written(): void
    {
        $user = User::factory()->create();

        $this->assertEquals(0, $user->commentsCount());
    }

    public function lessonsWatchedData(): array {
        return [
            [1],
            [5],
            [10],
            [25],
            [50],
        ];
    }

    public function commentsWrittenData(): array {
        return [
            [1],
            [3],
            [5],
            [10],
            [20],
        ];
    }

}
