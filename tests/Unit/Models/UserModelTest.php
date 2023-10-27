<?php

namespace Tests\Unit\Models;

use App\Events\AchievementUnlocked;
use App\Listeners\AchievementUnlockedListener;
use App\Models\Achievement;
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
     * @dataProvider userTestingData
     */
    public function test_can_get_user_unlocked_achievements($lessonNum, $commentsNum, $unlockedAchievements): void
    {
        $user = User::factory()->create();
        $this->watchLessons($user, $lessonNum);
        $this->createComments($user, $commentsNum);

        $this->assertEqualsCanonicalizing($unlockedAchievements, $user->unlockedUserAchievements());
    }

    /**
     * @dataProvider userTestingData
     */
    public function test_can_get_user_current_badge($lessonNum, $commentsNum, $unlockedAchievements, $currentBadge): void
    {
        $user = User::factory()->create();
        $this->watchLessons($user, $lessonNum);
        $this->createComments($user, $commentsNum);

        $this->assertEquals($currentBadge,  $user->currentBadge()?->name);
    }

    /**
     * @dataProvider userTestingData
     */
    public function test_can_get_user_next_badge($lessonNum, $commentsNum, $unlockedAchievements, $currentBadge, $nextBadge): void
    {
        $user = User::factory()->create();
        $this->watchLessons($user, $lessonNum);
        $this->createComments($user, $commentsNum);

        $this->assertEquals($nextBadge,  $user->nextUserBadge()?->name);
    }

    /**
     * @dataProvider userTestingData2
     */
    public function test_can_get_user_remaining_achievements_to_unlock_next_badge($lessonNum, $commentsNum, $remainingAchievementNumber): void
    {
        $user = User::factory()->create();
        $this->watchLessons($user, $lessonNum);
        $this->createComments($user, $commentsNum);

        $this->assertEquals($remainingAchievementNumber,  $user->remainingAchievementsToUnlockNextBadge());
    }

    /**
     * @dataProvider userTestingData2
     */
    public function test_can_get_user_next_available_achievements($lessonNum, $commentsNum, $remainingAchievementNumber,$nextAvailableAchievements): void
    {
        $user = User::factory()->create();
        $this->watchLessons($user, $lessonNum);
        $this->createComments($user, $commentsNum);

        $this->assertEquals($nextAvailableAchievements,  $user->nextAvailableAchievements());
    }

    /**
     * @dataProvider getNextAchievementLessonData
     */
    public function test_can_get_next_lessons_achievement($achievementName, $nextAchievementName): void
    {
        $currentAchievement = Achievement::query()->where(['name' => $achievementName])->first();
        $achievement = User::getNextAchievement($currentAchievement, Achievement::LESSON);

        $this->assertEquals($nextAchievementName,  $achievement?->name);
    }

    /**
     * @dataProvider getNextAchievementCommentData
     */
    public function test_can_get_next_comments_achievement($achievementName, $nextAchievementName): void
    {
        $currentAchievement = Achievement::query()->where(['name' => $achievementName])->first();
        $achievement = User::getNextAchievement($currentAchievement, Achievement::COMMENT);

        $this->assertEquals($nextAchievementName,  $achievement?->name);
    }

    public static function lessonsWatchedData(): array {
        return [
            [1],
            [5],
            [10],
            [25],
            [50],
        ];
    }

    public static function commentsWrittenData(): array {
        return [
            [1],
            [3],
            [5],
            [10],
            [20],
        ];
    }

    public static function lessonsData(): array {
        return [
            [0, null],
            [1, 'First Lesson Watched'],
            [5, '5 Lessons Watched'],
            [10, '10 Lessons Watched'],
            [30, '25 Lessons Watched'],
            [1000, '50 Lessons Watched'],
        ];
    }

    public static function commentsData(): array {
        return [
            [0, null],
            [1, 'First Comment Written'],
            [4, '3 Comments Written'],
            [5, '5 Comments Written'],
            [7, '5 Comments Written'],
            [1000, '20 Comments Written'],
        ];
    }

    /**
     * This data is used to test unlockedUserAchievements, currentBadge and nextUserBadge functions
     */
    public static function userTestingData(): array {
        return [
            [0, 0, [], 'Beginner', 'Intermediate'],
            [1, 0, ['First Lesson Watched'], 'Beginner', 'Intermediate'],
            [0, 1, ['First Comment Written'], 'Beginner', 'Intermediate'],
            [1, 1, ['First Lesson Watched', 'First Comment Written'], 'Beginner', 'Intermediate'],
            [5, 4, [
                'First Lesson Watched',
                '5 Lessons Watched',
                'First Comment Written',
                '3 Comments Written'
            ], 'Intermediate', 'Advanced'],

            [12, 7, [
                'First Lesson Watched',
                '5 Lessons Watched',
                '10 Lessons Watched',
                'First Comment Written',
                '3 Comments Written',
                '5 Comments Written'
            ], 'Intermediate', 'Advanced'],

            [25, 10, [
                'First Lesson Watched',
                '5 Lessons Watched',
                '10 Lessons Watched',
                '25 Lessons Watched',
                'First Comment Written',
                '3 Comments Written',
                '5 Comments Written',
                '10 Comments Written'
            ], 'Advanced', 'Master'],

            [30, 15, [
                'First Lesson Watched',
                '5 Lessons Watched',
                '10 Lessons Watched',
                '25 Lessons Watched',
                'First Comment Written',
                '3 Comments Written',
                '5 Comments Written',
                '10 Comments Written'
            ], 'Advanced', 'Master'],

            [50, 20, [
                'First Lesson Watched',
                '5 Lessons Watched',
                '10 Lessons Watched',
                '25 Lessons Watched',
                '50 Lessons Watched',
                'First Comment Written',
                '3 Comments Written',
                '5 Comments Written',
                '10 Comments Written',
                '20 Comments Written'
            ], 'Master', null],

            [1000, 1000, [
                'First Lesson Watched',
                '5 Lessons Watched',
                '10 Lessons Watched',
                '25 Lessons Watched',
                '50 Lessons Watched',
                'First Comment Written',
                '3 Comments Written',
                '5 Comments Written',
                '10 Comments Written',
                '20 Comments Written'
            ], 'Master', null],
        ];
    }

    /**
     * This data is used to test remainingAchievementsToUnlockNextBadge and nextAvailableAchievements functions
     */
    public static function userTestingData2(): array {
        return [
            [0, 0, 4, ['First Lesson Watched', 'First Comment Written']],
            [1, 0, 3, ['5 Lessons Watched', 'First Comment Written']],
            [0, 1, 3, ['First Lesson Watched', '3 Comments Written']],
            [1, 1, 2, ['5 Lessons Watched', '3 Comments Written']],
            [5, 4, 4, ['10 Lessons Watched', '5 Comments Written']],
            [12, 7, 2, ['25 Lessons Watched', '10 Comments Written']],
            [25, 10, 2, ['50 Lessons Watched', '20 Comments Written']],
            [30, 15, 2, ['50 Lessons Watched', '20 Comments Written']],
            [50, 20, 0, []],
            [1000, 1000, 0, []],
        ];
    }

    public static function getNextAchievementLessonData(): array {
        return [
            [null, 'First Lesson Watched'],
            ['First Lesson Watched', '5 Lessons Watched'],
            ['5 Lessons Watched', '10 Lessons Watched'],
            ['10 Lessons Watched', '25 Lessons Watched'],
            ['25 Lessons Watched', '50 Lessons Watched'],
            ['50 Lessons Watched', null],
        ];
    }

    public static function getNextAchievementCommentData(): array {
        return [
            [null, 'First Comment Written'],
            ['First Lesson Watched', '3 Comments Written'],
            ['3 Comments Written', '5 Comments Written'],
            ['5 Comments Written', '10 Comments Written'],
            ['10 Comments Written', '20 Comments Written'],
            ['20 Comments Written', null],
        ];
    }
}
