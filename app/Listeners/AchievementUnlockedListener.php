<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Models\Achievement;
use App\Models\Badge;

class AchievementUnlockedListener
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
    public function handle(AchievementUnlocked $event): void
    {
        $achievementName = $event->achievement_name;
        $user = $event->user;

        $achievement = Achievement::query()->where(['name' => $achievementName])->first();
        if ($achievement === null) return;

        $user->achievements()->attach($achievement->id);
        $userAchievementsCount = $user->achievementsCount();

        $badge = Badge::query()->where(['number' => $userAchievementsCount])->first();
        if ($badge === null) return;

        event(new BadgeUnlocked($badge->name, $user));
    }
}
