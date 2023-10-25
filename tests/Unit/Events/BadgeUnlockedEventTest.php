<?php

namespace Tests\Unit\Events;

use App\Events\BadgeUnlocked;
use App\Listeners\BadgeUnlockedListener;
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


}
