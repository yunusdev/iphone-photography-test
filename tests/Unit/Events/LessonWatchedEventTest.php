<?php

namespace Tests\Unit\Events;

use App\Events\AchievementUnlocked;
use App\Events\LessonWatched;
use App\Listeners\LessonWatchedListener;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class LessonWatchedEventTest extends TestCase
{

    public function test_lesson_watched_event_successfully_dispatched(): void
    {
        Event::fake([LessonWatched::class]);

        Event::dispatch(LessonWatched::class);

        Event::assertDispatched(LessonWatched::class);
    }

    public function test_lesson_watched_event_not_dispatched_when_event_is_not_called(): void
    {
        Event::fake([LessonWatched::class]);

        Event::assertNotDispatched(LessonWatched::class);
    }

    public function test_lesson_watched_event_listener_is_called_when_dispatched(): void
    {
        Event::fake([LessonWatched::class]);

        Event::dispatch(LessonWatched::class);

        Event::assertListening(LessonWatched::class, LessonWatchedListener::class);
    }

    public function test_that_lesson_watched_event_dispatches_when_lesson_is_watched(): void
    {
        Event::fake([LessonWatched::class]);

        $user = User::factory()->create();
        $this->watchLessons($user);

        Event::assertDispatched(LessonWatched::class);
    }

    public function test_that_lesson_watched_event_dispatches_with_the_correct_payload_when_lesson_is_watched(): void
    {
        Event::fake([LessonWatched::class]);

        $user = User::factory()->create();
        $lesson = $this->watchLessons($user);

        Event::assertDispatched(function (LessonWatched $event) use ($lesson, $user): bool {
            return $event->lesson->id === $lesson->id && $event->user->id === $user->id;
        });
    }

    public function test_that_lesson_watched_event_is_dispatched_the_right_number_of_times_when_multiple_lessons_are_watched(): void
    {
        Event::fake([LessonWatched::class]);

        $user = User::factory()->create();
        $this->watchLessons($user, 2);
        $this->watchLessons($user, 1, false);

        Event::assertDispatchedTimes(LessonWatched::class, 2);
    }

    public function test_that_lesson_watched_event_doesnt_get_dispatched_when_lesson_is_not_watched(): void
    {
        Event::fake([LessonWatched::class]);

        $user = User::factory()->create();
        $this->watchLessons($user, 1, false);

        Event::assertNotDispatched(LessonWatched::class);
    }


    /**
     * @dataProvider lessonsData
     */
    public function test_that_achievement_unlocked_event_is_dispatched_with_correct_payload_after_lessons_achievements_are_unlocked($eventName, $lessonsNum, $eventDispatchNum): void
    {
        Event::fake([AchievementUnlocked::class]);

        $user = User::factory()->create();
        $this->watchLessons($user, $lessonsNum);

        Event::assertDispatched(function (AchievementUnlocked $event) use ($eventName, $user): bool {
            return $event->achievement_name === $eventName && $event->user->id === $user->id;
        });
    }

    /**
     * @dataProvider lessonsData
     */
    public function test_that_achievement_unlocked_event_is_dispatched_the_right_number_of_times_after_lessons_achievements_are_unlocked($eventName, $lessonsNum, $eventDispatchNum): void
    {
        Event::fake([AchievementUnlocked::class]);

        $user = User::factory()->create();
        $this->watchLessons($user, $lessonsNum);

        Event::assertDispatchedTimes( AchievementUnlocked::class, $eventDispatchNum);
    }

    /**
     * Data provider for testing if AchievementUnlocked event is dispatched when lessons achievements are unlocked and also if the event is dispatched the number of required times
    */
    public function lessonsData() : array {
        return [
            ['First Lesson Watched', 1, 1],
            ['5 Lessons Watched', 5, 2],
            ['10 Lessons Watched', 10, 3],
            ['25 Lessons Watched', 25, 4],
            ['50 Lessons Watched', 50, 5],
        ];
    }

}
