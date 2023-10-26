<?php

namespace Tests\Unit\Events;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Listeners\AchievementUnlockedListener;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class AchievementUnlockedEventTest extends TestCase
{

    public function test_achievement_unlocked_event_successfully_dispatched(): void
    {
        Event::fake([AchievementUnlocked::class]);

        Event::dispatch(AchievementUnlocked::class);

        Event::assertDispatched(AchievementUnlocked::class);
    }

    public function test_achievement_unlocked_event_not_dispatched_when_event_is_not_called(): void
    {
        Event::fake([AchievementUnlocked::class]);

        Event::assertNotDispatched(AchievementUnlocked::class);
    }

    public function test_achievement_unlocked_event_listener_is_called_when_dispatched(): void
    {
        Event::fake([AchievementUnlocked::class]);

        Event::dispatch(AchievementUnlocked::class);

        Event::assertListening(AchievementUnlocked::class, AchievementUnlockedListener::class);
    }

    /**
     * @dataProvider badgesCommentsArray
     */
    public function test_that_badge_unlocked_event_is_dispatched_with_correct_payload_after_badges_are_unlocked_with_comments($badgeName, $commentNum): void
    {
        Event::fake([BadgeUnlocked::class]);

        $user = User::factory()->create();
        $this->createComments($user, $commentNum);

        Event::assertDispatched(function (BadgeUnlocked $event) use ($badgeName, $user): bool {
            return $event->badge_name === $badgeName && $event->user->id === $user->id;
        });
    }

    /**
     * @dataProvider badgesLessonsArray
     */
    public function test_that_badge_unlocked_event_is_dispatched_with_correct_payload_after_badges_are_unlocked_with_lessons($badgeName, $lessonsNum): void
    {
        Event::fake([BadgeUnlocked::class]);

        $user = User::factory()->create();
        $this->watchLessons($user, $lessonsNum);

        Event::assertDispatched(function (BadgeUnlocked $event) use ($badgeName, $user): bool {
            return $event->badge_name === $badgeName && $event->user->id === $user->id;
        });
    }

    /**
     * @dataProvider badgesArray
     */
    public function test_that_badge_unlocked_event_is_dispatched_with_correct_payload_after_badges_are_unlocked($badgeName, $lessonsNum, $commentNum): void
    {
        Event::fake([BadgeUnlocked::class]);

        $user = User::factory()->create();
        $this->createComments($user, $commentNum);
        $this->watchLessons($user, $lessonsNum);

        Event::assertDispatched(function (BadgeUnlocked $event) use ($badgeName, $user): bool {
            return $event->badge_name === $badgeName && $event->user->id === $user->id;
        });
    }

    /**
     * @dataProvider badgesArray
     */
    public function test_that_badge_unlocked_events_are_dispatched_the_right_number_of_times_after_badges_are_unlocked($badgeName, $lessonsNum, $commentNum, $badgeDispatchedNum): void
    {
        Event::fake([BadgeUnlocked::class]);

        $user = User::factory()->create();
        $this->createComments($user, $commentNum);
        $this->watchLessons($user, $lessonsNum);

        Event::assertDispatchedTimes( BadgeUnlocked::class, $badgeDispatchedNum);
    }

    /**
     * @dataProvider badgesArray
     */
    public function test_user_achievement_counts_after_achievement_unlocked_events_is_dispatched($badgeName, $lessonsNum, $commentNum, $badgeDispatchedNum, $achievementsCount): void
    {
        $user = User::factory()->create();
        $this->createComments($user, $commentNum);
        $this->watchLessons($user, $lessonsNum);

        $this->assertEquals($achievementsCount, $user->achievementsCount());
    }

    public function badgesCommentsArray() : array{

        return [
            ['Intermediate', 10],
            ['Intermediate', 12],
            ['Intermediate', 500],
        ];
    }

    public function badgesLessonsArray() : array{

        return [
            ['Intermediate', 25],
            ['Intermediate', 50],
            ['Intermediate', 500],
        ];
    }


    public function badgesArray() : array{

        return [
            ['Intermediate', 0, 10, 2, 4],
            ['Intermediate', 5, 3, 2, 4],
            ['Intermediate', 2, 10, 2, 5],

            ['Advanced', 25, 10, 3, 8],
            ['Advanced', 27, 13, 3, 8],
            ['Advanced', 50, 13, 3, 9],

            ['Master', 50, 20, 4, 10],
            ['Master', 60, 50, 4, 10],
            ['Master', 500, 500, 4, 10],
        ];

    }

}
