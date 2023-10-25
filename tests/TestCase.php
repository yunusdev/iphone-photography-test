<?php

namespace Tests;

use App\Events\CommentWritten;
use App\Events\LessonWatched;
use App\Models\Comment;
use App\Models\Lesson;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseTransactions;

    /**
     * @param $user
     * @param int $number -  numbers of Lessons to watch
     * @param bool $watched
     * @return mixed
     */
    public function watchLessons($user, int $number = 1, bool $watched = true): mixed
    {
        $lessons =  Lesson::factory()->count($number)->create()->each(function($lesson) use ($user, $watched): void {
            $user->lessons()->attach($lesson->id, ['watched' => $watched]);
            if (!$watched) return;
            event(new LessonWatched($lesson, $user));
        });
        return $lessons->last();
    }

    /**
     * @param $user
     * @param int $number - numbers of Comment to write
     * @return mixed
     */
    public function createComments($user, int $number = 1): mixed
    {
        $lastComment = null;

        for ($x = 0; $x < $number; $x++) {
            $lastComment = Comment::factory()->create(['user_id' => $user->id]);
            event(new CommentWritten($lastComment));
        }

        return $lastComment;
    }
}
