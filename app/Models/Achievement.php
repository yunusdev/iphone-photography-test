<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Achievement extends Model
{
    use HasFactory;

    const LESSON = 'Lesson';
    const COMMENT = 'Comment';

    protected $fillable = ['name', 'group', 'number'];


    /**
     * @param string $group
     * @param int $number
     * @return Builder|Model|null
     */
    public static function findByGroupAndNumber(string $group, int $number): Builder|Model|null
    {
        return self::query()->where(['group' => $group, 'number' => $number])->first();
    }
}
