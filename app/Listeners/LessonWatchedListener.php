<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Events\LessonWatched;
use App\Models\Achievement;

class LessonWatchedListener
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
    public function handle(LessonWatched $event): void
    {
        $lesson = $event->lesson;
        $user = $event->user;

        $userWatchedCount = $user->watchedCount();

        $achievement = Achievement::findByGroupAndNumber(Achievement::LESSON, $userWatchedCount);

        if ($achievement === null) return;

        event(new AchievementUnlocked($achievement->name, $user));

    }
}
