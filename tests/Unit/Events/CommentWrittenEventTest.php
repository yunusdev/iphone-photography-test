<?php

namespace Tests\Unit\Events;

use App\Events\AchievementUnlocked;
use App\Events\CommentWritten;
use App\Listeners\CommentWrittenListener;
use App\Models\User;
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


    public function test_that_comment_written_event_dispatches_when_comment_is_written(): void
    {
        Event::fake([CommentWritten::class]);

        $user = User::factory()->create();
        $this->createComments($user);

        Event::assertDispatched(CommentWritten::class);
    }

    public function test_that_comment_written_event_dispatches_with_the_correct_payload_when_comment_is_written(): void
    {
        Event::fake([CommentWritten::class]);

        $user = User::factory()->create();
        $comment = $this->createComments($user);

        Event::assertDispatched(function (CommentWritten $event) use ($user, $comment): bool {
            return $event->comment->id === $comment->id && $event->comment->user->id === $user->id;
        });
    }

    public function test_that_comment_written_event_is_dispatched_the_right_number_of_times_when_multiple_comments_are_written(): void
    {
        Event::fake([CommentWritten::class]);

        $user = User::factory()->create();
        $this->createComments($user, 4);

        Event::assertDispatchedTimes(CommentWritten::class, 4);
    }

    /**
     * @dataProvider commentsData
     */
    public function test_that_achievement_unlocked_event_is_dispatched_with_correct_payload_after_comments_achievements_are_unlocked($achievementName, $commentNum, $eventDispatchNum): void
    {
        Event::fake([AchievementUnlocked::class]);

        $user = User::factory()->create();
        $this->createComments($user, $commentNum);

        Event::assertDispatched(function (AchievementUnlocked $event) use ($achievementName, $user): bool {
            return $event->achievement_name === $achievementName && $event->user->id === $user->id;
        });
    }

    /**
     * @dataProvider commentsData
     */
    public function test_that_achievement_unlocked_events_are_dispatched_the_right_number_of_times_after_lessons_achievements_are_unlocked($achievementName, $commentNum, $eventDispatchNum): void
    {
        Event::fake([AchievementUnlocked::class]);

        $user = User::factory()->create();
        $this->createComments($user, $commentNum);

        Event::assertDispatchedTimes( AchievementUnlocked::class, $eventDispatchNum);
    }

    /**
     * Data provider for testing if AchievementUnlocked event is dispatched when comments achievements are unlocked and also if the event is dispatched the number of required times
     */
    public static function commentsData() : array {
        return [
            ['First Comment Written', 1, 1],
            ['3 Comments Written', 3, 2],
            ['5 Comments Written', 5, 3],
            ['10 Comments Written', 10, 4],
            ['20 Comments Written', 20, 5],
        ];
    }

}
