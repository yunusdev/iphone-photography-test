<?php

namespace App\Listeners;

use App\Events\BadgeUnlocked;
use App\Models\Badge;

class BadgeUnlockedListener
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
    public function handle(BadgeUnlocked $event): void
    {
        $badgeName = $event->badge_name;
        $user = $event->user;

        $badge = Badge::query()->where(['name' => $badgeName])->first();
        if ($badge === null) return;

        $user->badges()->attach($badge->id);
    }
}
