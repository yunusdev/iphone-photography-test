<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Events\CommentWritten;
use App\Models\Achievement;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CommentWrittenListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CommentWritten $event): void
    {
        $comment = $event->comment;
        $user = $comment->user;

        $userCommentsCount = $user->commentsCount();

        $achievement = Achievement::findByGroupAndNumber(Achievement::COMMENT, $userCommentsCount);

        if ($achievement === null) return;

        event(new AchievementUnlocked($achievement->name, $user));

    }
}
