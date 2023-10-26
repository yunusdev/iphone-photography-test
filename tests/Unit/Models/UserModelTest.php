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

    /**
     * @dataProvider lessonsData
     */
    public function test_can_get_user_last_lesson_achievement($lessonNum, $achievementName): void
    {
        $user = User::factory()->create();
        $this->watchLessons($user, $lessonNum);

        $this->assertEquals($achievementName, $user->lastLessonAchievement()?->name);
    }

    /**
     * @dataProvider commentsData
     */
    public function test_can_get_user_last_comment_achievement($commentsNum, $achievementName): void
    {
        $user = User::factory()->create();
        $this->createComments($user, $commentsNum);

        $this->assertEquals($achievementName, $user->lastCommentAchievement()?->name);
    }

    /**
     * @dataProvider unlockedAchievementsData
     */
    public function test_can_get_user_unlocked_achievements($lessonNum, $commentsNum, $unlockedAchievements): void
    {
        $user = User::factory()->create();
        $this->watchLessons($user, $lessonNum);
        $this->createComments($user, $commentsNum);

        $this->assertEqualsCanonicalizing($unlockedAchievements, $user->unlockedUserAchievements());
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

    public function lessonsData(): array {
        return [
            [0, null],
            [1, 'First Lesson Watched'],
            [5, '5 Lessons Watched'],
            [10, '10 Lessons Watched'],
            [30, '25 Lessons Watched'],
            [1000, '50 Lessons Watched'],
        ];
    }

    public function commentsData(): array {
        return [
            [0, null],
            [1, 'First Comment Written'],
            [4, '3 Comments Written'],
            [5, '5 Comments Written'],
            [7, '5 Comments Written'],
            [1000, '20 Comments Written'],
        ];
    }

    public function unlockedAchievementsData(): array {
        return [
            [0, 0, []],
            [1, 0, ['First Lesson Watched']],
            [0, 1, ['First Comment Written']],
            [1, 1, ['First Lesson Watched', 'First Comment Written']],
            [5, 4, ['First Lesson Watched', '5 Lessons Watched', 'First Comment Written', '3 Comments Written']],
            [12, 7, ['First Lesson Watched', '5 Lessons Watched', '10 Lessons Watched', 'First Comment Written', '3 Comments Written', '5 Comments Written']],
            [25, 10, ['First Lesson Watched', '5 Lessons Watched', '10 Lessons Watched', '25 Lessons Watched', 'First Comment Written', '3 Comments Written', '5 Comments Written', '10 Comments Written']],
            [30, 15, ['First Lesson Watched', '5 Lessons Watched', '10 Lessons Watched', '25 Lessons Watched', 'First Comment Written', '3 Comments Written', '5 Comments Written', '10 Comments Written']],
            [50, 20, ['First Lesson Watched', '5 Lessons Watched', '10 Lessons Watched', '25 Lessons Watched', '50 Lessons Watched', 'First Comment Written', '3 Comments Written', '5 Comments Written', '10 Comments Written', '20 Comments Written']],
            [1000, 1000, ['First Lesson Watched', '5 Lessons Watched', '10 Lessons Watched', '25 Lessons Watched', '50 Lessons Watched', 'First Comment Written', '3 Comments Written', '5 Comments Written', '10 Comments Written', '20 Comments Written']],
        ];
    }

}
