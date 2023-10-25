<?php

namespace Tests\Unit\Events;

use App\Events\CommentWritten;
use App\Listeners\CommentWrittenListener;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CommentWrittenEventTest extends TestCase
{

    public function test_comment_written_event_successfully_dispatched(): void
    {
        Event::fake([CommentWritten::class]);

        Event::dispatch(CommentWritten::class);

        Event::assertDispatched(CommentWritten::class);
    }

    public function test_comment_written_event_not_dispatched_when_event_is_not_called(): void
    {
        Event::fake([CommentWritten::class]);

        Event::assertNotDispatched(CommentWritten::class);
    }

    public function test_comment_written_event_listener_is_called_when_dispatched(): void
    {
        Event::fake([CommentWritten::class]);

        Event::dispatch(CommentWritten::class);

        Event::assertListening(CommentWritten::class, CommentWrittenListener::class);
    }


}
