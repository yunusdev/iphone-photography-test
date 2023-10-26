<?php

namespace Tests\Unit\Models;

use App\Models\Achievement;
use App\Models\Badge;
use Tests\TestCase;

class BadgeModelTest extends TestCase
{

    /**
     * @dataProvider badgesData
     */
    public function test_can_get_next_badge_with_number($number, $nextBadgeName): void
    {
        $badge = Badge::getNextBadge($number);

        $this->assertEquals($nextBadgeName,  $badge?->name);
    }

    public function badgesData(): array {
        return [
            [0, 'Intermediate'],
            [2, 'Intermediate'],
            [4, 'Advanced'],
            [7, 'Advanced'],
            [8, 'Master'],
            [9, 'Master'],
            [10, null],
            [1000, null],
        ];
    }


}
