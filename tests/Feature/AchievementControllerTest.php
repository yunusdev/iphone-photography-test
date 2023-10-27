<?php

namespace Tests\Feature;

use App\Models\User;
// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AchievementControllerTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $user = User::factory()->create();

        $response = $this->get("/users/{$user->id}/achievements");

        $response->assertStatus(200);
    }

    /**
     * @dataProvider data
     */
    public function test_achievements_response($lessonsNum, $commentsNum, $jsonResult)
    {
        $user = User::factory()->create();

        $this->watchLessons($user, $lessonsNum);
        $this->createComments($user, $commentsNum);

        $this->json('GET', "/users/{$user->id}/achievements")
            ->assertStatus(200)
            ->assertJson($jsonResult);
    }

    public static function data() : array {

        return [
            [
                0, 0,
                [
                    "unlocked_achievements" => [],
                    "next_available_achievements" => [
                        "First Lesson Watched",
                        "First Comment Written"
                    ],
                    "current_badge" => "Beginner",
                    "next_badge" => "Intermediate",
                    "remaining_to_unlock_next_badge" => 4
                ]
            ],
            [
                1, 1,
                [
                    "unlocked_achievements" => [
                        "First Lesson Watched",
                        "First Comment Written"
                    ],
                    "next_available_achievements" => [
                        "5 Lessons Watched",
                        "3 Comments Written"
                    ],
                    "current_badge" => "Beginner",
                    "next_badge" => "Intermediate",
                    "remaining_to_unlock_next_badge" => 2
                ]
            ],
            [
                5, 3,
                [
                    "unlocked_achievements" => [
                        "First Lesson Watched",
                        "5 Lessons Watched",
                        "First Comment Written",
                        "3 Comments Written"
                    ],
                    "next_available_achievements" => [
                        "10 Lessons Watched",
                        "5 Comments Written"
                    ],
                    "current_badge" => "Intermediate",
                    "next_badge" => "Advanced",
                    "remaining_to_unlock_next_badge" => 4
                ]
            ],
            [
                6, 7,
                [
                    "unlocked_achievements" => [
                        "First Lesson Watched",
                        "5 Lessons Watched",
                        "First Comment Written",
                        "3 Comments Written",
                        "5 Comments Written"
                    ],
                    "next_available_achievements" => [
                        "10 Lessons Watched",
                        "10 Comments Written"
                    ],
                    "current_badge" => "Intermediate",
                    "next_badge" => "Advanced",
                    "remaining_to_unlock_next_badge" => 3
                ]
            ],
            [
                7, 10,
                [
                    "unlocked_achievements" => [
                        "First Lesson Watched",
                        "5 Lessons Watched",
                        "First Comment Written",
                        "3 Comments Written",
                        "5 Comments Written",
                        "10 Comments Written"
                    ],
                    "next_available_achievements" => [
                        "10 Lessons Watched",
                        "20 Comments Written"
                    ],
                    "current_badge" => "Intermediate",
                    "next_badge" => "Advanced",
                    "remaining_to_unlock_next_badge" => 2
                ],
            ],
            [
                25, 10,
                [
                    "unlocked_achievements" => [
                        "First Lesson Watched",
                        "5 Lessons Watched",
                        "10 Lessons Watched",
                        "25 Lessons Watched",
                        "First Comment Written",
                        "3 Comments Written",
                        "5 Comments Written",
                        "10 Comments Written"
                    ],
                    "next_available_achievements" => [
                        "50 Lessons Watched",
                        "20 Comments Written"
                    ],
                    "current_badge" => "Advanced",
                    "next_badge" => "Master",
                    "remaining_to_unlock_next_badge" => 2
                ]
            ],
            [
                50, 14,
                [
                    "unlocked_achievements" => [
                        "First Lesson Watched",
                        "5 Lessons Watched",
                        "10 Lessons Watched",
                        "25 Lessons Watched",
                        "50 Lessons Watched",
                        "First Comment Written",
                        "3 Comments Written",
                        "5 Comments Written",
                        "10 Comments Written"
                    ],
                    "next_available_achievements" => [
                        "20 Comments Written"
                    ],
                    "current_badge" => "Advanced",
                    "next_badge" => "Master",
                    "remaining_to_unlock_next_badge" => 1
                ],
            ],
            [
                55, 55,
                [
                    "unlocked_achievements" => [
                        "First Lesson Watched",
                        "5 Lessons Watched",
                        "10 Lessons Watched",
                        "25 Lessons Watched",
                        "50 Lessons Watched",

                        "First Comment Written",
                        "3 Comments Written",
                        "5 Comments Written",
                        "10 Comments Written",
                        "20 Comments Written"
                    ],
                    "next_available_achievements" => [],
                    "current_badge" => "Master",
                    "next_badge" => "",
                    "remaining_to_unlock_next_badge" => 0
                ],
            ],
            [
                1000, 1000,
                [
                    "unlocked_achievements" => [
                        "First Lesson Watched",
                        "5 Lessons Watched",
                        "10 Lessons Watched",
                        "25 Lessons Watched",
                        "50 Lessons Watched",

                        "First Comment Written",
                        "3 Comments Written",
                        "5 Comments Written",
                        "10 Comments Written",
                        "20 Comments Written"
                    ],
                    "next_available_achievements" => [],
                    "current_badge" => "Master",
                    "next_badge" => "",
                    "remaining_to_unlock_next_badge" => 0
                ],
            ]
        ];

    }

    /**
     *TEST for new user
     * @return void
     */
    public function test_for_invalid_user_id()
    {
        $this->json('GET', "/users/99999/achievements")
            ->assertStatus(404);
    }

}
