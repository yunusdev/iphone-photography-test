<?php

namespace Tests\Unit\Events;

use App\Events\BadgeUnlocked;
use App\Listeners\BadgeUnlockedListener;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class BadgeUnlockedEventTest extends TestCase
{

    public function test_badge_unlocked_event_successfully_dispatched(): void
    {
        Event::fake([BadgeUnlocked::class]);

        Event::dispatch(BadgeUnlocked::class);

        Event::assertDispatched(BadgeUnlocked::class);
    }

    public function test_badge_unlocked_event_not_dispatched_when_event_is_not_called(): void
    {
        Event::fake([BadgeUnlocked::class]);

        Event::assertNotDispatched(BadgeUnlocked::class);
    }

    public function test_badge_unlocked_event_listener_is_called_when_dispatched(): void
    {
        Event::fake([BadgeUnlocked::class]);

        Event::dispatch(BadgeUnlocked::class);

        Event::assertListening(BadgeUnlocked::class, BadgeUnlockedListener::class);
    }


    public function test_that_badge_is_not_attached_if_the_badge_name_is_invalid(): void
    {
        $user = User::factory()->create();
        Event::dispatch(new BadgeUnlocked('InvalidBadgeName', $user));

        $this->assertEquals(0, $user->badges()->count());
    }

    /**
     * @dataProvider badgesArray
     */
    public function test_current_badge_after_watching_lessons_and_written_comments($badgeName, $lessonsNum, $commentsNum): void
    {
        $user = User::factory()->create();

        $this->watchLessons($user, $lessonsNum);
        $this->createComments($user, $commentsNum);

        $this->assertEquals($badgeName, $user->currentBadge()->name);
    }


    public function badgesArray() : array{

        return [
            ['Intermediate', 0, 10],
            ['Intermediate', 5, 3],
            ['Intermediate', 2, 10],

            ['Advanced', 25, 10],
            ['Advanced', 27, 13],
            ['Advanced', 50, 13],

            ['Master', 50, 20],
            ['Master', 60, 50],
            ['Master', 500, 500],
        ];

    }


}
