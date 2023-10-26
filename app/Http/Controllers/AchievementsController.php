<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AchievementsController extends Controller
{
    public function index(User $user): JsonResponse
    {
        $nextBadge = $user->nextUserBadge();

        return response()->json([
            'unlocked_achievements' => $user->unlockedUserAchievements(),
            'next_available_achievements' => $user->nextAvailableAchievements(),
            'current_badge' => $user->currentBadge()->name,
            'next_badge' => $nextBadge ? $nextBadge->name : '',
            'remaining_to_unlock_next_badge' => $user->remainingAchievementsToUnlockNextBadge()
        ]);
    }
}
