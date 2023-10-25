<?php

namespace Tests\Unit\Events;

use App\Events\AchievementUnlocked;
use App\Listeners\AchievementUnlockedListener;
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


}
