<?php

namespace Tests\Unit\Events;

use App\Events\LessonWatched;
use App\Listeners\LessonWatchedListener;
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


}
